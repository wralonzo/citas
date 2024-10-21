<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];

if ($varsesion == null || $varsesion == '') {
    header("Location: _sesion/login.php");
    exit();
}

include "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM medicinas WHERE id = '$id'");
    if ($query) {
        header('Location: ../views/medicinas.php?m=1');
        exit();
    } else {
        echo "Error al eliminar la medicina.";
    }
} else {
    echo "ID de medicina no proporcionado.";
}
?>
