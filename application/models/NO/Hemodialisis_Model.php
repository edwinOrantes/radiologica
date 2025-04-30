<?php
class Hemodialisis_Model extends CI_Model {
    
    // Obtener ultimo codigo de gasto
    public function guardarPaciente($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_pacientes(nombrePaciente, edadPaciente, telefonoPaciente, duiPaciente, nacimientoPaciente, tipeoPaciente, direccionPaciente, vinoDe)
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

    public function guardarCita($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_citas_hemodialisis(pacienteCita, turnoCita, fechaCita, responsablePaciente, telRespPaciente, idPaciente, estadoCita) VALUES(?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
    

    public function listaCitas($turno = null, $date = null){
            $sql = "SELECT ch.idCita, p.idPaciente, ch.turnoCita, ch.fechaCita, ch.estadoCita, ch.hojaCobro, ch.valeGenerado, p.nombrePaciente, p.edadPaciente, p.telefonoPaciente, p.direccionPaciente, p.tipeoPaciente,
            ch.responsablePaciente, ch.telRespPaciente  FROM tbl_citas_hemodialisis AS ch INNER JOIN tbl_pacientes AS p ON(ch.idPaciente = p.idPaciente)
            WHERE ch.turnoCita = '$turno' AND DATE(ch.fechaCita) = '$date'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function saldarCita($id){
        $sql = "UPDATE tbl_citas_hemodialisis SET estadoCita = 0 WHERE idCita = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    
    public function datosPaciente($id = null){
        $sql = "SELECT * FROM tbl_pacientes WHERE idPaciente = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarHoja($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_hoja_cobro(codigoHoja, idPaciente, fechaHoja, tipoHoja, idMedico, idHabitacion, estadoHoja, seguroHoja)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?) ";
            if($this->db->query($sql, $data)){
                $idHoja = $this->db->insert_id(); // Id de la hoja de cobro
                return $idHoja;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function guardarMedicamento($data = null, $cita = null){
        if($data != null){
            $idHoja = $data["idHoja"];
            $idInsumo = $data["idInsumo"];
            $precioInsumo = $data["precioInsumo"];
            $cantidadInsumo = $data["cantidadInsumo"];
            $fechaInsumo = $data["fechaInsumo"];

            $hoja = $data["idHoja"];
            $flag = 0;

            $sql2 = "UPDATE tbl_citas_hemodialisis SET hojaCobro = '$hoja' WHERE idCita = '$cita' ";
            $sql3 = "UPDATE tbl_citas_hemodialisis SET estadoCita = 0 WHERE idCita = '$cita' ";

            for ($i =0; $i <= 1; $i++) {
                /* $medicamentos["idInsumo"] = $data["idInsumo"][$i]; //Estableciendo indice especifico
                $medicamentos["precioInsumo"] = $data["precioInsumo"][$i]; //Estableciendo indice especifico */

                $sql = "INSERT INTO tbl_hoja_insumos(idHoja, idInsumo, precioInsumo, cantidadInsumo, fechaInsumo)
                        VALUES('$idHoja', '$idInsumo[$i]', '$precioInsumo[$i]', '$cantidadInsumo', '$fechaInsumo')";

                if($this->db->query($sql)){
                    $flag++;
                }
            }
            if($flag == 2){
                $this->db->query($sql2);
                $this->db->query($sql3);
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
    
    public function actualizarCita($data = null){
        if($data != null){
            $sql = "UPDATE tbl_citas_hemodialisis SET idPaciente = ?, pacienteCita = ?, turnoCita = ?, fechaCita = ?, responsablePaciente = ?, telRespPaciente = ?, estadoCita = ?
                    WHERE idCita = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    public function obtenerCorrelativo($id = null){
        $sql = "SELECT correlativoSalidaHoja FROM tbl_hoja_cobro WHERE idHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function eliminarCita($data = null){
        if($data != null){
            $sql = "UPDATE tbl_citas_hemodialisis SET estadoCita = '2' WHERE idCita = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    // Para vales
        public function obtenerCodigo(){
            $sql = "SELECT COALESCE((SELECT MAX(codigoVale) FROM tbl_vales_hemo), 0) AS codigo";
            $datos = $this->db->query($sql);
            return $datos->row();

        }

        public function guardarVale($data = null){
            if($data != null){
                $cita = $data["idCita"];
                $sql = "INSERT INTO tbl_vales_hemo(codigoVale, citaVale) VALUES(?, ?)";
                $sql2 = "UPDATE tbl_citas_hemodialisis SET valeGenerado = '1' WHERE idCita = '$cita'";
                if($this->db->query($sql, $data)){
                    $idVale = $this->db->insert_id(); // Id de la hoja de cobro
                    $this->db->query($sql2);
                    return $idVale;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
    
        }

        public function datosVale($id = null){
            if($id != null){
                $sql = "SELECT
                        vh.codigoVale, DATE(vh.creadoVale) AS fechaVale, ch.pacienteCita, hc.codigoHoja,
                        (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE (hi.idHoja = hc.idHoja)) AS interno,
                        (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE (he.idHoja = hc.idHoja)) AS externo
                        FROM tbl_vales_hemo AS vh
                        INNER JOIN tbl_citas_hemodialisis AS ch ON(ch.idCita = vh.citaVale)
                        INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = ch.hojaCobro)
                        WHERE vh.idVale = '$id' AND hc.correlativoSalidaHoja = 0";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function valexCita($cita = null){
            if($cita != null){
                $sql = "SELECT * FROM tbl_vales_hemo AS vh WHERE vh.citaVale = '$cita'";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }
    // Para vales
}
?>

