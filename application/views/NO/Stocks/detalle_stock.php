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


<?php
	$porVencer = 0;
	function dias($i = null, $f = null){
		$cadena = "";
        $inicio= new DateTime($i);
        $fin= new DateTime($f);
        $dias = $inicio->diff($fin);

        if($inicio < $fin){
            // El resultados sera 3 dias
            switch ($dias->days) {
                case ($dias->days <= 90):
                    $cadena = '<span class="badge badge-danger">'.$dias->days.' dias</span>';
                    break;
                case ($dias->days >= 90 && $dias->days <=100):
                    $cadena = '<span class="badge badge-success">'.$dias->days.' dias</span>';
                    break;
                    
                    default:
                        $cadena = '<span class="badge badge-primary">'.$dias->days.' dias</span>';
                        break;
                    
                }
                
        }else{
            $cadena = '<span class="badge badge-danger">'.$dias->days.' dias de Vencido </span>';  
        }
         return $cadena;

	}

    function nombreStock($param = null){
        $stock = "";
        switch ($param) {
            case 1:
                $stock = "Caja de paro del nivel 1";
                break;
            case 2:
                $stock = "Caja de paro del nivel 2";
                break;
            case 3:
                $stock = "Caja de paro del nivel 3";
                break;
            case 4:
                $stock = "Caja de paro del nivel 4";
                break;
            case 5:
                $stock = "Stock emergencia";
                break;
            
            default:
                $stock = "";
                break;
        }

        return $stock;
    }
?>

