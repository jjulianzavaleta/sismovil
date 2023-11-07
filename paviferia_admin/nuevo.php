<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_admin.php");

    $manageusers            = basic_validations($_POST['j']);
    $permission_paviferia   = basic_validations($_POST['i']);
    $activo       = 1;//activo por defecto
    $apellidos    = basic_validations($_POST['e']);
    $nombres      = basic_validations($_POST['d']);
    $password     = basic_validations($_POST['c']);
    $usuario      = basic_validations($_POST['b']);
    $id           = $_POST['a'];
    $id_new       = "";
    $usuario_new  = "";
    $nombres_new  = "";
    $apellidos_new= "";
    $estado_new   = "";
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id)){
        $error = "Error: ID debe ser un numero entero";
    }else{
        $res = registrarUsuario($id,$usuario,$password,$nombres,$apellidos,$activo,
                                $permission_paviferia,
                                $manageusers);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo guardar el registro";
        }else{
            $estado         = "1";//exito
        }
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>