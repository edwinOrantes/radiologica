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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Stock </a> </li>
                    <li class="breadcrumb-item"><a href="#">Crear Stock</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Lista de stocks</h6></div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary btn-sm" href="#agregarStock" data-toggle="modal"><i class="fa fa-plus"></i> Agregar stock</button>
                            <!-- <a href="<?php echo base_url()?>Gastos/gastos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
                        <?php
                            if(sizeof($stocks) > 0){
                        ?>
                            <div class="table-responsive">
                                <table id="tabla-pacientes" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Ubicación</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $flag = 0;
                                            foreach ($stocks as $row) {
                                                $flag++;
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?= $flag  ?></td>
                                                    <td class="text-center"><?= $row->nombreStock  ?></td>
                                                    <td class="text-center"><?= $row->nivelStock  ?></td>
                                                    <td class="text-center"><?= $row->descripcionStock  ?></td>
                                                    <td class="text-center">
                                                        <input type="hidden" value="<?= $row->nombreStock  ?>" class="nombreStock">
                                                        <input type="hidden" value="<?= $row->nivelStock  ?>" class="nivelStock">
                                                        <input type="hidden" value="<?= $row->descripcionStock  ?>" class="descripcionStock">
                                                        <input type="hidden" value="<?= $row->idStock  ?>" class="idStock">
                                                    <?php
                                                        echo "<a href='#actualizarDatos' data-toggle='modal' class='editarDatosStock' title='Editar datos'><i class='fas fa-pencil-alt ms-text-primary'></i></a>";
                                                        echo "<a href='".base_url().'Stock/detalle_stock/'.$row->idStock."/' title='Agregar insumos al stock'><i class='fas fa-file-signature ms-text-primary'></i></a>";
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                           }else{
                                echo '<div class="alert alert-danger col-md-12">
                                    <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                </div>';
                           }
                        ?>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>

<!-- Modal para agregar stock-->
    <div class="modal fade" id="agregarStock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del stock</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id=""  method="post" action="<?php echo base_url() ?>Stock/guardar_stock"  novalidate>
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Nombre:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nombreStock" name="nombreStock" placeholder="Nombre del stock" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Nivel:</strong></label>
                                            <div class="input-group">
                                                <select  class="form-control" id="nivelStock" name="nivelStock"  required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="Nivel 1">Nivel 1</option>
                                                    <option value="Nivel 2">Nivel 2</option>
                                                    <option value="Nivel 3">Nivel 3</option>
                                                    <option value="Nivel 4">Nivel 4</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un nivel.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Descripción:</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="descripcionStock" name="descripcionStock" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese una descripción.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal para agregar stock-->

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="actualizarDatos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del stock</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" method="post" action="<?php echo base_url() ?>Stock/actualizar_stock"  novalidate>
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Nombre:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nombreStockU" name="nombreStock" placeholder="Nombre del stock" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Nivel:</strong></label>
                                            <div class="input-group">
                                                <select  class="form-control" id="nivelStockU" name="nivelStock"  required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="Nivel 1">Nivel 1</option>
                                                    <option value="Nivel 2">Nivel 2</option>
                                                    <option value="Nivel 3">Nivel 3</option>
                                                    <option value="Nivel 4">Nivel 4</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un nivel.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Descripción:</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="descripcionStockU" name="descripcionStock" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese una descripción.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <input type="hidden" class="form-control" id="idStockU" name="idStock" required>
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar </button>
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

<script>
    $(document).on("click", ".editarDatosStock", function(e) {
        e.preventDefault();
        $("#nombreStockU").val($(this).closest("tr").find(".nombreStock").val());
        $("#nivelStockU").val($(this).closest("tr").find(".nivelStock").val());
        $("#descripcionStockU").val($(this).closest("tr").find(".descripcionStock").val());
        $("#idStockU").val($(this).closest("tr").find(".idStock").val());
    });
</script>