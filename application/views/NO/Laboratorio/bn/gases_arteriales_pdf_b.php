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
                            <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta; ?></p></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            
            <p style="font-size: 12px; color: #000000; margin-top: 25px"><strong>RESULTADO EXAMEN GASES ARTERIALES</strong></p>
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

                            if($arteriales->muestraGasesArteriales != ""){
                                echo '<tr>
                                        <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px"  colspan=4 ><strong>Muestra: </strong>'.$arteriales->muestraGasesArteriales.'</td>
                                    </tr>';
                            }
                            if($arteriales->phGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">PH</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$arteriales->phGasesArteriales.'</td>
                                        <td style="text-align: center; font-weight: bold">7.20 - 7.60</td>
                                    </tr>';
                            }
                            if($arteriales->pco2GasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">PCO2</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->pco2GasesArteriales.' mmHg</td>
                                        <td style="text-align: center;  font-weight: bold">30.0 - 50.0 mmHg </td>
                                    </tr>';
                            }
                            if($arteriales->po2GasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">PO2</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->po2GasesArteriales.' mmHg</td>
                                        <td style="text-align: center;  font-weight: bold">80.0 - 100.0 mmHg</td>
                                    </tr>';
                            }
                            if($arteriales->naGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">NA+</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->naGasesArteriales.' mmol/L</td>
                                        <td style="text-align: center;  font-weight: bold">135.0 -145.0 mmol/L</td>
                                    </tr>';
                            }
                            if($arteriales->kGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">K+</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->kGasesArteriales.' mmol/L</td>
                                        <td style="text-align: center;  font-weight: bold">3.50 - 5.10 mmol/L</td>
                                    </tr>';
                            }
                            if($arteriales->caGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">Ca++</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->caGasesArteriales.' mmol/L</td>
                                        <td style="text-align: center;  font-weight: bold">1.13 - 1.32 mmol/L</td>
                                    </tr>';
                            }
                            if($arteriales->tbhGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">tHb</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->tbhGasesArteriales.' gr/dl</td>
                                        <td style="text-align: center;  font-weight: bold">12.0 - 17.0 gr/dl</td>
                                    </tr>';
                            }
                            if($arteriales->soGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">S02</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->soGasesArteriales.' %</td>
                                        <td style="text-align: center;  font-weight: bold">90.0 - 100.0%</td>
                                    </tr>';
                            }
                            if($arteriales->fioGasesArteriales != ""){
                                echo '<tr>
                                        <td><strong class="">FIO2</strong></td>
                                        <td style="text-align: center;  font-weight: bold">'.$arteriales->fioGasesArteriales.' %</td>
                                        <td style="text-align: center;  font-weight: bold"></td>
                                    </tr>';
                            }
                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>