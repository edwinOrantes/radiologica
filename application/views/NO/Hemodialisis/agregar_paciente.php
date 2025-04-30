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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-user"></i> Paciente</a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar pacientes</a></li>
                </ol>
            </nav>
            
			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <div class="col-md-6">
                        <h6>Datos del paciente</h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="<?php echo base_url(); ?>Hemodialisis/agregar_cita/" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Crear cita</a>
                    </div>
                    <!-- <a href="<?php echo base_url()?>Paciente/lista_pacientes" class="btn btn-outline-primary btn-sm ms-text-primary"><i class="fa fa-users"></i> Lista de pacientes </a> -->
				</div>
				<div class="ms-panel-body">
					<form class="needs-validation" method="post" action="<?php echo base_url()?>Hemodialisis/guardar_paciente" novalidate>
						
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
                                <input type="date" class="form-control" id="nacimientoPaciente" name="nacimientoPaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la fecha de nacimiento del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for=""><strong>Tipeo</strong></label>
                                <input class="form-control" id="tipeoPaciente" name="tipeoPaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el tipeo sanguineo del paciente.
                                </div>
                            </div>

                        </div>
                        

                        <div class="row">

                            <div class="form-group col-md-12">
                                <label for=""><strong>Dirección</strong></label>
                                <input class="form-control" id="direccionPaciente" name="direccionPaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar dirección del paciente.
                                </div>
                            </div>

                        </div>


                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Guardar paciente </button>
                            <button type="reset" class="btn btn-default has-icon"><i class=" fa fa-times"></i> Cancelar </button>
                        </div>
                    
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal validar paciente-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="pacienteExistente" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Este paciente ya esta registrado, deseas crearle una hoja de cobro ?</p>
                    </div>

                    <div class="text-center">
                        <a href="<?php echo base_url(); ?>Hoja/" class="btn btn-danger"> <i class="fa fa-file-pdf"></i>  Crear Hoja</a>
                        <a href="<?php echo base_url(); ?>Paciente/agregar_pacientes" class="btn btn-default"> <i class="fa fa-times"></i>  Cancelar </a>
                    </div>

            </div>
        </div>
    </div>
<!-- Fin Modal validad paciente-->