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
        <p style="margin-top: 0px;"><strong>REPORTE COBROS POR PACIENTES ENTRE <?php echo $inicio; ?> Y <?php echo $fin; ?> </strong></p>
            <div class="detalle">
                <table cellspacing="0" cellpadding="5">
                    <thead>
                        <tr style="background-color: #007bff;">
                            <td style="color: #fff; text-align: center; font-weight: bold">Código</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Cuenta</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Descripción</td>
                            <!-- <td style="color: #fff; text-align: center; font-weight: bold">Tipo</td> -->
                            <td style="color: #fff; text-align: center; font-weight: bold">Pago</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Cheque</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Entregado a</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Fecha</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $totalGastos = 0;
                            foreach ($gastos as $gasto) {
                                $totalGastos += $gasto->montoGasto;
                        ?>
                        <tr>
                            <td style="padding: 2px;"><?php echo $gasto->codigoGasto; ?></td>
                            <td style="padding: 2px;"><?php echo $gasto->nombreCuenta; ?></td>
                            <td style="padding: 2px;"><?php echo $gasto->descripcionGasto; ?></td>
                            <!-- <td style="padding: 2px;"><?php // echo $gasto->nombreTipoGasto; ?></td> -->
                            
                            <?php
                                if($gasto->pagoGasto == 1){
                                    echo "<td>Efectivo</td>";
                                    echo "<td>---</td>";
                                }else{
                                    echo "<td>Cheque</td>";
                                    echo "<td>".$gasto->numeroGasto."</td>";
                                }
                                ?>
                            <td><?php echo $gasto->entregadoGasto; ?></td>
                            <td><?php echo $gasto->fechaGasto; ?></td>
                            <td style="padding: 2px;">$ <?php echo number_format($gasto->montoGasto, 2); ?></td>
                        </tr>

                        <?php } ?>
                        <tr>
                            <td style="padding: 2px; padding-right: 25px; text-align: right;" colspan="7"><strong>TOTAL</strong></td>
                            <td style="padding: 2px;" colspan=""><strong>$ <?php echo number_format($totalGastos, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>


