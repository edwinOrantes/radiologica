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
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Salas </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Lista de procedimientos</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">

                        <div class="col-md-6">
                            <h6>Procedimientos activos</h6>
                        </div>
                        
                        <div class="col-md-6 text-right"></div>

                    </div>
                </div>

                <div class="ms-panel-body">
                    <div class="row">
                        <?php
                        if(sizeof($procedimientos) > 0){
                            foreach ($procedimientos as $row) {
                                echo '<div class="col-xl-4 col-md-4 col-sm-6">';
                                echo '    <a href="'.base_url().'Quirofanos/detalle_procedimientos/'.$row->idProcedimiento.'/">';
                                echo '        <div class="ms-card card-linkedin ms-widget ms-infographics-widget carrerita">';
                                echo '            <div class="ms-card-body media">';
                                echo '                <div class="media-body">';
                                echo '                <h6>'.$row->codigoHoja.' -'.$row->nombrePaciente.'</h6>';
                                echo '                <p class="ms-card-change"></p>';
                                echo '                <p class="fs-12 text-warning"><i class="fa fa-calendar"></i> '.$row->fechaHoja.' </p>';
                                echo '                </div>';
                                echo '            </div>';
                                echo '            <i class="fas fa-address-book ms-icon-mr text-white fa-sm"></i>';
                                echo '        </div>';
                                echo '    </a>';
                                echo '</div>';
                            }

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

<!-- Modal para corte diario -->
    <div class="modal fade" id="mdlDatosCorte" tabindex="-1" role="dialog" aria-labelledby="modal-5">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content pb-5">

				<div class="modal-header bg-primary">
					<h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button>
				</div>
                <form action="<?php echo base_url()?>Reportes/generar_cobros/" method="post">
                    <div class="modal-body text-center">
                        <p class="h5">¿Los datos del corte para la fecha <span id="lblFechaCorte"></span> son los correcto?</p>
                        <div>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-center"><strong>Inicio</strong></td>
                                    <td><span id="tblInicioCorte"></span></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><strong>Fin</strong></td>
                                    <td><span id="tblFinCorte"></span></td>
                                </tr>
                            </table>
                        </div>
    
                        <div>
                            <input type="text" class="form-control" name="codigoVerificacion" placeholder="Ingrese el código de verificación" required>
                        </div>
                    </div>

					<div class="text-center">
                        <input type="hidden" id="inicioCorte" name="inicioCorte">								
                        <input type="hidden" id="finCorte" name="finCorte">						
                        <input type="hidden" id="fechaCorte" name="fechaCorte">						
						<button type="submit" class="btn btn-primary shadow-none"><i class="fa fa-trash"></i> Generar</button>
					</div>
				</form>

			</div>
		</div>
	</div>
<!-- Fin Modal corte diario -->
