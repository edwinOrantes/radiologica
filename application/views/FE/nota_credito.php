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

<?php
    $jsonDTE = unserialize(base64_decode(urldecode($jsonDTE)));
?>

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
                                <h6>Detalle de la nota crédito fiscal</h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?php echo base_url()?>Facturacion/lista_ccf/" class="btn btn-outline-success btn-sm"><i class="fa fa-arrow-left"></i> Volver</a>

                            </div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Facturacion/crear_nota_credito" novalidate>
                            <div class="row alert-danger p-1">
                                

                                <div class="col-md-8">
                                    <p><strong>DATOS DEL DOCUMENTO</strong></p>
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
                                            <td><?php echo $jsonDTE["dteJson"]["receptor"]["nombre"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>NIT:</strong></td>
                                            <td><?php echo $jsonDTE["dteJson"]["receptor"]["nit"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Teléfono:</strong></td>
                                            <td><?php echo $jsonDTE["dteJson"]["receptor"]["telefono"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Correo:</strong></td>
                                            <td><?php echo $jsonDTE["dteJson"]["receptor"]["correo"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dirección:</strong></td>
                                            <td><?php echo $jsonDTE["dteJson"]["receptor"]["descActividad"]; ?></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                            <div class="row">
                                <p><strong>Detalle</strong></p>
                                <table id="" class="table table-striped thead-primary">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Precio Unitario</th>
                                            <th class="text-center">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $subTotal = $jsonDTE["dteJson"]["cuerpoDocumento"][0]["precioUni"];
                                            $iva = $jsonDTE["dteJson"]["resumen"]["tributos"][0]["valor"];
                                            $total = $jsonDTE["dteJson"]["resumen"]["montoTotalOperacion"];
                                            
                                            $documento = $jsonDTE["dteJson"]["cuerpoDocumento"];
                                            $strDocumento = urlencode(base64_encode(serialize($jsonDTE))); // Datos de sistema local;

                                            /* 
                                            $totalGlobal = 0;
                                            $ivaGlobal = 0; */
                                            foreach ($documento as $row) {
                                                 /* $precio = $row["precioUni"];
                                                $cantidad = $row["cantidad"];
                                                $subtotal = $cantidad * $precio; // Sin iva
                                                $iva_fila =  $subtotal * 0.13;
                                                $ivaGlobal += $iva_fila;

                                                $total_fila = $subtotal + $iva_fila;
                                                $totalGlobal += $total_fila;
                                               
                                                echo '<tr>
                                                    <td class="text-center">1</td>
                                                    <td class="text-center">'.$row["descripcion"].'</td>
                                                    <td class="text-center"> $'.$row["precioUni"].'</td>
                                                    <td class="text-center"> $'.number_format($iva, 2).'</td>
                                                    <td class="text-center"> $'.number_format($total, 2).'
                                                        <input type="hidden" value="'.round($subTotal, 2).'" id="subTotalOld" name="subTotalOld">
                                                        <input type="hidden" value="'.round($iva, 2).'" id="ivaOld" name="ivaOld">
                                                        <input type="hidden" value="'.round($total, 2).'" id="totalOld" name="totalOld">
                                                    </td>
                                                </tr>'; */
                                            


                                        ?>
                                        
                                        

                                        <tr>
                                            <td><input size="1" type="text" value="<?php echo $row["cantidad"]; ?>" class="form-control cantidadServicio" id="" name="cantidadServicio" required readonly></td>
                                            <td>
                                                <textarea rows="1"  class="form-control descripcionServicios" id="" name="descripcionServicio" required readonly><?php echo $row["descripcion"]; ?></textarea>
                                            </td>
                                            <td><input type="text" size="1" value="<?php echo $row["precioUni"]; ?>" class="form-control precioServicio" name="precioServicio" required readonly></td>
                                            <td><input type="text" size="1" value="<?php echo $row["ventaGravada"]; ?>" class="form-control subtotalServicio" name="subtotalServicio" required readonly></td>
                                        </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 text-center" >
                                    <input type="hidden" value="<?php echo $strDocumento; ?>" id="strDocumento" name="strDocumento">
                                    <input type="hidden" value="<?php echo $idDTEC; ?>" id="idDTEC" name="idDTEC">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Crear documento <i class="fa fa-arrow-right"></i></button>
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
        $("#btnCrearNota").hide();
        $("#tipoContingencia").prop("disabled", true);

        $(".controlInteligente").select2({
            theme: "bootstrap4",
            dropdownParent: $("#actualizarDatos")
        });


    });

    $("#tipoTransmision").change(function () {
        if($(this).val() == 2){
            $("#tipoContingencia").prop("disabled", false); 
        }else{
            $("#tipoContingencia").prop("disabled", true); 
        }
        
    });

    
    $(document).on('change', '.precioServicio', function (event) {
        // event.preventDefault();
        var precio = $(this).closest("tr").find(".precioServicio").val();
        var cantidad = $(this).closest("tr").find(".cantidadServicio").val();

        var precio_s_iva = precio / 1.13
        var subtotal_fila = precio_s_iva * cantidad;
        var iva_fila = subtotal_fila * 0.13;
        var total_fila = subtotal_fila + iva_fila;


        $(this).closest("tr").find(".precioServicio").val(precio_s_iva);
        $(this).closest("tr").find(".subtotalServicio").val(subtotal_fila);
        $(this).closest("tr").find(".ivaServicio").val(iva_fila);
        $(this).closest("tr").find(".totalServicio").val(total_fila);
        
        
        // Valores anteriores
        var precioOld = $(this).closest("tr").find(".precioOld").val();
        var subTotalOld = $(this).closest("tr").find(".subTotalOld").val();
        var ivaOld = $(this).closest("tr").find(".ivaOld").val();
        var totalOld = $(this).closest("tr").find(".totalOld").val();
        
        
        // alert(precioOld + " " +subTotalOld + " " +ivaOld + " " +totalOld)
        
        // Valores anteriores


        // Valores nuevos
            $(this).closest("tr").find(".precioNew").val(precioOld - precio_s_iva);
            $(this).closest("tr").find(".subTotalNew").val(subTotalOld - subtotal_fila);
            $(this).closest("tr").find(".ivaNew").val(ivaOld - iva_fila);
            $(this).closest("tr").find(".totalNew").val(totalOld - total_fila);
        // Valores nuevos



        $("#precioServicio").val(subtotal.toFixed(2));
        $("#ivaServicio").val(iva.toFixed(2));
        $("#totalServicio").val(total.toFixed(2));

        // Obteniendo valores nuevos
            $("#subTotalNew").val((subTotalOld - subtotal).toFixed(2));
            $("#ivaNew").val((ivaOld - iva).toFixed(2));
            $("#totalNew").val((totalOld - total).toFixed(2));
        // Obteniendo valores nuevos

        // Mostrando boton
            $("#btnCrearNota").show();
        // Mostrando boton
        


    });
    
    $(document).on('change', '.cantidadServicio', function (event) {
        // event.preventDefault();
        var precio = $(this).closest("tr").find(".precioServicio").val();
        var cantidad = $(this).val();

        var subtotal_fila = precio * cantidad;
        var iva_fila = subtotal_fila * 0.13;
        var total_fila = subtotal_fila + iva_fila;


        // $(this).closest("tr").find(".precioServicio").val(precio_s_iva);
        $(this).closest("tr").find(".subtotalServicio").val(subtotal_fila);
        $(this).closest("tr").find(".ivaServicio").val(iva_fila);
        $(this).closest("tr").find(".totalServicio").val(total_fila);
        
        
        // Valores anteriores
        var precioOld = $(this).closest("tr").find(".precioOld").val();
        var subTotalOld = $(this).closest("tr").find(".subTotalOld").val();
        var ivaOld = $(this).closest("tr").find(".ivaOld").val();
        var totalOld = $(this).closest("tr").find(".totalOld").val();
        
        
        // alert(precioOld + " " +subTotalOld + " " +ivaOld + " " +totalOld)
        
        // Valores anteriores
        
        // Valores nuevos
            $(this).closest("tr").find(".precioNew").val(precioOld);
            $(this).closest("tr").find(".subTotalNew").val(subTotalOld - subtotal_fila);
            $(this).closest("tr").find(".ivaNew").val(ivaOld - iva_fila);
            $(this).closest("tr").find(".totalNew").val(totalOld - total_fila);
        // Valores nuevos


        // Mostrando boton
            $("#btnCrearNota").show();
        // Mostrando boton
        


    });

    $(document).on('click', '.close', function(event) {
        event.preventDefault();
        location.reload();
    });
</script>