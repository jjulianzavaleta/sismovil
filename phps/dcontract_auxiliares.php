<?php



function getContract_Empresas(){
    $sql = "select * from contract_empresa";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AreaUsuario($idusuario){
		
	$link = conectarBD();
	
    $sql = "select usuario from admin where id = ".$idusuario;
    $data = queryBD($sql,$link);
    
	
	if(empty($data)){
		$link = null;
		die("Error no hay match en usuario permisos y usuario Active Directory");
	}else{
		$usuarioAndDominio = $data[0]['usuario'];
		$pieces = explode("@", $usuarioAndDominio);
		
		$username = $pieces[0];
		
		$sql = "select ca.descripcion as area, ca.id as idarea
				from contract_usuarioshabilitados cuh
				inner join contract_area ca on cuh.idarea = ca.id
				where cuh.usuario = '".$username."'";
		$data = queryBD($sql,$link);		
		$link = null;
		
		if(empty($data)){
			die("El usuario ".$username." no tiene asignado un área.");
		}else{
			return $data[0];
		}
		
	}
}

function getContract_AreaUsuario_MOD2($idarea){
		
   $link = conectarBD();
	
   $sql = "select ca.descripcion as area, ca.id as idarea
			from contract_area ca
			where ca.id = '".$idarea."'";
	$data = queryBD($sql,$link);		
	$link = null;
		
	if(empty($data)){
		die("El usuario *** no tiene asignado un área.");
	}else{
		return $data[0];
	}
}

function getContract_JefeArea($area){
	
	$area_name = $area['area'];
	$area_id   = $area['idarea'];
	
    $sql = "select id,usuario,correo from contract_usuarioshabilitados where permission_responsablearea = 1 and idarea = ".$area_id;

    $link = conectarBD();
    $data = queryBD($sql,$link);

    $jefaturasAdicionales = getPermisosAdicionalesByArea($area_id, $link, true);
    $data = array_merge($data, $jefaturasAdicionales);

    $link = null;
	
	if(empty($data)){
		die("ERROR: El área ".$area_name." no tiene asignado ningun responsable de área.");
	}else{
		return $data;
	}
}

function getContract_CompradorLogitica(){
    $sql = "select * from contract_usuarioshabilitados where activo = 1 and comprador_logistica = 1 and activo = 1 order by usuario asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllAreas(){
    $sql = "select * from contract_area order by descripcion asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllProveedores(){
    $sql = "select * from contract_proveedor order by razon_social asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_RUCProveedor($idproveedor){
    $sql = "select ruc from contract_proveedor where idproveedor = ".$idproveedor;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllTipoContrato(){
    $sql = "select * from contract_tipocontrato order by descripcion asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllPlazoVigencia(){
    $sql = "select * from contract_vigenciaformato order by descripcion asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllTipoMoneda(){
    $sql = "select * from contract_tipomoneda order by descripcion asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllAvanze(){
    $sql = "select * from contract_avance order by id asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllTipoCreditos(){
    $sql = "select * from contract_credito order by id asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_JefeArea_byId($id){
	
    $sql = "select id,usuario,correo from contract_usuarioshabilitados where permission_responsablearea = 1 and idarea = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);

    $jefaturasAdicionales = getPermisosAdicionalesByArea($id, $link, true);
    $data = array_merge($data, $jefaturasAdicionales);

    $link = null;
	
	if(empty($data)){
		global $global_error;
		$global_error = "Error: No se asignó responsable de área para el área especificada.";
		return false;
	}else{
		return $data;
	}
}

function getContract_AllFormasPago(){
    $sql = "select * from contract_formapago";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllModalidadPago(){
    $sql = "select * from contract_modalidadpago";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_AllGarantias(){
    $sql = "select * from contract_garantia";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getContract_DatosUsuario($idusuario){	
    $sql = "select * from admin where id = ".$idusuario;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}