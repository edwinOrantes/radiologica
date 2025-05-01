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
	}

	public function index(){}

	public function agregar_paciente(){
		$data["medicos"] = $this->Medico_Model->obtenerMedicos();
		$this->load->view('Base/header');
		$this->load->view('Consulta/agregar_paciente', $data);
		$this->load->view('Base/footer');
		
	}
	
}
