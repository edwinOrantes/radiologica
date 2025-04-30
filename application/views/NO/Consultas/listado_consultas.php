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
                            <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Consultas </a> </li>
                            <li class="breadcrumb-item"><a href="#">Lista consultas pendientes</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right"></div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <h5><?php echo $titulo; ?></h5>
				</div>

                <div class="ms-panel-body">
                    <div class="row">
						<div class="col-md-12">
                            <?php 
                                if(sizeof($pendientes) > 0){
                            ?>
                            <table class="table table-bordered thead-primary">  <!-- llegada-pacientes -->
                                <thead class="thead-primary">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Paciente</th>
                                        <th>Llegada</th>
                                        <th>Edad</th>
                                        <th>Código</th>
                                        <th>Viene de</th>
                                        <th>Saldada</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $estado = 0;
                                        $cuenta = 0;
                                        foreach ($pendientes as $fila) {
                                            // Para verificar si la consulta ya fue realizada
                                            if($fila->estadoLlegada == 1){
                                                $estado = '<span class="badge badge-outline-danger">Esperando</span>';
                                            }else{
                                                $estado = '<span class="badge badge-outline-success">Atendido</span>';
                                            }
                                            // Para verificar si la cuenta ya fue saldada
                                            if($fila->correlativoSalidaHoja == 0){
                                                $cuenta = '<span class="badge badge-outline-danger">Pendiente</span>';
                                            }else{
                                                $cuenta = '<span class="badge badge-outline-success">Saldada</span>';
                                            }

                                            if(strpos($fila->codigoLlegada, "P") && $fila->estadoLlegada == 1) {
                                                echo '<tr class="alert-danger">';
                                            }else{
                                                echo '<tr>';
                                            }
                                    ?>
                                    
                                        <td class="text-center"><?php echo $fila->numeroLlegada; ?></td>
                                        <td class="text-center text-uppercase"><?php echo $fila->pacienteLlegada; ?></td>
                                        <td class="text-center"><?php echo date("G:i A", strtotime(substr($fila->fecha, 11, 30))); ?></td>
                                        <td class="text-center">
                                            <?php
                                                if($fila->edadLlegada > 0){
                                                    echo $fila->edadLlegada." Años";
                                                }else{
                                                    echo "--";
                                                }
                                                                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo $fila->codigoLlegada; ?></td>

                                        <td class="text-center">
                                            <?php
                                                if(strpos($fila->codigoLlegada, "01") ){
                                                    echo '<span class="badge badge-outline-primary">Hospital</span>';
                                                }else{
                                                    echo '<span class="badge badge-outline-warning">Bienestar</span>';
                                                }
                                            ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <?php
                                                //if(strpos($fila->codigoLlegada, "AM01") ){
                                                if( $fila->codigoLlegada == "AM01" ){
                                                    echo $cuenta; 
                                                }
                                            ?>
                                            
                                        </td>
                                        <td class="text-center estadoConsulta"><?php echo $estado; ?><input type="hidden" value="<?php echo $fila->idLlegada; ?>" id="idLlegada"> </td>
                                        <td class="text-center accionActual">
                                            <?php
                                                if($fila->estadoLlegada == 1){
                                                    echo '<a href="#" class="text-danger liberarCupo"><i class="far fa-check-circle"></i></a>';
                                                }else{
                                                    echo '<a href="#" class="text-success regresarCupo"><i class="fas fa-check-circle"></i></a>';
                                                }

                                                // if($fila->correlativoSalidaHoja == 0){
                                                //    echo '<a href="'.base_url().'Hoja/detalle_hoja/'.$fila->idHoja.'/" target="blank" title="Ver hoja"><i class="far fa-file ms-text-primary"></i></a>';
                                                // }


                                            ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php }else{ ?>
                            <div class="alert-danger text-center py-4"><strong>No hay datos que mostrar...</strong></div>
                        <?php } ?>
						</div>
					</div>
                </div>

			</div>
		</div>
	</div>
</div>


<script>
    $(document).on("click", ".liberarCupo", function(event) {
        event.preventDefault();
        var cupo = $(this).closest('tr').find("#idLlegada").val();
        datos = {
            idCupo: cupo
        }
        $.ajax({
            url: "liberar_cupo",
            type: "POST",
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if(registro.length > 0){}
            }
        });

        $(this).closest('tr').find(".estadoConsulta").html('<span class="badge badge-outline-success">Atendido</span>')
        $(this).closest('tr').find(".accionActual").html('<a href="#" class="text-success"><i class="fas fa-check-circle"></i></a>')

    });

    $(document).on("click", ".regresarCupo", function(event) {
        event.preventDefault();
        
        var resp = confirm("¿El paciente aun no ha sido atendido?");
        if(resp){
            var cupo = $(this).closest('tr').find("#idLlegada").val();
            datos = {
                idCupo: cupo
            }
            $.ajax({
                url: "regresar_cupo",
                type: "POST",
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if(registro.length > 0){}
                }
            });

            $(this).closest('tr').find(".estadoConsulta").html('<span class="badge badge-outline-danger">Esperando</span>')
            $(this).closest('tr').find(".accionActual").html('<a href="#" class="text-danger"><i class="far fa-check-circle"></i></a>')
        }else{
            console.log("Peticion denegada");
        }
    });
</script>

<script>
  //Cuando la página esté cargada completamente
  $(document).ready(function(){
    //Cada 5 minutos (300000 milisegundos) se ejecutará la función refrescar
    setTimeout(refrescar, 300000);
  });
  function refrescar(){
    //Actualiza la página
    location.reload();
  }
</script>