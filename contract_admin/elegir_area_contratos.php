<?php

session_start();

if(!isset($_SESSION['login'])){
    session_destroy();
    header("location:../index.php");
}

include_once("../phps/conexion.php");
include_once("../phps/dContract_permisosAdicionales.php");
include_once("../phps/dcontract_usuarios.php");


$permisos_adicionales = getPermisosAdicionalesByUser($_SESSION['username']);
$permisos_usuario = getPermissionsUsuarioContract($_SESSION['username']);

$areas = array();
$areas[0]['id'] = $permisos_usuario[0]['idarea'];
$areas[0]['desc'] = getAreaName($permisos_usuario[0]['idarea']);

foreach ($permisos_adicionales as $permiso){
    $areas[] = array('id' => $permiso['idarea'], 'desc' => $permiso['area']);
}

?>
<link rel="stylesheet" href="assets/css/elegirArea.css">
<script src="../assets/js/jquery-1.9.1.min.js"></script>

<label for="cars"><h1>Elegir área:</h1></label>

<select name="area" id="area" class="classic">

    <?php
    foreach ($areas as $area){
        echo '<option value="'. $area['id'].'">'.$area['desc'].'</option>';
    }
    ?>
</select>

<button id="send" name="send" onclick="send()" class="favorite styled">Continuar</button>

<script>
    function send(){
        var parametros = {
            a:  "<?=$_SESSION['username']?>",
            b:  $("#area").val()
        };

        $.ajax({
            data:  parametros,
            url:   'managePermisoAdicional.php',
            type:  'post',
            dataType: "html",
            beforeSend: function (repuesta) {
            },
            success: function(respuesta){

                respuesta = $.parseJSON( respuesta );

                if(respuesta.estado == "1"){
                    window.location.href = "index.php";
                }else{
                    alert("error");
                }
            },
            error: function(respuesta){
               alert("Error al conectar con el servidor");
            },
            failure: function(respuesta){
               alert("Error al conectar con el servidor");
            }
        });
    }

</script>