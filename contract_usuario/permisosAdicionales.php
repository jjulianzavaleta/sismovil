<?php

/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 01:30 AM
 */

include("../plantilla1.php");
include_once("../phps/conexion.php");
include_once("../phps/dContract_permisosAdicionales.php");
include_once("../phps/dcontract_usuarios.php");

$NOMBRE_SHOW = "Permiso adicional";
$NOMBRE_SHOW_PLURAL = "Permisos adicionales";

$areas = getListaAreas();
$usuarios = getAllContractUsuarios();

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
                             style="width: 40px;">ID
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 40px;">ID
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 80px;">Usuario
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 80px;">Area
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 40px;">Permisos
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 10px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getAllPermisosAdicionales();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

                            $permisos   = "";
                            $prefijo    = "";

                            if($d['permission_crear']===1){$permisos.=$prefijo."- Solicitar contratos"."<br>";}
                            if($d['permission_aprobar']===1){$permisos.=$prefijo."- Aprobar contratos"."<br>";}
                            if($d['permission_reportes']===1){$permisos.=$prefijo."- Ver reportes"."<br>";}
                            if($d['permission_responsablearea']===1){$permisos.=$prefijo."- Responsable de área"."<br>";}

                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"usuario\">".$d['usuario']."</td>";
                            echo "<td class=\"area\">".$d['area']."</td>";
                            echo "<td class=\"permisos\"><small>".$permisos."</small></td>";
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
                    <h4 class="modal-title">Nuevo <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate">

                        <div class="control-group">
                            <label class="control-label" for="desc_nuevo">Usuario :</label>

                            <div class="controls">
                                <div class="span12">
                                    <select name="usuario_nuevo" id="usuario_nuevo">
                                        <?php
                                        foreach($usuarios as $usuario){
                                            echo "<option value='".$usuario['id']."'>".$usuario['usuario']." - ID ".$usuario['id']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="area_nuevo">Área :</label>

                            <div class="controls">
                                <div class="span12">
                                    <select name="area_nuevo" id="area_nuevo">
                                        <?php
                                        foreach($areas as $area){
                                            echo "<option value='".$area['id']."'>".$area['descripcion']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="c_permission_crear"> Permisos:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="c_permission_crear" name="permission_crear">Solicitar contratos<br>
                                    <input type="checkbox" value="1" id="c_permission_aprobar" name="permission_aprobar">Aprobar contratos<br>
                                    <input type="checkbox" value="1" id="c_permission_reportes" name="permission_reportes">Ver reportes<br>
                                    <input type="checkbox" value="1" id="c_permission_responsablearea" name="permission_responsablearea">Responsable área<br>
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

                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
    <!-- /.modal -->
    <!--END MODA LEDITAR-->


</div>
<script>
    $( '#dbasesContratos' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/bases_contract.php'>Bases Contratos ></a> <a href='index.php'>Permisos</a> > <a href='permisosAdicionales.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

    $('#nueva_empresa_btn').on('click',function(){
        $("#desc_nuevo").val("");

        $("#desc_nuevo").focus();
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

        $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
                usuario_nuevo: {
                    required: true
                },
                area_nuevo: {
                    required: true
                }
            },

            messages: {
                usuario_nuevo: {
                    required: "Usuario es obligatorio."
                },
                area_nuevo: {
                    required: "Area es requerido"
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

                var usuario = $("#usuario_nuevo").val();
                var area = $("#area_nuevo").val();

                var permission_crear     = 0;
                var permission_aprobar   = 0;
                var permission_reportes  = 0;
                var permission_responsablearea = 0;

                if($("#c_permission_crear").is(':checked')) {
                    permission_crear      = 1;
                }
                if($("#c_permission_aprobar").is(':checked')) {
                    permission_aprobar      = 1;
                }
                if($("#c_permission_reportes").is(':checked')) {
                    permission_reportes      = 1;
                }
                if($("#c_permission_responsablearea").is(':checked')) {
                    permission_responsablearea      = 1;
                }

                if(permission_crear == 0 && permission_aprobar == 0 && permission_reportes == 0 &&
                   permission_responsablearea  == 0){
                    alert("Error: Debe seleccionar al menos un tipo de permiso");
                    return false;
                }

                var parametros = {
                    "a" : usuario,
                    "b" : permission_crear,
                    "c" : permission_aprobar,
                    "d" : permission_reportes,
                    "e" : permission_responsablearea,
                    "f" : area
                };

                $.ajax({
                    data:  parametros,
                    url:   'nuevoPermiso.php',
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
        var result = confirm("¿Esta seguro de eliminar la <?=$NOMBRE_SHOW?> seleccionado?");

        if(result == true){
            var id = $(this).parent().parent().parent().children("td.hide_column").text();
            var usuario = $(this).parent().parent().parent().children("td.usuario").text();

            var ids = id.split("#");

            var parametros = {
                "a" : ids[0],
                "b":  usuario
            };

            $.ajax({
                data:  parametros,
                url:   'eliminarPermiso.php',
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

