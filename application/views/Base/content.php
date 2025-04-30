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

<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
  <div class="row">
    <!-- Notifications Widgets -->
    <div class="col-xl-3 col-md-6 col-sm-6">
      <a href="#">
        <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
          <div class="ms-card-body media">
            <div class="media-body">
              <h6>Doctores</h6>
              <p class="ms-card-change"> 4567</p>
            </div>
          </div>
          <i class="fas fa-stethoscope ms-icon-mr"></i>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
      <a href="#">
        <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
          <div class="ms-card-body media">
            <div class="media-body">
              <h6>Enfermeras</h6>
              <p class="ms-card-change"> 4567</p>
            </div>
          </div>
          <i class="fas fa-user-plus ms-icon-mr"></i>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
      <a href="#">
        <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
          <div class="ms-card-body media">
            <div class="media-body">
              <h6 class="bold">Pacientes</h6>
              <p class="ms-card-change"> 4567</p>
            </div>
          </div>
          <i class="fa fa-wheelchair ms-icon-mr"></i>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
      <a href="#">
        <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
          <div class="ms-card-body media">
            <div class="media-body">
              <h6 class="bold">Botiquín</h6>
              <p class="ms-card-change"> 4567</p>
            </div>
          </div>
          <i class="fas fa-briefcase-medical ms-icon-mr"></i>
        </div>
      </a>
    </div>

    <div class="col-xl-8 col-md-12">
      <div class="ms-panel">
        <div class="ms-panel-header">
          <h6>Nuevos Pacientes</h6>
        </div>
        <div class="ms-panel-body">
          <div class="table-responsive">
            <table class="table table-hover thead-primary">
              <thead>
                <tr>
                  <th scope="col">Paciente</th>
                  <th scope="col">E-mail Id</th>
                  <th scope="col">M.No</th>
                  <th scope="col">Enfermedad</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="ms-table-f-w"> <img src="https://via.placeholder.com/270x270" alt="people"> Richard </td>
                  <td>Richard288@gmail.com</td>
                  <td>+1-202-555-0875</td>
                  <td>Fever</td>
                </tr>
                <tr>
                  <td class="ms-table-f-w"> <img src="https://via.placeholder.com/270x270" alt="people"> William </td>
                  <td>William434@gmail.com</td>
                  <td>+1-202-534-0112</td>
                  <td>Eye</td>
                </tr>
                <tr>
                  <td class="ms-table-f-w"> <img src="https://via.placeholder.com/270x270" alt="people"> John Doe </td>
                  <td>johndeo652@gmail.com</td>
                  <td>+1-202-182-0132</td>
                  <td>Typhoid</td>
                </tr>
                <tr>
                  <td class="ms-table-f-w"> <img src="https://via.placeholder.com/270x270" alt="people"> Martin </td>
                  <td>Martin876@gmail.com</td>
                  <td>+1-202-998-2341</td>
                  <td>Cancer</td>
                </tr>
                <tr>
                  <td class="ms-table-f-w"> <img src="https://via.placeholder.com/270x270" alt="people"> Robert </td>
                  <td>Robert082@gmail.com</td>
                  <td>+1-202-455-1431</td>
                  <td>Diabetes</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-md-12">
      <div class="ms-panel ms-panel-fh ms-widget">
        <div class="ms-panel-header ms-panel-custome">
          <h6>Lista de doctores</h6>
        </div>
        <div class="ms-panel-body p-0">
          <ul class="ms-followers ms-list ms-scrollable">
            <li class="ms-list-item media">
              <img src="https://via.placeholder.com/270x270" class="ms-img-small ms-img-round" alt="people">
              <div class="media-body mt-1">
                <h4>Micheal</h4>
                <span class="fs-12">MBBS, MD</span>
              </div>
              <button type="button" class="ms-btn-icon btn-success" name="button"><i class="material-icons">check</i> </button>
            </li>
            <li class="ms-list-item media">
              <img src="https://via.placeholder.com/270x270" class="ms-img-small ms-img-round" alt="people">
              <div class="media-body mt-1">
                <h4>Jennifer</h4>
                <span class="fs-12">MD</span>
              </div>
              <button type="button" class="ms-btn-icon btn-info" name="button"><i class="material-icons">person_add</i> </button>
            </li>
            <li class="ms-list-item media">
              <img src="https://via.placeholder.com/270x270" class="ms-img-small ms-img-round" alt="people">
              <div class="media-body mt-1">
                <h4>Adwerd </h4>
                <span class="fs-12">BMBS</span>
              </div>
              <button type="button" class="ms-btn-icon btn-info" name="button"><i class="material-icons">person_add</i> </button>
            </li>
            <li class="ms-list-item media">
              <img src="https://via.placeholder.com/270x270" class="ms-img-small ms-img-round" alt="people">
              <div class="media-body mt-1">
                <h4>John Doe</h4>
                <span class="fs-12">MS, MD</span>
              </div>
              <button type="button" class="ms-btn-icon btn-success" name="button"><i class="material-icons">check</i> </button>
            </li>
            <li class="ms-list-item media">
              <img src="https://via.placeholder.com/270x270" class="ms-img-small ms-img-round" alt="people">
              <div class="media-body mt-1">
                <h4>Jordan</h4>
                <span class="fs-12">MBBS</span>
              </div>
              <button type="button" class="ms-btn-icon btn-info" name="button"><i class="material-icons">person_add</i> </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
    
    

    
  
  </div>
</div>

