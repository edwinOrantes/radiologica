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
        height: 50px;
    }
</style>

<div>
    <div class="contenedor">
        <div class="medicamentos">
            <div style="border: 2px solid #00000000; margin: 20px 0px; padding-top: 10px; padding-bottom: 15px;">
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
            
            <p style="font-size: 12px; color: #00000000"><strong>RESULTADO PRUEBAS VARIAS </strong></p>
            <div class="detalle">
                <table class="table">
                    <thead>
                        <tr style="background: #00000000;">
                            <th> Parametro </th>
                            <th> Resultado </th>
                            <!-- <th> Unidades </th> -->
                            <?php
                                if($varios->valorNormalVarios != ""){
                                    echo '<th> Valores de referencia </th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td style="text-align: center; font-size: 12px; background: rgba(0, 0, 0, 0.1); height: 30px" colspan="3"><strong class="borderAzul">Examen realizado: </strong> <?php echo $cabecera->nombreMedicamento; ?></td>
                            </tr>
                        <?php

                            if($varios->muestraVarios != ""){
                                echo '<tr>
                                        <td style="text-align: center;"><strong class="borderAzul">Muestra</strong></td>
                                        <td style="text-align: center;">'.$varios->muestraVarios.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }
                            if($varios->resultadoVarios != ""){
                                if($varios->valorNormalVarios != ""){
                                    echo '<tr>
                                            <td style="text-align: center;"><strong class="borderAzul">Resultado</strong></td>
                                            <td style="text-align: center;">'.$varios->resultadoVarios.'</td>
                                            <td style="text-align: center;">'.$varios->valorNormalVarios.'</td>
                                        </tr>';

                                }else{
                                    echo '<tr>
                                    <td style="text-align: center;"><strong class="borderAzul">Resultado</strong></td>
                                    <td style="text-align: center;">'.$varios->resultadoVarios.'</td>
                                </tr>';
                                    
                                }
                            }
                            if($varios->observacionesVarios != ""){
                                echo '<tr>
                                        <td style="text-align: center;"><strong class="borderAzul">Observaciones</strong></td>
                                        <td style="text-align: center;">'.$varios->observacionesVarios.'</td>
                                        <td style="text-align: center;"></td>
                                    </tr>';
                            }

                        ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>