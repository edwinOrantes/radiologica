<?php
class Permisos_Model extends CI_Model {
    
    public function obtenerPermisos($id){
        $sql = "SELECT p.idPermiso, p.estadoPermiso, p.idMenu, m.nombreMenu, a.nombreAcceso, a.descripcionAcceso FROM tbl_permisos as p INNER JOIN 
                tbl_menu m ON(p.idMenu = m.idMenu) INNER JOIN tbl_accesos as a ON(p.idAcceso = a.idAcceso) WHERE p.idAcceso = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerNombreAcceso($id){
        $sql = "SELECT nombreAcceso FROM tbl_accesos WHERE idAcceso = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }
    

    public function obtenerMenus(){
        $sql = "SELECT * FROM tbl_menu ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarPermisos($data = null){
        if($data != null){
            if(isset($data["idPermisos"])){
                $permisos = $data["idPermisos"];
                $acceso = $data["idAcceso"];
                $estado = 1;
                for ($i=0; $i < sizeof($data["idPermisos"]) ; $i++) { 
                    $sql = "INSERT INTO tbl_permisos(idMenu, idAcceso, estadoPermiso) VALUES('$permisos[$i]', '$acceso', '$estado')";
                    $this->db->query($sql);
                }
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    public function eliminarPermiso($id = null){
        if($id != null){
            $sql = "UPDATE tbl_permisos SET estadoPermiso = 0 WHERE idPermiso = '$id' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function activarPermiso($id = null){
        if($id != null){
            $sql = "UPDATE tbl_permisos SET estadoPermiso = 1 WHERE idPermiso = '$id' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

   /*  public function guardarAcceso($data = null){
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
    } */
    
    
}
?>

