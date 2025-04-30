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
                        <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-users"></i> Cajeras
                            </a> </li>
                        <li class="breadcrumb-item"><a href="#">Lista de cortes </a></li>
                    </ol>
                </nav>

                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Cortes de caja hechos por: <strong><?php echo $this->session->userdata("usuario_h"); ?></strong> </h6>
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
                                            <th class="text-center" scope="col">Fecha</th>
                                            <th class="text-center" scope="col">Turno</th>
                                            <th class="text-center" scope="col">Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $index = 0;
                                            foreach ($cortes as $corte) {
                                            $index++;
                                        ?>
                                        <tr>
                                            <td class="text-center" scope="row"><?php echo $index; ?></td>
                                            <td class="text-center"><?php echo $corte->fechaCorte; ?></td>
                                            <td class="text-center"><?php echo $corte->turnoCorte; ?></td>
                                            <td class="text-center">
                                                <?php
                                                    echo '<a href="'.base_url().'Cajeras/hacia_corte/'.$corte->idCorteCaja.'" target="blank"><i class="far fa-eye ms-text-primary"></i></a>';
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
<!-- Body Content Wrapper -->