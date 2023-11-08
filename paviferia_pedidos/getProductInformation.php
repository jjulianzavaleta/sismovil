<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 23/08/2015
 * Time: 08:42 PM
 */
include("../phps/validateSession.php");
include("../phps/validaciones.php");
include("../phps/dpaviferia_productos.php");

$modofilter = $_GET['mode_filter'];

$filtro         = $_GET['filtro'];
$fechaVenta     = isset($_GET['fechaventa'])?$_GET['fechaventa']:'2015-09-17';

if($modofilter == 1 || $modofilter == 2){//filtro por nombre producto

    if(empty($filtro) && $filtro != 0){
        die("error");
    }

    if($modofilter == 1){

        $lstProductos = getAllProductosPaviferiaLikeName($filtro,$fechaVenta);
    }
    if($modofilter == 2){

        $lstProductos = getAllProductosPaviferiaLikeId($filtro,$fechaVenta);
    }


    if(!empty($lstProductos)){

        $output = "";

        foreach($lstProductos as $producto){

            if($modofilter == 1){

                $output= $output.$producto['descripcion']."|".$producto['id']."|1|".$producto['peso_unidad']." ".getUnidadMedicaById($producto['id'])."|".$producto['descripcion']."|".getGrupoProducto($producto['id'])."\n";
            }

            if($modofilter == 2){

                $output= $output.$producto['id']."|".$producto['id']."|1|".$producto['peso_unidad']." ".getUnidadMedicaById($producto['id'])."|".$producto['descripcion']."|".getGrupoProducto($producto['id'])."\n";
            }
        }

        echo $output;

    }else{

        echo "";

    }
}else{
    die("error");
}






//echo var_dump($lstProductos)."|12\n";