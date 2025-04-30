<?php
class Enfermeria_Model extends CI_Model {
    public $db_enfermeria; // Variable para la conexion con base de datos de enfermeria
    public function __construct(){
        parent::__construct();
        $this->db_enfermeria = $this->load->database('enfermeria_db', TRUE); // Conectando con enfermeria
    }

    // Creando Senso diario
        public function sensoExiste($fecha = null){
            if($fecha != null){
                $sql = "SELECT COALESCE((SELECT sd.idSenso FROM tbl_senso_diario AS sd WHERE DATE(sd.fechaSenso) = '$fecha'), 0) AS existe";
                $datos = $this->db_enfermeria->query($sql);
                return $datos->row();
            }
        }

        public function crearSenso($fecha = null){
            if ($fecha != null){
                $sql = "INSERT INTO tbl_senso_diario(fechaSenso) VALUES('$fecha')";
                if($this->db_enfermeria->query($sql)){
                    $idSenso = $this->db_enfermeria->insert_id(); // Id del senso de este dia
                    return $idSenso;
                }
            }else{
                return 0;
            }
        }

        public function obtenerPaciente($p = null){
            if($p != null){
                $sql = "SELECT p.idpaciente, p.nombrepaciente FROM tbl_pacientes AS p WHERE p.idpaciente = '$p' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function obtenerMedico($m = null){
            if($m != null){
                $sql = "SELECT m.nombreMedico FROM tbl_medicos AS m WHERE m.idMedico = '$m' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function guardarPaciente($data = null, $senso = null){
            $sql = "INSERT INTO tbl_detalle_senso(idSenso, fechaIngreso, nombrePaciente, medicoPaciente, habitacionPaciente, procedimientoPaciente, estadoPaciente, idPaciente, idMedico, idHoja)
                    VALUES('$senso', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db_enfermeria->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    // Creando Senso diario

    public function sensoHoy($fecha = null){
        $sql = "SELECT h.numeroHabitacion, ds.* FROM tbl_detalle_senso AS ds 
                INNER JOIN tbl_habitacion AS h ON(ds.habitacionPaciente = h.idHabitacion)
                WHERE idSenso = (SELECT idSenso FROM tbl_senso_diario WHERE fechaSenso = '$fecha' )";
        $datos = $this->db_enfermeria->query($sql);
        return $datos->result();
    }

    // Consulta a DB Hospital Orellana
        public function medicamentosHoja($id = null){
            if($id != null){
                $sql = "SELECT 
                        hi.idHIE, hi.idHoja, hi.idInsumo, hi.nombreInsumo, hi.cantidadHIE, hi.precioHIE, hi.fechaAgregado, hi.estadoHIE, hi.entregado, hi.devolucion, hi.cantidadDevolucion
                        FROM tbl_hoja_insumos_enfermeria as hi INNER JOIN tbl_medicamentos as m
                        ON(hi.idInsumo = m.idMedicamento) WHERE hi.idHoja = '$id' AND hi.estadoHIE > 0 AND hi.entregado = 0";
                $datos = $this->db->query($sql, $id);
                return $datos->result();
            }
        }

        public function medicamentosHojaEntregados($id = null){
            if($id != null){
                $sql = "SELECT 
                        hi.idHIE, hi.idHoja, hi.idInsumo, hi.nombreInsumo, hi.cantidadHIE, hi.precioHIE, hi.fechaAgregado, hi.estadoHIE, hi.entregado, hi.devolucion, hi.cantidadDevolucion
                        FROM tbl_hoja_insumos_enfermeria as hi INNER JOIN tbl_medicamentos as m
                        ON(hi.idInsumo = m.idMedicamento) WHERE hi.idHoja = '$id' AND hi.estadoHIE > 0 AND hi.entregado = 1";
                $datos = $this->db->query($sql, $id);
                return $datos->result();
            }
        }

        public function guardarMedicamentosHoja($data = null){
            if($data != null){
                foreach ($data as $row) {
                    $sql = "INSERT INTO tbl_hoja_insumos(idInsumo, cantidadInsumo, precioInsumo, idHoja, fechaInsumo, por, cantidadBase, cantidadMG, nombreMedida, pivoteEnfermeria)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->query($sql, $row);
                }
                return true;

            }else{
                return false;
            }
        }

        public function saldarEntrega($hoja = null){
            $sql = "UPDATE tbl_hoja_insumos_enfermeria AS hi SET hi.entregado = 1 WHERE hi.idHoja = '$hoja' AND hi.estadoHIE = 1 AND hi.entregado = 0";
            if($this->db->query($sql)){
                return true;
            }else{
                return false;
            }
        }

        public function procesarDevolucionInsumos($data = null){
            if($data != null){
                $sql = "UPDATE tbl_hoja_insumos AS hi SET hi.cantidadInsumo = ?, hi.cantidadMG = ? WHERE hi.pivoteEnfermeria = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function validarRetornoInsumo($fila = null){
            if($fila != null){
                $sql = "UPDATE tbl_hoja_insumos_enfermeria AS hi SET hi.devolucion = '2' WHERE hi.idHIE = '$fila' ";
                if($this->db->query($sql)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

    // Consulta a DB Hospital Orellana
    
}
?>

