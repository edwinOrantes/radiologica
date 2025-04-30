<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hospital Orellana</title>
    <!-- Iconic Fonts -->
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/font-awesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/flat-icons/flaticon.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/cryptocoins/cryptocoins.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/tema/vendors/iconic-fonts/cryptocoins/cryptocoins-colors.css">
    
    <link href="<?php echo base_url(); ?>public/tema/assets/css/select2.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/tema/assets/css/select2-bootstrap4.min.css" rel="stylesheet">
    <!-- jQuery UI -->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/jquery-ui.min.css" rel="stylesheet">
    <!-- Page Specific CSS (Slick Slider.css) -->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/slick.css" rel="stylesheet">
    <!-- medjestic styles -->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/style.css" rel="stylesheet">
    <!-- Page Specific CSS (Morris Charts.css) -->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/morris.css" rel="stylesheet">
    <!-- Page Specific Css (Datatables.css) -->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/datatables.min.css" rel="stylesheet">

    <!-- Notificaciones Toast-->
    <link href="<?php echo base_url(); ?>public/tema/assets/css/toastr.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>public/tema/assets/css/animate.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url(); ?>public/js/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/js/timepicker/jquery.timepicker.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>public/js/fullcalendar/lib/main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/highcharts/css/style.css">

    <!-- Favicon -->
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>public/tema/favicon.ico">
    <script src="<?php echo base_url(); ?>public/tema/assets/js/jquery-3.3.1.min.js"></script>

    <!-- Scripts para highcharts -->
    <script src="<?php echo base_url(); ?>public/highcharts/highcharts.js"></script>
    <script src="<?php echo base_url(); ?>public/highcharts/modules/exporting.js"></script>
    <script src="<?php echo base_url(); ?>public/highcharts/modules/export-data.js"></script>
    <script src="<?php echo base_url(); ?>public/highcharts/modules/accessibility.js"></script>

    <script src="<?php echo base_url(); ?>public/tema/assets/js/slick.min.js"></script>
	<script src="<?php echo base_url(); ?>public/tema/assets/js/moment.js"></script>
	<script src="<?php echo base_url(); ?>public/tema/assets/js/jquery.webticker.min.js"></script>
	<script src="<?php echo base_url(); ?>public/tema/assets/js/Chart.bundle.min.js"></script>
	<script src="<?php echo base_url(); ?>public/tema/assets/js/Chart.Financial.js"></script>
	<!--<script src="<?php echo base_url(); ?>public/tema/assets/js/index-chart.js"></script>-->

    <!-- <style>
        body, a, a:hover {cursor: url(http://cur.cursors-4u.net/holidays/hol-3/hol280.cur), progress;}
    </style> -->

</head>



<body class="ms-body ms-aside-left-open ms-primary-theme ms-has-quickbar">
    <!-- Ancla subir -->
		<div class="ms-toggler2 ms-settings-toggle2 ms-d-block-lg" id="hastaInicio">
			<i class="fa fa-arrow-up"></i>
		</div>
    <!-- Setting Panel -->
        <!-- <div class="ms-toggler ms-settings-toggle ms-d-block-lg">
            <i class="flaticon-gear"></i>
        </div>
        <div class="ms-settings-panel ms-d-block-lg">
            <div class="row">
                <div class="col-xl-4 col-md-4">
                    <h4 class="section-title">Personalizar</h4>
                    <div>
                        <label class="ms-switch">
                            <input type="checkbox" id="dark-mode">
                            <span class="ms-switch-slider round"></span>
                        </label>
                        <span> Modo oscuro </span>
                    </div>

                </div>
                <div class="col-xl-4 col-md-4">
                    <h4 class="section-title">Keyboard Shortcuts</h4>
                    <p class="ms-directions mb-0"><code>Esc</code> Close Quick Bar</p>
                    <p class="ms-directions mb-0"><code>Alt + (1 -> 6)</code> Open Quick Bar Tab</p>
                    <p class="ms-directions mb-0"><code>Alt + Q</code> Enable Quick Bar Configure Mode</p>
                </div>
            </div>
        </div> -->
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
    <!-- Overlays -->
    <div class="ms-aside-overlay ms-overlay-left ms-toggler" data-target="#ms-side-nav" data-toggle="slideLeft"></div>
    <div class="ms-aside-overlay ms-overlay-right ms-toggler" data-target="#ms-recent-activity" data-toggle="slideRight"></div>
    <!-- Sidebar Navigation Left -->
    <aside id="ms-side-nav" class="side-nav fixed ms-aside-scrollable ms-aside-left">
        <!-- Logo -->
        <div class="logo-sn ms-d-block-lg">
            <!-- <a class="pl-0 ml-0 text-center" href="<?php echo base_url(); ?>"> <img src="<?php echo base_url() ?>public/img/logo_web.png" alt="logo"> </a> -->
             <br> <br>
                <?php
                    switch ($this->session->userdata('acceso_h')) {
                        case '1':
                            echo '<a href="'.base_url().'Usuarios/dashboard" class="text-center ms-logo-img-link">';
                            break;
                        case '5':
                            echo '<a href="'.base_url().'Usuarios/dashboard" class="text-center ms-logo-img-link">';
                            break;
                        case '8':
                            echo '<a href="'.base_url().'Paciente/agregar_pacientes/" class="text-center ms-logo-img-link">';
                            break;
                        case '7':
                            echo '<a href="'.base_url().'Paciente/agregar_pacientes/" class="text-center ms-logo-img-link">';
                            break;
                        case '9':
                            echo '<a href="'.base_url().'Usuarios/dashboard" class="text-center ms-logo-img-link">';
                            break;
                        case '14':
                            echo '<a href="'.base_url().'Paciente/agregar_pacientes/" class="text-center ms-logo-img-link">';
                            break;

                        default:
                            echo '<a href="#" class="text-center ms-logo-img-link">';
                            break;
                    }
                ?>
                <!-- <img src="<?php echo base_url() ?>public/img/logo_circle.png" alt="Logo Hospital Orellana"> -->
                <img src="<?php echo base_url().'public/img/users/'.$this->session->userdata('imagen').'.png' ?>" alt="<?php echo $this->session->userdata('empleado_h'); ?>"> 
            </a>
            <h5 class="text-center text-white mt-2"><?php echo $this->session->userdata("empleado_h"); ?></h5>
            <h6 class="text-center text-white mb-3"><?php echo $this->session->userdata("acceso_nombre"); ?></h6>
        </div>

        <div>
            <?php
                if($this->session->userdata('acceso_h') != 14){
            ?>
            <form action="<?php echo base_url(); ?>Hoja/mover_a_hoja/" method="POST">
				<div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-file"></i></div>
					</div>
                    <input type="text" class="form-control" placeholder="Código de hoja" name="codigoHoja">
				</div>
			</form>
            <?php } ?>
        </div>
        <!-- Navigation -->
        <ul class="accordion ms-main-aside fs-14" id="side-nav-accordion">
            <?php
                if($this->session->userdata("acceso_h") == 1){
            ?>
            <!-- Dashboard -->
                <li class="menu-item">
                    <a href="#" class="has-chevron" data-toggle="collapse" data-target="#dashboard" aria-expanded="false"
                        aria-controls="dashboard">
                        <span><i class="fa fa-th-large"></i>Dashboard</span>
                    </a>
                    <ul id="dashboard" class="collapse" aria-labelledby="dashboard" data-parent="#side-nav-accordion">
                        <li> <a href="<?php echo base_url(); ?>Usuarios/dashboard">General</a> </li>
                        <li> <a href="<?php echo base_url(); ?>Laboratorio/dashboard_laboratorio">Laboratorio</a> </li>
                    </ul>
                </li>
            <!-- Dashboard -->
            <?php } ?>

            <?php
                
                $conn = mysqli_connect("localhost", "root", "", "db_hospital_orellana");
                // $conn = mysqli_connect("192.168.1.253", "ho", "ho24...", "db_hospital_orellana");
                if (!$conn){die("Connection failed: " . mysqli_connect_error());}
                else{
                    mysqli_query($conn, "SET CHARACTER SET 'utf8'");
                    $acceso = $this->session->userdata("acceso_h");
                    $consulta = "SELECT m.htmlMenu FROM tbl_permisos as p INNER JOIN tbl_accesos as a ON(p.idAcceso = a.idAcceso)
                    INNER JOIN tbl_menu as m ON(p.idMenu = m.idMenu) WHERE p.idAcceso = '$acceso' AND p.estadoPermiso = '1' ORDER BY p.idMenu ASC";
                    $datos =  mysqli_query( $conn, $consulta);  
                    $a = base_url();
                    while($item = mysqli_fetch_array($datos)){
				        //echo $item["htmlMenu"];
				        echo str_replace("<?php echo base_url(); ?>", "$a", $item["htmlMenu"]);
                    }
                }
                mysqli_close($conn);
            ?>
        </ul>

    </aside>

    <!-- Main Content -->
    <main class="body-content">
        <!-- Navigation Bar -->
        <nav class="navbar ms-navbar" id="navidad">
            <div class="ms-aside-toggler ms-toggler pl-0" data-target="#ms-side-nav" data-toggle="slideLeft">
                <span class="ms-toggler-bar bg-white"></span>
                <span class="ms-toggler-bar bg-white"></span>
                <span class="ms-toggler-bar bg-white"></span>
            </div>
            <div class="logo-sn logo-sm ms-d-block-sm">
                <a class="pl-0 ml-0 text-center navbar-brand" href="#"><img src="<?= base_url()?>public/img/mini_logo.png" alt="logo"> </a>
            </div>

            <div>
                
                <?php
                    /* if(date('m') == 12 && date('d') == 25){
                        echo '<div id="marzo">
                                <p>
                                🎄 Feliz Navidad 🎄
                                    <span>
                                        '.$this->session->userdata("usuario_h").'
                                    </span>
                                </p>
                            </div>';
                    }else{
                        echo '<a class="pl-0 ml-0 text-center navbar-brand mr-0"><img src="'.base_url().'public/img/logo_celebracion.png" alt="logo"> </a>';
                    } */
                
                ?>

            <div id="marzo">
                <?php
                    // Array con las frases
                    $frases = [
                        "Brillas sin sombras.",
                        "Imparable cada día.",
                        "Creas tu historia.",
                        "Invencible por naturaleza.",
                        "Fuerza sin medida.",
                        "Poder sin límites.",
                        "Transformas el mundo.",
                        "Única en esencia.",
                        "Luz que trasciende.",
                        "Grandeza en acción."
                    ];

                    // Array con los emojis de flores
                    $flores = [
                        "🌸", // Flor de cerezo
                        "💐", // Ramo de flores
                        "🌺", // Hibisco
                        "🌻", // Girasol
                        "🌷", // Tulipán
                        "🌼", // Flor
                        "🌹"  // Rosa
                    ];

                    // Verificar si ya hay una frase en la sesión, si no, asignar una aleatoria
                    if (!$this->session->userdata("frase_usuario")) {
                        $this->session->set_userdata("frase_usuario", $frases[array_rand($frases)]);
                    }

                    // Verificar si ya hay un emoji de flor en la sesión, si no, asignar uno aleatorio
                    if (!$this->session->userdata("flor_usuario")) {
                        $this->session->set_userdata("flor_usuario", $flores[array_rand($flores)]);
                    }

                    $fraseSesion = $this->session->userdata("frase_usuario");
                    $florSesion = $this->session->userdata("flor_usuario");

                    if(date('m') == "03") {
                        if($this->session->userdata("celebrar") == 1) {
                            echo '<p>
                                ' . $florSesion . ' ' . $fraseSesion . ' ' . $florSesion . '
                                <span>
                                    ' . $this->session->userdata("usuario_h") . '
                                </span>
                            </p>';
                        } else {
                            echo '<p>
                                🌹 Marzo 08 🌹
                                <span>
                                    Día de la mujer
                                </span>
                            </p>';
                        }
                    }
                ?>
            </div>

                    
                    
            </div>


            <ul class="ms-nav-list ms-inline mb-0" id="ms-nav-options">

                <li class="ms-nav-item ms-nav-user dropdown">
                    <a href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="ms-user-img ms-img-round float-right" src="<?php echo base_url().'public/img/users/'.$this->session->userdata('imagen').'.png' ?>" alt="<?php echo $this->session->userdata('empleado_h'); ?>"> </a>
                    <ul class="dropdown-menu dropdown-menu-right user-dropdown" aria-labelledby="userDropdown">
                        <li class="dropdown-menu-header">
                            <h6 class="dropdown-header ms-inline m-0">
                                <span class="text-disabled"> Bienvenido, <?php echo $this->session->userdata('empleado_h'); ?>
                                </span>
                            </h6>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li class="dropdown-menu-footer">
                            <a class="media fs-14 p-2" href="<?php echo base_url(); ?>Usuarios/cerrarSesion">
                                <span>
                                    <i class="flaticon-shut-down mr-2"></i>
                                    Salir
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="ms-toggler ms-d-block-sm pr-0 ms-nav-toggler" data-toggle="slideDown"
                data-target="#ms-nav-options">
                <span class="ms-toggler-bar bg-white"></span>
                <span class="ms-toggler-bar bg-white"></span>
                <span class="ms-toggler-bar bg-white"></span>
            </div>
        </nav>



<script>

    /* $(document).ready(function(){
        $.ajax({
                url: "obtener_medicamentos",
                success: function(data) {
                registro= eval(data);
                if(registro.length > 0){
                    var notificacion = "";
                    $("#notificationDropdown").show();
                    //console.log(registro.length);
                    for (var i = 0; i < registro.length; i++) {
                        if(registro[i]["tipoMedicamento"] == "Medicamentos" || registro[i]["tipoMedicamento"] == "Materiales médicos"){
                            notificacion += '<a class="media p-2" href="#">';
                            notificacion += '    <div class="media-body">';
                            notificacion += '        <span class="medium">'+registro[i]["nombreMedicamento"]+'</span>';
                            notificacion += '        <p class="fs-10 my-1 text-disabled"><i class="fa fa-play"></i> Stock disponible '+registro[i]["stockMedicamento"]+'</p>';
                            notificacion += '    </div>';
                            notificacion += '</a>';
                        }
                    }
                    $("#notificaciones").append(notificacion);
                    //console.log(notificacion);
                }else{
                    console.log("No hay minimos");
                }
    
                }
            });
    });

    function notificaciones(){
        var contador = 0;
        $.ajax({
            url: "obtener_medicamentos",
            success: function(data) {
            registro= eval(data);
            if(registro.length > 0){
                var notificacion = "";
                $("#notificaciones").html("");
                $("#notificationDropdown").show();
                //console.log(registro.length);
                for (var i = 0; i < registro.length; i++) {
                    if(registro[i]["tipoMedicamento"] == "Medicamentos" || registro[i]["tipoMedicamento"] == "Materiales médicos"){
                        notificacion += '<a class="media p-2" href="#">';
                        notificacion += '    <div class="media-body">';
                        notificacion += '        <span class="medium">'+registro[i]["nombreMedicamento"]+'</span>';
                        notificacion += '        <p class="fs-10 my-1 text-disabled"><i class="fa fa-play"></i> Stock disponible '+registro[i]["stockMedicamento"]+'</p>';
                        notificacion += '    </div>';
                        notificacion += '</a>';
                    }

                }
                $("#notificaciones").append(notificacion);
            }else{
                $("#notificaciones").html("");
                $("#notificationDropdown").hide();
                //console.log("No hay minimos");
            }

            }
        });
    } */

    /* setInterval(function(){ 
    notificaciones();
    }, 5000);  */
</script>