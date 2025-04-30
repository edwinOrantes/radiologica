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
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Total</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Abonado</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Pendiente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $abonoGlobal = 0;
                            foreach ($abonos as $abono) {
                                if($abono->esPaquete == 0){

                                
                        ?>
                        <tr>
                            <td style="font-size: 8px"><?php echo $abono->fechaHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $abono->salidaHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $abono->codigoHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $abono->correlativoSalidaHoja; ?></td>
                            <td style="font-size: 8px" class="mayusculas"><?php echo $abono->nombrePaciente; ?></td>
                            <td style="font-size: 8px" class="mayusculas"><?php echo $abono->nombreMedico; ?></td>
                            <td style="font-size: 8px">$ <?php
                                //////////////////////////////////////////////////////////////
                                    $totalHoja  = ($abono->medicamentos + $abono->externos);

                                //////////////////////////////////////////////////////////////
                                echo number_format($totalHoja, 2);
                            ?></td> 
                            <td style="font-size: 8px">$ <?php echo number_format($abono->montoAbono, 2); ?></td>  
                            <td style="font-size: 8px">$<?php echo number_format(($totalHoja - $abono->montoAbono), 2);?></td>  
                        </tr>
                        <?php
                                $abonoGlobal += $abono->montoAbono;
                                }
                            }
                        ?>
                        
                        <tr>
                            <td style="font-size: 8px" colspan="6"><strong>TOTAL</strong></td>
                            <td style="font-size: 8px"><strong>-</strong></td> 
                            <td style="font-size: 8px"><strong>$<?php echo number_format($abonoGlobal, 2); ?></strong></td>
                            <td style="font-size: 8px"><strong>-</strong></td> 
                        </tr>

                    </tbody>
                </table>
            </div>
    </div>
</div>