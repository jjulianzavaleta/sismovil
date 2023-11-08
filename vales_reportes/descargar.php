<?php

ini_set('memory_limit', '1024M');

include_once("../phps/libreriasphp/PHPExcel/baseExcel.php");
include_once("../phps/conexion.php");
include_once("../phps/dvales_reportes.php");

if( !isset($_REQUEST['optSelec']) ){
	die("Validacion: No data");
}

$opt1          				= "checked='checked'";
$opt2          				= "";
$chk_registrado 			= "";
$chk_emitido  				= "";
$chk_consumido  			= "";
$chk_anulado   				= "";
$fechaIni      				= "";
$fechaFin      				= "";
$observacion                = "";
$chk_emitido				= "";
$chk_placa 					= "";
$chk_chofer					= "";
$chk_centrocosto 			= "";
$chk_grifo		 			= "";
$chk_observacion			= "";
$select_1					= "";
$select_2					= "";
$select_3					= "";
$select_4                   = "";

    if($_REQUEST['optSelec'] == 1){
		
		 $opt1 = "checked='checked'";
		 
        $fechaIni = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaIni']." 00:00:00"):"";
        $fechaFin = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaFin']." 23:59:59"):"";    
		
		$chk_registrado   		= isset($_REQUEST['chk_registrado'])?"checked='checked'":"";
		$chk_emitido 			= isset($_REQUEST['chk_emitido'])?"checked='checked'":"";
		$chk_consumido 			= isset($_REQUEST['chk_consumido'])?"checked='checked'":"";
		$chk_anulado   			= isset($_REQUEST['chk_anulado'])?"checked='checked'":"";
		
		
    }else if($_REQUEST['optSelec'] == 2){		
       
        $opt2 	= "checked='checked'";		
		$select_1 				= isset($_REQUEST['select_1'])?$_REQUEST['select_1']:"";
		$select_2 				= isset($_REQUEST['select_2'])?$_REQUEST['select_2']:"";
		$select_3 				= isset($_REQUEST['select_3'])?$_REQUEST['select_3']:"";
		$select_4 				= isset($_REQUEST['select_4'])?$_REQUEST['select_4']:"";
		$chk_placa 				= $_REQUEST['rad_parametro']==1?"checked='checked'":"";
		$chk_chofer				= $_REQUEST['rad_parametro']==2?"checked='checked'":"";
		$chk_centrocosto 		= $_REQUEST['rad_parametro']==3?"checked='checked'":"";
		$chk_grifo		 		= $_REQUEST['rad_parametro']==4?"checked='checked'":"";
		$chk_observacion		= $_REQUEST['rad_parametro']==5?"checked='checked'":"";
		$observacion			= isset($_REQUEST['observacion'])?$_REQUEST['observacion']:"";		
			
    }

    $data = getAllMiValesWithFilters($_REQUEST['optSelec'],$fechaIni,$fechaFin,$chk_registrado,$chk_emitido,$chk_consumido,$chk_anulado,$chk_placa,$chk_chofer,$chk_centrocosto,$chk_grifo,$chk_observacion,$select_1,$select_2,$select_3,$select_4,$observacion,true);


$titulos = array("ID","TIPO FLUJO","FECHA REGISTRO","HORA REGISTRO","USUARIO REGISTRA","FECHA ULTIMA MODIFICACION","HORA ULTIMA MODIFICACION","USUARIO ULTIMA MODIFICACION","FECHA EMITE","HORA EMITE","USUARIO EMITE","FECHA CONSUMO","HORA CONSUMO","USUARIO CONSUMO","MATERIAL","OBSERVACION","PLACA","EQUIPO CODIGO","VOUCHER","KM","CANTIDAD CONSUMO","CHOFER","CHOFER DNI","COPILOTO","COPILOTO DNI","CENTRO DE COSTO","GRIFO","ESTADO","TIPO CONTADOR","TSOMobile status","TSOMobile response","RFC status","RFC response");

$indices = array("id","tipo_flujo","registra_fecha","registra_hora","registra_usuario","modifica_fecha","modifica_hora","modifica_usuario","emite_fecha","emite_hora","emite_usuario","consumo_fecha","consumo_hora","consume_usuario","material","consumo_observacion","placa","equipo_codigo","nrovoucher","consume_kilometraje","cantidad_consumida","chofer","chofer_dni","copiloto_dni","copiloto","centrocosto","grifo","estado","contador","tsomobile_status","tsomobil_response","rfc_status","rfc_response");

$objPHPExcel = new PHPExcel(); //nueva instancia
$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(85);
$objPHPExcel->getActiveSheet()->setTitle('DATA');
generateTableNxN_normal($objPHPExcel,$titulos,$data,$indices,0,1,true);

/*INICIO CREACION DE ARCHIVO Y EMPEZAR DESCARGA*/

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// Establecer formado de Excel 2007
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// nombre del archivo
header('Content-Disposition: attachment; filename="REPORTE VALES '.date('d/m/Y').'.xlsx"');

//forzar a descarga por el navegador
$objWriter->save('php://output');

/*FIN CREACION DE ARCHIVO Y EMPEZAR DESCARGA*/
