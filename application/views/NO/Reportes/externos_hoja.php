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
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Reportes</a> </li>
                    <li class="breadcrumb-item active"><a href="#">Detalle de externos por hoja</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">
                        <?php
                            if($this->session->userdata('id_usuario_h') != 8){
                        ?>
                        <div class="col-md-6">
                            <h6>Rango de hojas para generar el detalle de los externos</h6>
                        </div>
                        
                        <div class="col-md-6 text-right">
                            <strong> Generar recibos </strong>
                            <label class="ms-switch">
                            <input type="checkbox" id="pivoteRecibos" value="recibos" name="recibos">
                                <span class="ms-switch-slider round"></span>
                            </label>
                        </div>

                        <?php
                            }
                        ?>
                    
                    </div>
                </div>

                <div class="ms-panel-body">
                        <div class="col-md-8 tade">
							<div id="generarExternos">
                                <form class="needs-validation" id="reporteExternos" name="reporteExternos" method="post" action="<?php echo base_url()?>Reportes/externos_por_hoja" novalidate>
                                    <div class="form-row align-items-center">
                                        <div class="col-md-4">
                                            <input type="number" class="form-control numeros" value="<?php echo $inicio; ?>" min="<?php echo $inicio; ?>" max="<?php echo $numeroLimite; ?>" id="hojaInicio" name="hojaInicio" placeholder="Número correlativo de inicio" required>
                                            <div class="invalid-tooltip">
                                                Debes agregar el numero inicial.
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <input type="number" value="<?php echo $numeroLimite; ?>" min="<?php echo $inicio; ?>" max="<?php echo $numeroLimite; ?>" class="form-control numeros" id="hojaFin" name="hojaFin" placeholder="Número correlativo de fin" required>
                                            <div class="invalid-tooltip">
                                                Debes agregar el numero final.
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-2">
                                            <button type="submit" class="btn btn-success mb-2"><i class="fa fa-file-excel"></i>&nbsp; Generar Externos &nbsp;</button>
                                            <!-- <button type="button" id="generarGastos" class="btn btn-success mb-2"><i class="fa fa-hand-holding-usd"></i> Generar Gastos</button> -->
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div id="generarRecibos">
                                <form class="needs-validation" id="" name="generarDetalleCobros" method="post" action="<?php echo base_url()?>Reportes/generar_cobros" target="_blank" novalidate>
                                    <div class="form-row align-items-center">
                                        <div class="col-md-3">
                                            <input type="number" class="form-control numeros" value="<?php echo $inicio; ?>" min="<?php echo $inicio; ?>" max="<?php echo $numeroLimite; ?>" id="hojaInicio2" name="hojaInicio" placeholder="Número correlativo de inicio" required>
                                            <div class="invalid-tooltip">
                                                Debes agregar el numero inicial.
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="number" value="<?php echo $numeroLimite; ?>" min="<?php echo $inicio; ?>" max="<?php echo $numeroLimite; ?>" class="form-control numeros" id="hojaFin2" name="hojaFin" placeholder="Número correlativo de fin" required>
                                            <div class="invalid-tooltip">
                                                Debes agregar el numero final.
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="fechaGenerado" name="fechaGenerado" required>
                                            <div class="invalid-tooltip">
                                                Debes agregar la fecha.
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <!-- <button type="submit" class="btn btn-success mb-2"><i class="fa fa-file-pdf"></i>&nbsp; Generar Recibos &nbsp;</button>  Boton actual -->
                                            <button type="button" id="btnGenerarRecibos" class="btn btn-success mb-2"><i class="fa fa-file-pdf"></i> Generar Recibos</button>
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

<!-- Modal para corte diario -->
    <div class="modal fade" id="mdlDatosCorte" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">

				<div class="modal-header bg-primary">
					<h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>
                <form action="<?php echo base_url()?>Reportes/generar_cobros/" method="post">
                    <div class="modal-body text-center">
                        <p class="h5">¿Los datos del corte para la fecha <span id="lblFechaCorte"></span> son los correcto?</p>
                        <div>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-center"><strong>Inicio</strong></td>
                                    <td><span id="tblInicioCorte"></span></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><strong>Fin</strong></td>
                                    <td><span id="tblFinCorte"></span></td>
                                </tr>
                            </table>
                        </div>
    
                        <div>
                            <input type="text" class="form-control" name="codigoVerificacion" placeholder="Ingrese el código de verificación" required>
                        </div>
                    </div>

					<div class="text-center">
                        <input type="hidden" id="inicioCorte" name="inicioCorte">								
                        <input type="hidden" id="finCorte" name="finCorte">						
                        <input type="hidden" id="fechaCorte" name="fechaCorte">						
						<button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-trash"></i> Generar</button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin Modal corte diario -->


<script>
    $(document).on('click', '#generarGastos', function(event) {
            event.preventDefault();
            var inicio = $("#hojaInicio").val();
            var fin = $("#hojaFin").val();
            if(inicio != "" && fin != ""){
                $("#hojaInicio2").val(inicio);
                $("#hojaFin2").val(fin);
                $("#generarDetalleCobros").submit();
            }else{
                toastr.remove();
                toastr.options.positionClass = "toast-top-center";
                toastr.warning('Debes de llenar todos los campos', 'Aviso!');
                $("#hojaInicio").focus();
            }
    });

    $(document).on('click', '#btnGenerarRecibos', function(event) {
            // event.preventDefault();
            $("#lblFechaCorte").html($("#fechaGenerado").val());
            $("#inicioCorte").val($("#hojaInicio2").val());
            $("#finCorte").val($("#hojaFin2").val());
            $("#tblInicioCorte").html($("#hojaInicio2").val());
            $("#tblFinCorte").html($("#hojaFin2").val());
            $("#fechaCorte").val($("#fechaGenerado").val());
            $("#mdlDatosCorte").modal("show");
    });
</script>

<script>
    $(document).ready(function() {
        $("#pivoteRecibos").click(function() {
            var valor = $('input:checkbox[name=recibos]:checked').val();
            if (valor == "recibos") {
                $("#generarExternos").hide();
                $("#generarRecibos").show();
            } else {
                $("#generarRecibos").hide();
                $("#generarExternos").show();
            }
        });
    });
</script>