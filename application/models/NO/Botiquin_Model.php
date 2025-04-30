<?php
class Botiquin_Model extends CI_Model {

    public function obtenerClasificacionMedicamentos(){
        $sql = "SELECT * FROM tbl_clasificacion_medicamentos";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function ultimoCodigo(){
        $sql = "SELECT MAX(codigoMedicamento) as codigo FROM tbl_medicamentos";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function obtenerSeguro($id = null){
        $sql = "SELECT 
                hc.idHoja, s.nombreSeguro, s.porcentajeSeguro
                FROM tbl_hoja_cobro AS hc
                INNER JOIN tbl_seguros AS s ON(hc.seguroHoja = s.idSeguro)
                WHERE hc.idHoja = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function guardarMedicamento($data = null){
        if($data != null){
            $sql = "INSERT INTO tbl_medicamentos(codigoMedicamento, nombreMedicamento, idProveedorMedicamento, tipoMedicamento, precioCMedicamento,
                    precioVMedicamento, feriadoMedicamento, pivoteMedicamento, descuentoMedicamento, idClasificacionMedicamento, medidas)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function obtenerMedicamentos(){
        $sql = "SELECT * FROM tbl_medicamentos as m INNER JOIN tbl_clasificacion_medicamentos as cm on(m.idClasificacionMedicamento = cm.idClasificacionMedicamento)
                INNER JOIN tbl_proveedores as p on(m.idProveedorMedicamento = p.idProveedor) WHERE m.ocultarMedicamento = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function medicamentosBotiquin(){
        $sql = "SELECT 
                m.idMedicamento, cm.nombreClasificacionMedicamento, p.empresaProveedor, p.codigoProveedor, p.propietarioProveedor, m.idClasificacionMedicamento, m.usadosMedicamento,
                m.codigoMedicamento, m.tipoMedicamento, m.nombreMedicamento, m.stockMedicamento, m.idProveedorMedicamento, m.precioCMedicamento,
                m.medidas, m.precioVMedicamento, m.fechaVencimiento,
                COALESCE((SELECT ds.stockInsumo FROM tbl_detalle_stock AS ds WHERE ds.idInsumo = m.idMedicamento AND ds.idStock = 1), 0) AS cp1,
                COALESCE((SELECT ds.stockInsumo FROM tbl_detalle_stock AS ds WHERE ds.idInsumo = m.idMedicamento AND ds.idStock = 2), 0) AS cp2,
                COALESCE((SELECT ds.stockInsumo FROM tbl_detalle_stock AS ds WHERE ds.idInsumo = m.idMedicamento AND ds.idStock = 3), 0) AS cp3,
                COALESCE((SELECT ds.stockInsumo FROM tbl_detalle_stock AS ds WHERE ds.idInsumo = m.idMedicamento AND ds.idStock = 4), 0) AS cp4,
                COALESCE((SELECT ds.stockInsumo FROM tbl_detalle_stock AS ds WHERE ds.idInsumo = m.idMedicamento AND ds.idStock = 5), 0) AS emergencia
                FROM tbl_medicamentos AS m
                INNER JOIN tbl_clasificacion_medicamentos as cm on(m.idClasificacionMedicamento = cm.idClasificacionMedicamento)
                INNER JOIN tbl_proveedores as p on(m.idProveedorMedicamento = p.idProveedor)
                WHERE m.ocultarMedicamento = 0 AND m.pivoteMedicamento = 0";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerMedicamento($id = null){
        $sql = "SELECT * FROM tbl_medicamentos WHERE idMedicamento = ? ";
        $datos = $this->db->query($sql, $id);
        return $datos->result();
    }

    public function obtenerMedicamento2($id = null){
        $sql = "SELECT * FROM tbl_medicamentos WHERE idMedicamento = ? ";
        $datos = $this->db->query($sql, $id);
        return $datos->row();
    }

    public function actualizarMedicamento($data = null ){ 
        if($data != null){
            $sql = "UPDATE tbl_medicamentos SET nombreMedicamento = ?, idProveedorMedicamento = ?, tipoMedicamento = ?, precioCMedicamento = ?, precioVMedicamento = ?,
            feriadoMedicamento = ?, pivoteMedicamento = ?, descuentoMedicamento = ?, idClasificacionMedicamento = ?, medidas = ? WHERE idMedicamento = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function actualizarStock($data = null){
        if($data != null){
            $sql = "UPDATE tbl_medicamentos SET stockMedicamento = ? WHERE idMedicamento = ?";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function eliminarMedicamento($id = null){
        if ($id != null ) {
            $sql = "DELETE FROM tbl_medicamentos WHERE idMedicamento = ?";
            if($this->db->query($sql, $id)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function guardarDetalleFactura($data = null){
        if($data != null){
            $idFactura = $data["idFactura"];
            $idMedicamentos = $data["idMedicamentos"]; // Ids de medicamentos
            $cantidadMedicamentos = $data["cantidadMedicamentos"];
            $preciosMedicamentos = $data["preciosMedicamentos"]; // Precio de compra de medicamentos
            $vencimientoMedicamentos = $data["vencimientoMedicamentos"];
            $loteMedicamentos = $data["loteMedicamentos"];
            $totalCompra = $data["totalCompra"];
            
            /* $stockMedicamentos = $data["stockMedicamentos"];
            $totalUnitario = $data["totalUnitario"];
            $ivaMedicamentos = $data["ivaMedicamentos"]; */

            // Inicio de insertar factura
                $flag = 0;
                for ($i=0; $i < sizeof($idMedicamentos); $i++){ 
                    $sql = "INSERT INTO tbl_factura_medicamentos(idFactura, idMedicamento, cantidad, precio, vencimiento, lote, total)
                        VALUES('$idFactura', '$idMedicamentos[$i]', '$cantidadMedicamentos[$i]', '$preciosMedicamentos[$i]', '$vencimientoMedicamentos[$i]', '$loteMedicamentos[$i]', '$totalCompra[$i]')";
                    if($this->db->query($sql)){
                        // Aqui joder
                        $idTransaccion = $this->db->insert_id(); // Id de la factura
                        $sql3 = "INSERT INTO tbl_kardex(idMedicamento,  precioMedicamento, cantidadMedicamento, descripcionMedicamento,
                                facturaMedicamento, tipoProceso, transaccion) VALUES('$idMedicamentos[$i]', '$preciosMedicamentos[$i]',
                                '$cantidadMedicamentos[$i]', 'Entrada', '$idFactura', '1', '$idTransaccion')";
                        $this->db->query($sql3);
                        $flag++;
                    }else{
                        return false;
                    }
                }  
            // Fin de insertar factura

            // Actualizando stock del medicamentos
                if($flag == sizeof($idMedicamentos)){
                    $tp = 0;
                    for ($i=0; $i < sizeof($idMedicamentos); $i++) {
                        // $tp = $stockMedicamentos[$i] + $cantidadMedicamentos[$i];
                        $sqlStock = "SELECT stockMedicamento FROM tbl_medicamentos WHERE idMedicamento = '$idMedicamentos[$i]' ";
                        $datoStock = $this->db->query($sqlStock);
                        $ds = $datoStock->row();

                        $stockViejo = $ds->stockMedicamento; // Stock actual en db
                        $tp = $stockViejo + $cantidadMedicamentos[$i]; //Stock nuevo

                        $sql2 = "UPDATE tbl_medicamentos SET stockMedicamento = '$tp', precioCMedicamento = $preciosMedicamentos[$i] WHERE idMedicamento = '$idMedicamentos[$i]' ";
                        $this->db->query($sql2);
                        $tp = 0;
                    }
                    
                    return true;
                }else{
                    return false;
                }
            // Fin actualizar stock

        }else{
            return false;
        }
    }

    public function guardarFactura($data = null){
        if($data != null){
            $sql  = "INSERT INTO tbl_factura_compra(tipoFactura, documentoFactura, numeroFactura, idProveedor,
            fechaFactura, plazoFactura, descripcionFactura, estadoFactura, recibidoPor) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idHoja = $this->db->insert_id(); // Id de la factura
                return $idHoja;
            }else{
                return "0";
            }
        }else{
            return "0";
        }
    }

    public function obtenerFacturasCompra(){
        $sql = "SELECT c.idFactura, c.tipoFactura, c.documentoFactura, c.fechaFactura, c.plazoFactura,
                c.descripcionFactura, c.descripcionFactura, c.totalFactura, c.estadoFactura, c.numeroFactura, p.idProveedor, p.empresaProveedor
                FROM tbl_factura_compra as c INNER JOIN tbl_proveedores as p ON(c.idProveedor = p.idProveedor)";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function detalleFacturasCompra($id){
        $sql = "SELECT fc.*, p.idProveedor, p.empresaProveedor FROM tbl_factura_compra as fc INNER JOIN tbl_proveedores as p ON(fc.idProveedor = p.idProveedor) WHERE idFactura = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function medicamentosFactura($id){
        $sql = "SELECT m.nombreMedicamento, m.codigoMedicamento, m.stockMedicamento, fm.* FROM tbl_factura_medicamentos as fm INNER JOIN
                tbl_medicamentos as m ON(fm.idMedicamento = m.idMedicamento) WHERE idFactura = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function saldarCompra($data = null ){ 
        if($data != null){
            $sql = "UPDATE tbl_factura_compra SET estadoFactura = ? WHERE idFactura = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function actualizarMedicamentoFactura($data = null){ 
        if($data != null){
            $sql = "UPDATE tbl_factura_medicamentos SET cantidad = ?, cantidadUnitaria = ? WHERE idFacturaMedicamento = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function eliminarMedicamentoFactura($data = null){ 
        if($data != null){
            $sql = "DELETE FROM tbl_factura_medicamentos WHERE idFacturaMedicamento = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function actualizarDetalleFactura($data = null){ 
        if($data != null){
            $sql = "UPDATE tbl_factura_compra SET numeroFactura = ?, idProveedor = ?, fechaFactura = ?, plazoFactura = ?,
            descripcionFactura = ? WHERE idFactura = ? ";
            if($this->db->query($sql, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function kardex($i, $f){
        $sql = "SELECT m.nombreMedicamento, m.tipoMedicamento, DATE(kb.creadoKardex) AS fechaMovimiento, m.precioVMedicamento, kb.* FROM tbl_kardex_botiquin AS kb
                INNER JOIN tbl_medicamentos AS m ON(kb.idInsumo = m.idMedicamento)
                WHERE DATE(kb.creadoKardex) BETWEEN '$i' AND  '$f' AND kb.tipoKardex != 'Eliminado'";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function obtenerNombreMedicamento($id){
        $sql = "SELECT idMedicamento, nombreMedicamento FROM tbl_medicamentos WHERE idBM = '$id' ";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    // Minimos en stock
    /* public function stockMedicamento(){
        $sql = "SELECT idMedicamento, nombreMedicamento, stockMedicamento FROM tbl_medicamentos WHERE tipoMedicamento = ('Medicamentos' OR 'Materiales médicos')";
        $datos = $this->db->query($sql); 
        return $datos->result();
    } */

    public function stockMinimo(){
        $sql = "SELECT idMedicamento, nombreMedicamento, stockMedicamento, minimoMedicamento,tipoMedicamento FROM tbl_medicamentos
        WHERE stockMedicamento < minimoMedicamento";
        $datos = $this->db->query($sql); 
        return $datos->result();
    }


    // Metodos async
        public function guardarMedicamentoAsync($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_factura_medicamentos(idFactura, idMedicamento, cantidad, precio, vencimiento, total, nombreMedida, 
                                                            baseMedida, cantidadUnitaria, precioUnitario)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function guardarIVARetenido($data = null){
            if($data != null){
                $sql = "UPDATE tbl_factura_compra SET ivaRetenido = ?, ivaPercibido = ?, descuentoCompra = ? WHERE idFactura  = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    // Fin metodos async


    // Detalle de stocks
        public function listaStocks(){
            $sql ="SELECT s.idStock, s.nombreStock, s.nivelStock, s.descripcionStock FROM tbl_stocks AS s";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function medicamentosCajaParo($pivote = null){
            if($pivote != null){
                $sql ="SELECT 
                    m.idMedicamento, m.codigoMedicamento, m.nombreMedicamento, ds.idStock, ds.idInsumo, ds.stockInsumo, m.precioVMedicamento, m.ocultarMedicamento
                    FROM tbl_detalle_stock AS ds
                    INNER JOIN tbl_medicamentos AS m ON(ds.idInsumo = m.idMedicamento)
                    WHERE ds.idStock = '$pivote' AND ds.estadoInsumo = 1";
                $datos = $this->db->query($sql);
                return $datos->result();
            }
            
        }

        public function detalleStock(){
            $sql ="SELECT ds.idDetalleStock, ds.idInsumo, m.codigoMedicamento, m.nombreMedicamento, m.fechaVencimiento, ds.stockInsumo, ds.debeInsumo, ds.idStock
                   FROM tbl_detalle_stock AS ds
                   INNER JOIN tbl_medicamentos AS m ON(ds.idInsumo = m.idMedicamento)";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function guardarReintegro($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_reintegros_stocks(idStock, idInsumo, cantidadInsumo) VALUES(?, ?, ?)";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function listadoEnfermeras(){
            $DB2 = $this->load->database('enfermeria_db', TRUE);
            $sql = "SELECT * FROM tbl_enfermeras AS e WHERE e.estadoEnfermera = '1'";
            $datos = $DB2->query($sql);
            return $datos->result();
        }

        public function nombreStock($pivote = null){
            if($pivote != null){
                $sql = "SELECT * FROM tbl_stocks WHERE idStock = '$pivote' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }

    // Detalle de stocks


     // Metodos para medidas
        public function ultimoCodigoMedidas(){
            $sql = "SELECT COALESCE(MAX(codigoMedida), 0) as codigo FROM tbl_medidas_medicamentos";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function obtenerMedidas(){
            $sql = "SELECT * FROM tbl_medidas_medicamentos WHERE estadoMedida = '1'";
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function guardarMedida($data = null){
            if($data != null){
                $sql = "INSERT INTO tbl_medidas_medicamentos(codigoMedida, nombreMedida)
                        VALUES(?, ?)";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }
        }

        public function actualizarMedida($data = null ){
            if($data != null){
                $sql = "UPDATE tbl_medidas_medicamentos SET nombreMedida = ? WHERE idMedida = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }
        }

        public function eliminarMedida($id = null){
            if ($id != null ) {
                $sql = "UPDATE tbl_medidas_medicamentos SET estadoMedida = 0 WHERE idMEdida = ?";
                if($this->db->query($sql, $id)){
                    return true;
                }else{
                    return false;
                }
            }
        } 
    // Metodos para medidas

}
?> 