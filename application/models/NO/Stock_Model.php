<?php
class Stock_Model extends CI_Model {
    
    public function obtenerStocks(){
        $sql = "SELECT * FROM tbl_stocks";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarStock($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_stocks(nombreStock, nivelStock, descripcionStock) VALUES(?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idStock = $this->db->insert_id();
                return $idStock;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
        
    }
    
    public function actualizarStock($data = null){
        if($data != null){
            $sql = "UPDATE tbl_stocks SET nombreStock = ?, nivelStock = ?, descripcionStock = ? WHERE idStock = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }

    public function guardarDetalle($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_detalle_stock(idStock, idInsumo, stockInsumo, debeInsumo, fechaVencimiento) VALUES(?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }

    public function detalleStock($id = null){
        if($id != null){
            $sql = "SELECT m.codigoMedicamento, m.nombreMedicamento, ds.*
                    FROM tbl_detalle_stock AS ds
                    INNER JOIN tbl_medicamentos AS m ON(m.idMedicamento = ds.idInsumo)
                    WHERE ds.idStock = '$id' AND ds.estadoInsumo = '1' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function actualizarDetalleStock($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_stock SET stockInsumo = ?, debeInsumo = ?, fechaVencimiento = ? WHERE idDetalleStock = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }

    public function eliminarDetalle($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_stock SET stockInsumo = '0', debeInsumo = '0', estadoInsumo = '0' WHERE idDetalleStock = ?";
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
