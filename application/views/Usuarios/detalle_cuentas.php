<?php
    $totalGlobal = 0;
    
    foreach ($cuentas as $cuenta) {
        $totalGlobal += $cuenta->totalCuenta;
    }
?>
<div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--<nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-file "></i> Hoja</a></li>
                        <li class="breadcrumb-item"><a href="#">Detalle hoja</a></li>
                    </ol>
                </nav>-->

                <div class="ms-panel">

                    <div class="ms-panel-header">
                        <div class="row text-center">
                                <div class="col-md-6 text-left">
                                    <h6>Movimientos de cuentas durante el mes de <?php echo $mes; ?> </h6>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h6>$ <?php echo number_format($totalGlobal, 2); ?> </h6>
                                </div>
                                    
                        </div>
                    </div>

                    <div class="ms-panel-body"> 
                        <table class="table table-bordered thead-primary tablaPlus">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Cuenta</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                     $clase = "";
                                     $contador = 0;
                                     foreach ($cuentas as $cuenta) {
                                        $contador++;
                                        $idCuenta ='"'.$cuenta->idCuenta.'"';
                                        if($cuenta->totalCuenta > 0 && $cuenta->totalCuenta < 100){
                                             $clase = "card-gradient-info";
                                        }
                                        if($cuenta->totalCuenta >= 100 && $cuenta->totalCuenta < 1000){
                                             $clase = "card-gradient-secondary";
                                        }
                                        if($cuenta->totalCuenta >= 1000){
                                            $clase = "card-gradient-success";
                                        }
                                        $display = 'style="display: none;"';
                                        if($cuenta->pagoGasto != 1){
                                            $totalGlobal += $cuenta->totalCuenta;
                                            $display = 'style="display: inline;"';
                                        }
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $contador; ?></td>
                                        <td scope="row" class="text-uppercase text-center"><?php echo $cuenta->nombreCuenta; ?></td>
                                        <td class="text-center">$ <?php echo $cuenta->totalCuenta?></td>
                                        <td class="text-center">
                                            <a href="#verDetalle" class="text-primary" data-toggle="modal" onclick='mostrarDetalle(<?php echo $idCuenta;?>)'>
                                                <i class="fa fa-file"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>                 
                        
                        <!-- <div class="row">

                            <?php
                                $clase = "";
                                foreach ($cuentas as $cuenta) {

                                    $idCuenta ='"'.$cuenta->idCuenta.'"';

                                    if($cuenta->totalCuenta > 0 && $cuenta->totalCuenta < 100){
                                        $clase = "card-gradient-info";
                                    }
                                    if($cuenta->totalCuenta >= 100 && $cuenta->totalCuenta < 1000){
                                        $clase = "card-gradient-secondary";
                                    }
                                    if($cuenta->totalCuenta >= 1000){
                                        $clase = "card-gradient-success";
                                    }
                                    
                                    $display = 'style="display: none;"';

                                    if($cuenta->pagoGasto != 1){
                                        $totalGlobal += $cuenta->totalCuenta;
                                        $display = 'style="display: inline;"';
                                        /* if(){

                                        } */
                                    }
                                    /* if($cuenta->pagoGasto == 1){
                                        switch ($cuenta->idCuentaGasto) {
                                            case '11':
                                                $totalGlobal += $cuenta->totalCuenta;
                                                $display = 'style="display: inline;"';
                                                break;
                                            case '22':
                                                $totalGlobal += $cuenta->totalCuenta;
                                                $display = 'style="display: inline;"';
                                                break;
                                            case '46':
                                                $totalGlobal += $cuenta->totalCuenta;
                                                $display = 'style="display: inline;"';
                                                break;
                                            case '83':
                                                $totalGlobal += $cuenta->totalCuenta;
                                                $display = 'style="display: inline;"';
                                                break;
                                            case '93':
                                                $totalGlobal += $cuenta->totalCuenta;
                                                $display = 'style="display: inline;"';
                                                break;
                                            
                                            default:
                                                $display = 'style="display: none;"';
                                                break;
                                        }
                                    } */
                            ?>

                                <div class="col-xl-3 col-md-3" <?php echo $display; ?>>
                                    <div class="ms-card <?php echo $clase; ?> ms-widget ms-infographics-widget">
                                        <div class="ms-card-body media">
                                            <div class="media-body">
                                                <a href="#verDetalle" data-toggle="modal" onclick='mostrarDetalle(<?php echo $idCuenta;?>)'>
                                                    <h6 style="font-size: 12px;"><?php echo $cuenta->nombreCuenta; ?></h6>
                                                    <p class="ms-card-change">$ <?php echo $cuenta->totalCuenta; ?></p>
                                                </a>
                                            </div>
                                        </div>
                                        <i class="flaticon-stats"></i>
                                    </div>
                                </div>
                            
                            <?php
                                }
                            ?>

                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal para ver movimiento de el gasto -->
    <div class="modal fade" id="verDetalle" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Detalle de movimientos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body" id="detalleMovimientos">
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin modal para ver movimiento de el gasto -->

<script>
    function mostrarDetalle(id){
        var data = {
            idCuenta: id
        }
        $.ajax({
            url: "../../buscar_detalle_gasto",
            type: "post",
            data: data,
            success:function(respuesta){
                var registro = eval(respuesta);
                total = 0;
                var html = "";
                html += '<table id="" class="table table-striped thead-primary w-100 tablaPlus">';
                html += '<thead>';
                html += '    <tr>';
                html += '        <th class="text-center" scope="col">#</th>';
                html += '        <th class="text-center" scope="col">Recibio</th>';
                html += '        <th class="text-center" scope="col">Monto</th>';
                html += '        <th class="text-center" scope="col">Fecha</th>';
                html += '        <th class="text-center" scope="col">Efectuo</th>';
                html += '    </tr>';
                html += '</thead>';
                html += '<tbody>';

                if (registro.length > 0){
                    total = 0;
                    for (let i = 0; i < registro.length; i++) {
                        // console.log(registro[i]["idGasto"]);
                        html += '<tr>';
                        html += '    <td class="text-center">'+(i+1)+'</td>';
                        html += '    <td class="text-center">'+registro[i]["entregadoGasto"]+'</td>';
                        html += '    <td class="text-center">$ '+registro[i]["montoGasto"]+'</td>';
                        html += '    <td class="text-center">'+registro[i]["fechaGasto"]+'</td>';
                        html += '    <td class="text-center">'+registro[i]["efectuoGasto"]+'</td>';
                        html += '</tr>';
                        total += parseFloat(registro[i]["montoGasto"]);
                    }
                    html += '<tr>';
                        html += '    <td class="text-center"></td>';
                        html += '    <td class="text-center"> <strong>TOTAL</strong> </td>';
                        html += '    <td class="text-center"><strong>$ '+total.toFixed(2)+'</strong></td>';
                        html += '    <td class="text-center"></td>';
                        html += '    <td class="text-center"></td>';
                        html += '</tr>';
                    html += '    </tbody>';
                    html += '</table>';
                    $("#detalleMovimientos").html("");
                    $("#detalleMovimientos").append(html);
                }
            }
        });
    }
</script>










