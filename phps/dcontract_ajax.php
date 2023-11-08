<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("conexion.php");
include_once("dcontract_auxiliares.php");
include_once("dcontract_contratos.php");
require 'libreriasphp/PHPMailer/PHPMailerAutoload.php';
include_once("dcontract_notificaciones.php");
include_once("dContract_permisosAdicionales.php");
include_once("mis_contratos_pdf/createPDF.php");
include_once("setup.php");

global $dir_subida_general;

$dir_subida_general = ProjectManager::rootDirectory().'/files/contratos';

global $global_error;
$global_error = "";

global $dir_subida;
$dir_subida = $dir_subida_general;

$pdf_gen_response = "";
$email_response   = "";

if (isset($_POST['cod'])) {
	
	if($_POST['cod'] == 3 || $_POST['cod'] == 4 || $_POST['cod'] == 5 || $_POST['cod'] == 8){
        $idEmpresa = isset($_POST['reqgen_a_empresa'])?$_POST['reqgen_a_empresa']:getEmpresaBySolContrato($_POST['id']);
        $codigo_contrato	= upsertCodigoConrtato($_POST['id'], $idEmpresa);

        if($_POST['cod'] == 5 && $_POST['new_estado'] == 1 && strpos($codigo_contrato, "DRAFT") !== false){
            $newCodigo_contrato	= upsertCodigoConrtato($_POST['id'], $idEmpresa, true);
            rename_contrato_folder($newCodigo_contrato, $codigo_contrato, $dir_subida_general);
            actualizarCodigoContrato($_POST['id'], $newCodigo_contrato);
            $codigo_contrato = $newCodigo_contrato;
        }

		$dir_subida 		= $dir_subida."/".$codigo_contrato."/";
		if (!file_exists($dir_subida)) {			
			mkdir($dir_subida, 0700, true);			
		}		
	}	
	
    if($_POST['cod'] == 1){
        $salida = getContract_JefeArea_byId($_POST['a']);
    }else if($_POST['cod'] == 2){
        $salida = getContract_RUCProveedor($_POST['a']);
    }else if($_POST['cod'] == 3){
		$_POST['codigo_contrato'] = $codigo_contrato;
		$data   = extract_data_regContract($_POST);		
		if($_POST['mode'] == 1){
			$salida = registrarContrato_val($data, $email_response);
		}else if($_POST['mode'] == 2){
			$salida = updateContrato_val($data);
		}        
    }else if($_POST['cod'] == 4){					
			$salida = sendContractToJefeAreaForApprove($_POST['id'],$_POST['idusuario']);
			$pdf_gen_response = create_document_pdf_format($_POST['id'],$codigo_contrato);
            $email_response = sendEmaiNotification($_POST['id'],0,0.3);
	}else if($_POST['cod'] == 5){
			$salida = save_response($_POST);
			if( $_POST['new_estado'] == 0.6 || $_POST['new_estado'] == 1 ){
				$pdf_gen_response = create_document_pdf_format($_POST['id'],$codigo_contrato);
			}
			$tipo_flujo_contrato = getTipoFlujoContrato($_POST['id']);
            $email_response = sendEmaiNotification($_POST['id'],$_POST['current_estado'],$_POST['new_estado'],$_POST['waitFirmaModo'],$tipo_flujo_contrato,$_POST['nuevoEstadoDerivacion']);
	}else if($_POST['cod'] == 6){
			$salida = anular_solcontrato($_POST['id'],$_POST['usuario'], $_POST['reason']);
            $email_response = sendEmaiNotification($_POST['id'],-1,-1);
	}else if($_POST['cod'] == 7){
			$salida = acta_entrega($_POST['id'],$_POST['usuario'],$_POST['fecha_inicio'],$_POST['fecha_fin']);			
	}else if($_POST['cod'] == 8){
			$salida = create_document_pdf_format_int($_POST['id']);	
			$pdf_gen_response = $salida;
	}else if($_POST['cod'] == 999){
            $email_response = send_test_email();
            $salida = true;
            $pdf_gen_response = "";
    }else if($_POST['cod'] == 9){
        $salida = suspendGo($_POST['id'],$_POST['usuario'],$_POST['reason']);
    }else if($_POST['cod'] == 10){
        $salida = save_derivar($_POST['idContrato'],$_POST['idUsuarioAsignado'],$_POST['idUsuarioAsigna'], $_POST['detalle']);
        $email_response = sendEmaiNotification($_POST['idContrato'],-2,-2);
    }else if($_POST['cod'] == 11){
        $salida = anular_derivar($_POST['idContrato'],$_POST['usuario'],$_POST['reason']);
    }else if($_POST['cod'] == 12){
        $salida = delete_file_attached($_POST['idContrato'], $_POST['campo'], $_POST['codigo_contrato'], $_POST['name_file']);
    }else if($_POST['cod'] == 13){
        $salida = delete_file_attached_detalle($_POST['idContrato'], $_POST['idDetalle'], $_POST['codigo_contrato'], $_POST['name_file'], $_POST['option']);
    }

    $email_response = processEmailStatus($email_response);
    if($salida === false) {
        $res = array("estado"=> 0, "error" => $global_error, "pdf_status" => $pdf_gen_response, "email_status" => $email_response);
    }else{
        $res = array("estado"=> 1,"data"=>$salida, "pdf_status" => $pdf_gen_response, "email_status" => $email_response);
    }

    if($email_response["status"] === "error"){
        sendErrorNotificationToAdmin($_POST, $res);
    }

    echo json_encode($res);
}

