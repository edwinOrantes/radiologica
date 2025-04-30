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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Botíquin </a> </li>
                    <li class="breadcrumb-item"><a href="#">Proveedores</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de proveedores</h6></div>
                        <div class="col-md-6 text-right">
                                <button class="btn btn-primary btn-sm" href="#agregarProveedor" data-toggle="modal"><i class="fa fa-plus"></i> Agregar provedor</button>
                                <a href="<?php echo base_url()?>Proveedor/lista_proveedores_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                                <!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
							<?php
								if (sizeof($proveedores) > 0) {
							?>
							<table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Código</th>
                                        <th class="text-center" scope="col">Empresa</th>
                                        <th class="text-center" scope="col">Razón social</th>
                                        <th class="text-center" scope="col">NIT</th>
                                        <th class="text-center" scope="col">Teléfono</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
										$index = 0;
										foreach ($proveedores as $proveedor) {
											$index++;
											$id ='"'.$proveedor->idProveedor.'"';
											$codigo = '"'.$proveedor->codigoProveedor.'"';
											$empresa = '"'.$proveedor->empresaProveedor.'"';
											$nrc = '"'.$proveedor->nrcProveedor.'"';
											$nit = '"'.$proveedor->nitProveedor.'"';
											$telefono = '"'.$proveedor->telefonoProveedor.'"';
											$direccion = '"'.$proveedor->direccionProveedor.'"';
											$social	= '"'.$proveedor->socialProveedor.'"';
									?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $proveedor->codigoProveedor?></td>
                                        <td class="text-center"><?php echo $proveedor->empresaProveedor?></td>
                                        <td class="text-center"><?php echo $proveedor->socialProveedor?></td>
                                        <td class="text-center"><?php echo $proveedor->nitProveedor?></td>
                                        <td class="text-center"><?php echo $proveedor->telefonoProveedor?></td>
                                        <td class="text-center">
										<?php
                                            echo "<a onclick='verDetalle($id, $codigo, $empresa, $nrc, $nit, $telefono, $direccion)' href='#verProveedor' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
                                            if($this->session->userdata("nivel") == 1){
												echo "<a onclick='actualizarProveedor($id, $codigo, $empresa, $nrc, $nit, $telefono, $direccion, $social)' href='#actualizarProveedor' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
												echo "<a onclick='eliminarProveedor($id)' href='#eliminarProveedor' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
											}
										?>

                                        </td>
                                    </tr>

									<?php } ?>
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
	<div class="modal fade" id="agregarProveedor" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white">Datos del proveedor</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Proveedor/guardar_proveedor" novalidate>
									
									<div class="form-row">


										<div class="col-md-6">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value=<?php echo $cod; ?> readonly>
												<input type="hidden" class="form-control" id="codigoProveedor" name="codigoProveedor" value=<?php echo $cod; ?> required>
												<div class="invalid-tooltip">
													Ingrese el codigo del proveedor.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""><strong>Nombre Comercial</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="empresaProveedor" name="empresaProveedor" placeholder="Nombre de la empresa" required>
												<div class="invalid-tooltip">
													Ingrese el nombre de la empresa.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""><strong>Razón social</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="socialProveedor" name="socialProveedor" placeholder="Razón social" required>
												<div class="invalid-tooltip">
													Ingrese la razón social.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""><strong>NRC</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nrcProveedor" name="nrcProveedor" placeholder="NRC del proveedor" required>
												<div class="invalid-tooltip">
													Ingrese el NRC del proveedor.
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<label for=""> <strong>NIT</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" data-mask="9999-999999-999-9" id="nitProveedor" name="nitProveedor" placeholder="Numero de NIT" required>
												<div class="invalid-tooltip">
													Ingrese el NIT del proveedor.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""> <strong>Teléfono</strong> </label>
											<div class="input-group">
												<input type="text" class="form-control" data-mask="9999-9999" id="telefonoProveedor" name="telefonoProveedor" placeholder="Teléfono del proveedor" required>
												<div class="invalid-tooltip">
													Ingrese el número de teléfono.
												</div>
											</div>
										</div>

										<div class="col-md-12">
											<label for=""><strong>Dirección</strong></label>
											<div class="input-group">
												<textarea  class="form-control disableSelect" id="direccionProveedor" name="direccionProveedor"></textarea>
												<div class="invalid-tooltip">
													Ingrese la dirección del proveedor.
												</div>
											</div>
										</div>

									</div>

									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar proveedor</button>
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
	<div class="modal fade" id="verProveedor" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white">Datos del proveedor</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body" id="detallePaciente">
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal ver datos del Medicamento-->



<!-- Modal para agregar datos del Medicamento-->
	<div class="modal fade" id="actualizarProveedor" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white">Datos del proveedor</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>Proveedor/actualizar_proveedor" novalidate>
									
									<div class="form-row">

										<div class="col-md-6">
											<label for=""><strong>Código</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" value=<?php echo $cod; ?> readonly>
												<input type="hidden" class="form-control" id="codigoProveedorA" name="codigoProveedor" value=<?php echo $cod; ?> required>
												<div class="invalid-tooltip">
													Ingrese el codigo del proveedor.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""><strong>Nombre Comercial</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="empresaProveedorA" name="empresaProveedor" placeholder="Nombre de la empresa" required>
												<div class="invalid-tooltip">
													Ingrese el nombre de la empresa.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											
											<label for=""><strong>Razón social</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="socialProveedorA" name="socialProveedor" placeholder="Razón social" required>
												<div class="invalid-tooltip">
													Ingrese la razón social.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""><strong>NRC</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" data-mask="999999-9" id="nrcProveedorA" name="nrcProveedor" placeholder="NRC del proveedor" required>
												<div class="invalid-tooltip">
													Ingrese el NRC del proveedor.
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<label for=""> <strong>NIT</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" data-mask="9999-999999-999-9" id="nitProveedorA" name="nitProveedor" placeholder="Numero de NIT" required>
												<div class="invalid-tooltip">
													Ingrese el NIT del proveedor.
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<label for=""> <strong>Teléfono</strong> </label>
											<div class="input-group">
												<input type="text" class="form-control" data-mask="9999-9999" id="telefonoProveedorA" name="telefonoProveedor" placeholder="Teléfono del proveedor" required>
												<div class="invalid-tooltip">
													Ingrese el número de teléfono.
												</div>
											</div>
										</div>

										<div class="col-md-12">
											<label for=""><strong>Dirección</strong></label>
											<div class="input-group">
												<textarea  class="form-control" id="direccionProveedorA" name="direccionProveedor" required></textarea>
												<div class="invalid-tooltip">
													Ingrese la dirección del proveedor.
												</div>
											</div>
										</div>

									</div>

									<input type="hidden" class="form-control" id="idProveedorA" name="idProveedor">
									<div class="text-center">
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar proveedor</button>
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


<!-- Modal para agregar datos del Medicamento-->
	<div class="modal fade" id="eliminarProveedor" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">

				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este proveedor?</p>
				</div>
				
				<form action="<?php echo base_url() ?>Proveedor/eliminar_proveedor" method="post">
					<input type="hidden" id="proveedorEliminar" name="proveedorEliminar"/>
					<div class="text-center">
						<button class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
						<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!-- Fin Modal eliminar  datos del Medicamento-->


<script>
	function verDetalle(id, codigo, empresa, nrc, nit, telefono, direccion){
		var html = "";
				html += '<table class="table table-borderless">';
					html += '<tr>';
						html += '<td><strong>Código: </strong></td>';
						html += '<td>'+codigo+'</td>';
						html += '<td><strong>Empresa: </strong></td>';
						html += '<td>'+empresa+'</td>';
					html += '</tr>';

					html += '<tr>';
						html += '<td><strong>NRC: </strong></td>';
						html += '<td>'+nrc+'</td>';
						html += '<td><strong>NIT: </strong></td>';
						html += '<td>'+nit+'</td>';
					html += '</tr>';
					html += '<tr>';
						html += '<td><strong>Teléfono: </strong></td>';
						html += '<td>'+telefono+'</td>';
						html += '<td><strong>Dirección: </strong></td>';
						html += '<td>'+direccion+'</td>';
					html += '</tr>';
			html += '</table>';

			document.getElementById("detallePaciente").innerHTML = html;
	}

	function actualizarProveedor(id, codigo, empresa, nrc, nit, telefono, direccion, social){
		document.getElementById("idProveedorA").value = id;
		document.getElementById("codigoProveedorA").value = codigo;
		document.getElementById("empresaProveedorA").value = empresa;
		document.getElementById("nrcProveedorA").value = nrc;
		document.getElementById("nitProveedorA").value = nit;
		document.getElementById("telefonoProveedorA").value = telefono;
		document.getElementById("direccionProveedorA").value = direccion;
		document.getElementById("socialProveedorA").value = social;

	}

	function eliminarProveedor(id){
		document.getElementById("proveedorEliminar").value = id;
	}
</script>