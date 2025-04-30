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

// Libreria para impresora termica
	use Mike42\Escpos\Printer;
	use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
	use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
	use Mike42\Escpos\PrintConnectors\FilePrintConnector;
	use Mike42\Escpos\EscposImage;
// Libreria para impresora termica

class Gastos extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Gastos_Model");
		$this->load->model("Proveedor_Model");
		$this->load->model("Medico_Model");
		$this->load->model("Externos_Model");
		$this->load->model("Pendientes_Model");
	}

	public function index(){
		$this->load->view('Base/header');
        $data['clasificaciones'] = $this->Gastos_Model->obtenerClasificacion(); 
        $data['cuentas'] = $this->Gastos_Model->obtenerCuentas(); 
		$this->load->view('Gastos/index', $data);
		$this->load->view('Base/footer');
	}

    public function guardar_cuenta(){
        $datos = $this->input->post();
        $bool = $this->Gastos_Model->guardarCuenta($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos de la cuenta se guardaron con exito!");
			redirect(base_url()."Gastos/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos de la cuenta!");
			redirect(base_url()."Gastos/");
		}
        //var_dump($datos);
    }

    public function actualizar_cuenta(){
        $datos = $this->input->post();
        $bool = $this->Gastos_Model->actualizarCuenta($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos de la cuenta se actualizaron con exito!");
			redirect(base_url()."Gastos/");
		}else{
			$this->session->set_flashdata("error","Error al actualizar los datos de la cuenta!");
			redirect(base_url()."Gastos/");
		}
        //var_dump($datos);
    }

    public function eliminar_cuenta(){
        $id = $this->input->post("idEliminar");
        $bool = $this->Gastos_Model->eliminarCuenta($id);
		if($bool){
			$this->session->set_flashdata("exito","Los datos de la cuenta se eliminaron con exito!");
			redirect(base_url()."Gastos/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar los datos de la cuenta!");
			redirect(base_url()."Gastos/");
		}
        //var_dump($datos);
    }

    public function gastos_excel($i = null, $f = null){
		/* Obteniendo fechas localmente */
		/* $anio = date("Y");
				$mes = date("m");
				$i = $anio."-".$mes."-01";
				$f = $anio."-".$mes."-31";
		*/
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Detalle');
		$sheet->setCellValue('B1', 'Monto');
		$sheet->setCellValue('C1', 'Proveedor');
		$sheet->setCellValue('D1', 'Tipo de gasto');
		$sheet->setCellValue('E1', 'Forma de pago');
		$sheet->setCellValue('F1', 'Cheque');
		$sheet->setCellValue('G1', 'Banco');
		$sheet->setCellValue('H1', 'Fecha');
		$sheet->setCellValue('I1', 'Descripción');
			
		$datos = $this->Gastos_Model->obtenerGastos($i, $f);
		$number = 1;
		$flag = 2;
		$tipoEntidad = "";
        $proveedor = "";
		foreach($datos as $d){
			if ($d->entidadGasto == 1) {
				$tipoEntidad = "Médico";
				$medico = $this->Externos_Model->detalleExternoMedico($d->idProveedorGasto);
				$proveedor = $medico->nombreMedico;
				
			}else{
				$tipoEntidad = "Otros proveedores";
				if($d->flagGasto == 1){
					$medico = $this->Pendientes_Model->rowProveedor($d->idProveedorGasto);
					$proveedor = $medico->empresaProveedor;
				}else{
					$medico = $this->Externos_Model->detalleExternoProveedor2($d->idProveedorGasto);
					$proveedor = $medico->empresaProveedor;
				}
			}

			$sheet->setCellValue('A'.$flag, $d->nombreCuenta);
			$sheet->setCellValue('B'.$flag, $d->montoGasto);
			$sheet->setCellValue('C'.$flag, $proveedor);
			$sheet->setCellValue('D'.$flag, $d->nombreTipoGasto);
			switch ($d->pagoGasto) {
				case '1':
					$sheet->setCellValue('E'.$flag, "Efectivo");
					break;
				case '2':
					$sheet->setCellValue('E'.$flag, "Cheque");
					break;
				case '3':
					$sheet->setCellValue('E'.$flag, "Caja chica");
					break;
				case '4':
					$sheet->setCellValue('E'.$flag, "Cargo a cuenta");
					break;
				
				default:
					$sheet->setCellValue('E'.$flag, "Efectivo");
					break;
			}
			$sheet->setCellValue('F'.$flag, $d->numeroGasto);
			$sheet->setCellValue('G'.$flag, $d->bancoGasto);
			$sheet->setCellValue('H'.$flag, $d->fechaGasto);
			$sheet->setCellValue('I'.$flag, strip_tags($d->descripcionGasto));
				
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
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:H10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:I'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:I1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:I'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
		$filename = 'listado_de_gastos_'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function gastos_excel_fecha($f){
		$arreglo =  explode("-", $f);
		/* Obteniendo fechas localmente */
		$anio = $arreglo[0];
		$mes = $arreglo[1];
		$i = $anio."-".$mes."-01";
		$f = $anio."-".$mes."-31";

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Detalle');
		$sheet->setCellValue('B1', 'Monto');
		$sheet->setCellValue('C1', 'Proveedor');
		$sheet->setCellValue('D1', 'Tipo de gasto');
		$sheet->setCellValue('E1', 'Descripción');
			
		$datos = $this->Gastos_Model->obtenerGastos($i, $f);
		$number = 1;
		$flag = 2;
		$tipoEntidad = "";
        $proveedor = "";
		foreach($datos as $d){
			if ($d->entidadGasto == 1) {
				$tipoEntidad = "Médico";
				$medico = $this->Externos_Model->detalleExternoMedico($d->idProveedorGasto);
				$proveedor = $medico->nombreMedico;
				
			}else{
				$tipoEntidad = "Otros proveedores";
				if($d->flagGasto == 1){
					$medico = $this->Pendientes_Model->rowProveedor($d->idProveedorGasto);
					$proveedor = $medico->empresaProveedor;
				}else{
					$medico = $this->Externos_Model->detalleExternoProveedor2($d->idProveedorGasto);
					$proveedor = $medico->empresaProveedor;
				}
			}

			$sheet->setCellValue('A'.$flag, $d->nombreCuenta);
			$sheet->setCellValue('B'.$flag, $d->montoGasto);
			$sheet->setCellValue('C'.$flag, $proveedor);
			$sheet->setCellValue('D'.$flag, $d->nombreTipoGasto);
			$sheet->setCellValue('E'.$flag, strip_tags($d->descripcionGasto));
				
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
		//$sheet->getStyle('A1:H10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:E'.$flag)->getFont()->setSize(12);
		$sheet->getStyle('A1:E1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1:E'.$flag)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_de_gastos_'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		
		
	}

    public function control_gastos(){
		$meses = array('enero','febrero','marzo','abril','mayo','junio','julio', 'agosto','septiembre','octubre','noviembre','diciembre');
		$data['proveedores'] = $this->Proveedor_Model->obtenerProveedores(); 
		$data['cuentas'] = $this->Gastos_Model->obtenerCuentas();
		$data['categorias'] = $this->Gastos_Model->obtenerCategorias();
		$data['tipoGasto'] = $this->Gastos_Model->obtenertipoGasto();
		$data['bancos'] = $this->Gastos_Model->obtenerBancos();
		$data['cuentasBanco'] = $this->Gastos_Model->obtenerCuentasBancos();

		// Obtener los gastos efectuados
		// Logica para el mes
			$anio = date("Y");
			$mes = date("m");
			$i = $anio."-".$mes."-01";
			$f = $anio."-".$mes."-31";
			if($mes < 10){
				$flagMes = substr($mes-1, -1);
			}else{
				$flagMes = $mes-1;
			}
			$data["mes"] = $meses[$flagMes];
			$data['inicio'] = $i;
			$data['fin'] = $f;

		if($this->session->userdata('acceso_h') == 10){
			$data['listaGastos'] = $this->Gastos_Model->obtenerGastos($i, $f, $this->session->userdata('acceso_h'));

		}else{
			$data['listaGastos'] = $this->Gastos_Model->obtenerGastos($i, $f, 0);
		}
		
		$codigo = $this->Gastos_Model->codigoGasto(); // Ultimo codigo de hoja
		if($codigo == NULL ){
			$codigo = 1000000;
		}else{
			$codigo = $codigo->codigo +1;
		}
		$data["cod"] = $codigo;
		// Fin gastos efectuados

		$this->load->view('Base/header');
		$this->load->view('Gastos/control_gastos', $data);
		$this->load->view('Base/footer');

		// echo json_encode($data['listaGastos']);
	}

	public function control_gastos_fecha(){
		echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
			$meses = array('enero','febrero','marzo','abril','mayo','junio','julio', 'agosto','septiembre','octubre','noviembre','diciembre');
			$data['proveedores'] = $this->Proveedor_Model->obtenerProveedores(); 
			$data['cuentas'] = $this->Gastos_Model->obtenerCuentas();
			$data['tipoGasto'] = $this->Gastos_Model->obtenertipoGasto();
			
			// Obtener los gastos efectuados
			// Logica para el mes
				$anio = $datos ["anioReporte"];
				$mes = $datos ["mesReporte"];
				$i = $anio."-".$mes."-01";
				$f = $anio."-".$mes."-31";
				if($mes < 10){
					$flagMes = substr($mes-1, -1);
				}else{
					$flagMes = $mes-1;
				}
				$data["mes"] = $meses[$flagMes];
				$data["mess"] = $flagMes;
				$data["anio"] = $anio;

				$data["meses"] = $meses;
			

			if($this->session->userdata('acceso_h') == 10){
				$data['listaGastos'] = $this->Gastos_Model->obtenerGastos($i, $f, $this->session->userdata('acceso_h'));
	
			}else{
				$data['listaGastos'] = $this->Gastos_Model->obtenerGastos($i, $f, 0);
			}

			$data['inicio'] = $i;
			$data['fin'] = $f;
			// Fin gastos efectuados
			
			$this->load->view('Base/header');
			$this->load->view('Gastos/control_gastos_fecha', $data);
			$this->load->view('Base/footer'); 
			// echo json_encode($data['fin']);
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Gastos/control_gastos");
		}
	}

	public function guardar_gasto(){
		echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
		$datos = $this->input->post();  // Valores para el gasto
		$datos["descripcionGasto"] = trim($datos["descripcionGasto"]);
		$c = $datos["codigoGasto"];
		$codigo = $this->Gastos_Model->buscarCodigo($c);
		
		if(!is_null($codigo)){
			$ultimoCodigo = $this->Gastos_Model->ultimoGasto(); // Ultimo codigo de hoja
			$uc = $ultimoCodigo->ultimo + 1;
			$datos["codigoGasto"] = "$uc";
		}

		if(sizeof($datos) > 0){
			// Detalle
			$datos["efectuoGasto"] = $this->session->userdata("empleado_h");
			$datos["accesoGasto"] = $this->session->userdata('acceso_h');
			$entidadGasto = $datos["entidadGasto"];
			$recibo = array();
			// Datos del recibo
				$dato = $this->Gastos_Model->obtenerEntidadGasto($datos["idProveedorGasto"], $datos ["entidadGasto"]);
				$recibo["fecha"] = $datos["fechaGasto"];
				$recibo["codigo"] = $datos["codigoGasto"];
				$recibo["entregado"] = $datos["entregadoGasto"];
				$recibo["proveedor"] = $dato->proveedor;
				$recibo["concepto"] = $datos["descripcionGasto"];
				$recibo["total"] = $datos["montoGasto"];
				$recibo["forma"] = $datos["pagoGasto"];
				if(isset($datos["chequeGasto"])){
					$recibo["cheque"] = $datos["chequeGasto"];
				}else{
					$recibo["cheque"] = "";
				}
				$recibo["efectuoGasto"] = $datos["efectuoGasto"];
				//$this->recibo_gasto($recibo);
			// Fin datos del recibo


			if(isset($datos["chequeGasto"])){
				// Cuenta por pagar
					$porPagar["idProveedor"] = $datos["idProveedorGasto"]; 
					$porPagar["fechaCuenta"] = $datos["fechaGasto"];
					// Para cuando es un proveedor
					if($datos["entidadGasto"] == 1){
						$porPagar["nrcCuenta"] = "---"; 
					}else{
						$prov = $this->Gastos_Model->obtenerProveedor($datos["idProveedorGasto"]);
						$porPagar["nrcCuenta"] = $prov->nrcProveedor; 
					}
					
					// Fin para cuando es un proveedor

					$porPagar["facturaCuenta"] = "---"; 
					$porPagar["plazoCuenta"] = "30"; 
					$porPagar["subtotalCuenta"] = $datos["montoGasto"]; 
					$porPagar["ivaCuenta"] = "0"; 
					$porPagar["perivaCuenta"] = "0";
					$porPagar["notaCredito"] = "0";
					$porPagar["totalCuenta"] = $datos["montoGasto"]; 
					$porPagar["estadoCuentaPagar"] = "1";
					if($datos["entidadGasto"] == 1){
						$porPagar["pivote"] = "1"; 
					}else{
						$porPagar["pivote"] = "0"; 
					}

					$cuentaXPagar = $this->Pendientes_Model->guardarCuentaPagar($porPagar);  //Guardando cuenta por pagar

					// Ordenando datos del gasto
						$gasto["tipoGasto"] = $datos["tipoGasto"];
						$gasto["montoGasto"] = $datos["montoGasto"];
						$gasto["entregadoGasto"] = $datos["entregadoGasto"];
						$gasto["idCuentaGasto"] = $datos["idCuentaGasto"];
						$gasto["fechaGasto"] = $datos["fechaGasto"];
						$gasto["categoriaGasto"] = $datos["areaGasto"];
						$gasto["entidadGasto"] = $datos["entidadGasto"];
						$gasto["idProveedorGasto"] = $datos["idProveedorGasto"];
						$gasto["pagoGasto"] = $datos["pagoGasto"];
						$gasto["numeroGasto"] = $datos["chequeGasto"];
						$gasto["bancoGasto"] = $datos["bancoGasto"];
						$gasto["cuentaGasto"] = $datos["cuentaGasto"];
						$gasto["descripcionGasto"] = $datos["descripcionGasto"];
						$gasto["codigoGasto"] = $datos["codigoGasto"];
						$gasto["flagGasto"] = '1';
						$gasto["efectuoGasto"] = $datos["efectuoGasto"];
					// Fin datos del gasto

					$data["gasto"] = $gasto;

					$respGasto = array(
						"cuenta" => $cuentaXPagar,
					);
					$data["cuentas"] = $respGasto;
					

					$id = $this->Pendientes_Model->saldarCuentas($data); // Retorna el Id del gasto.
					if($id > 0){
						$url2 = "http://192.168.1.92/sirius/Home/imprimir_cheque/";
						$this->Gastos_Model->actualizarCuentaXPagar($cuentaXPagar, $id);
						$this->session->set_flashdata("exito","Los datos se guardaron con exito'");
						// redirect(base_url()."Gastos/imprimir_recibo/".$id."/".$datos["idProveedorGasto"]."/".$entidadGasto."/1");
						redirect($url2.$id."/".$datos["idProveedorGasto"]."/".$entidadGasto."/1");
					}else{
						$this->session->set_flashdata("error","Error al guardar los datos");
						redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
					}

				//Fin cuenta por pagar
			}else{
				$bool = $this->Gastos_Model->guardarGasto($datos);
				if($bool){
					//$this->session->set_flashdata("exito","El gasto se registro con exito!");
					$this->recibo_gasto($recibo);
					//redirect(base_url()."Gastos/recibo_gasto");
				}else{
					$this->session->set_flashdata("error","Error al registrar el gasto!");
					redirect(base_url()."Gastos/control_gastos");
				}
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Gastos/control_gastos");
		}

		
	}

	// Funcion para obtener de forma asincrona el tipo de entidad
	public function tipo_entidad(){
        if($this->input->is_ajax_request())
		{
            $id =$this->input->get("id");
            if($id =="1"){
                $datos = $this->Medico_Model->obtenerMedicos();
            }
            else{
                $datos = $this->Proveedor_Model->obtenerProveedores();
            }
			echo json_encode($datos);
		}
		else
		{
			echo "Error...";
		}
    }

	public function recibo_gasto($datos){
		// Recibo de gastos
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 30,
				'margin_bottom' => 78,
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
			$html = $this->load->view('Gastos/recibo_gasto', $datos ,true); // Cargando hoja de estilos
			$mpdf->SetHTMLHeader('
				<div class="cabecera" style="font-family: Times New Roman">
					<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
					<div class="title_cabecera">
						<h4>HOSPITAL ORELLANA, USULUTAN</h4>
						<h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5>
					</div>
				</div>
			');
			
			$mpdf->WriteHTML($html);
			$mpdf->Output('recibro_de_cobro.pdf', 'I');
		// Recibo de gastos
	}

	public function imprimir_recibo($gasto, $proveedor, $entidad, $flag){
		$datos = $this->Gastos_Model->obtenerGasto($gasto);
		$dato = $this->Gastos_Model->obtenerEntidadGasto($proveedor, $entidad);
		$cuenta = $this->Gastos_Model->obtenerCuentaBanco($datos->cuenta);
		if($flag == 1){
			$recibo["cuentasPagar"] = $this->Pendientes_Model->detalleGasto($gasto);
		}

		$recibo["fecha"] = $datos->fechaGasto;
		$recibo["codigo"] = $datos->codigoGasto;
		$recibo["entregado"] = $datos->entregadoGasto;
		$recibo["proveedor"] = $dato->proveedor;
		$recibo["forma"] = $datos->pagoGasto;
		$recibo["cheque"] = $datos->numeroGasto;
		$recibo["concepto"] = $datos->descripcionGasto;
		$recibo["total"] = $datos->montoGasto;
		$recibo["efectuoGasto"] = $datos->efectuoGasto;
		$recibo["bancoGasto"] = $datos->bancoGasto;
		$recibo["cuentaGasto"] = $cuenta->numeroCuenta;
		$recibo["flag"] = $flag;

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
			$html = $this->load->view('Gastos/recibo_gasto', $recibo ,true); // Cargando hoja de estilos
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
										&nbsp;&nbsp;&nbsp;&nbsp;'.$datos->efectuoGasto.'&nbsp;&nbsp;&nbsp;&nbsp;
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

	public function editar_gasto(){
		/* '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>'; */
		$datos = $this->input->post();
		if(sizeof($datos) > 0){
			// Detalle
			$idGasto =  $datos["idGasto"];
			$recibo = array();
			// Obteniendo nombre del Médico o proveedor
				$dato = $this->Gastos_Model->obtenerEntidadGasto($datos["idProveedorGasto"], $datos ["entidadGasto"]);
				$recibo["fecha"] = $datos["fechaGasto"];
				$recibo["codigo"] = $datos["codigoGasto"];
				$recibo["efectuoGasto"] = $datos["entregadoGasto"];
				$recibo["entregado"] = $datos["entregadoGasto"];
				$recibo["proveedor"] = $dato->proveedor;
				$recibo["concepto"] = $datos["descripcionGasto"];
				$recibo["total"] = $datos["montoGasto"];
				$recibo["forma"] = $datos["pagoGasto"];
				$recibo["efectuoGasto"] = $datos["efectuoGasto"];

				$recibo["bancoGasto"] = $datos["bancoGasto"];
				$recibo["numeroGasto"] = $datos["numeroGasto"];
				$recibo["cuentaGasto"] = $datos["cuentaGasto"];
				
			unset($datos["codigoGasto"]);
			unset($datos["efectuoGasto"]);
			if($datos["numeroGasto"] == ""){
				unset($datos["numeroGasto"]);
				unset($datos["bancoGasto"]);
				unset($datos["cuentaGasto"]);
			}else{
				$recibo["cheque"] = $datos["numeroGasto"];
			}
			$bool = $this->Gastos_Model->actualizarGastos($datos);
			if($bool){
				// $this->Gastos_Model->actualizarCuentaPorPagar($idGasto, $datos["montoGasto"]);
				$this->recibo_gasto($recibo);
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos de la cuenta!");
				redirect(base_url()."Gastos/control_gastos");
			}
		}else{
			$this->session->set_flashdata("error","No se permite el reenvio de datos");
			redirect(base_url()."Gastos/control_gastos");
		}
		// echo json_encode($datos);
	}

	public function eliminar_gasto(){
		if($this->input->is_ajax_request()){
			// Datos para eliminar medicamento 
				$gasto = $this->input->post();
				echo json_encode($gasto);
			
			// Ejecutando consultas
				$bool = $this->Gastos_Model->eliminarGasto($gasto);
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

	public function test(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Propiedades para centrar elemento
		$centrar = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
		];
		$sheet->setCellValue('A1', '#');
		$sheet->setCellValue('B1', 'Cuenta');
		$sheet->setCellValue('C1', 'Categoria');
		$sheet->setCellValue('D1', 'Gasto');

		$datos = $this->Gastos_Model->test();
		$flag = 2;
		$number = 1;
		foreach ($datos as $d) {
			// echo " |".$d->clasificacionCuenta." ".$d->nombreCuenta." --- ".$d->nombreCG."<br>";
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->nombreCuenta);
			$sheet->setCellValue('C'.$flag, $d->nombreCG);
			$sheet->setCellValue('D'.$flag, " ");
			$flag++;
			$number++;
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_gastos_'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

	// Dashboard -> detalle de cuentas
		public function detalle_cuentas_gastos($i, $f){
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			$data["mes"] = $meses[date("m") -1];
			/* $i = date("Y")."-".date("m")."-01";
			$f = date("Y")."-".date("m")."-31"; */
			// $data["cuentas"] = $this->Gastos_Model->obtenerGastos($i, $f);
			$data["cuentas"] = $this->Gastos_Model->resumenCuentas($i, $f);
			$this->load->view("Base/header");
			$this->load->view("Usuarios/detalle_cuentas", $data);
			$this->load->view("Base/footer");
			// var_dump($data["cuentas"]);
			
			
		}

		public function buscar_detalle_gasto(){
			if($this->input->is_ajax_request()){
				$data["id"] = $this->input->post("idCuenta");
				$data["i"] = date("Y")."-".date("m")."-01";
				$data["f"] = date("Y")."-".date("m")."-31";
				$detalle = $this->Gastos_Model->obtenerDetalleCuenta($data);
				echo json_encode($detalle);
			}else{
				echo "Error...";
			}
		}
	// Fin dashboard


	// Metodos para cheques
		public function guardar_cheque(){
			echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
			$datos = $this->input->post();
			$bancoCuenta = $this->Gastos_Model->obtenerCuentaBanco($datos["cuentaGasto"]);
			$datos["bancoGasto"] = $bancoCuenta->nombreBanco;
			$datos["gastoCuenta"] = $bancoCuenta->numeroCuenta;
			
			
				// Validar si cuenta esta autorizada para imprimir cheques
					$cuenta = $this->Gastos_Model->obtenerCuentaBanco($datos["cuentaGasto"]);
				// Validar si cuenta esta autorizada para imprimir cheques

			$datos["descripcionGasto"] = trim($datos["descripcionGasto"]);
			$c = $datos["codigoGasto"];
			$codigo = $this->Gastos_Model->buscarCodigo($c);
			
			if(!is_null($codigo)){
				$ultimoCodigo = $this->Gastos_Model->ultimoGasto(); // Ultimo codigo de hoja
				$uc = $ultimoCodigo->ultimo + 1;
				$datos["codigoGasto"] = "$uc";
			}

			$datos["efectuoGasto"] = $this->session->userdata("empleado_h");
			$entidadGasto = $datos["entidadGasto"];
			$recibo = array();

			// Datos del recibo
				$dato = $this->Gastos_Model->obtenerEntidadGasto($datos["idProveedorGasto"], $datos ["entidadGasto"]);
				$recibo["fecha"] = $datos["fechaGasto"];
				$recibo["codigo"] = $datos["codigoGasto"];
				$recibo["entregado"] = $datos["entregadoGasto"];
				$recibo["proveedor"] = $dato->proveedor;
				$recibo["concepto"] = $datos["descripcionGasto"];
				$recibo["total"] = $datos["montoGasto"];
				$recibo["forma"] = $datos["pagoGasto"];
				if(isset($datos["chequeGasto"])){
					$recibo["cheque"] = $datos["chequeGasto"];
				}else{
					$recibo["cheque"] = "";
				}
				$recibo["efectuoGasto"] = $datos["efectuoGasto"];
				//$this->recibo_gasto($recibo);
			// Fin datos del recibo

			// Cuenta por pagar
				$porPagar["idProveedor"] = $datos["idProveedorGasto"]; 
				$porPagar["fechaCuenta"] = $datos["fechaGasto"];
				// Para cuando es un proveedor
				if($datos["entidadGasto"] == 1){
					$porPagar["nrcCuenta"] = "---"; 
				}else{
					$prov = $this->Gastos_Model->obtenerProveedor($datos["idProveedorGasto"]);
					$porPagar["nrcCuenta"] = $prov->nrcProveedor; 
				}
				
				// Fin para cuando es un proveedor

				$porPagar["facturaCuenta"] = "---"; 
				$porPagar["plazoCuenta"] = "30"; 
				$porPagar["subtotalCuenta"] = $datos["montoGasto"]; 
				$porPagar["ivaCuenta"] = "0"; 
				$porPagar["perivaCuenta"] = "0";
				$porPagar["notaCredito"] = "0";
				$porPagar["totalCuenta"] = $datos["montoGasto"]; 
				$porPagar["estadoCuentaPagar"] = "1";
				if($datos["entidadGasto"] == 1){
					$porPagar["pivote"] = "1"; 
				}else{
					$porPagar["pivote"] = "0"; 
				}

				$cuentaXPagar = $this->Pendientes_Model->guardarCuentaPagar($porPagar);  //Guardando cuenta por pagar
			// Cuenta por pagar

			// Ordenando datos del gasto
				$gasto["tipoGasto"] = $datos["tipoGasto"];
				$gasto["montoGasto"] = $datos["montoGasto"];
				$gasto["entregadoGasto"] = $datos["entregadoGasto"];
				$gasto["idCuentaGasto"] = $datos["idCuentaGasto"];
				$gasto["fechaGasto"] = $datos["fechaGasto"];
				$gasto["categoriaGasto"] = $datos["areaGasto"];
				$gasto["entidadGasto"] = $datos["entidadGasto"];
				$gasto["idProveedorGasto"] = $datos["idProveedorGasto"];
				$gasto["pagoGasto"] = $datos["pagoGasto"];
				$gasto["numeroGasto"] = $datos["chequeGasto"];
				$gasto["bancoGasto"] = $datos["bancoGasto"];
				$gasto["cuentaGasto"] = $datos["cuentaGasto"];
				$gasto["descripcionGasto"] = $datos["descripcionGasto"];
				$gasto["codigoGasto"] = $datos["codigoGasto"];
				$gasto["flagGasto"] = '1';
				$gasto["efectuoGasto"] = $datos["efectuoGasto"];
				$gasto["banco"] = $bancoCuenta->idBanco;
				$gasto["cuenta"] = $bancoCuenta->idCuenta;
				$gasto["accesoGasto"] = $this->session->userdata('acceso_h');
			// Fin datos del gasto
			
			// Asignando variables
				$data["gasto"] = $gasto;
				$respGasto = array(
					"cuenta" => $cuentaXPagar,
				);
				$data["cuentas"] = $respGasto;
			// Asignando variables

			
			$id = $this->Pendientes_Model->saldarCuentas($data); // Retorna el Id del gasto.

			if($id > 0){
				$this->Gastos_Model->actualizarCuentaXPagar($cuentaXPagar, $id);
				if($cuenta->imprimir == 1){
					/* $ip = $_SERVER['REMOTE_ADDR']; // IP de la mquina actual
					$url2 = "http://".$ip."/"."sirius/Home/imprimir_cheque/";
					redirect($url2.$id."/".$datos["idProveedorGasto"]."/".$entidadGasto."/1"); */
					$this->session->set_flashdata("exito","Los datos se guardaron con exito'");
					redirect(base_url()."Gastos/imprimir_recibo/".$id."/".$datos["idProveedorGasto"]."/".$entidadGasto."/1");

				}else{
					$this->session->set_flashdata("exito","Los datos se guardaron con exito'");
					redirect(base_url()."Gastos/imprimir_recibo/".$id."/".$datos["idProveedorGasto"]."/".$entidadGasto."/1");
		
				}
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos");
				redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
			}
		//Fin cuenta por pagar
	}
	// Metodos para cheques
	
}