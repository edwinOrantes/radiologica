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

<?php
    function dias($i = null, $f = null){
        $cadena = "";
        $inicio= new DateTime($i);
        $fin= new DateTime($f);
        $dias = $inicio->diff($fin);

        if($inicio < $fin){
            // El resultados sera 3 dias
            switch ($dias->days) {
                case ($dias->days <= 90):
                    $cadena = '<span class="badge badge-danger">'.$dias->days.' dias</span>';
                    break;
                case ($dias->days >= 90 && $dias->days <=100):
                    $cadena = '<span class="badge badge-success">'.$dias->days.' dias</span>';
                    break;
                    
                    default:
                        $cadena = '<span class="badge badge-primary">'.$dias->days.' dias</span>';
                        break;
                    
                }
                
        }else{
            $cadena = '<span class="badge badge-danger">'.$dias->days.' dias de Vencido </span>';  
        }
        return $cadena;

    }
?>

<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Medicamentos </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Detalle de stocks</a></li>
                </ol>
            </nav>

            <div class="ms-panel">
                <!-- <div class="ms-panel-header">
                    <div class="row">

                        <div class="col-md-6">
                            <h6>Detalle de stocks</h6>
                        </div>
                        
                        <div class="col-md-6 text-right"></div>

                    </div>
                </div> -->

                <div class="ms-panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs tabs-bordered d-flex nav-justified mb-4" role="tablist">
                                <li role="presentation"><a href="#tab1" aria-controls="tab1" class="active show" role="tab" data-toggle="tab" aria-selected="true">Caja de paro Nivel 1</a></li>
                                <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab" class="" aria-selected="false">Caja de paro Nivel 2</a></li>
                                <li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab" class="" aria-selected="false">Caja de paro Nivel 3</a></li>
                                <li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab" class="" aria-selected="false">Caja de paro Nivel 4</a></li>
                                <li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab" class="" aria-selected="false">Stock Emergencia</a></li>
                            </ul>
                            <div class="tab-content text-right btn-sm">
                                
                                    
                                <div role="tabpanel" class="tab-pane fade in active show" id="tab1">
                                    <a href="<?php echo base_url()."Botiquin/reintegrar_insumos/".$faltante1."/"; ?>" class="btn btn-primary"> Reintegrar <i class="fa fa-tasks"></i> </a>
                                    <div class="table-responsive mt-3">
                                        <table id="" class="table table-bordered thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Fecha vencimiento</th>
                                                    <th class="text-center" scope="col">Para vencer</th>
                                                    <th class="text-center" scope="col">Existencia</th>
                                                    <th class="text-center" scope="col">Fijo</th>
                                                    <th class="text-center" scope="col">Faltante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $index = 0;
                                                $cero = 0;
                                                $alert = 0;
                                                foreach ($detalleStocks as $row) {
                                                    if($row->idStock == 1){
                                                        if($row->stockInsumo != $row->debeInsumo){
                                                            $alert = "alert-danger";
                                                        }else{
                                                            $alert = "";
                                                        }
                                                ?>
                                                <tr class="<?php echo $alert; ?>">
                                                    <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? $row->fechaVencimiento : "---";  ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? dias(date("Y-m-d"), $row->fechaVencimiento) : "---";  ?></td>
                                                    <td class="text-center"><?php echo $row->stockInsumo; ?></td>
                                                    <td class="text-center"><?php echo $row->debeInsumo; ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if($row->stockInsumo != $row->debeInsumo){
                                                                echo '<span class="badge badge-danger">'.($row->debeInsumo - $row->stockInsumo).'</span>';
                                                            }else{
                                                                echo "-";
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                                    }
                                                } // Fin del foreach
                                            
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab2">
                                    <a href="<?php echo base_url()."Botiquin/reintegrar_insumos/".$faltante2."/"; ?>" class="btn btn-primary"> Reintegrar <i class="fa fa-tasks"></i> </a>
                                    <div class="table-responsive mt-3">
                                        <table id="" class="table table-bordered thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Fecha vencimiento</th>
                                                    <th class="text-center" scope="col">Para vencer</th>
                                                    <th class="text-center" scope="col">Existencia</th>
                                                    <th class="text-center" scope="col">Fijo</th>
                                                    <th class="text-center" scope="col">Faltante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $index = 0;
                                                $cero = 0;
                                                $alert = 0;
                                                foreach ($detalleStocks as $row) {
                                                    if($row->idStock == 2){
                                                        if($row->stockInsumo != $row->debeInsumo){
                                                            $alert = "alert-danger";
                                                        }else{
                                                            $alert = "";
                                                        }
                                                ?>
                                                <tr class="<?php echo $alert; ?>">
                                                    <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? $row->fechaVencimiento : "---";  ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? dias(date("Y-m-d"), $row->fechaVencimiento) : "---";  ?></td>
                                                    <td class="text-center"><?php echo $row->stockInsumo; ?></td>
                                                    <td class="text-center"><?php echo $row->debeInsumo; ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if($row->stockInsumo != $row->debeInsumo){
                                                                echo '<span class="badge badge-danger">'.($row->debeInsumo - $row->stockInsumo).'</span>';
                                                            }else{
                                                                echo "-";
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                                    }
                                                } // Fin del foreach
                                            
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab3">
                                    <a href="<?php echo base_url()."Botiquin/reintegrar_insumos/".$faltante3."/"; ?>" class="btn btn-primary"> Reintegrar <i class="fa fa-tasks"></i> </a>
                                    <div class="table-responsive mt-3">
                                        <table id="" class="table table-bordered thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Fecha vencimiento</th>
                                                    <th class="text-center" scope="col">Para vencer</th>
                                                    <th class="text-center" scope="col">Existencia</th>
                                                    <th class="text-center" scope="col">Fijo</th>
                                                    <th class="text-center" scope="col">Faltante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $index = 0;
                                                $cero = 0;
                                                $alert = 0;
                                                foreach ($detalleStocks as $row) {
                                                    if($row->idStock == 3){
                                                        if($row->stockInsumo != $row->debeInsumo){
                                                            $alert = "alert-danger";
                                                        }else{
                                                            $alert = "";
                                                        }
                                                ?>
                                                <tr class="<?php echo $alert; ?>">
                                                    <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? $row->fechaVencimiento : "---";  ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? dias(date("Y-m-d"), $row->fechaVencimiento) : "---";  ?></td>
                                                    <td class="text-center"><?php echo $row->stockInsumo; ?></td>
                                                    <td class="text-center"><?php echo $row->debeInsumo; ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if($row->stockInsumo != $row->debeInsumo){
                                                                echo '<span class="badge badge-danger">'.($row->debeInsumo - $row->stockInsumo).'</span>';
                                                            }else{
                                                                echo "-";
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                                    }
                                                } // Fin del foreach
                                            
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab4">
                                    <a href="<?php echo base_url()."Botiquin/reintegrar_insumos/".$faltante4."/"; ?>" class="btn btn-primary"> Reintegrar <i class="fa fa-tasks"></i> </a>
                                    <div class="table-responsive mt-3">
                                        <table id="" class="table table-bordered thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Fecha vencimiento</th>
                                                    <th class="text-center" scope="col">Para vencer</th>
                                                    <th class="text-center" scope="col">Existencia</th>
                                                    <th class="text-center" scope="col">Fijo</th>
                                                    <th class="text-center" scope="col">Faltante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $index = 0;
                                                $cero = 0;
                                                $alert = 0;
                                                foreach ($detalleStocks as $row) {
                                                    if($row->idStock == 4){
                                                        if($row->stockInsumo != $row->debeInsumo){
                                                            $alert = "alert-danger";
                                                        }else{
                                                            $alert = "";
                                                        }
                                                ?>
                                                <tr class="<?php echo $alert; ?>">
                                                    <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? $row->fechaVencimiento : "---";  ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? dias(date("Y-m-d"), $row->fechaVencimiento) : "---";  ?></td>
                                                    <td class="text-center"><?php echo $row->stockInsumo; ?></td>
                                                    <td class="text-center"><?php echo $row->debeInsumo; ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if($row->stockInsumo != $row->debeInsumo){
                                                                echo '<span class="badge badge-danger">'.($row->debeInsumo - $row->stockInsumo).'</span>';
                                                            }else{
                                                                echo "-";
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                                    }
                                                } // Fin del foreach
                                            
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab5">
                                    <a href="<?php echo base_url()."Botiquin/reintegrar_insumos/".$faltante5."/"; ?>" class="btn btn-primary"> Reintegrar <i class="fa fa-tasks"></i> </a>
                                    <div class="table-responsive mt-3">
                                        <table id="" class="table table-bordered thead-primary w-100 tabla-medicamentos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Código</th>
                                                    <th class="text-center" scope="col">Nombre</th>
                                                    <th class="text-center" scope="col">Fecha vencimiento</th>
                                                    <th class="text-center" scope="col">Para vencer</th>
                                                    <th class="text-center" scope="col">Existencia</th>
                                                    <th class="text-center" scope="col">Fijo</th>
                                                    <th class="text-center" scope="col">Faltante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $index = 0;
                                                $cero = 0;
                                                $alert = 0;
                                                foreach ($detalleStocks as $row) {
                                                    if($row->idStock == 5){
                                                        if($row->stockInsumo != $row->debeInsumo){
                                                            $alert = "alert-danger";
                                                        }else{
                                                            $alert = "";
                                                        }
                                                ?>
                                                <tr class="<?php echo $alert; ?>">
                                                    <td class="text-center"><?php echo $row->codigoMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $row->nombreMedicamento; ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? $row->fechaVencimiento : "---";  ?></td>
                                                    <td class="text-center"><?php echo $dias = !is_null($row->fechaVencimiento) ? dias(date("Y-m-d"), $row->fechaVencimiento) : "---";  ?></td>
                                                    <td class="text-center"><?php echo $row->stockInsumo; ?></td>
                                                    <td class="text-center"><?php echo $row->debeInsumo; ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if($row->stockInsumo != $row->debeInsumo){
                                                                echo '<span class="badge badge-danger">'.($row->debeInsumo - $row->stockInsumo).'</span>';
                                                            }else{
                                                                echo "-";
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                                    }
                                                } // Fin del foreach
                                            
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>