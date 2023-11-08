<?php
/*
ini_set('display_errors', '1');
error_reporting(-1);
error_reporting(0);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
*/

$id = $_GET['id'];

include("PdfManage.php");

$pdf = new \Nextek\LaraPdfMerger\PdfManage;

$pdf->addPDF('temp/'.$id.'.pdf', 'all')
	->addPDF('anexos_formato_2020/PAVIFERIA_ANEXOS_2020.pdf', 'all')
	->merge('browser', 'FINAL.pdf');