<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 20/08/2015
 * Time: 08:38 PM
 */

include("../plantilla1.php");
include("../phps/dpaviferia_descuento.php");
include("../phps/dpaviferia_formapago.php");
include("../phps/dpaviferia_grupo.php");

$NOMBRE_SHOW = "Descuento";
$NOMBRE_SHOW_PLURAL = "Descuentos";

$lstFormaPago = getAllFormaPago();
$lstGupos = getAllGruposPaviferia();

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
                            style="width: 143px;">Tipo
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Grupo
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 200px;">Descripcion
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Descuento (%)
                        </th>

                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getAllDescuentosPaviferia();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"desc\">".$d['tipodescuento_desc']."</td>";
                            echo "<td class=\"grupo\">".$d['grupodesc']."</td>";
                            echo "<td class=\"condicion\">".$d['condicion']."</td>";
                            echo "<td class=\"descuento\">".$d['descuento']."</td>";
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
                    <h4 class="modal-title">Nueva <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate">

                        <div class="control-group">
                            <label class="control-label" for="tipo_nuevo"> Tipo:</label>
                            <div class="controls">
                                <div class="span12">
                                    <select class="span6" id="tipo_nuevo" name="tipo_nuevo" onchange="showhideTipo()">
                                        <option value="1">FORMA DE PAGO</option>
                                        <option value="2">VOLUMEN</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group" id="formapago_desc">
                            <label class="control-label" for="desc_nuevo"> Forma de Pago:</label>
                            <div class="controls">
                                <div class="span12">
                                    <select class="span6" id="desc_nuevo" name="desc_nuevo"">
                                    <?php
                                    foreach($lstFormaPago as $formadepago){
                                        echo "<option value='".$formadepago['id']."'>".$formadepago['descripcion']."</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group" id="grupo" style="display: none">
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

                        <div class="control-group" id="kgminimo" style="display: none">
                            <label class="control-label" for="kgminimo_nuevo">Desde :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="kgminimo_nuevo" id="kgminimo_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group" id="kgmaximo" style="display: none">
                            <label class="control-label" for="kgmaximo_nuevo">Hasta :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="kgmaximo_nuevo" id="kgmaximo_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="descuento_nuevo">Descuento (%) :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="descuento_nuevo" id="descuento_nuevo" class="span6 uppercase" autocomplete="off">
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



                        <div class="control-group">
                            <label class="control-label" for="id_editar">ID :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="id_editar" id="id_editar" class="span6" autocomplete="off" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="desc_editar">Descuento (%) :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="desc_editar" id="desc_editar" class="span6 uppercase" autocomplete="off">
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
        $("#kgminimo_nuevo").val("");
        $("#kgmaximo_nuevo").val("");

        setTimeout(function (){
            $("#descuento_nuevo").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);
    });

    function showhideTipo(){
        var tipo = $('#tipo_nuevo option:selected').val();

        if(tipo == 1){//forma de pago
            $("#kgminimo").css("display", "none");
            $("#kgmaximo").css("display", "none");
            $("#grupo").css("display", "none");
            $("#formapago_desc").css("display", "block");
            $("#kgminimo_nuevo").val("-2");
            $("#kgmaximo_nuevo").val("-1");
        }else{
            if(tipo == 2){//por volumen
                $("#kgminimo").css("display", "block");
                $("#kgmaximo").css("display", "block");
                $("#grupo").css("display", "block");
                $("#formapago_desc").css("display", "none");
                $("#desc_nuevo").val("#");
                $("#kgminimo_nuevo").val("");
                $("#kgmaximo_nuevo").val("");
            }

        }
    }

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
                    required: true,
                    number: true
                }
            },

            messages: {
                desc_editar: {
                    required: "Descuento es requerido.",
                    number: "Descuento debe ser un numero"
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

                var name = $("#desc_editar").val();
                var id =   $("#id_editar").val();

                var parametros = {
                    "a" : name,
                    "b" :id
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
                descuento_nuevo: {
                    required: true,
                    number: true
                },
                id_nuevo: {
                    required: true,
                    digits: true
                },
                kgminimo_nuevo: {
                    required: true,
                    number  : true
                },
                kgmaximo_nuevo: {
                    required: true,
                    number  : true
                },
                desc_nuevo: {
                    required: true
                },
            },

            messages: {
                descuento_nuevo: {
                    required: "Descuento es requerido.",
                    number: "Descuento debe ser un numero"
                },
                id_nuevo: {
                    required: "Id es requerido",
                    digits:"Id es un numero entero"
                },
                kgminimo_nuevo: {
                    required: "Es requerido",
                    number  : "Debe ser un numero"
                },
                kgmaximo_nuevo: {
                    required: "Es requerido",
                    number  : "Debe ser un numero"
                },
                desc_nuevo: {
                    required: "Forma de pago es requerida."
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

                var formapago = $("#desc_nuevo").val();
                var minimo    = $("#kgminimo_nuevo").val();
                var maximo    = $("#kgmaximo_nuevo").val();
                var descuento = $("#descuento_nuevo").val();
                var tipo      = $('#tipo_nuevo option:selected').val();
                var grupo     = $("#grupo_nuevo option:selected").val();

                if(tipo == 1){//si es modo de pago
                    grupo = 0;
                }

                if(tipo == 1){//forma de pago
                    minimo = 0;
                    maximo = 0;
                }
                if(tipo == 2){//x volumen
                    formapago = 0;
                    if(parseInt(minimo) >= parseInt(maximo)){
                        alert("Error: Valores DESDE y HASTA son incorrectos");
                        return false;
                    }
                }

                var parametros = {
                    "b" : tipo,
                    "c" : descuento,
                    "d" : minimo,
                    "e" : maximo,
                    "f" : formapago,
                    "g" : grupo
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

        var id   = $(this).parent().parent().parent().children("td.hide_column").text();
        var desc = $(this).parent().parent().parent().children("td.descuento").text();

        $("#id_editar").val(id);
        $("#desc_editar").val(desc);

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
