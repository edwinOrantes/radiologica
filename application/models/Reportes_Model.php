<?php
class Reportes_Model extends CI_Model {
    // Obtener departamentos
    public function obtenerConsultas($data = null){
        if($data != null){
            /* $sql = "SELECT m.nombreMedico, c.nombrePaciente, c.tipoReferencia, c.fechaConsulta, v.clienteVenta, v.totalVenta, v.dteVenta, v.notaFactura
                    FROM tbl_consulta AS c 
                    INNER JOIN tbl_ventas AS v ON v.consulta = c.idConsulta
                    INNER JOIN tbl_medicos AS m ON m.idMedico = c.idMedico
                    WHERE DATE(c.fechaConsulta) BETWEEN ? AND ? "; */
            $sql = "SELECT
                v.idVenta,
                GROUP_CONCAT(DISTINCT CONCAT(e.nombreExamen, ' ($', FORMAT(dv.precioDetalleVenta, 2), ')') SEPARATOR '\n') AS examenesRealizados,
                m.nombreMedico,
                c.nombrePaciente,
                c.tipoReferencia,
                c.fechaConsulta,
                v.clienteVenta,
                v.totalVenta,
                v.dteVenta,
                v.notaFactura
            FROM tbl_consulta AS c 
            INNER JOIN tbl_ventas AS v ON v.consulta = c.idConsulta
            INNER JOIN tbl_detalle_venta AS dv ON dv.idVenta = v.idVenta
            INNER JOIN tbl_examenes AS e ON e.idExamen = dv.idMedicamento
            INNER JOIN tbl_medicos AS m ON m.idMedico = c.idMedico
            WHERE DATE(c.fechaConsulta) BETWEEN ? AND ? 
            GROUP BY v.idVenta
            ORDER BY c.idConsulta";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }
    }

    
}
?>