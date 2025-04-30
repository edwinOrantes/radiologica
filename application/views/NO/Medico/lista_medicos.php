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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-user-md"></i> Médicos </a> </li>
                    <li class="breadcrumb-item"><a href="#">Lista médicos</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de médicos</h6></div>
                        <div class="col-md-6 text-right">
                                <button class="btn btn-primary btn-sm" href="#agregarMedico" data-toggle="modal"><i class="fa fa-plus"></i> Agregar Médico</button>
                                <a href="<?php echo base_url()?>Medico/medicos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                                <!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Nombre</th>
                                        <th class="text-center" scope="col">Especialidad</th>
                                        <th class="text-center" scope="col">Telefono</th>
                                        <th class="text-center" scope="col">Dirección</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>

									<?php
									$index = 0;
										foreach ($medicos as $medico) {
											$index++;
											$id ='"'.$medico->idMedico.'"';
											$nombre = '"'.$medico->nombreMedico.'"';
											$especialidad = '"'.$medico->especialidadMedico.'"';
											$telefono = '"'.$medico->telefonoMedico.'"';
											$direccion = '"'.$medico->direccionMedico.'"';
									?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $medico->nombreMedico; ?></td>
                                        <td class="text-center"><?php echo $medico->especialidadMedico; ?></td>
                                        <td class="text-center"><?php echo $medico->telefonoMedico; ?></td>
                                        <td class="text-center"><?php echo $medico->direccionMedico; ?></td>
                                        <td class="text-center">
										<?php
                                            //echo "<a onclick='verDetalle($id, $nombre, $especialidad, $telefono, $direccion)' href='#verMedico' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
                                            echo "<a onclick='actualizarMedico($id, $nombre, $especialidad, $telefono, $direccion)' href='#actualizarMedico' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
											switch($this->session->userdata('nivel')) {
												case '1':
													echo "<a onclick='eliminarMedico($id)' href='#eliminarMedicamento' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
												break;
												default:
													echo "";
													break;
											}
										?>
                                        </td>
                                    </tr>

									<?php }	?>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="agregarMedico" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos del médico</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">

							<form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Medico/guardar_medico"  novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Nombre</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreMedico" name="nombreMedico" placeholder="Nombre del médico" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
										</div>
									</div>
                                    
																		
								</div>
                                
								<div class="form-row">

									<div class="col-md-12">
										<label for=""><strong>Especialidad</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="especialidadMedico" name="especialidadMedico" placeholder="Especialidad del médico" required>
                                            <div class="invalid-tooltip">
                                                Ingrese una especialidad.
                                            </div>
										</div>
									</div>

								</div>

								<div class="form-row">

									<div class="col-md-12">
										<label for=""><strong>Teléfono</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" data-mask="9999-9999" id="telefonoMedico" name="telefonoMedico" placeholder="Teléfono de la clinica" required>
                                            <div class="invalid-tooltip">
                                            Ingrese el teléfono del médico.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Dirección</strong></label>
                                        <textarea class="form-control disableSelect" id="direccionMedico" name="direccionMedico" required></textarea>
                                        <div class="invalid-tooltip">
                                            Ingrese la dirección del médico.
                                        </div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar médico</button>
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
<!-- Fin Modal para agregar datos del Medicamento-->


<!-- Modal ver datos del Medicamento-->
<div class="modal fade" id="verMedico" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i>  Datos del médico</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body" id="detalleMedico">
							
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Fin Modal ver datos del Medicamento-->

<!-- Modal para actualizar datos del Medicamento-->
<div class="modal fade" id="actualizarMedico" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos del médico</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" id="frmMedico" method="post" action="<?php echo base_url() ?>Medico/actualizar_medico" novalidate>
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Nombre</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreMedicoA" name="nombreMedicoA" placeholder="Nombre del médico" required>
											<input type="hidden" class="form-control" id="idMedicoA" name="idMedicoA" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
										</div>
									</div>
								</div>
                                
								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Especialidad</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="especialidadMedicoA" name="especialidadMedicoA" placeholder="Especialidad del médico" required>
                                            <div class="invalid-tooltip">
                                                Ingrese una especialidad.
                                            </div>
										</div>
									</div>

								</div>

								<div class="form-row">
									
									<div class="col-md-12">
										<label for=""><strong>Teléfono</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" data-mask="9999-9999" id="telefonoClinicaA" name="telefonoClinicaA" placeholder="Teléfono de la clinica" required>
                                            <div class="invalid-tooltip">
                                            Ingrese el teléfono del medico.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Dirección</strong></label>
                                        <textarea class="form-control disableSelect" id="direccionMedicoA" name="direccionMedicoA" required></textarea>
                                        <div class="invalid-tooltip">
                                            Ingrese la dirección del médico.
                                        </div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar médico</button>
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
<!-- Fin Modal actualizar datos del Medicamento-->


<!-- Modal para eliminar datos del Medicamento-->
<div class="modal fade" id="eliminarMedicamento" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">
			<form action="<?php echo base_url() ?>Medico/eliminar_medico" method="post">
				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este médico ?</p>
					<input type="hidden" id="idEliminar" name="idEliminar"/>
				</div>

				<div class="text-center">
					<button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
					<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- Fin Modal eliminar  datos del Medicamento-->

<script>
	function verDetalle(id, nombre, especialidad, telefono, direccion){
		var html= '';
		html += '<table class="table table-borderless">';
			html += '<tr>';
				html += '<td><strong>Nombre</strong></td>';
				html += '<td>'+nombre+'</td>';
				html += '<td><strong>Especialidad</strong></td>';
				html += '<td>'+especialidad+'</td>';
			html += '</tr>';

			html += '<tr>';
				html += '<td><strong>Telefono</strong></td>';
				html += '<td>'+telefono+'</td>';
				html += '<td><strong>Dirección</strong></td>';
				html += '<td>'+direccion+'</td>';
			html += '</tr>';

		html += '</table>';

		document.getElementById('detalleMedico').innerHTML = html;
	}

	function actualizarMedico(id, nombre, especialidad, telefono, direccion){
		$("#idMedicoA").val(id);
		$("#nombreMedicoA").val(nombre);
		$("#especialidadMedicoA").val(especialidad);
		$("#telefonoClinicaA").val(telefono);
		$("#direccionMedicoA").val(direccion);
	}
	
	function eliminarMedico(id){
		$("#idEliminar").val(id);
	}
</script>