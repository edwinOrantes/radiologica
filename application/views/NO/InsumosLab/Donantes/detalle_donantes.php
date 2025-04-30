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

			<!-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Laboratorio </a> </li>
                    <li class="breadcrumb-item"><a href="#">Historial del donante </a></li>
                </ol>
            </nav> -->

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>InsumosLab/donantes/"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">

                    <div class="alert-primary table-responsive bordeContenedor pt-3 pl-3 mb-3">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Código:</strong></td>
                                <td><?php echo $donante->codigoDonante; ?></td>
                                <td><strong>Último donativo:</strong></td>
                                <td><?php echo substr($donante->ultimaFecha, 0, 10); ?></td>
                            </tr>
                
                            <tr>
                                <td><strong>Paciente:</strong></td>
                                <td><?php echo $donante->nombreDonante; ?></td>
                                <td><strong>Edad:</strong></td>
                                <td><?php echo $donante->edadDonante." Años"; ?></td>
                            </tr>
                
                        </table>
                    </div>
                    <!-- Inicio -->
                        <div class="contenido">
                            <?php
                                if(sizeof($historial) > 0){
                            ?>
                            <table id="tablag" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Recibo</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $index = 0;
                                        foreach ($historial as $fila) {
                                            $index++;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $fila->codigoDonanteInsumo; ?></td>
                                        <td class="text-center"><?php echo $fila->nombreInsumoLab; ?></td>
                                        <td class="text-center">$<?php echo number_format($fila->precioInsumo, 2); ?></td>
                                        <td class="text-center"><?php echo $fila->cantidadInsumo; ?></td>
                                        <td class="text-center"><?php echo $fila->fecha; ?></td>
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
                    <input type="hidden" value="<?php echo $cuenta; ?>" id="cuentaActual" />
                </div>
            </div>
		</div>
	</div>
</div>