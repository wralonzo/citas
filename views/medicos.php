<?php
// Seguridad de sesiones
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];

if ($varsesion == null || $varsesion = '') {
    header("Location: ../includes/_sesion/login.php");
    die();
}

include "../includes/db.php";

$sql = "SELECT doctor.cedula, doctor.nombres, especialidades.nombre AS especialidad, doctor.sexo, doctor.telefono, doctor.fecha, doctor.correo, doctor.fecha_registro 
        FROM doctor
        LEFT JOIN especialidades ON doctor.id_especialidad = especialidades.id";

$result = mysqli_query($conexion, $sql);
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
                <h6 class="m-0 font-weight-bold text-primary">Lista de Doctores</h6>
                <br>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#doctor">
                    <span class="glyphicon glyphicon-plus"></span> Agregar doctor <i class="fa fa-user-md" aria-hidden="true"></i></a></button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Folio#</th>
                                <th>Nombre</th>
                                <th>Especialidad</th>
                                <th>Sexo</th>
                                <th>Telefono#</th>
                                <th>Fecha_Nacimiento</th>
                                <th>Correo</th>
                                <th>Fecha_Registro</th>
                                <th>Acciones..</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($fila = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?php echo $fila['cedula']; ?></td>
                                    <td><?php echo $fila['nombres']; ?></td>
                                    <td><?php echo $fila['especialidad']; ?></td>
                                    <td><?php echo $fila['sexo']; ?></td>
                                    <td><?php echo $fila['telefono']; ?></td>
                                    <td><?php echo $fila['fecha']; ?></td>
                                    <td><?php echo $fila['correo']; ?></td>
                                    <td><?php echo $fila['fecha_registro']; ?></td>
                                    <td>
                                        <a class="btn btn-warning" href="../includes/editar_doctor.php?id=<?php echo $fila['id'] ?>">
                                            <i class="fa fa-edit "></i>
                                        </a>
                                        <a href="../includes/eliminar_doctor.php?id=<?php echo $fila['id'] ?>" class="btn btn-danger btn-del">
                                            <i class="fa fa-trash "></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <script>
                        $('.btn-del').on('click', function(e) {
                            e.preventDefault();
                            const href = $(this).attr('href')

                            Swal.fire({
                                title: 'Estas seguro de eliminar a este doctor?',
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
                                            'El usuario fue eliminado.',
                                            'success'
                                        )
                                    }

                                    document.location.href = href;
                                }
                            })

                        })
                    </script>
                    <script src="../package/dist/sweetalert2.all.js"></script>
                    <script src="../package/dist/sweetalert2.all.min.js"></script>
                    <script src="../package/jquery-3.6.0.min.js"></script>
                </div>
            </div>
        </div>
    </div>

    <?php include "../includes/footer.php"; ?>
    <?php include "../includes/form_doctor.php"; ?>

</body>

</html>
