<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Constancias extends CI_Controller {

	public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/El_Salvador');
        if (!$this->session->has_userdata('valido')){
            $this->session->set_flashdata("error", "Debes iniciar sesión");
            redirect(base_url());
        }
        $this->load->model("Constancias_Model");
    }

	public function index(){
        echo "Nel perrin, aqui no tenes nada que hacer.";
	}

	/* Contancias médica */
		public function constancia_medica(){
			$data["meses"] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			$data["constancias"] = $this->Constancias_Model->obtenerConstancias();
			$this->load->view('Base/header');
			$this->load->view('Constancias/constancia', $data);
			$this->load->view('Base/footer');
		}

		public function guardar_constancia(){
			$datos = $this->input->post();
			$datos["encargadoConstancia"] =  "ING. JOSE ERNESTO ALVARADO.";
			$resp = $this->Constancias_Model->guardarConstancia($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos de la constancia fueron guardados con exito!");
				redirect(base_url()."Constancias/detalle_constancia/$resp");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la constancia!");
				redirect(base_url()."Constancias/");
			}
		}

		public function actualizar_constancia(){
			$datos = $this->input->post();
			// var_dump($datos);
			$resp = $this->Constancias_Model->actualizarConstancia($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos de la constancia fueron guardados con exito!");
				redirect(base_url()."Constancias/detalle_constancia/".$datos['idConstancia']."/");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la constancia!");
				redirect(base_url()."Constancias/");
			}
		}

		public function eliminar_constancia(){
			$datos = $this->input->post();
			//var_dump($datos);
			$resp = $this->Constancias_Model->eliminarConstancia($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","La constancia fue eliminada con exito!");
				redirect(base_url()."Constancias/");
			}else{
				$this->session->set_flashdata("error","Error al eliminar la constancia!");
				redirect(base_url()."Constancias/");
			}
		}

		public function detalle_constancia($id){
			// Reporte
				$data["meses"] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
				$datos = $this->Constancias_Model->obtenerConstancia($id);
				$data = array('constancia' => $datos );

				setlocale(LC_TIME, "spanish");
				$fecha = date('Y/m/d');
				$fecha = str_replace("/", "-", $fecha);			
				$newDate = date("d-m-Y", strtotime($fecha));				
				$data["mesActual"] = strftime("%B de %Y", strtotime($newDate));

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
				/* $mpdf->SetHTMLFooter('
					<table width="100%" style="border: 5px solid red">
						<tr ">
							<td width="33%" style="font-weight: bold; font-size: 12px;">{DATE j-m-Y}</td>
							<td width="33%" align="center" style="font-weight: bold; font-size: 12px;">{PAGENO}/{nbpg}</td>
							<td width="33%" style="font-weight: bold; font-size: 12px; text-align: right;">Liquidación de caja</td>
						</tr>
					</table>'); */

				$mpdf->SetProtection(array('print'));
				$mpdf->SetTitle("Hospital Orellana, Usulutan");
				$mpdf->SetAuthor("Edwin Orantes");
				// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
				$mpdf->showWatermarkText = true;
				$mpdf->watermark_font = 'DejaVuSansCondensed';
				$mpdf->watermarkTextAlpha = 0.1;
				$mpdf->SetDisplayMode('fullpage');
				//$mpdf->AddPage('L'); //Voltear Hoja

				$html = $this->load->view("Constancias/constancia_pdf",$data,true); // Cargando hoja de estilos

				$mpdf->WriteHTML($html);
				$mpdf->Output('detalle_compra.pdf', 'I');
		}
	/* Fin contancias médica */

	/* Contancias incapacidad */
		public function constancia_incapacidad(){
			$data["meses"] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			//$data["constancias"] = $this->Constancias_Model->obtenerConstancias();
			$this->load->view('Base/header');
			$this->load->view('Constancias/constancia_incapacidad', $data);
			$this->load->view('Base/footer');
		}

		/* public function guardar_constancia_incapacidad(){
			$datos = $this->input->post();
			$datos["encargadoConstancia"] =  "ING. JOSE ERNESTO ALVARADO.";
			$resp = $this->Constancias_Model->guardarConstancia($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos de la constancia fueron guardados con exito!");
				redirect(base_url()."Constancias/detalle_constancia/$resp");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la constancia!");
				redirect(base_url()."Constancias/");
			}
		}

		public function actualizar_constancia(){
			$datos = $this->input->post();
			// var_dump($datos);
			$resp = $this->Constancias_Model->actualizarConstancia($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos de la constancia fueron guardados con exito!");
				redirect(base_url()."Constancias/detalle_constancia/".$datos['idConstancia']."/");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la constancia!");
				redirect(base_url()."Constancias/");
			}
		}

		public function eliminar_constancia(){
			$datos = $this->input->post();
			//var_dump($datos);
			$resp = $this->Constancias_Model->eliminarConstancia($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","La constancia fue eliminada con exito!");
				redirect(base_url()."Constancias/");
			}else{
				$this->session->set_flashdata("error","Error al eliminar la constancia!");
				redirect(base_url()."Constancias/");
			}
		}

		public function detalle_constancia($id){
			// Reporte
				$data["meses"] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
				$datos = $this->Constancias_Model->obtenerConstancia($id);
				$data = array('constancia' => $datos );

				setlocale(LC_TIME, "spanish");
				$fecha = date('Y/m/d');
				$fecha = str_replace("/", "-", $fecha);			
				$newDate = date("d-m-Y", strtotime($fecha));				
				$data["mesActual"] = strftime("%B de %Y", strtotime($newDate));

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
				// $mpdf->SetHTMLFooter(); 

				$mpdf->SetProtection(array('print'));
				$mpdf->SetTitle("Hospital Orellana, Usulutan");
				$mpdf->SetAuthor("Edwin Orantes");
				// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
				$mpdf->showWatermarkText = true;
				$mpdf->watermark_font = 'DejaVuSansCondensed';
				$mpdf->watermarkTextAlpha = 0.1;
				$mpdf->SetDisplayMode('fullpage');
				//$mpdf->AddPage('L'); //Voltear Hoja

				$html = $this->load->view("Constancias/constancia_pdf",$data,true); // Cargando hoja de estilos

				$mpdf->WriteHTML($html);
				$mpdf->Output('detalle_compra.pdf', 'I');
		} */
	/* Fin contancias incapacidad */

}
