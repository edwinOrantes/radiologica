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
					<h6> Información de la hoja de cobro</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>Hoja/guardar_hoja" novalidate>
								<div class="row">
									<div class="col-md-12">
										
                                        <div class="form-row">

                                            <div class="col-md-6">
												<label for=""><strong>Código</strong></label>
												<div class="input-group">
                                                    <input type="text" class="form-control" value="<?php echo $codigo; ?>" id="codigoHoja" readonly/>
                                                    <input type="hidden" class="form-control" value="<?php echo $codigo; ?>" name="codigoHoja"/>
													<div class="invalid-tooltip">
														Seleccione un tipo de documento.
													</div>
												</div>
											</div>
											
                                            <div class="col-md-6">
												<label for=""><strong>Nombre del paciente</strong></label>
												<div class="input-group">
													<input type="text" class="form-control" id="nombrePaciente" placeholder="Nombre del paciente" readonly required>
													<input type="hidden" class="form-control" id="idPaciente" name="idPaciente" placeholder="Nombre del paciente" required>
													<div class="input-group-append">
														<a class="btn btn-primary btn-sm" href="" data-toggle="modal" data-target="#agregarPaciente"><i class="fa fa-search"></i></a>
													</div>
												</div>
											</div>

										</div>

                                        <div class="form-row">

											<div class="col-md-6">
												<label for=""><strong>Fecha de ingreso</strong></label>
												<div class="input-group">
												<input type="date" class="form-control" id="fechaIngreso" name="fechaHoja" placeholder="Fecha del ingreso" required>
													<div class="invalid-tooltip">
														Seleccione un tipo de documento.
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<label for=""><strong>Tipo</strong></label>
												<div class="input-group">
													<select class="form-control" id="tipoConsulta" name="tipoHoja" required>
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

										<div class="form-row mb-5">
											<div class="col-md-6">
												<label for=""><strong>Médico</strong></label>
												<div class="input-group">
													<select class="form-control" id="medicoHoja" name="idMedico" required>
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

											<div class="col-md-6">
												<label for=""><strong>Habitación</strong></label>
												<div class="input-group">
													
													<select class="form-control" id="habitacionHoja" name="idHabitacion" required>
														<option value="">.:: Seleccionar ::.</option>
														<?php 
															foreach ($habitaciones as $habitacion) {
																if($habitacion->estadoHabitacion == 0){
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

									</div>

								</div>
								<input type="hidden" value="1" name="estadoHoja">
								<div class="text-center" id="">
									<button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Siguiente <i class="fa fa-arrow-right"></i></button>
									<button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
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
								<table id="tabla-medicamentos" class="table table-striped thead-primary w-100 tablaPlus">
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
											<td class="text-center"><?php echo $paciente->nombrePaciente." ".$paciente->apellidoPaciente ?></td>
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


<script>

	function agregarPaciente(id, nombre){
		$("#nombrePaciente").attr("value", nombre);
		$("#idPaciente").attr("value", id);
		console.log(nombre);
	}

    $(document).ready(function() {
        var edad = $(this).val();
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = (now.getFullYear() - edad)+"-"+(month)+"-"+(day) ;
        $("#fechaIngreso").val(today);
    });

</script>
