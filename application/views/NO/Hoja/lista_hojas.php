<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Hoja </a> </li>
                    <li class="breadcrumb-item"><a href="#">Listado de hojas</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <div class="col-md-6"><h6>Lista de hojas de cobro</h6></div>
                    <div class="col-md-6 text-right">
						<?php
							if($this->session->userdata("id_usuario_h") != 11){
								echo '<span class="badge badge-dark badge-square">Saldada</span> ';
								echo ' <span class="badge badge-danger badge-square">Pendiente</span> ';
								echo ' <span class="badge badge-warning badge-square">Cancelará médico</span> ';

							}
						?>
					</div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
                        <div class="table-responsive mt-3">
							<?php
								if (sizeof($hojas) > 0) {
								
							?>
                            <table id="" class="table table-striped thead-primary w-100 tablaHojas">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Paciente</th>
                                        <th class="text-center" scope="col">Médico</th>
                                        <th class="text-center" scope="col">Habitación</th>
										<?php echo $this->session->userdata('global') != 1 ? '<th class="text-center" scope="col">Fecha de ingreso</th>' :" ";?>
                                        <th class="text-center" scope="col">Seguro</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
									$index = 0;
									foreach ($hojas as $hoja) {
										if($hoja->anulada == 0){
										$index++;
										$id ='"'.$hoja->idHoja.'"'; // Id de la hoja.
										$fecha ='"'.$hoja->fechaHoja.'"'; // Fecha de la hoja.
										$habitacion ='"'.$hoja->idHabitacion.'"'; // Fecha de la hoja.
										$medico ='"'.$hoja->idMedico.'"'; // Medico de la hoja.
										$tipo ='"'.$hoja->tipoHoja.'"'; // Tipo de hoja Ambulatoria o Ingreso.
										$edad ='"'.$hoja->edadPaciente.'"'; // Tipo de hoja Ambulatoria o Ingreso.
										$pac ='"'.$hoja->nombrePaciente.'"'; // Tipo de hoja Ambulatoria o Ingreso.
										$destino ='"'.$hoja->destinoHoja.'"'; // Destino de la hoja de cobro.

										if($hoja->correlativoSalidaHoja == 0 ){
											if($hoja->pagaMedico == 1){
												echo '<tr class="text-warning">';
											}else{
												echo '<tr class="text-danger">';
											}
										}else{
											echo '<tr>';
										}

								?>
									
										<td class="text-center">
											<?php
												$codigo = 0;
												if($this->session->userdata('global') == 1){
													$codigo = $hoja->seguimientoHoja;
												}else{
													if($hoja->porPagos == 1){
														$codigo = $hoja->codigoHoja."-A";
													}else{
														$codigo = $hoja->codigoHoja;
													}
												}
												echo $codigo;
											?>
										</td>
										
										<td class="text-center"><?php echo $hoja->nombrePaciente; ?></td>
										<td class="text-center"> 
											<?php
												if($hoja->esPaquete == 1){
													echo $hoja->nombreMedico.'<span class="badge badge-primary"> Paquete </span>'; 
												}else{
													echo $hoja->nombreMedico; 
												}
											?>
										</td>
										<td class="text-center"><?php echo $hoja->numeroHabitacion; ?></td>
										<?php echo $this->session->userdata('global') != 1 ? '<td class="text-center">'.$hoja->fechaHoja.'</td>' :" ";?>
										
										<td class="text-center">
											<?php
												if($hoja->seguroHoja > 1){
													echo '<span class="badge badge-success">'.$hoja->nombreSeguro.'</span>';
												}else{
													echo '<span class="badge badge-info">N/A</span>';
												}
											?>
										</td>
										<td class="text-center">
										<?php
											if ($hoja->estadoHoja == 1) {
												echo "<a href='#editarHoja' onclick='editaHoja($id, $fecha, $habitacion, $medico, $tipo, $destino)' data-toggle='modal' title='Editar detalle de la hoja'><i class='fas fa-edit ms-text-primary'></i></a>";
												if($hoja->tipoHoja == "Ambulatorio"){
													echo "<a href='#editarHoja2' onclick='editaHoja2($id)' data-toggle='modal' title='Cambiar paciente'><i class='fas fa-user ms-text-primary'></i></a>";
												}
												
												echo "<a href='".base_url()."Hoja/detalle_hoja/".$hoja->idHoja."/' title='Agregar detalle'><i class='fas fa-file-signature ms-text-primary'></i></a>";
											}else{
												echo "<a href='".base_url()."Hoja/detalle_hoja/".$hoja->idHoja."/' title='Ver detalle'><i class='far fa-eye ms-text-primary'></i></a>";
											}
										?>
										</td>
									</tr>
								<?php }} ?>
								</tbody>
                            </table>
							<?php }else{
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

<!-- Modal para editar fechas de hojas-->
	<div class="modal fade" id="editarHoja" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos de la hoja</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" id="frmHpjaCobro" method="post" action="<?php echo base_url() ?>Hoja/actualizar_hoja" novalidate>
									
									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Fecha de la hoja:</strong></label>
											<div class="input-group">
												<input type="date" class="form-control focusNext" id="fechaHoja" name="fechaHoja" required>
												<div class="invalid-tooltip">
													Debes ingresar la fecha de de la hoja.
												</div>
											</div>
										</div>
										
									</div>
									
									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Tipo</strong></label>
											<div class="input-group">
												<select class="form-control" id="tipoHoja" name="tipoHoja" required>
													<option value="">.:: Seleccionar ::.</option>
													<option value="Ingreso">Ingreso</option>
													<option value="Ambulatorio">Ambulatorio</option>
													<!--<option value="Otro">Otro</option>-->
												</select>
												<div class="invalid-tooltip">
													Seleccione un tipo de hoja.
												</div>
											</div>
										</div>
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Médico</strong></label>
											<div class="input-group">
												
												<select class="form-control focusNext controlInteligente" id="idMedico" name="idMedico" required>
													<option value="">.:: Seleccionar ::.</option>
													<?php 
														$clase = "";
														foreach ($medicos as $medico) {
															?>
													
															<option class="" value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
													
													<?php } ?>
												</select>

												<div class="invalid-tooltip">
													Ingrese el numero de habitacion del paciente.
												</div>
											</div>
										</div>
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Habitación</strong></label>
											<div class="input-group">
												
												<select class="form-control focusNext" id="idHabitacion" name="idHabitacion" required>
													<option value="">.:: Seleccionar ::.</option>
													<?php 
														$clase = "";
														foreach ($habitaciones as $habitacion) {
															if($habitacion->estadoHabitacion == 1){
																$clase = "";
															}else{
																$clase = "";
															}
															?>
													
															<option class="<?php echo $clase; ?>" value="<?php echo $habitacion->idHabitacion; ?>"><?php echo $habitacion->numeroHabitacion; ?></option>
													
													<?php } ?>
												</select>

												<div class="invalid-tooltip">
													Ingrese el numero de habitacion del paciente.
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Destino</strong></label>
											<div class="input-group">
												<select name="destinoHoja" id="destinoHoja" class="form-control" required>
													<option value="">.::Seleccionar::.</option>
													<option value="1">Consulta</option>
													<option value="2">Ultrasonografía</option>
													<option value="3">Rayos X</option>
													<option value="4">Laboratorio</option>
													<option value="5">Hemodiálisis</option>
													<option value="6">Paquete</option>
													<option value="7">Cirugía </option>
												</select>
												<div class="invalid-tooltip">
													Ingrese el destino del paciente
												</div>
											</div>
										</div>
									</div>

									
									<input type="hidden" class="form-control" id="procedimientoHoja" name="procedimientoHoja" required>
									<input type="hidden" class="form-control" id="idHoja" name="idHoja" required>
									<input type="hidden" class="form-control" id="habitacionVieja" name="habitacionVieja" required>
									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar datos</button>
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
<!-- Fin Modal editar fechas de hojas-->


<!-- Modal para editar fechas de hojas-->
	<div class="modal fade" id="editarHoja2" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos de la hoja</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" id="frmHpjaCobro" method="post" action="<?php echo base_url() ?>Hoja/actualizar_paciente_hoja" novalidate>
									
									<!-- <div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Paciente: </strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="pacienteHoja" name="nombrePaciente" required>
												<div class="invalid-tooltip">
													Debes ingresar el nombre del paciente.
												</div>
											</div>
										</div>
										
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Edad:</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="edadHoja" name="edadPaciente" required>
												<div class="invalid-tooltip">
													Debes ingresar la edad del paciente.
												</div>
											</div>
										</div>
										
									</div> -->
									
									<div class="form-row">
										<div class="form-group col-md-12">
											<label for=""><strong>DUI</strong></label>
											<input type="text" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>
											<div class="invalid-tooltip">
												Debes ingresar el DUI del paciente.
											</div>
										</div>
	
										<div class="form-group col-md-12">
											<label for=""><strong>Nombre Completo</strong></label>
											<input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" list='recomendacionesPacientes'  required>
											<div class="invalid-tooltip">
												Debes ingresar el nombre del paciente.
											</div>
										</div>

										<div class="form-group col-md-12">
											<label for=""><strong>Edad</strong></label>
											<div class="input-group">
											<input type="number" class="form-control numeros" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>
												<div class="invalid-tooltip">
													Ingrese la edad del paciente
												</div>
											</div>
										</div>
									</div>

									<input type="hidden" class="form-control" id="idHojaU" name="idHoja" required>
									<input type="hidden" class="form-control" id="idPacienteU" name="idPacienteU" required>
									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar paciente</button>
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
<!-- Fin Modal editar fechas de hojas-->


<script>
	$(document).ready(function() {
		$(".controlInteligente").select2({
			theme: 'bootstrap4'
		});
	});

	function editaHoja(id, fecha, habitacion, medico, tipo, destino){
		$("#fechaHoja").val(fecha);
		$("#idHabitacion").val(habitacion);
		$("#habitacionVieja").val(habitacion);
		$("#idMedico").val(medico);
		$("#idHoja").val(id);
		$("#tipoHoja").val(tipo);
		$("#destinoHoja").val(destino);
		$("#procedimienotoHoja").val(destino);
		// console.log(id, fecha, habitacion, medico, tipo, destino);
	}

	function editaHoja2(idHoja){
		$("#idHojaU").val(idHoja);
	}

	document.addEventListener('keypress', function(evt) {

		// Si el evento NO es una tecla Enter
		if (evt.key !== 'Enter') {
		return;
		}

		let element = evt.target;

		// Si el evento NO fue lanzado por un elemento con class "focusNext"
		if (!element.classList.contains('focusNext')) {
		return;
		}

		// AQUI logica para encontrar el siguiente
		let tabIndex = element.tabIndex + 1;
		var next = document.querySelector('[tabindex="'+tabIndex+'"]');

		// Si encontramos un elemento
		if (next) {
		next.focus();
		event.preventDefault();
		}
	});

	$(document).on('change', '#duiPaciente', function (event) {
		event.preventDefault();
		var duiPaciente = $(this).val();
		var datos = {
			paciente: $(this).val(),
			pivote: 0
		}
		$.ajax({
				url: "../Paciente/validar_paciente",
				type: "POST",
				data: datos,
				success:function(respuesta){
					var registro = eval(respuesta);
					if (Object.keys(registro).length > 0){
						if(registro.estado == 1){
							var datos = registro.datos;
							// Paciente
								$("#idPacienteU").val(datos["idPaciente"]);
								$("#nombrePaciente").val(datos["nombrePaciente"]);
								$("#edadPaciente").val(datos["edadPaciente"]); 
						}else{
							$("#idPacienteU").val("");
							$("#duiPaciente").val("");
							$("#nombrePaciente").val("");
							$("#edadPaciente").val("");
							toastr.remove();
							toastr.options = {
								"positionClass": "toast-top-left",
								"showDuration": "300",
								"hideDuration": "1000",
								"timeOut": "1000",
								"extendedTimeOut": "50",
								"showEasing": "swing",
								"hideEasing": "linear",
								"showMethod": "fadeIn",
								"hideMethod": "fadeOut"
								},
							toastr.error('Paciente no existe...', 'Aviso!');

						}
					}else{
						toastr.remove();
						toastr.options = {
							"positionClass": "toast-top-left",
							"showDuration": "300",
							"hideDuration": "1000",
							"timeOut": "1000",
							"extendedTimeOut": "50",
							"showEasing": "swing",
							"hideEasing": "linear",
							"showMethod": "fadeIn",
							"hideMethod": "fadeOut"
							},
							toastr.error('Paciente no existe...', 'Aviso!');
					}
				}
			});

	});

	$(document).on('change', '#nombrePaciente', function (event) {
		event.preventDefault();
		var duiPaciente = $(this).val();
		var datos = {
			paciente: $(this).val(),
			pivote: 1
		}
		$.ajax({
				url: "../Paciente/validar_paciente",
				type: "POST",
				data: datos,
				success:function(respuesta){
					var registro = eval(respuesta);
					if (Object.keys(registro).length > 0){
						if(registro.estado == 1){
							var datos = registro.datos;
							// Paciente
								$("#idPacienteU").val(datos["idPaciente"]);
								$("#duiPaciente").val(datos["duiPaciente"]);
								$("#nombrePaciente").val(datos["nombrePaciente"]);
								$("#edadPaciente").val(datos["edadPaciente"]); 
						}else{
							$("#idPacienteU").val("");
							$("#duiPaciente").val("");
							$("#nombrePaciente").val("");
							$("#edadPaciente").val("");
							toastr.remove();
							toastr.options = {
								"positionClass": "toast-top-left",
								"showDuration": "300",
								"hideDuration": "1000",
								"timeOut": "1000",
								"extendedTimeOut": "50",
								"showEasing": "swing",
								"hideEasing": "linear",
								"showMethod": "fadeIn",
								"hideMethod": "fadeOut"
							},
							toastr.error('Paciente no existe...', 'Aviso!');

						}
					}else{
						toastr.remove();
						toastr.options = {
							"positionClass": "toast-top-left",
							"showDuration": "300",
							"hideDuration": "1000",
							"timeOut": "1000",
							"extendedTimeOut": "50",
							"showEasing": "swing",
							"hideEasing": "linear",
							"showMethod": "fadeIn",
							"hideMethod": "fadeOut"
						},
						toastr.error('Paciente no existe...', 'Aviso!');
					}
				}
			});

	});
</script>