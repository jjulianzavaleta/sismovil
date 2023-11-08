<?php

function getAllTipoContratoToCombobox(){
	
	$sql = "select * from contract_tipocontrato order by descripcion asc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}

function getAllProveedoresToCombobox(){
	
	$sql = "select * from contract_proveedor order by razon_social asc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}

function getAllEmpresasToCombobox(){
	
	$sql = "select * from contract_empresa order by descripcion asc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$data[] = $row;
	}
    sqlsrv_close($link);

    return $data;
}

function getAllMiContratosWithFilters($opt,$fechaIni,$fechaFin,$chk_vigente,$chk_concluido,$chk_registrado,$chk_val_jefarea,$chk_val_legal_acepta,$chk_val_jef_log,$chk_pendelaboracion,$chk_pendaprobacionusuario,$chk_colectarfirmas,$chk_anulado,$chk_porempresa,$chk_porproveedor,$chk_portipocontrato,$chk_porcodigo,$chk_poralcance,$select_1,$select_2,$select_3,$codigo,$chk_vence15,$chk_vence30,$chk_vence60,$chk_vence90,$chk_vence365,$alcance,$idusuario=0){
	
	$filters = "";
	if($opt==1){
		
		if( strlen($fechaIni) > 9 && strlen($fechaFin) > 9 )
            $filters = " AND CAST('".$fechaIni."' AS date) <= CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date) AND CAST(SUBSTRING('".$fechaFin."',0,11) AS date) >= CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date)";
		
		$estados = array();
		if(!empty($chk_vigente)){//datosgenerales_estado = 4
			$estados[] = 4;			
		}
		if(!empty($chk_concluido)){//datosgenerales_estado = 5
			$estados[] = 5;
		}
		if(!empty($chk_registrado)){//datosgenerales_estado = 0
			$estados[] = 0;
		}
		if(!empty($chk_val_jefarea)){//datosgenerales_estado = 0.3
			$estados[] = 0.3;
		}
		if(!empty($chk_val_jef_log)){//datosgenerales_estado = 0.6
			$estados[] = 0.6;
		}
        if(!empty($chk_val_legal_acepta)){//datosgenerales_estado = 0.8
            $estados[] = 0.8;
        }
		if(!empty($chk_pendelaboracion)){//datosgenerales_estado = 1
			$estados[] = 1;
		}
		if(!empty($chk_pendaprobacionusuario)){//datosgenerales_estado = 2
			$estados[] = 2;
		}
		if(!empty($chk_colectarfirmas)){//datosgenerales_estado = 3
			$estados[] = 3;
		}
		
		if( !empty($chk_vigente) or !empty($chk_concluido) or !empty($chk_registrado) or !empty($chk_val_jefarea) or !empty($chk_val_jef_log) or !empty($chk_val_legal_acepta) or !empty($chk_pendelaboracion) or !empty($chk_pendaprobacionusuario) or !empty($chk_colectarfirmas) ){
			$filters.=" AND cs.datosgenerales_estado in (".implode(",",$estados).")";
			if(empty($chk_anulado))
				$filters.=" AND cs.anulado=0";
		}
		
		if(!empty($chk_anulado)){//anulado = 1
			$filters.=" AND cs.anulado=1";
		}
		
	}else if($opt==2){
		
		if(!empty($chk_porempresa)){
			$filters = " AND cs.reqgen_a_empresa = ".$select_1;
		}
        if(!empty($chk_porproveedor)){
			$filters.= " AND cs.reqgen_proveedor = ".$select_2;
		}
        if(!empty($chk_portipocontrato)){
			$filters.= " AND cs.termiesp_a_tipocontrato = ".$select_3;
		}
        if(!empty($chk_porcodigo)){
			$filters.= " AND cs.datosgenerales_codigo like '%".$codigo."%'";
		}
        if(!empty($chk_poralcance)){
			$filters.= " AND cs.termiesp_b_alcance like '%".$alcance."%'";
		}
		
	}else if($opt==3){
		
		if(!empty($chk_vence15)){//termiesp_c_fechafin -  now () <= 15
			$filters.=" AND termiesp_c_fechafin IS NOT NULL AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) <= 15 AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0 AND anulado = 0 AND datosgenerales_estado in (4)";
		}
		if(!empty($chk_vence30)){//termiesp_c_fechafin -  now () <= 30
			$filters.=" AND termiesp_c_fechafin IS NOT NULL  AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) <= 30 AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0  AND anulado = 0 AND datosgenerales_estado in (4)";
		}
		if(!empty($chk_vence60)){//termiesp_c_fechafin -  now () <= 60
			$filters.=" AND termiesp_c_fechafin IS NOT NULL  AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) <= 60 AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0  AND anulado = 0 AND datosgenerales_estado in (4)";
		}
		if(!empty($chk_vence90)){//termiesp_c_fechafin -  now () <= 90
			$filters.=" AND termiesp_c_fechafin IS NOT NULL  AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) <= 90 AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0  AND anulado = 0 AND datosgenerales_estado in (4)";
		}
		if(!empty($chk_vence365)){//termiesp_c_fechafin -  now () <= 365
			$filters.=" AND termiesp_c_fechafin IS NOT NULL  AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) <= 365 AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0  AND anulado = 0 AND datosgenerales_estado in (4)";
		}
	}
	
	$ROLE_USUARIO 				= 1;
	$ROLE_RESPONSABLE_AREA 		= 2;
	$ROLE_RESPONSABLE_LEGAL 	= 3;
	$ROLE_RESPONSABLE_LOGISTICA = 4;
	
	$permissions_contracts = getPermissionsUsuarioContract( $_SESSION['username'] );
		
	$isLogistica = $permissions_contracts[0]['idarea'] == 20 ? true : false;
	$isLegal     = $permissions_contracts[0]['idarea'] == 1 ? true : false;
	$isJefeArea  = $permissions_contracts[0]['permission_responsablearea'] == 1 ? true : false;
		
	if( $isLogistica &&  $isJefeArea ){
		$role = $ROLE_RESPONSABLE_LOGISTICA;
	}else if( $isLegal && $isJefeArea ){
		$role = $ROLE_RESPONSABLE_LEGAL;
	}else if( $isJefeArea ){
		$role = $ROLE_RESPONSABLE_AREA;	
	}else{
		$role = $ROLE_USUARIO;
	}
	
	$sql = "select cs.id as idcontrato,
				   cs.* , 
	               ce.descripcion as nombre_empresa, 
				   cp.razon_social as nombre_proveedor,
				   tc.descripcion as nombre_tipocontrato,
				   convert(varchar, CONVERT(DATETIME, datosgenerales_fecharegistra),120) as fecha_formateada,
                   SUBSTRING(datosgenerales_fecharegistra,0,5)  as year_req,     
                   concat(cs.termiesp_d_monto,' ',tm.descripcion) as monto	,
                   cs.suspendido
	        from contract_solcontrato cs
			inner join contract_empresa ce on ce.id = cs.reqgen_a_empresa
			inner join contract_proveedor cp on cp.idproveedor = cs.reqgen_proveedor
			left join contract_tipomoneda tm on tm.id = cs.termiesp_d_moneda
			left join contract_tipocontrato tc on tc.id = cs.termiesp_a_tipocontrato
			where cs.id IS NOT NULL ".$filters.
			"order by cs.id desc";

    $link = conectarBD();
	$stmt = sqlsrv_query($link, $sql);
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

			$isTipoFlujoComprador = $row['tipo_flujo'] == 1 ? true : false;
			$hasLastUserApproved  = $row['flag_has_last_approved_usuario'] == 1 ? true : false;
			$hasLastLogisticaApproved = $row['flag_has_last_approved_logistica'] == 1 ? true : false;
			$row['estado_html'] = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,true,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
			$row['estado']      = convertToEstado($row['datosgenerales_estado'],$row['anulado'],$row['suspendido'],$role,false,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);
        	$row['aprob_preliminar'] = getAprobacionPreliminarText($row['idcontrato'], $link);
            $row['fecha_envia_sec'] = getFechaUsuarioEnviaSEC($row['idcontrato'], $link);
            $row['fecha_legal_elabora'] = getFechaLegalElabora($row['idcontrato'], $link);
			$row['aprob_final'] = getAprobacionFinalText($row['idcontrato'], $link);
            $row['legal_final'] = getFechasLegalFinal($row['idcontrato'], $link);
            $username_jefe_area_usuario = getNombreFromUser_cp2_1($row['reqgen_a_areausuaria_jefatura'], $link);
            $row['usuario_jefatura_area_usuaria']   = getNamesFromAdminTableUsingUsername($username_jefe_area_usuario, $link);
            $row['usuario_creador_sec']             = $row['autorizac_a_nombres'];
            $row['vigencia_inicio']                 = $row['termiesp_c_fechainicio'];
            $row['vigencia_fin']                    = $row['termiesp_c_fechafin'];
			$data[] = $row;
    	   }
    

    return $data;
}

