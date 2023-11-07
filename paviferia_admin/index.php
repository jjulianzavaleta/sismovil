<?php

/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 01:30 AM
 */

include("../plantilla1.php");
include("../phps/dpaviferia_admin.php");

$NOMBRE_SHOW = "Admin";
$NOMBRE_SHOW_PLURAL = "Administradores";
?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
        <?php if($_SESSION['manageusers'] ===1){ ?>
        <a data-toggle="modal" id="nueva_empresa_btn" href="#nueva_actividad" class="btn btn-app btn-primary btn-mini">
            <i class="icon-plus-sign"></i>
            Nuevo
        </a>
        <?php } ?>

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
                             style="width: 50px;">ID
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Nombres
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Apellidos
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 183px;">Permisos
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Nombre de Usuario
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Estado
                        </th>

                        <?php if($_SESSION['manageusers'] === 1){ ?>
                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>
                        <?php } ?>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    if($_SESSION['manageusers'] == 1):
                        $data = getAllAdmins();
                    else:
                            if($_SESSION['isadmin'] === 1):
                                $data = getAdminById(intval($_SESSION['id']));
                            else:
                                $data = getUsuarioById(intval($_SESSION['id']));//para usuarios de paviferia web
                            endif;

                    endif;

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

                            $permisos   = "";
                            $prefijo    = "";

                            if(!isset($d['manageusers']))$d['manageusers'] = 0;

                            if($d['permission_paviferia']==1){$permisos.=$prefijo."- Paviferia"."<br>";}
                            if($d['manageusers']==1){$permisos.=$prefijo."- Administrar Usuarios"."<br>";}

                            $estado = "";
                            if($d['activo']===1){$estado = "Activo";}else{$estado="Inactivo";}


                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id'].",".$d['activo'].",".
                                        "0,0,".$d['permission_paviferia'].",".
                                        $d['manageusers']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"nombres\">".$d['nombres']."</td>";
                            echo "<td class=\"apellidos\">".$d['apellidos']."</td>";
                            echo "<td class=\"tipo\">".$permisos."</td>";
                            echo "<td class=\"usuario\">".$d['usuario']."</td>";
                            echo "<td class=\"estado\">".$estado."</td>";

                            if($_SESSION['manageusers'] === 1) {
                                echo $EDITAR_ELIMINAR_HTML_CODE;
                            }

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

<?php    if($_SESSION['manageusers'] === 1){ ?>
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
                            <label class="control-label" for="tipo_nuevo"> Permisos:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="c_permission_paviferia" name="permission_paviferia">Administrar Paviferia<br>
                                    <input type="checkbox" value="1" id="c_manageuser" name="manageuser">Administrar Usuarios<br>
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
                            <label class="control-label" for="nombres_editar">Nombres :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="nombres_editar" id="nombres_editar" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="apellidos_editar">Apellidos :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="apellidos_editar" id="apellidos_editar" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="tipo_editar"> Permisos:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="e_permission_paviferia" name="permission_paviferia">Administrar Paviferia<br>
                                    <input type="checkbox" value="1" id="e_manageuser" name="manageuser">Administrar Usuarios<br>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="usuario_editar">Nombre de Usuario :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="usuario_editar" id="usuario_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password_editar">Contraseña :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="password" name="password_editar" id="password_editar" class="span6" autocomplete="off"
                                           placeholder="Vacio no actualiza">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password2_editar">Repita Contraseña :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="password" name="password2_editar" id="password2_editar" class="span6" autocomplete="off"
                                           placeholder="Vacio no actualiza">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="estado_editar"> Estado :</label>
                            <div class="controls">
                                <div class="span12">
                                    <select class="span6" id="estado_editar" name="estado_editar">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
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
<!-- /.modal -->
<!--END MODA LEDITAR-->
<?php } ?>

