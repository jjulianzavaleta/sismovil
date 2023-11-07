<?php
include ("../phps/libreriasphp/PHPExcel/Classes/PHPExcel.php");



/*INICIO Estilos para las hojas excel*/

//nuevo estilo
global $Style_contenido_PERCENTAGE;
$Style_contenido_PERCENTAGE = new PHPExcel_Style();//nuevo estilo

$Style_contenido_PERCENTAGE->applyFromArray(
 array(
 	  'fill'  => array( //relleno de color
      			'type' => PHPExcel_Style_Fill::FILL_SOLID
    			),
    'borders' => array( //bordes
				'top'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'right'  => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'left'   => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060'))
    			),
	'alignment' => array( //alineacion
				'wrap'       => false,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
    			),
	'numberformat' => array(
				'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
				)
	)
);


global $Style_contenido_PERCENTAGE2;
$Style_contenido_PERCENTAGE2 = new PHPExcel_Style();//nuevo estilo

$Style_contenido_PERCENTAGE2->applyFromArray(
 array(
 	 'fill'  => array( //relleno de color
      			'type' => PHPExcel_Style_Fill::FILL_SOLID
    			),
    'borders' => array( //bordes
				'top'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'right'  => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'left'   => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060'))
    			),
	'alignment' => array( //alineacion
				'wrap'       => false,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'color'      => array('rgb'=>'606060')
    			),
	'fill'     => array( //relleno de color
      					'type'  => PHPExcel_Style_Fill::FILL_SOLID,
     					'color' => array('argb' => '00A3D9')
    				   ),
	'font'  => array(
					   'bold'  => true,
					   'color' => array('rgb' => 'FFFFFF'),
					   'size'  => 10,
					   'name'  => 'Verdana'
					  ),
	'numberformat' => array(
				'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
				)
	)
);

//nuevo estilo
global $Style_contenido;
$Style_contenido = new PHPExcel_Style();//nuevo estilo

$Style_contenido->applyFromArray(
 array(
 	  'fill'  => array( //relleno de color
      			'type' => PHPExcel_Style_Fill::FILL_SOLID
    			),
    'borders' => array( //bordes
				'top'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'right'  => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'left'   => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060'))
    			),
	'alignment' => array( //alineacion
				'wrap'       => false,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
    			)
	)
);


//nuevo estilo
global $Style_subtitulo2;
$Style_subtitulo2 = new PHPExcel_Style();//nuevo estilo

$Style_subtitulo2->applyFromArray(
 array(
 	'fill'  => array( //relleno de color
      			'type' => PHPExcel_Style_Fill::FILL_SOLID
    			),
    'borders' => array( //bordes
				'top'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'right'  => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060')),
				'left'   => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb'=>'606060'))
    			),
	'alignment' => array( //alineacion
				'wrap'       => false,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'color'      => array('rgb'=>'606060')
    			),
	'fill'     => array( //relleno de color
      					'type'  => PHPExcel_Style_Fill::FILL_SOLID,
     					'color' => array('argb' => '00A3D9')
    				   ),
	'font'  => array(
					   'bold'  => true,
					   'color' => array('rgb' => 'FFFFFF'),
					   'size'  => 10,
					   'name'  => 'Verdana'
					  )
	)
);

//inicio estilos
global $Style_titulo;
$Style_titulo = new PHPExcel_Style(); //nuevo estilo

$Style_titulo->applyFromArray(
  array(
  	'alignment' => array( //alineacion
      					'wrap'       => false,
     					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
   					 ),
    'font' 		=> array( //fuente
						 'bold' => true,
						 'size' => 14
					)
	  )
);

global $Style_subtitulo;
$Style_subtitulo = new PHPExcel_Style(); //nuevo estilo

