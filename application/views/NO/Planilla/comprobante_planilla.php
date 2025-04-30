<style>

    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    #cabecera {
        text-align: left;
        width: 80%;
        margin: auto;
        }

    #lateral {
        width: 35%;  /* Este será el ancho que tendrá tu columna */
        float:left; /* Aquí determinas de lado quieres quede esta "columna" */
        padding-top: -15px;
        }

    #principal {
        width: 49%;
        float: right;
        }
        
    /* Para limpiar los floats */
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
        }

    .proveedor, .medicamentos{
        margin-top: 10px;
    }

    .proveedor .detalle table, .medicamentos .detalle table{
        font-size: 12px;
        margin: auto;
        width: 100%;
    }
    .proveedor .detalle table tr td{
        padding: 5px;
        text-align: left;
        font-size: 11px;
    }

    .medicamentos{
        text-align: center;
    }

    .medicamentos .detalle table tr td{
        padding: 5px !important;
        font-size: 10px;
        text-align: center;
        border-width: 0.1px;
        border-style: solid;
        border-color: #000;
    }
    .tabla_num_recibo{
        font-size: 12px;
        margin-top: 25px;
        width: 100%;

    }
    
    .recibo{
        height: 40%;
    }
    /* .detalle table tr:nth-child(even){
        background: rgba(11, 153, 208, 0.1);
        color: #FFFFFF;
    } */

