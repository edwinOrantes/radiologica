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


<?php
	function diferencia($a = null, $b = null){
		$fecha1= new DateTime($a);
		$fecha2= new DateTime($b);
		$diff = $fecha1->diff($fecha2);
		echo $diff->days . ' dias'; 
	}
?>


<!-- Body Content Wrapper -->
	<div class="ms-content-wrapper">
		<div class="row">
			<div class="col-md-12">
			
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb breadcrumb-arrow has-gap">
						<li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Laboratorio </a> </li>
						<li class="breadcrumb-item"><a href="#">Lista donantes </a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Listado de donantes</h6></div>
							<div class="col-md-6 text-right">
                                    <a href="#agregarDonante" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar donante </a>
									<!-- <a href="<?php echo base_url()?>InsumosLab/insumos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
							</div>
						</div>
					</div>

					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<?php
									if (sizeof($donantes) > 0) {
								?>
								<table id="" class="table table-striped thead-primary w-100 insumos-lab">
									<thead>
										<tr>
											<th class="text-center" scope="col">Código</th>
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Edad</th>
											<th class="text-center" scope="col">Última donacion</th>
											<th class="text-center" scope="col">Dias transcurridos</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
                                        <?php
                                            $index = 0;
                                            $cero = 0;
											$hoy = date('Y-m-d');
                                            foreach ($donantes as $donante) {
                                        ?>
                                            <tr class="">
                                                <td class="text-center"><?php echo $donante->codigoDonante; ?></td>
                                                <td class="text-center"><?php echo $donante->nombreDonante; ?></td>
                                                <td class="text-center"><?php echo $donante->edadDonante; ?> Años</td>
                                                <td class="text-center"><?php echo $donante->ultimaFecha; ?></td>
                                                <td class="text-center"><span class="badge badge-success"><?php diferencia($hoy, $donante->ultimaFecha); ?></span></td>
                                                <td class="text-center">
													<input type="hidden" value="<?php echo $donante->idDonante; ?>" class="idDonante">
													<input type="hidden" value="<?php echo $donante->nombreDonante; ?>" class="nombre">
													<input type="hidden" value="<?php echo $donante->edadDonante; ?>" class="edad">
													<input type="hidden" value="<?php echo $donante->duiDonante; ?>" class="dui">
                                                    <?php
                                                        echo "<a title='Editar datos paciente' href='#editarDonante' data-toggle='modal' class='btnEditarDonante'><i class='fas fa-edit ms-text-primary'></i></a>";
                                                        echo "<a title='Último comprobante' target='blank' href='".base_url()."InsumosLab/comprobante_donante/".$donante->idDonante."/' class='editarInsumoLab'><i class='fas fa-file ms-text-primary'></i></a>";
                                                        echo "<a title='Nuevo donativo' href='#nuevoDonativo' data-toggle='modal' class='btnHacerDonativo'><i class='fas fa-plus ms-text-success'></i></a>";
                                                        echo "<a title='Ver detalle' href='".base_url()."InsumosLab/historial_donante/".$donante->idDonante."/' class=''><i class='fas fa-eye ms-text-primary'></i></a>";
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

<!-- Modal para agregar donante-->
	<div class="modal fade" id="agregarDonante" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del donante </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/guardar_donante" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 2mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="hidden" name="codigoDonante" >
												<input type="text" class="form-control" id="nombreDonante" name="nombreDonante" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-12 mb-3">
											<label for=""><strong>Edad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="edadDonante" name="edadDonante" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>DUI</strong></label>
											<div class="input-group">
												<input type="text" data-mask="99999999-9" class="form-control" id="duiDonante" name="duiDonante" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>Insumo</strong></label>
											<div class="input-group">
												<select class="form-control" id="insumoDonante" name="insumoDonante" required>
													<option value="">:: Seleccionar ::</option>
													<?php
														foreach ($insumos as $row) {
															echo '<option value="'.$row->idInsumoLab.'">'.$row->nombreInsumoLab.'</option>';
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>Precio</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioDonante" name="precioDonante" required>
												<div class="invalid-tooltip">
													Ingrese el precio.
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
<!-- Fin Modal para agregar donante-->

<!-- Modal para nuevo donativo-->
	<div class="modal fade" id="nuevoDonativo" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del donante </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/nuevo_donativo" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 2mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombreDonanteA" name="nombreDonante" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-12 mb-3">
											<label for=""><strong>Edad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="edadDonanteA" name="edadDonante" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>DUI</strong></label>
											<div class="input-group">
												<input type="text" data-mask="99999999-9" class="form-control" id="duiDonanteA" name="duiDonante" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>Insumo</strong></label>
											<div class="input-group">
												<select class="form-control" id="insumoDonanteA" name="insumoDonante" required>
													<option value="">:: Seleccionar ::</option>
													<?php
														foreach ($insumos as $row) {
															echo '<option value="'.$row->idInsumoLab.'">'.$row->nombreInsumoLab.'</option>';
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>Precio</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioDonanteA" name="precioDonante" required>
												<div class="invalid-tooltip">
													Ingrese el precio.
												</div>
											</div>
										</div>

									</div>

									<div class="text-center">
										<input type="hidden" class="form-control" id="idDonanteA" name="idDonante" required>
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
<!-- Fin Modal para nuevo donativo-->

<!-- Modal para nuevo donativo-->
	<div class="modal fade" id="editarDonante" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del donante </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/editar_donante" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 2mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombreDonanteU" name="nombreDonante" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-12 mb-3">
											<label for=""><strong>Edad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="edadDonanteU" name="edadDonante" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-3">
											<label for=""><strong>DUI</strong></label>
											<div class="input-group">
												<input type="text" data-mask="99999999-9" class="form-control" id="duiDonanteU" name="duiDonante" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

									</div>

									<div class="text-center">
										<input type="hidden" class="form-control" id="idDonanteU" name="idDonante" required>
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
<!-- Fin Modal para nuevo donativo-->


<script>
	$(document).on('click', '.btnHacerDonativo', function(e) {
		e.preventDefault();
		$("#nombreDonanteA").val($(this).closest('tr').find(".nombre").val());
		$("#edadDonanteA").val($(this).closest('tr').find(".edad").val());
		$("#duiDonanteA").val($(this).closest('tr').find(".dui").val());
		$("#idDonanteA").val($(this).closest('tr').find(".idDonante").val());
	});

	$(document).on('click', '.btnEditarDonante', function(e) {
		e.preventDefault();
		$("#nombreDonanteU").val($(this).closest('tr').find(".nombre").val());
		$("#edadDonanteU").val($(this).closest('tr').find(".edad").val());
		$("#duiDonanteU").val($(this).closest('tr').find(".dui").val());
		$("#idDonanteU").val($(this).closest('tr').find(".idDonante").val());

	});
</script>
