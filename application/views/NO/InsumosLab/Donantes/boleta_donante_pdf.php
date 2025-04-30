<style>
    body{
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    .contenedor{
        border: 1px solid #000000;
        margin: 0 auto;
        margin-top: 25px;
        padding: 15px 15px 15px 25px;
        width: 70%
    }
    .cabecera{
        width: 100%;
    }
    .img_cabecera{
        width: 25%;
        width: 225px;
        float: left;
    }
    .title_cabecera{
        float: right;
        line-height: 5px;
        text-align: center;
        padding-top: 5px;
        width: 50%;
    }

    
    .detalle{
        width: 100%;
    }

    .tabla_detalle{
        font-size: 11px;
        margin-bottom: 25px;
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;

    }

    .tabla_detalle thead tr th, tbody tr td{
        border-width: 1px;
        border-style: solid;
        border-color: #000;
        
    }

    .letraMayuscula{
        text-transform: uppercase;
    }
</style>

<div class="contenedor">
    <div class="cabecera" style="font-family: Times New Roman">
        <div class="img_cabecera"><img src="<?php echo base_url()?>public/img/logo.png"></div>
        <div class="title_cabecera">
            <h5>LABORATORIO CLINICO</h5>
            <h6>HOSPITAL ORELLANA, USULUTAN</h6>
            <!-- <h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5> -->
        </div>
    </div>
    <div class="detalle">
        <h5><strong>Recibo de egreso:</strong> <?php echo $donante->codigoDonanteInsumo; ?></h5>
        <table class="tabla_detalle">
            <tr>
                <td style="height: 25px; font-size: 14px;"><strong>Fecha:</strong> <?php echo $donante->ultimaFecha; ?></td>
                <td style="height: 25px; font-size: 14px;"><strong>Precio:</strong> $<?php echo number_format($donante->precioInsumo, 2); ?> </td>
            </tr>
            <tr>
                <td  style="height: 25px; font-size: 14px;"><strong>Nombre:</strong> <?php echo $donante->nombreDonante; ?></td>
                <td style="height: 25px; font-size: 14px;"><strong>DUI:</strong> <?php echo $donante->duiDonante; ?> </td>
            </tr>
            <tr>
                <td  style="height: 25px; font-size: 14px;" colspan="2"><strong>Por:</strong> Muestra </td>
            </tr>
        </table>

        <table class="tabla_detalle">
        </table>
    
        <table class="tabla_num_recibo" style="padding-top: 25px; font-family: Times New Roman;">
            <tr>
                <td style="text-align: center;">
                    <p>______________________________</p>
                    <h5><strong>FIRMA</strong></h5>
                </td>
                <td style="width: 50px;"></td>
                <td style="text-align: center;">
                    <p>______________________________</p>
                    <h5><strong>SELLO</strong></h5>
                </td>
            </tr>

            <tr>
                <td  style="height: 20px; padding-top: 15px; font-size: 10px; text-align: left" colspan="2"><strong>Sexta calle oriente, #8, Usulután, El Salvador</strong></td>
                <td style="height: 20px; padding-top: 15px; font-size: 10px; text-align: right"><strong>PBX 2606-6673</strong></td>
            </tr>

        </table>
    </div>
</div>

<div class="contenedor">
    <div class="cabecera" style="font-family: Times New Roman">
        <div class="img_cabecera"><img src="<?php echo base_url()?>public/img/logo.png"></div>
        <div class="title_cabecera">
            <h5>LABORATORIO CLINICO</h5>
            <h6>HOSPITAL ORELLANA, USULUTAN</h6>
            <!-- <h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5> -->
        </div>
    </div>
    <div class="detalle">
        <h5><strong>Recibo de egreso:</strong> <?php echo $donante->codigoDonanteInsumo; ?></h5>
        <table class="tabla_detalle">
            <tr>
                <td style="height: 25px; font-size: 14px;"><strong>Fecha:</strong> <?php echo $donante->ultimaFecha; ?></td>
                <td style="height: 25px; font-size: 14px;"><strong>Precio:</strong> $<?php echo number_format($donante->precioInsumo, 2); ?> </td>
            </tr>
            <tr>
                <td  style="height: 25px; font-size: 14px;"><strong>Nombre:</strong> <?php echo $donante->nombreDonante; ?></td>
                <td style="height: 25px; font-size: 14px;"><strong>DUI:</strong> <?php echo $donante->duiDonante; ?> </td>
            </tr>
            <tr>
                <td  style="height: 25px; font-size: 14px;" colspan="2"><strong>Por:</strong> Donanción de <?php echo $donante->nombreInsumoLab; ?></td>
            </tr>
        </table>

        <table class="tabla_detalle">
        </table>
    
        <table class="tabla_num_recibo" style="padding-top: 25px; font-family: Times New Roman;">
            <tr>
                <td style="text-align: center;">
                    <p>______________________________</p>
                    <h5><strong>FIRMA</strong></h5>
                </td>
                <td style="width: 50px;"></td>
                <td style="text-align: center;">
                    <p>______________________________</p>
                    <h5><strong>SELLO</strong></h5>
                </td>
            </tr>

            <tr>
                <td  style="height: 20px; padding-top: 15px; font-size: 10px; text-align: left" colspan="2"><strong>Sexta calle oriente, #8, Usulután, El Salvador</strong></td>
                <td style="height: 20px; padding-top: 15px; font-size: 10px; text-align: right"><strong>PBX 2606-6673</strong></td>
            </tr>

        </table>
    </div>
</div>