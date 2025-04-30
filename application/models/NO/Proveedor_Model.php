<?php
class Proveedor_Model extends CI_Model {
    // Obtener departamentos
    public function obtenerProveedores(){
        $sql = "SELECT * FROM tbl_proveedores";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function ultimoCodigo(){
        $sql = "SELECT MAX(codigoProveedor) as codigo FROM tbl_proveedores";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarProveedor($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_proveedores(codigoProveedor, empresaProveedor, socialProveedor, nrcProveedor, nitProveedor, telefonoProveedor, direccionProveedor)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function actualizarProveedor($data = null ){
        if($data != null){
            $sql = "UPDATE tbl_proveedores SET codigoProveedor = ?, empresaProveedor = ?, socialProveedor = ?, nrcProveedor = ?,
                    nitProveedor = ?, telefonoProveedor = ?, direccionProveedor = ?
                        WHERE idProveedor = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function eliminarProveedor($id = null){
        if ($id != null ) {
            $sql = "DELETE FROM tbl_proveedores WHERE idProveedor = ?";
            if($this->db->query($sql, $id)){
                return true;
            }else{
                return false;
            }
        }
    }
    
}
?>