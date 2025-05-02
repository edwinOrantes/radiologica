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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-user"></i> Consulta </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar pacientes</a></li>
                </ol>
            </nav>
            
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Datos del paciente</h6></div>
                        <div class="col-md-6 text-right"></div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<form class="needs-validation" method="post" action="<?php echo base_url()?>Consulta/guardar_paciente" novalidate>
                        
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label for=""><strong>Nombre Completo</strong></label>
                                <input type="text" class="form-control" id="pacienteConsulta" name="pacienteConsulta" placeholder="Nombre del paciente" list='recomendacionesPacientes'  required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el nombre del paciente.
                                </div>
                            </div>

                            
                            <div class="form-group col-md-12">
                                <label for=""><strong>Médico</strong></label>
                                <select class="form-control controlInteligente" id="medicoConsulta" name="medicoConsulta" required>
                                    <option value="">..:: Seleccionar ::..</option>
                                    <?php foreach ($medicos as $row) { ?>
                                        <option value="<?php echo $row->idMedico;  ?>"><?php echo $row->nombreMedico;  ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-tooltip">
                                    Debes seleccionar un tipo de referencia.
                                </div>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for=""><strong>Tipo de referencia</strong></label>
                                <select class="form-control" id="referenciaConsulta" name="referenciaConsulta" required>
                                    <option value="">..:: Seleccionar ::..</option>
                                    <option value="Publica">Pública</option>
                                    <option value="Privada">Privada</option>
                                </select>
                                <div class="invalid-tooltip">
                                    Debes seleccionar un tipo de referencia.
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary has-icon"> Continuar <i class="fa fa-arrow-right"></i></button>
                        </div>
                    
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
     $(document).ready(function() {
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
    });
</script>