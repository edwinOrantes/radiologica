<?php
class InsumosHemo_Model extends CI_Model {
    
    // Metodos para la gestion se insumos
        public function obtenerInsumo($id = null){
            if(!is_null($id)){
                $sql = "SELECT * FROM tbl_insumos_hemo WHERE idInsumoHemo = '$id' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function obtenerInsumos(){
            $sql = "SELECT * FROM tbl_insumos_hemo WHERE pivoteInsumoHemo = 1 ORDER BY idInsumoHemo ASC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCodigoIH(){
            $sql = "SELECT codigoInsumoHemo FROM tbl_insumos_hemo WHERE idInsumoHemo = (SELECT MAX(idInsumoHemo) FROM tbl_insumos_hemo ) ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function guardarInsumo($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_insumos_hemo(codigoInsumoHemo, nombreInsumoHemo, precioInsumoHemo, minimoInsumoHemo, stockInsumoHemo)
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
        
        public function actualizarInsumo($data = null){
            if($data != null){
                $sql = "UPDATE tbl_insumos_hemo SET nombreInsumoHemo = ?, precioInsumoHemo = ?,  minimoInsumoHemo = ?, stockInsumoHemo = ? WHERE idInsumoHemo = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function eliminarInsumo($data = null){
            if($data != null){
                $sql = "UPDATE tbl_insumos_hemo SET pivoteInsumoHemo = '0' WHERE idInsumoHemo = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    // Metodos para la gestion se insumos

    // Metodos para la gestion de compras
        public function listaCompras(){
            $sql = "SELECT p.empresaProveedor, ch.* FROM tbl_compras_hemo AS ch
                    INNER JOIN tbl_proveedores AS p ON(ch.idProveedor = p.idProveedor)";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function guardarCompra($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_compras_hemo(tipoCompraHemo, documentoCompraHemo, numeroCompraHemo, idProveedor, fechaCompraHemo, plazoCompraHemo, 
                        descripcionCompraHemo)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    $idFactura = $this->db->insert_id(); // Id de la factura de compra.
                    return $idFactura;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

        public function detalleCabeceraCompra($id = null){
            $sql = "SELECT p.empresaProveedor, ch.* FROM tbl_compras_hemo as ch
                    INNER JOIN tbl_proveedores AS p ON(ch.idProveedor = p.idProveedor)
                    WHERE ch.idCompraHemo = '$id'";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function detalleContenidoCompra($id = null){
            $sql = "SELECT il.nombreInsumoHemo, dcl.* FROM tbl_dcompra_hemo AS dcl INNER JOIN tbl_insumos_hemo AS il ON(dcl.idInsumo = il.idInsumoHemo)
                    WHERE dcl.idCompra = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function agregarDetalleCIH($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_dcompra_hemo(idCompra, idInsumo, cantidad, precio, vencimiento)
                    VALUES(?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    $detalleCH = $this->db->insert_id(); // Id fila detalle de la compra;
                    return $detalleCH;
                }else{
                    return 0;
                }
            }else{ 
                return 0;
            }
        }

        public function actualizarDatosIL($data = null){
            if($data != null){
                $sql = "UPDATE tbl_insumos_hemo SET precioInsumoHemo = ?, stockInsumoHemo = ? WHERE idInsumoHemo = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function borrarDetalleCIL($id = null){
            $sql = "DELETE FROM tbl_dcompra_limpieza WHERE idDCompraLimpieza = '$id' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }

        public function stockIH($id = null){
            if(!is_null($id)){
                $sql = "SELECT * FROM tbl_insumos_hemo WHERE idInsumoHemo = '$id' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function actualizarContenidoCompra($data = null){
            if($data != null){
                $detalle = $data["detalle"];
                $sql = "UPDATE tbl_dcompra_hemo SET cantidad = ?, precio = ? WHERE idDCompraHemo = ? ";
                if($this->db->query($sql, $detalle)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function eliminarContenidoCompra($data = null){
            if($data != null){
                $detalle = $data["detalle"];
                $sql = "DELETE FROM tbl_dcompra_hemo WHERE idDCompraHemo = ? ";
                if($this->db->query($sql, $detalle)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function extrasCompra($data = null){
            if($data != null){
                $sql = "UPDATE tbl_compras_hemo SET ivaPercibido = ?, descuentoCompraHemo = ?, otrosCompraHemo = ? WHERE idCompraHemo = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
                
        }

    // Metodos para la gestion de compras

    // Metodos para agregar descargos
        public function crearCuenta($data = null){
            if ($data != null){
                $sql = "INSERT INTO tbl_salidas_hemo(fechaCuenta) VALUES(?)";
                if($this->db->query($sql, $data)){
                    $idCuenta = $this->db->insert_id(); // Id del senso de este dia
                    return $idCuenta;
                }
            }else{
                return 0;
            }
        }

        public function listaCuentas(){
            $sql = "SELECT * FROM tbl_salidas_hemo ORDER BY idCuenta DESC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerDetalleCuenta($id = null){
            $sql = "SELECT il.idInsumoHemo, il.nombreInsumoHemo, date(dsh.fechaDescargo) AS fechaDescargo,
                    il.codigoInsumoHemo, dsh.cantidadInsumo, dsh.idDescargosHemo, dsh.entregadoA FROM tbl_dsalidas_hemo AS dsh
                    INNER JOIN tbl_insumos_hemo AS il ON(dsh.idInsumo = il.idInsumoHemo) WHERE dsh.idCuentaDescargo = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCuenta($id = null){
            $sql = "SELECT * FROM tbl_salidas_hemo where idCuenta = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        /* public function obtenerInsumosReactivos(){
            $sql = "SELECT * FROM tbl_insumos_hemo";
            $datos = $this->db->query($sql);
            return $datos->result();
        } */

        public function buscarInsumo($id = null){
            $sql = "SELECT idInsumoHemo, codigoInsumoHemo, stockInsumoHemo, nombreInsumoHemo 
                    FROM tbl_insumos_hemo WHERE idInsumoHemo = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function descontarInsumo($data = null){
            if($data != null){
                $cuenta = $data["cuenta"];

                $sql = "INSERT INTO tbl_dsalidas_hemo(idCuentaDescargo, idInsumo, cantidadInsumo, por, entregadoA) VALUES(?, ?, ?, ?, ?)";
                
                if($this->db->query($sql, $cuenta)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function editarCuenta($data = null){
            if($data != null){
                $sql = "UPDATE tbl_insumos_hemo SET stockInsumoHemo = ? WHERE idInsumoHemo = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function editarFilaDescargo($data = null){
            if($data != null){
                $sql = "UPDATE tbl_dsalidas_hemo SET cantidadInsumo = ? WHERE idDescargosHemo = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function seleccionarDetalle($id){
            $sql = "SELECT * FROM tbl_dsalidas_hemo WHERE idDescargosHemo = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function eliminarDetalle($data = null){
            if($data != null){
                $sql = "DELETE FROM tbl_dsalidas_hemo WHERE idDescargosHemo = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function cerrarCuentaDescargos($data = null){
            if($data != null){
                $sql = "UPDATE tbl_salidas_hemo SET estadoCuenta = ? WHERE idCuenta = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    // Metodos para agregar descargos
    
}
?>
