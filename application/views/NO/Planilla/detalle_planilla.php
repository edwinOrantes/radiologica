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
<style>
    .resetear{
        cursor: pointer;
    }
</style>
<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap">
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Planilla </a> </li>
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
                                <a href="<?php echo base_url()?>Planilla/historial_planillas/" class="btn btn-success btn-sm"><i class="fa fa-arrow-left"></i> Volver </a>
                                <?php
                                    if($estado->estadoPlanilla == 1){
                                        echo ' <a href="#descuentosActivos" id="" class="btn btn-success btn-sm" data-toggle="modal"><i class="fas fa-file-invoice-dollar"></i> Descuentos </a> ';
                                        echo ' <a href="#cambiarEstado" id="txtCambiarEstado" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-times"></i> Cerrar </a>';
                                    }else{
                                        echo '<a href="'.base_url().'Planilla/planilla_excel/'.$estado->idPlanilla.'" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Ver excel</a> ';
                                        echo '<a href="'.base_url().'Planilla/planilla_comprobante/'.$estado->idPlanilla.'" class="btn btn-primary btn-sm" target="blank"><i class="fa fa-file-pdf"></i> Comprobantes </a>';
                                    }
                                ?>
                                
                                
                            </div>
                        </div>
                    </div>
                    <div class="ms-panel-body clearfix">

                        <div class="">
                            <ul class="nav nav-tabs d-flex nav-justified mb-4" role="tablist">
                            <li role="presentation"><a href="#tab1" aria-controls="tab1" class="active" role="tab" data-toggle="tab">Administración</a></li>
                            <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">Cajeras</a></li>
                            <li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">ISBM</a></li>
                            <li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">Enfermeria</a></li>
                            <li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">Hemodiálisis</a></li>
                            <li role="presentation"><a href="#tab6" aria-controls="tab6" role="tab" data-toggle="tab">Laboratorio Clínico </a></li>
                            <li role="presentation"><a href="#tab7" aria-controls="tab7" role="tab" data-toggle="tab">Rayos X</a></li>
                            <li role="presentation"><a href="#tab8" aria-controls="tab8" role="tab" data-toggle="tab">Botiquín</a></li>
                            <li role="presentation"><a href="#tab9" aria-controls="tab9" role="tab" data-toggle="tab">Limpieza </a></li>
                            <li role="presentation"><a href="#tab10" aria-controls="tab10" role="tab" data-toggle="tab">Mantenimiento</a></li>
                            <li role="presentation"><a href="#tab11" aria-controls="tab11" role="tab" data-toggle="tab">Vigilante</a></li>
                            <li role="presentation"><a href="#tab12" aria-controls="tab11" role="tab" data-toggle="tab">Cocina</a></li>
                            </ul>
                            <div class="tab-content">

                            <div role="tabpanel" class="tab-pane active show fade in" id="tab1">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col" style="display: none">area</th>
                                                        <th class="text-center" scope="col" style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 1){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row">  <?php echo $index; ?> </td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab2">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 2){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                            <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab3">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 3){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab4">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 4){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones; // Probando agregar vacaciones
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>

                                                                <input type="hidden" value="<?php echo $row->totalVacaciones; ?>" class="vacacionesPlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->

                                                            <td class="text-center">
                                                                <label class="lblIsss"><?php echo number_format($row->isssDetallePlanilla, 2); ?></label> 
                                                                <input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> 
                                                            </td> <!-- ISSS -->
                                                            <td class="text-center">
                                                                <label class="lblAfp"><?php echo number_format($row->afpDetallePlanilla, 2); ?></label> 
                                                                <input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado">
                                                            </td> <!-- AFP -->
                                                            <td class="text-center">
                                                                <label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label>
                                                                <input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado">
                                                            </td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>

                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab5">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 5){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab6">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 6){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab7">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 7){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab8">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 8){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab9">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 9){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab10">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 10){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab11">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 11){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center"><?php echo number_format($row->isssDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> </td> <!-- ISSS -->
                                                            <td class="text-center"><?php echo number_format($row->afpDetallePlanilla, 2); ?><input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado"></td> <!-- AFP -->
                                                            <td class="text-center"><label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label><input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado"></td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab12">
                                <!-- Inicio -->
                                    <div class="row">
                                        <div class="table-responsive mt-3">
                                            <table id="tablag" class="table table-bordered thead-primary w-100 tablag">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Vacaciones</th>
                                                        <th class="text-center" scope="col">Empleado</th>
                                                        <th class="text-center" scope="col">Cargo</th>
                                                        <th class="text-center" scope="col">Bono</th>
                                                        <th class="text-center" scope="col">Otros</th>
                                                        <th class="text-center" scope="col">Sueldo Base</th>
                                                        <th class="text-center" scope="col">Horas Extras</th>
                                                        <th class="text-center" scope="col">Total Extras</th>
                                                        <th class="text-center" scope="col">Horas nocturnas</th>
                                                        <th class="text-center" scope="col">Total nocturnas</th>
                                                        <!-- <th class="text-center" scope="col">Subtotal</th> -->
                                                        <th class="text-center" scope="col">ISSS</th>
                                                        <th class="text-center" scope="col">AFP</th>
                                                        <th class="text-center" scope="col">Renta</th>
                                                        <th class="text-center" scope="col">Liquido a pagar</th>
                                                        <th class="text-center" scope="col"  style="display: none">area</th>
                                                        <th class="text-center" scope="col"  style="display: none">extra</th>
                                                        <th class="text-center" scope="col" style="display: none">nocturno</th>
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
                                                        $clase = "";
                                                        foreach ($personal as $row) {
                                                            if($row->areaEmpleado == 14){
                                                            $index++;
                                                            $base = $row->salarioEmpleado;
                                                            $salario = $row->salarioEmpleado/2;
                                                            $totalRetenciones = $row->isssDetallePlanilla + $row->afpDetallePlanilla + $row->rentaDetallePlanilla;
                                                            $salario = $salario - $totalRetenciones;
                                                            $salario = $salario + $row->bonoEmpleado/2;
                                                            if($row->editadoDetallePlanilla == 1){
                                                                $clase = "alert-danger";
                                                            }else{
                                                                $clase = "";
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                                            <td class="text-center" scope="row">
                                                                <label class="ms-switch">
                                                                    <?php
                                                                        if($row->salarioEmpleado > 0 && $estado->estadoPlanilla == 1){
                                                                            if($row->totalVacaciones > 0){
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones" checked="">';
                                                                            }else{
                                                                                echo '<input type="checkbox" class="vacaciones" value="vacaciones" name="vacaciones">';
                                                                            }
                                                                            echo '<span class="ms-switch-slider ms-switch-secondary round"></span>';
                                                                        }else{
                                                                            echo "$".$row->totalVacaciones;
                                                                        }
                                                                    ?>
                                                                </label>
                                                            </td>
                                                            <td class="text-center"><?php echo $row->nombreEmpleado; ?>
                                                                <?php
                                                                    if($estado->estadoPlanilla == 1){
                                                                        echo '<a href="#resetearDatos" data-toggle="modal"><i class="fa fa-undo fa-1x text-danger resetear"></i></a>';
                                                                    }
                                                                ?>
                                                
                                                                <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="idDetallePlanilla">
                                                            </td>
                                                            <td class="text-center"><?php echo $row->cargoEmpleado; ?> <input type="hidden" value="<?php echo $row->otrosDetallePlanilla; ?>" class="oldOtrosDetallePlanilla"> </td>
                                                            <td class="text-center"><?php echo $row->bonoEmpleado/2; ?> <input type="hidden" value="<?php echo $row->bonoEmpleado/2; ?>" class="bonoEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->otrosDetallePlanilla; ?> <!-- <input type="text" value="<?php echo $row->otrosDetallePlanilla; ?>" class="otrosDetallePlanilla">  --></td>
                                                            <td class="text-center"><?php echo number_format($row->salarioEmpleado/2, 2); ?> <input type="hidden" value="<?php echo $row->salarioEmpleado/2; ?>" class="salarioBEmpleado"> <input type="hidden" value="<?php echo round($salario, 2); ?>" class="salarioLBEmpleado"> </td>
                                                            <td class="text-center"><?php echo $row->horasExtras; ?></td> <!-- Numero de horas extras -->
                                                            <td class="text-center"> 
                                                                <label class="lblTExtras"><?php echo $row->totalHorasExtras; ?></label> 
                                                                <input type="hidden" value="0" class="extrasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasExtras">  <!-- Total de horas extras numero-->
                                                            </td>  
                                                            <td class="text-center"><?php echo $row->horasNocturnas; ?></td>
                                                            <td class="text-center">
                                                                <label class="lblTNocturnas"><?php echo $row->totalHorasNocturnas; ?></label>
                                                                <input type="hidden" value="0" class="nocturnasTEmpleado"> <!-- Total de horas extras dinero-->
                                                                <input type="hidden" value="0" class="numeroHorasNocturnas">  <!-- Total de horas extras numero-->
                                                            </td>
                                                            <!-- <td class="text-center">$0</td> -->
                                                            <td class="text-center">
                                                                <label class="lblIsss"><?php echo number_format($row->isssDetallePlanilla, 2); ?></label> 
                                                                <input type="hidden" value="<?php echo $row->isssDetallePlanilla; ?>" class="isssEmpleado"> 
                                                            </td> <!-- ISSS -->
                                                            <td class="text-center">
                                                                <label class="lblAfp"><?php echo number_format($row->afpDetallePlanilla, 2); ?></label> 
                                                                <input type="hidden" value="<?php echo $row->afpDetallePlanilla; ?>" class="afpTEmpleado">
                                                            </td> <!-- AFP -->
                                                            <td class="text-center">
                                                                <label class="lblRentaEmpleado"><?php echo number_format($row->rentaDetallePlanilla, 2); ?></label>
                                                                <input type="hidden" value="<?php echo $row->rentaDetallePlanilla; ?>" class="rentaEmpleado">
                                                            </td> <!-- Renta -->
                                                            <td class="text-center alert-success liquidoPagar">
                                                                <label for="" class="lblLiquidoEmpleado" ><?php echo number_format($row->liquidoDetallePlanilla, 2); ?></label> <!-- Etiqueta de salario liquido -->
                                                                <input type="hidden" value="<?php echo round($row->liquidoDetallePlanilla, 2); ?>" class="txtLiquidoEmpleado"> <!-- Txt de salario liquido -->
                                                            </td>
                                                            <td class="text-center" style="display: none"><input type="text" class="areaEmpleado" value="<?php echo $row->areaEmpleado; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraExtra" value="<?php echo $row->precioHoraExtra; ?>"></td>
                                                            <td class="text-center" style="display: none"><input type="text" class="precioHoraNocturna" value="<?php echo $row->precioHoraNocturna; ?>"></td>
                                                            <?php
                                                                if($estado->estadoPlanilla == 0){
                                                                    echo '<th class="text-center" scope="col"><a href="'.base_url().'Planilla/comprobante_x_empleado/'.$row->idDetallePlanilla.'" target="blank" class="btn-sm text-danger"><i class="fa fa-file-pdf"></i></a></th>';
                                                                }
                                                            ?>
                                                        </tr>

                                                    <?php }}	?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <!-- Fin -->
                            </div>

                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="<?php echo $estado->estadoPlanilla; ?>" id="txtEstadoPlanilla">
    </div>
<!-- Body Content Wrapper -->

<!-- Modales -->
    <!-- Modal para cerrar planilla-->
        <div class="modal fade" id="cambiarEstado" tabindex="-1" role="dialog" aria-labelledby="modal-5">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pb-5">
                    <form action="<?php echo base_url() ?>Planilla/cerrar_planilla" method="post">
                        <div class="modal-header bg-danger">
                            <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                    class="text-white">&times;</span></button>
                        </div>

                        <div class="modal-body text-center">
                            <p class="h5">¿Estas seguro de cerrar la planilla ?</p>
                            <input type="hidden" id="estadoAsignar" value="0" name="estadoAsignar" />
                            <input type="hidden" id="planillaCerrar" value="<?php echo $estado->idPlanilla;?>" name="planillaCerrar" />
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-save"></i> Cerrar </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    <!-- Fin Modal cerrar planilla-->

    <!-- Modal para resetear valores-->
        <div class="modal fade" id="resetearDatos" tabindex="-1" role="dialog" aria-labelledby="modal-5">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content pb-5">
                    <form action="<?php echo base_url() ?>Planilla/resetar_valores" method="post">
                        <div class="modal-header bg-danger">
                            <h3 class="modal-title has-icon text-white"><i class="flaticon-alert-1"></i> Advertencia</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                    class="text-white">&times;</span></button>
                        </div>

                        <div class="modal-body text-center">
                            <p class="h5">¿Estas seguro de resetear los valores ?</p>
                            <input type="hidden" id="filaResetear" value="0" name="filaResetear" />
                            <input type="hidden" id="planillaCerrar" value="<?php echo $estado->idPlanilla;?>" name="planillaCerrar" />
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger shadow-none"><i class="fa fa-save"></i> Resetear </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    <!-- Fin Modal resetear valores-->

    <!-- Modal agregar descuento-->
        <div class="modal fade" id="descuentosActivos" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ms-modal-dialog-width">
                <div class="modal-content ms-modal-content-width">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white text-center"></i> Datos del activos</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                                aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <?php
                                    if(sizeof($descuentos) > 0){
                                ?>
                                <form class="needs-validation" id="frmGastos" method="post" action="<?php echo base_url(); ?>Planilla/procesar_descuentos/" novalidate>
                                        
                                    <div class="row mt-3">
                                        
                                        <table id="" class="table table-bordered thead-primary w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">#</th>
                                                    <th class="text-center" scope="col">Empleado</th>
                                                    <th class="text-center" scope="col">Total</th>
                                                    <th class="text-center" scope="col">Abonado</th>
                                                    <th class="text-center" scope="col">Descuento</th>
                                                    <th class="text-center" scope="col">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 0;
                                                    foreach ($descuentos as $row) {
                                                        $index++;
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $index; ?></td>
                                                        <td class="text-center"><?php echo $row->empleado; ?></td>
                                                        <td class="text-center"><?php echo "$".number_format($row->montoEmDes, 2); ?></td>
                                                        <td class="text-center"><?php echo "$".number_format($row->totalAbonado, 2); ?></td>
                                                        <td class="text-center">
                                                            <input type="text" value="<?php echo $row->cuotaDescuento; ?>" class="form-control cuotaDescuento">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="hidden" value="<?php echo $row->idEmDes; ?>" class="form-control idEmpleadoDescuento">
                                                            <input type="hidden" value="<?php echo $row->idDetallePlanilla; ?>" class="form-control filaDetalle">
                                                            <input type="hidden" value="<?php echo $row->idDescuento; ?>" class="form-control idDescuento">
                                                            <input type="hidden" value="<?php echo $row->idPlanilla; ?>" class="form-control idPlanilla">
                                                            <?php
                                                                if($row->montoEmDes == $row->totalAbonado){
                                                                    echo '<a class="saldarDescuento" title="La deuda fue saldada" href="#" ><i class="fas fa-check ms-text-success"></i></a>';
                                                                }else{
                                                                    echo '<a class="guardarDescuento" title="Guardar descuento" href="#" ><i class="fas fa-plus ms-text-success"></i></a>';
                                                                    echo '<a class="eliminarDescuento" title="Eliminar descuento" href="#" ><i class="fas fa-trash-alt ms-text-danger"></i></a>';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div>

                                    <!-- <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary has-icon" id="guardarDescuento"><i class="fa fa-save"></i> Guardar </button>
                                    </div> -->

                                </form>
                                <?php
                                    }else{
                                        echo '<div class="alert alert-danger">
											<h6 class="text-center"><strong>No hay datos que mostrar.</strong></h6>
										</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <!-- Fin Modal agregar descuento-->

    <!-- Modal para agregar liquido -->
        <div class="modal fade" id="asignarLiquido" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header  ms-modal-header-radius-0">
                        <h4 class="modal-title text-white text-center"></i> Datos del empleado</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span
                                aria-hidden="true" class="text-white">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="col-xl-12 col-md-12">
                            <div class="ms-panel ms-panel-bshadow-none">
                                <form class="needs-validation" id="frmGastos" method="post" action="<?php echo base_url(); ?>Planilla/asignar_liquido/" novalidate>
                                        
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for=""><strong>Salario liquido</strong></label>
                                            <input type="text" class="form-control" id="salarioLiquidoA" name="salarioLiquidoA" required>
                                            <div class="invalid-tooltip">
                                                Debes agregar el salario.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-center mt-3">
                                        <input type="hidden" id="idFilaA" name="idFilaA" required>
                                        <input type="hidden" value="<?php echo $estado->idPlanilla; ?>" id="idPlanillaA" name="idPlanillaA">
                                        <button type="submit" class="btn btn-primary has-icon"><i class="fa fa-save"></i> Guardar </button>
                                    </div>

                                </form>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <!-- Fin Modal para agregar liquido -->

<!-- Modales -->


<script src="<?php echo base_url(); ?>public/js/jquery.tabledit.js"></script> <!-- Script para tabla editable -->
<script>
    $(document).ready(function() {
        var estadoActual = $("#txtEstadoPlanilla").val();
        if(estadoActual == 1){
            $('.tablag').Tabledit({
                // url: '',
                url: '../../editar_insumo',
                columns: {
                    identifier: [0, 'fila'],
                    editable: [[5, 'otrosExtras'], [7, 'horasExtras'], [9, 'horasNocturnas']]
                },
                restoreButton:false,
                deleteButton:false,
                onDraw: function() {

                    // Agregar botón personalizado
                        $('.tabledit-edit-button').each(function() {
                            // Verifica si ya se ha agregado el botón para evitar duplicados
                            if ($(this).next('.tabledit-custom-button').length === 0) {
                                $(this).after('<a href="#asignarLiquido" data-toggle="modal" class="tabledit-custom-button btn btn-sm btn-light btn-liquido"><i class="fa fa-money-bill fa-1x"></i></a>');
                            }
                        });

                    // Aplica la restricción max y el tipo number a las celdas de horasExtras
                    $('input[name="horasExtras"]').attr({
                        'max': 10,
                        'min': 0,
                        // 'type': 'number'
                    });
                }
            });
        }

        /* $(document).on("click", '.tabledit-save-button', function(event) {
            event.preventDefault();
            var totalHorasExtras = 0;
            var area = $(this).closest("tr").find('.areaEmpleado').val(); // Area del empleado
            var liquido = parseFloat($(this).closest("tr").find('.txtLiquidoEmpleado').val());  // Salario liquido
            var liquidoBase = parseFloat($(this).closest("tr").find('.salarioLBEmpleado').val());  // Salario liquido base
            var precioHoraExtra = parseFloat($(this).closest("tr").find('.precioHoraExtra').val()); // Precio de cada hora extra
            var oldHorasExtras = $(this).closest("tr").find('.numeroHorasExtras').val(); // Input de total de horas extras en numero anterior
            
            var horasExtras = $(this).closest("tr").find('.horasExtras').val(); // Numero de horas extras
            
            



            var salario = $(this).closest("tr").find('.salarioBEmpleado').val(); // Salario del empleado sin descuentos

            var otrosExtras  = parseFloat($(this).closest("tr").find('.otrosExtras ').val()); // Otros bonos extras
            var oldOtrosExtras  = parseFloat($(this).closest("tr").find('.oldOtrosDetallePlanilla ').val()); // Otros bonos extras

            var renta = $(this).closest("tr").find('.rentaEmpleado').val()

            if(otrosExtras == oldOtrosExtras){
                liquido = liquido;
            }else{
                if(otrosExtras > oldOtrosExtras && oldOtrosExtras == 0){
                    liquido += otrosExtras;
                }else{
                    if(otrosExtras > oldOtrosExtras && oldOtrosExtras > 0){
                        liquido += (otrosExtras - oldOtrosExtras);
                    }else{
                        if(oldOtrosExtras > otrosExtras && otrosExtras == 0){
                            liquido -= oldOtrosExtras;
                        }else{
                            liquido -= (oldOtrosExtras - otrosExtras);
                        }
                    }
                }

            }
            
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
                    totalHorasExtras = horasExtras * precioHoraExtra;
                    break;
            }

            // console.log(oldOtrosExtras);
            
            $(this).closest("tr").addClass("alert-danger");
            $(this).closest("tr").find('.lblTExtras').html(totalHorasExtras.toFixed(2)); // Label de total de horas extras
            $(this).closest("tr").find('.extrasTEmpleado').val(totalHorasExtras.toFixed(2)); // Input de total de horas extras en dinero
            $(this).closest("tr").find('.numeroHorasExtras').val(horasExtras); // Input de total de horas extras en numero
            $(this).closest("tr").find('.lblLiquidoEmpleado').html((liquido + totalHorasExtras).toFixed(2) );
            $(this).closest("tr").find('.txtLiquidoEmpleado').val((liquido + totalHorasExtras).toFixed(2) );
            $(this).closest("tr").find('.oldOtrosDetallePlanilla ').val(otrosExtras); // Agregando respaldo de otros extras
            
            var datos = {
                otros : otrosExtras, // Fila que se esta editando.
                horas : horasExtras, // Fila que se esta editando.
                renta : renta, // Total de renta a cancelar
                liquido : (liquido + totalHorasExtras), // Fila que se esta editando.
                idFila : $(this).closest("tr").find('.idDetallePlanilla').val(), // Fila que se esta editando.
            };

            $.ajax({
                url: "../../guardar_horas_extras",
                type: "POST",
                beforeSend: function () { },
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.success('Vacaciones guardadas', 'Aviso!');
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('No se agregaron las vacaciones...', 'Aviso!');
                        }
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('No se agregaron las vacaciones...', 'Aviso!');

                    }
                },

                error:function(){
                    alert("Hay un error");
                }
            });

            
        }); */

        $(document).on("click", '.tabledit-save-button', function(event) {
            var area = $(this).closest("tr").find('.areaEmpleado').val(); // Area del empleado
            var salario = parseFloat($(this).closest("tr").find('.salarioBEmpleado').val()); // Salario del empleado sin descuentos
            var bono = parseFloat($(this).closest("tr").find('.bonoEmpleado').val()); // Bono del empleado
            var otros = parseFloat($(this).closest("tr").find('.otrosExtras').val()); // Otro del empleado
            var horasDiurnas = parseFloat($(this).closest("tr").find('.lblTExtras').html()); // Horas diurnas del empleado
            var horasNocturnas = parseFloat($(this).closest("tr").find('.lblTNocturnas').html()); // Horas nocturnas del empleado
            var vacacionesPlanilla = parseFloat($(this).closest("tr").find('.vacacionesPlanilla').val()); // Vacaciones del empleado
            var pivote = 0;

            if(area == 4){
                var totalBase = parseFloat(salario + bono + otros + horasDiurnas + horasNocturnas + vacacionesPlanilla);
                pivote = (salario > 0) ? 1 : 0;

                if(salario > 0){
                    // Calculo de retenciones
                        var salario = totalBase;
                        var isss = 0;
                        var afp = 0;
                        var renta = 0;
                        var totalRetenciones = 0;
                        // Calculo del ISSS
                            if (salario >= 1 && salario <= 500) {
                                isss = salario * 0.03;
                            } else if (salario > 500) {
                                isss = 30 / 2;
                            } else {
                                isss = salario * 0.03;
                            }
    
                        //Fin calculo ISSS
                        afp = salario * 0.0725;  // Calculando porcentaje a descontar de AFP
                        if(pivote == 0){
                            isss = 0;
                            afp = 0;
                            totalRetenciones = 0;
                        }else{
                            totalRetenciones = isss + afp; //Sumando retenciones
                        }
                        salario = salario - totalRetenciones;
                        // Calculo de la renta
                            if (salario >= 1 && salario <= 236) {
                                renta = 0;
                            } else if (salario > 236 && salario <= 447.62) {
                                renta = 8.83 + ((salario - 236) * 0.10);
                            } else if (salario > 447.62 && salario <= 1019.05) {
                                renta = 30 + ((salario - 447.62) * 0.20);
                            } else if (salario > 1019.05 && salario <= 1000000000) {
                                renta = 144.28 + ((salario - 1019.05) * 0.30);
                            } else {
                                renta = 0;
                            }
    
                        // Fin calculo renta
                        salario = salario - renta ;
    
                        $(this).closest("tr").find('.lblIsss').html(isss.toFixed(2));
                        $(this).closest("tr").find('.lblAfp').html(afp.toFixed(2));
                        $(this).closest("tr").find('.lblRentaEmpleado').html(renta.toFixed(2));
                        $(this).closest("tr").find('.lblLiquidoEmpleado').html(salario.toFixed(2));
    
                        var datos = {
                            isss: isss,
                            afp: afp,
                            renta: renta,
                            vacaciones: vacacionesPlanilla,
                            liquido: salario,
                            fila: $(this).closest("tr").find('.idDetallePlanilla').val() // Fila que se esta editando.
                        }
    
                        $.ajax({
                            url: "../../agregar_retenciones",
                            type: "POST",
                            beforeSend: function () { },
                            data: datos,
                            success:function(respuesta){
                                /* var registro = eval(respuesta);
                                if (Object.keys(registro).length > 0){}else{} */
                            },
    
                            error:function(){
                                alert("Hay un error");
                            }
                        });
                    // Calculo de retenciones
                }
    
            }
            $(this).closest("tr").addClass("alert-danger");
        });

        $(document).on("keyup", '.tabledit-input', function(e) {
            // e.preventDefault();
            if(e.which == 13) {
                return false;
            }
        });

        $(document).on("click", '.vacaciones', function(e) {
            // e.preventDefault();
            // var valor = $('input:checkbox[name=vacaciones]:checked').val();
            var area = $(this).closest("tr").find('.areaEmpleado').val(); // Area del empleado
            var valor = $(this).closest("tr").find('input:checkbox[name=vacaciones]:checked').val(); // Para saber si hay vacaciones
            var salarioBase = parseFloat($(this).closest("tr").find('.salarioBEmpleado').val()); // Salario total / 2
            var salarioLiquido = parseFloat($(this).closest("tr").find('.txtLiquidoEmpleado').val()); // Salario liquido

            var bono = parseFloat($(this).closest("tr").find('.bonoEmpleado').val()); // Bono del empleado
            var otros = parseFloat($(this).closest("tr").find('.otrosExtras').val()); // Otro del empleado
            var horasDiurnas = parseFloat($(this).closest("tr").find('.lblTExtras').html()); // Horas diurnas del empleado
            var horasNocturnas = parseFloat($(this).closest("tr").find('.lblTNocturnas').html()); // Horas nocturnas del empleado
            
            var vacaciones = 0;
            var totalBase = 0;

            
            if (valor == "vacaciones") {
                vacaciones = salarioBase * 0.3; // Obteniendo el 30% de el salario de 15 dias
                $(this).closest("tr").find('.vacacionesPlanilla').val(vacaciones)
            } else {
                vacaciones = -1 * (salarioBase * 0.3);
                $(this).closest("tr").find('.vacacionesPlanilla').val(0)
            }

            if(area == 4){
                if(vacaciones > 0){
                    var totalBase = parseFloat(salarioBase + bono + otros + horasDiurnas + horasNocturnas + vacaciones);
                }else{
                    var totalBase = parseFloat(salarioBase + bono + otros + horasDiurnas + horasNocturnas);
                }


                // Calculo de retenciones
                    var salario = totalBase;
                    var isss = 0;
                    var afp = 0;
                    var renta = 0;
                    var totalRetenciones = 0;
                    // Calculo del ISSS
                        if (salario >= 1 && salario <= 500) {
                            isss = salario * 0.03;
                        } else if (salario > 500) {
                            isss = 30 / 2;
                        } else {
                            isss = salario * 0.03;
                        }

                    //Fin calculo ISSS
                    afp = salario * 0.0725;  // Calculando porcentaje a descontar de AFP
                    totalRetenciones = isss + afp; //Sumando retenciones
                    salario = salario - totalRetenciones;
                    // Calculo de la renta
                        if (salario >= 1 && salario <= 236) {
                            renta = 0;
                        } else if (salario > 236 && salario <= 447.62) {
                            renta = 8.83 + ((salario - 236) * 0.10);
                        } else if (salario > 447.62 && salario <= 1019.05) {
                            renta = 30 + ((salario - 447.62) * 0.20);
                        } else if (salario > 1019.05 && salario <= 1000000000) {
                            renta = 144.28 + ((salario - 1019.05) * 0.30);
                        } else {
                            renta = 0;
                        }

                    // Fin calculo renta
                    salario = salario - renta ;

                    $(this).closest("tr").find('.lblIsss').html(isss.toFixed(2));
                    $(this).closest("tr").find('.lblAfp').html(afp.toFixed(2));
                    $(this).closest("tr").find('.lblRentaEmpleado').html(renta.toFixed(2));
                    $(this).closest("tr").find('.lblLiquidoEmpleado').html(salario.toFixed(2));

                    $(this).closest("tr").find('.salarioLEmpleado').val(salario.toFixed(2)); // Asignando salario liquido
                    $(this).closest("tr").find('.lblLiquidoEmpleado').html(salario.toFixed(2));
                    $(this).closest("tr").find('.txtLiquidoEmpleado').val(salario.toFixed(2));

                    if(vacaciones < 0){
                        vacaciones = 0;
                    }

                    var datos = {
                        isss: isss,
                        afp: afp,
                        renta: renta,
                        vacaciones: vacaciones,
                        liquido: salario,
                        fila: $(this).closest("tr").find('.idDetallePlanilla').val() // Fila que se esta editando.
                    }

                    $.ajax({
                        url: "../../agregar_retenciones",
                        type: "POST",
                        beforeSend: function () { },
                        data: datos,
                        success:function(respuesta){
                            // var registro = eval(respuesta);
                            // if (Object.keys(registro).length > 0){}else{}
                        },

                        error:function(){
                            alert("Hay un error");
                        }
                    });
                // Calculo de retenciones
                
                    
            }else{
                salarioLiquido += vacaciones; // Liquido actual en planilla mas el calculo de vacaciones
                $(this).closest("tr").find('.salarioLEmpleado').val(salarioLiquido.toFixed(2)); // Asignando salario liquido
                $(this).closest("tr").find('.lblLiquidoEmpleado').html(salarioLiquido.toFixed(2));
                $(this).closest("tr").find('.txtLiquidoEmpleado').val(salarioLiquido.toFixed(2));

                // Gestionando DB
                    if(vacaciones < 0){
                        vacaciones = 0;
                    }
                    var datos = {
                        salarioTotal : salarioLiquido,
                        vacaciones : vacaciones,
                        planilla : $(this).closest("tr").find('.idDetallePlanilla').val() // Fila que se esta editando.
                    };
                    $.ajax({
                        url: "../../guardar_vacaciones",
                        type: "POST",
                        beforeSend: function () { },
                        data: datos,
                        success:function(respuesta){
                            var registro = eval(respuesta);
                            if (Object.keys(registro).length > 0){
                                if(registro.estado == 1){
                                    toastr.remove();
                                    toastr.options = {
                                        "positionClass": "toast-top-left",
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "1000",
                                        "extendedTimeOut": "50",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                        },
                                    toastr.success('Vacaciones guardadas', 'Aviso!');
                                }else{
                                    toastr.remove();
                                    toastr.options = {
                                        "positionClass": "toast-top-left",
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "1000",
                                        "extendedTimeOut": "50",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                        },
                                    toastr.error('No se agregaron las vacaciones...', 'Aviso!');
                                }
                            }else{
                                toastr.remove();
                                toastr.options = {
                                    "positionClass": "toast-top-left",
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "1000",
                                    "extendedTimeOut": "50",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    },
                                toastr.error('No se agregaron las vacaciones...', 'Aviso!');
    
                            }
                        },
    
                        error:function(){
                            alert("Hay un error");
                        }
                    });
    
                // Gestionando DB
            }

            
            
            



        });


        $(document).on("change", '.horasNocturnas', function(e) {
            // e.preventDefault();
            var liquidoAsignado = 0;
            var totalNocturnas = 0;
            var horasNocturnas = parseFloat($(this).val());
            var precioHoraNocturna = parseFloat($(this).closest("tr").find('.precioHoraNocturna').val()); // Precio de horas nocturnas
            var liquido = parseFloat($(this).closest("tr").find('.txtLiquidoEmpleado').val()); // Salario del empleado sin descuentos
            var nocturnasAnterior = parseFloat($(this).closest("tr").find('.numeroHorasNocturnas').val());

            var totalNocturnas = horasNocturnas * precioHoraNocturna;

            $(this).closest("tr").find('.lblTNocturnas').html(totalNocturnas.toFixed(2)); // Label de total de horas nocturnas

            // nocturnasTEmpleado


           $(this).closest("tr").find('.numeroHorasNocturnas').val(horasNocturnas);

           $(this).closest("tr").find('.lblLiquidoEmpleado').html((liquido + totalNocturnas).toFixed(2));
           $(this).closest("tr").find('.txtLiquidoEmpleado').val((liquido + totalNocturnas).toFixed(2));
            

            // console.log(area);
            // Gestionando DB



        });


        $(document).on("change", '.horasExtras', function(e) {
            // e.preventDefault();
            var liquidoAsignado = 0;
            var totalHoras = 0;
            var rentaTotal = 0;

            var area = $(this).closest("tr").find('.areaEmpleado').val(); // Area del empleado

            var salario = parseFloat($(this).closest("tr").find('.salarioBEmpleado').val()); // Salario del empleado sin descuentos
            var horasActual = parseFloat($(this).val());
            var horasAnterior = parseFloat($(this).closest("tr").find('.numeroHorasExtras').val());
            var precioHoraExtra = parseFloat($(this).closest("tr").find('.precioHoraExtra').val()); // Precio de cada hora extra

            var otrosExtras = parseFloat($(this).closest("tr").find('.otrosExtras ').val()); // Precio de cada hora extra


            if(salario == 0){

                totalHoras = horasActual * precioHoraExtra;

                if(area == 9){
                    rentaTotal = 0;
                }else{
                    rentaTotal = totalHoras * 0.10;
                }
                totalHoras -= rentaTotal;
                totalHoras += otrosExtras;

                $(this).closest("tr").find('.numeroHorasExtras').val(horasActual);
                $(this).closest("tr").find('.lblLiquidoEmpleado').html((totalHoras).toFixed(2));
                $(this).closest("tr").find('.txtLiquidoEmpleado').val((totalHoras).toFixed(2));


                $(this).closest("tr").find('.lblRentaEmpleado').html((rentaTotal).toFixed(2));
                $(this).closest("tr").find('.rentaEmpleado').val((rentaTotal).toFixed(2));
                $(this).closest("tr").find('.lblTExtras').html((totalHoras).toFixed(2));
                
                
            }else{
                if(horasActual <= 1000){
                    totalHoras = horasActual * precioHoraExtra;
                    $(this).closest("tr").find('.lblTExtras').html((totalHoras).toFixed(2));
                }else{
                    $(this).val(0);
                    $(this).closest("tr").find('.lblTExtras ').html(0.00)
                    $(this).focus();
                }

            }


        });

        
        $(document).on("change", '.otrosExtras, .horasExtras, .horasNocturnas', function(e) {
            // e.preventDefault();
            var self = this;
            
            var datos = {
                totalOtros : $(this).closest("tr").find('.otrosExtras').val(),
                numHorasExtras : $(this).closest("tr").find('.horasExtras').val(),
                totalExtras : $(this).closest("tr").find('.lblTExtras').html(), // Label de total de horas extras
                numHorasNocturnas : $(this).closest("tr").find('.horasNocturnas ').val(),
                lblTNocturnas : $(this).closest("tr").find('.lblTNocturnas').html(), // Label de total de horas nocturnas
                isssEmpleado : $(this).closest("tr").find('.isssEmpleado').val(), // Label de total ISSS
                afpTEmpleado : $(this).closest("tr").find('.afpTEmpleado').val(), // Label de total AFP
                rentaEmpleado : $(this).closest("tr").find('.rentaEmpleado').val(), // Label de total renta
                txtLiquidoEmpleado : $(this).closest("tr").find('.txtLiquidoEmpleado').val(), // Label de total liquido
                idFila : $(this).closest("tr").find('.idDetallePlanilla').val() // Fila que se esta editando.
            };

            // console.log(datos);

            $.ajax({
                url: "../../guardar_montos",
                type: "POST",
                beforeSend: function () { },
                data: datos,
                success:function(respuesta){
                    var registro = eval(respuesta);
                    if (Object.keys(registro).length > 0){
                        if(registro.estado == 1){
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.success('Datos guardados', 'Aviso!');
                            $(self).closest("tr").find('.txtLiquidoEmpleado').val(registro.liquido.toFixed(2));
                            $(self).closest("tr").find('.lblLiquidoEmpleado').html(registro.liquido.toFixed(2));
                        }else{
                            toastr.remove();
                            toastr.options = {
                                "positionClass": "toast-top-left",
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "1000",
                                "extendedTimeOut": "50",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                                },
                            toastr.error('Error al guardar los datos', 'Aviso!');
                            $(self).closest("tr").find('.txtLiquidoEmpleado').val(registro.liquido.toFixed(2));
                            $(self).closest("tr").find('.lblLiquidoEmpleado').html(registro.liquido.toFixed(2));
                        }
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('Error al guardar los datos', 'Aviso!');
                        $(self).closest("tr").find('.txtLiquidoEmpleado').val(registro.liquido.toFixed(2));
                        $(self).closest("tr").find('.lblLiquidoEmpleado').html(registro.liquido.toFixed(2));
                    }
                },
                error:function(){
                    alert("Hay un error");
                }
            });

            // console.log(datos);
            /* var liquidoAsignado = 0;
            var extrasActual = parseFloat($(this).val());
            var extrasAnterior = parseFloat($(this).closest("tr").find('.oldOtrosDetallePlanilla').val());
            var salario = parseFloat($(this).closest("tr").find('.salarioBEmpleado').val()); // Salario del empleado sin descuentos
            var liquidoActual = parseFloat($(this).closest("tr").find('.txtLiquidoEmpleado').val()); // Salario del empleado sin descuentos
            // var liquidoAnterior = $(this).closest("tr").find('.oldOtrosDetallePlanilla').val(); // Salario del empleado sin descuentos

            if(salario == 0){

                liquidoActual = liquidoActual - extrasAnterior;
                liquidoActual = liquidoActual + extrasActual;
                
                $(this).closest("tr").find('.oldOtrosDetallePlanilla').val(extrasActual);
                $(this).closest("tr").find('.lblLiquidoEmpleado').html((liquidoActual).toFixed(2));
                $(this).closest("tr").find('.txtLiquidoEmpleado').val((liquidoActual).toFixed(2));

            } */

           
            // Gestionando DB

        });

        /* Reseteando valores */
        $(document).on("click", '.resetear', function(event) {
            event.preventDefault();
            $("#filaResetear").val($(this).closest("tr").find('.idDetallePlanilla').val());
            
        });
        /* Reseteando valores */  
    });

    $(document).on("click", '.btn-liquido', function(event) {
        event.preventDefault();
        $("#idFilaA").val($(this).closest("tr").find('.idDetallePlanilla').val());
        $("#salarioLiquidoA").val($(this).closest("tr").find('.txtLiquidoEmpleado').val());
    });

    $(document).on("click", '.guardarDescuento', function(event) {
        event.preventDefault();
        var datos = {
            cuota : $(this).closest("tr").find('.cuotaDescuento').val(),
            idEmpleadoDescuento : $(this).closest("tr").find('.idEmpleadoDescuento').val(),
            idDescuento : $(this).closest("tr").find('.idDescuento').val(),
            fila : $(this).closest("tr").find('.filaDetalle').val(),
            planilla : $(this).closest("tr").find('.idPlanilla').val()
        };

        $.ajax({
            url: "../../procesar_descuentos",
            type: "POST",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.success('Datos guardados', 'Aviso!');
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('Error al agregar los datos...', 'Aviso!');
                    }
                }else{
                    toastr.remove();
                    toastr.options = {
                        "positionClass": "toast-top-left",
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "1000",
                        "extendedTimeOut": "50",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                        },
                    toastr.error('Error al agregar los datos...', 'Aviso!');

                }
            },

            error:function(){
                alert("Hay un error");
            }
        });

        $(this).closest('tr').remove();
    });

    $(document).on("click", '.saldarDescuento', function(event) {
        // event.preventDefault();
        var datos = {
            idDescuento : $(this).closest("tr").find('.idEmpleadoDescuento').val(),
        };
        $.ajax({
            url: "../../saldar_descuentos",
            type: "POST",
            beforeSend: function () { },
            data: datos,
            success:function(respuesta){
                var registro = eval(respuesta);
                if (Object.keys(registro).length > 0){
                    if(registro.estado == 1){
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.success('El proceso fue realizado con exito', 'Aviso!');
                    }else{
                        toastr.remove();
                        toastr.options = {
                            "positionClass": "toast-top-left",
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "1000",
                            "extendedTimeOut": "50",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                            },
                        toastr.error('Error al realizar el proceso...', 'Aviso!');
                    }
                }else{
                    toastr.remove();
                    toastr.options = {
                        "positionClass": "toast-top-left",
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "1000",
                        "extendedTimeOut": "50",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                        },
                    toastr.error('Error al realizar el proceso...', 'Aviso!');

                }
            },
            error:function(){
                alert("Hay un error");
            }
        });

        $(this).closest('tr').remove();
    });

    $(document).on('click', '.close', function(event) {
        event.preventDefault();
        location.reload();
    });

    $(document).ready(function() {
        // Obtener la última pestaña activa desde el almacenamiento local
        var ultimaPestana = localStorage.getItem('ultimaPestana');

        // Si hay una última pestaña activa, activarla
        if (ultimaPestana) {
            $('.nav-tabs a[href="' + ultimaPestana + '"]').tab('show');
        }

        // Guardar la pestaña activa al cambiar de pestaña
        $('.nav-tabs a').on('shown.bs.tab', function(event){
            var nuevaPestana = $(event.target).attr('href');
            localStorage.setItem('ultimaPestana', nuevaPestana);
        });

        if($("#proximaReceta").val() != ""){
            $("#btnGuardarReceta").show();
        }

    });


    function calculoRetenciones(totalGlobal = null, pivote = null){

        // Calculo de retenciones
            var salario = totalGlobal;
            var isss = 0;
            var afp = 0;
            var renta = 0;
            var totalRetenciones = 0;
            // Calculo del ISSS
                if (salario >= 1 && salario <= 500) {
                    isss = salario * 0.03;
                } else if (salario > 500) {
                    isss = 30 / 2;
                } else {
                    isss = salario * 0.03;
                }

            //Fin calculo ISSS
            afp = salario * 0.0725;  // Calculando porcentaje a descontar de AFP
            if(pivote == 0){
                isss = 0;
                afp = 0;
                totalRetenciones = 0;
            }else{
                totalRetenciones = isss + afp; //Sumando retenciones
            }
            salario = salario - totalRetenciones;
            // Calculo de la renta
                if (salario >= 1 && salario <= 236) {
                    renta = 0;
                } else if (salario > 236 && salario <= 447.62) {
                    renta = 8.83 + ((salario - 236) * 0.10);
                } else if (salario > 447.62 && salario <= 1019.05) {
                    renta = 30 + ((salario - 447.62) * 0.20);
                } else if (salario > 1019.05 && salario <= 1000000000) {
                    renta = 144.28 + ((salario - 1019.05) * 0.30);
                } else {
                    renta = 0;
                }

            // Fin calculo renta
            salario = salario - renta ;

            $(this).closest("tr").find('.lblIsss').html(isss);
            $(this).closest("tr").find('.lblAfp').html(afp);
            $(this).closest("tr").find('.lblRentaEmpleado').html(renta);
            $(this).closest("tr").find('.lblLiquidoEmpleado').html(salario);
            
            
            

            console.log(isss + " " + afp + " " + renta + " " + totalGlobal + " " + salario);
        // Calculo de retenciones
    }

    
    
</script>