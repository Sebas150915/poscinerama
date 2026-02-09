<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

session_start();
//usar PDO PARA LA CONEXION
$username = $_POST['username'];
$password = $_POST['password'];
$password = hash('sha256', $password);

$sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
//ejecutar la consulta
$result = $connect->query($sql);
$fila = $result->fetch_assoc(); 


if ($fila) {
    $_SESSION["iniciarSesion"] = "cinemaposx";
    $_SESSION["username"] = $fila["username"];
    echo "success";
} else {
    echo "error";
}
  
