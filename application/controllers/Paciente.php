<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	// Clases para el reporte en excel
		use Mpdf\Tag\Dd;
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

class Paciente extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Paciente_Model");
		$this->load->model("Medico_Model");
		$this->load->model("Usuarios_Model");
		$this->load->model("Hoja_Model");
		$this->load->model("Facturacion_Model");
	}

	public function agregar_pacientes(){
		$data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
		$data["medicos"] = $this->Medico_Model->obtenerMedicos();
		$this->load->view('Base/header');
		$this->load->view('Paciente/agregar_paciente', $data);
		$this->load->view('Base/footer');
		// echo json_encode($data);
    }
    
    public function lista_pacientes(){
		
		/* $pacientes = $this->Paciente_Model->obtenerPacientes();
		$departamentos = $this->Paciente_Model->obtenerDepartamentos();
		$medicos = $this->Medico_Model->obtenerMedicos();
		$data = array('pacientes' => $pacientes, 'departamentos' => $departamentos, 'medicos' => $medicos); */

		$this->load->view('Base/header');
		// $this->load->view('Paciente/lista_paciente', $data);
		$this->load->view('Paciente/busqueda_paciente');
		$this->load->view('Base/footer');
		
	}

	public function resultado_busqueda($txt = null){
		$parametro = "";
		if($this->input->post()) {
			$parametro = $datos = $this->input->post("busquedaPaciente");
		} else {
			$parametro = str_replace("%20"," ", $txt);
		}

		$datos = $this->input->post();
		$pacientes = $this->Paciente_Model->busquedaPaciente(trim($parametro));
		$departamentos = $this->Paciente_Model->obtenerDepartamentos();
		$medicos = $this->Medico_Model->obtenerMedicos();
		$data = array('pacientes' => $pacientes, 'departamentos' => $departamentos, 'medicos' => $medicos);

		$this->load->view('Base/header');
		$this->load->view('Paciente/lista_paciente', $data);
		// $this->load->view('Paciente/busqueda_paciente');
		$this->load->view('Base/footer');
	}

	public function editar_paciente($id){
		$data["paciente"] = $this->Paciente_Model->detalleXPaciente($id);
		$this->load->view('Base/header');
		$this->load->view('Paciente/editar_paciente', $data);
		$this->load->view('Base/footer'); 
		// echo json_encode($data);
	}

	public function detalle_paciente($id){
		$data["paciente"] = $this->Paciente_Model->detallePaciente($id);
		$data["expedientes"] = $this->Paciente_Model->hojasPaciente($id);
		$this->load->view('Base/header');
		$this->load->view('Paciente/detalle_paciente', $data);
		$this->load->view('Base/footer'); 
	}

	// Municipios de El Salvador
	public function obtener_municipios(){
		if($this->input->is_ajax_request())
		{
			$id =$this->input->get("id");
			$datos = $this->Facturacion_Model->obtenerDistritos($id);
			echo json_encode($datos);
		}
		else
		{
			echo "Error...";
		}
	}

	public function agregar_paciente(){
		$datos = $this->input->post();
		
		//Pacientes
			$paciente["duiPaciente"] = $datos["duiPaciente"];
			$paciente["nombrePaciente"] = $datos["nombrePaciente"];
			$paciente["nacimientoPaciente"] = $datos["nacimientoPaciente"];
			$paciente["civilPaciente"] = $datos["civilPaciente"];
			$paciente["telefonoPaciente"] = $datos["telefonoPaciente"];
			$paciente["edadPaciente"] = $datos["edadPaciente"];
			$paciente["ocupacionPaciente"] = $datos["ocupacionPaciente"];
			$paciente["sexoPaciente"] = $datos["sexoPaciente"];
			$paciente["tipeoPaciente"] = $datos["tipeoPaciente"];
			$paciente["direccionPaciente"] = $datos["direccionPaciente"];
			$paciente["idPaciente"] = $datos["idPaciente"];
		//Pacientes

		// Responsables
			$responsable["nombreResponsable"] = $datos["nombreResponsable"];
			$responsable["edadResponsable"] = $datos["edadResponsable"];
			$responsable["telefonoResponsable"] = $datos["telefonoResponsable"];
			$responsable["duiResponsable"] = $datos["duiResponsable"];
			$responsable["profesionResponsable"] = $datos["profesionResponsable"];
			$responsable["parentescoResponsable"] = $datos["parentescoResponsable"];
			$responsable["direccionResponsable"] = $datos["direccionResponsable"];
			if($datos["edadPaciente"] < 18){
				$responsable["pivote"] = 0;
			}else{
				$responsable["pivote"] = 1;
			}
		// Responsables

		if($paciente["idPaciente"] == 0){
			unset($paciente["idPaciente"]);
			$bool = $this->Paciente_Model->guardarPaciente($paciente);// Agregar paciente
		}else{
			$bool = $this->Paciente_Model->actualizarPaciente($paciente); // Actualizar paciente
		}
		
		
		// Responsable
			if($datos["idResponsable"] == 0){
				$responsable["idMenor"] = $bool;
				$responsable["accion"] = 1;
			}else{
				$responsable["idResponsable"] = $datos["idResponsable"];
				$responsable["accion"] = 2;
			}
		// Responsable
		
		if($bool){
			$bool2 = $this->Paciente_Model->guardarResponsables($responsable);
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Paciente/motivo_paciente/".$bool."/"); // Redirigiendo al siguiente paso
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Paciente/agregar_pacientes");
		}
		
		// echo json_encode($paciente);

	}

	public function actualizar_paciente_responsable(){
		$datos = $this->input->post();

		//Pacientes
			$paciente["duiPaciente"] = $datos["duiPaciente"];
			$paciente["nombrePaciente"] = $datos["nombrePaciente"];
			$paciente["nacimientoPaciente"] = $datos["nacimientoPaciente"];
			$paciente["civilPaciente"] = $datos["civilPaciente"];
			$paciente["telefonoPaciente"] = $datos["telefonoPaciente"];
			$paciente["edadPaciente"] = $datos["edadPaciente"];
			$paciente["ocupacionPaciente"] = $datos["ocupacionPaciente"];
			$paciente["sexoPaciente"] = $datos["sexoPaciente"];
			$paciente["tipeoPaciente"] = $datos["tipeoPaciente"];
			$paciente["direccionPaciente"] = $datos["direccionPaciente"];
			$paciente["idPaciente"] = $datos["idPaciente"];
		//Pacientes

		// Responsables
			$responsable["nombreResponsable"] = $datos["nombreResponsable"];
			$responsable["edadResponsable"] = $datos["edadResponsable"];
			$responsable["telefonoResponsable"] = $datos["telefonoResponsable"];
			$responsable["duiResponsable"] = $datos["duiResponsable"];
			$responsable["profesionResponsable"] = $datos["profesionResponsable"];
			$responsable["parentescoResponsable"] = $datos["parentescoResponsable"];
			$responsable["direccionResponsable"] = $datos["direccionResponsable"];
			$responsable["idResponsable"] = $datos["idResponsable"];
			$responsable["accion"] = 2;
		// Responsables
		
		$bool = $this->Paciente_Model->actualizarPaciente($paciente); // Actualizar paciente
		if($bool){
			$bool2 = $this->Paciente_Model->guardarResponsables($responsable);
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Paciente/editar_paciente/".$paciente["idPaciente"]."/"); // Redirigiendo al siguiente paso
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Paciente/editar_paciente/".$paciente["idPaciente"]."/");
		}
		
		// echo json_encode($datos);

	}

	public function motivo_paciente($param = null){
		$data["paciente"] = $this->Paciente_Model->detallePaciente($param);
		$data["empleados"] = $this->Planilla_Model->personalPlanilla();
		$data["medicos"] = $this->Medico_Model->obtenerMedicos();
		$data["habitaciones"] = $this->Paciente_Model->obtenerHabitaciones2();
		$data["seguros"] = $this->Hoja_Model->segurosActivos();

		$codigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
		$cod = 0;
		if($codigo->codigoHoja == NULL ){
			$cod = 1000;
		}else{
			$cod = ($codigo->codigoHoja + 1);
		}
		$data["codigo"] = $cod;

		$this->load->view('Base/header');
		$this->load->view('Paciente/motivo_paciente', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data);
	}

	public function actualizar_paciente(){
		$datos = $this->input->post();
		$return = 0;
		if(isset($datos["returnHoja"])){
			$return = 1;
			$hoja = $datos["idHojaCobro"];
			unset($datos["returnHoja"]);
			unset($datos["idHojaCobro"]);

			// Se visualizo el resumen
			$bitacora["idCuenta"] = $hoja;
			$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
			$bitacora["usuario"] = $this->session->userdata('usuario_h');
			$bitacora["descripcionBitacora"] = "Actualizo los datos del paciente";
			
		// Se visualizo el resumen
		}

		$bool = $this->Paciente_Model->actualizarPacienteHoja($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			if($return == 0){
				redirect(base_url()."Paciente/resultado_busqueda/".$datos["nombrePaciente"]);
			}else{
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				redirect(base_url()."Hoja/detalle_hoja/".$hoja."/");
			}
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			if($return == 0){
				redirect(base_url()."Paciente/lista_pacientes/".$datos["nombrePaciente"]);
			}else{
				redirect(base_url()."Hoja/detalle_hoja/".$hoja."/");
			}
			
		}
		
	}

	public function eliminar_paciente(){
		$datos = $this->input->post();
		$bool = $this->Paciente_Model->eliminarPaciente($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Paciente/lista_pacientes");
		}else{
			$this->session->set_flashdata("error","Hubo un error al aliminar los datos!");
			redirect(base_url()."Paciente/lista_pacientes");
		}
	}

	public function lista_pacientes_excel(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nombre');
		$sheet->setCellValue('B1', 'Edad');
		$sheet->setCellValue('C1', 'Teléfono');
		$sheet->setCellValue('D1', 'DUI');
		$sheet->setCellValue('E1', 'Fecha de nacimiento');
		$sheet->setCellValue('F1', 'Dirección');
			
		$datos = $this->Paciente_Model->obtenerPacientes();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('B'.$flag, $d->edadPaciente);
			$sheet->setCellValue('C'.$flag, $d->telefonoPaciente);
			$sheet->setCellValue('D'.$flag, $d->duiPaciente);
			$sheet->setCellValue('E'.$flag, $d->nacimientoPaciente);
			$sheet->setCellValue('F'.$flag, $d->direccionPaciente);
				
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
		$sheet->getStyle('A1:F10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_pacientes '.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	// Control de habitaciones
	public function paciente_habitacion(){
		$this->load->view('Base/header');
		
		$pacientes = $this->Paciente_Model->obtenerPacientesHabitacion();
		
		$data = array('pacientes' => $pacientes);

		$this->load->view('Paciente/paciente_habitacion', $data);
		$this->load->view('Base/footer');

		

	}	

	// Obteniendo DUI de paciente para validad
	public function validar_paciente(){
		if($this->input->is_ajax_request()){
			$datos =$this->input->post();
			$data = $this->Paciente_Model->validadPaciente($datos);
			if(!is_null($data) > 0){
				$fecha1 = new DateTime($data->nacimientoPaciente);
				$fecha2 = new DateTime(date('Y-m-d'));
				$diferencia = $fecha1->diff($fecha2);
				$data->edadPaciente = $diferencia->y;

				$respuesta = array('estado' => 1, 'respuesta' => 'Exito', 'datos' => $data);
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error', 'datos' => null );
				header("content-type:application/json");
				print json_encode($respuesta);
			}
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error', 'datos' => null );
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

	public function esquema_habitaciones(){
		$this->load->view('Base/header');
		$habitaciones = $this->Paciente_Model->obtenerHabitaciones();
		$data = array('habitaciones' => $habitaciones);
		$this->load->view('Paciente/habitaciones', $data);
		$this->load->view('Base/footer');

	}

	public function detalle_habitacion(){
		if($this->input->is_ajax_request()){
			$id =$this->input->get("id");
			$data = $this->Paciente_Model->detalleHabitacion($id);
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	public function senso_diario(){
		$fecha = date('Y-m-d');
		$data["detalleSenso"] = $this->Paciente_Model->sensoHoy($fecha);
		
		$this->load->view("Base/header");
		$this->load->view("Paciente/senso_hoy", $data);
		$this->load->view("Base/footer");
	}

	public function obtener_detalle(){
		if($this->input->is_ajax_request()){
			$paciente =$this->input->post("id");
			$data = $this->Paciente_Model->obtenerDetalle(trim($paciente));
			echo json_encode($data);
		}else{
			echo "Error";
		}
	}

	// Busqueda asincrona de paciente
		public function busqueda_paciente(){
			if($this->input->is_ajax_request()){
				$paciente =$this->input->post("paciente");
				$data = $this->Paciente_Model->busquedaPaciente(trim($paciente));
				echo json_encode($data);
			}else{
				echo "Error";
			}
		}

		public function recomendaciones_paciente(){
			if($this->input->is_ajax_request()){
				$id =$this->input->get("id");
				$data = $this->Paciente_Model->buscarRecomendaciones(trim($id));
				echo json_encode($data);
			}
			else{
				echo "Error...";
			}
		}
	// Fin busqueda


	// Metodos para los consentimientos
	
	public function imprimir_consentimiento($paciente = null, $hoja = null, $pivote = null){
		// Datos para bitacora -Editar medicamento cuenta
			$bitacora["idCuenta"] = $hoja;
			$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
			$bitacora["usuario"] = $this->session->userdata('usuario_h');
			$bitacora["descripcionBitacora"] = "Genero el consentimiento informado";
			$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
		// Fin datos para bitacora -Editar medicamento cuenta	

		if($pivote == 1){
			redirect(base_url()."Paciente/consentimiento_menor_pdf/".$paciente."/".$hoja."/");
		}else{
			redirect(base_url()."Paciente/consentimiento_mayor_pdf/".$paciente."/".$hoja."/");
		}
	}


	public function consentimiento_menor(){
		$datos = $this->input->post();
		$responsable = array();
		//Datos del menor
			$menor["nombre"] = $datos["nombreMenor"];
			$menor["edad"] = $datos["edadMenor"];
			$menor["id"] = $datos["idMenor"];
		//Datos del menor
		
		//Datos del responsable
			$responsable["menor"] = $datos["idMenor"];
			$responsable["nombre"] = $datos["nombreResponsable"];
			$responsable["edad"] = $datos["edadResponsable"];
			$responsable["telefono"] = $datos["telefonoResponsable"];
			$responsable["dui"] = $datos["duiResponsable"];
			$responsable["oficio"] = $datos["profesionResponsable"];
			$responsable["parentesco"] = $datos["parentescoResponsable"];
			$responsable["direccion"] = $datos["direccionResponsable"];
			$responsable["es"] = $datos["esMenor"];
		//Datos del responsable
		
		if($datos["idResponsable"] == 0){
			// echo "El responsable no esta guardado";
			$responsable["pivote"] = 1; //1 Insert
		}else{
			$responsable["id"] = $datos["idResponsable"];
			$responsable["pivote"] = 2; //2 Update
		}

		 $data["menor"] = $menor;
		 $data["responsable"] = $responsable;

		$bool = $this->Paciente_Model->guardarDatos($data);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Hoja/detalle_hoja/".$datos["idHojaCobro"]."/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			redirect(base_url()."Hoja/detalle_hoja/".$datos["idHojaCobro"]."/");
		}

		// echo json_encode($data);
	}

	public function consentimiento_menor_pdf($paciente = null, $hoja = null){
		$data["paciente"] = $this->Paciente_Model->datosConsentimientos($hoja);
		$data["medico"] = $this->Paciente_Model->detallePaciente($paciente);
		if($data["paciente"]->edadPaciente < 18){
			$data["responsable"] = $this->Paciente_Model->obtenerResponsable($paciente);
		}

		// Reporte PDF
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 35,
				'margin_bottom' => 30,
				'margin_header' => 15,
				'margin_footer' => 23
			]);
			//$mpdf->setFooter('{PAGENO}');
			$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
				<div id="lateral">
					<p><img src="'.base_url().'public/img/logo.jpg" alt=""></p>
				</div>
				<div id="principal">
					<table>
						<tr>
							<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
						</tr>
						<tr>
							<td><strong>Sexta calle oriente, #6, Usulután, El Salvador</strong></td>
						</tr>
					</table>
				</div>
			</div>');

			$mpdf->SetHTMLFooter('
				<table width="100%">
					<tr>
					<td width="33%" style="font-weight: bold; font-size: 12px;"></td>
					<td width="33%" style="font-weight: bold; font-size: 12px; text-align: right;"></td>
					<td width="33%" align="right" style="font-weight: bold; font-size: 12px;">{PAGENO}/{nbpg}</td>
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
			$html = $this->load->view("Paciente/consentimiento_menor",$data,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html);
			$mpdf->Output('consentimiento_informado.pdf', 'I');
			//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
		// Fin reporte PDF

		// echo json_encode($data);
	}

	public function consentimiento_mayor(){
		$datos = $this->input->post();
		$hoja = $datos["idHojaCobroC"];
		$idResponsableC = $datos["idResponsableC"];

		$responsable = array();
		//Datos del responsable
			$responsable["paciente"] = $datos["idPacienteC"];
			$responsable["nombre"] = $datos["responsableConsentimiento"];
			$responsable["parentesco"] = $datos["parentescoConsentimiento"];
			$responsable["duiResponsableC"] = $datos["duiResponsableC"];
			unset($datos["responsableConsentimiento"]);
			unset($datos["parentescoConsentimiento"]);
			unset($datos["duiResponsableC"]);
			unset($datos["idResponsableC"]);
			unset($datos["idHojaCobroC"]);
		//Datos del responsable
		
		//Datos del responsable
			$paciente = $datos;
		//Datos del responsable
		
		if($idResponsableC == 0){
			// echo "El responsable no esta guardado";
			$responsable["pivote"] = 1; //1 Insert
		}else{
			$responsable["idResponsableC"] = $idResponsableC;
			$responsable["pivote"] = 2; //2 Update
		}
		$responsable["flag"] = 1;

		 $data["paciente"] = $paciente;
		 $data["responsable"] = $responsable;

		// Para bitacora
			$bitacora["idCuenta"] = $hoja;
			$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
			$bitacora["usuario"] = $this->session->userdata('usuario_h');
			$bitacora["descripcionBitacora"] = "Actualizo informacion para elaborar el consentimiento informado";
		// Para bitacora

		$bool = $this->Paciente_Model->guardarDatosMayor($data);
		if($bool){
			$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Hoja/detalle_hoja/".$hoja."/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			redirect(base_url()."Hoja/detalle_hoja/".$hoja."/");
		}

		// echo json_encode($data);
	}

	public function consentimiento_mayor_pdf($paciente = null, $hoja = null){
		$data["paciente"] = $this->Paciente_Model->datosConsentimientos($hoja);
		$data["medico"] = $this->Paciente_Model->detallePaciente($paciente);
		
		$responsable = $this->Paciente_Model->obtenerResponsable($paciente);
		if(!is_null($responsable)){
			$data["responsable"] = $responsable;
		}
			

		// Reporte PDF
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 35,
				'margin_bottom' => 30,
				'margin_header' => 15,
				'margin_footer' => 23
			]);
			//$mpdf->setFooter('{PAGENO}');
			$mpdf->SetHTMLHeader('<div id="cabecera" class="clearfix">
				<div id="lateral">
					<p><img src="'.base_url().'public/img/logo.jpg" alt=""></p>
				</div>
				<div id="principal">
					<table>
						<tr>
							<td><strong>HOSPITAL ORELLANA, USULUTAN</strong></td>
						</tr>
						<tr>
							<td><strong>Sexta calle oriente, #6, Usulután, El Salvador</strong></td>
						</tr>
					</table>
				</div>
			</div>');

			$mpdf->SetHTMLFooter('
				<table width="100%">
					<tr>
					<td width="33%" style="font-weight: bold; font-size: 12px;"></td>
					<td width="33%" style="font-weight: bold; font-size: 12px; text-align: right;"></td>
					<td width="33%" align="right" style="font-weight: bold; font-size: 12px;">{PAGENO}/{nbpg}</td>
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
			$html = $this->load->view("Paciente/consentimiento_mayor",$data,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html);
			$mpdf->Output('consentimiento_informado.pdf', 'I');
			//$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
		// Fin reporte PDF

		// echo json_encode($data);
	}
	// Metodos para los consentimientos

}
