<style>

    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        background-image: url('public/img/constancias_bg.jpg') ;
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    #cabecera {
        text-align: left;
        width: 100%;
        margin: auto;
        }

    #lateral {
        width: 50%;  /* Este será el ancho que tendrá tu columna */
        float:left; /* Aquí determinas de lado quieres quede esta "columna" */
        text-align: center;
    }
    
    #principal {
        width: 49%;
        float: right;
        }


    /* Para limpiar los floats */
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
        }

    .medicamentos{
        /* text-align: center; */
        font-size: 12px;
        margin: 0 auto;
        margin-top: 100px;
        width: 90%;
    }

    .proveedor .detalle table, .medicamentos .detalle table{
        font-size: 12px;
        margin: auto;
        width: 100%;
    }
    .proveedor .detalle table tr td, .medicamentos .detalle table tr td{
        padding: 2px;
        text-align: center;
        border: 1px solid #000;
    }

    .tabla_detalle{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 5px;
        border: 1px solid #000;
    }

    .detalle table tr{
        font-size: 1px !important
    }

    .mayusculas{
        text-transform: uppercase;
    }

    /* .detalle table tr:nth-child(even){
        background: rgba(11, 153, 208, 0.1);
        color: #FFFFFF;
    } */

    .firma{
        width: 55%;
        margin: 0 auto;
        margin-top: 150px;
    }

</style>

<?php
    $totalISBMGlobal = 0;
    $totalCostoGlobal = 0;
?>
<div id="cabecera" class="clearfix">

    <div id="lateral">
        <p><img src="<?php echo base_url() ?>public/img/logo.png" alt="" width="250"></p>
    </div>

    <div id="principal">
        <table style="text-align: center;">
            <tr>
                <td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
            </tr>
            <tr>
                <td><strong>Sexta calle oriente, #8, Usulután, El Salvador, <br> PBX 2606-6673, 2606-6676</strong></td>
            </tr>
        </table>
    </div>
</div>
<div style="background-color: #0b88c9; height: 3px;"></div>

<?php
$atender = "";
$ingresado = "";
    if($constancia->sexoConstancia == "F"){
        $atender = "ATENDIDA";
        $ingresado = "INGRESADA";
    }else{
        $atender = "ATENDIDO";
        $ingresado = "INGRESADO";
    }
?>

<div class="contenedor">
    <div class="medicamentos">
        <p><b>QUIEN INTERESE:</b></p>
        <br>
        <p style="text-indent: 7em; line-height: 30px; text-transform: uppercase;">POR ESTE MEDIO SE HACE CONSTAR QUE <?php echo $constancia->pacienteConstancia; ?> DE  <?php echo $constancia->edadConstancia; ?>  AÑOS, FUE <?php echo $atender; ?> EN EL HOSPITAL CLINICA ORELLANA EL DIA <?php echo $constancia->diaConstancia; ?> DE <?php echo $constancia->mesConstancia; ?> DEL CORRIENTE AÑO, SIENDO <?php echo $ingresado; ?> PARA REALIZARLE PROCEDIMIENTO QUIRURGICO.</p>
        <p style="line-height: 30px; text-transform: uppercase;">PARA LOS USOS QUE ESTIME CONVENIENTE SE EXTIENDE LA SIGUIENTE CONSTANCIA A LOS <?php echo date("d"); ?>  DIAS DEL MES <?php echo $mesActual; ?>.</p>
    </div>

    <div class="firma">
        <table style="width: 100%;">
            <tr>
                <td colspan=" 2" style="border-bottom: 1px solid #000; font-size:16px; line-height: 20px; font-size:12px; font-weight: bold; text-align: center;">
                    <img src="<?php echo base_url();?>public/img/firma.jpg" alt="Firma">
                </td>
            </tr>
            <tr>
                <td colspan="2"  style="font-size:12px; text-align: center; font-weight: bold;"> ING. JOSE ERNESTO ALVARADO LEMUS </td>
            </tr>

            <tr>
                <td colspan="2"  style="font-size:12px; text-align: center; font-weight: bold;"> GERENTE ADMINISTRATIVO UNION MEDICA, S.A. DE C.V. </td>
            </tr>

            <tr>
                <td colspan=" 2" style="text-align: center;">
                    <img src="<?php echo base_url();?>public/img/sello.jpg" alt="Firma">
                </td>
            </tr>

        </table>
    </div>
</div>