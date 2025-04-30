<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    #cabecera {
        text-align: left;
        width: 80%;
        margin: auto;
        }

    #lateral {
        width: 35%;  /* Este será el ancho que tendrá tu columna */
        float:left; /* Aquí determinas de lado quieres quede esta "columna" */
        padding-top: -25px;
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

    .proveedor, .medicamentos{
        margin-top: 0px;
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
        font-size: 10px;
        text-align: center;
        border-width: 0.1px;
        border-style: solid;
        border-color: #000;
    }

    .detalle table tr:nth-child(even){
        background: rgba(11, 153, 208, 0.1);
        color: #FFFFFF;
    }

</style>
<?php
    $medGlobal = 0;
    $matmeGlobal = 0;
    $servGlobal = 0;
    $oservGlobal = 0;
    $extGlobal = 0;
    foreach ($personas as $persona) {
        if($persona->anulada == 0){
            $id = $persona->idHoja;
            $externosHoja = $this->Hoja_Model->externosHoja($id);
            $medicamentosHoja = $this->Hoja_Model->medicamentosHoja($id);
            foreach ($medicamentosHoja as $medicamento) {
                //$totalGlobalHoja += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);

                // Suma medicamentos y materiales medicos
                switch ($medicamento->tipoMedicamento) {
                    case 'Medicamentos':
                        $medGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                        break;
                    case 'Materiales médicos':
                        $matmeGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                        break;
                    case 'Servicios':
                        $servGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                        break;
                    case 'Otros servicios':
                        $oservGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                        break;
                }
            }

            foreach ($externosHoja as $externo) {
                //$totalGlobalHoja += ($externo->precioExterno * $externo->cantidadExterno);
                $extGlobal+= ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
            }
        }
    }
?>
<div id="cabecera" class="clearfix">

    <div id="lateral">
        <p><img src="<?php echo base_url() ?>public/img/logo.jpg" alt="Logo hospital Orellana" width="225"></p>
    </div>

    <div id="principal">
        <table style="text-align: center;">
            <tr>
                <td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
            </tr>
            <tr>
                <td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
            </tr>
        </table>
    </div>
</div>
<div class="contenedor">
    <div class="medicamentos">
        <p style="margin-top: 0px;"><strong>REPORTE COBROS POR PACIENTES</strong></p>
            <div class="detalle">
                <table cellspacing="0" cellpadding="5">
                    <thead>
                        <tr style="background-color: #007bff;">
                            <td style="color: #fff; text-align: center; font-weight: bold">Fecha de ingreso</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Fecha egreso</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Recibo</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">HC</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Paciente</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Médico</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Medicamentos</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Materiales</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Total</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Servicios</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Otros servicios</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Total</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Externos</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">Total Hoja</td>
                            <td style="color: #fff; text-align: center; font-weight: bold">CF</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            
                            foreach ($personas as $persona) {
                                if($persona->anulada == 0){
                                    echo '<tr>';
                                }else{
                                    echo '<tr style="background-color: rgba(255,0,0,0.2);">';
                                }
                        ?>
                                
                                    <!-- Datos base de la hoja  -->
                                    <td><?php echo $persona->fechaHoja; ?></td>
                                    <td><?php
                                        if($persona->salidaHoja == ""){
                                            echo "---";
                                        }else{
                                            echo $persona->salidaHoja;
                                        }
                                    ?></td>
                                    <td><?php 
                                        if($persona->correlativoSalidaHoja == 0){
                                            echo "---";
                                        }else{
                                            echo $persona->correlativoSalidaHoja;
                                        }
                                    ?></td>
                                    <td><?php echo $persona->codigoHoja; ?></td>    
                                    <td><?php echo $persona->nombrePaciente; ?></td>
                                    <td><?php echo $persona->nombreMedico; ?></td>

                                    <!-- Detalle de la hoja -->
                                    <?php
                                        $id = $persona->idHoja;
                                        $externosHoja = $this->Hoja_Model->externosHoja($id);
                                        $medicamentosHoja = $this->Hoja_Model->medicamentosHoja($id);
                                        $med = 0;
                                        $matme = 0;
                                        $serv = 0;
                                        $oserv = 0;
                                        $ext = 0;
                                        
                                        foreach ($medicamentosHoja as $medicamento) {
                                            //$totalGlobalHoja += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);

                                            // Suma medicamentos y materiales medicos
                                            switch ($medicamento->tipoMedicamento) {
                                                case 'Medicamentos':
                                                    $med += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                                                    //$medGlobal += $med;
                                                    break;
                                                case 'Materiales médicos':
                                                    $matme += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                                                    //$matmeGlobal += $matme;
                                                    break;
                                                case 'Servicios':
                                                    $serv += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                                                    //$serGlobal += $serv;
                                                    break;
                                                case 'Otros servicios':
                                                    $oserv += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                                                    //$oservGlobal += $oserv;
                                                    break;
                                            }
                                        }

                                        foreach ($externosHoja as $externo) {
                                            //$totalGlobalHoja += ($externo->precioExterno * $externo->cantidadExterno);
                                            $ext += ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
                                            //$extGlobal += $ext;
                                        }
                                    // Fin total hoja de cobro.
                                    ?>


                                    <td>$ <?php echo number_format($med, 2); ?></td>
                                    <td>$ <?php echo number_format($matme, 2); ?></td>
                                    <td>$ <?php echo number_format(($med + $matme), 2); ?></td>
                                    <td>$ <?php echo number_format($serv, 2); ?></td>
                                    <td>$ <?php echo number_format($oserv, 2); ?></td>
                                    <td>$ <?php echo number_format(($serv + $oserv), 2); ?></td>
                                    <td>$ <?php echo number_format($ext, 2); ?></td>
                                    <td>$ <?php echo number_format(($med + $matme + $serv + $oserv + $ext), 2); ?></td>
                                    <td>
                                        <?php 
                                            //if($persona->tipoFactura == 2){
                                                echo $persona->credito_fiscal;
                                            //}
                                        ?>
                                    </td>
                                </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="6"><strong>Total</strong></td>
                            <td>$ <?php echo number_format($medGlobal, 2); ?></td>
                            <td>$ <?php echo number_format($matmeGlobal, 2); ?></td>
                            <td>$ <?php echo number_format(($medGlobal + $matmeGlobal), 2); ?></td>
                            <td>$ <?php echo number_format($servGlobal, 2); ?></td>
                            <td>$ <?php echo number_format($oservGlobal, 2); ?></td>
                            <td>$ <?php echo number_format(($servGlobal + $oservGlobal), 2); ?></td>
                            <td>$ <?php echo number_format($extGlobal, 2); ?></td>
                            <td>$ <?php echo number_format(($medGlobal + $matmeGlobal + $servGlobal + $oservGlobal + $extGlobal), 2); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>


