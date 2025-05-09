<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta extends CI_Controller {
	private $establecimiento;

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Medico_Model");
		$this->load->model("Consulta_Model");
		$this->load->model("Facturacion_Model");
		$this->establecimiento = "M001";
	}

	public function index(){}

	public function agregar_paciente(){
		$data["medicos"] = $this->Medico_Model->obtenerMedicos();
		$this->load->view('Base/header');
		$this->load->view('Consulta/agregar_paciente', $data);
		$this->load->view('Base/footer');
		
	}

	public function guardar_paciente(){
		$datos =$this->input->post();
		$bool = $this->Consulta_Model->guardarConsulta($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Consulta/detalle_consulta/".$bool."/"); // Redirigiendo al siguiente paso
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Consulta/agregar_paciente");
		}
		// echo json_encode($datos);
		
	}

	public function detalle_consulta($consulta = null){

		// Facturacion electronica
			$tipo = 1;
			$anio = date("Y");
			$strDte = $this->obtener_dte($tipo, $anio);
			$partesDTE = explode('.', $strDte);
			$data["dte"] = $partesDTE[0];
			$data["baseDTE"] = $partesDTE[1];
			$data["cGeneracion"] = $this->codigo_generacion();
		// Facturacion electronica

		$data["consulta"] = $this->Consulta_Model->detalleConsulta($consulta);
		$data["examenes"] = $this->Consulta_Model->listaExamenes();
		$data["codigo"] = $this->rellenar_codigo($data["consulta"] ->idConsulta);

		$this->load->view('Base/header');
		$this->load->view('Consulta/detalle_consulta', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["codigo"]);
	}

	public function buscar_examen(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Consulta_Model->buscarExamen($datos["str"]);
			print json_encode($resp);
		}else{
			echo "Error";
		}
	}

	public function agregar_a_consulta(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$strExamen = $datos["str"];
			$resp = $this->Consulta_Model->busquedaDetalleExamen($strExamen, $datos["referencia"]);
			print json_encode($resp);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

	public function obtener_numero_factura(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Consulta_Model->obtenerNumeroFactura($datos["pivote"]);
			print json_encode($resp);
		}
	}


	// Facturacion electronica
		public function obtener_dte($tipo, $anio){
			$str = "DTE-";
			$strTipo = $this->agregar_ceros($tipo, 2);
			$str = $str.$strTipo."-".$this->establecimiento."P001-";
			$numeroBase = 0;
			$obtenerNumero = $this->Facturacion_Model->validarDTE($tipo, $anio);
			if($obtenerNumero->actual == 0){
				$numeroBase = 1;
			}else{
				$numeroBase = $obtenerNumero->actual + 1;
			}
			$codigo = $this->agregar_ceros($numeroBase, 15);

			$str = $str.$codigo;

			return $str.".".$numeroBase;
			
		}

		private function agregar_ceros($numero, $longitud) {
			return str_pad($numero, $longitud, '0', STR_PAD_LEFT);
		}

		private function codigo_generacion() {   
			// Generar 16 bytes aleatorios
			$bytesAleatorios = random_bytes(16);
			
			// Convertir a formato hexadecimal y en mayúsculas
			$hex = strtoupper(bin2hex($bytesAleatorios));
			
			// Insertar los guiones para cumplir con el formato UUID v4
			$uuid = substr($hex, 0, 8) . '-' .
					substr($hex, 8, 4) . '-' .
					substr($hex, 12, 4) . '-' .
					substr($hex, 16, 4) . '-' .
					substr($hex, 20, 12);
			
			// Establecer los bits de la versión (UUID v4)
			$uuid[14] = '4'; // Versión 4
			$uuid[19] = strtoupper(dechex((hexdec($uuid[19]) & 0x3) | 0x8)); // Variant (10xx)
			
			// Convertir a mayúsculas nuevamente (por si alguna minúscula quedó en la manipulación)
			$uuid = strtoupper($uuid);
			return $uuid;
		}
	// Facturacion electronica


	private function rellenar_codigo($numero = null){
		if ($numero < 1000) {
			return str_pad($numero, 4, "0", STR_PAD_LEFT);
		} else {
			return (string)$numero;
		}
	}
	
}
