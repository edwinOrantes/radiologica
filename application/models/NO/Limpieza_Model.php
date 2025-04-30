<?php
class Limpieza_Model extends CI_Model {
    
    // Metodos para la gestion se insumos
        public function obtenerInsumo($id = null){
            if(!is_null($id)){
                $sql = "SELECT * FROM tbl_insumos_limpieza WHERE idInsumoLimpieza = '$id' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function obtenerInsumos(){
            $sql = "SELECT * FROM tbl_insumos_limpieza WHERE pivoteInsumoLimpieza = 1 ORDER BY idInsumoLimpieza ASC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCodigoIL(){
            $sql = "SELECT codigoInsumoLimpieza FROM tbl_insumos_limpieza WHERE idInsumoLimpieza = (SELECT MAX(idInsumoLimpieza) FROM tbl_insumos_limpieza ) ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function guardarInsumo($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_insumos_limpieza(codigoInsumoLimpieza, nombreInsumoLimpieza, precioInsumoLimpieza, marcaInsumoLimpieza, categoriaInsumoLimpieza, unidadInsumoLimpieza,  minimoInsumoLimpieza, stockInsumoLimpieza)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
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
                $sql = "UPDATE tbl_insumos_limpieza SET nombreInsumoLimpieza = ?, precioInsumoLimpieza = ?, marcaInsumoLimpieza = ?,
                        categoriaInsumoLimpieza = ?, unidadInsumoLimpieza = ?,  minimoInsumoLimpieza = ?, stockInsumoLimpieza = ? WHERE idInsumoLimpieza = ?";
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
                $sql = "UPDATE tbl_insumos_limpieza SET pivoteInsumoLimpieza = '0' WHERE idInsumoLimpieza = ?";
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
            $sql = "SELECT * FROM tbl_compras_limpieza";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function guardarCompra($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_compras_limpieza(tipoCompraLimpieza, documentoCompraLimpieza, numeroCompraLimpieza, nombreProveedor, fechaCompraLimpieza, plazoCompraLimpieza, 
                        descripcionCompraLimpieza)
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
            $sql = "SELECT  * FROM tbl_compras_limpieza WHERE idCompraLimpieza = '$id'";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function detalleContenidoCompra($id = null){
            $sql = "SELECT il.nombreInsumoLimpieza, dcl.* 
                    FROM tbl_dcompra_limpieza AS dcl
                    INNER JOIN tbl_insumos_limpieza AS il ON(dcl.idInsumo = il.idInsumoLimpieza)
                    WHERE dcl.idCompra = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function agregarDetalleCIL($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_dcompra_limpieza(idCompra, idInsumo, cantidad, precio, vencimiento) VALUES(?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    $detalleCL = $this->db->insert_id(); // Id fila detalle de la compra;
                    return $detalleCL;
                }else{
                    return 0;
                }
            }else{ 
                return 0;
            }
        }

        public function actualizarDatosIL($data = null){
            if($data != null){
                $sql = "UPDATE tbl_insumos_limpieza SET precioInsumoLimpieza = ?, stockInsumoLimpieza = ? WHERE idInsumoLimpieza = ?";
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

        public function stockIL($id = null){
            if(!is_null($id)){
                $sql = "SELECT * FROM tbl_insumos_limpieza WHERE idInsumoLimpieza = '$id' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function actualizarContenidoCompra($data = null){
            if($data != null){
                $detalle = $data["detalle"];
                $sql = "UPDATE tbl_dcompra_limpieza SET cantidad = ?, precio = ? WHERE idDCompraLimpieza = ? ";
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
                $insumo = $data["insumo"];
                $detalle = $data["detalle"];
                $sql = "DELETE FROM tbl_dcompra_limpieza WHERE idDCompraLimpieza = ? ";
                if($this->db->query($sql, $detalle)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }


        /* public function actualizarContenidoCompra($data = null){
            if($data != null){
                $insumo = $data["insumo"];
                $detalle = $data["detalle"];
                $sql = "UPDATE tbl_dcompra_limpieza SET cantidad = ?, precio = ? WHERE idDCompraLimpieza = ? ";
                $sql2 = "UPDATE tbl_insumos_limpieza SET precioInsumoLimpieza = ?, stockInsumoLimpieza = ? WHERE idInsumoLimpieza = ? ";
                if($this->db->query($sql, $detalle)){
                    if($this->db->query($sql2, $insumo)){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        } */

        /* public function eliminarContenidoCompra($data = null){
            if($data != null){
                $insumo = $data["insumo"];
                $detalle = $data["detalle"];
                $sql = "DELETE FROM tbl_dcompra_limpieza WHERE idDCompraLimpieza = ? ";
                $sql2 = "UPDATE tbl_insumos_limpieza SET stockInsumoLimpieza = ? WHERE idInsumoLimpieza = ? ";
                if($this->db->query($sql, $detalle)){
                    if($this->db->query($sql2, $insumo)){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        } */

        public function extrasCompra($data = null){
            if($data != null){
                $sql = "UPDATE tbl_compras_limpieza SET ivaPercibido = ?, descuentoCompraLimpieza = ?, otrosCompraLimpieza = ? WHERE idCompraLimpieza = ? ";
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
                $sql = "INSERT INTO tbl_salidas_limpieza(fechaCuenta) VALUES(?)";
                if($this->db->query($sql, $data)){
                    $idCuenta = $this->db->insert_id(); // Id del senso de este dia
                    return $idCuenta;
                }
            }else{
                return 0;
            }
        }

        public function listaCuentas(){
            $sql = "SELECT * FROM tbl_salidas_limpieza ORDER BY idCuenta DESC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerDetalleCuenta($id = null){
            $sql = "SELECT il.idInsumoLimpieza, il.nombreInsumoLimpieza, date(dl.fechaDescargo) AS fechaDescargo,
                    il.codigoInsumoLimpieza, dl.cantidadInsumo, dl.idDescargosLimpieza, dl.entregadoA FROM tbl_dsalidas_limpieza AS dl
                    INNER JOIN tbl_insumos_limpieza AS il ON(dl.idInsumo = il.idInsumoLimpieza) WHERE dl.idCuentaDescargo = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCuenta($id = null){
            $sql = "SELECT * FROM tbl_salidas_limpieza where idCuenta = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function obtenerInsumosReactivos(){
            $sql = "SELECT * FROM tbl_insumos_limpieza";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function buscarInsumo($id = null){
            $sql = "SELECT idInsumoLimpieza, codigoInsumoLimpieza, stockInsumoLimpieza, nombreInsumoLimpieza 
                    FROM tbl_insumos_limpieza WHERE idInsumoLimpieza = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function descontarInsumo($data = null){
            if($data != null){
                $cuenta = $data["cuenta"];
                $sql = "INSERT INTO tbl_dsalidas_limpieza(idCuentaDescargo, idInsumo, cantidadInsumo, por, entregadoA) VALUES(?, ?, ?, ?, ?)";
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
                $sql = "UPDATE tbl_insumos_limpieza SET stockInsumoLimpieza = ? WHERE idInsumoLimpieza = ? ";
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
                $sql = "UPDATE tbl_dsalidas_limpieza SET cantidadInsumo = ? WHERE idDescargosLimpieza = ? ";
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
            $sql = "SELECT * FROM tbl_dsalidas_limpieza WHERE idDescargosLimpieza = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function eliminarDetalle($data = null){
            if($data != null){
                $sql = "DELETE FROM tbl_dsalidas_limpieza WHERE idDescargosLimpieza = ? ";
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
                $sql = "UPDATE tbl_salidas_limpieza SET estadoCuenta = ? WHERE idCuenta = ? ";
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


    // Seguimiento de insumos
        public function compraInsumo($id = null, $fecha = null){
            $sql = "SELECT il.codigoInsumoLimpieza, il.nombreInsumoLimpieza, dcl.cantidad, dcl.precio, cl.fechaCompraLimpieza, 
                    SUM(dcl.cantidad) totalCompras FROM tbl_dcompra_limpieza AS dcl
                    INNER JOIN tbl_compras_limpieza cl ON(dcl.idCompra = cl.idCompraLimpieza)
                    INNER JOIN tbl_insumos_limpieza AS il ON(dcl.idInsumo = il.idInsumoLimpieza)
                    WHERE dcl.idInsumo = '$id' AND DATE(cl.fechaCompraLimpieza) >= '$fecha' GROUP BY dcl.idInsumo;";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function salidasInsumo($id = null, $fecha = null){
            $sql = "SELECT il.codigoInsumoLimpieza, il.nombreInsumoLimpieza, dsl.cantidadInsumo, SUM(dsl.cantidadInsumo) AS totalSalidas
                    FROM tbl_dsalidas_limpieza AS dsl
                    INNER JOIN tbl_salidas_limpieza AS sl ON(dsl.idCuentaDescargo = sl.idCuenta)
                    INNER JOIN tbl_insumos_limpieza AS il ON(dsl.idInsumo = il.idInsumoLimpieza)
                    WHERE dsl.idInsumo = '$id' AND DATE(sl.fechaCuenta) >= '$fecha'
                    GROUP BY dsl.idInsumo";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function fechaPivote(){
            $sql = "SELECT fechaPivote FROM tbl_insumos_limpieza LIMIT 1 ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    // Seguimiento de insumos
    
}
?>