function save_response($data){
	
	global $dir_subida;
	$files = array();
    $date = date_create();
	
	if( $_POST["inc_file"] == 1){		
		foreach( $_FILES["archivos"]["error"] as $key => $error ){
			if( $error == UPLOAD_ERR_OK ){

                $new_file_name = date_timestamp_get($date).basename($_FILES["archivos"]["name"][$key]);
				$fichero_subido = $dir_subida . $new_file_name;
				
				$files[] = $new_file_name;
				
				move_uploaded_file( $_FILES["archivos"]["tmp_name"][$key], $fichero_subido );				
			}else{				
				global $global_error;
				$global_error = "Error al subir archivo. Codigo ".$error;
				return false;			  	
			}			
		}
	}
	
	$res = save_contrato_data($data,$files);
	return $res;
}

function registrarContrato_val($data, &$email_response){

	subir_archivos_adjuntos($data);	
	$lasId = registrarContrato($data);
	if(!empty($lasId)) {
        $email_response = sendEmaiNotification($lasId, 0, 0);
    }else{
        $email_response = false;
	}
	
	return $lasId;
}

function updateContrato_val($data){

	subir_archivos_adjuntos($data, true);
	updateContrato($data);
	return $_POST['id'];
}

function create_document_pdf_format_int($idContrato){
	$codigo_contrato  = getCodigoContrato($idContrato);
	
	if(empty($codigo_contrato))
		return false;
	
	$pdf_gen_response = create_document_pdf_format($idContrato,$codigo_contrato);
	return $pdf_gen_response;
}

