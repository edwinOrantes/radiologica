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
    $fecha = "";
    foreach ($hojas as $hoja) {
        $fecha = $hoja->fechaLiquidado;
    }
?>
<div id="cabecera" class="clearfix">

    <div id="lateral">
        <p><img src="<?php echo base_url() ?>public/img/logo.png" alt="" width="225"></p>
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
            
            <table style="width: 100%;">
                <tr>
                    <td><p style="width:50%;"><strong>Corte de caja: </strong> <?php echo $usuario->usuario; ?> </p> </td>
                    <td><p style="width:50%;"><strong>Fecha: </strong> <?php echo $fecha; ?> </p> </td>
                </tr>
                <tr>
                    <td colspan="2"> </td>
                </tr>
            </table>
            <div class="detalle">
                <table class="tabla_detalle">
                    <thead>
                        <tr style="background-color: #007bff; color: #fff;">
                            
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px"> Código Hoja </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">N° Salida</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Paciente</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Médico</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Total cuenta</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Abonado</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 8px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $abonado = 0;
                            foreach ($hojas as $hoja) {
                                $total += $hoja->medicamentos + $hoja->externos;
                                $abonado += $hoja->abonado;
                        ?>
                            <tr>
                                <td style="font-size: 8px"><?php echo $hoja->codigoHoja; ?></td>
                                <td style="font-size: 8px"><?php echo $hoja->correlativoSalidaHoja; ?></td>
                                <td style="font-size: 8px" class="mayusculas"><?php echo $hoja->nombrePaciente; ?></td>
                                <td style="font-size: 8px" class="mayusculas"><?php echo $hoja->nombreMedico; ?></td>
                                <td style="font-size: 8px">$ <?php echo number_format(($hoja->medicamentos + $hoja->externos), 2); ?></td> 
                                <td style="font-size: 8px">$ <?php echo number_format(($hoja->abonado), 2); ?></td> 
                                <td style="font-size: 8px">$ <?php echo number_format(($hoja->medicamentos + $hoja->externos) - $hoja->abonado, 2); ?></td> 
                            </tr>
                        <?php
                            }
                        ?>
                        
                        <tr>
                            <td style="font-size: 8px" colspan="4"><strong>TOTAL</strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($total), 2); ?></strong></td> 
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($abonado), 2); ?></strong></td>
                            <td style="font-size: 8px"><strong>$ <?php echo number_format(($total - $abonado), 2); ?></strong></td>
                        </tr>

                    </tbody>
                </table>
            </div>
    </div>
</div>