<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

// Clases para el reporte en excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Herramientas extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Anuncio_Model");
        $this->load->model("Herramientas_Model");
        $this->load->model("Usuarios_Model");
        $this->load->model("Hoja_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function index(){
        $data["anuncios"] = $this->Anuncio_Model->obtenerAnuncios();
        $this->load->view("Base/header");
        $this->load->view("Herramientas/anuncios", $data);
        $this->load->view("Base/footer");

    }

    public function agregar_anuncio(){
        $data["listaUsuarios"] = $this->Anuncio_Model->obtenerUsuarios();
        $data["anuncios"] = $this->Anuncio_Model->obtenerAnuncios();
        $this->load->view("Base/header");
        $this->load->view("Herramientas/gestion_anuncios", $data);
        $this->load->view("Base/footer");

        // echo json_encode($data["usuarios"]);
    }

    public function guardar_anuncio(){
        $datos = $this->input->post();

        // Imagen principal
            $mi_archivo = 'imagenAnuncio';
            $config['upload_path'] =    "public/img/anuncios";
            $config['file_name'] = $datos["tituloAnuncio"];
            $config['allowed_types'] = "gif|jpg|png";
            $config['max_size'] = "50000";
            $config['max_width'] = "2000";
            $config['max_height'] = "2000";
            $config['overwrite'] = true;
        // Fin imagen principal

        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload($mi_archivo)) {
            //*** ocurrio un error
            $mensaje = "La imagen no se guardo....";
            $data['uploadError'] = $this->upload->display_errors();
            echo $this->upload->display_errors();
            return;
        }else{
            $data['uploadSuccess'] = $this->upload->data();
            $resp = $this->Anuncio_Model->guardarAnuncio($datos); //Guardar datos en db
            if($resp){
                $mensaje = "La imagen se guardo con exito!";
            }else{
                $mensaje = "La imagen no se guardo....";
            }
        }

        $this->session->set_flashdata("exito",$mensaje);
        redirect(base_url()."Herramientas/");
        
        // echo json_encode($datos);


    }

    public function eliminar_anuncio(){
        $datos = $this->input->post();
		$bool = $this->Anuncio_Model->eliminarAnuncio($datos);
		if($bool){
			$this->session->set_flashdata("exito","El anuncio fue eliminado con exito!");
			redirect(base_url()."Herramientas/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar el anuncio!");
			redirect(base_url()."Herramientas/");
		}

        // echo json_encode($datos);
    }

    public function factura_isbm(){
        $this->load->view("Base/header");
        $this->load->view("Herramientas/factura_isbm");
        $this->load->view("Base/footer");
    }

    public function guardar_factura(){
        if($this->input->is_ajax_request()){
            $total =$this->input->post("total");
            $data["fecha"] = $this->input->post("fecha");
            $data["contrato"] = $this->input->post("contrato");
            $data["total"] = $this->input->post("total");
            $data["gravada"] = $this->input->post("gravada");
            $data["retenido"] = $this->input->post("retenido");
            $data["subtotal"] = $this->input->post("subtotal");
            $data["vtotal"] = $this->input->post("vtotal");
            $data["concepto"] = $this->input->post("concepto");

            $letras = "";
            $arregloNumero = explode(".", $total);
            if(isset($arregloNumero[1])){
                 $letras = strtoupper($this->convertir($total)." ".$arregloNumero[1]."/100 Dolares");
            }else{
             $letras = strtoupper($this->convertir($total)." 00/100 Dolares");
            }

            $data["letras"] = $letras;

            /* $resp = $this->Honorarios_Model->saldarHonorario(trim($idHonorario));
            if($resp){
                echo "1";
            }else{
                echo "0";
            } */
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function basico($numero) {
        $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
        'nueve','diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
        'veinte', 'veintiuno', 'veintidos', 'veintitres', 'veinticuatro','veinticinco', 'veintiseis','veintisiete','veintiocho','veintinueve');
        return $valor[$numero-1];
    }
    
    public function decenas($n) {
        $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta', 70=>'setenta',80=>'ochenta',90=>'noventa');
        if( $n <= 29) return $this->basico($n);
        $x = $n % 10;
        if ( $x == 0 ) {
        return $decenas[$n];
        } else return $decenas[$n - $x].' y '.$this->basico($x);
    }
    
    public function centenas($n) {
        $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos', 400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
        700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
        if( $n >= 100) {
            if ( $n % 100 == 0 ) {
                return $cientos[$n];
            } else {
                $u = (int) substr($n,0,1);
                $d = (int) substr($n,1,2);
                return (($u == 1)?'ciento':$cientos[$u*100]).' '.$this->decenas($d);
            }
        } else return $this->decenas($n);
    }

    public function centenas_testing($n) {
        $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos', 400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
        700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
        if( $n >= 100) {
            if ( $n % 100 == 0 ) {
                echo  $cientos[$n];
            } else {
                $u = (int) substr($n,0,1);
                $d = (int) substr($n,1,2);
                echo (($u == 1)?'ciento':$cientos[$u*100]).' '.$this->decenas($d);
            }
        } else echo $this->decenas($n);
    }
    
    public function miles($n) {
        $arregloNumero = explode(".", $n);
        if(isset($arregloNumero[1])){
            unset($arregloNumero[1]);
            $n = implode($arregloNumero);
        }else{
            $n = $n;
        }
        
        if($n > 999) {
            if( $n == 1000) {return 'mil';}
            else {
                $l = strlen($n);
                $c = (int)substr($n,0,$l-3);
                $x = (int)substr($n,-3);
                if($c == 1) {$cadena = 'mil '.$this->centenas($x);}
                else if($x != 0) {$cadena = $this->centenas($c).' mil '.$this->centenas($x);}
                else $cadena = $this->centenas($c). ' mil';
                return $cadena;
            }
        } else return $this->centenas($n);
    }
    
    public function millones($n) {
        if($n == 1000000) {return 'un millon';}
        else {
            $l = strlen($n);
            $c = (int)substr($n,0,$l-6);
            $x = (int)substr($n,-6);
            if($c == 1) {
                $cadena = ' millon ';
            } else {
                $cadena = ' millones ';
            }
            return $this->miles($c).$cadena.(($x > 0)?$this->miles($x):'');
        }
    }
    
    public function convertir($n) {
        switch (true) {
        case ( $n >= 1 && $n < 30) : return $this->basico($n); break;
        case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
        case ( $n >= 100 && $n < 1000) : return $this->centenas($n); break;
        case ($n >= 1000 && $n <= 999999): return $this->miles($n); break;
        case ($n >= 1000000): return $this->millones($n);
        }
    }


    public function facturacion(){
        $anio = date("Y");
        $mes = date("m");
        $i = $anio."-".$mes."-01";
        $f = $anio."-".$mes."-31";
        $totalIngresos = 0;
        $totalAmbulatorio = 0;
        // Obteniendo Ingresos totales del mes
            $hojas = $this->Usuarios_Model->obtenerHojas($i, $f);
            /* foreach ($hojas as $hoja) {
				if($hoja->anulada == 0 && $hoja->correlativoSalidaHoja > 0){
					switch ($hoja->tipoHoja) {
						case 'Ingreso':
							// Detalles de la hoja
								$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
								foreach ($medicamentosHoja as $medicamento) {
									$totalIngresos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
								}
							break;
						case 'Ambulatorio':
							// Detalles de la hoja
								$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
								foreach ($medicamentosHoja as $medicamento) {
									$totalAmbulatorio += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
								}
							break;
						default:
							echo "Nel perrin, aqui no hay nada";
							break;
					}
				}
			} */
            $data["totalIngresos"] = $hojas->ingreso_ambulatorios + $hojas->ingreso_ingresos;
        // Fin obtener ingresos

        // Facturacion diaria
            $hoy = date('Y-m-d');
            $facturadoHoy = $this->Herramientas_Model->facturacionDiaria($hoy);
            $data["diario"] = $facturadoHoy->facturado;
        // Fin facturacion diaria

        //Facturacion mensual
            $anio = date('Y');
            $mes = date('m');
            $i = $anio."-".$mes."-01";
            $f = $anio."-".$mes."-31";
            $facturadoMes = $this->Herramientas_Model->facturadoMes($i, $f);
            $data["mes"] = $facturadoMes->facturado;
        // Fin facturacion mensual
        $fechas = $this->Herramientas_Model->fechasFactura();
        
        foreach ($fechas as $fecha) {
            $resumen['fechas'][] = $fecha->fechaFactura;
            $facturadoIn = $this->Herramientas_Model->facturacionDiaria($fecha->fechaFactura);
            $resumen['totales'][] = $facturadoIn->facturado;
        }
        $data['fecha_factura'] = json_encode($resumen['fechas']);
        $data['totales_factura'] = json_encode($resumen['totales']);

        // Fin facturacion por dia
        $this->load->view("Base/header");
        $this->load->view("Herramientas/facturacion", $data);
        $this->load->view("Base/footer");

        // echo json_encode($hojas);
    }

    public function reporte_facturas(){
        $datos = $this->input->post();
        if(sizeof($datos) > 0){
            if ($datos['fechaInicio'] > $datos['fechaFin']) {
				$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor fecha fin");
				redirect(base_url()."Herramientas/facturacion");
			}else{
                $i = $datos['fechaInicio'];
                $f = $datos['fechaFin'];
				$facturas = $this->Herramientas_Model->facturasExcel($i, $f);
                
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Detalle de facturas');
                $sheet->mergeCells('A1:F1'); // Combinando celdas
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
                $sheet->setCellValue('C2', 'Paciente');
                $sheet->setCellValue('D2', 'Factura');
                $sheet->setCellValue('E2', 'Total');
                $sheet->setCellValue('F2', 'Fecha');

                //FONT SIZE
                $sheet->getStyle('A1:F1')->getFont()->setSize(12);
                $sheet->getStyle('A2:F2')->getFont()->setSize(9);
                    
                $number = 1;
                $flag = 3;
                $totalFacturas = 0;
                $border = [
                    'borders' => [
                        'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $sheet->getStyle('A2:f2')->applyFromArray($border);
                foreach($facturas as $d){
                    $sheet->setCellValue('A'.$flag, $number);
                    $sheet->setCellValue('B'.$flag, $d->codigoHoja);
                    $sheet->setCellValue('C'.$flag, $d->nombrePaciente);
                    $sheet->setCellValue('D'.$flag, $d->numeroFactura);
                    $sheet->setCellValue('E'.$flag, $d->totalFactura);
                    $sheet->getStyle('E'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                    $sheet->setCellValue('F'.$flag, $d->fechaFactura);
                    
                    $sheet->getStyle('A'.$flag.':F'.$flag)->getFont()->setSize(9);
                    $sheet->getStyle('A'.$flag.':F'.$flag)->applyFromArray($border);

                    $flag = $flag+1;
                    $number = $number+1;
                    $totalFacturas += $d->totalFactura;
                }
                $sheet->setCellValue('E'.$flag, number_format($totalFacturas, 2));
                $sheet->getStyle('E'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $sheet->getStyle('A'.$flag.':E'.$flag)->applyFromArray($centrar); // Centrando elementos
                $sheet->getStyle('A'.$flag.':E'.$flag)->getFont()->setSize(9);
                $sheet->getStyle('A'.$flag.':F'.$flag++)->applyFromArray($border);
                
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
                $sheet->getStyle('A2:F2')->getFont()->setBold(true);		
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
                $curdate = date('d-m-Y H:i:s');
                $writer = new Xlsx($spreadsheet);
                $filename = 'listado_facturas_'.$curdate;
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
			
            }
        }else{
            $this->session->set_flashdata("error","No se seleccionaron datos");
			redirect(base_url()."Herramientas/facturacion");
        }
    }

    // Control de lo que se hace en las hojas de cobro
        public function movimientos_hojas(){
            $this->load->view("Base/header");
            $this->load->view("Herramientas/movimientos_hojas");
            $this->load->view("Base/footer");
        }

        public function buscarCuenta(){
            if($this->input->is_ajax_request()){
                $codigoCuenta =$this->input->post("codigoCuenta");
                $datos = $this->Herramientas_Model->detalleHoja($codigoCuenta);
                echo json_encode($datos);
            }
            else{
                echo "Error...";
            }
        }
    // Fin control

    // Subir imagenes
        public function subir_imagen(){
            $this->load->view("Base/header");
            $this->load->view("Herramientas/subir_imagen");
            $this->load->view("Base/footer");
        }

        public function mover_imagen(){
            $datos = $this->input->post();
            if(isset($datos["animacion"])){
                // echo json_encode($datos);
                // Imagen principal
                    $mi_archivo = 'imagenEvento';
                    $config['upload_path'] =    "public/img/adornos";
                    $config['file_name'] = "logo_animacion";
                    $config['allowed_types'] = "gif|jpg|png";
                    $config['max_size'] = "50000";
                    $config['max_width'] = "2000";
                    $config['max_height'] = "2000";
                    $config['overwrite'] = true;
                // Fin imagen principal
                //echo json_encode($config);
                $ani["nombre"] = $config["file_name"];
                $ani["fecha"] = $datos["fechaEvento"];;
                $ani["estado"] = 1;
                $ani["update"] = 1;


                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload($mi_archivo)) {
                    //*** ocurrio un error
                    $mensaje = "La imagen no se guardo....";
                    $data['uploadError'] = $this->upload->display_errors();
                    echo $this->upload->display_errors();
                    return;
                }else{
                    $data['uploadSuccess'] = $this->upload->data();
                    $resp = $this->Herramientas_Model->establecerAnimacion($ani);
                    if($resp){
                        $mensaje = "La imagen se guardo con exito!";
                    }else{
                        $mensaje = "La imagen no se guardo....";
                    }
                }
    
    
                $this->session->set_flashdata("exito",$mensaje);
                redirect(base_url()."Herramientas/subir_imagen");

            }else{
                if($datos["pivoteImagen"] == 1){
                    // Imagen principal
                        $mi_archivo = 'imagenPrincipal';
                        $config['upload_path'] =    "public/img";
                        $config['file_name'] = "logo_celebracion";
                        $config['allowed_types'] = "gif|jpg|png";
                        $config['max_size'] = "50000";
                        $config['max_width'] = "2000";
                        $config['max_height'] = "2000";
                        $config['overwrite'] = true;
                    // Fin imagen principal
                }else{
                    // Imagen principal
                        $mi_archivo = 'imagenFondo';
                        $config['upload_path'] =    "public/img";
                        $config['file_name'] = "fondo_navbar";
                        $config['allowed_types'] = "gif|jpg|png";
                        $config['max_size'] = "50000";
                        $config['max_width'] = "2000";
                        $config['max_height'] = "2000";
                        $config['overwrite'] = true;
                    // Fin imagen principal
                }
    
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload($mi_archivo)) {
                    //*** ocurrio un error
                    $data['uploadError'] = $this->upload->display_errors();
                    echo $this->upload->display_errors();
                    return;
                }
    
                $data['uploadSuccess'] = $this->upload->data();
    
                $this->session->set_flashdata("exito","La imagen se guardo con exito!");
                redirect(base_url()."Herramientas/subir_imagen");

            }

        }
    // Fin subir imagenes

    // Resumen de facturacion diario
        public function resumen_diario(){

            $totalIngresos = 0;
            $totalFacturado = 0;
            $ultimoCodigo = 0;
            $pivote = 10;
            
            if($this->input->post()){

                $pivote = 1;
                $input =$this->input->post();
                if ($input["primerSalida"] > $fin = $input["ultimaSalida"]) {
                    $this->session->set_flashdata("error","El codigo de inicio no puede ser mayor fecha fin");
                    redirect(base_url()."Herramientas/resumen_diario");
                }else{
                    $inicio = $input["primerSalida"];
                    $fin = $input["ultimaSalida"];
                    $hojas = $this->Herramientas_Model->despuesLiquidado($inicio, $fin, $pivote);
                    $data["fin"] = $inicio-1;   // Le sumamos 1 para obtener el inicio del dia
                }
            }else{
                $pivote = 2;
                $liquidado = $this->Herramientas_Model->ultimoLiquidado();
                $hojas = $this->Herramientas_Model->despuesLiquidado($liquidado->finExternoGenerado, 0 , $pivote);
                $data["fin"] = $liquidado->finExternoGenerado;   // Le sumamos 1 para obtener el inicio del dia
            }
            
            foreach ($hojas as $hoja) {
               
                $internos = $this->Hoja_Model->medicamentosHoja($hoja->idHoja);
                // Obteniendo el total de ingresos
                foreach ($internos as $interno) {
                    $totalIngresos += ($interno->cantidadInsumo * $interno->precioInsumo);
                }
                //Obteniendo el total de facturado
                // if($hoja->credito_fiscal != '' || $hoja->credito_fiscal != null || $hoja->credito_fiscal != 0){
                if($hoja->credito_fiscal > 0){
                    foreach ($internos as $interno) {
                        $totalFacturado += ($interno->cantidadInsumo * $interno->precioInsumo);
                    }
                }

                // Validando si no es un seguro
                if($hoja->seguroHoja == 5){
                    $externos = $this->Hoja_Model->externosHoja($hoja->idHoja);
                    foreach ($externos as $externo) {
                        $totalFacturado += ($externo->cantidadExterno * $externo->precioExterno);
                    }
                }
                $ultimoCodigo = $hoja->correlativoSalidaHoja;
            }
            
            $data["ultimoCodigo"] = $ultimoCodigo; // Ultimo codigo analizado
            // $data["inicio"] = $liquidado->inicioExternoGenerado;

            $data["totalIngresos"] = $totalIngresos; 
            $data["facturar"] = ($totalIngresos * 0.80); 
            $data["totalFacturado"] = $totalFacturado;
            
            $this->load->view("Base/header");
            $this->load->view("Herramientas/detalle_diario", $data);
            $this->load->view("Base/footer");
            
            // echo json_encode($hojas);

            
        }

        public function facturas_x_dia(){
            $datos = $this->input->post();
            // var_dump($datos);
            if(sizeof($datos) > 0){
                if ($datos['codigoInicio'] > $datos['codigoFin']) {
                    $this->session->set_flashdata("error","El codigo de inicio no puede ser mayor fecha fin");
                    redirect(base_url()."Herramientas/resumen_diario");
                }else{
                    $i = $datos['codigoInicio'];
                    $f = $datos['codigoFin'];
                    $facturas = $this->Herramientas_Model->facturasPorDia($i, $f);
                    
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', 'Detalle de facturas');
                    $sheet->mergeCells('A1:F1'); // Combinando celdas
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
                    $sheet->setCellValue('C2', 'Paciente');
                    $sheet->setCellValue('D2', 'Factura');
                    $sheet->setCellValue('E2', 'Total');
                    $sheet->setCellValue('F2', 'Fecha');

                    //FONT SIZE
                    $sheet->getStyle('A1:F1')->getFont()->setSize(12);
                    $sheet->getStyle('A2:F2')->getFont()->setSize(9);
                        
                    $number = 1;
                    $flag = 3;
                    $totalFacturas = 0;
                    $border = [
                        'borders' => [
                            'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ];
                    $sheet->getStyle('A2:f2')->applyFromArray($border);
                    foreach($facturas as $d){
                        // $codigo = 0;

                        switch ($d->numeroFactura) {
                            case ($d->numeroFactura > 0) && ($d->numeroFactura < 10):
                                $codigo = "00000".$d->numeroFactura;
                                break;
                            case ($d->numeroFactura >= 10) && ($d->numeroFactura < 100):
                                $codigo = "0000".$d->numeroFactura;
                                break;
                            case ($d->numeroFactura >= 100) && ($d->numeroFactura < 1000):
                                $codigo = "000".$d->numeroFactura;
                                break;
                            case ($d->numeroFactura >= 1000) && ($d->numeroFactura < 10000):
                                $codigo = "00".$d->numeroFactura;
                                break;
                            case ($d->numeroFactura >= 10000) && ($d->numeroFactura < 100000):
                                $codigo = "0".$d->numeroFactura;
                                break;
                            case ($d->numeroFactura >= 100000) && ($d->numeroFactura < 1000000):
                                $codigo = $d->numeroFactura;
                                break;
                            
                            default:
                                $codigo = "00000".$d->numeroFactura;
                                break;
                        }

                        $sheet->setCellValue('A'.$flag, $number);
                        $sheet->setCellValue('B'.$flag, $d->codigoHoja);
                        $sheet->setCellValue('C'.$flag, $d->nombrePaciente);
                        $sheet->setCellValue('D'.$flag, $codigo);
                        $sheet->setCellValue('E'.$flag, $d->totalFactura);
                        $sheet->getStyle('E'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                        $sheet->setCellValue('F'.$flag, $d->fechaFactura);
                        
                        $sheet->getStyle('A'.$flag.':F'.$flag)->getFont()->setSize(9);
                        $sheet->getStyle('A'.$flag.':F'.$flag)->applyFromArray($border);

                        $flag = $flag+1;
                        $number = $number+1;
                        $totalFacturas += $d->totalFactura;
                    }
                    $sheet->setCellValue('E'.$flag, number_format($totalFacturas, 2));
                    $sheet->getStyle('E'.$flag)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                    $sheet->getStyle('A'.$flag.':E'.$flag)->applyFromArray($centrar); // Centrando elementos
                    $sheet->getStyle('A'.$flag.':E'.$flag)->getFont()->setSize(9);
                    $sheet->getStyle('A'.$flag.':F'.$flag++)->applyFromArray($border);
                    
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
                    $sheet->getStyle('A2:F2')->getFont()->setBold(true);		
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
                    $curdate = date('d-m-Y H:i:s');
                    $writer = new Xlsx($spreadsheet);
                    $filename = 'listado_facturas_'.$curdate;
                    ob_end_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output');
                
                }
            }else{
                $this->session->set_flashdata("error","No se seleccionaron datos");
                redirect(base_url()."Herramientas/resumen_diario");
            }
        }
    // Fin resumen de facturacion diario
    
}