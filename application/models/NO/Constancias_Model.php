<?php
class Constancias_Model extends CI_Model {

    // Guardar constancia
        public function guardarConstancia($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_constancia(pacienteConstancia, sexoConstancia, edadConstancia, diaConstancia, mesConstancia, encargadoConstancia)
                        VALUES(?, ?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    $idConstancia = $this->db->insert_id(); // Id de la constancia insertada
                    return $idConstancia;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

    // Guardar constancia
    public function actualizarConstancia($data = null){
        if($data != null){
            $sql = "UPDATE tbl_constancia SET pacienteConstancia = ?, sexoConstancia = ?, edadConstancia = ?, diaConstancia = ?, mesConstancia = ?
                    WHERE idConstancia = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function eliminarConstancia($data = null){
        if($data != null){
            $sql = "DELETE FROM tbl_constancia WHERE idConstancia = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    // Obtener constancias
        public function obtenerConstancias(){
            $sql = "SELECT * FROM tbl_constancia";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    
    // Obtener una constancia
        public function obtenerConstancia($id){
            $sql = "SELECT * FROM tbl_constancia WHERE idConstancia = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

}
?>
