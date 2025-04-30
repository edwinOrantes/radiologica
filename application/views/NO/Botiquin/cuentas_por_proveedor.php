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

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fas fa-clipboard-list"></i> Cuentas por pagar </a></li>
                    <li class="breadcrumb-item active"><a href="#">Detalle por proveedores</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                
                <div class="ms-panel-header">
                    <div class="row">
                        
                        <div class="col-md-5 text-right">
                            <div id="">
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
                        <div class="col-md-2">
                            <!-- <strong> Saldar </strong>
                            <label class="ms-checkbox-wrap">
                                <input class="form-check-input" type="checkbox" id="verPorFechas" value="porFecha" name="porFecha">
                                <i class="ms-checkbox-check"></i>
                            </label> -->
                        </div>
                        <div class="col-md-5">
                            <div id="estadoCuentas">
                                <span> Saldadas </span>
                                <label class="ms-checkbox-wrap">
                                <input type="radio" name="filtroCuentas" class="filtroCuentas" value="0">
                                    <i class="ms-checkbox-check"></i>
                                </label>

                                <span> Pendientes </span>
                                <label class="ms-checkbox-wrap">
                                <input type="radio" name="filtroCuentas" class="filtroCuentas" value="1">
                                    <i class="ms-checkbox-check"></i>
                                </label>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="ms-panel-body">
                    <div class="row">
                       
                        <div id="" class="col-md-12">
                            <div class="table-responsive mt-3" id="tblCuentasPagar">
                                <form action="<?php echo base_url();?>CuentasPendientes/saldar_cuentas" method="post">
                                    <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">Fecha</th>
                                                <th class="text-center" scope="col">Proveedor</th>
                                                <th class="text-center" scope="col">Monto</th>
                                                <th class="text-center" scope="col">Dias restantes</th>
                                                <th class="text-center" scope="col">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detalleCuentasProveedor">
                                        </tbody>
                                    </table>

                                    <div class="text-center" id="botoneraHoja" style="display: none;">
                                        <button class="btn btn-primary mt-4 d-inline w-20" id="btnSaldar" type="button"><i class="fa fa-save"></i> Saldar</button>
                                        <button class="btn btn-light mt-4 d-inline w-20 cancelar" type="button"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                </form>    
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo date('Y-m-d')?>" id="fechaHoy"/>
                        <div id="sinDatos" class="col-md-12">
                            <div class="alert alert-danger">
                                <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
    });

    $(document).on('change', '#proveedorCuenta', function (event) {
        event.preventDefault();
        $(".filtroCuentas").prop("checked", false);
        $("#estadoCuentas").fadeIn();

        var proveedor = $(this).val();
        $("#detalleCuentasProveedor").html("");
        $.ajax({
            url: "validar_por_proveedor",
            type: "GET",
            data: {id: proveedor },
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var hoy = $("#fechaHoy").val();
                    var html = "";
                        for (let i = 0; i < registro.length; i++) {
                            // Obteniendo dias desde factura
                            
                            var date_1 = new Date(hoy);
                            var date_2 = new Date(registro[i]["fechaCuentaPagar"]);
                                var day_as_milliseconds = 86400000;
                                var diff_in_millisenconds = date_1 - date_2;
                                var diff_in_days = diff_in_millisenconds / day_as_milliseconds;

                            html += '<tr>';
                            html += '    <td class="text-center">'+registro[i]["fechaCuentaPagar"]+'</td>';
                            html += '    <td class="text-center">'+registro[i]["empresaProveedor"]+'</td>';
                            html += '    <td class="text-center">$ '+registro[i]["totalCuentaPagar"]+'</td>';
                            
                            if(registro[i]["estadoCuentaPagar"] == 1){
                                html += '    <td class="text-center">'+ diff_in_days +' dias</td>';
                            }else{
                                html += '    <td class="text-center">---</td>';
                            }
                            
                            if(registro[i]["estadoCuentaPagar"] == 1){

                                if(diff_in_days <= 30){
                                    html += '    <td class="text-center"><span class="badge badge-outline-danger">Pendiente</span></td>';
                                }else{
                                    html += '    <td class="text-center"><span class="badge badge-outline-danger">Vencida</span></td>';
                                    
                                }
                            }else{
                                html += '    <td class="text-center"><span class="badge badge-outline-success">Saldada</span></td>';
                            }

                            html += '</tr>';

                        }
                        $("#detalleCuentasProveedor").append(html);
                        $("#sinDatos").hide();
                        $("#tblCuentasPagar").show();
                        
                    }else{
                        $("#tblCuentasPagar").hide();
                        $("#sinDatos").show();
                    }
                }
        });

    });

    $(document).on('click', 'input:radio[name=filtroCuentas]:checked', function (event) {
        //event.preventDefault();
        $("#detalleCuentasProveedor").html("");
        var pivote = $('input:radio[name=filtroCuentas]:checked').val();
        var proveedor = $("#proveedorCuenta").val();
        $.ajax({
            url: "validar_por_proveedor2",
            type: "GET",
            data: {id: proveedor, p: pivote },
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var hoy = $("#fechaHoy").val();
                    var html = "";
                        for (let i = 0; i < registro.length; i++) {
                            // Obteniendo dias desde factura
                            
                            var date_1 = new Date(hoy);
                            var date_2 = new Date(registro[i]["fechaCuentaPagar"]);
                                var day_as_milliseconds = 86400000;
                                var diff_in_millisenconds = date_1 - date_2;
                                var diff_in_days = diff_in_millisenconds / day_as_milliseconds;

                            html += '<tr>';
                            html += '    <td class="text-center">'+registro[i]["fechaCuentaPagar"]+'</td>';
                            html += '    <td class="text-center">'+registro[i]["empresaProveedor"]+'</td>';
                            html += '    <td class="text-center">$ '+registro[i]["totalCuentaPagar"]+'</td>';
                            if(registro[i]["estadoCuentaPagar"] == 1){
                                html += '    <td class="text-center">'+ diff_in_days +' dias</td>';
                            }else{
                                html += '    <td class="text-center">---</td>';
                            }
                            if(registro[i]["estadoCuentaPagar"] == 1){

                                if(diff_in_days <= 30){
                                    html += '    <td class="text-center"><span class="badge badge-outline-danger">Pendiente</span></td>';
                                }else{
                                    html += '    <td class="text-center"><span class="badge badge-outline-danger">Vencida</span></td>';
                                    
                                }
                            }else{
                                html += '    <td class="text-center"><span class="badge badge-outline-success">Saldada</span></td>';
                            }

                            html += '</tr>';

                        }
                        $("#detalleCuentasProveedor").append(html);
                        $("#sinDatos").hide();
                        $("#tblCuentasPagar").show();
                        
                    }else{
                        $("#tblCuentasPagar").hide();
                        $("#sinDatos").show();
                    }
                }
        });

        
    });

</script>

<script>
    $(document).ready(function() {
        $("#verPorFechas").click(function() {
            var valor = $('input:checkbox[name=porFecha]:checked').val();
            if (valor == "porFecha") {
                $("#cuentasExistentes").hide();
                $("#cuentasProveedor").fadeIn();
                $("#divBotonera").hide();
                $("#proveedores").fadeIn();
            } else {
                $("#cuentasProveedor").hide();
                $("#cuentasExistentes").fadeIn();
                $("#divBotonera").fadeIn();
                $("#proveedores").hide();
                $("#sinDatos").hide();
            }
        });
    });
</script>

