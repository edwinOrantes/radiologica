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
						<h6>Lista de pacientes recientes Clínica Urológica</h6>
					</div>
					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<?php
									if(sizeof($pacientes) > 0){
								?>
								<table id="tabla-consultas" class="table table-bordered thead-urologica w-100">
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
											<td class="text-center"><?php echo $paciente["nombrePaciente"]; ?></td>
											<td class="text-center"><?php echo $paciente["edadPaciente"]; ?></td>
											<td class="text-center"><?php echo $paciente["duiPaciente"]; ?></td>
											<td class="text-center"><?php echo $paciente["telefonoPaciente"]; ?></td>
											<td class="text-center">
												<input type="hidden" value="<?php echo $paciente["nombrePaciente"]; ?>" class="nombrePaciente">
												<input type="hidden" value="<?php echo $paciente["edadPaciente"]; ?>" class="edadPaciente">
												<input type="hidden" value="<?php echo $paciente["duiPaciente"]; ?>" class="duiPaciente">
												<input type="hidden" value="<?php echo $paciente["direccionPaciente"]; ?>" class="direccionPaciente">
												<input type="hidden" value="<?php echo $paciente["telefonoPaciente"]; ?>" class="telefonoPaciente">
												<input type="hidden" value="<?php echo $paciente["nacimientoPaciente"]; ?>" class="nacimientoPaciente">
												<input type="hidden" value="<?php echo $paciente["pivoteOrellana"]; ?>" class="pivoteOrellana">
												<input type="hidden" value="<?php echo $paciente["idPaciente"]; ?>" class="pacienteConsulta">
												<input type="hidden" value="<?php echo $paciente["idConsulta"]; ?>" class="idConsulta">
												<?php
													if($paciente["existeLab"] > 0 && date("Y-m-d") == $paciente["fechaConsulta"]){
														echo '<a href="'.base_url().'Laboratorio/detalle_consulta/'.$paciente["existeLab"].'/" title="Ver consulta" class="text-success"><i class="fas fa-arrow-right"></i></a>';
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

<!-- Fin Modal editar datos del paciente-->

<div class="modal fade" id="crearConsulta" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">

			<div class="modal-header bg-primary">
				<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="ms-panel-body">
				<div class="text-center">
					<h5 class="text-danger bold">¿Esta seguro de crear la consulta?</h5>
				</div>
				<form class="needs-validation" id="frmHojaCobro" method="post" action="<?php echo base_url() ?>Laboratorio/guardar_paciente" novalidate>
					
					<div class="form-row">
						<div class="col-md-12">
							<label for=""><strong>Código:</strong></label>
							<div class="input-group">
								<input type="text" value="<?php echo $codigo->codigoConsulta + 1; ?>" class="form-control" readonly>
								<input type="hidden" value="<?php echo $codigo->codigoConsulta + 1; ?>" class="form-control"  name="codigoHoja">
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

					</div>

					<input type="hidden" id="nombrePacienteA" name="nombrePaciente">
					<input type="hidden" id="edadPacienteA" name="edadPaciente">
					<input type="hidden" id="duiPacienteA" name="duiPaciente">
					<input type="hidden" id="direccionPacienteA" name="direccionPaciente">
					<input type="hidden" id="telefonoPacienteA" name="telefonoPaciente">
					<input type="hidden" id="nacimientoPacienteA" name="nacimientoPaciente">
					<input type="hidden" id="pivoteOrellanaA" name="pivoteOrellana">
					
					<input type="hidden" value="2" name="idMedico" required> <!-- 2 = Dr. Bernabel -->
					<input type="hidden" value="3" name="tipoConsulta" required> <!-- 3 = Urologica -->
					<input type="hidden" value="36" name="idHabitacion" required> <!-- 36 = Pendiente -->

					<input type="hidden" id="idPacienteHospital" value="0" name="idPacienteHospital" required>
					<input type="hidden" id="idPacienteUrologica" value="0" name="idPacienteUrologica" required>
					<input type="hidden" value="0" id="idConsultaUrologica" name="idHoja" required>
					<input type="hidden" value="0" id="idCola" name="idCola" required>
					<div class="text-center">
						<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Crear consulta </button>
					</div>
				</form>
			</div>


		</div>
	</div>
</div>

<script>

	$(document).on("click", ".crearConsulta", function(e) {
		e.preventDefault();
		$("#idPacienteUrologica").val($(this).closest("tr").find(".pacienteConsulta").val());

		$("#nombrePacienteA").val($(this).closest("tr").find(".nombrePaciente").val());
		$("#edadPacienteA").val($(this).closest("tr").find(".edadPaciente").val());
		$("#duiPacienteA").val($(this).closest("tr").find(".duiPaciente").val());
		$("#direccionPacienteA").val($(this).closest("tr").find(".direccionPaciente").val());
		$("#telefonoPacienteA").val($(this).closest("tr").find(".telefonoPaciente").val());
		$("#nacimientoPacienteA").val($(this).closest("tr").find(".nacimientoPaciente").val());
		$("#pivoteOrellanaA").val($(this).closest("tr").find(".pivoteOrellana").val());
		$("#idConsultaUrologica").val($(this).closest("tr").find(".idConsulta").val());

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