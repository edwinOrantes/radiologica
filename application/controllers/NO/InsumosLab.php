<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	// Clases para el reporte en excel
    /* Clases para excel */
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
    /* Fin clases */

class InsumosLab extends CI_Controller {
	/* Metodos para gestion de procesos de laboratorio */
        public function __construct(){
            parent::__construct();
            date_default_timezone_set('America/El_Salvador');
            if (!$this->session->has_userdata('valido')){
                $this->session->set_flashdata("error", "Debes iniciar sesión");
                redirect(base_url());
            }
            $this->load->model("InsumosLab_Model");  // Modelo para gestionar datos del laboratorio
            $this->load->model("Isbm_Model");
            $this->load->model("Laboratorio_Model");
            $this->load->model("Medico_Model");
        }

        public function index(){
            echo "Tas mal, aqui neles, nuay nada...";
        }

        public function agregar_compra(){
            $data["insumos"] = $this->InsumosLab_Model->obtenerInsumos();
            $data["proveedores"] = $this->InsumosLab_Model->obtenerProveedores();
            $this->load->view("Base/header");
            $this->load->view("InsumosLab/crear_compra", $data);
            $this->load->view("Base/footer");
        }

        public function guardar_compra(){
            $datos = $this->input->post();
            $resp = $this->InsumosLab_Model->guardarCompra($datos); // true si se realizo la consulta.
            if($resp > 0){
                $this->session->set_flashdata("exito","La factura fue creada con exito!");
                redirect(base_url()."InsumosLab/detalle_compra/".$resp."/");
            }else{
                $this->session->set_flashdata("error","Error al crear la factura!");
                redirect(base_url()."InsumosLab/lista_compras/");
            }
        }

        public function detalle_compra($id = null){
            $data["compra"] = $id;
            $data["compraCabecera"] = $this->InsumosLab_Model->detalleCabeceraCompra($id);
		    $data["detalleCL"] = $this->InsumosLab_Model->detalleContenidoCompra($id);
		    $data["listaIL"] = $this->InsumosLab_Model->obtenerInsumos();
		    $data["proveedores"] = $this->InsumosLab_Model->obtenerProveedores();

            // echo json_encode($data["compraCabecera"]);

            $this->load->view("Base/header");
            $this->load->view("InsumosLab/detalle_compra", $data);
            $this->load->view("Base/footer");
        }

