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
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Honorarios </a> </li>
                            <li class="breadcrumb-item"><a href="#">Entrega de honorarios</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-outline-success" id="totalHonorario">$0.00</button>
                </div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
						<div class="col-md-12">
                            <!-- <?php
                                // if($this->session->userdata("acceso_h") == 10){
                                //     echo '<form class="needs-validation" method="post" action="'.base_url().'Honorarios/honorarios_pdf" target="blank" novalidate>';
                                // }else{
                                //     echo '<form class="needs-validation" method="post" action="'.base_url().'Honorarios/honorarios_excel" novalidate>';
                                // }
                            ?> -->
							<form class="needs-validation" method="post" action="<?php echo base_url(); ?>Honorarios/honorarios_paquete_pivote" target="blank" novalidate>
								<div class="row">
									<div class="col-md-12" style="margin: 0 auto;">
                                        <div class="form-row">
                                            
                                            <div class="col-md-7">
												<label for=""><strong></strong></label>
												<div class="input-group">
													<select class="form-control controlInteligente" id="idMedico" name="idMedico" required>
														<option value="">.:: Seleccionar un médico ::.</option>
														<?php 
															foreach ($medicos as $row) {
														?>
														    <option value="<?php echo $row->idMedico; ?>"><?php echo str_replace("(Honorarios)","", $row->nombreMedico); ?></option>
														<?php } ?>
													</select>
													<div class="invalid-tooltip">
														Seleccione un médico.
													</div>  
												</div>
											</div>
                                            
                                            <div class="text-center col-md-2">
                                                <select name="pivoteHonorario" id="pivoteHonorario" class="mt-4 form-control">
                                                    <option value="1">PDF</option>
                                                    <option value="2">Excel</option>
                                                </select>
                                            </div>

                                            <div class="text-center col-md-3">
                                                <button class="btn btn-success btn-sm mt-4" type="submit" id="generarExcel"> <i class="fa fa-file"></i> Generar</button>
                                                <button class="btn btn-outline-success btn-sm mt-4" type="button" id="saldarTodos"> <i class="fa fa-check"></i> Saldar todos</button>
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
                            <div id="honorariosMedico"></div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        $("#generarExcel").hide();
        $("#saldarTodos").hide();
        $("#totalHonorario").hide();
        $("#pivoteHonorario").hide();
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
        $('.controlInteligente2').select2({
            theme: "bootstrap4",
            dropdownParent: $("#facturadosFecha")
        });
    });

    $(document).on("change", "#idMedico", function(event) {
        event.preventDefault();
        var datos = {
                medico : $(this).val()
            }

        $.ajax({
            url: "buscar_honorarios_paquetes",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                var html = '';
                var dejo = '';
                    if (registro.length > 0){
            
                        html += '<table id="tabla-honorarios" class="table table-striped thead-primary w-100">';
                        html += '    <thead>';
                        html += '        <tr>';
                        html += '            <th class="text-center" scope="col">Hoja de cobro</th>';
                        html += '            <th class="text-center" scope="col">Paciente</th>';
                        html += '            <th class="text-center" scope="col">Monto</th>';
                        html += '            <th class="text-center" scope="col">Detalle</th>';
                        html += '            <th class="text-center" scope="col">Opción</th>';
                        html += '        </tr>';
                        html += '    </thead>';
                        html += '    <tbody>';
                        for (let i = 0; i < registro.length; i++) {
                            if(registro[i]["quienDeja"] != 0){
                                dejo = "Le deja  " + registro[i]["quienDeja"];
                            }else{
                                dejo = '';
                            }
                            html += '        <tr>';
                            html += '            <td class="text-center" scope="row">'+registro[i]["codigoHoja"]+' <input type="hidden" value="'+registro[i]["idHonorarioPaquete"]+'" class="idHonorario"></td>';
                            html += '            <td class="text-center" scope="row">'+registro[i]["nombrePaciente"]+' <input type="hidden" value="'+registro[i]["idHonorarioPaquete"]+'" class=""></td>';
                            html += '            <td class="text-center">$'+registro[i]["totalHonorarioPaquete"]+'</td>';
                            html += '            <td class="text-center">'+dejo+'</td>';
                            if(registro[i]["estadoHonorarioPaquete"] == 0){
                                html += '            <td class="text-center reemplazar"><i class="fa fa-check text-danger saldarHonorario" title="Saldar honorario"></i> </td>';
                            }else{
                                html += '            <td class="text-center reemplazar"><i class="fa fa-check text-success adeudarHonorario" title="Honorario saldado" data-toggle="tooltip" data-placement="top"></i> <input type="hidden" value="'+registro[i]["estadoExterno"]+'"/>  </td>';
                            }
                            // html += '            <td class="text-center">'+registro[i]["estadoExterno"]+'</td>';

                            html += '        </tr>';
                                
                            }
                        html += '    </tbody>';
                        html += '<tfoot>';
                        html += '    <tr>';
                        html += '        <th></th>';
                        html += '        <th></th>';
                        html += '        <th class="text-center"></th>';
                        html += '        <th></th>';
                        html += '        <th></th>';
                        html += '    </tr>';
                        html += '</tfoot>';
                        html += '</table>';
                        $("#honorariosMedico").html('');
                        $("#honorariosMedico").append(html);

                        var tabla = $('#tabla-honorarios').DataTable({
                            "order": [[ 3, "asc"]],
                            "drawCallback":function(){
                                var api = this.api();
                                $(api.column(2).footer()).html(
                                    '$'+(api.column(2, {page:'current'}).data().sum()).toFixed(2)
                                )
                                // console.log(api.column(3, {page:'current'}).data().sum());
                                //$("#totalHonorario").text("$"+(api.column(3, {page:'current'}).data().sum()).toFixed(2));
                            }
                        });
                        
                        $("#generarExcel").show();
                        $("#saldarTodos").show();
                        $("#pivoteHonorario").show();
                        $("#totalHonorario").show();
                        $("#totalHonorario").text("$"+(tabla.column(2).data().sum()).toFixed(2));
                    }else{
                        $("#totalHonorario").hide();
                        $("#generarExcel").hide();
                        $("#saldarTodos").hide();
                        $("#pivoteHonorario").hide();
                        $("#honorariosMedico").html("");
                        $("#honorariosMedico").append('<div class="alert-danger text-center bold p-3"> No hay datos que mostrar </div>');
                    }
            }
        });

        

    })

    $(document).on("click", ".saldarHonorario", function(event) {
        event.preventDefault();
        var datos = {
            idHonorario : $(this).closest('tr').find(".idHonorario").val()
        }

        $.ajax({
            url: "saldar_honorario_paquete",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if(registro.length > 0){}
            }
        });

        $(this).closest('tr').find(".reemplazar").find(".saldarHonorario").hide();
        $(this).closest('tr').find(".reemplazar").html('<i class="fa fa-check text-success adeudarHonorario" title="Honorario saldado"></i>')

    });

    $(document).on("click", ".adeudarHonorario", function(event) {
        //event.preventDefault();
        var resp = confirm("¿El honorario aun no ha sido entregado?");
        if(resp){

            // Obteniendo honorario
                var datos = {
                    idHonorario : $(this).closest('tr').find(".idHonorario").val()
                }
            // Fin de obtener honorario
            
            // Ejecutando consulta
                $.ajax({
                    url: "adeudar_honorario_paquete",
                    type: "POST",
                    data: datos,
                    success:function(respuesta){
                        var registro = eval(respuesta);
                        if(registro.length > 0){}
                    }
                });
                $(this).closest('tr').find(".reemplazar").find(".adeudarHonorario").hide();
               $(this).closest('tr').find(".reemplazar").html('<i class="fa fa-check text-danger saldarHonorario" title="Honorario pendiente"></i>');
            // Fin de ejecutar cosulta

        }else{
            console.log("Solicitud rechazada");
        }
        /* var datos = {
            idHonorario : $(this).closest('tr').find(".idHonorario").val()
        }

        $.ajax({
            url: "saldar_honorario",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if(registro.length > 0){}
            }
        });

        $(this).closest('tr').find(".reemplazar").find(".saldarHonorario").hide();
        $(this).closest('tr').find(".reemplazar").html('<i class="fa fa-check text-success adeudarHonorario" title="Honorario saldado"></i>') */

    });

    // Metodo para saldar todos los honorarios a la vez
    $(document).on("click", "#saldarTodos", function(event) {

        $('.saldarHonorario').each(function() {
            $(this).hide();
            $(".reemplazar").html('<i class="fa fa-check text-success adeudarHonorario" title="Honorario saldado"></i>')
        });

        var datos = {
            medico : $("#idMedico").val()
        }
        $.ajax({
            url: "saldar_all_honorarios_paquetes",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if(registro.length > 0){}
            }
        });
      
    });


</script>
