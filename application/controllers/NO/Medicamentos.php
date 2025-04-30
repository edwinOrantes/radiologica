<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Medicamentos extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Paciente_Model");
        /* $this->load->model("Usuarios_Model");
        $this->load->model("Gastos_Model");
        $this->load->model("Reportes_Model"); */
        $this->load->model("Medicamentos_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

	public function cuentas_medicamento(){
        $fecha_actual = date("Y-m-d");
        $fecha = date("Y-m-d",strtotime($fecha_actual."- 1 week")); // Una semana atras
        // Cuenta abiertas
            $data["cuentas"] = $this->Medicamentos_Model->obtenerCuentas();
        // Fin cuentas abiertas
        // $data["privados"] = $this->Medicamentos_Model->pacientesPrivados($fecha);
        // $data["isbm"] = $this->Medicamentos_Model->pacientesISBM($fecha);

        $privados = $this->Medicamentos_Model->pacientesPrivados($fecha);
        $isbm = $this->Medicamentos_Model->pacientesISBM($fecha);
        $all = array_merge($privados, $isbm);
        $data["pacientes"] = $all;


		$this->load->view('Base/header');
		$this->load->view('Medicamentos/gestion_cuentas', $data);
		$this->load->view('Base/footer');

        // echo json_encode($data);
        // echo sizeof($data["pacientes"]);


       

	}

    public function crear_cuenta(){
        $datos = $this->input->post();
        // Analisis de datos
            if($datos["idHoja"] != ""){
                $cuenta["paciente"] = $datos["idPaciente"];
                // $cuenta["habitacion"] = $datos["habitacion"];
                $cuenta["idHoja"] = $datos["idHoja"];
                $cuenta["tomadaDe"] = $datos["tomadaDe"];
                $cuenta["estadoCuentaBotiquin"] = 1;
            }else{
                if($datos["idPaciente"] != 0){
                    $cuenta["paciente"] = $datos["idPaciente"];
                    // $cuenta["habitacion"] = "37";
                    $cuenta["idHoja"] = "0";
                    $cuenta["tomadaDe"] = "Privado";
                    $cuenta["estadoCuentaBotiquin"] = 1;
                }else{
                    $paciente["nombrePaciente"] = $datos["nombrePaciente"];
                    $paciente["edadPaciente"] = $datos["edadPaciente"];
                    $paciente["telefonoPaciente"] = "0000-0000";
                    $paciente["duiPaciente"] = "00000000-0";
                    $paciente["nacimientoPaciente"] = "0000-00-00";
                    $paciente["direccionPaciente"] = "Usulután";

                    $idPaciente = $this->Medicamentos_Model->guardarPaciente($paciente);
                    if($idPaciente > 0){
                        $cuenta["paciente"] = $idPaciente;
                        // $cuenta["habitacion"] = "37";
                        $cuenta["idHoja"] = "0";
                        $cuenta["tomadaDe"] = "Privado";
                        $cuenta["estadoCuentaBotiquin"] = 1;
                    }else{
                        $this->session->set_flashdata("error", "Ocurrio un problema al guardar los datos");
                        redirect(base_url()."Medicamentos/cuentas_medicamento/");
                    }
                }
            }
        // Fin de analisis de datos

        // Creando cuenta de botiquin
            $idCuenta = $this->Medicamentos_Model->crearCuentaBotiquin($cuenta);
            if($idCuenta > 0){
                $this->session->set_flashdata("exito","Cuenta creada con exito!");
				redirect(base_url()."Medicamentos/detalle_cuenta_botiquin/$idCuenta/");
            }else{
                $this->session->set_flashdata("error", "Ocurrio un problema al crear la cuenta");
                redirect(base_url()."Medicamentos/cuentas_medicamento/");
            }
        // Fin crear cuenta de botiquin
        // echo json_encode($cuenta);

    }

    public function detalle_cuenta_botiquin($id){
        $data["cuenta"] = $id;
        $data["detalleCuenta"] = $this->Medicamentos_Model->detalleCuentaBotiquin($id); 
        $this->load->view("Base/header");
        $this->load->view("Medicamentos/detalle_cuenta", $data);
        $this->load->view("Base/footer");
    }

    public function test(){
        $data = $this->Medicamentos_Model->detalleCuentaBotiquin(3); 
        echo json_encode($data);

    }

    public function descontar_medicamento(){
        if($this->input->is_ajax_request()){
            $codigo =$this->input->get("codigo");
            $medicamento = $this->Medicamentos_Model->buscarMedicamento(trim($codigo));
            $cuenta = array('cuentaActual' => $this->input->get("cuenta"), 'idMedicamento' => $medicamento->idMedicamento, 'cantidad' => '1');
            $med = array('stock' => ($medicamento->stockMedicamento-1), 'usados' => ($medicamento->usadosMedicamento+1), 'idMedicamento' => $medicamento->idMedicamento);
            /* $data["cuentaActual"] = $this->input->get("cuenta");
            $data["idMedicamento"] = $medicamento->idMedicamento;
            $data["cantidad"] = "1"; */
            // $data["codigo"] = $medicamento->codigoMedicamento;
            /* $data["stock"] = ($medicamento->stockMedicamento-1);
            $data["usados"] = ($medicamento->usadosMedicamento+1); */
            
            $data["cuenta"] = $cuenta;
            $data["medicamento"] = $med;

            $resp = $this->Medicamentos_Model->descontarMedicamento($data);
            if($resp){
                // $respuesta = array('estado' => 1, 'respuesta' => 'Exito');
                $detalle = $this->Medicamentos_Model->detalleCuentaBotiquin($this->input->get("cuenta"));
            }
            echo json_encode($detalle);
        }
        else{
            echo "Error...";
        }
    }

    public function editar_medicamento(){
        if($this->input->is_ajax_request()){
            $datos =$this->input->post();
            //$data = $this->Paciente_Model->buscarRecomendaciones(trim($id));
            echo json_encode($datos);
        }
        else{
            echo "Error...";
        }
    }

    // Funcion para buscar recomendaciones de pacientes
		public function recomendaciones_paciente(){
			if($this->input->is_ajax_request()){
				$id =$this->input->get("id");
				$data = $this->Paciente_Model->buscarRecomendaciones(trim($id));
				echo json_encode($data);
			}
			else{
				echo "Error...";
			}
		}

		public function buscar_paciente(){
			if($this->input->is_ajax_request()){
				$id =$this->input->get("id");
				$data = $this->Paciente_Model->buscarPaciente(trim($id));
				echo json_encode($data);
			}
			else{
				echo "Error...";
			}
		}
	// Fin recomendacion paciente

}
