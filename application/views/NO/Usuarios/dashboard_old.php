

<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
            <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                <div class="ms-card-body media">
                <div class="media-body">
                    <h6>Total este mes</h6>
                    <p class="ms-card-change">$ <?php echo number_format(($totalIngresos + $totalAmbulatorio), 2); ?></p>
                </div>
                </div>
                <i class="fas fa-hand-holding-usd ms-icon-mr"></i>
            </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
            <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                <div class="ms-card-body media">
                <div class="media-body">
                    <h6>Ingresos</h6>
                    <p class="ms-card-change">$ <?php echo number_format($totalIngresos, 2); ?></p>
                </div>
                </div>
                <i class="fas fa-procedures ms-icon-mr"></i>
            </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
            <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                <div class="ms-card-body media">
                <div class="media-body">
                    <h6 class="bold">Ambulatorios</h6>
                    <p class="ms-card-change"> $ <?php echo number_format($totalAmbulatorio, 2); ?></p>
                </div>
                </div>
                <i class="fa fa-ambulance ms-icon-mr"></i>
            </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
            <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                <div class="ms-card-body media">
                <div class="media-body">
                    <h6 class="bold"> Total gastos</h6>
                    <p class="ms-card-change"> $ <?php echo number_format($totalGastos, 2); ?></p>
                </div>
                </div>
                <i class="fas fa-file-invoice-dollar ms-icon-mr"></i>
            </div>
            </a>
        </div>
    </div>
    
    <div>
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <?php
                    if(!isset($vacio)){
                ?>
                    <div class="ms-panel">
                        <div class="ms-panel-header">
                            <h6>Ingresos últimos 5 dias</h6>
                        </div>
                        <div class="ms-panel-body">
                            <canvas id="line-chart"></canvas>
                        </div>
                    </div>
                <?php } ?>
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
            <div class="col-xl-7 col-md-12">
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
            
            <div class="col-xl-5 col-md-12">
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
            gradientStroke.addColorStop(0, '#009efb');

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
                legend: {
                display: false,
                position: "bottom"
                },
                scales: {
                    yAxes: [{
                    ticks: {
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold",
                        beginAtZero: true,
                        maxTicksLimit: 200,
                        padding: 20
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
                        fontColor: "rgba(0,0,0,0.5)",
                        fontStyle: "bold"
                    }
                }]
                }
            }
        });


    })(jQuery);
</script>