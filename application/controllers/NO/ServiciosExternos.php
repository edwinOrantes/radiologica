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

class ServiciosExternos extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
		$this->load->model("Medico_Model");
		$this->load->model("Proveedor_Model");
		$this->load->model("Externos_Model");
	}

    public function index(){
		$datos = $this->Externos_Model->obtenerExternos();
        $data = array('externos' => $datos);
        $this->load->view('Base/header');
		$this->load->view('Externos/servicios_externos', $data);
		$this->load->view('Base/footer');
        
    }

	public function guardar_externo(){
        $datos = $this->input->post();
		$bool = $this->Externos_Model->guardarExterno($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron guardados con exito!");
			redirect(base_url()."ServiciosExternos/");
		}else{
			$this->session->set_flashdata("error","Error al guardar los datos!");
			redirect(base_url()."ServiciosExternos/");
		}
    }

    public function actualizar_servicio(){
        $datosA = $this->input->post();
        $datos['nombreExterno'] = $datosA['nombreExternoA'];
        $datos['descripcionExterno'] = $datosA['descripcionExternoA'];
        $datos['idExterno'] = $datosA['idActualizar'];
		$bool = $this->Externos_Model->actualizarExterno($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron actualizados con exito!");
			redirect(base_url()."ServiciosExternos/");
		}else{
			$this->session->set_flashdata("error","Error al actualizar los datos!");
			redirect(base_url()."ServiciosExternos/");
		}
    }

    public function eliminar_externo(){
        $datosA = $this->input->post();
        $datos['idExterno'] = $datosA['idEliminar'];
        $bool = $this->Externos_Model->eliminarExterno($datos);
		if($bool){
			$this->session->set_flashdata("exito","Los datos fueron eliminados con exito!");
			redirect(base_url()."ServiciosExternos/");
		}else{
			$this->session->set_flashdata("error","Error al eliminar los datos!");
			redirect(base_url()."ServiciosExternos/");
        }
    }

    public function lista_externos_excel(){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nombre');
		$sheet->setCellValue('B1', 'Tipo de entidad');
		$sheet->setCellValue('C1', 'Proveedor');
		$sheet->setCellValue('D1', 'Descripción');
			
		$datos = $this->Externos_Model->obtenerExternos();
		$number = 1;
		$flag = 2;
		foreach($datos as $d){
			$sheet->setCellValue('A'.$flag, $d->nombreExterno);
			
			
            
            // Operaciones para datos adicionales
            if ($d->tipoEntidad == 1) {
                $medico = $this->Externos_Model->detalleExternoMedico($d->idEntidad);
                $sheet->setCellValue('B'.$flag, "Médico");
			    $sheet->setCellValue('C'.$flag, $medico->nombreMedico);
                
            }else{
                $tipoEntidad = "Otros proveedores";
                $medico = $this->Externos_Model->detalleExternoProveedor($d->idEntidad);
                $sheet->setCellValue('B'.$flag, "Otros proveedores");
			    $sheet->setCellValue('C'.$flag, $medico->empresaProveedor);
            }
            // Fin datos adicionales

            $sheet->setCellValue('D'.$flag, $d->descripcionExterno);				
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
		$sheet->getStyle('A1:D1')->getFont()->setBold(true);		
		//$sheet->getStyle('A1:D10')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:D10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'listado_externos'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
    }
    
    public function tipo_entidad(){
        if($this->input->is_ajax_request())
		{
            $id =$this->input->get("id");
            if($id =="1"){
                $datos = $this->Medico_Model->obtenerMedicos();
            }
            else{
                $datos = $this->Proveedor_Model->obtenerProveedores();
            }
			echo json_encode($datos);
		}
		else
		{
			echo "Error...";
		}
    }
    
}
