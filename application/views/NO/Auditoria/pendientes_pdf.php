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
        padding: 2px !important;
        font-size: 10px;
        text-align: center;
        border-width: 0.1px;
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
        <p style="margin-top: 0px; text-transform: uppercase;"><strong>Detalle de abonos a cuentas pendientes</strong></p>
            <div class="detalle">
                <table cellspacing="0" cellpadding="5">
                    <thead>
                        <tr style="background-color: #007bff;">
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">#</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">CODIGO</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">F/E</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">F/S</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">PACIENTE</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">MEDICO</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">INTERNO</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">EXTERNO</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">TOTAL</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">ABONADO</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">TIPO HOJA</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">HABITACION</th>
                            <th style="color: #fff; text-align: center; font-weight: bold; font-size: 8px">SEGURO</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $flag = 0;
                        $medicamentosP = 0;
                        $medicamentosH = 0;
                        $externosP = 0;
                        $externosH = 0;
                        $abonadoH = 0;
                        $abonadoP = 0;
                        foreach ($abonos as $fila) {
                            $flag++;
                            if($fila->esPaquete == 0){
                                $abonadoH += $fila->abonado;
                                $medicamentosH += $fila->interno;
                                $externosH += $fila->externo;
                            }else{
                                $abonadoP += $fila->abonado;
                                $medicamentosP += $fila->interno;
                                $externosP += $fila->externo;
                            }

                    ?>
                        <tr>
                            <td style="font-size: 8px"><?php echo $flag; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->codigoHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->fechaHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->salidaHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->nombrePaciente; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->nombreMedico; ?></td>
                            <td style="font-size: 8px">$<?php echo number_format($fila->interno, 2); ?></td>
                            <td style="font-size: 8px">$<?php echo number_format($fila->externo, 2); ?></td>
                            <td style="font-size: 8px">$<?php echo number_format(($fila->interno + $fila->externo), 2); ?></td>
                            <td style="font-size: 8px">$<?php echo number_format($fila->abonado, 2); ?></td>
                            <td style="font-size: 8px"><?php echo $fila->tipoHoja; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->numeroHabitacion; ?></td>
                            <td style="font-size: 8px"><?php echo $fila->nombreSeguro; ?></td>
                        </tr>

                    <?php } ?>
                        <tr>
                            <td colspan="6" class="text-right"><strong>HOJAS</strong></td>
                            <td><strong>$<?php echo number_format($medicamentosH, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($externosH, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($medicamentosH + $externosH, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($abonadoH, 2); ?></strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>PAQUETES</strong></td>
                            <td><strong>$<?php echo number_format($medicamentosP, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($externosP, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($medicamentosP + $externosP, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($abonadoP, 2); ?></strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>TOTAL</strong></td>
                            <td><strong>$<?php echo number_format($medicamentosH + $medicamentosP, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($externosH + $externosP, 2); ?></strong></td>
                            <td><strong>$<?php echo number_format(($medicamentosH + $medicamentosP) + ($externosH + $externosP), 2); ?></strong></td>
                            <td><strong>$<?php echo number_format($abonadoP + $abonadoH, 2); ?></strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>


