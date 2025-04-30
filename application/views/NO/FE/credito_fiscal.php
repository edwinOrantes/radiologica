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
            
                <!-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap">
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Facturación </a> </li>
                        <li class="breadcrumb-item"><a href="#">Factura</a></li>
                    </ol>
                </nav> -->

                <div class="ms-panel">

                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Detalle del crédito fiscal</h6>
                                <div class="">
                                    <!-- Facturar todo -->
                                        <span > Facturar todo </span>
                                        <label class="ms-switch">
                                            <input type="checkbox" id="pivoteFacturas" value="todo" name="factura">
                                            <span class="ms-switch-slider ms-switch-success round"></span>
                                        </label>
                                    <!-- Facturar todo -->

                                    
                                </div>
                                

                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-primary btn-sm" href="#actualizarDatos" data-toggle="modal"><i class="fa fa-file"></i> Actualizar datos</a>
                                <a href="<?php echo base_url()?>Hoja/detalle_hoja/<?php echo $paciente->idHoja; ?>/" class="btn btn-outline-success btn-sm"><i class="fa fa-arrow-left"></i> Volver</a>

                            </div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Facturacion/sellar_ccf" novalidate>
                            <div class="row alert-danger p-1">
                                

                                <div class="col-md-8">
                                    <p><strong>DATOS DEL TERCERO</strong></p>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-left"><strong>DTE</strong></td>
                                            <td><?php echo $dte; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left"><strong>Código de Generación</strong></td>
                                            <td>
                                                <?php echo $cGeneracion; ?>
    
                                                <input type="hidden" value="<?php echo $dte; ?>" name="dteFactura">
                                                <input type="hidden" value="<?php echo $baseDTE; ?>" name="baseDTE">
                                                <input type="hidden" value="<?php echo $cGeneracion; ?>" name="cGeneracion">
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-4">
                                    <!-- Contingencia -->
                                    
                                        <div class="form-group">
                                            <label for=""><strong>Tipo de transmisión</strong></label>
                                            <select class="form-control" id="tipoTransmision" name="tipoTransmision">
                                                <option value="1">Transmisión normal</option>
                                                <option value="2">Transmisión por contingencia</option>
                                            </select>
                                            <div class="invalid-tooltip"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for=""> <strong>Tipo de contingencia</strong> </label>
                                            <select class="form-control" value="<?php echo $anexo->idMunicipio; ?>" id="tipoContingencia" name="tipoContingencia">
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

                                    <!-- Contingencia -->
                                </div>
                            </div>

                            <div class="row">
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

                                <div class="col-md-6">
                                    <p class="text-center"><strong></strong></p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-center" colspan="2">Receptor</th>
                                        </tr>
                                        <tr>
                                            <td><strong>Nombre:</strong></td>
                                            <td><?php echo @$anexo->nombrePaciente == "" ? $paciente->nombrePaciente : $anexo->nombrePaciente; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>DUI:</strong></td>
                                            <td><?php echo @$anexo->duiPaciente == "" ? $paciente->duiPaciente : $anexo->duiPaciente; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Teléfono:</strong></td>
                                            <td><?php echo @$anexo->telefonoPaciente == "" ? $paciente->telefonoPaciente : $anexo->telefonoPaciente; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Correo:</strong></td>
                                            <td><?php echo @$anexo->correoPaciente == "" ? $paciente->correoPaciente : $anexo->correoPaciente; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dirección:</strong></td>
                                            <td><?php echo @$anexo->direccionPaciente == "" ? $paciente->direccionPaciente : $anexo->direccionPaciente; ?></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                            <div class="row">
                                <p><strong>Interno y externos</strong></p>
                                <table id="" class="table table-striped thead-primary">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Precio</th>
                                            <th class="text-center">IVA</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $interno = 0;
                                            $externo = 0;
                                            foreach ($medicamentosHoja as $row) {
                                                $interno += (($row->precioInsumo * $row->cantidadInsumo) - $row->descuentoUnitario);
                                            }
                                            $subTotal = ($interno / 1.13);
                                            $iva = ($subTotal * 0.13);
                                            $totalI = ($subTotal + $iva);

                                            echo '<tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">Medicamentos e Insumos Médicos</td>
                                                        <td class="text-center"> $'.number_format($subTotal, 2).'</td>
                                                        <td class="text-center"> $'.number_format($iva, 2).'</td>
                                                        <td class="text-center"> $'.number_format($totalI, 2).'
                                                            <input type="hidden" value="'.round($iva, 2).'" id="ivaI" name="ivaI">
                                                            <input type="hidden" value="'.round($subTotal, 2).'" id="subTotalI" name="subTotalI">
                                                            <input type="hidden" value="'.round($totalI, 2).'" id="totalInterno" name="totalInterno"></td>
                                                    </tr>';

                                            foreach ($externosHoja as $row) {
                                                $externo += ($row->precioExterno * $row->cantidadExterno);
                                            }

                                            $subTotal = ($externo / 1.13);
                                            $iva = ($subTotal * 0.13);
                                            $totalE = ($subTotal + $iva);

                                            echo '<tr id="filaExterno">
                                                    <td class="text-center">1</td>
                                                    <td class="text-center">Servicios externos</td>
                                                    <td class="text-center">$'.number_format($subTotal, 2).'</td>
                                                    <td class="text-center">$'.number_format($iva, 2).'</td>
                                                    <td class="text-center">$'.number_format($totalE, 2).'
                                                        <input type="text" value="'.round($iva, 2).'" id="ivaE" name="ivaE">
                                                        <input type="text" value="'.round($subTotal, 2).'" id="subTotalE" name="subTotalE">
                                                        <input type="text" value="'.round($totalE, 2).'" id="totalExterno" name="totalExterno"></td>
                                                        
                                                </tr>';



                                        ?>
                                            
                                        <tr>
                                            <td class="text-right" colspan="4"><strong>TOTAL</strong></td>
                                            <td class="text-center"><span id="tdTotalGlobal">$<?php echo number_format($totalI, 2); ?></span>
                                                <input type="hidden" value="<?php echo round($totalI, 2) ?>" id="totalGlobal" name="totalGlobal"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php
                                if(!is_null($anexo)){
                            ?>
                                <div class="row">
                                    <input type="hidden" value="<?php echo $paciente->idPaciente; ?>" id="idPaciente" name="idPaciente">
                                    <input type="hidden" value="<?php echo $paciente->idHoja; ?>" id="idHoja" name="idHoja">
                                    <input type="hidden" value="0" id="facturarTodo" name="facturarTodo">
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Firmar documento <i class="fa fa-arrow-right"></i></button>
                                    </div>
                                </div>
                            <?php } ?>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Body Content Wrapper -->

