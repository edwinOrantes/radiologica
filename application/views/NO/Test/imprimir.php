<link href="<?= base_url() ?>public/css/factura_consumidor_final.css" rel="stylesheet" />
<button onclick="imprimirTabla()">Imprimir</button>

<div id="factura">
    <div id="cabecera"></div>
    <div id="fecha" class="text" >
      <div id="dia"></div>
      <div id="diaContainer" style="font-weight: 600;"><?php echo date('d');?></div>
      <div id="mes"></div>
      <div id="mesContainer" style="font-weight: 600;"><?php echo date('m');?></div>
      <div id="año"></div>
      <div id="añoContainer" style="font-weight: 600;"><?php echo date('Y');?></div>
    </div>
    <div id="paciente" class="text" style="font-weight: 600; margin-left: 1.8cm; margin-top: 0.2cm">Edwin Alexander</div>
    <div id="apellido" class="text" style="font-weight: 600; margin-left: 1.8cm;">Cortez Orantes</div>
    <div id="documento" class="text" style="font-weight: 600; margin-left: 1.8cm;">00000000-0</div>
    <div id="detalle">
      <table id="tblDetalle" >
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text" style="font-size: 10px; font-weight: bold; width: 1cm; text-align: center;">1</td>
              <td class="text" style="font-size: 10px; font-weight: bold; width: 6cm; text-align: center;">Medicamentos</td>
              <td class="text" style="font-size: 10px; font-weight: bold; width: 1.1cm; text-align: center;">0.50</td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="font-size: 10px; font-weight: bold; width: 1.8cm; text-align: center;">0.50</td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
            <tr>
              <td class="text" style="width: 1cm; text-align: center;"></td>
              <td class="text" style="width: 6cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.1cm; text-align: center;"></td>
              <td class="text" style="width: 1.8cm; text-align: center;"></td>
            </tr>
          </tbody>
      </table>
    </div>
    <div id="footer">
        <div id="footerLeft">
          <div class="text" style="height: 1.1cm;"></div>
          <div class="text" style="height: 1.60cm;"></div>
          <div class="text" style="height: 1.40cm"></div>
        </div>

        <div id="footerRight">
          <table id="tblFooter">
            <thead></thead>
              <tbody>
                <tr>
                  <td class="text" style="width: 2cm; text-align: center;"></td>
                  <td class="text" style="font-size: 10px; font-weight: bold; width: 1.8cm; text-align: center;">0.50</td>
                </tr>
                <tr>
                  <td class="text" style="width: 2cm; text-align: center;"></td>
                  <td class="text" style="width: 1.8cm; text-align: center;"></td>
                </tr>
                <tr>
                  <td class="text" style="width: 2cm; text-align: center;"></td>
                  <td class="text" style="font-size: 10px; font-weight: bold; width: 1.8cm; text-align: center;">0.50</td>
                </tr>
                <tr>
                  <td class="text" style="width: 2cm; text-align: center;"></td>
                  <td class="text" style="width: 1.8cm; text-align: center;"></td>
                </tr>
                <tr>
                  <td class="text" style="width: 2cm; text-align: center;"></td>
                  <td class="text" style="width: 1.8cm; text-align: center;"></td>
                </tr>
                <tr>
                  <td class="text" style="width: 2cm; text-align: center;"></td>
                  <td class="text" style="font-size: 10px; font-weight: bold; width: 1.8cm; text-align: center;">0.50</td>
                </tr>
                
              </tbody>
          </table>
        </div>
    </div>
</div>


<script>
    function imprimirTabla()
    {
      var elemento=document.getElementById('factura');
      var pantalla=window.open(' ','popimpr');

      pantalla.document.write('<html><head><title>' + document.title + '</title>');
     pantalla.document.write('<link href="<?= base_url() ?>public/css/factura_consumidor_final.css" rel="stylesheet" /></head><body>');
      pantalla.document.write(elemento.innerHTML);
      pantalla.document.write('</body></html>');
      pantalla.document.close();
      pantalla.focus();
      pantalla.onload = function() {
        pantalla.print();
        pantalla.close();
      };
    //    $(".mos").hide();
    //    $(".ocultarImprimir").show();
    }
</script>

<?php
function basico($numero) {
  $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
  'nueve','diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
  'veinte', 'veintiuno', 'veintidos', 'veintitres', 'veinticuatro','veinticinco', 'veintiseis','veintisiete','veintiocho','veintinueve');
  return $valor[$numero-1];
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
  $arregloNumero = explode(".", $n);
  if(isset($arregloNumero[1])){
    unset($arregloNumero[1]);
    $n = implode($arregloNumero);
  }else{
    $n = $n;
  }

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
  case ( $n >= 1 && $n < 30) : return basico($n); break;
  case ( $n >= 30 && $n < 100) : return decenas($n); break;
  case ( $n >= 100 && $n < 1000) : return centenas($n); break;
  case ($n >= 1000 && $n <= 999999): return miles($n); break;
  case ($n >= 1000000): return millones($n);
  }
}

$numero = 9.15;
$arregloNumero = explode(".", $numero);
if(isset($arregloNumero[1])){
  echo strtoupper(convertir($numero)." ".$arregloNumero[1]."/100 Dolares");
}else{
  echo strtoupper(convertir($numero)." Dolares");
}
?>