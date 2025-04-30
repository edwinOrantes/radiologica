<?php
class Medico_Model extends CI_Model {
    // Obtener departamentos
    public function obtenerMedicos(){
        $sql = "SELECT * FROM tbl_medicos";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function actualizarMedico($data = null ){
        if($data != null){
            $sql = "UPDATE tbl_medicos SET nombreMedico = ?, especialidadMedico = ?, telefonoMedico = ?, direccionMedico = ? 
                    WHERE idMedico = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function guardarMedico($data = null){
        if($data != null){
            $medico = $data["medico"];
            $externo = $data["externo"];
            // Datos del servicio externo.
                $nombre = $externo["medico"]."(Honorarios)";
                $entidad = $externo["tipoEntidad"];
                $descripcion = $externo["descripcionExterno"];

            $sql = "INSERT INTO tbl_medicos(nombreMedico, especialidadMedico, telefonoMedico, direccionMedico)
                    VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $medico)){
                $idMedico = $this->db->insert_id(); // Id del medico.
                $sql2 = "INSERT INTO tbl_externos(nombreExterno, tipoEntidad, idEntidad, descripcionExterno)
                         VALUES('$nombre', '$entidad', '$idMedico', '$descripcion')";
                $this->db->query($sql2);
                return true;
            }else{
                return false;
            }
        }
    }

    public function eliminarMedico($id = null){
        if ($id != null ) {
            $sql = "DELETE FROM tbl_medicos WHERE idMedico = ?";
            if($this->db->query($sql, $id)){
                return true;
            }else{
                return false;
            }
        }
    }
    
}
?>