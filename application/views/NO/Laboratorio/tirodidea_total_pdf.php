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
        margin-bottom: 15px;
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
        font-size: 12px;
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
            
            <p style="font-size: 11px; color: #0b88c9; font-size: 12px"><strong>RESULTADO PRUEBAS TIROIDEAS TOTALES</strong></p>
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

                            if($tiroideaTotal->muestraTiroideaTotal != ""){
                                echo '<tr>
                                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px"  colspan=4 ><strong>Muestra: </strong>'.$tiroideaTotal->muestraTiroideaTotal.'</td>
                                    </tr>';
                            }
                            if($tiroideaTotal->t3TiroideaTotal != ""){
                                echo '<tr>
                                        <td><strong class="">T3 Total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->t3TiroideaTotal.'</td>
                                        <td style="text-align: center; font-weight: bold">ng/ml</td>
                                        <td style="text-align: center; font-weight: bold">0.5-5.0 ng/ml</td>
                                    </tr>';
                            }
                            if($tiroideaTotal->t4TiroideaTotal != ""){
                                echo '<tr>
                                        <td><strong class="">T4 Total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->t4TiroideaTotal.'</td>
                                        <td style="text-align: center; font-weight: bold">nmol/l</td>
                                        <td style="text-align: center; font-weight: bold">60.0-120.0 nmol/l</td>
                                    </tr>';
                            }
                            if($tiroideaTotal->tshTiroideaTotal != ""){
                                echo '<tr>
                                        <td><strong class="">TSH Total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->tshTiroideaTotal.'</td>
                                        <td style="text-align: center; font-weight: bold">uUI/ml</td>
                                        <td style="text-align: center; font-weight: bold">0.4-4.0 uIU/mL</td>
                                    </tr>';
                            }
                            if($tiroideaTotal->t4TiroideaTotal2 != ""){
                                echo '<tr>
                                        <td><strong class="">T4 Total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->t4TiroideaTotal2.'</td>
                                        <td style="text-align: center; font-weight: bold">nmol/l</td>
                                        <td style="text-align: center; font-weight: bold">66-181 nmol/L</td>
                                    </tr>';
                            }
                            if($tiroideaTotal->tshTiroideaTotal2 != ""){
                                echo '<tr>
                                        <td><strong class="">TSH Total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->tshTiroideaTotal2.'</td>
                                        <td style="text-align: center; font-weight: bold">uUI/ml</td>
                                        <td style="text-align: center; font-weight: bold">0.45-4.5 mIU/L</td>
                                    </tr>';
                            }
                            if($tiroideaTotal->observacionTiroideaTotal != ""){
                                echo '<tr>
                                        <td><strong class="">Observaciones</strong></td>
                                        <td style="text-align: center; font-weight: bold" colspan=3>'.$tiroideaTotal->observacionTiroideaTotal.'</td>
                                    </tr>';
                            }

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <table width="100%">
    <tr>
        <td width="33%">
            <div style="position: relative;">
                <div style="position: absolute; z-index: 1; bottom: 140px; left: 100px">
                    <img src="<?php echo base_url(); ?>public/img/lab/sello_01.jpg" width="150">
                </div>
                <div style="position: absolute; z-index: 10; bottom: 100px; left: 125px">
                    <img src="<?php echo base_url(); ?>public/img/lab/firma.png" width="75">
                </div>
            </div>
        </td>
        <td width="33%"></td>
        <td width="33%">
            <div style="position: relative;">
                <div style="position: absolute; z-index: 1; bottom: 115px; right: 100px">
                    <img src="<?php echo base_url(); ?>public/img/lab/sello_lab.jpg" width="150">
                </div>
            </div>
        </td>
    </tr>
</table> -->