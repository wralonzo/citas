<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];

if ($varsesion == null || $varsesion == '') {
  header("Location: ../includes/_sesion/login.php");
  die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <script src="../js/jquery.min.js"></script>
</head>
<?php include "../includes/header.php"; ?>

<body id="page-top">
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Medicinas</h6>
        <br>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#medicinas">
          <i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar medicina
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID Medicina</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <?php
            include "../includes/db.php";
            $resultMedicinas = mysqli_query($conexion, "SELECT * FROM medicinas");
            while ($filaMedicina = mysqli_fetch_assoc($resultMedicinas)) :
            ?>
              <tr>
                <td><?php echo $filaMedicina['id']; ?></td>
                <td><?php echo $filaMedicina['nombre']; ?></td>
                <td><?php echo $filaMedicina['descripcion']; ?></td>
                <td><?php echo $filaMedicina['stock']; ?></td>
                <td>
                  <!-- Agrega aquí tus botones de acciones -->
                  <a class="btn btn-warning" href="../includes/editar_medicina.php?id=<?php echo $filaMedicina['id'] ?>">
                    <i class="fa fa-edit"></i>
                  </a>
                  <a href="../includes/eliminar_medicina.php?id=<?php echo $filaMedicina['id'] ?>" class="btn btn-danger btn-del">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php include "../includes/footer.php"; ?>
  <?php include "../includes/form_medicina.php"; ?>
  <?php include "../includes/form_examen.php"; ?>
  <script src="../package/dist/sweetalert2.all.js"></script>
  <script src="../package/dist/sweetalert2.all.min.js"></script>
  <script>
    $('.btn-del').on('click', function(e) {
      e.preventDefault();
      const href = $(this).attr('href')

      Swal.fire({
        title: 'Estas seguro de eliminar esta medicina?',
        text: "¡No podrás revertir esto!!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar!',
      }).then((result) => {
        if (result.value) {
          if (result.isConfirmed) {
            Swal.fire(
              'Eliminado!',
              'La medicina fue eliminado.',
              'success'
            )
          }

          document.location.href = href;
        }
      })

    })
  </script>
</body>

</html>