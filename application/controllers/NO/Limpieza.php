<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Limpieza extends CI_Controller {

	// Constructor
		public function __construct(){
			parent::__construct();
			date_default_timezone_set('America/El_Salvador');
			$this->load->model("Limpieza_Model");
			if (!$this->session->has_userdata('valido')){
				$this->session->set_flashdata("error", "Debes iniciar sesión");
				redirect(base_url());
			}
		}
	// Constructor

	// Metodos para la gestion se insumos
		public function index(){
			$data["insumos"] = $this->Limpieza_Model->obtenerInsumos();
			// Creando codigo del medicamento
				$codigo = $this->Limpieza_Model->obtenerCodigoIL();
				$cod = 0;
				if($codigo->codigoInsumoLimpieza == NULL ){
					$cod = 1000;
				}else{
					$cod = ($codigo->codigoInsumoLimpieza + 1);
				}
				$data["codigo"] = $cod;
			// Fin crear codigo del medicamento
			// echo json_encode($data);
			$this->load->view('Base/header');
			$this->load->view('Limpieza/lista_insumo', $data);
			$this->load->view('Base/footer');
		}

		public function guardar_insumos(){
			$datos = $this->input->post();
			$resp = $this->Limpieza_Model->guardarInsumo($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","Los datos se guardaron con exito");
				redirect(base_url()."Limpieza/");
			}else{
				$this->session->set_flashdata("error","No se guardaron los datos!");
				redirect(base_url()."Limpieza/");
			}
		}

		public function actualizar_insumos(){
			$datos = $this->input->post();
			// echo json_encode($datos);
			$resp = $this->Limpieza_Model->actualizarInsumo($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","Los datos se actualizaron con exito");
				redirect(base_url()."Limpieza/");
			}else{
				$this->session->set_flashdata("error","No se actualizaron los datos!");
				redirect(base_url()."Limpieza/");
			}
		}

		public function eliminar_insumo(){
			$datos = $this->input->post();
			$resp = $this->Limpieza_Model->eliminarInsumo($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","Los datos se eliminaron con exito");
				redirect(base_url()."Limpieza/");
			}else{
				$this->session->set_flashdata("error","No se eliminaron los datos!");
				redirect(base_url()."Limpieza/");
			}
		}
		
		public function insumos_excel(){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Código');
			$sheet->setCellValue('B1', 'Nombre');
			$sheet->setCellValue('C1', 'Categoría');
			$sheet->setCellValue('D1', 'Marca');
			$sheet->setCellValue('E1', 'Medida');
			$sheet->setCellValue('F1', 'Existencia');
				
			$datos = $this->Limpieza_Model->obtenerInsumos();

			$number = 1;
			$flag = 2;
			$totalVendido = 0;
			$totalStock = 0;
			foreach($datos as $d){
				if($d->pivoteInsumoLimpieza){

					$sheet->setCellValue('A'.$flag, $d->codigoInsumoLimpieza);
					$sheet->setCellValue('B'.$flag, $d->nombreInsumoLimpieza);
					$sheet->setCellValue('C'.$flag, $d->categoriaInsumoLimpieza);
					$sheet->setCellValue('D'.$flag, $d->marcaInsumoLimpieza);
					$sheet->setCellValue('E'.$flag, $d->unidadInsumoLimpieza);
					$sheet->setCellValue('F'.$flag, $d->stockInsumoLimpieza);
					
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
			$sheet->getStyle('A1:F1')->getFont()->setBold(true);		
			//$sheet->getStyle('A1:L10')->applyFromArray($styleThinBlackBorderOutline);
			//Alignment
			//fONT SIZE
			$sheet->getStyle('A1:F'.$flag)->getFont()->setSize(12);
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
			$filename = 'listado_insumos_limpieza'.$curdate;
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	// Metodos para la gestion se insumos

	// Metodos para la gestion de compras
		public function agregar_compra(){
			$data["insumos"] = $this->Limpieza_Model->obtenerInsumos();
			$this->load->view("Base/header");
			$this->load->view("Limpieza/crear_compra", $data);
			$this->load->view("Base/footer");
		}

		public function guardar_compra(){
			$datos = $this->input->post();
			$resp = $this->Limpieza_Model->guardarCompra($datos); // true si se realizo la consulta.
			if($resp > 0){
				$this->session->set_flashdata("exito","La factura fue creada con exito!");
				redirect(base_url()."Limpieza/detalle_compra/".$resp."/");
			}else{
				$this->session->set_flashdata("error","Error al crear la factura!");
				redirect(base_url()."Limpieza/lista_compras/");
			}
		}

		public function lista_compras(){
            $data["compras"] = $this->Limpieza_Model->listaCompras();
            $this->load->view("Base/header");
            $this->load->view("Limpieza/lista_compras", $data);
            $this->load->view("Base/footer");
        }

		public function detalle_compra($id = null){
			$data["compra"] = $id;
			$data["compraCabecera"] = $this->Limpieza_Model->detalleCabeceraCompra($id);
			$data["detalleCL"] = $this->Limpieza_Model->detalleContenidoCompra($id);
			$data["listaIL"] = $this->Limpieza_Model->obtenerInsumos();

			// echo json_encode($data["compraCabecera"]);

			$this->load->view("Base/header");
			$this->load->view("Limpieza/detalle_compra", $data);
			$this->load->view("Base/footer");
		}

		//Nuevos
			public function guardar_insumos_limpieza(){
				if($this->input->is_ajax_request()){
					$datos = $this->input->post();
					// Arreglo para informacion de insumos-compra
						$datosCIL["facturaLimpieza"] =  $datos["factura"];
						$datosCIL["idInsumoLimpieza"] =  $datos["id"];
						$datosCIL["cantidadDetalleCL"] =  $datos["cantidad"];
						$datosCIL["precioDetalleCL"] =  $datos["precio"];
						$datosCIL["vencimientoDetalleCL"] =  $datos["vencimiento"];
					// Fin arreglo para informacion de insumos-compra

					// echo json_encode($datosCIL);
					
					$resp = $this->Limpieza_Model->agregarDetalleCIL($datosCIL);

					if($resp > 0){
						$this->respuestaConsulta(1, "Exito");
					}else{
						$this->respuestaConsulta(0, "Error");
					}
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
				
				$resp = $this->Limpieza_Model->actualizarContenidoCompra($data); // true si se realizo la consulta.
				if($resp > 0){
					$this->session->set_flashdata("exito","Detalle actualizado con exito!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}else{
					$this->session->set_flashdata("error","Error al actualizar los datos!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}
			}

			public function eliminar_medicamento_cl(){
				$datos = $this->input->post();
				
				// Arreglo para actualizar detalle de la compra
					$datosDC["fila"] = $datos["idFila"];
				// Fin arreglo para actualizar detalle de la compra
				
				$data["detalle"] = $datosDC;
				
				
				// echo json_encode($data);
				$resp = $this->Limpieza_Model->eliminarContenidoCompra($data); // true si se realizo la consulta.
				if($resp > 0){
					$this->session->set_flashdata("exito","Detalle eliminadp con exito!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}else{
					$this->session->set_flashdata("error","Error al eliminar los datos!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}

			}
		//Nuevos

		// Viejos
			/* public function guardar_insumos_limpieza(){
				if($this->input->is_ajax_request()){
					$datos = $this->input->post();
					$datosStock = $this->Limpieza_Model->obtenerInsumo($datos["id"]);
					$precio = $datos["precio"];
					$stock = $datosStock->stockInsumoLimpieza + $datos["cantidad"];
					
					// Arreglo para actualizar informacion de stock y precio
						$datosIL["precio"] = $precio; 
						$datosIL["stock"] = $stock; 
						$datosIL["id"] = $datos["id"]; 
					// Fin arreglo para actualizar informacion de stock y precio
					
					
					// Arreglo para informacion de insumos-compra
						$datosCIL["facturaLimpieza"] =  $datos["factura"];
						$datosCIL["idInsumoLimpieza"] =  $datos["id"];
						$datosCIL["cantidadDetalleCL"] =  $datos["cantidad"];
						$datosCIL["precioDetalleCL"] =  $datos["precio"];
						$datosCIL["vencimientoDetalleCL"] =  $datos["vencimiento"];
					// Fin arreglo para informacion de insumos-compra

					// echo json_encode($datosCIL);
					
					$resp = $this->Limpieza_Model->agregarDetalleCIL($datosCIL);
					if($resp > 0){
						$resp2 = $this->Limpieza_Model->actualizarDatosIL($datosIL);
						if($resp2){
							$this->respuestaConsulta(1, "Exito");
						}else{
							$this->Limpieza_Model->borrarDetalleCIL($resp);
							$this->respuestaConsulta(0, "Error");
						}
					}else{
						$this->respuestaConsulta(0, "Error");
					}
				}
				else{
					$this->respuestaConsulta(0, "Error");
				}
			}

			public function actualizar_detalle_cl(){
				$datos = $this->input->post();
				
				$datosStock = $this->Limpieza_Model->stockIL($datos["idILEdit"]);
				// Arreglo para actualizar stock del medicamento/reactivo
					$datosIL["precio"] = $datos["precioILEdit"];
					$datosIL["stock"] = ($datosStock->stockInsumoLimpieza - $datos["cantidadILEdit"]) + $datos["cantidadInsumo"];
					$datosIL["insumo"] = $datos["idILEdit"];
				// Fin arreglo para actualizar stock del medicamento/reactivo
				
				// Arreglo para actualizar detalle de la compra
					$datosDC["cantidad"] = $datos["cantidadInsumo"];
					$datosDC["precio"] = $datos["precioILEdit"];
					$datosDC["fila"] = $datos["filaILEdit"];
				// Fin arreglo para actualizar detalle de la compra
				$data["insumo"] = $datosIL;
				$data["detalle"] = $datosDC;
				
				// echo json_encode($data);
				
				$resp = $this->Limpieza_Model->actualizarContenidoCompra($data); // true si se realizo la consulta.
				if($resp > 0){
					$this->session->set_flashdata("exito","Detalle actualizado con exito!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}else{
					$this->session->set_flashdata("error","Error al actualizar los datos!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}
			}

			public function eliminar_medicamento_cl(){
				$datos = $this->input->post();
				
				$datosStock = $this->Limpieza_Model->stockIL($datos["idInsumo"]);
				
				
				// Arreglo para actualizar stock del medicamento/reactivo
					$datosIL["stock"] = ($datosStock->stockInsumoLimpieza - $datos["cantidadInsumo"]);
					$datosIL["insumo"] = $datos["idInsumo"];
				// Fin arreglo para actualizar stock del medicamento/reactivo
				
				
				// Arreglo para actualizar detalle de la compra
					$datosDC["fila"] = $datos["idFila"];
				// Fin arreglo para actualizar detalle de la compra
				
				
				$data["insumo"] = $datosIL;
				$data["detalle"] = $datosDC;
				
				
				// echo json_encode($data);
				$resp = $this->Limpieza_Model->eliminarContenidoCompra($data); // true si se realizo la consulta.
				if($resp > 0){
					$this->session->set_flashdata("exito","Detalle eliminadp con exito!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}else{
					$this->session->set_flashdata("error","Error al eliminar los datos!");
					redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
				}

			} */
		// Viejos

		public function guardar_extras(){
            $datos = $this->input->post();
            $resp = $this->Limpieza_Model->extrasCompra($datos);
            if($resp){
                $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","No se guardaron los datos!");
                redirect(base_url()."Limpieza/detalle_compra/".$datos['idCompraReturn']."/");
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
			$data['cuentas'] = $this->Limpieza_Model->listaCuentas();
			// echo json_encode($data['cuentas']);
			/* 
			$data["ultimoCuenta"] = $this->InsumosLab_Model->ultimaCuenta(); */

			$this->load->view("Base/header");
			$this->load->view("Limpieza/cuentas_salidas", $data);
			$this->load->view("Base/footer");
		}

		public function crear_cuenta(){
			$datos = $this->input->post();
			// echo json_encode($datos);
			$resp = $this->Limpieza_Model->crearCuenta($datos); // true si se realizo la consulta.
			if($resp){
				$this->session->set_flashdata("exito","La cuenta se creo con exito");
				redirect(base_url()."Limpieza/detalle_salidas/".$resp."/");
			}else{
				$this->session->set_flashdata("error","Error al crear la cuenta!");
				redirect(base_url()."Limpieza/descargos_insumos/");
			}
		}

		public function detalle_salidas($id = null){
			$data["cuenta"] = $id;
			$data["datosCuenta"] = $this->Limpieza_Model->obtenerDetalleCuenta($id);
			$data["filaCuenta"] = $this->Limpieza_Model->obtenerCuenta($id);
			$data["insumos"] = $this->Limpieza_Model->obtenerInsumosReactivos();
			
			$this->load->view("Base/header");
			$this->load->view("Limpieza/detalle_cuenta_descargos", $data);
			$this->load->view("Base/footer");

			// echo json_encode($data);
		}

		public function sacar_de_stock(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				$cuenta = array('cuentaActual' => $this->input->post("cuentaActual"), 'idInsumo' => $datos["idInsumo"], 'cantidad' => $datos["cantidadInsumo"], 
							   "por" => $this->session->userdata('id_usuario_h'), 'entregadoA' => $datos["entregadoA"]); // Para tabla isbm
				$data["cuenta"] = $cuenta;
				
				$resp = $this->Limpieza_Model->descontarInsumo($data);
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

		/* public function sacar_de_stock(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				
				$id = $datos["idInsumo"];
				$entregadoA = $datos["entregadoA"];
				$cantidad =$datos["cantidadInsumo"]; // Cantidad que se requiere de medicamento
				$insumo = $this->Limpieza_Model->buscarInsumo(trim($id));
				
				
				$cuenta = array('cuentaActual' => $this->input->post("cuentaActual"), 'idInsumo' => $insumo->idInsumoLimpieza, 'cantidad' => $cantidad, "por" => $this->session->userdata('id_usuario_h'), 'entregadoA' => $entregadoA); // Para tabla isbm
				// Para descontar de stock
				$ins = array('stock' => ($insumo->stockInsumoLimpieza - $cantidad), 'idInsumo' => $insumo->idInsumoLimpieza);
				
				$data["cuenta"] = $cuenta;
				$data["insumo"] = $ins;
				
				// echo json_encode($data);
				
				$resp = $this->Limpieza_Model->descontarInsumo($data);
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
		} */

		public function editar_insumo(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();

				// Preparando arreglo fila detalle
						$filaDetalle = array(
							'cantidad' => $datos["cantidad"], 
							'filaDetalle' => $datos["cuentaInsumo"], 
						); 
				
				$resp = $this->Limpieza_Model->editarFilaDescargo($filaDetalle);
				if($resp){
					echo "Bien";
				}else{
					echo "Error";
				}

			}
			else{
				echo "Error...";
			}

		}

		public function eliminar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				$resp = $this->Limpieza_Model->eliminarDetalle($datos);
				if($resp){
					echo "Exito...";
				}else{
					echo "Error...";
				}

			}
			else{
				echo "Error...";
			}
		}

		/* public function eliminar_medicamento(){
			if($this->input->is_ajax_request()){
				$datos =$this->input->post();
				$data["filaDetalle"] = $datos["filaCuenta"];
				$detalleFila = $this->Limpieza_Model->seleccionarDetalle($datos["filaCuenta"]);  // Detalle de la fila seleccionada.
				$insumo = $this->Limpieza_Model->buscarInsumo($detalleFila->idInsumo);  // Datos puntuales del medicamento.
				
				$stock["stock"] = $insumo->stockInsumoLimpieza + $detalleFila->cantidadInsumo;
				$stock["idInsumo"] = $detalleFila->idInsumo;
				
				$data["stock"] = $stock;
				
				
				$resp = $this->Limpieza_Model->eliminarDetalle($data);
				
				if($resp){
					echo "Exito...";
				}else{
					echo "Error...";
				}

			}
			else{
				echo "Error...";
			}
		} */

		public function cerrar_cuenta_descargos(){
			$datos = $this->input->post();
			$return = $datos["idCuentaEditar"];
			// echo json_encode($datos);
			$resp = $this->Limpieza_Model->cerrarCuentaDescargos($datos); // true si se realizo la consulta.
			
			if($resp){
				$this->session->set_flashdata("exito","La cuenta fue cerrada con exito!");
				redirect(base_url()."Limpieza/detalle_salidas/".$return."/");
			}else{
				$this->session->set_flashdata("error","Error al cerrar la cuenta!");
				redirect(base_url()."Limpieza/detalle_salidas/".$return."/");
			}
		}
	// Metodos para la gestion de salidas

	// Seguimiento de insumos de limpieza
		public function seguimiento_insumos(){

			if($this->input->post()){
                $datos =$this->input->post();
				$fecha = $datos["actualizadoInsumo"];
				$data["fecha"] = $fecha;
				$insumos = $this->Limpieza_Model->obtenerInsumos();
				foreach ($insumos as $insumo) {
					$compra = $this->Limpieza_Model->compraInsumo($insumo->idInsumoLimpieza, $fecha);
					$salidas = $this->Limpieza_Model->salidasInsumo($insumo->idInsumoLimpieza, $fecha);
					
					$mov = array('codigo' => $insumo->codigoInsumoLimpieza, 'insumo' => $insumo->nombreInsumoLimpieza, 'stock' => $insumo->stockInsumoLimpieza, 'stockPivote' => $insumo->stockPivote);
					if(is_null($compra)){
						$mov["compras"] = 0;
					}else{
						$mov["compras"] = $compra->totalCompras;
					}

					if(is_null($salidas)){
						$mov["salidas"] = 0;
					}else{
						$mov["salidas"] = $salidas->totalSalidas;
					}

					$data["insumos"][] = $mov;
					
					// echo $insumo->idInsumoLimpieza;
				}
				$this->load->view("Base/header");
				$this->load->view("Limpieza/seguimiento_insumos", $data);
				$this->load->view("Base/footer");
            }
            else{
				$dato = $this->Limpieza_Model->fechaPivote();
				$data["fecha"] = $dato->fechaPivote;
                $this->load->view("Base/header");
				$this->load->view("Limpieza/seguimiento_insumos", $data);
				$this->load->view("Base/footer");
            }

		}

		public function buscarDatos(){
			if($this->input->is_ajax_request()){
                $datos =$this->input->post();
				$codigoInsumo = $datos["codigoInsumo"];
				$actualizadoInsumo = $datos["actualizadoInsumo"];

                $data["insumo"] = $this->Limpieza_Model->buscarInsumoXC($codigoInsumo);
                $data["test"] = $this->Limpieza_Model->buscarInsumoXC($codigoInsumo);
                echo json_encode($data);
            }
            else{
                echo "Error...";
            }
		}
	// Seguimiento de insumos de limpieza
		
}
