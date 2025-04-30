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
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Herramientas </a> </li>
                        <li class="breadcrumb-item"><a href="#">Facturas ISBM</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6"><h6>Listado de facturas</h6></div>
                            <div class="col-md-6 text-right">
                                    <a href="#agregarFactura" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar factura</a>
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

    <div class="containerFactura" style="display: none;">
        <div id="factura">
            <table style="border-collapse: collapse; border-spacing: 0; width: 18.2cm;" id="tabla_factura">
                <tr>
                    <td rowspan="3" style="width: 3cm; text-align: right; border-collapse: collapse; border-spacing: 0;"></td>
                    <td style="width: 8.5cm; font-size: 12px; text-align: left;">INSTITUTO SALVADOREÑO DE BIENESTAR MAGISTERIAL</td>
                    <td style="font-size: 12px; text-align: left;"><?php echo date('Y-m-d');?></td>
                </tr>
                <tr>
                    <td style="width: 8.5cm; font-size: 12px; text-align: left;"></td>
                    <td style="font-size: 12px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="width: 8.5cm; font-size: 12px; text-align: left;"></span></td>
                    <td style="font-size: 12px; text-align: left;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 1cm; text-align: left;"></td>
                </tr>
            </table>
            <table style="border-collapse: collapse; border-spacing: 0; height: 7.4cm; width: 18.2cm;">
                <tr>
                    <td style="width: 1.7cm; position: relative; top: -100px; font-size: 13px;">1</td>
                    <td style="width: 9.5cm; position: relative; top: -100px;  font-size: 13px; padding-top:100px"><span id="txtConcepto"></td>
                    <td style="width: 1.6cm; position: relative; top: -100px; font-size: 13px;"><span id="txtUnitario"></td>
                    <td style="width: 1.6cm; text-align: left;"></td>
                    <td style="width: 1.6cm; text-align: left;"></td>
                    <td style="width: 2.4cm; position: relative; top: -100px; font-size: 13px; padding-left:10px"><span id="txtAfectas"></td>
                </tr>
            </table>

            <div style="width: 18.2cm">
                <div style="width: 13.13cm; float: left">
                    <table style="border-collapse: collapse; border-spacing: 0;">
                        <tr>
                            <td colspan="2" style="height: 0.9cm; text-align: left; font-size: 11px; padding-left:15px"> LAS GRACIAS DE QUE SOMO LOS DE LA NUEVA REVOLUCION WEPAJE </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 0.25cm; text-align: left;"></td>
                        </tr>
                        <tr>
                            <td rowspan="3" style="width: 4.6cm;"></td>
                            <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 11px; padding-top:10px">
                                
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 11px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 8px;"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 0.9cm; text-align: left; font-size: 8px;"></td>
                        </tr>
                    </table>
                </div>

                <div style="width: 4.94cm; float: left">
                    <table style="border-collapse: collapse; border-spacing: 0; width: 4.94cm;">
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left;"></td>
                        </tr>
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left; font-size: 13px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left; font-size: 13px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left; font-size: 13px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left; font-size: 13px; padding-top: 25px"><span id="txtSubtotal"></span></td>
                        </tr>
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left; font-size: 13px;"><span id="txtRetenido"></span></td>
                        </tr>
                        <tr>
                            <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                            <td style="height: 0.4cm; text-align: left; font-size: 13px;"><span id="txtVTotal"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Fin Body -->

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="agregarFactura" tabindex="-1" role="dialog" aria-hidden="true">
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
                                        <div class="col-md-12">
                                            <label for=""><strong>Fecha</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaISBM" name="fechaISBM" placeholder="Nombre del usuario" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la fecha del anuncio.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <strong>Contrato</strong><br>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="contratoISBM" id="contratoISBM" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar el contrato.
                                                </div>
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Total Factura</strong><br>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="totalISBM" id="totalISBM" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar el total de la factura.
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <strong>Cantidad gravada</strong><br>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="totalGISBM" id="totalGISBM" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar la cantidad gravada.
                                                </div>
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <strong>IVA retenido</strong><br>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="retenidoISBM" id="retenidoISBM" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar el IVA retenido.
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <strong>Subtotal</strong><br>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="subtotalISBM" id="subtotalISBM" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar el subtotal.
                                                </div>
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <strong>Ventas totales</strong><br>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="ventaTotalISBM" id="ventaTotalISBM" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar la venta total.
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="button" id="generarFactura"><i class="fa fa-save"></i> Guardar factura</button>
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


