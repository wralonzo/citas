<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("db.php");

if (isset($_POST['accion'])) {
  switch ($_POST['accion']) {
      //casos de registros

    case 'acceso_user';
      acceso_user();
      break;

    case 'insert_doctor':
      insert_doctor();
      break;

    case 'insert_cita':
      insert_cita();
      break;

    case 'actualizar_examenes':
      actualizar_examenes();
      break;

    case 'insert_esp':
      insert_esp();
      break;

    case 'insert_horario':
      insert_horario();
      break;

    case 'insert_paciente':
      insert_paciente();
      break;

    case 'editar_user':
      editar_user();
      break;

    case 'editar_paciente':
      editar_paciente();
      break;

    case 'editar_esp':
      editar_esp();
      break;

    case 'editar_doctor':
      editar_doctor();
      break;

    case 'editar_hora':
      editar_hora();
      break;

    case 'editar_cita':
      editar_cita();
      break;

    case 'insert_medicina':
      insertarMedicina();
      break;

    case 'editar_medicina':
      editar_medicina();
      break;

    case 'insertarMedicamentos':
      insertarMedicamentos();
      break;
  }
}

function insertarMedicina()
{
  include("db.php");

  $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
  $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
  $stock = mysqli_real_escape_string($conexion, $_POST['stock']);

  $insertar = mysqli_query($conexion, "INSERT INTO medicinas (nombre, descripcion, stock) VALUES ('$nombre', '$descripcion', $stock)");

  if ($insertar) {
    echo "<script language='JavaScript'>
    alert('Medicina agregada');
    location.assign('../views/medicinas.php');
    </script>";
  } else {
    echo "Error al insertar la medicina: " . mysqli_error($conexion);
  }
}

function editar_medicina()
{
  include("db.php");

  $id_medicina = mysqli_real_escape_string($conexion, $_POST['id']);
  $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
  $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
  $stock = mysqli_real_escape_string($conexion, $_POST['stock']);

  $actualizar = mysqli_query($conexion, "UPDATE medicinas SET nombre='$nombre', descripcion='$descripcion', stock=$stock WHERE id=$id_medicina");

  if ($actualizar) {
    echo "<script language='JavaScript'>
    alert('Medicina actualizada');
    location.assign('../views/medicinas.php');
    </script>";
  } else {
    echo "Error al actualizar la medicina: " . mysqli_error($conexion);
  }
}

function insertarMedicamentos()
{
    include("db.php");

    $idCita = mysqli_real_escape_string($conexion, $_POST['id_cita']);
    $listaMedicinas = json_decode($_POST['lista_medicinas'], true);

    // Verificar si la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error al decodificar la lista de medicinas. Detalles: " . json_last_error_msg();
        return;
    }

    // Obtener los IDs de las medicinas
    $idsMedicinas = array_map('intval', $listaMedicinas);

    // Imprimir los IDs de las medicinas
    echo "IDs de Medicinas: " . implode(", ", $idsMedicinas) . "<br>";

    // Convertir el array de IDs a cadena separada por comas
    $cadenaIdsMedicinas = implode(", ", $idsMedicinas);

    // Actualizar el campo id_medicina en la tabla citas
    $actualizarRelacion = mysqli_query($conexion, "UPDATE citas SET id_medicina = '$cadenaIdsMedicinas' WHERE id = '$idCita'");

    if (!$actualizarRelacion) {
        echo "Error al actualizar la relación: " . mysqli_error($conexion);
        return;
    }

    echo "<script language='JavaScript'>
      alert('Medicinas actualizadas');
      location.assign('../views/citas.php');
      </script>";
}



function acceso_user()
{
  include("db.php");
  extract($_POST);

  $nombre = $conexion->real_escape_string($_POST['nombre']);
  $password = $conexion->real_escape_string($_POST['password']);
  session_start();
  $_SESSION['nombre'] = $nombre;

  $consulta = "SELECT*FROM user where nombre='$nombre' and password='$password'";
  $resultado = mysqli_query($conexion, $consulta);
  $filas = mysqli_fetch_array($resultado);

  if (isset($filas['rol']) == 1) {
    header('Location: ../views/usuarios.php');

    if ($filas['rol'] == 2) { //empleado
      header('Location: ../views/index.php');
    }
  } else {
    echo "<script language='JavaScript'>
        alert('Usuario o Contraseña Incorrecta');
        location.assign('./_sesion/login.php');
        </script>";
    session_destroy();
  }
}


function insert_esp()
{
  include "db.php";
  extract($_POST);

  $consulta = "INSERT INTO especialidades (nombre) VALUES ('$nombre')";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/especialidades.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/especialidades.php');
         </script>";
  }
}

function insert_horario()
{
  include "db.php";
  extract($_POST);

  $consulta = "INSERT INTO horario (dias, id_doctor) VALUES ('$dias', '$id_doctor')";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/horarios.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/horarios.php');
         </script>";
  }
}

function insert_paciente()
{
  include "db.php";
  extract($_POST);
  // var_dump($_POST);

  $consulta = "INSERT INTO pacientes (nombre, registro_hospitalario, sexo, correo, telefono, direccion, departamento, zona, pais, municipio, estado)
    VALUES ('$nombre', '$registro_hospitalario', '$sexo', '$correo', '$telefono', '$direccion', '$departamento', '$zona', '$pais', '$municipio',  '$estado')";
  $resultado = mysqli_query($conexion, $consulta);
  // var_dump($resultado);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/pacientes.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
        //  location.assign('../views/pacientes.php');
         </script>";
  }
}

