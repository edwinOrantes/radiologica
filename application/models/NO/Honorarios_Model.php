<?php
class Honorarios_Model extends CI_Model {
    public function honorariosExterno($id){
        $sql = "SELECT e.nombreExterno, hc.fechaHoja, hc.salidaHoja, hc.codigoHoja, s.nombreSeguro AS seguroHoja, p.nombrePaciente, h.* 
                FROM tbl_honorarios as h inner join tbl_hoja_externos as he 
                ON(h.idHojaExterno = he.idHojaExterno) inner join tbl_externos as e on(h.idExterno = e.idExterno) 
                INNER join tbl_hoja_cobro as hc on(h.idHoja = hc.idHoja) inner join tbl_pacientes as p on(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_seguros AS s ON(hc.seguroHoja = s.idSeguro)
                WHERE h.idExterno = '$id' AND h.estadoExterno = 0 AND h.enBanco = 0 ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }    

    public function saldarHonorario($id = null, $fecha = null){
        $sql = "UPDATE tbl_honorarios SET estadoExterno = '1', entregadoExterno = '$fecha' WHERE idHonorario = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function saldarTodos($id = null, $fecha = null){
        $sql = "UPDATE tbl_honorarios SET estadoExterno = 1, entregadoExterno = '$fecha' WHERE idExterno = '$id' AND estadoExterno = 0 AND enBanco = 0";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function adeudarHonorario($id){
        $sql = "UPDATE tbl_honorarios SET estadoExterno = '0' WHERE idHonorario = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function facturarHonorario($id, $facturar){
        $sql = "UPDATE tbl_honorarios SET facturar = '$facturar' WHERE idHonorario = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function honorariosFacturados($data = null){
        if($data != null){
            $sql = "SELECT e.nombreExterno, hc.codigoHoja, hc.salidaHoja, p.nombrePaciente, h.* FROM tbl_honorarios as h INNER JOIN tbl_externos AS e 
                ON(h.idExterno = e.idExterno) INNER JOIN tbl_hoja_cobro as hc ON(h.idHoja = hc.idHoja) INNER JOIN tbl_pacientes AS p 
                ON(hc.idPaciente = p.idPaciente) WHERE /* facturar = 1 AND */ h.idExterno = ? AND DATE(h.fechaExterno) BETWEEN ? AND ? ";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }else{
            $vacio = array('Estado' => "Sin datos" );
            return $vacio;
        }
    }

    public function honorariosEntregados($data = null){
        if($data != null){
            $sql = "SELECT e.nombreExterno, hc.fechaHoja, hc.salidaHoja, hc.codigoHoja, hc.seguroHoja, p.nombrePaciente, h.* 
                    FROM tbl_honorarios AS h INNER JOIN tbl_hoja_externos AS he 
                    ON(h.idHojaExterno = he.idHojaExterno) INNER JOIN tbl_externos AS e ON(h.idExterno = e.idExterno) 
                    INNER join tbl_hoja_cobro AS hc ON(h.idHoja = hc.idHoja) INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                    WHERE h.idExterno = ? AND DATE(h.entregadoExterno) BETWEEN ? AND ? ORDER BY hc.correlativoSalidaHoja ASC";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }else{
            $vacio = array('estado' => "Sin datos" );
            return $vacio;
        }
    }

    public function honorariosPendientes($pivote = null){
        /* $sql = "SELECT e.idExterno, e.nombreExterno, h.*, SUM(h.precioExterno) AS total_honorarios from tbl_honorarios AS h 
                INNER JOIN tbl_externos AS e ON(e.idExterno = h.idExterno)
                WHERE h.estadoExterno = '$pivote' GROUP BY e.idExterno ORDER BY e.idExterno ASC"; */

        $sql = "SELECT e.separacionMedico, e.idExterno, e.nombreExterno, h.*, SUM(h.precioExterno) AS total_honorarios from tbl_honorarios AS h 
                INNER JOIN tbl_externos AS e ON(e.idExterno = h.idExterno)
                WHERE h.estadoExterno = '$pivote' AND e.tipoEntidad = 1 AND e.separacionMedico = 0 AND h.pivotePlus = 0
                GROUP BY e.idExterno ORDER BY e.idExterno ASC";
                
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function honorariosPaquetes(){
        $sql = "SELECT 
                hc.idHoja, hc.codigoHoja, p.nombrePaciente, m.nombreMedico, hp.idHonorarioPaquete, hp.totalHonorarioPaquete, hp.originalHonorarioPaquete
                FROM tbl_honorarios_paquetes AS hp
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = hp.idHoja)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hp.idMedico)
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
                WHERE hp.estadoHonorarioPaquete = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function honorarioPaquete($id = null){
        $sql = "SELECT 
                hc.idHoja, hc.codigoHoja, p.nombrePaciente, m.nombreMedico, hp.idHonorarioPaquete, hp.totalHonorarioPaquete, hp.originalHonorarioPaquete
                FROM tbl_honorarios_paquetes AS hp
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = hp.idHoja)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hp.idMedico)
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
                WHERE hp.idHonorarioPaquete = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function honorarioPorMedico($id = null){
        $sql = "SELECT
                hp.idHonorarioPaquete, hc.idHoja, hc.codigoHoja, hc.credito_fiscal, hc.salidaHoja, p.nombrePaciente, m.nombreMedico, hp.idHonorarioPaquete, hp.totalHonorarioPaquete,
                hp.fechaSaldado, hp.originalHonorarioPaquete, hp.estadoHonorarioPaquete,
                COALESCE((SELECT nombreMedico FROM tbl_medicos WHERE idMedico = (SELECT idMedico FROM tbl_honorarios_paquetes
                WHERE idHonorarioPaquete = hp.honorarioPadre)), '0') AS quienDeja
                FROM tbl_honorarios_paquetes AS hp
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = hp.idHoja)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hp.idMedico)
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
                WHERE m.idMedico = '$id' AND hp.estadoHonorarioPaquete = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function divisionesHonorario($id = null){
        $sql = "SELECT 
                hc.idHoja, hc.codigoHoja, p.nombrePaciente, m.nombreMedico, hp.idHonorarioPaquete, hp.totalHonorarioPaquete
                FROM tbl_honorarios_paquetes AS hp
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = hp.idHoja)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hp.idMedico)
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
                WHERE hp.honorarioPadre = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarDivisionHonorario($data = null){
        if($data != null){
            $montoOriginalNuevo = $data["montoOriginal"] - $data["cantidadHonorario"];
            $idHonorario = $data["idHonorario"];
            unset($data["montoOriginal"]);
            $sql = "INSERT INTO tbl_honorarios_paquetes(idHoja, idMedico, totalHonorarioPaquete, honorarioPadre, originalHonorarioPaquete, divisionPor)
                VALUES(?, ?, ?, ?, ?, ?)";
            $sql2 = "UPDATE tbl_honorarios_paquetes SET totalHonorarioPaquete = '$montoOriginalNuevo' WHERE idHonorarioPaquete = '$idHonorario' ";
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

    public function saldarHonorarioPaquete($id = null, $fecha = null){
        $sql = "UPDATE tbl_honorarios_paquetes SET estadoHonorarioPaquete = '1', fechaSaldado = '$fecha' WHERE idHonorarioPaquete = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function adeudarHonorarioPaquete($id){
        $sql = "UPDATE tbl_honorarios_paquetes SET estadoHonorarioPaquete = '0', fechaSaldado = NULL WHERE idHonorarioPaquete = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function saldarHonorariosPaquetes($id = null, $fecha = null){
        $sql = "UPDATE tbl_honorarios_paquetes SET estadoHonorarioPaquete = '1', fechaSaldado = '$fecha' WHERE idMedico = '$id' AND estadoHonorarioPaquete = '0' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function honorariosEnBanco(){
        $sql = "SELECT SUM(h.precioExterno) AS honorario, COUNT(h.idHonorario) AS numero_honorarios,
                e.nombreExterno, hc.fechaHoja, hc.salidaHoja, hc.codigoHoja, s.nombreSeguro AS seguroHoja, p.nombrePaciente, h.* 
                FROM tbl_honorarios as h inner join tbl_hoja_externos as he 
                ON(h.idHojaExterno = he.idHojaExterno) inner join tbl_externos as e on(h.idExterno = e.idExterno) 
                INNER join tbl_hoja_cobro as hc on(h.idHoja = hc.idHoja) inner join tbl_pacientes as p on(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_seguros AS s ON(hc.seguroHoja = s.idSeguro)
                WHERE h.estadoExterno = 0 AND h.enBanco = 1
                GROUP BY e.idExterno ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function listaHonorariosEnBanco(){// Honorarios completos
        $sql = "SELECT e.nombreExterno, hc.fechaHoja, hc.salidaHoja, hc.codigoHoja, s.nombreSeguro AS seguroHoja, p.nombrePaciente, h.* 
                FROM tbl_honorarios as h inner join tbl_hoja_externos as he 
                ON(h.idHojaExterno = he.idHojaExterno) inner join tbl_externos as e on(h.idExterno = e.idExterno) 
                INNER join tbl_hoja_cobro as hc on(h.idHoja = hc.idHoja) inner join tbl_pacientes as p on(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_seguros AS s ON(hc.seguroHoja = s.idSeguro)
                WHERE h.estadoExterno = 0 AND h.enBanco = 1
                ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleHonorariosEnBanco($externo = null){
        $sql = "SELECT
                p.nombrePaciente, p.edadPaciente, hc.codigoHoja, DATE(h.fechaExterno) AS fechaGenerado, h.* 
                FROM tbl_honorarios AS h
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = h.idHoja)
                INNER JOIN tbl_pacientes AS p ON (p.idPaciente = hc.idPaciente)
                WHERE h.idExterno = '$externo' AND h.estadoExterno = 0 AND h.enBanco = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function honorariosEnBancoSaldar($data = null){
        if($data != null){
            $pivote = $data["pivote"];
            if($pivote == 0){
                unset($data["pivote"]);
                $sql = "SELECT
                        e.nombreExterno, hc.codigoHoja, DATE(h.fechaExterno) AS fechaGenerado, h.* 
                        FROM tbl_honorarios AS h
                        INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = h.idHoja)
                        INNER JOIN tbl_externos AS e ON(e.idExterno = h.idExterno)
                        WHERE h.idHonorario = ? ";
            }else{
                $sql = "SELECT
                        e.nombreExterno, hc.codigoHoja, DATE(h.fechaExterno) AS fechaGenerado, h.* 
                        FROM tbl_honorarios AS h
                        INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = h.idHoja)
                        INNER JOIN tbl_externos AS e ON(e.idExterno = h.idExterno)
                        WHERE h.idExterno = ? AND h.idHonorario = ? ";
            }
            $datos = $this->db->query($sql, $data);
            return $datos->row();
        }
    }

    public function saldarHonorariosBanco($data = null){
        /* if($data != null){

            $gastos = $data["gasto"];
            $honorarios = $data["honorarios"];

            $sql = "INSERT INTO tbl_gastos(tipoGasto, montoGasto, entregadoGasto, idCuentaGasto, fechaGasto, entidadGasto, idProveedorGasto,
                    pagoGasto, numeroGasto, bancoGasto, cuentaGasto, descripcionGasto, codigoGasto, flagGasto, efectuoGasto)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $sql_saldar = "UPDATE tbl_honorarios SET estadoExterno = '1', entregadoExterno = CURDATE() WHERE idHonorario = ?";
            
            foreach ($honorarios as $row) {
                $this->db->query($sql_saldar, $row);
            }
            foreach ($gastos as $row) {
                $this->db->query($sql, $row);
                $gInsertados["gasto"][] = $this->db->insert_id();
            }
            return $gInsertados;
        }else{
            return 0;
        } */

        if($data != null){

            $gasto = $data["gasto"];
            $honorarios = $data["honorarios"];

            $sql = "INSERT INTO tbl_gastos(tipoGasto, montoGasto, entregadoGasto, idCuentaGasto, fechaGasto, entidadGasto, idProveedorGasto,
                    pagoGasto, numeroGasto, bancoGasto, cuentaGasto, descripcionGasto, codigoGasto, flagGasto, efectuoGasto)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


            if($this->db->query($sql, $gasto)){
                $idGasto = $this->db->insert_id(); // Id de la cuenta de gasto
                $flag = 0;
                $sql_saldar = "UPDATE tbl_honorarios SET estadoExterno = '1', entregadoExterno = CURDATE(), gastoExterno = '$idGasto' WHERE idHonorario = ?";
                foreach ($honorarios as $row) {
                    $this->db->query($sql_saldar, $row);
                }
                return $idGasto;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public function obtenerhonorarioSaldado($id = null){
        if($id != null){
            $sql = "SELECT
                    CASE 
                        WHEN g.entidadGasto = 1 THEN (SELECT e.nombreExterno FROM tbl_externos AS e WHERE e.idExterno = g.idProveedorGasto)
                        WHEN g.entidadGasto = 2  THEN (SELECT p.empresaProveedor FROM tbl_proveedores AS p WHERE p.idProveedor = g.idProveedorGasto)
                    END AS valor_obtenido,
                    g.*
                    FROM tbl_gastos AS g
                    WHERE g.idGasto = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function detalleHonorarioGasto($gasto = null){
        $sql = "SELECT
                p.nombrePaciente, p.edadPaciente, hc.codigoHoja, DATE(h.fechaExterno) AS fechaGenerado, h.* 
                FROM tbl_honorarios AS h
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = h.idHoja)
                INNER JOIN tbl_pacientes AS p ON (p.idPaciente = hc.idPaciente)
                WHERE h.gastoExterno = '$gasto' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function reporteHonorariosPaquetes($data = null){
        if($data != null){
            $sql = "SELECT
                    hp.idHonorarioPaquete, hc.idHoja, hc.codigoHoja, hc.credito_fiscal, hc.salidaHoja, hc.procedimientoHoja, p.duiPaciente, p.nombrePaciente, p.direccionPaciente, m.nombreMedico, hp.*,
                    COALESCE((SELECT nombreMedico FROM tbl_medicos WHERE idMedico = (SELECT idMedico FROM tbl_honorarios_paquetes WHERE idHonorarioPaquete = hp.honorarioPadre)), '0') AS quienDeja
                    FROM tbl_honorarios_paquetes AS hp
                    INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = hp.idHoja)
                    INNER JOIN tbl_medicos AS m ON(m.idMedico = hp.idMedico)
                    INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
                    WHERE m.idMedico = ? AND DATE(hp.creadoHonorarioPaquete) BETWEEN ? AND ? ";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }
    }

}
?>