<script>
    $(document).ready(function() {
        $(".controlInteligente").select2({
            theme: "bootstrap4",
            // dropdownParent: $("#inmunologia")
        });
    });

    $(document).on("click", "#generarFactura", function(event) {
        event.preventDefault();
        porciones = $("#fechaISBM").val().split("-");
        var meses = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
        var contrato = $("#contratoISBM").val();

        if(contrato != ""){

            mes = parseInt(porciones[1]-1); // Obteniendo el indice del mes deseado
            anio = parseInt(porciones[1]-1); // Obteniendo el indice del mes deseado
            concepto =  "SERVICIO MEDICO-HOSPITALARIOS PARA<br> ";
            concepto +=  "DOCENTES Y BENEFICIARIOS QUE LO<br> ";
            concepto +=  "DEMANDEN A TRAVES DEL PROGRAMA<br> ";
            concepto +=  "DE SALUD DEL INSTITUTO SALVADOREÑO<br> ";
            concepto +=  "DE BIENESTAR MAGISTERIAL, MES DE<br> ";
            concepto +=  meses[mes]+" DE "+porciones[0]+" <br>";
            concepto += "<br>";
            concepto += "SEGÚN CONTRATO "+contrato;
            
            var datos = {
                fecha : $("#fechaISBM").val(),
                contrato : $("#contratoISBM").val(),
                total    : $("#totalISBM").val(),
                gravada  : $("#totalGISBM").val(),
                retenido : $("#retenidoISBM").val(),
                subtotal : $("#subtotalISBM").val(),
                vtotal   : $("#ventaTotalISBM").val(),
                concepto : concepto,
            };

            $.ajax({
                url: "guardar_factura",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (registro.length > 0){
                            console.log(registro);
                        }
                    }
            });
            /* $("#txtConcepto").html(concepto);
            $("#txtUnitario").html($("#totalISBM").val());
            $("#txtAfectas").html($("#totalISBM").val());

            $("#txtSubtotal").html($("#subtotalISBM").val());
            $("#txtRetenido").html($("#retenidoISBM").val());
            $("#txtVTotal").html($("#ventaTotalISBM").val()); */


            //imprimirFactura();
        }else{
            alert("Ingresa el contrato");
            $("#contratoISBM").focus()
        }
    });

    $(document).on("change", "#totalISBM", function() {
        var total = parseFloat($(this).val());
        var sinIva = (total / 1.13);
        var retenido = (sinIva * 0.01);
        var subtotal = total - retenido;

        $("#totalGISBM").val(sinIva.toFixed(2));
        $("#retenidoISBM").val(retenido.toFixed(2));
        $("#subtotalISBM").val(subtotal.toFixed(2));
        $("#ventaTotalISBM").val(subtotal.toFixed(2));

    });

    function imprimirFactura(){
        var elemento=document.getElementById('factura');
        var pantalla=window.open(' ','popimpr');

        pantalla.document.write('<html><head><title>' + document.title + '</title>');
        pantalla.document.write('<link href="<?= base_url() ?>public/css/factura_consumidor_final.css" rel="stylesheet" /></head><body>');
        pantalla.document.write(elemento.innerHTML);
        pantalla.document.write('</body></html>');
        pantalla.document.close();
        pantalla.focus();
        pantalla.onload = function() {
            pantalla.print();
            pantalla.close();
        };
        //    $(".mos").hide();
        //    $(".ocultarImprimir").show();
    }
            
</script>





