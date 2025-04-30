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
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> ISBM</a> </li>
                    <li class="breadcrumb-item"><a href="#">Detalle de la requisición </a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <?php
                            if($cuenta->estadoProcedimiento == 1){
                        ?>
                            <div class="col-md-6">
                                <form id="frmMedicamento">
                                    <input type="text" class="form-control" id="codigoMedicamento" name="codigoMedicamento" placeholder="Código del medicamento..." autocomplete="off" />
                                </form>
                                    
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Quirofanos/"><i class="fa fa-arrow-left"></i> Volver </a>
                                <a class="btn btn-danger btn-sm" href="#cerrarSala" data-toggle="modal"><i class="fa fa-file"></i> Cerrar cuenta </a>
                            </div>
                        <?php
                           }else{
                        ?>
                            <div class="col-md-6">
                                <h5>Detalle de insumos utilizados</h5>  
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Quirofanos/"><i class="fa fa-arrow-left"></i> Volver </a>
                            </div>
                        <?php
                           }
                        ?>

                    </div>
				</div>
			
            
				<div class="ms-panel-body">
                    <?php
                        if(sizeof($detalle) > 0){
                    ?>
                        <!-- Inicio -->
                            <div class="contenido">
                                <!-- <table id="tabla-pacientes" class="table table-striped thead-primary w-100"> -->
                                <table id="tablag" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Medicamento</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center" style="display: none;">ID</th>
                                            <th class="text-center" style="display: none;">Cuenta</th>
                                            <th class="text-center" style="display: none;">Actual</th>
                                            <?php
                                                if($cuenta->estadoProcedimiento == 1){
                                                    echo '<th class="text-center">Opción</th>';        
                                                }
                                            ?>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $index = 0;
                                            foreach ($detalle as $fila) {
                                                $index++;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $index; ?></td>
                                            <td class="text-center"><?php echo $fila->codigoMedicamento; ?></td>
                                            <td class="text-center"><?php echo $fila->nombreMedicamento; ?></td>
                                            <td class="text-center"><?php echo $fila->cantidadInsumo; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idDetalleProcedimiento; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <!-- Fin --> 
                    <?php
                        }else{

                        }
                    ?>
                </div>
            </div>
		</div>
	</div>
    <input type="hidden" value="<?php echo $cuenta->idProcedimiento; ?>" id="idProcedimiento"/>
    <input type="hidden" value="<?php echo $cuenta->idHoja; ?>" id="idHoja"/>
    <input type="hidden" value="<?php echo $this->session->userdata('id_usuario_h'); ?>" id="idUsuario"/>
    <input type="hidden" value="<?php echo $cuenta->estadoProcedimiento ; ?>" id="estadoProcedimiento"/>
</div>


<!-- Modal para cerrar hoja-->
<div class="modal fade p-5" id="cerrarSala" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-primary">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de cerrar esta cuenta?</p>
                    <p class="text-danger">(Este proceso es irreversible)</p>
                </div>

                <form class="needs-validation" action="<?php echo base_url()?>Quirofanos/cerrar_cuenta_descargos" method="post">
                    <input type="hidden" id="idProcedimiento" value="<?php echo $pivote; ?>" name="idProcedimiento">
                    <input type="hidden" id="valores" value="<?php echo urlencode(base64_encode(serialize($resumen))); ?>" name="valores">
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
        $("#codigoMedicamento").focus();
        var estadoProcedimientos = $("#estadoProcedimiento").val();
        
        $( "#frmMedicamento" ).submit(function( event ) {
            event.preventDefault();
            var datos = {
                idProcedimiento : $("#idProcedimiento").val(),
                idHoja : $("#idHoja").val(),
                idInsumo : '',
                precioInsumo: '',
                cantidad : 1,
                por: $("#idUsuario").val(),
                codigoMedicamento : $("#codigoMedicamento").val()
            }

            $("#codigoMedicamento").val("");

            $.ajax({
            url: "../../descontar_medicamento",
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
                        location.reload();
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

        });

        if(estadoProcedimientos == 1){
            $('#tablag').Tabledit({
                url: '../../editar_medicamento',
                columns: {
                    identifier: [0, 'fila'],
                    editable: [[3, 'cantidadNueva'],[4, 'detalleMedicamento']]
                },
                restoreButton:false,
            });
        }

    });
</script>

<script>
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

    $(document).on("click", '.btnEliminar', function(event) {
        // event.preventDefault();
        var datos = {
            detalleMedicamento: $(this).closest("tr").find('.detalleMedicamento').val()
        }

        $.ajax({
            url: "../../eliminar_medicamento",
            type: "POST",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (registro.length > 0){
                    
                }
            },
            error:function(){
                alert("Hay un error");
            }
        });

        $(this).closest('tr').remove();
        

    });

</script>