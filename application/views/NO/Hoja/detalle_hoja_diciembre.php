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

        if($paciente->descuentoHoja != null){
            $totalGlobalHoja = ($totalGlobalHoja - $paciente->descuentoHoja);
        }
    // Fin total hoja de cobro.
    
    // Validando existencia de pagos
        if(isset($pagos)){
            $totalAbonado = 0;
            foreach ($pagos as $row) {
                $totalAbonado += $row->montoAbono;
            }
        }                         
        
    // Validando existencia de pagos
?>

<!-- Funciones para la conversion de numeros a letras -->
    <?php
        function basico($numero) {
            $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
            'nueve','diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
            'veinte', 'veintiuno', 'veintidos', 'veintitres', 'veinticuatro','veinticinco', 'veintiseis','veintisiete','veintiocho','veintinueve');
            return $valor[$numero-1];
        }
        
        function decenas($n) {
            $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta', 70=>'setenta',80=>'ochenta',90=>'noventa');
            if( $n <= 29) return basico($n);
            $x = $n % 10;
            if ( $x == 0 ) {
            return $decenas[$n];
            } else return $decenas[$n - $x].' y '. basico($x);
        }
        
        function centenas($n) {
            $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos', 400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
            700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
            if( $n >= 100) {
                if ( $n % 100 == 0 ) {
                    return $cientos[$n];
                } else {
                    $u = (int) substr($n,0,1);
                    $d = (int) substr($n,1,2);
                    return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
                }
            } else return decenas($n);
        }
        
        function miles($n) {
            $arregloNumero = explode(".", $n);
            if(isset($arregloNumero[1])){
                unset($arregloNumero[1]);
                $n = implode($arregloNumero);
            }else{
                $n = $n;
            }
            
            if($n > 999) {
                if( $n == 1000) {return 'mil';}
                else {
                    $l = strlen($n);
                    $c = (int)substr($n,0,$l-3);
                    $x = (int)substr($n,-3);
                    if($c == 1) {$cadena = 'mil '.centenas($x);}
                    else if($x != 0) {$cadena = centenas($c).' mil '.centenas($x);}
                    else $cadena = centenas($c). ' mil';
                    return $cadena;
                }
            } else return centenas($n);
        }
        
        function millones($n) {
            if($n == 1000000) {return 'un millón';}
            else {
                $l = strlen($n);
                $c = (int)substr($n,0,$l-6);
                $x = (int)substr($n,-6);
                if($c == 1) {
                    $cadena = ' millón ';
                } else {
                    $cadena = ' millones ';
                }
                return miles($c).$cadena.(($x > 0)?miles($x):'');
            }
        }
        
        function convertir($n) {
            switch (true) {
            case ( $n >= 1 && $n < 30) : return basico($n); break;
            case ( $n >= 30 && $n < 100) : return decenas($n); break;
            case ( $n >= 100 && $n < 1000) : return centenas($n); break;
            case ($n >= 1000 && $n <= 999999): return miles($n); break;
            case ($n >= 1000000): return millones($n);
            }
        }
    ?>
<!-- Fin conversion de numeros a letras -->


