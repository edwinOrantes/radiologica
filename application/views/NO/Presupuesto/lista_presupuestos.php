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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Cotización  </a> </li>
                    <li class="breadcrumb-item"><a href="#">Listado de cotizaciones</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <h6>Lista de cotizaciones</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
                        <div class="table-responsive mt-3">
							<?php
								if (sizeof($cotizaciones) > 0) {
								
							?>
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Paciente</th>
                                        <th class="text-center" scope="col">Médico</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Tipo</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
								$index = 0;
                                foreach ($cotizaciones as $cotizacion) {
                                    $index++;
                                    ?>
									<tr>
										<td class="text-center"><?php echo $index; ?></td>
										<td class="text-center"><?php echo $cotizacion->pacientePresupuesto; ?></td>
										<td class="text-center"><?php echo $cotizacion->nombreMedico; ?></td>
										<td class="text-center"><?php echo $cotizacion->fechaPresupuesto; ?></td>
										<td class="text-center"><?php echo $cotizacion->tipoPresupuesto; ?></td>
										<td class="text-center">
										<?php
											echo "<a href='".base_url()."Hoja/detalle_presupuesto/".$cotizacion->idPresupuesto."/' title='Ver detalle'><i class='far fa-eye ms-text-primary'></i></a>";
										?>
										</td>
									</tr>
								<?php } ?>
								</tbody>
                            </table>
							<?php }else{
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




<script>
function editaHoja(id, fecha, habitacion, medico){
	console.log(id, fecha, habitacion, medico);
	$("#fechaHoja").val(fecha);
	$("#idHabitacion").val(habitacion);
	$("#habitacionVieja").val(habitacion);
	$("#idMedico").val(medico);
	$("#idHoja").val(id);

}
</script>