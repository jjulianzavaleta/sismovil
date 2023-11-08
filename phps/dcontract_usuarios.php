<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 11:31 PM
 */


/**
 * @return array|bool
 */
function getAllContractUsuarios(){

    $sql = "select cu.*, ca.descripcion as area, ca.id as idarea
			from contract_usuarioshabilitados cu
			left join contract_area ca on ca.id = cu.idarea
			";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}


function registrarContractUsuario( $usuario,$permission_crear,$permission_aprobar,$permission_reportes,$correo,$permission_admin, $permission_responsablearea, $area ,$tipo_usuario){

    if( isUserAlreadyRegistered($usuario, $correo) ){
        return false;
    }

    $usuario = mb_strtolower($usuario, 'UTF-8');

    $sql = "insert into contract_usuarioshabilitados(usuario,permission_crear,permission_aprobar,permission_reportes,activo,correo,permission_admin,permission_responsablearea,idarea,tipo_usuario) values ('$usuario',$permission_crear,$permission_aprobar,$permission_reportes,1,'$correo',$permission_admin,$permission_responsablearea, $area, $tipo_usuario)";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function isUserAlreadyRegistered($usuario, $correo){

    $sql = "select 1 from contract_usuarioshabilitados where UPPER(usuario) = '".strtoupper($usuario)."' or  UPPER(correo) = '".strtoupper($correo)."'";
    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return !empty($data);
}


/**
 * @param $id
 * @return bool
 */
function eliminarContractUsuario($id){

    $sql = "delete from contract_usuarioshabilitados where id = $id";

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
 * @param $descripcion
 * @return array|bool
 */
function updateContractUsuario($id, $permission_crear,$permission_aprobar,$permission_reportes,$activo,$correo,$permission_admin,$permission_responsablearea,$area,$tipo_usuario){
    
    $sql = "update contract_usuarioshabilitados set permission_crear=$permission_crear, permission_aprobar=$permission_aprobar, permission_reportes=$permission_reportes, activo=$activo, correo='$correo', permission_admin=$permission_admin, permission_responsablearea=$permission_responsablearea, idarea=$area, tipo_usuario=$tipo_usuario where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function getPermissionsUsuarioContract($usuario){
	
	$pos = strpos($usuario, '@');
	if ($pos === false) {
		return array();
	}else{
		$usuario = str_replace(substr($usuario, $pos), "", $usuario);
	}
	
	$sql = "select * from contract_usuarioshabilitados where usuario = '$usuario' and activo = 1";
    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getListaAreas(){
	
	$sql = "select * from contract_area order by descripcion asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function hasUsuarioExtraPermisos($userName){

    $pos = strpos($userName, '@');
    if ($pos === false) {
        return array();
    }else{
        $userName = str_replace(substr($userName, $pos), "", $userName);
    }

    $sql = "select 1 
            from contract_areajefaturas caj
            inner join contract_usuarioshabilitados cha on cha.id = caj.idusuariohabilitado
            where cha.usuario = '$userName'
            group by caj.idusuariohabilitado";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    if(empty($data))
        return false;
    else
        return true;
}

function getAreaName($idArea){

    $sql = "select ca.descripcion as area
            from contract_area ca
            where ca.id = $idArea";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data[0]['area'];
}
