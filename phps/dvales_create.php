<?php

function deshacer_emicion($idvale){
	$sql = "update vales_vale set estado = 1 where id = ".$idvale;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function anular_vale($data){
	
	$idvale = $data['id'];
	
	$idequipo 					= getIdEquipoFromVale($idvale);    
	$lastValeConsumido 			= getUltimoValeConsumidoByVehiculo($idequipo);//Obtiene el ultimo Vale Consumido
	$previousLastValeConsumido 	= getUltimoValeConsumidoByVehiculo($idequipo, $idvale);//Obtiene el ultimo Vale Consumido sin incluir el actual a anular	
	
	$isLastValeConsumidoByEquipo = $idvale == $lastValeConsumido['idvale']?"1":"0";

    $new_value = 0;
    if( !empty($previousLastValeConsumido) ){
        $new_value = $previousLastValeConsumido['vale_kilometraje'];
    }

    if($isLastValeConsumidoByEquipo == "1" && !empty($previousLastValeConsumido)){
        $new_observacion = "Vale fue anulado estando consumido y siendo el ultimo consumo del equipo, se revirtio el kilometraje del equipo al ultimo vale consumido NRO ".$previousLastValeConsumido['idvale']." con valor de kilometraje: ".$new_value;
    }else if($isLastValeConsumidoByEquipo == "1" && empty($previousLastValeConsumido)){
        $new_observacion = "Vale fue anulado estando consumido y siendo el ultimo consumo del equipo, se revirtio el kilometraje del equipo a cero";
    }else{
        $new_observacion = "Vale fue anulado, no se hicieron cambios al kilometraje del equipo";
    }
	
	$result = true;

	if( $isLastValeConsumidoByEquipo == "1" )
		$result = update_kilometraje($idvale,$new_value,$data['usuario'],$isLastValeConsumidoByEquipo,$idequipo,$new_observacion, false);
	
	if($result){
		$link = conectarBD();
		$sql = "update vales_vale set anulado = 1, usuario_anula = ".$data['usuario'].", fecha_anula = GETDATE() where id = ".$idvale;
		$result = queryBD($sql,$link);
	}

    if($result === false)
        return false;
    else
        return $new_observacion;
}

function update_vale($data){
	
	$link = conectarBD();	
	startTransaction($link);
	
	$estado = $data['emitir']==1?2:1;
	$sql_emite1 = "";
	if($estado==2){
		$sql_emite1 = ",usuario_emite=".$data['e'].",fecha_emite=GETDATE()";
	}
	$hasCarreta = isset($data['hasCarreta'])?$data['hasCarreta']:0;
	
	$sql = "UPDATE vales_vale
	        SET  equnr=".$data['a'].", placa=(select license_num from vales_equipoweb where id = ".$data['a']."), grifo=".$data['c'].", 
			    hasCarreta=".$hasCarreta.",isTermoking=".$data['k'].",fecha_max_consumo='".$data['d']."', estado=".$estado.", usuario_modifica=".$data['e'].",detalle2_modo=".$data['h'].", fecha_modifica=GETDATE(),chofer=".$data['i'].",chofer_aux=".$data['j'].",tractor_implemento='".$data['l']."', tractor_promedio='".$data['m']."'".$sql_emite1."
	        WHERE id = ".$data['id'].";";
	
	$res = sqlsrv_query( $link, $sql);
	
	if($res === false){		
		return finishTransaction($link, false);
	}else{
		
		$lastId = $data['id'];
		
		$res = delete_detalles($link, $lastId);
		
		if($res === false){		
			return finishTransaction($link, false);
		}else{
			$res    = save_detalle1($link, $data['f'], $lastId);
			$lastDetalle1ItemId = $res;
		
			if($res === false){		
				return finishTransaction($link, false);
			}else{
				
				$res = save_detalle2($link, $data['g'], $lastId, $data['a'], $data['f'], $data['h']);				
				
				if( finishTransaction($link, $res) ){
					return array("idvale" => $lastId, "lastDetalle1ItemId" => $lastDetalle1ItemId, "equipo_id" => $data['a']);
				}else{
					return false;
				}				
			}
		}
	}
}

function create_vale($data){
	
	$link = conectarBD();	
	startTransaction($link);
	
	$estado    = $data['emitir']==1?2:1;
	$tipoFlujo = isset($data['flujo'])?$data['flujo']:0;
	$sql_emite1 = "";
	$sql_emite2 = "";
	if($estado==2){
		$sql_emite1 = ",usuario_emite,fecha_emite";
		$sql_emite2 = ",".$data['e'].",GETDATE()";
	}
	$hasCarreta = isset($data['hasCarreta'])?$data['hasCarreta']:0;

	$chofer_aux = empty($data['j'])?"NULL":$data['j'];
	
	$sql = "INSERT INTO
				vales_vale( equnr, placa, grifo, isflujoconsumidor, 
				           fecha_registro, fecha_max_consumo, estado, anulado,detalle2_modo,				          
						   usuario_registra, fecha_registra,chofer,chofer_aux,isTermoking,hasCarreta,
				           tractor_implemento, tractor_promedio".$sql_emite1.")
	        VALUES ( ".$data['a'].", (select license_num from vales_equipoweb where id = ".$data['a']."), ".$data['c'].", ".$tipoFlujo.", '".date("Y-m-d")."','".$data['d']."' , ".$estado.", 0, ".$data['h'].", ".$data['e'].", GETDATE(),".$data['i'].",".$chofer_aux.",".$data['k'].",".$hasCarreta.",'".$data['l']."','".$data['m']."'".$sql_emite2.");";
	
	$res = sqlsrv_query( $link, $sql);
	
	if($res === false){		
		return finishTransaction($link, false);
	}else{
		
		$lastId = sqlsrv_fetch_array(sqlsrv_query( $link, "SELECT SCOPE_IDENTITY();"), SQLSRV_FETCH_NUMERIC)[0];
		
		$res    = save_detalle1($link, $data['f'], $lastId );
		$lastDetalle1ItemId = $res;
		
		if($res === false){		
			return finishTransaction($link, false);
		}else{
			
			$res = save_detalle2($link, $data['g'], $lastId, $data['a'], $data['f'], $data['h']);
			
			if( finishTransaction($link, $res) ){
				return array("idvale" => $lastId, "lastDetalle1ItemId" => $lastDetalle1ItemId, "equipo_id" => $data['a']);
			}else{
				return false;
			}			
		}
	}
}

function save_detalle1(&$link, $data, $lastId){
	
	$detalle1_productos  = objectToArray(json_decode($data));	
	
	if( empty($detalle1_productos) ){
		return true;
	}
		
	$sql = "";
	foreach($detalle1_productos as $item){
		$sql.= "(".$lastId.",".$item['idmaterial'].",".$item['cantidad']."),";
	}
	$sql = substr($sql, 0, -1);
	$sql = "INSERT INTO vales_detalle_productos(idvale,matnr,menge) VALUES ".$sql;
		
	$res = sqlsrv_query( $link, $sql);
	
	if($res === false)
		return false;
	else{
		$lastId = sqlsrv_fetch_array(sqlsrv_query( $link, "SELECT SCOPE_IDENTITY();"), SQLSRV_FETCH_NUMERIC)[0];
		return $lastId;
	}
}

function save_detalle2(&$link, $data, $lastId, $equipo = false , $lista_productos = array() , $detalle2_modo = 1){
	
	if( $detalle2_modo == 1 ){//modo automatico
		$detalle2_asignacion = get_asignacion_default($equipo, objectToArray(json_decode($lista_productos)));
	}else{
		$detalle2_asignacion = objectToArray(json_decode($data));
	}
	
	if( empty($detalle2_asignacion) ){
		return true;
	}
			
	$sql = "";
	foreach($detalle2_asignacion as $item){
		$sql.= "(".$lastId.",".$item['centrocosto'].",".$item['idmaterial'].",".$item['cantidad']."),";
	}
	$sql = substr($sql, 0, -1);
	$sql = "INSERT INTO vales_detalle_asignacion(idvale,kostl,matnr,asignacion) VALUES ".$sql;
			
	return sqlsrv_query( $link, $sql);
}

function get_asignacion_default($equipo, $lista_productos){
	
	$centrocosto = getCentroCostobyEquipo($equipo);
	$data        = array();
	
	foreach($lista_productos as $item){
		$data[] = array( "centrocosto" => $centrocosto, "idmaterial" => $item['idmaterial'], "cantidad" => 100.0);
	}
	
	return $data;
}

function delete_detalles(&$link, $lastId){
	
	$sql = "DELETE FROM vales_detalle_productos	WHERE idvale = ".$lastId.";";
	sqlsrv_query( $link, $sql);
	
	$sql = "DELETE FROM vales_detalle_asignacion WHERE idvale = ".$lastId.";";
	return sqlsrv_query( $link, $sql);
}

function getDataDetalle2($id_vale){
	
	$sql = "select * from vales_detalle_asignacion where idvale = $id_vale";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data;
	else
		return false;
}

function getDataDetalle1($id_vale){
	
	$sql = "select * from vales_detalle_productos where idvale = $id_vale";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data;
	else
		return false;
}

function getDataVale($id_vale){
	
	$sql = "select * from vales_vale where id = $id_vale order by equnr asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0];
	else
		return false;
}

function extractUsername($email){

    if(!empty($email)){
        $data__ = explode("@", $email);
        return $data__[0];
    }else{
        return "No user";
    }
}

function getUsernameFromUsuarioActiveDirectory($id){
	
	$sql = "select usuario from admin where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data)){
		$data__ = explode("@", $data[0]['usuario']);
		return $data__[0];
	}else{
		return false;
	}
}

