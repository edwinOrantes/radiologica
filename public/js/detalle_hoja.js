// Gestion datos de paciente en hoja de cobro
    $(document).on("click", "#user-flotante", function(event) {
    event.preventDefault();
    var paciente = $("#idPaciente").val();

    var datos = {
        id : paciente
    }    
    $.ajax({
        url: "../../../Paciente/obtener_detalle",
        type: "POST",
        data: datos,
        success:function(respuesta){
            var registro = eval(respuesta);
                if (registro.length > 0){
                    for (let i = 0; i < registro.length; i++) {
                        $("#idPacienteU").val(registro[i]["idPaciente"]);
                        $("#nombrePaciente").val(registro[i]["nombrePaciente"]);
                        $("#edadPaciente").val(registro[i]["edadPaciente"]);
                        $("#telefonoPaciente").val(registro[i]["telefonoPaciente"]);
                        $("#duiPaciente").val(registro[i]["duiPaciente"]);
                        $("#nacimientoPaciente").val(registro[i]["nacimientoPaciente"]);
                        $("#direccionPaciente").val(registro[i]["direccionPaciente"]);
                        
                    }
                }
            }
        });
        
    });
// Fin gestion

/* Off canvas */
    jQuery(document).ready(function($){
        $(document).on('click', '.pull-bs-canvas-right', function(){
            $('body').prepend('<div class="bs-canvas-overlay bg-dark position-fixed w-100 h-100"></div>');
            if($(this).hasClass('pull-bs-canvas-right')){
                $('.bs-canvas-right').addClass('mr-0');
            }
            
            
            return false;
        });
        
        $(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function(){
            var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
            elm.removeClass('mr-0 ml-0');
            $('.bs-canvas-overlay').remove();
            return false;
        });
    });

    function editarProcedimiento() {
        var label = document.getElementById('labelProcedimiento');
        var input = document.getElementById('inputProcedimiento');
        var editIcon = document.getElementById('editIcon');
        var checkIcon = document.getElementById('checkIcon');
        
        if (input.style.display === "none") {
            // Cambiar a modo edición
            input.value = label.textContent;
            label.style.display = "none";
            input.style.display = "block";
            editIcon.style.display = "none";
            checkIcon.style.display = "inline";
        } else {
            // Cambiar a modo vista
            label.textContent = input.value;
            label.style.display = "block";
            input.style.display = "none";
            editIcon.style.display = "inline";
            checkIcon.style.display = "none";
        }
    }

    $(document).on("click", "#checkIcon", function() {
        var datos = {
            procedimiento: $("#inputProcedimiento").val(),
            hoja: $("#idHoja").val(),
        }

        $.ajax({
            url: "../../actualizar_procedimiento",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
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
                        toastr.success('Procedimiento actualizado', 'Aviso!');
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
                        toastr.error('No se actualizo el procedimiento...', 'Aviso!');
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
                    toastr.error('No se actualizo el procedimiento...', 'Aviso!');
                }
            }
        });

        // console.log(datos);
    });


    $(document).on("change", "#seguroHoja", function() {
        var seguro = $(this).val();
        var datos = {
            seguro: seguro,
            hoja: $("#idHoja").val()
        }

        var html = '';

        if(seguro == 9 || seguro == 10){
            $.ajax({
                url: "../../buscar_empleado",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if ((registro).length > 0){
                        html += '<label for=""><strong>Empleado responsable</strong></label>';
                        html += '<div class="input-group">';
                        html += '    <select class="form-control controlEmpleados" id="nombreResponsableCuenta" name="nombreResponsableCuenta" required>';
                        html += '        <option value="">.:: Seleccionar ::.</option>';
                        for (let i = 0; i < registro.length; i++) {
                            html += '        <option value="'+registro[i]["idEmpleado"]+'">'+registro[i]["nombreEmpleado"]+'</option>';
                        }
                        html += '    </select>';
                        html += '    <div class="invalid-tooltip">';
                        html += '        Seleccione un empleado.';
                        html += '    </div>  ';
                        html += '</div>';

                        $("#responsableFamiliar").html(html);
                        $('.controlEmpleados').select2({
                            theme: "bootstrap4",
                            dropdownParent: $("#agregarDescuento")
                        });
                    }
                }
            });
        }else{
            if(seguro == 11){
                html += '<label for=""><strong>Detalle:</strong></label>';
                html += '<div class="input-group">';
                html += '    <input type="text" class="form-control" id="nombreResponsableCuenta" name="nombreResponsableCuenta" placeholder="Agregue el detalle" required/>';
                html += '    <div class="invalid-tooltip">';
                html += '        Agregar detalle.';
                html += '    </div>  ';
                html += '</div>';
                $("#responsableFamiliar").html(html);
            }
            else{
                $("#responsableFamiliar").html("");
            }
        }


        

    });

    
    
/* Off canvas */