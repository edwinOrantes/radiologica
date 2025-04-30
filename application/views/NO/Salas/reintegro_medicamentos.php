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
                    <li class="breadcrumb-item active"><a href="#">Lista de procedimientos</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">

                        <div class="col-md-6">
                            <h6>Lista de insumos a entregar</h6>
                        </div>
                        
                        <div class="col-md-6 text-right"></div>

                    </div>
                </div>

                <div class="ms-panel-body">
                    <?php
                        if(sizeof($medicamentos) > 0){
                    ?>
                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Hoja</th>
                                        <th class="text-center" scope="col">Paciente</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>

									<?php
									$index = 0;
										foreach ($medicamentos as $row) {
											$index++;
									?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                        <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                        <td class="text-center"><?php echo $row->total; ?></td>
                                        <td class="text-center">
                                            <input type="hidden" value="<?php echo $row->idMedicamento; ?>" class="idMedicamento">
                                            <input type="hidden" value="<?php echo $row->total; ?>"  class="total">
                                            <a href="#" class="saldarInsumo"><i class="fa fa-check ms-text-danger"></i></a>
                                        </td>
                                    </tr>

									<?php }	?>
                                </tbody>
                            </table>
                        </div>

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

<script>
    $(document).on("click", ".saldarInsumo", function(e){
        e.preventDefault();
        var datos = {
            insumo : $(this).closest('tr').find('.idMedicamento').val(),
            total : $(this).closest('tr').find('.total').val(),
        }
        $.ajax({
            url: "reintegrar_insumo",
            type: "POST",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.success('Proceso efectuado con exito', 'Aviso!');
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('Error al efectuar el proceso', 'Aviso!');
                    }
                }else{
                    toastr.remove();
                    toastr.options = {
                        "positionClass": "toast-top-left",
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "1000",
                        "extendedTimeOut": "50",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                        },
                    toastr.error('Error al efectuar el proceso', 'Aviso!');
                }
            },
            error:function(){
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-top-left",
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "1000",
                    "extendedTimeOut": "50",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                },
                toastr.error('Error al efectuar el proceso', 'Aviso!');
            }
        });

        $(this).closest('tr').remove();
    })
</script>