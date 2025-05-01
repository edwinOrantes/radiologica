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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Usuarios </a> </li>
                    <li class="breadcrumb-item"><a href="#">Gestión de usuarios</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de usuarios</h6></div>
                        <div class="col-md-6 text-right">
                                <a href="#agregarUsuario" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar usuario</a>
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <?php
                        if(sizeof($accesos) > 0){
                            ?>
                        <div class="row">
                            <div class="table-responsive mt-3">
                                <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th class="text-center" scope="col">Empleado</th>
                                            <th class="text-center" scope="col">Usuario</th>
                                            <th class="text-center" scope="col">Contraseña</th>
                                            <th class="text-center" scope="col">Tipo acceso</th>
                                            <th class="text-center" scope="col">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div>
                                            <?php
                                                $index = 0;
                                                    foreach ($usuarios as $usuario) {
                                                        $index++;
                                                        $id ='"'.$usuario->idUsuario.'"';
                                                        $nombre ='"'.$usuario->nombreUsuario.'"';
                                                        $ps ='"'.$usuario->psUsuario.'"';
                                                        $empleado ='"'.$usuario->idEmpleado.'"';
                                                        $acceso ='"'.$usuario->idAcceso.'"';
                                                ?>
                                                <tr>
                                                    <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                    <td class="text-center"><?php echo $usuario->nombreEmpleado." ".$usuario->apellidoEmpleado; ?></td>
                                                    <td class="text-center"><?php echo $usuario->nombreUsuario; ?></td>
                                                    <td class="text-center">
														<i class="fa fa-asterisk fa-sm text-info"></i>
														<i class="fa fa-asterisk fa-sm text-info"></i>
														<i class="fa fa-asterisk fa-sm text-info"></i>
														<i class="fa fa-asterisk fa-sm text-info"></i>
														<i class="fa fa-asterisk fa-sm text-info"></i>
													</td>
                                                    <td class="text-center"><?php echo $usuario->nombreAcceso; ?></td>
                                                    <td class="text-center">
                                                    <?php
                                                        //echo "<a onclick='verDetalle($id, $nombre, $especialidad, $telefono, $direccion)' href='#verMedico' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
                                                        echo "<a onclick='actualizarUsuario($id, $nombre, $ps, $empleado, $acceso)' href='#actualizarUsuario' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                        echo "<a onclick='eliminarUsuario($id)' href='#eliminarMedicamento' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                    ?>
                                                    </td>
                                                </tr>

                                            <?php }	?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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


<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="agregarUsuario" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos del usuario</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" id="frmAccesos"  method="post" action="<?php echo base_url() ?>Usuarios/guardar_usuario"  novalidate>
								
								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Usuario</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" placeholder="Nombre del usuario" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre para el usuario.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Contraseña</strong></label>
                                        <input type="text" class="form-control" id="psUsuario" name="psUsuario" placeholder="Contraseña de acceso" required>
                                        <div class="invalid-tooltip">
                                            Ingrese la contraseña del acceso.
                                        </div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Empleado</strong></label>
										<div class="input-group">
											<select class="form-control" id="empleadoUsuario" name="empleadoUsuario" required>
												<option value="">.:: Seleccionar ::.</option>
												<?php
													foreach ($empleados as $empleado) {
												?>
												<option value="<?php echo $empleado->idEmpleado; ?>"><?php echo $empleado->nombreEmpleado." ".$empleado->apellidoEmpleado; ?></option>
												<?php } ?>
											</select>
                                            <div class="invalid-tooltip">
                                                Seleccione un empleado.
                                            </div>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Acceso</strong></label>
										<div class="input-group">
											<select class="form-control" id="accesoUsuario" name="accesoUsuario" required>
												<option value="">.:: Seleccionar ::.</option>
												<option value="">.:: Seleccionar ::.</option>
												<?php
													foreach ($accesos as $acceso) {
												?>
												<option value="<?php echo $acceso->idAcceso; ?>"><?php echo $acceso->nombreAcceso; ?></option>
												<?php } ?>
											</select>
                                            <div class="invalid-tooltip">
                                                Ingrese un acceso para el usuario.
                                            </div>
										</div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar usuario</button>
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
<!-- Fin Modal para agregar datos del Medicamento-->


<!-- Modal para actualizar datos del Medicamento-->
<div class="modal fade" id="actualizarUsuario" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos del usuario</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
						<form class="needs-validation" id="frmAccesos"  method="post" action="<?php echo base_url() ?>Usuarios/actualizar_usuario"  novalidate>
								
								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Usuario</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreUsuarioA" name="nombreUsuario" placeholder="Nombre del usuario" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre para el usuario.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Contraseña</strong></label>
                                        <input type="password" class="form-control" id="psUsuarioA" name="psUsuario" placeholder="Contraseña de acceso">
                                        <div class="invalid-tooltip">
                                            Ingrese la contraseña del acceso.
                                        </div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Empleado</strong></label>
										<div class="input-group">
											<select class="form-control" id="empleadoUsuarioA" name="empleadoUsuario" required>
												<option value="">.:: Seleccionar ::.</option>
												<?php
													foreach ($empleados as $empleado) {
												?>
												<option value="<?php echo $empleado->idEmpleado; ?>"><?php echo $empleado->nombreEmpleado." ".$empleado->apellidoEmpleado; ?></option>
												<?php } ?>
											</select>
                                            <div class="invalid-tooltip">
                                                Seleccione un empleado.
                                            </div>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Acceso</strong></label>
										<div class="input-group">
											<select class="form-control" id="accesoUsuarioA" name="accesoUsuario" required>
												<option value="">.:: Seleccionar ::.</option>
												<?php
													foreach ($accesos as $acceso) {
												?>
												<option value="<?php echo $acceso->idAcceso; ?>"><?php echo $acceso->nombreAcceso; ?></option>
												<?php } ?>
											</select>
                                            <div class="invalid-tooltip">
                                                Ingrese un acceso para el usuario.
                                            </div>
										</div>
									</div>
								</div>
								
								<input type="hidden" class="form-control" id="idUsuarioA" name="idUsuario">
								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar usuario</button>
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
<!-- Fin Modal actualizar datos del Medicamento-->


<!-- Modal para eliminar datos del Medicamento-->
<div class="modal fade" id="eliminarMedicamento" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">
			<form action="<?php echo base_url() ?>Usuarios/eliminar_usuario" method="post">
				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este usuario ?</p>
					<input type="hidden" class="form-control" id="idUsuarioE" name="idUsuario">
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

	function actualizarUsuario(id, nombre, ps, empleado, acceso){
		$("#idUsuarioA").val(id);
		$("#nombreUsuarioA").val(nombre);
/* 		$("#psUsuarioA").val(ps); */
		$("#empleadoUsuarioA").val(empleado);
		$("#accesoUsuarioA").val(acceso);
	}
	
	function eliminarUsuario(id){
		$("#idUsuarioE").val(id);
	}
</script>




