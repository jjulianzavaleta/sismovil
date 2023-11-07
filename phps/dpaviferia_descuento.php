<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 20/08/2015
 * Time: 10:42 PM
 */

include_once("conexion.php");

/**
 * @return array|bool
 */
function getAllDescuentosPaviferia(){

    $sql = "select descu_pav.*,
             (SELECT CASE descu_pav.tipo WHEN '1' THEN 'FORMA DE PAGO' ELSE
             (SELECT CASE descu_pav.tipo WHEN '2' THEN 'VOLUMEN' ELSE '' END) END) as tipodescuento_desc,
             (SELECT CASE descu_pav.tipo WHEN '1' THEN  (select descripcion from paviferia_formapago where id = idformapago) ELSE
             (SELECT CASE descu_pav.tipo WHEN '2' THEN  'De '+CONVERT(varchar(10),descu_pav.xvolumen_min)+' Hasta '+CONVERT(varchar(10),descu_pav.xvolumen_max)+' UN' ELSE '' END) END)
              as condicion,
              grupo.descripcion as grupodesc
            from paviferia_descuento descu_pav
            left join paviferia_grupo  grupo on grupo.id = descu_pav.idgrupo";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

/**
 * @param $tipo
 * @param $xvolumen_min
 * @param $xvolumen_max
 * @param $idformapago
 * @return bool
 */
function existsDescuento($tipo,$xvolumen_min,$xvolumen_max,$idformapago,$idgrupo){

    if($tipo == 1){

        $sql = "select 1
                from paviferia_descuento descu_pav
                where descu_pav.tipo = 1 and descu_pav.idformapago = $idformapago";
    }

    if($tipo == 2){

        $sql = "select 1
                from paviferia_descuento descu_pav
                where descu_pav.tipo = 2 and descu_pav.xvolumen_min = $xvolumen_min and descu_pav.xvolumen_max = $xvolumen_max and idgrupo = $idgrupo";
    }

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if(empty($data)){
        return false;
    }else{
        return true;
    }
}
/**
 * @param $tipo
 * @param $descuento
 * @param $xvolumen_min
 * @param $xvolumen_max
 * @param $idformapago
 * @param $idusuario
 * @return bool
 */
function registrarDescuentoPaviferia($tipo,$descuento,$xvolumen_min,$xvolumen_max,$idformapago,$idgrupo,$idusuario){

    if($idgrupo == 0){
        $idgrupo = 'null';
    }else{
        $idformapago = 'null';
    }

    $sql = "insert into paviferia_descuento(tipo,descuento,xvolumen_min,xvolumen_max,idformapago,idgrupo,usuarioregistra,fecharegistra)
            values ($tipo,$descuento,$xvolumen_min,$xvolumen_max,$idformapago,$idgrupo,$idusuario,GETDATE())";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;
    session_start();
    $_SESSION['mm'] =$sql;
    if($res === false)
        return false;
    else
        return true;
}

/**
 * @param $id
 * @param $descuento
 * @param $xvolumen_min
 * @param $xvolumen_max
 * @param $xformapagodesc
 * @param $idusuario
 * @return bool
 */
function updateDescuentoPaviferia($id,$descuento,$idusuario){

    $sql = "update paviferia_descuento set
            descuento = $descuento,
            usuariomodifica = $idusuario,
            fechamodifica = GETDATE()
            WHERE
            id = $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

/**
 * @param $id
 * @return bool
 */
function eliminarDescuentoPaviferia($id){

    $sql = "delete from paviferia_descuento where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}