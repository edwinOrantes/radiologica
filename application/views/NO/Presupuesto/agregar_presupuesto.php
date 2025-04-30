
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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-invoice-dollar"></i> Cotización </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar cotización</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<h6> Información de la cotización</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>Hoja/guardar_presupuesto" novalidate>
								<div class="row">
									<div class="col-md-12">
										
                                        <div class="form-row">
                                            <div class="col-md-12">
												<label for=""><strong>Código cotización</strong></label>
												<div class="input-group">
                                                    <input type="text" class="form-control" value="<?php echo $codigo; ?>" id="codigoPresupuesto" readonly/>
                                                    <input type="hidden" class="form-control" value="<?php echo $codigo; ?>" name="codigoPresupuesto"/>
													<div class="invalid-tooltip">
														Seleccione un tipo de documento.
													</div>
												</div>
											</div>
										</div>

                                        <div class="form-row">

                                            <div class="col-md-6">
												<label for=""><strong>Nombre del paciente</strong></label>
												<div class="input-group">
													<!-- <input type="text" class="form-control" id="nombrePaciente" placeholder="Nombre del paciente" readonly required> -->
													<input type="text" class="form-control" id="pacientePresupuesto" name="pacientePresupuesto" placeholder="Nombre del paciente" required>
													<!-- <div class="input-group-append">
														<a class="btn btn-primary btn-sm" href="" data-toggle="modal" data-target="#agregarPaciente"><i class="fa fa-search"></i></a>
													</div> -->
                                                    <div class="invalid-tooltip">
														Debes agregar el nombre del paciente.
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<label for=""><strong>Fecha</strong></label>
												<div class="input-group">
												<input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaPresupuesto" name="fechaPresupuesto" placeholder="Fecha del ingreso" required>
													<div class="invalid-tooltip">
														Seleccione un tipo de documento.
													</div>
												</div>
											</div>
											
										</div>

										<div class="form-row mb-5">

                                            <div class="col-md-6">
												<label for=""><strong>Tipo</strong></label>
												<div class="input-group">
													<select class="form-control" id="tipoPresupuesto" name="tipoPresupuesto" required>
														<option value="">.:: Seleccionar ::.</option>
														<option value="Ingreso">Ingreso</option>
														<option value="Ambulatorio">Ambulatorio</option>
														<!--<option value="Otro">Otro</option>-->
													</select>
													<div class="invalid-tooltip">
														Seleccione un tipo de hoja.
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<label for=""><strong>Médico</strong></label>
												<div class="input-group">
													<select class="form-control controlInteligente" id="medicoPresupuesto" name="medicoPresupuesto" required>
														<option value="">.:: Seleccionar ::.</option>
														<?php 
															foreach ($medicos as $medico) {
																?>
														
														<option value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
														
														<?php } ?>
													</select>
													<div class="invalid-tooltip">
														Seleccione un médico.
													</div>
												</div>
											</div>

										</div>

									</div>

								</div>
								<div class="text-center" id="">
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

	function agregarPaciente(id, nombre){
		$("#nombrePaciente").attr("value", nombre);
		$("#idPaciente").attr("value", id);
		console.log(nombre);
	}

    $(document).ready(function() {

		$(".controlInteligente").select2({
			theme: 'bootstrap4'
		});


        var edad = $(this).val();
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = (now.getFullYear() - edad)+"-"+(month)+"-"+(day) ;
        $("#fechaIngreso").val(today);
    });

</script>
