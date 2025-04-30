<?php if($this->session->flashdata("exito")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.success('<?php echo $this->session->flashdata("exito")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>

<?php if($this->session->flashdata("error")):?>
  <script type="text/javascript">
    $(document).ready(function(){
	toastr.remove();
    toastr.options.positionClass = "toast-top-center";
	toastr.error('<?php echo $this->session->flashdata("error")?>', 'Aviso!');
    });
  </script>
<?php endif; ?>

<style>
    .ms-panel-body{
  position: relative;
  padding: 1.5rem 3rem;
}
</style>


<!-- Body Content Wrapper -->
<div class="ms-content-wrapper">
	<div class="row">
		<div class="col-md-12">

			<nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow has-gap">
                    <li class="breadcrumb-item" aria-current="page"> <a href="#"><i class="fa fa-dile"></i> Farmacia </a> </li>
                    <li class="breadcrumb-item"><a href="#">Actualizar medicamento</a></li>
                </ol>
            </nav>

			<div class="ms-panel">

                <div class="ms-panel-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Detalle de la compra</h6>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?php echo base_url(); ?>Botiquin/" class="btn btn-outline-success btn-sm"><i class="fa fa-arrow-left"></i> Volver </a>
                        </div>
                    </div>
                </div>
				
				<div class="ms-panel-body">
                    <div class="row">
                        <form class="needs-validation" method="post" action="<?php echo base_url()?>Botiquin/guardar_medicamento_actualizado" novalidate>
                            <div class="form-row">
                                <div class="col-md-8">
                                        
                                        <div class="form-row">

                                            <div class="col-md-6 mb-2">
                                                <label for=""><strong>Nombre</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="nombreMedicamento" name="nombreMedicamento" value="<?php echo $medicamento->nombreMedicamento; ?>" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese un nombre.
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="col-md-6 mb-3">
                                                <label for=""><strong>Proveedor</strong></label>
                                                <div class="input-group">
                                                <select class="form-control controlInteligente" id="idProveedorMedicamento" name="idProveedorMedicamento" required>
                                                    <option value="">.:: Seleccionar::.</option>
            
                                                    <?php
                                                        foreach ($proveedores as $proveedor) {
                                                    ?>
                                                    <option value="<?php echo $proveedor->idProveedor ?>"><?php echo $proveedor->empresaProveedor ?></option>
                                                    <?php } ?>
            
                                                </select>
                                                    <div class="invalid-tooltip">
                                                        Ingrese un proveedor.
                                                    </div>
                                                </div>
            
                                            </div>
    
                                            <div class="col-md-6 mb-3">
                                                <label for=""><strong>Tipo</strong></label>
                                                <select class="form-control" id="tipoMedicamento" name="tipoMedicamento" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="Medicamentos">Medicamentos</option>
                                                    <option value="Materiales médicos">Materiales médicos</option>
                                                    <option value="Servicios">Servicios</option>
                                                    <option value="Otros servicios">Otros servicios</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Selecciona un tipo de medicamento.
                                                </div>
                                            </div>
            
                                            <div class="col-md-6 mb-3">
                                                <label for=""><strong>Precio compra</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="precioCMedicamento" name="precioCMedicamento" value="<?php echo $medicamento->precioCMedicamento; ?>" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese el precio de compra.
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="col-md-6 mb-3">
                                                <label for=""><strong>Precio venta</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="precioVMedicamento" name="precioVMedicamento" value="<?php echo $medicamento->precioVMedicamento; ?>" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese un precio de venta.
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="col-md-6 mb-3">
                                                <label for=""><strong>Fuera de horario</strong></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="feriadoMedicamento" name="feriadoMedicamento" value="<?php echo $medicamento->feriadoMedicamento; ?>" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese un precio de venta.
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom08"><strong>Destino</strong></label>
                                                <select class="form-control" id="destinoMedicamento" name="destinoMedicamento" required>
                                                    <option value="">.:: Seleccionar ::.</option>
                                                    <option value="0">Botiquín </option>
                                                    <option value="1 ">Laboratorio Clínico  </option>
                                                    <option value="2 ">Rayos X </option>
                                                    <option value="3 ">USG</option>
            
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Selecciona el destino.
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for=""><strong>Descuento</strong></label>
                                                <div class="input-group">
                                                    <input type="number" value="<?php echo $medicamento->descuentoMedicamento ?>" class="form-control numeros" id="descuentoMedicamento" name="descuentoMedicamento" placeholder="Ejem. 10%" required>
                                                    <div class="invalid-tooltip">
                                                        Ingrese el porcentaje de descuento.
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="col-md-12 mb-3">
                                                <label for="validationCustom08"><strong>Clasificación</strong></label>
                                                <select class="form-control controlInteligente" id="idClasificacionMedicamento" name="idClasificacionMedicamento" required>
                                                    <option value="">.:: Seleccionar ::.</option>
            
                                                    <?php
                                                        foreach ($clasificaciones as $clasificacion) {
                                                    ?>
                                                        <option value="<?php echo $clasificacion->idClasificacionMedicamento ?>"><?php echo $clasificacion->nombreClasificacionMedicamento ?></option>
                                                    <?php } ?>
            
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Selecciona una clasificación.
                                                </div>
                                            </div>
            
                                        </div>
                                </div>
    
                                <div class="col-md-4">
                                    <label for=""><strong>Medidas</strong></label>
                                    <div class="input-group">
                                        <button type="button" id="agregarMedida" class="btn btn-primary btn-sm btn-block">Agregar <i class="fa fa-plus"></i> </button>
                                        <div>

                                            <table class="table table-bordered m-2" id="tablaMedidas">
                                                <?php
                                                    $medidas = json_decode($medicamento->medidas);
                                                    if(!is_null($medidas)){
                                                        foreach ($medidas as $row) {
                                                            echo '<tr>
                                                                    <td>'.$row->nombreMedida.'<input type="hidden" value="'.$row->nombreMedida.'" name=nombreMedida[] class="form-control"></td>
                                                                    <td><input type="text" value="'.$row->cantidad.'" name=cantidad[] class="form-control"></td>
                                                                    <td><input type="text" value="'.$row->precio.'" name=precio[] class="form-control"></td>
                                                                    <td> <i class="fa fa-times quitarOpcion text-danger"></i> </td>
                                                                </tr>';
                                                        }
                                                    }
                                                ?>
                                            </table>
                                                
                                            <!-- <table class="table table-bordered m-2" id="tablaMedidas"> </table> -->
                                        </div>
                                    </div>    
                                </div>

                            </div>

                            <div class="col-md-12 text-center">
                            <input type="hidden" value="<?php echo $medicamento->idMedicamento; ?>" name="idMedicamento">
                                <button class="btn btn-primary mt-4 d-inline w-20" type="submit"><i class="fa fa-save"></i> Actualizar medicamento</button>
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<input type="hidden" value="<?php echo $medicamento->tipoMedicamento; ?>" id="tipoU">
<input type="hidden" value="<?php echo $medicamento->idClasificacionMedicamento; ?>" id="clasificacionU">
<input type="hidden" value="<?php echo $medicamento->pivoteMedicamento; ?>" id="pivoteU">
<input type="hidden" value="<?php echo $medicamento->idProveedorMedicamento; ?>" id="proveedorU">

<script>

    $(document).on('keydown', '#codigoMedicamento', function(e) {
        if (e.key === 'Enter'){
            e.preventDefault()
        }
    });

    

    $(document).ready(function() {
        $("#tipoMedicamento").val($("#tipoU").val());
        $("#destinoMedicamento").val($("#pivoteU").val());
        $("#idClasificacionMedicamento").val($("#clasificacionU").val());
        $("#idProveedorMedicamento").val($("#proveedorU").val());

        $('.controlInteligente').select2({
            theme: "bootstrap4"
        });

    });

    $(document).on("click", "#agregarMedida", function(e) {
        e.preventDefault();
        $.ajax({
            url: "../../obtener_medidas",
            type: "POST",
            data: null,
            success:function(respuesta){
                var registro = eval(respuesta);
                if ((registro).length > 0){
                    var html = '<tr><td> ';
                    html += '<select name="nombreMedida[]" class="form-control nombreMedida">';
                    for (let i = 0; i < registro.length; i++) {
                        html += '<option value="'+registro[i]["nombreMedida"]+'">'+registro[i]["nombreMedida"]+'</option>';
                    }
                    html += '</select>';
                    html += ' </td>';
                    html +='<td><input type="number" name=cantidad[] class="form-control" placeholder="Unidades"></td>'
                    html +='<td><input type="text" name=precio[] class="form-control" placeholder="Precio"> </td>'
                    html +='<td> <i class="fa fa-times quitarOpcion text-danger"></i> </td>'
                    html +='</tr>';

                    $("#tablaMedidas").append(html);



                }
            }
        });
    });

    $(document).on("change", "#precioVMedicamento", function(e) {
        $("#feriadoMedicamento").val($(this).val());
    });

    $(document).on("click", ".quitarOpcion", function() {
        $(this).closest('tr').remove();
    });

    
    
</script>
