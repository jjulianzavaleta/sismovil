<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 06/09/2015
 * Time: 11:55 PM
 */
include("../../phps/dpaviferia_pedido.php");
include("../../phps/dpaviferia_usuario.php");
include("../../phps/dpaviferia_zona.php");
session_start();

/*Configuracion PDF  */
$cell_height      = 3;
$cellTable_height = 5;
/*Fin Configuracion PDF*/

$fechaRegistro = "";
$clietename    = "";
$contactoname  = "";
$pedido        = array();
$detallepedido = array();
$user_telefonos = "";
$user_correo    = "";
$user_name      = "";
$anho_reg       = "";

global $zona_entregapav;
global $telefono_zona;
global $direccion_zona;
global $fechainicioValides;
global $fechafinValides;
$zona_entregapav = "";
$telefono_zona  = "";
$direccion_zona = "";
$fechainicioValides = "";
$fechafinValides = "";

global $grupoProductosCot;
$grupoProductosCot = 0;
$modopago          = 0;
$subtotal          = 0;
$igv               = 0;
$total             = 0;
$codigoCotiz       = "";

if(isset( $_GET['id']) ){

    $pedido             = getPedidoPaviferiaById($_GET['id']);
    $detallepedido      = getDetallePedidoPaviferiaById2($_GET['id']);
    $usuariocrea        = getUsuario2ById($pedido['usuarioregistra']);

    if(!empty($detallepedido)){//Asigno el grupo del�primer producto, con esto sabre si es tipo Paneton o tipo Pavo

        $grupoProductosCot  = $detallepedido[0]['idgrupo'];

        if(!empty($pedido['fechavalida'])){
            $fechainicioValides =  "";
            $fechafinValides    =  date_format($pedido['fechavalida'], 'd/m/Y');
        }else{
            //Para las primeras 315 cotizaciones esta es la logica para la fecha de validez

            if(!empty($detallepedido[0]['fechainicio']) && !empty($detallepedido[0]['fechafin'])){
                $fechainicioValides =  date_format($detallepedido[0]['fechainicio'], 'd/m/Y');
                $fechafinValides    =  date_format($detallepedido[0]['fechafin'], 'd/m/Y');
            }else{
                if($_GET['id'] <= 20){//Para las 20 primeras cotizaciones esta fue la fecha de vencimiento
                    $fechafinValides = "30 de Setiembre del 2015";
                }
            }
        }
    }

    $codigoCotiz        = $pedido['codigo'];

    $zona_info          = getZonaById($pedido['serie']);
    $zona_entregapav     = $zona_info['direccion'];

    if($pedido['serie'] == 1){
        $telefono_zona  = "Av. Am�rica Norte # 2213 Urb. Las Quintanas Trujillo - Per�";
        $direccion_zona = "";
    }

    if($pedido['serie'] == 2){
        $telefono_zona  = "Av. Francisco C�neo N� 625 Urb. Patazca - Chiclayo.";
        $direccion_zona = "Tel: 074 - 232149 / RPM: # 941836625";
    }

    $user_telefonos     = utf8_decode($usuariocrea['telefonos']);
    $user_correo        = utf8_decode($usuariocrea['correo']);
    $user_name          = utf8_decode($usuariocrea['nombres'].' '.$usuariocrea['apellidos']);

    $fechaRegistro = $pedido['fechaEmision'];
    $anho_reg      = substr($fechaRegistro,-4);
    $clietename    = str_replace('&AMP;', '&', utf8_decode($pedido['nombre_rzsocial']));
    $contactoname  = utf8_decode($pedido['nombrecontacto']);

    $modopago         = utf8_decode($pedido['formapagodesc']);
    $subtotal         = $pedido['subtotal'];
    $igv              = $pedido['igv'];
    $total            = $pedido['total'];

}

