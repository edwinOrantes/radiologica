<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
 -->

 <style>
     .cell-focus {
        background-color: #0b99d0;
        color: white;
    }
    td, th{
        white-space: nowrap;
    }
 </style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <table id="tbl_medicamentos" class="table table-striped table-sm table-responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Desarrollo</th>
                        <th>Enero</th>
                        <th>Febrero</th>
                        <th>Marzo</th>
                        <th>Abril</th>
                        <th>Mayo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for ($i=0; $i < 5; $i++) { 
                    ?>
                    <tr>
                        <td id="start">1</td>
                        <td>Ejemplo de desarrollo 1</td>
                        <td> $46,762.07 </td>
                        <td> $37,697.94 </td>
                        <td> $97,052.56 </td>
                        <td> $58,454.34 </td>
                        <td> $11,393.24 </td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let start;
    let isEditing = false;
    let kc = 0; //Key Code
    let tableDT;

    $(function() {
        start = $('#start');
        start.addClass('cell-focus');

        document.onkeydown = checkKey;
        document.onclick = deleteInput;

        //Bloque para mover las celdas activas
        function checkKey(e) {
            if (isEditing) return;
            e = e || window.event;
            kc = e.keyCode;
            if (kc === 38) {
                // up arrow
                doTheNeedful($(start.parent().prev().find('td')[start.index()]));
            } else if (kc === 40) {
                // down arrow
                doTheNeedful($(start.parent().next().find('td')[start.index()]));
            } else if (kc === 37) {
                // left arrow
                doTheNeedful(start.prev());
            } else if (kc === 39) {
                // right arrow
                doTheNeedful(start.next());
            }else if (kc === 13) {
                // Enter
                replacedByAnInputText(e);
            }else if (kc === 9) {
                //Tab
                if (e.shiftKey){
                    if (start.prev().length === 0){
                        doTheNeedful($(start.parent().prev().children().last()));
                    }else{
                        doTheNeedful(start.prev());
                    }
                } else{
                    if (start.next().length === 0){
                        doTheNeedful($(start.parent().next().children()[0]));
                    }else{
                        doTheNeedful(start.next());
                    }
                }
                e.stopPropagation();
                e.preventDefault();
            }
        }

        function deleteInput(e) {
            console.log(start)
        }

        $("#tbl_medicamentos tr").on('click', function(e) {
            if ($(e.target).closest('td')) {
                start.removeClass('cell-focus');
                start = $(e.target);
                start.addClass('cell-focus');
                e.stopPropagation();
            }
        })
    } );

    function doTheNeedful(sibling) {
        if (sibling.length === 1) {
            start.removeClass('cell-focus');
            sibling.addClass('cell-focus');
            start = sibling;
        }
    }
</script>