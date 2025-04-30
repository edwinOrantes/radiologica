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

class CuentasPendientes extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Botiquin_Model");
		$this->load->model("Proveedor_Model");
		$this->load->model("Pendientes_Model");
		$this->load->model("Gastos_Model");
	}

	public function cuentas_por_pagar(){
 		$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
 		$data["cuentas"] = $this->Pendientes_Model->obtenerCuentasPagar();
		$this->load->view("Base/header");
		$this->load->view("Botiquin/cuentas_por_pagar", $data);
		$this->load->view("Base/footer");

	}

	public function guardar_cuenta_pagar(){
		$datos = $this->input->post();
		$datos["pivote"] = 0;
		$resp = $this->Pendientes_Model->guardarCuentaPagar($datos);
		if($resp > 0){
			$this->session->set_flashdata("exito","Los datos se guardaron con exito'");
			redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos");
			redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
		}

		// echo json_encode($datos);
	}

	public function guardar_cuenta_pendiente(){
		if($this->input->is_ajax_request()){
			
			$datos = $this->input->post();

			$datos["estado"] = 1;
			$datos["pivote"] = 0;
			
			// Ejecutando consultas
			$resp = $this->Pendientes_Model->guardarCuentaPagar($datos);
			if($resp > 0){
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

	// Obteniendo DUI de paciente para validad
	public function validar_proveedor(){
		if($this->input->is_ajax_request()){
			$id = $this->input->get("id");
			$data = $this->Pendientes_Model->obtenerProveedor(trim($id));
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	public function obtener_proveedor(){
		if($this->input->is_ajax_request()){
			$id = $this->input->get("id");
			$data = $this->Pendientes_Model->cuentasProveedor(trim($id));
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	public function saldar_cuentas(){
		$datos = $this->input->post();
		$bancoCuenta = $this->Gastos_Model->obtenerCuentaBanco($datos["cuentaGasto"]);
		$datos["bancoGasto"] = $bancoCuenta->nombreBanco;
		$datos["cuentaGasto"] = $bancoCuenta->numeroCuenta;
		
		
		// Ultimo codigo de hoja
			$codigo = $this->Gastos_Model->codigoGasto(); 
			if($codigo == NULL ){
				$codigo = 1000000;
			}else{
				$codigo = $codigo->codigo +1;
			}
		
		$montoTotal = 0;
		$idProveedor= 0;
		$empresaProveedor = "";
		for ($i=0; $i < sizeof($datos["cuenta"]); $i++) {
			$p = $datos["cuenta"][$i];
			$cuentas = $this->Pendientes_Model->cuentaProveedor($p);

			foreach ($cuentas as $cuenta) {
				$montoTotal += $cuenta->totalCuentaPagar;
				$idProveedor = $cuenta->idProveedor;
				$empresaProveedor = $cuenta->empresaProveedor;
			}

		}
		
		$gasto["tipoGasto"] = '1';
		$gasto["montoGasto"] = $montoTotal;
		$gasto["entregadoGasto"] = $empresaProveedor;
		$gasto["idCuentaGasto"] = '6';
		$gasto["fechaGasto"] = date('Y-m-d');
		$gasto["categoria"] = $datos["areaGasto"];
		$gasto["entidadGasto"] = '2';
		$gasto["idProveedorGasto"] = $idProveedor;
		$gasto["pagoGasto"] = '2';
		$gasto["numeroGasto"] = $datos["chequeCuenta"];
		$gasto["bancoGasto"] = $datos["bancoGasto"];
		$gasto["cuentaGasto"] = $datos["cuentaGasto"];
		$gasto["descripcionGasto"] = 'Pago de cuentas pendientes a '.$empresaProveedor;
		$gasto["codigoGasto"] = $codigo;
		$gasto["flagGasto"] = '1';
		$gasto["efectuoGasto"] = $this->session->userdata("empleado_h");
		$gasto["banco"] = $bancoCuenta->idBanco;
		$gasto["cuenta"] = $bancoCuenta->idCuenta;
		$gasto["accesoGasto"] = $this->session->userdata('acceso_h');




		$data["gasto"] = $gasto;  // Para el recibo de gastos
		$data["cuentas"] = $datos["cuenta"]; // Cuenta a saldar

		
		$id = $this->Pendientes_Model->saldarCuentas($data);
		if($id > 0){
			$this->session->set_flashdata("exito","Los datos se guardaron con exito'");

			//Impresion normal
				redirect(base_url()."Gastos/imprimir_recibo/".$id."/".$idProveedor."/2/1"); // Actual
			//Impresion normal

			// Impresion en maquina matricial
				$ip = $_SERVER['REMOTE_ADDR'];
				$url2 = "http://".$ip."/"."sirius/Home/imprimir_cheque/";
				redirect($url2.$id."/".$idProveedor."/2/1");
			// Impresion en maquina matricial



		}else{
			$this->session->set_flashdata("error","Error al guardar los datos");
			redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
		}

		// echo json_encode($data);

	}

	public function cuentas_por_proveedor(){
		$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
		$this->load->view("Base/header");
		$this->load->view("Botiquin/cuentas_por_proveedor", $data);
		$this->load->view("Base/footer");
	}

	public function validar_por_proveedor(){
		if($this->input->is_ajax_request()){
			$id = $this->input->get("id");
			$data = $this->Pendientes_Model->cuentasPorProveedor(trim($id));
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	public function validar_por_proveedor2(){
		if($this->input->is_ajax_request()){
			$id = $this->input->get("id");
			$p = $this->input->get("p");
			$data = $this->Pendientes_Model->cuentasPorProveedor2(trim($id), trim($p));
			echo json_encode($data);
		}
		else{
			echo "Error...";
		}
	}

	public function eliminar_cuenta_pagar(){
		if($this->input->is_ajax_request()){
			// Datos para eliminar medicamento 
				$cuenta["idCuentaPagar"] = $this->input->post("idCuentaPagar");
			
			// Ejecutando consultas
				$bool = $this->Pendientes_Model->eliminarCuentaPagar($cuenta);
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

	public function actualizar_cuenta_pagar(){
		$datos = $this->input->post();
		$bool = $this->Pendientes_Model->actualizarCuentaPagar($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos se actualizaron con exito'");
			redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos");
			redirect(base_url()."CuentasPendientes/cuentas_por_pagar");
		}
		// echo json_encode($datos);
	}

	public function cuentas_por_fecha(){
		$this->load->view("Base/header");
		$this->load->view("Botiquin/cuentas_por_fecha");
		$this->load->view("Base/footer");
	}

	public function filtro_cuentas_fecha(){
		$datos = $this->input->post();
		if ($datos['fechaInicio'] > $datos['fechaFin']) {
			$this->session->set_flashdata("error","La fecha de inicio no puede ser mayor o igual a la fecha fin");
			redirect(base_url()."CuentasPendientes/cuentas_por_fecha");
		}else{
			$data["inicio"] = $datos["fechaInicio"];
			$data["fin"] = $datos["fechaFin"];
			$data["cuentas"] = $this->Pendientes_Model->filtrarCuentasFecha($datos);
			$this->load->view("Base/header");
			$this->load->view("Botiquin/detalle_filtro_cuentas", $data);
			$this->load->view("Base/footer");
		}
	}

	// Nuevos metodos para saldar cuentas pendientes
	public function saldar_cuentas_pendientes(){
		$data["proveedores"] = $this->Proveedor_Model->obtenerProveedores();
 		$data["cuentas"] = $this->Pendientes_Model->obtenerCuentasPagar();
		$data['bancos'] = $this->Gastos_Model->obtenerBancos();
		$data['cuentasBanco'] = $this->Gastos_Model->obtenerCuentasBancos();
		$data['categorias'] = $this->Gastos_Model->obtenerCategorias();
		$this->load->view("Base/header");
		$this->load->view("Botiquin/lista_cuentas_pendientes", $data);
		$this->load->view("Base/footer");

		// echo json_encode($data['categorias']);
	}
	// Nuevos metodos para saldar cuentas pendientes


}
