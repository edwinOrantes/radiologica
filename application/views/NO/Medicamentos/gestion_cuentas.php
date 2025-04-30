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

			<!-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Laboratorio </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Datos del paciente</a></li>
                </ol>
            </nav> -->

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h6> Nueva Cuenta </h6>
                            <hr>
                            <form action="<?php echo base_url()?>Medicamentos/crear_cuenta" method="post">
                                <div class="form-row" id="pacientesContainer">
                                    <div id="porSelect" class="col-md-12">
                                        <select id="pacienteIngresado" class="form-control controlInteligente" name="pacienteIngresado" id="pacienteIngresado" required>
                                            <option value="">.:Seleccionar paciente:.</option>
                                            <?php
                                                foreach ($pacientes as $fila) {
                                            ?>
                                                <option value="<?php echo $fila->idHoja; ?>"
                                                        data-idpaciente="<?php echo $fila->idPaciente; ?>"
                                                        data-idhabitacion="<?php echo $fila->idHabitacion; ?>"
                                                        data-tomadade="<?php echo $fila->tomadaDe; ?>"
                                                        data-hoja="<?php echo $fila->idHoja; ?>"
                                                ><?php echo $fila->nombrePaciente; ?></option>
                                            <?php } ?>
                                        </select>

                                        <input type="hidden" name="habitacion" id="habitacionP">
                                        <input type="hidden" name="tomadaDe" id="tomadaDe">
                                        <input type="hidden" name="idHoja" id="idHoja">
                                        
                                    </div>
                                    <div id="porText" class="col-md-12">
                                        <!-- html para ambulatorios -->

                                            <div class="col-md-12">
                                                <label for=""><strong>Nombre del paciente</strong></label>
                                                <div class="input-group">
                                                    <input type="text" list="recomendacionesPacientes" class="form-control existeHoja" id="nombrePaciente" name="nombrePaciente" placeholder="Nombre del paciente" autocomplete="off" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese el nombre del paciente
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <input type="text" class="form-control numeros" id="edadPaciente" name="edadPaciente" placeholder="Edad del paciente" required>
                                                <input type="hidden" class="form-control numeros" id="idPaciente" name="idPaciente">
                                                <input type="hidden" value="1" class="form-control numeros" id="pacienteAmbulatorio" name="pacienteAmbulatorio">
                                            </div>

                                        <!-- Fin html ambulatorio -->
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-3" type="submit"> Crear <i class="fa fa-arrow-right"></i> </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <div>
                                <span> Sin cuenta </span>
                                <label class="ms-switch">
                                    <input type="checkbox" id="sinCuenta" value="sinCuenta" name="sinCuenta">
                                    <span class="ms-switch-slider ms-switch-primary round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
                            <div class="col-md-12"><h6 class="text-center">Cuentas abiertas</h6></div>
                            <?php
                                if(sizeof($cuentas) > 0 ){
                                    $clase = "";
                                    $de = "";
                                    $tipoBange = "";
                                    foreach ($cuentas as $cuenta) {
                                        if($cuenta->tipoHoja == "ISBM"){
                                            $clase = "col-md-3 alert-success text-center p-3 border border-success";
                                            $de = "ISBM";
                                            $tipoBange = "badge badge-outline-success";
                                        }else{
                                            $clase = "col-md-3 alert-primary text-center p-3 border border-primary";
                                            $de = "PRIVADO";
                                            $tipoBange = "badge badge-outline-primary";
                                        }
                            ?>
                                        <div class="<?php echo $clase?> text-center pb-5">
                                            <div class="text-right pb-3">
                                                <?php 
                                                    if($cuenta->idHabitacion == 37 || $cuenta->idHabitacion == ''){
                                                        echo "Sin Asignar ";
                                                    }else{
                                                        echo $cuenta->numeroHabitacion;
                                                    }
                                                    
                                                ?>
                                            </div>
                                            <a href="<?php echo base_url()?>Medicamentos/detalle_cuenta_botiquin/<?php echo $cuenta->idCuentaBotiquin; ?>/" title="Ver detalle">
                                                <?php
                                                    echo '<span class="bold">'.$cuenta->nombrePaciente.'</span><br>';
                                                    echo '<span class="'.$tipoBange.'">'.$de.'</span>';
                                                ?>
                                            </a>
                                        </div>
                            <?php 
                                    }
                                }else{
                                    echo '<div class="col-md-12 alert alert-danger">
                                            <h6 class="text-center"><strong>No hay cuentas abiertas.</strong></h6>
                                        </div>';
                                }
                            ?>
                            
					</div>
				</div>
			</div>
		</div>
	</div>
    <datalist id="recomendacionesPacientes"></datalist>
</div>

<script>
    $(document).ready(function() {
        $("#porText").hide();
        $("#edadPaciente").val("0");
        $("#nombrePaciente").attr("required", false);
        $("#sinCuenta").attr("checked", false);
        $('.controlInteligente').select2({
            theme: "bootstrap4",
            // dropdownParent: $("#agregarGasto")
        });
    });
    /* $(document).on('change', '.existeHoja', function (event) {
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
    }); */

    $(document).on("change", "#pacienteIngresado", function(event) {
        event.preventDefault();
        var paciente = event.target.options[event.target.selectedIndex].dataset.idpaciente;
        var habitacion = event.target.options[event.target.selectedIndex].dataset.idhabitacion;
        var tomadade = event.target.options[event.target.selectedIndex].dataset.tomadade;
        var hoja = event.target.options[event.target.selectedIndex].dataset.hoja;
        
        $("#idPaciente").val(paciente);
        $("#habitacionP").val(habitacion);
        $("#tomadaDe").val(tomadade);
        $("#idHoja").val(hoja);
    });

    $(document).on("click", "#sinCuenta", function() {
        var valor = $('input:checkbox[name=sinCuenta]:checked').val();
        var base = $("#pacientesContainer").html();
        if (valor == "sinCuenta") {
            $("#pacienteIngresado").attr("required", false);
            $("#porSelect").hide();
            $("#porText").show();
        } else {
            $("#nombrePaciente").attr("required", false);
            $("#porSelect").show();
            $("#porText").hide();
         }
    });

    $(document).on('keyup', '#nombrePaciente', function (event) {
       event.preventDefault();
       $("#recomendacionesPacientes").html("");
       var paciente = $(this).val();

        $.ajax({
            url: "../recomendaciones_paciente",
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
            url: "../buscar_paciente",
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
</script>

