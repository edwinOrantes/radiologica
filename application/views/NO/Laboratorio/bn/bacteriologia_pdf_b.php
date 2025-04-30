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
        font-size: 11px;
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
            <p style="font-size: 12px; color: #000000"><strong>RESULTADOS EXAMEN BACTERIOLOGIA</strong></p>
            <div class="detalle">
                <table class="table">
                    <thead>
                        <tr style="background: #000000;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px" colspan="2"><strong class="borderAzul">Examen realizado: </strong> <?php echo $cabecera->nombreMedicamento; ?></td>
                        </tr>
                        <?php

                            if($bacteriologia->resultadoDirecto != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Resultado directo</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->resultadoDirecto.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->procedenciaCultivo != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Procedencia de la muestra</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->procedenciaCultivo.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->resultadoCultivo != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Resultado cultivo</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->resultadoCultivo.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->cefixime != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Cefixime</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefixime.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->amikacina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Amikacina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->amikacina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->levofloxacina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Levofloxacina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->levofloxacina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->ceftriaxona != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Ceftriaxona</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ceftriaxona.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->azitromicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Azitromicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->azitromicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->imipenem != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Imipenem</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->imipenem.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->meropenem != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Meropenem</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->meropenem.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->fosfocil != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Fosfocil</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->fosfocil.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->ciprofloxacina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Ciprofloxacina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ciprofloxacina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->penicilina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Penicilina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->penicilina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->vancomicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Vancomicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->vancomicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->acidoNalidixico != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Acido Nalidixico</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->acidoNalidixico.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->gentamicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Gentamicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->gentamicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->nitrofurantoina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Nitrofurantoína</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->nitrofurantoina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->ceftazimide != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Ceftazidime</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ceftazimide.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->cefotaxime != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Cefotaxime</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefotaxime.'</td>
                                    </tr>';
                            }

                            if($bacteriologia->clindamicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Clindamicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->clindamicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->trimetropimSulfa != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Trimetropim Sulfa</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->trimetropimSulfa.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->ampicilina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Ampicilina/Sulbactam</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ampicilina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->piperacilina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Piperacilina/Tazobactam</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->piperacilina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->amoxicilina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Amoxicilina Acido Clavulánico</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->amoxicilina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->claritromicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Claritromicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->claritromicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->cefuroxime != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Cefuroxime</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefuroxime.'</td>
                                    </tr>';
                            }

                        // Nuevos antibioticos
                            if($bacteriologia->tetraciclina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Tetraciclina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->tetraciclina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->eritromicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Eritromicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->eritromicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->doxiciclina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Doxiciclina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->doxiciclina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->oxacilina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Oxacilina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->oxacilina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->tobramicina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Tobramicina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->tobramicina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->cefepime != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Cefepime</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefepime.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->norfloxacina != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Norfloxacina</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->norfloxacina.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->cefazolin != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Cefazolin</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefazolin.'</td>
                                    </tr>';
                            }
                            if($bacteriologia->aztreonam != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Aztreonam</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->aztreonam.'</td>
                                    </tr>';
                            }
                        // Nuevos antibioticos



                            if($bacteriologia->observacionesCultivo != ""){
                                echo '<tr>
                                        <td style="padding-left: 50px"><strong class="">Observaciones</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->observacionesCultivo.'</td>
                                    </tr>';
                            }

                            
                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>