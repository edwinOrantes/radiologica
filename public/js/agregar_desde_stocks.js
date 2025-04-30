// Agregar medicamento a la lista
$(document).on('click', '.agregarDesdeStock', function(event) {
    event.preventDefault();
    
    $(this).closest('tr').remove();
    var datos = {
        idStock: $(this).closest('tr').find(".stockHM").val(),
        idHoja: $(this).closest('tr').find(".hojaHM").val(),
        idInsumo: $(this).closest('tr').find(".idM").val(),
        precioV: $(this).closest('tr').find(".precioM").val(),
        cantidad: $(this).closest('tr').find(".cantidadM").val()
    }
    
    $.ajax({
        url: "../../agregar_med_desde_stock",
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
                    toastr.success('Medicamento agregado con exito', 'Aviso!');
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
                    toastr.error('No se agrego el medicamento...', 'Aviso!');
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
                toastr.error('No se agrego el medicamento...', 'Aviso!');

            }
        }
    });
    $(".form-control-sm").focus();

    // console.log(datos);
});