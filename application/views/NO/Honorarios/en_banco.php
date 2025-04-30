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
    $pivote = 0;
    $aSaldar = array();
    if(sizeof($listaHonorarios) > 0){
        $params = 0;
        $pivote = 1;
        foreach ($listaHonorarios as $row) {
            $aSaldar[]["honorario"] = $row->idHonorario;
        }
        $params = urlencode(base64_encode(serialize($aSaldar)));
    }else{
        $pivote  = 0;
    }
?>

<!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
        <div class="row">
            <div class="col-md-12">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrow has-gap">
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fas fa-flask"></i> Honorarios  </a> </li>
                        <li class="breadcrumb-item"><a href="#">Banco</a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6"><h6>Listado de honorarios depositados en el banco</h6></div>
                            <div class="col-md-6 text-right">
                            <?php
                                if(sizeof($honorarios) > 0){
                                    echo '<a href="#saldarHonorarios" data-toggle="modal" class="btn btn-success btn-sm"> Saldar honorarios <i class="fa fa-list"></i> </a>';
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="ms-panel-body">
                        <div class="row">
                            <div class="table-responsive mt-3">

                            <?php
                                if (sizeof($honorarios) > 0) {
    
                            ?>

                                <table id="tabla-medicamentos" class="table table-striped thead-primary w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th class="text-center" scope="col">Medico</th>
                                            <th class="text-center" scope="col">Cantidad</th>
                                            <th class="text-center" scope="col">Total</th>
                                            <th class="text-center" scope="col">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $index = 0;
                                            foreach ($honorarios as $row) {
                                                $index++;
                                        
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $index; ?></td>
                                            <td class="text-center">
                                                <?php 
                                                    $resultado = str_ireplace("(Honorarios)", "", $row->nombreExterno);
                                                    echo $resultado;
                                                ?>
                                            </td>
                                            <td class="text-center"><span class="badge badge-danger"><?php echo $row->numero_honorarios; ?></span></td>
                                            <td class="text-center">$<?php echo number_format($row->honorario, 2); ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo base_url(); ?>Honorarios/detalle_honorarios_banco/<?php echo $row->idExterno; ?>/"><i class="fa fa-list text-success"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>

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
    </div>
<!-- Body Content Wrapper -->

<!-- saldar honorarios-->
    <div class="modal fade" id="saldarHonorarios" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ms-modal-dialog-width">
            <div class="modal-content ms-modal-content-width">
                <div class="modal-header  ms-modal-header-radius-0">
                    <h4 class="modal-title text-white text-center"></i> Datos del activos</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class="text-white">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="col-xl-12 col-md-12">
                        <div class="ms-panel ms-panel-bshadow-none">
                            <form class="needs-validation" id="frmGastos" method="post" action="<?php echo base_url(); ?>Honorarios/saldar_honorarios_banco/" novalidate>
                                <div class="form-row">

                                    <div class="col-md-12">
                                        <label for=""><strong>N° de cheque</strong></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeros" id="numeroCheque" name="numeroCheque" placeholder="Ingrese el numero del cheque." onKeyPress="return soloNumeros(event)" required>
                                            <div class="invalid-tooltip">
                                                Ingrese el nombre del cheque.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for=""><strong>Banco:</strong></label>
                                        <div class="input-group">
                                            <select class="form-control" id="bancoPago" name="bancoPago" required>
                                                <option value="">.:: Seleccionar ::.</option>
                                                <option value="Hipotecario">Hipotecario</option>
                                                <option value="Davivienda">Davivienda</option>
                                                <option value="Agricola">Agricola</option>
                                                <option value="Promerica">Promerica</option>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Ingrese quien recibe el $.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for=""><strong>N° de cuenta:</strong></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeros" id="cuentaPago" name="cuentaPago" placeholder="Ingrese la cuenta del banco" onKeyPress="return soloNumeros(event)" required>
                                            <div class="invalid-tooltip">
                                                Ingrese un nombre.
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" value="<?php echo $params;?>" id="honorariosPago" name="honorariosPago" required>
                                    <input type="hidden" value="0" id="returnPago" name="returnPago" required>
                                    <div class=" col-md-12 text-center">
                                        <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Saldar </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Fin saldar honorarios-->