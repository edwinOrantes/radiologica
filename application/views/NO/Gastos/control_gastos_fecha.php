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
                        <div class="col-md-3">
                            <h6 id="">Listado de gastos del mes de <?php echo $mes; ?></h6>
                        </div>
                        <div class="col-md-7">
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
                        <div class="col-md-2 text-right" id="divBotonera">
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
                                                <td class="text-center">$<?php echo $cuenta->montoGasto; ?></td>
                                                <td class="text-center"><?php echo $proveedor; ?></td>
                                                <td class="text-center"><?php echo wordwrap($cuenta->descripcionGasto,60,"<br>\n"); ?></td>
                                                <td class="text-center"><?php echo $cuenta->fechaGasto; ?></td>
                                                <td>
                                                <input type="hidden" value="<?php echo $cuenta->idGasto; ?>" class="identificadorGasto" id="identificadorGasto" name="identificadorGasto">
                                                <?php
                                                    echo "<a title='Imprimir recibo' href='".base_url()."Gastos/imprimir_recibo/".$cuenta->idGasto."/".$cuenta->idProveedorGasto."/".$cuenta->entidadGasto."/".$cuenta->flagGasto."' target='blank'><i class='fas fa-print ms-text-primary'></i></a>";
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

                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('.controlInteligente').select2({
            theme: "bootstrap4",
            dropdownParent: $("#agregarGasto")
        });

        $('.controlInteligente2').select2({
            theme: "bootstrap4",
            dropdownParent: $("#editarGasto")
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
