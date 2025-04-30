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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Medicamentos</a> </li>
                    <li class="breadcrumb-item"><a href="#">Lista</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Listado de medicamentos</h6></div>
                        <div class="col-md-6 text-right"></div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
						<div class="table-responsive">
							<table id="tabla-pacientes" class="table table-striped thead-primary w-100">
								<thead>
									<tr>
										<th scope="col" class="text-center">#</th>
										<th scope="col" class="text-center">Nombre</th>
										<th scope="col" class="text-center">Precio compra</th>
										<th scope="col" class="text-center">Precio venta</th>
									</tr>
								</thead>
								<tbody>
							<?php
							if (sizeof($medicamentos) > 0) {
								$index= 0;
								foreach ($medicamentos as $medicamento) {
										$index++;
							?>
							
										<tr>
											<td class="text-center"><?php echo $index ?></td>
											<td class="text-center" style="text-transform: uppercase;"><?php echo $medicamento->nombreMedicamento; ?></td>
											<td class="text-center">$ <?php echo $medicamento->precioCMedicamento ?></td>
											<td class="text-center">$ <?php echo $medicamento->precioVMedicamento ?></td>
										</tr>
										
									

							<?php
									}
								}else
								{
									echo '<div class="alert alert-danger">
											<h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
										</div>';
								}
							?>   
								</tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>
