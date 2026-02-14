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
        INICIO HEAD: CSS Y JS NECESARIOS
  =============================================*/
  ?>
  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinerama POS</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Saira+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tu JS principal (si existe) -->
    <script src="assets/js/app.js"></script>
  </head>
  <body>
  <?php
  /*=============================================
        CONTENIDO
  =============================================*/
  if (isset($_GET["ruta"])) {
    $rutas = explode("/", $_GET["ruta"]);
    if (
      $rutas[0] == "inicio" ||
      $rutas[0] == "cerrar" ||
      $rutas[0] == "ticket" ||
      $rutas[0] == "pos") {
      include "modules/" . $rutas[0] . ".php";
    } else {
      include "modules/404.php";
    }
  } else {
    include "modules/inicio.php";
  }
  ?>
  </body>
  </html>
  <?php
} else {
  include "modules/login.php";
}
