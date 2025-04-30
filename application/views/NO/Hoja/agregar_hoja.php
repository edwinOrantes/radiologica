<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Hospital </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Agregar hoja de cobro</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<form class="needs-validation" method="post" action="<?php echo base_url()?>Hoja/guardar_hoja" novalidate>
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
                                                    <input type="date" class="form-control" id="fechaIngreso" name="fechaHoja" placeholder="Fecha del ingreso" required>
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
                                                                    if($habitacion->idHabitacion <= 36){
                                                            ?>
                                                                    <option value="<?php echo $habitacion->idHabitacion; ?>"><?php echo $habitacion->numeroHabitacion; ?></option>
                                                            <?php }} ?>
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
                                            <hr>

                                        </div>
                                        
                                    </div>
                                    <div id="datosPaciente">
                                            
                                    </div>                                 
                                    <input type="hidden" value="1" name="estadoHoja">
                                    <div class="text-center" id="">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Siguiente <i class="fa fa-arrow-right"></i></button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>

                                    <datalist id="recomendacionesPacientes"></datalist>

                                
                            </div>
                        </div>
                    </div>
                </form>                                                   
			</div>
		</div>
	</div>
</div>

<!-- Modal validar paciente-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="hojaActiva" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Este paciente tiene una hoja de cobro abierta, desea ver el detalle?</p>
                    </div>

                    <div class="text-center" id="controles">
                        
                    </div>

            </div>
        </div>
    </div>
<!-- Fin modal validar paciente-->


<script>

	function agregarPaciente(id, nombre){
		$("#nombrePaciente").attr("value", nombre);
		$("#idPaciente").attr("value", id);
	}

    $(document).ready(function() {
        var edad = $(this).val();
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = (now.getFullYear() - edad)+"-"+(month)+"-"+(day) ;
        $("#fechaIngreso").val(today);

        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });

        $("#dvPaquete").hide();
        $("#dvProcedimiento").hide();

    });

</script>


