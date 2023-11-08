<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_productos.php");


    $peso         = $_POST['d'];

    $descripcion  = basic_validations($_POST['a']);
    $id           = $_POST['b'];
    $id_new       = "";
    $desc_new     = "";
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id) || !is_numeric($peso) ){

        $error = "Error: ID, Peso deben de ser un numeros";

    }else{
        $res = updateProductoPaviferia($id,$descripcion,$peso,$_SESSION['id']);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo actualizar el registro";
        }else{
            $estado = "1";//exito
            $id_new   = $res['id'];
            $desc_new = $res['descripcion'];
        }
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>