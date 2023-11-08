<?php
include "../../../librerias/PHPExcel/Classes/PHPExcel.php";



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

//nuevo estilo
global $Style_normal;
$Style_normal = new PHPExcel_Style();//nuevo estilo

$Style_normal->applyFromArray(
 array(
 	'fill'  => array( //relleno de color
      			'type' => PHPExcel_Style_Fill::FILL_SOLID
    			),
    'borders' => array( //bordes
				'top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
				'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
				'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060'))
    			),
	'alignment' => array( //alineacion
				'wrap'       => false,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'color'      => array('rgb'=>'606060')
    			),
	'font'  => array(
					   'bold'  => false,
					   'color' => array('rgb' => '000000'),
					   'size'  => 8,
					   'name'  => 'Verdana'
					  )
	)
);

//nuevo estilo
global $Style_none;
$Style_none = new PHPExcel_Style();//nuevo estilo

$Style_none->applyFromArray(
 array(
	'font'  => array(
					   'bold'  => false,
					   'color' => array('rgb' => '000000'),
					   'size'  => 8,
					   'name'  => 'Verdana'
					  )
	)
);

//nuevo estilo
global $Style_importanttable;
$Style_importanttable = new PHPExcel_Style();//nuevo estilo

$Style_importanttable->applyFromArray(
 array(
 	'fill'  => array( //relleno de color
      			'type' => PHPExcel_Style_Fill::FILL_SOLID
    			),
    'borders' => array( //bordes
				'top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
				'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060')),
				'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb'=>'606060'))
    			),
	'alignment' => array( //alineacion
				'wrap'       => false,
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'color'      => array('rgb'=>'606060')
    			),
	'fill'     => array( //relleno de color
      					'type'  => PHPExcel_Style_Fill::FILL_SOLID,
     					'color' => array('argb' => '0BC2FF')
    				   ),
	'font'  => array(
					   'bold'  => true,
					   'color' => array('rgb' => 'FFFFFF'),
					   'size'  => 8,
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

function mergeCellsVertical(&$objPHPExcel,$firstRow,$lastRow,$col){
//combino columnas en una fila
	$letter = PHPExcel_Cell::stringFromColumnIndex($col);
	$header_range = "{$letter}$firstRow:{$letter}$lastRow";
	
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
	
	
	foreach($titulos as $title){
		//escribo los rotulos (cabecera) de la tabla
		$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum, $row));
		
		if( $colum==1  || $colum==2  || $colum==3  || $colum==4 || $colum==5 || $colum== 16|| $colum==17 ||
		    $colum==20 || $colum==21 || $colum==22 || $colum==23){
			
			if( $colum==1){
				mergeCellsHorizontal($objPHPExcel,$colum,$colum+2,$row);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode("Comp. de Pago o Docum."));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row+1, utf8_encode($titulos[$colum]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+1,$row+1, utf8_encode($titulos[$colum+1]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+2,$row+1, utf8_encode($titulos[$colum+2]));
				$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum+2, $row+1));
			}
			
			if( $colum==4){
				mergeCellsHorizontal($objPHPExcel,$colum,$colum+1,$row);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode("Docum. De Identidad"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row+1, utf8_encode($titulos[$colum]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+1,$row+1, utf8_encode($titulos[$colum+1]));
				$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum+1, $row+1));
			}
			
			if( $colum==16){
				mergeCellsHorizontal($objPHPExcel,$colum,$colum+1,$row);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode("Tipo de Medio de Pago"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row+1, utf8_encode($titulos[$colum]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+1,$row+1, utf8_encode($titulos[$colum+1]));
				$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum+1, $row+1));
			}
			
			if( $colum==20){
				mergeCellsHorizontal($objPHPExcel,$colum,$colum+3,$row);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode("Referenc. De Comprob. De Pago"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row+1, utf8_encode($titulos[$colum]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+1,$row+1, utf8_encode($titulos[$colum+1]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+2,$row+1, utf8_encode($titulos[$colum+2]));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum+3,$row+1, utf8_encode($titulos[$colum+3]));
				$objPHPExcel->getActiveSheet()->setSharedStyle($Style_subtitulo,numberToCellValueByColum($colum, $colum+3, $row+1));
			}
			
		}else{
			mergeCellsVertical($objPHPExcel,1,2,$colum);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colum,$row, utf8_encode(strtoupper($title)));
		}
		
		$colum++;
	}
	
	$row++;$row++;
	$colum = $aux_colum;
	$ITEM = 1;
	$nro_colums = sizeof($indices);
	
	foreach($data as $value){
	//escribo la data enviada
		
		for($i = 0 ; $i < $nro_colums ; $i++){
				
				$aux_indice = $indices[$i];
				
				if($i == 0){//SI EMPIEZA UNA NUEVA FILA QUE CALCULE LOS VALORES NECESARIOS
				
					$value['venta'] 			=	$value['Gravadas'];
					$value['igv_monto'] 		=	$value['montoIgv'];
					
					$recibo						=	getTxT_recibo($value['cantidaFichasVinculadas'],$value['IdComprobante']);
					$value['calc_recibo']		=	$recibo=="0"?$value['documento']:$recibo;
					$value['calc_moneda']		=	"S";	
					
					if($value['esCredito'] == 1){
						$value['calc_codigo'] 	= 0;
						$value['calc_cuenta'] 	= "1213";						
						$value['calc_codmpago'] = "000";
						$value['calc_fpago'] 	= "Credito";						
					}else{
						$valPago 				= TipoPago($value['formapago'], $value['tipoTarjeta']);
						
						$value['calc_codigo'] 	= $valPago['codigo'];
						$value['calc_cuenta'] 	= $valPago['cuenta'];
						$value['calc_codmpago'] = $valPago['cmPago'];
						$value['calc_fpago'] 	= $valPago['descripcion'];						
					}
				}		
				
				$objPHPExcel->getActiveSheet()->setSharedStyle($Style_contenido,numberToCellValueByColum($colum, $colum, $row));		
				
				if($aux_indice=="venta" || $aux_indice =="igv_monto" || $aux_indice=="Total"){
					$format_code		=	PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00;
					$data_type_format	=	PHPExcel_Cell_DataType::TYPE_NUMERIC;
				}else{
					$format_code		=	PHPExcel_Style_NumberFormat::FORMAT_TEXT;	
					$data_type_format	=	PHPExcel_Cell_DataType::TYPE_STRING;				
				}
				
				$cell_pos	=	PHPExcel_Cell::stringFromColumnIndex($colum).$row;
				
				$objPHPExcel->getActiveSheet()->getStyle(numberToCellValueByColum($colum, $colum, $row))->getNumberFormat()->setFormatCode( $format_code );
				$objPHPExcel->getActiveSheet()->setCellValueExplicit($cell_pos,utf8_encode($value[$aux_indice]),$data_type_format);

				$colum++;
		}
		
		$ITEM++;
		$row++;
		$colum = $aux_colum;
		
	}
	
	$colum = $aux_colum;
	$tamanios= array(10,8,8,20,8,
	                 20,30,18,10,10,
					 10,15,15,15,10,
					 10,15,15,20,10,
					 10,10,10,10);
	$i=0;
	foreach($titulos as $title){
		//ASIGNO TAMAÑO DE LAS COLUMNAS
		$objPHPExcel->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($colum))->setWidth($tamanios[$i]);
		$colum++;
		$i++;
	}
	
	return $row;
	
}


