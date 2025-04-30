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
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrow has-gap">
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Herramientas </a> </li>
                            <li class="breadcrumb-item"><a href="#">Anuncios</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right">
                    <a href="#agregarAnuncio" data-toggle="modal" class="btn btn-primary btn-sm">Agregar <i class="fa fa-plus"></i></a>
                </div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered thead-primary tablaPlus" id="movimientosHoja">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Titulo</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $index = 1;
                                        foreach ($anuncios as $row) {
                                    ?>

                                    <tr>
                                        <td class="text-center"><?php echo $index; ?></td>
                                        <td class="text-center">Anuncio <?php echo $row->tituloAnuncio; ?></td>
                                        <td class="text-center">
                                            <?php 
                                                if($row->estadoAnuncio == 0){
                                                    echo '<span class="badge badge-danger">Inactivo</span>';
                                                }else{
                                                    echo '<span class="badge badge-success">Activo</span>';
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->fechaAnuncio; ?>
                                            <input type="hidden" value="<?php echo $row->idAnuncio; ?>" class="idAnuncio">
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                if($row->estadoAnuncio == 0){
                                                    echo '<a href="#eliminarAnuncio" id="btnActivarAnuncio" data-toggle="modal"><i class="fa fa-undo text-primary"></i></a>';
                                                }else{
                                                    echo '<a href="#eliminarAnuncio" id="btnEliminarAnuncio" data-toggle="modal"><i class="fa fa-trash-alt text-danger"></i></a>';
                                                }
                                            ?>
                                            
                                        </td>
                                    </tr>

                                    <?php
                                        $index++;
                                        }
                                    ?>
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
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="agregarAnuncio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Datos del anuncio</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="table-responsive mt-3">
                                <form method="post" action="<?php echo base_url(); ?>Herramientas/guardar_anuncio" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="detalleAnuncio">Titulo</label>
                                        <input type="text" class="form-control" id="tituloAnuncio" name="tituloAnuncio">
                                    </div>
                                    <div class="form-group">
                                        <label for="detalleAnuncio">Detalle</label>
                                        <input type="text" class="form-control" id="detalleAnuncio" name="detalleAnuncio">
                                    </div>
                                    <div class="form-group">
                                        <label for="fechaAnuncio">Fecha anuncio</label>
                                        <input type="date" class="form-control" id="fechaAnuncio" name="fechaAnuncio">
                                    </div>
                                    <div class="form-group">
                                        <label for="estadoAnuncio">Estado anuncio</label>
                                        <select class="form-control" id="estadoAnuncio" name="estadoAnuncio">
                                            <option value="">.:: Seleccionar  ::.</option>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="imagenAnuncio">Imagen</label>
                                        <input type="file" class="form-control" id="imagenAnuncio" name="imagenAnuncio">
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary"> <i class="fa fa-save"></i> Guargar</button>
                                    </div>
                                </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- Modal para eliminar datos del medicamento-->
    <div class="modal fade" id="eliminarAnuncio" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                <form action="<?php echo base_url() ?>Herramientas/eliminar_anuncio" method="post">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Estas seguro de eliminar el anuncio?</p>
                        <input type="hidden" value="0" id="pivoteAnuncio" name="pivoteAnuncio">
                        <input type="hidden" class="form-control" id="idAnuncioE" name="idAnuncio" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar </button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<!-- Fin Modal eliminar  datos del medicamento-->

<script>
    $(document).on('click', '#btnEliminarAnuncio', function(e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('.idAnuncio').val();
        $("#idAnuncioE").val(id);
        $("#pivoteAnuncio").val("0");
    });

    $(document).on('click', '#btnActivarAnuncio', function(e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('.idAnuncio').val();
        $("#idAnuncioE").val(id);
        $("#pivoteAnuncio").val("1");
    });
</script>

