<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

$inactividad = 1080000; // 3 horas

// Configurar parámetros de la sesión
ini_set('session.gc_maxlifetime', $inactividad);
ini_set('session.cookie_lifetime', $inactividad);

session_start();
//error_reporting(0);
ini_set('display_errors', 1);
// Verificar inactividad y renovar sesión
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactividad)) {
  session_unset();
  session_destroy();
  header('Location: login?timeout=1');
  exit();
}
// Actualizar tiempo de última actividad
$_SESSION['LAST_ACTIVITY'] = time();


//echo 'sesion :'.$_SESSION["iniciarSesion"];
if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "cinemaposx") {


  $rutas = array();
  /*=============================================
        CONTENIDO
        =============================================*/
  if (isset($_GET["ruta"])) {
    $rutas = explode("/", $_GET["ruta"]);

    if (
      $rutas[0] == "inicio" ||
      $rutas[0] == "cerrar" ||
      $rutas[0] == "ticket" ||
      $rutas[0] == "pos") 
      {

      
      include "modules/" . $rutas[0] . ".php";
    } else {

      include "modules/404.php";
    }
  } else {
    include "modules/inicio.php";
  }
} else {
  include "modules/login.php";
}
