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
        padding-top: -25px;
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

    /* .proveedor, .medicamentos{
        margin-top: 10px;
    } */

    .proveedor .detalle table, .medicamentos .detalle table{
        font-size: 11px;
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
        padding: 2px !important;
        font-size: 10px;
        text-align: center;
        border-width: 1px;
        border-style: solid;
        border-color: #000;
    }

    .detalle table tr:nth-child(even){
        background: rgba(11, 153, 208, 0.1);
        color: #FFFFFF;
}

</style>

<div id="cabecera" class="clearfix">

    <div id="lateral">
        <p><img src="<?php echo base_url() ?>public/img/logo.jpg" alt="Logo hospital Orellana" width="225"></p>
    </div>

    <div id="principal">
        <table style="text-align: center;">
            <tr>
                <td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
            </tr>
            <tr>
                <td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
            </tr>
        </table>
    </div>
</div>
<div class="contenedor">
    <div class="medicamentos">
        <p style="margin-top: 0px;"><strong>REPORTE DE RX Y LABORATORIO</strong></p>
            <div class="detalle">
                <table cellspacing="0" cellpadding="5">
                    <thead>
                        <tr style="background-color: #007bff">
                            <td style="color: #fff; text-align: center; font-weight: bold">RECIBO</td>
                            <td style="color: #fff; text-align: center; font-weight: bold" colspan="8" >INGRESO</td>
                            <td style="color: #fff; text-align: center; font-weight: bold" colspan="8" >AMBULATORIO</td>
                            <td style="color: #fff; text-align: center; font-weight: bold" colspan="6" >TOTALES</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong></strong></td>
                            <td><strong>Total</strong></td>
                            <td><strong>Lab</strong></td>
                            <td><strong>RX</strong></td>
                            <td><strong>Ultras</strong></td>
                            <td><strong>Hemo.</strong></td>
                            <td><strong>Dietas</strong></td>
                            <td><strong>Refri.</strong></td>
                            <td><strong>Neto</strong></td>
                            <td><strong>Total</strong></td>
                            <td><strong>Lab</strong></td>
                            <td><strong>RX</strong></td>
                            <td><strong>Ultra</strong></td>
                            <td><strong>Hemo.</strong></td>
                            <td><strong>Dietas</strong></td>
                            <td><strong>Refri.</strong></td>
                            <td><strong>Neto</strong></td>
                            <td><strong>Lab</strong></td>
                            <td><strong>RX</strong></td>
                            <td><strong>Ultras</strong></td>
                            <td><strong>Hemo.</strong></td>
                            <td><strong>Dietas</strong></td>
                            <td><strong>Refri.</strong></td>
                        </tr>
                        <?php
                            $totalGlobalI = 0;
                            $totalGlobalA = 0;
                            $laboratorioGlobalI = 0;
                            $laboratorioGlobalA = 0;
                            $rxGlobalI = 0;
                            $rxGlobalA = 0;
                            $ultrasGlobalI = 0;
                            $ultrasGlobalA = 0;
                            $hemodialisisGlobalI = 0;
                            $hemodialisisGlobalA = 0;
                            $dietaGlobalI = 0;
                            $dietaGlobalA = 0;
                            $refrigeriosGlobalI = 0;
                            $refrigeriosGlobalA = 0;
                            foreach ($hojasCerradas as $hoja) {
                                if($hoja->anulada == 0 && $hoja->correlativoSalidaHoja > 0){
                        ?>
                            <tr>
                                <td><?php echo $hoja->correlativoSalidaHoja;?></td>
                                <?php
                                    $insumos = $this->Reportes_Model->insumosHoja($hoja->idHoja);
                                    $totalHoja = 0;
                                    foreach ($insumos as $insumo) {
                                        $totalHoja += ($insumo->cantidadInsumo * $insumo->precioInsumo);
                                    }
                                    if($hoja->descuentoHoja != null){
                                        $totalHoja = ($totalHoja - $hoja->descuentoHoja);
                                    }
                                ?>
                                 <!-- Sacando tatales de RX, Laboratorio y ultras -->
                                    <?php
                                        $examenes = $this->Reportes_Model->examenesHoja($hoja->idHoja);
                                        $totalLab = 0;
                                        $totalRX = 0;
                                        $totalUltras = 0;
                                        $totalHemodialisis = 0;
                                        $totalDietas = 0;
                                        $totalRefrigerios = 0;
                                        $totalNeto = 0;
                                        foreach ($examenes as $examen) {
                                            switch ($examen->pivoteMedicamento) {
                                                case '1':
                                                    $totalLab += ($examen->cantidadInsumo * $examen->precioInsumo);
                                                    break;
                                                case '2':
                                                    $totalRX += ($examen->cantidadInsumo * $examen->precioInsumo);
                                                    break;
                                                case '3':
                                                    $totalUltras += ($examen->cantidadInsumo * $examen->precioInsumo);
                                                    break;
                                                case '4':
                                                    $totalHemodialisis += ($examen->cantidadInsumo * $examen->precioInsumo);
                                                    break;
                                                case '5':
                                                    $totalDietas += ($examen->cantidadInsumo * $examen->precioInsumo);
                                                    break;
                                                case '6':
                                                    $totalRefrigerios += ($examen->cantidadInsumo * $examen->precioInsumo);
                                                    break;
                                            }
                                            $totalNeto += ($examen->cantidadInsumo * $examen->precioInsumo);
                                        }
                                        
                                    ?>
                                <!-- Fin totales -->
                                <?php
                                    if($hoja->tipoHoja == "Ingreso"){
                                        $totalGlobalI += $totalHoja;
                                        $laboratorioGlobalI += $totalLab;
                                        $rxGlobalI += $totalRX;
                                        $ultrasGlobalI += $totalUltras;
                                        $hemodialisisGlobalI += $totalHemodialisis;
                                        $dietaGlobalI += $totalDietas;
                                        $refrigeriosGlobalI += $totalRefrigerios;
                                ?>
                                <td>$<?php echo number_format($totalHoja, 2); ?></td>                                
                                <td>$<?php echo number_format($totalLab, 2); ?></td>
                                <td>$<?php echo number_format($totalRX, 2); ?></td>
                                <td>$<?php echo number_format($totalUltras, 2); ?></td>
                                <td>$<?php echo number_format($totalHemodialisis, 2); ?></td>
                                <td>$<?php echo number_format($totalDietas, 2); ?></td>
                                <td>$<?php echo number_format($totalRefrigerios, 2); ?></td>
                                <td>$<?php echo number_format(($totalHoja - $totalNeto), 2); ?></td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <?php
                                    }else{
                                        $totalGlobalA += $totalHoja;
                                        $laboratorioGlobalA += $totalLab;
                                        $rxGlobalA += $totalRX;
                                        $ultrasGlobalA += $totalUltras;
                                        $hemodialisisGlobalA += $totalHemodialisis;
                                        $dietaGlobalA += $totalDietas;
                                        $refrigeriosGlobalA += $totalRefrigerios;
                                ?>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>$<?php echo number_format($totalHoja, 2); ?></td>
                                <td>$<?php echo number_format($totalLab, 2); ?></td>
                                <td>$<?php echo number_format($totalRX, 2); ?></td>
                                <td>$<?php echo number_format($totalUltras, 2); ?></td>
                                <td>$<?php echo number_format($totalHemodialisis, 2); ?></td>
                                <td>$<?php echo number_format($totalDietas, 2); ?></td>
                                <td>$<?php echo number_format($totalRefrigerios, 2); ?></td>
                                <td>$<?php echo number_format(($totalHoja - $totalNeto), 2); ?></td>
                                <?php } ?>
                                <!-- Fin de validacion de tipo de hoja -->
                                <td>$<?php echo $totalLab; ?></td>
                                <td>$<?php echo $totalRX; ?></td>
                                <td>$<?php echo $totalUltras; ?></td>
                                <td>$<?php echo number_format($totalHemodialisis, 2); ?></td>
                                <td>$<?php echo number_format($totalDietas, 2); ?></td>
                                <td>$<?php echo number_format($totalRefrigerios, 2); ?></td>
                            </tr>

                        <?php 
                            }}
                        ?>

                        <tr>
                            <td><strong>TOTALES</strong></td>
                            <td><strong>$<?php echo number_format($totalGlobalI, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($laboratorioGlobalI, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($rxGlobalI, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($ultrasGlobalI, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($hemodialisisGlobalI, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($dietaGlobalI, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($refrigeriosGlobalI, 2); ?></strong></td>

                            <td><strong>$<?php echo number_format(($totalGlobalI - ($laboratorioGlobalI + $rxGlobalI + $ultrasGlobalI + $hemodialisisGlobalI + $dietaGlobalI + $refrigeriosGlobalI)), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($totalGlobalA, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($laboratorioGlobalA, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($rxGlobalA, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($ultrasGlobalA, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($hemodialisisGlobalA, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($dietaGlobalA, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($refrigeriosGlobalA, 2); ?></strong></td>

                            <td><strong>$<?php echo number_format(($totalGlobalA - ($laboratorioGlobalA + $rxGlobalA + $ultrasGlobalA + $hemodialisisGlobalA+ $dietaGlobalA + $refrigeriosGlobalA)), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($laboratorioGlobalI + $laboratorioGlobalA), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($rxGlobalI + $rxGlobalA), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($ultrasGlobalI + $ultrasGlobalA), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($hemodialisisGlobalI + $hemodialisisGlobalA), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($dietaGlobalI + $dietaGlobalA), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($refrigeriosGlobalI + $refrigeriosGlobalA), 2); ?></strong></td>
                        </tr>

                    </tbody>
                </table>
            </div>
    </div>
</div>


