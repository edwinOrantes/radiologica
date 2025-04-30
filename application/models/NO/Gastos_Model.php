<?php
class Gastos_Model extends CI_Model {
    
    // Obtener ultimo codigo de gasto
    public function codigoGasto(){
        $sql = "SELECT MAX(codigoGasto) as codigo FROM tbl_gastos";
        $datos = $this->db->query($sql);
        return $datos->row();
    }
    
    public function obtenerClasificacion(){
        $sql = "SELECT * FROM tbl_clasificacion_cg";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerCategorias(){
        $sql = "SELECT * FROM tbl_categorias_gastos as cg where cg.estadoCategoria = '1'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerCuentas(){
        $sql = "SELECT cg.idCuenta, cg.nombreCuenta, cg.descripcionCuenta, cg.clasificacionCuenta, ccg.nombreCG FROM tbl_cuentas_gastos as cg 
                INNER JOIN tbl_clasificacion_cg as ccg on(cg.clasificacionCuenta = ccg.idClasificacionCG) ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarCuenta($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_cuentas_gastos(nombreCuenta, clasificacionCuenta, descripcionCuenta)
                    VALUES(?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function actualizarCuenta($data = null ){
        if($data != null){
            $sql = "UPDATE tbl_cuentas_gastos SET nombreCuenta = ?,  clasificacionCuenta = ?, 
                        descripcionCuenta = ? WHERE idCuenta = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function eliminarCuenta($id = null){
        if ($id != null ) {
            $sql = "DELETE FROM tbl_cuentas_gastos WHERE idCuenta = ?";
            if($this->db->query($sql, $id)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function obtenertipoGasto(){
        $sql = "SELECT * FROM tbl_tipo_gasto ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }
    
    public function guardarGasto($data = null){
        if($data != null){
            if(isset($data["chequeGasto"])){
                $sql = "INSERT INTO tbl_gastos(tipoGasto, montoGasto, entregadoGasto, idCuentaGasto, fechaGasto, categoriaGasto, entidadGasto, idProveedorGasto, pagoGasto, 
                        numeroGasto, descripcionGasto, codigoGasto, efectuoGasto, accesoGasto)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            }else{
                $sql = "INSERT INTO tbl_gastos(tipoGasto, montoGasto, entregadoGasto, idCuentaGasto, fechaGasto, categoriaGasto, entidadGasto, idProveedorGasto, 
                        pagoGasto, descripcionGasto, codigoGasto, efectuoGasto, accesoGasto)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            }
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarCuentaXPagar($cuenta = null, $gasto = null){
        $sql = "UPDATE tbl_cuentas_pagar SET idGasto = '$gasto' WHERE idCuentaPagar = '$cuenta' ";
        $this->db->query($sql);
    }

    public function guardarGasto2($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_gastos(tipoGasto, montoGasto, entregadoGasto, idCuentaGasto, fechaGasto, entidadGasto, idProveedorGasto, pagoGasto, descripcionGasto, codigoGasto, efectuoGasto)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idGasto = $this->db->insert_id(); // Id del gasto
                return $idGasto;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerGastos($i = null, $f = null, $acceso = null){
        $strAnexa = "";
        if($acceso == 10){
            $strAnexa = "AND g.accesoGasto = '$acceso' ";
        }else{
            $strAnexa = "";
        }
        $sql = "SELECT tg.nombreTipoGasto, ccg.idClasificacionCG, cg.idCuenta, cg.nombreCuenta, g.*
                FROM tbl_gastos AS g
                INNER JOIN tbl_tipo_gasto AS tg ON(g.tipoGasto = tg.idTipoGasto)
                INNER JOIN tbl_cuentas_gastos as cg ON(g.idCuentaGasto = cg.idCuenta)
                INNER JOIN tbl_clasificacion_cg AS ccg ON(cg.clasificacionCuenta = ccg.idClasificacionCG)
                WHERE ccg.idClasificacionCG != 5 $strAnexa AND g.fechaGasto BETWEEN '$i' AND '$f' ORDER BY g.idCuentaGasto DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function totalGastos($i, $f){
        $sql = "SELECT SUM(g.montoGasto) AS total
                FROM tbl_gastos AS g
                INNER JOIN tbl_tipo_gasto AS tg ON(g.tipoGasto = tg.idTipoGasto)
                INNER JOIN tbl_cuentas_gastos as cg ON(g.idCuentaGasto = cg.idCuenta)
                INNER JOIN tbl_clasificacion_cg AS ccg ON(cg.clasificacionCuenta = ccg.idClasificacionCG)
                WHERE ccg.idClasificacionCG != 5 AND g.fechaGasto BETWEEN  '$i' AND '$f' ORDER BY g.idCuentaGasto DESC;";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerGasto($id){
        $sql = "SELECT * FROM tbl_gastos WHERE idGasto = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerProveedor($id = null){
        $sql = "SELECT * FROM tbl_proveedores WHERE idProveedor = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function sumaGastos($i, $f){
        $sql = "SELECT SUM(montoGasto) as totalGasto FROM tbl_gastos WHERE fechaGasto BETWEEN '$i' AND '$f' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerEntidadGasto($id, $e){
        $sql1 = "SELECT nombreMedico as proveedor FROM tbl_medicos WHERE idMedico = '$id' ";
        $sql2 = "SELECT empresaProveedor as proveedor FROM tbl_proveedores WHERE idProveedor = $id";
        if($e == 1){
            $datos = $this->db->query($sql1);
            return $datos->row();
        }else{
            $datos = $this->db->query($sql2);
            return $datos->row();
        }
    }

    public function actualizarGastos($data = null){
        if($data != null){
            if(isset($data["numeroGasto"])){
                $sql = "UPDATE tbl_gastos SET tipoGasto = ?, montoGasto = ?, entregadoGasto = ?, idCuentaGasto = ?, fechaGasto = ?, categoriaGasto = ?,
                    entidadGasto = ?, idProveedorGasto = ?, pagoGasto = ?, numeroGasto = ?, bancoGasto = ?, cuentaGasto = ?, descripcionGasto = ? WHERE idGasto = ? ";
            }else{
                $sql = "UPDATE tbl_gastos SET tipoGasto = ?, montoGasto = ?, entregadoGasto = ?, idCuentaGasto = ?, fechaGasto = ?, categoriaGasto = ?,
                    entidadGasto = ?, idProveedorGasto = ?, pagoGasto = ?, descripcionGasto = ? WHERE idGasto = ? ";
            }
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarCuentaPorPagar($gasto, $total){
        $sql = "UPDATE tbl_cuentas_pagar SET subtotalCuentaPagar = '$total', totalCuentaPagar = '$total' WHERE idGasto = '$gasto' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function buscarCodigo($codigo){
        $sql = "SELECT codigoGasto FROM tbl_gastos WHERE codigoGasto = '$codigo' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function ultimoGasto(){
        $sql = "SELECT MAX(codigoGasto) AS ultimo FROM tbl_gastos";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function eliminarGasto($data = null){
        if($data != null){
            // $sql = "DELETE FROM tbl_gastos WHERE idGasto = ?";
            $sql = "UPDATE tbl_gastos SET eliminadoPor = ?, estadoGasto = ? WHERE idGasto = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    // Resumen para dashboard
    public function resumenCuentas($i, $f){
        /* $sql = "SELECT SUM(g.montoGasto) AS totalCuenta, cg.idCuenta, cg.nombreCuenta, tg.nombreTipoGasto, g.* FROM tbl_gastos as g INNER JOIN tbl_tipo_gasto
        AS tg ON(g.tipoGasto = tg.idTipoGasto) INNER JOIN tbl_cuentas_gastos as cg ON(g.idCuentaGasto = cg.idCuenta) WHERE g.fechaGasto
        BETWEEN '$i' AND '$f' AND g.pagoGasto != 2 AND g.idCuentaGasto != 10 GROUP BY cg.idCuenta ORDER BY g.codigoGasto DESC"; */
        /* $sql = "SELECT SUM(g.montoGasto) AS totalCuenta, cg.idCuenta, cg.nombreCuenta, g.* FROM tbl_gastos as g INNER JOIN tbl_cuentas_gastos
                as cg ON(g.idCuentaGasto = cg.idCuenta) WHERE g.fechaGasto BETWEEN '$i' AND '$f' GROUP BY cg.idCuenta 
                ORDER BY cg.idCuenta DESC"; */
        $sql = "SELECT SUM(g.montoGasto) AS totalCuenta, cg.idCuenta, cg.nombreCuenta, g.* 
                FROM tbl_gastos as g 
                INNER JOIN tbl_cuentas_gastos as cg ON(g.idCuentaGasto = cg.idCuenta)
                INNER JOIN tbl_clasificacion_cg AS ccg ON(cg.clasificacionCuenta = ccg.idClasificacionCG)
                WHERE cg.clasificacionCuenta != 5 AND g.fechaGasto BETWEEN '$i' AND '$f' GROUP BY cg.idCuenta 
                ORDER BY cg.idCuenta DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerDetalleCuenta($data = null){
        $id = $data["id"];
        $i = $data["i"];
        $f = $data["f"];
        $sql = "SELECT * FROM tbl_gastos WHERE idCuentaGasto = '$id' AND fechaGasto BETWEEN '$i' AND '$f'  ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function test(){
        $sql = "SELECT cg.nombreCuenta, cg.clasificacionCuenta ,ccg.nombreCG FROM tbl_cuentas_gastos AS cg INNER JOIN tbl_clasificacion_cg AS ccg ON(cg.clasificacionCuenta = ccg.idClasificacionCG)";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Metodos para efectuar cheques
        public function obtenerBancos(){
            $sql = "SELECT * FROM tbl_bancos";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCuentasBancos(){
            $sql = "SELECT * FROM tbl_cuentas_bancos";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCuentaBanco($id = null){
            if($id != null){
                $sql = "SELECT * FROM tbl_cuentas_bancos AS cb
                        INNER JOIN tbl_bancos AS b ON(b.idBanco = cb.idBanco)
                        WHERE cb.idCuenta = '$id' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }
    // Metodos para efectuar cheques
    
}
?>
