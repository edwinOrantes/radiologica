<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        /* color: #00002B; */
        color: #000000;
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
        border: 1px solid #00002B;
    }

    .tabla_detalle{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 5px;
        border: 1px solid #00002B;
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
            <p style="margin-top: 0px;"><strong>Lista de honorarios pendientes hasta el <?php echo date('Y-m-d'); ?> </strong></p>
            <div class="detalle">
                <?php 
                    if(sizeof($honorarios) > 0){
                ?>
                <table class="tabla_detalle">
                    <thead>
                        <tr style="background-color: #007bff; color: #fff;">
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 9px"> # </th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 9px">Nombre</th>
                            <th style="color: #fff; border: 1px solid #000; padding-top: 2px; padding-bottom: 2px; font-size: 9px">Monto honorario</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            $flag = 1;
                            $total = 0;
                            foreach ($honorarios as $honorario) {
                                $total += $honorario->total_honorarios;
                        ?>
                        <tr>
                            <td style="font-size: 9px"><?php echo $flag; ?></td>
                            <td style="font-size: 9px"><?php echo str_replace("(Honorarios)","", $honorario->nombreExterno); ?></td>
                            <td style="font-size: 9px">$ <?php echo number_format($honorario->total_honorarios, 2); ?></td>
                        </tr>

                        <?php
                                $flag++;
                            }
                        ?>
                        <tr>
                            <td style="font-size: 9px" colspan="2"><strong>TOTAL PENDIENTES</strong></td>
                            <td style="font-size: 9px"><strong><?php echo "$ ".number_format($total, 2); ?></strong></td> 
                        </tr>

                    </tbody>
                </table>
                <?php
                    }else{
                        echo '<div style="background:rgba(255,0,0,0.2); padding: 10px; margin-top: 10px; font-weight: bold">
                                <p>No hay honorarios pendientes para este médico.</p>
                            </div>';
                    }
                ?>
            </div>
    </div>
</div>

