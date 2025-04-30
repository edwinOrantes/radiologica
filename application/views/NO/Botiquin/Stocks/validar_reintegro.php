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
<div class="ms-content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Botiquin </a> </li>
                    <li class="breadcrumb-item active"><a href="#">Lista de insumos a ser reintegrados</a></li>
                </ol>
            </nav>

            <?php
                if(sizeof($insumos)){
            ?>
                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" id="enfermeraEntrega" required="">
                                        <option value="0">..: Seleccionar :..</option>
                                        <?php
                                            foreach ($enfermeras as $row) {
                                                echo '<option value="'.$row->idEnfermera.'">'.$row->nombreEnfermera.'</option>';
                                            }
                                        ?>
                                        
                                    </select>

                                </div>
                            </div>
                            
                            <div class="col-md-6 text-right">
                                <div class="botonera text-right" id="botonera" style="display: none">
                                    <a href="<?php echo base_url().'Botiquin/validar_reintegro/'.$parametros; ?>" class="btn btn-success btn-sm"> Validar <i class="fa fa-check"></i></a>
                                    <a href="<?php echo base_url().'Botiquin/detalle_a_imprimir/'.$parametros; ?>" target="blank" class="btn btn-danger btn-sm"> Imprimir <i class="fa fa-print"></i></a>
                                    <!-- <button type="button" class="btn btn-danger btn-sm" id="btnImprimir"> Imprimir <i class="fa fa-print"></i></button> -->
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="ms-panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="" class="table table-bordered thead-primary w-100 tabla-medicamentos">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">#</th>
                                            <th class="text-center" scope="col">Código</th>
                                            <th class="text-center" scope="col">Nombre</th>
                                            <th class="text-center" scope="col">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $index = 0;

                                        foreach ($insumos as $nombreInsumo => $row) {
                                            $index++;
                                        ?>
                                        <tr class="">
                                            <td class="text-center"><?php echo $index; ?></td>
                                            <td class="text-center"><?php echo $row["codigoInsumo"]; ?></td>
                                            <td class="text-center"><?php echo $nombreInsumo; ?></td>
                                            <td class="text-center"><?php echo $row["faltante"]; ?></td>
                                            
                                        </tr>
                                        
                                        <?php
                                        } // Fin del foreach
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }else{
                    echo '<div class="text-center p-3 alert-danger bold"> No hay datos que mostrar... </div>';
                }
            ?>

        </div>
    </div>
</div>

<script>
    $(document).on("change", "#enfermeraEntrega", function() {
        var pivote = $(this).val();
        // Obtener el índice seleccionado
        var indiceSeleccionado = $(this).prop("selectedIndex"); // Si se desea el indice
        // Obtener el texto seleccionado
        var textoSeleccionado = $(this).find("option:selected").text();  // Si se desea el texto
        $("#entregadoA").html(textoSeleccionado);

        if(pivote > 0){
            $("#botonera").show();
        }else{
            $("#botonera").hide();
        }
        // alert(pivote);
    });
</script>