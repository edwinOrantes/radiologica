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
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Datos del paciente</h6></div>
                        <div class="col-md-6 text-right">
                            <!-- <strong> Agregar responsable </strong>
                            <label class="ms-switch">
                            <input type="checkbox" id="pivoteResponsable" value="responsable" name="responsable">
                                <span class="ms-switch-slider round"></span>
                            </label> -->
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<form class="needs-validation" method="post" action="<?php echo base_url()?>Paciente/actualizar_paciente_responsable" novalidate>
                        
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for=""><strong>DUI</strong></label>
                                <input type="text" value="<?php echo $paciente->duiPaciente; ?>" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" >
                                <div class="invalid-tooltip">
                                    Debes ingresar el DUI del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Nombre Completo</strong></label>
                                <input type="text" value="<?php echo $paciente->nombrePaciente; ?>" class="form-control" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" list='recomendacionesPacientes'  required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el nombre del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Fecha de nacimiento</strong></label>
                                <input type="date" value="<?php echo $paciente->nacimientoPaciente; ?>" class="form-control" id="nacimientoPaciente" name="nacimientoPaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la fecha de nacimiento del paciente.
                                </div>
                            </div>
                            
                            <div class="form-group col-md-4"  style="display:none">
                                <label for=""><strong>Estado civil</strong></label>
                                <input type="text" value="<?php echo $paciente->civilPaciente; ?>" class="form-control" id="civilPaciente" name="civilPaciente">
                                <div class="invalid-tooltip">
                                    Debes seleccionar el estado civil.
                                </div>
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for=""><strong>Teléfono</strong></label>
                                <input value="<?php echo $paciente->telefonoPaciente; ?>" type="text" class="form-control" data-mask="9999-9999" id="telefonoPaciente" name="telefonoPaciente" placeholder="Teléfono del paciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el teléfono del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Edad</strong></label>
                                <input value="<?php echo $paciente->edadPaciente; ?>" type="number" class="form-control numeros" min="0" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la edad del paciente.
                                </div>
                            </div>

                             <div class="form-group col-md-4">
                                <label for=""><strong>Ocupación</strong></label>
                                <input type="text" value="<?php echo $paciente->profesionPaciente; ?>" class="form-control" id="ocupacionPaciente" name="ocupacionPaciente" placeholder="Ocupación del paciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la ocupación paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-4" style="display:none">
                                <label for=""><strong>Sexo</strong></label>
                                <input type="text" value="<?php echo $paciente->sexoPaciente; ?>" class="form-control" id="sexoPaciente" name="sexoPaciente">
                                <div class="invalid-tooltip">
                                    Debes seleccionar el sexo paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Tipeo</strong></label>
                                <input  value="<?php echo $paciente->tipeoPaciente; ?>"class="form-control" id="tipeoPaciente" name="tipeoPaciente">
                                <div class="invalid-tooltip">
                                    Debes ingresar el tipeo sanguineo del paciente.
                                </div>
                            </div>

                            <div class="form-group col-md-8">
                                <label for=""><strong>Dirección</strong></label>
                                <input type="text" value="<?php echo $paciente->direccionPaciente; ?>" class="form-control" id="direccionPaciente" name="direccionPaciente" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar dirección del paciente.
                                </div>
                            </div>

                        </div>
                        
                        <!-- datos para el responsable -->
                            <div id="datosResponsable">
                                <h6 class="text-center text-primary">Datos del responsable</h6>
                                <hr>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Nombre Completo</strong></label>
                                        <input type="text" class="form-control" value="<?php echo $paciente->nombreResponsable; ?>"id="nombreResponsable" name="nombreResponsable" placeholder="Nombre del paciente"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el nombre del responsable.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Edad</strong></label>
                                        <input type="number" class="form-control numeros" value="<?php echo $paciente->edadResponsable; ?>" min="0" id="edadResponsable" name="edadResponsable" placeholder="Edad del paciente"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la edad del responsable.
                                        </div>
                                    </div>
                                                
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Teléfono</strong></label>
                                        <input type="text" class="form-control" data-mask="9999-9999" value="<?php echo $paciente->telefonoResponsable; ?>" id="telefonoResponsable" name="telefonoResponsable" placeholder="Teléfono del paciente"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el teléfono del responsable.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>DUI</strong></label>
                                        <input type="text" class="form-control" id="duiResponsable" value="<?php echo $paciente->duiResponsable; ?>" name="duiResponsable" data-mask="99999999-9" placeholder="DUI del paciente"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el DUI del responsable.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Profesión</strong></label>
                                        <input type="text" class="form-control" value="<?php echo $paciente->profesionResponsable; ?>" id="profesionResponsable" name="profesionResponsable" placeholder="Profesion del responsable"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la profesión.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Parentesco</strong></label>
                                        <input type="text" class="form-control" value="<?php echo $paciente->parentescoResponsable; ?>" id="parentescoResponsable" name="parentescoResponsable" placeholder="Parentesco del responsable"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el parentesco.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for=""><strong>Dirección</strong></label>
                                        <input class="form-control" value="<?php echo $paciente->direccionResponsable; ?>" id="direccionResponsable" name="direccionResponsable"/>
                                        <div class="invalid-tooltip">
                                            Debes ingresar dirección del responsable.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- datos para el responsable -->

                        <div class="form-group text-center mt-3">
                            <input type="hidden" class="form-control" value="<?php echo $paciente->idPaciente; ?>" id="idPaciente" name="idPaciente" required>
                            <input type="hidden" class="form-control" value="<?php echo $paciente->idResponsable; ?>" id="idResponsable" name="idResponsable" required>
                            <button type="submit" class="btn btn-primary has-icon"> Actualizar <i class="fa fa-arrow-right"></i></button>
                        </div>
                    
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>

