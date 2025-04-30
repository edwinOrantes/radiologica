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


class Test extends CI_Controller {
    
    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Usuarios_Model");
        $this->load->model("Hoja_Model");
        $this->load->model("Gastos_Model");
        $this->load->model("Reportes_Model");
		$this->load->model("Botiquin_Model");
		$this->load->model("Externos_Model");
        $this->load->model("Test_Model");
		/* if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		} */
	}
    
    public function index(){
        $this->load->view("Base/header");
        $this->load->view("Test/test");
        $this->load->view("Base/footer");
    }

    public function generar_externos(){
        $datos = $this->input->post();

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Recibo');
		$sheet->setCellValue('B1', 'Fecha Entrada');
		$sheet->setCellValue('C1', 'Fecha Salida');
		$sheet->setCellValue('D1', 'Paciente');
		$sheet->setCellValue('E1', 'Doctor');
		$sheet->setCellValue('F1', 'Monto');
			
		$datos = $this->Test_Model->obtenerExternos($datos["reciboInicio"], $datos["reciboFin"]);
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->hc_id_pk);
			$sheet->setCellValue('B'.$flag, substr($d->hc_ingreso_dt, 0, 10));
			$sheet->setCellValue('C'.$flag, substr($d->hc_egreso_dt, 0, 10));
			$sheet->setCellValue('D'.$flag, (trim($d->pa_nombre_vc)." ".trim($d->pa_apellido_vc)));
            $sheet->setCellValue('E'.$flag, trim($d->ce_insumo_vc));
			$sheet->setCellValue('F'.$flag, $d->ce_precio_de);
				
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
		$sheet->getStyle('A1:F'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:F'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:F'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'reporte_externos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    }

	public function testMedicos(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Titulo');
		$sheet->setCellValue('B1', 'Nombre medico');
		$sheet->setCellValue('B1', 'Especialdiad');
		$sheet->setCellValue('D1', 'Telefono');
		$sheet->setCellValue('E1', 'Direccion');
			
		$datos = $this->Test_Model->obtenerMedicos();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, trim($d->me_trato_vc));
			$sheet->setCellValue('B'.$flag, trim($d->me_nombre_vc));
			$sheet->setCellValue('C'.$flag, trim($d->me_especialidad_vc));
			$sheet->setCellValue('D'.$flag, trim($d->me_tel_cli_vc));
			$sheet->setCellValue('E'.$flag, trim($d->me_direccion_vc));
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
		$sheet->getStyle('A1:E1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:E'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:E'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:E'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'reporte_externos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    	
	}

	public function testLaboratorio(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Examen');
		$sheet->setCellValue('B1', 'Precio');
		$datos = $this->Test_Model->examenesLaboratorio();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, trim($d->nombreMedicamento));
			$sheet->setCellValue('B'.$flag, trim($d->precioVMedicamento));
				
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
		$sheet->getStyle('A1:B1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:B'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:B'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:B'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'examenes_laboratorio'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
    	
	}

	public function fechas(){
		$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
		$precio = 25;
		$dia = date("w");
		
		//echo $dias[date("w")];
		$hora = date('H:i:s');  
		$ampm = date('a');
		$normal = false;  
		echo "La hora es ".$hora." ".$ampm."<br>";
		echo "<br>";

		if($dia >= 1 && $dia <= 5){
			
			switch ($hora) {
				case $hora > "07:00:00" && $ampm == "am":
					$normal = 1;
					break;
				case $hora < "16:00:00" && $ampm == "pm":
					$normal = 1;
					break;
				case $hora < "07:00:00" && $ampm == "am":
					if($dia == 1){						
						$normal = 2;
					}else{
						$normal = 0;
					}
					break;
				case $hora > "16:00:00" && $ampm == "pm":
					$normal = 0;
					break;
				default:
					echo "Son las ".$hora;
					break;
			}

			
		}else{
			if($dia == 6){
				switch ($hora) {
					case $hora < "07:00:00" && $ampm == "am":
						$normal = 0;
						break;
					case $hora > "07:00:00" && $ampm == "am":
						$normal = 1;
						break;
					case $hora > "12:00:00" && $ampm == "pm":
						$normal = 2;
						break;
					default:
						echo "Son las ".$hora;
						break;
				}
			}
			if($dia == 0){
				echo $normal = 2;
			}
			if($dia == 1){
				if($hora < "07:00:00" && $ampm == "am"){
					$normal = 2;
				}
			}
		}
		switch ($normal) {
			case '0':
				echo "Estamos fuera de horario";
				break;
			case '1':
				echo "Estamos en horario normal";
				break;
			case '2':
				echo "Estamos en horario plus";
				break;
			
			default:
				echo "Horario indefinido";
				break;
		}

	}

	public function fechas2(){
		# Agregar o quitar dias a una fecha determinada
			$date = date_create(date('Y-m-d'));
			date_add($date, date_interval_create_from_date_string("10 days"));
			$fecha_nueva = date_format($date, "Y-m-d");
			echo $fecha_nueva;
			echo "<br><br><br>";
		# Diferencia entre fech
			$date1=date_create("2021-02-15");
			$date2=date_create("2021-12-12");
			$diff=date_diff($date1,$date2);
			echo $diff->format("%a dias");
	}

	public function user(){
		echo "Nombre de usuario: ".$this->session->userdata('usuario_h')."<br>";
		echo "Id Usuario: ".$this->session->userdata('id_usuario_h')."<br>";
		echo "Id Empleado: ".$this->session->userdata('id_empleado_h')."<br>";
		echo "Id Acceso: ".$this->session->userdata('acceso_h')."<br>";
		echo "Es valid: ".$this->session->userdata('valido')."<br>";
		echo "El usuario ".$this->session->userdata('id_usuario_h')." afectuo un proceso<br>";
	}

	public function gastos(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nombre');
		$sheet->setCellValue('B1', 'Clasificacion');
		$sheet->setCellValue('C1', 'Descripcion');
		$datos = $this->Test_Model->cuentasGastos();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, trim($d->cu_nombre_vc));
			$sheet->setCellValue('B'.$flag, trim($d->cu_clasificacion_vc));
			$sheet->setCellValue('C'.$flag, trim($d->cu_descripcion_te));
				
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
		$sheet->getStyle('A1:C1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:C'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:C'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:C'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'examenes_laboratorio'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
    	
	
	}

	public function imprimir(){
		$this->load->view("Test/imprimir");
	}

	public function medicamentos_hospital(){
        $datos = $this->input->post();

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'IDBM');
		$sheet->setCellValue('B1', 'CODIGO');
		$sheet->setCellValue('C1', 'NOMBRE');
		$sheet->setCellValue('D1', 'PRECIO');
		$sheet->setCellValue('E1', 'STOCK');
			
		$datos = $this->Test_Model->medicamentosHospital();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->idBM);
			$sheet->setCellValue('B'.$flag, $d->codigoMedicamento);
			$sheet->setCellValue('C'.$flag, $d->nombreMedicamento);
			$sheet->setCellValue('D'.$flag, $d->precioVMedicamento);
			$sheet->setCellValue('E'.$flag, $d->stockMedicamento);
				
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
		$sheet->getStyle('A1:F'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:F'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:F'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'medicamento_hospital'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    }

	public function tabla(){
		$this->load->view("Base/header");
		$this->load->view("Test/tabla");
		$this->load->view("Base/footer");
	}

	public function dashboard_laboratorio(){
		$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		$anio = date("Y");
		$mes = date("m");
		$i = $anio."-".$mes."-01";
		$f = $anio."-".$mes."-31";

		// Para total mensual 
		// $detalleExamenes = $this->Test_Model->examenesConsulta($fecha->fechaConsulta, 1);  // De donde sacar la fecha we?

		// Para graficas
			$hoy = date("Y-m-d");
			$fechas = $this->Test_Model->fechasGraficas($hoy);
			$s = 0;
			if(sizeof($fechas) > 0){
				foreach ($fechas as $fecha) {
					$totalMes = 0;
					$data['fechas'][] = substr($fecha->fechaConsulta, 0, 10);
					
					$examenes = $this->Test_Model->examenesConsulta($fecha->fechaConsulta, 1);
	
					foreach ($examenes as $examen) {
						$totalMes += 1 * $examen->precioVMedicamento;
					}
					$data['totalMes'][] = round($totalMes, 2);
					/* echo $totalMes."<br>";
					echo "<br>-------------------------------------------------<br>"; */
					
				}
				
				$data['fecha_data'] = json_encode($data['fechas']);
				$data['mes_data'] = json_encode($data['totalMes']);
			}else{
				$data["vacio"] = true;
			}

        /* $this->load->view("Base/header");
        $this->load->view("Test/dashboard_laboratorio", $data);
        $this->load->view("Base/footer"); */
    }

	public function ultras_excel(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Paciente');
		$sheet->setCellValue('B1', 'Examen');
		$sheet->setCellValue('C1', 'Fecha');
			
		$datos = $this->Test_Model->ultrasExcel();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('B'.$flag, $d->nombreMedicamento);
			$sheet->setCellValue('C'.$flag, $d->fechaInsumo);
				
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
		$sheet->getStyle('A1:C1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:C'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:C'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:C'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'reporte_ultras'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

	public function test(){
			$datos = $this->Test_Model->nuevosMedicamentos();
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Id');
			$sheet->setCellValue('B1', 'IdBM');
			$sheet->setCellValue('C1', 'Nombre');
				
			$number = 1;
			$flag = 2;
			foreach($datos as $d){
				$sheet->setCellValue('A'.$flag, $d->idMedicamento);
				$sheet->setCellValue('B'.$flag, $d->idBM);
				$sheet->setCellValue('C'.$flag, $d->nombreMedicamento);
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
			$sheet->getStyle('A1:C1')->getFont()->setBold(true);		
			//$sheet->getStyle('A1:D10')->applyFromArray($styleThinBlackBorderOutline);
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:C10')->getFont()->setSize(12);
			//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setAutoSize(true);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getColumnDimension('C')->setAutoSize(true);
			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'nuevo'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
	}

	public function test_med(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'CODIGO');
		$sheet->setCellValue('B1', 'NOMBRE');
		$sheet->setCellValue('C1', 'PRECIO');
		$sheet->setCellValue('D1', 'TIPO');
		$sheet->setCellValue('E1', 'STOCK');
		$sheet->setCellValue('F1', 'NUEVO');
			
		$datos = $this->Test_Model->medCat();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->codigoMedicamento);
			$sheet->setCellValue('B'.$flag, $d->nombreMedicamento);
			$sheet->setCellValue('C'.$flag, number_format($d->precioVMedicamento, 2));
			$sheet->setCellValue('D'.$flag, $d->tipoMedicamento);
			$sheet->setCellValue('E'.$flag, $d->stockMedicamento);				
			$sheet->setCellValue('F'.$flag, "");				
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
		$sheet->getStyle('A1:E1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:F'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:F'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A1:F'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'medicamento_hospital'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    }

	public function hoja_test($id = 10){
		$data["paciente"] = $this->Hoja_Model->detalleHoja($id);
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
			$data["externos"] = $this->Externos_Model->obtenerExternos();
			
			// Detalles de la hoja
				$data["externosHoja"] = $this->Hoja_Model->externosHoja($id);
				$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosHoja($id);
			
			// Código factura
				$codigoFactura = $this->Hoja_Model->hojaFactura($id);
				if($codigoFactura == NULL){

					// Consumidor final
						$facturaC = $this->Hoja_Model->obtenerCodigoFacturaC();
						$codC = 0;
						if($facturaC->codigo == NULL){
							$codC = 3965;
						}else{
							$codC = $facturaC->codigo + 1;
						}
						$data["facturaC"] = $codC;
					
					// Credito fiscal
						$facturaCF = $this->Hoja_Model->obtenerCodigoFacturaCF();
						$codCF = 0;
						if($facturaCF->codigo == NULL){
							$codCF = 2240;
						}else{
							$codCF = $facturaCF->codigo + 1;
						}
						$data["facturaCF"] = $codCF;
					
				}else{
					$data["facturaC"] = $codigoFactura->numeroFactura;
					$data["fechaFacturaHoja"] = $codigoFactura->fechaMostrar;
					$data["tipoFacturaHoja"] = $codigoFactura->tipoFactura;
					$data["facturaCF"] = $codigoFactura->numeroFactura;
					$data["pivoteFactura"] = 1;
				}
			$this->load->view('Base/header');
			$this->load->view('Hoja/detalle_hoja', $data);
			$this->load->view('Base/footer');
	}

	public function pacientes_hemodialisis(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'CODIGO');
		$sheet->setCellValue('C1', 'FECHA');
		$sheet->setCellValue('D1', 'PACIENTE');
			
		$datos = $this->Test_Model->pacientesHemodialisis();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->codigoHoja);
			$sheet->setCellValue('C'.$flag, $d->fechaHoja);
			$sheet->setCellValue('D'.$flag, $d->nombrePaciente);	
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
		$sheet->getStyle('A1:D1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
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

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'paciente_hemodialisis'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    }

	public function reporte_hemodialisis(){
		$datos = $this->Test_Model->reporteHemodialisis();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'CODIGO');
		$sheet->setCellValue('C1', 'PACIENTE');
		$sheet->setCellValue('D1', 'FECHA');
		$sheet->setCellValue('F1', 'TOTAL HOJA');
		$sheet->setCellValue('G1', 'TOTAL COSTO');
			
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->codigoHoja);
			$sheet->setCellValue('C'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('D'.$flag, $d->fechaHoja);

			// Sacando total hoja y total costo
				$total = 0;
				$costos =0;
				$medicamentos = $this->Test_Model->medicamentosUH($d->idHoja);
				foreach ($medicamentos as $fila) {
					$total += ($fila->cantidadInsumo * $fila->precioInsumo);
					$costos += ($fila->cantidadInsumo * $fila->precioCMedicamento);
				}
			$sheet->setCellValue('F'.$flag, $total);	
			$sheet->setCellValue('G'.$flag, $costos);	
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
		$sheet->getStyle('A1:G1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:G'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:G'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A1:G'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'paciente_hemodialisis'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function examenes_rx(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'CODIGO');
		$sheet->setCellValue('B1', 'NOMBRE');
		$sheet->setCellValue('C1', 'PRECIO');
		$sheet->setCellValue('D1', 'FERIADO');
			
		$datos = $this->Test_Model->examenesRX();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			//if($d->precioVMedicamento == $d->feriadoMedicamento){
				$sheet->setCellValue('A'.$flag, $d->codigoMedicamento);
				$sheet->setCellValue('B'.$flag, $d->nombreMedicamento);
				$sheet->setCellValue('C'.$flag, number_format($d->precioVMedicamento, 2));			
				$sheet->setCellValue('D'.$flag, number_format($d->feriadoMedicamento, 2));			
				$flag = $flag+1;
				$number = $number+1;
			///}
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
		$sheet->getStyle('A1:D1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
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

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'rayos_x'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    }

	public function examenes_laboratorio(){
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'NOMBRE');
		$sheet->setCellValue('C1', 'CANTIDAD');
			
		$datos = $this->Test_Model->examenesRX();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			if($d->precioVMedicamento == $d->feriadoMedicamento){
				$sheet->setCellValue('A'.$flag, $d->codigoMedicamento);
				$sheet->setCellValue('B'.$flag, $d->nombreMedicamento);
				$sheet->setCellValue('C'.$flag, number_format($d->precioVMedicamento, 2));			
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
		$sheet->getStyle('A1:D1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
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

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'rayos_x'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');



    }


	public function cambioFecha(){
		/* // Obteniendo el turno al que se agregara la ultra
			if(
				((date('h:i:s', time()) > date_create_from_format("h:i:s", "04:59:59")->format("h:i:s")) && (date('a', time()) == "pm")) && ((date('h:i:s', time()) < date_create_from_format("h:i:s", "11:59:59")->format("h:i:s")) && (date('a', time()) == "pm"))
				||
				((date('h:i:s', time()) > date_create_from_format("h:i:s", "01:00:00")->format("h:i:s")) && (date('a', time()) == "am")) && ((date('h:i:s', time()) < date_create_from_format("h:i:s", "06:59:59")->format("h:i:s")) && (date('a', time()) == "am"))
			){
				$turno = "Chimba nos juimos, subanle";
			}else{
				$turno = "Estamos en modo normal";
			}
		// Fin calcular turno

		// echo "El turno actual es: ".$turno;
		echo "<br>El turno actual es: ".$turno; */

		
		$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
		$precio = 25;
		$dia = date("w");
		
		//echo $dias[date("w")];
		$hora = date('H:i:s');  
		$ampm = date('a');
		$normal = false;  
		echo "La hora es ".$hora." ".$ampm."<br>";
		echo "<br>";

		if($dia >= 1 && $dia <= 5){
			
			switch ($hora) {
				case $hora > "07:00:00" && $ampm == "am":
					$normal = 1;
					break;
				case $hora < "16:00:00" && $ampm == "pm":
					$normal = 1;
					break;
				case $hora < "07:00:00" && $ampm == "am":
					if($dia == 1){						
						$normal = 2;
					}else{
						$normal = 0;
					}
					break;
				case $hora > "16:00:00" && $ampm == "pm":
					$normal = 0;
					break;
				default:
					echo "Son las ".$hora;
					break;
			}

			
		}else{
			if($dia == 6){
				switch ($hora) {
					case $hora < "07:00:00" && $ampm == "am":
						$normal = 0;
						break;
					case $hora > "07:00:00" && $ampm == "am":
						$normal = 1;
						break;
					case $hora > "12:00:00" && $ampm == "pm":
						$normal = 2;
						break;
					default:
						echo "Son las ".$hora;
						break;
				}
			}
			if($dia == 0){
				echo $normal = 2;
			}
			if($dia == 1){
				if($hora < "07:00:00" && $ampm == "am"){
					$normal = 2;
				}
			}
		}
		switch ($normal) {
			case '0':
				echo "Estamos fuera de horario";
				break;
			case '1':
				echo "Estamos en horario normal";
				break;
			case '2':
				echo "Estamos en horario plus";
				break;
			
			default:
				echo "Horario indefinido";
				break;
		}

	
	}


	public function actualizarPrecio(){
		$datos = $this->Test_Model->medicamentos();
		foreach ($datos as $fila) {
			echo "<br>".$fila->idMedicamento." ---------------> ".$fila->precioVMedicamento." ---------------> ".$fila->feriadoMedicamento;
			//$this->Test_Model->medicamentosU($fila->precioVMedicamento, $fila->idMedicamento);
		}
	}

	public function numeroAleatorio(){

		$personajes = ["Crash", "Mario", "Luigi", "Spyro", "Marco", "Yoshi"];
		unset($personajes[2]);
		sort($personajes, 2);
		echo json_encode($personajes);
		echo "<br>";
		echo $personajes[2];
		/*
		echo "<br>";
		$conteo = count($personajes);
		$indice_aleatorio = mt_rand(0, $conteo - 1);
		$personaje_aleatorio = $personajes[$indice_aleatorio];
		unset($personajes, $indice_aleatorio);
		echo $personaje_aleatorio; */
		/* sort($personajes);
		for ($i=0; $i < count($personajes) ; $i++) { 
			//echo var_dump($personajes)."<br>";
			$conteo = count($personajes);
			$indice_aleatorio = mt_rand(0, $conteo - 1);
			$personaje_aleatorio = $personajes[$indice_aleatorio];
			unset($personajes[$indice_aleatorio]);
			sort($personajes);
			echo $personajes[$indice_aleatorio];
		} */

	}


	public function pdfQR(){
		// Reporte PDF
		$data = array('hojas' => "Si" );
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
		$mpdf->SetHTMLFooter('');

		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Hospital Orellana, Usulutan");
		$mpdf->SetAuthor("Edwin Orantes");
		// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('L'); //Voltear Hoja
		$html = $this->load->view("Test/qr",$data,true); // Cargando hoja de estilos
		$mpdf->WriteHTML($html);
		$mpdf->Output('detalle_compra.pdf', 'I');
		//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
	// Fin reporte PDF
	}

}

?>