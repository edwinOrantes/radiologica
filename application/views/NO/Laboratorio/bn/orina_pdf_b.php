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
        height: 30px;
    }

    .detalle .table tr td{
        height: 27px;
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
            
            <p style="font-size: 12px; color: #000000"><strong>RESULTADO EXAMEN ORINA</strong></p>
            <div class="detalle">
                <table class="table">
                    <thead>
                        <tr style="background: #000000;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                            <th> Unidades </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        // Analisis fisico quimico
                            echo  '<tr>
                                    <td style="text-align: center; background: rgba(0, 0, 0, 0.1);" colspan=4><strong>Análisis Fisico-Quimico </strong></td>
                                </tr>';
                            if($orina->colorOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Color</strong></td>
                                        <td style="text-align: center;">'.$orina->colorOrina.'</td>
                                        <td style="text-align: center;">-</td>
                                    </tr>';
                            }
                            if($orina->aspectoOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Aspecto</strong></td>
                                        <td style="text-align: center;">'.$orina->aspectoOrina.'</td>
                                        <td style="text-align: center;">-</td>
                                    </tr>';
                            }
                            if($orina->densidadOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Densidad</strong></td>
                                        <td style="text-align: center;">'.$orina->densidadOrina.'</td>
                                        <td style="text-align: center;">-</td>
                                    </tr>';
                            }
                            if($orina->phOrina != ""){
                                echo '<tr>
                                        <td><strong class="">pH</strong></td>
                                        <td style="text-align: center;">'.$orina->phOrina.'</td>
                                        <td style="text-align: center;">-</td>
                                    </tr>';
                            }
                            if($orina->proteinasOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Proteínas</strong></td>
                                        <td style="text-align: center;">'.$orina->proteinasOrina.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                    </tr>';
                            }
                            if($orina->glucosaOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Glucosa</strong></td>
                                        <td style="text-align: center;">'.$orina->glucosaOrina.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                    </tr>';
                            }
                            if($orina->sangreOcultaOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Sangre oculta</strong></td>
                                        <td style="text-align: center;">'.$orina->sangreOcultaOrina.'</td>
                                        <td style="text-align: center;">-</td>
                                    </tr>';
                            }
                            if($orina->bilirrubinaOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Bilirrubina</strong></td>
                                        <td style="text-align: center;">'.$orina->bilirrubinaOrina.'</td>
                                        <td style="text-align: center;">mg/dl</td>
                                    </tr>';
                            }
                            if($orina->nitritoOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Nitrito</strong></td>
                                        <td style="text-align: center;">'.$orina->nitritoOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->urobilinogenoOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Urobilinógeno</strong></td>
                                        <td style="text-align: center;">'.$orina->urobilinogenoOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->cuerposCetonicosOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Cuerpos cetónicos</strong></td>
                                        <td style="text-align: center;">'.$orina->cuerposCetonicosOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                        // Analisis fisico quimico
                        // Analisis microscopico
                            echo  '<tr>
                                    <td style="text-align: center; background: rgba(0, 0, 0, 0.1);" colspan=4><strong>Análisis Microscopico </strong></td>
                                </tr>';
                            if($orina->cilindrosOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Cilindros</strong></td>
                                        <td style="text-align: center;">'.$orina->cilindrosOrina.'</td>
                                        <td style="text-align: center;">x campo</td>
                                    </tr>';
                            }
                            if($orina->hematiesOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Hematíes</strong></td>
                                        <td style="text-align: center;">'.$orina->hematiesOrina.'</td>
                                        <td style="text-align: center;">x campo</td>
                                    </tr>';
                            }
                            if($orina->leucocitosOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Leucocitos</strong></td>
                                        <td style="text-align: center;">'.$orina->leucocitosOrina.'</td>
                                        <td style="text-align: center;">x campo</td>
                                    </tr>';
                            }
                            if($orina->celulasEpitelialesOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Células epiteliales</strong></td>
                                        <td style="text-align: center;">'.$orina->celulasEpitelialesOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->cristalesOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Cristales</strong></td>
                                        <td style="text-align: center;">'.$orina->cristalesOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->parasitologicoOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Parasitológico</strong></td>
                                        <td style="text-align: center;">'.$orina->parasitologicoOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->bacteriasOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Bacterias</strong></td>
                                        <td style="text-align: center;">'.$orina->bacteriasOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->grumosOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Grumos leucocitarios</strong></td>
                                        <td style="text-align: center;">'.$orina->grumosOrina.'</td>
                                        <td style="text-align: center;">x campo</td>
                                    </tr>';
                            }
                            if($orina->filamentoOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Filamento mucoide</strong></td>
                                        <td style="text-align: center;">'.$orina->filamentoOrina.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($orina->observacionesOrina != ""){
                                echo '<tr>
                                        <td><strong class="">Observaciones</strong></td>
                                        <td style="text-align: center;">'.$orina->observacionesOrina.'</td>
                                        <td></td>
                                    </tr>';
                            }

                        // Fin analisis microscopico

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>