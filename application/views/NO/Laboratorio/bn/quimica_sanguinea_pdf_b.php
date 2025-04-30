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
        margin-top: -100px;
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
        height: 30px;
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
            
            <p style="font-size: 12px; color: #000000; margin-top: 25px"><strong>RESULTADOS EXAMEN QUIMICA SANGUINEA</p>
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

                            if($sanguinea->glucosaQS != ""){
                                echo '<tr>
                                        <td><strong>Glucosa</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->glucosaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">60-110mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->posprandialQS != ""){
                                echo '<tr>
                                        <td><strong>Glucosa postprandial</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->posprandialQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Menor de 140 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->colesterolQS != ""){
                                echo '<tr>
                                        <td><strong>Colesterol</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->colesterolQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Menor de 200 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->colesterolHDLQS != ""){
                                echo '<tr>
                                        <td><strong>Colesterol HDL</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->colesterolHDLQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Mayor de 35 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->colesterolLDLQS != ""){
                                echo '<tr>
                                        <td><strong>Colesterol LDL</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->colesterolLDLQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Menor de 130 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->trigliceridosQS != ""){
                                echo '<tr>
                                        <td><strong>Triglicéridos</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->trigliceridosQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Menor de 150 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->acidoUricoQS != ""){
                                echo '<tr>
                                        <td><strong>Ácido úrico</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->acidoUricoQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">2.4-7.0 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->ureaQS != ""){
                                echo '<tr>
                                        <td><strong>Urea</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->ureaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">15-45 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->nitrogenoQS != ""){
                                echo '<tr>
                                        <td><strong>Nitrógeno Ureico</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->nitrogenoQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">5-25 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->creatininaQS != ""){
                                echo '<tr>
                                        <td><strong>Creatinina</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->creatininaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">0.5-1.4 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->amilasaQS != ""){
                                echo '<tr>
                                        <td><strong>Amilasa</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->amilasaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">Menor de 90 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->lipasaQS != ""){
                                echo '<tr>
                                        <td><strong>Lipasa</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->lipasaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">Menor de 38 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->fosfatasaQS != ""){
                                echo '<tr>
                                        <td><strong>Fosfatasa alcalina</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->fosfatasaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">Hasta 275 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->tgpQS != ""){
                                echo '<tr>
                                        <td><strong>TGP</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->tgpQS.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">1-40 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->tgoQS != ""){
                                echo '<tr>
                                        <td><strong>TGO</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->tgoQS.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">1-38 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->hba1cQS != ""){
                                echo '<tr>
                                        <td><strong>Hemoglobina glicosilada</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->hba1cQS.'</td>
                                        <td style="text-align: center; font-weight: bold">%</td>
                                        <td style="text-align: center; font-weight: bold">4.5-6.5%</td>
                                    </tr>';
                            }
                            if($sanguinea->proteinaTotalQS != ""){
                                echo '<tr>
                                        <td><strong>Proteína total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->proteinaTotalQS.'</td>
                                        <td style="text-align: center; font-weight: bold">g/dl</td>
                                        <td style="text-align: center; font-weight: bold">6.6-8.3 d/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->albuminaQS != ""){
                                echo '<tr>
                                        <td><strong>Albúmina</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->albuminaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">g/dl</td>
                                        <td style="text-align: center; font-weight: bold">3.5-5.0 g/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->globulinaQS != ""){
                                echo '<tr>
                                        <td><strong>Globulina</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->globulinaQS.'</td>
                                        <td style="text-align: center; font-weight: bold">g/dl</td>
                                        <td style="text-align: center; font-weight: bold">2-3.5 g/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->relacionAGQS != ""){
                                echo '<tr>
                                        <td><strong>Relación A/G</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->relacionAGQS.'</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                        <td style="text-align: center; font-weight: bold">1.2-2.2</td>
                                    </tr>';
                            }
                            if($sanguinea->bilirrubinaTQS != ""){
                                echo '<tr>
                                        <td><strong>Bilirrubina total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->bilirrubinaTQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Hasta 1.1 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->bilirrubinaDQS != ""){
                                echo '<tr>
                                        <td><strong>Bilirrubina directa</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->bilirrubinaDQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">Hasta 0.25 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->bilirrubinaIQS != ""){
                                echo '<tr>
                                        <td><strong>Bilirrubina indirecta</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->bilirrubinaIQS.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold"></td>
                                    </tr>';
                            }
                            if($sanguinea->sodioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Sodio</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->sodioQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">mmol/L</td>
                                        <td style="text-align: center; font-weight: bold">136-148 mmol/L</td>
                                    </tr>';
                            }
                            if($sanguinea->potasioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Potasio</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->potasioQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">mmol/L</td>
                                        <td style="text-align: center; font-weight: bold">3.5-5.3 mmol/L</td>
                                    </tr>';
                            }
                            if($sanguinea->cloroQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Cloro</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->cloroQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">mmol/L</td>
                                        <td style="text-align: center; font-weight: bold">98-107 mmol/L</td>
                                    </tr>';
                            }
                            if($sanguinea->magnesioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Magnesio</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->magnesioQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">1.6-2.5 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->calcioQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Calcio</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->calcioQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">8.5-10.5 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->fosforoQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Fosforo</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->fosforoQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                                        <td style="text-align: center; font-weight: bold">2.5-5.0 mg/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->cpkTQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>CPK Total</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->cpkTQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">0-195 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->cpkMbQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>CPK MB</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->cpkMbQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">Menor a 24 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->ldhQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>LDH</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->ldhQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">U/L</td>
                                        <td style="text-align: center; font-weight: bold">230-460 U/L</td>
                                    </tr>';
                            }
                            if($sanguinea->troponinaQuimicaClinica != ""){
                                echo '<tr>
                                        <td><strong>Troponina I</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$sanguinea->troponinaQuimicaClinica.'</td>
                                        <td style="text-align: center; font-weight: bold">ng/dl</td>
                                        <td style="text-align: center; font-weight: bold">VN: menor a 0.30 ng/dl</td>
                                    </tr>';
                            }
                            if($sanguinea->notaQS != ""){
                                echo '<tr>
                                        <td><strong>Observaciones</strong></td>
                                        <td style="text-align: center; font-weight: bold" colspan=3>'.$sanguinea->notaQS.'</td>
                                    </tr>';
                            }

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>