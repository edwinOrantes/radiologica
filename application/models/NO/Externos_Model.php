<?php
class Externos_Model extends CI_Model {

    public function obtenerExternos(){
        $sql = "SELECT * FROM tbl_externos";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerMedico($id){
        $sql = "SELECT * FROM tbl_medicos WHERE idMedico = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerProveedor($id){
        $sql = "SELECT * FROM tbl_proveedores WHERE idProveedor = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function detalleExternoMedico($id){
        $sql = "SELECT * FROM tbl_externos as t INNER JOIN tbl_medicos as m ON(t.idEntidad = m.idMedico) WHERE m.idMedico= ?";
        $datos = $this->db->query($sql, $id);
        return $datos->row();
    }
    
    public function detalleExternoProveedor($id){
        $sql = "SELECT * FROM tbl_externos as t INNER JOIN tbl_proveedores as p ON(t.idEntidad = p.idProveedor) WHERE t.tipoEntidad = 2 AND p.idProveedor = ?";
        $datos = $this->db->query($sql, $id);
        return $datos->row();
    }

    public function detalleExternoProveedor2($id){
        $sql = "SELECT * FROM tbl_proveedores WHERE idProveedor = ?";
        $datos = $this->db->query($sql, $id);
        return $datos->row();
    }

    // Guardar un paciente
    public function guardarExterno($data = null){
        if ($data != null) {
            $sql = "INSERT INTO tbl_externos(nombreExterno, tipoEntidad, idEntidad, descripcionExterno)
                    VALUES(?, ?, ?, ?)";
            if ($this->db->query($sql, $data)) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarExterno($data = null){
        if($data != null){
            $sql = "UPDATE tbl_externos SET nombreExterno = ?, descripcionExterno= ?
                            WHERE idExterno = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }


    public function eliminarExterno($id){
        $sql = "DELETE FROM tbl_externos WHERE idExterno = ?";
        if ($this->db->query($sql, $id)) {
            return true;
        }else{
            return false;
        }
    }

}
?>