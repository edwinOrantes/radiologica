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
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrow has-gap">
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Herramientas </a> </li>
                            <li class="breadcrumb-item"><a href="#">Movimientos Insumo</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right"></div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
						<div class="col-md-12">
                            <form class="needs-validation" method="post" action="<?php echo base_url(); ?>Limpieza/seguimiento_insumos" novalidate>
								<div class="row">
									<div class="col-md-12" style="margin: 0 auto;">
                                        <div class="form-row">
       
                                            <div class="col-md-10">
												<label for=""><strong>Fecha pivote:</strong></label>
												<div class="input-group">
													<input type="date" name="actualizadoInsumo" id="actualizadoInsumo" class="form-control" value="<?php echo $fecha; ?>" required>
													<div class="invalid-tooltip">
														Ingrese la fecha.
													</div>  
												</div>
											</div>

                                            <div class="text-center col-md-2 mt-1">
                                                <button class="btn btn-success btn-sm mt-4" type="submit"> <i class="fa fa-search"></i> Consultar </button>
                                            </div>

										</div>
									</div>
								</div>                              
							</form>
						</div>
					</div>
				</div>
				<div class="ms-panel-body">
                    <?php 
                        if(isset($insumos)){
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center">Detalle de movimientos desde el : <?php echo $fecha; ?> </h5>
                            <div class="table-responsive">
                                <table class="table table-striped thead-primary w-100 insumos-lab">
                                    <thead class="">
                                        <tr>
                                            <th>Código</th>
                                            <th>Insumo</th>
                                            <th>Stock</th>
                                            <th>Compras</th>
                                            <th>Salidas</th>
                                            <th class="alert-danger">Base</th>
                                            <th class="alert-danger">Resultado</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            <?php 
                                                foreach ($insumos as $insumo) {
                                            ?>
                                            <tr>
                                                <td><?php echo $insumo["codigo"]; ?></td>
                                                <td><?php echo $insumo["insumo"]; ?></td>
                                                <td><?php echo $insumo["stock"]; ?></td>
                                                <td><?php echo $insumo["compras"]; ?></td>
                                                <td><?php echo $insumo["salidas"]; ?></td>
                                                <td class="alert-danger"><?php echo $insumo["stockPivote"]; ?></td>
                                                <td class="alert-danger"><?php echo ($insumo["stock"] + $insumo["salidas"]) - $insumo["compras"]; ?></td>
                                            </tr>
                                            <?php 
                                                }
                                            ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php
                        }
                    ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script>


    $(document).on("click", "#consultarInsumo", function(event) {
        event.preventDefault();
        var datos = {
            codigoInsumo : $("#codigoInsumo").val(),
            actualizadoInsumo : $("#actualizadoInsumo").val(),
        }
        $.ajax({
            url: "buscarDatos",
            type: "POST",
            data: datos,
            success:function(respuesta){
                console.log(JSON.stringify(respuesta));
                var registro = eval(respuesta);
                var html = '';
                /* if (registro.length > 0){
                    let $codigo = "";
                    let nombre = "";
                    let stock = "";
                    for (let i = 0; i < registro.length; i++){
                        codigo = registro[i]["codigoInsumoLimpieza"];
                        nombre = registro[i]["nombreInsumoLimpieza"];
                        stock = registro[i]["stockInsumoLimpieza"];
                    }
                    html += '    <table class="table table-bordered">';
                    html += '        <tr class="alert-info">';
                    html += '            <td class="text-center"><strong>Código del insumo:</strong></td>';
                    html += '            <td class="text-left">'+codigo+'</td>';
                    html += '            <td class="text-center"><strong>Nombre del insumo:</strong></td>';
                    html += '            <td class="text-left">'+nombre+'</td>';
                    html += '            <td class="text-center"><strong>Stock:</strong></td>';
                    html += '            <td class="text-left pl-3" colspan="3">'+stock+'</td>';
                    html += '        </tr>';
                    html += '    </table>';
                    html += '            </tbody>';
                    html += '    </table>';
                    html += '</div>';


                    $("#detalleInsumo").html('');
                    $("#detalleInsumo").append(html);

                }else{
                    $("#detalleInsumo").html('');
                    $("#detalleInsumo").append('<div class="alert-danger text-center bold p-3"> No hay datos que mostrar </div>');
                } */
            }
        });

        

    })

</script>