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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Botíquin </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar medicamentos</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<div class="row">
                        <div class="col-md-6"><h6>Información de la compra</h6></div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary mt-2" type="button" href="#agregarMedicamento" data-toggle="modal"><i class="fa fa-plus"></i> Medicamentos</button>
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="<?php echo base_url(); ?>Botiquin/guardar_compra" novalidate>
								<div class="form-row">
									<div class="col-md-5">

										<div class="col-sm-8">
											<div class="input-group">
												<span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
											</div>
											<!-- input-group -->
										</div>

										<!--<input type="text" id="time"/>-->

										<label for=""><strong>Tipo</strong></label>
										<div class="input-group">
											<select class="form-control" id="tipoFactura" name="tipoFactura" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="Compra de medicamentos">Compra de medicamentos</option>
                                                <option value="">Otra</option>
                                                <option value="">Otra</option>
                                            </select>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									<div class="col-md-5">
										<label for=""><strong>Tipo de documento</strong></label>
										<div class="input-group">
											<select class="form-control" id="documentoFactura" name="documentoFactura" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="Crédito fiscal">Crédito fiscal</option>
                                                <option value="">Otra</option>
                                                <option value="">Otra</option>
                                            </select>
											<div class="invalid-tooltip">
												Seleccione un tipo de documento.
											</div>
										</div>
									</div>

									<div class="col-md-2">
										<label for=""><strong>Número de documento</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="numeroFactura" name="numeroFactura" placeholder="Número del medicamento" required>
											<div class="invalid-tooltip">
												Ingrese un número de documento.
											</div>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-5">
										<label for=""><strong>Responsable</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="responsableFactura" name="responsableFactura" placeholder="Descripción del proceso" required>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									<div class="col-md-5">
										<label for=""><strong>Proveedor</strong></label>
										<div class="input-group">
											<select class="form-control" id="idProveedor" name="idProveedor" required>
                                                <option value="">.:: Seleccionar ::.</option>

												<?php
													foreach ($proveedores as $proveedor) {
												?>

                                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->empresaProveedor; ?></option>

												<?php } ?>
                                            </select>
											<div class="invalid-tooltip">
												Seleccione un proveedor.
											</div>
										</div>
									</div>

									<div class="col-md-2">
										<label for=""><strong>Fecha</strong></label>
										<div class="input-group">
											<input type="date" class="form-control" id="fechaFactura" name="fechaFactura" placeholder="Registro del medicamento" required>
											<div class="invalid-tooltip">
												Ingrese un número de documento.
											</div>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-5">
										<label for=""><strong>Plazo</strong></label>
										<div class="input-group">
											<select name="plazoFactura" id="plazoFactura" class="form-control">
												<option value="">.:: Seleccionar ::.</option>
												<option value="0">Contado</option>
												<option value="30">30 dias</option>
												<option value="60">60 dias</option>
												<option value="90">90 dias</option>
											</select>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									<div class="col-md-7">
										<label for=""><strong>Descripción</strong></label>
										<div class="input-group">
											<textarea class="form-control" id="descripcionFactura" name="descripcionFactura" placeholder="Descripción del proceso" required></textarea>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									
								</div>

								<!--Tabla de medicamentos seleccionados-->
								<div class="table-responsive mt-3">
									<table id="tabla-medicamentos-seleccionados" class="table table-striped thead-primary w-100">
										<thead>
											<tr>
												<th class="text-center" scope="col">Código</th>
												<th class="text-center" scope="col">Medicamento</th>
												<th class="text-center" scope="col">Precio unitario</th>
												<th class="text-center" scope="col">Cantidad</th>
												<th class="text-center" scope="col">Total</th>
												<th class="text-center" scope="col">IVA</th>
												<th class="text-center" scope="col">Vencimiento</th>
												<th class="text-center" scope="col">Lote</th>
												<th class="text-center" scope="col">Opción</th>
											</tr>
										</thead>
										<tbody id="detalleFactura">
											<tr>
												<td class="text-right" scope="row" colspan="5"><strong>TOTAL</strong></td>
												<td class="text-center" colspan="3">
													<label for="" id="totalCompraL"><strong>$0</strong></label>
													<input type="hidden" value="0" class="form-control" id="totalCompra" name="totalCompra">
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!--Fin tabla de medicamentos-->

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


