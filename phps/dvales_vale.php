<?php

function getAllValesValeWeb($sql_table_elements = array()){

    $order = " order by vv.id desc";
    $limit = "";
    if( !empty($sql_table_elements) ){
        $order = $sql_table_elements["order"];
        $limit = $sql_table_elements["limit"];
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select  vv.id,
                    convert(varchar, vv.fecha_registro, 103) as fecha_registro,
                    convert(varchar, vv.fecha_max_consumo, 103) as fecha_max_consumo,
                    vv.placa, 
                    vv.usuario_registra, 
                    ad.usuario,
                    vv.anulado,
                    vv.estado,
                    vv.consumo_fechaconsumo
			from vales_vale vv
            left join admin ad on ad.id = vv.usuario_registra
            where vv.fecha_registro >  DATEADD(month, -3, GETDATE()) and isFlujoConsumidor = 0 ".
        $search_filter." ".$order." ".$limit;

    $link = conectarBD();
    $stmt = sqlsrv_query( $link, $sql );
    $data = array();

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $centro_costo = getCentroCostoOfValve($row['id'], $link);
        $row['centro_costo'] = $centro_costo;
        $row['usuario'] = extractUsername_vale($row['usuario']);
        $row['estado_html'] = convertToEstadoVale($row['anulado'], $row['estado']);
        $row['td_acciones'] = calculate_acciones_vistaValesPlanner($row['anulado'], $row['estado'], $row['id'], $row['consumo_fechaconsumo']);
        $data[] = $row;
    }

    return $data;

}

function getAllValesValeWeb_count($sql_table_elements = array()){

    if( !empty($sql_table_elements) ){
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select COUNT(vv.id) as cantidad	
			from vales_vale vv
            left join admin ad on ad.id = vv.usuario_registra
			where vv.fecha_registro >  DATEADD(month, -3, GETDATE()) and vv.isFlujoConsumidor = 0 ".
        $search_filter;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function convertToEstadoVale($anulado, $estado){
    $estado_desc = "";
    if( $anulado == 0 ){
        switch($estado){
            case 1:
                $estado_desc = "<span class='label label-success'>Registrado</span>";
                break;
            case 2:
                $estado_desc = "<span class='label label-info'>Emitido</span>";
                break;
            case 3:
                $estado_desc = "<span class='label label-warning'>Consumido</span>";
                break;
        }
    }else{
        $estado_desc = "<span class='label'>Anulado</span>";
    }

    return $estado_desc;
}

function isValeConsumidoMesVigente($consumo_fechaconsumo){

    $valeConsumidoMesVigente = false;
    if(!empty($consumo_fechaconsumo)){
        $mes_consumo = substr($consumo_fechaconsumo->format('Y-m-d'),5,2);
        $mes_actual  = strval(date('m'));
        if($mes_actual == $mes_consumo)
            $valeConsumidoMesVigente = true;
    }

    return $valeConsumidoMesVigente;
}

function calculate_acciones_vistaValesPlanner($anulado, $estado, $idvale, $consumo_fechaconsumo){

    $valeConsumidoMesVigente = isValeConsumidoMesVigente($consumo_fechaconsumo);


    if($anulado == 1 || ( $estado == 3 && $valeConsumidoMesVigente == false) ) {
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idvale.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else{
        return   '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idvale.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<button alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger" onclick="anular_go('.$idvale.')">'.
            '<i  class="icon-trash bigger-120"></i>'.
            '</button>'.
            '</div>'.
            '</td>';
    }
}

function extractUsername_vale($email){

    if(!empty($email)){
        $data__ = explode("@", $email);
        return $data__[0];
    }else{
        return "No user";
    }
}

function getCentroCostoOfValve($id, &$link){


    $asignaciones = getAllItemsVale($id, $link);
    $ccs = "";
    foreach($asignaciones as $asignacion){
        $ccs.= $asignacion['ktext']." (".$asignacion['kostl']."), ";
    }
    return substr($ccs, 0, -2);
}

function getAllItemsVale($idvale, &$link){

    $sql = "select cc.ktext, cc.kostl
	        from vales_detalle_asignacion vd
			inner join vales_centroweb cc on vd.kostl = cc.id
			where idvale =".$idvale;

    $data = queryBD($sql,$link);

    if(!empty($data))
        return $data;
    else
        return array();
}
