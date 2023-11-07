<?php

 if( isset( $_GET['email'] ) && isset( $_GET['id'] ) ){
	 
	session_start();
	if(!isset($_SESSION['login'])){
		setcookie('fromEmailContratosNotificacion', "?id=".$_GET['id']."&email=true", time()+3600, '/sismovil/');
		session_destroy();
		header("location:../index.php");
	}else{
		include("../phps/conexion.php");
		include("../phps/dcontract_usuarios.php");
		
		$permissions_contracts = getPermissionsUsuarioContract( $_SESSION['username'] );
		
		$isLogistica = $permissions_contracts[0]['idarea'] == 20 ? true : false;
		$isLegal     = $permissions_contracts[0]['idarea'] == 1 ? true : false;
		$isJefeArea  = $permissions_contracts[0]['permission_responsablearea'] == 1 ? true : false;
		
		if( $isLogistica &&  $isJefeArea ){
			header("location: create.php?id=".$_GET['id']."&mode=approve&role=logistica");
		}else if( $isLegal && $isJefeArea ){
			header("location: create.php?id=".$_GET['id']."&mode=approve&role=legal");
		}else if( $isJefeArea ){
			header("location: create.php?id=".$_GET['id']."&mode=approve&role=jefe");
		}else{
			header("location: create.php?id=".$_GET['id']."&mode=edit");
		}
	}
 }else{
	 header('Location: index.php');
 }