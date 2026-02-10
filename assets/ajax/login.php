<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor ingrese usuario y contraseña.']);
        exit;
    }

    try {
        // En este sistema las contraseñas suelen ser texto plano o MD5, 
        // pero usaremos el estándar moderno si existe. 
        // Por ahora buscaremos el usuario.
        $stmt = $connect->prepare("SELECT * FROM tbl_usuarios WHERE usuario = ? AND estado = '1'");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verificar contraseña (ajustar si el sistema usa MD5 o texto plano)
            // Intentaremos password_verify primero, si falla comparamos directo (para debugeo)
            $auth = false;
            if (password_verify($password, $user['password'])) {
                $auth = true;
            } else if ($password === $user['password']) {
                $auth = true;
            }

            if ($auth) {
                // Login Success
                $_SESSION['iniciarSesion'] = 'cinemaposx';
                $_SESSION['id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['id_local'] = $user['id_local'];

                if ($user['id_local']) {
                    $lStmt = $connect->prepare("SELECT nombre FROM tbl_locales WHERE id = ?");
                    $lStmt->execute([$user['id_local']]);
                    $_SESSION['local_nombre'] = $lStmt->fetchColumn();
                }

                $_SESSION['permiso_boleteria'] = $user['permiso_boleteria'] ?? 1;
                $_SESSION['permiso_dulceria'] = $user['permiso_dulceria'] ?? 1;

                session_regenerate_id(true);

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login exitoso',
                    'user' => [
                        'nombre' => $user['nombre'],
                        'rol' => $user['rol'],
                        'local' => $_SESSION['local_nombre'] ?? 'General'
                    ]
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error de sistema: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
