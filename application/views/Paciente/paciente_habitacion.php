<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-hospital"></i> Habitaciones </a> </li>
                    <li class="breadcrumb-item"><a href="#">Pacientes por habitación</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <h6>Habitaciones ocupadas</h6>
                    <!--<a href="<?php echo base_url()?>Paciente/lista_pacientes" class="ms-text-primary">Lista de pacientes </a>-->
				</div>
				<div class="ms-panel-body">
                    <div class="table-responsive">
                        <table id="tabla-pacientes" class="table table-striped thead-primary w-100">
                            <thead>
                                <th class="text-center">Habitacion</th>
                                <th class="text-center">Paciente</th>
                                <th class="text-center">Direccion</th>
                                <th class="text-center">Fecha de ingreso</th>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($pacientes as $paciente) {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $paciente->numeroHabitacion; ?></td>
                                    <td class="text-center"><?php echo $paciente->nombrePaciente." ".$paciente->apellidoPaciente; ?></td>
                                    <td class="text-center"><?php echo $paciente->direccionPaciente; ?></td>
                                    <td class="text-center"><?php echo $paciente->fechaHoja; ?></td>
                                </tr>
                                <?php } ?>
                                    
                            </tbody>
                        </table>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

