<?php if ($this->session->flashdata("exito")) : ?>
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.remove();
            toastr.options.positionClass = "toast-top-center";
            toastr.success('<?php echo $this->session->flashdata("exito") ?>', 'Aviso!');
        });
    </script>
<?php endif; ?>

<?php if ($this->session->flashdata("error")) : ?>
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.remove();
            toastr.options.positionClass = "toast-top-center";
            toastr.error('<?php echo $this->session->flashdata("error"); ?>', 'Aviso!');
        });
    </script>
<?php endif; ?>

<?php
    $totalCompraGlobal = 0;
    $totalIva = 0;
    foreach ($detalleCL as $fila) {
        $totalCompraGlobal += ($fila->cantidadDetalleCL * $fila->precioDetalleCL);
    }

    
    /* $totalIva = (($totalCompraGlobal-$facturas->descuentoCompra) *0.13);
    $idFactura = '"' . $facturas->idFactura . '"';
    $nDocumento = '"' . $facturas->numeroFactura . '"';
    $nProveedor = '"' . $facturas->idProveedor . '"';
    $fFactura = '"' . $facturas->fechaFactura . '"';
    $pFactura = '"' . $facturas->plazoFactura . '"';
    $dFactura = '"' . $facturas->descripcionFactura . '"'; */
?>

