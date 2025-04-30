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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Paquetes </a> </li>
                    <li class="breadcrumb-item"><a href="#">Listado de paquetes</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <div class="col-md-6">
                        <h6>Lista de recibos de paquetes</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="#agregarPaquete" class="btn btn-success" data-toggle="modal"><i class="fa fa-plus"></i> Agregar </a>
                    </div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
                        <div class="table-responsive mt-3">
							<?php
								if(sizeof($paquetes) > 0){
							?>
                            <table id="tabla-paquetes" class="table table-striped thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Código</th>
                                        <th class="text-center" scope="col">Paciente</th>
                                        <th class="text-center" scope="col">Médico</th>
                                        <th class="text-center" scope="col">Total</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Tipo</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- $id ='"'.$hoja->idHoja.'"'; // Id de la hoja. -->
									<?php
									$number = 1;
									$concepto = "";
										foreach ($paquetes as $paquete) {
											if($paquete->conceptoPaquete == 1){
												$concepto = "Abono";
											}else{
												$concepto = "Cancelación";
											}
											if($paquete->estadoPaquete == 1){
									?>
												<tr>
													<td class="text-center"><?php echo $paquete->codigoPaquete; ?></td>
													<td class="text-center"><?php echo $paquete->pacientePaquete; ?></td>
													<td class="text-center"><?php echo $paquete->nombreMedico; ?></td>
													<td class="text-center">$ <?php echo number_format($paquete->cantidadPaquete, 2); ?></td>
													<td class="text-center"><?php echo $paquete->fechaPaquete; ?></td>
													<td class="text-center"><?php echo $concepto; ?></td>
													<td class="text-center">
														<input type="hidden" value="<?php echo $paquete->idPaquete; ?>" class="idPaquete">
														<input type="hidden" value="<?php echo $paquete->pacientePaquete; ?>" class="pacientePaquete">
														<input type="hidden" value="<?php echo $paquete->medicoPaquete; ?>" class="medicoPaquete">
														<input type="hidden" value="<?php echo $paquete->cantidadPaquete; ?>" class="cantidadPaquete">
														<input type="hidden" value="<?php echo $paquete->conceptoPaquete; ?>" class="conceptoPaquete">
														<input type="hidden" value="<?php echo $paquete->fechaPaquete; ?>" class="fechaPaquete">
														<?php
															if($paquete->saldado == 1){
																echo '<a href="'.base_url().'Hoja/recibo_paquete/'.$paquete->idPaquete.'/" target="blank"><i class="fa fa-print text-primary"></i></a>';
															}else{
																echo '<a href="#reciboPaquete" data-toggle="modal" class="recibo_paquete"><i class="fa fa-file text-danger"></i></a>';
																echo '<a href="#actualizarPaquete" data-toggle="modal" class="actualizarPaquete"><i class="fa fa-edit text-success"></i></a>';
															}

															if($this->session->userdata("nivel") == 1){
																echo '<a href="#eliminarPaquete" data-toggle="modal" class="eliminarPaquete"><i class="fa fa-trash-alt text-danger"></i></a>';
															}
														?>
														
													</td>
												</tr>
									<?php
										$number++;
											}
										}
									?>
								</tbody>
                            </table>
							<?php
								}else{
									echo "<div class='alert-danger text-center p-3 bold'>No hay datos que mostrar.</div";
								}
							?>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal para agregar paquete -->
	<div class="modal fade" id="agregarPaquete" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos del paquete</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" id="frmHpjaCobro" method="post" action="<?php echo base_url() ?>Hoja/guardar_paquete" novalidate>
								<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Código del paquete:</strong></label>
											<div class="input-group">
												<input type="text text-right" value="<?php echo $codigo; ?>" class="form-control" id="" name="" readonly>
												<input type="hidden" value="<?php echo $codigo; ?>" class="form-control" id="codigoPaquete" name="codigoPaquete" required>
												<div class="invalid-tooltip">
													Debes ingresar la fecha del paquete.
												</div>
											</div>
										</div>
									</div>

                                    <div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Fecha del paquete:</strong></label>
											<div class="input-group">
												<input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="fechaPaquete" name="fechaPaquete" required>
												<div class="invalid-tooltip">
													Debes ingresar la fecha del paquete.
												</div>
											</div>
										</div>
									</div>

                                    <div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Médico</strong></label>
											<div class="input-group">
												
												<select class="form-control controlInteligente" id="medicoPaquete" name="medicoPaquete" required>
													<option value="">.:: Seleccionar ::.</option>
													<?php 
														$clase = "";
														foreach ($medicos as $medico) {
															?>
													
															<option class="" value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
													
													<?php } ?>
												</select>

												<div class="invalid-tooltip">
													Seleccione el médico.
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group col-md-12">
											<label for=""><strong>DUI</strong></label>
											<input type="text" class="form-control" id="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>
											<div class="invalid-tooltip">
												Debes ingresar el DUI del paciente.
											</div>
										</div>
									</div>

                                    <div class="form-row">

                                        <div class="col-md-12">
											<label for=""><strong>Paciente:</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="pacientePaquete" name="pacientePaquete" placeholder="Nombre del paciente" required>
												<div class="invalid-tooltip">
													Debes ingresar el paciente.
												</div>
											</div>
										</div>
										
									</div>
									
                                    <div class="form-row">

                                        <div class="col-md-12">
											<label for=""><strong>Total paquete:</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="totalPaquete" name="totalPaquete" placeholder="Monto total del paquete" required>
												<div class="invalid-tooltip">
													Debes ingresar el monto total del paquete.
												</div>
											</div>
										</div>

                                        <div class="col-md-12">
											<label for=""><strong>Monto entregado por paciente:</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="cantidadPaquete" name="cantidadPaquete" placeholder="Cantidad entregada por el paciente" required>
												<div class="invalid-tooltip">
													Debes ingresar la cantidad entregada por el paciente.
												</div>
											</div>
										</div>
										
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Tipo</strong></label>
											<div class="input-group">
												<select class="form-control" id="tipoPaquete" name="tipoPaquete" required>
													<option value="">.:: Seleccionar ::.</option>
													<option value="1">Abono</option>
													<option value="2">Cancelación</option>
												</select>
												<div class="invalid-tooltip">
													Seleccione un tipo de proceso.
												</div>
											</div>
										</div>
									</div>

									<div class="text-center">
										<input type="hidden" value="0" name="idPaciente" id="idPaciente">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Crear recibo</button>
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
<!-- Fin Modal agregar paquete -->

