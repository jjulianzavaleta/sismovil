<?php

session_start();

if(!isset($_SESSION['login'])){
    session_destroy();
    header("location:../index.php");
}

include_once("../phps/libreriasphp/PHPExcel/baseExcel.php");
include_once("../phps/conexion.php");
include_once("../phps/dcontract_contratos.php");
include_once("../phps/dcontract_reportes.php");
include_once("../phps/dcontract_usuarios.php");

if( !isset($_REQUEST['optSelec']) ){
	die("Validacion: No data");
}

$userId        				= $_REQUEST['userId'];
$opt1          				= "checked='checked'";
$opt2          				= "";
$opt3						= "";
$chk_registrado 			= "";
$chk_val_jefarea 			= "";
$chk_val_legal_acepta 		= "";
$chk_val_jef_log 			= "";
$chk_pendelaboracion  		= "";
$chk_pendaprobacionusuario  = "";
$chk_colectarfirmas 		= "";
$chk_vigente   				= "";
$chk_concluido 				= "";
$chk_proceso   				= "";
$chk_anulado   				= "";
$chk_vence15   				= "";
$chk_vence30   				= "";
$chk_vence60   				= "";
$chk_vence90   				= "";
$chk_vence365  				= "";
$fechaIni      				= "";
$fechaFin      				= "";
$codigo        				= "";
$alcance                    = "";
$chk_porempresa				= "";
$chk_porproveedor 			= "";
$chk_portipocontrato 		= "";
$chk_porcodigo				= "";
$chk_poralcance				= "";
$select_1					= "";
$select_2					= "";
$select_3					= "";

	if($_REQUEST['optSelec'] == 1){
		
		 $opt1 = "checked='checked'";
		 
        $fechaIni = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaIni']." 00:00:00"):"";
        $fechaFin = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaFin']." 23:59:59"):"";    
		
		$chk_vigente   				= isset($_REQUEST['chk_vigente'])?"checked='checked'":"";
		$chk_concluido 				= isset($_REQUEST['chk_concluido'])?"checked='checked'":"";
		$chk_registrado 			= isset($_REQUEST['chk_registrado'])?"checked='checked'":"";
		$chk_val_jefarea 			= isset($_REQUEST['chk_val_jefarea'])?"checked='checked'":"";
        $chk_val_legal_acepta 		= isset($_REQUEST['chk_val_legal_acepta'])?"checked='checked'":"";
		$chk_val_jef_log 			= isset($_REQUEST['chk_val_jef_log'])?"checked='checked'":"";
		$chk_pendelaboracion 		= isset($_REQUEST['chk_pendelaboracion'])?"checked='checked'":"";
		$chk_pendaprobacionusuario 	= isset($_REQUEST['chk_pendaprobacionusuario'])?"checked='checked'":"";
		$chk_colectarfirmas			= isset($_REQUEST['chk_colectarfirmas'])?"checked='checked'":"";
		$chk_anulado   				= isset($_REQUEST['chk_anulado'])?"checked='checked'":"";
		
		$hide_option1= "";
		$hide_option2= " style='display:none' ";
		$hide_option3= " style='display:none' ";
		$chk_porempresa="checked='checked'";
		
    }else if($_REQUEST['optSelec'] == 2){		
       
        $opt2 	= "checked='checked'";

        $chk_porempresa			= isset($_REQUEST['rad_parametro_1'])?"checked='checked'":"";
        $chk_porproveedor		= isset($_REQUEST['rad_parametro_2'])?"checked='checked'":"";
        $chk_portipocontrato	= isset($_REQUEST['rad_parametro_3'])?"checked='checked'":"";
        $chk_porcodigo			= isset($_REQUEST['rad_parametro_4'])?"checked='checked'":"";
        $chk_poralcance			= isset($_REQUEST['rad_parametro_5'])?"checked='checked'":"";
		$select_1 				= isset($_REQUEST['select_1'])?$_REQUEST['select_1']:"";
		$select_2 				= isset($_REQUEST['select_2'])?$_REQUEST['select_2']:"";
		$select_3 				= isset($_REQUEST['select_3'])?$_REQUEST['select_3']:"";
		$codigo 				= isset($_REQUEST['codigo'])?$_REQUEST['codigo']:"";
		$alcance 				= isset($_REQUEST['alcance'])?$_REQUEST['alcance']:"";
		
		$hide_option1= " style='display:none' ";
		$hide_option2= "";
		$hide_option3= " style='display:none' ";
		
    }else if($_REQUEST['optSelec'] == 3){
		
		$opt3 	= "checked='checked'";
		
		$chk_vence15   = isset($_REQUEST['chk_vence15'])?"checked='checked'":"";
		$chk_vence30   = isset($_REQUEST['chk_vence30'])?"checked='checked'":"";
		$chk_vence60   = isset($_REQUEST['chk_vence60'])?"checked='checked'":"";
		$chk_vence90   = isset($_REQUEST['chk_vence90'])?"checked='checked'":"";
		$chk_vence365  = isset($_REQUEST['chk_vence365'])?"checked='checked'":"";
		
		$hide_option1= " style='display:none' ";
		$hide_option2= " style='display:none' ";
		$hide_option3= "";
		$chk_porempresa="checked='checked'";
	}

    $data = getAllMiContratosWithFilters($_REQUEST['optSelec'],$fechaIni,$fechaFin,$chk_vigente,$chk_concluido,$chk_registrado,$chk_val_jefarea,$chk_val_legal_acepta,$chk_val_jef_log,$chk_pendelaboracion,$chk_pendaprobacionusuario,$chk_colectarfirmas,$chk_anulado,$chk_porempresa,$chk_porproveedor,$chk_portipocontrato,$chk_porcodigo,$chk_poralcance,$select_1,$select_2,$select_3,$codigo,$chk_vence15,$chk_vence30,$chk_vence60,$chk_vence90,$chk_vence365,$alcance,$userId);


$titulos = array("Codigo",utf8_decode("Año"),"Fecha Registra","Fecha Envia SEC","USUARIO (Jefatura de Area Usuario)","SOLICITANTE (Creador SEC)","Empresa","Proveedor","Tipo Contrato","Monto","FECHA INICIO (Vigencia)","FECHA FIN (Vigencia)" ,"Aprob. Preliminar","Legal elabora contrato","Aprob. Final","Legal Final","Anular Razon","Estado");

$indices = array("datosgenerales_codigo","year_req","fecha_formateada","fecha_envia_sec","usuario_jefatura_area_usuaria","usuario_creador_sec", "nombre_empresa","nombre_proveedor","nombre_tipocontrato","monto","vigencia_inicio","vigencia_fin","aprob_preliminar","fecha_legal_elabora","aprob_final","legal_final","anulado_razon","estado");

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
header('Content-Disposition: attachment; filename="REPORTE CONTRATOS '.date('d/m/Y').'.xlsx"');

//forzar a descarga por el navegador
$objWriter->save('php://output');

/*FIN CREACION DE ARCHIVO Y EMPEZAR DESCARGA*/
