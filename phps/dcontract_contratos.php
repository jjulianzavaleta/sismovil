<?php

include_once("validaciones.php");

global $TIPO_USUARIO_COMPRADOR;
global $TIPO_USUARIO_NO_COMPRADOR;
$TIPO_USUARIO_COMPRADOR		= 	1;
$TIPO_USUARIO_NO_COMPRADOR	=	2;

global $TIPO_FLOW_USUARIO;
global $TIPO_FLOW_LEGAL;
global $TIPO_RESPONSABLE_AREA;
global $TIPO_RESPONSABLE_LOGISTICA;
$TIPO_FLOW_USUARIO		= 1;
$TIPO_FLOW_LEGAL 		= 2;
$TIPO_RESPONSABLE_AREA  = 3;
$TIPO_RESPONSABLE_LOGISTICA = 4;

global $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO;
global $ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_AREA;
global $ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_LOGISTICA;
global $ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL;
global $ESTADO_SOLCONTRACT_ELABORADO_LEGAL;
global $ESTADO_SOLCONTRACT_APROBADO_RESPONSABLE_AREA;
global $ESTADO_SOLCONTRACT_ESPERAR_FIRMAS;
global $ESTADO_SOLCONTRACT_VIGENTE;
$ESTADO_SOLCONTRACT_REGISTRADO_USUARIO	=	0;
$ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_AREA = 0.3;
$ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_LOGISTICA = 0.6;
$ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL = 0.8;
$ESTADO_SOLCONTRACT_ELABORADO_LEGAL		=	1;
$ESTADO_SOLCONTRACT_APROBADO_RESPONSABLE_AREA	=	2;
$ESTADO_SOLCONTRACT_ESPERAR_FIRMAS		=	3;
$ESTADO_SOLCONTRACT_VIGENTE				=	4;
$ESTADO_SOLCONTRACT_CONCLUIDO			=	5;


global $TITLE_USUARIO_CREA_SEC;
global $TITLE_USUARIO_ENVIA_SOLICITUD_CREACION;
global $TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION;
global $TITLE_RESPONSABLE_AREA_VALIDA_PRELIMINAR;
global $TITLE_LOGISTICA_VALIDA_PRELIMINAR;
global $TITLE_LEGAL_ELABORA_CONTRATO;
global $TITLE_ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL;
global $TITLE_RESPONSABLE_AREA_VALIDA_FINAL;
global $TITLE_LOGISTICA_VALIDA_FINAL;
global $TITLE_VALIDACION_FINAL;
global $TITLE_ESPERA_FIRMAS;
global $TITLE_LEGAL_SUBE_FIRMAS_PROVEEDOR;
global $TITLE_LEGAL_SUBE_FIRMAS_CHIMU;
global $TITLE_SEC_VIGENTE;
global $TITLE_SEC_CONCLUIDO;

$TITLE_USUARIO_CREA_SEC = "USUARIO REGISTRA SEC";
$TITLE_USUARIO_ENVIA_SOLICITUD_CREACION = "USUARIO ENVIA SOLICITUD PARA CREACIÓN DE CONTRATO";
$TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION = "USUARIO ENVIA SOLICITUD PARA VALIDACIÓN DE RESPONSABLE DE ÁREA";
$TITLE_RESPONSABLE_AREA_VALIDA_PRELIMINAR = "RESPONSABLE ÁREA REALIZA VALIDACIÓN PRELIMINAR DE SEC";
$TITLE_LOGISTICA_VALIDA_PRELIMINAR = "RESPONSABLE LOGÍSTICA REALIZA VALIDACIÓN PRELIMINAR DE SEC";
$TITLE_ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL = "LEGAL ACEPTA ELABORAR CONTRATO";
$TITLE_LEGAL_ELABORA_CONTRATO = "LEGAL ELABORA CONTRATO";
$TITLE_RESPONSABLE_AREA_VALIDA_FINAL = "RESPONSABLE DE ÁREA REALIZA VALIDACIÓN FINAL";
$TITLE_LOGISTICA_VALIDA_FINAL = "LOGÍSTICA REALIZA VALIDACIÓN FINAL";
$TITLE_VALIDACION_FINAL = "SE REALIZA VALIDACIÓN FINAL DE CONTRATO";
$TITLE_ESPERA_FIRMAS = "ESPERA DE FIRMAS";
$TITLE_LEGAL_SUBE_FIRMAS_PROVEEDOR = "SUBE FIRMAS DE PROVEEDOR";
$TITLE_LEGAL_SUBE_FIRMAS_CHIMU = "SUBE FIRMAS DE CHIMÚ";
$TITLE_SEC_VIGENTE = "CONTRATO VIGENTE";
$TITLE_SEC_CONCLUIDO = "CONTRATO CONCLUIDO";

function getAllMiContratos($idusuario, $role, $sql_table_elements = array()){

    $order = " order by cs.id desc";
    $limit = "";
    if( !empty($sql_table_elements) ){
        $order = $sql_table_elements["order"];
        $limit = $sql_table_elements["limit"];
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select 
                   cs.id as idcontrato,
                   ce.descripcion as nombre_empresa, 
				   cp.razon_social as nombre_proveedor,
				   tc.descripcion as nombre_tipocontrato,
				   cs.datosgenerales_fecharegistra as fecha_formateada,
                   cs.datosgenerales_codigo , 
                   cs.anulado,
                   cs.tipo_flujo,
                   cs.flag_has_last_approved_usuario,
                   cs.flag_has_last_approved_logistica,
                   cs.datosgenerales_estado,
                   cs.suspendido
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where cs.datosgenerales_usuarioregistra = ".$idusuario." "
        .$search_filter." ".$order." ".$limit;

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC )) {

			$isTipoFlujoComprador = $row['tipo_flujo'] == 1 ? true : false;
			$hasLastUserApproved  = $row['flag_has_last_approved_usuario'] == 1 ? true : false;
			$hasLastLogisticaApproved = $row['flag_has_last_approved_logistica'] == 1 ? true : false;
			$row['estado_html'] = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,true,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
			$row['estado']      = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,false,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
            $row['td_acciones'] = calculate_acciones_vistaMisContratos($row['anulado'], $row['estado'], $row['idcontrato']);
        	$data[] = $row;
    	   }
    sqlsrv_close($link);

    return $data;
}

function getAllMiContratos_count($idusuario, $role, $sql_table_elements){

    if( !empty($sql_table_elements) ){
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select COUNT(cs.id) as cantidad					   
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where cs.datosgenerales_usuarioregistra = ".$idusuario." "
        .$search_filter;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getAllContratosVistaApprove($role, $sql_table_elements = array()){

    $order = " order by cs.id desc";
    $limit = "";
    if( !empty($sql_table_elements) ){
        $order = $sql_table_elements["order"];
        $limit = $sql_table_elements["limit"];
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select cs.id as idcontrato,
	               cs.datosgenerales_codigo , 
                   cs.anulado,
                   cs.tipo_flujo,
                   cs.flag_has_last_approved_usuario,
                   cs.flag_has_last_approved_logistica,
                   cs.datosgenerales_estado,
	               ce.descripcion as nombre_empresa, 
				   cp.razon_social as nombre_proveedor,
				   tc.descripcion as nombre_tipocontrato,
				   datosgenerales_fecharegistra as fecha_formateada,
                   cs.suspendido
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where  (datosgenerales_estado > 0 and datosgenerales_estado < 4) 
			         AND 
			      (anulado <> 1 or anulado is null)
			          AND 
			       (
				   MONTH(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date)) = ".date("m")."
				     or 
					 (
					  MONTH(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date)) <> ".date("m")." and datosgenerales_estado IN (0.8,1,3) and (anulado <> 1 or anulado is null)
					 )
				   )
			  ".$search_filter." ".$order." ".$limit;

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

			$isTipoFlujoComprador = $row['tipo_flujo'] == 1 ? true : false;
			$hasLastUserApproved  = $row['flag_has_last_approved_usuario'] == 1 ? true : false;
			$hasLastLogisticaApproved = $row['flag_has_last_approved_logistica'] == 1 ? true : false;

			$row['estado_html'] =  convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,true,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
            $warning_icon = display_warning_icon_when_action_needed($row['estado_html']);
            if(empty($warning_icon))
                $warning_icon = display_warning_icon_when_action_needed($row['estado_html'],"pink");
            if(empty($warning_icon))
                $warning_icon = display_warning_icon_when_action_needed($row['estado_html'],"purple");
            $row['estado_html'] = $warning_icon.$row['estado_html'];

            $row['td_acciones'] = calculate_acciones_vistaLegal($row['idcontrato'], $row['anulado'], $row['datosgenerales_estado'], (isset($row['termiesp_c_fechainicio'])?$row['termiesp_c_fechainicio']:null),  (isset($row['$termiesp_c_incluyeacta'])?$row['$termiesp_c_incluyeacta']:null), $row['datosgenerales_codigo']);

			$row['estado']      = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,false,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
			$row['aprob_preliminar'] = getAprobacionPreliminarText($row['idcontrato'], $link);
			$row['aprob_final'] = getAprobacionFinalText($row['idcontrato'], $link);
        	$data[] = $row;
    	   }
    //sqlsrv_close($link);

    return $data;
}

