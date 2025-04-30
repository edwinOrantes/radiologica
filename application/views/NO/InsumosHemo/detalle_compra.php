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
        $totalCompraGlobal += ($fila->cantidad * $fila->precio);
    } 
?>

<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pl-0">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-users "></i> Hemodiálisis </a></li>
                        <li class="breadcrumb-item"><a href="#">Detalle compra</a></li>
                    </ol>
                </nav>
                <?php
                $plazo = "";
                $estado = "";
                $clase = "";
                if ($compraCabecera->plazoCompraHemo == "0") {
                    $plazo = "Contado";
                } else {
                    $plazo = "Crédito " . $compraCabecera->plazoCompraHemo . " dias";
                }

                if ($compraCabecera->estadoCompraHemo == 1) {
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
                                <a href="<?php echo base_url() ?>InsumosHemo/lista_compras" class="btn btn-outline-success btn-sm"><i class="fa fa-arrow-left"></i> Volver </a>
                                <!-- <?php
                                if (sizeof($detalleCL) > 0) {
                                    $id = '"' . $compraCabecera->idCompraHemo . '"';
                                    if ($estado == "Pendiente") {
                                        $proveedor = $compraCabecera->nombreEmpresa;
                                        echo "<a onclick='saldar($id)' href='#saldarCompra' data-toggle='modal' class='btn btn-success btn-sm'><i class='fa fa-check'></i> Saldar </a>";
                                    }
                                    if ($estado != "Pendiente") {
                                        $proveedor = $compraCabecera->nombreEmpresa;
                                        echo "<a href='" . base_url() . "Botiquin/detalle_compra_pdf/" . $compraCabecera->idCompraHemo . "' class='btn btn-danger btn-sm' target='_blank'><i class='fa fa-file-pdf'></i> Ver en PDF </a>";
                                    }
                                }
                                ?> -->

                                <?php
                                if ($compraCabecera->estadoCompraHemo == 1) {
                                    echo '<a href="#agregarInsumosHemo" data-toggle="modal" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Insumos </a> ';
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
                                    <td><?php echo $compraCabecera->tipoCompraHemo; ?></td>
                                    <td><strong>Número de documento</strong></td>
                                    <td><?php echo $compraCabecera->numeroCompraHemo; ?></td>
                                    <td><strong>Fecha</strong></td>
                                    <td><?php echo $compraCabecera->fechaCompraHemo; ?></td>
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
                                    <td><?php echo $compraCabecera->descripcionCompraHemo; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2" class="text-center">
                                        <?php
                                        if($compraCabecera->estadoCompraHemo == 1){
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
                                                    $totalCompraIL += ($detalle->cantidad * $detalle->precio);
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $detalle->nombreInsumoHemo; ?></td>
                                                <td class="text-center">$ <?php echo $detalle->precio; ?></td>
                                                <td class="text-center"><?php echo $detalle->cantidad; ?></td>
                                                <td class="text-center">$ <?php echo number_format(($detalle->cantidad * $detalle->precio), 2) ?></td>
                                                <td class="text-center"><?php echo $detalle->vencimiento; ?></td>
                                                <td class="text-center">
                                                    <input type="hidden" value="<?php echo $detalle->idDCompraHemo; ?>" class="filaILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->idInsumo; ?>" class="idILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->cantidad; ?>" class="cantidadILEdit">
                                                    <input type="hidden" value="<?php echo $detalle->precio; ?>" class="precioILEdit">
                                                    <?php
                                                        echo "<a href='#actualizarDetalleCL' data-toggle='modal'><i class='fas fa-edit ms-text-success editarDetalleCL'></i></a>";
                                                        echo "<a href='#eliminarCH' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger eliminarDetalleCL'></i></a>";
                                                    ?>
                                                </td>
                                            </tr>

                                            <?php
                                                }
                                                $totalCompraIL = ($totalCompraIL + $compraCabecera->otrosCompraHemo);
                                            ?>

                                            <tr>
                                                <td class="text-right" colspan="5"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sumas</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($totalCompraIL, 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="5"><strong>IVA</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format((($totalCompraIL) * 0.13), 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="5"><strong>(+) IVA Percibido</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($compraCabecera->ivaPercibido, 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="5"><strong>(+) Otros</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($compraCabecera->otrosCompraHemo, 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="5"><strong>(-) Descuento</strong></td>
                                                <td class="text-left">$ <strong><?php echo number_format($compraCabecera->descuentoCompraHemo, 2); ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td class="text-right" colspan="5"><strong>Total</strong></td>
                                                <td class="text-left">$
                                                    <strong> 
                                                        <?php echo number_format((($totalCompraIL + ($totalCompraIL * 0.13) ) + $compraCabecera->ivaPercibido - $compraCabecera->descuentoCompraHemo), 2); ?>
                                                    </strong>
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

<!-- Modal para agregar datos del insumo-->
    <div class="modal fade" id="agregarInsumosHemo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Lista de insumos hemodiálisis</h4>
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
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <th class="text-center" scope="col"> Precio </th>
                                                    <th class="text-center" scope="col">Vencimiento</th>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($listaIL as $il) {
                                                    if($il->pivoteInsumoHemo == 1){
                                                ?>
                                                        <tr>
                                                            <td class="text-center" scope="row"><?php echo $il->nombreInsumoHemo ?></td>
                                                            <td class="text-center precio">
                                                                <input type="text" class="cantidadIL form-control menosHeight" value="1">
                                                                <input type="hidden" value="<?php echo $il->idInsumoHemo; ?>" id="test" class="form-control idIL">
                                                                <input type="hidden" value="<?php echo $il->codigoInsumoHemo; ?>" id="test" class="form-control codigoIL">
                                                                <input type="hidden" value="<?php echo $il->nombreInsumoHemo; ?>" id="test" class="form-control nombreIL">
                                                                <input type="hidden" value="<?php echo $il->precioInsumoHemo; ?>" id="test" class="form-control">
                                                                <input type="hidden" value="<?php echo $il->stockInsumoHemo; ?>" id="test" class="form-control stockIL">
                                                                <input type="hidden" value="<?php echo $compra; ?>" id="test" class="form-control facturaIL">
                                                            </td>
                                                            <td class="text-center" scope="row"><input type="text" value="<?php echo $il->precioInsumoHemo; ?>" style="width: 100px;" class="form-control menosHeight precioIL">  </td>
                                                            <td class="text-center" scope="row"><input type="date" class="form-control menosHeight vencimientoIL"  size="5">  </td>
                                                            <td class="text-center">
                                                                <?php
                                                                    echo "<a href='#' class='ocultarIL agregarIL' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary'></i></a>";
                                                                ?>

                                                            </td>
                                                        </tr>
                                                <?php }} ?>
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
<!-- Fin Modal para agregar datos del insumo-->

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
                                <form class="needs-validation" id="frmInsumo" method="post" action="<?php echo base_url() ?>InsumosHemo/actualizar_detalle_cl" novalidate>

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
    <div class="modal fade" id="eliminarCH" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                <form action="<?php echo base_url() ?>InsumosHemo/eliminar_medicamento_ch" method="post">
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

<!-- Modal para agregar datos extras de la factura-->
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
                                <form action="<?php echo base_url(); ?>InsumosHemo/guardar_extras" method="post">
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="">IVA Percibido</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="<?php echo $compraCabecera->ivaPercibido; ?>" id="ivaPercibido" name="ivaPercibido" placeholder="Ingrese la cantidad de IVA percibido" required>
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
                                                <input type="text" class="form-control" value="<?php echo $compraCabecera->descuentoCompraHemo; ?>" id="descuentoCompra" name="descuentoCompra" placeholder="Ingrese la cantidad de descuento" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la cantidad de descuento.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="">Otros</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="<?php echo $compraCabecera->otrosCompraHemo; ?>" id="otrosCompra" name="otrosCompra" placeholder="Detalle costo de otros elementos">
                                                <div class="invalid-tooltip">
                                                    Detalle costo de otros elementos.
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
<!-- Fin Modal para agregar datos extras de la factura-->


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
            factura: $(this).closest('tr').find(".facturaIL").val(),
            vencimiento: $(this).closest('tr').find(".vencimientoIL").val(),
        }

        $.ajax({
            url: "../../guardar_insumos_hemo",
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

        $("#filaILEdit2").val(filaILEdit);
        $("#idILEdit2").val(idILEdit);
        $("#cantidadILEdit2").val(cantidadILEdit);
        $("#cantidadInsumo").val(cantidadILEdit);
        $("#precioILEdit2").val(precioILEdit);

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
