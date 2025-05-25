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
						<li class="breadcrumb-item"><a href="#">Lista de facturas</a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Historial</h6></div>
							<div class="col-md-6 text-right">
									<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Facturacion/agregar_consumidor_final/"><i class="fa fa-plus"></i> Agregar factura</a>
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
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Fecha</th>
											<th class="text-center" scope="col">DTE</th>
											<th class="text-center" scope="col">Sello</th>
											<th class="text-center" scope="col">Estado</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach ($lista as $row) {
                                            $jsonDTE = unserialize(base64_decode(urldecode($row->jsonDTE)));
                                            $respuestaHacienda = !empty($row->respuestaHacienda) ? unserialize(base64_decode(urldecode($row->respuestaHacienda))) : null;
									?>
										<tr class="">
											<td class="text-center"><?php echo $jsonDTE["dteJson"]["identificacion"]["fecEmi"]; ?></td>
											<td class="text-center"><?php echo $jsonDTE["dteJson"]["receptor"]["nombre"]; ?></td>
											<td class="text-center"><?php echo $jsonDTE["dteJson"]["identificacion"]["numeroControl"]; ?></td>
											<td class="text-center"> <?php echo !empty($row->respuestaHacienda) ? $respuestaHacienda->selloRecibido : null; ?> </td>
											<td class="text-center"> 
												<?php 
													if($row->estadoDTE == 0){
														echo '<span class="badge badge-danger">ANULADO</span>';
													}else{
														echo '<span class="badge badge-success">ACTIVO</span>';
													}
												?> 
											</td> 

											<td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                                        Opciones 
                                                    </button>
                                                    <ul class="dropdown-menu" x-placement="bottom-start">
														<li><a class="dropdown-item" target="blank" href="<?php echo base_url(); ?>Facturacion/fin_factura_electronica/<?php echo $row->idDTEFC; ?>/V/">Ver</a></li>
														<?php
															if($row->estadoDTE == 1){
														?>
														<li><a class="dropdown-item" href="<?php echo base_url(); ?>Facturacion/anular_factura/<?php echo $row->idDTEFC; ?>/">Anular</a></li>
														<?php
															}
														?>
														
                                                    </ul>
                                                </div>
												<?php
													if($row->enContingencia == 1){
														echo '<a href="'.base_url().'Facturacion/factura_en_contingencia/'.$row->idDTEFC.'/" target="_blank" class="text-primary" title="Enviar documento"> <i class="fa fa-check"> </i></a>';
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
	<div class="modal fade" id="anularFactura" tabindex="-1" role="dialog" aria-hidden="true">
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
