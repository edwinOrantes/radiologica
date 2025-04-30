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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fas fa-flask"></i> Laboratorio </a> </li>
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
							<form class="needs-validation" method="post" action="<?php echo base_url(); ?>InsumosLab/guardar_compra" novalidate>
								
                                <div class="form-row">
									<div class="col-md-4">
										<label for=""><strong>Tipo</strong></label>
										<div class="input-group">
											<select class="form-control" id="tipoFactura" name="tipoFactura" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="Compra de medicamentos" selected="true">Compra de medicamentos</option>
                                            </select>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									<div class="col-md-4">
										<label for=""><strong>Tipo de documento</strong></label>
										<div class="input-group">
											<select class="form-control" id="documentoFactura" name="documentoFactura" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="Crédito fiscal" selected="true">Crédito fiscal</option>
                                                <option value="Consumidor final">Consumidor final</option>
                                                <option value="Recibo">Recibo</option>
                                                <option value="Otro">Otro</option>
                                            </select>
											<div class="invalid-tooltip">
												Seleccione un tipo de documento.
											</div>
										</div>
									</div>

									<div class="col-md-4">
										<label for=""><strong>Número de documento</strong></label>
										<div class="input-group">
											<input type="text" class="form-control numeros" id="numeroFactura" name="numeroFactura" placeholder="Número del medicamento" required>
											<div class="invalid-tooltip">
												Ingrese un número de documento.
											</div>
										</div>
									</div>
								</div>

								<div class="form-row">

									<div class="col-md-4">
										<label for=""><strong>Proveedor</strong></label>
										<div class="input-group">
											<select class="form-control controlInteligente" id="idProveedor" name="idProveedor" required>
                                                <option value="">.:: Seleccionar ::.</option>

												<?php
													foreach ($proveedores as $proveedor) {
												?>

                                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->empresaProveedor; ?></option>

												<?php } ?>
                                            </select>
											<div class="invalid-tooltip">
												Seleccione un proveedor.
											</div>
										</div>
									</div>

									<div class="col-md-4">
										<label for=""><strong>Fecha</strong></label>
										<div class="input-group">
											<input type="date" class="form-control" value="<?php echo date("Y-m-d")?>" id="fechaFactura" name="fechaFactura" placeholder="Registro del medicamento" required>
											<div class="invalid-tooltip">
												Ingrese un número de documento.
											</div>
										</div>
									</div>

                                    <div class="col-md-4">
										<label for=""><strong>Plazo</strong></label>
										<div class="input-group">
											<select name="plazoFactura" id="plazoFactura" class="form-control" required>
												<option value="">.:: Seleccionar ::.</option>
												<option value="0">Contado</option>
												<option value="30" selected="true">30 dias</option>
												<option value="60">60 dias</option>
												<option value="90">90 dias</option>
											</select>
											<div class="invalid-tooltip">
												Seleccione el plazo para esta factura.
											</div>
										</div>
									</div>

								</div>

								<div class="form-row">
									<div class="col-md-12">
										<label for=""><strong>Descripción</strong></label>
										<div class="input-group">
											<textarea class="form-control disableSelect" id="descripcionFactura" name="descripcionFactura" placeholder="Descripción del proceso" required></textarea>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									<input type="hidden" value="1" id="estadoFactura" name="estadoFactura">
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