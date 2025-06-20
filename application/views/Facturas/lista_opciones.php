<style>
    body{
        text-align: left;
    }
    table{
        width: 100%;
    }
    table td{
    }
</style>
<div class="p-1 py-3 text-center bold">
    <!-- <img src="<?php echo base_url()?>public/img/logo.png" alt="Logo Centro medico la familia"> -->
</div>

<div class="container">
    <?php 
        switch ($factura["tipo"]) {
            case '01':
                echo '<a href="'.base_url().'Facturas/final_factura_electronica/'.$factura["dte"].'/0/" target="_blank" class="btn btn-primary btn-block py-3"><strong class="h5">Descargar factura</strong></a>';
                break;
            
            default:
                # code...
                break;
        }
       $url = unserialize(base64_decode(urldecode($url)));
       echo '<a href="'.$url.'" class="btn btn-primary btn-block py-3 mt-3" target="_blank" ><strong class="h5">Ver en hacienda</strong></a>';
    ?>
    
    
</div>
