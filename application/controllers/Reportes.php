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
// Clases para el reporte en excel

class Reportes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        if (!$this->session->has_userdata('valido')){
			redirect(base_url());
		}
		$this->load->model("Reportes_Model");
	}

	public function index(){
		$this->load->view("Base/header");
		$this->load->view("Reportes/resumen_consultas");
		$this->load->view("Base/footer");
	}

    public function resumen_consultas_excel(){
        $datos = $this->input->post();
        if ($datos['fechaInicio'] > $datos['fechaFin']) {
            $this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
            redirect(base_url()."Reportes/");
        }else{
            $consultas = $this->Reportes_Model->obtenerConsultas($datos);
            // echo json_encode($consultas);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'FECHA');
            $sheet->setCellValue('B1', 'PACIENTE');
            $sheet->setCellValue('C1', 'MEDICO');
            $sheet->setCellValue('D1', 'FACTURA');
            $sheet->setCellValue('E1', 'TIPO');
            $sheet->setCellValue('F1', 'EXAMEN');
            $sheet->setCellValue('G1', 'TOTAL');
            $sheet->setCellValue('H1', 'LECTURA');
            $sheet->getStyle('A1:H1')->getFont()->setSize(10);
            $border = [
                'borders' => [
                    'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A1:H1')->applyFromArray($border);
            $number = 1;
            $flag = 2;
            $total = 0;
            foreach($consultas as $d){
                $sheet->setCellValue('A'.$flag, $d->fechaConsulta);
                $sheet->setCellValue('B'.$flag, $d->nombrePaciente);
                $sheet->setCellValue('C'.$flag, $d->nombreMedico);
                $sheet->setCellValue('D'.$flag, $d->dteVenta);
                $sheet->setCellValue('E'.$flag, $d->notaFactura);
                $sheet->setCellValue('F'.$flag, $d->examenesRealizados);
                $sheet->getStyle('F'.$flag,)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('G'.$flag, $d->totalVenta);
                $sheet->getStyle('G'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $sheet->setCellValue('H'.$flag, "");

                $total += $d->totalVenta;
                $flag++;
            }
            $sheet->setCellValue('F'.$flag, "TOTAL");
            $sheet->setCellValue('G'.$flag, number_format($total, 2));
            $sheet->getStyle('G'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
            
            $styleThinBlackBorderOutline = [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ];
            //Font BOLD
            $sheet->getStyle('A1:H1')->getFont()->setBold(true);		

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
            $filename = 'resumen_consultas'.$curdate;
            ob_end_clean();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
           
            
        
    
        }
	

    }
	
}