<script>
    $(document).on('change', '#tipoConsulta', function(event) {
        event.preventDefault();
        var pivote = $(this).val();
        
    // html para ambulatorios
        var htmlAmbulatorio = ""
        htmlAmbulatorio += '<div class="row">';
        htmlAmbulatorio += '    <div class="form-group col-md-12">';
        htmlAmbulatorio += '        <label for=""><strong>DUI</strong></label>';
        htmlAmbulatorio += '        <input type="text" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>';
        htmlAmbulatorio += '        <div class="invalid-tooltip">';
        htmlAmbulatorio += '            Debes ingresar el DUI del paciente.';
        htmlAmbulatorio += '        </div>';
        htmlAmbulatorio += '    </div>';
        htmlAmbulatorio += '</div>';

        htmlAmbulatorio += '<div class="form-row">';
        htmlAmbulatorio += '<div class="col-md-6">';
        htmlAmbulatorio += '    <label for=""><strong>Nombre del paciente</strong></label>';
        htmlAmbulatorio += '    <div class="input-group">';
        htmlAmbulatorio += '    <input type="text" list="recomendacionesPacientes" class="form-control existeHoja" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" autocomplete="off" required>';
        htmlAmbulatorio += '        <div class="invalid-tooltip">';
        htmlAmbulatorio += '            Ingrese el nombre del paciente';
        htmlAmbulatorio += '        </div>';
        htmlAmbulatorio += '    </div>';
        htmlAmbulatorio += '</div>';

        htmlAmbulatorio += '<div class="col-md-6">';
        htmlAmbulatorio += '    <label for=""><strong>Edad</strong></label>';
        htmlAmbulatorio += '    <div class="input-group">';
        htmlAmbulatorio += '    <input type="number" class="form-control numeros" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>';
        htmlAmbulatorio += '        <div class="invalid-tooltip">';
        htmlAmbulatorio += '            Ingrese la edad del paciente';
        htmlAmbulatorio += '        </div>';
        htmlAmbulatorio += '    </div>';
        htmlAmbulatorio += '</div>';

        htmlAmbulatorio += '<div class="col-md-12">';
        htmlAmbulatorio += '    <input type="hidden" class="form-control numeros" id="idPaciente" name="idPaciente">';
        htmlAmbulatorio += '    <input type="hidden" value="1" class="form-control numeros" id="pacienteAmbulatorio" name="pacienteAmbulatorio">';
        htmlAmbulatorio += '</div>';

        htmlAmbulatorio += '</div>';
    // Fin html ambulatorio

    // htlm de Ingresos
        var htmlIngreso = "";
        htmlIngreso += '<div class="row">';
        htmlIngreso += '    <div class="form-group col-md-6">';
        htmlIngreso += '        <label for=""><strong>DUI</strong></label>';
        htmlIngreso += '        <input type="text" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>';
        htmlIngreso += '        <div class="invalid-tooltip">';
        htmlIngreso += '            Debes ingresar el DUI del paciente.';
        htmlIngreso += '        </div>';
        htmlIngreso += '    </div>';
        htmlIngreso += '    <div class="form-group col-md-6">';
        htmlIngreso += '        <label for=""><strong>Nombre Completo</strong></label>';
        htmlIngreso += '        <input type="text" class="form-control nombrePaciente existeHoja" list="recomendacionesPacientes" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" autocomplete="off" required>';
        htmlIngreso += '        <div class="invalid-tooltip">';
        htmlIngreso += '            Debes ingresar el nombre del paciente.';
        htmlIngreso += '        </div>';
        htmlIngreso += '    </div>';
        htmlIngreso += '</div>';

        htmlIngreso += '<div class="row">';
        htmlIngreso += '    <div class="form-group col-md-6">';
        htmlIngreso += '        <label for=""><strong>Edad</strong></label>';
        htmlIngreso += '        <input type="number" class="form-control numeros" min="0" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>';
        htmlIngreso += '        <div class="invalid-tooltip">';
        htmlIngreso += '            Debes ingresar la edad del paciente.';
        htmlIngreso += '        </div>';
        htmlIngreso += '    </div>';
        htmlIngreso += '    <div class="form-group col-md-6">';
        htmlIngreso += '        <label for=""><strong>Teléfono</strong></label>';
        htmlIngreso += '        <input type="text" class="form-control" data-mask="9999-9999" id="telefonoPaciente" name="telefonoPaciente" placeholder="Teléfono del paciente" required>';
        htmlIngreso += '        <div class="invalid-tooltip">';
        htmlIngreso += '            Debes ingresar el teléfono del paciente.';
        htmlIngreso += '        </div>';
        htmlIngreso += '    </div>';
        htmlIngreso += '</div>';

        htmlIngreso += '<div class="row">';
        htmlIngreso += '    <div class="form-group col-md-6">';
        htmlIngreso += '        <label for=""><strong>Fecha de nacimiento</strong></label>';
        htmlIngreso += '        <input type="date" class="form-control" id="nacimientoPaciente" name="nacimientoPaciente" required>';
        htmlIngreso += '        <div class="invalid-tooltip">';
        htmlIngreso += '            Debes ingresar la fecha de nacimiento del paciente.';
        htmlIngreso += '        </div>';
        htmlIngreso += '    </div>';
        htmlIngreso += '    <div class="form-group col-md-6">';
        htmlIngreso += '        <label for=""><strong>Dirección</strong></label>';
        htmlIngreso += '        <input type="text" class="form-control" id="direccionPaciente" name="direccionPaciente" required>';
        htmlIngreso += '        <input type="hidden" class="form-control" value="0" id="idPaciente" name="idPaciente">';
        htmlIngreso += '        <div class="invalid-tooltip">';
        htmlIngreso += '            Debes ingresar dirección del paciente.';
        htmlIngreso += '        </div>';
        htmlIngreso += '    </div>';
        htmlIngreso += '</div>';
    // Fin html ingresos
        if(pivote == "Ambulatorio"){
            //$("#habitacionHoja").val(1);
            $("#datosPaciente").html(" ");
            $("#datosPaciente").append(htmlAmbulatorio);
        }else{
            var html = "";
            $("#datosPaciente").html(" ");
            //$("#habitacionHoja").val(0);
            $("#datosPaciente").append(htmlIngreso);
        }
    });
</script>

