<?php

function exectActions_contratos(){
    exec_NoRenovacion();
    exec_renovacionmanual();
    exec_renovacionautomatica();
}

function exec_NoRenovacion(){
	$tipo_renovacion = 0;//SIN RENOVACION
	$createNewContrato = false;
	exec_renovacion($tipo_renovacion,$createNewContrato);
}

function exec_renovacionautomatica(){
	$tipo_renovacion = 1;//RENOVACION AUTOMATICA
	$createNewContrato = true;
	exec_renovacion($tipo_renovacion,$createNewContrato);
}

function exec_renovacionmanual(){
	$tipo_renovacion = 2;//RENOVACION MANUAL
	$createNewContrato = false;
	exec_renovacion($tipo_renovacion,$createNewContrato);
}

function exec_renovacion($tipo_renovacion,$createNewContrato){
    /*
     - obtener contratos vencidos y marcados con renovacion
     - enviar email notificando que vencio
     - cambiar estado de contrato a concluido
    */
    $status = "";

    $contratos_vencidos = getContratosVencidos($tipo_renovacion);
    $id_contratos_vencidos = array();
    $codigos_contratos_vencidos = array();

    foreach($contratos_vencidos as $contrato_vencido){
        $id_contratos_vencidos[] = $contrato_vencido['id'];
        $codigos_contratos_vencidos[] = create_link_vencidos($contrato_vencido);
    }
    change_estado_to_concluido_create_vinculado($id_contratos_vencidos, $createNewContrato);

    if(sizeof($contratos_vencidos) < 10){
        foreach($contratos_vencidos as $contrato_vencido){
            $status_email = sendNotification_ContratoSetConcluido($contrato_vencido['id']);

            if($status_email === true){
                $status.= "Contrato id: ".$contrato_vencido['id']." -- Exito.<br>";
            }else{
                $status.= "Contrato id: ".$contrato_vencido['id']." -- Error: ".$status_email['error']."<br>";
            }
        }
    }else{
        $body = "Los siguientes contratos fueron cerrados automaticamente tras su vencimiento,".
                "pero no se pudo enviar notificacion a los responsables debido a limitaciones en el servidor de correo.<br><br>";

        $lista_contratos_vencidos_link = implode("<br>", $codigos_contratos_vencidos);
        $body = $body.$lista_contratos_vencidos_link;
        send_email_to_legal_contratos_cerrados_no_notification($body);
    }

	
	//Send status
	if( empty($status) ){
		$status = "Data is empty.";
	}
	switch ($tipo_renovacion) {
		case 0:
			$tipo_renovacion_str = "SIN RENOVACION";
			break;
		case 1:
			$tipo_renovacion_str = "RENOVACION AUTOMATICA";
			break;
		case 2:
			$tipo_renovacion_str = "RENOVACION MANUAL";
			break;
	}
	$body = "Filtros: <br> Tipo de renovacion: ".$tipo_renovacion_str.
		         " y crear nuevo contrato es ".($createNewContrato?"true":"false")."<br><br>".
				 " ".$status;
	$title = "Status Contratos Emailer Actions ".date("Y-m-d");
	send_status_by_email($title, $body);
}

function create_link_vencidos($contrato_vencido){
    global $LINK_VER_CONTRATO;

    return $contrato_vencido['datosgenerales_codigo'] .
            " " .
            $LINK_VER_CONTRATO .
            "&id=" .
            $contrato_vencido['id'];
}