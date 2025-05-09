<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Importar la clase Curl
    use Curl\Curl;
// Importar la clase Curl

// Mailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
// Mailer

// Crear QR
    use BaconQrCode\Renderer\ImageRenderer;
    use BaconQrCode\Renderer\RendererStyle\RendererStyle;
    use BaconQrCode\Renderer\Image\SvgImageBackEnd; // También puedes usar \ImagickImageBackEnd o \GdImageBackEnd
    use BaconQrCode\Writer;
// Crear QR

// Libreria para impresora termica
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
    use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    use Mike42\Escpos\EscposImage;
// Libreria para impresora termica

class Facturacion extends CI_Controller {
    // Declarar una variable global
    protected $urlHacienda;
    private $tokenHacienda;

    private $establecimiento;
    private $psPublica;

	public function __construct(){
        parent::__construct();
        $this->load->helper('download'); // Cargar helper de descarga
        $this->load->helper('file'); // Cargar el helper de archivos
        date_default_timezone_set('America/El_Salvador');
        if (!$this->session->has_userdata('valido')){
            $this->session->set_flashdata("error", "Debes iniciar sesión");
            redirect(base_url());
        }
        $this->load->model("Facturacion_Model");
        $this->load->model("Consulta_Model");

        // $this->urlHacienda = "https://apitest.dtes.mh.gob.sv/fesv/";
        $this->urlHacienda = "https://api.dtes.mh.gob.sv/fesv/"; // URL produccion
        $this->establecimiento = "S001";
        $this->psPublica = "UnionMedica_25";

        $this->tokenHacienda = $this->Facturacion_Model->obtenerToken(); // Obtener el token y asignarlo a la variable de clase
    }

        public function index(){
            if (!$this->tokenHacienda) {
                echo "Error al obtener el token.";
                return;
            }else{
                redirect(base_url()."Ventas/");
            }

            // echo "Token disponible: " . $this->tokenHacienda;


            /* $timestamp = 1740451324;
            if ($timestamp > 9999999999) { // Si el timestamp tiene más de 10 dígitos, está en milisegundos
                $timestamp = intval($timestamp / 1000);
            }
            echo date('Y-m-d H:i:s', $timestamp); */



            // echo json_encode($token);

            
        }

    
    // Pivote manejo de facturas
        public function procesar_factura($params = null){
            $datos = $this->input->post();
            $pivote = $datos["documentoPago"];
            $params = urlencode(base64_encode(serialize($datos)));
                        
           
            switch ($pivote) {
                case '2':
                    redirect(base_url()."Facturacion/sellar_factura/".$params."/");
                    break;
                case '3':
                    redirect(base_url()."Facturacion/credito_fiscal/".$params."/");
                    break;
                case '22':
                    redirect(base_url()."Facturacion/consumidor_final_solicitado/".$params."/");
                    break;
                
                default:
                    # code...
                    break;
            } 

            // echo json_encode($datos);

        }

        
        public function fin_factura_electronica($dte = null, $mensaje = null){
            if($dte > 0){
                $datos = $this->Facturacion_Model->obtenerDTE($dte, 1);
                $data["datos"] = $datos;
                $data["datosLocales"] = $datos->datosLocales;
                $datosLocales = unserialize(base64_decode(urldecode($datos->datosLocales)));
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
        
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 25,
                    'margin_bottom' => 25,
                    'margin_header' => 20,
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
                $html = $this->load->view('FE/factura_pdf', $data ,true); // Cargando hoja de estilos
                $mpdf->SetHTMLHeader('');
                $mpdf->WriteHTML($html);
                // Nombre del archivo PDF basado en el JSON
                // Guardar el PDF en el servidor
                    $rutaArchivo = FCPATH."public/archivos_pdf/".$receptor.".pdf";
                    $pdfContent = $mpdf->Output('', 'S'); // 'S' devuelve el PDF como string
                    file_put_contents($rutaArchivo, $pdfContent);
                // Guardar el PDF en el servidor

                // Guardar el JSON en el servidor
                    $rutaJson = FCPATH."public/archivos_pdf/".$receptor.".json";
                    $jsonPaciente = unserialize(base64_decode(urldecode($this->crear_json($jsonDTE))));
                    file_put_contents($rutaJson, json_encode($jsonPaciente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                // Guardar el JSON en el servidor

                
                $mpdf->Output($receptor.'-FC.pdf', 'I');
               /*  if($mensaje == "V"){
                    $mpdf->Output($receptor.'-FC.pdf', 'I');

                }else{
                    $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);
                    redirect(base_url()."Ventas/");
                } */

                /* 
                $this->recibo_hoja($datos->idHoja); */

            }else{
                
                echo urldecode($mensaje);
            }
        }
        
        public function fin_ccf($dte = null, $mensaje = null){
            if($dte > 0){
                $datos = $this->Facturacion_Model->obtenerDTE($dte, 3);
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
                $receptor = "CCF-".$jsonDTE["dteJson"]["receptor"]["nombre"]."_".$datos->idDTEFC;

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
        
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 25,
                    'margin_bottom' => 25,
                    'margin_header' => 20,
                    'margin_footer' => 23
                    ]);
                //$mpdf->setFooter('');
                //$mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Hospital Orellana, Usulutan");
                $mpdf->SetAuthor("Edwin Orantes");
                if($datos->estadoDTE == 0){
                    $mpdf->setWatermarkText('ANULADA'); // set the watermark
                    $mpdf->showWatermarkText = true;
                }else{
                    $mpdf->showWatermarkText = false;
                }
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->SetDisplayMode('fullpage');
                //$mpdf->AddPage('L'); //Voltear Hoja 
                $html = $this->load->view('FE/ccf_pdf', $data ,true); // Cargando hoja de estilos
                $mpdf->SetHTMLHeader('');
                $mpdf->WriteHTML($html);


                // Nombre del archivo PDF basado en el JSON
                    // Guardar el PDF en el servidor
                    $rutaArchivo = FCPATH."public/archivos_pdf/".$receptor.".pdf";
                    $pdfContent = $mpdf->Output('', 'S'); // 'S' devuelve el PDF como string
                    file_put_contents($rutaArchivo, $pdfContent);
                // Guardar el PDF en el servidor

                // Guardar el JSON en el servidor
                    $rutaJson = FCPATH."public/archivos_pdf/".$receptor.".json";
                    $jsonPaciente = unserialize(base64_decode(urldecode($this->crear_json($jsonDTE))));
                    file_put_contents($rutaJson, json_encode($jsonPaciente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                // Guardar el JSON en el servidor

                /* 
                $mpdf->Output($receptor.'-CCF.pdf', 'I');

                // echo json_encode($datos);

                $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);
                $this->recibo_hoja($datos->idHoja); */

                if($mensaje == "V"){
                    $mpdf->Output($receptor.'-FC.pdf', 'I');
                }else{
                    $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);
                    redirect(base_url()."Ventas/");
                }


            }else{
                $arrayStr = $frutas = explode("-", $mensaje);
                $mh = unserialize(base64_decode(urldecode($arrayStr[1]))); // Mensaje de hacienda
                echo $mh;
            }

            // echo $strUrl;
        

        }
        
        public function fin_nota_credito($dte = null, $mensaje = null){
            if($dte > 0){

                $datos = $this->Facturacion_Model->obtenerDTE($dte, 5);
                $ccf = $this->Facturacion_Model->obtenerCCF($dte, 1); // 2 - Pivote es Nota de credito
                $ccf_json = unserialize(base64_decode(urldecode($ccf->jsonDTE)));
                $data["ccf"] = $ccf_json["dteJson"]["identificacion"];
                $data["datos"] = $datos;
                $data["datosLocales"] = $datos->datosLocales;
                $data["respuestaHacienda"] = $datos->respuestaHacienda;
                $data["jsonDTE"] = $datos->jsonDTE;

                $hacienda = unserialize(base64_decode(urldecode($datos->respuestaHacienda)));
                $jsonDTE = unserialize(base64_decode(urldecode($datos->jsonDTE)));

                $identificacion = $jsonDTE["dteJson"]["identificacion"];
                $receptor = "NC-".$jsonDTE["dteJson"]["receptor"]["nombre"]."_".$datos->idDTEFC;

                unset($datos->respuestaHacienda);
                unset($datos->datosLocales);
                unset($datos->jsonDTE);
                $strUrl = 'https://admin.factura.gob.sv/consultaPublica?ambiente='.urlencode($hacienda->ambiente).'&codGen='.urlencode($hacienda->codigoGeneracion).'&fechaEmi='.urlencode($identificacion["fecEmi"]);
                // Contenidos para los tres QR
                    $qrs = [
                        $this->generarQr($strUrl),
                        $this->generarQr($hacienda->codigoGeneracion),
                        $this->generarQr($hacienda->selloRecibido)
                    ];
                    
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
                                <span style="font-size: 7px;">'.$str.'</span>
                            </td>
                            ';
                    }
                    $tablaQrs .= '</tr></table>';
                    $data["qrs"] = $tablaQrs;
                // Contenidos para los tres QR
        
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 25,
                    'margin_bottom' => 25,
                    'margin_header' => 20,
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
                $html = $this->load->view('FE/nc_pdf', $data ,true); // Cargando hoja de estilos
                $mpdf->SetHTMLHeader('');
                $mpdf->WriteHTML($html);
                // Nombre del archivo PDF basado en el JSON
                    // Guardar el PDF en el servidor
                    $rutaArchivo = FCPATH."public/archivos_pdf/".$receptor.".pdf";
                    $pdfContent = $mpdf->Output('', 'S'); // 'S' devuelve el PDF como string
                    file_put_contents($rutaArchivo, $pdfContent);
                // Guardar el PDF en el servidor

                // Guardar el JSON en el servidor
                    $rutaJson = FCPATH."public/archivos_pdf/".$receptor.".json";
                    $jsonPaciente = unserialize(base64_decode(urldecode($this->crear_json($jsonDTE))));
                    file_put_contents($rutaJson, json_encode($jsonPaciente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                // Guardar el JSON en el servidor

                $mpdf->Output($receptor.'-NC.pdf', 'I');

              
                // $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);
            }else{
                $arrayStr = $frutas = explode("-", $mensaje);
                $mh = unserialize(base64_decode(urldecode($arrayStr[1]))); // Mensaje de hacienda
                echo $mh;
            }

            // echo $strUrl;
        

        }
        
        public function fin_nota_debito($dte = null, $mensaje = null){
            if($dte > 0){

                $datos = $this->Facturacion_Model->obtenerDTE($dte, 6); // Datos del DTE de nota de credito
                $ccf = $this->Facturacion_Model->obtenerCCF($dte, 2); // 2 - Pivote es Nota de debito
                $ccf_json = unserialize(base64_decode(urldecode($ccf->jsonDTE)));
                $data["ccf"] = $ccf_json["dteJson"]["identificacion"];
                $data["datos"] = $datos;
                $data["datosLocales"] = $datos->datosLocales;
                $data["respuestaHacienda"] = $datos->respuestaHacienda;
                $data["jsonDTE"] = $datos->jsonDTE;

                $hacienda = unserialize(base64_decode(urldecode($datos->respuestaHacienda)));
                $jsonDTE = unserialize(base64_decode(urldecode($datos->jsonDTE)));

                $identificacion = $jsonDTE["dteJson"]["identificacion"];
                $receptor = "ND-".$jsonDTE["dteJson"]["receptor"]["nombre"]."_".$datos->idDTEFC;

                unset($datos->respuestaHacienda);
                unset($datos->datosLocales);
                unset($datos->jsonDTE);
                $strUrl = 'https://admin.factura.gob.sv/consultaPublica?ambiente='.urlencode($hacienda->ambiente).'&codGen='.urlencode($hacienda->codigoGeneracion).'&fechaEmi='.urlencode($identificacion["fecEmi"]);
                // Contenidos para los tres QR
                    $qrs = [
                        $this->generarQr($strUrl),
                        $this->generarQr($hacienda->codigoGeneracion),
                        $this->generarQr($hacienda->selloRecibido)
                    ];
                    
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
                                <span style="font-size: 7px;">'.$str.'</span>
                            </td>
                            ';
                    }
                    $tablaQrs .= '</tr></table>';
                    $data["qrs"] = $tablaQrs;
                // Contenidos para los tres QR
        
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 25,
                    'margin_bottom' => 25,
                    'margin_header' => 20,
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
                $html = $this->load->view('FE/nd_pdf', $data ,true); // Cargando hoja de estilos
                $mpdf->SetHTMLHeader('');
                $mpdf->WriteHTML($html);
                
                // Nombre del archivo PDF basado en el JSON
                    // Guardar el PDF en el servidor
                    $rutaArchivo = FCPATH."public/archivos_pdf/".$receptor.".pdf";
                    $pdfContent = $mpdf->Output('', 'S'); // 'S' devuelve el PDF como string
                    file_put_contents($rutaArchivo, $pdfContent);
                // Guardar el PDF en el servidor

                // Guardar el JSON en el servidor
                    $rutaJson = FCPATH."public/archivos_pdf/".$receptor.".json";
                    $jsonPaciente = unserialize(base64_decode(urldecode($this->crear_json($jsonDTE))));
                    file_put_contents($rutaJson, json_encode($jsonPaciente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                // Guardar el JSON en el servidor

                $mpdf->Output($receptor.'-ND.pdf', 'I');

                $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);

            }else{
                $arrayStr = $frutas = explode("-", $mensaje);
                $mh = unserialize(base64_decode(urldecode($arrayStr[1]))); // Mensaje de hacienda
                echo $mh;
            }

            // echo $strUrl;
        

        }

        public function fin_se($dte = null, $mensaje = null){
            if($dte > 0){
                $datos = $this->Facturacion_Model->obtenerDTE($dte, 14);
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
                $receptor = "SE-".$jsonDTE["dteJson"]["sujetoExcluido"]["nombre"]."_".$datos->idDTEFC;

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
                                '.$qr.' <br>
                                <span style="font-size: 7px;">'.$str.'</span>
                            </td>
                            ';
                    }
                    $tablaQrs .= '</tr></table>';
                    $data["qrs"] = $tablaQrs;
                // Contenidos para los tres QR
        
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 25,
                    'margin_bottom' => 25,
                    'margin_header' => 20,
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
                $html = $this->load->view('FE/se_pdf', $data ,true); // Cargando hoja de estilos
                $mpdf->SetHTMLHeader('');
                $mpdf->WriteHTML($html);
                
                
                // Nombre del archivo PDF basado en el JSON
                    // Guardar el PDF en el servidor
                    $rutaArchivo = FCPATH."public/archivos_pdf/".$receptor.".pdf";
                    $pdfContent = $mpdf->Output('', 'S'); // 'S' devuelve el PDF como string
                    file_put_contents($rutaArchivo, $pdfContent);
                // Guardar el PDF en el servidor

                // Guardar el JSON en el servidor
                    $rutaJson = FCPATH."public/archivos_pdf/".$receptor.".json";
                    $jsonPaciente = unserialize(base64_decode(urldecode($this->crear_json($jsonDTE))));
                    file_put_contents($rutaJson, json_encode($jsonPaciente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                // Guardar el JSON en el servidor

                $mpdf->Output($receptor.'-SE.pdf', 'I');
                // $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);
                @$this->recibo_hoja($datos->idHoja);

                if($datos->idHoja > 0){
                    $this->imprimir_ticket($datos->idHoja, $data["jsonDTE"]);
                }

                // echo "Orale wachin";
            }else{
                echo $mensaje;
            }

            // echo $strUrl;
        

        }
        
    // Pivote manejo de facturas
    
    // Credito fiscal desde cero
        public function lista_ccf(){
            $data["lista"] = $this->Facturacion_Model->listaCCF();
            $this->load->view('Base/header');
            $this->load->view('FE/lista_ccf', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

         public function agregar_ccf(){
            $tipo = 3; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            $partesDTE = explode('.', $strDte);


            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);

            
            $this->load->view('Base/header');
            $this->load->view('FE/agregar_credito_fiscal', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        
        }

        public function crear_estructura_ccf(){
            $datos = $this->input->post();
            
            $contingencia = 0;
            $modelo = 1;

            // Agregando o actualizando datos de el anexo de facturacion
                $pivote = $datos["idAnexo"];
                $datosAnexo["idDepartamento"] = $datos["departamentoPaciente"];
                $datosAnexo["idMunicipio"] = $datos["municipioPaciente"];
                $datosAnexo["nombrePaciente"] = $datos["nombrePaciente"];
                $datosAnexo["duiPaciente"] = $datos["documentoPaciente"];
                $datosAnexo["telefonoPaciente"] = $datos["telefonoPaciente"];
                $datosAnexo["correoPaciente"] = $datos["correoPaciente"];
                $datosAnexo["codigoDepartamento"] = $datos["codigoDepartamento"];
                $datosAnexo["codigoMunicipio"] = $datos["codigoMunicipio"];
                $datosAnexo["direccionPaciente"] = $datos["complementoPaciente"];
                $datosAnexo["actividadEconomica"] = $datos["codigoActividadEconomica"];
                $datosAnexo["tipoDocumento"] = $datos["tipoDocumento"];
                $datosAnexo["nrcCreditoFiscal"] = $datos["nrcPaciente"];
                if($pivote == 0){
                    $datosAnexo["pivote"] = 0;
                }else{
                    $datosAnexo["idAnexo"] = $datos["idAnexo"];
                    $datosAnexo["pivote"] = 1;
                }
                $this->Facturacion_Model->guardarProveedorAnexo($datosAnexo);
            // Agregando o actualizando datos de el anexo de facturacion
            
            // Validando DTE
                $tipo = 3;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE


            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                    if($strCodigo->codigo != 0){
                    $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion
           
            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                $arrayDTE["idHoja"] = 0;
                // $arrayDTE["generacion"] = $datos["cGeneracion"];
            // Datos a insertar en la base de datos
           
           
            if(!isset($datos["tipoContingencia"])){
                $datos["tipoContingencia"] = null;
            }else{
                $contingencia = 1;
                $modelo = 2;
            }

            $empresa = $this->Facturacion_Model->obtenerEmpresa();

            //Precios
                $subtotal = $datos["precioServicio"];
                $iva = $datos["ivaServicio"];
                $total = $datos["totalServicio"];
            //Precios
            
            $montoTotal = $total;
            $arregloNumero = explode(".", round($montoTotal, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($montoTotal)." 00/100 Dolares"); 
            }

            $codigoDepartamento = explode( "-", $datos["codigoDepartamento"]);
            $codigoMunicipio = explode( "-", $datos["codigoMunicipio"]);
            $actividadEconomica = explode( "-", $datos["codigoActividadEconomica"]);

            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 3,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "03",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => $modelo,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "tipoContingencia" => is_null($datos["tipoContingencia"]) ?  null : intval($datos["tipoContingencia"]),
                        "motivoContin" => null,
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                    ),
                    "documentoRelacionado" => null,
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "nombreComercial" => $empresa->nombreEmpresa,
                        "tipoEstablecimiento" => "02",
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,
                        "codEstableMH" => "P001",
                        "codEstable" => null,
                        "codPuntoVentaMH" => "M001",
                        "codPuntoVenta" => null
                    ),

                    "receptor" => array(
                        "nit" => $datos["documentoPaciente"],
                        "nrc" => $datos["nrcPaciente"],
                        "nombre" => $datos["nombrePaciente"],
                        "codActividad" => trim($actividadEconomica[0]),
                        "descActividad" => trim($actividadEconomica[1]),
                        "nombreComercial" => null,
                        "direccion" => array(
                            "departamento" => $codigoDepartamento[0],
                            "municipio" => $codigoMunicipio[0],
                            "complemento" => $datos["complementoPaciente"],
                        ),
                        "telefono" => $datos["telefonoPaciente"],
                        "correo" => $datos["correoPaciente"],
                    ),
                    "otrosDocumentos" => null,
                    "ventaTercero" => null,
                    "cuerpoDocumento" => array(
                        array(
                            "numItem" => 1,
                            "tipoItem" => 2,
                            "numeroDocumento" => null,
                            "codigo" => "1", // Convertido en string
                            "codTributo" => null,
                            "descripcion" => $datos["detalleServicio"],
                            "cantidad" => 1,
                            "uniMedida" => 59,
                            "precioUni" => (float)$subtotal,
                            "montoDescu" => 0,
                            "ventaNoSuj" => 0,
                            "ventaExenta" => 0,
                            "ventaGravada" => (float)$subtotal,
                            "tributos" => array(
                                "20"
                            ),
                            "psv" => 0,
                            "noGravado" => 0,

                        )
                    ),
                    "resumen" => array(
                        "totalNoSuj" => 0,
                        "totalExenta" => 0,
                        "totalGravada" => (float)$subtotal,
                        "subTotalVentas" => (float)$subtotal,
                        "descuNoSuj" => 0,
                        "descuExenta" => 0,
                        "descuGravada" => 0,
                        "porcentajeDescuento" => 0,
                        "totalDescu" => 0,
                        "tributos" => [
                            [
                                "codigo" => "20",
                                "descripcion" => "Impuesto Al valor agregado 13%",
                                "valor" => (float)$iva,
                            ],
                        ],
                        "subTotal" => (float)$subtotal,
                        "ivaPerci1" => 0,
                        "ivaRete1" => 0,
                        "reteRenta" => 0,
                        "montoTotalOperacion" => (float)$total,
                        "totalNoGravado" => 0,
                        "totalPagar" => (float)$total,
                        "totalLetras" => $letras,
                        "saldoFavor" => 0,
                        "condicionOperacion" => 1,
                        "pagos" => null,
                        "numPagoElectronico" => null,

                    ),
                    "extension" => null,
                    "apendice" => null,
                )
            );


            
            if($contingencia == 0){
                $this->envioHaciendaCCF($factura, $empresa, $datos, $arrayDTE, 2, 3);
            }else{
                 $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                 $arrayDTE["paramsHacienda"] = "";
                 $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                 $arrayDTE["contingencia"] = $contingencia; // Datos de sistema local
                 
                 // Existe contingencia, el documento solo se firmara y guardara
                 $documentoFirmado = json_decode($this->firmar_DTE($factura));
 
                 if($documentoFirmado->status == "OK"){
                     $arrayDTE["firma"] = $documentoFirmado->body; // Datos de sistema local
                     
                     $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 51);
 
                     $msg = "El documento se firmo exitosamente";
                     redirect(base_url()."Facturacion/fin_ccf/$pivoteDTE/$msg/");
 
 
                 }else{
                     $this->session->set_flashdata("error","El documento no puede ser firmado");
                     redirect(base_url()."Hoja/detalle_hoja/$hoja/");
 
                }

                //  echo json_encode($arrayDTE);
 
 
            }

            // echo json_encode($arrayDTE);
            
            
        }

    // Credito fiscal desde cero

    // Anular credito fiscal
        public function anular_ccf($dte = null){
            $tipo = 3;

            $datosDte = $this->Facturacion_Model->obtenerDTE($dte, $tipo);

            $data["dteAnular"] = $dte;
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);
            $data["datosLocales"] = unserialize(base64_decode(urldecode($datosDte->datosLocales)));
            $data["respuestaHacienda"] = unserialize(base64_decode(urldecode($datosDte->respuestaHacienda)));
            $data["jsonAnterior"] = $datosDte->jsonDTE;
            $jsonDTE = unserialize(base64_decode(urldecode($datosDte->jsonDTE)));
            
            $data["identificacion"] = $jsonDTE["dteJson"]["identificacion"];
            $data["emisor"] = $jsonDTE["dteJson"]["emisor"];
            $data["receptor"] = $jsonDTE["dteJson"]["receptor"];
            $data["cuerpoDocumento"] = $jsonDTE["dteJson"]["cuerpoDocumento"];
            $data["resumen"] = $jsonDTE["dteJson"]["resumen"];
            
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            $partesDTE = explode('.', $strDte);
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);

            if (array_key_exists("actividadEconomica", $data["datosLocales"])) {
                $data["datosLocales"]["codigoActividadEconomica"] = $data["datosLocales"]["actividadEconomica"];
                unset($data["datosLocales"]["actividadEconomica"]);
            }
            
            
            $this->load->view('Base/header');
            $this->load->view('FE/anular_ccf', $data);
            $this->load->view('Base/footer');
            


