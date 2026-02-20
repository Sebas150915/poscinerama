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
            $idFuncion = intval($input['id_funcion'] ?? 0);
            $idCartelera = intval($input['id_cartelera'] ?? 0);
            $idHora = intval($input['id_hora'] ?? 0);
            $total = $input['total'];
            $tipoComprobante = $input['tipo_comprobante'];
            $medioPago = $input['medio_pago'];
            $items = $input['items'];
            $codigo = "POS-" . date('YmdHis') . rand(10, 99);
            $idLocal = $_SESSION['id_local'];
            $clienteDoc = $input['cliente_doc'] ?? '';
            $clienteNombre = $input['cliente_nombre'] ?? '';
            if ($idFuncion <= 0) {
                if ($idCartelera <= 0 || $idHora <= 0) {
                    throw new Exception("Falta identificar función");
                }
                $stmtC = $connect->prepare("SELECT pelicula, sala FROM tbl_cartelera WHERE id = ?");
                $stmtC->execute([$idCartelera]);
                $rowC = $stmtC->fetch(PDO::FETCH_ASSOC);
                if (!$rowC) {
                    throw new Exception("Cartelera no encontrada");
                }
                $fechaActual = date('Y-m-d');
                $stmtF = $connect->prepare("SELECT id FROM tbl_funciones WHERE id_pelicula = ? AND id_sala = ? AND id_hora = ? AND fecha = ? LIMIT 1");
                $stmtF->execute([$rowC['pelicula'], $rowC['sala'], $idHora, $fechaActual]);
                $func = $stmtF->fetch(PDO::FETCH_ASSOC);
                if ($func) {
                    $idFuncion = intval($func['id']);
                } else {
                    $stmtIns = $connect->prepare("INSERT INTO tbl_funciones (id_pelicula, id_sala, id_hora, fecha) VALUES (?, ?, ?, ?)");
                    $stmtIns->execute([$rowC['pelicula'], $rowC['sala'], $idHora, $fechaActual]);
                    $idFuncion = intval($connect->lastInsertId());
                }
            }
            $connect->beginTransaction();
            $qVenta = "INSERT INTO tbl_ventas (codigo, id_local, id_usuario, id_funcion, total, medio_pago, tipo_comprobante, cliente_doc, cliente_nombre, origen, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'BOLETERIA', 'PAGADO')";
            $stmtVenta = $connect->prepare($qVenta);
            $stmtVenta->execute([$codigo, $idLocal, $idUsuario, $idFuncion, $total, $medioPago, $tipoComprobante, $clienteDoc, $clienteNombre]);
            $idVenta = $connect->lastInsertId();
            $stmtFunc = $connect->prepare("SELECT id_sala FROM tbl_funciones WHERE id = ?");
            $stmtFunc->execute([$idFuncion]);
            $funcData = $stmtFunc->fetch(PDO::FETCH_ASSOC);
            $idSala = $funcData['id_sala'];
            $colsSA = [];
            try {
                $resSA = $connect->query("SHOW COLUMNS FROM tbl_sala_asiento");
                $colsSA = $resSA->fetchAll(PDO::FETCH_COLUMN, 0);
            } catch (PDOException $e) {}
            $numCol = in_array('numero', $colsSA) ? 'numero' : 'columna';
            $colsB = [];
            try {
                $resB = $connect->query("SHOW COLUMNS FROM tbl_boletos");
                $colsB = $resB->fetchAll(PDO::FETCH_COLUMN, 0);
            } catch (PDOException $e) {}
            $hasCart = in_array('id_cartelera', $colsB);
            $hasHora = in_array('id_hora', $colsB);
            foreach ($items as $item) {
                $fila = substr($item['seat'], 0, 1);
                $numero = substr($item['seat'], 1);
                $stmtAsiento = $connect->prepare("SELECT id FROM tbl_sala_asiento WHERE idsala = ? AND fila = ? AND $numCol = ?");
                $stmtAsiento->execute([$idSala, $fila, $numero]);
                $asiento = $stmtAsiento->fetch(PDO::FETCH_ASSOC);
                if (!$asiento) {
                    throw new Exception("Asiento {$item['seat']} no encontrado");
                }
                $colsInsert = "id_venta, id_asiento, fila, columna, letra, numero, id_tarifa, precio, estado";
                $placeholders = "?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO'";
                $params = [$idVenta, $asiento['id'], $fila, $numero, $fila, $numero, $item['tarifa_id'], $item['precio']];
                if ($hasCart) {
                    $colsInsert .= ", id_cartelera";
                    $placeholders .= ", ?";
                    $params[] = $idCartelera;
                }
                if ($hasHora) {
                    $colsInsert .= ", id_hora";
                    $placeholders .= ", ?";
                    $params[] = $idHora;
                }
                $qBoleto = "INSERT INTO tbl_boletos ($colsInsert) VALUES ($placeholders)";
                $stmtBoleto = $connect->prepare($qBoleto);
                $stmtBoleto->execute($params);
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

    case 'searchContributor':
        try {
            $tipo = $_GET['tipo'] ?? $_POST['tipo'] ?? '';
            $numDoc = $_GET['num_doc'] ?? $_POST['num_doc'] ?? '';
            $apiKey = $_GET['api_key'] ?? $_POST['api_key'] ?? '';
            if ($numDoc === '') {
                throw new Exception("Número de documento requerido");
            }
            $tipoDocCode = 0;
            if (is_numeric($tipo)) {
                $tipoDocCode = intval($tipo);
            } else {
                $t = strtoupper(trim($tipo));
                if ($t === 'DNI') $tipoDocCode = 1;
                else if ($t === 'RUC') $tipoDocCode = 6;
                else $tipoDocCode = 0;
            }
            $data = [
                'tipo_doc' => $tipoDocCode,
                'num_doc' => $numDoc,
                'nombre' => '',
                'direccion' => '',
                'ubigeo' => '',
                'distrito' => '',
                'provincia' => '',
                'departamento' => '',
                'correo' => '',
                'telefono' => ''
            ];
            $existsStmt = $connect->prepare("SELECT * FROM tbl_contribuyente WHERE num_doc = ? LIMIT 1");
            $existsStmt->execute([$numDoc]);
            $existing = $existsStmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                echo json_encode(['status' => 'success', 'data' => $existing, 'source' => 'db']);
                break;
            }
            if ($tipoDocCode === 6) {
                if ($apiKey === '') {
                    throw new Exception("api_key requerido para RUC");
                }
                $url = "https://www.smartbase.club/sunat/ruc2.php?ruc=" . urlencode($numDoc) . "&api_key=" . urlencode($apiKey);
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT => 10
                ]);
                $resp = curl_exec($ch);
                if ($resp === false) {
                    throw new Exception("Error consultando RUC: " . curl_error($ch));
                }
                curl_close($ch);
                $json = @json_decode($resp, true);
                if (is_array($json)) {
                    $data['nombre'] = $json['razon_social'] ?? $json['nombre'] ?? '';
                    $data['direccion'] = $json['direccion'] ?? ($json['domicilio_fiscal'] ?? '');
                    $data['ubigeo'] = $json['ubigeo'] ?? '';
                    $data['distrito'] = $json['distrito'] ?? '';
                    $data['provincia'] = $json['provincia'] ?? '';
                    $data['departamento'] = $json['departamento'] ?? '';
                }
            } else if ($tipoDocCode === 1) {
                $url = "http://smartbase.club/webservices/dni.php?dni=" . urlencode($numDoc);
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT => 10
                ]);
                $resp = curl_exec($ch);
                if ($resp === false) {
                    throw new Exception("Error consultando DNI: " . curl_error($ch));
                }
                curl_close($ch);
                $json = @json_decode($resp, true);
                if (is_array($json)) {
                    $nombre = '';
                    if (isset($json['nombre'])) $nombre = $json['nombre'];
                    else {
                        $nombres = $json['nombres'] ?? '';
                        $ap = $json['apellido_paterno'] ?? '';
                        $am = $json['apellido_materno'] ?? '';
                        $nombre = trim(($ap . ' ' . $am . ' ' . $nombres));
                    }
                    $data['nombre'] = $nombre;
                }
            }
            $ins = $connect->prepare("INSERT INTO tbl_contribuyente (idempresa, tipo_doc, num_doc, direccion, ubigeo, distrito, provincia, departamento, correo, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $idEmpresa = $_SESSION['id_local'] ?? 0;
            $ins->execute([
                $idEmpresa,
                $data['tipo_doc'],
                $data['num_doc'],
                $data['direccion'],
                $data['ubigeo'],
                $data['distrito'],
                $data['provincia'],
                $data['departamento'],
                $data['correo'],
                $data['telefono']
            ]);
            $idContrib = $connect->lastInsertId();
            $sel = $connect->prepare("SELECT * FROM tbl_contribuyente WHERE id = ?");
            $sel->execute([$idContrib]);
            $row = $sel->fetch(PDO::FETCH_ASSOC);
            if ($data['nombre'] !== '') {
                try {
                    $connect->prepare("UPDATE tbl_contribuyente SET direccion = ?, distrito = ?, provincia = ?, departamento = ? WHERE id = ?")
                        ->execute([$data['direccion'], $data['distrito'], $data['provincia'], $data['departamento'], $idContrib]);
                    $row['direccion'] = $data['direccion'];
                    $row['distrito'] = $data['distrito'];
                    $row['provincia'] = $data['provincia'];
                    $row['departamento'] = $data['departamento'];
                } catch (Exception $e) {}
            }
            $row['nombre'] = $data['nombre'];
            echo json_encode(['status' => 'success', 'data' => $row, 'source' => 'api']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'getContributor':
        try {
            $numDoc = $_GET['num_doc'] ?? '';
            if ($numDoc === '') {
                throw new Exception("Número de documento requerido");
            }
            $stmt = $connect->prepare("SELECT * FROM tbl_contribuyente WHERE num_doc = ? LIMIT 1");
            $stmt->execute([$numDoc]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                echo json_encode(['status' => 'success', 'data' => $row]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Contribuyente no encontrado']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
        break;
}
