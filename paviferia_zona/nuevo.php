<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_zona.php");

    $telefonos    = basic_validations($_POST['d']);
    $direccion    = basic_validations($_POST['c']);
    $descripcion  = basic_validations($_POST['a']);
    $id           = $_POST['b'];
    $id_new       = "";
    $desc_new     = "";
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id)){

        $error = "Error: ID debe ser un numero entero";

    }else{
        $res = registrarZona($id,$descripcion,$direccion,$telefonos);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo guardar el registro";
        }else{
            $estado = "1";//exito
            $id_new   = $res['id'];
            $desc_new = $res['descripcion'];
        }
    }

    $response = array("estado" => $estado,"id" => $id_new, "descripcion" => $desc_new,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>