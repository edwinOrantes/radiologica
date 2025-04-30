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

                <div class="ms-panel">

                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Detalle de la contingencia</h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Facturacion/lista_contingencias"><i class="fa fa-file"></i> Lista Contingencia</a>
                            </div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Facturacion/crear_contingencia" novalidate>

                            <!-- Datos del emisor -->
                                <div class="row">
                                    <div class="col-md-6 alert-danger p-1">

                                        <div class="col-md-12">
                                            <table class="table table-borderless">
                                               
                                                <tr>
                                                    <td class="text-left"><strong>Código de Generación</strong></td>
                                                    <td>
                                                        <?php echo $cGeneracion; ?>
                                                        <input type="hidden" value="<?php echo $cGeneracion; ?>" name="cGeneracion">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-12">
                                            <!-- Contingencia -->
                                                <div class="form-group">
                                                    <label for=""><strong>Tipo de transmisión</strong></label>
                                                    <select class="form-control" id="tipoTransmision" name="tipoTransmision" required>
                                                        <!-- <option value="1">Transmisión normal</option> -->
                                                        <option value="2">Transmisión por contingencia</option>
                                                    </select>
                                                    <div class="invalid-tooltip"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for=""> <strong>Tipo de contingencia</strong> </label>
                                                    <select class="form-control" id="tipoContingencia" name="tipoContingencia" required>
                                                        <option value=""></option>
                                                        <option value="1">No disponibilidad de sistema del MH</option>
                                                        <option value="2">No disponibilidad de sistema del emisor</option>
                                                        <option value="3">Falla en el suministro de servicio de Internet del Emisor</option>
                                                        <option value="4">Falla en el suministro de servicio de energía eléctrica del Emisor que impida la transmision de los DTE</option>
                                                    </select>
                                                    <div class="invalid-tooltip">
                                                        Debes seleccionar el municipio del paciente.
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for=""> <strong>Detalle</strong> </label>
                                                    <textarea class="form-control" id="textoContingencia" name="textoContingencia" required></textarea>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el motivo.
                                                    </div>
                                                </div>


                                            <!-- Contingencia -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-center"><strong></strong></p>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-center" colspan="2">Emisor</th>
                                            </tr>
                                            <tr>
                                                <td><strong>Nombre:</strong></td>
                                                <td><?php echo $empresa->nombreEmpresa; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>NIT:</strong></td>
                                                <td><?php echo $empresa->nitEmpresa; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>NRC:</strong></td>
                                                <td><?php echo $empresa->nrcEmpresa; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Actividad Económica:</strong></td>
                                                <td><?php echo $empresa->codActividadEmpresa; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Correo:</strong></td>
                                                <td><?php echo $empresa->correoEmpresa; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <!-- Datos del emisor -->

                            <!-- Datos del receptor -->
                                <div class="mt-3">
                                    <p class="text-center"><strong>DATOS PARA LA SOLICITUD</strong></p>
                                    <hr>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label for=""><strong>Fecha de inicio</strong></label>
                                            <input type="date" class="form-control" value="" id="inicioContingencia" name="inicioContingencia" placeholder="Fecha del inicio de la contingencia" required>
                                            <div class="invalid-tooltip">
                                                Debes ingresar la fecha.
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for=""><strong>Fecha de fin</strong></label>
                                            <input type="date" class="form-control" value="" id="finContingencia" name="finContingencia" placeholder="Fecha del final de la contingencia" required>
                                            <div class="invalid-tooltip">
                                                Debes ingresar la fecha.
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label for=""><strong>Hora de inicio</strong></label>
                                            <input type="text" class="form-control" value="" id="hinicioContingencia" name="hinicioContingencia" placeholder="Hora del inicio de la contingencia" required>
                                            <div class="invalid-tooltip">
                                                Debes ingresar la hora.
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for=""><strong>Hora de fin</strong></label>
                                            <input type="text" class="form-control" value="" id="hfinContingencia" name="hfinContingencia" placeholder="Hora del final de la contingencia" required>
                                            <div class="invalid-tooltip">
                                                Debes ingresar la hora.
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <!-- Datos del receptor -->
                            
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Enviar solicitud <i class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Body Content Wrapper -->

<script>

    $(document).ready(function(){

        $('#hinicioContingencia, #hfinContingencia').timepicker({
            timeFormat: 'HH:mm:ss',
            interval: 30, // Intervalo de 30 minutos
            minTime: '00:00', // Hora mínima
            maxTime: '23:00', // Hora máxima
            defaultTime: false, // Hora por defecto
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });

    });

    $(document).on('change', '#tipoContingencia', function(event) {
    event.preventDefault();

    // Obtener el valor del option seleccionado
    let valor = $(this).val();

    // Obtener el texto del option seleccionado
    let texto = $(this).find("option:selected").text();

    $("#textoContingencia").val(texto);
});


    
</script>