require('../../phps/libreriasphp/pdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de p�gina
    function Header()
    {
        // Logo
        $this->Image('../../assets/images/logo1.png',30,0,-110);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // T�tulo
        // $this->Cell(30,10,'COTIZACI�N',0,0,'C');
        // Salto de l�nea
        $this->Ln(20);
    }

// Pie de p�gina
    function Footer()
    {
        // Posici�n: a 1,5 cm del final
           $this->SetY(-60); //original
	// $this->SetY(-90);
        // Arial italic 8

        global $fechainicioValides;
        global $fechafinValides;

        $this->SetFont('Times','',8);
        //$this->MultiCell(180,3,'La presente cotizaci�n est� expresada en NUEVOS SOLES (S/.) y es v�lida hasta el '.$fechafinValides);
        //$this->MultiCell(180,3,'*El  Precio Base/Precio Dscto  no  incluye  el 18% del I.G.V. ');
        //$this->MultiCell(180,3,'Esta cotizaci�n no obliga a Chim� Agropecuaria a realizar la reserva de la cantidad del producto cotizada.');
		
		$this->MultiCell(180,3,'La presente cotizaci�n no obliga a Chim� Agropecuaria S.A. a realizar la reserva de la cantidad del producto cotizada.');
		$this->MultiCell(180,3,'El precio se mantendr� vigente hasta el periodo definido en la presente cotizaci�n; para reservar �ste deber� enviar la OC y emitir el comprobante de pago.');		
  

        globaL $grupoProductosCot;
        global $telefono_zona;
        global $direccion_zona;

        if($grupoProductosCot == 1){
            $this->MultiCell(180,3,'El vale ser� canjeable �nicamente por pavo entero. Por favor detallar su pedido por ciudad de entrega.');
        }


        $this->SetFont('Arial','I',8);

        // $this->SetTextColor(224,255,255);

        $this->Cell(0,10,'Chim�  Agropecuaria S.A.',0,0,'L');
        $this->Ln(3);
        $this->Cell(0,10,$direccion_zona,0,0,'L');
        $this->Ln(3);
        $this->Cell(0,10,$telefono_zona,0,0,'L');
	
	$this->Ln(8);
	$this->Image('../../assets/images/arte2.png',0,null,0);
    } 
}


// Creaci�n del objeto de la clase heredada
$pdf = new PDF();

$pdf->AddPage();

$pdf->SetFont('Times','BU',12);
$pdf->Cell(180,10,'COTIZACI�N '.$codigoCotiz,0,1,'C');

$pdf->Ln(0);

$pdf->SetFont('Times','',8);
$pdf->Cell(180,$cell_height,'Fecha Registro: '.$fechaRegistro,0,1,'R');

$pdf->SetFont('Times','B',8);
$pdf->Cell(180,$cell_height,'Sres.: '.$clietename,0,1,'L');

$pdf->Ln(0);
$pdf->SetFont('Times','BU',8);
$pdf->Cell(180,$cell_height,'Ciudad:',0,1,'L');

$pdf->Ln(0);
$pdf->SetFont('Times','BU',8);
$pdf->Cell(180,$cell_height,'ATT: '.$contactoname,0,1,'R');

$pdf->Ln(5);
$pdf->SetFont('Times','',8);
$pdf->Cell(180,$cell_height,'Estimado Cliente:',0,1,'L');

$pdf->Ln(5);
$pdf->SetFont('Times','',8);

if($grupoProductosCot == 1){//Pavo
    //$pdf->MultiCell(180,$cell_height, 'Muchas gracias por contactarse con Chim� Agropecuaria y preferir la calidad y garant�a de nuestro producto �Pavo San Fernando.� ' );
	$pdf->MultiCell(180,$cell_height, 'Gracias por contactarte con Chim� Agropecuaria, 72 a�os de experiencia con nuestro producto "PAVO SAN FERNANDO" nos permite asegurarte la mejor cena navide�a.' );
    $pdf->Ln(2);
    $pdf->MultiCell(180,$cell_height, 'A continuaci�n, presentamos la cotizaci�n para la presente CAMPA�A NAVIDE�A '.$anho_reg.', bajo la modalidad de �Vale de Pavo�:' );
}
if($grupoProductosCot == 2){//Paneton
    $pdf->MultiCell(180,$cell_height, 'Muchas gracias por contactarse con Chim� Agropecuaria S.A. y preferir la calidad y garant�a de nuestros productos.' );
    $pdf->Ln(2);
    $pdf->MultiCell(180,$cell_height, 'Les informamos que este a�o estamos ofreciendo para los clientes Institucionales, nuestro Panet�n �Navilandia�, el cual cuenta con la calidad y sabor garantizado por San Fernando. Este producto cuenta con un precio preferencial asociado a la compra de nuestro vale de pavo.' );
    $pdf->Ln(2);
    $pdf->MultiCell(180,$cell_height, 'A continuaci�n, presentamos la cotizaci�n para la presente CAMPA�A NAVIDE�A 2018:' );
}


$pdf->Ln(2);
$pdf->SetFont('Times','BU',8);
// $pdf->Cell(180,10,'Entregable al cliente:',0,1,'L');

$pdf->SetFont('Times','B',8);

