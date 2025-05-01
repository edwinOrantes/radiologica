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
                        <div class="col-md-6"><h6>Listado de permisos para el acceso <?php echo $nombreAcceso->nombreAcceso; ?></h6></div>
                        <div class="col-md-6 text-right">
                                <a href="#agregarAcceso" data-toggle="modal" class="btn btn-primary btn-sm validarTamanio"><i class="fa fa-plus"></i> Agregar permiso</a>
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <?php
                        if(sizeof($permisos) > 0){
                            ?>
                        <div class="row">
                            <div class="table-responsive mt-3">
                                <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th class="text-center" scope="col">Acceso</th>
                                            <th class="text-center" scope="col">Estado</th>
                                            <th class="text-center" scope="col">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div>
                                            <?php
                                                $index = 0;
                                                    foreach ($permisos as $permiso) {
                                                        $index++;
                                                        $id ='"'.$permiso->idPermiso.'"';
                                                        $nombre ='"'.$permiso->nombreMenu.'"';
                                                ?>
                                                <tr>
                                                    <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                    <td class="text-center"><?php echo $permiso->nombreMenu; ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                            if($permiso->estadoPermiso == 0){
                                                                echo '<span class="badge badge-outline-danger">Inactivo</span>';
                                                            }else{
                                                                echo '<span class="badge badge-outline-primary">Activo</span>';
                                                            }    
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                    <?php
                                                        if($permiso->estadoPermiso == 0){
                                                            echo "<a href='#agregarPermiso' onclick='agregarPermiso($id)' data-toggle='modal'><i class='fas fa-plus-square ms-text-primary'></i></a>";
                                                        }else{
                                                            echo "<a href='#quitarPermiso' onclick='quitarPermiso($id)' data-toggle='modal'><i class='fas fa-trash ms-text-danger'></i></a>";
                                                        }
                                                        
                                                        
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
				<h4 class="modal-title text-white"></i> Lista de accesos</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
                            
                            <div id="contenedorPermisos">
                                <form class="needs-validation" id="frmAccesos"  method="post" action="<?php echo base_url() ?>Permisos/guardar_permisos"  novalidate>
                                    
                                    <div class="table-responsive mt-3">
                                        <table id="tblPermisos" class="table table-striped thead-primary w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">#</th>
                                                    <th class="text-center" scope="col">Menu</th>
                                                    <th class="text-center" scope="col">Agregar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <div>
                                                    <?php
                                                        $index = 0;
                                                            foreach ($menus as $menu) {
                                                                // Recorriendo permisos ya agregados
                                                                $flag = 0;
                                                                foreach ($permisos as $permiso) {
                                                                    if($menu->idMenu == $permiso->idMenu){
                                                                        $flag++;
                                                                    }
                                                                }
                                                                
                                                                if($flag == 0){
                                                                    $index++;
                                                        ?>
                                                                    <tr class="fila_permiso">
                                                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                                        <td class="text-center"><?php echo $menu->nombreMenu; ?></td>
                                                                        <td class="text-center">
                                                                            <label class="ms-switch">
                                                                                <input type="checkbox" name="idPermisos[]" value="<?php echo $menu->idMenu; ?>">
                                                                                <span class="ms-switch-slider ms-switch-primary round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>

                                                    <?php 
                                                            }
                                                        }	
                                                    ?>
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <input type="hidden" value="<?php echo $acceso; ?>" name="idAcceso">
                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Agregar permisos</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>

                            <div id="contenedorNull">
                                <div class="alert alert-danger">
                                    <h6 class="text-center"><strong>Todos los permisos han sido agregados.</strong></h6>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Fin Modal para agregar datos del Medicamento-->


<!-- Modal para eliminar datos del Medicamento-->
<div class="modal fade" id="quitarPermiso" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">
			<form action="<?php echo base_url() ?>Permisos/eliminar_permiso" method="post">
				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de quitar este permiso para este usuario?</p>
					<input type="hidden" class="form-control" id="idPermisoE" name="idPermiso">
                    <input type="hidden" value="<?php echo $acceso; ?>" name="idAcceso">
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

<!-- Modal para eliminar datos del Medicamento-->
<div class="modal fade" id="agregarPermiso" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">
			<form action="<?php echo base_url() ?>Permisos/activar_permiso" method="post">
				<div class="modal-header bg-primary">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de activar nuevamente este permiso para este usuario?</p>
					<input type="hidden" class="form-control" id="idPermisoA" name="idPermiso">
                    <input type="hidden" value="<?php echo $acceso; ?>" name="idAcceso">
				</div>

				<div class="text-center">
					<button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-plus-square"></i> Activar </button>
					<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- Fin Modal eliminar  datos del Medicamento-->

<script>
    function quitarPermiso(id){
		$("#idPermisoE").val(id);
	}

    function agregarPermiso(id){
		$("#idPermisoA").val(id);
	}

</script>

<script>
    $(document).ready(function() {
        var mFilas = $("#tblPermisos .fila_permiso").length;
        if(mFilas == 0){
            $("#contenedorPermisos").hide();
            $("#contenedorNull").show();
        }else{
            $("#contenedorPermisos").show(); 
            $("#contenedorNull").hide();
        }
    });
</script>