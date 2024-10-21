<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];

if ($varsesion == null || $varsesion == '') {
  header("Location: _sesion/login.php");
}
?>

<div class="modal fade" id="medicinas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <?php if (isset($_POST['id_medicina_actualizar'])) : ?>
          <h3 class="modal-title" id="exampleModalLabel">Actualizar medicina</h3>
        <?php else : ?>
          <h3 class="modal-title" id="exampleModalLabel">Agregar nueva medicina</h3>
        <?php endif; ?>
        <button type="button" class="btn btn-black" data-dismiss="modal">
          <i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <div class="modal-body">
        <form action="../includes/functions.php" method="POST">
          <?php if (isset($_POST['id_medicina_actualizar'])) : ?>
            <input type="hidden" name="id_medicina_actualizar" value="<?php echo $_POST['id_medicina_actualizar']; ?>">
          <?php endif; ?>
          <div class="form-group">
            <label for="nombre" class="form-label">Nombre*</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required <?php if (isset($_POST['nombre_medicina_actualizar'])) : ?>value="<?php echo $_POST['nombre_medicina_actualizar']; ?>" <?php endif; ?>>
          </div>
          <div class="form-group">
            <label for="descripcion" class="form-label">Descripción*</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required><?php if (isset($_POST['descripcion_medicina_actualizar'])) echo $_POST['descripcion_medicina_actualizar']; ?></textarea>
          </div>
          <div class="form-group">
            <label for="stock" class="form-label">Stock*</label>
            <input type="number" id="stock" name="stock" class="form-control" required <?php if (isset($_POST['stock_medicina_actualizar'])) : ?>value="<?php echo $_POST['stock_medicina_actualizar']; ?>" <?php endif; ?>>
          </div>

          <input type="hidden" name="accion" value="<?php echo isset($_POST['id_medicina_actualizar']) ? 'update_medicina' : 'insert_medicina'; ?>">
          <br>
          <div class="mb-3">
            <input type="submit" value="<?php echo isset($_POST['id_medicina_actualizar']) ? 'Actualizar' : 'Guardar'; ?>" id="register" class="btn btn-success" name="registrar">
            <a href="medicinas.php" class="btn btn-danger">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>