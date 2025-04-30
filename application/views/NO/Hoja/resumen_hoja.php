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
        font-size: 11px;
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
    $totalMedicamentosGlobal = 0;
    $m = 0;
    $mm = 0;
    $s = 0;
    $os = 0;
    foreach ($medicamentosHoja as $medicamento) {

        switch ($medicamento->tipoMedicamento) {
            case 'Medicamentos':
                $m = 1;
                break;
            case 'Materiales médicos':
                $mm = 1;
                break;
            case 'Servicios':
                $s = 1;
                break;
            case 'Otros servicios':
                $os = 1;
                break;
        }
    }
?>

<div class="detalle">
    <table class="tabla_detalle">
        <thead>
            <tr style="background-color: rgba(11, 153, 208, 1);">
                <th style="color: #ffffff">NOMBRE</th>
                <th style="color: #ffffff">CANTIDAD</th>
                <th style="color: #ffffff">PRECIO</th>
                <th style="color: #ffffff">TOTAL</th>
                
            </tr>
        </thead>
        <tbody>
        <!-- Medicamentos -->
            <?php
                if($m == 1){
            ?>
                <tr style="border: 1px solid #000; background-color: rgba(11, 153, 208, 1);">
                    <th colspan="4" style="padding-left: 10x; color: #ffffff; text-align: center"> MEDICAMENTOS</th>
                    <!-- <th colspan="3" style="padding: 5x; color: #ffffff"></th> -->
                </tr>

                <?php
                $totalMedicamentos = 0;
                    foreach ($medicamentosHoja as $medicamento) {
                        if($medicamento->tipoMedicamento == "Medicamentos"){
                            $totalMedicamentos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
                ?>
                    <tr>
                        <td style="text-align: left; padding-left: 10px"><?php echo $medicamento->nombreMedicamento; ?></td>
                        <td style="text-align: center;"><?php echo $medicamento->cantidadInsumo; ?></td>
                        <td style="text-align: center;">$ <?php echo $medicamento->precioInsumo; ?></td>
                        <td style="text-align: center;">$ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo ), 2); ?></td>
                    </tr>
                <?php
                    }}
                    $totalMedicamentosGlobal += $totalMedicamentos;  // Sumando al total global
                ?>

                <tr>
                    <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Total medicamentos </strong></td>
                    <td style="text-align: center;">$ <?php echo number_format($totalMedicamentos, 2); ?></td>
                </tr>
            <?php } ?>
        <!-- Fin medicamentos -->
        
        <!-- Materiales medicos -->
            <?php
                if($mm == 1){
            ?>
            <tr style="border: 1px solid #000; background-color: rgba(11, 153, 208, 1);">
                <th colspan="4" style="padding-left: 10x; color: #ffffff; text-align: center">MATERIALES MEDICOS</th>
                <!-- <th colspan="3" style="padding: 5x; color: #ffffff; text-align: center"></th> -->
            </tr>

            <?php
                $totalMM = 0;
                foreach ($medicamentosHoja as $medicamento) {
                    if($medicamento->tipoMedicamento == "Materiales médicos"){
                        $totalMM += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
            ?>
                <tr>
                    <td style="text-align: left; padding-left: 10x;"><?php echo $medicamento->nombreMedicamento; ?></td>
                    <td style="text-align: center;"><?php echo $medicamento->cantidadInsumo; ?></td>
                    <td style="text-align: center;">$ <?php echo $medicamento->precioInsumo; ?></td>
                    <td style="text-align: center;">$ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo ), 2); ?></td>
                </tr>
            <?php
                }}
                $totalMedicamentosGlobal += $totalMM; // Sumando al total global
            ?>
            <tr>
                <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Total materiales médicos </strong></td>
                <td style="text-align: center;">$ <?php echo number_format($totalMM, 2); ?></td>
            </tr>
            <?php
              }
            ?>
        <!-- Fin materiales medicos -->
        
        <!-- Servicios -->
            <?php
                if($s == 1){
            ?>
            <tr style="border: 1px solid #000; background-color: rgba(11, 153, 208, 1);">
                <th colspan="4" style="padding-left: 10x; color: #ffffff; text-align: center">SERVICIOS</th>
                <!-- <th colspan="3" style="padding: 5x;color: #ffffff; text-align: center"></th> -->
            </tr>

            <?php
            $totalServicios = 0;
                foreach ($medicamentosHoja as $medicamento) {
                    if($medicamento->tipoMedicamento == "Servicios"){
                        $totalServicios += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
            ?>
                <tr>
                    <td style="text-align: left; padding-left: 10x;"><?php echo $medicamento->nombreMedicamento; ?></td>
                    <td style="text-align: center;"><?php echo $medicamento->cantidadInsumo; ?></td>
                    <td style="text-align: center;">$ <?php echo $medicamento->precioInsumo; ?></td>
                    <td style="text-align: center;">$ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo ), 2); ?></td>
                </tr>
            <?php
                }}
                $totalMedicamentosGlobal += $totalServicios; // Sumando al total global
            ?>

            <tr>
                <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Total servicios </strong></td>
                <td style="text-align: center;">$ <?php echo number_format($totalServicios, 2); ?></td>
            </tr>
            <?php
                }
            ?>
        <!-- Fin servicios -->

        <!-- Otros servicios -->
            <?php
                if($os == 1){
            ?>
            <tr style="border: 1px solid #000; background-color: rgba(11, 153, 208, 1);">
                <th colspan="4" style="padding-left: 10x; color: #ffffff; text-align: center ">OTROS SERVICIOS</th>
                <!-- <th colspan="3" style="padding: 5x; color: #ffffff; text-align: center "></th> -->
            </tr>
            
            <?php
                $totalOS = 0;
                foreach ($medicamentosHoja as $medicamento) {
                    if($medicamento->tipoMedicamento == "Otros servicios"){
                        $totalOS += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
            ?>
                <tr>
                    <td style="text-align: left; padding-left: 10x;"><?php echo $medicamento->nombreMedicamento; ?></td>
                    <td style="text-align: center;"><?php echo $medicamento->cantidadInsumo; ?></td>
                    <td style="text-align: center;">$ <?php echo $medicamento->precioInsumo; ?></td>
                    <td style="text-align: center;">$ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo ), 2); ?></td>
                </tr>
            <?php
                }}
                $totalMedicamentosGlobal += $totalOS;
            ?>

            <tr>
                <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Total otros servicios</strong></td>
                <td style="text-align: center;">$ <?php echo number_format($totalOS, 2); ?></td>
            </tr>
            <?php
                }
            ?>
        <!-- Total otros servicios -->

            <tr>
                <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Total medicamentos e insumos </strong></td>
                <td style="text-align: center;">$ <?php echo number_format($totalMedicamentosGlobal, 2); ?></td>
            </tr>

        <!-- Descuento -->
                <?php

                    if(isset($paciente->descuentoHoja)){
                        if($paciente->descuentoHoja != null && $paciente->descuentoHoja != 0){
                            echo '<tr>';
                            echo '    <td colspan="3" style="text-align: right; padding-right: 10px;"><strong>Descuento</strong></td>';
                            echo '    <td style="text-align: center;">$ '.number_format($descuentoTotal, 2).'</td>';
                            echo '</tr>';
                        }
                    }
                ?>
                
        <!-- Fin descuento -->
        
       </tbody>
    </table>

    <!-- Abonos -->
        <?php
            if(isset($abonos)){
                $flag =0;
                $totalAbonos = 0;
                echo '<h5>Detalle de abonos</h5>';
                echo '<table class="tabla_detalle">
                        <thead>
                            <tr style="background-color: rgba(11, 153, 208, 1);">
                                <th>#</th>
                                <th>FECHA</th>
                                <th>MONTO</th>
                            </tr>
                        </thead>
                        <tbody>';
                foreach ($abonos as $row) {
                    $flag++;
                    $totalAbonos += $row->montoAbono;
                    echo '<tr>
                            <td style="text-align: center">'.$flag.'</td>
                            <td style="text-align: center">'.$row->fechaAbono.'</td>
                            <td style="text-align: center">$ '.number_format($row->montoAbono, 2).'</td>
                        </tr>';
                }
                echo '<tr>
                        <td style="text-align: center" colspan="2"><strong>TOTAL</strong></td>
                        <td style="text-align: center">$ '.number_format($totalAbonos, 2).'</td>
                    </tr>';
                echo '</tbody>
                </table>';
            }else{
                echo "";
            }
        ?>
    <!-- Abonos -->

</div>