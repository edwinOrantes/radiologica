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
						<li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file"></i> Facturación  </a> </li>
						<li class="breadcrumb-item"><a href="#">Lista contingencias</a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Historial</h6></div>
							<div class="col-md-6 text-right">
									<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Facturacion/agregar_contingencia"><i class="fa fa-plus"></i> Agregar Contingencia</a>
							</div>
						</div>
					</div>

					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<?php
									if (sizeof($lista) > 0) {
								?>
								<table id="" class="table table-striped thead-primary w-100 insumos-lab">
									<thead>
										<tr>
											<th class="text-center" scope="col">#</th>
											<th class="text-center" scope="col">Fecha</th>
											<th class="text-center" scope="col">Hora</th>
											<th class="text-center" scope="col">Código</th>
											<th class="text-center" scope="col">Motivo</th>
											<th class="text-center" scope="col">Estado</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$index = 0;
										foreach ($lista as $row) {
											$index++;
											$fechaHora = new DateTime($row->creadoDocumento);

											$fecha = $fechaHora->format("Y-m-d"); // 2025-02-13
											$hora = $fechaHora->format("h:i:s A"); // 08:47:37
									?>
										<tr>
											<td class="text-center"><?php echo $index; ?></td>
											<td class="text-center"><?php echo $fecha; ?></td>
											<td class="text-center"><?php echo $hora; ?></td>
											<td class="text-center"><?php echo $row->codigoDocumento; ?></td>
											<td class="text-center"><?php echo $row->motivoDocumento; ?></td>
											<td class="text-center">
												<?php
													if($row->estadoContigencia == 1){
														echo '<span class="badge badge-danger">Pendiente</span>';
													}else{
														echo '<span class="badge badge-success">Finalizada</span>';
													}
												?>
											</td> 
											<td class="text-center">
												<?php
													echo '<a href="'.base_url().'Facturacion/detalle_contingencia/'.$row->idDocumento.'/" class="text-primary" title="Ver detalle"><i class="fa fa-eye"></i></a>';
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
