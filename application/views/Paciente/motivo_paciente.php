<style>
    #dvPaquete, #dvProcedimiento{
        display: none;
    }
</style>

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

			<!-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Pacientes</a> </li>
                    <li class="breadcrumb-item"><a href="#">Detalle paciente</a></li>
                </ol>
            </nav> -->

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert-primary bordeContenedor">
                                <table class="table">
                                    <tr>
                                        <td><strong>Nombre: </strong></td>
                                        <td><?php echo $paciente->nombrePaciente; ?></td>
                                        <td><strong>Edad: </strong></td>
                                        <td><?php echo $paciente->edadPaciente; ?> Años</td>
                                        <td><strong>DUI: </strong></td>
                                        <td><?php echo $paciente->duiPaciente; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                        <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Paciente/agregar_pacientes"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
                    

                    <!-- Si viene solo sera hoja de cobro -->
                        <div class="">
                            <form class="needs-validation" method="post" action="<?php echo base_url()?>Hoja/crear_hoja" novalidate>
                                <div class="ms-panel-header row">
                                    <div class="col-md-6"><h6> Información de la hoja de cobro </h6></div>
                                    <div class="col-md-6 text-right">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <span> Paquete </span>
                                                <label class="ms-switch">
                                                    <input type="checkbox" id="esPaquete" name="esPaquete" value="esPaquete">
                                                    <span class="ms-switch-slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- <input type="text" class="form-control mb-2"> -->
                                                <select class="form-control form-select" name="pagaMedico" aria-label="¿Cancelará médico?">
                                                    <option value="0" selected>Cancelará paciente</option>
                                                    <option value="1">Cancelará médico</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="ms-panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="form-row">
                                                            <div class="col-md-6">
                                                                <label for=""><strong>Código</strong></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" value="<?php echo $codigo; ?>" id="codigoHoja" readonly/>
                                                                    <input type="hidden" class="form-control" value="<?php echo $codigo; ?>" name="codigoHoja"/>
                                                                    <div class="invalid-tooltip">
                                                                        Seleccione un tipo de documento.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6" id="dvPaquete">
                                                                <label for=""><strong>Total del paquete</strong></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" value="0" id="txtTotalPaquete" name="totalPaquete" placeholder="Total del paquete"/>
                                                                    <div class="invalid-tooltip">
                                                                        Agregue el monto toal del paquete.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-md-6">
                                                                <label for=""><strong>Tipo</strong></label>
                                                                <div class="input-group">
                                                                    <select class="form-control" id="tipoConsulta" name="tipoHoja" required>
                                                                        <option value="">.:: Seleccionar ::.</option>
                                                                        <option value="Ingreso">Ingreso</option>
                                                                        <option value="Ambulatorio">Ambulatorio</option>
                                                                        <!--<option value="Otro">Otro</option>-->
                                                                    </select>
                                                                    <div class="invalid-tooltip">
                                                                        Seleccione un tipo de hoja.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <label for=""><strong>Fecha de ingreso</strong></label>
                                                                <div class="input-group">
                                                                <input type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" id="fechaIngreso" name="fechaHoja" placeholder="Fecha del ingreso" required>
                                                                    <div class="invalid-tooltip">
                                                                        Seleccione un tipo de documento.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">

                                                            <div class="col-md-6">
                                                                <label for=""><strong>Médico</strong></label>
                                                                <div class="input-group">
                                                                    <select class="form-control controlInteligente" id="medicoHoja" name="idMedico" required>
                                                                        <option value="">.:: Seleccionar ::.</option>
                                                                        <?php 
                                                                            foreach ($medicos as $medico) {
                                                                                ?>
                                                                        
                                                                        <option value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
                                                                        
                                                                        <?php } ?>
                                                                    </select>
                                                                    <div class="invalid-tooltip">
                                                                        Seleccione un médico.
                                                                    </div>  
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for=""><strong>Habitación</strong></label>
                                                                <div class="input-group">
                                                                    
                                                                    <select class="form-control" id="habitacionHoja" name="idHabitacion" required>
                                                                        <option value="37">.:: Seleccionar ::.</option>
                                                                        <?php 
                                                                            foreach ($habitaciones as $habitacion) {
                                                                               // if($habitacion->idHabitacion <= 36){
                                                                        ?>
                                                                                <option value="<?php echo $habitacion->idHabitacion; ?>"><?php echo $habitacion->numeroHabitacion; ?></option>
                                                                        <?php }//} ?>
                                                                    </select>
                
                                                                    <div class="invalid-tooltip">
                                                                        Ingrese el numero de habitacion del paciente.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="form-row">

                                                            <div class="col-md-6">
                                                                <label for=""><strong>Seguro</strong></label>
                                                                <div class="input-group">
                                                                    <select class="form-control" id="seguroHoja" name="seguroHoja" required>
                                                                        <?php
                                                                            foreach ($seguros as $row) {
                                                                                echo '<option value="'.$row->idSeguro.'">'.$row->nombreSeguro.'</option>';
                                                                            }
                                                                        ?>
                                                                    </select>

                                                                    <div class="invalid-tooltip">
                                                                        Debes seleccionar el seguro.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for=""><strong>Destino</strong></label>
                                                                <div class="input-group">
                                                                    <select name="destinoHoja" id="destinoHoja" class="form-control" required>
                                                                        <option value="">.::Seleccionar::.</option>
                                                                        <option value="1">Consulta</option>
                                                                        <option value="2">Ultrasonografía</option>
                                                                        <option value="3">Rayos X</option>
                                                                        <option value="4">Laboratorio</option>
                                                                        <option value="5">Hemodiálisis</option>
                                                                        <!-- <option value="6">Paquete</option> -->
                                                                        <option value="7">Cirugía</option>
                                                                        <option value="8">Medicina</option>
                                                                    </select>
                                                                    <div class="invalid-tooltip">
                                                                        Ingrese el destino del paciente
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div id="responsableFamiliar"></div>
                                                            </div>

                                                            <div class="col-md-12" id="dvProcedimiento">
                                                                <label for=""><strong>Procedimiento</strong></label>
                                                                <div class="input-group">
                                                                    <input type="text" value=" " name="procedimientoHoja" id="procedimientoHoja" class="form-control" required>
                                                                    <div class="invalid-tooltip">
                                                                        Ingrese el destino del paciente
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    
                                                </div>
                                                <div id="datosPaciente">
                                                        
                                                </div>                                 
                                                <input type="hidden" value="1" name="estadoHoja">
                                                <div class="text-center" id="">
                                                    <input type="hidden" value="<?php echo $paciente->idPaciente; ?>" name="idPaciente">
                                                    <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Siguiente <i class="fa fa-arrow-right"></i></button>
                                                    <button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                                </div>

                                                <datalist id="recomendacionesPacientes"></datalist>

                                            
                                        </div>
                                    </div>
                                </div>
                            </form>   
                        </div>
                    <!-- Si viene solo sera hoja de cobro -->

				</div>
            </div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>public/js/motivo_hoja.js"> </script>
<script>

     $(document).ready(function() {
        $("#esPaquete").click(function() {
            var valor = $('input:checkbox[name=esPaquete]:checked').val();
            if (valor == "esPaquete") {
                $("#dvPaquete").show();
            } else {
                $("#dvPaquete").hide();
            }
        });

        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
    });

    $(document).on('change', '#destinoHoja', function (event) {
        event.preventDefault();
        var destino = $(this).val();
        if(destino == 7){
            $("#dvProcedimiento").show();
        }else{
            $("#dvProcedimiento").hide();
        }
    });
</script>
