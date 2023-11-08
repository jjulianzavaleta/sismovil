<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 28/09/2015
 * Time: 09:58 PM
 */

include_once("conexion.php");

function getAllClientesPaviferia(){

    $sql = "select id,
                   (SELECT CASE paviferia_cliente.tipodocumento WHEN '1' THEN 'DNI' ELSE
                   (SELECT CASE paviferia_cliente.tipodocumento WHEN '2' THEN 'RUC' ELSE '' END) END) as tipodocumento,
                   nrodocumento,nombre_rzsocial,direccion
            from paviferia_cliente";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}