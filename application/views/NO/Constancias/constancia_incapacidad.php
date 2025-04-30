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
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-user-md"></i> Constancias </a> </li>
                        <li class="breadcrumb-item"><a href="#">Constancia de incapacidad</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6"><h6>Listado de constancias de incapacidad </h6></div>
                            <div class="col-md-6 text-right">
                                    <button class="btn btn-primary btn-sm" href="#agregarContancia" data-toggle="modal"><i class="fa fa-plus"></i> Agregar constancias</button>
                            </div>
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="row">
                            <div class="table-responsive mt-3">
                                <table id="tabla-medicamentos" class="table table-striped thead-primary w-100 tablaPlus">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th class="text-center" scope="col">Paciente</th>
                                            <th class="text-center" scope="col">Edad</th>
                                            <th class="text-center" scope="col">Fecha</th>
                                            <th class="text-center" scope="col">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <!-- 
                                        <?php
                                        $index = 0;
                                            foreach ($constancias as $constancia) {
                                                $index++;
                                                // $id ='"'.$medico->idMedico.'"';
                                        ?>
                                        <tr>
                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                            <td class="text-center"><?php echo $constancia->pacienteConstancia; ?></td>
                                            <td class="text-center"><?php echo $constancia->edadConstancia; ?> Años</td>
                                            <td class="text-center">
                                                <?php echo substr($constancia->fechaConstancia, 0, 10); ?>
                                                <input type="hidden" value="<?php echo $constancia->idConstancia; ?>" class="idConstancia">
                                                <input type="hidden" value="<?php echo $constancia->pacienteConstancia; ?>" class="pacienteConstancia">
                                                <input type="hidden" value="<?php echo $constancia->sexoConstancia; ?>" class="sexoConstancia">
                                                <input type="hidden" value="<?php echo $constancia->edadConstancia; ?>" class="edadConstancia">
                                                <input type="hidden" value="<?php echo $constancia->diaConstancia; ?>" class="diaConstancia">
                                                <input type="hidden" value="<?php echo $constancia->mesConstancia; ?>" class="mesConstancia">
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    echo "<a  href='".base_url()."Constancias/detalle_constancia/".$constancia->idConstancia."' target='_blank'><i class='far fa-eye ms-text-primary'></i></a>";
                                                    echo "<a href='#actualizarContancia' class='actualizarContancia' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                    echo "<a href='#eliminarConstancia' class='eliminarConstancia' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                ?>
                                            </td>
                                        </tr>

                                        <?php }	?>  -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal para agregar datos del Medicamento-->
    <div class="modal fade" id="agregarContancia" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del paciente</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="frmConstancia"  method="post" action="<?php echo base_url() ?>Constancias/guardar_constancia_incapacidad"  novalidate>
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Nombre</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pacienteConstancia" name="pacienteConstancia" placeholder="Nombre del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el nombre del paciente.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Sexo</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="sexoConstancia" name="sexoConstancia" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Femenino</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese el sexo del paciente.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for=""><strong>Edad</strong></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="edadConstancia" name="edadConstancia" placeholder="Edad del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la edad del paciente.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for=""><strong>Dia</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="diaConstancia" name="diaConstancia" placeholder="Teléfono de la clinica" required>
                                                <div class="invalid-tooltip">
                                                Ingrese el teléfono del médico.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Mes</strong></label>
                                            <select class="form-control" id="mesConstancia" name="mesConstancia" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($meses as $mes) {
                                                ?>
                                                <option value="<?php echo $mes; ?>"><?php echo $mes; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Ingrese el mes de la constancia.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Guardar constancia</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal para agregar datos del Medicamento-->

<!-- Modal para actualizar datos del Medicamento-->
    <div class="modal fade" id="actualizarContancia" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white"></i> Datos del paciente</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">

                                <form class="needs-validation" id="frmConstancia"  method="post" action="<?php echo base_url() ?>Constancias/actualizar_constancia"  novalidate>
                                    
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Nombre</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pacienteConstanciaA" name="pacienteConstancia" placeholder="Nombre del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese el nombre del paciente.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Sexo</strong></label>
                                            <div class="input-group">
                                                <select class="form-control" id="sexoConstanciaA" name="sexoConstancia" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Femenino</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Ingrese el sexo del paciente.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for=""><strong>Edad</strong></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="edadConstanciaA" name="edadConstancia" placeholder="Edad del paciente" required>
                                                <div class="invalid-tooltip">
                                                    Ingrese la edad del paciente.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-12">
                                            <label for=""><strong>Dia</strong></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="diaConstanciaA" name="diaConstancia" placeholder="Teléfono de la clinica" required>
                                                <div class="invalid-tooltip">
                                                Ingrese el teléfono del médico.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for=""><strong>Mes</strong></label>
                                            <select class="form-control" id="mesConstanciaA" name="mesConstancia" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <?php
                                                    foreach ($meses as $mes) {
                                                ?>
                                                <option value="<?php echo $mes; ?>"><?php echo $mes; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Ingrese el mes de la constancia.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <input type="hidden" id="idConstancia" name="idConstancia" required>
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar constancia</button>
                                        <button class="btn btn-light mt-4 d-inline w-20" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Fin Modal para actualizar datos del Medicamento-->

<!-- Modal para eliminar datos de la constancia-->
    <div class="modal fade" id="eliminarConstancia" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">

                <div class="modal-header bg-danger">
                    <h5 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">×</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">
                        ¿Estas seguro de eliminar esta constancia?
                    </p>
                </div>

                <form action="<?php echo base_url(); ?>Constancias/eliminar_constancia/" method="post">
                    <input type="hidden" id="idConstanciaE" value="" name="idConstancia">
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-unlock"></i> Eliminar </button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<!-- Modal para eliminar datos de la constancia-->


<script>
    $(document).on('click', '.actualizarContancia', function(event) {
        event.preventDefault();
        $("#idConstancia").val($(this).closest('tr').find(".idConstancia").val());
        $("#pacienteConstanciaA").val($(this).closest('tr').find(".pacienteConstancia").val());
        $("#sexoConstanciaA").val($(this).closest('tr').find(".sexoConstancia").val());
        $("#edadConstanciaA").val($(this).closest('tr').find(".edadConstancia").val());
        $("#diaConstanciaA").val($(this).closest('tr').find(".diaConstancia").val());
        $("#mesConstanciaA").val($(this).closest('tr').find(".mesConstancia").val());
    });

    $(document).on('click', '.eliminarConstancia', function(event) {
        event.preventDefault();
        console.log("hola");
        $("#idConstanciaE").val($(this).closest('tr').find(".idConstancia").val());
    });
</script>