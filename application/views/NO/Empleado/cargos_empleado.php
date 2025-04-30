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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Cargos </a> </li>
                    <li class="breadcrumb-item"><a href="#">Gestión de cargos</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de cargos para empleados</h6></div>
                        <div class="col-md-6 text-right">
                                <button class="btn btn-primary btn-sm" href="#agregarCargo" data-toggle="modal"><i class="fa fa-plus"></i> Agregar cargo</button>
                                <!--<a href="<?php echo base_url() ?>ServiciosExternos/lista_externos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>-->
                                <!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
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
                                        foreach ($cargos as $cargo) {
                                            $index++;
                                            $id ='"'.$cargo->idCargo.'"';
											$nombreCargo ='"'.$cargo->nombreCargo.'"';
									?>
										<tr>
											<td class="text-center" scope="row"><?php echo $index; ?></td>
											<td class="text-center"><?php echo $cargo->nombreCargo; ?></td>
											<td class="text-center">
											<?php
												echo "<a onclick='actualizarCargo($id, $nombreCargo)' href='#actualizarCargo' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
												echo "<a onclick='eliminarCargo($id)' href='#aliminarCargo' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
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


<!-- Modal para agregar datos del servicio externo-->
<div class="modal fade" id="agregarCargo" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Datos del cargo</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" method="post" action="<?php echo base_url();?>Empleado/guardar_cargo" novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for="">Nombre</label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreCargo" name="nombreCargo" placeholder="Nombre del cargo" required>
                                            <div class="invalid-tooltip">
                                                Ingrese el nombre del cargo.
                                            </div>
										</div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar cargo</button>
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
<!-- Fin Modal para agregar datos del servicio externo-->

<!-- Modal para actualizar datos del servicio externo-->
<div class="modal fade" id="actualizarCargo" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Datos del cargo</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>Empleado/actualizar_cargo" novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for="">Nombre del cargo</label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreCargoA" name="nombreCargo" placeholder="Nombre del insumo y/o servicio" required>
                                            <div class="invalid-tooltip">
                                                Ingrese el nombre del insumo y/o servicio.
                                            </div>
										</div>
									</div>
                                    
								</div>

								<input type="hidden" class="form-control" id="idCargoA" name="idCargo" />
								
								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar cargo</button>
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
<!-- Fin Modal actualizar datos del servicio externo-->


<!-- Modal para agregar datos del servicio externo-->
<div class="modal fade" id="aliminarCargo" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">

			<div class="modal-header bg-danger">
				<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body text-center">
				<p class="h5">¿Estas seguro de eliminar los datos de este cargo?</p>
			</div>
			
			<form action="<?php echo base_url()?>Empleado/eliminar_cargo" method="post">
				<input type="hidden" class="form-control" id="idCargoE" name="idCargo" />
				<div class="text-center">
					<button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
					<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- Fin Modal eliminar  datos del servicio externo-->


<script>

    function actualizarCargo(id, nombre){
        document.getElementById("idCargoA").value = id;
        document.getElementById("nombreCargoA").value = nombre;
    }

    function eliminarCargo(id){
        document.getElementById("idCargoE").value = id;
    }
</script>