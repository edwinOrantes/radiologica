<style>
    body{
        
        background-image: url('public/img/test3_bg.jpg') ;
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    .cabecera{
        width: 100%;
    }
    .img_cabecera{
        padding-top: -20px;
        width: 25%;
        width: 225px;
        float: left;
    }
    .title_cabecera{
        float: right;
        line-height: 5px;
        text-align: center;
        width: 60%;
    }

    .subtitle_cabecera{
        clear: both;
        width: 100%;
    }

    .subtitle_cabecera h5{
        font-size: 11px;
        margin-top: 15px;
        text-align: center;
    }

    .paciente{
        width: 100%;
        margin-top: -8px;;
    }

    .tabla_paciente{
        font-size: 10px;
        margin-bottom: 25px;
        width: 100%;

    }

    .tabla_num_recibo{
        font-size: 12px;
        margin-bottom: 25px;
        width: 100%;

    }

    .detalle{
        width: 100%;
        padding-top: -25px;
    }

    .tabla_detalle{
        font-size: 11px;
        margin-bottom: 25px;
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;

    }

    .tabla_detalle thead tr th, tbody tr td{
        border-width: 1px;
        border-style: solid;
        border-color: #000;
    }

    .pie{
        width: 100%;
     }
    .pie_izquierda{
        width: 68%;
        float: left;
    }
    .pie_derecha{
        float: right;
        text-align: left;
        width: 32%;
        line-height: 12px;
        
    }

    .pie_abajo{
        clear: both;
    }
    
    .pie_abajo_detalle{
        font-size: 8px;
        word-spacing: 3px;
    }
    
    .numeracion{
        font-size: 12x;
        width: 100%;
    }
    .numeracion_izquierda{
        width: 40%;
        width: 115px;
        float: left;
    }
    .numeracion_derecha{
        float: right;
        line-height: 5px;
        text-align: right;
        width: 60%;
    }

    .letraMayuscula{
        text-transform: uppercase;
    }
</style>
<?php

    // Total de la hoja de cobro.
        $totalExternos = 0;
        $mm = 0;
        $med = 0;
        $serm = 0;
        $oserm = 0;
        $prom = 0;
        foreach ($medicamentosHoja as $medicamento) {

            // Suma medicamentos y materiales medicos
            switch ($medicamento->tipoMedicamento) {
                case 'Medicamentos':
                    $med += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Materiales médicos':
                    $mm += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Servicios':
                    $serm += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Promoción':
                    $prom += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Otros servicios':
                    $oserm += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
            }
        }

        foreach ($externosHoja as $externo) {
            $totalExternos += ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
        }
    // Fin total hoja de cobro.
    
?>

<div class="detalle">
    <table class="tabla_detalle">
        <thead>
            
                <tr style="color: #fff; background-color: rgba(11, 153, 208, 1);">
                    <th style="color: #ffffff; padding: 2px">CANTIDAD</th>
                    <th style="color: #ffffff; padding: 2px">DESCRIPCION</th>
                    <th style="color: #ffffff; padding: 2px">PRECIO</th>
                    <th style="color: #ffffff; padding: 2px">TOTAL</th>
                </tr>
            
        </thead>
        <tbody>

            <?php
                if($mm > 0){
            ?>
                <tr>
                    <td style="padding: 2px; text-align: center">1</td>
                    <td style="padding: 2px; text-align: center">Materiales Médicos</td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($mm, 2);?></td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($mm, 2);?></td>
                </tr>
            <?php } ?>


            <?php
                if($med > 0){
            ?>
                <tr>
                    <td style="padding: 2px; text-align: center">1</td>
                    <td style="padding: 2px; text-align: center">Medicamentos</td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($med, 2);?></td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($med, 2);?></td>
                </tr>
            <?php } ?>

            <?php
                if($serm > 0){
            ?>
                <tr>
                    <td style="padding: 2px; text-align: center">1</td>
                    <td style="padding: 2px; text-align: center">Servicios</td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($serm, 2);?></td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($serm, 2);?></td>
                </tr>
            <?php } ?>

            <?php
                if($oserm > 0){
            ?>
                <tr>
                    <td style="padding: 2px; text-align: center">1</td>
                    <td style="padding: 2px; text-align: center">Otros Servicios</td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($oserm, 2);?></td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($oserm, 2);?></td>
                </tr>
            <?php } ?>

            <?php
                if($prom > 0){
            ?>
                <tr>
                    <td style="padding: 2px; text-align: center">1</td>
                    <td style="padding: 2px; text-align: center">Promoción</td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($prom, 2);?></td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($prom, 2);?></td>
                </tr>
            <?php } ?>

            <?php
                if($totalExternos > 0){
            ?>
                <tr>
                    <td style="padding: 2px; text-align: center">1</td>
                    <td style="padding: 2px; text-align: center"> Cobros Externos</td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($totalExternos, 2);?></td>
                    <td style="padding: 2px; text-align: center">$ <?php echo number_format($totalExternos, 2);?></td>
                </tr>
            <?php } ?>
            
            <?php
                if($paciente->descuentoHoja != null && $paciente->descuentoHoja != 0){
            ?>
                <tr>
                    <td colspan="3" style="padding: 2px; text-align: center"><strong>Descuento: </strong></td>
                    <td style="padding: 2px; text-align: center"><strong>$ <?php echo number_format($paciente->descuentoHoja, 2);?></strong></td>
                </tr>
            <?php } ?>
            
            <!-- Para el total global -->
            <?php
                $totalRecibo = 0;
                if($paciente->descuentoHoja != null){
                    $totalRecibo = ($totalExternos + $prom + $mm + $med + $serm + $oserm - $paciente->descuentoHoja);
                }else{
                    $totalRecibo = $totalExternos + $prom + $mm + $med + $serm + $oserm;
                }
            ?>
            <tr>
                    <td colspan="3" style="padding: 2px; text-align: center"><strong>Total: </strong></td>
                    <td style="padding: 2px; text-align: center"><strong>$ <?php echo number_format($totalRecibo, 2);?></strong></td>
            </tr>
            
       </tbody>
    </table>

    <table class="tabla_num_recibo" style="padding-top: 25px; font-family: Times New Roman;">
        <tr>
            <td style="text-align: center;">
                <p>_______________________________________</p>
                <h5><strong>AUTORIZADO POR</strong></h5>
            </td>
            <td style="text-align: center;">
                <p>_______________________________________</p>
                <h5><strong>RECIBIDO POR</strong></h5>
            </td>
        </tr>
    </table>
</div>