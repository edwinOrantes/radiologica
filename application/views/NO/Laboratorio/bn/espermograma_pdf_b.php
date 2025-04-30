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
        /* height: 21px; */
    }

    .detalle .table tr td{
        height: 21px;
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
            
            <p style="font-size: 12px; color: #000000; margin-top: 25px"><strong>RESULTADO EXAMEN ESPERMOGRAMA</strong></p>
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
                        <tr>
                            <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Examen macroscópico</td>
                        </tr>
                        <?php
                            if($espermograma->colorEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Color</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->colorEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->phEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Ph</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->phEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->volumenEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Volumen</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->volumenEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">ml</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->licuefaccionEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Licuefacción</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->licuefaccionEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->viscocidadEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Viscocidad</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->viscocidadEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->abstinenciaEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Dias de abstinencia</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->abstinenciaEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            
                            echo '<tr>
                                    <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Examen microscópico directo</td>
                                </tr>';
                            if($espermograma->hematiesEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Hematíes</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->hematiesEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">x campo</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->leucocitosEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Leucocitos</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->leucocitosEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">x campo</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->epitelialesEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Células Epiteliales</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->epitelialesEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->bacteriasEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Bacterias</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->bacteriasEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }

                            echo '<tr>
                                    <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Movilidad</td>
                                </tr>';

                            if($espermograma->mprEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Movilidad progresivamente rápida</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->mprEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->mplEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Movilidad progresivamente lenta</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->mplEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->mnpEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Movilidad no progresiva</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->mnpEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->inmovilesEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Inmoviles</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->inmovilesEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            echo '<tr>
                                    <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Recuento</td>
                                </tr>';
                            if($espermograma->recuentoEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Recuento</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->recuentoEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">millones/ml</td>
                                        <td style="text-align: center; font-weight: bold">20-150</td>
                                    </tr>';
                            }
                            echo '<tr>
                                    <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Normalidad</td>
                                </tr>';
                            if($espermograma->normalesEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Normales</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->normalesEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->anormalCbEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Anormal cabeza</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->anormalCbEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->anormalClEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Anormal cola</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->anormalClEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            echo '<tr>
                                    <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Vitalidad</td>
                                </tr>';
                            if($espermograma->vivosEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Vivos</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->vivosEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->muertosEspermograma != ""){
                                echo '<tr>
                                        <td><strong class="">Muertos</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$espermograma->muertosEspermograma.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($espermograma->observacionesEspermograma != ""){
                                echo '<tr>
                                        <td><strong><br>Observaciones</strong></td>
                                        <td style="text-align: center;  font-weight: bold" colspan=3>'.$espermograma->observacionesEspermograma.'</td>
                                    </tr>';
                            }


                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>