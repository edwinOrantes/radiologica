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
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Salas </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Insumos</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">

                        <div class="col-md-6">
                            <h6>Lista de insumos</h6>
                        </div>
                        
                        <div class="col-md-6 text-right"></div>

                    </div>
                </div>

                <div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Código</th>
                                        <th class="text-center" scope="col">Insumo</th>
                                        <th class="text-center" scope="col">Actual</th>
                                        <th class="text-center" scope="col">Fijo</th>
                                        <th class="text-center" scope="col">Diferencia</th>
                                    </tr>
                                </thead>
                                <tbody>

									<?php
									$index = 0;
										foreach ($insumos as $row) {
											$index++;
									?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                        <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                        <td class="text-center"><?php echo $row->stockSala; ?></td>
                                        <td class="text-center"><?php echo $row->debeSala; ?></td>
                                        <td class="text-center text-danger"><?php echo ($row->debeSala - $row->stockSala); ?></td>
                                        
                                    </tr>

									<?php }	?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>