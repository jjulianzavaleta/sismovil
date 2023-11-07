<?php

function getVale_filtro1($nrovoucher){

	if( empty($nrovoucher) )return array();
	
    $sql = "select v.id, v.placa, vu.name1 as chofer, vu.num_doc_identidad as chofer_dni, 
			  convert(varchar, v.consumo_fechaconsumo, 103) as fechaconsumo, v.consumo_unidadmedida
		from vales_vale v
		inner join vales_usuarioweb vu on vu.id = v.chofer
		inner join vales_detalle_productos vdp on vdp.idvale = v.id
		inner join vales_material vm on vm.id = vdp.matnr
		where vdp.voucher_nro = '".strtoupper(trim($nrovoucher))."'";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if( !empty($data) && sizeof($data) == 1 ){
		return $data[0];
	}else{
		return array();
	}

}

function validaciones_campos_ok($placa, $documento, $fechaconsumo, $codigo_sap){
	
	$error_msg 	  = "";
	$datos_leidos = "Datos leidos:<br><br>
				<table border='2'>
					<tr>
						<td>Placa</td>
						<td>".$placa."</td>
					</tr>
					<tr>
						<td>DNI</td>
						<td>".$documento."</td>
					</tr>
					<tr>
						<td>FECHA CONSUMO</td>
						<td>".$fechaconsumo."</td>
					</tr>
					<tr>
						<td>CODIGO SAP</td>
						<td>".$codigo_sap."</td>
					</tr>
				</table><br><br>";
	$sugerencia   = "<br>Sugerencia: Verifique los tipos de columna de PLACA, DNI, FECHA CONSUMO Y CODIGO SAP. Estos deben ser tipo TEXTO.";
	$sugerencia.=   "<br>Sugerencia: Verifique los valores esten en la posicion columna adecuada.";
	$sugerencia.=	"
					<table border='2'>
						<tr>
							<td>Serie vale</td>
							<td>Columna T</td>
						</tr>
						<tr>
							<td>Placa</td>
							<td>Columna D</td>
						</tr>
						<tr>
							<td>DNI documento</td>
							<td>Columna I</td>
						</tr>
						<tr>
							<td>Fecha consumo</td>
							<td>Columna J</td>
						</tr>
						<tr>
							<td>Codigo sap</td>
							<td>Columna K</td>
						</tr>
						<tr>
							<td>Centro costo</td>
							<td>Columna G</td>
						</tr>
						<tr>
							<td>Nombre conductor</td>
							<td>Columna H</td>
						</tr>
						<tr>
							<td>Nombre producto</td>
							<td>Columna L</td>
						</tr>
						<tr>
							<td>Cantidad consumida</td>
							<td>Columna M</td>
						</tr>
						<tr>
							<td>Precio</td>
							<td>Columna O</td>
						</tr>
					</table>";
	
	//Validaciones si vacio
	if( empty($placa) )
		$error_msg.="Error: Placa esta vacio<br>";
	if( empty($documento) )
		$error_msg.="Error: DNI chofer esta vacio<br>";
	if( empty($fechaconsumo) )
		$error_msg.="Error: Fecha consumo esta vacio<br>";
	if( empty($codigo_sap) )
		$error_msg.="Error: Codigo SAP esta vacio<br>";
	
	//Validaciones si formato		
	if( !preg_match('/^([0-9]*)$/', $documento) )
		$error_msg.="Error: DNI solo debe contener números<br>";
	if( strlen($fechaconsumo) != 10 )
		$error_msg.="Error: Fecha de consumo esta en formato inválido<br>";
	if( !preg_match('/^([0-9]*)$/', $codigo_sap) )
		$error_msg.="Error: CODIGO SAP solo debe contener números<br>";
		
	if( empty($error_msg) )
		return true;
	else{
		die($datos_leidos.$error_msg.$sugerencia);
	}
	
}

function getVale_filtro2($placa, $documento, $fechaconsumo, $codigo_sap){
	
	if(!validaciones_campos_ok($placa, $documento, $fechaconsumo, $codigo_sap))
		return array();
	
	
	
	$fechaconsumo = substr($fechaconsumo,6,4)."-".substr($fechaconsumo,3,2)."-".substr($fechaconsumo,0,2);

    $sql = "select v.id, v.placa, vu.name1 as chofer, vu.num_doc_identidad as chofer_dni, 
				  convert(varchar, v.consumo_fechaconsumo, 103) as fechaconsumo, v.consumo_unidadmedida
			from vales_vale v
			inner join vales_usuarioweb vu on vu.id = v.chofer
			inner join vales_detalle_productos vdp on vdp.idvale = v.id
			inner join vales_material vm on vm.id = vdp.matnr
			where UPPER(v.placa) = '".strtoupper(trim($placa))."' and
				  vu.num_doc_identidad = '".strtoupper(trim($documento))."' and
				  convert(varchar, v.consumo_fechaconsumo, 23) = '".strtoupper(trim($fechaconsumo))."' and
				  vm.cod_sap = '".strtoupper(trim($codigo_sap))."'";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if( !empty($data) && sizeof($data) == 1 ){
		return $data[0];
	}else{
		return array();
	}

}

function get_detallevalemateriales($idvale){
	
	 $sql = "select vdp.id, vm.cod_sap, vm.nombre, vdp.menge_chofer as cantidad_chofer
			from vales_detalle_productos vdp
			inner join vales_material vm on vm.id = vdp.matnr
			where vdp.idvale = ".$idvale;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if( !empty($data) ){
		return $data;
	}else{
		return array();
	}
}

function get_asignacionesmateriales($idvale){
	
	 $sql = "select vcw.kostl as centrocosto, vm.cod_sap
			from vales_detalle_asignacion va
			inner join vales_centroweb vcw on vcw.id = va.kostl
			inner join vales_material vm on vm.id = va.matnr
			where va.idvale = ".$idvale;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if( !empty($data) ){
		return $data;
	}else{
		return array();
	}
}

function save_to_db($idvale, $cantidad_string, $precio_string, $id_item){
	
	if( empty($idvale) || empty($id_item) ) return false;
	
	$cantidad 	= floatval($cantidad_string);
	$precio		= round( floatval($precio_string) , 3);
	$total 		= round( $cantidad * $precio , 3);
	
	$link = conectarBD();
	
	$sql = "update vales_detalle_productos
			set fromexcel_total = ".$total.",
				fromexcel_cantidad = ".$cantidad.",
				fromexcel_precio = ".$precio." 
			where id = ".$id_item;
			
    $res = queryBD($sql,$link);	
	
	$link = null;
	
	if($res === false)return false; else return true;	
}