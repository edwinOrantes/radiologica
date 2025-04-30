<?php if ($this->session->flashdata("exito")): ?>
  <script type="text/javascript">
    $(document).ready(function(){
      toastr.remove();
      toastr.options.positionClass = "toast-top-center";
      toastr.success('<?php echo addslashes($this->session->flashdata("exito")); ?>', 'Aviso!');
    });
  </script>
<?php $this->session->unset_userdata("exito"); endif; ?>

<?php if ($this->session->flashdata("error")): ?>
  <script type="text/javascript">
    $(document).ready(function(){
      toastr.remove();
      toastr.options.positionClass = "toast-top-center";
      toastr.error('<?php echo addslashes($this->session->flashdata("error")); ?>', 'Aviso!');
    });
  </script>
<?php $this->session->unset_userdata("error"); endif; ?>


<?php
	$porVencer = 0;
	function dias($i = null, $f = null){
		$cadena = "";
        $inicio= new DateTime($i);
        $fin= new DateTime($f);
        $dias = $inicio->diff($fin);

        if($inicio < $fin){
            // El resultados sera 3 dias
            switch ($dias->days) {
                case ($dias->days <= 90):
                    $cadena = '<span class="badge badge-danger">'.$dias->days.' dias</span>';
                    break;
                case ($dias->days >= 90 && $dias->days <=100):
                    $cadena = '<span class="badge badge-success">'.$dias->days.' dias</span>';
                    break;
                    
                    default:
                        $cadena = '<span class="badge badge-primary">'.$dias->days.' dias</span>';
                        break;
                    
                }
                
        }else{
            $cadena = '<span class="badge badge-danger">'.$dias->days.' dias de Vencido </span>';  
        }
         return $cadena;

	}

	foreach ($medicamentos as $medicamento) {
		if(!is_null($medicamento->fechaVencimiento)){
			$inicio= new DateTime(date("Y-m-d"));
			$fin= new DateTime($medicamento->fechaVencimiento);
			$dias = $inicio->diff($fin);
	
			if($dias->days <= 100){
				$porVencer++;
			}
		}
	}
