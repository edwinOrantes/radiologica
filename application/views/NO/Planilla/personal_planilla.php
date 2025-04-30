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
                            <a href="<?php echo base_url()?>Planilla/agregar_empleado_planilla" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar empleado </a>
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
                                        <th class="text-center" scope="col">Salario</th>
                                        <th class="text-center" scope="col">Telefono</th>
                                        <th class="text-center" scope="col">DUI</th>
                                        <th class="text-center" scope="col">Dirección</th>
                                        <th class="text-center" scope="col">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                      $index = 0;
                                        foreach ($personal as $row) {
                                            if($row->estadoEmpleado != 2){
                                                $index++;

                                    ?>
                                                <tr>
                                                    <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                    <td class="text-center"><?php echo $row->nombreEmpleado; ?></td>
                                                    <td class="text-center"><?php echo $row->cargoEmpleado; ?></td>
                                                    <td class="text-center"><?php echo $row->salarioEmpleado; ?></td>
                                                    <td class="text-center"><?php echo $row->telefonoEmpleado; ?></td>
                                                    <td class="text-center"><?php echo $row->duiEmpleado; ?></td>
                                                    <td class="text-center"><?php echo $row->direccionEmpleado; ?></td>
                                                    <td class="text-center">
                                                        <input type="hidden" value="<?php echo $row->idEmpleado; ?>" class="idEmpleado">
                                                        <input type="hidden" value="<?php echo $row->nombreEmpleado; ?>" class="nombreEmpleado">
                                                        <input type="hidden" value="<?php echo $row->telefonoEmpleado; ?>" class="telefonoEmpleado">
                                                        <input type="hidden" value="<?php echo $row->duiEmpleado; ?>" class="duiEmpleado">
                                                        <input type="hidden" value="<?php echo $row->correoEmpleado; ?>" class="correoEmpleado">
                                                        <input type="hidden" value="<?php echo $row->nacimientoEmpleado; ?>" class="nacimientoEmpleado">
                                                        <input type="hidden" value="<?php echo $row->ingresoEmpleado; ?>" class="ingresoEmpleado">
                                                        <input type="hidden" value="<?php echo $row->salarioEmpleado; ?>" class="salarioEmpleado">
                                                        <input type="hidden" value="<?php echo $row->areaEmpleado; ?>" class="areaEmpleado">
                                                        <input type="hidden" value="<?php echo $row->direccionEmpleado; ?>" class="direccionEmpleado">
                                                        <input type="hidden" value="<?php echo $row->estadoEmpleado; ?>" class="estadoEmpleado">
                                                        <input type="hidden" value="<?php echo $row->cargoEmpleado; ?>" class="cargoEmpleado">
                                                        <input type="hidden" value="<?php echo $row->precioHoraExtra; ?>" class="precioHoraExtra">
                                                        <input type="hidden" value="<?php echo $row->bonoEmpleado; ?>" class="bonoEmpleado">
                                                        <?php
                                                        // echo "<a href='#verEmpleado' class='verEmpleado' data-toggle='modal'><i class='far fa-eye ms-text-primary'></i></a>";
                                                        echo "<a href='".base_url()."Planilla/detalle_empleado/".$row->idEmpleado."/' class='verEmpleado'><i class='far fa-eye ms-text-primary'></i></a>";
                                                        echo "<a href='#actualizarEmpleado' class='editarEmpleado' data-toggle='modal'><i class='fas fa-edit ms-text-success'></i></a>";
                                                        echo "<a href='#eliminarEmpleado' class='eliminarEmpleado' data-toggle='modal'><i class='far fa-trash-alt ms-text-danger'></i></a>";
                                                        ?>
                                                    </td>
                                                </tr>

                                    <?php }} ?>
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
                    <h4 class="modal-title text-white text-center"></i> Datos del empleado</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                            aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body" id="detalleEmpleado"></div>
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
                    <h4 class="modal-title text-white"></i> Datos del empleado</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body p-0 text-left">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <div class="ms-panel-body">
                                <form class="needs-validation" method="post" action="<?php echo base_url()?>Planilla/actualizar_empleado" novalidate>
                                    
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
                                            <input type="text" class="form-control" data-mask="9999999-9" id="duiEmpleado" name="duiEmpleado" required>
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
                                            <input type="text" class="form-control" id="telefonoEmpleado" name="telefonoEmpleado" required>
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
                                        <input type="hidden" class="form-control" id="idEmpleado" name="idEmpleado" required>
                                        <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Actualizar </button>
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
<!-- Fin Modal actualizar datos del Medicamento-->


<!-- Modal para eliminar datos del Medicamento-->
    <div class="modal fade" id="eliminarEmpleado" tabindex="-1" role="dialog" aria-labelledby="modal-5">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-5">
                <form action="<?php echo base_url() ?>Planilla/eliminar_empleado" method="post">
                    <div class="modal-header bg-danger">
                        <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <p class="h5">¿Estas seguro de eliminar los datos de este empleado?</p>
                        <input type="hidden" id="empleadoEliminar" name="idEmpleado" />
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-trash"></i>  Eliminar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<!-- Fin Modal eliminar  datos del Medicamento-->

