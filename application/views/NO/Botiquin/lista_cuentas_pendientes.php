<?php if($this->session->flashdata("exito")):?>
    <script type="text/javascript">
    $(document).ready(function() {
        toastr.remove();
        toastr.options.positionClass = "toast-top-center";
        toastr.success('<?php echo $this->session->flashdata("exito")?>', 'Aviso!');
    });
    </script>
<?php endif; ?>

<?php if($this->session->flashdata("error")):?>
    <script type="text/javascript">
    $(document).ready(function() {
        toastr.remove();
        toastr.options.positionClass = "toast-top-center";
        toastr.error('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
    });
    </script>
<?php endif; ?>


<?php
$totalFactura = 0;
foreach ($cuentas as $cuenta) {
    $totalFactura += $cuenta->totalCuentaPagar;
}
?>
<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                        <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fas fa-clipboard-list"></i> Cuentas por pagar </a></li>
                        <li class="breadcrumb-item active"><a href="#">Selecionar proveedor</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-5">
                                <h6> Seleccione un proveedor </h6>
                            </div>

                            <div class="col-md-2"></div>

                            <div class="col-md-5 text-right">
                                <!-- <div id="divBotonera">
                                    <a href="#agregarCuenta" data-toggle='modal' class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar cuenta</a>
                                    <a href="<?php echo base_url();?>CuentasPendientes/saldar_cuentas_pendientes/" class="btn btn-success btn-sm"><i class="fa fa-money-bill"></i> Saldar cuentas</a>
                                </div> -->

                                <div>
                                    <div class="input-group">
                                        <select name="proveedorCuenta" id="proveedorCuenta" class="form-control controlInteligente ">
                                            <option value="" class="text-center">.:: Seleccionar un proveedor ::.</option>
                                            <?php
                                                foreach ($proveedores as $proveedor) {
                                            ?>
                                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->empresaProveedor; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Seleccione un proveedor.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive mt-3" id="tblCuentasPagar">
                                    <form action="<?php echo base_url();?>CuentasPendientes/saldar_cuentas" method="post">
                                        <table class="table table-striped thead-primary w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        <!-- <input class="" type="checkbox" id="seleccionarTodos" value="todos" name="seleccionarTodos[]"> -->
                                                        <label class="ms-switch">
                                                            <input type="checkbox" id="seleccionarTodos" value="todos" name="seleccionarTodos[]">
                                                            <span class="ms-switch-slider round"></span>
                                                        </label>
                                                    </th>
                                                    <th class="text-center" scope="col">Fecha</th>
                                                    <th class="text-center" scope="col">Proveedor</th>
                                                    <th class="text-center" scope="col">Factura</th>
                                                    <th class="text-center" scope="col">Monto</th>
                                                    <th class="text-center" scope="col">Dias restantes</th>
                                                    <th class="text-center" scope="col">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detalleCuentasProveedor">
                                            </tbody>
                                        </table>

                                        <div class="text-center" id="botoneraHoja">
                                            <button class="btn btn-primary mt-4 d-inline w-20" id="btnSaldar" type="button"><i class="fa fa-save"></i> Saldar</button>
                                            <!-- <button class="btn btn-light mt-4 d-inline w-20 cancelar" type="button"><i class="fa fa-times"></i> Cancelar</button> -->
                                        </div>
                                    </form>    
                                </div>
                            </div>
                            
                            <div id="sinDatos" class="col-md-12">
                                <div class="alert alert-danger">
                                    <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo date('Y-m-d')?>" id="fechaHoy"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End Body Content Wrapper -->

<!-- Modal para cerrar hoja-->
    <div class="modal fade p-5" id="agregarCheque" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-primary">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body">
                    <!-- <p class="h5">Ingresa el número de cheque</p> -->
                    <form action="<?php echo base_url()?>CuentasPendientes/saldar_cuentas" method="post">
                        
                        <div class="col-md-12">
                            <label for=""><strong>Para:</strong></label>
                            <div class="input-group">
                                <select class="form-control" id="areaGasto" name="areaGasto" required>
                                    <option value="">.:: Seleccionar::.</option>
                                    <?php
                                        foreach ($categorias as $row) {
                                            echo '<option value="'.$row->idCategoria.'">'.$row->nombreCategoria.'</option>';
                                        }
                                    ?>
                                </select>
                                <div class="invalid-tooltip">
                                    Debes seleccionar el area que requiere el gasto.
                                </div>
                            </div>
                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGasto" name="fechaGasto" required>
                        </div>

                        <div class="px-2 mb-3">
                            <div class="form-group">
                                <label for=""><strong>Número de cheque</strong></label> 
                                <input type="text" id="chequeCuenta" name="chequeCuenta" class="form-control" required="">
                                <div class="invalid-tooltip">
                                    Debes ingresar el número de cheque.
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for=""><strong>Banco</strong></label>    
                                    <div class="input-group">
                                        <select class="form-control" id="bancoGasto" name="bancoGasto" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <?php
                                                foreach ($bancos as $row) {
                                            ?>
                                            <option value="<?php echo $row->idBanco; ?>"><?php echo $row->nombreBanco; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-tooltip">Seleccione el banco</div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for=""><strong>N° de cuenta:</strong></label>    
                                    <div class="input-group">
                                        <!-- <input type="text"  placeholder="Ingrese la cuenta del banco" onkeypress="return soloNumeros(event)" required="">         -->
                                            <select class="form-control numeros" id="cuentaGasto" name="cuentaGasto"required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($cuentasBanco as $row) {
                                                ?>
                                                <option value="<?php echo $row->idCuenta; ?>"><?php echo $row->nombreCuenta." - ".$row->numeroCuenta; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        <div class="invalid-tooltip">Ingrese un nombre.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div id="entradasIds"></div>
        
                        <div class="text-center">
                            <input type="hidden" id="totalGlobalSaldar" name="totalGlobalSaldar">
                            <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-money-bill"></i> Saldar </button>
                            <!-- <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button> -->
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
<!-- Fin Modal cerrar hoja-->




<script>
    $(document).ready(function() {
        $('.controlInteligente').prop('value', false);
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });

         // Desmarcar todos los checkboxes al cargar la página
        $('.cuentas').prop('checked', false);
        $('#seleccionarTodos').prop('checked', false);

        // Checkbox "Seleccionar Todos"
        $('#seleccionarTodos').change(function() {
            $('.cuentas').prop('checked', $(this).prop('checked'));
            calcularTotal();
        });
        // Checkbox "cuentas"
        $(document).on('click', '.cuentas', function (event) {
            if ($(".cuentas").length == $(".cuentas:checked").length) {
                $("#seleccionarTodos").prop("checked", true);
            } else {
                $("#seleccionarTodos").prop("checked", false);
            }
            calcularTotal();
        });

        
    }); // Si

    $(document).on('change', '#proveedorCuenta', function (event) {
        event.preventDefault();
        var proveedor = $(this).val();
        $("#detalleCuentasProveedor").html("");
        $.ajax({
            url: "../obtener_proveedor",
            type: "GET",
            data: {id: proveedor },
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var hoy = $("#fechaHoy").val();
                    var totalFacturas = 0;
                    var html = "";
                        for (let i = 0; i < registro.length; i++) {
                            var totalFacturas = parseFloat(totalFacturas) + parseFloat(registro[i]["totalCuentaPagar"]);
                            // Obteniendo dias desde factura
                                var date_1 = new Date(hoy);
                                var date_2 = new Date(registro[i]["fechaCuentaPagar"]);
                                var day_as_milliseconds = 86400000;
                                var diff_in_millisenconds = date_1 - date_2;
                                var diff_in_days = diff_in_millisenconds / day_as_milliseconds;

                            html += '<tr>';
                            html += '    <td class="text-center" scope="row"><label class="ms-switch"><input class="cuentas" type="checkbox" id="seleccionarUnitario" value="'+registro[i]["idCuentaPagar"]+'" name="cuenta[]"><span class="ms-switch-slider round"></span></label></td>';
                            html += '    <td class="text-center">'+registro[i]["fechaCuentaPagar"]+'</td>';
                            html += '    <td class="text-center">'+registro[i]["empresaProveedor"]+'</td>';
                            html += '    <td class="text-center">'+registro[i]["facturaCuentaPagar"]+'</td>';
                            html += '    <td class="text-center">$ '+registro[i]["totalCuentaPagar"]+' <input type="hidden" class="montoFactura" value="'+registro[i]["totalCuentaPagar"]+'"> </td>';
                            html += '    <td class="text-center">'+ diff_in_days +' dias</td>';
                            if(diff_in_days <= 30){
                                
                                html += '    <td class="text-center"><span class="badge badge-outline-danger">Pendiente</span></td>';
                            }else{
                                html += '    <td class="text-center"><span class="badge badge-outline-danger">Vencida</span></td>';
                                
                            }
                            html += '</tr>';
                        }
                        html += '<tr>';
                        html += '    <td class="text-center" colspan=4><strong>TOTAL</strong></td>';
                        // html += '    <td class="text-center"><strong>$ <span id="totalSumaFacturas">'+totalFacturas.toFixed(2)+'</span><input type="hidden" id="sumaFacturas" value="'+totalFacturas.toFixed(2)+'"></strong></td>';
                        html += '    <td class="text-center"><strong><span id="totalSumaFacturas"></span><input type="hidden" id="sumaFacturas" value=""></strong></td>';
                        html += '    <td class="text-center"></td>';
                        html += '    <td class="text-center"></td>';
                        html += '</tr>';
                        $("#detalleCuentasProveedor").append(html);
                        $("#sinDatos").hide();
                        $("#tblCuentasPagar").show();
                        
                    }else{
                        $("#tblCuentasPagar").hide();
                        $("#sinDatos").show();
                    }
                }
        });
    }); // Si

    $(document).on('click', '#btnSaldar', function (event) {
        //event.preventDefault();
        var html = "";
        var arr = $('[name="cuenta[]"]:checked').map(function(){
            return this.value;
        }).get();
        if(arr.length > 0){
            var str = arr.join(',');
            var ids = str.split(',')
            for (let i = 0; i < ids.length; i++) {
                //console.log(ids[i]);
                html += '<input type="hidden" value="'+ids[i]+'" name="cuenta[]" />';
            }
            $("#entradasIds").html(html);
            $("#agregarCheque").modal();
            var html = "";
        }else{
            var html = "";
            alert("No selecciono ninguna cuenta");
        }
        
    }); // Si

    function calcularTotal() {
        var total = 0;
        var cuentasSeleccionadas = [];
            // Calcular el total de dinero de las filas seleccionadas
            $('.cuentas:checked').each(function() {
            var fila = $(this).closest('tr');
            var totalFila = parseFloat(fila.find('.montoFactura').val().replace('$', '').replace(',', ''));
            total += totalFila;
            
            var cuenta = parseFloat($(this).val());
            cuentasSeleccionadas.push(cuenta);
        });
        
        $('#totalSumaFacturas').text(total.toFixed(2));
        $('#sumaFacturas').val(total.toFixed(2));
        $('#totalGlobalSaldar').val(total.toFixed(2));
        // Almacenar el total en la variable totalPagar
        var totalPagar = total;
        
        // Almacenar las cuentas seleccionadas en el arreglo cuentasSeleccionadas
        // console.log('Cuentas seleccionadas:', cuentasSeleccionadas);
    } // Si

