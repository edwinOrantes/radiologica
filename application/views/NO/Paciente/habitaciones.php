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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-hospital"></i> Habitaciones </a> </li>
                    <li class="breadcrumb-item"><a href="#">Detalle de habitaciones</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header ms-panel-custome">
                    <h6>Detalle de habitaciones</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
                        <div class="col-md-6 text-center">
                            <h6 class="text-primary">Primera planta</h6>
                            <div class="row">
                                <?php
                                    foreach ($habitaciones as $habitacion) {
                                        if ($habitacion->idPlanta == 1) {
                                            $id ='"'.$habitacion->idHabitacion.'"';
                                            $estado ='"'.$habitacion->estadoHabitacion.'"';

                                            if ($habitacion->estadoHabitacion == 0) {
                                                echo "<div class='col-md-3 alert-primary p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal' title='Habitación libre'>".$habitacion->numeroHabitacion."</a></div>";
                                            }else{
                                                echo "<div class='col-md-3 alert-danger p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal'  title='Habitación ocupada'>".$habitacion->numeroHabitacion."</a></div>";
                                            }
                                        }   
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <h6 class="text-primary">Segunda planta</h6>
                            <div class="row">
                                <?php
                                    foreach ($habitaciones as $habitacion) {
                                        if ($habitacion->idPlanta == 2) {
                                            $id ='"'.$habitacion->idHabitacion.'"';
                                            $estado ='"'.$habitacion->estadoHabitacion.'"';

                                            if ($habitacion->estadoHabitacion == 0) {
                                                echo "<div class='col-md-3 alert-primary p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal' title='Habitación libre'>".$habitacion->numeroHabitacion."</a></div>";
                                            }else{
                                                echo "<div class='col-md-3 alert-danger p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal'  title='Habitación ocupada'>".$habitacion->numeroHabitacion."</a></div>";
                                            }
                                        }   
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6 text-center">
                            <h6 class="text-primary">Tercera planta</h6>
                            <div class="row">
                                <?php
                                    foreach ($habitaciones as $habitacion) {
                                        if ($habitacion->idPlanta == 3) {
                                            $id ='"'.$habitacion->idHabitacion.'"';
                                            $estado ='"'.$habitacion->estadoHabitacion.'"';

                                            if ($habitacion->estadoHabitacion == 0) {
                                                echo "<div class='col-md-3 alert-primary p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal' title='Habitación libre'>".$habitacion->numeroHabitacion."</a></div>";
                                            }else{
                                                echo "<div class='col-md-3 alert-danger p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal'  title='Habitación ocupada'>".$habitacion->numeroHabitacion."</a></div>";
                                            }
                                        }   
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <h6 class="text-primary">Cuarta planta</h6>
                            <div class="row">
                                <?php
                                    foreach ($habitaciones as $habitacion) {
                                        if ($habitacion->idPlanta == 4) {
                                            $id ='"'.$habitacion->idHabitacion.'"';
                                            $estado ='"'.$habitacion->estadoHabitacion.'"';

                                            if ($habitacion->estadoHabitacion == 0) {
                                                echo "<div class='col-md-3 alert-primary p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal' title='Habitación libre'>".$habitacion->numeroHabitacion."</a></div>";
                                            }else{
                                                echo "<div class='col-md-3 alert-danger p-5 m-1'><a onclick='gestionarHabitacion($id, $estado)' href='#detalleHabitacion' data-toggle='modal'  title='Habitación ocupada'>".$habitacion->numeroHabitacion."</a></div>";
                                            }
                                        }   
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal para eliminar datos del Medicamento-->
<div class="modal fade" id="detalleHabitacion" tabindex="-1" role="dialog" aria-labelledby="modal-5">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5">
            
            <div class="modal-header bg-primary">
                <h4 class="modal-title has-icon text-white"><i class="fa fa-list"></i> Detalle de la habitación </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
            </div>
            <div class="modal-body">
                <div id="detalleH"></div>
            </div>

		</div>
	</div>
</div>

<!-- Fin Modal eliminar  datos del Medicamento-->

<script>
function gestionarHabitacion(id, estado){

    $.ajax({
    url: "detalle_habitacion",
    type: "GET",
    data: {id:id},
    success:function(respuesta){
            var registro = eval(respuesta);
            var html = "";
            if (registro.length > 0)
            {   
                
                for (var i = 0; i < registro.length; i++) 
                {
                    html += "<table class='table table-borderless'>";
                    html += "    <tr>";
                    html += "        <td><strong>Paciente:</strong></td>";
                    html += "        <td>"+registro[i]["nombrePaciente"]+"</td>";
                    html += "    </tr>";
                    html += "    <tr>";
                    html += "        <td><strong>Fecha de ingreso:</strong></td>";
                    html += "        <td>"+registro[i]["fechaIngreso"]+"</td>";
                    html += "    </tr>";
                    html += "    <tr>";
                    html += "        <td><strong>Dieta:</strong></td>";
                    html += "        <td>"+registro[i]["dietaPaciente"]+"</td>";
                    html += "    </tr>";
                    html += "    <tr>";
                    html += "        <td><strong>Médico:</strong></td>";
                    html += "        <td>"+registro[i]["medicoPaciente"]+"</td>";
                    html += "    </tr>";
                    html += "    <tr>";
                    html += "        <td><strong>Observaciones:</strong></td>";
                    html += "        <td>"+registro[i]["observacionPaciente"]+"</td>";
                    html += "    </tr>";
                    html += "</table>";
                }

                
            }else{
                html += '<h2 class="text-center text-danger"><strong>Esta habitación esta vacia</strong></h2>';
            }
            $("#detalleH").html(html);
            console.log(anio);
        }
    });

}
</script>

