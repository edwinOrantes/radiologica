<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.error('<?php echo $this->session->flashdata("error")?>', 'Advertencia!');
    });
  </script>
<?php endif; ?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-xl-12 col-md-12">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Cajeras</a> </li>
                    <li class="breadcrumb-item active"><a href="#">Corte diario</a></li>
                </ol>
            </nav>
			<div class="ms-panel">
				<div class="ms-panel-header">
					<h6>Ingrese el rango de numeros que desea</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-8 tade">
							<form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url()?>Cajeras/guardar_corte" target="_blank" novalidate>
								<div class="form-row align-items-center">
									<div class="col-md-3">
										<select id="usuarioActual" name="usuarioActual" class="form-control" required>
											<option value="<?php echo $this->session->userdata('id_usuario_h'); ?>"><?php echo $this->session->userdata('empleado_h'); ?></option>
										</select>
										<div class="invalid-tooltip">
											Debes seleccionar la cajera.
										</div>
									</div>

                                    <div class="col-md-3">
										<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="fechaCorte" name="fechaCorte" required>
										<div class="invalid-tooltip">
											Debes agregar la fecha de corte.
										</div>
									</div>

									<div class="col-md-3">
										<select id="turnoActual" name="turnoActual" class="form-control" required>
											<option value="0">.:: Seleccionar ::.</option>
											<option value="Dia">Dia</option>
											<option value="Noche">Noche</option>
											<option value="24 horas">24 horas</option>
										</select>
										<div class="invalid-tooltip">
											Debes seleccionar el turno.
										</div>
									</div>

									<div class="col-md-3">
									    <button type="submit" class="btn btn-success mb-2" id="btnCorte" style="display: none"><i class="fa fa-file-pdf"></i> Generar</button>
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
	$(document).on('change', '#turnoActual', function() {
		var pivote = $("#turnoActual").val();
		if(pivote == 0){
			$("#btnCorte").hide();
		}else{
			$("#btnCorte").show();
		}
	});

	/* $(document).on('click', '#btnCorte', function() {
		var datos = {
			usuarioActual : $("#usuarioActual").val(),
			fechaCorte : $("#fechaCorte").val(),
			turnoActual : $("#turnoActual").val()
		}
		console.log(datos);
	}); */
</script>