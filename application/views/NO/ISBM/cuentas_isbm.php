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
<?php
	$fechaI = "";
	$flag = 0;
	if(sizeof($cuentas) == 0){
		$fechaI = date('Y-m-d');
	}else{
		foreach ($cuentas as $cuenta) {
			if($flag == 0){
				$fechaI = $cuenta->fechaCuenta;
			}
			$flag++;
		}
	}
?>
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-xl-12 col-md-12">
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Requisiciones existentes</h6></div>
                        <div class="col-md-6 text-right">
							<?php
								if( $fechaI == date('Y-m-d')){
									if(sizeof($cuentas) == 0){
										echo '<a href="#crearCuenta" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-address-book"></i> Crear requisición</a>';
									}
								}else{
									echo '<a href="#crearCuenta" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-address-book"></i> Crear requisición</a>';
								}
							?> 

							
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<div class="table-responsive">
                        <?php
                            if (sizeof($cuentas) > 0) {
                        ?>
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Fecha requisición</th>
                                    <th class="text-center">Opción</th>
                                </thead>
                                <tbody>
                                    <?php
                                        $index= 0;
                                        foreach ($cuentas as $cuenta) {
                                            $index++;
											if($cuenta->estadoCuenta == 0){
												echo '<tr class="text-danger">';
											}else{
												echo '<tr>';
											}
                                    ?>
                                                
                                                    <td class="text-center"><?php echo $index ?></td>
                                                    <td class="text-center">
														<?php 
															switch ($cuenta->codigoCuenta) {
																case ($cuenta->codigoCuenta > 0) && ($cuenta->codigoCuenta < 10):
																	echo "000".$cuenta->codigoCuenta;
																	break;
																case ($cuenta->codigoCuenta >= 10) && ($cuenta->codigoCuenta < 100):
																	echo "00".$cuenta->codigoCuenta;
																	break;
																case ($cuenta->codigoCuenta >= 100) && ($cuenta->codigoCuenta < 1000):
																	echo "0".$cuenta->codigoCuenta;
																	break;
																case ($cuenta->codigoCuenta >= 1000):
																	echo $cuenta->codigoCuenta;
																	break;
																
																default:
																echo "000".$cuenta->codigoCuenta;
																	break;
															}
														
														
														?>
													</td>
                                                    <td class="text-center"><?php echo $cuenta->fechaCuenta ?></td>
                                                    <td class="text-center">
														<?php
															if ($cuenta->fechaCuenta < date('Y-m-d')) {
														?>
															<a  title='Ver detalles del senso' href='<?php echo base_url()?>Isbm/detalle_cuenta/<?php echo $cuenta->idCuenta;?>/'><i class='far fa-eye ms-text-success'></i></a>
														<?php
															}else{
																echo "<a  title='Ver detalles del senso' href='<?php echo base_url()?>Isbm/detalle_cuenta/<?php echo $cuenta->idCuenta;?>/'><i class='far fa-edit ms-text-primary'></i></a>";
														?>
														<?php
															}
															
															if($cuenta->estadoCuenta == 1){
																echo "<a  title='Ver detalles del senso' href='<?php echo base_url()?>Isbm/detalle_cuenta/<?php echo $cuenta->idCuenta;?>/'><i class='far fa-file-alt ms-text-primary'></i></a>";
																
															}
														?>
                                                    </td>
                                                </tr>
                                    <?php }  ?>
                                    
                                </tbody>
                            </table>
                        <?php }else{
                                echo '<div class="alert alert-danger">
                                        <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                    </div>';
                            } ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<!-- Modal para crear senso-->
	<div class="modal fade" id="crearCuenta" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Crear requisición </h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								
								<h4 class="text-center">¿Crear requisición para este dia?</h4>
								<form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Isbm/crear_cuenta"  novalidate>
									
								<div class="form-row">
										
										<div class="col-md-12">
											<div class="input-group">
												<input type="hidden" class="form-control" id="fechaCuenta" name="fechaCuenta" value="<?php echo date('Y-m-d'); ?>" required>
												<div class="invalid-tooltip">
													Seleccione la fecha.
												</div>
											</div>
										</div>
									</div>

									<div class="text-center">
										<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Crear requisición </button>
										<button class="btn btn-light" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal para crear senso-->


