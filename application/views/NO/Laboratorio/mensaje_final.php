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
                    <li class="breadcrumb-item"><a href="#">Mensaje final</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
					<div class="row">
                        <div class="col-md-6"><h6>Texto a compartir</h6></div>
                        <div class="col-md-6 text-right">
                            <!-- <button class="btn btn-primary mt-2" type="button" href="#agregarMedicamento" data-toggle="modal"><i class="fa fa-plus"></i> Medicamentos</button> -->
                        </div>
                    </div>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="" novalidate>
								<div class="form-row">

									<div class="col-md-12">
										<label for=""><strong>Teléfono</strong></label>
										<div class="input-group">
											<input class="form-control" value="<?php echo str_replace('-', '', $paciente->telefonoPaciente); ?>" type="text" id="telefonoPaciente" name="telefonoPaciente">
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>

									<div class="col-md-12">
										<label for=""><strong>Descripción</strong></label>
										<div class="input-group">
                                        <?php
                                                $pivote = urlencode(base64_encode(serialize($consulta->consulta)));
                                                $mensaje = "Estimado/a: ".$paciente->nombrePaciente.": \n".
                                                "Nos complace informarle que sus resultados de laboratorio clínico ya están disponibles. Puede visualizarlos de manera sencilla a través del siguiente enlace:\n".
                                                " https://laboratorio.hospitalorellana.com.sv/Resultados/detalle_examenes/".$pivote."  \n".
                                                "Si tiene alguna pregunta o necesita más información, no dude en ponerse en contacto.\n".
                                                "¡Gracias por confiar en nosotros!\n".
                                                "Atentamente:\n".
                                                "Laboratorio Clínico Hospital Orellana\n".
                                                "Tel: 2606-6673, 7854-4300";
                                        ?>
											<textarea class="form-control disableSelect" id="descripcionMensaje" name="descripcionMensaje" rows="10" placeholder="Descripción del proceso" required><?php echo $mensaje; ?></textarea>
											<div class="invalid-tooltip">
												Seleccione un tipo de proceso.
											</div>
										</div>
									</div>
								</div>

								<div class="text-center" id="">
									<a class="text-white btn btn-success mt-4 d-inline w-20" id="btnEnviarMensaje" type="submit"> Enviar a WhatsApp </a> &nbsp;
                                    <?php
                                        echo '<a href="'.base_url().'Laboratorio/detalle_consulta/'.$consulta->local.'/" class="text-white btn btn-light mt-4 d-inline w-20">Volver </a>'
                                    ?>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).on("click", "#btnEnviarMensaje", function() {
        telefono = "503"+$("#telefonoPaciente").val();
        descripcion = $("#descripcionMensaje").val();

        url = "https://wa.me/"+ encodeURIComponent(telefono)+"?text="+ encodeURIComponent(descripcion);
        // Abrir la nueva URL en una pestaña distinta
        window.open(url, '_blank');
    });
</script>