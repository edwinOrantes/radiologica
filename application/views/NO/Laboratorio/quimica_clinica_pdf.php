<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        background-image: url('public/img/test2_bg.jpg') ;
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
        color: #0b88c9;
        font-size: 11px;
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
        color: #0b88c9;
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
            <p><img src="<?php echo base_url() ?>public/img/logo.jpg" alt="Logo hospital Orellana" width="225"></p>
        </div>

        <div id="principal">
            <table style="text-align: center;">
                <tr>
                    <td><strong style="font-size: 12px; color: #0b88c9">LABORATORIO CLINICO</strong></td>
                </tr>
                <tr>
                    <td><strong style="font-size: 11px; color: #0b88c9">Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673, <br> C.S.S.P. N° 2150</strong></td>
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
                            <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta; ?></p></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <p style="font-size: 11px; color: #0b88c9"><strong>RESULTADOS EXAMEN QUIMICA CLINICA </strong></p>
            <div class="detalle">
                <table class="table">
                    <thead>
                        <tr style="background: #0b88c9;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                            <th> Unidades </th>
                            <th> Valores de referencia </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            if($quimica->sodioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Sodio</strong></td>
                                        <td style="text-align: center;">'.$quimica->sodioQuimicaClinica.'</td>
                                        <td style="text-align: center;">mmol/L</td>
                                        <td style="text-align: center;">136-148 mmol/L</td>
                                    </tr>';
                            }
                            if($quimica->potasioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Potasio</strong></td>
                                        <td style="text-align: center;">'.$quimica->potasioQuimicaClinica.'</td>
                                        <td style="text-align: center;">mmol/L</td>
                                        <td style="text-align: center;">3.5-5.3 mmol/L</td>
                                    </tr>';
                            }
                            if($quimica->cloroQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Cloro</strong></td>
                                        <td style="text-align: center;">'.$quimica->cloroQuimicaClinica.'</td>
                                        <td style="text-align: center;">mmol/L</td>
                                        <td style="text-align: center;">98-107 mmol/L</td>
                                    </tr>';
                            }
                            if($quimica->magnesioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Magnesio</strong></td>
                                        <td style="text-align: center;">'.$quimica->magnesioQuimicaClinica.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                        <td style="text-align: center;">1.6-2.5 mg/dl</td>
                                    </tr>';
                            }
                            if($quimica->fosforoQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Fosforo</strong></td>
                                        <td style="text-align: center;">'.$quimica->fosforoQuimicaClinica.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                        <td style="text-align: center;">Vn: 2.5-5.0 mg/dl</td>
                                    </tr>';
                            }
                            if($quimica->cpkTQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">CPK Total</strong></td>
                                        <td style="text-align: center;">'.$quimica->cpkTQuimicaClinica.'</td>
                                        <td style="text-align: center;">U/L</td>
                                        <td style="text-align: center;">0-195 U/L</td>
                                    </tr>';
                            }
                            if($quimica->cpkMbQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">CPK MB</strong></td>
                                        <td style="text-align: center;">'.$quimica->cpkMbQuimicaClinica.'</td>
                                        <td style="text-align: center;">U/L</td>
                                        <td style="text-align: center;">Menor a 24 U/L</td>
                                    </tr>';
                            }
                            if($quimica->ldhQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">LDH</strong></td>
                                        <td style="text-align: center;">'.$quimica->ldhQuimicaClinica.'</td>
                                        <td style="text-align: center;">U/L</td>
                                        <td style="text-align: center;">230-460 U/L</td>
                                    </tr>';
                            }
                            if($quimica->creatininaQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Creatinina</strong></td>
                                        <td style="text-align: center;">'.$quimica->creatininaQuimicaClinica.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                        <td style="text-align: center;">0.5-1.4 mg/dl</td>
                                    </tr>';
                            }
                            if($quimica->troponinaQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Troponina</strong></td>
                                        <td style="text-align: center;">'.$quimica->troponinaQuimicaClinica.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                        <td style="text-align: center;">0.01-15.0 ng/ml</td>
                                    </tr>';
                            }
                            if($quimica->comentariosQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Comentarios</strong></td>
                                        <td colspan=3 style="text-align: center;">'.$quimica->comentariosQuimicaClinica.'</td>
                                    </tr>';
                            }

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>