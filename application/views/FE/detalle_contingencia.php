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
						<li class="breadcrumb-item"><a href="#">Detalle de la contingencia</a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Detalle</h6></div>
							<div class="col-md-6 text-right">
									<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Facturacion/lista_contingencias/"><i class="fa fa-arrow-left"></i> Volver </a>
							</div>
						</div>
					</div>

					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<p class="h6"> <strong>Código:</strong> <span><?php echo $detalle->codigoDocumento; ?></span></p>
								<p class="h6"> <strong>Motivo:</strong> <span><?php echo $detalle->motivoDocumento; ?></span></p>
								<table id="" class="table table-striped thead-primary w-100 insumos-lab">
									<thead>
										<tr>
											<th class="text-center" scope="col">#</th>
											<th class="text-center" scope="col">Documento</th>
											<th class="text-center" scope="col">Tipo</th>
											<th class="text-center" scope="col">Opcion</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$jsonDTE = unserialize(base64_decode(urldecode($detalle->textoDocumento)));
										$documentos = $jsonDTE["dteJson"]["detalleDTE"];
										$dte = array();
										$strTipo = "";
										foreach ($documentos as $row) {
											$dte["codigo"] = $row["codigoGeneracion"];
											switch ($row["tipoDoc"]) {
												case '01':
													$strTipo = '<span class="badge badge-primary">Consumidor Final</span>';
													$dte["tipo"] = $row["tipoDoc"];
													break;
												case '03':
													$strTipo = '<span class="badge badge-success">Crédito Fiscal</span>';
													$dte["tipo"] = $row["tipoDoc"];
													break;
												case '14':
													$strTipo = '<span class="badge badge-warning">Sujeto excluido</span>';
													$dte["tipo"] = $row["tipoDoc"];
													break;
												
												default:
													$strTipo = '<span class="badge badge-primary">Consumidor Final</span>';
													$dte["tipo"] = $row["tipoDoc"];
													break;
											}

											$strUrl = urlencode(base64_encode(serialize($dte))); // Respuesta de hacienda
									?>
										<tr>
											<td class="text-center"><?php echo $row["noItem"]; ?></td>
											<td class="text-center"><?php echo $row["codigoGeneracion"]; ?></td>
											<td class="text-center"> <?php echo $strTipo;?> </td>

											<td class="text-center">
												<?php
													echo '<a href="'.base_url().'Facturacion/consultar_dte/'.$strUrl.'/" target="blank" class="text-success"><i class="fas fa-search"></i></a>';
												?>
											</td>
											 
                                            
										</tr>

									<?php
										} // Fin del foreach
									
									?>
									</tbody>
								</table>
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
