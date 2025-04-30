<!-- scripts para avisos -->
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

<!-- Horas para tolerancia a la glucosa -->
    <?php
        $hora= date('h:i A'); 
        $primera = strtotime ( '-1 hour' , strtotime ($hora)); 
        $segunda = strtotime ( '-2 hour' , strtotime ($hora)); 
        $tercera = strtotime ( '-3 hour' , strtotime ($hora)); 
        $primera = date ( 'h:i A' , $primera); 
        $segunda = date ( 'h:i A' , $segunda); 
        $tercera = date ( 'h:i A' , $tercera);

        $clase = "";
        $totalCuentasPendientes = sizeof($cuentasPendientes);
        if($totalCuentasPendientes > 0){
            $clase = "text-danger bold";
        }

    ?>
<!-- Fin horas para tolerancia a la glucosa -->


<div class="">
    <div class="row m-3">
        <div class="col-md-12 mt-1">
            <div class="alert-primary table-responsive bordeContenedor pt-3 pl-3">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Empleado:</strong></td>
                        <td><?php echo $empleado->nombreEmpleado; ?></td>
                        <td><strong>Cargo:</strong></td>
                        <td><?php echo $empleado->cargoEmpleado; ?></td>
                        <td><strong>Teléfono:</strong></td>
                        <td><?php echo $empleado->telefonoEmpleado; ?></td>
                        <!-- <td class="text-right"><a href="#agregarDescuento" class="btn btn-success btn-sm">Agregar <i class="fa fa-plus"></i></a></td> -->
                    </tr>
        
                    <tr>
                        <td><strong>DUI:</strong></td>
                        <td><?php echo $empleado->duiEmpleado; ?></td>
                        <td><strong>Correo:</strong></td>
                        <td><?php echo $empleado->correoEmpleado; ?></td>
                        <td><strong>Dirección:</strong></td>
                        <td><?php echo $empleado->direccionEmpleado; ?></td>
                    </tr>
                    
                    <tr>
                        <td><a href="#agregarDescuento" class="btn btn-success btn-sm" data-toggle="modal">Descuentos</a></td>
                        <td colspan="6"> </td> 
                    </tr>
        
                </table>

                <div class="row">
                    <?php
                        if(sizeof($descargos) > 0){
                            foreach ($descargos as $row) {
                                // if($row->estadoDescuento == 1){
                                    echo '<div class="col-lg-2 col-md-2 col-sm-2 text-center">
                                            <div class="ms-card card-info">
                                                <div class="ms-card-body">
                                                    <a href="#historialDescuentos" data-toggle="modal" class="contenedorDescuento">
                                                        <h6>'.$row->nombreDP.'</h6>
                                                        <p>Total: $'.number_format($row->montoEmDes, 2).'</p>
                                                        <p>Abonado: $'.number_format($row->totalAbonado, 2).'</p>
                                                        <p>Pendiente: $'.number_format(($row->montoEmDes -$row->totalAbonado), 2).'</p>
                                                        <input type="hidden" class="pivoteDescuento" value="'.$row->idEmDes.'">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>';
                                // }
                            }
                        }else{
                            echo '';
                        }
                    ?>
                    
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="ms-panel ms-panel-fh">
                <div class="ms-panel-header"> </div>
                <div class="ms-panel-body">
                    <ul class="nav nav-tabs tabs-bordered d-flex nav-justified mb-4" role="tablist">
                        <li role="presentation"><a href="#tabSalarios" aria-controls="tabSalarios" class="active show" role="tab" data-toggle="tab" aria-selected="true"> Salarios recibidos </a></li>
                        <li role="presentation"><a href="#tabCuentasPendientes" aria-controls="tabCuentasPendientes" role="tab" data-toggle="tab" class="<?php echo $clase; ?>" aria-selected="false"> Cuentas pendientes </a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active show" id="tabSalarios">
                            <?php
                                if(sizeof($salarios) > 0){
                            ?>  
                                <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $index = 0;
                                            foreach ($salarios as $row) {
                                                $index++;
                                                echo '<tr>
                                                        <td class="text-center">'.$index.'</td>
                                                        <td class="text-center">'.$row->fechaPlanilla.'</td>
                                                        <td class="text-center">'.$row->descripcionPlanilla.'</td>
                                                        <td class="text-center">$'.number_format($row->liquidoDetallePlanilla, 2).'</td>
                                                    </tr>';
                                            }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            <?php
                                }else{
                                    echo '<div class="alert-danger p-3 text-center bold">No hay datos que mostrar...</div>';
                                }
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tabCuentasPendientes">
                            <?php
                                if(sizeof($cuentasPendientes) > 0){
                            ?>  
                                <h6>Salarios:</h6>
                                <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Médico</th>
                                            <th class="text-center">Habitación</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $index = 0;
                                            foreach ($cuentasPendientes as $row) {
                                                $index++;
                                                echo '<tr>
                                                        <td class="text-center">'.$index.'</td>
                                                        <td class="text-center">'.$row->fechaHoja.'</td>
                                                        <td class="text-center">'.$row->nombreMedico.'</td>
                                                        <td class="text-center">'.$row->numeroHabitacion.'</td>
                                                        <td class="text-center">$'.number_format(($row->interno + $row->externo), 2).'</td>
                                                        <td class="text-center"><a href="'.base_url().'Hoja/detalle_hoja/'.$row->idHoja.'/" target="blank"><i class="fa fa-file text-primary"></i></a></td>
                                                    </tr>';
                                            }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            <?php
                                }else{
                                    echo '<div class="alert-danger p-3 text-center bold">No hay datos que mostrar...</div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal agregar descuentos-->
    <div class="modal fade" id="agregarDescuento" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white text-center"></i> Seleccionar datos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form action="">

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Cuentas de descuento</strong></label>
                                            <div class="input-group">
                                                <select name="cuentaDescargo" id="cuentaDescargo" class="form-control" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                        foreach ($cuentasDescargos as $row) {
                                                            echo '<option value="'.$row->idDP.'">'.$row->nombreDP.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione el descuento a agregar.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Monto total descontar</strong></label>
                                            <div class="input-group">
                                                <input name="txtMontoDescuento" id="txtMontoDescuento" class="form-control" placeholder="Ingrese el total a descontar" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el total a descontar.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Cuota</strong></label>
                                            <div class="input-group">
                                                <input name="txtCuotaDescuento" id="txtCuotaDescuento" class="form-control" placeholder="Ingrese la cuota quincenal" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el total a descontar.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 text-center" id="">
                                            <input type="hidden" class="form-control" value="<?php echo $empleado->idEmpleado;?>" id="txtIdEmpleado" name="txtIdEmpleado">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="button" id="btnAgregarDescuento"> Guardar <i class="fa fa-save"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal agregar descuentos-->

<!-- Modal agregar descuentos-->
    <div class="modal fade" id="historialDescuentos" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white text-center"></i> Historial de descuentos </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="">
                                    <table class="table table-bordered thead-primary">
                                        <thead class="text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha</th>
                                                <th>Descripción</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody id="contenedorHistorial">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal agregar descuentos-->
<script>
    $(document).on("click", '#btnAgregarDescuento', function(e) {
        // e.preventDefault();
        // var salario = $(this).closest("tr").find('.salarioBEmpleado').val();
        var cuenta = $('#cuentaDescargo').val();
        if(cuenta != ""){
            var datos = {
                empleado: $('#txtIdEmpleado').val(),
                nombre : cuenta,
                monto: $('#txtMontoDescuento').val(),
                cuota: $('#txtCuotaDescuento').val(),
            }
            $.ajax({
                url: "../../guardar_descuento_empleado",
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
                            toastr.success('Datos agregados', 'Aviso!');
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
                            toastr.error('No se agregaron los datos...', 'Aviso!');
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
                        toastr.error('No se agregaron los datos', 'Aviso!');
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
                    toastr.error('No se agregaron los datos', 'Aviso!');
                }
            });
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
            toastr.error('Debes ingresar un nombre', 'Aviso!');
        }
                $('#cuentaDescargo').val("");
                $('#txtMontoDescuento').val("");
                $('#txtCuotaDescuento').val("");
            });

    $(document).on('click', '.close', function(event) {
            event.preventDefault();
            location.reload();
    });

    $(document).on("click", '.contenedorDescuento', function(e) {
        // e.preventDefault();
        var pivote = $(this).find('.pivoteDescuento').val();
        
        var datos = {
            pivote: $(this).find('.pivoteDescuento').val(),
        }
        $.ajax({
            url: "../../obtener_historial_descuentos",
            type: "POST",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                var html = "";
                var flag = 0;
                var total = 0;
                if (registro.length > 0){
                    for (let i = 0; i < registro.length; i++) {
                        flag++;
                        html += '<tr>';
                        html += '    <td>'+flag+'</td>';
                        html += '    <td scope="row">'+registro[i]["fecha"]+'</td>';
                        html += '    <td scope="row">'+registro[i]["descripcionPlanilla"]+'</td>';
                        html += '    <td scope="row">$'+registro[i]["montoAbono"]+'</td>';
                        html += '</tr>';
                        total += parseFloat(registro[i]["montoAbono"]);
                    }
                    html += '<tr>';
                    html += '    <td colspan="3" class="text-center"><strong>TOTAL</strong></td>';
                    html += '    <td scope="row">$'+parseFloat(total.toFixed(2))+'</td>';
                    html += '</tr>';
                    // console.log(html);
                    $("#contenedorHistorial").html(html);
                }else{
                    
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
                toastr.error('Ha ocurrido un error...', 'Aviso!');
            }
        });


        $('#cuentaDescargo').val("");
    });
</script>

