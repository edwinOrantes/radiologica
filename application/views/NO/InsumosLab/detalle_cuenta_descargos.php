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
                <div class="row">
                    <div class="col-md-6">
                        <ol class="breadcrumb breadcrumb-arrow has-gap">
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Laboratorio</a> </li>
                            <li class="breadcrumb-item"><a href="#">Detalle de los descargos </a></li>
                        </ol>
                    </div>

                    <div class="col-md-6 text-right">
                        <div class="mensajeCodigo"></div>
                    </div>
                </div>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>InsumosLab/gestion_insumos/"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>

                        <!-- <div class="col-md-6">
                            <form action="">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control menosHeight" name="codigoMedicamento" id="codigoMedicamento" placeholder="Código del medicamento" required/>
                                            <div class="invalid-tooltip">
                                                Ingrese el codigo del medicamento.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control menosHeight" name="cantidadMedicamento" id="cantidadMedicamento" placeholder="Cantidad del medicamento" required/>
                                            <div class="invalid-tooltip">
                                                Ingrese la cantidad del medicamento.
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div> -->
                        <div class="col-md-6 text-right">
                            <?php
                                if($filaCuenta->estadoCuenta == 1 ){
                                    echo '<a href="#agregarMedicamentos" data-toggle="modal" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Agregar medicamentos</a> ';
                                    echo ' <a href="#cerrarCuenta" data-toggle="modal" class="btn btn-danger btn-sm"> <i class="fa fa-times"></i> Cerrar cuenta </a>';
                                }
                            ?>
                            <!-- <a class="btn btn-outline-primary btn-sm" href="<?php echo base_url()?>InsumosLab/resumen_gestion/<?php echo $cuenta; ?>" target="_blank"><i class="fa fa-file"></i> Resumen </a> -->
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
                    <div class="mensajeDelete text-center"></div>
                    <!-- Inicio -->
                        <div class="contenido">
                            <!-- <table id="tabla-pacientes" class="table table-striped thead-primary w-100"> -->
                            <?php
                                if(sizeof($datosCuenta) > 0){
                            ?>
                            <table id="tablag" class="table table-striped thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Código</th>
                                        <th class="text-center">Medicamento</th>
                                        <th class="text-center">Cantidad</th>
                                        
                                        <?php
                                            if($filaCuenta->estadoCuenta == 1){
                                        ?>
                                            <th class="text-center" style="display: none;">ID</th>
                                            <th class="text-center" style="display: none;">Cuenta</th>
                                            <th class="text-center" style="display: none;">Actual</th>
                                            <th class="text-center">Opción</th>
                                        <?php } ?>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $index = 0;
                                        foreach ($datosCuenta as $fila) {
                                            $index++;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $fila->codigoInsumoLab; ?></td>
                                        <td class="text-center"><?php echo $fila->nombreInsumoLab; ?></td>
                                        <td class="text-center"><?php echo $fila->cantidadInsumo; ?></td>

                                        <?php
                                            if($filaCuenta->estadoCuenta == 1){
                                        ?>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idInsumoLab; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idDescargosLab; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->cantidadInsumo; ?></td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php
                                }else{
                                    echo '<div class="alert-danger text-center p-4">No hay datos que mostrar...</div>';
                                }
                            ?>
                        </div>
                    <!-- Fin -->  
                </div>
            </div>
		</div>
	</div>
    <input type="hidden" value="<?php echo $cuenta; ?>" id="cuentaActual"/>
    <input type="hidden" value="<?php echo $filaCuenta->estadoCuenta; ?>" id="estadoCuentaActual"/>
    <input type="hidden" value="<?php echo date('Y-m-d'); ?>" id="fechaHoy"/>
</div>


<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="agregarMedicamentos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white">Lista de medicamentos</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body p-0 text-left">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <div class="ms-panel-body">
                                    <div class="mensaje text-center"></div>
                                    <div class="table-responsive mt-3">
                                        <table id="" class="table table-striped thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Medida</th>
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($insumos as $insumo) {
                                                ?>

                                                <tr>
                                                    <td class="text-center"><?php echo $insumo->codigoInsumoLab; ?></td>
                                                    <td><?php echo $insumo->nombreInsumoLab; ?></td>
                                                    <td class="text-center"><span class="badge badge-success"><?php echo $insumo->medidaInsumoLab; ?></span></td>
                                                    <td class="text-center">
                                                        <input type="hidden" value="<?php echo $insumo->idInsumoLab; ?>" class="form-control idIns menosHeight" />
                                                        <input type="hidden" value="<?php echo $insumo->controlado; ?>" class="form-control controladoIns menosHeight" />
                                                        <input type="text" value="1" id="test" class="form-control cantidadIns menosHeight" />
                                                    </td>
                                                    <td class="text-center"><a class='ocultarAgregar agregarInsumo' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a></td>
                                                </tr>

                                                <?php
                                                    }
                                                ?>
                                              <input type="hidden" value="<?php echo $cuenta; ?>" id="cuentaActual" />
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
<!-- Fin Modal para agregar datos del Medicamento-->

<!-- Modal para cerrar hoja-->
    <div class="modal fade p-5" id="cerrarCuenta" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-primary">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de cerrar esta cuenta?</p>
                </div>

                <form class="needs-validation" action="<?php echo base_url()?>InsumosLab/cerrar_cuenta_descargos" method="post">

                    <input type="hidden" id="estadoNuevo" value="0" name="estadoNuevo">
                    <input type="hidden" id="idCuentaEditar" value="<?php echo $cuenta; ?>" name="idCuentaEditar">
        
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-times"></i> Cerrar Cuenta</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
<!-- Fin Modal cerrar hoja-->


<script src="<?php echo base_url(); ?>public/js/jquery.tabledit.js"></script>

<script>

    $(document).ready(function() {
        /* $("#codigoMedicamento").focus();
        
        $( "#frmMedicamento" ).submit(function( event ) {
            event.preventDefault();
            var datos = {
                codigo : $("#codigoMedicamento").val(),
                cuenta : $("#cuentaActual").val()
            }

            $("#codigoMedicamento").val("");

            $.ajax({
            url: "../../descontar_medicamento",
            type: "GET",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (registro.length > 0){
                        var html = "";
                        var index = 0;
                        for (var i = 0; i < registro.length; i++) {
                            index++;
                            html += "<tr>";
                            html += "    <td class='text-center'>"+index+"</td>";
                            html += "    <td class='text-center'>"+registro[i]["codigoMedicamento"]+"</td>";
                            html += "    <td class='text-center'>"+registro[i]["nombreMedicamento"]+"</td>";
                            html += "    <td class='text-center'>"+registro[i]["cantidadMedicamento"]+"</td>";
                            html += "    <td class='text-center' style='display: none'>"+registro[i]["idMedicamento"]+"</td>";
                            html += "    <td class='text-center' style='display: none'>"+registro[i]["idDetalleCuenta"]+"</td>";
                            html += "    <td class='text-center' style='display: none'>"+registro[i]["cantidadMedicamento"]+"</td>";
                            html += "</tr>";
                        }

                        $("#tablag tbody").html(html);

                        $('#tablag').Tabledit({
                            url: '../../editar_medicamento',
                            columns: {
                                identifier: [0, 'fila'],
                                editable: [[1, 'codigo'], [2, 'nombreMedicamento'], [3, 'cantidad'], [4, 'idMedicamento'], [5, 'cuentaMedicamento'], [6, 'cantidadActual']]
                            },
                            restoreButton:false,
                        });
                    }
                },
                error:function(){
                    alert("Hay un error");
                }
            });

        }); */
        var estadoCuenta = $("#estadoCuentaActual").val();
        var fechaHoy = $("#fechaHoy").val();
        if(estadoCuenta == 1){
            $('#tablag').Tabledit({
                url: '../../editar_insumo',
                columns: {
                    identifier: [0, 'fila'],
                    editable: [[3, 'cantidad'], [4, 'idInsumo'], [5, 'cuentaInsumo'], [6, 'cantidadActual']]
                },
                restoreButton:false,
            });
        }
        

        $(document).on("click", '.tabledit-save-button', function(event) {
            event.preventDefault();
            var cantidad = $(this).closest("tr").find('.cantidad').val();
            $(this).closest("tr").find('.cantidadActual').val(cantidad);
            $(this).closest("tr").find('.txtcantidadActual').html(cantidad);
        });

        $(document).on("click", '.tabledit-edit-button', function(event) {
            event.preventDefault();
            $(this).closest("tr").find('.cantidad').focus();
        });

        $(document).on("keyup", '.tabledit-input', function(e) {
            // e.preventDefault();
            if(e.which == 13) {
                return false;
            }
        });

    });

    // Agregar medicamento
        $(document).on("click", ".agregarInsumo", function() {
            event.preventDefault();
            
            var datos = {
                cuentaActual : $("#cuentaActual").val(),
                idInsumo : $(this).closest("tr").find(".idIns").val(),
                cantidadInsumo : $(this).closest("tr").find(".cantidadIns").val(),
                controlado : $(this).closest("tr").find(".controladoIns").val(),
            }

            $.ajax({
                url: "../../sacar_de_stock",
                type: "POST",
                beforeSend: function () { },
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
                                toastr.success('Insumo ingresado', 'Aviso!');
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
                                toastr.error('Error al ingresar el insumo...', 'Aviso!');
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
                            toastr.error('Error al ingresar el insumo...', 'Aviso!');
                        }
                    },
                    error:function(){
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
                        toastr.error('Error al ingresar el insumo...', 'Aviso!');
                    }
            });
            $(".form-control-sm").focus();
            $(this).hide(); // Ocultando boton
            $(this).closest("tr").hide(); // Ocultando fila
        });

        $('.cantidadIns').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var datos = {
                    cuentaActual : $("#cuentaActual").val(),
                    idInsumo : $(this).closest("tr").find(".idIns").val(),
                    cantidadInsumo : $(this).closest("tr").find(".cantidadIns").val(),
                }

                $.ajax({
                    url: "../../sacar_de_stock",
                    type: "POST",
                    beforeSend: function () { },
                    data: datos,
                    success:function(respuesta){
                            var registro = eval(respuesta);
                            if (Object.keys(registro).length > 0){
                                if(registro.estado == 1){
                                    $(".mensaje").html('<div class="alert-success">Agregado con exito...</div>');
                                    $(".mensaje").fadeIn().delay(500).hide(1000);
                                }else{
                                    $(".mensaje").html('<div class="alert-danger">No se agrego el medicamento...</div>');
                                    $(".mensaje").fadeIn().delay(500).hide(1000);
                                }
                            }else{
                                $(".mensaje").html('<div class="alert-danger">No se agrego el medicamento...</div>');
                                $(".mensaje").fadeIn().delay(500).hide(1000);
                            }
                        },
                        error:function(){
                            $(".mensaje").html('<div class="alert-danger">No se agrego el medicamento debido a un error sql...</div>');
                            $(".mensaje").fadeIn().delay(500).hide(1000);
                        }
                    });
                $(".form-control-sm").focus();
                $(this).hide(); // Ocultando boton
                $(this).closest("tr").hide(); // Ocultando fila
            }
        });
    // Fin agregar medicamento


    // Editar medicamento
        /* $('#tablag').Tabledit({
            url: '../../editar_medicamento',
            columns: {
                identifier: [0, 'fila'],
                editable: [[1, 'codigo'], [2, 'nombreMedicamento'], [3, 'cantidad'], [4, 'idMedicamento'], [5, 'cuentaMedicamento'], [6, 'cantidadActual']]
            },
            restoreButton:false,
            editButton:false,
        }); */
    // Fin editar medicamento

    // Eliminar medicamento
        $(document).on("click", '.btnEliminar', function(event) {
            // event.preventDefault();
            var datos = {
                filaCuenta: $(this).closest("tr").find('.txtcuentaInsumo').html()
            }

            $.ajax({
                url: "../../eliminar_medicamento",
                type: "POST",
                beforeSend: function () { },
                data: datos,
                success:function(respuesta){
                        var registro = eval(respuesta);
                        if (registro.length > 0){}
                    },
                    error:function(){
                        alert("Hay un error");
                    }
            });

            $(this).closest('tr').remove();
            

        });
    // Fin eliminar medicamento

    // Actualizar pagina
        $(document).on('click', '.cancelar', function(event) {
            event.preventDefault();
            location.reload();
        });

        $(document).on('click', '.close', function(event) {
            event.preventDefault();
            location.reload();
        });
    // Fin actualizar pagina

    // Agregando medicamento a travez de codigo
         $('#cantidadMedicamento').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var datos = {
                    codigo : $("#codigoMedicamento").val(),
                    cantidad : $("#cantidadMedicamento").val(),
                    cuenta : $("#cuentaActual").val(),
                }
                console.log(datos);
                $.ajax({
                    url: "../../stock_x_codigo",
                    type: "POST",
                    beforeSend: function () { },
                    data: datos,
                    success:function(respuesta){
                        location.reload();
                    },
                    error:function(){
                        location.reload();
                    }
                });


                // Reseteando campos
                    $("#codigoMedicamento").val("").focus();
                    $("#cantidadMedicamento").val("");
                //Fin reseteo
            }
        });
    // Fin agregar medicamento a travez de codigo
    
