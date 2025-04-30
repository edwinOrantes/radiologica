<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Clases para el reporte en excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet;


class Rx extends CI_Controller {
    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Rx_Model");
	}

	public function index(){
        $hoy = date("Y-m-d");
		$data["pacientes"] = $this->Rx_Model->obtenerPacientes($hoy);
		$this->load->view('Base/header');
		$this->load->view('Rx/lista_pacientes', $data);
		$this->load->view('Base/footer');
		// echo json_encode($data);
    }

	public function saldar_cola(){
        if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$bool = $this->Rx_Model->saldarCola($datos);
			if($bool){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			} 
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
    }

	public function resultados_archivados(){
		$data["archivos"] = $this->Rx_Model->obtenerRxArchivados();
		$this->load->view("Base/header");
		$this->load->view("Rx/rx_archivados", $data);
		$this->load->view("Base/footer");

		// echo json_encode($data["archivos"]);
	}

	public function obtener_codigo(){
        if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$secuencia = 0;
			$codigo = 0;
			$str = "";
			// Obteniendo STR
				switch ($datos["pivote"]) {
					case '1':
						$str = "HO";
						break;
					case '2':
						$str = "BM";
						break;
					case '3':
						$str = "S";
						break;
					
					default:
						$str = "HO";
						break;
				}
			// Obteniendo STR
			
			// Ejecutando consultas
				$resp = $this->Rx_Model->obtenerCodigo($datos);
				if($resp->secuencia == 0){
					$secuencia = 1;
				}else{
					$secuencia = $resp->secuencia + 1;
				}
				$codigo = $str."-".$secuencia;
				if($resp){
					$respuesta = array('estado' => 1, 'respuesta' => 'Exito', 'codigo' => $codigo, 'secuencia' => $secuencia);
					header("content-type:application/json");
					print json_encode($respuesta);
				}else{
					$respuesta = array('estado' => 0, 'respuesta' => 'Error', 'codigo' => '0', 'secuencia' => '0');
					header("content-type:application/json");
					print json_encode($respuesta);
				} 
			// Ejecutando consultas
			// echo json_encode($codigo);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
    }

	public function obtener_datos_actual(){
        if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			
			// Ejecutando consultas
				$resp = $this->Rx_Model->obtenerDatosActuales($datos);
				print json_encode($resp);
			// Ejecutando consultas
			// echo json_encode($codigo);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
    }

	public function guardar_rx(){
		$datos = $this->input->post();
		$datos = $this->input->post();
		$bool = $this->Rx_Model->guardarResultado($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Rx/resultados_archivados/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Rx/resultados_archivados/");
		}
		// echo json_encode($datos);
	}

	public function actualizar_rx(){
		$datos = $this->input->post();
		$bool = $this->Rx_Model->actualizarResultado($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Rx/resultados_archivados/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Rx/resultados_archivados/");
		}
		// echo json_encode($datos);
	}
}

?>