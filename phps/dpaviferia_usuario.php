<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 03/08/15
 * Time: 11:51 PM
 */

include_once("conexion.php");

/**
 * @return array|bool
 */
function getAllUsuarios(){

    $sql = "select vend.* , zon.descripcion as zonadesc
            from paviferia_vendedor vend
            inner join paviferia_zona zon on zon.id = vend.idzona";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getUsuario2ById($id){

    $sql = "select * from paviferia_vendedor vend where vend.id = $id ";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);

    return $data;
}

function getUsuarioByUsernameAndPassword($username,$password){

    $sql = "select * from paviferia_vendedor vend where vend.usuario = '$username' and password = '$password' ";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);

    return $data;
}


/**
 * @param $id
 * @param $usuario
 * @param $password
 * @param $nombres
 * @param $apellidos
 * @param $estado
 * @param $permission_data
 * @param $permission_pedidos
 * @param $permission_paviferia
 * @return array|bool
 */
function registrarUsuario( $id, $usuario, $password, $nombres, $apellidos, $estado, $idzona,
                            $permission_data,$permission_pedidos,$permission_paviferia,
                            $telefonos, $correo){

    $nombres = mb_strtoupper($nombres, 'UTF-8');
    $apellidos = mb_strtoupper($apellidos, 'UTF-8');

    $sql = "insert into paviferia_vendedor(id,usuario,password,nombres,apellidos,activo,
                                 permission_data,permission_pedidos,permission_paviferia,idzona,
                                 telefonos,correo)
            values
                                ($id,'$usuario','$password',
                                '$nombres','$apellidos',$estado,
                                $permission_data,$permission_pedidos,$permission_paviferia,
                                $idzona,'$telefonos','$correo')";
    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"usuario"=>$usuario,
                     "nombres"=>$nombres,"apellidos"=>$apellidos,
                     "estado"=>$estado,"permission_data"=>$permission_data,
                     "permission_pedidos"=>$permission_pedidos,"permission_paviferia"=>$permission_paviferia,
                     "idzona"=>$idzona);
}

/**
 * @return int
 */
function getNewIdUsuario(){

    $id = 0;

    $sql = "select MAX(id) as id from paviferia_vendedor ";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false){
        $id = null;
    }else{
        $id = intval($data['id']) + 1;
    }

    return $id;
}



/**
 * @param $id
 * @return bool
 */
function eliminarUsuario($id){

    $sql = "delete from paviferia_vendedor where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}



function updateUsuario($id,$nombres,$apellidos,$estado,$idzona,
                       $permission_data,$permission_pedidos,$permission_paviferia,
                       $correo, $telefonos){

    $nombres = mb_strtoupper($nombres, 'UTF-8');
    $apellidos = mb_strtoupper($apellidos, 'UTF-8');


    $sql = "update paviferia_vendedor set nombres = '$nombres', apellidos = '$apellidos', activo = $estado,
                                permission_data = $permission_data, permission_pedidos = $permission_pedidos,
                                permission_paviferia = $permission_paviferia,
                                idzona = $idzona,
                                telefonos = '$telefonos',
                                correo  = '$correo'
            where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function updatePassword($idUsuario,$password){

    $sql = "update paviferia_vendedor set password = '$password' where id = $idUsuario";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;

}


