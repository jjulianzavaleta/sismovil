<?php

    include("validaciones.php");

	$user = basic_validations( $_POST['user'] );
	$pass = basic_validations( $_POST['pass'] );
	$login_mode = basic_validations( $_POST['login_mode'] );
	
	if( empty($user) || empty($pass) ){
		session_start();
		$_SESSION['noLogin']      = 1;
		header('Location:./../index.php') ;
	}

	if($login_mode == 1){//LOGIN AL SISTEMA PAVIFERIA USANDO TABLA admin_paviferia
	
		include("dpaviferia_admin.php");
		
		$data = getUsuarioByUsernameAndPassword($user,$pass);

		if(!empty($data)) {

			session_start();
			$_SESSION['login']                  = 1;
			$_SESSION['username']               = $data['usuario'];
			$_SESSION['id']                     = $data['id'];
			$_SESSION['permission_data']        = 0;
			$_SESSION['permission_pedidos']     = 0;
			$_SESSION['permission_paviferia']   = $data['permission_paviferia'];
			$_SESSION['isadmin']                = $data['isadmin'];
			$_SESSION['manageusers']            = $data['manageusers'];			
			
			$_SESSION['departamento']   		= $data['departamento'];
			$_SESSION['puesto']   				= $data['puesto'];
			$_SESSION['puesto2']   				= $data['puesto2'];
			$_SESSION['from_activedirectory']   = 0;			

			if($_SESSION['permission_data'] === 1 || $_SESSION['permission_pedidos'] === 1 ||
			   $_SESSION['permission_paviferia'] === 1){
				header('Location:../paviferia_admin/index.php') ;
			}else{
				$_SESSION['noLogin']      = 1;
				header('Location:./../index.php') ;
			}
		}else{
			session_start();
			$_SESSION['noLogin']      = 1;
			header('Location:./../index.php') ;
		}
		
	}else if($login_mode == 2){//LOGIN AL SISTEMA DE CONTRATOS O VALES USANDO ACTIVE DIRECTORY Y TABLA admin
		
		include("conexion.php");
		include("dlogin.php");
		include("dcontract_usuarios.php");
		
		$data = validar_usuario($user, $pass);
		
		if(!empty($data)){
			session_start();
			$_SESSION['login']                  = 1;
			$_SESSION['username']               = $data['usuario'];
			$_SESSION['id']                     = intval($data['id']);
			$_SESSION['permission_data']        = intval($data['permission_data']);
			$_SESSION['permission_pedidos']     = intval($data['permission_pedidos']);
			$_SESSION['permission_paviferia']   = intval($data['permission_paviferia']);			
			$_SESSION['isadmin']                = intval($data['isadmin']);
			$_SESSION['manageusers']            = intval($data['manageusers']);			
			
			$_SESSION['departamento']   		= $data['departamento'];
			$_SESSION['puesto']   				= $data['puesto'];
			$_SESSION['puesto2']   				= $data['puesto2'];
			$_SESSION['from_activedirectory']   = 1;

			$permissions_contracts = getPermissionsUsuarioContract( $_SESSION['username'] );
			
			if( isset($_COOKIE["fromEmailContratosNotificacion"]) && !empty($permissions_contracts) ){
				$cookie_fromemail = $_COOKIE["fromEmailContratosNotificacion"];
				unset($_COOKIE['fromEmailContratosNotificacion']); 
				setcookie('fromEmailContratosNotificacion', '', time() - 3600, '/sismovil/');
				header('Location:../contract_miscontratos/redirect.php'.$cookie_fromemail) ;
			}else if( !empty($permissions_contracts) ){
				$hasExtraPermisos = hasUsuarioExtraPermisos($data['usuario']);
				if($hasExtraPermisos){
					header('Location:../contract_admin/elegir_area_contratos.php') ;
				}else{
					header('Location:../contract_admin/index.php') ;
				}

			}else{
				header('Location:../vales_planner/index.php') ;	
			}
			
		}else{
			session_start();
			$_SESSION['noLogin']      = 1;
			header('Location:./../index.php') ;
		}	
		
	} else if($login_mode == 3){//LOGIN AL SISTEMA DE VALES USANDO (FLUJO 1 - CONSUMIDOR) TABLA vales_usuariosweb
		
		include("conexion.php");
		include("dvales_consumidor.php");
		
		$data = login_consumidor($user, $pass);
		
		if( !empty($data)){
			session_start();
			$_SESSION['login']                  = 1;
			$_SESSION['username']               = $data['num_doc_identidad'];
			$_SESSION['id']                     = intval($data['id']);
			$_SESSION['name']					= $data['name1'];
			$_SESSION['from_activedirectory']	= 0;			
			$_SESSION['isadmin']				= 0;
			$_SESSION['permission_paviferia']	= 0;
			
			$_SESSION['permission_valesconsumidor']					= 1;
			
			header('Location:../vales_consumidor/index.php') ;
		}else{
			session_start();
			$_SESSION['noLogin']      = 1;
			header('Location:./../index.php') ;
		}
		
	}else{
		header('Location:./../index.php') ;
	}
?>
