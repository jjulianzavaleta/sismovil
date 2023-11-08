<?php

/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 01:30 AM
 */

include("../plantilla1.php");
include("../phps/dpaviferia_productos.php");
include("../phps/dpaviferia_grupo.php");

$lstGupos = getAllGruposPaviferia();

$NOMBRE_SHOW = "Producto";
$NOMBRE_SHOW_PLURAL = "Productos";

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
        <a data-toggle="modal" id="nueva_empresa_btn" href="#nueva_actividad" class="btn btn-app btn-primary btn-mini">
            <i class="icon-plus-sign"></i>
            Nuevo
        </a>

        <div class="row-fluid">
            <h3 class="header smaller lighter blue"><?=$NOMBRE_SHOW_PLURAL?></h3>

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
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 60px;">ID
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 200px;">Nombre
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Grupo
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Unidad Medida
                        </th>
                       <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Peso (Kg)
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Precios
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getAllProductosPaviferia();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

                            $preciosDesc = "";
                            if($d['cantPrecios'] == 0){
                                $preciosDesc = "<a href='precios.php?id=".$d['id']."'><font color='red'>[0] Registrar </font></a>";
                            }else{
                                $preciosDesc = "<a href='precios.php?id=".$d['id']."'><font color='green'>[".$d['cantPrecios']."] Agregar </font></a>";

                            }

                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"desc\">".$d['descripcion']."</td>";
                            echo "<td class=\"grupo\">".$d['grupodesc']."</td>";
                            echo "<td class=\"unidad\">".$d['unidadmedida_desc']."</td>";
                            echo "<td class=\"peso\">".$d['peso_unidad']."</td>";
                            echo "<td class=\"peso\">".$preciosDesc."</td>";
                            echo $EDITAR_ELIMINAR_HTML_CODE;
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
                    <h4 class="modal-title">Nuevo <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate">

                        <div class="control-group">
                            <label class="control-label" for="id_nuevo">ID :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="id_nuevo" id="id_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="desc_nuevo">Nombre :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="desc_nuevo" id="desc_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="unidad_nuevo"> Unidad Medida:</label>
                            <div class="controls">
                                <div class="span12">
                                    <select class="span6" id="unidad_nuevo" name="unidad_nuevo"">
                                        <option value="2">Kg</option>
                                        <option value="1">Unidad</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="peso_nuevo">Peso (Kg) :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="peso_nuevo" id="peso_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="grupo_nuevo"> Grupo:</label>
                            <div class="controls">
                                <div class="span12">
                                    <select class="span6" id="grupo_nuevo" name="zona_nuevo"">
                                    <?php
                                    foreach( $lstGupos as $grupo){
                                        echo "<option value='".$grupo['id']."'>".$grupo['descripcion']."</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
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
    <div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Editar <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_editar" method="get" novalidate="novalidate">



                        <div class="hidden">
                            <label class="control-label" for="id_editar">ID :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="id_editar" id="id_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="desc_editar">Nombre :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="desc_editar" id="desc_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="peso_editar">Peso (Kg) :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="peso_editar" id="peso_editar" class="span6 uppercase" autocomplete="off">
                                </div>
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
<!-- /.modal -->
<!--END MODA LEDITAR-->


</div>
<script>
    $( '#bpaviferiaPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/bases_paviferia.php'>Datos Bases Paviferia> <a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a></a>");

    $('#nueva_empresa_btn').on('click',function(){
        $("#desc_nuevo").val("");
        $("#peso_nuevo").val("");

        $.post("getNewId.php", {}, function(data){
            $("#id_nuevo").val(data);
        });

        setTimeout(function (){
            $("#desc_nuevo").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);
    });

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
                null,
                null,
                { "bSortable": false }
            ] ,
            "aaSorting": [
                [ 1, "desc" ]
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

        $('#validation-form_editar').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
                desc_editar: {
                    required: true
                },
                peso_editar: {
                    required: true,
                    number: true
                }
            },

            messages: {
                desc_editar: {
                    required: "El nombre es obligatorio."
                },
                peso_editar: {
                    required: "Peso base es requerido",
                    number: "Debe ser un numero"
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

                var name      = $("#desc_editar").val();
                var id        =   $("#id_editar").val();
                var peso      = $("#peso_editar").val();

                var parametros = {
                    "a" : name,
                    "b" : id,

                    "d" : peso,

                };

                $.ajax({
                    data:  parametros,
                    url:   'editar.php',
                    type:  'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#editar').modal('hide');
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
            },
            invalidHandler: function (form) {
            }
        });
        $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
                desc_nuevo: {
                    required: true
                },
                id_nuevo: {
                    required: true,
                    digits: true
                },
                peso_nuevo: {
                    required: true,
                    number: true
                }
            },

            messages: {
                desc_nuevo: {
                    required: "El nombre es obligatorio."
                },
                id_nuevo: {
                    required: "Id es requerido",
                    digits:"Id es un numero entero"
                },
                peso_nuevo: {
                    required: "Peso es requerido",
                    number: "Peso debe ser un numero"
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

                var nameNuevo = $("#desc_nuevo").val();
                var id        = $("#id_nuevo").val();
                var peso      = $("#peso_nuevo").val();
                var grupo     = $("#grupo_nuevo").val();
                var unidad     = $("#unidad_nuevo").val();

                var parametros = {
                    "a" : nameNuevo,
                    "b" : id,

                    "d" : peso,
                    "e" : grupo,
                    "f" : unidad,

                };

                $.ajax({
                    data:  parametros,
                    url:   'nuevo.php',
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

    $("a","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece

        var id     = $(this).parent().parent().parent().children("td.hide_column").text();
        var desc   = $(this).parent().parent().parent().children("td.desc").text();
        var peso   = $(this).parent().parent().parent().children("td.peso").text().replace("[","").replace("]","").replace("Registrar","").trim();
        var unidad = $(this).parent().parent().parent().children("td.unidad").text();

        $("#id_editar").val(id);
        $("#desc_editar").val(desc);
        $("#peso_editar").val(peso);

        setTimeout(function (){
            $("#desc_editar").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);

    });

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
                url:   'eliminar.php',
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