function subir_archivos_adjuntos($data, $deleteOlderFiles = false){

	global $dir_subida;

	if($data['proveedor_tipo'] == 1){
		if(isset($_FILES['proveedor_jur_file_ficharuc'])){
            if($deleteOlderFiles){
                removeOlderFile($data['id'], 'jur_file_ficharuc');
            }
			$fichero_subido = $dir_subida . basename($_FILES['proveedor_jur_file_ficharuc']['name']);		
			move_uploaded_file($_FILES['proveedor_jur_file_ficharuc']['tmp_name'], $fichero_subido);
		}
		
		if(isset($_FILES['proveedor_jur_file_represetante'])){
            if($deleteOlderFiles){
                removeOlderFile($data['id'], 'jur_file_represetante');
            }
			$fichero_subido = $dir_subida . basename($_FILES['proveedor_jur_file_represetante']['name']);		
			move_uploaded_file($_FILES['proveedor_jur_file_represetante']['tmp_name'], $fichero_subido);
		}

		if(isset($_FILES['proveedor_jur_file_vigenciapoder'])){
            if($deleteOlderFiles){
                removeOlderFile($data['id'], 'jur_file_vigenciapoder');
            }
			$fichero_subido = $dir_subida . basename($_FILES['proveedor_jur_file_vigenciapoder']['name']);		
			move_uploaded_file($_FILES['proveedor_jur_file_vigenciapoder']['tmp_name'], $fichero_subido);
		}

	}else if($data['proveedor_tipo'] == 2){
		
		if(isset($_FILES['proveedor_nat_file_ficharuc'])){
            if($deleteOlderFiles){
                removeOlderFile($data['id'], 'nat_file_ficharuc');
            }
			$fichero_subido = $dir_subida . basename($_FILES['proveedor_nat_file_ficharuc']['name']);		
			move_uploaded_file($_FILES['proveedor_nat_file_ficharuc']['tmp_name'], $fichero_subido);
		}
		
		if(isset($_FILES['proveedor_nat_file_represetante'])){
            if($deleteOlderFiles){
                removeOlderFile($data['id'], 'nat_file_represetante');
            }
			$fichero_subido = $dir_subida . basename($_FILES['proveedor_nat_file_represetante']['name']);		
			move_uploaded_file($_FILES['proveedor_nat_file_represetante']['tmp_name'], $fichero_subido);
		}
	}
	
	if(isset($_FILES['contrato_propuesto_proveedor'])){
        if($deleteOlderFiles){
            removeOlderFile($data['id'], 'contrato_propuesto_proveedor');
        }
		$fichero_subido = $dir_subida . basename($_FILES['contrato_propuesto_proveedor']['name']);		
		move_uploaded_file($_FILES['contrato_propuesto_proveedor']['tmp_name'], $fichero_subido);
	}
	
	if(isset($_FILES['modalidadpago_adelanto_adelantofile'])){
        if($deleteOlderFiles){
            removeOlderFile($data['id'], 'modalidadpago_adelanto_adelantofile');
        }
		$fichero_subido = $dir_subida . basename($_FILES['modalidadpago_adelanto_adelantofile']['name']);		
		move_uploaded_file($_FILES['modalidadpago_adelanto_adelantofile']['tmp_name'], $fichero_subido);
	}
	
	if(isset($_FILES['metas_cumplir_entregables'])){
        if($deleteOlderFiles){
            removeOlderFile($data['id'], 'metas_cumplir_entregables');
        }
		$fichero_subido = $dir_subida . basename($_FILES['metas_cumplir_entregables']['name']);		
		move_uploaded_file($_FILES['metas_cumplir_entregables']['tmp_name'], $fichero_subido);
	}
	
	if(isset($_FILES['contraprestacion_file'])){
        if($deleteOlderFiles){
            removeOlderFile($data['id'], 'contraprestacion_file');
        }
		$fichero_subido = $dir_subida . basename($_FILES['contraprestacion_file']['name']);		
		move_uploaded_file($_FILES['contraprestacion_file']['tmp_name'], $fichero_subido);
	}
	
	if( isset($_FILES["archivos_inmuebles_tp"]["error"]) ){
		foreach( $_FILES["archivos_inmuebles_tp"]["error"] as $key => $error ){
			if( $error == UPLOAD_ERR_OK ){
				$fichero_subido = $dir_subida . basename($_FILES["archivos_inmuebles_tp"]["name"][$key]);
				move_uploaded_file( $_FILES["archivos_inmuebles_tp"]["tmp_name"][$key], $fichero_subido );				
			}else{
				echo "Error al subir archivo. Codigo ".$error;			
			}		
		}		
	}		
	
	if( isset($_FILES["archivos_inmuebles_cg"]["error"]) ){
		foreach( $_FILES["archivos_inmuebles_cg"]["error"] as $key => $error ){
			if( $error == UPLOAD_ERR_OK ){
				$fichero_subido = $dir_subida . basename($_FILES["archivos_inmuebles_cg"]["name"][$key]);
				move_uploaded_file( $_FILES["archivos_inmuebles_cg"]["tmp_name"][$key], $fichero_subido );				
			}else{
				echo "Error al subir archivo. Codigo ".$error;			
			}		
		}
	}
	
	if( isset($_FILES["archivos_sct2"]["error"]) ){
		foreach( $_FILES["archivos_sct2"]["error"] as $key => $error ){
			if( $error == UPLOAD_ERR_OK ){
				$fichero_subido = $dir_subida . basename($_FILES["archivos_sct2"]["name"][$key]);
				move_uploaded_file( $_FILES["archivos_sct2"]["tmp_name"][$key], $fichero_subido );				
			}else{
				echo "Error al subir archivo. Codigo ".$error;			
			}		
		}
	}
	
	if( isset($_FILES["archivos_OA1"]["error"]) ){
		foreach( $_FILES["archivos_OA1"]["error"] as $key => $error ){
			if( $error == UPLOAD_ERR_OK ){
				$fichero_subido = $dir_subida . basename($_FILES["archivos_OA1"]["name"][$key]);
				move_uploaded_file( $_FILES["archivos_OA1"]["tmp_name"][$key], $fichero_subido );				
			}else{
				echo "Error al subir archivo. Codigo ".$error;			
			}		
		}
	}
	
}

function processEmailStatus($email_response){
    if($email_response === true){
        return array(
            "status" => "exito",
            "msg"  =>  ""
        );
    }else if($email_response === false || $email_response === ""){
        return array(
            "status" => "exito",
            "msg"  =>  "No se envió ningun email"
        );
    }else if(is_array($email_response) && !empty($email_response)){
        return array(
            "status" => "error",
            "error"  => $email_response['error']
        );
    }else{
        return array(
            "status" => "error",
            "error"  =>  "No se pudo procesar la respuesta"
        );
    }
}

function sendErrorNotificationToAdmin($post_data, $response_data){
    $msg = array( "REQUEST_data" => $post_data, "RESPONSE_Data" => $response_data);
    sendNotification_ErrorAdmin(json_encode($msg));
}

function send_test_email(){
    $msg = "Test email sent to validate emailer feature. All ok.";
    $result = sendTestNotification_Admin($msg);

    return  $result;

}
