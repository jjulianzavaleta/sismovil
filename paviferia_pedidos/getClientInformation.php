<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 16/09/2015
 * Time: 11:56 PM
 */

include("../phps/validateSession.php");
include("../phps/validaciones.php");
include("../phps/dpaviferia_productos.php");

$filtro     = $_GET['filtro'];

if(!empty($filtro)){

    $lstClientes = getAllClientesPaviferiaLikeNroDocumento($filtro);

    if(!empty($lstClientes)){

        $output = "";

        foreach($lstClientes as $cliente){

            $output= $output.$cliente['nrodocumento']." - ".$cliente['nombre_rzsocial']." - ".$cliente['nombrecontacto']."|".$cliente['nrodocumento']."|".$cliente['tipodocumento']."|".$cliente['nombre_rzsocial']."|".$cliente['direccion']."|".$cliente['nombrecontacto']."|".$cliente['correocontacto']."|".$cliente['telefonofijo']."|".$cliente['celularcontacto']."|".$cliente['filial']." "."\n";
        }

        echo $output;

    }else{

        echo "";

    }

}else{
 die("Erro");
}