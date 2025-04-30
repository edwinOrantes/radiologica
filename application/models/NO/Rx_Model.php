<?php
class Rx_Model extends CI_Model {
    
    // Obtener ultimo codigo de gasto
    public function obtenerPacientes($f = null){
        $sql = "SELECT crx.idColaRx, crx.examenRx, p.nombrePaciente, p.edadPaciente, hc.fechaHoja, crx.estadoColaRx FROM tbl_cola_rx AS crx
            INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = crx.idHoja)
            INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
            WHERE DATE(crx.fechaColaRx) = '$f' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function saldarCola($data = null){
        if($data != null){
            $sql = "UPDATE tbl_cola_rx SET estadoColaRx = '0' WHERE idColaRx = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public function obtenerCodigo($data = null){
        if($data != null){
            $sql = "SELECT COALESCE(MAX(ra.secuenciaArchivado), 0) AS secuencia FROM tbl_rx_archivados AS ra
                    WHERE ra.perteneceArchivo = ? ";
            $datos = $this->db->query($sql, $data);
            return $datos->row();
        }
    }
    
    public function obtenerRxArchivados(){
        $sql = "SELECT * FROM tbl_rx_archivados WHERE estadoArchivado = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }
    
    public function obtenerDatosActuales($datos = null){
        if($datos != null){
            $sql = "SELECT * FROM tbl_rx_archivados AS rxa WHERE rxa.idArchivado = ?";
            $datos = $this->db->query($sql, $datos);
            return $datos->result();
        }
    }

    public function guardarResultado($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_rx_archivados(fechaArchivado, codigoArchivado, nombrePaciente, perteneceArchivo, secuenciaArchivado)
                    VALUES(?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarResultado($data = null){
        if($data != null){
            $sql = "UPDATE tbl_rx_archivados SET fechaArchivado = ?, codigoArchivado = ?, nombrePaciente = ?, perteneceArchivo = ?, secuenciaArchivado = ?
                    WHERE idArchivado = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
}
?>

