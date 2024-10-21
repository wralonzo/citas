<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['nombre'];
if ($varsesion == null || $varsesion = '') {
  header("Location: _sesion/login.php");
}


$medicinasQuery = mysqli_query($conexion, "SELECT id, nombre FROM medicinas");
$medicinas = mysqli_fetch_all($medicinasQuery, MYSQLI_ASSOC);
?>

<div class="modal fade" id="aggMedicinaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h3 class="modal-title" id="exampleModalLabel">Agregar Medicina </h3>
        <button type="button" class="btn btn-black" data-dismiss="modal">
          <i class="fa fa-times" aria-hidden="true"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="../includes/functions.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <!-- <label for="id_cita" class="form-label">ID de la Cita*</label> -->
            <input type="hidden" id="id_cita" name="id_cita" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="nombre_medicina" class="form-label">Nombre de la Medicina*</label>
            <input type="text" id="nombre_medicina" name="nombre_medicina" class="form-control">
            <div id="lista_autocompletar"></div>
          </div>
          <div class="form-group">
            <label for="lista_medicinas" class="form-label">Medicinas Agregadas</label>
            <ul id="lista_medicinas"></ul>

            <!-- Campo oculto para almacenar la lista de medicinas -->
            <input type="hidden" name="lista_medicinas" id="hidden_medicinas" value="">
          </div>

          <input type="hidden" name="accion" value="insertarMedicamentos">
          <br>
          <div class="mb-3">

            <input type="submit" value="Guardar" id="register" class="btn btn-success" name="registrar">
            <a href="#" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var listaMedicinas = [];

    document.getElementById("nombre_medicina").addEventListener("input", function() {
      var input = this.value.trim();

      if (input === "") {
        document.getElementById("lista_autocompletar").innerHTML = "";
        return;
      }

      var xhr = new XMLHttpRequest();
      xhr.open("GET", "../includes/obtener_medicinas.php?nombre=" + input, true);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var sugerencias = JSON.parse(xhr.responseText);
          mostrarSugerencias(sugerencias);
        }
      };

      xhr.send();
    });

    function mostrarSugerencias(sugerencias) {
      var listaHTML = "<ul>";
      sugerencias.forEach(function(medicina) {
        listaHTML += "<li class='opcion-medicina' data-id='" + medicina.id + "'>" + medicina.nombre + "</li>";
      });
      listaHTML += "</ul>";
      document.getElementById("lista_autocompletar").innerHTML = listaHTML;

      var opcionesMedicina = document.querySelectorAll('.opcion-medicina');
      opcionesMedicina.forEach(function(opcion) {
        opcion.addEventListener('click', function() {
          var idMedicina = this.getAttribute('data-id');
          agregarMedicina(idMedicina, sugerencias);
        });
      });
    }

    function agregarMedicina(id, sugerencias) {
      // Buscar la medicina por su ID en las sugerencias
      var medicinaSeleccionada = sugerencias.find(function(medicina) {
        return medicina.id === id;
      });

      // Si se encuentra la medicina, agregarla a la lista
      if (medicinaSeleccionada) {
        listaMedicinas.push({
          id: id,
          nombre: medicinaSeleccionada.nombre
        });

        console.log("Lista de medicinas después de agregar:", listaMedicinas);

        actualizarListaMedicinas();
        document.getElementById("nombre_medicina").value = "";
        document.getElementById("lista_autocompletar").innerHTML = "";
      }
    }


    function actualizarListaMedicinas() {
      var listaHTML = "";
      listaMedicinas.forEach(function(medicina) {
        listaHTML += "<li>" + medicina.nombre + "</li>";
      });

      console.log("Lista HTML de medicinas:", listaHTML);

      document.getElementById("lista_medicinas").innerHTML = listaHTML;

      var listaIDs = listaMedicinas.map(function(medicina) {
        return medicina.id;
      });

      console.log("Lista de IDs de medicinas:", listaIDs);

      document.getElementById("hidden_medicinas").value = JSON.stringify(listaIDs);
    }

  });
</script>