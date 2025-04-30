
    $(document).on("change", "#seguroHoja", function() {
        var seguro = $(this).val();
        var datos = {
            seguro: seguro,
            hoja: $("#idHoja").val()
        }

        var html = '';

        if(seguro == 9 || seguro == 10){
            $.ajax({
                url: "../../../Hoja/buscar_empleado",
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
                        });
                    }
                }
            });
        }else{
            if(seguro == 11 || seguro == 12){
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