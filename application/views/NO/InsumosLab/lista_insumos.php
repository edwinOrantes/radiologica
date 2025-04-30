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
						<li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Botíquin </a> </li>
						<li class="breadcrumb-item"><a href="#">Lista medicamentos</a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Listado de medicamentos</h6></div>
							<div class="col-md-6 text-right">
									<button class="btn btn-primary btn-sm" href="#agregarInsumo" data-toggle="modal"><i class="fa fa-plus"></i> Agregar Medicamento</button>
									<a href="<?php echo base_url()?>InsumosLab/insumos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
									<!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
							</div>
						</div>
					</div>

					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<?php
									if (sizeof($insumos) > 0) {
								?>
								<table id="" class="table table-striped thead-primary w-100 insumos-lab">
									<thead>
										<tr>
											<th class="text-center" scope="col">Código</th>
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Clasificación</th>
											<th class="text-center" scope="col">Precio compra</th>
											<th class="text-center" scope="col">Existencia</th>
											<th class="text-center" scope="col">Medida</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$index = 0;
										$cero = 0;
										foreach ($insumos as $insumo) {
									?>
										<tr class="">
											<td class="text-center"><?php echo $insumo->codigoInsumoLab; ?></td>
											<td class="text-center"><?php echo $insumo->nombreInsumoLab; ?></td>
											<td class="text-center"><?php echo $insumo->nombreClasificacionRI; ?></td>
											<td class="text-center"><?php echo $insumo->precioInsumoLab; ?></td>
											<td class="text-center">
												<?php
													if($insumo->stockInsumoLab == 0){
														echo '<span class="badge badge-danger">Sin stock</span>';
													}else{
														echo $insumo->stockInsumoLab;
													}
													
												?>
											</td>
											<td class="text-center"> <span class="badge badge-success"><?php echo $insumo->medidaInsumoLab; ?></span> </td>
											<td class="text-center">
												<input type="hidden" class="codigoLab" name="codigoLab" value="<?php echo $insumo->codigoInsumoLab; ?>">
												<input type="hidden" class="nombreLab" name="nombreLab" value="<?php echo $insumo->nombreInsumoLab; ?>">
												<input type="hidden" class="tipoLab" name="tipoLab" value="<?php echo $insumo->tipoInsumoLab; ?>">
												<input type="hidden" class="stockLab" name="stockLab" value="<?php echo $insumo->stockInsumoLab; ?>">
												<input type="hidden" class="medidaLab" name="medidaLab" value="<?php echo $insumo->medidaInsumoLab; ?>">
												<input type="hidden" class="idLab" name="idLab" value="<?php echo $insumo->idInsumoLab; ?>">
												<?php
													switch($this->session->userdata('nivel')) {
														case '1':
															echo "<a href='#actualizarInsumo' class='editarInsumoLab' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
															echo "<a href='#eliminarMedicamento' class='eliminarInsumoLab' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
															break;
														case '9':
															echo "<a href='#actualizarInsumo' class='editarInsumoLab' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
														break;

														
														default:
															echo "";
															break;
													}
												?>
											</td>
										</tr>

									<?php
										} // Fin del foreach
									
									?>
									</tbody>
								</table>

								<?php 
									}else{
										echo '<div class="alert alert-danger">
											<h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
										</div>';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- End body Content Wrapper -->


<!-- Modal para agregar datos del Medicamento-->
	<div class="modal fade" id="agregarInsumo" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del Insumo/Reactivo </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/guardar_insumoslab" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 mb-3">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value="<?php echo $codigoIL; ?>"  readonly>
												<input type="hidden" class="form-control" value="<?php echo $codigoIL; ?>" name="codigoIL">
												<div class="invalid-tooltip">
													Ingrese un código.
												</div>
											</div>
										</div>
																		</div>
									
									<div class="form-row">
										<div class="col-md-6 mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" name="nombreIL" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<label for=""><strong>Clasificación</strong></label>
											<div class="input-group">
											<select class="form-control controlInteligente2" name="clasificacionIL" required>
												<option value="">.:: Seleccionar::.</option>

												<?php
													foreach ($clasificacionRI as $fila) {
												?>
												<option value="<?php echo $fila->idClasificacionRI ?>"><?php echo $fila->nombreClasificacionRI; ?></option>
												<?php } ?>

											</select>
												<div class="invalid-tooltip">
													Ingrese una clasificación.
												</div>
											</div>

										</div>
									</div>

									<div class="form-row">
										<div class="col-md-6 mb-3">
											<label for=""><strong>Existencia</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value="0" name="existenciaIL" required>
												<div class="invalid-tooltip">
													Ingrese La existencia actual.
												</div>
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<label for=""><strong>Medida</strong></label>
											<div class="input-group">
												<select class="form-control controlInteligente2" name="medidaIL" required>
													<option value="">.:: Seleccionar::.</option>
													<option value="Unit.">Unit.</option>
													<option value="Set">Set</option>
													<option value="Bolsa">Bolsa</option>
													<option value="Galon">Galón</option>
													<option value="Caja">Caja</option>
													<option value="Frasco">Frasco</option>
													<option value="Vial">Vial</option>
													<option value="Paquete">Paquete</option>
												</select>
												<div class="invalid-tooltip">
													Ingrese la medida del Insumo/Reactivo.
												</div>
											</div>
										</div>
									</div>

									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar </button>
										<button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
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

<!-- Modal para actualizar datos del Medicamento-->
	<div class="modal fade" id="actualizarInsumo" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h5 class="modal-title text-white"><i class="fa fa-file"></i> Datos del Insumo/Reactivo </h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/actualizar_insumoslab" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 mb-3">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="codigoIL"  readonly>
												<div class="invalid-tooltip">
													Ingrese un código.
												</div>
											</div>
										</div>
																		</div>
									
									<div class="form-row">
										<div class="col-md-6 mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombreIL" name="nombreIL" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<label for=""><strong>Clasificación</strong></label>
											<div class="input-group">
											<select class="form-control controlInteligente2" id="clasificacionIL" name="clasificacionIL" required>
												<option value="">.:: Seleccionar::.</option>

												<?php
													foreach ($clasificacionRI as $fila) {
												?>
												<option value="<?php echo $fila->idClasificacionRI ?>"><?php echo $fila->nombreClasificacionRI; ?></option>
												<?php } ?>

											</select>
												<div class="invalid-tooltip">
													Ingrese una clasificación.
												</div>
											</div>

										</div>
									</div>

									<div class="form-row">
										<div class="col-md-6 mb-3">
											<label for=""><strong>Existencia</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="existenciaIL" name="existenciaIL" required>
												<div class="invalid-tooltip">
													Ingrese La existencia actual.
												</div>
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<label for=""><strong>Medida</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="medidaIL" name="medidaIL" required>
												<div class="invalid-tooltip">
													Ingrese la medida del Insumo/Reactivo.
												</div>
											</div>
										</div>
									</div>

									<input type="hidden" id="idIL" name="idIL">						
									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar </button>
										<button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal actualizar datos del Medicamento-->


<!-- Modal para eliminar datos del medicamento-->
	<div class="modal fade" id="eliminarMedicamento" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">

				<div class="modal-header bg-danger">
					<h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este elemento?</p>
				</div>

				<form action="<?php echo base_url()?>InsumosLab/eliminar_insumolab" method="post">
					<input type="hidden" id="idInsumoDelete" name="idInsumoDelete">									
					<div class="text-center">
						<button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
						<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin modal eliminar datos del medicamento-->


<script>
	$(document).on('click', '.editarInsumoLab', function() {
		
		var codigoLab = $(this).closest('tr').find(".codigoLab").val();
		var nombreLab = $(this).closest('tr').find(".nombreLab").val();
		var tipoLab = $(this).closest('tr').find(".tipoLab").val();
		var stockLab = $(this).closest('tr').find(".stockLab").val();
		var medidaLab = $(this).closest('tr').find(".medidaLab").val();
		var idLab = $(this).closest('tr').find(".idLab").val();

		$("#codigoIL").val(codigoLab);
		$("#nombreIL").val(nombreLab);
		$("#clasificacionIL").val(tipoLab);
		$("#existenciaIL").val(stockLab);
		$("#medidaIL").val(medidaLab);
		$("#idIL").val(idLab);


	});

	$(document).on('click', '.eliminarInsumoLab', function() {
		var idLab = $(this).closest('tr').find(".idLab").val();
		$("#idInsumoDelete").val(idLab);
	});
</script>