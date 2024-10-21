<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary " href="./index.php?page=new_parcel"><i class="fa fa-plus"></i> Agregar Nuevo</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<!-- <colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="25%">
					<col width="15%">
				</colgroup> -->
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Número de guía</th>
						<th>Nombre de remitente</th>
						<th>Nombre de receptor</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if (isset($_GET['s'])) {
						$where = " where status = {$_GET['s']} ";
					}
					if ($_SESSION['login_type'] == 2) {
						$firstname = $_SESSION['login_firstname'];
						$lastname = $_SESSION['login_lastname'];
						if (empty($where)) {
							$where = " WHERE pedido_asignado = (SELECT id FROM users WHERE CONCAT(firstname, ' ', lastname) = '$firstname $lastname') ";
						} else {
							$where .= " AND pedido_asignado = (SELECT id FROM users WHERE CONCAT(firstname, ' ', lastname) = '$firstname $lastname') ";
						}
					}
					$qry = $conn->query("SELECT * from parcels $where order by  unix_timestamp(date_created) desc ");
					while ($row = $qry->fetch_assoc()) :
					?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td><b><?php echo ($row['reference_number']) ?></b></td>
							<td><b><?php echo ucwords($row['sender_name']) ?></b></td>
							<td><b><?php echo ucwords($row['recipient_name']) ?></b></td>
							<td class="text-center">
								<?php
								switch ($row['status']) {
										// case '1':
										// 	echo "<span class='badge badge-pill badge-info'> Recogido</span>";
										// 	break;
										// case '2':
										// 	echo "<span class='badge badge-pill badge-info'> Enviado</span>";
										// 	break;
									case '1':
										echo "<span class='badge badge-pill badge-primary'> En tránsito</span>";
										break;
										// case '4':
										// 	echo "<span class='badge badge-pill badge-primary'> Llegó al destino</span>";
										// 	break;
										// case '5':
										// 	echo "<span class='badge badge-pill badge-primary'> Salío al destino</span>";
										// 	break;
										// case '6':
										// 	echo "<span class='badge badge-pill badge-primary'> Listo para recoger</span>";
										// 	break;
									case '2':
										echo "<span class='badge badge-pill badge-success'>Entregado</span>";
										break;
									case '3':
										echo "<span class='badge badge-pill badge-success'> Recogido</span>";
										break;
									case '4':
										echo "<span class='badge badge-pill badge-danger'> Intento de entrega fallido</span>";
										break;

									default:
										echo "<span class='badge badge-pill badge-danger'> Pendiente</span>";

										break;
								}

								?>
							</td>
							<td class="text-center">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-flat view_parcel" data-id="<?php echo $row['id'] ?>">
										<i class="fas fa-eye"></i>
									</button>
									<!-- <a href="index.php?page=edit_parcel&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat ">
										<i class="fas fa-edit"></i>
									</a> -->
									<button type="button" class="btn btn-danger btn-flat delete_parcel" data-id="<?php echo $row['id'] ?>">
										<i class="fas fa-trash"></i>
									</button>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table td {
		vertical-align: middle !important;
	}
</style>
<script>
	$(document).ready(function() {
		$('#list').DataTable({
			dom: 'Bfrtip',
			language: {
				url: "js/es-mx.json",
			},
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
	});
</script>
<script>
	$(document).ready(function() {
		$('#list').dataTable()
		$('.view_parcel').click(function() {
			uni_modal("Detalles de Paquete", "view_parcel.php?id=" + $(this).attr('data-id'), "large")
		})
		$('.delete_parcel').click(function() {
			_conf("Estas seguro de eliminarlo?", "delete_parcel", [$(this).attr('data-id')])
		})
	})

	function delete_parcel($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_parcel',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Registro eliminado", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>