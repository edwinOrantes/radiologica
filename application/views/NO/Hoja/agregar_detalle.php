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

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">
        <nav aria-label="breadcrumb">
				<ol class="breadcrumb pl-0">
					<li class="breadcrumb-item"><a href="#"><i class="fa fa-users "></i> Botíquin</a></li>
					<li class="breadcrumb-item"><a href="#">Lista medicamentos</a></li>
				</ol>
            </nav>
			<div class="ms-panel">

            <?php
                $totalInsumos = 0;
                foreach ($hoja as $detalle) {
                    $paciente = $detalle->nombrePaciente." ".$detalle->apellidoPaciente;
                    $ingreso = $detalle->fechaHoja;
                    $medico = $detalle->nombreMedico;
                    $tipo = $detalle->tipoHoja;
                    $idHoja = $detalle->idHoja;
                    $fechaHoja = $detalle->fechaHoja;
                    $estadoHoja = $detalle->estadoHoja;
                    $salidaHoja = $detalle->salidaHoja;
                    $habitacion = $detalle->numeroHabitacion;
                    $total = $detalle->totalHoja;
                    $totalInsumos =  $totalInsumos + ($detalle->precioInsumo * $detalle->cantidadInsumo );
                    $id ='"'.$detalle->idHoja.'"';
                    $idH ='"'.$detalle->idHabitacion.'"';
                }
                $totalExternos = 0;
                foreach ($externos as $detalleE) {
                    $totalExternos = $totalExternos + ($detalleE->cantidadExterno  * $detalleE->precioExterno);
                }
            ?>

				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Detalle de la hoja de cobro</h6></div>
                        <div class="col-md-6 text-right">
                                <?php
                                    
                                    if($estadoHoja == 1){
                                        echo "<a href='#agregarInsumo' class='btn btn-primary btn-sm' data-toggle='modal'><i class='fa fa-plus'></i> Agregar Insumo</a> ";
                                        echo " <a href='#agregarExterno' class='btn btn-primary btn-sm' data-toggle='modal'><i class='fa fa-plus'></i> Agregar Externo</a> ";
                                        echo " <a onclick='cerrarIngreso($id, $idH)' href='#cerrarIngreso' class='btn btn-outline-danger btn-sm' data-toggle='modal'><i class='fa fa-check'></i> Cerrar Ingreso </a>";
                                    }else{
                                        echo " <a href='".base_url()."HojaCobro/detalle_hoja_pdf/".$idHoja."' class='btn btn-primary btn-sm'><i class='fa fa-file-pdf'></i> Ver recibo</a>";
                                    }
                                ?>
                                <a href="<?php echo base_url()?>HojaCobro/lista_hojas" class="btn btn-outline-success btn-sm"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                    </div>
				</div>

				<div class="ms-panel-body">
                    <form action="<?php echo base_url();?>HojaCobro/agregar_detalle_hoja" method="post">
                        <input type="hidden" value="<?php echo $idHoja?>" name="idHoja"/>
                        <input type="hidden" value="<?php echo $fechaHoja?>" name="fechaHoja"/>
                        <div class="row pt-3 pb-3">
                            
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    
                                    <tr>
                                        <td class="text-center"><strong>Paciente:</strong></td>
                                        <td class="text-center"><?php echo $paciente; ?></td>
                                        <td class="text-center"><strong>Médico :</strong></td>
                                        <td class="text-center"><?php echo $medico; ?></td>
                                    </tr>

                                    <tr>
                                        <td class="text-center"><strong>Tipo: </strong></td>
                                        <td class="text-center"><?php echo $tipo; ?></td>
                                        <td class="text-center"><strong>Habitación</strong></td>
                                        <td class="text-center"><?php echo $habitacion; ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="text-center"><strong>Fecha Ingreso</strong></td>
                                        <td class="text-center"><?php echo $ingreso; ?></td>
                                        <td class="text-center"><strong>Fecha Salida:</strong></td>
                                        <td class="text-center"><?php echo $salidaHoja; ?></td>
                                    </tr>


                                </table>
                            </div>

                            <div class="col-md-4">
                                <table class="table table-bordered ml-1">
                                    <tr>
                                        <td class="text-right"><strong>Insumos y/o medicamentos</strong></td>
                                        <td class="text-right">$ <?php echo $totalInsumos; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Externos</strong></td>
                                        <td class="text-right">$ <?php echo $totalExternos; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Total</strong></td>
                                        <td class="text-right">$ <?php echo $totalInsumos + $totalExternos; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-5">
                            
                            <div class="col-md-6">
                                <h6 class="text-info text-center">Insumos y/o medicamentos</h6>
                                <table class="table table-borderless" id="tblInsumos">
                                    <tr class="alert-info">
                                        <td class="text-center">Insumo</td>
                                        <td class="text-center">Precio</td>
                                        <td class="text-center">Cantidad</td>
                                        <td class="text-center">Total</td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <?php
                                        $totalI= 0;
                                        foreach ($hoja as $detalle) {
                                            $totalI = $totalI + ($detalle->precioInsumo  * $detalle->cantidadInsumo);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $detalle->nombreMedicamento; ?></td>
                                        <td class="text-center">$<?php echo $detalle->precioInsumo ?></td>
                                        <td class="text-center"><?php echo $detalle->cantidadInsumo; ?></td>
                                        <td class="text-center">$<?php echo ($detalle->precioInsumo  * $detalle->cantidadInsumo) ?></td>
                                    </tr>
                                    <?php } ?>

                                    <input type="hidden" value="<?php echo $totalI; ?>" id="totalInsumosHoja"/>
                                    
                                    <tr id="insumosHoja">
                                        <!--<td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                        <td class="text-center" id="lblTotalInsumosHoja">
                                            <strong>$ <?php //echo $totalI; ?></strong> 
                                        </td>-->
                                    </tr>

                                </table>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-info text-center">Servicios Externos</h6>
                                <table class="table table-borderless" id="tblServiciosExternos">
                                    <tr class="alert-info">
                                        <td class="text-center">Insumo</td>
                                        <td class="text-center">Precio</td>
                                        <td class="text-center">Cantidad</td>
                                        <td class="text-center">Total</td>
                                    </tr>
                                    <?php
                                        $totalE = 0;
                                        foreach ($externos as $detalleE) {
                                            $totalE= $totalE + ($detalleE->cantidadExterno  * $detalleE->precioExterno);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $detalleE->nombreExterno; ?></td>
                                        <td class="text-center">$ <?php echo $detalleE->precioExterno ?></td>
                                        <td class="text-center"><?php echo $detalleE->cantidadExterno; ?></td>
                                        <td class="text-center">$ <?php echo ($detalleE->cantidadExterno  * $detalleE->precioExterno) ?></td>
                                    </tr>
                                    <?php } ?>
                                    <input type="hidden" value="<?php echo $totalE; ?>" id="totalExternosHoja"/>
                                    <tr id="externosHoja">
                                        <!--<td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                        <td class="text-center" id="lblTotalExternosHoja"><strong>$ <?php // echo $totalE; ?></strong></td>-->
                                    </tr>

                                </table>
                            </div>

                        </div>
                        
                        <div class="text-center" id="botonera">
                            <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar datos</button>
                            <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                        </div>
                    </form> 
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ============================================================== -->
<!-- Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->
<div class="modal fade" id="agregarInsumo" tabindex="-1" role="dialog" aria-hidden="true">
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

								<?php
									if( sizeof($medicamentos) > 0){
								?>
								
								<table id="tabla-medicamentos-hoja" class="table table-striped thead-primary w-100">
									<thead>
										<tr>
											<th class="text-center" scope="col">Nombre</th>
											<th class="text-center" scope="col">Existencia</th>
											<th class="text-center" scope="col">Opción</th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach ($medicamentos as $medicamento) {
											$id ='"'.$medicamento->idMedicamento.'"';
											$codigo ='"'.$medicamento->codigoMedicamento.'"';
											$nombre = '"'.$medicamento->nombreMedicamento.'"';
											$precioV = '"'.$medicamento->precioVMedicamento.'"';
											$stock = '"'.$medicamento->stockMedicamento.'"';
											$usados = '"'.$medicamento->usadosMedicamento.'"';
									?>
										<tr>
											<td class="text-center" scope="row"><?php echo $medicamento->nombreMedicamento?></td>
											<td class="text-center precio"><?php echo $medicamento->stockMedicamento?></td>
											<td class="text-center">
											<?php
												echo "<a onclick='agregarMedicamento($id, $codigo, $nombre, $precioV, $stock, $usados)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
											?>
												
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

								<?php
									}else{
										echo '<div class="alert alert-danger">
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
	</div>
</div>
<!-- ============================================================== -->
<!-- Fin Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->
<div class="modal fade" id="agregarExterno" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog ms-modal-dialog-width">
		<div class="modal-content ms-modal-content-width">
			<div class="modal-header  ms-modal-header-radius-0">
				<h4 class="modal-title text-white">Lista de servicios externos</h4>
				<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body p-0 text-left">
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel ms-panel-bshadow-none">
						<div class="ms-panel-body">
						<div class="table-responsive mt-3">

							<?php
								if( sizeof($listaExternos) > 0){
							?>

							<table id="tabla-externos-hoja" class="table table-striped thead-primary w-100">
								<thead>
									<tr>
										<th class="text-center" scope="col">Nombre</th>
										<th class="text-center" scope="col">Opción</th>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach ($listaExternos as $externo) {
										$id ='"'.$externo->idExterno.'"';
										$nombre ='"'.$externo->nombreExterno.'"';
								?>
									<tr>
										<td class="text-center" scope="row"><?php echo $externo->nombreExterno?></td>
										<td class="text-center">
										<?php
											echo "<a onclick='agregarExterno($id, $nombre)' class='ocultarAgregar' title='Agregar a la lista'><i class='fa fa-plus ms-text-primary addMed'></i></a>";
										?>
											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>

							<?php
								}else{
									echo '<div class="alert alert-danger">
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
	</div>
</div>
<!-- ============================================================== -->
<!-- Fin Modal para agregar datos del Medicamento-->
<!-- ============================================================== -->


<!-- Modal para cerrar Ingreso-->
<div class="modal fade" id="cerrarIngreso" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">

			<div class="modal-header bg-danger">
				<h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
			</div>

			<div class="modal-body text-center">
				<p class="h5">¿Estas seguro de cerrar este ingreso?</p>
			</div>
			<form action="<?php echo base_url() ?>HojaCobro/cerrar_ingreso" method="post">								
				<div class="text-center">
					<input type="hidden" id="hoja" name="hoja"/>
					<input type="hidden" id="hab" name="hab"/>
					<button class="btn btn-danger shadow-none"><i class="fa fa-trash"></i> Cerrar</button>
					<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				</div>
			</form>									
		</div>
	</div>
</div>
<!-- Fin Modal cerrar ingreso-->



<script>
    function agregarMedicamento(id, codigo, nombre, precioV, stock, usados){
            $("#botonera").show();
            var html = '';

            html += '<tr>';
            html += '    <td class="text-center">'+ nombre +'<input type="hidden" class="" value="'+id+'" name="idsMedicamentos[]" /><input type="hidden" class="" value="'+stock+'" name="stocksMedicamentos[]" /><input type="hidden" class="" value="'+usados+'" name="usadosMedicamentos[]" /></td>';
            html += '    <td class="text-center">'+ precioV +'<input type="hidden" class="precioInsumo" name="precioInsumo[]" value="'+ precioV +'" required></td>';
            html += '    <td class="text-center"><input type="number" class="cantidadInsumo" name="cantidadInsumo[]" required></td>';
            html += '    <td class="text-center totalUnitario">$ 0 </td>';
            html += '    <td class="text-center"><a class="quitarInsumo" title="Quitar de la lista"><i class="fa fa-times ms-text-danger"></i></a>';
            html += '        <input type="hidden" class="txtTotalUnitario" name="txtTotalUnitario[]" />';
            html += '    </td>';
            html += '</tr>';
            
            $("#insumosHoja").before(html);
            
    }

    $(document).on('change', '.cantidadInsumo', function (event) {
            event.preventDefault();
            var cantidad = $(this).val(); // Cantidad de cada caja de texto
            var precio = $(this).closest('tr').find(".precioInsumo").val(); // Precio de cada producto
            
            var totalInsumosHoja = $("#totalInsumosHoja").val(); // Total de insumos y/o servicios
            
            var totalUnitario = cantidad * parseFloat(precio); // Total por cada producto
            $(this).closest('tr').find(".totalUnitario").html(totalUnitario.toFixed(2));
            $(this).closest('tr').find(".txtTotalUnitario").val(totalUnitario.toFixed(2));
            
            var t = parseFloat(totalInsumosHoja) + parseFloat(totalUnitario);
            $("#lblTotalInsumosHoja").html("$ "+t); // Total de insumos y/o servicios
            $("#totalInsumosHoja").val(t); // Agregando valor a la caja del monto total de insumos
            
        
            
            
            
    });
        
    $(document).on('click', '.quitarInsumo', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        /*var valorADescontar = $(this).closest('tr').find(".totalUnitario").html();
        var totalConDescuento = parseFloat($("#totalInsumosHoja").val()) - parseFloat(valorADescontar);
        $("#lblTotalInsumosHoja").html("$ "+totalConDescuento.toFixed(2)); // Total de insumos y/o servicios
        $("#totalInsumosHoja").val(totalConDescuento.toFixed(2)); // Agregando valor a la caja del monto total de insumos*/

        var eFilas = $("#tblServiciosExternos tr .precioExterno").length;
        var iFilas = $("#tblInsumos tr .precioInsumo").length;
        if(eFilas == 0 && iFilas == 0){
            $("#botonera").hide();
        }
    });

    function agregarExterno(id, nombre){
        $("#botonera").show();
        var html = '';
        html += '<tr>';
        html += '    <td class="text-center">'+ nombre +'<input type="hidden" value="'+id+'" class="" name="idsExternos[]" required></td>';
        html += '    <td class="text-center"><input type="text" class="precioExterno" name="precioExterno[]" required></td>';
        html += '    <td class="text-center"><input type="number" class="cantidadExterno" name="cantidadExterno[]" value=1 required><input type="hidden" id="totalExternoU" class="totalExternoU" name="totalExternoUnitario[]" required></td>';
        html += '    <td class="text-center totalUnitarioExterno">$ 0 </td>';
        html += '    <td class="text-center">';
        html += '        <a class="quitarExterno" title="Quitar de la lista"><i class="fa fa-times ms-text-danger"></i></a>';
        html += '    </td>';
        html += '</tr>';
        $("#externosHoja").before(html);
    }
        
    $(document).on('change', '.precioExterno', function (event) {
        event.preventDefault();
        var cantidad = $(this).val(); // Cantidad de cada caja de texto
        var precio = $(this).closest('tr').find(".cantidadExterno").val(); // Precio de cada producto
        
        var totalUnitarioExterno = parseFloat(cantidad) * parseFloat(precio);
        
        var totalExternosHoja = $("#totalExternosHoja").val(); // Total de insumos y/o servicios
        
        totalFinalExternos = parseFloat(totalUnitarioExterno) + parseFloat(totalExternosHoja);
        $("#totalExternosHoja").val(totalFinalExternos);
        $("#lblTotalExternosHoja").html("$ "+totalFinalExternos);
        
        $(this).closest('tr').find(".totalUnitarioExterno").html(totalUnitarioExterno.toFixed(2));
        $(this).closest('tr').find("#totalExternoU").val(totalUnitarioExterno.toFixed(2));
        
    });

    $(document).on('change', '.cantidadExterno', function (event) {
        event.preventDefault();
        var cantidad = $(this).val(); // Cantidad de cada caja de texto
        var precio = $(this).closest('tr').find(".precioExterno").val(); // Precio de cada producto
        
        var totalUnitarioExterno = parseFloat(cantidad) * parseFloat(precio);
        
        var totalExternosHoja = $("#totalExternosHoja").val(); // Total de insumos y/o servicios
        
        totalFinalExternos = parseFloat(totalUnitarioExterno) + parseFloat(totalExternosHoja);
        $("#totalExternosHoja").val(totalFinalExternos);
        $("#lblTotalExternosHoja").html("$ "+totalFinalExternos);
        
        $(this).closest('tr').find(".totalUnitarioExterno").html(totalUnitarioExterno.toFixed(2));
        $(this).closest('tr').find("#totalExternoU").val(totalUnitarioExterno.toFixed(2));
        
    });

    $(document).on('click', '.quitarExterno', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        /* var valorADescontar = $(this).closest('tr').find(".totalUnitario").html();
        var totalConDescuento = parseFloat($("#totalInsumosHoja").val()) - parseFloat(valorADescontar);
        $("#lblTotalInsumosHoja").html("$ "+totalConDescuento.toFixed(2)); // Total de insumos y/o servicios
        $("#totalInsumosHoja").val(totalConDescuento.toFixed(2)); // Agregando valor a la caja del monto total de insumos*/
        
        var eFilas = $("#tblServiciosExternos tr .precioExterno").length;
        var iFilas = $("#tblInsumos tr .precioInsumo").length;
        if(eFilas == 0 && iFilas == 0){
            $("#botonera").hide();
        }
    });

    function cerrarIngreso(id, idH){
        document.getElementById("hoja").value = id;
        document.getElementById("hab").value = idH;
    }
</script>