<?php if($this->session->flashdata("exito")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.success('<?php echo $this->session->flashdata("exito")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>

<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.error('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>
<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Hoja </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar hoja de cobro</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<div class="row">
						<div class="col-md-6"><h6> Información de la hoja de cobro &nbsp;&nbsp;&nbsp; <input type="text" value="<?php echo $codigo; ?>" id="codigoHoja" readonly/> <input type="hidden" value="<?php echo $codigo; ?>" name="codigoHoja"/> </h6></div>
						<div class="col-md-6 text-right">
							<a href="#" class="btn btn-success" data-toggle="modal" data-target="#agregarMedicamento"><i class="fa fa-plus"></i> Insumos</a>    
							<a href="#" class="btn btn-success" data-toggle="modal" data-target="#agregarExterno"><i class="fa fa-plus"></i> Externos</a>
						</div>
					</div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>HojaCobro/guardar_hoja" novalidate>
								<div class="row">
									<div class="col-md-10">
										<div class="form-row mt-3">
											<div class="col-md-5">
												<label for=""><strong>Nombre del paciente</strong></label>
												<div class="input-group">
													<input type="text" class="form-control" id="nombrePaciente" placeholder="Nombre del paciente" readonly required>
													<input type="hidden" class="form-control" id="idPaciente" name="pacienteHoja" placeholder="Nombre del paciente" required>
													<div class="input-group-append">
														<a class="btn btn-primary btn-sm" href="" data-toggle="modal" data-target="#agregarPaciente"><i class="fa fa-search"></i></a>
													</div>
												</div>
											</div>

											<div class="col-md-4">
												<label for=""><strong>Fecha de ingreso</strong></label>
												<div class="input-group">
												<input type="text" class="form-control datepicker" id="fechaIngreso" name="ingresoHoja" placeholder="Fecha del ingreso" required>
													<div class="invalid-tooltip">
														Seleccione un tipo de documento.
													</div>
												</div>
											</div>

											<div class="col-md-3">
												<label for=""><strong>Tipo</strong></label>
												<div class="input-group">
													<select class="form-control" id="tipoConsulta" name="consultaHoja" required>
														<option value="">.:: Seleccionar ::.</option>
														<option value="Ingreso">Ingreso</option>
														<option value="Ambulatorio">Ambulatorio</option>
														<option value="Otro">Otro</option>
													</select>
													<div class="invalid-tooltip">
														Seleccione un tipo de hoja.
													</div>
												</div>
											</div>
										</div>

										<div class="form-row mt-3 mb-5">
											<div class="col-md-5">
												<label for=""><strong>Médico</strong></label>
												<div class="input-group">
													<select class="form-control" id="medicoHoja" name="medicoHoja" required>
														<option value="">.:: Seleccionar ::.</option>
														<?php 
															foreach ($medicos as $medico) {
																?>
														
														<option value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
														
														<?php } ?>
													</select>
													<div class="invalid-tooltip">
														Seleccione un médico.
													</div>
												</div>
											</div>

											<div class="col-md-7">
												<label for=""><strong>Habitación</strong></label>
												<div class="input-group">
													
													<select class="form-control" id="habitacionHoja" name="habitacionHoja" required>
														<option value="">.:: Seleccionar ::.</option>
														<?php 
															foreach ($habitaciones as $habitacion) {
																?>
														
														<option value="<?php echo $habitacion->idHabitacion; ?>"><?php echo $habitacion->numeroHabitacion; ?></option>
														
														<?php } ?>
													</select>

													<div class="invalid-tooltip">
														Ingrese el numero de habitacion del paciente.
													</div>
												</div>
											</div>

										</div>
									</div>

									<div class="col-md-2">
											<table class="table table-bordered mt-5">

												<tr>
													<td><strong>Insumos</strong></td>
													<td id="totalMedicamentosH">0</td>
												</tr>
												
												<tr>
													<td><strong>Externos</strong></td>
													<td id="totalExternosH">0 </td>
												</tr>

												<tr class="alert-success">
													<td><strong>Total</strong></td>
													<td id="totalHoja">0</td>
												</tr>

											</table>
									</div>
									<input type="hidden" id="totalIS" name="totalIS" value="0"/> <input type="hidden" id="totalSE" name="totalSE" value="0"/> 
								</div>

								<div class="row">
										
									<div class="col-md-6" id="tm">
										<!--Tabla de medicamentos seleccionados-->
										<div class="table-responsive mt-3">
											<h5 class="text-center">Medicamentos e Insumos médicos</h5>
											<table id="tblInsumos" class="table table-responsive thead-primary w-100">
												<thead>
													<tr>
														<th class="text-center" scope="col">Medicamento</th>
														<th class="text-center" scope="col">Precio</th>
														<th class="text-center" scope="col">Cantidad</th>
														<th class="text-center" scope="col">Total</th>
														<th class="text-center" scope="col">Opción</th>
													</tr>
												</thead>
												<tbody>

													<tr id="detalleMed">
														<td colspan="3" class="text-center"><strong>TOTAL</strong></td>
														<td colspan="2"><strong><label for="" id="totalCompraL">$0</label></strong></td>
													</tr>
												</tbody>
											</table>
										</div>
										<!--Fin tabla de medicamentos-->
									</div>

									<div class="col-md-6" id="te">
										<!--Tabla de medicamentos seleccionados-->
										<div class="table-responsive mt-3">
											<h5 class="text-center">Servicios externos</h5>
											<table id="tblServiciosExternos" class="table table-responsive thead-primary w-100">
												<thead>
													<tr>
														<th class="text-center" scope="col">Servicio</th>
														<th class="text-center" scope="col">Cantidad</th>
														<th class="text-center" scope="col">Precio</th>
														<th class="text-center" scope="col">Total</th>
														<th class="text-center" scope="col">Opción</th>
													</tr>
												</thead>
												<tbody>

													

													<tr id="detalleExt">
														<td colspan="3" class="text-center"><strong>TOTAL</strong></td>
														<td colspan="2">
															<input type="hidden" class="form-control" id="totalExternos" value="0"/>
															<strong><label id="totalCompraLE">$0</label></strong>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!--Fin tabla de medicamentos-->
									</div>

								</div>						
								<div class="text-center" id="botonera">
									<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar datos</button>
									<button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ============================================================== -->
<!-- Ventana Modal para agregar Nuevo Plazo-->
<!-- ============================================================== -->
<div class="modal fade" id="agregarPaciente" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Lista de Pacientes</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<div class="table-responsive mt-3">
								<table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
									<thead>
										<tr>
											<th class="text-center" scope="col">#</th>
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
										
										<?php
										$index = 0;
											foreach ($pacientes as $paciente) {
												$index++;
												$id ='"'.$paciente->idPaciente.'"';
												$nombre ='"'.$paciente->nombrePaciente.'"';
										?>
										<tr>
											<td class="text-center" scope="row"><?php echo $index ?></td>
											<td class="text-center"><?php echo $paciente->nombrePaciente ?></td>
											<td class="text-center">
											<?php
												echo "<a onclick='agregarPaciente($id, $nombre)' title='Agregar a la lista' data-dismiss='modal'><i class='fa fa-plus ms-text-primary'></i></a>";
											?>
												
											</td>
										</tr>

										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- Fin de ventana modal -->
<!-- ============================================================== -->



<!-- ============================================================== -->
<!-- Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->
<div class="modal fade" id="agregarMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Lista de medicamentos</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<div class="table-responsive mt-3">

								<?php
									if( sizeof($medicamentos) > 0){
								?>
								
								<table id="tabla-medicamentos-hoja" class="table table-striped thead-primary w-100">
									<thead>
										<tr>
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Existencia</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach ($medicamentos as $medicamento) {
											$id ='"'.$medicamento->idMedicamento.'"';
											$codigo ='"'.$medicamento->codigoMedicamento.'"';
											$nombre = '"'.$medicamento->nombreMedicamento.'"';
											$precioV = '"'.$medicamento->precioVMedicamento.'"';
											$stock = '"'.$medicamento->stockMedicamento.'"';
											$usados = '"'.$medicamento->usadosMedicamento.'"';
									?>
										<tr>
											<td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento?></td>
											<td class="text-center precio"><?php echo $medicamento->stockMedicamento?></td>
											<td class="text-center">
											<?php
												echo "<a onclick='agregarMedicamento($id, $codigo, $nombre, $precioV, $stock, $usados)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
											?>
												
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

								<?php
									}else{
										echo '<div class="alert alert-danger">
											<h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
										</div>';
									}
								?>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- Fin Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->
<div class="modal fade" id="agregarExterno" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Lista de servicios externos</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
						<div class="table-responsive mt-3">

							<?php
								if( sizeof($externos) > 0){
							?>

							<table id="tabla-externos-hoja" class="table table-striped thead-primary w-100">
								<thead>
									<tr>
										<th class="text-center" scope="col">Nombre</th>
										<th class="text-center" scope="col">Opción</th>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach ($externos as $externo) {
										$id ='"'.$externo->idExterno.'"';
										$nombre ='"'.$externo->nombreExterno.'"';
								?>
									<tr>
										<td class="text-center" scope="row"><?php echo $externo->nombreExterno?></td>
										<td class="text-center">
										<?php
											echo "<a onclick='agregarExterno($id, $nombre)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
										?>
											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>

							<?php
								}else{
									echo '<div class="alert alert-danger">
										<h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
									</div>';
								}
							?>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- Fin Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->


<script>

	function agregarPaciente(id, nombre){
		$("#nombrePaciente").attr("value", nombre);
		$("#idPaciente").attr("value", id);
		console.log(nombre);
	}

	function agregarMedicamento(id, codigo, nombre, precioV, stock, usados){
		$("#tm").show();
		$("#botonera").show();
		var totalUnitario = precioV * 1;
		var html = "";
		html  += '<tr>';
			html  += '<td class="text-center" scope="row">'+nombre+'<input type="hidden" class="form-control"value="'+id+'" name="idMedicamentos[]" required></td>';
			html  += '<td class="text-center" scope="row">'+precioV+'<input type="hidden" class="form-control precioM" value="'+precioV+'" name="preciosMedicamentos[]" required></td>';
			html  += '<td class="text-center">';
			html  += '<div class="input-group">';
			html  += '<input type="number" class="cantidadMedicamento" min="0" value="1" name="cantidadMedicamentos[]" required><input type="hidden" class="form-control precioM" value="'+stock+'" name="stockMedicamentos[]" required>';
			html  += '<div class="invalid-tooltip">';
			html  +=	'Ingresa una cantidad';
			html  += '</div>';
			html  += '</div>';
			html  += '</td>';
			html  += '<td class="text-center">';
			html  += '<label class="lblTotalUM">$ '+totalUnitario+'</label>';
			html  += '<div class="input-group">';
			html  += '<input type="hidden" class="form-control totalM" min="0" value="'+totalUnitario+'" name="totalMedicamentos[]">';
			html  += '<input type="hidden" class="form-control" min="0" value="'+usados+'" name="usadosMedicamentos[]">';
			html  += '</div>';
			html  += '</td>';
			html  += '<td class="text-center">';
			html  += '<a class="quitarMedicamento" title="Quitar de la lista"><i class="fa fa-times ms-text-danger"></i></a>';
			html  += '</td>';

		html  += '</tr>';

		$("#detalleMed").before(html);

		var totalCompra = 0 ; // Total de la factura
		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});
		$("#totalCompraL").html("$ "+totalCompra.toFixed(2)); // Asignando el valor al total de la compralblTotalUM
		$("#totalMedicamentosH").html(totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra

		$("#totalIS").val(totalCompra.toFixed(2)); // Asignando el valor al total de la compralblTotalUM


	}

	$(document).on('change', '.cantidadMedicamento', function (event) {
		event.preventDefault();
		var cantidad = $(this).val(); // Cantidad de cada caja de texto
		var precio = $(this).closest('tr').find(".precioM").val(); // Precio de cada producto
		var totalCompra = 0 ; // Total de la factura

		var totalUnitario = cantidad * precio; // Total por cada producto
		$(this).closest('tr').find(".totalM").val(totalUnitario.toFixed(2));
		$(this).closest('tr').find(".lblTotalUM").html("$ "+totalUnitario.toFixed(2));
		//$(this).closest('tr').find(".totalUL").html("$ "+totalUnitario.toFixed(2));

		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});

		$("#totalCompraL").html("$ "+totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra
		$("#totalMedicamentosH").html(totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra


		// Insumos + Externos
		var externos = parseFloat($("#totalExternosH").html());
		$("#totalHoja").html((totalCompra + externos ));
		$("#totalIS").val(totalCompra.toFixed(2));


	});

	$(document).on('click', '.quitarMedicamento', function (event) {
		event.preventDefault();
		$(this).closest('tr').remove();
		var totalCompra = 0 ; // Total de la factura
		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});
		$("#totalCompraL").html("$ "+totalCompra.toFixed(2)); // Asignando el valor al labe de total de la compra
		$("#totalMedicamentosH").html(totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra

		// Insumos + Externos
		var externos = parseFloat($("#totalExternosH").html());
		$("#totalHoja").html((totalCompra + externos ));
		$("#totalIS").val(totalCompra.toFixed(2)); // Para el total de los medicamentos

		var eFilas = $("#tblServiciosExternos tr .cantidadE").length;
		var iFilas = $("#tblInsumos tr .cantidadMedicamento").length;
		if(iFilas == 0){
			$("#tm").hide();
		}

		if(iFilas == 0 && eFilas == 0){
			$("#botonera").hide();
		}
		

	});

	function agregarExterno(id, nombre){
		$("#te").show();
		$("#botonera").show();
		var html = "";
		html += "<tr>";
			html += "<td class='text-center'>"+nombre+"</td>";
			html += "<td class='text-center'><input type='text' class='form-control cantidadE' value='1' name='cantidadExternos[]'/> <input type='hidden' class='form-control' value="+id+" name='idExternos[]'/></td>";
			
			html  += "<td class='text-center'>";
			html  += "<div class='input-group'>";
			html  += "<input type='text' class='precioE' name='precioE[]' required/>";
			html  += "<div class='invalid-tooltip'>";
			html  +=	"Ingresa una cantidad";
			html  += "</div>";
			html  += "</div>";
			html  += "</td>";

			html += "<td class='text-center'> <span id='totalEUL'>$ 0</span> <input type='hidden' class='form-control totalEU' value='0' name='totalEU' required /></td>";
			html += "<td class='text-center'>";
			html  += '<a class="quitarExterno" title="Quitar de la lista"><i class="fa fa-times ms-text-danger"></i></a>';
			html += "</td>";
		html += "</tr>";

		$("#detalleExt").before(html);

	}

	$(document).on('change', '.precioE', function (event) {
		event.preventDefault();
		var precio = $(this).val(); // Cantidad de cada caja de texto
		var cantidad = $(this).closest('tr').find(".cantidadE").val(); // Precio de cada producto
		var totalCompra = 0 ; // Total de la factura
		var totalUnitario = precio * cantidad;
		$(this).closest('tr').find(".totalEU").val(totalUnitario.toFixed(2));
		$(this).closest('tr').find("#totalEUL").html(totalUnitario.toFixed(2));
		$(".totalEU").each(function(){
			totalCompra += parseFloat($(this).val());
		});
		$("#totalExternos").val(totalCompra);
		$("#totalCompraLE").html("$ "+totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra
		$("#totalExternosH").html(totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra

		// Insumos + Externos
		var insumos = parseFloat($("#totalMedicamentosH").html());
		$("#totalHoja").html("$ "+(totalCompra + insumos )); // Asignando el valor al label de total de la compra

		$("#totalSE").val(totalCompra.toFixed(2)); // Para el total de externos

	});

	$(document).on('change', '.cantidadE', function (event) {
		
		event.preventDefault();
		var cantidad = $(this).val(); // Cantidad de cada caja de texto
		var precio = $(this).closest('tr').find(".precioE").val(); // Precio de cada producto
		var totalCompra = 0 ; // Total de la factura
		var totalUnitario = precio * cantidad;
		$(this).closest('tr').find(".totalEU").val(totalUnitario.toFixed(2));
		$(this).closest('tr').find("#totalEUL").html(totalUnitario.toFixed(2));
		$(".totalEU").each(function(){
			totalCompra += parseFloat($(this).val());
		});
		$("#totalExternos").val(totalCompra);
		$("#totalCompraLE").html("$ "+totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra
		$("#totalExternosH").html(totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra

		// Insumos + Externos
		var insumos = parseFloat($("#totalMedicamentosH").html());
		$("#totalHoja").html("$ "+(totalCompra + insumos )); // Asignando el valor al label de total de la compra
		$("#totalSE").val(totalCompra.toFixed(2)); // Para el total de externos

	});

	$(document).on('click', '.quitarExterno', function (event) {
		event.preventDefault();
		$(this).closest('tr').remove();
		var totalCompra = 0 ; // Total de la factura
		// Sumando todos los input con totales unitarios
		$('.totalEU').each(function(){
			totalCompra += parseFloat($(this).val());
		});
		$("#totalExternos").val(totalCompra);
		$("#totalCompraLE").html("$ "+totalCompra.toFixed(2)); // Asignando el valor al labe de total de la compra
		$("#totalExternosH").html(totalCompra.toFixed(2)); // Asignando el valor al label de total de la compra

		// Insumos + Externos
		var insumos = parseFloat($("#totalMedicamentosH").html());
		$("#totalHoja").html("$ "+(totalCompra + insumos )); // Asignando el valor al label de total de la compra
		$("#totalSE").val(totalCompra.toFixed(2)); // Para el total de externos


		var eFilas = $("#tblServiciosExternos tr .cantidadE").length;
		var iFilas = $("#tblInsumos tr .cantidadMedicamento").length;
		if(eFilas == 0){
			$("#te").hide();
		}

		if(iFilas == 0 && eFilas == 0){
			$("#botonera").hide();
		}

	});

	$(".ocultarAgregar").click(function() {
			$(this).closest('tr').remove();
	});

</script>