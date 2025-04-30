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

    .medicamentos{
        text-align: center;
    }

    .proveedor .detalle table, .medicamentos .detalle table{
        font-size: 12px;
        margin: auto;
        width: 100%;
    }
    .proveedor .detalle table tr td, .medicamentos .detalle table tr td{
        padding: 2px;
        text-align: center;
        border: 1px solid #000;
    }

    .tabla_detalle{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 5px;
        border: 1px solid #000;
    }

    .detalle table tr{
        font-size: 1px !important
    }

    .mayusculas{
        text-transform: uppercase;
    }

    /* .detalle table tr:nth-child(even){
        background: rgba(11, 153, 208, 0.1);
        color: #FFFFFF;
    } */

</style>

<?php
    $totalISBMGlobal = 0;
    $totalCostoGlobal = 0;
    $totalAbonadoGlobal = 0;
?>


<div class="contenedor">
    <div class="medicamentos">
            <div class="detalle">
                <table class="tabla_detalle">
                    <thead>
                        <tr style="background-color: #007bff; color: #fff;">
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px"> Fecha Ingreso </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px"> Fecha Egreso </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px"> Código Hoja </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">N° Salida</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Paciente</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Médico</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Hospital</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Externos</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Subtotal</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Abonado</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Total</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">C/F</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $globalMedicamentosH = 0;
                            $globalMedicamentosP = 0;
                            $globalExternosH = 0;
                            $globalExternosP = 0;
                            foreach ($hojas as $datos_hoja) {
                                if($datos_hoja->anulada == 0){
                                    if($datos_hoja->porPagos == 0 || $datos_hoja->esPaquete == 1){
                                        echo "<tr>";
                                    }else{
                                        echo "<tr style='background: #EFCBD0;'>";
                                    }
                        ?>
                        <!-- <tr> -->
                            <td style="font-size: 8px"><?php echo $datos_hoja->fechaHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $datos_hoja->salidaHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $datos_hoja->codigoHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $datos_hoja->correlativoSalidaHoja; ?></td>
                            <td style="font-size: 8px" class="mayusculas"><?php echo $datos_hoja->nombrePaciente; ?></td>
                            <td style="font-size: 8px" class="mayusculas"><?php echo $datos_hoja->nombreMedico; ?></td>
                            <td style="font-size: 8px">$ <?php
                                //////////////////////////////////////////////////////////////
                                    $totalHospital = 0;
                                    $totalExterno = 0;
                                    $totalAbonado = 0;

                                    $totalHospital  += ($datos_hoja->medicamentos - $datos_hoja->descuentoHoja);
                                    $totalExterno  += $datos_hoja->externos;
                                    $totalAbonado  += @$datos_hoja->abonado;
                                    
                                //////////////////////////////////////////////////////////////

                                echo number_format(($totalHospital), 2);
                            ?></td> 
                            <td style="font-size: 8px">$ <?php echo number_format($totalExterno, 2); ?></td>  
                            <td style="font-size: 8px">$ <?php echo number_format(($totalHospital + $totalExterno), 2); ?></td>  
                            <td style="font-size: 8px">$ <?php echo number_format($totalAbonado, 2); ?></td>
                            <td style="font-size: 8px">$ <?php echo number_format(($totalHospital + $totalExterno) - $totalAbonado, 2); ?></td>
                            <td style="font-size: 8px"><?php echo $datos_hoja->credito_fiscal; ?></td>
                        </tr>
                        <?php   
                                    if($datos_hoja->porPagos == 0 || $datos_hoja->esPaquete == 1){
                                        $globalMedicamentosH += $totalHospital;
                                        $globalExternosH += $totalExterno;
                                    }else{
                                        if($datos_hoja->porPagos == 1){
                                            $globalMedicamentosP += $totalHospital;
                                            $globalExternosP += $totalExterno;
                                            $totalAbonadoGlobal += $totalAbonado;
                                        }
                                    }
                                }
                            }
                        ?>

                        <tr>
                            <td style="font-size: 8px" colspan="6"><strong>CUENTAS POR ABONOS</strong></td>
                            <td style="font-size: 8px; text-decoration:line-through">$ <?php echo number_format($globalMedicamentosP, 2); ?></td> 
                            <td style="font-size: 8px; text-decoration:line-through">$ <?php echo number_format($globalExternosP, 2); ?></td> 
                            <td style="font-size: 8px; text-decoration:line-through">$ <?php echo number_format(($globalMedicamentosP + $globalExternosP), 2); ?></td>
                            <td style="font-size: 8px; text-decoration:line-through">$ <?php echo number_format($totalAbonadoGlobal, 2); ?></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentosP + $globalExternosP) - $totalAbonadoGlobal, 2); ?></strong></td>
                            <td style="font-size: 8px"><strong></strong></td> 
                        </tr>
                        
                        <tr>
                            <td style="font-size: 8px" colspan="6"><strong>TOTAl DIA</strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format($globalMedicamentosH, 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format($globalExternosH, 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentosH + $globalExternosH), 2); ?></strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(0, 2); ?></strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentosH + $globalExternosH), 2); ?></strong></td>
                            <td style="font-size: 8px"><strong></strong></td> 
                        </tr>

                        <tr>
                            <td style="font-size: 8px" colspan="6"><strong>TOTAl</strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentosH + $globalMedicamentosP), 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format($globalExternosH + $globalExternosP, 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentosH + $globalExternosH) + ($globalMedicamentosP + $globalExternosP), 2); ?></strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format($totalAbonadoGlobal, 2); ?></strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentosH + $globalExternosH) + ($globalMedicamentosP + $globalExternosP) - $totalAbonadoGlobal, 2); ?></strong></td>
                            <td style="font-size: 8px"><strong></strong></td> 
                        </tr>

                    </tbody>
                </table>
            </div>
    </div>
</div>