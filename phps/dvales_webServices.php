<?php

function save_result_from_app($data, $include_images = true){
	
	$link = conectarBD();	
	startTransaction($link);
	
	$sql = "UPDATE vales_vale 
			SET 
			    consumo_idusuario = ".$data['idusuario'].",
				consumo_fechaconsumo = GETDATE(),
				consumo_gps_longitude = '".$data['longitud']."',
				consumo_gps_latitude = '".$data['latitud']."',
				consumo_observacion = '".$data['observacion']."',
				kilom = ".$data['kilometraje'].",
				consumo_unidadmedida = ".$data['unidad_medida'].",
				estado = 3
			WHERE id = ".$data['idvale']." and estado = 2";
	
	$res = sqlsrv_query( $link, $sql);
	
	if($res === false){		
		return finishTransaction($link, false);
	}else{
		
		$cmdtuples = sqlsrv_rows_affected($res);
		
		if($cmdtuples != 1){
			return finishTransaction($link, false);
		}
		
		$detalle = $data['detalle'];
		
		foreach($detalle as $d){
			$url_image_item = "";
			if($include_images){
				$info = pathinfo(basename( $d['voucher_img']));
				$url_image_item = $data['idvale']."/".'img_'.$d['iditem'].'.'.$info['extension'];
			}
			
			$sql = "UPDATE vales_detalle_productos 
			        SET menge_chofer = ".$d['cantidad'].",
                        voucher_img = '".$url_image_item."',
                        voucher_nro = '".$d['voucher_nro']."'
					WHERE id = ".$d['iditem'];
			$res = sqlsrv_query( $link, $sql);
			
			if($res === false){		
				return finishTransaction($link, false);
			}
		}		
		
		$sql = "update vales_equipoweb set kilometraje ='".$data['kilometraje']."' where id = ".$data['equipo_id'];
		$res = sqlsrv_query( $link, $sql);
		
		if($res === false){		
			return finishTransaction($link, false);
		}
		
		$sql = "delete from vales_detalle_img_extras where idvale = ".$data['idvale'];
		$res = sqlsrv_query( $link, $sql);
		
		if($res === false){		
			return finishTransaction($link, false);
		}
		
		if( sizeof($data['mapping_extra_images']) > 0 ){
			$detalle = $data['mapping_extra_images'];
		
			foreach($detalle as $d){
				$url_image_item = "";
				if($include_images){
					$info = pathinfo(basename( $d['voucher_img']));
					$url_image_item = $data['idvale']."/".'img_extras_'.$d['iditem'].'.'.$info['extension'];
				}
				
				$sql = "INSERT into vales_detalle_img_extras(idvale,voucher_img,matnr) VALUES 
						(".$d['idvale'].",'".$url_image_item."','".$d['material']."')";
				$res = sqlsrv_query( $link, $sql);
				
				if($res === false){		
					return finishTransaction($link, false);
				}
			}	
		}
			
		return finishTransaction($link, $res);			
		
	}
}

function validarAccesoChofer($username,$password){

    $sql = "select * 
	        from vales_usuarioweb vend 
			where vend.num_doc_identidad = '$username' 
			and estado = 1
			and ( 
			     ( cod_conductor = '$password'  and password is NULL)
				    or
				 ( password =  '$password' )
			)";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);

	if( !empty( $data ) ){
		return array( "idusuario" => $data['id'] );
	}else{
		return false;
	}    
}

function getValesValidosByChofer($idchofer){
	
	 $sql = "select v.id, 
                    convert(varchar, v.fecha_registro, 103) as fecha_registro,
                    convert(varchar, v.fecha_max_consumo, 103) as fecha_max_consumo,
                    ad.usuario as planner,
                    'Emitido' as estado,
                    v.placa,
	                vg.nombre as grifo,
					( select name1 from vales_usuarioweb vuw where vuw.id = v.chofer ) as chofer,
					( select name1 from vales_usuarioweb vuw where vuw.id = v.chofer_aux ) as copiloto,
					(select concat('; ' ,vm1.nombre,': ',vdp.menge,' ') from vales_detalle_productos vdp inner join vales_material vm1 on vm1.id = vdp.matnr where vdp.idvale = v.id FOR XML PATH('')) as asignacion_display
	        from vales_vale v
			left join admin ad on v.usuario_registra = ad.id
			left join vales_grifo vg on v.grifo = vg.id
			where ( v.chofer = $idchofer or v.chofer_aux = $idchofer ) and v.anulado = 0 and v.estado = 2
			      and v.fecha_max_consumo >= GETDATE()
			order by v.id desc;";

    $link = conectarBD();
    $data = queryBD($sql,$link);

	
	return $data;
	 
}

function getValeCabecera($idvale){
	
	$sql = "select  v.id, 
                    convert(varchar, v.fecha_registro, 103) as fecha_registro,
                    convert(varchar, v.fecha_max_consumo, 103) as fecha_max_consumo,
                    ad.usuario as planner,
                    'Emitido' as estado,
                    v.placa,
	                vg.nombre as grifo,
					vg.longitud as grifo_longitud,
					vg.latitud as grifo_latitud,
					vg.direccion as grifo_direccion,
					( select name1 from vales_usuarioweb vuw where vuw.id = v.chofer ) as chofer,
					( select name1 from vales_usuarioweb vuw where vuw.id = v.chofer_aux ) as copiloto,					
					vew.kilometraje, 
					vew.id as equipo_id,
					v.istermoking as termoking,
					vew.rendimiento_estandar,
					vew.medida_contador
	        from vales_vale v
			left join admin ad on v.usuario_registra = ad.id
			left join vales_equipoweb ve on ve.id = v.equnr
			left join vales_grifo vg on v.grifo  = vg.id
			left join vales_equipoweb vew on vew.id = v.equnr
			where v.id = $idvale;";

    $link = conectarBD();
    $data = queryBD($sql,$link);
	
	if( !empty( $data ) ){
		return $data;
	}else{
		return false;
	}  
}

function getValeDetalle_Materiales($idvale){
	
	$sql = "select vdp.id, vdp.menge, vm.nombre as producto
	        from vales_detalle_productos vdp	
            inner join vales_material vm on vdp.matnr = vm.id			
			where vdp.idvale = $idvale;";

    $link = conectarBD();
    $data = queryBD($sql,$link);
	
	if( !empty( $data ) ){
		return $data;
	}else{
		return false;
	} 
}

function getValeDetalle_Asignaciones($idvale){
	
	$sql = "select vda.id, vda.asignacion, vcw.ktext as centrocosto, vm.nombre as material
	        from vales_detalle_asignacion vda
			inner join vales_centroweb vcw on vcw.id = vda.kostl
			inner join vales_material vm on vm.id = vda.matnr
			where vda.idvale = $idvale;";

    $link = conectarBD();
    $data = queryBD($sql,$link);
	
	if( !empty( $data ) ){
		return $data;
	}else{
		return false;
	} 
}

function changePassword($username,$new_password){
	
	$sql = "update vales_usuarioweb set password = '".$new_password."' where num_doc_identidad = '$username'";

    $link = conectarBD();
    $stmt = queryBD($sql,$link);
	
	if($stmt === false){
		return false;
	}else{
		return true;
	}
}

function getSetupAppMovil(){

    $sql = "select * from vales_setup";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);

	if( !empty( $data ) ){
		return $data;
	}else{
		return false;
	}    
}