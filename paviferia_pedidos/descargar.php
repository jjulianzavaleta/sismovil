<?php

include_once("../phps/libreriasphp/PHPExcel/baseExcel.php");
include_once("../phps/dpaviferia_pedido.php");

$data =  getAllCotizaciones();

$titulos = array("Codigo","Estado","Fecha Emision","Usuario Registra","Zona","Forma de Pago",
				 "Tipo Documento","Nro Documento","Nombre/RzSocial","Direccion",
	             "Nombre Contacto","Correo Contacto","Telefono Contacto","Celular Contacto",
	             "Nro Item","Producto ID","Nombre Producto","Unidades","Cantidad","Precio Base",
	             "Dscto %","Subtotal","IGV","Importe Parcial",
	             "Subtotal Pedido","IGV Pedido","Total Pedido");

$indices = array("codigo","estadoCot1","fechaEmision","username","zona","formapagodesc",
				 "tipodocumento","nrodocumento","nombre_rzsocial","direccion",
	             "nombrecontacto","correocontacto","telefonofijo","celularcontacto",
				 "nroitem","idproducto","productoname","unidades","kilogramosdesc","precio",
			     "descuento","subtotal","igv","total",
	             "subtotalpedido","igvpedido","totalpedido");

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
header('Content-Disposition: attachment; filename="REPORTE COTIZACIONES '.date('d/m/Y').'.xlsx"');

//forzar a descarga por el navegador
$objWriter->save('php://output');

/*FIN CREACION DE ARCHIVO Y EMPEZAR DESCARGA*/
