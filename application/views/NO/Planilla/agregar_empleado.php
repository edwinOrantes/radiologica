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
                    <li class="breadcrumb-item"><a href="#">Agregar empleados </a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Listado de empleados</h6>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?php echo base_url()?>Planilla/personal_planilla/" class="btn btn-success btn-sm"><i class="fa fa-list"></i> Lista empleados </a>
                        </div>
                    </div>
                </div>
                <div class="ms-panel-body">
                    <div class="row">
                        <div class="col md-12">
                            <form class="needs-validation" method="post" action="<?php echo base_url()?>Planilla/guardar_empleado" novalidate>
                                        
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Nombre</strong></label>
                                        <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el nombre del empleado.
                                        </div>
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Área</strong></label>
                                        <select class="form-control" id="areaEmpleado" name="areaEmpleado" required>
                                            <option value="">.:: Seleccionar ::.</option>
                                            <?php
                                                foreach ($areas as $area) {
                                            ?>
                                            <option value="<?php echo $area->idArea; ?>">
                                                <?php echo $area->nombreArea; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
    
                                        <div class="invalid-tooltip">
                                            Debes ingresar el área del empleado.
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Salario</strong></label>
                                        <input type="text" class="form-control" id="salarioEmpleado" name="salarioEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el salario del empleado.
                                        </div>
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Bono</strong> </label>
                                        <input type="text" class="form-control" value="0" id="bonoEmpleado" name="bonoEmpleado" required>
    
                                        <div class="invalid-tooltip">
                                            Debes la fecha en que empezo atrabajar el empleado.
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Cargo</strong></label>
                                        <input type="text" class="form-control" id="cargoEmpleado" name="cargoEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el cargo del empleado.
                                        </div>
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Precio horas extras</strong> </label>
                                        <input type="text" class="form-control" value="0" id="precioHEEmpleado" name="precioHEEmpleado" required>
    
                                        <div class="invalid-tooltip">
                                            Debes ingresar el precio de las horas extras el empleado.
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>DUI</strong></label>
                                        <input type="text" class="form-control" data-mask="99999999-9" id="duiEmpleado" name="duiEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el DUI del empleado.
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Correo</strong></label>
                                        <input type="text" class="form-control" id="correoEmpleado" name="correoEmpleado">
                                        <div class="invalid-tooltip">
                                            Debes ingresar el correo del empleado.
                                        </div>
                                    </div>
    
    
                                </div>
    
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Teléfono</strong></label>
                                        <input type="text" data-mask="9999-9999" class="form-control" id="telefonoEmpleado" name="telefonoEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el telefono del empleado.
                                        </div>
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Estado</strong> </label>
                                        <select class="form-control" id="estadoEmpleado" name="estadoEmpleado" required>
                                            <option value="">.::Selecionar::.</option>
                                            <option value="0">Inactivo</option>
                                            <option value="1">Activo</option>
                                        </select>
                                        <div class="invalid-tooltip">
                                            Debes ingresar el estado del empleado.
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Nacimiento</strong></label>
                                        <input type="date" class="form-control" id="nacimientoEmpleado" name="nacimientoEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la fecha de nacimiento del empleado.
                                        </div>
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label for=""><strong>Ingreso</strong></label>
                                        <input type="date" class="form-control"  id="ingresoEmpleado" name="ingresoEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar la fecha de ingreso del empleado.
                                        </div>
                                    </div>
    
                                </div>
    
    
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for=""><strong>Dirección</strong></label>
                                        <input type="text" class="form-control" id="direccionEmpleado" name="direccionEmpleado" required>
                                        <div class="invalid-tooltip">
                                            Debes ingresar dirección del empleado.
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Guardar </button>
                                    <button type="button" class="btn btn-default has-icon"><i class=" fa fa-times"></i> Cancelar </button>
                                </div>
    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