            // echo json_encode($data["datosLocales"]);

        }

        public function procesar_anulacion_ccf(){
            $datos = $this->input->post();
            $dteAnular = $datos["idDTEAnular"];
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            $tipoAnulacion = $datos["tipoAnulacion"]; // 1-Nuevo documento; 2-Anular documento
            $jsonAnterior = unserialize(base64_decode(urldecode($datos["jsonAnterior"])));
            $generacion = $jsonAnterior["dteJson"]["identificacion"]["codigoGeneracion"] ;
            
            
            if($tipoAnulacion == 1){

                // Datos a insertar en la base de datos
                    $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                    $arrayDTE["anioDTE"] = date("Y");
                    $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                    $arrayDTE["idHoja"] = 0;
                    $arrayDTE["dtePadre"] = $datos["idDTEAnular"];
                // Datos a insertar en la base de datos
                
                
                if(!isset($datos["tipoContingencia"])){
                    $datos["tipoContingencia"] = null;
                }
                
                //Precios
                    $subtotal = $datos["precioServicio"];
                    $iva = $datos["ivaServicio"];
                    $total = $datos["totalServicio"];
                //Precios
                $montoTotal = $total;
                $arregloNumero = explode(".", round($montoTotal, 2));
                $letras = "";
                if(isset($arregloNumero[1])){
                    $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
                }else{
                    $letras = strtoupper($this->convertir($montoTotal)." 00/100 Dolares"); 
                }

                $codigoDepartamento = explode( "-", $datos["codigoDepartamento"]);
                $codigoMunicipio = explode( "-", $datos["codigoMunicipio"]);
                $actividadEconomica = explode( "-", $datos["codigoActividadEconomica"]);

                $direccion = (!empty($codigoDepartamento[0]) && !empty($codigoMunicipio[0]) && !empty($datos["complementoPaciente"])) 
                ? array(
                    "departamento" => $codigoDepartamento[0],
                    "municipio" => $codigoMunicipio[0],
                    "complemento" => (!empty($datos["complementoPaciente"])) ? $datos["complementoPaciente"] : null,
                ) 
                : null;
                    
                $factura = array(
                    "nit" => $empresa->nitEmpresa,
                    "activo" => true, // Booleano sin comillas
                    "passwordPri" => $this->psPublica,
                    "dteJson" => array(
                        "identificacion" => array(
                            "version" => 3,
                            "ambiente" => $empresa->ambiente,
                            "tipoDte" => "03",
                            "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                            "codigoGeneracion" => $datos["cGeneracion"],
                            "tipoModelo" => 1,
                            "tipoOperacion" => intval($datos["tipoTransmision"]),
                            "tipoContingencia" => $datos["tipoContingencia"],
                            "motivoContin" => null,
                            "fecEmi" => date("Y-m-d"),
                            "horEmi" => date("H:i:s"),
                            "tipoMoneda" => "USD",
                        ),
                        "documentoRelacionado" => null,
                        "emisor" => array(
                            "nit" => $empresa->nitEmpresa,
                            "nrc" => $empresa->nrcEmpresa,
                            "nombre" => $empresa->nombreEmpresa,
                            "codActividad" => $empresa->codActividadEmpresa,
                            "descActividad" => $empresa->descActividadEmpresa,
                            "nombreComercial" => $empresa->nombreEmpresa,
                            "tipoEstablecimiento" => "02",
                            "direccion" => array(
                                "departamento" => $empresa->departamento,
                                "municipio" => $empresa->municipio,
                                "complemento" => $empresa->direccionEmpresa,
                            ),
                            "telefono" => $empresa->telefonoEmpresa,
                            "correo" => $empresa->correoEmpresa,
                            "codEstableMH" => "P001",
                            "codEstable" => null,
                            "codPuntoVentaMH" => "M001",
                            "codPuntoVenta" => null
                        ),

                        "receptor" => array(
                            "nit" => $datos["documentoPaciente"],
                            "nrc" => $datos["nrcPaciente"],
                            "nombre" => $datos["nombrePaciente"],
                            "codActividad" => trim($actividadEconomica[0]),
                            "descActividad" => trim($actividadEconomica[1]),
                            "nombreComercial" => null,
                            "direccion" => array(
                                "departamento" => $codigoDepartamento[0],
                                "municipio" => $codigoMunicipio[0],
                                "complemento" => $datos["complementoPaciente"],
                            ),
                            "telefono" => $datos["telefonoPaciente"],
                            "correo" => $datos["correoPaciente"],
                        ),
                        "otrosDocumentos" => null,
                        "ventaTercero" => null,
                        "cuerpoDocumento" => array(
                            array(
                                "numItem" => 1,
                                "tipoItem" => 2,
                                "numeroDocumento" => null,
                                "codigo" => "1", // Convertido en string
                                "codTributo" => null,
                                "descripcion" => "MEDICAMENTOS E INSUMOS MÉDICOS",
                                "cantidad" => 1,
                                "uniMedida" => 59,
                                "precioUni" => (float)$subtotal,
                                "montoDescu" => 0,
                                "ventaNoSuj" => 0,
                                "ventaExenta" => 0,
                                "ventaGravada" => (float)$subtotal,
                                "tributos" => array(
                                    "20"
                                ),
                                "psv" => 0,
                                "noGravado" => 0,

                            )
                        ),
                        "resumen" => array(
                            "totalNoSuj" => 0,
                            "totalExenta" => 0,
                            "totalGravada" => (float)$subtotal,
                            "subTotalVentas" => (float)$subtotal,
                            "descuNoSuj" => 0,
                            "descuExenta" => 0,
                            "descuGravada" => 0,
                            "porcentajeDescuento" => 0,
                            "totalDescu" => 0,
                            "tributos" => [
                                [
                                    "codigo" => "20",
                                    "descripcion" => "Impuesto Al valor agregado 13%",
                                    "valor" => (float)$iva,
                                ],
                            ],
                            "subTotal" => (float)$subtotal,
                            "ivaPerci1" => 0,
                            "ivaRete1" => 0,
                            "reteRenta" => 0,
                            "montoTotalOperacion" => (float)$total,
                            "totalNoGravado" => 0,
                            "totalPagar" => (float)$total,
                            "totalLetras" => $letras,
                            "saldoFavor" => 0,
                            "condicionOperacion" => 1,
                            "pagos" => null,
                            "numPagoElectronico" => null,

                        ),
                        "extension" => null,
                        "apendice" => null,
                    )
                );

                // Limpiando datos
                    unset($datos["identificacionAnterior"]);
                    unset($datos["emisorAnterior"]);
                    unset($datos["receptorAnterior"]);
                    unset($datos["cuerpoAnterior"]);
                    unset($datos["resumenAnterior"]);
                    unset($datos["rhAnterior"]);
                    unset($datos["jsonAnterior"]);
                // Limpiando datos
                
                
                $resp = $this->envioHaciendaCCF($factura, $empresa, $datos, $arrayDTE, $tipoAnulacion, 33);
                $datosNuevaFactura = unserialize(base64_decode(urldecode($resp)));
                
                // Si habra nuevo documento
                $this->procesarAnulacionCCF($empresa, $datos["cGeneracion"],  $datosNuevaFactura, $jsonAnterior, $dteAnular, $tipoAnulacion, $datos);
                
                // echo json_encode($factura); 
                
            }else{
                // echo "estamos aca";
                $this->procesarAnulacionCCF($empresa, $generacion,  $datosNuevaFactura = null, $jsonAnterior, $dteAnular, $tipoAnulacion, $datos);
            }

            //  echo json_encode($tipoAnulacion);

        }

        public function procesarAnulacionCCF($empresa = null, $generacion = null,  $datosNuevaFactura = null, $jsonAnterior = null, $dteAnular = null, $pivoteAnulacion = null, $datos = null){
            $codigoGeneracionAnular = $this->codigo_generacion();
            // Creando factura
                
                $factura = array(
                    "nit" => $empresa->nitEmpresa,
                    "activo" => true, // Booleano sin comillas
                    "passwordPri" => $this->psPublica,
                    "dteJson" => array(
                        "identificacion" => array(
                            "version" => 2,
                            "ambiente" => $empresa->ambiente,
                            "codigoGeneracion" => $codigoGeneracionAnular,
                            "fecAnula" => date("Y-m-d"),
                            "horAnula" => date("H:i:s"),
                            
                        ),
                        "emisor" => array(
                            "nit" => $empresa->nitEmpresa,
                            "nombre" => $empresa->nombreEmpresa,
                            "tipoEstablecimiento" => "02",
                            "nomEstablecimiento" => $empresa->nombreEmpresa,
                            "codEstableMH" => "P001",
                            "codEstable" => null,
                            "codPuntoVentaMH" => "M001",
                            "codPuntoVenta" => null,
                            "telefono" => $empresa->telefonoEmpresa,
                            "correo" => $empresa->correoEmpresa,
                        ),
                        "documento" => array(
                            "tipoDte" => $jsonAnterior["dteJson"]["identificacion"]["tipoDte"],
                            "codigoGeneracion" => $jsonAnterior["dteJson"]["identificacion"]["codigoGeneracion"],
                            "selloRecibido" => $jsonAnterior["selloRecibido"],
                            "numeroControl" => $jsonAnterior["dteJson"]["identificacion"]["numeroControl"],
                            "fecEmi" => $jsonAnterior["dteJson"]["identificacion"]["fecEmi"],
                            "montoIva" => $jsonAnterior["dteJson"]["resumen"]["tributos"][0]["valor"],
                            "codigoGeneracionR" => null,
                            "tipoDocumento" => "36",
                            "numDocumento" => $jsonAnterior["dteJson"]["receptor"]["nit"],
                            "nombre" => $jsonAnterior["dteJson"]["receptor"]["nombre"],
                            "telefono" => $jsonAnterior["dteJson"]["receptor"]["telefono"],
                            "correo" => $jsonAnterior["dteJson"]["receptor"]["correo"]
                        ),

                        "motivo" => array(
                            "tipoAnulacion" => intval($datos["tipoAnulacion"]),
                            "motivoAnulacion" => $datos["detalleAnulacion"],
                            "nombreResponsable" => $this->session->userdata('nombreEmpleado'),
                            "tipDocResponsable" => "13",
                            "numDocResponsable" => $this->session->userdata('duiEmpleado'),
                            "nombreSolicita" => $jsonAnterior["dteJson"]["receptor"]["nombre"],
                            "tipDocSolicita" => "36",
                            "numDocSolicita" => $jsonAnterior["dteJson"]["receptor"]["nit"]
                        ),

                    )
                );
            // Creando factura

            // echo json_encode($factura);

            if($pivoteAnulacion == 1){
                $factura["dteJson"]["documento"]["codigoGeneracionR"] = $generacion;
            }
            


            // Firmando documento
                $documentoFirmado = json_decode($this->firmar_DTE($factura));
            // Firmando documento
            
            // echo json_encode($factura);
            
            if($documentoFirmado->status == "OK"){
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."anulardte"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producciónñ
                        "idEnvio"       => time(), // Identificador de envío único
                        "version"       => 2, // Versión del JSON del DTE
                        "documento"     => $documentoFirmado->body, // Documento firmado
                    ];
                    // "codigoGeneracion" => $codigoGeneracionAnular // Código de generación
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                $mensaje = $response->descripcionMsg;
                
                // echo json_encode($response );
                
                if($response->estado == "PROCESADO"){
                
                    $curl->close();
                    $nuevoDTE = $datosNuevaFactura["filaDB"];
                    $resp = $this->Facturacion_Model->anularDTECCF($dteAnular, $nuevoDTE, $pivoteAnulacion);
                    if($pivoteAnulacion == 1){
                        redirect(base_url()."Facturacion/fin_ccf/$nuevoDTE/$mensaje/");
                    }else{
                        $this->session->set_flashdata("exito","DTE anulado con exito");
                        redirect(base_url()."Facturacion/lista_ccf");
                    }

                }else{
                    $curl->close();
                    // $this->session->set_flashdata("error","DTE no pudo ser anulado");
                    redirect(base_url()."Facturacion/fin_ccf/0/$mensaje/");
                }

            }else{
                $this->session->set_flashdata("error","No se ha podido firmar el documento");
                redirect(base_url()."Facturacion/anular_factura/$dteAnular/");
            }

            // echo json_encode($factura);

        }

    // Anular credito fiscal

    // Credito fiscal
        public function credito_fiscal($params = null){
            $datos = unserialize(base64_decode(urldecode($params)));
            $totalVenta = 0;
            $totalIva = 0;

            // Para venta 
                $venta["codigoVenta"] = $datos["codigoVenta"];
                $venta["fechaVenta"] = $datos["fechaVenta"];
                $venta["clienteVenta"] = $datos["clienteVenta"];
                $venta["documentoPago"] = $datos["documentoPago"];
                $venta["numeroDocumento"] = $datos["numeroDocumento"];
                $venta["formaPago"] = $datos["formaPago"];
                $venta["txtSubtotal"] = $datos["txtSubtotal"];
                $venta["txtIVA"] = $datos["txtIVA"];
                $venta["txtTotal"] = $datos["txtTotal"];
                $venta["txtDineroRecibido"] = $datos["txtDineroRecibido"];
                $venta["txtVuelto"] = $datos["txtVuelto"];
            // Para venta 


            // Ordenando datos
                if(isset($datos["idMedicamento"])){
                    // Creando Json de medidas
                        // Crear un arreglo combinado
                        $datosInsumo = array();
                        // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                        $numElementos = count($datos["idMedicamento"]);
                        // Iterar sobre la longitud de las matrices y combinar los datos
                        $index = 1;
                        for ($i = 0; $i < $numElementos; $i++) {

                            $objeto = array(
                                "descripcion" => $datos["nombreMedicamento"][$i]."(".$datos["nombreInsumo"][$i].")",
                                "idMedicamento" => $datos["idMedicamento"][$i],
                                "precio" => $datos["precios"][$i],
                                "cantidad" => $datos["cantidad"][$i],
                                "unitaria" => $datos["cantidadUnitaria"][$i],
                                "medida" => $datos["nombreInsumo"][$i]
                            );
                            // Agregar el arreglo al arreglo combinado
                            array_push($datosInsumo, $objeto);
                            $index++;
                        }
                        unset($datos["idMedicamento"], $datos["precios"], $datos["cantidad"], $datos["cantidadUnitaria"], $datos["nombreInsumo"], $datos["nombreMedicamento"]);
                       
                        $data["insumos"] = $datosInsumo;
                    // Creando Json de medidas

                }else{
                    $data["insumos"] = "";
                }

            // Ordenando datos

            // $tipoDocumento = $datos["codigoCatDetalle"];
            $tipo = 3; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            // Partir la cadena usando el punto como delimitador
            $partesDTE = explode('.', $strDte);

            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["idHoja"] = 0;
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);
            $data["venta"] = $venta;

            
            $this->load->view('Base/header');
            $this->load->view('FE/credito_fiscal', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        
        }

        public function actualizar_facturacion_ccf(){
            if($this->input->is_ajax_request()){
                $datos =$this->input->post();
                $idPaciente = $datos["idPaciente"];
                // unset($datos["idPaciente"]);
                $resp = $this->Facturacion_Model->validarAnexo($idPaciente);
                $datos["existe"] = $resp->existe;

                $bool = $this->Facturacion_Model->guardarAnexo($datos);
                if($bool){
                    $respuesta = array('estado' => 1, 'respuesta' => 'Exito');
                    header("content-type:application/json");
                    print json_encode($respuesta);
                }else{
                    $respuesta = array('estado' => 0, 'respuesta' => 'Error');
                    header("content-type:application/json");
                    print json_encode($respuesta);
                    }
            // print json_encode($datos);
            }
            else
            {
                echo "Error...";
            }
        }

        public function sellar_ccf(){
            $datos = $this->input->post();
            $parametrosFactura = unserialize(base64_decode(urldecode($datos["parametrosFactura"])));
            $datosVenta = unserialize(base64_decode(urldecode($datos["venta"]))); // Datos a insertar en la venta
            $insumos = $parametrosFactura["insumos"];

            $contingencia = 0;
            $modelo = 1;
            $totalIva = 0;
            $totalVenta = 0;
            $subtotal = 0;
            unset($datos["parametrosFactura"]);

            // Ordenando datos para factura
                if(isset($datos["detalleServicio"])){
                    // Creando Json de medidas
                        // Crear un arreglo combinado
                        $datosInsumo = array();
                        // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                        $numElementos = count($datos["detalleServicio"]);
                        // Iterar sobre la longitud de las matrices y combinar los datos
                        $index = 1;
                        for ($i = 0; $i < $numElementos; $i++) {

                            // $totalIva += (float)$datos["ivaFila"][$i];

                            $totalVenta += (float)$datos["totalBruto"][$i];

                            $subtotal += (float)$datos["totalNeto"][$i];

                            // Crear un arreglo asociativo para cada conjunto de datos
                            $objeto = array(
                                "numItem" => $index,
                                "tipoItem" => 2,
                                "numeroDocumento" => null,
                                "codigo" => null, // Convertido en string
                                "codTributo" => null,
                                "descripcion" => $datos["detalleServicio"][$i],
                                "cantidad" => intval($datos["cantidadServicio"][$i]),
                                "uniMedida" => 59,
                                "precioUni" => (float)round($datos["precioServicio"][$i], 5),
                                "montoDescu" => 0,
                                "ventaNoSuj" => 0,
                                "ventaExenta" => 0,
                                "ventaGravada" => (float)round($datos["totalNeto"][$i], 5),
                                "tributos" => array(
                                    "20"
                                ),
                                "psv" => 0,
                                "noGravado" => 0,


                            );

                            // Agregar el arreglo al arreglo combinado
                            array_push($datosInsumo, $objeto);
                            $index++;
                        }
                        unset($datos["cantidadServicio"], $datos["detalleServicio"], $datos["precioServicio"], $datos["totalNeto"], $datos["totalBruto"], $datos["ivaFila"]);
                    // Creando Json de medidas

                    $totalIva = $subtotal* 0.13;

                }
            // Ordenando datos para factura


            // Agregando o actualizando datos de el anexo de facturacion
                $pivote = $datos["idAnexo"];
                $datosAnexo["idDepartamento"] = $datos["departamentoPaciente"];
                $datosAnexo["idMunicipio"] = $datos["municipioPaciente"];
                $datosAnexo["nombrePaciente"] = $datos["nombrePaciente"];
                $datosAnexo["duiPaciente"] = $datos["documentoPaciente"];
                $datosAnexo["telefonoPaciente"] = $datos["telefonoPaciente"];
                $datosAnexo["correoPaciente"] = $datos["correoPaciente"];
                $datosAnexo["codigoDepartamento"] = $datos["codigoDepartamento"];
                $datosAnexo["codigoMunicipio"] = $datos["codigoMunicipio"];
                $datosAnexo["direccionPaciente"] = $datos["complementoPaciente"];
                $datosAnexo["actividadEconomica"] = $datos["codigoActividadEconomica"];
                $datosAnexo["tipoDocumento"] = $datos["tipoDocumento"];
                $datosAnexo["nrcCreditoFiscal"] = $datos["nrcPaciente"];
                if($pivote == 0){
                    $datosAnexo["pivote"] = 0;
                }else{
                    $datosAnexo["idAnexo"] = $datos["idAnexo"];
                    $datosAnexo["pivote"] = 1;
                }
                $this->Facturacion_Model->guardarProveedorAnexo($datosAnexo);
            // Agregando o actualizando datos de el anexo de facturacion

            $hoja = $parametrosFactura["idHoja"];

            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                
            // Datos a insertar en la base de datos
            
            if(isset($datos["tipoContingencia"]) && $datos["tipoContingencia"] == "" ){
                $datos["tipoContingencia"] = null;
            }else{
                $contingencia = 1;
                $modelo = 2;
            }

            // Validando DTE
                $tipo = 3;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE

            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                    if($strCodigo->codigo != 0){
                    $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion

            // Guardando venta
                    $venta = $this->Ventas_Model->validarNumeroActual($datos["baseDTE"], 3); //Verificando que el codigo del tiket no haya sido agregado
                    $paramsVenta["insumos"] = $insumos;
                    if($venta->codigo > 0){
                        $maximoActual = $this->Ventas_Model->maximoActual(3);
                        $datosVenta["numeroDocumento"] = $maximoActual->codigo + 1;
                    }else{
                        $datosVenta["numeroDocumento"] = $datos["baseDTE"];
                    }
                    $paramsVenta["datosVenta"] = $datosVenta;
                    
                    $resp = $this->Ventas_Model->guardarVentaCF($paramsVenta);
                    $arrayDTE["idHoja"] = $resp;
            // Guardando venta
            
            $empresa = $parametrosFactura["empresa"];
            $arregloNumero = explode(".", round($totalVenta, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($totalVenta)." 00/100 Dolares"); 
            }

            $codigoDepartamento = explode( "-", $datosAnexo["codigoDepartamento"]);
            $codigoMunicipio = explode( "-", $datosAnexo["codigoMunicipio"]);
            $actividadEconomica = explode( "-", $datosAnexo["actividadEconomica"]);

            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 3,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "03",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => $modelo,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "tipoContingencia" => is_null($datos["tipoContingencia"]) ?  null : intval($datos["tipoContingencia"]),
                        "motivoContin" => null,
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                    ),
                    "documentoRelacionado" => null,
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "nombreComercial" => $empresa->nombreEmpresa,
                        "tipoEstablecimiento" => "02",
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,
                        "codEstableMH" => "P001",
                        "codEstable" => null,
                        "codPuntoVentaMH" => "M001",
                        "codPuntoVenta" => null
                    ),

                    "receptor" => array(
                        "nit" => $datosAnexo["duiPaciente"],
                        "nrc" => $datosAnexo["nrcCreditoFiscal"],
                        "nombre" => $datosAnexo["nombrePaciente"],
                        "codActividad" => trim($actividadEconomica[0]),
                        "descActividad" => trim($actividadEconomica[1]),
                        "nombreComercial" => null,
                        "direccion" => array(
                            "departamento" => $codigoDepartamento[0],
                            "municipio" => $codigoMunicipio[0],
                            "complemento" => $datosAnexo["direccionPaciente"],
                        ),
                        "telefono" => $datosAnexo["telefonoPaciente"],
                        "correo" => $datosAnexo["correoPaciente"],
                    ),
                    "otrosDocumentos" => null,
                    "ventaTercero" => null,
                    "cuerpoDocumento" => $datosInsumo,
                    "resumen" => array(
                        "totalNoSuj" => 0,
                        "totalExenta" => 0,
                        "totalGravada" => (float)round($subtotal, 2),
                        "subTotalVentas" => (float)round($subtotal, 2),
                        "descuNoSuj" => 0,
                        "descuExenta" => 0,
                        "descuGravada" => 0,
                        "porcentajeDescuento" => 0,
                        "totalDescu" => 0,
                        "tributos" => [
                            [
                                "codigo" => "20",
                                "descripcion" => "Impuesto Al valor agregado 13%",
                                "valor" => (float)round($totalIva, 2),
                            ],
                        ],
                        "subTotal" => (float)round($subtotal, 2),
                        "ivaPerci1" => 0,
                        "ivaRete1" => 0,
                        "reteRenta" => 0,
                        "montoTotalOperacion" => (float)round($totalVenta, 2),
                        "totalNoGravado" => 0,
                        "totalPagar" => (float)round($totalVenta, 2),
                        "totalLetras" => $letras,
                        "saldoFavor" => 0,
                        "condicionOperacion" => 1,
                        "pagos" => null,
                        "numPagoElectronico" => null,

                    ),
                    "extension" => null,
                    "apendice" => null,
                )
            );

            if($contingencia == 0){
                $this->envioHaciendaCCF($factura, $empresa, $datos, $arrayDTE, 2, 3);
            }else{
                $arrayDTE["cGeneracion"] = $datos["cGeneracion"];
                 $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                 $arrayDTE["paramsHacienda"] = "";
                 $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                 $arrayDTE["contingencia"] = $contingencia; // Datos de sistema local
                 
                 // Existe contingencia, el documento solo se firmara y guardara
                 $documentoFirmado = json_decode($this->firmar_DTE($factura));
 
                 if($documentoFirmado->status == "OK"){
                     $arrayDTE["firma"] = $documentoFirmado->body; // Datos de sistema local
                     
                     $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 51);
 
                     $msg = "El documento se firmo exitosamente";
                     redirect(base_url()."Facturacion/fin_ccf/$pivoteDTE/$msg/");
                 }else{
                     $this->session->set_flashdata("error","El documento no puede ser firmado");
                     redirect(base_url()."Hoja/detalle_hoja/$hoja/");
 
                }

                // echo json_encode($arrayDTE);
 
            }

            // echo json_encode($factura);
            
        
        }

    // Credito fiscal

    // Nota de credito
        public function lista_nc(){
            $data["lista"] = $this->Facturacion_Model->listaNC();
            $this->load->view('Base/header');
            $this->load->view('FE/lista_nc', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function nota_credito($idDTEC = null){

            $tipo = 5; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            // Partir la cadena usando el punto como delimitador
            $partesDTE = explode('.', $strDte);
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $dte_credito = $this->Facturacion_Model->obtenerDTE($idDTEC, 3);
            $data["jsonDTE"] = $dte_credito->jsonDTE;
            $data["idDTEC"] = $idDTEC;
            // $jsonDTE = unserialize(base64_decode(urldecode($dte_credito->jsonDTE)));

            $this->load->view('Base/header');
            $this->load->view('FE/nota_credito', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data["jsonDTE"]);
        }

        public function crear_nota_credito(){
            $datos = $this->input->post();
            $credito_fiscal = unserialize(base64_decode(urldecode($datos["strDocumento"])));
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            $identificacion = $credito_fiscal["dteJson"]["identificacion"];
            $emisor = $credito_fiscal["dteJson"]["emisor"];
            // formateando emisor
                unset($emisor["codEstableMH"]);
                unset($emisor["codEstable"]);
                unset($emisor["codPuntoVentaMH"]);
                unset($emisor["codPuntoVenta"]);
            // formateando emisor
            $receptor = $credito_fiscal["dteJson"]["receptor"];
            $cuerpo_documento = $credito_fiscal["dteJson"]["cuerpoDocumento"];
            // Formateando el cuerpo del documento
                $datosInsumo = array();
                // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                $numElementos = count($cuerpo_documento);
                // Iterar sobre la longitud de las matrices y combinar los datos
                $index = 1;
                for ($i = 0; $i < $numElementos; $i++) {
                    // Crear un arreglo asociativo para cada conjunto de datos
                    $objeto = array(
                        "numItem" => $cuerpo_documento[$i]["numItem"],
                        "tipoItem" => $cuerpo_documento[$i]["tipoItem"],
                        "numeroDocumento" => $identificacion["codigoGeneracion"],
                        "cantidad" => intval($cuerpo_documento[$i]["cantidad"]),
                        "codigo" => null, // Convertido en string
                        "codTributo" => null,
                        "uniMedida" => intval($cuerpo_documento[$i]["uniMedida"]),
                        "descripcion" => $cuerpo_documento[$i]["descripcion"],
                        "precioUni" => (float)$cuerpo_documento[$i]["precioUni"],
                        "montoDescu" => 0,
                        "ventaNoSuj" => 0,
                        "ventaExenta" => 0,
                        "ventaGravada" => (float)$cuerpo_documento[$i]["ventaGravada"],
                        "tributos" => array(
                            "20"
                        )


                    );

                    // Agregar el arreglo al arreglo combinado
                    array_push($datosInsumo, $objeto);
                    $index++;
                }


            // Formateando el cuerpo del documento

            $resumen = $credito_fiscal["dteJson"]["resumen"];
            // Formateando resumen
                unset($resumen["porcentajeDescuento"]);
                unset($resumen["totalNoGravado"]);
                unset($resumen["totalPagar"]);
                unset($resumen["saldoFavor"]);
                unset($resumen["pagos"]);
                unset($resumen["numPagoElectronico"]);
            // Formateando resumen
            
           // Validando DTE
               $tipo = 5;
               $anio = date("Y");
               $strDte = $this->obtener_dte($tipo, $anio);
               $partesDTE = explode('.', $strDte);
               $datos["dteFactura"] = $partesDTE[0];
               $datos["baseDTE"] = $partesDTE[1];
           // Validando DTE
           
           // Validando codigo generacion
               $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
               if($strCodigo->codigo != 0){
               $datos["cGeneracion"] = $this->codigo_generacion();
               }
           // Validando codigo generacion
           
           
           // Datos a insertar en la base de datos
               $arrayDTE["numeroDTE"] = $datos["baseDTE"];
               $arrayDTE["anioDTE"] = date("Y");
               $arrayDTE["detalleDTE"] = $datos["dteFactura"];
               $arrayDTE["idCreditoFiscal"] = 0; // Id del credito fiscal a enlazar
               $arrayDTE["generacion"] = $datos["cGeneracion"];
           // Datos a insertar en la base de datos
           
           if(!isset($datos["tipoContingencia"])){
               $datos["tipoContingencia"] = null;
           }

           $factura = array(
               "nit" => $empresa->nitEmpresa,
               "activo" => true, // Booleano sin comillas
               "passwordPri" => $this->psPublica,
               "dteJson" => array(
                   "identificacion" => array(
                       "version" => 3,
                       "ambiente" => $empresa->ambiente,
                       "tipoDte" => "05",
                       "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                       "codigoGeneracion" => $datos["cGeneracion"],
                       "tipoModelo" => 1,
                       "tipoOperacion" => intval($datos["tipoTransmision"]),
                       "tipoContingencia" => $datos["tipoContingencia"],
                       "motivoContin" => null,
                       "fecEmi" => date("Y-m-d"),
                       "horEmi" => date("H:i:s"),
                       "tipoMoneda" => "USD",
                   ),

                   "documentoRelacionado" => array(
                       array(
                           "tipoDocumento" => "03",
                           "tipoGeneracion" => 2,
                           "numeroDocumento" => $identificacion["codigoGeneracion"],
                           "fechaEmision" => $identificacion["fecEmi"] // Valor entre comillas
                       ),
                   ),
                   "emisor" => $emisor,
                   "receptor" =>$receptor,
                   "ventaTercero" => null,
                   "cuerpoDocumento" => $datosInsumo,
                   "resumen" => $resumen,
                   "extension" => null,
                   "apendice" => null
               )
           );

            // Procesando en hacienda
                // Firmando documento
                    $documentoFirmado = json_decode($this->firmar_DTE($factura));
                // Firmando documento
                
                if($documentoFirmado->status == "OK"){
                    
                    //$conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                    $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
                    $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                    $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                    // Datos del cuerpo de la solicitud
                        $documento = [
                            "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                            "idEnvio"       => time(), // Identificador de envío único
                            "version"       => 3, // Versión del JSON del DTE
                            "tipoDte"       => "05", // Tipo de DTE
                            "documento"     => $documentoFirmado->body, // Documento firmado
                            "codigoGeneracion" => $datos["cGeneracion"] // Código de generación
                        ];
                    // Datos del cuerpo de la solicitud
                            
                    // Configurar los encabezados
                        $curl = new Curl();
                        $curl->setHeader("Authorization", $token);
                        $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                        $curl->setHeader("Content-Type", "application/json");
                    // Configurar los encabezados
                            
                    $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    
                    $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                    $response = json_decode($resp->response); // Verificar la respuesta
                    // echo json_encode($response );
                    if($response->estado == "PROCESADO"){

                        $factura['firmaElectronica'] = $documentoFirmado->body;
                        $factura['selloRecibido'] = $response->selloRecibido;

                        $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                        $arrayDTE["paramsHacienda"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                        $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                        $arrayDTE["padreDTE"] = $datos["idDTEC"];
                        $curl->close();
                        
                        $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 5);
                        $this->Facturacion_Model->actualizarDTECCF($datos["idDTEC"], $pivoteDTE, 1); // Asignando nota de credito a credito Fiscal

                        $exito = "El documento se proceso exitosamente en hacienda";
                        redirect(base_url()."Facturacion/fin_nota_credito/$pivoteDTE/$exito/");

                    }else{
                        $curl->close();
                        $error = "No se ha podido validar el documento en hacienda".$response->descripcionMsg;
                        redirect(base_url()."Facturacion/fin_nota_credito/0/$error/");
                    }

                }else{
                    $this->session->set_flashdata("error","No se ha podido firmar el documento");
                    redirect(base_url()."Facturacion/nota_credito/".$datos["idDTEC"]);
                }
            // Procesando en hacienda

            // echo json_encode($arrayDTE);

        }
    // Nota de credito

    // Nota de credito
        public function lista_nd(){
            $data["lista"] = $this->Facturacion_Model->listaND();
            $this->load->view('Base/header');
            $this->load->view('FE/lista_nd', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function nota_debito($idDTEC = null){
            $tipo = 6; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            // Partir la cadena usando el punto como delimitador
            $partesDTE = explode('.', $strDte);
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $dte_credito = $this->Facturacion_Model->obtenerDTE($idDTEC, 3);
            $data["jsonDTE"] = $dte_credito->jsonDTE;
            $data["idDTEC"] = $idDTEC;
            // $jsonDTE = unserialize(base64_decode(urldecode($dte_credito->jsonDTE)));

            $this->load->view('Base/header');
            $this->load->view('FE/nota_debito', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function crear_nota_debito(){
            $datos = $this->input->post();

            $dte_credito = $this->Facturacion_Model->obtenerDTE($datos["idDTEC"], 3);
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            $data["jsonDTE"] = $dte_credito->jsonDTE;
            $jsonDTE = unserialize(base64_decode(urldecode($dte_credito->jsonDTE)));

            // Validando DTE
                $tipo = 6;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE

            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                if($strCodigo->codigo != 0){
                $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion

            
           
            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                $arrayDTE["idCreditoFiscal"] = 0; // Id del credito fiscal a enlazar
                $arrayDTE["generacion"] = $datos["cGeneracion"];
            // Datos a insertar en la base de datos

            if(!isset($datos["tipoContingencia"])){
                $datos["tipoContingencia"] = null;
            }

            $anexo = $jsonDTE["dteJson"]["receptor"];
            // Uniendo datos locales
                $anexoArray = (array) $anexo;
                $datos = array_merge($datos, $anexoArray);
            // Uniendo datos locales

            //Precios
                $subtotal = $datos["subTotalNew"];
                $iva = $datos["ivaNew"];
                $total = $datos["totalNew"];
            //Precios

            $montoTotal = $total;
            $arregloNumero = explode(".", round($montoTotal, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($montoTotal)." 00/100 Dolares"); 
            }

            $codigoDepartamento = explode( "-", $datos["direccion"]["departamento"]);
            $codigoMunicipio = explode( "-", $datos["direccion"]["municipio"]);
            $actividadEconomica = explode( "-", $datos["direccion"]["complemento"]);
            
            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 3,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "06",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => 1,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "tipoContingencia" => $datos["tipoContingencia"],
                        "motivoContin" => null,
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                    ),

                    "documentoRelacionado" => array(
                        array(
                            "tipoDocumento" => "03",
                            "tipoGeneracion" => 2,
                            "numeroDocumento" => $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"],
                            "fechaEmision" => $jsonDTE["dteJson"]["identificacion"]["fecEmi"] // Valor entre comillas
                        ),
                    ),
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "nombreComercial" => $empresa->nombreEmpresa,
                        "tipoEstablecimiento" => "02",
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,
                    ),
                    "receptor" => array(
                        "nit" => $jsonDTE["dteJson"]["receptor"]["nit"],
                        "nrc" => $jsonDTE["dteJson"]["receptor"]["nrc"],
                        "nombre" => $jsonDTE["dteJson"]["receptor"]["nombre"],
                        "codActividad" => $jsonDTE["dteJson"]["receptor"]["codActividad"],
                        "descActividad" => $jsonDTE["dteJson"]["receptor"]["descActividad"],
                        "nombreComercial" => $jsonDTE["dteJson"]["receptor"]["nombreComercial"],
                        "direccion" => array(
                            "departamento" => $jsonDTE["dteJson"]["receptor"]["direccion"]["departamento"],
                            "municipio" => $jsonDTE["dteJson"]["receptor"]["direccion"]["municipio"],
                            "complemento" => $jsonDTE["dteJson"]["receptor"]["direccion"]["complemento"],
                        ),
                        "telefono" => $jsonDTE["dteJson"]["receptor"]["telefono"],
                        "correo" => $jsonDTE["dteJson"]["receptor"]["correo"],
                    ),
                    "ventaTercero" => null,
                    "cuerpoDocumento" => array(
                        array(
                            "numItem" => 1,
                            "tipoItem" => 2,
                            "numeroDocumento" => $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"],
                            "cantidad" => intval($datos['cantidadServicio']),
                            "codigo" => null, // Convertido en string
                            "codTributo" => null,
                            "uniMedida" => 59,
                            "descripcion" => $datos['descripcionServicio'],
                            "precioUni" => (float)$subtotal,
                            "montoDescu" => 0,
                            "ventaNoSuj" => 0,
                            "ventaExenta" => 0,
                            "ventaGravada" => (float)$subtotal,
                            "tributos" => array(
                                "20"
                            )
                        )
                    ),
                    "resumen" => array(
                        "totalNoSuj" => 0,
                        "totalExenta" => 0,
                        "totalGravada" => (float)$subtotal,
                        "subTotalVentas" => (float)$subtotal,
                        "descuNoSuj" => 0,
                        "descuExenta" => 0,
                        "descuGravada" => 0,
                        "totalDescu" => 0,
                        "tributos" => [
                            [
                                "codigo" => "20",
                                "descripcion" => "Impuesto Al valor agregado 13%",
                                "valor" => (float)$iva,
                            ],
                        ],
                        "subTotal" => (float)$subtotal,
                        "ivaPerci1" => 0,
                        "ivaRete1" => 0,
                        "reteRenta" => 0,
                        "montoTotalOperacion" => (float)$total,
                        "totalLetras" => $letras,
                        "condicionOperacion" => 1,
                        "numPagoElectronico" => null

                    ),
                    "extension" => null,
                    "apendice" => null
                )
            );
            
            // Procesando en hacienda
                // Firmando documento
                    $documentoFirmado = json_decode($this->firmar_DTE($factura));
                // Firmando documento
                
                if($documentoFirmado->status == "OK"){
                    
                //    $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                    $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
                    $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                    $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                    // Datos del cuerpo de la solicitud
                        $documento = [
                            "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                            "idEnvio"       => time(), // Identificador de envío único
                            "version"       => 3, // Versión del JSON del DTE
                            "tipoDte"       => "06", // Tipo de DTE
                            "documento"     => $documentoFirmado->body, // Documento firmado
                            "codigoGeneracion" => $datos["cGeneracion"] // Código de generación
                        ];
                    // Datos del cuerpo de la solicitud
                            
                    // Configurar los encabezados
                        $curl = new Curl();
                        $curl->setHeader("Authorization", $token);
                        $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                        $curl->setHeader("Content-Type", "application/json");
                    // Configurar los encabezados
                            
                    $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    
                    $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                    $response = json_decode($resp->response); // Verificar la respuesta
                    // echo json_encode($response );
                    if($response->estado == "PROCESADO"){

                        $factura['firmaElectronica'] = $documentoFirmado->body;
                        $factura['selloRecibido'] = $response->selloRecibido;

                        $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                        $arrayDTE["paramsHacienda"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                        $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                        $arrayDTE["padreDTE"] = $datos["idDTEC"]; // Id del credito Fiscal
                        $curl->close();
                        
                        $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 6);
                        $this->Facturacion_Model->actualizarDTECCF($datos["idDTEC"], $pivoteDTE, 2); // Asignando nota de credito a credito Fiscal, 2 Nota debito

                        $exito = "El documento se proceso exitosamente en hacienda";
                        redirect(base_url()."Facturacion/fin_nota_debito/$pivoteDTE/$exito/");

                    }else{
                        $curl->close();
                        $error = "No se ha podido validar el documento en hacienda".$response->descripcionMsg;
                        redirect(base_url()."Facturacion/fin_nota_debito/0/$error/");
                    }


                }else{
                    $this->session->set_flashdata("error","No se ha podido firmar el documento");
                    redirect(base_url()."Facturacion/nota_debito/".$datos["idDTEC"]);
                }
            // Procesando en hacienda

            // echo json_encode($arrayDTE);

        }
    // Nota de credito

    // Factura consumidor final desde cero
        public function lista_facturas(){
            $data["lista"] = $this->Facturacion_Model->listaCF();
            $this->load->view('Base/header');
            $this->load->view('FE/lista_fc', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function agregar_consumidor_final(){
            $tipo = 1; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            $partesDTE = explode('.', $strDte);


            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);

            
            $this->load->view('Base/header');
            $this->load->view('FE/agregar_consumidor_final', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        
        }

        public function crear_estructura_cf(){
            $datos = $this->input->post();
            $contingencia = 0;
            $modelo = 1;
            $existeHojaCobro = $this->Facturacion_Model->obtenerHojaCobro(trim($datos["codigoHojaCobro"]));

            // Validando DTE
                $tipo = 1;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE

            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                if($strCodigo->codigo != 0){
                $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion


            // Agregando o actualizando datos de el anexo de facturacion
                $pivote = $datos["idAnexo"];
                $datosAnexo["idDepartamento"] = $datos["departamentoPaciente"];
                $datosAnexo["idMunicipio"] = $datos["municipioPaciente"];
                $datosAnexo["nombrePaciente"] = $datos["nombrePaciente"];
                $datosAnexo["duiPaciente"] = $datos["documentoPaciente"];
                $datosAnexo["telefonoPaciente"] = $datos["telefonoPaciente"];
                $datosAnexo["correoPaciente"] = $datos["correoPaciente"];
                $datosAnexo["codigoDepartamento"] = $datos["codigoDepartamento"];
                $datosAnexo["codigoMunicipio"] = $datos["codigoMunicipio"];
                $datosAnexo["direccionPaciente"] = $datos["complementoPaciente"];
                $datosAnexo["actividadEconomica"] = $datos["codigoActividadEconomica"];
                $datosAnexo["tipoDocumento"] = $datos["tipoDocumento"];
                $datosAnexo["nrcCreditoFiscal"] = $datos["nrcPaciente"];
                if($pivote == 0){
                    $datosAnexo["pivote"] = 0;
                }else{
                    $datosAnexo["idAnexo"] = $datos["idAnexo"];
                    $datosAnexo["pivote"] = 1;
                }
                // $this->Facturacion_Model->guardarProveedorAnexo($datosAnexo);
            // Agregando o actualizando datos de el anexo de facturacion

            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                $arrayDTE["idHoja"] = $existeHojaCobro->hoja;
                $arrayDTE["generacion"] = $datos["cGeneracion"];
            // Datos a insertar en la base de datos

            if(!isset($datos["tipoContingencia"])){
                $datos["tipoContingencia"] = null;
            }else{
                $contingencia = 1;
                $modelo = 2;
            }

            $empresa = $this->Facturacion_Model->obtenerEmpresa();

            //Precios
                $subtotal = $datos["precioServicio"];
                $iva = $datos["ivaServicio"];
                $total = $datos["totalServicio"];
            //Precios
            
            $montoTotal = $total;
            $arregloNumero = explode(".", number_format($montoTotal, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($montoTotal)." 00/100 Dolares"); 
            }

            $codigoDepartamento = explode( "-", $datos["codigoDepartamento"]);
            $codigoMunicipio = explode( "-", $datos["codigoMunicipio"]);
            $actividadEconomica = explode( "-", $datos["codigoActividadEconomica"]);

            $direccion = (!empty($codigoDepartamento[0]) && !empty($codigoMunicipio[0]) && !empty($datos["complementoPaciente"])) 
                ? array(
                    "departamento" => $codigoDepartamento[0],
                    "municipio" => $codigoMunicipio[0],
                    "complemento" => (!empty($datos["complementoPaciente"])) ? $datos["complementoPaciente"] : null,
                ) 
                : null;


            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 1,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "01",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => $modelo,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                        "tipoContingencia" => is_null($datos["tipoContingencia"]) ?  null : intval($datos["tipoContingencia"]),
                        "motivoContin" => null,
                    ),
                    "documentoRelacionado" => null,
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "nombreComercial" => $empresa->nombreEmpresa,
                        "tipoEstablecimiento" => "02",
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,
                        "codEstableMH" => $this->establecimiento,
                        "codEstable" => null,
                        "codPuntoVentaMH" => "P001",
                        "codPuntoVenta" => null
                    ),

                    "receptor" => array(
                        "codActividad" => (!empty($actividadEconomica[0])) ? trim($actividadEconomica[0]) : null,
                        "correo" => (!empty($datos["correoPaciente"])) ? $datos["correoPaciente"] : null,
                        "descActividad" => (!empty($actividadEconomica[1])) ? trim($actividadEconomica[1]) : null,
                        "nombre" => $datos["nombrePaciente"],
                        "nrc" => null,
                        "numDocumento" => $datos["documentoPaciente"],
                        "telefono" => $datos["telefonoPaciente"],
                        "tipoDocumento" => $datos["tipoDocumento"],
                        "direccion" => $direccion,
                    ),
                    "otrosDocumentos" => null,
                    "ventaTercero" => null,
                    "cuerpoDocumento" => array(
                        array( // Sin índice entre comillas
                            "cantidad" => 1,
                            "codigo" => "1", // Convertido en string
                            "codTributo" => null,
                            "descripcion" => $datos["detalleServicio"],
                            "ivaItem" => (float)$iva,
                            "montoDescu" => 0,
                            "noGravado" => 0,
                            "numeroDocumento" => null,
                            "numItem" => 1,
                            "precioUni" => (float)$total,
                            "psv" => 0,
                            "tipoItem" => 2,
                            "uniMedida" => 59,
                            "ventaExenta" => 0,
                            "ventaGravada" => (float)$total,
                            "ventaNoSuj" => 0,
                            "tributos" => null,
                            "psv" => 0,
                        )
                    ),
                    "resumen" => array(
                        "condicionOperacion" => 1,
                        "descuExenta" => 0,
                        "descuGravada" => 0,
                        "descuNoSuj" => 0,
                        "ivaRete1" => 0,
                        "montoTotalOperacion" => (float)$total,
                        "numPagoElectronico" => null,
                        "porcentajeDescuento" => 0,
                        "reteRenta" => 0,
                        "saldoFavor" => 0,
                        "subTotal" => (float)$total,
                        "subTotalVentas" => (float)$total,
                        "totalDescu" => 0,
                        "totalExenta" => 0,
                        "totalGravada" => (float)$total,
                        "totalIva" => (float)$iva,
                        "totalLetras" => $letras,
                        "totalNoGravado" => 0,
                        "totalNoSuj" => 0,
                        "totalPagar" => (float)$total,
                        "pagos" => null,
                        "tributos" => null,
                    ),
                    "extension" => null,
                    "apendice" => null,
                )
            );

            
            if($contingencia == 0){
               $this->envioHaciendaCF($factura, $empresa, $datos, $arrayDTE, 2, 1, null);
            }else{
                $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                $arrayDTE["paramsHacienda"] = "";
                $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                $arrayDTE["contingencia"] = $contingencia; // Datos de sistema local
                
                // Existe contingencia, el documento solo se firmara y guardara
                $documentoFirmado = json_decode($this->firmar_DTE($factura));

                if($documentoFirmado->status == "OK"){
                    $arrayDTE["firma"] = $documentoFirmado->body; // Datos de sistema local
                    
                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 50);

                    $msg = "El documento se firmo exitosamente";
                    redirect(base_url()."Facturacion/fin_factura_electronica/$pivoteDTE/$msg/");


                    // echo json_encode($arrayDTE);

                }else{
                    $this->session->set_flashdata("error","El documento no puede ser firmado");
                    redirect(base_url()."Hoja/detalle_hoja/$hoja/");

                }


            }
            
            // echo json_encode($factura);
        }
    // Factura consumidor final desde cero

    // Factura consumidor final
        public function factura_comercial($params = null){
            $datos = unserialize(base64_decode(urldecode($params)));
            $tipo = 1;
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            $partesDTE = explode('.', $strDte);
            $hoja = $datos["idHoja"];
            $data["paciente"] = $this->Facturacion_Model->detalleHoja($hoja);
            $data["detalleFactura"] = $this->detalleFactura($data["paciente"]->destinoHoja);
            $data["anexo"] = $this->Facturacion_Model->obtenerPacienteAnexo($data["paciente"]->idPaciente);
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["seguros"] = $this->Facturacion_Model->obtenerSeguros();
            $data["externosHoja"] = $this->Facturacion_Model->externosHoja($hoja);
            $data["medicamentosHoja"] = $this->Facturacion_Model->medicamentosHoja($hoja);
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["idHoja"] = $datos["idHoja"];
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);

            
            $this->load->view('Base/header');
            $this->load->view('FE/factura', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function actualizar_datos_facturacion(){
            if($this->input->is_ajax_request()){
                $datos =$this->input->post();
                $idPaciente = $datos["idPaciente"];
                // unset($datos["idPaciente"]);
                $resp = $this->Facturacion_Model->validarAnexo($idPaciente);
                $datos["actividadEconomica"] = "";
                $datos["tipoDocumento"] = "";
                $datos["nrcCreditoFiscal"] = "";



                $datos["existe"] = $resp->existe;

                $bool = $this->Facturacion_Model->guardarAnexo($datos);
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
            else
            {
                echo "Error...";
            }
        }

        public function sellar_factura($params = null){
            // $datos = $this->input->post();
            $datos = unserialize(base64_decode(urldecode($params)));
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            // Para venta
                $venta = $this->Consulta_Model->validarNumeroActual($datos["baseDTE"], $datos["documentoPago"]); //Verificando que el codigo del tiket no haya sido agregado
                if($venta->codigo > 0){
                    $maximoActual = $this->Consulta_Model->maximoActual($datos["documentoPago"]);
                    $datos["numeroDocumento"] = $maximoActual->codigo + 1;
                }else{
                    $datos["numeroDocumento"] = $datos["baseDTE"];
                }
            // Para venta
            $totalVenta = 0;
            $totalIva = 0;

            // Ordenando datos
                if(isset($datos["nombreExamen"])){
                    // Creando Json de medidas
                        // Crear un arreglo combinado
                        $datosInsumo = array();
                        $datosInsumoV = array();
                        // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                        $numElementos = count($datos["nombreExamen"]);
                        // Iterar sobre la longitud de las matrices y combinar los datos
                        $index = 1;
                        for ($i = 0; $i < $numElementos; $i++) {
                            $total_fila = $datos["cantidad"][$i] * $datos["precios"][$i];
                            $iva = ($total_fila /1.13) * 0.13;
                            $totalVenta += $total_fila;
                            $totalIva += $iva;
                            // Crear un arreglo asociativo para cada conjunto de datos
                            $objeto = array(
                                "cantidad" => intval($datos["cantidad"][$i]),
                                "codigo" => null, // Convertido en string
                                "codTributo" => null,
                                "descripcion" => $datos["nombreExamen"][$i],
                                "ivaItem" => (float)round($iva, 2),
                                "montoDescu" => 0,
                                "noGravado" => 0,
                                "numeroDocumento" => null,
                                "numItem" => $index,
                                "precioUni" => (float)$datos["precios"][$i],
                                "psv" => 0,
                                "tipoItem" => 2,
                                "uniMedida" => 59,
                                "ventaExenta" => 0,
                                "ventaGravada" => (float)round($total_fila ,2),
                                "ventaNoSuj" => 0,
                                "tributos" => null,
                                "psv" => 0,
                            );

                            $objetoV = array(
                                "idExamen" => $datos["idExamen"][$i],
                                "precio" => $datos["precios"][$i],
                                "cantidad" => $datos["cantidad"][$i],
                            );

                            // Agregar el arreglo al arreglo combinado
                            array_push($datosInsumo, $objeto);
                            array_push($datosInsumoV, $objetoV);
                            $index++;
                        }
                        unset($datos["idMedicamento"], $datos["precios"], $datos["cantidad"], $datos["cantidadUnitaria"], $datos["nombreInsumo"], $datos["nombreMedicamento"]);
                       
                        // $datos["insumos"] = $datosInsumo;
                        $datos["insumos"] = $datosInsumoV;
                    // Creando Json de medidas

                }else{
                    $datos["insumos"] = "";
                }
            // Ordenando datos

            $contingencia = 0;
            $modelo = 1;
            $hoja = 0;

            // Guardando venta
                $datosVenta = $datos;
                $datosVenta["personalizado"] = 0;
            // Guardando venta

            // Validando DTE
                $tipo = 1;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE
            
            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                if($strCodigo->codigo != 0){
                   $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion

            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                
            // Datos a insertar en la base de datos

            if(isset($datos["tipoContingencia"]) && $datos["tipoContingencia"] == "" ){
                $datos["tipoContingencia"] = null;
            }else{
                $contingencia = 1;
                $modelo = 2;
            }
            // $anexo = $this->Facturacion_Model->pacienteGeneral();
           
            
            $arregloNumero = explode(".", round($totalVenta ,2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($totalVenta)." 00/100 Dolares"); 
            }
            
            $totalVenta = round($totalVenta, 2);
            $totalIva = round($totalIva, 2);


            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 1,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "01",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => $modelo,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                        "tipoContingencia" => is_null($datos["tipoContingencia"])  ?  null : intval($datos["tipoContingencia"]),
                        "motivoContin" => null,
                    ),
                    "documentoRelacionado" => null,
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "nombreComercial" => $empresa->nombreEmpresa,
                        "tipoEstablecimiento" => "02",
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,
                        "codEstableMH" => "P001",
                        "codEstable" => null,
                        "codPuntoVentaMH" => "M001",
                        "codPuntoVenta" => null
                    ),

                    "receptor" => array(
                        "codActividad" => null,
                        "correo" => null,
                        "descActividad" => null,
                        "nombre" => $datos["clienteVenta"],
                        "nrc" => null,
                        "numDocumento" => '00000000-0',
                        "telefono" => 0000-0000,
                        "tipoDocumento" => "13",
                        "direccion" => null,
                    ),
                    "otrosDocumentos" => null,
                    "ventaTercero" => null,
                    "cuerpoDocumento" => $datosInsumo,
                    "resumen" => array(
                        "condicionOperacion" => 1,
                        "descuExenta" => 0,
                        "descuGravada" => 0,
                        "descuNoSuj" => 0,
                        "ivaRete1" => 0,
                        "montoTotalOperacion" => (float)$totalVenta,
                        "numPagoElectronico" => null,
                        "porcentajeDescuento" => 0,
                        "reteRenta" => 0,
                        "saldoFavor" => 0,
                        "subTotal" => (float)$totalVenta,
                        "subTotalVentas" => (float)$totalVenta,
                        "totalDescu" => 0,
                        "totalExenta" => 0,
                        "totalGravada" => (float)$totalVenta,
                        "totalIva" => (float)$totalIva,
                        "totalLetras" => $letras,
                        "totalNoGravado" => 0,
                        "totalNoSuj" => 0,
                        "totalPagar" => (float)$totalVenta,
                        "pagos" => null,
                        "tributos" => null,
                    ),
                    "extension" => null,
                    "apendice" => null,
                )
            );
            /* 
           
           

            
            
            if($contingencia == 0){
                $this->envioHaciendaCF($factura, $empresa, $datos, $arrayDTE, 2, 1, $datosVenta);
            }else{
                unset($datosVenta["personalizado"]);
                $resp = $this->Ventas_Model->guardarVenta($datosVenta);
                $arrayDTE["idVenta"] = $resp;
                $arrayDTE["generacion"] = $datos["cGeneracion"];


                $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                $arrayDTE["paramsHacienda"] = "";
                $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                $arrayDTE["contingencia"] = $contingencia; // Datos de sistema local
                
                // Existe contingencia, el documento solo se firmara y guardara
                $documentoFirmado = json_decode($this->firmar_DTE($factura));

                if($documentoFirmado->status == "OK"){
                    $arrayDTE["firma"] = $documentoFirmado->body; // Datos de sistema local
                    
                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 50);

                    $msg = "El documento se firmo exitosamente";
                    redirect(base_url()."Facturacion/fin_factura_electronica/$pivoteDTE/$msg/");

    
                    // echo json_encode($arrayDTE);

                }else{
                    $this->session->set_flashdata("error","El documento no puede ser firmado");
			        redirect(base_url()."Hoja/detalle_hoja/$hoja/");

                }


            } */

            echo json_encode($factura);


        }

        public function imprimir_ticket($hoja = null, $dte = null){

            try {
                //Obteniendo datos ya existentes
                $venta = $this->Ventas_Model->obtenerVenta($hoja);
                $jsonDTE = unserialize(base64_decode(urldecode($dte)));
                $tipoDTE = $jsonDTE["dteJson"]["identificacion"]["tipoDte"];
                $emisor = $jsonDTE["dteJson"]["emisor"];
                $receptor = $jsonDTE["dteJson"]["receptor"];
                $detalle = $jsonDTE["dteJson"]["cuerpoDocumento"];
                $resumen = $jsonDTE["dteJson"]["resumen"];
  

                // Creado ticket
                        // echo json_encode($totales);
                        $hora = date("h:i:s A");
                        $am_pm = date("h:i:s A", strtotime($hora));
                        // $connector = new WindowsPrintConnector("EPSON TM-T20");
                        $connector = new NetworkPrintConnector("192.168.1.154");
                        $printer = new Printer($connector);
                        // Encabezado del ticket
                            $printer->setJustification(Printer::JUSTIFY_CENTER);
                            $printer->setEmphasis(true); // Texto en negrita
                            $printer ->feed(1);
                            $printer->setTextSize(1, 1); // Tamaño de texto
                            $printer->text($emisor["nombreComercial"]."\n");
                            // $printer->setEmphasis(false); // Texto en negrita
                            $printer->setTextSize(1, 1); // Tamaño de texto
                            $printer->text("NRC: ".$emisor["nrc"]."\n");
                            $printer->text("NIT: ".$emisor["nit"]."\n");
                            $printer->text("GIRO: ".$emisor["descActividad"]."\n");
                            $df = str_pad("G---->GRAVADO", 10, " ").str_pad(" ", 5, " ").str_pad("E---->EXCENTO", 5, " ");
                            $printer->text($df."\n");
                            $printer->text($emisor["direccion"]["complemento"]."\n");
                            $printer->text("\n");
                            $printer->setJustification(Printer::JUSTIFY_LEFT);
                            $telefono = substr($emisor["telefono"], 0, 4) . "-" . substr($emisor["telefono"], 4);
                            $tel = str_pad("TEL: ".$telefono, 10, " ").str_pad(" ", 15, " ").str_pad("Caja: HO01", 5, " ");
                            $printer->text($tel."\n");
                            $printer->text("------------------------------------------\n");
                        // Encabezado del ticket
                        
                        // Identificacion
                            $printer ->feed(1);
                            $printer->setJustification(Printer::JUSTIFY_CENTER);
                            // $printer->setEmphasis(true); // Texto en negrita
                            $printer->text("FACTURA ELECTRONICA\n");
                            // $printer->setEmphasis(false); // Texto en negrita
                            $printer->text("------------------------------------------\n");
                            $printer->setJustification(Printer::JUSTIFY_LEFT);
                            $printer->text("Código de generación\n");
                            $printer->text($jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"]."\n");
                            $printer->text("Numero de control\n");
                            $printer->text($jsonDTE["dteJson"]["identificacion"]["numeroControl"]."\n");
                            $printer->text("Sello de recepción\n");
                            $printer->text($jsonDTE["selloRecibido"]."\n");
                            $fecha = str_pad($jsonDTE["dteJson"]["identificacion"]["fecEmi"], 2, ).str_pad(" ".$jsonDTE["dteJson"]["identificacion"]["horEmi"], 3, " ");
                            $printer->text($fecha."\n");
                        // Identificacion
    
    
                        // Receptor
                            $printer->setJustification(Printer::JUSTIFY_CENTER);
                            // $printer->setEmphasis(true); // Texto en negrita
                            $printer->text("RECEPTOR\n");
                            // $printer->setEmphasis(false); // Texto en negrita
                            $printer->text("------------------------------------------\n");
                            $printer->setJustification(Printer::JUSTIFY_LEFT);
                            $printer->text("Cliente: ".$receptor["nombre"]."\n");
                            $printer->text("Teléfono: ".$receptor["telefono"]."\n");
                            $printer->text("Correo: ".$receptor["correo"]."\n");
                    
                        // Receptor
    
    
                        // Detalle
                            $printer ->feed(1);
                            $printer->setJustification(Printer::JUSTIFY_CENTER);
                            // $printer->setEmphasis(true); // Texto en negrita
                            $printer->text("DETALLE\n");
                            $printer->text("------------------------------------------\n");
                            // $printer->setEmphasis(false); // Texto en negrita
                            $printer->setJustification(Printer::JUSTIFY_LEFT);
                    

                            foreach ($detalle as $row) {
                                $printer->text($row["descripcion"]."\n");
                                $medi = str_pad($row["cantidad"], 5, ).str_pad("x $".number_format($row["precioUni"], 2), 20, " ") . str_pad("$".number_format($row["ventaGravada"], 2)."G", 2, " ");
                                $printer->text($medi."\n");
                            }


                    
                            $printer->feed(1);
                    
                            $printer->setJustification(Printer::JUSTIFY_RIGHT);
                            $printer->text("------------------------------------------\n");
                            $printer->text("SUB-TOTAL $:".number_format($resumen["totalPagar"], 2)."\n");
                            $printer->text("G= Ventas gravadas $:".number_format($resumen["totalPagar"], 2)."\n");
                            $printer->text("V= Ventas excentas $:".number_format(0, 2)."\n");
                            $printer->text("Ventas no sujetas $:".number_format(0, 2)."\n");
                    
                            $printer->text("TOTAL $:".number_format($resumen["totalPagar"], 2)."\n");
                            if($hoja > 0){
                                $printer->text("RECIBIDO $:".number_format($venta->recibidoVenta, 2)."\n");
                                $printer->text("VUELTO $:".number_format($venta->vueltoVenta, 2)."\n");
                            }
                            $printer->text("------------------------------------------\n");
                    
                        
                        // Detalle
    
                        // Creando QR
                            $ambiente = $jsonDTE["dteJson"]["identificacion"]["ambiente"];
                            $fecha = $jsonDTE["dteJson"]["identificacion"]["fecEmi"];
                            $codigo = $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"];
                            $strUrl = 'https://admin.factura.gob.sv/consultaPublica?ambiente='.urlencode($ambiente).'&codGen='.urlencode($codigo).'&fechaEmi='.urlencode($fecha);
                            
                            $printer -> setJustification(Printer::JUSTIFY_CENTER);
                            $printer -> qrCode("$strUrl", Printer::QR_ECLEVEL_H, 4);
                            $printer -> setJustification();
                            $printer -> feed();
                            $printer -> feed();
                        // Creando QR

                        
                           
                        // $printer->setEmphasis(true); // Texto en negrita
                        $printer->setJustification(Printer::JUSTIFY_CENTER);
                        $printer->text("Gracias por preferirnos!!!\n");
                        // $printer->setEmphasis(false); // Texto en negrita
                        $printer -> feed();
                        $printer -> feed();

                        // Cortar el papel (si es una impresora con soporte para corte)
                        $printer->cut();
                        
                        // Abrir la caja de dinero
                        $printer->pulse();
                        
                        // Cierra la conexión
                        $printer->close();
                // Creado ticket    

                // echo json_encode($venta);
    
            }catch (Exception $e) {
                echo "Error al imprimir: " . $e->getMessage();
            }
            
        }

        private function recibo_hoja($id = null){
			$correlativoHoja = $this->Facturacion_Model->correlativoHoja($id); //Recibo actual
            
            if($correlativoHoja->recibo == 0){
				$maximoCorrelativo = $this->Facturacion_Model->correlativoActual();
				$numeroCorrelativo = $maximoCorrelativo->recibo + 1;

				// Validando caja
					$nuevoRCaja = 0;
					$reciboCaja = $this->Facturacion_Model->obtenerCorrelativoCaja($this->session->userdata("idCaja"));
					if($reciboCaja->recibo == 0){
						$nuevoRCaja = 1;
					}else{
						$nuevoRCaja = $reciboCaja->recibo + 1;
					}

						$caja["idUsuario"] = $this->session->userdata("id_usuario_h");
						$caja["idHoja"] = $id; // ID de la hoja de cobro
						$caja["idCaja"] = $this->session->userdata("idCaja");
						$caja["reciboGlobal"] = $numeroCorrelativo;
						$caja["idXCaja"] = $nuevoRCaja;
						$caja["nombreCaja"] = $this->session->userdata("codigoCaja");
						$caja["fechaGenerado"] = date("Y-m-d");
						$this->Facturacion_Model->insertarReciboXCaja($caja);
				// Validando caja

                // Actualizando el numero de correlativo
                    $this->Facturacion_Model->actualizarCorrelativo($id, $numeroCorrelativo);
                // Actualizando el numero de correlativo

                // Datos para bitacora -Anular externo cuenta
                    $bitacora["idCuenta"] = $id ;
                    $bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
                    $bitacora["usuario"] = $this->session->userdata('usuario_h');
                    $bitacora["descripcionBitacora"] = "Creo el recibo número: #".$numeroCorrelativo;
                    $this->Facturacion_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
                // Fin datos para bitacora -Anular externo cuenta

                // Calculando honorarios para cuando es paquete
                    $paquete = $this->Facturacion_Model->validarPaquete($id);
                    if($paquete->esPaquete == 1){
                        $datoCalculado = $paquete->totalPaquete - ($paquete->medicamentos + $paquete->externos);
                        if($datoCalculado > 0){
                            $honorario["idHoja"] = $paquete->idHoja;
                            $honorario["idMedico"] = $paquete->idMedico;
                            $honorario["honorario"] = $datoCalculado;
                            $honorario["original"] = $datoCalculado;
                            $this->Facturacion_Model->guardarHonorarioPaquete($honorario);
                        }
                    }
                // Calculando honorarios para cuando es paquete

                //Guardar el correlativo para control de cajeras
                    $controlCaja["usuario"] = $this->session->userdata('id_usuario_h');
                    $controlCaja["hoja"] = $id;
                    $controlCaja["correlativo"] = $numeroCorrelativo;
                    $controlCaja["fecha"] = date("Y-m-d");
                    $this->Facturacion_Model->agregarAControlCajeras($controlCaja);
                //Guardar el correlativo para control de cajeras

			}
            // echo json_encode($controlCaja);

			
		}

        private function crear_json($dte = null){

            $jsonPaciente  = array(
                "identificacion" => $dte["dteJson"]["identificacion"],
                "documentoRelacionado" => isset($dte["dteJson"]["documentoRelacionado"]) ? $dte["dteJson"]["documentoRelacionado"] : null,
                "emisor" => $dte["dteJson"]["emisor"],
                "receptor" => isset($dte["dteJson"]["receptor"]) ? $dte["dteJson"]["receptor"] : $dte["dteJson"]["sujetoExcluido"],
                "otrosDocumentos" => $dte["dteJson"]["otrosDocumentos"],
                "ventaTercero" => $dte["dteJson"]["ventaTercero"],
                "cuerpoDocumento" => $dte["dteJson"]["cuerpoDocumento"],
                "resumen" => $dte["dteJson"]["resumen"],
                "extension" => $dte["dteJson"]["extension"],
                "apendice" => $dte["dteJson"]["apendice"],
                "firmaElectronica" => $dte["firmaElectronica"],
                "selloRecibido" => $dte["selloRecibido"],
            );

            return urlencode(base64_encode(serialize($jsonPaciente))); // Respuesta de hacienda
        }

    // Factura consumidor final

    //Consumidor final solicitado
        public function consumidor_final_solicitado($params = null){
            $datos = unserialize(base64_decode(urldecode($params)));
            $totalVenta = 0;
            $totalIva = 0;

            // Para venta 
                $venta["codigoVenta"] = $datos["codigoVenta"];
                $venta["fechaVenta"] = $datos["fechaVenta"];
                $venta["clienteVenta"] = $datos["clienteVenta"];
                $venta["documentoPago"] = "2";
                $venta["numeroDocumento"] = $datos["numeroDocumento"];
                $venta["formaPago"] = $datos["formaPago"];
                $venta["txtSubtotal"] = $datos["txtSubtotal"];
                $venta["txtIVA"] = $datos["txtIVA"];
                $venta["txtTotal"] = $datos["txtTotal"];
                $venta["txtDineroRecibido"] = $datos["txtDineroRecibido"];
                $venta["txtVuelto"] = $datos["txtVuelto"];
            // Para venta 


            // Ordenando datos
                if(isset($datos["idMedicamento"])){
                    // Creando Json de medidas
                        // Crear un arreglo combinado
                        $datosInsumo = array();
                        // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                        $numElementos = count($datos["idMedicamento"]);
                        // Iterar sobre la longitud de las matrices y combinar los datos
                        $index = 1;
                        for ($i = 0; $i < $numElementos; $i++) {

                            $objeto = array(
                                "descripcion" => $datos["nombreMedicamento"][$i]."(".$datos["nombreInsumo"][$i].")",
                                "idMedicamento" => $datos["idMedicamento"][$i],
                                "precio" => $datos["precios"][$i],
                                "cantidad" => $datos["cantidad"][$i],
                                "unitaria" => $datos["cantidadUnitaria"][$i],
                                "medida" => $datos["nombreInsumo"][$i]
                            );
                            // Agregar el arreglo al arreglo combinado
                            array_push($datosInsumo, $objeto);
                            $index++;
                        }
                        unset($datos["idMedicamento"], $datos["precios"], $datos["cantidad"], $datos["cantidadUnitaria"], $datos["nombreInsumo"], $datos["nombreMedicamento"]);
                       
                        $data["insumos"] = $datosInsumo;
                    // Creando Json de medidas

                }else{
                    $data["insumos"] = "";
                }

            // Ordenando datos

            // $tipoDocumento = $datos["codigoCatDetalle"];
            $tipo = 1; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            // Partir la cadena usando el punto como delimitador
            $partesDTE = explode('.', $strDte);

            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["idHoja"] = 0;
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);
            $data["venta"] = $venta;

            
            $this->load->view('Base/header');
            $this->load->view('FE/consumidor_final_solicitado', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        
        
        }

        public function sellar_cf_solicitado(){
            
            $datos = $this->input->post();
            $parametrosFactura = unserialize(base64_decode(urldecode($datos["parametrosFactura"])));
            $datosVenta = unserialize(base64_decode(urldecode($datos["venta"]))); // Datos a insertar en la venta
            $insumos = $parametrosFactura["insumos"];

            $contingencia = 0;
            $modelo = 1;
            $totalIva = 0;
            $totalVenta = 0;
            $subtotal = 0;
            unset($datos["parametrosFactura"]);
            
            // Ordenando datos para factura
                if(isset($datos["detalleServicio"])){
                    // Creando Json de medidas
                        // Crear un arreglo combinado
                        $datosInsumo = array();
                        // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                        $numElementos = count($datos["detalleServicio"]);
                        // Iterar sobre la longitud de las matrices y combinar los datos
                        $index = 1;
                        for ($i = 0; $i < $numElementos; $i++) {

                            $totalIva += (float)$datos["ivaFila"][$i] * $datos["cantidadServicio"][$i];

                            $totalVenta += (float)$datos["totalBruto"][$i];

                            $subtotal += (float)$datos["totalNeto"][$i];

                            // Crear un arreglo asociativo para cada conjunto de datos
                            $objeto = array(
                                "cantidad" => intval($datos["cantidadServicio"][$i]),
                                "codigo" => null, // Convertido en string
                                "codTributo" => null,
                                "descripcion" => $datos["detalleServicio"][$i],
                                "ivaItem" => (float)($datos["ivaFila"][$i] * $datos["cantidadServicio"][$i]),
                                "montoDescu" => 0,
                                "noGravado" => 0,
                                "numeroDocumento" => null,
                                "numItem" => $index,
                                "precioUni" => (float)round($datos["precioServicio"][$i], 5),
                                "psv" => 0,
                                "tipoItem" => 2,
                                "uniMedida" => 59,
                                "ventaExenta" => 0,
                                "ventaGravada" => (float)round($datos["totalBruto"][$i], 5),
                                "ventaNoSuj" => 0,
                                "tributos" => null,
                                "psv" => 0,
                            );


                            // Agregar el arreglo al arreglo combinado
                            array_push($datosInsumo, $objeto);
                            $index++;
                        }
                        unset($datos["cantidadServicio"], $datos["detalleServicio"], $datos["precioServicio"], $datos["totalNeto"], $datos["totalBruto"], $datos["ivaFila"]);
                    // Creando Json de medidas

                    $totalIva = $subtotal* 0.13;

                }
            // Ordenando datos para factura

            // Agregando o actualizando datos de el anexo de facturacion
                $pivote = $datos["idAnexo"];
                $datosAnexo["idDepartamento"] = $datos["departamentoPaciente"];
                $datosAnexo["idMunicipio"] = $datos["municipioPaciente"];
                $datosAnexo["nombrePaciente"] = $datos["nombrePaciente"];
                $datosAnexo["duiPaciente"] = $datos["documentoPaciente"];
                $datosAnexo["telefonoPaciente"] = $datos["telefonoPaciente"];
                $datosAnexo["correoPaciente"] = $datos["correoPaciente"];
                $datosAnexo["codigoDepartamento"] = $datos["codigoDepartamento"];
                $datosAnexo["codigoMunicipio"] = $datos["codigoMunicipio"];
                $datosAnexo["direccionPaciente"] = $datos["complementoPaciente"];
                $datosAnexo["actividadEconomica"] = $datos["codigoActividadEconomica"];
                $datosAnexo["tipoDocumento"] = $datos["tipoDocumento"];
                $datosAnexo["nrcCreditoFiscal"] = $datos["nrcPaciente"];
                if($pivote == 0){
                    $datosAnexo["pivote"] = 0;
                }else{
                    $datosAnexo["idAnexo"] = $datos["idAnexo"];
                    $datosAnexo["pivote"] = 1;
                }
                $this->Facturacion_Model->guardarProveedorAnexo($datosAnexo);
            // Agregando o actualizando datos de el anexo de facturacion
            
            $hoja = $parametrosFactura["idHoja"];
            
            
            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
            // Datos a insertar en la base de datos

            if(isset($datos["tipoContingencia"]) && $datos["tipoContingencia"] == "" ){
                $datos["tipoContingencia"] = null;
            }else{
                $contingencia = 1;
                $modelo = 2;
            }

            // Validando DTE
                $tipo = 1;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE

            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                    if($strCodigo->codigo != 0){
                    $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion

            // Guardando venta
                    $venta = $this->Ventas_Model->validarNumeroActual($datos["baseDTE"], 2); //Verificando que el codigo del tiket no haya sido agregado
                    $paramsVenta["insumos"] = $insumos;
                    if($venta->codigo > 0){
                        $maximoActual = $this->Ventas_Model->maximoActual(2);
                        $datosVenta["numeroDocumento"] = $maximoActual->codigo + 1;
                    }else{
                        $datosVenta["numeroDocumento"] = $datos["baseDTE"];
                    }
                    $paramsVenta["datosVenta"] = $datosVenta;
                    $paramsVenta["personalizado"] = 1;
                    /*
                    
                    $resp = $this->Ventas_Model->guardarVentaCF($paramsVenta);
                    $arrayDTE["idHoja"] = $resp;
                    $arrayDTE["cGeneracion"] = $datos["cGeneracion"]; */
            // Guardando venta
            $empresa = $parametrosFactura["empresa"];
            $arregloNumero = explode(".", round($totalVenta, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($totalVenta)." 00/100 Dolares"); 
            }
            
            $codigoDepartamento = explode( "-", $datosAnexo["codigoDepartamento"]);
            $codigoMunicipio = explode( "-", $datosAnexo["codigoMunicipio"]);
            $actividadEconomica = explode( "-", $datosAnexo["actividadEconomica"]);
            
            $direccion = (!empty($codigoDepartamento[0]) && !empty($codigoMunicipio[0]) && !empty($datos["complementoPaciente"])) 
            ? array(
                "departamento" => $codigoDepartamento[0],
                "municipio" => $codigoMunicipio[0],
                "complemento" => (!empty($datos["complementoPaciente"])) ? $datos["complementoPaciente"] : null,
            ) 
            : null;
                        
            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 1,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "01",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => $modelo,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                        "tipoContingencia" => is_null($datos["tipoContingencia"]) ?  null : intval($datos["tipoContingencia"]),
                        "motivoContin" => null,
                    ),
                    "documentoRelacionado" => null,
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "nombreComercial" => $empresa->nombreEmpresa,
                        "tipoEstablecimiento" => "02",
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,
                        "codEstableMH" => "P001",
                        "codEstable" => null,
                        "codPuntoVentaMH" => "M001",
                        "codPuntoVenta" => null,
                    ),

                    "receptor" => array(
                        "codActividad" => (!empty($actividadEconomica[0])) ? trim($actividadEconomica[0]) : null,
                        "correo" => (!empty($datos["correoPaciente"])) ? $datos["correoPaciente"] : null,
                        "descActividad" => (!empty($actividadEconomica[1])) ? trim($actividadEconomica[1]) : null,
                        "nombre" => $datosAnexo["nombrePaciente"],
                        "nrc" => null,
                        "numDocumento" => $datosAnexo["duiPaciente"],
                        "telefono" => $datosAnexo["telefonoPaciente"],
                        "tipoDocumento" => $datos["tipoDocumento"],
                        "direccion" => $direccion,

                    ),
                    "otrosDocumentos" => null,
                    "ventaTercero" => null,
                    "cuerpoDocumento" => $datosInsumo,
                    "resumen" => array(
                        "condicionOperacion" => 1,
                        "descuExenta" => 0,
                        "descuGravada" => 0,
                        "descuNoSuj" => 0,
                        "ivaRete1" => 0,
                        "montoTotalOperacion" => (float)$totalVenta,
                        "numPagoElectronico" => null,
                        "porcentajeDescuento" => 0,
                        "reteRenta" => 0,
                        "saldoFavor" => 0,
                        "subTotal" => (float)$totalVenta,
                        "subTotalVentas" => (float)$totalVenta,
                        "totalDescu" => 0,
                        "totalExenta" => 0,
                        "totalGravada" => (float)$totalVenta,
                        "totalIva" => (float)round($totalIva, 2),
                        "totalLetras" => $letras,
                        "totalNoGravado" => 0,
                        "totalNoSuj" => 0,
                        "totalPagar" => (float)$totalVenta,
                        "pagos" => null,
                        "tributos" => null,

                    ),
                    "extension" => null,
                    "apendice" => null,
                )
            );
            

            if($contingencia == 0){
                $this->envioHaciendaCF($factura, $empresa, $datos, $arrayDTE, 2, 1, $paramsVenta);
             }else{
                unset($paramsVenta["personalizado"]);
                $resp = $this->Ventas_Model->guardarVentaCF($paramsVenta);
                $arrayDTE["idVenta"] = $resp;
                $arrayDTE["generacion"] = $datos["cGeneracion"];

                 $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                 $arrayDTE["paramsHacienda"] = "";
                 $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                 $arrayDTE["contingencia"] = $contingencia; // Datos de sistema local
                 
                 // Existe contingencia, el documento solo se firmara y guardara
                 $documentoFirmado = json_decode($this->firmar_DTE($factura));
 
                 if($documentoFirmado->status == "OK"){
                     $arrayDTE["firma"] = $documentoFirmado->body; // Datos de sistema local
                     
                     $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 50);
 
                     $msg = "El documento se firmo exitosamente";
                     redirect(base_url()."Facturacion/fin_factura_electronica/$pivoteDTE/$msg/");
 
 
                     // echo json_encode($arrayDTE);
 
                 }else{
                     $this->session->set_flashdata("error","El documento no puede ser firmado");
                     redirect(base_url()."Hoja/detalle_hoja/$hoja/");
 
                 }
 
 
             }

            // echo json_encode($factura);
            
        
        
        }
    //Consumidor final solicitado

    // Anular fartura comercial
        public function anular_factura($dte = null){

            $datosDte = $this->Facturacion_Model->obtenerDTE($dte, 1);

            $data["dteAnular"] = $dte;
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);
            $data["datosLocales"] = unserialize(base64_decode(urldecode($datosDte->datosLocales)));
            $data["respuestaHacienda"] = unserialize(base64_decode(urldecode($datosDte->respuestaHacienda)));
            $data["jsonAnterior"] = $datosDte->jsonDTE;
            $jsonDTE = unserialize(base64_decode(urldecode($datosDte->jsonDTE)));
        
            $data["identificacion"] = $jsonDTE["dteJson"]["identificacion"];
            $data["emisor"] = $jsonDTE["dteJson"]["emisor"];
            $data["receptor"] = $jsonDTE["dteJson"]["receptor"];
            $data["cuerpoDocumento"] = $jsonDTE["dteJson"]["cuerpoDocumento"];
            $data["resumen"] = $jsonDTE["dteJson"]["resumen"];

            $tipo = 1;
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            $partesDTE = explode('.', $strDte);
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);

            $this->load->view('Base/header');
            $this->load->view('FE/anular_factura_cf', $data);
            $this->load->view('Base/footer');
            
            // echo json_encode($data);

        }

        public function procesar_anulacion_factura(){
            $datos = $this->input->post();

            // Ordenando datos
                $totalVenta = 0;
                $totalIva = 0;
                if(isset($datos["detalleServicio"])){
                    // Creando Json de medidas
                        // Crear un arreglo combinado
                        $datosInsumo = array();
                        // Obtener el número de elementos en una de las matrices (se asume que todas tienen la misma longitud)
                        $numElementos = count($datos["detalleServicio"]);
                        // Iterar sobre la longitud de las matrices y combinar los datos
                        $index = 1;
                        for ($i = 0; $i < $numElementos; $i++) {
                            
                            $total_fila = $datos["cantidadServicio"][$i] * $datos["precioServicio"][$i]; // El total
                            $iva = ($total_fila / 1.13) * 0.13; // Obteneiendo el iva

                            $totalVenta += $total_fila;
                            $totalIva += $iva;
                            // Crear un arreglo asociativo para cada conjunto de datos
                            $objeto = array(
                                "cantidad" => intval($datos["cantidadServicio"][$i]),
                                "codigo" => null, // Convertido en string
                                "codTributo" => null,
                                "descripcion" => $datos["detalleServicio"][$i],
                                "ivaItem" => (float)round($iva, 2),
                                "montoDescu" => 0,
                                "noGravado" => 0,
                                "numeroDocumento" => null,
                                "numItem" => $index,
                                "precioUni" => (float)$datos["precioServicio"][$i],
                                "psv" => 0,
                                "tipoItem" => 2,
                                "uniMedida" => 59,
                                "ventaExenta" => 0,
                                "ventaGravada" => (float)round($total_fila ,2),
                                "ventaNoSuj" => 0,
                                "tributos" => null,
                                "psv" => 0,

                                

                            );
                            // Agregar el arreglo al arreglo combinado
                            array_push($datosInsumo, $objeto);
                            $index++;
                        }
                        unset($datos["cantidadServicio"], $datos["precioServicio"], $datos["detalleServicio"]);
                    
                    // $datos["insumos"] = $datosInsumo;
                    // Creando Json de medidas

                }else{
                    $datos["insumos"] = "";
                }

            // Ordenando datos


            $dteAnular = $datos["idDTEAnular"];
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            $tipoAnulacion = $datos["tipoAnulacion"]; // 1-Nuevo documento; 2-Anular documento
            $jsonAnterior = unserialize(base64_decode(urldecode($datos["jsonAnterior"])));
            $generacion = $jsonAnterior["dteJson"]["identificacion"]["codigoGeneracion"] ;

            if($tipoAnulacion == 1){
                    // Datos a insertar en la base de datos
                        $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                        $arrayDTE["anioDTE"] = date("Y");
                        $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                        $arrayDTE["idHoja"] = 0;
                        $arrayDTE["cGeneracion"] = $datos["cGeneracion"];
                        $arrayDTE["dtePadre"] = $datos["idDTEAnular"];
                    // Datos a insertar en la base de datos

                    if(!isset($datos["tipoContingencia"])){
                        $datos["tipoContingencia"] = null;
                    }
                    
                    $montoTotal = $totalVenta;
                    $arregloNumero = explode(".", round($montoTotal, 2));
                    $letras = "";
                    if(isset($arregloNumero[1])){
                        $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
                    }else{
                        $letras = strtoupper($this->convertir($montoTotal)." 00/100 Dolares"); 
                    }

                    $codigoDepartamento = explode( "-", $datos["codigoDepartamento"]);
                    $codigoMunicipio = explode( "-", $datos["codigoMunicipio"]);
                    $actividadEconomica = explode( "-", $datos["codigoActividadEconomica"]);

                    $direccion = (!empty($codigoDepartamento[0]) && !empty($codigoMunicipio[0]) && !empty($datos["complementoPaciente"])) 
                        ? array(
                            "departamento" => $codigoDepartamento[0],
                            "municipio" => $codigoMunicipio[0],
                            "complemento" => (!empty($datos["complementoPaciente"])) ? $datos["complementoPaciente"] : null,
                        ) 
                        : null;
                    
                    
                    $totalVenta = round($totalVenta, 2);
                    $totalIva = round($totalIva, 2);
                    $factura = array(
                        "nit" => $empresa->nitEmpresa,
                        "activo" => true, // Booleano sin comillas
                        "passwordPri" => $this->psPublica,
                        "dteJson" => array(
                            "identificacion" => array(
                                "version" => 1,
                                "ambiente" => $empresa->ambiente,
                                "tipoDte" => "01",
                                "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                                "codigoGeneracion" => $datos["cGeneracion"],
                                "tipoModelo" => 1,
                                "tipoOperacion" => intval($datos["tipoTransmision"]),
                                "fecEmi" => date("Y-m-d"),
                                "horEmi" => date("H:i:s"),
                                "tipoMoneda" => "USD",
                                "tipoContingencia" => $datos["tipoContingencia"],
                                "motivoContin" => null,
                            ),
                            "documentoRelacionado" => null,
                            "emisor" => array(
                                "nit" => $empresa->nitEmpresa,
                                "nrc" => $empresa->nrcEmpresa,
                                "nombre" => $empresa->nombreEmpresa,
                                "codActividad" => $empresa->codActividadEmpresa,
                                "descActividad" => $empresa->descActividadEmpresa,
                                "nombreComercial" => $empresa->nombreEmpresa,
                                "tipoEstablecimiento" => "02",
                                "direccion" => array(
                                    "departamento" => $empresa->departamento,
                                    "municipio" => $empresa->municipio,
                                    "complemento" => $empresa->direccionEmpresa,
                                ),
                                "telefono" => $empresa->telefonoEmpresa,
                                "correo" => $empresa->correoEmpresa,
                                "codEstableMH" => "P001",
                                "codEstable" => null,
                                "codPuntoVentaMH" => "M001",
                                "codPuntoVenta" => null
                            ),

                            "receptor" => array(
                                "codActividad" => (!empty($actividadEconomica[0])) ? $actividadEconomica[0] : null,
                                "correo" => (!empty($datos["correoPaciente"])) ? $datos["correoPaciente"] : null,
                                "descActividad" => (!empty($actividadEconomica[1])) ? $actividadEconomica[1] : null,
                                "nombre" => $datos["nombrePaciente"],
                                "nrc" => null,
                                "numDocumento" => $datos["documentoPaciente"],
                                "telefono" => $datos["telefonoPaciente"],
                                "tipoDocumento" => $datos["tipoDocumento"],
                                "direccion" => $direccion,
                            ),
                            "otrosDocumentos" => null,
                            "ventaTercero" => null,
                            "cuerpoDocumento" => $datosInsumo,
                            "resumen" => array(
                                "condicionOperacion" => 1,
                                "descuExenta" => 0,
                                "descuGravada" => 0,
                                "descuNoSuj" => 0,
                                "ivaRete1" => 0,
                                "montoTotalOperacion" => (float)$totalVenta,
                                "numPagoElectronico" => null,
                                "porcentajeDescuento" => 0,
                                "reteRenta" => 0,
                                "saldoFavor" => 0,
                                "subTotal" => (float)$totalVenta,
                                "subTotalVentas" => (float)$totalVenta,
                                "totalDescu" => 0,
                                "totalExenta" => 0,
                                "totalGravada" => (float)$totalVenta,
                                "totalIva" => (float)$totalIva,
                                "totalLetras" => $letras,
                                "totalNoGravado" => 0,
                                "totalNoSuj" => 0,
                                "totalPagar" => (float)$totalVenta,
                                "pagos" => null,
                                "tributos" => null,
                            ),
                            "extension" => null,
                            "apendice" => null,
                        )
                    );

                    // Limpiando datos
                        unset($datos["identificacionAnterior"]);
                        unset($datos["emisorAnterior"]);
                        unset($datos["receptorAnterior"]);
                        unset($datos["cuerpoAnterior"]);
                        unset($datos["resumenAnterior"]);
                        unset($datos["rhAnterior"]);
                        unset($datos["jsonAnterior"]);
                    // Limpiando datos
                   $resp = $this->envioHaciendaCF($factura, $empresa, $datos, $arrayDTE, $tipoAnulacion, 11, null);
                   $datosNuevaFactura = unserialize(base64_decode(urldecode($resp)));

                    // Si habra nuevo documento
                    $this->procesarAnulacion($empresa, $datos["cGeneracion"],  $datosNuevaFactura, $jsonAnterior, $dteAnular, $tipoAnulacion, $datos);

                // echo json_encode($arrayDTE);
            }else{
                $this->procesarAnulacion($empresa, $generacion,  $datosNuevaFactura = null, $jsonAnterior, $dteAnular, $tipoAnulacion, $datos);
            }

            // echo json_encode($tipoAnulacion);

        }

        public function procesarAnulacion($empresa = null, $generacion = null,  $datosNuevaFactura = null, $jsonAnterior = null, $dteAnular = null, $pivoteAnulacion = null, $datos = null){
            $codigoGeneracionAnular = $this->codigo_generacion();
            // Creando factura
                
                $factura = array(
                    "nit" => $empresa->nitEmpresa,
                    "activo" => true, // Booleano sin comillas
                    "passwordPri" => $this->psPublica,
                    "dteJson" => array(
                        "identificacion" => array(
                            "version" => 2,
                            "ambiente" => $empresa->ambiente,
                            "codigoGeneracion" => $codigoGeneracionAnular,
                            "fecAnula" => date("Y-m-d"),
                            "horAnula" => date("H:i:s"),
                            
                        ),
                        "emisor" => array(
                            "nit" => $empresa->nitEmpresa,
                            "nombre" => $empresa->nombreEmpresa,
                            "tipoEstablecimiento" => "02",
                            "nomEstablecimiento" => $empresa->nombreEmpresa,
                            "codEstableMH" => "P001",
                            "codEstable" => null,
                            "codPuntoVentaMH" => "M001",
                            "codPuntoVenta" => null,
                            "telefono" => $empresa->telefonoEmpresa,
                            "correo" => $empresa->correoEmpresa,
                        ),
                        "documento" => array(
                            "tipoDte" => $jsonAnterior["dteJson"]["identificacion"]["tipoDte"],
                            "codigoGeneracion" => $jsonAnterior["dteJson"]["identificacion"]["codigoGeneracion"],
                            "selloRecibido" => $jsonAnterior["selloRecibido"],
                            "numeroControl" => $jsonAnterior["dteJson"]["identificacion"]["numeroControl"],
                            "fecEmi" => $jsonAnterior["dteJson"]["identificacion"]["fecEmi"],
                            "montoIva" => null,
                            "codigoGeneracionR" => null,
                            "tipoDocumento" => $jsonAnterior["dteJson"]["receptor"]["tipoDocumento"],
                            "numDocumento" => $jsonAnterior["dteJson"]["receptor"]["numDocumento"],
                            "nombre" => $jsonAnterior["dteJson"]["receptor"]["nombre"],
                            "telefono" => $jsonAnterior["dteJson"]["receptor"]["telefono"],
                            "correo" => $jsonAnterior["dteJson"]["receptor"]["correo"]
                        ),

                        "motivo" => array(
                            "tipoAnulacion" => intval($datos["tipoAnulacion"]),
                            "motivoAnulacion" => $datos["detalleAnulacion"],
                            "nombreResponsable" => $this->session->userdata('nombreEmpleado'),
                            "tipDocResponsable" => "13",
                            "numDocResponsable" => $this->session->userdata('duiEmpleado'),
                            "nombreSolicita" => $jsonAnterior["dteJson"]["receptor"]["nombre"],
                            "tipDocSolicita" => "13",
                            "numDocSolicita" => $jsonAnterior["dteJson"]["receptor"]["numDocumento"]
                        ),

                    )
                );
            // Creando factura

            if($pivoteAnulacion == 1){
                $factura["dteJson"]["documento"]["codigoGeneracionR"] = $generacion;
            }

            // echo json_encode($factura);

            // Firmando documento
                $documentoFirmado = json_decode($this->firmar_DTE($factura));
            // Firmando documento
              
            if($documentoFirmado->status == "OK"){
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."anulardte"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                        "idEnvio"       => time(), // Identificador de envío único
                        "version"       => 2, // Versión del JSON del DTE
                        "documento"     => $documentoFirmado->body, // Documento firmado
                    ];
                    // "codigoGeneracion" => $codigoGeneracionAnular // Código de generación
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                $mensaje = $response->descripcionMsg;
                
                // echo json_encode($response );
                
                if($response->estado == "PROCESADO"){
                   
                    $curl->close();
                    $nuevoDTE = $datosNuevaFactura["filaDB"];
                    $resp = $this->Facturacion_Model->anularDTE($dteAnular, $nuevoDTE, $pivoteAnulacion);
                    if($pivoteAnulacion == 1){
                        redirect(base_url()."Facturacion/fin_factura_electronica/$nuevoDTE/$mensaje/");
                    }else{
                        $this->session->set_flashdata("exito","DTE anulado con exito");
                        redirect(base_url()."Facturacion/anular_factura/$dteAnular/");
                    }

                }else{
                    $curl->close();
                    // $this->session->set_flashdata("error","DTE no pudo ser anulado");
                    redirect(base_url()."Facturacion/fin_factura_electronica/0/$mensaje/");
                }

            }else{
                $this->session->set_flashdata("error","No se ha podido firmar el documento");
                redirect(base_url()."Facturacion/anular_factura/$dteAnular/");
            }

        }

    // Anular fartura comercial

    // Sujeto excluido
        public function sujeto_excluido($params = null){
            $datos = unserialize(base64_decode(urldecode($params)));
            // $tipoDocumento = $datos["codigoCatDetalle"];
            $tipo = 14; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            // Partir la cadena usando el punto como delimitador
            $partesDTE = explode('.', $strDte);
            $hoja = $datos["idHoja"];
            $data["paciente"] = $this->Facturacion_Model->detalleHoja($hoja);
            $data["anexo"] = $this->Facturacion_Model->obtenerPacienteAnexo($data["paciente"]->idPaciente);
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            // $data["seguros"] = $this->Facturacion_Model->obtenerSeguros();
            $data["externosHoja"] = $this->Facturacion_Model->externosHoja($hoja);
            $data["medicamentosHoja"] = $this->Facturacion_Model->medicamentosHoja($hoja);
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["idHoja"] = $datos["idHoja"];
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);

            
            $this->load->view('Base/header');
            $this->load->view('FE/sujeto_excluido', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data["baseDTE"]);
        
        }

        public function sellar_sujeto_excluido(){
            $datos = $this->input->post();
            
            
            $hoja = $datos["idHoja"];
            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                $arrayDTE["idHoja"] = $hoja;
            // Datos a insertar en la base de datos
            
            if(!isset($datos["tipoContingencia"])){
                $datos["tipoContingencia"] = null;
            }

            $paciente = $this->Facturacion_Model->detalleHoja($hoja);
            $anexo = $this->Facturacion_Model->obtenerPacienteAnexo($paciente->idPaciente);

            // Uniendo datos locales
                $anexoArray = (array) $anexo;
                $datos = array_merge($datos, $anexoArray);
            // Uniendo datos locales

            $empresa = $this->Facturacion_Model->obtenerEmpresa();

            // echo json_encode($anexo);

            //Precios
                if($datos["facturarTodo"] == 0){
                    $subtotal = $datos["subTotalI"];
                    $iva = $datos["ivaI"];
                    $total = $datos["totalInterno"];
                }else{
                    $subtotal = $datos["subTotalI"] + $datos["subTotalE"];
                    $iva = $datos["ivaI"] + $datos["ivaE"];
                    $total = $datos["totalInterno"] + $datos["totalExterno"];
                }
            //Precios

            $montoTotal = $total;
            $arregloNumero = explode(".", round($montoTotal, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($montoTotal)." 00/100 Dolares"); 
            }

            $codigoDepartamento = explode( "-", $anexo->codigoDepartamento);
            $codigoMunicipio = explode( "-", $anexo->codigoMunicipio);
            $actividadEconomica = explode( "-", $anexo->actividadEconomica);
            


            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 1,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "14",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => 1,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "tipoContingencia" => $datos["tipoContingencia"],
                        "motivoContin" => null,
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                    ),
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "codEstableMH" => "P001",
                        "codEstable" => null,
                        "codPuntoVentaMH" => "M001",
                        "codPuntoVenta" => null,
                        "correo" => $empresa->correoEmpresa,
                    ),

                    "sujetoExcluido" => array(

                        "tipoDocumento" => $datos["tipoDocumento"],
                        "numDocumento" => $anexo->duiPaciente == "" ? $paciente->duiPaciente : $anexo->duiPaciente,
                        "nombre" => $anexo->nombrePaciente != "" ? $paciente->nombrePaciente : $anexo->nombrePaciente,
                        "codActividad" => null,
                        "descActividad" => null,
                        "direccion" => array(
                            "departamento" => $codigoDepartamento[0],
                            "municipio" => $codigoMunicipio[0],
                            "complemento" => $anexo->direccionPaciente,
                        ),
                        "telefono" => $anexo->telefonoPaciente == "" ? $paciente->telefonoPaciente : $anexo->telefonoPaciente,
                        "correo" => $anexo->correoPaciente == "" ? $paciente->correoPaciente : $anexo->correoPaciente,

                    ),

                    "cuerpoDocumento" => array(
                        array(
                            "numItem" => 1,
                            "tipoItem" => 2,
                            "cantidad" => 1,
                            "codigo" => null, // Convertido en string
                            "uniMedida" => 59,
                            "descripcion" => "MEDICAMENTOS E INSUMOS MÉDICOS",
                            "precioUni" => (float)$total,
                            "montoDescu" => 0,
                            "compra" => (float)$total,

                        )
                    ),
                    "resumen" => array(
                        "totalCompra" =>(float)$total,
                        "descu" => 0,
                        "totalDescu" => 0,
                        "subTotal" => (float)$total,
                        "ivaRete1" => 0,
                        "reteRenta" => 0,
                        "totalPagar" => (float)$total,
                        "totalLetras" => $letras,
                        "condicionOperacion" => 1,
                        "pagos" => [
                            [
                                "codigo" => "01",
                                "montoPago" => (float)$total,
                                "referencia" => null,
                                "plazo" => null,
                                "periodo" => null,
                            ],
                        ],
                        "observaciones" => null,


                    ),
                    "apendice" => null,
                )
            );

            // echo json_encode($factura);

            
            
            // Firmando documento
                $documentoFirmado = json_decode($this->firmar_DTE($factura));
            // Firmando documento
                
            
            if($documentoFirmado->status == "OK"){
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                        "idEnvio"       => time(), // Identificador de envío único
                        "version"       => 1, // Versión del JSON del DTE
                        "tipoDte"       => "14", // Tipo de DTE
                        "documento"     => $documentoFirmado->body, // Documento firmado
                        "codigoGeneracion" => $datos["cGeneracion"] // Código de generación
                    ];
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                // echo json_encode($response );
                if($response->estado == "PROCESADO"){

                    $factura['firmaElectronica'] = $documentoFirmado->body;
                    $factura['selloRecibido'] = $response->selloRecibido;

                    $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                    $arrayDTE["paramsHacienda"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                    $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                    $curl->close();

                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 14);
                    $exito = "El documento se proceso exitosamente en hacienda";
                    redirect(base_url()."Facturacion/fin_se/$pivoteDTE/$exito/");

                }else{
                    $curl->close();
                    $error = "No se ha podido validar el documento en hacienda".$response->descripcionMsg;
                    redirect(base_url()."Facturacion/fin_se/0/$error/");
                }

            }else{
                $this->session->set_flashdata("error","No se ha podido firmar el documento");
                redirect(base_url()."Hoja/detalle_hoja/".$hoja."/");
            }
        
        }
        // Estructura desde cero
        public function lista_se(){
            $data["lista"] = $this->Facturacion_Model->listaSE();

            $this->load->view('Base/header');
            $this->load->view('FE/lista_se', $data);
            $this->load->view('Base/footer');

        }

        public function agregar_sujeto_excluido(){
            $tipo = 14; // Para validar el tipo de DTE requerido
            $anio = date("Y");
            $strDte = $this->obtener_dte($tipo, $anio);
            $partesDTE = explode('.', $strDte);


            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["dte"] = $partesDTE[0];
            $data["baseDTE"] = $partesDTE[1];
            $data["cGeneracion"] = $this->codigo_generacion();
            $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);
            $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);

            
            $this->load->view('Base/header');
            $this->load->view('FE/agregar_sujeto_excluido', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        
        }
        

        public function crear_estructura_se(){
            $datos = $this->input->post();
            $contingencia = 0;
            $modelo = 1;
            $existeHojaCobro = $this->Facturacion_Model->obtenerHojaCobro(trim($datos["codigoHojaCobro"]));
            // Agregando o actualizando datos de el anexo de facturacion
                $pivote = $datos["idAnexo"];
                $datosAnexo["idDepartamento"] = $datos["departamentoPaciente"];
                $datosAnexo["idMunicipio"] = $datos["municipioPaciente"];
                $datosAnexo["nombrePaciente"] = $datos["nombrePaciente"];
                $datosAnexo["duiPaciente"] = $datos["documentoPaciente"];
                $datosAnexo["telefonoPaciente"] = $datos["telefonoPaciente"];
                $datosAnexo["correoPaciente"] = $datos["correoPaciente"];
                $datosAnexo["codigoDepartamento"] = $datos["codigoDepartamento"];
                $datosAnexo["codigoMunicipio"] = $datos["codigoMunicipio"];
                $datosAnexo["direccionPaciente"] = $datos["complementoPaciente"];
                $datosAnexo["actividadEconomica"] = $datos["codigoActividadEconomica"];
                $datosAnexo["tipoDocumento"] = $datos["tipoDocumento"];
                $datosAnexo["nrcCreditoFiscal"] = "";
                if($pivote == 0){
                    $datosAnexo["pivote"] = 0;
                }else{
                    $datosAnexo["idAnexo"] = $datos["idAnexo"];
                    $datosAnexo["pivote"] = 1;
                }
                $this->Facturacion_Model->guardarProveedorAnexo($datosAnexo);
            // Agregando o actualizando datos de el anexo de facturacion

            // Validando DTE
                $tipo = 14;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $datos["dteFactura"] = $partesDTE[0];
                $datos["baseDTE"] = $partesDTE[1];
            // Validando DTE


            // Validando codigo generacion
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                if($strCodigo->codigo != 0){
                $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion


            // Datos a insertar en la base de datos
                $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                $arrayDTE["anioDTE"] = date("Y");
                $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                $arrayDTE["idHoja"] = $existeHojaCobro->hoja;
                $arrayDTE["generacion"] = $datos["cGeneracion"];
            // Datos a insertar en la base de datos
            
            if(!isset($datos["tipoContingencia"])){
                $datos["tipoContingencia"] = null;
            }else{
                $contingencia = 1;
                $modelo = 2;
            }

            $empresa = $this->Facturacion_Model->obtenerEmpresa();

            //Precios
                $cantidad = $datos["cantidadServicio"];
                $precio = $datos["precioServicio"];
                $subtotal = $cantidad * $precio;
                $iva = $datos["ivaServicio"];
                $total = $datos["totalServicio"];
                $precioCompleto = $precio + $iva;
            //Precios
            
            $arregloNumero = explode(".", round($subtotal, 2));
            $letras = "";
            if(isset($arregloNumero[1])){
                $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
            }else{
                $letras = strtoupper($this->convertir($subtotal)." 00/100 Dolares"); 
            }

            $codigoDepartamento = explode( "-", $datos["codigoDepartamento"]);
            $codigoMunicipio = explode( "-", $datos["codigoMunicipio"]);
            $actividadEconomica = explode( "-", $datos["codigoActividadEconomica"]);

            $direccion = (!empty($codigoDepartamento[0]) && !empty($codigoMunicipio[0]) && !empty($datos["complementoPaciente"])) 
                ? array(
                    "departamento" => $codigoDepartamento[0],
                    "municipio" => $codigoMunicipio[0],
                    "complemento" => (!empty($datos["complementoPaciente"])) ? $datos["complementoPaciente"] : null,
                ) 
                : null;


            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 1,
                        "ambiente" => $empresa->ambiente,
                        "tipoDte" => "14",
                        "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "tipoModelo" => $modelo,
                        "tipoOperacion" => intval($datos["tipoTransmision"]),
                        "tipoContingencia" => is_null($datos["tipoContingencia"]) ?  null : intval($datos["tipoContingencia"]),
                        "motivoContin" => null,
                        "fecEmi" => date("Y-m-d"),
                        "horEmi" => date("H:i:s"),
                        "tipoMoneda" => "USD",
                    ),
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nrc" => $empresa->nrcEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "codActividad" => $empresa->codActividadEmpresa,
                        "descActividad" => $empresa->descActividadEmpresa,
                        "direccion" => array(
                            "departamento" => $empresa->departamento,
                            "municipio" => $empresa->municipio,
                            "complemento" => $empresa->direccionEmpresa,
                        ),
                        "telefono" => $empresa->telefonoEmpresa,
                        "codEstableMH" => "P001",
                        "codEstable" => null,
                        "codPuntoVentaMH" => "M001",
                        "codPuntoVenta" => null,
                        "correo" => $empresa->correoEmpresa,
                    ),

                    "sujetoExcluido" => array(

                        "tipoDocumento" => $datos["tipoDocumento"],
                        "numDocumento" => (string)$datos["documentoPaciente"],
                        "nombre" => $datos["nombrePaciente"],
                        "codActividad" => null,
                        "descActividad" => null,
                        "direccion" => array(
                            "departamento" => $codigoDepartamento[0],
                            "municipio" => $codigoMunicipio[0],
                            "complemento" => $datos["complementoPaciente"],
                        ),
                        "telefono" => $datos["telefonoPaciente"],
                        "correo" => $datos["correoPaciente"] != "" ? $datos["correoPaciente"] : null,

                    ),

                    "cuerpoDocumento" => array(
                        array(
                            "numItem" => 1,
                            "tipoItem" => intval($datos["tipoVenta"]),
                            "cantidad" => intval($cantidad),
                            "codigo" => null, // Convertido en string
                            "uniMedida" => 59,
                            "descripcion" => $datos["detalleServicio"],
                            "precioUni" => (float)$precioCompleto,
                            "montoDescu" => 0,
                            "compra" => (float)($cantidad * $precioCompleto),

                        )
                    ),
                    "resumen" => array(
                        "totalCompra" =>(float)round(($cantidad * $precioCompleto), 2),
                        "descu" => 0,
                        "totalDescu" => 0,
                        "subTotal" => (float)round(($cantidad * $precioCompleto), 2),
                        "ivaRete1" => 0,
                        "reteRenta" => (float)round(($iva * $cantidad), 2),
                        "totalPagar" => (float)round($subtotal, 2),
                        "totalLetras" => $letras,
                        "condicionOperacion" => 1,
                        "pagos" => [
                            [
                                "codigo" => "01",
                                "montoPago" => (float)round($subtotal, 2),
                                "referencia" => null,
                                "plazo" => null,
                                "periodo" => null,
                            ],
                        ],
                        "observaciones" => null,


                    ),
                    "apendice" => null,
                )
            );

            // echo json_encode($arrayDTE); 
            
            if($contingencia == 0){
                $this->envioHaciendaSE($factura, $empresa, $datos, $arrayDTE, 2, 14);
            }else{
                $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                $arrayDTE["paramsHacienda"] = "";
                $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                $arrayDTE["contingencia"] = $contingencia; // Datos de sistema local
                
                // Existe contingencia, el documento solo se firmara y guardara
                $documentoFirmado = json_decode($this->firmar_DTE($factura));

                if($documentoFirmado->status == "OK"){
                    $arrayDTE["firma"] = $documentoFirmado->body; // Datos de sistema local
                    
                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 52);

                    $msg = "El documento se firmo exitosamente";
                    redirect(base_url()."Facturacion/fin_se/$pivoteDTE/$msg/");


                    // echo json_encode($arrayDTE);

                }else{
                    $this->session->set_flashdata("error","El documento no puede ser firmado");
                    redirect(base_url()."Facturacion/agregar_sujeto_excluido/");

                }

            }
            
            //echo json_encode($factura);
        }

        // Anlar
            public function anular_se($dte = null){

                $datosDte = $this->Facturacion_Model->obtenerDTE($dte, 14);

                $data["dteAnular"] = $dte;
                $data["actividadesEconomicas"] = $this->Facturacion_Model->obtenerDetalleCatalogo(19);
                $data["datosLocales"] = unserialize(base64_decode(urldecode($datosDte->datosLocales)));
                $data["respuestaHacienda"] = unserialize(base64_decode(urldecode($datosDte->respuestaHacienda)));
                $data["jsonAnterior"] = $datosDte->jsonDTE;
                $jsonDTE = unserialize(base64_decode(urldecode($datosDte->jsonDTE)));
            
                $data["identificacion"] = $jsonDTE["dteJson"]["identificacion"];
                $data["emisor"] = $jsonDTE["dteJson"]["emisor"];
                $data["receptor"] = $jsonDTE["dteJson"]["sujetoExcluido"];
                $data["cuerpoDocumento"] = $jsonDTE["dteJson"]["cuerpoDocumento"];
                $data["resumen"] = $jsonDTE["dteJson"]["resumen"];

                $tipo = 14;
                $anio = date("Y");
                $strDte = $this->obtener_dte($tipo, $anio);
                $partesDTE = explode('.', $strDte);
                $data["dte"] = $partesDTE[0];
                $data["baseDTE"] = $partesDTE[1];
                $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
                $data["cGeneracion"] = $this->codigo_generacion();
                $data["departamentos"] = $this->Facturacion_Model->obtenerDetalleCatalogo(12);

                $this->load->view('Base/header');
                $this->load->view('FE/anular_factura_se', $data);
                $this->load->view('Base/footer');
                
                // echo json_encode($data["datosLocales"]);

            }

            public function procesar_anulacion_se(){
                $datos = $this->input->post();
                $dteAnular = $datos["idDTEAnular"];
                $empresa = $this->Facturacion_Model->obtenerEmpresa();
                $tipoAnulacion = $datos["tipoAnulacion"]; // 1-Nuevo documento; 2-Anular documento
                $jsonAnterior = unserialize(base64_decode(urldecode($datos["jsonAnterior"])));
                $generacion = $jsonAnterior["dteJson"]["identificacion"]["codigoGeneracion"] ;

                if($tipoAnulacion == 1){
                    
                    // Datos a insertar en la base de datos
                        $arrayDTE["numeroDTE"] = $datos["baseDTE"];
                        $arrayDTE["anioDTE"] = date("Y");
                        $arrayDTE["detalleDTE"] = $datos["dteFactura"];
                        $arrayDTE["idHoja"] = 0;
                        $arrayDTE["dtePadre"] = $datos["idDTEAnular"];
                    // Datos a insertar en la base de datos

                    if(!isset($datos["tipoContingencia"])){
                        $datos["tipoContingencia"] = null;
                    }
                    //Precios
                        $subtotal = $datos["precioServicio"];
                        $iva = $datos["ivaServicio"];
                        $total = $datos["totalServicio"];
                    //Precios
                    $arregloNumero = explode(".", round($subtotal, 2));
                    $letras = "";
                    if(isset($arregloNumero[1])){
                        $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
                    }else{
                        $letras = strtoupper($this->convertir($subtotal)." 00/100 Dolares"); 
                    }

                    $codigoDepartamento = explode( "-", $datos["codigoDepartamento"]);
                    $codigoMunicipio = explode( "-", $datos["codigoMunicipio"]);
                    $actividadEconomica = explode( "-", $datos["codigoActividadEconomica"]);

                    $direccion = (!empty($codigoDepartamento[0]) && !empty($codigoMunicipio[0]) && !empty($datos["complementoPaciente"])) 
                        ? array(
                            "departamento" => $codigoDepartamento[0],
                            "municipio" => $codigoMunicipio[0],
                            "complemento" => (!empty($datos["complementoPaciente"])) ? $datos["complementoPaciente"] : null,
                        ) 
                        : null;
                    
                    
                    $factura = array(
                        "nit" => $empresa->nitEmpresa,
                        "activo" => true, // Booleano sin comillas
                        "passwordPri" => $this->psPublica,
                        "dteJson" => array(
                            "identificacion" => array(
                                "version" => 1,
                                "ambiente" => $empresa->ambiente,
                                "tipoDte" => "14",
                                "numeroControl" => $datos["dteFactura"], // Valor entre comillas
                                "codigoGeneracion" => $datos["cGeneracion"],
                                "tipoModelo" => 1,
                                "tipoOperacion" => intval($datos["tipoTransmision"]),
                                "fecEmi" => date("Y-m-d"),
                                "horEmi" => date("H:i:s"),
                                "tipoMoneda" => "USD",
                                "tipoContingencia" => $datos["tipoContingencia"],
                                "motivoContin" => null,
                            ),
                            "emisor" => array(
                                "nit" => $empresa->nitEmpresa,
                                "nrc" => $empresa->nrcEmpresa,
                                "nombre" => $empresa->nombreEmpresa,
                                "codActividad" => $empresa->codActividadEmpresa,
                                "descActividad" => $empresa->descActividadEmpresa,
                                "direccion" => array(
                                    "departamento" => $empresa->departamento,
                                    "municipio" => $empresa->municipio,
                                    "complemento" => $empresa->direccionEmpresa,
                                ),
                                "telefono" => $empresa->telefonoEmpresa,
                                "codEstableMH" => "P001",
                                "codEstable" => null,
                                "codPuntoVentaMH" => "M001",
                                "codPuntoVenta" => null,
                                "correo" => $empresa->correoEmpresa,
                            ),

                            "sujetoExcluido" => array(
                                "tipoDocumento" => $datos["tipoDocumento"],
                                "numDocumento" => (string)$datos["documentoPaciente"],
                                "nombre" => $datos["nombrePaciente"],
                                "codActividad" => null,
                                "descActividad" => null,
                                "direccion" => array(
                                    "departamento" => $codigoDepartamento[0],
                                    "municipio" => $codigoMunicipio[0],
                                    "complemento" => $datos["complementoPaciente"],
                                ),
                                "telefono" => $datos["telefonoPaciente"],
                                "correo" => $datos["correoPaciente"] != "" ? $datos["correoPaciente"] : null,
        
                            ),
                            "cuerpoDocumento" => array(
                                array(
                                    "numItem" => 1,
                                    "tipoItem" => intval($datos["tipoVenta"]),
                                    "cantidad" => 1,
                                    "codigo" => null, // Convertido en string
                                    "uniMedida" => 59,
                                    "descripcion" => $datos["detalleServicio"],
                                    "precioUni" => (float)$total,
                                    "montoDescu" => 0,
                                    "compra" => (float)$total,
        
                                )
                            ),
                            "resumen" => array(
                                "totalCompra" =>(float)$total,
                                "descu" => 0,
                                "totalDescu" => 0,
                                "subTotal" => (float)$total,
                                "ivaRete1" => 0,
                                "reteRenta" => (float)$iva,
                                "totalPagar" => (float)$subtotal,
                                "totalLetras" => $letras,
                                "condicionOperacion" => 1,
                                "pagos" => [
                                    [
                                        "codigo" => "01",
                                        "montoPago" => (float)$subtotal,
                                        "referencia" => null,
                                        "plazo" => null,
                                        "periodo" => null,
                                    ],
                                ],
                                "observaciones" => null,


                            ),
                            "apendice" => null,
                        )
                    );

                        // Limpiando datos
                            unset($datos["identificacionAnterior"]);
                            unset($datos["emisorAnterior"]);
                            unset($datos["receptorAnterior"]);
                            unset($datos["cuerpoAnterior"]);
                            unset($datos["resumenAnterior"]);
                            unset($datos["rhAnterior"]);
                            unset($datos["jsonAnterior"]);
                        // Limpiando datos
                    $resp = $this->envioHaciendaSE($factura, $empresa, $datos, $arrayDTE, $tipoAnulacion, 40);
                    $datosNuevaFactura = unserialize(base64_decode(urldecode($resp)));

                    // Si habra nuevo documento
                        $this->procesarAnulacionSE($empresa, $datos["cGeneracion"],  $datosNuevaFactura, $jsonAnterior, $dteAnular, $tipoAnulacion, $datos);
                    // echo json_encode($arrayDTE);
                }else{
                    $this->procesarAnulacionSE($empresa, $generacion,  $datosNuevaFactura = null, $jsonAnterior, $dteAnular, $tipoAnulacion, $datos);
                }

                // echo json_encode($datos);

            }
            

            public function procesarAnulacionSE($empresa = null, $generacion = null,  $datosNuevaFactura = null, $jsonAnterior = null, $dteAnular = null, $pivoteAnulacion = null, $datos = null){
                $codigoGeneracionAnular = $this->codigo_generacion();
                // Creando factura
                    
                    $factura = array(
                        "nit" => $empresa->nitEmpresa,
                        "activo" => true, // Booleano sin comillas
                        "passwordPri" => $this->psPublica,
                        "dteJson" => array(
                            "identificacion" => array(
                                "version" => 2,
                                "ambiente" => $empresa->ambiente,
                                "codigoGeneracion" => $codigoGeneracionAnular,
                                "fecAnula" => date("Y-m-d"),
                                "horAnula" => date("H:i:s"),
                                
                            ),
                            "emisor" => array(
                                "nit" => $empresa->nitEmpresa,
                                "nombre" => $empresa->nombreEmpresa,
                                "tipoEstablecimiento" => "02",
                                "nomEstablecimiento" => $empresa->nombreEmpresa,
                                "codEstableMH" => "P001",
                                "codEstable" => null,
                                "codPuntoVentaMH" => "M001",
                                "codPuntoVenta" => null,
                                "telefono" => $empresa->telefonoEmpresa,
                                "correo" => $empresa->correoEmpresa,
                            ),
                            "documento" => array(
                                "tipoDte" => $jsonAnterior["dteJson"]["identificacion"]["tipoDte"],
                                "codigoGeneracion" => $jsonAnterior["dteJson"]["identificacion"]["codigoGeneracion"],
                                "selloRecibido" => $jsonAnterior["selloRecibido"],
                                "numeroControl" => $jsonAnterior["dteJson"]["identificacion"]["numeroControl"],
                                "fecEmi" => $jsonAnterior["dteJson"]["identificacion"]["fecEmi"],
                                "montoIva" => null,
                                "codigoGeneracionR" => null,
                                "tipoDocumento" => $jsonAnterior["dteJson"]["sujetoExcluido"]["tipoDocumento"],
                                "numDocumento" => $jsonAnterior["dteJson"]["sujetoExcluido"]["numDocumento"],
                                "nombre" => $jsonAnterior["dteJson"]["sujetoExcluido"]["nombre"],
                                "telefono" => $jsonAnterior["dteJson"]["sujetoExcluido"]["telefono"],
                                "correo" => $jsonAnterior["dteJson"]["sujetoExcluido"]["correo"]
                            ),

                            "motivo" => array(
                                "tipoAnulacion" => intval($datos["tipoAnulacion"]),
                                "motivoAnulacion" => $datos["detalleAnulacion"],
                                "nombreResponsable" => $this->session->userdata('nombreEmpleado'),
                                "tipDocResponsable" => "13",
                                "numDocResponsable" => "043604851",
                                "nombreSolicita" => $jsonAnterior["dteJson"]["sujetoExcluido"]["nombre"],
                                "tipDocSolicita" => "13",
                                "numDocSolicita" => $jsonAnterior["dteJson"]["sujetoExcluido"]["numDocumento"]
                            ),

                        )
                    );
                // Creando factura
                if($pivoteAnulacion == 1){
                    $factura["dteJson"]["documento"]["codigoGeneracionR"] = $generacion;
                }




                // Firmando documento
                    $documentoFirmado = json_decode($this->firmar_DTE($factura));
                // Firmando documento
                
                // echo json_encode($factura);
                
                if($documentoFirmado->status == "OK"){
                    // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                    $url = $this->urlHacienda."anulardte"; // Cambia a URL PROD en producción
                    $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                    $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                    // Datos del cuerpo de la solicitud
                        $documento = [
                            "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                            "idEnvio"       => time(), // Identificador de envío único
                            "version"       => 2, // Versión del JSON del DTE
                            "documento"     => $documentoFirmado->body, // Documento firmado
                        ];
                        // "codigoGeneracion" => $codigoGeneracionAnular // Código de generación
                    // Datos del cuerpo de la solicitud
                            
                    // Configurar los encabezados
                        $curl = new Curl();
                        $curl->setHeader("Authorization", $token);
                        $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                        $curl->setHeader("Content-Type", "application/json");
                    // Configurar los encabezados
                            
                    $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    
                    $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                    $response = json_decode($resp->response); // Verificar la respuesta
                    $mensaje = $response->descripcionMsg;
                    
                    // echo json_encode($response );
                    
                    if($response->estado == "PROCESADO"){
                    
                        $curl->close();
                        $nuevoDTE = $datosNuevaFactura["filaDB"];
                        $resp = $this->Facturacion_Model->anularDTESE($dteAnular, $nuevoDTE, $pivoteAnulacion);
                        if($pivoteAnulacion == 1){
                            redirect(base_url()."Facturacion/fin_se/$nuevoDTE/$mensaje/");
                        }else{
                            $this->session->set_flashdata("exito","DTE anulado con exito");
                            redirect(base_url()."Facturacion/anular_se/$dteAnular/");
                        }

                    }else{
                        $curl->close();
                        // $this->session->set_flashdata("error","DTE no pudo ser anulado");
                        redirect(base_url()."Facturacion/fin_se/0/$mensaje/");
                    }

                }else{
                    $this->session->set_flashdata("error","No se ha podido firmar el documento");
                    redirect(base_url()."Facturacion/anular_se/$dteAnular/");
                }

                // echo json_encode($factura);

            }
        // Anular


    // Sujeto excluido

    // Contingencia
        public function lista_contingencias(){
            
            $data["lista"] = $this->Facturacion_Model->listaContingencias();
            $this->load->view('Base/header');
            $this->load->view('FE/lista_contingencias', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function detalle_contingencia($c = null){
            $data["detalle"] = $this->Facturacion_Model->detalleContingencia($c);
            $this->load->view('Base/header');
            $this->load->view('FE/detalle_contingencia', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function agregar_contingencia(){
            
            $data["empresa"] = $this->Facturacion_Model->obtenerEmpresa();
            $data["cGeneracion"] = $this->codigo_generacion();

            
            $this->load->view('Base/header');
            $this->load->view('FE/agregar_contingencia', $data);
            $this->load->view('Base/footer');

            // echo json_encode($data);
        }

        public function crear_contingencia(){
            $datos = $this->input->post();
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            $fc = $this->Facturacion_Model->obtenerDocumentoContingencia(1); // Factura comercial
            $ccf = $this->Facturacion_Model->obtenerDocumentoContingencia(3); // Factura comercial
            $se = $this->Facturacion_Model->obtenerDocumentoContingencia(14); // Factura comercial
            $archivosEnContingencia = array();
            $indice = 1;

            // Validando codigo generacion
                $tipo = 20;
                $anio = date("Y");
                $strCodigo = $this->Facturacion_Model->validaCodigoGeneracion($datos["cGeneracion"], $anio, $tipo);
                if($strCodigo->codigo != 0){
                    $datos["cGeneracion"] = $this->codigo_generacion();
                }
            // Validando codigo generacion

            
            if(count($fc) > 0){
                foreach ($fc as $row) {
                    $jsonDTE = unserialize(base64_decode(urldecode($row->jsonDTE)));                
                    $archivosEnContingencia[] = [
                        "noItem" => $indice, // numItem empieza en 1 y se incrementa
                        "codigoGeneracion" => $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"], // Codigo de generacion de la factura
                        "tipoDoc" => "01" // Tipo de documento
                    ];
                    $indice++;
                    
                }
            }
            
            if(count($ccf) > 0){
                foreach ($ccf as $row) {
                    $jsonDTE = unserialize(base64_decode(urldecode($row->jsonDTE)));                
                    $archivosEnContingencia[] = [
                        "noItem" => $indice, // numItem empieza en 1 y se incrementa
                        "codigoGeneracion" => $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"], // Codigo de generacion de la factura
                        "tipoDoc" => "03" // Tipo de documento
                    ];
                    $indice++;
                    
                }
            }

            if(count($se) > 0){
                foreach ($se as $row) {
                    $jsonDTE = unserialize(base64_decode(urldecode($row->jsonDTE)));                
                    $archivosEnContingencia[] = [
                        "noItem" => $indice, // numItem empieza en 1 y se incrementa
                        "codigoGeneracion" => $jsonDTE["dteJson"]["identificacion"]["codigoGeneracion"], // Codigo de generacion de la factura
                        "tipoDoc" => "14" // Tipo de documento
                    ];
                    $indice++;
                    
                }
            }

            
            $factura = array(
                "nit" => $empresa->nitEmpresa,
                "activo" => true, // Booleano sin comillas
                "passwordPri" => $this->psPublica,
                "dteJson" => array(
                    "identificacion" => array(
                        "version" => 3,
                        "ambiente" => $empresa->ambiente,
                        "codigoGeneracion" => $datos["cGeneracion"],
                        "fTransmision" => date("Y-m-d"),
                        "hTransmision" => date("H:i:s"),
                    ),
                    "emisor" => array(
                        "nit" => $empresa->nitEmpresa,
                        "nombre" => $empresa->nombreEmpresa,
                        "nombreResponsable" => $this->session->userdata('nombreEmpleado'),
                        "tipoDocResponsable" => "13",
                        "numeroDocResponsable" => $this->session->userdata('duiEmpleado'),
                        "tipoEstablecimiento" => "02",
                        "codEstableMH" => null,
                        "codPuntoVenta" => null,
                        "telefono" => $empresa->telefonoEmpresa,
                        "correo" => $empresa->correoEmpresa,

                    ),

                    "detalleDTE" => $archivosEnContingencia,

                    "motivo" => array(
                        "fInicio" => $datos["inicioContingencia"],
                        "fFin" => $datos["finContingencia"],
                        "hInicio" => $datos["hinicioContingencia"],
                        "hFin" => $datos["hfinContingencia"],
                        "tipoContingencia" => intval($datos["tipoContingencia"]),
                        "motivoContingencia" => $datos["textoContingencia"],
                    ),
                )
            );
            
            // Existe contingencia, el documento solo se firmara y guardara
            $documentoFirmado = json_decode($this->firmar_DTE($factura));

            if($documentoFirmado->status == "OK"){
                $arrayDTE["generacion"] = $datos["cGeneracion"]; // Datos de sistema local
                $arrayDTE["motivo"] = $datos["textoContingencia"]; // Datos de sistema local
                


                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."contingencia"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "nit"      => $empresa->nitEmpresa, // "00" para prueba, "01" para producción
                        "documento"     => $documentoFirmado->body, // Documento firmado
                    ];
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                // echo json_encode($response );
                if($response->estado == "RECIBIDO"){

                    $factura['selloRecibido'] = $response->selloRecibido;
                    $arrayDTE["json"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda; // Datos de sistema local

                    $curl->close();
                    
                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, 60);

                    if($pivoteDTE > 0){
                        $this->session->set_flashdata("exito","El documento se proceso exitosamente en hacienda");
                        redirect(base_url()."Facturacion/agregar_contingencia");

                    }else{
                        $this->session->set_flashdata("exito","El documento no se proceso exitosamente en hacienda");
                        redirect(base_url()."Facturacion/agregar_contingencia");
                    }
                    
                }else{
                    $curl->close();
                    $this->session->set_flashdata("exito","No se ha podido validar el documento en hacienda".$response->descripcionMsg);
                    redirect(base_url()."Facturacion/agregar_contingencia/");

                }

            }

            // echo json_encode($factura);
        }

        public function documentos_en_contingencia(){

            $contingencia = $this->Facturacion_Model->obtenerContingencia(1);
            $jsonContingencia = unserialize(base64_decode(urldecode($contingencia->textoDocumento)));
            $sello = $jsonContingencia["selloRecibido"];

            if($sello != ""){
                $empresa = $this->Facturacion_Model->obtenerEmpresa();
                $fc = $this->Facturacion_Model->obtenerDocumentoContingencia(1); // Factura comercial
                $ccf = $this->Facturacion_Model->obtenerDocumentoContingencia(3); // Factura comercial
                $firmas = array();
                $indice = 1;
                
                /* if(count($fc) > 0){
                    foreach ($fc as $row) {
                        $firmas[] = trim($row->firma);
                        
                    }
                } */
                
                if(count($ccf) > 0){
                    foreach ($ccf as $row) {
                        $firmas[] = trim($row->firma);
                    }
                }
                
                // echo json_encode($firmas);
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."recepcionlote/"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";

                // Datos del cuerpo de la solicitud
                    $documentos = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                        "idEnvio"       => $this->codigo_generacion(),
                        "version"       => 1, // Versión del JSON del DTE
                        "nitEmisor"       => $empresa->nitEmpresa, // Tipo de DTE
                        "documentos"     => $firmas, // Documento firmado
                    ];
                // Datos del cuerpo de la solicitud
                
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados

                $jsonData = json_encode($documentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                // echo json_encode($documentos);
                if($response->codigoMsg == "001"){
                    $curl->close();
                    $data["json"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda; // Datos de sistema local
                    $data["contingencia"] = $contingencia->idDocumento;
                    $pivoteDTE = $this->Facturacion_Model->actualizarContingencia($data);

                    $this->session->set_flashdata("exito","El documento se proceso exitosamente en hacienda");
                    redirect(base_url()."Facturacion/agregar_contingencia/");
                }else{
                    $curl->close();
                    $this->session->set_flashdata("exito","No se ha podido validar el documento en hacienda".$response->descripcionMsg);
                    redirect(base_url()."Facturacion/agregar_contingencia/");

                }


                // echo json_encode($contingencia);
            }

 
            
            // echo json_encode($sello );
        }

        public function consultar_lote(){
            $contingencia = $this->Facturacion_Model->obtenerContingencia(0);
            $jsonLote = unserialize(base64_decode(urldecode($contingencia->fueLote)));
            $codigoLote = $jsonLote->codigoLote;
            
            // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
            $url = $this->urlHacienda."recepcion/consultadtelote/".$codigoLote; // Cambia a URL PROD en producción
            $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
            $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";

            
            // Configurar los encabezados
                $curl = new Curl();
                $curl->setHeader("Authorization", $token);
                $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                $curl->setHeader("Content-Type", "application/json");
            // Configurar los encabezados

            $resp = $curl->get($url); // Enviar la petición POST con los datos
            $response = json_decode($resp->response); // Verificar la respuesta
            echo json_encode($response);
        }

        public function corregir_dte(){

            $ccf = $this->Facturacion_Model->obtenerDocumentoContingencia(1); 
            foreach ($ccf as $row) {
                $json = unserialize(base64_decode(urldecode($row->jsonDTE)));
                $json["dteJson"]["resumen"]["totalLetras"] = "CERO 29/100";

                // $documentoFirmado = json_decode($this->firmar_DTE($json));
                echo json_encode($documentoFirmado);
            }


            // echo json_encode($ccf);
        }

        public function factura_en_contingencia($dte = null){
            $contingencia = $this->Facturacion_Model->facturaEnContingencia($dte, 1);
            $jsonContingencia = unserialize(base64_decode(urldecode($contingencia->jsonDTE)));
            $firma = $contingencia->firma;
            // $jsonContingencia["dteJson"]["identificacion"]["tipoContingencia"] = 4;
            $codigoGeneracion = $jsonContingencia["dteJson"]["identificacion"]["codigoGeneracion"];

            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            
            // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
            $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
            $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
            $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";

            // Datos del cuerpo de la solicitud
                $documento = [
                    "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                    "idEnvio"       => time(), // Identificador de envío único
                    "version"       => 1, // Versión del JSON del DTE
                    "tipoDte"       => "01", // Tipo de DTE
                    "documento"     => $firma, // Documento firmado
                    "codigoGeneracion" => $codigoGeneracion// Código de generación
                ];

            // Datos del cuerpo de la solicitud
            
            // Configurar los encabezados
                $curl = new Curl();
                $curl->setHeader("Authorization", $token);
                $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                $curl->setHeader("Content-Type", "application/json");
            // Configurar los encabezados

            $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
            $response = json_decode($resp->response); // Verificar la respuesta
            // echo json_encode($response );
            if($response->estado == "PROCESADO"){
                $curl->close();
                $hacienda = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                $jsonContingencia['firmaElectronica'] = $firma;
                $jsonContingencia['selloRecibido'] = $response->selloRecibido;
                $strSerial = urlencode(base64_encode(serialize($jsonContingencia))); // Respuesta de hacienda
                
                $bool = $this->Facturacion_Model->actualizarDTE($strSerial, $hacienda, $dte, 1);

                if($bool){
                    $exito = "El documento se proceso exitosamente en hacienda";
                    redirect(base_url()."Facturacion/fin_factura_electronica/$dte/$exito/");
                }else{
                    $error = "No se ha podido validar el documento en hacienda";
                    redirect(base_url()."Facturacion/lista_facturas");
                }
                

            }else{
                $curl->close();
                $error = "No se ha podido validar el documento en hacienda";
                redirect(base_url()."Facturacion/lista_facturas");
            }

            // echo json_encode($firmas);

        }

        public function se_en_contingencia($dte = null){
            $contingencia = $this->Facturacion_Model->facturaEnContingencia($dte, 14);
            $jsonContingencia = unserialize(base64_decode(urldecode($contingencia->jsonDTE)));
            $firma = $contingencia->firma;
            
            $codigoGeneracion = $jsonContingencia["dteJson"]["identificacion"]["codigoGeneracion"];

            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            
            // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
            $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
            $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
            $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";

            // Datos del cuerpo de la solicitud
                $documento = [
                    "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                    "idEnvio"       => time(), // Identificador de envío único
                    "version"       => 1, // Versión del JSON del DTE
                    "tipoDte"       => "14", // Tipo de DTE
                    "documento"     => $firma, // Documento firmado
                    "codigoGeneracion" => $codigoGeneracion// Código de generación
                ];

            // Datos del cuerpo de la solicitud
            
            // Configurar los encabezados
                $curl = new Curl();
                $curl->setHeader("Authorization", $token);
                $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                $curl->setHeader("Content-Type", "application/json");
            // Configurar los encabezados

            $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
            $response = json_decode($resp->response); // Verificar la respuesta
            //  echo json_encode($response );
            if($response->estado == "PROCESADO"){
                $curl->close();
                $hacienda = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                $jsonContingencia['firmaElectronica'] = $firma;
                $jsonContingencia['selloRecibido'] = $response->selloRecibido;
                $strSerial = urlencode(base64_encode(serialize($jsonContingencia))); // Respuesta de hacienda
                
                $bool = $this->Facturacion_Model->actualizarDTE($strSerial, $hacienda, $dte, 14);

                if($bool){
                    $exito = "El documento se proceso exitosamente en hacienda";
                    redirect(base_url()."Facturacion/fin_se/$dte/$exito/");
                }else{
                    $error = "No se ha podido validar el documento en hacienda";
                    redirect(base_url()."Facturacion/lista_se");
                }
                

            }else{
                $curl->close();
                $error = "No se ha podido validar el documento en hacienda";
                redirect(base_url()."Facturacion/lista_se");
            }


        }


        public function ccf_en_contingencia($dte = null){
            $contingencia = $this->Facturacion_Model->facturaEnContingencia($dte, 2);
            $jsonContingencia = unserialize(base64_decode(urldecode($contingencia->jsonDTE)));
            $firma = $contingencia->firma;
            // $jsonContingencia["dteJson"]["identificacion"]["tipoContingencia"] = 4;
            $codigoGeneracion = $jsonContingencia["dteJson"]["identificacion"]["codigoGeneracion"];

            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            // $documentoFirmado = json_decode($this->firmar_DTE($jsonContingencia));

            
            // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
            $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
            $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
            $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";

            // Datos del cuerpo de la solicitud
                $documento = [
                    "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                    "idEnvio"       => time(), // Identificador de envío único
                    "version"       => 3, // Versión del JSON del DTE
                    "tipoDte"       => "03", // Tipo de DTE
                    "documento"     => $firma, // Documento firmado
                    "codigoGeneracion" => $codigoGeneracion// Código de generación
                ];

            // Datos del cuerpo de la solicitud
            
            // Configurar los encabezados
                $curl = new Curl();
                $curl->setHeader("Authorization", $token);
                $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                $curl->setHeader("Content-Type", "application/json");
            // Configurar los encabezados

            $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
            $response = json_decode($resp->response); // Verificar la respuesta
            // echo json_encode($response );
            if($response->estado == "PROCESADO"){
                $curl->close();
                $hacienda = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                $jsonContingencia['firmaElectronica'] = $firma;
                $jsonContingencia['selloRecibido'] = $response->selloRecibido;

                $strSerial = urlencode(base64_encode(serialize($jsonContingencia))); // Respuesta de hacienda
                
                $bool = $this->Facturacion_Model->actualizarDTE($strSerial, $hacienda, $dte, 2);

                if($bool){
                    $exito = "El documento se proceso exitosamente en hacienda";
                    redirect(base_url()."Facturacion/fin_ccf/$dte/$exito/");
                }else{
                    $error = "No se ha podido validar el documento en hacienda";
                    redirect(base_url()."Facturacion/lista_ccf");
                }
                

            }else{
                $curl->close();
                $error = "No se ha podido validar el documento en hacienda";
                redirect(base_url()."Facturacion/lista_ccf");
            }


            // echo json_encode($jsonContingencia);

        }


        public function consultar_dte($dte = null){
            $datos = unserialize(base64_decode(urldecode($dte)));

            $codigo = $datos["codigo"];
            $tipo = $datos["tipo"];
            $empresa = $this->Facturacion_Model->obtenerEmpresa();
            
            // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
            $url = $this->urlHacienda."recepcion/consultadte/"; // Cambia a URL PROD en producción
            $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
            $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
            
            // Datos del cuerpo de la solicitud
            $documento = [
                "nitEmisor"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                "tdte"       => $tipo, // Identificador de envío único
                "codigoGeneracion" => $codigo // Código de generación
                ];
                
            // Datos del cuerpo de la solicitud

            
            // Configurar los encabezados
                $curl = new Curl();
                $curl->setHeader("Authorization", $token);
                $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                $curl->setHeader("Content-Type", "application/json");
            // Configurar los encabezados

             $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
            $response = json_decode($resp->response); // Verificar la respuesta
            
            $data["respuesta"] = $response;

            echo '<p><strong>Estado: </strong>'.$response->estado.'</p>';
            echo '<p><strong>Código: </strong>'.$response->codigoGeneracion.'</p>';
            echo '<p><strong>Sello recibido: </strong>'.$response->selloRecibido.'</p>';
            echo '<p><strong>Fecha procesamiento: </strong>'.$response->fhProcesamiento.'</p>';

            // echo json_encode($documento);
        }
        

    // Contingencia


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

    public function obtener_dte($tipo, $anio){
        $str = "DTE-";
        $strTipo = $this->agregar_ceros($tipo, 2);
        $str = $str.$strTipo."-".$this->establecimiento."P001-";
        $numeroBase = 0;
        $obtenerNumero = $this->Facturacion_Model->validarDTE($tipo, $anio);
        if($obtenerNumero->actual == 0){
            $numeroBase = 1;
        }else{
            $numeroBase = $obtenerNumero->actual + 1;
        }
        $codigo = $this->agregar_ceros($numeroBase, 15);

        $str = $str.$codigo;

        return $str.".".$numeroBase;
        
    }

    private function agregar_ceros($numero, $longitud) {
        return str_pad($numero, $longitud, '0', STR_PAD_LEFT);
    }

    private function codigo_generacion() {   
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
        $uuid[19] = strtoupper(dechex((hexdec($uuid[19]) & 0x3) | 0x8)); // Variant (10xx)
        
        // Convertir a mayúsculas nuevamente (por si alguna minúscula quedó en la manipulación)
        $uuid = strtoupper($uuid);
        return $uuid;
    }

    private function detalleFactura($destino = null){
        $strDestino = "";
        switch ($destino) {
            case '1':
                $strDestino = "MEDICAMENTOS E INSUMOS MÉDICOS";
                break;
            case '2':
                $strDestino = "EXAMENES DE ULTRASONOGRAFÍA";
                break;
            case '3':
                $strDestino = "EXAMENES DE RAYOS X";
                break;
            case '4':
                $strDestino = "EXAMENES DE LABORATORIO CLINICO";
                break;
            
            default:
                $strDestino = "MEDICAMENTOS E INSUMOS MÉDICOS";
                break;
        }

        return $strDestino;

    }


     // Configuración del renderizador (SVG como formato de salida)
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

    // Numero a letras
        private function basico($numero) {
            $valor = array ('cero', 'uno','dos','tres','cuatro','cinco','seis','siete','ocho',
            'nueve','diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
            'veinte', 'veintiuno', 'veintidos', 'veintitres', 'veinticuatro','veinticinco', 'veintiseis','veintisiete','veintiocho','veintinueve');
            return $valor[$numero];
        }
        
        private function decenas($n) {
            $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta', 70=>'setenta',80=>'ochenta',90=>'noventa');
            if( $n <= 29) return $this->basico($n);
            $x = $n % 10;
            if ( $x == 0 ) {
            return $decenas[$n];
            } else return $decenas[$n - $x].' y '. $this->basico($x);
        }
        
        private function centenas($n) {
            $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trescientos', 400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
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
        
        private function miles($n) {
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
        
        private function millones($n) {
            if($n == 1000000) {return 'un millón';}
            else {
                $l = strlen($n);
                $c = (int)substr($n,0,$l-6);
                $x = (int)substr($n,-6);
                if($c == 1) {
                    $cadena = ' millón ';
                } else {
                    $cadena = ' millones ';
                }
                return $this->miles($c).$cadena.(($x > 0)?$this->miles($x):'');
            }
        }
        
        private function convertir($n) {
            switch (true) {
            case ( $n >= 0 && $n < 30) : return $this->basico($n); break;
            case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
            case ( $n >= 100 && $n < 1000) : return $this->centenas($n); break;
            case ($n >= 1000 && $n <= 999999): return $this->miles($n); break;
            case ($n >= 1000000): return $this->millones($n);
            }
        }
    // Numero a letras

    private function firmar_DTE($factura = null){
        $jsonData = json_encode($factura, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');  // Indicamos que el contenido es JSON
        $curl->setHeader('Accept', 'application/json');         // Aceptamos respuestas en formato JSON
        $resp = $curl->post('http://localhost:8113/firmardocumento/', $jsonData); //Cambiar a la direccion en la nube
        $respuesta = $resp->response;
        return $respuesta;

    }

    /* private function conectar_API(){
        // URL de autenticación
        $url = "https://apitest.dtes.mh.gob.sv/seguridad/auth";

        // Datos de autenticación
        $data = [
            "user" => "06142405161029", // Usuario proporcionado
            "pwd" => "Genesis_$2025" // Contraseña proporcionada
        ];

        // Inicializar Curl
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $curl->setHeader('User-Agent', 'hospitalOrellana/1.0 (ElSalvador; PHP; Codeigniter'); // Opcional, pero recomendado

        // Realizar la solicitud POST
        $curl->post($url, $data);

        return $curl->response;


    } */


    public function envioHaciendaCCF($factura = null, $empresa = null, $datos = null, $arrayDTE = null, $pivoteReturn = null, $pivoteInsert = null){
        if($factura != null){
            // Firmando documento
                $documentoFirmado = json_decode($this->firmar_DTE($factura));
            // Firmando documento

            // echo json_encode($arrayDTE);
     
             
            if($documentoFirmado->status == "OK"){
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                        "idEnvio"       => time(), // Identificador de envío único
                        "version"       => 3, // Versión del JSON del DTE
                        "tipoDte"       => "03", // Tipo de DTE
                        "documento"     => $documentoFirmado->body, // Documento firmado
                        "codigoGeneracion" => $datos["cGeneracion"] // Código de generación
                    ];
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                /* echo json_encode($response);
                echo json_encode($factura); */
                if($response->estado == "PROCESADO"){

                    $factura['firmaElectronica'] = $documentoFirmado->body;
                    $factura['selloRecibido'] = $response->selloRecibido;

                    $arrayDTE["cGeneracion"] = $datos["cGeneracion"];
                    $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                    $arrayDTE["paramsHacienda"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                    $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local
                    $curl->close();

                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, $pivoteInsert);

                    if($pivoteReturn == 1){
                        $dataAnulacion["mensaje"] = $response->descripcionMsg;
                        $dataAnulacion["filaDB"] = $pivoteDTE;
                        $codificacionAnular = urlencode(base64_encode(serialize($dataAnulacion)));
                        return $codificacionAnular;
                    }else{
                        
                        $exito = "El documento se proceso exitosamente en hacienda";
                        redirect(base_url()."Facturacion/fin_ccf/$pivoteDTE/$exito/");
                    } 
                }else{
                    $curl->close();
                    $msg = urlencode(base64_encode(serialize($response->descripcionMsg)));
                    $error = "No se ha podido validar el documento en hacienda-".$msg;
                    redirect(base_url()."Facturacion/fin_ccf/0/$error/");
                }

            }else{
                $this->session->set_flashdata("error","No se ha podido firmar el documento");
                redirect(base_url()."Facturacion/agregar_ccf/");
            }
        }
    }

    public function envioHaciendaCF($factura = null, $empresa = null, $datos = null, $arrayDTE = null, $pivoteReturn = null, $pivoteInsert = null, $datosVenta = null){
        if($factura != null){
            // Firmando documento
                $documentoFirmado = json_decode($this->firmar_DTE($factura));
            // Firmando documento
                 
             
            if($documentoFirmado->status == "OK"){
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                        "idEnvio"       => time(), // Identificador de envío único
                        "version"       => 1, // Versión del JSON del DTE
                        "tipoDte"       => "01", // Tipo de DTE
                        "documento"     => $documentoFirmado->body, // Documento firmado
                        "codigoGeneracion" => $datos["cGeneracion"] // Código de generación
                    ];
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                /* echo json_encode($factura);
                echo json_encode($response ); */
                if($response->estado == "PROCESADO"){
                    if($datosVenta != null){
                        $p = $datosVenta["personalizado"];
                        unset($datosVenta["personalizado"]);
                        if($p == 1){
                            $resp = $this->Ventas_Model->guardarVentaCF($datosVenta);
                        }else{
                            $resp = $this->Ventas_Model->guardarVenta($datosVenta);
                        }
                        $arrayDTE["idVenta"] = $resp;
                        $arrayDTE["generacion"] = $datos["cGeneracion"];
                    }

                    $factura['firmaElectronica'] = $documentoFirmado->body;
                    $factura['selloRecibido'] = $response->selloRecibido;

                    $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                    $arrayDTE["paramsHacienda"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                    $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local

                    $curl->close();
                    
                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, $pivoteInsert);

                    if($pivoteReturn == 1){
                        $dataAnulacion["mensaje"] = $response->descripcionMsg;
                        $dataAnulacion["filaDB"] = $pivoteDTE;
                        $codificacionAnular = urlencode(base64_encode(serialize($dataAnulacion)));
                        return $codificacionAnular;
                    }else{
                        $exito = "El documento se proceso exitosamente en hacienda";
                        redirect(base_url()."Facturacion/fin_factura_electronica/$pivoteDTE/$exito/");
                    }
                    

                }else{
                    $curl->close();
                    $error = "No se ha podido validar el documento en hacienda".$response->descripcionMsg;
                    redirect(base_url()."Facturacion/fin_factura_electronica/0/$error/");
                }

            }else{
                $this->session->set_flashdata("error","No se ha podido firmar el documento");
                redirect(base_url()."Facturacion/agregar_consumidor_final/");
            }
        }
    }

    public function envioHaciendaSE($factura = null, $empresa = null, $datos = null, $arrayDTE = null, $pivoteReturn = null, $pivoteInsert = null){
        if($factura != null){
            // Firmando documento
                $documentoFirmado = json_decode($this->firmar_DTE($factura));
            // Firmando documento
                 
             
            if($documentoFirmado->status == "OK"){
                // $conexion = json_decode($this->conectar_API()); funcion dentro del controlador
                $url = $this->urlHacienda."recepciondte"; // Cambia a URL PROD en producción
                $token = $this->tokenHacienda; // Token obtenido del servicio de autenticación
                $userAgent = "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter/4.4.0)";
                // Datos del cuerpo de la solicitud
                    $documento = [
                        "ambiente"      => $empresa->ambiente, // "00" para prueba, "01" para producción
                        "idEnvio"       => time(), // Identificador de envío único
                        "version"       => 1, // Versión del JSON del DTE
                        "tipoDte"       => "14", // Tipo de DTE
                        "documento"     => $documentoFirmado->body, // Documento firmado
                        "codigoGeneracion" => $datos["cGeneracion"] // Código de generación
                    ];
                // Datos del cuerpo de la solicitud
                        
                // Configurar los encabezados
                    $curl = new Curl();
                    $curl->setHeader("Authorization", $token);
                    $curl->setHeader("User-Agent", "hospitalOrellana/1.0 (ElSalvador; PHP; CodeIgniter)");
                    $curl->setHeader("Content-Type", "application/json");
                // Configurar los encabezados
                        
                $jsonData = json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
                $resp = $curl->post($url,  $jsonData); // Enviar la petición POST con los datos
                $response = json_decode($resp->response); // Verificar la respuesta
                // echo json_encode($response );
                if($response->estado == "PROCESADO"){

                    $factura['firmaElectronica'] = $documentoFirmado->body;
                    $factura['selloRecibido'] = $response->selloRecibido;

                    $arrayDTE["paramsFactura"] = urlencode(base64_encode(serialize($factura))); // Respuesta de hacienda
                    $arrayDTE["paramsHacienda"] = urlencode(base64_encode(serialize($response))); // Respuesta de hacienda obtenida desde la API
                    $arrayDTE["paramsLocales"] = urlencode(base64_encode(serialize($datos))); // Datos de sistema local

                    $curl->close();
                    
                    $pivoteDTE = $this->Facturacion_Model->guardarDTE($arrayDTE, $pivoteInsert);

                    if($pivoteReturn == 1){
                        $dataAnulacion["mensaje"] = $response->descripcionMsg;
                        $dataAnulacion["filaDB"] = $pivoteDTE;
                        $codificacionAnular = urlencode(base64_encode(serialize($dataAnulacion)));
                        return $codificacionAnular;
                    }else{
                        $exito = "El documento se proceso exitosamente en hacienda";
                        redirect(base_url()."Facturacion/fin_se/$pivoteDTE/$exito/");
                    }
                    

                }else{
                    $curl->close();
                    $error = "No se ha podido validar el documento en hacienda".$response->descripcionMsg;
                    redirect(base_url()."Facturacion/fin_se/0/$error/");
                }

            }else{
                $this->session->set_flashdata("error","No se ha podido firmar el documento");
                redirect(base_url()."Facturacion/agregar_sujeto_excluido/");
            }
        }
    }

    public function recomendaciones_proveedores(){
        if($this->input->is_ajax_request()){
            $id =$this->input->get("id");
            $data = $this->Facturacion_Model->buscarRecomendaciones(trim($id));
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function validar_proveedor(){
        if($this->input->is_ajax_request()){
            $datos =$this->input->post();
            $data = $this->Facturacion_Model->buscarProveedor($datos);
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }


    // Archivos locales
        public function lista_documentos_generados(){
            // Ruta de la carpeta donde se guardan los archivos
            $dir = FCPATH . 'public/archivos_pdf/';
            // Obtener todos los archivos de la carpeta
            $archivos = array_diff(scandir($dir), array('..', '.')); // Excluye '.' y '..'
            
            // Crear un array con información de los archivos
            $archivos_info = [];
            
            foreach ($archivos as $archivo) {
                // Verificar si es un archivo PDF
                if (pathinfo($archivo, PATHINFO_EXTENSION) == 'pdf') {
                    $nombre_json = pathinfo($archivo, PATHINFO_FILENAME) . '.json'; // Buscar el json correspondiente
                    
                    $json_path = $dir . $nombre_json;
                    
                    // Si existe el archivo JSON correspondiente
                    if (file_exists($json_path)) {
                        
                        // Leer el JSON
                        $json_data = json_decode(file_get_contents($json_path), true);

                        // echo json_encode($json_data["receptor"]["correo"]);

                        
                        // Verificar si la clave 'fecha' existe en el JSON
                        if (isset($json_data["identificacion"]["fecEmi"])) {
                            // Convertir la fecha a un timestamp para ordenarla numéricamente
                            $timestamp_fecha = strtotime($json_data["identificacion"]["fecEmi"]);
                            
                            $archivos_info[] = [
                                'nombre_pdf' => mb_convert_encoding($archivo, 'UTF-8', 'auto'),
                                'nombre_json' => $nombre_json,
                                'fecha' => $timestamp_fecha, // Almacenar como timestamp
                                'fecha_normal' => $json_data["identificacion"]["fecEmi"], // Almacenar como timestamp
                                'correo' => $json_data["receptor"]["correo"] ?? ($json_data["sujetoExcluido"]["correo"] ?? '-'),
                                'tam_pdf' => filesize($dir . $archivo),
                                'tam_json' => filesize($json_path),
                                'tipo' => $this->get_tipo_archivo($archivo)
                            ];
                        } else {
                            // Si no existe la clave 'fecha', omitir este archivo
                            log_message('error', "Archivo JSON sin la clave 'fecha': " . $nombre_json);
                        }
                    }
                }
            }

            // Ordenar los archivos por fecha de forma ascendente
            usort($archivos_info, function($a, $b) {
                return $a['fecha'] - $b['fecha'];
            });
            
            // Pasar los archivos a la vista
            $data['lista_archivos'] = $archivos_info;

            $this->load->view('Base/header');
            $this->load->view('FE/lista_archivos', $data);
            $this->load->view('Base/footer');


            // echo json_encode($data['lista_archivos']);
        }

        private function get_tipo_archivo($nombre_archivo) {
            if (strpos($nombre_archivo, 'CCF') === 0) {
                return 'Crédito  Fiscal';
            } elseif (strpos($nombre_archivo, 'FC') === 0) {
                return 'Factura Comercial';
            } elseif (strpos($nombre_archivo, 'SE') === 0) {
                return 'Sujeto Excluido';
            }elseif (strpos($nombre_archivo, 'NC') === 0) {
                return 'Nota de crédito ';
            }elseif (strpos($nombre_archivo, 'ND') === 0) {
                return 'Nota de débito';
            }
            return 'Otro';
        }

        /* public function descargar_todos() {
            // Obtener los archivos seleccionados del formulario
            $archivos_seleccionados = $this->input->post('archivos');
            
            // Ruta de la carpeta de archivos PDF y JSON
            $dir_pdf = FCPATH . 'public/archivos_pdf/';
            
            // Crear el archivo ZIP
            $zip_name = 'archivos_descargados.zip';
            $zip_path = FCPATH . 'public/archivos_pdf/' . $zip_name;
            
            // Crear un objeto ZipArchive
            $zip = new ZipArchive();
    
            if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
                // Añadir los archivos seleccionados al ZIP
                foreach ($archivos_seleccionados as $archivo) {
                    $archivo = json_decode($archivo, true); // Decodificar JSON del archivo seleccionado
    
                    // Añadir PDF
                    $pdf_path = $dir_pdf . $archivo['nombre_pdf'];
                    if (file_exists($pdf_path)) {
                        $zip->addFile($pdf_path, $archivo['nombre_pdf']);
                    }
    
                    // Añadir JSON
                    $json_path = $dir_pdf . $archivo['nombre_json'];
                    if (file_exists($json_path)) {
                        $zip->addFile($json_path, $archivo['nombre_json']);
                    }
                }
    
                // Cerrar el archivo ZIP
                $zip->close();
    
                // Forzar la descarga del archivo ZIP
                force_download($zip_path, NULL);
    
                // Eliminar el archivo ZIP después de la descarga (opcional)
                unlink($zip_path);
            } else {
                log_message('error', 'No se pudo crear el archivo ZIP');
                show_error('Hubo un error al generar el archivo ZIP.');
            }
        } */

        public function descargar_archivo($nombre_pdf, $nombre_json) {
            // Ruta de la carpeta de archivos
            $dir_pdf = FCPATH . 'public/archivos_pdf/';

            // Decodificar los nombres de los archivos
                $nombre_pdf = urldecode($nombre_pdf);
                $nombre_json = urldecode($nombre_json);
            
            // Construir las rutas completas de los archivos
            $pdf_path = $dir_pdf . $nombre_pdf;
            $json_path = $dir_pdf . $nombre_json;
            
            
            // Verificar que ambos archivos existan
            if (file_exists($pdf_path) && file_exists($json_path)) {
                // Descargar los archivos juntos
                $this->descargar_archivos($pdf_path, $json_path);
            } else {
                show_error('Uno o ambos archivos no existen.');
            }

            // echo $pdf_path;
        }

        private function descargar_archivos($pdf_path, $json_path) {
            // Abrir un archivo ZIP para incluir ambos archivos
            $zip_name = 'archivos_' . time() . '.zip';
            $zip_path = FCPATH . 'public/archivos_pdf/' . $zip_name;
            
            // Crear un archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
                // Añadir el archivo PDF
                $zip->addFile($pdf_path, basename($pdf_path));
                // Añadir el archivo JSON
                $zip->addFile($json_path, basename($json_path));
    
                // Cerrar el archivo ZIP
                $zip->close();
    
                // Forzar la descarga del archivo ZIP
                force_download($zip_path, NULL);
    
                // Eliminar el archivo ZIP después de la descarga (opcional)
                unlink($zip_path);
            } else {
                show_error('No se pudo crear el archivo ZIP.');
            }
        }

        public function descargar_archivos_rango() {
            // Obtener las fechas del formulario
            $fecha_inicio = strtotime($this->input->post('fecha_inicio'));
            $fecha_fin = strtotime($this->input->post('fecha_fin'));
    
            // Ruta de la carpeta de archivos
            $dir_pdf = FCPATH . 'public/archivos_pdf/';
            
            // Obtener todos los archivos en la carpeta
            $archivos = get_filenames($dir_pdf);
    
            // Filtrar los archivos por las fechas de creación
            $archivos_a_descargar = [];
            foreach ($archivos as $archivo) {
                // Obtener la fecha de creación del archivo (modificación del archivo)
                $fecha_archivo = filemtime($dir_pdf . $archivo);
    
                // Verificar si el archivo está dentro del rango de fechas
                if ($fecha_archivo >= $fecha_inicio && $fecha_archivo <= $fecha_fin) {
                    $archivos_a_descargar[] = $archivo;
                }
            }

            // Verificar si se encontraron archivos en el rango
            if (count($archivos_a_descargar) > 0) {
                // Crear un archivo ZIP con los archivos encontrados
                $zip_name = 'archivos_rango_' . time() . '.zip';
                $zip_path = FCPATH . 'public/archivos_pdf/' . $zip_name;
    
                // Crear el archivo ZIP
                $zip = new ZipArchive();
                if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
                    foreach ($archivos_a_descargar as $archivo) {
                        $zip->addFile($dir_pdf . $archivo, basename($archivo));
                    }
                    $zip->close();
    
                    // Forzar la descarga del archivo ZIP
                    force_download($zip_path, NULL);
    
                    // Eliminar el archivo ZIP después de la descarga (opcional)
                    unlink($zip_path);
                } else {
                    show_error('No se pudo crear el archivo ZIP.');
                }
            } else {
                show_error('No se encontraron archivos en el rango de fechas seleccionado.');
            }
        }

        public function compartir_archivo($params = null){
            $datos = $this->input->post();
            $datos["asunto"] = "Estimado Cliente, <br>
                    Por este medio, le hacemos llegar la factura correspondiente a su reciente visita en Farmacia U. Medica .<br>
                    Agradecemos su confianza en nuestros servicios y nos comprometemos a seguir brind&aacute;dole atenci&oacute;n de calidad con el profesionalismo y el cuidado que usted merece.<br>

                    <strong>Atentamente,</strong><br>  
                    Farmacia U. Medica";

           // echo json_encode($datos);

            $this->send_mail($datos);
        }

    // Archivos locales

    // Envio de archivos
        public function send_mail($datos = null){
            $mail = new PHPMailer(true);
            $timestamp = time();  // Obtén el timestamp actual
            $receptor = preg_replace('/^[A-Za-z0-9\-]+-(.*?)(_\d+)?\.pdf$/', '$1', $datos['archivoPDF']);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host       = 'hospitalorellana.com.sv'; // Servidor SMTP de tu hosting
                $mail->SMTPAuth   = true;
                $mail->Username   = 'facturacion@hospitalorellana.com.sv'; // Tu correo creado en cPanel
                $mail->Password   = ',%RtGBP*p$n2'; // Contraseña de ese correo
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usar SSL
                $mail->Port       = 465; // Puede ser 465 si usas SSL

                // Configuración del remitente y destinatario
                $mail->setFrom('facturacion@hospitalorellana.com.sv', 'Farmacia U. Medica');
                $mail->addAddress($datos["receptorCorreo"], 'Destinatario');
                $mail->addCC('facturacion@hospitalorellana.com.sv', 'Copia'); // Dirección de CC (copia)

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = $receptor;
                $mail->Body    = $datos["asunto"].'<br><br><br><img src="'.base_url().'public/img/firma_electronica.jpg?timestamp=' . $timestamp . '" alt="Logo de Hospital Orellana">'; 

                // Ruta al archivo PDF
                $pdfPath = FCPATH . 'public/archivos_pdf/'.$datos["archivoPDF"];  // Ruta absoluta al archivo PDF
                if (file_exists($pdfPath)) {
                    $mail->addAttachment($pdfPath, $datos["archivoPDF"]);
                }

                // Ruta al archivo JSON
                $jsonPath = FCPATH . 'public/archivos_pdf/'.$datos["archivojson"];  // Ruta absoluta al archivo JSON
                if (file_exists($jsonPath)) {
                    $mail->addAttachment($jsonPath, $datos["archivojson"]);
                }


                // Enviar correo
                if ($mail->send()) {
                    $this->session->set_flashdata("exito","El correo se envio correctamente");
                    redirect(base_url()."Facturacion/lista_documentos_generados");
                } else {
                    $this->session->set_flashdata("error","Error al enviar el correo");
                    redirect(base_url()."Facturacion/lista_documentos_generados");
                }
            } catch (Exception $e) {
                $this->session->set_flashdata("error","Error al enviar el correo");
                redirect(base_url()."Facturacion/lista_documentos_generados");
            }

            // echo json_encode($datos);
        }
    // Envio de archivos

    
    public function test($dte = null, $mensaje = null){
        /* $datos = $this->Facturacion_Model->obtenerDTE($dte, 5);
        $data["datos"] = $datos;
        $data["datosLocales"] = $datos->datosLocales;
        $data["respuestaHacienda"] = $datos->respuestaHacienda;
        $data["jsonDTE"] = $datos->jsonDTE;

        $testdatosLocales = unserialize(base64_decode(urldecode($datos->datosLocales)));
        $testrespuestaHacienda = unserialize(base64_decode(urldecode($datos->respuestaHacienda)));
        $testjsonDTE = unserialize(base64_decode(urldecode($datos->jsonDTE)));

        $identificacion = $testjsonDTE["dteJson"]["identificacion"];
        $emisor = $testjsonDTE["dteJson"]["emisor"];
        $receptor = $testjsonDTE["dteJson"]["receptor"];
        $cuerpoDocumento = $testjsonDTE["dteJson"]["cuerpoDocumento"];
        $resumen = $testjsonDTE["dteJson"]["resumen"];
        
        $hacienda = unserialize(base64_decode(urldecode($datos->respuestaHacienda)));
        unset($datos->respuestaHacienda);
        unset($datos->datosLocales);
        unset($datos->jsonDTE);


        $identificacion = $testjsonDTE["dteJson"]["identificacion"];

        echo json_encode($testjsonDTE["dteJson"]["resumen"]); */
        $total = 1050.50;
        $arregloNumero = explode(".", round($total, 2));
        $letras = "";
        if(isset($arregloNumero[1])){
            $letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
        }else{
            $letras = strtoupper($this->convertir($total)." 00/100 Dolares"); 
        }

        echo json_encode($letras);

    }




    public function testQR(){

        $connector = new WindowsPrintConnector("TM-T88VII");
		$printer = new Printer($connector);

        // Most simple example
        $testStr = "Testing 123";
        // Demo that alignment is the same as text
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> qrCode($testStr, Printer::QR_ECLEVEL_L, 10);
        $printer -> text("Same example, centred\n");
        $printer -> setJustification();
        $printer -> feed();
        // Demo that alignment is the same as text
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> qrCode($testStr, Printer::QR_ECLEVEL_M, 10);
        $printer -> text("Same example, centred\n");
        $printer -> setJustification();
        $printer -> feed();
        // Demo that alignment is the same as text
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> qrCode($testStr, Printer::QR_ECLEVEL_Q, 10);
        $printer -> text("Same example, centred\n");
        $printer -> setJustification();
        $printer -> feed();
        // Demo that alignment is the same as text
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> qrCode($testStr, Printer::QR_ECLEVEL_H, 10);
        $printer -> text("Same example, centred\n");
        $printer -> setJustification();
        $printer -> feed();

        // Cut & close
        $printer -> cut();
        $printer -> close();


    }

    public function title(Printer $printer, $str){
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer -> text($str);
        $printer -> selectPrintMode();
    }

    
    public function compactCharTable($printer, $start = 4, $header = false)
    {
        /* Output a compact character table for the current encoding */
        $chars = str_repeat(' ', 256);
        for ($i = 0; $i < 255; $i++) {
            $chars[$i] = ($i > 32 && $i != 127) ? chr($i) : ' ';
        }
        if ($header) {
            $printer -> setEmphasis(true);
            $printer -> textRaw("  0123456789ABCDEF0123456789ABCDEF\n");
            $printer -> setEmphasis(false);
        }
        for ($y = $start; $y < 8; $y++) {
            $printer -> setEmphasis(true);
            $printer -> textRaw(strtoupper(dechex($y * 2)) . " ");
            $printer -> setEmphasis(false);
            $printer -> textRaw(substr($chars, $y * 32, 32) . "\n");
        }
    }   
}
