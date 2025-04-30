<!-- Scripts PHP para avisos -->
    <?php if($this->session->flashdata("exito")):?>
    <script type="text/javascript">
    $(document).ready(function() {
        toastr.remove();
        toastr.options.positionClass = "toast-top-center";
        toastr.success('<?php echo $this->session->flashdata("exito")?>', 'Aviso!');
    });
    </script>
    <?php endif; ?>

    <?php if($this->session->flashdata("error")):?>
    <script type="text/javascript">
    $(document).ready(function() {
        toastr.remove();
        toastr.options.positionClass = "toast-top-center";
        toastr.error('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
    });
    </script>
    <?php endif; ?>

<!-- Contenido principal -->
<?php

    // Total de la hoja de cobro.
        $totalMedicamentos = 0;
        $totalExternos = 0;
        $totalExternosCabecera = 0;
        $totalGlobalHoja = 0;
        $med = 0;
        $serm = 0;
        foreach ($medicamentosHoja as $medicamento) {
            $totalGlobalHoja += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);

            // Suma medicamentos y materiales medicos
            switch ($medicamento->tipoMedicamento) {
                case 'Medicamentos':
                    $med += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Materiales médicos':
                    $med += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Servicios':
                    $serm += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
                case 'Otros servicios':
                    $serm += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                    break;
            }
        }

        foreach ($externosHoja as $externo) {
            $totalGlobalHoja += ($externo->precioExterno * $externo->cantidadExterno);
            $totalExternosCabecera += ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
        }
    // Fin total hoja de cobro.
    
?>

<?php
$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
$precio = 25;
$dia = date("w");

//echo $dias[date("w")];
$hora = date('H:i:s');  
$ampm = date('a');
$normal = false;  

    if($dia >= 1 && $dia <= 5){
        
        switch ($hora) {
            case $hora > "07:00:00" && $ampm == "am":
                $normal = 1;
                break;
            case $hora < "16:00:00" && $ampm == "pm":
                $normal = 1;
                break;
            case $hora < "07:00:00" && $ampm == "am":
                if($dia == 1){						
                    $normal = 2;
                }else{
                    $normal = 0;
                }
                break;
            case $hora > "16:00:00" && $ampm == "pm":
                $normal = 0;
                break;
            default:
                echo "Son las ".$hora;
                break;
        }

        
    }else{
        if($dia == 6){
            switch ($hora) {
                case $hora < "07:00:00" && $ampm == "am":
                    $normal = 0;
                    break;
                case $hora > "07:00:00" && $ampm == "am":
                    $normal = 1;
                    break;
                case $hora > "12:00:00" && $ampm == "pm":
                    $normal = 2;
                    break;
                default:
                    echo "Son las ".$hora;
                    break;
            }
        }
        if($dia == 0){
            echo $normal = 2;
        }
        if($dia == 1){
            if($hora < "07:00:00" && $ampm == "am"){
                $normal = 2;
            }
        }
    }
