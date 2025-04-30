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

	// Importar la clase Curl
		use Curl\Curl;
	// Importar la clase Curl

class Medico extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Medico_Model");
	}

	public function index(){
		$medicos = $this->Medico_Model->obtenerMedicos();
		$data = array('medicos' => $medicos);
		$this->load->view('Base/header');
		$this->load->view('Medico/lista_medicos', $data);
		$this->load->view('Base/footer');
    }
	
	public function prueba(){
		$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
		redirect(base_url()."Medico/");
	}

	public function actualizar_medico(){
		$data = $this->input->post();
		$datos = array('nombreMedico ' => $data['nombreMedicoA'], 'especialidadMedico ' => $data['especialidadMedicoA'],
		'telefonoMedico ' => $data['telefonoClinicaA'], 'direccionMedico ' => $data['direccionMedicoA'], 'idMedico ' => $data['idMedicoA']);
		$bool = $this->Medico_Model->actualizarMedico($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Medico/");
		}else{
			$this->session->set_flashdata("error","Los datos no fueron actualizados!");
			redirect(base_url()."Medico/");
		}
	}
	
	public function guardar_medico(){
		$datos = $this->input->post();
		// Datos para el servicio externo
			$externo["medico"] = $datos["nombreMedico"];
			$externo["tipoEntidad"] = 1;
			$externo["descripcionExterno"] = "Para pago de honorarios";
			// Creando un solo arreglo
			$data["medico"] = $datos;
			$data["externo"] = $externo;
		$bool = $this->Medico_Model->guardarMedico($data);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Medico/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos!");
			redirect(base_url()."Medico/");
		}

	}

	public function eliminar_medico(){
		$id = $this->input->post('idEliminar');
		$data = array('idMedico' => $id );
		$bool = $this->Medico_Model->eliminarMedico($data);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Medico/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar los datos!");
			redirect(base_url()."Medico/");
		}
	}

	public function medicos_excel(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Id');
		$sheet->setCellValue('B1', 'Nombre');
		$sheet->setCellValue('C1', 'Especialidad');
		$sheet->setCellValue('D1', 'Telefono');
		$sheet->setCellValue('E1', 'Dirección');
			
		$datos = $this->Medico_Model->obtenerMedicos();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->nombreMedico);
			$sheet->setCellValue('C'.$flag, $d->especialidadMedico);
			$sheet->setCellValue('D'.$flag, $d->telefonoMedico);
			$sheet->setCellValue('E'.$flag, $d->direccionMedico);
				
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
		$sheet->getStyle('A1:H1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:H10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:H10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_medicos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}


	public function dte(){
		$cadena = "DTE-01-12345678-000000000000001";
		$longitud = strlen($cadena);

		// echo "La longitud de la cadena es: " . $longitud;

		// Generar 16 bytes aleatorios
		$bytesAleatorios = random_bytes(16);
    
		// Convertir a formato hexadecimal y en mayúsculas
		$hex = strtoupper(bin2hex($bytesAleatorios));
		
		// Insertar los guiones para cumplir con el formato UUID v4
		$uuid = substr($hex, 0, 8) . '-' .
				substr($hex, 8, 4) . '-' .
				substr($hex, 12, 4) . '-' .
				substr($hex, 16, 4) . '-' .
				substr($hex, 20, 12);
		
		// Establecer los bits de la versión (UUID v4)
		$uuid[14] = '4'; // Versión 4
		$uuid[19] = dechex((hexdec($uuid[19]) & 0x3) | 0x8); // Variant (10xx)
		
		$cadena = $uuid;
		$longitud = strlen($cadena);

		echo $longitud;

	}

	public function firmar(){
		/* $data = '{
			"nit" : "06142405161029",
			"activo" : true,
			"passwordPri" : "UnionMedica_25",
			"dteJson": {
					"identificacion": {
						"version": 1,
						"ambiente": "00",
						"tipoDte": "01",
						"numeroControl": "DTE-01-12345678-000000000000001",
						"codigoGeneracion": "FF54E9DB-79C3-42CE-B432-EC522C97EFB9",
						"tipoModelo": 1,
						"tipoOperacion": 1,
						"tipoContingencia": null,
						"motivoContin": null,
						"fecEmi": "2021-10-27",
						"horEmi": "15:58:30",
						"tipoMoneda": "USD",
					
					},
					"documentoRelacionado": null,
					"emisor": {
						"nit": "06142407961057",
						"nrc": "1208778",
						"nombre": "HELIOS INTERNACIONAL, S.A. DE C.V.",
						"codActividad": "46484",
						"descActividad": "Venta de productos farmacéuticos y medicinales",
						"nombreComercial": "HELIOS INTERNACIONAL, S.A. DE C.V.",
						"tipoEstablecimiento": "02",
						"direccion": {
							"departamento": "06",
							"municipio": "21",
							"complemento": "10a CALLE ORIENTE Y 8a AV. SUR BO. LA VEGA  # 470"
						},
						"telefono": "22810222",
						"correo": "info@heliosdrogueria.com",
						"codEstableMH": "0000",
						"codEstable": "0000",
						"codPuntoVentaMH": "0000",
						"codPuntoVenta": "000000000000000"

					},
					"receptor": {
						"tipoDocumento": "13",
						"numDocumento": "00000000-0",
						"nrc": null,
						"nombre": "Juan perez",
						"codActividad": null,
						"descActividad": null,
						"direccion": null,
						"telefono": null,
						"correo": null
					},
					"otrosDocumentos": null,
					"ventaTercero": {
						"nit": "00000000000000",
						"nombre": "Nombre, denominación o razón social del Tercero"
					},
					"cuerpoDocumento": [
						{
							"numItem": 1,
							"tipoItem": 1,
							"numeroDocumento": null,
							"cantidad": 50.000,
							"codigo": "codigo interno insumo o null",
							"codTributo": null,
							"uniMedida": 59,
							"descripcion": "BIO-MIKIN 500 MG SOL INY VIAL X 2ML",
							"precioUni": 3.01940000,
							"montoDescu": 0.00000000,
							"ventaNoSuj": 0.00,
							"ventaExenta": 0.00000000,
							"ventaGravada": 150.97000000,
							"tributos": [
								"20"
							],
							"psv": 0.00,
							"noGravado": 0.00,
							"ivaItem" : 0.00
						},
						
						{
							"numItem": 2,
							"tipoItem": 1,
							"codigo": "50296",
							"codTributo": null,
							"numeroDocumento": null,
							"descripcion": "CLORFENIRAMINA PL 10 MG SOL INY AMP X 1 ML",
							"cantidad": 100.000,
							"uniMedida": 59,
							"precioUni": 0.55000000,
							"montoDescu": 0.00000000,
							"ventaNoSuj": 0.00,
							"ventaExenta": 0.00000000,
							"ventaGravada": 55.00000000,
							"noGravado": 0.00,
							"tributos": [
								"20"
							],
							"psv": 0.00
						}
					],
					"resumen": {
						"totalNoSuj": 0.00,
						"totalExenta": 0.00,
						"totalGravada": 601.62,
						"subTotalVentas": 601.62,
						"descuNoSuj": 0.00,
						"descuExenta": 0.00,
						"descuGravada": 0.00,
						"porcentajeDescuento": 0.00,
						"totalDescu": 6.27,
						"tributos": [
							{
								"codigo": "20",
								"descripcion": "Impuesto al Valor Agregado 13%",
								"valor": 78.21
							}
						],
						"subTotal": 601.62,
						"ivaRete1": 0.00,
						"reteRenta": 0.00,
						"montoTotalOperacion": 679.84,
						"totalNoGravado": 0.00,
						"totalPagar": 679.84,
						"totalLetras": "SEISCIENTOS SETENTA Y NUEVE 84/100 DOLARES",
						"totalIva": 78.21,
						"saldoFavor": 0.00,
						"condicionOperacion": 1,
						"pagos": [
							{
								"codigo": "01",
								"montoPago": 679.84,
								"referencia": null,
								"plazo": null,
								"periodo": null
							}
						],
						"numPagoElectronico": null
					},
					"extension": null,
					"apendice": null
				}
		}';
 */

// Datos para enviar a la API firmardocumento
	$data = [
		"contentType" => "application/JSON",
		"nit" => "06142405161029", // Nit del contribuyente
		"activo" => true, // Contribuyente activo
		"passwordPri" => "UnionMedica_25", // Contraseña de la llave privada
		"dteJson" => [ // Documento JSON del DTE
			"identificacion" => [
				"version" => 1,
				"ambiente" => "00",
				"tipoDte" => "01",
				"numeroControl" => "DTE-01-12345678-000000000000001",
				"codigoGeneracion" => "FF54E9DB-79C3-42CE-B432-EC522C97EFB9",
				"tipoModelo" => 1,
				"tipoOperacion" => 1,
				"fecEmi" => "2021-10-27",
				"horEmi" => "15:58:30",
				"tipoMoneda" => "USD"
			],
			"emisor" => [
				"nit" => "06142407961057",
				"nrc" => "1208778",
				"nombre" => "HELIOS INTERNACIONAL, S.A. DE C.V.",
				"direccion" => [
					"departamento" => "06",
					"municipio" => "21",
					"complemento" => "10a CALLE ORIENTE Y 8a AV. SUR BO. LA VEGA  # 470"
				],
				"telefono" => "22810222",
				"correo" => "info@heliosdrogueria.com"
			],
			"receptor" => [
				"tipoDocumento" => "13",
				"numDocumento" => "00000000-0",
				"nombre" => "Juan Perez"
			],
			"cuerpoDocumento" => [
				[
					"numItem" => 1,
					"descripcion" => "BIO-MIKIN 500 MG SOL INY VIAL X 2ML",
					"cantidad" => 50,
					"precioUni" => 3.0194,
					"ventaGravada" => 150.97
				]
			],
			"resumen" => [
				"totalGravada" => 150.97,
				"montoTotalOperacion" => 169.6,
				"totalIva" => 18.63
			]
		]
	];

	// Convertir a JSON
	$jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);



		$curl = new Curl();
		// Configurar los encabezados de la solicitud
		$curl->setHeader('Content-Type', 'application/json');  // Indicamos que el contenido es JSON
		$curl->setHeader('Accept', 'application/json');         // Aceptamos respuestas en formato JSON
        $resp = $curl->post('http://192.168.1.92:8113/firmardocumento/', $jsonData); //Cambiar a la direccion en la nube
     	print_r($resp->response);

	}
	
}

