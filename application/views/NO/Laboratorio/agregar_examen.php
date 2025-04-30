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
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Laboratorio </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Datos del paciente</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<h6> Información del paciente </h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="<?php echo base_url()?>Laboratorio/guardar_paciente" novalidate>
								<div class="row">
									<div class="col-md-12">
										
                                        <div class="form-row">

                                            <div class="col-md-12">
												<label for=""><strong>Código</strong></label>
												<div class="input-group">
                                                    <input type="text" class="form-control" value="<?php echo $codigo; ?>" id="codigoHoja" readonly/>
                                                    <input type="hidden" class="form-control" value="<?php echo $codigo; ?>" name="codigoHoja"/>
													<div class="invalid-tooltip">
														Seleccione un tipo de documento.
													</div>
												</div>
											</div>

										</div>

                                        <div class="row">
                                            
                                            <div class="form-group col-md-6">
                                                <label for=""><strong>DUI</strong></label>
                                                <input type="text" class="form-control" id="duiPaciente" name="duiPaciente" data-mask="99999999-9" placeholder="DUI del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Debes ingresar el DUI del paciente.
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
                                                <label for=""><strong>Nombre del paciente</strong></label>
                                                <div class="input-group">
                                                <input type="text" list="recomendacionesPacientes" class="form-control existeHoja" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" autocomplete="off" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese el nombre del paciente
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for=""><strong>Edad</strong></label>
                                                <div class="input-group">
                                                <input type="number" class="form-control numeros" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese la edad del paciente
                                                    </div>
                                                </div>
                                                <input type="hidden" value="0" class="form-control numeros" id="idPaciente" name="idPaciente">
                                                <input type="hidden" value="1" class="form-control numeros" id="pacienteAmbulatorio" name="pacienteAmbulatorio">
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
                                                <label for=""><strong>Tipo</strong></label>
                                                <div class="input-group">
                                                    <select class="form-control controlInteligente" id="tipoConsulta" name="tipoConsulta" required>
                                                        <?php
                                                            /* foreach ($tipo as $row) {
                                                                if($row->idTipoConsultaLab == 1){
                                                                    echo '<option value="'.$row->idTipoConsultaLab.'" selected="selected">'.$row->nombreTipoConsultaLab.'</option>';
                                                                }else{
                                                                    echo '<option value="'.$row->idTipoConsultaLab.'">'.$row->nombreTipoConsultaLab.'</option>';
                                                                }
                                                            } */

                                                            foreach ($tipo as $row) {
                                                                if($row->idTipoConsultaLab == 1){
                                                                    echo '<option value="'.$row->idTipoConsultaLab.'" selected="selected">'.$row->nombreTipoConsultaLab.'</option>';
                                                                }else{
                                                                    echo '<option value="'.$row->idTipoConsultaLab.'">'.$row->nombreTipoConsultaLab.'</option>';
                                                                }
                                                            }
                                                            
                                                        ?>
                                                        <!-- <option value="" >.::Seleccionar::.</option>
                                                        <option value="2">ISBM</option>
                                                        <option value="4">ISSS</option> -->
                                                    </select>
                                                    <div class="invalid-tooltip">
                                                        Seleccione un tipo de hoja.
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
									<button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Siguiente <i class="fa fa-arrow-right"></i></button>
									<button class="btn btn-light mt-4 d-inline w-20" type="reset" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
								</div>

                                <datalist id="recomendacionesPacientes"></datalist>

							</form>
						</div>
					</div>
				</div>
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
        $("#edadPaciente").val(""); 

    }else{
        $.ajax({
            url: "../Paciente/validar_paciente",
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
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/detalle_hoja/'+registro[i]["idHoja"]+'" class="btn btn-danger"> <i class="fa fa-eye"></i>  Ver </a>                   ';
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/" class="btn btn-default"> <i class="fa fa-file-times"></i>  Cancelar </a>';
                            $("#controles").append(enlaces);
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
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/detalle_hoja/'+registro[i]["idHoja"]+'" class="btn btn-danger"> <i class="fa fa-eye"></i>  Ver </a>                   ';
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/" class="btn btn-default"> <i class="fa fa-file-times"></i>  Cancelar </a>';
                            $("#controles").append(enlaces);
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
                            enlaces += '<a href="<?php echo base_url(); ?>Hoja/detalle_hoja/'+registro[i]["idHoja"]+'" class="btn btn-danger"> <i class="fa fa-eye"></i>  Ver </a>                   ';
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
</script>
