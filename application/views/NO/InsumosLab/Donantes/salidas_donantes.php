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
						<li class="breadcrumb-item"><a href="#">Lista salidas </a></li>
					</ol>
				</nav>

				<div class="ms-panel">

					<div class="ms-panel-header">
						<div class="row">
							<div class="col-md-6"><h6>Listado de salidas de sangre</h6></div>
							<div class="col-md-6 text-right">
                                    <a href="#agregarSalida" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar salida </a>
									<!-- <a href="<?php echo base_url()?>InsumosLab/insumos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
							</div>
						</div>
					</div>

					<div class="ms-panel-body">
						<div class="row">
							<div class="table-responsive mt-3">
								<?php
									if (sizeof($salidas) > 0) {
								?>
								<table id="" class="table table-striped thead-primary w-100 insumos-lab">
									<thead>
										<tr>
											<th class="text-center" scope="col">Código</th>
											<th class="text-center" scope="col">Paciente</th>
											<th class="text-center" scope="col">Edad</th>
											<th class="text-center" scope="col">Fecha</th>
											<th class="text-center" scope="col">Insumo</th>
											<th class="text-center" scope="col">Cantidad</th>
											<th class="text-center" scope="col">Médico</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
                                        <?php
                                            $index = 0;
                                            $cero = 0;
											$hoy = date('Y-m-d');
                                            foreach ($salidas as $salida) {
                                        ?>
                                            <tr class="">
                                                <td class="text-center"><?php echo $salida->codigoDonanteSalida; ?></td>
                                                <td class="text-center"><?php echo $salida->nombrePaciente; ?></td>
                                                <td class="text-center"><?php echo $salida->edadPaciente; ?> Años</td>
                                                <td class="text-center"><?php echo $salida->fecha; ?></td>
                                                <td class="text-center"><?php echo $salida->nombreInsumoLab; ?></td>
                                                <td class="text-center"><?php echo $salida->cantidadInsumo; ?></td>
                                                <td class="text-center"><?php echo $salida->nombreMedico; ?></td>
                                                <td class="text-center">
													<input type="hidden" value="<?php echo $salida->nombrePaciente; ?>" class="paciente">
													<input type="hidden" value="<?php echo $salida->edadPaciente; ?>" class="edad">
													<input type="hidden" value="<?php echo $salida->idInsumo; ?>" class="insumo">
													<input type="hidden" value="<?php echo $salida->cantidadInsumo; ?>" class="cantidad">
													<input type="hidden" value="<?php echo $salida->precioInsumo; ?>" class="precio">
													<input type="hidden" value="<?php echo $salida->areaDonanteSalida; ?>" class="area">
													<input type="hidden" value="<?php echo $salida->idMedico; ?>" class="medico">
													<input type="hidden" value="<?php echo $salida->idDonanteSalida; ?>" class="fila">

													<input type="hidden" value="<?php echo $salida->numeroBolsa; ?>" class="bolsa">
													<input type="hidden" value="<?php echo $salida->pruebaCruzada; ?>" class="prueba">
													
                                                    <?php
                                                        echo "<a title='Editar datos paciente' href='#editarSalida' data-toggle='modal' class='btnEditarSalida'><i class='fas fa-edit ms-text-primary'></i></a>";
                                                        echo "<a title='Ver detalle' href='".base_url()."InsumosLab/comprobante_salida/".$salida->idDonanteSalida."/' target='blank'><i class='fas fa-file-pdf ms-text-danger'></i></a>";
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

<!-- Modal para agregar salida-->
	<div class="modal fade" id="agregarSalida" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del paciente </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/guardar_salida" novalidate>
									
									<div class="form-row">
										<div class="col-md-6 2mb-2">
											<label for=""><strong>Paciente</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-6 mb-2">
											<label for=""><strong>Edad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="edadPaciente" name="edadPaciente" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Area</strong></label>
											<div class="input-group">
												<select name="areaSalida" id="areaSalida" class="form-control" required>
                                                    <option value="">::Seleccionar::</option>
                                                    <option value="Enfermeria">Enfermeria</option>
                                                    <option value="ISBM">ISBM</option>
                                                    <option value="Hemodiálisis ">Hemodiálisis </option>
													<option value="Urologica ">Urologica</option>
                                                </select>
												<div class="invalid-tooltip">
													Seleccionar el area.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Insumo</strong></label>
											<div class="input-group">
												<select class="form-control" id="insumoSalida" name="insumoSalida" required>
													<option value="">:: Seleccionar ::</option>
													<?php
														foreach ($insumos as $row) {
															echo '<option value="'.$row->idInsumoLab.'">'.$row->nombreInsumoLab.'</option>';
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Ingrese el insumo.
												</div>
											</div>
										</div>

                                        <div class="col-md-6 mb-2">
											<label for=""><strong>Cantidad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="cantidadPaciente" name="cantidadPaciente" required>
												<div class="invalid-tooltip">
													Ingrese la cantidad.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Precio</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioSalida" name="precioSalida" required>
												<div class="invalid-tooltip">
													Ingrese el precio.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Número Bolsa</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="numeroBolsa" name="numeroBolsa" required>
												<div class="invalid-tooltip">
													Ingrese el número de bolsa.
												</div>
											</div>
										</div>

                                        <div class="col-md-6 mb-2">
											<label for=""><strong>Médico</strong></label>
											<div class="input-group">
												<select class="form-control" id="medicoSalida" name="medicoSalida" required>
													<option value="">:: Seleccionar ::</option>
													<?php
														foreach ($medicos as $row) {
															echo '<option value="'.$row->idMedico.'">'.$row->nombreMedico.'</option>';
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Ingrese el medico.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-2">
											<label for=""><strong>Prueba cruzada</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="pruebaCruzada" name="pruebaCruzada" required>
												<div class="invalid-tooltip">
													Ingrese la prueba cruzada.
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
<!-- Fin Modal para agregar salida-->

<!-- Modal para editar la salida-->
	<div class="modal fade" id="editarSalida" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog ms-modal-dialog-width">
			<div class="modal-content ms-modal-content-width">
				<div class="modal-header  ms-modal-header-radius-0">
					<h6 class="modal-title text-white"><i class="fa fa-file"></i> Datos del paciente </h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>

				<div class="modal-body p-0 text-left">
					<div class="col-xl-12 col-md-12">
						<div class="ms-panel ms-panel-bshadow-none">
							<div class="ms-panel-body">
								<form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/actualizar_salida" novalidate>
									
									<div class="form-row">
										<div class="col-md-6 2mb-2">
											<label for=""><strong>Paciente</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="nombrePacienteU" name="nombrePaciente" required>
												<div class="invalid-tooltip">
													Ingrese un nombre.
												</div>
											</div>
										</div>
										
                                        <div class="col-md-6 mb-2">
											<label for=""><strong>Edad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="edadPacienteU" name="edadPaciente" required>
												<div class="invalid-tooltip">
													Ingrese la edad.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Area</strong></label>
											<div class="input-group">
												<select name="areaSalida" id="areaSalidaU" class="form-control" required>
                                                    <option value="">::Seleccionar::</option>
                                                    <option value="Enfermeria">Enfermeria</option>
                                                    <option value="ISBM">ISBM</option>
                                                    <option value="Hemodiálisis ">Hemodiálisis </option>
													<option value="Urologica ">Urologica</option>
                                                </select>
												<div class="invalid-tooltip">
													Seleccionar el area.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Insumo</strong></label>
											<div class="input-group">
												<select class="form-control" id="insumoSalidaU" name="insumoSalida" required>
													<option value="">:: Seleccionar ::</option>
													<?php
														foreach ($insumos as $row) {
															echo '<option value="'.$row->idInsumoLab.'">'.$row->nombreInsumoLab.'</option>';
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Ingrese el insumo.
												</div>
											</div>
										</div>

                                        <div class="col-md-6 mb-2">
											<label for=""><strong>Cantidad</strong></label>
											<div class="input-group">
												<input type="number" class="form-control" id="cantidadPacienteU" name="cantidadPaciente" required>
												<div class="invalid-tooltip">
													Ingrese la cantidad.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Precio</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="precioSalidaU" name="precioSalida" required>
												<div class="invalid-tooltip">
													Ingrese el precio.
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-2">
											<label for=""><strong>Número Bolsa</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="numeroBolsaU" name="numeroBolsa" required>
												<div class="invalid-tooltip">
													Ingrese el número de bolsa.
												</div>
											</div>
										</div>

                                        <div class="col-md-6 mb-2">
											<label for=""><strong>Médico</strong></label>
											<div class="input-group">
												<select class="form-control" id="medicoSalidaU" name="medicoSalida" required>
													<option value="">:: Seleccionar ::</option>
													<?php
														foreach ($medicos as $row) {
															echo '<option value="'.$row->idMedico.'">'.$row->nombreMedico.'</option>';
														}
													?>
												</select>
												<div class="invalid-tooltip">
													Ingrese el medico.
												</div>
											</div>
										</div>

										<div class="col-md-12 mb-2">
											<label for=""><strong>Prueba cruzada</strong></label>
											<div class="input-group">
												<input type="text" class="form-control" id="pruebaCruzadaU" name="pruebaCruzada" required>
												<div class="invalid-tooltip">
													Ingrese la prueba cruzada.
												</div>
											</div>
										</div>

									</div>

									<div class="text-center">
                                        <input type="hidden" class="form-control" id="filaU" name="fila" required>
										<button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar </button>
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
<!-- Fin Modal para editar la salida-->


<script>
    $(document).ready(function() {
        $("#medicoSalida").select2({
            theme: "bootstrap4",
            dropdownParent: $("#agregarSalida")
        });
    });


	$(document).on('click', '.btnEditarSalida', function(e) {
		e.preventDefault();
        $("#nombrePacienteU").val($(this).closest('tr').find(".paciente").val());
        $("#edadPacienteU").val($(this).closest('tr').find(".edad").val());
        $("#areaSalidaU").val($(this).closest('tr').find(".area").val());
        $("#insumoSalidaU").val($(this).closest('tr').find(".insumo").val());
        $("#cantidadPacienteU").val($(this).closest('tr').find(".cantidad").val());
        $("#precioSalidaU").val($(this).closest('tr').find(".precio").val());
        $("#medicoSalidaU").val($(this).closest('tr').find(".medico").val());
        $("#filaU").val($(this).closest('tr').find(".fila").val());

        $("#numeroBolsaU").val($(this).closest('tr').find(".bolsa").val());
        $("#pruebaCruzadaU").val($(this).closest('tr').find(".prueba").val());
	});

	$(document).on('click', '.btnEditarDonante', function(e) {
		e.preventDefault();
		$("#nombreDonanteU").val($(this).closest('tr').find(".nombre").val());
		$("#edadDonanteU").val($(this).closest('tr').find(".edad").val());
		$("#duiDonanteU").val($(this).closest('tr').find(".dui").val());
		$("#idDonanteU").val($(this).closest('tr').find(".idDonante").val());

	});
</script>
