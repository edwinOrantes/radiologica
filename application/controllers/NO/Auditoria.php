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

class Auditoria extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Auditoria_Model");
		$this->load->model("Reportes_Model");
	}

    public function index(){
		$this->load->view("Base/header");
		$this->load->view("Base/footer");
	}

    public function detalle_hojas(){
		$datos = $this->input->post();
        $datos['fechaInicio'] = '2020-01-01';
        $datos['fechaFin'] = '2024-01-01';
		if ($datos['fechaInicio'] > $datos['fechaFin']) {
			$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor a la fecha fin");
			redirect(base_url()."Reportes/usg_rx");
		}else{
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'CODIGO');
			$sheet->setCellValue('B1', 'RECIBO');
			$sheet->setCellValue('C1', 'PACIENTE');
			$sheet->setCellValue('D1', 'TIPO');
			$sheet->setCellValue('E1', 'HABITACION');
			$sheet->setCellValue('F1', 'F/E');
			$sheet->setCellValue('G1', 'F/S');
			$sheet->setCellValue('H1', 'MEDICAMENTOS');
			$sheet->setCellValue('I1', 'EXTERNOS');
			$sheet->setCellValue('J1', 'TOTAL');
			$sheet->setCellValue('K1', 'C/F');
			$sheet->setCellValue('L1', 'FECHA FACTURA');
			
			$datos = $this->Auditoria_Model->detalleHojas($datos);
			// echo json_encode($datos);
			$number = 1;
			$flag = 2;
			$globalMedicamentos = 0;
			$globalExternos = 0;
			foreach($datos as $d){
					$sheet->setCellValue('A'.$flag, $d->codigoHoja);
					$sheet->setCellValue('B'.$flag, $d->correlativoSalidaHoja);
					$sheet->setCellValue('C'.$flag, $d->nombrePaciente);
					$sheet->setCellValue('D'.$flag, $d->tipoHoja);
					$sheet->setCellValue('E'.$flag, $d->numeroHabitacion);
					$sheet->setCellValue('F'.$flag, $d->fechaHoja);
					$sheet->setCellValue('G'.$flag, $d->salidaHoja);
					$sheet->setCellValue('H'.$flag, $d->medicamentos);
					$sheet->setCellValue('I'.$flag, $d->externos);
					$sheet->setCellValue('J'.$flag, number_format(($d->medicamentos + $d->externos),2));
					$sheet->setCellValue('K'.$flag, $d->credito_fiscal);
					$sheet->setCellValue('L'.$flag, "---");
	
					$flag = $flag+1;
					$number = $number+1;
			}
	
	
			$sheet->getStyle('A1:L1')->getFont()->setBold(true);		
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:L'.$flag)->getFont()->setSize(12);
			$sheet->getStyle('A1:L'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	
			$sheet->getStyle('A1:L'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("A".($flag+1).":L".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	
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
	
			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'detalle_cuentas'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
           
		} 

	}

	public function cuentas_con_abonos(){
		$this->load->view("Base/header");
		$this->load->view("Auditoria/cuentas_con_abonos");
		$this->load->view("Base/footer");
	}

	public function cuentas_con_abonos_excel(){
		$datos = $this->Auditoria_Model->cuentasConAbonos();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'CODIGO');
		$sheet->setCellValue('B1', 'F/E	');
		$sheet->setCellValue('C1', 'F/S');
		$sheet->setCellValue('D1', '# RECIBO');
		$sheet->setCellValue('E1', 'FECHA R/P');
		$sheet->setCellValue('F1', 'PACIENTE');
		$sheet->setCellValue('G1', 'MEDICO');
		$sheet->setCellValue('H1', 'INTERNO');
		$sheet->setCellValue('I1', 'EXTERNO');
		$sheet->setCellValue('J1', 'TOTAL');
		$sheet->setCellValue('K1', 'ABONADO');
		$sheet->setCellValue('L1', 'TIPO HOJA');
		$sheet->setCellValue('M1', 'HABITACION');
		$sheet->setCellValue('N1', 'SEGURO');
		
		$number = 1;
		$flag = 2;
		$globalMedicamentos = 0;
		$globalExternos = 0;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->codigoHoja);
			$sheet->setCellValue('B'.$flag, $d->fechaHoja);
			$sheet->setCellValue('C'.$flag, $d->salidaHoja);
			$sheet->setCellValue('D'.$flag, $d->idAbono);
			$sheet->setCellValue('E'.$flag, $d->fechaAbono);
			$sheet->setCellValue('F'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('G'.$flag, $d->nombreMedico);
			$sheet->setCellValue('H'.$flag, $d->medicamentos);
			$sheet->setCellValue('I'.$flag, $d->externos);
			$sheet->setCellValue('J'.$flag, number_format($d->medicamentos + $d->externos, 2));
			$sheet->setCellValue('K'.$flag, $d->total_abonos);
			$sheet->setCellValue('L'.$flag, $d->tipoHoja);
			$sheet->setCellValue('M'.$flag, $d->numeroHabitacion);
			$sheet->setCellValue('N'.$flag, $d->nombreSeguro);
			
			$flag = $flag+1;
			$number = $number+1;
		}
		$sheet->getStyle('A1:N1')->getFont()->setBold(true);		
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:N'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:N'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:N'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("A".($flag+1).":N".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'cuentas_con_abonos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function cuentas_con_abonos_pdf(){
		$data["abonos"] = $this->Auditoria_Model->cuentasConAbonos();
		
		// Reporte Abonos
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
						<td width="33%"><strong>{DATE Y-m-d}</strong></td>
						<td width="33%" align="center"><strong>{PAGENO}/{nbpg}</strong></td>
						<td width="33%" style="text-align: right;"><strong>Abonos a cuentas pendientes</strong></td>
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

			$html = $this->load->view('Auditoria/abonos_pdf', $data,true); // Cargando hoja de estilos

			$mpdf->WriteHTML($html);
			$mpdf->Output('cuentas_con_abonos.pdf', 'I');
		// Reporte Abonos

		// echo json_encode($data["abonos"]);
	}

	public function liquidacion_caja(){
		$datos = $this->Reportes_Model->ultimaSalida();
		$data["numeroLimite"] = $datos->salida;
		$this->load->view("Base/header");
		$this->load->view("Auditoria/liquidacion_caja", $data);
		$this->load->view("Base/footer");
	}

	public function liquidacion_caja_excel(){
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
			$inicio = $datos['hojaInicio'];
			$fin = $datos['hojaFin'];
			$datos = $this->Auditoria_Model->liquidacionCaja($inicio, $fin);
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Fecha Ingreso ');
			$sheet->setCellValue('B1', 'Fecha Egreso');
			$sheet->setCellValue('C1', 'Código Hoja');
			$sheet->setCellValue('D1', 'N° Salida');
			$sheet->setCellValue('E1', 'Paciente');
			$sheet->setCellValue('F1', 'Médico');
			$sheet->setCellValue('G1', 'Hospital');
			$sheet->setCellValue('H1', 'Externos');
			$sheet->setCellValue('I1', 'Total');
			$sheet->setCellValue('J1', 'N° Factura');
			$sheet->setCellValue('K1', 'Fecha factura');
			
			$number = 1;
			$flag = 2;
			$globalMedicamentos = 0;
			$globalExternos = 0;
			foreach($datos as $d){
				$sheet->setCellValue('A'.$flag, $d->fechaHoja);
				$sheet->setCellValue('B'.$flag, $d->salidaHoja);
				$sheet->setCellValue('C'.$flag, $d->codigoHoja);
				$sheet->setCellValue('D'.$flag, $d->correlativoSalidaHoja);
				$sheet->setCellValue('E'.$flag, $d->nombrePaciente);
				$sheet->setCellValue('F'.$flag, $d->nombreMedico);
				$sheet->setCellValue('G'.$flag, $d->medicamentos);
				$sheet->setCellValue('H'.$flag, $d->externos);
				$sheet->setCellValue('I'.$flag, round($d->medicamentos + $d->externos, 2));
				$sheet->setCellValue('J'.$flag, $d->credito_fiscal);
				$sheet->setCellValue('K'.$flag, $d->fechaFactura);
				
				$flag = $flag+1;
				$number = $number+1;
			}
			$sheet->getStyle('A1:K1')->getFont()->setBold(true);		
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:K'.$flag)->getFont()->setSize(12);
			$sheet->getStyle('A1:K'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			$sheet->getStyle('A1:K'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("A".($flag+1).":k".($flag+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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

			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'liquidacion_de_caja'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output'); 
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Reportes/liquidacion_caja");
		}
	}


	public function cuentas_pendientes(){
		$this->load->view("Base/header");
		$this->load->view("Auditoria/cuentas_pendientes");
		$this->load->view("Base/footer");
	}

	public function cuentas_pendientes_pdf(){
		$data["abonos"] = $this->Auditoria_Model->cuentasPendientes();
		
		// Reporte Abonos
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
						<td width="33%"><strong>{DATE Y-m-d}</strong></td>
						<td width="33%" align="center"><strong>{PAGENO}/{nbpg}</strong></td>
						<td width="33%" style="text-align: right;"><strong>Abonos a cuentas pendientes</strong></td>
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

			$html = $this->load->view('Auditoria/pendientes_pdf', $data,true); // Cargando hoja de estilos

			$mpdf->WriteHTML($html);
			$mpdf->Output('cuentas_con_abonos.pdf', 'I');
		// Reporte Abonos

		// echo json_encode($data["abonos"]);
	}
}