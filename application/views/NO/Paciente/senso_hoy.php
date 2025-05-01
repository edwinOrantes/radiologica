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
                        <div class="col-md-6"><h6>Detalles del censo a este dia </h6></div>
                        
                    </div>
				</div>
				<div class="ms-panel-body">
				<?php
					
				
					if(sizeof($detalleSenso) == 0){
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
										<th scope="col" class="text-center">Observación</th>
										<th scope="col" class="text-center">Estado</th>
									</tr>
								</thead>
								<tbody>

									<?php
										// Ingresos perteneciente a censos diario
										foreach ($detalleSenso as $senso) {

										 $estado = "";
										 $clase = "";
										 if($senso->estadoPaciente == 1 || $senso->estadoPaciente == 2){
											$estado = "Ingresado";
											$clase = "badge badge-outline-success";
										}else{
											$estado = "De alta";
											$clase = "badge badge-outline-primary";
										 }

									?>
									<tr>
										<td class="text-center"><?= $senso->numeroHabitacion?></td>
										<td class="text-center"><?= $senso->nombrePaciente ?></td>
										<td class="text-center"><?= $senso->dietaPaciente ?></td>
										<td class="text-center"><?= $senso->medicoPaciente ?></td>
										<td class="text-center"><?= $senso->observacionPaciente ?></td>
										<td class="text-center"><span class="<?= $clase?>"><?= $estado?></span></td>
									</tr>

								<?php
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

