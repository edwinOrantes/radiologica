<style>
    body{
        font-family: Arial, sans-serif;
    }
    .contenedor {
    width: 100%; /* Ancho completo del contenedor */
    overflow: hidden; /* Para evitar problemas con el float */
    }

    .caja {
    width: 50%; /* Cada div ocupa el 50% del contenedor */
    padding: 0; /* Elimina el espaciado interno */
    margin: 0; /* Elimina cualquier margen */
    border: 0; /* Elimina los bordes si no los necesitas */
    box-sizing: border-box; /* Asegura que el padding y borde no afecten el tamaño */
    float: left; /* Alinea los divs uno al lado del otro */
    }

    .caja-abajo {
        clear: both; /* Asegura que este div esté debajo de los otros */
        width: 100%; /* Ocupa el 100% del contenedor */
        padding: 2px; /* Espaciado interno */
    }


    #tabla_emisor{
        width: 100%;
        font-size: 10px;
    }
    

    #tabla_tributaria{
        width: 100%;
        font-size: 9px;
        border-collapse: collapse; /* Elimina el espacio entre celdas */
        background:#e2e2e2;
        
    }

    #tabla_tributaria td {
    padding: 5px 5px 2px 5px; /* Elimina el espaciado dentro de las celdas */
    margin: 0; /* Elimina el margen de las celdas */
    }

    .detalle_venta{
        border: 1px solid #000000;
        height: 425px
    }

    #tabla-detalle{
        width: 100%;
        font-size: 9px;
    }



</style>

<?php
    $datosLocales = unserialize(base64_decode(urldecode($datosLocales)));
    $respuestaHacienda = unserialize(base64_decode(urldecode($respuestaHacienda)));
    $jsonDTE = unserialize(base64_decode(urldecode($jsonDTE)));

    $identificacion = $jsonDTE["dteJson"]["identificacion"];
    $emisor = $jsonDTE["dteJson"]["emisor"];
    $sujetoExcluido = $jsonDTE["dteJson"]["sujetoExcluido"];
    $cuerpoDocumento = $jsonDTE["dteJson"]["cuerpoDocumento"];
    $resumen = $jsonDTE["dteJson"]["resumen"];

?>


