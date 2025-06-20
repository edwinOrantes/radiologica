<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="<?php echo base_url(); ?>public/tema/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/font-awesome/css/all.min.css" rel="stylesheet">

    <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>public/tema/favicon.ico">

  
  <script src="<?php echo base_url(); ?>public/tema/assets/js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo base_url(); ?>public/tema/assets/js/popper.min.js"></script>
  <link href="<?php echo base_url(); ?>public/tema/assets/css/toastr.min.css" rel="stylesheet">

    <title>Clínica Radiologica</title>
    <style>
      /* =======================================================================
      Template Name: Youtubers
      Author:  SmartEye Technologies
      Author URI: www.smarteyeapps.com
      Version: 1.0
      coder name:Prabin Raja
      Description: This Template is created for Youtubers
      ======================================================================= */
      /* ===================================== Import Variables ================================== */
      @import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700);
      @import url(https://fonts.googleapis.com/css?family=Arimo:300,400,400italic,700,700italic);
      /* ===================================== Basic CSS ==================================== */
      * {
        margin: 0px;
        padding: 0px;
        list-style: none; 
      }

      body{
        font-family: "Open Sans";
      }

      img {
        max-width: 100%; 
      }

      a {
        text-decoration: none;
        outline: none;
        color: #444; 
      }

      a:hover {
        color: #444; 
      }

      ul {
        margin-bottom: 0;
        padding-left: 0; 
      }

      ol,ul{
        margin:0px;
        padding:0px;
      }

      a:hover,
      a:focus,
      input,
      textarea {
        text-decoration: none;
        outline: none; 
      }

        .container {
            max-width: 600px; /* Establece un ancho máximo para el contenido */
            width: 100%;
            padding: 0 15px; /* Añade un poco de espacio en los bordes */
        }

      .form-02-main{
        background:url(public/img/bg-01.png);
        background-size:cover;
        background-repeat:no-repeat;
        background-position:center;
        height: 100vh;
        position:relative;
        z-index:2;
        overflow:hidden;
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        align-items: center; /* Centra verticalmente */
        height: 100vh; /* Establece la altura al 100% del viewport */
      }


      .form-03-main{
        width:500px;
        display:block;
        margin:20px auto;
        padding:25px 50px 25px;
        background:rgba(255,255,255,0.8);
        border-radius:6px;
        z-index:9;
      }

      .logo{
        display:block;
        margin:20px auto;
        width:230px;
        height:130px;
      }

      .form-group{
        padding:20px 0px;
        display:inline-block;
        width:100%;
        position:relative;
      }

      .form-group p{
        margin:0px;
      }

      .form-control{
        min-height:45px;
        -webkit-box-shadow: none;
        box-shadow: none;
        padding: 10px 15px;
      }

      .btn-primary{
        background: #1E5CA7;
        border: none;
      }

      .btn-primary:hover{
        background: #1E5CA7;
      }
    </style>

  <?php if($this->session->flashdata("error")):?>
    <script type="text/javascript">
      $(document).ready(function(){
      toastr.remove();
      toastr.options.positionClass = "toast-top-center";
      toastr.warning('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
      });
    </script>
  <?php endif; ?>

  </head>
  <body>
    <section class="form-02-main">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="_lk_de">
              <form class="needs-validation" id="frmLogin" method="post" action="<?php echo base_url() ?>Home/validar_usuario">
                <div class="form-03-main">
                  <div class="logo">
                    <img src="<?php echo base_url(); ?>public/img/user.png">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingresa tu nombre de usuario" required>
                  </div>
  
                  <div class="form-group">
                    <input type="password" class="form-control" id="psUsuario" name="psUsuario" placeholder="Ingresa tu contraseña de usuario" required>
                  </div>
  
                  <div class="form-group">
                    <div class="_btn_04">
                      <button class="btn btn-primary btn-block py-3">Ingresar</button>
                    </div>
                  </div>
  
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>

<script src="<?php echo base_url(); ?>public/tema/assets/js/toastr.min.js" type="text/javascript"></script>