<!-- Modal para actualizar paquete -->
	<div class="modal fade" id="actualizarPaquete" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos del paquete</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" id="frmPaquete" method="post" action="<?php echo base_url() ?>Hoja/actualizar_paquete" novalidate>
                                    
                                    <div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Fecha del paquete:</strong></label>
											<div class="input-group">
												<input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="fechaPaqueteA" name="fechaPaquete" required>
												<div class="invalid-tooltip">
													Debes ingresar la fecha del paquete.
												</div>
											</div>
										</div>
									</div>

                                    <div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Médico</strong></label>
											<div class="input-group">
												
												<select class="form-control controlInteligente2" id="medicoPaqueteA" name="medicoPaquete" required>
													<option value="">.:: Seleccionar ::.</option>
													<?php 
														$clase = "";
														foreach ($medicos as $medico) {
															?>
													
															<option class="" value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
													
													<?php } ?>
												</select>

												<div class="invalid-tooltip">
													Seleccione el médico.
												</div>
											</div>
										</div>
									</div>

                                    <div class="form-row">

                                        <div class="col-md-12">
											<label for=""><strong>Paciente:</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="pacientePaqueteA" name="pacientePaquete" required>
												<div class="invalid-tooltip">
													Debes ingresar el paciente.
												</div>
											</div>
										</div>
										
									</div>
									
                                    <div class="form-row">

                                        <div class="col-md-12">
											<label for=""><strong>Cantidad:</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="cantidadPaqueteA" name="cantidadPaquete" required>
												<div class="invalid-tooltip">
													Debes ingresar la cantidad del paquete.
												</div>
											</div>
										</div>
										
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Tipo</strong></label>
											<div class="input-group">
												<select class="form-control" id="tipoPaqueteA" name="tipoPaquete" required>
													<option value="">.:: Seleccionar ::.</option>
													<option value="1">Abono</option>
													<option value="2">Cancelación</option>
												</select>
												<div class="invalid-tooltip">
													Seleccione un tipo de proceso.
												</div>
											</div>
										</div>
									</div>

									<div class="text-center">
										<input type="hidden" class="form-control" id="idPaqueteA" name="idPaquete" required>
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar </button>
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
<!-- Fin Modal actualizar paquete -->

