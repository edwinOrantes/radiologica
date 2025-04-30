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
						<li class="breadcrumb-item"><a href="#">Lista de notas de créditos </a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<!-- <div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Historial</h6></div>
							<div class="col-md-6 text-right">
									<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Facturacion/lista_ccf/"><i class="fa fa-plus"></i> Agregar Nota de Crédito</a>
							</div>
						</div>
					</div> -->

					<div class="ms-panel-body">
						<div class="row">

							

							
								<?php
									if (sizeof($lista_archivos) > 0) {
								?>

								<div class="col-md-6">

								</div>

								<div class="col-md-6 text-center">
									<form action="<?php echo base_url(); ?>Facturacion/descargar_archivos_rango" method="POST" class="form-inline">
										<div class="form-group mb-2">
											<label for="fecha_inicio" class="mr-2"><strong>Fecha de inicio</strong></label>
											<input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
										</div>
										<div class="form-group mb-2 ml-2">
											<label for="fecha_fin" class="mr-2"><strong>Fecha de fin</strong></label>
											<input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
										</div>
										<button type="submit" class="btn btn-primary mb-2 ml-2">Descargar archivos en rango</button>
									</form>
								</div>

								<div class="table-responsive mt-3">
									<table id="" class="table table-striped thead-primary w-100 insumos-lab">
										<thead>
											<tr>
												<th class="text-center">Fecha</th>
												<th class="text-center">Tipo</th>
												<th class="text-center">Nombre PDF</th>
												<th class="text-center">Tamaño PDF (KB)</th>
												<th class="text-center">Tamaño JSON (KB)</th>
												<th class="text-center">Acciones</th>
											</tr>
										</thead>
										<tbody>
										<?php 
											foreach ($lista_archivos as $archivo){
												$doc["nombre"] = $archivo['nombre_pdf'];
												$doc["correo"] = $archivo['correo'];
												$doc["nombre_json"] = $archivo['nombre_json'];
												$params = urlencode(base64_encode(serialize($doc)));
												
											?>
											
											<tr>
												<td class="text-center"><?php echo date('Y-m-d', $archivo['fecha']); ?></td>
												<td class="text-center"><?php echo $archivo['tipo']; ?></td>
												<td class="text-center"><?php echo preg_replace('/^[A-Za-z0-9\-]+-(.*?)(_\d+)?\.pdf$/', '$1', $archivo['nombre_pdf']); ?></td>
												<td class="text-center"><?php echo round($archivo['tam_pdf'] / 1024, 2); ?> KB</td>
												<td class="text-center"><?php echo round($archivo['tam_json'] / 1024, 2); ?> KB</td>
												<!-- <td>
													<a href="<?php echo site_url('archivos/descargar/' . $archivo['nombre_pdf']); ?>" class="btn btn-primary btn-sm">Descargar PDF</a>
													<a href="<?php echo site_url('archivos/descargar/' . $archivo['nombre_json']); ?>" class="btn btn-success btn-sm">Descargar JSON</a>
												</td> -->
												<td class="text-center">
													<input type="hidden" value="<?php echo $archivo['nombre_pdf']; ?>" class="nombre_pdf">
													<input type="hidden" value="<?php echo $archivo['nombre_json']; ?>" class="nombre_json">
													<input type="hidden" value="<?php echo $archivo['correo']; ?>" class="correo">
													<!-- <input type="checkbox" name="archivos[]" value='<?php echo json_encode($archivo); ?>'> -->
													<a href="<?php echo base_url('Facturacion/descargar_archivo/' . $archivo['nombre_pdf'] . '/' . $archivo['nombre_json']); ?>" class="btn btn-primary btn-sm">
														<i class="fa fa-download fa-xs"></i>
													</a>
													<a href="#envioDeCorreo" data-toggle="modal" class="btn btn-outline-primary btn-sm btnEnviarCorreo"> <i class="fa fa-share fa-xs"></i> </a>
												</td>


											</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
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
<!-- End body Content Wrapper -->


<!-- Modal para agregar datos del Medicamento-->
	<div class="modal fade" id="envioDeCorreo" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del correo</h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Facturacion/compartir_archivo" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 mb-3">
											<label for=""><strong>Correo</strong></label>
											<div class="input-group">
												<input type="email" class="form-control" id="receptorCorreo" name="receptorCorreo" required>
												<div class="invalid-tooltip">
													Ingrese el corrreo del receptor
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-row">
										<div class="col-md-12 mb-2">
											<label for=""><strong>Documentos</strong></label>
											<div class="">
												<span class="badge badge-danger p-2" id="pdfCorreo"></span><br>
												<span class="badge badge-success p-2 my-2" id="jsonCorreo"></span>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
									</div>

									<div class="text-center">
										<input type="hidden" id="archivoPDF" name="archivoPDF">
										<input type="hidden" id="archivojson" name="archivojson">
										<button class="btn btn-primary mt-4 d-inline btn-block" type="submit"><i class="fa fa-share fa-x"></i> Enviar </button>
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


<script>
	$(document).on('click', '.btnEnviarCorreo', function() {
		var nombre_pdf = $(this).closest('tr').find('.nombre_pdf').val();
		var nombre_json = $(this).closest('tr').find('.nombre_json').val();
		var correo = $(this).closest('tr').find('.correo').val();

		$("#receptorCorreo").val(correo);
		$("#pdfCorreo").html(nombre_pdf);
		$("#jsonCorreo").html(nombre_json);
		
		$("#archivoPDF").val(nombre_pdf);
		$("#archivojson").val(nombre_json);

	});
</script>