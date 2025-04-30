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
        font-size: 1px;
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
        font-size: 12px;
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
            
            <p style="font-size: 12px; color: #000000; margin-top: 20px"><strong>RESULTADOS EXAMEN COAGULACION </strong></p>
            <div class="detalle">
                <table class="table">
                    <thead>
                        <tr style="background: #000000;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                            <th> Unidades </th>
                            <th> Valores de referencia </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($coagulacion->tiempoProtombina != ""){
                                echo '<tr>
                                        <td><strong>Tiempo de Protombina</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoProtombina.'</td>
                                        <td style="text-align: center; font-weight: bold">Segundos</td>
                                        <td style="text-align: center; font-weight: bold">10-14</td>
                                    </tr>';
                            }
                            if($coagulacion->tiempoTromboplastina != ""){
                                echo '<tr>
                                        <td><strong>Tiempo Parcial de Tromboplastina</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoTromboplastina.'</td>
                                        <td style="text-align: center; font-weight: bold">Segundos</td>
                                        <td style="text-align: center; font-weight: bold">20-33</td>
                                    </tr>';
                            }
                            if($coagulacion->fibrinogeno != ""){
                                echo '<tr>
                                        <td><strong>Fibrinógeno</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$coagulacion->fibrinogeno.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">200-400 mg/dl</td>
                                    </tr>';
                            }
                            if($coagulacion->inr != ""){
                                echo '<tr>
                                        <td><strong>INR</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$coagulacion->inr.'</td>
                                        <td style="text-align: center; font-weight: bold">-</td>
                                        <td style="text-align: center; font-weight: bold">-</td>
                                    </tr>';
                            }
                            if($coagulacion->tiempoSangramiento != ""){
                                echo '<tr>
                                        <td><strong>Tiempo de sangramiento</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoSangramiento.'</td>
                                        <td style="text-align: center; font-weight: bold">Minutos</td>
                                        <td style="text-align: center; font-weight: bold">1-4</td>
                                    </tr>';
                            }
                            if($coagulacion->tiempoCoagulacion != ""){
                                echo '<tr>
                                        <td><strong>Tiempo de coagulación</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoCoagulacion.'</td>
                                        <td style="text-align: center; font-weight: bold">Minutos</td>
                                        <td style="text-align: center; font-weight: bold">4-9</td>
                                    </tr>';
                            }
                            if($coagulacion->observacion != ""){
                                echo '<tr>
                                        <td><strong>Observación</strong></td>
                                        <td style="text-align: center; font-weight: bold" colspan=3>'.$coagulacion->observacion.'</td>
                                    </tr>';
                            }
                            
                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>