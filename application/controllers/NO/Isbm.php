<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet;

class Isbm extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Isbm_Model");
		$this->load->model("Botiquin_Model");
	}

	// Metodos para crear requisicion de ISBM

		public function cuentas_isbm(){
            $data["cuentas"] = $this->Isbm_Model->obtenerCuentas();
			$this->load->view("Base/header");
			$this->load->view("ISBM/cuentas_isbm", $data);
			$this->load->view("Base/footer");
		}

        public function crear_cuenta(){
            $datos = $this->input->post();
			$uc = 0;
			$codigo = $this->Isbm_Model->obtenerCodigo();
			if(!is_null($codigo)){
				$uc = $codigo->codigoCuenta + 1;
			}else{
				$uc = 1;
			}
			$datos["codigoCuenta"] = $uc;
			
            $resp = $this->Isbm_Model->crearCuenta($datos);
			if($resp != 0){
                $this->session->set_flashdata("exito","La requisición se creo con exito!");
                redirect(base_url()."Isbm/detalle_cuenta/$resp/");
            }else{
                $this->session->set_flashdata("error","Error al crear la requisición!");
                redirect(base_url()."Isbm/cuentas_isbm/");
            }
			
        }

		public function detalle_cuenta($id = null){
			$data["cuenta"] = $id;
			$data["datosCuenta"] = $this->Isbm_Model->obtenerDetalleRequision($id);
			$data["detalleCuenta"] = $this->Isbm_Model->detalleCuentaIsbm($id);
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
			// $data["resumenCuenta"] = $this->Isbm_Model->resumenCuentaIsbm($id);
			$this->load->view("Base/header");
			$this->load->view("ISBM/detalle_cuenta", $data);
			$this->load->view("Base/footer");	
		}	
		
		public function descontar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				$resp = $this->Isbm_Model->agregarDetalle($datos);
				if($resp){
					echo "Good";
				}else{
					echo "Error";
				}
			}
			else{
				echo "Error...";
			}
		}

		public function editar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				$data['cantidad'] = $datos["cantidad"];
				$data['filaDetalle'] = $datos["cuentaMedicamento"];
				$resp = $this->Isbm_Model->editarFilaCuenta($data);
				if($resp){
					echo "Bien";
				}else{
					echo "Error";
				}
				// echo json_encode($data);
			}
			else{
				echo "Error...";
			}

			// $datos =$this->input->post();
			//echo json_encode($filaDetalle);	
		}	

		public function eliminar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				$resp = $this->Isbm_Model->eliminarDetalle($datos);
				if($resp){
					echo "Exito...";
				}else{
					echo "Error...";
				}
				// echo json_encode($datos);
			}
			else{
				echo "Error...";
			}
		}

		public function resumen_cuenta($id = null){
			$data["medicamentos"] = $this->Isbm_Model->resumenCuentaIsbm($id);
			
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
							<td width="33%" style="font-weight: bold; font-size: 12px;">{PAGENO}/{nbpg}</td>
							<td width="33%" align="center" style="font-weight: bold; font-size: 12px;"></td>
							<td width="33%" style="font-weight: bold; font-size: 12px; text-align: right;">Requisición de medicamentos.</td>
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
				$html = $this->load->view("ISBM/resumen_cuenta_pdf",$data,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html);
				$mpdf->Output('detalle_compra.pdf', 'I');
				//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
			// Fin reporte PDF
		}

		public function lista_cuenta_descargos(){
			$data["cuentas"] = $this->Isbm_Model->listaCuentasDescargo();
			$this->load->view("Base/header");
			$this->load->view("ISBM/lista_cuentas_descargo", $data);
			$this->load->view("Base/footer");
        }

		public function detalle_cuenta_descargo($id = null){
			$data["cuenta"] = $id;
			$data["datosCuenta"] = $this->Isbm_Model->obtenerDetalleCuenta($id);
			$data["detalleCuenta"] = $this->Isbm_Model->detalleDescargosCuentaIsbm($id);
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();

			$this->load->view("Base/header");
			$this->load->view("ISBM/detalle_cuenta_descargo", $data);
			$this->load->view("Base/footer");
		}

		public function sacar_de_stock(){
			if($this->input->is_ajax_request()){
				
				$datos = $this->input->post();
				
				$data["cuentaActual"] = $datos["cuentaActual"];
				$data["idMedicamento"] = $datos["idMedicamento"];
				$data["cantidad"] = $datos["cantidadMedicamento"];
				$data["por"] = $this->session->userdata('id_usuario_h');
				$data["pivote"] = $datos["destino"];

				$resp = $this->Isbm_Model->descontarMedicamento($data);
				if($resp){
					// $respuesta = array('estado' => 1, 'respuesta' => 'Exito');
					// $detalle = $this->Isbm_Model->detalleCuentaIsbm($this->input->post("cuentaActual"));
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

		/* public function eliminar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				$data["idDescargo"] = $datos["idDescargo"];
				$data["cantidadDescargo"] = $datos["cantidadDescargo"];
				$data["idMedicamento"] = $datos["idMedicamento"];
				$resp = $this->Isbm_Model->eliminarDetalle($data);
				if($resp){
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
		} */

	// Fin metodos para requisicion de ISBM

	// Metodos para descargos Isbm
		public function cuenta_descargo(){
			// $data["requisiciones"] = $this->Isbm_Model->obtenerRequisiciones();
			
			// Obtener codigo de la cuenta
				$uc = 0;
				$codigo = $this->Isbm_Model->obtenerCodigo();
				if(!is_null($codigo)){
					$uc = $codigo->codigoCuenta + 1;
				}else{
					$uc = 1;
				}
			// Fin obtener codigo de la cuenta
			$data["codigoCuenta"] = $uc;


			$this->load->view("Base/header");
			$this->load->view("ISBM/cuenta_descargos", $data);
			$this->load->view("Base/footer");
		}

		public function crear_cuenta_descargos(){
			$datos = $this->input->post();
			$datos["estado"] = 1;
			$turno = "";
			if($datos["turnoDescargos"] == "Dia"){
				$turno = "Noche";
			}else{
				$turno = "Dia";
			}
			$resp = $this->Isbm_Model->guardarCuentaDescargo($datos, $turno);
			if($resp != 0){
				$this->Isbm_Model->actualizarEstadoCuenta($datos["idRequisicion"]);
                $this->session->set_flashdata("exito","La cuenta se creo con exito!");
                redirect(base_url()."Isbm/detalle_cuenta_descargo/$resp/");
            }else{
                $this->session->set_flashdata("error","Error al crear la cuenta!");
                redirect(base_url()."Isbm/cuenta_descargo/");
            }
		}

		public function detalle_cuenta_descargos($id = null){
			echo "Esta es la cuenta de descargos #".$id;
		}

		public function stock_x_codigo(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();

				$codigo = $datos["codigo"];
				$cantidad =$datos["cantidad"]; // Cantidad que se requiere de medicamento
				$cuenta =$datos["cuenta"]; // Cuenta donde se usa el medicamentob

				$medicamento = $this->Isbm_Model->buscarMedicamentoCodigo(trim($codigo));
				
				$cuenta = array('cuentaActual' => $cuenta, 'idMedicamento' => $medicamento->idMedicamento, 'cantidad' => $cantidad ); // Para tabla isbm
				// Para descontar de stock
				$med = array('stock' => ($medicamento->stockMedicamento - $cantidad), 'usados' => ($medicamento->usadosMedicamento + $cantidad), 'idMedicamento' => $medicamento->idMedicamento);
				
				$data["cuenta"] = $cuenta;
				$data["medicamento"] = $med;

				$resp = $this->Isbm_Model->descontarMedicamento($data);
				if($resp){
					$this->session->set_flashdata("exito","Medicamento agregado con exito!");
                	redirect(base_url()."Isbm/detalle_cuenta_descargo/$cuenta/");
				}else{
					$this->session->set_flashdata("exito","Error al agregar el medicamento!");
                	redirect(base_url()."Isbm/detalle_cuenta_descargo/$cuenta/");
				}

			}
		}
	// Fin metodos para descargos Isbm

	// Turno para los descargos de ISBM, Laboratorio y empleados
		public function obtenerTurno(){
			$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
			$dia = date("w");

			//echo $dias[date("w")];
			$hora = date('H:i:s');  
			$ampm = date('a');
			$turno = false;  

			if($dia >= 1 && $dia <= 5){
				
				switch ($hora) {
					case $hora > "07:00:00" && $ampm == "am":
						$turno = 1; // Turno dia
						break;
					case $hora < "17:00:00" && $ampm == "pm":
						$turno = 1; // Turno dia
						break;
					case $hora < "07:00:00" && $ampm == "am":
						$turno = 2; // Turno noche
						/* if($dia == 1){						
							$turno = 2;
						}else{
							$turno = 0;
						} */
						break;
					case $hora > "17:00:00" && $ampm == "pm":
						$turno = 2; // Turno noche
						break;
					default:
						echo "Son las ".$hora;
						break;
				}

				
			}/* else{
				if($dia == 6){
					switch ($hora) {
						case $hora < "07:00:00" && $ampm == "am":
							$turno = 0;
							break;
						case $hora > "07:00:00" && $ampm == "am":
							$turno = 1;
							break;
						case $hora > "12:00:00" && $ampm == "pm":
							$turno = 2;
							break;
						default:
							echo "Son las ".$hora;
							break;
					}
				}
				if($dia == 0){
					$turno = 2;
				}
				if($dia == 1){
					if($hora < "07:00:00" && $ampm == "am"){
						$turno = 2;
					}
				}
			} */

			return $turno;
		}
	// Turno para los descargos de ISBM, Laboratorio y empleados

}