<!-- Modal actualizar paciente-->
    <div class="modal fade" id="actualizarDatos" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos para la facturación</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                            <form class="needs-validation" method="post" action="<?php echo base_url()?>Facturacion/actualizar_anexo_paciente" novalidate>
                            
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for=""><strong>Actividad económica </strong> <span id="spanCodigoActividadEconomica" class="badge badge-success"><?php echo @$anexo->actividadEconomica; ?></span> </label>

                                        <select class="form-control controlInteligente" id="actividadPaciente"  name="actividadPaciente" placeholder="Nombre del paciente" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <?php
                                                foreach ($actividadesEconomicas as $row) {
                                            ?>
                                                    <option value="<?php echo $row->idDetalleCatalogo; ?>"><?php echo $row->codigoCatDetalle."-".$row->valorCatDetalle?></option>

                                            <?php } ?>
                                        </select>

                                        <div class="invalid-tooltip">
                                            Debes ingresar la actividad economica.
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Nombre/ Razón social </strong></label>
                                        <input type="text" class="form-control" value="<?php echo @$anexo->nombrePaciente == "" ? $paciente->nombrePaciente : $anexo->nombrePaciente; ?>" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el nombre del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-12 cl-lg-6">
                                        <label for="">Tipo de documento</label>
                                        <select class="form-control" value="" id="tipoDocumento" name="tipoDocumento" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <option value="36">NIT</option>
                                            <option value="13">DUI</option>
                                            <option value="37">Otro</option>
                                            <option value="03">Pasaporte</option>
                                            <option value="02">Carnet de residente</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el tipo de documento
                                        </div>
                                    </div>

                                    

                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Número</strong></label>
                                        <input type="text" class="form-control" value="<?php echo @$anexo->duiPaciente == "" ? $paciente->duiPaciente : $anexo->duiPaciente; ?>" id="documentoPaciente" name="documentoPaciente" placeholder="Documento del paciente" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el documento del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for=""><strong>NRC</strong></label>
                                        <input type="text" class="form-control" value="<?php echo @$anexo->nrcCreditoFiscal == "" ? "" : $anexo->nrcCreditoFiscal; ?>" id="nrcPaciente" name="nrcPaciente" placeholder="Documento del paciente">
                                        <div class="invalid-tooltip">
                                            Debes ingresar el NRC.
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Teléfono</strong></label>
                                        <input type="text" class="form-control" value="<?php echo @$anexo->telefonoPaciente == "" ? $paciente->telefonoPaciente : $anexo->telefonoPaciente; ?>" id="telefonoPaciente" name="telefonoPaciente" placeholder="Teléfono del paciente" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el teléfono del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Correo</strong></label>
                                        <input type="email" value="<?php echo @$anexo->correoPaciente == "" ? $paciente->correoPaciente : $anexo->correoPaciente; ?>" class="form-control" id="correoPaciente" name="correoPaciente" placeholder="Correo del paciente">
                                        <div class="invalid-tooltip">
                                            Debes ingresar el correo del paciente.
                                        </div>
                                    </div>

                                </div>


                                <div class="row">
                                    
                                    <div class="form-group col-md-6 col-sm-12 cl-lg-6">
                                        <label for="">Departamento <span id="spanDepartamento" class="badge badge-success"><?php echo @$anexo->codigoDepartamento; ?></span></label>
                                        <select class="form-control" value="" id="departamentoPaciente" name="departamentoPaciente">
                                            <option value="">.:: Seleccionar ::.</option>
                                            <?php
                                                foreach ($departamentos as $row) {
                                            ?>
                                                    <option value="<?php echo $row->idDetalleCatalogo; ?>"><?php echo $row->codigoCatDetalle."-".$row->valorCatDetalle?></option>

                                            <?php } ?>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el departamento del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-12 cl-lg-6">
                                        <label for="">Municipio <span id="spanMunicipio" class="badge badge-success"><?php echo @$anexo->codigoMunicipio; ?></span></label>
                                        <select class="form-control" value="" id="municipioPaciente" name="municipioPaciente">
                                            <option value="">Elija una opción</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el municipio del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for=""><strong>Complemento</strong></label>
                                        <input type="text" value="<?php echo @$anexo->direccionPaciente; ?>" class="form-control" id="complementoPaciente" name="complementoPaciente" >
                                        <div class="invalid-tooltip">
                                            Debes ingresar dirección del paciente.
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group text-center mt-3">
                                    <input type="hidden" value="<?php echo $paciente->idPaciente; ?>" class="form-control" id="idPaciente" name="idPaciente">
                                    <input type="hidden" value="<?php echo @$anexo->codigoDepartamento; ?>" class="form-control" id="codigoDepartamento" name="codigoDepartamento">
                                    <input type="hidden" value="<?php echo @$anexo->codigoMunicipio; ?>" class="form-control" id="codigoMunicipio" name="codigoMunicipio">
                                    <input type="hidden" value="<?php echo @$anexo->codigoMunicipio; ?>" class="form-control" id="codigoActividadEconomica" name="codigoActividadEconomica">
                                    <button type="button" id="btnActualizarDatosCCF" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Actualizar datos </button>
                                </div>
                            
                            </form>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal actualizar paciente-->


