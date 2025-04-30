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
                            <!-- <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta; ?></p></td> -->
                            <td><p class="borderAzul"><?php echo substr($cabecera->fechaDetalleConsulta, 0, 10); ?></p></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            
            <p style="font-size: 12px; color: #000000; margin-top: 25px"><strong>RESULTADO EXAMEN TOXOPLASMOSIS IGG/IGM</strong></p>
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

                            if($toxoplasma->iggToxoplasma != ""){
                                echo '<tr>
                                        <td style="padding-top: 15px;"><strong class="">Toxoplasma IgG</strong></td>
                                        <td style="padding-top: 15px; text-align: center; font-weight: bold">'.$toxoplasma->iggToxoplasma.' UI/ML</td>
                                        <td style="padding-top: 15px; text-align: center; font-weight: bold">
                                            <p><strong>Negativo: </strong> Menor de 4.0 UI/ML</p>
                                            <p><strong>Positivo: </strong> Mayor de 8.0 UI/ML</p>
                                            <p><strong>Zona gris: </strong> 4.0-8.0 UI/ML</p>

                                        </td>
                                    </tr>';
                            }

                            if($toxoplasma->igmToxoplasma != ""){
                                echo '<tr>
                                        <td style="padding-top: 15px;"><strong class="">Toxoplasma IgM</strong></td>
                                        <td style="padding-top: 15px; text-align: center; font-weight: bold">'.$toxoplasma->igmToxoplasma.' UI/ML</td>
                                        <td style="padding-top: 15px; text-align: center; font-weight: bold">
                                            <p><strong>Negativo: </strong> Menor de 0.9 UI/ML</p>
                                            <p><strong>Positivo: </strong> Mayor de 1.1 UI/ML</p>
                                            <p><strong>Zona gris: </strong> 0.9-1.1 UI/ML</p>
                                        </td>
                                    </tr>';
                                }
    
                                if($toxoplasma->observacionesToxoplasma != ""){
                                    echo '<tr>
                                            <td style="padding-top: 15px;"><strong class="">Observaciones</strong></td>
                                            <td style="padding-top: 15px; text-align: left; font-weight: bold" colspan="2">'.$toxoplasma->observacionesToxoplasma.' </td>
                                        </tr>';
                                }
                                    
                            ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>