function getChoferNameFromID($id){
	
	$sql = "select name1 from vales_usuarioweb where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['name1'];
	else
		return false;
}

function getMaterialNameFromValeDetalleProducto($id_detallle_producto){

    $sql = "select nombre
            from vales_material vm
            inner join vales_detalle_productos vdp on vdp.matnr = vm.id
            and vdp.id = ".$id_detallle_producto;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    if(!empty($data))
        return $data[0]['nombre'];
    else
        return false;
}

function getMaterialNameFromMaterial($id){
	
	$sql = "select nombre from vales_material where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['nombre'];
	else
		return false;
}

function getCentroCostoName($id){
	
	$sql = "select ktext from vales_centroweb where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['ktext'];
	else
		return false;
}

function getCentroCostoCodigo($id){
	
	$sql = "select kostl from vales_centroweb where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['kostl'];
	else
		return false;
}

function getAllEquiposWebForCombobox(){

    $sql = "select * from vales_equipoweb order by equnr asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getAllCentroCostoWebForCombobox(){

    $sql = "select * from vales_centroweb order by ktext asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getAllGrifosForCombobox(){

    $sql = "select * from vales_grifo order by nombre asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getAllGrifosForCombobox_flujo1(){

    $sql = "select * from vales_grifo where flujo = '1' order by nombre asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}


function getAllMaterialForCombobox(){

    $sql = "select * from vales_material order by nombre asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getAllChoferesForCombobox(){
	
	$sql = "select * from vales_usuarioweb order by name1 asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getTractorImplementosCombobox(){
    $sql = "select * from vales_tractor_implemento order by id asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getTractorPromedioCombobox(){
    $sql = "select * from vales_tractor_promedio order by id asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getImplementoPromedio(){
    $sql = "select vti.valor as implemento, vtp.valor as promedio
            from vales_tractor_implemento vti
            inner join vales_tractor_promedio vtp on vtp.implemento_id = vti.id
            order by vti.id asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function generate_javascript_mapping($found_implemento, $found_promedio, $tractor_implemento, $tractor_promedio){
    $vales_implemento_promedio = getImplementoPromedio();

    if(!$found_implemento or !$found_promedio){
        $vales_implemento_promedio[] = array(
            "implemento"=>$tractor_implemento,
            "promedio"=>$tractor_promedio
        );
    }

    $mappings = array();
    foreach ($vales_implemento_promedio as $element){
        $mappings[] = '
                 if(idImplemento == "'.$element['implemento'].'"){
                     $("#tractor_promedio").val('.$element['promedio'].');
                     $("#tractor_promedio").trigger("chosen:updated");
                 }';
    }
    return implode("else", $mappings);
}

function getCentroCostobyEquipo($idequipo){
	
	$sql = "select kostl from vales_equipoweb where id =  ".$idequipo;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['kostl'];
	else
		return false;
}

function getCentroCostoDatabyEquipo($idequipo){
	
	$sql = "select vcw.id, vcw.kostl ,
			(select medida_contador from vales_equipoweb vew where vew.id = ".$idequipo.") as medida_contador
		    from vales_centroweb vcw 
			where vcw.id =  ".getCentroCostobyEquipo($idequipo);

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0];
	else
		return false;
}

function update_kilometraje($idvale,$new_value,$idusuario,$isLastValeConsumidoByEquipo,$idequipo,$new_observacion,$updateOnVale=true){

	$link = conectarBD();
	startTransaction($link);
	
	$sql = "insert into vales_historial_kilometraje(idvale,usuario,fecha,vale_valor_old,vale_valor_new,was_equipo_valor_updated,vale_obs_old,vale_obs_new) values (".$idvale.",".$idusuario.",GETDATE(),(select kilom from vales_vale where id = ".$idvale."),".$new_value.",".$isLastValeConsumidoByEquipo.",(select consumo_observacion from vales_vale where id = ".$idvale."),'".$new_observacion."')";

    $res = queryBD($sql,$link);
	
	if($res === false){
		return finishTransaction($link, false);	
	}

    if($updateOnVale){
        $sql = "update vales_vale set kilom = ".$new_value.", consumo_observacion = '".$new_observacion."' where id = ".$idvale;

        $res = queryBD($sql,$link);
        if($res === false){
            return finishTransaction($link, false);
        }
    }
	
	if($isLastValeConsumidoByEquipo == "1"){
		$sql = "update vales_equipoweb set kilometraje = '".$new_value."' where id = ".$idequipo;

		$res = queryBD($sql,$link);
		if($res === false){
			return finishTransaction($link, false);	
		}
	}
    
	$sql = "select kilom from vales_vale where id =  ".$idvale;
	$data = queryBD($sql,$link);
	
	finishTransaction($link, $res);	
    $link = null;
	
	if(!empty($data))
		return $data[0]['kilom'];
	else
		return false;

}

function getHistorialKilometraje($id_vale){
	
	$sql = "select * from vales_historial_kilometraje where idvale = ".$id_vale;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;	
}

function getUltimoValeConsumidoByVehiculo($idvehiculo, $notIncludeVale=""){
	
	if( !empty($notIncludeVale) ){
		$notIncludeVale = " AND v.id NOT IN ( ".$notIncludeVale." )";
	}
	
	$link = conectarBD();
	$sql = "select top 1 v.id as idvale, ew.kilometraje as equipo_kilometraje, v.kilom as vale_kilometraje, ew.equnr, v.equnr as vale_eqnro
			from vales_vale v
			inner join vales_equipoweb ew on ew.id = v.equnr
			where v.equnr = '".$idvehiculo."' and v.estado = 3 and v.anulado != 1 ".$notIncludeVale."
			order by v.consumo_fechaconsumo desc";
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0];
	else
		return false;
}

