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
    $saldado = 0;
    $pendiente = 0;
    $total = 0;
    foreach ($cuentas as $cuenta) {
        if($cuenta->estadoCuentaPagar == 1){
            $pendiente += $cuenta->totalCuentaPagar;;
        }else{
            $saldado += $cuenta->totalCuentaPagar;;
        }
    }
    $total = $pendiente + $saldado;

?>
<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Cuentas por pagar </a> </li>
                    <li class="breadcrumb-item"><a href="#">Detalle de cuentas entre <?php echo $inicio; ?> y <?php echo $fin; ?></a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<div class="row">
						<div class="col-md-10">
                            <table class="tbl tbl-bordered">
                                <tr>
                                    <th> <button type="button" class="btn btn-outline-success btn-sm py-3 has-icon bailarin" readonly=""><i class="fa fa-money-check-alt fa-sm"></i> Saldado $<?php echo number_format($saldado, 2); ?></button> </th>
                                    <th> <button type="button" class="btn btn-outline-danger btn-sm py-3 has-icon bailarin" readonly=""><i class="fa fa-money-check-alt fa-sm"></i> Pendiente $<?php echo number_format($pendiente, 2); ?></button> </th>
                                    <th> <button type="button" class="btn btn-outline-primary btn-sm py-3 has-icon bailarin" readonly=""><i class="fa fa-money-check-alt fa-sm"></i>Total $<?php echo number_format($total, 2); ?></button> </th>
                                </tr>
                            </table>
                        </div>
						<div class="col-md-2 text-right"><a href="<?php echo base_url(); ?>CuentasPendientes/cuentas_por_fecha" class="btn btn-success btn-sm"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Volver </a></div>
					</div>
				</div>
				<div class="ms-panel-body">
                    <div class="table-responsive">
                        <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">#</th>
                                    <th class="text-center" scope="col">Fecha</th>
                                    <th class="text-center" scope="col">Proveedor</th>
                                    <th class="text-center" scope="col">Factura</th>
                                    <th class="text-center" scope="col">Monto</th>
                                    <th class="text-center" scope="col">Dias Transcurridos</th>
                                    <th class="text-center" scope="col">Estado</th>
                                    <th class="text-center" scope="col">Opción</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $index = 0;
                                    foreach ($cuentas as $cuenta) {
                                        $index++;
                                ?>
                                <tr>
                                    <td class="text-center" scope="row">
                                        <?php echo $index; ?>
                                    </td>
                                    <td class="text-center"><?php echo $cuenta->fechaCuentaPagar; ?></td>
                                    <td class="text-center"><?php echo $cuenta->empresaProveedor; ?></td>
                                    <td class="text-center"><?php echo $cuenta->facturaCuentaPagar; ?></td>
                                    <td class="text-center">$ <?php echo $cuenta->totalCuentaPagar; ?></td>
                                    <td class="text-center">
                                        <?php
                                            $fecha1= new DateTime($cuenta->fechaCuentaPagar);
                                            $fecha2= new DateTime(date("Y-m-d"));
                                            $diff = $fecha1->diff($fecha2);
                                            if($cuenta->estadoCuentaPagar == 1){
                                                if($diff->days <= 30){
                                                    echo $diff->days." dias";
                                                }else{
                                                    echo ($diff->days - 30)." dias de vencida";
                                                }
                                            }else{
                                                echo '<i class="fa fa-check-double fa-sm text-primary "></i>';
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php

                                        if($cuenta->estadoCuentaPagar != 1){
                                            echo '<span class="badge badge-outline-success">Saldada</span>';
                                        }else{
                                            if($diff->days <= 30){
                                                echo '<span class="badge badge-outline-danger">Pendiente</span>';
                                            }else{
                                                echo '<span class="badge badge-outline-danger">Vencida</span>';
                                            }
                                        } 
                                        
                                        ?>
                                    </td>

                                    <td class="text-center"><?php
                                        echo "<a class='actualizarCuenta' title='Actualizar datos del la cuenta' href='#actualizarCuenta' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                        echo "<a class='eliminarCuenta' title='Eliminar del médico' href='#eliminarCuenta' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                    ?>
                                    </td>

                                    
                                </tr>

                                <?php }	?>
                            </tbody>
                        </table>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
