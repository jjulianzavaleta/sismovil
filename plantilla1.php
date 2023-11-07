<?php

session_start();

if(!isset($_SESSION['login'])){
    session_destroy();
    header("location:../index.php");
}

$permissions_contracts = false;
$permissions_vales     = false;
if( $_SESSION['from_activedirectory'] == 1 ){
	include_once("phps/conexion.php");
	include_once("phps/dcontract_usuarios.php");
	include_once("phps/dvales_usuarios.php");
	$permissions_contracts = getPermissionsUsuarioContract( $_SESSION['username'] );
	$permissions_vales     = getPermissionsUsuarioVales( $_SESSION['username'] );
}

$SUCCES_MESSAGE = "Exito";
$ERROR_MESSAGE = "Error";

$SLEEP_TIME = 450;
$SLEEP_TIME_FOCUS = 1000;

$EDITAR_HTML_CODE =
    '<td class="td-actions">'.
        '<div class="btn-group">'.
        '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar">'.
            '<i  class="icon-edit bigger-120"></i>'.
        '</a>'.
        '</div>'.
    '</td>';

$ELIMINAR_HTML_CODE =
    '<td class="td-actions">'.
        '<div class="btn-group">'.
        '<button type="button" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger">'.
            '<i  class="icon-trash bigger-120"></i>'.
        '</button>'.
        '</div>'.
    '</td>';

$EDITAR_ELIMINAR_HTML_CODE =
    '<td class="td-actions">'.
        '<div class="btn-group">'.
        '<a  alt="Editar" title="Editar" data-toggle="modal" class="btn btn-mini btn-info" href="#editar">'.
            '<i  class="icon-edit bigger-120"></i>'.
        '</a>'.
        '<button alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger">'.
            '<i  class="icon-trash bigger-120"></i>'.
        '</button>'.
        '</div>'.
    '</td>';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>SISVENMOV Beta</title>
    </title>
    <link rel="shortcut icon" href="../assets/images/ico_fv.ico">
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script src="../assets/js/jquery-1.9.1.min.js"></script>
    <?php if( isset($loadNewestDataTablerVersion) ){
        echo '<script src="../assets/js/jquery.dataTablesNV.min.js"></script>';
    }else{
        echo '  <script src="../assets/js/jquery.dataTables.min.js"></script>';
    }
    ?>

    <script src="../assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="../assets/js/jquery.validate.min.js"></script>
    <script src="../assets/js/bootbox.min.js"></script>
    <script src="../assets/js/date-time/bootstrap-datepicker.min.js"></script>

    <script src="../assets/js/jquery.toastmessage.js"></script>
    <link rel="stylesheet" href="../assets/js/resources/css/jquery.toastmessage.css">

    <script src="../assets/js/ace-elements.min.js"></script>
    <script src="../assets/js/ace.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!--basic styles-->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="../assets/css/bootstrap-responsive.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css"/>

    <!--[if IE 7]>
    <link rel="stylesheet" href="../assets/css/font-awesome-ie7.min.css"/>
    <![endif]-->

    <!--ace styles-->

    <style type="text/css">
        .hide_column{
            display : none;
        }
        .uppercase{
            text-transform: uppercase;
        }

        .row_css{
            font-size: 11px;
        }

        .tdClase {
            text-align: center;
            font-size: 11px;
        }

        .tdClase1 {
            text-align: center;
            font-size: 9px;
        }
    </style>

    <link rel="stylesheet" href="../assets/css/ace.min.css"/>
    <link rel="stylesheet" href="../assets/css/ace-responsive.min.css"/>
    <link rel="stylesheet" href="../assets/css/ace-skins.min.css"/>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="../assets/css/ace-ie.min.css"/>
    <![endif]-->
    <link rel="stylesheet" href="../media/css/ajaxLoading.css"/>
    <script>
        function openModal() {
            document.getElementById('modal2').style.display = 'block';
            document.getElementById('fade2').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal2').style.display = 'none';
            document.getElementById('fade2').style.display = 'none';
        }

        jQuery.validator.addMethod("max_cifras_decimales", function(value, element) {

            var isValid = false;

            if(value.toString().indexOf(".") > 0){//si contiene un punto
                var aux = value.toString().split(".");

                if(aux[1].length > 4 || aux[0].length > 10){
                    isValid = false;
                }else{
                    isValid = true;
                }

            }else{
                if(value.toString().length > 10){
                    isValid = false;
                }else{
                    isValid = true;
                }

            }

            return this.optional(element) || isValid;

        }, "Cifra no cumple el formato");
    </script>

