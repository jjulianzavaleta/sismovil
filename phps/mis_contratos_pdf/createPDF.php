<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../libreriasphp/pdf3/Html2Pdf.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

/** Only enable when testing
include_once("../conexion.php");
include_once("../dcontract_contratos.php");
global $dir_subida;
$dir_subida = 'c:/xampp/htdocs/sismovil/files/contratos/AU202008000028/';
create_document_pdf_format(110,"AU202008000028");
*/
function create_document_pdf_format($id,$name_file){

	if( empty($id) ){
		return " Empty id for create_document_pdf_format";
	}
	
	global $dir_subida;
	
	ob_start();    
    $content = ob_get_clean();

    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(15, 10, 10, 10));
	$response = create_formato_solicitud($content,$id);
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content);
	
	$dir_name			=	$dir_subida.$name_file.".pdf";
	
    $html2pdf->Output($dir_name, 'F');
	
	return $response;
	
}

function create_formato_solicitud(&$html,$id){
	$tamLetra = 10;
	$tipoLetra = "Times";
	
	$data 		= getContratoDataForReport($id);
	if( empty($data) ){
		return "El contrato no fue encontrado en la BD. Id contrato = ".$id;
	}

	$estilo='
	<style type="text/css">
			
			.colorTabla{ font-size:'.($tamLetra).'pt;
					font-family: '.$tipoLetra.';
					color:black; 
					font-weight: normal;
					border-collapse:collapse;
					}
			.color1bold { font-size:'.($tamLetra).'pt;
					font-family: '.$tipoLetra.';
					color:black; 
					font-weight: bold;
					}
			.color1normal { font-size:'.($tamLetra).'pt;
					font-family: '.$tipoLetra.';
					color:black; 
					font-weight: normal;
					}
			.color2normal { font-size:'.($tamLetra-2).'pt;
					font-family: '.$tipoLetra.';
					color:black; 
					font-weight: normal;
					}	
			.centrar{text-align:center ;
					}
			.I{text-align:left ;
					}
			.D{text-align:right ;
					}
			td{padding-right:6px;padding-left: 6px; padding-bottom:3px;padding-top:3px;}
			
			.borderbotton 
			       {
				   border-bottom:0px solid #EEE;
				   }
				   
			.borderleft 
			       {
				   border-left:0px solid #EEE;
				   }	
			.bordertop
			       {
				   border-top:0px solid #EEE;
				   }	
				   
			.ceropadding{padding-bottom:0px;padding-top:0px;}
		</style>
	';
	
	$html= $html.$estilo;
	
	$proveedor_documentos = "";
	if( $data['proveedor_tipo'] == 1){
		$proveedor_documentos = '<tr>
				<td  width="650" colspan="4" class="borderbotton">
				[ X ] Copia simple de ficha RUC de una antigüedad no mayor de 30 días.<br>
				[ X ] Copia simple del DNI del representante legal.<br>
				[ X ] Original y copia legalizada de vigencia de poder del proveedor de una antigüedad no mayor de 30 días.<br>
				</td>
			</tr>';
	}else if( $data['proveedor_tipo'] == 2){
		$proveedor_documentos = '<tr>
				<td  width="650" colspan="4" class="borderbotton">
				[ X ] Copia simple de ficha RUC de una antigüedad no mayor de 30 días.<br>
				[ X ] Copia legible del DNI de la persona natural.<br>
				</td>
			</tr>';
	}
	
	$comprador_responsable = "";
	if( $data['tipo_flujo'] == 1 ){
		$comprador_responsable = '<tr>
				<td  width="150">Comprador responsable:</td>
				<td  width="350" colspan="3">'.$data['data_compradorresponsable'].'</td>
			</tr> ';
	}
	
	if( $data['termiesp_g_garantia'] == 3){
		$garantias_html = "Importe: ".number_format($data['termiesp_g_adelanto_importe'], 2, '.', ' ')."<br>Excepción: ".$data['modalidadpago_adelanto_exception']."<br>Monto mobiliario: ".$data['monto_mobiliario'];		
	}else if( $data['termiesp_g_garantia'] == 2){
		$garantias_html = "Importe: ".number_format($data['modalidadpago_cartafianza_importe'], 2, '.', ' ');		
	}else if( $data['termiesp_g_garantia'] == 4){
		$garantias_html = "Importe: ".number_format($data['termiesp_g_fcumplimiento_importe'], 2, '.', ' ');		
	}else if( $data['termiesp_g_garantia'] == 6){
		$garantias_html = "Importe: ".number_format($data['termiesp_g_fondogarantia_importe'], 2, '.', ' ');		
	}else{
		$garantias_html = "";
	}
	$garantias_html = '<tr>				
				<td  width="650" colspan="4">'.$data['data_garantia'].".  ".$garantias_html.'</td>
			</tr> ';
	
	
	
	$formapago_html = '';
	if( $data['termiesp_e_formapago'] == 1){
		
		$detalles_pago_partes = getConractFormaPagoPartesDetalles($id);		
		
		$formapago_html = $formapago_html.'
			<tr>
				<td width="650"class="color1normal"colspan="4">'.$data['data_formapago'].'
					<table border="1" class="colorTabla">
						<tr>
							<td>Inicial</td>
							<td>Porcentaje (%)</td>
							<td>Importe</td>
						</tr>';
		$contador_pago_partes = 1;
		foreach($detalles_pago_partes as $d){
			$formapago_html = $formapago_html.'
				<tr>
					<td class="centrar">Pago '.$contador_pago_partes.'</td>
					<td class="centrar">'.$d['porcentaje'].'</td>
					<td class="centrar">'.$d['importte'].'</td>
				</tr>';
			$contador_pago_partes++;
		}
		$formapago_html = $formapago_html.'
					</table>
				</td>
			</tr>';
		
	}else if( $data['termiesp_e_formapago'] == 2){
		$formapago_html = '<tr>
				<td width="650"class="color1normal"colspan="4">'.$data['data_formapago'].'</td>
			</tr>';
			
	}else if( $data['termiesp_e_formapago'] == 3){
		$formapago_html = '<tr>
				<td width="650"class="color1normal"colspan="4">'.$data['data_formapago'].". ".$data['data_poravances'].'</td>
			</tr>';
			
	}else if( $data['termiesp_e_formapago'] == 4){
		$formapago_html = '<tr>
				<td width="650"class="color1normal"colspan="4">'.$data['data_formapago'].". ".$data['data_creditodias'].'</td>
			</tr>';
	}else{
		$formapago_html = '<tr>
				<td width="650"class="color1normal"colspan="4">.</td>
				</tr>';	
	}
	
	$personal_tercero = "";
	if($data['lugar_entrega_personal_tercero'] == 1){
		$personal_tercero.= '<tr>
				<td  width="150">Número:</td>
				<td  width="125">'.$data['lugar_entrega_personal_tercero_numero'].'</td>
				<td  width="100">Días:</td>
				<td  width="125">'.$data['lugar_entrega_personal_tercero_dias'].'</td>
			</tr>
			<tr>
				<td width="150"class="color1normal">Equipos:</td>
				<td width="400"class="color1normal"colspan="3">'.$data['lugar_entrega_personal_tercero_equipo'].'</td>
			</tr>';
	}
	
	$penalidades_html = '<tr>
				<td width="650" colspan="4">No Registra.</td>
			</tr>';
	$penalidades_data = getPenalidades($id);
	if(!empty($penalidades_data)){
		$contador_penalidades = 1;
		$penalidades_html = '
			<tr>
				<td width="650"class="color1normal"colspan="4">
					<table border="1" class="colorTabla">
						<tr>
							<td>Item</td>
							<td>Supuesto</td>
							<td>Sancion económica</td>
						</tr>';		
		foreach($penalidades_data as $d){
			$penalidades_html.= '<tr>																	
				<td>'.$contador_penalidades.'</td>
				<td>'.$d['supuesto'].'</td>
				<td>'.$d['sancion_economica'].'</td>
				</tr>';
			$contador_penalidades++;
		}
		$penalidades_html = $penalidades_html.'
					</table>
				</td>
			</tr>';
	}
	
	$files_moviliarios = getListaArchivosRubrosMoviliarios($id);
	$files_partidaregistral = getListaArchivosPartidaRegistral($id);
	
	$jefatura_logistica_aprove = '';
	if( $data['tipo_flujo'] == 1 ){	
		$jefatura_logistica_aprove =
				'<tr>
					<td width="650"class="color1normal"colspan="1"> <b>JEFATURA LOGÍSTICA</b></td>
				</tr>
				
				<tr>
					<td  width="650" colspan="1">Nombre:  '.$data['autorizac_c_nombres'].'</td>
				</tr>
				<tr>
					<td  width="650" colspan="1">Cargo:  '.$data['autorizac_c_cargo'].'</td>
				</tr>
				<tr>
					<td  width="650" colspan="1">Fecha:  '.$data['autorizac_c_fecha'].'</td>
				</tr>
				<tr>
					<td  width="650" colspan="1" height="70">Firma:</td>
				</tr>';
	}

    $path_logo = "../assets/images/";
    $logo_empresa =  $path_logo."logo.png";////CHIMU AGROPECUARIA default
    $logo_chavin  =  $path_logo."logo_chavin.png";
    $logo_maisa   =  $path_logo."logo_maisa.png";

    if($data['reqgen_a_empresa'] == 2 && file_exists($logo_chavin)){//CHAVIN
        $logo_empresa =  $logo_chavin;
    }else if($data['reqgen_a_empresa'] == 3 && file_exists($logo_maisa)){//MAISA
        $logo_empresa = $logo_maisa;
    }


    $detalle_modo_pago = "";
    if($data['termiesp_f_modalidadpago']=="12"){
        $detalle_modo_pago = " : ".$data['modalidadpago_otro'];
    }else if($data['termiesp_f_modalidadpago']=="1"){
        $detalle_modo_pago = " : ".$data['modalidadpago_transcuenta_desc'];
    }

    $html=$html.'
		<page orientation="P"  pagegroup="new"   backtop="25mm" backbottom="10mm"   >
			<page_header >
				<table border="0" width="650"> 
						<tr>
							<td width="50" class="I">
								<img    SRC="'.$logo_empresa.'"  height="72" />
							</td> 
							<td width="525" align="center" class="color1bold centrar">
								RFP ELABORACIÓN Y REVISIÓN DE CONTRATOS<br>
								'.$data['datosgenerales_codigo'].'
							</td>
						</tr> 
				</table>				  
			</page_header>		
			
				
		<table border="1" class="colorTabla" width="650"> 			
			
			<tr>
				<td width="650"class="color1normal"colspan="4"> <b><u>I. REQUISITOS GENERALES</u></b></td>
			</tr> 

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>a) DATOS DE LA EMPRESA</b></td>
			</tr> 		
			
			<tr>
				<td width="150">Empresa:</td>
				<td width="450" colspan="3">'.$data['data_empresa'].'</td>
			</tr>
			<tr>
				<td  width="150">Área solicitante</td>
				<td  width="125">'.$data['reqgen_a_areasolicitante'].'</td>
				<td  width="100">Jefatura:</td>
				<td  width="125">'.getNombreFromUser_cp2($data['reqgen_a_areasolicitante_jefatura']).'</td>
			</tr>					
			'.$comprador_responsable.'
			<tr>
				<td  width="150">Área usuario:</td>
				<td  width="125">'.$data['data_areasuaria'].'</td>
				<td  width="100">Jefatura:</td>
				<td  width="125">'.getNombreFromUser_cp2($data['reqgen_a_areausuaria_jefatura']).'</td>
			</tr>	


			<tr>
				<td width="650" class="color1normal" colspan="4"> <b>b) DATOS DEL PROVEEDOR / CLIENTE / CONTRAPARTE</b></td>
			</tr> 

			<tr>
				<td  width="150">Proveedor:</td>
				<td  width="125">'.$data['data_razon_social'].'</td>
				<td  width="100">RUC:</td>
				<td  width="125">'.$data['reqgen_proveedor_ruc'].'</td>
			</tr>

			'.$proveedor_documentos.'
			
		</table>

		<table border="1" class="colorTabla" width="650"> 			
			
			<tr>
				<td width="650"class="color2"colspan="4"> <b><u>II. TÉRMINOS ESPECÍFICOS DEL CONTRATO</u></b></td>
			</tr> 		
			
			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>a) TIPO DE CONTRATO</b></td>
			</tr> 					
			
			<tr>
				<td  width="150">Tipo:</td>
				<td  width="350" colspan="3">'.($data['data_tipocontrato'].($data['termiesp_a_tipocontrato']==14?" :  ".$data['tipocontrato_otrosdesc']:"")).'</td>
				
			</tr>	

			<tr>
				<td  width="150">Nro Cotización:</td>
				<td  width="125">'.$data['termiesp_a_nrocotizacion'].'</td>
				<td  width="100">Fecha:</td>
				<td  width="125">'.$data['termiesp_a_fecha'].'</td>
			</tr>	

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>b) ALCANCE DEL CONTRATO</b></td>
			</tr> 	

			<tr>
				<td  width="150">Alcance:</td>
				<td width="450" colspan="3">'.$data['termiesp_b_alcance'].'</td>
			</tr>

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>c) PLAZO DE VIGENCIA</b></td>
			</tr> 	

			<tr>
				<td  width="150">'.($data['termiesp_c_formato']==0?"Días:":($data['termiesp_c_formato']==1?"Años:":"")).'</td>
				<td width="450" colspan="3">'.$data['termiesp_c_dias']." ".$data['data_vigenciaformato'].'</td>
			</tr>				
			
			<tr>
				<td  width="150">Fecha inicio:</td>
				<td  width="125">'.$data['termiesp_c_fechainicio'].'</td>
				<td  width="100">Fecha término:</td>
				<td  width="125">'.$data['termiesp_c_fechafin'].'</td>
			</tr>	

			<tr>
				<td  width="650" colspan="4">'.($data['termiesp_c_incluyeacta']==1?"[ X ]":"[  ]").' Acta de entrega de terreno</td>
			</tr>

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>d) CONTRAPRESTACIÓN (consignar valor sin IGV)</b></td>
			</tr> 

			<tr>
				<td width="150">Monto:</td>
				<td width="450" colspan="3">'.(!empty($data['termiesp_d_monto'])?number_format($data['termiesp_d_monto'], 2, '.', ' '):"")." ".(!empty($data['termiesp_d_monto'])?$data['data_tipomodena']:"").'</td>
			</tr>

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>e) FORMA DE PAGO</b></td>
			</tr>
			
			'.$formapago_html.'

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>f) MODALIDAD DE PAGO</b></td>
			</tr>
			
			<tr>
				<td  width="150">Modo:</td>
				<td width="450" colspan="3">'.$data['data_modalidadpago'].$detalle_modo_pago.'</td>
			</tr>

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>g) GARANTÍAS</b></td>
			</tr>
			
			'.$garantias_html.'
			
			<tr>				
				<td width="650" colspan="4" >'.(!empty(sizeof($files_moviliarios)>0)?"[ X ] Adjunta documentos rubro mobiliarias":'[   ] Adjunta documentos rubro mobiliarias').'</td>
			</tr>
			
			<tr>				
				<td width="650" colspan="4" >'.(!empty(sizeof($files_partidaregistral)>0)?"[ X ] Adjunta documentos partida registral":'[   ] Adjunta documentos partida registral').'</td>
			</tr>

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>h) LUGAR DE SERVICIO</b></td>
			</tr> 	

			<tr>
				<td width="650" colspan="4">'.(!empty($data['termiesp_h_lugarentrega'])?$data['termiesp_h_lugarentrega']:'.').'</td>
			</tr>
			
			'.$personal_tercero.'

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>i) OBSERVACIONES O AMPLIACIONES</b></td>
			</tr>

			<tr>				
				<td width="650" colspan="4">'.(!empty($data['termiesp_i_observacionesamplicaciones'])?$data['termiesp_i_observacionesamplicaciones']:'.').'</td>
			</tr>
			
			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>j) METAS A CUMPLIR (ENTREGABLES)</b></td>
			</tr>

			<tr>				
				<td width="650" colspan="4" class="borderbotton">'.(!empty($data['metas_cumplir_comentario'])?$data['metas_cumplir_comentario']:'').'</td>
			</tr>
			
			<tr>				
				<td width="650" colspan="4" >'.(!empty($data['metas_cumplir_entregables'])?"[ X ] Adjunta documentos":'[   ] Adjunta documentos').'</td>
			</tr>
			
			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>k) RUBRO DE PENALIDADES</b></td>
			</tr>
			
			'.$penalidades_html.'

			<tr>
				<td width="650"class="color1normal"colspan="4"> <b>l) CONTRATO PROPUESTO POR EL PROVEEDOR</b></td>
			</tr>
			
			<tr>				
				<td width="650" colspan="4" class="borderbotton">'.(!empty($data['contrato_propuesto_proveedor'])?"[ X ] Adjunta documentos":'[   ] Adjunta documentos').'</td>
			</tr>
			
		</table>

		<table border="1" class="colorTabla" width="650"> 			
			
			<tr>
				<td width="650"class="color1normal"colspan="2"> <b><u>III. REQUISITOS ESPECIALES DEL CONTRATO</u></b></td>
			</tr> 		
			
			<tr>
				<td width="150">RUTA:</td>
				<td width="450">'.$data['reqesp_ruta'].'</td>
			</tr>

			<tr>
				<td  width="650" colspan="2">					
					- Legal trabajará con los archivos adjuntos al momento de la recepción de la SEC.<br>
					- Todo documento digitalizado según sea el caso, debe ser previamente firmado por el ente que lo emitió.<br>
					- El nombre de la carpeta que contenga los requisitos deberá ser el # de la SEC.
				</td>
			</tr>
			
		</table>
		
		<table border="1" class="colorTabla" width="650"> 			
			
			<tr>
				<td width="650"class="color1normal"colspan="1"> <b><u>IV. AUTORIZACIONES</u></b></td>
			</tr> 
			
			<tr>
				<td width="650"class="color1normal"colspan="1"> <b>CREADOR SEC</b></td>
			</tr> 

			<tr>
				<td  width="650" colspan="1">Nombre:  '.$data['autorizac_a_nombres'].'</td>
			</tr>
			<tr>
				<td  width="650" colspan="1">Cargo:  '.$data['autorizac_a_cargo'].'</td>
			</tr>
			<tr>
				<td  width="650" colspan="1">Fecha:  '.$data['autorizac_a_fecha'].'</td>
			</tr>
			<tr>
				<td  width="650" colspan="1" height="70">Firma:</td>
			</tr>
			
			<tr>
				<td width="650"class="color1normal"colspan="1"> <b>JEFATURA ÁREA USUARIA</b></td>
			</tr>
			
			<tr>
				<td  width="650" colspan="1">Nombre:  '.$data['autorizac_b_nombres'].'</td>
			</tr>
			<tr>
				<td  width="650" colspan="1">Cargo:  '.$data['autorizac_b_cargo'].'</td>
			</tr>
			<tr>
				<td  width="650" colspan="1">Fecha:  '.$data['autorizac_b_fecha'].'</td>
			</tr>
			<tr>
				<td  width="650" colspan="1" height="70">Firma:</td>
			</tr>
			
			'.$jefatura_logistica_aprove.'
			
		</table>

		</page>		
		';		
		
	return "Exito";
		
}
