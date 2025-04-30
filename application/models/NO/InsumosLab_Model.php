<?php
class InsumosLab_Model extends CI_Model {

    public function obtenerInsumos(){
        $sql = "SELECT * FROM tbl_insumos_lab AS il INNER JOIN tbl_clasificacion_ri AS cri ON(il.tipoInsumoLab = cri.idClasificacionRI) WHERE il.estadoInsumoLab = '1' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    } 

    public function clasificacionRI(){
        $sql = "SELECT * FROM tbl_clasificacion_ri";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarInsumoLab($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_insumos_lab(codigoInsumoLab, nombreInsumoLab, tipoInsumoLab, stockInsumoLab, medidaInsumoLab, estadoInsumoLab)
                    VALUES(?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarInsumosLab($data = null){
        if($data != null){
            $sql = "UPDATE tbl_insumos_lab SET nombreInsumoLab = ?, tipoInsumoLab = ?, stockInsumoLab = ?, medidaInsumoLab = ? WHERE idInsumoLab = ? ";
            if($this->db->query($sql, $data)){
                return $data["nombreIL"];
            }else{
                return "0";
            }
        }else{
            return "0";
        }
    }

    public function eliminarInsumoLab($data = null){
        if($data != null){
            $sql = "UPDATE tbl_insumos_lab SET estadoInsumoLab = '0' WHERE idInsumoLab = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerCodigoIL(){
        $sql = "SELECT codigoInsumoLab FROM tbl_insumos_lab WHERE idInsumoLab = (SELECT MAX(idInsumoLab) FROM tbl_insumos_lab ) ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerProveedores(){
        $sql = "SELECT * FROM tbl_proveedores";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function guardarCompra($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_compras_lab(tipoCompraLab, documentoCompraLab, numeroCompraLab, idProveedor, fechaCompraLab, plazoCompraLab, descripcionCompraLab, estadoCompraLab)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idFactura = $this->db->insert_id(); // Id de la factura de compra.
                return $idFactura;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function extrasCompra($data = null){
        if($data != null){
            $sql = "UPDATE tbl_compras_lab SET ivaPercibido = ?, descuentoCompraLab = ? WHERE idCompraLab = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
            
    }

    public function listaCompras(){
        $sql = "SELECT p.empresaProveedor, cl.* FROM tbl_compras_lab AS cl INNER JOIN tbl_proveedores AS p ON(cl.idProveedor = p.idProveedor)";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleCabeceraCompra($id = null){
        $sql = "SELECT p.empresaProveedor, cl.* FROM tbl_compras_lab AS cl
                INNER JOIN tbl_proveedores AS p ON(cl.idProveedor = p.idProveedor) WHERE cl.idCompraLab = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function detalleContenidoCompra($id = null){
        $sql = "SELECT il.nombreInsumoLab, dcl.* FROM tbl_detalle_compra_lab AS dcl
                INNER JOIN tbl_insumos_lab AS il ON(dcl.idInsumoLab = il.idInsumoLab)
                WHERE dcl.idCompraIL = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function actualizarContenidoCompra($data = null){
        if($data != null){
            $detalle = $data["detalle"];
            $sql = "UPDATE tbl_detalle_compra_lab SET cantidadDetalleCL = ?, precioDetalleCL = ?, descuentoDetalleCL = ? WHERE idDetalleCL = ? ";
            if($this->db->query($sql, $detalle)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function eliminarContenidoCompra($data = null){
        if($data != null){
            $detalle = $data["detalle"];
            $sql = "DELETE FROM tbl_detalle_compra_lab WHERE idDetalleCL = ? ";
            if($this->db->query($sql, $detalle)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function stockIL($id = null){
        if(!is_null($id)){
            $sql = "SELECT * FROM tbl_insumos_lab WHERE idInsumoLab = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function actualizarDatosIL($data = null){
        if($data != null){
            $sql = "UPDATE tbl_insumos_lab SET precioInsumoLab = ?, stockInsumoLab = ? WHERE idInsumoLab = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function agregarDetalleCIL($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_detalle_compra_lab(idInsumoLab, idCompraIL, cantidadDetalleCL, precioDetalleCL, vencimientoDetalleCL, medidaDetalleCL, descuentoDetalleCL)
                VALUES(?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $detalleCL = $this->db->insert_id(); // Id fila detalle de la compra;
                return $detalleCL;
            }else{
                return 0;
            }
        }else{ 
            return 0;
        }
    }

    public function borrarDetalleCIL($id = null){
        $sql = "DELETE FROM tbl_detalle_compra_lab WHERE idDetalleCL = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    // Gestion de insumos
        public function crearCuenta($data = null){
            if ($data != null){
                $sql = "INSERT INTO tbl_cuentas_gestion_insumos(fechaCuenta) VALUES(?)";
                if($this->db->query($sql, $data)){
                    $idCuenta = $this->db->insert_id(); // Id del senso de este dia
                    return $idCuenta;
                }
            }else{
                return 0;
            }

        }
        public function obtenerCuentas(){
            $sql = "SELECT * FROM tbl_cuentas_gestion_insumos ORDER BY fechaCuenta DESC ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function ultimaCuenta(){
            $sql = "SELECT fechaCuenta as cuenta, fechaCuenta FROM tbl_cuentas_gestion_insumos WHERE idCuenta = (SELECT MAX(idCuenta) FROM tbl_cuentas_gestion_insumos) ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function obtenerDetalleCuenta($id = null){
            $sql = "SELECT il.idInsumoLab, il.nombreInsumoLab, il.medidaInsumoLab, dl.idDescargosLab, dl.cantidadInsumo, date(dl.fechaDescargo) AS fechaDescargo, 
                    il.codigoInsumoLab, il.controlado
                    FROM tbl_dcuenta_descargoslab AS dl
                    INNER JOIN tbl_insumos_lab AS il ON(dl.idInsumo = il.idInsumoLab) WHERE dl.idCuentaDescargo = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function obtenerCuenta($id = null){
            $sql = "SELECT * FROM tbl_cuentas_gestion_insumos WHERE idCuenta = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function obtenerInsumosReactivos(){
            $sql = "SELECT * FROM tbl_insumos_lab WHERE estadoInsumoLab = '1' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function buscarInsumo($i = null){
            $sql = "SELECT idInsumoLab, codigoInsumoLab, stockInsumoLab
                    FROM tbl_insumos_lab WHERE idInsumoLab = '$i' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function descontarInsumo($data = null){
            if($data != null){
                $cuenta = $data["cuenta"];
                $sql = "INSERT INTO tbl_dcuenta_descargoslab(idCuentaDescargo, idInsumo, cantidadInsumo, por) VALUES(?, ?, ?, ?)";
                if($this->db->query($sql, $cuenta)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function editarCuenta($data = null){
            if($data != null){
                $sql = "UPDATE tbl_insumos_lab SET stockInsumoLab = ? WHERE idInsumoLab = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    
        public function editarFilaCuenta($data = null){
            if($data != null){
                $sql = "UPDATE tbl_dcuenta_descargoslab SET cantidadInsumo = ? WHERE idDescargosLab = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function seleccionarDetalle($id){
            $sql = "SELECT * FROM tbl_dcuenta_descargoslab WHERE idDescargosLab = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function eliminarDetalle($data = null){
            if($data != null){
                $sql = "DELETE FROM tbl_dcuenta_descargoslab WHERE idDescargosLab = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function resumenCuentaGestion($cuenta = null){
            $sql = "SELECT
                    SUM(dd.cantidadInsumo) AS cantidadInsumo, il.codigoInsumoLab, il.nombreInsumoLab, il.idInsumoLab,
                    gi.fechaCuenta
                    FROM tbl_dcuenta_descargoslab AS dd
                    INNER JOIN tbl_cuentas_gestion_insumos gi ON(dd.idCuentaDescargo = gi.idCuenta)
                    INNER JOIN tbl_insumos_lab AS il ON(dd.idInsumo = il.idInsumoLab)
                    WHERE gi.idCuenta = '$cuenta' GROUP BY dd.idInsumo";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function cerrarCuentaDescargos($data = null){
            if($data != null){
                $sql = "UPDATE tbl_cuentas_gestion_insumos SET estadoCuenta = ? WHERE idCuenta = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    // Fin gestion de insumos

    // Gestion de donantes
        public function obtenerCodigo(){
            $sql = "SELECT * FROM tbl_donantes WHERE idDonante = (SELECT MAX(idDonante) FROM tbl_donantes)";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function guardarDonante($data = null){
            if($data != null){
                $codigo = $this->db->query("SELECT MAX(di.codigoDonanteInsumo) AS codigo FROM tbl_donantes_insumo AS di")->row();
                $cod = $codigo->codigo + 1;
                $insumo = $data["insumoDonante"];
                $precio = $data["precioDonante"];

                unset($data["insumoDonante"]);
                unset($data["precioDonante"]);

                $sql = "INSERT INTO tbl_donantes(codigoDonante, nombreDonante, edadDonante, duiDonante) VALUES(?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    $idDonante = $this->db->insert_id(); // Id del donante.
                    $sql2 = "INSERT INTO tbl_donantes_insumo(idDonante, idInsumo, precioInsumo, codigoDonanteInsumo) VALUES('$idDonante', '$insumo', '$precio', '$cod')";
                    $this->db->query($sql2);
                    return $idDonante;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

        public function nuevoDonativo($data = null){
            if($data != null){
                $codigo = $this->db->query("SELECT MAX(di.codigoDonanteInsumo) AS codigo FROM tbl_donantes_insumo AS di")->row();
                $cod = $codigo->codigo + 1;

                $insumo = $data["insumoDonante"];
                $precio = $data["precioDonante"];

                unset($data["insumoDonante"]);
                unset($data["precioDonante"]);

                $sql = "UPDATE tbl_donantes SET nombreDonante = ?, edadDonante = ?, duiDonante = ? WHERE idDonante = ?";

                if($this->db->query($sql, $data)){
                    $idDonante = $data["idDonante"]; // Id del donante.
                    $sql2 = "INSERT INTO tbl_donantes_insumo(idDonante, idInsumo, precioInsumo, codigoDonanteInsumo) VALUES('$idDonante', '$insumo', '$precio', '$cod')";
                    $this->db->query($sql2);
                    return $idDonante;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

        public function actualizarDatosDonante($data = null){
            if($data != null){
                $sql = "UPDATE tbl_donantes SET nombreDonante = ?, edadDonante = ?, duiDonante = ? WHERE idDonante = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function listaDonantes(){
            $sql = "SELECT d.*, il.nombreInsumoLab, di.* FROM tbl_donantes_insumo AS di
                    INNER JOIN tbl_donantes AS d ON(di.idDonante = d.idDonante)
                    INNER JOIN tbl_insumos_lab AS il ON(il.idInsumoLab = di.idInsumo)
                    WHERE idDonanteInsumo = (SELECT MAX(di2.idDonanteInsumo) FROM tbl_donantes_insumo AS di2 WHERE di2.idDonante = di.idDonante)";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function boletaDonante($id = null){
            $sql = "SELECT d.*, il.nombreInsumoLab, di.* FROM tbl_donantes_insumo AS di
                    INNER JOIN tbl_donantes AS d ON(di.idDonante = d.idDonante)
                    INNER JOIN tbl_insumos_lab AS il ON(il.idInsumoLab = di.idInsumo)
                    WHERE idDonanteInsumo = (SELECT MAX(di2.idDonanteInsumo) FROM tbl_donantes_insumo AS di2 WHERE di2.idDonante = '$id')";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function listaInsumos(){
            $sql = "SELECT * from tbl_insumos_lab AS il WHERE il.editable = '1' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function listaMedicamentos($pivote = null){
            $sql = "SELECT * FROM tbl_medicamentos WHERE pivoteMedicamento = '$pivote' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function descontarMedicamento($data = null){
            if($data != null){
                $cuenta = $data["cuenta"];
                $ins = $data["insumo"];
                $sql = "INSERT INTO tbl_descargos_donantes(idCuentaDescargo, idInsumo, cantidadInsumo, por) VALUES(?, ?, ?, ?)";
                
                $sql2 = "UPDATE tbl_insumos_lab SET stockInsumoLab = ? WHERE idInsumoLab = ? ";
                if($this->db->query($sql, $cuenta)){
                    if($this->db->query($sql2, $ins)){
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

        public function detalleDonante($id = null){
            $sql = "SELECT il.idInsumoLab, il.codigoInsumoLab, il.nombreInsumoLab, dd.* FROM tbl_descargos_donantes AS dd
                    INNER JOIN tbl_insumos_lab AS il ON(dd.idInsumo = il.idInsumoLab) 
                    WHERE dd.idCuentaDescargo = '$id' AND dd.pivote = '0'";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function detalleExtraDonante($id = null){
            $sql = "SELECT m.idMedicamento, m.codigoMedicamento, m.nombreMedicamento, dd.* FROM tbl_descargos_donantes AS dd
                    INNER JOIN tbl_medicamentos AS m ON(dd.examenPivote = m.idMedicamento) 
                    WHERE dd.idCuentaDescargo = '$id' AND dd.pivote = '1'";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function detalleCuenta($id = null){
            $sql = "SELECT codigoDonante, nombreDonante, edadDonante, DATE(fechaDonante) AS fecha, ultimaFecha FROM tbl_donantes WHERE idDonante = '$id'  ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function editarFilaDonante($data = null){
            if($data != null){
                $sql = "UPDATE tbl_descargos_donantes SET cantidadInsumo = ? WHERE idDescargosDonante = ? ";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function seleccionarDetalleDonante($id = null){
            $sql = "SELECT il.nombreInsumoLab, DATE(di.fechaDonanteInsumo) AS fecha, di.* FROM tbl_donantes_insumo AS di 
            INNER JOIN tbl_insumos_lab AS il ON(il.idInsumoLab = di.idInsumo)
            WHERE di.idDonante = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function eliminarDetalleDonante($data = null){
            if($data != null){
                $fila = $data["filaDetalle"];
                $stock = $data["stock"];
                $sql = "UPDATE tbl_insumos_lab SET stockInsumoLab = ? WHERE idInsumoLab = ? ";
                $sql2 = "DELETE FROM tbl_descargos_donantes WHERE idDescargosDonante = '$fila' ";
                if($this->db->query($sql, $stock)){
                    if($this->db->query($sql2)){
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

        public function descontarReactivo($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_descargos_donantes(idCuentaDescargo, idInsumo, cantidadInsumo, por, pivote, examenPivote) VALUES(?, ?, ?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    return true;
                    
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function seleccionarExamenDonante($id){
            $sql = "SELECT * FROM tbl_descargos_donantes WHERE idDescargosDonante = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function eliminarDetalleED($data = null){
            if($data != null){
                $fila = $data["filaDetalle"];
                $stock = $data["stock"];
                $sql = "UPDATE tbl_insumos_lab SET stockInsumoLab = ? WHERE idInsumoLab = ? ";
                $sql2 = "DELETE FROM tbl_descargos_donantes WHERE idDescargosDonante = '$fila' ";
                if($this->db->query($sql, $stock)){
                    if($this->db->query($sql2)){
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
        

    // Fin gestion de donantes


    // Para el control de fechas en reactivos
        public function guardarControles($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_control_reactivos(idIReactivo, fechaInicio, cuentaUso, estadoControlR) VALUES(?, ?, ?, ?)";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                }
            }else{
                return false;
            }
        }

        public function buscarReactivoEnUso($id = null){
            if($id != null){
                // $sql = "SELECT * FROM tbl_control_reactivos WHERE idIReactivo = '$id' AND estadoControlR = 0";
                $sql = "SELECT * FROM tbl_control_reactivos WHERE idControlR = (SELECT MAX(idControlR) FROM tbl_control_reactivos WHERE idIReactivo = '$id' AND estadoControlR = 1);";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

        public function actualizarReactivoEnUso($data = null){
            if($data != null){
                $sql = "UPDATE tbl_control_reactivos SET fechaFin = ?, estadoControlR = ?, conteoExamenes = ? WHERE idControlR = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }
        }

        public function obtenerResumenExamenes($reactivo = null, $inicio = null, $fin = null){
            if($reactivo != null){
                $sql = "SELECT * FROM tbl_pivote_reactivos WHERE idReactivo = '$reactivo' ";
                $datos = $this->db->query($sql)->row();

                // $sql = "SELECT * FROM tbl_examenes_realizados WHERE DATE(fechaExamenRealizado) BETWEEN '$inicio' AND '$fin' AND idExamen = '$datos->idExamen' ";
                $sql = "SELECT count(idExamenRealizado) as totalExamenes FROM tbl_examenes_realizados WHERE DATE(fechaExamenRealizado) BETWEEN '$inicio' AND '$fin' AND idExamen = '$datos->idExamen' ";
                $datos = $this->db->query($sql);
                return $datos->row();

            }
        }
    // Fin para el control de fechas en reactivos


    // Salidas de sangre
        public function nuevaSalida($data = null){
            if($data != null){
                $codigo = $this->db->query("SELECT MAX(ds.codigoDonanteSalida) AS codigo FROM tbl_donantes_salidas AS ds")->row();
                $cod = $codigo->codigo + 1;
                $data["codigo"] = $cod;

                $sql = "INSERT INTO tbl_donantes_salidas(nombrePaciente, edadPaciente, areaDonanteSalida, idInsumo, cantidadInsumo, 
                        precioInsumo, numeroBolsa, medicoDonanteSalida, pruebaCruzada, codigoDonanteSalida) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if($this->db->query($sql, $data)){
                    $idSalida = $this->db->insert_id(); // Id del donante.
                    return $idSalida;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }

        public function boletaSalida($id = null){
            $sql = "SELECT m.nombreMedico, DATE(ds.fechaDonanteSalida) AS fecha, il.nombreInsumoLab, ds.* FROM tbl_donantes_salidas AS ds
                    INNER JOIN tbl_medicos AS m ON(ds.medicoDonanteSalida = m.idMedico)
                    INNER JOIN tbl_insumos_lab AS il ON(ds.idInsumo = il.idInsumoLab)
                    WHERE ds.idDonanteSalida = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function listaSalidas(){
            $sql = "SELECT m.nombreMedico, m.idMedico, DATE(ds.fechaDonanteSalida) AS fecha, il.nombreInsumoLab, ds.* FROM tbl_donantes_salidas AS ds
                    INNER JOIN tbl_medicos AS m ON(ds.medicoDonanteSalida = m.idMedico)
                    INNER JOIN tbl_insumos_lab AS il ON(ds.idInsumo = il.idInsumoLab)";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function actualizarSalida($data = null){
            if($data != null){
            
                $sql = "UPDATE tbl_donantes_salidas SET nombrePaciente = ?, edadPaciente = ?, areaDonanteSalida = ?, idInsumo = ?, cantidadInsumo = ?,
                        precioInsumo = ?, numeroBolsa =?, medicoDonanteSalida = ?, pruebaCruzada = ?
                        WHERE idDonanteSalida = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    // Salidas de sangre
}
?> 