?>

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
                        <div class="col-md-6">
							<h6>Listado de medicamentos</h6>
							<p class="alert-danger p-2 text-danger bold">Hay <?php echo $porVencer; ?> medicamentos que estan por vencer</p>
						</div>
                        <div class="col-md-6 text-right">
                                <!-- <button class="btn btn-primary btn-sm" href="#agregarMedicamento" data-toggle="modal"><i class="fa fa-plus"></i> Agregar Medicamento</button> -->
                                <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Botiquin/datos_medicamento/"><i class="fa fa-plus"></i> Agregar Medicamento</a>
                                <a href="<?php echo base_url()?>Botiquin/medicamentos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                                <!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
                        </div>
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
                                        <th class="text-center" scope="col">F/V</th>
                                        <th class="text-center" scope="col">Para vencer</th>
                                        <th class="text-center" scope="col">Precio Compra</th>
                                        <th class="text-center" scope="col">Precio Venta</th>
                                        <th class="text-center" scope="col">S/B</th>
                                        <th class="text-center" scope="col">S/CP1</th>
                                        <th class="text-center" scope="col">S/CP2</th>
                                        <th class="text-center" scope="col">S/CP3</th>
                                        <th class="text-center" scope="col">S/CP4</th>
                                        <th class="text-center" scope="col">S/E</th>
                                        <th class="text-center" scope="col">Existencia Total</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
									$index = 0;
									$cero = 0;
									$st = 0;
									foreach ($medicamentos as $medicamento) {

										if($medicamento->tipoMedicamento != "Servicios" && $medicamento->tipoMedicamento != "Otros servicios"){
										// Variables
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
											$vencimiento = '"'.$medicamento->fechaVencimiento.'"';
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
										// Variables

										// Sacando el stock total
										$st = ($medicamento->stockMedicamento + $medicamento->cp1 + $medicamento->cp2 + $medicamento->cp3 + $medicamento->cp4 + $medicamento->emergencia);
								?>
                                    <tr class="<?php echo $alert; ?>">
                                        <td class="text-center"><?php echo $medicamento->codigoMedicamento; ?></td>

                                        <td class="text-center">
											<?php 
												echo $medicamento->nombreMedicamento;
												$medidas = json_decode($medicamento->medidas);
												if(!is_null($medidas)){
													foreach ($medidas as $row) {
														echo ' <span class="badge badge-primary">'.$row->nombreMedida." - ".$row->cantidad.'</span> ';
													}
												}
												
											?>
										</td>

                                        <td class="text-center"><?php echo $dias = !is_null($medicamento->fechaVencimiento) ? $medicamento->fechaVencimiento : "---"; ?></td>
                                        <td class="text-center"><?php echo $dias = !is_null($medicamento->fechaVencimiento) ? dias(date("Y-m-d"), $medicamento->fechaVencimiento) : "---"; ?></td>
                                        <td class="text-center">$ <?php echo number_format($medicamento->precioCMedicamento, 2); ?></td>
                                        <td class="text-center">$ <?php echo number_format($medicamento->precioVMedicamento, 2); ?></td>
                                        <td class="text-center"> 
											<?php
												if($medicamento->stockMedicamento == 0){
													echo '<span class="badge badge-gradient-danger">Sin stock</span>';
												}else{
													echo $medicamento->stockMedicamento;
												}
											?>
										</td>
										<td class="text-center"> <a href="<?php echo base_url()."Stock/detalle_stock/1/"; ?>" title="Ver stock" target="blank"> <?php echo ($medicamento->cp1 > 0) ? '<span class="badge badge-gradient-info">'.$medicamento->cp1.'</span>': ""; ?></a></td>
										<td class="text-center"> <a href="<?php echo base_url()."Stock/detalle_stock/2/"; ?>" title="Ver stock" target="blank"><?php echo ($medicamento->cp2 > 0) ? '<span class="badge badge-gradient-info">'.$medicamento->cp2.'</span>': ""; ?> </a> </td>
										<td class="text-center"> <a href="<?php echo base_url()."Stock/detalle_stock/3/"; ?>" title="Ver stock" target="blank"><?php echo ($medicamento->cp3 > 0) ? '<span class="badge badge-gradient-info">'.$medicamento->cp3.'</span>': ""; ?> </a> </td>
										<td class="text-center"> <a href="<?php echo base_url()."Stock/detalle_stock/4/"; ?>" title="Ver stock" target="blank"><?php echo ($medicamento->cp4 > 0) ? '<span class="badge badge-gradient-info">'.$medicamento->cp4.'</span>': ""; ?> </a> </td>
										<td class="text-center"> <a href="<?php echo base_url()."Stock/detalle_stock/5/"; ?>" title="Ver stock" target="blank"><?php echo ($medicamento->emergencia > 0) ? '<span class="badge badge-gradient-info">'.$medicamento->emergencia.'</span>': ""; ?> </a> </td>
										<td class="text-center"><?php echo $st; ?></td>
                                        <td class="text-center">
											<?php
												echo "<a onclick='verDetalle($id, $codigo, $nombre, $proveedor, $precioC, $precioV, $tipo, $clasif, $stock, $usados)' href='#verMedicamento' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
												
												switch($this->session->userdata('nivel')) {
													case '1':
														echo "<a href='".base_url()."Botiquin/actualizar_datos_medicamento/".$medicamento->idMedicamento."/'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
														echo "<a onclick='eliminarMedicamento($id)' href='#eliminarMedicamento' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
													break;
													case '9':
														echo "<a href='".base_url()."Botiquin/actualizar_datos_medicamento/".$medicamento->idMedicamento."/'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
													break;
													default:
														echo "";
														break;
												}

											?>
                                        </td>
                                    </tr>

								<?php
										} // Fin del IF
										$st = 0;
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


<!-- Modal para agregar datos del Medicamento-->
	<div class="modal fade" id="agregarMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
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
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Botiquin/guardar_medicamento" novalidate>
									
									<div class="form-row">
										<div class="col-md-12 mb-3">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value=<?php echo $cod; ?> readonly>
												<input type="hidden" class="form-control" id="codigoMedicamento" name="codigoMedicamento" value=<?php echo $cod; ?> required>
												<div class="invalid-tooltip">
													Ingrese un código.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombreMedicamento" name="nombreMedicamento" placeholder="Nombre del medicamento" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Proveedor</strong></label>
											<div class="input-group">
											<select class="form-control controlInteligente" id="idProveedorMedicamento" name="idProveedorMedicamento" required>
												<option value="">.:: Seleccionar::.</option>

												<?php
													foreach ($proveedores as $proveedor) {
												?>
												<option value="<?php echo $proveedor->idProveedor ?>"><?php echo $proveedor->empresaProveedor ?></option>
												<?php } ?>

											</select>
												<div class="invalid-tooltip">
													Ingrese un proveedor.
												</div>
											</div>

										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Precio compra</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioCMedicamento" name="precioCMedicamento" placeholder="Precio de compra del medicamento" required>
												<div class="invalid-tooltip">
													Ingrese el precio de compra.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Precio venta</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioVMedicamento" name="precioVMedicamento" placeholder="Precio venta del medicamento" required>
												<div class="invalid-tooltip">
													Ingrese un precio de venta.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Tipo</strong></label>
											<select class="form-control" id="tipoMedicamento" name="tipoMedicamento" required>
												<option value="">.:: Seleccionar ::.</option>
												<option value="Medicamentos">Medicamentos</option>
												<option value="Materiales médicos">Materiales médicos</option>
												<option value="Servicios">Servicios</option>
												<option value="Otros servicios">Otros servicios</option>
											</select>
											<div class="invalid-tooltip">
												Selecciona un tipo de medicamento.
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for="validationCustom08"><strong>Clasificación</strong></label>
											<select class="form-control controlInteligente" id="idClasificacionMedicamento" name="idClasificacionMedicamento" required>
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

									<div class="text-center">
										<input type="hidden" name="ocultarMedicamento" value="0">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar medicamento</button>
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


<!-- Modal ver datos del Medicamento-->
	<div class="modal fade" id="verMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white">Datos del medicamento para actualizar</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body" id="detalleMedicamento"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal ver datos del Medicamento-->



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
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Botiquin/actualizar_medicamento" novalidate>
									
									<div class="form-row">

										<div class="col-md-6 mb-3">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="codigoMedicamentoAA"  readonly>
												<input type="hidden" class="form-control" id="codigoMedicamentoA" name="codigoMedicamentoA" required>
												<div class="invalid-tooltip">
													Ingrese un código.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Nombre</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombreMedicamentoA" name="nombreMedicamentoA" placeholder="Nombre del medicamento" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Proveedor</strong></label>
											<div class="input-group">
											<select class="form-control controlInteligente2" id="idProveedorMedicamentoA" name="idProveedorMedicamentoA" required>
												<option value="">.:: Seleccionar::.</option>

												<?php
													foreach ($proveedores as $proveedor) {
												?>
												<option value="<?php echo $proveedor->idProveedor ?>"><?php echo $proveedor->empresaProveedor ?></option>
												<?php } ?>

											</select>
												<div class="invalid-tooltip">
													Ingrese un proveedor.
												</div>
											</div>

										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Precio compra</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioCMedicamentoA" name="precioCMedicamentoA" placeholder="Precio de compra del medicamento" required>
												<div class="invalid-tooltip">
													Ingrese el precio de compra.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Precio venta</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioVMedicamentoA" name="precioVMedicamentoA" placeholder="Precio venta del medicamento" required>
												<div class="invalid-tooltip">
													Ingrese un precio de venta.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<label for=""><strong>Tipo</strong></label>
											<select class="form-control" id="tipoMedicamentoA" name="tipoMedicamentoA" required>
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

										<div class="col-md-6">
                                            <label for=""><strong>Fecha vencimiento:</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="venceMedicamento" name="fechaVencimiento" placeholder="Fecha de vencimiento" required>
                                                <div class="invalid-tooltip">
                                                    Fecha de vencimiento
                                                </div>
                                            </div>
                                        </div>

										<div class="col-md-6 mb-3">
											<label for="validationCustom08"><strong>Clasificación</strong></label>
											<select class="form-control controlInteligente2" id="idClasificacionMedicamentoA" name="idClasificacionMedicamentoA" required>
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
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar medicamento</button>
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


<!-- Modal para agregar datos del Medicamento-->
	<div class="modal fade" id="eliminarMedicamento" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">

				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este medicamento?</p>
				</div>

				<form action="<?php echo base_url()?>Botiquin/eliminar_medicamento" method="post">
					<input type="hidden" id="idMedicamento" name="idMedicamento">									
					<div class="text-center">
						<button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
						<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin Modal eliminar  datos del Medicamento-->

<script>
	function verDetalle(id, codigo,nombre, proveedor, precioC, precioV, tipo, clasif, stock, usados){
		var html = "";
				html += '<table class="table table-borderless">';
					html += '<tr>';
						html += '<td><strong>Código: </strong></td>';
						html += '<td>'+codigo+'</td>';
					html += '</tr>';
					html += '<tr>';
						html += '<td><strong>Medicamento: </strong></td>';
						html += '<td>'+nombre+'</td>';
						html += '<td><strong>Proveedor: </strong></td>';
						html += '<td>'+proveedor+'</td>';
					html += '</tr>';
					html += '<tr>';
						html += '<td><strong>Precio de compra: </strong></td>';
						html += '<td>$ '+precioC+'</td>';
						html += '<td><strong>Precio de venta: </strong></td>';
						html += '<td>$ '+precioV+'</td>';
					html += '</tr>';
					html += '<tr>';
						html += '<td><strong>Cantidad disponible: </strong></td>';
						html += '<td>'+stock+'</td>';
						html += '<td><strong>Cantidad usada: </strong></td>';
						html += '<td>'+usados+'</td>';
					html += '</tr>';
					html += '<tr>';
						html += '<td><strong>Tipo de medicamento: </strong></td>';
						html += '<td>'+tipo+'</td>';
						html += '<td><strong>Clasificación</strong></td>';
						html += '<td>'+clasif+'</td>';
					html += '</tr>';
			html += '</table>';

			document.getElementById("detalleMedicamento").innerHTML = html;
	}

	function actualizarMedicamento(id, codigo, nombre, proveedor, precioC, precioV, tipo, idproveedor, idclasif, vencimiento){
		document.getElementById("codigoMedicamentoAA").value = codigo;
		document.getElementById("codigoMedicamentoA").value = codigo;
		document.getElementById("nombreMedicamentoA").value = nombre;
		document.getElementById("idProveedorMedicamentoA").value = idproveedor;
		document.getElementById("precioCMedicamentoA").value = precioC;
		document.getElementById("precioVMedicamentoA").value = precioV;
		document.getElementById("tipoMedicamentoA").value = tipo;
		document.getElementById("idClasificacionMedicamentoA").value = idclasif;
		document.getElementById("idMedicamentoA").value = id;
		document.getElementById("venceMedicamento").value = vencimiento;
	}

	function eliminarMedicamento(id){
		document.getElementById("idMedicamento").value = id;
	}

	$(document).ready(function() {
        $(".controlInteligente").select2({
            theme: 'bootstrap4',
			dropdownParent: $("#agregarMedicamento")
        });

		$(".controlInteligente2").select2({
            theme: 'bootstrap4',
			dropdownParent: $("#actualizarMedicamento")
        });
    });

</script>