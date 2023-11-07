<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 05/08/15
 * Time: 06:46 PM
 */


include_once("conexion.php");


/**
 * @param $user
 * @param $password
 * @return array|bool
 */
function getUsuarioByUsernameAndPassword($user,$password,$nocheckpassword=false){

	$password_sql1 = " adm.password = '$password' and ";
	$password_sql2 = " vend.password = '$password' and ";
	if($nocheckpassword){
			$password_sql1 = "";
			$password_sql2 = "";
	}
	
    $sql = "select id, usuario, permission_paviferia, 1 as isadmin, manageusers, departamento,puesto,puesto2
            from admin_paviferia adm
            where ".$password_sql1." adm.usuario = '$user' and adm.activo = 1
             UNION
            select id, usuario, permission_paviferia, 0 as isadmin, 0 as manageusers,'' as departamento,'' as puesto,'' as puesto2
            from paviferia_vendedor vend
            where  ".$password_sql2." vend.usuario = '$user' and vend.activo = 1 and vend.permission_paviferia = 1";
    $link = conectarBD();
    $data = queryBD($sql,$link,true);

    return $data;

}

/**
 * @param $id
 * @return array|bool
 */
function getAdminById($id){

    $sql = "select * from admin_paviferia adm where adm.id = $id ";

    $link = conectarBD();
    $data = queryBD($sql,$link);

    return $data;

}

function getUsuarioById($id){

    $sql = "select * from paviferia_vendedor vend where vend.id = $id ";

    $link = conectarBD();
    $data = queryBD($sql,$link);

    return $data;
}

/**
 * @return array|bool
 */
function getAllAdmins(){

    $sql = "select * from admin_paviferia";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}


function registrarUsuario( $id, $usuario, $password, $nombres, $apellidos, $estado,
                           $permission_paviferia,
                           $manageusers,$departamento="",$puesto="",$puesto2=""){

    $nombres = mb_strtoupper($nombres, 'UTF-8');
    $apellidos = mb_strtoupper($apellidos, 'UTF-8');

    $sql = "insert into admin_paviferia(id,usuario,password,nombres,apellidos,activo,permission_paviferia,manageusers,departamento,puesto,puesto2)
            values
                                ($id,'$usuario','$password',
                                '$nombres','$apellidos',$estado,
                                $permission_paviferia,$manageusers,'$departamento','$puesto','$puesto2')";
    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

/**
 * @return int
 */
function getNewIdAdmin(){

    $sql = "select MAX(id) as id from admin_paviferia ";

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

    $sql = "delete from admin_paviferia where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
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
 * @param $manageusers
 * @return array|bool
 */
function updateUsuario($id,$usuario,$password,$nombres,$apellidos,$estado,
                      $permission_paviferia,
                       $manageusers){

    $nombres = mb_strtoupper($nombres, 'UTF-8');
    $apellidos = mb_strtoupper($apellidos, 'UTF-8');

    if($password === ""){
        $update_password = "";
    }else{
          $update_password = ", password = '$password'";
    }

    $sql = "update admin_paviferia set nombres = '$nombres', apellidos = '$apellidos', activo = $estado,                             
                             permission_paviferia = $permission_paviferia, manageusers = $manageusers,
                             usuario = '$usuario' ".$update_password."
            where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}