function convertToEstado($estado,$anulado,$suspendido,$role,$html=false,$isTipoFlujoComprador=false,$hasLastLogisticaApproved=false,$hasLastUserApproved=false){
	
	$word  = "-";
	$label = "";
	$ROLE_USUARIO 				= 1;
	$ROLE_RESPONSABLE_AREA 		= 2;
	$ROLE_RESPONSABLE_LEGAL 	= 3;
	$ROLE_RESPONSABLE_LOGISTICA = 4;
	
	if($anulado == 1){
		if($html){
			return "<span class='label'>Anulado</span>";
		}else{
			return "Anulado";
		}
	}else if($suspendido){
        if($html){
            return "<span class='label'>Suspendido</span>";
        }else{
            return "Suspendido";
        }
    }else{

		switch ( $estado ) {
			case 0:
				$word = "Registrado";
				$label = "label-success";				
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;
				
			case 0.3:
				$word = "Pendiente de validación preliminar jefatura área";
				$label = "label-primary";
				if( $role == $ROLE_RESPONSABLE_AREA ){					
					$label = "label-warning";
				}
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;
				
			case 0.6:
				$word = "Pendiente de validación preliminar jefatura logística";
				$label = "label-primary";
				if( $role == $ROLE_RESPONSABLE_LOGISTICA ){					
					$label = "label-warning";
				}
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;

            case 0.8:
                $word = "Espera de aceptación de elaboración por Legal";
                $label = "label-primary";
                if( $role == $ROLE_RESPONSABLE_LEGAL){
                    $label = "label-purple";
                }

                if($html)
                    return "<span class='label ".$label."'>".$word."</span>";
                else
                    return $word;

                break;
				
			case 1:
				$word = "Pendiente de elaboración por Legal";
				$label = "label-primary";
				if( $role == $ROLE_RESPONSABLE_LEGAL){					
					$label = "label-purple";
				}
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;			
			case 2:
				$label = "label-primary";
				if( $isTipoFlujoComprador && !$hasLastLogisticaApproved && !$hasLastUserApproved ){
					$word = "Validación final 0/2";
					if( $role == $ROLE_RESPONSABLE_AREA || $role == $ROLE_RESPONSABLE_LOGISTICA ){
						$label = "label-warning";
					}
				}else if( $isTipoFlujoComprador &&  $hasLastLogisticaApproved && !$hasLastUserApproved ){
					$word = "Pendiente de validación final responsable de área";
					if( $role == $ROLE_RESPONSABLE_AREA ){
						$label = "label-warning";
					}
				}else if( $isTipoFlujoComprador &&  !$hasLastLogisticaApproved && $hasLastUserApproved ){
					$word = "Pendiente de validación final responsable de logística";
					if( $role == $ROLE_RESPONSABLE_LOGISTICA ){
						$label = "label-warning";
					}
				}else if( !$isTipoFlujoComprador && !$hasLastUserApproved){					
					$word = "Pendiente de validación final responsable de área";
					if( $role == $ROLE_RESPONSABLE_AREA ){
						$label = "label-warning";
					}
				}else{
					$word = "Pendiente de validación final";
				}
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;
			case 3:
				$word = "Pendiente de recolección firmas por Legal";
				if( $role == $ROLE_RESPONSABLE_LEGAL ){					
					$label = "label-pink";
				}else{
					$label = "label-primary";
				}
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;
			case 4:
				$word = "Vigente";
				$label = "label-success";
				
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
				break;
			case 5:
				$word = "Concluido";
				$label = "";
				
				if($html)
					return "<span class='label'>".$word."</span>";
				else
					return $word;
				
				break;
			default:
				if($html)
					return "<span class='label ".$label."'>".$word."</span>";
				else
					return $word;
				
		}
	}
}

