<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.error('<?php echo $this->session->flashdata("error")?>', 'Advertencia!');
    });
  </script>
<?php endif; ?>

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-xl-12 col-md-12">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap has-bg">
                    <li class="breadcrumb-item active" aria-current="page"> <a href="#"><i class="fa fa-tasks"></i> Reportes</a> </li>
                    <li class="breadcrumb-item active"><a href="#">Cuentas activas con abonos</a></li>
                </ol>
            </nav>
			<div class="ms-panel">
				<div class="ms-panel-header">
					<h6>Seleccione el formato en el que desea el reporte</h6>
				</div>
				<div class="ms-panel-body">
					<div class="row">
						<div class="col-md-8 tade">
                            <a href="<?php echo base_url(); ?>Auditoria/cuentas_con_abonos_pdf" class="btn btn-danger" target="blank"><i class="fa fa-file-pdf"></i> PDF</a>
                            <a href="<?php echo base_url(); ?>Auditoria/cuentas_con_abonos_excel" class="btn btn-success" target="blank"><i class="fa fa-file-excel"></i> EXCEL</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
