<?php
class Consulta_Model extends CI_Model {

    public function listaExamenes(){
        $sql = "SELECT * FROM tbl_examenes AS e WHERE e.estadoExamen = '1' ";
        $datos = $this->db->query($sql);
        return $datos->result();

    }
    public function detalleConsulta($consulta = null){
        if($consulta != null){
            $sql = "SELECT m.nombreMedico, c.* FROM tbl_consulta AS c
                    INNER JOIN tbl_medicos AS m ON m.idMedico = c.idMedico
                    WHERE c.idConsulta = '$consulta' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

    }
    
    public function guardarConsulta($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_consulta(nombrePaciente, idMedico, tipoReferencia, fechaConsulta) VALUES(?, ?, ?, NOW())";
            if($this->db->query($sql, $data)){
                $idConsulta = $this->db->insert_id(); // Id de la hoja de cobro
                return $idConsulta;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function buscarExamen($str = null){
        if($str != null){
            $sql = "SELECT * FROM tbl_examenes AS e WHERE e.nombreExamen LIKE '%$str%' AND e.estadoExamen = '1' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function busquedaDetalleExamen($examen = null, $referencia = null){
        if($examen != null){
            $sql = "";
            if($referencia == "Publica"){
                $sql .= "SELECT e.idExamen, e.nombreExamen, e.precioPublico as precioFinal";
            }else{
                $sql .= "SELECT e.idExamen, e.nombreExamen, e.precioPrivado as precioFinal";
            }
            $sql .= " FROM tbl_examenes AS e WHERE e.nombreExamen = '$examen' AND e.estadoExamen = '1' ";

            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function obtenerNumeroFactura($pivote = null){
        $sql = "";
        if($pivote != null){
            switch ($pivote) {
                case '1':
                    $sql = "SELECT COALESCE(MAX(t.numeroTicket),0) AS numero  FROM tbl_tickets AS t
                    WHERE t.idTicket = (SELECT MAX(idTicket) FROM tbl_tickets )";
                    break;
                case '2':
                    $sql = "SELECT COALESCE(MAX(cf.numeroConsumidorFinal), 0) AS numero  FROM tbl_consumidor_final AS cf
                    WHERE cf.idConsumidorFinal = (SELECT MAX(idConsumidorFinal) FROM tbl_consumidor_final )";
                    break;
                case '22':
                    $sql = "SELECT COALESCE(MAX(cf.numeroConsumidorFinal), 0) AS numero  FROM tbl_consumidor_final AS cf
                    WHERE cf.idConsumidorFinal = (SELECT MAX(idConsumidorFinal) FROM tbl_consumidor_final )";
                    break;
                case '3':
                    $sql = "SELECT COALESCE(MAX(cf.numeroCreditoFiscal), 0) AS numero  FROM tbl_credito_fiscal AS cf
                    WHERE cf.idCreditoFiscal = (SELECT MAX(idCreditoFiscal) FROM tbl_credito_fiscal )";
                    break;
                default:
                    $sql = "";
                    break;
            }
        }
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function validarNumeroActual($n = null, $pivote = null){
        if($pivote != null){
            $sql = "";
            
            switch ($pivote) {
                case '2':
                    $sql = "SELECT COALESCE((SELECT cf.numeroConsumidorFinal FROM tbl_consumidor_final AS cf  WHERE cf.numeroConsumidorFinal = '$n' 
                            AND cf.anio = YEAR(CURDATE()) LIMIT 1), 0) AS codigo"; 
                    break;
                case '3':
                    $sql = "SELECT COALESCE((SELECT cf.numeroCreditoFiscal FROM tbl_credito_fiscal AS cf  WHERE cf.numeroCreditoFiscal = '$n' 
                            AND cf.anio = YEAR(CURDATE()) LIMIT 1), 0) AS codigo"; 
                    break;
                
                default:
                    # code...
                    break;
            }

            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function maximoActual($p = null){
        // $sql = "SELECT MAX(v.numeroDocumento) AS codigo FROM tbl_ventas AS v WHERE v.tipoDocumento = '$p' ";
        switch ($p) {
            case '2':
                $sql = "SELECT MAX(cf.numeroConsumidorFinal) AS codigo FROM tbl_consumidor_final AS cf WHERE cf.anio = YEAR(CURDATE())";
                break;
            case '3':
                $sql = "SELECT MAX(cf.numeroCreditoFiscal) AS codigo FROM tbl_credito_fiscal AS cf WHERE cf.anio = YEAR(CURDATE())";
                break;
            
            default:
                # code...
                break;
        }
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    
}
?>

