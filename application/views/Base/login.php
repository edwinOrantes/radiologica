
<!DOCTYPE html>
<html lang="en">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Hospital Orellana</title>
  <!-- Iconic Fonts -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  	<link href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/font-awesome/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/flat-icons/flaticon.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/cryptocoins/cryptocoins.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/cryptocoins/cryptocoins-colors.css">
  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url(); ?>public/tema/assets/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery UI -->
  <link href="<?php echo base_url(); ?>public/tema/assets/css/jquery-ui.min.css" rel="stylesheet">
  <!-- Medjestic styles -->
  <link href="<?php echo base_url(); ?>public/tema/assets/css/style.css" rel="stylesheet">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>public/tema/favicon.ico">

  <link href="<?php echo base_url(); ?>public/tema/assets/css/toastr.min.css" rel="stylesheet">


  
<style>
  body{
    /* background: url('./public/img/bg_navidad.png'); */
    overflow: hidden;
  }
  footer {
      background: #197fb0;
      position: relative;
      bottom: 2rem;
      z-index: 2;
      height: 50px;
      
    }

</style>


  <script src="<?php echo base_url(); ?>public/tema/assets/js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo base_url(); ?>public/tema/assets/js/popper.min.js"></script>

</head>

<body class="ms-body ms-primary-theme">

<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
  	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	  toastr.warning('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>


  <!-- Preloader -->
  <div id="preloader-wrap">
    <div class="spinner spinner-8">
      <div class="ms-circle1 ms-child"></div>
      <div class="ms-circle2 ms-child"></div>
      <div class="ms-circle3 ms-child"></div>
      <div class="ms-circle4 ms-child"></div>
      <div class="ms-circle5 ms-child"></div>
      <div class="ms-circle6 ms-child"></div>
      <div class="ms-circle7 ms-child"></div>
      <div class="ms-circle8 ms-child"></div>
      <div class="ms-circle9 ms-child"></div>
      <div class="ms-circle10 ms-child"></div>
      <div class="ms-circle11 ms-child"></div>
      <div class="ms-circle12 ms-child"></div>
    </div>
  </div>

  <div class="ms-lock-screen-weather">
      <p></p>
      <p>Usulután, ES</p>
      <p></p>
  </div>

  <div class="ms-auth-container">
    <div class="ms-auth-col">
      <div class="ms-auth-bg">
        <br><br><br><br><br>
        <?php
          if(sizeof($anuncios) > 0){
        ?>
          <div class="col-md-8 pt-5"  style="display:flex; margin: 0 auto; justify-content: center; align-items: center">
            <div id="dottedSlider" class="ms-dotted-indicator-slider mt-5 carousel slide" data-ride="carousel">
              
              <ol class="carousel-indicators">
                <?php
                  $flag = 0;
                  foreach ($anuncios as $row) {
                    if($flag == 0){
                      echo '<li data-target="#dottedSlider" data-slide-to="" class="active"></li>';
                    }else{
                      echo '<li data-target="#dottedSlider" data-slide-to="'.$flag.'"></li>';
                    }
                    $flag++;
                  }
  
                ?>
                
              </ol>
  
              <div class="carousel-inner">
                <?php
                  $flag = 0;
                  foreach ($anuncios as $row) {
                    if($flag == 0){
                      echo '<div class="carousel-item active">
                              <img class="d-block w-100" src="'.base_url().'public/img/anuncios/'.$row->tituloAnuncio.'.png" alt="First slide">
                            </div>';
                    }else{
                      echo '<div class="carousel-item">
                              <img class="d-block w-100" src="'.base_url().'public/img/anuncios/'.$row->tituloAnuncio.'.png" alt="Second slide">
                            </div>';
                    }
                    $flag++;
                  }
  
                ?>
  
              </div>
  
              <a class="carousel-control-prev" href="#dottedSlider" role="button" data-slide="prev">
                <span class="material-icons" aria-hidden="true">keyboard_arrow_left</span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#dottedSlider" role="button" data-slide="next">
                <span class="material-icons" aria-hidden="true">keyboard_arrow_right</span>
                <span class="sr-only">Next</span>
              </a>
  
            </div>
          </div>
        <?php
          }
        ?>

      </div>
    </div>

    <div class="ms-auth-col">
      <div class="ms-auth-form">
          <form class="needs-validation" id="frmLogin" method="post" action="<?php echo base_url() ?>Home/validar_usuario" novalidate="">
            <div class=" text-center"><img src="<?php echo base_url(); ?>public/img/logo.png" width="350" class="mb-3"/></div>
            
            <p class="text-info mb-4 mt-2 text-center"> Por favor ingresa tus datos de usuarios </p>

            <div class="mb-4">
                <!-- <label for="nombreUsuario" class="text-info">Nombre de usuario</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="flaticon-user"></i></span>
                    </div>
                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" placeholder="Ingresa tu nombre de usuario" required>
                    <div class="invalid-feedback">
                        Ingresa tu nombre de usuario.
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <!-- <label for="psUsuario" class="text-info">Contraseña</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="flaticon-security"></i></span>
                    </div>
                    <input type="password" class="form-control" id="psUsuario" name="psUsuario" placeholder="Ingresa tu contraseña de usuario" required>
                    <div class="invalid-feedback">
                        Ingresa tu contraseña.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button class="btn btn-primary mt-4 w-50" type="submit">Ingresar</button>
            </div>

            
          </form>
      </div>
    </div>
    
  </div>

  <div class="ms-lock-screen-time">
      <p><?php //echo $hora; ?></p>
      <p class="text-primary bold"><?php echo $fecha; ?></p>
  </div>

  
  <footer class="footer mt-auto py-2 text-center">
    <div class="container">
      <span class="text-white"> Copyright © <span class="text-white"><?php echo date("Y"); ?></span> <a href="javascript:void(0);" class="text-white font-weight-bold">Hospital Orellana</a>.
          Creado con <span class="fa fa-heart text-success"></span> por <a href="javascript:void(0);">
          <span class="font-weight-bold text-white text-decoration-underline">Edwin Orantes</span>
        </a> Todos los derechos reservados.
      </span>
    </div>
  </footer>

</body>

</html>

<!-- SCRIPTS -->
<!-- Global Required Scripts Start -->

<script src="<?php echo base_url(); ?>public/tema/assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/tema/assets/js/perfect-scrollbar.js"></script>
<script src="<?php echo base_url(); ?>public/tema/assets/js/jquery-ui.min.js"></script>
<!-- Global Required Scripts End -->

<script src="<?php echo base_url(); ?>public/tema/assets/js/framework.js"></script>
<!-- Settings -->
<script src="<?php echo base_url(); ?>public/tema/assets/js/settings.js"></script>

<script src="<?php echo base_url(); ?>public/tema/assets/js/toastr.min.js" type="text/javascript"></script>