<!-- Modal para agregar datos del Medicamento-->
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
								
								<table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
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
											$precioC = '"'.$medicamento->precioCMedicamento.'"';
											$stock = '"'.$medicamento->stockMedicamento.'"';
									?>
										<tr>
											<td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento?></td>
											<td class="text-center precio"><?php echo $medicamento->stockMedicamento?></td>
											<td class="text-center">
											<?php
												echo "<a onclick='agregarMedicamento($id, $codigo, $nombre, $precioC, $stock)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
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
<!-- Fin Modal para agregar datos del Medicamento-->

<script>
	$(document).ready(function() {
		$("#tabla-medicamentos-seleccionados").hide();
		$("#botonera").hide();
	});

	function agregarMedicamento(id, codigo, nombre, precioC, stock){
		var totalUnitario = precioC * 1;
		var iva = precioC * 0.13;
		var html = "";
		html  += '<tr>';
			html  += '<td class="text-center" scope="row">'+codigo+'</td>';
			html  += '<td class="text-center">'+nombre+'<input type="hidden" class="form-control"value="'+id+'" name="idMedicamentos[]" required></td>';
			html  += '<td class="text-center"><input type="text" class="form-control precioM" value="'+precioC+'" name="preciosMedicamentos[]" required></td>';
			html  += '<td class="text-center">';
			html  += '<div class="input-group">';
			html  += '<input type="number" class="form-control cantidadMedicamento" min="0" value="1" name="cantidadMedicamentos[]" required>';
			html  += '<div class="invalid-tooltip">';
			html  +=	'Ingresa una cantidad';
			html  += '</div>';
			html  += '</div>';
			html  += '</td>';
			html  += '<td class="text-center">';
			html  += '<div class="input-group">';
			html  += '<label class="totalUL"><strong>$ '+ totalUnitario +'</strong></label><input type="hidden" class="form-control" value="'+stock+'" name="stockMedicamentos[]" required>';
			html  += '<input type="hidden" name="totalUnitario[]" class="form-control totalM" min="0" value="'+ totalUnitario +'" required>';
			html  += '<div class="invalid-tooltip">';
			html  +=	'Ingresa el lote del medicamento';
			html  += '</div>';
			html  += '</div>';
			html  += '</td>';
			
			html  += '<td class="text-center">';
			html  += '<div class="input-group">';
			html  += '<label class="totalUIVA"><strong>$ '+ iva.toFixed(2) +'</strong></label>';
			html  += '<input type="hidden" class="form-control ivaMedicamentos" name="ivaMedicamentos[]"  value="'+iva+'" required>';
			html  += '<div class="invalid-tooltip">';
			html  +=	'Ingresa una fecha de venimiento';
			html  += '</div>';
			html  += '</div>';
			html  += '</td>';

			html  += '<td class="text-center">';
			html  += '<div class="input-group">';
			html  += '<input type="date" class="form-control" name="vencimientoMedicamentos[]"  placeholder="Fecha de vencimiento" required>';
			html  += '<div class="invalid-tooltip">';
			html  +=	'Ingresa una fecha de venimiento';
			html  += '</div>';
			html  += '</div>';
			html  += '</td>';
			html  += '<td class="text-center">';
			html  += '<div class="input-group">';
			html  += '<input type="text" class="form-control" name="loteMedicamentos[]"  placeholder="Lote del medicamento" required>';
			html  += '<div class="invalid-tooltip">';
			html  +=	'Ingresa el lote del medicamento';
			html  += '</div>';
			html  += '</div>';
			html  += '</td>';
			html  += '<td class="text-center">';
			html  += '<a class="quitarMedicamento" title="Quitar de la lista"><i class="fa fa-times ms-text-danger"></i></a>';
			html  += '</td>';
		html  += '</tr>';
		$("#detalleFactura").before(html);
		
		
		var totalCompra = 0 ; // Total de la factura
		var totalIva = 0 ; // Total iva
		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});

		// Sumando todos los input con iva
		$('.ivaMedicamentos').each(function(){
			totalIva += parseFloat($(this).val());
		});
		
		$("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
		$("#totalCompraL").html("$ "+ (totalCompra + totalIva).toFixed(2) ); // Asignando el valor al total de la compra

		$("#tabla-medicamentos-seleccionados").show();
		$("#botonera").show();
	}

	$(".ocultarAgregar").click(function() {
			$(this).closest('tr').remove();
	});

	$(document).on('change', '.cantidadMedicamento', function (event) {
		event.preventDefault();
		var cantidad = $(this).val(); // Cantidad de cada caja de texto
		var precio = $(this).closest('tr').find(".precioM").val(); // Precio de cada producto
		var totalCompra = 0 ; // Total de la factura
		var totalIva = 0 ; // Total iva
		
		var totalUnitario = cantidad * precio; // Total por cada producto
		var iva = (cantidad * precio) * 0.13
		
		$(this).closest('tr').find(".totalM").val(totalUnitario.toFixed(2));
		$(this).closest('tr').find(".totalUL").html("$ "+totalUnitario.toFixed(2));
		
		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});
		
		
		
		$(this).closest('tr').find(".ivaMedicamentos").val(iva);
		$(this).closest('tr').find(".totalUIVA").html("$ "+iva.toFixed(2));

		
		$('.ivaMedicamentos').each(function(){
			totalIva += parseFloat($(this).val());
		});
		
		$("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra 
		$("#totalCompraL").html("$ "+ (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
		

	});

	$(document).on('change', '.precioM', function (event) {
		event.preventDefault();
		var precio = $(this).val(); // Cantidad de cada caja de texto
		var cantidad = $(this).closest('tr').find(".cantidadMedicamento").val(); // Precio de cada producto
		var totalCompra = 0 ; // Total de la factura
		var totalIva = 0 ; // Total de la factura

		var totalUnitario = cantidad * precio; // Total por cada producto
		var iva = (cantidad * precio) * 0.13 ; // Total por cada producto

		$(this).closest('tr').find(".totalM").val(totalUnitario.toFixed(2));
		$(this).closest('tr').find(".totalUL").html("$ "+totalUnitario.toFixed(2));
		$(this).closest('tr').find(".totalUIVA").html("$ "+iva.toFixed(2));

		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});

		$(this).closest('tr').find(".ivaMedicamentos").val(iva);

		$('.ivaMedicamentos').each(function(){
			totalIva += parseFloat($(this).val());
		});
		
		$("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
		$("#totalCompraL").html("$ "+ (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra


		console.log(totalCompra);

	});

	$(document).on('click', '.quitarMedicamento', function (event) {
		event.preventDefault();
		$(this).closest('tr').remove();
		var totalCompra = 0 ; // Total de la factura
		var totalIva = 0 ; // Total de la factura

		// Sumando todos los input con totales unitarios
		$('.totalM').each(function(){
			totalCompra += parseFloat($(this).val());
		});

		$('.ivaMedicamentos').each(function(){
			totalIva += parseFloat($(this).val());
		});

		$("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
		$("#totalCompraL").html("$ "+ (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra


		var eFilas = $("#tabla-medicamentos-seleccionados tr").length;
		if(eFilas == 2){
			$("#botonera").hide();
			$("#tabla-medicamentos-seleccionados").hide();
		}

	});
</script>