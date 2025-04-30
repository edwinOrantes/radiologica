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
                        <div class="col-md-6"><h6>Lista de expedientes disponibles</h6></div>
                        
                    </div>
				</div>
				<div class="ms-panel-body">
				<?php
					
				
					if(sizeof($detalle_senso) == 0){
						$fecha = date('Y-m-d');
						/*echo '<div class="alert alert-success">
							<h6 class="text-center"><strong>Aun no hay datos que mostrar.</strong></h6>
						</div>';*/
					}else{
				?>

						<div class="table-responsive">

							<table class="table table-hover thead-primary tablaPlus" id="detalleSenso">
								<thead>
									<tr>
										<th scope="col" class="text-center">Habitación</th>
										<th scope="col" class="text-center">Nombre del paciente</th>
										<th scope="col" class="text-center">Dieta</th>
										<th scope="col" class="text-center">Médico </th>
										<th scope="col" class="text-center">Estado</th>
										<th scope="col" class="text-center">Opción</th>
									</tr>
								</thead>
								<tbody>

									<?php
										// Ingresos perteneciente a censos diario
										foreach ($detalle_senso as $row) {
											if($row->idHoja > 0){
												$estado = "";
												$clase = "";
												if($row->estadoPaciente == 1 || $row->estadoPaciente == 2){
													$estado = "Ingresado";
													$clase = "badge badge-outline-success";
												}else{
													$estado = "De alta";
													$clase = "badge badge-outline-primary";
												}

									?>
									<tr>
										<td class="text-center"><?= $row->numeroHabitacion?></td>
										<td class="text-center"><?= $row->nombrePaciente ?></td>
										<td class="text-center"><?= $row->dietaPaciente ?></td>
										<td class="text-center"><?= $row->medicoPaciente ?></td>
										<td class="text-center"><span class="<?= $clase?>"><?= $estado?></span></td>
										<td class="text-center">

											<?php
												echo '<a href="'.base_url().'Enfermeria/Expediente/detalle_expediente/'.$row->idHoja.'/" class="text-primary"><i class="fa fa-file"></i></a>';
											?>

											
										</td>
									</tr>

								<?php
										}
									}
									
								?>
								</tbody>
							</table>
						
						</div>

					<?php } ?> <!-- Fin de pacientes parte 1-->

				</div>
			</div>
		</div>

	</div>
</div>

