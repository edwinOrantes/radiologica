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
        margin-top: 25px;
    }
    .table{
        border-collapse: collapse;
        margin-top: 25px;
    }
    
    .detalle .table tr th{
        color: #fff;
        font-size: 12px;
        padding: 5px 10px 5px 10px;
    }

    .detalle .table tr td{
        font-size: 12px;
        font-weight: bold;
        height: 40px;
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
            <div style="border: 2px solid #0b88c9; padding-top: 10px; padding-bottom: 0px;">
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
                            <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta; ?></p></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            
            <p style="font-size: 12px; color: #0b88c9; margin-top: 40px;"><strong>RESULTADOS EXAMEN DEPURACION DE CREATININA EN ORINA DE 24 HORAS</strong></p>
            <div class="detalle">
                <table class="table" style="margin-top: -5px;">
                    <thead>
                        <tr style="background: #0b88c9;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                            <th> Valores normales </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($creatinina->tiempoDepuracion != ""){
                                echo '<tr>
                                        <td><strong class="">Volumen</strong></td>
                                        <td style="text-align: center;">'.number_format($creatinina->volumenDepuracion).' ml/24h</td>
                                        <td style="text-align: center;">750-2000 ml/24h</td>
                                    </tr>';
                            }

                            if($creatinina->tiempoDepuracion != ""){
                                echo '<tr>
                                        <td><strong class="">Tiempo</strong></td>
                                        <td style="text-align: center;">'.number_format($creatinina->tiempoDepuracion).' minutos</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }

                            if($creatinina->csDepuracion != ""){
                                echo '<tr>
                                        <td><strong class="">Creatinina en sangre</strong></td>
                                        <td style="text-align: center;">'.$creatinina->csDepuracion.' mg/dl</td>
                                        <td style="text-align: center;">0.7-1.4 mg/dl</td>
                                    </tr>';
                            }

                            if($creatinina->coDepuracion != ""){
                                echo '<tr>
                                        <td><strong class="">Creatinina en orina</strong></td>
                                        <td style="text-align: center;">'.$creatinina->coDepuracion.' mg/dl</td>
                                        <td style="text-align: center;">15-25 mg/dl</td>
                                    </tr>';
                            }

                            if($creatinina->dcDepuracion != ""){
                                echo '<tr>
                                        <td><strong class=""> Depuración de creatinina</strong></td>
                                        <td style="text-align: center;">'.$creatinina->dcDepuracion.' ml/min</td>
                                        <td style="text-align: center;">'.$creatinina->valorNormal.'</td>
                                    </tr>';
                            }


                            if($creatinina->proteinasDepuracion != ""){
                                echo '<tr>
                                        <td><strong class=""> Proteinas 24 hr</strong></td>
                                        <td style="text-align: center;">'.$creatinina->proteinasDepuracion.' mg/24hr</td>
                                        <td style="text-align: center;">Menor de 100</td>
                                    </tr>';
                            }
                        ?>
                    </tbody>
                </table>


                <table class="table" style="margin-top: 10px;">
                    <thead></thead>
                    <tbody>
                        <?php
                            echo '<tr>
                                    <td style="width: 25px"><strong>Observaciones:</strong></td>
                                    <td colspan="2" style="border-bottom: 1px solid #000">'.$creatinina->observacionesDepuracion.'</td>
                                </tr>';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- <div id="imgAgua" style="margin-top: -400px;">  
        <img src="<?php echo base_url(); ?>public/img/logo.png" alt="Logo hospital orellana" id="marcaAgua">
    </div> -->
</div>