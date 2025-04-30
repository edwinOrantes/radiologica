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

    // Importar la clase Curl
        use Curl\Curl;

class Online extends CI_Controller {
	/* Metodos para gestion de procesos de laboratorio */
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/El_Salvador');
        if (!$this->session->has_userdata('valido')){
            $this->session->set_flashdata("error", "Debes iniciar sesión");
            redirect(base_url());
        }
        $this->load->model("Laboratorio_Model");  // Modelo para gestionar datos del laboratorio
        $this->load->model("Usuarios_Model");  // Modelo para gestionar bitacora de laboratorio
    }

    // Subir resultados en linea
        public function subir_resultados($params = null){
            $datosOnline = [];
            $datos = unserialize(base64_decode(urldecode($params)));
            $paciente = $datos["paciente"];
            $examenes = $datos["examenes"];
            $consulta = $paciente->idConsultaLaboratorio;
            $hoja = $paciente->idHoja;
            
            if(sizeof($examenes) == 0){
                $this->session->set_flashdata("error","No hay datos que procesar.");
                redirect(base_url()."Laboratorio/detalle_consulta/$consulta/");
            }else{
                foreach ($examenes as $row) {
                    switch ($row["tipoExamen"]) {
                        case '1':
                            $tblHTML = $this->examen_inmunologia($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
                            ];
                            $datosOnline[] = $examen;
                        break;

                        case '2':
                                $tblHTML = $this->examen_bacteriologia($row["idExamen"], $row["examenes"]);
                                $examen = [
                                    "idConsulta" => $row["idConsulta"],
                                    "idExamen" => $row["idExamen"],
                                    "detalle" => $tblHTML,
                                    "fila" => $row["filaDetalle"],
                                    "str" => $row["examenes"]
                                ];
                                $datosOnline[] = $examen;
                        break;
        
                        case '3':
                            $tblHTML = $this->examen_coagulacion($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '4':
                            $tblHTML = $this->examen_tipeo_sanguineo($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '6':
                            $tblHTML = $this->quimica_sanguinea($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '7':
                            $tblHTML = $this->examen_coprologia($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '8':
                            $tblHTML = $this->examen_tiroideas_libres($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '9':
                            $tblHTML = $this->examen_tiroideas_totales($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
                    
                        case '10':
                            $tblHTML = $this->examen_varios($row["idExamen"], $row["examenes"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
                        
                        case '11':
                            $tblHTML = $this->examen_psa($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
                        case '12':
                            $tblHTML = $this->examen_hematologia($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
                        case '13':
                            $tblHTML = $this->examen_orina($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
                        case '14':
                            $tblHTML = $this->examen_hisopado($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '15':
                            $tblHTML = $this->examen_espermograma($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '16':
                            $tblHTML = $this->examen_creatinina($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '17':
                            $tblHTML = $this->examen_gases_arteriales($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '18':
                            $tblHTML = $this->examen_tolerancia_glucosa($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
        
                        case '19':
                            $tblHTML = $this->examen_toxoplasma($row["idExamen"]);
                            $examen = [
                                "idConsulta" => $row["idConsulta"],
                                "idExamen" => $row["idExamen"],
                                "detalle" => $tblHTML,
                                "fila" => $row["filaDetalle"],
                                "str" => $row["examenes"]
    
                            ];
                            $datosOnline[] = $examen;
                        break;
                        
                        default:
                            echo "Nel";
                            break;
                    }
                }
                if($paciente->tipoConsulta == 3){
                    $this->subirDatos($datosOnline, 3, $paciente->idHoja, $paciente->creadaPor, $paciente->nombrePaciente); // 3: Urologica
                }else{
                    $this->subirDatos($datosOnline, 0, $paciente->idHoja, $paciente->creadaPor, $paciente->nombrePaciente); // 3: Urologica
                }
                
            }

            // echo json_encode($datosOnline);

           
        }

        public function examen_inmunologia($id){
            $html = '';
            $cabecera = $this->Laboratorio_Model->cabeceraPDF($id, "tbl_inmunologia", "idInmunologia", 1);
            $inmunologia = $this->Laboratorio_Model->detalleExamen($id, 1);
            // Tabla detalle
                $html .= '<div class="contenedor">';
                $html .= '    <div class="medicamentos">';
                $html .= '        <div id="cabeceraExamen" style="border: 2px solid #0b88c9; padding-top: 10px; padding-bottom: 10px;">';
                $html .= '            <div class="">';
                $html .= '                <table id="tablaPaciente" cellspacing=10>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Paciente:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombrePaciente.'</td>';
                $html .= '                        <td><strong class="borderAzul">Edad:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->edadPaciente.' Años</p></td>';
                $html .= '                    </tr>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Médico:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombreMedico.'</p></td>';
                $html .= '                        <td><strong class="borderAzul">Fecha:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.substr($cabecera->fechaDetalleConsulta, 0, 10).' '.$cabecera->horaDetalleConsulta.'</p></td>';
                $html .= '                    </tr>';
                $html .= '                </table>';
                $html .= '            </div>';
                $html .= '        </div>';
                $html .= '        <div class="detalle">';
                $html .= '            <p style="font-size: 12px; color: #0b88c9; margin-top: 20px"><strong>RESULTADOS EXAMEN INMUNOLOGIA</strong></p>';
                $html .= '            <table class="table">';
                $html .= '                <thead>';
                $html .= '                    <tr style="background: #0b88c9;">';
                $html .= '                        <th> Parametro </th>';
                $html .= '                        <th> Resultado </th>';
                $html .= '                        <th> Valores de referencia </th>';
                $html .= '                    </tr>';
                $html .= '                </thead>';
                $html .= '                <tbody>';
                $html .= '                        <tr>';
                $html .= '                            <th colspan="4" style="color: #0b88c9; text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 25px" >';
                $html .= '                                <strong>Examen realizado: '.ucwords(strtolower($cabecera->examenes)).'</strong>';
                $html .= '                            </th>';
                $html .= '                        </tr>';
                    if($inmunologia->tificoO != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Tífico O</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->tificoO.'</td>
                                <td style="text-align: center; font-weight: bold">Negativo</td>
                            </tr>';
                    }
                    if($inmunologia->tificoH != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Tífico H</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->tificoH.'</td>
                                <td style="text-align: center; font-weight: bold">Negativo</td>
                            </tr>';
                    }
                    if($inmunologia->paratificoA != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Paratífico A</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->paratificoA.'</td>
                                <td style="text-align: center; font-weight: bold">Negativo</td>
                            </tr>';
                    }
                    if($inmunologia->paratificoB != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Paratífico B</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->paratificoB.'</td>
                                <td style="text-align: center; font-weight: bold">Negativo</td>
                            </tr>';
                    }
                    if($inmunologia->brucellaAbortus != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Brucella Abortus</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->brucellaAbortus.'</td>
                                <td style="text-align: center; font-weight: bold">Negativo</td>
                            </tr>';
                    }
                    if($inmunologia->proteusOx != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Proteus OX-19</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->proteusOx.'</td>
                                <td style="text-align: center; font-weight: bold">Negativo</td>
                            </tr>';
                    }
                    if($inmunologia->proteinaC != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Proteína "C" reactiva</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->proteinaC.'</td>
                                <td style="text-align: center;"><p class="borderAzul">VN: Hasta 6mg/L</p></td>
                            </tr>';
                    }
                    if($inmunologia->reumatoideo != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Factor Reumatoideo</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->reumatoideo.'</td>
                                <td style="text-align: center;"><p class="borderAzul">Valor normal: < 8UI/mL</p></td>
                            </tr>';
                    }
                    if($inmunologia->antiestreptolisina != ""){
                        $html .= '<tr>
                                <td><strong class="borderAzul">Antiestreptolisina "O"</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$inmunologia->antiestreptolisina.'</td>
                                <td style="text-align: center;"><p class="borderAzul">Valor normal: Hasta 200 UI/mL</p></td>
                            </tr>';
                    }

                $html .= '                </tbody>';
                $html .= '            </table>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
            // Tabla detalle

            return urlencode(base64_encode(serialize($html)));
        }

        public function quimica_sanguinea($id){
            $html = "";
            $cabecera = $this->Laboratorio_Model->cabeceraPDF($id, "tbl_quimica_sanguinea", "idQuimicaSanguinea ", 6);
            $sanguinea = $this->Laboratorio_Model->detalleExamen($id, 6);
            // Inicio HTML
                $html .= '<div class="contenedor">';
                $html .= '    <div class="medicamentos">';
                $html .= '        <div style="border: 2px solid #0b88c9; padding-top: 10px; padding-bottom: 15px;">';
                $html .= '            <div class="">';
                $html .= '                <table id="tablaPaciente" cellspacing=10>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Paciente:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombrePaciente.'</p></td>';
                $html .= '                        <td><strong class="borderAzul">Edad:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->edadPaciente.' Años</p></td>';
                $html .= '                    </tr>';
                $html .= '                    ';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Médico:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombreMedico.'</p></td>';
                $html .= '                        <td><strong class="borderAzul">Fecha:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta.'</p></td>';
                $html .= '                    </tr>';
                $html .= '                </table>';
                $html .= '            </div>';
                $html .= '        </div>';
                $html .= '        ';
                $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS EXAMEN QUIMICA SANGUINEA</p>';
                $html .= '        <div class="detalle">';
                $html .= '            <table class="table">';
                $html .= '                <thead>';
                $html .= '                    <tr style="background: #0b88c9;">';
                $html .= '                        <th> Parametro </th>';
                $html .= '                        <th> Resultado </th>';
                $html .= '                        <th> Unidades </th>';
                $html .= '                        <th> Valores de referencia </th>';
                $html .= '                    </tr>';
                $html .= '                </thead>';
                $html .= '                <tbody>';
                if($sanguinea->glucosaQS != ""){
                    $html .= '<tr>
                                <td><strong>Glucosa</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->glucosaQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">60-110mg/dl</td>
                            </tr>';
                }
                if($sanguinea->posprandialQS != ""){
                    $html .= '<tr>
                                <td><strong>Glucosa postprandial</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->posprandialQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Menor de 140 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->colesterolQS != ""){
                    $html .= '<tr>
                                <td><strong>Colesterol</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->colesterolQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Menor de 200 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->colesterolHDLQS != ""){
                    $html .= '<tr>
                                <td><strong>Colesterol HDL</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->colesterolHDLQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Mayor de 35 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->colesterolLDLQS != ""){
                    $html .= '<tr>
                                <td><strong>Colesterol LDL</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->colesterolLDLQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Menor de 130 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->trigliceridosQS != ""){
                    $html .= '<tr>
                                <td><strong>Triglicéridos</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->trigliceridosQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Menor de 150 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->acidoUricoQS != ""){
                    $html .= '<tr>
                                <td><strong>Ácido úrico</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->acidoUricoQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">2.4-7.0 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->ureaQS != ""){
                    $html .= '<tr>
                                <td><strong>Urea</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->ureaQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">15-45 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->nitrogenoQS != ""){
                    $html .= '<tr>
                                <td><strong>Nitrógeno Ureico</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->nitrogenoQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">5-25 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->creatininaQS != ""){
                    $html .= '<tr>
                                <td><strong>Creatinina</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->creatininaQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">0.5-1.4 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->amilasaQS != ""){
                    $html .= '<tr>
                                <td><strong>Amilasa</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->amilasaQS.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">Menor de 90 U/L</td>
                            </tr>';
                }
                if($sanguinea->lipasaQS != ""){
                    $html .= '<tr>
                                <td><strong>Lipasa</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->lipasaQS.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">Menor de 38 U/L</td>
                            </tr>';
                }
                if($sanguinea->fosfatasaQS != ""){
                    $html .= '<tr>
                                <td><strong>Fosfatasa alcalina</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->fosfatasaQS.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">Hasta 275 U/L</td>
                            </tr>';
                }
                if($sanguinea->tgpQS != ""){
                    $html .= '<tr>
                                <td><strong>TGP</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->tgpQS.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">1-40 U/L</td>
                            </tr>';
                }
                if($sanguinea->tgoQS != ""){
                    $html .= '<tr>
                                <td><strong>TGO</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->tgoQS.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">1-38 U/L</td>
                            </tr>';
                }
                if($sanguinea->hba1cQS != ""){
                    $html .= '<tr>
                                <td><strong>Hemoglobina glicosilada</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->hba1cQS.'</td>
                                <td style="text-align: center; font-weight: bold">%</td>
                                <td style="text-align: center; font-weight: bold">4.5-6.5%</td>
                            </tr>';
                }
                if($sanguinea->proteinaTotalQS != ""){
                    $html .= '<tr>
                                <td><strong>Proteína total</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->proteinaTotalQS.'</td>
                                <td style="text-align: center; font-weight: bold">g/dl</td>
                                <td style="text-align: center; font-weight: bold">6.6-8.3 d/dl</td>
                            </tr>';
                }
                if($sanguinea->albuminaQS != ""){
                    $html .= '<tr>
                                <td><strong>Albúmina</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->albuminaQS.'</td>
                                <td style="text-align: center; font-weight: bold">g/dl</td>
                                <td style="text-align: center; font-weight: bold">3.5-5.0 g/dl</td>
                            </tr>';
                }
                if($sanguinea->globulinaQS != ""){
                    $html .= '<tr>
                                <td><strong>Globulina</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->globulinaQS.'</td>
                                <td style="text-align: center; font-weight: bold">g/dl</td>
                                <td style="text-align: center; font-weight: bold">2-3.5 g/dl</td>
                            </tr>';
                }
                if($sanguinea->relacionAGQS != ""){
                    $html .= '<tr>
                                <td><strong>Relación A/G</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->relacionAGQS.'</td>
                                <td style="text-align: center; font-weight: bold"></td>
                                <td style="text-align: center; font-weight: bold">1.2-2.2</td>
                            </tr>';
                }
                if($sanguinea->bilirrubinaTQS != ""){
                    $html .= '<tr>
                                <td><strong>Bilirrubina total</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->bilirrubinaTQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Hasta 1.1 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->bilirrubinaDQS != ""){
                    $html .= '<tr>
                                <td><strong>Bilirrubina directa</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->bilirrubinaDQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">Hasta 0.25 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->bilirrubinaIQS != ""){
                    $html .= '<tr>
                                <td><strong>Bilirrubina indirecta</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->bilirrubinaIQS.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold"></td>
                            </tr>';
                }
                if($sanguinea->sodioQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Sodio</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->sodioQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">mmol/L</td>
                                <td style="text-align: center; font-weight: bold">136-148 mmol/L</td>
                            </tr>';
                }
                if($sanguinea->potasioQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Potasio</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->potasioQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">mmol/L</td>
                                <td style="text-align: center; font-weight: bold">3.5-5.3 mmol/L</td>
                            </tr>';
                }
                if($sanguinea->cloroQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Cloro</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->cloroQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">mmol/L</td>
                                <td style="text-align: center; font-weight: bold">98-107 mmol/L</td>
                            </tr>';
                }
                if($sanguinea->magnesioQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Magnesio</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->magnesioQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">1.6-2.5 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->calcioQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Calcio</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->calcioQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">8.5-10.5 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->fosforoQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Fosforo</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->fosforoQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">mg/dl</td>
                                <td style="text-align: center; font-weight: bold">2.5-5.0 mg/dl</td>
                            </tr>';
                }
                if($sanguinea->cpkTQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>CPK Total</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->cpkTQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">0-195 U/L</td>
                            </tr>';
                }
                if($sanguinea->cpkMbQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>CPK MB</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->cpkMbQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">Menor a 24 U/L</td>
                            </tr>';
                }
                if($sanguinea->ldhQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>LDH</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->ldhQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">U/L</td>
                                <td style="text-align: center; font-weight: bold">230-460 U/L</td>
                            </tr>';
                }
                if($sanguinea->troponinaQuimicaClinica != ""){
                    $html .= '<tr>
                                <td><strong>Troponina I</strong></td>
                                <td style="text-align: center; font-weight: bold">'.$sanguinea->troponinaQuimicaClinica.'</td>
                                <td style="text-align: center; font-weight: bold">ng/dl</td>
                                <td style="text-align: center; font-weight: bold">VN: menor a 0.30 ng/dl</td>
                            </tr>';
                }
                if($sanguinea->notaQS != ""){
                    $html .= '<tr>
                                <td><strong>Observaciones</strong></td>
                                <td style="text-align: center; font-weight: bold" colspan=3>'.$sanguinea->notaQS.'</td>
                            </tr>';
                }
                $html .= '                </tbody>';
                $html .= '            </table>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        private function cabecera_online($consulta = null){
            $html = '';
            $cabecera = $this->Laboratorio_Model->cabeceraOnline($consulta);
            // Cabecera
                $html .= '        <div style="border: 2px solid #0b88c9; padding-top: 10px; padding-bottom: 15px;" id="contenedorCabecera">';
                $html .= '                <table id="tablaPaciente" cellspacing=10>';
                $html .= '                    <tr>';
                $html .= '                        <td colspan="2"><p style="text-align: center, font-weight: bold" class="borderAzul codigoConsulta">'.$cabecera->codigoConsulta.'</p></td>';
                $html .= '                    </tr>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Paciente:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombrePaciente.'</p></td>';
                $html .= '                    </tr>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Edad:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->edadPaciente.' Años</p></td>';
                $html .= '                    </tr>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Médico:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombreMedico.'</p></td>';
                $html .= '                    </tr>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Fecha:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->fecha." ".date("g:i A", strtotime($cabecera->hora)).'</p></td>';
                $html .= '                    </tr>';
                $html .= '                </table>';
                $html .= '        </div>';
            // Cabecera

            // return $html;
            return urlencode(base64_encode(serialize($html)));
        }

        private function html_cabecera($id = null, $tabla = null, $examen = null, $pivote = null){
            $html = '';
            $cabecera = $this->Laboratorio_Model->cabeceraPDF($id, $tabla, $examen, $pivote);
            // Cabecera
                $html .= '<div class="contenedor">';
                $html .= '    <div class="medicamentos">';
                $html .= '        <div style="border: 2px solid #0b88c9; padding-top: 10px; padding-bottom: 15px;">';
                $html .= '            <div class="">';
                $html .= '                <table id="tablaPaciente" cellspacing=10>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Paciente:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombrePaciente.'</p></td>';
                $html .= '                        <td><strong class="borderAzul">Edad:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->edadPaciente.' Años</p></td>';
                $html .= '                    </tr>';
                $html .= '                    <tr>';
                $html .= '                        <td><strong class="borderAzul">Médico:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.$cabecera->nombreMedico.'</p></td>';
                $html .= '                        <td><strong class="borderAzul">Fecha:</strong></td>';
                $html .= '                        <td><p class="borderAzul">'.substr($cabecera->fechaDetalleConsulta, 0, 10)." ".$cabecera->horaDetalleConsulta.'</p></td>';
                $html .= '                    </tr>';
                $html .= '                </table>';
                $html .= '            </div>';
                $html .= '        </div>';
            // Cabecera

            return $html;
        }

        public function examen_bacteriologia($id = null, $examen = null){
            $html = $this->html_cabecera($id, "tbl_bacteriologia", "idBacteriologia", 2);
            $bacteriologia = $this->Laboratorio_Model->detalleExamen($id, 2);
            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS EXAMEN BACTERIOLOGIA</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '                  <thead>';
            $html .= '                      <tr style="background: #0b88c9;">';
            $html .= '                          <th> Parametro </th>';
            $html .= '                          <th> Resultado </th>';
            $html .= '                      </tr>';
            $html .= '                  </thead>';
            $html .= '                <tbody>';

            $html .= '<tr>';
            $html .= '    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px" colspan="2"><strong class="borderAzul">Examen realizado: </strong>'.$examen.'</td>';
            $html .= '</tr>';

            if($bacteriologia->resultadoDirecto != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Resultado directo</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->resultadoDirecto.'</td>
                    </tr>';
            }
            if($bacteriologia->procedenciaCultivo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Procedencia de la muestra</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->procedenciaCultivo.'</td>
                    </tr>';
            }
            if($bacteriologia->resultadoCultivo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Resultado cultivo</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->resultadoCultivo.'</td>
                    </tr>';
            }
            if($bacteriologia->cefixime != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Cefixime</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefixime.'</td>
                    </tr>';
            }
            if($bacteriologia->amikacina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Amikacina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->amikacina.'</td>
                    </tr>';
            }
            if($bacteriologia->levofloxacina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Levofloxacina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->levofloxacina.'</td>
                    </tr>';
            }
            if($bacteriologia->ceftriaxona != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Ceftriaxona</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ceftriaxona.'</td>
                    </tr>';
            }
            if($bacteriologia->azitromicina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Azitromicina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->azitromicina.'</td>
                    </tr>';
            }
            if($bacteriologia->imipenem != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Imipenem</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->imipenem.'</td>
                    </tr>';
            }
            if($bacteriologia->meropenem != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Meropenem</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->meropenem.'</td>
                    </tr>';
            }
            if($bacteriologia->fosfocil != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Fosfocil</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->fosfocil.'</td>
                    </tr>';
            }
            if($bacteriologia->ciprofloxacina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Ciprofloxacina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ciprofloxacina.'</td>
                    </tr>';
            }
            if($bacteriologia->penicilina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Penicilina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->penicilina.'</td>
                    </tr>';
            }
            if($bacteriologia->vancomicina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Vancomicina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->vancomicina.'</td>
                    </tr>';
            }
            if($bacteriologia->acidoNalidixico != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Acido Nalidixico</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->acidoNalidixico.'</td>
                    </tr>';
            }
            if($bacteriologia->gentamicina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Gentamicina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->gentamicina.'</td>
                    </tr>';
            }
            if($bacteriologia->nitrofurantoina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Nitrofurantoína</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->nitrofurantoina.'</td>
                    </tr>';
            }
            if($bacteriologia->ceftazimide != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Ceftazidime</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ceftazimide.'</td>
                    </tr>';
            }
            if($bacteriologia->cefotaxime != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Cefotaxime</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefotaxime.'</td>
                    </tr>';
            }

            if($bacteriologia->clindamicina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Clindamicina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->clindamicina.'</td>
                    </tr>';
            }
            if($bacteriologia->trimetropimSulfa != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Trimetropim Sulfa</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->trimetropimSulfa.'</td>
                    </tr>';
            }
            if($bacteriologia->ampicilina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Ampicilina/Sulbactam</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->ampicilina.'</td>
                    </tr>';
            }
            if($bacteriologia->piperacilina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Piperacilina/Tazobactam</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->piperacilina.'</td>
                    </tr>';
            }
            if($bacteriologia->amoxicilina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Amoxicilina Acido Clavulánico</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->amoxicilina.'</td>
                    </tr>';
            }
            if($bacteriologia->claritromicina != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Claritromicina</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->claritromicina.'</td>
                    </tr>';
            }
            if($bacteriologia->cefuroxime != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Cefuroxime</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefuroxime.'</td>
                    </tr>';
            }

            // Nuevos antibioticos
                if($bacteriologia->tetraciclina != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Tetraciclina</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->tetraciclina.'</td>
                        </tr>';
                }
                if($bacteriologia->eritromicina != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Eritromicina</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->eritromicina.'</td>
                        </tr>';
                }
                if($bacteriologia->doxiciclina != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Doxiciclina</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->doxiciclina.'</td>
                        </tr>';
                }
                if($bacteriologia->oxacilina != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Oxacilina</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->oxacilina.'</td>
                        </tr>';
                }
                if($bacteriologia->tobramicina != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Tobramicina</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->tobramicina.'</td>
                        </tr>';
                }
                if($bacteriologia->cefepime != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Cefepime</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefepime.'</td>
                        </tr>';
                }
                if($bacteriologia->norfloxacina != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Norfloxacina</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->norfloxacina.'</td>
                        </tr>';
                }
                if($bacteriologia->cefazolin != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Cefazolin</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->cefazolin.'</td>
                        </tr>';
                }
                if($bacteriologia->aztreonam != ""){
                    $html .= '<tr>
                            <td style="padding-left: 50px"><strong class="">Aztreonam</strong></td>
                            <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->aztreonam.'</td>
                        </tr>';
                }
            // Nuevos antibioticos

            if($bacteriologia->observacionesCultivo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Observaciones</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$bacteriologia->observacionesCultivo.'</td>
                    </tr>';
            }
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            // return $html;
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_coagulacion($id){
            
            $html = $this->html_cabecera($id, "tbl_coagulacion", "idCoagulacion", 3);
            $coagulacion = $this->Laboratorio_Model->detalleExamen($id, 3);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS PRUEBAS DE COAGULACION</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Unidades </th>';
            $html .= '                      <th> Valores de referencia </th>';
            $html .= '                  </tr>';
            $html .= '              </thead>';
            $html .= '                <tbody>';

            
            if($coagulacion->tiempoProtombina != ""){
                $html .= '<tr>
                        <td><strong>Tiempo de Protombina</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoProtombina.'</td>
                        <td style="text-align: center; font-weight: bold">Segundos</td>
                        <td style="text-align: center; font-weight: bold">10-14</td>
                    </tr>';
            }
            if($coagulacion->tiempoTromboplastina != ""){
                $html .= '<tr>
                        <td><strong>Tiempo Parcial de Tromboplastina</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoTromboplastina.'</td>
                        <td style="text-align: center; font-weight: bold">Segundos</td>
                        <td style="text-align: center; font-weight: bold">20-33</td>
                    </tr>';
            }
            if($coagulacion->fibrinogeno != ""){
                $html .= '<tr>
                        <td><strong>Fibrinógeno</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$coagulacion->fibrinogeno.'</td>
                        <td style="text-align: center; font-weight: bold">mg/dl</td>
                        <td style="text-align: center; font-weight: bold">200-400 mg/dl</td>
                    </tr>';
            }
            if($coagulacion->inr != ""){
                $html .= '<tr>
                        <td><strong>INR</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$coagulacion->inr.'</td>
                        <td style="text-align: center; font-weight: bold">-</td>
                        <td style="text-align: center; font-weight: bold">-</td>
                    </tr>';
            }
            if($coagulacion->tiempoSangramiento != ""){
                $html .= '<tr>
                        <td><strong>Tiempo de sangramiento</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoSangramiento.'</td>
                        <td style="text-align: center; font-weight: bold">Minutos</td>
                        <td style="text-align: center; font-weight: bold">1-4</td>
                    </tr>';
            }
            if($coagulacion->tiempoCoagulacion != ""){
                $html .= '<tr>
                        <td><strong>Tiempo de coagulación</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$coagulacion->tiempoCoagulacion.'</td>
                        <td style="text-align: center; font-weight: bold">Minutos</td>
                        <td style="text-align: center; font-weight: bold">4-9</td>
                    </tr>';
            }
            if($coagulacion->observacion != ""){
                $html .= '<tr>
                        <td><strong>Observación</strong></td>
                        <td style="text-align: center; font-weight: bold" colspan=3>'.$coagulacion->observacion.'</td>
                    </tr>';
            }
            
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_tipeo_sanguineo($id){

            $html = $this->html_cabecera($id, "tbl_sanguineo", "idSanguineo", 4);
            $sanguineo = $this->Laboratorio_Model->detalleExamen($id, 4);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS TIPEO SANGUINEO</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                  </tr>';
            $html .= '              </thead>';
            $html .= '                <tbody>';

            if($sanguineo->muestraSanguineo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Muestra</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$sanguineo->muestraSanguineo.'</td>
                    </tr>';
            }
            if($sanguineo->grupoSanguineo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Grupo sanguíneo</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$sanguineo->grupoSanguineo.'</td>
                    </tr>';
            }
            if($sanguineo->factorSanguineo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Factor RH</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$sanguineo->factorSanguineo.'</td>
                    </tr>';
            }
            if($sanguineo->duSanguineo != ""){
                $html .= '<tr>
                        <td style="padding-left: 50px"><strong class="">Du</strong></td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px">'.$sanguineo->duSanguineo.'</td>
                    </tr>';
            }
            
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_coprologia($id){

            $html = $this->html_cabecera($id, "tbl_cropologia", "idCropologia", 7);
            $cropologia = $this->Laboratorio_Model->detalleExamen($id, 7);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS EXAMEN COPROLOGIA</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                  </tr>';
            $html .= '              </thead>';
            $html .= '                <tbody>';

            
            if($cropologia->colorCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Color</strong></td>
                        <td style="text-align: center;">'.$cropologia->colorCropologia.'</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->consistenciaCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Consistencia</strong></td>
                        <td style="text-align: center;">'.$cropologia->consistenciaCropologia.'</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->mucusCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Mucus</strong></td>
                        <td style="text-align: center;">'.$cropologia->mucusCropologia.'</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->hematiesCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Hematíes</strong></td>
                        <td style="text-align: center;">'.$cropologia->hematiesCropologia.' x campo</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->leucocitosCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Leucocitos</strong></td>
                        <td style="text-align: center;">'.$cropologia->leucocitosCropologia.' x campo</td>
                        <td></td>
                    </tr>';
            }

            $html .= '<tr>
                    <th colspan="3" style="color: #0b88c9; text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 25px" ><strong>METAZOARIOS</strong></th>
                </tr>';
            if($cropologia->ascarisCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Ascaris lumbricoides</strong></td>
                        <td style="text-align: center;">'.$cropologia->ascarisCropologia.'</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->hymenolepisCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Hymenolepis</strong></td>
                        <td style="text-align: center;">'.$cropologia->hymenolepisCropologia.'</td>
                        <td></td>

                    </tr>';
            }
            if($cropologia->uncinariasCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Uncinarias</strong></td>
                        <td style="text-align: center;">'.$cropologia->uncinariasCropologia.'</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->tricocefalosCropologia != ""){
                $html .= '<tr>
                        <td><strong class="">Trichuris trichiura</strong></td>
                        <td style="text-align: center;">'.$cropologia->tricocefalosCropologia.'</td>
                        <td></td>
                    </tr>';
            }
            if($cropologia->larvaStrongyloides != ""){
                $html .= '<tr>
                        <td><strong class="">Larva strongyloides stercoralis </strong></td>
                        <td style="text-align: center;">'.$cropologia->larvaStrongyloides.'</td>
                        <td></td>
                    </tr>';
            }

            $html .= '<tr>
                    <th colspan="3" style="color: #0b88c9; color: #0b88c9; text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 25px"><strong>PROTOZOARIOS</strong></th>
                </tr>';
            $html .= '<tr>
                    <td></td>
                    <td style="text-align: center; color: #0b88c9;"><strong>Quistes</strong></td>
                    <td style="text-align: center; color: #0b88c9;"><strong>Trofozoitos</strong></td>
                    <td></td>
                    <td></td>
                </tr>';
            if($cropologia->histolyticaQuistes != "" || $cropologia->histolyticaTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Entamoeba histolytica</strong></td>
                        <td style="text-align: center;">'.$cropologia->histolyticaQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->histolyticaTrofozoitos.'</td>
                    </tr>';
            }
            if($cropologia->coliQuistes != "" || $cropologia->coliTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Entamoeba coli</strong></td>
                        <td style="text-align: center;">'.$cropologia->coliQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->coliTrofozoitos.'</td>
                    </tr>';
            }
            if($cropologia->giardiaQuistes != "" || $cropologia->giardiaTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Giardia lamblia</strong></td>
                        <td style="text-align: center;">'.$cropologia->giardiaQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->giardiaTrofozoitos.'</td>
                    </tr>';
            }
            if($cropologia->blastocystisQuistes != "" || $cropologia->blastocystisTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Blastocystis hominis</strong></td>
                        <td style="text-align: center;">'.$cropologia->blastocystisQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->blastocystisTrofozoitos.'</td>
                    </tr>';
            }
            if($cropologia->tricomonasQuistes != "" || $cropologia->tricomonasTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Tricomonas hominis</strong></td>
                        <td style="text-align: center;">'.$cropologia->tricomonasQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->tricomonasTrofozoitos.'</td>
                    </tr>';
            }
            if($cropologia->mesnilliQuistes != "" || $cropologia->mesnilliTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Chilomastix mesnilli</strong></td>
                        <td style="text-align: center;">'.$cropologia->mesnilliQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->mesnilliTrofozoitos.'</td>
                    </tr>';
            }
            if($cropologia->nanaQuistes != "" || $cropologia->nanaTrofozoitos != ""){
                $html .= '<tr>
                        <td><strong class="">Endolimax nana</strong></td>
                        <td style="text-align: center;">'.$cropologia->nanaQuistes.'</td>
                        <td style="text-align: center;">'.$cropologia->nanaTrofozoitos.'</td>
                    </tr>';
            }

            if($cropologia->restosMacroscopicos != "" && $cropologia->restosMicroscopicos != ""){
                $html .= '<tr>
                    <th colspan="3" style="color: #0b88c9; color: #0b88c9; color: #0b88c9; text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 25px"><strong>RESTOS ALIMENTICIOS</strong></th>
                </tr>';
                if($cropologia->restosMacroscopicos != ""){
                    $html .= '<tr>
                            <td><strong class="borderAzul">Restos Alimenticios Macroscópicos</strong></td>
                            <td colspan="2" style="text-align: center;">'.$cropologia->restosMacroscopicos.'</td>
                        </tr>';
                }
                if($cropologia->restosMicroscopicos != ""){
                    $html .= '<tr>
                            <td><strong class="borderAzul">Restos Alimenticios Microscópicos</strong></td>
                            <td colspan="2" style="text-align: center;">'.$cropologia->restosMicroscopicos.'</td>
                        </tr>';
                }
            }
            
            $html .= '                </tbody>';
            $html .= '            </table>';


            $html .= ' <table class="table" style="margin-top: 10px;">';
            $html .= '     <tbody>';
                if($cropologia->observacionesCropologia != ""){
                    $html .= '<tr>
                                <td><strong class="">Observaciones</strong></td>
                                <td colspan=3>'.$cropologia->observacionesCropologia.'</td>
                            </tr>';
                }
            $html .= '     </tbody>';
            $html .= ' </table>';

            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_tiroideas_libres($id){

            $html = $this->html_cabecera($id, "tbl_tiroideas_libres", "idTiroideaLibre", 8);
            $tiroideaLibre = $this->Laboratorio_Model->detalleExamen($id, 8);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS EXAMEN TIROIDEAS LIBRES</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Unidades </th>';
            $html .= '                      <th> Valores de referencia </th>';
            $html .= '                  </tr>';
            $html .= '              </thead>';
            $html .= '                <tbody>';

            if($tiroideaLibre->muestraTiroideaLibre != ""){
                $html .= '<tr>
                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px"  colspan=4 ><strong>Muestra: </strong>'.$tiroideaLibre->muestraTiroideaLibre.'</td>
                    </tr>';
            }
            if($tiroideaLibre->t3TiroideaLibre != ""){
                $html .= '<tr>
                        <td><strong>T3 Libres</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaLibre->t3TiroideaLibre.'</td>
                        <td style="text-align: center; font-weight: bold">pg/ml</td>
                        <td style="text-align: center; font-weight: bold">1.4-4.2 pg/ml</td>
                    </tr>';
            }
            if($tiroideaLibre->t4TiroideaLibre != ""){
                $html .= '<tr>
                        <td><strong>T4 Libres</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaLibre->t4TiroideaLibre.'</td>
                        <td style="text-align: center; font-weight: bold">ng/dl</td>
                        <td style="text-align: center; font-weight: bold">0.80-2.0 ng/dl</td>
                    </tr>';
            }
            if($tiroideaLibre->tshTiroideaLibre != ""){
                $html .= '<tr>
                        <td><strong>TSH</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaLibre->tshTiroideaLibre.'</td>
                        <td style="text-align: center; font-weight: bold">uUI/ml</td>
                        <td style="text-align: center; font-weight: bold">0.3-3.0 uUI/ml</td>
                    </tr>';
            }
            if($tiroideaLibre->tshTiroideaLibreU != ""){
                $html .= '<tr>
                        <td><strong>TSH Ultrasensible</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaLibre->tshTiroideaLibreU.'</td>
                        <td style="text-align: center; font-weight: bold">uUI/ml</td>
                        <td style="text-align: center; font-weight: bold">0.03-3.0 uUI/ml</td>
                    </tr>';
            }
            if($tiroideaLibre->observacionTiroideaLibre != ""){
                $html .= '<tr>
                        <td><strong>Observaciones</strong></td>
                        <td style="text-align: center; font-weight: bold" colspan=3>'.$tiroideaLibre->observacionTiroideaLibre.'</td>
                    </tr>';
            }

        
            
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_tiroideas_totales($id){

            $html = $this->html_cabecera($id, "tbl_tiroideas_totales", "idTiroideaTotal", 9);
            $tiroideaTotal = $this->Laboratorio_Model->detalleExamen($id, 9);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS TIROIDEAS TOTALES</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Unidades </th>';
            $html .= '                      <th> Valores de referencia </th>';
            $html .= '                  </tr>';
            $html .= '              </thead>';
            $html .= '                <tbody>';

            if($tiroideaTotal->muestraTiroideaTotal != ""){
                $html .= '<tr>
                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px"  colspan=4 ><strong>Muestra: </strong>'.$tiroideaTotal->muestraTiroideaTotal.'</td>
                    </tr>';
            }
            if($tiroideaTotal->t3TiroideaTotal != ""){
                $html .= '<tr>
                        <td><strong class="">T3 Total</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->t3TiroideaTotal.'</td>
                        <td style="text-align: center; font-weight: bold">ng/ml</td>
                        <td style="text-align: center; font-weight: bold">0.5-5.0 ng/ml</td>
                    </tr>';
            }
            if($tiroideaTotal->t4TiroideaTotal != ""){
                $html .= '<tr>
                        <td><strong class="">T4 Total</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->t4TiroideaTotal.'</td>
                        <td style="text-align: center; font-weight: bold">nmol/l</td>
                        <td style="text-align: center; font-weight: bold">60.0-120.0 nmol/l</td>
                    </tr>';
            }
            if($tiroideaTotal->tshTiroideaTotal != ""){
                $html .= '<tr>
                        <td><strong class="">TSH Total</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$tiroideaTotal->tshTiroideaTotal.'</td>
                        <td style="text-align: center; font-weight: bold">uUI/ml</td>
                        <td style="text-align: center; font-weight: bold">0.3-5.6 uUI/ml</td>
                    </tr>';
            }
            if($tiroideaTotal->observacionTiroideaTotal != ""){
                $html .= '<tr>
                        <td><strong class="">Observaciones</strong></td>
                        <td style="text-align: center; font-weight: bold" colspan=3>'.$tiroideaTotal->observacionTiroideaTotal.'</td>
                    </tr>';
            }
        
            
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_varios($id =null, $examen = null ){

            $html = $this->html_cabecera($id, "tbl_varios", "idVarios", 10);
            $varios = $this->Laboratorio_Model->detalleExamen($id, 10);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS EXAMEN VARIOS</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            if($varios->valorNormalVarios != ""){
                $html .=               '     <th> Valores de referencia </th>';
            }
            $html .= '                      </tr>';
            $html .= '                  </thead>';
            $html .= '                  <tbody>';

            $html .= '<tr>';
            $html .= '    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px" colspan="3"><strong class="borderAzul">Examen realizado: </strong>'.$examen.'</td>';
            $html .= '</tr>';

            if($varios->muestraVarios != ""){
                $html .= '<tr>
                        <td style="text-align: center;"><strong class="borderAzul">Muestra</strong></td>
                        <td style="text-align: center;">'.$varios->muestraVarios.'</td>
                        <td style="text-align: center;"></td>
                    </tr>';
            }
            if($varios->resultadoVarios != ""){
                if($varios->valorNormalVarios != ""){
                    $html .= '<tr>
                            <td style="text-align: center;"><strong class="borderAzul">Resultado</strong></td>
                            <td style="text-align: center;">'.$varios->resultadoVarios.'</td>
                            <td style="text-align: center;">'.$varios->valorNormalVarios.'</td>
                        </tr>';

                }else{
                    $html .= '<tr>
                    <td style="text-align: center;"><strong class="borderAzul">Resultado</strong></td>
                    <td style="text-align: center;">'.$varios->resultadoVarios.'</td>
                </tr>';
                    
                }
            }
            if($varios->observacionesVarios != ""){
                $html .= '<tr>
                        <td style="text-align: center;"><strong class="borderAzul">Observaciones</strong></td>
                        <td style="text-align: center;">'.$varios->observacionesVarios.'</td>
                        <td style="text-align: center;"></td>
                    </tr>';
            }        
            
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_psa($id =null){

            $html = $this->html_cabecera($id, "tbl_antigeno_prostatico", "idAntigenoProstatico", 11);
            $psa = $this->Laboratorio_Model->detalleExamen($id, 11);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS ANTIGENO PROSTATICO ESPECIFICO TOTAL</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                      <tr style="background: #0b88c9;">';
            $html .= '                          <th> Parametro </th>';
            $html .= '                          <th> Resultado </th>';
            $html .= '                          <th> Unidades </th>';
            $html .= '                          <th> Valores de referencia </th>';
            $html .= '                      </tr>';
            $html .= '                  </thead>';
            $html .= '                  <tbody>';

            if($psa->muestraAntigenoProstatico != ""){
                $html .= '<tr>
                        <td><strong class="">Muestra</strong></td>
                        <td style="text-align: center;">'.$psa->muestraAntigenoProstatico.'</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                    </tr>';
            }

            if($psa->resultadoAntigenoProstatico != ""){
                $html .= '<tr>
                        <td><strong class="">Resultado</strong></td>
                        <td style="text-align: center;">'.$psa->resultadoAntigenoProstatico.'</td>
                        <td style="text-align: center;">ng/ml</td>
                        <td style="text-align: center;">Menor de 4.0 ng/ml</td>
                    </tr>';
            }

            if($psa->observacionAntigenoProstatico != ""){
                $html .= '<tr>
                        <td><strong class="">Observaciones</strong></td>
                        <td style="text-align: center;">'.$psa->observacionAntigenoProstatico.'</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                    </tr>';
            }        
            
            
            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            // return $html;
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_hematologia($id =null){

            $html = $this->html_cabecera($id, "tbl_hematologia", "idHematologia", 12);
            $cabecera = $this->Laboratorio_Model->cabeceraPDF($id, "tbl_hematologia", "idHematologia", 12);
            $hematologia = $this->Laboratorio_Model->detalleExamen($id, 12);


            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADO EXAMEN HEMATOLOGIA</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                      <tr style="background: #0b88c9;">';
            $html .= '                          <th> Parametro </th>';
            $html .= '                          <th> Resultado </th>';
            $html .= '                          <th> Valores de referencia </th>';
            $html .= '                      </tr>';
            $html .= '                  </thead>';
            $html .= '                  <tbody>';

            
            // Valores de referencia segun edad
                $ns = "";
                $nb = "";
                $linfocitos = "";
                $eosinofilos = "";
                $basofilo = "";
                $monocitos = "";
                if($cabecera->edadPaciente >= 1 && $cabecera->edadPaciente <= 8){
                    $ns = "20-45";
                    $nb = "0-4";
                    $linfocitos = "40-60";
                    $eosinofilos = "1-5";
                    $basofilo = "0-1";
                    $monocitos = "2-8";
                }else{
                    if($cabecera->edadPaciente > 8){
                        $ns = "50-70";
                        $nb = "0-5";
                        $linfocitos = "20-40";
                        $eosinofilos = "0-5";
                        $basofilo = "0-1";
                        $monocitos = "2-8";
                    }
                }
            // Fin valores de referencia segun edad

            if($hematologia->eritrocitosHematologia != ""){
                $html .= '<tr>
                    <td><strong class="">Eritrocitos</strong></td>
                    <td style="text-align: center; font-weight: bold">'.$hematologia->eritrocitosHematologia.'</td>
                    <td style="text-align: center; font-weight: bold">4-6 millones</td>
                </tr>';
                
            }
            if($hematologia->hematocritoHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Hematócrito</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->hematocritoHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">37-45%</td>
                    </tr>';
            }
            if($hematologia->hemoglobinaHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Hemoglobina</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->hemoglobinaHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold"> 12-15 g/dl</td>
                    </tr>';
            }
            if($hematologia->vgmHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">VCM</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->vgmHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold"> 82-96 fl</td>
                    </tr>';
            }
            if($hematologia->hgmHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">HCM</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->hgmHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">27-32 pg</td>
                    </tr>';
            }
            if($hematologia->chgmHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">CHCM</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->chgmHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold"> 30-35 g/dl</td>
                    </tr>';
            }
            $html .= '<tr>
                    <td style="font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 5px" colspan="3"></td>
                </tr>';
            if($hematologia->leucocitosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Leucocitos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->leucocitosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">5-10 mil</td>
                    </tr>';
            }
            if($hematologia->neutrofHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Neutrofilos segmentados</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->neutrofHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">'.$ns.'%</td>
                    </tr>';
            }
            if($hematologia->neutrofBandHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Neutrofilos en banda</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->neutrofBandHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">'.$nb.'%</td>
                    </tr>';
            }
            if($hematologia->linfocitosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Linfocitos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->linfocitosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">'.$linfocitos.'%</td>
                    </tr>';
            }
            if($hematologia->eosinofilosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Eosinófilos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->eosinofilosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">'.$eosinofilos.'%</td>
                    </tr>';
            }
            if($hematologia->monocitosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Monocitos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->monocitosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">'.$monocitos.'%</td>
                    </tr>';
            }
            if($hematologia->basofilosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Basófilos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->basofilosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">'.$basofilo.'%</td>
                    </tr>';
            }
            if($hematologia->blastosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Blastos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->blastosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">%</td>
                    </tr>';
            }
            if($hematologia->reticulocitosHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Reticulocitos</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->reticulocitosHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">0.5-2.0%</td>
                    </tr>';
            }
            if($hematologia->eritrosedHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Eritrosedimentación</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->eritrosedHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">0-20 mm/hr</td>
                    </tr>';
            }
            $html .= '<tr>
                <td style="font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 5px" colspan="3"></td>
            </tr>';
            if($hematologia->plaquetasHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Plaquetas</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->plaquetasHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold">150,000-450,000 xmmc</td>
                    </tr>';
            }
            if($hematologia->gotaGruesaHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Gota gruesa</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$hematologia->gotaGruesaHematologia.'</td>
                        <td style="text-align: center;  font-weight: bold"></td>
                    </tr>';
            }
            if($hematologia->rojaHematologia == "" && $hematologia->blancaHematologia == "" && $hematologia->plaquetariaHematologia == ""){
                $html .= '<tr></tr>';
            }else{
                $html .= '<tr>
                        <td colspan=3 style="text-align: center; background: rgba(0, 123, 255, 0.1);"><strong class="">Frotis de sangre periferica</strong></td>
                    </tr>';        
            }

            if($hematologia->rojaHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Linea roja</strong></td>
                        <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->rojaHematologia.'</td>
                    </tr>';
            }
            if($hematologia->blancaHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Linea blanca</strong></td>
                        <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->blancaHematologia.'</td>
                    </tr>';
            }
            if($hematologia->plaquetariaHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Linea plaquetaria</strong></td>
                        <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->plaquetariaHematologia.'</td>
                    </tr>';
            }

            $html .= '<tr>
                <td style="font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 5px" colspan="3"></td>
            </tr>';

            if($hematologia->observacionesHematologia != ""){
                $html .= '<tr>
                        <td><strong class="">Observaciones</strong></td>
                        <td style="text-align: center;  font-weight: bold" colspan=2>'.$hematologia->observacionesHematologia.'</td>
                    </tr>';
            }

            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            // return $html;
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_orina($id =null){

            $html = $this->html_cabecera($id, "tbl_orina", "idOrina", 13);
            $orina = $this->Laboratorio_Model->detalleExamen($id, 13);


            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADO EXAMEN ORINA</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Unidades </th>';
            $html .= '                  </tr>';
            $html .= '             </thead>';
            $html .= '             <tbody>';

            // Analisis fisico quimico
                $html .=  '<tr>
                    <td style="text-align: center; background: rgba(0, 123, 255, 0.1);" colspan=4><strong>Análisis Fisico-Quimico </strong></td>
                </tr>';
                if($orina->colorOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Color</strong></td>
                            <td style="text-align: center;">'.$orina->colorOrina.'</td>
                            <td style="text-align: center;">-</td>
                        </tr>';
                }
                if($orina->aspectoOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Aspecto</strong></td>
                            <td style="text-align: center;">'.$orina->aspectoOrina.'</td>
                            <td style="text-align: center;">-</td>
                        </tr>';
                }
                if($orina->densidadOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Densidad</strong></td>
                            <td style="text-align: center;">'.$orina->densidadOrina.'</td>
                            <td style="text-align: center;">-</td>
                        </tr>';
                }
                if($orina->phOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">pH</strong></td>
                            <td style="text-align: center;">'.$orina->phOrina.'</td>
                            <td style="text-align: center;">-</td>
                        </tr>';
                }
                if($orina->proteinasOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Proteínas</strong></td>
                            <td style="text-align: center;">'.$orina->proteinasOrina.'</td>
                            <td style="text-align: center;">mg/dl</td>
                        </tr>';
                }
                if($orina->glucosaOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Glucosa</strong></td>
                            <td style="text-align: center;">'.$orina->glucosaOrina.'</td>
                            <td style="text-align: center;">mg/dl</td>
                        </tr>';
                }
                if($orina->sangreOcultaOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Sangre oculta</strong></td>
                            <td style="text-align: center;">'.$orina->sangreOcultaOrina.'</td>
                            <td style="text-align: center;">-</td>
                        </tr>';
                }
                if($orina->bilirrubinaOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Bilirrubina</strong></td>
                            <td style="text-align: center;">'.$orina->bilirrubinaOrina.'</td>
                            <td style="text-align: center;">mg/dl</td>
                        </tr>';
                }
                if($orina->nitritoOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Nitrito</strong></td>
                            <td style="text-align: center;">'.$orina->nitritoOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->urobilinogenoOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Urobilinógeno</strong></td>
                            <td style="text-align: center;">'.$orina->urobilinogenoOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->cuerposCetonicosOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Cuerpos cetónicos</strong></td>
                            <td style="text-align: center;">'.$orina->cuerposCetonicosOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
            // Analisis fisico quimico
            // Analisis microscopico
                $html .=  '<tr>
                        <td style="text-align: center; background: rgba(0, 123, 255, 0.1);" colspan=4><strong>Análisis Microscopico </strong></td>
                    </tr>';
                if($orina->cilindrosOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Cilindros</strong></td>
                            <td style="text-align: center;">'.$orina->cilindrosOrina.'</td>
                            <td style="text-align: center;">x campo</td>
                        </tr>';
                }
                if($orina->hematiesOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Hematíes</strong></td>
                            <td style="text-align: center;">'.$orina->hematiesOrina.'</td>
                            <td style="text-align: center;">x campo</td>
                        </tr>';
                }
                if($orina->leucocitosOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Leucocitos</strong></td>
                            <td style="text-align: center;">'.$orina->leucocitosOrina.'</td>
                            <td style="text-align: center;">x campo</td>
                        </tr>';
                }
                if($orina->celulasEpitelialesOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Células epiteliales</strong></td>
                            <td style="text-align: center;">'.$orina->celulasEpitelialesOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->cristalesOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Cristales</strong></td>
                            <td style="text-align: center;">'.$orina->cristalesOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->parasitologicoOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Parasitológico</strong></td>
                            <td style="text-align: center;">'.$orina->parasitologicoOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->bacteriasOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Bacterias</strong></td>
                            <td style="text-align: center;">'.$orina->bacteriasOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->grumosOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Grumos leucocitarios</strong></td>
                            <td style="text-align: center;">'.$orina->grumosOrina.'</td>
                            <td style="text-align: center;">x campo</td>
                        </tr>';
                }
                if($orina->filamentoOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Filamento mucoide</strong></td>
                            <td style="text-align: center;">'.$orina->filamentoOrina.'</td>
                            <td style="text-align: center;"></td>
                        </tr>';
                }
                if($orina->observacionesOrina != ""){
                    $html .= '<tr>
                            <td><strong class="">Observaciones</strong></td>
                            <td style="text-align: center;">'.$orina->observacionesOrina.'</td>
                            <td></td>
                        </tr>';
                }

            // Fin analisis microscopico

            $html .= '                </tbody>';
            $html .= '            </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_hisopado($id =null){

            $html = $this->html_cabecera($id, "tbl_hisopado_nasal", "idHisopadoNasal", 14);
            $hisopado = $this->Laboratorio_Model->detalleExamen($id, 14);
            $cabecera = $this->Laboratorio_Model->cabeceraPDF($id, "tbl_hisopado_nasal", "idHisopadoNasal", 14);


            if(isset($hisopado->pasaporteHisopadoNasal)){
                if($hisopado->pasaporteHisopadoNasal != ""){
                    $html .= '<table><tr>
                            <td><strong class="borderAzul">Pasaporte:</strong></td>
                            <td><p class="borderAzul">'.$hisopado->pasaporteHisopadoNasal.'</p></td>
                        </tr></table>';
                }
            }

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>'.$cabecera->nombreMedicamento.'</p>';
            $html .= '        <div class="detalle">';
            if($hisopado->resultadoHisopadoNasal){
                $html .= '<p style="font-size: 16px; text-align: left; margin-top: 50px"><strong>Resultado: </strong>'.$hisopado->resultadoHisopadoNasal.'</p>';
            }

            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_espermograma($id =null){

            $html = $this->html_cabecera($id, "tbl_espermograma", "idEspermograma", 15);
            $espermograma = $this->Laboratorio_Model->detalleExamen($id, 15);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADO EXAMEN ESPERMOGRAMA</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                                <th> Parametro </th>';
            $html .= '                                <th> Resultado </th>';
            $html .= '                                <th> Unidades </th>';
            $html .= '                                <th> Valores de referencia </th>';
            $html .= '                  </tr>';
            $html .= '             </thead>';
            $html .= '             <tbody>';
           
            $html .= '<tr>';
            $html .= '    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Examen macroscópico</td>';
            $html .= '</tr>';

            if($espermograma->colorEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Color</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->colorEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->phEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Ph</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->phEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->volumenEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Volumen</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->volumenEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">ml</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->licuefaccionEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Licuefacción</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->licuefaccionEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->viscocidadEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Viscocidad</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->viscocidadEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->abstinenciaEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Dias de abstinencia</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->abstinenciaEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            
            $html .= '<tr>
                    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Examen microscópico directo</td>
                </tr>';
            if($espermograma->hematiesEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Hematíes</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->hematiesEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">x campo</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->leucocitosEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Leucocitos</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->leucocitosEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">x campo</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->epitelialesEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Células Epiteliales</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->epitelialesEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->bacteriasEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Bacterias</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->bacteriasEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold"></td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }

            $html .= '<tr>
                    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Movilidad</td>
                </tr>';

            if($espermograma->mprEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Movilidad progresivamente rápida</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->mprEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->mplEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Movilidad progresivamente lenta</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->mplEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->mnpEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Movilidad no progresiva</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->mnpEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->inmovilesEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Inmoviles</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->inmovilesEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            $html .= '<tr>
                    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Recuento</td>
                </tr>';
            if($espermograma->recuentoEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Recuento</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->recuentoEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">millones/ml</td>
                        <td style="text-align: center; font-weight: bold">20-150</td>
                    </tr>';
            }
            $html .= '<tr>
                    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Normalidad</td>
                </tr>';
            if($espermograma->normalesEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Normales</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->normalesEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->anormalCbEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Anormal cabeza</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->anormalCbEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->anormalClEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Anormal cola</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->anormalClEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            $html .= '<tr>
                    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 21px" colspan="4"><strong class="borderAzul">Vitalidad</td>
                </tr>';
            if($espermograma->vivosEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Vivos</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->vivosEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->muertosEspermograma != ""){
                $html .= '<tr>
                        <td><strong class="">Muertos</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$espermograma->muertosEspermograma.'</td>
                        <td style="text-align: center; font-weight: bold">%</td>
                        <td style="text-align: center; font-weight: bold"></td>
                    </tr>';
            }
            if($espermograma->observacionesEspermograma != ""){
                $html .= '<tr>
                        <td><strong><br>Observaciones</strong></td>
                        <td style="text-align: center;  font-weight: bold" colspan=3>'.$espermograma->observacionesEspermograma.'</td>
                    </tr>';
            }
            $html .= '              </tbody>';
            $html .= '          </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_creatinina($id =null){

            $html = $this->html_cabecera($id, "tbl_depuracion_creatinina", "idDepuracion", 16);
            $creatinina = $this->Laboratorio_Model->detalleExamen($id, 16);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADOS EXAMEN DEPURACION DE CREATININA EN ORINA DE 24 HORAS</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Valores normales </th>';
            $html .= '                  </tr>';
            $html .= '             </thead>';
            $html .= '             <tbody>';
           
            if($creatinina->tiempoDepuracion != ""){
                $html .= '<tr>
                        <td><strong class="">Volumen</strong></td>
                        <td style="text-align: center;">'.number_format($creatinina->volumenDepuracion).' ml/24h</td>
                        <td style="text-align: center;">750-2000 ml/24h</td>
                    </tr>';
            }

            if($creatinina->tiempoDepuracion != ""){
                $html .= '<tr>
                        <td><strong class="">Tiempo</strong></td>
                        <td style="text-align: center;">'.number_format($creatinina->tiempoDepuracion).' minutos</td>
                        <td style="text-align: center;"></td>
                    </tr>';
            }

            if($creatinina->csDepuracion != ""){
                $html .= '<tr>
                        <td><strong class="">Creatinina en sangre</strong></td>
                        <td style="text-align: center;">'.$creatinina->csDepuracion.' mg/dl</td>
                        <td style="text-align: center;">0.7-1.4 mg/dl</td>
                    </tr>';
            }

            if($creatinina->coDepuracion != ""){
                $html .= '<tr>
                        <td><strong class="">Creatinina en orina</strong></td>
                        <td style="text-align: center;">'.$creatinina->coDepuracion.' mg/dl</td>
                        <td style="text-align: center;">15-25 mg/dl</td>
                    </tr>';
            }

            if($creatinina->dcDepuracion != ""){
                $html .= '<tr>
                        <td><strong class=""> Depuración de creatinina</strong></td>
                        <td style="text-align: center;">'.$creatinina->dcDepuracion.' ml/min</td>
                        <td style="text-align: center;">'.$creatinina->valorNormal.'</td>
                    </tr>';
            }


            if($creatinina->proteinasDepuracion != ""){
                $html .= '<tr>
                        <td><strong class=""> Proteinas 24 hr</strong></td>
                        <td style="text-align: center;">'.$creatinina->proteinasDepuracion.' mg/24hr</td>
                        <td style="text-align: center;">Menor de 100</td>
                    </tr>';
            }
            $html .= '              </tbody>';
            $html .= '          </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_gases_arteriales($id =null){

            $html = $this->html_cabecera($id, "tbl_gases_arteriales", "idGasesArteriales", 17);
            $arteriales = $this->Laboratorio_Model->detalleExamen($id, 17);


            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADO EXAMEN GASES ARTERIALES</strong></p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Valores de referencia </th>';
            $html .= '                  </tr>';
            $html .= '             </thead>';
            $html .= '             <tbody>';
           

            if($arteriales->muestraGasesArteriales != ""){
                $html .= '<tr>
                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px"  colspan="3" ><strong>Muestra: </strong>'.$arteriales->muestraGasesArteriales.'</td>
                    </tr>';
            }
            if($arteriales->phGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">PH</strong></td>
                        <td style="text-align: center; font-weight: bold">'.$arteriales->phGasesArteriales.'</td>
                        <td style="text-align: center; font-weight: bold">7.20 - 7.60</td>
                    </tr>';
            }
            if($arteriales->pco2GasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">PCO2</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->pco2GasesArteriales.' mmHg</td>
                        <td style="text-align: center;  font-weight: bold">30.0 - 50.0 mmHg </td>
                    </tr>';
            }
            if($arteriales->po2GasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">PO2</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->po2GasesArteriales.' mmHg</td>
                        <td style="text-align: center;  font-weight: bold">80.0 - 100.0 mmHg</td>
                    </tr>';
            }
            if($arteriales->naGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">NA+</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->naGasesArteriales.' mmol/L</td>
                        <td style="text-align: center;  font-weight: bold">135.0 -145.0 mmol/L</td>
                    </tr>';
            }
            if($arteriales->kGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">K+</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->kGasesArteriales.' mmol/L</td>
                        <td style="text-align: center;  font-weight: bold">3.50 - 5.10 mmol/L</td>
                    </tr>';
            }
            if($arteriales->caGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">Ca++</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->caGasesArteriales.' mmol/L</td>
                        <td style="text-align: center;  font-weight: bold">1.13 - 1.32 mmol/L</td>
                    </tr>';
            }
            if($arteriales->tbhGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">tHb</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->tbhGasesArteriales.' gr/dl</td>
                        <td style="text-align: center;  font-weight: bold">12.0 - 17.0 gr/dl</td>
                    </tr>';
            }
            if($arteriales->soGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">S02</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->soGasesArteriales.' %</td>
                        <td style="text-align: center;  font-weight: bold">90.0 - 100.0%</td>
                    </tr>';
            }
            if($arteriales->fioGasesArteriales != ""){
                $html .= '<tr>
                        <td><strong class="">FIO2</strong></td>
                        <td style="text-align: center;  font-weight: bold">'.$arteriales->fioGasesArteriales.' %</td>
                        <td style="text-align: center;  font-weight: bold"></td>
                    </tr>';
            }
        
            $html .= '              </tbody>';
            $html .= '          </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_tolerancia_glucosa($id =null){

            $html = $this->html_cabecera($id, "tbl_tolerancia_glucosa", "idToleranciaGlucosa ", 18);
            $tolerancia = $this->Laboratorio_Model->detalleExamen($id, 18);

            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADO EXAMEN TOLERANCIA A LA GLUCOSA</strong></p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                  <tr style="background: #0b88c9;">';
            $html .= '                      <th> Parametro </th>';
            $html .= '                      <th> Resultado </th>';
            $html .= '                      <th> Valores de referencia </th>';
            $html .= '                  </tr>';
            $html .= '             </thead>';
            $html .= '             <tbody>';

            if($tolerancia->resultado1ToleranciaGlucosa != ""){
                $html .= '<tr>
                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px" colspan="3" ><strong>PRIMERA MUESTRA GLUCOSA EN AYUNAS('.$tolerancia->hora1ToleranciaGlucosa.')</strong></td>
                    </tr>';
                $html .= '<tr>
                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado1ToleranciaGlucosa.' mg/dl</td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">60 - 110 mg/dl</td>
                    </tr>';
            }

            if($tolerancia->resultado2ToleranciaGlucosa != ""){
                $html .= '<tr>
                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px" colspan="3" ><strong>1h POST CARGA '.$tolerancia->parametroCarga.'gr DE DEXTROSA('.$tolerancia->hora2ToleranciaGlucosa.')</strong></td>
                    </tr>';
                $html .= '<tr>
                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado2ToleranciaGlucosa.' mg/dl</td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">Menor de 200 mg/dl</td>
                    </tr>';
            }

            if($tolerancia->resultado3ToleranciaGlucosa != ""){
                $html .= '<tr>
                    <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px" colspan="3" ><strong>2h POST CARGA('.$tolerancia->hora3ToleranciaGlucosa.')</strong></td>
                </tr>';
                $html .= '<tr>
                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado3ToleranciaGlucosa.' mg/dl</td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">Menor de 140 mg/dl</td>
                    </tr>';
            }

            if($tolerancia->resultado4ToleranciaGlucosa != ""){
                $html .= '<tr>
                        <td style="text-align: center; font-size: 12px; background: rgba(0, 123, 255, 0.1); height: 30px" colspan="3" ><strong>3h POST CARGA('.$tolerancia->hora4ToleranciaGlucosa.')</strong></td>
                    </tr>';
                $html .= '<tr>
                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Glucosa</strong></td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">'.$tolerancia->resultado4ToleranciaGlucosa.' mg/dl</td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center; font-weight: bold">70 - 115 mg/dl</td>
                    </tr>';
            }


            if($tolerancia->observacionToleranciaGlucosa != ""){
                $html .= '<tr>
                        <td style="padding-top: 15px; padding-bottom:50px;"><strong class="">Observaciones: </strong></td>
                        <td style="padding-top: 15px; padding-bottom:50px; text-align: center;" colspan="2">'.$tolerancia->observacionToleranciaGlucosa.'</td>
                    </tr>';
            }
            $html .= '              </tbody>';
            $html .= '          </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

        public function examen_toxoplasma($id =null){

            $html = $this->html_cabecera($id, "tbl_toxoplasma", "idToxoplasma ", 19);
            $toxoplasma = $this->Laboratorio_Model->detalleExamen($id, 19);


            $html .= '        <p style="font-size: 12px; color: #0b88c9; margin-top: 25px"><strong>RESULTADO EXAMEN TOXOPLASMOSIS IGG/IGM</p>';
            $html .= '        <div class="detalle">';
            $html .= '            <table class="table">';
            $html .= '              <thead>';
            $html .= '                <tr style="background: #0b88c9;">';
            $html .= '                    <th> Parametro </th>';
            $html .= '                    <th> Resultado </th>';
            $html .= '                    <th> Valores de referencia </th>';
            $html .= '                </tr>';
            $html .= '             </thead>';
            $html .= '             <tbody>';

            if($toxoplasma->iggToxoplasma != ""){
                $html .= '<tr>
                        <td style="padding-top: 15px;"><strong class="">Toxoplasma IgG</strong></td>
                        <td style="padding-top: 15px; text-align: center; font-weight: bold">'.$toxoplasma->iggToxoplasma.' UI/ML</td>
                        <td style="padding-top: 15px; text-align: center; font-weight: bold">
                            <p><strong>Negativo: </strong> Menor de 4.0 UI/ML</p>
                            <p><strong>Positivo: </strong> Mayor de 8.0 UI/ML</p>
                            <p><strong>Zona gris: </strong> 4.0-8.0 UI/ML</p>
                        </td>
                    </tr>';
            }

            if($toxoplasma->igmToxoplasma != ""){
                $html .= '<tr>
                        <td style="padding-top: 15px;"><strong class="">Toxoplasma IgM</strong></td>
                        <td style="padding-top: 15px; text-align: center; font-weight: bold">'.$toxoplasma->igmToxoplasma.' UI/ML</td>
                        <td style="padding-top: 15px; text-align: center; font-weight: bold">
                            <p><strong>Negativo: </strong> Menor de 0.9 UI/ML</p>
                            <p><strong>Positivo: </strong> Mayor de 1.1 UI/ML</p>
                            <p><strong>Zona gris: </strong> 0.9-1.1 UI/ML</p>
                        </td>
                    </tr>';
            }

            if($toxoplasma->observacionesToxoplasma != ""){
                $html .= '<tr>
                        <td style="padding-top: 15px;"><strong class="">Observaciones</strong></td>
                        <td style="padding-top: 15px; text-align: left; font-weight: bold" colspan="2">'.$toxoplasma->observacionesToxoplasma.' </td>
                    </tr>';
            }
            $html .= '              </tbody>';
            $html .= '          </table>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            // Fin HTML
            return urlencode(base64_encode(serialize($html)));
        }

    

    public function subirDatos($datos = null, $pivote = null, $fila = null, $creadaPor = null, $paciente = null){
        if($datos != null){
            
            // Obteniendo el id de la consulta
                foreach ($datos as $row) {
                    $consulta = $row["idConsulta"];
                }
            // Obteniendo el id de la consulta

            $data["consulta"] = $consulta; // Para validar si ya hay un registro de este tipo.
            $data["paciente"] = $paciente; // Para validar si ya hay un registro de este tipo.
            $data["creadaPor"] = $creadaPor; // Para validar si ya hay un registro de este tipo.
            $data["cabecera"] = $this->cabecera_online($consulta); // Para validar si ya hay un registro de este tipo.
            $data["detalle"] = $datos;

            // Datos para bitacora -Anular externo cuenta
                $bitacora["idCuenta"] = $consulta;
                $bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
                $bitacora["usuario"] = $this->session->userdata('usuario_h');
                $bitacora["descripcionBitacora"] = "Subio los resultados a la nube";
                $this->Usuarios_Model->insertarMovimientoLab($bitacora); // Capturando movimiento de la hoja de cobro
            // Fin datos para bitacora -Anular externo cuenta

            if($pivote == 3){
                $data["consultaUro"] = $fila;
                $this->guardar_examenes_urologica($data, $consulta);
            }else{
                $data["consultaUro"] = 0;
                $this->guardar_examenes_online($data, $consulta);
            }
            
            
            /* $curl = new Curl();
            // $resp = $curl->post(https://urologica.hospitalorellana.com.sv/Servicios/guardar_examenes', $data); //Cambiar a la direccion en la nube
            $resp = $curl->post('http://192.168.1.92/urologica/Servicios/guardar_examenes', $data); //Cambiar a la direccion en la nube
            $data = json_decode($resp->response);
            if($data->estado == 1){
                $this->Laboratorio_Model->marcarOnline($consulta);
                $this->session->set_flashdata("exito","Los datos se compartieron con exito!");
                redirect(base_url()."/Laboratorio/detalle_consulta/".$consulta."/");
            }else{
                $this->session->set_flashdata("error","Error al compartir los datos!");
                redirect(base_url()."/Laboratorio/detalle_consulta/".$consulta."/");
            } */
                
            // echo json_encode($data);
            
        }
    }

    private function guardar_examenes_urologica($params = null, $consulta = null){
        $curl = new Curl();
        // $resp = $curl->post('http://192.168.1.92/urologica/Servicios/guardar_examenes', $params); //Cambiar a la direccion en la nube
        $resp = $curl->post('https://urologica.hospitalorellana.com.sv/Servicios/guardar_examenes', $params); //Cambiar a la direccion en la nube
        $data = json_decode($resp->response);
        if($data->estado == 1){
            // $this->Laboratorio_Model->marcarOnline($consulta); // Marcamos en 1 el campo 'online'

            // $resp = $curl->post('http://192.168.1.92/lab-online/Resultados/guardar_examenes', $params); //Cambiar a la direccion en la nube
            $resp = $curl->post('https://laboratorio.hospitalorellana.com.sv/Resultados/guardar_examenes', $params); //Cambiar a la direccion en la nube
            
            $data = json_decode($resp->response);
            $data->local = $consulta;
            $params = urlencode(base64_encode(serialize($data)));

            if($data->estado > 0){
                $sellar = $this->Laboratorio_Model->sellarConsulta($consulta, $data->consulta);
                if($sellar){
                    $this->session->set_flashdata("exito","Los datos se compartieron con exito!");
                    redirect(base_url()."Online/mensaje_final/".$params."/");
                }else{
                    $this->session->set_flashdata("error","Error al compartir los datos!");
                    redirect(base_url()."Laboratorio/detalle_consulta/".$consulta."/");
                }
            }else{
                $this->session->set_flashdata("error","Error al compartir los datos!");
                redirect(base_url()."Laboratorio/detalle_consulta/".$consulta."/");
            }
           
        }else{
            $this->session->set_flashdata("error","Error al compartir los datos!");
            redirect(base_url()."Laboratorio/detalle_consulta/".$consulta."/");
        }

        // echo print_r($datos);
    }

    private function guardar_examenes_online($params = null, $consulta = null){
        $curl = new Curl();
        // $resp = $curl->post('http://192.168.1.92/lab-online/Resultados/guardar_examenes', $params); //Cambiar a la direccion en la nube
        $resp = $curl->post('https://laboratorio.hospitalorellana.com.sv/Resultados/guardar_examenes', $params); //Cambiar a la direccion en la nube
        $data = json_decode($resp->response);
        // $data->local = $consulta;
        $params = urlencode(base64_encode(serialize($data)));
        if($data->estado > 0){
            $sellar = $this->Laboratorio_Model->sellarConsulta($consulta, $data->consulta);
            if($sellar){
                $this->session->set_flashdata("exito","Los datos se compartieron con exito!");
                redirect(base_url()."Online/mensaje_final/".$params."/");
            }else{
                $this->session->set_flashdata("error","Error al compartir los datos!");
                redirect(base_url()."Laboratorio/detalle_consulta/".$consulta."/");
            }
        }else{
            $this->session->set_flashdata("error","Error al compartir los datos!");
            redirect(base_url()."Laboratorio/detalle_consulta/".$consulta."/");
        }

    }

    public function mensaje_final($params = null){
        $consulta = unserialize(base64_decode(urldecode($params)));
        $data["consulta"] = $consulta;
        $data["paciente"] = $this->Laboratorio_Model->nombrePaciente($consulta->local);

        $this->load->view("Base/header");
        $this->load->view("Laboratorio/mensaje_final", $data);
        $this->load->view("Base/footer");
            
        // echo json_encode($data);
        
    }

    public function detalle_examenes_online($c = null){

        if($c != null){
            $this->load->view("Base/header");
            $this->load->view("Laboratorio/mensaje_online");
            $this->load->view("Base/footer");
        }else{
            redirect(base_url()."Laboratorio/detalle_consulta/$c/");
        }
    }
    // Subir resultados en linea


    public function detalle_resultados($params = null){
        $datos = unserialize(base64_decode(urldecode($params)));
        $consulta = 0;
        foreach ($datos as $row) {
            $consulta = $row["idConsulta"];
        }
        // redirect("http://localhost/Lab_Online/Resultados/detalle_examen/$consulta/");
        redirect("https://laboratorio.hospitalorellana.com.sv/Resultados/detalle_examen/$consulta/");
    }
	
}

