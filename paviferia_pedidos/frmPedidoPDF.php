<?php
include("../phps/dpaviferia_pedido.php");

if(!isset($_GET['id'])){
	die("Error: Parametro requerido");
}

session_start();
$pedido             = getPedidoPaviferiaById($_GET['id']);
$fechaRegistro      = $pedido['fechaEmision'];

$year               = substr($fechaRegistro,6,4);

$pdf_version_name   = "";
switch($year){
    case "2023":
        $pdf_version_name = "reportes_pdf/frmPedidoPDF_v2023.php";
        break;
    case "2022":
        $pdf_version_name = "reportes_pdf/frmPedidoPDF_v2022.php";
        break;
    case "2021":
        $pdf_version_name = "reportes_pdf/frmPedidoPDF_v2021.php";
        break;
	case "2020":
	     $pdf_version_name = "reportes_pdf/frmPedidoPDF_v2020.php";
	     break;
    case "2019":
	     $pdf_version_name = "reportes_pdf/frmPedidoPDF_v2019.php";
	     break;
    case "2018":
	     $pdf_version_name = "reportes_pdf/frmPedidoPDF_v2018.php";
	     break;
    default:
	     $pdf_version_name = "reportes_pdf/frmPedidoPDF_3.php";
	     //die("Error, version no soportada");
}

header('Location: '.$pdf_version_name.'?id='.$_GET['id']);
