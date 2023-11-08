<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_productos.php");

    $unidad       = $_POST['f'];
    $grupo        = $_POST['e'];
    $peso         = $_POST['d'];

    $descripcion  = basic_validations($_POST['a']);
    $id           = $_POST['b'];
    $id_new       = "";
    $desc_new     = "";
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id) || !is_numeric($peso)  || !is_numeric($grupo)){

        $error = "Error: ID, Peso deben de ser un numeros";

    }else{
        $res = registrarProductoPaviferia($id,$descripcion,$unidad,$peso,$grupo,$_SESSION['id']);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo guardar el registro";
        }else{
            $estado = "1";//exito
        }
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>