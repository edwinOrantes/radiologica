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

<style>
    .medidaInsumo {
        height: 30px
    }
</style>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Farmacia </a> </li>
                    <li class="breadcrumb-item"><a href="#">Agregar venta</a></li>
                    <li class="breadcrumb-item"><strong>#<?php echo $codigo; ?></strong></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<!-- <div class="ms-panel-header"></div> -->
				<div class="ms-panel-body">
					<div class="row">
                        <div class="col-md-6">
                            <div id="buscador">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-barcode"></i></div>
                                    </div>
                                    <input type="text" id="codigoMedicamento" placeholder="Código del medicamento" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div id="buscador">
                                <div class="input-group">
                                    <input type="text" list="lista_examenes"  id="nombreExamen" placeholder="Nombre del medicamento" class="form-control">
                                    <datalist id="lista_examenes"></datalist>
                                </div>
                            </div>
                        </div>

						<div class="col-md-12">
							<!-- <form id="frmVenta" class="needs-validation" method="post" action="<?php echo base_url(); ?>Ventas/guardar_venta" novalidate> Version OLD -->
							<!-- <form id="frmVenta" class="needs-validation" method="post" action="<?php echo base_url(); ?>Facturacion/sellar_factura" novalidate> -->
							<form id="frmVenta" class="needs-validation" method="post" action="<?php echo base_url(); ?>Facturacion/procesar_factura" novalidate>
                                <div class="form-row">

                                    <div class="col-md-8">
                                        
                                        <div class="tabla_productos">
                                            <div class="table-responsive">
                                                <table class="table table-bordered thead-primary table-sm mt-4"  id="tabla_productos">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th scope="col">#</th>
                                                            <th scope="col">Producto</th>
                                                            <th scope="col">Presentacion</th>
                                                            <th scope="col">Precio</th>
                                                            <th scope="col">Cantidad</th>
                                                            <th scope="col">Total</th>
                                                            <th scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="alert-danger text-center p-2">
                                            <!-- Contingencia -->
                                                <div class="form-group">
                                                    <label for=""><strong>Tipo de transmisión</strong></label>
                                                    <select class="form-control" id="tipoTransmision" name="tipoTransmision">
                                                        <option value="1">Transmisión normal</option>
                                                        <option value="2">Transmisión por contingencia</option>
                                                    </select>
                                                    <div class="invalid-tooltip"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for=""> <strong>Tipo de contingencia</strong> </label>
                                                    <select class="form-control" id="tipoContingencia" name="tipoContingencia">
                                                        <option value=""></option>
                                                        <option value="1">No disponibilidad de sistema del MH</option>
                                                        <option value="2">No disponibilidad de sistema del emisor</option>
                                                        <option value="3">Falla en el suministro de servicio de Internet del Emisor</option>
                                                        <option value="4">Falla en el suministro de servicio de energía eléctrica del Emisor que impida la transmision de los DTE</option>
                                                    </select>
                                                    <div class="invalid-tooltip">
                                                        Debes seleccionar el municipio del paciente.
                                                    </div>
                                                </div>

                                            <!-- Contingencia -->
                                        </div>
                                            
                                            
                                        <div>
                                        <input type="hidden" value="<?php echo $codigo; ?>" name="codigoVenta" id="codigoVenta">
                                            <table class="table table-borderless table-sm mt-4">
                                                <thead> </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2"><input type="date" value="<?php echo date('Y-m-d'); ?>" name="fechaVenta" class="form-control form-control-sm"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><input type="text" value="<?php echo $consulta->nombrePaciente; ?>" name="clienteVenta" placeholder="Nombre cliente" class="form-control"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="documentoPago" id="documentoPago" required="" class="form-control">
                                                                <option value="">.:: Seleccionar tipo de documento::.</option>
                                                                <!-- <option value="1"> Ticket </option> -->
                                                                <option value="2"> Consumidor final</option>
                                                                <option value="3">Crédito fiscal</option>
                                                                <option value="22"> Consumidor final (Perzonalizado)</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="numeroDocumento" id="numeroDocumento" class="form-control" required>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <select name="formaPago" id="formaPago" required="" class="form-control">
                                                                <option value="">.:: Seleccionar forma de pago::.</option>
                                                                <option value="Efectivo">Efectivo</option>
                                                                <option value="Credito">Crédito</option>
                                                                <option value="Tarjeta">Tarjeta</option>
                                                                <option value="Transferencia">Transferencia</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Subtotal</strong></td>
                                                        <td class="text-left"><p class="alert alert-info alert-outline bold p-2" id="lblSubtotal">0</p><input type="hidden" value="0" name="txtSubtotal" id="txtSubtotal"></td>
                                                    </tr>
                                                    <tr style="display: none">
                                                        <td><strong>IVA</strong></td>
                                                        <td class="text-left"><p class="alert alert-info alert-outline bold p-2" id="lblIVA">0</p><input type="hidden" value="0" name="txtIVA" id="txtIVA"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <td class="text-left"><p class="alert alert-info alert-outline bold p-2" id="lblTotal">0</p><input type="hidden" value="0" name="txtTotal" id="txtTotal"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Efectivo</strong></td>
                                                        <td><input type="text" id="txtDineroRecibido" name="txtDineroRecibido" value="" placeholder="Cantidad de dinero recibida" class="form-control alert-info bold" required></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Vuelto</strong></td>
                                                        <td><input type="text" id="txtVuelto" name="txtVuelto" value="0.00" class="form-control alert-info bold"></td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <!-- <input type="hidden" value="<?php echo $numser->idSerie; ?>" name="serie">
                                        <input type="hidden" value="<?php echo $numser->numeroActual; ?>" name="numActual">
                                        
                                        <input type="hidden" value="<?php echo $dte; ?>" name="dteFactura">
                                        <input type="hidden" value="<?php echo $baseDTE; ?>" name="baseDTE">
                                        <input type="hidden" value="<?php echo $cGeneracion; ?>" name="cGeneracion">
                                        <input type="hidden" value="<?php echo $paciente->idAnexo; ?>" name="pacienteVenta"> -->

                                        <input type="text" value="<?php echo $consulta->idConsulta; ?>" id="consultaActual" name="consultaActual">
        
                                        <button class="btn btn-primary btn-sm">Terminar</button>
                                    </div>
                                    
                                </div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$("#codigoMedicamento").focus();
	});

    $( "#codigoMedicamento" ).submit(function( event ) {
        event.preventDefault()

    });

    $(document).on("keydown", "#codigoMedicamento", function(e) {
        // Verificar si se ha presionado la tecla Enter (código 13)
        if (event.keyCode === 13 || event.key === 'Enter') {
            event.preventDefault(); // Detenemos submit
            var datos = {
                str : $(this).val(),
                pivote: 0
            };
            $("#codigoMedicamento").val("");

            $.ajax({
                url: "agregar_a_venta",
                type: "POST",
                beforeSend: function () { },
                data: datos,
                success:function(respuesta){
                        var registro = eval(respuesta);
                        if (registro.length > 0){
                            var html = "";
                            var index = 0;
                            
                            for (var i = 0; i < registro.length; i++) {
                                if(registro[i]["pivote"] > 0){
                                    index++;
                                    html += "<tr>";
                                    html += "    <td class='text-center'>"+index+"</td>";
                                    html += "    <td class='text-center'>"+registro[i]["nombreMedicamento"]+"<input type='hidden' name='nombreMedicamento[]' value='"+registro[i]["nombreMedicamento"]+"'>  <input type='hidden' name='idMedicamento[]' value='"+registro[i]["idMedicamento"]+"'> <input type='hidden' name='nombreInsumo[]' value='Unidad' class='nombreInsumo'> </td>";

                                    // Recorriendo medidas
                                    var select = '<select class="medidaInsumo form-control">';
                                        select += '    <option value="'+registro[i]["precioVMedicamento"]+'">Unidad-1</option>';
                                    if(registro[i]["medidasMedicamento"] && registro[i]["medidasMedicamento"].trim() !== ''){
                                        var medidas = JSON.parse(registro[i]["medidasMedicamento"]);
                                        // Iterar sobre los elementos del arreglo
                                        medidas.forEach(function(row) {
                                            select +='<option value="'+row.precio+'">'+row.nombreMedida+'-'+row.cantidad+'</option>';
                                        });
                                    }
                                    select += '</select>';
                                    // Iterar sobre los elementos del arreglo
                                    

                                    html += "    <td class='text-center'>"+select+"</td>";
                                    html += "    <td class='text-center'><strong class='lblPrecioVenta'>$"+registro[i]["precioVMedicamento"]+"</strong><input type='hidden' name='precios[]' value='"+registro[i]["precioVMedicamento"]+"' class='txtPrecioVenta'></td>";
                                    html += "    <td class='text-center'><strong style='display: none' class='lblCantidadVenta'>1</strong><input type='text' value='1' name='cantidad[]' class='txtcantidadVenta'> <input type='hidden' value='1' name='cantidadUnitaria[]' class='txtCantidadUnitaria'> </td>";
                                    html += "    <td class='text-center'><span class='totalRow'>"+(registro[i]["precioVMedicamento"] * 1)+"</span></td>";
                                    html += "    <td class='text-center'><i class='fa fa-times text-danger deleteRow'></i></td>";
                                    html += "</tr>";

                                    calculos(registro[i]["precioVMedicamento"], 1);
                                    $("#tabla_productos tbody").before(html);
                                }
                            }

                            /* 

                            $('#tablag').Tabledit({
                                url: '../../editar_medicamento',
                                columns: {
                                    identifier: [0, 'fila'],
                                    editable: [[1, 'codigo'], [2, 'nombreMedicamento'], [3, 'cantidad'], [4, 'idMedicamento'], [5, 'cuentaMedicamento'], [6, 'cantidadActual']]
                                },
                                restoreButton:false,
                            }); */
                        }
                    },
                    error:function(){
                        alert("Hay un error");
                    }
            });
        
        }
    });

    $(document).on("keyup", "#nombreExamen", function() {
        $("#lista_examenes").html();
        var lista = "";
        var datos = {
            str : $(this).val(),
        }

        $.ajax({
            url: "../../buscar_examen",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    for (let i = 0; i < registro.length; i++) {
                        lista += '<option value="'+registro[i]["nombreExamen"]+'">'+registro[i]["nombreExamen"]+'</option>';
                    }
                    $("#lista_examenes").html(lista);
                }
            }
        });
    });

    $(document).on("keydown", "#nombreExamen", function(e) {
        // Verificar si se ha presionado la tecla Enter (código 13)
        if (event.keyCode === 13 || event.key === 'Enter') {
            event.preventDefault(); // Detenemos submit
            var datos = {
                str : $(this).val(),
                consulta: $("#consultaActual").val()
            };
            $("#nombreExamen").val("");

            $.ajax({
                url: "../../agregar_a_consulta",
                type: "POST",
                beforeSend: function () { },
                data: datos,
                success:function(respuesta){
                    /* var registro = eval(respuesta);
                    if (registro.length > 0){
                        var html = "";
                        var index = 0;
                        
                        for (var i = 0; i < registro.length; i++) {
                            if(registro[i]["pivote"] > 0){
                                index++;
                                html += "<tr>";
                                html += "    <td class='text-center'>"+index+"</td>";
                                html += "    <td class='text-center'>"+registro[i]["nombreMedicamento"]+"<input type='hidden' name='nombreMedicamento[]' value='"+registro[i]["nombreMedicamento"]+"'> <input type='hidden' name='idMedicamento[]' value='"+registro[i]["idMedicamento"]+"'> <input type='hidden' name='nombreInsumo[]' value='Unidad' class='nombreInsumo'> </td>";

                                // Recorriendo medidas
                                var select = '<select class="medidaInsumo form-control">';
                                    select += '    <option value="'+registro[i]["precioVMedicamento"]+'">Unidad-1</option>';
                                if(registro[i]["medidasMedicamento"] && registro[i]["medidasMedicamento"].trim() !== ''){
                                    var medidas = JSON.parse(registro[i]["medidasMedicamento"]);
                                    // Iterar sobre los elementos del arreglo
                                    medidas.forEach(function(row) {
                                        select +='<option value="'+row.precio+'">'+row.nombreMedida+'-'+row.cantidad+'</option>';
                                    });
                                }
                                select += '</select>';
                                // Iterar sobre los elementos del arreglo
                                

                                html += "    <td class='text-center'>"+select+"</td>";
                                html += "    <td class='text-center'><strong class='lblPrecioVenta'>$"+registro[i]["precioVMedicamento"]+"</strong><input type='hidden' name='precios[]' value='"+registro[i]["precioVMedicamento"]+"' class='txtPrecioVenta'></td>";
                                html += "    <td class='text-center'><strong style='display: none' class='lblCantidadVenta'>1</strong><input type='text' value='1' name='cantidad[]' class='txtcantidadVenta'> <input type='hidden' value='1' name='cantidadUnitaria[]' class='txtCantidadUnitaria'> </td>";
                                html += "    <td class='text-center'><span class='totalRow'>"+(registro[i]["precioVMedicamento"] * 1)+"</span></td>";
                                html += "    <td class='text-center'><i class='fa fa-times text-danger deleteRow'></i></td>";
                                html += "</tr>";

                                calculos(registro[i]["precioVMedicamento"], 1);
                                $("#tabla_productos tbody").before(html);
                            }
                        }

                        

                        $('#tablag').Tabledit({
                            url: '../../editar_medicamento',
                            columns: {
                                identifier: [0, 'fila'],
                                editable: [[1, 'codigo'], [2, 'nombreMedicamento'], [3, 'cantidad'], [4, 'idMedicamento'], [5, 'cuentaMedicamento'], [6, 'cantidadActual']]
                            },
                            restoreButton:false,
                        });
                    } */
                },
                error:function(){
                    alert("Hay un error");
                }
            });
        
        }
    });

    $(document).on("keydown", "#txtDineroRecibido", function(e) {
        if (event.keyCode === 13 || event.key === 'Enter') {
            event.preventDefault(); // Detenemos submit
        }
    });

    $(document).on("change", "#txtDineroRecibido", function(e) {
        var recibido = parseFloat($(this).val());
        var total = parseFloat($("#txtTotal").val());
        var vuelto = recibido - total;
        $("#txtVuelto").val(vuelto.toFixed(2));

    });
    
    $(document).on("change", ".medidaInsumo", function(e) {
        e.preventDefault();
        var precio = $(this).val()
        var total = 0;

        var texto = $(this).find('option:selected').text().split('-');;
        var nombre = texto[0];
        var cantidad = texto[1];
        $(this).closest('tr').find('.txtCantidadUnitaria').val(cantidad * $(this).closest('tr').find('.txtcantidadVenta').val()); // asignando medidas unitarias
        /* var precio_unitario = (parseFloat(precio)/parseFloat(cantidad)).toFixed(2);
        $(this).closest('tr').find('.cantidadM').val(cantidad); */
        $(this).closest('tr').find('.nombreInsumo').val(nombre);
        $(this).closest('tr').find('.lblPrecioVenta').html(precio);
        $(this).closest('tr').find('.txtPrecioVenta').val(precio);

        $(this).closest('tr').find('.totalRow').html((precio*$(this).closest('tr').find('.txtcantidadVenta').val()).toFixed(2));

        $(".txtPrecioVenta").each(function() {
            total += ($(this).closest('tr').find('.txtPrecioVenta').val() * $(this).closest('tr').find('.txtcantidadVenta').val());
        });

        //Asignando totales
            // Subtotal
                var subtotal_new = total;
                $("#txtSubtotal").val(subtotal_new.toFixed(2))
                $("#lblSubtotal").html(subtotal_new.toFixed(2))
            // Subtotal

            // Subtotal
                var iva_new = subtotal_new * 0.13;
                /* $("#txtIVA").val(iva_new.toFixed(2))
                $("#lblIVA").html(iva_new.toFixed(2)) */
            // Subtotal

            // Subtotal
                // var total_new = subtotal_new + iva_new;
                var total_new = subtotal_new;
                $("#txtTotal").val(total_new.toFixed(2))
                $("#lblTotal").html(total_new.toFixed(2))
            // Subtotal

        //Asignando totales

    });

    $(document).on("change", ".txtcantidadVenta", function(e) {
        e.preventDefault();

        // Para saber cuanto es la cantidad de la medida
            var texto = $(this).closest('tr').find('.medidaInsumo option:selected').text().split('-');
            var cantidad = texto[1];
            $(this).closest('tr').find('.txtCantidadUnitaria').val(cantidad * $(this).closest('tr').find('.txtcantidadVenta').val()); // asignando medidas unitarias
        // Para saber cuanto es la cantidad de la medida

        var precio = $(this).closest('tr').find('.txtPrecioVenta').val();
        var cantidad = $(this).val();
        var total = 0;
        $(this).closest('tr').find('.lblCantidadVenta').html(cantidad);
        $(this).closest('tr').find('.totalRow').html((precio*cantidad).toFixed(2));

        $(".txtPrecioVenta").each(function() {
            total += $(this).closest('tr').find('.txtPrecioVenta').val() * $(this).closest('tr').find('.txtcantidadVenta').val();
        });

        //Asignando totales
            // Subtotal
                var subtotal_new = total;
                $("#txtSubtotal").val(subtotal_new.toFixed(2))
                $("#lblSubtotal").html(subtotal_new.toFixed(2))
            // Subtotal

            // Subtotal
                var iva_new = subtotal_new * 0.13;
                /* $("#txtIVA").val(iva_new.toFixed(2))
                $("#lblIVA").html(iva_new.toFixed(2)) */
            // Subtotal

            // Subtotal
                // var total_new = subtotal_new + iva_new;
                var total_new = subtotal_new;
                $("#txtTotal").val(total_new.toFixed(2))
                $("#lblTotal").html(total_new.toFixed(2))
            // Subtotal
        //Asignando totales

        // Entregado
            $("#txtDineroRecibido").val("")
            $("#txtVuelto").val(0)
        // entregado

    });
    
    $(document).on("change", "#txtDineroRecibido", function(e) {
        e.preventDefault();
        var recibido = parseFloat($(this).val());
        var total = $("#txtTotal").val();
        if(total > recibido){
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
            toastr.error('La cantidad recibida es menor', 'Aviso!');
            $("#txtDineroRecibido").val("");
            $("#txtVuelto").val("");
        }
    });


    $(document).on("click", ".deleteRow", function(e) {
        e.preventDefault();
        var total = 0;
        $(this).closest('tr').remove();
        $(".txtPrecioVenta").each(function() {
            total += ($(this).closest('tr').find('.txtPrecioVenta').val() * $(this).closest('tr').find('.txtcantidadVenta').val());
        });

        //Asignando totales
            // Subtotal
                var subtotal_new = total;
                $("#txtSubtotal").val(subtotal_new.toFixed(2))
                $("#lblSubtotal").html(subtotal_new.toFixed(2))
            // Subtotal

            // Subtotal
                var iva_new = subtotal_new * 0.13;
                /* $("#txtIVA").val(iva_new.toFixed(2))
                $("#lblIVA").html(iva_new.toFixed(2)) */
            // Subtotal

            // Subtotal
                // var total_new = subtotal_new + iva_new;
                var total_new = subtotal_new;
                $("#txtTotal").val(total_new.toFixed(2))
                $("#lblTotal").html(total_new.toFixed(2))
            // Subtotal

        //Asignando totales
    });


    function calculos(precio, cantidad){
        var subtotal_old = parseFloat($("#txtSubtotal").val());
        // var iva_old = parseFloat($("#txtIVA").val());
        var total_old = parseFloat($("#txtTotal").val());

        // Subtotal
            var subtotal_new = subtotal_old + (cantidad * precio);
            $("#txtSubtotal").val(subtotal_new.toFixed(2))
            $("#lblSubtotal").html(subtotal_new.toFixed(2))
        // Subtotal

        // Subtotal
            var iva_new = subtotal_new * 0.13;
            /* $("#txtIVA").val(iva_new.toFixed(2))
            $("#lblIVA").html(iva_new.toFixed(2)) */
        // Subtotal

        // Subtotal
            // var total_new = subtotal_new + iva_new;
            var total_new = subtotal_new;
            $("#txtTotal").val(total_new.toFixed(2))
            $("#lblTotal").html(total_new.toFixed(2))
        // Subtotal


        // Entregado
            $("#txtDineroRecibido").val("")
            $("#txtVuelto").val(0)
        // Entregado

    }

    $('#plazoFactura').on('change', function () {
        var valor = $(this).val();
        if(valor == 0){
            $("#estadoFactura").val("1");
        }
        else{
            $("#estadoFactura").val("1");
        }
    });

    $('#documentoPago').on('change', function () {
        var datos = {
            pivote: $(this).val()
        }
        var html = "";
        $.ajax({
            url: "obtener_numero",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                for (let i = 0; i < registro.length; i++) {
                    let numero = parseInt(registro[i]["numero"]);
                    $("#numeroDocumento").val(numero+1);
                }
            }
        });

    });
</script>