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


class Planilla extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Planilla_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
        $this->load->view('Base/header');
		$this->load->view('Planilla/crear_planilla');
		$this->load->view('Base/footer');

		// echo json_encode($data["personal"]);
    }

	public function crear_planilla(){
		$datos = $this->input->post();
		$pivote = $datos["tipoPlanilla"];
		if($pivote == 2){
			$datos["estado"] = 0;
		}else{
			$datos["estado"] = 1;
		}
		$resp = $this->Planilla_Model->crearPlanilla($datos); // true si se realizo la consulta.
		if($resp > 0){
			$this->session->set_flashdata("exito","La planilla fue creada con exito!");

			switch ($pivote) {
				case '2':
					redirect(base_url()."Planilla/agregar_personal_liquidacion/".$resp."/");
					break;
				case '3':
					redirect(base_url()."Planilla/agregar_personal_aguinaldo/".$resp."/");
					break;
				
				default:
					redirect(base_url()."Planilla/agregar_personal/".$resp."/");
					break;
			}
			/* if($pivote == 1){
				redirect(base_url()."Planilla/agregar_personal/".$resp."/");
			}else{
				redirect(base_url()."Planilla/agregar_personal_liquidacion/".$resp."/");
			} */
		}else{
			$this->session->set_flashdata("error","Error al crear la planilla!");
			redirect(base_url()."Planilla/");
		}

		// echo json_encode($datos);
	}

	public function agregar_personal($flag = null){
		$datos = $this->Planilla_Model->personalPlanilla();
		$count = 0;
		foreach ($datos as $row) {
			// echo json_encode($row->salarioEmpleado);
			$base = $row->salarioEmpleado;
			$salario = $row->salarioEmpleado/2;
			
			if($row->areaEmpleado == 4){
				$salario += ($row->bonoEmpleado/2); // Sumando el bono, si hay
			}

			// Calculo del ISSS
				switch ($base) {
					case( ($base >= 1) && ($base <= 1000)):
						$isss = $salario * 0.03;
						break;
					case ($base > 1000 ):
						$isss = 30/2;
						break;
						
					default:
						$isss = $base * 0.03;
						break;
				}
			//Fin calculo ISSS
		
			$afp = $salario * 0.0725;  // Calculando porcentaje a descontar de AFP
			
			switch ($row->pivoteRetenciones) {
				case '1':
					$isss = 0;
					$afp = 0;
					$totalRetenciones = 0;
					break;
				case '3':
					$isss = 0;
					$afp = 0;
					$totalRetenciones = 0;
					break;
				
				default:
					$totalRetenciones = $isss + $afp; //Sumando retenciones
					break;
			}

			$salario = $salario - $totalRetenciones;
		
			// Calculo de la renta
				switch ($salario) {
					case ( ($salario >= 1) && ($salario <= 236)):
						$renta = 0;
						break;
					case ( ($salario > 236) && ($salario <= 447.62)):
						$renta = 8.83 + (($salario -236 )* 0.10);
						break;
					case ( ($salario > 447.62) && ($salario <= 1019.05)):
						$renta = 30 + (($salario - 447.62 ) * 0.20);
						break;
					
					case ( ($salario > 1019.05) && ($salario <= 1000000000)):
						$renta = 144.28 + (($salario - 1019.05 ) * 0.30);
						break;
					
					default:
						# code...
						break;
				}
			// Fin calculo renta
			
			switch ($row->pivoteRetenciones) {
				case '1':
					$renta = ($base/2) *0.10;
					$salario = $salario - $renta ; // Descontando la renta
					break;
				case '3':
					$renta = 0;
					$salario = $salario - $renta ; // Descontando la renta
					break;
				default:
					$salario = $salario - $renta ;
					break;
			}

			if($row->areaEmpleado != 4){
				$salario += ($row->bonoEmpleado/2); // Sumando el bono, si hay
			}


			$personal[$count]["nombreEmpleado"] = $row->nombreEmpleado;
			$personal[$count]["idEmpleado"] = $row->idEmpleado;
			$personal[$count]["salarioEmpleado"] = $row->salarioEmpleado;	
			$personal[$count]["precioHoraExtra"] = $row->precioHoraExtra;
			$personal[$count]["bonoEmpleado"] = $row->bonoEmpleado;
			$personal[$count]["isss"] = $isss;
			$personal[$count]["afp"] = $afp;
			$personal[$count]["renta"] = $renta;
			$personal[$count]["liquido"] = round($salario, 2);
			$count++;

			//echo $row->nombreEmpleado." --- ".$row->areaEmpleado." --- ".$row->pivoteRetenciones."<br>";
		}
		$data["personal"] = $personal;
		$resp = $this->Planilla_Model->guardarPersonalPlanilla($data, $flag); // true si se realizo la consulta.
		if($resp ){
			$this->session->set_flashdata("exito","La planilla fue creada con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$flag."/");
		}else{
			$this->session->set_flashdata("error","Error al crear la planilla!");
			redirect(base_url()."Planilla/");
		}

		// echo json_encode($data);
	}

	public function agregar_personal_liquidacion($flag = null){
		$datos = $this->Planilla_Model->personalPlanilla();
		$count = 0;
		$inicio = date('Y').'-06-30';
		$pago  = 0;
		$salario  = 0;
		foreach ($datos as $row) {
			$anios = $this->calcularRangos($inicio, $row->ingresoEmpleado, 1);
			
			if($row->salarioEmpleado == 0){
				$salario = 365;
			}else{
				$salario = $row->salarioEmpleado;
			}

			if($anios === 0){
				$dias = $this->calcularRangos($inicio, $row->ingresoEmpleado, 2);
				$anio = 365;
				$pago = $dias * ($salario / $anio);
			}else{
				$pago = $salario;
			}
			$personal[$count]["idEmpleado"] = $row->idEmpleado;
			$personal[$count]["salarioEmpleado"] = $row->salarioEmpleado;	
			$personal[$count]["precioHoraExtra"] = 0;
			$personal[$count]["bonoEmpleado"] = 0;
			$personal[$count]["isss"] = 0;
			$personal[$count]["afp"] = 0;
			$personal[$count]["renta"] = 0;
			$personal[$count]["liquido"] = round($pago, 2);
			$count++;

			//echo $row->nombreEmpleado." --- ".$row->areaEmpleado." --- ".$row->pivoteRetenciones."<br>";
		}
		
		$data["personal"] = $personal;
		$resp = $this->Planilla_Model->guardarPersonalPlanilla($data, $flag); // true si se realizo la consulta.
		if($resp ){
			$this->session->set_flashdata("exito","La planilla fue creada con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$flag."/");
		}else{
			$this->session->set_flashdata("error","Error al crear la planilla!");
			redirect(base_url()."Planilla/");
		}

		// echo json_encode($personal);
	}

	private function calcularRangos($fechaInicio, $fechaFin, $pivote) {
		$valor = 0;
		// Crear objetos DateTime para las dos fechas
		$fechaInicio = new DateTime($fechaInicio);
		$fechaFin = new DateTime($fechaFin);

		// Calcular la diferencia entre las fechas
		$diferencia = $fechaInicio->diff($fechaFin);

		if($pivote == 1){
			// Obtener la diferencia en años
			$valor = (int)$diferencia->y;
		}else{
			// Obtener la diferencia en días
			$valor = (int)$diferencia->days;
		}

		// Devolver la diferencia en años
		return $valor;
	}

	public function detalle_planilla($flag = null){
		$data["personal"] = $this->Planilla_Model->personalXPlanilla($flag);
		$data["planillaActual"] = $flag;
		$data["estado"] = $this->Planilla_Model->estadoPlanilla($flag);
		$data["descuentos"] = $this->Planilla_Model->descuentosActivos($flag);
		$this->load->view('Base/header');
		$this->load->view('Planilla/detalle_planilla', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["descuentos"]);
	}

	public function editar_insumo(){}

	public function historial_planillas(){
		$data["planillas"] = $this->Planilla_Model->historialPlanillas();
		$this->load->view('Base/header');
		$this->load->view('Planilla/historial_planilla', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["planillas"]);
	}

	public function guardar_vacaciones(){
		if($this->input->is_ajax_request()){
			$vacaciones["salarioTotal"] = $this->input->post("salarioTotal");
			$vacaciones["vacaciones"] =  $this->input->post("vacaciones");
			$vacaciones["planilla"] =  $this->input->post("planilla");


			// Ejecutando consultas
			$bool = $this->Planilla_Model->guardarVacaciones($vacaciones);
			if($bool){
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
	}

	public function guardar_horas_extras(){
		if($this->input->is_ajax_request()){
			$extras["otros"] = $this->input->post("otros");
			$extras["horas"] = $this->input->post("horas");
			$extras["totalHoras"] =  $this->input->post("totalHoras");
			$extras["renta"] =  $this->input->post("renta");
			$extras["liquido"] =  $this->input->post("liquido");
			$extras["idFila"] =  $this->input->post("idFila");
			
			// Ejecutando consultas
			$bool = $this->Planilla_Model->guardarHorasExtras($extras);
			if($bool){
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
	}

	public function cerrar_planilla(){
		$datos = $this->input->post();
		$planilla = $datos["planillaCerrar"];
		$resp = $this->Planilla_Model->cerrarPlanilla($datos); // true si se realizo la consulta.
		if($resp){
			$this->session->set_flashdata("exito","La planilla fue cerrada con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$planilla."/");
		}else{
			$this->session->set_flashdata("error","Error al crear la planilla!");
			redirect(base_url()."Planilla/detalle_planilla/".$planilla."/");
		}
		/* echo json_encode($descuentos); */
	}

	public function anonima(){
        $data["personal"] = $this->Planilla_Model->obtenerEmpleados();
        $this->load->view('Base/header');
		$this->load->view('Planilla/detalle_planilla_anonima', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["personal"]);
    }
    
	public function planilla_excel($pivote = null){
		// Encriptacion
			//nadie mas debe conocerla
			$clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';
			//Metodo de encriptacion
			$method = 'aes-256-cbc';
			// Puedes generar una diferente usando la funcion $getIV()
			$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

			/*
			Desencripta el texto recibido
			*/
			$desencriptar = function ($valor) use ($method, $clave, $iv) {
				$encrypted_data = base64_decode($valor);
				return openssl_decrypt($valor, $method, $clave, false, $iv);
			};
			/*
			Genera un valor para IV
			*/
			$getIV = function () use ($method) {
				return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
			};

		// Encriptacion
 		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'EMPLEADO');
		$sheet->setCellValue('C1', 'SALARIO');
		$sheet->setCellValue('D1', 'DESCUENTO');
		$sheet->setCellValue('E1', 'A PAGAR');
		$sheet->setCellValue('F1', 'CONCEPTO');

		$border = [
            'borders' => [
                'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
		
		$sheet->getStyle('A1:F1')->applyFromArray($border);
 
		$datos = $this->Planilla_Model->planillaExcel($pivote);
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$des = $this->Planilla_Model->totalDescuentosMes($pivote, $d->idEmpleado);
			if(isset($des->descuento)){
				$descuento = $des->descuento;
			}else{
				$descuento = 0;
			}
			$sheet->setCellValue('A'.$flag, $desencriptar($d->seguimientoEmpleado));
			$sheet->setCellValue('B'.$flag, $d->nombreEmpleado);
			$sheet->setCellValue('C'.$flag, number_format(($d->liquidoDetallePlanilla + $descuento), 2));
			$sheet->setCellValue('D'.$flag, number_format($descuento, 2));
			$sheet->setCellValue('E'.$flag, number_format($d->liquidoDetallePlanilla, 2));
			$sheet->setCellValue('F'.$flag, $d->descripcionPlanilla);
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
		$sheet->getStyle('A1:D'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

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
		$filename = 'detalle_planilla'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function planilla_comprobante($pivote = null){
		$data["empleados"] = $this->Planilla_Model->comprobantePlanilla($pivote);
		$data["pivote"] = 1;

		// Factura
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 45,
				'margin_bottom' => 25,
				'margin_header' => 10,
				'margin_footer' => 10
			]);
			//$mpdf->setFooter('{PAGENO}');
			//$mpdf->SetHTMLFooter('');
			$mpdf->SetProtection(array('print'));
			$mpdf->SetTitle("Hospital Orellana, Usulutan");
			$mpdf->SetAuthor("Edwin Orantes");
			//$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
			$mpdf->showWatermarkText = true;
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->watermarkTextAlpha = 0.1;
			$mpdf->SetDisplayMode('fullpage');
			//$mpdf->AddPage('L'); //Voltear Hoja

			$html = $this->load->view('Planilla/comprobante_planilla', $data,true); // Cargando hoja de estilos

			$mpdf->WriteHTML($html);
			$mpdf->Output('detalle_compra.pdf', 'I');
		// Factura

		// echo json_encode($data);

	}

	public function comprobante_x_empleado($pivote = null){
		$data["empleados"] = $this->Planilla_Model->comprobanteXEmpleado($pivote);
		$data["pivote"] = 1;

		// Factura
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 45,
				'margin_bottom' => 25,
				'margin_header' => 10,
				'margin_footer' => 10
			]);
			//$mpdf->setFooter('{PAGENO}');
			// $mpdf->SetHTMLFooter('');
			// $mpdf->SetProtection(array('print'));
			$mpdf->SetTitle("Hospital Orellana, Usulutan");
			$mpdf->SetAuthor("Edwin Orantes");
			//$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
			$mpdf->showWatermarkText = true;
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->watermarkTextAlpha = 0.1;
			$mpdf->SetDisplayMode('fullpage');
			//$mpdf->AddPage('L'); //Voltear Hoja

			$html = $this->load->view('Planilla/comprobante_planilla', $data,true); // Cargando hoja de estilos

			$mpdf->WriteHTML($html);
			$mpdf->Output('detalle_comprobantes.pdf', 'I');
		// Factura

		// echo json_encode($data);

	}

	public function agregar_empleado_planilla(){
		$data["areas"] = $this->Planilla_Model->obtenerAreas();
		$this->load->view('Base/header');
		$this->load->view('Planilla/agregar_empleado', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["personal"]);
	}

	public function guardar_empleado(){
		$datos = $this->input->post();
		$resp = $this->Planilla_Model->guardarEmpleado($datos); // true si se realizo la consulta.
		if($resp){
			$this->session->set_flashdata("exito","El empleado fue guardado con exito!");
			redirect(base_url()."Planilla/agregar_empleado_planilla/");
		}else{
			$this->session->set_flashdata("error","Error al guardar el empleado!");
			redirect(base_url()."Planilla/agregar_empleado_planilla/");
		}
	}
    
	public function personal_planilla(){
		$data["personal"] = $this->Planilla_Model->personalPlanilla();
		$data["areas"] = $this->Planilla_Model->obtenerAreas();
		$this->load->view('Base/header');
		$this->load->view('Planilla/personal_planilla', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["personal"]);
	}
    
	public function detalle_empleado($emp = null){
		$data["empleado"] = $this->Planilla_Model->obtenerEmpleado($emp);
		$data["cuentasDescargos"] = $this->Planilla_Model->obtenerCuentasDescargos($emp);
		$data["descargos"] = $this->Planilla_Model->obtenerDescuentosEmpleado($emp);
		$data["salarios"] = $this->Planilla_Model->obtenerSueldos($emp);
		$data["cuentasPendientes"] = $this->Planilla_Model->cuentasPorPagar($emp);
		$this->load->view('Base/header');
		$this->load->view('Planilla/detalle_empleado', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data["cuentasPendientes"]);
	}

	public function actualizar_empleado(){
		$datos = $this->input->post();
		$resp = $this->Planilla_Model->actualizarEmpleado($datos); // true si se realizo la consulta.
		if($resp > 0){
			$this->session->set_flashdata("exito","El empleado fue actualizado con exito!");
			redirect(base_url()."Planilla/personal_planilla/");
		}else{
			$this->session->set_flashdata("error","Error al actualizar la planilla!");
			redirect(base_url()."Planilla/personal_planilla/");
		}

		// echo json_encode($data["empleado"] );
	}

	public function eliminar_empleado(){
		$datos = $this->input->post();
		$resp = $this->Planilla_Model->eliminarEmpleado($datos); // true si se realizo la consulta.
		if($resp > 0){
			$this->session->set_flashdata("exito","El empleado fue eliminado con exito!");
			redirect(base_url()."Planilla/personal_planilla/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar el empleado!");
			redirect(base_url()."Planilla/personal_planilla/");
		}

		// echo json_encode($datos);
	}

	public function descuentos_planilla(){
		$data["descuentos"] = $this->Planilla_Model->obtenerDescargosPlanilla();
		$this->load->view("Base/header");
		$this->load->view("Planilla/descuentos_planilla", $data);
		$this->load->view("Base/footer");
	}

	public function guardar_info_descuento(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Planilla_Model->guardarInfoDescargo($datos);
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
	}

	public function obtener_historial_descuentos(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Planilla_Model->obtenerHistorialDescuentos($datos);
			header("content-type:application/json");
			print json_encode($resp);
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

	public function editar_descuento(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$descuento["nombre"] = $datos["nombreD"];
			$descuento["id"] = $datos["idD"];
			$resp = $this->Planilla_Model->actualizarInfoDescargo($descuento);
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
	}

	public function guardar_descuento_empleado(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$resp = $this->Planilla_Model->cargarDescuentoEmpleado($datos);
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
	}

	public function procesar_descuentos(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$filaEditar = $datos["fila"];
			$datosFila = $this->Planilla_Model->empleadoDescuento($filaEditar); // Detalle de la fila a editar
			$nombreDescuento = $this->Planilla_Model->nombreDescuento($datos["idDescuento"]); // Nombre del descuento
			// Datos para consultas
			$data["cuota"] = $datos["cuota"];
			$data["liquido"] = $datosFila->liquidoDetallePlanilla;
			$data["nuevoLiquido"] = $datosFila->liquidoDetallePlanilla - $datos["cuota"];
			$data["idEmpleadoDescuento"] = $datos["idEmpleadoDescuento"];
			$data["idDescuento"] = $datos["idDescuento"];
			$data["fila"] = $datos["fila"];
			$data["planilla"] = $datos["planilla"];
			
			$data["descuento"] = $datosFila->descuentosPlanilla + $datos["cuota"]; // Sumando los descuentos
			$data["detalleDescuentos"] = $datosFila->detalleDescuentos."<p>".$nombreDescuento->nombreDP."<p>"; // Sumando los descuentos
			// Datos para consultas

			$bool = $this->Planilla_Model->guardarAbonoEmpleado($data);
			if($bool){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}

			// echo json_encode($nombreDescuento);
		}
		else{
			echo "Error...";
		}
	}

	public function saldar_descuentos(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$bool = $this->Planilla_Model->saldarDescuento($datos);
			if($bool){
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
			echo "Error...";
		}
	}

	public function agregar_retenciones(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$bool = $this->Planilla_Model->agregarRetenciones($datos);
			if($bool){
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
			echo "Error...";
		}
	}

	// Nueva forma de realizar calculos
	public function guardar_montos(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$fila = $this->Planilla_Model->obtenerFila($datos["idFila"]);
			
			// $liquido_nuevo = $fila->despuesRetenciones + $fila->totalVacaciones + $datos["totalOtros"] + $datos["totalExtras"] + $datos["lblTNocturnas"];
			$liquido_nuevo = floatval($fila->despuesRetenciones)  + floatval($fila->totalVacaciones) + floatval($datos["totalOtros"]) + floatval($datos["totalExtras"]) + floatval($datos["lblTNocturnas"]);
			$datos["txtLiquidoEmpleado"] = $liquido_nuevo;
			
			$resp = $this->Planilla_Model->guardarMontos($datos);
			if($resp >= 0){
				$respuesta = array('estado' => 1, 'respuesta' => 'Exito', 'liquido' => $liquido_nuevo);
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error', 'liquido' => $liquido_nuevo);
				header("content-type:application/json");
				print json_encode($respuesta);
			}
			// echo json_encode($datos);
		}
		else{
			echo "Error...";
		}
	}

	public function resetar_valores($flag = null){
		$datos = $this->input->post();
		$fila = $this->Planilla_Model->obtenerDetallePlanilla($datos["filaResetear"]);
		$empleado = $this->Planilla_Model->obtenerEmpleado($fila->idEmpleado);
		

		$base = $empleado->salarioEmpleado;
		$salario = $empleado->salarioEmpleado/2;
		if(($empleado->bonoEmpleado > 0)){
			$salario += ($empleado->bonoEmpleado/2); // Sumando el bono, si hay
		}

		// Calculo del ISSS
			switch ($base) {
				case( ($base >= 1) && ($base <= 1000)):
					$isss = $salario * 0.03;
					break;
				case ($base > 1000 ):
					$isss = 30/2;
					break;
					
				default:
					$isss = $base * 0.03;
					break;
			}
		//Fin calculo ISSS
	
		$afp = $salario * 0.0725;  // Calculando porcentaje a descontar de AFP
		
		switch ($empleado->pivoteRetenciones) {
			case '1':
				$isss = 0;
				$afp = 0;
				$totalRetenciones = 0;
				break;
			case '3':
				$isss = 0;
				$afp = 0;
				$totalRetenciones = 0;
				break;
			
			default:
				$totalRetenciones = $isss + $afp; //Sumando retenciones
				break;
		}

		$salario = $salario - $totalRetenciones;
	
		// Calculo de la renta
			switch ($salario) {
				case ( ($salario >= 1) && ($salario <= 236)):
					$renta = 0;
					break;
				case ( ($salario > 236) && ($salario <= 447.62)):
					$renta = 8.83 + (($salario -236 )* 0.10);
					break;
				case ( ($salario > 447.62) && ($salario <= 1019.05)):
					$renta = 30 + (($salario - 447.62 ) * 0.20);
					break;
				
				case ( ($salario > 1019.05) && ($salario <= 1000000000)):
					$renta = 144.28 + (($salario - 1019.05 ) * 0.30);
					break;
				
				default:
					# code...
					break;
			}
		// Fin calculo renta
		
		switch ($empleado->pivoteRetenciones) {
			case '1':
				$renta = ($base/2) *0.10;
				$salario = $salario - $renta ; // Descontando la renta
				break;
			case '3':
				$renta = 0;
				$salario = $salario - $renta ; // Descontando la renta
				break;
			default:
				$salario = $salario - $renta ;
				break;
		}

		
		$emp["salarioEmpleado"] = $empleado->salarioEmpleado;
		$emp["precioHoraExtra"] = $empleado->precioHoraExtra;
		$emp["bonoEmpleado"] = $empleado->bonoEmpleado;
		$emp["isss"] = $isss;
		$emp["afp"] = $afp;
		$emp["renta"] = $renta;
		$emp["despuesRetenciones"] = round($salario, 2);
		$emp["liquido"] = round($salario, 2);
		$emp["fila"] = $datos["filaResetear"];

		$resp = $this->Planilla_Model->resetearFila($datos["filaResetear"]); // true si se realizo la consulta.
		if($resp ){
			$this->Planilla_Model->actualizarFila($emp);
			$this->session->set_flashdata("exito","Datos procesados con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$fila->idPlanilla."/");
		}else{
			$this->session->set_flashdata("error","Error al procesar los datos!");
			redirect(base_url()."Planilla/detalle_planilla/".$fila->idPlanilla."/");
		}

		// echo json_encode($emp);
	}

	public function asignar_liquido(){
		$datos = $this->input->post();
		$planilla = $datos["idPlanillaA"];
		unset($datos["idPlanillaA"]);
		
		$bool = $this->Planilla_Model->asignarLiquido($datos); // true si se realizo la consulta.
		if($bool){
			$this->session->set_flashdata("exito","Datos procesados con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$planilla."/");
		}else{
			$this->session->set_flashdata("error","Error al procesar los datos!");
			redirect(base_url()."Planilla/detalle_planilla/".$planilla."/");
		}

		// echo json_encode($datos);
	}

	


	public function planilla_enfermeria1($pivote = null){
		// Encriptacion
			//nadie mas debe conocerla
			$clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';
			//Metodo de encriptacion
			$method = 'aes-256-cbc';
			// Puedes generar una diferente usando la funcion $getIV()
			$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

			/*
			Desencripta el texto recibido
			*/   
			$desencriptar = function ($valor) use ($method, $clave, $iv) {
				$encrypted_data = base64_decode($valor);
				return openssl_decrypt($valor, $method, $clave, false, $iv);
			};
			/*
			Genera un valor para IV
			*/
			$getIV = function () use ($method) {
				return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
			};

		// Encriptacion
		
 		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'EMPLEADO');
		$sheet->setCellValue('B1', 'BASE');
		$sheet->setCellValue('C1', 'ISSS');
		$sheet->setCellValue('D1', 'AFP');
		$sheet->setCellValue('E1', 'RENTA');
		$sheet->setCellValue('F1', 'A PAGAR');
		$sheet->setCellValue('G1', 'HORAS EXTRAS');

		$border = [
            'borders' => [
                'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
		
		$sheet->getStyle('A1:F1')->applyFromArray($border);
 
		$datos = $this->Planilla_Model->planillaEnfermeria($pivote);
		$number = 1;
		$flag = 2;
		$salario = 415;
		$horaExtra = round((($salario/30)/8*2), 2);
		
		$bono = 100;
		foreach($datos as $d){
			if($d->salarioEmpleado > 0){
				$salario = 415/2;
			}else{
				$salario = 0;
			}
			// $des = $this->Planilla_Model->totalDescuentosMes($pivote, $d->idEmpleado);
			$horas = $d->horasExtras * $horaExtra;
			$salarioTotal = $salario + $horas;

			if($d->idEmpleado == 19 || $d->idEmpleado == 62|| $d->idEmpleado == 13){
				$salarioTotal += ($bono / 2);
			}

			// $salario_liquido = $this->calculo_retenciones($salarioTotal);


			// Retenciones
				$base = $salarioTotal;
				$salario = $base;
				$renta = 0;
				// Calculo del ISSS
					switch ($base) {
						case( ($base >= 1) && ($base <= 1000)):
							$isss = $salario * 0.03;
							break;
						case ($base > 1000 ):
							$isss = 30/2;
							break;
							
						default:
							$isss = $base * 0.03;
							break;
					}
				//Fin calculo ISSS
			
				$afp = $salario * 0.0725;  // Calculando porcentaje a descontar de AFP
				
				$totalRetenciones = $isss + $afp; //Sumando retenciones
		
				$salario = $salario - $totalRetenciones;
			
				// Calculo de la renta
					switch ($salario) {
						case ( ($salario >= 1) && ($salario <= 236)):
							$renta = 0;
							break;
						case ( ($salario > 236) && ($salario <= 447.62)):
							$renta = 8.83 + (($salario -236 )* 0.10);
							break;
						case ( ($salario > 447.62) && ($salario <= 1019.05)):
							$renta = 30 + (($salario - 447.62 ) * 0.20);
							break;
						
						case ( ($salario > 1019.05) && ($salario <= 1000000000)):
							$renta = 144.28 + (($salario - 1019.05 ) * 0.30);
							break;
						
						default:
							# code...
							break;
					}
				// Fin calculo renta
		
					
				$salario = $salario - $renta ;
		
				// return $isss." - ".$afp." - ".$renta." - ".$salario;
			// Retenciones





			// echo $salario." + ".$horas." - ".$salarioTotal." - ".$salario_liquido."<br>";
			// echo $d->idEmpleado." -".$d->nombreEmpleado." -> ".$salario." + ".$horas." - ".$salarioTotal." ---------------------------- ".$salario_liquido."<br>";

			/* if(isset($des->descuento)){
				$descuento = $des->descuento;
			}else{
				$descuento = 0;
			} */
			$sheet->setCellValue('A'.$flag, $d->nombreEmpleado);
			$sheet->setCellValue('B'.$flag, number_format($salarioTotal, 2));
			
			$sheet->setCellValue('C'.$flag, number_format($isss, 2));
			$sheet->setCellValue('D'.$flag, number_format($afp, 2));
			$sheet->setCellValue('E'.$flag, number_format($renta, 2));
			$sheet->setCellValue('F'.$flag, number_format($salario, 2));

			$sheet->setCellValue('G'.$flag, number_format($horas, 2));
			$flag = $flag+1;
			$number = $number+1;

			/* $sheet->setCellValue('B1', 'EMPLEADO');
		$sheet->setCellValue('C1', 'ISSS');
		$sheet->setCellValue('D1', 'AFP');
		$sheet->setCellValue('E1', 'RENTA');
		$sheet->setCellValue('F1', 'A PAGAR'); */


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
		$sheet->getStyle('A1:D'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

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
		$filename = 'detalle_planilla'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		
	} 


	public function calculo_retenciones($mensual = null){ //Seria el salario mas total de horas extras

		/* $base = $row->salarioEmpleado;
		$salario = $row->salarioEmpleado/2; */

		$base = $mensual;
		$salario = $base;
		$renta = 0;
		// Calculo del ISSS
			switch ($base) {
				case( ($base >= 1) && ($base <= 1000)):
					$isss = $salario * 0.03;
					break;
				case ($base > 1000 ):
					$isss = 30/2;
					break;
					
				default:
					$isss = $base * 0.03;
					break;
			}
		//Fin calculo ISSS
	
		$afp = $salario * 0.0725;  // Calculando porcentaje a descontar de AFP
		
		$totalRetenciones = $isss + $afp; //Sumando retenciones

		$salario = $salario - $totalRetenciones;
	
		// Calculo de la renta
			switch ($salario) {
				case ( ($salario >= 1) && ($salario <= 236)):
					$renta = 0;
					break;
				case ( ($salario > 236) && ($salario <= 447.62)):
					$renta = 8.83 + (($salario -236 )* 0.10);
					break;
				case ( ($salario > 447.62) && ($salario <= 1019.05)):
					$renta = 30 + (($salario - 447.62 ) * 0.20);
					break;
				
				case ( ($salario > 1019.05) && ($salario <= 1000000000)):
					$renta = 144.28 + (($salario - 1019.05 ) * 0.30);
					break;
				
				default:
					# code...
					break;
			}
		// Fin calculo renta

			
		$salario = $salario - $renta ;

		// return $isss." - ".$afp." - ".$renta." - ".$salario;
		return $salario;
	}

	public function planilla_enfermeria2($pivote = null){
		// Encriptacion
			//nadie mas debe conocerla
			$clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';
			//Metodo de encriptacion
			$method = 'aes-256-cbc';
			// Puedes generar una diferente usando la funcion $getIV()
			$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

			/*
			Desencripta el texto recibido
			*/
			$desencriptar = function ($valor) use ($method, $clave, $iv) {
				$encrypted_data = base64_decode($valor);
				return openssl_decrypt($valor, $method, $clave, false, $iv);
			};
			/*
			Genera un valor para IV
			*/
			$getIV = function () use ($method) {
				return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
			};

		// Encriptacion
 		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'EMPLEADO');
		$sheet->setCellValue('B1', 'BASE');
		$sheet->setCellValue('C1', 'ISSS');
		$sheet->setCellValue('D1', 'AFP');
		$sheet->setCellValue('E1', 'RENTA');
		$sheet->setCellValue('F1', 'A PAGAR');

		$border = [
            'borders' => [
                'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
		
		$sheet->getStyle('A1:F1')->applyFromArray($border);
 
		$datos = $this->Planilla_Model->planillaExcel($pivote);
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$des = $this->Planilla_Model->totalDescuentosMes($pivote, $d->idEmpleado);
			if(isset($des->descuento)){
				$descuento = $des->descuento;
			}else{
				$descuento = 0;
			}
			$sheet->setCellValue('A'.$flag, $d->nombreEmpleado);
			$sheet->setCellValue('B'.$flag, $d->salarioEmpleado);
			$sheet->setCellValue('C'.$flag, $d->isssDetallePlanilla);
			$sheet->setCellValue('D'.$flag, $d->afpDetallePlanilla);
			$sheet->setCellValue('E'.$flag, $d->rentaDetallePlanilla);
			$sheet->setCellValue('F'.$flag, $d->liquidoDetallePlanilla);
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
		$sheet->getStyle('A1:D'.$flag)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

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
		$filename = 'detalle_planilla'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	// Funciones para encriptar
		public function encriptar(){
			//ConfiguraciÃ³n del algoritmo de encriptacion
			//Debes cambiar esta cadena, debe ser larga y unica
			//nadie mas debe conocerla
			$clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';
			//Metodo de encriptacion
			$method = 'aes-256-cbc';
			// Puedes generar una diferente usando la funcion $getIV()
			$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
			/*
			Encripta el contenido de la variable, enviada como parametro.
			*/
			$encriptar = function ($valor) use ($method, $clave, $iv) {
				return openssl_encrypt ($valor, $method, $clave, false, $iv);
			};
			/*
			Desencripta el texto recibido
			*/
			$desencriptar = function ($valor) use ($method, $clave, $iv) {
				$encrypted_data = base64_decode($valor);
				return openssl_decrypt($valor, $method, $clave, false, $iv);
			};
			/*
			Genera un valor para IV
			*/
			$getIV = function () use ($method) {
				return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
			};


			$personal = $this->Planilla_Model->personalEncriptar();
			/*foreach ($personal  as $row) {
				$dato_encriptado = $encriptar($row->seguimientoEmpleado);
				$dato_desencriptado = $desencriptar($dato_encriptado);
				//echo $row->idEmpleado." -- ".$row->nombreEmpleado." --- ".$row->seguimientoEmpleado." --- ".$dato_encriptado." --- ".$dato_desencriptado."<br>";
				//$this->Planilla_Model->personalUEncriptar($dato_encriptado, $row->idEmpleado);
			}*/
			$e = $encriptar("");
			$d = $desencriptar($e);
			echo $d;

		}
	// Funciones para encriptar
	

	public function test_personal($flag = null){
		$datos = $this->Planilla_Model->personalPlanilla();
		$count = 0;
		foreach ($datos as $row) {
			// echo json_encode($row->salarioEmpleado);
			$base = $row->salarioEmpleado;
			$salario = $row->salarioEmpleado/2;

			if(($row->bonoEmpleado > 0)){
				$salario += ($row->bonoEmpleado/2); // Sumando el bono, si hay
			}
			
			// Calculo del ISSS
				switch ($base) {
					case( ($base >= 1) && ($base <= 1000)):
						$isss = $salario * 0.03;
						break;
					case ($base > 1000 ):
						$isss = 30/2;
						break;
						
					default:
						$isss = $base * 0.03;
						break;
				}
			//Fin calculo ISSS
		
			$afp = $salario * 0.0725;  // Calculando porcentaje a descontar de AFP
			
			switch ($row->pivoteRetenciones) {
				case '1':
					$isss = 0;
					$afp = 0;
					$totalRetenciones = 0;
					break;
				case '3':
					$isss = 0;
					$afp = 0;
					$totalRetenciones = 0;
					break;
				
				default:
					$totalRetenciones = $isss + $afp; //Sumando retenciones
					break;
			}

			$salario = $salario - $totalRetenciones;
		
			// Calculo de la renta
				switch ($salario) {
					case ( ($salario >= 1) && ($salario <= 236)):
						$renta = 0;
						break;
					case ( ($salario > 236) && ($salario <= 447.62)):
						$renta = 8.83 + (($salario -236 )* 0.10);
						break;
					case ( ($salario > 447.62) && ($salario <= 1019.05)):
						$renta = 30 + (($salario - 447.62 ) * 0.20);
						break;
					
					case ( ($salario > 1019.05) && ($salario <= 1000000000)):
						$renta = 144.28 + (($salario - 1019.05 ) * 0.30);
						break;
					
					default:
						# code...
						break;
				}
			// Fin calculo renta
			
			switch ($row->pivoteRetenciones) {
				case '1':
					$renta = ($base/2) *0.10;
					$salario = $salario - $renta ; // Descontando la renta
					break;
				case '3':
					$renta = 0;
					$salario = $salario - $renta ; // Descontando la renta
					break;
				default:
					$salario = $salario - $renta ;
					break;
			}

			
			// $personal[$count]["nombreEmpleado"] = $row->nombreEmpleado;
			$personal[$count]["idEmpleado"] = $row->idEmpleado;
			$personal[$count]["salarioEmpleado"] = $row->salarioEmpleado;	
			$personal[$count]["precioHoraExtra"] = $row->precioHoraExtra;
			$personal[$count]["bonoEmpleado"] = $row->bonoEmpleado;
			$personal[$count]["isss"] = $isss;
			$personal[$count]["afp"] = $afp;
			$personal[$count]["renta"] = $renta;
			$personal[$count]["liquido"] = round($salario, 2);
			$count++;

			//echo $row->nombreEmpleado." --- ".$row->areaEmpleado." --- ".$row->pivoteRetenciones."<br>";
		}
		$data["personal"] = $personal;
		$resp = $this->Planilla_Model->guardarPersonalPlanilla($data, $flag); // true si se realizo la consulta.
		if($resp ){
			$this->session->set_flashdata("exito","La planilla fue creada con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$flag."/");
		}else{
			$this->session->set_flashdata("error","Error al crear la planilla!");
			redirect(base_url()."Planilla/");
		}

		// echo json_encode($data);
	}


	public function agregar_personal_aguinaldo($flag = null){
		$datos = $this->Planilla_Model->personalPlanilla();
		$count = 0;
		$fin = date("Y")."-12-".date("d");
		$pago  = 0;
		$salario  = 0;
		foreach ($datos as $row) {
			if($row->salarioEmpleado > 0){
				//echo $row->nombreEmpleado." ".$row->salarioEmpleado."<br>";

				$inicio = $row->ingresoEmpleado;
				$anios = $this->calcularRangos($inicio, $fin, 1);
				
				if($row->salarioEmpleado == 0){
					$salario = 365;
				}else{
					$salario = $row->salarioEmpleado;
				}

				if($anios === 0){
					$dias = $this->calcularRangos($inicio, $fin, 2);
					$pago = 50;
				}else{
					$salarioDia = ($salario / 30);
					switch ($anios) {
						case (($anios >= 1) && ($anios < 3)):
							$pago = $salarioDia * 15;
							break;
						case (($anios >= 3) && ($anios < 10)):
							$pago = $salarioDia * 19;
							break;
						case (($anios >= 10)):
							$pago = $salarioDia * 21;
							break;
						
						default:
							$pago = $salarioDia * 15;
							break;
					}
				}
				$personal[$count]["nombreEmpleado"] = $row->nombreEmpleado;
				$personal[$count]["idEmpleado"] = $row->idEmpleado;
				$personal[$count]["salarioEmpleado"] = $row->salarioEmpleado;	
				$personal[$count]["precioHoraExtra"] = 0;
				$personal[$count]["bonoEmpleado"] = 0;
				$personal[$count]["isss"] = 0;
				$personal[$count]["afp"] = 0;
				$personal[$count]["renta"] = 0;
				$personal[$count]["liquido"] = round($pago, 2);
				$count++;
			}

			// echo $row->nombreEmpleado." - ".$row->ingresoEmpleado." - ".$anios." -".$pago."<br>";
		}
		/* $data["personal"] = $personal;
		$resp = $this->Planilla_Model->guardarPersonalPlanilla($data, $flag); // true si se realizo la consulta.
		if($resp ){
			$this->session->set_flashdata("exito","La planilla fue creada con exito!");
			redirect(base_url()."Planilla/detalle_planilla/".$flag."/");
		}else{
			$this->session->set_flashdata("error","Error al crear la planilla!");
			redirect(base_url()."Planilla/");
		} */

		echo json_encode($personal);
	}


	public function agregar_personal_aguinaldo2($flag = null){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'EMPLEADO');
		$sheet->setCellValue('C1', 'SALARIO');
		$sheet->setCellValue('D1', 'F/I');
		$sheet->setCellValue('E1', 'F/F');
		$sheet->setCellValue('F1', 'TIEMPO');
		$sheet->setCellValue('G1', 'A PAGAR');

		$border = [
            'borders' => [
                'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
		
		$sheet->getStyle('A1:G1')->applyFromArray($border);

		$datos = $this->Planilla_Model->personalPlanilla();
		$count = 0;
		$fin = date("Y")."-12-12";
		$pago  = 0;
		$salario  = 0;
		$number = 1;
		$flag = 2;
		foreach ($datos as $row) {
			if($row->salarioEmpleado > 0){
				//echo $row->nombreEmpleado." ".$row->salarioEmpleado."<br>";

				$inicio = $row->ingresoEmpleado;
				$anios = $this->calcularRangos($inicio, $fin, 1);
				$dias = $this->calcularRangos($inicio, $fin, 2);
				
				if($row->salarioEmpleado == 0){
					$salario = 365;
				}else{
					$salario = $row->salarioEmpleado;
				}

				if($anios === 0){
					$dias = $this->calcularRangos($inicio, $fin, 2);
					$salarioDia = ($salario / 30); //Salario por dia
					$pago = ($salarioDia * 15) * ($dias/365);
				}else{
					$salarioDia = ($salario / 30);
					switch ($anios) {
						case (($anios >= 1) && ($anios < 3)):
							$pago = $salarioDia * 15;
							break;
						case (($anios >= 3) && ($anios < 10)):
							$pago = $salarioDia * 19;
							break;
						case (($anios >= 10)):
							$pago = $salarioDia * 21;
							break;
						
						default:
							$pago = $salarioDia * 15;
							break;
					}
				}

				$tiempo = $anios > 0 ? $anios." A&ntilde;os" : $dias." dias";
				$personal[$count]["nombreEmpleado"] = $row->nombreEmpleado;
				$personal[$count]["idEmpleado"] = $row->idEmpleado;
				$personal[$count]["salarioEmpleado"] = $row->salarioEmpleado;	
				$personal[$count]["ingresoEmpleado"] = $row->ingresoEmpleado;
				$personal[$count]["hoy"] = $fin;
				$personal[$count]["tiempo"] = $anios > 0 ? $anios." A&ntilde;os" : $dias." dias";
				$personal[$count]["precioHoraExtra"] = 0;
				$personal[$count]["bonoEmpleado"] = 0;
				$personal[$count]["isss"] = 0;
				$personal[$count]["afp"] = 0;
				$personal[$count]["renta"] = 0;
				$personal[$count]["liquido"] = round($pago, 2);
				$count++;

				$sheet->setCellValue('A'.$flag, $number);
				$sheet->setCellValue('B'.$flag, $row->nombreEmpleado);
				$sheet->setCellValue('C'.$flag, number_format(($row->salarioEmpleado), 2));
				$sheet->setCellValue('D'.$flag, $row->ingresoEmpleado);
				$sheet->setCellValue('E'.$flag, $fin);
				$sheet->setCellValue('F'.$flag, $tiempo);
				$sheet->setCellValue('G'.$flag, number_format($pago, 2));
				$flag = $flag+1;
				$number = $number+1;

			}

			// echo $row->nombreEmpleado." - ".$row->ingresoEmpleado." - ".$anios." -".$pago."<br>";
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
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'detalle_planilla'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	

		
	}


} 