</div>
<script>
    $( '#adminPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( " <a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

    $('#nueva_empresa_btn').on('click',function(){
        $("#nombres_nuevo").val("");
        $("#apellidos_nuevo").val("");
        $("#usuario_nuevo").val("");
        $("#password_nuevo").val("");
        $("#c_permission_paviferia").attr('checked', false);
        $("#c_manageuser").attr('checked', false);

        $.post("getNewId.php", {}, function(data){
            $("#id_nuevo").val(data);
        });

        setTimeout(function (){
            $("#nombres_nuevo").focus();
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
                null
                <?php if($_SESSION['manageusers'] === 1){ ?>
                ,{ "bSortable": false }
                <?php } ?>
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

        <?php    if($_SESSION['manageusers'] === 1){ ?>

        $('#validation-form_editar').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
                usuario_editar: {
                    required: true
                },
                nombres_editar: {
                    required: true
                },
                apellidos_editar: {
                    required: true
                },
                estado_editar: {
                    required: true
                }
            },

            messages: {
                usuario_editar: {
                    required: "Usuario es requerido"
                },
                nombres_editar: {
                    required: "Nombres es requerido"
                },
                apellidos_editar: {
                    required: "Apellidos es requerido"
                },
                estado_editar: {
                    required: "Estado es requerido"
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

                var password = $("#password_editar").val();
                var password2 = $("#password2_editar").val();

                if(password != password2){
                    alert("Contraseñas no coinciden");
                    return false;
                }

                var tipo = $("#tipo_editar").val();
                var estado = $("#estado_editar").val();
                var lastname = $("#apellidos_editar").val();
                var name = $("#nombres_editar").val();
                var username = $("#usuario_editar").val();
                var id =   $("#id_editar").val();

                var permission_paviferia = 0;
                var manageusers          = 0;

                if($("#e_permission_paviferia").is(':checked')) {
                    permission_paviferia = 1;
                }

                if($("#e_manageuser").is(':checked')) {
                    manageusers          = 1;
                }


                var parametros = {
                    "a" : id,
                    "b" : username,
                    "c" : password,
                    "d" : name,
                    "e" : lastname,
                    "f" : estado,
                    "i" : permission_paviferia,
                    "j" : manageusers

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
                var name = $("#nombres_nuevo").val();
                var username = $("#usuario_nuevo").val();
                var id =   $("#id_nuevo").val();

                var ids = id.split(",");
                var permission_paviferia = 0;
                var manageusers          = 0;

                if($("#c_permission_paviferia").is(':checked')) {
                    permission_paviferia = 1;
                }

                if($("#c_manageuser").is(':checked')) {
                    manageusers          = 1;
                }

                var parametros = {
                    "a" : ids[0],
                    "b" : username,
                    "c" : password,
                    "d" : name,
                    "e" : lastname,
                    "i" : permission_paviferia,
                    "j" : manageusers
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

        <?php } ?>

        $('.date-picker').datepicker();

    })

    <?php    if($_SESSION['manageusers'] === 1){ ?>

    $("a","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece

        var id   = $(this).parent().parent().parent().children("td.hide_column").text();
        var name = $(this).parent().parent().parent().children("td.nombres").text();
        var lastname = $(this).parent().parent().parent().children("td.apellidos").text();
        var username = $(this).parent().parent().parent().children("td.usuario").text();

        var ids = id.split(",");

        $("#id_editar").val(ids[0]);
        $("#nombres_editar").val(name);
        $("#apellidos_editar").val(lastname);
        $("#usuario_editar").val(username);
        $("#estado_editar").val(ids[1]);

        if(ids[4] == "1"){$("#e_permission_paviferia").attr('checked', true);}else{$("#e_permission_paviferia").attr('checked', false);}
        if(ids[5] == "1"){$("#e_manageuser").attr('checked', true);}else{$("#e_manageuser").attr('checked', false);}

        setTimeout(function (){
            $("#nombres_editar").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);

    });

    $("button","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece
        //
        var result = confirm("¿Esta seguro de eliminar la <?=$NOMBRE_SHOW?> seleccionada?");

        if(result == true){
            var id = $(this).parent().parent().parent().children("td.hide_column").text();

            var ids = id.split(",");

            var parametros = {
                "a" : ids[0]
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

    <?php } ?>
</script>

</body>
</html>
