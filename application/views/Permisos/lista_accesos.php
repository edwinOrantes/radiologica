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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Permisos </a> </li>
                    <li class="breadcrumb-item"><a href="#">Gestión de permisos de usuario</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de accesos</h6></div>
                        <div class="col-md-6 text-right">
                                <a href="#agregarAcceso" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar acceso</a>
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
                                            <th class="text-center" scope="col">Nombre acceso</th>
                                            <th class="text-center" scope="col">Descripción</th>
                                            <th class="text-center" scope="col">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div>
                                            <?php
                                                $index = 0;
                                                    foreach ($accesos as $acceso) {
                                                        $index++;
                                                        $id ='"'.$acceso->idAcceso.'"';
                                                        $nombre ='"'.$acceso->nombreAcceso.'"';
                                                        $descripcion ='"'.$acceso->descripcionAcceso.'"';
                                                ?>
                                                <tr>
                                                    <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                    <td class="text-center"><?php echo $acceso->nombreAcceso; ?></td>
                                                    <td class="text-center"><?php echo $acceso->descripcionAcceso; ?></td>
                                                    <td class="text-center">
                                                    <?php
                                                        
                                                        echo "<a href='".base_url()."Permisos/agregar_permisos/".$acceso->idAcceso."'><i class='fas fa-edit ms-text-success'></i></a>";
                                                        
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
<div class="modal fade" id="agregarAcceso" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Datos del acceso</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" id="frmAccesos"  method="post" action="<?php echo base_url() ?>Accesos/guardar_acceso"  novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Nombre</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreAcceso" name="nombreAcceso" placeholder="Nombre del acceso" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre para el acceso.
                                            </div>
										</div>
									</div>
                                    
																		
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Descripción</strong></label>
                                        <textarea class="form-control disableSelect" id="descripcionAcceso" name="descripcionAcceso" required></textarea>
                                        <div class="invalid-tooltip">
                                            Ingrese la descripción del acceso.
                                        </div>
									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar acceso</button>
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
<div class="modal fade" id="actualizarAcceso" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <form class="needs-validation" id="frmAccesos"  method="post" action="<?php echo base_url() ?>Accesos/actualizar_acceso"  novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Nombre</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreAccesoA" name="nombreAcceso" placeholder="Nombre del acceso" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre para el acceso.
                                            </div>
										</div>
									</div>
                                    
																		
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Descripción</strong></label>
                                        <textarea class="form-control disableSelect" id="descripcionAccesoA" name="descripcionAcceso" required></textarea>
                                        <div class="invalid-tooltip">
                                            Ingrese la descripción del acceso.
                                        </div>
									</div>
								</div>
                                
                                <input type="hidden" class="form-control" id="idAccesoA" name="idAcceso">
								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar acceso</button>
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
			<form action="<?php echo base_url() ?>Accesos/eliminar_acceso" method="post">
				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este acceso ?</p>
					<input type="hidden" class="form-control" id="idAccesoE" name="idAcceso">
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

	function actualizarAcceso(id, nombre, descripcion){
		$("#idAccesoA").val(id);
		$("#nombreAccesoA").val(nombre);
		$("#descripcionAccesoA").val(descripcion);
	}
	
	function eliminarAcceso(id){
		$("#idAccesoE").val(id);
	}
</script>