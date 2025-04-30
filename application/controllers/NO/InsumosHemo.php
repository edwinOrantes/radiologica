<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InsumosHemo extends CI_Controller {

	// Constructor
		public function __construct(){
			parent::__construct();
			date_default_timezone_set('America/El_Salvador');
			$this->load->model("InsumosHemo_Model");
			$this->load->model("Proveedor_Model");
			if (!$this->session->has_userdata('valido')){
				$this->session->set_flashdata("error", "Debes iniciar sesión");
				redirect(base_url());
			}
		}
	// Constructor

	// Metodos para la gestion se insumos
		public function index(){
			$data["insumos"] = $this->InsumosHemo_Model->obtenerInsumos();
			// Creando codigo del medicamento
				$codigo = $this->InsumosHemo_Model->obtenerCodigoIH();
				$cod = 0;
				if($codigo->codigoInsumoHemo == NULL ){
					$cod = 1000;
				}else{
					$cod = ($codigo->codigoInsumoHemo + 1);
				}
				$data["codigo"] = $cod;
			// Fin crear codigo del medicamento
			// echo json_encode($data["insumos"]);
			$this->load->view('Base/header');
			$this->load->view('InsumosHemo/lista_insumo', $data);
			$this->load->view('Base/footer');
		}

		public function guardar_insumos(){
			$datos = $this->input->post();
			$resp = $this->InsumosHemo_Model->guardarInsumo($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","Los datos se guardaron con exito");
				redirect(base_url()."InsumosHemo/");
			}else{
				$this->session->set_flashdata("error","No se guardaron los datos!");
				redirect(base_url()."InsumosHemo/");
			}
		}

		public function actualizar_insumos(){
			$datos = $this->input->post();
			// echo json_encode($datos);
			$resp = $this->InsumosHemo_Model->actualizarInsumo($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","Los datos se actualizaron con exito");
				redirect(base_url()."InsumosHemo/");
			}else{
				$this->session->set_flashdata("error","No se actualizaron los datos!");
				redirect(base_url()."InsumosHemo/");
			}
		}

		public function eliminar_insumo(){
			$datos = $this->input->post();
			// echo json_encode($datos);
			$resp = $this->InsumosHemo_Model->eliminarInsumo($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","Los datos se eliminaron con exito");
				redirect(base_url()."InsumosHemo/");
			}else{
				$this->session->set_flashdata("error","No se eliminaron los datos!");
				redirect(base_url()."InsumosHemo/");
			}
		}
		
		public function insumos_excel(){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Código');
			$sheet->setCellValue('B1', 'Nombre');
			$sheet->setCellValue('C1', 'Existencia');
				
			$datos = $this->InsumosHemo_Model->obtenerInsumos();

			$number = 1;
			$flag = 2;
			$totalVendido = 0;
			$totalStock = 0;
			foreach($datos as $d){
				if($d->pivoteInsumoHemo){

					$sheet->setCellValue('A'.$flag, $d->codigoInsumoHemo);
					$sheet->setCellValue('B'.$flag, $d->nombreInsumoHemo);
					$sheet->setCellValue('C'.$flag, $d->stockInsumoHemo);
					
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
			//$sheet->getStyle('A1:L10')->applyFromArray($styleThinBlackBorderOutline);
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:C'.$flag)->getFont()->setSize(12);
			//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setAutoSize(true);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getColumnDimension('C')->setAutoSize(true);

			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'listado_insumos_hemo'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	// Metodos para la gestion se insumos

	// Metodos para la gestion de compras
		public function agregar_compra(){
			$data["insumos"] = $this->InsumosHemo_Model->obtenerInsumos();
			$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
			$this->load->view("Base/header");
			$this->load->view("InsumosHemo/crear_compra", $data);
			$this->load->view("Base/footer");
		}

		public function guardar_compra(){
			$datos = $this->input->post();
			// echo json_encode($datos);
			$resp = $this->InsumosHemo_Model->guardarCompra($datos); // true si se realizo la consulta.
			if($resp > 0){
				$this->session->set_flashdata("exito","La factura fue creada con exito!");
				redirect(base_url()."InsumosHemo/detalle_compra/".$resp."/");
			}else{
				$this->session->set_flashdata("error","Error al crear la factura!");
				redirect(base_url()."InsumosHemo/lista_compras/");
			}
		}

		public function lista_compras(){
            $data["compras"] = $this->InsumosHemo_Model->listaCompras();
			// echo json_encode($data);
            $this->load->view("Base/header");
            $this->load->view("InsumosHemo/lista_compras", $data);
            $this->load->view("Base/footer");
        }

		public function detalle_compra($id = null){
			$data["compra"] = $id;
			$data["compraCabecera"] = $this->InsumosHemo_Model->detalleCabeceraCompra($id);
			$data["detalleCL"] = $this->InsumosHemo_Model->detalleContenidoCompra($id);
			$data["listaIL"] = $this->InsumosHemo_Model->obtenerInsumos();

			// echo json_encode($data);

			$this->load->view("Base/header");
			$this->load->view("InsumosHemo/detalle_compra", $data);
			$this->load->view("Base/footer");
		}

		public function guardar_insumos_hemo(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();

				
				// Arreglo para informacion de insumos-compra
					$datosCIH["facturaHemo"] =  $datos["factura"];
					$datosCIH["idInsumoHemo"] =  $datos["id"];
					$datosCIH["cantidadDetalleCH"] =  $datos["cantidad"];
					$datosCIH["precioDetalleCH"] =  $datos["precio"];
					$datosCIH["vencimientoDetalleCH"] =  $datos["vencimiento"];
				// Fin arreglo para informacion de insumos-compra

				$resp = $this->InsumosHemo_Model->agregarDetalleCIH($datosCIH);
				if($resp > 0){
					$this->respuestaConsulta(1, "Exito");

				}else{
					$this->respuestaConsulta(0, "Error");
				}
				// echo json_encode($datos);
			}
			else{
				$this->respuestaConsulta(0, "Error");
			}
		}

		public function actualizar_detalle_cl(){
            $datos = $this->input->post();
            // Arreglo para actualizar detalle de la compra
                $datosDC["cantidad"] = $datos["cantidadInsumo"];
                $datosDC["precio"] = $datos["precioILEdit"];
                $datosDC["fila"] = $datos["filaILEdit"];
            // Fin arreglo para actualizar detalle de la compra
            $data["detalle"] = $datosDC;
			
			// echo json_encode($data);
            
            $resp = $this->InsumosHemo_Model->actualizarContenidoCompra($data); // true si se realizo la consulta.
            if($resp > 0){
                $this->session->set_flashdata("exito","Detalle actualizado con exito!");
                redirect(base_url()."InsumosHemo/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","Error al actualizar los datos!");
                redirect(base_url()."InsumosHemo/detalle_compra/".$datos['idCompraReturn']."/");
            }
        }

		public function eliminar_medicamento_ch(){
            $datos = $this->input->post();
            $data["detalle"] = $datos["idFila"];
            $resp = $this->InsumosHemo_Model->eliminarContenidoCompra($data); // true si se realizo la consulta.
            if($resp > 0){
                $this->session->set_flashdata("exito","Detalle eliminadp con exito!");
                redirect(base_url()."InsumosHemo/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","Error al eliminar los datos!");
                redirect(base_url()."InsumosHemo/detalle_compra/".$datos['idCompraReturn']."/");
            }

        }

		public function guardar_extras(){
            $datos = $this->input->post();
			// echo json_encode($datos);
            $resp = $this->InsumosHemo_Model->extrasCompra($datos);
            if($resp){
                $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                redirect(base_url()."InsumosHemo/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","No se guardaron los datos!");
                redirect(base_url()."InsumosHemo/detalle_compra/".$datos['idCompraReturn']."/");
            }
        }

		private function respuestaConsulta($estado = null, $resp = null){
			$respuesta = array('estado' => $estado, 'respuesta' => $resp);
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	// Metodos para la gestion de compras

	// Metodos para la gestion de salidas
		public function descargos_insumos(){
			$data['cuentas'] = $this->InsumosHemo_Model->listaCuentas();
			// echo json_encode($data['cuentas']);
			/* 
			$data["ultimoCuenta"] = $this->InsumosLab_Model->ultimaCuenta(); */

			$this->load->view("Base/header");
			$this->load->view("InsumosHemo/cuentas_salidas", $data);
			$this->load->view("Base/footer");
		}

		public function crear_cuenta(){
			$datos = $this->input->post();
			// echo json_encode($datos);
			$resp = $this->InsumosHemo_Model->crearCuenta($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","La cuenta se creo con exito");
				redirect(base_url()."InsumosHemo/detalle_salidas/".$resp."/");
			}else{
				$this->session->set_flashdata("error","Error al crear la cuenta!");
				redirect(base_url()."InsumosHemo/descargos_insumos/");
			}
		}

		public function detalle_salidas($id = null){
			$data["cuenta"] = $id;
			$data["datosCuenta"] = $this->InsumosHemo_Model->obtenerDetalleCuenta($id);
			$data["filaCuenta"] = $this->InsumosHemo_Model->obtenerCuenta($id);
			$data["insumos"] = $this->InsumosHemo_Model->obtenerInsumos();
			
			$this->load->view("Base/header");
			$this->load->view("InsumosHemo/detalle_cuenta_descargos", $data);
			$this->load->view("Base/footer");

			// echo json_encode($data);
		}

		public function sacar_de_stock(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				$cuenta = array('cuentaActual' => $datos["cuentaActual"], 'idInsumo' => $datos["idInsumo"], 'cantidad' => $datos["cantidadInsumo"], 
								'por' => $this->session->userdata('id_usuario_h'), 'entregadoA' => $datos["entregadoA"]); // Para tabla isbm
				$data["cuenta"] = $cuenta;
				$resp = $this->InsumosHemo_Model->descontarInsumo($data);
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
		}

		public function editar_insumo(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				// Preparando arreglo fila detalle
					$filaDetalle = array(
						'cantidad' => $datos["cantidad"], 
						'filaDetalle' => $datos["cuentaInsumo"], 
					);  // Volviendo a su estado original.
				// Fin prepara arreglo fila detalle
				$resp = $this->InsumosHemo_Model->editarFilaDescargo($filaDetalle);
				if($resp){
					echo "Bien";
				}else{
					echo "Error";
				}
				//echo json_encode($datos);
			}
			else{
				echo "Error...";
			}

		}

		public function eliminar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				$resp = $this->InsumosHemo_Model->eliminarDetalle($datos);
				if($resp){
					echo "Exito...";
				}else{
					echo "Error...";
				}
				// echo json_encode($datos);
			}
			else{
				echo "Error...";
			}
		}

		public function cerrar_cuenta_descargos(){
			$datos = $this->input->post();
			$return = $datos["idCuentaEditar"];
			// echo json_encode($datos);
			$resp = $this->InsumosHemo_Model->cerrarCuentaDescargos($datos); // true si se realizo la consulta.
			
			if($resp){
				$this->session->set_flashdata("exito","La cuenta fue cerrada con exito!");
				redirect(base_url()."InsumosHemo/detalle_salidas/".$return."/");
			}else{
				$this->session->set_flashdata("error","Error al cerrar la cuenta!");
				redirect(base_url()."InsumosHemo/detalle_salidas/".$return."/");
			}
		}
	// Metodos para la gestion de salidas
		
}
