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
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Rx </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Lista paciente</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">

                        <div class="col-md-6">
                            <h6>Pacientes pendientes</h6>
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
                                        <th class="text-center" scope="col">Nombre</th>
                                        <th class="text-center" scope="col">Edad</th>
                                        <th class="text-center" scope="col">Examen</th>
                                        <th class="text-center" scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

									<?php
									$index = 0;
										foreach ($pacientes as $row) {
											$index++;
									?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $row->nombrePaciente; ?></td>
                                        <td class="text-center"><?php echo $row->edadPaciente; ?></td>
                                        <td class="text-center">
                                            <?php 
                                                $examenes = explode(", ", $row->examenRx);
                                                // Filtrando solo los que comienzan con "RX-"
                                                $examenesRX = "";
                                                foreach ($examenes as $examen) {
                                                    // Extrayendo el prefijo de la cadena de examen
                                                    $prefijo = substr(trim($examen), 0, 3);
                                                    // Comparando con "RX-"
                                                    if ($prefijo === 'RX-') {
                                                        $examenesRX .= $examen.", ";
                                                    }
                                                }
                                                echo $examenesRX ;
                                            ?>

                                        </td>
                                        <td class="text-center" class="estadoCola">
                                            <input type="hidden" value="<?php echo $row->idColaRx;?>" class="idColaRx">
                                            <?php 
                                                if($row->estadoColaRx == 1){
                                                    echo '<a href="#" class="saldarCola"><i class="fa fa-check text-primary" title="Atendido"></i></a>';
                                                }else{
                                                    echo '<span class="badge text-success">Atendido</span>'; 
                                                }
                                            ?>
                                        </td>
                                        
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

<script>
    $(document).on("click", ".saldarCola", function() {
        var datos = {
            cola : $(this).closest( 'tr').find('.idColaRx').val()
        };
        $(this).closest( 'tr').find('.saldarCola').html('<span class="badge text-success">Atendido</span>');
        $.ajax({
            url: "saldar_cola",
            type: "POST",
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
                        toastr.success('Proceso efectuad', 'Aviso!');
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
                        toastr.error('Error al efectuar el proceso...', 'Aviso!');
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
                    toastr.error('Error al efectuar el proceso...', 'Aviso!');

                }
            }
        });
    });
</script>