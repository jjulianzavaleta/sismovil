<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_clientesdescuentos.php");

    $nrdoc      = basic_validations($_POST['a']);
    $rzo_social = $_POST['b'];
    $grupo      = $_POST['c'];

    $estado = "0";//0:error
	$error  = "";

    if(empty($rzo_social) || empty($nrdoc) || empty($grupo)){

        $error = "Error: Algunos datos estan vacios";

    }else{
        $res = registrarClientesPaviferiaDescuento($nrdoc,$rzo_social,$grupo,$_SESSION['id']);
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