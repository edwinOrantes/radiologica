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

    /* .proveedor, .medicamentos{
        margin-top: 10px;
    } */

    .proveedor .detalle table, .medicamentos .detalle table{
        font-size: 11px;
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
        border-width: 1px;
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
        <p style="text-align: left"><strong>Entrega de insumos para: </strong> <?php echo $stock; ?> </p>
        <p style="text-align: left"><strong>Entrego: </strong> <span id="entregadoA"></span> <?php echo $this->session->userdata('empleado_h'); ?> </p>
        <div class="detalle">
            <table cellspacing="0" cellpadding="5">
                <thead>
                    <tr style="background-color: #007bff">
                        <td style="color: #fff; text-align: center; font-weight: bold">#</td>
                        <td style="color: #fff; text-align: center; font-weight: bold">CODIGO</td>
                        <td style="color: #fff; text-align: center; font-weight: bold">INSUMO</td>
                        <td style="color: #fff; text-align: center; font-weight: bold">CANTIDAD</td>
                        <td style="color: #fff; text-align: center; font-weight: bold">ENTREGADO</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $index = 0;

                        foreach ($insumos as $nombreInsumo => $row) {
                            $index++;
                        ?>
                        <tr class="">
                            <td class="text-center"><?php echo $index; ?></td>
                            <td class="text-center"><?php echo $row["codigoInsumo"]; ?></td>
                            <td class="text-center"><?php echo $nombreInsumo; ?></td>
                            <td class="text-center"><?php echo $row["faltante"]; ?></td>
                            <td class="text-center"> </td>
                            
                        </tr>
                        
                        <?php
                        } // Fin del foreach
                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


