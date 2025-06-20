<?php
class Ventas_Model extends CI_Model {

    public function obtenerCodigo(){
        $sql = "SELECT COALESCE(MAX(v.codigoVenta),0) AS codigo from tbl_ventas AS v";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    public function busquedaInsumo($data = null){
        if($data != null){
            $sql = "SELECT COALESCE(SUM(m.idMedicamento), 0) AS pivote, m.* FROM tbl_medicamentos AS m WHERE m.codigoMedicamento = ?";
            $datos = $this->db->query($sql, $data);
            return $datos->result();
        }
    }
 
    public function guardarVenta($data = null){
        if($data != null){
            $insumos = $data["insumos"];
            unset($data["insumos"]);
            $sql = "INSERT INTO tbl_ventas(codigoVenta, fechaVenta, clienteVenta, tipoDocumento, numeroDocumento, formaPago, subtotalVenta, ivaVenta, totalVenta,
                    recibidoVenta, vueltoVenta, consulta) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $data)){
                $idVenta = $this->db->insert_id(); // Id de la factura
                for ($i=0; $i < sizeof($insumos) ; $i++) {
                    $idExamen = $insumos[$i]["idExamen"];
                    $precio = $insumos[$i]["precio"];
                    $cantidad = $insumos[$i]["cantidad"];
                   
                    $sqld = "INSERT INTO tbl_detalle_venta(idVenta, idMedicamento, precioDetalleVenta, cantidadDetalleVenta)
                            VALUES('$idVenta', '$idExamen', '$precio ', '$cantidad')";
                    $this->db->query($sqld);
                }
                return $idVenta;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function guardarVentaCF($data = null){
        if($data != null){
            $venta = $data["datosVenta"];
            $insumos = $data["insumos"];
            $sql = "INSERT INTO tbl_ventas(codigoVenta, fechaVenta, clienteVenta, tipoDocumento, numeroDocumento, formaPago, subtotalVenta, ivaVenta, totalVenta, 
                    recibidoVenta, vueltoVenta, consulta) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, $venta)){
                $idVenta = $this->db->insert_id(); // Id de la factura
                $sqld = "INSERT INTO tbl_detalle_venta(idVenta, idMedicamento, precioDetalleVenta, cantidadDetalleVenta)
                        VALUES('$idVenta', ?, ?, ?)";
                foreach($insumos as $row) {
                    $this->db->query($sqld, $row);
                }
                return $idVenta;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

 
    public function eliminarVenta($data = null){
        if($data != null){
            $venta = $data["idVenta"];
            $html = $data["html"];

            // Actualizando el detalle de la venta a anulada
            $sqlU  = "UPDATE tbl_ventas SET htmlVenta = '$html', estadoVenta = '0' WHERE idVenta = '$venta' ";
            $sqlD  = "DELETE FROM tbl_detalle_venta WHERE idVenta = '$venta' ";
            if($this->db->query($sqlU)){
                if($this->db->query($sqlD)){
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

    public function obtenerVenta($v = null){
        if($v != null){
            $sql = "SELECT * FROM tbl_ventas WHERE idVenta = '$v' ";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function eliminarDetalle($v = null){
        if($v != null){
            $sqlV = "UPDATE tbl_ventas AS v SET v.estadoVenta = '3' WHERE v.idVenta = '$v' ";
            $sqlD = "UPDATE tbl_detalle_venta AS dv SET dv.estadoDetalle = '3' WHERE dv.idVenta = '$v' ";
            if($this->db->query($sqlV)){
                $this->db->query($sqlD);
                return true;
            }else{
                return false;
            }
        }
    }

    public function totalesVenta($v = null){
        if($v != null){
            $sql = "SELECT v.*, TIME(v.creadoVenta) as hora FROM tbl_ventas AS v WHERE v.idVenta = '$v'";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
    }

    public function listaVentas(){
        $sql = "SELECT * FROM tbl_ventas AS v ORDER BY v.idVenta DESC";
        $datos = $this->db->query($sql);
        return $datos->result();
    }

    public function serieNumeroActual(){
        $sql = "SELECT st.idSerie, st.numeroActual FROM tbl_series_ticket AS st WHERE st.idSerie = (SELECT MAX(idSerie) FROM tbl_series_ticket) AND st.estadoSerie = 1";
        $datos = $this->db->query($sql);
        return $datos->row();
    }

    // Obteniendo numeros fiscales actuales
        public function obtenerNumero($pivote = null){
            $sql = "";
            if($pivote != null){
                switch ($pivote) {
                    case '1':
                        $sql = "SELECT COALESCE(MAX(t.numeroTicket),0) AS numero  FROM tbl_tickets AS t
                        WHERE t.idTicket = (SELECT MAX(idTicket) FROM tbl_tickets )";
                        break;
                    case '2':
                        $sql = "SELECT COALESCE(MAX(cf.numeroConsumidorFinal), 0) AS numero  FROM tbl_consumidor_final AS cf
                        WHERE cf.idConsumidorFinal = (SELECT MAX(idConsumidorFinal) FROM tbl_consumidor_final )";
                        break;
                    case '3':
                        $sql = "SELECT COALESCE(MAX(cf.numeroCreditoFiscal), 0) AS numero  FROM tbl_credito_fiscal AS cf
                        WHERE cf.idCreditoFiscal = (SELECT MAX(idCreditoFiscal) FROM tbl_credito_fiscal )";
                        break;
                    default:
                        $sql = "";
                        break;
                }
            }
            $datos = $this->db->query($sql);
            return $datos->result();
        }

        public function ticketActual(){
            $sql = "SELECT COALESCE(MAX(t.numeroTicket),0) AS numero  FROM tbl_tickets AS t
                    WHERE t.idTicket = (SELECT MAX(idTicket) FROM tbl_tickets )";
            $datos = $this->db->query($sql);
            return $datos->row();
        }
        
        public function consumidorActual(){
            $sql = "SELECT COALESCE(MAX(cf.numeroConsumidorFinal), 0) AS numero  FROM tbl_consumidor_final AS cf
            WHERE cf.idConsumidorFinal = (SELECT MAX(idConsumidorFinal) FROM tbl_consumidor_final )";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function creditoActual(){
            $sql = "SELECT COALESCE(MAX(cf.numeroCreditoFiscal), 0) AS numero  FROM tbl_credito_fiscal AS cf
                    WHERE cf.idCreditoFiscal = (SELECT MAX(idCreditoFiscal) FROM tbl_credito_fiscal )";
            $datos = $this->db->query($sql);
            return $datos->row();
        }

        public function actualizarNumSer($data = null){
            if($data != null){
                $sql = "UPDATE tbl_tickets AS t SET t.idSerie = ?, t.numeroSerie = ? WHERE t.idVenta = ?";
                if($this->db->query($sql, $data)){
                    return true;
                }else{
                    return false;
                }
            }
        }

        public function obtenercorrelativoTicket($venta = null){
            if($venta != null){
                $sql = "SELECT * FROM tbl_tickets AS t WHERE t.idVenta = '$venta' ";
                $datos = $this->db->query($sql);
                return $datos->row();
            }
        }
    // Obteniendo numeros fiscales actuales


}
?>