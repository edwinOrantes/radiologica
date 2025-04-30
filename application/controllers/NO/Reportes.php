<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Clases para el reporte en excel
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

class Reportes extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Reportes_Model");
		$this->load->model("Hoja_Model");
		$this->load->model("Botiquin_Model");
		$this->load->model("Gastos_Model");
		$this->load->model("Externos_Model");
		$this->load->model("Pendientes_Model");
	}

    public function index(){
		echo "Que haces aqui, es prohibido";
	}

	public function externos_hoja(){
        $correlativo = $this->Reportes_Model->numeroCorrelativo();
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
		}
		$this->load->view('Base/header');
		$this->load->view('Reportes/externos_hoja', $data);
		$this->load->view('Base/footer');

	}

    public function externos_por_hoja(){
        $datos = $this->input->post();
		// echo json_encode($datos);
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'De: ');
		$sheet->setCellValue('B1', $datos["hojaInicio"]);
		$sheet->setCellValue('C1', 'Hasta: ');
		$sheet->setCellValue('D1', $datos["hojaFin"]);

		$sheet->setCellValue('A2', 'Hoja de cobro');
		// $sheet->setCellValue('B2', 'Recibo de cobro');
		$sheet->setCellValue('B2', 'Fecha Entrada');
		$sheet->setCellValue('C2', 'Fecha Salida');
		$sheet->setCellValue('D2', 'Paciente');
		$sheet->setCellValue('E2', 'Doctor');
		$sheet->setCellValue('F2', 'Monto');
		$sheet->setCellValue('G2', 'Tipo');
		$sheet->getStyle('A1:G1')->getFont()->setSize(8);
		$sheet->getStyle('A2:G2')->getFont()->setSize(8);
		$border = [
			'borders' => [
				'allBorders' => [
				'borderStyle' => Border::BORDER_THIN,
				'color' => ['argb' => 'FF000000'],
				],
			],
		];
		$sheet->getStyle('A1:G1')->applyFromArray($border);
		$sheet->getStyle('A2:G2')->applyFromArray($border);
		$datos = $this->Reportes_Model->externosHoja($datos["hojaInicio"], $datos["hojaFin"]);
		$number = 1;
		$flag = 3;
		$totalHonorariosH = 0;
		$totalHonorariosP = 0;
		foreach($datos as $d){
			if($d->anulada == 0){
				$sheet->setCellValue('A'.$flag, $d->codigoHoja);
				// $sheet->setCellValue('B'.$flag, $d->correlativoSalidaHoja);
				$sheet->setCellValue('B'.$flag, $d->fechaHoja);
				$sheet->setCellValue('C'.$flag, $d->salidaHoja);
				$sheet->setCellValue('D'.$flag, $d->nombrePaciente);
				$sheet->setCellValue('E'.$flag, $d->nombreExterno);
				$sheet->setCellValue('F'.$flag, ($d->cantidadExterno * $d->precioExterno));
				$sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				if($d->porPagos == 1 && $d->esPaquete == 0){
					$sheet->setCellValue('G'.$flag, "Banco");
					$totalHonorariosP += ($d->cantidadExterno * $d->precioExterno); // Sumando honorarios en hojas de cobro
				}else{
					$sheet->setCellValue('G'.$flag, '---');
					$totalHonorariosH += ($d->cantidadExterno * $d->precioExterno); // Sumando honorarios en paquetes
				}

				$sheet->getStyle('A'.$flag.':G'.$flag)->getFont()->setSize(8);
                $sheet->getStyle('A'.$flag.':G'.$flag)->applyFromArray($border);

				$flag = $flag+1;
				$number = $number+1;

			}
		}
		$sheet->setCellValue('E'.$flag, "Total");
		$sheet->setCellValue('F'.$flag, number_format($totalHonorariosH + $totalHonorariosP, 2));
		$sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
		$flag++;
		$sheet->setCellValue('E'.$flag, "Por abonos");
		$sheet->setCellValue('F'.$flag, number_format($totalHonorariosP, 2));
		$sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
		$flag++;
		$sheet->setCellValue('E'.$flag, "Cuentas normales");
		$sheet->setCellValue('F'.$flag, number_format($totalHonorariosH, 2));
		$sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

		// $sheet->getStyle('A'.$flag.':G'.$flag)->getFont()->setSize(8);
		// $sheet->getStyle('A'.$flag.':G'.$flag)->applyFromArray($border);
		
		$styleThinBlackBorderOutline = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => Border::BORDER_THIN,
							'color' => ['argb' => 'FF000000'],
						],
					],
				];
		//Font BOLD
		$sheet->getStyle('A1:G1')->getFont()->setBold(true);		
		$sheet->getStyle('A2:G2')->getFont()->setBold(true);		

		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth('6');
		$sheet->getColumnDimension('B')->setWidth('9');
		$sheet->getColumnDimension('C')->setWidth('9');
		$sheet->getColumnDimension('D')->setWidth('25');
		$sheet->getColumnDimension('E')->setWidth('30');
		$sheet->getColumnDimension('F')->setWidth('10');
		$sheet->getColumnDimension('G')->setWidth('10');
		

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'reporte_externos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
    }

	public function rx_laboratorio(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/rx_laboratorio");
		$this->load->view("Base/footer");
	}

	public function generar_rx_laboratorio(){
		echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
		// Recibo
			if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/rx_laboratorio");
			}else{
				$data["hojasCerradas"] = $this->Reportes_Model->hojasCerradas($datos['fechaInicio'], $datos['fechaFin']);
				// Factura
					$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
					$mpdf = new \Mpdf\Mpdf([
						'margin_left' => 15,
						'margin_right' => 15,
						'margin_top' => 15,
						'margin_bottom' => 15,
						'margin_header' => 10,
						'margin_footer' => 10
					]);
					//$mpdf->setFooter('{PAGENO}');
					$mpdf->SetHTMLFooter('
						<table width="100%">
							<tr>
								<td width="33%"><strong>{DATE j-m-Y}</strong></td>
								<td width="33%" align="center"><strong>{PAGENO}/{nbpg}</strong></td>
								<td width="33%" style="text-align: right;"><strong>Reporte de Laboratorio, RX y Ultras</strong></td>
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
					$mpdf->AddPage('L'); //Voltear Hoja

					$html = $this->load->view('Reportes/rx_laboratorio_pdf', $data,true); // Cargando hoja de estilos

					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
				// Factura
			
		
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/rx_laboratorio");
		}

		//echo json_encode($datos);
	}

	public function generar_rx_laboratorio_salida(){
		echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
		// Recibo
			if ($datos['numeroInicio'] > $datos['numeroFin']) {
				$this->session->set_flashdata("error","El inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/rx_laboratorio");
			}else{
				$data["hojasCerradas"] = $this->Reportes_Model->hojasCerradasCorrelativoSalida($datos['numeroInicio'], $datos['numeroFin']);
				
				// Factura
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
								<td width="33%" style="text-align: right;"><strong>Reporte de Laboratorio, RX y Ultras</strong></td>
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
					$mpdf->AddPage('L'); //Voltear Hoja

					$html = $this->load->view('Reportes/rx_laboratorio_pdf', $data,true); // Cargando hoja de estilos

					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
				// Fun factura
			

			//  echo json_encode($data["hojasCerradas"]);
		
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/rx_laboratorio");
		}
	}

	// Liquidacio de caja

	public function obtener_min_max(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Reportes_Model->minMaxCajas($datos);
			echo json_encode($resp);

		} 
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
		}
	}


	public function liquidacion_caja(){

		$data["cajas"] = $this->Reportes_Model->obtenerCajas();

		// todas las cajas juntas
			/* $correlativo = $this->Reportes_Model->numeroCorrelativo(); // Ultimo cuenta cobrada //110
			$ultimoLiquidado = $this->Reportes_Model->ultimoLiquidacionGenerada(); // 84
			$data["numeroLimite"] = $correlativo->correlativo;
			
			if($ultimoLiquidado->ultimoLiquidado == 0){
				$data["inicio"] = 1;
			}else{
				if($ultimoLiquidado->ultimoLiquidado == $correlativo->correlativo){
					$data["inicio"] = $ultimoLiquidado->ultimoLiquidado;
				}else{
					$data["inicio"] = $ultimoLiquidado->ultimoLiquidado + 1 ;
				}
			} */
		// todas las cajas juntas

		// Correlativos para toda las cajas juntas
			$correlativo = $this->Reportes_Model->numeroCorrelativo();
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
			}
		// Correlativos para toda las cajas juntas



		$this->load->view("Base/header");
		$this->load->view("Reportes/liquidacion_caja", $data);
		$this->load->view("Base/footer");

		// echo json_encode($data);s
	}

	public function procesar_liquidacion(){
		$datos = $this->input->post();
		$pivote = $datos["pivoteLiquidacion"];
		// unset($datos["pivoteLiquidacion"]);

		$datos = urlencode(base64_encode(serialize($datos)));

		switch ($pivote) {
			case '0':
				unset($datos["pivoteLiquidacion"]);
				redirect(base_url()."Reportes/liquidacion_completa/".$datos);
				break;
				
			case '1':
				// redirect(base_url()."Reportes/liquidacion_hospital/".$datos); Version futura
				redirect(base_url()."Reportes/liquidacion_completa/".$datos); // Version solo hospital
				break;

			case '2':
				redirect(base_url()."Reportes/liquidacion_hemodialisis/".$datos);
				break;
				
			default:
				redirect(base_url()."Reportes/liquidacion_completa/".$datos);
				break;
		}
		
		// echo json_encode($datos);
	}


	public function liquidacion_completa($data = null){
		$datos = unserialize(base64_decode(urldecode($data)));;

		$fechaLiquidacion = $datos["fechaLiquidacion"];
		$param = date("Y-m-d");
		if($fechaLiquidacion > $param){
			$this->session->set_flashdata("error","La fecha es incorrecta.");
			redirect(base_url()."Reportes/liquidacion_caja");
		}else{
			$data = $datos;
			$datos["usuario"] = $this->session->userdata('id_usuario_h');
	
			$resp = $this->Reportes_Model->guardarLiquidacion($datos);
			$data["liquidacion"] = $resp;
			if($resp > 0){
				$params = urlencode(base64_encode(serialize($data)));
				redirect(base_url()."Reportes/liquidacion_caja_excel/".$params."/");
			}else{
				$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
				redirect(base_url()."Reportes/liquidacion_caja");
			}
		}
		//  echo json_encode($datos);
	}

	public function liquidacion_hospital($data = null){
		$datos = unserialize(base64_decode(urldecode($data)));
		$fechaLiquidacion = $datos["fechaLiquidacion"];
		unset($datos["fechaLiquidacion"]);
		$param = date("Y-m-d");

		if($fechaLiquidacion > $param){
			$this->session->set_flashdata("error","La fecha es incorrecta.");
			redirect(base_url()."Reportes/liquidacion_caja");
		}else{
			$data = $datos;
			$datos["fechaLiquidacion"] = $fechaLiquidacion;
			$datos["usuario"] = $this->session->userdata('id_usuario_h');
			$datos["nota"] = "Cuadre Hospital Orellana";

			$resp = $this->Reportes_Model->guardarLiquidacionXCaja($datos); // Guardando liquidacion
			$datos["liquidacion"] = $resp; // Liquidacion efectuada

			if($resp > 0){
				$params = urlencode(base64_encode(serialize($datos)));
				redirect(base_url()."Reportes/liquidacion_x_caja_excel/".$params."/");
			}else{
				$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
				redirect(base_url()."Reportes/liquidacion_caja");
			}
		}
	
	}
	
	public function liquidacion_hemodialisis($data = null){
		$datos = unserialize(base64_decode(urldecode($data)));
		$fechaLiquidacion = $datos["fechaLiquidacion"];
		unset($datos["fechaLiquidacion"]);
		$param = date("Y-m-d");

		if($fechaLiquidacion > $param){
			$this->session->set_flashdata("error","La fecha es incorrecta.");
			redirect(base_url()."Reportes/liquidacion_caja");
		}else{
			$data = $datos;
			$datos["fechaLiquidacion"] = $fechaLiquidacion;
			$datos["usuario"] = $this->session->userdata('id_usuario_h');
			$datos["nota"] = "Cuadre Unidad de Hemodialisis";

			$resp = $this->Reportes_Model->guardarLiquidacionXCaja($datos); // Guardando liquidacion
			$datos["liquidacion"] = $resp; // Liquidacion efectuada

			if($resp > 0){
				$params = urlencode(base64_encode(serialize($datos)));
				redirect(base_url()."Reportes/liquidacion_x_caja_excel/".$params."/");
			}else{
				$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
				redirect(base_url()."Reportes/liquidacion_caja");
			}
		}
	}

	
	public function liquidacion_x_caja_excel($params = null){
		$datos = unserialize(base64_decode(urldecode($params)));
		
		$encabezado = $this->Reportes_Model->detalleLiquidacion($datos["liquidacion"]);

		if(sizeof($datos) > 0){
			$inicio = $datos['hojaInicio'];
			$fin = $datos['hojaFin'];
			$data["hojas"] = $this->Reportes_Model->resumenXCaja($inicio, $fin, $encabezado->cajaLiquidacion);
					
			// Reporte PDF
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 50,
					'margin_bottom' => 25,
					'margin_header' => 15,
					'margin_footer' => 17
				]);

				//$mpdf->setFooter('{PAGENO}');
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA </strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$encabezado->empleado.'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.$encabezado->fechaLiquidacion.'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%" style="font-weight: bold; font-size: 12px;">'.date("Y-m-d").'</td>
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
				$html = $this->load->view("Reportes/liquidacion_caja_pdf",$data,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html);
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -ABONOS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$encabezado->empleado.'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.$encabezado->fechaLiquidacion.'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);
				

				$mpdf->Output($encabezado->notaLiquidacion, 'I');
				//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
			// Fin reporte PDF
 			
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/liquidacion_caja");
		}

		// echo json_encode($encabezado);
	}


	/* // Version anterior
	public function liquidacion_caja_excel(){
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
			$inicio = $datos['hojaInicio'];
			$fin = $datos['hojaFin'];
			$data1["hojas"] = $this->Reportes_Model->obtenerResumenHoja($inicio, $fin);
			$data2["abonos"] = $this->Reportes_Model->abonosLiquidar();
			// Reporte PDF
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 50,
					'margin_bottom' => 30,
					'margin_header' => 15,
					'margin_footer' => 23
				]);
				//$mpdf->setFooter('{PAGENO}');
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -CUENTAS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$this->session->userdata('empleado_h').'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.date('Y-m-d').'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%" style="font-weight: bold; font-size: 12px;">'.date("Y-m-d").'</td>
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
				$html = $this->load->view("Reportes/liquidacion_caja_pdf",$data1,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html);
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -ABONOS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$this->session->userdata('empleado_h').'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.date('Y-m-d').'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);
				$mpdf->AddPage();
				$html2 = $this->load->view("Reportes/liquidacion_abonos_pdf",$data2,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html2);

				$mpdf->Output('liquidacion_caja.pdf', 'I');
				//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
			// Fin reporte PDF
			// echo json_encode($data);
		

		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/liquidacion_caja");
		}
	} */

	// Version nueva
	public function liquidacion_caja_excel($params = null){
		$datos = unserialize(base64_decode(urldecode($params)));
		$encabezado = $this->Reportes_Model->detalleLiquidacion($datos["liquidacion"]);
		if(sizeof($datos) > 0){
			$inicio = $datos['hojaInicio'];
			$fin = $datos['hojaFin'];
			$data1["hojas"] = $this->Reportes_Model->obtenerResumenHoja($inicio, $fin);
			$data2["abonos"] = $this->Reportes_Model->abonosLiquidar();
			
			
			// Reporte PDF
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 50,
					'margin_bottom' => 25,
					'margin_header' => 15,
					'margin_footer' => 17
				]);

				//$mpdf->setFooter('{PAGENO}');
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -CUENTAS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$encabezado->empleado.'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.$encabezado->fechaLiquidacion.'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%" style="font-weight: bold; font-size: 12px;">'.date("Y-m-d").'</td>
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
				$html = $this->load->view("Reportes/liquidacion_caja_pdf",$data1,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html);
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -ABONOS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$encabezado->empleado.'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.$encabezado->fechaLiquidacion.'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);
				// Para mostrar abonos
				if(sizeof($data2["abonos"]) > 0){
					$mpdf->AddPage();
					$html2 = $this->load->view("Reportes/liquidacion_abonos_pdf",$data2,true); // Cargando hoja de estilos
					$mpdf->WriteHTML($html2);
				}


				$mpdf->Output('liquidacion_caja.pdf', 'I');
				//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
			// Fin reporte PDF

			$liquidar["abonos"] = $data2["abonos"];
			$liquidar["liquidacion"] = $datos["liquidacion"];
			$this->Reportes_Model->liquidarAbonos($liquidar);
			
			// echo json_encode($data1);
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/liquidacion_caja");
		}

		// echo json_encode($datos);
	}
	
	public function detalle_facturas_excell($inicio, $fin){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Fecha de ingreso');
		$sheet->setCellValue('B1', 'Fecha de egreso');
		$sheet->setCellValue('C1', 'Código de hoja');
		$sheet->setCellValue('D1', 'Nº salida');
		$sheet->setCellValue('E1', 'Paciente');
		$sheet->setCellValue('F1', 'Médico');
		$sheet->setCellValue('G1', 'Hospital');
		$sheet->setCellValue('H1', 'Externos');
		$sheet->setCellValue('I1', 'Total');

		$datos = $this->Reportes_Model->obtenerResumenHoja($inicio, $fin);
		$number = 1;
		$flag = 2;
		$globalMedicamentos = 0;
        $globalExternos = 0;
		foreach($datos as $d){
			if($d->anulada == 0){
				$sheet->setCellValue('A'.$flag, $d->fechaHoja);
				$sheet->setCellValue('B'.$flag, $d->salidaHoja);
				$sheet->setCellValue('C'.$flag, $d->codigoHoja);
				$sheet->setCellValue('D'.$flag, $d->correlativoSalidaHoja);
				$sheet->setCellValue('E'.$flag, $d->nombrePaciente);
				$sheet->setCellValue('F'.$flag, $d->nombreMedico); // HERE

			//////////////////////////////////////////////////////////////
				$totalHospital = 0;
				$totalExterno = 0;
				
				$medicamentos = $this->Reportes_Model->obtenerMedicamentos($d->idHoja);
				if(sizeof($medicamentos) > 0){
					foreach ($medicamentos as $hoja) {
						$totalHospital  += ($hoja->cantidadInsumo * $hoja->precioInsumo);
					   
					}
				}

				$externos = $this->Reportes_Model->obtenerExternos($d->idHoja);
				if(sizeof($externos) > 0){
					foreach ($externos as $hoja) {
						$totalExterno  += ($hoja->cantidadExterno * $hoja->precioExterno);
					   
					}
				}

			//////////////////////////////////////////////////////////////

				$sheet->setCellValue('G'.$flag, number_format($totalHospital, 2));

				$sheet->setCellValue('C'.$flag, number_format($totalExterno, 2));
				$sheet->setCellValue('I'.$flag, number_format(($totalHospital + $totalExterno), 2));

				$flag = $flag+1;
				$number = $number+1;
			}
		}


		$sheet->getStyle('A1:I1')->getFont()->setBold(true);		
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:I'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:I'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:I'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("A".($flag+1).":I".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'reporte_facturas'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function cobros_pacientes(){
		$data["medicos"] = $this->Reportes_Model->medicosCuentas();
		$this->load->view("Base/header");
		$this->load->view("Reportes/cobros_pacientes", $data);
		$this->load->view("Base/footer");
	}

	public function generar_cobros_pacientes(){
		$datos = $this->input->post();

		if(sizeof($datos) > 0){
			// Detalle
				if ($datos['fechaInicio'] > $datos['fechaFin']) {
					$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
					redirect(base_url()."Reportes/cobros_pacientes");
				}else{
					$i = $datos["fechaInicio"];
					$f = $datos["fechaFin"];
					$flag = $datos["tipoConsulta"];
					$data["personas"] = $this->Reportes_Model->cuentasPacientes($i, $f, $flag);
					
					// Factura
						$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
						$mpdf = new \Mpdf\Mpdf([
							'margin_left' => 15,
							'margin_right' => 15,
							'margin_top' => 10,
							'margin_bottom' => 15,
							'margin_header' => 10,
							'margin_footer' => 10
						]);
						//$mpdf->setFooter('{PAGENO}');
						$mpdf->SetHTMLFooter('
							<table width="100%">
								<tr>
									<td width="33%"><strong>{DATE j-m-Y}</strong></td>
									<td width="33%" align="center"><strong>{PAGENO}/{nbpg}</strong></td>
									<td width="33%" style="text-align: right;"><strong>Reporte cobros</strong></td>
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
						$mpdf->AddPage('L'); //Voltear Hoja

						$html = $this->load->view('Reportes/cobros_pacientes_pdf', $data,true); // Cargando hoja de estilos

						$mpdf->WriteHTML($html);
						$mpdf->Output('cuentas_pendientes.pdf', 'I');
					// Fin factura

					// echo json_encode($data["personas"]);
						
				}
			// Detalle
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/cobros_pacientes");
		}

		// echo json_encode($datos);
		
	}

	public function generar_cobros_pacientesm(){
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
		// Detalle
			if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/cobros_pacientes");
			}else{
				$i = $datos["fechaInicio"];
				$f = $datos["fechaFin"];
				$med = $datos["idMedico"];
				$flag = $datos["tipoConsulta"];
				$data["personas"] = $this->Reportes_Model->cuentasMedicos($i, $f, $med, $flag);
				
				// Factura
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
								<td width="33%" style="text-align: right;"><strong>Reporte de Laboratorio, RX y Ultras</strong></td>
							</tr>
						</table>');
					// $mpdf->SetProtection(array('print'));
					$mpdf->SetTitle("Hospital Orellana, Usulutan");
					$mpdf->SetAuthor("Edwin Orantes");
					//$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
					$mpdf->showWatermarkText = true;
					$mpdf->watermark_font = 'DejaVuSansCondensed';
					$mpdf->watermarkTextAlpha = 0.1;
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->AddPage('L'); //Voltear Hoja

					$html = $this->load->view('Reportes/cobros_pacientes_pdf', $data,true); // Cargando hoja de estilos

					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
		

		
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/cobros_pacientes");
		}
		// echo json_encode($datos);
		
	}

	public function detalle_medicamento(){
		$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
		$this->load->view("Base/header");
		$this->load->view("Reportes/detalle_medicamento", $data);
		$this->load->view("Base/footer");
	}

	public function generar_detalle_paciente(){
		$datos = $this->input->post();
		if ($datos['fechaInicio'] > $datos['fechaFin']) {
			$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
			redirect(base_url()."Reportes/detalle_medicamento");
		}else{
			$i = $datos["fechaInicio"];
			$f = $datos["fechaFin"];
			$med = $datos["idMedicamento"];
			$data["medicamento"] = $this->Reportes_Model->detalleMedicamento($i, $f, $med);

			if(sizeof($data["medicamento"]) > 0){
				// Factura
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
	
					$html = $this->load->view('Reportes/detalle_medicamento_pdf', $data,true); // Cargando hoja de estilos
	
					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
			}else{
				$this->session->set_flashdata("error","El medicamento no tiene movimientos en el rango de fecha seleccionado");
				redirect(base_url()."Reportes/detalle_medicamento");
			}

	

	
		}
	}

	public function generar_cobros(){
		$datos = $this->input->post();
		$generados = $this->Reportes_Model->seGeneroExternos($datos["fechaCorte"]);
		
		if(sizeof($generados) == 0){
			$datos["codigoVerificacion"] = md5($datos["codigoVerificacion"]);
			if($datos["codigoVerificacion"] == $this->session->userdata("verificacion")){
				unset($datos["codigoVerificacion"]);
				$this->extraer_cobros($datos["inicioCorte"], $datos["finCorte"]); // Guardando los externos generados
				if(sizeof($datos) > 0){
				// Recibo de gastos
					$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
					$mpdf = new \Mpdf\Mpdf([
						'margin_left' => 15,
						'margin_right' => 15,
						'margin_top' => 10,
						'margin_bottom' => 25,
						'margin_header' => 10,
						'margin_footer' => 23
						]);
					//$mpdf->setFooter('');
					//$mpdf->SetProtection(array('print'));
					$mpdf->SetTitle("Hospital Orellana, Usulutan");
					$mpdf->SetAuthor("Edwin Orantes");
					$mpdf->showWatermarkText = false;
					$mpdf->watermark_font = 'DejaVuSansCondensed';
					$mpdf->watermarkTextAlpha = 0.1;
					$mpdf->SetDisplayMode('fullpage');
					//$mpdf->AddPage('L'); //Voltear Hoja
					
					//Motor PHP
						$this->Reportes_Model->externosGenerados($datos); // Se guarda el rango de recibos que se guardo
						$hojas = $this->Reportes_Model->externosHoja($datos["inicioCorte"], $datos["finCorte"]);
						$cuentaGasto = 0;
						$flagGasto = 1;
						$gastosInsertados = array();
						
						// Obteniendo codigo de gasto
							$codigo = $this->Gastos_Model->codigoGasto(); // Ultimo codigo de hoja
							$cod = 0;
							if($codigo == NULL ){
								$cod = 1000000;
							}else{
								$cod = $codigo->codigo +1;
							}
							//var_dump($hojas);
						foreach ($hojas as $hoja) {
							if($hoja->anulada == 0){
								// Obtener si es proveedor o medico
									if($hoja->tipoEntidad == 1){
										$dato = $this->Reportes_Model->obtenerMedico($hoja->idExterno, $hoja->tipoEntidad);
										if($hoja->nombreExterno == $dato->nombre."(Honorarios)"){
											// $concepto = "Por pago de honorarios a <strong>".$dato->nombre."</strong>, ".$hoja->codigoHoja;
											$concepto = "Honorarios médicos, ".$hoja->nombrePaciente.", ".$hoja->codigoHoja;
										}else{
											//$concepto = "Por pago de servicio <strong>".$hoja->nombreExterno."</strong> a <strong>".$dato->nombre."</strong> ".$hoja->codigoHoja;
											$concepto = "Pago de servicio <strong>".$hoja->nombreExterno."</strong>, ".$hoja->nombrePaciente.", ".$hoja->codigoHoja;
		
										}
										$cuentaGasto = 10;
									}else{
										$dato = $this->Reportes_Model->obtenerMedico($hoja->idExterno, $hoja->tipoEntidad);
										// $concepto = "Por pago de <strong>".$hoja->nombreExterno."</strong> a <strong>".$dato->nombre."</strong> ".$hoja->codigoHoja;
										$concepto = "Pago de <strong>".$hoja->nombreExterno."</strong>, ".$hoja->nombrePaciente.", ".$hoja->codigoHoja;
										$cuentaGasto = 9;
									}
								// Obtener si es proveedor o medico
								//Detalle para base de datos
									$data["tipoGasto"] = 1;
									$data["montoGasto"] = ($hoja->cantidadExterno * $hoja->precioExterno);
									$data["entregadoGasto"] = $dato->nombre;
									$data["idCuentaGasto "] = $cuentaGasto;
									$data["fechaGasto"] = date('Y-m-d');
									$data["entidadGasto"] = $hoja->tipoEntidad;
									$data["idProveedorGasto "] = $dato->id;
									$data["pagoGasto"] = 1;
									$data["descripcionGasto"] = $concepto;
									$data["codigoGasto"] = $cod;
									$data["efectuoGasto"] = $this->session->userdata("empleado_h");
		
									$idGastoInsertado = $this->Gastos_Model->guardarGasto2($data);
									array_push($gastosInsertados, $idGastoInsertado);
								
									$cod++;
									$flagGasto++;
								//Detalle para base de datos
							}
						}
						$data["listaRecibos"] = $gastosInsertados;
					// Motor PHP
					$html = $this->load->view("Gastos/recibos_gastos", $data, true);
					$mpdf->WriteHTML($html);
					$mpdf->Output('resumen_hoja_cobro.pdf', 'I');
				// Recibo de gastos
		
				}else{
					$this->session->set_flashdata("error","No se permite el reenvio de datos");
					redirect(base_url()."Reportes/externos_hoja");
				}
			}else{
				$this->session->set_flashdata("error","No estas autorizado para este proceso");
				redirect(base_url()."Reportes/externos_hoja");
			}
		}else{
			$this->session->set_flashdata("error","Estos corte ya se llevo a cabo");
			redirect(base_url()."Reportes/externos_hoja");
		}
		
		//echo json_encode($datos);

	}

	public function extraer_cobros($inicio, $fin){
		for ($i=$inicio; $i <= $fin; $i++) { 
			$honorarios = $this->Reportes_Model->extraerExternos($inicio);
			if(sizeof($honorarios) > 0){ // Porque hay hojas de cobro que no tienen externos
				foreach ($honorarios as $honorario) {
					$arregloHonorario["salida"] = $honorario->correlativoSalidaHoja;
					$arregloHonorario["externo"] = $honorario->idExterno;
					$arregloHonorario["hojaExterno"] = $honorario->idHojaExterno;
					$arregloHonorario["idHoja"] = $honorario->idHoja;
					$arregloHonorario["precio"] = $honorario->cantidadExterno * $honorario->precioExterno;
					$arregloHonorario["estado"] = 0;
					if($honorario->credito_fiscal == ""){
						$arregloHonorario["facturar"] = 0;
					}else{
						$arregloHonorario["facturar"] = 1;
					}
					$arregloHonorario["enBanco"] = '0';
					/* if($honorario->esPaquete == 1){
						$arregloHonorario["enBanco"] = '0';
					}else{
						$arregloHonorario["enBanco"] = $honorario->porPagos;
					} */
					$resp = $this->Reportes_Model->guardarHonorarios($arregloHonorario);
				}
			}
			$inicio++;
		}
	}

	public function detalle_gastos(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/detalle_gastos");
		$this->load->view("Base/footer");
	}

	// generar_detalle_gastos EN PDF
		/* public function generar_detalle_gastos(){
			echo '<script>
					if (window.history.replaceState) { // verificamos disponibilidad
						window.history.replaceState(null, null, window.location.href);
					}
				</script>';
			$datos = $this->input->post();
			if(sizeof($datos) > 0){
			// Detalle
				if ($datos['fechaInicio'] > $datos['fechaFin']) {
					$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
					redirect(base_url()."Reportes/detalle_gastos");
				}else{
					$data["gastos"] = $this->Gastos_Model->obtenerGastos($datos['fechaInicio'], $datos['fechaFin']);
					$data["inicio"] = $datos['fechaInicio'];
					$data["fin"] = $datos['fechaFin'];
					// Factura
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
									<td width="33%" style="text-align: right;"><strong>Reporte de Gastos</strong></td>
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
						$mpdf->AddPage('L'); //Voltear Hoja

						$html = $this->load->view('Reportes/detalle_gastos_pdf', $data,true); // Cargando hoja de estilos

						$mpdf->WriteHTML($html);
						$mpdf->Output('detalle_compra.pdf', 'I');

			
				}
			}else{
				$this->session->set_flashdata("error","No se permite el reenvio de datos");
				redirect(base_url()."Reportes/detalle_gastos");
			}
		} */
	public function generar_detalle_gastos(){
		/* echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
		</script>'; */
		$datos = $this->input->post();
		
		if(sizeof($datos) > 0){
		// Detalle
			if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/detalle_gastos");
			}else{
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 'Código');
				$sheet->setCellValue('B1', 'Cuenta');
				$sheet->setCellValue('C1', 'Descripción');
				$sheet->setCellValue('D1', 'Pago');
				$sheet->setCellValue('E1', 'Cheque');
				$sheet->setCellValue('F1', 'Entregado');
				$sheet->setCellValue('G1', 'Fecha');
				$sheet->setCellValue('H1', 'Total');
					
				$datos = $this->Gastos_Model->obtenerGastos($datos['fechaInicio'], $datos['fechaFin']);

				$number = 1;
				$flag = 2;
				foreach($datos as $d){
						$sheet->setCellValue('A'.$flag, $d->codigoGasto);
						$sheet->setCellValue('B'.$flag, $d->nombreCuenta);
						$sheet->setCellValue('C'.$flag, strip_tags($d->descripcionGasto));
						
						switch ($d->pagoGasto) {
							case '1':
								$sheet->setCellValue('D'.$flag, 'Efectivo');
								$sheet->setCellValue('E'.$flag, '');
								break;
							case '2':
								$sheet->setCellValue('D'.$flag, 'Cheque');
								$sheet->setCellValue('E'.$flag, $d->numeroGasto);
								break;
							case '3':
								$sheet->setCellValue('D'.$flag, 'Caja Chica');
								$sheet->setCellValue('E'.$flag, '');
								break;
							case '4':
								$sheet->setCellValue('D'.$flag, 'Cargo a cuenta');
								$sheet->setCellValue('E'.$flag, '');
								break;
							
							default:
								$sheet->setCellValue('D'.$flag, 'Efectivo');
								$sheet->setCellValue('E'.$flag, '');
								break;
						}
						
						/* if($d->pagoGasto == 1){
							$sheet->setCellValue('D'.$flag, 'Efectivo');
							$sheet->setCellValue('E'.$flag, '');
						}else{
							if($d->pagoGasto == 3){
								$sheet->setCellValue('D'.$flag, 'Caja Chica');
								$sheet->setCellValue('E'.$flag, $d->numeroGasto);
							}else{
								$sheet->setCellValue('D'.$flag, 'Cheque');
								$sheet->setCellValue('E'.$flag, $d->numeroGasto);
							}
						} */

						$sheet->setCellValue('F'.$flag, $d->entregadoGasto);
						$sheet->setCellValue('G'.$flag, $d->fechaGasto);
						$sheet->setCellValue('H'.$flag, $d->montoGasto);
							
						$flag = $flag+1;
						$number = $number+1;
				}
				
				
				$styleThinBlackBorderOutline = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => Border::BORDER_THIN,
									'color' => ['argb' => 'FF000000'],
								],
							],
						];
				//Font BOLD
				$sheet->getStyle('A1:F1')->getFont()->setBold(true);		
				//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
				//Alignment
				//fONT SIZE
				$sheet->getStyle('A1:H'.$flag)->getFont()->setSize(12);
				$sheet->getStyle('A1:H'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

				$sheet->getStyle('A1:H'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					//Custom width for Individual Columns
				$sheet->getColumnDimension('A')->setAutoSize(true);
				$sheet->getColumnDimension('B')->setAutoSize(true);
				$sheet->getColumnDimension('C')->setAutoSize(true);
				$sheet->getColumnDimension('D')->setAutoSize(true);
				$sheet->getColumnDimension('E')->setAutoSize(true);
				$sheet->getColumnDimension('F')->setAutoSize(true);
				$sheet->getColumnDimension('G')->setAutoSize(true);
				$sheet->getColumnDimension('H')->setAutoSize(true);

				$curdate = date('d-m-Y H:i:s');
				$writer = new Xlsx($spreadsheet);
				$filename = 'reporte_gastos'.$curdate;
				ob_end_clean();
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
				header('Cache-Control: max-age=0');
				$writer->save('php://output');
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/detalle_gastos");
		}
	}

	public function detalle_facturas(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/detalle_facturas");
		$this->load->view("Base/footer");
	}

	public function detalle_facturas_excel(){
		$datos = $this->input->post();
		if ($datos['hojaInicio'] > $datos['hojaFin']) {
			$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
			redirect(base_url()."Reportes/detalle_facturas");
		}else{

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Paciente');
			$sheet->setCellValue('B1', 'Tipo de hoja');
			$sheet->setCellValue('C1', 'Tipo Factura');
			$sheet->setCellValue('D1', 'Número Factura');
			$sheet->setCellValue('E1', 'Fecha Factura');
			$sheet->setCellValue('F1', 'Total Factura');

			$datos = $this->Reportes_Model->facturasCredito($datos['hojaInicio'], $datos['hojaFin']);
			$number = 1;
			$flag = 2;
			$totalFactura = 0;
			$totalGlobal = 0;
			foreach($datos as $d){
				$sheet->setCellValue('A'.$flag, $d->nombrePaciente);
				$sheet->setCellValue('B'.$flag, $d->tipoHoja);
				$sheet->setCellValue('C'.$flag, "Consumidor final");
				$sheet->setCellValue('D'.$flag, $d->numeroFactura);
				$sheet->setCellValue('E'.$flag, substr($d->fechaFactura, 0, 10));

				$medicamentos = $this->Reportes_Model->obtenerMedicamentos($d->idHoja);
				$externos = $this->Reportes_Model->obtenerExternos($d->idHoja);
				foreach ($medicamentos as $medicamento) {
					$totalFactura += $medicamento->precioInsumo * $medicamento->cantidadInsumo;
				}

				/* foreach ($externos as $externo) {
					$totalFactura += $externo->precioExterno * $externo->cantidadExterno;
				} */

				$sheet->setCellValue('F'.$flag, "$".number_format($totalFactura, 2));
					
				$flag = $flag+1;
				$number = $number+1;
				$totalGlobal += $totalFactura;
				$totalFactura = 0;
			}
			/*$sheet->setCellValue('A'.($flag + 1), "TOTAL");
			$sheet->setCellValue('E'.($flag + 1), "$".number_format($totalGlobal, 2));
			$sheet->mergeCells("A".($flag+1).":D".($flag+1));*/
			
			$style = [
						'borders' => [
							'allBorders' => [
								'borderStyle' => Border::BORDER_THIN,
								'color' => ['argb' => 'FF000000'],
							],
						],
					];
			/*$style2 = [
						'font' => [
							'bold' => true,
							'color' => ['argb' => 'FFFFFFFF'],
						],
						'fill' => [
							'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
							'rotation' => 90,
							'startColor' => [
								'argb' => '0b99d0',
							],
							'endColor' => [
								'argb' => '0b99d0',
							],
						],
					];*/
			//Font BOLD

			//$sheet->getStyle("A".($flag+1).":E".($flag+1))->applyFromArray($style2);
			//$sheet->getStyle("A".($flag+1).":E".($flag+1))->applyFromArray($style);
			//$sheet->getStyle('A1:E1')->applyFromArray($style2);

			$sheet->getStyle('A1:F1')->getFont()->setBold(true);		
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:F'.$flag)->getFont()->setSize(12);
			$sheet->getStyle('A1:F'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			$sheet->getStyle('A1:F'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("A".($flag+1).":F".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

			//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setAutoSize(true);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getColumnDimension('C')->setAutoSize(true);
			$sheet->getColumnDimension('D')->setAutoSize(true);
			$sheet->getColumnDimension('E')->setAutoSize(true);
			$sheet->getColumnDimension('F')->setAutoSize(true);

			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'reporte_facturas'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
				
		}
	}

	public function ingresos_costos_gastos(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/ingresos_costos_gastos");
		$this->load->view("Base/footer");
	}

	public function generar_icg(){
		$data["meses"] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		$datos = $this->input->post();
		
		if(sizeof($datos) > 0){
			if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/ingresos_costos_gastos");
			}else{
				$i = $datos["fechaInicio"];
				$f = $datos["fechaFin"];

				$data["i"] = $i;
				$data["f"] = $f;
				$data["hojasCerradas"] = $this->Reportes_Model->hojasCerradas($i, $f);
				$data["gastos"] = $this->Reportes_Model->cuentasCostosGastos($i, $f);
				// Factura
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
								<td width="33%" style="text-align: right;"><strong>Detalle de Ingresos, Costos y Gastos</strong></td>
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
					// $mpdf->AddPage('L'); //Voltear Hoja

					$html = $this->load->view('Reportes/detalle_icg_pdf', $data,true); // Cargando hoja de estilos

					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/ingresos_costos_gastos");
		}
	}

	public function salidas_botiquin(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/salidas_botiquin");
		$this->load->view("Base/footer");
	}

	public function generar_detalle_movimientos(){
		echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
		$datos = $this->input->post();
		if(sizeof($datos) == 0){
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/salidas_botiquin");
		}else{
			if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/salidas_botiquin");
			}else{
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 'Medicamento');
				$sheet->setCellValue('B1', 'Total Usadas');
				$sheet->setCellValue('C1', 'Por Hospital');
				$sheet->setCellValue('D1', 'Por BM');
				// $sheet->setCellValue('E1', 'Stock Medicamento');

				$number = 1;
				$flag = 2;

				// Valores requeridos

				$mUsados = $this->Reportes_Model->obtenerMedicamentosUsados($datos['fechaInicio'], $datos['fechaFin']);
				foreach ($mUsados as $medicamento) {
					if($medicamento->tipoMedicamento == "Medicamentos"){
						$totalUsadas = 0;
						$totalHospital = 0;
						$totalBM = 0;
						$dMedicamento = $this->Reportes_Model->detalleMovimientoMedicamento($medicamento->idMedicamento, $datos['fechaInicio'], $datos['fechaFin']);
						foreach ($dMedicamento as $detalle) {
							if($detalle->descripcionMedicamento == "Salida"){
								$totalUsadas += $detalle->cantidadMedicamento;
								if ($detalle->ocupadaPor == "Bienestar") {
									$totalBM += $detalle->cantidadMedicamento;
								}else{
									$totalHospital += $detalle->cantidadMedicamento;
								}
							}
							
						}
						
						// Imprimiendo detalle
						$sheet->setCellValue('A'.$flag, $medicamento->nombreMedicamento);
						$sheet->setCellValue('B'.$flag, $totalUsadas);
						$sheet->setCellValue('C'.$flag, $totalHospital);
						$sheet->setCellValue('D'.$flag, $totalBM);
						//$sheet->setCellValue('E'.$flag, $medicamento->stockMedicamento);
						$flag = $flag+1;
						$number = $number+1;
					}
				}

				// Fin valores requeridos

				$style = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => Border::BORDER_THIN,
							'color' => ['argb' => 'FF000000'],
						],
					],
				];
				$style2 = [
							'font' => [
								'bold' => true,
								'color' => ['argb' => 'FFFFFFFF'],
							],
							'fill' => [
								'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
								'rotation' => 90,
								'startColor' => [
									'argb' => '0b99d0',
								],
								'endColor' => [
									'argb' => '0b99d0',
								],
							],
						];
				//Font BOLD

				$sheet->getStyle('A1:D1')->getFont()->setBold(true);		
				//Alignment
				//fONT SIZE
				$sheet->getStyle('A1:D'.$flag)->getFont()->setSize(12);
				$sheet->getStyle('A1:D'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

				$sheet->getStyle('A1:D'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

				//Custom width for Individual Columns
				$sheet->getColumnDimension('A')->setAutoSize(true);
				$sheet->getColumnDimension('B')->setAutoSize(true);
				$sheet->getColumnDimension('C')->setAutoSize(true);
				$sheet->getColumnDimension('D')->setAutoSize(true);
				//$sheet->getColumnDimension('E')->setAutoSize(true);

				$curdate = date('d-m-Y H:i:s');
				$writer = new Xlsx($spreadsheet);
				$filename = 'reporte_facturas'.$curdate;
				ob_end_clean();
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
				header('Cache-Control: max-age=0');
				$writer->save('php://output');
				
			}
		}
		
	}

	public function detalle_compras(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/detalle_compras");
		$this->load->view("Base/footer");
	}

	public function generar_detalle_compras(){
		$datos = $this->input->post();
		if ($datos['fechaInicio'] > $datos['fechaFin']) {
			$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
			redirect(base_url()."Reportes/detalle_facturas");
		}else{

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Proveedor');
			$sheet->setCellValue('B1', 'Factura');
			$sheet->setCellValue('C1', 'Fecha compra');
			$sheet->setCellValue('D1', 'Estado');
			$sheet->setCellValue('E1', 'Total');

			$datos = $this->Reportes_Model->detalleCompras($datos['fechaInicio'], $datos['fechaFin']);
			$flag = 2;
			$estado = "";
			foreach($datos as $d){
				if($d->estadoCuentaPagar == 0){
					$estado = "Saldada";
				}else{
					$estado = "Pendiente";
				}
				$sheet->setCellValue('A'.$flag, $d->empresaProveedor);
				$sheet->setCellValue('B'.$flag, $d->facturaCuentaPagar);
				$sheet->setCellValue('C'.$flag, $d->fechaCuentaPagar);
				$sheet->setCellValue('D'.$flag, $estado);
				$sheet->setCellValue('E'.$flag, $d->totalCuentaPagar);
				$flag++;
			}
			
			$style = [
						'borders' => [
							'allBorders' => [
								'borderStyle' => Border::BORDER_THIN,
								'color' => ['argb' => 'FF000000'],
							],
						],
					];
			
			//Font BOLD

			$sheet->getStyle('A1:E1')->getFont()->setBold(true);		
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:E'.$flag)->getFont()->setSize(12);
			$sheet->getStyle('A1:E'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			$sheet->getStyle('A1:E'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("A".($flag+1).":E".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

			//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setAutoSize(true);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getColumnDimension('C')->setAutoSize(true);
			$sheet->getColumnDimension('D')->setAutoSize(true);
			$sheet->getColumnDimension('E')->setAutoSize(true);

			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'reporte_compras'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
				
		}
	}

	// Nuevo reporte de total por externos
	public function total_x_externo(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'NOMBRE');
		$sheet->setCellValue('C1', 'TOTAL');

		$border = [
            'borders' => [
                'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
		
		$sheet->getStyle('A1:C1')->applyFromArray($border);

		$datos = $this->Reportes_Model->listaExterno();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$totalHonorarios = $this->Reportes_Model->totalPorExterno($d->idExterno);
			if($totalHonorarios->total != null && $totalHonorarios->total > 0){
				$sheet->setCellValue('A'.$flag, $number);
				$sheet->setCellValue('B'.$flag, str_replace("(Honorarios)", "", $d->nombreExterno));
				$sheet->setCellValue('C'.$flag, $totalHonorarios->total);

				$sheet->getStyle('A'.$flag.':C'.$flag)->applyFromArray($border);
				$sheet->getStyle('C'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$flag = $flag+1;
				$number = $number+1;
			}
		}
		
		
		$styleThinBlackBorderOutline = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => Border::BORDER_THIN,
							'color' => ['argb' => 'FF000000'],
						],
					],
				];
		//Font BOLD
		$sheet->getStyle('A1:C1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:C'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:C'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:C'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setWidth(75);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'resumen_honorarios'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	// Paquetes sin liquidar
	public function paquetes_sin_liquidar(){
		echo "Holalaalalal";
	}

	// Reporte de USG y RX
	public function usg_rx(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/usg_rx");
		$this->load->view("Base/footer");
	}

	public function generar_usg_rx(){
		$datos = $this->input->post();
		if ($datos['fechaInicio'] > $datos['fechaFin']) {
			$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor a la fecha fin");
			redirect(base_url()."Reportes/usg_rx");
		}else{
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Paciente');
			$sheet->setCellValue('B1', 'Examen');
			$sheet->setCellValue('C1', 'Fecha');
			$sheet->setCellValue('D1', 'Cantidad');
			$sheet->setCellValue('E1', 'Precio');
			$sheet->setCellValue('F1', 'Total');
			
			$datos = $this->Reportes_Model->obtenerUSGRX($datos);
			// echo json_encode($datos);
			$number = 1;
			$flag = 2;
			$globalMedicamentos = 0;
			$globalExternos = 0;
			foreach($datos as $d){
					$sheet->setCellValue('A'.$flag, $d->nombrePaciente);
					$sheet->setCellValue('B'.$flag, $d->nombreMedicamento);
					$sheet->setCellValue('C'.$flag, $d->salidaHoja);
					$sheet->setCellValue('D'.$flag, $d->cantidadInsumo);
					$sheet->setCellValue('E'.$flag, $d->precioInsumo);
					$sheet->setCellValue('F'.$flag, number_format(($d->cantidadInsumo * $d->precioInsumo), 2));
	
					$flag = $flag+1;
					$number = $number+1;
			}
	
	
			$sheet->getStyle('A1:F1')->getFont()->setBold(true);		
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:F'.$flag)->getFont()->setSize(12);
			$sheet->getStyle('A1:F'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	
			$sheet->getStyle('A1:F'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("A".($flag+1).":F".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	
			//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setAutoSize(true);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getColumnDimension('C')->setAutoSize(true);
			$sheet->getColumnDimension('D')->setAutoSize(true);
			$sheet->getColumnDimension('E')->setAutoSize(true);
			$sheet->getColumnDimension('F')->setAutoSize(true);
	
			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'reporte_usg_rx'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}

	}


	public function cobros_excel(){
		$datos['fechaInicio'] = '2023-06-01';
		$datos['fechaFin'] = '2023-07-01';
		$datos['tipoConsulta'] = '3';

		if(sizeof($datos) > 0){
		// Detalle
			if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Reportes/cobros_pacientes");
			}else{
				$i = $datos["fechaInicio"];
				$f = $datos["fechaFin"];
				$flag = $datos["tipoConsulta"];
				$personas = $this->Reportes_Model->cuentasPacientes($i, $f, $flag);

				$medGlobal = 0;
				$matmeGlobal = 0;
				$servGlobal = 0;
				$oservGlobal = 0;
				$extGlobal = 0;
				foreach ($personas as $persona) {
					if($persona->anulada == 0){
						$id = $persona->idHoja;
						$externosHoja = $this->Hoja_Model->externosHoja($id);
						$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($id);
						foreach ($medicamentosHoja as $medicamento) {
							//$totalGlobalHoja += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);

							// Suma medicamentos y materiales medicos
							switch ($medicamento->tipoMedicamento) {
								case 'Medicamentos':
									$medGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
									break;
								case 'Materiales médicos':
									$matmeGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
									break;
								case 'Servicios':
									$servGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
									break;
								case 'Otros servicios':
									$oservGlobal += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
									break;
							}
						}

						foreach ($externosHoja as $externo) {
							//$totalGlobalHoja += ($externo->precioExterno * $externo->cantidadExterno);
							$extGlobal+= ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
						}
					}
				} // End Foreach

				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 'Fecha de ingreso');
				$sheet->setCellValue('B1', 'Fecha de egreso');
				$sheet->setCellValue('C1', 'Recibo');
				$sheet->setCellValue('D1', 'HC');
				$sheet->setCellValue('E1', 'Paciente');
				$sheet->setCellValue('F1', 'Médico');
				$sheet->setCellValue('G1', 'Medicamentos');
				$sheet->setCellValue('H1', 'Materiales');
				$sheet->setCellValue('I1', 'Total');
				$sheet->setCellValue('J1', 'Servicios');
				$sheet->setCellValue('K1', 'Otros servicios');
				$sheet->setCellValue('L1', 'Total');
				$sheet->setCellValue('M1', 'Externos');
				$sheet->setCellValue('N1', 'Total Hoja');
				$sheet->setCellValue('O1', 'CF');


                            
				foreach ($personas as $persona) {
					$sheet->setCellValue('A'.$flag, $persona->fechaHoja);
					if($persona->salidaHoja == ""){
						$sheet->setCellValue('B1', '---');
					}else{
						$sheet->setCellValue('B1', $persona->salidaHoja);
					}
					if($persona->correlativoSalidaHoja == 0){
						$sheet->setCellValue('C1', '---');
					}else{
						$sheet->setCellValue('C1', $persona->correlativoSalidaHoja);
					}
					$sheet->setCellValue('C1', $persona->codigoHoja);
					$sheet->setCellValue('D1', $persona->nombrePaciente);
					$sheet->setCellValue('F1', $persona->nombreMedico);

					$id = $persona->idHoja;
					$externosHoja = $this->Hoja_Model->externosHoja($id);
					$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($id);
					$med = 0;
					$matme = 0;
					$serv = 0;
					$oserv = 0;
					$ext = 0;
					
					foreach ($medicamentosHoja as $medicamento) {
						//$totalGlobalHoja += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);

						// Suma medicamentos y materiales medicos
						switch ($medicamento->tipoMedicamento) {
							case 'Medicamentos':
								$med += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								//$medGlobal += $med;
								break;
							case 'Materiales médicos':
								$matme += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								//$matmeGlobal += $matme;
								break;
							case 'Servicios':
								$serv += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								//$serGlobal += $serv;
								break;
							case 'Otros servicios':
								$oserv += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								//$oservGlobal += $oserv;
								break;
						}
					}

					foreach ($externosHoja as $externo) {
						//$totalGlobalHoja += ($externo->precioExterno * $externo->cantidadExterno);
						$ext += ($externo->precioExterno * $externo->cantidadExterno); // Para el total de los externos
						//$extGlobal += $ext;
					}
				// Fin total hoja de cobro.
					$sheet->setCellValue('G1', number_format($med, 2));
					$sheet->setCellValue('H1', number_format($matme, 2));
					$sheet->setCellValue('I1', number_format(($med + $matme), 2));
					$sheet->setCellValue('J1', number_format($serv, 2));
					$sheet->setCellValue('K1', number_format($oserv, 2));
					$sheet->setCellValue('L1', number_format(($serv + $oserv), 2));
					$sheet->setCellValue('M1', number_format($ext, 2));
					$sheet->setCellValue('N1', number_format(($med + $matme + $serv + $oserv + $ext), 2));
					$sheet->setCellValue('O1', $persona->credito_fiscal);
				}


				$style = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => Border::BORDER_THIN,
									'color' => ['argb' => 'FF000000'],
								],
							],
						];

				$sheet->getStyle('A1:O1')->getFont()->setBold(true);		
				//Alignment
				//fONT SIZE
				$sheet->getStyle('A1:O'.$flag)->getFont()->setSize(12);
				$sheet->getStyle('A1:O'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

				$sheet->getStyle('A1:O'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle("A".($flag+1).":O".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

				//Custom width for Individual Columns
				$sheet->getColumnDimension('A')->setAutoSize(true);
				$sheet->getColumnDimension('B')->setAutoSize(true);
				$sheet->getColumnDimension('C')->setAutoSize(true);
				$sheet->getColumnDimension('D')->setAutoSize(true);
				$sheet->getColumnDimension('E')->setAutoSize(true);
				$sheet->getColumnDimension('F')->setAutoSize(true);
				$sheet->getColumnDimension('G')->setAutoSize(true);
				$sheet->getColumnDimension('H')->setAutoSize(true);
				$sheet->getColumnDimension('I')->setAutoSize(true);
				$sheet->getColumnDimension('J')->setAutoSize(true);
				$sheet->getColumnDimension('K')->setAutoSize(true);
				$sheet->getColumnDimension('L')->setAutoSize(true);
				$sheet->getColumnDimension('M')->setAutoSize(true);
				$sheet->getColumnDimension('N')->setAutoSize(true);
				$sheet->getColumnDimension('0')->setAutoSize(true);

				$curdate = date('d-m-Y H:i:s');
				$writer = new Xlsx($spreadsheet);
				$filename = 'reporte_cobros'.$curdate;
				ob_end_clean();
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
				header('Cache-Control: max-age=0');
				$writer->save('php://output');

				
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/cobros_pacientes");
		}
		
	}

	public function validar_fecha_liquidacion(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$fecha = $datos["fecha"];
			$resp = $this->Reportes_Model->existeLiquidacion($fecha);
			if($resp->liquidacion != 0){
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


	public function liquidacion_caja_edwin(){
		$inicio = 46223;
			$fin = 46276;
			$data1["hojas"] = $this->Reportes_Model->obtenerResumenHoja($inicio, $fin);
			
			// Reporte PDF
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 50,
					'margin_bottom' => 25,
					'margin_header' => 15,
					'margin_footer' => 17
				]);

				//$mpdf->setFooter('{PAGENO}');
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -CUENTAS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase"></th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left"></th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%" style="font-weight: bold; font-size: 12px;">'.date("Y-m-d").'</td>
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
				$html = $this->load->view("Reportes/liquidacion_caja_edwin",$data1,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html);
				$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
					<div id="lateral">
						<p><img src="'.base_url().'public/img/logo.jpg" alt="" width="225"></p>
					</div>
				
					<div id="principal">
						<table style="text-align: center;">
							<tr>
								<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
							</tr>
							<tr>
								<td><strong>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</strong></td>
							</tr>
						</table>
						
					</div>
					</div>
					<p style="margin-top: 0px; text-align: center"><strong>LIQUIDACION DE CAJA -ABONOS</strong></p>
					<table class="" style="width: 100%">
						<thead>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> USUARIO: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; text-transform: uppercase">'.$encabezado->empleado.'</th>
							</tr>
							<tr>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left; width: 100px"> FECHA: </th>
								<th style="padding-top: 2px; padding-bottom: 2px; font-size: 9px; text-align: left">'.$encabezado->fechaLiquidacion.'</th>
							</tr>
						</thead>
						<tbody><tbody>
					</table>'
				);

				$mpdf->Output('liquidacion_caja.pdf', 'I');
				//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
			// Fin reporte PDF

	}

	public function lista_liquidaciones(){
		$data["liquidaciones"] = $this->Reportes_Model->liquidacionesCajas();

		$this->load->view("Base/header");
		$this->load->view("Reportes/lista_liquidaciones", $data);
		$this->load->view("Base/footer");

		// echo json_encode($data);
	}
	
	public function detalle_liquidacion($l = null){
		// $data["liquidaciones"] = $this->Reportes_Model->liquidacionesCajas();
		$liquidacion = $this->Reportes_Model->obtenerLiquidacion($l);
		
		echo json_encode($liquidacion);
		
		// echo json_encode($data);
	}

}