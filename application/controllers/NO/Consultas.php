<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Consultas extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Consultas_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}


    // Metodos para obtener los pacientes pendientes
		public function consultas_pendientes(){
			//$pivote = $this->session->userdata("acceso_h");
			 $pivote = 8;
			$destino = 0;
			$titulo = "";
			$fecha = date('Y-m-d');
			switch ($pivote) {
				case '14':
					$destino = 3;
					$titulo = "Lista de examenes pendientes";
					break;
				case '8':
					$destino = 2;
					$titulo = "Lista de ultrasonografias pendientes";
					break;
				
				default:
					$destino = 0;
					break; 
			}

			// Obteniendo el turno al que se agregara la ultra
				$turno = 0;
				if((date('h:i:s', time()) < date_create_from_format("h:i:s", "10:15:00")->format("h:i:s")) && (date('a', time()) == "am")){
					$turno = 1;
				}else{
					$turno = 2;
				}
			// Fin calcular turno
			$data["titulo"] = $titulo;
			$data["pendientes"] = $this->Consultas_Model->consultasPendientesHoy($fecha, $destino, $turno);
			$this->load->view("Base/header");
			$this->load->view("Consultas/listado_consultas", $data);
			$this->load->view("Base/footer");

		}

		public function liberar_cupo(){
			if($this->input->is_ajax_request()){
				$cupo = $this->input->post("idCupo");
				$bool = $this->Consultas_Model->liberarCupo($cupo);
				if($bool){
					echo "Good";
				}else{
					echo "Error";
				}
			}else{
				echo "Error";
			}
		}
		
		public function regresar_cupo(){
			if($this->input->is_ajax_request()){
				$cupo = $this->input->post("idCupo");
				$bool = $this->Consultas_Model->regresarCupo($cupo);
				if($bool){
					echo "Good";
				}else{
					echo "Error";
				}
			}else{
				echo "Error";
			}
		}
	// Fin procesos

    
}