if($grupoProductosCot == 1){//Pavo
    $pdf->MultiCell(180,$cell_height, 'PAVO ENTERO CONGELADO, marinado, c/menudencia, empacado al vac�o, el mismo que es beneficiado con la m�s alta tecnolog�a de exportaci�n e higiene bajo normas ISO 9001, 14001 y HACCP. (Normas que garantizan la calidad, INOCUIDAD y cuidado del medio ambiente).');
}

if($grupoProductosCot == 2){//Paneton
    $pdf->MultiCell(180,$cell_height, 'Panet�n Navilandia Caja x 1 Kg. / Panet�n Navilandia Bolsa x 900 g.' );
}

$pdf->Ln(2);
$pdf->SetFont('Times','',8);

$nrLineasProductoName = 1;
////$ancho_procutoName    = 20;
$ancho_procutoName    = 45;
$sumLineas            = 0;

$pdf->SetFillColor(0, 51, 102);   //color Azul
//$pdf->Rect($pdf->getX(), $pdf->getY(), 181, 5, 'F');
$pdf->Rect($pdf->getX(), $pdf->getY(), 186, 5, 'F');
$pdf->SetTextColor(255);
$pdf->Cell( 6,$cellTable_height,'Item',1,0,'C');
$pdf->Cell($ancho_procutoName,$cellTable_height,'Producto',1,0,'C');
$pdf->Cell(15,$cellTable_height,'Unidades',1,0,'C');
$pdf->Cell(20,$cellTable_height,'Peso',1,0,'C');
// $pdf->Cell(20,$cellTable_height,'Precio Base*',1,0,'C');
$pdf->Cell(20,$cellTable_height,'Total Kilos',1,0,'C');
//$pdf->Cell(20,$cellTable_height,'Dsctos. %',1,0,'C');
$pdf->Cell(20,$cellTable_height,'Valor Kilo',1,0,'C');
$pdf->Cell(20,$cellTable_height,'Subtotal',1,0,'C');
$pdf->Cell(20,$cellTable_height,'IGV',1,0,'C');
$pdf->Cell(20,$cellTable_height,'Total',1,0,'C');

$pdf->SetTextColor(0);

foreach($detallepedido as $detalle){

    /*Inicio Calculos auxiliares pero necesarios*/
    if(getUnidadMedicaById($detalle['idproducto']) == "UN"){
      //  $kgss = number_format($detalle['kilogramos'],0);
      $kgss = number_format($detalle['peso_base'],0);

    }else{
      //  $kgss = $detalle['kilogramos'];

	$kgss = $detalle['peso_base'];

    }

    if(empty($detalle['precio_dscto']) && $detalle['precio_dscto'] != 0){
        $precioDscto = $detalle['precio_dscto'];
    }else{
        //Las 19 primeras cotizaciones creadas no guardaban el precio con dscto, por lo que se debe calcular
        $precioDscto = calcularPrecioDsctoProductoPaviferia($detalle['precio'],$detalle['descuento']);
    }

    //////////////////////////////////////////////////////////////////////
    //TotalKilos

    if($detalle['unidades'] !=0 && $detalle['kilogramos'] != 0){
        $kilosTotal = $detalle['unidades'] * $detalle['peso_base'];
    }else{
        //Calcular kilos total cuando uno de los valores es 0
        $kilosTotal = $detalle['peso_base'];
    }

	$kilosFinal = $kilosTotal + $kilosFinal;    //C�lculo de kilos total proforma


    /////////////////////////////////////////////////////////////////////

    $nrLineasProductoName = $pdf->NbLines($ancho_procutoName, $detalle['productodesc']);
    $alto_celdaAux = $cellTable_height*$nrLineasProductoName;
    /*Fin Calculos auxiliares pero necesarios*/

    $pdf->Ln();
    $y = $pdf->GetY();

    $pdf->Cell(6,$alto_celdaAux,$detalle['nroitem'],1,0,'C');
    $pdf->MultiCell($ancho_procutoName,$cellTable_height,utf8_decode($detalle['productodesc']),1,"L");

    $pdf->SetXY($pdf->GetX()+$ancho_procutoName+6/*6 es el ancho de la celda Item*/,$y);

    $pdf->Cell(15,$alto_celdaAux,$detalle['unidades'],1,0,'C');
    $pdf->Cell(20,$alto_celdaAux,$kgss.' '.getUnidadMedicaById($detalle['idproducto']),1,0,'C');
  //  $pdf->Cell(20,$alto_celdaAux,$detalle['precio'],1,0,'C');
    $pdf->Cell(20,$alto_celdaAux,$kilosTotal.' '.getUnidadMedicaById($detalle['idproducto']),1,0,'C');
  //  $pdf->Cell(20,$alto_celdaAux,$detalle['descuento'],1,0,'C');
    $pdf->Cell(20,$alto_celdaAux,$precioDscto,1,0,'C');
    $pdf->Cell(20,$alto_celdaAux,$detalle['subtotal'],1,0,'C');
    $pdf->Cell(20,$alto_celdaAux,$detalle['igv'],1,0,'C');
    $pdf->Cell(20,$alto_celdaAux,$detalle['total'],1,0,'C');

    $sumLineas = $sumLineas + $nrLineasProductoName;
}
$saltos = $sumLineas/sizeof($detallepedido);

