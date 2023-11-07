<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 17/09/2015
 * Time: 01:12 AM
 */

if(!isset($_GET['id'])){
    die("Id Producto erroneo");
}

$idProducto =  $_GET['id'];

include("../plantilla1.php");
include("../phps/dpaviferia_productos.php");

$NOMBRE_SHOW = "Precio";
$NOMBRE_SHOW_PLURAL = "Precios";

$producto = getProductoPaviferiaById($idProducto);

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
        <a data-toggle="modal" id="nueva_empresa_btn" href="#nueva_actividad" class="btn btn-app btn-primary btn-mini">
            <i class="icon-plus-sign"></i>
            Nuevo
        </a>

        <div class="row-fluid">
            <h3 class="header smaller lighter blue"><?=$NOMBRE_SHOW_PLURAL?> para el Producto ID: <?=$producto['id']?> - <?=$producto['descripcion']?></h3>

            <div class="table-header">
                Resultados de "<?=$NOMBRE_SHOW_PLURAL?> registrados"
            </div>

            <div id="table_report_wrapper" class="dataTables_wrapper" role="grid">
                <table id="table_report" class="table table-striped table-bordered table-hover dataTable"
                       aria-describedby="table_report_info">
                    <thead>
                    <tr role="row">
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 143px;">ID
                        </th>
                       <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Descripcion
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">F. Inicio
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">F. Fin
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Precio
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getPreciosProductosPaviferiaById($idProducto);

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."</td>";
                            echo "<td class=\"desc\">".$d['descripcion']."</td>";
                            echo "<td class=\"desc\">".date_format($d['fechainicio'], 'Y-m-d')."</td>";
                            echo "<td class=\"desc\">".date_format($d['fechafin'], 'Y-m-d')."</td>";
                            echo "<td class=\"desc\">".$d['precio_base']."</td>";
                            echo $ELIMINAR_HTML_CODE;
                            echo "</tr>";

                        }
                    }

                    ?>

                    </tbody>
                </table>
            </div>
            <!--PAGE CONTENT ENDS HERE-->

            <!--/row-->
        </div>
        <!--/#page-content-->

    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->


<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>

<!--MODAL- NUEVO-->
<!-- Modal -->
<div class="row-fluid">
    <div class="modal fade" id="nueva_actividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Nueva <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate">


                        <div class="control-group">
                            <label class="control-label" for="desc_nuevo">Descripcion :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="desc_nuevo" id="desc_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="precio_nuevo">Precio Base :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="precio_nuevo" class="span6 uppercase" id="precio_nuevo" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="fecha_inicio">Fecha Inicio :</label>

                            <div class="controls">
                                <input class="span6 date-picker" name="fecha_inicio" id="fecha_inicio" type="text"
                                       data-date-format="yyyy/mm/dd" autocomplete="off"
                                       value=""/>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="fecha_fin">Fecha Fin :</label>

                            <div class="controls">
                                <input class="span6 date-picker" name="fecha_fin" id="fecha_fin" type="text"
                                       data-date-format="yyyy/mm/dd" autocomplete="off"
                                       value=""/>
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <input type="submit" class="btn btn-primary" value="Guardar">
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
    <!--END MODAL- NUEVO-->

    <!--MODAL EDITAR-->
    <!-- Modal -->

    <!-- /.modal -->
    <!--END MODA LEDITAR-->


</div>
<script>
    $( '#bpaviferiaPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/bases_paviferia.php'>Datos Bases Paviferia></a><a href='index.php'>Productos > </a> <a href='precios.php?id=<?=$idProducto?>'><?=$NOMBRE_SHOW_PLURAL?></a>");

</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {
        var oTable1 = $('#table_report').dataTable({
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column"},
                null,
                null,
                null,
                null,
                { "bSortable": false }
            ] ,
            "aaSorting": [
                [ 2, "desc" ]
            ]
        });

        $('table th input:checkbox').on('click', function () {
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });

        });

        $('[data-rel=tooltip]').tooltip();

        $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
                desc_nuevo: {
                    required: true
                },
                precio_nuevo: {
                    required: true,
                    number: true
                },
                fecha_inicio: {
                    required: true
                },
                fecha_fin: {
                    required: true
                }
            },

            messages: {
                desc_nuevo: {
                    required: "Descripcion es obligatorio."
                },
                precio_nuevo: {
                    required: "Precio Base es obligatorio",
                    number: "Precio Base debe ser un numero"
                },
                fecha_inicio: {
                    required: "Fecha Inicio es obligatorio"
                },
                fecha_fin: {
                    required: "Fecha Fin es obligatorio"
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-error', $('.login-form')).show();
            },

            highlight: function (e) {
                $(e).closest('.control-group').removeClass('info').addClass('error');
            },

            success: function (e) {
                $(e).closest('.control-group').removeClass('error').addClass('info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if (element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('.controls');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl').eq(0));
                }
                else if (element.is('.chzn-select')) {
                    error.insertAfter(element.nextAll('[class*="chzn-container"]').eq(0));
                }
                else error.insertAfter(element);
            },

            submitHandler: function (form) {

                var nameNuevo    = $("#desc_nuevo").val();
                var precio_nuevo = $("#precio_nuevo").val();
                var fecha_inicio = $("#fecha_inicio").val();
                var fecha_fin    = $("#fecha_fin").val();

                var parametros = {
                    "a" : nameNuevo,
                    "b" : precio_nuevo,
                    "c" : fecha_inicio,
                    "d" : fecha_fin,
                    "e" : <?=$idProducto?>
                };

                $.ajax({
                    data:  parametros,
                    url:   'nuevoPrecio.php',
                    type:  'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#nueva_actividad').modal('hide');
                        openModal();
                    },
                    success: function(respuesta){

                        respuesta = $.parseJSON( respuesta );

                        if(respuesta.estado == "1"){

                            closeModal();
                            $().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
                            //oTable1.fnAddData([respuesta.id,respuesta.id,respuesta.descripcion,'']);
                            setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

                        }else{

                            closeModal();
                            $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                            alert(respuesta.error);
                            location.reload();
                        }
                    },
                    error: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    },
                    failure: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    }
                });


            },
            invalidHandler: function (form) {
            }
        });
        $('.date-picker').datepicker();

    })


    $("button","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece
        //
        var result = confirm("¿Esta seguro de eliminar la <?=$NOMBRE_SHOW?> seleccionada?");

        if(result == true){
            var id = $(this).parent().parent().parent().children("td.hide_column").text();

            var parametros = {
                "a" : id
            };

            $.ajax({
                data:  parametros,
                url:   'eliminarPrecio.php',
                type:  'post',
                dataType: "html",
                beforeSend: function (repuesta) {
                    // lo que se hace mientras llega
                    openModal();
                },
                success: function(respuesta){
                    respuesta = $.parseJSON( respuesta );

                    if(respuesta.estado == "1"){

                        closeModal();
                        $().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
                        setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

                    }else{

                        closeModal();
                        $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                        alert(respuesta.error);
                        location.reload();
                    }
                },
                error: function(respuesta){
                    closeModal();alert("Error al conectar con el servidor");
                },
                failure: function(respuesta){
                    closeModal();alert("Error al conectar con el servidor");
                }
            });

        }

    });
</script>

</body>
</html>
