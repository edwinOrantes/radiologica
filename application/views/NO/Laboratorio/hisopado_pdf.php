<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        background-image: url('public/img/bg_lab.jpg') ;
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    #cabecera {
        text-align: left;
        width: 100%;
        margin: auto;
        margin-bottom: 10px;
    }

    #lateral {
        width: 40%;  /* Este será el ancho que tendrá tu columna */
        float:left; /* Aquí determinas de lado quieres quede esta "columna" */
        padding-top: -20px;
        }

    #principal {
        width: 60%;
        float: right;
        }
        
    /* Para limpiar los floats */
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
        }

    .borderAzul{
        color: #000000;
        font-size: 13px;
    }

    .proveedor .detalle table, .medicamentos .detalle table{
        font-size: 12px;
        margin: auto;
        width: 100%;
    }
    .proveedor .detalle table tr td{
        padding: 5px;
        text-align: left;
        font-size: 12px;
    }

    .medicamentos{
        text-align: center;
    }

    .medicamentos .detalle table tr td{
        padding: 2px !important;
        font-size: 12px;
        color: #000000;
    }

    #tablaPaciente{
        width: 100%;
    }

    #imgAgua{
        margin: 0 auto;
        width: 75%;
    }
    #marcaAgua{
        width: 450px;
        margin: 0 auto;
        opacity: 0.1;
    }

    .detalle{
        margin-top: 15px;
    }
    .table{
        border-collapse: collapse;
        margin-top: 25px;
    }
    
    .detalle .table tr th{
        color: #fff;
        padding: 5px 40px 5px 40px;
        height: 30px;
    }

    .detalle .table tr td{
        height: 50px;
    }
    
</style>

<div>
    <div id="cabecera" class="clearfix">

        <div id="lateral">
            <p><img src="<?php echo base_url() ?>public/img/logo.jpg" alt="Logo hospital Orellana" width="250"></p>
        </div>

        <div id="principal">
            <table style="text-align: center; margin-left: 20px">
                <tr>
                    <td><strong style="font-size: 14px; color: #0b88c9">LABORATORIO CLINICO</strong></td>
                </tr>
                <tr>
                    <td><strong style="font-size: 14px; color: #0b88c9">Sexta calle oriente, #8, Usulután, El Salvador,</strong></td>
                </tr>
                <tr>
                    <td><strong style="font-size: 14px; color: #0b88c9">PBX 2606-6673, C.S.S.P. N° 2150</strong></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="contenedor">
        <div class="medicamentos">
            
            <div style="border: 2px solid #0b88c9; padding-top: 10px; padding-bottom: 15px;">
                <div class="">
                    <table id="tablaPaciente" cellspacing=10>
                        <tr>
                            <td><strong class="borderAzul">Paciente:</strong></td>
                            <td><p class="borderAzul"><?php echo $cabecera->nombrePaciente; ?></p></td>
                            <td><strong class="borderAzul">Edad:</strong></td>
                            <td><p class="borderAzul"><?php echo $cabecera->edadPaciente; ?> Años</p></td>
                        </tr>
                        
                        <tr>
                            <td><strong class="borderAzul">Médico:</strong></td>
                            <td><p class="borderAzul"><?php echo $cabecera->nombreMedico; ?></p></td>
                            <td><strong class="borderAzul">Fecha:</strong></td>
                            <td><p class="borderAzul"><?php echo $cabecera->horaDetalleConsulta; ?></p></td>
                        </tr>
                        <?php
                            if(isset($hisopado->pasaporteHisopadoNasal)){
                                if($hisopado->pasaporteHisopadoNasal != ""){
                                    echo '<tr>
                                    <td><strong class="borderAzul">Pasaporte:</strong></td>
                                    <td><p class="borderAzul">'.$hisopado->pasaporteHisopadoNasal.'</p></td>
                                    </tr>';
                                }
                            }
                            ?>
                    </table>
                </div>
            </div>
            
            <p style="font-size: 14px; margin-top: 25px"><strong><?php echo $cabecera->nombreMedicamento; ?></strong></p>
            <div class="detalle">
                <!-- <p style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Resultado:</p> -->
                <?php
                    if($hisopado->resultadoHisopadoNasal){
                        echo '<p style="font-size: 16px; text-align: left; margin-top: 50px"><strong>Resultado: </strong>'.$hisopado->resultadoHisopadoNasal.'</p>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>
