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


<?php
$totalFactura = 0;
foreach ($cuentas as $cuenta) {
    $totalFactura += $cuenta->totalCuentaPagar;
}
?>
<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                        <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fas fa-clipboard-list"></i> Cuentas por pagar </a></li>
                        <li class="breadcrumb-item active"><a href="#">Lista de cuentas</a></li>
                        <li class="breadcrumb-item"><a href="#">Total cuentas<strong>$ <?php echo number_format($totalFactura, 2); ?></strong></a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-5">
                                <h6 id="titleLista">Lista de cuentas </h6>
                                <h6 id="titleFecha">Seleccione un proveedor </h6>
                            </div>
                            <div class="col-md-2">
                            <?php
                                if(sizeof($cuentas) > 0){
                            ?>
                                <strong> Saldar </strong>
                                <label class="ms-checkbox-wrap">
                                    <input class="form-check-input" type="checkbox" id="verPorFechas" value="porFecha" name="porFecha">
                                    <i class="ms-checkbox-check"></i>
                                </label>
                            <?php } ?>
                            </div>
                            <div class="col-md-5 text-right">
                                <div id="divBotonera">
                                    <a href="#agregarCuenta" data-toggle='modal' class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar cuenta</a>
                                </div>

                                <div id="proveedores">
                                    <div class="input-group">
                                        <select name="proveedorCuenta" id="proveedorCuenta" class="form-control controlInteligente ">
                                            <option value="" class="text-center">.:: Seleccionar un proveedor ::.</option>
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
                            </div>
                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <div class="row">
                            <div id="cuentasExistentes" class="col-md-12">
                                <div class="table-responsive mt-3">
                                <?php
                                    if(sizeof($cuentas) > 0){
                                ?>
                                    <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">#</th>
                                                <th class="text-center" scope="col">Fecha</th>
                                                <th class="text-center" scope="col">Proveedor</th>
                                                <th class="text-center" scope="col">Factura</th>
                                                <th class="text-center" scope="col">Monto</th>
                                                <th class="text-center" scope="col">Dias Transcurridos</th>
                                                <th class="text-center" scope="col">Estado</th>
                                                <th class="text-center" scope="col">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $index = 0;
                                                foreach ($cuentas as $cuenta) {
                                                    if($cuenta->facturaCuentaPagar != "---"){
                                                    $index++;
                                                    /* $id ='"'.$cuenta->idCuentaPagar.'"';
                                                    $idProveedor ='"'.$cuenta->idProveedor.'"';
                                                    $fechaCuentaPagar ='"'.$cuenta->fechaCuentaPagar.'"';
                                                    $nrcCuentaPagar ='"'.$cuenta->nrcCuentaPagar.'"';
                                                    $facturaCuentaPagar ='"'.$cuenta->facturaCuentaPagar.'"';
                                                    $plazoCuentaPagar ='"'.$cuenta->plazoCuentaPagar.'"';
                                                    $subtotalCuentaPagar ='"'.$cuenta->subtotalCuentaPagar.'"';
                                                    $ivaCuentaPagar ='"'.$cuenta->ivaCuentaPagar.'"';
                                                    $perivaCuentaPagar ='"'.$cuenta->perivaCuentaPagar.'"';
                                                    $totalCuentaPagar ='"'.$cuenta->totalCuentaPagar.'"'; */
                                            ?>
                                            <tr>
                                                <td class="text-center" scope="row">
                                                    <?php echo $index; ?>
                                                    <input type="hidden" class="idCuentaPagar" value="<?php echo $cuenta->idCuentaPagar; ?>">
                                                    <input type="hidden" class="idProveedor" value="<?php echo $cuenta->idProveedor; ?>">
                                                    <input type="hidden" class="fechaCuentaPagar" value="<?php echo $cuenta->fechaCuentaPagar; ?>">
                                                    <input type="hidden" class="nrcCuentaPagar" value="<?php echo $cuenta->nrcCuentaPagar; ?>">
                                                    <input type="hidden" class="facturaCuentaPagar" value="<?php echo $cuenta->facturaCuentaPagar; ?>">
                                                    <input type="hidden" class="plazoCuentaPagar" value="<?php echo $cuenta->plazoCuentaPagar; ?>">
                                                    <input type="hidden" class="subtotalCuentaPagar" value="<?php echo $cuenta->subtotalCuentaPagar; ?>">
                                                    <input type="hidden" class="ivaCuentaPagar" value="<?php echo $cuenta->ivaCuentaPagar; ?>">
                                                    <input type="hidden" class="perivaCuentaPagar" value="<?php echo $cuenta->perivaCuentaPagar; ?>">
                                                    <input type="hidden" class="totalCuentaPagar" value="<?php echo $cuenta->totalCuentaPagar; ?>">
                                                </td>
                                                <td class="text-center"><?php echo $cuenta->fechaCuentaPagar; ?></td>
                                                <td class="text-center"><?php echo $cuenta->empresaProveedor; ?></td>
                                                <td class="text-center"><?php echo $cuenta->facturaCuentaPagar; ?></td>
                                                <td class="text-center">$ <?php echo $cuenta->totalCuentaPagar; ?></td>
                                                <td class="text-center">
                                                    <?php
                                                        $fecha1= new DateTime($cuenta->fechaCuentaPagar);
                                                        $fecha2= new DateTime(date("Y-m-d"));
                                                        $diff = $fecha1->diff($fecha2);
                                                        if($cuenta->estadoCuentaPagar == 1){
                                                            if($diff->days <= 30){
                                                                echo $diff->days." dias";
                                                            }else{
                                                                echo ($diff->days - 30)." dias de vencida";
                                                            }
                                                        }else{
                                                            echo '<i class="fa fa-check-double fa-sm text-primary "></i>';
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php

                                                    if($cuenta->estadoCuentaPagar != 1){
                                                        echo '<span class="badge badge-outline-success">Saldada</span>';
                                                    }else{
                                                        if($diff->days <= 30){
                                                            echo '<span class="badge badge-outline-danger">Pendiente</span>';
                                                        }else{
                                                            echo '<span class="badge badge-outline-danger">Vencida</span>';
                                                        }
                                                    } 
                                                    
                                                    ?>
                                                </td>

                                                <td class="text-center"><?php
                                                    echo "<a class='actualizarCuenta' title='Actualizar datos del la cuenta' href='#actualizarCuenta' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                    echo "<a class='eliminarCuenta' title='Eliminar del médico' href='#eliminarCuenta' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                ?>
                                                </td>

                                                
                                            </tr>

                                            <?php 
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                                }else
                                {
                                    echo '<div class="alert alert-danger">
                                            <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                        </div>';
                                }
                                ?>   
                                </div>
                            </div>
                            <div id="cuentasProveedor" class="col-md-12">
                                <div class="table-responsive mt-3" id="tblCuentasPagar">
                                    <form action="<?php echo base_url();?>CuentasPendientes/saldar_cuentas" method="post">
                                        <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        <input class="" type="checkbox" id="seleccionarTodos" value="todos" name="seleccionarTodos[]">
                                                    </th>
                                                    <th class="text-center" scope="col">Fecha</th>
                                                    <th class="text-center" scope="col">Proveedor</th>
                                                    <th class="text-center" scope="col">Factura</th>
                                                    <th class="text-center" scope="col">Monto</th>
                                                    <th class="text-center" scope="col">Dias restantes</th>
                                                    <th class="text-center" scope="col">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detalleCuentasProveedor">
                                            </tbody>
                                        </table>

                                        <div class="text-center" id="botoneraHoja">
                                            <button class="btn btn-primary mt-4 d-inline w-20" id="btnSaldar" type="button"><i class="fa fa-save"></i> Saldar</button>
                                            <button class="btn btn-light mt-4 d-inline w-20 cancelar" type="button"><i class="fa fa-times"></i> Cancelar</button>
                                        </div>
                                    </form>    
                                </div>
                            </div>
                            
                            <div id="sinDatos" class="col-md-12">
                                <div class="alert alert-danger">
                                    <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo date('Y-m-d')?>" id="fechaHoy"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End Body Content Wrapper -->

<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="agregarCuenta" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Agregar datos de la cuenta</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
                                <!-- aseda -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="needs-validation" method="post" action="<?php echo base_url(); ?>CuentasPendientes/guardar_cuenta_pagar" novalidate>
                                            
                                            <div class="form-row">
                                                <div class="col-md-12" >
                                                    <label for=""><strong>Proveedor</strong></label>
                                                    <div class="input-group">
                                                        <select name="idProveedor" id="nuevoMedicamento" class="form-control controlInteligente ">
                                                            <option value="">.:: Seleccionar ::.</option>
                                                            <?php
                                                                foreach ($proveedores as $proveedor) {
                                                            ?>
                                                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->empresaProveedor; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="invalid-tooltip">
                                                            Seleccione el medicamento o insumo.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Fecha</strong></label>
                                                    <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" min="0" id="fechaCuenta" name="fechaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la fecha de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>NRC</strong></label>
                                                    <input type="text" class="form-control" min="0" id="nrcCuenta" name="nrcCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el NRC del proveedor.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>N° Factura</strong></label>
                                                    <input type="text" class="form-control numeros" min="0" id="facturaCuenta" name="facturaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el número de factura de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Plazo</strong></label>
                                                    <input type="text" class="form-control numeros" min="0" id="plazoCuenta" name="plazoCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el plazo de la cuenta por pagar..
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Sub total</strong></label>
                                                    <input type="text" class="form-control numeros sumaFactura" value="0" min="0" id="subtotalCuenta" name="subtotalCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el sub total de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>IVA</strong></label>
                                                    <input type="text" class="form-control numeros sumaFactura" value="0" min="0" id="ivaCuenta" name="ivaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar IVA de la cuenta por pagar..
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Per IVA</strong></label>
                                                    <input type="text" class="form-control numeros sumaFactura" value="0" min="0" id="perivaCuenta" name="perivaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el sub total de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Total</strong></label>
                                                    <input type="text" class="form-control numeros" value="0" min="0" id="totalCuenta" name="totalCuenta">
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar IVA percibido de la cuenta por pagar..
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-center" id="">
                                                <input type="hidden" value="1" name="estadoCuentaPagar"/>
                                                <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar </button>
                                                <button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <!-- sds -->
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Fin Modal para agregar datos del Medicamento-->

<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="actualizarCuenta" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Datos de la cuenta</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
                                <!-- aseda -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="needs-validation" method="post" action="<?php echo base_url(); ?>CuentasPendientes/actualizar_cuenta_pagar" novalidate>
                                            
                                            <div class="form-row">
                                                <div class="col-md-12" >
                                                    <label for=""><strong>Proveedor</strong></label>
                                                    <div class="input-group">
                                                        <select name="idProveedor" id="idProveedorA" class="form-control controlInteligente ">
                                                            <option value="">.:: Seleccionar ::.</option>
                                                            <?php
                                                                foreach ($proveedores as $proveedor) {
                                                            ?>
                                                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->empresaProveedor; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="invalid-tooltip">
                                                            Seleccione el medicamento o insumo.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Fecha</strong></label>
                                                    <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" min="0" id="fechaCuentaA" name="fechaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar la fecha de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>NRC</strong></label>
                                                    <input type="text" class="form-control" min="0" id="nrcCuentaA" name="nrcCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el NRC del proveedor.
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>N° Factura</strong></label>
                                                    <input type="text" class="form-control numeros" min="0" id="facturaCuentaA" name="facturaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el número de factura de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Plazo</strong></label>
                                                    <input type="text" class="form-control numeros" min="0" id="plazoCuentaA" name="plazoCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el plazo de la cuenta por pagar..
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Sub total</strong></label>
                                                    <input type="text" class="form-control numeros sumaFactura" value="0" min="0" id="subtotalCuentaA" name="subtotalCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el sub total de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>IVA</strong></label>
                                                    <input type="text" class="form-control numeros sumaFactura" value="0" min="0" id="ivaCuentaA" name="ivaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar IVA de la cuenta por pagar..
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Per IVA</strong></label>
                                                    <input type="text" class="form-control numeros sumaFactura" value="0" min="0" id="perivaCuentaA" name="perivaCuenta" required>
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar el sub total de la cuenta por pagar.
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for=""><strong>Total</strong></label>
                                                    <input type="text" class="form-control numeros" value="0" min="0" id="totalCuentaA" name="toatalCuenta">
                                                    <div class="invalid-tooltip">
                                                        Debes ingresar IVA percibido de la cuenta por pagar..
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-center" id="">
                                                <input type="hidden" id="idCuentaPagarA" name="idCuentaPagar"/>
                                                <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar </button>
                                                <button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                <!-- sds -->
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Fin Modal para agregar datos del Medicamento-->


<!-- Modal para cerrar hoja-->
<div class="modal fade p-5" id="agregarCheque" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">

			<div class="modal-header bg-primary">
				<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body text-center">
				<p class="h5">Ingresa el número de cheque</p>
			</div>

			<form action="<?php echo base_url()?>CuentasPendientes/saldar_cuentas" method="post">

                <div class="px-2 mb-3">
                    <div class="form-group">
                        <input type="text" id="chequeCuenta" name="chequeCuenta" class="form-control"/>
                        <div class="invalid-tooltip">
                            Debes ingresar el número de cheque.
                        </div>
                    </div>
                </div>

                <div id="entradasIds"></div>
                <div class="text-center">
					<button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-times"></i> Saldar </button>
					<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- Fin Modal cerrar hoja-->




<script>
    $(document).ready(function() {
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
    });

    $(document).on('change', '#nuevoMedicamento', function (event) {
        event.preventDefault();
        var proveedor = $(this).val();
        $.ajax({
            url: "validar_proveedor",
            type: "GET",
            data: {id: proveedor },
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (registro.length > 0){
                        for (let i = 0; i < registro.length; i++) {
                            let nrc = registro[i]["nrcProveedor"];
                            $("#nrcCuenta").val(nrc);
                        }
                    }
                }
        });
    });

    $(document).on('change', '.sumaFactura', function (event) {
        event.preventDefault();
        var totalFactura = 0; // Total de la factura
        // Sumando todos los input con totales unitarios
        $('.sumaFactura').each(function() {
            totalFactura += parseFloat($(this).val());
            console.log(totalFactura);
        });
        $("#totalCuenta").val(totalFactura); 
    });

    $(document).on('change', '#proveedorCuenta', function (event) {
        event.preventDefault();
        var proveedor = $(this).val();
        $("#detalleCuentasProveedor").html("");
        $.ajax({
            url: "obtener_proveedor",
            type: "GET",
            data: {id: proveedor },
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var hoy = $("#fechaHoy").val();
                    var totalFacturas = 0;
                    var html = "";
                        for (let i = 0; i < registro.length; i++) {
                            var totalFacturas = parseFloat(totalFacturas) + parseFloat(registro[i]["totalCuentaPagar"]);
                            // Obteniendo dias desde factura
                                var date_1 = new Date(hoy);
                                var date_2 = new Date(registro[i]["fechaCuentaPagar"]);
                                var day_as_milliseconds = 86400000;
                                var diff_in_millisenconds = date_1 - date_2;
                                var diff_in_days = diff_in_millisenconds / day_as_milliseconds;

                            html += '<tr>';
                            html += '    <td class="text-center" scope="row"><input class="cuentas" type="checkbox" id="seleccionarUnitario" value="'+registro[i]["idCuentaPagar"]+'" name="cuenta[]"></td>';
                            html += '    <td class="text-center">'+registro[i]["fechaCuentaPagar"]+'</td>';
                            html += '    <td class="text-center">'+registro[i]["empresaProveedor"]+'</td>';
                            html += '    <td class="text-center">'+registro[i]["facturaCuentaPagar"]+'</td>';
                            html += '    <td class="text-center">$ '+registro[i]["totalCuentaPagar"]+' <input type="hidden" class="montoFactura" value="'+registro[i]["totalCuentaPagar"]+'"> </td>';
                            html += '    <td class="text-center">'+ diff_in_days +' dias</td>';
                            if(diff_in_days <= 30){
                                
                                html += '    <td class="text-center"><span class="badge badge-outline-danger">Pendiente</span></td>';
                            }else{
                                html += '    <td class="text-center"><span class="badge badge-outline-danger">Vencida</span></td>';
                                
                            }
                            html += '</tr>';
                        }
                        html += '<tr>';
                        html += '    <td class="text-center" colspan=4><strong>TOTAL</strong></td>';
                        html += '    <td class="text-center"><strong>$ <span id="totalSumaFacturas">'+totalFacturas.toFixed(2)+'</span><input type="hidden" id="sumaFacturas" value="'+totalFacturas.toFixed(2)+'"></strong></td>';
                        html += '    <td class="text-center"></td>';
                        html += '    <td class="text-center"></td>';
                        html += '</tr>';
                        $("#detalleCuentasProveedor").append(html);
                        $("#sinDatos").hide();
                        $("#tblCuentasPagar").show();
                        
                    }else{
                        $("#tblCuentasPagar").hide();
                        $("#sinDatos").show();
                    }
                }
        });
    });

    $(document).on('click', '#btnSaldar', function (event) {
        //event.preventDefault();
        var arr = $('[name="cuenta[]"]:checked').map(function(){
            return this.value;
        }).get();
        if(arr.length > 0){
            var str = arr.join(',');
            var ids = str.split(',')
            var html = "";
            for (let i = 0; i < ids.length; i++) {
                //console.log(ids[i]);
                html += '<input type="hidden" value="'+ids[i]+'" name="cuenta[]" />';
            }
            $("#entradasIds").append(html);
            $("#agregarCheque").modal();
        }else{
            console.log("Nada");
        }
        
    });


    //Trabajando checkbox
        // add multiple select/unselect functionality
            /* $("#seleccionarTodos").on("click", function() {
                $(".cuentas").prop("checked", this.checked);
                if($(".montoFactura").hasClass()){

                }
                
                var totalGenerado = $("#sumaFacturas").val();
                $("#totalSumaFacturas").html(totalGenerado);
                // Limpiando clases
                $('.montoFactura').each(function() {
                    $(this).removeClass("seSaldara");
                });

            }); */

            // if all checkbox are selected, check the selectall checkbox and viceversa
            $(document).on('click', '.cuentas', function (event) {
                if ($(".cuentas").length == $(".cuentas:checked").length) {
                    $("#seleccionarTodos").prop("checked", true);
                } else {
                    $("#seleccionarTodos").prop("checked", false);
                }
            });
            
</script>

<script>
    $(document).ready(function() {
        $("#verPorFechas").click(function() {
            var valor = $('input:checkbox[name=porFecha]:checked').val();
            if (valor == "porFecha") {
                $("#cuentasExistentes").hide();
                $("#cuentasProveedor").fadeIn();
                $("#titleLista").hide();
                $("#titleFecha").fadeIn();
                $("#divBotonera").hide();
                $("#proveedores").fadeIn();
            } else {
                $("#cuentasProveedor").hide();
                $("#cuentasExistentes").fadeIn();
                $("#titleLista").fadeIn();
                $("#titleFecha").hide();
                $("#divBotonera").fadeIn();
                $("#proveedores").hide();
                $("#sinDatos").hide();
            }
        });
    });
</script>


<!-- Actualizando y eliminando cuentas -->
    <script>
        $(document).on('click', '.eliminarCuenta', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();

            var datos = {
                idCuentaPagar  : $(this).closest('tr').find(".idCuentaPagar").val()
            }
            console.log(datos);

            $.ajax({
                url: "eliminar_cuenta_pagar",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (registro.length > 0){
                            console.log(registro);
                        }
                    }
            });
        });

        $(document).on('click', '.actualizarCuenta', function (event) {
            event.preventDefault();

            /* idProveedor = $(this).closest('tr').find(".idProveedorA").val();  
            fechaCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val();  
            nrcCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val();  
            facturaCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val();  
            plazoCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val();  
            subtotalCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val();  
            ivaCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val();  
            perivaCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val(); 
            totalCuentaPagar = $(this).closest('tr').find(".idCuentaPagar").val(); */

            $("#idProveedorA").val($(this).closest('tr').find(".idProveedor").val());
            $("#fechaCuentaA").val($(this).closest('tr').find(".fechaCuentaPagar").val());
            $("#nrcCuentaA").val($(this).closest('tr').find(".nrcCuentaPagar").val());
            $("#facturaCuentaA").val($(this).closest('tr').find(".facturaCuentaPagar").val());
            $("#plazoCuentaA").val($(this).closest('tr').find(".plazoCuentaPagar").val());
            $("#subtotalCuentaA").val($(this).closest('tr').find(".subtotalCuentaPagar").val());
            $("#ivaCuentaA").val($(this).closest('tr').find(".ivaCuentaPagar").val());
            $("#perivaCuentaA").val($(this).closest('tr').find(".perivaCuentaPagar").val());
            $("#totalCuentaA").val($(this).closest('tr').find(".totalCuentaPagar").val());
            $("#idCuentaPagarA").val($(this).closest('tr').find(".idCuentaPagar").val());

        });

        $(document).on('change', '.sumaFactura', function (event) {
            event.preventDefault();
            var totalFactura = 0; // Total de la factura
            // Sumando todos los input con totales unitarios
            $('.sumaFactura').each(function() {
                totalFactura += parseFloat($(this).val());
                console.log(totalFactura);
            });
            $("#totalCuentaA").val(totalFactura); 
        });

        $(document).on('click', '.cuentas', function (event) {
            //event.preventDefault();
            var totalSaldar = 0;
            if(!$(this).closest('tr').find(".montoFactura").hasClass("seSaldara")){
                $(this).closest('tr').find(".montoFactura").addClass("seSaldara");
            }else{
                $(this).closest('tr').find(".montoFactura").removeClass("seSaldara");
            }

            //var totalFactura = $(this).closest('tr').find(".montoFactura").val();
            $('.seSaldara').each(function() {
                totalSaldar += parseFloat($(this).val());
                //console.log($(this).val());
            });
            $("#totalSumaFacturas").html(totalSaldar.toFixed(2));
            
        });
    </script>
<!-- Fin actualizando y eliminando cuentas -->










