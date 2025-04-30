<?php
class Cajeras_Model extends CI_Model {
    
    public function ultimoCorte(){
        $sql = "SELECT cc.inicioCorte, cc.finCorte, cc.fechaCorte 
                FROM tbl_corte_caja AS cc 
                WHERE cc.idCorte = (SELECT MAX(idCorte) FROM tbl_corte_caja)";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function listaCortes($flag = null){
        $sql = "SELECT * FROM tbl_corte_cajera AS cc WHERE cc.idUsuario = '$flag' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function datosCorte($id = null){
        $sql = "SELECT cc.inicioCorte, cc.finCorte, cc.fechaCorte, u.nombreUsuario FROM tbl_corte_caja AS cc
                INNER JOIN tbl_usuarios AS u ON(cc.cajeraCorte = u.idUsuario)
                WHERE cc.idCorte = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarCorte($data = null){
        if($data != null){
            $usuario = $data["usuario"];
            $turno = $data["turnoActual"];
            $fecha = $data["fechaCorte"];

            $sqlprev = "INSERT INTO tbl_corte_cajera(idUsuario, turnoCorte, fechaCorte) VALUES('$usuario', '$turno', '$fecha')";
            if($this->db->query($sqlprev)){
                $idCorte = $this->db->insert_id(); // Id del corte
                $sql = "UPDATE tbl_control_cajeras SET fechaLiquidado = ?, estadoControl = '0', turnoCorte = ?, pivoteCorte = '$idCorte'
                        WHERE idUsuario = ? AND estadoControl = '1' ";
                $this->db->query($sql, $data);
                return $idCorte;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function obtenerTotales($data = null){
        if($data != null){
            $pivote = $data["idCorte"];
            $sql = "SELECT hc.idHoja, hc.fechaHoja, hc.salidaHoja, hc.totalHoja, hc.codigoHoja, hc.anulada, hc.tipoHoja, hc.descuentoHoja,
                    p.nombrePaciente, m.nombreMedico, cc.fechaLiquidado,
                    (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE hi.idHoja = hc.idHoja) AS medicamentos,
                    (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE he.idHoja = hc.idHoja) AS externos,
                    (SELECT IFNULL(SUM(ah.montoAbono), 0) FROM tbl_abonos_hoja AS ah WHERE ah.idHoja = hc.idHoja AND ah.seLiquido = 1 AND ah.liquidacion != 0 AND hc.esPaquete = 0) AS abonado,
                    hc.correlativoSalidaHoja, hc.credito_fiscal, med.nombreMedico, fe.fechaFactura
                    FROM tbl_control_cajeras AS cc
                    INNER JOIN tbl_hoja_cobro as hc ON(cc.idHoja = hc.idHoja)
                    INNER JOIN tbl_medicos AS med ON(hc.idMedico = med.idMedico)
                    INNER JOIN tbl_pacientes  p ON(hc.idPaciente = p.idPaciente) 
                    INNER JOIN tbl_medicos as m ON(hc.idMedico = m.idMedico)
                    LEFT JOIN tbl_facturas_emitidas AS fe ON(fe.idHoja = hc.idHoja)
                    WHERE cc.pivoteCorte = '$pivote'
                    ORDER BY hc.correlativoSalidaHoja ASC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function validarCorte($data = null){
        if($data != null){
            $sql = "SELECT * FROM tbl_corte_cajera AS cc WHERE cc.fechaCorte = ? AND cc.turnoCorte = ? AND cc.idUsuario = ?";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }
    }

    public function obtenerCorte($id = null){
        $sql = "SELECT * FROM tbl_corte_cajera WHERE idCorteCaja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }
    

}
?>