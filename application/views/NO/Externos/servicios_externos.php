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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-ambulance"></i> Servicios </a> </li>
                    <li class="breadcrumb-item"><a href="#">Servicios Externos</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de servicios externos</h6></div>
                        <div class="col-md-6 text-right">
                                <button class="btn btn-primary btn-sm" href="#agregarServicio" data-toggle="modal"><i class="fa fa-plus"></i> Agregar servicio</button>
                                <?php
									if(sizeof($externos) > 0){
								?>
								<a href="<?php echo base_url() ?>ServiciosExternos/lista_externos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a>
                                <!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
								<?php } ?>
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
							<?php
								if(sizeof($externos) > 0){
							?>
								<table id="" class="table table-striped thead-primary w-100 tablaPlus">
									<thead>
										<tr>
											<th class="text-center" scope="col">#</th>
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Tipo de entidad</th>
											<th class="text-center" scope="col">Proveedor</th>
											<th class="text-center" scope="col">Descripción</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>

										<?php
											$index = 0;
											$tipoEntidad = "";
											$proveedor = "";
											$datosServicioExterno = [];
											foreach ($externos as $externo) {
												$index++;
												if ($externo->tipoEntidad == 1) {
													$tipoEntidad = "Médico";
													$medico = $this->Externos_Model->detalleExternoMedico($externo->idEntidad);
													$proveedor = $medico->nombreMedico;
													
												}else{
													$tipoEntidad = "Otros proveedores";
													$medico = $this->Externos_Model->detalleExternoProveedor($externo->idEntidad);
													$proveedor = $medico->empresaProveedor;
												}
												// Creando arreglo de info
												$id ='"'.$externo->idExterno.'"';
												$nombreExterno ='"'.$externo->nombreExterno.'"';
												$tipoE ='"'.$tipoEntidad.'"';
												$prove ='"'.$proveedor.'"';
												$desc ='"'.$externo->descripcionExterno.'"';
										?>
											<tr>
												<td class="text-center" scope="row"><?php echo $index; ?></td>
												<td class="text-center"><?php echo $externo->nombreExterno; ?></td>
												<td class="text-center"><?php echo $tipoEntidad; ?></td>
												<td class="text-center"><?php echo $proveedor; ?></td>
												<td class="text-center"><?php echo $externo->descripcionExterno; ?></td>
												<td class="text-center">
												<?php
													echo "<a onclick='verExterno($nombreExterno, $tipoE, $prove, $desc)' href='#verMedicamento' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
													
													if($this->session->userdata("nivel") == 1){
														echo "<a onclick='actualizarExterno($id, $nombreExterno, $desc)' href='#actualizarServicio' data-toggle='modal'><i class='fas fa-pencil-alt ms-text-success'></i></a>";
														echo "<a onclick='eliminarExterno($id)' href='#aliminarServicio' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
													}
												?>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php 
							}else
								{
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


<!-- Modal para agregar datos del servicio externo-->
<div class="modal fade" id="agregarServicio" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Datos del servicio</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" method="post" action="<?php echo base_url();?>ServiciosExternos/guardar_externo" novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for="">Nombre</label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreExterno" name="nombreExterno" placeholder="Nombre del insumo y/o servicio" required>
                                            <div class="invalid-tooltip">
                                                Ingrese el nombre del insumo y/o servicio.
                                            </div>
										</div>
									</div>
                                    
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for="">Tipo de entidad</label>
										<div class="input-group">
                                            <select class="form-control" id="tipoEntidad" name="tipoEntidad" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="1">Médico</option>
                                                <option value="2">Otros proveedores</option>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Seleccione el tipo de entidad.
                                            </div>
										</div>
									</div>
								</div>
                                
								<div class="form-row">
									<div class="col-md-12 mb-2">
										<label for="validationCustom04">Entidad que brinda el servicio</label>
										<div class="input-group">
                                            <select class="form-control controlInteligente" id="idEntidad" name="idEntidad" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Ingrese la entidad que brinda el servicio.
                                            </div>
										</div>
									</div>
									
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for="validationCustom05">Descripción del insumo y/o servicio</label>
										<div class="input-group">
                                        <textarea id="descripcionExterno" name="descripcionExterno" class="form-control disableSelect" required></textarea>
                                            <div class="invalid-tooltip">
                                                Ingrese la descripción del servicio.
                                            </div>
										</div>

									</div>
								</div>

								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar servicio</button>
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
<!-- Fin Modal para agregar datos del servicio externo-->


<!-- Modal ver datos del servicio externo-->
<div class="modal fade" id="verMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Descripción del servicio</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body" id="detalleServicio">
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Fin Modal ver datos del servicio externo-->



<!-- Modal para actualizar datos del servicio externo-->
<div class="modal fade" id="actualizarServicio" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Datos del servicio</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>ServiciosExternos/actualizar_servicio" novalidate>
								
                                <div class="form-row">
									<div class="col-md-12">
										<label for="">Nombre</label>
										<div class="input-group">
											<input type="text" class="form-control" id="nombreExternoA" name="nombreExternoA" placeholder="Nombre del insumo y/o servicio" required>
                                            <div class="invalid-tooltip">
                                                Ingrese el nombre del insumo y/o servicio.
                                            </div>
										</div>
									</div>
                                    
								</div>

                                <div class="form-row">
									<div class="col-md-12">
										<label for="validationCustom05">Descripción del insumo y/o servicio</label>
										<div class="input-group">
                                        <textarea id="descripcionExternoA" name="descripcionExternoA" class="form-control" required></textarea>
                                            <div class="invalid-tooltip">
                                                Ingrese la descripción del servicio.
                                            </div>
										</div>

									</div>
								</div>

								<input type="hidden" class="form-control" id="idActualizar" name="idActualizar" />
								
								<div class="text-center">
                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar servicio</button>
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
<!-- Fin Modal actualizar datos del servicio externo-->


<!-- Modal para agregar datos del servicio externo-->
	<div class="modal fade" id="aliminarServicio" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">

				<div class="modal-header bg-danger">
					<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body text-center">
					<p class="h5">¿Estas seguro de eliminar los datos de este servicio?</p>
				</div>
				
				<form action="<?php echo base_url()?>ServiciosExternos/eliminar_externo" method="post">
					<input type="hidden" class="form-control" id="idEliminar" name="idEliminar" />
					<div class="text-center">
						<button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
						<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin Modal eliminar  datos del servicio externo-->


<script>
	$(document).ready(function() {
		$('.controlInteligente').select2({
			theme: "bootstrap4"
		});

		$("#idEntidad").prop('disabled', true); //Bloqueando la entidad
		$("#tipoEntidad").change(function() {
			$("#idEntidad").prop('disabled', false); // Desbloqueando la entidad
			$('#idEntidad').each(function(){
				$('#idEntidad option').remove();
			})
			var flag = $(this).val(); 
			$.ajax({
			url: "tipo_entidad",
			type: "GET",
			data: {id:flag},
			success:function(respuesta){
				var registro = eval(respuesta);
					if (registro.length > 0)
					{
						var entidad = "";
						for (var i = 0; i < registro.length; i++) 
						{
							if(flag == "1"){
								entidad += "<option value='"+ registro[i]["idMedico"] +"'>"+ registro[i]["nombreMedico"] +"</option>";
							}else{
								entidad += "<option value='"+ registro[i]["idProveedor"] +"'>"+ registro[i]["empresaProveedor"] +"</option>";
							}
						}
						$("#idEntidad").append(entidad);
					}

					console.log(entidad);
				}
			});

		});

	});

	function verExterno(nombreExterno, tipoEntidad, proveedor, desc){
		var html = "";
				html += '<table class="table table-borderless">';
					html += '<tr>';
						html += '<td><strong>Nombre: </strong></td>';
						html += '<td>'+nombreExterno+'</td>';
						html += '<td><strong>Tipo de entidad: </strong></td>';
						html += '<td>'+tipoEntidad+'</td>';
					html += '</tr>';
					html += '<tr>';
						html += '<td><strong>Proveedor: </strong></td>';
						html += '<td>'+proveedor+'</td>';
						html += '<td><strong>Descripción: </strong></td>';
						html += '<td>'+desc+'</td>';
					html += '</tr>';
					
			html += '</table>';

			document.getElementById("detalleServicio").innerHTML = html;
	}

	function actualizarExterno(id, nombre, descripcion){
		document.getElementById("idActualizar").value = id;
		document.getElementById("nombreExternoA").value = nombre;
		document.getElementById("descripcionExternoA").value = descripcion;
	}

	function eliminarExterno(id){
		document.getElementById("idEliminar").value = id;
	}
</script>