$Style_subtitulo->applyFromArray(
  array(
  	  'fill'     => array( //relleno de color
      					'type'  => PHPExcel_Style_Fill::FILL_SOLID,
     					'color' => array('argb' => '00A3D9')
    					 ),
      'borders' => array( //bordes
					   'top'    => array('style'    => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
					   'right'  => array('style'    => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
					   'bottom' => array('style'    => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
					   'left'   => array('style'    => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060'))
					   ),
	  'font'  => array(
					   'bold'  => true,
					   'color' => array('rgb' => 'FFFFFF'),
					   'size'  => 10,
					   'name'  => 'Verdana'
					  ),
	  'alignment' => array( //alineacion
      					'wrap'       => false,
     					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'color'      => array('rgb'=>'606060')
   					 )
));

/*FIN Estilos para las hojas excel*/






function setNegrita(&$objPHPExcel,$firstLetter,$lastLetter,$fila){
//aplicar negrita a un rango de celdas en una fila
	$first_letter = PHPExcel_Cell::stringFromColumnIndex($firstLetter);
	$last_letter  = PHPExcel_Cell::stringFromColumnIndex($lastLetter);
	$header_range = "{$first_letter}$fila:{$last_letter}$fila";
	$objPHPExcel->getActiveSheet()->getStyle($header_range)->getFont()->setBold(true); //negrita

}

function mergeCellsHorizontal(&$objPHPExcel,$firstLetter,$lastLetter,$fila){
//combino columnas en una fila
	$first_letter = PHPExcel_Cell::stringFromColumnIndex($firstLetter);
	$last_letter  = PHPExcel_Cell::stringFromColumnIndex($lastLetter);
	$header_range = "{$first_letter}$fila:{$last_letter}$fila";
	
	$objPHPExcel->getActiveSheet()->mergeCells($header_range);
}

function  numberToCellValueByColum($columStart, $columnEnd, $row){
//convierto numeros a rango de celdas en una fila para la clase phpexcel

	 $merge = 'A1:A1';
    if(is_numeric($columStart) && is_numeric($columnEnd) && is_numeric($row)){
	  	$columStart   = PHPExcel_Cell::stringFromColumnIndex($columStart);
		$columnEnd    = PHPExcel_Cell::stringFromColumnIndex($columnEnd);
        $merge = "$columStart{$row}:$columnEnd{$row}";

    }

    return $merge;

}

function  numberToCellValueByRow($rowStart, $rowEnd, $colum){
//convierto numeros a rango de celdas en una fila para la clase phpexcel

	$merge = 'A1:A1';
    if(is_numeric($rowStart) && is_numeric($rowEnd) && is_numeric($colum)){
	
	  	$colum     = PHPExcel_Cell::stringFromColumnIndex($colum);
		$merge     = "$colum{$rowStart}:$colum{$rowEnd}";
       //$merge     = '$'.$colum.'$'.$rowStart.':$'.$colum.'$'.$rowEnd;//"$colum{$rowStart}:$colum{$rowEnd}";

    }

    return $merge;
}

//crea una tabla NxN: N columnas y N filas
function generateTableNxN_normal(&$objPHPExcel,$titulos,$data,$indices,$colum,$row,$hasNro){
		
	global $Style_contenido;
	global $Style_subtitulo;
	
	$aux_colum = $colum;
	
	if($hasNro){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode("ITEM"));
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colum)->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum, $row));
		$colum++;
	}
	
	foreach($titulos as $title){
		//escribo los rotulos (cabecera) de la tabla, aplicandole autozise para que se vea mejor
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode(strtoupper($title)));
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colum)->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum, $row));
		$colum++;
	}
	
	$row++;
	
	$colum = $aux_colum;
	$ITEM = 1;
	$nro_colums = sizeof($indices);
	
	foreach($data as $value){
	//escribo la data enviada
		
		if($hasNro){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode($ITEM));
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colum)->setAutoSize(true);
			
			$objPHPExcel->getActiveSheet()->setSharedStyle($Style_contenido,numberToCellValueByColum($colum, $colum, $row));
			$colum++;
		}
		
		for($i = 0 ; $i < $nro_colums ; $i++){
				
				$aux_indice = $indices[$i];

                if(is_object($value[$aux_indice])){
                    $value[$aux_indice] = date_format($value[$aux_indice],"Y/m/d H:i:s");
                }

				//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode($value[$aux_indice]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, $value[$aux_indice]);
				$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colum)->setAutoSize(true);
			
				$objPHPExcel->getActiveSheet()->setSharedStyle($Style_contenido,numberToCellValueByColum($colum, $colum, $row));
				
				$colum++;
		}
		
		$ITEM++;
		$row++;
		$colum = $aux_colum;
		
	}
	
	return $row;
	
}
