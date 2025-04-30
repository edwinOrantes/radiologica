<style>
    body{
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
        width: 100%;
    }

    .cabecera{
        width: 100%;
    }

    .img_cabecera{
        width: 50%;
        /* width: 225px; */
        float: left;
    }

    .title_cabecera{
        font-size: 10px;
        float: right;
        line-height: 1px;
        text-align: center;
        width: 50%;
    }
    

    .tabla_detalle{
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

<div class="" style="">
    <?php
        foreach ($vales as $row) {
    ?>
        <div class="" style="border: 1px solid #000000; box-sizing: border-box; height: 22%; float: left; padding: 15px 15px 0px 25px; width: 40%;">
            <div class="cabecera" style="font-family: Times New Roman">
                <div class="img_cabecera"><img src="<?php echo base_url()?>public/img/logo.png" width="125"></div>
                <div class="title_cabecera">
                    <h5>UNIDAD DE HEMODIALISIS</h5>
                    <h6>HOSPITAL ORELLANA, USULUTAN</h6>
                    <!-- <h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5> -->
                </div>
            </div>
            <div class="detalle" style="margin-top: 15px;">
                <table class="tabla_detalle">
                    <tr>
                        <td style="height: 25px; font-size: 10px;"><strong>#:</strong> <?php echo $row->codigoVale; ?> </td>
                    </tr>
                    <tr>
                        <td style="height: 25px; font-size: 10px;"><strong>Paciente:</strong> <?php echo $row->pacienteCita; ?> </td>
                    </tr>
                    <tr>
                        <td style="height: 25px; font-size: 10px;"><strong>Hoja:</strong> <?php echo $row->codigoHoja; ?></td>
                    </tr>
                    <tr>
                        <td  style="height: 25px; font-size: 10px;" colspan="2"><strong>Fecha:</strong> <?php echo $row->fechaVale; ?> </td>
                    </tr>
                    <tr>
                        <td  style="height: 25px; font-size: 10px;" colspan="2"><strong>Total: $</strong> <?php echo number_format(($row->interno + $row->externo), 2); ?> </td>
                    </tr>
                </table>
            
                <table class="tabla_num_recibo" style="font-family: Times New Roman;">
                    <tr>
                        <td  style="padding-top: 20px; font-size: 10px; text-align: center" colspan="3"><strong>Sexta calle oriente, #8, Usulután, El Salvador, <br> PBX 2606-6673</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php
       }
    ?>

</div>



