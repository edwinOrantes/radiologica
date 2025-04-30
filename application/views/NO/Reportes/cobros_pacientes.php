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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Reportes </a> </li>
                    <li class="breadcrumb-item"><a href="#">Cobros a pacientes</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
					<div class="row">
						<div class="col-md-6"><h6>Seleccione el rango de fecha</h6></div>
					</div>
                    
					<div class="col-md-6 text-right">
						<span> Por médico </span>
						<label class="ms-checkbox-wrap">
							<input class="form-check-input" type="checkbox" id="newMed" value="newMed" name="newMed">
							<i class="ms-checkbox-check"></i>
						</label>
					</div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
						
						<div class="col-md-8 tade" id="porFecha">
							<form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url()?>Reportes/generar_cobros_pacientes" target="_blank" novalidate>
								<div class="form-row align-items-center">
									<div class="col-md-3">
										<label class="" for=""><strong>Fecha Inicio</strong></label>
										<input type="date" class="form-control mb-2" id="fechaInicio" name="fechaInicio" placeholder="Seleccione la fecha de inicio" required>
										<div class="invalid-tooltip">
											Debes seleccionar la fecha de inicio.
										</div>
									</div>
									<div class="col-md-3">
										<label class="" for=""><strong>Fecha Fin</strong></label>
										<div class="input-group mb-2">
											<input type="date" class="form-control" id="fechaFin" name="fechaFin" placeholder="Seleccione la fecha de inicio" required>
											<div class="invalid-tooltip">
												Debes seleccionar la fecha final.
											</div>
										</div>
									</div>
                                    <div class="col-md-3">
										<label class="" for=""><strong>Tipo consulta</strong></label>
										<div class="input-group mb-2">
											<select name="tipoConsulta" id="tipoConsulta" class="form-control">
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="1"> Ingresos </option>
                                                <option value="2"> Ambulatorios</option>
                                                <option value="3"> Todos </option>
                                                <option value="4"> Pendientes </option>
												<option value="5"> Saldadas </option>
												<option value="6"> El Tercio </option>
												<option value="7"> Abank </option>
												<option value="8"> Mapfre </option>
												<option value="9"> Mi Red </option>
												<option value="10"> Paligmed </option>
												<option value="11"> RPN </option>
												<option value="12"> ISSS </option>
                                            </select>
											<div class="invalid-tooltip">
												Debes seleccionar la fecha final.
											</div>
										</div>
									</div>
									<div class="col-md-3 mt-4">
									<button type="submit" class="btn btn-success mb-2"><i class="fa fa-file-pdf"></i> Generar</button>
									</div>
								</div>
							</form>
						</div>

						<div class="col-md-12 tade" id="porMedico">
							<form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url()?>Reportes/generar_cobros_pacientesm" target="_blank" novalidate>
								<div class="form-row align-items-center">
									
									<div class="col-md-2">
										<label class="" for=""><strong>Fecha Inicio</strong></label>
										<input type="date" class="form-control mb-2" id="fechaInicio" name="fechaInicio" placeholder="Seleccione la fecha de inicio" required>
										<div class="invalid-tooltip">
											Debes seleccionar la fecha de inicio.
										</div>
									</div>
									<div class="col-md-2">
										<label class="" for=""><strong>Fecha Fin</strong></label>
										<div class="input-group mb-2">
											<input type="date" class="form-control" id="fechaFin" name="fechaFin" placeholder="Seleccione la fecha de inicio" required>
											<div class="invalid-tooltip">
												Debes seleccionar la fecha final.
											</div>
										</div>
									</div>

									<div class="col-md-3">
										<label class="" for=""><strong>Médico</strong></label>
										<div class="input-group mb-2">

											<select class="form-control controlInteligente" id="idMedico" name="idMedico" required>
												<option value="">.:: Seleccionar ::.</option>
												<?php
													foreach ($medicos as $medico) {
												?>
													<option value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
												<?php } ?>
											</select>

											<div class="invalid-tooltip">
												Debes seleccionar la fecha final.
											</div>
										</div>
									</div>
                                    <div class="col-md-3">
										<label class="" for=""><strong>Tipo consulta</strong></label>
										<div class="input-group mb-2">
											<select name="tipoConsulta" id="tipoConsulta" class="form-control">
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="1"> Ingresos </option>
                                                <option value="2"> Ambulatorios</option>
                                                <option value="3"> Todos </option>
                                                <option value="4"> Pendientes </option>
                                                <option value="5"> Saldadas </option>
                                            </select>
											<div class="invalid-tooltip">
												Debes seleccionar la fecha final.
											</div>
										</div>
									</div>
									<div class="col-md-2 mt-4">
									<button type="submit" class="btn btn-success mb-2"><i class="fa fa-file-pdf"></i> Generar</button>
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
		$(".controlInteligente").select2({
			theme: 'bootstrap4'
		});

		$("#newMed").click(function() {
			var valor = $('input:checkbox[name=newMed]:checked').val();
			if (valor == "newMed") {
				$("#porMedico").fadeIn();
				$("#porFecha").hide();
			} else {
				$("#porMedico").hide();
				$("#porFecha").fadeIn();
			}
		});
	});

</script>