<!-- Modal para eliminar paquete-->
	<div class="modal fade" id="eliminarPaquete" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">
				<form action="<?php echo base_url() ?>Hoja/eliminar_paquete/" method="post">
					<div class="modal-header bg-danger">
						<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
								class="text-white">&times;</span></button>
					</div>

					<div class="modal-body text-center">
						<p class="h5">¿Estas seguro de eliminar este paquete?</p>
						<input type="hidden" class="form-control" name="idPaqueteEliminar" id="idPaqueteEliminar" required>
					</div>

					<div class="text-center">
						<button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar </button>
						<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar </button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin Modal eliminar  paquete-->

<!-- Modal para eliminar paquete-->
	<div class="modal fade" id="reciboPaquete" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">
				<form action="<?php echo base_url() ?>Hoja/generar_recibo_paquete/" method="post">
					<div class="modal-header bg-danger">
						<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
								class="text-white">&times;</span></button>
					</div>

					<div class="modal-body text-center">
						<p class="h5">¿Estas seguro de generar el recibo del paquete?</p>
						<input type="hidden" class="form-control" name="idPaqueteEditar" id="idPaqueteEditar" required>
					</div>

					<div class="text-center">
						<button type="submit" class="btn btn-danger shadow-none"> Generar <i class="fa fa-arrow-right"></i> </button>
						<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar </button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin Modal eliminar  paquete-->

<script>
	$(document).ready(function() {
		$(".controlInteligente").select2({
			theme: 'bootstrap4',
			dropdownParent: $("#agregarPaquete")
		});
	});

	$(document).ready(function() {
		$(".controlInteligente2").select2({
			theme: 'bootstrap4',
			dropdownParent: $("#actualizarPaquete")
		});
	});

	$(document).on("click", ".actualizarPaquete", function(event) {
		event.preventDefault();
						// $(this).closest('tr').find(".txtCantidad").val();
		idPaquete = $(this).closest("tr").find(".idPaquete").val();
		fechaPaquete = $(this).closest("tr").find(".fechaPaquete").val();
		medicoPaquete = $(this).closest("tr").find(".medicoPaquete").val();
		pacientePaquete = $(this).closest("tr").find(".pacientePaquete").val();
		cantidadPaquete = $(this).closest("tr").find(".cantidadPaquete").val();
		tipoPaquete = $(this).closest("tr").find(".conceptoPaquete").val();

		$("#fechaPaqueteA").val(fechaPaquete);
		$("#medicoPaqueteA").val(medicoPaquete);
		$("#pacientePaqueteA").val(pacientePaquete);
		$("#cantidadPaqueteA").val(cantidadPaquete);
		$("#tipoPaqueteA").val(tipoPaquete);
		$("#idPaqueteA").val(idPaquete);
		
	});

	$(document).on("click", ".eliminarPaquete", function(event) {
		event.preventDefault();
		idPaquete = $(this).closest("tr").find(".idPaquete").val();
		$("#idPaqueteEliminar").val(idPaquete);
	});

	$(document).on("click", ".recibo_paquete", function() {
		var paquete =  $(this).closest("tr").find(".idPaquete").val();
		$("#idPaqueteEditar").val(paquete);
	});

	$(document).on('change', '#duiPaciente', function (event) {
        event.preventDefault();
        var duiPaciente = $(this).val();
        if(duiPaciente == "00000000-0"){
            toastr.remove();
            toastr.options = {
                "positionClass": "toast-top-left",
                "showDuration": "5000",
                "hideDuration": "5000",
                "timeOut": "5000",
                "extendedTimeOut": "5000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
                },
            toastr.error('El número de DUI es invalido y debera ser actualizado...', 'Aviso!');

            // Paciente
				$("#idPaciente").val("0");
				$("#pacientePaquete").val("");
			// Paciente

        }else{
            $.ajax({
                url: "../Paciente/validar_paciente",
                type: "POST",
                data: {dui: duiPaciente },
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            var datos = registro.datos;
                            // Paciente
								$("#idPaciente").val(datos["idPaciente"]);
								$("#pacientePaquete").val(datos["nombrePaciente"]);
                            // Paciente
                        }else{
                            // Paciente
								$("#idPaciente").val("0");
								$("#pacientePaquete").val("");
                            // Paciente
                        }
                    }
                }
            });
        }

    });
	
</script>