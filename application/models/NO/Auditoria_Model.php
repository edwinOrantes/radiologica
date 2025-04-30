<?php
class Auditoria_Model extends CI_Model {
    
    public function detalleHojas($data = null){
        if($data != null){
            $sql = "SELECT 
                    hc.codigoHoja, hc.correlativoSalidaHoja, p.nombrePaciente, hc.tipoHoja, h.numeroHabitacion, hc.fechaHoja, hc.salidaHoja,
                    (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM  tbl_hoja_insumos AS hi WHERE hi.idHoja = hc.idHoja) AS medicamentos,
                    (SELECT SUM(he.cantidadExterno * he.precioExterno) FROM  tbl_hoja_externos AS he WHERE he.idHoja = hc.idHoja) AS externos,
                    hc.credito_fiscal
                    FROM tbl_hoja_cobro AS hc
                    INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                    INNER JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                    WHERE hc.anulada = 0 AND hc.correlativoSalidaHoja != 0 AND DATE(hc.salidaHoja) BETWEEN ? AND ?
                    ORDER BY hc.codigoHoja ASC";
            $datos = $this->db->query($sql, $data);
            return $datos->result();

        }
    }


    public function cuentasConAbonos(){
        $sql = "SELECT ah.idAbono, ah.fechaAbono, hc.codigoHoja, hc.fechaHoja, hc.salidaHoja, p.nombrePaciente, m.nombreMedico,
                (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE (hi.idHoja = hc.idHoja)) AS medicamentos,
                (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE (he.idHoja = hc.idHoja)) AS externos,
                (SELECT SUM(ah.montoAbono) FROM tbl_abonos_hoja AS ah WHERE (ah.idHoja = hc.idHoja)) AS total_abonos,
                hc.tipoHoja, h.numeroHabitacion, s.nombreSeguro
                FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_seguros AS s ON(s.idSeguro = hc.seguroHoja)
                INNER JOIN tbl_habitacion AS h ON(h.idHabitacion = hc.idHabitacion)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hc.idMedico)
                INNER JOIN  tbl_abonos_hoja AS ah ON(ah.idHoja = hc.idHoja)  
                WHERE hc.porPagos = 1 AND hc.correlativoSalidaHoja = 0 AND hc.esPaquete = 0 AND ah.seLiquido = 0
                GROUP BY hc.codigoHoja ORDER BY ah.idAbono ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
        
    }

    public function liquidacionCaja($i, $f){
        $sql = "SELECT hc.idHoja, hc.fechaHoja, hc.salidaHoja, hc.totalHoja, hc.codigoHoja, hc.anulada, hc.tipoHoja, hc.descuentoHoja,
                p.nombrePaciente, m.nombreMedico,
                (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE (hi.idHoja = hc.idHoja)) AS medicamentos,
                (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE (he.idHoja = hc.idHoja)) AS externos,
                hc.correlativoSalidaHoja, hc.credito_fiscal, med.nombreMedico, fe.fechaFactura
                FROM tbl_hoja_cobro as hc 
                INNER JOIN tbl_medicos AS med ON(hc.idMedico = med.idMedico)
                INNER JOIN tbl_pacientes  p ON(hc.idPaciente = p.idPaciente) 
                INNER JOIN tbl_medicos as m ON(hc.idMedico = m.idMedico)
                LEFT JOIN tbl_facturas_emitidas AS fe ON(fe.idHoja = hc.idHoja)
                WHERE hc.correlativoSalidaHoja 
                BETWEEN '$i' AND '$f' 
                ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function cuentasPendientes(){
        $sql = "SELECT hc.idHoja, hc.codigoHoja, hc.fechaHoja, hc.salidaHoja, hc.correlativoSalidaHoja, hc.fechaRecibo, p.nombrePaciente, m.nombreMedico,
                (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE (hi.idHoja = hc.idHoja)) AS interno,
                (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE (he.idHoja = hc.idHoja)) AS externo,
                (SELECT SUM(ah.montoAbono) FROM tbl_abonos_hoja AS ah WHERE (ah.idHoja = hc.idHoja)) AS abonado,
                hc.tipoHoja, h.numeroHabitacion, s.nombreSeguro, hc.esPaquete, hc.pagaMedico, hc.procedimientoHoja, hc.porPagos
                FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_pacientes  AS p ON(p.idPaciente = hc.idPaciente)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hc.idMedico)
                INNER JOIN tbl_habitacion AS h ON(h.idHabitacion = hc.idHabitacion)
                INNER JOIN tbl_seguros AS s ON(s.idSeguro = hc.seguroHoja)
                WHERE hc.correlativoSalidaHoja = 0 AND hc.anulada = 0 AND hc.esPromocion = 0";
        $datos = $this->db->query($sql);
        return $datos->result();

    }
    
}
?>