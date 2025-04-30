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
                        <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                            <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-user"></i> Médico </a> </li>
                            <li class="breadcrumb-item active"><a href="#"><strong><?php echo $honorario->nombreMedico; ?></strong></a></li>
                            <li class="breadcrumb-item"><a href="#"><strong>$<?php echo number_format($honorario->originalHonorarioPaquete, 2); ?></strong></a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right"></div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="needs-validation" method="post" action="<?php echo base_url()?>Honorarios/guardar_division" novalidate>
                                <div class="row g-3">
                                        <div class="form-group col-md-6">
                                            <input type="hidden" value="<?php echo $honorario->idHoja; ?>" id="idHoja" name="idHoja">
                                            <label for=""><strong>Médico</strong></label>
                                            <div class="input-group">
                                                <select class="form-control controlInteligente" id="idMedico" name="idMedico" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <?php 
                                                        foreach ($medicos as $medico) {
                                                            ?>
                                                    
                                                    <option value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
                                                    
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Seleccione un médico.
                                                </div>  
                                            </div>

                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for=""><strong>Cantidad</strong></label>
                                            <input type="text" class="form-control" min="0" max="<?php echo $honorario->totalHonorarioPaquete; ?>" id="cantidadHonorario" name="cantidadHonorario" placeholder="Monto del honorario" required>
                                            <div class="invalid-tooltip">
                                                Debes ingresar un monto valido.
                                            </div>
                                        </div>
                                        <div class="form-group text-center mt-4 col-md-3">
                                            <input type="hidden" value="<?php echo $pivote; ?>" class="form-control" id="idHonorario" name="idHonorario">
                                            <input type="hidden" value="<?php echo $honorario->totalHonorarioPaquete; ?>" class="form-control" id="montoOriginal" name="montoOriginal">
                                            <button type="submit" class="btn btn-primary has-icon btn-sm" ><i class="fa fa-save"></i> Guardar </button>
                                    </div>
                                </div>
                            </form> 
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="#dividirCuenta" data-toggle="modal" class="btn btn-outline-primary" title="Agregar división">Disponible: <input type="hidden" value="<?php echo $honorario->totalHonorarioPaquete; ?>" id="totalBase"> <strong>$<?php echo number_format($honorario->totalHonorarioPaquete, 2); ?></strong></a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
                        <?php
                            if(sizeof($divisiones) > 0){
                        ?>
                        <div class="table-responsive">
                            <table id="tabla-pacientes" class="table table-striped thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Hoja</th>
                                        <th class="text-center">Médico</th>
                                        <th class="text-center">Paciente</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 0;
                                        foreach ($divisiones as $row) {
                                            $index++;
                                    ?>

                                     <tr>
                                        <td class="text-center"><?php echo $index; ?></td>
                                        <td class="text-center"> <a href="<?php echo base_url().'Hoja/detalle_hoja/'.$row->idHoja.'/'; ?>" target="_blank" rel="noopener noreferrer"><?php echo $row->codigoHoja; ?></a> </td>
                                        <td class="text-center"><?php echo $row->nombreMedico; ?></td>
                                        <td class="text-center"><?php echo $row->nombrePaciente; ?></td>
                                        <td class="text-center">$<?php echo number_format($row->totalHonorarioPaquete, 2); ?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                
                                </tbody>
                            </table>
                        </div>
                        <?php
                            }else{
                                echo "<div class='alert-danger text-center p-3 bold col-md-12'>No hay datos que mostrar.</div";
                            }
                        ?>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        $('.controlInteligente').select2({
            theme: "bootstrap4",
        });
    });

    /* $(document).on("click", "#guardarHonorario", function(e){
        e.preventDefault();
        var totalBase = $("#totalBase").val();
        var cantidad = $("#cantidadHonorario").val();
        var nuevoBase = (totalBase - cantidad).toFixed(2);
        var datos = {
            idHoja : $("#idHoja").val(),
            idMedico : $("#idMedico").val(),
            cantidadHonorario : $("#cantidadHonorario").val(),
            idHonorario : $("#idHonorario").val(),
            montoOriginal : $("#montoOriginal").val()
        };
        

        $.ajax({
            url: "../../guardar_division",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
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
                        toastr.success('Datos guardados', 'Aviso!');
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
                        toastr.error('No se guardaron los datos...', 'Aviso!');
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
                    toastr.error('No se guardaron los datos...', 'Aviso!');
                }
            }
        });

        $("#idMedico").val("");
        $("#cantidadHonorario").val("");
        $("#totalBase").val(nuevoBase);

    }); */

    $(document).on("click", ".close", function() {
        location.reload();
    });
</script>