<?php

// Libreria para impresora termica
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
    use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    use Mike42\Escpos\EscposImage;
// Libreria para impresora termica

// Crear QR
    use BaconQrCode\Renderer\ImageRenderer;
    use BaconQrCode\Renderer\RendererStyle\RendererStyle;
    use BaconQrCode\Renderer\Image\SvgImageBackEnd; // También puedes usar \ImagickImageBackEnd o \GdImageBackEnd
    use BaconQrCode\Writer;
// Crear QR

defined('BASEPATH') OR exit('No direct script access allowed');

class Facturas extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		$this->load->model("Facturacion_Model");
	}

	public function index(){}

	public function lista_opciones($params = null){
		$factura = unserialize(base64_decode(urldecode($params)));
		$detalle = $this->Facturacion_Model->obtenerDTE($factura["dte"], $factura["tipo"]);
		$jsonDTE = unserialize(base64_decode(urldecode($detalle->jsonDTE)));
		$ambiente = $jsonDTE["dteJson"]["identificacion"]["ambiente"];
		$codigo = $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"];
		$fecha = $jsonDTE["dteJson"]["identificacion"]["fecEmi"];

		$strUrl = 'https://admin.factura.gob.sv/consultaPublica?ambiente='.urlencode($ambiente).'&codGen='.urlencode($codigo).'&fechaEmi='.urlencode($fecha);

		$data["factura"] = $factura ;
		$data["url"] = urlencode(base64_encode(serialize($strUrl)));
		
		$this->load->view("Base/header");
		$this->load->view("Facturas/lista_opciones", $data);
		$this->load->view("Base/footer");
		
	}

	public function final_factura_electronica($dte = null, $mensaje = null){
            if($dte > 0){
                $datos = $this->Facturacion_Model->obtenerDTE($dte, 1);

                $data["datos"] = $datos;
                $data["datosLocales"] = $datos->datosLocales;
                $data["respuestaHacienda"] = $datos->respuestaHacienda;
                $data["jsonDTE"] = $datos->jsonDTE;
                $hacienda = null;
                $sello = null;
    
                if (!empty($datos->respuestaHacienda)) {
                    $hacienda = unserialize(base64_decode(urldecode($datos->respuestaHacienda)));
                    $sello = $hacienda->selloRecibido;
                }else{
                    $sello = "0";
                }

                $data["sello"] = $sello;
                
                $jsonDTE = unserialize(base64_decode(urldecode($datos->jsonDTE)));

                $identificacion = $jsonDTE["dteJson"]["identificacion"];
                $receptor = "FC-".$jsonDTE["dteJson"]["receptor"]["nombre"]."_".$datos->idDTEFC;


                // Creando registro de factura para control interno
                    $locales = unserialize(base64_decode(urldecode($datos->datosLocales)));
                    $controlFactura["clienteFactura"] = $locales["nombrePaciente"];
                    $controlFactura["duiFactura"] = @$locales["duiPaciente"];
                    $controlFactura["idHojaCobro"] = @$locales["idHoja"];
                    $controlFactura["numeroFactura"] = $locales["baseDTE"];
                    $controlFactura["tipoFactura"] = "1";
                    $controlFactura["fechaMostrar"] = $identificacion["fecEmi"];
                // Creando registro de factura para control interno
                
                // Parametro para URL
                    $ambiente = $identificacion["ambiente"];
                    $cg = $identificacion["codigoGeneracion"];
                    $fecEmi = $identificacion["fecEmi"];
                // Parametro para URL
    
                unset($datos->respuestaHacienda);
                unset($datos->datosLocales);
                unset($datos->jsonDTE);
                $strUrl = 'https://admin.factura.gob.sv/consultaPublica?ambiente='.urlencode($ambiente).'&codGen='.urlencode($cg).'&fechaEmi='.urlencode($fecEmi);
                // Contenidos para los tres QR
                    $qrs = array_filter([
                        $this->generarQr($strUrl),
                        $this->generarQr($cg),
                        $sello ? $this->generarQr($sello) : null
                    ]);

                    
                    $tablaQrs = '<table><tr>';
                    $flag = 0;
                    $str = "";
                    foreach ($qrs as $qr) {
                        $flag++;
                        switch ($flag) {
                            case '1':
                                $str ="Portal Ministerio de Hacienda";
                                break;
                            case '2':
                                $str ="Código generación";
                                break;
                            case '3':
                                $str ="Sello recibido";
                                break;
                            
                            default:
                            $str ="Portal Ministerio de Hacienda";
                                break;
                        }
                        $tablaQrs  .= '
                            <td style="text-align: center;">
                                '.$qr.'
                                <br>
                                <span style="font-size: 7px;">'.$str.'</span>
                            </td>
                            ';
                    }
                    $tablaQrs .= '</tr></table>';
                    $data["qrs"] = $tablaQrs;
                // Contenidos para los tres QR
                    
                    $mpdf = new \Mpdf\Mpdf([
                        'mode' => 'utf-8',
                        'format' => 'A4',
                        'margin_left' => 15,
                        'margin_right' => 15,
                        'margin_top' => 25,
                        'margin_bottom' => 25,
                        'margin_header' => 20,
                        'margin_footer' => 23
                    ]);
                    //$mpdf->setFooter('');
                    //$mpdf->SetProtection(array('print'));
                    $mpdf->SetTitle("Hospital La Familia");
                    $mpdf->SetAuthor("Edwin Orantes");
                    if($datos->estadoDTE == 0){
                        $mpdf->setWatermarkText('ANULADA'); // set the watermark
                        $mpdf->showWatermarkText = true;
                    }else{
                        $mpdf->showWatermarkText = false;
                    }
                    $mpdf->watermark_font = 'DejaVuSansCondensed';
                    $mpdf->watermarkTextAlpha = 0.1;
                    $mpdf->simpleTables = true;
                    $mpdf->packTableData = true;
    
                    $mpdf->SetDisplayMode('fullpage');
                    //$mpdf->AddPage('L'); //Voltear Hoja
                    $html = $this->load->view('FE/factura_pdf', $data ,true); // Cargando hoja de estilos
                    // $mpdf->SetHTMLHeader('');
                    $mpdf->WriteHTML($html);
                    // Nombre del archivo PDF basado en el JSON
                   
    
                    $mpdf->Output($receptor.'-FC.pdf', 'I');
    


            }else{
                $mh = unserialize(base64_decode(urldecode($mensaje))); // Mensaje de hacienda
                
                $estado = $mh->estado;
                $descripcionMsg = $mh->descripcionMsg;
                $descripcionMsg = $mh->descripcionMsg;
                $observaciones = $mh->observaciones;
                
                echo "<div style='margin: 0 auto; width: 500px; text-align: center'>";
                    echo "<h2 class=''>Advertencia</h2>
                        <table class='table table-bordered table-striped'>
                            <thead class=''>
                                <tr style='color: red'>
                                    <th>ESTADO</th>
                                    <th>".$estado."</th>
                                </tr>
                            </thead>
                            <tbody>";
                            echo "<tr><td colspan='2'><strong>Errores</strong></td></tr>";
                            $index = 1;
                        foreach ($observaciones as $row) {
                            echo "<tr><td><strong>$index</strong></td><td>$row</td></tr>";
                            $index++;
                        }
                    echo "  </tbody>
                            </table>
                            <button class='btn btn-primary' onclick='goBack()'>⬅️ Regresar</button>
                            <script>
                            function goBack() {
                                window.history.back();
                            }
                        </script>";
                echo "</div>";
            }
        }

	private function generarQr($contenido) {
        $renderer = new ImageRenderer(
            new RendererStyle(100), // Tamaño del QR (150x150)
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        // Generar el SVG como string
        $svg = $writer->writeString($contenido);
    
        // Eliminar la línea XML usando una expresión regular
        $svg = preg_replace('/<\?xml.*\?>/', '', $svg);
    
        return $svg;
    }

	

	public function ver_factura($factura = null){
		$detalle = $this->Facturas_Model->obtenerFactura($factura);
		$ruta_pdf = FCPATH . 'public/archivos_pdf/' . $detalle->nombreFactura.".pdf";

		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="' . $detalle->nombreFactura . '"');
		readfile($ruta_pdf);

	}

}

?>