</script>


<script>
    /* $(document).on("click", '.tabledit-save-button', function(event) {
        event.preventDefault();
        var cantidad = $(this).closest("tr").find('.cantidad').val();
        $(this).closest("tr").find('.cantidadActual').val(cantidad);
        $(this).closest("tr").find('.txtcantidadActual').html(cantidad);
    });

    $(document).on("click", '.tabledit-edit-button', function(event) {
        event.preventDefault();
        $(this).closest("tr").find('.cantidad').focus();
    });

    // Capturando evento "Enter"
    $(document).on("keyup", '.tabledit-input', function(e) {
        // e.preventDefault();
        if(e.which == 13) {
            return false;
        }
    });
 */
    /* $(document).on("click", '.btnEliminar', function(event) {
        // event.preventDefault();
        var datos = {
            idDescargo: $(this).closest("tr").find('.idDescargo').val(),
            cantidadDescargo: $(this).closest("tr").find('.cantidadDescargo').val(),
            idMedicamento: $(this).closest("tr").find('.idMedicamento').val()
        }

        $.ajax({
            url: "../../eliminar_medicamento",
            type: "POST",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                            if(registro.estado == 1){
                                $(".mensajeDelete").html('<div class="alert-success">Eliminado con exito...</div>');
                                $(".mensajeDelete").fadeIn().delay(500).hide(1000);
                            }else{
                                $(".mensajeDelete").html('<div class="alert-danger">No se elimino el medicamento...</div>');
                                $(".mensajeDelete").fadeIn().delay(500).hide(1000);
                            }
                        }else{
                            $(".mensajeDelete").html('<div class="alert-danger">No se elimino el medicamento...</div>');
                            $(".mensajeDelete").fadeIn().delay(500).hide(1000);
                        }
                },
                error:function(){
                    $(".mensajeDelete").html('<div class="alert-danger">No se elimino el medicamento debido a un error sql...</div>');
                    $(".mensajeDelete").fadeIn().delay(500).hide(1000);
                }
        });

        $(this).closest('tr').remove();
        

    }); */

</script>


