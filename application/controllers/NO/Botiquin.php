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

class Botiquin extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Botiquin_Model");
		$this->load->model("Proveedor_Model");
		$this->load->model("Usuarios_Model");
	}

	public function index(){
		// Obteniendo el ultimo codigo
			$ultimoCodigo = $this->Botiquin_Model->ultimoCodigo();
			$codigo = $ultimoCodigo->codigo;
			if($codigo  == null){ 
				$codigo = 1000; 
			}else{ 
				$codigo = $codigo + 1;
			}
		// Fin del obtener ultimo codigo

		$data["medicamentos"] = $this->Botiquin_Model->medicamentosBotiquin();
		$data["clasificaciones"] = $this->Botiquin_Model->obtenerClasificacionMedicamentos();
		$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
		$data["cod"] = $codigo;

		$this->load->view('Base/header');
		$this->load->view('Botiquin/lista_medicamentos', $data);
		$this->load->view('Base/footer');
		// echo json_encode($data["medicamentos"]);
    }
    
    public function agregar_medicamento(){
		$this->load->view('Base/header');
		
		$proveedores = $this->Proveedor_Model->obtenerProveedores();
		$medicamentos = $this->Botiquin_Model->obtenerMedicamentos();
		$data = array('proveedores' => $proveedores, 'medicamentos' => $medicamentos);
		$this->load->view('Botiquin/agregar_medicamento', $data);
		$this->load->view('Base/footer');
	}

	public function guardar_medicamento(){
		$datos = $this->input->post();

		// Ordenando medidas
			if(isset($datos["nombreMedida"])){
				// Creando Json de medidas
					// Crear un arreglo combinado
					$listaMedidas = array();
					// Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
					$numElementos = count($datos["nombreMedida"]);
					// Iterar sobre la longitud de las matrices y combinar los datos
					for ($i = 0; $i < $numElementos; $i++) {
						// Crear un arreglo asociativo para cada conjunto de datos
						$objeto = array(
							"nombreMedida" => $datos["nombreMedida"][$i],
							"cantidad" => $datos["cantidad"][$i],
							"precio" => $datos["precio"][$i]
						);
						// Agregar el arreglo al arreglo combinado
						array_push($listaMedidas, $objeto);
					}
					unset($datos["nombreMedida"], $datos["cantidad"], $datos["precio"]);
					$datos["medidas"] = json_encode($listaMedidas);
				// Creando Json de medidas

			}else{
				$datos["medidas"] = "";
			}

		// Ordenando medidas
		
		$bool = $this->Botiquin_Model->guardarMedicamento($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Botiquin/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos!");
			redirect(base_url()."Botiquin/");
		}

		// echo json_encode($datos);
	}

	public function actualizar_medicamento(){
		$datosA = $this->input->post();
		
		$datos["codigoMedicamento"]  = $datosA["codigoMedicamentoA"];
		//$datos["registroMedicamento"]  = $datosA["registroMedicamentoA"];
		$datos["nombreMedicamento"]  = $datosA["nombreMedicamentoA"];
		$datos["idProveedorMedicamento"]  = $datosA["idProveedorMedicamentoA"];
		$datos["precioCMedicamento"]  = $datosA["precioCMedicamentoA"];
		$datos["precioVMedicamento"]  = $datosA["precioVMedicamentoA"];
		$datos["feriadoMedicamento"]  = $datosA["precioVMedicamentoA"];
		$datos["tipoMedicamento"]  = $datosA["tipoMedicamentoA"];
		$datos["fechaVencimiento"]  = $datosA["fechaVencimiento"];
		$datos["idClasificacionMedicamento"]  = $datosA["idClasificacionMedicamentoA"];
		$datos["idMedicamento"]  = $datosA["idMedicamentoA"];
		$bool = $this->Botiquin_Model->actualizarMedicamento($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Botiquin/");
		}else{
			$this->session->set_flashdata("error","Error al actualizar los datos!");
			redirect(base_url()."Botiquin/");
		}
		
		// echo json_encode($datos);
	}

	public function eliminar_medicamento(){
		$datos = $this->input->post();
		$bool = $this->Botiquin_Model->eliminarMedicamento($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Botiquin/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar los datos!");
			redirect(base_url()."Botiquin/");
		}
		// echo json_encode($datos);
	}

	public function medicamentos_excel(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Código');
		$sheet->setCellValue('B1', 'Nombre');
		$sheet->setCellValue('C1', 'Proveedor');
		$sheet->setCellValue('D1', 'Precio compra');
		$sheet->setCellValue('E1', 'Tipo');
		$sheet->setCellValue('F1', 'Clasificación');
		$sheet->setCellValue('G1', 'Precio venta');
		$sheet->setCellValue('H1', 'Cantidad vendida');
		$sheet->setCellValue('I1', 'Total vendido ($)');
		$sheet->setCellValue('J1', 'Cantidad en stock');
		$sheet->setCellValue('K1', 'Total stock ($)');
			
		$datos = $this->Botiquin_Model->obtenerMedicamentos();

		$number = 1;
		$flag = 2;
		$totalVendido = 0;
		$totalStock = 0;
		foreach($datos as $d){
			if($d->tipoMedicamento == "Medicamentos" || $d->tipoMedicamento == "Materiales médicos"){

			
				$totalVendido = ($d->usadosMedicamento * $d->precioVMedicamento);
				$totalStock = ($d->stockMedicamento * $d->precioVMedicamento );

				$sheet->setCellValue('A'.$flag, $d->codigoMedicamento);
				$sheet->setCellValue('B'.$flag, $d->nombreMedicamento);
				$sheet->setCellValue('C'.$flag, $d->empresaProveedor);
				$sheet->setCellValue('D'.$flag, $d->precioCMedicamento);
				$sheet->setCellValue('E'.$flag, $d->tipoMedicamento);
				$sheet->setCellValue('F'.$flag, $d->nombreClasificacionMedicamento);
				$sheet->setCellValue('G'.$flag, $d->precioVMedicamento);
				$sheet->setCellValue('H'.$flag, $d->usadosMedicamento);
				$sheet->setCellValue('I'.$flag, $totalVendido);
				
				if($d->stockMedicamento == 0){
					$sheet->setCellValue('J'.$flag, '0');
				}else{
					if($d->stockMedicamento < 0){
						$sheet->setCellValue('J'.$flag, '0' );
					}else{
						$sheet->setCellValue('J'.$flag, $d->stockMedicamento);
					}
						
				}

				if($d->stockMedicamento < 0){
					$sheet->setCellValue('K'.$flag, '0');
				}else{
					$sheet->setCellValue('K'.$flag, $totalStock );
				}
				
				
					
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
		$sheet->getStyle('A1:K1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:L10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:K'.$flag)->getFont()->setSize(12);
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
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_medicamentos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	/* public function guardar_compra(){
		$datos = $this->input->post();

		// Datos para la factura
		$datosFactura["tipoFactura"] = $datos["tipoFactura"];
		$datosFactura["documentoFactura"] = $datos["documentoFactura"];
		$datosFactura["numeroFactura"] = $datos["numeroFactura"];
		$datosFactura["responsableFactura"] = $datos["responsableFactura"];
		$datosFactura["idProveedor"] = $datos["idProveedor"];
		$datosFactura["fechaFactura"] = $datos["fechaFactura"];
		$datosFactura["plazoFactura"] = $datos["plazoFactura"];
		$datosFactura["descripcionFactura"] = $datos["descripcionFactura"];
		$datosFactura["totalFactura"] = $datos["totalCompra"];
		
		if($datos["plazoFactura"] == "0"){
			$datosFactura["estadoFactura"] = 0;
		}else{
			$datosFactura["estadoFactura"] = 1;
		}

		if( !in_array($datos['vencimientoMedicamentos'], $datos)){
			$this->session->set_flashdata("error","Tienes que ingresar el detalle de los medicamentos, para esta factura");
			redirect(base_url()."Botiquin/agregar_medicamento");
		}else{
			// Productos
			$medicamentos = $datos["idMedicamentos"];
			// Precios productos
			$precios = $datos["preciosMedicamentos"];
			// Cantidades de productos
			$cantidad = $datos["cantidadMedicamentos"];
			// Totales por producto
			$totales = $datos["totalUnitario"];
			// Fechas de vencimientos
			$vencimiento = $datos["vencimientoMedicamentos"];
			// Lote de cada medicamentos
			$lote = $datos["loteMedicamentos"];
			// Total de la compra
			$stock = $datos["stockMedicamentos"];
			// Total de la compra

			$data = array('datosFactura' => $datosFactura, 'medicamentos' => $medicamentos, 'precios' => $precios,
						'cantidad' => $cantidad, 'totales' => $totales, 'vencimiento' => $vencimiento, 'lote' => $lote, 'stock' => $stock);

			
				$bool = $this->Botiquin_Model->guardarFactura($data);
				if($bool){
					$this->session->set_flashdata("exito","Los datos de la factura fueron guardados con exito!");
					redirect(base_url()."Botiquin/agregar_medicamento");
				}else{
					$this->session->set_flashdata("error","Error al guardar los datos de la factura!");
					redirect(base_url()."Botiquin/agregar_medicamento");
				}
		}
		
	} */

	public function guardar_compra(){
		$datos = $this->input->post();
		$datos["recibidoPor"] = $this->session->userdata("empleado_h");

		$resp = $this->Botiquin_Model->guardarFactura($datos);
		if($resp > 0){
			// Agregando evento a bitacora
				$data["idUsuario"] = $this->session->userdata('id_usuario_h');
				$data["descripcionBitacora"] = "El usuario: ".$this->session->userdata('usuario_h')." agrego la compra con Id = ".$resp;
				$this->Usuarios_Model->insertarBitacora($data);
			// Fin bitacora	
			$this->session->set_flashdata("exito","Los datos de la factura fueron guardados con exito!");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$resp."/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos de la factura!");
			redirect(base_url()."Botiquin/agregar_medicamento");
		}
		
	}
/* 
	public function agregar_detalle_medicamento($id){
		echo $id;
	} */

	public function historial_compras(){
		$datos = $this->Botiquin_Model->obtenerFacturasCompra();
		$data = array('facturas' => $datos );
		$this->load->view('Base/header');
		$this->load->view('Botiquin/historial_compras', $data);
		$this->load->view('Base/footer');
	}

	public function detalle_factura_compra($id){
		$data["facturas"] = $this->Botiquin_Model->detalleFacturasCompra($id);
		$data["medicamentos"] = $this->Botiquin_Model->medicamentosFactura($id);
		$data["listaMedicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
		$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
		
		$this->load->view('Base/header');
		$this->load->view('Botiquin/detalle_compras', $data);
		$this->load->view('Base/footer'); 


	}

	public function guardar_detalle_compra(){
		$datos = $this->input->post();
		$bool = $this->Botiquin_Model->guardarDetalleFactura($datos);
		if($bool){
			// Agregando evento a bitacora
				$data["idUsuario"] = $this->session->userdata('id_usuario_h');
				$data["descripcionBitacora"] = "El usuario: ".$this->session->userdata('usuario_h')." agrego medicamentos a la factura con Id = ".$datos['idFactura'];
			$this->Usuarios_Model->insertarBitacora($data);
		// Fin bitacora	
			$this->session->set_flashdata("exito","La factura cambio su estado a Saldada");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos['idFactura']."/");
		}else{
			$this->session->set_flashdata("error","Error al saldar la factura");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos['idFactura']."/");
		}

	}

	public function compras_excel(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Fecha');
		$sheet->setCellValue('B1', 'Número');
		$sheet->setCellValue('C1', 'Tipo');
		$sheet->setCellValue('D1', 'Proveedor');
		$sheet->setCellValue('E1', 'Plazo');
		$sheet->setCellValue('F1', 'Total');
		$sheet->setCellValue('G1', 'Descripcion');
		$sheet->setCellValue('H1', 'Estado');
			
		$datos = $this->Botiquin_Model->obtenerFacturasCompra();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){

			// Obteniendo el plazo de la factura
			if ($d->plazoFactura == "0") {
				$plazo = "Contado";
			}else{
				$plazo = "Crédito ".$d->plazoFactura." dias";
			}

			// Obteniendo el estado de la factura
			if ($d->estadoFactura == 1) {
				$estado = "Pendiente";
			}else{
				$estado = "Saldada";
			}

			$sheet->setCellValue('A'.$flag, $d->idFactura);
			$sheet->setCellValue('B'.$flag, $d->numeroFactura);
			$sheet->setCellValue('C'.$flag, $d->tipoFactura);
			$sheet->setCellValue('D'.$flag, $d->empresaProveedor);
			$sheet->setCellValue('E'.$flag, $plazo);
			/* Aqui estoy trabajando */
			$totalFactura = 0;
			$medicamentos = $this->Botiquin_Model->medicamentosFactura($d->idFactura);
			foreach ($medicamentos as $medicamento) {
				$totalFactura += ($medicamento->cantidad * $medicamento->precio) + (($medicamento->cantidad * $medicamento->precio) * 0.13);
			}

			$sheet->setCellValue('F'.$flag, number_format($totalFactura, 2));
			$sheet->setCellValue('G'.$flag, $d->descripcionFactura);
			$sheet->setCellValue('H'.$flag, $estado);
				
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
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_compras'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function saldar_compra(){
		$datos = $this->input->post();
		$data = array('estadoFactura' => "0", 'idFactura' => $datos['idFactura'] );
		$bool = $this->Botiquin_Model->saldarCompra($data);
		if($bool){
			$this->session->set_flashdata("exito","La factura cambio su estado a Saldada");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos['idFactura']."/");
		}else{
			$this->session->set_flashdata("error","Error al saldar la factura");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos['idFactura']."/");
		}

		
	}

	/*public function detalle_compra_pdf(){
		$mpdf = new \Mpdf\Mpdf();
		//$html = $this->load->view('genpdf_view',[],true);
		$html = '';
		$html .= '<!DOCTYPE html>';
		$html .= '<html lang="en">';
		$html .= '	<head>';
		$html .= '		<meta charset="utf-8">';
		$html .= '		<title>My PDF</title>';
		$html .= '	</head>';
		$html .= '	<body>';
		$html .= '	<div class="container">';
		$html .= '		<h1>My Own Pdf</h1>';
		$html .= '		<div class="row">';
		$html .= '			<p>This is a demo text used to show how PDF is generated dynamically</p>';
		$html .= '	';
		$html .= '			<p>Try your own in this block</p>';
		$html .= '			<p>This is a paragraph. Edit this to enter your own</p>';
		$html .= '			<p>We have successfully generated our first PDF.</p>';
		$html .= '		</div>';
		$html .= '	</div>';
		$html .= '	</body>';
		$html .= '</html>';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}*/

	public function detalle_compra_pdf($id = null){

		$data["factura"] = $this->Botiquin_Model->detalleFacturasCompra($id);
		$data["medicamentos"] = $this->Botiquin_Model->medicamentosFactura($id);
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
						<td width="33%">{DATE j-m-Y}</td>
						<td width="33%" align="center">{PAGENO}/{nbpg}</td>
						<td width="33%" style="text-align: right;">Detalle de compra</td>
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

			$html = $this->load->view('Botiquin/detalle_compra_pdf',$data,true); // Cargando hoja de estilos

			$mpdf->WriteHTML($html);
			$mpdf->Output('detalle_compra.pdf', 'I');
		// Fin factura
		
	}

	public function actualizar_medicamento_factura(){
		$datos = $this->input->post();
		/* Para Factura */
			$data["cantidad"] = $datos["cantidadInsumo"]; // Cantidad para actualizar kardex y factura
			$data["cantidad_unitaria"] = ($datos["cantidadInsumo"] * $datos["baseMedida"]); // Cantidad para actualizar kardex y factura
			$data["idFacturaMedicamento"] = $datos["idMedicamentoFactura"];
		/* Para Factura */
		$bool = $this->Botiquin_Model->actualizarMedicamentoFactura($data);
		if($bool){
			$this->session->set_flashdata("exito","El medicamento se actualizo con exito'");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos["idHojaReturn"]."/");
		}else{
			$this->session->set_flashdata("error","Error al actualizar el medicamento");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos["idHojaReturn"]."/");
		} 
		// echo json_encode($data);
	}

	public function eliminar_medicamento_factura(){
		$datos = $this->input->post();
		/* Para Factura */
			$data["idFacturaMedicamento"] = $datos["idMedicamentoFactura"];
		/* Para Factura */
		$bool = $this->Botiquin_Model->eliminarMedicamentoFactura($data);
		if($bool){
			$this->session->set_flashdata("exito","El medicamento se elimino exito'");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos["idHojaReturn"]."/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar el medicamento");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos["idHojaReturn"]."/");
		}

		// echo json_encode($datos);
		
	}

	public function actualizar_datos_factura(){
		$datos = $this->input->post();
		$bool = $this->Botiquin_Model->actualizarDetalleFactura($datos);
		if($bool){
			$this->session->set_flashdata("exito","La factuar se actualizo con exito'");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos["idFactura"]);
		}else{
			$this->session->set_flashdata("error","Error al actualizar la factura");
			redirect(base_url()."Botiquin/detalle_factura_compra/".$datos["idFactura"]);
		}
	}
	
	public function kardex(){

		$meses = array('enero','febrero','marzo','abril','mayo','junio','julio', 'agosto','septiembre','octubre','noviembre','diciembre');

		// Logica para el mes
			$anio = date("Y");
			$mes = date("m");
			$i = $anio."-".$mes."-01 00:00:00"; 
			$f = $anio."-".$mes."-31 23:59:59";
			if($mes < 10){
				$flagMes = substr($mes-1, -1);
			}else{
				$flagMes = $mes-1;
			}
			$data["mes"] = $meses[$flagMes];

		$data["movimientos"] = $this->Botiquin_Model->kardex($i, $f);
		
		$this->load->view("Base/header");
		$this->load->view("Botiquin/kardex", $data);
		$this->load->view("Base/footer");

		// echo json_encode($data["movimientos"]);
	}

	public function kardex_fecha(){
		$datos = $this->input->post();
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		
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
		
		$data['movimientos'] = $this->Botiquin_Model->kardex($i, $f);
		$data['inicio'] = $i;
		$data['fin'] = $f;
		// Fin gastos efectuados
		
		$this->load->view('Base/header');
		$this->load->view('Botiquin/kardex_fecha', $data);
		$this->load->view('Base/footer'); 
		
	}

	public function obtener_medicamentos(){
		if($this->input->is_ajax_request()){
			$datos = $this->Botiquin_Model->stockMinimo();
			echo json_encode($datos);
		}
		else{
			echo "Error...";
		}
	}

	public function stock_medicamentos(){
		// Obteniendo el ultimo codigo
		$ultimoCodigo = $this->Botiquin_Model->ultimoCodigo();
        $codigo = $ultimoCodigo->codigo;
        if($codigo  == null){ 
            $codigo = 1000; 
        }else{ 
            $codigo = $codigo + 1;
		}
		// Fin del obtener ultimo codigo

		$medicamentos = $this->Botiquin_Model->obtenerMedicamentos();
		$clasificacionMedicamentos = $this->Botiquin_Model->obtenerClasificacionMedicamentos();
		$proveedores = $this->Proveedor_Model->obtenerProveedores();
		$data = array('clasificaciones' => $clasificacionMedicamentos, 'proveedores' => $proveedores, 'cod' => $codigo, 'medicamentos' => $medicamentos);

		$this->load->view('Base/header');
		$this->load->view('Botiquin/stock_medicamentos', $data);
		$this->load->view('Base/footer');
	}

	public function actualizar_stock_medicamento(){
		$datos = $this->input->post();

		$data["stockMedicamento"] = $datos["stockMedicamento"];
		$data["idMedicamento"] = $datos["idMedicamentoA"];
		
		$bool = $this->Botiquin_Model->actualizarStock($data);
		if($bool){
			$this->session->set_flashdata("exito","Los datos se actualizaron con exito!");
			redirect(base_url()."Botiquin/stock_medicamentos");
		}else{
			$this->session->set_flashdata("error","Error al actualizar los datos!");
			redirect(base_url()."Botiquin/stock_medicamentos");
		}
	}


	// Para vista de consultar precios
		public function precios_medicamentos(){
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
			$this->load->view("Base/header");
			$this->load->view("Botiquin/lista_precios", $data);
			$this->load->view("Base/footer");
		}
	// Fin vista consultar precio
	/* public function obtener_medicamentos(){
		if($this->input->is_ajax_request()){
			$id =$this->input->get("id");
			$datos = $this->Botiquin_Model->stockMedicamento();
			echo json_encode($datos);
		}
		else
		{
			echo "Error...";
		}
	} */


	// Metodos ajax
		public function guardar_medicamento_async(){
			if($this->input->is_ajax_request()){

				$datos = $this->input->post();
				$data["factura"]  = $datos["factura"];
				$data["id"]  = $datos["id"];
				$data["cantidad"]  = $datos["cantidad"]; // Eje 1 Caja, 1 Blister, 1 unidad, esto no se suma al stock
				$data["precioC"]  = $datos["precioC"];
				$data["fecha"]  = $datos["fecha"];
				$data["total"]  = ($datos["cantidad"] * $datos["precioC"]);
				
				$data["nombre_medida"]  = $datos["nombre_medida"];
				$data["base_medida"]  = $datos["base_medida"];
				$data["cantidad_unitaria"]  = ($datos["cantidad"] * $datos["base_medida"]); // Ejem. 1 Caja x 100 unidades = 100 unidades, esto se suma al stock

				if($data["nombre_medida"] == "Unitario"){
					$data["precio_unitario"]  = $datos["precioC"];
				}else{
					$data["precio_unitario"]  = (($datos["precioC"] * $datos["cantidad"]) / $data["cantidad_unitaria"]);
				}

				// echo json_encode($data);

				$bool = $this->Botiquin_Model->guardarMedicamentoAsync($data);

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
	// Fin metodos ajax

	// Guardando IVA retenido
		public function guardar_iva(){
			$datos = $this->input->post();
			$return = $datos["idFactura"];
			//var_dump($datos);
			$bool = $this->Botiquin_Model->guardarIVARetenido($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
				redirect(base_url()."Botiquin/detalle_factura_compra/".$return."/");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos!");
				redirect(base_url()."Botiquin/detalle_factura_compra/".$return."/");
			}
		}
	// Fin IVA retenido


	// Metodos para controlar Stocks
		public function estado_stocks(){
			// Variables para manejo de faltantes
				$faltante1 = array();
				$faltante2 = array();
				$faltante3 = array();
				$faltante4 = array();
				$faltante5 = array();
				$faltante = 0;
			// Variables para manejo de faltantes

			// $data["stocks"] = $this->Botiquin_Model->listaStocks();
			$data["detalleStocks"] = $this->Botiquin_Model->detalleStock();
			foreach ($data["detalleStocks"] as $row) {
				if($row->stockInsumo != $row->debeInsumo){
					$faltante = $row->debeInsumo - $row->stockInsumo;
					switch ($row->idStock) {
						case '1':
							$faltante1[$row->nombreMedicamento] = array(
								'idFila' => $row->idDetalleStock,
								'idStock' => $row->idStock,
								'idInsumo' => $row->idInsumo,
								'codigoInsumo' => $row->codigoMedicamento,
								'faltante' => $faltante,
							);
							break;
						case '2':
							$faltante2[$row->nombreMedicamento] = array(
								'idFila' => $row->idDetalleStock,
								'idStock' => $row->idStock,
								'idInsumo' => $row->idInsumo,
								'codigoInsumo' => $row->codigoMedicamento,
								'faltante' => $faltante,
							);
							break;
						case '3':
							$faltante3[$row->nombreMedicamento] = array(
								'idFila' => $row->idDetalleStock,
								'idStock' => $row->idStock,
								'idInsumo' => $row->idInsumo,
								'codigoInsumo' => $row->codigoMedicamento,
								'faltante' => $faltante,
							);
							break;
						case '4':
							$faltante4[$row->nombreMedicamento] = array(
								'idFila' => $row->idDetalleStock,
								'idStock' => $row->idStock,
								'idInsumo' => $row->idInsumo,
								'codigoInsumo' => $row->codigoMedicamento,
								'faltante' => $faltante,
							);
							break;

						case '5':
							$faltante5[$row->nombreMedicamento] = array(
								'idFila' => $row->idDetalleStock,
								'idStock' => $row->idStock,
								'idInsumo' => $row->idInsumo,
								'codigoInsumo' => $row->codigoMedicamento,
								'faltante' => $faltante,
							);
							break;
						
						default:
							# code...
							break;
					}
				}
				
			}
			
			$data["faltante1"] = urlencode(base64_encode(serialize($faltante1)));
			$data["faltante2"] = urlencode(base64_encode(serialize($faltante2)));
			$data["faltante3"] = urlencode(base64_encode(serialize($faltante3)));
			$data["faltante4"] = urlencode(base64_encode(serialize($faltante4)));
			$data["faltante5"] = urlencode(base64_encode(serialize($faltante5)));
			
			
			$this->load->view("Base/header");
			$this->load->view("Botiquin/Stocks/estado_stocks", $data);
			$this->load->view("Base/footer");
			
			// echo json_encode($data);
		}

		public function reintegrar_insumos($params = null){
			$data["enfermeras"] = $this->Botiquin_Model->listadoEnfermeras();
			$data["insumos"] = unserialize(base64_decode(urldecode($params)));
			$data["parametros"] = $params;
			$this->load->view("Base/header");
			$this->load->view("Botiquin/Stocks/validar_reintegro", $data);
			$this->load->view("Base/footer");

			// echo json_encode($data["insumos"]);
		}

		public function validar_reintegro($params = null){
			$datos = unserialize(base64_decode(urldecode($params)));
			/* foreach ($datos as $nombreInsumo => $row) {
				$data["idStock"] = $row["idStock"];
				$data["idInsumo"] = $row["idInsumo"];
				$data["faltante"] = $row["faltante"];
				$this->Botiquin_Model->guardarReintegro($data);
			}
			redirect(base_url()."Botiquin/estado_stocks"); */

			echo json_encode($datos);
		}

		public function detalle_a_imprimir($params = null){
			$data["insumos"] = unserialize(base64_decode(urldecode($params)));
			$data["insumos"] = unserialize(base64_decode(urldecode($params)));

			$stock = 0;
			foreach ($data["insumos"] as $nombreInsumo => $row) {
				$stock = $row["idStock"];
			} // Fin del foreach
			$data["stock"] = $this->Botiquin_Model->nombreStock($stock)->nombreStock;
			
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
							<td width="33%"></td>
							<td width="33%" align="center"><strong>{PAGENO}/{nbpg}</strong></td>
							<td width="33%"></td>
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

				$html = $this->load->view('Botiquin/Stocks/detalle_a_reintegrar', $data,true); // Cargando hoja de estilos

				$mpdf->WriteHTML($html);
				$mpdf->Output('detalle_compra.pdf', 'I');
			// Fun factura

			// echo json_encode($data);
		}
	// Metodos para controlar Stocks


	// Metodos nuevos para agregar medicamento
		public function datos_medicamento(){

			// Obteniendo el ultimo codigo
				$ultimoCodigo = $this->Botiquin_Model->ultimoCodigo();
				$codigo = $ultimoCodigo->codigo;
				if($codigo  == null){ 
					$codigo = 1000; 
				}else{ 
					$codigo = $codigo + 1;
				}
			// Fin del obtener ultimo codigo

			$data["medicamentos"] = $this->Botiquin_Model->medicamentosBotiquin();
			$data["clasificaciones"] = $this->Botiquin_Model->obtenerClasificacionMedicamentos();
			$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
			$data["cod"] = $codigo;

			$this->load->view("Base/header");
			$this->load->view("Botiquin/agregar_datos_medicamento", $data);
			$this->load->view("Base/footer");
		}

		public function actualizar_datos_medicamento($id = null){
			$data["medicamento"] = $this->Botiquin_Model->obtenerMedicamento2($id);
			$data["clasificaciones"] = $this->Botiquin_Model->obtenerClasificacionMedicamentos();
			$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
			$this->load->view('Base/header');
			$this->load->view('Botiquin/editar_medicamento', $data);
			$this->load->view('Base/footer');

			// echo json_encode($data["medicamento"]);
		}

		public function guardar_medicamento_actualizado(){
			$datos = $this->input->post();
			$idMedicamento = $datos["idMedicamento"];
			unset($datos["idMedicamento"]);
			if(isset($datos["nombreMedida"])){
				// Creando Json de medidas
					// Crear un arreglo combinado
					$listaMedidas = array();
					// Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
					$numElementos = count($datos["nombreMedida"]);
					// Iterar sobre la longitud de las matrices y combinar los datos
					for ($i = 0; $i < $numElementos; $i++) {
						// Crear un arreglo asociativo para cada conjunto de datos
						$objeto = array(
							"nombreMedida" => $datos["nombreMedida"][$i],
							"cantidad" => $datos["cantidad"][$i],
							"precio" => $datos["precio"][$i]
						);
						// Agregar el arreglo al arreglo combinado
						array_push($listaMedidas, $objeto);
					}
					unset($datos["nombreMedida"], $datos["cantidad"], $datos["precio"]);
					$datos["medidas"] = json_encode($listaMedidas);
				// Creando Json de medidas

			}else{
				$datos["medidas"] = "";
			}
			$datos["idMedicamento"] = $idMedicamento ;
			$bool= $this->Botiquin_Model->actualizarMedicamento($datos); //Guardar datos en db
			if($bool){
				$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
				redirect(base_url()."Botiquin/actualizar_datos_medicamento/".$idMedicamento."/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos!");
				redirect(base_url()."Botiquin/actualizar_datos_medicamento/".$idMedicamento."/");
			}

			// echo json_encode($datos);
		}
	// Metodos nuevos para agregar medicamento

	// Metodos para medidas
		public function lista_medidas(){
			$data["medidas"] = $this->Botiquin_Model->obtenerMedidas();
			$resp = $this->Botiquin_Model->ultimoCodigoMedidas();
			if($resp->codigo == 0){ 
				$codigo = 1000; 
			}else{ 
				$codigo = $resp->codigo + 1;
			}
			$data["codigo"] = $codigo;
			$this->load->view('Base/header');
			$this->load->view('Botiquin/lista_medidas', $data);
			$this->load->view('Base/footer');

			// echo json_encode($data);
		}
		
		public function guardar_medida(){
			$datos = $this->input->post();
			$bool = $this->Botiquin_Model->guardarMedida($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
				redirect(base_url()."Botiquin/lista_medidas/");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos!");
				redirect(base_url()."Botiquin/lista_medidas/");
			}

			// echo json_encode($datos);
		}
	
		public function actualizar_medida(){
			$datos = $this->input->post();
			$bool = $this->Botiquin_Model->actualizarMedida($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
				redirect(base_url()."Botiquin/lista_medidas/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos!");
				redirect(base_url()."Botiquin/lista_medidas/");
			}

			// echo json_encode($datos);
		}
	
		public function eliminar_medida(){
			$datos = $this->input->post();
			$bool = $this->Botiquin_Model->eliminarMedida($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
				redirect(base_url()."Botiquin/lista_medidas/");
			}else{
				$this->session->set_flashdata("error","Error al eliminar los datos!");
				redirect(base_url()."Botiquin/lista_medidas/");
			}
			// echo json_encode($datos);
		}
	
		public function lista_medidas_excel(){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Código');
			$sheet->setCellValue('B1', 'Nombre');
				
			$datos = $this->Botiquin_Model->obtenerMedidas();
			$number = 1;
			$flag = 2;
			foreach($datos as $d){
				$sheet->setCellValue('A'.$flag, $d->codigoMedida);
				$sheet->setCellValue('B'.$flag, $d->nombreMedida);
					
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

			$sheet->getColumnDimension('A')->setAutoSize(true);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'listado_medidas '.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}

		public function obtener_medidas(){
			if($this->input->is_ajax_request()){
				// Ejecutando consultas
				$medidas = $this->Botiquin_Model->obtenerMedidas();
				print json_encode($medidas);
			}
		}
	// Metodos para medidas



	public function kardex_inicial(){
		
	}

}
