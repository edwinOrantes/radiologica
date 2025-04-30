<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Usuarios extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Usuarios_Model");
        $this->load->model("Hoja_Model");
        $this->load->model("Gastos_Model");
        $this->load->model("Reportes_Model");
        $this->load->model("Herramientas_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

	public function index(){
		/* $this->load->view('Base/header');
		$this->load->view('Base/content');
		$this->load->view('Base/footer'); */
		echo "Sos un intruso...";
	}

    public function gestion_usuarios(){
        $data["empleados"] = $this->Usuarios_Model->obtenerEmpleados();
        $data["accesos"] = $this->Usuarios_Model->obtenerAccesos();
        $data["usuarios"] = $this->Usuarios_Model->obtenerUsuarios();
        $this->load->view('Base/header');
		$this->load->view('Usuarios/gestion_usuarios', $data);
		$this->load->view('Base/footer');
    }

    public function guardar_usuario(){
        $datos = $this->input->post();
        $datos["psUsuario"] = md5($datos["psUsuario"]);
        $bool = $this->Usuarios_Model->guardarUsuario($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Usuarios/gestion_usuarios");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Usuarios/gestion_usuarios");
		}
    }

    public function actualizar_usuario(){
        $datos = $this->input->post();
        $datos["psUsuario"] = md5($datos["psUsuario"]);
        $bool = $this->Usuarios_Model->actualizarUsuario($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Usuarios/gestion_usuarios");
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			redirect(base_url()."Usuarios/gestion_usuarios");
		} 
        
    }

    public function eliminar_usuario(){
        $datos = $this->input->post();
        $bool = $this->Usuarios_Model->eliminarUsuario($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Usuarios/gestion_usuarios");
		}else{
			$this->session->set_flashdata("error","Hubo un error al eliminar los datos!");
			redirect(base_url()."Usuarios/gestion_usuarios");
		}

    }

	/* public function validar_usuario(){
		$inputs = $this->input->post();
		$params = array('nombreUsuario' => $inputs['nombreUsuario'], 'psUsuario' => md5($inputs['psUsuario']));
		$datos = $this->Usuarios_Model->validarUsuario($params);
		if ($datos['estado'] == 1) {
			$userName = explode(" ", $datos["datos"]->nombreEmpleado);
			$lastName = explode(" ", $datos["datos"]->apellidoEmpleado);
			$ses = array(
				'usuario_h'=> $datos["datos"]->nombreUsuario,
				'id_usuario_h'=> $datos["datos"]->idUsuario,
				'id_empleado_h'=> $datos["datos"]->idEmpleado,
				'acceso_h'=> $datos["datos"]->idAcceso,
				'empleado_h'=> $userName[0]." ".$lastName[0],
				'valido'=> TRUE,
			);
			$this->session->set_userdata($ses);
			$this->session->set_flashdata("exito", "Bienvenido nuevamente: ".$this->session->userdata('empleado_h')."");
			
			redirect(base_url()."Empleado/");
		}
		else{
			$this->session->set_flashdata("error", "Los datos ingresados son incorrectos");
			redirect(base_url());
		} 


			
	} */
	
	public function cerrarSesion(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function dashboard(){
		$i = "";
		$f = "";
		$dias_transcurridos = 0;
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$datos = $this->input->post();
			if($datos ["hojaInicio"] <= $datos ["hojaFin"]){
				$i = $datos ["hojaInicio"];
				$f = $datos ["hojaFin"];
				
				$dias = (strtotime($i)-strtotime($f))/86400;
				$dias = abs($dias); $dias = floor($dias);
				$dias_transcurridos = $dias+1;
			}else{
				$this->session->set_flashdata("error", "La fecha inicial no puede ser mayor que la final...");
				redirect(base_url()."Usuarios/dashboard/");
			}
		}else{
			$anio = date("Y");
			$mes = date("m");
			$i = $anio."-".$mes."-01";
			$f = $anio."-".$mes."-31";
			
			$dia  = date('d');
			if($dia != "01"){
				$fecha_base = $anio."-".$mes."-01"; // Fecha base
				$fecha_actual = date("Y-m-d");
				//$fecha_ayer = date("Y-m-d",strtotime($fecha_actual."- 1 days"));
				
				if($dia == "02"){
					$dias = 1;				
				}else{
					$dias = (strtotime($fecha_base)-strtotime($fecha_actual))/86400;
					$dias = abs($dias); $dias = floor($dias);
				}
				$dias_transcurridos = $dias;
			}

		}

		$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');

		$hojas = $this->Usuarios_Model->obtenerHojas($i, $f); //Tomando como base los correlativos de salida.
		
		// echo json_encode($hojas);

		
		$totalIngresos = 0;
		$totalAmbulatorio = 0;

		// Sacando total es de examenes
			// $examenes = $this->Usuarios_Model->obtenerHojas($i, $f);
			/* $totalHoja = 0;
			$totalLabA = 0;
			$totalRXA = 0;
			$totalUltrasA = 0;
			$totalLabI = 0;
			$totalRXI = 0;
			$totalUltrasI = 0;
			$totalNeto = 0;
			$totalHemodialisisI = 0;
			$totalHemodialisisA = 0;
			foreach ($hojas as $hoja) {
				if($hoja->anulada == 0 && $hoja->correlativoSalidaHoja > 0){
					$insumos = $this->Reportes_Model->insumosHoja($hoja->idHoja);
					
					foreach ($insumos as $insumo) {
						$totalHoja += ($insumo->cantidadInsumo * $insumo->precioInsumo);
					}
					// Sacando tatales de RX, Laboratorio y ultras
					$examenes = $this->Reportes_Model->examenesHoja($hoja->idHoja);
						if($hoja->tipoHoja != "Ingreso"){
							foreach ($examenes as $examen) {
								switch ($examen->pivoteMedicamento) {
									case '1':
										$totalLabA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '2':
										$totalRXA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '3':
										$totalUltrasA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '4':
										$totalHemodialisisA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
								}
							}

						}else{
							foreach ($examenes as $examen) {
								switch ($examen->pivoteMedicamento) {
									case '1':
										$totalLabI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '2':
										$totalRXI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '3':
										$totalUltrasI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '4':
										$totalHemodialisisI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
								}
							}
						}
				}
			} */
			
			$data["totalLabA"] = $hojas->laboratorio_ambulatorio;
			$data["totalRXA"] = $hojas->rx_ambulatorio;
			$data["totalUltrasA"] = $hojas->usg_ambulatorio;
			$data["totalHemodialisisA"] = $hojas->hemo_ambulatorio;

			$data["totalLabI"] = $hojas->laboratorio_ingreso;
			$data["totalRXI"] = $hojas->rx_ingresos;
			$data["totalUltrasI"] = $hojas->usg_ingresos;
			$data["totalHemodialisisI"] = $hojas->hemo_ingresos;
		// Fin sacar total examenes

		// Totales
			$totalIngresos = $hojas->ingreso_ingresos;
			$totalAmbulatorio  = $hojas->ingreso_ambulatorios;
		// Totales

		// Obteniendo ingreso total
			/* foreach ($hojas as $hoja) {
				if($hoja->anulada == 0 && $hoja->correlativoSalidaHoja > 0){
					switch ($hoja->tipoHoja) {
						case 'Ingreso':
							// Detalles de la hoja
								$externosHoja = $this->Hoja_Model->externosHoja($hoja->idHoja);
								$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
								foreach ($medicamentosHoja as $medicamento) {
									$totalIngresos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
								}
							break;
						case 'Ambulatorio':
							// Detalles de la hoja
								$externosHoja = $this->Hoja_Model->externosHoja($hoja->idHoja);
								$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
								foreach ($medicamentosHoja as $medicamento) {
									$totalAmbulatorio += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								}
							break;
						default:
							echo "Nel perrin, aqui no hay nada";
							break;
					}
				}
			} */
			
		
		// Listado de gastos
			$totalGastos = 0;
			$listaGastos = $this->Gastos_Model->totalGastos($i, $f);
			$totalGastos = $listaGastos->total;
		// Total facturado
			$facturadoMes = $this->Herramientas_Model->facturadoMes($i, $f);
			$data["facturado"] = $facturadoMes->facturado;

		// Fin facturacion mensual
		// Facturacion diaria
			$hoy = date('Y-m-d');
			$facturadoHoy = $this->Herramientas_Model->facturacionDiaria($hoy);
			if($facturadoHoy->facturado == NULL){
				$data["facturadoD"] = 0;
			}else{
				$data["facturadoD"] = $facturadoHoy->facturado;
			}
		// Fin facturacion diaria
		
		if($dias_transcurridos == 0){
			$data["ingreso_promedio"] = 0;
		}else{
			$data["ingreso_promedio"] = (($totalIngresos + $totalAmbulatorio)/$dias_transcurridos);
		}
		
		$data["i"] = $i;
		$data["f"] = $f;
		$data["totalIngresos"] = $totalIngresos;
		$data["totalAmbulatorio"] = $totalAmbulatorio;
		$data["totalGastos"] = $totalGastos;
		$data["topMedicos"] = $this->Usuarios_Model->topMedicos($i, $f);
		$data["topMedicamentos"] = $this->Usuarios_Model->topMedicamentos($i, $f);

		// Para graficas
			if($this->input->server('REQUEST_METHOD') == 'POST'){
				$datos = $this->input->post();
				$hoy = $datos ["hojaFin"];
			}else{
				$hoy = date("Y-m-d");
			}
			$fechas = $this->Usuarios_Model->fechasGraficas($hoy);
			$s = 0;
			if(sizeof($fechas) > 0){
				foreach ($fechas as $fecha) {
					$totalMes = 0;
					$data['fechas'][] = $fecha->salidaHoja;
					
					$externosHoja = $this->Usuarios_Model->externosHoja($fecha->salidaHoja);
					$medicamentosHoja = $this->Usuarios_Model->medicamentosHoja($fecha->salidaHoja);


					foreach ($medicamentosHoja as $medicamento) {
						$totalMes += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
					}
					$data['totalMes'][] = round($totalMes, 2);
					
				}
				
				$data['fecha_data'] = json_encode($data['fechas']);
				$data['mes_data'] = json_encode($data['totalMes']);
			}else{
				$data["vacio"] = true;
			}

			for($i = 0; $i < 5; $i++ ){
				$inicio =  date("Y-m-d",mktime(0,0,0,date("m")-$i,date("01"),date("Y")));
				$fin = date("Y-m-t", strtotime($inicio));
				$totalEM = 0;
				$totalMM = 0;
				// Medicamentos por mes
					$medicamentosMes = $this->Usuarios_Model->medicamentosMes($inicio, $fin);

					if(sizeof($medicamentosMes) == 0){
						$data['medicamentosMes'][] = 0;
					}else{
						foreach ($medicamentosMes as $mm) {
							$totalMM += $mm->cantidadInsumo * $mm->precioInsumo;
						}
						$data['medicamentosMes'][] = round($totalMM, 2);
						$totalMM = 0;
					}
				// Externos por mes
					$externosMes = $this->Usuarios_Model->externosMes($inicio, $fin);
					if(sizeof($externosMes) == 0){
						$data['externoMes'][] = 0;
					}else{
						foreach ($externosMes as $em) {
							$totalEM += ($em->cantidadExterno * $em->precioExterno);
						}
						$data['externoMes'][] = round($totalEM, 2);
						$totalEM = 0;
					}
				
			}
		// Para graficas

		$data['medicamentos_data'] = json_encode($data['medicamentosMes']);
		$data['externo_data'] = json_encode($data['externoMes']);
		// Obteniendo los nombres de los meses anteriores
			for($i = 0; $i < 5; $i++ ){
				$inicio =  date("Y-m-d",mktime(0,0,0,date("m")-$i,date("01"),date("Y")));
				$fin = date("Y-m-t", strtotime($inicio));

				$mes = date("m", mktime(0, 0, 0, date("m") - $i, date("01"), date("Y")));
				if($mes < 10){
					$data['arregloMeses'][] = $meses[(substr($mes, 1)-1)];
					//echo (substr($mes, 1)-1)."<br>";
				}else{
					$data['arregloMeses'][] = $meses[$mes-1];

				}

				//echo ($mes-$i)."<br>";
				
			}
			$data['meses_data'] = json_encode($data['arregloMeses']);


		$this->load->view("Base/header");
		$this->load->view("Usuarios/dashboard", $data);
		$this->load->view("Base/footer");

	}

	/* 	public function dashboard(){
		$i = "";
		$f = "";
		$dias_transcurridos = 0;
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$datos = $this->input->post();
			if($datos ["hojaInicio"] <= $datos ["hojaFin"]){
				$i = $datos ["hojaInicio"];
				$f = $datos ["hojaFin"];
				
				$dias = (strtotime($i)-strtotime($f))/86400;
				$dias = abs($dias); $dias = floor($dias);
				$dias_transcurridos = $dias+1;
			}else{
				$this->session->set_flashdata("error", "La fecha inicial no puede ser mayor que la final...");
				redirect(base_url()."Usuarios/dashboard/");
			}
		}else{
			$anio = date("Y");
			$mes = date("m");
			$i = $anio."-".$mes."-01";
			$f = $anio."-".$mes."-31";
			
			$dia  = date('d');
			if($dia != "01"){
				$fecha_base = $anio."-".$mes."-01"; // Fecha base
				$fecha_actual = date("Y-m-d");
				//$fecha_ayer = date("Y-m-d",strtotime($fecha_actual."- 1 days"));
				
				if($dia == "02"){
					$dias = 1;				
				}else{
					$dias = (strtotime($fecha_base)-strtotime($fecha_actual))/86400;
					$dias = abs($dias); $dias = floor($dias);
				}
				$dias_transcurridos = $dias;
			}

		}

		$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');

		$hojas = $this->Usuarios_Model->obtenerHojas($i, $f); //Tomando como base los correlativos de salida.

		$totalIngresos = 0;
		$totalAmbulatorio = 0;
		// Sacando total es de examenes
			// $examenes = $this->Usuarios_Model->obtenerHojas($i, $f);
			$totalHoja = 0;
			$totalLabA = 0;
			$totalRXA = 0;
			$totalUltrasA = 0;
			$totalLabI = 0;
			$totalRXI = 0;
			$totalUltrasI = 0;
			$totalNeto = 0;
			$totalHemodialisisI = 0;
			$totalHemodialisisA = 0;
			foreach ($hojas as $hoja) {
				if($hoja->anulada == 0 && $hoja->correlativoSalidaHoja > 0){
					$insumos = $this->Reportes_Model->insumosHoja($hoja->idHoja);
					
					foreach ($insumos as $insumo) {
						$totalHoja += ($insumo->cantidadInsumo * $insumo->precioInsumo);
					}
					// Sacando tatales de RX, Laboratorio y ultras
					$examenes = $this->Reportes_Model->examenesHoja($hoja->idHoja);
						if($hoja->tipoHoja != "Ingreso"){
							foreach ($examenes as $examen) {
								switch ($examen->pivoteMedicamento) {
									case '1':
										$totalLabA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '2':
										$totalRXA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '3':
										$totalUltrasA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '4':
										$totalHemodialisisA += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
								}
							}

						}else{
							foreach ($examenes as $examen) {
								switch ($examen->pivoteMedicamento) {
									case '1':
										$totalLabI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '2':
										$totalRXI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '3':
										$totalUltrasI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
									case '4':
										$totalHemodialisisI += ($examen->cantidadInsumo * $examen->precioInsumo);
										break;
								}
							}
						}
				}
			}
			
			$data["totalLabA"] = $totalLabA;
			$data["totalRXA"] = $totalRXA;
			$data["totalUltrasA"] = $totalUltrasA;
			$data["totalLabI"] = $totalLabI;
			$data["totalRXI"] = $totalRXI;
			$data["totalUltrasI"] = $totalUltrasI;
			$data["totalHemodialisisI"] = $totalHemodialisisI;
			$data["totalHemodialisisA"] = $totalHemodialisisA;
		// Fin sacar total examenes
		// Obteniendo ingreso total
			foreach ($hojas as $hoja) {
				if($hoja->anulada == 0 && $hoja->correlativoSalidaHoja > 0){
					switch ($hoja->tipoHoja) {
						case 'Ingreso':
							// Detalles de la hoja
								$externosHoja = $this->Hoja_Model->externosHoja($hoja->idHoja);
								$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
								foreach ($medicamentosHoja as $medicamento) {
									$totalIngresos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
								}
							break;
						case 'Ambulatorio':
							// Detalles de la hoja
								$externosHoja = $this->Hoja_Model->externosHoja($hoja->idHoja);
								$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
								foreach ($medicamentosHoja as $medicamento) {
									$totalAmbulatorio += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								}
							break;
						default:
							echo "Nel perrin, aqui no hay nada";
							break;
					}
				}
			}
			
		
		// Listado de gastos
			$totalGastos = 0;
			$listaGastos = $this->Gastos_Model->obtenerGastos($i, $f);
			foreach ($listaGastos as $gasto) {
				$totalGastos += $gasto->montoGasto;
			}
		// Total facturado
			$facturadoMes = $this->Herramientas_Model->facturadoMes($i, $f);
			if($facturadoMes->facturado == NULL){
				$data["facturado"] = 0;
			}else{
				$data["facturado"] = $facturadoMes->facturado;
			}
		// Fin facturacion mensual
		// Facturacion diaria
			$hoy = date('Y-m-d');
			$facturadoHoy = $this->Herramientas_Model->facturacionDiaria($hoy);
			if($facturadoHoy->facturado == NULL){
				$data["facturadoD"] = 0;
			}else{
				$data["facturadoD"] = $facturadoHoy->facturado;
			}
		// Fin facturacion diaria
		
		if($dias_transcurridos == 0){
			$data["ingreso_promedio"] = 0;
		}else{
			$data["ingreso_promedio"] = (($totalIngresos + $totalAmbulatorio)/$dias_transcurridos);
		}
		
		$data["i"] = $i;
		$data["f"] = $f;
		$data["totalIngresos"] = $totalIngresos;
		$data["totalAmbulatorio"] = $totalAmbulatorio;
		$data["totalGastos"] = $totalGastos;
		$data["topMedicos"] = $this->Usuarios_Model->topMedicos($i, $f);
		$data["topMedicamentos"] = $this->Usuarios_Model->topMedicamentos($i, $f);
		// Para graficas

			if($this->input->server('REQUEST_METHOD') == 'POST'){
				$datos = $this->input->post();
				$hoy = $datos ["hojaFin"];
			}else{
				$hoy = date("Y-m-d");
			}
			$fechas = $this->Usuarios_Model->fechasGraficas($hoy);
			$s = 0;
			if(sizeof($fechas) > 0){
				foreach ($fechas as $fecha) {
					$totalMes = 0;
					$data['fechas'][] = $fecha->salidaHoja;
					
					$externosHoja = $this->Usuarios_Model->externosHoja($fecha->salidaHoja);
					$medicamentosHoja = $this->Usuarios_Model->medicamentosHoja($fecha->salidaHoja);
	
					foreach ($medicamentosHoja as $medicamento) {
						$totalMes += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
					}
					$data['totalMes'][] = round($totalMes, 2);
				}
				
				$data['fecha_data'] = json_encode($data['fechas']);
				$data['mes_data'] = json_encode($data['totalMes']);
			}else{
				$data["vacio"] = true;
			}

			for($i = 0; $i < 5; $i++ ){
				$inicio =  date("Y-m-d",mktime(0,0,0,date("m")-$i,date("01"),date("Y")));
				$fin = date("Y-m-t", strtotime($inicio));
				$totalEM = 0;
				$totalMM = 0;
				// Medicamentos por mes
					$medicamentosMes = $this->Usuarios_Model->medicamentosMes($inicio, $fin);

					if(sizeof($medicamentosMes) == 0){
						$data['medicamentosMes'][] = 0;
					}else{
						foreach ($medicamentosMes as $mm) {
							$totalMM += $mm->cantidadInsumo * $mm->precioInsumo;
						}
						$data['medicamentosMes'][] = round($totalMM, 2);
						$totalMM = 0;
					}
				// Externos por mes
					$externosMes = $this->Usuarios_Model->externosMes($inicio, $fin);
					if(sizeof($externosMes) == 0){
						$data['externoMes'][] = 0;
					}else{
						foreach ($externosMes as $em) {
							$totalEM += ($em->cantidadExterno * $em->precioExterno);
						}
						$data['externoMes'][] = round($totalEM, 2);
						$totalEM = 0;
					}
				
			}
		$data['medicamentos_data'] = json_encode($data['medicamentosMes']);
		$data['externo_data'] = json_encode($data['externoMes']);
		// Obteniendo los nombres de los meses anteriores
			for($i = 0; $i < 5; $i++ ){
				$inicio =  date("Y-m-d",mktime(0,0,0,date("m")-$i,date("01"),date("Y")));
				$fin = date("Y-m-t", strtotime($inicio));

				$mes = date("m", mktime(0, 0, 0, date("m") - $i, date("01"), date("Y")));
				if($mes < 10){
					$data['arregloMeses'][] = $meses[(substr($mes, 1)-1)];
					//echo (substr($mes, 1)-1)."<br>";
				}else{
					$data['arregloMeses'][] = $meses[$mes-1];

				}

				//echo ($mes-$i)."<br>";
				
			}
			$data['meses_data'] = json_encode($data['arregloMeses']);


		$this->load->view("Base/header");
		$this->load->view("Usuarios/dashboard", $data);
		$this->load->view("Base/footer");

	} */

	public function dias_anteriores($i, $f){
		$anio = date('Y');
		$mes  = date('m');
		$dia  = date('d');
		if($dia != "01"){
			$fecha_base = $anio."-".$mes."-01"; // Fecha base
			$fecha_actual = date("Y-m-d");
			//$fecha_ayer = date("Y-m-d",strtotime($fecha_actual."- 1 days"));
			
			if($dia == "02"){
				$dias = 1;				
			}else{
				$dias = (strtotime($fecha_base)-strtotime($fecha_actual))/86400;
				$dias = abs($dias); $dias = floor($dias);
			}
			return $dias;
		}

	}
       
}
