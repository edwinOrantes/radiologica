<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Permisos extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
        $this->load->model("Accesos_Model");
        $this->load->model("Permisos_Model");
	}

    public function index(){
        $data["accesos"] = $this->Accesos_Model->obtenerAccesos();
        //var_dump($data["accesos"]);
        $this->load->view('Base/header');
		$this->load->view('Permisos/lista_accesos', $data);
		$this->load->view('Base/footer');
    }
    
    public function agregar_permisos($id){
        $data["permisos"] = $this->Permisos_Model->obtenerPermisos($id);
        $data["menus"] = $this->Permisos_Model->obtenerMenus($id);
        $data["nombreAcceso"] = $this->Permisos_Model->obtenerNombreAcceso($id);
        $data["acceso"] = $id;
        $this->load->view('Base/header');
		$this->load->view('Permisos/permisos_acceso', $data);
		$this->load->view('Base/footer');
    }

    public function guardar_permisos(){
        $datos = $this->input->post();
        $return = $datos["idAcceso"];
        $bool = $this->Permisos_Model->guardarPermisos($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Permisos/agregar_permisos/".$return);
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Permisos/agregar_permisos/".$return);
		}
    }

    public function eliminar_permiso(){
        $datos = $this->input->post();
        $permiso = $datos["idPermiso"];
        $return = $datos["idAcceso"];
        $bool = $this->Permisos_Model->eliminarPermiso($permiso);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Permisos/agregar_permisos/".$return);
		}else{
			$this->session->set_flashdata("error","Hubo un error al eliminar los datos!");
			redirect(base_url()."Permisos/agregar_permisos/".$return);
		}

    }

    public function activar_permiso(){
        $datos = $this->input->post();
        $permiso = $datos["idPermiso"];
        $return = $datos["idAcceso"];
        $bool = $this->Permisos_Model->activarPermiso($permiso);
		if($bool){
			$this->session->set_flashdata("exito","El permiso se activo con exito!");
			redirect(base_url()."Permisos/agregar_permisos/".$return);
		}else{
			$this->session->set_flashdata("error","Hubo un error al activar el permiso!");
			redirect(base_url()."Permisos/agregar_permisos/".$return);
		}


    }

    /* public function guardar_acceso(){
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
    } */
    
}
