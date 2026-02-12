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
            $idLocal = $_SESSION['id_local'] ?? null;
            $idSala = $_GET['id_sala'] ?? null;
            $tariffs = [];
            $dayIndex = intval(date('N'));
            $dayMap = [1 => 'l', 2 => 'm', 3 => 'x', 4 => 'j', 5 => 'v', 6 => 's', 7 => 'd'];
            $dayCol = $dayMap[$dayIndex];

            // Detectar tabla disponible: tbl_tarifas (por local) o tbl_tarifa (global)
            $table = null;
            try {
                $chk = $connect->query("SHOW TABLES LIKE 'tbl_tarifas'");
                if ($chk && $chk->rowCount() > 0) $table = 'tbl_tarifas';
            } catch (PDOException $e) {}
            if ($table === null) $table = 'tbl_tarifa';

            if ($table === 'tbl_tarifas') {
                // Obtener columnas para normalizar
                $cols = [];
                try {
                    $res = $connect->query("SHOW COLUMNS FROM tbl_tarifas");
                    $cols = $res->fetchAll(PDO::FETCH_COLUMN, 0);
                } catch (PDOException $e) {}

                // Determinar columna de local y construir consulta
                $localCols = ['id_local','local','idlocal','sede'];
                $priceCols = ['precio','monto','valor','importe','costo'];
                $nameCols = ['nombre','name','descripcion','tarifa'];
                $stateCols = ['estado','activado','activo','habilitado'];
                $dayColUse = null;
                if (in_array($dayCol, $cols)) { $dayColUse = $dayCol; }
                else { $upperDay = strtoupper($dayCol); if (in_array($upperDay, $cols)) { $dayColUse = $upperDay; } }

                $localCol = null;
                foreach ($localCols as $c) if (in_array($c, $cols)) { $localCol = $c; break; }

                $sql = "SELECT * FROM tbl_tarifas";
                $params = [];
                if ($idLocal && $localCol) {
                    $sql .= " WHERE $localCol = ?";
                    $params[] = $idLocal;
                }
                $stmt = $connect->prepare($sql);
                $stmt->execute($params);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Normalizar y filtrar activas
                foreach ($rows as $r) {
                    $active = null;
                    foreach ($stateCols as $sc) {
                        if (array_key_exists($sc, $r)) {
                            $val = strtoupper((string)$r[$sc]);
                            $active = in_array($val, ['1','SI','SÍ','ACTIVO','HABILITADO','TRUE']);
                            break;
                        }
                    }
                    $activeDay = true;
                    if ($dayColUse && array_key_exists($dayColUse, $r)) {
                        $valDay = strtoupper((string)$r[$dayColUse]);
                        $activeDay = in_array($valDay, ['1','SI','SÍ','TRUE']);
                    }
                    if ($active === false || !$activeDay) continue;

                    $name = null; foreach ($nameCols as $nc) { if (isset($r[$nc]) && $r[$nc] !== '') { $name = $r[$nc]; break; } }
                    $price = null; foreach ($priceCols as $pc) { if (isset($r[$pc]) && is_numeric($r[$pc])) { $price = (float)$r[$pc]; break; } }
                    if ($name !== null && $price !== null) {
                        $tariffs[] = ['id' => $r['id'] ?? null, 'name' => $name, 'price' => $price];
                    }
                }
            }

            // Si aún vacío y existe relación por sala
            if (empty($tariffs) && $idSala) {
                try {
                    $qSala = "
                        SELECT t.id, t.nombre as name, t.precio as price
                        FROM tbl_tarifa_sala ts
                        JOIN tbl_tarifa t ON ts.id_tarifa = t.id
                        WHERE ts.id_sala = ? AND t.estado = '1'
                    ";
                    $stSala = $connect->prepare($qSala);
                    $stSala->execute([$idSala]);
                    $tariffs = $stSala->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {}
            }

            // Fallback a tabla global si sigue vacío
            if (empty($tariffs)) {
                try {
                    $colsG = [];
                    try {
                        $resG = $connect->query("SHOW COLUMNS FROM tbl_tarifa");
                        $colsG = $resG->fetchAll(PDO::FETCH_COLUMN, 0);
                    } catch (PDOException $e) {}
                    $localColsG = ['id_local','local','idlocal','sede'];
                    $localColG = null;
                    foreach ($localColsG as $c) if (in_array($c, $colsG)) { $localColG = $c; break; }
                    $dayColUseG = null;
                    if (in_array($dayCol, $colsG)) { $dayColUseG = $dayCol; }
                    else { $upperDay = strtoupper($dayCol); if (in_array($upperDay, $colsG)) { $dayColUseG = $upperDay; } }
                    $hasEstado = in_array('estado', $colsG);
                    $sqlG = "SELECT id, nombre as name, precio as price FROM tbl_tarifa WHERE 1=1";
                    $paramsG = [];
                    if ($hasEstado) $sqlG .= " AND estado = '1'";
                    if ($idLocal && $localColG) { $sqlG .= " AND $localColG = ?"; $paramsG[] = $idLocal; }
                    if ($dayColUseG) { $sqlG .= " AND `$dayColUseG` = '1'"; }
                    $stmt = $connect->prepare($sqlG);
                    $stmt->execute($paramsG);
                    $tariffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $tariffs = [];
                }
            }

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

    case 'getSeatLayout':
        try {
            $idFuncion = $_GET['id_funcion'] ?? 0;
            $idCartelera = $_GET['id_cartelera'] ?? 0;
            $idSala = null;

            if ($idFuncion) {
                $stmtF = $connect->prepare("SELECT id_sala FROM tbl_funciones WHERE id = ?");
                $stmtF->execute([$idFuncion]);
                $rowF = $stmtF->fetch(PDO::FETCH_ASSOC);
                if ($rowF) $idSala = $rowF['id_sala'];
            }
            if (!$idSala && $idCartelera) {
                $stmtC = $connect->prepare("SELECT sala FROM tbl_cartelera WHERE id = ?");
                $stmtC->execute([$idCartelera]);
                $rowC = $stmtC->fetch(PDO::FETCH_ASSOC);
                if ($rowC) $idSala = $rowC['sala'];
            }
            if (!$idSala) {
                throw new Exception("No se pudo determinar la sala para la función/cartelera");
            }

            // Intentar con columna 'numero'
            $layout = [];
            try {
                $stmtS = $connect->prepare("SELECT fila, numero FROM tbl_sala_asiento WHERE idsala = ? ORDER BY fila ASC, numero ASC");
                $stmtS->execute([$idSala]);
                $seats = $stmtS->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Fallback si columna se llama 'columna'
                $stmtS = $connect->prepare("SELECT fila, columna AS numero FROM tbl_sala_asiento WHERE idsala = ? ORDER BY fila ASC, columna ASC");
                $stmtS->execute([$idSala]);
                $seats = $stmtS->fetchAll(PDO::FETCH_ASSOC);
            }

            foreach ($seats as $s) {
                $fila = $s['fila'];
                $num = intval($s['numero']);
                if (!isset($layout[$fila])) $layout[$fila] = [];
                $layout[$fila][] = $num;
            }

            // Ordenar números por fila
            foreach ($layout as $fila => $nums) {
                sort($layout[$fila], SORT_NUMERIC);
            }

            // Ordenar filas alfabéticamente
            ksort($layout);

            echo json_encode(['status' => 'success', 'data' => ['id_sala' => $idSala, 'layout' => $layout]]);
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
