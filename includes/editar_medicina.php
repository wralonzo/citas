<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];
if ($varsesion == null || $varsesion = '') {
  header("Location: _sesion/login.php");
}

include "db.php";

$id = $_GET['id'];

// Consulta para obtener los detalles de la medicina
$consulta = "SELECT * FROM medicinas WHERE id = $id";
$resultado = mysqli_query($conexion, $consulta);
$medicina = mysqli_fetch_assoc($resultado);
?>

<?php include_once "header.php"; ?>

<form action="functions.php" method="POST">
  <div class="container">
    <div id="login-row" class="row justify-content-center align-items-center">
      <div id="login-column" class="col-md-6">
        <div id="login-box" class="col-md-12">
          <h3 class="text-center">Editar Medicina: <?php echo $medicina['nombre']; ?></h3>
          <br>
          <div class="form-group">
            <label for="nombre" class="form-label">Nombre*</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $medicina['nombre']; ?>" required>
          </div>

          <div class="form-group">
            <label for="descripcion" class="form-label">Descripción*</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required><?php echo $medicina['descripcion']; ?></textarea>
          </div>

          <div class="form-group">
            <label for="stock" class="form-label">Stock*</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?php echo $medicina['stock']; ?>" required>
          </div>

          <input type="hidden" name="accion" value="editar_medicina">
          <input type="hidden" name="id" value="<?php echo $id; ?>">

          <br>
          <div class="mb-3">
            <button type="submit" id="form" name="form" class="btn btn-success">Editar</button>
            <a href="../views/medicinas.php" class="btn btn-danger">Cancelar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<?php include_once "footer.php"; ?>