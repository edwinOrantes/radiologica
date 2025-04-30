<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Stock extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Stock_Model");
        $this->load->model("Botiquin_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
		$data["stocks"] = $this->Stock_Model->obtenerStocks();
        $this->load->view('Base/header');
		$this->load->view('Stocks/crear_stock', $data);
		$this->load->view('Base/footer');
		// echo json_encode($data);
    }

	public function guardar_stock(){
		$datos = $this->input->post();
		$resp = $this->Stock_Model->guardarStock($datos);
		if($resp > 0){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Stock/detalle_stock/".$resp."/");
		}else{
			$this->session->set_flashdata("exito","Error al guardar los datos!");
			redirect(base_url()."Stock/detalle_stock/".$resp."/");
		}
	}

	public function detalle_stock($id = null){
		$data["detalle"] = $this->Stock_Model->detalleStock($id);
		$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
		$data["stock"] = $id;
		$this->load->view("Base/header");
		$this->load->view("Stocks/detalle_stock", $data);
		$this->load->view("Base/footer");
		// echo json_encode($data);
	}

	public function actualizar_stock(){
		$datos = $this->input->post();
		$resp = $this->Stock_Model->actualizarStock($datos);
		if($resp){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Stock/");
		}else{
			$this->session->set_flashdata("exito","Error al actualizar los datos!");
			redirect(base_url()."Stock/");
		}
		// echo json_encode($datos);
	}

	public function agregar_a_stock(){
		if($this->input->is_ajax_request()){

			$datos = $this->input->post();
			// Ejecutando consultas
			$bool = $this->Stock_Model->guardarDetalle($datos);
			if($bool){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}

			// echo json_encode($datos);

		} 
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
		}
	}

	public function actualizar_detalle_stock(){
		$datos = $this->input->post();
		$stockActual = $datos["stockActual"];
		unset($datos["stockActual"]);
		$resp = $this->Stock_Model->actualizarDetalleStock($datos);
		if($resp){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Stock/detalle_stock/".$stockActual."/");
		}else{
			$this->session->set_flashdata("exito","Error al actualizar los datos!");
			redirect(base_url()."Stock/detalle_stock/".$stockActual."/");
		}
		// echo json_encode($datos);
	}

	public function eliminar_detalle_stock(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			// Ejecutando consultas
			$bool = $this->Stock_Model->eliminarDetalle($datos);
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
    
}
