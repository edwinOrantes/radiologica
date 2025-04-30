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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-clipboard-list"></i> Hemodiálisis </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar cita</a></li>
                </ol>
            </nav> 
            
			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <div class="col-md-6"><h6>Datos de la cita</h6></div>
                    <div class="col-md-6 text-right">
                        <a href="<?php echo base_url(); ?>Hemodialisis/lista_citas/" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Lista citas</a>
                    </div>
                    <!-- <a href="<?php echo base_url()?>Paciente/lista_pacientes" class="btn btn-outline-primary btn-sm ms-text-primary"><i class="fa fa-users"></i> Lista de pacientes </a> -->
				</div>
				<div class="ms-panel-body">
					<form class="needs-validation" method="post" action="<?php echo base_url()?>Hemodialisis/guardar_cita" novalidate>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for=""><strong>DUI</strong></label>
                                <input type="text" class="form-control" id="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el DUI del paciente.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for=""><strong>Nombre Completo</strong></label>
                                <input type="text" class="form-control" id="nombrePacienteR" readonly>
                                <input type="hidden" list="recomendacionesPacientes" class="form-control" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" autocomplete="off"  required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el nombre del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Turno</strong></label>
                                <select class="form-control" name="turnoCita" id="turnoCita" required>
                                    <option value="">.:Seleccionar:.</option>
                                    <option value="1">Primer</option>
                                    <option value="2">Segundo</option>
                                    <option value="3">Tercer</option>
                                </select>
                                <div class="invalid-tooltip">
                                    Debes seleccionar el turno.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Fecha</strong></label>
                                <input type="date" class="form-control" name="fechaCita" id="fechaCita" value="<?php echo date('Y-m-d'); ?>" required>
                                <div class="invalid-tooltip">
                                    Selecciona la fecha de la cita.
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for=""><strong>Responsable</strong></label>
                                <input type="text" class="form-control" id="responsablePacienteR" readonly>
                                <input type="hidden" class="form-control" id="responsablePaciente" name="responsablePaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la fecha de nacimiento del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for=""><strong>Tel. responsable</strong></label>
                                <input type="text" class="form-control" data-mask="9999-9999" id="telRespPacienteR" readonly>
                                <input type="hidden" class="form-control" data-mask="9999-9999" id="telRespPaciente" name="telRespPaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar dirección del paciente.
                                </div>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="col md-12 text-center"><p id="tipoPaciente"></p></div>
                        </div>

                        
                        <div class="form-group text-center mt-3 botonesCita">
                            <input type="hidden" value="0" name="idPaciente" id="idPaciente">
                            <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Crear cita </button>
                            <button type="reset" class="btn btn-default has-icon"><i class=" fa fa-times"></i> Cancelar </button>
                        </div>
                    
                    </form>
                    <!-- Recomendaciones para cita hemodialisis -->
                    
				</div>
			</div>
		</div>
	</div>
    <datalist id="recomendacionesPacientes"></datalist>
</div> 


<script>

    $(document).on('change', '#duiPaciente', function (event) {
        event.preventDefault();

        var datos = {
            paciente : $(this).val(),
            pivote: 0
        }

        $.ajax({
            url: "../../Paciente/validar_paciente",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
                        var datos = registro.datos;
                        // Paciente
                            $("#idPaciente").val(datos["idPaciente"]);
                            $("#nombrePaciente").val(datos["nombrePaciente"]);
                            $("#nombrePacienteR").val(datos["nombrePaciente"]);
                            $("#turnoCita").val("1"); 
                        // Paciente
                        
                        // Responsable
                            $("#responsablePaciente").val(datos["nombreResponsable"]);
                            $("#telRespPaciente").val(datos["telefonoResponsable"]);
                            $("#responsablePacienteR").val(datos["nombreResponsable"]);
                            $("#telRespPacienteR").val(datos["telefonoResponsable"]);
                            $("#tipoPaciente").html('<a href="#" class="btn btn-outline-success bt-sm">Paciente privado</a>');
                        // Responsable
                        $('#turnoCita').prop('disabled', false);
                    }else{
                        // Paciente
                            $("#idPaciente").val("0");
                            $("#nombrePaciente").val("");
                            $("#nombrePacienteR").val("");
                            $("#turnoCita").val(""); 
                        // Paciente
                        
                        // Responsable
                            $("#responsablePaciente").val("");
                            $("#telRespPaciente").val("");
                            $("#responsablePacienteR").val("");
                            $("#telRespPacienteR").val("");
                            $("#tipoPaciente").html("");
                        // Responsable
                        $('#turnoCita').prop('disabled', true);
                    }
                }
            }
        });

    });

    $(document).ready(function() {
        $('#turnoCita').prop('disabled', true);
    });

    $(document).on('keyup', '#nombrePaciente', function (event) {
       event.preventDefault();
       $("#recomendacionesPacientes").html("");
       var data = {
            id: $(this).val()
       }

        $.ajax({
            url: "../recomendaciones_paciente",
            type: "GET",
            beforeSend: function () { },
            data: data,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var html = "";
                    for (var i = 0; i < registro.length; i++){
                        html += "<option value='"+ registro[i]["nombrePaciente"]+"'>  ";
                    }
                        console.log(html);
                    $("#recomendacionesPacientes").append(html);
                }
            },
            error:function(){
                alert("Hay un error");
            }
        });

    });

    $(document).on('change', '#nombrePaciente', function (event) {
       event.preventDefault();
        $.ajax({
            url: "../buscar_paciente",
            type: "GET",
            beforeSend: function () { },
            data: {id:$(this).val()},
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var html = "";
                    $("#tipoPaciente").html('');
                    for (var i = 0; i < registro.length; i++) {
                        $("#edadPaciente").val(registro[i]["edadPaciente"]);
                        $("#idPaciente").val(registro[i]["idPaciente"]);
                        if(registro[i]["vinoDe"] == 1){
                            html = '<a href="#" class="btn btn-outline-success bt-sm">Paciente hemodialisis</a>';
                        }else{
                            html = '<a href="#" class="btn btn-outline-primary bt-sm">Paciente privado</a>';
                        }
                    }

                    $(".botonesCita").show();
                    $('#turnoCita').prop('disabled', false);
                }else{
                    $("#idPaciente").val("0");
                    $(".botonesCita").hide();
                    html = '<a href="#" class="btn btn-outline-danger bt-sm">Paciente no existe</a>';
                    $('#turnoCita').prop('disabled', true);
                    $('#turnoCita').val('');
                    $("#responsablePaciente").val('');
                    $("#telRespPaciente").val('');
                }
                $("#tipoPaciente").html(html);
            },
            error:function(){
                alert("Hay un error");
            }
        });

    });

    $(document).on('change', '#turnoCita', function (event) {
       event.preventDefault();
       var data = {
            idPaciente : $("#idPaciente").val(),
       }
           

        $.ajax({
            url: "../buscar_encargado",
            type: "GET",
            beforeSend: function () { },
            data: data,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    $("#tipoPaciente").html('');
                    for (var i = 0; i < registro.length; i++) {
                        $("#responsablePaciente").val(registro[i]["responsablePaciente"]);
                        $("#telRespPaciente").val(registro[i]["telRespPaciente"]);
                    }

                }else{
                    $("#responsablePaciente").val('');
                    $("#telRespPaciente").val('');
                }
            },
            error:function(){
                alert("Hay un error");
            }
        });

    });
</script>