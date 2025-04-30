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


class Honorarios extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Honorarios_Model");
        $this->load->model("Externos_Model");
        $this->load->model("Medico_Model");
        $this->load->model("Gastos_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
        echo "Chinguenguencha aqui no hay nada";
    }

    public function gestion_honorarios(){
        $data["externos"] = $this->Externos_Model->obtenerExternos();
        $this->load->view("Base/header");
        $this->load->view("Honorarios/gestion_honorarios", $data);
        $this->load->view("Base/footer");
    }

    public function buscar_honorarios(){
        if($this->input->is_ajax_request()){
            $medico =$this->input->post("medico");
            $data = $this->Honorarios_Model->honorariosExterno(trim($medico));
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function saldar_honorario(){
        if($this->input->is_ajax_request()){
            $idHonorario =$this->input->post("idHonorario");
            $hoy = date('Y-m-d');
            $resp = $this->Honorarios_Model->saldarHonorario(trim($idHonorario), trim($hoy));
            if($resp){
                echo "1";
            }else{
                echo "0";
            }
        }
        else{
            echo "Error...";
        }
    }

    public function adeudar_honorario(){
        if($this->input->is_ajax_request()){
            $idHonorario =$this->input->post("idHonorario");
            $resp = $this->Honorarios_Model->adeudarHonorario(trim($idHonorario));
            if($resp){
                echo "1";
            }else{
                echo "0";
            }
        }
        else{
            echo "Error...";
        }
    }

    public function facturar_honorario(){
        if($this->input->is_ajax_request()){
            $idHonorario =$this->input->post("idHonorario");
            $facturar =$this->input->post("facturar");
            $resp = $this->Honorarios_Model->facturarHonorario(trim($idHonorario), trim($facturar));
            if($resp){
                echo "Se cambio estado a facturar";
            }else{
                echo "Se cambio estado a no facturar";
            }
        }
        else{
            echo "Error...";
        }
    }
    
    public function honorarios_pivote(){
        $datos = $this->input->post();
        $pivote = $datos["pivoteHonorario"];
        if($pivote == 1 ){
            $this->honorarios_pdf($datos);
        }else{
            $this->honorarios_excel($datos);
        }
    }

    public function honorarios_excel($data){
        // $datos = $this->input->post();
        $datos = $data;
        $honorarios = $this->Honorarios_Model->honorariosExterno(trim($datos["idMedico"]));
        $medico = "";
        foreach($honorarios as $d){
            $medico = $d->nombreExterno;
        }

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
        // Cargando imágen en Excel.
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('HCO');
            $drawing->setDescription('HCO');
            $drawing->setPath('./public/img/logo2.png'); /* put your path and image here */
            $drawing->setCoordinates('A1');
            $drawing->setWidthAndHeight(210, 215);
            $drawing->setOffsetX(110);
            $drawing->getShadow()->setVisible(false);
            $drawing->getShadow()->setDirection(45);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        // Fin cargar imagen

        $sheet->setCellValue('E2', 'HOSPITAL ORELLANA, USULUTAN');
        $sheet->mergeCells('E2:J2');
        $sheet->setCellValue('E3', 'Sexta calle oriente #8, Usulutan, El Salvador');
        $sheet->mergeCells('E3:J3');

        $derecha = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ];
        $sheet->getStyle('E2:J2')->applyFromArray($derecha);
        $sheet->getStyle('E3:J3')->applyFromArray($derecha);

        $sheet->getStyle('E2:J2')->getFont()->setBold(true);	
        $sheet->getStyle('E3:J3')->getFont()->setBold(true);
        
        $sheet->getStyle('E2:J2')->getFont()->setSize(10);
        $sheet->getStyle('E3:J3')->getFont()->setSize(10);


		$sheet->setCellValue('A5', 'Honorarios del médico: '.str_replace("(Honorarios)","", $medico));
        $sheet->mergeCells('A5:J5'); // Combinando celdas
        // Propiedades para centrar elemento
        $centrar = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A5')->applyFromArray($centrar); // Centrando elementos
        $sheet->getStyle('A6:J6')->applyFromArray($centrar); // Centrando elementos
        
		$sheet->setCellValue('A6', '#');
		$sheet->setCellValue('B6', 'Código Hoja');
		$sheet->setCellValue('C6', 'Facturado');
		$sheet->setCellValue('D6', 'Número recibo');
		$sheet->setCellValue('E6', 'Paciente');
		$sheet->setCellValue('F6', 'Monto honorario');
		$sheet->setCellValue('G6', 'Fecha ingreso');
		$sheet->setCellValue('H6', 'Fecha alta');
		$sheet->setCellValue('I6', 'Fecha entregado');
		$sheet->setCellValue('J6', 'Estado');
		
        //FONT SIZE
		$sheet->getStyle('A5:J5')->getFont()->setSize(10);
		$sheet->getStyle('A6:J6')->getFont()->setSize(8);
        
		$number = 1;
		$flag = 7;
        $totalHonorarios = 0;
        $border = [
            'borders' => [
                'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A6:J6')->applyFromArray($border);
		foreach($honorarios as $d){
            if($d->estadoExterno == 0){

                $sheet->setCellValue('A'.$flag, $number);
                $sheet->setCellValue('B'.$flag, $d->codigoHoja);
                if($d->facturar == 0){
                    $sheet->setCellValue('C'.$flag, "NO");
                }else{
                    $sheet->setCellValue('C'.$flag, "Si");
                }
                $sheet->setCellValue('D'.$flag, $d->correlativoSalidaHoja);
                $sheet->setCellValue('E'.$flag, $d->nombrePaciente);
                $sheet->setCellValue('F'.$flag, $d->precioExterno);
                $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $sheet->setCellValue('G'.$flag, $d->fechaHoja);
                $sheet->setCellValue('H'.$flag, $d->salidaHoja);
                $sheet->setCellValue('I'.$flag, date('Y-m-d'));
                if($d->estadoExterno == 0){
                    $sheet->setCellValue('J'.$flag, "Pendiente");
                }else{
                    $sheet->setCellValue('J'.$flag, "Saldado");
                }
                $sheet->getStyle('A'.$flag.':J'.$flag)->applyFromArray($centrar); // Centrando elementos
                $sheet->getStyle('A'.$flag.':J'.$flag)->getFont()->setSize(8);
                $sheet->getStyle('A'.$flag.':J'.$flag)->applyFromArray($border);
                
                $flag = $flag+1;
                $number = $number+1;
                $totalHonorarios += $d->precioExterno;
            }
		}
        $sheet->setCellValue('F'.$flag, number_format($totalHonorarios, 2));
        $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $sheet->getStyle('A'.$flag.':J'.$flag)->applyFromArray($centrar); // Centrando elementos
        $sheet->getStyle('A'.$flag.':J'.$flag)->getFont()->setSize(8);
        $sheet->getStyle('A'.$flag.':J'.$flag++)->applyFromArray($border);
		
		//Font BOLD
		$sheet->getStyle('A5:J5')->getFont()->setBold(true);		
		$sheet->getStyle('A6:J6')->getFont()->setBold(true);
		//$sheet->getStyle('A1:H10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
        
		//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth(3);
		$sheet->getColumnDimension('B')->setWidth(7);
		$sheet->getColumnDimension('C')->setWidth(7);
		$sheet->getColumnDimension('D')->setWidth(10);
		$sheet->getColumnDimension('E')->setWidth(25);
		$sheet->getColumnDimension('F')->setWidth(11);
		$sheet->getColumnDimension('G')->setWidth(10);
		$sheet->getColumnDimension('H')->setWidth(10);
		$sheet->getColumnDimension('I')->setWidth(10);
		$sheet->getColumnDimension('J')->setWidth(10);

        
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_honorarios_'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');


    }

    public function honorarios_facturados(){
        $datos = $this->input->post();
        if(sizeof($datos) > 0){
            if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
				redirect(base_url()."Honorarios/gestion_honorarios");
			}else{
				$facturados = $this->Honorarios_Model->honorariosFacturados($datos);
                $medico = "";
                foreach($facturados as $d){
                    $medico = $d->nombreExterno;
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Honorarios facturados del médico: '.str_replace("(Honorarios)","", $medico));
                $sheet->mergeCells('A1:H1'); // Combinando celdas
                // Propiedades para centrar elemento
                $centrar = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $sheet->getStyle('A1')->applyFromArray($centrar); // Centrando elementos
                $sheet->getStyle('A2:H2')->applyFromArray($centrar); // Centrando elementos
                
                $sheet->setCellValue('A2', '#');
                $sheet->setCellValue('B2', 'Código Hoja');
                $sheet->setCellValue('C2', 'Facturado');
                $sheet->setCellValue('D2', 'Número recibo');
                $sheet->setCellValue('E2', 'Paciente');
                $sheet->setCellValue('F2', 'Monto honorario');
                $sheet->setCellValue('G2', 'Fecha');
                $sheet->setCellValue('H2', 'Estado');

                //FONT SIZE
                $sheet->getStyle('A1:H1')->getFont()->setSize(12);
                $sheet->getStyle('A2:H2')->getFont()->setSize(8);
                    
                $number = 1;
                $flag = 3;
                $totalHonorarios = 0;
                $border = [
                    'borders' => [
                        'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $sheet->getStyle('A2:H2')->applyFromArray($border);
                foreach($facturados as $d){
                    $sheet->setCellValue('A'.$flag, $number);
                    $sheet->setCellValue('B'.$flag, $d->codigoHoja);
                    if($d->facturar == 0){
                        $sheet->setCellValue('C'.$flag, "NO");
                    }else{
                        $sheet->setCellValue('C'.$flag, "SI");
                    }
                    $sheet->setCellValue('D'.$flag, $d->correlativoSalidaHoja);

                    $sheet->setCellValue('E'.$flag, $d->nombrePaciente);
                    $sheet->setCellValue('F'.$flag, $d->precioExterno);
                    $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                    // $sheet->setCellValue('G'.$flag, substr($d->fechaExterno, 0, 10));
                    $sheet->setCellValue('G'.$flag, $d->salidaHoja);
                    if($d->estadoExterno == 0){
                        $sheet->setCellValue('H'.$flag, "Pendiente");
                    }else{
                        $sheet->setCellValue('H'.$flag, "Entregado");
                    }
                    
                    $sheet->getStyle('A'.$flag.':H'.$flag)->applyFromArray($centrar); // Centrando elementos
                    $sheet->getStyle('A'.$flag.':H'.$flag)->getFont()->setSize(8);
                    $sheet->getStyle('A'.$flag.':H'.$flag)->applyFromArray($border);
                    $flag = $flag+1;
                    $number = $number+1;
                    $totalHonorarios += $d->precioExterno;
                }
                $sheet->setCellValue('F'.$flag, number_format($totalHonorarios, 2));
                $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $sheet->getStyle('A'.$flag.':H'.$flag)->applyFromArray($centrar); // Centrando elementos
                $sheet->getStyle('A'.$flag.':H'.$flag)->getFont()->setSize(8);
                $sheet->getStyle('A'.$flag.':H'.$flag++)->applyFromArray($border);
                
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
                $sheet->getStyle('A2:H2')->getFont()->setBold(true);		
                //$sheet->getStyle('A1:H10')->applyFromArray($styleThinBlackBorderOutline);
                //Alignment
                //fONT SIZE
                // $sheet->getStyle('A1:G10')->getFont()->setSize(12);
                //$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                //$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
                $filename = 'listado_honorarios_'.$curdate;
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
			
            }
        }else{
            $this->session->set_flashdata("error","No se seleccionaron datos");
			redirect(base_url()."Honorarios/gestion_honorarios");
        }
    }

    public function honorarios_pdf($data){
        // $datos = $this->input->post();
        $datos = $data;
        $datos = $this->Honorarios_Model->honorariosExterno(trim($datos["idMedico"]));
        $medico = "";
        foreach($datos as $d){
            $medico = $d->nombreExterno;
        }

        // Reporte PDF
			$data = array('honorarios' => $datos, 'medico' => $medico );
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 15,
				'margin_bottom' => 50,
				'margin_header' => 10,
				'margin_footer' => 30
			]);
			//$mpdf->setFooter('{PAGENO}');
			$mpdf->SetHTMLFooter('
					<div class="numeracion" style="text-align: center; width: 300px; margin: 0 auto">
						<div class="numeracion_izquierda">_____________________________________________</div>
						<div class="numeracion_derecha"><strong>'.str_replace("(Honorarios)","", $medico).'</strong></div>
					</div>
				');

			$mpdf->SetProtection(array('print'));
			$mpdf->SetTitle("Hospital Orellana, Usulutan");
			$mpdf->SetAuthor("Edwin Orantes");
			// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan"); //Para agragar marca de agua
			$mpdf->showWatermarkText = true;
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->watermarkTextAlpha = 0.1;
			$mpdf->SetDisplayMode('fullpage');
			//$mpdf->AddPage('L'); //Voltear Hoja
			$html = $this->load->view("Honorarios/honorarios_pdf",$data,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html);
			$mpdf->Output('detalle_compra.pdf', 'I');
			//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
		// Fin reporte PDF
        
    }

    public function saldar_all_honorario(){
        
        if($this->input->is_ajax_request()){
            $datos = $this->input->post();
            $medico = $datos["medico"];
            $hoy = date('Y-m-d');
            $resp = $this->Honorarios_Model->saldarTodos(trim($medico), trim($hoy));
            if($resp){
                echo "Okay...";
            }else{
                echo "Error...";
            }
        }
        else{
            echo "Error...";
        }

    }

    /* Nuevas funciones */
        public function honorarios_entregados(){
            $data["externos"] = $this->Externos_Model->obtenerExternos();
            $this->load->view("Base/header");
            $this->load->view("Honorarios/honorarios_entregados", $data);
            $this->load->view("Base/footer");
        }

        public function entregados_x_medico(){
            if($this->input->post()){
                $datos = $this->input->post();
                $pivote = $datos["pivoteHonorario"];
                unset($datos["pivoteHonorario"]);
                $data["honorarios"] = $this->Honorarios_Model->honorariosEntregados($datos);
                if($pivote == 1){
                    $this->honorarios_x_medico_pdf($data);
                }else{
                    $this->honorarios_x_medico_excel($data);
                }

            }else{
                $this->session->set_flashdata("error","No hay datos que mostrar");
				redirect(base_url()."Honorarios/honorarios_entregados");
            }
        }

        public function honorarios_x_medico_pdf($datos = null){
            if($datos != null){
                $data["honorarios"] = $datos["honorarios"];
                $medico = "";
                foreach($datos["honorarios"] as $d){
                    $medico = $d->nombreExterno;
                }
                $data["medico"] = $medico;
                // Reporte PDF
                    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
                    $mpdf = new \Mpdf\Mpdf([
                        'margin_left' => 15,
                        'margin_right' => 15,
                        'margin_top' => 15,
                        'margin_bottom' => 50,
                        'margin_header' => 10,
                        'margin_footer' => 30
                    ]);
                    //$mpdf->setFooter('{PAGENO}');
                    $mpdf->SetHTMLFooter('
                        <div class="numeracion" style="text-align: center; width: 300px; margin: 0 auto">
                            <div class="numeracion_izquierda">_____________________________________________</div>
                            <div class="numeracion_derecha"><strong>'.str_replace("(Honorarios)","", $medico).'</strong></div>
                        </div>
                    ');
        
                    $mpdf->SetProtection(array('print'));
                    $mpdf->SetTitle("Hospital Orellana, Usulutan");
                    $mpdf->SetAuthor("Edwin Orantes");
                    // $mpdf->SetWatermarkText("Hospital Orellana, Usulutan"); //Para agragar marca de agua
                    $mpdf->showWatermarkText = true;
                    $mpdf->watermark_font = 'DejaVuSansCondensed';
                    $mpdf->watermarkTextAlpha = 0.1;
                    $mpdf->SetDisplayMode('fullpage');
                    //$mpdf->AddPage('L'); //Voltear Hoja
                    $html = $this->load->view("Honorarios/honorarios_x_medico_pdf",$data,true); // Cargando hoja de estilos
                    $mpdf->WriteHTML($html);
                    $mpdf->Output('detalle_compra.pdf', 'I');
                    //$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
                // Fin reporte PDF
    
                // echo json_encode($data);

            }else{
                $this->session->set_flashdata("error","No hay datos que mostrar");
				redirect(base_url()."Honorarios/honorarios_entregados");
            }
            
        }

        public function honorarios_x_medico_excel($datos = null){
            $data["honorarios"] = $datos["honorarios"];
            $medico = "";
            foreach($datos["honorarios"] as $d){
                $medico = $d->nombreExterno;
            }
            $data["medico"] = $medico;
    
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Cargando imágen en Excel.
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('HCO');
                $drawing->setDescription('HCO');
                $drawing->setPath('./public/img/logo2.png'); /* put your path and image here */
                $drawing->setCoordinates('A1');
                $drawing->setWidthAndHeight(210, 215);
                $drawing->setOffsetX(110);
                $drawing->getShadow()->setVisible(false);
                $drawing->getShadow()->setDirection(45);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
            // Fin cargar imagen
    
            $sheet->setCellValue('E2', 'HOSPITAL ORELLANA, USULUTAN');
            $sheet->mergeCells('E2:J2');
            $sheet->setCellValue('E3', 'Sexta calle oriente #8, Usulutan, El Salvador');
            $sheet->mergeCells('E3:J3');
    
            $derecha = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ];
            $sheet->getStyle('E2:J2')->applyFromArray($derecha);
            $sheet->getStyle('E3:J3')->applyFromArray($derecha);
    
            $sheet->getStyle('E2:J2')->getFont()->setBold(true);	
            $sheet->getStyle('E3:J3')->getFont()->setBold(true);
            
            $sheet->getStyle('E2:J2')->getFont()->setSize(10);
            $sheet->getStyle('E3:J3')->getFont()->setSize(10);
    
    
            $sheet->setCellValue('A5', 'Honorarios del médico: '.str_replace("(Honorarios)","", $medico));
            $sheet->mergeCells('A5:J5'); // Combinando celdas
            // Propiedades para centrar elemento
            $centrar = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $sheet->getStyle('A5')->applyFromArray($centrar); // Centrando elementos
            $sheet->getStyle('A6:J6')->applyFromArray($centrar); // Centrando elementos
            
            $sheet->setCellValue('A6', '#');
            $sheet->setCellValue('B6', 'Código Hoja');
            $sheet->setCellValue('C6', 'Facturado');
            $sheet->setCellValue('D6', 'Número recibo');
            $sheet->setCellValue('E6', 'Paciente');
            $sheet->setCellValue('F6', 'Monto honorario');
            $sheet->setCellValue('G6', 'Fecha ingreso');
            $sheet->setCellValue('H6', 'Fecha alta');
            $sheet->setCellValue('I6', 'Fecha entregado');
            $sheet->setCellValue('J6', 'Estado');
            
            //FONT SIZE
            $sheet->getStyle('A5:J5')->getFont()->setSize(10);
            $sheet->getStyle('A6:J6')->getFont()->setSize(8);
            
            $number = 1;
            $flag = 7;
            $totalHonorarios = 0;
            $border = [
                'borders' => [
                    'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A6:J6')->applyFromArray($border);
            foreach($datos["honorarios"] as $d){
                $sheet->setCellValue('A'.$flag, $number);
                $sheet->setCellValue('B'.$flag, $d->codigoHoja);
                if($d->facturar == 0){
                    $sheet->setCellValue('C'.$flag, "NO");
                }else{
                    $sheet->setCellValue('C'.$flag, "Si");
                }
                $sheet->setCellValue('D'.$flag, $d->correlativoSalidaHoja);
                $sheet->setCellValue('E'.$flag, $d->nombrePaciente);
                $sheet->setCellValue('F'.$flag, $d->precioExterno);
                $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $sheet->setCellValue('G'.$flag, $d->fechaHoja);
                $sheet->setCellValue('H'.$flag, $d->salidaHoja);
                $sheet->setCellValue('I'.$flag, date('Y-m-d'));
                if($d->estadoExterno == 0){
                    $sheet->setCellValue('J'.$flag, "Pendiente");
                }else{
                    $sheet->setCellValue('J'.$flag, "Saldado");
                }
                $sheet->getStyle('A'.$flag.':J'.$flag)->applyFromArray($centrar); // Centrando elementos
                $sheet->getStyle('A'.$flag.':J'.$flag)->getFont()->setSize(8);
                $sheet->getStyle('A'.$flag.':J'.$flag)->applyFromArray($border);
                
                $flag = $flag+1;
                $number = $number+1;
                $totalHonorarios += $d->precioExterno;
            }
            $sheet->setCellValue('F'.$flag, number_format($totalHonorarios, 2));
            $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
            $sheet->getStyle('A'.$flag.':J'.$flag)->applyFromArray($centrar); // Centrando elementos
            $sheet->getStyle('A'.$flag.':J'.$flag)->getFont()->setSize(8);
            $sheet->getStyle('A'.$flag.':J'.$flag++)->applyFromArray($border);
            
            //Font BOLD
            $sheet->getStyle('A5:J5')->getFont()->setBold(true);		
            $sheet->getStyle('A6:J6')->getFont()->setBold(true);
            //$sheet->getStyle('A1:H10')->applyFromArray($styleThinBlackBorderOutline);
            //Alignment
            
            //$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
            //$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            //Custom width for Individual Columns
            $sheet->getColumnDimension('A')->setWidth(3);
            $sheet->getColumnDimension('B')->setWidth(7);
            $sheet->getColumnDimension('C')->setWidth(7);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(25);
            $sheet->getColumnDimension('F')->setWidth(11);
            $sheet->getColumnDimension('G')->setWidth(10);
            $sheet->getColumnDimension('H')->setWidth(10);
            $sheet->getColumnDimension('I')->setWidth(10);
            $sheet->getColumnDimension('J')->setWidth(10);
    
            
            $curdate = date('d-m-Y H:i:s');
            $writer = new Xlsx($spreadsheet);
            $filename = 'listado_honorarios_'.$curdate;
            ob_end_clean();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
    
    
        }

        public function honorarios_pendientes(){
            $this->load->view("Base/header");
            $this->load->view("Honorarios/honorarios_pendientes");
            $this->load->view("Base/footer");
        }

        public function pivote_pendientes(){
            if($this->input->post()){
                $datos = $this->input->post();
                if($datos["pivoteHonorario"] == 1){
                    if($datos["tipoReporte"] == 1){
                        // Pendientes
                        $honorarios = $this->Honorarios_Model->honorariosPendientes(0);
                    }else{
                        $honorarios = $this->Honorarios_Model->honorariosPendientes(1);
                        // Entregados
                    }
                    $this->pendientes_pdf($honorarios);
                }else{
                    if($datos["tipoReporte"] == 1){
                        // Pendientes
                        $honorarios = $this->Honorarios_Model->honorariosPendientes(0);
                    }else{
                        $honorarios = $this->Honorarios_Model->honorariosPendientes(1);
                        // Entregados
                    }
                    $this->pendientes_excel($honorarios);
                }
            }else{
                $this->session->set_flashdata("error","Se debe volver a generar la vista.");
				redirect(base_url()."Honorarios/honorarios_pendientes");
            }
            
            /* echo json_encode($datos); */
        }

        public function pendientes_pdf($datos = null){
            $data["honorarios"] = $datos;
             // Reporte PDF
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 15,
                    'margin_bottom' => 50,
                    'margin_header' => 10,
                    'margin_footer' => 30
                ]);
                //$mpdf->setFooter('{PAGENO}');
                $mpdf->SetHTMLFooter('');

                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Hospital Orellana, Usulutan");
                $mpdf->SetAuthor("Edwin Orantes");
                // $mpdf->SetWatermarkText("Hospital Orellana, Usulutan"); //Para agragar marca de agua
                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->SetDisplayMode('fullpage');
                //$mpdf->AddPage('L'); //Voltear Hoja
                $html = $this->load->view("Honorarios/honorarios_pendientes_pdf",$data,true); // Cargando hoja de estilos
                $mpdf->WriteHTML($html);
                $mpdf->Output('detalle_compra.pdf', 'I');
            // Fin reporte PDF 

            // echo json_encode($data);
        }

        public function pendientes_excel($datos = null){
            
            $honorarios = $datos;
    
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Lista de honorarios');
            $sheet->mergeCells('A1:C1'); // Combinando celdas
            $sheet->setCellValue('A2', '#');
            $sheet->setCellValue('B2', 'Nombre');
            $sheet->setCellValue('C2', 'Monto honorario');
    
            // Propiedades para centrar elemento
            $centrar = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            $sheet->getStyle('A1')->applyFromArray($centrar); // Centrando elementos
            $sheet->getStyle('A2:C2')->applyFromArray($centrar); // Centrando elementos
            
            //FONT SIZE
            $sheet->getStyle('A2:C2')->getFont()->setSize(10);
            
            $number = 1;
            $flag = 3;
            $totalHonorarios = 0;
            $border = [
                'borders' => [
                    'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A1:C1')->applyFromArray($border);
            $sheet->getStyle('A2:C2')->applyFromArray($border);
            foreach($honorarios as $d){
                $sheet->setCellValue('A'.$flag, $number);
                $sheet->setCellValue('B'.$flag, str_replace("(Honorarios)","", $d->nombreExterno));
                $sheet->setCellValue('C'.$flag, $d->total_honorarios);
                $sheet->getStyle('C'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                

                $sheet->getStyle('A'.$flag.':C'.$flag)->applyFromArray($centrar); // Centrando elementos
                $sheet->getStyle('A'.$flag.':C'.$flag)->getFont()->setSize(8);
                $sheet->getStyle('A'.$flag.':C'.$flag)->applyFromArray($border);


                $flag = $flag+1;
                $totalHonorarios += $d->total_honorarios;
            }
            
            //Custom width for Individual Columns
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(40);
            $sheet->getColumnDimension('C')->setWidth(15);
    
            
            $curdate = date('d-m-Y H:i:s');
            $writer = new Xlsx($spreadsheet);
            $filename = 'listado_honorarios_'.$curdate;
            ob_end_clean();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
    
    
        
        }
    /* Nuevas funciones */

    public function honorarios_paquetes(){
        $data["honorarios"] = $this->Honorarios_Model->honorariosPaquetes();
        $data["medicos"] = $this->Medico_Model->obtenerMedicos(); // Lista de los medicos usados para honorarios de paquetes
        $this->load->view("Base/header");
        $this->load->view("Honorarios/paquetes/honorarios_paquetes", $data);
        $this->load->view("Base/footer");

        // echo json_encode($data);
    }

    public function dividir_honorario($id = null){
        $data["pivote"] = $id;
        $data["honorario"] = $this->Honorarios_Model->honorarioPaquete($id);
        $data["divisiones"] = $this->Honorarios_Model->divisionesHonorario($id);
        $data["medicos"] = $this->Medico_Model->obtenerMedicos();
        $this->load->view("Base/header");
        $this->load->view("Honorarios/paquetes/dividir_honorario_paquete", $data);
        $this->load->view("Base/footer");

        // echo json_encode($data["divisiones"]);
    }

    /* public function guardar_division(){
        if($this->input->is_ajax_request()){
            $datos = $this->input->post();
            $datos["original"] = $datos["cantidadHonorario"];
            $datos["por"] = $this->session->userdata('id_usuario_h');
            // Ejecutando consultas
                $resp = $this->Honorarios_Model->guardarDivisionHonorario($datos);
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
    } */

    public function guardar_division(){
        $datos = $this->input->post();
        $datos["original"] = $datos["cantidadHonorario"];
        $datos["por"] = $this->session->userdata('id_usuario_h');
        $resp = $this->Honorarios_Model->guardarDivisionHonorario($datos);
        if($resp){
            $this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
            redirect(base_url()."Honorarios/dividir_honorario/".$datos["idHonorario"]."/");
        }else{
            $this->session->set_flashdata("error","Error al guardar los datos!");
            redirect(base_url()."Honorarios/dividir_honorario/".$datos["idHonorario"]."/");
        } 
        // echo json_encode($datos);
    }

    public function gestion_honorarios_paquetes(){
        $data["medicos"] = $this->Medico_Model->obtenerMedicos();
        $this->load->view("Base/header");
        $this->load->view("Honorarios/paquetes/gestion_honorarios_paquetes", $data);
        $this->load->view("Base/footer");
        // echo json_encode($data["medicos"]);
    }

    public function buscar_honorarios_paquetes(){
        if($this->input->is_ajax_request()){
            $medico =$this->input->post("medico");
            $data = $this->Honorarios_Model->honorarioPorMedico(trim($medico));
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function saldar_honorario_paquete(){
        if($this->input->is_ajax_request()){
            $idHonorario =$this->input->post("idHonorario");
            $hoy = date('Y-m-d');
            $resp = $this->Honorarios_Model->saldarHonorarioPaquete(trim($idHonorario), trim($hoy));
            if($resp){
                echo "1";
            }else{
                echo "0";
            }
        }
        else{
            echo "Error...";
        }
    }

    public function adeudar_honorario_paquete(){
        if($this->input->is_ajax_request()){
            $idHonorario =$this->input->post("idHonorario");
            $resp = $this->Honorarios_Model->adeudarHonorarioPaquete(trim($idHonorario));
            if($resp){
                echo "1";
            }else{
                echo "0";
            }
        }
        else{
            echo "Error...";
        }
    }

    public function honorarios_paquete_pivote(){
        if($this->input->post()){
            $datos = $this->input->post();
            $pivote = $datos["pivoteHonorario"];
            if($pivote == 1 ){
                $this->honorarios_paquete_pdf($datos);
            }else{
                $this->honorarios_paquete_excel($datos);
            }
        }else{
            echo "<script>window.close();</script>";
        }
    }

    public function honorarios_paquete_pdf($data){
        // $datos = $this->input->post();
        $datos = $this->Honorarios_Model->honorarioPorMedico(trim($data["idMedico"]));
        $medico = "";
        foreach($datos as $d){
            $medico = $d->nombreMedico;
        }
        // Reporte PDF
			$data = array('honorarios' => $datos, 'medico' => $medico );
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 15,
				'margin_bottom' => 50,
				'margin_header' => 10,
				'margin_footer' => 30
			]);
			//$mpdf->setFooter('{PAGENO}');
			$mpdf->SetHTMLFooter('
					<div class="numeracion" style="text-align: center; width: 300px; margin: 0 auto">
						<div class="numeracion_izquierda">_____________________________________________</div>
						<div class="numeracion_derecha"><strong>'.str_replace("(Honorarios)","", $medico).'</strong></div>
					</div>
				');

			$mpdf->SetProtection(array('print'));
			$mpdf->SetTitle("Hospital Orellana, Usulutan");
			$mpdf->SetAuthor("Edwin Orantes");
			// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan"); //Para agragar marca de agua
			$mpdf->showWatermarkText = true;
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->watermarkTextAlpha = 0.1;
			$mpdf->SetDisplayMode('fullpage');
			//$mpdf->AddPage('L'); //Voltear Hoja
			$html = $this->load->view("Honorarios/paquetes/honorarios_paquete_pdf",$data,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html);
			$mpdf->Output('honorarios_paquetes.pdf', 'I');
		// Fin reporte PDF
        

        // echo json_encode($datos);
        
    }

    public function honorarios_paquete_excel($data){
        // $datos = $this->input->post();
        $datos = $this->Honorarios_Model->honorarioPorMedico(trim($data["idMedico"]));

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'Código Hoja');
		$sheet->setCellValue('C1', 'Paciente');
		$sheet->setCellValue('D1', 'Monto honorario');
		$sheet->setCellValue('E1', 'Detalle');
		$number = 1;
		$flag = 2;
		$total = 0;
        $dejo = "";
		foreach($datos as $d){
            if($d->quienDeja != 0){
                $dejo = "Le deja  ".$d->quienDeja ;
            }else{
                $dejo = '';
            }
            $total += $d->totalHonorarioPaquete;
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->codigoHoja);
			$sheet->setCellValue('C'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('D'.$flag, $d->totalHonorarioPaquete);
			$sheet->setCellValue('E'.$flag, $dejo);
            $sheet->getStyle('D'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
			
			$flag = $flag+1;
			$number = $number+1;
		}
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
		$filename = 'honorarios_de_paquetes'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');


    }

    public function saldar_all_honorarios_paquetes(){
        if($this->input->is_ajax_request()){
            $datos = $this->input->post();
            $medico = $datos["medico"];
            $hoy = date('Y-m-d');
            $resp = $this->Honorarios_Model->saldarHonorariosPaquetes(trim($medico), trim($hoy));
            if($resp){
                echo "Okay...";
            }else{
                echo "Error...";
            }
        }
        else{
            echo "Error...";
        }

    }

    public function honorarios_en_banco(){
        $data["honorarios"] = $this->Honorarios_Model->honorariosEnBanco();
        $data["listaHonorarios"] = $this->Honorarios_Model->listaHonorariosEnBanco();
        $this->load->view("Base/header");
        $this->load->view("Honorarios/en_banco", $data);
        $this->load->view("Base/footer");
        // echo json_encode($data);
    }

    public function detalle_honorarios_banco($id = null){
        $data["honorarios"] = $this->Honorarios_Model->detalleHonorariosEnBanco($id);
        $data["pivoteExterno"] = $id;
        $this->load->view("Base/header");
        $this->load->view("Honorarios/detalle_en_banco", $data);
        $this->load->view("Base/footer");

        // echo json_encode($data   );
    }

    public function saldar_honorarios_banco(){
        $datos = $this->input->post();
        $honorarios = unserialize(base64_decode(urldecode($datos["honorariosPago"])));
        
        // Ultimo codigo de hoja
            $codigo = $this->Gastos_Model->codigoGasto(); 
            if($codigo == NULL ){
                $codigo = 1000;
            }else{
                $codigo = $codigo->codigo +1;
            }
        // Ultimo codigo de hoja
        $montoTotal = 0;
        $idProveedor= 0;
        $empresaProveedor = "";
        $seleccionarH["pivote"] = $datos["returnPago"];
        $listaHonorarios = array();
        $pivote = $datos["returnPago"];
        echo json_encode($seleccionarH);
        foreach ($honorarios as $row) {
            $seleccionarH["honorario"] = $row["honorario"];
            $cuentas = $this->Honorarios_Model->honorariosEnBancoSaldar($seleccionarH);
            $montoTotal += $cuentas->precioExterno;
            $listaHonorarios[]["honorario"] = $row["honorario"]; // Se usara cuando se genere un solo cheque por todos los honorarios
        }
        $gasto["tipoGasto"] = '1';
        $gasto["montoGasto"] = $montoTotal;
        if($pivote == 0){
            $gasto["entregadoGasto"] = "Hospital Orellana";
        }else{
            $gasto["entregadoGasto"] = str_replace("(Honorarios)", "", $cuentas->nombreExterno);
        }
        $gasto["idCuentaGasto"] = '6';
        $gasto["fechaGasto"] = date('Y-m-d');
        if($pivote == 0){
            $gasto["entidadGasto"] = '2';
            $gasto["idProveedorGasto"] = "1";
        }else{
            $gasto["entidadGasto"] = '1';
            $gasto["idProveedorGasto"] = $cuentas->idExterno;
        }
        $gasto["pagoGasto"] = '2';
        $gasto["numeroGasto"] = $datos["numeroCheque"];
        $gasto["bancoGasto"] = $datos["bancoPago"];
        $gasto["cuentaGasto"] = $datos["cuentaPago"];
        $gasto["descripcionGasto"] = 'Pago de honorarios pendientes con cheque #'.$datos["numeroCheque"].", ".$datos["bancoPago"];
        $gasto["codigoGasto"] = $codigo;
        $gasto["flagGasto"] = '2';
        $gasto["efectuoGasto"] = $this->session->userdata("empleado_h");

        $data["gasto"] = $gasto;  // Para el recibo de gastos
        $data["honorarios"] = $listaHonorarios;  // Para el recibo de gastos
        /* if($pivote == 0){
        }else{
            $data["honorarios"][] = $row["honorario"];  // Para el recibo de gastos
        } */
        
        // $data["cuentas"] = $datos["cuenta"]; // Cuenta a saldar
    
		$resp = $this->Honorarios_Model->saldarHonorariosBanco($data);
        redirect(base_url()."Honorarios/mostrar_pagos/".$resp."/");
        
       
    }

    public function mostrar_pagos($resp = null){
            $datos = $this->Gastos_Model->obtenerGasto($resp);
            $proveedor = $this->Gastos_Model->obtenerEntidadGasto($datos->idProveedorGasto, $datos->entidadGasto);
            
            $recibo["honorarios"] = $this->Honorarios_Model->detalleHonorarioGasto($resp);
            
    
            $recibo["fecha"] = $datos->fechaGasto;
            $recibo["codigo"] = $datos->codigoGasto;
            $recibo["entregado"] = $datos->entregadoGasto;
            $recibo["proveedor"] = $proveedor->proveedor;
            $recibo["forma"] = $datos->pagoGasto;
            $recibo["cheque"] = $datos->numeroGasto;
            $recibo["concepto"] = $datos->descripcionGasto;
            $recibo["total"] = $datos->montoGasto;
            $recibo["efectuoGasto"] = $datos->efectuoGasto;
            $recibo["bancoGasto"] = $datos->bancoGasto;
            $recibo["cuentaGasto"] = $datos->cuentaGasto;
            $recibo["flag"] = 2;

            
            // Recibo de gastos
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 35,
                    'margin_bottom' => 15,
                    'margin_header' => 10,
                    'margin_footer' => 25
                    ]);
                
                //$mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Hospital Orellana, Usulutan");
                $mpdf->SetAuthor("Edwin Orantes");
                $mpdf->showWatermarkText = false;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->SetDisplayMode('fullpage');
                //$mpdf->AddPage('L'); //Voltear Hoja
                $html = $this->load->view('Honorarios/recibo_gasto', $recibo ,true); // Cargando hoja de estilos
                $mpdf->SetHTMLHeader('
                    <div class="cabecera">
                        <div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
                        <div class="title_cabecera">
                            <h4>HOSPITAL ORELLANA, USULUTAN</h4>
                            <h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5>
                        </div>
                    </div>
                ');
    
                $mpdf->WriteHTML($html);
                if($flag == 1){
                    $mpdf->setHTMLFooter('
                        <div class="detalle">
                            <table class="tabla_num_recibo">
                                <tr>
                                    <td style="text-align: center;">
                                        <p>F._______________________________________</p>
                                        <h5><strong>RECIBI CONFORME</strong></h5>
                                    </td>
                                    <td style="text-align: center;">
                                        <p style="text-decoration: underline;">
                                            F.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;'.$this->session->userdata("empleado_h").'&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        <h5><strong>ELABORADO POR</strong></h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    ');
                }
                $mpdf->Output('recibo_gasto.pdf', 'I');
            // Fin


            // echo json_encode($recibo);
        
    }

    public function reporte_honorario_paquetes(){
        $datos = $this->input->post();
        $data = $this->Honorarios_Model->reporteHonorariosPaquetes($datos);

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'Paciente');
		$sheet->setCellValue('C1', 'DUI');
		$sheet->setCellValue('D1', 'Dirección');
		$sheet->setCellValue('E1', 'Procedimiento');
		$sheet->setCellValue('F1', 'Total');
		$sheet->setCellValue('G1', 'Entregado');
		$sheet->setCellValue('H1', 'Facturado');
		$sheet->setCellValue('I1', 'Fecha');
		$number = 1;
		$flag = 2;
		$total = 0;
		foreach($data as $d){
            $total += $d->totalHonorarioPaquete;
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('C'.$flag, $d->duiPaciente);
			$sheet->setCellValue('D'.$flag, $d->direccionPaciente);
			$sheet->setCellValue('E'.$flag, $d->procedimientoHoja);
			$sheet->setCellValue('F'.$flag, $d->totalHonorarioPaquete);
            if($d->estadoHonorarioPaquete == 1){
                $sheet->setCellValue('G'.$flag, "Si");
            }else{
                $sheet->setCellValue('G'.$flag, "No");
            }
            if($d->credito_fiscal > 0){
                $sheet->setCellValue('H'.$flag, "Si");
            }else{
                $sheet->setCellValue('H'.$flag, "No");
            }
			$sheet->setCellValue('I'.$flag, $d->salidaHoja);
			
            $sheet->getStyle('F'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
			$flag = $flag+1;
			$number = $number+1;
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
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'honorarios_de_paquetes'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

        // echo json_encode($data);
    }
}