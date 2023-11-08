<?php

function getAllMiValesWithFilters($opt,$fechaIni,$fechaFin,$chk_registrado,$chk_emitido,$chk_consumido,$chk_anulado,$chk_placa,$chk_chofer,$chk_centrocosto,$chk_grifo,$chk_observacion,$select_1,$select_2,$select_3,$select_4,$observacion,$descargar){
	
	$filters = "";
	if($opt==1){
		
		if( strlen($fechaIni) > 9 && strlen($fechaFin) > 9 )
			$filters = " AND '".$fechaIni."' <= vv.fecha_registra AND '".$fechaFin."' >= vv.fecha_registra";
		
		$estados = array();		
		if(!empty($chk_registrado)){
			$estados[] = 1;
		}
		if(!empty($chk_emitido)){
			$estados[] = 2;
		}
		if(!empty($chk_consumido)){
			$estados[] = 3;
		}
		
		if( !empty($chk_registrado) or !empty($chk_emitido) or !empty($chk_consumido)  ){
			$filters.=" AND vv.estado in (".implode(",",$estados).")";
			if(empty($chk_anulado))
				$filters.=" AND vv.anulado=0";
		}
		
		if(!empty($chk_anulado)){//anulado = 1
			$filters.=" AND vv.anulado=1";
		}
		
	}else if($opt==2){
		
		if(!empty($chk_placa)){
			$filters = " AND vv.equnr = '".$select_1."'";
		}else if(!empty($chk_chofer)){
			$filters = " AND vv.chofer = ".$select_2;
		}else if(!empty($chk_grifo)){
			$filters = " AND vv.grifo = ".$select_4;
		}else if(!empty($chk_observacion)){
			$filters = " AND vv.consumo_observacion like '%".$observacion."%'";
		}
		
	}

    $sql_centro_costo = "";
    $sql_rfc_response = "";
    if($descargar){
        $sql_centro_costo = "(select concat('; ' , vcw2.ktext, ': ', vda.asignacion,'%') from vales_detalle_asignacion vda inner join vales_centroweb vcw2 on vcw2.id = vda.kostl where vda.idvale = vv.id and vda.matnr = vdp.matnr FOR XML PATH(''))  as centrocosto,";
        $sql_rfc_response = "(select top 1 response from vales_rfc_logs vrfcl where vrfcl.idvale = vv.id order by fecha desc) as rfc_response,";
    }
	
	$sql = "SELECT distinct
				vv.id,
				(vv.fecha_registra) as registra_fecha,
				(vv.fecha_registra) as registra_hora,
				vv.fecha_max_consumo,
				uw.usuario as registra_usuario,
				(vv.fecha_modifica) as modifica_fecha,
				(vv.fecha_modifica) as modifica_hora,
				uw1.usuario as modifica_usuario,
				(vv.fecha_emite) as emite_fecha,
				(vv.fecha_emite) as emite_hora,
				uw2.usuario as emite_usuario,
				(vv.consumo_fechaconsumo) as consumo_fecha,
				(vv.consumo_fechaconsumo) as consumo_hora,
				uw3.name1 as consume_usuario,
				vv.consumo_observacion,
				ew.license_num as placa,
                ew.equnr as equipo_codigo,
				vv.kilom as consume_kilometraje,
				uw4.name1 as chofer,
				uw4.num_doc_identidad as chofer_dni,
				uw5.name1 as copiloto,
				uw5.num_doc_identidad as copiloto_dni,
				vg.nombre as grifo,				
				case when vv.consumo_unidadmedida = 0 then 'Kilometraje'
					 when vv.consumo_unidadmedida = 1 then 'Hódometro'
					 else '-'
				end as contador,
				case when vv.anulado = 1 then 'Anulado'
					 when vv.estado = 1 then 'Registrado'
					 when vv.estado = 2 then 'Emitido'
					 when vv.estado = 3 then 'Consumido'
					 else '-'
				end as estado,			   
				vm.nombre as material,
				vdp.menge_chofer as cantidad_consumida,
				vdp.voucher_nro as nrovoucher,
				".$sql_centro_costo."
				rfcconsumo_somethingwentwrong,
				tsomobile_somethingwentwrong,
				tsomobile_response,
				".$sql_rfc_response."
				case when vv.isflujoconsumidor = 1 then 'Flujo 1'
				     else 'Flujo 2'
				end as tipo_flujo
				FROM vales_vale vv
				left join admin uw on uw.id = vv.usuario_registra
				left join admin uw1 on uw1.id = vv.usuario_modifica
				left join admin uw2 on uw2.id = vv.usuario_emite
				left join vales_usuarioweb uw3 on uw3.id = vv.consumo_idusuario
				left join vales_equipoweb ew on ew.id = vv.equnr
				left join vales_usuarioweb uw4 on uw4.id = vv.chofer
				left join vales_usuarioweb uw5 on uw5.id = vv.chofer_aux
				left join vales_grifo vg on vg.id = vv.grifo				
				left join vales_detalle_productos vdp on vdp.idvale = vv.id
				left join vales_material vm on vm.id = vdp.matnr
			where vv.id IS NOT NULL ".$filters.
			" order by vv.id desc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			
			$add_row = true;
			if(!empty($chk_centrocosto)){
				if( strpos($row['centrocosto'],$select_3) === false){
					$add_row = false;
				}
			}
			
			if($descargar){
				$row['rfc_status'] 		  	= getRFC_status($row['rfcconsumo_somethingwentwrong']);
				$row['rfc_response'] 		= getRFC_response($row['rfc_response']);
				$row['tsomobile_status']  	= getTSOMobile_status($row['tsomobile_somethingwentwrong']);
				$row['tsomobil_response'] 	= getTSOMobile_response($row['tsomobile_response']);
			}

            if($add_row)
                $data[] = $row;
        	
    }
    sqlsrv_close($link);

    return $data;
}

