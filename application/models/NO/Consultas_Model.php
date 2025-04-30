<?php
class Consultas_Model extends CI_Model {
    // Metodos para extraer lista de pacientes por atender
        public function consultasPendientesHoy($fecha, $destino, $turno){
            $sql = "SELECT hc.correlativoSalidaHoja, llp.* FROM tbl_llegada_pacientes AS llp LEFT JOIN tbl_hoja_cobro AS hc ON(llp.idHoja = hc.idHoja)
                    WHERE DATE(llp.fechaLlegada) = '$fecha' AND llp.destinoLlegada = '$destino' AND llp.turno = '$turno' ORDER BY llp.turno asc ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function liberarCupo($cupo){
            $sql = "UPDATE tbl_llegada_pacientes SET estadoLlegada = '0' WHERE idLlegada = '$cupo' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }

        public function regresarCupo($cupo){
            $sql = "UPDATE tbl_llegada_pacientes SET estadoLlegada = '1' WHERE idLlegada = '$cupo' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }
    
}
?>

