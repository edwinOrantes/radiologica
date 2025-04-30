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
						<li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Limpieza </a> </li>
						<li class="breadcrumb-item"><a href="#">Lista insumos</a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Listado de Insumos</h6></div>
							<div class="col-md-6 text-right">
									<button class="btn btn-primary btn-sm" href="#agregarInsumo" data-toggle="modal"><i class="fa fa-plus"></i> Agregar Insumo</button>
									<a href="<?php echo base_url()?>Limpieza/insumos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
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
											<th class="text-center" scope="col">Categoría</th>
											<th class="text-center" scope="col">Medida</th>
											<th class="text-center" scope="col">Stock</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$index = 0;
										$cero = 0;
										foreach ($insumos as $insumo) {
											if ($insumo->pivoteInsumoLimpieza == 1) {
									?>
										<tr class="">
											<td class="text-center"><?php echo $insumo->codigoInsumoLimpieza; ?></td>
											<td class="text-center"><?php echo $insumo->nombreInsumoLimpieza; ?></td>
											<td class="text-center"><?php echo $insumo->categoriaInsumoLimpieza; ?></td>
											<td class="text-center"> <span class="badge badge-success"><?php echo $insumo->unidadInsumoLimpieza; ?></span> </td>
											<td class="text-center"><?php echo $insumo->stockInsumoLimpieza; ?></td>
											<td class="text-center">
                                                <input type="hidden" class="nombreLimp" name="nombreLab" value="<?php echo $insumo->nombreInsumoLimpieza; ?>">
												<input type="hidden" class="precioLimp" name="codigoLab" value="<?php echo $insumo->precioInsumoLimpieza; ?>">
												<input type="hidden" class="marcaLimp" name="codigoLab" value="<?php echo $insumo->marcaInsumoLimpieza; ?>">
												<input type="hidden" class="categoriaLimp" name="tipoLab" value="<?php echo $insumo->categoriaInsumoLimpieza; ?>">
												<input type="hidden" class="unidadLimp" name="codigoLab" value="<?php echo $insumo->unidadInsumoLimpieza; ?>">
												<input type="hidden" class="minimoLimp" name="stockLab" value="<?php echo $insumo->minimoInsumoLimpieza; ?>">
												<input type="hidden" class="stockLimp" name="stockLab" value="<?php echo $insumo->stockInsumoLimpieza; ?>">
												<input type="hidden" class="idLimp" name="idLab" value="<?php echo $insumo->idInsumoLimpieza; ?>">
												<?php
													if($this->session->userdata("nivel") == 1){
														echo "<a href='#actualizarInsumo' class='editarInsumoLab' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
														echo "<a href='#eliminarMedicamento' class='eliminarInsumoLab' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
													}
												?>
											</td>
										</tr>

									<?php
											}
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
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del Insumo </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Limpieza/guardar_insumos" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 mb-3">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value="<?php echo $codigo; ?>"  readonly>
												<input type="hidden" class="form-control" value="<?php echo $codigo; ?>" name="codigoIL">
												<div class="invalid-tooltip">
													Ingrese un código.
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-row">
										<div class="col-md-4 mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" name="nombreIL" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-4 mb-3">
											<label for=""><strong>Precio</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value="0" name="precioIL" required>
												<div class="invalid-tooltip">
													Ingrese el precio del insumo.
												</div>
											</div>
										</div>
                                        <div class="col-md-4 mb-2">
                                            <label for=""><strong>Marca</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="marcaIL">
                                                <div class="invalid-tooltip">
                                                    Ingrese la marca.
                                                </div>
                                            </div>
                                        </div>
									</div>

									<div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for=""><strong>Categoría</strong></label>
                                            <div class="input-group">
                                            <select class="form-control" name="categoriaIL" required>
                                                <option value="">.:: Seleccionar::.</option>
                                                <option value="Insumos de lavanderia">Insumos de lavanderia</option>
                                                <option value="Atencion a pacientes">Atención a pacientes</option>
                                                <option value="Insumos de limpieza">Insumos de limpieza</option>
                                                <option value="Insumos de mantenimiento">Insumos de mantenimiento</option>

                                            </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese una categoría.
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for=""><strong>Medida</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" name="medidaIL" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <option value="Unidad.">Unidad.</option>
                                                    <option value="Docena">Docena</option>
                                                    <option value="Galon">Galón</option>
                                                    <option value="Tira">Tira</option>
                                                    <option value="Caja">Caja</option>
                                                    <option value="Bolsa">Bolsa</option>
                                                    <option value="Paquete">Paquete</option>
                                                    <option value="Libra">Libra</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese la medida del Insumo.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
										<div class="col-md-6 mb-2">
											<label for=""><strong>Stock mínimo</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" value="0"  name="minimoIL" required>
												<div class="invalid-tooltip">
													Ingrese el stock minimo.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-6 mb-3">
											<label for=""><strong>Stock</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" value="0" name="stockIL" required>
												<div class="invalid-tooltip">
													Ingrese la existencia actual.
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
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del Insumo </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Limpieza/actualizar_insumos" novalidate>
									
									<div class="form-row">
										<div class="col-md-4 mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombreIL" name="nombreIL" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-4 mb-3">
											<label for=""><strong>Precio</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value="0" id="precioIL" name="precioIL" required>
												<div class="invalid-tooltip">
													Ingrese el precio del insumo.
												</div>
											</div>
										</div>
                                        <div class="col-md-4 mb-2">
                                            <label for=""><strong>Marca</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="marcaIL" name="marcaIL">
                                                <div class="invalid-tooltip">
                                                    Ingrese la marca.
                                                </div>
                                            </div>
                                        </div>
									</div>

									<div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for=""><strong>Categoría</strong></label>
                                            <div class="input-group">
                                            <select class="form-control" id="categoriaIL" name="categoriaIL" required>
                                                <option value="">.:: Seleccionar::.</option>
                                                <option value="Insumos de lavanderia">Insumos de lavanderia</option>
                                                <option value="Atencion a pacientes">Atención a pacientes</option>
                                                <option value="Insumos de limpieza">Insumos de limpieza</option>
                                                <option value="Insumos de mantenimiento">Insumos de mantenimiento</option>

                                            </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese una categoría.
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for=""><strong>Medida</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="medidaIL" name="medidaIL" required>
                                                    <option value="">.:: Seleccionar::.</option>
                                                    <option value="Unidad">Unidad</option>
                                                    <option value="Docena">Docena</option>
                                                    <option value="Galon">Galón</option>
                                                    <option value="Tira">Tira</option>
                                                    <option value="Caja">Caja</option>
                                                    <option value="Bolsa">Bolsa</option>
                                                    <option value="Paquete">Paquete</option>
													<option value="Libra">Libra</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese la medida del Insumo.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
										<div class="col-md-6 mb-2">
											<label for=""><strong>Stock mínimo</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" value="0" id="minimoIL" name="minimoIL" required>
												<div class="invalid-tooltip">
													Ingrese el stock minimo.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-6 mb-3">
											<label for=""><strong>Stock</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" value="0" id="stockIL" name="stockIL" required>
												<div class="invalid-tooltip">
													Ingrese la existencia actual.
												</div>
											</div>
										</div>
									</div>

									<div class="text-center">
										<input type="hidden" id="idILA" name="idILA"/>
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

				<form action="<?php echo base_url()?>Limpieza/eliminar_insumo" method="post">
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
	$(document).on('click', '.editarInsumoLab', function(event) {
        var nombreLimp = $(this).closest('tr').find(".nombreLimp").val();
        var precioLimp = $(this).closest('tr').find(".precioLimp").val();
        var marcaLimp = $(this).closest('tr').find(".marcaLimp").val();
        var categoriaLimp = $(this).closest('tr').find(".categoriaLimp").val();
        var unidadLimp = $(this).closest('tr').find(".unidadLimp").val();
        var minimoLimp = $(this).closest('tr').find(".minimoLimp").val();
        var stockLimp = $(this).closest('tr').find(".stockLimp").val();
        var idLimp = $(this).closest('tr').find(".idLimp").val();


        $("#nombreIL").val(nombreLimp);
        $("#precioIL").val(precioLimp);
        $("#marcaIL").val(marcaLimp);
        $("#categoriaIL").val(categoriaLimp);
        $("#medidaIL").val(unidadLimp);
        $("#minimoIL").val(minimoLimp);
        $("#stockIL").val(stockLimp);
        $("#idILA").val(idLimp);


	});

	$(document).on('click', '.eliminarInsumoLab', function() {
		var idLimp = $(this).closest('tr').find(".idLimp").val();
		$("#idInsumoDelete").val(idLimp);
	});
</script>