</head>

<body onload="closeModal()">

<div id="fade2"></div>
<div id="modal2" style="display: none">
    <img src="../assets/images/712.GIF">
</div>

<script>openModal()</script>

<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a href="#" class="brand">
                <small>
                    <i class=" icon-dashboard"></i>
                    Administrador
                    <?php if( !empty($permissions_contracts) ){
                        $area_name__ = getAreaName($permissions_contracts[0]['idarea']);
                        echo "- Contratos - Area ".$area_name__;
                     }?>
                </small>
            </a><!--/.brand-->

            <ul class="nav ace-nav pull-right">
                <li class="light-blue user-profile">
                    <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                        <img class="nav-user-photo" src="../assets/avatars/user.jpg"/>
                                <span id="user_info">
                                    <small>Bienvenido,</small>
                                    <b>
                                    <?php
                                       echo $_SESSION['username'];
                                    ?>
                                    </b>
                                </span>

                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer" id="user_menu">
                        <li>
                            <a href="../phps/logout.php">
                                <i class="icon-off"></i>
                                Cerrar Sesion
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--/.ace-nav-->
        </div>
        <!--/.container-fluid-->
    </div>
    <!--/.navbar-inner-->
</div>

<div class="container-fluid" id="main-container">
    <a id="menu-toggler" href="#">
        <span></span>
    </a>

    <div id="sidebar">
        <div id="sidebar-shortcuts">

        </div>
        <!--#sidebar-shortcuts-->

        <ul class="nav nav-list">



			<?php if( !empty($permissions_contracts) ){ ?>
				<li id="adminPlantilla1">
					<a href="../contract_admin/index.php">
						<i class="icon-lock"></i>
						<span>Administradores</span>
					</a>
				</li>
			<?php }else if( empty($permissions_vales) && !isset($_SESSION['permission_valesconsumidor'])){?>
				<li id="adminPlantilla1">
					<a href="../paviferia_admin/index.php">
						<i class="icon-lock"></i>
						<span>Administradores</span>
					</a>
				</li>
			<?php }?>
            

            <?php if($_SESSION['isadmin'] === 1 && $_SESSION['from_activedirectory'] == 0){ //Solo administradores Y no AD?>
             <li id="dbasesPlantilla1">
                 <a href="../menu/datos_bases.php">
                        <i class="icon-folder-close"></i>
                        <span>Usuarios Paviferia</span>
                 </a>
             </li>
            <?php } ?>
			
			<?php if( !empty($permissions_contracts) ){ ?>
				<?php			
				if($permissions_contracts[0]['permission_admin'] == 1){ ?>
				 <li id="dbasesContratos">
					 <a href="../menu/bases_contract.php">
							<i class="icon-list-alt"></i>
							<span>Bases Contratos</span>
					 </a>
				 </li>
				<?php } ?>
			<?php } ?>
			
			<?php if( !empty($permissions_vales) ){ ?>
				<?php			
				if($permissions_vales[0]['permission_admin'] == 1){ ?>
				 <li id="dbasesVales">
					 <a href="../menu/bases_vales.php">
							<i class="icon-tag"></i>
							<span>Bases Vales</span>
					 </a>
				 </li>
				<?php } ?>
			<?php } ?>	
           

            <?php
            if($_SESSION['isadmin'] === 1 && $_SESSION['permission_paviferia'] === 1){//Administrador Paviferia
                ?>

                <li id="bpaviferiaPlantilla1">
                    <a href="../menu/bases_paviferia.php">
                        <i class="icon-gift"></i>
                        <span>Bases Paviferia</span>
                    </a>
                </li>
                <?php
            }
            ?>
           

            <?php
            if($_SESSION['permission_paviferia'] === 1){//Usuario para Pedidos Paviferia
                ?>

                <li id="pedidospaviferiaPlantilla1">
                    <a href="../paviferia_pedidos/index.php">
                        <i class=" icon-tag"></i>
                        <span>Pedidos Paviferia</span>
                    </a>
                </li>
                <?php
            }
            ?>
			
			<?php
			if( !empty($permissions_contracts) ){
				?>
				<li id="listar_contract">
                    <a href="../contract_miscontratos/index.php">
                        <i class=" icon-briefcase"></i>
                        <span>Mis contratos</span>
                    </a>
                </li>
				<?php
			}
			?>
			
			<?php
			if( !empty($permissions_contracts) ){
				if($permissions_contracts[0]['permission_responsablearea'] == 1){
					?>
					<li id="listar_contract_approve_miarea">
						<a href="../contract_approve_miarea/index.php">
							<i class=" icon-legal"></i>
							<span>Contratos mi área</span>
						</a>
					</li>
					<?php
				}
			}
			?>
			
			<?php
			if( !empty($permissions_contracts) ){
				if($permissions_contracts[0]['permission_aprobar'] == 1 && $permissions_contracts[0]['idarea'] == 1/*Are Legal*/){
					?>
					<li id="listar_contract_approve">
						<a href="../contract_approve/index.php">
							<i class=" icon-legal"></i>
							<span>Contratos legal</span>
						</a>
					</li>
					<?php
				}
			}
			?>
			
			<?php
			if( !empty($permissions_contracts) ){
				if($permissions_contracts[0]['permission_responsablearea'] == 1 && $permissions_contracts[0]['idarea'] == 20/*Are Logistica*/){
					?>
					<li id="listar_contract_approve_logistica">
						<a href="../contract_approve_logistica/index.php">
							<i class=" icon-legal"></i>
							<span>Contratos logística</span>
						</a>
					</li>
					<?php
				}
			}
			?>
			
			<?php if( !empty($permissions_vales) ){ ?>
				<?php			
				if($permissions_vales[0]['permission_planner'] == 1){ ?>
				 <li id="dValesMain">
					 <a href="../vales_planner/index.php">
							<i class="icon-cogs"></i>
							<span>Planner Vales</span>
					 </a>
				 </li>
				<?php } ?>
			<?php } ?>
			
			
			<?php
			if( !empty($permissions_contracts) ){
				if($permissions_contracts[0]['permission_reportes'] == 1){
					?>
					<li id="listar_contract_reportes">
						<a href="../contract_reportes/index.php">
							<i class=" icon-zoom-in"></i>
							<span>Reportes Contratos</span>
						</a>
					</li>
					<?php
				}
			}
			?>
			
			
			
			<?php
			if( !empty($permissions_vales) ){
				if($permissions_vales[0]['permission_reportes'] == 1){
					?>
					<li id="listar_vales_reportes">
						<a href="../vales_reportes/index.php">
							<i class=" icon-zoom-in"></i>
							<span>Reportes Vales</span>
						</a>
					</li>
					<?php
				}
			}
			?>
			
			
			<?php
			if( isset($_SESSION['permission_valesconsumidor']) && $_SESSION['permission_valesconsumidor'] == 1){
				?>
					<li id="listar_vales_reportes">
						<a href="../vales_consumidor/index.php">
							<i class=" icon-zoom-in"></i>
							<span>Comsumidor Vales</span>
						</a>
					</li>
				<?php
				
			}
			?>
			


        </ul>
        <!--/.nav-list-->

        <div id="sidebar-collapse">
            <i class="icon-double-angle-left"></i>
        </div>
    </div>

    <div id="main-content" class="clearfix">
        <div id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="icon-home"></i>
                    <a href="#">Inicio</a>

                            <span class="divider">
                                <i class="icon-angle-right"></i>
                            </span>
                </li>
                <li class="activePlantilla1"></li>
            </ul>
        </div>


