<?php

function exectActions_vales(){
	exec_cerrarvalesvencidos();
}

function exec_cerrarvalesvencidos(){
	/* 
	 - obtener vales vencidos y cerrar vales vencidos
	 - Enviar correo con confirmacion de exito
	*/	
	$res = getCerrarValesVencidos();	
	
	$estado = "";
	if($res === false){
		$estado = "Ocurrio un error al cerrar vales vencidos";
	}else{
		$estado = "Vales vencidos cerrados con exito";
	}
	
	send_status_by_email("ESTADO DE CONTRATOS VENCIDOS ".date("Y-m-d"), $estado);
	
}