</script>


<!-- Actualizando y eliminando cuentas -->
    <script>
       /*  $(document).on('click', '.eliminarCuenta', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();

            var datos = {
                idCuentaPagar  : $(this).closest('tr').find(".idCuentaPagar").val()
            }
            console.log(datos);

            $.ajax({
                url: "eliminar_cuenta_pagar",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (registro.length > 0){
                            console.log(registro);
                        }
                    }
            });
        });

        $(document).on('click', '.actualizarCuenta', function (event) {
            event.preventDefault();

            $("#idProveedorA").val($(this).closest('tr').find(".idProveedor").val());
            $("#fechaCuentaA").val($(this).closest('tr').find(".fechaCuentaPagar").val());
            $("#nrcCuentaA").val($(this).closest('tr').find(".nrcCuentaPagar").val());
            $("#facturaCuentaA").val($(this).closest('tr').find(".facturaCuentaPagar").val());
            $("#plazoCuentaA").val($(this).closest('tr').find(".plazoCuentaPagar").val());
            $("#subtotalCuentaA").val($(this).closest('tr').find(".subtotalCuentaPagar").val());
            $("#ivaCuentaA").val($(this).closest('tr').find(".ivaCuentaPagar").val());
            $("#perivaCuentaA").val($(this).closest('tr').find(".perivaCuentaPagar").val());
            $("#totalCuentaA").val($(this).closest('tr').find(".totalCuentaPagar").val());
            $("#idCuentaPagarA").val($(this).closest('tr').find(".idCuentaPagar").val());

        });

        $(document).on('change', '.sumaFactura', function (event) {
            event.preventDefault();
            var totalFactura = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.sumaFactura').each(function() {
                totalFactura += parseFloat($(this).val());
                console.log(totalFactura);
            });
            $("#totalCuentaA").val(totalFactura); 
        });

        $(document).on('click', '.cuentas', function (event) {
            //event.preventDefault();
            var totalSaldar = 0;
            if(!$(this).closest('tr').find(".montoFactura").hasClass("seSaldara")){
                $(this).closest('tr').find(".montoFactura").addClass("seSaldara");
            }else{
                $(this).closest('tr').find(".montoFactura").removeClass("seSaldara");
            }

            //var totalFactura = $(this).closest('tr').find(".montoFactura").val();
            $('.seSaldara').each(function() {
                totalSaldar += parseFloat($(this).val());
                //console.log($(this).val());
            });
            $("#totalSumaFacturas").html(totalSaldar.toFixed(2));
            
        }); */
    </script>
<!-- Fin actualizando y eliminando cuentas -->