<script>

    $(document).ready(function(){
        $("#filaExterno").hide();
        $("#tipoContingencia").prop("disabled", true);

        $(".controlInteligente").select2({
            theme: "bootstrap4",
            dropdownParent: $("#actualizarDatos")
        });


        $("#pivoteFacturas").click(function() {
            var valor = $('input:checkbox[name=factura]:checked').val();
            var interno = parseFloat($("#totalInterno").val());
            var externo = parseFloat($("#totalExterno").val());

            if (valor == "todo") {
                $("#filaExterno").show();
                $("#totalGlobal").val((interno + externo));
                $("#tdTotalGlobal").html("$" + (interno + externo));
                $("#facturarTodo").val(1);
            } else {
                $("#totalGlobal").val(interno);
                $("#tdTotalGlobal").html("$" + interno);
                $("#filaExterno").hide();
                $("#facturarTodo").val(0);
            }
        });

    });

    $("#tipoTransmision").change(function () {
        if($(this).val() == 2){
            $("#tipoContingencia").prop("disabled", false); 
        }else{
            $("#tipoContingencia").prop("disabled", true); 
        }
        
    });

    $("#tipoDocumento").change(function () {
        // alert($(this).val());
        if($(this).val() == 13){
            $('#documentoPaciente').val('');
            $('#documentoPaciente').attr('placeholder', 'Ingrese en número, ej. 99999999-9');
        }else{
            $('#documentoPaciente').val('');
            $('#documentoPaciente').attr('placeholder', 'Ingrese en número sin guiones');
        }
        
    });


    $("#departamentoPaciente").change(function () {

        // Obtén el valor y el texto de la opción seleccionada
        const valorSeleccionado = $("#departamentoPaciente").val(); // El atributo 'value'
        const textoSeleccionado = $("#departamentoPaciente option:selected").text(); // El texto visible


        let partes = textoSeleccionado.split("-");
        $("#codigoDepartamento").val(textoSeleccionado);

        $("#spanDepartamento").html(partes[0]);
        

        $('#municipioPaciente').each(function(){
            $('#municipioPaciente option').remove();
        })
        $.ajax({
            url: "../../obtener_municipios",
            type: "GET",
            data: {id:$(this).val()},
            success:function(respuesta){
                var registro = eval(respuesta);
                    if (registro.length > 0)
                    {
                        var municipio = "<option value=''>Elija una opción</option>";
                        for (var i = 0; i < registro.length; i++) 
                        {
                            municipio += "<option value='"+ registro[i]["idDetalleCatalogo"] +"'>"+ registro[i]["codigoCatDetalle"] +"-"+registro[i]["valorCatDetalle"] +"</option>";
                        }
                        $("#municipioPaciente").append(municipio);
                    }
                }
            });
    });

    $("#municipioPaciente").change(function () {

        // Obtén el valor y el texto de la opción seleccionada
        const valorSeleccionado = $("#municipioPaciente").val(); // El atributo 'value'
        const textoSeleccionado = $("#municipioPaciente option:selected").text(); // El texto visible


        let partes = textoSeleccionado.split("-");
        $("#codigoMunicipio").val(textoSeleccionado);
        $("#spanMunicipio").html(partes[0]);


        
    });

    $("#actividadPaciente").change(function () {

        // Obtén el valor y el texto de la opción seleccionada
        const valorSeleccionado = $("#actividadPaciente").val(); // El atributo 'value'
        const textoSeleccionado = $("#actividadPaciente option:selected").text(); // El texto visible


        let partes = textoSeleccionado.split("-");
        $("#codigoActividadEconomica").val(textoSeleccionado);
        $("#spanCodigoActividadEconomica").html(partes[0]);


        
    });

    
    $("#btnActualizarDatos").click(function () {
        var datos = {
            idPaciente : $("#idPaciente").val(),
            departamentoPaciente : $("#departamentoPaciente").val(),
            municipioPaciente : $("#municipioPaciente").val(),
            nombrePaciente : $("#nombrePaciente").val(),
            duiPaciente : $("#duiPaciente").val(),
            telefonoPaciente : $("#telefonoPaciente").val(),
            correoPaciente : $("#correoPaciente").val(),
            codigoDepartamento : $("#codigoDepartamento").val(),
            codigoMunicipio : $("#codigoMunicipio").val(),
            complementoPaciente : $("#complementoPaciente").val()
        };

        // console.log(datos);

        $.ajax({
            url: "../../actualizar_datos_facturacion",
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
                        toastr.success('Datos actualizados', 'Aviso!');
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
                        toastr.error('Nio se actualizaron los datos...', 'Aviso!');
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
                    toastr.error('Nio se actualizaron los datos...', 'Aviso!');
                }
            }
        }); 
    });
    
    $("#btnActualizarDatosCCF").click(function () {
        var datos = {
            idPaciente : $("#idPaciente").val(),
            idDepartamento : $("#departamentoPaciente").val(),
            idMunicipio : $("#municipioPaciente").val(),
            nombrePaciente : $("#nombrePaciente").val(),
            duiPaciente : $("#documentoPaciente").val(),
            telefonoPaciente : $("#telefonoPaciente").val(),
            correoPaciente : $("#correoPaciente").val(),
            codigoDepartamento : $("#codigoDepartamento").val(),
            codigoMunicipio : $("#codigoMunicipio").val(),
            direccionPaciente : $("#complementoPaciente").val(),
            actividadEconomica : $("#codigoActividadEconomica").val(),
            tipoDocumento : $("#tipoDocumento").val(),
            nrcCreditoFiscal : $("#nrcPaciente").val(),
        };

        $.ajax({
            url: "../../actualizar_facturacion_ccf",
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
                        toastr.success('Datos actualizados', 'Aviso!');
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
                        toastr.error('Nio se actualizaron los datos...', 'Aviso!');
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
                    toastr.error('Nio se actualizaron los datos...', 'Aviso!');
                }
            }
        }); 
    });

    $(document).on('click', '.close', function(event) {
        event.preventDefault();
        location.reload();
    });
</script>