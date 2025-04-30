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

<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Pacientes</a> </li>
                    <li class="breadcrumb-item"><a href="#">Detalle paciente</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de pacientes</h6></div>
                        <div class="col-md-6 text-right">
                        <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Paciente/resultado_busqueda/<?php echo $paciente->nombrePaciente; ?>"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
                    <div class="alert-primary p-1 bordeContenedor">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nombre: </strong></td>
                                <td><?php echo $paciente->nombrePaciente; ?></td>
                                <td><strong>Edad: </strong></td>
                                <td><?php echo $paciente->edadPaciente; ?> Años</td>
                                <td><strong>Teléfono: </strong></td>
                                <td><?php echo $paciente->telefonoPaciente; ?></td>
                            </tr>

                            <tr>
                                <td><strong>DUI: </strong></td>
                                <td><?php echo $paciente->duiPaciente; ?></td>
                                <td><strong>Fecha de nacimiento: </strong></td>
                                <td><?php echo $paciente->nacimientoPaciente; ?></td>
                                <td><strong>Dirección: </strong></td>
                                <td><?php echo $paciente->direccionPaciente; ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Bloque para listar los expedientes -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <?php
                                    if(sizeof($expedientes) > 0){
                                ?>
                                    <div class="table-responsive">
                                        <h6 class="text-center mt-3">Lista de expedientes de este paciente</h6>
                                        <table id="tabla-pacientes" class="table table-striped thead-primary w-100">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">Código</th>
                                                    <th scope="col" class="text-center">Médico</th>
                                                    <th scope="col" class="text-center">Fecha</th>
                                                    <th scope="col" class="text-center">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($expedientes as $expediente) {
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $expediente->codigoHoja; ?></td>
                                                    <td class="text-center"><?php echo $expediente->nombreMedico; ?></td>
                                                    <td class="text-center"><?php echo $expediente->fechaHoja; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url(); ?>Hoja/detalle_hoja/<?php echo $expediente->idHoja; ?>/" class="text-info" target="blank"><i class="fas fa-file"></i></a>
                                                        <a href="<?php echo base_url(); ?>Hoja/resumen_hoja/<?php echo $expediente->idHoja; ?>/" class="text-info" target="blank"><i class="fas fa-file-pdf"></i></a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php
                                    }else{
                                        echo '<div class="alert alert-danger">
                                                <h6 class="text-center"><strong>No hay expedientes que mostrar.</strong></h6>
                                            </div>';
                                    }
                                ?>
                            </div>   
                        </div>
                    <!-- Fin bloque para listar los expedientes -->

                    <!-- Parte para mostrar el detalle de Ingresos y Ambulatorios -->

				</div>
            </div>
		</div>
	</div>
</div>
