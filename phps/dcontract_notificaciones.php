<?php

//require "conexion.php";
require "Emailer.php";

if(file_exists('setup.php'))
    include_once("setup.php");

global $LINK_VER_CONTRATO;
$LINK_VER_CONTRATO = "Ver/Imprimir detalles en: ".ProjectManager::projectURL()."/contract_miscontratos/redirect.php?email=true";

function sendEmaiNotification($idcontrato,$estado_old,$estado_new,$waitFirmaModo=0,$tipo_flujo_contrato=1,$nuevoEstadoDerivacion=-1){
	$res = false;
    switch($estado_new){
        case -2:
            $res = sendNotification_NuevaDerivacion($idcontrato);
            break;
		case -1:
            $res = sendNotification_ContratoAnulado($idcontrato);
			break;
		case 0.3:
            $res = sendNotification_ContratoAprobacionResponsableArea($idcontrato);
			break;
		case 0.6:
			sendNotification_ContratoAprobacionJefaturaLogistica($idcontrato);
			break;
        case 0.8:
            sendNotification_ContratoLegalAceptaElaboracion($idcontrato);
            break;
		case 0:
			if($estado_old == 1)
                $res = sendNotification_ContratoObservadoPorLegalRegistrado($idcontrato);
			else if($estado_old == 0.3 || $estado_old == 0.6 || $estado_old == 0.8 )
                $res = sendNotification_ContratoObservado($idcontrato);
			else
                $res = sendNotification_ContratoRegistrado($idcontrato);
			break;
		case 1:
			if($estado_old == 2)
                $res = sendNotification_ContratoObservadoPorUsuarioElaboracionLegal($idcontrato);
            else if($estado_old == 1 && $nuevoEstadoDerivacion == 0)
                $res = sendNotification_DerivacionObservada($idcontrato);
            else if($estado_old == 1 && $nuevoEstadoDerivacion == 1)
                $res = sendNotification_DerivacionEsperaValidacion($idcontrato);
            else
                $res = sendNotification_ContratoElaboracionLegal($idcontrato);

			break;
		case 2:
			if($tipo_flujo_contrato == 2)
                $res = sendNotification_ContratoAprobacionResponsableAreaFinal($idcontrato);
			else if($estado_old == 2){
                $res = sendNotification_ContratoAprobacionParcial($idcontrato);
			}else{
                if($nuevoEstadoDerivacion == 2)
                    sendNotification_DerivacionValidacionOk($idcontrato);

                $res = sendNotification_ContratoAprobacionDoblePendiente($idcontrato);
			}
				
			break;
		case 3:
            $res = sendNotification_ContratoRecoleccionFirmas($idcontrato,$waitFirmaModo);
			break;
		case 4:
            $res = sendNotification_ContratoSetVigente($idcontrato);
			break;
	}

	return $res;
}

function isNotificationEnabled(){
	return true;
}

function sendNotification_ContratoAnulado($idcontrato){
	
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);	
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);			
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica']) && $contract['tipo_flujo'] == 1 )
			addCC($mail,$contract['logistica']);
		
		if( !isset($contract['tipocontrato']) || empty($contract['tipocontrato']) ){
			$contract['tipocontrato'] = "No especificado";
		}
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato ANULADA<br>Tipo contrato: ".$contract['tipocontrato'].$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoRegistrado($idcontrato){
	
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato creada".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoAprobacionResponsableArea($idcontrato){
	
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato creada. Require aprobacion preliminar por responsable area usuaria.".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoAprobacionJefaturaLogistica($idcontrato){
	
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);

		if( !empty($contract['logistica']) )
			addCC($mail,$contract['logistica']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato creada. Require aprobacion preliminar por responsable area logistica.".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoElaboracionLegal($idcontrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato enviada a Legal para elaboración".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoLegalAceptaElaboracion($idcontrato){
    if( isNotificationEnabled() ){
        $contract = getEmailsFromContract($idcontrato);

        $mail = create_smtp_creation();

        $location = $contract['codigo']."/".$contract['codigo'].".pdf";
        addAttachment($mail,$location);

        addEmailDestination($mail,$contract['compradorresponsable']);

        if( !empty($contract['areausuaria_jefatura']) )
            addCC($mail,$contract['areausuaria_jefatura']);

        if( !empty($contract['areasolicitante_jefatura']) )
            addCC($mail,$contract['areasolicitante_jefatura']);

        if( !empty($contract['legal']) )
            addCC($mail,$contract['legal']);

        global $LINK_VER_CONTRATO;
        $link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;

        $body = utf8_decode("En espera de que legal acepte la solicitud de elaboración".$link);
        setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);

        return sendEmail($mail);
    }else{
        return false;
    }
}

