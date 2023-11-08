<?php
session_start();

if(!isset($_SESSION['login'])){
    session_destroy();
    header("location:../index.php");
}

include_once("../phps/conexion.php");
include("../phps/dvales_rendimientoEstandar.php");
include_once("../phps/libreriasphp/PHPExcel/Classes/PHPExcel.php");


if( isset($_FILES['archivo']['tmp_name']) ){

	 
	$tmpfname = $_FILES['archivo']['tmp_name'];
	
	$excelReader_ = PHPExcel_IOFactory::createReader('Excel2007');
	$excelReader = $excelReader_->load($tmpfname);
	
	$excelReader->setActiveSheetIndex(0);
	
	$worksheet   = $excelReader->getSheet(0);
	$lastRow     = $worksheet->getHighestRow();
	
	$array_update = array();
		
	for ($row = 2; $row <= $lastRow; $row++) {
		
		$placa_string 			= $worksheet->getCell('A'.$row)->getValue();
		$ruta_string 			= $worksheet->getCell('B'.$row)->getValue();
		$re_string 				= $worksheet->getCell('C'.$row)->getValue();
		$found					= false;
		
		if( !empty($placa_string) && !empty($re_string) ){
			$idequipo = getEquipoByPlaca_re($placa_string);
			
			if( !empty($idequipo) ){
				$found = true;
				$placa = strtoupper(trim($placa_string));
				$array_update[] = array( "idequipo" => $idequipo, "ruta" => $ruta_string, "re" => $re_string, "placa" => $placa );
			}
		}		
	}
	
	$success = false;
	if( !empty($array_update) ){
		$success = update_equipos_rendimieinto_estandar($array_update,$_SESSION['id']);
	}
	
	for ($row = 2; $row <= $lastRow; $row++) {
		
		$placa_string 			= $worksheet->getCell('A'.$row)->getValue();
		
		if( !empty($placa_string) ){
			$placa = strtoupper(trim($placa_string));
			if( array_search($placa, array_column($array_update,"placa")) !== false){
				set_estado($success, $worksheet, 'D'.$row);
			}else{
				set_estado(false, $worksheet, 'D'.$row);				
			}			
		}else{
			set_estado(false, $worksheet, 'D'.$row);
		}
		
	}
	
	$objWriter = new PHPExcel_Writer_Excel2007($excelReader);

	header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment; filename="Resultado procesado Rendimiento Estandar.xlsx"');
	$objWriter->save('php://output');
}

function set_estado($procesada, &$worksheet, $position){
	  
	$COLOR_RED_BK = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000')
        )
    );
	
	$COLOR_GREEN_BK = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '009900')
        )
    );
	  
	  
	  if($procesada){
		$worksheet->getStyle($position)->applyFromArray($COLOR_GREEN_BK);
		$worksheet->setCellValue($position, "PROCESADA");
	  }else{
		$worksheet->getStyle($position)->applyFromArray($COLOR_RED_BK);
		$worksheet->setCellValue($position, "NO PROCESADA");
	  }
	  
  }