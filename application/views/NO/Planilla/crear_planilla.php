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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fas fa-flask"></i> Limpieza </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar compra</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<div class="row">
                        <div class="col-md-6"><h6>Información de la compra</h6></div>
                        <div class="col-md-6 text-right">
                            <!-- <button class="btn btn-primary mt-2" type="button" href="#agregarMedicamento" data-toggle="modal"><i class="fa fa-plus"></i> Medicamentos</button> -->
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="<?php echo base_url(); ?>Planilla/crear_planilla" novalidate>

                                <div class="form-row">
                                    <div class="col-md-12">
										<label for=""><strong>Fecha</strong></label>
										<div class="input-group">
											<input type="date" class="form-control" value="<?php echo date("Y-m-d")?>" readonly>
											<div class="invalid-tooltip">
												Ingrese la fecha.
											</div>
										</div>
									</div>
                                </div>

								<div class="form-row">

                                    <div class="col-md-4">
										<label for=""><strong>Quincena</strong></label>
										<div class="input-group">
											<select name="quincenaPlanilla" id="quincenaPlanilla" class="form-control" required>
												<option value="">.:: Seleccionar ::.</option>
												<option value="1">Primera</option>
												<option value="2">Segunda</option>
												<option value="3">Anual</option>
												<option value="4">Aguinaldo</option>
											</select>
											<div class="invalid-tooltip">
												Seleccione la quincena a cancelar.
											</div>
										</div>
									</div>

                                    <div class="col-md-4">
										<label for=""><strong>Mes</strong></label>
										<div class="input-group">
											<select name="mesPlanilla" id="mesPlanilla" class="form-control" required>
												<option value="">.:: Seleccionar ::.</option>
												<option value="Enero">Enero</option>
												<option value="Febrero">Febrero</option>
												<option value="Marzo">Marzo</option>
												<option value="Abril">Abril</option>
												<option value="Mayo">Mayo</option>
												<option value="Junio">Junio</option>
												<option value="Julio">Julio</option>
												<option value="Agosto">Agosto</option>
												<option value="Septiembre">Septiembre</option>
												<option value="Octubre">Octubre</option>
												<option value="Noviembre">Noviembre</option>
												<option value="Diciembre">Diciembre</option>
											</select>
											<div class="invalid-tooltip">
												Seleccione el Mes a pagar.
											</div>
										</div>
									</div>

									<div class="col-md-4">
										<label for=""><strong>Tipo</strong></label>
										<div class="input-group">
											<select name="tipoPlanilla" id="tipoPlanilla" class="form-control" required>
												<option value="">.:: Seleccionar ::.</option>
												<option value="1" selected>Salario</option>
												<option value="2">Indemnización </option>
												<option value="3">Aguinaldo </option>
											</select>
											<div class="invalid-tooltip">
												Seleccione el tipo de planilla.
											</div>
										</div>
									</div>

								</div>

								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Descripción</strong></label>
										<div class="input-group">
											<textarea class="form-control disableSelect" id="descripcionPlanilla" name="descripcionPlanilla" placeholder="" required></textarea>
											<div class="invalid-tooltip">
												Agregar descripción.
											</div>
										</div>
									</div>
								</div>

								<div class="text-center" id="">
                                    <input type="hidden" class="form-control" value="<?php echo date("Y-m-d")?>" id="fechaFactura" name="fechaFactura">
									<button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Siguiente <i class="fa fa-arrow-right"></i></button>
									<button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
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
		$(".controlInteligente").select2({
			theme : 'bootstrap4'
		});
	});
</script>

<script>
    $('#plazoFactura').on('change', function () {
        var valor = $(this).val();
        if(valor == 0){
            $("#estadoFactura").val("1");
        }
        else{
            $("#estadoFactura").val("1");
        }
      });
</script>