        public function guardar_insumos_Laboratorio(){
            if($this->input->is_ajax_request()){
                $datos = $this->input->post();
                // Arreglo para informacion de insumos-compra
                    $datosCIL["idInsumoLab"] =  $datos["id"];
                    $datosCIL["facturaLab"] =  $datos["factura"];
                    $datosCIL["cantidadDetalleCL"] =  $datos["cantidad"];
                    $datosCIL["precioDetalleCL"] =  $datos["precio"];
                    $datosCIL["vencimientoDetalleCL"] =  $datos["vencimiento"];
                    $datosCIL["medidaDetalleCL"] =  $datos["medida"];
                    $datosCIL["descuentoDetalleCL"] =  $datos["descuento"];
                // Fin arreglo para informacion de insumos-compra

                $resp = $this->InsumosLab_Model->agregarDetalleCIL($datosCIL);
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

        public function guardar_extras(){
            $datos = $this->input->post();

            $resp = $this->InsumosLab_Model->extrasCompra($datos);
            if($resp){
                $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                redirect(base_url()."InsumosLab/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","No se guardaron los datos!");
                redirect(base_url()."InsumosLab/detalle_compra/".$datos['idCompraReturn']."/");
            }
        }

        public function actualizar_detalle_cl(){
            $datos = $this->input->post();
            // Arreglo para actualizar detalle de la compra
                $datosDC["cantidad"] = $datos["cantidadInsumo"];
                $datosDC["precio"] = $datos["precioILEdit"];
                $datosDC["descuento"] = $datos["descuentoILEdit"];
                $datosDC["fila"] = $datos["filaILEdit"];
            // Fin arreglo para actualizar detalle de la compra
            $data["detalle"] = $datosDC;
            // echo json_encode($data);
            $resp = $this->InsumosLab_Model->actualizarContenidoCompra($data); // true si se realizo la consulta.
            if($resp > 0){
                $this->session->set_flashdata("exito","Detalle actualizado con exito!");
                redirect(base_url()."InsumosLab/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","Error al actualizar los datos!");
                redirect(base_url()."InsumosLab/detalle_compra/".$datos['idCompraReturn']."/");
            }
        }

        public function eliminar_medicamento_cl(){
            $datos = $this->input->post();
            $data["detalle"] = $datos["idFila"];
            // echo json_encode($data);
            $resp = $this->InsumosLab_Model->eliminarContenidoCompra($data); // true si se realizo la consulta.
            if($resp > 0){
                $this->session->set_flashdata("exito","Detalle eliminadp con exito!");
                redirect(base_url()."InsumosLab/detalle_compra/".$datos['idCompraReturn']."/");
            }else{
                $this->session->set_flashdata("error","Error al eliminar los datos!");
                redirect(base_url()."InsumosLab/detalle_compra/".$datos['idCompraReturn']."/");
            }
        }

        private function respuestaConsulta($estado = null, $resp = null){
            $respuesta = array('estado' => $estado, 'respuesta' => $resp);
            header("content-type:application/json");
            print json_encode($respuesta);
        }

        public function lista_compras(){
            $data["compras"] = $this->InsumosLab_Model->listaCompras();
            $this->load->view("Base/header");
            $this->load->view("InsumosLab/lista_compras", $data);
            $this->load->view("Base/footer");
        }

        public function lista_insumos(){
            $data["insumos"] = $this->InsumosLab_Model->obtenerInsumos();
            $data["clasificacionRI"] = $this->InsumosLab_Model->clasificacionRI();

            // Creando codigo del medicamento
                $codigo = $this->InsumosLab_Model->obtenerCodigoIL();
                $str = explode("O", $codigo->codigoInsumoLab);
                $codigoIL = $str[1]+1;
                $data["codigoIL"] = $str[0]."O".$codigoIL;
            // Fin crear codigo del medicamento

            $this->load->view("Base/header");
            $this->load->view("InsumosLab/lista_insumos", $data);
            $this->load->view("Base/footer");
        }

        public function guardar_insumoslab(){
            $datos = $this->input->post();
            $datos["estado"] = 1;
            $resp = $this->InsumosLab_Model->guardarInsumoLab($datos); // true si se realizo la consulta.
            if($resp){
                $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                redirect(base_url()."InsumosLab/lista_insumos/");
            }else{
                $this->session->set_flashdata("error","No se guardaron los datos!");
                redirect(base_url()."InsumosLab/lista_insumos/");
            }
        }

        public function actualizar_insumoslab(){
            $datos = $this->input->post();
            
            $resp = $this->InsumosLab_Model->actualizarInsumosLab($datos); // 0 Si no se realizo la consulta.

            if($resp != "0"){
                $this->session->set_flashdata("exito","Los datos se actualizaron con exito");
                redirect(base_url()."InsumosLab/lista_insumos/");
            }else{
                $this->session->set_flashdata("error","No se actualizaron los datos!");
                redirect(base_url()."InsumosLab/lista_insumos/");
            }
        }

        public function eliminar_insumolab(){
            $data = $this->input->post();
            $resp = $this->InsumosLab_Model->eliminarInsumoLab($data);
            if($resp){
                $this->session->set_flashdata("exito","Los datos fueron eliminados con exito");
                redirect(base_url()."InsumosLab/lista_insumos/");
            }else{
                $this->session->set_flashdata("error","No se eliminaron los datos!");
                redirect(base_url()."InsumosLab/lista_insumos/");
            }
        }

        // Gestion de insumo de laboratorio
            public function gestion_insumos(){
                $data['cuentas'] = $this->InsumosLab_Model->obtenerCuentas();
		        $data["ultimoCuenta"] = $this->InsumosLab_Model->ultimaCuenta();

                $this->load->view("Base/header");
                $this->load->view("InsumosLab/cuenta_gestion_insumos", $data);
                $this->load->view("Base/footer");
            }

            public function crear_cuenta_insumos(){
                $datos = $this->input->post();
                $resp = $this->InsumosLab_Model->crearCuenta($datos); // true si se realizo la consulta.
                if($resp){
                    $this->session->set_flashdata("exito","La cuenta se creo con exito");
                    redirect(base_url()."InsumosLab/detalle_cuenta_insumos/".$resp."/");
                }else{
                    $this->session->set_flashdata("error","Error al crear la cuenta!");
                    redirect(base_url()."InsumosLab/gestion_insumos/");
                }
            }

            public function detalle_cuenta_insumos($id = null){
                $data["cuenta"] = $id;
                $data["datosCuenta"] = $this->InsumosLab_Model->obtenerDetalleCuenta($id);
                $data["filaCuenta"] = $this->InsumosLab_Model->obtenerCuenta($id);
                $data["insumos"] = $this->InsumosLab_Model->obtenerInsumosReactivos();

                $this->load->view("Base/header");
                $this->load->view("InsumosLab/detalle_cuenta_descargos", $data);
                $this->load->view("Base/footer");

                // echo json_encode($data["insumos"]);
            }

            public function cerrar_cuenta_descargos(){
                $datos = $this->input->post();
                $return = $datos["idCuentaEditar"];
                // echo json_encode($datos);
                $resp = $this->InsumosLab_Model->cerrarCuentaDescargos($datos); // true si se realizo la consulta.
                
                if($resp){
                    $this->session->set_flashdata("exito","La cuenta fue cerrada con exito!");
                    redirect(base_url()."InsumosLab/detalle_cuenta_insumos/".$return."/");
                }else{
                    $this->session->set_flashdata("error","Error al cerrar la cuenta!");
                    redirect(base_url()."InsumosLab/detalle_cuenta_insumos/".$return."/");
                }
            }


            public function sacar_de_stock(){
                if($this->input->is_ajax_request()){
                    $datos = $this->input->post();
                    $cuenta = array('cuentaActual' => $datos["cuentaActual"], 'idInsumo' => $datos["idInsumo"], 'cantidad' => $datos["cantidadInsumo"],
                                    'por' => $this->session->userdata('id_usuario_h'));
                    $data["cuenta"] = $cuenta;
                    $resp = $this->InsumosLab_Model->descontarInsumo($data);
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

            public function editar_Insumo(){
                if($this->input->is_ajax_request()){
                    $datos = $this->input->post();
                    // Preparando arreglo fila detalle
                        $filaDetalle = array(
                            'cantidad' => $datos["cantidad"], 
                            'filaDetalle' => $datos["cuentaInsumo"], 
                        );
                    // Fin prepara arreglo fila detalle
                    $resp = $this->InsumosLab_Model->editarFilaCuenta($filaDetalle);
                    if($resp){
                        echo "Bien";
                    }else{
                        echo "Error";
                    }
                    // echo json_encode($datos);
    
                }
                else{
                    echo "Error...";
                }
            }

            public function eliminar_medicamento(){
                if($this->input->is_ajax_request()){
                    $datos =$this->input->post();
                    $resp = $this->InsumosLab_Model->eliminarDetalle($datos);
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

            public function resumen_gestion($id = null){
                $data["insumos"] = $this->InsumosLab_Model->resumenCuentaGestion($id);
                
                // Reporte PDF
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
                    $mpdf->SetHTMLFooter('
                        <table width="100%">
                            <tr>
                                <td width="33%" style="font-weight: bold; font-size: 12px;">{PAGENO}/{nbpg}</td>
                                <td width="33%" align="center" style="font-weight: bold; font-size: 12px;"></td>
                                <td width="33%" style="font-weight: bold; font-size: 12px; text-align: right;">Requisición de medicamentos.</td>
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
                    $html = $this->load->view("InsumosLab/resumen_cuenta_pdf",$data,true); // Cargando hoja de estilos
                    $mpdf->WriteHTML($html);
                    $mpdf->Output('detalle_compra.pdf', 'I');
                    //$this->detalle_facturas_excell($inicio, $fin); // Fila para obtener el detalle en excel
                // Fin reporte PDF
            }
        // Fin gestion de insumo de laboratorio

        // Control de donantes
            public function donantes(){
                $data["donantes"] = $this->InsumosLab_Model->listaDonantes();
                $data["insumos"] = $this->InsumosLab_Model->listaInsumos();
                $this->load->view("Base/header");
                $this->load->view("InsumosLab/Donantes/lista_donantes", $data);
                $this->load->view("Base/footer");
            }

            public function guardar_donante(){
                $datos = $this->input->post();

                //Codigo del donante
                    $uc = 0;
                    $codigo = $this->InsumosLab_Model->obtenerCodigo();
                    if(!is_null($codigo)){
                        $uc = $codigo->codigoDonante + 1;
                    }else{
                        $uc = 1000;
                    }
                    $datos["codigoDonante"] = $uc;
                //Fin codigo del donante
                
                $resp = $this->InsumosLab_Model->guardarDonante($datos);
                if($resp > 0){
                    $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                    redirect(base_url()."InsumosLab/comprobante_donante/".$resp."/");
                }else{
                    $this->session->set_flashdata("error","No se guardaron los datos!");
                    redirect(base_url()."InsumosLab/donantes/");
                }
                // echo json_encode($datos);

            }

            public function comprobante_donante($id = null){
                $data['donante'] = $this->InsumosLab_Model->boletaDonante($id);
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
					$mpdf->SetHTMLFooter('');
					$mpdf->SetProtection(array('print'));
					$mpdf->SetTitle("Hospital Orellana, Usulutan");
					$mpdf->SetAuthor("Edwin Orantes");
					//$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
					$mpdf->showWatermarkText = true;
					$mpdf->watermark_font = 'DejaVuSansCondensed';
					$mpdf->watermarkTextAlpha = 0.1;
					$mpdf->SetDisplayMode('fullpage');
					// $mpdf->AddPage('L'); //Voltear Hoja

					$html = $this->load->view('InsumosLab/Donantes/boleta_donante_pdf', $data,true); // Cargando hoja de estilos

					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
                // Factura
                // echo json_encode($data);
            }

            public function detalle_donante($id = null){
                $data["cuenta"] = $id;
                $data["detalleCuenta"] = $this->InsumosLab_Model->detalleCuenta($id);
                $data["insumos"] = $this->InsumosLab_Model->obtenerInsumosReactivos();
                $data["examenes"] = $this->InsumosLab_Model->listaMedicamentos(1);

                // Obteniendo detalle de la cuenta
                    $data["detalle"] = $this->InsumosLab_Model->detalleDonante($id);
                    $data["examenesAgregados"] = $this->InsumosLab_Model->detalleExtraDonante($id);
                    // echo json_encode($data["detalle"]);
                // Fin detalle medicamentos
                $this->load->view("Base/header");
                $this->load->view("InsumosLab/Donantes/detalle_donantes", $data);
                $this->load->view("Base/footer");
            }

            public function lista_donantes(){
                $data["donantes"] = $this->InsumosLab_Model->listaDonantes();
                $this->load->view("Base/header");
                $this->load->view("InsumosLab/Donantes/lista_donantes", $data);
                $this->load->view("Base/footer");
            }

            public function nuevo_donativo(){
                $datos = $this->input->post();

                $resp = $this->InsumosLab_Model->nuevoDonativo($datos);
                if($resp > 0){
                    $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                    redirect(base_url()."InsumosLab/comprobante_donante/".$resp."/");
                }else{
                    $this->session->set_flashdata("error","No se guardaron los datos!");
                    redirect(base_url()."InsumosLab/donantes/");
                }
                
                // echo json_encode($datos);
            }

            public function editar_donante(){
                $datos = $this->input->post();
                $resp = $this->InsumosLab_Model->actualizarDatosDonante($datos);
                if($resp > 0){
                    $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                    redirect(base_url()."InsumosLab/donantes/");
                }else{
                    $this->session->set_flashdata("error","No se guardaron los datos!");
                    redirect(base_url()."InsumosLab/donantes/");
                }
                
                // echo json_encode($datos);
            }

            public function historial_donante($id = null){
                $data["cuenta"] = $id;
                $data["donante"] = $this->InsumosLab_Model->detalleCuenta($id);
                $data["historial"] = $this->InsumosLab_Model->seleccionarDetalleDonante($id);
                $this->load->view("Base/header");
                $this->load->view("InsumosLab/Donantes/detalle_donantes", $data);
                $this->load->view("Base/footer");

                // echo json_encode($data);
            }


            public function descontar_medicamento(){
                if($this->input->is_ajax_request()){
                    
                    $datos = $this->input->post();
    
                    $id =$datos["idInsumo"];
                    $cantidad =$datos["cantidadInsumo"]; // Cantidad que se requiere de medicamento
                    $insumo = $this->InsumosLab_Model->buscarInsumo(trim($id));
                    
                    $cuenta = array('cuentaActual' => $this->input->post("cuentaActual"), 'idInsumo' => $insumo->idInsumoLab, 'cantidad' => $cantidad, "por" => $this->session->userdata('id_usuario_h')); // Para tabla isbm
                    // Para descontar de stock
                    $med = array('stock' => ($insumo->stockInsumoLab - $cantidad), 'idInsumo' => $insumo->idInsumoLab);
                    
                    $data["cuenta"] = $cuenta;
                    $data["insumo"] = $med;

                    $resp = $this->InsumosLab_Model->descontarMedicamento($data);
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

            public function editar_medicamento(){
                if($this->input->is_ajax_request()){
                    $datos =$this->input->post();
                    $id = $datos["idMedicamento"]; //Medicamento en gestion.
                    $cantidad = $datos["cantidadActual"]; // Cantidad asignada antes del proceso.
                    $cantidadNueva = $datos["cantidad"]; // Nueva cantidad asignada.
                    $filaCuenta = $datos["cuentaMedicamento"]; // Nueva cantidad asignada.
                    $insumo = $this->InsumosLab_Model->buscarInsumo(trim($id)); // Buscando datos del medicamento.
                    // Preparando arreglo.
                        $med = array(
                                'stock' => (($insumo->stockInsumoLab + $cantidad) - $cantidadNueva), 
                                'idInsumo' => $insumo->idInsumoLab
                            );  // Volviendo a su estado original.
                    // Fin prepara arreglo
    
                    // Preparando arreglo fila detalle
                            $filaDetalle = array(
                                'cantidad' => $cantidadNueva, 
                                'filaDetalle' => $filaCuenta, 
                            );  // Volviendo a su estado original.
                    // Fin prepara arreglo fila detalle
    
                    $resp = $this->InsumosLab_Model->editarCuenta($med);
                    if($resp){
                        $resp2 = $this->InsumosLab_Model->editarFilaDonante($filaDetalle);
                        if($resp2){
                            echo "Bien";
                        }else{
                            echo "Mal";
                        }
                    }else{
                        echo "Error";
                    }
    
                }
                else{
                    echo "Error...";
                }
	
            }

            public function eliminar_medicamento_donante(){
                if($this->input->is_ajax_request()){
                    $datos =$this->input->post();
                    $data["filaDetalle"] = $datos["filaCuenta"];
                    $detalleFila = $this->InsumosLab_Model->seleccionarDetalleDonante($datos["filaCuenta"]);  // Detalle de la fila seleccionada.
                    $insumo = $this->InsumosLab_Model->buscarInsumo($detalleFila->idInsumo);  // Datos puntuales del medicamento.

                    $stock["stock"] = $insumo->stockInsumoLab + $detalleFila->cantidadInsumo;
                    $stock["idInsumo"] = $detalleFila->idInsumo;

                    $data["stock"] = $stock;

                    $resp = $this->InsumosLab_Model->eliminarDetalleDonante($data);
                    
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

            public function descontar_reactivos(){
                if($this->input->is_ajax_request()){
                    
                    $datos = $this->input->post();
                    $idExa =$datos["idExa"]; // Examen realizado
                    $cantidadExa =$datos["cantidadExa"]; // Cantidad del examen realizado
                    $cuentaActual =$datos["cuentaActual"]; // Cantidad del examen realizado
                    
                    $reactivo = array('cuentaActual' => $cuentaActual, 'idInsumo' => $idExa, 'cantidad' => $cantidadExa, "por" => $this->session->userdata('id_usuario_h'), 'pivote' => '1', 'examen' => $idExa);

                    // Recorriendo el vector de examenes
                        $stock = 0;
                        $resp = $this->Laboratorio_Model->examenReactivo($idExa); // Buscando los reactivos necesarios para este examen
                        $pivote = 0;
                        $tabla = 10;
                        
                        foreach ($resp as $fila) {
                            $stock = 0;
                            $reactivo["idInsumo"] = $fila->idReactivo;
                            $med =  $this->Laboratorio_Model->obtenerReactivo($fila->idReactivo); // Datos del reactivo
                            // Nuevo stock
                            $stock = ($med->stockInsumoLab - ($cantidadExa * $fila->medidaReactivo)); // Descontando del stock
                            $this->Laboratorio_Model->actualizarReactivos($stock, $fila->idReactivo, ($cantidadExa * $fila->medidaReactivo), $pivote, $tabla); // Actualizando el detalle del stock
                            $this->InsumosLab_Model->descontarReactivo($reactivo); // Actualizando el detalle del stock
                        }

                    // Fin recorrer el vector de examenes
    
                }
                else{
                    $respuesta = array('estado' => 0, 'respuesta' => 'Error');
                    header("content-type:application/json");
                    print json_encode($respuesta);
                }
            }

            public function borrar_examen(){
                if($this->input->is_ajax_request()){
                    $datos =$this->input->post();
                    $data["filaDetalle"] = $datos["flag"];
                    $detalleFila = $this->InsumosLab_Model->seleccionarExamenDonante($datos["flag"]);  // Detalle de la fila seleccionada.
                    $insumo = $this->InsumosLab_Model->buscarInsumo($detalleFila->idInsumo);  // Datos puntuales del medicamento.
                    
                    $stock["stock"] = $insumo->stockInsumoLab + $detalleFila->cantidadInsumo;
                    $stock["idInsumo"] = $detalleFila->idInsumo;
    
                    $data["stock"] = $stock;
                    
                    $resp = $this->InsumosLab_Model->eliminarDetalleED($data);
                    
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

        //Fin control donantes
        
        // Metodos para el control de fechas en reactivos
            private function guardarControles($data = null){
                if($data != null){
                    $reactivo = $data["idReactivo"];
                    // Consultando si hay en uso
                    $datosReactivo = $this->InsumosLab_Model->buscarReactivoEnUso($reactivo); // null si no encuentra nada

                    if($datosReactivo != null){
                        $fechaInicio = $datosReactivo->fechaInicio;
                        $params["fechaSalida"] = date("Y-m-d"); // Fecha de salida del uso anterior
                        $params["estado"] = 0; // Fecha de salida del uso anterior
                        $resumen = $this->InsumosLab_Model->obtenerResumenExamenes($reactivo, $fechaInicio, $params["fechaSalida"]);
                        $params["totalExamenes"] = $resumen->totalExamenes;
                        $params["filaReactivo"] = $datosReactivo->idControlR; // Fila a actualizar
                        $resp = $this->InsumosLab_Model->actualizarReactivoEnUso($params);
                        if($resp){
                            $this->InsumosLab_Model->guardarControles($data);
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        $this->InsumosLab_Model->guardarControles($data);
                        return true;
                    }
                }else{
                    return false;
                }
            }
        // Fin metodos para el control de fechas en reactivos

        // Gestion salidas de sangre
            public function salida_sangre(){
                $data["salidas"] = $this->InsumosLab_Model->listaSalidas();
                $data["insumos"] = $this->InsumosLab_Model->listaInsumos();
                $data["medicos"] = $this->Medico_Model->obtenerMedicos();
                $this->load->view("Base/header");
                $this->load->view("InsumosLab/Donantes/salidas_donantes", $data);
                $this->load->view("Base/footer");
                // echo json_encode($data);
            }

            public function guardar_salida(){
                $datos = $this->input->post();

                $resp = $this->InsumosLab_Model->nuevaSalida($datos);
                if($resp > 0){
                    $this->session->set_flashdata("exito","Los datos se guardaron con exito");
                    redirect(base_url()."InsumosLab/comprobante_salida/".$resp."/");
                }else{
                    $this->session->set_flashdata("error","No se guardaron los datos!");
                    redirect(base_url()."InsumosLab/donantes/");
                }
                
                // echo json_encode($datos);
            }

            public function comprobante_salida($id = null){
                $data['salida'] = $this->InsumosLab_Model->boletaSalida($id);
                // Factura
					$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
					$mpdf = new \Mpdf\Mpdf([
						'margin_left' => 15,
						'margin_right' => 15,
						'margin_top' => 10,
						'margin_bottom' => 10,
						'margin_header' => 10,
						'margin_footer' => 10
					]);
					//$mpdf->setFooter('{PAGENO}');
					$mpdf->SetHTMLFooter('');
					$mpdf->SetProtection(array('print'));
					$mpdf->SetTitle("Hospital Orellana, Usulutan");
					$mpdf->SetAuthor("Edwin Orantes");
					//$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
					$mpdf->showWatermarkText = true;
					$mpdf->watermark_font = 'DejaVuSansCondensed';
					$mpdf->watermarkTextAlpha = 0.1;
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->AddPage('L'); //Voltear Hoja

					$html = $this->load->view('InsumosLab/Donantes/boleta_salida_pdf', $data,true); // Cargando hoja de estilos

					$mpdf->WriteHTML($html);
					$mpdf->Output('detalle_compra.pdf', 'I');
                // Factura
                // echo json_encode($data);
            }

            public function actualizar_salida(){
                $datos = $this->input->post();
                $resp = $this->InsumosLab_Model->actualizarSalida($datos);
                if($resp > 0){
                    $this->session->set_flashdata("exito","Los datos se actualizaron con exito");
                    redirect(base_url()."InsumosLab/comprobante_salida/".$datos["fila"]."/");
                }else{
                    $this->session->set_flashdata("error","No se actualizaron los datos!");
                    redirect(base_url()."InsumosLab/donantes/");
                }
                
                // echo json_encode($datos);
            }
        // Gestion salidas de sangre

}
