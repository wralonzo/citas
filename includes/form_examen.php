<?php


session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];

if ($varsesion == null || $varsesion = '') {
  header("Location: _sesion/login.php");
}

?>

<div class="modal fade" id="agregarExamenes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Exámenes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="../includes/functions.php" method="POST" enctype="multipart/form-data">          
          <div class="form-group">
            <label for="examen_imagen">Examen Imagen:</label>
            <input type="file" name="examen_imagen" accept="image/*" required>
          </div>

          <div class="form-group">
            <label for="examen_pdf">Examen PDF:</label>
            <input type="file" name="examen_pdf" accept=".pdf" >
          </div>
          <input type="hidden" name="id_registro" id="id_registro" value="">
          <input type="hidden" name="accion" value="actualizar_examenes">
          <input type="submit" class="btn btn-success" value="Guardar Exámenes">
        </form>
      </div>
    </div>
  </div>
</div>