<!-- Modal para agregar paciente al censo-->
	<div class="modal fade" id="agregarIngreso" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos del ingreso</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">

								<form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Senso/guardar_senso"  novalidate>
									
								<div class="form-row">
										
										<div class="col-md-12">
											<label for=""><strong>Fecha</strong></label>
											<div class="input-group">
												<input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= $sensoActual->fechaSenso?>" required>
												<div class="invalid-tooltip">
													Seleccione la fecha.
												</div>
											</div>

										</div>
										
									</div>

									<div class="form-row">
										<div class="col-md-6">
											<label for="">Nombre</label>
											<div class="input-group">
												<input type="text" class="form-control" id="pacienteSenso" name="pacienteSenso" placeholder="Nombre del paciente" required>
												<div class="invalid-tooltip">
													Ingrese e nombre del paciente.
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<label for="">Habitación</label>
											<div class="input-group">
												<select name="habitacionSenso" id="habitacionSenso" class="form-control" required>
													<option value="">.:: Seleccionar ::.</option>
													<?php
													foreach ($habitaciones as $habitacion) {
														?>
													<option value="<?= $habitacion->idHabitacion ?>"><?= $habitacion->numeroHabitacion ?></option>
													<?php } ?>
												</select>
												<div class="invalid-tooltip">
													Seleccione la habitacion del paciente.
												</div>
											</div>

										</div>
										
									</div>
									
									<div class="form-row">

										<div class="col-md-6">
											<label for="">Médico</label>
											<div class="input-group">
												<input type="text" class="form-control" id="medicoSenso" name="medicoSenso" placeholder="Nombre del médico" required>
												<div class="invalid-tooltip">
													Ingrese el nombre del médico.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for="">Dieta</label>
											<div class="input-group">
												<input type="text" class="form-control" id="dietaSenso" name="dietaSenso" placeholder="Dieta del paciente" required>
												<div class="invalid-tooltip">
													Ingrese la dieta del paciente.
												</div>
											</div>
										</div>
										
									</div>

									<div class="form-row">
										<div class="col-md-12">
											<label for="">Observación</label>
											<textarea class="form-control" id="observacionSenso" name="observacionSenso" required></textarea>
											<div class="invalid-tooltip">
												Ingrese las observaciones.
											</div>
											<input type="hidden" class="form-control" id="idSenso" name="idSenso" value="<?= $sensoActual->senso ?>" required>
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
<!-- Fin Modal para agregar paciente al censo-->

<!-- Modal para dar de alta-->
	<div class="modal fade" id="darAlta" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Datos del senso</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								
								<h4 class="text-center">¿Dar de alta al paciente?</h4>
								<form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Senso/alta_paciente"  novalidate>
									
								<div class="form-row">
										
										<div class="col-md-12">
											<div class="input-group">
												<input type="hidden" class="form-control" id="idPaciente" name="idDetalleSenso" required>
												<input type="hidden" class="form-control" id="habitacionP" name="habitacionPaciente" required>
												<input type="hidden" class="form-control" id="pacienteS" name="nombrePaciente" required>
												<div class="invalid-tooltip">
													Seleccione la fecha.
												</div>
											</div>

										</div>
									</div>

									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Aceptar</button>
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
<!-- Fin Modal para dar de alta-->


<script>

	$(document).ready(function() {

	    var now = new Date();
	    var day = ("0" + now.getDate()).slice(-2);
	    var month = ("0" + (now.getMonth() + 1)).slice(-2);
	    var today = (now.getFullYear())+"-"+(month)+"-"+(day) ;

	    $("#fechaSenso").val(today);
	    //$("#fechaIngreso").val(today);
	});

	function darAlta(id, habitacion, paciente){
		document.getElementById("idPaciente").value = id;
		document.getElementById("habitacionP").value = habitacion;
		document.getElementById("pacienteS").value = paciente;
	}

</script>