<datalist id="recomendacionesPacientes"></datalist>

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


<script>

$(document).on('change', '#duiPaciente', function (event) {
	event.preventDefault();
    var duiPaciente = $(this).val();
    var datos = {
        paciente: $(this).val(),
        pivote: 0
    }
    if(duiPaciente == "00000000-0"){
        toastr.remove();
        toastr.options = {
            "positionClass": "toast-top-left",
            "showDuration": "5000",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "5000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            },
        toastr.error('El número de DUI es invalido y debera ser actualizado...', 'Aviso!');

        $("#idPaciente").val("0"); 
        $("#nombrePaciente").val(""); 
        $("#nacimientoPaciente").val(""); 
        $("#telefonoPaciente").val(""); 
        $("#edadPaciente").val(""); 
        $("#ocupacionPaciente").val(""); 
        $("#sexoPaciente").val("");
        $("#civilPaciente").val("");
        $("#tipeoPaciente").val(""); 
        $("#direccionPaciente").val("");
        $("#idResponsable").val("0");

    }else{
        $.ajax({
            url: "validar_paciente",
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
                            $("#nacimientoPaciente").val(datos["nacimientoPaciente"]); 
                            $("#telefonoPaciente").val(datos["telefonoPaciente"]); 
                            $("#edadPaciente").val(datos["edadPaciente"]); 
                            $("#ocupacionPaciente").val(datos["profesionPaciente"]); 
                            $("#sexoPaciente").val(datos["sexoPaciente"]);
                            $("#civilPaciente").val(datos["civilPaciente"]);
                            $("#tipeoPaciente").val(datos["tipeoPaciente"]); 
                            $("#direccionPaciente").val(datos["direccionPaciente"]);
                            // Paciente
                            
                        // Responsable
                            $("#nombreResponsable").val(datos["nombreResponsable"]);
                            $("#edadResponsable").val(datos["edadResponsable"]);
                            $("#telefonoResponsable").val(datos["telefonoResponsable"]);
                            $("#duiResponsable").val(datos["duiResponsable"]);
                            $("#profesionResponsable").val(datos["profesionResponsable"]);
                            $("#parentescoResponsable").val(datos["parentescoResponsable"]);
                            $("#direccionResponsable").val(datos["direccionResponsable"]);
                            if(datos["idResponsable"] > 0){
                                $("#idResponsable").val(datos["idResponsable"]);
                            }else{
                                $("#idResponsable").val("0");
                            }
                        // Responsable

                        $("#datosResponsable").show(); // Mostrando valores del responsable
                    }
                }else{
                    toastr.remove();
                    toastr.options = {
                        "positionClass": "toast-top-left",
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "1000",
                        "extendedTimeOut": "50",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                        },
                    toastr.error('Error...', 'Aviso!');
                }
            }
        });
    }

});

