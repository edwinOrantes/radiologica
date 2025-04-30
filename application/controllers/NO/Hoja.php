<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

// Libreria para impresora termica
	use Mike42\Escpos\Printer;
	use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
	use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
	use Mike42\Escpos\PrintConnectors\FilePrintConnector;
	use Mike42\Escpos\EscposImage;
// Libreria para impresora termica

defined('BASEPATH') OR exit('No direct script access allowed');

class Hoja extends CI_Controller {
	
	// Seccion para hoja de cobro
		public function __construct(){
			parent::__construct();
			date_default_timezone_set('America/El_Salvador');
			if (!$this->session->has_userdata('valido')){
				$this->session->set_flashdata("error", "Debes iniciar sesión");
				redirect(base_url());
			}
			$this->load->model("Medico_Model");
			$this->load->model("Paciente_Model");
			$this->load->model("Botiquin_Model");
			$this->load->model("Externos_Model");
			$this->load->model("Hoja_Model");
			$this->load->model("Usuarios_Model");
			$this->load->model("Quirofanos_Model");
			$this->load->model("Planilla_Model");
			$this->load->model("Facturacion_Model");

			date_default_timezone_set('America/El_Salvador');

		}
		
		public function index(){
			$data["medicos"] = $this->Medico_Model->obtenerMedicos();
			$data["pacientes"] = $this->Paciente_Model->obtenerPacientes();
			$data["habitaciones"] = $this->Paciente_Model->obtenerHabitaciones2();
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
			$data["externos"] = $this->Externos_Model->obtenerExternos();
			$data["seguros"] = $this->Hoja_Model->segurosActivos();
			$codigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
			$cod = 0;
			if($codigo->codigoHoja == NULL ){
				$cod = 1000;
			}else{
				$cod = ($codigo->codigoHoja + 1);
			}
			$data["codigo"] = $cod;

			$this->load->view('Base/header');
			$this->load->view('Hoja/agregar_hoja', $data);
			$this->load->view('Base/footer');
			// echo json_encode($data);
		}

		public function guardar_hoja(){			  
			echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
			$destino = 0;
			$procedimiento = array();
			$datos = $this->input->post();
			$esPaquete = 0;
			if(isset($datos["esPaquete"])){
				$esPaquete = 1;
				$montoPaquete = $datos["totalPaquete"];
				unset($datos["totalPaquete"]);
				unset($datos["esPaquete"]);
			}else{
				unset($datos["totalPaquete"]);
			}

			// Proceso de crear lista de pacientes para ultra
				/* 	if(isset($datos["destinoHoja"])){
						$destino = $datos["destinoHoja"];
						unset($datos["destinoHoja"]);
					}
					if($destino > 0){
						// Obteniendo el turno al que se agregara la ultra
							$turno = 0;
							if((date('h:i:s', time()) < date_create_from_format("h:i:s", "10:15:00")->format("h:i:s")) && (date('a', time()) == "am")){
								$turno = 1;
							}else{
								$turno = 2;
							}
						// Fin calcular turno

						// numeroLlegada, pacienteLlegada, edadLlegada, codigoLlegada, destinoLlegada, estadoLlegada, fechaLlegada, idHoja
						$buscandoCupo = $this->Hoja_Model->obtenerCupo($datos["fechaHoja"], $destino, $turno);
						$cupo = 0;
						if($buscandoCupo->cupo == NULL){
							$cupo = 1;
						}else{
							$cupo = $buscandoCupo->cupo + 1;
						}

						$fila["cupo"] = $cupo;
						$fila["paciente"] = $datos["nombrePaciente"];
						$fila["edad"] = $datos["edadPaciente"];
						$fila["codigo"] = "AM01";
						$fila["destino"] = $destino;
						$fila["estado"] = 1;
						$fila["fecha"] = $datos["fechaHoja"];

					} */
			// Proceso de crear lista de pacientes para ultra

			$bita = $datos["nombrePaciente"];
			if(sizeof($datos) > 0){
				
				$c = $datos["codigoHoja"];
				$codigo = $this->Hoja_Model->validarCodigoHoja($c);
				if(!is_null($codigo)){
					$ultimoCodigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
					$uc = $ultimoCodigo->codigoHoja + 1;
					$datos["codigoHoja"] = "$uc";
				}

				if(isset($datos["idPaciente"]) && $datos["idPaciente"] > 0){
					if(isset($datos["pacienteAmbulatorio"])){
						$paciente["nombrePaciente"] = $datos["nombrePaciente"];
						$paciente["edadPaciente"] = $datos["edadPaciente"];
						$paciente["telefonoPaciente"] = "0000-0000";
						$paciente["duiPaciente"] = $datos["duiPaciente"];
						$paciente["nacimientoPaciente"] = "0000-00-00";
						$paciente["direccionPaciente"] = "Usulután";
						$paciente["idPaciente"] = $datos["idPaciente"];

						unset($datos["pacienteAmbulatorio"]);
					}else{
						$paciente["nombrePaciente"] = $datos["nombrePaciente"];
						$paciente["edadPaciente"] = $datos["edadPaciente"];
						$paciente["telefonoPaciente"] = $datos["telefonoPaciente"];
						$paciente["duiPaciente"] = $datos["duiPaciente"];
						$paciente["nacimientoPaciente"] = $datos["nacimientoPaciente"];
						$paciente["direccionPaciente"] = $datos["direccionPaciente"];
						$paciente["idPaciente"] = $datos["idPaciente"];
					}

					unset($datos["nombrePaciente"]);
					unset($datos["edadPaciente"]);
					unset($datos["telefonoPaciente"]);
					unset($datos["duiPaciente"]);
					unset($datos["nacimientoPaciente"]);
					unset($datos["direccionPaciente"]);

					$data["paciente"] = $paciente;
					$data["hoja"] = $datos;

				}else{
					if($datos["tipoHoja"] == "Ingreso"){
						$paciente["nombrePaciente"] = $datos["nombrePaciente"];
						$paciente["edadPaciente"] = $datos["edadPaciente"];
						$paciente["telefonoPaciente"] = $datos["telefonoPaciente"];
						$paciente["duiPaciente"] = $datos["duiPaciente"];
						$paciente["nacimientoPaciente"] = $datos["nacimientoPaciente"];
						$paciente["direccionPaciente"] = $datos["direccionPaciente"];
					}else{
						$paciente["nombrePaciente"] = $datos["nombrePaciente"];
						$paciente["edadPaciente"] = $datos["edadPaciente"];
						$paciente["telefonoPaciente"] = "0000-0000";
						$paciente["duiPaciente"] = $datos["duiPaciente"];
						$paciente["nacimientoPaciente"] = "0000-00-00";
						$paciente["direccionPaciente"] = "Usulután";
						$paciente["idPaciente"] = "0";
					}
					unset($datos["nombrePaciente"]);
					unset($datos["edadPaciente"]);

					$data["paciente"] = $paciente;
					$data["hoja"] = $datos;
					
				}

				$resp = $this->Hoja_Model->guardarHoja($data);
				
				if($resp != 0){

					// Datos para bitacora -Anular externo cuenta
						$bitacora["idCuenta"] = $resp ;
						$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
						$bitacora["usuario"] = $this->session->userdata('usuario_h');
						$bitacora["descripcionBitacora"] = "Creo la hoja de cobro";
					// Fin datos para bitacora -Anular externo cuenta


					//Agregando paciente a cola de ultra
						/* 	if($destino > 0){
								$fila["hoja"] = $resp;
								$fila["turno"] = $turno;
								$this->Hoja_Model->guardarLlegadaPaciente($fila);
							} */
					// Fin agregar a la cola  de ultra

					// Actualizando hoja a paquete
						if($esPaquete == 1){
							$this->Hoja_Model->esPaquete($resp, $montoPaquete);
						}
					// Actualizando hoja a paquete
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron guardados con exito!");
					redirect(base_url()."Hoja/validacion_cirugia/$resp/");
				}else{
					$this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
					redirect(base_url()."Hoja/");
				}

				// echo json_encode($datos);

			}else{
				$this->session->set_flashdata("error","No se permite el reenvio de datos");
				redirect(base_url()."Hoja/");
			}

		}

		public function crear_hoja(){			  
			echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
			$datos = $this->input->post();
			// Validando si existe un responsable de la cuenta ya sea empleado o familiar.
				if(isset($datos["nombreResponsableCuenta"])){
					$responsable = $datos["nombreResponsableCuenta"];
					unset($datos["nombreResponsableCuenta"]);
					$datos["nombreResponsableCuenta"] = $responsable;
				}else{
					$responsable = "";
					$datos["nombreResponsableCuenta"] = $responsable;
				}
		
			// Validando si existe un responsable de la cuenta ya sea empleado o familiar.
			$destino = 0;
			$procedimiento = array();
			$esPaquete = 0;
			if(isset($datos["esPaquete"])){
				$esPaquete = 1;
				$montoPaquete = $datos["totalPaquete"];
				unset($datos["totalPaquete"]);
				unset($datos["esPaquete"]);
			}else{
				unset($datos["totalPaquete"]);
			}
			
			
			if(sizeof($datos) > 0){
				
				$c = $datos["codigoHoja"];
				$codigo = $this->Hoja_Model->validarCodigoHoja($c);
				if(!is_null($codigo)){
					$ultimoCodigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
					$uc = $ultimoCodigo->codigoHoja + 1;
					$datos["codigoHoja"] = "$uc";
				}
				$data["hoja"] = $datos;

				$resp = $this->Hoja_Model->guardarHoja($data);
				
				if($resp != 0){

					// Datos para bitacora -Anular externo cuenta
						$bitacora["idCuenta"] = $resp ;
						$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
						$bitacora["usuario"] = $this->session->userdata('usuario_h');
						$bitacora["descripcionBitacora"] = "Creo la hoja de cobro";
					// Fin datos para bitacora -Anular externo cuenta

					// Actualizando hoja a paquete
						if($esPaquete == 1){
							$this->Hoja_Model->esPaquete($resp, $montoPaquete);
						}
					// Actualizando hoja a paquete
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron guardados con exito!");

					// Agregando redireccion si es ingreso
						if($datos["tipoHoja"] == "Ingreso"){
							$datos["hoja"] = $resp;
							$params = urlencode(base64_encode(serialize($datos)));
							redirect(base_url()."Enfermeria/Expediente/agregar_paciente/$params/");
							
						}else{
							redirect(base_url()."Hoja/detalle_hoja/$resp/");
						}
					// Agregando redireccion si es ingreso

				}else{
					$this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
					redirect(base_url()."Hoja/");
				}

			}else{
				$this->session->set_flashdata("error","No se permite el reenvio de datos");
				redirect(base_url()."Hoja/");
			}

			// echo json_encode($datos);

		}

