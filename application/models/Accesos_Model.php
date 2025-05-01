<?php
class Accesos_Model extends CI_Model {
    
    // Obtener ultimo codigo de gasto
    public function obtenerAccesos(){
        $sql = "SELECT * FROM tbl_accesos";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarAcceso($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_accesos(nombreAcceso, descripcionAcceso, estadoAcceso) VALUES(?, ?, '1')";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return null;
        }
    }

    public function actualizarAcceso($data = null){
        if($data != null){
            $sql = "UPDATE tbl_accesos SET nombreAcceso = ?, descripcionAcceso = ? WHERE idAcceso = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return null;
        }
    }

    public function eliminarAcceso($data = null){
        if($data != null){
            $sql = "DELETE FROM tbl_accesos WHERE idAcceso = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return null;
        }
    }
    
    
}
?>

