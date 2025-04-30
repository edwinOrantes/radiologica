<?php
class Paciente_Model extends CI_Model {
    
    // Obtener departamentos
    public function obtenerDepartamentos(){
        $sql = "SELECT * FROM tbl_departamentos_sv";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Obtener municipios
    public function obtenerMunicipios($id = null){
        $sql = "SELECT * FROM tbl_municipios_sv WHERE idDepartamento=?";
        $datos = $this->db->query($sql, $id);
        return $datos->result();
    }

     // Obtener pacientes
    public function obtenerPacientes(){
        $sql = "SELECT * FROM tbl_pacientes";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Detalle paciente
    public function detallePaciente($data = null){
        if($data != null){
            $sql ="SELECT * FROM tbl_pacientes WHERE idPaciente = ? ";
            $datos = $this->db->query($sql, $data);
            return $datos->row();
        }
    }

    // Guardar un paciente
    public function guardarPaciente($data = null){
        if ($data != null) {
            /* $sql = "INSERT INTO tbl_pacientes(nombrePaciente, edadPaciente, telefonoPaciente, duiPaciente, nacimientoPaciente, direccionPaciente)
                    VALUES(?, ?, ?, ?, ?, ?)"; */
            $sql = "INSERT INTO tbl_pacientes(duiPaciente, nombrePaciente, nacimientoPaciente, civilPaciente, telefonoPaciente, edadPaciente,
                    profesionPaciente, sexoPaciente, tipeoPaciente, direccionPaciente)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($this->db->query($sql, $data)) {
                $paciente = $this->db->insert_id(); // Id de la hoja de cobro
                return $paciente;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    // Actualizar un paciente
    public function actualizarPaciente($data = null){
        if($data != null){
            $sql = "UPDATE tbl_pacientes SET duiPaciente = ?, nombrePaciente= ?, nacimientoPaciente= ?, civilPaciente= ?, telefonoPaciente= ?, edadPaciente= ?, 
                            profesionPaciente= ?, sexoPaciente= ?, tipeoPaciente= ?, direccionPaciente= ?
                    WHERE idPaciente = ?";
                   
            if($this->db->query($sql, $data)){
                $paciente = $this->db->insert_id(); // Id de la hoja de cobro
                return $data["idPaciente"];
            }else{
                return 0;
            }
        }
    }

    public function actualizarPacienteHoja($data = null){
        if($data != null){
            $sql = "UPDATE tbl_pacientes SET nombrePaciente = ?, edadPaciente= ?, telefonoPaciente= ?, duiPaciente= ?,
                                 nacimientoPaciente= ?, direccionPaciente=? WHERE idPaciente = ?";
                   
            if($this->db->query($sql, $data)){
                $paciente = $this->db->insert_id(); // Id de la hoja de cobro
                return $data["idPaciente"];
            }else{
                return 0;
            }
        }
    }

    // Eliminar un paciente
    public function eliminarPaciente($id){
        $sql = "DELETE FROM tbl_pacientes WHERE idPaciente = ?";
        if ($this->db->query($sql, $id)) {
            return true;
        }else{
            return false;
        }
    }

    // Obtener habitaciones
    /* public function obtenerHabitaciones(){
        $sql = "SELECT * FROM tbl_habitacion";
        $datos = $this->db->query($sql);
        return $datos->result();
    } */

    public function obtenerPacientesHabitacion(){
        $sql = "SELECT p.nombrePaciente, p.apellidoPaciente, p.direccionPaciente, h.numeroHabitacion, h.estadoHabitacion, hc.idHoja, hc.fechaHoja FROM tbl_hoja_cobro as hc
                INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_habitacion as h ON(hc.idHabitacion = h.idHabitacion)
                WHERE h.estadoHabitacion = '1'";
        $datos = $this->db->query($sql);
        return $datos->result();

    }

    // Obteniendo datos del paciente
    public function obtenerDetalle($id = null){
        $sql = "SELECT * FROM tbl_pacientes WHERE idPaciente = $id";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleXPaciente($id = null){
        $sql = "SELECT * FROM tbl_pacientes AS p
                        LEFT JOIN tbl_responsables AS r ON(r.idMenor = p.idPaciente)
                        WHERE p.idPaciente = '$id' LIMIT 1";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    // Validando existencia de paciente mediante DUI
    public function validadPaciente($data = null){
        if($data != null){
            $paciente = $data["paciente"];
            $pivote = $data["pivote"];
            if($pivote == 0){
                $sql = "SELECT * FROM tbl_pacientes AS p
                        LEFT JOIN tbl_responsables AS r ON(r.idMenor = p.idPaciente)
                        WHERE p.duiPaciente = '$paciente' LIMIT 1";
            }else{
                $sql = "SELECT * FROM tbl_pacientes AS p
                        LEFT JOIN tbl_responsables AS r ON(r.idMenor = p.idPaciente)
                        WHERE p.nombrePaciente LIKE '%$paciente%' LIMIT 1";
            }
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    // Validando existencia de hojas de cobros abierta mediante idPaciente
    public function validadHoja($paciente){
        $sql = " SELECT idHoja FROM tbl_hoja_cobro WHERE idPaciente = '$paciente' AND estadoHoja = 1  ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function validadExistenciaHoja($paciente){
        $sql = " SELECT idHoja FROM tbl_hoja_cobro as hc INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente)
                 WHERE p.nombrePaciente = '$paciente' AND estadoHoja = 1  ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }


    // Conectando a base de enfermeria
    public function obtenerHabitaciones(){
        $DB2 = $this->load->database('enfermeria_db', TRUE);
        $sql = "SELECT h.idHabitacion, h.numeroHabitacion, h.estadoHabitacion, p.idPlanta, p.nombrePlanta FROM tbl_habitacion as h INNER JOIN tbl_planta as p on(h.idPlanta=p.idplanta)";
        $datos = $DB2->query($sql);
        return $datos->result();
    }

    public function obtenerHabitaciones2(){
        $sql = "SELECT h.idHabitacion, h.numeroHabitacion, h.estadoHabitacion, p.idPlanta, p.nombrePlanta FROM tbl_habitacion as h INNER JOIN tbl_planta as p on(h.idPlanta=p.idplanta)";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleHabitacion($id){
        $DB2 = $this->load->database('enfermeria_db', TRUE);
        $sql = "SELECT * FROM tbl_detalle_senso WHERE habitacionPaciente = '$id' AND estadoPaciente > 0 AND idSenso = (SELECT MAX(idSenso) FROM tbl_detalle_senso)";
        $datos = $DB2->query($sql);
        return $datos->result();
    }

    public function sensoHoy($fecha){
        $DB2 = $this->load->database('enfermeria_db', TRUE);
        $sql = "SELECT h.numeroHabitacion, ds.* FROM tbl_detalle_senso AS ds INNER JOIN tbl_habitacion AS h ON(ds.habitacionPaciente = h.idHabitacion)
                WHERE idSenso = (SELECT idSenso FROM tbl_senso_diario WHERE fechaSenso = '$fecha' )";
        $datos = $DB2->query($sql);
        return $datos->result();
    }

    public function buscarRecomendaciones($txt){
        $sql = "SELECT * FROM tbl_pacientes WHERE nombrePaciente LIKE '%$txt%' LIMIT 10";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function buscarPaciente($txt){
        $sql = "SELECT * FROM tbl_pacientes WHERE nombrePaciente = '$txt' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function busquedaPaciente($txt = null){
        $sql = "SELECT * FROM tbl_pacientes WHERE nombrePaciente LIKE '%$txt%' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function buscarEncargado($id = null){
        $sql = "SELECT MAX(ch.idCita), ch.* FROM tbl_citas_hemodialisis AS ch WHERE ch.idPaciente = (SELECT MAX(idPaciente) FROM tbl_citas_hemodialisis 
                WHERE idPaciente = '$id'); ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function hojasPaciente($id){
        $sql = "SELECT m.nombreMedico, hc.idHoja, hc.codigoHoja, hc.fechaHoja, hc.tipoHoja, h.numeroHabitacion  FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                INNER JOIN tbl_habitacion AS h ON(hc.idHabitacion = h.idHabitacion)
                WHERE hc.idPaciente = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Nuevas funciones
    public function obtenerResponsable($id = null){
        $sql = "SELECT * FROM tbl_responsables WHERE idMenor = (SELECT MAX(idMenor) FROM tbl_responsables WHERE idMenor = '$id' )";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarDatos($data = null){
        if ($data != null) {
            $menor = $data["menor"];
            $responsable = $data["responsable"];
            $pivote = $responsable["pivote"];
            unset($responsable["pivote"]);
            $sqlR = "";
            $sqlP = "UPDATE tbl_pacientes SET nombrePaciente = ?, edadPaciente = ? WHERE idPaciente = ?";
            switch ($pivote) {
                case '1':
                    // Insertar
                    $sqlR = "INSERT INTO tbl_responsables(idMenor, nombreResponsable, edadResponsable, telefonoResponsable, duiResponsable, profesionResponsable, parentescoResponsable, direccionResponsable, esResponsable)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '2':
                    // Actualizar
                    $sqlR = "UPDATE tbl_responsables SET idMenor = ?, nombreResponsable = ?, edadResponsable = ?, telefonoResponsable = ?, duiResponsable = ?,
                            profesionResponsable = ?, parentescoResponsable = ?, direccionResponsable =?, esResponsable=?
                            WHERE idResponsable = ?";
                    break;
                default:
                    # code...
                    break;
            }
            if ($this->db->query($sqlR, $responsable)) {
                $this->db->query($sqlP, $menor);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function guardarDatosMayor($data = null){
        if ($data != null) {
            $paciente = $data["paciente"];
            $responsable = $data["responsable"];

            $pivote = $responsable["pivote"];

            unset($responsable["pivote"]);

            $sqlR = "";
            $sqlP = "UPDATE tbl_pacientes SET nombrePaciente = ?, edadPaciente = ?, duiPaciente = ?, profesionPaciente = ?, direccionPaciente = ? WHERE idPaciente = ?";

            switch ($pivote) {
                case '1':
                    // Insertar
                    $sqlR = "INSERT INTO tbl_responsables(idMenor, nombreResponsable, parentescoResponsable, duiResponsable, pivoteResponsable)
                            VALUES(?, ?, ?, ?, ?)";
                    break;
                case '2':
                    // Actualizar
                    unset($responsable["flag"]);
                    $sqlR = "UPDATE tbl_responsables SET idMenor = ?, nombreResponsable = ?,  parentescoResponsable = ?, duiResponsable = ?
                            WHERE idResponsable = ?";
                    break;
                default:
                    # code...
                    break;
            }

            if ($this->db->query($sqlP, $paciente)) {
                if($responsable["nombre"] != ""){
                    $this->db->query($sqlR, $responsable);
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function datosConsentimientos($hoja = null){
        $sql = "SELECT
                p.nombrePaciente, p.edadPaciente, p.duiPaciente, p.telefonoPaciente, p.direccionPaciente, p.profesionPaciente, m.nombreMedico, m.especialidadMedico
                FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                WHERE hc.idHoja = '$hoja' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }


    public function guardarResponsables($data = null){
        if ($data != null) {
            $accion = $data["accion"];
            unset($data["accion"]);

            switch ($accion) {
                case '1':
                    // Insertar
                    $sql = "INSERT INTO tbl_responsables(nombreResponsable, edadResponsable, telefonoResponsable, duiResponsable, profesionResponsable, 
                            parentescoResponsable, direccionResponsable, pivoteResponsable, idMenor)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '2':
                    // Actualizar
                    unset($data["pivote"]);
                    $sql = "UPDATE tbl_responsables SET nombreResponsable = ?, edadResponsable = ?, telefonoResponsable = ?, duiResponsable = ?, profesionResponsable = ?, 
                            parentescoResponsable = ?, direccionResponsable = ?
                            WHERE idResponsable = ?";
                    break;
                default:
                    $sql = "";
                    break;
            }

            if ($this->db->query($sql, $data)) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    // Nuevas funciones
}
?>

