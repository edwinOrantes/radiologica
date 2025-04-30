<?php if($this->session->flashdata("exito")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.success('<?php echo $this->session->flashdata("exito")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>

<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.error('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>

<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap">
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-invoice-dollar"></i> Herramientas </a> </li>
                        <li class="breadcrumb-item"><a href="#">Detalle de facturación diaria y mensual.</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="d-flex justify-content-between">
                            <div class="ms-header-text">
                                <button class="btn btn-outline-success bold">Total a facturar en el mes: <?php echo "$".number_format(($totalIngresos * 0.80), 2); ?></button>
                            </div>
                            <a href="#reporteFacturas" class="btn btn-outline-success btn-sm" data-toggle="modal"><i class="fa fa-file-excel"></i> Detalle</a>
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="col-md-12">
                            <div class="ms-panel">
                                
                                <div class="ms-panel-body pb-0">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="ms-card card-twitter ms-widget ms-infographics-widget">
                                                <div class="ms-card-body media">
                                                    <div class="media-body">
                                                        <h6>Este dia</h6>
                                                        <p class="ms-card-change"> <i class="material-icons">arrow_upward</i> $ <?php echo number_format($diario, 2); ?> </p>
                                                        <br>
                                                    </div>
                                                </div>
                                                <i class="fa fa-money-check-alt"></i>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="ms-card card-linkedin ms-widget ms-infographics-widget">
                                                <div class="ms-card-body media">
                                                    <div class="media-body">
                                                        <h6>Este mes</h6>
                                                        <p class="ms-card-change"> <i class="material-icons">arrow_upward</i> $ <?php echo number_format($mes, 2); ?> </p>
                                                        <br>
                                                    </div>
                                                </div>
                                                <i class="fa fa-money-check-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="ms-panel">
                                                <div class="ms-panel-header">
                                                    <h6>Facturación ultimos 30 dias</h6>
                                                </div>
                                                <div class="ms-panel-body">
                                                    <canvas id="bar-chart-grouped" style="height: 350px; width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Fin Body -->


<!-- Modales -->
<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="reporteFacturas" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white"></i> Selecciones fechas</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">

							<form class="needs-validation" id="frmReporteFactura"  method="post" action="<?php echo base_url() ?>Herramientas/reporte_facturas"  novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Inicio</strong></label>
										<div class="input-group">
											<input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                                            <div class="invalid-tooltip">
                                                Selecciones fecha de inicio.
                                            </div>
										</div>
									</div>
                                    
								</div>
                                
								<div class="form-row">

									<div class="col-md-12">
										<label for=""><strong>Fin</strong></label>
										<div class="input-group">
											<input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                                            <div class="invalid-tooltip">
                                                Ingrese la fecha final.
                                            </div>
										</div>
									</div>

								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Generar </button>
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
<!-- Fin Modales -->


<script>
    (function($) {
    'use strict';
    
        // Grafica de barras
            new Chart(document.getElementById("bar-chart-grouped"), {
                type: 'bar',
                data: {
                    labels: JSON.parse(`<?php echo $fecha_factura; ?>`),
                    datasets: [
                        {
                        label: "Medicamentos",
                        backgroundColor: "#0b99d0",
                        data: JSON.parse(`<?php echo $totales_factura; ?>`)
                        }
                    ]
                },
                options: {
                title: {
                    display: true,
                    text: 'Movimientos últimos 30 dias'  
                }
                }
            });


    })(jQuery);
</script>