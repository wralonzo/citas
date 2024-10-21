<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];
if ($varsesion == null || $varsesion = '') {
  header("Location: _sesion/login.php");
}

include("db.php");

// Obtener el nombre proporcionado por la solicitud GET
$nombre = $_GET["nombre"];

// Convertir a minúsculas
$nombre = strtolower($nombre);

// Consulta para obtener sugerencias de nombres de medicinas que coincidan con el nombre proporcionado
$consulta = "SELECT id, nombre FROM medicinas WHERE LOWER(nombre) LIKE '%$nombre%'";

$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$medicinas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Devolver las sugerencias en formato JSON
echo json_encode($medicinas);

// Cerrar la conexión
mysqli_close($conexion);

?>
