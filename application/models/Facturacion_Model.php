<?php

// Importar la clase Curl
    use Curl\Curl;
// Importar la clase Curl

class Facturacion_Model extends CI_Model {


    public function obtenerHojaCobro($cod = null){
        if($cod != null){
            $sql ="SELECT COALESCE((SELECT v.idVenta FROM tbl_ventas AS v WHERE v.codigoVenta = '$cod' LIMIT 1), 0) AS hoja";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function listaCCF(){
        $sql ="SELECT * FROM tbl_dte_ccf ORDER BY idDTEFC DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function listaNC(){
        $sql ="SELECT * FROM tbl_dte_nc ORDER BY idDTEFC DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function listaND(){
        $sql ="SELECT * FROM tbl_dte_nd ORDER BY idDTEFC DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function listaCF(){
        $sql ="SELECT * FROM tbl_dte_fc ORDER BY idDTEFC DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function listaSE(){
        $sql ="SELECT * FROM tbl_dte_se ORDER BY idDTEFC DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleVenta($v = null){
        if($v != null){
            $sql = "SELECT m.nombreMedicamento, m.imagenMedicamento, m.cFiscal, v.codigoVenta, v.fechaVenta, v.subtotalVenta, v.ivaVenta, v.totalVenta, dv.* 
                FROM tbl_detalle_venta AS dv
                INNER JOIN tbl_ventas AS v ON(v.idVenta = dv.idVenta)
                INNER JOIN tbl_medicamentos AS m ON(m.idMedicamento = dv.idMedicamento)
                WHERE dv.idVenta = '$v' ORDER BY dv.idDetalleVenta ASC";
            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function medicamentosHoja($id = null){
        if($id != null){
            $sql = "SELECT hi.idHojaInsumo, m.nombreMedicamento, m.idMedicamento, m.stockMedicamento, m.usadosMedicamento, hi.precioInsumo, 
                    hi.cantidadInsumo, hi.descuentoUnitario, hi.fechaInsumo, hi.pivoteStock, hi.cantidadBase, hi.cantidadMG, hi.nombreMedida, m.precioVMedicamento,
                    m.tipoMedicamento, m.pivoteMedicamento, m.descuentoMedicamento
                    FROM tbl_hoja_insumos as hi INNER JOIN tbl_medicamentos as m
                    ON(hi.idInsumo = m.idMedicamento) WHERE hi.idHoja = '$id' AND hi.eliminado = '0'";
            $datos = $this->db->query($sql, $id);
            return $datos->result();
        }
    }

    public function externosHoja($id = null){
        if($id != null){
            $sql ="SELECT e.idExterno, e.nombreExterno, he.idHojaExterno, he.idHoja, he.cantidadExterno, he.precioExterno, he.fechaExterno
            FROM tbl_hoja_externos as he INNER JOIN tbl_externos as e ON(he.idExterno = e.idExterno) WHERE he.idHoja = ? ";
            $datos = $this->db->query($sql, $id);
            return $datos->result();
        }
    }

    public function obtenerSeguros(){
        $sql ="SELECT * FROM tbl_seguros as s WHERE s.esSeguro  = '1' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerEmpresa(){
        $sql ="SELECT * FROM tbl_empresa ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerDetalleCatalogo($pivote = null){
        $sql = "SELECT * FROM tbl_detalle_cm AS dc WHERE dc.idCatalogo = '$pivote' AND dc.estado = '1' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerDistritos($id = null){
        $sql = "SELECT * FROM tbl_detalle_cm AS dc WHERE dc.padre = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerPacienteAnexo($id = null){
        if($id != null){
            $sql = "SELECT * FROM tbl_anexo_facturacion AS af WHERE af.idPaciente = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function validarAnexo($id = null){
        if($id != null){
            $sql = "SELECT COALESCE(MAX(af.idAnexo), 0) AS existe FROM tbl_anexo_facturacion AS af WHERE af.idPaciente = '$id' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function guardarProveedorAnexo($data = null){
        if($data != null){
            $pivote = $data["pivote"];
            unset($data["pivote"]);

            if($pivote == 0){
                $sql = "INSERT INTO tbl_anexo_facturacion(idDepartamento, idMunicipio, nombrePaciente, duiPaciente, telefonoPaciente, correoPaciente,
                        codigoDepartamento, codigoMunicipio, direccionPaciente, actividadEconomica, tipoDocumento, nrcCreditoFiscal)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            }else{
                $sql = "UPDATE tbl_anexo_facturacion SET idDepartamento = ?, idMunicipio = ?, nombrePaciente = ?, duiPaciente = ?, telefonoPaciente = ?,
                    correoPaciente = ?, codigoDepartamento = ?, codigoMunicipio = ?, direccionPaciente = ?, actividadEconomica = ?, tipoDocumento = ?, nrcCreditoFiscal = ?
                    WHERE idAnexo = ?";
            }

            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }

        }
    }

    public function guardarAnexo($data = null){
        if($data != null){
            $flag = 0;
            $existe = $data["existe"];
            unset($data["existe"]);

            $sqlI = "INSERT INTO tbl_anexo_facturacion(idPaciente, idDepartamento, idMunicipio, nombrePaciente, duiPaciente, telefonoPaciente, correoPaciente,
                    codigoDepartamento, codigoMunicipio, direccionPaciente, actividadEconomica, tipoDocumento, nrcCreditoFiscal)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $sqlU = "UPDATE tbl_anexo_facturacion SET idDepartamento = ?, idMunicipio = ?, nombrePaciente = ?, duiPaciente = ?, telefonoPaciente = ?,
                    correoPaciente = ?, codigoDepartamento = ?, codigoMunicipio = ?, direccionPaciente = ?, actividadEconomica = ?, tipoDocumento = ?, nrcCreditoFiscal = ?
                    WHERE idPaciente = ?";

            if($existe == 0){
                if($this->db->query($sqlI, $data)){
                    $flag = 1;
                }
            }else{
                $idPaciente = $data["idPaciente"];
                unset($data["idPaciente"]);
                $data["idPaciente"] = $idPaciente;
                if($this->db->query($sqlU, $data)){
                    $flag = 1;
                }
            }

            if($flag == 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerDTE($id = null, $pivote = null){
        
        switch ($pivote) {
            case '1':
                $sql = "SELECT * FROM tbl_dte_fc AS dte WHERE dte.idDTEFC = '$id' ";
                break;
            case '3':
                $sql = "SELECT * FROM tbl_dte_ccf AS dte WHERE dte.idDTEFC = '$id' ";
                break;
            case '5':
                $sql = "SELECT * FROM tbl_dte_nc AS dte WHERE dte.idDTEFC = '$id' ";
                break;
            case '6':
                $sql = "SELECT * FROM tbl_dte_nd AS dte WHERE dte.idDTEFC = '$id' ";
                break;
            case '14':
                $sql = "SELECT * FROM tbl_dte_se AS dte WHERE dte.idDTEFC = '$id' ";
                break;
            
            default:
                # code...
                break;
        }

        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function validaCodigoGeneracion($codigo = null, $anio = null, $pivote = null){
        
        switch ($pivote) {
            case '1':
                $sql = "SELECT COALESCE((SELECT d.codigoGeneracion FROM tbl_dte_fc AS d WHERE d.codigoGeneracion = '$codigo' AND d.anioDTE = '$anio' LIMIT 1), 0) AS codigo;";
                break;
            case '3':
                    $sql = "SELECT COALESCE((SELECT d.codigoGeneracion FROM tbl_dte_ccf AS d WHERE d.codigoGeneracion = '$codigo' AND d.anioDTE = '$anio' LIMIT 1), 0) AS codigo;";
                break;
            case '5':
                $sql = "SELECT COALESCE((SELECT d.codigoGeneracion FROM tbl_dte_nc AS d WHERE d.codigoGeneracion = '$codigo' AND d.anioDTE = '$anio' LIMIT 1), 0) AS codigo;";
                break;
            case '6':
                $sql = "SELECT COALESCE((SELECT d.codigoGeneracion FROM tbl_dte_nd AS d WHERE d.codigoGeneracion = '$codigo' AND d.anioDTE = '$anio' LIMIT 1), 0) AS codigo;";
                break;
            case '14':
                $sql = "SELECT COALESCE((SELECT d.codigoGeneracion FROM tbl_dte_se AS d WHERE d.codigoGeneracion = '$codigo' AND d.anioDTE = '$anio' LIMIT 1), 0) AS codigo;";
                break;
            
            case '20': // Para contingencias
                $sql = "SELECT COALESCE((SELECT d.codigoDocumento FROM tbl_documentos_contingencia AS d WHERE d.codigoDocumento = '$codigo' AND YEAR(d.creadoDocumento) = '$anio' LIMIT 1), 0) AS codigo;";
                break;
            
            default:
                # code...
                break;
        }

        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerCCF($nota = null, $pivote = null){
        // Pivote: 1-Nota de credito, 2-Nota de debito
        switch ($pivote) {
            case '1':
                $sql = "SELECT * FROM tbl_dte_ccf AS ccf WHERE ccf.notaCredito = '$nota' ";
                break;
            case '2':
                $sql = "SELECT * FROM tbl_dte_ccf AS ccf WHERE ccf.notaDebito = '$nota' ";
                break;
            default:
                # code...
                break;
        }

        $datos = $this->db->query($sql);
        return $datos->row();
    }


    public function guardarDTE($data = null, $pivote = null){
        if($data != null){

            switch ($pivote) {
                case '1':
                    $sql = "INSERT INTO tbl_dte_fc(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '3':
                    $sql = "INSERT INTO tbl_dte_ccf(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '5':
                    $sql = "INSERT INTO tbl_dte_nc(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales, padreDTE)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '6':
                    $sql = "INSERT INTO tbl_dte_nd(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales, padreDTE)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '14':
                    $sql = "INSERT INTO tbl_dte_se(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '11':
                    $sql = "INSERT INTO tbl_dte_fc(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, padreDTE, jsonDTE, respuestaHacienda, datosLocales)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '33':
                    $sql = "INSERT INTO tbl_dte_ccf(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, padreDTE, jsonDTE, respuestaHacienda, datosLocales)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                case '40':
                    $sql = "INSERT INTO tbl_dte_se(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, padreDTE, jsonDTE, respuestaHacienda, datosLocales)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                // Eventos por contingencia
                case '50': // Consumidor final
                    $sql = "INSERT INTO tbl_dte_fc(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales, enContingencia, firma)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '51': // Credito fiscal
                    $sql = "INSERT INTO tbl_dte_ccf(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales, enContingencia, firma)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '52': // Sujeto excluido
                    $sql = "INSERT INTO tbl_dte_se(numeroDTE, anioDTE, detalleDTE, idHoja, codigoGeneracion, jsonDTE, respuestaHacienda, datosLocales, enContingencia, firma)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    break;
                case '60': // Datos de la contingencia
                    $sql = "INSERT INTO tbl_documentos_contingencia(codigoDocumento, motivoDocumento, textoDocumento)
                            VALUES(?, ?, ?)";
                    break;
                
                default:
                    # code...
                    break;
            }


            if($this->db->query($sql, $data)){
                $filaDte = $this->db->insert_id(); // Id hoja de cobro
                return $filaDte;
            }else{
                return 0;
            }

        }else{
            return 0;
        }
    }

    public function listaContingencias(){
        $sql = "SELECT * FROM tbl_documentos_contingencia ORDER BY idDocumento DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleContingencia($c = null){
        $sql = "SELECT * FROM tbl_documentos_contingencia WHERE idDocumento = '$c' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function actualizarContingencia($data = null){
        $sql = "UPDATE tbl_documentos_contingencia SET estadoContigencia = '0', fueLote = ? WHERE idDocumento = ? ";
        if($this->db->query($sql, $data)){
            return true;
        }else{
            return false;
        }
    }

    public function obtenerContingencia($estado = null){
        $sql = "SELECT * FROM tbl_documentos_contingencia AS dc WHERE dc.estadoContigencia = '$estado' LIMIT 1";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function facturaEnContingencia($dte = null, $tipo = null){
        switch ($tipo) {
            case '1':
                $sql = "SELECT * FROM tbl_dte_fc AS dc WHERE dc.idDTEFC = '$dte'";
                break;
            case '2':
                $sql = "SELECT * FROM tbl_dte_ccf AS dc WHERE dc.idDTEFC = '$dte'";
                break;
            case '14':
                $sql = "SELECT * FROM tbl_dte_se AS dc WHERE dc.idDTEFC = '$dte'";
                break;
            
            default:
                # code...
                break;
        }

        $datos = $this->db->query($sql);
        return $datos->row();
    }


    public function obtenerDocumentoContingencia($pivote = null){
        if($pivote != null){

            switch ($pivote) {
                case '1':
                    $sql = "SELECT fc.jsonDTE, fc.firma FROM tbl_dte_fc AS fc WHERE fc.enContingencia = 1";
                    break;
                case '3':
                    $sql = "SELECT fc.jsonDTE, fc.firma FROM tbl_dte_ccf AS fc WHERE fc.enContingencia = 1";
                    break;
                case '14':
                    $sql = "SELECT fc.jsonDTE, fc.firma FROM tbl_dte_se AS fc WHERE fc.enContingencia = 1";
                    break;
                
                default:
                    # code...
                    break;
            }

            $datos = $this->db->query($sql);
            return $datos->result();
        }
    }

    public function actualizarDTECCF($ccf = null, $nc = null, $pivote = null){
        // $pivote nos sirve para ver si es nota de debito o credito
        switch ($pivote) {
            case '1': // Nota credito
                $sql = "UPDATE tbl_dte_ccf SET notaCredito = '$nc' WHERE idDTEFC = '$ccf' "; //Asignar nota de credito a CCF
                break;
            case '2': // Nota credito
                $sql = "UPDATE tbl_dte_ccf SET notaDebito = '$nc' WHERE idDTEFC = '$ccf' "; //Asignar nota de debito a CCF
                break;
            
            default:
                # code...
                break;
        }

        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }


        
    }

    public function validarDTE($tipo, $anio){
        switch ($tipo) {
            case '1':
                $sql = "SELECT COALESCE(MAX(fc.numeroDTE), 0) AS actual FROM tbl_dte_fc AS fc WHERE fc.anioDTE = '$anio'";
                break;
            case '3':
                $sql = "SELECT COALESCE(MAX(fc.numeroDTE), 0) AS actual FROM tbl_dte_ccf AS fc WHERE fc.anioDTE = '$anio'";
                break;
            case '5':
                $sql = "SELECT COALESCE(MAX(fc.numeroDTE), 0) AS actual FROM tbl_dte_nc AS fc WHERE fc.anioDTE = '$anio'";
                break;
            case '6':
                $sql = "SELECT COALESCE(MAX(fc.numeroDTE), 0) AS actual FROM tbl_dte_nd AS fc WHERE fc.anioDTE = '$anio'";
                break;
            case '14':
                $sql = "SELECT COALESCE(MAX(fc.numeroDTE), 0) AS actual FROM tbl_dte_se AS fc WHERE fc.anioDTE = '$anio'";
                break;
            
            default:
                # code...
                break;
        }

        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function anularDTE($dteAnular = null, $dteNuevo = null, $pivote = null){
        switch ($pivote) {
            case '1': // Anular y reemplazar
                $sql = "UPDATE tbl_dte_fc AS fc SET fc.padreDTE = '$dteAnular' WHERE fc.idDTEFC = '$dteNuevo'";
                $sqlOculta = "UPDATE tbl_dte_fc AS fc SET fc.estadoDTE = '0' WHERE fc.idDTEFC = '$dteAnular'";
                $this->db->query($sqlOculta);
                break;
            case '2': // Solamente anular
                $sql = "UPDATE tbl_dte_fc AS fc SET fc.estadoDTE = '0' WHERE fc.idDTEFC = '$dteAnular'";
                break;
            
            default:
                # code...
                break;
        }
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function actualizarDTE($str = null, $hacienda = null, $dte = null, $tipo = null){
        switch ($tipo) {
            case '1': // Actualiza DTE FC
                $sql = "UPDATE tbl_dte_fc AS f SET f.enContingencia = 0, f.firma = ' ', f.jsonDTE = '$str', f.respuestaHacienda = '$hacienda' WHERE f.idDTEFC = '$dte' ";
                break;
            case '2': // Solamente anular
                $sql = "UPDATE tbl_dte_ccf AS f SET f.enContingencia = 0, f.firma = ' ', f.jsonDTE = '$str', f.respuestaHacienda = '$hacienda' WHERE f.idDTEFC = '$dte' ";
                break;
            case '14': // Solamente anular
                $sql = "UPDATE tbl_dte_se AS f SET f.enContingencia = 0, f.firma = ' ', f.jsonDTE = '$str', f.respuestaHacienda = '$hacienda' WHERE f.idDTEFC = '$dte' ";
                break;
            
            default:
                # code...
                break;
        }
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function anularDTECCF($dteAnular = null, $dteNuevo = null, $pivote = null){
        switch ($pivote) {
            case '1': // Anular y reemplazar
                $sql = "UPDATE tbl_dte_ccf AS ccf SET ccf.padreDTE = '$dteAnular' WHERE ccf.idDTEFC = '$dteNuevo'";
                $sqlOculta = "UPDATE tbl_dte_ccf AS ccf SET ccf.estadoDTE = '0' WHERE ccf.idDTEFC = '$dteAnular'";
                $this->db->query($sqlOculta);
                break;
            case '2': // Solamente anular
                $sql = "UPDATE tbl_dte_ccf AS ccf SET ccf.estadoDTE = '0' WHERE ccf.idDTEFC = '$dteAnular'";
                break;
            
            default:
                # code...
                break;
        }
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function anularDTESE($dteAnular = null, $dteNuevo = null, $pivote = null){
        switch ($pivote) {
            case '1': // Anular y reemplazar
                $sql = "UPDATE tbl_dte_se AS fc SET fc.padreDTE = '$dteAnular' WHERE fc.idDTEFC = '$dteNuevo'";
                $sqlOculta = "UPDATE tbl_dte_se AS fc SET fc.estadoDTE = '0' WHERE fc.idDTEFC = '$dteAnular'";
                $this->db->query($sqlOculta);
                break;
            case '2': // Solamente anular
                $sql = "UPDATE tbl_dte_se AS fc SET fc.estadoDTE = '0' WHERE fc.idDTEFC = '$dteAnular'";
                break;
            
            default:
                # code...
                break;
        }
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function buscarRecomendaciones($txt = null){
        $sql = "SELECT * FROM tbl_anexo_facturacion AS an WHERE an.nombrePaciente LIKE '%$txt%' LIMIT 10";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function buscarProveedor($data = null){
        $pivote = $data["pivote"];
        unset($data["pivote"]);
        $sql = "SELECT * FROM tbl_anexo_facturacion AS an WHERE an.nombrePaciente = ?";
        $datos = $this->db->query($sql, $data);
        return $datos->result();
    }

    public function correlativoHoja($id = null){
        $sql = "SELECT hc.correlativoSalidaHoja AS recibo FROM tbl_hoja_cobro AS hc WHERE hc.idHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function correlativoActual(){
        $sql = "SELECT MAX(hc.correlativoSalidaHoja) AS recibo FROM tbl_hoja_cobro AS hc";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerCorrelativoCaja($caja = null){
        if($caja != null){
            $sql = "SELECT COALESCE(MAX(cc.codigoReciboCaja), 0) AS recibo FROM tbl_control_cajas AS cc WHERE cc.idCaja = '$caja' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function insertarReciboXCaja($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_control_cajas(idUsuario, idHoja, idCaja, codigoRecibo, codigoReciboCaja, nombreCaja, fechaGenerado)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarCorrelativo($id, $c){
        $sql = "UPDATE tbl_hoja_cobro SET correlativoSalidaHoja = '$c', fechaRecibo = NOW() WHERE idHoja = '$id' ";
        if($this->db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    public function insertarMovimientoHoja($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_movimientos_hoja(idHoja, idUsuario, nombreUsuario, detalleBitacora) VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }
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

    public function guardarHonorarioPaquete($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_honorarios_paquetes(idHoja, idMedico, totalHonorarioPaquete, originalHonorarioPaquete)
                VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function agregarAControlCajeras($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_control_cajeras(idUsuario, idHoja, correlativoHoja, fechaGenerado) VALUES(?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public function pacienteGeneral(){
        $sql = "SELECT * from tbl_anexo_facturacion AS af WHERE af.idAnexo= '1'";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    // Conexion con la API
        private $tokenFile = APPPATH . 'cache/auth_token.json'; // Ruta del archivo donde se guarda el token

        public function obtenerToken() {
            // Verificar si el token ya existe y es válido
            if (file_exists($this->tokenFile)) {
                $tokenData = json_decode(file_get_contents($this->tokenFile), true);
                if ($tokenData && isset($tokenData['token'], $tokenData['fecha_expiracion'])) {

                     // Configurar la zona horaria de El Salvador
                    date_default_timezone_set('America/El_Salvador');
                    $fecha_actual = date('Y-m-d H:i:s');

                    if ($fecha_actual < $tokenData['fecha_expiracion']) {
                        // Token aún es válido
                        return $tokenData['token'];
                    }

                }else {
                    unlink($this->tokenFile); // Elimina el archivo si el token expiró
                }
            }

            // Si no hay token o ha expirado, obtener uno nuevo
            return $this->generarToken();
        }

        private function generarToken() {
            $url = "https://apitest.dtes.mh.gob.sv/seguridad/auth";
            // $url = "https://api.dtes.mh.gob.sv/seguridad/auth";

            $data = [
                "user" => "06142405161029", // Usuario proporcionado
                "pwd" => "Genesis_$2025" // Contraseña proporcionada
            ];

            $curl = new Curl();
            $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
            $curl->setHeader('User-Agent', 'hospitalOrellana/1.0 (ElSalvador; PHP; Codeigniter)');

            $curl->post($url, $data);
            $response = json_decode($curl->response, true);

            // return $response["body"]["token"];

            if ($curl->http_status_code == 200 && isset($response["body"]["token"])) {
                 // Definir la expiración: 48h en pruebas, 24h en producción
                $horas_expiracion = 12;

                // Configurar la zona horaria de El Salvador
                date_default_timezone_set('America/El_Salvador');
                $fecha_expiracion = date('Y-m-d H:i:s', strtotime("+ $horas_expiracion hours"));

                // Guardar el token
                $tokenData = [
                    'token' => $response["body"]["token"],
                    'fecha_expiracion' => $fecha_expiracion
                ];
                file_put_contents($this->tokenFile, json_encode($tokenData));

                return $response["body"]["token"];
            }

            return null; // Si falla la autenticación, retorna null
        }
    // Conexion con la API





}
?>