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
						<li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Laboratorio </a> </li>
						<li class="breadcrumb-item"><a href="#"> Seleccionar paciente</a></li>
					</ol>
				</nav>

				<div class="ms-panel">
					<div class="ms-panel-header ms-panel-custome">
						<h6>Lista de pacientes recientes</h6>
					</div>
					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<?php
									if(sizeof($pacientes) > 0){
								?>
								<table id="tabla-consultas" class="table table-striped thead-primary w-100">
									<thead>
										<tr>
											<th class="text-center" scope="col">#</th>
											<th class="text-center" scope="col">Paciente</th>
											<th class="text-center" scope="col">Edad</th>
											<th class="text-center" scope="col">DUI</th>
											<th class="text-center" scope="col">Teléfono</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>

									<?php
										$index = 0;
										foreach ($pacientes as $paciente) {
											$index++;
									?>
										<tr>
											<td class="text-center"><?php echo $index; ?></td>
											<td class="text-center"><?php echo $paciente->nombrePaciente; ?></td>
											<td class="text-center"><?php echo $paciente->edadPaciente; ?></td>
											<td class="text-center"><?php echo $paciente->duiPaciente; ?></td>
											<td class="text-center"><?php echo $paciente->telefonoPaciente; ?></td>
											<td class="text-center">
												<input type="hidden" value="<?php echo $paciente->idPaciente; ?>" class="pacienteConsulta">
												<input type="hidden" value="<?php echo $paciente->idCola; ?>" class="idCola">
												<?php
													if($paciente->consultaGenerada > 0 && date("Y-m-d") == $paciente->fechaConsulta){
														echo '<a href="'.base_url().'Laboratorio/detalle_consulta/'.$paciente->consultaGenerada.'/" title="Ver consulta" class="text-success"><i class="fas fa-arrow-right"></i></a>';
													}else{
														echo '<a href="#crearConsulta" data-toggle="modal" title="Crear consulta" class="text-primary crearConsulta"><i class="fas fa-file-export"></i></a>';
													}
												?>
												
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
								<?php
									}else{
										echo '<div class="alert-danger p-3 bold text-center">No hay datos que mostrar.</div>';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Modal para editar datos del paciente-->
	<div class="modal fade" id="crearConsulta" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos de la consulta</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" id="frmHpjaCobro" method="post" action="<?php echo base_url() ?>Laboratorio/crear_consulta" novalidate>
									
									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Código:</strong></label>
											<div class="input-group">
												<input type="text" value="<?php echo $codigo->codigoConsulta + 1; ?>" class="form-control" readonly>
												<input type="hidden" value="<?php echo $codigo->codigoConsulta + 1; ?>" class="form-control" id="nombrePaciente" name="codigo">
												<div class="invalid-tooltip">
													Debes ingresar el código.
												</div>
											</div>
										</div>

										<div class="col-md-12">
											<label for=""><strong>Fecha:</strong></label>
											<div class="input-group">
												<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="fechaConsulta" name="" readonly>
												<div class="invalid-tooltip">
													Debes ingresar la fecha.
												</div>
											</div>
										</div>

										<div class="col-md-12">
											<label for=""><strong>Médico:</strong></label>
											<div class="input-group">
													<select class="form-control controlInteligente" id="medicoConsulta" name="medicoConsulta" required>
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

										<div class="col-md-12">
											<label for=""><strong>Tipo</strong></label>
											<div class="input-group">
												<select class="form-control controlInteligente" id="tipoConsulta" name="tipoConsulta" required>
													<?php
														foreach ($tipo as $row) {
															if($row->idTipoConsultaLab == 1){
																echo '<option value="'.$row->idTipoConsultaLab.'" selected="selected">'.$row->nombreTipoConsultaLab.'</option>';
															}else{
																if($row->idTipoConsultaLab != 3){
																	echo '<option value="'.$row->idTipoConsultaLab.'">'.$row->nombreTipoConsultaLab.'</option>';
																}
															}
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Seleccione un tipo de hoja.
												</div>  
											</div>
										</div>

										<div class="col-md-12">
											<label for=""><strong>Habitación</strong></label>
											<div class="input-group">
												
												<select class="form-control" id="habitacionHoja" name="idHabitacion" required>
													<option value="37">.:: Seleccionar ::.</option>
													<?php 
														foreach ($habitaciones as $habitacion) {
															if($habitacion->idHabitacion <= 36){
													?>
															<option value="<?php echo $habitacion->idHabitacion; ?>"><?php echo $habitacion->numeroHabitacion; ?></option>
													<?php }} ?>
												</select>

												<div class="invalid-tooltip">
													Ingrese el numero de habitacion del paciente.
												</div>
											</div>
										</div>


									</div>

									
									<input type="hidden" class="form-control" id="idPaciente" name="idPaciente" required>
									<input type="hidden" value="<?php echo $paciente->idHoja; ?>" class="form-control" id="idHoja" name="idHoja" required>
									<input type="hidden" class="form-control" id="idCola" name="idCola" required>
									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Crear consulta </button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal editar datos del paciente-->

<script>

	$(document).on("click", ".crearConsulta", function(e) {
		e.preventDefault();
		$("#idCola").val($(this).closest("tr").find(".idCola").val());
		$("#idPaciente").val($(this).closest("tr").find(".pacienteConsulta").val());
	});

    function editarPaciente(id, nombre,edad, medico, idConsultaLaboratorio){
        document.getElementById("idPaciente").value =  id;
        document.getElementById("nombrePaciente").value =  nombre;
        document.getElementById("edadPaciente").value =  edad;
        document.getElementById("medicoHoja").value =  medico;
        document.getElementById("idConsultaLaboratorio").value = idConsultaLaboratorio;
		// console.log(id, nombre,edad, medico, idConsultaLaboratorio);
    }

	$(document).ready(function() {
		$("#tabla-consultas").DataTable({
			//stateSave: true,
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
			},
			"order": [[ 0, "desc"]]
		})
	});

	$(document).ready( function(){
		$('.controlInteligente').select2({
            theme: "bootstrap4",
			dropdownParent: $("#crearConsulta")
        });
	});

</script>