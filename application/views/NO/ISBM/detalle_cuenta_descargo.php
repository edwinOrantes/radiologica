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
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> ISBM</a> </li>
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
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Isbm/lista_cuenta_descargos/"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>

                        <div class="col-md-6 text-right">
                            <?php
                                // if($datosCuenta->fechaDescargosBM == date('Y-m-d') ){
                                if($datosCuenta->estadoDescargosBM == 1 ){
                                    echo '<a href="#agregarMedicamentos" data-toggle="modal" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Agregar medicamentos</a>';
                                }
                            ?>
                            <a class="btn btn-outline-primary btn-sm" href="<?php echo base_url()?>Isbm/resumen_cuenta/<?php echo $cuenta; ?>" target="_blank"><i class="fa fa-file"></i> Resumen </a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
                    <div class="mensajeDelete text-center"></div>
                    <!-- Inicio -->
                        <div class="contenido">
                            <!-- <table id="tabla-pacientes" class="table table-striped thead-primary w-100"> -->
                            <?php
                                if(sizeof($detalleCuenta) > 0){
                            ?>
                            <table id="tablag" class="table table-striped thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Código</th>
                                        <th class="text-center">Medicamento</th>
                                        <th class="text-center">Cantidad</th>
                                        
                                        <?php
                                            // if(date('Y-m-d') == $datosCuenta->fechaDescargosBM){
                                            if($datosCuenta->estadoDescargosBM == 1){
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
                                        foreach ($detalleCuenta as $fila) {
                                            $index++;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $fila->codigoMedicamento; ?></td>
                                        <td class="text-center">
                                            <?php
                                                if($fila->pivote != ""){
                                                    echo $fila->nombreMedicamento.' <span class="badge badge-success">'.$fila->pivote.'</span>';
                                                }else{
                                                    echo $fila->nombreMedicamento;
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo $fila->cantidadMedicamento; ?></td>

                                        <?php
                                            if($datosCuenta->estadoDescargosBM == 1){
                                        ?>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idMedicamento; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idDescargosBM; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->cantidadMedicamento; ?></td>
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
    <input type="hidden" value="<?php echo $datosCuenta->fechaDescargosBM; ?>" id="fechaDescargos"/>
    <input type="hidden" value="<?php echo date('Y-m-d'); ?>" id="fechaHoy"/>
    <input type="hidden" value="<?php echo $datosCuenta->estadoDescargosBM; ?>" id="estadoCD"/>
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
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <?php
                                                        if($datosCuenta->areaDescargosBM == "E"){
                                                            echo '<th class="text-center" scope="col">Empleado</th>';
                                                            
                                                        }
                                                    ?>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($medicamentos as $medicamento) {
                                                ?>

                                                <tr>
                                                    <td class="text-center"><?php echo $medicamento->codigoMedicamento; ?></td>
                                                    <td><?php echo $medicamento->nombreMedicamento; ?></td>
                                                    <td class="text-center">
                                                        <input type="hidden" value="<?php echo $medicamento->idMedicamento; ?>" class="form-control idMed menosHeight" />
                                                        <input type="text" value="1" id="test" class="form-control cantidadM menosHeight" />
                                                    </td>
                                                    <?php
                                                        if($datosCuenta->areaDescargosBM == "E"){
                                                    ?>
                                                       <td class="text-center">
                                                           <input type="text" id="" class="form-control empleadoM menosHeight" required/>
                                                       </td>
                                                    <?php
                                                       }
                                                    ?>
                                                    <td class="text-center"><a class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a></td>
                                                </tr>

                                                <?php
                                                    }
                                                ?>
                                              <input type="hidden" value="<?php echo $cuenta; ?>" id="cuentaActual" />
                                            </tbody>
                                        </table>
                                    </div>
                                    <input type="hidden" id="pivoteArea" value="<?php echo $datosCuenta->areaDescargosBM; ?>">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
<!-- Fin Modal para agregar datos del Medicamento-->




<script src="<?php echo base_url(); ?>public/js/jquery.tabledit.js"></script>

<script>

    $(document).ready(function() {
        $("#codigoMedicamento").val("").focus();
    });

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
        var fechaCuenta = $("#fechaDescargos").val();
        var fechaHoy = $("#fechaHoy").val();
        var estadoCD = $("#estadoCD").val();
        // if(fechaCuenta == fechaHoy){
        if(estadoCD == 1){
            $('#tablag').Tabledit({
                url: '../../editar_medicamento',
                columns: {
                    identifier: [0, 'fila'],
                    editable: [[3, 'cantidad'], [4, 'idMedicamento'], [5, 'cuentaMedicamento'], [6, 'cantidadActual']]
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
        $(document).on("click", ".agregarMedicamento", function() {
            event.preventDefault();
            var strPivote = "";
            var pivote = $("#pivoteArea").val();
            if(pivote == "E"){
                strPivote = $(this).closest("tr").find(".empleadoM").val();
            }else{
                strPivote = "";
            }
            var datos = {
                cuentaActual : $("#cuentaActual").val(),
                idMedicamento : $(this).closest("tr").find(".idMed").val(),
                cantidadMedicamento : $(this).closest("tr").find(".cantidadM").val(),
                destino : strPivote
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
        });

        $('.cantidadM').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var strPivote = "";
                var pivote = $("#pivoteArea").val();
                if(pivote == "E"){
                    strPivote = $(this).closest("tr").find(".empleadoM").val();
                }else{
                    strPivote = "";
                }
                var datos = {
                    cuentaActual : $("#cuentaActual").val(),
                    idMedicamento : $(this).closest("tr").find(".idMed").val(),
                    cantidadMedicamento : $(this).closest("tr").find(".cantidadM").val(),
                    destino : strPivote
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


    // Eliminar medicamento
        $(document).on("click", '.btnEliminar', function(event) {
            // event.preventDefault();
            var datos = {
                filaCuenta: $(this).closest("tr").find('.txtcuentaMedicamento').html()
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


