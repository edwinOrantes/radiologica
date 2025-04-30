<?php
class Medicamentos_Model extends CI_Model {
    
    public function pacientesPrivados($fecha){
        $sql = "SELECT hc.fechaHoja, p.nombrePaciente, hc.idHoja, hc.idPaciente, h.idHabitacion, h.numeroHabitacion, CONCAT('privado') AS tomadaDe FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                WHERE hc.correlativoSalidaHoja = 0 AND hc.anulada = 0 AND DATE(hc.fechaHoja) > '$fecha'
                ORDER BY hc.fechaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function pacientesISBM(){
        $ISBM_DB = $this->load->database('bienestar_db', TRUE);
        $sql = "SELECT hc.fechaHoja, CONCAT(p.nombrePaciente, ' ' , p.apellidoPaciente) AS nombrePaciente, hc.idHoja, p.idPaciente, h.idHabitacion, h.numeroHabitacion, CONCAT('ISBM') AS tomadaDe
                FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_habitaciones AS h ON(hc.idHabitacion = h.idHabitacion)
                WHERE hc.estadoHoja = 1
                ORDER BY h.numeroHabitacion ASC";
        $datos = $ISBM_DB->query($sql);
        return $datos->result();
    }

    public function guardarPaciente($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_pacientes(nombrePaciente, edadPaciente, telefonoPaciente, duiPaciente, nacimientoPaciente, direccionPaciente)
                    VALUES(?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idPaciente = $this->db->insert_id(); // Id del paciente.
                return $idPaciente;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function crearCuentaBotiquin($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_cuenta_botiquin(idPaciente, idHoja, tipoHoja, estadoCuentaBotiquin)
                    VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idCuenta = $this->db->insert_id(); // Id del paciente.
                return $idCuenta;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function detalleCuentaBotiquin($cuenta = null){
        $sql = "SELECT cm.idCuentaMedicamento, cm.idCuentaBotiquin, cm.idMedicamento, cm.cantidadMedicamento, m.codigoMedicamento, m.nombreMedicamento
                FROM tbl_cuenta_medicamentos AS cm
                INNER JOIN tbl_medicamentos AS m ON(cm.idMedicamento = m.idMedicamento) WHERE cm.idCuentaBotiquin = '$cuenta' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerCuentas(){
        $sql = "SELECT cb.idCuentaBotiquin, cb.tipoHoja, p.idPaciente, p.nombrePaciente, p.edadPaciente, hc.idHoja, hc.estadoHoja, h.numeroHabitacion, h.idHabitacion 
                FROM tbl_cuenta_botiquin as cb
                INNER JOIN tbl_pacientes AS p ON(cb.idPaciente = p.idPaciente)
                LEFT JOIN tbl_hoja_cobro AS hc ON(cb.idHoja = hc.idHoja)
                LEFT JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                WHERE cb.estadoCuentaBotiquin = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function buscarMedicamento($codigo = null){
        $sql = "SELECT idMedicamento, codigoMedicamento, stockMedicamento, usadosMedicamento
                FROM tbl_medicamentos WHERE codigoMedicamento = '$codigo' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function descontarMedicamento($data = null){
        if($data != null){
            $cuenta = $data["cuenta"];
            $med = $data["medicamento"];
            $sql = "INSERT INTO tbl_cuenta_medicamentos(idCuentaBotiquin, idMedicamento, cantidadMedicamento) VALUES(?, ?, ?)";
            $sql2 = "UPDATE tbl_medicamentos SET stockMedicamento = ?, usadosMedicamento = ? WHERE idMedicamento = ? ";
            if($this->db->query($sql, $cuenta)){
                if($this->db->query($sql2, $med)){
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


    }
    
}
?>



