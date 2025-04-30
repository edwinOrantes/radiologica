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
                        <li class="breadcrumb-item"><a href="#">Gestión de anuncios</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6"><h6>Listado de anuncios</h6></div>
                            <div class="col-md-6 text-right">
                                    <a href="#agregarAnuncio" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar anuncio</a>
                            </div>
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <?php
                            //if(sizeof($accesos) > 0){
                        ?>
                            <div class="row">
                                <div class="table-responsive mt-3">
                                    <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">#</th>
                                                <th class="text-center" scope="col">Detalle</th>
                                                <th class="text-center" scope="col">Usuarios</th>
                                                <th class="text-center" scope="col">Estado</th>
                                                <th class="text-center" scope="col">Fecha</th>
                                                <th class="text-center" scope="col">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $index = 0;
                                            foreach ($anuncios as $anuncio) {
                                                $index++;
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $index; ?></td>
                                                <td class="text-center"><?php echo $anuncio->detalleAnuncio; ?></td>
                                                <td class="text-center">
                                                    <?php
                                                        $usuarios = $this->Anuncio_Model->obtenerUsuariosAnuncio($anuncio->idAnuncio);
                                                        $lista = "";
                                                        foreach ($usuarios as $usuario) {
                                                            if($usuario == end($usuarios)){
                                                                $lista .= $usuario->nombreUsuario;
                                                                
                                                            }else{
                                                                $lista .= $usuario->nombreUsuario.", ";
                                                            }
                                                        }
                                                        echo $lista
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                        if($anuncio->estadoAnuncio == 1){
                                                            echo '<span class="badge badge-outline-success">Activo</span>';
                                                            
                                                            
                                                        }else{
                                                            echo '<span class="badge badge-outline-danger">Inactivo</span>';
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-center"><?php echo $anuncio->fechaAnuncio; ?></td>

                                                <td class="text-center">
                                                    <?php
                                                        echo "<a href='#actualizarAnuncio' data-toggle='modal' title='Editar detalle de la hoja'><i class='fas fa-edit ms-text-primary'></i></a>";
                                                        echo "<a href='#eliminarAnuncio' data-toggle='modal' title='Editar detalle de la hoja'><i class='fas fa-trash-alt ms-text-danger'></i></a>";
                                                        
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                            /* }else{
                                echo '<div class="alert alert-danger">
                                    <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                </div>';
                            } */
                            
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="agregarAnuncio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del anuncio</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" id="frmAnuncios"  method="post" action="<?php echo base_url() ?>Anuncio/guardar_anuncio"  novalidate>

                                    <div class="form-row">
                                        <strong>Usuario</strong><br>
                                        <div class="input-group">
                                            <select name="usuariosDestino[]" id="" class="controlInteligente form-control" multiple="multiple" required>
                                                <option value="">:: Seleccionar  ::</option>
                                                <?php
                                                    
                                                    foreach ($listaUsuarios as $usuario) {
                                                ?>
                                                    <option value="<?php echo $usuario->idUsuario; ?>"><?php echo $usuario->nombreUsuario; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione un usuario al que se mostrara el anuncio.
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Detalle</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="detalleAnuncio" name="detalleAnuncio" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese el detalle del anuncio.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <strong>Usuario</strong><br>
                                        <div class="input-group">
                                            <select name="estadoAnuncio" id="" class="form-control" required>
                                                <option value="">:: Seleccionar  ::</option>
                                                <option value="1" selected>Activo</option>
                                                <option value="2">Inactivo</option>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione el estado del anuncio.
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Fecha</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaAnuncio" name="fechaAnuncio" placeholder="Nombre del usuario" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la fecha del anuncio.
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar anuncio</button>
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


<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="actualizarAnuncio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del anuncio</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" id="frmAnuncios"  method="post" action="<?php echo base_url() ?>Anuncio/guardar_anuncio"  novalidate>

                                    <div class="form-row">
                                        <strong>Usuario</strong><br>
                                        <div class="input-group">
                                            <select name="usuariosDestino[]" id="" class="controlInteligente form-control" multiple="multiple" required>
                                                <option value="">:: Seleccionar  ::</option>
                                                <?php
                                                    foreach ($usuarios as $usuario) {
                                                ?>
                                                    <option value="<?php echo $usuario->idUsuario; ?>"><?php echo $usuario->nombreUsuario; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione un usuario al que se mostrara el anuncio.
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Detalle</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="detalleAnuncio" name="detalleAnuncio" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese el detalle del anuncio.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <strong>Usuario</strong><br>
                                        <div class="input-group">
                                            <select name="estadoAnuncio" id="" class="form-control" required>
                                                <option value="">:: Seleccionar  ::</option>
                                                <option value="1" selected>Activo</option>
                                                <option value="2">Inactivo</option>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione el estado del anuncio.
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Fecha</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaAnuncio" name="fechaAnuncio" placeholder="Nombre del usuario" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la fecha del anuncio.
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar anuncio</button>
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

<!-- <script>

	function actualizarUsuario(id, nombre, ps, empleado, acceso){
		$("#idUsuarioA").val(id);
		$("#nombreUsuarioA").val(nombre);
		$("#empleadoUsuarioA").val(empleado);
		$("#accesoUsuarioA").val(acceso);
	}
	
	function eliminarUsuario(id){
		$("#idUsuarioE").val(id);
	}
</script> -->

<script>
    $(document).ready(function() {
        $(".controlInteligente").select2({
            theme: "bootstrap4",
            // dropdownParent: $("#inmunologia")
        });
    });
            
</script>




