<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_usuario.php");

    $correo       = $_POST['l'];
    $telefonos    = $_POST['k'];
    $idzona       = $_POST['j'];
    $permission_paviferia   = basic_validations($_POST['i']);
    $permission_pedidos     = basic_validations($_POST['h']);
    $permission_data        = basic_validations($_POST['g']);
    $activo       = basic_validations($_POST['f']);
    $apellidos    = basic_validations($_POST['e']);
    $nombres      = basic_validations($_POST['d']);
    $id           = $_POST['a'];
    $id_new       = "";
    $usuario_new  = "";
    $nombres_new  = "";
    $apellidos_new= "";
    $estado_new   = "";
    $error        = "";
    $idzona_new   = "";

    $estado = "0";//0:error

    if(!isNumber($id) || !isNumber($idzona)){

        $error = "Error: ID debe ser un numero entero";

    }else{
        $res = updateUsuario($id,$nombres,$apellidos,$activo,$idzona,
                            $permission_data,$permission_pedidos,$permission_paviferia,
                            $correo,$telefonos);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo actualizar el registro";
        }else{
            $estado         = "1";//exito
            $id_new         = $res['id'];
            $usuario_new    = $res['usuario'];
            $nombres_new    = $res['nombres'];
            $apellidos_new  = $res['apellidos'];
            $estado_new     = $res['estado'];
            $idzona_new     = $res['idzona'];
        }
    }

    $response = array("estado" => $estado,"id" => $id_new, "usuario"=>$usuario_new, "idzona"=>$idzona_new,
                      "nombres"=>$nombres_new,"apellidos"=>$apellidos_new,"activo"=>$estado_new,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>