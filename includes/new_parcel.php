<?php if (!isset($conn)) {
  include 'db_connect.php';
} ?>
<style>
  textarea {
    resize: none;
  }
</style>
<div class="col-lg-12">
  <div class="card card-outline card-primary">
    <div class="card-body">
      <form action="" id="manage-parcel">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class=""></div>
        <div class="row">
          <div class="col-md-6">
            <b>Información del Remitente</b>
            <div class="form-group">
              <label for="" class="control-label">Nombre</label>
              <input type="text" name="sender_name" id="" class="form-control form-control-sm" value="<?php echo isset($sender_name) ? $sender_name : '' ?>" required>
            </div>
            <div class="form-group">
              <label for="" class="control-label">Dirección</label>
              <input type="text" name="sender_address" id="" class="form-control form-control-sm" value="<?php echo isset($sender_address) ? $sender_address : '' ?>" required>
            </div>
            <div class="form-group">
              <label for="" class="control-label">Numéro de Contacto</label>
              <input type="text" name="sender_contact" id="" class="form-control form-control-sm" value="<?php echo isset($sender_contact) ? $sender_contact : '' ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <b>Información del Receptor</b>
            <div class="form-group">
              <label for="" class="control-label">Nombre</label>
              <input type="text" name="recipient_name" id="" class="form-control form-control-sm" value="<?php echo isset($recipient_name) ? $recipient_name : '' ?>" required>
            </div>
            <div class="form-group">
              <label for="" class="control-label">Dirección</label>
              <input type="text" name="recipient_address" id="" class="form-control form-control-sm" value="<?php echo isset($recipient_address) ? $recipient_address : '' ?>" required>
            </div>
            <div class="form-group">
              <label for="" class="control-label">Número de Contacto</label>
              <input type="text" name="recipient_contact" id="" class="form-control form-control-sm" value="<?php echo isset($recipient_contact) ? $recipient_contact : '' ?>" required>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="dtype">Tipo</label>
              <input type="checkbox" name="type" id="dtype" <?php echo isset($type) && $type == 1 ? 'checked' : '' ?> data-bootstrap-switch data-toggle="toggle" data-on="Entregar" data-off="Recoger" class="switch-toggle status_chk" data-size="xs" data-offstyle="info" data-width="5rem" value="1">
              <small>Entregar = Entregar a la dirección indicada</small>
              <small>, Recoger = Recoger en alguna sucursal</small>
            </div>
          </div>
          <div class="col-md-6" id="" <?php echo isset($type) && $type == 1 ? 'style="display: none"' : '' ?>>
            <?php if ($_SESSION['login_branch_id'] <= 0) : ?>
              <div class="form-group" id="fbi-field">
                <label for="" class="control-label">Sucursal que envía</label>
                <select name="from_branch_id" id="from_branch_id" class="form-control select2" required="">
                  <option value=""></option>
                  <?php
                  $branches = $conn->query("SELECT *,concat(street,', ',city,', ',state,', ',zip_code,', ',country) as address FROM branches");
                  while ($row = $branches->fetch_assoc()) :
                  ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($from_branch_id) && $from_branch_id == $row['id'] ? "selected" : '' ?>><?php echo $row['branch_code'] . ' | ' . (ucwords($row['address'])) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            <?php else : ?>
              <input type="hidden" name="from_branch_id" value="<?php echo $_SESSION['login_branch_id'] ?>">
            <?php endif; ?>
            <div class="form-group" id="tbi-field">
              <label for="" class="control-label">Sucursal a recoger</label>
              <select name="to_branch_id" id="to_branch_id" class="form-control select2">
                <option value=""></option>
                <?php
                $branches = $conn->query("SELECT *,concat(street,', ',city,', ',state,', ',zip_code,', ',country) as address FROM branches");
                while ($row = $branches->fetch_assoc()) :
                ?>
                  <option value="<?php echo $row['id'] ?>" <?php echo isset($to_branch_id) && $to_branch_id == $row['id'] ? "selected" : '' ?>><?php echo $row['branch_code'] . ' | ' . (ucwords($row['address'])) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6" id="">
            <div class="form-group" id="fbi-field">
              <label for="" class="control-label">Asignar Mensajero:</label>
              <select name="pedido_asignado" id="pedido_asignado" class="form-control select2" required="">
                <option value=""></option>
                <?php
                $sql = "SELECT id, concat(firstname, ' ' , lastname) as nombre FROM users WHERE type = 2";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $selected = isset($pedido_asignado) && $pedido_asignado == $row['id'] ? "selected" : '';
                    echo "<option value='" . $row["id"] . "' " . $selected . ">" . $row["nombre"] . "</option>";
                  }
                } else {
                  echo "<option value=''>No se encontraron mensajeros</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- Agregar tipo de pago -->
          <div class="col-md-6">
            <div class="form-group">
              <label for="payment_type">Tipo de pago:</label>
              <select name="payment_type" id="payment_type" class="form-control">
                <option value="">Seleccionar tipo de pago</option>
                <option value="POS" <?php echo isset($payment_type) && $payment_type == 'POS' ? 'selected' : '' ?>>POS</option>
                <option value="Pago contra entrega" <?php echo isset($payment_type) && $payment_type == 'Pago contra entrega' ? 'selected' : '' ?>>Pago contra entrega</option>
              </select>
            </div>
          </div>
        </div>
        <hr>
        <!-- Agregar el producto -->
        <div class="col-md-12" id="">
          <div class="form-group" id="fbi-field">
            <label for="" class="control-label">Seleccione Productos:</label>
            <select name="producto" id="producto" class="form-control select2">
              <option value=""></option>
              <?php
              $productos = "SELECT * FROM productos ";
              $result = $conn->query($productos);
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $selected = isset($producto) && $producto == $row['numero_articulo'] ? "selected" : '';
                  echo "<option value='" . $row["numero_articulo"] . "' " . $selected . ">" . $row["descripcion_articulo"] . "</option>";
                }
              } else {
                echo "<option value=''>No se encontraron productos</option>";
              }
              ?>
            </select>
          </div>

        </div>
        <b>Información del producto</b>
        <table class="table table-bordered" id="parcel-items">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Descripción</th>
              <th>Unidad de Medida</th>
              <th>Cantidad</th>
              <!-- <th>Ancho</th> -->
              <th>Precio</th>

            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text" id="codigo" class="form-control" required></td>
              <td><input type="text" id="descripcion" class="form-control" required></td>
              <td><input type="text" id="medida" class="form-control" required></td>
              <td><input type="number" id="cantidad" class="form-control" min="1" value="1" required></td>
              <td><input type="text" id="price" class="form-control text-right number" required></td>

            </tr>
          </tbody>
        </table>
        <?php if (!isset($id)) : ?>
          <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
              <button id="agregar-producto" class="btn btn-primary" formnovalidate>Agregar Producto</button>
            </div>
          </div>
        <?php endif; ?>
        <b>Carrito de Compras</b>
        <table class="table table-bordered" id="carrito-items">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Descripción</th>
              <th>Unidad de Medida</th>
              <th>Cantidad</th>
              <th>Precio</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <!-- Los elementos del carrito se agregarán aquí dinámicamente con jQuery -->
          </tbody>
          <tfoot>
            <tr>
              <th colspan="5" class="text-right">Total</th>
              <th class="text-right" id="tAmount">0.00</th>
              <!-- <input type="hidden" name="total_pedido" id="total_pedido" value="0.00"> -->
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
    <div class="card-footer border-top border-info">
      <div class="d-flex w-100 justify-content-center align-items-center">
        <button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-parcel">Guardar</button>
        <a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=parcel_list">Cancelar</a>
      </div>
    </div>
  </div>
