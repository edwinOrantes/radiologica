<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Pacientes</a> </li>
                    <li class="breadcrumb-item"><a href="#">Lista pacientes</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de pacientes</h6></div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Paciente/lista_pacientes"><i class="fa fa-arrow-left"></i> Volver </a>
							<!-- <a href="<?php echo base_url()?>Paciente/lista_pacientes_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
							<!--<button class="btn btn-success btn-sm"><i class="fa fa-file-pdf"></i> Ver PDF</button>-->
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
						<div class="table-responsive">
							<?php
								if (sizeof($pacientes) > 0) {
								$index= 0;
							?>
							<table id="tabla-pacientes" class="table table-striped thead-primary w-100">
								<thead>
									<th class="text-center">Nombre</th>
									<th class="text-center">Edad</th>
									<!-- <th class="text-center">Ocupación</th>
									<th class="text-center">Estado civil</th> -->
									<th class="text-center">Teléfono</th>
									<th class="text-center">DUI</th>
									<th class="text-center">Dirección</th>
									<th class="text-center">Opción</th>
								</thead>
								<tbody>
							<?php
								foreach ($pacientes as $paciente) {
									$index++;
									$id ='"'.$paciente->idPaciente.'"';
									$nombre = '"'.$paciente->nombrePaciente.'"';
									//$apellido = '"'.$paciente->apellidoPaciente.'"';
									$edad = '"'.$paciente->edadPaciente.'"';
									$telefono = '"'.$paciente->telefonoPaciente.'"';
									//$ocupacion = '"'.$paciente->ocupacionPaciente.'"';
									//$sexo = '"'.$paciente->sexoPaciente.'"';
									$dui = '"'.$paciente->duiPaciente.'"';
									//$estado = '"'.$paciente->estadoPaciente.'"';
									$nacimiento = '"'.$paciente->nacimientoPaciente.'"';
									/* $municipio = '"'.$paciente->nombreMunicipio.'"';
									$idMun = '"'.$paciente->idMunicipio.'"';
									$idDepto = '"'.$paciente->idDepartamento.'"';
									$departamento = '"'.$paciente->nombreDepartamento.'"';
									$medico = '"'.$paciente->idMedico.'"'; */
									$direccion = '"'.$paciente->direccionPaciente.'"';
							?>
							
										<tr>
											<td class="text-center"><?php echo $paciente->nombrePaciente; ?></td>
											<td class="text-center"><?php echo $paciente->edadPaciente ?> años</td>
											<!-- <td class="text-center"><?php //echo $paciente->ocupacionPaciente ?></td>
											<td class="text-center"><?php //echo $paciente->estadoPaciente ?></td> -->
											<td class="text-center"><?php echo $paciente->telefonoPaciente ?></td>
											<td class="text-center"><?php echo $paciente->duiPaciente ?></td>
											<td class="text-center"><?php echo wordwrap($paciente->direccionPaciente,25,"<br>\n"); ?></td>
											<td class="text-center"><?php
												//echo "<a onclick='verDetalle($id, $nombre, $apellido, $edad, $telefono, $ocupacion, $sexo, $dui, $estado, $nacimiento, $municipio, $departamento, $direccion)' title='Ver detalles del médico' href='#verPaciente' data-toggle='modal'><i class='fas fa-eye ms-text-primary'></i></a>";
												echo "<a title='Ver detalles del médico' href='".base_url()."Paciente/detalle_paciente/".$paciente->idPaciente."'><i class='fas fa-eye ms-text-primary'></i></a>";
												echo "<a title='Actualizar datos del médico' href='".base_url()."Paciente/editar_paciente/".$paciente->idPaciente."' target='blank'><i class='fas fa-edit ms-text-success'></i></a>";
												// echo "<a onclick='actualizarPaciente($id, $nombre, $edad, $telefono, $dui, $nacimiento, $direccion)' title='Actualizar datos del médico' href='#actualizarP' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
												//echo "<a onclick='eliminarPaciente($id)' title='Eliminar del médico' href='#pacienteAEliminar' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
											?>
											</td>
										</tr>
										
									

							<?php }
								}else
								{
									echo '<div class="alert alert-danger">
											<h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
										</div>';
								}
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

<!-- Modal ver datos del paciente-->
	<div class="modal fade" id="verPaciente" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i>  Datos del paciente</h4>
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
<!-- Fin Modal ver datos del paciente-->


