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
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Planilla
                        </a> </li>
                    <li class="breadcrumb-item"><a href="#"> Detalle planilla </a></li>
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
                            <table id="tablag" class="table table-bordered thead-primary w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">#</th>
                                        <th class="text-center" scope="col">Vacaciones</th>
                                        <th class="text-center" scope="col">Empleado</th>
                                        <th class="text-center" scope="col">Cargo</th>
                                        <th class="text-center" scope="col">Bono</th>
                                        <th class="text-center" scope="col">Sueldo Base</th>
                                        <th class="text-center" scope="col">Horas Extras</th>
                                        <th class="text-center" scope="col">Total Extras</th>
                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                        <th class="text-center" scope="col">ISSS</th>
                                        <th class="text-center" scope="col">AFP</th>
                                        <th class="text-center" scope="col">Renta</th>
                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                        <th class="text-center" scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        $index = 0;
                                        $salario = 0;
                                        $isss = 0;
                                        $afp = 0;
                                        $renta = 0;
                                        $totalRetenciones = 0;
                                        $renta = 0;
                                        foreach ($personal as $row) {
                                          $index++;
                                          $base = $row->salarioEmpleado;
                                          $salario = $row->salarioEmpleado/2;
                                          // Calculo del ISSS
                                          switch ($base) {
                                            case( ($base >= 1) && ($base <= 1000)):
                                                $isss = $salario * 0.03;
                                                break;
                                            case ($base > 1000 ):
                                                $isss = 30/2;
                                                break;
                                                
                                            default:
                                                $isss = $base * 0.03;
                                                break;
                                          }


                                          $afp = $salario * 0.0725;
                                          $totalRetenciones = $isss + $afp;
                                          $salario = $salario - $totalRetenciones;

                                          // Calculo de la renta
                                          switch ($salario) {
                                            case ( ($salario >= 1) && ($salario <= 236)):
                                                $renta = 0;
                                                break;
                                            case ( ($salario > 236) && ($salario <= 447.62)):
                                                $renta = 8.83 + (($salario -236 )* 0.10);
                                                break;
                                            case ( ($salario > 447.62) && ($salario <= 1019.05)):
                                                $renta = 30 + (($salario - 447.62 ) * 0.20);
                                                break;
                                            
                                            case ( ($salario > 1019.05) && ($salario <= 1000000000)):
                                                $renta = 144.28 + (($salario - 1019.05 ) * 0.30);
                                                break;
                                            
                                            default:
                                                # code...
                                                break;
                                          }

                                          $salario = $salario - $renta ;
                                          if(($row->bonoEmpleado > 0)){
                                            $salario += ($row->bonoEmpleado/2);
                                          }
                                    ?>
                                    <tr>
                                        <td class="text-center" scope="row"><?php echo $index; ?></td>
                                        <td class="text-center" scope="row">
                                            <label class="ms-switch">
                                                <input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">
                                                <span class="ms-switch-slider ms-switch-secondary round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center"><?php echo $row->nombreEmpleado; ?></td>
                                        <td class="text-center"><?php echo $row->cargoEmpleado; ?></td>
                                        <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                        <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                        <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                        <td class="text-center"> 
                                            <label class="lblTExtras">$0</label> 
                                            <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                            <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                        </td>  
                                        <!-- <td class="text-center">$0</td> -->
                                        <td class="text-center"><?php echo number_format($isss, 2); ?><input type="hidden" value="<?php echo $isss; ?>" class="isssEmpleado"></td> <!-- ISSS -->
                                        <td class="text-center"><?php echo number_format($afp, 2); ?><input type="hidden" value="<?php echo $afp; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                        <td class="text-center"><?php echo number_format($renta, 2); ?><input type="hidden" value="<?php echo $renta; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                        <td class="text-center alert-success liquidoPagar">
                                            <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($salario, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                            <input type="hidden" value="<?php echo round($salario, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                        </td>
                                        <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                        <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
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

<!-- Modales -->
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
<!-- Modales -->


<script src="<?php echo base_url(); ?>public/js/jquery.tabledit.js"></script> <!-- Script para tabla editable -->
<script>
    $(document).ready(function() {
        
        $('#tablag').Tabledit({
            url: '../../editar_insumo',
            columns: {
                identifier: [0, 'fila'],
                editable: [[6, 'horasExtras']]
            },
            restoreButton:false,
        });

        /* var fechaCuenta = $("#fechaDescargos").val();
        var estadoCuentaActual = $("#estadoCuentaActual").val();
        if(estadoCuentaActual == 1){
            $('#tablag').Tabledit({
                url: '../../editar_insumo',
                columns: {
                    identifier: [0, 'fila'],
                    editable: [[3, 'cantidad'], [4, 'idInsumo'], [5, 'cuentaInsumo'], [6, 'cantidadActual']]
                },
                restoreButton:false,
            });
        } */
        

        $(document).on("click", '.tabledit-save-button', function(event) {
            event.preventDefault();
            var totalHorasExtras = 0;
            var area = $(this).closest("tr").find('.areaEmpleado').val(); // Area del empleado
            var liquido = parseFloat($(this).closest("tr").find('.txtLiquidoEmpleado').val());  // Salario liquido
            var liquidoBase = parseFloat($(this).closest("tr").find('.salarioLBEmpleado').val());  // Salario liquido base
            var precioHoraExtra = parseFloat($(this).closest("tr").find('.precioHoraExtra').val()); // Precio de cada hora extra
            var oldHorasExtras = $(this).closest("tr").find('.numeroHorasExtras').val(); // Input de total de horas extras en numero anterior

            var horasExtras = $(this).closest("tr").find('.horasExtras').val(); // Numero de horas extras
            var salario = $(this).closest("tr").find('.salarioBEmpleado').val(); // Salario del empleado sin descuentos
            
            if(oldHorasExtras > 0){
                liquido -= (oldHorasExtras * precioHoraExtra);
            }

            // Evaluando area
            switch (area) {
                case '4':
                    totalHorasExtras = horasExtras * precioHoraExtra;
                    break;
                case '9':
                    totalHorasExtras = horasExtras * precioHoraExtra;
                    break;
                    
                    default:
                        break;
                    }
                    
            console.log(liquido);

            $(this).closest("tr").addClass("alert-danger");
            $(this).closest("tr").find('.lblTExtras').html(totalHorasExtras); // Label de total de horas extras
            $(this).closest("tr").find('.extrasTEmpleado').val(totalHorasExtras); // Input de total de horas extras en dinero
            $(this).closest("tr").find('.numeroHorasExtras').val(horasExtras); // Input de total de horas extras en numero
            $(this).closest("tr").find('.lblLiquidoEmpleado').html((liquido + totalHorasExtras).toFixed(2));
            $(this).closest("tr").find('.txtLiquidoEmpleado').val((liquido + totalHorasExtras).toFixed(2));


        });

        // $(document).on("click", '.tabledit-edit-button', function(event) {
        //     event.preventDefault();
        //     $(this).closest("tr").find('.cantidad').focus();
        // });

        $(document).on("keyup", '.tabledit-input', function(e) {
            // e.preventDefault();
            if(e.which == 13) {
                return false;
            }
        });

        $(document).on("click", '.vacaciones', function(e) {
            // e.preventDefault();
            // var valor = $('input:checkbox[name=vacaciones]:checked').val();
            var valor = $(this).closest("tr").find('input:checkbox[name=vacaciones]:checked').val(); // Para saber si hay vacaciones
            var salarioBase = parseFloat($(this).closest("tr").find('.salarioBEmpleado').val()); // Salario total / 2
            var salarioLiquido = parseFloat($(this).closest("tr").find('.txtLiquidoEmpleado').val()); // Salario liquido
            var vacaciones = 0;
            if (valor == "vacaciones") {
                vacaciones = salarioBase * 0.3; // Obteniendo el 30% de el salario de 15 dias
            } else {
                vacaciones = -1 * (salarioBase * 0.3);
            }
            salarioLiquido += vacaciones; // Pendiente la evaluacion de de las sumas de vacaciones y salario liquido
            $(this).closest("tr").find('.salarioLEmpleado').val(salarioLiquido.toFixed(2)); // Asignando salario liquido
            $(this).closest("tr").find('.lblLiquidoEmpleado').html(salarioLiquido.toFixed(2));
            $(this).closest("tr").find('.txtLiquidoEmpleado').val(salarioLiquido.toFixed(2));
            console.log(salarioLiquido);
        });


        /* $(document).ready(function() {

            $("#newMed").click(function() {
                var valor = $('input:checkbox[name=newMed]:checked').val();
                if (valor == "newMed") {
                    alert("Activado")
                } else {
                    alert("Desactivado")
                }
            });
        }); */


    });
</script>