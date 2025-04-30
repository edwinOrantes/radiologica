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

<?php
    //var_dump($listaRecibos);
    $flag = 0;
    foreach ($listaRecibos as $row) {
        $gasto = $this->Gastos_Model->obtenerGasto($row);
        // var_export($gasto);
        if($flag == 0 ){
            echo '<div class="cabecera" style="font-family: Times New Roman">';
        }else{
            echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
            echo '<div class="cabecera" style="font-family: Times New Roman">';
        }
        
?>

    <div class="img_cabecera"><img src=<?php echo base_url()."public/img/logo.png"; ?>></div>
    <div class="title_cabecera">
        <h4>HOSPITAL ORELLANA, USULUTAN</h4>
        <h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5>
    </div>
</div>
<div class="paciente">
    <table class="tabla_paciente" style="font-family: Times New Roman; padding-left: -4px;">

        <tr>
            <td style="font-size: 12px; text-align: left;"><strong>Fecha: </strong> <?php echo $gasto->fechaGasto; ?></td>
            <td style="font-size: 12px; text-align: right;"><strong>Recibo de egreso:</strong> <?php echo $gasto->codigoGasto; ?></td>
        </tr>
    </table>
    <p style="margin-bottom: -100px;"><strong>Entregue a: </strong> <?php echo $gasto->entregadoGasto; ?></p>
    <p>
        <strong>Proveedor: </strong>
        <?php 
            if ($gasto->entidadGasto == 1) {
                $medico = $this->Externos_Model->obtenerMedico($gasto->idProveedorGasto);
                $proveedor = $medico->nombreMedico;
            } else {
                $medico = $this->Pendientes_Model->rowProveedor($gasto->idProveedorGasto);
                $proveedor = $medico->empresaProveedor;
                
            }
            echo $proveedor;
        ?>
    </p>
    <p><strong>Forma de pago: </strong> <?php
        if($gasto->pagoGasto == 1){
            echo "En efectivo";
        }else{
            if($forma == 2){
                // echo "Con cheque #".$cheque;
                echo "Con cheque ";
            }else{
                echo "Caja Chica";
            }
        }
    ?></p>
    <p><strong>En concepto de: </strong> <?php echo $gasto->descripcionGasto; ?></p>
</div>

<div class="detalle">
    <table class="tabla_num_recibo" style="padding-top: 40px; font-family: Times New Roman;">
        <tr>
            <td colspan="2" style="text-align: right; padding-right: 205px; padding-bottom: 55px"><strong>Total: $ <?php echo number_format($gasto->montoGasto, 2); ?></strong> </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <p>F._______________________________________</p>
                <h5><strong>RECIBI CONFORME</strong></h5>
            </td>
            <td style="text-align: center;">
                <p style="text-decoration: underline;">F.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $gasto->efectuoGasto; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <h5><strong>ELABORADO POR</strong></h5>
            </td>
        </tr>
    </table>
</div>

<?php
    $flag++;
}
?>

<!-- if ($cuenta->entidadGasto == 1) {
    $tipoEntidad = "Médico";
    //$medico = $this->Externos_Model->detalleExternoMedico($cuenta->idProveedorGasto);
    $medico = $this->Externos_Model->obtenerMedico($cuenta->idProveedorGasto);
    $proveedor = $medico->nombreMedico;
} else {
    $tipoEntidad = "Otros proveedores";
    $medico = $this->Pendientes_Model->rowProveedor($cuenta->idProveedorGasto);
    $proveedor = $medico->empresaProveedor;
    /* if($cuenta->flagGasto == 1){
        $medico = $this->Pendientes_Model->rowProveedor($cuenta->idProveedorGasto);
        $proveedor = $medico->empresaProveedor;
    }else{
        $medico = $this->Externos_Model->detalleExternoProveedor($cuenta->idProveedorGasto);
        $proveedor = $medico->empresaProveedor;
    } */
    
} -->

<!-- http://192.168.1.92/hospital/Honorarios/mostrar_pagos/YToxOntzOjU6Imdhc3RvIjthOjI6e2k6MDtpOjE2MjtpOjE7aToxNjM7fX0%3D/ -->