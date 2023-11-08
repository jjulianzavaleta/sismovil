<?php


function saveResponseRFCConsumo($idvale, $request, $response, $status, $fromJob = false){
	
	$fromJob = $fromJob?1:0;
	
	$sql = "insert into vales_rfc_logs(idvale, rfc, request, response, success, fecha, byjob) values ( ".$idvale.", 'ZMMRFC_IFCU_CONSUMO_APP' , '".$request."', '".$response."', ".$status.", GETDATE(), ".$fromJob." )";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
	
	$sql = "update vales_vale set rfcconsumo_somethingwentwrong  = ".($status==1?0:1)." where id = ".$idvale;
	$res = queryBD($sql,$link,true);

    if($res === false)
        return false;
    else
        return true;
	
}

function getHistorialRFCConsumo($idvale){
	
	$link = conectarBD();
	$sql = "select *
			from vales_rfc_logs
			where idvale = ".$idvale." order by fecha desc";
			
	$data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data;
	else
		return false;
}

function getValesConsumidosInterval_rfc($days){
	
	$sql = "select vv.id as idvale, convert(varchar, vv.consumo_fechaconsumo, 25) as consumo_fechaconsumo
	        from vales_vale vv
		where vv.consumo_fechaconsumo >=  DATEADD(DAY,-".$days.",GETDATE())
                      and vv. anulado != 1
                      and not exists (select 1 from vales_rfc_logs where idvale = vv.id and success = 1 )
		order by vv.consumo_fechaconsumo desc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(empty($data)){
		return "";
	}else{
		return $data;
	}
}

function getDataVale_forRFCConsumoVale($idvale){

    $sql = "select 
				case when v.isflujoconsumidor = 1 then 1 else 2 end as flujo,
				ve.equnr,
				vg.nroestacion,
				convert(varchar, DATEADD(MINUTE,-4,v.consumo_fechaconsumo), 23) as fecha,
				convert(varchar, DATEADD(MINUTE,-4,v.consumo_fechaconsumo), 8) as hora,
				v.kilom,
				vdp.menge_chofer,
				vm.rfcname as materialnombre,
				ve.rendimiento_estandar,
				v.consumo_unidadmedida
			from vales_vale v
			inner join vales_equipoweb ve on ve.id = v.equnr
			inner join vales_grifo vg on vg.id = v.grifo
			inner join vales_detalle_productos vdp on vdp.idvale = v.id
			inner join vales_material vm on vm.id = vdp.matnr
			where v.id = ".$idvale;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    if(!empty($data))
        return $data[0];
    else
        return array();

}