$(document).on('change', '#edadPaciente', function (event) {
	event.preventDefault();
    var edad = $(this).val();
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = (now.getFullYear() - edad)+"-"+(month)+"-"+(day) ;
    $("#nacimientoPaciente").val(today);
});



$(document).on('change', '#nacimientoPaciente', function (event) {
	event.preventDefault();
    var fechaNacimiento = $(this).val();  //Fecha introducida por el usuario
    var now = new Date();                                        // Calculando la fecha de este dia
    var day = ("0" + now.getDate()).slice(-2);                   
    var month = ("0" + (now.getMonth() + 1)).slice(-2);          
    var today = (now.getFullYear())+"-"+(month)+"-"+(day) ;      // Fin del calculo
    
    var fecha1 = moment(fechaNacimiento); // Sacando diferencia de años
    var fecha2 = moment(today);           // Fin del calculo

    var edad = fecha2.diff(fecha1, 'years');
    $("#edadPaciente").val(edad);        // Asignando el valor
});

$(document).on('change', '#nombrePaciente', function (event) {
    event.preventDefault();
    var datos = {
        paciente: $(this).val(),
        pivote: 1
    }
    $.ajax({
        url: "validar_paciente",
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
                        $("#nacimientoPaciente").val(datos["nacimientoPaciente"]); 
                        $("#telefonoPaciente").val(datos["telefonoPaciente"]); 
                        $("#edadPaciente").val(datos["edadPaciente"]); 
                        $("#civilPaciente").val(datos["civilPaciente"]); 
                        $("#sexoPaciente").val(datos["sexoPaciente"]); 
                        $("#direccionPaciente").val(datos["direccionPaciente"]);
                        $("#duiPaciente").val(datos["duiPaciente"]);
                        $("#ocupacionPaciente").val(datos["profesionPaciente"]);
                        // Paciente
                        
                    // Responsable
                        $("#nombreResponsable").val(datos["nombreResponsable"]);
                        $("#telefonoResponsable").val(datos["telefonoResponsable"]);
                        $("#duiResponsable").val(datos["duiResponsable"]);
                        $("#parentescoResponsable").val(datos["parentescoResponsable"]);
                        if(datos["idResponsable"] > 0){
                            $("#idResponsable").val(datos["idResponsable"]);
                        }else{
                            $("#idResponsable").val("0");
                        }
                    // Responsable

                // $("#datosResponsable").show(); // Mostrando valores del responsable
                }
            }else{
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-top-left",
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "1000",
                    "extendedTimeOut": "50",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                    },
                toastr.error('Error...', 'Aviso!');
            }
        }
    });

});



$(document).on('keyup', '#nombrePaciente', function (event) {
    event.preventDefault();
    $("#recomendacionesPacientes").html("");
    var paciente = $(this).val();

    $.ajax({
        url: "recomendaciones_paciente",
        type: "GET",
        beforeSend: function () { },
        data: {id:$(this).val()},
        success:function(respuesta){
            var registro = eval(respuesta);
            if (registro.length > 0){
                var html = "";
                for (var i = 0; i < registro.length; i++) 
                {
                    html += "<option value='"+ registro[i]["nombrePaciente"] +"'>  ";
                    
                }
                $("#recomendacionesPacientes").append(html);
            }
        },
        error:function(){
            alert("Hay un error");
        }
    });

});
/* $(document).on('click', '#pivoteResponsable', function() {
    var valor = $('input:checkbox[name=responsable]:checked').val();

    if (valor == "responsable") {
        $("#datosResponsable").show();
    } else {
        $("#datosResponsable").hide();
    }
}); */

</script>

