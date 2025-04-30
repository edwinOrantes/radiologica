<?php
class Isbm_Model extends CI_Model {

    public function obtenerCodigo(){
        $sql = "SELECT * FROM tbl_cuenta_descargos_bm WHERE idDescargosBM = (SELECT MAX(idDescargosBM) FROM tbl_cuenta_descargos_bm)";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerCuentas(){
        $sql = "SELECT * FROM tbl_cuenta_isbm ORDER BY idCuenta DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function crearCuenta($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_cuenta_isbm(fechaCuenta, codigoCuenta) VALUES(?, ?)";
            if($this->db->query($sql, $data)){
                $cuenta = $this->db->insert_id();
                return $cuenta;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function detalleCuentaIsbm($cuenta = null){
        $sql = "SELECT dci.idDetalleCuenta, dci.idCuenta, dci.fechaUso,  dci.cantidadMedicamento, m.codigoMedicamento, m.nombreMedicamento, m.idMedicamento
                FROM tbl_dcuenta_isbm AS dci
                INNER JOIN tbl_medicamentos AS m ON(dci.idMedicamento = m.idMedicamento) WHERE dci.idCuenta = '$cuenta' ORDER BY dci.idDetalleCuenta DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function resumenCuentaIsbm($cuenta = null){
        /* $sql = "SELECT ci.fechaCuenta, ci.codigoCuenta, dci.idDetalleCuenta, dci.idCuenta, dci.fechaUso,  SUM(dci.cantidadMedicamento) AS cantidadMedicamento, m.codigoMedicamento, m.nombreMedicamento, m.idMedicamento
                FROM tbl_dcuenta_isbm AS dci
                INNER JOIN tbl_cuenta_isbm AS ci ON (dci.idCuenta = ci.idCuenta)
                INNER JOIN tbl_medicamentos AS m ON(dci.idMedicamento = m.idMedicamento) WHERE dci.idCuenta = '$cuenta' GROUP BY dci.idMedicamento"; */
        $sql = "SELECT
                SUM(dd.cantidadMedicamento) AS cantidadMedicamento, m.codigoMedicamento, m.nombreMedicamento, m.idMedicamento,
                cd.fechaDescargosBM AS fechaCuenta, cd.codigoCuenta FROM tbl_dcuenta_descargosbm AS dd
                INNER JOIN tbl_cuenta_descargos_bm AS cd ON(dd.idCuentaDescargo = cd.idDescargosBM)
                INNER JOIN tbl_medicamentos AS m ON(dd.idMedicamento = m.idMedicamento)
                WHERE cd.idDescargosBM = '$cuenta' GROUP BY dd.idMedicamento";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function buscarMedicamento($i = null){
        $sql = "SELECT idMedicamento, codigoMedicamento, stockMedicamento, usadosMedicamento
                FROM tbl_medicamentos WHERE idMedicamento = '$i' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function buscarMedicamentoCodigo($c = null){
        $sql = "SELECT idMedicamento, codigoMedicamento, stockMedicamento, usadosMedicamento
                FROM tbl_medicamentos WHERE codigoMedicamento = '$c' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function agregarDetalle($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_dcuenta_isbm(idCuenta, idMedicamento, cantidadMedicamento) VALUES(?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;                
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public function obtenerMedicamento($id = null){
        $sql = "SELECT idMedicamento, codigoMedicamento, stockMedicamento, usadosMedicamento
                FROM tbl_medicamentos WHERE idMedicamento = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function editarCuenta($data = null){
        if($data != null){
            $sql = "UPDATE tbl_medicamentos SET stockMedicamento = ?, usadosMedicamento = ? WHERE idMedicamento = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function editarFilaCuenta($data = null){
        if($data != null){
            $sql = "UPDATE tbl_dcuenta_descargosbm SET cantidadMedicamento = ? WHERE idDescargosBM = ? ";
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
        $sql = "SELECT * FROM tbl_dcuenta_descargosbm WHERE idDescargosBM = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerDetalleRequision($id = null){
        $sql = "SELECT * FROM tbl_cuenta_isbm WHERE idCuenta = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }
    
    public function eliminarDetalle($data = null){
        if($data != null){
            $sql = "DELETE FROM tbl_dcuenta_descargosbm WHERE idDescargosBM = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    // Metodos para cuentas de descargar medicamentos
        public function obtenerRequisiciones(){
            $sql = "SELECT * FROM tbl_cuenta_isbm WHERE estadoCuenta = 0 " ;
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function actualizarEstadoCuenta($id = null){
            $sql = "UPDATE tbl_cuenta_isbm SET estadoCuenta = 1 WHERE idCuenta = '$id'";
            $this->db->query($sql);
            // return $datos->result();
        }

        public function guardarCuentaDescargo($data = null, $dia = null){
            if($data != null ){
                $sql = "INSERT INTO tbl_cuenta_descargos_bm(codigoCuenta, turnoDescargosBM, areaDescargosBM, fechaDescargosBM, estadoDescargosBM) VALUES(?, ?, ?, ?, ?)";
                $sqlU = "UPDATE tbl_cuenta_descargos_bm SET estadoDescargosBM = '0' WHERE turnoDescargosBM = '$dia' AND estadoDescargosBM = 1";
                if($this->db->query($sql, $data)){
                    $cuenta = $this->db->insert_id();
                    $this->db->query($sqlU);
                    return $cuenta;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

        public function listaCuentasDescargo(){
            /* $sql = "SELECT cd.idDescargosBM, cd.fechaDescargosBM, cd.fechaDescargosBM, ci.codigoCuenta FROM tbl_cuenta_descargos_bm AS cd
                    INNER JOIN tbl_cuenta_isbm AS ci ON(cd.cuentaDescargosBM = ci.idCuenta) ORDER BY cd.fechaDescargosBM DESC"; */
            $sql = "SELECT cd.idDescargosBM, cd.codigoCuenta, cd.fechaDescargosBM, cd.turnoDescargosBM, cd.areaDescargosBM, cd.fechaDescargosBM, cd.estadoDescargosBM
                    FROM tbl_cuenta_descargos_bm AS cd ORDER BY cd.fechaDescargosBM DESC LIMIT 100";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function descontarMedicamento($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_dcuenta_descargosbm(idCuentaDescargo, idMedicamento, cantidadMedicamento, por, pivote) VALUES(?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function detalleDescargosCuentaIsbm($cuenta = null){
            $sql = "SELECT dcm.idDescargosBM, dcm.idCuentaDescargo, dcm.cantidadMedicamento, dcm.fechaDecargo, dcm.pivote, m.codigoMedicamento, m.nombreMedicamento, m.idMedicamento
                    FROM tbl_dcuenta_descargosbm AS dcm
                    INNER JOIN tbl_medicamentos AS m ON(dcm.idMedicamento = m.idMedicamento) WHERE dcm.idCuentaDescargo = '$cuenta' ORDER BY dcm.idDescargosBM ASC ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        /* public function eliminarDetalle($data = null){
            if($data != null){
                $idDescargo = $data["idDescargo"];
				$cantidadDescargo = $data["cantidadDescargo"];
				$idMedicamento = $data["idMedicamento"];

                $sql = "DELETE FROM tbl_dcuenta_descargosbm WHERE idDescargosBM = '$idDescargo' ";
                if($this->db->query($sql)){
                    $med = $this->actualizarMedicamento($idMedicamento);
                    $stock = ($med->stockMedicamento + $cantidadDescargo);
                    $usados = ($med->usadosMedicamento - $cantidadDescargo);
                    $sql2 = "UPDATE tbl_medicamentos SET stockMedicamento = '$stock', usadosMedicamento = '$usados' WHERE idMedicamento = '$idMedicamento' ";
                    if($this->db->query($sql2)){
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

        public function actualizarMedicamento($id = null){
            $sql = "SELECT stockMedicamento, usadosMedicamento FROM tbl_medicamentos WHERE idMedicamento = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }


        public function obtenerDetalleCuenta($id = null){
            $sql = "SELECT * FROM tbl_cuenta_descargos_bm WHERE idDescargosBM = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    

        
    // Fin metodos para cuentas de descargar medicamentos
}
?>