$pdf->Ln(7*($saltos<=0?1:$saltos));

//$pdf->Cell(61,5,'MODO DE PAGO: '.$modopago,1,0,'L');
$pdf->Cell(51,5,'MODO DE PAGO: '.$modopago,1,0,'L');

$pdf->Cell(30,5,'KILOS: '.$kilosFinal.' KG',1,0,'L');

$pdf->Cell(45,5,'SUBTOTAL: '.$subtotal,1,0,'L');
$pdf->Cell(25,5,'IGV: '.$igv ,1,0,'L');
$pdf->Cell(35,5,'TOTAL: '.$total,1,0,'L');

$pdf->Ln(15);

$pdf->Cell(180,$cell_height,'Precio v�lido:          Hasta el '.date_to_text($fechafinValides),0,1,'L');
$pdf->Cell(180,$cell_height,'Forma de pago:       '.$modopago,0,1,'L');
$pdf->Cell(180,$cell_height,'Vigencia de Vales:  31 Octubre del 2021',0,1,'L');

if($grupoProductosCot == 1) {//Pavo
    $pdf->SetFont('Times','',8);
    $pdf->Cell(180,$cell_height,'Lugar de Canje:      Prolongaci�n Av F�tima s/n Sector Encalada  (Costado C.C. Real Plaza - Promart)',0,1,'L');
	$pdf->Ln(5);
}

if($grupoProductosCot == 2){//Paneton
    $pdf->Ln(2);
    $pdf->SetFont('Times','',8);
    $pdf->MultiCell(180,$cell_height, '�	La cantidad de pedido es  caja x 6 unidades ( bolsa o caja)' );
}

$pdf->Ln(2);
$pdf->SetFont('Times','',8);
$pdf->MultiCell(180,$cell_height, 'Para mayor informaci�n, te puedes comunicar con '.$user_name.' a los telefonos '.$user_telefonos.' y al correo electr�nico '.$user_correo);
$pdf->Ln(2);
$pdf->SetFont('Times','',8);
$pdf->MultiCell(180,$cell_height, 'Agradecemos su atenci�n y nos despedimos seguros de ofrecer el mejor servicio de venta y despacho de producto que como clientes y colaboradores merecen. ' );

$pdf->Ln(12);
$pdf->SetFont('Times','',8);
$pdf->MultiCell(180,$cell_height, 'Atentamente, ' );

$pdf->Ln(5);

if(file_exists('../../assets/images/DoraNapan.jpg')){
    $pdf->Image('../../assets/images/DoraNapan.jpg',20,null,0);
    $pdf->Cell(20,5,'',0,0,'L');
}

$pdf->Output('temp/'.$_GET['id'].'.pdf','F');
header('Location: pdfGeneratorv2020.php?id='.$_GET['id']);


function date_to_text($fecha){
	
	$fecha.=$fecha."";
	
	$dia = substr($fecha, 0, 2); 
	$mes = substr($fecha, 3, 2); 
	$anio = substr($fecha, 6, 4); 
	
	return $dia." de ".mes_to_string($mes)." del ".$anio;
}

function mes_to_string($mes){
	
	switch($mes){
		case "1": 
			return "Enero";
			break;
		case "2": 
			return "Febrero";
			break;
		case "3": 
			return "Marzo";
			break;
		case "4": 
			return "Abril";
			break;
		case "5": 
			return "Mayo";
			break;
		case "6": 
			return "Junio";
			break;
		case "7": 
			return "Julio";
			break;
		case "8": 
			return "Agosto";
			break;
		case "9": 
			return "Septiembre";
			break;
		case "10": 
			return "Octubre";
			break;
		case "11": 
			return "Noviembre";
			break;
		case "12": 
			return "Diciembre";
			break;
	}
}

?>