<!-- Modal actualizar paciente-->
	<div class="modal fade" id="actualizarP" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h4 class="modal-title text-white"></i>  Datos del paciente</h4>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>Paciente/actualizar_paciente" novalidate>
							
								<div class="row">
									<div class="form-group col-md-6">
										<label for=""><strong>Nombre Completo</strong></label>
										<input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" required>
										<div class="invalid-tooltip">
											Debes ingresar el nombre del paciente.
										</div>
									</div>

									<div class="form-group col-md-6">
										<label for=""><strong>Edad</strong></label>
										<input type="number" class="form-control numeros" min="0" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>
										<div class="invalid-tooltip">
											Debes ingresar la edad del paciente.
										</div>
									</div>

								</div>

								<div class="row">
									
									<div class="form-group col-md-6">
										<label for=""><strong>Teléfono</strong></label>
										<input type="text" class="form-control" data-mask="9999-9999" id="telefonoPaciente" name="telefonoPaciente" placeholder="Teléfono del paciente" required>
										<div class="invalid-tooltip">
											Debes ingresar el teléfono del paciente.
										</div>
									</div>

									<div class="form-group col-md-6">
										<label for=""><strong>DUI</strong></label>
										<input type="text" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>
										<div class="invalid-tooltip">
											Debes ingresar el DUI del paciente.
										</div>
									</div>

								</div>


								<div class="row">

									<div class="form-group col-md-6">
										<label for=""><strong>Fecha de nacimiento</strong></label>
										<input type="date" class="form-control" id="nacimientoPaciente" name="nacimientoPaciente">
										<div class="invalid-tooltip">
											Debes ingresar la fecha de nacimiento del paciente.
										</div>
									</div>

									<div class="form-group col-md-6">
										<label for=""><strong>Dirección</strong></label>
										<input class="form-control" id="direccionPaciente" name="direccionPaciente" required>
										<div class="invalid-tooltip">
											Debes ingresar dirección del paciente.
										</div>
									</div>

								</div>


								<div class="form-group text-center mt-3">
									<input type="hidden" class="form-control" id="idPaciente" name="idPaciente">
									<button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Actualizar paciente </button>
									<button type="reset" class="btn btn-default has-icon" data-dismiss="modal"><i class=" fa fa-times"></i> Cancelar </button>
								</div>
							
							</form>

							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<!-- Fin Modal actualizar paciente-->

<!-- Modal para eliminar datos paciente-->
<div class="modal fade" id="pacienteAEliminar" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">

			<div class="modal-header bg-danger">
				<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body text-center">
				<p class="h5">¿Estas seguro de eliminar los datos de este paciente?</p>
			</div>
			<form action="<?php echo base_url() ?>Paciente/eliminar_paciente" method="post">								
				<div class="text-center">
					<input type="hidden" id="pacienteE" name="idPaciente"/>
					<button class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Eliminar</button>
					<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				</div>
			</form>									
		</div>
	</div>
</div>
<!-- Fin Modal eliminar datos del paciente-->

<script>
	function verDetalle(id, nombre, apellido, edad, telefono, ocupacion, sexo, dui, estado, nacimiento, municipio, departamento, direccion){
			var html = "";
			html += '<table class="table table-borderless">';
				html += '<tr>';
					html += '<td><strong>Nombre: </strong></td>';
					html += '<td>'+nombre+' '+apellido+'</td>';
					html += '<td><strong>Edad: </strong></td>';
					html += '<td>'+edad+'</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td><strong>Ocupación: </strong></td>';
					html += '<td>'+ocupacion+'</td>';
					html += '<td><strong>Teléfono: </strong></td>';
					html += '<td>'+telefono+'</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td><strong>Estado civil: </strong></td>';
					html += '<td>'+estado+'</td>';
					html += '<td><strong>Sexo: </strong></td>';
					html += '<td>'+sexo+'</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td><strong>DUI: </strong></td>';
					html += '<td>'+dui+'</td>';
					html += '<td><strong>Fecha de nacimiento: </strong></td>';
					html += '<td>'+nacimiento+'</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td><strong>Dirección: </strong></td>';
					html += '<td>'+direccion+', '+municipio+','+departamento+'</td>';
				html += '</tr>';
		html += '</table>';

		document.getElementById("detallePaciente").innerHTML = html;
	}

	function actualizarPaciente(id, nombre, edad, telefono, dui, nacimiento, direccion){
		document.getElementById("idPaciente").value = id;
		document.getElementById("nombrePaciente").value = nombre;
		//document.getElementById("apellidoPaciente").value = apellido;
		document.getElementById("edadPaciente").value = edad;
		document.getElementById("telefonoPaciente").value = telefono;
		/* document.getElementById("ocupacionPaciente").value = ocupacion;
		document.getElementById("sexoPaciente").value = sexo; */
		document.getElementById("duiPaciente").value = dui;
		//document.getElementById("estadoPaciente").value = estado;
		document.getElementById("nacimientoPaciente").value = nacimiento;
		/* document.getElementById("municipioPaciente").value = idMun;
		document.getElementById("departamentoPaciente").value = idDepto;
		document.getElementById("medicoPaciente").value = medico; */
		document.getElementById("direccionPaciente").value = direccion;
		
	}

	function eliminarPaciente(id){
		document.getElementById("pacienteE").value = id;
	}
</script>