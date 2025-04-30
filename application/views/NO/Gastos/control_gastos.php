<?php
    if ($this->session->flashdata("exito")) : ?>
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
            toastr.error('<?php echo $this->session->flashdata("error") ?>', 'Aviso!');
        });
    </script>
<?php endif; ?>

<?php
    $totalGastos = 0;
    foreach ($listaGastos as $cuenta) {
        $totalGastos += $cuenta->montoGasto;
    }
?>

<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Gastos</a> </li>
                    <li class="breadcrumb-item active"><a href="#">Control de gastos</a></li>
                    <?php
                        if(sizeof($listaGastos) > 0){
                            echo '<li class="breadcrumb-item"><a href="#">Total de gastos <strong>$ '.number_format($totalGastos, 2).'</strong></a></li>';
                        }
                    ?>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-5">
                            <h6 id="titleLista">Listado de gastos del mes de <?php echo $mes; ?></h6>
                            <h6 id="titleFecha" class="ocultarElemento">Seleccione una fecha</h6>
                        </div>
                        <div class="col-md-2">
                            <strong> Por fecha </strong>
                            <label class="ms-checkbox-wrap">
                                <input class="form-check-input" type="checkbox" id="verPorFechas" value="porFecha" name="porFecha">
                                <i class="ms-checkbox-check"></i>
                            </label>
                        </div>
                        <div class="col-md-5 text-right" id="divBotonera">
                            

                            <?php
                                if($this->session->userdata('acceso_h') == 1 || $this->session->userdata('acceso_h') == 5){
                            ?>
                                <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-print"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <?php
                                        echo "<a class='dropdown-item' title='Agregar gasto' href='#agregarDatosGasto' data-toggle='modal'>Gasto</a>";
                                        echo "<a class='dropdown-item' title='Agregar Cheque' href='#agregarDatosCheque' data-toggle='modal'>Cheque</a>";
                                    ?>
                                </div>
                                </div>

                            <?php
                                }else{
                                    echo '<button class="btn btn-primary btn-sm" href="#agregarDatosGasto" data-toggle="modal"><i class="fa fa-plus"></i> Agregar gasto</button>';
                                }
                            ?>


                            <a href="<?php echo base_url() ?>Gastos/gastos_excel/<?php echo $inicio ?>/<?php echo $fin ?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                        </div>
                    </div>
                </div>


                <div class="ms-panel-body">
                    <div class="row mt-3" id="detalleGeneralGastos">
                        <?php
                        if (sizeof($listaGastos) > 0) {
                        ?>
                            <div class="table-responsive">
                                <table id="tabla-gastos" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Detalle</th>
                                            <th class="text-center">Monto</th>
                                            <th class="text-center">Proveedor</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tipoEntidad = "";
                                        $proveedor = "";
                                        foreach ($listaGastos as $cuenta) {
                                            if($cuenta->estadoGasto == 1){
                                                $id ='"'.$cuenta->idGasto.'"';
                                                $tipo ='"'.$cuenta->tipoGasto.'"';
                                                $monto ='"'.$cuenta->montoGasto.'"';
                                                $entregado ='"'.$cuenta->entregadoGasto.'"';
                                                $idCuenta ='"'.$cuenta->idCuentaGasto.'"';
                                                $fecha ='"'.$cuenta->fechaGasto.'"';
                                                $entidad ='"'.$cuenta->entidadGasto.'"';
                                                $idProveedor ='"'.$cuenta->idProveedorGasto.'"';
                                                $pago ='"'.$cuenta->pagoGasto.'"';
                                                $numero ='"'.$cuenta->numeroGasto.'"';
                                                $descripcion ='"'.$cuenta->descripcionGasto.'"';
                                                $codigo ='"'.$cuenta->codigoGasto.'"';
                                                $efectuado ='"'.$cuenta->efectuoGasto.'"';
                                                $banco ='"'.$cuenta->banco.'"';
                                                $categoriaGasto ='"'.$cuenta->categoriaGasto.'"';
                                                $ncuenta ='"'.$cuenta-> cuentaGasto.'"';

                                                if ($cuenta->entidadGasto == 1) {
                                                    $tipoEntidad = "Médico";
                                                    //$medico = $this->Externos_Model->detalleExternoMedico($cuenta->idProveedorGasto);
                                                    $medico = $this->Externos_Model->obtenerMedico($cuenta->idProveedorGasto);
                                                    $proveedor = $medico->nombreMedico;
                                                } else {
                                                    $tipoEntidad = "Otros proveedores";
                                                    $medico = $this->Pendientes_Model->rowProveedor($cuenta->idProveedorGasto);
                                                    $proveedor = $medico->empresaProveedor;
                                                }
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo "$cuenta->codigoGasto"; ?></td>
                                                <td class="text-center"><?php echo $cuenta->nombreCuenta; ?></td>
                                                <td class="text-center">$ <?php echo $cuenta->montoGasto; ?></td>
                                                <td class="text-center"><?php echo $proveedor; ?></td>
                                                <td class="text-center"><?php echo wordwrap($cuenta->descripcionGasto,60,"<br>\n"); ?></td>
                                                <td class="text-center"><?php echo $cuenta->fechaGasto; ?></td>
                                                <td>
                                                <input type="hidden" value="<?php echo $cuenta->idGasto; ?>" class="identificadorGasto" id="identificadorGasto" name="identificadorGasto">
                                                    <?php
                                                        if($cuenta->pagoGasto == 2 && $this->session->userdata('acceso_h') == 1 || $this->session->userdata('acceso_h') == 5){
                                                    ?>
                                                        <div class="btn-group">
                                                        <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-print"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <?php
                                                                $ip = $_SERVER['REMOTE_ADDR'];
                                                                $urlVoucher = "http://".$ip."/"."sirius/Home/imprimir_cheque/".$cuenta->idGasto."/".$cuenta->idProveedorGasto."/".$cuenta->entidadGasto."/1";
                                                                $urlGasto = "http://".$ip."/"."sirius/Home/imprimir_gasto/".$cuenta->idGasto."/".$cuenta->idProveedorGasto."/".$cuenta->entidadGasto."/1";

                                                                echo "<a class='dropdown-item' title='Imprimir recibo' href='".base_url()."Gastos/imprimir_recibo/".$cuenta->idGasto."/".$cuenta->idProveedorGasto."/".$cuenta->entidadGasto."/".$cuenta->flagGasto."' target='_blank'>Recibo</a>";
                                                                echo "<a class='dropdown-item' title='Imprimir recibo' href='".$urlGasto."' target='blank'>Gasto</a>";
                                                                echo "<a class='dropdown-item' title='Imprimir recibo' href='".$urlVoucher."' target='blank'>Gasto-Vouche</a>";
                                                                echo "<a class='dropdown-item' title='Editar datos' href='#editarGasto' onclick='editarGasto($id, $tipo, $monto, $entregado, $idCuenta, $fecha, $entidad, $idProveedor, $pago, $numero, $descripcion, $codigo, $efectuado, $banco, $ncuenta, $categoriaGasto)' data-toggle='modal'>Editar gasto</a>";

                                                            ?>
                                                        </div>
                                                        </div>

                                                    <?php
                                                        }else{
                                                            echo "<a title='Editar datos' href='#editarGasto' onclick='editarGasto($id, $tipo, $monto, $entregado, $idCuenta, $fecha, $entidad, $idProveedor, $pago, $numero, $descripcion, $codigo, $efectuado, $banco, $ncuenta, $categoriaGasto)' data-toggle='modal'><i class='fas fa-edit ms-text-primary'></i></a>";
                                                            if($cuenta->flagGasto == 2){
                                                                echo "<a title='Imprimir recibo' href='".base_url()."Honorarios/mostrar_pagos/".$cuenta->idGasto."/' target='blank'><i class='fas fa-print ms-text-primary'></i></a>";
                                                            }else{
                                                                echo "<a title='Imprimir recibo' href='".base_url()."Gastos/imprimir_recibo/".$cuenta->idGasto."/".$cuenta->idProveedorGasto."/".$cuenta->entidadGasto."/".$cuenta->flagGasto."' target='blank'><i class='fas fa-print ms-text-primary'></i></a>";
                                                            }

                                                            switch($this->session->userdata('nivel')) {
                                                                    case '1':
                                                                        echo "<a title='Eliminar datos' href='#' class='btnEliminarGasto' ><i class='fa fa-trash-alt ms-text-danger'></i></a>";
                                                                    break;
                                                                    default:
                                                                        echo "";
                                                                        break;
                                                                }

                                                        }
                                                    ?>

                                                </td>
                                            </tr>
                                        <?php 
                                                $proveedor = "";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } else {
                            echo '<div class="col-md-12 alert alert-danger">
                                        <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                    </div>';
                        }
                        ?>

                    </div>

                    <div class="row mt-3" id="formularioFechas">
                        <div class="col-md-8 tade">
                            <form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url(); ?>Gastos/control_gastos_fecha" novalidate="">
                                <div class="form-row align-items-center">
                                    <div class="col-md-4">
                                        <select name="mesReporte" id="mesReporte" class="form-control" required="">
                                            <option value="">.:: Seleccionar un mes ::.</option>
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el mes.
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <select name="anioReporte" id="anioReporte" class="form-control" required="">
                                            <option value="">.:: Seleccionar el año ::.</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                            <option value="2028">2028</option>
                                            <option value="2029">2029</option>
                                            <option value="2030">2030</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el año.
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success mb-2"><i class="fa fa-arrow-right"></i> Consultar</button>
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

<!-- Modal para agregar datos del gasto-->
    <div class="modal fade" id="agregarDatosGasto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del gasto</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="" method="post" action="<?php echo base_url() ?>Gastos/guardar_gasto" novalidate>
                                    
                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Código:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" value="<?php echo $cod; ?>" required readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Tipo de Gasto:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="tipoGasto" name="tipoGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    $flag = 0;
                                                    foreach ($tipoGasto as $tipo) {
                                                        if($flag == 0){
                                                    ?>
                                                        <option value="<?php echo $tipo->idTipoGasto; ?>" selected="selected"><?php echo $tipo->nombreTipoGasto; ?></option>
                                                    <?php }else{ ?>
                                                        <option value="<?php echo $tipo->idTipoGasto; ?>"><?php echo $tipo->nombreTipoGasto; ?></option>
                                                    <?php } $flag++;} ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione una opción.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Monto:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" id="montoGasto" name="montoGasto" placeholder="Ingrese el monto del gasto" onKeyPress="return soloNumeros(event)" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Entregado a:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="entregadoGasto" name="entregadoGasto" placeholder="A quien se entrega el dinero" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre de la persona a quien se entrega el dinero.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Cuenta de gastos:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlInteligente" id="idCuentaGasto" name="idCuentaGasto" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <?php
                                                    foreach ($cuentas as $cuenta) {
                                                    ?>
                                                        <option value="<?= $cuenta->idCuenta ?>"><?= $cuenta->nombreCuenta ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGasto" name="fechaGasto" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Para:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlInteligente" id="areaGasto" name="areaGasto" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <?php
                                                        foreach ($categorias as $row) {
                                                            echo '<option value="'.$row->idCategoria.'">'.$row->nombreCategoria.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Debes seleccionar el area que requiere el gasto.
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGasto" name="fechaGasto" required>
                                        </div>
                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Tipo entidad:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control entidadGasto" id="entidadGasto" name="entidadGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="1">Médico</option>
                                                    <option value="2">Proveedor</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un proveedor.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Proveedor:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlInteligente idProveedorGasto" id="idProveedorGasto" name="idProveedorGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    foreach ($proveedores as $proveedor) {
                                                    ?>
                                                        <option value="<?= $proveedor->idProveedor ?>"><?= $proveedor->empresaProveedor ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un proveedor.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Forma de pago:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="pagoGasto" name="pagoGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="1">Efectivo</option>
                                                    <!-- <option value="2">Cheque</option> -->
                                                    <option value="3">Caja chica</option>
                                                    <option value="4">Cargo a cuenta</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese quien recibe el $.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="numeroCheque"></div>
                                    </div>

                                    <div class="form-row" id="detallesBanco"></div>


                                    <div class="form-row">

                                        <!-- <div class="col-md-6">
                                                <label for=""><strong>Recibido por:</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="receptorCuenta" name="receptorCuenta" placeholder="Persona que recibe el $" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese quien recibe el $.
                                                    </div>
                                                </div>
                                            </div>-->

                                        <div class="col-md-12">
                                            <label for=""><strong>Descripción:</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control disableSelect" id="descripcionCuenta" name=" descripcionGasto" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese una descripción.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="codigoGasto" value="<?php echo $cod; ?>">
                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save" id="envioDatos"></i> Guardar gasto</button>
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
<!-- Fin Modal para agregar datos del gasto-->

<!-- Modal para agregar datos del gasto-->
    <div class="modal fade" id="agregarDatosCheque" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del gasto</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="" method="post" action="<?php echo base_url() ?>Gastos/guardar_cheque" novalidate>
                                    
                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Código:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" value="<?php echo $cod; ?>" required readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Tipo de Gasto:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="tipoGasto" name="tipoGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    $flag = 0;
                                                    foreach ($tipoGasto as $tipo) {
                                                        if($flag == 0){
                                                    ?>
                                                        <option value="<?php echo $tipo->idTipoGasto; ?>" selected="selected"><?php echo $tipo->nombreTipoGasto; ?></option>
                                                    <?php }else{ ?>
                                                        <option value="<?php echo $tipo->idTipoGasto; ?>"><?php echo $tipo->nombreTipoGasto; ?></option>
                                                    <?php } $flag++;} ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione una opción.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Monto:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" id="montoGasto" name="montoGasto" placeholder="Ingrese el monto del gasto" onKeyPress="return soloNumeros(event)" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Entregado a:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="entregadoGasto" name="entregadoGasto" placeholder="A quien se entrega el dinero" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre de la persona a quien se entrega el dinero.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for=""><strong>Cuenta de gastos:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlCheque" id="idCuentaGastoC" name="idCuentaGasto" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <?php
                                                    foreach ($cuentas as $cuenta) {
                                                    ?>
                                                        <option value="<?= $cuenta->idCuenta ?>"><?= $cuenta->nombreCuenta ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGasto" name="fechaGasto" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Para:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="areaGasto" name="areaGasto" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <?php
                                                        foreach ($categorias as $row) {
                                                            echo '<option value="'.$row->idCategoria.'">'.$row->nombreCategoria.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Debes seleccionar el area que requiere el gasto.
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGasto" name="fechaGasto" required>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Tipo entidad:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control entidadGasto" id="entidadGasto" name="entidadGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="1">Médico</option>
                                                    <option value="2">Proveedor</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un proveedor.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Proveedor:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlCheque idProveedorGasto" id="idProveedorGastoC" name="idProveedorGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    foreach ($proveedores as $proveedor) {
                                                    ?>
                                                        <option value="<?= $proveedor->idProveedor ?>"><?= $proveedor->empresaProveedor ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un proveedor.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Forma de pago:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="pagoGasto" name="pagoGasto" required>
                                                    <option value="2">Cheque</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese forma de pago.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Número de cheque:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="chequeGasto" name="chequeGasto" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el numero de cheque.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                        <label for=""><strong>Banco:</strong></label>
                                        <div class="input-group">
                                            <select class="form-control" id="bancoGasto" name="bancoGasto" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($bancos as $row) {
                                                ?>
                                                <option value="<?php echo $row->idBanco; ?>"><?php echo $row->nombreBanco; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Ingrese quien recibe el $.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for=""><strong>N° de cuenta:</strong></label>
                                        <div class="input-group">
                                            <select class="form-control numeros" id="cuentaGasto" name="cuentaGasto" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($cuentasBanco as $row) {
                                                ?>
                                                <option value="<?php echo $row->idCuenta; ?>"><?php echo $row->nombreCuenta." - ".$row->numeroCuenta; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                            
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
                                        </div>
                                    </div>


                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for=""><strong>Descripción:</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control disableSelect" id="descripcionCuenta" name=" descripcionGasto" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese una descripción.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="codigoGasto" value="<?php echo $cod; ?>">
                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save" id="envioDatos"></i> Guardar gasto</button>
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
<!-- Fin Modal para agregar datos del gasto-->

<!-- Modal para editar Gasto-->
    <div class="modal fade" id="editarGasto" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del gasto</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="" method="post" action="<?php echo base_url() ?>Gastos/editar_gasto" novalidate>
                                    
                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Código:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" id="codigoGastoA" required readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Tipo de Gasto:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="tipoGastoA" name="tipoGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    foreach ($tipoGasto as $tipo) {
                                                    ?>
                                                        <option value="<?php echo $tipo->idTipoGasto; ?>"><?php echo $tipo->nombreTipoGasto; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione una opción.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Monto:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" id="montoGastoA" name="montoGasto" placeholder="Ingrese el monto del gasto" onKeyPress="return soloNumeros(event)" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Entregado a:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="entregadoGastoA" name="entregadoGasto" placeholder="A quien se entrega el dinero" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre de la persona a quien se entrega el dinero.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Cuenta de gastos:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlInteligente2" id="idCuentaGastoA" name="idCuentaGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    foreach ($cuentas as $cuenta) {
                                                    ?>
                                                        <option value="<?= $cuenta->idCuenta ?>"><?= $cuenta->nombreCuenta ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGastoA" name="fechaGasto" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Para:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="categoriaGastoA" name="areaGasto" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <?php
                                                        foreach ($categorias as $row) {
                                                            echo '<option value="'.$row->idCategoria.'">'.$row->nombreCategoria.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Debes seleccionar el area que requiere el gasto.
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaGasto" name="fechaGasto" required>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Tipo entidad:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="entidadGastoA" name="entidadGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="1">Médico</option>
                                                    <option value="2">Proveedor</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un proveedor.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for=""><strong>Proveedor:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlInteligente2" id="idProveedorGastoA" name="idProveedorGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                    foreach ($proveedores as $proveedor) {
                                                    ?>
                                                        <option value="<?= $proveedor->idProveedor ?>"><?= $proveedor->empresaProveedor ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un proveedor.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <label for=""><strong>Forma de pago:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="pagoGastoA" name="pagoGasto" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="1">Efectivo</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Caja chica</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese quien recibe el $.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="numeroChequeA">
                                            <label for=""><strong>Número de cheque:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="chequeGastoA" name="numeroGasto">
                                                <div class="invalid-tooltip">
                                                    Ingrese el numero de cheque.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row" id="detallesBancoU">
                                        <div class="col-md-6">
                                            <label for=""><strong>Banco:</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="bancoGastoU" name="bancoGasto">
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php
                                                        foreach ($bancos as $row) {
                                                    ?>
                                                    <option value="<?php echo $row->idBanco; ?>"><?php echo $row->nombreBanco; ?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese quien recibe el $.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for=""><strong>N° de cuenta:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control numeros" id="cuentaGastoU" name="cuentaGasto" placeholder="Ingrese la cuenta del banco" onKeyPress="return soloNumeros(event)">
                                                <div class="invalid-tooltip">
                                                    Ingrese un nombre.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for=""><strong>Descripción:</strong></label>
                                            <div class="input-group">
                                                <textarea class="form-control disableSelect" id="descripcionCuentaA" name=" descripcionGasto" required></textarea>
                                                <div class="invalid-tooltip">
                                                    Ingrese una descripción.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <input type="hidden" id="idGastoA" name="idGasto" />
                                        <input type="hidden" class="form-control numeros" id="codigoGastoA2" name="codigoGasto">
                                        <input type="hidden" class="form-control" id="efectuoGasto" name="efectuoGasto">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar cuenta</button>
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
<!-- Fin Modal para editar Gasto-->

<script>
    $(document).ready(function() {

        $('.controlInteligente').select2({
            theme: "bootstrap4",
            dropdownParent: $("#agregarDatosGasto")
        });

        $('.controlInteligente2').select2({
            theme: "bootstrap4",
            dropdownParent: $("#editarGasto")
        });

        $('.controlCheque').select2({
            theme: "bootstrap4",
            dropdownParent: $("#agregarDatosCheque")
        });


        $(".idProveedorGasto").prop('disabled', true); //Bloqueando la entidad

        $(".entidadGasto").change(function() {
            $(".idProveedorGasto").prop('disabled', false); // Desbloqueando la entidad
            $('.idProveedorGasto').each(function() {
                $('.idProveedorGasto option').remove();
            })
            var flag = $(this).val();
            $.ajax({
                url: "tipo_entidad",
                type: "GET",
                data: {id: flag},
                success: function(respuesta) {
                    var registro = eval(respuesta);
                    if (registro.length > 0) {
                        var entidad = "";
                        for (var i = 0; i < registro.length; i++) {
                            if (flag == "1") {
                                entidad += "<option value='" + registro[i]["idMedico"] + "'>" + registro[i]["nombreMedico"] + "</option>";
                            } else {
                                entidad += "<option value='" + registro[i]["idProveedor"] + "'>" + registro[i]["empresaProveedor"] + "</option>";
                            }
                        }
                        $(".idProveedorGasto").append(entidad);
                    }

                }
            });

        });

        // Actualizar
            $("#idProveedorGastoA").prop('disabled', true); //Bloqueando la entidad
            $("#entidadGastoA").change(function() {
                $("#idProveedorGastoA").prop('disabled', false); // Desbloqueando la entidad
                $('#idProveedorGastoA').each(function() {
                    $('#idProveedorGastoA option').remove();
                })
                var flag = $(this).val();
                $.ajax({
                    url: "tipo_entidad",
                    type: "GET",
                    data: {id: flag},
                    success: function(respuesta) {
                        var registro = eval(respuesta);
                        if (registro.length > 0) {
                            var entidad = "";
                            for (var i = 0; i < registro.length; i++) {
                                if (flag == "1") {
                                    entidad += "<option value='" + registro[i]["idMedico"] + "'>" + registro[i]["nombreMedico"] + "</option>";
                                } else {
                                    entidad += "<option value='" + registro[i]["idProveedor"] + "'>" + registro[i]["empresaProveedor"] + "</option>";
                                }
                            }
                            $("#idProveedorGastoA").append(entidad);
                        }

                    }
                });

            });

    });

    // Ocultando y mostrando campo para cheque
    $(document).on('change', '#pagoGasto', function(event) {
        event.preventDefault();
        var valor = $(this).val();
        var html = '';
        var banco = '';
        html += '<label for=""><strong>Número de cheque:</strong></label>';
        html += '<div class="input-group">';
        html += '    <input type="text" class="form-control" id="chequeGasto" name="chequeGasto" required>';
        html += '    <div class="invalid-tooltip">';
        html += '        Ingrese el numero de cheque.';
        html += '    </div>';
        html += '</div>';

        // Informacion del banco
        banco += '<div class="col-md-6">';
        banco += '    <label for=""><strong>Banco:</strong></label>';
        banco += '    <div class="input-group">';
        banco += '        <select class="form-control" id="bancoGasto" name="bancoGasto" required>';
        banco += '            <option value="">.:: Seleccionar ::.</option>';
        banco += '            <option value="Hipotecario">Hipotecario</option>';
        banco += '            <option value="Davivienda">Davivienda</option>';
        banco += '            <option value="Agricola">Agricola</option>';
        banco += '            <option value="Promerica">Promerica</option>';
        banco += '        </select>';
        banco += '        <div class="invalid-tooltip">';
        banco += '            Ingrese quien recibe el $.';
        banco += '        </div>';
        banco += '    </div>';
        banco += '</div>';
        banco += '<div class="col-md-6">';
        banco += '    <label for=""><strong>N° de cuenta:</strong></label>';
        banco += '    <div class="input-group">';
        banco += '        <input type="text" class="form-control numeros" id="cuentaGasto" name="cuentaGasto" placeholder="Ingrese la cuenta del banco" onKeyPress="return soloNumeros(event)" required>';
        banco += '        <div class="invalid-tooltip">';
        banco += '            Ingrese un nombre.';
        banco += '        </div>';
        banco += '    </div>';
        banco += '</div>';

        // Informacion del banco
        if (valor == 2) {
            $("#numeroCheque").append(html);
            $("#detallesBanco").append(banco);
        } else {
            $("#numeroCheque").html('');
            $("#detallesBanco").html('');
        }

    });

    $(document).on('change', '#pagoGastoA', function(event) {
        event.preventDefault();
        var valor = $(this).val();
        var html = '';
        var banco = '';
        html += '<label for=""><strong>Número de cheque:</strong></label>';
        html += '<div class="input-group">';
        html += '    <input type="text" class="form-control" id="chequeGastoA" name="chequeGasto" required>';
        html += '    <div class="invalid-tooltip">';
        html += '        Ingrese el numero de cheque.';
        html += '    </div>';
        html += '</div>';

        banco += '<div class="col-md-6">';
        banco += '    <label for=""><strong>Banco:</strong></label>';
        banco += '    <div class="input-group">';
        banco += '        <select class="form-control" id="bancoGastoU" name="bancoGasto">';
        banco += '            <option value="">.:: Seleccionar ::.</option>';
        banco += '            <option value="Hipotecario">Hipotecario</option>';
        banco += '            <option value="Davivienda">Davivienda</option>';
        banco += '            <option value="Agricola">Agricola</option>';
        banco += '            <option value="Promerica">Promerica</option>';
        banco += '        </select>';
        banco += '        <div class="invalid-tooltip">';
        banco += '            Ingrese quien recibe el $.';
        banco += '        </div>';
        banco += '    </div>';
        banco += '</div>';
        banco += '<div class="col-md-6">';
        banco += '    <label for=""><strong>N° de cuenta:</strong></label>';
        banco += '    <div class="input-group">';
        banco += '        <input type="text" class="form-control numeros" id="cuentaGastoU" name="cuentaGasto" placeholder="Ingrese la cuenta del banco" onKeyPress="return soloNumeros(event)">';
        banco += '        <div class="invalid-tooltip">';
        banco += '            Ingrese un nombre.';
        banco += '        </div>';
        banco += '    </div>';
        banco += '</div>';
        
        if (valor == 2) {
            $("#numeroChequeA").show();
            $("#detallesBancoU").show();
        } else {
            $("#numeroChequeA").hide();
            $("#detallesBancoU").hide();
        }

    });
</script>


<script>
    $(document).ready(function() {
        $("#verPorFechas").click(function() {
            var valor = $('input:checkbox[name=porFecha]:checked').val();
            if (valor == "porFecha") {
                $("#detalleGeneralGastos").hide();
                $("#divBotonera").hide();
                $("#formularioFechas").fadeIn();
                $("#titleLista").hide();
                $("#titleFecha").fadeIn();
            } else {
                $("#formularioFechas").hide();
                $("#detalleGeneralGastos").fadeIn();
                $("#divBotonera").fadeIn();
                $("#titleLista").fadeIn();
                $("#titleFecha").hide();
            }
        });
    });
</script>

<script>
    function editarGasto(id, tipo, monto, entregado, idCuenta, fecha, entidad, idProveedor, pago, numero, descripcion, codigo, efectuo, banco, cuenta, categoriaGasto){
        //console.log(id, tipo, monto, entregado, idCuenta, fecha, entidad, idProveedor, pago, numero, descripcion, codigo, efectuo);
        document.getElementById("idGastoA").value = id;
        document.getElementById("tipoGastoA").value = tipo;
        document.getElementById("entregadoGastoA").value = entregado;
        document.getElementById("idCuentaGastoA").value = idCuenta;
        document.getElementById("montoGastoA").value = monto;
        document.getElementById("fechaGastoA").value = fecha;
        document.getElementById("entidadGastoA").value = entidad;
        document.getElementById("bancoGastoU").value = banco;
        document.getElementById("cuentaGastoU").value = cuenta;
        
        document.getElementById("pagoGastoA").value = pago;
        if(pago == 1){
            $("#numeroChequeA").hide();
            $("#detallesBancoU").hide();
        }else{
            $("#numeroChequeA").show();
            $("#detallesBancoU").show();
        }
        document.getElementById("chequeGastoA").value = numero;
        document.getElementById("descripcionCuentaA").value = descripcion;
        document.getElementById("codigoGastoA").value = codigo;
        document.getElementById("codigoGastoA2").value = codigo;
        document.getElementById("efectuoGasto").value = efectuo;
        document.getElementById("categoriaGastoA").value = categoriaGasto;

        $("#idProveedorGastoA").prop('disabled', false); // Desbloqueando la entidad
        $('#idProveedorGastoA').each(function() {
            $('#idProveedorGastoA option').remove();
        })
        $.ajax({
            url: "tipo_entidad",
            type: "GET",
            data: {id: entidad},
            success: function(respuesta) {
                var registro = eval(respuesta);
                if (registro.length > 0) {
                    var entidadHTML = "";
                    for (var i = 0; i < registro.length; i++) {
                        if (entidad == 1) {
                            if(registro[i]["idMedico"] == idProveedor){
                                entidadHTML += "<option value='" + registro[i]["idMedico"] + "' selected>" + registro[i]["nombreMedico"] + "</option>";
                            }else{
                                entidadHTML += "<option value='" + registro[i]["idMedico"] + "'>" + registro[i]["nombreMedico"] + "</option>";
                            }
                        } else {
                            if(registro[i]["idProveedor"] == idProveedor){
                                entidadHTML += "<option value='" + registro[i]["idProveedor"] + "' selected>" + registro[i]["empresaProveedor"] + "</option>";
                            }else{
                                entidadHTML += "<option value='" + registro[i]["idProveedor"] + "'>" + registro[i]["empresaProveedor"] + "</option>";
                            }
                        }
                    }
                    $("#idProveedorGastoA").append(entidadHTML);
                }

            }
        });
        document.getElementById("idProveedorGastoA").value = idProveedor;
       
    }

    $(document).on('click', '.btnEliminarGasto', function(event) {
        event.preventDefault();
        
        motivo = window.prompt("Motivo por el cual se eliminara el gasto: ");
        if(motivo == ""){
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
            toastr.error('El gasto no puede ser eliminado sin motivo...', 'Aviso!');
        }else{
            var gasto = {
                motivo: motivo,
                estado: 0,
                idGasto: $(this).closest('tr').find(".identificadorGasto").val(),
            }
            $.ajax({
                url: "eliminar_gasto",
                type: "POST",
                data: gasto,
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
                            toastr.success('Gasto eliminado', 'Aviso!');
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
                            toastr.error('El gasto no puede ser eliminado...', 'Aviso!');
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
            $(this).closest('tr').remove();
        }


    });

    $(document).ready(function() {
        $("#entregadoGasto").val("");
        $("#montoGasto").val("");
        $("#entidadGasto").val("");
        $("#pagoGasto").val("");
        $("#descripcionCuenta").val("");
        $("#idCuentaGasto").val("");
        $("#idProveedorGasto").val("");
    });
</script>