<div class="ms-content-wrapper">
    <div class="row">

        <div class="col-xl-3 col-md-3 col-sm-6 bailarin">
            <div class="ms-card card-gradient-os ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6>Total este mes</h6>
                        <p class="ms-card-change"> $ <?php echo number_format($totalLab, 2); ?> </p>
                    </div>
                </div>
                <i class="flaticon-stats"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-6 bailarin">
            <div class="ms-card card-gradient-os ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6>Total examenes realizados</h6>
                        <p class="ms-card-change"> <?php echo $totalExamenes; ?> </p>
                    </div>
                </div>
                <i class="flaticon-statistics"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-6 bailarin">
            <div class="ms-card card-gradient-os ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6>Examenes hoy</h6>
                        <p class="ms-card-change"> <?php echo $totalH; ?> </p>
                    </div>
                </div>
                <i class="flaticon-statistics"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-6 bailarin">
            <div class="ms-card card-gradient-os ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6>Examenes realizados</h6>
                        <p class="ms-card-change">
                            <a href="<?php echo base_url(); ?>Laboratorio/detalle_examenes_excel" target="blank" class="btn btn-success btn-sm"> Este mes </a>
                            <a href="#rangoFecha" data-toggle="modal" class="btn btn-success btn-sm"> Por fecha </a>
                        </p>
                    </div>
                </div>
                <i class="flaticon-excel"></i>
            </div>
        </div>

    </div>
    
    <div class="row" style="display: none;">
        <div class="col-xl-12 col-md-12">
            <div class="ms-panel">
                <div class="ms-panel-header">
                    <h6>Examenes durante los últimos 30 dias</h6>
                </div>
                <div class="ms-panel-body">
                    <canvas id="grafica_examenes" style="height: 350px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="ms-panel">
                <div class="ms-panel-header">
                    <h6>Reactivos en uso</h6>
                </div>

                <div class="ms-panel-body">
                    <div class="table-responsive mt-3">
                        <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">Código</th>
                                    <th class="text-center" scope="col">Nombre</th>
                                    <th class="text-center" scope="col">Desde</th>
                                    <th class="text-center" scope="col">Dias transcurridos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($reactivosEU as $row) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $row->codigoInsumoLab; ?></td>
                                        <td class="text-center"><?php echo $row->nombreInsumoLab; ?></td>
                                        <td class="text-center"><?php echo $row->fechaInicio; ?></td>
                                        <td class="text-center"><?php echo $row->diasTranscurridos; ?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>

<!-- Modal para seleccionar rando de fechas-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="rangoFecha" tabindex="-1" role="dialog" awhria-hidden="true">
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
                                    <form class="needs-validation" id="reporteExternos" name="reporteExternos" method="post" action="<?php echo base_url()?>Laboratorio/examenes_por_fecha_excel" novalidate>
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
                                                <button type="submit" class="btn btn-success mb-2 btn-sm"><i class="fa fa-file-excel"></i>&nbsp; Generar Reporte &nbsp;</button>
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


<!-- <script>
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
</script> -->

<script>
    (function($) {
    'use strict';
    
        // Grafica de barras
            /* var externos = JSON.parse(`<?php echo $externo_data; ?>`);
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
            }); */


       // Grafica de lineas
            var ctx = document.getElementById('grafica_examenes').getContext("2d");
            var gradientStroke = ctx.createLinearGradient(0, 0, 0, 450);
            gradientStroke.addColorStop(0, '#0177b5');

            var gradientFill = ctx.createLinearGradient(0, 0, 0, 450);
            gradientFill.addColorStop(0, "rgba(0, 158, 251, 0.50)");
            gradientFill.addColorStop(1, "rgba(0, 158, 251, 0.04)");

        
            var data_1 = JSON.parse(`<?php echo $examenes_data; ?>`);
            var labels = JSON.parse(`<?php echo $fecha_data; ?>`);

            var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Total: ",
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
                    position: "bottom",
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