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

<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Gastos</a> </li>
                    <li class="breadcrumb-item"><a href="#">Lista de cuentas de gastos</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Cuentas de gastos</h6></div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary btn-sm" href="#agregarCuenta" data-toggle="modal"><i class="fa fa-plus"></i> Agregar cuenta</button>
                            <a href="<?php echo base_url()?>Gastos/gastos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
						<div class="table-responsive">
							<table id="tabla-pacientes" class="table table-striped thead-primary w-100">
								<thead>
									<tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Clasificación</th>
                                        <th class="text-center">Descripción</th>
                                        <th class="text-center">Opción</th>
                                    </tr>
								</thead>
								<tbody>
                                <?php
                                $flag = 0;
                                    foreach ($cuentas as $cuenta) {
                                        $flag++;
                                        $id ='"'.$cuenta->idCuenta.'"';
                                        $nCuenta ='"'.$cuenta->nombreCuenta.'"';
                                        $idClasificacion ='"'.$cuenta->clasificacionCuenta.'"';
                                        $clasificacion ='"'.$cuenta->nombreCG.'"';
                                        $descripcion ='"'.$cuenta->descripcionCuenta.'"';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $flag  ?></td>
                                            <td class="text-center"><?= $cuenta->nombreCuenta  ?></td>
                                            <td class="text-center"><?= $cuenta->nombreCG  ?></td>
                                            <td class="text-center"><?= $cuenta->descripcionCuenta  ?></td>
                                            <td class="text-center">
                                            <?php
                                                echo "<a onclick='actualizarMedico($id, $nCuenta, $idClasificacion, $clasificacion, $descripcion)' href='#actualizarCuenta' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
                                                echo "<a onclick='eliminarMedico($id)' href='#eliminarCuenta' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
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

<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="agregarCuenta" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos de la cuenta</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">

							<form class="needs-validation" id=""  method="post" action="<?php echo base_url() ?>Gastos/guardar_cuenta"  novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Nombre:</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreCuenta" name="nombreCuenta" placeholder="Nombre de la cuenta" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Clasificación:</strong></label>
										<div class="input-group">
											<select  class="form-control" id="clasificacionCuenta" name="clasificacionCuenta" >
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($clasificaciones as $clasificacion) {
                                                        ?>
                                                            <option value="<?= $clasificacion->idClasificacionCG  ?>"><?= $clasificacion->nombreCG  ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione una clasificación.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Descripción:</strong></label>
										<div class="input-group">
											<textarea class="form-control" id="descripcionCuenta" name="descripcionCuenta"></textarea>
                                            <div class="invalid-tooltip">
                                                Ingrese una descripción.
                                            </div>
										</div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar cuenta</button>
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

<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="actualizarCuenta" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos de la cuenta</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">

							<form class="needs-validation" id=""  method="post" action="<?php echo base_url() ?>Gastos/actualizar_cuenta" novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Nombre:</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreCuentaA" name="nombreCuenta" placeholder="Nombre de la cuenta" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Clasificación:</strong></label>
										<div class="input-group">
											<select  class="form-control" id="clasificacionCuentaA" name="clasificacionCuenta" >
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($clasificaciones as $clasificacion) {
                                                        ?>
                                                            <option value="<?= $clasificacion->idClasificacionCG  ?>"><?= $clasificacion->nombreCG  ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione una clasificación.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Descripción:</strong></label>
										<div class="input-group">
											<textarea class="form-control" id="descripcionCuentaA" name="descripcionCuenta"></textarea>
                                            <div class="invalid-tooltip">
                                                Ingrese una descripción.
                                            </div>
											<input type="hidden" class="form-control" id="idCuentaA" name="idCuenta">
										</div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar cuenta</button>
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


<!-- Modal para eliminar datos de la cuenta-->
<div class="modal fade" id="eliminarCuenta" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">
			<form action="<?php echo base_url() ?>Gastos/eliminar_cuenta" method="post">
				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de esta cuenta?</p>
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
<!-- Fin Modal eliminar  datos de la cuenta-->

<script>
    function actualizarMedico(id, nCuenta, idClasificacion, clasificacion, descripcion){
        document.getElementById("nombreCuentaA").value = nCuenta
        document.getElementById("clasificacionCuentaA").value = idClasificacion
        document.getElementById("descripcionCuentaA").value = descripcion
        document.getElementById("idCuentaA").value = id
    }
    
    function eliminarMedico(id){
        document.getElementById("idEliminar").value = id
    }
</script>