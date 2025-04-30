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

<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Pacientes</a> </li>
                    <li class="breadcrumb-item"><a href="#">Lista pacientes</a></li>
                </ol>
            </nav>

			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="frmMedicamento">
                                <input type="text" class="form-control" id="codigoMedicamento" name="codigoMedicamento" placeholder="Código del medicamento..."/>
                            </form>
                                
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Medicamentos/cuentas_medicamento/"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
                    <ul class="nav nav-tabs d-flex nav-justified mb-4" role="tablist">
                        <li role="presentation"><a href="#tab13" aria-controls="tab13" class="active show" role="tab" data-toggle="tab" aria-selected="true">Agregados recientemente</a></li>
                        <li role="presentation"><a href="#tab14" aria-controls="tab14" role="tab" data-toggle="tab" class="" aria-selected="false">Historial agregados</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active show" id="tab13">
                            <!-- Inicio -->
                            <div class="contenido">
                                <?php
                                    if(sizeof($detalleCuenta) > 0){
                                ?>
                                <!-- <table id="tabla-pacientes" class="table table-striped thead-primary w-100"> -->
                                <table id="tablag" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Medicamento</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center" style="display: none;">ID</th>
                                            <th class="text-center" style="display: none;">Cuenta</th>
                                            <th class="text-center">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $index = 0;
                                            foreach ($detalleCuenta as $fila) {
                                                $index++;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $index; ?></td>
                                            <td class="text-center"><?php echo $fila->codigoMedicamento; ?></td>
                                            <td class="text-center"><?php echo $fila->nombreMedicamento; ?></td>
                                            <td class="text-center"><?php echo $fila->cantidadMedicamento; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idMedicamento; ?></td>
                                            <td class="text-center" style="display: none;"><?php echo $fila->idCuentaMedicamento;; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php
                                    }else{
                                        echo '<div class="col-md-12 alert alert-danger">
                                                    <h6 class="text-center"><strong>No datos que mostrar.</strong></h6>
                                                </div>';
                                    }
                                ?>
                            </div>
                            <!-- Fin -->
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab14">
                            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam urna nunc, congue nec laoreet sed, maximus non massa. Fusce vestibulum vel risus vitae tincidunt. </p>
                            <p> Cras egestas nisi vel tempor dignissim. Ut condimentum iaculis ex nec ornare. Vivamus sit amet elementum ante. Fusce eget erat volutpat </p>
                            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam urna nunc, congue nec laoreet sed, maximus non massa. Fusce vestibulum vel risus vitae tincidunt. </p>
                        </div>
                    </div>




                    
                </div>
            </div>
		</div>
	</div>
    <input type="hidden" value="<?php echo $cuenta; ?>" id="cuentaActual"/>
</div>

<script src="<?php echo base_url(); ?>public/js/jquery.tabledit.js"></script>

<script>
    $(document).ready(function() {
        $("#codigoMedicamento").focus();
        
        $( "#frmMedicamento" ).submit(function( event ) {
            event.preventDefault();
            var datos = {
                codigo : $("#codigoMedicamento").val(),
                cuenta : $("#cuentaActual").val()
            }

            $("#codigoMedicamento").val("");

            $.ajax({
            url: "../../descontar_medicamento",
            type: "GET",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                    var registro = eval(respuesta);
                    if (registro.length > 0){
                        var html = "";
                        var index = 0;
                        for (var i = 0; i < registro.length; i++) {
                            index++;
                            html += "<tr>";
                            html += "    <td class='text-center'>"+index+"</td>";
                            html += "    <td class='text-center'>"+registro[i]["codigoMedicamento"]+"</td>";
                            html += "    <td class='text-center'>"+registro[i]["nombreMedicamento"]+"</td>";
                            html += "    <td class='text-center'>"+registro[i]["cantidadMedicamento"]+"</td>";
                            html += "    <td class='text-center'>---</td>";
                            html += "</tr>";
                        }
                        $("#tablag tbody").html(html);
                    }
                },
                error:function(){
                    alert("Hay un error");
                }
            });



        });

        $('#tablag').Tabledit({
            url: '../../editar_medicamento',
            columns: {
                identifier: [0, 'fila'],
                editable: [[1, 'codigo'], [2, 'nombreMedicamento'], [3, 'cantidad'], [4, 'idMedicamento'], [5, 'cuentaMedicamento']]
            },
            restoreButton:false,
        });

    });


</script>


