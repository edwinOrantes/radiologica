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
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Herramientas/resumen_diario">Detalle de facturación diaria.</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="d-flex justify-content-between">

                            <div class="col-md-8">
                                <form action="<?php echo base_url(); ?>Herramientas/resumen_diario" method="POST">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="number" name="primerSalida" value="<?php echo $fin+1; ?>" id="primerSalida" placeholder="Primer correlativo salida" class="form-control" required>
                                                <input type="number" name="ultimaSalida" value="<?php echo $ultimoCodigo; ?>" id="ultimaSalida" placeholder="Ultimo correlativo salida" class="form-control" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el correlativo de salida.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <button class="btn btn-primary">Obtener <i class="fa fa-arrow-right"></i></button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#reporteFacturas" class="btn btn-success btn-sm" data-toggle="modal"><i class="fa fa-file-excel"></i> Detalle</a>
                                <button class="btn btn-outline-success btn-sm" id="imprimirDetalle"><i class="fa fa-print"></i> Imprimir detalle </button>
                            </div>
                                
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="col-md-12">
                            <div class="ms-panel">
                                
                                <div class="ms-panel-body pb-0">
                                    <h5 class="text-center">Inicia desde: <?php echo ($fin+1)." hasta ".$ultimoCodigo; ?> </h5>
                                    <div class="row">

                                        <div class="col-xl-3 col-md-6">
                                            <div class="ms-card card-twitter ms-widget ms-infographics-widget">
                                                <div class="ms-card-body media">
                                                    <div class="media-body">
                                                        <h6>Ingresos</h6>
                                                        <p class="ms-card-change">$ <?php echo number_format($totalIngresos, 2); ?> </p>
                                                        <br>
                                                    </div>
                                                </div>
                                                <i class="fa fa-money-check-alt"></i>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="ms-card card-linkedin ms-widget ms-infographics-widget">
                                                <div class="ms-card-body media">
                                                    <div class="media-body">
                                                        <h6>Monto facturado </h6>
                                                        <p class="ms-card-change">  $ <?php echo number_format($totalFacturado, 2); ?> </p>
                                                        <br>
                                                    </div>
                                                </div>
                                                <i class="fa fa-money-check-alt"></i>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="ms-card card-linkedin ms-widget ms-infographics-widget">
                                                <div class="ms-card-body media">
                                                    <div class="media-body">
                                                        <h6>Diferencia </h6>
                                                        <p class="ms-card-change"> $ <?php echo number_format(($totalIngresos - $totalFacturado), 2); ?> </p>
                                                        <br>
                                                    </div>
                                                </div>
                                                <i class="fa fa-money-check-alt"></i>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="ms-card card-linkedin ms-widget ms-infographics-widget">
                                                <div class="ms-card-body media">
                                                    <div class="media-body">
                                                        <h6>Monto a facturar</h6>
                                                        <p class="ms-card-change"> $ <?php echo number_format($facturar, 2); ?> </p>
                                                        <br>
                                                    </div>
                                                </div>
                                                <i class="fa fa-money-check-alt"></i>
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

<div style="display: none;" id="tablaDetalle">
    <br><br><br>
    <div class="cabecera" style="font-family: Times New Roman; text-align: center">
        <div class="img_cabecera"><img src="<?php echo base_url(); ?>public/img/logo.jpg" width=250></div>
    </div>
    <hr>
    <br>
    <table style="width: 100%">
        <tr>
            <td><strong>Generado por:</strong></td>
            <td style="text-align: left"><?php echo $this->session->userdata('empleado_h'); ?></td>
            <td><strong>Fecha:</strong></td>
            <td style="text-align: left"><?php echo date('Y-m-d h:i A'); ?></td>
        </tr>
        
        <tr>
            <td><strong>Desde:</strong></td>
            <td style="text-align: left"><?php echo ($fin+1); ?> </td>
            <td><strong>Hasta:</strong></td>
            <td style="text-align: left"><?php echo $ultimoCodigo; ?> </td>
        </tr>
    </table>
    <br><br>
    
    <!-- <h5 style="font-size: 16px; font-weight: bold; text-align: center;">Desde: <?php echo ($fin+1)."-------------------------- Hasta: ".$ultimoCodigo; ?> </h5> -->
    <div class="row" style="border: 1px solid #000; text-align: center;">

        <div class="col-xl-3 col-md-6" style="border-bottom: 1px solid #000;">
            <div class="ms-card card-twitter ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6 style="font-size: 16px; font-weight: bold;">Ingresos</h6>
                        <p style="margin-top: -25px; font-size: 16px; font-weight: bold;">$ <?php echo number_format($totalIngresos, 2); ?> </p>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6" style="border-bottom: 1px solid #000;">
            <div class="ms-card card-linkedin ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6 style="font-size: 16px; font-weight: bold;">Monto facturado </h6>
                        <p style="margin-top: -25px; font-size: 16px; font-weight: bold;">  $ <?php echo number_format($totalFacturado, 2); ?> </p>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="ms-card card-linkedin ms-widget ms-infographics-widget">
                <div class="ms-card-body media">
                    <div class="media-body">
                        <h6 style="font-size: 16px; font-weight: bold;">Diferencia </h6>
                        <p style="margin-top: -25px; font-size: 16px; font-weight: bold;"> $ <?php echo number_format(($totalIngresos - $totalFacturado), 2); ?> </p>
                        <br>
                    </div>
                </div>
                <i class="fa fa-money-check-alt"></i>
            </div>
        </div>
    </div>
    
</div>

<!-- Modales -->
    <!-- Modal para agregar datos del Medicamento-->
        <div class="modal fade" id="reporteFacturas" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white"></i> Ingreso los correlativos respectivos</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">

                                    <form class="needs-validation" id="frmReporteFactura"  method="post" action="<?php echo base_url() ?>Herramientas/facturas_x_dia"  novalidate>
                                        
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <label for=""><strong>Inicio</strong></label>
                                                <div class="input-group">
                                                    <input type="number" value="<?php echo $fin+1; ?>" class="form-control" id="fechaInicio" name="codigoInicio" placeholder="Inicio" required>
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
                                                    <input type="number" value="<?php echo $ultimoCodigo; ?>" class="form-control" id="fechaFin" name="codigoFin" placeholder="Fin" required>
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
    $(document).on("click", "#imprimirDetalle", function() {
        var elemento=document.getElementById('tablaDetalle');
        var pantalla=window.open(' ','popimpr');

        pantalla.document.write('<html><head><title>' + document.title + '</title>');
        pantalla.document.write('<link href="<?= base_url() ?>public/css/factura_consumidor_final.css" rel="stylesheet" /></head><body>');
        pantalla.document.write(elemento.innerHTML);
        pantalla.document.write('</body></html>');
        pantalla.document.close();
        pantalla.focus();
        pantalla.onload = function() {
            pantalla.print();
            pantalla.close();
        };
    });
</script>