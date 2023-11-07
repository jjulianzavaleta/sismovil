<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_grupocliente.php");

    $descripcion  = basic_validations($_POST['a']);
    $id           = $_POST['b'];
    $descuento           = $_POST['c'];


    $estado = "0";//0:error
	$error  = "";

    if(!isNumber($id) || !is_numeric($descuento)){

        $error = "Error: ID debe ser un numero entero";

    }else{
        $res = registrarGrupoPaviferiaCliente($id,$descripcion,$descuento,$_SESSION['id']);
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