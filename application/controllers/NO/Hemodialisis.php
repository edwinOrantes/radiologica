<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Hemodialisis extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Hemodialisis_Model");
        $this->load->model("Paciente_Model");
        $this->load->model("Hoja_Model");
        $this->load->model("Usuarios_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    public function agregar_paciente(){
        // $data["accesos"] = $this->Accesos_Model->obtenerAccesos();
        $this->load->view('Base/header');
		$this->load->view('Hemodialisis/agregar_paciente');
		$this->load->view('Base/footer');
    }

    public function guardar_paciente(){
        $datos = $this->input->post();
        $datos["vinoA"] = 1;
        // echo json_encode($datos);
        $bool = $this->Hemodialisis_Model->guardarPaciente($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos del paciente se guardaron con exito!");
			redirect(base_url()."Hemodialisis/agregar_paciente/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos del paciente!");
			redirect(base_url()."Hemodialisis/agregar_paciente/");
		}
    }

    public function agregar_cita(){
        $this->load->view('Base/header');
        $this->load->view('Hemodialisis/agregar_cita');
        $this->load->view('Base/footer');
    }

    public function recomendaciones_paciente(){
        if($this->input->is_ajax_request()){
            $id =$this->input->get("id");
            $data = $this->Paciente_Model->buscarRecomendaciones(trim($id));
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function buscar_paciente(){
        if($this->input->is_ajax_request()){
            $id = $this->input->get("id");
            $data = $this->Paciente_Model->buscarPaciente(trim($id));
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function buscar_encargado(){
        if($this->input->is_ajax_request()){
            $id = $this->input->get("idPaciente");
            $data = $this->Paciente_Model->buscarEncargado(trim($id));
            echo json_encode($data);
        }
        else{
            echo "Error...";
        }
    }

    public function guardar_cita(){
        $datos = $this->input->post();
        $datos["estadoCita"] = 1;
        $bool = $this->Hemodialisis_Model->guardarCita($datos);
		if($bool){
			$this->session->set_flashdata("exito","La cita se creo con exito!");
			redirect(base_url()."Hemodialisis/agregar_cita/");
		}else{
			$this->session->set_flashdata("error","Error al crear la cita!");
			redirect(base_url()."Hemodialisis/agregar_cita/");
		}

        // echo json_encode($datos);

    }
    
    public function lista_citas($t = null, $f = null){
        $fecha = "";
        $turno = 0;
        if($this->input->post()){
            $datos = $this->input->post();
            $fecha = $datos["fechaCitas"];
            $turno = $datos["turnoCitas"];
        }else{
            if(!is_null($t)){
                $fecha = $f;
                $turno = $t;
            }else{
                $fecha = date('Y-m-d');
                // Obteniendo el turno al que se agregara la ultra
                    
                    if((date('h:i:s', time()) < date_create_from_format("h:i:s", "10:59:59")->format("h:i:s")) && (date('a', time()) == "am")){
                        $turno = 1;
                    }else{
                        $turno = 2;
                    }
                // Fin calcular turno
            }
        }
        $data["fecha"] = $this->obtenerFechaEnLetra($fecha);
        $data["dia"] = $fecha;
        $data["turno"] = $turno;
        $data["citas"] = $this->Hemodialisis_Model->listaCitas($turno, $fecha);
        $this->load->view("Base/header");
        $this->load->view("Hemodialisis/lista_citas", $data);
        $this->load->view("Base/footer");

        // echo json_encode($data);

        
    }

    public function saldar_cita(){
        if($this->input->is_ajax_request()){
            $id =$this->input->post("idCita");
            $resp = $this->Hemodialisis_Model->saldarCita(trim($id));
            echo json_encode($resp);
        }
        else{
            echo "Error...";
        }
    }

    public function crear_hoja(){
        $datos = $this->input->post();
        $id = $datos["pacienteHoja"];
        $cita = $datos["citaHoja"];
        $paciente = $this->Hemodialisis_Model->datosPaciente($id);

        // Obteniendo codigo de hoja de cobro
            $ultimoCodigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
            $uc = $ultimoCodigo->codigoHoja + 1;
            // $datos["codigoHoja"] = "$uc";
        // Fin codigo hoja

        // Datos de para la hoja
            $hoja["codigoHoja"]= $uc;
            $hoja["idPaciente"]= $paciente->idPaciente;
            $hoja["fechaHoja"] = date("Y-m-d");
            $hoja["tipoHoja"]= "Ambulatorio";
            $hoja["idMedico"]= "262";
            $hoja["idHabitacion"]= "38";
            /* $hoja["idMedico"]= "1";
            $hoja["idHabitacion"]= "1"; */
            $hoja["estadoHoja"]= "1";
            $hoja["seguroHoja"]= "1";
        // Fin datos
        //Insertando hoja
        $hoja = $this->Hemodialisis_Model->guardarHoja($hoja);
        //Arreglo para medicamentos
            // Estableciendo medicamentos por default
                $medicamentos = ["994","998"];
                // $medicamentos = ["1","2"];
                $precios = ["120","0"];
            // Fin establecer medicamentos
            $medicamento["idHoja"] = $hoja; //Se obtiene al insertar hoja
            $medicamento["idInsumo"] = $medicamentos; // Un arreglo de 2 elementos
            $medicamento["precioInsumo"] = $precios; // Un arreglo de 2 elementos
            $medicamento["cantidadInsumo"] = 1;
            $medicamento["fechaInsumo"] = date("Y-m-d");
        //Arreglo para medicamenos
        $resp = $this->Hemodialisis_Model->guardarMedicamento($medicamento, $cita);

        if($resp != 0){
            // Datos de la bitacora
                $bitacora["idCuenta"] = $hoja ;
                $bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
                $bitacora["usuario"] = $this->session->userdata('usuario_h');
                $bitacora["descripcionBitacora"] = "Creo la hoja de cobro desde hemodialisis";
            // Fin bitacora
            $this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
            $this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron guardados con exito!");
            redirect(base_url()."Hoja/detalle_hoja/$hoja/");
        }else{
            $this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
            redirect(base_url()."Hemodialisis/lista_citas/");
        }

        // echo json_encode($hoja);
    }

    public function actualizar_cita(){
        $datos = $this->input->post();
        $paciente["idPaciente"] = $datos["idPaciente"];
        $paciente["pacienteCita"] = $datos["nombrePaciente"];
        $paciente["turnoCita"] = $datos["turnoCita"];
        $paciente["fechaCita"] = $datos["fechaCita"];
        $paciente["responsablePaciente"] = $datos["responsablePaciente"];
        $paciente["telRespPaciente"] = $datos["telRespPaciente"];
        $paciente["estadoCita"] = "1";
        $paciente["idCita"] = $datos["idCita"];
        $bool = $this->Hemodialisis_Model->actualizarCita($paciente);
		if($bool){
			$this->session->set_flashdata("exito","Se actualizo la cita con exito!");
			redirect(base_url()."Hemodialisis/lista_citas/");
		}else{
			$this->session->set_flashdata("error","Error al actualizar la cita!");
			redirect(base_url()."Hemodialisis/lista_citas/");
		}
    }

    public function obtenerFechaEnLetra($fecha){
        // asigno a la variable $dia el dia de la semana dada una fecha ver funcion conocerDiaSemanaFecha
        $dia = $this->conocerDiaSemanaFecha($fecha);

        // asigno a la variable $num el número del dia de la fecha dada ejemplo: 17/06/2016 $num = 17 ver date en http://php.net/manual/es/function.date.php
        $num = date("j", strtotime($fecha));

        // asigno a la variable $anno el año de la fecha dada ejemplo: 17/06/2016 $anno = 2016 ver date en http://php.net/manual/es/function.date.php
        $anno = date("Y", strtotime($fecha));

        // asigno a la variable $mes una lista de los meses donde cada elemento de la lista concide con el numero del mes - 1
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

        // redefino la variable $mes que es una lista con el número de mes que me devuelve la (date('m', strtotime($fecha)), lo multiplico x1 y le
        // resto -1 ejemplo : fecha-> 17/06/2016 (date('m', strtotime($fecha))-> m= 07*1 -> 7-1 = 6 -> $mes[6] = junio
        $mes = $mes[(date('m', strtotime($fecha)) * 1) - 1];

        // retorno todo los valores concatenados como quiero ejemplo: Viernes, 17 de junio del 2016
        return $dia." ".$num . ' de ' . $mes . ' del ' . $anno;
    }

    //Para conocer el dia de la semana que cae una fecha dada
    public function conocerDiaSemanaFecha($fecha){
        // asigno a la variable $dia una lista de los dias donde cada elemento de la lista concide con el numero del dia
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');

        // redefino la lista $dia con el resultado de la funcion date('w', strtotime($fecha)) que devuelve el numero del dia
        // que coincide con la posicion de los dias en la lista $dia ejemplo: fecha = 17/06/2016 -> date('w', strtotime($fecha)) = 5 -> $dias[5] = Viernes
        $dia = $dias[date('w', strtotime($fecha))];
        // retorno el valor de la variable $dia que ya no es una lista sino una cadena de caracters que corresponde a Viernes
        return $dia;
    }

    public function eliminar_cita(){
        $datos = $this->input->post();
        $turno = $datos["turnoCita"];
        unset($datos["turnoCita"]);
        $fecha = $datos["fechaCita"];
        unset($datos["fechaCita"]);
        $bool = $this->Hemodialisis_Model->eliminarCita($datos);
		if($bool){
			$this->session->set_flashdata("exito","Se elimino la cita con exito!");
			redirect(base_url()."Hemodialisis/lista_citas/".$turno."/".$fecha."/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar la cita!");
			redirect(base_url()."Hemodialisis/lista_citas/".$turno."/".$fecha."/");
		}

    }

    public function boletas_para_pago($params = null){
        if($params != null){
            $citas = unserialize(base64_decode(urldecode($params)));
            $listaVales = array();
            $codigo = 0;
            foreach ($citas as $cita) {
                // Obtener codigo
                    $datos = $this->Hemodialisis_Model->obtenerCodigo();

                    if($datos->codigo == 0){
                        $codigo = 1000;
                    }else{
                        $codigo = $datos->codigo +1;;
                    }
                    $lista["codigoVale"] = $codigo;
                    $lista["idCita"] = $cita["idCita"];

                // Obtener codigo
                
                //Guardando vale
                    $resp = $this->Hemodialisis_Model->guardarVale($lista);
                //Guardando vale
                if($resp > 0){
                    $listaVales[]["idVale"] = $resp;
                }

                // echo json_encode($lista);
            }
            $params = urlencode(base64_encode(serialize($listaVales)));
            if(sizeof($listaVales) > 0){
                redirect(base_url()."Hemodialisis/lista_vales_pdf/".$params."/");
            }else{
                $this->session->set_flashdata("error","No hay datos que procesar!");
                redirect(base_url()."Hemodialisis/lista_citas/");
            }
        }
    }

    public function lista_vales_pdf($params = null){
        // Datos para el pdf
            $vales = unserialize(base64_decode(urldecode($params)));
            $boletas = array();
            foreach ($vales as $vale) {
                $datos = $this->Hemodialisis_Model->datosVale($vale["idVale"]);
                if(!is_null($datos)){
                    $data["vales"][] = $datos;
                }
            }
            // $data["vales"] = $boletas;
        // Datos para el pdf

        // Tickets para
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 18,
                'margin_bottom' => 18,
                'margin_header' => 10,
                'margin_footer' => 20
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

            $html = $this->load->view('Hemodialisis/boleta_pdf', $data,true); // Cargando hoja de estilos

            $mpdf->WriteHTML($html);
            $mpdf->Output('detalle_compra.pdf', 'I');
        // Tickets para

        // $this->load->view('Hemodialisis/boleta_pdf', $data); // Cargando hoja de estilos
        // echo json_encode($data);
    }

    public function boleta_para_pago($cita = null){
        $datosCita = $this->Hemodialisis_Model->valexCita($cita);
        $datos = $this->Hemodialisis_Model->datosVale($datosCita->idVale);
        if(!is_null($datos)){
            $data["vales"][] = $datos;
            // Tickets para
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
                $mpdf = new \Mpdf\Mpdf([
                    'margin_left' => 15,
                    'margin_right' => 15,
                    'margin_top' => 18,
                    'margin_bottom' => 18,
                    'margin_header' => 10,
                    'margin_footer' => 20
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
    
                $html = $this->load->view('Hemodialisis/boleta_pdf', $data,true); // Cargando hoja de estilos
    
                $mpdf->WriteHTML($html);
                $mpdf->Output('detalle_compra.pdf', 'I');
            // Tickets para
        }else{
            $this->session->set_flashdata("error","La hoja de cobro ya fue saldada, no se pueden generar la boleta de pago.!");
			redirect(base_url()."Hemodialisis/lista_citas/");
        }
        // echo json_encode($datos);
    }

    
    
}

