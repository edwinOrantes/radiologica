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
                    </div>
				</div>

				<div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
							<?php
								if (sizeof($medicamentos) > 0) {
							?>
                            <table id="" class="table table-striped thead-primary w-100 tabla-medicamentos">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Código</th>
                                        <th class="text-center" scope="col">Nombre</th>
                                        <th class="text-center" scope="col">Precio Compra</th>
                                        <th class="text-center" scope="col">Precio Venta</th>
                                        <th class="text-center" scope="col">Existencia</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
									$index = 0;
									$cero = 0;
									foreach ($medicamentos as $medicamento) {

										if($medicamento->tipoMedicamento != "Servicios" && $medicamento->tipoMedicamento != "Otros servicios"){

										$index++;
										$id ='"'.$medicamento->idMedicamento.'"';
										$codigo = '"'.$medicamento->codigoMedicamento.'"';
										$nombre = '"'.$medicamento->nombreMedicamento.'"';
										$proveedor = '"'.$medicamento->empresaProveedor .'"';
										$idproveedor = '"'.$medicamento->idProveedorMedicamento.'"';
										$precioC = '"'.$medicamento->precioCMedicamento.'"';
										$precioV = '"'.$medicamento->precioVMedicamento.'"';
										$tipo = '"'.$medicamento->tipoMedicamento.'"';
										$clasif = '"'.$medicamento->nombreClasificacionMedicamento.'"';
										$idclasif = '"'.$medicamento->idClasificacionMedicamento.'"';
										$stock = '"'.$medicamento->stockMedicamento.'"';
										$usados = '"'.$medicamento->usadosMedicamento.'"';
										if($medicamento->tipoMedicamento == "Servicios"){
											$stock = '"'.$cero.'"';
										}else{
											$stock = '"'.$medicamento->stockMedicamento.'"';
										} 

										if($medicamento->tipoMedicamento == 1){
											$alert = "alert-danger";
										}else{
											$alert = "";
										}	
								?>
                                    <tr class="<?php echo $alert; ?>">
                                        <td class="text-center"><?php echo $medicamento->codigoMedicamento; ?></td>
                                        <td class="text-center"><?php echo $medicamento->nombreMedicamento; ?></td>
                                        <td class="text-center">$ <?php echo number_format($medicamento->precioCMedicamento, 2); ?></td>
                                        <td class="text-center">$ <?php echo number_format($medicamento->precioVMedicamento, 2); ?></td>
                                        <td class="text-center">
											<?php
												if($medicamento->stockMedicamento == 0){
													switch ($medicamento->tipoMedicamento) {
														case 'Servicios':
															echo '<span class="badge badge-gradient-info">Servicio</span>';
															break;
														case 'Otros servicios':
															echo '<span class="badge badge-gradient-info">Otros servicios</span>';
															break;
														
														default:
															echo '<span class="badge badge-gradient-danger">Sin stock</span>';
															break;
													}
												}else{
													echo $medicamento->stockMedicamento;
												}
											?>
										</td>
                                        <td class="text-center">
										<?php
                                            echo "<a onclick='actualizarMedicamento($id, $codigo, $nombre, $precioC, $precioV, $tipo, $idproveedor, $idclasif, $stock)' href='#actualizarMedicamento' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
											if( $medicamento->stockMedicamento == 0){
												echo "<a onclick='eliminarMedicamento($id)' href='#eliminarMedicamento' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
											}
										?>
                                        </td>
                                    </tr>

								<?php
										} // Fin del IF
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



<!-- Modal para actualizar datos del Medicamento-->
<div class="modal fade" id="actualizarMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Datos del medicamento</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>Botiquin/actualizar_stock_medicamento" novalidate>
								
                                <div class="form-row">
									<div class="col-md-12 mb-3">
										<label for=""><strong>Código</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="codigoMedicamentoAA"  readonly>
											<input type="hidden" class="form-control" id="codigoMedicamentoA" name="codigoMedicamento" required>
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
											<input type="text" class="form-control" id="nombreMedicamentoA" name="nombreMedicamento" placeholder="Nombre del medicamento" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<label for=""><strong>Stock</strong></label>
										<div class="input-group">
                                        <input type="text" class="form-control" id="stockMedicamento" name="stockMedicamento" placeholder="Existencia del medicamento" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un proveedor.
                                            </div>
										</div>

									</div>
								</div>

								<div class="form-row">
									<div class="col-md-6 mb-3">
										<label for=""><strong>Precio compra</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="precioCMedicamentoA" name="precioCMedicamento" placeholder="Precio de compra del medicamento" required>
                                            <div class="invalid-tooltip">
                                                Ingrese el precio de compra.
                                            </div>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<label for=""><strong>Precio venta</strong></label>
										<div class="input-group">
											<input type="text" class="form-control" id="precioVMedicamentoA" name="precioVMedicamento" placeholder="Precio venta del medicamento" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un precio de venta.
                                            </div>
										</div>
									</div>
								</div>

                                <div class="form-row">
									<div class="col-md-6 mb-3">
										<label for=""><strong>Tipo</strong></label>
                                        <select class="form-control" id="tipoMedicamentoA" name="tipoMedicamento" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <option value="Medicamentos">Medicamentos</option>
                                            <option value="Materiales médicos">Materiales médicos</option>
                                            <option value="Gastos hospitalarios">Gastos hospitalarios</option>
                                            <option value="Servicios">Servicios</option>
                                            <option value="Otros servicios">Otros servicios</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Selecciona un tipo de medicamento.
                                        </div>
									</div>
									<div class="col-md-6 mb-3">
										<label for="validationCustom08"><strong>Clasificación</strong></label>
										<select class="form-control" id="idClasificacionMedicamentoA" name="idClasificacionMedicamento" required>
                                            <option value="">.:: Seleccionar ::.</option>

											<?php
												foreach ($clasificaciones as $clasificacion) {
											?>
                                            <option value="<?php echo $clasificacion->idClasificacionMedicamento ?>"><?php echo $clasificacion->nombreClasificacionMedicamento ?></option>
											<?php } ?>

                                        </select>
                                        <div class="invalid-tooltip">
                                            Selecciona una clasificación.
                                        </div>
									</div>
								</div>
								<input type="hidden" id="idMedicamentoA" name="idMedicamentoA">						
								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar medicamento</button>
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

<script>

	function actualizarMedicamento(id, codigo, nombre, precioC, precioV, tipo, idproveedor, idclasif, stock){
		document.getElementById("codigoMedicamentoAA").value = codigo;
		document.getElementById("codigoMedicamentoA").value = codigo;
		document.getElementById("nombreMedicamentoA").value = nombre;
		document.getElementById("stockMedicamento").value = stock;
		document.getElementById("precioCMedicamentoA").value = precioC;
		document.getElementById("precioVMedicamentoA").value = precioV;
		document.getElementById("tipoMedicamentoA").value = tipo;
		document.getElementById("idClasificacionMedicamentoA").value = idclasif;
		document.getElementById("idMedicamentoA").value = id;
	}

</script>