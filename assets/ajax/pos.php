<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no válida']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getBillBoard':
        try {
            $idLocal = $_SESSION['id_local'];
            $fechaActual = date('Y-m-d');

            // Si el local es null o 0, tal vez es un superadmin, pero para POS usualmente hay local
            $whereLocal = $idLocal ? "AND c.local = :idLocal" : "";

            $query = "
                SELECT 
                    p.id as id_pelicula,
                    p.nombre,
                    p.duracion,
                    p.censura,
                    p.img,
                    c.id as id_cartelera,
                    c.sala as id_sala,
                    s.nombre as nombre_sala,
                    GROUP_CONCAT(CONCAT(h.id, '|', h.hora, '|', IFNULL(f.id, 0)) ORDER BY h.hora SEPARATOR ',') as horarios
                FROM tbl_cartelera c
                JOIN tbl_pelicula p ON c.pelicula = p.id
                JOIN tbl_sala s ON c.sala = s.id
                JOIN tbl_hora h ON (h.id = c.id_hora_f1 OR h.id = c.id_hora_f2 OR h.id = c.id_hora_f3 OR h.id = c.id_hora_f4 OR h.id = c.id_hora_f5 OR h.id = c.id_hora_f6)
                LEFT JOIN tbl_funciones f ON (f.id_pelicula = p.id AND f.id_sala = s.id AND f.id_hora = h.id AND f.fecha = :fechaActual)
                WHERE c.estado = '1' $whereLocal
                GROUP BY p.id, c.id
            ";

            $stmt = $connect->prepare($query);
            $stmt->bindParam(':fechaActual', $fechaActual);
            if ($idLocal) {
                $stmt->bindParam(':idLocal', $idLocal);
            }
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Procesar horarios
            foreach ($movies as &$movie) {
                $hArr = explode(',', $movie['horarios']);
                $formattedH = [];
                foreach ($hArr as $h) {
                    $parts = explode('|', $h);
                    $idH = $parts[0];
                    $timeH = $parts[1];
                    $idFunc = $parts[2] ?? 0;
                    $formattedH[] = ['id' => $idH, 'hora' => $timeH, 'id_funcion' => $idFunc];
                }
                $movie['horarios'] = $formattedH;
            }

            echo json_encode(['status' => 'success', 'data' => $movies]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'getTariffs':
        try {
            $stmt = $connect->prepare("SELECT id, nombre as name, precio as price FROM tbl_tarifa WHERE estado = '1'");
            $stmt->execute();
            $tariffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $tariffs]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'getOccupiedSeats':
        try {
            $idCartelera = $_GET['id_cartelera'] ?? 0;
            $idHora = $_GET['id_hora'] ?? 0;

            // Buscar boletos pagados o pendientes para esta función
            $query = "
                SELECT sa.fila, sa.numero
                FROM tbl_boletos b
                JOIN tbl_sala_asiento sa ON b.id_asiento = sa.id
                JOIN tbl_ventas v ON b.id_venta = v.id
                WHERE b.id_cartelera = ? AND b.id_hora = ? 
                AND v.estado IN ('PAGADO', 'PENDIENTE')
            ";
            $stmt = $connect->prepare($query);
            $stmt->execute([$idCartelera, $idHora]);
            $occupied = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $formatted = [];
            foreach ($occupied as $o) {
                $formatted[] = $o['fila'] . $o['numero'];
            }

            echo json_encode(['status' => 'success', 'data' => $formatted]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'processSale':
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                throw new Exception("Datos de venta no recibidos");
            }

            $idUsuario = $_SESSION['id'];
            $idFuncion = $input['id_funcion'];
            $total = $input['total'];
            $tipoComprobante = $input['tipo_comprobante'];
            $medioPago = $input['medio_pago'];
            $items = $input['items']; // array( {seat: 'A1', tarifa_id: 1, precio: 15.00} )

            // Generar código de venta
            $codigo = "POS-" . date('YmdHis') . rand(10, 99);
            $idLocal = $_SESSION['id_local'];
            $clienteDoc = $input['cliente_doc'] ?? '';
            $clienteNombre = $input['cliente_nombre'] ?? '';

            $connect->beginTransaction();

            // 1. Insertar en tbl_ventas
            $qVenta = "INSERT INTO tbl_ventas (codigo, id_local, id_usuario, id_funcion, total, medio_pago, tipo_comprobante, cliente_doc, cliente_nombre, origen, estado) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'BOLETERIA', 'PAGADO')";
            $stmtVenta = $connect->prepare($qVenta);
            $stmtVenta->execute([
                $codigo,
                $idLocal,
                $idUsuario,
                $idFuncion,
                $total,
                $medioPago,
                $tipoComprobante,
                $clienteDoc,
                $clienteNombre
            ]);
            $idVenta = $connect->lastInsertId();

            // Obtener id_sala de la función para buscar los asientos
            $stmtFunc = $connect->prepare("SELECT id_sala FROM tbl_funciones WHERE id = ?");
            $stmtFunc->execute([$idFuncion]);
            $funcData = $stmtFunc->fetch(PDO::FETCH_ASSOC);
            $idSala = $funcData['id_sala'];

            // 2. Insertar en tbl_boletos
            foreach ($items as $item) {
                // Resolver id_asiento
                // El seat viene como 'A1', 'A2', etc.
                $fila = substr($item['seat'], 0, 1);
                $numero = substr($item['seat'], 1);

                $qAsiento = "SELECT id FROM tbl_sala_asiento WHERE idsala = ? AND fila = ? AND columna = ?";
                $stmtAsiento = $connect->prepare($qAsiento);
                $stmtAsiento->execute([$idSala, $fila, $numero]);
                $asiento = $stmtAsiento->fetch(PDO::FETCH_ASSOC);

                if (!$asiento) {
                    throw new Exception("Asiento {$item['seat']} no encontrado en la sala");
                }

                $qBoleto = "INSERT INTO tbl_boletos (id_venta, id_asiento, fila, columna, letra, numero, id_tarifa, precio, estado) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO')";
                $stmtBoleto = $connect->prepare($qBoleto);
                $stmtBoleto->execute([
                    $idVenta,
                    $asiento['id'],
                    $fila,
                    $numero,
                    $fila, // letra
                    $numero,
                    $item['tarifa_id'],
                    $item['precio']
                ]);
            }

            $connect->commit();
            echo json_encode(['status' => 'success', 'message' => 'Venta procesada correctamente', 'id_venta' => $idVenta, 'codigo' => $codigo]);
        } catch (Exception $e) {
            if ($connect->inTransaction()) {
                $connect->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'getSalesHistory':
        try {
            $idUsuario = $_SESSION['id'];
            $query = "
                SELECT 
                    v.*, 
                    p.nombre as pelicula_nombre,
                    h.hora as showtime_hora
                FROM tbl_ventas v
                JOIN tbl_funciones f ON v.id_funcion = f.id
                JOIN tbl_pelicula p ON f.id_pelicula = p.id
                JOIN tbl_hora h ON f.id_hora = h.id
                WHERE v.id_usuario = ? AND DATE(v.created_at) = CURDATE()
                ORDER BY v.created_at DESC
            ";
            $stmt = $connect->prepare($query);
            $stmt->execute([$idUsuario]);
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $sales]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'getSaleDetails':
        try {
            $idVenta = $_GET['id_venta'] ?? 0;

            // 1. Datos de la venta
            $stmtV = $connect->prepare("
                SELECT v.*, p.nombre as pelicula_nombre, s.nombre as nombre_sala, h.hora
                FROM tbl_ventas v
                JOIN tbl_funciones f ON v.id_funcion = f.id
                JOIN tbl_pelicula p ON f.id_pelicula = p.id
                JOIN tbl_sala s ON f.id_sala = s.id
                JOIN tbl_hora h ON f.id_hora = h.id
                WHERE v.id = ?
            ");
            $stmtV->execute([$idVenta]);
            $venta = $stmtV->fetch(PDO::FETCH_ASSOC);

            // 2. Boletos
            $stmtB = $connect->prepare("
                SELECT b.*, t.nombre as tarifa_nombre
                FROM tbl_boletos b
                JOIN tbl_tarifa t ON b.id_tarifa = t.id
                WHERE b.id_venta = ?
            ");
            $stmtB->execute([$idVenta]);
            $boletos = $stmtB->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'venta' => $venta, 'boletos' => $boletos]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'cancelSale':
        try {
            $idVenta = $_POST['id_venta'] ?? 0;

            $connect->beginTransaction();

            // 1. Anular venta
            $stmtV = $connect->prepare("UPDATE tbl_ventas SET estado = 'ANULADO' WHERE id = ?");
            $stmtV->execute([$idVenta]);

            // 2. Anular boletos
            $stmtB = $connect->prepare("UPDATE tbl_boletos SET estado = 'ANULADO' WHERE id_venta = ?");
            $stmtB->execute([$idVenta]);

            $connect->commit();
            echo json_encode(['status' => 'success', 'message' => 'Venta anulada correctamente']);
        } catch (Exception $e) {
            if ($connect->inTransaction()) $connect->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
        break;
}
