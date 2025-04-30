<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Importar la clase Curl
use Curl\Curl;

class Urologica extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
        $this->load->model("Urologica_Model");
		$this->load->model("Medico_Model");
		$this->load->model("Paciente_Model");
		$this->load->model("Laboratorio_Model");  // Modelo para gestionar datos del laboratorio
	}

    public function index(){
		// Obteniendo datos
			// Crear una instancia de Curl
			$curl = new Curl();
			// URL de la API
			$apiUrl = 'https://urologica.hospitalorellana.com.sv/Servicios/lista_pacientes';
			// $apiUrl = 'http://192.168.1.92/urologica/Servicios/lista_pacientes';

			// Realizar la solicitud GET
			$curl->get($apiUrl);

			// Verificar si hubo un error en la petición
			if ($curl->error) {
				// Si hay un error, puedes enviar un array vacío o un mensaje de error
				$data['pacientes'] = [];
				$data['error'] = 'Error: '. $curl->errorMessage;
			} else {
				// Si no hay error, decodificar la respuesta JSON
				$data['pacientes'] = json_decode($curl->response, true); // Convertir la respuesta a un array
				$data['error'] = null;
			}
			// Cerrar la conexión cURL
			$curl->close();
		// Obteniendo datos

		$data['medicos'] = $this->Medico_Model->obtenerMedicos();
		$data["codigo"] = $this->Laboratorio_Model->codigoConsulta();
		$this->load->view('Base/header');
		$this->load->view('Laboratorio/Urologica/cola_pacientes', $data);
		$this->load->view('Base/footer');

		// echo json_encode($curl->response);

    }

	public function actualizar_pivote_paciente($params  = null){

		$datos = unserialize(base64_decode(urldecode($params)));
		$consulta = $datos["idConsultaO"];
		
		$curl = new Curl();
		// $resp = $curl->post(https://urologica.hospitalorellana.com.sv/Servicios/agregar_pivote_paciente', $params); //Cambiar a la direccion en la nube
		$resp = $curl->post('http://192.168.1.92/urologica/Servicios/agregar_pivote_paciente', $datos); //Cambiar a la direccion en la nube
		$data = json_decode($resp->response);
		if($data->estado == 1){
			$this->session->set_flashdata("exito","Los datos se compartieron con exito!");
			redirect(base_url()."/Laboratorio/detalle_consulta/".$consulta."/");
		}else{
			$this->session->set_flashdata("error","Error al compartir los datos!");
			redirect(base_url()."/Laboratorio/detalle_consulta/".$consulta."/");
		}

		// echo json_encode($resp->response);
	}
   
}
