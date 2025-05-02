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

    public function busquedaDetalleExamen($examen = null){
        if($examen != null){
            $sql = "SELECT * FROM tbl_examenes AS e WHERE e.nombreExamen = '$examen' AND e.estadoExamen = '1' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    
}
?>

