
<a href="#rangoFechas" data-toggle="modal" class="user-flotante" title="Seleccionar rango de fechas"><i class="fa fa-calendar"></i></a>
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-xl-4 col-md-4 col-sm-6">
            <a href="#">
                <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">
                    <div class="ms-card-body media">
                        <div class="media-body">
                        <h6>Total este mes</h6>
                        <p class="ms-card-change">$ <?php echo number_format(($totalIngresos + $totalAmbulatorio), 2); ?></p>
                        <p class="fs-12 text-warning">$ <?php echo number_format((($totalIngresos + $totalAmbulatorio) * 0.80), 2); ?> <i class="fa fa-arrow-right"></i> a facturar.</p>
                        </div>
                    </div>
                    <i class="fas fa-hand-holding-usd ms-icon-mr text-white fa-sm"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <a href="#">
                <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">
                    <div class="ms-card-body media">
                        <div class="media-body">
                        <h6>Hospitalización</h6>
                        <p class="ms-card-change">$ <?php echo number_format(($totalIngresos), 2); ?></p>
                        <p class="fs-12 text-warning">Totales durante este mes.</p>
                        </div>
                    </div>
                    <i class="fas fa-procedures ms-icon-mr text-white fa-sm"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <a href="#">
                <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">
                    <div class="ms-card-body media">
                        <div class="media-body">
                        <h6>Ambulatorios</h6>
                        <p class="ms-card-change">$ <?php echo number_format(($totalAmbulatorio), 2); ?></p>
                        <p class="fs-12 text-warning">Totales durante este mes.</p>
                        </div>
                    </div>
                    <i class="fas fa-ambulance ms-icon-mr text-white fa-sm"></i>
                </div>
            </a>
        </div>
        
        <div class="col-xl-4 col-md-4 col-sm-6">
            <a href="#">
                <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">
                    <div class="ms-card-body media">
                        <div class="media-body">
                        <h6>Facturación mensual</h6>
                        <p class="ms-card-change">$ <?php echo number_format(($facturado), 2); ?></p>
                        </div>
                    </div>
                    <i class="fas fa-chart-line ms-icon-mr text-white fa-sm"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <a href="#">
                <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">
                    <div class="ms-card-body media">
                        <div class="media-body">
                        <h6>Ingreso promedio</h6>
                        <p class="ms-card-change">$ <?php echo number_format(($ingreso_promedio), 2); ?></p>
                        </div>
                    </div>
                    <i class="fas fa-money-bill-alt ms-icon-mr text-white fa-sm"></i>
                </div>
            </a>
        </div>


        <div class="col-xl-4 col-md-4 col-sm-6">
            <a href="<?php echo base_url(); ?>Gastos/detalle_cuentas_gastos/<?php echo $i; ?>/<?php echo $f; ?>" target="_blank">
            <!-- <a href="#"> -->
                <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">
                    <div class="ms-card-body media">
                        <div class="media-body">
                        <h6>Gastos</h6>
                        <p class="ms-card-change">$ <?php echo number_format(($totalGastos), 2); ?></p>
                        </div>
                    </div>
                    <i class="fas fa-file-invoice-dollar ms-icon-mr text-white fa-sm"></i>
                </div>

            </a>
        </div>
    </div>
    
    <div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <?php
                    if(!isset($vacio)){
                ?>
                    <div class="ms-panel">
                        <div class="ms-panel-header">
                            <h6>Hospital últimos 30 dias</h6>
                        </div>
                        <div class="ms-panel-body">
                            <canvas id="line-chart" style="height: 350px; width: 100%;"></canvas>
                        </div>
                    </div>
                <?php } ?>
            </div>
                    
            <div class="col-xl-6 col-md-12">
                <div class="ms-panel" id="">
                    <h5 class="text-center py-3">Ingresos por categoría de exámenes </h5 class="text-center">
                    <div class="table-responsive">
                        <table class="table table-hover thead-primary" id="">
                            <thead>
                            <tr>
                                <th class="text-center" scope="col">Área</th>
                                <th class="text-center" scope="col">Ingreso</th>
                                <th class="text-center" scope="col">Ambulatorio</th>
                                <th class="text-center" scope="col">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">Laboratorio </td>
                                    <td class="text-center">$ <?php echo number_format($totalLabI, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format($totalLabA, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format(($totalLabA + $totalLabI), 2); ?></td>
                                </tr>
                                
                                <tr>
                                    <td class="text-center">Rayos X </td>
                                    <td class="text-center">$ <?php echo number_format($totalRXI, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format($totalRXA, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format(($totalRXA+$totalRXI), 2); ?></td>
                                </tr>

                                <tr>
                                    <td class="text-center">Ultras </td>
                                    <td class="text-center">$ <?php echo number_format($totalUltrasI, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format($totalUltrasA, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format(($totalUltrasA+$totalUltrasI), 2); ?></td>
                                </tr>

                                <tr>
                                    <td class="text-center"> Hemodiálisis  </td>
                                    <td class="text-center">$ <?php echo number_format($totalHemodialisisI, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format($totalHemodialisisA, 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format(($totalHemodialisisA+$totalHemodialisisI), 2); ?></td>
                                </tr>

                                <tr class="alert-primary">
                                    <td class="text-center">Totales </td>
                                    <td class="text-center">$ <?php echo number_format(($totalLabI + $totalRXI + $totalUltrasI + $totalHemodialisisI), 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format(($totalLabA + $totalRXA + $totalUltrasA + $totalHemodialisisA), 2); ?></td>
                                    <td class="text-center">$ <?php echo number_format(($totalLabI + $totalRXI + $totalUltrasI + $totalHemodialisisI) + ($totalLabA + $totalRXA + $totalUltrasA + $totalHemodialisisA), 2); ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-12">
                <div class="ms-panel" id="ingresosMeses">
                    <div class="ms-panel-header">
                        <h6>Ingresos últimos 5 meses</h6>
                    </div>
                    <div class="ms-panel-body">
                        <canvas id="bar-chart-grouped"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-6 col-md-12">
                <?php
                    if(sizeof($topMedicos) > 0){
                ?>
                    <div class="ms-panel">
                        <div class="ms-panel-header">
                            <h6>Top 5 -Ingresos por médico</h6>
                        </div>
                        <div class="ms-panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover thead-primary" id="tblTopMedicos">
                                    <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Nombre</th>
                                        <th class="text-center" scope="col">Total $</th>
                                        <th class="text-center" scope="col">Especialidad</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($topMedicos as $medico) {
                                                echo '<tr><td class=""> </i>'.$medico->nombreMedico.'</td>';

                                                $hojas = $this->Usuarios_Model->obtenerHojasMedico($i, $f, $medico->idMedico);
                                                $totalMedico = 0;
                                                foreach ($hojas as $hoja) {
                                                    if($hoja->anulada == 0){
                                                        $externosHoja = $this->Hoja_Model->externosHoja($hoja->idHoja);
                                                        $medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
                                                        /* foreach ($externosHoja as $externo) {
                                                            $totalMedico += $externo->cantidadExterno * $externo->precioExterno;
                                                        } */
                        
                                                        foreach ($medicamentosHoja as $medicamento) {
                                                            $totalMedico += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
                                                        }
                                                    }
                                                }
                                        ?>
                                                <td class="text-center"><?php echo $totalMedico; ?></td>
                                                <td class="text-center"><?php echo $medico->especialidadMedico; ?></td>
                                            </tr>
                                        <?php  $totalMedico = 0; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            <div class="col-xl-6 col-md-12">
                <?php
                    if(sizeof($topMedicamentos) > 0){
                ?>
                    <div class="ms-panel ms-panel-fh ms-widget">
                        <div class="ms-panel-header ms-panel-custome">
                            <h6>Top 5 -Medicamentos usados</h6>
                        </div>
                        <div class="ms-panel-body">
                        <div class="table-responsive">
                                <table class="table table-hover thead-primary" id="tblTopMedicos">
                                    <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Medicamento</th>
                                        <th class="text-center" scope="col">Cantidad usada</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($topMedicamentos as $medicamento) {
                                                
                                                
                                        ?>
                                        <tr>
                                            <td><?php echo $medicamento->nombreMedicamento; ?></td>
                                            <td class="text-center"><?php echo $medicamento->totalUsadas; ?></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleccionar rando de fechas-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="rangoFechas" tabindex="-1" role="dialog" awhria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Seleccione un rango de fechas</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="col-md-10 tade">
                                    <form class="needs-validation" id="frmDashboard" name="frmDashboard" method="post" action="<?php echo base_url()?>Usuarios/dashboard/" novalidate>
                                        <div class="form-row align-items-center">
                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="hojaInicio" name="hojaInicio" placeholder="Fecha de inicio" required>
                                                <div class="invalid-tooltip">
                                                    Debes agregar la fecha inicial.
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="hojaFin" name="hojaFin" placeholder="Fecha final" required>
                                                <div class="invalid-tooltip">
                                                    Debes agregar la fecha final.
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <button type="submit" class="btn btn-primary mb-2 btn-sm">&nbsp; Ir al dashboard &nbsp;<i class="fa fa-arrow-right"></i></button>
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
    </div>
<!-- Fin Modal para seleccionar rando de fechas-->

<script>
    $(document).ready(function(){
        sortTable();
    });
    function sortTable() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("tblTopMedicos");
    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
        shouldSwitch = false;
        x = rows[i].getElementsByTagName("td")[1];
        y = rows[i + 1].getElementsByTagName("td")[1];
        if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
            rows[i].parentNode.insertBefore(rows[i], rows[i]);
        }else{
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        }
        }
    }
    }
</script>

<script>
    $(document).ready(function() {
        var html = $("#bar-chart-grouped").html();
        if(html){
            $("#ingresosMeses").hide();
        }
    });
</script>

<script>
    (function($) {
    'use strict';
    
        // Grafica de barras
            var externos = JSON.parse(`<?php echo $externo_data; ?>`);
            new Chart(document.getElementById("bar-chart-grouped"), {
                type: 'bar',
                data: {
                    labels: JSON.parse(`<?php echo $meses_data; ?>`),
                    datasets: [
                        {
                        label: "Medicamentos",
                        backgroundColor: "#0b99d0",
                        data: JSON.parse(`<?php echo $medicamentos_data; ?>`)
                        }, {
                        label: "Externos",
                        backgroundColor: "#0177b5", 
                        data: JSON.parse(`<?php echo $externo_data; ?>`)
                        }
                    ]
                },
                options: {
                title: {
                    display: true,
                    text: 'Movimientos últimos 5 meses'  
                }
                }
            });


       // Grafica de lineas
            var ctx = document.getElementById('line-chart').getContext("2d");
            var gradientStroke = ctx.createLinearGradient(0, 0, 0, 450);
            gradientStroke.addColorStop(0, '#0177b5');

            var gradientFill = ctx.createLinearGradient(0, 0, 0, 450);
            gradientFill.addColorStop(0, "rgba(0, 158, 251, 0.50)");
            gradientFill.addColorStop(1, "rgba(0, 158, 251, 0.04)");

        
            var data_1 = JSON.parse(`<?php echo $mes_data; ?>`);
            var labels = JSON.parse(`<?php echo $fecha_data; ?>`);

            var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Total: $",
                    borderColor: gradientStroke,
                    pointBorderColor: gradientStroke,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    pointBorderWidth: 1,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 2,
                    fill: true,
                    backgroundColor: gradientFill,
                    borderWidth: 1,
                    data: data_1
                }]
            },
            options: {
                responsive: false,
                legend: {
                display: false,
                position: "bottom"
                },
                scales: {
                    yAxes: [{
                    ticks: {
                        fontColor: "rgba(0,0,0,0.7)",
                        fontStyle: "bold",
                        beginAtZero: true,
                        maxTicksLimit: 200,
                        padding: 20,
                    },
                    gridLines: {
                        drawTicks: false,
                        display: false
                    }

                }],
                xAxes: [{
                    gridLines: {
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        padding: 20,
                        fontColor: "rgba(0,0,0,0.7)",
                        fontStyle: "bold",
                        fontSize: 10
                    }
                }]
                }
            }
        });


    })(jQuery);
</script>