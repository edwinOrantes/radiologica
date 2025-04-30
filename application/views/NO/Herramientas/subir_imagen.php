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
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Herramientas </a> </li>
                        <li class="breadcrumb-item"><a href="#">Personalizar navbar</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6"><h6>Cargar imagenes</h6></div>
                            <div class="col-md-6 text-right"></div>
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="POST" action="<?php echo base_url(); ?>Herramientas/mover_imagen" enctype="multipart/form-data">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="validatedCustomFile" name="imagenPrincipal" required>
                                        <label class="custom-file-label" for="validatedCustomFile">Imagen principal...</label>
                                    </div>

                                    <div class="text-center mt-3">
                                        <input type="hidden" value="1" name="pivoteImagen" />
                                        <button class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <form method="POST" action="<?php echo base_url(); ?>Herramientas/mover_imagen" enctype="multipart/form-data">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="validatedCustomFile" name="imagenFondo" required>
                                        <label class="custom-file-label" for="validatedCustomFile">Imagen de fondo...</label>
                                    </div>
                                    
                                    <div class="text-center mt-3">
                                        <input type="hidden" value="0" name="pivoteImagen" />
                                        <button class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>

                        <div class=" mt-5">
                            <div class="col-md-6">
                                <form method="POST" action="<?php echo base_url(); ?>Herramientas/mover_imagen" enctype="multipart/form-data">
                                    <input type="hidden" value="1" name="animacion">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="validatedCustomFile" name="imagenEvento" required>
                                        <label class="custom-file-label" for="validatedCustomFile">Animacion del dia...</label>
                                    </div>

                                    <div class="custom-file mt-3">
                                        <input type="date" class="form-control menosHeight" id="" name="fechaEvento" required>
                                    </div>

                                    <div class="text-center mt-3">
                                        <input type="hidden" value="1" name="pivoteImagen" />
                                        <button class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End Body Content Wrapper -->

<script>
    $(document).ready(function() {
        $(".controlInteligente").select2({
            theme: "bootstrap4",
            // dropdownParent: $("#inmunologia")
        });
    });
            
</script>




