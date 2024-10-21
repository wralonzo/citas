<?php
// Seguridad de sesiones
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];

if ($varsesion == null || $varsesion = '') {

  header("Location: ../includes/_sesion/login.php");
  die();
}
?>





<?php
// historial_citas.php

// Incluye la configuración y la conexión a la base de datos
include "../includes/db.php";

// Obtén el ID del paciente de la URL
$id_paciente = $_GET['id_paciente'];

$result_paciente = mysqli_query($conexion, "SELECT * FROM pacientes WHERE id = $id_paciente");
$datos_paciente = mysqli_fetch_assoc($result_paciente);

$result = mysqli_query($conexion, "SELECT c.id, c.fecha, c.hora, c.id_paciente, c.examen_imagen, c.examen_pdf, c.hora, p.nombre, p.registro_hospitalario, es.nombre AS especialidad,
    d.nombres AS nombre_doctor, c.id_especialidad, c.observacion, c.fecha_registro, e.estado,
    GROUP_CONCAT(m.nombre) AS nombres_medicina
FROM 
    citas c 
LEFT JOIN pacientes p ON c.id_paciente = p.id                        
LEFT JOIN doctor d ON c.id_doctor = d.id
LEFT JOIN especialidades es ON c.id_especialidad = es.id
LEFT JOIN medicinas m ON FIND_IN_SET(m.id, REPLACE(c.id_medicina, ' ', ''))
LEFT JOIN estado e ON c.estado = e.id
GROUP BY c.id");

$citas = array(); 

while ($fila_cita = mysqli_fetch_assoc($result)) {
    $citas[] = $fila_cita; 
}

// Imprimir los nombres de las medicinas para cada cita
foreach ($citas as $cita) {
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Encabezado y enlaces a CSS y JavaScript (si es necesario) -->
  <!-- ... (código anterior) ... -->
  <script src="../js/jquery.min.js"></script>

</head>
<?php include "../includes/header.php"; ?>

<body id="page-top">

  <div class="container-fluid">
    <!-- Mostrar el historial de citas en una tabla -->
    <h3>Historial de Citas del Paciente</h3>
    <div class="row">
      <div class="col-md-6">
        <label for="registro_hospitalario">Registro Hospitalario:</label>
        <input type="text" id="registro_hospitalario" name="registro_hospitalario" class="form-control" value="<?php echo $datos_paciente['registro_hospitalario']; ?>" readonly>
      </div>
      <div class="col-md-6">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $datos_paciente['direccion']; ?>" readonly>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label for="pais">País:</label>
        <input type="text" id="pais" name="pais" class="form-control" value="<?php echo $datos_paciente['pais']; ?>" readonly>
      </div>
      <div class="col-md-6">
        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento" class="form-control" value="<?php echo $datos_paciente['departamento']; ?>" readonly>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label for="zona">Zona:</label>
        <input type="text" id="zona" name="zona" class="form-control" value="<?php echo $datos_paciente['zona']; ?>" readonly>
      </div>
      <div class="col-md-6">
        <label for="municipio">Municipio:</label>
        <input type="text" id="municipio" name="municipio" class="form-control" value="<?php echo $datos_paciente['municipio']; ?>" readonly>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $datos_paciente['nombre']; ?>" readonly>
      </div>
      <div class="col-md-6">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo $datos_paciente['telefono']; ?>" readonly>
      </div>
    </div> <br>
    <a name="" id="verHora" class="btn btn-primary" href="../views/citas.php" role="button">Salir</a>
    <td>
      <a name="" id="medicina" class="btn btn-primary abrir-citas" data-toggle="modal" data-target="#aggMedicinaModal" data-id="<?php echo $citas[0]['id']; ?>">+ Medicina</a>
    </td> <br>
    <table class="table table-bordered" id="historialTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>NumCita#</th>
          <th>Doctor que atendio</th>
          <th>Observacion </th>
          <th>Fecha y hora de visita</th>
          <th>Medicina</th>
          <th>Examenes</th>
          <!-- Otras columnas que desees mostrar -->
        </tr>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conexion, "SELECT c.id, c.fecha, c.hora, c.id_paciente, c.examen_imagen, c.examen_pdf, c.hora, c.id_medicina, p.nombre, p.registro_hospitalario, es.nombre AS especialidad,
        d.nombres AS nombre_doctor, c.id_especialidad, c.observacion, c.fecha_registro, m.nombre AS nombre_medicina, e.estado FROM 
        citas c 
        LEFT JOIN pacientes p ON c.id_paciente = p.id                        
        LEFT JOIN doctor d ON c.id_doctor = d.id
        LEFT JOIN especialidades es ON c.id_especialidad = es.id
        LEFT JOIN medicinas m ON c.id_medicina = m.id
        LEFT JOIN estado e ON c.estado= e.id  ");

        while ($fila_cita = mysqli_fetch_assoc($result)) :
        ?>
          <tr>
            <td><?php echo $fila_cita['id']; ?></td>
            <td><?php echo $fila_cita['nombre_doctor']; ?></td>
            <td><?php echo $fila_cita['observacion']; ?></td>
            <td><?php echo $fila_cita['fecha'] . ' ' . $fila_cita['hora']; ?></td>
            <td><?= $cita['nombres_medicina']; ?></td>
            <td>
              <?php if (!empty($fila_cita['examen_imagen'])) : ?>
                <div class="download-buttons">
                  <a href="../examen/<?php echo $fila_cita['examen_imagen']; ?>" download>
                    <button class="btn btn-success" title="Descargar Imagen">
                      <i class="fa fa-image"></i>
                    </button>
                  </a>
                  <?php if (!empty($fila_cita['examen_pdf'])) : ?>
                    <a href="../examen/<?php echo $fila_cita['examen_pdf']; ?>" download>
                      <button class="btn btn-info" title="Descargar PDF">
                        <i class="fa fa-file-pdf"></i>
                      </button>
                    </a>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </td>

          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <script src="../package/dist/sweetalert2.all.js"></script>
    <script src="../package/dist/sweetalert2.all.min.js"></script>
    <script src="../package/jquery-3.6.0.min.js"></script>
  </div>
  <script>
    $(document).ready(function() {
      // Manejar clic en el enlace de medicina
      $('.abrir-citas').on('click', function() {
        // Obtener el ID de la cita desde el enlace
        var idCita = $(this).data('id');

        // Establecer el ID de la cita en el campo del formulario
        $('#id_cita').val(idCita);

        // Restablecer el valor del campo cuando se cierra el modal
        $('#aggMedicinaModal').on('hidden.bs.modal', function() {
          $('#id_cita').val('');
        });
      });
    });
  </script>
  <?php include "../includes/footer.php"; ?>
  <?php include "../includes/form_cita.php"; ?>
  <?php include "../includes/form_aggMedicina.php"; ?>
  <script>
    $(document).ready(function() {
      $('.dropdown-toggle').dropdown();
    });
  </script>
</body>

</html>