<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Cajeras extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Cajeras_Model");
        $this->load->model("Reportes_Model");
        $this->load->model("Usuarios_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
        /* $correlativo = $this->Reportes_Model->numeroCorrelativo();
        $rango = $this->Cajeras_Model->ultimoCorte();
        $data["numeroLimite"] = $correlativo->correlativo;
        if(!is_null($rango)){
            $data["inicio"] = $rango->inicioCorte;
            $data["fin"] = $rango->finCorte;
            $data["fechaUltimo"] = $rango->fechaCorte;
        }else{
            $data["inicio"] = 0;
            $data["fin"] = 0;
            $data["fechaUltimo"] = date('Y-m-d');
        } */

        $this->load->view('Base/header');
		$this->load->view('Cajeras/corte_caja');
		$this->load->view('Base/footer');
        
        // echo json_encode($data);
    }

    public function lista_cortes(){
        $flag = $this->session->userdata("id_usuario_h");
        $data["cortes"] = $this->Cajeras_Model->listaCortes($flag);

        $this->load->view('Base/header');
		$this->load->view('Cajeras/lista_cortes', $data);
		$this->load->view('Base/footer');

        // echo json_encode($data);
    }

    public function guardar_corte(){
        $datos = $this->input->post();
        $usuario = $datos["usuarioActual"];
        unset($datos["usuarioActual"]);
        $datos["usuario"] = $usuario ;
        $validacion = $this->Cajeras_Model->validarCorte($datos);
        if(sizeof($validacion) == 0){
            $resp = $this->Cajeras_Model->guardarCorte($datos); // Retorna el id del corte agregado
            if($resp > 0){
                $datos["idCorte"] = $resp;
                $params = urlencode(base64_encode(serialize($datos)));
                $this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
                redirect(base_url()."Cajeras/corte_caja_pdf/$params/");
            }else{
                $this->session->set_flashdata("error","Error al guardar los datos!");
                redirect(base_url()."Cajeras/");
            }
            // echo json_encode($datos);
        }else{
            $this->session->set_flashdata("error","Esta liquidacion ya existe!");
            redirect(base_url()."Cajeras/");
        }
        
        // echo sizeof($validacion);
    }

    public function hacia_corte($idCorte = null){
        $datos = $this->Cajeras_Model->obtenerCorte($idCorte);
        $params["fechaLiquidado"] = $datos->fechaCorte;
        $params["turnoCorte"] = $datos->turnoCorte;
        $params["usuario"] = $datos->idUsuario;
        $params["idCorte"] = $idCorte;
        $data = urlencode(base64_encode(serialize($params)));
        redirect(base_url()."Cajeras/corte_caja_pdf/$data/");
    }

    public function corte_caja_pdf($params = null){
        $datos = unserialize(base64_decode(urldecode($params)));
        $data["usuario"] = $this->Usuarios_Model->obtenerUsuario($datos["usuario"]);
        $data["hojas"] = $this->Cajeras_Model->obtenerTotales($datos);

        // Reporte PDF


			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 15,
				'margin_bottom' => 30,
				'margin_header' => 10,
				'margin_footer' => 23
			]);
			//$mpdf->setFooter('{PAGENO}');
			$mpdf->SetHTMLFooter('
				<table width="100%">
					<tr>
						<td width="33%" style="font-weight: bold; font-size: 12px;">{DATE j-m-Y}</td>
						<td width="33%" align="center" style="font-weight: bold; font-size: 12px;">{PAGENO}/{nbpg}</td>
						<td width="33%" style="font-weight: bold; font-size: 12px; text-align: right;">Liquidación de caja</td>
					</tr>
				</table>');

			$mpdf->SetProtection(array('print'));
			$mpdf->SetTitle("Hospital Orellana, Usulutan");
			$mpdf->SetAuthor("Edwin Orantes");
			// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
			$mpdf->showWatermarkText = true;
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->watermarkTextAlpha = 0.1;
			$mpdf->SetDisplayMode('fullpage');
			//$mpdf->AddPage('L'); //Voltear Hoja
			$html = $this->load->view("Cajeras/corte_caja_pdf",$data,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html);
			$mpdf->Output('detalle_compra.pdf', 'I');
			//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
		// Fin reporte PDF

      // echo json_encode($data["hojas"]);
	}

    public function validaCorte(){
        if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Quirofanos_Model->validarCorte($datos);
			if($resp){
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
    
}


/* $correlativo = $this->Reportes_Model->numeroCorrelativo();
$ultimoExterno = $this->Reportes_Model->ultimoGenerado();
$data["numeroLimite"] = $correlativo->correlativo;
if(is_null($ultimoExterno->ultimoGenerado)){
    $data["inicio"] = 1;
}else{
    if($ultimoExterno->ultimoGenerado == $correlativo->correlativo){
        $data["inicio"] = $ultimoExterno->ultimoGenerado;
    }else{
        $data["inicio"] = $ultimoExterno->ultimoGenerado + 1 ;
    }
} */