function insert_cita()
{
  include "db.php";
  extract($_POST);

  $consulta = "INSERT INTO citas (fecha, hora, id_paciente, id_doctor , id_especialidad, observacion, estado)
    VALUES ('$fecha', '$hora', '$id_paciente', '$id_doctor', '$id_especialidad', '$observacion', '$estado')";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/citas.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/citas.php');
         </script>";
  }
}

function actualizar_examenes()
{
  include "db.php";
  $id = isset($_POST['id_registro']) ? (int)$_POST['id_registro'] : 0;
  var_dump($id);
  extract($_POST);
  var_dump($_POST);

  $data = array();

  if ($_FILES['examen_imagen']['tmp_name'] != '') {
    $fname_imagen = strtotime(date('y-m-d H:i')) . '_' . $_FILES['examen_imagen']['name'];
    $move_imagen = move_uploaded_file($_FILES['examen_imagen']['tmp_name'], '../examen/' . $fname_imagen);
    $data[] = "examen_imagen = '$fname_imagen'";
  }

  if ($_FILES['examen_pdf']['tmp_name'] != '') {
    $fname_pdf = strtotime(date('y-m-d H:i')) . '_' . $_FILES['examen_pdf']['name'];
    $move_pdf = move_uploaded_file($_FILES['examen_pdf']['tmp_name'], '../examen/' . $fname_pdf);
    $data[] = "examen_pdf = '$fname_pdf'";
  }

  if (empty($id)) {
    $save = mysqli_query($conexion, "INSERT INTO citas SET " . implode(", ", $data));
  } else {
    $save = mysqli_query($conexion, "UPDATE citas SET " . implode(", ", $data) . " WHERE id = " . $id);
    if (!$save) {
      echo "Error en la consulta: " . mysqli_error($conexion);
    }
  }

  if ($save) {
    echo "<script language='JavaScript'>
                alert('Los exámenes fueron actualizados correctamente');
                location.href='../views/citas.php';
                </script>";
  } else {
    echo "<script language='JavaScript'>
                // alert('Hubo un error al actualizar los exámenes');
                // location.assign('../views/citas.php');
                </script>";
  }
}

function insert_doctor()
{
  include "db.php";
  extract($_POST);
  $consulta = "INSERT INTO doctor (cedula, nombres, id_especialidad, sexo,  telefono, fecha, correo)
    VALUES ('$cedula', '$nombres', '$id_especialidad','$sexo', '$telefono',  '$fecha', '$correo')";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/medicos.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/medicos.php');
         </script>";
  }
}


function editar_user()
{
  include "db.php";
  extract($_POST);
  $consulta = "UPDATE user SET nombre = '$nombre', correo = '$correo', password = '$password',
     rol ='$rol' WHERE id = '$id' ";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/usuarios.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/usuarios.php');
         </script>";
  }
}

function editar_paciente()
{
  include "db.php";
  extract($_POST);
  $consulta = "UPDATE pacientes SET nombre = '$nombre', registro_hospitalario = '$registro_hospitalario', sexo = '$sexo', correo = '$correo', 
    telefono = '$telefono', direccion = '$direccion', departamento = '$departamento', zona = '$zona', pais = '$pais', municipio = '$municipio', estado ='$estado' WHERE id = '$id' ";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/pacientes.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/pacientes.php');
         </script>";
  }
}

function editar_esp()
{
  include "db.php";
  extract($_POST);
  $consulta = "UPDATE especialidades SET nombre = '$nombre' WHERE id = '$id' ";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/especialidades.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/especialidades.php');
         </script>";
  }
}

function editar_doctor()
{
  include "db.php";
  extract($_POST);
  $consulta = "UPDATE doctor SET cedula = '$cedula', nombres = '$nombres', id_especialidad = '$id_especialidad',  sexo = '$sexo',
    telefono = '$telefono', fecha = '$fecha',  correo = '$correo' WHERE id = '$id' ";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/medicos.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/medicos.php');
         </script>";
  }
}

function editar_hora()
{
  include "db.php";
  extract($_POST);
  $consulta = "UPDATE horario SET dias = '$dias', id_doctor = '$id_doctor' WHERE id = '$id' ";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/horarios.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/horarios.php');
         </script>";
  }
}

function editar_cita()
{
  include "db.php";
  extract($_POST);
  $consulta = "UPDATE citas SET fecha = '$fecha', hora = '$hora', id_paciente = '$id_paciente', id_doctor = '$id_doctor',
    id_especialidad = '$id_especialidad', observacion = '$observacion' , estado= '$estado' 
    WHERE id = '$id' ";
  $resultado = mysqli_query($conexion, $consulta);

  if ($resultado) {
    echo "<script language='JavaScript'>
        alert('El registro fue actualizado correctamente');
        location.assign('../views/citas.php');
        </script>";
  } else {
    echo "<script language='JavaScript'>
         alert('Uy no! ya valio hablale al ing :v');
         location.assign('../views/citas.php');
         </script>";
  }
}
