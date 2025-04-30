<?php
class Herramientas_Model extends CI_Model {

    public function facturacionDiaria($fecha){
        // $sql = "SELECT SUM(totalFactura) AS facturado FROM tbl_facturas_emitidas WHERE DATE(fechaFactura) = '$fecha' ";
        $sql = "SELECT COALESCE(SUM(fe.totalFactura), 0) AS facturado FROM tbl_facturas_emitidas AS fe
                INNER JOIN tbl_facturas AS f ON(fe.idHoja = f.idHojaCobro)
                WHERE DATE(f.fechaMostrar) = '$fecha' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function facturadoMes($i, $f){
        $sql = "SELECT COALESCE(SUM(totalFactura),0) AS facturado FROM tbl_facturas_emitidas WHERE DATE(fechaFactura) BETWEEN  '$i' AND '$f' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function facturasExcel($i, $f){
        $sql = "SELECT p.nombrePaciente, hc.codigoHoja, hc.correlativoSalidaHoja, hc.credito_fiscal, fe.numeroFactura, fe.totalFactura,
                fe.fechaFactura FROM tbl_facturas_emitidas AS fe INNER JOIN tbl_hoja_cobro AS hc ON(fe.idHoja = hc.idHoja) INNER JOIN 
                tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente) WHERE DATE(fe.fechaFactura) BETWEEN '$i' AND '$f' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function fechasFactura(){
        // $sql = "SELECT DISTINCT(fechaFactura) FROM tbl_facturas_emitidas ORDER BY fechaFactura DESC LIMIT 30";
        $sql = "SELECT DISTINCT(fechaMostrar) AS fechaFactura FROM tbl_facturas ORDER BY fechaMostrar DESC LIMIT 30";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Para el detalle de hojas de cobro
    public function detalleHoja($id){
        $sql = "SELECT hc.codigoHoja, hc.fechaHoja, CONCAT(em.nombreEmpleado,' ', em.apellidoEmpleado) AS empleado, p.nombrePaciente, mh.* 
                FROM tbl_movimientos_hoja AS mh INNER JOIN tbl_hoja_cobro AS hc ON(mh.idHoja = hc.idHoja)
                INNER JOIN tbl_usuarios AS u ON(mh.idUsuario = u.idUsuario) INNER JOIN tbl_empleados AS em ON(u.idEmpleado = em.idEmpleado)
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                WHERE hc.codigoHoja = '$id' ORDER BY mh.fechaMovimiento ASC ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Para el resumen de facturacion diaria
    public function ultimoLiquidado(){
        $sql = "SELECT * FROM tbl_externos_generados WHERE idExternoGenerado = (SELECT MAX(idExternoGenerado) FROM tbl_externos_generados)";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function despuesLiquidado($codigo = null, $fin = null, $pivote = null){
        if($pivote == 1){
            $sql = "SELECT hc.idHoja, hc.codigoHoja, hc.correlativoSalidaHoja, hc.credito_fiscal, hc.fechaHoja, hc.salidaHoja, hc.seguroHoja, p.nombrePaciente, p.edadPaciente, 
                h.numeroHabitacion, m.nombreMedico FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                WHERE hc.correlativoSalidaHoja BETWEEN '$codigo' AND '$fin' ORDER BY hc.correlativoSalidaHoja ASC ";
        }else{
            $sql = "SELECT hc.idHoja, hc.codigoHoja, hc.correlativoSalidaHoja, hc.credito_fiscal, hc.fechaHoja, hc.salidaHoja, hc.seguroHoja, p.nombrePaciente, p.edadPaciente, 
                h.numeroHabitacion, m.nombreMedico FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                WHERE hc.correlativoSalidaHoja > '$codigo' ORDER BY hc.correlativoSalidaHoja ASC";
        }
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    
    public function facturasPorDia($i = null, $f = null){
        $sql = "SELECT fe.numeroFactura, fe.totalFactura, fe.fechaFactura, hc.idHoja, hc.codigoHoja, hc.correlativoSalidaHoja, hc.credito_fiscal, hc.fechaHoja, hc.salidaHoja,
                p.nombrePaciente, p.edadPaciente, h.numeroHabitacion, m.nombreMedico, hc.seguroHoja
                FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                INNER JOIN tbl_facturas_emitidas AS fe ON(hc.idHoja = fe.idHoja)
                WHERE hc.correlativoSalidaHoja BETWEEN '$i'  AND '$f'  ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }
    
    public function establecerAnimacion($data = null){
        if($data != null){
            $sql = "UPDATE tbl_animations SET linkAnimation = ?, fechaAnimation = ?, estadoAnimation = ? WHERE idAnimation = ? ";
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