function createComplexGraphicFinal(&$objPHPExcel,$range_rotulos,$valores,$titulo_grafico,
						     $TopLeftPosition,$BottomRightPosition,$TIPO_GRAFICO,$DIRECTION,$dsl,$showVal=true){
//crea un grafico de barras simple basados en un rango de celdas

	// definir origem dos rótulos das colunas
	$categories = new PHPExcel_Chart_DataSeriesValues('String',  $objPHPExcel->getActiveSheet()->getTitle().'!'.$range_rotulos);
	
	// definir dados a mostrar no gráfico
	$series = new PHPExcel_Chart_DataSeries(
		$TIPO_GRAFICO, // tipo de gráfico
		PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
		range(0, count($valores)-1),	
		$dsl,
		array($categories), // rótulos das colunas
		$valores // valores
		);
	
	$series->setPlotDirection($DIRECTION);
	
	// inicializar o layout do gráfico e área do gráfico
	$layout   = new PHPExcel_Chart_Layout();
	$layout->setShowVal($showVal); 
	$layout->setShowPercent(TRUE);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
	 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOP, null, false);
	 
	// inicializar o gráfico
	$chart = new PHPExcel_Chart(utf8_encode(''), null, $legend, $plotarea);
		
	// definir título do gráfico
	$title = new PHPExcel_Chart_Title(null, $layout);
	$title->setCaption(utf8_encode($titulo_grafico));
	 
	// definir posição do gráfico e título
	$chart->setTopLeftPosition($TopLeftPosition);
	$chart->setBottomRightPosition($BottomRightPosition);
	$chart->setTitle($title);
	
	// adicionar o gráfico à folha
	$objPHPExcel->getActiveSheet()->addChart($chart);

}