<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <div class="ms-panel">

                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-4">
                            <div id="reciboGenerado" class="">
                                <?php 
                                    if($paciente->correlativoSalidaHoja > 0){
                                        echo '<h6 class="text-danger">Cuenta saldada</h6>'; 
                                        echo '<h6 class="text-danger">Recibo generado Nº '.$paciente->correlativoSalidaHoja.'</h6>';
                                     }
                                     if($paciente->seguroHoja > 1){
                                         echo '<span class="badge badge-danger">'.$paciente->nombreSeguro.'</span>';
                                     }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-8 text-right">
                            <?php
                                if($paciente->anulada == 0){
                            ?>
                                <!-- Si la hoja esta abierta o no -->
                                    <?php
                                        if($paciente->estadoHoja == 1){
                                    ?>
                                    <a href="#medicamentosInsumos" onclick="cambiar(1)" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-medkit"></i> Medicamentos e insumos</a>
                                    <a href="#externos" onclick="cambiar(2)" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-user-md"></i> Servicios externos</a>
                                    
                                    
                                    <?php
                                            // Laboratorio no puede ver resumen de hojas de cobro
                                            if($this->session->userdata("acceso_h") != 7){
                                                echo ' <a href="'.base_url().'Hoja/resumen_hoja/'.$paciente->idHoja.'" class="btn btn-primary text-white" target="_blank"><i class="fa fa-file-pdf"></i> Ver resumen</a> ';
                                            }

                                            if($paciente->tipoHoja == "Ambulatorio"){
                                                echo '<a href="#cerrarHoja" onclick="cerrarHoja()" data-toggle="modal" class="btn btn-outline-danger"><i class="fa fa-times"></i> Cerrar Cuenta</a>';
                                            }else{
                                                echo '<a href="#cerrarHoja" data-toggle="modal" class="btn btn-outline-danger"><i class="fa fa-times"></i> Cerrar Cuenta</a>';
                                            }
                                            /* Validar el tipo de usuario a quien se le mostrara esta opcion */
                                            if($paciente->correlativoSalidaHoja == 0 && $this->session->userdata("acceso_h") == 1){
                                                echo ' <a href="#anularHoja" data-toggle="modal" class="btn btn-outline-danger"><i class="fa fa-ban"></i> Anular</a>';
                                            }
                                        }else{
                                    ?>

                                    <!-- Para anexar expediente -->
                                        <!-- <?php
                                            // if($this->session->userdata('tipo') == 1){
                                            //     if(isset($expediente->nombreExpediente)){
                                            //         echo ' <a href="#actualizarExpediente" data-toggle="modal" class="btn btn-primary text-white" target="blank"><i class="fa fa-file-pdf"></i> Actualizar expediente</a> ';
                                            //     }else{
                                            //         echo ' <a href="#anexarExpediente" data-toggle="modal" class="btn btn-primary text-white" target="blank"><i class="fa fa-file-pdf"></i> Agregar expediente</a> ';
                                            //     }
                                            // }
                                        ?> -->
                                    <!-- Fin anexar expediente -->
                                    <!-- Seccion mostrada solo a cajera -->
                                        <?php
                                            if( $this->session->userdata("acceso_h") == 10 || $this->session->userdata("acceso_h") == 1 || $this->session->userdata("acceso_h") == 2){
                                        ?>
                                            <!-- Validacion de si es credito o consumidor -->
                                                <!-- <?php
                                                    // if(isset($tipoFacturaHoja)){
                                                    //     if($tipoFacturaHoja == 1){
                                                    //         echo '<a href="#consumidorFinal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Consumidor final</a>';
                                                    //     }else{
                                                    //         echo '<a href="#creditoFiscal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Crédito fiscal</a>';
                                                    //     }
                                                    // }else{
                                                ?>
                                                <a href="#creditoFiscal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Crédito fiscal</a>
                                                <a href="#consumidorFinal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Consumidor final</a>
                                                <?php // } ?> -->
                                            <!-- Fin validacion de si es credito o consumidor -->
                                            <a href="#consumidorFinal" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-file"></i> Consumidor final</a>
                                        <?php } ?>
                                    <!-- Fin seccion solo cajera -->
                                    
                                    <a href="<?php echo base_url(); ?>Hoja/recibo_hoja/<?php echo $paciente->idHoja; ?>" class="btn btn-primary btnRecibo" target="_blank"><i class="fa fa-print"></i> Recibo</a>
                                    <!-- <a href="<?php echo base_url(); ?>Hoja/resumen_hoja/<?php echo $paciente->idHoja; ?>" class="btn btn-primary text-white" target="_blank"><i class="fa fa-file-pdf"></i> Ver resumen</a> -->
                                    
                                    
                                    <!-- No restaurar para usuarios normales -->
                                        <?php
                                            if($paciente->correlativoSalidaHoja == 0 || $this->session->userdata("acceso_h") == 1){
                                                echo '<a href="#restaurarHoja" data-toggle="modal" class="btn btn-primary text-white btnRestaurar"><i class="fa fa-sync"></i> Restaurar</a>';
                                            }
                                        ?>
                                    <!-- Fin no restaurar para usuarios normales -->
                                    
                                    <!-- Nueva validacion solo botiquin y cuentas puede abrir hoja -->
                                        <?php
                                            // if($this->session->userdata("acceso_h") == 1 || $this->session->userdata("acceso_h") == 3 ||  $this->session->userdata("acceso_h") == 4 ||  $this->session->userdata("acceso_h") == 5 ){
                                            //     echo '<a href="#restaurarHoja" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-sync"></i> Restaurar</a>';
                                            // }
                                        ?>
                                    <!-- Fin nueva validacion -->

                                    
                                    <!-- <a href="#anularHoja" data-toggle="modal" class="btn btn-danger text-white"><i class="fa fa-ban"></i> Anular</a> -->

                                    <?php 
                                            // Laboratorio no puede ver resumen de hojas de cobro
                                            if($this->session->userdata("acceso_h") != 7){
                                                echo ' <a href="'.base_url().'Hoja/resumen_hoja/'.$paciente->idHoja.'" class="btn btn-primary text-white" target="_blank"><i class="fa fa-file-pdf"></i> Ver resumen</a> ';
                                            }
                                        }
                                    ?>
                                <!-- Fin hoja abierta o no -->
                            <?php 
                                }else{
                                    echo '<h5 class="text-center text-danger text-uppercase">Hoja de cobro anulada
                                    <a href="#detalleAnulado" data-toggle="modal" class="btn btn-danger text-white btn-sm"><i class="fa fa-eye"></i> Ver detalle</a>
                                    </h5>';
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="ms-panel-body">
                    <!-- Inicio cabecera de la hoja -->
                    <div class="alert-primary p-2 table-responsive bordeContenedor pt-3" >
                        <table class="table table-borderless">
                            <?php
                                if($paciente->pagaMedico == 1){
                                    echo '<tr class="alert-warning text-center">';
                                    echo    '<td colspan="6"><strong>Nota: </strong> La cuenta sera cancelada por el médico</td>';
                                    echo '</tr>';
                                }
                            ?>

                            <tr>
                                <td><strong>Paciente:</strong></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>Hoja/boleta_informativa/<?php echo $paciente->idHoja; ?>/" target="blank" class="btn btn-outline-info btn-sm" title="Imprimir boleta informativa"><?php echo $paciente->nombrePaciente; ?></a>
                                    <input type="hidden" value="<?php echo $paciente->idPaciente; ?>" id="idPaciente"/>
                                    <?php
                                        if($this->session->userdata('id_usuario_h') != 11){
                                            if($paciente->edadPaciente < 18){
                                                if(!is_null($responsable)){
                                                    echo '<a href="'.base_url()."Paciente/imprimir_consentimiento/".$paciente->idPaciente."/".$paciente->idHoja."/1".'" target="_blank" rel="noopener noreferrer" class="text-info" title="Imprimir consentimiento"><i class="fa fa-file-pdf"></i></a>';
                                                }
                                            }else{
                                                echo '<a href="'.base_url()."Paciente/imprimir_consentimiento/".$paciente->idPaciente."/".$paciente->idHoja."/2".'" target="_blank" rel="noopener noreferrer" class="text-info" title="Imprimir consentimiento"><i class="fa fa-file-pdf"></i></a>';
                                            }
                                        }
                                    ?>
                                    
                                </td>
                                <td><strong>DUI:</strong></td>
                                <td><?php echo $paciente->duiPaciente; ?> <input type="hidden" value="<?php echo $paciente->duiPaciente; ?>" id="duiCabecera"/></td>
                                <td><strong>Código hoja:</strong></td>
                                <td><?php echo $paciente->codigoHoja; ?></td>
                            </tr>

                            <tr>
                                <td><strong>Medico:</strong></td>
                                <td>
                                    <?php
                                        if($paciente->pagaMedico == 0 && $paciente->correlativoSalidaHoja == 0 && $paciente->nombreMedico != "Pendiente"){
                                            if($this->session->userdata('id_usuario_h') != 11){
                                                echo '<a href="#pagaraMedico" data-toggle="modal" class="btn btn-outline-info btn-sm alert-primary" title="Si la cuenta sera cancelada por el médico.">'.$paciente->nombreMedico.' </a>';
                                            }else{
                                                echo $paciente->nombreMedico;
                                            }
                                            
                                        }else{
                                            echo $paciente->nombreMedico;
                                        }
                                    ?>
                                </td>
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
                                                echo "Abierta";
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
                                <td><strong>Tipo:</strong></td>
                                <td>
                                    <?php 
                                        if($paciente->esPaquete == 1){
                                            if($paciente->totalPaquete == 0){
                                                echo $paciente->tipoHoja.'<a href="#agregarMontoPaquete" data-toggle="modal"><span class="badge badge-danger">Paquete $'.$paciente->totalPaquete.'</span></a>';
                                            }else{
                                                echo $paciente->tipoHoja.'<span class="badge badge-success">Paquete $'.$paciente->totalPaquete.'</span>';
                                            }
                                        }else{
                                            echo $paciente->tipoHoja;
                                        }
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <?php
                                    

                                    if($paciente->procedimientoHoja != null){
                                        echo '<td><strong>Procedimiento:</strong></td>';
                                        echo '<td colspan="">'.$paciente->procedimientoHoja.'</td>';
                                    }
                                    if($paciente->descuentoHoja != null && $paciente->descuentoHoja != 0){
                                        echo '<td class="text-danger"><strong>Descuento:</strong></td>';
                                        echo '<td class="text-danger" colspan="">$ '.$paciente->descuentoHoja.'</td>';
                                    }
                                ?>
                            </tr>

                            <tr>
                                <td><button type="button" class="btn btn-outline-primary btn-sm py-3 has-icon" readonly><i class="fa fa-money-check-alt fa-sm"></i><strong>Medicamentos $<?php echo number_format($med, 2);  ?></strong></button></td>
                                <td><button type="button" class="btn btn-outline-primary btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i><strong>Servicios internos $<?php echo number_format($serm, 2);  ?></strong></button></td>
                                <td><button type="button" class="btn btn-outline-primary btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i><strong>Total interno $
                                    <?php 
                                        if($paciente->descuentoHoja != null && $paciente->descuentoHoja != 0){
                                            echo number_format(($med + $serm) - $paciente->descuentoHoja, 2);
                                        }else{
                                            echo number_format(($med + $serm), 2);
                                        }
                                    ?>
                                </strong></button></td>
                                <td><button type="button" class="btn btn-outline-primary btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i><strong>Total externo: $<?php echo number_format($totalExternosCabecera, 2);  ?></strong> </button></td>
                                <?php
                                    if($paciente->porPagos == 0){
                                        echo '<td><a href="#porPagos" class="btn btn-primary btn-sm py-3 has-icon text-white" data-toggle="modal">
                                             <i class="fa fa-money-check-alt fa-sm"></i><strong>Total hoja: $'.number_format($totalGlobalHoja, 2).'</strong> </a></td>';
                                    }else{
                                        echo '<td><a href="#" class="btn btn-square btn-gradient-info btn-sm py-3 has-icon text-white">
                                             <i class="fa fa-money-check-alt fa-sm"></i><strong>Total hoja: $'.number_format($totalGlobalHoja, 2).'</strong> </a></td>';
                                        echo '<td><a href="#" class="btn btn-square btn-gradient-info btn-sm py-3 has-icon text-white">
                                              <i class="fa fa-money-check-alt fa-sm"></i><strong>Abonado: $'.number_format($totalAbonado, 2).'</strong> </a></td>';
                                    }
                                ?>
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
                                    <!-- <form action="<?php echo base_url() ?>Hoja/guardar_detalle_hoja" method="post" class="needs-validation" novalidate> -->
                                    <form action="<?php echo base_url() ?>Hoja/guardar_detalle_hoja" method="post" class="needs-validation" novalidate>
                                    <form action="" method="post" class="needs-validation" novalidate>
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
                                                                <th class="text-center" id="comienzo">Medicamento</th>
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

                                                            <tr class="move">
                                                                <td class="text-center precioMedicamento"><?php echo $medicamento->nombreMedicamento; ?> </td>
                                                                <td class="text-center"><p class="lblPrecio">$ <?php echo $medicamento->precioInsumo; ?></p> <input type="hidden" class="txtPrecio" id="txtPrecio" name="txtPrecio" value="<?php echo $medicamento->precioInsumo; ?>"></td>
                                                                <td class="text-center"><p class="lblCantidad"><?php echo $medicamento->cantidadInsumo; ?></p> <input type="hidden" class="txtCantidad" id="txtCantidad" name="txtCantidad" value="<?php echo $medicamento->cantidadInsumo; ?>"> </td>
                                                                <td class="text-center"> <p class="lblTotalUMedicamento">$ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo), 2); ?></p>
                                                                    <input value="<?php echo ($medicamento->cantidadInsumo * $medicamento->precioInsumo); ?>" type="hidden" class="totalUMedicamento" id="totalUMedicamento" name="totalUMedicamento" required>
                                                                </td>

                                                                <?php
                                                                        if($paciente->estadoHoja == 1){
                                                                    ?>

                                                                <td class="text-center">
                                                                    <input type="hidden" class="form-control txtIdHoja" id="idHoja" name="idHoja" value="<?php echo $paciente->idHoja; ?>" required="">
                                                                    <input type="hidden" class="form-control txtIdM" id="idM" name="idMedicamento" value="<?php echo $medicamento->idMedicamento; ?>" required="">
                                                                    <input type="hidden" class="form-control txtStock" id="stock" name="stockMedicamento" value="<?php echo $medicamento->stockMedicamento; ?>" required="">
                                                                    <input type="hidden" class="form-control txtUsados" id="usados" name="usadosMedicamento" value="<?php echo $medicamento->usadosMedicamento; ?>" required="">
                                                                    <input type="hidden" class="form-control" id="cantidad" name="cantidadMedicamento" value="<?php echo $medicamento->cantidadInsumo; ?>" required="" >
                                                                    <input type="hidden" class="form-control txTransaccion" id="transaccion" name="transaccion" value="<?php echo $medicamento->idHojaInsumo; ?>" required="">
                                                                    <input type="hidden" class="form-control txtNombreMedicamentoA" id="nombreMedicamentoA" name="nombreMedicamento" value="<?php echo $medicamento->nombreMedicamento; ?>" required="">
                                                                    
                                                                    <?php
                                                                        if($medicamento->pivoteStock == 2){
                                                                            echo '<span class="badge badge-danger">Sala</span>';
                                                                        }else{
                                                                            echo "<a href='#' class='activarCampos' data-toggle='modal' title='Editar datos'><i class='fa fa-edit ms-text-primary'></i></a>";
                                                                            echo "<a href='#' class='bloquearCampos' data-toggle='modal' title='Guardar datos'><i class='fa fa-check ms-text-success'></i></a>";
                                                                            echo "<a onclick='actualizarMedicamentos($id, $idM, $cantidad, $tipo, $transaccion)' href='#actualizarMedicamentos' data-toggle='modal' title='Cambiar medicamento'><i class='fas fa-retweet ms-text-success'></i></a>";
                                                                            echo "<a class='eliminarDetalle' data-toggle='modal' title='Eliminar medicamento'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                            //echo "<a onclick='eliminarMedicamentos($id, $idM, $stock, $usados, $cantidad, $precio, $tipo, $transaccion, $hoja)' class='eliminarDetalle' data-toggle='modal' title='Eliminar medicamento'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                            //echo "<a onclick='actualizarMedicamentos($id, $idM, $stock, $usados, $cantidad, $precio, $tipo, $transaccion)' href='#actualizarMedicamentos' data-toggle='modal' title='Cambiar medicamento'><i class='fas fa-retweet ms-text-success'></i></a>";
                                                                        }
                                                                        
                                                                    ?>
                                                                </td>

                                                                <?php } ?>

                                                            </tr>

                                                            <?php } ?>

                                                            <tr id="totalMedicamentos">
                                                                <td colspan="3" class="text-right"><strong>TOTAL INSUMOS Y MEDICAMENTOS</strong></td>
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
                                                    <h6 class="text-center"><strong>No hay medicamentos o insumos que mostrar.</strong></h6>
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
                                                                        $idHoja ='"'.$paciente->idHoja.'"';
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
                                                                                //echo "<a onclick='eliminarExternos($idHojaExterno, $nombre, $precio, $cantidad, $idHoja)' class='quitarExternoAsync' href='#eliminarExternos' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                                echo "<a onclick='eliminarExternos($idHojaExterno, $nombre, $precio, $cantidad, $idHoja)' class='quitarExternoAsync' href='#' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
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
                                                    <h6 class="text-center"><strong>No hay servicios externos que mostrar.</strong></h6>
                                                </div>

                                            </div>

                                        </div>

                                        <!-- <div class="text-center" id="botoneraHoja" style="display: none;"> -->
                                        <div class="text-center" style="display: none;">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar datos</button>
                                            <button class="btn btn-light mt-4 d-inline w-20 cancelar" type="button"><i class="fa fa-times"></i> Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Detalle de medicamentos y externos -->

                    <!-- Menu opciones -->

                        <div id="circularMenu" class="circular-menu">

                        <a class="floating-btn" onclick="document.getElementById('circularMenu').classList.toggle('active');">
                            <i class="fa fa-plus text-white"></i>
                        </a>

                        <menu class="items-wrapper">
                        
                            <?php
                                if($paciente->correlativoSalidaHoja == 0 || $this->session->userdata("acceso_h") == 1 ){
                                    echo '<a href="#agregarProcedimiento" data-toggle="modal" class="menu-item" title="Agregar historia clinica"> <i class="fa fa-file-medical"></i> </a>';
                                }
                                echo '<a href="#editarPaciente" id="user-flotante" data-toggle="modal" class="menu-item" title="Actualizar datos del paciente" data-toggle="modal"><i class="fa fa-user-edit"></i></a>';

                                if($paciente->correlativoSalidaHoja == 0 && $paciente->estadoHoja == 1){
                                    if($paciente->esPaquete == 1){
                                        echo '<a href="#paqueteHoja" data-toggle="modal" class="menu-item" title="Cambiar a hoja de cobro"> <i class="fa fa-sync-alt"></i></a>';
                                    }else{
                                        echo '<a href="#paqueteHoja" data-toggle="modal" class="menu-item" title="Cambiar a paquete"> <i class="fa fa-sync-alt"></i></a>';
                                    }
                                }

                                // if($paciente->estadoHoja == 0 && $paciente->porPagos == 1){
                                if($paciente->porPagos == 1){
                                    echo '<a class="pull-bs-canvas-right d-block menu-item" href="#" title="Agregar Pago"><i class=" fa fa-thin fa-dollar-sign"></i></span></a>';
                                }/* else{
                                    echo '<a href="#" class="menu-item"></a>';
                                } */
                            ?>
                            <!-- <a href="#boletaInformativa" data-toggle="modal" class="menu-item  btn-sm" title="Boleta informativa"><i class="fa fa-file-pdf"></i></a> -->
                            <!-- <a href="#datosPaciente" data-toggle="modal" class="menu-item btn-sm" title="Actualizar información de paciente"><i class="fa fa-user-edit"></i></a> -->
                            
                            <a href="#historiaClinica" id="consentimientoInformado" class="menu-item" title="Consentimiento informado"><i class="fa fa-file"></i></a>

                            <a href="#" class="menu-item"></a>
                            <a href="#" class="menu-item"></a>
                        </menu>

                        </div>
                    <!-- Menu opciones -->
                        
                    <!-- <a class="pull-bs-canvas-right d-block menu-item btn-flotante" href="#" title="Agregar Pago"><i class=" fa fa-thin fa-dollar-sign"></i></span></a> -->
                    <?php
                        if( $this->session->userdata("acceso_h") == 10 || $this->session->userdata("acceso_h") == 1 || $this->session->userdata("acceso_h") == 2){
                            if(isset($pivoteFactura)){
                                echo '<td><a href="#" title="Imprimir factura" onclick="imprimirFactura()" class="btn btn-danger text-white btn-flotante btn-flotante"><i class="fa fa-print"></i></a></td>';
                            }
                        } 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Probando offcanvas -->
    <div class="bs-canvas bs-canvas-right position-fixed bg-light h-100">
        <header class="bs-canvas-header p-3 bg-primary overflow-auto">
            <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true" class="text-light">&times;</span></button>
            <h6 class="d-inline-block text-light mb-0 float-right">Agregar datos del pago</h6>
        </header>
        <div class="bs-canvas-content px-3 py-5">
            <div class="">
                <button type="button" class="btn btn-outline-warning btn-block mb-3">Monto pendiente: $<?php echo number_format(($totalGlobalHoja - $totalAbonado), 2); ?></button>
                
                <?php
                    //if((($totalGlobalHoja - $totalAbonado)) > 0){
                ?>
                
                <form method="post" action="<?php echo base_url(); ?>Hoja/guardar_abono/" class="needs-validation was-validated" target="blank" novalidate="">
                    <div class="form-row">
                        <div class="col-md-12 mb-1">
                            <label for="montoAbono"><strong>Monto que se abonara:</strong></label>
                            <div class="input-group">
                                <input type="hidden" class="form-control" value="<?php echo $paciente->idHoja; ?>" id="" name="idHoja"  required>
                                <input type="text" class="form-control" value="0.00" max="10" id="montoAbono" name="montoAbono"  required>
                                <div class="invalid-tooltip">
                                    En monto es requerido
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="fechaAbono"><strong>Fecha del abono:</strong></label>
                            <div class="input-group">
                                <input type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaAbono" name="fechaAbono"  required>
                                <div class="invalid-tooltip">
                                    La fecha es requerida
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="fechaAbono"><strong>Tipo:</strong></label>
                            <div class="input-group">
                                <select class="form-control" name="tipoAbono" id="tipoAbono">
                                    <option value="1" selected>Abono</option>
                                    <option value="2">Cancelación</option>
                                </select>
                                <div class="invalid-tooltip">
                                    El tipo es requerido.
                                </div>
                            </div>
                        </div>
                        

                        <div class="col-md-12 mb-3">
                            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-money-check-alt"></i> Abonar</button>
                        </div>
                </form>

                <?php
                   // }
                ?>
                
                <?php
                    if(isset($pagos) && sizeof($pagos) > 0){
                ?>
                <h6 class="text-primary text-center mt-3">Historial de pagos</h6>
                <table class="table mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Opción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $flag = 0;
                            foreach ($pagos as $row) {
                                $flag++;
                        ?>
                        <tr>
                            <th scope="row"><?php echo $flag; ?></th>
                            <td><?php echo $row->fechaAbono; ?></td>
                            <td>$<?php echo number_format($row->montoAbono, 2); ?></td>
                            <td class="text-center"><a href="<?php echo base_url(); ?>Hoja/imprimir_recibo_paquete/<?php echo $row->paqueteAbono; ?>/" target="blank"><i class="fa fa-file-pdf text-danger"></i></a></td>
                        </tr>
                        <?php
                            }
                        ?>
                        <tr>
                            <th colspan="2" class="text-center"><strong>TOTAL</strong></th>
                            <td>$<?php echo number_format($totalAbonado, 2); ?></td>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
                <?php
                    }else{
                        echo '<div><p class="text-danger">No hay pagos efectuados aún...</p></div>';
                    }
                ?>

            </div>
            
        </div>    
    </div>
