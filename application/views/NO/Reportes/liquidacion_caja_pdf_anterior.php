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
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Total</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">C/F</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $globalMedicamentos = 0;
                            $globalExternos = 0;
                            foreach ($hojas as $datos_hoja) {
                                if($datos_hoja->anulada == 0){
                        ?>
                        <tr>
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
                                    
                                    $medicamentos = $this->Reportes_Model->obtenerMedicamentos($datos_hoja->idHoja);
                                    if(sizeof($medicamentos) > 0){
                                        foreach ($medicamentos as $hoja) {
                                            $totalHospital  += ($hoja->cantidadInsumo * $hoja->precioInsumo);
                                           
                                        }
                                    }

                                    $externos = $this->Reportes_Model->obtenerExternos($datos_hoja->idHoja);
                                    if(sizeof($externos) > 0){
                                        foreach ($externos as $hoja) {
                                            $totalExterno  += ($hoja->cantidadExterno * $hoja->precioExterno);
                                           
                                        }
                                    }

                                //////////////////////////////////////////////////////////////

                                echo number_format(($totalHospital - $datos_hoja->descuentoHoja), 2);
                            ?></td> 
                            <td style="font-size: 8px">$ <?php echo number_format($totalExterno, 2); ?></td>  
                            <td style="font-size: 8px">$ <?php echo number_format(($totalHospital + $totalExterno - $datos_hoja->descuentoHoja), 2); ?></td>  
                            <td style="font-size: 8px"><?php echo $datos_hoja->credito_fiscal; ?></td>  
                        </tr>
                        <?php
                                $globalMedicamentos += ($totalHospital - $datos_hoja->descuentoHoja);
                                $globalExternos += $totalExterno;
                                }
                            }
                        ?>
                        
                        <tr>
                            <td style="font-size: 8px" colspan="6"><strong>TOTAL</strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format($globalMedicamentos, 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format($globalExternos, 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($globalMedicamentos + $globalExternos), 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong></strong></td> 
                        </tr>

                    </tbody>
                </table>
            </div>
    </div>
</div>