<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	// Clases para el reporte en excel
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

class Empleado extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Paciente_Model");
		$this->load->model("Empleado_Model");
	}

    public function index(){
        $departamentos = $this->Paciente_Model->obtenerDepartamentos();
        $cargos = $this->Empleado_Model->obtenerCargos();
		$data = array('departamentos' => $departamentos, 'cargos' => $cargos);
        $this->load->view("Base/header");
        $this->load->view("Empleado/agregar_empleado", $data);
        $this->load->view("Base/footer");
    }
    
    public function obtener_municipios(){
		if($this->input->is_ajax_request())
		{
			$id =$this->input->get("id");
			$datos = $this->Paciente_Model->obtenerMunicipios($id);
			echo json_encode($datos);
		}
		else
		{
			echo "Error...";
		}
	}

    public function agregar_empleado(){
		$datos = $this->input->post();
		$bool = $this->Empleado_Model->guardarEmpleado($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Empleado/");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Empleado/");
		}
	}

    public function lista_empleados(){
		$data["empleados"] = $this->Empleado_Model->obtenerEmpleados();
        $data["departamentos"] = $this->Paciente_Model->obtenerDepartamentos();
        $data["cargos"] = $this->Empleado_Model->obtenerCargos();
		// $data = array('empleados' => $empleados, 'departamentos' => $departamentos, 'cargos' => $cargos);

		$this->load->view('Base/header');
		$this->load->view('Empleado/lista_empleados', $data);
		$this->load->view('Base/footer');
	}

	public function actualizar_empleado(){
		$datos = $this->input->post();
		$bool = $this->Empleado_Model->actualizarEmpleado($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Empleado/lista_empleados");
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			redirect(base_url()."Empleado/lista_empleados");
		}
		
	}

    public function eliminar_empleado(){
		$datos = $this->input->post();
		$bool = $this->Empleado_Model->eliminarEmpleado($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Empleado/lista_empleados");
		}else{
			$this->session->set_flashdata("error","Hubo un error al aliminar los datos!");
			redirect(base_url()."Empleado/lista_empleados");
		}
	}

    /*

	// Municipios de El Salvador


	

	public function lista_pacientes_excel(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Id');
		$sheet->setCellValue('B1', 'Nombre');
		$sheet->setCellValue('C1', 'Edad');
		$sheet->setCellValue('D1', 'Teléfono');
		$sheet->setCellValue('E1', 'Ocupación');
		$sheet->setCellValue('F1', 'Sexo');
		$sheet->setCellValue('G1', 'DUI');
		$sheet->setCellValue('H1', 'NIT');
		$sheet->setCellValue('I1', 'Estado Civil');
		$sheet->setCellValue('J1', 'Fecha de nacimiento');
		$sheet->setCellValue('K1', 'Dirección');
			
		$datos = $this->Paciente_Model->obtenerPacientes();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $number);
			$sheet->setCellValue('B'.$flag, $d->nombrePaciente);
			$sheet->setCellValue('C'.$flag, $d->edadPaciente);
			$sheet->setCellValue('D'.$flag, $d->telefonoPaciente);
			$sheet->setCellValue('E'.$flag, $d->ocupacionPaciente);
			$sheet->setCellValue('F'.$flag, $d->sexoPaciente);
			$sheet->setCellValue('G'.$flag, $d->duiPaciente);
			$sheet->setCellValue('H'.$flag, $d->nitPaciente);
			$sheet->setCellValue('I'.$flag, $d->estadoPaciente);
			$sheet->setCellValue('J'.$flag, $d->nacimientoPaciente);
			$sheet->setCellValue('K'.$flag, $d->direccionPaciente);
				
			$flag = $flag+1;
			$number = $number+1;
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
		$sheet->getStyle('A1:K1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:K10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:K10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_pacientes'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	// Control de habitaciones
	public function paciente_habitacion(){
		$this->load->view('Base/header');
		
		$pacientes = $this->Paciente_Model->obtenerPacientesHabitacion();
		
		$data = array('pacientes' => $pacientes);

		$this->load->view('Paciente/paciente_habitacion', $data);
		$this->load->view('Base/footer');

	}	

	public function esquema_habitaciones(){
		$this->load->view('Base/header');
		
		$habitaciones = $this->Paciente_Model->obtenerHabitaciones();
		
		$data = array('habitaciones' => $habitaciones);

		$this->load->view('Paciente/habitaciones', $data);
		$this->load->view('Base/footer');

	}	*/

    // Funciones para cargos de empleados

    public function cargos_empleados(){
		$cargos = $this->Empleado_Model->obtenerCargos();
		$data = array('cargos' => $cargos);
        $this->load->view("Base/header");
        $this->load->view("Empleado/cargos_empleado", $data);
        $this->load->view("Base/footer");
    }

    public function guardar_cargo(){
        $datos = $this->input->post();
        $bool = $this->Empleado_Model->guardarCargo($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."Empleado/cargos_empleados");
		}else{
			$this->session->set_flashdata("error","Hubo un error al guardar los datos!");
			redirect(base_url()."Empleado/cargos_empleados");
		}
    }

    public function actualizar_cargo(){
        $datos = $this->input->post();
        $bool = $this->Empleado_Model->actualizarCargo($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."Empleado/cargos_empleados");
		}else{
			$this->session->set_flashdata("error","Hubo un error al actualizar los datos!");
			redirect(base_url()."Empleado/cargos_empleados");
		}
    }

    public function eliminar_cargo(){
        $datos = $this->input->post();
        $bool = $this->Empleado_Model->eliminarCargo($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."Empleado/cargos_empleados");
		}else{
			$this->session->set_flashdata("error","Hubo un error al eliminar los datos!");
			redirect(base_url()."Empleado/cargos_empleados");
		}
    }

	public function vacaciones_empleados(){
		$this->load->view("Base/header");
        $this->load->view("Empleado/vacaciones");
        $this->load->view("Base/footer");
	}

	function obtenerCumples(){
		$empleados = $this->Empleado_Model->obtenerCumpleaños();
		foreach($empleados->result_array() as $row)
		{
			$vacacioInicio = ($this->anios_entre_fecha($row['nacimientoEmpleado']) + 1 );
			//$vacacioFin = $vacacioInicio + 15;

			$cumple =  date("Y-m-d",strtotime($row['nacimientoEmpleado']."+ $vacacioInicio year"));

			$data[] = array(
				'id'	=>	$row['idEmpleado'],
				'title'	=>	$row['nombreEmpleado'],
				'start'	=>	$cumple,
				//'start'	=>	$vacacioFin
			);

			//echo $vacacioInicio."<br>";
		}
		echo json_encode($data);
		//echo $cumple;
	}

	private function anios_entre_fecha($fecha_nacimiento){
		$nacimiento = new DateTime($fecha_nacimiento);
		$ahora = new DateTime(date("Y-m-d"));
		$diferencia = $ahora->diff($nacimiento);
		return $diferencia->format("%y");
	}
}
