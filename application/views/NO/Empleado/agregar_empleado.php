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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-user"></i> Empleado</a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Agregar empleado</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header ms-panel-custome">
                    <h6>Datos del empleado</h6>
                    <a href="<?php echo base_url()?>Empleado/lista_empleados" class="btn btn-outline-primary btn-sm ms-text-primary">
                        <i class="fa fa-users"></i> Lista de empleados </a>
                </div>
                <div class="ms-panel-body">
                    <form class="needs-validation" method="post" action="<?php echo base_url()?>Empleado/agregar_empleado" novalidate>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for=""><strong>Nombre</strong></label>
                                <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado"
                                    placeholder="Nombre del empleado" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el nombre del empleado.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Apellido</strong></label>
                                <input type="text" class="form-control" id="apellidoEmpleado" name="apellidoEmpleado"
                                    placeholder="Apellido del empleado" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el apellido del empleado.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Edad</strong></label>
                                <input type="number" class="form-control" min="0" id="edadEmpleado" name="edadEmpleado"
                                    placeholder="Edad del empleado" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la edad del empleado.
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="form-group col-md-4">
                                <label for=""><strong>Teléfono</strong></label>
                                <input type="text" class="form-control" data-mask="9999-9999" id="telefonoEmpleado"
                                    name="telefonoEmpleado" placeholder="Teléfono del empleado" required>
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
                                    <option value="<?php echo $cargo->idCargo; ?>"><?php echo $cargo->nombreCargo; ?>
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
                                <input type="text" class="form-control" id="duiEmpleado" name="duiEmpleado"
                                    data-mask="99999999-9" placeholder="DUI del empleado" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar el DUI del empleado.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>NIT</strong></label>
                                <input type="text" class="form-control" data-mask="9999-999999-999-9" id="nitEmpleado"
                                    name="nitEmpleado" placeholder="NIT del empleado" required>
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
                                <input type="date" class="form-control" id="nacimientoEmpleado"
                                    name="nacimientoEmpleado" required>
                                <div class="invalid-tooltip">
                                    Debes ingresar la fecha de nacimiento del empleado.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Departamento</strong></label>
                                <select class="form-control" id="departamentoEmpleado" name="departamentoEmpleado"
                                    required>
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
                                <input type="text" class="form-control" id="direccionEmpleado" name="direccionEmpleado"
                                    required>
                                <div class="invalid-tooltip">
                                    Debes ingresar dirección del empleado.
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for=""><strong>Ingreso al hospita</strong> </label>
                                <input type="date" class="form-control" id="ingresoEmpleado" name="ingresoEmpleado"
                                    required>
                                <div class="invalid-tooltip">
                                    Debes la fecha en que empezo atrabajar el empleado.
                                </div>
                            </div>

                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Guardar
                            </button>
                            <a href="<?php echo base_url(); ?>Empleado/"  class="btn btn-default has-icon"><i class=" fa fa-times"></i> Cancelar</a>
                            <!-- <button type="reset" class="btn btn-default has-icon"><i class=" fa fa-times"></i> Cancelar
                            </button> -->
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('change', '#edadEmpleado', function(event) {
    event.preventDefault();
    var edad = $(this).val();
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = (now.getFullYear() - edad) + "-" + (month) + "-" + (day);
    $("#nacimientoEmpleado").val(today);
});
</script>