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
        margin-top: 25px;
    }
    .table{
        border-collapse: collapse;
        margin-top: 25px;
    }
    
    .detalle .table tr th{
        color: #fff;
        font-size: 12px;
        padding: 5px 10px 5px 10px;
    }

    .detalle .table tr td{
        font-size: 12px;
        font-weight: bold;
        height: 25px;
    }
</style>

<div>
    <div class="contenedor">
        <div class="medicamentos">
            
            <div style="border: 2px solid #000000; padding-top: 10px; padding-bottom: 0px;">
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
            
            <p style="font-size: 12px; color: #000000"><strong>RESULTADOS EXAMEN GENERAL DE HECES</strong></p>
            <div class="detalle">
                <table class="table" style="margin-top: -15px;">
                    <thead>
                        <tr style="background: #000000;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($cropologia->colorCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Color</strong></td>
                                        <td style="text-align: center;">'.$cropologia->colorCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->consistenciaCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Consistencia</strong></td>
                                        <td style="text-align: center;">'.$cropologia->consistenciaCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->mucusCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Mucus</strong></td>
                                        <td style="text-align: center;">'.$cropologia->mucusCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->hematiesCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Hematíes</strong></td>
                                        <td style="text-align: center;">'.$cropologia->hematiesCropologia.' x campo</td>
                                    </tr>';
                            }
                            if($cropologia->leucocitosCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Leucocitos</strong></td>
                                        <td style="text-align: center;">'.$cropologia->leucocitosCropologia.' x campo</td>
                                    </tr>';
                            }

                            echo '<tr>
                                    <th colspan="4" style="color: #000000; text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 25px" ><strong>METAZOARIOS</strong></th>
                                </tr>';
                            if($cropologia->ascarisCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Ascaris lumbricoides</strong></td>
                                        <td style="text-align: center;">'.$cropologia->ascarisCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->hymenolepisCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Hymenolepis</strong></td>
                                        <td style="text-align: center;">'.$cropologia->hymenolepisCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->uncinariasCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Uncinarias</strong></td>
                                        <td style="text-align: center;">'.$cropologia->uncinariasCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->tricocefalosCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Trichuris trichiura</strong></td>
                                        <td style="text-align: center;">'.$cropologia->tricocefalosCropologia.'</td>
                                    </tr>';
                            }
                            if($cropologia->larvaStrongyloides != ""){
                                echo '<tr>
                                        <td><strong class="">Larva strongyloides stercoralis </strong></td>
                                        <td style="text-align: center;">'.$cropologia->larvaStrongyloides.'</td>
                                    </tr>';
                            }

                            echo '<tr>
                                    <th colspan="5" style="color: #000000; color: #000000; text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 25px"><strong>PROTOZOARIOS</strong></th>
                                </tr>';
                            echo '<tr>
                                    <td></td>
                                    <td style="text-align: center; color: #000000;"><strong>Quistes</strong></td>
                                    <td style="text-align: center; color: #000000;"><strong>Trofozoitos</strong></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
                            if($cropologia->histolyticaQuistes != "" || $cropologia->histolyticaTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Entamoeba histolytica</strong></td>
                                        <td style="text-align: center;">'.$cropologia->histolyticaQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->histolyticaTrofozoitos.'</td>
                                    </tr>';
                            }
                            if($cropologia->coliQuistes != "" || $cropologia->coliTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Entamoeba coli</strong></td>
                                        <td style="text-align: center;">'.$cropologia->coliQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->coliTrofozoitos.'</td>
                                    </tr>';
                            }
                            if($cropologia->giardiaQuistes != "" || $cropologia->giardiaTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Giardia lamblia</strong></td>
                                        <td style="text-align: center;">'.$cropologia->giardiaQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->giardiaTrofozoitos.'</td>
                                    </tr>';
                            }
                            if($cropologia->blastocystisQuistes != "" || $cropologia->blastocystisTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Blastocystis hominis</strong></td>
                                        <td style="text-align: center;">'.$cropologia->blastocystisQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->blastocystisTrofozoitos.'</td>
                                    </tr>';
                            }
                            if($cropologia->tricomonasQuistes != "" || $cropologia->tricomonasTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Tricomonas hominis</strong></td>
                                        <td style="text-align: center;">'.$cropologia->tricomonasQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->tricomonasTrofozoitos.'</td>
                                    </tr>';
                            }
                            if($cropologia->mesnilliQuistes != "" || $cropologia->mesnilliTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Chilomastix mesnilli</strong></td>
                                        <td style="text-align: center;">'.$cropologia->mesnilliQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->mesnilliTrofozoitos.'</td>
                                    </tr>';
                            }
                            if($cropologia->nanaQuistes != "" || $cropologia->nanaTrofozoitos != ""){
                                echo '<tr>
                                        <td><strong class="">Endolimax nana</strong></td>
                                        <td style="text-align: center;">'.$cropologia->nanaQuistes.'</td>
                                        <td style="text-align: center;">'.$cropologia->nanaTrofozoitos.'</td>
                                    </tr>';
                            }

                            if($cropologia->restosMacroscopicos != "" && $cropologia->restosMicroscopicos != ""){
                                echo '<tr>
                                    <th colspan="4" style="color: #000000; color: #000000; color: #000000; text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 25px"><strong>RESTOS ALIMENTICIOS</strong></th>
                                </tr>';
                                if($cropologia->restosMacroscopicos != ""){
                                    echo '<tr>
                                            <td><strong class="borderAzul">Restos Alimenticios Macroscópicos</strong></td>
                                            <td style="text-align: center;">'.$cropologia->restosMacroscopicos.'</td>
                                        </tr>';
                                }
                                if($cropologia->restosMicroscopicos != ""){
                                    echo '<tr>
                                            <td><strong class="borderAzul">Restos Alimenticios Microscópicos</strong></td>
                                            <td style="text-align: center;">'.$cropologia->restosMicroscopicos.'</td>
                                        </tr>';
                                }
                            }

                        ?>
                       
                    </tbody>
                </table>


                <table class="table" style="margin-top: 10px;">
                    <thead></thead>
                    <tbody>
                        <?php
                            if($cropologia->observacionesCropologia != ""){
                                echo '<tr>
                                        <td><strong class="">Observaciones</strong></td>
                                        <td colspan=3>'.$cropologia->observacionesCropologia.'</td>
                                    </tr>';
                            }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- <div id="imgAgua" style="margin-top: -400px;">  
        <img src="<?php echo base_url(); ?>public/img/logo.png" alt="Logo hospital orellana" id="marcaAgua">
    </div> -->
</div>