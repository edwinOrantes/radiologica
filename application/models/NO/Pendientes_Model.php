<?php
class Pendientes_Model extends CI_Model {
    
    public function obtenerProveedor($id){
        $sql = "SELECT idProveedor, empresaProveedor, nrcProveedor FROM tbl_proveedores WHERE idProveedor = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function cuentasProveedor($id){
        $sql = "SELECT p.empresaProveedor, cp.* FROM tbl_cuentas_pagar as cp INNER JOIN tbl_proveedores AS p ON(cp.idProveedor = p.idProveedor ) 
        WHERE  cp.idProveedor = '$id' AND cp.facturaCuentaPagar != '---' AND cp.estadoCuentaPagar = 1 ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function cuentaProveedor($id){
        $sql = "SELECT p.empresaProveedor, cp.* FROM tbl_cuentas_pagar as cp INNER JOIN tbl_proveedores AS p ON(cp.idProveedor = p.idProveedor )
                WHERE cp.idCuentaPagar = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerCuentasPagar(){
        $sql = "SELECT p.empresaProveedor, cp.* FROM tbl_cuentas_pagar as cp 
                INNER JOIN tbl_proveedores AS p ON(cp.idProveedor = p.idProveedor )
                ORDER BY cp.idCuentaPagar DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarCuentaPagar($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_cuentas_pagar(idProveedor, fechaCuentaPagar, nrcCuentaPagar, facturaCuentaPagar, plazoCuentaPagar,
                    subtotalCuentaPagar, ivaCuentaPagar, perivaCuentaPagar, notaCredito, totalCuentaPagar, estadoCuentaPagar, pivote)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idCuenta = $this->db->insert_id(); // Id de la cuenta por pagar
                return $idCuenta;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function saldarCuentas($data = null){
        if($data != null){

            $gasto = $data["gasto"];
            $cuentas = $data["cuentas"];

            unset($data["cuentas"]);
            
            $sql = "INSERT INTO tbl_gastos(tipoGasto, montoGasto, entregadoGasto, idCuentaGasto, fechaGasto, categoriaGasto, entidadGasto, idProveedorGasto,
                    pagoGasto, numeroGasto, bancoGasto, cuentaGasto, descripcionGasto, codigoGasto, flagGasto, efectuoGasto, banco, cuenta, accesoGasto)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if($this->db->query($sql, $gasto)){
                $idGasto = $this->db->insert_id(); // Id de la cuenta de gasto
                $flag = 0;
                for($i=0; $i < sizeof($cuentas); $i++) { 
                    $sql2 = "UPDATE tbl_cuentas_pagar SET estadoCuentaPagar = '0', idGasto = '$idGasto' WHERE idCuentaPagar = '$cuentas[$i]' ";
                    if($this->db->query($sql2)){
                        $flag++;
                    }
                }
                if(sizeof($cuentas) == $flag){
                    return $idGasto;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function detalleGasto($id){
        $sql = "SELECT cp.idCuentaPagar, cp.fechaCuentaPagar, cp.facturaCuentaPagar, cp.totalCuentaPagar, p.empresaProveedor, g.numeroGasto, g.descripcionGasto
                FROM tbl_cuentas_pagar AS cp LEFT JOIN tbl_proveedores as p ON(cp.idProveedor = p.idProveedor) INNER JOIN tbl_gastos AS g 
                ON(cp.idGasto = g.idGasto) WHERE cp.idGasto = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function rowProveedor($id){
        $sql = "SELECT idProveedor, empresaProveedor, nrcProveedor FROM tbl_proveedores WHERE idProveedor = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function cuentasPorProveedor($id){
        $sql = "SELECT p.empresaProveedor, cp.* FROM tbl_cuentas_pagar as cp INNER JOIN tbl_proveedores AS p ON(cp.idProveedor = p.idProveedor )
                WHERE cp.idProveedor = '$id'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function cuentasPorProveedor2($id, $e){
        $sql = "SELECT p.empresaProveedor, cp.* FROM tbl_cuentas_pagar as cp INNER JOIN tbl_proveedores AS p ON(cp.idProveedor = p.idProveedor )
                WHERE cp.idProveedor = '$id' AND cp.estadoCuentaPagar = '$e' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function eliminarCuentaPagar($data = null){
        if($data != null){
            $sql = "DELETE FROM tbl_cuentas_pagar WHERE idCuentaPagar = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{  
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarCuentaPagar($data = null){
        if($data != null){
            $sql = "UPDATE tbl_cuentas_pagar SET idProveedor = ?, fechaCuentaPagar = ?, nrcCuentaPagar = ?, facturaCuentaPagar = ?,
                    plazoCuentaPagar = ?, subtotalCuentaPagar = ?, ivaCuentaPagar = ?, perivaCuentaPagar = ?, notaCredito = ?, totalCuentaPagar = ? 
                    WHERE idCuentaPagar = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function filtrarCuentasFecha($data = null){
        if($data != null){
            $sql = "SELECT p.empresaProveedor, cp.* FROM tbl_cuentas_pagar as cp INNER JOIN tbl_proveedores AS p
                    ON(cp.idProveedor = p.idProveedor ) WHERE fechaCuentaPagar BETWEEN ? AND ? ";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }
    }
}
?>


