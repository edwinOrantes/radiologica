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
                    <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-file-word"></i> Laboratorio </a> </li>
                        <li class="breadcrumb-item active"><a href="#">Donantes</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        
                        <div class="row">
                        <div class="col-md-6">
                            <h6> Información de la hoja de descargos</h6>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>InsumosLab/lista_donantes/"> Lista de donantes <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="needs-validation" method="post" action="<?php echo base_url()?>InsumosLab/guardar_donante" novalidate>
                                    <input type="hidden" class="form-control" value=""  name="codigoDonante"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <div class="form-row">

                                                <div class="col-md-6 text-center">
                                                    
                                                    <label for=""><strong>Nombre donante</strong></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" value="" name="nombreDonante" id="nombreDonante" required/>
                                                        <div class="invalid-tooltip">
                                                            Ingrese el nombre dle donante.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 text-center">
                                                <button class="btn btn-primary mt-4 d-inline w-20" type="submit"> Siguiente <i class="fa fa-arrow-right"></i></button>
                                                </div>
                                                
                                            </div>

                                        </div>
                                        
                                    </div>    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End body Content Wrapper -->



<!-- Modal validar paciente-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="hojaActiva" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Este paciente tiene una hoja de cobro abierta, desea ver el detalle?</p>
                    </div>

                    <div class="text-center" id="controles">
                        
                    </div>

            </div>
        </div>
    </div>
<!-- Fin modal validar paciente-->

