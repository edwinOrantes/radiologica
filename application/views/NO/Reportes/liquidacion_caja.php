<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.error('<?php echo $this->session->flashdata("error")?>', 'Advertencia!');
    });
  </script>
<?php endif; ?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-xl-12 col-md-12">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Reportes</a> </li>
                    <li class="breadcrumb-item active"><a href="#">Detalle de servicios externos</a></li>
                </ol>
            </nav>
			<div class="ms-panel">
				<div class="ms-panel-header">
					<div class="row">
							<div class="col-md-6 "><h6>Ingrese el rango de numeros que desea</h6></div>
							<div class="col-md-6 text-right">
								<a href="<?php echo base_url(); ?>Reportes/lista_liquidaciones/" class="btn btn-outline-success btn-sm"> <i class="fa fa-list"></i> Lista de cortes</a>
							</div>
					</div>
					
				</div>

				
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-12 tade">
							<form class="needs-validation" id="reporteCirugiasFechas" method="post" action="<?php echo base_url()?>Reportes/procesar_liquidacion" target="_blank" novalidate>
								<div class="form-row align-items-center">

									<div class="col-md-4">
										<select class="form-control" id="pivoteLiquidacion" name="pivoteLiquidacion" required="">
											<option value="">.:: Seleccionar ::.</option>
											<option value="0">Todo</option>
											<option value="1">Hospital Orellana</option>
											<option value="2">Unidad Hemodialisis</option>
											<!-- <?php
												foreach ($cajas as $row) {
													if($row->estadoCaja == 1){
														echo '<option value="'.$row->idCaja.'">'.$row->nombreCaja.'</option>';
													}
												}
											?> -->
										</select>

										<div class="invalid-tooltip">
											Seleccione una opcion.
										</div>
									</div>

									<div class="col-md-2">
										<input type="number" class="form-control numeros" max="" id="hojaInicio" name="hojaInicio" placeholder="Número correlativo de inicio" required>
										<div class="invalid-tooltip">
											Debes agregar el numero inicial.
										</div>
									</div>

                                    <div class="col-md-2">
										<input type="number" value="" max="" class="form-control numeros" id="hojaFin" name="hojaFin" placeholder="Número correlativo de fin" required>
										<div class="invalid-tooltip">
											Debes agregar el numero final.
										</div>
									</div>

                                    <div class="col-md-2">
										<input type="date" value=""  class="form-control" id="fechaLiquidacion" name="fechaLiquidacion" required>
										<div class="invalid-tooltip">
											Debes agregar la fecha.
										</div>
									</div>

									<div class="col-md-2">
									    <button type="submit" class="btn btn-success mb-2"><i class="fa fa-file-pdf"></i> Generar</button>
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

<input type="hidden" value="<?php echo $inicio; ?>" id="inicioTodo">
<input type="hidden" value="<?php echo $numeroLimite; ?>" id="finTodo">

<script>
	$(document).on("change", "#fechaLiquidacion", function(e) {
		e.preventDefault();
		var datos = {
			fecha : $(this).val()
		}

		/* $.ajax({
			url: "validar_fecha_liquidacion",
			type: "POST",
			beforeSend: function () { },
			data: datos,
			success:function(respuesta){
				var registro = eval(respuesta);
				if (Object.keys(registro).length > 0){
					if(registro.estado == 1){
						toastr.remove();
						toastr.options = {
							"positionClass": "toast-top-left",
							"showDuration": "5000",
							"hideDuration": "5000",
							"timeOut": "5000",
							"extendedTimeOut": "5000",
							"showEasing": "swing",
							"hideEasing": "linear",
							"showMethod": "fadeIn",
							"hideMethod": "fadeOut"
							},
						toastr.error('Ya existe una liquidacion con esta fecha', 'Aviso!');
						$("#fechaLiquidacion").val("");
						// location.reload();
					}
				}
			},
			error:function(){
				alert("Hay un error");
			}
		}); */
	});

	$(document).on("change", "#pivoteLiquidacion", function() {
		var pivote = $(this).val();
		if(pivote != ""){
			$('#hojaInicio').prop('readonly', false);
			$('#hojaFin').prop('readonly', false);
		}else{
			$("#hojaInicio").val("");
			$("#hojaFin").val("");
			$('#hojaInicio').prop('readonly', true);
			$('#hojaFin').prop('readonly', true);
		}
		if(pivote == 0) {
			$("#hojaInicio").val($("#inicioTodo").val());
			$("#hojaFin").val($("#finTodo").val());
		}else{
			
			var datos = {
				caja: pivote
			}


			$.ajax({
				url: "obtener_min_max",
				type: "POST",
				beforeSend: function () { },
				data: datos,
				success:function(respuesta){
					var registro = eval(respuesta);
					if (registro.length > 0){

						for (let i = 0; i < registro.length; i++) {
							$("#hojaInicio").val(registro[i]["minimo"]);
							$("#hojaFin").val(registro[i]["maximo"]);
						}
					}else{
						toastr.remove();
						toastr.options = {
							"positionClass": "toast-top-left",
							"showDuration": "300",
							"hideDuration": "1000",
							"timeOut": "1000",
							"extendedTimeOut": "50",
							"showEasing": "swing",
							"hideEasing": "linear",
							"showMethod": "fadeIn",
							"hideMethod": "fadeOut"
							},
						toastr.error('Error al efectuar el proceso', 'Aviso!');
					}
				},
				error:function(){
					toastr.remove();
					toastr.options = {
						"positionClass": "toast-top-left",
						"showDuration": "300",
						"hideDuration": "1000",
						"timeOut": "1000",
						"extendedTimeOut": "50",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					},
					toastr.error('Error al efectuar el proceso', 'Aviso!');
				}
			});





		}




		
	});

	$(document).ready(function() {
		// Bloquear el input
		$('#hojaInicio').prop('readonly', true);
		$('#hojaFin').prop('readonly', true);
	});


</script>