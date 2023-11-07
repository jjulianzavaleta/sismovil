<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 03/08/15
 * Time: 11:49 PM
 */
 
function basic_validations($data) {

    if ( is_numeric($data) ) return $data;
    if ( !isset($data) or empty($data) ) return '';

    $data = trim($data);//Elimina espacio en blanco
    $data = stripslashes($data);//Quita las barras de un string con comillas escapadas.
    $data = htmlspecialchars($data);//Convierte caracteres especiales en entidades HTML

    $non_displayables = array(
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/[\x0e-\x1f]/'             // 14-31
    );

    foreach ( $non_displayables as $regex )
        $data = preg_replace( $regex, '', $data );

    $data = str_replace("'", "''", $data );

    return $data;

}

function typeValidations($value,$type){

    if($type == 'num'){
        if(!preg_match('/^[0-9]*$/',$value)){
            $value = '1';
        }
    }

    return $value;

}

function isCifrasOK($number,$maxCifrasDecimales,$maxCifrasEnteras){

    if(empty($number) || !is_numeric($number)) return false;

    if(strpos(strval($number),".") != false){

        $partes = explode(".",strval($number));

        if(strlen($partes[0]) > $maxCifrasEnteras || strlen($partes[1]) > $maxCifrasDecimales){
            return false;
        }else{
            return true;
        }

    }else{
        if(strlen(strval($number)) > $maxCifrasEnteras){
            return false;
        }else{
            return true;
        }
    }

}


function isNumber($value){

    if($value === "")
        return false;

    if(preg_match('/^[0-9]*$/',$value)){

        return true;
    }

    return false;

}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}
