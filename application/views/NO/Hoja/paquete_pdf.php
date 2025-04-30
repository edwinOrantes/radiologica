<style>
    body{
        background-image: url('public/img/test3_bg.jpg') ;
        background-size: cover;        
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    .container{
        width: 100%;
    }
    .cabecera{
        border-bottom: 1px solid #0b88c9;
        margin-bottom: 25px;
    }
    .izquierda{
        float: left;
        width: 35%;
    }

    .derecha{
        float: right;
        width: 65%;
        text-align: center;
        line-height: 0px;
    }
    .contenido{
        font-size: 12px;
        margin-top: 15px !important;
    }
    .contenido table{
        font-size: 12px;
        width: 100%;
    }
    .contenido table td{
        text-transform: uppercase;
    }
    .contenido p{
        text-align: left;
        word-spacing: 5px;
        line-height: 25px;
        text-transform: uppercase;
    }

    .tabla_num_recibo{
        font-size: 12px;
        margin-bottom: 25px;
        width: 100%;

    }
</style>

<?php
    function basico($numero) {
        $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
        'nueve','diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
        'veinte', 'veintiuno', 'veintidos', 'veintitres', 'veinticuatro','veinticinco', 'veintiseis','veintisiete','veintiocho','veintinueve');
        return $valor[$numero - 1];
    }

    function decenas($n) {
        $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta', 70=>'setenta',80=>'ochenta',90=>'noventa');
        if( $n <= 29) return basico($n);
        $x = $n % 10;
        if ( $x == 0 ) {
        return $decenas[$n];
        } else return $decenas[$n - $x].' y '. basico($x);
    }

    function centenas($n) {
        $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos', 400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
        700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
        if( $n >= 100) {
            if ( $n % 100 == 0 ) {
                return $cientos[$n];
            } else {
                $u = (int) substr($n,0,1);
                $d = (int) substr($n,1,2);
                return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
            }
        } else return decenas($n);
    }

    function miles($n) {
        if($n > 999) {
            if( $n == 1000) {return 'mil';}
            else {
                $l = strlen($n);
                $c = (int)substr($n,0,$l-3);
                $x = (int)substr($n,-3);
                if($c == 1) {$cadena = 'mil '.centenas($x);}
                else if($x != 0) {$cadena = centenas($c).' mil '.centenas($x);}
                else $cadena = centenas($c). ' mil';
                return $cadena;
            }
        } else return centenas($n);
    }

    function millones($n) {
        if($n == 1000000) {return 'un millón';}
        else {
            $l = strlen($n);
            $c = (int)substr($n,0,$l-6);
            $x = (int)substr($n,-6);
            if($c == 1) {
                $cadena = ' millón ';
            } else {
                $cadena = ' millones ';
            }
            return miles($c).$cadena.(($x > 0)?miles($x):'');
        }
    }

    function convertir($n) {
        switch (true) {
        case ( $n >= 1 && $n <= 29) : return basico($n); break;
        case ( $n >= 30 && $n < 100) : return decenas($n); break;
        case ( $n >= 100 && $n < 1000) : return centenas($n); break;
        case ($n >= 1000 && $n <= 999999): return miles($n); break;
        case ($n >= 1000000): return millones($n);
        }
    }
?>
<div class="container">
    <div class="cabecera">
        <div class="izquierda">
            <img src="<?php echo base_url(); ?>public/img/logo.jpg" alt="Hospital Orellana" width="225">
        </div>
        <div class="derecha">
            <h4>HOSPITAL ORELLANA, USULUTAN</h4>
            <h5 style="font-size: 12px;">Sexta calle oriente, #8, Usulután, El Salvador, PBX 2606-6673</h5>
        </div>
    </div>
    <br>
    <div class="contenido">
        <table>
            <tr>
                <td><strong>Médico:</strong> <?php echo $paquete->nombreMedico; ?></td>
                <td><strong>Código:</strong>
                <?php
                if(isset($paquete->esPaquete)){
                    switch ($paquete->esPaquete) {
                        case '0':
                            echo "H-".$paquete->codigoPaquete;
                            break;
                        case '1':
                            echo "P-".$paquete->codigoPaquete;
                            break;
                        
                        default:
                            echo $paquete->codigoPaquete;
                            break;
                        }
                }else{
                    echo $paquete->codigoPaquete;
                }
                ?>
                </td>
            </tr>
        </table>
        <p>
            RECIBI DE <?php echo $paquete->pacientePaquete; ?>,
            LA CANTIDAD DE 
            <?php
                $arregloNumero = explode(".", $paquete->cantidadPaquete);
                if($arregloNumero[1] != "00"){
                    echo convertir($arregloNumero[0])." ".$arregloNumero[1]."/100 Dolares";
                }else{
                    echo convertir($arregloNumero[0])." Dolares exactos";
                }
            ?>
            ($<?php echo number_format($paquete->cantidadPaquete, 2); ?>)
            EN CONCEPTO DE 
            <?php 
                if($paquete->conceptoPaquete == 1){
                    echo "Abono";
                }else{
                    echo "Cancelación";
                }
            ?> DE CUENTA HOSPITALARIA.
        </p>
        <p>
            <?php
                $arregloFecha = explode("-", $paquete->fechaPaquete);
                echo "Usulután, ".$arregloFecha[2]." de ".$meses[$arregloFecha[1]-1]." de ".$arregloFecha[0] 
            ?>
        </p>
    </div>

    <div class="pie">
        <table class="tabla_num_recibo" style="padding-top: 50px; font-family: Times New Roman;">
            <tr>
                <td style="text-align: center;">
                    <p>_______________________________________</p>
                    <h5><strong>RECIBIDO POR</strong></h5>
                </td>
            </tr>
        </table>
    </div>
</div>