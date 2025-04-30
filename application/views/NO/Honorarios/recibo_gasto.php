<style>
    .cabecera {
        padding-top: 50px;
        padding-bottom: 20px;
        width: 100%;
    }

    .img_cabecera {
        padding-top: -20px;
        width: 25%;
        width: 225px;
        float: left;
    }

    .title_cabecera {
        padding-top: -15px;
        float: right;
        line-height: 5px;
        text-align: center;
        width: 60%;
    }

    .subtitle_cabecera {
        clear: both;
        width: 100%;
    }

    .subtitle_cabecera h5 {
        font-size: 11px;
        margin-top: 15px;
        text-align: center;
    }

    .paciente {
        padding-top: 20px;
        width: 100%;
    }

    .tabla_paciente {
        font-size: 10px;
        width: 100%;

    }

    .tabla_num_recibo {
        font-size: 12px;
        width: 100%;

    }

    .detalle {
        width: 100%;
        padding-top: -25px;
    }

    .tabla_detalle {
        font-size: 11px;
        margin-bottom: 25px;
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;

    }

    .tabla_detalle thead tr th,
    tbody tr td {
        border-width: 1px;
        border-style: solid;
        border-color: #000;
    }

    .pie {
        width: 100%;
    }

    .pie_izquierda {
        width: 68%;
        float: left;
    }

    .pie_derecha {
        float: right;
        text-align: left;
        width: 32%;
        line-height: 12px;

    }

    .pie_abajo {
        clear: both;
    }

    .pie_abajo_detalle {
        font-size: 8px;
        word-spacing: 3px;
    }

    .numeracion {
        font-size: 12x;
        width: 100%;
    }

    .numeracion_izquierda {
        width: 40%;
        width: 115px;
        float: left;
    }

    .numeracion_derecha {
        float: right;
        line-height: 5px;
        text-align: right;
        width: 60%;
    }

    .letraMayuscula {
        text-transform: uppercase;
    }
    p{
        font-size: 12px;
    }

    .medicamentos .detalle table tr td{
        padding: 5px;
        text-align: center;
        border-width: 0.1px;
        border-style: solid;
    }

    .medicamentos .detalle table{
        font-size: 12px;
        margin: auto;
        width: 100%;
    }

</style>

<div class="paciente">
    <table class="tabla_paciente" style="font-family: Times New Roman; padding-left: -4px;">

        <tr>
            <td style="font-size: 12px; text-align: left;"><strong>Fecha: </strong> <?php echo $fecha; ?></td>
            <td style="font-size: 12px; text-align: right;"><strong>Recibo de egreso:</strong> <?php echo $codigo; ?></td>
        </tr>
    </table>
    <p style="margin-bottom: -100px;"><strong>Entregue a: </strong> <?php echo $entregado; ?></p>
    <p><strong>Proveedor: </strong> <?php echo $proveedor; ?></p>
    <p><strong>Forma de pago: </strong> <?php

        switch ($forma) {
            case '1':
                echo "En efectivo";
                break;
            case '2':
                echo "Con cheque #".$cheque.", ".$bancoGasto.", ".$cuentaGasto;
                break;
            case '3':
                echo "Caja Chica";
                break;
            case '4':
                echo "Cargo a cuenta";
                break;
            
            default:
                echo "---";
                break;
        }

    ?></p>
    <p><strong>En concepto de: </strong> <?php echo $concepto; ?></p>
</div>

<div class="detalle">
    <table class="tabla_num_recibo" style="padding-top: 40px; font-family: Times New Roman;">
        <tr>
            <td colspan="2" style="text-align: right; padding-right: 205px; padding-bottom: 55px"><strong>Total: $ <?php echo number_format($total, 2); ?></strong> </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <p style="text-decoration: underline;">F.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php 
                if(isset($entregado)){ 
                    echo $entregado;
                }
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <h5><strong>RECIBI CONFORME</strong></h5>
            </td>
            <td style="text-align: center;">
                <p style="text-decoration: underline;">F.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                        if(isset($efectuoGasto)){
                            echo $efectuoGasto;
                        }                        
                    ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <h5><strong>ELABORADO POR</strong></h5>
            </td>
        </tr>
    </table>
</div>

<div style="margin-top: 500px;">
    
    <div style="padding-top: 400px;">
        <div class="medicamentos">
            <div class="detalle">
                <table cellspacing="0" cellpadding="5">
                    <thead>
                        <tr style="background-color: #007bff; color: #fff">
                            <td style="color: #fff"><strong>Fecha</strong></td>
                            <td style="color: #fff"><strong>Concepto</strong></td>
                            <td style="color: #fff"><strong># Cheque</strong></td>
                            <td style="color: #fff"><strong>Banco</strong></td>
                            <td style="color: #fff"><strong>Monto</strong></td>
                        </tr>
                        <?php
                            $totalHonorarios = 0;
                            foreach ($honorarios as $row) {
                                $totalHonorarios += $row->precioExterno;
                        ?>
                        <tr>
                            <td><?php echo $row->fechaGenerado; ?></td>
                            <td><?php echo "Pago por cuenta: ".$row->codigoHoja.", Paciente: ".$row->nombrePaciente; ?></td>
                            <td><?php echo $cheque ; ?></td>
                            <td><?php echo $bancoGasto ; ?></td>
                            <td>$ <?php echo number_format($row->precioExterno, 2); ?></td>
                        </tr>
                        <?php } ?> 

                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Total: </strong></td>
                            <td><strong>$ <?php echo number_format($totalHonorarios, 2); ?></strong></td>
                        </tr>

                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