function getFechasLegalFinal($idcontrato, &$link)
{
	global $TITLE_ESPERA_FIRMAS;
	global $TITLE_SEC_VIGENTE;

	$firmas_fecha = getLastMovimientoByIdContrato($idcontrato, $TITLE_ESPERA_FIRMAS, $link);
	$vigente_fecha = getLastMovimientoByIdContrato($idcontrato, $TITLE_SEC_VIGENTE, $link);
	$estado = "";
	if( !empty($firmas_fecha) ){
        $estado.= "Firmas: ".$firmas_fecha['fecha_registra_formatted'];
    }
	if( !empty($vigente_fecha) ){
        $estado.= "<br>Vigente: ".$vigente_fecha['fecha_registra_formatted'];
    }
	if(!empty($estado)){
        return $estado;
    }else{
        return "No disponible";
    }
}

function getFechaLegalElabora($idcontrato, &$link){

    global $TITLE_LEGAL_ELABORA_CONTRATO;

    $data = getLastMovimientoByIdContrato($idcontrato, $TITLE_LEGAL_ELABORA_CONTRATO, $link);

    $estado = "";
    if( !empty($data) ){
        $estado.= $data['fecha_registra_formatted'];
    }

    if(!empty($estado)){
        return $estado;
    }else{
        return "No disponible";
    }

}