</style>
<?php
    $flag = 0;
        if($pivote == 1){
            foreach ($empleados as $row) {
            
?>

<div class="recibo" style="padding-top: -60px">
    <div id="cabecera" class="clearfix">
    
        <div id="lateral">
            <p><img src="<?php echo base_url() ?>public/img/logo.png" alt="Logo hospital Orellana" width="225"></p>
        </div>
    
        <div id="principal">
            <table style="text-align: center;">
                <tr>
                    <td><strong>RECIBI DE: </strong>HOSPITAL ORELLANA, USULUTAN</td>
                </tr>
                <tr>
                    <td><strong>Comprobante de pago</strong></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="contenedor">
            <div class="medicamentos">
                <p style="margin-top: 0px; text-align: left; text-transform: uppercase;"><strong>Detalle: </strong><?php echo $row->descripcionPlanilla; ?>. Por servicios prestados como colaborador en el area de: <?php echo $row->nombreArea; ?></p>
                    <div class="detalle">
                        <table cellspacing="0" cellpadding="5">
                            <!-- <thead></thead> -->
                            <tbody>
                                <tr>
                                    <td><strong>Empleado</strong></td>
                                    <td><?php echo $row->nombreEmpleado; ?></td>
                                    <td><strong>Salario base quincenal</strong></td>
                                    <td>$<?php echo number_format(($row->salarioEmpleado/2), 2); ?></td>
                                </tr>
                                <tr style="background: rgba(11, 153, 208, 0.1);">
                                    <td colspan="4"><strong>Detalle</strong></td>
                                </tr>
                                <?php
                                
                                    if($row->bonoEmpleado > 0){
                                        echo '<tr>
                                                <td><strong>Bono</strong></td>
                                                <td>$'.number_format(($row->bonoEmpleado/2), 2).' (+)'.'</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
    
                                    if($row->totalVacaciones > 0){
                                        echo '<tr>
                                                <td><strong>Vacaciones</strong></td>
                                                <td>$'.number_format($row->totalVacaciones, 2).'(+)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
    
                                    if($row->totalHorasExtras > 0){
                                        echo '<tr>
                                                <td><strong>Horas extras</strong></td>
                                                <td>$'.number_format($row->totalHorasExtras, 2).'(+)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
    
                                    if($row->otrosDetallePlanilla > 0){
                                        echo '<tr>
                                                <td><strong>Otros</strong></td>
                                                <td>$'.number_format($row->otrosDetallePlanilla, 2).'(+)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
                                ?>
                                
                                
                                <tr>
                                    <td><strong>ISSS</strong></td>
                                    <td></td>
                                    <td>$<?php echo number_format($row->isssDetallePlanilla, 2)."(-)"; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>AFP</strong></td>
                                    <td></td>
                                    <td>$<?php echo number_format($row->afpDetallePlanilla, 2)."(-)"; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>Renta</strong></td>
                                    <td></td>
                                    <td>$<?php echo number_format($row->rentaDetallePlanilla, 2)."(-)"; ?></td>
                                    <td></td>
                                </tr>
    
                                <?php
                                    if($row->descuentosPlanilla > 0){
                                        echo '<tr>
                                                <td><strong>Otros descuentos</strong><span style="font-size: 9px;">'.$row->detalleDescuentos.'</span></td>
                                                <td></td>
                                                <td>$'.number_format($row->descuentosPlanilla, 2).'(-)</td>
                                                <td></td>
                                            </tr>';
                                    }
                                ?>
    
                                <tr>
                                    <td colspan='3'><strong>Liquido a recibir</strong></td>
                                    <td>$<?php echo number_format($row->liquidoDetallePlanilla, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
    </div>
    <table class="tabla_num_recibo" style="padding-top: 50px; padding-bottom: 50px; font-family: Times New Roman;">
        <tr>
            <td style="text-align: center;">
                <p>_______________________________________</p>
                <h5><strong>RECIBIDO</strong></h5>
            </td>
        </tr>
    </table>
</div>
<hr>
<div class="recibo" style="padding-top: 75px">
    <div id="cabecera" class="clearfix">
    
        <div id="lateral">
            <p><img src="<?php echo base_url() ?>public/img/logo.png" alt="Logo hospital Orellana" width="225"></p>
        </div>
    
        <div id="principal">
            <table style="text-align: center;">
                <tr>
                    <td><strong>RECIBI DE: </strong>HOSPITAL ORELLANA, USULUTAN</td>
                </tr>
                <tr>
                    <td><strong>Comprobante de pago</strong></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="contenedor">
            <div class="medicamentos">
                <p style="margin-top: 0px; text-align: left; text-transform: uppercase;"><strong>Detalle: </strong><?php echo $row->descripcionPlanilla; ?> Por servicios prestados como colaborador en el area de: <?php echo $row->nombreArea; ?></p>
                    <div class="detalle">
                        <table cellspacing="0" cellpadding="5">
                            <!-- <thead></thead> -->
                            <tbody>
                                <tr>
                                    <td><strong>Empleado</strong></td>
                                    <td><?php echo $row->nombreEmpleado; ?></td>
                                    <td><strong>Salario base quincenal</strong></td>
                                    <td>$<?php echo number_format(($row->salarioEmpleado/2), 2); ?></td>
                                </tr>
                                <tr style="background: rgba(11, 153, 208, 0.1);">
                                    <td colspan="4"><strong>Detalle</strong></td>
                                </tr>
                                <?php
                                
                                    if($row->bonoEmpleado > 0){
                                        echo '<tr>
                                                <td><strong>Bono</strong></td>
                                                <td>$'.number_format(($row->bonoEmpleado/2), 2).' (+)'.'</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
    
                                    if($row->totalVacaciones > 0){
                                        echo '<tr>
                                                <td><strong>Vacaciones</strong></td>
                                                <td>$'.number_format($row->totalVacaciones, 2).'(+)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
    
                                    if($row->totalHorasExtras > 0){
                                        echo '<tr>
                                                <td><strong>Horas extras</strong></td>
                                                <td>$'.number_format($row->totalHorasExtras, 2).'(+)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
    
                                    if($row->otrosDetallePlanilla > 0){
                                        echo '<tr>
                                                <td><strong>Otros</strong></td>
                                                <td>$'.number_format($row->otrosDetallePlanilla, 2).'(+)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>';
                                    }
                                ?>
                                
                                
                                <tr>
                                    <td><strong>ISSS</strong></td>
                                    <td></td>
                                    <td>$<?php echo number_format($row->isssDetallePlanilla, 2)."(-)"; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>AFP</strong></td>
                                    <td></td>
                                    <td>$<?php echo number_format($row->afpDetallePlanilla, 2)."(-)"; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>Renta</strong></td>
                                    <td></td>
                                    <td>$<?php echo number_format($row->rentaDetallePlanilla, 2)."(-)"; ?></td>
                                    <td></td>
                                </tr>
    
                                <?php
                                    if($row->descuentosPlanilla > 0){
                                        echo '<tr>
                                                <td><strong>Otros descuentos</strong><span style="font-size: 9px;">'.$row->detalleDescuentos.'</span></td>
                                                <td></td>
                                                <td>$'.number_format($row->descuentosPlanilla, 2).'(-)</td>
                                                <td></td>
                                            </tr>';
                                    }
                                ?>
    
                                <tr>
                                    <td colspan='3'><strong>Liquido a recibir</strong></td>
                                    <td>$<?php echo number_format($row->liquidoDetallePlanilla, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
    </div>
    <table class="tabla_num_recibo" style="padding-top: 50px; font-family: Times New Roman;">
        <tr>
            <td style="text-align: center;">
                <p>_______________________________________</p>
                <h5><strong>RECIBIDO</strong></h5>
            </td>
        </tr>
    </table>
</div>




<?php 
        $flag++;
        }
    } 
?>



