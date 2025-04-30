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
		<div class="col-xl-12 col-md-12">
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Lista de medicamentos solicitados</h6></div>
                    </div>
				</div>
				<div class="ms-panel-body">

					<ul class="nav nav-tabs d-flex nav-justified mb-4" role="tablist">
						<li role="presentation"><a href="#pendientes" aria-controls="pendientes" class="active show" role="tab" data-toggle="tab" aria-selected="true">Por entregar</a></li>
						<li role="presentation"><a href="#entregados" aria-controls="entregados" role="tab" data-toggle="tab" class="" aria-selected="false">Entregados </a></li>
					</ul>
					
					<div class="tab-content">
							
						<div role="tabpanel" class="tab-pane fade in active show" id="pendientes">
							<?php
								if(count($pendientes) > 0){

									$arregloInsert = [];
									foreach ($pendientes as $row) {
										$arregloInsert[] = [
											'idInsumo' => $row->idInsumo,
											'cantidadHIE' => $row->cantidadHIE,
											'precio' => $row->precioHIE,
											'idHoja' => $row->idHoja,
											'fecha' => date('Y-m-d'),
											'por' => $this->session->userdata('id_usuario_h'),
											'base' => 1,
											'mg' => $row->cantidadHIE,
											'medida' => 'Unidad',
											'pe' => $row->idHIE
										];
									}

									$params = urlencode(base64_encode(serialize($arregloInsert))); // Datos a insertar

									
							?>
									
									<div class="text-right pb-2"><a href="<?php echo base_url(); ?>Enfermeria/Expediente/descontar_insumos/<?php echo $params; ?>" class="btn btn-success btn-sm text-right">Entregar todo</a></div>

									<table class="table table-hover thead-primary" id="detalleSenso">
										<thead>
											<tr>
												<th scope="col" class="text-center">#</th>
												<th scope="col" class="text-center">Insumo</th>
												<th scope="col" class="text-center">Cantidad</th>
												<th scope="col" class="text-center">Opción</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$flag = 1;
												$arregloInsert = [];
												foreach ($pendientes as $row) {

													$arregloInsert[] = [
														'idInsumo' => $row->idInsumo,
														'cantidadHIE' => $row->cantidadHIE,
														'idHoja' => $row->idHoja
													];
													echo '<tr>';
													echo '	<td scope="col" class="text-center">'.$flag.'</td>';
													echo '	<td scope="col" class="text-center">'.$row->nombreInsumo.'</td>';
													echo '	<td scope="col" class="text-center">'.$row->cantidadHIE.'</td>';
													echo '	<td scope="col" class="text-center">
																<a href="" title="Entregar"><i class="fa fa-check text-success"></i></a>
															</td>';
													echo '</tr>';
													$flag++;
												}
											?>
										</tbody>
									</table>

							<?php
									
								}else{
									echo '<div class="col-md-12 alert-danger text-center p-4"><strong>No hay insumos pendientes de entregar</strong></div>';
								}
							?>
						</div>

						<div role="tabpanel" class="tab-pane fade" id="entregados">
							<?php
								if(count($entregados) > 0){
							?>
									<table class="table table-hover thead-primary" id="detalleSenso">
										<thead>
											<tr>
												<th scope="col" class="text-center p-1">#</th>
												<th scope="col" class="text-center p-1">Insumo</th>
												<th scope="col" class="text-center p-1">Cantidad</th>
												<th scope="col" class="text-center p-1">C. D</th>
												<th scope="col" class="text-center p-1">Devolución</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$grupoActual = '';
												$colores = ['#ffffff', '#e6f6fc'];
												$colorIndex = 0;

												foreach ($entregados as $hora => $grupo) {
													$rowspan = count($grupo);
													foreach ($grupo as $index => $fila) {
														$clave = $hora;
														if ($clave !== $grupoActual) {
															$grupoActual = $clave;
															$color = $colores[$colorIndex];
															$colorIndex = ($colorIndex + 1) % count($colores);
														}

														echo "<tr data-id='".$fila->idHIE."' style='background-color: ".$color."'>";
														if ($index === 0) {
															echo "<td class='text-center p-1' data-hora='".$hora."' rowspan='".$rowspan."'>".$hora."</td>";
														}else{
															// Celda oculta para que Tabledit no se rompa
															echo "<td class='d-none'></td>";
														}
														echo "<td class='text-center p-1'>".$fila->nombreInsumo." <input type='hidden' value='".$fila->idHIE."' class='idFila'> </td>";
														echo "<td class='text-center p-1'>".$fila->cantidadHIE."
															<input type='hidden' value='".$fila->cantidadHIE."' class='cantidadHIE'>
															<input type='hidden' value='".$fila->idHIE."' class='filaHIE'>
														</td>";
														echo "<td class='text-center p-1'>".($fila->cantidadDevolucion > 0 ? $fila->cantidadDevolucion : '-')."</td>";
														if($fila->devolucion == 1){
															echo "<td class='text-center p-1 text-danger'>
																<input type='hidden' value='".$fila->cantidadDevolucion."' class='cantidadDevolucionHIE'>
																<a href='#procesarDevolucion' data-toggle='modal' class='btnDevolver' style='cursor: pointer' title='Devolución de insumo'><i class='fa fa-check text-danger'></i></a>
															</td>";
														}else{
															echo "<td class=''></td>";
														}
														echo "</tr>";
													}
												}
											?>
										</tbody>
									</table>

							<?php
									
								}else{
									echo '<div class="col-md-12 alert-danger text-center p-4"><strong>No hay insumos pendientes de entregar</strong></div>';
								}
							?>
						</div>

					</div>

				</div>
			</div>
		</div>

	</div>
</div>

<!-- Modal para crear senso-->
	<div class="modal fade" id="procesarDevolucion" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i> Advertencia</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								
								<h4 class="text-center">¿El medicamento ha sido devuelto?</h4>
								<form class="needs-validation" id="frmMedico"  method="post" action="<?php echo base_url() ?>Enfermeria/Expediente/procesar_devolucion/"  novalidate>
									
									<input type="hidden" class="form-control" id="cantidadNueva" name="cantidadNueva" required>
									<input type="hidden" class="form-control" id="cantidadDevolver" name="cantidadDevolver" required>
									<input type="hidden" class="form-control" id="filaActualizar" name="filaActualizar" required>
									<input type="hidden" value="<?php echo $hoja; ?>" class="form-control" name="hoja" required>

									<div class="text-center">
										<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Si </button>
										<button class="btn btn-light" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> No</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal para crear senso-->



<script>
	document.addEventListener("DOMContentLoaded", function () {
		// Restaurar el tab activo desde localStorage
		var activeTab = localStorage.getItem("activeTab");
		if (activeTab) {
			$('a[href="' + activeTab + '"]').tab('show');
		}

		// Guardar el tab activo en localStorage cuando se cambia
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			localStorage.setItem("activeTab", $(e.target).attr("href"));
		});
	});

	$(document).on("click", ".btnDevolver", function() {
		var fila = $(this).closest('tr');

		$("#cantidadNueva").val(fila.find('.cantidadHIE').val())
		$("#cantidadDevolver").val(fila.find('.cantidadDevolucionHIE').val())
		$("#filaActualizar").val(fila.find('.filaHIE').val())
		
	});
</script>