function getFechaUsuarioEnviaSEC($idcontrato, &$link){

    global $TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION;

    $data = getLastMovimientoByIdContrato($idcontrato, $TITLE_USUARIO_ENVIA_SOLICITUD_VALIDACION, $link);

    $estado = "";
    if( !empty($data) ){
        $estado.= $data['fecha_registra_formatted'];
    }

    if(!empty($estado)){
        return $estado;
    }else{
        return "No disponible";
    }

}

function getAprobacionPreliminarText($idcontrato, &$link){
	
	global $TITLE_RESPONSABLE_AREA_VALIDA_PRELIMINAR;
	global $TITLE_LOGISTICA_VALIDA_PRELIMINAR;
	
	$mov_aprb_resp_area = getLastMovimientoByIdContrato($idcontrato, $TITLE_RESPONSABLE_AREA_VALIDA_PRELIMINAR, $link);
	$mov_aprb_logistica = getLastMovimientoByIdContrato($idcontrato, $TITLE_LOGISTICA_VALIDA_PRELIMINAR, $link);
	$estado = "";
	if( !empty($mov_aprb_resp_area) ){
		$estado.= "Jefe área: ".$mov_aprb_resp_area['fecha_registra_formatted'];
	}
	if( !empty($mov_aprb_logistica) ){
		$estado.= "<br>Logística: ".$mov_aprb_logistica['fecha_registra_formatted'];
	}
	if(!empty($estado)){
		return $estado;
	}else{
		return "No disponible";
	}
}

function getAprobacionFinalText($idcontrato, &$link){
	
	global $TITLE_LOGISTICA_VALIDA_FINAL;
    global $TITLE_RESPONSABLE_AREA_VALIDA_FINAL;
	
	$mov_aprb_resp_area = getLastMovimientoByIdContrato($idcontrato, $TITLE_LOGISTICA_VALIDA_FINAL, $link);
	$mov_aprb_logistica = getLastMovimientoByIdContrato($idcontrato, $TITLE_RESPONSABLE_AREA_VALIDA_FINAL, $link);
	
	$estado = "";
	if( !empty($mov_aprb_resp_area) ){
		$estado.= "Jefe área: ".$mov_aprb_resp_area['fecha_registra_formatted'];
	}
	if( !empty($mov_aprb_logistica) ){
		$estado.= "<br>Logística: ".$mov_aprb_logistica['fecha_registra_formatted'];
	}
	
	if(!empty($estado)){
		return $estado;
	}else{
		return "No disponible";
	}
}

function getLastMovimientoByIdContrato($idcontrato, $title, &$link){
	
	$sql = "select top 1 convert(varchar, fecha_registra, 120) as fecha_registra_formatted from contract_movimiento where idcontrato = ".$idcontrato." and title like '".$title."' order by fecha_registra desc";

	$stmt = sqlsrv_query($link, $sql);
	
	$data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$data[] = $row;
    }
	
	if(!empty($data))
		return $data[0];
	else
		return array();
}