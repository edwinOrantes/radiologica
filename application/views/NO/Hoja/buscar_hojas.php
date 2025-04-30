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
                    <li class="breadcrumb-item"><a href="#">Buscar paciente</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            
                        </div>
                    </div>
				</div>
               
            
				<div class="ms-panel-body">
					<form class="form" id="" action="<?php echo base_url(); ?>Hoja/detalle_historial" method="post">
                        <div class="form-row">
                            <div class="col-md-10">
                                <label for=""><strong>Paciente:</strong></label>
                                <div class="input-group">
                                <input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" required>
                                    <div class="invalid-tooltip">
                                        Ingrese el nombre del paciente a buscar.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary mt-4">Buscar <i class="fa fa-search"></i></button>
                            </div>

                        </div>
                    </form>
				</div>
            </div>
		</div>
	</div>
</div>