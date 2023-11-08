<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 08/10/2015
 * Time: 06:40 PM
 */

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Error recibiendo los datos");
}

include("../phps/dpaviferia_usuario.php");
include("../phps/dpaviferia_zona.php");
include("../plantilla1.php");

$idUsuario = $_GET['id'];
$lstZonas = getAllZonas();

$usuario = getUsuario2ById($idUsuario);

$estado_activo   = $usuario['activo']==1?'selected="selected"':'';
$estado_inactivo = $usuario['activo']==0?'selected="selected"':'';;
$idzona          = $usuario['idzona'];
$nombres         = $usuario['nombres'];
$apellidos       = $usuario['apellidos'];
$correo          = $usuario['correo'];
$telefono        = $usuario['telefonos'];
$permisos_tomadata  = $usuario['permission_data']==1?'checked="checked"':'';
$permisos_pedidos   = $usuario['permission_pedidos']==1?'checked="checked"':'';
$permisos_paviferia = $usuario['permission_paviferia']==1?'checked="checked"':'';

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->

        <form class="form-horizontal" id="validation-form_nuevo" method="post" novalidate="novalidate">

            <div class="row-fluid">
                <h5 class="header smaller lighter blue">Editar Datos del Usuario:  <span class="label label-success"><?=$usuario['usuario']?></span></h5>

                <table border="0" cellpadding="0" cellspacing="0" width="100%">

                    <tr>
                        <td>
                            <label class="control-label" for="estado"> Estado:</label>
                            <div class="control-group">
                                <select class="span6" id="estado" name="estado"">
                                <option value="1" <?=$estado_activo?>>Activo</option>
                                <option value="0" <?=$estado_inactivo?>>Inactivo</option>
                                </select>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="zona"> Zona (Paviferia y Toma de Data):</label>
                                <div class="control-group">
                                    <select class="span6" id="zona" name="zona"">
                                    <?php
                                    foreach($lstZonas as $zona){

                                        $selected = "";
                                        if($idzona  == $zona['id'])
                                            $selected = "selected='selected'";

                                        echo "<option $selected value='".$zona['id']."'>".$zona['descripcion']."</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="nombres">Nombres:</label>

                            <div class="control-group">
                                <input class="span6" name="nombres" id="nombres" type="text"
                                       value="<?=$nombres?>"/>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="apellidos">Apellidos:</label>

                            <div class="control-group">
                                <input class="span6" name="apellidos" id="apellidos" type="text"
                                       value="<?=$apellidos?>"/>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="correo">Correo:</label>

                            <div class="control-group">
                                <input class="span6" name="correo" id="correo" type="text"
                                       value="<?=$correo?>"/>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="telefono">Telefonos:</label>

                            <div class="control-group">
                                <input class="span6" name="telefono" id="telefono" type="text"
                                       value="<?=$telefono?>"/>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="tipo_nuevo"> Permisos:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="c_permission_data" name="permission_data" <?=$permisos_tomadata?>>Toma Data<br>
                                    <input type="checkbox" value="1" id="c_permission_pedidos" name="permission_pedidos" <?=$permisos_pedidos?>>Toma Pedidos<br>
                                    <input type="checkbox" value="1" id="c_permission_paviferia" name="permission_paviferia" <?=$permisos_paviferia?>>Paviferia<br>
                                </div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <input type="submit" class="btn btn-primary" value="Editar">
                        </td>
                    </tr>


                </table>

        </form>


    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->


<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>


</div>

<script src="../assets/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="../assets/css/jquery.autocomplete.css" />

<script>
    $( '#dbasesPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/datos_bases.php'>Datos Bases ></a> <a href='index.php'>Usuarios</a> <a href='editarUsuario.php?id=<?=$idUsuario?>'> > Editar Usuario</a>");

</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {

        $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            onkeyup: false,
            onclick: false,
            // onfocusout: true,
            rules: {
                estado: {
                    required: true
                },
                zona: {
                    required: true
                },
                nombres: {
                    required: true
                },
                apellidos: {
                    required: true
                },
                correo: {
                    required: true,
                    email: true
                },
                telefono: {
                    required: true
                }
            },

            messages: {
                estado: {
                    required: "Estado es requerido"
                },
                zona: {
                    required: "Zona  es requerida"
                },
                nombres: {
                    required: "Nombres son requeridos"
                },
                apellidos: {
                    required: "Apellifos son requeridos"
                },
                correo: {
                    required: "Correos son requeridos",
                    email: "Correo no cumple el formato de correo"
                },
                telefono: {
                    required: "Telefonos son requeridos"
                },
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

                var estado   = $("#estado").val();
                var lastname = $("#apellidos").val();
                var name     = $("#nombres").val();
                var idzona   = $("#zona").val();
                var correo   = $("#correo").val();
                var telefonos= $("#telefono").val();

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
                    "a" : <?=$idUsuario?>,
                    "d" : name,
                    "e" : lastname,
                    "f" : estado,
                    "g" : permission_data,
                    "h" : permission_pedidos,
                    "i" : permission_paviferia,
                    "j" : idzona,
                    "k" : telefonos,
                    "l" : correo
                };

                $.ajax({
                    data: parametros,
                    url: 'editar.php',
                    type: 'post',
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
                            setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

                        }else{

                            closeModal();
                            $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                            alert(respuesta.error);
                            location.reload();
                        }
                    },
                    error: function (respuesta) {
                        closeModal();
                        alert("Error al conectar con el servidor");
                    },
                    failure: function (respuesta) {
                        closeModal();
                        alert("Error al conectar con el servidor");
                    }
                });


            },
            invalidHandler: function (form) {
            }
        });


        $('[data-rel=tooltip]').tooltip();

        $('.date-picker').datepicker();

        $('table th input:checkbox').on('click', function () {
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });

        });

    })


</script>

</body>
</html>

