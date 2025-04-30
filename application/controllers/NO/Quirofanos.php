<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Quirofanos extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Quirofanos_Model");
	}

    public function index(){
        $data["procedimientos"] = $this->Quirofanos_Model->procedimientosActivos();
        $this->load->view('Base/header');
		$this->load->view('Salas/procedimientos_activos', $data);
		$this->load->view('Base/footer');
		// echo json_encode($data);
    } 

	public function lista_insumos(){
		$data["insumos"] = $this->Quirofanos_Model->medicamentosSala();
		$this->load->view("Base/header");
		$this->load->view("Salas/lista_insumos", $data);
		$this->load->view("Base/footer");

		// echo json_encode($data);
	}

	public function detalle_procedimientos($pivote = null){
		$medicamentos = [];
		$data["pivote"] = $pivote;
		$data["cuenta"] = $this->Quirofanos_Model->procedimientoSala($pivote);
		$data["medicamentos"] = $this->Quirofanos_Model->medicamentosSala($pivote);
		$data["detalle"] = $this->Quirofanos_Model->detalleProcedimiento($pivote);
		// Medicamentos que se agregaran a la hoja de cobro
			foreach ($data["detalle"] as $item) {
				$medicamento = [
					'idHoja' => $data["cuenta"]->idHoja,
					'idInsumo' => $item->idMedicamento,
					'precioInsumo' => $item->precioInsumo,
					'cantidadInsumo' => $item->cantidadInsumo,
					'fechaInsumo' => date("Y-m-d"),
					'por' => $this->session->userdata('id_usuario_h'),
					'pivoteStock' => 2,
				];
				$medicamentos[] = $medicamento;
			}
		// Medicamentos que se agregaran a la hoja de cobro
		$data["resumen"] = $medicamentos;
		$this->load->view('Base/header');
		$this->load->view('Salas/detalle_procedimientos', $data);
		$this->load->view('Base/footer');

		// echo json_encode($medicamentos);
	}

	public function descontar_medicamento(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$med = $this->Quirofanos_Model->medicamentoPorCodigo($datos["codigoMedicamento"]);
			$datos["idInsumo"] = $med->idMedicamento;
			$datos["precioInsumo"] = $med->precioVMedicamento;
			$datos["por"] = $datos["por"];
			unset($datos["codigoMedicamento"]);

			// Validacion extra para saber si el medicamento ya esta en uso en el procedimiento
				$enUso = $this->Quirofanos_Model->estaEnUso($datos["idInsumo"], $datos["idProcedimiento"]);
				$datos["pivoteConsulta"] = $enUso->idInsumo;
				$datos["cantidadActual"] = $enUso->cantidad;
			// Validacion extra para saber si el medicamento ya esta en uso en el procedimiento

			$resp = $this->Quirofanos_Model->guardarDescargo($datos);

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

	public function editar_medicamento(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$med["cantidad"] = $datos["cantidadNueva"];
			$med["fila"] = $datos["detalleMedicamento"];

			$resp = $this->Quirofanos_Model->actualizarDescargo($med);

			if($resp){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}
			// echo json_encode($med);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

	public function eliminar_medicamento(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$med["fila"] = $datos["detalleMedicamento"];

			$resp = $this->Quirofanos_Model->eliminarDescargo($med);

			if($resp){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}
			// echo json_encode($med);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

	public function cerrar_cuenta_descargos(){
		$datos = $this->input->post();
		$data["procedimiento"] = $datos["idProcedimiento"];
		$data["medicamentos"] = unserialize(base64_decode(urldecode($datos["valores"])));

		$resp = $this->Quirofanos_Model->agregarACuenta($data);
		if($resp != 0){
			$this->session->set_flashdata("exito","Datos agregados con exito!");
			redirect(base_url()."Quirofanos/detalle_procedimientos/".$data["procedimiento"]."/");
		}else{
			$this->session->set_flashdata("error","Error al agregar los datos!");
			redirect(base_url()."Quirofanos/detalle_procedimientos/".$data["procedimiento"]."/");
		}
		// echo json_encode($data);
		
	}

	public function lista_procedimientos(){
		$data["procedimientos"] = $this->Quirofanos_Model->listaProcedimientos();
		$this->load->view("Base/header");
		$this->load->view("Salas/lista_procedimientos", $data);
		$this->load->view("Base/footer");
		// echo json_encode($data["procedimientos"]);
	}

	public function resumen_insumos(){
		$data["medicamentos"] = $this->Quirofanos_Model->insumosParaReintegro();
		// Creacion de pdf
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 15,
				'margin_bottom' => 25,
				'margin_header' => 10,
				'margin_footer' => 10
			]);
			//$mpdf->setFooter('{PAGENO}');
			$mpdf->SetHTMLFooter('
				<table width="100%">
					<tr>
						<td width="33%"><strong>{DATE j-m-Y}</strong></td>
						<td width="33%" align="center"><strong>{PAGENO}/{nbpg}</strong></td>
						<td width="33%" style="text-align: right;"><strong>Detalle del medicamento</strong></td>
					</tr>
				</table>');
			$mpdf->SetProtection(array('print'));
			$mpdf->SetTitle("Hospital Orellana, Usulutan");
			$mpdf->SetAuthor("Edwin Orantes");
			//$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
			$mpdf->showWatermarkText = true;
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->watermarkTextAlpha = 0.1;
			$mpdf->SetDisplayMode('fullpage');
			//$mpdf->AddPage('L'); //Voltear Hoja

			$html = $this->load->view('Salas/resumen_medicamentos_usados', $data,true); // Cargando hoja de estilos

			$mpdf->WriteHTML($html);
			$mpdf->Output('detalle_compra.pdf', 'I');
		// Creacion de pdf
		// echo json_encode($data);
	}

	public function resumen_insumos_botiquin(){
		$data["medicamentos"] = $this->Quirofanos_Model->insumosParaReintegro();
		$html = $this->load->view('Base/header');
		$html = $this->load->view('Salas/reintegro_medicamentos', $data);
		$html = $this->load->view('Base/footer');
		// echo json_encode($data);
	}

	public function reintegrar_insumo(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$med = $this->Quirofanos_Model->medicamentoPorId($datos["insumo"]);

			$actualizacion["stockSala"] = $datos["total"] + $med->stockSala;
			$actualizacion["stockBotiquin"] = $med->stockMedicamento - $datos["total"];
			$actualizacion["medicamento"] = $datos["insumo"];

			$resp = $this->Quirofanos_Model->reintegrarInsumo($actualizacion);

			if($resp){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}
			// echo json_encode($actualizacion);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

}