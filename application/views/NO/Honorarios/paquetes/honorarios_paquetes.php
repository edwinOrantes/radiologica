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
                            <li class="breadcrumb-item"><a href="#">Paquetes</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-right"></div>
            </div>
			<div class="ms-panel">
				<div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6"><h6>Honorarios de paquetes</h6></div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Honorarios/gestion_honorarios_paquetes"> Entregar honorarios <i class="fa fa-hand-holding-usd"></i> </a>
							<a href="#reportePaquetes"" class="btn btn-outline-primary btn-sm" data-toggle="modal"><i class="fa fa-file"></i> Resumen honorarios </a>
                        </div>
                    </div>
				</div>
			
            
				<div class="ms-panel-body">
					<div class="row mt-3">
                        <?php
                            if(sizeof($honorarios) > 0){
                        ?>
                            <div class="table-responsive">
                                <table id="tabla-pacientes" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Hoja</th>
                                            <th class="text-center">Paciente</th>
                                            <th class="text-center">Médico</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index = 0;
                                            foreach ($honorarios as $row) {
                                                $index++;
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php
                                                    if($row->totalHonorarioPaquete != $row->originalHonorarioPaquete){
                                                        echo "<i class='fa fa-users text-warning'></i>";
                                                    }else{
                                                        echo "<i class='fa fa-user text-primary'></i>";
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-center"><?php echo $index; ?></td>
                                            <td class="text-center"> <a href="<?php echo base_url().'Hoja/detalle_hoja/'.$row->idHoja.'/'; ?>" target="_blank" rel="noopener noreferrer"><?php echo $row->codigoHoja; ?></a> </td>
                                            <td class="text-center"><?php echo $row->nombrePaciente; ?></td>
                                            <td class="text-center"><?php echo $row->nombreMedico; ?></td>
                                            <td class="text-center">$<?php echo number_format($row->totalHonorarioPaquete, 2); ?></td>
                                            <td class="text-center">
                                                <?php
                                                    echo '<a href="'.base_url().'Honorarios/dividir_honorario/'.$row->idHonorarioPaquete.'/" title="Dividir honorario"><i class="fa fa-list text-primary"></i></a>';
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
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

<!-- Modal para agregar datos del Medicamento-->
<div class="modal fade" id="reportePaquetes" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <form class="needs-validation" id="generarFacturados" method="post" action="<?php echo base_url()?>Honorarios/reporte_honorario_paquetes" target="_blank" novalidate>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <label class="" for=""><strong>Médico</strong></label>
                                                <div class="input-group mb-2">

                                                    <select class="form-control controlInteligente2" id="idMedico" name="idMedico" required>
                                                        <option value="">.:: Seleccionar ::.</option>
                                                        <?php
                                                            foreach ($medicos as $medico) {
                                                        ?>
                                                            <option value="<?php echo $medico->idMedico; ?>"><?php echo $medico->nombreMedico; ?></option>
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
/*         $("#generarExcel").hide();
        $("#saldarTodos").hide();
        $("#totalHonorario").hide();
        $("#pivoteHonorario").hide(); */
        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });
        $('.controlInteligente2').select2({
            theme: "bootstrap4",
            dropdownParent: $("#reportePaquetes")
        });
    });
</script>