<!-- Probando offcanvas -->


<!-- Navegacion -->

<!-- Modales -->
    <!-- Modal para agregar datos del Medicamento-->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="medicamentosInsumos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Lista de medicamentos</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
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
                                                    <!-- <th class="text-center" scope="col">Existencia</th> -->
                                                    <th class="text-center" scope="col">Precio</th>
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $precioFinal = 0;
                                                    $porcentaje  = $paciente->porcentajeSeguro/100;
                                                    foreach ($medicamentos as $medicamento) {
                                                        if($medicamento->ocultarMedicamento == 0){
                                                            $precioFinal = 0;
                                                            $id ='"'.$medicamento->idMedicamento.'"';
                                                            $nombre ='"'.$medicamento->nombreMedicamento.'"';
                                                            $stock ='"'.$medicamento->stockMedicamento.'"';
                                                            $usados = '"'.$medicamento->usadosMedicamento.'"';
                                                            /* if($turno == 1){
                                                                $precioV = '"'.$medicamento->precioVMedicamento.'"';
                                                            }else{
                                                                $precioV = '"'.$medicamento->feriadoMedicamento.'"';
                                                            } */
                                                            if($turno == 1){
                                                                $precioFinal = round($medicamento->precioVMedicamento + ($medicamento->precioVMedicamento * $porcentaje), 2);
                                                            }else{
                                                                $precioFinal = round($medicamento->feriadoMedicamento + ($medicamento->feriadoMedicamento * $porcentaje), 2);
                                                            }

                                                ?>
                                                <tr class="filaMedicamento">
                                                    <td class="text-center" scope="row"><?php echo $medicamento->codigoMedicamento; ?></td>
                                                    <td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento; ?></td>

                                                    <!-- <td class="text-center" scope="row">
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
                                                    </td> -->

                                                    <td class="text-center" scope="row">
                                                        <?php 
                                                        //echo number_format($medicamento->precioVMedicamento, 2);
                                                        ?>
                                                        <input type="hidden" value="<?php echo $medicamento->idMedicamento; ?>" id="test" class="form-control idM" />
                                                        <input type="hidden" value="<?php echo $medicamento->nombreMedicamento; ?>" id="test" class="form-control nombreM" />
                                                        <input type="hidden" value="<?php echo $medicamento->stockMedicamento; ?>" id="test" class="form-control stockM" />
                                                        <input type="hidden" value="<?php echo $medicamento->usadosMedicamento; ?>" id="test" class="form-control usadosM" />
                                                        <input type="text" value="<?php  echo $precioFinal; ?>" id="test" class="form-control precioM" />
                                                    </td>
                                                    <td>
                                                        <!-- <?php
                                                            // if($medicamento->stockMedicamento == 0){
                                                            //     echo '';
                                                            // }else{
                                                            //     echo '<input type="text" value="1" id="test" class="form-control cantidadM" />';
                                                            // }
                                                        ?> -->
                                                        <input type="text" value="1" id="test" class="form-control cantidadM" />
                                                        <input type="hidden" value="<?php echo $paciente->idHoja?>" id="test" class="form-control hojaHM" />
                                                        <input type="hidden" value="<?php echo $paciente->fechaHoja?>" id="test" class="form-control fechaHM" />
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                            if($medicamento->stockMedicamento > 0){
                                                                echo "<a class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                            }else{
                                                                if($medicamento->tipoMedicamento != "Servicios" && $medicamento->tipoMedicamento != "Otros servicios"){
                                                                    //echo '<span class="badge badge-gradient-danger">Sin stock</span>';
                                                                }else{
                                                                    echo "<a class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
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
    <!-- Fin Modal para agregar datos del Medicamento-->

    <!-- Modal para agregar datos del Externo-->
        <div class="modal fade"  data-backdrop="static" data-keyboard="false" id="externos" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                        if($externo->pivoteExterno == 0){
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
                                                        <input type="hidden" value="<?php echo $paciente->idHoja; ?>" class="form-control idHojaE">
                                                        <input type="hidden" value="<?php echo $paciente->fechaHoja; ?>" class="form-control fechaHojaE">
                                                    </td>

                                                    <td class="text-center">
                                                        <?php
                                                                //echo "<a onclick='agregarExterno($id, $nombre)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                                echo "<a class='ocultarAgregar agregarExterno' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                            ?>
                                                    </td>
                                                </tr>
                                                <?php  $nombreProveedor = ""; }} ?>
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
    <!-- Fin Modal para agregar datos del Externo-->

    <!-- Modal para actualizar datos de medicamentos-->
        <div class="modal fade" id="actualizarMedicamentos" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white"></i> Datos del nuevo Medicamento</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                                aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <form class="needs-validation" id="frmMedicamento" method="post" action="<?php echo base_url() ?>Hoja/actualizar_medicamento" novalidate>

                                        <div class="form-row">

                                            <!-- <div class="col-md-12 text-right">
                                                <span> Cambiar medicamento </span>
                                                <label class="ms-checkbox-wrap">
                                                    <input class="form-check-input" type="checkbox" id="newMed" value="newMed"
                                                        name="newMed">
                                                    <i class="ms-checkbox-check"></i>
                                                </label>
                                            </div> -->

                                            <div class="col-md-12">
                                                <label for=""><strong>Nuevo Medicamento/Insumo</strong></label>
                                                <div class="input-group">
                                                    <select name="idNuevoMedicamento" id="nuevoMedicamento" class="form-control controlInteligente2" required>
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
                                                    <input type="text" class="form-control numeros" id="cantidadInsumo" name="cantidadInsumoNuevo" placeholder="Ingrese la nueva cantidad" required>
                                                    <input type="hidden" class="form-control" id="idHojaInsumoV" name="idHojaInsumo" required>
                                                    <input type="hidden" class="form-control" id="idMV" name="idMedicamento" required>
                                                    <input type="hidden" class="form-control" id="cantidadV" name="cantidadMedicamento" required>
                                                    <input type="hidden" class="form-control" id="transaccionV" name="transaccion" required>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Estas seguro de cerrar esta cuenta?</p>
                        <p class="text-danger"><strong>Nota: </strong>Revisar si hay honorarios por agregar</p>
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
                        <p class="h5">
                            <?php 
                                if($paciente->correlativoSalidaHoja > 0){
                                echo '<h6 class="text-danger">Cuenta saldada, recibo generado Nº '.$paciente->correlativoSalidaHoja.'</h6>'; 
                                echo '<h6 class="text-danger"></h6>'; 
                                }
                            ?>
                            ¿Estas seguro de abrir nuevamente esta hoja de cobro?
                        </p>
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
                        <!-- <h6 class="modal-title text-white"> <i class="fa fa-file"></i> Factura de Consumidor Final</h6> -->
                        <!-- Validando si ya tiene factura -->
                            <?php
                                // if(isset($montoFactura)){
                                if(!isset($montoFactura)){
                                    if($paciente->seguroHoja != 7){
                            ?>
                               
                                <div>
                                    <span class="text-white"> Facturar todo </span>
                                    <label class="ms-switch">
                                        <input type="checkbox" id="pivoteFacturas" value="factura" name="factura">
                                        <span class="ms-switch-slider ms-switch-success round"></span>
                                    </label>
                                </div>
                            <?php } ?>

                                <div>
                                    <span class="text-white"> Cambiar nombre </span>
                                    <label class="ms-switch">
                                        <input type="checkbox" id="cambioPaciente" value="paciente" name="paciente">
                                        <span class="ms-switch-slider ms-switch-success round"></span>
                                    </label>
                                </div>

                            <?php
                                }else{
                                    echo '<h6 class="modal-title text-white"> <i class="fa fa-file"></i> Factura de Consumidor Final</h6>';
                                }

                                if(!is_null($responsable)){
                                    echo '<input type="hidden" id="pivoteResponsable" value="1" name="pivoteResponsable">';
                                }else{
                                    echo '<input type="hidden" id="pivoteResponsable" value="0" name="pivoteResponsable">';
                                }
                            ?>
                        <!-- Fin validar existencia de factura -->

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
                                                            <td>
                                                                <?php 
                                                                    if(isset($pacienteFactura)){
                                                                        echo $pacienteFactura;
                                                                    }else{
                                                                        echo $paciente->nombrePaciente;
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><strong>Edad:</strong></td>
                                                            <td><?php echo $paciente->edadPaciente; ?> años</td>
                                                            <td><strong>DUI:</strong></td>
                                                            <td><?php echo $paciente->duiPaciente; ?> <input type="hidden" value="<?php echo $paciente->duiPaciente; ?>" id="duiCabecera"></td>
                                                            <?php
                                                                if(isset($pivoteFactura)){
                                                                    // echo '<td><a href="'.base_url().'Hoja/consumidor_final_pdf/'.$paciente->idHoja.'" target="_blank" class="text-danger"><i class="fa fa-print"></i></a></td>';
                                                                    echo '<td><a href="#" onclick="imprimirFactura()" class="text-danger"><i class="fa fa-print"></i></a></td>';
                                                                }
                                                            ?>
                                                        </tr>
                                                        
                                                        <tr style="display:none;" id="filaPacienteFactura">
                                                            <td colspan="6"><input type="text" class="form-control" id="pacienteFactura" name="pacienteFactura" value="<?php echo $paciente->nombrePaciente; ?>"></td>
                                                            <td colspan="6"><input type="text" data-mask="99999999-9" class="form-control" id="duiFactura" name="duiFactura" value="<?php echo $paciente->duiPaciente; ?>"></td>
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
                                                            echo '<input type="date" class="form-control" value="'.substr($fechaFacturaHoja, 0, 10).'" id="fechaFactura" name="fechaFactura">';
                                                        }else{
                                                            echo '<input type="date" class="form-control" value="'.date('Y-m-d').'" id="fechaFactura" name="fechaFactura">';
                                                        }
                                                    ?>
                                                    
                                                    <div class="invalid-tooltip">
                                                        Ingrese el número de factura.
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Sacando el monto a facturar -->
                                            <?php
                                            $interno = 0;
                                            $externo = 0;
                                                $totalFactura = 0;
                                                if($paciente->descuentoHoja != null || $paciente->descuentoHoja != 0){
                                                    $totalFactura = ($med + $serm) - $paciente->descuentoHoja;
                                                    $interno = ($med + $serm) - $paciente->descuentoHoja; // Internos de la hoja de cobro
                                                }else{
                                                    $totalFactura = ($med + $serm);
                                                    $interno = ($med + $serm); // Internos de la hoja de cobro
                                                }

                                                if($paciente->seguroHoja > 1){
                                                    $totalFactura += $totalExternosCabecera;
                                                }
                                                $externo = $totalExternosCabecera;  // Externos de la hoja de cobro
                                            ?>
                                            
                                        <!-- Fin sacando el monto a facturar -->

                                        <div class="form-row mb-4">
                                            <div class="p-2 table-responsive pt-3">
                                                
                                                <?php if(isset($pivoteFactura)){ ?>
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
                                                                <td id="unitarioAFacturar">$<?php echo @number_format($montoFactura->totalFactura, 2);  ?></td>
                                                                <td><span id="totalAFacturar">$<?php echo @number_format($montoFactura->totalFactura, 2);  ?></span> <input type="hidden" id="montoAFacturar" value="<?php echo @$montoFactura->totalFactura; ?>" name="totalFactura"> </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php }else{ ?>
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
                                                                <td id="unitarioAFacturar">$<?php echo @number_format($totalFactura, 2);  ?></td>
                                                                <td><span id="totalAFacturar">$<?php echo @number_format($totalFactura, 2);  ?></span> <input type="hidden" id="montoAFacturar" value="<?php echo $totalFactura; ?>" name="totalFactura"> </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
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
                                    <!-- Input con resumen de internos y externos -->
                                            <input type="hidden" id="internoHojaCobro" value="<?php echo $interno; ?>">
                                            <input type="hidden" id="externoHojaCobro" value="<?php echo $externo; ?>">
                                            <input type="hidden" id="pacienteParaFactura" value="<?php echo $paciente->nombrePaciente; ?>">
                                    <!-- Fin input con resumen de internos y externos -->
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
                        <p class="h5">¿Porque deseas anular esta hoja de cobro?</p>
                    </div>

                    <form class="needs-validation" action="<?php echo base_url()?>Hoja/anular_hoja" method="post">
                        <div class="px-2 mb-3">
                            <div class="form-group">
                                <textarea name="motivoAnular" id="motivoAnular" class="form-control disableSelect" required></textarea>
                                <div class="invalid-tooltip">
                                    Debes ingresar el diagnóstico del paciente.
                                </div>
                            </div>
                        </div>
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

    <!-- Modal para agregar procedimiento -->
        <div class="modal fade" id="agregarProcedimiento" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Agregar procedimiento</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><i
                                class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <!-- Inio -->
                                    <form class="needs-validation" id="frmAgregarProcedimiento" method="post" action="<?php echo base_url(); ?>Hoja/guardar_procedimiento/" novalidate="">

                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <label for=""><strong>Procedimiento</strong></label>
                                                <textarea class="form-control disableSelect" id="procedimientoHoja" name="procedimientoHoja"><?php echo $paciente->procedimientoHoja; ?></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese la historia clinica.
                                                </div>
                                            </div>

                                            <?php
                                                if($paciente->dh == NULL || $paciente->dh == 0 ){
                                            ?>

                                            <div class="col-md-12">
                                                <label for=""><strong>Descuento</strong></label>
                                                <input type="text" class="form-control" id="descuentoHoja" name="descuentoHoja" value="<?php echo $paciente->descuentoHoja ? $paciente->descuentoHoja : 0; ?>" placeholder="Agregar porcentaje de descuento."/>
                                                <div class="invalid-tooltip">
                                                    Ingrese El descuento.
                                                </div>
                                            </div>
                                            <?php } ?>
                                            
                                            <?php 
                                                if($paciente->idSeguro <= 1){
                                            ?>
                                                <div class="col-md-12 mt-3">
                                                    <label for=""><strong>Seguro</strong></label>
                                                    <div class="input-group">
                                                        
                                                        <select class="form-control" value="<?php echo $paciente->idSeguro; ?>" id="seguroHoja" name="seguroHoja" required>
                                                            <?php
                                                                foreach ($seguros as $row) {
                                                                    echo '<option value="'.$row->idSeguro.'">'.$row->nombreSeguro.'</option>';
                                                                }
                                                            ?>
                                                        </select>

                                                        <div class="invalid-tooltip">
                                                            Debes seleccionar el seguro.
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php 
                                                } 
                                            ?>
                                        </div>

                                        <div class="text-center">
                                            <input type="hidden" value="<?php echo $paciente->idHoja; ?>" name="hojaProcedimiento">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar </button>
                                            <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                        </div>
                                    </form>
                                    <!-- Fin -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>                                    
    <!-- Fin agregar procedimiento -->

    <!-- Modal para editar paciente -->
        <div class="modal fade" id="editarPaciente" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Datos del paciente</h4>
                        <!-- <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button> -->
                    </div>
                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <!-- Inicio -->
                                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Paciente/actualizar_paciente" novalidate>
						
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Nombre Completo</strong></label>
                                                    <input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el nombre del paciente.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Edad</strong></label>
                                                    <input type="number" class="form-control numeros" min="0" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la edad del paciente.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Teléfono</strong></label>
                                                    <input type="text" class="form-control" data-mask="9999-9999" id="telefonoPaciente" name="telefonoPaciente" placeholder="Teléfono del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el teléfono del paciente.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>DUI</strong></label>
                                                    <input type="text" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el DUI del paciente.
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row">

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Fecha de nacimiento</strong></label>
                                                    <input type="date" class="form-control" id="nacimientoPaciente" name="nacimientoPaciente">
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la fecha de nacimiento del paciente.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Dirección</strong></label>
                                                    <input class="form-control" id="direccionPaciente" name="direccionPaciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar dirección del paciente.
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="form-group text-center mt-3">
                                                <input type="hidden" class="form-control" id="idPacienteU" name="idPaciente" required>
                                                <input type="hidden" class="form-control" id="returnHoja" name="returnHoja" required>
                                                <input type="hidden" value="<?php echo $paciente->idHoja; ?>" id="idHojaCobro" name="idHojaCobro">
                                                <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Actualizar paciente </button>
                                                <button type="reset" class="btn btn-default has-icon" data-dismiss="modal"><i class=" fa fa-times"></i> Cancelar </button>
                                            </div>
                                        
                                        </form>
                                    <!-- Fin -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>                                    
    <!-- Fin editar paciente -->

    <!-- Modal para paquete-hoja -->
        <div class="modal fade p-5" id="paqueteHoja" tabindex="-1" role="dialog" aria-labelledby="modal-5">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pb-5">

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <?php
                            $actual = "";
                            $nuevo = "";
                            if($paciente->esPaquete == 1){
                                $actual = "Paquete";
                                $nuevo = "Hoja de cobro";
                            }else{
                                $actual = "Hoja de cobro";
                                $nuevo = "Paquete";
                            }
                            echo '<p class="h5">¿Cambiar esta cuenta de <strong>'.$actual.'</strong> a <strong>'.$nuevo.'</strong> ?</p>';
                        ?>
                        <form class="needs-validation" action="<?php echo base_url()?>Hoja/paquete_hoja" method="post">
                            <input type="text" class="form-control my-3" name="totalPaquete" placeholder="Monto total del paquete" required>
                            <input type="hidden" id="pivotePaquete" value="<?php echo $paciente->esPaquete; ?>" name="pivotePaquete">
                            <input type="hidden" id="" value="<?php echo $paciente->idHoja; ?>" name="idHoja">

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-sync-alt"></i> Cambiar </button>
                                <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    <!-- Fin para paquete-hoja -->

    <!-- Modal para paquete-hoja -->
        <div class="modal fade p-5" id="porPagos" tabindex="-1" role="dialog" aria-labelledby="modal-5">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pb-5">

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿El proceso de pagos sera por cuotas?</p>
                        <p class="text-danger">*Este proceso no se puede revertir...</p>
                    </div>

                    <form class="needs-validation" action="<?php echo base_url()?>Hoja/por_pagos_hoja" method="post">
                        <input type="hidden" id="" value="<?php echo $paciente->idHoja; ?>" name="idHoja">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-light fa-clipboard-check"></i> Si </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> No </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    <!-- Fin para paquete-hoja -->

    <!-- Modal para menor de edad -->
        <div class="modal fade" id="consentimientoMenor" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Datos para el consentimiento de menor</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <!-- Inicio -->

                                        <!-- Datos del acompañante -->
                                            <?php
                                                $nombreResponsable = "";
                                                $edadResponsable = "";
                                                $telefonoResponsable = "";
                                                $duiResponsable = "";
                                                $profesionResponsable = "";
                                                $parentescoResponsable = "";
                                                $direccionResponsable = "";
                                                $esResponsable = "";
                                                $idResponsable = 0;
                                                if(!is_null($responsable)){
                                                    $nombreResponsable = $responsable->nombreResponsable;
                                                    $edadResponsable = $responsable->edadResponsable;
                                                    $telefonoResponsable = $responsable->telefonoResponsable;
                                                    $duiResponsable = $responsable->duiResponsable;
                                                    $profesionResponsable = $responsable->profesionResponsable;
                                                    $parentescoResponsable = $responsable->parentescoResponsable;
                                                    $direccionResponsable = $responsable->direccionResponsable;
                                                    $esResponsable = $responsable->esResponsable;
                                                    $idResponsable = $responsable->idResponsable;
                                                }
                                            ?>
                                        <!-- Datos del acompañante -->
                                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Paciente/consentimiento_menor/" novalidate>
                                            <h5 class="text-primary">Datos del menor</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Nombre Completo</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $paciente->nombrePaciente; ?>" id="nombreMenor" name="nombreMenor" placeholder="Nombre del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el nombre del menor.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Edad</strong></label>
                                                    <input type="text" class="form-control numeros" value="<?php echo $paciente->edadPaciente; ?>" min="0" id="edadMenor" name="edadMenor" placeholder="Edad del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la edad del menor.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for=""><strong>Soy</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $esResponsable; ?>" id="esMenor" name="esMenor" placeholder="Hijo/a, Sobrino/a, etc." required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el detalle.
                                                    </div>
                                                </div>

                                            </div>

                                            <h5 class="text-primary">Datos del responsable</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Nombre Completo</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $nombreResponsable; ?>" id="nombreResponsable" name="nombreResponsable" placeholder="Nombre del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el nombre del responsable.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Edad</strong></label>
                                                    <input type="number" class="form-control numeros" value="<?php echo $edadResponsable; ?>" min="0" id="edadResponsable" name="edadResponsable" placeholder="Edad del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la edad del responsable.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Teléfono</strong></label>
                                                    <input type="text" class="form-control" data-mask="9999-9999" value="<?php echo $telefonoResponsable; ?>" id="telefonoResponsable" name="telefonoResponsable" placeholder="Teléfono del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el teléfono del responsable.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>DUI</strong></label>
                                                    <input type="text" class="form-control" id="duiResponsable" value="<?php echo $duiResponsable; ?>" name="duiResponsable" data-mask="99999999-9" placeholder="DUI del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el DUI del responsable.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Profesión</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $profesionResponsable; ?>" id="profesionResponsable" name="profesionResponsable" placeholder="Profesion del responsable" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la profesión.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Parentesco</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $parentescoResponsable; ?>" id="parentescoResponsable" name="parentescoResponsable" placeholder="Parentesco del responsable" required>
                                                    <!-- <select class="form-control" value="<?php echo $parentescoResponsable; ?>" id="parentescoResponsable" name="parentescoResponsable" required>
                                                        <option value="">.:Seleccionar:.</option>
                                                        <option value="Mamá">Mamá</option>
                                                        <option value="Papá">Papá</option>
                                                        <option value="Hermano">Hermano</option>
                                                        <option value="Hermanoa">Hermanoa</option>
                                                        <option value="Tio">Tio</option>
                                                        <option value="Tia">Tia</option>
                                                        <option value="Abuelo">Abuelo</option>
                                                        <option value="Abuela">Abuela</option>
                                                        <option value="Otro">Otro</option>
                                                    </select> -->
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el parentesco.
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for=""><strong>Dirección</strong></label>
                                                    <input class="form-control" value="<?php echo $direccionResponsable; ?>" id="direccionResponsable" name="direccionResponsable" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar dirección del responsable.
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="form-group text-center mt-3">
                                                <input type="hidden" value="<?php echo $paciente->idPaciente; ?>" id="idMenor" name="idMenor">
                                                <input type="hidden" value="<?php echo $idResponsable; ?>" id="idResponsable" name="idResponsable">
                                                <input type="hidden" value="<?php echo $paciente->idHoja; ?>" id="idHojaCobro" name="idHojaCobro">
                                                <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Guardar </button>
                                                <button type="reset" class="btn btn-default has-icon" data-dismiss="modal"><i class=" fa fa-times"></i> Cancelar </button>
                                            </div>
                                        
                                        </form>
                                    <!-- Fin -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>                                    
    <!-- Fin menor de edad -->

    <!-- Modal para menor de edad -->
        <div class="modal fade" id="consentimientoMayor" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Datos para el consentimiento</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <!-- Inicio -->

                                        <!-- Datos del acompañante -->
                                            <?php
                                                $nombreResponsable = "";
                                                $duiResponsable = "";
                                                $parentescoResponsable = "";
                                                $direccionResponsable = "";
                                                $profesionPaciente = ""; //Dato del paciente;
                                                $idResponsable = 0;
                                                if(!is_null($responsable)){
                                                    $nombreResponsable = $responsable->nombreResponsable;
                                                    $duiResponsable = $responsable->duiResponsable;
                                                    $parentescoResponsable = $responsable->parentescoResponsable;
                                                    $idResponsable = $responsable->idResponsable;

                                                    /* $edadResponsable = $responsable->edadResponsable;
                                                    $telefonoResponsable = $responsable->telefonoResponsable;
                                                    $profesionResponsable = $responsable->profesionResponsable;
                                                    $direccionResponsable = $responsable->direccionResponsable;
                                                    $esResponsable = $responsable->esResponsable; */
                                                }
                                            ?>
                                        <!-- Datos del acompañante -->

                                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Paciente/consentimiento_mayor/" novalidate>

                                            <h5 class="text-primary">Datos del paciente</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Nombre Completo</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $paciente->nombrePaciente; ?>" id="pacienteConsentimiento" name="pacienteConsentimiento" placeholder="Nombre del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el nombre del paciente.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Edad</strong></label>
                                                    <input type="number" class="form-control numeros" value="<?php echo $paciente->edadPaciente; ?>" min="0" id="edadConsentimiento" name="edadConsentimiento" placeholder="Edad del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la edad del paciente.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>DUI</strong></label>
                                                    <input type="text" class="form-control" id="duiConsentimiento" value="<?php echo $paciente->duiPaciente; ?>" name="duiConsentimiento" data-mask="99999999-9" placeholder="DUI del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el DUI del paciente.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Profesión</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $paciente->profesionPaciente; ?>" id="profesionConsentimiento" name="profesionConsentimiento" placeholder="Profesion del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la profesión.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for=""><strong>Dirección</strong></label>
                                                    <input class="form-control" value="<?php echo $paciente->direccionPaciente; ?>" id="direccionConsentimiento" name="direccionConsentimiento" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar dirección del responsable.
                                                    </div>
                                                </div>

                                            </div>

                                            <h5 class="text-primary">Datos del responsable(Si tiene)</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Nombre Completo</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $nombreResponsable; ?>" id="responsableConsentimiento" name="responsableConsentimiento" placeholder="Nombre del responsable"/>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el nombre del responsable.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Parentesco</strong></label>
                                                    <input type="text" class="form-control" value="<?php echo $parentescoResponsable; ?>" min="0" id="parentescoConsentimiento" name="parentescoConsentimiento" placeholder="Edad del responsable"/>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la edad del responsable.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>DUI</strong></label>
                                                    <input type="text" class="form-control" id="duiResponsableC" value="<?php echo $duiResponsable; ?>" name="duiResponsableC" data-mask="99999999-9" placeholder="DUI del paciente"/>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el DUI del responsable.
                                                    </div>
                                                </div>

                                            </div>

                                            


                                            <div class="form-group text-center mt-3">
                                                <input type="hidden" value="<?php echo $paciente->idPaciente; ?>" id="idPacienteC" name="idPacienteC">
                                                <input type="hidden" value="<?php echo $idResponsable; ?>" id="idResponsableC" name="idResponsableC">
                                                <input type="hidden" value="<?php echo $paciente->idHoja; ?>" id="idHojaCobroC" name="idHojaCobroC">
                                                <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Guardar </button>
                                                <button type="reset" class="btn btn-default has-icon" data-dismiss="modal"><i class=" fa fa-times"></i> Cancelar </button>
                                            </div>
                                        
                                        </form>
                                    <!-- Fin -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>                                    
    <!-- Fin menor de edad -->

    <!-- Modal detalle anulado -->
        <div class="modal fade" id="detalleAnulado" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Detalle de la hoja anulada</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <?php echo $paciente->detalleAnulada; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>                                    
    <!-- Fin detalle anulado -->

    <!-- Modal pagara medico -->
        <div class="modal fade p-5" id="pagaraMedico" tabindex="-1" role="dialog" aria-labelledby="modal-5">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pb-5">

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Esta cuenta sera cancelada por el médico? </strong></p>
                        <p class="text-danger">*Este proceso no se puede revertir...</p>
                    </div>

                    <form class="needs-validation" action="<?php echo base_url()?>Hoja/pagara_medico" method="post">
                        <input type="hidden" id="" value="<?php echo $paciente->idHoja; ?>" name="idHoja">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-sync-alt"></i> Cambiar </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    <!-- Fin pagara medico -->

    <!-- Modal agregar monto paquete -->
        <div class="modal fade p-5" id="agregarMontoPaquete" tabindex="-1" role="dialog" aria-labelledby="modal-5">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pb-5">

                    <div class="modal-header bg-primary">
                        <h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">Agregar el monto del paquete. </strong></p>
                        <form class="needs-validation" action="<?php echo base_url()?>Hoja/actualizar_monto_paquete" method="post">
                            <input type="text" class="form-control mb-3" value="<?php echo $paciente->totalPaquete; ?>" name="montoTotalPaquete">
                            <input type="hidden" id="" value="<?php echo $paciente->idHoja; ?>" name="idHoja">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-save"></i> Guardar </button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    <!-- Fin agregar monto paquete -->

<!-- Fin modales -->

<!-- Script JS para agregar un medicamento -->
    <script>
        // Validando el tamaño de las tablas
        $(document).ready(function() {
            $('.controlInteligente').select2({
                theme: "bootstrap4",
            });
            $('.controlInteligente2').select2({
                theme: "bootstrap4",
                dropdownParent: $("#actualizarMedicamentos")
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
            var datos = {
                id: $(this).closest('tr').find(".idM").val(),
                nombre: $(this).closest('tr').find(".nombreM").val(),
                stock: $(this).closest('tr').find(".stockM").val(),
                usados: $(this).closest('tr').find(".usadosM").val(),
                precioV: $(this).closest('tr').find(".precioM").val(),
                cantidad: $(this).closest('tr').find(".cantidadM").val(),
                idHoja: $(this).closest('tr').find(".hojaHM").val(),
                fechaHoja: $(this).closest('tr').find(".fechaHM").val(),

            }
            $.ajax({
                url: "../../agregar_medicamento_hoja",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.success('Medicamento insertado', 'Aviso!');
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('No se agrego el medicamento...', 'Aviso!');
                        }
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('No se agrego el medicamento...', 'Aviso!');

                    }
                }
            });
            $(".medicamentosContainer").show();
            $(".medicamentosContainerNull").hide();
            $("#botoneraHoja").show();
            $(".form-control-sm").focus();
        });

        $('.cantidadM').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                // ejecutando procesos
                $(this).closest('tr').remove();
                var datos = {
                    id: $(this).closest('tr').find(".idM").val(),
                    nombre: $(this).closest('tr').find(".nombreM").val(),
                    stock: $(this).closest('tr').find(".stockM").val(),
                    usados: $(this).closest('tr').find(".usadosM").val(),
                    precioV: $(this).closest('tr').find(".precioM").val(),
                    cantidad: $(this).closest('tr').find(".cantidadM").val(),
                    idHoja: $(this).closest('tr').find(".hojaHM").val(),
                    fechaHoja: $(this).closest('tr').find(".fechaHM").val(),

                }

                $.ajax({
                    url: "../../agregar_medicamento_hoja",
                    type: "POST",
                    data: datos,
                    success:function(respuesta){
                        var registro = eval(respuesta);
                        if (Object.keys(registro).length > 0){
                            if(registro.estado == 1){
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.success('Medicamento insertado', 'Aviso!');
                            }else{
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.error('No se agrego el medicamento...', 'Aviso!');
                            }
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('No se agrego el medicamento...', 'Aviso!');

                        }
                    }
                });
                $(".form-control-sm").focus();
            }
        });
  
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
        function actualizarMedicamentos(id, idM, cantidad, tipo, transaccion){
            $("#idHojaInsumoV").val(id);        // Id hoja-insumo
            $("#idMV").val(idM);                // Id del medicamento actual
            $("#cantidadV").val(cantidad);      // Cantidad del medicamento actual
            $("#transaccionV").val(transaccion);// Id hoja-insumo
        }

        $(document).on('click', '.eliminarDetalle', function(event) {
            var respuesta = window.prompt("Motivo por el cuál desea eliminar este registro:");
            if (respuesta != "" && respuesta.trim().length > 0) {
                var datos = {
                    idHojaInsumo : $(this).closest('tr').find(".txTransaccion").val(),
                    idMedicamento : $(this).closest('tr').find(".txtIdM").val(),
                    stockMedicamento : $(this).closest('tr').find(".txtStock").val(),
                    usadosMedicamento : $(this).closest('tr').find(".txtUsados").val(),
                    cantidadMedicamento : $(this).closest('tr').find(".txtCantidad").val(),
                    precioMedicamento : $(this).closest('tr').find(".txtPrecio").val(),
                    transaccion : $(this).closest('tr').find(".txTransaccion").val(),
                    hoja : $(this).closest('tr').find(".txtIdHoja").val(),
                    motivo: respuesta,
                }
            
                // Si el usuario hizo clic en "Aceptar" y proporcionó un texto, se puede usar el valor ingresado
                // alert("Texto ingresado: " + respuesta);

                // alert(datos);
                $.ajax({
                    url: "../../eliminar_medicamento_hoja",
                    type: "POST",
                    data: datos,
                    success:function(respuesta){
                        var registro = eval(respuesta);
                        if (Object.keys(registro).length > 0){
                            if(registro.estado == 1){
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.success('Medicamento eliminado', 'Aviso!');
                            }else{
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.error('No se elimino el medicamento...', 'Aviso!');
                            }
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('No se elimino el medicamento...', 'Aviso!');

                        }
                    }
                });


                // Recalculando totales
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
                // Recalculando totales

                // console.log(datos);
            }else{
                // alert("No se eliminara el registro.");
            }

        }); 
 
     /*    $(document).on('click', '.eliminarDetalle', function(event) {
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
        }); */


    </script>

        <!-- Script JS para agregar un externo -->
    <script>
        // Agregar externo a la lista
        $(document).on('click', '.agregarExterno', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();

            var externo = {
                id : $(this).closest('tr').find(".idE").val(),
                nombre : $(this).closest('tr').find(".nombreE").val(),
                precio : $(this).closest('tr').find(".precioE").val(),
                idHoja : $(this).closest('tr').find(".idHojaE").val(),
                fechaHoja : $(this).closest('tr').find(".fechaHojaE").val(),
            }

            $.ajax({
                url: "../../agregar_externo_hoja",
                type: "POST",
                data: externo,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.success('Externo insertado', 'Aviso!');
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('No se agrego el externo...', 'Aviso!');
                        }
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('No se agrego el externo...', 'Aviso!');

                    }
                }
            });

            $(".form-control-sm").focus();
            
        });

        $('.precioE').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                // ejecutando procesos
                $(this).closest('tr').remove();
                
                var externo = {
                    id : $(this).closest('tr').find(".idE").val(),
                    nombre : $(this).closest('tr').find(".nombreE").val(),
                    precio : $(this).closest('tr').find(".precioE").val(),
                    idHoja : $(this).closest('tr').find(".idHojaE").val(),
                    fechaHoja : $(this).closest('tr').find(".fechaHojaE").val(),
                }

                $.ajax({
                    url: "../../agregar_externo_hoja",
                    type: "POST",
                    data: externo,
                    success:function(respuesta){
                        var registro = eval(respuesta);
                        if (Object.keys(registro).length > 0){
                            if(registro.estado == 1){
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.success('Externo insertado', 'Aviso!');
                            }else{
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.error('No se agrego el externo...', 'Aviso!');
                            }
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('No se agrego el externo...', 'Aviso!');

                        }
                    }
                });
                $(".form-control-sm").focus();

            }
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

        // Actualizar y eliminar externos
        function actualizarExternos(idHojaExterno, nombre, precio, cantidad) {
            $("#idExterno").val(idHojaExterno);
            $("#nombreExterno").val(nombre);
            $("#precioExterno").val(precio);
            $("#cantidadExterno").val(cantidad);
        }

        function eliminarExternos(idHojaExterno, nombre, precio, cantidad, idHoja) {
            var datos = {
                idExterno : idHojaExterno,
                idHoja : idHoja,
            }

            $.ajax({
                url: "../../eliminar_externo_hoja",
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

        $(document).on('click', '.quitarExternoAsync', function(event) {
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

        $(document).on('click', '.cancelar', function(event) {
            event.preventDefault();
            location.reload();
        });

        $(document).on('click', '.close', function(event) {
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

                                $("#stock2V").val(registro[i]["stockMedicamento"]);
                                $("#usados2V").val(registro[i]["usadosMedicamento"]);
                                $("#precio2V").val(registro[i]["precioVMedicamento"]);
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
                    dui += '<input type="text" class="form-control" value="" data-mask="99999999-9" name="duiPaciente" id="duiPaciente" required>';
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

<script>
    $(document).on('click', '.activarCampos', function(event) {
        event.preventDefault();
        $(this).closest('tr').find(".bloquearCampos").show();
        $(this).closest('tr').find(".activarCampos").hide();
        $(this).closest('tr').find(".lblPrecio").hide();
        $(this).closest('tr').find(".txtPrecio").attr("type", "text");
        $(this).closest('tr').find(".lblCantidad").hide();
        $(this).closest('tr').find(".txtCantidad").attr("type", "text");
        $(this).closest('tr').find(".lblTotalUMedicamento").hide();
        $(this).closest('tr').find(".totalUMedicamento").attr("type", "text");
    });

    $(document).on('click', '.bloquearCampos', function(event) {
        event.preventDefault();
        var cantidadNueva = $(this).closest('tr').find(".txtCantidad").val();
        /* Ocultando o mostrando campos */
            $(this).closest('tr').find(".bloquearCampos").hide();
            $(this).closest('tr').find(".activarCampos").show();
            $(this).closest('tr').find(".lblPrecio").show();
            $(this).closest('tr').find(".txtPrecio").attr("type", "hidden");
            $(this).closest('tr').find(".lblCantidad").show();
            $(this).closest('tr').find(".txtCantidad").attr("type", "hidden");
            $(this).closest('tr').find(".lblTotalUMedicamento").show();
            $(this).closest('tr').find(".totalUMedicamento").attr("type", "hidden");
        /* Fin Ocultando */

        var medicamento = {
            id : $(this).closest('tr').find("#idM").val(),
            nombre : $(this).closest('tr').find("#nombreMedicamentoA").val(),
            precio : $(this).closest('tr').find(".txtPrecio").val(),
            cantidadNueva : $(this).closest('tr').find(".txtCantidad").val(),
            idHoja : $(this).closest('tr').find("#idHoja").val(),
            idHojaInsumo : $(this).closest('tr').find("#transaccion").val(),
            stock : $(this).closest('tr').find("#stock").val(),
            usados : $(this).closest('tr').find("#usados").val(),
            cantidadVieja : $(this).closest('tr').find("#cantidad").val(),
            transaccion : $(this).closest('tr').find("#transaccion").val(),
        }
        
        /* Actualizando cantidad actual */
            $(this).closest('tr').find("#cantidad").val(cantidadNueva);
        /* Fin actualizando cantidad actual */

        $.ajax({
            url: "../../actualizar_medicamento_hoja",
            type: "POST",
            data: medicamento,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.success('Medicamento actualizado', 'Aviso!');
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('No se actualizo el medicamento...', 'Aviso!');
                    }
                }else{
                    toastr.remove();
                    toastr.options = {
                        "positionClass": "toast-top-left",
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "1000",
                        "extendedTimeOut": "50",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                        },
                    toastr.error('No se actualizo el medicamento...', 'Aviso!');
                }
            }
        });
        
        
    });

    $(document).on('change', '.txtCantidad', function(event) {
        event.preventDefault();
        var cantidad = $(this).val(); // Cantidad de cada caja de texto
        var precio = $(this).closest('tr').find(".txtPrecio").val(); // Precio de cada producto

        var totalMedicamentos = 0; // Total de la factura
        var totalUMedicamento = cantidad * precio; // Total por cada producto


        $(this).closest('tr').find(".totalUMedicamento").val(totalUMedicamento.toFixed(2));
        $(this).closest('tr').find(".lblTotalUMedicamento").html("$ " + totalUMedicamento.toFixed(2));
        $(this).closest('tr').find(".lblPrecio").html("$ " + precio);
        $(this).closest('tr').find(".lblCantidad").html(cantidad);

        // Sumando todos los input con totales unitarios
        $('.totalUMedicamento').each(function() {
            totalMedicamentos += parseFloat($(this).val());
        });

        $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(
        2)); // Asignando el valor a lblTotalMedicamentos
        //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
    });

    $(document).on('change', '.txtPrecio', function(event) {
        event.preventDefault();
        var precio = $(this).val(); // Cantidad de cada caja de texto
        var cantidad = $(this).closest('tr').find(".txtCantidad").val(); // Precio de cada producto
        var totalMedicamentos = 0; // Total de la factura
        var totalUMedicamento = cantidad * precio; // Total por cada producto
        
        $(this).closest('tr').find(".totalUMedicamento").val(totalUMedicamento.toFixed(2));
        $(this).closest('tr').find(".lblTotalUMedicamento").html("$ " + totalUMedicamento.toFixed(2));
        $(this).closest('tr').find(".lblPrecio").html("$ " + precio);
        $(this).closest('tr').find(".lblCantidad").html(cantidad);
        
        // Sumando todos los input con totales unitarios
        $('.totalUMedicamento').each(function() {
            totalMedicamentos += parseFloat($(this).val());
        });
        
        $("#lblTotalMedicamentos").html("$ " + totalMedicamentos.toFixed(2)); // Asignando el valor a lblTotalMedicamentos
        //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
    });
</script>

<script>
    let start;
    let isEditing = false;
    let kc = 0; //Key Code
    let tableDT;

    $(function() {
        start = $('#comienzo');
        start.addClass('cell-focus');

        document.onkeydown = checkKey;
        document.onclick = deleteInput;

        //Bloque para mover las celdas activas
        function checkKey(e) {
            if (isEditing) return;
            e = e || window.event;
            kc = e.keyCode;
            if (kc === 38) {
                // up arrow
                doTheNeedful($(start.parent().prev().find('td')[start.index()]));
            } else if (kc === 40) {
                // down arrow
                doTheNeedful($(start.parent().next().find('td')[start.index()]));
            } else if (kc === 37) {
                // left arrow
                doTheNeedful(start.prev());
            } else if (kc === 39) {
                // right arrow
                doTheNeedful(start.next());
            }else if (kc === 13) {
                // Enter
                replacedByAnInputText(e);
            }else if (kc === 9) {
                //Tab
                if (e.shiftKey){
                    if (start.prev().length === 0){
                        doTheNeedful($(start.parent().prev().children().last()));
                    }else{
                        doTheNeedful(start.prev());
                    }
                } else{
                    if (start.next().length === 0){
                        doTheNeedful($(start.parent().next().children()[0]));
                    }else{
                        doTheNeedful(start.next());
                    }
                }
                e.stopPropagation();
                e.preventDefault();
            }
        }

        function deleteInput(e) {
            console.log(start)
        }

        $("#tblMedicamentos tr").on('click', function(e) {
            if ($(e.target).closest('td')) {
                start.removeClass('cell-focus');
                start = $(e.target);
                start.addClass('cell-focus');
               // e.stopPropagation();
            }

        })
    } );

    function doTheNeedful(sibling) {
        if (sibling.length === 1) {
            start.removeClass('cell-focus');
            sibling.addClass('cell-focus');
            start = sibling;
        }
    }

</script>

<?php
    /* if($paciente->tipoHoja == "Ingreso"){
        echo "<script>
                var window_height = 5000;
                console.log(window_height);
                $('html, body').animate({scrollTop:window_height}, 'slow');
            </script>";
    } */
?>


<!-- Inicio para factura -->

    <!-- Impresora normal con margen superior de 28mm -->
        <div class="containerFactura" style="display: none;">
            <div id="factura">
                <table style="border-collapse: collapse; border-spacing: 0; width: 18.2cm;" id="tabla_factura">
                    <tr>
                        <td rowspan="3" style="width: 3cm; text-align: right; border-collapse: collapse; border-spacing: 0;"></td>
                        <td style="width: 8.5cm; font-size: 12px; text-align: left;"><?php echo $pacienteFactura;?></td>
                        <td style="font-size: 12px; text-align: left;"><?php echo $fechaFacturaHoja;?></td>
                    </tr>
                    <tr>
                        <td style="width: 8.5cm; font-size: 12px; text-align: left;" colspan="4"><?php echo $paciente->direccionPaciente;?></td>
                        <td style="font-size: 12px; text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="width: 8.5cm; font-size: 12px; text-align: left;" colspan="4"><?php echo $duiFactura;?></td>
                        <td style="font-size: 12px; text-align: left;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="height: 1cm; text-align: left;"></td>
                    </tr>
                </table>
                <table style="border-collapse: collapse; border-spacing: 0; height: 7.4cm; width: 18.2cm;">
                    <tr>
                        <td style="width: 1.7cm; position: relative; top: -100px; font-size: 13px;">1</td>
                        <td style="width: 9.5cm; position: relative; top: -100px;  font-size: 13px;">Medicamentos e Insumos Médicos</td>
                        <td style="width: 1.6cm; position: relative; left: -30px; top: -100px; font-size: 13px;"><?php echo number_format($montoFactura->totalFactura, 2);  ?></td>
                        <td style="width: 1.6cm; text-align: left;"></td>
                        <td style="width: 1.6cm; text-align: left;"></td>
                        <td style="width: 2.4cm; position: relative; left: -15px; top: -100px; font-size: 13px; padding-left:10px"><?php echo number_format($montoFactura->totalFactura, 2);  ?></td>
                    </tr>
                </table>

                <div style="width: 18.2cm">
                    <div style="width: 13.13cm; float: left">
                        <table style="border-collapse: collapse; border-spacing: 0;">
                            <tr>
                                <td colspan="2" style="height: 0.9cm; text-align: left; font-size: 11px; padding-left:15px">
                                
                                <?php
                                    echo  "<br>";
                                    $numero = $montoFactura->totalFactura;
                                    $arregloNumero = explode(".", $numero);
                                    if(isset($arregloNumero[1])){
                                        echo strtoupper(convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
                                    }else{
                                        echo strtoupper(convertir($numero)." 00/100 Dolares"); 
                                    }
                                ?>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="height: 0.25cm; text-align: left;"></td>
                            </tr>
                            <tr>
                                <td rowspan="3" style="width: 4.6cm;"></td>
                                <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 11px; padding-top:15px">
                                    <?php
                                        if($totalGlobalHoja >= 200){
                                            echo $pacienteFactura;
                                        }else
                                        echo " ";
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 11px;">
                                    <?php
                                        if($totalGlobalHoja >= 200){
                                            echo $duiFactura;
                                        }else
                                        echo " ";
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 8px;"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="height: 0.9cm; text-align: left; font-size: 8px;"></td>
                            </tr>
                        </table>
                    </div>

                    <div style="width: 4.94cm; float: left">
                        <table style="border-collapse: collapse; border-spacing: 0; width: 4.94cm;">
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                <td style="height: 0.4cm; text-align: left;"></td>
                            </tr>
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                            </tr>
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                            </tr>
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                            </tr>
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                <td style="height: 0.4cm; text-align: left; font-size: 13px; position: relative; left: -25px; padding-top: 10px"><?php echo number_format($montoFactura->totalFactura, 2);  ?></td>
                            </tr>
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                            </tr>
                            <tr>
                                <td style="height: 0.4cm; width: 3cm; text-align: left; "></td>
                                <td style="height: 0.4cm; text-align: left; font-size: 13px; position: relative; left: -25px;"><?php echo number_format($montoFactura->totalFactura, 2);  ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Base -->
            <!-- <div class="containerFactura" style="display: none;">
                <div id="factura">
                    <table style="border-collapse: collapse; border-spacing: 0; width: 18.2cm;" id="tabla_factura">
                        <tr>
                            <td rowspan="3" style="width: 3cm; text-align: right; border-collapse: collapse; border-spacing: 0;"></td>
                            <td style="width: 8.5cm; font-size: 12px; text-align: left;"><?php echo $paciente->nombrePaciente;?></td>
                            <td style="font-size: 12px; text-align: left;"><?php echo date('Y-m-d');?></td>
                        </tr>
                        <tr>
                            <td style="width: 8.5cm; font-size: 12px; text-align: left;"><?php echo $paciente->direccionPaciente;?></td>
                            <td style="font-size: 12px; text-align: left;"></td>
                        </tr>
                        <tr>
                            <td style="width: 8.5cm; font-size: 12px; text-align: left;"><?php echo $paciente->duiPaciente;?></td>
                            <td style="font-size: 12px; text-align: left;"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 1cm; text-align: left;"></td>
                        </tr>
                    </table>
                    <table style="border-collapse: collapse; border-spacing: 0; height: 7.4cm; width: 18.2cm;">
                        <tr>
                            <td style="width: 1.7cm; position: relative; top: -100px; font-size: 13px;">1</td>
                            <td style="width: 10.2cm; position: relative; top: -100px;  font-size: 13px;">Medicamentos e Insumos Médicos</td>
                            <td style="width: 1.5cm; position: relative; top: -100px; font-size: 13px;"><?php echo number_format(($med + $serm), 2);  ?></td>
                            <td style="width: 1cm; text-align: left;"></td>
                            <td style="width: 1.4cm; text-align: left;"></td>
                            <td style="width: 2.3cm; position: relative; top: -100px; font-size: 13px;"><?php echo number_format(($med + $serm), 2);  ?></td>
                        </tr>
                    </table>

                    <div style="width: 18.2cm">
                        <div style="width: 13.13cm; float: left">
                            <table style="border-collapse: collapse; border-spacing: 0;">
                                <tr>
                                    <td colspan="2" style="height: 0.9cm; text-align: left; font-size: 13px;">
                                    Son:
                                    <?php
                                        echo  "<br>";
                                        $numero = ($med + $serm);
                                        $arregloNumero = explode(".", $numero);
                                        if(isset($arregloNumero[1])){
                                        echo strtoupper(convertir($numero)." ".$arregloNumero[1]."/100 Dolares");
                                        }else{
                                        echo strtoupper(convertir($numero)." 00/100 Dolares");
                                        }
                                    ?>
                                </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="height: 0.25cm; text-align: left;"></td>
                                </tr>
                                <tr>
                                    <td rowspan="3" style="width: 4.6cm;"></td>
                                    <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 13px;">
                                        <?php
                                            if($totalGlobalHoja >= 200){
                                                echo $paciente->nombrePaciente;
                                            }else
                                            echo " ";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 13px;">
                                        <?php
                                            if($totalGlobalHoja >= 200){
                                                echo $paciente->duiPaciente;
                                            }else
                                            echo " ";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 0.3cm; width: 8.5275cm; text-align: left; font-size: 8px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="height: 0.9cm; text-align: left; font-size: 8px;"></td>
                                </tr>
                            </table>
                        </div>

                        <div style="width: 4.94cm; float: left">
                            <table style="border-collapse: collapse; border-spacing: 0; width: 4.94cm;">
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                    <td style="height: 0.4cm; text-align: left;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                    <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                    <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                    <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                    <td style="height: 0.4cm; text-align: left; font-size: 13px;"><?php echo number_format(($med + $serm), 2);  ?></td>
                                </tr>
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left;"></td>
                                    <td style="height: 0.4cm; text-align: left; font-size: 8px;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 0.4cm; width: 3cm; text-align: left; "></td>
                                    <td style="height: 0.4cm; text-align: left; font-size: 13px;"><?php echo number_format(($med + $serm), 2);  ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
        <!-- Fin base -->
    <!-- Fin Inpresora normal -->

<!-- Fin factura -->

<!-- Script para imprimir y consentimiento informado-->
<script>
   function imprimirFactura(){
        var elemento=document.getElementById('factura');
        var pantalla=window.open(' ','popimpr');

        pantalla.document.write('<html><head><title>' + document.title + '</title>');
        pantalla.document.write('<link href="<?= base_url() ?>public/css/factura_consumidor_final.css" rel="stylesheet" /></head><body>');
        pantalla.document.write(elemento.innerHTML);
        pantalla.document.write('</body></html>');
        pantalla.document.close();
        pantalla.focus();
        pantalla.onload = function() {
            pantalla.print();
            pantalla.close();
        };
        //    $(".mos").hide();
        //    $(".ocultarImprimir").show();
   }

   $(document).on('click', '#consentimientoInformado', function() {
        var edad = <?php echo $paciente->edadPaciente; ?>;
        if(edad < 18){
            $("#consentimientoMenor").modal("show");
        }else{
            $("#consentimientoMayor").modal("show");
        }
    });

</script>

<script>
    $(document).keydown(function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '39') {
            // ejecutando procesos
            $(".cantidadM:first").focus();
        }
    })

    $(function(){
        $('.cantidadM').keyup(function(e) {
            if(e.keyCode==38)//38 para arriba
            mover(e,-1);
            if(e.keyCode==40)//40 para abajo
            mover(e,1);
        });
    });

    function mover(event, to) {
        let list = $('.cantidadM');
        let index = list.index($(event.target));
        index = (index + to) % list.length;
        list.eq(index).focus();
    }

    $(document).ready(function() {
        $("#pivoteFacturas").click(function() {
            var valor = $('input:checkbox[name=factura]:checked').val();
            var interno = parseFloat($("#internoHojaCobro").val());
            var externo = parseFloat($("#externoHojaCobro").val());
            var total = interno + externo;
            if (valor == "factura") {
                /* $("#generarExternos").hide();
                $("#generarRecibos").show(); */
                $("#montoAFacturar").val(total.toFixed(2));
                $("#unitarioAFacturar").html(total.toFixed(2));
                $("#totalAFacturar").html(total.toFixed(2));
            } else {
                $("#montoAFacturar").val(interno.toFixed(2));
                $("#unitarioAFacturar").html(interno.toFixed(2));
                $("#totalAFacturar").html(interno.toFixed(2));
                /* $("#generarRecibos").hide();
                $("#generarExternos").show(); */
            }
        });
    });

    $(document).ready(function() {
        $("#cambioPaciente").click(function() {
            var nombre = '';
            var dui = '';
            var valor = $('input:checkbox[name=paciente]:checked').val(); 
            var paciente = $("#pacienteParaFactura").val();

            var pivote = $("#pivoteResponsable").val();
            if(pivote == 1){
                nombre = '<?php echo @$responsable->nombreResponsable;?>';
                dui = '<?php echo @$responsable->duiResponsable;?>';
            }else{
                nombre = '<?php echo $paciente->nombrePaciente;?>';
                dui = '<?php echo $paciente->duiPaciente;?>';
            }

            // alert(pivote);

            if (valor == "paciente") {
                $("#filaPacienteFactura").show();
                if(pivote == 1){
                    $("#pacienteFactura").val('<?php echo @$responsable->nombreResponsable;?>');
                    $("#duiFactura").val('<?php echo @$responsable->duiResponsable;?>');
                }else{
                    $("#pacienteFactura").val('<?php echo $paciente->nombrePaciente;?>');
                    $("#duiFactura").val('<?php echo $paciente->duiPaciente;?>');
                }
                
            } else {
                // alert(paciente);
                $("#pacienteFactura").val('<?php echo $paciente->nombrePaciente;?>');
                $("#duiFactura").val('<?php echo $paciente->duiPaciente;?>');
                $("#filaPacienteFactura").hide();
            }
        });
    });

    $(document).on('click', '.btnRecibo', function() {
        $(".btnRestaurar").hide();
        // setInterval("actualizar()", 5000);
    });

    // function actualizar(){location.reload(true);}
</script>

<!-- Procesos para examenes de laboratorio -->
<script src="<?php echo base_url(); ?>public/js/detalle_hoja.js"> </script>