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
                    <li class="breadcrumb-item"><a href="#">Gastos</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <h6>Seleccione el rango de fecha</h6>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
						<div class="col-md-8 tade">
							<form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url()?>Test/generar_externos" target="_blank" novalidate>
								<div class="form-row align-items-center">
									<div class="col-md-4">
										<label class="" for=""><strong>Inicio</strong></label>
										<input type="number" class="form-control mb-2" id="reciboInicio" name="reciboInicio" placeholder="Seleccione el recibo de inicio" required>
										<div class="invalid-tooltip">
											Debes seleccionar el recibo de inicio.
										</div>
									</div>
									<div class="col-md-4">
										<label class="" for=""><strong>Fin</strong></label>
										<div class="input-group mb-2">
											<input type="number" class="form-control" id="reciboFin" name="reciboFin" placeholder="Seleccione el recibo final" required>
											<div class="invalid-tooltip">
												Debes seleccionar el recibo final.
											</div>
										</div>
									</div>
									<div class="col-md-4 mt-3">
										<button type="submit" class="btn btn-success mb-2"><i class="fa fa-file-pdf"></i> Generar</button>
									</div>
								</div>
							</form>
						</div>
					</div>-
				</div>
			</div>
		</div>
	</div>
</div>

