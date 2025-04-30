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
   /*  $totalResultado = 0;
    foreach ($movimientos as $movimiento) {
        if($movimiento->tipoMedicamento != "Servicios" && $movimiento->tipoMedicamento != "Otros servicios"){

            
            if($movimiento->tipoKardex == 1){
                $totalResultado -= ($movimiento->cantidadMedicamento * $movimiento->precioMedicamento);
            }else{
                $totalResultado += ($movimiento->cantidadMedicamento * $movimiento->precioMedicamento);
            }
        }
    } */
?>



<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-chart-line"></i> Botiquín </a>
                    </li>
                    <li class="breadcrumb-item active"><a href="#">Movimientos</a></li>
                    <!-- <li class="breadcrumb-item"><a href="#"><?php echo "$ ".number_format($totalResultado, 2); ?></a></li> -->
                </ol>
            </nav>

            <div class="ms-panel">
                
                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-5">
                            <h6 id="titleLista">Movimientos en el inventario durante el mes de <?php echo $mes; ?></h6>
                            <h6 id="titleFecha" class="ocultarElemento">Seleccione una fecha</h6>
                        </div>
                        <div class="col-md-2">
                            <!-- <label class="ms-checkbox-wrap">
                                <button class="btn btn-success btn-sm">Ver en excel</button>
                                <i class="ms-checkbox-check"></i>
                            </label> -->
                        </div>
                        <div class="col-md-5 text-right" id="divBotonera">
                            <!-- <a href="<?php echo base_url() ?>Gastos/gastos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
                            <strong> Por fecha </strong>
                            <label class="ms-checkbox-wrap">
                                <input class="form-check-input" type="checkbox" id="verPorFechas" value="porFecha" name="porFecha">
                                <i class="ms-checkbox-check"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="ms-panel-body">
                    <div class="row mt-3" id="detalleGeneralGastos">
                        <?php
                            if(sizeof($movimientos) > 0){
                        ?>

                            <div class="table-responsive mt-3">
                                <table id="tabla-kardex" class="table thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th class="text-center" scope="col">Fecha</th>
                                            <th class="text-center" scope="col">Insumo</th>
                                            <th class="text-center" scope="col">Precio</th>
                                            <th class="text-center" scope="col">Salidas</th>
                                            <th class="text-center" scope="col">Entradas</th>
                                            <!-- <th class="text-center" scope="col">SALDOS</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                            $index = 0;
                                            $totalMes = 0;
                                            $por = "";
                                            $nombreMedicamento = "";
                                            foreach ($movimientos as $movimiento) {
                                                if($movimiento->tipoMedicamento != "Servicios" && $movimiento->tipoMedicamento != "Otros servicios"){
                                                    
                                                    echo '<tr>';
                                                    // Mostrando elementos
                                                        $index++;
                                                        echo '    <td class="text-center">'.$index.'</td>';
                                                        // echo '    <td class="text-center">'.substr($movimiento->fechaMedicamento, 0, 10).'</td>';
                                                        echo '    <td class="text-center">'.$movimiento->fechaMovimiento.'</td>';
                                                        echo '    <td class="text-center">'.$movimiento->nombreMedicamento.'</td>';
                                                        echo '    <td class="text-center">$ '.$movimiento->precioVMedicamento.'</td>';

                                                        if($movimiento->tipoKardex == 'Salida'){
                                                            echo '<td class="text-center text-danger">'.$movimiento->cantidadInsumo.'</td>';
                                                            echo '<td class="text-center"></td>';
                                                        }else{
                                                            echo '<td class="text-center"></td>';
                                                            echo '<td class="text-center text-danger">'.$movimiento->cantidadInsumo.'</td>';
                                                        }

                                                        // if($movimiento->tipoProceso == 1){
                                                        //     echo '    <td class="text-center text-danger">$ '.number_format($totalMes, 2).'</td>';
                                                        // }else{
                                                        //     echo '    <td class="text-center text-success">$ '.number_format($totalMes, 2).'</td>';
                                                        // }

                                                    echo '</tr>';
                                                }
                                            }
                                        ?>
                                    </tbody>

                                   <tfoot>
                                       <tr>
                                           <th></th>
                                           <th></th>
                                           <th></th>
                                           <th></th>
                                           <th></th>
                                           <th class="text-center"></th>
                                       </tr>
                                   </tfoot>

                                </table>
                            </div>
                        <?php
                            }else{
                                echo '<div class="alert alert-danger col-md-12">
                                        <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                    </div>';
                            }
                        ?>
                    </div>

                    <div class="row mt-3" id="formularioFechas">
                        <div class="col-md-8 tade">
                            <form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url(); ?>Botiquin/kardex_fecha" novalidate="">
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


        // Estilo a datatable de kardex
        var tabla = $('#tabla-kardex').DataTable({
            "drawCallback":function(){
                var api = this.api();
                $(api.column(6).footer()).html(
                    (api.column(6, {page:'current'}).data().sum()).toFixed(2)
                )
                // console.log(api.column(3, {page:'current'}).data().sum());
                //$("#totalHonorario").text("$"+(api.column(3, {page:'current'}).data().sum()).toFixed(2));
            }
        });
    });
</script>