<script>

    $(document).on('change', '#edadPaciente', function (event) {
        event.preventDefault();
        var edad = $(this).val();
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = (now.getFullYear() - edad)+"-"+(month)+"-"+(day) ;
        $("#nacimientoPaciente").val(today);
    });

    $(document).on('change', '#duiPaciente', function (event) {
        event.preventDefault();
        var duiPaciente = $(this).val();
        if(duiPaciente != "00000000-0"){
            $.ajax({
                url: "validar_paciente",
                type: "POST",
                data: {dui: duiPaciente },
                success:function(respuesta){
                    var registro = eval(respuesta);
                        if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            var datos = registro.datos;
                            $("#idPaciente").val(datos["idPaciente"]);
                            $("#nombrePaciente").val(datos["nombrePaciente"]);
                            $("#edadPaciente").val(datos["edadPaciente"]);
                            $("#telefonoPaciente").val(datos["telefonoPaciente"]);
                            $("#nacimientoPaciente").val(datos["nacimientoPaciente"]);
                            $("#direccionPaciente").val(datos["direccionPaciente"]);
                            validarHoja(datos["idPaciente"]);
                        }
                    }else{

                    }
                        /* if (registro.length > 0){
                            console.log(registro);
                            for (let i = 0; i < registro.length; i++) {
                                $("#idPaciente").val(registro[i]["idPaciente"]);
                                $("#nombrePaciente").val(registro[i]["nombrePaciente"]);
                                $("#edadPaciente").val(registro[i]["edadPaciente"]);
                                $("#telefonoPaciente").val(registro[i]["telefonoPaciente"]);
                                $("#nacimientoPaciente").val(registro[i]["nacimientoPaciente"]);
                                $("#direccionPaciente").val(registro[i]["direccionPaciente"]);
                                validarHoja(registro[i]["idPaciente"]);
                            }
                        }else{
                                $("#idPaciente").val("0");
                                $("#nombrePaciente").val("");
                                $("#edadPaciente").val("");
                                $("#telefonoPaciente").val("");
                                $("#nacimientoPaciente").val("");
                                $("#direccionPaciente").val("");
                        } */
                    }
            });
        }else{
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
            $("#direccionPaciente").val("");

            }
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

    function validarHoja(paciente){
        var id = paciente;
        $.ajax({
            url: "validar_hoja",
            type: "GET",
            data: {id: id },
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (registro.length > 0){
                        var enlaces = "";
                        for (let i = 0; i < registro.length; i++) {
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/detalle_hoja/'+registro[i]["idHoja"]+'/" class="btn btn-danger"> <i class="fa fa-eye"></i>  Ver </a>                   ';
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/" class="btn btn-default"> <i class="fa fa-file-times"></i>  Cancelar </a>';
                            $("#controles").html(enlaces);
                        }
                        $("#hojaActiva").modal();
                    }
                }
        });

    }

    function validarHojaPor(paciente){
        var id = paciente;
        $.ajax({
            url: "validar_hoja",
            type: "GET",
            data: {id: id },
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (registro.length > 0){
                        var enlaces = "";
                        for (let i = 0; i < registro.length; i++) {
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/detalle_hoja/'+registro[i]["idHoja"]+'/" class="btn btn-danger"> <i class="fa fa-eye"></i>  Ver </a>                   ';
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/" class="btn btn-default"> <i class="fa fa-file-times"></i>  Cancelar </a>';
                            $("#controles").html(enlaces);
                        }
                        $("#hojaActiva").modal();
                    }
                }
        });

    }

</script>

<script>
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

    $(document).on('change', '#nombrePaciente', function (event) {
       event.preventDefault();
        $.ajax({
            url: "buscar_paciente",
            type: "GET",
            beforeSend: function () { },
            data: {id:$(this).val()},
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var html = "";
                    for (var i = 0; i < registro.length; i++) {
                        $("#duiPaciente").val(registro[i]["duiPaciente"]);
                        $("#edadPaciente").val(registro[i]["edadPaciente"]);
                        $("#idPaciente").val(registro[i]["idPaciente"]);
                    }
                }else{
                    $("#edadPaciente").val("");
                    $("#idPaciente").val("0");
                }
            },
            error:function(){
                alert("Hay un error");
            }
        });

    });

    $(document).on('change', '.nombrePaciente', function (event) {
       event.preventDefault();
        $.ajax({
            url: "buscar_paciente",
            type: "GET",
            beforeSend: function () { },
            data: {id:$(this).val()},
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    var html = "";
                    for (var i = 0; i < registro.length; i++) {
                        $("#duiPaciente").val(registro[i]["duiPaciente"]);
                        $("#edadPaciente").val(registro[i]["edadPaciente"]);
                        $("#telefonoPaciente").val(registro[i]["telefonoPaciente"]);
                        $("#nacimientoPaciente").val(registro[i]["nacimientoPaciente"]);
                        $("#direccionPaciente").val(registro[i]["direccionPaciente"]);
                        $("#idPaciente").val(registro[i]["idPaciente"]);
                    }
                }else{
                   // $("#duiPaciente").val("");
                    $("#edadPaciente").val("");
                    $("#telefonoPaciente").val("");
                    $("#nacimientoPaciente").val("");
                    $("#direccionPaciente").val("");
                    // $("#duiPaciente").val("");
                    $("#idPaciente").val("0");
                }
            },
            error:function(){
                alert("Hay un error");
            }
        });

    });

    $(document).on('change', '.existeHoja', function (event) {
       event.preventDefault();
       var id = $(this).val();
        $.ajax({
            url: "validar_existencia_hoja",
            type: "GET",
            data: {id: id },
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (registro.length > 0){
                        var enlaces = "";
                        for (let i = 0; i < registro.length; i++) {
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/detalle_hoja/'+registro[i]["idHoja"]+'/" class="btn btn-danger"> <i class="fa fa-eye"></i>  Ver </a>                   ';
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/" class="btn btn-default"> <i class="fa fa-file-times"></i>  Cancelar </a>';
                            $("#controles").append(enlaces);
                        }
                        $("#hojaActiva").modal();
                    }
                }
        });

    });
</script>

<!-- Calculando edad del paciente -->
<script>
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

    $(document).ready(function() {
        $("#esPaquete").click(function() {
            var valor = $('input:checkbox[name=esPaquete]:checked').val();
            if (valor == "esPaquete") {
                $("#dvPaquete").show();
            } else {
                $("#dvPaquete").hide();
            }
        });
    });

</script>