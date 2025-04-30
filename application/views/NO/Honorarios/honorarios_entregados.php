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
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrow has-gap">
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Botíquin </a> </li>
                            <li class="breadcrumb-item"><a href="#">Proveedores</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right"></div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
						<div class="col-md-12">

							<form class="needs-validation" method="post" action="<?php echo base_url(); ?>Honorarios/entregados_x_medico" target="blank" novalidate>
								<div class="row">
									<div class="col-md-12" style="margin: 0 auto;">

                                        <div class="form-row">
                                            
                                            <div class="col-md-6">
												<label for=""><strong></strong></label>
												<div class="input-group">
													<select class="form-control controlInteligente" id="idMedico" name="idMedico" required>
														<option value="">.:: Seleccionar un médico ::.</option>
														<?php 
															foreach ($externos as $externo) {
														?>
														    <option value="<?php echo $externo->idExterno; ?>"><?php echo str_replace("(Honorarios)","", $externo->nombreExterno); ?></option>
														<?php } ?>
													</select>
													<div class="invalid-tooltip">
														Seleccione un médico.
													</div>  
												</div>
											</div>
                                            
                                            <div class="text-center col-md-2">
                                                <select name="pivoteHonorario" id="pivoteHonorario" class="mt-4 form-control">
                                                    <option value="1">PDF</option>
                                                    <option value="2">Excel</option>
                                                </select>
                                            </div>

                                            <div class="text-center col-md-3">
                                                <button class="btn btn-success btn-sm mt-4" type="submit" id="generarReporte"> <i class="fa fa-file"></i> Generar</button>
                                            </div>

										</div>
                                        
                                        <div class="form-row align-items-center mt-3">
                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" placeholder="Fecha de inicio" required="">
                                                <div class="invalid-tooltip">
                                                    Debes agregar la fecha de inicio.
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="fechaFina" name="fechaFina" placeholder="Fecha de fin" required="">
                                                <div class="invalid-tooltip">
                                                    Debes agregar la fecha final.
                                                </div>
                                            </div>

                                        </div>

									</div>
								</div>                              
							</form>
						</div>
					</div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="honorariosMedico"></div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
    $(document).ready(function() {
/*         $("#generarExcel").hide();
        $("#saldarTodos").hide();
        $("#totalHonorario").hide();
        $("#pivoteHonorario").hide(); */
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
        $('.controlInteligente2').select2({
            theme: "bootstrap4",
            dropdownParent: $("#facturadosFecha")
        });
    });
</script>