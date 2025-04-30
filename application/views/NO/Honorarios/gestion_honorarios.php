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
                            <li class="breadcrumb-item"><a href="#">Honorarios pendientes</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-outline-success" id="totalHonorario">$0.00</button>
                    <?php
                        if($this->session->userdata("acceso_h") == 5 || $this->session->userdata("acceso_h") == 1 ){
                            echo '<a href="'.base_url().'Reportes/total_x_externo" class="btn btn-outline-primary"><i class="fa fa-file"></i> Resumen honorarios </a>';
                        }
                    ?>
                    
                    <a href="#facturadosFecha" class="btn btn-outline-primary" data-toggle="modal"><i class="fa fa-file"></i> Facturados</a>
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
							<form class="needs-validation" method="post" action="<?php echo base_url(); ?>Honorarios/honorarios_pivote" target="blank" novalidate>
								<div class="row">
									<div class="col-md-12" style="margin: 0 auto;">
                                        <div class="form-row">
                                            
                                            <div class="col-md-7">
												<label for=""><strong></strong></label>
												<div class="input-group">
													<select class="form-control controlInteligente" id="idMedico" name="idMedico" required>
														<option value="">.:: Seleccionar un médico ::.</option>
														<?php 
															foreach ($externos as $externo) {
														?>
														    <option value="<?php echo $externo->idExterno; ?>"><?php echo str_replace("(Honorarios)","", $externo->nombreExterno); ?></option>
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


<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="facturadosFecha" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white">Seleccione los datos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <div class="col-md-12" id="">
                                    <form class="needs-validation" id="generarFacturados" method="post" action="<?php echo base_url()?>Honorarios/honorarios_facturados" target="_blank" novalidate>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <label class="" for=""><strong>Médico</strong></label>
                                                <div class="input-group mb-2">

                                                    <select class="form-control controlInteligente2" id="idExterno" name="idExterno" required>
                                                        <option value="">.:: Seleccionar ::.</option>
                                                        <?php
                                                            foreach ($externos as $externo) {
                                                        ?>
                                                            <option value="<?php echo $externo->idExterno; ?>"><?php echo str_replace("(Honorarios)","", $externo->nombreExterno); ?></option>
                                                        <?php } ?>
                                                    </select>

                                                    <div class="invalid-tooltip">
                                                        Debes seleccionar el servicio externo.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <label class="" for=""><strong>Fecha Inicio</strong></label>
                                                <input type="date" class="form-control mb-2" id="fechaInicio" name="fechaInicio" placeholder="Seleccione la fecha de inicio" required>
                                                <div class="invalid-tooltip">
                                                    Debes seleccionar la fecha de inicio.
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="" for=""><strong>Fecha Fin</strong></label>
                                                <input type="date" class="form-control mb-2" id="fechaFin" name="fechaFin" placeholder="Seleccione la fecha final" required>
                                                <div class="invalid-tooltip">
                                                    Debes seleccionar la fecha final.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row text-center">
                                            <div class="col-md-12 mt-4 center">
                                                <button type="submit" class="btn btn-success mb-2" id="generar"><i class="fa fa-file-pdf"></i> Generar</button>
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
    </div>
<!-- Fin Modal para agregar datos del Medicamento-->

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
            url: "buscar_honorarios",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                var html = '';
                    if (registro.length > 0){
                        html += '<table id="tabla-honorarios" class="table table-striped thead-primary w-100">';
                        html += '    <thead>';
                        html += '        <tr>';
                        html += '            <th class="text-center" scope="col">Facturar</th>';
                        html += '            <th class="text-center" scope="col">Hoja de cobro</th>';
                        html += '            <th class="text-center" scope="col">Paciente</th>';
                        html += '            <th class="text-center" scope="col">Monto</th>';
                        html += '            <th class="text-center" scope="col">Fecha</th>';
                        html += '            <th class="text-center" scope="col">Entregado</th>';
                        html += '        </tr>';
                        html += '    </thead>';
                        html += '    <tbody>';
                        for (let i = 0; i < registro.length; i++) {
                            html += '        <tr>';
                            html += '            <td class="text-center" scope="row"><label class="ms-switch">';
                            if(registro[i]["facturar"] == 0){
                                html += '                    <input type="checkbox" class="facturarHonorarios" value="noFacturar" name="facturar">';
                            }else{
                                html += '                    <input type="checkbox" class="facturarHonorarios" value="facturar" name="facturar" checked="">';
                            }
                            html += '                    <span class="ms-switch-slider round"></span>';
                            html += '                    </label>';
                            html += '            </td>';
                            html += '            <td class="text-center" scope="row">'+registro[i]["codigoHoja"]+' <input type="hidden" value="'+registro[i]["idHonorario"]+'" class="idHonorario"></td>';
                            html += '            <td class="text-center" scope="row">'+registro[i]["nombrePaciente"]+' <input type="hidden" value="'+registro[i]["idHonorario"]+'" class=""></td>';
                            html += '            <td class="text-center">$'+registro[i]["precioExterno"]+'</td>';
                            html += '            <td class="text-center">'+registro[i]["fechaExterno"].substr(0,10)+'</td>';
                            if(registro[i]["estadoExterno"] == 0){
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
                                $(api.column(3).footer()).html(
                                    '$'+(api.column(3, {page:'current'}).data().sum()).toFixed(2)
                                )
                                // console.log(api.column(3, {page:'current'}).data().sum());
                                //$("#totalHonorario").text("$"+(api.column(3, {page:'current'}).data().sum()).toFixed(2));
                            }
                        });
                        
                        $("#generarExcel").show();
                        $("#saldarTodos").show();
                        $("#pivoteHonorario").show();
                        $("#totalHonorario").show();
                        $("#totalHonorario").text("$"+(tabla.column(3).data().sum()).toFixed(2));
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
            url: "saldar_honorario",
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
                    url: "adeudar_honorario",
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

    $(document).on("click", ".facturarHonorarios", function(event) {
       var valor = $(this).val();
        //var valor = $('input:checkbox[name=facturar]:checked').val();
        var facturado = 0;
       if(valor == "noFacturar"){
            $(this).val("facturar")
            facturado = 1;
            //alert(valor);
        }else{
            $(this).val("noFacturar")
            facturado = 0;
        }

        var datos = {
            idHonorario : $(this).closest('tr').find(".idHonorario").val(),
            facturar : facturado
        }
        
        // Ejecutando consulta
        $.ajax({
            url: "facturar_honorario",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if(registro.length > 0){}
            }
        });

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
            url: "saldar_all_honorario",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if(registro.length > 0){}
            }
        });
      
    });


</script>
