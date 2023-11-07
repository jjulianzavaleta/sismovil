<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 27/08/2015
 * Time: 12:52 AM
 */

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_pedido.php");

    $detallepedido      = $_POST['a'];
    $fecha_emision      = $_POST['b'];
    $formadepago        = $_POST['c'];
    $tipo_documento     = $_POST['d'];
    $nro_documento      = basic_validations($_POST['e']);
    $nombre_rzsocial    = basic_validations($_POST['f']);
    $direccion          = basic_validations($_POST['g']);
    $nombre_contacto    = basic_validations($_POST['h']);
    $email_contacto     = basic_validations($_POST['i']);
    $telefono_contacto  = basic_validations($_POST['j']);
    $celular_contacto   = basic_validations($_POST['k']);
    $subtotal           = $_POST['l'];
    $igv                = $_POST['m'];
    $total              = $_POST['n'];
    $modoEnvio          = $_POST['o'];
    $idcliente          = $_POST['p'];
    $fechaventa         = $_POST['q'];
    $filial             = $_POST['r'];
    $fechavalida             = $_POST['s'];
    $id_new       = "";
    $error        = "";

    $estado = "0";//0:error

    if(!is_numeric($formadepago) || !is_numeric($tipo_documento) || !is_numeric($subtotal) ||
       !is_numeric($igv) || !is_numeric($total) || empty($nro_documento) || empty($nombre_rzsocial) ||
       empty($direccion) || !is_numeric($modoEnvio) || !is_numeric($idcliente) || empty($fechaventa) || empty($fechavalida)){

        $error = "Error: Algunos datos son incorrectos";

    }else{

        $_SESSION['merror'] = "";
        $detallepedido = objectToArray(json_decode($detallepedido));
        $res = registrarPedidoPaviferia($detallepedido,$fecha_emision,$formadepago,$tipo_documento,$nro_documento,
                                        $nombre_rzsocial,$direccion,$nombre_contacto,$email_contacto,$telefono_contacto,
                                        $celular_contacto,$_SESSION['id'],$modoEnvio,$fechaventa,$idcliente,$filial,$fechavalida);

        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo guardar el registro. \n".$_SESSION['merror'];
        }else{
            $estado = "1";//exito
            $id_new   = $res;
        }

        unset($_SESSION['merror']);
    }

    $response = array("estado" => $estado,"id" => $id_new,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>