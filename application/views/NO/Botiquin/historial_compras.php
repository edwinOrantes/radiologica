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
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-medkit"></i> Botíquin </a> </li>
                    <li class="breadcrumb-item"><a href="#">Historial de compras</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de compras</h6></div>
                        <div class="col-md-6 text-right"><a href="<?php echo base_url() ?>Botiquin/compras_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a></div>
                    </div>
                </div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">

                        <?php
                            if (sizeof($facturas) > 0) {
 
                        ?>

                            <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Proveedor</th>
                                        <th class="text-center" scope="col">Plazo</th>
                                        <!-- <th class="text-center" scope="col">Total</th>
                                        <th class="text-center" scope="col">Descripción</th> -->
                                        <!-- <th class="text-center" scope="col">Estado</th> -->
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $plazo = "";
                                        foreach ($facturas as $factura) {
                                            if ($factura->plazoFactura == "0") {
                                                $plazo = "Contado";
                                            }else{
                                                $plazo = "Crédito ".$factura->plazoFactura." dias";
                                            }                           
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $factura->fechaFactura; ?></td>
                                        <td class="text-center"><?php echo $factura->empresaProveedor; ?></td>
                                        <td class="text-center"><?php echo $plazo; ?></td>
                                        <!-- <td class="text-center">$
                                            <?php
                                                /* Aqui estoy trabajando */
                                                $totalFactura = 0;
                                                $medicamentos = $this->Botiquin_Model->medicamentosFactura($factura->idFactura);
                                                foreach ($medicamentos as $medicamento) {
                                                    // $totalFactura += number_format(($medicamento->cantidad * $medicamento->precio), 2) + number_format((($medicamento->cantidad * $medicamento->precio) * 0.13), 2);
                                                    $totalFactura += ($medicamento->cantidad * $medicamento->precio )+ (($medicamento->cantidad * $medicamento->precio) * 0.13);
                                                }
                                                echo number_format($totalFactura, 2);
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo $factura->descripcionFactura; ?></td> -->
                                        <!-- <td class="text-center">
                                                <?php
                                                    /* if ($factura->estadoFactura == 1) {
                                                        echo '<span class="badge badge-outline-danger">Pendiente</span>';
                                                    }else{
                                                        echo '<span class="badge badge-outline-primary">Saldada</span>';
                                                    } */
                                                ?>
                                        </td> -->
                                        <td class="text-center">
                                            <?php
                                            if ($factura->estadoFactura == 1) {
                                                echo '<a href="'.base_url().'Botiquin/detalle_factura_compra/'.$factura->idFactura.'/" class="text-primary" title="Ver detalle de factura pendiente"><i class="fa fa-file-signature"></i></a>';
                                            }else{
                                                echo '<a href="'.base_url().'Botiquin/detalle_factura_compra/'.$factura->idFactura.'/" class="text-primary" title="Ver detalle de factura saldada"><i class="fa fa-eye"></i></a>';
                                            }
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


