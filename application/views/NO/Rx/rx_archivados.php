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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-prescription"></i> Rx </a> </li>
                    <li class="breadcrumb-item"><a href="#">Radiografias archivadas</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de Rx penmdientes de entregar</h6></div>
                        <div class="col-md-6 text-right">
                                <button class="btn btn-primary btn-sm" href="#agregarResultado" data-toggle="modal"><i class="fa fa-plus"></i> Agregar RX</button>
                                <a href="<?php echo base_url()?>Medico/medicos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <?php 
                            if(sizeof($archivos) > 0){
                        ?>
                        <div class="table-responsive mt-3">
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Código</th>
                                        <th class="text-center" scope="col">Nombre</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>

									<?php
									$index = 0;
										foreach ($archivos as $row) {
											$index++;
									?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $row->codigoArchivado; ?></td>
                                        <td class="text-center"><?php echo $row->nombrePaciente; ?></td>
                                        <td class="text-center"><?php echo $row->fechaArchivado; ?></td>
                                        <td class="text-center">
                                            <input type="hidden" value="<?php echo $row->codigoArchivado; ?>" class="codigo">
                                            <input type="hidden" value="<?php echo $row->nombrePaciente; ?>" class="nombre">
                                            <input type="hidden" value="<?php echo $row->fechaArchivado; ?>" class="fecha">
                                            <input type="hidden" value="<?php echo $row->perteneceArchivo; ?>" class="pertenece">
                                            <input type="hidden" value="<?php echo $row->secuenciaArchivado; ?>" class="secuencia">
                                            <input type="hidden" value="<?php echo $row->idArchivado; ?>" class="idRx">
										<?php
                                            //echo "<a onclick='verDetalle($id, $nombre, $especialidad, $telefono, $direccion)' href='#verMedico' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
                                            echo "<a href='#actualizarDatos' class='actualizar_datos' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
											/* switch($this->session->userdata('nivel')) {
												case '1':
													echo "<a  href='#eliminarMedicamento' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
												break;
												default:
													echo "";
													break;
											} */
										?>
                                        </td>
                                    </tr>

									<?php }	?>
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

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="agregarResultado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del resultado</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Rx/guardar_rx"  novalidate>
                                    
                                    <div class="form-row">
                                        
                                        <div class="col-md-12">
                                            <label for=""><strong>Fecha</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaRx" name="fechaRx" placeholder="Fecha del examen" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la fecha.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for=""><strong>Código</strong></label>
                                            <div class="input-group">
                                                <input type="text" value="0000" id="codigoRx_" class="form-control" readonly>
                                                <input type="hidden" value="0000" class="form-control" id="codigoRx" name="codigoRx" placeholder="Código del resultado" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el código.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <label for=""><strong>Paciente</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pacienteRx" name="pacienteRx" placeholder="Nombre del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el nombre del paciente.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for=""><strong>Pertenece a:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="perteneceA" name="perteneceA" required>
                                                    <option value="">.::Seleccionar::.</option>
                                                    <option value="1">Hospital Orellana</option>
                                                    <option value="2">ISBM</option>
                                                    <option value="3">Seguro</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese el nombre del paciente.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-center">
                                        <input type="hidden" value="" id="secuenciaRx" name="secuenciaRx">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar</button>
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
    <div class="modal fade" id="actualizarDatos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del resultado</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Rx/actualizar_rx"  novalidate>
                                    
                                    <div class="form-row">
                                        
                                        <div class="col-md-12">
                                            <label for=""><strong>Fecha</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaRxU" name="fechaRx" placeholder="Fecha del examen" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la fecha.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for=""><strong>Código</strong></label>
                                            <div class="input-group">
                                                <input type="text" value="0000" id="codigoRx_U" class="form-control" readonly>
                                                <input type="text" value="0000" class="form-control" id="codigoRxU" name="codigoRx" placeholder="Código del resultado" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el código.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <label for=""><strong>Paciente</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pacienteRxU" name="pacienteRx" placeholder="Nombre del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el nombre del paciente.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for=""><strong>Pertenece a:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="perteneceAU" name="perteneceA" required>
                                                    <option value="">.::Seleccionar::.</option>
                                                    <option value="1">Hospital Orellana</option>
                                                    <option value="2">ISBM</option>
                                                    <option value="3">Seguro</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese el nombre del paciente.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-center">
                                        <input type="hidden" value="" id="secuenciaRxU" name="secuenciaRx">
                                        <input type="hidden" value="" id="perteneceRx2U" name="">
                                        <input type="hidden" value="" id="idRxU" name="idRx">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar</button>
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


<script>
	$(document).on("change", "#perteneceA", function(e) {
        e.preventDefault()
        var pivote = $(this).val();
        var datos = {
            pivote: pivote
        }
        $.ajax({
            url: "../obtener_codigo",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
                        $("#codigoRx_").val(registro.codigo);
                        $("#codigoRx").val(registro.codigo);
                        $("#secuenciaRx").val(registro.secuencia);
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


	$(document).on("click", ".actualizar_datos", function(e) {
        e.preventDefault()
        $("#fechaRxU").val($(this).closest('tr').find('.fecha').val());
        $("#codigoRx_U").val($(this).closest('tr').find('.codigo').val());
        $("#codigoRxU").val($(this).closest('tr').find('.codigo').val());
        $("#pacienteRxU").val($(this).closest('tr').find('.nombre').val());
        $("#perteneceAU").val($(this).closest('tr').find('.pertenece').val());
        $("#perteneceRx2U").val($(this).closest('tr').find('.pertenece').val());
        $("#secuenciaRxU").val($(this).closest('tr').find('.secuencia').val());
        $("#idRxU").val($(this).closest('tr').find('.idRx').val());

    });

	$(document).on("change", "#perteneceAU", function(e) {
        e.preventDefault()
        var actual = $("#perteneceRx2U").val();
        var pivote = $(this).val();
        if(actual != pivote){
            var datos = {
                pivote: pivote
            }
            $.ajax({
                url: "../obtener_codigo",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            $("#codigoRx_U").val(registro.codigo);
                            $("#codigoRxU").val(registro.codigo);
                            $("#secuenciaRxU").val(registro.secuencia);
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
        }else{
            var datos = {
                pivote : $('#idRxU').val()
            }
            $.ajax({
                url: "../obtener_datos_actual",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    for (let i = 0; i < registro.length; i++) {
                        $("#fechaRxU").val(registro[i]["fechaArchivado"]);
                        $("#codigoRx_U").val(registro[i]["codigoArchivado"]);
                        $("#codigoRxU").val(registro[i]["codigoArchivado"]);
                        $("#pacienteRxU").val(registro[i]["nombrePaciente"]);
                        $("#perteneceAU").val(registro[i]["perteneceArchivo"]);
                        $("#secuenciaRxU").val(registro[i]["secuenciaArchivado"]);
                        $("#perteneceRx2U").val(registro[i]["perteneceArchivo"]);
                        $("#idRxU").val(registro[i]["idArchivado"]);
                    }
                    
                }
            });
        }

    });

    
</script>