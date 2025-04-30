<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Expediente extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Enfermeria/Enfermeria_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
        // $hoy = "2023-08-12";
        $hoy = date("Y-m-d");
        // $existe = $this->Enfermeria_Model->sensoExiste($ayer);
        
        $resp = $this->Enfermeria_Model->sensoExiste($hoy);
        if($resp->existe > 0){
            echo $resp->existe;
            // $senso = $this->Enfermeria_Model->crearSenso($datos, $fecha);
        }else{
            echo "No existe";
        }

        /* $data["accesos"] = $this->Accesos_Model->obtenerAccesos();
        $this->load->view('Base/header');
		$this->load->view('Roles/gestion_accesos', $data);
		$this->load->view('Base/footer'); */
    } 

    public function agregar_paciente($params = null){
        $hoy = date("Y-m-d");
        $datos = unserialize(base64_decode(urldecode($params)));
        $hoja = $datos["hoja"];
        $resp = $this->Enfermeria_Model->obtenerPaciente($datos["idPaciente"]);
        $med = $this->Enfermeria_Model->obtenerMedico($datos["idMedico"]);

        $paciente["fecha"] = $datos["fechaHoja"];
        $paciente["paciente"] = $resp->nombrepaciente;
        $paciente["medico"] = $med->nombreMedico;
        $paciente["habitacion"] = $datos["idHabitacion"];
        $paciente["procedimiento"] = $datos["procedimientoHoja"];
        $paciente["estadoPaciente"] = 1;
        $paciente["idPaciente"] = $datos["idPaciente"];
        $paciente["idMedico"] = $datos["idMedico"];
        $paciente["idHoja"] = $hoja;

        // Validando si existe un senso ya creado para este dia
            $resp = $this->Enfermeria_Model->sensoExiste($hoy);
            if($resp->existe > 0){
                $senso = $resp->existe;
                $resp = $this->Enfermeria_Model->guardarPaciente($paciente, $senso);
                if($resp){
                    redirect(base_url()."Hoja/detalle_hoja/$hoja/");
                }else{
                    redirect(base_url()."Hoja/detalle_hoja/$hoja/");
                }
            }else{
                // echo json_encode($paciente);
                $idSenso = $this->Enfermeria_Model->crearSenso($hoy);
                if($idSenso > 0){
                    $this->Enfermeria_Model->guardarPaciente($paciente, $idSenso);
                    redirect(base_url()."Hoja/detalle_hoja/$hoja/");
                }else{
                    redirect(base_url()."Hoja/detalle_hoja/$hoja/");
                }
            }
        // Validando si existe un senso ya creado para este dia



        // echo json_encode($paciente);
    }

    public function pacientes_activos(){
        $fecha = date('Y-m-d');
		$data["detalle_senso"] = $this->Enfermeria_Model->sensoHoy($fecha);
		
		$this->load->view("Base/header");
		$this->load->view("Enfermeria/senso_hoy", $data);
		$this->load->view("Base/footer");

        // echo json_encode($data["detalle_senso"]);
    }

    public function detalle_expediente($hoja = null){
        // Separar por entregado
            $data["pendientes"] = $this->Enfermeria_Model->medicamentosHoja($hoja);
            $data["hoja"] = $hoja;
            $entregados = $this->Enfermeria_Model->medicamentosHojaEntregados($hoja);
        // Separar por entregado

        // Agrupar por fecha/hora
            $medAgrupados = [];
            foreach ($entregados as $fila) {
                $clave = $fila->fechaAgregado;
                if (!isset($medAgrupados[$clave])) {
                    $medAgrupados[$clave] = [];
                }
                $medAgrupados[$clave][] = $fila;
            }

            $data["entregados"] = $medAgrupados;
        // Agrupar por fecha/hora

		
		$this->load->view("Base/header");
		$this->load->view("Enfermeria/detalle_requisicion", $data);
		$this->load->view("Base/footer");


        // echo json_encode($data["pendientes"]);
    }

    public function descontar_insumos($params = null){
        $datos = unserialize(base64_decode(urldecode($params)));
        foreach ($datos as $row) {
            $hoja = $row["idHoja"];
        }

        $bool = $this->Enfermeria_Model->guardarMedicamentosHoja($datos);
		if($bool){
            $this->Enfermeria_Model->saldarEntrega($hoja);
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Enfermeria/Expediente/detalle_expediente/".$hoja."/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardados los datos!");
			redirect(base_url()."Enfermeria/Expediente/detalle_expediente/".$hoja."/");
		}

        // echo json_encode($datos);
    }

    public function procesar_devolucion(){
        $datos = $this->input->post();
        
        $datosUpdate["cantidad"] = $datos["cantidadNueva"];
        $datosUpdate["unitaria"] = $datos["cantidadNueva"];
        $datosUpdate["fila"] = $datos["filaActualizar"];
        $hoja = $datos["hoja"];

        $bool = $this->Enfermeria_Model->procesarDevolucionInsumos($datosUpdate);
        if($bool){
            $this->Enfermeria_Model->validarRetornoInsumo($datos["filaActualizar"]);
            $this->session->set_flashdata("exito","El proceso se efectuo con exito!");
            redirect(base_url()."Enfermeria/Expediente/detalle_expediente/".$hoja."/");
        }else{
            $this->session->set_flashdata("error","Hubo un error al precesar los datos!");
            redirect(base_url()."Enfermeria/Expediente/detalle_expediente/".$hoja."/");
        }

        // echo json_encode($datosUpdate);
    }




    // Obtener fecha anterior
    private function obtener_fecha_anterior($fecha = null){
        $fecha = new DateTime($fecha);
        // $fecha->modify('-1 day');
        $dia_anterior = $fecha->format('Y-m-d');
        $existe = $this->Enfermeria_Model->sensoExiste($dia_anterior);

        return $existe;

    }



}
