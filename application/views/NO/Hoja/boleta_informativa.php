
<style>
    body{
        background-image: url('public/img/test3_bg.jpg') ;
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    .cabecera{
        width: 100%;
    }
    .img_cabecera{
        padding-top: -20px;
        width: 25%;
        width: 225px;
        float: left;
    }
    .title_cabecera{
        float: right;
        line-height: 5px;
        text-align: center;
        width: 60%;
    }

    .subtitle_cabecera{
        clear: both;
        width: 100%;
    }

    .subtitle_cabecera h5{
        font-size: 11px;
        margin-top: 15px;
        text-align: center;
    }

    .paciente{
        width: 100%;
        margin-top: -8px;
        
    }
    .tabla_paciente{
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 12px
    }

    .tabla_paciente tr th, .tabla_paciente tr td{
        height: 30px;
        border-bottom: 1px solid #000000
    }

    .letraMayuscula{
        text-transform: uppercase;
    }

    .cirugia {
        font-size: 16px
    }
</style>

<div class="paciente">
    <h5>DATOS DEL PACIENTE<hr></h5>
    <table class="tabla_paciente" style="font-family: Times New Roman; width: 100%">
    <tr>
            <td><strong>Paciente: </strong></td>
            <td class="" colspan="3"><?php echo $paciente->nombrePaciente; ?></td>
            <td class=""><strong>Edad:</strong></td>
            <td class=""><?php echo $paciente->edadPaciente; ?> años</td>
        </tr>

        <tr>
            <td><strong>Sexo: </strong></td>
            <td colspan="2"><?php echo $paciente->sexoPaciente; ?></td>
            <td><strong>DUI: </strong></td>
            <td colspan="2"><?php echo $paciente->duiPaciente; ?></td>
        </tr>

        <tr>
            <td><strong>Estado civil: </strong></td>
            <td colspan="2"><?php echo $paciente->civilPaciente; ?></td>

            <td><strong>Ocupación: </strong></td>
            <td colspan="2"><?php echo $paciente->profesionPaciente; ?></td>
            
        </tr>

        <tr>
            <td><strong>Domicilio: </strong></td>
            <td colspan="2"><?php echo $paciente->direccionPaciente; ?></td>
            
            <td class=""><strong>Teléfono:</strong></td>
            <td colspan="2"><?php echo $paciente->telefonoPaciente?></td>
        </tr>
        
        <tr>
            <td><strong>Responsable: </strong></td>
            <td colspan="2"><?php echo $resp = !is_null($paciente->nombreResponsable) ? $paciente->nombreResponsable : "---"; ?></td>
            
            <td class=""><strong>Teléfono:</strong></td>
            <td colspan="2"><?php echo $resp = !is_null($paciente->telefonoResponsable) ? $paciente->telefonoResponsable : "---"; ?></td>
        </tr>
        
        <tr>
            <td><strong>Fecha y hora de ingreso: </strong></td>
            <td colspan="5"><?php echo $paciente->fechaHoja." ".date("h:i:s A", strtotime($paciente->hora)); ?></td>
        </tr>

        <tr>
            <td><strong>Ingresado por Dr.: </strong></td>
            <td colspan="5" class=""><?php echo $paciente->nombreMedico; ?></td>
        </tr>

        <tr>
            <td><strong>Diagnóstico inicial: </strong></td>
            <td colspan="5" class=""> </td>
        </tr>
        
        <tr>
            <td colspan="6" class=""> </td>
        </tr>
        <tr>
            <td colspan="6" class=""> </td>
        </tr>
        <tr>
            <td colspan="6" class=""> </td>
        </tr>
        <tr>
            <td colspan="6" class=""> </td>
        </tr>
        <tr>
            <td colspan="6" class=""> </td>
        </tr>
        <tr>
            <td colspan="6" class=""> </td>
        </tr>
        <tr>
            <td><strong>Diagnóstico final: </strong></td>
            <td colspan="5" class=""> </td>
        </tr>
        <tr>
            <td><strong>Tratamiento efectuado: </strong></td>
            <td colspan="5" class=""> </td>
        </tr>
        <tr>
            <td><strong>Cirugía practicada: </strong></td>
            <td colspan="5" class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cirugía menor <input type="checkbox" class="cirugia">  Cirugía  mayor<input type="checkbox" class="cirugia"></td>
        </tr>
        
        <tr>
            <td><strong>Estado en que sale: </strong></td>
            <td colspan="2"></td>
            
            <td><strong>Día de estancia</strong></td>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td class=""><strong>Fecha de salida:</strong></td>
            <td colspan="5"></td>
        </tr>
        

    </table>


    <table class="" style="margin-top: 150px; font-family: Times New Roman;">
        <tr>
            <td style="width: 300px; height:"> </td>
            <td style="text-align: center;">
                <p style="text-decoration: underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <h5><strong>MEDICO</strong></h5>
            </td>
            <td> </td>
        </tr>
    </table>


</div>