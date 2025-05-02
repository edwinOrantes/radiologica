<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Medico_Model");
		$this->load->model("Consulta_Model");
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
			$resp = $this->Consulta_Model->busquedaDetalleExamen($datos["str"]);
			print json_encode($resp);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}


	private function rellenar_codigo($numero = null){
		if ($numero < 1000) {
			return str_pad($numero, 4, "0", STR_PAD_LEFT);
		} else {
			return (string)$numero;
		}
	}
	
}
