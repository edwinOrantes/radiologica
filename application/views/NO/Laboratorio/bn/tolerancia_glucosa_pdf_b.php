<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
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
        /* height: 30px; */
    }

    .detalle .table tr td{
        height: 40px;
    }
</style>

<div>
    <div class="contenedor">
        <div class="medicamentos">
            
            <div style="border: 2px solid #000000; padding-top: 10px; padding-bottom: 15px;">
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
                            <!-- <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta; ?></p></td> -->
                            <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10); ?></p></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            
            <p style="font-size: 12px; color: #000000; margin-top: 25px"><strong>RESULTADO EXAMEN TOLERANCIA A LA GLUCOSA</strong></p>
            <div class="detalle">
                <table class="table">
                    <thead>
                        <tr style="background: #000000;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                            <th> Valores de referencia </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            if($tolerancia->resultado1ToleranciaGlucosa != ""){
                                echo '<tr>
                                        <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px" colspan=4 ><strong>PRIMERA MUESTRA GLUCOSA EN AYUNAS('.$tolerancia->hora1ToleranciaGlucosa.')</strong></td>
                                    </tr>';
                                echo '<tr>
                                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado1ToleranciaGlucosa.' mg/dl</td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">60 - 110 mg/dl</td>
                                    </tr>';
                            }

                            if($tolerancia->resultado2ToleranciaGlucosa != ""){
                                echo '<tr>
                                        <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px" colspan=4 ><strong>1h POST CARGA '.$tolerancia->parametroCarga.'gr DE DEXTROSA('.$tolerancia->hora2ToleranciaGlucosa.')</strong></td>
                                    </tr>';
                                echo '<tr>
                                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado2ToleranciaGlucosa.' mg/dl</td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">Menor de 200 mg/dl</td>
                                    </tr>';
                            }

                            if($tolerancia->resultado3ToleranciaGlucosa != ""){
                                echo '<tr>
                                    <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px" colspan=4 ><strong>2h POST CARGA('.$tolerancia->hora3ToleranciaGlucosa.')</strong></td>
                                </tr>';
                                echo '<tr>
                                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado3ToleranciaGlucosa.' mg/dl</td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">Menor de 140 mg/dl</td>
                                    </tr>';
                            }

                            if($tolerancia->resultado4ToleranciaGlucosa != ""){
                                echo '<tr>
                                        <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px" colspan=4 ><strong>3h POST CARGA('.$tolerancia->hora4ToleranciaGlucosa.')</strong></td>
                                    </tr>';
                                echo '<tr>
                                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado4ToleranciaGlucosa.' mg/dl</td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">70 - 115 mg/dl</td>
                                    </tr>';
                            }


                            if($tolerancia->observacionToleranciaGlucosa != ""){
                                echo '<tr>
                                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Observaciones: </strong></td>
                                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center;" colspan="2">'.$tolerancia->observacionToleranciaGlucosa.'</td>
                                    </tr>';
                            }
                            
                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>