		public function paquete_hoja(){
			$datos = $this->input->post();
			$idReturn = $datos["idHoja"];
			if($datos["pivotePaquete"] == 1){
				$datos["pivotePaquete"] = 0;
				$datos["totalPaquete"] = 0;
			}else{
				$datos["pivotePaquete"] = 1;
			}
			// Datos para bitacora -Editar medicamento cuenta
				$bitacora["idCuenta"] = $datos["idHoja"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Cambio la modalidad de la hoja a Paquete";
			// Fin datos para bitacora -Editar medicamento cuenta

			$bool = $this->Hoja_Model->paqueteHoja($datos);
			if($bool){
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}else{
				$this->session->set_flashdata("error","Error al eliminar los datos del servicio externo!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}

			// echo json_encode($datos);
		}

		public function validacion_cirugia($pivote = null){
			$datos = $this->Hoja_Model->datosProcedimiento($pivote);
			if($datos->destinoHoja == 7){
				$procedimiento["hoja"] = $datos->idHoja;
				$procedimiento["paciente"] = $datos->idPaciente;
				$procedimiento["procedimiento"] = $datos->procedimientoHoja;
				$procedimiento["fecha"] = date('Y-m-d');
				$procedimiento["hora"] = date('h:i:s A');
				$bool = $this->Hoja_Model->guardarSala($procedimiento);
				if($bool){
					redirect(base_url()."Hoja/detalle_hoja/$pivote/");
				}else{
					redirect(base_url()."Hoja/detalle_hoja/$pivote/");
				}
			}else{
				redirect(base_url()."Hoja/detalle_hoja/$pivote/");
			}

			// echo date('h:i:s A');
		}

		public function guardar_presupuesto_hoja(){
			$datos = $this->input->post();
			$c = $datos["codigoHoja"];
			$codigo = $this->Hoja_Model->validarCodigoHoja($c);
			if(!is_null($codigo)){
				$ultimoCodigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
				$uc = $ultimoCodigo->codigoHoja + 1;
				$datos["codigoHoja"] = "$uc";
			}
			if(isset($datos["idPaciente"]) && $datos["idPaciente"] > 0){
				if(isset($datos["pacienteAmbulatorio"])){
					$paciente["nombrePaciente"] = $datos["nombrePaciente"];
					$paciente["edadPaciente"] = $datos["edadPaciente"];
					$paciente["telefonoPaciente"] = "0000-0000";
					$paciente["duiPaciente"] = "00000000-0";
					$paciente["nacimientoPaciente"] = "0000-00-00";
					$paciente["direccionPaciente"] = "Usulután";
					$paciente["idPaciente"] = $datos["idPaciente"];

					unset($datos["pacienteAmbulatorio"]);
				}else{
					$paciente["nombrePaciente"] = $datos["nombrePaciente"];
					$paciente["edadPaciente"] = $datos["edadPaciente"];
					$paciente["telefonoPaciente"] = $datos["telefonoPaciente"];
					$paciente["duiPaciente"] = $datos["duiPaciente"];
					$paciente["nacimientoPaciente"] = $datos["nacimientoPaciente"];
					$paciente["direccionPaciente"] = $datos["direccionPaciente"];
					$paciente["idPaciente"] = $datos["idPaciente"];
				}

				unset($datos["nombrePaciente"]);
				unset($datos["edadPaciente"]);
				unset($datos["telefonoPaciente"]);
				unset($datos["duiPaciente"]);
				unset($datos["nacimientoPaciente"]);
				unset($datos["direccionPaciente"]);

				// Detalle de la hoja
				$hoja["codigoHoja"] = $datos["codigoHoja"];
				$hoja["tipoHoja"] = $datos["tipoHoja"];
				$hoja["fechaHoja"] = $datos["fechaHoja"];
				$hoja["idMedico"] = $datos["idMedico"];
				$hoja["idHabitacion"] = $datos["idHabitacion"];
				$hoja["idPaciente"] = $datos["idPaciente"];
				$hoja["estadoHoja"] = $datos["estadoHoja"];

				unset($datos["codigoHoja"]);
				unset($datos["tipoHoja"]);
				unset($datos["fechaHoja"]);
				unset($datos["idMedico"]);
				unset($datos["idHabitacion"]);
				unset($datos["idPaciente"]);
				unset($datos["estadoHoja"]);

				$data["paciente"] = $paciente;
				$data["hoja"] = $hoja;
				$data["detalle"] = $datos;


			}else{
				if($datos["tipoHoja"] == "Ingreso"){
					$paciente["nombrePaciente"] = $datos["nombrePaciente"];
					$paciente["edadPaciente"] = $datos["edadPaciente"];
					$paciente["telefonoPaciente"] = $datos["telefonoPaciente"];
					$paciente["duiPaciente"] = $datos["duiPaciente"];
					$paciente["nacimientoPaciente"] = $datos["nacimientoPaciente"];
					$paciente["direccionPaciente"] = $datos["direccionPaciente"];
				}else{
					$paciente["nombrePaciente"] = $datos["nombrePaciente"];
					$paciente["edadPaciente"] = $datos["edadPaciente"];
					$paciente["telefonoPaciente"] = "0000-0000";
					$paciente["duiPaciente"] = "00000000-0";
					$paciente["nacimientoPaciente"] = "0000-00-00";
					$paciente["direccionPaciente"] = "Usulután";
					$paciente["idPaciente"] = "0";
				}
				
				// Detalle de la hoja
				$hoja["codigoHoja"] = $datos["codigoHoja"];
				$hoja["tipoHoja"] = $datos["tipoHoja"];
				$hoja["fechaHoja"] = $datos["fechaHoja"];
				$hoja["idMedico"] = $datos["idMedico"];
				$hoja["idHabitacion"] = $datos["idHabitacion"];
				$hoja["estadoHoja"] = $datos["estadoHoja"];

				unset($datos["codigoHoja"]);
				unset($datos["tipoHoja"]);
				unset($datos["fechaHoja"]);
				unset($datos["idMedico"]);
				unset($datos["idHabitacion"]);
				unset($datos["estadoHoja"]);

				unset($datos["nombrePaciente"]);
				unset($datos["edadPaciente"]);

				$data["paciente"] = $paciente;
				$data["hoja"] = $hoja;
				$data["detalle"] = $datos;
				
			}
			
			$resp = $this->Hoja_Model->guardarPresupuestoHoja($data);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron guardados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$resp/");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
				redirect(base_url()."Hoja/");
			}

			// echo json_encode($data["detalle"]);
			

		}

		public function detalle_hoja($id){
			$data["paciente"] = $this->Hoja_Model->detalleHoja($id);
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
			$data["externos"] = $this->Externos_Model->obtenerExternos();
			$data["responsable"] = $this->Paciente_Model->obtenerResponsable($data["paciente"]->idPaciente);
			$data["seguros"] = $this->Hoja_Model->segurosActivos();
			$data["documentos"] = $this->Hoja_Model->obtenerDetalleCatalogo();
			
			// Nuevos stocks
				$data["stockCaja1"] = $this->Botiquin_Model->medicamentosCajaParo(1);
				$data["stockCaja2"] = $this->Botiquin_Model->medicamentosCajaParo(2);
				$data["stockCaja3"] = $this->Botiquin_Model->medicamentosCajaParo(3);
				$data["stockCaja4"] = $this->Botiquin_Model->medicamentosCajaParo(4);
				$data["stockCaja5"] = $this->Botiquin_Model->medicamentosCajaParo(5);
			// Nuevos stocks
			
			// Detalles de la hoja
				$data["externosHoja"] = $this->Hoja_Model->externosHoja($id);
				$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosHoja($id);
			// Fin detalle hoja
			
			// Código factura
				
				$codigoFactura = $this->Hoja_Model->hojaFactura($id);
				
				if($codigoFactura == NULL){
					// Consumidor final
						$facturaC = $this->Hoja_Model->obtenerCodigoFacturaC();
						$codC = 0;
						if($facturaC->numeroFactura == NULL){
							$codC = 3965;
						}else{
							$codC = $facturaC->numeroFactura + 1;
						}
						$data["facturaC"] = $codC;
					// Fin consumidor Final
					
					// Credito fiscal
						$facturaCF = $this->Hoja_Model->obtenerCodigoFacturaCF();
						$codCF = 0;
						if($facturaCF->codigo == NULL){
							$codCF = 2240;
						}else{
							$codCF = $facturaCF->codigo + 1;
						}
						$data["facturaCF"] = $codCF;
					// Fin credito fiscal
					
				}else{
					$montoFactura = $this->Hoja_Model->montoFactura($id); // Obteniendo el monto de la factura
					if($montoFactura != NULL){
						$data["montoFactura"] = $montoFactura;
					}else{
						$data["montoFactura"] = 0;
					}

					$data["facturaC"] = $codigoFactura->numeroFactura;
					$data["fechaFacturaHoja"] = $codigoFactura->fechaMostrar;
					$data["tipoFacturaHoja"] = $codigoFactura->tipoFactura;
					$data["facturaCF"] = $codigoFactura->numeroFactura;
					if(isset($codigoFactura->clienteFactura) && $codigoFactura->clienteFactura != ""){
						$data["pacienteFactura"] = $codigoFactura->clienteFactura;
						$data["duiFactura"] = $codigoFactura->duiFactura;
					}else{
						$data["pacienteFactura"] = $data["paciente"]->nombrePaciente;
						$data["duiFactura"] = $data["paciente"]->duiPaciente;
					}
					$data["pivoteFactura"] = 1;
				}
			// Fin codigo factura

			// Obteniendo turno
				$data["turno"] = $this->obtenerTurno();
			// Fin obtener turno

			// Si es por pagos
				if($data["paciente"]->porPagos == 1){
					$data["pagos"] = $this->Hoja_Model->obtenerPagos( $data["paciente"]->idHoja);
				}
			// Si es por pagos

			$this->load->view('Base/header');
			$this->load->view('Hoja/detalle_hoja', $data);
			$this->load->view('Base/footer');

			// echo json_encode($data["paciente"] );


		}

		public function lista_hojas(){
			$global = $this->session->userdata('global');
			$data["hojas"] = $this->Hoja_Model->obtenerRangoHojas($global);
			$data["medicos"] = $this->Medico_Model->obtenerMedicos();
			$data["habitaciones"] = $this->Paciente_Model->obtenerHabitaciones();
			
			
			$this->load->view('Base/header');
			$this->load->view('Hoja/lista_hojas', $data);
			$this->load->view('Base/footer');

			// echo json_encode($data["hojas"]);

		}

		public function historial_hojas(){
			
			$this->load->view('Base/header');
			$this->load->view('Hoja/buscar_hojas');
			$this->load->view('Base/footer');

			// var_dump($data["hojas"]);

		}

		public function detalle_historial(){
			echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
			$datos = $this->input->post();
			if(!isset($datos["nombrePaciente"])){
				$this->session->set_flashdata("error","Regresando a busqueda de pacientes!");
				redirect(base_url()."Hoja/historial_hojas");
			}else{
				$datos = $this->input->post();
				$param = trim($datos["nombrePaciente"]);
				$data["hojas"] = $this->Hoja_Model->obtenerHojasPN($param);
				$data["medicos"] = $this->Medico_Model->obtenerMedicos();
				$data["habitaciones"] = $this->Paciente_Model->obtenerHabitaciones();
				
				$this->load->view('Base/header');
				$this->load->view('Hoja/lista_hojas', $data);
				$this->load->view('Base/footer');
			}
		}

		public function guardar_detalle_hoja(){
			$datos = $this->input->post();
			$idReturn = $datos["idHoja"];
			$this->session->set_flashdata("error","Accion no valida");
			redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			/*$bool = $this->Hoja_Model->guardarDetalleHoja($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron guardados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
				redirect(base_url()."Hoja/");
			} */
			//var_dump($datos);
		
		}

		public function actualizar_medicamento(){
			$datos = $this->input->post();
			$precio = 0;
			$idReturn = $datos["idHojaInsumo"];
			$seguro = $this->Botiquin_Model->obtenerSeguro($idReturn);
			$medicamentoNuevo = $this->Botiquin_Model->obtenerMedicamento2($datos["idNuevoMedicamento"]); //Datos del nuevo medicamento

			$nuevaCantidad = $datos["cantidadInsumoNuevo"] * $datos["baseNuevaMedida"];

			if($seguro->porcentajeSeguro > 0){
				$precio = round($datos["precioNuevoMedicamento"] + ($datos["precioNuevoMedicamento"] * ($seguro->porcentajeSeguro/100)), 2);
			}else{
				$precio = $datos["precioNuevoMedicamento"];
			}

			$data["cantidadInsumo"] = $datos["cantidadInsumoNuevo"];
			$data["cantidadMG"] = $nuevaCantidad;
			$data["idInsumo"] = $datos["idNuevoMedicamento"];
			$data["precioInsumo"] = $precio;
			$data["idHojaInsumo"] = $datos["transaccion"];


			// Datos para bitacora -Editar medicamento cuenta
				$nombreMedicamento = $this->Usuarios_Model->nombreMedicamento($datos["idMedicamentoViejo"]); // Medicamento antiguo
				$nombreMedicamento2 = $this->Usuarios_Model->nombreMedicamento($datos["idNuevoMedicamento"]); // Medicamento nuevo
				$bitacora["idCuenta"] = $datos["idHojaInsumo"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Cambio ".$datos["cantidadMedicamentoViejo"]." elementos de ".$nombreMedicamento->nombre." a ".$nuevaCantidad." elementos de ".$nombreMedicamento2->nombre;
			// Fin datos para bitacora -Editar medicamento cuenta
			
			$bool = $this->Hoja_Model->actualizarHoja($data); // Ejecutando consultas
			if($bool){
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron actualizados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos de la hoja de cobro!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}
			// echo json_encode($data);
		}

		public function actualizar_medicamento_hoja(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				$base = $this->Hoja_Model->filaHoja($datos["idHojaInsumo"]); // Cantidad base de la medida agregada
				$data["cantidad"] = $datos["cantidadNueva"];
				$data["cantidadMG"] = $datos["cantidadNueva"] * $base->cantidadBase;
				$data["insumo"] = $datos["id"];
				$data["precio"] = $datos["precio"];
				$data["fila"] = $datos["idHojaInsumo"]; // Fila que se va editar


				// Datos para bitacora -Editar medicamento cuenta
					$nombreMedicamento = $this->Usuarios_Model->nombreMedicamento($this->input->post("id"));
					$bitacora["idCuenta"] = $this->input->post("idHoja");
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "Edito de ".$this->input->post("cantidadVieja")." elementos a ".$this->input->post("cantidadNueva")." el medicamento ".$nombreMedicamento->nombre;
				// Fin datos para bitacora -Editar medicamento cuenta	
				
				// Ejecutando consultas
				$bool = $this->Hoja_Model->actualizarHoja($data);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
					header("content-type:application/json");
					print json_encode($respuesta);
				}else{
					$respuesta = array('estado' => 0, 'respuesta' => 'Error');
					header("content-type:application/json");
					print json_encode($respuesta);
				}
				// echo json_encode($data);
			}
			else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}
		}

		public function eliminar_medicamento(){
			$datos = $this->input->post();
			$idReturn = $datos["idHojaReturn"];
			// Datos para eliminar medicamento
				$datosMedicamento["idHojaInsumo"] = $datos["idHojaInsumo"];

			// Datos para actualizar el nuevo stock
				$datosStock["stockMedicamento"] = ($datos["stockMedicamento"] + $datos["cantidadMedicamento"]);
				$datosStock["usadosMedicamento"] = ($datos["usadosMedicamento"] - $datos["cantidadMedicamento"]);
				$datosStock["idMedicamento"] = $datos["idMedicamento"];

			// Datos para Kardex
				$datosKardex["idMedicamento"] = $datos["idMedicamento"];
				$datosKardex["cantidadMedicamento"] = $datos["cantidadMedicamento"];
				$datosKardex["descripcionMedicamento"] = "Entrada";
				$datosKardex["factura"] = $datos["idHojaReturn"];
				$datosKardex["transaccion"] = $datos["transaccion"];
			// Ejecutando consultas
				$bool = $this->Hoja_Model->eliminarMedicamento($datosMedicamento, $datosStock, $datosKardex);
				if($bool){
					$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron eliminados con exito!");
					redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
				}else{
					$this->session->set_flashdata("error","Error al eliminar los datos de la hoja de cobro!");
					redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
				} 
				

		}

