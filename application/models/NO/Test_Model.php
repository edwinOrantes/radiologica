<?php
class Test_Model extends CI_Model {
    
    public function obtenerExternos($i, $f){
        $DB2 = $this->load->database('hospitalfox_db', TRUE);
        $sql = "SELECT cr.cr_id_pk, p.pa_nombre_vc, p.pa_apellido_vc, c.*, ce.* FROM cobros AS c INNER JOIN pacientes AS p
            ON(c.pa_id_fk = p.pa_id_pk) INNER JOIN cobros_externos AS ce ON(c.hc_id_pk = ce.hc_id_fk) INNER JOIN cobros_recibo AS cr 
            ON(c.hc_id_pk = cr.hc_id_fk) WHERE cr.cr_id_pk BETWEEN '$i' AND '$f' ";
        $datos = $DB2->query($sql);
        return $datos->result();
    }

    public function obtenerMedicos(){
        $DB2 = $this->load->database('hospitalfox_db', TRUE);
        $sql = "SELECT * FROM medicos";
        $datos = $DB2->query($sql);
        return $datos->result();
    }

    public function examenesLaboratorio(){
        $sql = "SELECT * FROM tbl_medicamentos WHERE pivoteMedicamento = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function cuentasGastos(){
        $DB2 = $this->load->database('hospitalfox_db', TRUE);
        $sql = "SELECT * FROM cuentas_gastos";
        $datos = $DB2->query($sql);
        return $datos->result();
    }