<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Stock </a> </li>
                    <li class="breadcrumb-item"><a href="#">Detalle Stock</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Detalle: <?php echo nombreStock($stock);?></h6></div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary btn-sm" href="#agregarMedicamento" data-toggle="modal"><i class="fa fa-plus"></i> Agregar medicamento</button>
                            <!-- <a href="<?php echo base_url()?>Gastos/gastos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
                        <?php
                            if(sizeof($detalle) > 0){
                        ?>
                            <div class="table-responsive">
                                <table id="tabla-pacientes" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Vencimiento</th>
                                            <th class="text-center">Dias para vencer</th>
                                            <th class="text-center">Stock actual</th>
                                            <th class="text-center">Stock fijo</th>
                                            <th class="text-center">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($detalle as $row) {
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?= $row->codigoMedicamento  ?></td>
                                                    <td class="text-center"><?= $row->nombreMedicamento  ?></td>
                                                    <td class="text-center"><?= $dias = !is_null($row->fechaVencimiento) ? $row->fechaVencimiento : "---";  ?></td>
                                                    <td class="text-center"><?= $dias = !is_null($row->fechaVencimiento) ? dias(date("Y-m-d"), $row->fechaVencimiento) : "---";  ?></td>
                                                    <td class="text-center"><?= $row->stockInsumo  ?></td>
                                                    <td class="text-center"><?= $row->debeInsumo  ?></td>
                                                    <td class="text-center">
                                                        <input type="hidden" value="<?= $row->stockInsumo;  ?>" class="stockInsumo" >
                                                        <input type="hidden" value="<?= $row->debeInsumo;  ?>" class="debeInsumo" >
                                                        <input type="hidden" value="<?= $row->fechaVencimiento;  ?>" class="fechaVencimiento" >
                                                        <input type="hidden" value="<?= $row->idDetalleStock;  ?>" class="filaEditar" >
                                                    <?php
                                                        echo "<a href='#actualizarDatos' data-toggle='modal' class='actualizarDatos' title='Editar datos'><i class='fas fa-pencil-alt ms-text-primary'></i></a>";
                                                        echo "<a href='#eliminarDatos' data-toggle='modal' class='eliminarDatos' title='Eliminar datos'><i class='fas fa-trash-alt ms-text-danger'></i></a>";
                                                    ?>
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                           }else{
                                echo '<div class="alert alert-danger col-md-12">
                                    <h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
                                </div>';
                           }
                        ?>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="agregarMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <div class="table-responsive mt-3">
                                    <table id="" class="table table-striped thead-primary w-100 tabla-medicamentos">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">Código</th>
                                                <th class="text-center" scope="col">Nombre</th>
                                                <th class="text-center" scope="col">Vencimiento</th>
                                                <th class="text-center" scope="col">Precio</th>
                                                <th class="text-center" scope="col">Cantidad</th>
                                                <th class="text-center" scope="col">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($medicamentos as $medicamento) {
                                                    if($medicamento->ocultarMedicamento == 0 && $medicamento->pivoteMedicamento == 0){
                                            ?>
                                            <tr class="filaMedicamento">
                                                <td class="text-center" scope="row"><?php echo $medicamento->codigoMedicamento; ?></td>
                                                <td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento; ?></td>
                                                <td class="text-center" scope="row"><?php echo $medicamento->fechaVencimiento; ?></td>
                                                <td class="text-center" scope="row">$<?php  echo number_format($medicamento->precioVMedicamento, 2); ?></td>
                                                <td>
                                                    <input type="text" value="1" class="form-control cantidadM" />
                                                    <input type="hidden" value="<?php echo $medicamento->idMedicamento; ?>" class="form-control idM" />
                                                    <input type="hidden" value="<?php echo $stock; ?>" class="stockActual" />
                                                    <input type="hidden" value="<?php echo $medicamento->fechaVencimiento; ?>" class="form-control fVencimiento" />
                                                </td>
                                                <td class="text-center">
                                                    <a class='ocultarAgregar agregarMedicamento' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary'></i></a>
                                                </td>
                                            </tr>
                                            <?php  } } ?>
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

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="actualizarDatos" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del stock</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" method="post" action="<?php echo base_url() ?>Stock/actualizar_detalle_stock"  novalidate>
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Stock:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="stockInsumo" name="stockInsumo" placeholder="Nombre del stock" required>
                                                <div class="invalid-tooltip">
                                                    Cantidad de stock.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Stock fijo:</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="fijoInsumo" name="fijoInsumo" placeholder="Nombre del stock" required>
                                                <div class="invalid-tooltip">
                                                    Cantidad de fijo.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Fecha vencimiento:</strong></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" placeholder="Fecha de vencimiento" required>
                                                <div class="invalid-tooltip">
                                                    Fecha de vencimiento
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <input type="hidden" class="form-control" id="filaEditar" name="filaEditar" required>
                                        <input type="hidden" class="form-control" value="<?php echo $stock;?>" id="stockActual" name="stockActual" required>
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal para agregar datos del Medicamento-->

<script>
    $(document).on('click', '.agregarMedicamento', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        var datos = {
            idStock: $(this).closest('tr').find(".stockActual").val(),
            idInsumo: $(this).closest('tr').find(".idM").val(),
            stock: $(this).closest('tr').find(".cantidadM").val(),
            debe: $(this).closest('tr').find(".cantidadM").val(),
            vencimiento: $(this).closest('tr').find(".fVencimiento").val()
        }
        $.ajax({
            url: "../../agregar_a_stock",
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
                        toastr.success('Datos guardados', 'Aviso!');
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
                        toastr.error('No se guardaron los datos', 'Aviso!');
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
                    toastr.error('No se guardaron los datos', 'Aviso!');

                }
            }
        });
        $(".form-control-sm").focus();
    });

    $('.cantidadM').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            // ejecutando procesos
            $(this).closest('tr').remove();
            var datos = {
                idStock: $(this).closest('tr').find(".stockActual").val(),
                idInsumo: $(this).closest('tr').find(".idM").val(),
                stock: $(this).closest('tr').find(".cantidadM").val(),
                debe: $(this).closest('tr').find(".cantidadM").val(),
                vencimiento: $(this).closest('tr').find(".fVencimiento").val()
            }
            $.ajax({
                url: "../../agregar_a_stock",
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
                            toastr.success('Datos guardados', 'Aviso!');
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
                            toastr.error('No se guardaron los datos', 'Aviso!');
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
                        toastr.error('No se guardaron los datos', 'Aviso!');

                    }
                }
            });
            $(".form-control-sm").focus();
        }
    });

    $(document).on("click", ".actualizarDatos", function(e) {
        e.preventDefault();
        $("#filaEditar").val($(this).closest('tr').find('.filaEditar').val());
        $("#stockInsumo").val($(this).closest('tr').find('.stockInsumo').val());
        $("#fijoInsumo").val($(this).closest('tr').find('.debeInsumo').val());
        $("#fechaVencimiento").val($(this).closest('tr').find('.fechaVencimiento').val());
    });

    $(document).on("click", ".eliminarDatos", function(e) {
        e.preventDefault();
        var datos = {
            fila : $(this).closest('tr').find('.filaEditar').val()
        };
        
        
        $.ajax({
            url: "../../eliminar_detalle_stock",
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
                        toastr.success('Datos eliminados', 'Aviso!');
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
                        toastr.error('Error al eliminar los datos.', 'Aviso!');
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
                    toastr.error('Error al eliminar los datos.', 'Aviso!');

                }
            }
        });
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.close', function(event) {
        event.preventDefault();
        location.reload();
    });
</script>