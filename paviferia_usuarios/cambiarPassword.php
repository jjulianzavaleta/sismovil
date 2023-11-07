<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 08/10/2015
 * Time: 06:16 PM
 */

    include("../phps/validateSession.php");
    include("../phps/dpaviferia_usuario.php");

    if( isset($_POST['iduser']) && isset($_POST['password']) ){

        $res = updatePassword($_POST['iduser'],$_POST['password']);
        if($res === false){
            die("Error no se pudo cambiar la contraseña");
        }else{
            die("Exito al cambiar la contraseña");
        }

    }else{
        die("Error");
    }