    public function medicamentosHospital(){
        $sql = "SELECT * FROM tbl_medicamentos WHERE pivoteMedicamento = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function medicamentosISBM(){
        $sql = "SELECT * FROM tbl_medicamentos WHERE pivoteMedicamento = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Metodos para el Dashboard
    public function topMedicos($i, $f){
        $sql = "SELECT COUNT(hc.idMedico) as totalMedico, hc.idHoja, hc.idMedico, m.* FROM tbl_hoja_cobro AS hc inner JOIN tbl_medicos AS m
            ON(hc.idMedico = m.idMedico) WHERE hc.correlativoSalidaHoja > 0 AND hc.anulada = 0 AND hc.salidaHoja BETWEEN '$i' AND '$f'
            GROUP BY hc.idMedico ORDER BY COUNT(hc.idMedico) DESC LIMIT 5";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerHojasMedico($i, $f, $m){
        $sql = "SELECT hc.idHoja, hc.fechaHoja, hc.tipoHoja, hc.totalHoja, hc.fechaIngresoHoja, hc.estadoHoja, hc.anulada, p.nombrePaciente,
                m.nombreMedico, m.idMedico, h.idHabitacion, h.numeroHabitacion FROM tbl_hoja_cobro as hc INNER JOIN tbl_pacientes as p on(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_medicos as m on(hc.idMedico = m.idMedico) INNER JOIN tbl_habitacion as h on(hc.idHabitacion = h.idHabitacion)
                WHERE hc.salidaHoja BETWEEN '$i' AND '$f' AND hc.idMedico = $m AND hc.correlativoSalidaHoja > 0 ORDER BY hc.fechaHoja DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function topMedicamentos($i, $f){
        /* $sql = "SELECT m.nombreMedicamento, COUNT(hi.idInsumo) as vecesOcupada, SUM(hi.cantidadInsumo) AS totalUsadas, hi.* FROM 
                tbl_hoja_insumos as hi INNER JOIN tbl_medicamentos as m ON(hi.idInsumo = m.idMedicamento) INNER JOIN tbl_hoja_cobro AS hc
                ON(hi.idHoja = hc.idHoja) WHERE hc.correlativoSalidaHoja> 0 AND hc.anulada = 0 AND hc.salidaHoja BETWEEN '$i' AND '$f' GROUP BY hi.idInsumo 
                ORDER BY SUM(hi.cantidadInsumo) DESC LIMIT 5 "; */
        $sql = "SELECT m.nombreMedicamento, COUNT(hi.idInsumo) as vecesOcupada, SUM(hi.cantidadInsumo) AS totalUsadas, hi.*, m.tipoMedicamento FROM 
                tbl_hoja_insumos as hi INNER JOIN tbl_medicamentos as m ON(hi.idInsumo = m.idMedicamento) INNER JOIN tbl_hoja_cobro AS hc
                ON(hi.idHoja = hc.idHoja) WHERE m.tipoMedicamento = 'Medicamentos' AND hc.correlativoSalidaHoja > 0 AND hc.anulada = 0 AND hc.salidaHoja BETWEEN '2021-07-01' AND '2021-07-28' GROUP BY hi.idInsumo 
                ORDER BY SUM(hi.cantidadInsumo) DESC LIMIT 5";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function fechasGraficas($f){
        $sql = "SELECT idConsultaLaboratorio, fechaConsultaLaboratorio as fechaConsulta FROM tbl_consulta_laboratorio WHERE fechaConsultaLaboratorio < '$f'
                ORDER BY fechaConsultaLaboratorio DESC LIMIT 31 ";
        $datos = $this->db->query($sql);
        return $datos->result();   
    }

    public function examenesConsulta($fecha = null, $pivote = null){
        if($fecha != null){
            $fecha = substr($fecha, 0, 10);
            if($pivote == 1){
                $sql ="SELECT m.idMedicamento, m.nombreMedicamento, m.precioVMedicamento from tbl_detalle_consulta AS dc INNER JOIN
                tbl_medicamentos AS m ON(dc.examenSolicitado = m.idMedicamento) WHERE dc.fechaDetalleConsulta LIKE '$fecha%' ";
            }else{
                $anio = date("Y");
                $mes = date("m");
                $i = $anio."-".$mes."-01";
                $f = $anio."-".$mes."-31";
                $sql = "SELECT m.idMedicamento, m.nombreMedicamento, m.precioVMedicamento from tbl_detalle_consulta AS dc INNER JOIN
                tbl_medicamentos AS m ON(dc.examenSolicitado = m.idMedicamento) WHERE dc.fechaDetalleConsulta BETWEEN '$i' AND '$f'";
            }

            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function medicamentosHoja($fecha = null){
        if($fecha != null){
            $sql = "SELECT hc.salidaHoja, hi.idHojaInsumo, m.nombreMedicamento, hi.precioInsumo, hi.cantidadInsumo, hi.fechaInsumo
                    FROM tbl_hoja_insumos as hi INNER JOIN tbl_medicamentos as m ON(hi.idInsumo = m.idMedicamento)
                    INNER JOIN tbl_hoja_cobro as hc ON(hi.idHoja = hc.idHoja) WHERE hc.salidaHoja = '$fecha' AND correlativoSalidaHoja > 0  AND hc.anulada = 0 ";
            $datos = $this->db->query($sql, $fecha);
            return $datos->result();
        }
    }

    public function externosMes($i, $f){
            $sql ="SELECT hc.salidaHoja, e.idExterno, e.nombreExterno, he.idHojaExterno, he.idHoja, he.cantidadExterno, he.precioExterno,
            he.fechaExterno FROM tbl_hoja_externos as he INNER JOIN tbl_externos as e ON(he.idExterno = e.idExterno) INNER JOIN
            tbl_hoja_cobro as hc ON(he.idHoja = hc.idHoja) WHERE hc.salidaHoja BETWEEN '$i' AND '$f' AND correlativoSalidaHoja > 0 AND hc.anulada = 0 ";
            $datos = $this->db->query($sql);
            return $datos->result();
    }

    public function medicamentosMes($i, $f){
            $sql = "SELECT hc.salidaHoja, hi.idHojaInsumo, m.nombreMedicamento, hi.precioInsumo, hi.cantidadInsumo, hi.fechaInsumo
            FROM tbl_hoja_insumos as hi INNER JOIN tbl_medicamentos as m ON(hi.idInsumo = m.idMedicamento) INNER JOIN tbl_hoja_cobro as
            hc ON(hi.idHoja = hc.idHoja) WHERE hc.salidaHoja BETWEEN '$i' AND '$f' AND correlativoSalidaHoja > 0 AND hc.anulada = 0";
            $datos = $this->db->query($sql);
            return $datos->result();
    }

    public function ultrasExcel(){
        $sql = "SELECT p.nombrePaciente, m.nombreMedicamento, hi.fechaInsumo FROM tbl_hoja_insumos AS hi INNER JOIN tbl_medicamentos AS m ON(hi.idInsumo = m.idMedicamento)
        INNER JOIN tbl_hoja_cobro AS h ON(hi.idHoja = h.idHoja)
        INNER JOIN tbl_pacientes AS p ON(h.idPaciente = p.idPaciente )
        WHERE m.pivoteMedicamento = 3 AND hi.fechaInsumo BETWEEN '2021-08-01' AND '2021-08-31'
        ORDER BY hi.fechaInsumo ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }


    public function nuevosMedicamentos(){
        $sql = "SELECT * FROM tbl_medicamentos WHERE idMedicamento BETWEEN 657 AND 972";
        $datos =$this->db->query($sql);
        return $datos->result();
    }

    public function medCat(){
        $sql = "SELECT * FROM tbl_medicamentos WHERE pivoteMedicamento = 0 ORDER BY codigoMedicamento";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function reporteHemodialisis(){
        $sql = "SELECT hc.idHoja, hc.codigoHoja, m.nombreMedico, p.nombrePaciente, hc.fechaHoja FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico) 
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                WHERE hc.idMedico = 262 AND hc.anulada = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function medicamentosUH($id){
        $sql = "SELECT hi.precioInsumo, hi.cantidadInsumo, m.precioCMedicamento, m.precioVMedicamento FROM tbl_hoja_insumos AS hi 
        INNER JOIN tbl_medicamentos AS m ON(hi.idInsumo = m.idMedicamento) WHERE hi.idHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function examenesRX(){
        $sql = "SELECT * FROM tbl_medicamentos WHERE pivoteMedicamento = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function medicamentos(){
        $sql = "SELECT * FROM tbl_medicamentos";
        $datos =$this->db->query($sql);
        return $datos->result();
    }

    public function medicamentosU($precio, $id){
        $sql = "UPDATE tbl_medicamentos SET feriadoMedicamento = '$precio' WHERE idMedicamento = '$id' ";
        $this->db->query($sql);
    }
    
    // Metodos para extraer lista de pacientes por atender
        public function consultasPendientesHoy($fecha, $destino, $turno){
            $sql = "SELECT hc.correlativoSalidaHoja, llp.* FROM tbl_llegada_pacientes AS llp LEFT JOIN tbl_hoja_cobro AS hc ON(llp.idHoja = hc.idHoja)
                    WHERE DATE(llp.fechaLlegada) = '$fecha' AND llp.destinoLlegada = '$destino' AND llp.turno = '$turno' ORDER BY llp.turno asc ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function liberarCupo($cupo){
            $sql = "UPDATE tbl_llegada_pacientes SET estadoLlegada = '0' WHERE idLlegada = '$cupo' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }

        public function regresarCupo($cupo){
            $sql = "UPDATE tbl_llegada_pacientes SET estadoLlegada = '1' WHERE idLlegada = '$cupo' ";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }

        public function pacientesHemodialisis(){
            $sql = "SELECT hc.codigoHoja, hc.fechaHoja, p.nombrePaciente FROM tbl_hoja_cobro AS hc INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                    WHERE hc.idMedico = 262 AND hc.anulada = 0; ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    
}
?>

