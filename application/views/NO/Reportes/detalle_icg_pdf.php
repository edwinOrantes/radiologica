<style>
    body {
        font-family: "Arial";
        font-size: 12px;
    }

    #cabecera {
        text-align: left;
        width: 99%;
        margin: auto;
    }

    #lateral {
        width: 35%;
        /* Este será el ancho que tendrá tu columna */
        float: left;
        /* Aquí determinas de lado quieres quede esta "columna" */
        padding-top: -25px;
    }

    #principal {
        width: 60%;
        float: right;
    }

    /* Para limpiar los floats */
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    .proveedor,
    .medicamentos {
        margin-top: 10px;
    }

    .proveedor .detalle table,
    .medicamentos .detalle table {
        font-size: 12px;
        margin: auto;
        width: 100%;
    }

    .proveedor .detalle table tr td {
        padding: 5px;
        text-align: left;
        font-size: 11px;
    }

    .medicamentos {
        text-align: center;
    }

    .medicamentos .detalle table tr td {
        padding: 2px !important;
        /* font-size: 10px; */
        text-align: left;
        padding-left: 15px;
        /* border-width: 1px;
    border-style: solid;
    border-color: #000; */
    }

    .bordeBajo {
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: #000;
    }

    .bordeArriba {
        border-top-width: 1px;
        border-top-style: solid;
        border-top-color: #000;
    }

    /* .detalle table tr:nth-child(even){
    background: rgba(11, 153, 208, 0.1);
    color: #FFFFFF;
} */
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
        <p style="margin-top: 0px;"><strong>
                <?php
                $fechaI = explode("-", $i);
                $fechaF = explode("-", $f);
                $anioI = $fechaI[0];
                $anioF = $fechaI[0];
                $mes = $fechaI[1];
                $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anioI); // 31
                //echo "There were {$number} days in August 2003";
                if ($anioI == $anioF) {
                    echo "Detalle de Ingresos, Costos y Gastos del 01 al " . $dias . " de " . $meses[$mes - 1] . " " . $anioF;
                }

                ?>
            </strong>
        </p>
        <div class="detalle">
            <table cellspacing="0" cellpadding="5">
                <thead>
                </thead>
                <?php
                $totalGlobalI = 0;
                $totalGlobalA = 0;
                $laboratorioGlobalI = 0;
                $laboratorioGlobalA = 0;
                $rxGlobalI = 0;
                $rxGlobalA = 0;
                $ultrasGlobalI = 0;
                $ultrasGlobalA = 0;
                foreach ($hojasCerradas as $hoja) {
                ?>
                    <?php
                    $insumos = $this->Reportes_Model->insumosHoja($hoja->idHoja);
                    $totalHoja = 0;
                    foreach ($insumos as $insumo) {
                        $totalHoja += ($insumo->cantidadInsumo * $insumo->precioInsumo);
                    }
                    ?>
                    <!-- Sacando tatales de RX, Laboratorio y ultras -->
                    <?php
                    $examenes = $this->Reportes_Model->examenesHoja($hoja->idHoja);
                    $totalLab = 0;
                    $totalRX = 0;
                    $totalUltras = 0;
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
                        }
                        $totalNeto += ($examen->cantidadInsumo * $examen->precioInsumo);
                    }

                    ?>
                    <!-- Fin totales -->
                    <?php
                    if ($hoja->tipoHoja == "Ingreso") {
                        $totalGlobalI += $totalHoja;
                        $laboratorioGlobalI += $totalLab;
                        $rxGlobalI += $totalRX;
                        $ultrasGlobalI += $totalUltras;
                    ?>

                    <?php
                    } else {
                        $totalGlobalA += $totalHoja;
                        $laboratorioGlobalA += $totalLab;
                        $rxGlobalA += $totalRX;
                        $ultrasGlobalA += $totalUltras;
                    ?>
                    <?php } ?>
                    <!-- Fin de validacion de tipo de hoja -->
                <?php
                }
                ?>
                <!-- Detalle Ingresos -->
                    <tr style="background-color: #409cff">
                        <td style="color: #fff; text-align: left; font-weight: bold" colspan="3">DETALLE DE INGRESOS</td>
                        <td style="color: #fff;"><strong>$<?php echo number_format(($totalGlobalI + $totalGlobalA), 2); ?></strong></td>
                    </tr>
                <tbody>
                    <!-- Total de Ingresos -->
                        <tr>
                            <td colspan="2" style="padding-left: 30px;">Total Ingresos</td>
                            <td>$<?php echo number_format($totalGlobalI, 2); ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Ingresos</td>
                            <td>$<?php echo number_format(($totalGlobalI - ($laboratorioGlobalI + $rxGlobalI + $ultrasGlobalI)), 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Rayos X</td>
                            <td>$<?php echo number_format($rxGlobalI, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Laboratorio</td>
                            <td>$<?php echo number_format($laboratorioGlobalI, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Ultrasonografia</td>
                            <td>$<?php echo number_format($ultrasGlobalI, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>

                    <!-- Total de Ambulatorio -->
                        <tr>
                            <td colspan="2" style="padding-left: 30px;">Total Ambulatorio</td>
                            <td class="bordeBajo">$<?php echo number_format($totalGlobalA, 2); ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Ingresos</td>
                            <td>$<?php echo number_format(($totalGlobalA - ($laboratorioGlobalA + $rxGlobalA + $ultrasGlobalA)), 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Rayos X</td>
                            <td>$<?php echo number_format($rxGlobalA, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Laboratorio</td>
                            <td>$<?php echo number_format($laboratorioGlobalA, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px;">Ultrasonografia</td>
                            <td>$<?php echo number_format($ultrasGlobalA, 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <!-- Total Costos -->
                        <?php
                        $totalCostos = 0;
                        foreach ($gastos as $costo) {
                            if ($costo->clasificacionCuenta == 3) {
                                $totalCostos += $costo->total;
                            }
                        }

                        if ($totalCostos > 0) {
                        ?>
                            <tr style="background-color: #409cff">
                                <td style="color: #fff; text-align: left; font-weight: bold" colspan="3">DETALLE DE COSTOS</td>
                                <td style="color: #fff;"><strong>$<?php echo number_format($totalCostos, 2); ?></strong></td>
                            </tr>

                            <?php
                            foreach ($gastos as $costo) {
                                if ($costo->clasificacionCuenta == 3) {
                            ?>
                                    <tr>
                                        <td colspan="2" style="padding-left: 30px;"><?php echo $costo->nombreCuenta; ?></td>
                                        <td>$<?php echo number_format($costo->total, 2); ?></td>
                                        <td></td>
                                    </tr>
                        <?php }
                            }
                        } ?>

                    <!-- Total Gastos -->
                        <?php
                        $totalGastos = 0;
                        foreach ($gastos as $costo) {
                            if ($costo->clasificacionCuenta != 3) {
                                $totalGastos += $costo->total;
                            }
                        }

                        if ($totalGastos > 0) {
                        
                        ?>
                        <tr style="background-color: #409cff">
                            <td style="color: #fff; text-align: left; font-weight: bold" colspan="3">DETALLE DE GASTOS</td>
                            <td style="color: #fff;"><strong>$<?php echo number_format($totalGastos, 2); ?></strong></td>
                        </tr>

                        <?php
                        foreach ($gastos as $costo) {
                            if ($costo->clasificacionCuenta != 3) {
                        ?>
                                <tr>
                                    <td colspan="2" style="padding-left: 30px;"><?php echo $costo->nombreCuenta; ?></td>
                                    <td>$<?php echo number_format($costo->total, 2); ?></td>
                                    <td></td>
                                </tr>
                        <?php }}} ?>

                    <!-- Totales -->

                        <tr style="background-color: #409cff">
                            <td style="color: #fff; text-align: left; font-weight: bold" colspan="3">TOTAL COSTOS Y GASTOS</td>
                            <td style="color: #fff;"><strong>$<?php echo number_format(($totalCostos + $totalGastos), 2); ?></strong></td>
                        </tr>

                        <tr>
                            <td colspan="3"> &nbsp; </td>
                            <td><strong></strong> &nbsp; </td>
                        </tr>

                        <tr style="background-color: #409cff">
                            <td style="color: #fff; text-align: left; font-weight: bold" colspan="3">TOTAL GENERAL</td>
                            <td style="color: #fff;"><strong>$<?php echo number_format(($totalCostos + $totalGastos), 2); ?></strong></td>
                        </tr>

                        <tr style="background-color: #409cff">
                            <td style="color: #fff; text-align: left; font-weight: bold" colspan="3">UTILIDAD MENSUAL</td>
                            <td style="color: #fff;"><strong>$<?php echo number_format(($totalGlobalI + $totalGlobalA) - ($totalCostos + $totalGastos), 2); ?></strong></td>
                        </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Consulta para detalle de costos y gastos -->