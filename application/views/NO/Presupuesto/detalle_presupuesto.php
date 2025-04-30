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
        $totalMedicamentos += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);

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
        $totalExternos += ($externo->precioExterno * $externo->cantidadExterno);
        $totalExternosCabecera += ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
    }
// Fin total hoja de cobro.

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
                                <!-- Si la hoja esta abierta o no -->
                                    
                                        <a href="#medicamentos" onclick="cambiar(1)" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-medkit"></i> Medicamentos e insumos</a>
                                        <a href="#externos" onclick="cambiar(2)" data-toggle="modal" class="btn btn-primary text-white"><i class="fa fa-user-md"></i> Servicios externos</a>
                                        <a href="<?php echo base_url(); ?>Hoja/resumen_presupuesto/<?php echo $presupuesto->idPresupuesto; ?>" target="blank" class="btn btn-outline-danger"><i class="fa fa-file-pdf"></i> Resumen cotización</a>
                                        <a href="<?php echo base_url(); ?>Hoja/hoja_presupuesto/<?php echo $presupuesto->idPresupuesto; ?>/" target="blank" class="btn btn-outline-danger"><i class="fa fa-file"></i> Crear hoja</a>
                                 
                                <!-- Fin hoja abierta o no -->
                            </div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                    <!-- Inicio cabecera de la hoja -->
                        <div class="alert-primary p-2 table-responsive bordeContenedor pt-3">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Código:</strong></td>
                                    <td><?php echo $presupuesto->codigoPresupuesto; ?></td>
                                    <td><strong>Paciente:</strong></td>
                                    <td><?php echo $presupuesto->pacientePresupuesto; ?></td>
                                    <td><strong>Tipo:</strong></td>
                                    <td><?php echo $presupuesto->tipoPresupuesto; ?></td>
                                </tr>

                                <tr>
                                    <td><strong>Medico:</strong></td>
                                    <td><?php echo $presupuesto->nombreMedico; ?></td>
                                    <td><strong>Fecha:</strong></td>
                                    <td><?php echo $presupuesto->fechaPresupuesto; ?></td>
                                </tr>

                                <tr>
                                <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Medicamentos $<?php echo number_format($med, 2);  ?></button></td>
                                    <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Servicios internos $<?php echo number_format($serm, 2);  ?></button></td>
                                    <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Total interno $<?php echo number_format(($med + $serm), 2);  ?></button></td>
                                    <td><button type="button" class="btn btn-info btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Total externo: $<?php echo number_format($totalExternosCabecera, 2);  ?> </button></td>
                                    <td><button type="button" class="btn btn-primary btn-sm py-3 has-icon"><i class="fa fa-money-check-alt fa-sm"></i>Total hoja: $<?php echo number_format($totalGlobalHoja, 2);  ?> </button></td>
                                </tr>

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
                                    <form action="<?php echo base_url() ?>Hoja/guardar_detalle_presupuesto" method="post" class="needs-validation" novalidate>
                                            <input type="hidden" value="<?php echo $presupuesto->idPresupuesto; ?>" name="idHoja">
                                            <input type="hidden" value="<?php echo $presupuesto->fechaPresupuesto; ?>" name="fechaHoja">
                                            <ul class="nav nav-tabs tabs-bordered d-flex nav-justified mb-4" role="tablist">
                                                <li role="presentation"><a href="#tab1" id="control1" aria-controls="tab1" class="active show pivote" role="tab" data-toggle="tab" aria-selected="true">Medicamentos e insumos</a></li>
                                                <li role="presentation"><a href="#tab2" id="control2" aria-controls="tab2" role="tab" data-toggle="tab" class="pivote" aria-selected="false">Servicios externos </a></li>
                                            </ul>

                                            <div class="tab-content">

                                                <div role="tabpanel" class="tab-pane fade in active show" id="tab1">
                                                    <div class="medicamentosContainer">
                                                        <table class="table table-hover thead-primary" id="tblMedicamentos">
                                                            <thead>
                                                                <tr>
                                                                <th class="text-center">Medicamento</th>
                                                                <th class="text-center">Precio</th>
                                                                <th class="text-center">Cantidad</th>
                                                                <th class="text-center">Total</th>
                                                                <th class="text-center">Opción</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                                <?php
                                                                  foreach ($medicamentosHoja as $medicamento) {
                                                                    $id ='"'.$medicamento->idPresupuestoInsumo.'"';
                                                                    /* $idM ='"'.$medicamento->idMedicamento.'"';
                                                                    $stock ='"'.$medicamento->stockMedicamento.'"';
                                                                    $usados ='"'.$medicamento->usadosMedicamento.'"';
                                                                    $cantidad ='"'.$medicamento->cantidadInsumo.'"';
                                                                    $precio ='"'.$medicamento->precioInsumo.'"';
                                                                    $tipo ='"'.$medicamento->tipoMedicamento.'"';
                                                                    $totalMedicamentos += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
                                                                    $transaccion ='"'.$medicamento->idPresupuestoInsumo.'"'; */
                                                                ?>

                                                                <tr>
                                                                    <td class="text-center"><?php echo $medicamento->nombreMedicamento; ?> </td>
                                                                    <td class="text-center precioMedicamento">$ <?php echo $medicamento->precioInsumo; ?> </td>
                                                                    <td class="text-center"><?php echo $medicamento->cantidadInsumo; ?></td>
                                                                    <td class="text-center">
                                                                        $ <?php echo number_format(($medicamento->cantidadInsumo * $medicamento->precioInsumo), 2); ?>
                                                                        <input value="<?php echo ($medicamento->cantidadInsumo * $medicamento->precioInsumo); ?>" type="hidden" class="totalUMedicamento" required>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <?php
                                                                            echo "<a onclick='actualizarMedicamentos($id)' href='#actualizarMedicamentos' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                                            // echo "<a onclick='eliminarMedicamentos($id)' href='#eliminarMedicamentos' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                            echo "<a onclick='eliminarMedicamentos($id)' class='eliminarMed' href='#' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                        ?>
                                                                    </td>


                                                                </tr>

                                                                <?php } ?>

                                                                <tr id="totalMedicamentos">
                                                                    <td colspan="3" class="text-right"><strong>TOTAL INSUMOS Y MEDICAMENTOS</strong></td>
                                                                    <td class="text-center">
                                                                        <!-- <label id="lblTotalMedicamentos">$ <?php //echo $totalMedicamentos; ?></label> -->
                                                                        <label><strong><p id="lblTotalMedicamentos"><?php echo "$ ".number_format($totalMedicamentos, 2); ?></p></strong></label>
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
                                                        <table class="table table-hover thead-primary" id="tblExternos">
                                                            <thead>
                                                                <tr>
                                                                <th class="text-center">Servicio</th>
                                                                <th class="text-center">Precio</th>
                                                                <th class="text-center">Cantidad</th>
                                                                <th class="text-center">Total</th>
                                                                <th class="text-center">Opción</th>
                                                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    foreach ($externosHoja as $externo) {
                                                                        $idPresupuestoExterno ='"'.$externo->idPresupuestoExterno.'"';
                                                                        $nombre ='"'.$externo->nombreExterno.'"';
                                                                        $precio ='"'.$externo->precioExterno.'"';
                                                                        $cantidad ='"'.$externo->cantidadExterno.'"';
                                                                ?>
                                                                    <tr">
                                                                        <td class="text-center"><?php echo $externo->nombreExterno; ?></td>
                                                                        <td class="text-center precioExterno">$ <?php echo $externo->precioExterno; ?></td>
                                                                        <td class="text-center"><?php echo $externo->cantidadExterno; ?></td>
                                                                        <td class="text-center">$ <?php echo number_format(($externo->precioExterno * $externo->cantidadExterno), 2); ?>
                                                                        <input value="<?php echo ($externo->precioExterno * $externo->cantidadExterno); ?>" type="hidden" class="totalUExterno" required>
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <?php
                                                                                echo "<a onclick='actualizarExternos($idPresupuestoExterno, $nombre, $precio, $cantidad)' href='#actualizarExternos' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                                                //echo "<a onclick='eliminarExternos($idPresupuestoExterno)' href='#eliminarExternos' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                                echo "<a onclick='eliminarExternos($idPresupuestoExterno)' class='eliExtern' href='#' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                                            ?>
                                                                        </td>

                                                                    </tr>
                                                                <?php } ?>
                                                                <tr id="totalExternos">
                                                                    <td colspan="3" class="text-right"><strong>TOTAL EXTERNOS</strong></td>
                                                                    <td class="text-center">
                                                                        <!-- <label id="lblTotalExternos">$0.00</label> -->
                                                                        <label><strong><p id="lblTotalExternos"><?php echo "$ ".number_format($totalExternos ,2); ?></p></strong></label>
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

                                            <!-- <div class="text-center" id="botoneraHoja"> -->
                                            <div class="text-center" style="display: none;">
                                                <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar datos</button>
                                                <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
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
    <!-- Modal para agregar datos del Medicamento-->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="medicamentos" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <!-- <th class="text-center" scope="col">Existencia</th> -->
                                                    <th class="text-center" scope="col">Precio</th>
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($medicamentos as $medicamento) {
                                                        $id ='"'.$medicamento->idMedicamento.'"';
                                                        $nombre ='"'.$medicamento->nombreMedicamento.'"';
                                                        $stock ='"'.$medicamento->stockMedicamento.'"';
                                                        $usados = '"'.$medicamento->usadosMedicamento.'"';
                                                        $precioV = '"'.$medicamento->precioVMedicamento.'"';
                                            ?>
                                                <tr class="filaMedicamento">
                                                    <td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento?></td>
                                                    <!-- <td class="text-center" scope="row">
                                                        <?php 
                                                            if($medicamento->stockMedicamento == 0){
                                                                echo '<span class="badge badge-gradient-danger">Sin stock</span>';
                                                            }else{
                                                                if($medicamento->stockMedicamento < 0){
                                                                    echo '<span class="badge badge-gradient-info">Servicio</span>';
                                                                }else{
                                                                    echo $medicamento->stockMedicamento;
                                                                }
                                                                    
                                                            }
                                                        ?>
                                                    </td> -->
                                                    <td class="text-center" scope="row">
                                                        <!-- $ <?php //echo number_format($medicamento->precioVMedicamento, 2)?> -->
                                                            <input type="hidden" value="<?php echo $medicamento->idMedicamento; ?>" class="form-control idM">
                                                            <input type="hidden" value="<?php echo $medicamento->nombreMedicamento; ?>" class="form-control nombreM">
                                                            <input type="hidden" value="<?php echo $medicamento->stockMedicamento; ?>" class="form-control stockM">
                                                            <input type="hidden" value="<?php echo $medicamento->usadosMedicamento; ?>" class="form-control usadosM">
                                                            <input type="text" value="<?php echo $medicamento->precioVMedicamento; ?>" class="form-control precioM">
                                                            <input type="hidden" value="<?php echo $presupuesto->idPresupuesto; ?>" class="form-control idPresupuestoM">
                                                            <input type="hidden" value="<?php echo $presupuesto->fechaPresupuesto; ?>" class="form-control fechaPresupuestoM">
                                                        </td>
                                                    <td>
                                                        <input type="text" value="1" id="test" class="form-control cantidadM">
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                            echo "<a onclick='agregarMedicamento($id, $nombre, $stock, $usados, $precioV)' class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php  } ?>
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

    <!-- Modal para agregar datos del Medicamento-->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="externos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Lista de servicios externos</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
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
                                                    <td class="text-center" scope="row"> <?php echo $externo->nombreExterno; ?></td>
                                                    
                                                    <td>
                                                        <input type="hidden" value="<?php echo $externo->idExterno; ?>" class="form-control idE" />
                                                        <input type="hidden" value="<?php echo $externo->nombreExterno; ?>" class="form-control nombreE" />
                                                        <input type="text" value="0" class="form-control precioE"/>
                                                        <input type="hidden" value="<?php echo $presupuesto->idPresupuesto; ?>" id="test" class="form-control idPresupuestoE">
                                                        <input type="hidden" value="<?php echo $presupuesto->fechaPresupuesto; ?>" id="test" class="form-control fechaPresupuestoE">
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
    <!-- Fin Modal para agregar datos del Medicamento-->

    <!-- Modal para actualizar datos de medicamentos-->
        <div class="modal fade" id="actualizarMedicamentos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white"></i> Datos del Medicamento</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <form class="needs-validation" id="frmMedicamento" method="post" action="<?php echo base_url() ?>Hoja/actualizar_medicamento_presupuesto" novalidate>
                                        
                                    <div class="form-row">
                                            <div class="col-md-12">
                                                <label for="">Cantidad</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="cantidadInsumo" name="cantidadInsumo" placeholder="Ingrese la nueva cantidad" required>
                                                    <input type="hidden" class="form-control" id="idPresupuestoInsumo" name="idPresupuestoInsumo" required>
                                                    <input type="hidden" class="form-control"  name="idHojaReturn" value="<?php echo $presupuesto->idPresupuesto; ?>" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese la nueva cantidad.
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="text-center">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar detalle</button>
                                            <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
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
                    <form action="<?php echo base_url() ?>Hoja/eliminar_medicamento_presupuesto" method="post">
                        <div class="modal-header bg-danger">
                            <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                        </div>

                        <div class="modal-body text-center">
                            <p class="h5">¿Estas seguro de eliminar el medicamento?</p>
                            <input type="hidden" class="form-control" id="idPresupuestoInsumoE" name="idPresupuestoInsumo" required>
                            <input type="hidden" class="form-control"  name="idHojaReturn" value="<?php echo $presupuesto->idPresupuesto; ?>" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
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
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <form class="needs-validation" id="frmExternos" method="post" action="<?php echo base_url() ?>Hoja/actualizar_externo_presupuesto" novalidate>
                                        
                                    <div class="form-row">
                                            <div class="col-md-12">
                                                <label for=""><strong>Cantidad</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="cantidadExterno" name="cantidadExterno" placeholder="Ingrese la nueva cantidad" required>
                                                    
                                                    <div class="invalid-tooltip">
                                                        Ingrese la nueva cantidad de el servicio externo.
                                                    </div>
                                                </div>

                                                <label for=""><strong>Precio</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="precioExterno" name="precioExterno" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese el precio de el servicio externo.
                                                    </div>
                                                </div>

                                            </div>
                                            
                                        </div>
                                        
                                        <input type="hidden" class="form-control" id="idExternoPresupuesto" name="idPresupuestoExterno" required>
                                        <input type="hidden" class="form-control"  name="idHojaReturn" value="<?php echo $presupuesto->idPresupuesto; ?>" required>
                                        <div class="text-center">
                                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar detalle</button>
                                            <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
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
                    <form action="<?php echo base_url() ?>Hoja/eliminar_externo_presupuesto" method="post">
                        <div class="modal-header bg-danger">
                            <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                        </div>

                        <div class="modal-body text-center">
                            <p class="h5">¿Estas seguro de eliminar el servicio externo?</p>
                            <input type="hidden" class="form-control" id="idPresupuestoExternoE" name="idPresupuestoExterno" required>
                            <input type="hidden" class="form-control"  name="idHojaReturn" value="<?php echo $presupuesto->idPresupuesto; ?>" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    <!-- Fin Modal eliminar  datos del medicamento-->

    
<!-- Script JS para agregar un medicamento -->
    <script>
        // Validando el tamaño de las tablas
            $(document).ready(function() {
                var mFilas = $("#tblMedicamentos tr .precioMedicamento").length;
                if(mFilas == 0){
                    $(".medicamentosContainer").hide();
                    $(".medicamentosContainerNull").show();
                    $("#botoneraHoja").hide();
                }else{
                    $(".medicamentosContainer").show();
                    $(".medicamentosContainerNull").hide();
                    $("#botoneraHoja").show();
                }

                var eFilas = $("#tblExternos tr .precioExterno").length;
                if(eFilas == 0){
                    $(".externosContainer").hide();
                    $(".externosContainerNull").show();
                    $("#botoneraHoja").hide();
                }else{
                    $(".externosContainer").show();
                    $(".externosContainerNull").hide();
                    $("#botoneraHoja").show();
                }
            });

        // Agregar medicamento a la lista

            $(document).on('click', '.agregarMedicamento', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                
                var medicamento = {
                    id: $(this).closest('tr').find(".idM").val(),
                    nombre: $(this).closest('tr').find(".nombreM").val(),
                    stock: $(this).closest('tr').find(".stockM").val(),
                    usados: $(this).closest('tr').find(".usadosM").val(),
                    precioV: $(this).closest('tr').find(".precioM").val(),
                    cantidad: $(this).closest('tr').find(".cantidadM").val(),
                    idPresupuesto: $(this).closest('tr').find(".idPresupuestoM").val(),
                    fechaPresupuesto: $(this).closest('tr').find(".fechaPresupuestoM").val(),
                };

                $.ajax({
                url: "../../agregar_medicamento_presupuesto",
                type: "POST",
                data: medicamento,
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (registro.length > 0){
                            console.log(registro);
                        }
                    }
                });
                $(".medicamentosContainer").show();
                $(".medicamentosContainerNull").hide();
                $("#botoneraHoja").show();
                $(".form-control-sm").focus();
            });

            $('.cantidadM').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                
                if(keycode == '13'){
                    // ejecutando procesos
                    $(this).closest('tr').remove();
                
                    var medicamento = {
                        id: $(this).closest('tr').find(".idM").val(),
                        nombre: $(this).closest('tr').find(".nombreM").val(),
                        stock: $(this).closest('tr').find(".stockM").val(),
                        usados: $(this).closest('tr').find(".usadosM").val(),
                        precioV: $(this).closest('tr').find(".precioM").val(),
                        cantidad: $(this).closest('tr').find(".cantidadM").val(),
                        idPresupuesto: $(this).closest('tr').find(".idPresupuestoM").val(),
                        fechaPresupuesto: $(this).closest('tr').find(".fechaPresupuestoM").val(),
                    };

                    $.ajax({
                    url: "../../agregar_medicamento_presupuesto",
                    type: "POST",
                    data: medicamento,
                    success:function(respuesta){
                        var registro = eval(respuesta);
                            if (registro.length > 0){
                                console.log(registro);
                            }
                        }
                    });
                    $(".form-control-sm").focus();
                }

                keycode = null;
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
            $(document).on('change', '.cantidadMedicamento', function (event) {
                event.preventDefault();
                var cantidad = $(this).val(); // Cantidad de cada caja de texto
                var precio = $(this).closest('tr').find(".precioMedicamento").val(); // Precio de cada producto

                var totalMedicamentos = 0 ; // Total de la factura
                var totalUMedicamento = cantidad * precio; // Total por cada producto
                
                
                $(this).closest('tr').find(".totalTemp").html(totalUMedicamento.toFixed(2));
                $(this).closest('tr').find(".totalUMedicamento").val(totalUMedicamento.toFixed(2));
                
                // Sumando todos los input con totales unitarios
                $('.totalUMedicamento').each(function(){
                    totalMedicamentos += parseFloat($(this).val());
                });
                
                $("#lblTotalMedicamentos").html("$ "+totalMedicamentos.toFixed(2)); // Asignando el valor a lblTotalMedicamentos
                //$("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
            });

        // Quitar medicamento de la lista
            $(document).on('click', '.quitarMedicamento', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                var totalMedicamentos = 0 ; // Total de la factura
                // Sacando el total inicial
                var totalMedicamentos = 0 ; // Total de la factura
                // Sumando todos los input con totales unitarios
                $('.totalUMedicamento').each(function(){
                    totalMedicamentos += parseFloat($(this).val());
                });
                $("#lblTotalMedicamentos").html("$ "+totalMedicamentos.toFixed(2)); // Asignando el valor a lblTotalMedicamentos
                $("#txtTotalMedicamentos").val(totalMedicamentos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio


                
                var rFilas = $("#tblMedicamentos tr .precioMedicamento").length;
                var eFilas = $("#tblExternos tr .precioExterno").length;
                if(rFilas == 0){
                    $(".medicamentosContainer").hide();
                    $(".medicamentosContainerNull").show();
                }
                if(rFilas == 0 && eFilas == 0){
                    $("#botoneraHoja").hide();
                }

            });
        
        // Ocultar medicamento por medicamentos agregados
            $(document).on('click', '.ocultarAgregar', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
            });
        
        // Funcion para controlar estado de las pestañas del tab
            function cambiar(a){
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
            function actualizarMedicamentos(id){
                $("#idPresupuestoInsumo").val(id);
            }

            function eliminarMedicamentos(id){
                var datos = {
                    idMedicamento : id
                }

                $.ajax({
                    url: "../../eliminar_medicamento_presupuesto",
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

            $(document).on('click', '.eliminarMed', function(event) {
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
            
            /* $(document).on('click', '.agregarExterno', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                id = $(this).closest('tr').find(".idE").val();
                nombre = $(this).closest('tr').find(".nombreE").val();
                precio = $(this).closest('tr').find(".precioE").val();

                $(".externosContainer").show();
                $(".externosContainerNull").hide();
                $("#botoneraHoja").show(); 
                var totalTempExternos = precio * 1;
                var html = '';
                html += '<tr>';
                html += '    <td class="text-center">'+nombre+'<input type="hidden" id="idExterno" name="idExterno[]" value="'+id+'" min="0" class="form-control idExterno numeros" required></td>';
                html += '    <td class="text-center"><input value='+precio+' type="text" id="precioExterno" name="precioExterno[]" min="0" class="form-control precioExterno numeros" required></td>';
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
                $(".form-control-sm").focus();
            }); */

            $(document).on('click', '.agregarExterno', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                var externo = {
                    id: $(this).closest('tr').find(".idE").val(),
                    nombre: $(this).closest('tr').find(".nombreE").val(),
                    precio: $(this).closest('tr').find(".precioE").val(),
                    idPresupuesto: $(this).closest('tr').find(".idPresupuestoE").val(),
                    fecha: $(this).closest('tr').find(".fechaPresupuestoE").val(),
                };

                $.ajax({
                    url: "../../agregar_externo_presupuesto",
                    type: "POST",
                    data: externo,
                    success:function(respuesta){
                        var registro = eval(respuesta);
                            if (registro.length > 0){
                                console.log(registro);
                            }
                        }
                });

            });
        
            /* $('.precioE').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    // ejecutando procesos
                            $(this).closest('tr').remove();
                            id = $(this).closest('tr').find(".idE").val();
                            nombre = $(this).closest('tr').find(".nombreE").val();
                            precio = $(this).closest('tr').find(".precioE").val();

                            $(".externosContainer").show();
                            $(".externosContainerNull").hide();
                            $("#botoneraHoja").show(); 
                            var totalTempExternos = precio * 1;
                            var html = '';
                            html += '<tr>';
                            html += '    <td class="text-center">'+nombre+'<input type="hidden" id="idExterno" name="idExterno[]" value="'+id+'" min="0" class="form-control idExterno numeros" required></td>';
                            html += '    <td class="text-center"><input type="text" value='+precio+' id="precioExterno" name="precioExterno[]" min="0" class="form-control precioExterno numeros" required></td>';
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
                            $(".form-control-sm").focus();
                }
            }); */

            $('.precioE').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    // ejecutando procesos
                        $(this).closest('tr').remove();
                        var externo = {
                            id: $(this).closest('tr').find(".idE").val(),
                            nombre: $(this).closest('tr').find(".nombreE").val(),
                            precio: $(this).closest('tr').find(".precioE").val(),
                            idPresupuesto: $(this).closest('tr').find(".idPresupuestoE").val(),
                            fecha: $(this).closest('tr').find(".fechaPresupuestoE").val(),
                        };

                        $.ajax({
                            url: "../../agregar_externo_presupuesto",
                            type: "POST",
                            data: externo,
                            success:function(respuesta){
                                var registro = eval(respuesta);
                                    if (registro.length > 0){
                                        console.log(registro);
                                    }
                                }
                        });
                        $(".form-control-sm").focus();
                }
            });
        
        // Calcular al cambiar la cantidad del externo
                $(document).on('change', '.cantidadExterno', function (event) {
                    event.preventDefault();
                    var cantidad = $(this).val(); // Cantidad de cada caja de texto
                    var precio = $(this).closest('tr').find(".precioExterno").val(); // Precio de cada producto

                    var totalExternos = 0 ; // Total de la factura
                    var totalUExterno = cantidad * precio; // Total por cada producto
                    
                    
                    $(this).closest('tr').find(".totalTempExternos").html(totalUExterno.toFixed(2));
                    $(this).closest('tr').find(".totalUExterno").val(totalUExterno.toFixed(2));
                    
                    // Sumando todos los input con totales unitarios
                    $('.totalUExterno').each(function(){
                        totalExternos += parseFloat($(this).val());
                    });
                    
                    $("#lblTotalExternos").html("$ "+totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
                    //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
                });
        
        // Calcular al cambiar el precio del externo
            $(document).on('change', '.precioExterno', function (event) {
                event.preventDefault();
                var cantidad = $(this).closest('tr').find(".cantidadExterno").val(); // Cantidad de medicamento
                var precio = $(this).val(); // Precio del externo

                var totalExternos = 0 ; // Total de la factura
                var totalUExterno = cantidad * precio; // Total por cada producto
                
                
                $(this).closest('tr').find(".totalTempExternos").html(totalUExterno.toFixed(2));
                $(this).closest('tr').find(".totalUExterno").val(totalUExterno.toFixed(2));
                
                // Sumando todos los input con totales unitarios
                $('.totalUExterno').each(function(){
                    totalExternos += parseFloat($(this).val());
                });
                
                $("#lblTotalExternos").html("$ "+totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
                //$("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
            });
        
        // Quitar externos de la lista
            $(document).on('click', '.quitarExterno', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                var totalExternos = 0 ; // Total de la factura
                // Sacando el total inicial
                var totalExternos = 0 ; // Total de la factura
                // Sumando todos los input con totales unitarios
                $('.totalUExterno').each(function(){
                    totalExternos += parseFloat($(this).val());
                });
                $("#lblTotalExternos").html("$ "+totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
                $("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
                
                var eFilas = $("#tblExternos tr .precioExterno").length;
                var rFilas = $("#tblMedicamentos tr .precioMedicamento").length;
                if(eFilas == 0){
                    $(".externosContainer").hide();
                    $(".externosContainerNull").show();
                }
                if(rFilas == 0 && eFilas == 0){
                    $("#botoneraHoja").hide();
                }

            });

        // Actualizar y eliminar externos
            function actualizarExternos(idPresupuestoExterno, nombre, precio, cantidad){
                    $("#cantidadExterno").val(cantidad);
                    $("#precioExterno").val(precio);
                    $("#idExternoPresupuesto").val(idPresupuestoExterno);
                    console.log(idPresupuestoExterno, nombre, precio, cantidad);
            }

            function eliminarExternos(idHojaExterno){
                var datos = {
                    idExterno : idHojaExterno
                }

                $.ajax({
                    url: "../../eliminar_externos_presupuesto",
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

            $(document).on('click', '.eliExtern', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                var totalExternos = 0 ; // Total de la factura
                // Sacando el total inicial
                var totalExternos = 0 ; // Total de la factura
                // Sumando todos los input con totales unitarios
                $('.totalUExterno').each(function(){
                    totalExternos += parseFloat($(this).val());
                });
                $("#lblTotalExternos").html("$ "+totalExternos.toFixed(2)); // Asignando el valor a lblTotalExternos
                $("#txtTotalExternos").val(totalExternos.toFixed(2)); // Asignando el valor a txtTotalLaboratorio
                
                var eFilas = $("#tblExternos tr .precioExterno").length;
                var rFilas = $("#tblMedicamentos tr .precioMedicamento").length;
                if(eFilas == 0){
                    $(".externosContainer").hide();
                    $(".externosContainerNull").show();
                }
                if(rFilas == 0 && eFilas == 0){
                    $("#botoneraHoja").hide();
                }

            });
        
        $(document).on('click', '.close', function(event) {
            event.preventDefault();
            location.reload();
        });
    </script>

