<?php

function login_consumidor($user, $pass){
	
	$sql = "select id, cod_conductor, name1, num_doc_identidad
			from vales_usuarioweb
			where num_doc_identidad = '".$user."' and cod_conductor = '".$pass."' and estado = 1 and isflujoconsumidor = 1";

    $link = conectarBD();
    $data = queryBD($sql,$link);
	
	if( empty($data) ){
		return false;
	}else{
		return $data[0];
	}
    
}

function getNameConsumidorFromChoferes($idchofer){
	
	$sql = "select name1 from vales_usuarioweb where id = ".$idchofer;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data)){		
		return $data[0]['name1'];
	}else{
		return false;
	}
}

function getValesFlujo1Consumidor($idconsumidor){
	
	$sql = "select vv.anulado, vv.estado, vv.id, vv.fecha_registro, vv.fecha_max_consumo, vv.placa, vv.usuario_registra, vu.name1 as usuario, vv.consumo_fechaconsumo, ve.equnr
			from vales_vale vv
			inner join vales_equipoweb ve on ve.id = vv.equnr
            left join vales_usuarioweb vu on vv.usuario_registra = vu.id
			where vv.fecha_registro >  DATEADD(MONTH,-3,GETDATE()) and vv.usuario_registra = ".$idconsumidor." and vv.isFlujoConsumidor = 1
			order by vv.fecha_registra desc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}