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
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-list-alt"></i> Hemodiálisis </a> </li>
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
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>InsumosHemo/descargos_insumos/"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php

                                if($filaCuenta->estadoCuenta == 1 ){
                                    echo '<a href="#agregarMedicamentos" data-toggle="modal" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Agregar medicamentos</a> ';
                                    echo ' <a href="#cerrarCuenta" data-toggle="modal" class="btn btn-danger btn-sm"> <i class="fa fa-times"></i> Cerrar cuenta </a>';
                                }
                            ?>
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
                                        <td class="text-center"><?php echo $fila->codigoInsumoHemo; ?></td>
                                        <td class="text-center"><?php echo $fila->nombreInsumoHemo." <span class='badge badge-primary'>".$fila->entregadoA."</span> "; ?></td>
                                        <td class="text-center"><?php echo $fila->cantidadInsumo; ?></td>

                                        <?php
                                            if($filaCuenta->estadoCuenta == 1){
                                        ?>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idInsumoHemo; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idDescargosHemo; ?></td>
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
    <input type="hidden" value="<?php echo $filaCuenta->fechaCuenta; ?>" id="fechaDescargos"/>
    <input type="hidden" value="<?php echo $filaCuenta->estadoCuenta; ?>" id="estadoCuentaActual"/>
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
                                        <input type="text" class="form-control menosHeight" name="entregadoA" id="entregadoA" placeholder="Entregado a"><br> <!-- Para nombre de empleado -->
                                        <table id="" class="table table-striped thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Cantidad</th>
                                                    <th class="text-center" scope="col">Opción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($insumos as $insumo) {
                                                ?>

                                                <tr>
                                                    <td class="text-center"><?php echo $insumo->codigoInsumoHemo; ?></td>
                                                    <td><?php echo $insumo->nombreInsumoHemo; ?></td>
                                                    <td class="text-center">
                                                        <input type="hidden" value="<?php echo $insumo->idInsumoHemo; ?>" class="form-control idIns menosHeight" />
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

                    <form class="needs-validation" action="<?php echo base_url()?>InsumosHemo/cerrar_cuenta_descargos" method="post">

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
        var fechaCuenta = $("#fechaDescargos").val();
        var estadoCuentaActual = $("#estadoCuentaActual").val();
        if(estadoCuentaActual == 1){
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

            var entregadoA = $("#entregadoA").val();
            if(entregadoA == ""){
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
                toastr.error('Debes especificar a quien se le entrego el insumo...', 'Aviso!');
                $("#entregadoA").focus();
            }else{
             var datos = {
                    cuentaActual : $("#cuentaActual").val(),
                    idInsumo : $(this).closest("tr").find(".idIns").val(),
                    cantidadInsumo : $(this).closest("tr").find(".cantidadIns").val(),
                    entregadoA : entregadoA,
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
                                toastr.success('Medicamento actualizado', 'Aviso!');
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
                                toastr.error('No se actualizo el medicamento...', 'Aviso!');
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
                            toastr.error('No se actualizo el medicamento...', 'Aviso!');
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
                        toastr.error('No se agrego el medicamento debido a un error sql...', 'Aviso!');
                    }
                });
                $(".form-control-sm").focus();
                $(this).hide(); // Ocultando boton
                $(this).closest("tr").hide(); // Ocultando fila
               
            }
        });

        $('.cantidadIns').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                
                var entregadoA = $("#entregadoA").val();
                if(entregadoA == ""){
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
                    toastr.error('Debes especificar a quien se le entrego el insumo...', 'Aviso!');
                    $("#entregadoA").focus();
            }else{
                var datos = {
                    cuentaActual : $("#cuentaActual").val(),
                    idInsumo : $(this).closest("tr").find(".idIns").val(),
                    cantidadInsumo : $(this).closest("tr").find(".cantidadIns").val(),
                    entregadoA : entregadoA,
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
                                toastr.success('Medicamento actualizado', 'Aviso!');
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
                                toastr.error('No se actualizo el medicamento...', 'Aviso!');
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
                            toastr.error('No se actualizo el medicamento...', 'Aviso!');
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
                        toastr.error('No se agrego el medicamento debido a un error sql...', 'Aviso!');
                    }
                });
                $(".form-control-sm").focus();
                $(this).hide(); // Ocultando boton
                $(this).closest("tr").hide(); // Ocultando fila
            }

            }
        });
    // Fin agregar medicamento

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