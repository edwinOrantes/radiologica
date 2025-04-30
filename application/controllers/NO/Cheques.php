<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Libreria para impresora termica
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
    use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    use Mike42\Escpos\EscposImage;
// Libreria para impresora termica

class Cheques extends CI_Controller {

    public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/El_Salvador');
        $this->load->model("Cajeras_Model");
        $this->load->model("Reportes_Model");
        $this->load->model("Usuarios_Model");
		if (!$this->session->has_userdata('valido')){
			$this->session->set_flashdata("error", "Debes iniciar sesión");
			redirect(base_url());
		}
	}

    /* public function index(){
        $connector = new WindowsPrintConnector("Tally Dascom 1330");
        $printer = new Printer($connector);
        $printer ->feed(1);
        $printer ->feed(1);
        $printer ->feed(1);

        $printer ->feed(1);
        $printer ->feed(1);
        $printer->setTextSize(1, 1); // Tamaño de texto
        $a = str_pad(" ", 9, " ").str_pad("Edwin Alexander", 5, " ").str_pad(" ", 7, " ").str_pad("2024-10-11", 7, " ");
        $b = str_pad(" ", 9, " ").str_pad("Lorem ipsum dolor sit amet consectetur.", 20, " ");
        $c = str_pad(" ", 9, " ").str_pad("00000000-0", 20, " ");
        // $printer->setUnderline(Printer::UNDERLINE_NONE); // Sin subrayado
        $printer->text($a."\n");
        $printer->text($b."\n");
        $printer->text($c."\n");
        $printer ->feed(1);
        $printer ->feed(1);
        $printer ->feed(1);
        $printer ->feed(1);
        $printer ->feed(1);
        $medicamentos = str_pad("1", 7, " ").str_pad("Medicamentos e insumos médicos", 25, " ").str_pad(" ", 4, " ").str_pad("$1000.00", 7, " ").str_pad(" ", 11, " ").str_pad("$1000.00", 7, " ");
        $printer->text($medicamentos."\n");
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
        $printer->feed(1);
        $son = str_pad("TRES MIL DOSCIENTO OCHETA Y NUEVE", 20, " ");
        $printer->text($son."\n");

        $gravado = str_pad(" ", 60, " ").str_pad("1000.000", 20, " ");
        $printer->text($gravado."\n");

        $printer->feed(1);

        $paciente = str_pad(" ", 11, " ").str_pad("Edwin Alexander", 20, " ");
        $printer->text($paciente."\n");
        $dui = str_pad(" ", 11, " ").str_pad("00000000-0", 20, " ");
        $printer->text($dui."\n");

        $printer->feed(1);

        $total = str_pad(" ", 60, " ").str_pad("$1000.000", 20, " ");
        $printer->text($total."\n");
        

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
        $printer ->feed(1);
        // Cortar el papel (si es una impresora con soporte para corte)
        $printer->cut();
        
        // Abrir la caja de dinero
        $printer->pulse();
        
        // Cierra la conexión
        $printer->close();
    } */

    public function index(){
        try {
            $connector = new WindowsPrintConnector("Tally Dascom 1330");
            $printer = new Printer($connector);
            $printer->feed(1);
            $printer->feed(1);
            $printer->feed(1);
            $printer->feed(1);
            

            // Encabezados de la tabla
            $headers = [" ", " ", " ", " ", " "];
            $dui = str_pad(" ", 3, " ").str_pad("00000000-0", 20, " ");
            $fecha = str_pad(" ", 5, " ").str_pad("2024-05-06", 20, " ");
            // Filas de la tabla
            $rows = [
                [" ", "Edwin Orantes", $fecha, " ", " "],
                [" ", "Another long text here long text here", " ", " ", " "],
                [" ", $dui, " ", " ", " "]
            ];

            // Imprimir tabla
            $this->printTable($printer, $headers, $rows);

            $printer->feed(1);
            $printer->feed(1);
            $printer->feed(1);
            $printer->feed(1);
            $rows = [
                ["1", "Medicamentos e insumos médicos", "$5000.00", " ", "$5000.00"],
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
            $rows = [
                ["", "Mil doscientos veinti cuatro", " ", " ", "$5000.00"],
            ];
            $this->printTable($printer, $headers, $rows);
            $printer->feed(1);
            $cliente = str_pad(" ", 6, " ").str_pad("Edwin Orantes", 6, " ").str_pad("", 20, " ");
            $documento = str_pad(" ", 9, " ").str_pad("00000000-0", 20, " ");

            $rows = [
                ["", $cliente, " ", " ", " "],
                ["", $documento, " ", " ", " "],
            ];
            $this->printTable($printer, $headers, $rows);
            // $printer->feed(1);
            $rows = [
                ["", " ", " ", " ", "5000.00"],
            ];
            $this->printtable($printer, $headers, $rows);
            
            

            // $printer->cut();
            $printer->close();
        } catch (Exception $e) {
            echo "No se pudo imprimir: " . $e->getMessage() . "\n";
        }
    }

    // Para factura
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
    
}


