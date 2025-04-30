<?php
class Planilla_Model extends CI_Model {
    // Obtener departamentos
    public function obtenerEmpleados(){
        $sql = "SELECT * FROM tbl_personal_planilla AS pp WHERE pp.estadoEmpleado = '1' ORDER BY pp.areaEmpleado ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerAreas(){
        $sql = "SELECT * FROM tbl_areas_hospital";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function personalPlanilla(){
        $sql = "SELECT * FROM tbl_personal_planilla AS pp WHERE pp.estadoEmpleado = 1 ORDER BY pp.areaEmpleado ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerEmpleado($id = null){
        $sql = "SELECT * FROM tbl_personal_planilla WHERE idEmpleado = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerCuentasDescargos($id = null){
        $sql = "SELECT * FROM tbl_descuentos_planilla ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function crearPlanilla($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_planilla(quincenaPlanilla, mesPlanilla, tipoPlanilla, descripcionPlanilla, fechaPlanilla, estadoPlanilla) VALUES(?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idPlanilla = $this->db->insert_id(); // Id de la planilla.
                return $idPlanilla;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function guardarPersonalPlanilla($data = null, $p = null){
        if($data != null){
            $personal = $data["personal"];
            $flag = 0;
            for ($i=0; $i < sizeof($personal) ; $i++) {
                $idEmpleado = $personal[$i]["idEmpleado"]; 
                $salarioEmpleado = $personal[$i]["salarioEmpleado"];
                $precioHoraExtra = $personal[$i]["precioHoraExtra"];
                $bonoEmpleado = $personal[$i]["bonoEmpleado"];
                $isssEmpleado = $personal[$i]["isss"];
                $afpEmpleado = $personal[$i]["afp"];
                $rentaEmpleado = $personal[$i]["renta"];
                $liquido = $personal[$i]["liquido"];

                $sql = "INSERT INTO tbl_detalle_planilla(idEmpleado, salarioEmpleado, precioHoraExtra, bonoEmpleado, isssDetallePlanilla, afpDetallePlanilla, rentaDetallePlanilla, despuesRetenciones, liquidoDetallePlanilla, idPlanilla) 
                        VALUES('$idEmpleado', '$salarioEmpleado', '$precioHoraExtra', '$bonoEmpleado', '$isssEmpleado', '$afpEmpleado', '$rentaEmpleado','$liquido', '$liquido', '$p')";
                if($this->db->query($sql)){
                    $flag++;
                }
            }
            if($flag == sizeof($personal)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public function personalXPlanilla($flag = null){
        $sql = "SELECT p.nombreEmpleado, p.cargoEmpleado, p.areaEmpleado, p.precioHoraNocturna, dp.* FROM tbl_detalle_planilla AS dp
                INNER JOIN tbl_personal_planilla AS p ON(dp.idEmpleado = p.idEmpleado)
                WHERE dp.idPlanilla = '$flag' ORDER BY p.areaEmpleado ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function estadoPlanilla($flag = null){
        $sql = "SELECT * FROM tbl_planilla WHERE idPlanilla = '$flag' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function historialPlanillas(){
        $sql = "SELECT * FROM tbl_planilla";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarVacaciones($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_planilla SET liquidoDetallePlanilla = ?, totalVacaciones = ? WHERE idDetallePlanilla = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function guardarHorasExtras($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_planilla SET otrosDetallePlanilla = ?, horasExtras = ?, totalHorasExtras = ?, rentaDetallePlanilla = ?, liquidoDetallePlanilla = ?, editadoDetallePlanilla = '1' WHERE idDetallePlanilla = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function agregarRetenciones($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_planilla SET isssDetallePlanilla = ?, afpDetallePlanilla = ?, rentaDetallePlanilla = ?, totalVacaciones = ?, liquidoDetallePlanilla = ?,
                    editadoDetallePlanilla = '1' WHERE idDetallePlanilla = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function asignarLiquido($data = null){
        if($data != null){
            $sql = "UPDATE tbl_detalle_planilla SET liquidoDetallePlanilla = ?, editadoDetallePlanilla = '1' WHERE idDetallePlanilla = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function cerrarPlanilla($data = null){
        if($data != null){

            $sql = "UPDATE tbl_planilla SET estadoPlanilla = ? WHERE idPlanilla = ?";
            $sql2 = "UPDATE tbl_empleado_x_descuentos AS exd SET exd.estadoDescuento = 1 WHERE exd.estadoDescuento = 2";
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

    public function planillaExcel($pivote = null){
        /* $sql = "SELECT pp.nombreEmpleado, p.liquidoDetallePlanilla FROM tbl_detalle_planilla AS p 
                INNER JOIN tbl_personal_planilla AS pp ON(p.idEmpleado = pp.idEmpleado)
                WHERE p.idPlanilla = '$pivote' "; */
        $sql = "SELECT pp.nombreEmpleado, dp.liquidoDetallePlanilla, dp.idEmpleado, pp.seguimientoEmpleado, p.descripcionPlanilla, p.idPlanilla, dp.salarioEmpleado,
                dp.isssDetallePlanilla, dp.afpDetallePlanilla, rentaDetallePlanilla FROM tbl_detalle_planilla AS dp
                INNER JOIN tbl_planilla AS p ON(dp.idPlanilla = p.idPlanilla)
                INNER JOIN tbl_personal_planilla AS pp ON(dp.idEmpleado = pp.idEmpleado)
                WHERE dp.idPlanilla = '$pivote' ORDER BY pp.areaEmpleado ";
            /* $sql = "SELECT 
                    pp.nombreEmpleado, dp.liquidoDetallePlanilla, SUM(ae.montoAbono) AS descuento, pp.seguimientoEmpleado, p.descripcionPlanilla
                    FROM tbl_detalle_planilla AS dp
                    INNER JOIN tbl_planilla AS p ON(dp.idPlanilla = p.idPlanilla)
                    INNER JOIN tbl_personal_planilla AS pp ON(dp.idEmpleado = pp.idEmpleado)
                    LEFT JOIN tbl_empleado_x_descuentos AS exd ON(exd.idEmpleado = pp.idEmpleado)
                    LEFT JOIN tbl_abonos_empleados AS ae ON(ae.idEmpleadoDescuento = exd.idEmDes)
                    WHERE dp.idPlanilla = '$pivote'
                    GROUP BY dp.idDetallePlanilla"; */
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function totalDescuentosMes($p = null, $emp = null){
        $sql = "SELECT exd.idEmpleado, SUM(ae.montoAbono) AS descuento
                FROM tbl_abonos_empleados AS ae
                INNER JOIN tbl_empleado_x_descuentos AS exd ON(exd.idEmDes = ae.idEmpleadoDescuento)
                WHERE ae.idPlanilla = '$p' AND exd.idEmpleado = '$emp'
                GROUP BY exd.idEmpleado";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function comprobantePlanilla($pivote = null){
        $sql = "SELECT
                pp.nombreEmpleado, ah.nombreArea,dp.salarioEmpleado, dp.bonoEmpleado, dp.otrosDetallePlanilla, dp.totalHorasExtras, dp.isssDetallePlanilla, dp.afpDetallePlanilla,
                dp.rentaDetallePlanilla, dp.liquidoDetallePlanilla, dp.totalVacaciones, dp.descuentosPlanilla, dp.detalleDescuentos, p.descripcionPlanilla
                FROM tbl_detalle_planilla AS dp
                INNER JOIN tbl_planilla AS p ON(p.idPlanilla = dp.idPlanilla)
                INNER JOIN tbl_personal_planilla AS pp ON(dp.idEmpleado = pp.idEmpleado)
                INNER JOIN tbl_areas_hospital AS ah ON(pp.areaEmpleado = ah.idArea)
                WHERE dp.idPlanilla = '$pivote' ORDER BY ah.idArea ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function comprobanteXEmpleado($pivote = null){
        $sql = "SELECT
                pp.nombreEmpleado, ah.nombreArea, dp.salarioEmpleado, dp.bonoEmpleado, dp.otrosDetallePlanilla, dp.totalHorasExtras, dp.isssDetallePlanilla, dp.afpDetallePlanilla,
                dp.totalVacaciones, dp.rentaDetallePlanilla, dp.liquidoDetallePlanilla, dp.descuentosPlanilla, dp.detalleDescuentos, p.descripcionPlanilla
                FROM tbl_detalle_planilla AS dp
                INNER JOIN tbl_planilla AS p ON(p.idPlanilla = dp.idPlanilla)
                INNER JOIN tbl_personal_planilla AS pp ON(dp.idEmpleado = pp.idEmpleado)
                INNER JOIN tbl_areas_hospital AS ah ON(pp.areaEmpleado = ah.idArea)
                WHERE dp.idDetallePlanilla = '$pivote'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarEmpleado($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_personal_planilla(nombreEmpleado, areaEmpleado, salarioEmpleado, bonoEmpleado, cargoEmpleado, precioHoraExtra, duiEmpleado,
                    correoEmpleado, telefonoEmpleado, estadoEmpleado, nacimientoEmpleado, ingresoEmpleado, direccionEmpleado)
                    values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarEmpleado($data = null){
        if($data != null){
            $sql = "UPDATE tbl_personal_planilla SET nombreEmpleado = ?, areaEmpleado = ?, salarioEmpleado = ?, bonoEmpleado = ?, cargoEmpleado = ?,
                    precioHoraExtra = ?, duiEmpleado = ?, correoEmpleado = ?, telefonoEmpleado = ?, estadoEmpleado = ?, nacimientoEmpleado = ?,
                    ingresoEmpleado = ?, direccionEmpleado = ? WHERE idEmpleado = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function eliminarEmpleado($data = null){
        if($data != null){
            $sql = "UPDATE tbl_personal_planilla SET estadoEmpleado = 2 WHERE idEmpleado = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerDescargosPlanilla($data = null){
        $sql = "SELECT * FROM tbl_descuentos_planilla";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarInfoDescargo($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_descuentos_planilla(nombreDP) VALUES(?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarInfoDescargo($data = null){
        if($data != null){
            $sql = "UPDATE tbl_descuentos_planilla SET nombreDP = ? WHERE idDP = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function cargarDescuentoEmpleado($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_empleado_x_descuentos(idEmpleado, idDescuento, montoEmDes, cuotaDescuento) VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerDescuentosEmpleado($emp = null){
        $sql = "SELECT * FROM tbl_empleado_x_descuentos AS exd
                INNER JOIN tbl_descuentos_planilla AS dd ON(exd.idDescuento = dd.idDP)
                WHERE exd.idEmpleado = '$emp' AND exd.estadoDescuento = '1'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerSueldos($emp = null){
        $sql = "SELECT p.fechaPlanilla, p.mesPlanilla, p.descripcionPlanilla, dp.* FROM tbl_detalle_planilla AS dp 
                INNER JOIN tbl_planilla AS p ON(dp.idPlanilla = p.idPlanilla)
                WHERE dp.idEmpleado = '$emp' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    /* public function descuentosActivos(){
        $sql = "SELECT 
                pp.idEmpleado, dp.nombreDP, pp.nombreEmpleado, ed.montoEmDes, ed.cuotaDescuento, ed.totalAbonado
                FROM tbl_empleado_x_descuentos AS ed
                INNER JOIN tbl_personal_planilla AS pp ON(pp.idEmpleado = ed.idEmpleado)
                INNER JOIN tbl_descuentos_planilla AS dp ON(dp.idDP = ed.idDescuento)
                WHERE ed.estadoDescuento = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    } */

    public function descuentosActivos($planilla = null){
        /* $sql = "SELECT
                CONCAT(emp.nombreEmpleado,' ', emp.apellidoEmpleado) AS empleado, exd.idEmDes, exd.montoEmDes, exd.cuotaDescuento,
                exd.totalAbonado, exd.estadoDescuento, dp.idDetallePlanilla, dp.liquidoDetallePlanilla, p.idPlanilla
                FROM tbl_detalle_planilla AS dp
                INNER JOIN tbl_empleado_x_descuentos AS exd ON(dp.idEmpleado = exd.idEmpleado)
                INNER JOIN tbl_planilla AS p ON(dp.idPlanilla = p.idPlanilla)
                INNER JOIN tbl_empleados AS emp ON(dp.idEmpleado = emp.idEmpleado)
                WHERE p.idPlanilla = '$planilla' AND exd.estadoDescuento = 1"; */
        $sql = "SELECT 
                dp.idDetallePlanilla, dp.liquidoDetallePlanilla, dp.idPlanilla, pp.nombreEmpleado as empleado, exd.idEmDes, exd.montoEmDes, 
                exd.cuotaDescuento, exd.totalAbonado, exd.estadoDescuento, exd.* 
                FROM tbl_empleado_x_descuentos AS exd
                INNER JOIN tbl_personal_planilla AS pp ON(exd.idEmpleado = pp.idEmpleado)
                INNER JOIN tbl_detalle_planilla AS dp ON(dp.idEmpleado = exd.idEmpleado)
                WHERE exd.estadoDescuento = 1 AND dp.idPlanilla = '$planilla' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    /* public function empleadoDescuento($emp = null, $p = null){
        $sql = "SELECT * FROM tbl_detalle_planilla AS dp
                WHERE dp.idEmpleado = '$emp' AND dp.idPlanilla = '$p' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    } */

    public function empleadoDescuento($fila = null){
        $sql = "SELECT * FROM tbl_detalle_planilla AS dp
                WHERE dp.idDetallePlanilla = '$fila' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarAbonoEmpleado($data = null){
        if($data != null){
            $cuota = $data["cuota"];
            $liquido = $data["liquido"];
            $nuevoLiquido = $data["nuevoLiquido"];
            $idEmpleadoDescuento = $data["idEmpleadoDescuento"]; // idEmDes en tbl_empleado_x_descuentos,
            $idDescuento = $data["idDescuento"]; // idDP en tbl_descuentos_planilla,
            $fila = $data["fila"];
            $planilla = $data["planilla"];

            $descuento = $data["descuento"];
            $detalleDescuentos = $data["detalleDescuentos"];

            $sql = "INSERT INTO tbl_abonos_empleados(idEmpleadoDescuento, montoAbono, idPlanilla) VALUES('$idEmpleadoDescuento', '$cuota', '$planilla')";

            $sql2 = "UPDATE tbl_detalle_planilla AS dp SET dp.liquidoDetallePlanilla = '$nuevoLiquido', dp.editadoDetallePlanilla = 2,
                    dp.descuentosPlanilla = '$descuento', dp.detalleDescuentos = '$detalleDescuentos'
                    WHERE dp.idDetallePlanilla = '$fila' ";

            if($this->db->query($sql)){
                $this->db->query($sql2);
                return true;
            }else{
                return false;
            }
        }
        
    }

    public function actualizarLiquido($l = null, $i = null){
        $sql = "UPDATE tbl_detallePlanilla SET liquidoDetallePlanilla = '$l' WHERE idDetallePlanilla = '$i'";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function saldarDescuento($data){
        if($data != null){
            $sql = "UPDATE tbl_empleado_x_descuentos SET estadoDescuento = '0' WHERE idEmDes = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerHistorialDescuentos($data = null){
        if($data != null){
            $sql = "SELECT
                    ae.montoAbono, p.descripcionPlanilla, DATE(ae.fechaAbono) AS fecha
                    FROM tbl_abonos_empleados AS ae
                    INNER JOIN tbl_planilla AS p ON(ae.idPlanilla = p.idPlanilla)
                    WHERE ae.idEmpleadoDescuento = ? ";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }
    }

    /* Resetear usuarios */
        public function obtenerDetallePlanilla($flag = null){
            $sql = "SELECT * FROM tbl_detalle_planilla AS dp WHERE dp.idDetallePlanilla = '$flag' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function resetearFila($row = null){
            if($row != null){
                $sql = "UPDATE tbl_detalle_planilla AS dp SET dp.otrosDetallePlanilla = '0', dp.horasExtras ='0', dp.totalHorasExtras = '0', dp.totalVacaciones = '0',
                        dp.descuentosPlanilla = '0', dp.detalleDescuentos = ' ', editadoDetallePlanilla = '0'
                        WHERE dp.idDetallePlanilla = '$row' ";
                if($this->db->query($sql)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function actualizarFila($data = null){
            if($data != null){
                $sql = "UPDATE tbl_detalle_planilla SET salarioEmpleado = ?, precioHoraExtra = ?, bonoEmpleado = ?, isssDetallePlanilla = ?, afpDetallePlanilla = ?, 
                                rentaDetallePlanilla = ?, despuesRetenciones = ?, liquidoDetallePlanilla = ?
                        WHERE idDetallePlanilla = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    /* Resetear usuarios */


    // Encriptar
    public function personalEncriptar(){
        $sql = "SELECT * FROM tbl_personal_planilla WHERE estadoEmpleado = 1";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function personalUEncriptar($str, $id){
        $sql = "UPDATE tbl_personal_planilla SET seguimientoEmpleado = '$str' WHERE idEmpleado = '$id'";
        $this->db->query($sql);
        //$datos = $this->db->query($sql);
        //return $datos->result();
    }

    public function nombreDescuento($id = null){
        if($id != null){
            $sql = "SELECT * FROM tbl_descuentos_planilla AS dp WHERE dp.idDP = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }
    

    public function guardarMontos($data = null){
        if($data != null){
            $liquido = $data["txtLiquidoEmpleado"];
            $sql = "UPDATE tbl_detalle_planilla SET otrosDetallePlanilla = ?, horasExtras = ?, totalHorasExtras = ?, horasNocturnas = ?, totalHorasNocturnas = ?,
                    isssDetallePlanilla = ?, afpDetallePlanilla = ?, rentaDetallePlanilla = ?, liquidoDetallePlanilla = ?, editadoDetallePlanilla = '1' WHERE idDetallePlanilla = ?";
            if($this->db->query($sql, $data)){
                return $liquido;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function obtenerFila($fila = null){
        $sql = "SELECT * FROM tbl_detalle_planilla AS dp WHERE dp.idDetallePlanilla = '$fila' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function cuentasPorPagar($empleado = null){
        $sql = "SELECT 
                (SELECT COALESCE(SUM(hi.cantidadInsumo * hi.precioInsumo), 0)  FROM tbl_hoja_insumos AS hi WHERE (hi.idHoja = hc.idHoja)) AS interno,
                (SELECT COALESCE(SUM(he.precioExterno * he.cantidadExterno), 0) FROM tbl_hoja_externos AS he WHERE (he.idHoja = hc.idHoja)) AS externo,
                hc.idHoja, hc.fechaHoja, hc.tipoHoja, hc.codigoHoja, hc.fechaIngresoHoja, p.nombrePaciente, p.edadPaciente, m.nombreMedico, h.numeroHabitacion
                FROM tbl_hoja_cobro as hc 
                INNER JOIN tbl_pacientes as p on(hc.idPaciente = p.idPaciente)
                INNER JOIN tbl_medicos as m on(hc.idMedico = m.idMedico) 
                INNER JOIN tbl_habitacion as h on(hc.idHabitacion = h.idHabitacion)
                WHERE hc.correlativoSalidaHoja = 0 AND	hc.responsableHoja = '$empleado' AND hc.anulada = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }


    public function planillaEnfermeria($planilla = null){
        $sql = "SELECT pp.idEmpleado, pp.nombreEmpleado, p.descripcionPlanilla, dp.* FROM tbl_detalle_planilla AS dp
                INNER JOIN tbl_planilla AS p ON(dp.idPlanilla = p.idPlanilla)
                INNER JOIN tbl_personal_planilla AS pp ON(dp.idEmpleado = pp.idEmpleado)
                WHERE dp.idPlanilla = '$planilla' AND pp.areaEmpleado = '4' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }


}
?>