function getAllContratosVistaApprove_count($sql_table_elements){

    if( !empty($sql_table_elements) ){
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select COUNT(cs.id) as cantidad	   
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where  (datosgenerales_estado > 0 and datosgenerales_estado < 4) 
			         AND 
			      (anulado <> 1 or anulado is null)
			          AND 
			       (
				   MONTH(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date)) = ".date("m")."
				     or 
					 (
					  MONTH(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date)) <> ".date("m")." and datosgenerales_estado IN (0.8,1,3) and (anulado <> 1 or anulado is null)
					 )
				   )
			 ".$search_filter;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getAllContratosToApproveVistaLogistica($role, $sql_table_elements = array()){

    $order = " order by cs.id desc";
    $limit = "";
    if( !empty($sql_table_elements) ){
        $order = $sql_table_elements["order"];
        $limit = $sql_table_elements["limit"];
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select cs.id as idcontrato,
	               cs.datosgenerales_codigo , 
                   cs.anulado,
                   cs.tipo_flujo,
                   cs.flag_has_last_approved_usuario,
                   cs.flag_has_last_approved_logistica,
                   cs.datosgenerales_estado,
	               ce.descripcion as nombre_empresa, 
				   cp.razon_social as nombre_proveedor,
				   tc.descripcion as nombre_tipocontrato,
                   cs.termiesp_c_fechainicio ,
                   cs.termiesp_c_incluyeacta,       
				   datosgenerales_fecharegistra as fecha_formateada,
                   cs.suspendido
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where  datosgenerales_estado = 0.6 or ( datosgenerales_estado = 2 and tipo_flujo = 1 )
			 ".$search_filter." ".$order." ".$limit;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $isTipoFlujoComprador = $row['tipo_flujo'] == 1 ? true : false;
        $hasLastUserApproved  = $row['flag_has_last_approved_usuario'] == 1 ? true : false;
        $hasLastLogisticaApproved = $row['flag_has_last_approved_logistica'] == 1 ? true : false;

        $row['estado_html'] = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,true,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);

        $warning_icon = display_warning_icon_when_action_needed($row['estado_html']);
        $row['estado_html'] = $warning_icon.$row['estado_html'];

        $row['estado']      = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,false,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
        $row['td_acciones'] = calculate_acciones_vistaLogistica($row['idcontrato'], $row['anulado'], $row['datosgenerales_estado'], $row['termiesp_c_fechainicio'],  $row['termiesp_c_incluyeacta'], $row['datosgenerales_codigo']);
        $data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getAllContratosToApproveVistaLogistica_count($sql_table_elements){

    if( !empty($sql_table_elements) ){
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select COUNT(cs.id) as cantidad			   
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where  datosgenerales_estado = 0.6 or ( datosgenerales_estado = 2 and tipo_flujo = 1 )
			 ".$search_filter;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getAllContratosToApproveVistaMiArea($role, $idarea, $sql_table_elements = array()){

    $order = " order by cs.id desc";
    $limit = "";
    if( !empty($sql_table_elements) ){
        $order = $sql_table_elements["order"];
        $limit = $sql_table_elements["limit"];
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select cs.id as idcontrato,
	               cs.datosgenerales_codigo , 
                   cs.anulado,
                   cs.tipo_flujo,
                   cs.flag_has_last_approved_usuario,
                   cs.flag_has_last_approved_logistica,
                   cs.datosgenerales_estado,
	               ce.descripcion as nombre_empresa, 
				   cp.razon_social as nombre_proveedor,
				   tc.descripcion as nombre_tipocontrato,
				   datosgenerales_fecharegistra as fecha_formateada,
                   cs.suspendido
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where  (datosgenerales_estado > 0) 
					  AND
					reqgen_a_areausuaria = ".$idarea."
			 ".$search_filter." ".$order." ".$limit;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

        $row['termiesp_c_fechainicio'] = !isset($row['termiesp_c_fechainicio'])?null:$row['termiesp_c_fechainicio'];
        $row['$termiesp_c_incluyeacta'] = !isset($row['$termiesp_c_incluyeacta'])?null:$row['$termiesp_c_incluyeacta'];

        $isTipoFlujoComprador = $row['tipo_flujo'] == 1 ? true : false;
        $hasLastUserApproved  = $row['flag_has_last_approved_usuario'] == 1 ? true : false;
        $hasLastLogisticaApproved = $row['flag_has_last_approved_logistica'] == 1 ? true : false;

        $row['estado_html'] = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,true,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
        $warning_icon = display_warning_icon_when_action_needed($row['estado_html']);
        $row['estado_html'] = $warning_icon.$row['estado_html'];

        $row['estado']      = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,false,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
        $row['td_acciones'] = calculate_acciones_vistaMiArea($row['idcontrato'], $row['anulado'], $row['datosgenerales_estado'], $row['termiesp_c_fechainicio'],  $row['$termiesp_c_incluyeacta'], $row['datosgenerales_codigo']);
        $data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getAllContratosToApproveVistaMiArea_count($idarea, $sql_table_elements){

    if( !empty($sql_table_elements) ){
        $search_filter = str_replace("WHERE", "AND", $sql_table_elements["where"]);
    }

    $sql = "select COUNT(cs.id) as cantidad			   
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where  (datosgenerales_estado > 0) 
					  AND
					reqgen_a_areausuaria = ".$idarea."
			 ".$search_filter;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function calculate_acciones_vistaMiArea($idcontrato, $anulado, $datosgenerales_estado, $termiesp_c_fechainicio, $termiesp_c_incluyeacta, $codigo){
    if($anulado == 1 || $datosgenerales_estado == 5){//Anulado o Concluido
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Ver" title="Ver" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else if($datosgenerales_estado == 4 && empty($termiesp_c_fechainicio) && $termiesp_c_incluyeacta == 1){//Vigente
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '<button type="button" data-toggle="modal" href="#nueva_actividad" alt="Acta terreno" title="Acta terreno" class="btn btn-mini btn-warning" onclick="acta_terreno('.$idcontrato.',&#39'.$codigo.'&#39)">'.
            '<i  class="icon-suitcase bigger-120"></i>'.
            '</button>'.
            '</div>'.
            '</td>';
    }else if($datosgenerales_estado == 4){//Vigente
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else if($datosgenerales_estado < 4){
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" class="btn btn-mini btn-info" href="#" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '<button type="button" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger" onclick="anular_go('.$idcontrato.')">'.
            '<i  class="icon-trash bigger-120"></i>'.
            '</button>'.
            '</div>'.
            '</td>';

    }else{
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" class="btn btn-mini btn-success" href="#" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }
}

function calculate_acciones_vistaLogistica($idcontrato, $anulado, $datosgenerales_estado, $termiesp_c_fechainicio, $termiesp_c_incluyeacta, $codigo){

    if($anulado == 1 || $datosgenerales_estado == 5){//Anulado o Concluido
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Ver" title="Ver" data-toggle="modal" class="btn btn-mini btn-info" href="#editar"  onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else if( $datosgenerales_estado == 4 && empty($termiesp_c_fechainicio) && $termiesp_c_incluyeacta == 1){//Emitido
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar"  onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '<button type="button" data-toggle="modal" href="#nueva_actividad" alt="Acta terreno" title="Acta terreno" class="btn btn-mini btn-warning"  onclick="acta_terreno('.$idcontrato.',&#39'.$codigo.'&#39)">'.
            '<i  class="icon-suitcase bigger-120"></i>'.
            '</button>'.
            '</div>'.
            '</td>';
    }else if($datosgenerales_estado == 4){//Vigente
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else if( $datosgenerales_estado < 4){
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" class="btn btn-mini btn-info" href="#"  onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '<button type="button" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"  onclick="anular_go('.$idcontrato.')">'.
            '<i  class="icon-trash bigger-120"></i>'.
            '</button>'.
            '</div>'.
            '</td>';

    }else{
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" class="btn btn-mini btn-success" href="#"  onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }
}

function calculate_acciones_vistaMisContratos($anulado, $estado, $idcontrato){
    if($anulado == 1 || $estado == 5){//Anulado o Concluido
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else{
       return '<td class="td-actions">'.
        '<div class="btn-group">'.
        '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar"  onclick="edit_go('.$idcontrato.')">'.
        '<i  class="icon-edit bigger-120"></i>'.
        '</a>'.
        '<a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
        '<i  class="icon-pause bigger-120"></i>'.
        '</a>'.
        '<button alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger" onclick="anular_go('.$idcontrato.')">'.
        '<i  class="icon-trash bigger-120"></i>'.
        '</button>'.
        '</div>'.
        '</td>';
    }
}

function calculate_acciones_vistaLegal($idcontrato, $anulado, $datosgenerales_estado, $termiesp_c_fechainicio, $termiesp_c_incluyeacta, $codigo){

    if($anulado == 1 || $datosgenerales_estado == 5){//Anulado o Concluido
        return '<td class="td-actions">
            <div class="btn-group">
            <a  alt="Ver" title="Ver" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">
            <i  class="icon-edit bigger-120"></i>
            </a>
            </div>
            </td>';
    }else if($datosgenerales_estado == 4 && empty($termiesp_c_fechainicio) && $termiesp_c_incluyeacta == 1){//Vigente
        return '<td class="td-actions">
            <div class="btn-group">
            <a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">
            <i  class="icon-edit bigger-120"></i>
            </a>
            <button type="button" data-toggle="modal" href="#nueva_actividad" alt="Acta terreno" title="Acta terreno" class="btn btn-mini btn-warning" onclick="acta_terreno('.$idcontrato.',&#39'.$codigo.'&#39)">
            <i  class="icon-suitcase bigger-120"></i>
            </button>
            </div>
            </td>';
    }else if($datosgenerales_estado == 4){//Vigente
        return '<td class="td-actions">'.
            '<div class="btn-group">'.
            '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar" onclick="edit_go('.$idcontrato.')">'.
            '<i  class="icon-edit bigger-120"></i>'.
            '</a>'.
            '<a  alt="Suspender" title="Suspender" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">'.
            '<i  class="icon-pause bigger-120"></i>'.
            '</a>'.
            '</div>'.
            '</td>';
    }else if($datosgenerales_estado == 1){
        return '<td class="td-actions">
            <div class="btn-group">
            <a  alt="Editar" title="Editar" class="btn btn-mini btn-info" href="#" onclick="edit_go('.$idcontrato.')">
            <i  class="icon-edit bigger-120"></i>
            </a>
            <a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">
            <i  class="icon-pause bigger-120"></i>
            </a>
            <button type="button" alt="Derivar" title="Derivar" class="btn btn-mini btn-success"  onclick="derivar_go('.$idcontrato.')">
            <i  class="icon-random bigger-120"></i>
            </button>
            <button type="button" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"  onclick="anular_go('.$idcontrato.')">
            <i  class="icon-trash bigger-120"></i>
            </button>
            </div>
            </td>';
    }else if($datosgenerales_estado < 4){
        return '<td class="td-actions">
            <div class="btn-group">
            <a  alt="Editar" title="Editar" class="btn btn-mini btn-info" href="#" onclick="edit_go('.$idcontrato.')">
            <i  class="icon-edit bigger-120"></i>
            </a>
            <a  alt="Suspender/Activar" title="Suspender/Activar" data-toggle="modal" class="btn btn-mini btn-warning" href="#suspender" onclick="suspend_go('.$idcontrato.')">
            <i  class="icon-pause bigger-120"></i>
            </a>
            <button type="button" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"  onclick="anular_go('.$idcontrato.')">
            <i  class="icon-trash bigger-120"></i>
            </button>
            </div>
            </td>';
    }else{
        return '<td class="td-actions">
            <div class="btn-group">
            <a  alt="Editar" title="Editar" class="btn btn-mini btn-success" href="#" onclick="edit_go('.$idcontrato.')">
            <i  class="icon-edit bigger-120"></i>
            </a>
            </div>
            </td>';
    }
}

function registrarContrato($data){
	
    $sql = "INSERT INTO contract_solcontrato
           (tipo_flujo,
		    reqgen_a_empresa
           ,reqgen_a_areasolicitante
           ,reqgen_a_areasolicitante_jefatura
           ,reqgen_a_compradorresponsable
           ,reqgen_a_areausuaria
           ,reqgen_a_areausuaria_jefatura
           ,reqgen_proveedor
           ,reqgen_proveedor_ruc
           ,termiesp_a_tipocontrato
           ,termiesp_a_nrocotizacion
		   ,cotizacion_bynrocontrato
           ,termiesp_a_fecha
           ,termiesp_b_alcance
           ,termiesp_c_dias
		   ,termiesp_c_formato
           ,termiesp_c_medida
           ,termiesp_c_fechainicio
           ,termiesp_c_fechafin
           ,termiesp_c_incluyeacta
           ,termiesp_d_monto
           ,termiesp_d_moneda
		   ,formapago_medida
		   ,modalidadpago_cartafianza_medida
		   ,modalidadpago_adelanto_medida
		   ,modalidadpago_fcumplimiento_medida
		   ,modalidadpago_fgarantia_medida
		   ,monto_mobiliario_medida
		   ,penalidades_medida
           ,termiesp_e_formapago
           ,termiesp_e_avancez_medida
           ,termiesp_e_credito_dias
           ,termiesp_f_modalidadpago
           ,termiesp_g_garantia
           ,termiesp_g_adelanto_importe
           ,termiesp_g_fcumplimiento_importe
           ,termiesp_g_fondogarantia_importe
           ,termiesp_h_lugarentrega
           ,termiesp_i_observacionesamplicaciones
           ,reqesp_ruta
           ,autorizac_a_nombres
           ,autorizac_a_cargo
           ,autorizac_a_fecha           
           ,datosgenerales_fecharegistra
           ,datosgenerales_usuarioregistra           
           ,datosgenerales_estado,           
           jur_file_ficharuc,
           jur_file_represetante,
           jur_file_vigenciapoder,
           nat_file_ficharuc,
           nat_file_represetante,
           proveedor_tipo,
		   anulado,
		   datosgenerales_codigo,
		   tipo_renovacion,
		   contrato_vinculado,
		   tipocontrato_otrosdesc,
		   modalidadpago_otro,
           modalidadpago_transcuenta_desc,
		   modalidadpago_cartafianza_importe,
		   modalidadpago_adelanto_exception,
		   modalidadpago_adelanto_adelantofile,
		   lugar_entrega_personal_tercero,
		   lugar_entrega_personal_tercero_numero,
		   lugar_entrega_personal_tercero_dias,
		   lugar_entrega_personal_tercero_equipo,
		   metas_cumplir_comentario,
		   metas_cumplir_entregables,		  
		   monto_mobiliario,
		   contraprestacion_incdocumento,
		   contraprestacion_file,
		   contrato_propuesto_proveedor)
     VALUES          
            (".$data['tipo_flujo_contrato']."
		   ,".$data['reqgen_a_empresa']."
           ,".$data['reqgen_a_areasolicitante']."
           ,".$data['reqgen_a_areasolicitante_jefatura']."
           , ".$data['reqgen_a_compradorresponsable']."
           , ".$data['reqgen_a_areausuaria']."
           ,".$data['reqgen_a_areausuaria_jefatura']."
           , ".$data['reqgen_proveedor']."
           ,".$data['reqgen_proveedor_ruc']."
           , ".$data['termiesp_a_tipocontrato']."
           ,".$data['termiesp_a_nrocotizacion']."
		   ,".$data['cotizacion_bynrocontrato']."
           ,".$data['termiesp_a_fecha']."
           ,".$data['termiesp_b_alcance']."
           ,".$data['termiesp_c_dias']."
		   ,".$data['termiesp_c_formato']."
           , ".$data['termiesp_c_medida']."
           ,".$data['termiesp_c_fechainicio']."
           ,".$data['termiesp_c_fechafin']."
           , ".$data['termiesp_c_incluyeacta']."
           , ".$data['termiesp_d_monto']."
           , ".$data['termiesp_d_moneda']."
		   , ".$data['formapago_medida']."
		   , ".$data['modalidadpago_cartafianza_medida']."
		   , ".$data['modalidadpago_adelanto_medida']."
		   , ".$data['modalidadpago_fcumplimiento_medida']."
		   , ".$data['modalidadpago_fgarantia_medida']."
		   , ".$data['monto_mobiliario_medida']."
		   , ".$data['penalidades_medida']."
           , ".$data['termiesp_e_formapago']."
           , ".$data['termiesp_e_avancez_medida']."
           , ".$data['termiesp_e_credito_dias']."
           , ".$data['termiesp_f_modalidadpago']."
           , ".$data['termiesp_g_garantia']."
           , ".$data['termiesp_g_adelanto_importe']."
           , ".$data['termiesp_g_fcumplimiento_importe']."
           , ".$data['termiesp_g_fondogarantia_importe']."
           ,".$data['termiesp_h_lugarentrega']."
           ,".$data['termiesp_i_observacionesamplicaciones']."
           ,".$data['reqesp_ruta']."
           ,".$data['autorizac_a_nombres']."
           ,".$data['autorizac_a_cargo']."
           ,".$data['autorizac_a_fecha']."           
           ,CONVERT(VARCHAR,GETDATE(),121)
           , ".$data['datosgenerales_usuarioregistra']."
           , ".$data['datosgenerales_estado']."           
           , '".(!empty($_FILES['proveedor_jur_file_ficharuc']['name'])?$_FILES['proveedor_jur_file_ficharuc']['name']:"")."'
           , '".(!empty($_FILES['proveedor_jur_file_represetante']['name'])?$_FILES['proveedor_jur_file_represetante']['name']:"")."'
           , '".(!empty($_FILES['proveedor_jur_file_vigenciapoder']['name'])?$_FILES['proveedor_jur_file_vigenciapoder']['name']:"")."'
           , '".(!empty($_FILES['proveedor_nat_file_ficharuc']['name'])?$_FILES['proveedor_nat_file_ficharuc']['name']:"")."'
           , '".(!empty($_FILES['proveedor_nat_file_represetante']['name'])?$_FILES['proveedor_nat_file_represetante']['name']:"")."'
           ,".$data['proveedor_tipo']."
		   ,0
		   ,'".$data['codigo_contrato']."'
		   ,".$data['tipo_renovacion']."
		   ,".$data['contrato_vinculado']."
		   ,".$data['tipocontrato_otrosdesc']."
		   ,".$data['modalidadpago_otro']."
		   ,".$data['modalidadpago_transcuenta_desc']."
		   ,".$data['modalidadpago_cartafianza_importe']."
		   ,".$data['modalidadpago_adelanto_exception']."
		   , '".(!empty($_FILES['modalidadpago_adelanto_adelantofile']['name'])?$_FILES['modalidadpago_adelanto_adelantofile']['name']:"")."'
		   ,".$data['lugar_entrega_personal_tercero']."
		   ,".$data['lugar_entrega_personal_tercero_numero']."
		   ,".$data['lugar_entrega_personal_tercero_dias']."
		   ,".$data['lugar_entrega_personal_tercero_equipo']."
		   ,".$data['metas_cumplir_comentario']."
		   , '".(!empty($_FILES['metas_cumplir_entregables']['name'])?$_FILES['metas_cumplir_entregables']['name']:"")."'
		   ,".$data['monto_mobiliario']."
		   ,".$data['contraprestacion_incdocumento']."
		   , '".(!empty($_FILES['contraprestacion_file']['name'])?$_FILES['contraprestacion_file']['name']:"")."'
		   , '".(!empty($_FILES['contrato_propuesto_proveedor']['name'])?$_FILES['contrato_propuesto_proveedor']['name']:"")."');";
	
	$observacion_creacion = "Por favor crear contrato en base al formato adjunto";

    $link = conectarBD();

	startTransaction($link);
	
    $res = sqlsrv_query( $link, $sql);

    if($res === false){		
		return finishTransaction($link, false);
	}else{
		
		$lastId = sqlsrv_fetch_array(sqlsrv_query( $link, "SELECT SCOPE_IDENTITY();"), SQLSRV_FETCH_NUMERIC)[0];

		if(!is_numeric($lastId)){
            return finishTransaction($link, false);
        }

		$sql = "";
		if( isset($_FILES["archivos_inmuebles_tp"]["error"]) ){
			foreach( $_FILES["archivos_inmuebles_tp"]["error"] as $key => $error ){
				$file_name_inmueble = $_FILES["archivos_inmuebles_tp"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name_inmueble."',1),";		
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_inmuebles_archivos(idcontrato,url,tipo) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		$sql = "";
		if( isset($_FILES["archivos_inmuebles_cg"]["error"]) ){
			foreach( $_FILES["archivos_inmuebles_cg"]["error"] as $key => $error ){
				$file_name_inmueble = $_FILES["archivos_inmuebles_cg"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name_inmueble."',2),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_inmuebles_archivos(idcontrato,url,tipo) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		$sql = "";
		if( isset($_FILES["archivos_sct2"]["error"]) ){
			foreach( $_FILES["archivos_sct2"]["error"] as $key => $error ){
				$file_name_inmueble = $_FILES["archivos_sct2"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name_inmueble."'),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_inmuebles_partregistral(idcontrato,url) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		$sql = "";
		if( isset($_FILES["archivos_OA1"]["error"]) ){
			foreach( $_FILES["archivos_OA1"]["error"] as $key => $error ){
				$file_name = $_FILES["archivos_OA1"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name."'),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_observaciones_ampliaciones(idcontrato,url) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		
		if( strlen($data['penalidades']) > 2  ){
			$sql = "";
			$penalidades = objectToArray(json_decode($data['penalidades']));
			foreach( $penalidades as $penalidad ){			
				$sql.= "(".$lastId.",'".$penalidad['supuesto']."','".$penalidad['sancion']."'),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_penalidades(idcontrato,supuesto,sancion_economica) values ".$sql;
				
			$res = sqlsrv_query( $link, $sql);
					
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		
		if( $data['termiesp_e_formapago'] == 1 ){//FORMA DE PAGO EN PARTES			

				$detalle_en_partes = objectToArray(json_decode($data['forma_pago_en_partes_detalle']));			
				
				$sql = "";
				foreach($detalle_en_partes as $detalle){
						$sql.= "(".$lastId.",".$detalle['porcentaje'].",".$detalle['importe']."),";
				}
				$sql = substr($sql, 0, -1);
				$sql = "INSERT INTO contract_formapao_parntes_detalle(idsolcontracto,porcentaje,importte) VALUES ".$sql;
				
				$res = sqlsrv_query( $link, $sql);
				
				if($res === false){
					return finishTransaction($link, false);
				}
		}
			
		global $TIPO_FLOW_USUARIO;
		global $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO;
				
		$res = reg_contacmovimiento($link,$lastId,$data['datosgenerales_usuarioregistra'],$observacion_creacion,$ESTADO_SOLCONTRACT_REGISTRADO_USUARIO,$TIPO_FLOW_USUARIO,1);
		$res = finishTransaction($link, $res);
				
		if($res)
		    return $lastId;
		else
		    return false;

	}
}

function reg_contacmovimiento(&$link,$idcontrato,$idusuario,$observacion,$estado,$tipo_flow,$cerrado,$archivos=array(),$title="",$waitFirmaModo=0){
	
	global $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO;
	global $ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_AREA;
	global $ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_LOGISTICA;
	global $ESTADO_SOLCONTRACT_ELABORADO_LEGAL;
    global $ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL;
	global $ESTADO_SOLCONTRACT_APROBADO_RESPONSABLE_AREA;
	global $ESTADO_SOLCONTRACT_ESPERAR_FIRMAS;
	global $ESTADO_SOLCONTRACT_VIGENTE;
	global $ESTADO_SOLCONTRACT_CONCLUIDO;
	global $TITLE_USUARIO_CREA_SEC;
	global $TITLE_USUARIO_ENVIA_SOLICITUD_CREACION;
	global $TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION;
	global $TITLE_RESPONSABLE_AREA_VALIDA_PRELIMINAR;
	global $TITLE_LOGISTICA_VALIDA_PRELIMINAR;
    global $TITLE_ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL;
	global $TITLE_LEGAL_ELABORA_CONTRATO;
	global $TITLE_RESPONSABLE_AREA_VALIDA_FINAL;
	global $TITLE_LOGISTICA_VALIDA_FINAL;
	global $TITLE_VALIDACION_FINAL;
	global $TITLE_ESPERA_FIRMAS;
	global $TITLE_LEGAL_SUBE_FIRMAS_PROVEEDOR;
	global $TITLE_LEGAL_SUBE_FIRMAS_CHIMU;
	global $TITLE_SEC_VIGENTE;
	global $TITLE_SEC_CONCLUIDO;

	if( empty($title) ){
		switch($estado){
			case $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO:
				$title = $TITLE_USUARIO_CREA_SEC;
				break;
			case $ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_AREA:
				$title = $TITLE_RESPONSABLE_AREA_VALIDA_PRELIMINAR;
				break;
			case $ESTADO_SOLCONTRACT_APROBAR_RESPONSABLE_LOGISTICA:
				$title = $TITLE_LOGISTICA_VALIDA_PRELIMINAR;
				break;
            case $ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL:
                $title = $TITLE_ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL;
                break;
			case $ESTADO_SOLCONTRACT_ELABORADO_LEGAL:
				$title = $TITLE_LEGAL_ELABORA_CONTRATO;
				break;
			case $ESTADO_SOLCONTRACT_APROBADO_RESPONSABLE_AREA:
				$title = $TITLE_VALIDACION_FINAL;
				break;
			case $ESTADO_SOLCONTRACT_ESPERAR_FIRMAS:
				if($waitFirmaModo==1){
					$title = $TITLE_LEGAL_SUBE_FIRMAS_PROVEEDOR;
				}else if($waitFirmaModo==2){
					$title = $TITLE_LEGAL_SUBE_FIRMAS_CHIMU;
				}else{
					$title = $TITLE_ESPERA_FIRMAS;
				}				
				break;
			case $ESTADO_SOLCONTRACT_VIGENTE:
				$title = $TITLE_SEC_VIGENTE;
				break;
			case $ESTADO_SOLCONTRACT_CONCLUIDO;
			    $title = $TITLE_SEC_CONCLUIDO;
				break;
		}
	}
	
	$sql = "INSERT INTO contract_movimiento
				   (idcontrato
				   ,idusuario
				   ,fecha_registra
				   ,observacion
				   ,title
				   ,tipo_flow
				   ,estado
				   ,cerrado)
			VALUES
				   (".$idcontrato."
				   ,".$idusuario."
				   ,GETDATE()
				   ,'".$observacion."'
				   ,'".$title."'
				   ,".$tipo_flow."
				   ,".$estado."
				   ,".$cerrado.")
				";
	
    $res = sqlsrv_query( $link, $sql);

    if($res === false){
        return false;
	}else{
		
		if(!empty($archivos)){			
            $lastId = sqlsrv_fetch_array(sqlsrv_query( $link, "SELECT SCOPE_IDENTITY();"), SQLSRV_FETCH_NUMERIC)[0];
			
			$values = "";
			foreach($archivos as $archivo){
				$values = $values."(".$lastId.",'".$archivo."'),";
			}
			$values = substr($values, 0, -1);
			
			$sql = "INSERT INTO contract_mov_archivo(idmovimiento,url) VALUES ".$values;
			$res = sqlsrv_query( $link, $sql);

            return $res;
		}else{
            return true;
		}
	}	
}

function getContratoData($idcontrato){

    $sql = "select * from contract_solcontrato where id = ".$idcontrato;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);
	
	if(empty($data)){
		return array();
	}else{
		return $data[0];
	}    
}

function getContratoDataForReport($idcontrato){

    $sql = "SELECT cs.*,
	               cemp.descripcion as data_empresa,
				   cuh.usuario as data_compradorresponsable,
				   carea.descripcion as data_areasuaria,
				   cprov.razon_social as data_razon_social,
				   ctipoc.descripcion as data_tipocontrato,
				   cvigencia.descripcion as data_vigenciaformato,
				   cvtipomodena.descripcion as data_tipomodena,
				   cvmodalidadpago.descripcion as data_modalidadpago,
				   cvgarantia.descripcion as data_garantia,
				   cvformapago.descripcion as data_formapago,
				   cvavance.descripcion as data_poravances,
				   cvcredito.descripcion as data_creditodias
			FROM contract_solcontrato cs
			INNER JOIN contract_empresa cemp on cemp.id = cs.reqgen_a_empresa
			LEFT JOIN contract_usuarioshabilitados cuh on cuh.id = cs.reqgen_a_compradorresponsable
			INNER JOIN contract_area carea on carea.id = cs.reqgen_a_areausuaria
			INNER JOIN contract_proveedor cprov on cprov.idproveedor = cs.reqgen_proveedor
			LEFT JOIN contract_tipocontrato ctipoc on ctipoc.id = cs.termiesp_a_tipocontrato
			LEFT JOIN contract_vigenciaformato cvigencia on cvigencia.id = cs.termiesp_c_medida
			LEFT JOIN contract_tipomoneda cvtipomodena on cvtipomodena.id = cs.termiesp_d_moneda
			LEFT JOIN contract_modalidadpago cvmodalidadpago on cvmodalidadpago.id = cs.termiesp_f_modalidadpago
			LEFT JOIN contract_garantia cvgarantia on cvgarantia.id = cs.termiesp_g_garantia
			LEFT JOIN contract_formapago cvformapago on cvformapago.id = cs.termiesp_e_formapago
			LEFT JOIN contract_avance cvavance on cvavance.id = cs.termiesp_e_avancez_medida
			LEFT JOIN contract_credito cvcredito on cvcredito.id = cs.termiesp_e_credito_dias
			WHERE cs.id = ".$idcontrato;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);
	
	if(empty($data)){
		return array();
	}else{
		return $data[0];
	}    
}

function updateContrato($data){
	
	if($data['proveedor_tipo'] == 1){
		$sql_file_4 = " , nat_file_ficharuc = NULL ";
		$sql_file_5 = " , nat_file_represetante = NULL ";
		$sql_file_1 = "";
		$sql_file_2 = "";
		$sql_file_3 = "";
		
		if(isset($_FILES['proveedor_jur_file_ficharuc'])){
			$sql_file_1 = " , jur_file_ficharuc = '".$_FILES['proveedor_jur_file_ficharuc']['name']."' ";
		}
	
		if(isset($_FILES['proveedor_jur_file_represetante'])){
			$sql_file_2 = " , jur_file_represetante = '".$_FILES['proveedor_jur_file_represetante']['name']."' ";
		}
		
		if(isset($_FILES['proveedor_jur_file_vigenciapoder'])){
			$sql_file_3 = " , jur_file_vigenciapoder = '".$_FILES['proveedor_jur_file_vigenciapoder']['name']."' ";
		}
	}else if($data['proveedor_tipo'] == 2){
		$sql_file_1 = " , jur_file_ficharuc = NULL ";
		$sql_file_2 = " , jur_file_represetante = NULL ";
		$sql_file_3 = " , jur_file_vigenciapoder = NULL ";
		$sql_file_4 = "";
		$sql_file_5 = "";
		
		if(isset($_FILES['proveedor_nat_file_ficharuc'])){
		$sql_file_4 = " , nat_file_ficharuc = '".$_FILES['proveedor_nat_file_ficharuc']['name']."' ";
		}
		
		if(isset($_FILES['proveedor_nat_file_represetante'])){
			$sql_file_5 = " , nat_file_represetante = '".$_FILES['proveedor_nat_file_represetante']['name']."' ";
		}
	}	
	
	$sql_file_6 = "";
	if(isset($_FILES['contrato_propuesto_proveedor'])){
		$sql_file_6 = " , contrato_propuesto_proveedor = '".$_FILES['contrato_propuesto_proveedor']['name']."' ";
	}
	
	$sql_file_7 = "";
	if(isset($_FILES['modalidadpago_adelanto_adelantofile'])){
		$sql_file_7 = " , modalidadpago_adelanto_adelantofile = '".$_FILES['modalidadpago_adelanto_adelantofile']['name']."' ";
	}
	
	$sql_file_8 = "";
	if(isset($_FILES['metas_cumplir_entregables'])){
		$sql_file_8 = " , metas_cumplir_entregables = '".$_FILES['metas_cumplir_entregables']['name']."' ";
	}	
	
	$sql_file_9 = "";
	if(isset($_FILES['contraprestacion_file'])){
		$sql_file_9 = " , contraprestacion_file = '".$_FILES['contraprestacion_file']['name']."' ";
	}
	
    $sql = "UPDATE contract_solcontrato
		    SET reqgen_a_areasolicitante = ".$data['reqgen_a_areasolicitante']."
			   ,reqgen_a_areasolicitante_jefatura = ".$data['reqgen_a_areasolicitante_jefatura']."
			   ,reqgen_a_compradorresponsable = ".$data['reqgen_a_compradorresponsable']."
			   ,reqgen_a_areausuaria = ".$data['reqgen_a_areausuaria']."
			   ,reqgen_a_areausuaria_jefatura = ".$data['reqgen_a_areausuaria_jefatura']."
			   ,reqgen_proveedor = ".$data['reqgen_proveedor']."
			   ,reqgen_proveedor_ruc = ".$data['reqgen_proveedor_ruc']."
			   ,termiesp_a_tipocontrato = ".$data['termiesp_a_tipocontrato']."
			   ,termiesp_a_nrocotizacion = ".$data['termiesp_a_nrocotizacion']."
			   ,cotizacion_bynrocontrato = ".$data['cotizacion_bynrocontrato']."
			   ,termiesp_a_fecha = ".$data['termiesp_a_fecha']."
			   ,termiesp_b_alcance = ".$data['termiesp_b_alcance']."
			   ,termiesp_c_dias = ".$data['termiesp_c_dias']."
			   ,termiesp_c_formato = ".$data['termiesp_c_formato']."
			   ,termiesp_c_medida = ".$data['termiesp_c_medida']."
			   ,termiesp_c_fechainicio = ".$data['termiesp_c_fechainicio']."
			   ,termiesp_c_fechafin = ".$data['termiesp_c_fechafin']."
			   ,termiesp_c_incluyeacta = ".$data['termiesp_c_incluyeacta']."
			   ,termiesp_d_monto = ".$data['termiesp_d_monto']."
			   ,termiesp_d_moneda = ".$data['termiesp_d_moneda']."
			   ,formapago_medida = ".$data['formapago_medida']."
			   ,modalidadpago_cartafianza_medida = ".$data['modalidadpago_cartafianza_medida']."
			   ,modalidadpago_adelanto_medida = ".$data['modalidadpago_adelanto_medida']."
			   ,modalidadpago_fcumplimiento_medida = ".$data['modalidadpago_fcumplimiento_medida']."
			   ,modalidadpago_fgarantia_medida = ".$data['modalidadpago_fgarantia_medida']."
			   ,monto_mobiliario_medida = ".$data['monto_mobiliario_medida']."
			   ,penalidades_medida = ".$data['penalidades_medida']."
			   ,termiesp_e_formapago = ".$data['termiesp_e_formapago']."
			   ,termiesp_e_avancez_medida = ".$data['termiesp_e_avancez_medida']."
			   ,termiesp_e_credito_dias = ".$data['termiesp_e_credito_dias']."
			   ,termiesp_f_modalidadpago = ".$data['termiesp_f_modalidadpago']."
			   ,termiesp_g_garantia = ".$data['termiesp_g_garantia']."
			   ,termiesp_g_adelanto_importe = ".$data['termiesp_g_adelanto_importe']."
			   ,termiesp_g_fcumplimiento_importe = ".$data['termiesp_g_fcumplimiento_importe']."
			   ,termiesp_g_fondogarantia_importe = ".$data['termiesp_g_fondogarantia_importe']."
			   ,termiesp_h_lugarentrega = ".$data['termiesp_h_lugarentrega']."
			   ,termiesp_i_observacionesamplicaciones = ".$data['termiesp_i_observacionesamplicaciones']."
			   ,reqesp_ruta = ".$data['reqesp_ruta']."
			   ,autorizac_a_nombres = ".$data['autorizac_a_nombres']."
			   ,autorizac_a_cargo = ".$data['autorizac_a_cargo']."
			   ,autorizac_a_fecha = ".$data['autorizac_a_fecha']."           
			   ,datosgenerales_usuarioactualiza = ".$data['datosgenerales_usuarioregistra']."
			   ,datosgenerales_fechaactualiza = CONVERT(VARCHAR,GETDATE(),121)    
			   ,datosgenerales_estado = ".$data['datosgenerales_estado']."
			   ,tipocontrato_otrosdesc = ".$data['tipocontrato_otrosdesc']."
			   ,modalidadpago_otro = ".$data['modalidadpago_otro']."
			   ,modalidadpago_transcuenta_desc = ".$data['modalidadpago_transcuenta_desc']."
			   ,modalidadpago_cartafianza_importe = ".$data['modalidadpago_cartafianza_importe']."
			   ,modalidadpago_adelanto_exception = ".$data['modalidadpago_adelanto_exception']."
			   ,tipo_renovacion = ".$data['tipo_renovacion']."	
			   ,lugar_entrega_personal_tercero = ".$data['lugar_entrega_personal_tercero']."	
			   ,lugar_entrega_personal_tercero_numero = ".$data['lugar_entrega_personal_tercero_numero']."	
			   ,lugar_entrega_personal_tercero_dias = ".$data['lugar_entrega_personal_tercero_dias']."	
			   ,lugar_entrega_personal_tercero_equipo = ".$data['lugar_entrega_personal_tercero_equipo']."	
			   ,metas_cumplir_comentario = ".$data['metas_cumplir_comentario']."	
			   ,contraprestacion_incdocumento=".$data['contraprestacion_incdocumento']."
			   ,monto_mobiliario = ".$data['monto_mobiliario']."
			   ".$sql_file_1.$sql_file_2.$sql_file_3.$sql_file_4.$sql_file_5.$sql_file_6.$sql_file_7.$sql_file_8.$sql_file_9."
	        WHERE id = ".$data['id'];

    $link = conectarBD();
	
	startTransaction($link);
	
    $res = sqlsrv_query($link,  $sql);

    if($res === false){
        return finishTransaction($link, false);
	}else{
		
		$lastId = $data['id'];
		
		$sql = "";
		if( isset($_FILES["archivos_inmuebles_tp"]["error"]) ){
			foreach( $_FILES["archivos_inmuebles_tp"]["error"] as $key => $error ){
				$file_name_inmueble = $_FILES["archivos_inmuebles_tp"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name_inmueble."',1),";		
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_inmuebles_archivos(idcontrato,url,tipo) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
				if($res === false){
					return finishTransaction($link, false);
				}
		}
		
		$sql = "";
		if( isset($_FILES["archivos_inmuebles_cg"]["error"]) ){
			foreach( $_FILES["archivos_inmuebles_cg"]["error"] as $key => $error ){
				$file_name_inmueble = $_FILES["archivos_inmuebles_cg"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name_inmueble."',2),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_inmuebles_archivos(idcontrato,url,tipo) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
				if($res === false){
					return finishTransaction($link, false);
				}
		}
		
		$sql = "";
		if( isset($_FILES["archivos_sct2"]["error"]) ){
			foreach( $_FILES["archivos_sct2"]["error"] as $key => $error ){
				$file_name_inmueble = $_FILES["archivos_sct2"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name_inmueble."'),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_inmuebles_partregistral(idcontrato,url) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		$sql = "";
		if( isset($_FILES["archivos_OA1"]["error"]) ){
			foreach( $_FILES["archivos_OA1"]["error"] as $key => $error ){
				$file_name = $_FILES["archivos_OA1"]["name"][$key];
				$sql.= "(".$lastId.",'".$file_name."'),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_observaciones_ampliaciones(idcontrato,url) values ".$sql;
			
			$res = sqlsrv_query( $link, $sql);
				
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		$sql = "DELETE FROM contract_penalidades WHERE idcontrato =".$lastId;				
				
		$res = sqlsrv_query($link,  $sql);
				
		if($res === false){
            return finishTransaction($link, false);
		}
		
		if( strlen($data['penalidades']) > 2  ){
			$sql = "";
			$penalidades = objectToArray(json_decode($data['penalidades']));
			foreach( $penalidades as $penalidad ){			
				$sql.= "(".$lastId.",'".$penalidad['supuesto']."','".$penalidad['sancion']."'),";			
			}
			$sql = substr($sql, 0, -1);
			$sql = "insert into contract_penalidades(idcontrato,supuesto,sancion_economica) values ".$sql;
				
			$res = sqlsrv_query( $link, $sql);
					
			if($res === false){
				return finishTransaction($link, false);
			}
		}
		
		
		if( $data['termiesp_e_formapago'] == 1 ){//FORMA DE PAGO EN PARTES	
            
			if( !empty($lastId) ){						
				$sql = "DELETE FROM contract_formapao_parntes_detalle WHERE idsolcontracto =".$lastId;				
				
				$res = sqlsrv_query($link,  $sql);
				
				if($res === false){
					return finishTransaction($link, false);;
				}else{
					$detalle_en_partes = objectToArray(json_decode($data['forma_pago_en_partes_detalle']));	
					
					$sql = "";
					foreach($detalle_en_partes as $detalle){
							$sql.= "(".$lastId.",".$detalle['porcentaje'].",".$detalle['importe']."),";
					}
					$sql = substr($sql, 0, -1);
					$sql = "INSERT INTO contract_formapao_parntes_detalle(idsolcontracto,porcentaje,importte) VALUES ".$sql;
				
					$res = sqlsrv_query($link,  $sql);

                    return finishTransaction($link, $res);
				}
			}		
		}else{
            return finishTransaction($link, true);
		}
	}
}

function sendContractToLegalForCreation($id,$idusuario){
	
	global $TITLE_USUARIO_ENVIA_SOLICITUD_CREACION;
	
	$sql = "UPDATE contract_solcontrato SET datosgenerales_estado = 1 WHERE id = ".$id;

    $link = conectarBD();
	
	startTransaction($link);
	
    $res = sqlsrv_query( $link, $sql);
	
	if($res){
		global $TIPO_FLOW_USUARIO;
		$observacion_enviar = "Por favor crear contrato en base al formato adjunto";		
		$current_estado		=  0;
		$title 				=  $TITLE_USUARIO_ENVIA_SOLICITUD_CREACION;
		
		$res = reg_contacmovimiento($link,$id,$idusuario,$observacion_enviar,$current_estado,$TIPO_FLOW_USUARIO,1,array(),$title);
		
		return finishTransaction($link, $res);
	}else{
		return finishTransaction($link, false);
	}
}

function sendContractToJefeAreaForApprove($id,$idusuario){
	
	global $TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION;
	
	$sql = "UPDATE contract_solcontrato SET datosgenerales_estado = 0.3 WHERE id = ".$id;

    $link = conectarBD();
	
	startTransaction($link);
	
    $res = sqlsrv_query( $link, $sql);
	
	if($res){
		global $TIPO_FLOW_USUARIO;
		$observacion_enviar = "Por favor validar contrato en base al formato adjunto";		
		$current_estado		=  0;
		$title 				=  $TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION;
		
		$res = reg_contacmovimiento($link,$id,$idusuario,$observacion_enviar,$current_estado,$TIPO_FLOW_USUARIO,1,array(),$title);
		
		return finishTransaction($link, $res);
	}else{
		return finishTransaction($link, false);
	}
}

function getConractFormaPagoPartesDetalles($idcontrato){

    $sql = "select * from contract_formapao_parntes_detalle where idsolcontracto = ".$idcontrato;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    return $data;

}

function getContractMovimientos($id){
	
	$sql = "select contract.* , ad.usuario as usuarioname
	        from contract_movimiento contract
			inner join admin ad on ad.id = contract.idusuario
			where contract.idcontrato = ".$id."
			order by fecha_registra desc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    return $data;
}

function getContractArchivosFromMovimiento($id){
	
	$sql = "select * from contract_mov_archivo where idmovimiento = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    return $data;
}

function save_contrato_data($data,$archivos){
	
	global $TITLE_RESPONSABLE_AREA_VALIDA_FINAL;
	global $TITLE_LOGISTICA_VALIDA_FINAL;
	global $TITLE_SEC_VIGENTE;
	global $TIPO_USUARIO_COMPRADOR;
	
	$title_mov = "";
	
	$legal_action_estado1 = "";
	if($data['current_estado'] == 0.3){
		$legal_action_estado1 = 
			   "autorizac_b_nombres 	= '".$data['autorizac_b_nombres']."',
                autorizac_b_cargo 		= '".$data['autorizac_b_cargo']."',
                autorizac_b_fecha 		= '".$data['autorizac_b_fecha']."',";
	}else if($data['current_estado'] == 0.6){
		$legal_action_estado1 = 
			   "autorizac_c_nombres 	= '".$data['autorizac_c_nombres']."',
                autorizac_c_cargo 		= '".$data['autorizac_c_cargo']."',
                autorizac_c_fecha 		= '".$data['autorizac_c_fecha']."',";
	}
	
	$set_waitFirmaModo = "";
	if(!empty($data['waitFirmaModo'])){
		$set_waitFirmaModo = " waitfirmamodo=".$data['waitFirmaModo'].",";
	}
	
	$set_flags_last_approved = "";
	if($data['new_estado'] == 1 && ($data['current_estado'] == 2 || $data['current_estado'] == 3) ){
		$set_flags_last_approved = " flag_has_last_approved_usuario=0,flag_has_last_approved_logistica=0,";
		if( $data['current_estado'] == 2 && isset($data['role']) && $data['role'] == "jefe" ){
			$title_mov = $TITLE_RESPONSABLE_AREA_VALIDA_FINAL;
		}else if( $data['current_estado'] == 2 && isset($data['role']) && $data['role'] == "logistica" ){
			$title_mov = $TITLE_LOGISTICA_VALIDA_FINAL;
		}
	}else if ( isset($data['validateOthers']) ){
		
		$flags_status = getFlagsSatus($data['id']);		
		if( empty($flags_status) )
			return finishTransaction($link, false);
		
		$flag_has_last_approved_usuario   = $flags_status['flag_has_last_approved_usuario'];
		$flag_has_last_approved_logistica = $flags_status['flag_has_last_approved_logistica'];
		
		if(!empty($data['flag_has_last_approved_usuario'])){
			$set_flags_last_approved = " flag_has_last_approved_usuario=1,";
			$title_mov = $TITLE_RESPONSABLE_AREA_VALIDA_FINAL;
			if($data['TIPO_FLUJO_CONTRATO'] == $TIPO_USUARIO_COMPRADOR && $flag_has_last_approved_logistica == 1){
				$data['new_estado'] = 3;
			}
		}else if(!empty($data['flag_has_last_approved_logistica'])){
			$set_flags_last_approved = " flag_has_last_approved_logistica=1,";
			$title_mov = $TITLE_LOGISTICA_VALIDA_FINAL;
			if($data['TIPO_FLUJO_CONTRATO'] == $TIPO_USUARIO_COMPRADOR && $flag_has_last_approved_usuario == 1){
				$data['new_estado'] = 3;
			}
		}
	}
	
	$sql = "UPDATE contract_solcontrato
        	SET ".$legal_action_estado1.$set_waitFirmaModo.$set_flags_last_approved."
				datosgenerales_estado 	= ".$data['new_estado']."
			WHERE id = ".$data['id'];

    $link = conectarBD();
	
	$cerrado = 1;
	if($data['new_estado'] < $data['current_estado']){
		$cerrado = 0;
	}
	
    startTransaction($link);
	
    $res = sqlsrv_query($link, $sql );
	
	if($res){
		$res = reg_contacmovimiento($link,$data['id'],$data['idusuario'],$data['contrato_file1_obs2'],$data['current_estado'],$data['flow'],$cerrado,$archivos,$title_mov,$data['waitFirmaModo']);
		
		if($res === false){
			return finishTransaction($link, false);
		}else{			
			$res = true;
			if($data['new_estado'] == 4 && $data['current_estado'] == 3){
				$res = reg_contacmovimiento($link,$data['id'],$data['idusuario'],"Contrato vigente",3,$data['flow'],1,array(),$TITLE_SEC_VIGENTE);				
			}

            if($data['nuevoEstadoDerivacion'] >= 0){
                $res = nuevoEstadoDerivacion($data['idusuario'], $data['id'], $data['nuevoEstadoDerivacion'], $link);
            }
			
			return finishTransaction($link, $res);			
		}
	}else{
		return finishTransaction($link, false);
	}
}

function getArchivosContractByMovimiento($idmovimiento){
	
	$sql = "select * from contract_mov_archivo where idmovimiento = ".$idmovimiento;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    return $data;
}

function getDataForSuspend($id, &$link){
    $sql = "select suspendido
            from contract_solcontrato
            where id = ".$id;

    $data = queryBD($sql,$link);

    return $data[0]['suspendido'];
}

function suspendGo($id, $idusuario,$reason){

    $link = conectarBD();

    $isSuspendido = getDataForSuspend($id,$link);

    $suspend_details = "Razón: ".$reason;
    if($isSuspendido == 1 ){//Ya esta suspendido
        $suspend_message = "CONTRATO RE-ACTIVADO";
        $new_estado = 0;
    }else{
        $suspend_message = "CONTRATO SUSPENDIDO";
        $new_estado = 1;
    }

    $sql = "UPDATE contract_solcontrato
         	SET suspendido = ".$new_estado."
			WHERE id = ".$id;

    $res = sqlsrv_query( $link, $sql);

    reg_contacmovimiento($link,$id,$idusuario,$suspend_details,0,0,1, array(), $suspend_message);

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function anular_solcontrato($id,$idusuario,$reason){
	
	$link = conectarBD();
	
	$sql = "UPDATE contract_solcontrato
         	SET anulado = 1, anulado_usuario =  ".$idusuario." , anulado_fecha = GETDATE(), anulado_razon = '$reason'
			WHERE id = ".$id;
	
    $res = sqlsrv_query( $link, $sql);
	
	if($res === false){
		return false;
	}else{
		return true;
	}
}

function getEmpresaBySolContrato($id){

    $sql = "select reqgen_a_empresa from contract_solcontrato where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    if(empty($data)){
        return 0;
    }else{
        return $data[0]['reqgen_a_empresa'];
    }
}

function getAreaBySolContrato($id){
	
	$sql = "select reqgen_a_areausuaria from contract_solcontrato where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);
	
	if(empty($data)){
		return 0;
	}else{
		return $data[0]['reqgen_a_areausuaria'];
	}
}

function getCodigoContrato($id){
	
	$sql = "select datosgenerales_codigo from contract_solcontrato where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);
	
	if(empty($data)){
		return 0;
	}else{
		return $data[0]['datosgenerales_codigo'];
	}
}

function getCorrelativoFromTable($idarea){
	
	$sql = "select correlativo from contract_config_correlativos where idarea = '".$idarea."' and year = '".date("Y")."'";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);
	
	if(empty($data)){
		return 0;
	}else{
		return $data[0]['correlativo'];
	}    
}

function getCorrelativoDraftFromTable(){

    $sql = "select correlativo_draf from contract_config_correlativos where id = 92";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    if(empty($data)){
        return 0;
    }else{
        return $data[0]['correlativo_draf'];
    }
}

function getCorrelativoFromTable_method2023($idEmpresa){

    $sql = "select correlativo from contract_config_correlativos where idempresa = '".$idEmpresa."' and year = '".date("Y")."'";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    if(empty($data)){
        return 0;
    }else{
        return $data[0]['correlativo'];
    }
}

function getEmpresaCodigo($idEmpresa){
    $sql = "select codigo from contract_empresa where id = ".$idEmpresa;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    if(empty($data)){
        return "XXX";
    }else{
        return $data[0]['codigo'];
    }
}

function getCodigoArea($idarea){
	
	$sql = "select codigo from contract_area where id = ".$idarea;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

    return $data[0]['codigo'];
}

function createCorrelativo_newFormat2023($idEmpresa){
    $link = conectarBD();

    $sql = "INSERT INTO contract_config_correlativos(year,idempresa,correlativo) VALUES (".date("Y").",".$idEmpresa.",1)";

    $res = sqlsrv_query( $link, $sql);

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function createCorrelativo($idarea){
	
	$link = conectarBD();
	
	$sql = "INSERT INTO contract_config_correlativos(year,idarea,correlativo) VALUES (".date("Y").",".$idarea.",1)";
	
    $res = sqlsrv_query( $link, $sql);
	
	if($res === false){
		return false;
	}else{
		return true;
	}
}

function sumOneToCorrelativo_newFormat2023($idEmpresa){
    $link = conectarBD();

    $sql = "UPDATE contract_config_correlativos SET correlativo = correlativo+1 WHERE year = '".date("Y")."' AND idempresa = '".$idEmpresa."'";

    $res = sqlsrv_query( $link, $sql);

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function sumOneToCorrelativo($idarea){
	
	$link = conectarBD();
	
	$sql = "UPDATE contract_config_correlativos SET correlativo = correlativo+1 WHERE year = '".date("Y")."' AND idarea = '".$idarea."'";
	
    $res = sqlsrv_query( $link, $sql);
	
	if($res === false){
		return false;
	}else{
		return true;
	}
}

function sumOneToCorrelativoDraft(){

    $link = conectarBD();

    $sql = "UPDATE contract_config_correlativos SET correlativo_draf = correlativo_draf+1 WHERE id = 92";

    $res = sqlsrv_query( $link, $sql);

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function extract_data_regContract($post){
	$data = array(
				   "id" => $post['id'],
				   "tipo_flujo_contrato" => $post['tipo_flujo_contrato'],
				   "reqgen_a_empresa" => nullifempty($post['reqgen_a_empresa']),
				   "reqgen_a_areasolicitante" => nullifempty($post['reqgen_a_areasolicitante'],true),
				   "reqgen_a_areasolicitante_jefatura" => nullifempty($post['reqgen_a_areasolicitante_jefatura'],true),
				   "reqgen_a_compradorresponsable" => nullifempty($post['reqgen_a_compradorresponsable']),
				   "reqgen_a_areausuaria" => nullifempty($post['reqgen_a_areausuaria']),
				   "reqgen_a_areausuaria_jefatura" => nullifempty($post['reqgen_a_areausuaria_jefatura'],true),
				   "reqgen_proveedor" => nullifempty($post['reqgen_proveedor']),
				   "reqgen_proveedor_ruc" => nullifempty($post['reqgen_proveedor_ruc'],true),
				   "termiesp_a_tipocontrato" => nullifempty($post['termiesp_a_tipocontrato']),
				   "termiesp_a_nrocotizacion" => nullifempty($post['termiesp_a_nrocotizacion'],true),
				   "termiesp_a_fecha" => nullifempty($post['termiesp_a_fecha'],true),
				   "termiesp_b_alcance" => nullifempty($post['termiesp_b_alcance'],true),
				   "termiesp_c_dias" => nullifempty($post['termiesp_c_dias'],true),
				   "termiesp_c_formato" => nullifempty($post['termiesp_c_formato'],true),
				   "termiesp_c_medida" => nullifempty($post['termiesp_c_medida']),
				   "termiesp_c_fechainicio" => nullifempty($post['termiesp_c_fechainicio'],true),
				   "termiesp_c_fechafin" => nullifempty($post['termiesp_c_fechafin'],true),
				   "termiesp_c_incluyeacta" => nullifempty($post['termiesp_c_incluyeacta']),
				   "termiesp_d_monto" => nullifempty($post['termiesp_d_monto']),
				   "termiesp_d_moneda" => nullifempty($post['termiesp_d_moneda'], false, true),
				   "termiesp_e_formapago" => nullifempty($post['termiesp_e_formapago']),
				   "termiesp_e_avancez_medida" => nullifempty($post['termiesp_e_avancez_medida']),
				   "termiesp_e_credito_dias" => nullifempty($post['termiesp_e_credito_dias']),
				   "termiesp_f_modalidadpago" => nullifempty($post['termiesp_f_modalidadpago']),
				   "termiesp_g_garantia" => nullifempty($post['termiesp_g_garantia']),
				   "termiesp_g_adelanto_importe" => nullifempty($post['termiesp_g_adelanto_importe']),
				   "termiesp_g_fcumplimiento_importe" => nullifempty($post['termiesp_g_fcumplimiento_importe']),
				   "termiesp_g_fondogarantia_importe" => nullifempty($post['termiesp_g_fondogarantia_importe']),
				   "termiesp_h_lugarentrega" => nullifempty($post['termiesp_h_lugarentrega'],true),
				   "termiesp_i_observacionesamplicaciones" => nullifempty($post['termiesp_i_observacionesamplicaciones'],true),
				   "reqesp_ruta" => nullifempty($post['reqesp_ruta'],true),
				   "autorizac_a_nombres" => nullifempty($post['autorizac_a_nombres'],true),
				   "autorizac_a_cargo" => nullifempty($post['autorizac_a_cargo'],true),
				   "autorizac_a_fecha" => nullifempty($post['autorizac_a_fecha'],true),				   				   
				   "datosgenerales_usuarioregistra" => nullifempty($post['datosgenerales_usuarioregistra']),
				   "datosgenerales_estado" => nullifempty($post['datosgenerales_estado']),
				   "datosgenerales_codigo" => nullifempty($post['datosgenerales_codigo'],true),
				   "forma_pago_en_partes_detalle" => $post['forma_pago_en_partes_detalle'],
				   "proveedor_tipo" => $post['proveedor_tipo'],
				   "codigo_contrato" => $post['codigo_contrato'],
                   "tipo_renovacion" => $post['tipo_renovacion'],
				   "contrato_vinculado" => nullifempty($post['contrato_vinculado']),
				   "tipocontrato_otrosdesc" => nullifempty($post['tipocontrato_otrosdesc'],true),
				   "modalidadpago_otro" => nullifempty($post['modalidadpago_otro'],true),
                   "modalidadpago_transcuenta_desc" => nullifempty($post['modalidadpago_transcuenta_desc'], true),
				   "modalidadpago_cartafianza_importe" => nullifempty($post['modalidadpago_cartafianza_importe'],true),
				   "modalidadpago_adelanto_exception" => nullifempty($post['modalidadpago_adelanto_exception'],true),
				   "lugar_entrega_personal_tercero" => nullifempty($post['lugar_entrega_personal_tercero']),
				   "lugar_entrega_personal_tercero_numero" => nullifempty($post['lugar_entrega_personal_tercero_numero'],true),
				   "lugar_entrega_personal_tercero_dias" => nullifempty($post['lugar_entrega_personal_tercero_dias'],true),
				   "lugar_entrega_personal_tercero_equipo" => nullifempty($post['lugar_entrega_personal_tercero_equipo'],true),
				   "metas_cumplir_comentario" => nullifempty($post['metas_cumplir_comentario'],true),
				   "monto_mobiliario" => nullifempty($post['monto_mobiliario'],true),
				   "penalidades" => $post['penalidades'],
				   "formapago_medida" => nullifempty($post['formapago_medida'],false, true),
				   "modalidadpago_cartafianza_medida" => nullifempty($post['modalidadpago_cartafianza_medida']),
				   "modalidadpago_adelanto_medida" => nullifempty($post['modalidadpago_adelanto_medida']),
				   "modalidadpago_fcumplimiento_medida" => nullifempty($post['modalidadpago_fcumplimiento_medida']),
				   "modalidadpago_fgarantia_medida" => nullifempty($post['modalidadpago_fgarantia_medida']),
				   "monto_mobiliario_medida" => nullifempty($post['monto_mobiliario_medida']),
				   "penalidades_medida" => nullifempty($post['penalidades_medida']),
				   "contraprestacion_incdocumento" => nullifempty($post['contraprestacion_incdocumento']),
				   "cotizacion_bynrocontrato" => $post['cotizacion_bynrocontrato']
				);
	
	return $data;
}

function nullifempty($value,$add_comillas=false,$zeroToNull=false){
	if( ( empty($value) && $value != 0 ) || !isset($value) || is_null($value) || $value ===""){
		return 'NULL';
	}else if($zeroToNull && $value == 0){
	    return 'NULL';
	}else if($add_comillas){
	    return "'".$value."'";
	}else{
	    return $value;
	}
}

function format_code_correlativo_newFormat2023($correlativo, $idEmpresa){
    $codigo = "";
    $abreviatura_empresa = getEmpresaCodigo($idEmpresa);

    $cantidad_ceros_a_agregar = 3-strlen("".$correlativo."");
    for($i=0;$i<$cantidad_ceros_a_agregar;$i++){
        $codigo=$codigo."0";
    }

    return date("Y")."-".$codigo.(intval($correlativo))."-".$abreviatura_empresa;
}

function format_code_correlativo($correlativo,$area_codigo){
	
	$codigo = $area_codigo.date("Y").date("m");
	
	$cantidad_ceros_a_agregar = 6-strlen("".$correlativo."");
	for($i=0;$i<$cantidad_ceros_a_agregar;$i++){
		$codigo=$codigo."0";
	}
	return $codigo.(intval($correlativo));
}

function format_code_correlativo_draft($correlativo){

    $codigo = "";

    $cantidad_ceros_a_agregar = 6-strlen("".$correlativo);
    for($i=0;$i<$cantidad_ceros_a_agregar;$i++){
        $codigo=$codigo."0";
    }
    return $codigo.(intval($correlativo));
}

function upsertCodigoConrtato($id, $idEmpresa, $createRealNewCode = false){
    if($id == 0){
        return generateDraftCode();
    }else{
        if(!$createRealNewCode){
            return getCodigoContrato($id);
        }else{
            return generateCodigo_newFormat2023($idEmpresa);
        }
    }

}

function generateCodigo_newFormat2023($idEmpresa){

    $correlativo_from_table = getCorrelativoFromTable_method2023($idEmpresa);

    if( empty($correlativo_from_table)){
        createCorrelativo_newFormat2023($idEmpresa);
        $correlativo_from_table = 1;
    }else{
        sumOneToCorrelativo_newFormat2023($idEmpresa);
        $correlativo_from_table++;
    }

    return format_code_correlativo_newFormat2023($correlativo_from_table,$idEmpresa);
}

function generateDraftCode(){
    $codigo_correlativo_draf = getCodigoCorreltivoDraft();
    return "DRAFT_".$codigo_correlativo_draf;
}

function getCodigoCorreltivoDraft(){
    $correlativo_from_table = getCorrelativoDraftFromTable();
    $codigo = format_code_correlativo_draft($correlativo_from_table);
    sumOneToCorrelativoDraft();
    return $codigo;
}

function rename_contrato_folder($newContratoCode, $old_contrato_name, $dir_subida_general){
    rename($dir_subida_general."/".$old_contrato_name."/", $dir_subida_general."/".$newContratoCode."/");
}

function generateCodigo_oldMethod($id,$idarea){
    if($id == 0){
        $correlativo_from_table = getCorrelativoFromTable($idarea);
        $area_codigo 			= getCodigoArea($idarea);
        if( empty($correlativo_from_table)){
            createCorrelativo($idarea);
            $correlativo_from_table = 1;
        }else{
            sumOneToCorrelativo($idarea);
            $correlativo_from_table++;
        }
        return format_code_correlativo($correlativo_from_table,$area_codigo);
    }else{
        return getCodigoContrato($id);
    }
}

function getContratosVencidos($tipo_renovacion){
	
	$link = conectarBD();
	
	$sql = "SELECT id, datosgenerales_codigo
			FROM contract_solcontrato 
			WHERE GETDATE() > CONVERT(DATETIME,termiesp_c_fechafin,111) 
				  AND anulado = 0 
				  AND termiesp_c_fechafin IS NOT NULL				  
				  AND datosgenerales_estado = 4
				  AND procesado = 0
				  AND tipo_renovacion = ".$tipo_renovacion;
	
    $res = queryBD( $sql, $link);
	
	if($res === false){
		return false;
	}else{
	  return $res;
	}
}

function change_estado_to_concluido_create_vinculado($lista_ids,$createNewContrato){
	
	if(empty($lista_ids))
		return true;
	
	global $ESTADO_SOLCONTRACT_CONCLUIDO;
	global $TIPO_FLOW_USUARIO;
	global $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO;
    $lista_contratos_vencidos = implode(",", $lista_ids);

    $link = conectarBD();
	
	startTransaction($link);
	
	$sql = "UPDATE contract_solcontrato 
			SET procesado_time =GETDATE(), procesado = 1, datosgenerales_estado = ".$ESTADO_SOLCONTRACT_CONCLUIDO."
			WHERE id IN (".$lista_contratos_vencidos.") ";
		
	$res = sqlsrv_query( $link, $sql);
	
	if($res){
		foreach($lista_ids as $id){
			$idusuario   = 1;//USUARIO SISTEMA
			$observacion = "Contrato concluido automaticamente";
			$estado      = $ESTADO_SOLCONTRACT_CONCLUIDO;//estado concluido
			$tipo_flow   = $TIPO_FLOW_USUARIO;
			$cerrado     = 1;//MOVIMIENTO CONCLUIDO
			$res = reg_contacmovimiento($link,$id,$idusuario,$observacion,$estado,$tipo_flow,$cerrado);

            if(!$res)break;
		}
        finishTransaction($link, $res);
	}else{
		return finishTransaction($link, false);
	}
	
	if($createNewContrato && $res){
		foreach($lista_ids as $id){
			$contract = getContratoData($id);
			$codigo_contrato = upsertCodigoConrtato(0, 0);
			$data = array(
                            "tipo_flujo_contrato" => $contract['tipo_flujo'],
							"cotizacion_bynrocontrato" => 1,
							"contrato_vinculado"=>$id,
							"tipo_renovacion"=>$contract['tipo_renovacion'],
							"reqgen_a_empresa"=>$contract['reqgen_a_empresa'],
							"reqgen_a_areasolicitante"=>$contract['reqgen_a_areasolicitante'],
							"reqgen_a_areasolicitante_jefatura"=>$contract['reqgen_a_areasolicitante_jefatura'],
							"reqgen_a_areausuaria"=>$contract['reqgen_a_areausuaria'],
							"reqgen_a_areausuaria_jefatura"=>$contract['reqgen_a_areausuaria_jefatura'],
							"reqgen_proveedor"=>$contract['reqgen_proveedor'],
							"reqgen_proveedor_ruc"=>$contract['reqgen_proveedor_ruc'],
							"termiesp_a_tipocontrato"=>$contract['termiesp_a_tipocontrato'],							
							"proveedor_tipo" => $contract['proveedor_tipo'],
				            "codigo_contrato" => $codigo_contrato,
							"datosgenerales_usuarioregistra" => $contract['datosgenerales_usuarioregistra'],
							"datosgenerales_estado" => $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO,
							"reqgen_a_compradorresponsable" => $contract['reqgen_a_compradorresponsable']
                   
						 );
			$data = extract_data_regContract($data);
			$res = registrarContrato($data);
			if(!empty($res))
				sendNotification_ContratoRenovacionAutomatica($res,$contract['datosgenerales_codigo'], $codigo_contrato);
		}
	}
	
	return $res;
}

function getContratosVencidos_alert($nro_dias_para_vencer,$menorIgual=false){
	
	$link = conectarBD();
	
	$operador = " = ";
	if($menorIgual)
		$operador = " <= ";
	
	$sql = " SELECT DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) as diasparacaducar, *
			 FROM contract_solcontrato
			 WHERE termiesp_c_fechafin IS NOT NULL 
			       AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0 
				   AND procesado = 0 
				   AND datosgenerales_estado = 4
				   AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime))".$operador.$nro_dias_para_vencer;
	
    $res = queryBD( $sql, $link);
	sqlsrv_close($link);
	
	if($res === false){
		return false;
	}else{
	  return $res;
	}
}

function acta_entrega($id, $usuario, $fecha_inicio, $fecha_fin){
	
	$link = conectarBD();
	
	$sql = "UPDATE contract_solcontrato 
	        SET termiesp_c_fechainicio = '".$fecha_inicio."',
 			    termiesp_c_fechafin = '".$fecha_fin."'
			WHERE id = ".$id;
	
    $res = queryBD( $sql, $link);
	sqlsrv_close($link);
	
	if($res === false){
		return false;
	}else{
	  return true;
	}
	
}

function actualizarCodigoContrato($id, $newCodigo_contrato){

    $link = conectarBD();

    $sql = "UPDATE contract_solcontrato 
                SET datosgenerales_codigo = '".$newCodigo_contrato."'
                WHERE id = ".$id;

    $res = queryBD( $sql, $link);
    sqlsrv_close($link);

    if($res === false){
        return false;
    }else{
        return true;
    }

}

function getListaArchivosRubrosMoviliarios($idcontrato){
	
	$sql = "select * from contract_inmuebles_archivos where idcontrato = ".$idcontrato;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getListaArchivosPartidaRegistral($idcontrato){
	
	$sql = "select * from contract_inmuebles_partregistral where idcontrato = ".$idcontrato;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getListaArchivosObservacionesAmpliaciones($idcontrato){
	
	$sql = "select * from contract_observaciones_ampliaciones where idcontrato = ".$idcontrato;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getPenalidades($idcontrato){
	
	$sql = "select * from contract_penalidades where idcontrato = ".$idcontrato;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
    }
    sqlsrv_close($link);

    return $data;
}

function getNombreFromUser_cp2($idusuario){
	
	$sql = "select usuario from contract_usuarioshabilitados where id = ".$idusuario;
			
	$link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

	if(!empty($data)){
		return $data[0]['usuario'];
	}else{
		return "";
	}
}

function getNombreFromUser_cp2_1($idusuario, &$link){

    $sql = "select usuario from contract_usuarioshabilitados where id = ".$idusuario;

    $data = queryBD($sql,$link);

    if(!empty($data)){
        return $data[0]['usuario'];
    }else{
        return "";
    }
}

function getNamesFromAdminTableUsingUsername($usuario, &$link){

    $sql = "select nombres, apellidos
            from admin ad
            where ad.usuario like '".$usuario."@%'";

    $data = queryBD($sql,$link);

    if(sizeof($data) == 1)
        return $data[0]['nombres']." ".$data[0]['apellidos'];
    else
        return false;
}

function getTipoFlujoContrato($id){
	
	$sql = "select tipo_flujo from contract_solcontrato where id = ".$id;
			
	$link = conectarBD();
    $data = queryBD($sql,$link);
    sqlsrv_close($link);

	if(!empty($data)){
		return $data[0]['tipo_flujo'];
	}else{
		return "";
	}
}

function display_iframe_or_link_to_download($file_name, $codigo, $label){
	
	if(empty($file_name)){echo "<font color='red'>Error: No se adjunto archivo: ".$label."</font><br>";return "";}
	
	$pieces = explode(".",$file_name);
	
	if(empty($pieces)){echo "Error: file extension error.<br>";return "";}
	
	$file_ext = array_pop($pieces);
    if($file_ext == "pdf"){
					
			echo 		'<iframe id="iframepdf" height="450" width="850" style="border:1px solid #666CCC"			        
									src="../files/contratos/'.$codigo.'/'.$file_name.'#zoom=50">
						</iframe><br>';					
	}else{
			echo  "<a href='../files/contratos/".$codigo."/".$file_name."'><i  class='icon-download bigger-120'></i> Descargar</a><br>";
	}
	
}

function display_warning_icon_when_action_needed($status_html,$status="warning"){
	
	$pos = strpos($status_html, $status);
	
	if($pos === false){
		return "";
	}else{
		return "<i class=' icon-warning-sign' alt='Accion Pendiente' title='Accion Pendiente'></i>";
	}
}

function getFlowDescMovimiento($movimiento_title){
	
	$input = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ä', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü', 'â', 'ã', 'ä', 'å', 'ā', 'ă', 'ą', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ā', 'Ă', 'Ą', 'è', 'é', 'é', 'ê', 'ë', 'ē', 'ĕ', 'ė', 'ę', 'ě', 'Ē', 'Ĕ', 'Ė', 'Ę', 'Ě', 'ì', 'í', 'î', 'ï', 'ì', 'ĩ', 'ī', 'ĭ', 'Ì', 'Í', 'Î', 'Ï', 'Ì', 'Ĩ', 'Ī', 'Ĭ', 'ó', 'ô', 'õ', 'ö', 'ō', 'ŏ', 'ő', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ō', 'Ŏ', 'Ő', 'ù', 'ú', 'û', 'ü', 'ũ', 'ū', 'ŭ', 'ů', 'Ù', 'Ú', 'Û', 'Ü', 'Ũ', 'Ū', 'Ŭ', 'Ů'];
	$output = '_';
	$movimiento_title = str_replace($input, $output, $movimiento_title);
	
	$pos_usuario 		= strpos(strtoupper($movimiento_title), strtoupper("usuario"));
	$pos_legal 			= strpos(strtoupper($movimiento_title), strtoupper("legal"));
	$pos_legal_2    	= strpos(strtoupper($movimiento_title), strtoupper("firmas"));
	$pos_legal_3    	= strpos(strtoupper($movimiento_title), strtoupper("vigente"));
	$pos_logistica_tilde= strpos(strtoupper($movimiento_title), strtoupper("log_stica"));
	$pos_logistica		= strpos(strtoupper($movimiento_title), strtoupper("logistica"));
	$pos_responsable 	= strpos(strtoupper($movimiento_title), strtoupper("responsable"));
	
	if( $pos_usuario !== false ){
		return "Usuario";
	}else if( $pos_legal !== false || $pos_legal_2 !== false || $pos_legal_3 !== false){
		return "Legal";
	}else if( $pos_logistica !== false || $pos_logistica_tilde !== false ){
		return "Logística";
	}else if( $pos_responsable !== false ){
		return "Responsable área";
	}
	
	return "User";
	
}

function getAllCodigoContratos(){
	
	$sql = "select datosgenerales_codigo, id from contract_solcontrato order by id desc";

    $link = conectarBD();
    $data = queryBD($sql,$link);

    return $data;
	
}

function getFlagsSatus($idcontrato){
	
	$sql = "select flag_has_last_approved_usuario, flag_has_last_approved_logistica
			from contract_solcontrato
			where id = ".$idcontrato;
			
	$link = conectarBD();
    $data = queryBD($sql,$link);

	if(!empty($data)){
		return $data[0];
	}else{
		return false;
	}
}

function getUserIdFromAdminTableUsingUsername($usuario, &$link){

    $sql = "select id
            from admin ad
            where ad.usuario like '".$usuario."@%'";

    $data = queryBD($sql,$link);

    if(sizeof($data) == 1)
        return $data[0]['id'];
    else
        return false;
}

function getUsuariosLegal(){

    $sql = "select cuh.usuario
            from contract_usuarioshabilitados cuh
            where cuh.idarea = 1
            
            union
            
            select cuh.usuario
            from contract_areajefaturas caj
            inner join contract_usuarioshabilitados cuh on cuh.id = caj.idusuariohabilitado
            where caj.idarea = 1";

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC )) {
        $row['id'] = getUserIdFromAdminTableUsingUsername($row['usuario'], $link);
        $data[] = $row;
    }

    return $data;
}

function anular_derivar($id,$usuario,$reason){

    $sql = "UPDATE contract_derivacioneslegal SET anulado_fecha = GETDATE(), anulado = 1, anulado_razon = '".$reason."', anulado_usuario = ".$usuario.
            "WHERE id = ".$id;

    $link = conectarBD();
    $res = sqlsrv_query( $link, $sql);
    if($res === false)
        return false;
    else
        return true;
}

function delete_file_attached($idContrato, $campo, $codigo_contrato, $nombre_archivo){
    if(empty($idContrato) || empty($campo) || empty($codigo_contrato) || empty($nombre_archivo)){
        global $global_error;
        $global_error = "Parametos invalidos.";
        return false;
    }

    global $dir_subida;

    $res = unlink($dir_subida."/".$codigo_contrato."/".$nombre_archivo);
    if($res){
        setValueToBlankInDb($campo, $idContrato);
        return true;
    }else{
        global $global_error;
        $global_error = "No se pudo eliminar el archivo solciitado.";
        return false;
    }
}

function delete_file_attached_detalle($idContrato, $idDetalle, $codigo_contrato, $nombre_archivo, $option){
    if(empty($idContrato) || empty($idDetalle) || empty($codigo_contrato) || empty($nombre_archivo)){
        global $global_error;
        $global_error = "Parametos invalidos.";
        return false;
    }

    global $dir_subida;

    $res = unlink($dir_subida."/".$codigo_contrato."/".$nombre_archivo);
    if($res){
        if($option==1){
            deleteDocumentDetalle($idDetalle, $idContrato, "contract_inmuebles_archivos");
        }else if($option==2){
            deleteDocumentDetalle($idDetalle, $idContrato, "contract_inmuebles_partregistral");
        }else if($option==3){
            deleteDocumentDetalle($idDetalle, $idContrato, "contract_observaciones_ampliaciones");
        }
        return true;
    }else{
        global $global_error;
        $global_error = "No se pudo eliminar el archivo solicitado.";
        return false;
    }
}

function deleteDocumentDetalle($idDetalle, $idContrato, $table){
    $sql = "DELETE FROM ".$table." WHERE id = ".$idDetalle." AND idcontrato = ".$idContrato;

    $link = conectarBD();
    $res = sqlsrv_query( $link, $sql);
    if($res === false)
        return false;
    else
        return true;
}

function setValueToBlankInDb($campo, $idContrato){
    $sql = "UPDATE contract_solcontrato SET ".$campo." = '' WHERE id = ".$idContrato;

    $link = conectarBD();
    $res = sqlsrv_query( $link, $sql);
    if($res === false)
        return false;
    else
        return true;
}

function save_derivar($idContrato,$idUsuarioAsignado,$idUsuarioAsigna,$detalle){

    if(isContratoDerivadoOpen($idContrato)){
        return false;
    }

    $sql = "INSERT INTO contract_derivacioneslegal
                       (idusuarioderiva
                       ,idusuarioasignado
                       ,idcontrato
                       ,detalle
                       ,fechaderiva)
                 VALUES
                       (".$idUsuarioAsigna."
                       ,".$idUsuarioAsignado."
                       ,".$idContrato."
                       ,'".$detalle."'
                       ,GETDATE())
            ";

    $link = conectarBD();
    $res = sqlsrv_query( $link, $sql);
    if($res === false)
        return false;
    else
        return true;
}

function getDerivaciones($idcontrato){

    $sql = "SELECT cd.*, cs.datosgenerales_codigo
            FROM contract_derivacioneslegal cd
            INNER JOIN contract_solcontrato cs ON cs.id = cd.idcontrato
            WHERE idcontrato = ".$idcontrato;

    $link = conectarBD();

    $data = queryBD($sql,$link);

    return $data;
}

function getUsernameFromAdmin($id){

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

function getContratosDerivadoByUser_to($idUser){

    $sql = "select distinct cs.id, cs.datosgenerales_codigo, cs.anulado, cs.suspendido, cs.tipo_flujo, cs.flag_has_last_approved_usuario, cs.flag_has_last_approved_logistica, cs.datosgenerales_estado
            from contract_derivacioneslegal cdl
            inner join contract_solcontrato cs on cs.id = cdl.idcontrato
            where cdl.idusuarioasignado = ".$idUser;

    $link = conectarBD();

    $data = queryBD($sql,$link);

    return $data;
}

function getContratosDerivadoByUser_from($idUser){

    $sql = "select distinct cs.id, cs.datosgenerales_codigo, cs.anulado, cs.suspendido, cs.tipo_flujo, cs.flag_has_last_approved_usuario, cs.flag_has_last_approved_logistica, cs.datosgenerales_estado
            from contract_derivacioneslegal cdl
            inner join contract_solcontrato cs on cs.id = cdl.idcontrato
            where cdl.idusuarioderiva = ".$idUser;

    $link = conectarBD();

    $data = queryBD($sql,$link);

    return $data;
}

function isContratoDerivadoAMiUsuario($idContrato, $idUsuario){

    $sql = "select 1
            from contract_derivacioneslegal cdl
            inner join contract_solcontrato cs on cs.id = cdl.idcontrato
            where cdl.idusuarioasignado = ".$idUsuario." and cs.id = ".$idContrato;

    $link = conectarBD();

    $data = queryBD($sql,$link);

    if(empty($data))
        return false;
    else
        return true;
}
function isContratoDerivadoPorMiUsuario($idContrato, $idUsuario){

    $sql = "select 1
            from contract_derivacioneslegal cdl
            inner join contract_solcontrato cs on cs.id = cdl.idcontrato
            where cdl.idusuarioderiva = ".$idUsuario." and cs.id = ".$idContrato;

    $link = conectarBD();

    $data = queryBD($sql,$link);

    if(empty($data))
        return false;
    else
        return true;
}

function getEstadoDerivacion($idContrato){

    $sql = "select estado
            from contract_derivacioneslegal
            where anulado = 0 and estado < 2 and idcontrato = ".$idContrato;

    $link = conectarBD();

    $data = queryBD($sql,$link);

    if(!empty($data))
        return $data[0]['estado'];
    else
        return 0;
}

function isContratoDerivadoOpen($idContrato){

    $sql = "select 1
            from contract_derivacioneslegal cdl
            inner join contract_solcontrato cs on cs.id = cdl.idcontrato
            where cs.id = ".$idContrato." and cdl.estado < 2 and cdl.anulado = 0";

    $link = conectarBD();

    $data = queryBD($sql,$link);

    if(empty($data))
        return false;
    else
        return true;
}

function nuevoEstadoDerivacion($idUsuario, $idContrato, $nuevoEstadoDerivacion,  &$link){

    $sql = "UPDATE contract_derivacioneslegal SET estado = ".$nuevoEstadoDerivacion.", fechacompleta = GETDATE(), idusuariocompleta = ".$idUsuario."
            WHERE idcontrato = ".$idContrato." AND anulado = 0";

    $res = sqlsrv_query( $link, $sql);
    if($res === false)
        return false;
    else
        return true;
}

function getIconToDiplay($filename){
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if($ext == "pdf"){
        return "pdf.png";
    }else if($ext == "doc" || $ext == "docx"){
        return  "word.png";
    }else if($ext == "xls" || $ext == "xlsx"){
        return  "excel.png";
    }else if($ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "bmp"){
        return  "image.png";
    }else{
        return  "other.png";
    }
}

function removeOlderFile($idContrato, $campo){

    if(empty($idContrato) || empty($campo))
        return false;

    global $dir_subida;

    $data = getContractInfo($idContrato, $campo);
    $nombre_archivo =  $data['nombre_archivo'];

    if(empty($nombre_archivo))
        return false;

    $dir_file = $dir_subida."/".$nombre_archivo;

    $res = false;
    if(file_exists($dir_file)){
        $res = unlink($dir_file);
    }

    return $res;

}

function getContractInfo($idContrato, $campo){

    $sql = "select cs.id as idcontrato,
	               cs.datosgenerales_codigo as codigo_contrato, 
	               ".$campo." as nombre_archivo
	        from contract_solcontrato cs
	        where id = ".$idContrato;

    $link = conectarBD();
    $stmt = sqlsrv_query($link, $sql);
    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    if(empty($data))
        return array();
    else return $data[0];
}