<div class="contenedor">

  <div class="caja">
        <img src="<?php echo base_url(); ?>public/img/logo.jpg" width="150" style="margin-top: -20px ">
        <table id="tabla_emisor">
            <tr>
                <th colspan="2">Actividades de hospitales</th>
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><?php echo $emisor["direccion"]["complemento"];?></td>
            </tr>
            <tr>
                <td><strong>Municipio</strong></td>
                <td>USULUTAN ESTE</td>
            </tr>
            <tr>
                <td><strong>Departamento</strong></td>
                <td>Usulután</td>
            </tr>
            <tr>
                <td><strong>Teléfono</strong></td>
                <td><?php echo $emisor["telefono"];?></td>
            </tr>
            <tr>
                <td><strong>Correo</strong></td>
                <td><?php echo $emisor["correo"];?></td>
            </tr>
            <tr>
                <td><strong>Tipo</strong></td>
                <td><?php echo $emisor["codActividad"];?></td>
            </tr>
            <tr>
                <td><strong>NIT</strong></td>
                <td><?php echo $emisor["nit"];?></td>
            </tr>
            <tr>
                <td><strong>NRC</strong></td>
                <td><?php echo $emisor["nrc"];?></td>
            </tr>
        </table>
  </div>


  <div class="caja">

        <table id="tabla_tributaria">
            <tr>
                <th colspan="4" style="padding: 5px; background:#bfbfbf; font-size:10px;">DOCUMENTO TRIBUTARIO ELECTRÓNICO</th>
            </tr>
            <tr>
                <th colspan="4" style="padding: 5px; background:#bfbfbf; font-size: 12px;">FACTURA SUJETO EXCLUIDO</th>
            </tr>
            <tr>
                <td><strong>Código de generación:</strong></td>
                <td colspan="3"><?php echo $datosLocales["cGeneracion"]; ?></td>
            </tr>
            <tr>
                <td><strong>Sello de recepción:</strong></td>
                <td colspan="3">
                    <?php 
                        if($sello != 0){
                            echo $respuestaHacienda->selloRecibido;
                        }else{
                            echo "-";
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Número de control:</strong></td>
                <td colspan="3"><?php echo $datosLocales["dteFactura"];?></td>
            </tr>
            <tr>
                <td><strong>Moneda:</strong></td>
                <td><?php echo $identificacion["tipoMoneda"];?></td>
                <td><strong>Version del Json:</strong></td>
                <td><?php echo $identificacion["version"];?></td>
            </tr>
            <tr>
                <td><strong>Fecha de emisión:</strong></td>
                <td><?php echo $identificacion["fecEmi"];?></td>
                <td><strong>Hora de emisión:</strong></td>
                <td><?php echo $identificacion["horEmi"];?></td>
            </tr>
        </table>
        <?php echo @$qrs; ?>
  </div>



</div>


<div class="caja-abajo">
    <p style="font-size: 9px; width: 100%; border-bottom: 1px solid #000000"><strong>Información del receptor del DTE</strong></p>
    <table id="tabla_emisor">
        <tr>
            <td><strong>Nombre/Razón Social:</strong></td>
            <td style="width: 160px"><?php echo $datosLocales["nombrePaciente"]?></td>
            <td><strong>DUI:</strong></td>
            <td><?php echo $datosLocales["documentoPaciente"]?></td>
            <td><strong>Moneda:</strong></td>
            <td>USD</td>
        </tr>
       
        <tr>
            <td><strong>Correo:</strong></td>
            <td colspan="2"><?php echo $datosLocales["correoPaciente"]?></td>
            <td><strong>Teléfono:</strong></td>
            <td colspan="2"><?php echo $datosLocales["telefonoPaciente"]?></td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong></td>
            <td><?php echo $datosLocales["complementoPaciente"]; ?></td>
            <td><strong>Municipio:</strong></td>
            <td>
                <?php 
                    $mun = explode("-", $datosLocales["codigoMunicipio"]);
                    echo $mun[1]; 
                ?>
            </td>
            <td><strong>Departamento:</strong></td>
            <td>
                <?php 
                    $dep = explode("-", $datosLocales["codigoDepartamento"]);
                    echo $dep[1]; 
                ?>
            </td>
        </tr>
    </table>
</div>

<div class="caja-abajo detalle_venta">
    <p style="text-align: center; font-size: 10px; width: 100%; border-bottom: 1px solid #000000"><strong>Cuerpo del documento</strong></p>
    <table id="tabla-detalle">
        <thead>
            <tr>
                <th>Num. Item</th>
                <th>Cant.</th>
                <th>Unidad de Medida</th>
                <th>Descripción</th>
                <th>Precio Unitario</th>
                <th>Monto Descuento</th>
                <th>Ventas No Sujetas</th>
                <th>Ventas Excentas</th>
                <th>Ventas Gravadas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center">1</td>
                <td style="text-align: center"><?php echo $cuerpoDocumento[0]["cantidad"]; ?></td>
                <td style="text-align: center"><?php echo $cuerpoDocumento[0]["uniMedida"]; ?></td>
                <td style="text-align: center"><?php echo $cuerpoDocumento[0]["descripcion"]; ?></td>
                <td style="text-align: center"><?php echo number_format($resumen["totalCompra"], 2); ?></td>
                <td style="text-align: center"> </td>
                <td style="text-align: center"> </td>
                <td style="text-align: center"> </td>
                <td style="text-align: center"><?php echo number_format($resumen["totalCompra"], 2); ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="caja-abajo">
    <table id="tabla-detalle">
            <tr>
                <th style="text-align: left">Total en letras:</th>
                <th colspan="4"><?php echo $resumen["totalLetras"]?></th>
                <th style="text-align: right">Sumas:</th>
                <th></th>
                <th></th>
                <th><?php echo  number_format($resumen["totalCompra"], 2); ?></th>
            </tr>

            <tr>
                <th style="text-align: left">Entregado por:</th>
                <th colspan="3"> </th>
                <th style="text-align: left">Recibido por:</th>
                <th style="text-align: right">Sub total:</th>
                <th></th>
                <th></th>
                <th><?php echo number_format($resumen["totalCompra"], 2); ?></th>
            </tr>

            <tr>
                <th style="text-align: left">No. de Documento:</th>
                <th colspan="3"> </th>
                <th style="text-align: left">No. de Documento:</th>
                <th style="text-align: right">Renta 10%:</th>
                <th></th>
                <th></th>
                <th><?php echo number_format($resumen["reteRenta"], 2); ?></th>
            </tr>

            <tr>
                <th style="text-align: left">Condición de la operación:</th>
                <th colspan="3">CONTADO </th>
                <th></th>
                <th style="text-align: right">Total a pagar:</th>
                <th></th>
                <th></th>
                <th><?php echo number_format($resumen["totalPagar"], 2); ?></th>
            </tr>

            <tr>
                <th style="text-align: left">Observaciones:</th>
                <th colspan="3"> </th>
                <th></th>
                <th style="text-align: right"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <th></th>
                <th colspan="3"> </th>
                <th></th>
                <th style="text-align: right"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <th></th>
                <th colspan="3"> </th>
                <th></th>
                <th style="text-align: right"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>


    </table>
</div>

