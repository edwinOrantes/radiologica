<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        padding: 0;
        margin: 0;
    }
.eye{
  position:absolute;
  height:200px;
  width:200px;
  top: 40px;
  left : 40px;
  z-index: 1;
}

.heaven
{
  position:absolute;
  height:300px;
  width:300px;
  z-index: -1;
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
        height: 26px;
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
            
            <p style="font-size: 12px; color: #000000; margin-top: 25px"><strong>RESULTADO EXAMEN HEMATOLOGIA</strong></p>
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
                            // Valores de referencia segun edad
                                $ns = "";
                                $nb = "";
                                $linfocitos = "";
                                $eosinofilos = "";
                                $basofilo = "";
                                $monocitos = "";
                                if($cabecera->edadPaciente >= 1 && $cabecera->edadPaciente <= 8){
                                    $ns = "20-45";
                                    $nb = "0-4";
                                    $linfocitos = "40-60";
                                    $eosinofilos = "1-5";
                                    $basofilo = "0-1";
                                    $monocitos = "2-8";
                                }else{
                                    if($cabecera->edadPaciente > 8){
                                        $ns = "50-70";
                                        $nb = "0-5";
                                        $linfocitos = "20-40";
                                        $eosinofilos = "0-5";
                                        $basofilo = "0-1";
                                        $monocitos = "2-8";
                                    }
                                }
                            // Fin valores de referencia segun edad

                            if($hematologia->eritrocitosHematologia != ""){
                                echo '<tr>
                                        <td><strong class="">Eritrocitos</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$hematologia->eritrocitosHematologia.'</td>
                                        <td style="text-align: center; font-weight: bold">4-6 millones</td>
                                    </tr>';
                                    
                                }
                                if($hematologia->hematocritoHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Hematócrito</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->hematocritoHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">37-45%</td>
                                        </tr>';
                                }
                                if($hematologia->hemoglobinaHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Hemoglobina</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->hemoglobinaHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold"> 12-15 g/dl</td>
                                        </tr>';
                                }
                                if($hematologia->vgmHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">VCM</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->vgmHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold"> 82-96 fl</td>
                                        </tr>';
                                }
                                if($hematologia->hgmHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">HCM</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->hgmHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">27-32 pg</td>
                                        </tr>';
                                }
                                if($hematologia->chgmHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">CHCM</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->chgmHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold"> 30-35 g/dl</td>
                                        </tr>';
                                }
                                echo '<tr>
                                        <td style="font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 5px" colspan="3"></td>
                                    </tr>';
                                if($hematologia->leucocitosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Leucocitos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->leucocitosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">5-10 mil</td>
                                        </tr>';
                                }
                                if($hematologia->neutrofHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Neutrofilos segmentados</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->neutrofHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">'.$ns.'%</td>
                                        </tr>';
                                }
                                if($hematologia->neutrofBandHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Neutrofilos en banda</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->neutrofBandHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">'.$nb.'%</td>
                                        </tr>';
                                }
                                if($hematologia->linfocitosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Linfocitos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->linfocitosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">'.$linfocitos.'%</td>
                                        </tr>';
                                }
                                if($hematologia->eosinofilosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Eosinófilos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->eosinofilosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">'.$eosinofilos.'%</td>
                                        </tr>';
                                }
                                if($hematologia->monocitosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Monocitos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->monocitosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">'.$monocitos.'%</td>
                                        </tr>';
                                }
                                if($hematologia->basofilosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Basófilos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->basofilosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">'.$basofilo.'%</td>
                                        </tr>';
                                }
                                if($hematologia->blastosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Blastos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->blastosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">%</td>
                                        </tr>';
                                }
                                if($hematologia->reticulocitosHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Reticulocitos</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->reticulocitosHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">0.5-2.0%</td>
                                        </tr>';
                                }
                                if($hematologia->eritrosedHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Eritrosedimentación</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->eritrosedHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">0-20 mm/hr</td>
                                        </tr>';
                                }
                                echo '<tr>
                                    <td style="font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 5px" colspan="3"></td>
                                </tr>';
                                if($hematologia->plaquetasHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Plaquetas</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->plaquetasHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold">150,000-450,000 xmmc</td>
                                        </tr>';
                                }
                                if($hematologia->gotaGruesaHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Gota gruesa</strong></td>
                                            <td style="text-align: center;  font-weight: bold">'.$hematologia->gotaGruesaHematologia.'</td>
                                            <td style="text-align: center;  font-weight: bold"></td>
                                        </tr>';
                                }
                                if($hematologia->rojaHematologia == "" && $hematologia->blancaHematologia == "" && $hematologia->plaquetariaHematologia == ""){
                                    echo '<tr></tr>';
                                }else{
                                    echo '<tr>
                                            <td colspan=3 style="text-align: center; background: rgba(0, 0, 0, 0.1);"><strong class="">Frotis de sangre periferica</strong></td>
                                        </tr>';        
                                }
    
                                if($hematologia->rojaHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Linea roja</strong></td>
                                            <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->rojaHematologia.'</td>
                                        </tr>';
                                }
                                if($hematologia->blancaHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Linea blanca</strong></td>
                                            <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->blancaHematologia.'</td>
                                        </tr>';
                                }
                                if($hematologia->plaquetariaHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Linea plaquetaria</strong></td>
                                            <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->plaquetariaHematologia.'</td>
                                        </tr>';
                                }
    
                                echo '<tr>
                                    <td style="font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 5px" colspan="3"></td>
                                </tr>';
    
                                if($hematologia->observacionesHematologia != ""){
                                    echo '<tr>
                                            <td><strong class="">Observaciones</strong></td>
                                            <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->observacionesHematologia.'</td>
                                        </tr>';
                                }

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>