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

<?php
    foreach ($medicamento as $med) {} ?>

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
        <p style="margin-top: 0px; text-transform: uppercase;"><strong>MOVIMIENTOS DEL MEDICAMENTO "<?php echo $med->nombreMedicamento; ?>"</strong></p>
            <div class="detalle">
                <table cellspacing="0" cellpadding="5">
                    <thead>
                        <tr style="background-color: #007bff;">
                            <td style="color: #fff; text-align: center; font-weight: bold">Fecha de uso</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Tipo de hoja</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Paciente</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Precio</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Cantidad</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $total = 0;
                        $cantidadTotal = 0;
                        foreach ($medicamento as $med) {
                            $total += ($med->cantidadInsumo * $med->precioInsumo);
                            $cantidadTotal += $med->cantidadInsumo;
                    ?>
                        <tr>
                            <td><?php echo $med->fechaInsumo; ?></td>
                            <td><?php echo $med->tipoHoja; ?></td>
                            <td><?php echo $med->nombrePaciente; ?></td>
                            <td>$ <?php echo number_format($med->precioInsumo, 2); ?></td>
                            <td><?php echo $med->cantidadInsumo; ?></td>
                            <td>$ <?php echo number_format(($med->cantidadInsumo * $med->precioInsumo), 2); ?></td>
                        </tr>

                    <?php } ?>
                        <tr>
                            <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                            <td><strong><?php echo $cantidadTotal; ?></strong></td>
                            <td><strong>$ <?php echo $total; ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>


