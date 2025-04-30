<style>
    body{
        background-image: url('public/img/bg_isbm.jpg') ;
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    #cabecera {
        text-align: left;
        width: 100%;
        margin: auto;
        }

    #lateral {
        width: 25%;  /* Este será el ancho que tendrá tu columna */
        float:left; /* Aquí determinas de lado quieres quede esta "columna" */
    }
    
    #principal {
        width: 65%;
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

    #noData{
        font-weight:  bold;
        font-size: 16px;
        text-align: center;
        margin: 0 auto;
        width: 50%;
    }

</style>

<?php
    $fecha = "jujuju";
    $codigo = "Holala";
    /* foreach ($medicamentos as $fila) {
        $fecha = $fila->fechaCuenta;
        switch ($fila->codigoCuenta) {
            case ($fila->codigoCuenta > 0) && ($fila->codigoCuenta < 10):
                $codigo = "000".$fila->codigoCuenta;
                break;
            case ($fila->codigoCuenta >= 10) && ($fila->codigoCuenta < 100):
                $codigo = "00".$fila->codigoCuenta;
                break;
            case ($fila->codigoCuenta >= 100) && ($fila->codigoCuenta < 1000):
                $codigo = "0".$fila->codigoCuenta;
                break;
            case ($fila->codigoCuenta >= 1000):
                $codigo = $fila->codigoCuenta;
                break;
            
            default:
            $codigo = "000".$fila->codigoCuenta;
                break;
        }
    } */
?>

<?php
    if(sizeof($insumos) > 0){
?>
<div id="cabecera" class="clearfix">

    <div id="lateral">
        <p><img src="<?php echo base_url() ?>public/img/logo.png" alt="" width=""></p>
    </div>

    <div id="principal">
        <table style="text-align: center;">
            <tr>
                <td style="font-size: 12px;"><strong><br>HOSPITAL ORELLANA, USULUTAN</strong></td>
            </tr>
            <tr>
                <td style="font-size: 12px; line-height: 15px;"><strong>RESUMEN DE DESCARGOS, LABORATORIO CLINICO</strong></td>
            </tr>
        </table>
    </div>
</div>
<hr style="color: #0b88c9; margin-top: -5px;">
<p style="font-size: 12px; text-align: center; font-weight: bold;">DETALLE</p>
<div class="contenedor">
    <div class="medicamentos">
            <div class="detalle">
                <table class="tabla_detalle">
                    <thead>
                        <tr style="background-color: #0b88c9; color: #fff;">
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 10px"> # </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 10px"> Código </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 10px">Medicamento</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 10px">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $index = 0;
                            $total = 0;
                            foreach ($insumos as $fila) {
                                $total += $fila->cantidadInsumo;
                                $index++;
                        ?>
                            <tr>
                                <td style="font-size: 10px"><?php echo $index; ?></td>
                                <td style="font-size: 10px"><?php echo $fila->codigoInsumoLab; ?></td>
                                <td style="font-size: 10px"><?php echo $fila->nombreInsumoLab; ?></td>
                                <td style="font-size: 10px"><?php echo $fila->cantidadInsumo; ?></td>
                            </tr>

                        <?php } ?>

                            <tr>
                                <td style="font-size: 10px" colspan="3"><strong>TOTAL</strong></td>
                                <td style="font-size: 10px"><?php echo $total; ?></td>
                            </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<?php
    }else{
        echo "<div id='noData'><p>Ho hay datos para este dia...</p></div>";
    }
 ?>