?>
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!--<nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-file "></i> Hoja</a></li>
                        <li class="breadcrumb-item"><a href="#">Detalle hoja</a></li>
                    </ol>
                </nav>-->

            <div class="ms-panel">

                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?php
                                if($paciente->anulada == 0){
                            ?>
                                <!-- Si la hoja esta abierta o no -->
                                    <?php
                                        if($paciente->estadoHoja == 1){
                                    ?>
                                    <a href="#medicamentos" onclick="cambiar(1)" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-medkit"></i> Medicamentos e insumos</a>
                                    <a href="#externos" onclick="cambiar(2)" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-user-md"></i> Servicios externos</a>
                                    <a href="<?php echo base_url(); ?>Hoja/resumen_hoja/<?php echo $paciente->idHoja; ?>" class="btn btn-primary text-white" target="blank"><i class="fa fa-file-pdf"></i> Ver resumen</a>
                                    
                                    <?php
                                            if($paciente->tipoHoja == "Ambulatorio"){
                                                echo '<a href="#cerrarHoja" onclick="cerrarHoja()" data-toggle="modal" class="btn btn-outline-danger"><i class="fa fa-times"></i> Cerrar Cuenta</a>';
                                            }else{
                                                echo '<a href="#cerrarHoja" data-toggle="modal" class="btn btn-outline-danger"><i class="fa fa-times"></i> Cerrar Cuenta</a>';
                                            }
                                            echo ' <a href="#anularHoja" data-toggle="modal" class="btn btn-outline-danger"><i class="fa fa-ban"></i> Anular</a>';
                                        }else{
                                    ?>


                                    <!-- Para anexar expediente -->
                                        <?php
                                            if($this->session->userdata('tipo') == 1){
                                                if(isset($expediente->nombreExpediente)){
                                                    echo ' <a href="#actualizarExpediente" data-toggle="modal" class="btn btn-primary text-white" target="blank"><i class="fa fa-file-pdf"></i> Actualizar expediente</a> ';
                                                }else{
                                                    echo ' <a href="#anexarExpediente" data-toggle="modal" class="btn btn-primary text-white" target="blank"><i class="fa fa-file-pdf"></i> Agregar expediente</a> ';
                                                }
                                            }
                                        ?>
                                    <!-- Fin anexar expediente -->
                                    <!-- Seccion mostrada solo a cajera -->
                                    <?php
                                        if( $this->session->userdata("acceso_nombre") == "Cajera" || $this->session->userdata("acceso_nombre") == "Administrador"){
                                    ?>
                                        <?php
                                            if(isset($tipoFacturaHoja)){
                                                if($tipoFacturaHoja == 1){
                                                    echo '<a href="#consumidorFinal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Consumidor final</a>';
                                                }else{
                                                    echo '<a href="#creditoFiscal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Crédito fiscal</a>';
                                                }
                                            }else{
                                        ?>
                                        <a href="#creditoFiscal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Crédito fiscal</a>
                                        <a href="#consumidorFinal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Consumidor final</a>
                                    <?php } ?>
                                    <!-- Fin seccion solo cajera -->
                                    <?php } ?>

                                    <a href="<?php echo base_url(); ?>Hoja/recibo_hoja/<?php echo $paciente->idHoja; ?>" class="btn btn-primary text-white" target="blank"><i class="fa fa-print"></i> Recibo</a>
                                    <a href="<?php echo base_url(); ?>Hoja/resumen_hoja/<?php echo $paciente->idHoja; ?>" class="btn btn-primary text-white" target="blank"><i class="fa fa-file-pdf"></i> Ver resumen</a>
                                    <a href="#restaurarHoja" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-sync"></i> Restaurar</a>
                                    <!-- <a href="#anularHoja" data-toggle="modal" class="btn btn-danger text-white"><i class="fa fa-ban"></i> Anular</a> -->

                                    <?php } ?>
                                <!-- Fin hoja abierta o no -->
                            <?php 
                                }else{
                                    echo '<h5 class="text-center text-danger text-uppercase">Hoja de cobro anulada</h5>';
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="ms-panel-body">
                    <!-- Inicio cabecera de la hoja -->
                    <div class="alert-primary p-2 table-responsive bordeContenedor pt-3">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Paciente:</strong></td>
                                <td><?php echo $paciente->nombrePaciente; ?></td>
                                <td><strong>DUI:</strong></td>
                                <td><?php echo $paciente->duiPaciente; ?> <input type="hidden" value="<?php echo $paciente->duiPaciente; ?>" id="duiCabecera"/></td>
                                <td><strong>Código hoja:</strong></td>
                                <td><?php echo $paciente->codigoHoja; ?></td>
                            </tr>

                            <tr>
                                <td><strong>Medico:</strong></td>
                                <td><?php echo $paciente->nombreMedico; ?></td>
                                <td><strong>Habitación:</strong></td>
                                <td><?php 
                                    if(isset($paciente->numeroHabitacion)){
                                        echo $paciente->numeroHabitacion;
                                    }else{
                                        echo "Sin asignar";
                                    }                          
                                ?></td>
                                <td><strong>Estado Hoja:</strong></td>
                                <td>
                                    <?php
                                        switch ($paciente->estadoHoja) {
                                            case '0':
                                                echo "Cerrada";
                                                break;
                                            case '1':
                                                echo "Pendiente";
                                                break;
                                            case '2':
                                                echo "Anulada";
                                                break;
                                            
                                            default:
                                                echo "None";
                                                break;
                                        }
                                            
                                            ?>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Fecha de ingreso:</strong></td>
                                <td><?php echo $paciente->fechaHoja; ?></td>
                                <td><strong>Fecha salida:</strong></td>
                                <td>
                                    <?php
                                            if($paciente->salidaHoja == ""){
                                                echo "Aun ingresado";
                                            }else{
                                                echo $paciente->salidaHoja;
                                            }
                                        ?>
                                </td>
                                <!--  <?php
                                       /*  $colorEstado = "";
                                        switch ($paciente->estadoHoja) {
                                            case '0':
                                                $colorEstado = "btn btn-outline-danger btn-sm";
                                                $title = "Esta cuenta esta cerrada";
                                                break;
                                            case '1':
                                                $colorEstado = "btn btn-outline-success btn-sm";
                                                $title = "Esta cuenta aún esta abierta";
                                                break;
                                            default:
                                                $colorEstado = "btn btn-outline-warning btn-sm";
                                                $title = "Esta cuenta esta cancelada";
                                                break;
                                        } */
                                    ?> -->
                                <td><strong>Tipo:</strong></td>
                                <td><?php echo $paciente->tipoHoja; ?></td>
                            </tr>

                            <tr>
                                <td><button type="button" class="btn btn-info btn-sm py-3 has-icon" readonly><i class="fa fa-money-check-alt fa-sm"></i>Medicamentos $<?php echo number_format($med, 2);  ?></button></td>
                                <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Servicios internos $<?php echo number_format($serm, 2);  ?></button></td>
                                <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Total interno $<?php echo number_format(($med + $serm), 2);  ?></button></td>
                                <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Total externo: $<?php echo number_format($totalExternosCabecera, 2);  ?> </button></td>
                                <td><button type="button" class="btn btn-primary btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Total hoja: $<?php echo number_format($totalGlobalHoja, 2);  ?> </button></td>
                                <input type="hidden" value="<?php echo $totalGlobalHoja;  ?>" id="totalGlobalCuenta">
                            </tr>
                            <?php
                                    if(isset($expediente->nombreExpediente)){
                                        echo '<tr>
                                                <td><a href="#verExpediente" data-toggle="modal" class="text-danger" target="_blank" rel="noopener noreferrer"> <i class="fa fa-file-pdf"></i> Ver contrato</a></td>
                                            </tr>';
                                    }
                                ?>


                        </table>
                    </div>
                    <!-- Fin cabecera de la hoja -->

                    <!-- Detalle de medicamentos y externos -->
                    <div class="">
                        <div class="col-md-12">
                            <div class="ms-panel ms-panel-fh">
                                <div class="ms-panel-header">
                                </div>
                                <div class="ms-panel-body clearfix">
                                    <form action="<?php echo base_url() ?>Hoja/guardar_detalle_hoja" method="post"
                                        class="needs-validation" novalidate>
                                        <input type="hidden" value="<?php echo $paciente->idHoja; ?>" name="idHoja">
                                        <input type="hidden" value="<?php echo $paciente->fechaHoja; ?>" name="fechaHoja">
                                        <ul class="nav nav-tabs tabs-bordered d-flex nav-justified mb-4" role="tablist">
                                            <li role="presentation"><a href="#tab1" id="control1" aria-controls="tab1" class="active show pivote" role="tab" data-toggle="tab" aria-selected="true">Medicamentos e insumos</a></li>
                                            <li role="presentation"><a href="#tab2" id="control2" aria-controls="tab2" role="tab" data-toggle="tab" class="pivote" aria-selected="false">Servicios externos </a></li>
                                        </ul>

                                        <div class="tab-content">

                                            <div role="tabpanel" class="tab-pane fade in active show" id="tab1">
                                                <div class="medicamentosContainer">
                                                    <table class="table table-hover thead-primary tblM tablaMedicamentos" id="tblMedicamentos">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Medicamento</th>
                                                                <th class="text-center">Precio</th>
                                                                <th class="text-center">Cantidad</th>
                                                                <th class="text-center">Total</th>

                                                                <?php
                                                                    if($paciente->estadoHoja == 1){
                                                                        echo '<th class="text-center">Opción</th>';
                                                                    }
                                                                ?>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php
                                                                  foreach ($medicamentosHoja as $medicamento) {
                                                                    $id ='"'.$medicamento->idHojaInsumo.'"';
                                                                    $idM ='"'.$medicamento->idMedicamento.'"';
                                                                    $stock ='"'.$medicamento->stockMedicamento.'"';
                                                                    $usados ='"'.$medicamento->usadosMedicamento.'"';
                                                                    $cantidad ='"'.$medicamento->cantidadInsumo.'"';
                                                                    $precio ='"'.$medicamento->precioInsumo.'"';
                                                                    $tipo ='"'.$medicamento->tipoMedicamento.'"';
                                                                    $totalMedicamentos += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                                                                    $transaccion ='"'.$medicamento->idHojaInsumo.'"';
                                                                    $hoja ='"'.$paciente->idHoja.'"';
                                                                ?>

                                                            <tr>
                                                                <td class="text-center"><?php echo $medicamento->nombreMedicamento; ?> </td>
                                                                <td class="text-center precioMedicamento">$ <?php echo $medicamento->precioInsumo; ?> </td>
                                                                <td class="text-center"><?php echo $medicamento->cantidadInsumo; ?></td>
                                                                <td class="text-center"> $ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo), 2); ?>
                                                                    <input value="<?php echo ($medicamento->cantidadInsumo * $medicamento->precioInsumo); ?>" type="hidden" class="totalUMedicamento" required>
                                                                </td>

                                                                <?php
                                                                        if($paciente->estadoHoja == 1){
                                                                    ?>

                                                                <td class="text-center">
                                                                    <?php
                                                                            echo "<a onclick='actualizarMedicamentos($id, $idM, $stock, $usados, $cantidad, $precio, $tipo, $transaccion)' href='#actualizarMedicamentos' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                                            //echo "<a onclick='eliminarMedicamentos($id, $idM, $stock, $usados, $cantidad, $precio, $tipo, $transaccion)' href='#eliminarMedicamentos' class='btnEli' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                            echo "<a onclick='eliminarMedicamentos($id, $idM, $stock, $usados, $cantidad, $precio, $tipo, $transaccion, $hoja)' class='eliminarDetalle' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                        ?>
                                                                </td>

                                                                <?php } ?>

                                                            </tr>

                                                            <?php } ?>

                                                            <tr id="totalMedicamentos">
                                                                <td colspan="3" class="text-right"><strong>TOTAL INSUMOS
                                                                        Y MEDICAMENTOS</strong></td>
                                                                <td class="text-center">
                                                                    <!-- <label id="lblTotalMedicamentos">$ <?php //echo $totalMedicamentos; ?></label> -->
                                                                    <label><strong>
                                                                            <p id="lblTotalMedicamentos">
                                                                                <?php echo "$ ".number_format($totalMedicamentos, 2); ?>
                                                                            </p>
                                                                        </strong></label>
                                                                    <div class="input-group">
                                                                        <input type="hidden" id="txtTotalMedicamentos" value="0" name="txtTotalMedicamentos" class="txtTotalRadiologico form-control" required>
                                                                        <div class="invalid-tooltip">
                                                                            No se pueden enviar datos vacios.
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                                <div class="alert alert-danger medicamentosContainerNull">
                                                    <h6 class="text-center"><strong>No hay medicamentos o insumos que
                                                            mostrar.</strong></h6>
                                                </div>
                                            </div>

                                            <div role="tabpanel" class="tab-pane fade" id="tab2">
                                                <div class="externosContainer">
                                                    <table class="table table-hover thead-primary tablaMedicamentos" id="tblExternos">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Servicio</th>
                                                                <th class="text-center">Precio</th>
                                                                <th class="text-center">Cantidad</th>
                                                                <th class="text-center">Total</th>
                                                                <?php
                                                                    if($paciente->estadoHoja == 1){
                                                                        echo '<th class="text-center">Opción</th>';
                                                                    }
                                                                ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                    foreach ($externosHoja as $externo) {
                                                                        $idHojaExterno ='"'.$externo->idHojaExterno.'"';
                                                                        $nombre ='"'.$externo->nombreExterno.'"';
                                                                        $precio ='"'.$externo->precioExterno.'"';
                                                                        $cantidad ='"'.$externo->cantidadExterno.'"';
                                                                        $totalExternos += ($externo->precioExterno * $externo->cantidadExterno);
                                                                ?>
                                                            <tr">
                                                                <td class="text-center"><?php echo $externo->nombreExterno; ?></td>
                                                                <td class="text-center precioExterno">$ <?php echo $externo->precioExterno; ?></td>
                                                                <td class="text-center"><?php echo $externo->cantidadExterno; ?></td>
                                                                <td class="text-center">$ <?php echo number_format(($externo->precioExterno * $externo->cantidadExterno), 2); ?>
                                                                    <input value="<?php echo ($externo->precioExterno * $externo->cantidadExterno); ?>" type="hidden" class="totalUExterno" required>
                                                                </td>

                                                                <?php
                                                                            if($paciente->estadoHoja == 1){
                                                                        ?>
                                                                <td class="text-center">
                                                                    <?php
                                                                                echo "<a onclick='actualizarExternos($idHojaExterno, $nombre, $precio, $cantidad)' href='#actualizarExternos' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                                                echo "<a onclick='eliminarExternos($idHojaExterno, $nombre, $precio, $cantidad)' href='#eliminarExternos' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                            ?>
                                                                </td>

                                                                <?php } ?>

                                                                </tr>
                                                                <?php } ?>
                                                                <tr id="totalExternos">
                                                                    <td colspan="3" class="text-right"><strong>TOTAL EXTERNOS</strong></td>
                                                                    <td class="text-center">
                                                                        <!-- <label id="lblTotalExternos">$0.00</label> -->
                                                                        <label>
                                                                            <strong>
                                                                                <p id="lblTotalExternos">
                                                                                    <?php echo "$ ".number_format($totalExternos, 2); ?>
                                                                                </p>
                                                                            </strong>
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <input type="hidden" id="txtTotalExternos" value="000" name="txtTotalExternos" class="txtTotalRadiologico form-control" required>
                                                                            <div class="invalid-tooltip">
                                                                                No se pueden enviar datos vacios.
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                                <div class="alert alert-danger externosContainerNull">
                                                    <h6 class="text-center"><strong>No hay servicios externos que
                                                            mostrar.</strong></h6>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="text-center" id="botoneraHoja">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i
                                                    class="fa fa-save"></i> Guardar datos</button>
                                            <button class="btn btn-light mt-4 d-inline w-20 cancelar" type="button"><i
                                                    class="fa fa-times"></i> Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Detalle de medicamentos y externos -->

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
    <!-- ============================================================== -->
    <!-- Modal para agregar datos del Medicamento-->
    <!-- ============================================================== -->
    <div class="modal fade" id="medicamentos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Lista de medicamentos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                            aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="table-responsive mt-3">

                                    <?php
                                        if( sizeof($medicamentos) > 0){
                                           
                                    ?>

                                    <table id="" class="table table-striped thead-primary w-100 tabla-medicamentos">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">Código</th>
                                                <th class="text-center" scope="col">Nombre</th>
                                                <th class="text-center" scope="col">Existencia</th>
                                                <th class="text-center" scope="col">Precio</th>
                                                <th class="text-center" scope="col">Cantidad</th>
                                                <th class="text-center" scope="col">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    foreach ($medicamentos as $medicamento) {
                                                        if($medicamento->idBM >= 0){
                                                            $id ='"'.$medicamento->idMedicamento.'"';
                                                            $nombre ='"'.$medicamento->nombreMedicamento.'"';
                                                            $stock ='"'.$medicamento->stockMedicamento.'"';
                                                            $usados = '"'.$medicamento->usadosMedicamento.'"';
                                                            //$precioV = '"'.$medicamento->precioVMedicamento.'"';
                                                            if($medicamento->pivoteMedicamento == 2){
                                                                switch ($normal) {
                                                                    case '0':
                                                                        $precioV = '"'.($medicamento->precioVMedicamento+10).'"';
                                                                        break;
                                                                    case '1':
                                                                        $precioV = '"'.$medicamento->precioVMedicamento.'"';
                                                                        break;
                                                                    case '2':
                                                                        $precioV = '"'.($medicamento->precioVMedicamento+11).'"';
                                                                        break;
                                                                    
                                                                    default:
                                                                        $precioV = '"'.$medicamento->precioVMedicamento.'"';
                                                                        break;
                                                                }
                                                            }else{
                                                                $precioV = '"'.$medicamento->precioVMedicamento.'"';
                                                            }
                                                ?>
                                            <tr class="filaMedicamento">
                                                <td class="text-center" scope="row"><?php echo $medicamento->codigoMedicamento; ?></td>
                                                <td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento; ?></td>
                                                <td class="text-center" scope="row">
                                                    <?php
                                                        if($medicamento->stockMedicamento == 0){
                                                            switch ($medicamento->tipoMedicamento) {
                                                                case 'Servicios':
                                                                    echo '<span class="badge badge-gradient-info">Servicio</span>';
                                                                    break;
                                                                case 'Otros servicios':
                                                                    echo '<span class="badge badge-gradient-info">Otros servicios</span>';
                                                                    break;
                                                                
                                                                default:
                                                                    echo '<span class="badge badge-gradient-danger">Sin stock</span>';
                                                                    break;
                                                            }
                                                        }else{
                                                            echo $medicamento->stockMedicamento;
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-center" scope="row">
                                                    <?php 
                                                       //echo number_format($medicamento->precioVMedicamento, 2);
                                                    ?>
                                                    <input type="hidden" value="<?php echo $medicamento->idMedicamento; ?>" id="test" class="form-control idM" />
                                                    <input type="hidden" value="<?php echo $medicamento->nombreMedicamento; ?>" id="test" class="form-control nombreM" />
                                                    <input type="hidden" value="<?php echo $medicamento->stockMedicamento; ?>" id="test" class="form-control stockM" />
                                                    <input type="hidden" value="<?php echo $medicamento->usadosMedicamento; ?>" id="test" class="form-control usadosM" />
                                                    <input type="text" value="<?php echo $medicamento->precioVMedicamento; ?>" id="test" class="form-control precioM" />
                                                </td>
                                                <td>
                                                    <input type="text" value="1" id="test" class="form-control cantidadM" />
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                        if($medicamento->stockMedicamento > 0){
                                                            echo "<a onclick='agregarMedicamento($id, $nombre, $stock, $usados, $precioV)' class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                        }else{
                                                            if($medicamento->tipoMedicamento != "Servicios" && $medicamento->tipoMedicamento != "Otros servicios"){
                                                                echo '<span class="badge badge-gradient-danger">Sin stock</span>';
                                                            }else{
                                                                echo "<a onclick='agregarMedicamento($id, $nombre, $stock, $usados, $precioV)' class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php   } } ?>
                                        </tbody>
                                    </table>
                                    <?php
                                                }else{
                                                    echo '<div class="alert alert-danger">
                                                        <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                                    </div>';
                                                }
                                            ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Fin Modal para agregar datos del Medicamento-->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Modal para agregar datos del Externo-->
    <!-- ============================================================== -->
    <div class="modal fade" id="externos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Lista de servicios externos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                            aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="table-responsive mt-3">

                                    <?php
                                            if( sizeof($externos) > 0){
                                        ?>

                                    <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">Nombre</th>
                                                <th class="text-center" scope="col">Precio</th>
                                                <th class="text-center" scope="col">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $nombreProveedor = "";
                                                foreach ($externos as $externo) {
                                                    $id ='"'.$externo->idExterno.'"';
                                                    $nombre ='"'.$externo->nombreExterno.'"';

                                                    /* Es medico */
                                                    if($externo->tipoEntidad == 1){
                                                        $medico = $this->Externos_Model->obtenerMedico($externo->idEntidad);
                                                        $nombreProveedor = $medico->nombreMedico;
                                                    }
                                                    /* Es otro proveedor */
                                                    if($externo->tipoEntidad == 2){
                                                        $proveedor = $this->Externos_Model->obtenerProveedor($externo->idEntidad);
                                                        $nombreProveedor = $proveedor->empresaProveedor;
                                                    }
                                            ?>
                                            <tr>
                                                <td class="text-center" scope="row">
                                                    <?php echo $externo->nombreExterno; ?></td>
                                                <td>
                                                    <input type="hidden" value="<?php echo $externo->idExterno; ?>" class="form-control idE">
                                                    <input type="hidden" value="<?php echo $externo->nombreExterno; ?>" class="form-control nombreE">
                                                    <input type="text" value="0" class="form-control precioE">
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                            //echo "<a onclick='agregarExterno($id, $nombre)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                            echo "<a class='ocultarAgregar agregarExterno' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                        ?>
                                                </td>
                                            </tr>
                                            <?php  $nombreProveedor = ""; } ?>
                                        </tbody>
                                    </table>

                                    <?php
                                            }else{
                                                echo '<div class="alert alert-danger">
                                                    <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                                </div>';
                                            }
                                        ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Fin Modal para agregar datos del Externo-->
    <!-- ============================================================== -->

    <!-- Modal para actualizar datos de medicamentos-->
    <div class="modal fade" id="actualizarMedicamentos" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del Medicamento</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                            aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" id="frmMedicamento" method="post"
                                    action="<?php echo base_url() ?>Hoja/actualizar_medicamento" novalidate>

                                    <div class="form-row">

                                        <div class="col-md-12 text-right">
                                            <span> Cambiar medicamento </span>
                                            <label class="ms-checkbox-wrap">
                                                <input class="form-check-input" type="checkbox" id="newMed" value="newMed"
                                                    name="newMed">
                                                <i class="ms-checkbox-check"></i>
                                            </label>
                                        </div>

                                        <div class="col-md-12" id="divNuevoMedicamento">
                                            <label for=""><strong>Medicamento/Insumo</strong></label>
                                            <div class="input-group">
                                                <select name="nuevoMedicamento" id="nuevoMedicamento" class="form-control controlInteligente ">
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                        foreach ($medicamentos as $medicamento) {
                                                    ?>
                                                        <option value="<?php echo $medicamento->idMedicamento; ?>"><?php echo $medicamento->nombreMedicamento; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione el medicamento o insumo.
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <label for=""><strong>Cantidad</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" id="cantidadInsumo" name="cantidadInsumo" placeholder="Ingrese la nueva cantidad" required>
                                                <input type="hidden" class="form-control" id="idHojaInsumo" name="idHojaInsumo" required>
                                                <input type="hidden" class="form-control" id="idM" name="idMedicamento" required>
                                                <input type="hidden" class="form-control" id="stock" name="stockMedicamento" required>
                                                <input type="hidden" class="form-control" id="stock2" name="stockMedicamento2" required>
                                                <input type="hidden" class="form-control" id="usados" name="usadosMedicamento" required>
                                                <input type="hidden" class="form-control" id="usados2" name="usadosMedicamento2" required>
                                                <input type="hidden" class="form-control" id="cantidad" name="cantidadMedicamento" required>
                                                <input type="hidden" class="form-control" id="precio" name="precioInsumo" required>
                                                <input type="hidden" class="form-control" id="precio2" name="precioInsumo2" required>
                                                <input type="hidden" class="form-control" id="transaccion" name="transaccion" required>
                                                <input type="hidden" class="form-control" name="idHojaReturn" value="<?php echo $paciente->idHoja; ?>" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la nueva cantidad.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i
                                                class="fa fa-save"></i> Actualizar detalle</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal"
                                            aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin Modal actualizar datos de medicamentos -->

    <!-- Modal para eliminar datos del medicamento-->
    <div class="modal fade" id="eliminarMedicamentos" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                <form action="<?php echo base_url() ?>Hoja/eliminar_medicamento" id="frmEliminarMedicamento" method="post">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Estas seguro de eliminar el medicamento?</p>
                        <input type="hidden" class="form-control" id="idHojaInsumoE" name="idHojaInsumo" required>
                        <input type="hidden" class="form-control" id="idMedicamentoE" name="idMedicamento" required>
                        <input type="hidden" class="form-control" id="stockE" name="stockMedicamento" required>
                        <input type="hidden" class="form-control" id="usadosE" name="usadosMedicamento" required>
                        <input type="hidden" class="form-control" id="cantidadE" name="cantidadMedicamento" required>
                        <input type="hidden" class="form-control" id="precioE" name="precioMedicamento" required>
                        <input type="hidden" class="form-control" id="transaccionE" name="transaccion" required>
                        <input type="hidden" class="form-control" name="idHojaReturn" value="<?php echo $paciente->idHoja; ?>" required>
                        <!-- <input type="text" class="form-control" id="indexFila" name="indexFila" required> -->
                    </div>

                    <div class="text-center">
                        <button type="submit" id="btnEliminarMedicamento" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i>
                            Eliminar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i>
                            Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Fin Modal eliminar  datos del medicamento-->

    <!-- Modal para actualizar datos de servicios externos-->
    <div class="modal fade" id="actualizarExternos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del servicio externo</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                            aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" id="frmExternos" method="post"
                                    action="<?php echo base_url() ?>Hoja/actualizar_externo" novalidate>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Cantidad</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="cantidadExterno"
                                                    name="cantidadExterno" placeholder="Ingrese la nueva cantidad" required>

                                                <input type="hidden" class="form-control" id="idExterno" name="idExterno"
                                                    required>
                                                <input type="hidden" class="form-control" id="nombreExterno"
                                                    name="nombreExterno" required>
                                                <input type="hidden" class="form-control" name="idHojaReturn"
                                                    value="<?php echo $paciente->idHoja; ?>" required>

                                                <div class="invalid-tooltip">
                                                    Ingrese la nueva cantidad de el servicio externo.
                                                </div>
                                            </div>

                                            <label for=""><strong>Precio</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="precioExterno"
                                                    name="precioExterno" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el precio de el servicio externo.
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i
                                                class="fa fa-save"></i> Actualizar detalle</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal"
                                            aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin Modal actualizar datos de servicios externos -->

    <!-- Modal para eliminar datos del medicamento-->
    <div class="modal fade" id="eliminarExternos" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                <form action="<?php echo base_url() ?>Hoja/eliminar_externo" method="post">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Estas seguro de eliminar el servicio externo?</p>
                        <input type="hidden" class="form-control" id="idExternoE" name="idHojaExterno" required>
                        <input type="hidden" class="form-control" name="idHojaReturn"
                            value="<?php echo $paciente->idHoja; ?>" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i>
                            Eliminar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i>
                            Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Fin Modal eliminar  datos del medicamento-->

    <!-- Modal para cerrar hoja-->
    <div class="modal fade p-5" id="cerrarHoja" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-primary">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                            class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de cerrar esta cuenta?</p>
                </div>

                <form class="needs-validation" action="<?php echo base_url()?>Hoja/cerrar_hoja" method="post">
                    <?php
                        if($paciente->diagnosticoHoja == "" ){
                            //echo '<input type="hidden" value="'.date("Y-m-d").'" name="salidaHoja">';
                            echo '<div class="form-group px-3 fechaEgreso">
                                    <label><strong>Fecha de salida</strong></label>
                                    <input type="date" value="'.date("Y-m-d").'" class="form-control" name="salidaHoja">
                                    <div class="invalid-tooltip">
                                        Debes ingresar el diagnóstico del paciente.
                                    </div>
                                </div>';
                        }else{
                            echo '<div class="form-group px-3 fechaEgreso">
                                    <label><strong>Fecha de salida</strong></label>
                                    <input type="date" value="'.$paciente->salidaHoja.'" class="form-control" name="salidaHoja">
                                    <div class="invalid-tooltip">
                                        Debes ingresar el diagnóstico del paciente.
                                    </div>
                                </div>';
                        }
                    ?>

                    <?php
                                
                            ?>
                    <!-- <div class="px-2 mb-3">
                                <div class="form-group">
                                    <textarea name="diagnosticoHoja" cols="5" rows="5" id="diagnosticoHoja" class="form-control" required><?php echo $paciente->diagnosticoHoja; ?></textarea>
                                    <div class="invalid-tooltip">
                                        Debes ingresar el diagnóstico del paciente.
                                    </div>
                                </div>
                            </div>	 -->
                    <input type="hidden" id="diagnosticoHoja" value="Paciente de alta" name="diagnosticoHoja">
                    <input type="hidden" id="correlativoSalidaHoja" value="0" name="correlativoSalidaHoja">
                    <input type="hidden" id="idHoja" value="<?php echo $paciente->idHoja; ?>" name="idHoja">
                    <input type="hidden" id="idPaciente" value="<?php echo $paciente->idPaciente; ?>" name="idPaciente">
                    <?php
                        if($paciente->diagnosticoHoja == ""){
                    ?>
                        <input type="hidden" id="idHabitacion" value="<?php echo $paciente->idHabitacion; ?>" name="idHabitacion">
                    <?php
                        }
                    ?>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-times"></i> Cerrar Cuenta</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Fin Modal cerrar hoja-->

    <!-- Modal para restaurar hoja-->
    <div class="modal fade" id="restaurarHoja" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-primary">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                            class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de abrir nuevamente esta hoja de cobro?</p>
                </div>

                <form action="<?php echo base_url()?>Hoja/restaurar_hoja" method="post">
                    <input type="hidden" id="idHoja" value="<?php echo $paciente->idHoja; ?>" name="idHoja">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-unlock"></i>
                            Abrir</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i>
                            Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Fin Modal restaurar hoja-->

    <!-- Modal consumidor final -->
    <div class="modal fade" id="consumidorFinal" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"> <i class="fa fa-file"></i> Factura de Consumidor Final</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                            aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" id="frmMedicamento" method="post" action="<?php echo base_url() ?>Hoja/guardar_consumidor_final" novalidate>
                                    
                                    <div class="alert-primary form-row mb-4">
                                        <div class="p-2 table-responsive bordeContenedor pt-3">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                    <td><strong>Paciente:</strong></td>
                                                        <td><?php echo $paciente->nombrePaciente; ?></td>
                                                        <td><strong>Edad:</strong></td>
                                                        <td><?php echo $paciente->edadPaciente; ?> años</td>
                                                        <td><strong>DUI:</strong></td>
                                                        <td><?php echo $paciente->duiPaciente; ?> <input type="hidden" value="<?php echo $paciente->duiPaciente; ?>" id="duiCabecera"></td>
                                                        <?php
                                                            if(isset($pivoteFactura)){
                                                                echo '<td><a href="'.base_url().'Hoja/consumidor_final_pdf/'.$paciente->idHoja.'" target="_blank" class="text-danger"><i class="fa fa-print"></i></a></td>';
                                                            }
                                                        ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for=""><strong>Número de factura</strong></label>
                                            <div class="input-group">
                                                <input type="hidden" value="<?php echo $paciente->idHoja; ?>" class="form-control numeros" id="idHojaCobro" name="idHojaCobro" required>
                                                <input type="text" value="<?php echo $facturaC; ?>" class="form-control numeros" id="numeroFactura" name="numeroFactura" required>
                                                <input type="hidden" value="1" class="form-control numeros" id="tipoFactura" name="tipoFactura" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el número de factura.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Fecha</strong></label>
                                            <div class="input-group">
                                                <?php
                                                    if(isset($fechaFacturaHoja)){
                                                        echo '<input type="date" class="form-control" value="'.substr($fechaFacturaHoja, 0, 10).'" id="fechaFactura" name="fechaFactura" readonly>';
                                                    }else{
                                                        echo '<input type="date" class="form-control" value="'.date('Y-m-d').'" id="fechaFactura" name="fechaFactura" readonly>';
                                                    }
                                                ?>
                                                
                                                <div class="invalid-tooltip">
                                                    Ingrese el número de factura.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row mb-4">
                                        <div class="p-2 table-responsive pt-3">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>Cantidad</th>
                                                        <th>Descripción</th>
                                                        <th>Precio unitario</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Medicamentos e Insumos Médicos</td>
                                                        <td>$<?php echo number_format(($med + $serm), 2);  ?></td>
                                                        <td>$<?php echo number_format(($med + $serm), 2);  ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <?php
                                        if(!isset($pivoteFactura)){
                                    ?>                  
                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar factura</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="text-center">
                                        <input type="hidden" value="1" name="actualizarCodigoFactura">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar factura</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                    <?php } ?>                    
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin modal consumidor final -->

    <!-- Modal credito fiscal-->
        <div class="modal fade" id="creditoFiscal" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white"> <i class="fa fa-file"></i> Factura de crédito fiscal</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                                aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <form class="needs-validation" id="frmMedicamento" method="post" action="<?php echo base_url() ?>Hoja/guardar_consumidor_final" novalidate>
                                        
                                        <div class="alert-primary form-row mb-4">
                                            <div class="p-2 table-responsive bordeContenedor pt-3">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Paciente:</strong></td>
                                                            <td><?php echo $paciente->nombrePaciente; ?></td>
                                                            <td><strong>Edad:</strong></td>
                                                            <td><?php echo $paciente->edadPaciente; ?> años</td>
                                                            <td><strong>DUI:</strong></td>
                                                            <td><?php echo $paciente->duiPaciente; ?> <input type="hidden" value="<?php echo $paciente->duiPaciente; ?>" id="duiCabecera"></td>
                                                            <?php
                                                                if(isset($pivoteFactura)){
                                                                    echo '<td><a href="'.base_url().'Hoja/credito_fiscal_pdf/'.$paciente->idHoja.'" target="_blank" class="text-danger"><i class="fa fa-print"></i></a></td>';
                                                                }
                                                            ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label for=""><strong>Número de factura</strong></label>
                                                <div class="input-group">
                                                    <input type="hidden" value="<?php echo $paciente->idHoja; ?>" class="form-control numeros" id="idHojaCobro" name="idHojaCobro" required>
                                                    <input type="text" value="<?php echo $facturaCF; ?>" class="form-control numeros" id="numeroFactura" name="numeroFactura" required>
                                                    <input type="hidden" value="2" class="form-control numeros" id="tipoFactura" name="tipoFactura" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese el número de factura.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for=""><strong>Fecha</strong></label>
                                                <div class="input-group">
                                                    <?php
                                                        if(isset($fechaFacturaHoja)){
                                                            echo '<input type="date" class="form-control" value="'.substr($fechaFacturaHoja, 0, 10).'" id="fechaFactura" name="fechaFactura" readonly>';
                                                        }else{
                                                            echo '<input type="date" class="form-control" value="'.date('Y-m-d').'" id="fechaFactura" name="fechaFactura" readonly>';
                                                        }
                                                    ?>
                                                    
                                                    <div class="invalid-tooltip">
                                                        Ingrese el número de factura.
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-row mb-4">
                                            <div class="p-2 table-responsive pt-3">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>Descripción</th>
                                                            <th>Precio unitario</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Medicamentos e Insumos Médicos</td>
                                                            <td>$<?php echo number_format(($med + $serm), 2);  ?></td>
                                                            <td>$<?php echo number_format(($med + $serm), 2);  ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <?php
                                            if(!isset($pivoteFactura)){
                                        ?>                  
                                        <div class="text-center">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar factura</button>
                                            <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                        </div>
                                        <?php } ?>                  
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <!-- Fin modal credito fiscal-->


    <!-- Modal para anular hoja-->
    <div class="modal fade p-5" id="anularHoja" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-danger">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                            class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de anular esta hoja de cobro?</p>
                </div>

                <form class="needs-validation" action="<?php echo base_url()?>Hoja/anular_hoja" method="post">
                    <input type="hidden" value="<?php echo $paciente->idHoja; ?>" name="idHoja">
                
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-ban"></i> Anular Hoja</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Fin Modal anular hoja-->


<!-- Script JS para agregar un medicamento -->
    <script>
        // Validando el tamaño de las tablas
        $(document).ready(function() {
            $('.controlInteligente').select2({
                theme: "bootstrap4"
            });
            var mFilas = $("#tblMedicamentos tr .precioMedicamento").length;
            if (mFilas == 0) {
                $(".medicamentosContainer").hide();
                $(".medicamentosContainerNull").show();
                $("#botoneraHoja").hide();
            } else {
                $(".medicamentosContainer").show();
                $(".medicamentosContainerNull").hide();
                $("#botoneraHoja").show();
            }

            var eFilas = $("#tblExternos tr .precioExterno").length;
            if (eFilas == 0) {
                $(".externosContainer").hide();
                $(".externosContainerNull").show();
                $("#botoneraHoja").hide();
            } else {
                $(".externosContainer").show();
                $(".externosContainerNull").hide();
                $("#botoneraHoja").show();
            }
        });

        // Agregar medicamento a la lista
        $(document).on('click', '.agregarMedicamento', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            id = $(this).closest('tr').find(".idM").val();
            nombre = $(this).closest('tr').find(".nombreM").val();
            stock = $(this).closest('tr').find(".stockM").val();
            usados = $(this).closest('tr').find(".usadosM").val();
            precioV = $(this).closest('tr').find(".precioM").val();
            cantidad = $(this).closest('tr').find(".cantidadM").val();

            $(".medicamentosContainer").show();
            $(".medicamentosContainerNull").hide();
            $("#botoneraHoja").show();
            var totalTemp = (precioV * cantidad).toFixed(2);
            var html = '';
            html += '<tr>';
            html += '    <td class="text-center">' + nombre + '<input type="hidden" id="idMedicamento" value="' + id +
                '" name="idMedicamento[]" class="idMedicamento"></td>';
            html += '    <td class="text-center"><input type="text" id="precioMedicamento" value="' + precioV +
                '" name="precioMedicamento[]" class="precioMedicamento"></td>';
            html += '    <td class="text-center">';
            html += '        <div class="input-group">';
            html += '            <input type="number" id="cantidadMedicamento" value="' + cantidad +
                '" name="cantidadMedicamento[]" class="cantidadMedicamento form-control" min="1" required>';
            html += '            <div class="invalid-tooltip">';
            html += '                Debes ingresar la cantidad del medicamento.';
            html += '            </div>';
            html += '            <input type="hidden" id="stockMedicamento" value="' + stock +
                '" name="stockMedicamento[]" class="stockMedicamento" required>';
            html += '            <input type="hidden" id="usadosMedicamento" value="' + usados +
                '" name="usadosMedicamento[]" class="usadosMedicamento" required>';
            html += '        </div>';
            html += '        ';
            html += '    </td>';
            html += '    <td class="text-center"><p class="totalTemp">$' + totalTemp + '</p><input value=' + totalTemp +
                ' type="hidden" id="totalUMedicamento" name="totalUMedicamento[]" class="totalUMedicamento" required></td>';
            html += '    <td class="text-center">';
            html += '        <a href="" class="quitarMedicamento text-danger"><i class="fa fa-trash"></i></a>';
            html += '    </td>';
            html += '</tr>';
            $("#totalMedicamentos").before(html);

            // Sacando el total inicial
            var totalMedicamentos = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.totalUMedicamento').each(function() {
                totalMedicamentos += parseFloat($(this).val());
            });
            $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
            2)); // Asignando el valor a lblTotalMedicamentos
            //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
            $(".form-control-sm").focus();

        });

        $('.cantidadM').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                // ejecutando procesos
                $(this).closest('tr').remove();
                id = $(this).closest('tr').find(".idM").val();
                nombre = $(this).closest('tr').find(".nombreM").val();
                stock = $(this).closest('tr').find(".stockM").val();
                usados = $(this).closest('tr').find(".usadosM").val();
                precioV = $(this).closest('tr').find(".precioM").val();
                cantidad = $(this).closest('tr').find(".cantidadM").val();

                $(".medicamentosContainer").show();
                $(".medicamentosContainerNull").hide();
                $("#botoneraHoja").show();
                var totalTemp = (precioV * cantidad).toFixed(2);
                var html = '';
                html += '<tr>';
                html += '    <td class="text-center">' + nombre + '<input type="hidden" id="idMedicamento" value="' +
                    id + '" name="idMedicamento[]" class="idMedicamento"></td>';
                html += '    <td class="text-center"><input type="text" id="precioMedicamento" value="' + precioV +
                    '" name="precioMedicamento[]" class="precioMedicamento"></td>';
                html += '    <td class="text-center">';
                html += '        <div class="input-group">';
                html += '            <input type="number" id="cantidadMedicamento" value="' + cantidad +
                    '" name="cantidadMedicamento[]" class="cantidadMedicamento form-control" min="1" required>';
                html += '            <div class="invalid-tooltip">';
                html += '                Debes ingresar la cantidad del medicamento.';
                html += '            </div>';
                html += '            <input type="hidden" id="stockMedicamento" value="' + stock +
                    '" name="stockMedicamento[]" class="stockMedicamento" required>';
                html += '            <input type="hidden" id="usadosMedicamento" value="' + usados +
                    '" name="usadosMedicamento[]" class="usadosMedicamento" required>';
                html += '        </div>';
                html += '        ';
                html += '    </td>';
                html += '    <td class="text-center"><p class="totalTemp">$' + totalTemp + '</p><input value=' +
                    totalTemp +
                    ' type="hidden" id="totalUMedicamento" name="totalUMedicamento[]" class="totalUMedicamento" required></td>';
                html += '    <td class="text-center">';
                html += '        <a href="" class="quitarMedicamento text-danger"><i class="fa fa-trash"></i></a>';
                html += '    </td>';
                html += '</tr>';
                $("#totalMedicamentos").before(html);

                // Sacando el total inicial
                var totalMedicamentos = 0; // Total de la factura
                // Sumando todos los input con totales unitarios
                $('.totalUMedicamento').each(function() {
                    totalMedicamentos += parseFloat($(this).val());
                });
                $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
                2)); // Asignando el valor a lblTotalMedicamentos
                //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio

                $(".form-control-sm").focus();
            }
        });

        // Funcion chiva
        /* function agregarMedicamento(id, nombre, stock, usados, precioV){
                $(".medicamentosContainer").show();
                $(".medicamentosContainerNull").hide();
                $("#botoneraHoja").show();
                var totalTemp = precioV * 1;
                var html = '';
                html += '<tr>';
                html += '    <td class="text-center">'+nombre+'<input type="hidden" id="idMedicamento" value="'+id+'" name="idMedicamento[]" class="idMedicamento"></td>';
                html += '    <td class="text-center">$'+precioV+'<input type="hidden" id="precioMedicamento" value="'+precioV+'" name="precioMedicamento[]" class="precioMedicamento"></td>';
                html += '    <td class="text-center">';
                html += '        <div class="input-group">';
                html += '            <input type="number" id="cantidadMedicamento" value="1" name="cantidadMedicamento[]" class="cantidadMedicamento form-control" min="1" required>';
                html += '            <div class="invalid-tooltip">';
                html += '                Debes ingresar la cantidad del medicamento.';
                html += '            </div>';
                html += '            <input type="hidden" id="stockMedicamento" value="'+stock+'" name="stockMedicamento[]" class="stockMedicamento" required>';
                html += '            <input type="hidden" id="usadosMedicamento" value="'+usados+'" name="usadosMedicamento[]" class="usadosMedicamento" required>';
                html += '        </div>';
                html += '        ';
                html += '    </td>';
                html += '    <td class="text-center"><p class="totalTemp">$'+ totalTemp +'</p><input value='+ totalTemp +' type="hidden" id="totalUMedicamento" name="totalUMedicamento[]" class="totalUMedicamento" required></td>';
                html += '    <td class="text-center">';
                html += '        <a href="" class="quitarMedicamento text-danger"><i class="fa fa-trash"></i></a>';
                html += '    </td>';
                html += '</tr>';
                $("#totalMedicamentos").before(html);

                // Sacando el total inicial
                var totalMedicamentos = 0 ; // Total de la factura
                // Sumando todos los input con totales unitarios
                $('.totalUMedicamento').each(function(){
                    totalMedicamentos += parseFloat($(this).val());
                });
                $("#lblTotalMedicamentos").html("$ "+totalMedicamentos.toFixed(2)); // Asignando el valor a lblTotalMedicamentos
                //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
            } */
        // Fin funcion chiva    
        // Calcular al cambiar la cantidad del medicamento
        $(document).on('change', '.cantidadMedicamento', function(event) {
            event.preventDefault();
            var cantidad = $(this).val(); // Cantidad de cada caja de texto
            var precio = $(this).closest('tr').find(".precioMedicamento").val(); // Precio de cada producto

            var totalMedicamentos = 0; // Total de la factura
            var totalUMedicamento = cantidad * precio; // Total por cada producto


            $(this).closest('tr').find(".totalTemp").html(totalUMedicamento.toFixed(2));
            $(this).closest('tr').find(".totalUMedicamento").val(totalUMedicamento.toFixed(2));

            // Sumando todos los input con totales unitarios
            $('.totalUMedicamento').each(function() {
                totalMedicamentos += parseFloat($(this).val());
            });

            $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
            2)); // Asignando el valor a lblTotalMedicamentos
            //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
        });

        // Calcular al cambiar el precio del medicamento
        $(document).on('change', '.precioMedicamento', function(event) {
            event.preventDefault();
            var precio = $(this).val(); // Cantidad de cada caja de texto
            var cantidad = $(this).closest('tr').find(".cantidadMedicamento").val(); // Precio de cada producto

            var totalMedicamentos = 0; // Total de la factura
            var totalUMedicamento = cantidad * precio; // Total por cada producto


            $(this).closest('tr').find(".totalTemp").html(totalUMedicamento.toFixed(2));
            $(this).closest('tr').find(".totalUMedicamento").val(totalUMedicamento.toFixed(2));

            // Sumando todos los input con totales unitarios
            $('.totalUMedicamento').each(function() {
                totalMedicamentos += parseFloat($(this).val());
            });

            $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
            2)); // Asignando el valor a lblTotalMedicamentos
            //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
        });

        // Quitar medicamento de la lista
        $(document).on('click', '.quitarMedicamento', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            var totalMedicamentos = 0; // Total de la factura
            // Sacando el total inicial
            var totalMedicamentos = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.totalUMedicamento').each(function() {
                totalMedicamentos += parseFloat($(this).val());
            });
            $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
            2)); // Asignando el valor a lblTotalMedicamentos
            $("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio



            var rFilas = $("#tblMedicamentos tr .precioMedicamento").length;
            var eFilas = $("#tblExternos tr .precioExterno").length;
            if (rFilas == 0) {
                $(".medicamentosContainer").hide();
                $(".medicamentosContainerNull").show();
            }
            if (rFilas == 0 && eFilas == 0) {
                $("#botoneraHoja").hide();
            }

        });

        // Ocultar medicamento por medicamentos agregados
        $(document).on('click', '.ocultarAgregar', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });

        // Funcion para controlar estado de las pestañas del tab
        function cambiar(a) {
            switch (a) {
                case 1:
                    $("#tab1").addClass("active show");
                    $("#control1").addClass("active show");
                    $("#tab2").removeClass("active show");
                    $("#control2").removeClass("active show");
                    break;
                case 2:
                    $("#tab1").removeClass("active show");
                    $("#control1").removeClass("active show");
                    $("#tab2").addClass("active show");
                    $("#control2").addClass("active show");
                    break;
                default:
                    break;
            }
        }

        // Cargar id para actualizar medicamento
        function actualizarMedicamentos(id, idM, stock, usados, cantidad, precio, tipo, transaccion) {
            $("#idHojaInsumo").val(id);
            $("#nuevoMedicamento").val(idM);
            $("#idM").val(idM);
            $("#stock").val(stock);
            $("#usados").val(usados);
            $("#cantidad").val(cantidad);
            $("#precio").val(precio);
            $("#transaccion").val(transaccion);
        }

        function eliminarMedicamentos(id, idM, stock, usados, cantidad, precio, tipo, transaccion, hoja) {
            var datos = {
                idHojaInsumo : id,
                idMedicamento : idM,
                stockMedicamento : stock,
                usadosMedicamento : usados,
                cantidadMedicamento : cantidad,
                precioMedicamento : precio,
                transaccion : transaccion,
                hoja : hoja,
            }

            $.ajax({
                url: "../eliminar_medicamento_hoja",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (registro.length > 0){
                            console.log(registro);
                        }
                    }
            });

        }

        $(document).on('click', '.eliminarDetalle', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            var totalMedicamentos = 0; // Total de la factura
            // Sacando el total inicial
            var totalMedicamentos = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.totalUMedicamento').each(function() {
                totalMedicamentos += parseFloat($(this).val());
            });
            $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
            2)); // Asignando el valor a lblTotalMedicamentos
            $("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio



            var rFilas = $("#tblMedicamentos tr .precioMedicamento").length;
            var eFilas = $("#tblExternos tr .precioExterno").length;
            if (rFilas == 0) {
                $(".medicamentosContainer").hide();
                $(".medicamentosContainerNull").show();
            }
            if (rFilas == 0 && eFilas == 0) {
                $("#botoneraHoja").hide();
            }
        });

        /* function eliminarMedicamentos(id, idM, stock, usados, cantidad, precio, tipo, transaccion) {
            $("#idHojaInsumoE").val(id);
            $("#idMedicamentoE").val(idM);
            $("#stockE").val(stock);
            $("#usadosE").val(usados);
            $("#cantidadE").val(cantidad);
            $("#precioE").val(precio);
            $("#transaccionE").val(transaccion);
        } */

        </script>

        <!-- Script JS para agregar un externo -->
        <script>
        // Agregar externo a la lista
        /* function agregarExterno(id, nombre){
            $(".externosContainer").show();
            $(".externosContainerNull").hide();
            $("#botoneraHoja").show(); 
            var totalTempExternos = 0;
            var html = '';
            html += '<tr>';
            html += '    <td class="text-center">'+nombre+'<input type="hidden" id="idExterno" name="idExterno[]" value="'+id+'" min="0" class="form-control idExterno numeros" required></td>';
            html += '    <td class="text-center"><input type="text" id="precioExterno" name="precioExterno[]" min="0" class="form-control precioExterno numeros" required></td>';
            html += '    <td class="text-center">';
            html += '        <div class="input-group">';
            html += '            <input type="number" id="cantidadExterno" value="1" min="1" name="cantidadExterno[]" class="cantidadExterno form-control" min="1" required>';
            html += '            <div class="invalid-tooltip">';
            html += '                Debes ingresar la cantidad del Externo.';
            html += '            </div>';
            html += '        </div>';
            html += '    </td>';
            html += '    <td class="text-center"><p class="totalTempExternos">$'+ totalTempExternos +'</p><input value='+ totalTempExternos +' type="hidden" id="totalUExterno" name="totalUExterno[]" class="totalUExterno" required></td>';
            html += '    <td class="text-center">';
            html += '        <a href="" class="quitarExterno text-danger"><i class="fa fa-trash"></i></a>';
            html += '    </td>';
            html += '</tr>';
            $("#totalExternos").before(html);

            // Sacando el total inicial
            var totalExternos = 0 ; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.totalUExterno').each(function(){
                totalExternos += parseFloat($(this).val());
            });
            $("#lblTotalExternos").html("$ "+totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
            //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
        } */

        $(document).on('click', '.agregarExterno', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            id = $(this).closest('tr').find(".idE").val();
            nombre = $(this).closest('tr').find(".nombreE").val();
            precio = $(this).closest('tr').find(".precioE").val();

            $(".externosContainer").show();
            $(".externosContainerNull").hide();
            $("#botoneraHoja").show();
            var totalTempExternos = 0;
            totalTempExternos = precio * 1;
            var html = '';
            html += '<tr>';
            html += '    <td class="text-center">' + nombre +
                '<input type="hidden" id="idExterno" name="idExterno[]" value="' + id +
                '" min="0" class="form-control idExterno numeros" required></td>';
            html += '    <td class="text-center"><input type="text" value="' + precio +
                '" id="precioExterno" name="precioExterno[]" min="0" class="form-control precioExterno numeros" required></td>';
            html += '    <td class="text-center">';
            html += '        <div class="input-group">';
            html +=
                '            <input type="number" id="cantidadExterno" value="1" min="1" name="cantidadExterno[]" class="cantidadExterno form-control" min="1" required>';
            html += '            <div class="invalid-tooltip">';
            html += '                Debes ingresar la cantidad del Externo.';
            html += '            </div>';
            html += '        </div>';
            html += '    </td>';
            html += '    <td class="text-center"><p class="totalTempExternos">$' + totalTempExternos +
                '</p><input value=' + totalTempExternos +
                ' type="hidden" id="totalUExterno" name="totalUExterno[]" class="totalUExterno" required></td>';
            html += '    <td class="text-center">';
            html += '        <a href="" class="quitarExterno text-danger"><i class="fa fa-trash"></i></a>';
            html += '    </td>';
            html += '</tr>';
            $("#totalExternos").before(html);

            // Sacando el total inicial
            var totalExternos = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.totalUExterno').each(function() {
                totalExternos += parseFloat($(this).val());
            });
            $("#lblTotalExternos").html("$ " + totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
            //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
            $(".form-control-sm").focus();
        });

        $('.precioE').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                // ejecutando procesos
                $(this).closest('tr').remove();
                id = $(this).closest('tr').find(".idE").val();
                nombre = $(this).closest('tr').find(".nombreE").val();
                precio = $(this).closest('tr').find(".precioE").val();

                $(".externosContainer").show();
                $(".externosContainerNull").hide();
                $("#botoneraHoja").show();
                var totalTempExternos = 0;
                totalTempExternos = precio * 1;
                var html = '';
                html += '<tr>';
                html += '    <td class="text-center">' + nombre +
                    '<input type="hidden" id="idExterno" name="idExterno[]" value="' + id +
                    '" min="0" class="form-control idExterno numeros" required></td>';
                html += '    <td class="text-center"><input type="text" value="' + precio +
                    '" id="precioExterno" name="precioExterno[]" min="0" class="form-control precioExterno numeros" required></td>';
                html += '    <td class="text-center">';
                html += '        <div class="input-group">';
                html +=
                    '            <input type="number" id="cantidadExterno" value="1" min="1" name="cantidadExterno[]" class="cantidadExterno form-control" min="1" required>';
                html += '            <div class="invalid-tooltip">';
                html += '                Debes ingresar la cantidad del Externo.';
                html += '            </div>';
                html += '        </div>';
                html += '    </td>';
                html += '    <td class="text-center"><p class="totalTempExternos">$' + totalTempExternos +
                    '</p><input value=' + totalTempExternos +
                    ' type="hidden" id="totalUExterno" name="totalUExterno[]" class="totalUExterno" required></td>';
                html += '    <td class="text-center">';
                html += '        <a href="" class="quitarExterno text-danger"><i class="fa fa-trash"></i></a>';
                html += '    </td>';
                html += '</tr>';
                $("#totalExternos").before(html);

                // Sacando el total inicial
                var totalExternos = 0; // Total de la factura
                // Sumando todos los input con totales unitarios
                $('.totalUExterno').each(function() {
                    totalExternos += parseFloat($(this).val());
                });
                $("#lblTotalExternos").html("$ " + totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
                //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
                $(".form-control-sm").focus();
            }
        });

        // Calcular al cambiar la cantidad del externo
        $(document).on('change', '.cantidadExterno', function(event) {
            event.preventDefault();
            var cantidad = $(this).val(); // Cantidad de cada caja de texto
            var precio = $(this).closest('tr').find(".precioExterno").val(); // Precio de cada producto

            var totalExternos = 0; // Total de la factura
            var totalUExterno = cantidad * precio; // Total por cada producto


            $(this).closest('tr').find(".totalTempExternos").html(totalUExterno.toFixed(2));
            $(this).closest('tr').find(".totalUExterno").val(totalUExterno.toFixed(2));

            // Sumando todos los input con totales unitarios
            $('.totalUExterno').each(function() {
                totalExternos += parseFloat($(this).val());
            });

            $("#lblTotalExternos").html("$ " + totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
            //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
        });

        // Calcular al cambiar el precio del externo
        $(document).on('change', '.precioExterno', function(event) {
            event.preventDefault();
            var cantidad = $(this).closest('tr').find(".cantidadExterno").val(); // Cantidad de medicamento
            var precio = $(this).val(); // Precio del externo

            var totalExternos = 0; // Total de la factura
            var totalUExterno = cantidad * precio; // Total por cada producto


            $(this).closest('tr').find(".totalTempExternos").html(totalUExterno.toFixed(2));
            $(this).closest('tr').find(".totalUExterno").val(totalUExterno.toFixed(2));

            // Sumando todos los input con totales unitarios
            $('.totalUExterno').each(function() {
                totalExternos += parseFloat($(this).val());
            });

            $("#lblTotalExternos").html("$ " + totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
            //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
        });

        // Quitar externos de la lista
        $(document).on('click', '.quitarExterno', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            var totalExternos = 0; // Total de la factura
            // Sacando el total inicial
            var totalExternos = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.totalUExterno').each(function() {
                totalExternos += parseFloat($(this).val());
            });
            $("#lblTotalExternos").html("$ " + totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
            $("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio

            var eFilas = $("#tblExternos tr .precioExterno").length;
            var rFilas = $("#tblMedicamentos tr .precioMedicamento").length;
            if (eFilas == 0) {
                $(".externosContainer").hide();
                $(".externosContainerNull").show();
            }
            if (rFilas == 0 && eFilas == 0) {
                $("#botoneraHoja").hide();
            }

        });

        // Actualizar y eliminar externos
        function actualizarExternos(idHojaExterno, nombre, precio, cantidad) {
            $("#idExterno").val(idHojaExterno);
            $("#nombreExterno").val(nombre);
            $("#precioExterno").val(precio);
            $("#cantidadExterno").val(cantidad);
        }

        function eliminarExternos(idHojaExterno, nombre, precio, cantidad) {
            $("#idExternoE").val(idHojaExterno);
        }

        $(document).on('click', '.cancelar', function(event) {
            event.preventDefault();
            location.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#newMed").click(function() {
                var valor = $('input:checkbox[name=newMed]:checked').val();
                if (valor == "newMed") {
                    $("#divNuevoMedicamento").fadeIn();
                } else {
                    $("#divNuevoMedicamento").hide();
                }
            });

            $(document).on('change', '.controlInteligente', function(event) {
                event.preventDefault();

                var med = $(this).val();

                $.ajax({
                    url: "../obtener_medicamento",
                    type: "GET",
                    data: {
                        id: med
                    },
                    success: function(respuesta) {
                        var registro = eval(respuesta);

                        if (registro.length > 0) {
                            console.log(registro);
                            for (var i = 0; i < registro.length; i++) {

                                $("#stock2").val(registro[i]["stockMedicamento"]);
                                $("#usados2").val(registro[i]["usadosMedicamento"]);
                                $("#precio2").val(registro[i]["precioVMedicamento"]);
                            }
                        }
                    }
                });

            });

        });


        function cerrarHoja(){
            var total_hoja = $("#totalGlobalCuenta").val();
            if(total_hoja >= 200){
                var dui_cabecera = $("#duiCabecera").val();
                var dui = "";
                dui += '<div class="form-group px-3">';
                dui += '    <label><strong>DUI</strong></label>';
                if(dui_cabecera == "00000000-0" ){
                    dui += '<input type="text" class="form-control" value="00000000-0" data-mask="99999999-9" name="duiPaciente" id="duiPaciente">';
                }else{
                    dui += '<input type="text" class="form-control" data-mask="99999999-9" value="'+dui_cabecera+'" name="duiPaciente" id="duiPaciente">';
                }

                dui += '    <div class="invalid-tooltip">';
                dui += '        Debes ingresar el número de DUI.';
                dui += '    </div>';
                dui += '</div>';
                $(".fechaEgreso").before(dui);
            }else{
                // alert(total_hoja);
            }
        }
    </script>
<!-- Fin -->

<!-- Script para eliminar un medicamento asincronamente -->
    <!-- <script>

        $(document).on('click', '.btnEli', function(event) {
            event.preventDefault();
            var index = $(this).closest('tr').index(); // Index de cada fila
            $("#indexFila").val(index);
            // $(this).closest('tr').remove();

            var count = $('.tblM tbody').children().length;
            //alert(index);
            
            

        });


       $(document).ready(function() {
            $("#btnEliminarMedicamento").click(function() {
                $.ajax({
                url: "../eliminar_medicamento_hoja",
                type: "POST",
                data: $("#frmEliminarMedicamento").serialize(),
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (registro.length > 0){
                            console.log(registro);
                            $(".tblM tbody").find("tr:eq(" + registro + ")").remove();
                        }
                    }
                });
            });
            
        })
    </script> -->