function sendNotification_ContratoObservadoPorLegalRegistrado($idcontrato){
	
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato observada por Legal".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoObservado($idcontrato){
	
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato observada".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoAprobacionResponsableAreaFinal($idcontrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato esperando aprobación final por responsable área".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoAprobacionParcial($idcontrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);		
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato recibió aprobación final 1 de 2".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoAprobacionDoblePendiente($idcontrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica']) )
			addCC($mail,$contract['logistica']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato esperando aprobación final por responsable área y logística".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoObservadoPorUsuarioElaboracionLegal($idcontrato){

	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		
		$location = $contract['codigo']."/".$contract['codigo'].".pdf";
		addAttachment($mail,$location);
		
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato observada por Usuario".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoRecoleccionFirmas($idcontrato,$waitFirmaModo){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica'])  && $contract['tipo_flujo'] == 1  )
			addCC($mail,$contract['logistica']);
		
		if($waitFirmaModo==1){
			$body = utf8_decode("Solicitud de contrato subió firma de Proveedor");
		}else if($waitFirmaModo==2){
			$body = utf8_decode("Solicitud de contrato subió firmas de Chimú");
		}else{
			$body = utf8_decode("Solicitud de contrato esperando recoleccion de firmas");
		}
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = $body.$link;
		
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoSetVigente($idcontrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
	
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica'])  && $contract['tipo_flujo'] == 1  )
			addCC($mail,$contract['logistica']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Solicitud de contrato cambió a estado Vigente".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoSetConcluido($idcontrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
		
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica'])  && $contract['tipo_flujo'] == 1 )
			addCC($mail,$contract['logistica']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Contrato venció. Estado cambió a Concluido".$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoRenovacionAutomatica($idcontrato,$referenia,$new_contrato){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
		
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica'])  && $contract['tipo_flujo'] == 1 )
			addCC($mail,$contract['logistica']);
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		
		$body = utf8_decode("Contrato ".$new_contrato." creado por renovación automática. Contrato referencia: ".$referenia.$link);
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ContratoPorVencer($idcontrato,$days){
	if( isNotificationEnabled() ){
		$contract = getEmailsFromContract($idcontrato);
		
		$mail = create_smtp_creation();
		addEmailDestination($mail,$contract['compradorresponsable']);
		
		if( !empty($contract['areausuaria_jefatura']) )
			addCC($mail,$contract['areausuaria_jefatura']);
		
		if( !empty($contract['areasolicitante_jefatura']) )
			addCC($mail,$contract['areasolicitante_jefatura']);	
		
		if( !empty($contract['legal']) )
			addCC($mail,$contract['legal']);
		
		if( !empty($contract['logistica'])  && $contract['tipo_flujo'] == 1  )
			addCC($mail,$contract['logistica']);
		
		if($days>=0){
			$body = utf8_decode("Alerta: Contrato por vencer en ".$days." días.");
		}else{
			$body = utf8_decode("Alerta: Contrato venció hace ".abs($days)." días.");
		}
		
		global $LINK_VER_CONTRATO;
		$link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;
		$body = $body.$link;
		
		setEmailData($mail,"Notificacion SEC ".$contract['codigo'],$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
}

function sendNotification_ErrorAdmin($msg){

    $mail = create_smtp_creation();
    addEmailDestination($mail,ProjectManager::adminEmail());
    $body = utf8_decode($msg);
    setEmailData($mail,"ERROR AL ENVIAR NOTIFICACION SISTEMA CONTRATOS",$body);

    sendEmail($mail);

}

function sendNotification_NuevaDerivacion($idcontrato){
    if( isNotificationEnabled() ){
        $data = getEmailsForDerivacion($idcontrato);

        $mail = create_smtp_creation();
        addEmailDestination($mail,$data['usuarioasignado_email']);

        if( !empty($data['usuarioderiva_email']) )
            addCC($mail,$data['usuarioderiva_email']);

        global $LINK_VER_CONTRATO;
        $link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;

        $body = utf8_decode("El usuario ".$data['usuarioderiva']." le derivó el contrato ".$data['codigo']." según flujo del area de legal ".$link);
        setEmailData($mail,"Notificacion SEC ".$data['codigo'],$body);

        return sendEmail($mail);
    }
}

function sendNotification_DerivacionObservada($idcontrato){
    if( isNotificationEnabled() ){
        $data = getEmailsForDerivacion($idcontrato);

        $mail = create_smtp_creation();
        addEmailDestination($mail,$data['usuarioasignado_email']);

        if( !empty($data['usuarioderiva_email']) )
            addCC($mail,$data['usuarioderiva_email']);

        global $LINK_VER_CONTRATO;
        $link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;

        $body = utf8_decode("El usuario ".$data['usuarioderiva']." observó su progreso respecto a la derivación asignada del contrato ".$data['codigo']." según flujo del area de legal ".$link);
        setEmailData($mail,"Notificacion SEC ".$data['codigo'],$body);

        return sendEmail($mail);
    }
}

function sendNotification_DerivacionEsperaValidacion($idcontrato){
    if( isNotificationEnabled() ){
        $data = getEmailsForDerivacion($idcontrato);

        $mail = create_smtp_creation();
        addEmailDestination($mail,$data['usuarioderiva_email']);

        if( !empty($data['usuarioasignado_email']) )
            addCC($mail,$data['usuarioasignado_email']);

        global $LINK_VER_CONTRATO;
        $link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;

        $body = utf8_decode("El usuario ".$data['usuarioasignado']." completó la derivación asignada del contrato ".$data['codigo']." según flujo del area de legal. Su visto bueno es necesario. ".$link);
        setEmailData($mail,"Notificacion SEC ".$data['codigo'],$body);

        return sendEmail($mail);
    }
}

function sendNotification_DerivacionValidacionOk($idcontrato){
    if( isNotificationEnabled() ){
        $data = getEmailsForDerivacion($idcontrato);

        $mail = create_smtp_creation();
        addEmailDestination($mail,$data['usuarioasignado_email']);

        if( !empty($data['usuarioderiva_email']) )
            addCC($mail,$data['usuarioderiva_email']);

        global $LINK_VER_CONTRATO;
        $link = "<br><br>".$LINK_VER_CONTRATO."&id=".$idcontrato;

        $body = utf8_decode("El usuario ".$data['usuarioderiva']." dio su visto bueno a su progreso respecto a la derivación asignada del contrato ".$data['codigo']." según flujo del area de legal ".$link);
        setEmailData($mail,"Notificacion SEC ".$data['codigo'],$body);

        return sendEmail($mail);
    }

}

function sendTestNotification_Admin($msg){

    $mail = create_smtp_creation();
    addEmailDestination($mail,ProjectManager::adminEmail());
    addCC($mail,"canessaalvamiguel@gmail.com");
    $body = utf8_decode($msg);
    setEmailData($mail,"This is a test email",$body);

    return sendEmail($mail);

}


function getEmailFromArea($idarea){
	
	$sql = "select correo from contract_area where id = ".$idarea;
			
	$link = conectarBD();
    $data = queryBD($sql,$link);

	if(!empty($data)){
		return $data[0]['correo'];
	}else{
		return "";
	}
}

function getEmailFromUserv2($username){
    $sql = "select correo from contract_usuarioshabilitados where usuario = '".$username."'";

    $link = conectarBD();
    $data = queryBD($sql,$link);

    if(!empty($data)){
        return $data[0]['correo'];
    }else{
        return "";
    }
}

function getEmailFromUser($idusuario){
	
	$sql = "select correo from contract_usuarioshabilitados where id = ".$idusuario;
			
	$link = conectarBD();
    $data = queryBD($sql,$link);

	if(!empty($data)){
		return $data[0]['correo'];
	}else{
		return "";
	}
}

function getEmailsForDerivacion($idcontrato){

    $sql = "select cs.datosgenerales_codigo,
            (select usuario from admin where id =cdl.idusuarioasignado ) as usuarioasignado,
            (select usuario from admin where id =cdl.idusuarioderiva ) as usuarioderiva
            from contract_derivacioneslegal cdl
            inner join contract_solcontrato cs on cs.id = cdl.idcontrato
            where cdl.idcontrato = ".$idcontrato." and cdl.anulado = 0 and estado < 2";

    $link = conectarBD();
    $data = queryBD($sql,$link);

    if(!empty($data)){
        return array(
            "usuarioasignado_email" 	=> getEmailFromUserv2(removeDomain($data[0]['usuarioasignado'])),
            "usuarioderiva_email"    	=> getEmailFromUserv2(removeDomain($data[0]['usuarioderiva'])),
            "usuarioderiva" 	        => removeDomain($data[0]['usuarioderiva']),
            "usuarioasignado"           => removeDomain($data[0]['usuarioasignado']),
            "codigo"              		=> $data[0]['datosgenerales_codigo']
        );
    }else{
        return false;
    }
}

function removeDomain($username){
    $data__ = explode("@", $username);
    return $data__[0];
}

function getEmailsFromContract($id){

	$sql = "select cs.reqgen_a_compradorresponsable, 
				   cs.reqgen_a_areausuaria,
				   cs.reqgen_a_areausuaria_jefatura,
				   cs.reqgen_a_areasolicitante_jefatura,
				   cs.datosgenerales_codigo,
                   ct.descripcion as tipocontrato,
				   cs.tipo_flujo
	        from contract_solcontrato cs
			left join contract_tipocontrato ct on ct.id = cs.termiesp_a_tipocontrato
			where cs.id = ".$id;
			
	$link = conectarBD();
    $data = queryBD($sql,$link);
	
	if(!empty($data)){
		return array(
		"compradorresponsable" 		=> getEmailFromUser($data[0]['reqgen_a_compradorresponsable']),
		"areausuaria_jefatura"    	=> getEmailFromUser($data[0]['reqgen_a_areausuaria_jefatura']),
		"areasolicitante_jefatura" 	=> getEmailFromUser($data[0]['reqgen_a_areasolicitante_jefatura']),
		"legal"               		=> ProjectManager::legalEmail(),
		"logistica"           		=> ProjectManager::logisticaEmail(),
		"codigo"              		=> $data[0]['datosgenerales_codigo'],
		"tipo_flujo"				=> $data[0]['tipo_flujo'],
		);
	}else{
		return false;
	}
}

function send_email_to_legal_contratos_cerrados_no_notification($body){

    if( isNotificationEnabled() ){

        $mail = create_smtp_creation();
        addEmailDestination($mail,ProjectManager::legalEmail());

        setEmailData($mail,"Notificacion de correos cerrados",$body);

        return sendEmail($mail);
    }else{
        return false;
    }

}

function send_status_by_email($title, $body){
	
	if( isNotificationEnabled() ){		
		
		$mail = create_smtp_creation();
		addEmailDestination($mail,"contratos@chimuagropecuaria.com.pe");
		
		setEmailData($mail,$title,$body);
		
		return sendEmail($mail);
	}else{
		return false;
	}
	
}