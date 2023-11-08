<?php

/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 01:30 AM
 */

include("../plantilla1.php");
include("../phps/dpaviferia_usuario.php");
include("../phps/dpaviferia_zona.php");

$NOMBRE_SHOW = "Usuario";
$NOMBRE_SHOW_PLURAL = "Usuarios";

$lstZonas = getAllZonas();

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
                             style="width: 80px;">ID
                        </th>
                       <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Username
                        </th><th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Estado
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getAllUsuarios();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

							$estado = "";
                            if($d['activo']===1){$estado = "Activo";}else{$estado="Inactivo";}

                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"usuario\">".$d['usuario']."</td>";
                            echo "<td class=\"estado\">".$estado."</td>";
                            echo '<td class="td-actions">'.
                                 '<div class="btn-group">'.
                                 '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#" onclick="editar('.$d['id'].')">'.
                                 '<i  class="icon-edit bigger-120"></i>'.
                                 '</a>'.
                                 '<a  alt="Contraseña" title="Contraseña" data-toggle="modal" class="btn btn-mini btn-warning" href="#" onclick="password_('.$d['id'].',\''.$d['usuario'].'\')">'.
                                 '<i  class="icon-lock bigger-120"></i>'.
                                 '</a>'.
                                 '<button alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger">'.
                                 '<i  class="icon-trash bigger-120"></i>'.
                                 '</button>'.
                                 '</div>'.
                                 '</td>';
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
                            <label class="control-label" for="id_nuevo">ID :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="id_nuevo" id="id_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="nombres_nuevo">Nombres :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="nombres_nuevo" id="nombres_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="apellidos_nuevo">Apellidos :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="apellidos_nuevo" id="apellidos_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="zona_nuevo"> Zona (Paviferia y Toma de Data):</label>
                            <div class="controls">
                                <div class="span12">
                                    <select class="span6" id="zona_nuevo" name="zona_nuevo"">
                                    <?php
                                    foreach($lstZonas as $zona){
                                        echo "<option value='".$zona['id']."'>".$zona['descripcion']."</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="correo_nuevo">Correo :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="correo_nuevo" id="correo_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="telefonos_nuevo">Telefono :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="telefonos_nuevo" id="telefonos_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="tipo_nuevo"> Permisos:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="c_permission_data" name="permission_data">Toma Data<br>
                                    <input type="checkbox" value="1" id="c_permission_pedidos" name="permission_pedidos">Toma Pedidos<br>
                                    <input type="checkbox" value="1" id="c_permission_paviferia" name="permission_paviferia">Paviferia<br>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="usuario_nuevo">Nombre de Usuario :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="usuario_nuevo" id="usuario_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password_nuevo">Contraseña :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="password" name="password_nuevo" id="password_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password2_nuevo">Repita Contraseña :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="password" name="password2_nuevo" id="password2_nuevo" class="span6" autocomplete="off">
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

</div>
<script>
    $( '#dbasesPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/datos_bases.php'>Datos Bases > <a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a></a>");

    $('#nueva_empresa_btn').on('click',function(){
        $("#nombres_nuevo").val("");
        $("#apellidos_nuevo").val("");
        $("#usuario_nuevo").val("");
        $("#password_nuevo").val("");
        $("#c_permission_data").attr('checked', false);
        $("#c_permission_pedidos").attr('checked', false);
        $("#c_permission_paviferia").attr('checked', false);

        $.post("getNewId.php", {}, function(data){
            $("#id_nuevo").val(data);
        });

        setTimeout(function (){
            $("#nombres_nuevo").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);
    });

    function editar(id){
        window.location.href = 'editarUsuario.php?id='+id;
    }

    function password_(iduser,username){
        var confirmation = confirm("¿Seguro que desea cambiar la contraseña del usuario "+username+"?")

        if(confirmation){
            var pass = prompt("Ingrese nueva contraseña para usuario: "+username, "");

            if(pass != ""){
                cambiarPasswordEnServidor(iduser,pass);
            }
        }
    }

    function cambiarPasswordEnServidor(iduser,pass){
        $.post("cambiarPassword.php", {iduser:iduser,password:pass}, function(data){
            alert(data);
        });
    }

    function zonasventa(id){
        window.location.href = 'zonasventaTomaPedidos.php?id='+id;
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

                id_nuevo: {
                    required: true,
                    digits: true
                },nombres_nuevo: {
                    required: true
                },apellidos_nuevo: {
                    required: true
                },usuario_nuevo: {
                    required: true
                },password_nuevo: {
                    required: true
                },password2_nuevo: {
                    required: true
                },telefonos_nuevo: {
                    required: true
                },correo_nuevo: {
                    required: true,
                    email: true
                }

            },

            messages: {
                id_nuevo: {
                    required: "Id es requerido",
                    digits:"Id es un numero entero"
                },nombres_nuevo: {
                    required: "Nombrea es requerido"
                },apellidos_nuevo: {
                    required: "Apellidos es requerido"
                },usuario_nuevo: {
                    required: "Usuario es requerido"
                },password_nuevo: {
                    required: "Contraseña es requerida"
                },password2_nuevo: {
                    required: "Contraseña es requerida"
                },telefonos_nuevo: {
                    required: "Telefonos es requerido"
                },correo_nuevo: {
                    required: "Correo es requerido",
                    email: "Correo no cumple el formato indicado"
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

                var password = $("#password_nuevo").val();
                var password2 = $("#password2_nuevo").val();

                if(password != password2){
                    alert("Contraseñas no coinciden");
                    return false;
                }

                var lastname = $("#apellidos_nuevo").val();
                var name     = $("#nombres_nuevo").val();
                var username = $("#usuario_nuevo").val();
                var id       = $("#id_nuevo").val();
                var idzona   = $("#zona_nuevo").val();
                var correo   = $("#correo_nuevo").val();
                var telefonos= $("#telefonos_nuevo").val();

                var ids = id.split(",");

                var permission_data      = 0;
                var permission_pedidos   = 0;
                var permission_paviferia = 0;

                if($("#c_permission_data").is(':checked')) {
                    permission_data      = 1;
                }

                if($("#c_permission_pedidos").is(':checked')) {
                    permission_pedidos   = 1;
                }

                if($("#c_permission_paviferia").is(':checked')) {
                    permission_paviferia = 1;
                }

                var parametros = {
                    "a" : ids[0],
                    "b" : username,
                    "c" : password,
                    "d" : name,
                    "e" : lastname,
                    "g" : permission_data,
                    "h" : permission_pedidos,
                    "i" : permission_paviferia,
                    "j" : idzona,
                    "k" : telefonos,
                    "l" : correo
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

    $("button","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece
        //
        var result = confirm("¿Esta seguro de eliminar la <?=$NOMBRE_SHOW?> seleccionado?");

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
