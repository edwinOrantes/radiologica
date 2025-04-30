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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fas fa-flask"></i> Laboratorio </a> </li>
                    <li class="breadcrumb-item"><a href="#"> Mensaje </a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Mensaje a compartir con los pacientes</h6></div>
                        <div class="col-md-6 text-right"></div>
                    </div>
				</div>
				<div class="ms-panel-body">
                    <div class="row text-center">
                        <textarea name="textoCopiar" id="textoCopiar" class="form-control" cols="30" rows="10">Lorem ipsum dolor sit amet.</textarea>
                        <button id="btnCopiar" type="button" class="btn btn-primary mt-3">Copiar texto <span class="fa fa-copy text-white"></span> </button>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).on("click", "#btnCopiar", function() {
        $("#textoCopiar").select();
        // Ejecuta el comando de copiar
        document.execCommand('copy');

        // Deselecciona el texto
        $("#textoCopiar").blur();
        
        // Puedes mostrar un mensaje o realizar otras acciones después de copiar
        alert("Texto copiado al portapapeles");
    });
</script>
