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
                    <li class="breadcrumb-item"><a href="#">Examenes</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <h6>Lista de examenes</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
                        <div class="table-responsive mt-3">
                            <?php
                                if(sizeof($paciente) > 0){
                            ?>
                            <table id="tabla-consultas" class="table table-striped thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Paciente</th>
                                        <th class="text-center" scope="col">Médico</th>
                                        <th class="text-center" scope="col">Destino</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    foreach ($paciente as $paciente) {
                                        $id ='"'.$paciente->idPaciente.'"'; // Id del paciente.
                                        $nombre ='"'.$paciente->nombrePaciente.'"'; // Nombre del paciente.
                                        $edad ='"'.$paciente->edadPaciente.'"'; // Edad del paciente.
                                        $medico ='"'.$paciente->idMedico.'"'; // Id del medico.
                                        $idConsultaLaboratorio ='"'.$paciente->idConsultaLaboratorio.'"'; // Id del medico.
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $paciente->codigoConsulta; ?></td>
                                        <td class="text-center">
											<?php
											if($paciente->consultaOnline > 0){
												echo $paciente->nombrePaciente.' <i class="fa fa-globe fa-sm text-success"></i>';
											}else{
												echo $paciente->nombrePaciente;
											}
											
											?>
										</td>
                                        <td class="text-center"><?php echo $paciente->nombreMedico; ?></td>
                                        <td class="text-center"><span class="badge badge-success"><?php echo $paciente->nombreTipoConsultaLab; ?></span></td>
                                        <td class="text-center"><?php echo substr($paciente->fechaConsultaLaboratorio, 0, 10); ?></td>
										<td class="text-center">
                                            <?php
                                                echo "<a href='#editarPaciente' onclick='editarPaciente($id, $nombre,$edad, $medico, $idConsultaLaboratorio)' title='Editar paciente' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                            ?>
                                            <a href="<?php echo base_url(); ?>Laboratorio/detalle_consulta/<?php echo $paciente->idConsultaLaboratorio; ?>/" target="blank" title="Ver detalle"><i class="fas fa-eye ms-text-primary"></i></a>
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
	<div class="modal fade" id="editarPaciente" tabindex="-1" role="dialog" aria-hidden="true">
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
								<form class="needs-validation" id="frmHpjaCobro" method="post" action="<?php echo base_url() ?>Laboratorio/actualizar_paciente" novalidate>
									
									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Nombre:</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" required>
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
												<input type="text" class="form-control" id="edadPaciente" name="edadPaciente" required>
												<div class="invalid-tooltip">
													Debes ingresar la edad del paciente.
												</div>
											</div>
										</div>
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for=""><strong>Médico:</strong></label>
											<div class="input-group">
													<select class="form-control controlInteligente" id="medicoHoja" name="idMedico" required>
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
									</div>
									
									<input type="hidden" class="form-control" id="idPaciente" name="idPaciente" required>
									<input type="hidden" class="form-control" id="idConsultaLaboratorio" name="idConsultaLaboratorio" required>
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
<!-- Fin Modal editar datos del paciente-->

<script>
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
			dropdownParent: $("#editarPaciente")
        });
	});

</script>
