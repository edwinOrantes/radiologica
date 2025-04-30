$(document).ready(function(){

    var window_height = $(window).height();
    if(window_height <= 700){
        $("#hastaInicio").hide();
    }
    $("#hastaInicio").click(function() {
        $('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });
    

    $(".tabla-medicamentos").DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        /*"order": [[ 1, "desc"]]*/
    })

    $("#tabla-medicamentos").DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        "order": [[ 0, "desc"]]
    })

    $(".tablaPlus").DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        /* "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]] */
        /*"order": [[ 1, "desc"]]*/
    })

    $(".llegada-pacientes").DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
        // "order": [[ 0, "desc"]]
    })

    $(".tablaHojas").DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        "order": [[ 0, "desc"]],
    })

    $(".insumos-lab").DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        "order": [[ 0, "desc"]],
    })

    $('#tabla-externos').DataTable();
    $('#tabla-insumos').DataTable();
    $('#tabla-pacientes').DataTable();
    $('#tabla-gastos').DataTable({
        stateSave: true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        "order": [[ 0, "desc"]]
    });

    $("#tabla-paquetes").DataTable({
        "order": [[ 0, "desc"]]
    });

    $('#tabla-medicamentos-hoja').DataTable();
    $('#tabla-externos-hoja').DataTable();
    $('#time').timepicker();

    // Seleccionando los municipios de un determinado departamento de forma asincrona
    // $("#municipioPaciente").prop('disabled', true);
    /* $("#departamentoPaciente").change(function () {
        // $("#municipioPaciente").prop('disabled', false);
        $('#municipioPaciente').each(function(){
            $('#municipioPaciente option').remove();
        })
        $.ajax({
            url: "obtener_municipios",
            type: "GET",
            data: {id:$(this).val()},
            success:function(respuesta){
                var registro = eval(respuesta);
                    if (registro.length > 0)
                    {
                        var municipio = "";
                        for (var i = 0; i < registro.length; i++) 
                        {
                            municipio += "<option value='"+ registro[i]["codigoCatDetalle"] +"'>"+ registro[i]["valorCatDetalle"] +"</option>";
                        }
                        $("#municipioPaciente").append(municipio);
                    }
                }
            });
    }); */

    // Seleccionando los municipios de un determinado departamento de forma asincrona
    $("#municipioEmpleado").prop('disabled', true);
    $("#departamentoEmpleado").change(function () {
        $("#municipioEmpleado").prop('disabled', false);
        $('#municipioEmpleado').each(function(){
            $('#municipioEmpleado option').remove();
        })
        $.ajax({
            url: "obtener_municipios",
            type: "GET",
            data: {id:$(this).val()},
            success:function(respuesta){
                var registro = eval(respuesta);
                    if (registro.length > 0)
                    {
                        var municipio = "";
                        for (var i = 0; i < registro.length; i++) 
                        {
                            municipio += "<option value='"+ registro[i]["idMunicipio"] +"'>"+ registro[i]["nombreMunicipio"] +"</option>";
                        }
                        $("#municipioEmpleado").append(municipio);
                    }
                }
            });
    });

    $("#fullSize").click(function() {
        var elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    });

    $('.numeros').on('input', function () {
        this.value = this.value.replace(/[^0-9,.]/g, '').replace(/,/g, '.');
    });

      $('.disableSelect').keypress(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
		}
	});

    $(document).on('change', '.form-control-sm', function(event) {
        event.preventDefault();
        $(this).val(" ");
    });

    $("#reciboGenerado").slideDown("slow");


 });

 