function getRFC_response($response){
	if( empty($response) ) return "";
	
	$json = json_decode($response, true);

    if($json == null || !isset($json['ET_RETURN']) || !isset($json['ET_RETURN']['item']))
        return "";

    $output1 = $json['ET_RETURN']['item'];

	$success_response = false;
	$msg_error		  = "";
	foreach($output1 as $d){
		if( $d['CREAD0'] == "X" ){
			$success_response = true;
		}else{
			$msg_error.= " ".$d['MENSAJE_ERROR'];
		}
	}

	if( $success_response ){
		return "Consumo exitoso";
	}else{
		return "Error: ".$msg_error;
	}
}

function getTSOMobile_response($response){
	if( empty($response) ) return "";
	
	$json = json_decode($response, true);
	
	if( $json['status'] == "ok"){
		if( $json['response']['mileage'] == "0" ){
			return "Error: mileage is zero";
		}else{
			$mileage = $json['response']['mileage'];
			return 	"mileage : ".$mileage;
		}		
	}else{
		$message = $json['response']['message'];
		return "Error: ".$message;
	}
}

function getRFC_status($rfcconsumo_somethingwentwrong){
	if( $rfcconsumo_somethingwentwrong == 1 ){ 						
		return 'Error al enviar data';							 
	}else if( $rfcconsumo_somethingwentwrong == "0" ){ 
		return 'Exito al enviar data';						 
	}else{
		return 'Data aun no enviada';
	}
	
}

function getTSOMobile_status($tsomobile_somethingwentwrong){
	if( $tsomobile_somethingwentwrong == 1 ){ 						
		return 'Error al enviar data';							 
	}else if( !empty($tsomobile_somethingwentwrong) || $tsomobile_somethingwentwrong == "0" ){ 
		return 'Exito al enviar data';						 
	}else{
		return 'Data aun no enviada';
	}
}

function getAllPlacasToCombobox(){
	
	$sql = "select id, license_num as descripcion, equnr from vales_equipoweb order by license_num asc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}

function getAllChoferesToCombobox(){
	
	$sql = "select id, CONCAT(name1, ' (', num_doc_identidad, ')') as descripcion from vales_usuarioweb order by name1 asc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}

function getAllCentroCostoToCombobox(){
	
	$sql = "select id, CONCAT(ktext, ' (', kostl, ')') as descripcion , ktext as descripcion2 from vales_centroweb order by ktext asc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}

function getAllGrifosToCombobox(){
	
	$sql = "select id, nombre as descripcion from vales_grifo order by nombre";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}