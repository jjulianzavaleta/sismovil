<?php

include_once("../phps/conexion.php");
include("../phps/dValesLinkSAP.php");
include_once("../phps/libreriasphp/PHPExcel/Classes/PHPExcel.php");


if( isset($_FILES['archivo']['tmp_name']) ){

	 
	$tmpfname = $_FILES['archivo']['tmp_name'];
	
	$excelReader_ = PHPExcel_IOFactory::createReader('Excel2007');
	$excelReader = $excelReader_->load($tmpfname);
	
	$excelReader->setActiveSheetIndex(0);
	
	$worksheet   = $excelReader->getSheet(0);
	$lastRow     = $worksheet->getHighestRow();
	
	$ids_detallemateriales_procesdos = array();	
	$array_contador_field = array();
		
	for ($row = 4; $row <= $lastRow; $row++) {
		
		$idvale_string 			= $worksheet->getCell('T'.$row)->getValue();
		$placa_string 			= $worksheet->getCell('D'.$row)->getValue();
		$centrocosto_string		= $worksheet->getCell('G'.$row)->getValue();
		$conductor_string 		= $worksheet->getCell('H'.$row)->getValue();
		$documento_string 		= $worksheet->getCell('I'.$row)->getValue();
		$fechaconsumo_string 	= $worksheet->getCell('J'.$row)->getValue();
		$codigo_sap_string 		= $worksheet->getCell('K'.$row)->getValue();
		$producto_string 		= $worksheet->getCell('L'.$row)->getValue();
		$cantidad_string 		= $worksheet->getCell('M'.$row)->getValue();//se guardan en db		
		$precio_string			= $worksheet->getCell('O'.$row)->getValue();//se guardan en db		
		
		$vale_data_temp         = get_vale_data_g($idvale_string,$placa_string,$documento_string,$fechaconsumo_string,$codigo_sap_string);
		$vale_data = array();
		
		if( $vale_data_temp['found'] === false){
			set_estado(false, $worksheet, 'Z'.$row);
			continue;
		}else{
			$vale_data = $vale_data_temp['data'];
			set_estado(true, $worksheet, 'Z'.$row, $vale_data_temp['method']);
		}
		
		$detalle_vale_materiales   = get_detallevalemateriales_g($vale_data['id']);
		$detalle_vale_asignaciones = get_asignacionesmateriales_g($vale_data['id']);
		$method                    = $vale_data_temp['method'];
		
		$data_validar_materiales = array( "cod_sap" => $codigo_sap_string,
										  "nombre"  => $producto_string,
										  "cantidad_chofer"   => $cantidad_string);
										  
		$data_validar_asignaciones = array( "cod_sap" => $codigo_sap_string,
										    "centrocosto"  => $centrocosto_string);		
			
		/*********************************Valido items procesados para no volverlos a procesar******************/
		$position_aux = validar_detalle_producto_item($detalle_vale_materiales, $data_validar_materiales, true);
		
		if($position_aux >= 0){
			$id_detalle_material = $detalle_vale_materiales[$position_aux]['id'];
			
			if (in_array($id_detalle_material, $ids_detallemateriales_procesdos)){
				set_estado(false, $worksheet, 'Z'.$row);
				continue;
			}else{
				$ids_detallemateriales_procesdos[] = $id_detalle_material;
			}
		}
		
		
		/*******************************************************************************************************/
		
		if( $method == 1 ){//encontro por voucher_nro
			
			$new_value = get_correct_value_from_vale($vale_data, $detalle_vale_materiales, $detalle_vale_asignaciones, $placa_string, "placa");
			$position  = 'D'.$row;
			set_value_and_format_cell($position, $new_value, $worksheet );
			
			$new_value = get_correct_value_from_vale($vale_data, $detalle_vale_materiales, $detalle_vale_asignaciones, $documento_string, "documento");
			$position  = 'H'.$row;
			set_value_and_format_cell($position, $new_value, $worksheet );
			
			$new_value = get_correct_value_from_vale($vale_data, $detalle_vale_materiales, $detalle_vale_asignaciones, $fechaconsumo_string, "fechaconsumo");
			$position  = 'I'.$row;
			set_value_and_format_cell($position, $new_value, $worksheet );
			
		}
		
		$new_value = get_correct_value_from_vale($vale_data, $detalle_vale_materiales, $detalle_vale_asignaciones, $conductor_string, "conductor");
		$position  = 'G'.$row;
		set_value_and_format_cell($position, $new_value, $worksheet );
		
		$new_value = get_correct_value_from_vale($vale_data, $detalle_vale_materiales, $detalle_vale_asignaciones, $data_validar_asignaciones, "centrocosto");
		$position  = 'F'.$row;
		set_value_and_format_cell($position, $new_value, $worksheet );		
		
		$new_value = get_correct_value_from_vale($vale_data, $detalle_vale_materiales, $detalle_vale_asignaciones, $data_validar_materiales, "codigo_sap");
		$position  = array( 'K'.$row , 'L'.$row );
		set_value_and_format_cell_materiales($position, $new_value, $worksheet );
		
		$position = $row;
		$unidad_medida = $vale_data['consumo_unidadmedida']==0?"Kilometraje":($vale_data['consumo_unidadmedida']==1?"Hódometro":"");		
		$array_contador_field[] = array("position" => $position, "unidad_medida" => $unidad_medida);
		
		//save to db
		$id_item = $detalle_vale_materiales[$position_aux]['id'];/*id del producto en vales_detalle_productos*/
		save_to_db($vale_data['id'], $cantidad_string, $precio_string, $id_item);
		
	}	
	
	//ADD COLUM CONTADOR
	$worksheet->insertNewColumnBefore('E', 1);
	
	$COLOR_YELOW_BK = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ffff00')
			)
		);
	$position = 'E1';	
	$worksheet->setCellValue($position, "Contador");
	$worksheet->getStyle($position)->applyFromArray($COLOR_YELOW_BK);
	
	foreach($array_contador_field as $contador_colum){
		$worksheet->setCellValue("E".$contador_colum['position'], $contador_colum['unidad_medida']);
	}
	
	//SAVE PDF
    //$objWriter = PHPExcel_IOFactory::createWriter($excelReader, 'Excel2007');
	$objWriter = new PHPExcel_Writer_Excel2007($excelReader);

	header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment; filename="file.xlsx"');
	$objWriter->save('php://output');

}

  function get_correct_value_from_vale($vale_data, &$detalle_vale_materiales, $detalle_vale_asignaciones, $value_excel, $identifier_data){
	  
	  if( empty($vale_data) || empty($detalle_vale_materiales) || empty($detalle_vale_asignaciones) ){		 
		  return false;
	  }
	  
	  switch($identifier_data){
		  
		  case "placa":		  
			if( $vale_data['placa'] == $value_excel)
				return false;
			else
				return $vale_data['placa'];
			
		   break;
		   
		  case "conductor":		  
		    if( $vale_data['chofer'] == $value_excel)
				return false;
			else
				return $vale_data['chofer'];
		  
		   break;
		   
		  case "documento":		  
			if( $vale_data['chofer_dni'] == $value_excel)
				return false;
			else
				return $vale_data['chofer_dni'];
			
		   break;
		   
		  case "fechaconsumo":		  
			if( $vale_data['fechaconsumo'] == $value_excel)
				return false;
			else
				return $vale_data['fechaconsumo'];
		  
		   break;
		   
		  case "codigo_sap":		  
			return validar_detalle_producto_item($detalle_vale_materiales, $value_excel);
			
		   break;
		   
		   case "centrocosto":
				if( sizeof($detalle_vale_asignaciones) == sizeof($detalle_vale_materiales) ){
					return validar_detalle_asignacion($detalle_vale_asignaciones, $value_excel);
				}else{
					return "Error, modo de asignaciones no soportada";
				}
		   break;
	  }
	  
	  return false;
	  
  }
	
  function set_value_and_format_cell($position, $new_value, &$worksheet ){

	  $COLOR_BLUESKY_BK = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '7EC0EE')
			)
		);
	  $COLOR_RED_BK = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF0000')
			)
		);
		
		if($new_value === false){			
		}else if($new_value == -1){
			$worksheet->setCellValue($position, "Error");			
			$worksheet->getStyle($position)->applyFromArray($COLOR_RED_BK);
		}else{
			$worksheet->setCellValue($position, $new_value);			
			$worksheet->getStyle($position)->applyFromArray($COLOR_BLUESKY_BK);			
		}	
  }
  
  function set_value_and_format_cell_materiales($position, $new_value, $worksheet ){
	  
	  $COLOR_BLUESKY_BK = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '7EC0EE')
			)
		);
		
    	$COLOR_RED_BK = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF0000')
			)
		);
		
		if($new_value === false){//Valores eran correctos			
		
		}else if($new_value == -1){//Producto no encontrado en el vale
			
			$worksheet->setCellValue($position[0], "Item no encontrado");			
			$worksheet->getStyle($position[0])->applyFromArray($COLOR_RED_BK);	
			
			$worksheet->setCellValue($position[1], "Item no encontrado");			
			$worksheet->getStyle($position[1])->applyFromArray($COLOR_RED_BK);
			
		}else{//Datos del producto eran incorrectos
			
			$worksheet->setCellValue($position[0], $new_value['nombre']);			
			$worksheet->getStyle($position[0])->applyFromArray($COLOR_BLUESKY_BK);	
			
			$worksheet->setCellValue($position[1], $new_value['cantidad_chofer']);			
			$worksheet->getStyle($position[1])->applyFromArray($COLOR_BLUESKY_BK);
							
		}	
  }
  
  function get_vale_data_g($idvale_string,$placa_string,$documento_string,$fechaconsumo_string,$codigo_sap_string){
	  
	  $method = 1;
	  $found = false;
	  	  
	  $data = getVale_filtro1( $idvale_string );
	  
	  if( empty($data) ){
		  $data = getVale_filtro2($placa_string, $documento_string, $fechaconsumo_string, $codigo_sap_string);
		  $method = 2;
	  }
	  
	  if( !empty($data) )$found = true;
	  
	  return array( "method" => $method, "found" => $found ,"data" => $data );
  }  
  
  function get_detallevalemateriales_g($idvale_string){
	  
	  $idvale_string =  intval( preg_replace('/[^0-9]/', '', $idvale_string) );
	  $data = get_detallevalemateriales( $idvale_string );
	  
	  return $data;
  }
  
  function get_asignacionesmateriales_g($idvale_string){
	  
	  $idvale_string =  intval( preg_replace('/[^0-9]/', '', $idvale_string) );
	  $data = get_asignacionesmateriales( $idvale_string );
	  
	  return $data;
  }
  
  function validar_detalle_producto_item($detalle_vale_materiales, $value_excel, $return_only_position = false){
	  
	  $index = 0;
	  $position_item = -1;
	  foreach($detalle_vale_materiales as $item){
		  if( $item['cod_sap'] == $value_excel['cod_sap'] ){
			  $position_item = $index;
		  }
		  $index++;
	  }
	  
	  if($return_only_position)return $position_item;
	  
	  if($position_item == -1){
		  return -1;
	  }else{
		  if($detalle_vale_materiales[$position_item]['cod_sap'] == $value_excel['cod_sap'] &&
        	 $detalle_vale_materiales[$position_item]['nombre']  == $value_excel['nombre'] &&
             $detalle_vale_materiales[$position_item]['cantidad_chofer'] == $value_excel['cantidad_chofer'] ){
				return false;
			}else{
			  return array("nombre"          =>  $detalle_vale_materiales[$position_item]['nombre'], 
			               "cantidad_chofer" =>  $detalle_vale_materiales[$position_item]['cantidad_chofer']);	
			}
	  }
	  
  }
  
    function validar_detalle_asignacion(&$detalle_vale_asignaciones, $value_excel){
	  
	  $index = 0;
	  $position_item = -1;
	  foreach($detalle_vale_asignaciones as $item){
		  if( $item['cod_sap'] == $value_excel['cod_sap'] ){
			  $position_item = $index;
		  }
		  $index++;
	  }
	  
	  if($position_item == -1){
		  return -1;
	  }else{
		  if($detalle_vale_asignaciones[$position_item]['cod_sap'] == $value_excel['cod_sap'] &&
        	 $detalle_vale_asignaciones[$position_item]['centrocosto']  == $value_excel['centrocosto'] ){
				return false;
			}else{
			  return  $detalle_vale_asignaciones[$position_item]['centrocosto'];	
			}
	  }
	  
  }
  
  function set_estado($procesada, &$worksheet, $position, $method = 0){
	  
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
		$worksheet->setCellValue($position, "PROCESADA, metodo: 0x".$method);
	  }else{
		$worksheet->getStyle($position)->applyFromArray($COLOR_RED_BK);
		$worksheet->setCellValue($position, "NO PROCESADA");
	  }
	  
  }




