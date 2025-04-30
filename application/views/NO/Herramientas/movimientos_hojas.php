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
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrow has-gap">
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Herramientas </a> </li>
                            <li class="breadcrumb-item"><a href="#">Movimientos Hoja</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right"></div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
						<div class="col-md-12">
							<form class="needs-validation" method="post" action="" novalidate>
								<div class="row">
									<div class="col-md-12" style="margin: 0 auto;">
                                        <div class="form-row">
                                            
                                            <div class="col-md-5">
												<label for=""><strong></strong></label>
												<div class="input-group">
													<input type="text" name="codigoHoja" id="codigoHoja" class="form-control border border-primary" required>
													<div class="invalid-tooltip">
														Ingrese el codigo de hoja.
													</div>  
												</div>
											</div>

                                            <div class="text-center col-md-2">
                                                <button class="btn btn-success btn-sm mt-4" type="number" id="consultarHoja"> <i class="fa fa-search"></i> Consultar </button>
                                            </div>

										</div>
									</div>
								</div>                              
							</form>
						</div>
					</div>
				</div>
				<div class="ms-panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="detalleCuenta">
                                
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>


    $(document).on("click", "#consultarHoja", function(event) {
        event.preventDefault();
        var datos = {
            codigoCuenta : $("#codigoHoja").val()
            }
        $.ajax({
            url: "buscarCuenta",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                var html = '';
                    if (registro.length > 0){
                        let $paciente = "";
                        let fecha = "";
                        let codigo = "";
                        for (let i = 0; i < registro.length; i++){
                            paciente = registro[i]["nombrePaciente"];
                            fecha = registro[i]["fechaHoja"];
                            codigo = registro[i]["codigoHoja"];
                        }
                        html += '    <table class="table table-bordered">';
                        html += '        <tr>';
                        html += '            <td class="text-center"><strong>Código Hoja:</strong></td>';
                        html += '            <td class="text-left">'+codigo+'</td>';
                        html += '            <td class="text-center"><strong>Fecha hoja:</strong></td>';
                        html += '            <td class="text-left">'+fecha+'</td>';
                        html += '        </tr>';
                        html += '        <tr>';
                        html += '            <td class="text-center"><strong>Paciente:</strong></td>';
                        html += '            <td class="text-left pl-3" colspan="3">'+paciente+'</td>';
                        html += '        </tr>';
                        html += '    </table>';

                        html += '    <table class="table table-bordered thead-primary" id="movimientosHoja">';
                        html += '        <thead class="thead-inverse">';
                        html += '            <tr>';
                        html += '                <th class="text-center">Empleado</th>';
                        html += '                <th class="text-center">Usuario</th>';
                        html += '                <th class="text-center">Proceso</th>';
                        html += '                <th class="text-center">Fecha</th>';
                        html += '                <th class="text-center">Hora</th>';
                        html += '            </tr>';
                        html += '            </thead>';
                        html += '            <tbody>';

                        for (let i = 0; i < registro.length; i++){
                        html += '           <tr>';
                        html += '               <td scope="row">'+registro[i]["empleado"]+'</td>';
                        html += '               <td scope="row">'+registro[i]["nombreUsuario"]+'</td>';
                        html += '               <td scope="row">'+registro[i]["detalleBitacora"]+'</td>';
                        html += '               <td scope="row">'+registro[i]["fechaMovimiento"].substr(0,10)+'</td>';
                        html += '               <td scope="row">'+registro[i]["fechaMovimiento"].substr(11,20)+'</td>';
                        html += '           </tr>';
                        }
                        html += '            </tbody>';
                        html += '    </table>';
                        html += '</div>';


                        $("#detalleCuenta").html('');
                        $("#detalleCuenta").append(html);
                        $("#movimientosHoja").DataTable({
                            "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                            "order": [[ 3, "asc"]]
                        });

                    }else{
                        $("#detalleCuenta").html('');
                        $("#detalleCuenta").append('<div class="alert-danger text-center bold p-3"> No hay datos que mostrar </div>');
                    }
            }
        });

        

    })

</script>