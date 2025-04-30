<?php
class Anuncio_Model extends CI_Model {
    
    // Obtener ultimo codigo de gasto
    public function obtenerUsuarios(){
        $sql = "SELECT idUsuario, nombreUsuario FROM tbl_usuarios";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarAnuncio($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_anuncios(tituloAnuncio, detalleAnuncio, fechaAnuncio, estadoAnuncio) VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{  
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerAnuncios(){
        $sql = "SELECT * FROM tbl_anuncios";
        $datos = $this->db->query($sql);
        return $datos->result();
    }
    
    public function eliminarAnuncio($data = null){
        if($data != null){
            $sql = "UPDATE tbl_anuncios SET estadoAnuncio = ? WHERE idAnuncio = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerUsuariosAnuncio($id){
        $sql = "SELECT u.idUsuario, u.nombreUsuario FROM tbl_usuario_anuncio AS ua INNER JOIN tbl_usuarios AS u ON(ua.idUsuario = u.idUsuario)
                WHERE ua.idAnuncio = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }
    
}
?>