<script>

    $(document).on("click", '.verEmpleado', function(e) {
        // e.preventDefault();
        var salario = $(this).closest("tr").find('.salarioBEmpleado').val();

        var idEmpleado = $(this).closest("tr").find('.idEmpleado').val();
        var nombreEmpleado = $(this).closest("tr").find('.nombreEmpleado').val();
        var telefonoEmpleado = $(this).closest("tr").find('.telefonoEmpleado').val();
        var duiEmpleado = $(this).closest("tr").find('.duiEmpleado').val();
        var correoEmpleado = $(this).closest("tr").find('.correoEmpleado').val();
        var nacimientoEmpleado = $(this).closest("tr").find('.nacimientoEmpleado').val();
        var ingresoEmpleado = $(this).closest("tr").find('.ingresoEmpleado').val();
        var salarioEmpleado = $(this).closest("tr").find('.salarioEmpleado').val();
        var areaEmpleado = $(this).closest("tr").find('.areaEmpleado').val();
        var direccionEmpleado = $(this).closest("tr").find('.direccionEmpleado').val();
        var estadoEmpleado = $(this).closest("tr").find('.estadoEmpleado').val();
        var cargoEmpleado = $(this).closest("tr").find('.cargoEmpleado').val();
        var precioHoraExtra = $(this).closest("tr").find('.precioHoraExtra').val();
        var bonoEmpleado = $(this).closest("tr").find('.bonoEmpleado').val();
        if(estadoEmpleado == 1){
            estadoEmpleado = "Activo";
        }else{
            estadoEmpleado = "Inactivo";
        }
           
        var html = '';
        html += '<table class="table table-borderless">';
        html += '<tr>';
        html += '<td><strong>Nombre</strong></td>';
        html += '<td>' + nombreEmpleado+ '</td>';
        html += '<td><strong>Cargo</strong></td>';
        html += '<td>' + cargoEmpleado + '</td>';
        html += '</tr>';

        html += '<tr>';
        html += '<td><strong>Salario</strong></td>';
        html += '<td>$' + salarioEmpleado + '</td>';
        html += '<td><strong>Bono</strong></td>';
        html += '<td>$' + bonoEmpleado + '</td>';
        html += '</tr>';

        html += '<tr>';
        html += '<td><strong>Precio hora extra</strong></td>';
        html += '<td>$' + precioHoraExtra + '</td>';
        html += '<td><strong>Teléfono</strong></td>';
        html += '<td>' + telefonoEmpleado + '</td>';
        html += '</tr>';

        html += '<tr>';
        html += '<td><strong>DUI</strong></td>';
        html += '<td>' + duiEmpleado + '</td>';
        html += '<td><strong>Estado empleado</strong></td>';
        html += '<td>' + estadoEmpleado + '</td>';
        html += '</tr>';

        html += '<tr>';
        html += '<td><strong>Fecha de nacimiento</strong></td>';
        html += '<td>' + nacimientoEmpleado + '</td>';
        html += '<td><strong>Ingreso al hospital</strong></td>';
        html += '<td>' + ingresoEmpleado + '</td>';
        html += '</tr>';


        html += '<tr>';
        html += '<td><strong>Dirección</strong></td>';
        html += '<td>' + direccionEmpleado + '</td>';
        html += '<td><strong></strong></td>';
        html += '<td><strong></strong></td>';
        html += '</tr>';
        html += '</table>';

        $('#detalleEmpleado').html(html);

    });

    $(document).on("click", '.editarEmpleado', function(e) {
        // e.preventDefault();
        var salario = $(this).closest("tr").find('.salarioBEmpleado').val();

        var idEmpleado = $(this).closest("tr").find('.idEmpleado').val();
        var nombreEmpleado = $(this).closest("tr").find('.nombreEmpleado').val();
        var telefonoEmpleado = $(this).closest("tr").find('.telefonoEmpleado').val();
        var duiEmpleado = $(this).closest("tr").find('.duiEmpleado').val();
        var correoEmpleado = $(this).closest("tr").find('.correoEmpleado').val();
        var nacimientoEmpleado = $(this).closest("tr").find('.nacimientoEmpleado').val();
        var ingresoEmpleado = $(this).closest("tr").find('.ingresoEmpleado').val();
        var salarioEmpleado = $(this).closest("tr").find('.salarioEmpleado').val();
        var areaEmpleado = $(this).closest("tr").find('.areaEmpleado').val();
        var direccionEmpleado = $(this).closest("tr").find('.direccionEmpleado').val();
        var estadoEmpleado = $(this).closest("tr").find('.estadoEmpleado').val();
        var cargoEmpleado = $(this).closest("tr").find('.cargoEmpleado').val();
        var precioHoraExtra = $(this).closest("tr").find('.precioHoraExtra').val();
        var bonoEmpleado = $(this).closest("tr").find('.bonoEmpleado').val();

        $("#idEmpleado").val(idEmpleado);
        $("#nombreEmpleado").val(nombreEmpleado);
        $("#areaEmpleado").val(areaEmpleado);
        $("#salarioEmpleado").val(salarioEmpleado);
        $("#bonoEmpleado").val(bonoEmpleado);
        $("#cargoEmpleado").val(cargoEmpleado);
        $("#precioHEEmpleado").val(precioHoraExtra);
        $("#duiEmpleado").val(duiEmpleado);
        $("#correoEmpleado").val(correoEmpleado);
        $("#telefonoEmpleado").val(telefonoEmpleado);
        $("#estadoEmpleado").val(estadoEmpleado);
        $("#nacimientoEmpleado").val(nacimientoEmpleado);
        $("#ingresoEmpleado").val(ingresoEmpleado);
        $("#direccionEmpleado").val(direccionEmpleado);

    });

    $(document).on("click", '.eliminarEmpleado', function(e) {
        // e.preventDefault();
        $("#empleadoEliminar").val($(this).closest("tr").find('.idEmpleado').val());
    });

</script>