function getLogsTSMobile($idvale){
	
	$link = conectarBD();
	$sql = "select v.id as idvale,tsomobile_somethingwentwrong,tsomobile_byjob,tsomobile_fechaconsulta,tsomobile_response,tsomobile_endpoint
			from vales_vale v
			where v.id = '".$idvale."'  and v.estado = 3";
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0];
	else
		return false;
	
}


function getIdEquipoFromVale($idvale){
	$link = conectarBD();
	$sql = "select v.placa, ve.id as idequipo
			from vales_vale v
			inner join vales_equipoweb ve on ve.id = v.equnr
			where v.id = ".$idvale;
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['idequipo'];
	else
		return false;
}

function getRFCResponse($idvale){
	
	$link = conectarBD();
	$sql = "select top 1 idvale, success, fecha, response
			from vales_rfc_logs
			where  idvale = ".$idvale." and fecha > dateadd(minute, -10, GetDate())
			order by fecha desc";
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0];
	else
		return false;
}

function undoConsumido($idvale){
	
	$link = conectarBD();
	$sql = "update vales_vale set estado = 2 where id = ".$idvale;
			
	$response = queryBD($sql,$link);
    $link = null;
	
	if($response === false)
		return false;
	else
		return true;
}

function getKilometrajeEquipo($idequipo){
	
	$link = conectarBD();
	$sql = "select id, kilometraje, rendimiento_estandar from vales_equipoweb where id = ".$idequipo;
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0];
	else
		return false;
}

function get_img_consumo_extras($idvale){
	
	$link = conectarBD();
	$sql = "select * from vales_detalle_img_extras where idvale = ".$idvale;
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data;
	else
		return false;
}

function getMaxNumImagenesExtras(){
	
	$link = conectarBD();
	$sql = "select max_images_per_product from vales_setup";
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['max_images_per_product'];
	else
		return false;
}