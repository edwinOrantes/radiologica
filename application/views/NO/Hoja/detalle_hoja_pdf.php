<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
}
#cabecera {
    text-align: left;
    width: 100%;
    margin: auto;
    }

#lateral {
    width: 35%;  /* Este será el ancho que tendrá tu columna */
    float:left; /* Aquí determinas de lado quieres quede esta "columna" */
    }

#principal {
    width: 59%;
    float: right;
    }
    
/* Para limpiar los floats */
.clearfix:after {
    content: "";
    display: table;
    clear: both;
    }

.medicamentos .detalle table{
    font-size: 12px;
    margin: auto;
    width: 100%;
}
.medicamentos .detalle table tr td{
    padding: 5px;
    text-align: center;

}

</style>


<div id="cabecera" class="clearfix">

    <div id="lateral">
        <p><img src="<?php echo base_url() ?>public/img/logo.png" alt=""></p>
    </div>

    <div id="principal">
        <table>
            <tr>
                <td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
            </tr>
            <tr>
                <td><strong>Sexta calle oriente, #6, Usulután, El Salvador</strong></td>
            </tr>
        </table>
    </div>
</div>

<div class="contenedor">

    <div class="medicamentos">
            <p><strong>DETALLE DE LA PRODUCTOS:</strong></p>
            <div class="detalle">
                <table>
                    <thead>
                        <tr style="background-color: #007bff; color: #fff">
                            <th style="color: #fff">Lote</th>
                            <th style="color: #fff">Fecha vencimiento</th>
                            <th style="color: #fff">Medicamento</th>
                            <th style="color: #fff">Precio</th>
                            <th style="color: #fff">Cantidad</th>
                            <th style="color: #fff">Total</th>
                        </tr>
                    </thead>
                    <tbody>


                        <tr>
                            <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                            <td colspan=""><strong>$</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>


