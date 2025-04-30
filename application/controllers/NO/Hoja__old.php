<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

defined('BASEPATH') OR exit('No direct script access allowed');

class Hoja extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model("Medico_Model");
		$this->load->model("Paciente_Model");
		$this->load->model("Botiquin_Model");
		$this->load->model("Externos_Model");
		$this->load->model("Hoja_Model");
	}
	
	public function index(){
		$medicos = $this->Medico_Model->obtenerMedicos();
		$pacientes = $this->Paciente_Model->obtenerPacientes();
		$habitaciones = $this->Paciente_Model->obtenerHabitaciones();
		$medicamentos = $this->Botiquin_Model->obtenerMedicamentos();
		$externos = $this->Externos_Model->obtenerExternos();
		$codigo = $this->Hoja_Model->codigoHoja(); // Ultimo codigo de hoja
		if($codigo == NULL ){
			$codigo = 1000000;
		}else{
			$codigo = $codigo->codigoHoja +1;
		}

		$data = array('medicos' => $medicos, 'pacientes' => $pacientes, 'habitaciones' => $habitaciones,
					  'medicamentos' => $medicamentos, 'externos' => $externos, 'codigo' => $codigo);

		$this->load->view('Base/header');
		$this->load->view('HojaCobro/agregar_hoja', $data);
		$this->load->view('Base/footer');
	}

	public function lista_hojas(){
		$this->load->view('Base/header');

		$datos = $this->Hoja_Model->obtenerHojas();
		$data = array('hojas' => $datos );
		$this->load->view('HojaCobro/lista_hojas', $data);
		$this->load->view('Base/footer');
	}

	public function guardar_hoja(){
		$datos = $this->input->post();
		$totalHoja = 0;
		// Datos para la hoja de cobro
		$hoja["idPaciente "] = $datos["pacienteHoja"];
		$hoja["fechaHoja"] = $datos["ingresoHoja"];
		$hoja["tipoHoja"] = $datos["consultaHoja"];
		$hoja["idMedico "] = $datos["medicoHoja"];
		$hoja["idHabitacion"] = $datos["habitacionHoja"];

		if(isset($datos["idMedicamentos"])){
			// Medicamentos
			$data['medicamentos'] = $datos["idMedicamentos"];
			// Medicamentos
			$data['preciosMedicamentos'] = $datos["preciosMedicamentos"];
			// Medicamentos
			$totalMedicamentos= $datos["totalMedicamentos"];
			// Cantidad medicamentos
			$data['cantidadMedicamentos'] = $datos["cantidadMedicamentos"];
			// Total medicamentos
			$totalMedicamentosD = $datos["totalIS"];
			// Stock medicamentos
			$data['stockMedicamentos'] = $datos["stockMedicamentos"];
			$data['usadosMedicamento'] = $datos["usadosMedicamentos"];

			$totalHoja = $totalHoja + $totalMedicamentosD;
			
		}
		if(isset($datos["idExternos"])){
			// Externos
			$externos= $datos["idExternos"];
			// Cantidad Externos
			$cantidadExternos= $datos["cantidadExternos"];
			// Precio externos
			$precioExternos= $datos["precioE"];
			// Total externos
			$totalExternosD = $datos["totalSE"];
			$totalHoja = $totalHoja + $totalExternosD;

			// Arreglo con toda la informacion
			$data['externos'] = $externos;
			$data['cantidadExternos'] = $cantidadExternos;
			$data['precioExternos'] = $precioExternos;
		}

		$hoja["totalHoja "] = $totalHoja;
		$hoja["codigoHoja "] = $datos["codigoHoja"];

		$data["hoja"] = $hoja; // Agregando la informacion de la hoja de cobro

		$bool = $this->Hoja_Model->guardarHoja($data);
		if($bool){
			$this->session->set_flashdata("exito","Los datos de la hoja de cobro fueron guardados con exito!");
			redirect(base_url()."Botiquin/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos de la hoja de cobro!");
			redirect(base_url()."HojaCobro/");
		}

	}

	public function agregar_detalle($id){
		$this->load->view('Base/header');
		$medicamentos = $this->Botiquin_Model->obtenerMedicamentos();
		$datos = $this->Hoja_Model->detalleHoja($id);
		$externos = $this->Hoja_Model->detalleHojaExternos($id);
		$listaExternos = $this->Externos_Model->obtenerExternos();
		$data = array('hoja' => $datos, 'externos' => $externos, 'medicamentos' => $medicamentos, 'listaExternos' => $listaExternos);
		$this->load->view('HojaCobro/agregar_detalle', $data);
		$this->load->view('Base/footer');
	}

	public function agregar_detalle_hoja(){
		$datos = $this->input->post();
		$data["idHoja"] = $datos["idHoja"];
		$data["fechaHoja"] = $datos["fechaHoja"];
		if(isset($datos["idsMedicamentos"])){
			$data["medicamentos"] = $datos["idsMedicamentos"];
			$data["preciosMedicamentos"] = $datos["precioInsumo"];
			$data["cantidadMedicamentos"] = $datos["cantidadInsumo"];
			$data["totalMedicamentosU"] = $datos["txtTotalUnitario"];
			$data["stocksMedicamentos"] = $datos["stocksMedicamentos"];
			$data["usadosMedicamentos"] = $datos["usadosMedicamentos"];
		}
		
		if(isset($datos["idsExternos"])){
			$data["externos"] = $datos["idsExternos"];
			$data["precioExternos"] = $datos["precioExterno"];
			$data["precioExternos"] = $datos["precioExterno"];
			$data["cantidadExternos"] = $datos["cantidadExterno"];
			$data["totalExternosU"] = $datos["totalExternoUnitario"];
		}

		$bool = $this->Hoja_Model->guardarDetalleHojas($data);
		if($bool){
			$this->session->set_flashdata("exito","Se agregaron los detalles a la hoja");
			redirect(base_url()."HojaCobro/agregar_detalle/".$datos["idHoja"]);
		}else{
			$this->session->set_flashdata("error","Error al agregar los detalles a la hoja");
			redirect(base_url()."HojaCobro/agregar_detalle/".$datos["idHoja"]);
		}

		//var_dump($data);

		/*var_dump($medicamentos);
		echo "<br>";
		var_dump($preciosMedicamentos);
		echo "<br>";
		var_dump($cantidadMedicamentos);
		echo "<br>";
		var_dump($totalMedicamentosU);
		echo "<br>";
		var_dump($stocksMedicamentos);
		echo "<br>";

		echo "---------------------------------------------------------------------------------------------------<br>";
		var_dump($externos);
		echo "<br>";
		var_dump($precioExternos);
		echo "<br>";
		var_dump($cantidadExternos);
		echo "<br>";
		var_dump($totalExternosU);*/
	}

	public function cerrar_ingreso(){
		$datos = $this->input->post();
		$data['hoja']= $datos['hoja'];
		$data['hab']= $datos['hab'];
		$data['fecha']= Date('m/d/Y');

		$bool = $this->Hoja_Model->cerrarHoja($data);
		if($bool){
			$this->session->set_flashdata("exito","Se cerro la hoja de cobros");
			redirect(base_url()."HojaCobro/agregar_detalle/".$datos["hoja"]);
		}else{
			$this->session->set_flashdata("error","Error al cerrar la hoja");
			redirect(base_url()."HojaCobro/agregar_detalle/".$datos["hoja"]);
		}
	}

	public function detalle_hoja_pdf($id){
		/*$datos = $this->Botiquin_Model->detalleFacturasCompra($id);
		$data = array('facturas' => $datos );*/


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
		$mpdf->SetHTMLFooter('
			<table width="100%">
				<tr>
					<td width="33%">{DATE j-m-Y}</td>
					<td width="33%" align="center">{PAGENO}/{nbpg}</td>
					<td width="33%" style="text-align: right;">Detalle de compra</td>
				</tr>
			</table>');
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Hospital Orellana, Usulutan");
		$mpdf->SetAuthor("Edwin Orantes");
		$mpdf->SetWatermarkText("Hospital Orellana, Usulutan");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');
		//$mpdf->AddPage('L'); //Voltear Hoja

		$html = $this->load->view('HojaCobro/detalle_hoja_pdf',[],true); // Cargando hoja de estilos

        $mpdf->WriteHTML($html);
		$mpdf->Output('detalle_compra.pdf', 'I');
	}
    
}

// SELECT * FROM tbl_hoja_externos as he INNER JOIN tbl_externos as e ON(he.idExterno = e.idExterno) LEFT JOIN tbl_proveedores as p on(e.idEntidad = p.idProveedor) WHERE fechaExterno BETWEEN '01/25/2021' AND '01/29/2021' 
