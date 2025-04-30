<?php
class Quirofanos_Model extends CI_Model {

    public function medicamentosSala(){
        $sql = "SELECT * FROM tbl_medicamentos AS m WHERE m.stockSala > 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function medicamentoPorCodigo($codigo = null){
        $sql = 'SELECT
                m.idMedicamento, m.nombreMedicamento, m.precioVMedicamento, m.stockSala, m.debeSala
                FROM tbl_medicamentos AS m 
                WHERE m.codigoMedicamento = "'.$codigo.'"';
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function medicamentoPorId($id = null){
        $sql = 'SELECT
                m.idMedicamento, m.nombreMedicamento, m.stockSala, m.stockMedicamento
                FROM tbl_medicamentos AS m 
                WHERE m.idMedicamento = "'.$id.'"';
        $datos = $this->db->query($sql);
        return $datos->row();
    }
    
    public function procedimientosActivos(){
        $sql = "SELECT p.nombrePaciente, ps.*, hc.fechaHoja, hc.codigoHoja
                FROM tbl_procedimientos_salas AS ps
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = ps.idPaciente)
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = ps.idHoja)
                WHERE ps.estadoProcedimiento = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleProcedimiento($pivote = null){
        $sql = "SELECT m.idMedicamento, m.codigoMedicamento, m.nombreMedicamento, dp.* FROM
                tbl_detalle_procedimientos AS dp 
                INNER JOIN tbl_medicamentos AS m ON(m.idMedicamento = dp.idInsumo)
                WHERE dp.idProcedimiento = '$pivote' AND dp.eliminado = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function procedimientoSala($pivote = null){
        $sql = "SELECT * FROM tbl_procedimientos_salas AS ps WHERE ps.idProcedimiento = '$pivote' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function estaEnUso($insumo = null, $procedimiento = null){
        $sql = "SELECT COALESCE(MAX(idInsumo), '0') AS idInsumo, COALESCE(MAX(cantidadInsumo), '0') AS cantidad
                FROM tbl_detalle_procedimientos
                WHERE idInsumo = '$insumo' AND idProcedimiento = '$procedimiento'";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarDescargo($data = null){
        if($data != null){
            $pivote = $data["pivoteConsulta"];
            $cantidad = $data["cantidadActual"] + 1;
            unset($data["pivoteConsulta"]);
            unset($data["cantidadActual"]);
            if($pivote == 0){
                $sql = "INSERT INTO tbl_detalle_procedimientos(idProcedimiento, idHoja, idInsumo, precioInsumo, cantidadInsumo, por)
                        VALUES(?, ?, ?, ?, ?, ?)";
                $resp = $this->db->query($sql, $data);
            }else{
                $i = $data['idInsumo'];
                $p = $data['idProcedimiento'];
                $sql = "UPDATE tbl_detalle_procedimientos SET cantidadInsumo = '$cantidad'
                        WHERE idInsumo = '$i' AND idProcedimiento = '$p' ";
                $resp = $this->db->query($sql);
            }
            if($resp){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarDescargo($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_procedimientos SET cantidadInsumo = ?
                    WHERE idDetalleProcedimiento = ?"; 
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function eliminarDescargo($data = null){
        if($data != null){
            $sql = "DELETE FROM tbl_detalle_procedimientos WHERE idDetalleProcedimiento = ?"; 
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function agregarACuenta($data = null){
        if($data != null){
            $procedimiento = $data["procedimiento"];
            $medicamentos = $data["medicamentos"];
            $sql = "UPDATE tbl_procedimientos_salas SET estadoProcedimiento = '0' WHERE idProcedimiento = '$procedimiento' ";
            if($this->db->query($sql)){
                $sql2 = 'INSERT INTO tbl_hoja_insumos(idHoja, idInsumo, precioInsumo, cantidadInsumo, fechaInsumo, por, pivoteStock) VALUES (?, ?, ?, ?, ?, ?, ?)';
                foreach ($medicamentos as $row) {
                    $this->db->query($sql2, $row);
                }
                return true;

            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function listaProcedimientos(){
        $sql = "SELECT p.nombrePaciente, ps.*, hc.fechaHoja, hc.codigoHoja
                FROM tbl_procedimientos_salas AS ps
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = ps.idPaciente)
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = ps.idHoja)";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function insumosParaReintegro(){
        $sql = "SELECT m.idMedicamento, m.codigoMedicamento, m.nombreMedicamento, dp.idDetalleProcedimiento, dp.idProcedimiento, dp.cantidadInsumo, SUM(dp.cantidadInsumo) AS total
                FROM tbl_detalle_procedimientos AS dp
                INNER JOIN tbl_medicamentos AS m ON(m.idMedicamento = dp.idInsumo)
                WHERE dp.reintegro = 0 GROUP BY dp.idInsumo";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function reintegrarInsumo($data = null){
        if($data != null){
            $sql = "UPDATE tbl_medicamentos SET stockSala = ?, stockMedicamento = ?
                    WHERE idMedicamento = ?";
            $sql2 = "UPDATE tbl_detalle_procedimientos SET reintegro = 1 WHERE idInsumo = '".$data["medicamento"]."' ";
            if($this->db->query($sql, $data)){
                $this->db->query($sql2);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function procedimientoHoja($hoja = null){
        $sql = "SELECT * FROM tbl_procedimientos_salas AS ps WHERE ps.idHoja = '$hoja' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function eliminarProcedimiento($p = null){
        $sql = "DELETE FROM tbl_procedimientos_salas WHERE idProcedimiento = '$p' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function datosHoja($hoja = null){
        $sql = "SELECT hc.idHoja, hc.idPaciente, hc.procedimientoHoja FROM tbl_hoja_cobro AS hc WHERE hc.idHoja = '$hoja' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }
}
?>