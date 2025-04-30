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
        margin-top: 5px;
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
        height: 35px;
    }
</style>

<div>
    <div class="contenedor">
        <div class="medicamentos">
            
            <div style="border: 2px solid #000000; padding-top: 10px; padding-bottom: 10px;">
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

            <div class="detalle">
                <p style="font-size: 12px; color: #000000; margin-top: 20px"><strong>RESULTADOS EXAMEN INMUNOLOGIA</strong></p>
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

                            echo '<tr>
                                <th colspan="4" style="color: #000000; text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 25px" >
                                    <strong>Examen realizado: '.ucwords(strtolower($cabecera->examenes)).'</strong>
                                </th>
                            </tr>';
                            if($inmunologia->tificoO != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Tífico O</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->tificoO.'</td>
                                        <td style="text-align: center; font-weight: bold">Negativo</td>
                                    </tr>';
                            }
                            if($inmunologia->tificoH != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Tífico H</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->tificoH.'</td>
                                        <td style="text-align: center; font-weight: bold">Negativo</td>
                                    </tr>';
                            }
                            if($inmunologia->paratificoA != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Paratífico A</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->paratificoA.'</td>
                                        <td style="text-align: center; font-weight: bold">Negativo</td>
                                    </tr>';
                            }
                            if($inmunologia->paratificoB != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Paratífico B</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->paratificoB.'</td>
                                        <td style="text-align: center; font-weight: bold">Negativo</td>
                                    </tr>';
                            }
                            if($inmunologia->brucellaAbortus != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Brucella Abortus</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->brucellaAbortus.'</td>
                                        <td style="text-align: center; font-weight: bold">Negativo</td>
                                    </tr>';
                            }
                            if($inmunologia->proteusOx != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Proteus OX-19</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->proteusOx.'</td>
                                        <td style="text-align: center; font-weight: bold">Negativo</td>
                                    </tr>';
                            }
                            if($inmunologia->proteinaC != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Proteína "C" reactiva</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->proteinaC.'</td>
                                        <td style="text-align: center;"><p class="borderAzul">VN: Hasta 6mg/L</p></td>
                                    </tr>';
                            }
                            if($inmunologia->reumatoideo != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Factor Reumatoideo</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->reumatoideo.'</td>
                                        <td style="text-align: center;"><p class="borderAzul">Valor normal: < 8UI/mL</p></td>
                                    </tr>';
                            }
                            if($inmunologia->antiestreptolisina != ""){
                                echo '<tr>
                                        <td><strong class="borderAzul">Antiestreptolisina "O"</strong></td>
                                        <td style="text-align: center; font-weight: bold">'.$inmunologia->antiestreptolisina.'</td>
                                        <td style="text-align: center;"><p class="borderAzul">Valor normal: Hasta 200 UI/mL</p></td>
                                    </tr>';
                            }

                        ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        console.log("yeah ");
    });
</script>