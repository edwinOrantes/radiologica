<?php
class Reportes_Model extends CI_Model {

    // Obtener el numero correlativo de salida
    public function numeroCorrelativo(){
        $sql = "SELECT MAX(correlativoSalidaHoja) AS correlativo FROM tbl_hoja_cobro";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function seGeneroExternos($fecha = null){
        $sql = "SELECT * FROM tbl_externos_generados AS eg WHERE DATE(eg.fechaGenerado) = '$fecha' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function externosHoja($i, $f){
        $sql = "SELECT p.nombrePaciente, m.nombreMedico, e.nombreExterno, hc.*, he.*, ex.tipoEntidad FROM tbl_hoja_externos
                as he INNER JOIN tbl_hoja_cobro as hc ON(he.idHoja = hc.idHoja) INNER JOIN tbl_externos as e ON(he.idExterno = e.idExterno)
                INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos as m ON(hc.idMedico = m.idMedico)
                INNER JOIN tbl_externos as ex ON(he.idExterno = ex.idExterno) WHERE hc.correlativoSalidaHoja BETWEEN '$i' AND '$f' ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function hojasCerradas($i, $f){
        $sql = "SELECT * FROM tbl_hoja_cobro WHERE correlativoSalidaHoja > 0 AND salidaHoja BETWEEN '$i' AND '$f' ORDER BY correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function hojasCerradasCorrelativoSalida($i, $f){
        $sql = "SELECT * FROM tbl_hoja_cobro WHERE correlativoSalidaHoja > 0 AND correlativoSalidaHoja BETWEEN '$i' AND '$f' ORDER BY correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function insumosHoja($id){
        $sql = "SELECT m.nombreMedicamento, hi.precioInsumo, hi.cantidadInsumo FROM tbl_hoja_insumos as hi INNER JOIN tbl_medicamentos as m
        ON(hi.idInsumo = m.idMedicamento) WHERE hi.idHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function examenesHoja($id){
        $sql = "SELECT hc.idHoja, m.idMedicamento, m.nombreMedicamento, hi.cantidadInsumo, hi.precioInsumo,m.pivoteMedicamento
        FROM tbl_hoja_insumos as hi INNER JOIN tbl_hoja_cobro as hc ON(hi.idHoja = hc.idHoja) INNER JOIN tbl_medicamentos as m
        ON(hi.idInsumo = m.idMedicamento) WHERE m.pivoteMedicamento > 0 AND hc.correlativoSalidaHoja > 0 AND hi.idHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Trabajando liquidacion de caja
    public function ultimaSalida(){
        $sql = "SELECT MAX(correlativoSalidaHoja) AS salida FROM tbl_hoja_cobro";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerResumenHoja($i, $f){
        // Consulta anterior
        $sql = "SELECT hc.idHoja, hc.fechaHoja, hc.salidaHoja, hc.totalHoja, hc.codigoHoja, hc.anulada, hc.tipoHoja, hc.descuentoHoja, hc.porPagos,
                hc.esPaquete, p.nombrePaciente,
                (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE hi.idHoja = hc.idHoja) AS medicamentos,
                (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE he.idHoja = hc.idHoja) AS externos,
                (SELECT IFNULL(SUM(ah.montoAbono), 0) FROM tbl_abonos_hoja AS ah WHERE ah.idHoja = hc.idHoja AND ah.seLiquido = 1 AND ah.liquidacion != 0 AND hc.esPaquete = 0) AS abonado,
                hc.correlativoSalidaHoja, hc.credito_fiscal, med.nombreMedico, fe.fechaFactura
                FROM tbl_hoja_cobro as hc 
                INNER JOIN tbl_medicos AS med ON(hc.idMedico = med.idMedico)
                INNER JOIN tbl_pacientes  p ON(hc.idPaciente = p.idPaciente) 
                LEFT JOIN tbl_facturas_emitidas AS fe ON(fe.idHoja = hc.idHoja)
                WHERE hc.correlativoSalidaHoja
                BETWEEN '$i' AND '$f' 
                ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerMedicamentos($id = null){
        if($id != null){
            $sql = "SELECT * FROM tbl_hoja_insumos WHERE idHoja = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function obtenerExternos($id = null){
        if($id != null){
            $sql = "SELECT * FROM tbl_hoja_externos WHERE idHoja = '$id'  ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function cuentasPacientes($i = null, $f = null, $flag = null){
        if($flag != null){
            switch ($flag) {
                case '1':
                        $str = "AND tipoHoja = 'Ingreso'";
                    break;
                case '2':
                    $str = "AND tipoHoja = 'Ambulatorio'";
                    break;
                case '3':
                        $str = "";
                    break;
                default:
                        $str = "";
                    break;
            }
        }else{
            $str = "";
        }

        switch ($flag) {
            case '4':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.esPromocion = 0 AND hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0)  ORDER BY hc.idHoja";
                break;
            case '5':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.correlativoSalidaHoja > 0 AND hc.salidaHoja BETWEEN '$i' AND '$f' ".$str." ORDER BY hc.codigoHoja";
                break;
            
            case '6':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 7 ORDER BY hc.idHoja";
                break;

            case '7':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 2 ORDER BY hc.idHoja";
                break;
            case '8':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 3 ORDER BY hc.idHoja";
                break;
            case '9':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 4 ORDER BY hc.idHoja";
                break;
            case '10':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 5 ORDER BY hc.idHoja";
                break;
            case '11':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 6 ORDER BY hc.idHoja";
                break;
            case '12':
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0) AND hc.seguroHoja = 8 ORDER BY hc.idHoja";
                break;
            default:
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                        INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                        WHERE hc.anulada = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' ".$str." ORDER BY hc.codigoHoja";
                break;
        }
            
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function medicosCuentas(){
        $sql = "SELECT DISTINCT(hc.idMedico), m.nombreMedico FROM tbl_hoja_cobro AS hc INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function cuentasMedicos($i = null, $f = null, $med = null, $flag = null){
        if($flag != null){
            switch ($flag) {
                case '1':
                        $str = "AND tipoHoja = 'Ingreso'";
                    break;
                case '2':
                    $str = "AND tipoHoja = 'Ambulatorio'";
                    break;
                case '3':
                        $str = "";
                    break;
                default:
                        $str = "";
                    break;
            }
        }else{
            $str = "";
        }
        /* $sql = "SELECT p.nombrePaciente, m.nombreMedico, f.numeroFactura, f.tipoFactura, hc.* FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico) 
                INNER JOIN tbl_facturas AS f ON(hc.idHoja = f.idHojaCobro) WHERE salidaHoja BETWEEN '$i' AND '$f' ".$str."
                ORDER BY hc.correlativoSalidaHoja"; */
        if($flag == 4){
            $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                WHERE hc.anulada = 0 AND (hc.salidaHoja = '' OR hc.correlativoSalidaHoja = 0 ) AND hc.idMedico = '$med' AND hc.esPromocion = 0 ORDER BY hc.idHoja";
        }else{
            if($flag == 5){
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                WHERE hc.anulada = 0 AND hc.correlativoSalidaHoja > 0 AND hc.idMedico = '$med' AND hc.esPromocion = 0 AND hc.salidaHoja BETWEEN '$i' AND '$f' ".$str."
                ORDER BY hc.codigoHoja";
            }else{
                $sql = "SELECT p.nombrePaciente, m.nombreMedico, hc.* FROM tbl_hoja_cobro AS hc 
                INNER JOIN tbl_pacientes as p ON(hc.idPaciente = p.idPaciente) INNER JOIN tbl_medicos AS m ON(hc.idMedico = m.idMedico)
                WHERE hc.anulada = 0 AND hc.idMedico = '$med' AND hc.esPromocion = 0 AND hc.fechaHoja BETWEEN '$i' AND '$f' ".$str."
                ORDER BY hc.codigoHoja";
            }
        }
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleMedicamento($i = null, $f = null, $med = null){
        if($med != null){
            $sql = "SELECT m.nombreMedicamento, hc.tipoHoja, p.nombrePaciente, hi.idInsumo, hi.precioInsumo, hi.cantidadInsumo, hi.fechaInsumo
                    FROM tbl_hoja_insumos AS hi INNER JOIN tbl_medicamentos as m ON(hi.idInsumo = m.idMedicamento) INNER JOIN tbl_hoja_cobro 
                    AS hc ON(hi.idHoja = hc.idHoja) INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente) WHERE hi.idInsumo = '$med' 
                    AND hi.eliminado = 0 AND hi.fechaInsumo BETWEEN '$i' AND '$f' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }else{
            return 0;
        }
    }

    public function obtenerMedico($id, $entidad){
        if($entidad == 1){
            //$sql = "SELECT nombreMedico as nombre FROM tbl_medicos WHERE idMedico = '$id' ";
            $sql = "SELECT m.idMedico AS id, m.nombreMedico AS nombre FROM tbl_externos AS e INNER JOIN tbl_medicos as m ON(e.idEntidad = m.idMedico) WHERE e.idExterno = '$id' ";
        }else{
            //$sql = "SELECT empresaProveedor as nombre  FROM tbl_proveedores WHERE idProveedor = '$id' ";
            $sql = "SELECT p.idProveedor AS id, empresaProveedor as nombre  FROM tbl_externos AS e INNER JOIN tbl_proveedores as p ON(e.idEntidad = p.idProveedor) WHERE e.idExterno = '$id' ";
        }
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function facturasCredito($i, $f){
        $sql = "SELECT hc.idHoja, hc.tipoHoja, p.nombrePaciente, f.id_factura, f.numeroFactura, f.tipoFactura, f.estadoFactura, f.fechaFactura
                FROM tbl_facturas AS f INNER JOIN tbl_hoja_cobro as hc ON(f.idHojaCobro = hc.idHoja) INNER JOIN tbl_pacientes AS p 
                ON(hc.idPaciente = p.idPaciente) WHERE f.tipoFactura = 1  AND f.fechaFactura BETWEEN '$i' AND '$f'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function externosGenerados($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_externos_generados(inicioExternoGenerado, finExternoGenerado, fechaGenerado) VALUES(?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }            
        }else{
            return false;
        }
    }

    public function ultimoGenerado(){
        $sql = "SELECT MAX(finExternoGenerado) AS ultimoGenerado FROM tbl_externos_generados";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    // Metodo para obtener cuentas de costos y gastos
    public function cuentasCostosGastos($i, $f){
        $sql = "SELECT SUM(g.montoGasto) as total, cg.idCuenta, cg.nombreCuenta, cg.clasificacionCuenta, g.montoGasto, g.fechaGasto
                FROM tbl_gastos AS g INNER JOIN tbl_cuentas_gastos as cg ON(g.idCuentaGasto = cg.idCuenta) WHERE g.fechaGasto 
                BETWEEN '$i' AND '$f' GROUP BY cg.idCuenta";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Para reporte "Movimientos Botiquin"
    public function obtenerMedicamentosUsados($i, $f){
        $sql = "SELECT DISTINCT(k.idMedicamento) AS idMedicamento, m.nombreMedicamento, m.stockMedicamento, m.tipoMedicamento  FROM tbl_kardex AS k INNER JOIN tbl_medicamentos AS m 
                ON(k.idMedicamento = m.idMedicamento) WHERE k.descripcionMedicamento = 'Salida' AND k.fechaMedicamento BETWEEN '$i' AND '$f'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleMovimientoMedicamento($id, $i, $f){
        $sql = "SELECT k.* FROM tbl_kardex AS k INNER JOIN tbl_medicamentos AS m ON(k.idMedicamento = m.idMedicamento) 
                WHERE k.idMedicamento = '$id' AND k.fechaMedicamento BETWEEN '$i' AND '$f' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Para reporte "Compras"
    public function detalleCompras($i, $f){
        $sql = "SELECT p.empresaProveedor, cp.* from tbl_cuentas_pagar AS cp INNER JOIN tbl_proveedores AS p ON(cp.idProveedor = p.idProveedor)
        WHERE cp.fechaCuentaPagar BETWEEN '$i' AND '$f'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    // Extraer externos
    public function extraerExternos($id){
        $sql = "SELECT hc.correlativoSalidaHoja, hc.credito_fiscal, hc.porPagos, hc.esPaquete, he.idExterno, he.idHojaExterno, he.idHoja, he.cantidadExterno, he.precioExterno, he.fechaExterno
                FROM tbl_hoja_cobro AS hc INNER JOIN tbl_hoja_externos AS he ON(hc.idHoja = he.idHoja) WHERE correlativoSalidaHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarHonorarios($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_honorarios(correlativoSalidaHoja, idExterno, idHojaExterno, idHoja, precioExterno, estadoExterno, facturar, enBanco)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    // Nuevo reporte de total por externos
    public function listaExterno(){
        $sql = "SELECT * FROM tbl_externos ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function totalPorExterno($id = null){
        $sql = "SELECT SUM(precioExterno) AS total FROM tbl_honorarios WHERE estadoExterno = 0 AND idExterno = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerUSGRX($data = null){
        if($data != null){
            $pivote = $data["areaReporte"];
            $inicio = $data["fechaInicio"];
            $fin = $data["fechaFin"];
            $sql = "SELECT 
                    p.nombrePaciente, m.nombreMedicamento, hc.salidaHoja, hi.cantidadInsumo, hi.precioInsumo
                    FROM tbl_hoja_insumos AS hi
                    INNER JOIN tbl_hoja_cobro AS hc ON(hi.idHoja = hc.idHoja)
                    INNER JOIN tbl_pacientes AS p ON(hc.idPaciente = p.idPaciente)
                    INNER JOIN tbl_medicamentos AS m ON(hi.idInsumo = m.idMedicamento)
                    WHERE m.pivoteMedicamento = '$pivote'
                    AND DATE(hc.salidaHoja) BETWEEN '$inicio' AND '$fin' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function abonosLiquidar(){
        $sql = "SELECT
                hc.fechaHoja, hc.salidaHoja, hc.codigoHoja, hc.correlativoSalidaHoja, hc.esPaquete, p.nombrePaciente, m.nombreMedico,
                (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE (hi.idHoja = hc.idHoja)) AS medicamentos,
                (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE (he.idHoja = hc.idHoja)) AS externos,
                ah.idAbono, SUM(ah.montoAbono) AS montoAbono, ah.fechaAbono, ah.seLiquido
                FROM tbl_abonos_hoja AS ah
                INNER JOIN tbl_hoja_cobro AS hc ON(hc.idHoja = ah.idHoja)
                INNER JOIN tbl_pacientes AS p ON(p.idPaciente = hc.idPaciente)
                INNER JOIN tbl_medicos AS m ON(m.idMedico = hc.idMedico)
                WHERE ah.seLiquido = 0
                GROUP BY hc.idHoja"; // Todos los abonos que aun no se han liquidado
                // WHERE ah.seLiquido = 0 AND hc.correlativoSalidaHoja = 0;";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function ultimoLiquidacionGenerada(){
        $sql = "SELECT IFNULL(MAX(finLiquidacion), 0) AS ultimoLiquidado FROM tbl_liquidaciones_caja";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarLiquidacion($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_liquidaciones_caja(inicioLiquidacion, finLiquidacion, fechaLiquidacion, cajeraLiquidacion)
                    VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idLiquidacion = $this->db->insert_id();
                return $idLiquidacion;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function detalleLiquidacion($id = null){
        $sql = "SELECT
            lc.*, CONCAT(e.nombreEmpleado, ' ', e.apellidoEmpleado) AS empleado
            FROM tbl_liquidaciones_caja AS lc
            INNER JOIN tbl_usuarios AS u ON(lc.cajeraLiquidacion = u.idUsuario)
            INNER JOIN tbl_empleados AS e ON(e.idEmpleado = u.idEmpleado)
            WHERE lc.idLiquidacion = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function liquidarAbonos($data = null){
        if($data != null){
            $abonos = $data["abonos"];
            $liquidacion = $data["liquidacion"];
            // Descomentar para poder liquidar
            for ($i=0; $i < sizeof($abonos); $i++) { 
                // $sql = "UPDATE tbl_abonos_hoja AS hc SET hc.seLiquido = 1, hc.fechaLiquidado = CURRENT_DATE(), hc.liquidacion = '$liquidacion' WHERE hc.idAbono = '".$abonos[$i]->idAbono."' ";
                $sql = "UPDATE tbl_abonos_hoja AS hc SET hc.seLiquido = 1, hc.fechaLiquidado = CURRENT_DATE(), hc.liquidacion = '$liquidacion' ";
                $this->db->query($sql);
            }
            return true;
        }else{
            return false;
        }
    }

    public function validarPaquete($id = null){
        $sql = "SELECT hc.idHoja, hc.fechaHoja, hc.salidaHoja, hc.codigoHoja, hc.anulada, hc.tipoHoja, hc.descuentoHoja, hc.esPaquete, hc.totalPaquete,
                p.idPaciente, p.nombrePaciente, m.idMedico, m.nombreMedico,
                (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE hi.idHoja = hc.idHoja) AS medicamentos,
                (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE he.idHoja = hc.idHoja) AS externos
                FROM tbl_hoja_cobro as hc 
                INNER JOIN tbl_medicos AS med ON(hc.idMedico = med.idMedico)
                INNER JOIN tbl_pacientes  p ON(hc.idPaciente = p.idPaciente) 
                INNER JOIN tbl_medicos as m ON(hc.idMedico = m.idMedico)
                WHERE hc.idHoja = '$id'
                ORDER BY hc.correlativoSalidaHoja ASC";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function existeLiquidacion($fecha = null){
        $sql = "SELECT COALESCE(MAX(lc.idLiquidacion),'0') AS liquidacion FROM tbl_liquidaciones_caja AS lc WHERE lc.fechaLiquidacion = '$fecha' LIMIT 1";
        $datos = $this->db->query($sql);
        return $datos->row();
    }


    // Metodos para cajas
        public function obtenerCajas(){
            $sql = "SELECT * FROM tbl_cajas";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerRangoRecibos($data = null){
            if($data != null){
                $sql = "SELECT * FROM tbl_control_cajas AS cc WHERE cc.idCaja = ? AND cc.codigoReciboCaja BETWEEN ? AND ? AND cc.estadoRecibo = '1' ";
                $datos = $this->db->query($sql, $data);
                return $datos->result();
            }
        }

        public function guardarLiquidacionXCaja($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_liquidaciones_caja(cajaLiquidacion, inicioLiquidacion, finLiquidacion, fechaLiquidacion, cajeraLiquidacion, notaLiquidacion)
                        VALUES(?, ?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    $idLiquidacion = $this->db->insert_id();
                    return $idLiquidacion;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

        public function resumenXCaja($i = null, $f = null, $caja = null){
            $sql = "SELECT cc.codigoReciboCaja, cc.idCaja, hc.idHoja, hc.fechaHoja, hc.salidaHoja, hc.totalHoja, hc.codigoHoja, hc.anulada, hc.tipoHoja, hc.descuentoHoja, hc.porPagos,
                    hc.esPaquete, p.nombrePaciente,
                    (SELECT SUM(hi.cantidadInsumo * hi.precioInsumo) FROM tbl_hoja_insumos AS hi WHERE hi.idHoja = hc.idHoja) AS medicamentos,
                    (SELECT SUM(he.precioExterno * he.cantidadExterno) FROM tbl_hoja_externos AS he WHERE he.idHoja = hc.idHoja) AS externos,
                    (SELECT IFNULL(SUM(ah.montoAbono), 0) FROM tbl_abonos_hoja AS ah WHERE ah.idHoja = hc.idHoja AND ah.seLiquido = 1 AND ah.liquidacion != 0 AND hc.esPaquete = 0) AS abonado,
                    hc.correlativoSalidaHoja, hc.credito_fiscal, med.nombreMedico, fe.fechaFactura
                    FROM tbl_hoja_cobro as hc
                    INNER JOIN tbl_control_cajas AS cc ON(cc.idHoja = hc.idHoja)
                    INNER JOIN tbl_medicos AS med ON(hc.idMedico = med.idMedico)
                    INNER JOIN tbl_pacientes  p ON(hc.idPaciente = p.idPaciente) 
                    LEFT JOIN tbl_facturas_emitidas AS fe ON(fe.idHoja = hc.idHoja)
                    WHERE cc.codigoReciboCaja
                    BETWEEN '$i' AND '$f'
                    AND cc.idCaja = '$caja'
                    ORDER BY hc.correlativoSalidaHoja ASC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function minMaxCajas($data = null){
            if($data != null){
                $sql = "SELECT MIN(cc.codigoReciboCaja) AS minimo, MAX(cc.codigoReciboCaja) AS maximo FROM tbl_control_cajas cc
                        WHERE cc.idCaja = ? AND cc.estadoRecibo = 1";
                $datos = $this->db->query($sql, $data);
                return $datos->result();
            }
        }
    // Metodos para cajas

    public function liquidacionesCajas(){
        $sql = "SELECT lc.*, TIME_FORMAT(TIME(lc.creadoLiquidacion), '%h:%i %p') AS hora FROM tbl_liquidaciones_caja AS lc";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerLiquidacion($l = null){
        if($l != null){
            $sql = "SELECT * FROM tbl_liquidaciones_caja AS lc WHERE lc.idLiquidacion = '$l' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

}
?>

