<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Accesos extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Accesos_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
        $data["accesos"] = $this->Accesos_Model->obtenerAccesos();
        $this->load->view('Base/header');
		$this->load->view('Roles/gestion_accesos', $data);
		$this->load->view('Base/footer');
    } 

    public function guardar_acceso(){
        $datos = $this->input->post();
        $bool = $this->Accesos_Model->guardarAcceso($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Accesos/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Accesos/");
		}
    }

    public function actualizar_acceso(){
        $datos = $this->input->post();
        $bool = $this->Accesos_Model->actualizarAcceso($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Accesos/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			redirect(base_url()."Accesos/");
		}
    }

    public function eliminar_acceso(){
        $datos = $this->input->post();
        $bool = $this->Accesos_Model->eliminarAcceso($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Accesos/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al eliminar los datos!");
			redirect(base_url()."Accesos/");
		}
    }
    
}