</div>

<script>
  $('#dtype').change(function() {
    if ($(this).prop('checked') == true) {
      $('#tbi-field').hide()
    } else {
      $('#tbi-field').show()
    }
  })
  // $('[name="price[]"]').keyup(function() {
  //   calc()
  // })

  $('#new_parcel').click(function() {
    var tr = $('#ptr_clone tr').clone();

    // Cambiar los IDs de los elementos dentro de la fila clonada
    tr.find('[id]').each(function() {
      var oldId = $(this).attr('id');
      var newId = oldId + '_new'; // Cambia '_new' a cualquier sufijo deseado
      $(this).attr('id', newId);
      console.log('Changed ID from', oldId, 'to', newId);
    });

    // Asignar evento de cambio al select clonado (si es necesario)
    var selectSelector = '#producto_new'; // Cambia '_new' a cualquier sufijo deseado
    $(selectSelector).change(function() {
      // Resto de tu código de cambio de producto aquí...
    });

    $('#parcel-items tbody').append(tr);


    // $('[name="price[]"]').keyup(function() {
    //   calc()
    // })

    $('.number').on('input keyup keypress', function() {
      var val = $(this).val()
      val = val.replace(/[^0-9]/, '');
      val = val.replace(/,/g, '');
      val = val > 0 ? parseFloat(val).toLocaleString("en-US") : 0;
      $(this).val(val)
    })
  })

  $('#manage-parcel').submit(function(e) {
    e.preventDefault();
    start_load();
    // Obtén el valor de tAmount
    var total_pedido = parseFloat($("#tAmount").text());

    // Crear un objeto FormData
    var formData = new FormData($(this)[0]);
    // Obtener los datos de los productos en el carrito
    var productsData = [];
    $('#carrito-items tbody tr').each(function() {
      var v_codigo = $(this).find('td:nth-child(1)').text();
      // var v_nombre = $(this).find('td:nth-child(2)').text();
      // var v_medida = $(this).find('td:nth-child(3)').text();
      var v_cantidad = $(this).find('td:nth-child(4)').text();
      var v_subTotal = $(this).find('td:nth-child(5)').text();
      // var v_total = $(this).find('[name="v_total"]').val();

      var productData = {
        v_codigo: v_codigo,
        // v_nombre: v_nombre,
        // v_medida: v_medida,
        v_cantidad: v_cantidad,
        v_subTotal: v_subTotal,
        // v_total: v_total
      };

      productsData.push(productData);
    });

    formData.append('total_pedido', total_pedido);
    // Agregar los datos de los productos al formData como JSON
    formData.append('productsData', JSON.stringify(productsData));
    console.log('formData:', formData); // Verificar el objeto FormData
    console.log('total_pedido:', total_pedido);
    console.log('productsData:', JSON.parse(formData.get('productsData')));

    $.ajax({
      url: 'ajax.php?action=save_parcel',
      data: formData, // Utilizar el mismo objeto formData
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
      success: function(resp) {
        console.log('Respuesta:', resp);
        if (resp == '1') {
          // Redirige al usuario a la página de lista de productos
          window.location.href = './index.php?page=parcel_list'; // Reemplaza con la URL correcta
        } else {
          // Maneja el caso en que la operación no fue exitosa
          console.log('La operación no fue exitosa.');
        }
      },
      error: function(xhr, status, error) {
        console.log('Error en la solicitud AJAX:', status, error); // Mostrar detalles del error
      }
    });
  });


  function displayImgCover(input, _this) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#cover').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  function recalcularTotal() {
    v_subTotal = 0;
    $('#carrito-items tbody tr').each(function() {
      var v_cantidad = parseFloat($(this).find('td:eq(3)').text());
      var v_precioVenta = parseFloat($(this).find('td:eq(4)').text());
      v_subTotal += v_cantidad * v_precioVenta;
    });
    if ($('#carrito-items tbody tr').length === 0) {
      v_subTotal = 0;
    }
    $("#tAmount").text(v_subTotal.toFixed(2));
  }

  $(document).ready(function() {
    $('#producto').change(function() {
      let selectedProduct = $(this).val();
      console.log("Selected Product:", selectedProduct);

      $.ajax({
        url: 'ajax.php?action=get_products', // Ruta al archivo PHP de manejo de AJAX
        method: 'GET',
        data: {
          product_id: selectedProduct
        }, // Enviar el ID del producto seleccionado
        dataType: 'json', // Esperar una respuesta JSON
        success: function(data) {
          console.log("Response from server:", data);

          const precioFormateado = parseFloat(data[0].precio).toFixed(2);

          // Actualizar los campos directamente sin necesidad de índice
          $('#codigo').val(data[0].codigo);
          $('#descripcion').val(data[0].descripcion);
          $('#medida').val(data[0].unidad_medida);
          $('#price').val(precioFormateado);

          // No es necesario incrementar el índice aquí

          // Llamar a la función calc() aquí si es necesario
          // calc();
        },
        error: function(xhr, status, error) {
          console.log("Error:", error);
        }
      });
    });
  });


  $(document).ready(function() {
    var codigosArray = []; // Array para almacenar los códigos de los productos en el carrito
    var v_total = 0; // Variable para almacenar el total de la venta

    // Manejador de clic para el botón "Agregar Producto"
    $("#agregar-producto").click(function(e) {
      e.preventDefault();
      var selectedOption = $("#producto option:selected"); // Obtener la opción seleccionada

      if (selectedOption.val() !== "") {
        var v_codigo = selectedOption.val();
        var v_nombre = selectedOption.text();
        var V_medida = $("#medida").val();
        var v_cantidad = parseFloat($("#cantidad").val());
        var v_precioVenta = parseFloat($('#price').val());

        // Calcular el subtotal
        var v_subTotal = v_cantidad * v_precioVenta;

        // Agregar el producto al carrito
        $('#carrito-items tbody').append(
          '<tr>' +
          '<td>' + v_codigo + '</td>' +
          '<td>' + v_nombre + '</td>' +
          '<td>' + V_medida + '</td>' +
          '<td>' + v_cantidad + '</td>' +
          '<td>' + v_subTotal.toFixed(2) + '</td>' +
          '<td><button class="btn btn-sm btn-danger" type="button" onclick="$(this).closest(\'tr\').remove(); recalcularTotal();"><i class="fa fa-times"></i></button></td>' +
          '</tr>'
        );
        
        // Agregar atributos 'name' a los elementos de la fila del producto
        // $('#carrito-form').append(
        //   '<input type="hidden" name="v_codigo[]" value="' + v_codigo + '">' +
        //   '<input type="hidden" name="v_nombre[]" value="' + v_nombre + '">' +
        //   '<input type="hidden" name="v_medida[]" value="' + V_medida + '">' +
        //   '<input type="hidden" name="v_cantidad[]" value="' + v_cantidad + '">' +
        //   '<input type="hidden" name="v_subTotal[]" value="' + v_subTotal.toFixed(2) + '">'
        // );

        // console.log('v_codigo[]: ' + v_codigo);
        // console.log('v_nombre[]: ' + v_nombre);
        // console.log('v_medida[]: ' + V_medida);
        // console.log('v_cantidad[]: ' + v_cantidad);
        // console.log('v_subTotal[]: ' + v_subTotal.toFixed(2));

        // Actualizar el total
        v_total += v_subTotal;
        $("#tAmount").text(v_total.toFixed(2));
        recalcularTotal();
        // Limpiar los campos
        $("#producto").val("").trigger("change.select2"); // Restablecer el select2
        $("#cantidad").val(1);
        // $("#descuento").val(0);
      } else {
        alert("Debe seleccionar un producto válido.");
      }
    });

    // Manejador de clic para aumentar la cantidad
    $("#aumentar-cantidad").click(function() {
      var currentQuantity = parseFloat($("#cantidad").val());
      $("#cantidad").val(currentQuantity + 1);
    });
  });
</script>