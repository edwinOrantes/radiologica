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
                                <h6>Anular factura</h6>
                            </div>
                            <div class="col-md-6 text-right"></div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Facturacion/procesar_anulacion_factura" novalidate>
                            <div class="row alert-success p-1">

                                <div class="col-md-4">
                                    <p><strong></strong></p>
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
                                
                                <div class="col-md-4 alert-danger ">
                                    <div class="form-group">
                                        <label for="tipoAnulacion"> <strong>Tipo de invalidación</strong> </label>
                                        <select class="form-control" id="tipoAnulacion" name="tipoAnulacion" required>
                                            <option value=""></option>
                                            <!-- <option value="1">Error en la información del Documento Tributario Electrónico a invalidar</option> -->
                                            <option value="2">Rescindir de la operacion realizada</option>
                                            <!-- <option value="3">Otro</option> -->
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar una opción.
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="detalleAnulacion"> <strong>Detalle</strong> </label>
                                        <textarea name="detalleAnulacion" id="detalleAnulacion" class="form-control" required></textarea>
                                        <div class="invalid-tooltip">
                                            Debes agregar un detalle.
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Datos del receptor -->
                            <div class="mt-2">
                                <p class="text-center"><strong>RECEPTOR</strong></p>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Actividad económica </strong> <span id="spanCodigoActividadEconomica" class="badge badge-success"></span> </label>

                                        <select class="form-control controlInteligente" id="actividadPaciente"  name="actividadPaciente" placeholder="Nombre del paciente">
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
    
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Nombre/ Razón social </strong></label>
                                        <input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" value="<?php echo $receptor["nombre"] ?>" placeholder="Nombre del paciente" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el nombre del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 col-sm-12 cl-lg-6">
                                        <label for=""><strong>Tipo de documento</strong></label>
                                        <select class="form-control" value="" id="tipoDocumento" name="tipoDocumento" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <option value="36">NIT</option>
                                            <option value="13" selected>DUI</option>
                                            <option value="37">Otro</option>
                                            <option value="03">Pasaporte</option>
                                            <option value="02">Carnet de residente</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el tipo de documento
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Número</strong></label>
                                        <input type="text" class="form-control" id="documentoPaciente" name="documentoPaciente" value="<?php echo $receptor["numDocumento"] ?>" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el documento del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4" style="display: none">
                                        <label for=""><strong>NRC</strong></label>
                                        <input type="text" class="form-control"  id="nrcPaciente" name="nrcPaciente" placeholder="Documento del paciente">
                                        <div class="invalid-tooltip">
                                            Debes ingresar el NRC.
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Teléfono</strong></label>
                                        <input type="text" class="form-control" id="telefonoPaciente" name="telefonoPaciente" value="<?php echo $receptor["telefono"] ?>" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el teléfono del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Correo</strong></label>
                                        <input type="email"  class="form-control" id="correoPaciente" name="correoPaciente" value="<?php echo $receptor["correo"] ?>">
                                        <div class="invalid-tooltip">
                                            Debes ingresar el correo del paciente.
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-4 col-sm-12 cl-lg-6">
                                        <label for=""> <strong>Departamento</strong> <span id="spanDepartamento" class="badge badge-success"> </span></label>
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

                                    <div class="form-group col-md-4 col-sm-12 cl-lg-6">
                                        <label for=""> <strong>Municipio</strong> <span id="spanMunicipio" class="badge badge-success"> </span></label>
                                        <select class="form-control" value="" id="municipioPaciente" name="municipioPaciente">
                                            <option value="">Elija una opción</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el municipio del paciente.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for=""><strong>Complemento</strong></label>
                                        <input type="text" class="form-control" id="complementoPaciente" name="complementoPaciente" >
                                        <div class="invalid-tooltip">
                                            Debes ingresar dirección del paciente.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <p><strong>Detalle</strong></p>
                                <table id="" class="table table-striped thead-primary">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        

                                        <?php
                                            $index = 1;
                                            foreach ($cuerpoDocumento as $row) {
                                        ?>

                                        <tr>
                                            <td><input size="1" type="text" value="<?php echo $row["cantidad"]; ?>" class="form-control" id="cantidadServicio" name="cantidadServicio[]" required></td>
                                            <td>
                                                <input value="<?php echo $row["descripcion"]; ?>"  class="form-control" id="detalleServicio" name="detalleServicio[]" required>
                                            </td>
                                            <td>
                                                <input type="text" size="1" value="<?php echo $row["precioUni"]; ?>" class="form-control" id="precioServicio" name="precioServicio[]" required>
                                                <input type="hidden" size="1" value="<?php echo $row["ivaItem"]; ?>" class="form-control" id="ivaServicio" name="ivaServicio" required>
                                                <input type="hidden" size="1" value="<?php echo $row["ventaGravada"]; ?>" class="form-control" id="totalServicio" name="totalServicio" required></td>
                                        </tr>
                                        <?php
                                                $index++;
                                            }
                                        ?>

                                    </tbody>
                                </table>
                            </div>

                            <div class="row">

                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Firmar documento <i class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>

                            <input type="hidden" name="identificacionAnterior" value="<?php echo urlencode(base64_encode(serialize($identificacion)));?>">
                            <input type="hidden" name="emisorAnterior" value="<?php echo urlencode(base64_encode(serialize($emisor)));?>">
                            <input type="hidden" name="receptorAnterior" value="<?php echo urlencode(base64_encode(serialize($receptor)));?>">
                            <input type="hidden" name="cuerpoAnterior" value="<?php echo urlencode(base64_encode(serialize($cuerpoDocumento)));?>">
                            <input type="hidden" name="resumenAnterior" value="<?php echo urlencode(base64_encode(serialize($resumen)));?>">
                            <input type="hidden" name="rhAnterior" value="<?php echo urlencode(base64_encode(serialize($respuestaHacienda)));?>">
                            <input type="hidden" name="jsonAnterior" value="<?php echo $jsonAnterior;?>">
                            <input type="hidden" name="idDTEAnular" value="<?php echo $dteAnular;?>">

                            <input type="hidden" class="form-control" id="codigoDepartamento" name="codigoDepartamento">
                            <input type="hidden" class="form-control" id="codigoMunicipio" name="codigoMunicipio">
                            <input type="hidden" class="form-control" id="codigoActividadEconomica" name="codigoActividadEconomica">

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Body Content Wrapper -->


<script>

    $(document).on('change', '#precioServicio', function (event) {
        event.preventDefault();
        var monto = $(this).val();
        var subtotal = monto/1.13;
        var iva = subtotal * 0.13;
        var total = subtotal + iva

        $("#ivaServicio").val(iva.toFixed(2));
        $("#totalServicio").val(total.toFixed(2));
        


    });
    

    $(document).ready(function(){
        $("#filaExterno").hide();
        $("#tipoContingencia").prop("disabled", true);

        $(".controlInteligente").select2({
            theme: "bootstrap4",
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