		public function actualizar_externo(){
			// Parametros
				$datos = $this->input->post();
				$idReturn = $datos["idHojaReturn"];
				$datosExterno["cantidadExterno"] = $datos["cantidadExterno"];
				$datosExterno["precioExterno"] = $datos["precioExterno"];
				$datosExterno["idHojaExterno"] = $datos["idExterno"];
			// Fin parametros
			// Datos para bitacora -Restaurar cuenta
			$datosExternoB = $this->Usuarios_Model->datosExterno($datos["idExterno"]);
			$bitacora["idCuenta"] = $datosExternoB->idHoja;
			$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
			$bitacora["usuario"] = $this->session->userdata('usuario_h');
			$bitacora["descripcionBitacora"] = "Cambio la cantidada del externo ".$datosExternoB->nombreExterno." de $".$datosExternoB->precioExterno." a $".$datos["precioExterno"];
			// Fin datos para bitacora -Restaurar cuenta
			// Ejecutando consultas
				$bool = $this->Hoja_Model->actualizarExterno($datosExterno);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$this->session->set_flashdata("exito","Los datos de los servicios externos fueron actualizados con exito!");
					redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
				}else{
					$this->session->set_flashdata("error","Error al actualizar los datos de la hoja de cobro!");
					redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
				} 

		}

		public function eliminar_externo(){
			$datos = $this->input->post();
			$idReturn = $datos["idHojaReturn"];
			unset($datos["idHojaReturn"]);
			// Ejecutando consultas
				$bool = $this->Hoja_Model->eliminarExterno($datos);
				if($bool){
					$this->session->set_flashdata("exito","Los datos del servicio externo fueron eliminados con exito!");
					redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
				}else{
					$this->session->set_flashdata("error","Error al eliminar los datos del servicio externo!");
					redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
				}
		}

		// Gestionando estado de hoja
		public function cerrar_hoja(){
			$datos = $this->input->post();
			// Datos para bitacora -Cerrar cuenta
				$bitacora["idCuenta"] = $datos["idHoja"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "El usuario ha cerrado la cuenta";
			// Fin datos para bitacora -Cerrar cuenta
			
			if($datos["correlativoSalidaHoja"] == 0){
				$correlativo = $this->Hoja_Model->numeroCorrelativo();
				$numeroCorrelativo = $correlativo->correlativo+1;
			}
			unset($datos["correlativoSalidaHoja"]);

			$idReturn = $datos["idHoja"];
			$bool = $this->Hoja_Model->cerrarHoja($datos, $numeroCorrelativo);
			if($bool){
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","La hoja de cobro se cerro con exito!");
				redirect(base_url()."Hoja/detalle_hoja/".$idReturn."/");
			}else{
				$this->session->set_flashdata("error","Error al cerrar la hoja de cobro!");
				redirect(base_url()."Hoja/detalle_hoja/".$idReturn."/");
			}

		}

		public function restaurar_hoja(){
			$datos = $this->input->post();
			$recibo = $this->Hoja_Model->validarReciboGenerado($datos["idHoja"]);
			$idReturn = $datos["idHoja"];

			if($recibo->recibo == 0){
				// Datos para bitacora -Restaurar cuenta
					$bitacora["idCuenta"] = $datos["idHoja"];
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "El usuario abrio nuevamente la cuenta";
				// Fin datos para bitacora -Restaurar cuenta
	
				$bool = $this->Hoja_Model->restaurarHoja($datos);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$this->session->set_flashdata("exito","La hoja de cobro se restauro con exito!");
					redirect(base_url()."Hoja/detalle_hoja/".$idReturn."/");
				}else{
					$this->session->set_flashdata("error","Error al restaurar la hoja de cobro!");
					redirect(base_url()."Hoja/detalle_hoja/".$idReturn."/");
				}
			}else{
				if($this->session->userdata('acceso_h') == 1){
					$bool = $this->Hoja_Model->restaurarHoja($datos);
					redirect(base_url()."Hoja/detalle_hoja/".$idReturn."/");
				}else{
					$this->session->set_flashdata("error","La hoja ya fue cobrada!");
					redirect(base_url()."Hoja/detalle_hoja/".$idReturn."/");
				}
				
			}

			// echo json_encode($recibo);

		}

		public function resumen_hoja($id){
			//Obteniendo datos ya existentes
			$totalGlobal = 0;
			$prevEgreso = "";
			$egreso = "";
			$prevIngreso = "";
			$ingreso = "";
			$paciente = $this->Hoja_Model->detalleHoja($id);
			$data["paciente"] = $paciente;
			if($this->session->userdata('global') == 1){
				$prevIngreso = "Fecha:";
				$ingreso = $paciente->fechaMostrar;
				$prevEgreso = "Seguro:";
				$egreso = $paciente->nombreSeguro;
			}else{
				$prevIngreso = "Fecha de Ingreso:";
				$ingreso = date("d/m/Y", strtotime($paciente->fechaHoja));
				$prevEgreso = "Fecha de Egreso:";
				if($paciente->salidaHoja == ""){
					$egreso = "0000-00-00";
				}else{
					$egreso = date("d/m/Y", strtotime($paciente->salidaHoja));
				}
			}
			$codigo = $this->session->userdata('global') == 1 ? $paciente->seguimientoHoja : $paciente->codigoHoja ;
			//$codigo = $paciente->credito_fiscal ;

			

			// Se visualizo el resumen
				$bitacora["idCuenta"] = $id;
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "El usuario visualizo el resumen de la cuenta";
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
			// Se visualizo el resumen

			// Detalles de la hoja
				$filaExtra = "";
				$data["externosHoja"] = $this->Hoja_Model->externosHoja($id);
				$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosHojaResumen($id);

				$totalMedicamentos = 0;
				$totalDescuento = 0;
				foreach ($data["medicamentosHoja"] as $medicamento) {
					$totalMedicamentos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
					$totalDescuento += $medicamento->descuentoUnitario;
				}
				$totalExternos = 0;
				foreach ($data["externosHoja"] as $externo) {
					$totalExternos += $externo->cantidadExterno * $externo->precioExterno;
				}

				if($paciente->procedimientoHoja != ''){
					$filaExtra .= '<tr>';
					$filaExtra .= '		<td><strong>PROCEDIMIENTO: </strong></td>';
					$filaExtra .= '		<td colspan="3" style="text-transform: uppercase">'.$paciente->procedimientoHoja.'</td>';
					$filaExtra .= '		<td></td>';
					$filaExtra .= '		<td></td>';
					$filaExtra .= '</tr>';
				}else{
					$filaExtra .= '';
				}
				if($paciente->descuentoHoja != null){
					$totalGlobal = (($totalExternos + $totalMedicamentos) - $paciente->descuentoHoja);
				}else{
					$totalGlobal = ($totalExternos + $totalMedicamentos);
				}
			//Fin

			$data["descuentoTotal"] = $totalDescuento;
			$data["totalGlobal"] = $totalGlobal;
			// Fin detalle hoja
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 70,
				'margin_bottom' => 40,
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
			
			$mpdf->SetHTMLHeader('	
				<div class="cabecera" style="font-family: Times New Roman">
					<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
					<div class="title_cabecera">
						<h4>HOSPITAL ORELLANA, USULUTAN</h4>
						<h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX: 2606-6673</h5>
					</div>
					
					<div class="subtitle_cabecera">
						<h5>DETALLE DE HOJA DE COBRO: '.$codigo.'</h5>
					</div>
				</div>
				<div class="paciente">
					<table class="tabla_paciente" style="font-family: Times New Roman;">

					<tr>
							<td><strong>NOMBRE DEL PACIENTE: </strong></td>
							<td class="letraMayuscula" colspan="3">'.$paciente->apellidoPaciente." ".$paciente->nombrePaciente.'</td>
							<td><strong>'.$prevIngreso.'</strong></td>
							<td>'.$ingreso.'</td>
						</tr>

						<tr>
							<td><strong>DUI: </strong></td>
							<td colspan="3">'.strtoupper($paciente->duiPaciente).'</td>
							<td><strong>'.$prevEgreso.'</strong></td>
							<td>'.$egreso.'</td>
						</tr>

						<tr>
							<td><strong>MEDICO: </strong></td>
							<td colspan="3" class="letraMayuscula">'.$paciente->nombreMedico.'</td>
							<td><strong>Edad:</strong></td>
							<td>'.$paciente->edadPaciente." AÑOS".'</td>
						</tr>
						'.$filaExtra.'
					</table>
				</div>
			');
						
			// FOOTER 
			$mpdf->SetHTMLFooter('
				<div class="numeracion" style="font-family: Times New Roman">
					<div class="numeracion_izquierda"></div>
					<div class="numeracion_derecha"><strong></strong></div>
				</div>
			');

			// $mpdf->SetHTMLFooter('');
			$html = $this->load->view('Hoja/resumen_hoja', $data ,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html);
			
			if(sizeof($data["externosHoja"]) > 0 && $this->session->userdata("global") == 3){
				$mpdf->AddPage();
				$html2 = $this->load->view('Hoja/resumen_externos', $data ,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html2);
			}

			$mpdf->Output('resumen_hoja_cobro.pdf', 'I');
			// echo json_encode($data);
		}

		public function recibo_hoja($id){
			$correlativoActual = $this->Hoja_Model->existenciaCorrelativo($id); // $id es el id de la hoja de cobro
			if($correlativoActual->correlativoSalidaHoja == 0){
				$maximoCorrelativo = $this->Hoja_Model->numeroCorrelativo();
				$numeroCorrelativo = $maximoCorrelativo->correlativo+1;

				// Validando caja
					$nuevoRCaja = 0;
					$reciboCaja = $this->Hoja_Model->obtenerCorrelativoCaja($this->session->userdata("idCaja"));
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

						$this->Hoja_Model->insertarReciboXCaja($caja);
				// Validando caja

				
				// Actualizando el numero de correlativo
					$this->Hoja_Model->actualizarCorrelativo($id, $numeroCorrelativo);
				// Actualizando el numero de correlativo

				// Datos para bitacora -Anular externo cuenta
					$bitacora["idCuenta"] = $id ;
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "Creo el recibo número: #".$numeroCorrelativo;
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				// Fin datos para bitacora -Anular externo cuenta

				// Calculando honorarios para cuando es paquete
					$paquete = $this->Hoja_Model->validarPaquete($id);
					if($paquete->esPaquete == 1){
						$datoCalculado = $paquete->totalPaquete - ($paquete->medicamentos + $paquete->externos);
						if($datoCalculado > 0){
							$honorario["idHoja"] = $paquete->idHoja;
							$honorario["idMedico"] = $paquete->idMedico;
							$honorario["honorario"] = $datoCalculado;
							$honorario["original"] = $datoCalculado;
							$this->Hoja_Model->guardarHonorarioPaquete($honorario);
						}
					}
				// Calculando honorarios para cuando es paquete

				//Guardar el correlativo para control de cajeras
					$controlCaja["usuario"] = $this->session->userdata('id_usuario_h');
					$controlCaja["hoja"] = $id;
					$controlCaja["correlativo"] = $numeroCorrelativo;
					$controlCaja["fecha"] = date("Y-m-d");
					$this->Hoja_Model->agregarAControlCajeras($controlCaja);
				//Guardar el correlativo para control de cajeras

			}

			
			//Obteniendo datos ya existentes
			$paciente = $this->Hoja_Model->detalleHoja($id);
			$data["paciente"] = $paciente;
			$strCaja = "";
			
			// Detalles de la hoja
				$data["externosHoja"] = $this->Hoja_Model->externosHoja($id);
				$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosHojaResumen($id);

				if(!is_null($paciente->nombreCaja)){
					$strCaja = "CAJA: ".$paciente->nombreCaja.'-'.$paciente->codigoReciboCaja;
				}else{
					$strCaja = "";
				}

				
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 70,
					'margin_bottom' => 78,
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
				$html = $this->load->view('Hoja/recibo_hoja', $data ,true); // Cargando hoja de estilos
				$mpdf->SetHTMLHeader('
					<div class="cabecera" style="font-family: Times New Roman">
						<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
						<div class="title_cabecera">
							<h4>HOSPITAL ORELLANA, USULUTAN</h4>
							<h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5>
						</div>
						
						<div class="subtitle_cabecera">
							<table class="tabla_num_recibo" style="font-family: Times New Roman;">
								<tr>
									<td><h5>HOJA DE COBRO: '.$paciente->codigoHoja.'</h5></td>
									<td><h5>RECIBO DE COBRO: '.$paciente->correlativoSalidaHoja.'</h5></td>
									<td><h5>'.$strCaja.'</h5></td>
								</tr>
							</table>

						</div>
					</div>
					<div class="paciente">
						<table class="tabla_paciente" style="font-family: Times New Roman;">

						<tr>
								<td><strong>NOMBRE DEL PACIENTE: </strong></td>
								<td class="letraMayuscula" colspan="3">'.$paciente->apellidoPaciente." ".$paciente->nombrePaciente.'</td>
								<td><strong>Fecha de Ingreso:</strong></td>
								<td>'.date("d/m/Y", strtotime($paciente->fechaHoja)).'</td>
							</tr>

							<tr>
								<td><strong>DUI: </strong></td>
								<td colspan="3">'.$paciente->duiPaciente.'</td>
								<td><strong>Fecha de Egreso:</strong></td>
								<td>'.date("d/m/Y", strtotime($paciente->salidaHoja)).'</td>
							</tr>

							<tr>
								<td><strong>MEDICO: </strong></td>
								<td colspan="3" class="letraMayuscula">'.$paciente->nombreMedico.'</td>
								<td><strong>Edad:</strong></td>
								<td>'.$paciente->edadPaciente." AÑOS".'</td>
							</tr>

						</table>
					</div>
				');
				
				$mpdf->WriteHTML($html);
				$mpdf->Output('recibo_cobro.pdf', 'I');
			
			// Fin detalle hoja
			

			// echo json_encode($correlativoActual);
		}
		
		public function actualizar_hoja(){
			$datos = $this->input->post();
			$destino = $datos["destinoHoja"];
			$pivote = $datos["procedimientoHoja"];
			unset($datos["procedimientoHoja"]);
			$procedimiento = $this->Quirofanos_Model->procedimientoHoja($datos["idHoja"]); // La hoja tiene un procedimiento asignado

			// Ejecutando consultas
			$bool = $this->Hoja_Model->actualizarDetalleHoja($datos);
			if($bool){
				if($destino == 7){
					if(!$procedimiento){
						$hoja = $this->Quirofanos_Model->datosHoja($datos["idHoja"]);
						$procedimiento["hoja"] = $hoja->idHoja;
						$procedimiento["paciente"] = $hoja->idPaciente;
						$procedimiento["procedimiento"] = $hoja->procedimientoHoja;
						$procedimiento["fecha"] = date('Y-m-d');
						$procedimiento["hora"] = date('h:i:s A');
						$this->Hoja_Model->guardarSala($procedimiento);
					}
				}else{
					if($procedimiento){
						$this->Quirofanos_Model->eliminarProcedimiento($procedimiento->idProcedimiento);
					}
				}
				$this->session->set_flashdata("exito","Los datos de la hoja fueron actualizados con exito!");
				redirect(base_url()."Hoja/lista_hojas/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos de la hoja de cobro!");
				redirect(base_url()."Hoja/lista_hojas/");
			}
			
			// echo json_encode($datos);
			
		}

		public function actualizar_paciente_hoja(){
			$datos = $this->input->post();
			$paciente["idPaciente"] = $datos["idPacienteU"];
			$paciente["idHoja"] = $datos["idHoja"];
			// Ejecutando consultas
			$resp = $this->Hoja_Model->actualizarPacienteHoja($paciente);
			if($resp > 0){
				$this->session->set_flashdata("exito","Los datos de la hoja fueron actualizados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/".$resp."/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos de la hoja de cobro!");
				redirect(base_url()."Hoja/lista_hojas/");
			}

			// echo json_encode($paciente);
		}

	// Seccion para presupuesto

		public function presupuesto(){
			$medicos = $this->Medico_Model->obtenerMedicos();
			$pacientes = $this->Paciente_Model->obtenerPacientes();
			$codigo = $this->Hoja_Model->codigoPresupuesto(); // Ultimo codigo de hoja
			$cod = 0;
			if($codigo->codigoPresupuesto == NULL ){
				$cod = 1000000;
			}else{
				$cod = $codigo->codigoPresupuesto + 1;
			}
			$data = array('medicos' => $medicos, 'pacientes' => $pacientes, 'codigo' => $cod);
			$this->load->view("Base/header");
			$this->load->view("Presupuesto/agregar_presupuesto", $data);
			$this->load->view("Base/footer");

		}

		public function guardar_presupuesto(){
			$datos = $this->input->post();
			$resp = $this->Hoja_Model->guardarPresupuesto($datos);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos del presupuesto fueron guardados con exito!");
				redirect(base_url()."Hoja/detalle_presupuesto/$resp/");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
				redirect(base_url()."Hoja/");
			}
		}

		public function detalle_presupuesto($id){
			$data["presupuesto"] = $this->Hoja_Model->detallePresupuesto($id);
			$data["medicamentos"] = $this->Botiquin_Model->obtenerMedicamentos();
			$data["externos"] = $this->Externos_Model->obtenerExternos();
				
			// Detalles del presupuesto
				$data["externosHoja"] = $this->Hoja_Model->externosPresupuesto($id);
				$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosPresupuesto($id);
			// Detalles del presupuesto
			
			$this->load->view("Base/header");
			$this->load->view("Presupuesto/detalle_presupuesto", $data);
			$this->load->view("Base/footer");
			
		}

		public function guardar_detalle_presupuesto(){
			$datos = $this->input->post();
			/* $idReturn = $datos["idHoja"];
			$bool = $this->Hoja_Model->guardarDetallePresupuesto($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos del presupuesto fueron guardados con exito!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn");
			}else{
				$this->session->set_flashdata("error","Error al guardar los datos del presupuesto!");
				redirect(base_url()."Hoja/");
			} */

			var_dump($datos);

		
		}

		public function actualizar_medicamento_presupuesto(){
			$datos = $this->input->post();
			$idReturn = $datos["idHojaReturn"];
			unset($datos["idHojaReturn"]);
			// Ejecutando consultas
			$bool = $this->Hoja_Model->actualizarMedicamentoPresupuesto($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos del presupuesto fueron actualizados con exito!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos del presupuesto!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn/");
			}

		}

		/* public function eliminar_medicamento_presupuesto(){
			$datos = $this->input->post();
			$idReturn = $datos["idHojaReturn"];
			unset($datos["idHojaReturn"]);
			// Ejecutando consultas
			$bool = $this->Hoja_Model->eliminarMedicamentoPresupuesto($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos del presupuesto fueron eliminados con exito!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn");
			}else{
				$this->session->set_flashdata("error","Error al eliminar los datos del presupuesto!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn");
			}


		} */
		

		public function actualizar_externo_presupuesto(){
			$datos = $this->input->post();
			$idReturn = $datos["idHojaReturn"];
			unset($datos["idHojaReturn"]);
			// Ejecutando consultas
			$bool = $this->Hoja_Model->actualizarExternoPresupuesto($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos del presupuesto fueron actualizados con exito!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos del presupuesto!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn/");
			}
		}
		
		public function eliminar_externo_presupuesto(){
			$datos = $this->input->post();
			$idReturn = $datos["idHojaReturn"];
			unset($datos["idHojaReturn"]);
			// Ejecutando consultas
			$bool = $this->Hoja_Model->eliminarExternoPresupuesto($datos);
			if($bool){
				$this->session->set_flashdata("exito","Los datos del presupuesto fueron eliminados con exito!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn/");
			}else{
				$this->session->set_flashdata("error","Error al eliminar los datos del presupuesto!");
				redirect(base_url()."Hoja/detalle_presupuesto/$idReturn/");
			}

		}

		public function resumen_presupuesto($id){

			//Obteniendo datos ya existentes
			$presupuesto = $this->Hoja_Model->detallePresupuesto($id);		
			// Detalles de la hoja
			$data["externosHoja"] = $this->Hoja_Model->externosPresupuesto($id);
			$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosPresupuesto($id);

				$totalMedicamentos = 0;
					foreach ($data["medicamentosHoja"] as $medicamento) {
							$totalMedicamentos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
						}
				$totalExternos = 0;
					foreach ($data["externosHoja"] as $externo) {
						$totalExternos += $externo->cantidadExterno * $externo->precioExterno;
					}
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf = new \Mpdf\Mpdf([
				'margin_left' => 15,
				'margin_right' => 15,
				'margin_top' => 70,
				'margin_bottom' => 78,
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
			$html = $this->load->view('Hoja/resumen_hoja', $data ,true); // Cargando hoja de estilos
			$mpdf->SetHTMLHeader('
				<div class="cabecera" style="font-family: Times New Roman">
					<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
					<div class="title_cabecera">
						<h4>HOSPITAL ORELLANA, USULUTAN</h4>
						<h5>Sexta calle oriente, #8, Usulután, El Salvador</h5>
					</div>
					
					<div class="subtitle_cabecera">
						<h5>DETALLE DEL PRESUPUESTO: '.$presupuesto->codigoPresupuesto.'</h5>
					</div>
				</div>
				<div class="paciente">
					<table class="tabla_paciente" style="font-family: Times New Roman;">

						<tr>
							<td><strong>NOMBRE DEL PACIENTE: </strong></td>
							<td class="letraMayuscula" colspan="3">'.$presupuesto->pacientePresupuesto.'</td>
							<td><strong>FECHA:</strong></td>
							<td>'.date("d/m/Y", strtotime($presupuesto->fechaPresupuesto)).'</td>
						</tr>

						<tr>
							<td><strong>MEDICO: </strong></td>
							<td class="letraMayuscula" colspan="3">'.$presupuesto->nombreMedico.'</td>
							<td><strong>TIPO:</strong></td>
							<td>'.$presupuesto->tipoPresupuesto.'</td>
						</tr>

					</table>
				</div>
			');
			
					
			// FOOTER MALO EN TEORIA
			$mpdf->SetHTMLFooter('
				<div class="numeracion" style="font-family: Times New Roman">
					<div class="numeracion_izquierda"><strong>Pagina {PAGENO}/{nb}</strong></div>
					<div class="numeracion_derecha"><strong>Total hoja $'.number_format(($totalExternos + $totalMedicamentos), 2).'</strong></div>
				</div>
			');
			$mpdf->WriteHTML($html);
			$mpdf->Output('resumen_cotizacion.pdf', 'I');
			

		}

		public function hoja_presupuesto($id){

			$medicos = $this->Medico_Model->obtenerMedicos();
			$pacientes = $this->Paciente_Model->obtenerPacientes();
			$habitaciones = $this->Paciente_Model->obtenerHabitaciones();
			$codigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
			$cod = 0;
				if($codigo->codigoHoja == NULL ){
					$cod = 1000000;
				}else{
					$cod = $codigo->codigoHoja +1;
				}
			
			$data = array('medicos' => $medicos, 'pacientes' => $pacientes, 'codigo' => $cod, 'habitaciones' => $habitaciones);

			// Detalles de la hoja
				$data["externosHoja"] = $this->Hoja_Model->externosPresupuesto($id);
				$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosPresupuesto($id);
			
			$this->load->view("Base/header");
			$this->load->view("Presupuesto/hoja_presupuesto", $data);
			$this->load->view("Base/footer");
			
		}

		public function lista_presupuestos(){
			$this->load->view('Base/header');
			$data["cotizaciones"] = $this->Hoja_Model->obtenerPresupuestos();
			$this->load->view('Presupuesto/lista_presupuestos', $data);
			$this->load->view('Base/footer');
		}

		// Metodos para seleccionar el nuevo medicamento en hoja de cobro
		// Obteniendo DUI de paciente para validad
		public function obtener_medicamento(){
			if($this->input->is_ajax_request()){
				$id =$this->input->get("id");
				$data = $this->Hoja_Model->obtenerMedicamento(trim($id));
				echo json_encode($data);
			}
			else{
				echo "Error...";
			}
		}
	
	// Conectandose a otra DB
		/* public function test(){
			$test = $this->Hoja_Model->test();
			foreach ($test as $t) {
				echo $t->idSenso."<br>";
			}
		} */

		/* public function test(){
			
			$test = $this->Hoja_Model->test();
			$vari = '<a href="'.base_url().'Empleados/ViewInsertarEmpleados">Nuevo empleado</a>';
			echo $vari;
			foreach ($test as $t) {
				$a = base_url();
				//echo $t->html."<br>";
				echo str_replace("http://localhost/fast-cash/", "$a", $t->html);
			}
		} */

	// Obteniendo DUI de paciente para validad
	public function validar_paciente(){
		if($this->input->is_ajax_request()){
			$dui =$this->input->post("dui");
			$data = $this->Paciente_Model->validadPaciente(trim($dui));
			if(!is_null($data) > 0){
				$fecha1 = new DateTime($data->nacimientoPaciente);
				$fecha2 = new DateTime(date('Y-m-d'));
				$diferencia = $fecha1->diff($fecha2);
				$data->edadPaciente = $diferencia->y;

				$respuesta = array('estado' => 1, 'respuesta' => 'Exito', 'datos' => $data);
				header("content-type:application/json");
				print json_encode($respuesta);
			}else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error', 'datos' => null );
				header("content-type:application/json");
				print json_encode($respuesta);
			}
		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error', 'datos' => null );
			header("content-type:application/json");
			print json_encode($respuesta);
		}
	}

	public function validar_hoja(){
		if($this->input->is_ajax_request()){
			$id =$this->input->get("id");
			$data = $this->Paciente_Model->validadHoja(trim($id));
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	public function validar_existencia_hoja(){
		if($this->input->is_ajax_request()){
			$id =$this->input->get("id");
			$data = $this->Paciente_Model->validadExistenciaHoja(trim($id));
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
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

	public function eliminar_medicamento_hoja(){
		if($this->input->is_ajax_request()){
			$datos = $this->input->post();
			$data["motivo"] = $datos["motivo"];
			$data["insumo"] = $datos["idHojaInsumo"];
			
			// Datos para bitacora -Restaurar cuenta
				$nombreMedicamento = $this->Usuarios_Model->nombreMedicamento($this->input->post("idMedicamento"));
				$bitacora["idCuenta"] = $this->input->post("hoja");
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Elimino ".$this->input->post("cantidadMedicamento")." elementos del medicamento ".$nombreMedicamento->nombre.", motivo: ".$data["motivo"];
			// Fin datos para bitacora -Restaurar cuenta

			// Ejecutando consultas
				$bool = $this->Hoja_Model->eliminarMedicamento($datos["idHojaInsumo"]);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
					header("content-type:application/json");
					print json_encode($respuesta);
				}else{
					$respuesta = array('estado' => 0, 'respuesta' => 'Error');
					header("content-type:application/json");
					print json_encode($respuesta);
				} 
			// echo json_encode($data);

		}
		else{
			$respuesta = array('estado' => 0, 'respuesta' => 'Error');
			header("content-type:application/json");
			print json_encode($respuesta);
		}

	}

	// Agregando medicamento asyncronamente
		public function agregar_medicamento_hoja(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				$medicamento["idHoja"] = $datos["idHoja"];
				$medicamento["idMedicamento"] = $datos["id"];
				$medicamento["precioMedicamento"] = $datos["precioV"];
				$medicamento["cantidadMedicamento"] = $datos["cantidad"];
				$medicamento["fechaHoja"] = $datos["fechaHoja"];
				$medicamento["por"] = $this->session->userdata('id_usuario_h');
				
				$medicamento["cantidadBase"] = $datos["cantidadBase"];
				$medicamento["cantidadMG"] = $datos["cantidadMG"];
				$medicamento["nombreMedida"] = $datos["nombreMedida"];

				
				// echo json_encode($medicamento);
				// Datos para bitacora -Restaurar cuenta
					$nombreMedicamento = $this->Usuarios_Model->nombreMedicamento($datos["id"]);
					$bitacora["idCuenta"] = $datos["idHoja"];
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "Agrego ".$datos["cantidad"]." ".$datos["nombreMedida"]." de ".$nombreMedicamento->nombre.", con precio de $".$datos["precioV"];;
				// Fin datos para bitacora -Restaurar cuenta

				// Ejecutando consultas
				$bool = $this->Hoja_Model->guardarDetalleHojaUnitario($medicamento);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
					header("content-type:application/json");
					print json_encode($respuesta);
				}else{
					$respuesta = array('estado' => 0, 'respuesta' => 'Error');
					header("content-type:application/json");
					print json_encode($respuesta);
				}

				// print json_encode($medicamento);

			} 
			else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
					header("content-type:application/json");
					print json_encode($respuesta);
			}
		}

		public function agregar_externo_hoja(){
			if($this->input->is_ajax_request()){
				$externo["idHoja"] = $this->input->post("idHoja");
				$externo["fechaHoja"] = $this->input->post("fechaHoja");
				$externo["idExterno"] = $this->input->post("id");
				$externo["precioExterno"] = $this->input->post("precio");
				$externo["cantidadExterno"] = 1;

				// Datos para bitacora -Restaurar cuenta
					$nombreExterno = $this->Usuarios_Model->nombreExterno($this->input->post("id"));
					$bitacora["idCuenta"] = $this->input->post("idHoja");
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "Agrego al externo ".$nombreExterno->nombre." con un valor de $".$this->input->post("precio");
				// Fin datos para bitacora -Restaurar cuenta

				// Ejecutando consultas
				$bool = $this->Hoja_Model->guardarDetalleHojaUnitario2($externo);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
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

		public function eliminar_externo_hoja(){
			if($this->input->is_ajax_request()){
				$externo["idHojaExterno"] = $this->input->post("idExterno");
				//$externo["idHojaReturn"] = $this->input->post("idHoja");
				// Datos para bitacora -Eliminar externo cuenta
					$datosExterno = $this->Usuarios_Model->datosExterno($this->input->post("idExterno"));
					$bitacora["idCuenta"] = $datosExterno->idHoja;
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "Elimino al externo ".$datosExterno->nombreExterno." con un valor de $".$datosExterno->precioExterno;
				// Fin datos para bitacora -Eliminar externo cuenta
				// Ejecutando consultas
					$bool = $this->Hoja_Model->eliminarExterno($externo);
					if($bool){
						$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
						echo "GOOD";
					}else{
						echo "ERROR";
					}
				// Fin consultas 
			}
			else{
				echo "Error...";
			}

		}

		public function agregar_medicamento_presupuesto(){
			if($this->input->is_ajax_request()){
				$medicamento["idHoja"] = $this->input->post("idPresupuesto");
				$medicamento["fechaHoja"] = $this->input->post("fechaPresupuesto");
				$medicamento["idMedicamento"] = $this->input->post("id");
				$medicamento["precioMedicamento"] = $this->input->post("precioV");
				$medicamento["cantidadMedicamento"] = $this->input->post("cantidad");
				$medicamento["stockMedicamento"] = $this->input->post("stock");
				$medicamento["usadosMedicamento"] = $this->input->post("usados");
				$medicamento["totalUMedicamento"] = ($this->input->post("cantidad") * $this->input->post("precioV"));

				// Ejecutando consultas
				$bool = $this->Hoja_Model->guardarDetallePresupuesto($medicamento);
				if($bool){
					echo "GOOD";
				}else{
					echo "ERROR";
				}

			}
			else{
				echo "Error...";
			}
		}

		public function eliminar_medicamento_presupuesto(){
			if($this->input->is_ajax_request()){
				// Datos para eliminar medicamento 
					$medicamento["idMedicamento"] = $this->input->post("idMedicamento");
				
				// Ejecutando consultas
					$bool = $this->Hoja_Model->eliminarMedicamentoPresupuesto($medicamento);
					if($bool){
						echo "GOOD";
					}else{
						echo "ERROR";
					}  
			}
			else{
				echo "Error...";
			}

		}

		public function agregar_externo_presupuesto(){
			if($this->input->is_ajax_request()){
				$externo["idHoja"] = $this->input->post("idPresupuesto");
				$externo["fechaHoja"] = $this->input->post("fecha");
				$externo["idExterno"] = $this->input->post("id");
				$externo["precioExterno"] = $this->input->post("precio");
				$externo["cantidadExterno"] = 1;

				// Ejecutando consultas
				$bool = $this->Hoja_Model->guardarExternoPresupuesto($externo);
				if($bool){
					echo "GOOD";
				}else{
					echo "ERROR";
				}

			}
			else{
				echo "Error...";
			}
		}

		public function eliminar_externos_presupuesto(){
			if($this->input->is_ajax_request()){
				// Datos para eliminar medicamento 
					$externo["idExterno"] = $this->input->post("idExterno");
				
				// Ejecutando consultas
					$bool = $this->Hoja_Model->eliminarExternoPresupuesto($externo);
					if($bool){
						echo "GOOD";
					}else{
						echo "ERROR";
					}  
			}
			else{
				echo "Error...";
			}

		}

	// Guardando numeros de factura
		public function guardar_consumidor_final(){
			$datos = $this->input->post();
			$pivoteFacturaRecibo = $datos["pivoteFacturaRecibo"];
			$factura["idHoja"] = $datos["idHojaCobro"];
			$factura["numeroFactura"] = $datos["numeroFactura"];
			$factura["totalFactura"] = $datos["totalFactura"];
			$factura["fechaFactura"] = $datos["fechaFactura"];
			$idReturn = $datos ["idHojaCobro"];
			unset($datos["totalFactura"]);
			unset($datos["pivoteFacturaRecibo"]);

			// Para bitacora
				$bitacora["idCuenta"] = $factura["idHoja"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Agrego la factura #".$factura["numeroFactura"];
			// Para bitacora

			$bool = $this->Hoja_Model->guardarConsumidorFinal($datos);
			if($bool){
				// $base_url2 = 'http://192.168.1.92/sirius/';

				$ip = $_SERVER['REMOTE_ADDR']; // IP de la mquina actual
				$base_url2 = "http://".$ip."/"."sirius/";
				$this->Hoja_Model->guardarFactura($factura);
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","Los datos de la factura fueron guardados con exito!");
				// redirect(base_url()."Hoja/recibo_factura/$idReturn/".$pivoteFacturaRecibo."/");
				redirect($base_url2."Home/recibo_factura/$idReturn/".$pivoteFacturaRecibo."/");

			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la factura!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}
			
		}

		public function guardar_numero_cf(){
			$datos = $this->input->post();
			$pivoteFacturaRecibo = $datos["pivoteFacturaRecibo"];
			$factura["idHoja"] = $datos["idHojaCobro"];
			$factura["numeroFactura"] = $datos["numeroFactura"];
			$factura["totalFactura"] = $datos["totalFactura"];
			$factura["fechaFactura"] = $datos["fechaFactura"];
			$idReturn = $datos ["idHojaCobro"];
			unset($datos["totalFactura"]);
			unset($datos["pivoteFacturaRecibo"]);

			// Para bitacora
				$bitacora["idCuenta"] = $factura["idHoja"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Agrego la factura #".$factura["numeroFactura"];
			// Para bitacora

			$bool = $this->Hoja_Model->guardarConsumidorFinal($datos);
			if($bool){
				$this->Hoja_Model->guardarFactura($factura);
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","Los datos de la factura fueron guardados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");

			}else{
				$this->session->set_flashdata("error","Error al guardar los datos de la factura!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}
			
		}

		public function recibo_factura($hoja = null, $pivote = null){
			try {
				$factura = $this->Hoja_Model->reciboFactura($hoja);
				$connector = new WindowsPrintConnector("Tally Dascom 1330");
				$printer = new Printer($connector);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				
	
				// Encabezados de la tabla
				$headers = [" ", " ", " ", " ", " "];
				$dui = str_pad(" ", 3, " ").str_pad($factura->duiFactura, 20, " ");
				$fecha = str_pad(" ", 5, " ").str_pad($factura->fechaMostrar, 20, " ");
				// Filas de la tabla
				$rows = [
					[" ", $factura->clienteFactura, $fecha, " ", " "],
					[" ", $factura->direccionPaciente, " ", " ", " "],
					[" ", $dui, " ", " ", " "]
				];
	
				// Imprimir tabla
				$this->printTable($printer, $headers, $rows);
	
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$totalFactura = "$".number_format($factura->totalFactura, 2);
				$rows = [
					["1", "Medicamentos e insumos médicos", $totalFactura, " ", $totalFactura],
				];
				$this->printTable($printer, $headers, $rows);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);
				$printer->feed(1);

				$numero = $factura->totalFactura;
				$arregloNumero = explode(".", $numero);
				$letras = "";
				if(isset($arregloNumero[1])){
					$letras = strtoupper($this->convertir($arregloNumero[0])." ".$arregloNumero[1]."/100");
				}else{
					$letras = strtoupper($this->convertir($numero)." 00/100 Dolares"); 
				}

				$rows = [
					["", $letras, " ", " ", $totalFactura],
				];
				$this->printTable($printer, $headers, $rows);
				$printer->feed(1);

				if($factura->totalFactura >= 200){
					$cliente = str_pad(" ", 6, " ").str_pad($factura->clienteFactura, 6, " ").str_pad("", 20, " ");
					$documento = str_pad(" ", 9, " ").str_pad($factura->duiFactura, 20, " ");
				}else{
					$cliente = str_pad(" ", 6, " ").str_pad(" ", 6, " ").str_pad("", 20, " ");
					$documento = str_pad(" ", 9, " ").str_pad(" ", 20, " ");
				}
	
				$rows = [
					["", $cliente, " ", " ", " "],
					["", $documento, " ", " ", " "],
				];
				$this->printTable($printer, $headers, $rows);
				// $printer->feed(1);
				$rows = [
					["", " ", " ", " ", $totalFactura],
				];
				$this->printTable($printer, $headers, $rows);
				
				
				// $printer->cut();
				$printer->close();
				if($pivote != 0){
					redirect(base_url()."Hoja/recibo_hoja/$hoja/");
				}
			} catch (Exception $e) {
				echo "No se pudo imprimir: " . $e->getMessage() . "\n";
			}


		}
		
		public function consumidor_final_pdf($id){
			$data = array();
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 70,
					'margin_bottom' => 78,
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
				$html = $this->load->view('Hoja/consumidor_final_pdf', $data ,true); // Cargando hoja de estilos
				/* $mpdf->SetHTMLHeader('
					<div class="cabecera" style="font-family: Times New Roman">
						<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
						<div class="title_cabecera">
							<h4>HOSPITAL ORELLANA, USULUTAN</h4>
							<h5>Sexta calle oriente, #8, Usulután, El Salvador</h5>
						</div>
						
						<div class="subtitle_cabecera">
							<h5>DETALLE DEL PRESUPUESTO: '.$presupuesto->codigoPresupuesto.'</h5>
						</div>
					</div>
					<div class="paciente">
						<table class="tabla_paciente" style="font-family: Times New Roman;">

							<tr>
								<td><strong>NOMBRE DEL PACIENTE: </strong></td>
								<td class="letraMayuscula" colspan="3">'.$presupuesto->pacientePresupuesto.'</td>
								<td><strong>FECHA:</strong></td>
								<td>'.date("d/m/Y", strtotime($presupuesto->fechaPresupuesto)).'</td>
							</tr>

							<tr>
								<td><strong>MEDICO: </strong></td>
								<td class="letraMayuscula" colspan="3">'.$presupuesto->nombreMedico.'</td>
								<td><strong>TIPO:</strong></td>
								<td>'.$presupuesto->tipoPresupuesto.'</td>
							</tr>

						</table>
					</div>
				'); */
				
						
				// FOOTER MALO EN TEORIA
				/* $mpdf->SetHTMLFooter('
					<div class="numeracion" style="font-family: Times New Roman">
						<div class="numeracion_izquierda"><strong>Pagina {PAGENO}/{nb}</strong></div>
						<div class="numeracion_derecha"><strong>Total hoja $'.number_format(($totalExternos + $totalMedicamentos), 2).'</strong></div>
					</div>
				'); */
				$mpdf->WriteHTML($html);
				$mpdf->Output('resumen_cotizacion.pdf', 'I');
				
		}

		public function credito_fiscal_pdf($id){
			$data = array();
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 70,
					'margin_bottom' => 78,
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
				$html = $this->load->view('Hoja/credito_fiscal_pdf', $data ,true); // Cargando hoja de estilos
				/* $mpdf->SetHTMLHeader('
					<div class="cabecera" style="font-family: Times New Roman">
						<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
						<div class="title_cabecera">
							<h4>HOSPITAL ORELLANA, USULUTAN</h4>
							<h5>Sexta calle oriente, #8, Usulután, El Salvador</h5>
						</div>
						
						<div class="subtitle_cabecera">
							<h5>DETALLE DEL PRESUPUESTO: '.$presupuesto->codigoPresupuesto.'</h5>
						</div>
					</div>
					<div class="paciente">
						<table class="tabla_paciente" style="font-family: Times New Roman;">

							<tr>
								<td><strong>NOMBRE DEL PACIENTE: </strong></td>
								<td class="letraMayuscula" colspan="3">'.$presupuesto->pacientePresupuesto.'</td>
								<td><strong>FECHA:</strong></td>
								<td>'.date("d/m/Y", strtotime($presupuesto->fechaPresupuesto)).'</td>
							</tr>

							<tr>
								<td><strong>MEDICO: </strong></td>
								<td class="letraMayuscula" colspan="3">'.$presupuesto->nombreMedico.'</td>
								<td><strong>TIPO:</strong></td>
								<td>'.$presupuesto->tipoPresupuesto.'</td>
							</tr>

						</table>
					</div>
				'); */
				
						
				// FOOTER MALO EN TEORIA
				/* $mpdf->SetHTMLFooter('
					<div class="numeracion" style="font-family: Times New Roman">
						<div class="numeracion_izquierda"><strong>Pagina {PAGENO}/{nb}</strong></div>
						<div class="numeracion_derecha"><strong>Total hoja $'.number_format(($totalExternos + $totalMedicamentos), 2).'</strong></div>
					</div>
				'); */
				$mpdf->WriteHTML($html);
				$mpdf->Output('	|.pdf', 'I');
				
		}
		
	// Funcion para anular hoja
		public function anular_hoja(){
			$datos = $this->input->post();

			
			$hoja = $datos["idHoja"];
			$hojaDetalle["estadoHoja"] = 0;
			$hojaDetalle["anulada"] = 1;

			$hojaDetalle["estadoHoja"] = 0;
			$hojaDetalle["anulada"] = 1;
			$hojaDetalle["motivo"] = $datos["motivoAnular"];
			$data["motivo"] = $datos["motivoAnular"];
			
			// Obteniendo medicamentos
			$medicamentosHoja = $this->Hoja_Model->medicamentosHojaResumen($hoja);
			$externosHoja = $this->Hoja_Model->externosHoja($hoja);
			$paciente = $this->Hoja_Model->detalleHoja($hoja);
			$index = 0;
			$detalle = '';
			$ti = 0;
			$te = 0;
			$t = 0;
			
			// Datos para bitacora -Anular externo cuenta
				$bitacora["idCuenta"] = $hoja ;
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Anulo la hoja de cobro, corespondiente al paciente ".$paciente->nombrePaciente.", motivo: ".$datos["motivoAnular"];
			// Fin datos para bitacora -Anular externo cuenta
			
			// Reintegrando valores al stock
			$detalle .= '<table id="" class="table table-striped thead-primary w-100">';
			$detalle .= '		<tr>';
			$detalle .= '			<th class="text-center" scope="col" colspan="4">Medicamentos</th>';
			$detalle .= '		</tr>';
			
			foreach ($medicamentosHoja as $medicamento) {
				$ti += $medicamento->precioInsumo*$medicamento->cantidadInsumo;
				$data["fila"] = $medicamento->idHojaInsumo;
				$detalle .= '		<tr>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">'.$medicamento->nombreMedicamento.'</td>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">'.$medicamento->cantidadInsumo.'</td>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">$'.$medicamento->precioInsumo.'</td>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">$'.round(($medicamento->precioInsumo*$medicamento->cantidadInsumo), 2).'</td>';
				$detalle .= '		</tr>';

				$this->Hoja_Model->eliminarMedicamento($data);
				//echo json_encode($data);
							
			}

			$detalle .= '		<tr>';
			$detalle .= '			<td class="text-center" scope="col" colspan="3">Interno</td>';
			$detalle .= '			<td class="text-center" scope="col">$'.round($ti, 2).'</td>';
			$detalle .= '		</tr>';

			$detalle .= '		<tr>';
			$detalle .= '			<td class="text-center" scope="col" colspan="4">Externos</td>';
			$detalle .= '		</tr>';
			foreach ($externosHoja as $externo) {
				$te += $externo->precioExterno*$externo->cantidadExterno;
				$data["fila"] = $externo->idHojaExterno;
				$detalle .= '		<tr>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">'.$externo->nombreExterno.'</td>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">'.$externo->cantidadExterno.'</td>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">$'.$externo->precioExterno.'</td>';
				$detalle .= '			<td class="text-center" scope="col" colspan="">$'.round(($externo->precioExterno*$externo->cantidadExterno), 2).'</td>';
				$detalle .= '		</tr>';
				$this->Hoja_Model->eliminarExterno($data);	
			}
			$detalle .= '		<tr>';
			$detalle .= '			<td class="text-center" scope="col" colspan="3">Externo</td>';
			$detalle .= '			<td class="text-center" scope="col">$'.round($te, 2).'</td>';
			$detalle .= '		</tr>';

			$detalle .= '		<tr>';
			$detalle .= '			<td class="text-center" scope="col" colspan="3">Total</td>';
			$detalle .= '			<td class="text-center" scope="col">$'.round(($ti + $te), 2).'</td>';
			$detalle .= '		</tr>';
			$detalle .= '</table>';
			
			$hojaDetalle["detalle"] = $detalle;
			$hojaDetalle["hoja"] = $datos["idHoja"];

			// Ejecutando consultas
			$bool = $this->Hoja_Model->anularHoja($hojaDetalle);
			if($bool){
				// $this->Hoja_Model->eliminarDetalleHoja($contenido);
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","La hoja fue anulada con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$hoja/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos del presupuesto!");
				redirect(base_url()."Hoja/detalle_hoja/$hoja/");
			}
			// echo json_encode($hojaDetalle);
			// echo $detalle;
		}

	// Funcion para buscar recomendaciones de pacientes
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
				$id =$this->input->get("id");
				$data = $this->Paciente_Model->buscarPaciente(trim($id));
				echo json_encode($data);
			}
			else{
				echo "Error...";
			}
		}
	// Fin recomendacion paciente

	// Metodos para recibo de paquetes
		public function lista_paquetes(){
			$data["medicos"] = $this->Medico_Model->obtenerMedicos();
			$data["paquetes"] = $this->Hoja_Model->obtenerPaquetes();

			$codigo = $this->Hoja_Model->codigoPaquete(); // Ultimo codigo de paquete
			$cod = 0;
			if($codigo->codigoPaquete == 0 ){
				$cod = 1000;
			}else{
				$cod = ($codigo->codigoPaquete + 1);
			}
			$data["codigo"] = $cod;
			$this->load->view("Base/header");
			$this->load->view("Hoja/lista_paquetes", $data);
			$this->load->view("Base/footer");
			// echo json_encode($data["paquetes"]);
		}

		public function guardar_paquete(){
			echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
			$datos = $this->input->post();
			$totalPaquete = $datos["totalPaquete"];
			unset($datos["totalPaquete"]);
			if($datos["idPaciente"] != 0){
				// Datos para el abono
					$codigo = "";
					$datosCodigo = $this->Hoja_Model->codigoPaquete();
					if($datosCodigo != null){
						$codigo = $datosCodigo->codigoPaquete + 1;
					}else{
						$codigo = "1000";
					}
					$data["codigoPaquete"] = $codigo;
					$data["pacientePaquete"] = $datos["pacientePaquete"];
					$data["medicoPaquete"] = $datos["medicoPaquete"];
					$data["cantidadPaquete"] = $datos["cantidadPaquete"];
					$data["conceptoPaquete"] = $datos["tipoPaquete"];
					$data["fechaPaquete"] = $datos["fechaPaquete"];
					$data["estadoPaquete"] = '1';
					$data["cuenta_creada"] = $this->session->userdata('usuario_h');
				// Datos para el abono
	
				// Datos para hoja de cobro
					$codigoH = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
					$cod = 0;
					if($codigoH->codigoHoja == NULL ){
						$cod = 1000;
					}else{
						$cod = ($codigoH->codigoHoja + 1);
					}
					$hoja["codigo"] = $cod;
					$hoja["paciente"] = $datos["idPaciente"];
					$hoja["fecha"] = $datos["fechaPaquete"];
					$hoja["tipo"] = "Ingreso";
					$hoja["medico"] = $datos["medicoPaquete"];
					$hoja["habitacion"] = 37;
					$hoja["estado"] = 1;
					$hoja["seguro"] = 1;
					$hoja["porPagos"] = 1;
					$hoja["paga"] = 0;
					$hoja["esPaquete"] = 1;
					$hoja["totalPaquete"] = $totalPaquete;
					$respHoja = $this->Hoja_Model->crearHojaPaquete($hoja); // Retorna el id de la hoja de cobro creada.
					$data["idHoja"] = $respHoja;
				// Datos para hoja de cobro
	
				$resp = $this->Hoja_Model->guardarPaquete($data); // Guardando paquete
				// Datos para bitacora -Anular externo cuenta
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["descripcionBitacora"] = "El usuario ".$this->session->userdata('usuario_h')." creo el paquete con ID =".$resp;
				// Fin datos para bitacora -Anular externo cuenta
	
				// Abono
					$abono["hoja"] = $respHoja;
					$abono["monto"] = $datos["cantidadPaquete"];
					$abono["fecha"] = $datos["fechaPaquete"];
					$abono["paquete"] = $resp; // Id del abono
				// Abono
	
				if($resp > 0){
	
					// Datos para bitacora -Anular externo cuenta
						$movimiento["idCuenta"] = $respHoja ;
						$movimiento["idUsuario"] = $this->session->userdata('id_usuario_h');
						$movimiento["usuario"] = $this->session->userdata('usuario_h');
						$movimiento["descripcionBitacora"] = "Creo la hoja de cobro y agrego el abono por: ".$abono["monto"];
					// Fin datos para bitacora -Anular externo cuenta
	
					$this->Hoja_Model->guardarAbonoHoja($abono);
					$this->Usuarios_Model->insertarBitacora($bitacora);
					$this->Usuarios_Model->insertarMovimientoHoja($movimiento); // Capturando movimiento de la hoja de cobro
					$this->session->set_flashdata("exito","Los datos del paquete fueron guardados con exito!");
					redirect(base_url()."Hoja/lista_paquetes");
				}else{
					$this->session->set_flashdata("error","Error al guardar los datos del paquete!");
					redirect(base_url()."Hoja/lista_paquetes");
				}

			}else{
				
				$this->session->set_flashdata("error","Error al guardar los datos del paquete!");
				redirect(base_url()."Hoja/lista_paquetes");
			}


		}

		public function actualizar_paquete(){	
			$datos = $this->input->post();
			$data["pacientePaquete"] = $datos["pacientePaquete"];
			$data["medicoPaquete"] = $datos["medicoPaquete"];
			$data["cantidadPaquete"] = $datos["cantidadPaquete"];
			$data["conceptoPaquete"] = $datos["tipoPaquete"];
			$data["fechaPaquete"] = $datos["fechaPaquete"];
			$data["idPaquete"] = $datos["idPaquete"];
			$resp = $this->Hoja_Model->actualizarPaquete($data);
			if($resp != 0){
				$this->session->set_flashdata("exito","Los datos del paquete fueron actualizados con exito!");
				redirect(base_url()."Hoja/recibo_paquete/".$datos["idPaquete"]."/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos del paquete!");
				redirect(base_url()."Hoja/lista_paquetes");
			}
		}

		public function eliminar_paquete(){
			$datos = $this->input->post();
			$bool = $this->Hoja_Model->eliminarPaquete($datos);
			if($bool){
				$this->session->set_flashdata("exito","El paquete se elimino con exito!");
				redirect(base_url()."Hoja/lista_paquetes/");
			}else{
				$this->session->set_flashdata("error","Error al eliminar el paquete!");
				redirect(base_url()."Hoja/lista_paquetes/");
			}
		}

		public function recibo_paquete($id){
			$datos = $this->Hoja_Model->obtenerPaquete($id, 0);
			if(is_null($datos)){
				$datos = $this->Hoja_Model->obtenerPaquete($id, 1);
			}
			echo '<script>
				if (window.history.replaceState) { // verificamos disponibilidad
					window.history.replaceState(null, null, window.location.href);
				}
			</script>';
			$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			// Reporte PDF
				$data = array('paquete' => $datos, "meses" => $mes );
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

				$mpdf->SetProtection(array('print'));
				$mpdf->SetTitle("Hospital Orellana, Usulutan");
				$mpdf->SetAuthor("Edwin Orantes");
				// $mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
				$mpdf->showWatermarkText = true;
				$mpdf->watermark_font = 'DejaVuSansCondensed';
				$mpdf->watermarkTextAlpha = 0.1;
				$mpdf->SetDisplayMode('fullpage');
				//$mpdf->AddPage('L'); //Voltear Hoja
				$html = $this->load->view("Hoja/paquete_pdf",$data,true); // Cargando hoja de estilos
				$mpdf->WriteHTML($html);
				$mpdf->Output('detalle_compra.pdf', 'I');
			// Fin reporte PDF

			// echo json_encode($datos);
			
		}

		public function generar_recibo_paquete(){
			$datos = $this->input->post();
			$recibo["recibo_creado"] = $this->session->userdata('usuario_h')." | ".date('Y-m-d h:i:s a');
			$recibo["saldado"] = '1';
			$recibo["paquete"] = $datos["idPaqueteEditar"];
			
			$resp = $this->Hoja_Model->agregarAPaquete($recibo);
			if($resp){
				$this->recibo_paquete($datos["idPaqueteEditar"]);
			}else{
				$this->session->set_flashdata("error","Error al generar el recibo!");
				redirect(base_url()."Hoja/lista_paquetes");
			}
		}
	// Fin metodos para recibo de paquetes
	
	// Navegando a hoja a traves de su codigo
		public function mover_a_hoja(){
			$datos = $this->input->post();
			$hoja = $this->Hoja_Model->consultaXCodigo($datos["codigoHoja"]);
			if(is_null($hoja)){
				$this->session->set_flashdata("error","La hoja de cobro no existe...");
				redirect(base_url()."Hoja/");
			}else{
				$this->session->set_flashdata("exito","Mostrando detalle de hoja y observandote...");
				redirect(base_url()."Hoja/detalle_hoja/$hoja->idHoja/");
			}	
			
		}
	// Fin navegar hoja

	// Metodos para testing
		public function testing(){
			$respuesta = $this->Hoja_Model->testing();

			$totalIngresos = 0;
			$totalHoja = 0;
			foreach ($respuesta as $fila) {
				$totalHoja = 0;
				$medicamentos = $this->Usuarios_Model->medicamentosHoja2($fila->idHoja);
				$externos = $this->Usuarios_Model->externosHoja2($fila->idHoja);

				foreach ($medicamentos as $medicamento) {
					//echo "* ".$medicamento->idHoja." --- ".$medicamento->nombreMedicamento." --- ".$medicamento->precioInsumo." --- ".$medicamento->cantidadInsumo."<br>";
					$totalHoja += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
					$totalIngresos += ($medicamento->cantidadInsumo * $medicamento->precioInsumo);
				}
				//echo $fila->idHoja.">>>>>>>".$fila->salidaHoja." ---------> ".$fila->idHoja." ---> ".$fila->correlativoSalidaHoja." --- ".$totalHoja."<br>";
			}

			echo "<br>El total es: ".$totalIngresos;

			
			//var_dump($respuesta);
		}
	// Fin testing


	public function obtenerTurno(){
		$hoyFeriado = false;
		$datos = $this->Hoja_Model->fechasFestivas();
		$hoy = date('m-d');
		$fechas = [];
		foreach ($datos as $item) {
			$fechas[] = $item->fechaFecha;
		}
		
		if (in_array($hoy, $fechas)) {
			$hoyFeriado = true;
		} else {
			$hoyFeriado = false;
		}
	
		$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
		$dia = date("w");

		//echo $dias[date("w")];
		$hora = date('H:i:s');  
		$ampm = date('a');
		$turno = false;  

		if($hoyFeriado){
			$turno = 2;
		}else{
			if($dia >= 1 && $dia <= 5){
				
				switch ($hora) {
					case $hora > "05:30:00" && $ampm == "am":
						$turno = 1;
						break;
					case $hora < "16:30:00" && $ampm == "pm":
						$turno = 1;
						break;
					case $hora < "05:30:00" && $ampm == "am":
						if($dia == 1){						
							$turno = 2;
						}else{
							$turno = 0;
						}
						break;
					case $hora > "16:30:00" && $ampm == "pm":
						$turno = 0;
						break;
					default:
	
						// echo "Son las ".$hora;
						break;
				}
			}else{
				if($dia == 6){
					switch ($hora) {
						case $hora < "05:30:00" && $ampm == "am":
							$turno = 0;
							break;
						case $hora > "05:30:00" && $ampm == "am":
							$turno = 1;
							break;
						case $hora > "12:00:00" && $ampm == "pm":
							$turno = 2;
							break;
						default:
							// echo "Son las ".$hora;
							break;
					}
				}
				if($dia == 0){
					$turno = 2;
				}
				if($dia == 1){
					if($hora < "05:30:00" && $ampm == "am"){
						$turno = 2;
					}
				}
			}
		}


		return $turno;
	}

	/* public function obtenerTurno(){
		$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
		$dia = date("w");

		//echo $dias[date("w")];
		$hora = date('H:i:s');  
		$ampm = date('a');
		$turno = false;  

		if($dia >= 1 && $dia <= 5){
			
			switch ($hora) {
				case (($hora > "07:00:00" && $ampm == "am") && ($hora < "16:00:00" && $ampm == "pm")):
					$turno = 1;
					break;
				default:
					$turno = 2;
					break;
			}

			
		}else{
			if($dia == 6){
				switch ($hora) {
					case (($hora > "07:00:00" && $ampm == "am") && ($hora < "11:59:59" && $ampm == "am")):
						$turno = 1;
						break;
					default:
						$turno = 2;
						break;
				}
			}
			if($dia == 0){
				$turno = 2;
			}
		}

		echo $turno;
	} */

	public function por_pagos_hoja(){
		$datos = $this->input->post();
		$hoja = $datos["idHoja"];
		
		// Datos para bitacora -Editar medicamento cuenta
			$bitacora["idCuenta"] = $datos["idHoja"];
			$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
			$bitacora["usuario"] = $this->session->userdata('usuario_h');
			$bitacora["descripcionBitacora"] = "Cambio la modalidad de la hoja a 'Por pagos'";
		// Fin datos para bitacora -Editar medicamento cuenta	
		
		$resp = $this->Hoja_Model->porCuotas($datos);
		if($resp){
			$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
			$this->session->set_flashdata("exito","La hoja de cobro se cambio a 'por pagos'");
			redirect(base_url()."Hoja/detalle_hoja/$hoja/");
		}else{
			$this->session->set_flashdata("error","No se pudo realizar la acción...");
			redirect(base_url()."Hoja/detalle_hoja/$hoja/");
		}
		
		// echo json_encode($bitacora); // Para cambiar metodo de pagos
	}

	public function guardar_abono(){
		$datos = $this->input->post();
		// Datos para el recibo de paquete
			$codigo = "";
			$datosCodigo = $this->Hoja_Model->codigoPaquete();
			if($datosCodigo != null){
				$codigo = $datosCodigo->codigoPaquete + 1;
			}else{
				$codigo = "1000";
			}
			
			$hoja = $this->Hoja_Model->obtenerHoja($datos["idHoja"]);

			$data["codigoPaquete"] = $codigo;
			$data["pacientePaquete"] = $hoja->nombrePaciente;
			$data["medicoPaquete"] = $hoja->idMedico;
			$data["cantidadPaquete"] = $datos["montoAbono"];
			$data["conceptoPaquete"] = $datos["tipoAbono"];
			unset($datos["tipoAbono"]);
			$data["fechaPaquete"] = $datos["fechaAbono"];
			$data["estadoPaquete"] = '1';
			$data["cuenta_creada"] = $this->session->userdata('usuario_h');
			$data["hoja"] = $datos["idHoja"];
		// Datos para el recibo de paquete

			
		// Datos para bitacora -Crear paquete.
			$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
			$bitacora["descripcionBitacora"] = "El usuario ".$this->session->userdata('usuario_h')." creo el paquete con ID =".$resp;
		// Fin datos para bitacora -Crear paquete.

		// Datos para bitacora -Editar medicamento cuenta
			$creoPago["idCuenta"] = $datos["idHoja"];
			$creoPago["idUsuario"] = $this->session->userdata('id_usuario_h');
			$creoPago["usuario"] = $this->session->userdata('usuario_h');
			$creoPago["descripcionBitacora"] = "Creo el abono con codigo ".$codigo." con un valor de $".$datos["montoAbono"];
		// Fin datos para bitacora -Editar medicamento cuenta	
			
		$pago = $this->Hoja_Model->guardarPagos($datos);
		if($pago > 0){
			$paquete = $this->Hoja_Model->guardarPaquete($data); // Guardando paquete
			$this->Hoja_Model->agregarPaquete($paquete, $pago); // Agregando paquete
			$this->Usuarios_Model->insertarBitacora($bitacora);
			$this->Usuarios_Model->insertarMovimientoHoja($creoPago); // Capturando movimiento de la hoja de cobro
			$this->session->set_flashdata("exito","Pago realizado con exito...");
			redirect(base_url()."Hoja/lista_paquetes");
		}else{
			$this->session->set_flashdata("error","No se pudo realizar el pago");
			redirect(base_url()."Hoja/lista_paquetes");
		}

	}

	public function imprimir_recibo_paquete($paquete = null){
		$datos = $this->input->post();
		$recibo["recibo_creado"] = $this->session->userdata('usuario_h')." | ".date('Y-m-d h:i:s a');
		$recibo["saldado"] = '1';
		$recibo["paquete"] = $paquete;
		
		$resp = $this->Hoja_Model->agregarAPaquete($recibo);
		if($resp){
			$this->recibo_paquete($paquete);
		}else{
			$this->session->set_flashdata("error","Error al generar el recibo!");
			redirect(base_url()."Hoja/lista_paquetes");
		}
	}
	

	// Nuevos funciones
		public function boleta_informativa($id = null){
			$paciente = $this->Hoja_Model->boletaInformativa($id);
			//Definiendo estado civil
				if($paciente->sexoPaciente == "Femenino"){
					switch ($paciente->civilPaciente) {
						case 'Soltero/a':
							$paciente->civilPaciente = "Soltera";
							break;
						case 'Casado/a':
							$paciente->civilPaciente = "Casada";
							break;
						case 'Viudo/a':
							$paciente->civilPaciente = "Viuda";
							break;
							
						default:
							$paciente->civilPaciente = "Otro";
							break;
					}
				}else{
					switch ($paciente->civilPaciente) {
						case 'Soltero/a':
							$paciente->civilPaciente = "Soltero";
							break;
						case 'Casado/a':
							$paciente->civilPaciente = "Casado";
							break;
						case 'Viudo/a':
							$paciente->civilPaciente = "Viudo";
							break;
							
						default:
							$paciente->civilPaciente = "Otro";
							break;
					}
				}
			//Definiendo estado civil

			$data["paciente"] = $paciente;
			
			// Datos para bitacora -Editar medicamento cuenta
				$bitacora["idCuenta"] = $id;
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Genero la boleta informativa ";
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
			// Fin datos para bitacora -Editar medicamento cuenta

			// echo json_encode($data["paciente"]);
			// Detalles de la hoja
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
				$mpdf = new \Mpdf\Mpdf([
					'margin_left' => 15,
					'margin_right' => 15,
					'margin_top' => 50,
					'margin_bottom' => 40,
					'margin_header' => 30,
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
				$html = $this->load->view('Hoja/boleta_informativa', $data ,true); // Cargando hoja de estilos
				$mpdf->SetHTMLHeader('
					<div class="cabecera" style="font-family: Times New Roman">
						<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
						<div class="title_cabecera">
							<h4>HOSPITAL ORELLANA, USULUTAN</h4>
							<h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5>
						</div>
					</div>
				');

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%" style="font-weight: bold; font-size: 12px;"></td>
							<td width="33%" align="center" style="font-weight: bold; font-size: 12px;"></td>
							<td width="33%" style="font-weight: bold; font-size: 10px; text-align: right;">'.$paciente->codigoHoja.'</td>
						</tr>
					</table>');
				
				$mpdf->WriteHTML($html);
				$mpdf->Output('resumen_hoja_cobro.pdf', 'I');
			
			// Fin detalle hoja

			// echo json_encode($data["paciente"]);

		}

		public function pagara_medico(){
			$datos = $this->input->post();
			$hoja = $datos["idHoja"];
			// Datos para bitacora -Editar medicamento cuenta
				$bitacora["idCuenta"] = $datos["idHoja"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Agrego detalle que especifica que el médico cancelara la cuenta";
			// Fin datos para bitacora -Editar medicamento cuenta	
			
			$resp = $this->Hoja_Model->pagaraMedico($datos);
			if($resp){
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","El médico cancelara la cuenta");
				redirect(base_url()."Hoja/detalle_hoja/$hoja/");
			}else{
				$this->session->set_flashdata("error","No se pudo realizar la acción...");
				redirect(base_url()."Hoja/detalle_hoja/$hoja/");
			}
			
			// echo json_encode($datos); // Para cambiar metodo de pagos

		}

		public function actualizar_monto_paquete(){
			$datos = $this->input->post();
			$idReturn = $datos["idHoja"];

			// Datos para bitacora -Editar medicamento cuenta
				$bitacora["idCuenta"] = $datos["idHoja"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Actualizo el precio del paquete a: $".$datos["montoTotalPaquete"];
			// Fin datos para bitacora -Editar medicamento cuenta

			$bool = $this->Hoja_Model->actualizarMontoPaquete($datos);
			if($bool){
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}else{
				$this->session->set_flashdata("error","Error al actualizar los datos!");
				redirect(base_url()."Hoja/detalle_hoja/$idReturn/");
			}

			// echo json_encode($datos);
		}
	// Nuevos funciones

	public function test(){
		echo md5("V.23");
	}

	// Descargos desde stocks
		public function agregar_med_desde_stock(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				$datos["por"] = $this->session->userdata('id_usuario_h');
				$resp = $this->Hoja_Model->guardarDescargoStock($datos);
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
	// Descargos desde stocks


	// Codigos
		public function codigos(){
			$index = 22689;
			$datos = $this->Hoja_Model->facturadas();
			foreach($datos as $row){
				$this->Hoja_Model->facturadas_update($index, $row->idHoja);
				echo $index." ".$row->seguimientoHoja." -".$row->idHoja."<br>";
				$index++;
			}
			// echo json_encode($datos);
		}
	// Codigos

	public function resumen_hoja_all(){
		$hojas = $this->Hoja_Model->funcionTemporal();
		foreach ($hojas as $row) {
			// echo $row->idHoja." - ".$row->codigoHoja." - ".$row->seguimientoHoja."<br>";
			$this->testtt($row->idHoja, $row->seguimientoHoja);
		}
		/* for ($i=7838; $i < 7845; $i++) { 
			$this->testtt($i);
		} */
	}

	public function testtt($id, $s){

		// Define la ruta completa al directorio donde deseas guardar los archivos PDF
			$ruta_directorio = FCPATH . 'archivos/';

		//Obteniendo datos ya existentes
		$totalGlobal = 0;
		$prevEgreso = "";
		$egreso = "";
		$prevIngreso = "";
		$ingreso = "";
		$paciente = $this->Hoja_Model->detalleHoja($id);
		$data["paciente"] = $paciente;
		if($this->session->userdata('global') == 1){
			$prevIngreso = "Fecha:";
			$ingreso = $paciente->fechaMostrar;
			$prevEgreso = "Seguro:";
			$egreso = $paciente->nombreSeguro;
		}else{
			$prevIngreso = "Fecha de Ingreso:";
			$ingreso = date("d/m/Y", strtotime($paciente->fechaHoja));
			$prevEgreso = "Fecha de Egreso:";
			if($paciente->salidaHoja == ""){
				$egreso = "0000-00-00";
			}else{
				$egreso = date("d/m/Y", strtotime($paciente->salidaHoja));
			}
		}
		$codigo = $this->session->userdata('global') == 1 ? $paciente->seguimientoHoja : $paciente->codigoHoja ;
		//$codigo = $paciente->credito_fiscal ;

		// Detalles de la hoja
			$filaExtra = "";
			$data["externosHoja"] = $this->Hoja_Model->externosHoja($id);
			$data["medicamentosHoja"] = $this->Hoja_Model->medicamentosHojaResumen($id);

			$totalMedicamentos = 0;
			$totalDescuento = 0;
			foreach ($data["medicamentosHoja"] as $medicamento) {
				$totalMedicamentos += $medicamento->cantidadInsumo * $medicamento->precioInsumo;
				$totalDescuento += $medicamento->descuentoUnitario;
			}
			$totalExternos = 0;
			foreach ($data["externosHoja"] as $externo) {
				$totalExternos += $externo->cantidadExterno * $externo->precioExterno;
			}

			if($paciente->procedimientoHoja != ''){
				$filaExtra .= '<tr>';
				$filaExtra .= '		<td><strong>PROCEDIMIENTO: </strong></td>';
				$filaExtra .= '		<td colspan="3" style="text-transform: uppercase">'.$paciente->procedimientoHoja.'</td>';
				$filaExtra .= '		<td></td>';
				$filaExtra .= '		<td></td>';
				$filaExtra .= '</tr>';
			}else{
				$filaExtra .= '';
			}
			if($paciente->descuentoHoja != null){
				$totalGlobal = (($totalExternos + $totalMedicamentos) - $paciente->descuentoHoja);
			}else{
				$totalGlobal = ($totalExternos + $totalMedicamentos);
			}
		//Fin

		$data["descuentoTotal"] = $totalDescuento;
		$data["totalGlobal"] = $totalGlobal;
		// Fin detalle hoja
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
		$mpdf = new \Mpdf\Mpdf([
			'margin_left' => 15,
			'margin_right' => 15,
			'margin_top' => 70,
			'margin_bottom' => 40,
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
		
		$mpdf->SetHTMLHeader('	
			<div class="cabecera" style="font-family: Times New Roman">
				<div class="img_cabecera"><img src="'.base_url().'public/img/logo.jpg"></div>
				<div class="title_cabecera">
					<h4>HOSPITAL ORELLANA, USULUTAN</h4>
					<h5>Sexta calle oriente, #8, Usulután, El Salvador, PBX: 2606-6673</h5>
				</div>
				
				<div class="subtitle_cabecera">
					<h5>DETALLE DE HOJA DE COBRO: '.$codigo.'</h5>
				</div>
			</div>
			<div class="paciente">
				<table class="tabla_paciente" style="font-family: Times New Roman;">

				<tr>
						<td><strong>NOMBRE DEL PACIENTE: </strong></td>
						<td class="letraMayuscula" colspan="3">'.$paciente->apellidoPaciente." ".$paciente->nombrePaciente.'</td>
						<td><strong>'.$prevIngreso.'</strong></td>
						<td>'.$ingreso.'</td>
					</tr>

					<tr>
						<td><strong>DUI: </strong></td>
						<td colspan="3">'.strtoupper($paciente->duiPaciente).'</td>
						<td><strong>'.$prevEgreso.'</strong></td>
						<td>'.$egreso.'</td>
					</tr>

					<tr>
						<td><strong>MEDICO: </strong></td>
						<td colspan="3" class="letraMayuscula">'.$paciente->nombreMedico.'</td>
						<td><strong>Edad:</strong></td>
						<td>'.$paciente->edadPaciente." AÑOS".'</td>
					</tr>
					'.$filaExtra.'
				</table>
			</div>
		');
					
		// FOOTER 
		$mpdf->SetHTMLFooter('
			<div class="numeracion" style="font-family: Times New Roman">
				<div class="numeracion_izquierda"></div>
				<div class="numeracion_derecha"><strong></strong></div>
			</div>
		');

		// $mpdf->SetHTMLFooter('');
		$html = $this->load->view('Hoja/resumen_hoja', $data ,true); // Cargando hoja de estilos
		$mpdf->WriteHTML($html);
		
		if(sizeof($data["externosHoja"]) > 0 && $this->session->userdata("global") == 3){
			$mpdf->AddPage();
			$html2 = $this->load->view('Hoja/resumen_externos', $data ,true); // Cargando hoja de estilos
			$mpdf->WriteHTML($html2);
		}

		// Genera un nombre de archivo único para cada PDF
		$nombre_archivo = 'resumen_hoja_cobro_' . $s . '.pdf';

		// Salida del PDF: guardar en un archivo en el servidor
		$mpdf->Output($ruta_directorio . $nombre_archivo, 'F');

		// $mpdf->Output('resumen_hoja_cobro.pdf', 'I');
		// echo json_encode($data);
	}


	// Metodo para maqueta de factura
		private function printTable($printer, $headers, $rows) {
			$widths = [6, 35, 7, 13, 9]; // Ancho de las columnas en caracteres
		
			// Imprimir encabezados
			foreach ($headers as $index => $header) {
				$printer->text(str_pad($header, $widths[$index]));
			}
			$printer->text("\n");
			
			/*    // Línea separadora
			$totalWidth = array_sum($widths);
			$printer->text(str_repeat('-', $totalWidth));
			$printer->text("\n"); */
			
			// Imprimir filas
			foreach ($rows as $row) {
				foreach ($row as $index => $column) {
					$printer->text(str_pad($column, $widths[$index]));
				}
				$printer->text("\n");
			}
    	}

		private function basico($numero) {
            $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
            'nueve','diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
            'veinte', 'veintiuno', 'veintidos', 'veintitres', 'veinticuatro','veinticinco', 'veintiseis','veintisiete','veintiocho','veintinueve');
            return $valor[$numero-1];
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
            case ( $n >= 1 && $n < 30) : return $this->basico($n); break;
            case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
            case ( $n >= 100 && $n < 1000) : return $this->centenas($n); break;
            case ($n >= 1000 && $n <= 999999): return $this->miles($n); break;
            case ($n >= 1000000): return $this->millones($n);
            }
        }
	// Metodo para maqueta de factura


	public function validar_medicamento_nuevo(){
		if($this->input->is_ajax_request()){
			$datos =$this->input->post();
			$data = $this->Botiquin_Model->obtenerMedicamento($datos);
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	// Metodo para actualizar procedimiento
		public function actualizar_procedimiento(){
			if($this->input->is_ajax_request()){
				$datos = $this->input->post();
				// Datos para bitacora -Editar medicamento cuenta
					$bitacora["idCuenta"] = $datos["hoja"];
					$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
					$bitacora["usuario"] = $this->session->userdata('usuario_h');
					$bitacora["descripcionBitacora"] = "Edito el nombre del procedimiento a: ".$datos["procedimiento"];
				// Fin datos para bitacora -Editar medicamento cuenta	

				
				
				// Ejecutando consultas
				$bool = $this->Hoja_Model->actualizarProcedimiento($datos);
				if($bool){
					$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
					$respuesta = array('estado' => 1, 'respuesta' => 'Exito');
					header("content-type:application/json");
					print json_encode($respuesta);
				}else{
					$respuesta = array('estado' => 0, 'respuesta' => 'Error');
					header("content-type:application/json");
					print json_encode($respuesta);
				}
				// echo json_encode($bitacora);
			}
			else{
				$respuesta = array('estado' => 0, 'respuesta' => 'Error');
				header("content-type:application/json");
				print json_encode($respuesta);
			}
		}


		public function buscar_empleado(){
			if($this->input->is_ajax_request()){
				$empleados = $this->Planilla_Model->personalPlanilla();
				print json_encode($empleados);
			}
		}

		public function guardar_descuento(){
			$datos =  $this->input->post();
			$responsable = "";
			// Validando si existe un responsable de la cuenta ya sea empleado o familiar.
				if(isset($datos["nombreResponsableCuenta"])){
					$responsable = $datos["nombreResponsableCuenta"];
					unset($datos["nombreResponsableCuenta"]);
				}
			// Validando si existe un responsable de la cuenta ya sea empleado o familiar.
			$hojaActual = $datos["hojaCobro"];
			$descuentoFila = 0;
			$medicamentosHoja = $this->Hoja_Model->medicamentosHoja($hojaActual);
			$seguro = $this->Hoja_Model->obtenerSeguro($datos["seguroHoja"]);
			$detalle = '';

			// Procesando seguro
				if($datos["seguroHoja"] > 1){
					$aumento = $seguro->porcentajeSeguro/100;
					foreach ($medicamentosHoja as $med) {
						// Si fue un elemento agregado con un precio especifico
						if($med->precioEditable == 0){
							$this->Hoja_Model->aumentoSeguro($med->precioVMedicamento, $med->idHojaInsumo, 0);
							$precioOriginal = $med->precioVMedicamento;
							if($med->descuentoMedicamento != 0){
								$nuevoPrecio = round($precioOriginal + ($precioOriginal * ($med->descuentoMedicamento/100)), 2);
							}else{
								$nuevoPrecio = round($precioOriginal + ($precioOriginal * $aumento), 2);
							}
	
							$descuentoFila = ($precioOriginal * $aumento);
							$this->Hoja_Model->aumentoSeguro($nuevoPrecio , $med->idHojaInsumo, $descuentoFila);
						}


					}
					$detalle .= ' Cambio la hoja al seguro: '.$seguro->nombreSeguro.",";
				}else{
					foreach ($medicamentosHoja as $med) {
						if($med->precioEditable == 0){
							$this->Hoja_Model->aumentoSeguro($med->precioVMedicamento, $med->idHojaInsumo, 0);
						}
						
					}
					$detalle .= ' Cambio la hoja al seguro: '.$seguro->nombreSeguro.",";
				}
			// Procesando seguro
			
			//Creando descripcion
				$bitacora["idCuenta"] = $hojaActual;
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = $detalle;
			// Fin datos para bitacora
			
			$bool = $this->Hoja_Model->agregarSeguro($seguro->idSeguro, $responsable, $hojaActual); // Capturando movimiento de la hoja de cobro
			if($bool){
				$this->Usuarios_Model->insertarMovimientoHoja($bitacora);
				$this->session->set_flashdata("exito","Se agrego el detalle con exito!");
				redirect(base_url()."Hoja/detalle_hoja/".$hojaActual."/");
			}else{
				$this->session->set_flashdata("error","Error al cerrar la hoja de cobro!");
				redirect(base_url()."Hoja/detalle_hoja/".$hojaActual."/");
			}


			// echo json_encode($responsable);


		}

	// Metodo para actualizar procedimiento


	// Metodos para corte de caja
	// Metodos para corte de caja

	//Metodo para actualizar correlativo de factura electronica
		public function guardar_numero_facturacion(){
			$datos = $this->input->post();
			$hoja = $this->Hoja_Model->detalleHoja($datos["hojaFactura"]);

			$controlFactura["clienteFactura"] = $hoja->nombrePaciente;
			$controlFactura["duiFactura"] = $hoja->duiPaciente;
			$controlFactura["idHojaCobro"] = $hoja->idHoja;
			$controlFactura["numeroFactura"] = $datos["numeroFactura"];
			$controlFactura["tipoFactura"] = "1";
			$controlFactura["fechaMostrar"] = $hoja->salidaHoja;

			// Datos para bitacora -Editar medicamento cuenta
				$bitacora["idCuenta"] = $datos["hojaFactura"];
				$bitacora["idUsuario"] = $this->session->userdata('id_usuario_h');
				$bitacora["usuario"] = $this->session->userdata('usuario_h');
				$bitacora["descripcionBitacora"] = "Agrego la factura de ".$datos["tipoFactura"]." con código ".$datos["numeroFactura"];
			// Fin datos para bitacora -Editar medicamento cuenta	
			
			$bool = $this->Hoja_Model->guardarNumeroFacturacion($datos);
			if($bool){
				$this->Facturacion_Model->guardarCorrelativoFactura($controlFactura);

				$this->Usuarios_Model->insertarMovimientoHoja($bitacora); // Capturando movimiento de la hoja de cobro
				$this->session->set_flashdata("exito","Datos actualizados con exito");
				redirect(base_url().'Hoja/detalle_hoja/'.$datos["hojaFactura"].'/');
			}else{
				$this->session->set_flashdata("error","Error al actualizar datos");
				redirect(base_url().'Hoja/detalle_hoja/'.$datos["hojaFactura"].'/');
			}

			// echo json_encode($controlFactura);

		}
	//Metodo para actualizar correlativo de factura electronica

}
