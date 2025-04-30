<?php if($this->session->flashdata("exito")):?>
<script type="text/javascript">
$(document).ready(function() {
    toastr.remove();
    toastr.options.positionClass = "toast-top-center";
    toastr.success('<?php echo $this->session->flashdata("exito")?>', 'Aviso!');
});
</script>
<?php endif; ?>

<?php if($this->session->flashdata("error")):?>
<script type="text/javascript">
$(document).ready(function() {
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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Empleados
                        </a> </li>
                    <li class="breadcrumb-item"><a href="#">Lista empleados </a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Listado de empleados</h6>
                        </div>
                        <div class="col-md-6 text-right">
                            <!-- <a href="<?php echo base_url()?>Medico/medicos_excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver Excel</a> -->
                        </div>
                    </div>
                </div>
                <div class="ms-panel-body">
                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table id="" class="table table-striped thead-primary w-100 tablaPlus">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Nombre</th>
                                        <th class="text-center" scope="col">Cargo</th>
                                        <th class="text-center" scope="col">Telefono</th>
                                        <th class="text-center" scope="col">Dirección</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                      $index = 0;
                                        foreach ($empleados as $empleado) {
                                          $index++;
                                          $id ='"'.$empleado->idEmpleado.'"';
                                          $nombre = '"'.$empleado->nombreEmpleado.'"';
                                          $apellido = '"'.$empleado->apellidoEmpleado.'"';
                                          $edad = '"'.$empleado->edadEmpleado.'"';
                                          $telefono = '"'.$empleado->telefonoEmpleado.'"';
                                          $cargo = '"'.$empleado->cargoEmpleado.'"';
                                          $cargoN = '"'.$empleado->nombreCargo.'"';
                                          $sexo = '"'.$empleado->sexoEmpleado.'"';
                                          $dui = '"'.$empleado->duiEmpleado.'"';
                                          $nit = '"'.$empleado->nitEmpleado.'"';
                                          $estado = '"'.$empleado->estadoEmpleado.'"';
                                          $nacimiento = '"'.$empleado->nacimientoEmpleado.'"';
                                          $departamento = '"'.$empleado->departamentoEmpleado .'"';
                                          $departamentoN = '"'.$empleado->nombreDepartamento .'"';
                                          $municipio = '"'.$empleado->municipioEmpleado .'"';
                                          $municipioN = '"'.$empleado->nombreMunicipio .'"';
                                          $direccion = '"'.$empleado->direccionEmpleado.'"';
                                          $ingreso = '"'.$empleado->ingresoEmpleado.'"';

                                    ?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center"><?php echo $empleado->nombreEmpleado." ".$empleado->apellidoEmpleado; ?></td>
                                        <td class="text-center"><?php echo $empleado->nombreCargo; ?></td>
                                        <td class="text-center"><?php echo $empleado->telefonoEmpleado; ?></td>
                                        <td class="text-center">
                                            <?php echo $empleado->direccionEmpleado/*." ".$empleado->nombreDepartamento." ".$empleado->nombreMunicipio*/; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                               echo "<a onclick='verEmpleado($id, $nombre, $apellido, $edad, $telefono, $cargoN, $sexo, $dui, $nit, $estado, $nacimiento, $departamentoN, $municipioN, $direccion, $ingreso)' href='#verEmpleado' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
                                               echo "<a onclick='actualizarEmpleado($id, $nombre, $apellido, $edad, $telefono, $cargo, $sexo, $dui, $nit, $estado, $nacimiento, $departamento, $municipio, $direccion, $ingreso)' href='#actualizarEmpleado' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                               echo "<a onclick='eliminarEmpleado($id)' href='#eliminarEmpleado' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                            ?>
                                        </td>
                                    </tr>

                                    <?php }	?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ver datos del Medicamento-->
<div class="modal fade" id="verEmpleado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ms-modal-dialog-width">
        <div class="modal-content ms-modal-content-width">
            <div class="modal-header  ms-modal-header-radius-0">
                <h4 class="modal-title text-white"></i> Datos del médico</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                        aria-hidden="true" class="text-white">&times;</span></button>
            </div>

            <div class="modal-body p-0 text-left">
                <div class="col-xl-12 col-md-12">
                    <div class="ms-panel ms-panel-bshadow-none">
                        <div class="ms-panel-body" id="detalleMedico">

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Fin Modal ver datos del Medicamento-->

<!-- Modal para actualizar datos del Medicamento-->
<div class="modal fade" id="actualizarEmpleado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ms-modal-dialog-width">
        <div class="modal-content ms-modal-content-width">
            <div class="modal-header  ms-modal-header-radius-0">
                <h4 class="modal-title text-white"></i> Datos del médico</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                        aria-hidden="true" class="text-white">&times;</span></button>
            </div>

            <div class="modal-body p-0 text-left">
                <div class="col-xl-12 col-md-12">
                    <div class="ms-panel ms-panel-bshadow-none">
                        <div class="ms-panel-body">
                            <form class="needs-validation" method="post" action="<?php echo base_url()?>Empleado/actualizar_empleado" novalidate>
                                
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Nombre</strong></label>
                                        <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado" placeholder="Nombre del empleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el nombre del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Apellido</strong></label>
                                        <input type="text" class="form-control" id="apellidoEmpleado" name="apellidoEmpleado" placeholder="Apellido del empleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el apellido del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Edad</strong></label>
                                        <input type="number" class="form-control" min="0" id="edadEmpleado" name="edadEmpleado" placeholder="Edad del empleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la edad del empleado.
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Teléfono</strong></label>
                                        <input type="text" class="form-control" data-mask="9999-9999" id="telefonoEmpleado" name="telefonoEmpleado" placeholder="Teléfono del empleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el teléfono del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Ocupación</strong></label>
                                        <select class="form-control" id="cargoEmpleado" name="cargoEmpleado" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <?php
                                        foreach ($cargos as $cargo) {
                                    ?>
                                            <option value="<?php echo $cargo->idCargo; ?>">
                                                <?php echo $cargo->nombreCargo; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la ocupación empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Sexo</strong></label>
                                        <select class="form-control" id="sexoEmpleado" name="sexoEmpleado" required>
                                            <option value=""> .:: Seleccionar ::.</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el sexo empleado.
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>DUI</strong></label>
                                        <input type="text" class="form-control" id="duiEmpleado" name="duiEmpleado" data-mask="99999999-9" placeholder="DUI del empleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el DUI del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>NIT</strong></label>
                                        <input type="text" class="form-control" data-mask="9999-999999-999-9" id="nitEmpleado" name="nitEmpleado" placeholder="NIT del empleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el DUI del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Estado civil</strong></label>
                                        <select class="form-control" id="estadoEmpleado" name="estadoEmpleado" required>
                                            <option value=""> .:: Seleccionar ::.</option>
                                            <option value="Soltero/a">Soltero/a</option>
                                            <option value="Casado/a">Casado/a</option>
                                            <option value="Viudo/a">Viudo/a</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el estado civil del empleado.
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Fecha de nacimiento</strong></label>
                                        <input type="date" class="form-control" id="nacimientoEmpleado" name="nacimientoEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la fecha de nacimiento del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Departamento</strong></label>
                                        <select class="form-control" id="departamentoEmpleado" name="departamentoEmpleado" required>
                                            <option value="">.:: Seleccionar empleado ::.
                                            <option>
                                                <?php
                                        foreach ($departamentos as $departamento) {
                                    ?>
                                            <option value="<?php echo $departamento->idDepartamento?>">
                                                <?php echo $departamento->nombreDepartamento?></option>

                                            <?php } ?>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el departamento del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Municipio</strong> </label>
                                        <select class="form-control" id="municipioEmpleado" name="municipioEmpleado" required>
                                            <option value=""> .:: Seleccionar ::.</option>
                                            <option value="">A</option>
                                            <option value="">B</option>
                                            <option value="">C</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes seleccionar el municipio del empleado.
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for=""><strong>Dirección completa</strong></label>
                                        <input type="text" class="form-control" id="direccionEmpleado" name="direccionEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar dirección del empleado.
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for=""><strong>Ingreso al hospita</strong> </label>
                                        <input type="date" class="form-control" id="ingresoEmpleado" name="ingresoEmpleado" required>
                                        <input type="hidden" class="form-control" id="idEmpleado" name="idEmpleado" placeholder="Nombre del empleado" required>

                                        <div class="invalid-tooltip">
                                            Debes la fecha en que empezo atrabajar el empleado.
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i>
                                        Actualizar
                                    </button>
                                    <button type="button" class="btn btn-default has-icon"><i class=" fa fa-times"></i>
                                        Cancelar
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Fin Modal actualizar datos del Medicamento-->


<!-- Modal para eliminar datos del Medicamento-->
<div class="modal fade" id="eliminarEmpleado" tabindex="-1" role="dialog" aria-labelledby="modal-5">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content pb-5">
            <form action="<?php echo base_url() ?>Empleado/eliminar_empleado" method="post">
                <div class="modal-header bg-danger">
                    <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                            class="text-white">&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <p class="h5">¿Estas seguro de eliminar los datos de este empleado ?</p>
                    <input type="hidden" id="empleadoEliminar" name="idEmpleado" />
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i>
                        Eliminar</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i>
                        Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Fin Modal eliminar  datos del Medicamento-->

<script>
function verEmpleado(id, nombre, apellido, edad, telefono, cargo, sexo, dui, nit, estado, nacimiento, departamentoN, municipioN, direccion, ingreso) {
    var html = '';
    html += '<table class="table table-borderless">';
    html += '<tr>';
    html += '<td><strong>Nombre</strong></td>';
    html += '<td>' + nombre + ' ' + apellido + '</td>';
    html += '<td><strong>Edad</strong></td>';
    html += '<td>' + edad + '</td>';
    html += '</tr>';

    html += '<tr>';
    html += '<td><strong>Telefono</strong></td>';
    html += '<td>' + telefono + '</td>';
    html += '<td><strong>Cargo</strong></td>';
    html += '<td>' + cargo + '</td>';
    html += '</tr>';

    html += '<tr>';
    html += '<td><strong>Sexo</strong></td>';
    html += '<td>' + sexo + '</td>';
    html += '<td><strong>DUI</strong></td>';
    html += '<td>' + dui + '</td>';
    html += '</tr>';

    html += '<tr>';
    html += '<td><strong>NIT</strong></td>';
    html += '<td>' + nit + '</td>';
    html += '<td><strong>Estado</strong></td>';
    html += '<td>' + estado + '</td>';
    html += '</tr>';

    html += '<tr>';
    html += '<td><strong>Fecha de nacimiento</strong></td>';
    html += '<td>' + nacimiento + '</td>';
    html += '<td><strong>Ingreso al hospital</strong></td>';
    html += '<td>' + ingreso + '</td>';
    html += '</tr>';


    html += '<tr>';
    html += '<td><strong>Dirección</strong></td>';
    html += '<td>' + direccion + ', ' + municipioN + ', ' + departamentoN + '</td>';
    html += '<td><strong></strong></td>';
    html += '<td><strong></strong></td>';
    html += '</tr>';
    html += '</table>';

    document.getElementById('detalleMedico').innerHTML = html;
}

function actualizarEmpleado(id, nombre, apellido, edad, telefono, cargo, sexo, dui, nit, estado, nacimiento, departamento, municipio, direccion, ingreso) {
    $("#idEmpleado").val(id);
    $("#nombreEmpleado").val(nombre);
    $("#apellidoEmpleado").val(apellido);
    $("#edadEmpleado").val(edad);
    $("#telefonoEmpleado").val(telefono);
    $("#cargoEmpleado").val(cargo);
    $("#sexoEmpleado").val(sexo);
    $("#duiEmpleado").val(dui);
    $("#nitEmpleado").val(nit);
    $("#estadoEmpleado").val(estado);
    $("#nacimientoEmpleado").val(nacimiento);
    $("#departamentoEmpleado").val(departamento);
    $("#municipioEmpleado").val(municipio);
    $("#direccionEmpleado").val(direccion);
    $("#ingresoEmpleado").val(ingreso);

}

function eliminarEmpleado(id) {
    $("#empleadoEliminar").val(id);
}



</script>