<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pl-0">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-users "></i> Botíquin</a></li>
                        <li class="breadcrumb-item"><a href="#">Detalle compra</a></li>
                    </ol>
                </nav>
                <?php
                $plazo = "";
                $estado = "";
                $clase = "";
                if ($compraCabecera->plazoCompraLab == "0") {
                    $plazo = "Contado";
                } else {
                    $plazo = "Crédito " . $compraCabecera->plazoCompraLab . " dias";
                }

                if ($compraCabecera->estadoCompraLab == 1) {
                    $estado = "Pendiente";
                    $clase = "pendiente p-2 ";
                } else {
                    $estado = "Saldada";
                    $clase = "saldada p-2";
                }

                ?>
                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Detalle de la compra</h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?php echo base_url() ?>InsumosLab/lista_compras" class="btn btn-outline-success btn-sm"><i class="fa fa-arrow-left"></i> Volver </a>
                                <!-- <?php
                                if (sizeof($detalleCL) > 0) {
                                    $id = '"' . $compraCabecera->idCompraLab . '"';
                                    if ($estado == "Pendiente") {
                                        $proveedor = $compraCabecera->empresaProveedor;
                                        echo "<a onclick='saldar($id)' href='#saldarCompra' data-toggle='modal' class='btn btn-success btn-sm'><i class='fa fa-check'></i> Saldar </a>";
                                    }
                                    if ($estado != "Pendiente") {
                                        $proveedor = $compraCabecera->empresaProveedor;
                                        echo "<a href='" . base_url() . "Botiquin/detalle_compra_pdf/" . $compraCabecera->idCompraLab . "' class='btn btn-danger btn-sm' target='_blank'><i class='fa fa-file-pdf'></i> Ver en PDF </a>";
                                    }
                                }
                                ?> -->

                                <?php
                                if ($compraCabecera->estadoCompraLab == 1) {
                                    echo '<a href="#agregarInsumosLab" data-toggle="modal" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Medicamentos </a> ';
                                    echo '<a href="#agregarExtras" data-toggle="modal" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Extra </a>';
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="row alert-primary bordeContenedor pt-3">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tipo</strong></td>
                                    <td><?php echo $compraCabecera->tipoCompraLab; ?></td>
                                    <td><strong>Número de documento</strong></td>
                                    <td><?php echo $compraCabecera->numeroCompraLab; ?></td>
                                    <td><strong>Fecha</strong></td>
                                    <td><?php echo $compraCabecera->fechaCompraLab; ?></td>
                                </tr>

                                <tr>
                                    <td><strong>Proveedor</strong></td>
                                    <td><?php echo $compraCabecera->empresaProveedor; ?></td>
                                    <td><strong>Plazo</strong></td>
                                    <td><?php echo $plazo; ?></td>
                                    <td><strong>Monto total</strong></td>
                                    <td>$ <?php echo number_format($totalCompraGlobal, 2); ?></td>
                                </tr>

                                <tr>
                                    <td><strong>Descripción</strong></td>
                                    <td><?php echo $compraCabecera->descripcionCompraLab; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2" class="text-center">
                                        <?php
                                        if($compraCabecera->estadoCompraLab == 1){
                                            //echo "<a onclick='editarFactura($idFactura, $nDocumento, $nProveedor, $fFactura, $dFactura, $pFactura)' href='#editarFactura' data-toggle='modal' title='Editar hoja'><i class='fa fa-pencil-alt text-primary'></i></a>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                            <div class="col-md-12">
                                <?php
                                    if(sizeof($detalleCL) > 0){
                                ?>
                                <!--Tabla de medicamentos seleccionados-->
                                <div class="table-responsive mt-3">
                                    <table id="tabla-medicamentos-seleccionados" class="table table-striped thead-primary ">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">Insumo</th>
                                                <th class="text-center" scope="col">Medida</th>
                                                <th class="text-center" scope="col">Precio unitario</th>
                                                <th class="text-center" scope="col">Cantidad</th>
                                                <th class="text-center" scope="col">Total</th>
                                                <th class="text-center" scope="col">Vencimiento</th>
                                                <th class="text-center" scope="col">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalCompraIL = 0;
                                                foreach ($detalleCL as $detalle) {
                                                    $totalCompraIL += ($detalle->cantidadDetalleCL * $detalle->precioDetalleCL) - $detalle->descuentoDetalleCL;
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $detalle->nombreInsumoLab; ?></td>
                                                <td class="text-center"><span class="badge badge-success"><?php echo $detalle->medidaDetalleCL; ?></span></td>
                                                <td class="text-center">$ <?php echo $detalle->precioDetalleCL; ?></td>
                                                <td class="text-center"><?php echo $detalle->cantidadDetalleCL; ?></td>
                                                <td class="text-center">$ <?php echo number_format((($detalle->cantidadDetalleCL * $detalle->precioDetalleCL) - $detalle->descuentoDetalleCL), 2) ?></td>
                                                <td class="text-center"><?php echo $detalle->vencimientoDetalleCL; ?></td>
                                                <td class="text-center">
                                                    <input type="hidden" value="<?php echo $detalle->idDetalleCL; ?>" class="filaILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->idInsumoLab; ?>" class="idILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->cantidadDetalleCL; ?>" class="cantidadILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->precioDetalleCL; ?>" class="precioILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->descuentoDetalleCL; ?>" class="descuentoILEdit">
                                                    <?php
                                                        echo "<a href='#actualizarDetalleCL' data-toggle='modal'><i class='fas fa-edit ms-text-success editarDetalleCL'></i></a>";
                                                        echo "<a href='#eliminarCL' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger eliminarDetalleCL'></i></a>";
                                                    ?>
                                                </td>
                                            </tr>

                                            <?php
                                                }
                                            ?>

                                            <tr>
                                                <td class="text-right" colspan="6"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Subtotal</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($totalCompraIL, 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="6"><strong>(-) Descuento</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($compraCabecera->descuentoCompraLab, 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="6"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sumas</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format(($totalCompraIL - $compraCabecera->descuentoCompraLab), 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="6"><strong>IVA</strong></td>
                                                <td class="text-left">$ <strong><?php
                                                    $valor = ($totalCompraIL - $compraCabecera->descuentoCompraLab) * 0.13;
                                                    echo number_format($valor, 2); 
                                                ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="6"><strong>(+) IVA Percibido</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($compraCabecera->ivaPercibido, 2); ?></strong></td>
                                            </tr>

                                            

                                            <tr>
                                                <td class="text-right" colspan="6"><strong>Total</strong></td>
                                                <td class="text-left">$
                                                    <strong><?php
                                                        $param = $totalCompraIL -$compraCabecera->descuentoCompraLab;
                                                        $valor = (($param + ($param * 0.13) ) + $compraCabecera->ivaPercibido);
                                                        echo number_format($valor, 2); 
                                                    ?></strong>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!--Fin tabla de medicamentos-->
                                <?php 
                                    }else{
                                        echo "<div class='alert-danger mt-5 p-4 text-center bold'> No hay datos que mostrar... </div>";
                                    }
                                ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End Body Content Wrapper -->

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="agregarInsumosLab" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Lista de Insumos/Reactivos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="table-responsive mt-3">

                                    <?php
                                    if (sizeof($listaIL) > 0) {
                                    ?>

                                        <table id="tabla-listaMedicamentos" class="table table-striped thead-primary w-100 tablaPlus">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Medida</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <th class="text-center" scope="col"> Precio </th>
                                                    <!-- <th class="text-center" scope="col"> Descuento </th> -->
                                                    <th class="text-center" scope="col">Vencimiento</th>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($listaIL as $il) {
                                                ?>
                                                        <tr>
                                                            <td class="text-center" scope="row"><span class="badge badge-success"><?php echo $il->medidaInsumoLab ?></span></td>
                                                            <td class="text-center" scope="row"><?php echo $il->nombreInsumoLab ?></td>
                                                            <td class="text-center precio">
                                                                <input type="text" class="cantidadIL form-control menosHeight" value="1">
                                                                <input type="hidden" value="<?php echo $il->idInsumoLab; ?>" id="test" class="form-control idIL">
                                                                <input type="hidden" value="<?php echo $il->codigoInsumoLab; ?>" id="test" class="form-control codigoIL">
                                                                <input type="hidden" value="<?php echo $il->nombreInsumoLab; ?>" id="test" class="form-control nombreIL">
                                                                <input type="hidden" value="<?php echo $il->precioInsumoLab; ?>" id="test" class="form-control">
                                                                <input type="hidden" value="<?php echo $il->stockInsumoLab; ?>" id="test" class="form-control stockIL">
                                                                <input type="hidden" value="<?php echo $il->medidaInsumoLab; ?>" class="form-control menosHeight medidaIL"  size="5"> 
                                                                <input type="hidden" value="<?php echo $compra; ?>" id="test" class="form-control facturaIL">
                                                            </td>
                                                            <td class="text-center" scope="row"><input type="text" value="<?php echo $il->precioInsumoLab; ?>" style="width: 100px;" class="form-control menosHeight precioIL">  </td>
                                                            <!-- <td class="text-center" scope="row"><input type="text" value="0.00" class="form-control menosHeight descuentoIL"  size="5">  </td> -->
                                                            <td class="text-center" scope="row"><input type="date" class="form-control menosHeight vencimientoIL"  size="5">  </td>
                                                            <td class="text-center">
                                                                <?php
                                                                    // echo "<a href='#' class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
                                                                    echo "<a href='#' class='ocultarIL agregarIL' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary'></i></a>";
                                                                ?>

                                                            </td>
                                                        </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                    <?php
                                    } else {
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

<!-- Modal para saldar factura-->
    <div class="modal fade" id="saldarCompra" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-danger">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de cambiar el estado de la factura a "Saldada"?</p>
                </div>

                <form action="<?php echo base_url() ?>Botiquin/saldar_compra/" method="post">
                    <input type="hidden" id="idSaldar" name="idFactura" />
                    <div class="text-center">
                        <button class="btn btn-danger shadow-none"><i class="fa fa-check"></i> Cambiar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Fin para saldar factura-->


<!-- Modal para actualizar datos de medicamentos-->
    <div class="modal fade" id="actualizarDetalleCL" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h5 class="modal-title text-white"><i class="fa fa-file"></i> Datos del Medicamento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" id="frmMedicamento" method="post" action="<?php echo base_url() ?>InsumosLab/actualizar_detalle_cl" novalidate>

                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for="">Cantidad</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="cantidadInsumo" name="cantidadInsumo" placeholder="Ingrese la nueva cantidad" required>
                                                <input type="hidden" class="form-control" id="filaILEdit2" name="filaILEdit" required>
                                                <input type="hidden" class="form-control" id="idILEdit2" name="idILEdit" required>
                                                <input type="hidden" class="form-control" id="cantidadILEdit2" name="cantidadILEdit" required>
                                                <input type="hidden" class="form-control" name="idCompraReturn" value="<?php echo $compra; ?>" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la nueva cantidad.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="">Precio</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="precioILEdit2" name="precioILEdit"  required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el precio de compra.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="">Descuento</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="descuentoILEdit2" name="descuentoILEdit"  required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el descuento del medicamento.
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
    <div class="modal fade" id="eliminarCL" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                <form action="<?php echo base_url() ?>InsumosLab/eliminar_medicamento_cl" method="post">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Estas seguro de eliminar el registro?</p>
                        <input type="hidden" class="form-control" id="idILE" name="idInsumo" required>
                        <input type="hidden" class="form-control" id="idDetalleE" name="idFila" required>
                        <input type="hidden" class="form-control" id="cantidadILE" name="cantidadInsumo" required>
                        <input type="hidden" class="form-control" name="idCompraReturn" value="<?php echo $compra; ?>" required>
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


<!-- Modal para actualizar datos de medicamentos-->
    <div class="modal fade" id="editarFactura" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos de la factura</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>
                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <!-- Inicio -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="needs-validation" method="post" action="<?php echo base_url(); ?>Botiquin/actualizar_datos_factura" novalidate>

                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <label for=""><strong>Número de documento</strong></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control numeros" id="numeroFactura" name="numeroFactura" placeholder="Número del medicamento" required>
                                                        <div class="invalid-tooltip">
                                                            Ingrese un número de documento.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">

                                                <div class="col-md-12">
                                                    <label for=""><strong>Proveedor</strong></label>
                                                    <div class="input-group">
                                                        <select class="form-control" id="idProveedor" name="idProveedor" required>
                                                            <option value="">.:: Seleccionar ::.</option>

                                                            <?php
                                                            foreach ($proveedores as $proveedor) {
                                                            ?>

                                                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->empresaProveedor; ?></option>

                                                            <?php } ?>
                                                        </select>
                                                        <div class="invalid-tooltip">
                                                            Seleccione un proveedor.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for=""><strong>Fecha</strong></label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" value="<?php echo date("Y-m-d") ?>" id="fechaFactura" name="fechaFactura" placeholder="Registro del medicamento" required>
                                                        <div class="invalid-tooltip">
                                                            Ingrese un número de documento.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for=""><strong>Plazo</strong></label>
                                                    <div class="input-group">
                                                        <select name="plazoFactura" id="plazoFactura" class="form-control" required>
                                                            <option value="">.:: Seleccionar ::.</option>
                                                            <option value="0">Contado</option>
                                                            <option value="30">30 dias</option>
                                                            <option value="60">60 dias</option>
                                                            <option value="90">90 dias</option>
                                                        </select>
                                                        <div class="invalid-tooltip">
                                                            Seleccione el plazo para esta factura.
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <label for=""><strong>Descripción</strong></label>
                                                    <div class="input-group">
                                                        <textarea class="form-control disableSelect" id="descripcionFactura" name="descripcionFactura" placeholder="Descripción del proceso" required></textarea>
                                                        <div class="invalid-tooltip">
                                                            Seleccione un tipo de proceso.
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="idFacturaE" name="idFactura">
                                            </div>

                                            <div class="text-center" id="">
                                                <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-sync"></i> Actualizar</button>
                                                <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Fin -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal actualizar datos de medicamentos -->

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="agregarExtras" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Detalles extras</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form action="<?php echo base_url(); ?>InsumosLab/guardar_extras" method="POST">
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="">IVA Percibido</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="0" id="ivaPercibido" name="ivaPercibido" placeholder="Ingrese la cantidad de IVA percibido" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la cantidad de IVA percibido.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="">Descuento</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="0" id="descuentoCompra" name="descuentoCompra" placeholder="Ingrese la cantidad de descuento" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la cantidad de descuento.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div>
                                            <input type="hidden" value="<?php echo $compra; ?>" name="idCompraReturn">
                                            <button class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal para agregar datos del Medicamento-->


<script>
    function saldar(id) {
        document.getElementById("idSaldar").value = id;
    }
</script>

<script>
    $(document).ready(function() {
        //$("#tabla-medicamentos-seleccionados").hide();
        $("#botonera").hide();
    });
    
    $(document).on('click', '.agregarIL', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        var datos = {
            id: $(this).closest('tr').find(".idIL").val(),
            codigo: $(this).closest('tr').find(".codigoIL").val(),
            nombre: $(this).closest('tr').find(".nombreIL").val(),
            precio: $(this).closest('tr').find(".precioIL").val(),
            stock: $(this).closest('tr').find(".stockIL").val(),
            cantidad: $(this).closest('tr').find(".cantidadIL").val(),
            medida: $(this).closest('tr').find(".medidaIL").val(),
            factura: $(this).closest('tr').find(".facturaIL").val(),
            descuento: 0,
            vencimiento: $(this).closest('tr').find(".vencimientoIL").val(),

        }

        $.ajax({
            url: "../../guardar_insumos_Laboratorio",
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
                        toastr.success('Registro insertado', 'Aviso!');
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
                        toastr.error('Error al agregar el registro...', 'Aviso!');
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
                    toastr.error('Error al agregar el registro...', 'Aviso!');

                }
            }
        });

    

    });

    $(document).on('click', '.editarDetalleCL', function() {
        var filaILEdit = $(this).closest('tr').find(".filaILEdit").val();
        var idILEdit = $(this).closest('tr').find(".idILEdit").val();
        var cantidadILEdit = $(this).closest('tr').find(".cantidadILEdit").val();
        var precioILEdit  = $(this).closest('tr').find(".precioILEdit").val();
        var descuentoILEdit  = $(this).closest('tr').find(".descuentoILEdit").val();

        $("#filaILEdit2").val(filaILEdit);
        $("#idILEdit2").val(idILEdit);
        $("#cantidadILEdit2").val(cantidadILEdit);
        $("#cantidadInsumo").val(cantidadILEdit);
        $("#precioILEdit2").val(precioILEdit);
        $("#descuentoILEdit2").val(descuentoILEdit);

    });

    $(document).on('click', '.eliminarDetalleCL', function() {

        var idIL = $(this).closest('tr').find(".idILEdit").val();
        var idDetalleE = $(this).closest('tr').find(".filaILEdit").val();
        var cantidadIL = $(this).closest('tr').find(".cantidadILEdit").val();

        $("#idILE").val(idIL);
        $("#idDetalleE").val(idDetalleE);
        $("#cantidadILE").val(cantidadIL);


    });

    $(document).on('click', '.close', function(event) {
        event.preventDefault();
        location.reload();
    });

    /* $('.cantidadM').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            // ejecutando procesos
                event.preventDefault();
                $(this).closest('tr').remove();
                id = $(this).closest('tr').find(".idM").val();
                codigo = $(this).closest('tr').find(".codigoM").val();
                nombre = $(this).closest('tr').find(".nombreM").val();
                precioC = $(this).closest('tr').find(".precioCM").val();
                stock = $(this).closest('tr').find(".stockM").val();
                cantidad = $(this).closest('tr').find(".cantidadM").val();

                var totalUnitario = (precioC * cantidad).toFixed(2);
                var iva = (precioC * cantidad) * 0.13;
                var html = "";
                html += '<tr>';
                //html += '<td class="text-center" scope="row">' + codigo + '</td>';
                html += '<td class="text-center">' + nombre + '<input type="hidden" class="form-control"value="' + id + '" name="idMedicamentos[]" required></td>';
                html += '<td class="text-center"><input type="text" class="precioM" value="' + precioC + '" name="preciosMedicamentos[]" required></td>';

                html += '<td class="text-center">';
                html += '<div class="input-group">';
                html += '<input type="number" class="cantidadMedicamento" min="0" value="'+cantidad+'" name="cantidadMedicamentos[]" required>';
                html += '<div class="invalid-tooltip">';
                html += 'Ingresa una cantidad';
                html += '</div>';
                html += '</div>';
                html += '</td>';

                html += '<td class="text-center">';
                html += '<div class="input-group">';
                html += '<label class="totalUL"><strong>$ ' + totalUnitario + '</strong></label><input type="hidden" class="form-control" value="' + stock + '" name="stockMedicamentos[]" required>';
                html += '<input type="hidden" name="totalUnitario[]" class="totalM" min="0" value="' + totalUnitario + '" required>';
                html += '<div class="invalid-tooltip">';
                html += 'Ingresa el lote del medicamento';
                html += '</div>';
                html += '</div>';
                html += '</td>';

                html += '<td class="text-center">';
                html += '<div class="input-group">';
                html += '<label class="totalUIVA"><strong>$ ' + iva.toFixed(2) + '</strong></label>';
                html += '<input type="hidden" class="ivaMedicamentos" name="ivaMedicamentos[]"  value="' + iva + '" required>';
                html += '<div class="invalid-tooltip">';
                html += 'Ingresa una fecha de venimiento';
                html += '</div>';
                html += '</div>';
                html += '</td>';

                html += '<td class="text-center">';
                html += '<div class="input-group">';
                html += '<input type="date" name="vencimientoMedicamentos[]" placeholder="Fecha de vencimiento">';
                html += '<div class="invalid-tooltip">';
                html += 'Ingresa una fecha de vencimiento';
                html += '</div>';
                html += '</div>';
                html += '</td>';

                html += '<td class="text-center">';
                html += '<div class="input-group">';
                html += '<input type="text" name="loteMedicamentos[]" placeholder="Lote del medicamento">';
                html += '<div class="invalid-tooltip">';
                html += 'Ingresa el lote del medicamento';
                html += '</div>';
                html += '</div>';
                html += '</td>';

                html += '<td class="text-center">';
                html += '<a class="quitarMedicamento" title="Quitar de la lista"><i class="fa fa-times ms-text-danger"></i></a>';
                html += '</td>';

                html += '</tr>';

                $("#detalleFactura").before(html);


                var totalCompra = 0; // Total de la factura
                var totalIva = 0; // Total iva
                // Sumando todos los input con totales unitarios
                $('.totalM').each(function() {
                    totalCompra += parseFloat($(this).val());
                });

                // Sumando todos los input con iva
                $('.ivaMedicamentos').each(function() {
                    totalIva += parseFloat($(this).val());
                });

                $("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
                $("#totalCompraL").html("$ " + (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra

                $("#tabla-medicamentos-seleccionados").show();
                $("#botonera").show();
                $(".form-control-sm").focus();
        }
    });

    $(".ocultarAgregar").click(function() {
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.cantidadMedicamento', function(event) {
        event.preventDefault();
        var cantidad = $(this).val(); // Cantidad de cada caja de texto
        var precio = $(this).closest('tr').find(".precioM").val(); // Precio de cada producto
        var totalCompra = 0; // Total de la factura
        var totalIva = 0; // Total iva

        var totalUnitario = cantidad * precio; // Total por cada producto
        var iva = (cantidad * precio) * 0.13

        $(this).closest('tr').find(".totalM").val(totalUnitario.toFixed(2));
        $(this).closest('tr').find(".totalUL").html("$ " + totalUnitario.toFixed(2));

        // Sumando todos los input con totales unitarios
        $('.totalM').each(function() {
            totalCompra += parseFloat($(this).val());
        });



        $(this).closest('tr').find(".ivaMedicamentos").val(iva);
        $(this).closest('tr').find(".totalUIVA").html("$ " + iva.toFixed(2));


        $('.ivaMedicamentos').each(function() {
            totalIva += parseFloat($(this).val());
        });

        $("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra 
        $("#totalCompraL").html("$ " + (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra


    });

    $(document).on('change', '.precioM', function(event) {
        event.preventDefault();
        var precio = $(this).val(); // Cantidad de cada caja de texto
        var cantidad = $(this).closest('tr').find(".cantidadMedicamento").val(); // Precio de cada producto
        var totalCompra = 0; // Total de la factura
        var totalIva = 0; // Total de la factura

        var totalUnitario = cantidad * precio; // Total por cada producto
        var iva = (cantidad * precio) * 0.13; // Total por cada producto

        $(this).closest('tr').find(".totalM").val(totalUnitario.toFixed(2));
        $(this).closest('tr').find(".totalUL").html("$ " + totalUnitario.toFixed(2));
        $(this).closest('tr').find(".totalUIVA").html("$ " + iva.toFixed(2));

        // Sumando todos los input con totales unitarios
        $('.totalM').each(function() {
            totalCompra += parseFloat($(this).val());
        });

        $(this).closest('tr').find(".ivaMedicamentos").val(iva);

        $('.ivaMedicamentos').each(function() {
            totalIva += parseFloat($(this).val());
        });

        $("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
        $("#totalCompraL").html("$ " + (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra


        console.log(totalCompra);

    });

    $(document).on('click', '.quitarMedicamento', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        var totalCompra = 0; // Total de la factura
        var totalIva = 0; // Total de la factura

        // Sumando todos los input con totales unitarios
        $('.totalM').each(function() {
            totalCompra += parseFloat($(this).val());
        });

        $('.ivaMedicamentos').each(function() {
            totalIva += parseFloat($(this).val());
        });

        $("#totalCompra").val((totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra
        $("#totalCompraL").html("$ " + (totalCompra + totalIva).toFixed(2)); // Asignando el valor al total de la compra


        var eFilas = $("#tabla-medicamentos-seleccionados tr").length;
        if (eFilas == 2) {
            $("#botonera").hide();
            $("#tabla-medicamentos-seleccionados").hide();
        }

    });

     */
</script>

<!-- Tamaños de tablas -->
    <script>
        function actualizarMedicamentos(idMedicamento, idFactura, stock, cantidad, precio, transaccion) {
            $("#idMedicamento").val(idMedicamento);
            $("#idMedicamentoFactura").val(idFactura);
            $("#stock").val(stock);
            $("#cantidadMedicamentoFactura").val(cantidad);
            $("#transaccionMedicamento").val(transaccion);
        }

        function eliminarMedicamentos(idMedicamento, idFactura, stock, cantidad, precio, transaccion) {
            $("#idMedicamentoE").val(idMedicamento);
            $("#idMedicamentoFacturaE").val(idFactura);
            $("#stockE").val(stock);
            $("#cantidadMedicamentoFacturaE").val(cantidad);
            $("#precioMedicamentoE").val(precio);
            $("#transaccionMedicamentoE").val(transaccion);
        }

        function editarFactura(idFactura, nDocumento, nProveedor, fFactura, dFactura, pFactura) {
            //console.log(idFactura, nDocumento, nProveedor, fFactura, dFactura, pFactura);
            document.getElementById("idFacturaE").value = idFactura
            document.getElementById("numeroFactura").value = nDocumento
            document.getElementById("idProveedor").value =  nProveedor
            document.getElementById("fechaFactura").value = fFactura
            document.getElementById("plazoFactura").value = pFactura
            document.getElementById("descripcionFactura").value = dFactura
        }
    </script>
<!-- Fin tamaños de tablas -->
