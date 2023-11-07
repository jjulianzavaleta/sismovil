<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../plantilla1.php");
include_once("../phps/conexion.php");
include_once("../phps/dcontract_auxiliares.php");
include_once("../phps/dcontract_contratos.php");
include_once("../phps/dcontract_usuarios.php");
include_once("../phps/dContract_permisosAdicionales.php");
include_once("../phps/dAdmin.php");

$NOMBRE_SHOW 	 = "Contrato";
$btn_submit_name = "Guardar";

/*Estados generales*/
global $ESTADO_SOLCONTRACT_REGISTRADO_USUARIO;
global $ESTADO_SOLCONTRACT_ELABORADO_LEGAL;
global $ESTADO_SOLCONTRACT_ESPERAR_ACEPTAR_ELABRACION_LEGAL;
global $ESTADO_SOLCONTRACT_APROBADO_RESPONSABLE_AREA;
global $ESTADO_SOLCONTRACT_ESPERAR_FIRMAS;
global $ESTADO_SOLCONTRACT_VIGENTE;
global $TIPO_FLOW_USUARIO;
global $TIPO_FLOW_LEGAL;
global $TIPO_RESPONSABLE_AREA;
global $TIPO_RESPONSABLE_LOGISTICA;
global $TIPO_USUARIO_COMPRADOR;
global $TIPO_USUARIO_NO_COMPRADOR;

/*Carga de datos requeridos para la creacion de la solicitud del contrato*/

$contract_empresas 			= getContract_Empresas();

$area_solicitante_temp      		= getContract_AreaUsuario($_SESSION['id']);
$area_solicitante_jefatura		 	= getContract_JefeArea($area_solicitante_temp);
$area_solicitante['departamento']   = $area_solicitante_temp['area'];


$compradores_logistica 		= getContract_CompradorLogitica();

$area_usuario_jefatura		= array();

$allContratosCodigos		= getAllCodigoContratos();
$areas				   		= getContract_AllAreas();
$proveedores		   		= getContract_AllProveedores();
$ruc_proveedor		   		= getContract_RUCProveedor($proveedores[0]['idproveedor']);
$tipo_contratos				= getContract_AllTipoContrato();
$plazo_vigencias			= getContract_AllPlazoVigencia();
$tiposMonedas				= getContract_AllTipoMoneda();
$avancez					= getContract_AllAvanze();
$tiposCreditos				= getContract_AllTipoCreditos();
$formas_pago				= getContract_AllFormasPago();
$modalidades_pago			= getContract_AllModalidadPago();
$garantias					= getContract_AllGarantias();
$datos_usuario_activedirectory= getContract_DatosUsuario($_SESSION['id']);
$datos_usuario_usuarioshabilitados= getPermissionsUsuarioContract( $_SESSION['username'] );
$renovacion_automatica		= "";
$renovacion_manual			= "";
$renovacion_sinrenovacion   = "";
$isLegalArea                = false;
$legal_advance_options      = false;

/*Carga de datos de una solicitud de contrato especifico, segun modo*/
$showData 			= false;
$noUserFlow   		= false;
$UsuarioFlow		= false;
$data_contract	 	= array();
$data_contract['anulado'] = 0;
$data_contract['datosgenerales_codigo'] = '';
$estado				= 0;
if( isset($_GET['id']) && $_GET['mode'] == 'edit'){	
	$data_contract 		= getContratoData($_GET['id']);
	
	if( empty($data_contract) ){
		die("Error: Contrato no existe");
	}
	
	$showData 			= true;
	$btn_submit_name 	= "Editar";
	$estado				= $data_contract['datosgenerales_estado'];
	$UsuarioFlow		= true;
	$TIPO_FLUJO_CONTRATO= $data_contract['tipo_flujo'];
	
	$area_solicitante_xx  = getContract_AreaUsuario_MOD2($data_contract['reqgen_a_areausuaria']);
	$area_usuaria_jefatura	= getContract_JefeArea($area_solicitante_xx);
	
}else if( isset($_GET['id']) && $_GET['mode'] == 'approve'){	
	$data_contract 		= getContratoData($_GET['id']);	
	
	if( empty($data_contract) ){
		die("Error: Contrato no existe");
	}
	
	$showData 			= true;
	$noUserFlow 		= true;
	$TIPO_FLUJO_CONTRATO= $data_contract['tipo_flujo'];
	$estado				= $data_contract['datosgenerales_estado'];
	
	$area_solicitante_xx  	= getContract_AreaUsuario_MOD2($data_contract['reqgen_a_areausuaria']);
	$area_usuaria_jefatura	= getContract_JefeArea($area_solicitante_xx);
	
}else if( !isset($_GET['id']) ){
	$UsuarioFlow		= true;
	$TIPO_FLUJO_CONTRATO = $TIPO_USUARIO_COMPRADOR;
	if($datos_usuario_usuarioshabilitados[0]['tipo_usuario'] == 2){
		$TIPO_FLUJO_CONTRATO = $TIPO_USUARIO_NO_COMPRADOR;
	}
}else{
	die("Error: Parámetros invalidos");
}

$currentUserNames_fromActiveDirectory = $datos_usuario_activedirectory[0]['nombres']." ".$datos_usuario_activedirectory[0]['apellidos'];
$currentUserCargo_fromActiveDirectory = $datos_usuario_activedirectory[0]['puesto'];
$currentFecha					 	  = date("d/m/Y");

/*Carga otros datos*/
$autorizac_a_nombres = $showData?$data_contract['autorizac_a_nombres']:$currentUserNames_fromActiveDirectory;
$autorizac_a_cargo	 = $showData?$data_contract['autorizac_a_cargo']:$currentUserCargo_fromActiveDirectory;
$autorizac_a_fecha	 = $showData?$data_contract['autorizac_a_fecha']:$currentFecha;
$renovacion_sinrenovacion = $showData?($data_contract['tipo_renovacion']==0?"checked='checked'":""):"checked='checked'";
$renovacion_automatica    = $showData?($data_contract['tipo_renovacion']==1?"checked='checked'":""):"";
$renovacion_manual        = $showData?($data_contract['tipo_renovacion']==2?"checked='checked'":""):"";

/*Valores por defecto*/
$tipocontrato_otrosdesc					=	"";
$tipo_contrato_cotizacion 				= 	"";
$tipo_contrato_fecha					=	"";
$alcance_contrato						=	"";
$plazo_vigencia_dias					=	"";
$plazo_vigencia_formato                 =   "";
$plazo_vigencia_inicio					=	"";
$plazo_vigencia_termino					=	"";
$plazo_vigencia_incluye_actaentrega		=	"";
$contraprestacion_monto					=	"";
$formapago_id_1							=	"none";
$formapago_id_3							=	"none";
$formapago_id_4							=	"none";
$garantia_id_2							=	"none";
$garantia_id_3							=	"none";
$garantia_id_4							=	"none";
$garantia_id_6							=	"none";
$modalidad_pago_otro					=	"none";
$modalidad_pago_tras_cuenta_de          =	"none";
$modalidadpago_otro						=	"";
$modalidadpago_transcuenta_desc         =   "";
$modalidadpago_adelanto_importe			=	"";
$modalidadpago_adelanto_exception		=	"";
$modalidadpago_fcumplimiento_importe	=	"";
$modalidadpago_fgarantia_importe		=	"";
$modalidadpago_cartafianza_importe		=	"";
$lugar_entrega							=	"";
$observaciones_amplicaciones			=	"";
$ruta									=	"";
$proveedor_tipo 						=	0;
$lugar_entrega_personal_tercero			=	"";
$lugar_entrega_personal_tercero_numero	=	"";
$lugar_entrega_personal_tercero_dias	=	"";
$lugar_entrega_personal_tercero_equipo	=	"";
$lugar_entrega_personal_tercero_tr		=	"none";
$metas_cumplir_comentario				=	"";
$monto_mobiliario						=	"";
$contraprestacion_incdocumento			=	"";
$cotizacion_bynrocontrato				=	"";
$displayFile_new_movimiento 			= false;

/*No permitir edicion de campos para algunos flujos*/
$select_disabled						=	"";
if( isset($_GET['id']) ){
	if($estado >= 0.3){
		$select_disabled				=	"disabled";
	}
}

/*Movimientos*/
$movimientos = array();
if(isset($_GET['id'])){
	$movimientos		=	getContractMovimientos($_GET['id']);
}

?>
<script src="../media/js/chosen.jquery.min.js"></script>
<link href="../media/css/chosen.min.css" rel="stylesheet"/>

<div id="exTab2" class="container">	
	<ul class="nav nav-tabs">
		<li class="active"><a  href="#1" data-toggle="tab">Solicitud</a></li>
		<?php if( $estado >= 0.3 ){ ?>
		<li><a href="#2" data-toggle="tab">Anexos</a></li>
		<li><a href="#3" data-toggle="tab">Solicitud PDF</a></li>
		<?php } ?>
		<li><a href="#4" data-toggle="tab">Aprobación</a></li>
		<?php if( $estado == 4 || $estado == 5){ ?>
		<li><a href="#5" data-toggle="tab">Documentos Finales</a></li>
		<?php } ?>
	</ul>

	<div class="tab-content " style="width:95%;">
		<div class="tab-pane active" id="1">
		  <div id="page-content" class="clearfix">
			<div class="row-fluid">
				<!--PAGE CONTENT BEGINS HERE-->

				<input id="auxidgrupo" type="hidden" value="0"  />


				<form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate" autocomplete="off" enctype='multipart/form-data'>

					<div class="row-fluid">
                        <?php
                        if( $data_contract['anulado'] == 1){
                            include("anulado_view.php");
                        }
                        ?>
						<?php if(!empty($data_contract['datosgenerales_codigo'])){ ?>
						<h5 class="center smaller lighter blue"><b><?=$data_contract['datosgenerales_codigo']?></b></h5>
						<?php } ?>

						<?php if(!empty($data_contract['contrato_vinculado'])){ ?>
						<?php     $vinculado = getContratoData($data_contract['contrato_vinculado']);?>
						<span class="label">
							<a href="create.php?id=<?=$data_contract['contrato_vinculado']?>&mode=<?=$_GET['mode']?>">
								Vinculado: <?=$vinculado['datosgenerales_codigo']?>
							</a>
						</span><br>
								
						<?php } ?>
						
						<input type="radio" name="renovacion" id="renovacion_sinrenovacion" value="0" <?=$renovacion_sinrenovacion?> <?=$select_disabled?>>Sin renovación
						<input type="radio" name="renovacion" id="renovacion_automatica" value="1" <?=$renovacion_automatica?> <?=$select_disabled?>>Renovación automática
						<input type="radio" name="renovacion" id="renovacion_manual" value="2" <?=$renovacion_manual?> <?=$select_disabled?>>Renovación manual
					
						<br>
						<span class="label label-success"><?php echo $TIPO_FLUJO_CONTRATO==2?"Flujo no comprador":"Flujo comprador"?></span>
					
						<h5 class="header smaller lighter blue">I. REQUISITOS GENERALES </h5>
						
						<h6 class="lighter blue">a) DATOS DE LA EMPRESA</h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  width="50%"><label class="control-label" for="empresa">*Empresa:</label>
									<div class="control-group">
									<select <?=isset($_GET['id'])?'disabled':'class="span6 chosen-select"'?>   name="empresa" id="empresa" <?=$select_disabled?>>
									<?php							
									  foreach($contract_empresas as $empresa){
										  $empresa_selected = "";
										  if($showData){
											  if($data_contract['reqgen_a_empresa'] == $empresa['id']){
												  $empresa_selected = "selected";
											  }
										  }
										  echo "<option value='".$empresa['id']."' ".$empresa_selected.">".$empresa['descripcion']."</option>";
									  }
									?>							  							
									</select>
									</div>
								</td>
								<td width="50%">                            
								</td>
							</tr>
							<tr>
								<td  width="50%"><label class="control-label" for="area_solicitante_usuario">*Área solicitante:</label>
									<div class="control-group">
									<?php
										if($showData){
											$area_solicitante['departamento'] = $data_contract['reqgen_a_areasolicitante'];
										}
									?>
									  <input readonly="readonly" type="text" id="area_solicitante_usuario" name="area_solicitante_usuario" value="<?=$area_solicitante['departamento']?>" <?=$select_disabled?>/>
									</div>
								</td>
								<td  width="50%"><label class="control-label" for="area_solicitante_jefatura">Jefatura:</label>
									<div class="control-group">								
									<select  class="span6 chosen-select" name="area_solicitante_jefatura" id="area_solicitante_jefatura" <?=$select_disabled?>>
									  <?php
									    $comprador_selected = "";
										foreach($area_solicitante_jefatura as $jefe){
											$comprador_selected = "";
											if($showData){
												if($data_contract['reqgen_a_areasolicitante_jefatura'] == $jefe['id']){
													$comprador_selected = "selected";
												}
											}
											echo "<option value='".$jefe['id']."' ".$comprador_selected.">".$jefe['usuario']."</option>";
										}
										if( $showData && $comprador_selected == ""){
											echo "<option value='".$data_contract['reqgen_a_areasolicitante_jefatura']."' selected>".getNombreFromUser_cp2($data_contract['reqgen_a_areasolicitante_jefatura'])."</option>";
										}
									  ?>
									</select>
									</div>
								</td>
							</tr>
							<?php if( $TIPO_FLUJO_CONTRATO == $TIPO_USUARIO_COMPRADOR ){ ?>
							<tr>
								<td  width="50%"><label class="control-label" for="comprador_responsable">*Comprador responsable:</label>
									<div class="control-group">
									<select  class="span6 chosen-select" name="comprador_responsable" id="comprador_responsable" <?=$select_disabled?>>
									  <?php
										foreach($compradores_logistica as $comprador){
											$comprador_selected = "";
											if($showData){
												if($data_contract['reqgen_a_compradorresponsable'] == $comprador['id']){
													$comprador_selected = "selected";
												}
											}
											echo "<option value='".$comprador['id']."' ".$comprador_selected.">".$comprador['usuario']."</option>";
										}
									  ?>
									</select>
									</div>
								</td>
								<td  width="50%">
								</td>
							</tr> 
							<?php } ?>
							<tr>
								<td  width="50%"><label class="control-label" for="area_usuario">*Área usuaria:</label>
									<div class="control-group">
									<select  class="span6 chosen-select" name="area_usuario" id="area_usuario" onchange="load_jefatura_area_usuario()" <?=$select_disabled?>>
									  <?php
										echo "<option value='0'>--Seleccione--</option>";
										foreach($areas as $area){
											$area_selected = "";
											if($showData){
												if($data_contract['reqgen_a_areausuaria'] == $area['id']){
													$area_selected = "selected";
												}
											}
											echo "<option value='".$area['id']."' ".$area_selected.">".$area['descripcion']."</option>";
										}
									   ?>
									</select>
									</div>
								</td>
								<td  width="50%"><label class="control-label" for="area_usuario_jefatura">Jefatura:</label>
									<div class="control-group">
									
									  <?php
										if(!$showData){
											echo '<select class="span6" name="area_usuario_jefatura" id="area_usuario_jefatura" '.$select_disabled.' ></select>';
										}else{
											$comprador_selected = "";
											echo '<select class="span6 chosen-select" name="area_usuario_jefatura" id="area_usuario_jefatura" '.$select_disabled.'>';
											foreach($area_usuaria_jefatura as $jefe){
												$comprador_selected = "";
												if($showData){
													if($data_contract['reqgen_a_areausuaria_jefatura'] == $jefe['id']){
														$comprador_selected = "selected";
													}
												}
												echo "<option value='".$jefe['id']."' ".$comprador_selected.">".$jefe['usuario']."</option>";
											}
											if( $showData && $comprador_selected == ""){
												echo "<option value='".$data_contract['reqgen_a_areausuaria_jefatura']."' selected>".getNombreFromUser_cp2($data_contract['reqgen_a_areausuaria_jefatura'])."</option>";
											}
											echo '</select>';	
										}
									  ?>
									
									</div>
								</td>
							</tr>
						</table>
						
						<h6 class="lighter blue">b) DATOS DEL PROVEEDOR / CLIENTE / CONTRAPARTE</h6>
						
						<table border="0" cellpadding="0" cellspacing="0" width="100%">                        
							<tr>
								<td  width="50%"><label class="control-label" for="proveedor">*Proveedor:</label>
									<div class="control-group">
									<select   class="span6 chosen-select" name="proveedor" id="proveedor" onchange="load_ruc_proveedor()" <?=$select_disabled?>>
									  <?php
										foreach($proveedores as $proveedor){
											$proveedor_selected = "";
											if($showData){
												if($data_contract['reqgen_proveedor'] == $proveedor['idproveedor']){
													$proveedor_selected = "selected";
												}
											}
											echo "<option value='".$proveedor['idproveedor']."' ".$proveedor_selected.">".$proveedor['razon_social']."</option>";
										}
									  ?>
									</select>
									</div>
								</td>
								<td  width="50%"><label class="control-label" for="proveedor_ruc">RUC:</label>
									<div class="control-group">
									<?php
										if($showData){
											$ruc_proveedor[0]['ruc'] = $data_contract['reqgen_proveedor_ruc'];
										}
									?>
									<input readonly="readonly" class="span6" type="text" name="proveedor_ruc" id="proveedor_ruc" value="<?=$ruc_proveedor[0]['ruc']?>" <?=$select_disabled?>>
									</div>
								</td>
							</tr> 
							<tr>
								<?php
									$display_mode_tipo_proveedor_1 = "none";
									$display_mode_tipo_proveedor_2 = "none";
									if($showData){
										$proveedor_tipo = $data_contract['proveedor_tipo'];
										if($proveedor_tipo == 1){
											$display_mode_tipo_proveedor_1 = "table-row";
										}else if($proveedor_tipo == 2){
											$display_mode_tipo_proveedor_2 = "table-row";
										}
									}
									?>
								<td colspan="2">
									<input type="radio" name="proveedor_tipo" id="proveedor_tipo_1" value="1" onclick="show_tipo_proveedor_data(1)" <?=($proveedor_tipo==1?"checked='checked'":"")?> <?=$select_disabled?>> Persona jurídica<br>									
								</td>
							</tr>
							<tr style="display:<?=$display_mode_tipo_proveedor_1?>" id="proveedor_juridica_tr">									
								<td colspan="2">	
									<table width="100%">										
										<tr width="100%">
										<td width="5%">
										</td>
										<td width="95%">
											<input  type="file" id="proveedor_jur_file_ficharuc" name="proveedor_jur_file_ficharuc" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?>/><small>*Copia simple de ficha RUC de una antigüedad no mayor a 30 días.</small><br>
											<input  type="file" id="proveedor_jur_file_represetante" name="proveedor_jur_file_represetante" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?>/><small>*Copia simple del DNI del representante legal.</small><br>
											<input  type="file" id="proveedor_jur_file_vigenciapoder" name="proveedor_jur_file_vigenciapoder" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?>/><small>*Original o copia legalizada de vigencia de poder del proveedor de una antigüedad no mayor a 30 días</small><br>
										</td>
										</tr>

										<?php
										if($showData  && $proveedor_tipo == 1){
										?>

										<tr>
										<td colspan="2">
											<table>
												<tr>
													<td>
											<div class="control-group">
											<?php if(!empty($data_contract['jur_file_ficharuc'])){ ?>
												<a href="#" onclick="ver_archivo('<?=$data_contract['jur_file_ficharuc']?>')">
													<img src="../assets/images/<?=getIconToDiplay($data_contract['jur_file_ficharuc'])?>" width="40" height="40" title="Ver Ficha RUC">
												</a>
												<p style="font-size: 10px" id="p_proveedor_jur_file_ficharuc"><?=$data_contract['jur_file_ficharuc']?></p>
												<?php if($estado == 0){ ?>
												<a href="#" onclick="delete_document('jur_file_ficharuc', '<?=$data_contract['jur_file_ficharuc']?>')">Eliminar Ficha Ruc</a>
												<?php } ?>
										    <?php } ?>
												</div>
													</td>
													<td style="width: 50px"></td>
													<td>
													<div class="control-group">
											<?php if(!empty($data_contract['jur_file_represetante'])){ ?>
												<a href="#" onclick="ver_archivo('<?=$data_contract['jur_file_represetante']?>')">
													<img src="../assets/images/<?=getIconToDiplay($data_contract['jur_file_represetante'])?>" width="40" height="40" title="Ver DNI del representante legal">
												</a>
												<p style="font-size: 10px" id="p_proveedor_jur_file_represetante"><?=$data_contract['jur_file_represetante']?></p>
												<?php if($estado == 0){ ?>
												<a href="#" onclick="delete_document('jur_file_represetante', '<?=$data_contract['jur_file_represetante']?>')">Eliminar DNI</a>
												<?php } ?>
											<?php } ?>
													</div>
													</td>
													<td style="width: 50px"></td>
													<td>
													<div class="control-group">
											<?php if(!empty($data_contract['jur_file_vigenciapoder'])){ ?>
												<a href="#" onclick="ver_archivo('<?=$data_contract['jur_file_vigenciapoder']?>')">
													<img src="../assets/images/<?=getIconToDiplay($data_contract['jur_file_vigenciapoder'])?>" width="40" height="40" title="Ver Vigencia de poder">
												</a>
												<p style="font-size: 10px" id="p_proveedor_jur_file_vigenciapoder"><?=$data_contract['jur_file_vigenciapoder']?></p>
												<?php if($estado == 0){ ?>
												<a href="#" onclick="delete_document('jur_file_vigenciapoder', '<?=$data_contract['jur_file_vigenciapoder']?>')">Eliminar Vigencia Poder</a>
												<?php } ?>
											<?php } ?>
											</div>
													</td>
												</tr>
											</table>
										</td>
										</tr>
										<?php
										}
										?>

									</table>
								</td>
							</tr>
							
							
							<tr>
								<td colspan="2">									
									<input type="radio" name="proveedor_tipo" id="proveedor_tipo_2" value="2" onclick="show_tipo_proveedor_data(2)" <?=($proveedor_tipo==2?"checked='checked'":"")?> <?=$select_disabled?>> Persona natural<br>
								</td>
							</tr>							
							
							<tr style="display:<?=$display_mode_tipo_proveedor_2?>" id="proveedor_natural_tr">								
								<td  colspan="2">	
									<table width="100%">
										<tr width="100%">
										<td width="5%">
										</td>
										<td width="95%">
											<input  type="file" id="proveedor_nat_file_ficharuc" name="proveedor_nat_file_ficharuc" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?>/><small>*Copia simple de ficha RUC de una antigüedad no mayor de 30 días.</small><br>
											<input  type="file" id="proveedor_nat_file_represetante" name="proveedor_nat_file_represetante" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?>/><small>*Copia legible del DNI de la persona natural.</small><br>											
										</td>
										</tr>

										<?php
										if($showData && $proveedor_tipo == 2){
										?>
										<tr>
										<td colspan="2">
											<table>
												<tr>
													<td>
											<div class="control-group">
										<?php if(!empty($data_contract['nat_file_ficharuc'])){ ?>
												<a href="#" onclick="ver_archivo('<?=$data_contract['nat_file_ficharuc']?>')">
													<img src="../assets/images/<?=getIconToDiplay($data_contract['nat_file_ficharuc'])?>" width="40" height="40" title="Ver Ficha RUC">
												</a>
											<p style="font-size: 10px" id="p_proveedor_nat_file_ficharuc"><?=$data_contract['nat_file_ficharuc']?></p>
											<?php if($estado == 0){ ?>
											<a href="#" onclick="delete_document('nat_file_ficharuc', '<?=$data_contract['nat_file_ficharuc']?>')">Eliminar ficha RUC</a>
											<?php } ?>
										<?php }?>
											</div>
													</td>
													<td style="width: 50px"></td>
													<td>
														<div class="control-group">
										<?php if(!empty($data_contract['nat_file_represetante'])){ ?>
												<a href="#" onclick="ver_archivo('<?=$data_contract['nat_file_represetante']?>')">
													<img src="../assets/images/<?=getIconToDiplay($data_contract['nat_file_represetante'])?>" width="40" height="40" title="Ver DNI">
												</a>
											<p style="font-size: 10px" id="p_proveedor_nat_file_represetante"><?=$data_contract['nat_file_represetante']?></p>
											<?php if($estado == 0){ ?>
											<a href="#" onclick="delete_document('nat_file_represetante', '<?=$data_contract['nat_file_represetante']?>')">Eliminar DNI</a>
											<?php } ?>
										<?php }?>
											</div>
													</td>
												</tr>
											</table>
										</td>
										</tr>
										<?php
										}
										?>
									</table>
								</td>
							</tr>
							
						</table>
					</div>
					
					<div class="row-fluid">
						<h5 class="header smaller lighter blue">II. TÉRMINOS ESPECÍFICOS DEL CONTRATO</h5>

						<h6 class="lighter blue">a) TIPO DE CONTRATO</h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  width="50%"><label class="control-label" for="tipo_contrato">*Tipo:</label>
									<div class="control-group">
									<select  class="span6 chosen-select" name="tipo_contrato" id="tipo_contrato" <?=$select_disabled?>>							
									  <option value='0'>---Seleccione---</option>
									<?php
										$tipoContratoSelected_id = "";
										foreach($tipo_contratos as $tipo_contrato){
											$tipo_contrato_selected = "";
											if($showData){
												if($data_contract['termiesp_a_tipocontrato'] == $tipo_contrato['id']){
													$tipo_contrato_selected = "selected";
													$tipoContratoSelected_id = $tipo_contrato['id'];
												}
											}
											echo "<option value='".$tipo_contrato['id']."' ".$tipo_contrato_selected.">".$tipo_contrato['descripcion']."</option>";
										}								 
									?>							  
									</select>									
									</div>
								</td>
								<td width="50%">                            
								</td>
							</tr>						
							
							<tr <?=($tipoContratoSelected_id == "14")?"style='display:table-row;'":"style='display:none;'"?> id="tr_tipocontrato_otrodesc" >
								<td width="50%">
									<label class="control-label" for="tipocontrato_otrosdesc">*Otro:</label>
									<?php if($showData){ 
										$tipocontrato_otrosdesc = $data_contract['tipocontrato_otrosdesc'];
									}
									?>
									<div class="control-group">
										<input type="text" id="tipocontrato_otrosdesc" name="tipocontrato_otrosdesc" value="<?=$tipocontrato_otrosdesc?>" <?=$select_disabled?>/>
									</div>
								</td>
								<td width="50%"></td>
							</tr>
							
							<?php
								if($showData){									
									if($data_contract['cotizacion_bynrocontrato'] == 1){
										$cotizacion_bynrocontrato = "checked='checked'";
									}
								}								
							?>
							
							
							<tr>
								<td  width="50%"><label class="control-label" for="tipo_contrato_cotizacion" id="label_numero_cotizacion">*Nro Cotización:</label>
									<div class="control-group">
									<?php
										if($showData){
											$tipo_contrato_cotizacion = $data_contract['termiesp_a_nrocotizacion'];
										}
									?>
									<input class="span6" type="text" name="tipo_contrato_cotizacion" id="tipo_contrato_cotizacion" value="<?=$tipo_contrato_cotizacion?>" <?=$select_disabled?>>
									</div>
								</td>
								<td  width="50%"><label class="control-label" for="tipo_contrato_fecha">*Fecha:</label>
									<div class="control-group">
									<?php
										if($showData){
											$tipo_contrato_fecha = $data_contract['termiesp_a_fecha'];											
										}
									?>							
									<input class="span6 date-picker" name="tipo_contrato_fecha" id="tipo_contrato_fecha" type="text"
											   data-date-format="yyyy/mm/dd"
											   value="<?=$tipo_contrato_fecha?>" <?=$select_disabled?>/>
									</div>
								</td>
							</tr>
							<tr>
								<td  width="50%"><label class="control-label" for="cotizacion_bynrocontrato"></label>
									<div class="control-group">
									<input type="checkbox" name="cotizacion_bynrocontrato" id="cotizacion_bynrocontrato" value="1" onClick="habilitar_cotizacion_contratovinculado()" <?=$cotizacion_bynrocontrato?> <?=$select_disabled?>>Vincular contrato						
									</div>
								</td>
								<td  width="50%">                           
								</td>
							</tr>
						</table>
						
						<h6 class="lighter blue">b) ALCANCE DEL CONTRATO</h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  width="50%"><label class="control-label" for="alcance_contrato">*Alcance:</label>
									<div class="control-group">
									<?php
										if($showData){
											$alcance_contrato = $data_contract['termiesp_b_alcance'];
										}
									?>								
									<textarea class="span6" name="alcance_contrato" id="alcance_contrato" rows="2" cols="50" <?=$select_disabled?> ><?=$alcance_contrato?></textarea>
									</div>
								</td>
								<td width="50%">                            
								</td>
							</tr>
							<tr>
						</table>
						
						<h6 class="lighter blue">c) PLAZO DE VIGENCIA </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  width="50%">								
									<div class="control-group">
									<?php
										if($showData){
											$plazo_vigencia_dias = $data_contract['termiesp_c_dias'];
											$plazo_vigencia_formato = $data_contract['termiesp_c_formato'];
										}
									?>
									*<select  class="span2 chosen-select" name="plazo_vigencia_formato" id="plazo_vigencia_formato" <?=$select_disabled?>>
										<option value='0' <?=$plazo_vigencia_formato==0?'selected':''?> >Días</option>
										<option value='1' <?=$plazo_vigencia_formato==1?'selected':''?>>Años</option>
									</select>
									*<input class="span4" type="text" name="plazo_vigencia_dias" id="plazo_vigencia_dias" onKeyPress="return soloNumerosEnterosPositivos(event)" value="<?=$plazo_vigencia_dias?>" <?=$select_disabled?>>
									*<select  class="span4 chosen-select" name="plazo_vigencia_medida" id="plazo_vigencia_medida" <?=$select_disabled?>>
										<option value='0'>---Seleccione---</option>
									  <?php
										foreach($plazo_vigencias as $plazo_vigencia){
											$plazo_vigencia_selected = "";
											if($showData){
												if($data_contract['termiesp_c_medida'] == $plazo_vigencia['id']){
													$plazo_vigencia_selected = "selected";
												}
											}
											echo "<option value='".$plazo_vigencia['id']."' ".$plazo_vigencia_selected.">".$plazo_vigencia['descripcion']."</option>";
										}
									  ?>					  
									</select>
									</div>
								</td>
								<td width="50%">                            
								</td>
							</tr>
							<tr>
								<td  width="50%"><label class="control-label" for="plazo_vigencia_inicio">*Fecha inicio:</label>
									<div class="control-group">
									<?php
										if($showData){
											$plazo_vigencia_inicio = $data_contract['termiesp_c_fechainicio'];
										}
									?>							
									<input class="span6 date-picker" name="plazo_vigencia_inicio" id="plazo_vigencia_inicio" type="text"
											   data-date-format="yyyy/mm/dd"
											   value="<?=$plazo_vigencia_inicio?>" <?=$select_disabled?>/>
									</div>
								</td>
								<td  width="50%"><label class="control-label" for="plazo_vigencia_termino">*Fecha término:</label>
									<div class="control-group">
									<?php
										if($showData){
											$plazo_vigencia_termino = $data_contract['termiesp_c_fechafin'];
										}
									?>
									<input class="span6 date-picker" name="plazo_vigencia_termino" id="plazo_vigencia_termino" type="text"
											   data-date-format="yyyy/mm/dd"
											   value="<?=$plazo_vigencia_termino?>" <?=$select_disabled?>/>
									</div>
								</td>
							</tr>
							<tr>
								<td  width="50%"><label class="control-label" for="plazo_vigencia_inicio"></label>
									<div class="control-group">
									<?php
										if($showData){
											if( $data_contract['termiesp_c_incluyeacta'] == 1 ){
												$plazo_vigencia_incluye_actaentrega = "checked='checked'";
											}									
										}
									?>
									<input type="checkbox" name="plazo_vigencia_incluye_actaentrega" id="plazo_vigencia_incluye_actaentrega" value="1" <?=$plazo_vigencia_incluye_actaentrega?> <?=$select_disabled?>>ACTA DE ENTREGA DE TERRENO							
									</div>
								</td>
								<td  width="50%">                           
								</td>
							</tr>
						</table>
						
						<h6 class="lighter blue">d) CONTRAPRESTACIÓN <small>(consignar valor sin IGV)</small> </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  width="100%"><label class="control-label" for="contraprestacion_monto">*Monto:</label>
									<div class="control-group">
									<?php
										$disabled_monto_cotraprestacion = "";
										if($showData){
											$contraprestacion_monto = $data_contract['termiesp_d_monto'];
											if( $data_contract['contraprestacion_incdocumento'] == 1 ){
												$contraprestacion_incdocumento = "checked='checked'";
												$disabled_monto_cotraprestacion = "disabled='disabled' style='background-color: #eee'";
											}
										}
									?>							
									<input class="span4" type="text" name="contraprestacion_monto" id="contraprestacion_monto" value="<?=$contraprestacion_monto?>" <?=$select_disabled?>  <?=$disabled_monto_cotraprestacion?> onKeyPress="return soloNumeros(event)">
									*<select  class="span4" name="contraprestacion_medida" id="contraprestacion_medida" <?=$select_disabled?> <?=$disabled_monto_cotraprestacion?>>
										<option value='0'>---Seleccione---</option>
									<?php
										foreach($tiposMonedas as $tiposMoneda){
											$tiposMoneda_selected = "";
											if($showData){
												if($data_contract['termiesp_d_moneda'] == $tiposMoneda['id']){
													$tiposMoneda_selected = "selected";
												}
											}
											echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
										}
									?>							  
									</select>
									</div>
								</td>
								<td width="50%">                            
								</td>
							</tr>
							<tr>
								<td  width="50%"><label class="control-label" for="plazo_vigencia_inicio"></label>
									<div class="control-group">
									<input type="checkbox" name="contraprestacion_incdocumento" id="contraprestacion_incdocumento" value="1" onClick="habilitar_subir_documento_montos()" <?=$contraprestacion_incdocumento?> <?=$select_disabled?>>Adjunta documento con precios							
									</div>
								</td>
								<td  width="50%">                           
								</td>
							</tr>
							<tr id="contraprestacion_file_tr" <?=empty($contraprestacion_incdocumento && $showData)?'style="display:none"':''?>>
								<td><label class="control-label" for="contraprestacion_file"></label>
									<div class="control-group">										
									Montos:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="file" id="contraprestacion_file" name="contraprestacion_file" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="">
									</div>
								</td>
							</tr>
							<?php
							if($showData){
							?>
								<tr>
									<td colspan="2">
										<div class="control-group">
											<?php if(!empty($data_contract['contraprestacion_file'])){ ?>
												<a href="#" onclick="ver_archivo('<?=$data_contract['contraprestacion_file']?>')">
													<img src="../assets/images/<?=getIconToDiplay($data_contract['contraprestacion_file'])?>" width="40" height="40" title="Ver contrato">
												</a>
												<p style="font-size: 10px" id="p_contraprestacion_file"><?=$data_contract['contraprestacion_file']?></p>
												<?php if($estado == 0){ ?>
													<a href="#" onclick="delete_document('contraprestacion_file', '<?=$data_contract['contraprestacion_file']?>')">Eliminar adjunto</a>
												<?php } ?>
											<?php } ?>
										</div>
									</td>
								</tr>
							<?php
							}
							?>
						</table>
						
						<h6 class="lighter blue">e) *FORMA DE PAGO</h6>

						<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<?php
							
							foreach($formas_pago as $forma_pago){						
								
								$formapago_selected = "";
								if($showData){
									if($data_contract['termiesp_e_formapago'] == $forma_pago['id']){
										$formapago_selected = "checked='checked'";
										
										if($data_contract['termiesp_e_formapago'] == 1){
											$formapago_id_1 = "block";
										}
										if($data_contract['termiesp_e_formapago'] == 3){
											$formapago_id_3 = "block";
										}
										if($data_contract['termiesp_e_formapago'] == 4){
											$formapago_id_4 = "block";
										}
									}
								}						
									
								echo "<tr>";
								echo "<td>";
								echo "<input type='radio' name='formapago' value='".$forma_pago['id']."' onclick='show_formapago_info(".$forma_pago['id'].")' ".$formapago_selected." ".$select_disabled.">".$forma_pago['descripcion'];
								echo "</td>";
								echo "</tr>";
								
								if($forma_pago['id'] == 1){
									?>
									<tr style="display:<?=$formapago_id_1?>" id="formapago_en_partes_detalles">
										<td width="20%">
										</td>
										<td  width="80%">	
											<table>
												<tr>
													<td>
														Tipo Moneda:
														<select  class="span6" name="formapago_medida" id="formapago_medida" <?=$select_disabled?>>
															<option value='0'>---Seleccione---</option>
														<?php
															foreach($tiposMonedas as $tiposMoneda){
																$tiposMoneda_selected = "";
																if($showData){
																	if($data_contract['formapago_medida'] == $tiposMoneda['id']){
																		$tiposMoneda_selected = "selected";
																	}
																}
																echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
															}
														?>							  
														</select>
													</td>
												</tr>
												<?php if(!$noUserFlow){ ?> 												
												<tr>
													<td>
														<img onclick="add_row_formapago_enpartes()" src="../assets/images/plusAudio.png" width="17" height="17">
													</td>									
												</tr>
												<?php } ?>
												<tr>									
													<td>
														<div id="table_report_wrapper" class="dataTables_wrapper" role="grid">
															<table id="table_report" class="table table-striped table-bordered table-hover dataTable"
																   aria-describedby="table_report_info">
																<thead>
																<tr role="row">
																   <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
																		 colspan="1"
																		 style="width: 50px;;font-size: 11px">Inicial
																	</th>
																	<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
																		colspan="1"
																		style="width: 50px;;font-size: 11px">%
																	</th>
																	<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
																		colspan="1"
																		style="width: 80px;;font-size: 11px">Importe
																	</th>                            
																	<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 30px;;font-size: 11px">
																		Eliminar
																	</th>
																</tr>
																</thead>
																<tbody role="alert" aria-live="polite" aria-relevant="all">
																<?php
																	if($showData){
																		$detalles_pago_partes = getConractFormaPagoPartesDetalles($_GET['id']);
																		$contador_pago_partes = 1;
																		foreach($detalles_pago_partes as $d){
																			echo '<tr>';
																			echo '<td>Pago '.$contador_pago_partes.'</td>';
																			echo '<td><input type="text" style="width: 50px;;font-size: 11px" class="per" value="'.$d['porcentaje'].'" '.$select_disabled.'></td>';
																			echo '<td><input type="text" style="width: 50px;;font-size: 11px" class="imp" value="'.$d['importte'].'" '.$select_disabled.'></td>';
																			echo '<td><button style="width: 30px;;font-size: 11px" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger" '.$select_disabled.'><i  class="icon-trash bigger-120"></i></button></td>';																	
																			echo '</tr>';
																			$contador_pago_partes++;
																		}
																	}
																?>
																</tbody>
															</table>
														</div>
													</td>
												</tr>
											</table>
											</td>
										</td>						
									</tr>
									<?php
								}
								
								if($forma_pago['id'] == 3){
									?>
									<tr style="display:<?=$formapago_id_3?>" id="formapago_por_avanzes_detalle">
										<td width="20%">
										</td>
										<td  width="80%">	
											<table>
											<tr>
												<td>
													<select  name="formapago_por_avanzes" id="formapago_por_avanzes" <?=$select_disabled?>>
														<option value='0'>---Seleccione---</option>
													<?php
														foreach($avancez as $avance){
															$formapago_por_avanzes_selected = "";
															if($showData){
																if($data_contract['termiesp_e_avancez_medida'] == $avance['id']){
																	$formapago_por_avanzes_selected = "selected";
																}
															}
															echo "<option value='".$avance['id']."' ".$formapago_por_avanzes_selected.">".$avance['descripcion']."</option>";
														}
													?>							  
													</select>
												</td>
											</tr>
											</table>
										</td>
									</tr>
									<?php
								}
								
								if($forma_pago['id'] == 4){
									?>
									<tr style="display:<?=$formapago_id_4?>" id="formapago_por_credito_detalle">
										<td width="20%">
										</td>
										<td  width="80%">	
											<table>
											<tr>
												<td>
													<select  name="formapago_por_credito" id="formapago_por_credito" <?=$select_disabled?>>
														<option value='0'>---Seleccione---</option>
													<?php
														foreach($tiposCreditos as $tiposCredito){
															$formapago_por_credito_selected = "";
															if($showData){
																if($data_contract['termiesp_e_credito_dias'] == $tiposCredito['id']){
																	$formapago_por_credito_selected = "selected";
																}
															}
															echo "<option value='".$tiposCredito['id']."' ".$formapago_por_credito_selected.">".$tiposCredito['descripcion']."</option>";
														}
													?>							  
													</select>
												</td>
											</tr>
											</table>
										</td>
									</tr>
									<?php
								}
							}
						?>
						</table>
						
						<h6 class="lighter blue">f) *MODALIDAD DE PAGO</h6>

						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<?php
								foreach($modalidades_pago as $modalidad_pago){
									$modalidadpago_selected = "";
									if($showData){
										if($data_contract['termiesp_f_modalidadpago'] == $modalidad_pago['id']){
											$modalidadpago_selected = "checked='checked'";
											
											if($data_contract['termiesp_f_modalidadpago'] == 12){
												$modalidad_pago_otro = "block";
											}
                                            if($data_contract['termiesp_f_modalidadpago'] == 1){
                                                $modalidad_pago_tras_cuenta_de = "block";
                                            }
										}
									}		
									echo "<tr>";
									echo "<td>";
									echo "<input type='radio' name='modalidadpago' value='".$modalidad_pago['id']."' onclick='show_modalidad_pago_radiobox(".$modalidad_pago['id'].")'  ".$modalidadpago_selected." ".$select_disabled.">".$modalidad_pago['descripcion'];
									echo "</td>";
									echo "</tr>";

                                    if($modalidad_pago['id'] == "1"){
                                        ?>
                                        <tr style="display:<?=$modalidad_pago_tras_cuenta_de?>" id="modalidadpago_transcuenta_desc_tr">
                                            <td width="20%">
                                            </td>
                                            <td  width="80%">
                                                <table>
                                                    <tr>
                                                        <td>
                                                        <?php
                                                        if($showData){
                                                           $modalidadpago_transcuenta_desc = $data_contract['modalidadpago_transcuenta_desc'];
                                                        }
                                                        ?>
                                                        Detalle: <textarea type="text" class="span6" rows="2" cols="50" name="modalidadpago_transcuenta_desc" id="modalidadpago_transcuenta_desc"  <?=$select_disabled?>><?=$modalidadpago_transcuenta_desc?></textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php
                                    }

									if($modalidad_pago['id'] == "12"){
										?>
										<tr style="display:<?=$modalidad_pago_otro?>" id="modalidadpago_otro_tr">
											<td width="20%">
											</td>
											<td  width="80%">	
												<table>
												<tr>
													<td>
													<?php
														if($showData){
															$modalidadpago_otro = $data_contract['modalidadpago_otro'];
														}
													?>
														Otro: <input type="text" name="modalidadpago_otro" id="modalidadpago_otro" value="<?=$modalidadpago_otro?>" <?=$select_disabled?>>
													</td>
												</tr>
												</table>
											</td>
										</tr>
										<?php
									}
								}
							?>
						</table>
						
						<h6 class="lighter blue">g) *GARANTÍAS</h6>

						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<?php
								foreach($garantias as $garantia){
									$garantia_selected = "";
									if($showData){
										if($data_contract['termiesp_g_garantia'] == $garantia['id']){
											$garantia_selected = "checked='checked'";
											
											if($data_contract['termiesp_g_garantia'] == 2){
												$garantia_id_2 = "block";
											}
											if($data_contract['termiesp_g_garantia'] == 3){
												$garantia_id_3 = "block";
											}
											if($data_contract['termiesp_g_garantia'] == 4){
												$garantia_id_4 = "block";
											}
											if($data_contract['termiesp_g_garantia'] == 6){
												$garantia_id_6 = "block";
											}
										}
									}
									echo "<tr>";
									echo "<td>";
									echo "<input type='radio' name='garantia' value='".$garantia['id']."' onclick='show_modalidad_pago_info(".$garantia['id'].")' ".$garantia_selected." ".$select_disabled.">".$garantia['descripcion'];
									echo "</td>";
									echo "</tr>";
									
									if($garantia['id'] == 2){
										?>
										<tr style="display:<?=$garantia_id_2?>" id="modalidadpago_cartafianza_detalle">
											<td width="20%">
											</td>
											<td  width="80%">	
												<table>
												<tr>
													<td>
													<?php
														if($showData){
															$modalidadpago_cartafianza_importe = $data_contract['modalidadpago_cartafianza_importe'];
														}
													?>
														Importe: <input type="text" name="modalidadpago_cartafianza_importe" id="modalidadpago_cartafianza_importe" value="<?=$modalidadpago_cartafianza_importe?>" <?=$select_disabled?> onKeyPress="return soloNumeros(event)">														
													</td>
												</tr>
												<tr>
													<td>
														Tipo Moneda:
														<select style="width:150px;" class="span4" name="modalidadpago_cartafianza_medida" id="modalidadpago_cartafianza_medida" <?=$select_disabled?>>
															<option value='0'>---Seleccione---</option>
														<?php
															foreach($tiposMonedas as $tiposMoneda){
																$tiposMoneda_selected = "";
																if($showData){
																	if($data_contract['modalidadpago_cartafianza_medida'] == $tiposMoneda['id']){
																		$tiposMoneda_selected = "selected";
																	}
																}
																echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
															}
														?>							  
														</select>
													</td>
												</tr>
												</table>
											</td>
										</tr>
										<?php
									}
									
									if($garantia['id'] == 3){
										?>
										<tr style="display:<?=$garantia_id_3?>" id="modalidadpago_adelanto_detalle">
											<td width="20%">
											</td>
											<td  width="80%">	
												<table width="100%">
												<?php
														if($showData){
															$modalidadpago_adelanto_importe = $data_contract['termiesp_g_adelanto_importe'];
															$modalidadpago_adelanto_exception = $data_contract['modalidadpago_adelanto_exception'];
														}
													?>
												<tr>
													<td colspan="2">
														<span class="label label-info">Carta fianza activada</span><br>
													</td>
												</tr>
												<tr>
													<td  colspan="2">
														<label class="control-label" for="modalidadpago_adelanto_importe">Importe:</label> <input type="text" name="modalidadpago_adelanto_importe" id="modalidadpago_adelanto_importe" value="<?=$modalidadpago_adelanto_importe?>" <?=$select_disabled?> onKeyPress="return soloNumeros(event)"><br>
														
													</td>
												</tr>
												<tr>
													<td colspan="2"> 
														<label class="control-label" for="modalidadpago_adelanto_medida">Tipo Moneda:</label>
														<select style="width:150px;" class="span4" name="modalidadpago_adelanto_medida" id="modalidadpago_adelanto_medida" <?=$select_disabled?>>
															<option value='0'>---Seleccione---</option>
														<?php
															foreach($tiposMonedas as $tiposMoneda){
																$tiposMoneda_selected = "";
																if($showData){
																	if($data_contract['modalidadpago_adelanto_medida'] == $tiposMoneda['id']){
																		$tiposMoneda_selected = "selected";
																	}
																}
																echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
															}
														?>							  
														</select>
													</td>
												</tr>
												<tr>
													<td  colspan="2">
														<label class="control-label" for="modalidadpago_adelanto_exception">Excepción:</label><textarea type="text" name="modalidadpago_adelanto_exception" id="modalidadpago_adelanto_exception"  <?=$select_disabled?>><?=$modalidadpago_adelanto_exception?></textarea><br>														
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<label class="control-label" for="modalidadpago_adelanto_adelantofile">Archivo(Opcional):</label><input  type="file" id="modalidadpago_adelanto_adelantofile" name="modalidadpago_adelanto_adelantofile" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value=""/><small>
													</td>
												</tr>
														
														<?php
															if($showData){
														?>
															<tr>
																<td colspan="2">
																	<div class="control-group">
																		<?php if(!empty($data_contract['modalidadpago_adelanto_adelantofile'])){ ?>
																			<a href="#" onclick="ver_archivo('<?=$data_contract['modalidadpago_adelanto_adelantofile']?>')">
																				<img src="../assets/images/<?=getIconToDiplay($data_contract['modalidadpago_adelanto_adelantofile'])?>" width="40" height="40" title="Ver File">
																			</a>
																			<p style="font-size: 10px"><?=$data_contract['modalidadpago_adelanto_adelantofile']?></p>
																			<?php if($estado == 0){ ?>
																				<a href="#" onclick="delete_document('modalidadpago_adelanto_adelantofile', '<?=$data_contract['modalidadpago_adelanto_adelantofile']?>')">Eliminar adjunto</a>
																			<?php } ?>
																		<?php } ?>
																	</div>
																</td>
															</tr>
														<?php
														}
														?>
												</table>
											</td>
										</tr>
										<?php
									}
									
									if($garantia['id'] == 4){
										?>
										<tr style="display:<?=$garantia_id_4?>" id="modalidadpago_fcumplimiento_detalle">
											<td width="20%">
											</td>
											<td  width="80%">	
												<table>
												<tr>
													<td>
													<?php
														if($showData){
															$modalidadpago_fcumplimiento_importe = $data_contract['termiesp_g_fcumplimiento_importe'];
														}
													?>
														Importe: <input type="text" name="modalidadpago_fcumplimiento_importe" id="modalidadpago_fcumplimiento_importe" value="<?=$modalidadpago_fcumplimiento_importe?>" <?=$select_disabled?> onKeyPress="return soloNumeros(event)">
													</td>
												</tr>
												<tr>
													<td>
														Tipo Moneda:
														<select style="width:150px;" class="span4" name="modalidadpago_fcumplimiento_medida" id="modalidadpago_fcumplimiento_medida" <?=$select_disabled?>>
															<option value='0'>---Seleccione---</option>
														<?php
															foreach($tiposMonedas as $tiposMoneda){
																$tiposMoneda_selected = "";
																if($showData){
																	if($data_contract['modalidadpago_fcumplimiento_medida'] == $tiposMoneda['id']){
																		$tiposMoneda_selected = "selected";
																	}
																}
																echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
															}
														?>							  
														</select>
													</td>
												</tr>
												</table>
											</td>
										</tr>
										<?php
									}
									
									if($garantia['id'] == 6){
										?>
										<tr style="display:<?=$garantia_id_6?>" id="modalidadpago_fgarantia_detalle">
											<td width="20%">
											</td>
											<td  width="80%">	
												<table>
												<tr>
													<td>
													<?php
														if($showData){
															$modalidadpago_fgarantia_importe = $data_contract['termiesp_g_fondogarantia_importe'];
														}
													?>												
														Importe:
														<input type="text" name="modalidadpago_fgarantia_importe" id="modalidadpago_fgarantia_importe" value="<?=$modalidadpago_fgarantia_importe?>" <?=$select_disabled?> onKeyPress="return soloNumeros(event)">
													</td>
												</tr>
												<tr>
													<td>
														Tipo Moneda:
														<select style="width:150px;" class="span4" name="modalidadpago_fgarantia_medida" id="modalidadpago_fgarantia_medida" <?=$select_disabled?>>
															<option value='0'>---Seleccione---</option>
														<?php
															foreach($tiposMonedas as $tiposMoneda){
																$tiposMoneda_selected = "";
																if($showData){
																	if($data_contract['modalidadpago_fgarantia_medida'] == $tiposMoneda['id']){
																		$tiposMoneda_selected = "selected";
																	}
																}
																echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
															}
														?>							  
														</select>
													</td>
												</tr>
												</table>
											</td>
										</tr>
										<?php
									}
								}
							?>
						</table>
						
						<h6 class="lighter blue">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp g1) RUBRO MOBILIARIAS </h6>
						
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  colspan="2"><label class="control-label" for="observaciones_amplicaciones"></label>
									<div class="control-group">
									<?php
										if($showData){
											$monto_mobiliario = $data_contract['monto_mobiliario'];
										}
									?>							
									Monto mobiliario:<input type="text" name="monto_mobiliario" id="monto_mobiliario"  value="<?=$monto_mobiliario?>" <?=$select_disabled?> onKeyPress="return soloNumeros(event)"></input>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label class="control-label" for="monto_mobiliario_medida">Tipo Moneda:</label>
									<div class="control-group">
									<select style="width:150px;" class="span4 chosen-select" name="monto_mobiliario_medida" id="monto_mobiliario_medida" <?=$select_disabled?>>
										<option value='0'>---Seleccione---</option>
											<?php
												foreach($tiposMonedas as $tiposMoneda){
													$tiposMoneda_selected = "";
													if($showData){
														if($data_contract['monto_mobiliario_medida'] == $tiposMoneda['id']){
															$tiposMoneda_selected = "selected";
														}
													}
													echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
												}
											?>							  
									</select>
									</div>
								</td>
							</tr>
							<?php if(!$noUserFlow){ ?>
							<tr>
								<td  colspan="2"><label class="control-label" for="rubro_inmuebles_partidaregistral"></label>
									<?php if(empty($select_disabled)){ ?>
									<div class="control-group">
									<label class="control-label" for="">Agregar archivo:</label>
										<a href="#/" onClick="addCampo_inmuebles()">
											<img src="../assets/images/plusAudio.png" border="0"/>
										</a>
										<select class="span4 chosen-select" name="inmueble_tipo" id="inmueble_tipo">
										  <option value="1">Tarjeta propiedad</option> 
										  <option value="2">Certificado de gravámenes</option>									  
										</select>
									</div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id="adjuntos_inmuebles"></div>
								</td>
							</tr>
							<?php } ?>
							<?php
							if($showData){
								$files_moviliarios = getListaArchivosRubrosMoviliarios($_GET['id']);
							?>
								<tr>
									<td colspan="2">
										<table>
											<?php foreach($files_moviliarios as $file){
												$file_tipo_name_moviliario = "";
												if($file['tipo'] == 1){
													$file_tipo_name_moviliario = "Tarjeta de propiedad";
												}else if($file['tipo'] == 2){
													$file_tipo_name_moviliario = "Certificado de gravámenes";
												}
											?>
												<tr>
											<div class="control-group">
												<a href="#" onclick="ver_archivo('<?=$file['url']?>')">
													<img src="../assets/images/<?=getIconToDiplay($file['url'])?>" width="40" height="40" title="Ver <?=$file_tipo_name_moviliario?>">
												</a>
												<p style="font-size: 10px"><?=$file['url']?></p>
												<?php if($estado == 0){ ?>
													<a href="#" onclick="delete_document_detalle('<?=$file['id']?>', '<?=$file['url']?>', 1)">Eliminar adjunto</a>
												<?php } ?>
											</div>
												</tr>
											<?php } ?>
										</table>
									</td>
								</tr>
							<?php
							}
							?>
						</table>
						
						
						<h6 class="lighter blue">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  g2) RUBRO INMUEBLES </h6>
						
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<?php if(!$noUserFlow){ ?>
							<tr>
								<td  colspan="2"><label class="control-label" for="rubro_inmuebles_partidaregistral_sct2"></label>
									<?php if(empty($select_disabled)){ ?>
									<div class="control-group">
									<label class="control-label" for="">Partida registral:</label>
										<a href="#/" onClick="addFilesPRsct2()">
											<img src="../assets/images/plusAudio.png" border="0"/>
										</a>
									</div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id="adjuntos_sct2"></div>
								</td>
							</tr>
						<?php } ?>
						<?php
							if($showData){
								$files_partidaregistral = getListaArchivosPartidaRegistral($_GET['id']);
							?>
								<tr>
									<td colspan="2">
                                        <table>
											<?php foreach($files_partidaregistral as $file){												
											?>
                                                    <tr>
                                                <div class="control-group">
												<a href="#" onclick="ver_archivo('<?=$file['url']?>')">
													<img src="../assets/images/<?=getIconToDiplay($file['url'])?>" width="40" height="40" title="Ver">
												</a>
												<p style="font-size: 10px"><?=$file['url']?></p>
                                                <?php if($estado == 0){ ?>
                                                    <a href="#" onclick="delete_document_detalle('<?=$file['id']?>', '<?=$file['url']?>', 2)">Eliminar adjunto</a>
                                                <?php } ?>
                                                </div>
                                                    </tr>
											<?php } ?>
                                        </table>
									</td>
								</tr>
							<?php
							}
							?>
						</table>
						
						<h6 class="lighter blue">h) LUGAR DE SERVICIO</h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  colspan="2"><label class="control-label" for="lugar_entrega"></label>
									<div class="control-group">
									<?php
										if($showData){
											$lugar_entrega = $data_contract['termiesp_h_lugarentrega'];
										}
									?>
									<textarea class="span6" name="lugar_pago" id="lugar_entrega" rows="2" cols="50" <?=$select_disabled?>><?=$lugar_entrega?></textarea>
									</div>
								</td>								
							</tr>
							<tr>
								<td colspan="2"><label class="control-label" for="lugar_entrega"></label>
									<div class="control-group">
									<?php
										if($showData){
											$lugar_entrega_personal_tercero = $data_contract['lugar_entrega_personal_tercero']==1?"checked='checked'":"";
											
											if(!empty($lugar_entrega_personal_tercero)){
												$lugar_entrega_personal_tercero_numero = $data_contract['lugar_entrega_personal_tercero_numero'];
												$lugar_entrega_personal_tercero_dias = $data_contract['lugar_entrega_personal_tercero_dias'];
												$lugar_entrega_personal_tercero_equipo = $data_contract['lugar_entrega_personal_tercero_equipo'];
												$lugar_entrega_personal_tercero_tr = "block";
											}
											
											
										}
									?>
									<input type="checkbox" name="lugar_entrega_personal_tercero" id="lugar_entrega_personal_tercero" onclick="handle_personal_tercero()" <?=$lugar_entrega_personal_tercero?> <?=$select_disabled?>> Personal tercero
									</div>
								</td>
							</tr>
							
							<tr style="display:<?=$lugar_entrega_personal_tercero_tr?>" id="lugar_entrega_personal_tercero_tr">
								<td  width="50%">									
									
									<label class="control-label" for="lugar_entrega_personal_tercero_numero">Número:</label> <input type="text" name="lugar_entrega_personal_tercero_numero" id="lugar_entrega_personal_tercero_numero" value="<?=$lugar_entrega_personal_tercero_numero?>" <?=$select_disabled?> onKeyPress="return soloNumerosEnterosPositivos(event)">
									<label class="control-label" for="lugar_entrega_personal_tercero_numero">Días:</label> <input type="text" name="lugar_entrega_personal_tercero_dias" id="lugar_entrega_personal_tercero_dias" value="<?=$lugar_entrega_personal_tercero_dias?>" <?=$select_disabled?> onKeyPress="return soloNumerosEnterosPositivos(event)">
								    <label class="control-label" for="lugar_entrega_personal_tercero_numero">Equipo:</label> <textarea type="text" name="lugar_entrega_personal_tercero_equipo" id="lugar_entrega_personal_tercero_equipo" <?=$select_disabled?>><?=$lugar_entrega_personal_tercero_equipo?></textarea>									
								</td>
								<td width="50%">                            
								</td>
							</tr>
							
						</table>
						
						<h6 class="lighter blue">i) OBSERVACIONES O AMPLIACIONES </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  colspan="2"><label class="control-label" for="observaciones_amplicaciones"></label>
									<div class="control-group">
									<?php
										if($showData){
											$observaciones_amplicaciones = $data_contract['termiesp_i_observacionesamplicaciones'];
										}
									?>							
									<textarea class="span6" name="observaciones_amplicaciones" id="observaciones_amplicaciones" rows="2" cols="50" <?=$select_disabled?>><?=$observaciones_amplicaciones?></textarea>
									</div>
								</td>
							</tr>
							<tr>
								<td>
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<?php if(!$noUserFlow){ ?>
											<tr>
												<td  colspan="2"><label class="control-label" for=""></label>
													<?php if(empty($select_disabled)){ ?>
													<div class="control-group">
													<label class="control-label" for="">Adjuntos:</label>
														<a href="#/" onClick="addFilesOA1()">
															<img src="../assets/images/plusAudio.png" border="0"/>
														</a>
													</div>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td>
													<div id="adjuntos_OA1"></div>
												</td>
											</tr>
										<?php } ?>
										<?php
											if($showData){
												$files_oa = getListaArchivosObservacionesAmpliaciones($_GET['id']);
											?>
												<tr>
                                                    <table>
													<td colspan="2">

															<?php foreach($files_oa as $file){												
															?>
                                                                    <tr>
                                                        <div class="control-group">
																<a href="#" onclick="ver_archivo('<?=$file['url']?>')">
																	<img src="../assets/images/<?=getIconToDiplay($file['url'])?>" width="40" height="40" title="Ver">
																</a>
																<p style="font-size: 10px"><?=$file['url']?></p>
                                                                <?php if($estado == 0){ ?>
                                                                    <a href="#" onclick="delete_document_detalle('<?=$file['id']?>', '<?=$file['url']?>', 3)">Eliminar adjunto</a>
                                                                <?php } ?>
                                                        </div>
                                        </tr>
                                            </table>
															<?php } ?>
													</td>
												</tr>
											<?php
											}
											?>
										</table>
								</td>
							</tr>
						</table>
						
						<h6 class="lighter blue">j) METAS A CUMPLIR (ENTREGABLES) </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  colspan="2"><label class="control-label" for="observaciones_amplicaciones"></label>
									<div class="control-group">
									<?php
										if($showData){
											$metas_cumplir_comentario = $data_contract['metas_cumplir_comentario'];
										}
									?>							
									<textarea class="span6" name="metas_cumplir_comentario" id="metas_cumplir_comentario" rows="2" cols="50" <?=$select_disabled?>><?=$metas_cumplir_comentario?></textarea><br>
									</div>
								</td>
							</tr>
							<?php if(!$noUserFlow){ ?>
							<tr>
								<td colspan="2"><label class="control-label" for="observaciones_amplicaciones"></label>
									<div class="control-group">										
									Entregables:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input  type="file" id="metas_cumplir_entregables" name="metas_cumplir_entregables" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?>/>
									</div>
								</td>
							</tr>
							<?php } ?>
							<?php
							if($showData){
							?>
							<tr>
								<td colspan="2">
									<div class="control-group">
										<?php if(!empty($data_contract['metas_cumplir_entregables'])){ ?>
											<a href="#" onclick="ver_archivo('<?=$data_contract['metas_cumplir_entregables']?>')">
												<img src="../assets/images/<?=getIconToDiplay($data_contract['metas_cumplir_entregables'])?>" width="40" height="40" title="Ver entregables">
											</a>
											<p style="font-size: 10px"><?=$data_contract['metas_cumplir_entregables']?></p>
											<?php if($estado == 0){ ?>
												<a href="#" onclick="delete_document('metas_cumplir_entregables', '<?=$data_contract['metas_cumplir_entregables']?>')">Eliminar adjunto</a>
											<?php } ?>
										<?php } ?>
									</div>
								</td>
							</tr>							
							<?php
							}
							?>
						</table>
						
						<h6 class="lighter blue">k) RUBRO DE PENALIDADES </h6>
						
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  colspan="2">
								
								<table>
									<?php if(!$noUserFlow){ ?> 
										<tr>
										<?php if(empty($select_disabled)){ ?>
										<td>
											<img onclick="add_row_formapago_penalidades()" src="../assets/images/plusAudio.png" width="17" height="17">
										</td>	
										<?php } ?>
										</tr>
									<?php } ?>
										<tr>
													<td>
														Tipo Moneda:
														<select style="width:150px;" class="span4 chosen-select" name="penalidades_medida" id="penalidades_medida" <?=$select_disabled?>>
															<option value='0'>---Seleccione---</option>
														<?php
															foreach($tiposMonedas as $tiposMoneda){
																$tiposMoneda_selected = "";
																if($showData){
																	if($data_contract['penalidades_medida'] == $tiposMoneda['id']){
																		$tiposMoneda_selected = "selected";
																	}
																}
																echo "<option value='".$tiposMoneda['id']."' ".$tiposMoneda_selected.">".$tiposMoneda['descripcion']."</option>";
															}
														?>							  
														</select>
													</td>
										</tr>	
										<tr>
										<td>
											<div id="table_report_wrapper2" class="dataTables_wrapper" role="grid">
													<table id="table_report2" class="table table-striped table-bordered table-hover dataTable"
																   aria-describedby="table_report_info">
														<thead>
														<tr role="row">
															<th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
																		 colspan="1"
																		 style="width: 200px;;font-size: 11px">Supuesto
															</th>
															<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
																		colspan="1"
																		style="width: 200px;;font-size: 11px">Sancion económica
															</th>                          
															<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 30px;;font-size: 11px">
																		Eliminar
															</th>
														</tr>
														</thead>
														<tbody role="alert" aria-live="polite" aria-relevant="all">
															<?php
																if($showData){
																	$detalles_penalidades = getPenalidades($_GET['id']);
																	$contador_pago_partes = 1;
																	foreach($detalles_penalidades as $d){
																		echo '<tr>';																		
																		echo '<td><input type="text" style="width: 200px;;font-size: 11px" class="supuesto" value="'.$d['supuesto'].'" '.$select_disabled.'></td>';
																		echo '<td><input type="text" style="width: 200px;;font-size: 11px" class="sancion" value="'.$d['sancion_economica'].'" '.$select_disabled.'></td>';
																		echo '<td><button style="width: 30px;;font-size: 11px" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger" '.$select_disabled.'><i  class="icon-trash bigger-120"></i></button></td>';																	
																		echo '</tr>';
																		$contador_pago_partes++;
																	}
																}
															?>
														</tbody>
													</table>
											</div>
										</td>
										</tr>
								</table>
								</td>
							</tr>
						</table>	
						
						<h6 class="lighter blue">l) CONTRATO PROPUESTO POR EL PROVEEDOR </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						
						<?php if(!$noUserFlow){ ?>
							<tr>
								<td  colspan="2"><label class="control-label" for="contrato_propuesto_proveedor"></label>
									<div class="control-group">
									Contrato:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input  type="file" id="contrato_propuesto_proveedor" name="contrato_propuesto_proveedor" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" value="" <?=$select_disabled?> />
									</div>
								</td>
							</tr>
						<?php } ?>
						<?php
							if($showData){
						?>
							<tr>
								<td colspan="2">
									<div class="control-group">
										<?php if(!empty($data_contract['contrato_propuesto_proveedor'])){ ?>
											<a href="#" onclick="ver_archivo('<?=$data_contract['contrato_propuesto_proveedor']?>')">
												<img src="../assets/images/<?=getIconToDiplay($data_contract['contrato_propuesto_proveedor'])?>" width="40" height="40" title="Ver contrato">
											</a>
											<p style="font-size: 10px"><?=$data_contract['contrato_propuesto_proveedor']?></p>
											<?php if($estado == 0){ ?>
												<a href="#" onclick="delete_document('contrato_propuesto_proveedor', '<?=$data_contract['contrato_propuesto_proveedor']?>')">Eliminar adjunto</a>
											<?php } ?>
										<?php } ?>
									</div>
								</td>
							</tr>
						<?php
						}
						?>
						</table>
						
					</div>
					<div class="row-fluid">
						<h5 class="header smaller lighter blue">III. REQUISITOS ESPECIALES DEL CONTRATO</h5>
						
						<h6 class="lighter blue">RUTA </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td  width="50%"><label class="control-label" for="ruta"></label>
									<div class="control-group">
									<?php
										if($showData){
											$ruta = $data_contract['reqesp_ruta'];
										}
									?>							
									<textarea class="span6" name="ruta" id="ruta" rows="2" cols="50" <?=$select_disabled?>><?=$ruta?></textarea>
									</div>
								</td>
								<td width="50%">                            
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="2">
									 - Legal trabajará con los archivos adjuntos al momento de la recepción de la SEC.<br>
									 - Todo documento digitalizado según sea el caso, debe ser previamente firmado por el ente que lo emitió.<br>
									 - El nombre de la carpeta que contenga los requisitos deberá ser el # de la SEC.<br>
								</td>
							</tr>
						</table>

						
					</div>
					<div class="row-fluid">
						<h5 class="header smaller lighter blue">IV. AUTORIZACIONES </h5>
						
						<h6 class="lighter blue">CREADOR SEC </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								   <span class="label label-success">
										Nombre: <?=$autorizac_a_nombres?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Cargo: <?=$autorizac_a_cargo?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Fecha: <?=$autorizac_a_fecha?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								  <span class="label label-success">
										Firma
									</span>
								</td>
							</tr>                  
						</table>
						
				<?php if( isset($_GET['id']) || in_array($estado,array(1)) ){ ?>
						<h6 class="lighter blue">JEFATURA ÁREA USUARIA </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								   <span class="label label-success">
										Nombre: 
										<?php
											if($showData){
												echo $data_contract['autorizac_b_nombres'];
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Cargo:
										<?php
											if($showData){
												echo $data_contract['autorizac_b_cargo'];
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Fecha:
										<?php
											if($showData){
												echo $data_contract['autorizac_b_fecha'];
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Firma:
										<?php
											if($showData){
												echo "";
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>                  
						</table>
				<?php } ?>
				
				<?php if( $TIPO_FLUJO_CONTRATO == $TIPO_USUARIO_COMPRADOR && ( isset($_GET['id']) || in_array($estado,array(1)) ) ){ ?>
						<h6 class="lighter blue">JEFATURA LOGÍSTICA </h6>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								   <span class="label label-success">
										Nombre: 
										<?php
											if($showData){
												echo $data_contract['autorizac_c_nombres'];
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Cargo:
										<?php
											if($showData){
												echo $data_contract['autorizac_c_cargo'];
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Fecha:
										<?php
											if($showData){
												echo $data_contract['autorizac_c_fecha'];
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>
							<tr>
								 <td>
								   <span class="label label-success">
										Firma:
										<?php
											if($showData){
												echo "";
											}else{
												echo "";
											}
										?>
									</span>
								</td>
							</tr>                  
						</table>
				<?php } ?>
					</div>	
				<?php if($UsuarioFlow){ ?>
					<div class="modal-footer">
						<?php if( !isset($_GET['id']) ){ ?>
							<input type="submit" class="btn btn-primary" value="<?=$btn_submit_name?>" style= "float: left;position: relative;left: 50%;">
						<?php }else if($estado == 0){?>
							<input type="submit" class="btn btn-primary" value="<?=$btn_submit_name?>" style= "float: left;position: relative;left: 50%;">					
							<button type="button" class="btn btn-success" style= "float: left;position: relative;left: 50%;" onclick="send_1(<?=$_GET['id']?>)">
							  <i class="icon-plane icon-white"></i> Enviar
							</button>
						<?php }?>
					</div>
				<?php }?>
				</form>
				<!--/#page-content-->
			</div>
			<!--/#main-content-->
			<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>

		</div>

		</div>
		
		<?php if( $estado >= 0.3 ){ ?>
		
		<div class="tab-pane" id="2">
			
			<h5 class="header smaller lighter blue">DATOS DEL PROVEEDOR</h5>
			
		  <?php if( $data_contract['proveedor_tipo'] == 1 ){ ?>
			<div >
				<?=display_iframe_or_link_to_download($data_contract['jur_file_ficharuc'], $data_contract['datosgenerales_codigo'], "jur_file_ficharuc")?>				
			</div>
			
			<div >
				<?=display_iframe_or_link_to_download($data_contract['jur_file_represetante'], $data_contract['datosgenerales_codigo'], "jur_file_represetante")?>
			</div>			
			
			<div >
				<?=display_iframe_or_link_to_download($data_contract['jur_file_vigenciapoder'], $data_contract['datosgenerales_codigo'], "jur_file_vigenciapoder")?>
			</div>
			
		  <?php }else if( $data_contract['proveedor_tipo'] == 2 ){ ?>
			<div >
				<?=display_iframe_or_link_to_download($data_contract['nat_file_ficharuc'], $data_contract['datosgenerales_codigo'], "nat_file_ficharuc")?>
			</div>			
			
			<div >
				<?=display_iframe_or_link_to_download($data_contract['nat_file_represetante'], $data_contract['datosgenerales_codigo'], "nat_file_represetante")?>
			</div>			
		  <?php } ?>	
		
		<?php if(!empty($data_contract['contraprestacion_file'])){ ?>
			<h5 class="header smaller lighter blue">CONTRAPRESTACIÓN </h5>
			<?=display_iframe_or_link_to_download($data_contract['contraprestacion_file'], $data_contract['datosgenerales_codigo'], "contraprestacion")?>
		<?php } ?>
		
		<?php if(!empty($data_contract['modalidadpago_adelanto_adelantofile'])){ ?>
			<h5 class="header smaller lighter blue">GARANTIAS</h5>
			<?=display_iframe_or_link_to_download($data_contract['modalidadpago_adelanto_adelantofile'], $data_contract['datosgenerales_codigo'], "modalidadpago_adelanto_adelantofile")?>
		<?php } ?>	
		
		<?php if(!empty($files_moviliarios)){ ?>
			<h5 class="header smaller lighter blue">GARANTIAS</h5>
			<h6 class="lighter blue">RUBRO MOBILIARIAS</h6>
			<?php
			foreach($files_moviliarios as $archivo){
				display_iframe_or_link_to_download($archivo['url'], $data_contract['datosgenerales_codigo'] , "garantias");			
			}
			?>
		<?php } ?>	
		
		<?php if(!empty($files_partidaregistral)){ ?>
			<h5 class="header smaller lighter blue">GARANTIAS</h5>
			<h6 class="lighter blue">RUBRO INMUEBLES</h6>
			<?php
			foreach($files_partidaregistral as $archivo){
				display_iframe_or_link_to_download($archivo['url'], $data_contract['datosgenerales_codigo'] , "inmuebles");			
			}
			?>
		<?php } ?>	
		
		<?php if(!empty($files_oa)){ ?>
			<h5 class="header smaller lighter blue">OBSERVACIONES O AMPLIACIONES</h5>
			<?php
			foreach($files_oa as $archivo){
				display_iframe_or_link_to_download($archivo['url'], $data_contract['datosgenerales_codigo'] , "observaciones_amplicaciones");			
			}
			?>
		<?php } ?>
		
		<?php if(!empty($data_contract['metas_cumplir_entregables'])){ ?>
			<h5 class="header smaller lighter blue">METAS A CUMPLIR (ENTREGABLES)</h5>
			<?=display_iframe_or_link_to_download($data_contract['metas_cumplir_entregables'], $data_contract['datosgenerales_codigo'], "metas_cumplir_entregables")?>
		<?php } ?>
		
		<?php if(!empty($data_contract['contrato_propuesto_proveedor'])){ ?>
			<h5 class="header smaller lighter blue">CONTRATO PROPUESTO POR EL PROVEEDOR</h5>
			<?=display_iframe_or_link_to_download($data_contract['contrato_propuesto_proveedor'], $data_contract['datosgenerales_codigo'], "contrato_propuesto_proveedor")?>
		<?php } ?>
		
		</div>
		
		
		<div class="tab-pane" id="3">
		  <div >
			<iframe id="iframepdf" height="450" width="850" style="border:1px solid #666CCC"			        
					src="../files/contratos/<?=$data_contract['datosgenerales_codigo']?>/<?=$data_contract['datosgenerales_codigo']?>.pdf#zoom=50">
			</iframe>
		  </div>
		</div>
		
		<?php } ?>
		
		<div class="tab-pane" id="4">
		  <div >
		  <?php 

			if( $data_contract['anulado'] == 1){
				include("anulado_view.php");
			}else if( $estado == 0.3 && $noUserFlow && isset($_GET['role']) && $_GET['role'] == "jefe"){
				include("create_mod2.php");
			}else if( $estado == 0.6 && $noUserFlow && isset($_GET['role']) && $_GET['role'] == "logistica"){
				include("create_mod2.php");
			}else if( $estado == 0.8 && $noUserFlow && isset($_GET['role']) && $_GET['role'] == "legal"){
				include("create_mod2.php");
			}else if( $estado == 1 && $noUserFlow && isset($_GET['role']) && $_GET['role'] == "legal"){
				include("create_mod2.php");
			}else if( $estado == 2 && isset($_GET['role']) && $_GET['role'] == "jefe" && $data_contract['flag_has_last_approved_usuario'] != 1 ){
				include("create_mod2.php");
			}else if( $estado == 2 && isset($_GET['role']) && $_GET['role'] == "logistica" && $data_contract['flag_has_last_approved_logistica'] != 1 ){
				include("create_mod2.php");
			}else if( $estado == 3 && $noUserFlow && isset($_GET['role']) && $_GET['role'] == "legal"){
				include("create_mod2.php");
			}else if($estado == 4 || $estado == 5){
                $permissions_contracts_ = getPermissionsUsuarioContract( $_SESSION['username'] );
                $isLegalArea = $permissions_contracts_[0]['idarea'] == 1 ? true : false;

                if($isLegalArea){
                    $legal_advance_options = true;
                    include("create_mod2.php");
                }
            }
			
			if( !empty($movimientos) ){				
				foreach($movimientos as $movimiento){					
					include("create_mod1.php");		
				}
			}
		  ?>
		  </div>
		</div>
		
		<?php if( $estado == 4 || $estado == 5){ ?>
		<div class="tab-pane" id="5">
		  <div >
			<?php
			$archivos = getArchivosContractByMovimiento($movimientos[1]['idmovimiento']);
            if(empty($archivos)){
                $archivos = getArchivosContractByMovimiento($movimientos[0]['idmovimiento']);
            }
            if($estado == 5 && empty($archivos)){
                $archivos = getArchivosContractByMovimiento($movimientos[2]['idmovimiento']);
            }

			foreach($archivos as $archivo){
				echo '<h5 class="header smaller lighter blue">Documento</h5>';
				display_iframe_or_link_to_download($archivo['url'], $data_contract['datosgenerales_codigo'] , "documentos_finales");				
			}
			?>
		  </div>
		</div>
		<?php } ?>
</div>

<!--/.fluid-container#main-container-->

</div>

<script src="../assets/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="../assets/css/jquery.autocomplete.css" />

<script>    	
	<?php if($noUserFlow){ ?>
		$( '#listar_contract_approve' ).addClass( "active" );
		$( '.activePlantilla1' ).html( "<a href='../contract_approve/index.php'>Contratos</a>");
	<?php }else{?>
		$( '#listar_contract' ).addClass( "active" );
		$( '.activePlantilla1' ).html( "<a href='index.php'>Mis contratos</a> > <a href='create.php'>Nuevo</a>");
	<?php }?>
	
		var oTable1 = $('#table_report').dataTable({
				"bPaginate": false,
				"bFilter"  : false,
				"bInfo"    : false,
				"bSort": false,
				"aoColumns": [
					{"sClass": "row_css"},
					{"sClass": "row_css"},
					{"sClass": "row_css"},
					{ "bSortable": false }
				] 
			});
			
		var oTable2 = $('#table_report2').dataTable({
				"bPaginate": false,
				"bFilter"  : false,
				"bInfo"    : false,
				"bSort": false,
				"aoColumns": [
					{"sClass": "row_css"},
					{"sClass": "row_css"},
					{ "bSortable": false }
				] 
			});	
		
		function load_jefatura_area_usuario(){
			
			var value = $("#area_usuario").val();
			
			var parametros = {
						"cod" : 1,
						"a" : value

			};

			$.ajax({
						data:  parametros,
						url:   '../phps/dcontract_ajax.php',
						type:  'post',
						dataType: "html",
						beforeSend: function (repuesta) {                       
							openModal();
						},
						success: function(respuesta){

							respuesta = $.parseJSON( respuesta );

							if(respuesta.estado == "1"){							
								
								$("#area_usuario_jefatura").empty();
								respuesta.data.forEach(function(currentValue, index, arr){
									$('#area_usuario_jefatura').append('<option value="'+currentValue.id+'">'+currentValue.usuario+'</option>');
								});
								
								
								closeModal();

								//$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');                            

							}else{
								$("#area_usuario_jefatura").empty();
								closeModal();
								//$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
								alert(respuesta.error);
								
							}
						},
						error: function(respuesta){
							closeModal();alert("Error al conectar con el servidor");
						},
						failure: function(respuesta){
							closeModal();alert("Error al conectar con el servidor");
						}
					});
		}
		
		function load_ruc_proveedor(){
			
			var value = $("#proveedor").val();
			
			var parametros = {
						"cod" : 2,
						"a" : value

			};

			$.ajax({
						data:  parametros,
						url:   '../phps/dcontract_ajax.php',
						type:  'post',
						dataType: "html",
						beforeSend: function (repuesta) {                       
							openModal();
						},
						success: function(respuesta){

							respuesta = $.parseJSON( respuesta );

							if(respuesta.estado == "1"){							
								;
								$("#proveedor_ruc").val(respuesta.data[0].ruc);
								closeModal();

								//$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');                            

							}else{

								closeModal();
								//$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
								alert(respuesta.error);
								
							}
						},
						error: function(respuesta){
							closeModal();alert("Error al conectar con el servidor");
						},
						failure: function(respuesta){
							closeModal();alert("Error al conectar con el servidor");
						}
					});
		}
		
		function add_row_formapago_enpartes(){
			
			var oTable1 = $("#table_report").dataTable();
			
			    oTable1.fnAddData(
                    ['Pago '+(oTable1.fnGetData().length+1),
                     '<input type="text" style="width: 100px;;font-size: 11px" class="per" id="'+("fpenp"+oTable1.fnGetData().length)+'" onKeyPress="return soloNumeros(event)">',
                     '<input type="text" style="width: 100px;;font-size: 11px" class="imp" id="'+("fpenv"+oTable1.fnGetData().length)+'" onKeyPress="return soloNumeros(event)">',
                     '<button style="width: 30px;;font-size: 11px" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"><i  class="icon-trash bigger-120"></i></button>'
					 ]);
			
		}
		
		function add_row_formapago_penalidades(){
			
			var oTable2 = $("#table_report2").dataTable();
			
			    oTable2.fnAddData(
                    ['<input type="text" style="width: 250px;;font-size: 11px" class="supuesto">',
                     '<input type="text" style="width: 250px;;font-size: 11px" class="sancion"  id="'+("pen22"+oTable2.fnGetData().length)+'" maxlength="100">',
                     '<button style="width: 30px;;font-size: 11px" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"><i  class="icon-trash bigger-120"></i></button>'
					 ]);
			
		}
		
		$('#table_report tbody').on( 'click', 'button', function () {

            if(confirm("¿Seguro que desea eliminar el item?")){
				
				var oTable1 = $("#table_report").dataTable();
                var target_row = $(this).closest("tr").get(0);
                var aPos = oTable1.fnGetPosition(target_row);
                oTable1.fnDeleteRow( aPos );

                var rows = oTable1.fnGetNodes();
                var aux = 0;

                //vuelvo a setear la fila ITEM
                for(var i=0;i<rows.length;i++){
                    aux = i + 1;
                    $(rows[i]).find("td:eq(0)").html("Pago" + aux);
                }
            }
        } );
		
		$('#table_report2 tbody').on( 'click', 'button', function () {

            if(confirm("¿Seguro que desea eliminar el item?")){
				
				var oTable2 = $("#table_report2").dataTable();
                var target_row = $(this).closest("tr").get(0);
                var aPos = oTable2.fnGetPosition(target_row);
                oTable2.fnDeleteRow( aPos );

                var rows = oTable2.fnGetNodes();
                var aux = 0;
               
            }
        } );
		
		function show_formapago_info(id){
			
			$("#formapago_en_partes_detalles").css("display", "none");
			$("#formapago_por_avanzes_detalle").css("display", "none");
			$("#formapago_por_credito_detalle").css("display", "none");
			
			if(id==1){
				$("#formapago_en_partes_detalles").css("display", "block");
			}else if(id == 3){
				$("#formapago_por_avanzes_detalle").css("display", "block");
			}else if(id==4){
				$("#formapago_por_credito_detalle").css("display", "block");
			}			
		}
		
		function show_tipo_proveedor_data(modo){
			if(modo == 1){
				$("#proveedor_juridica_tr").css("display", "table-row");
				$("#proveedor_natural_tr").css("display", "none");
			}else if(modo == 2){
				$("#proveedor_juridica_tr").css("display", "none");
				$("#proveedor_natural_tr").css("display", "table-row");
			}
		}
		
		function show_modalidad_pago_info(id){
			
			$("#modalidadpago_cartafianza_detalle").css("display", "none");
			$("#modalidadpago_adelanto_detalle").css("display", "none");
			$("#modalidadpago_fcumplimiento_detalle").css("display", "none");
			$("#modalidadpago_fgarantia_detalle").css("display", "none");
			
			if(id==2){
				$("#modalidadpago_cartafianza_detalle").css("display", "block");
			}else if(id==3){
				$("#modalidadpago_adelanto_detalle").css("display", "block");
			}else if(id == 4){
				$("#modalidadpago_fcumplimiento_detalle").css("display", "block");
			}else if(id==6){
				$("#modalidadpago_fgarantia_detalle").css("display", "block");
			}
		}

		function show_modalidad_pago_radiobox(id){
			
			$("#modalidadpago_otro_tr").css("display", "none");
            $("#modalidadpago_transcuenta_desc_tr").css("display", "none");
			if(id==12){
				$("#modalidadpago_otro_tr").css("display", "table-row");
			}
            if(id==1){
                $("#modalidadpago_transcuenta_desc_tr").css("display", "table-row");
            }
		}
		
		$("#plazo_vigencia_inicio").change(function(){
			calculate_fecha_termino();
		});
		
		function calculate_fecha_termino(){
			/*
			var fecha_inicio = $("#plazo_vigencia_inicio").val();
			var nro_dias     = $("#plazo_vigencia_dias").val();
			
			var TuFecha = new Date('30/09/2018');
  
		    //dias a sumar
		    var dias = parseInt(nro_dias);
		  
		    //nueva fecha sumada
		    TuFecha.setDate(TuFecha.getDate() + dias);
		    //formato de salida para la fecha
		    var resultado = TuFecha.getDate() + '/' +
			(TuFecha.getMonth() + 1) + '/' + TuFecha.getFullYear();
			alert(resultado);
			*/
		}
		
		 $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
            },

            messages: {
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-error', $('.login-form')).show();
            },

            highlight: function (e) {
                $(e).closest('.control-group').removeClass('info').addClass('error');
            },

            success: function (e) {
                $(e).closest('.control-group').removeClass('error').addClass('info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if (element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('.controls');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl').eq(0));
                }
                else if (element.is('.chzn-select')) {
                    error.insertAfter(element.nextAll('[class*="chzn-container"]').eq(0));
                }
                else error.insertAfter(element);
            },

            submitHandler: function (form) {

				  var confirmacion = confirm("Se guardarán los datos del formulario.\n¿Confirma la acción?");
				  if(confirmacion){
				  }else{
					return false;
				  }
				
				   var forma_pago_en_partes_detalle = JSON.stringify(get_forma_pago_en_partes_to_array());
				   var penalidades 					= JSON.stringify(get_penalidades_to_array());
				
					var tipo_flujo_contrato = <?=$TIPO_FLUJO_CONTRATO?>;
				
				   var reqgen_a_empresa = $("#empresa").val();
				   var reqgen_a_areasolicitante = $("#area_solicitante_usuario").val();
				   var reqgen_a_areasolicitante_jefatura = $("#area_solicitante_jefatura :selected").val();
				   
				   <?php
					if( $TIPO_FLUJO_CONTRATO == $TIPO_USUARIO_COMPRADOR ){
						echo 'var reqgen_a_compradorresponsable = $("#comprador_responsable").val();';
					}else{
						echo 'var reqgen_a_compradorresponsable = 0;';
					}
					
					if( $showData && !empty($data_contract['contraprestacion_file']) ){
						echo "var noFilesUploadedToMontoContraprestacion = false;";
					}else{
						echo "var noFilesUploadedToMontoContraprestacion = true;";
					}
				   ?>
				   
				   var reqgen_a_areausuaria = $("#area_usuario").val();
				   var reqgen_a_areausuaria_jefatura = $("#area_usuario_jefatura :selected").val();
				   var reqgen_proveedor = $("#proveedor").val();
				   var reqgen_proveedor_ruc = $("#proveedor_ruc").val();
				   var termiesp_a_tipocontrato = $("#tipo_contrato").val();
				   var tipocontrato_otrosdesc = $("#tipocontrato_otrosdesc").val();
				   var termiesp_a_nrocotizacion = $("#tipo_contrato_cotizacion").val();
				   var termiesp_a_fecha = $("#tipo_contrato_fecha").val();
				   var termiesp_b_alcance = $("#alcance_contrato").val();
				   var termiesp_c_dias = $("#plazo_vigencia_dias").val();
				   var termiesp_c_formato = $("#plazo_vigencia_formato").val();
				   var termiesp_c_medida = $("#plazo_vigencia_medida").val();
				   var termiesp_c_fechainicio = $("#plazo_vigencia_inicio").val();
				   var termiesp_c_fechafin = $("#plazo_vigencia_termino").val();			   
				   var tipo_renovacion = $("input[name='renovacion']:checked").val();
				   
				   var termiesp_c_incluyeacta = 0;
				   if($("#plazo_vigencia_incluye_actaentrega").is( ":checked" )){
					   termiesp_c_incluyeacta = 1;
				   }
					
				   var cotizacion_bynrocontrato = 0;
				   if($("#cotizacion_bynrocontrato").is( ":checked" )){
					   //termiesp_a_nrocotizacion = $("#nro_cotizacion_contrato_vinculado :selected").val();
					   cotizacion_bynrocontrato = 1;
				   }
				   
				   var contraprestacion_incdocumento = $('#contraprestacion_incdocumento').is(':checked');
				   var termiesp_d_monto = $("#contraprestacion_monto").val();
				   var termiesp_d_moneda = $("#contraprestacion_medida").val();
				   
				   var formapago_medida = $("#formapago_medida").val();
				   var modalidadpago_cartafianza_medida = $("#modalidadpago_cartafianza_medida").val();
				   var modalidadpago_adelanto_medida = $("#modalidadpago_adelanto_medida").val();
				   var modalidadpago_fcumplimiento_medida = $("#modalidadpago_fcumplimiento_medida").val();
				   var modalidadpago_fgarantia_medida = $("#modalidadpago_fgarantia_medida").val();
				   var monto_mobiliario_medida = $("#monto_mobiliario_medida").val();
				   var penalidades_medida = $("#penalidades_medida").val();
				   
				   var termiesp_e_formapago = $('input:radio[name=formapago]:checked').val();
				   if(typeof termiesp_e_formapago === "undefined"){
					   termiesp_e_formapago = 0;
				   }
				   
				   var termiesp_e_avancez_medida = $("#formapago_por_avanzes").val();
				   var termiesp_e_credito_dias = $("#formapago_por_credito").val();
				   
				   if(termiesp_e_credito_dias === undefined)termiesp_e_credito_dias = 0;
				   
				   var termiesp_f_modalidadpago = $('input:radio[name=modalidadpago]:checked').val();
				   if(typeof termiesp_f_modalidadpago === "undefined"){
					   termiesp_f_modalidadpago = 0;
				   }
				   var modalidadpago_otro = $("#modalidadpago_otro").val();
                   var modalidadpago_transcuenta_desc = $("#modalidadpago_transcuenta_desc").val();

                   if(termiesp_f_modalidadpago == 12){
                       modalidadpago_transcuenta_desc = "";
                   }else if(termiesp_f_modalidadpago == 1 ){
                       modalidadpago_otro = "";
                   }
				   
				   var termiesp_g_garantia = $('input:radio[name=garantia]:checked').val();
				   if(typeof termiesp_g_garantia === "undefined"){
					   termiesp_g_garantia = 0;
				   }
				   var modalidadpago_adelanto_adelantofile = $("#modalidadpago_adelanto_adelantofile").val();

				   var termiesp_g_cartafianza_importe = $("#modalidadpago_cartafianza_importe").val();
				   var termiesp_g_adelanto_importe = $("#modalidadpago_adelanto_importe").val();
				   var modalidadpago_adelanto_exception = $("#modalidadpago_adelanto_exception").val();
				   var termiesp_g_fcumplimiento_importe = $("#modalidadpago_fcumplimiento_importe").val();
				   var termiesp_g_fondogarantia_importe = $("#modalidadpago_fgarantia_importe").val();
				   var termiesp_h_lugarentrega = $("#lugar_entrega").val();
				   var lugar_entrega_personal_tercero_numero = $("#lugar_entrega_personal_tercero_numero").val();
				   
				   var lugar_entrega_personal_tercero_aux = $('#lugar_entrega_personal_tercero').is(':checked');
				   var lugar_entrega_personal_tercero = 0;
				   if(lugar_entrega_personal_tercero_aux==true){
					   lugar_entrega_personal_tercero = 1;
				   }
				   
				   var lugar_entrega_personal_tercero_dias = $("#lugar_entrega_personal_tercero_dias").val();
				   var lugar_entrega_personal_tercero_equipo = $("#lugar_entrega_personal_tercero_equipo").val();
				   var termiesp_i_observacionesamplicaciones = $("#observaciones_amplicaciones").val();
				   var reqesp_ruta = $("#ruta").val();
				   var autorizac_a_nombres = "<?=$autorizac_a_nombres?>";
				   var autorizac_a_cargo = "<?=$autorizac_a_cargo?>";
				   var autorizac_a_fecha = "<?=$autorizac_a_fecha?>";				   				   
				   var datosgenerales_usuarioregistra = <?=$_SESSION['id']?>;
				   var datosgenerales_estado = 0;
				   var datosgenerales_codigo = 0;
				   
				   var metas_cumplir_comentario		=	$("#metas_cumplir_comentario").val();

				   var monto_mobiliario				=	$("#monto_mobiliario").val();
				   
				   ///////////////START VALIDACIONES

				<?php if(isset($_GET['id'])){ ?>
				if(!validateAdjuntosEliminables()){
					return false;
				}
				<?php } ?>
				   
				   if(typeof reqgen_a_areausuaria_jefatura === 'undefined'){
					   alert("JEFATURA DE AREA USUARIA: No permitido");
					   return false;
				   }
				   
				   if(typeof reqgen_a_areasolicitante_jefatura === 'undefined'){
					   alert("JEFATURA DE AREA SOLICITANTE: No permitido");
					   return false; 
				   }
				   
				   if(termiesp_a_tipocontrato == 0){
					   alert("TIPO DE CONTRATO: No permitido");
					   return false;
				   }
				   if(cotizacion_bynrocontrato == 1 && termiesp_a_nrocotizacion == "0"){
					   alert("NRO CONTRATO VINCULADO (Nro Cotizacion): Debe ser seleccionado");
					   return false;
				   }
				   
				   if(termiesp_a_nrocotizacion == ""){
					   alert("NRO DE COTIZACION: No puede estar vacio");
					   return false;
				   }
                    if(termiesp_a_nrocotizacion.length > 255){
                        alert("NRO DE COTIZACION: Maximo numero de caracteres es 255");
                        return false;
                    }
				   if(termiesp_a_fecha == ""){
					   alert("FECHA DE COTIZACION: No puede estar vacio");
					   return false;
				   }
				   
				   if(termiesp_a_tipocontrato == 14 && tipocontrato_otrosdesc == ""){
						alert("Selecciono tipo de contrato otro, debe especificar.");   
						return false;
				   }
				   
				   if(termiesp_b_alcance == ""){
					   alert("Debe ingresar alcance");   
					   return false;
				   }
                   if(termiesp_b_alcance.length > 1000){
                       alert("Alcance: Maximo numero de caracteres es 1000");
                       return false;
                   }

					if(termiesp_c_dias == ""){
						alert("Indique plazo de vigencia");   
					   return false;
					}
				   
				   if(termiesp_c_medida == "0"){
					   alert("Seleccione formato de plazo de vigencia");   
					   return false;
				   }
				   if(termiesp_c_fechainicio == ""){
					   alert("Indique fecha de inicio");   
					   return false;
				   }
					if(!isValidDate(termiesp_c_fechainicio)){
						alert("Formato para Fecha Inicio es incorrecto. Debe usar: yyyy/mm/dd");
						return false;
					}

				   if(termiesp_c_fechafin == ""){
					   alert("Indique fecha de fin");   
					   return false;
				   }
					if(!isValidDate(termiesp_c_fechafin)){
						alert("Formato para Fecha Fin es incorrecto. Debe usar: yyyy/mm/dd");
						return false;
					}
				   
				   if(termiesp_d_monto == "" && contraprestacion_incdocumento == false){
					   alert("Indique monto de contraprestación");   
					   return false;
				   }
				   
				   if(termiesp_d_moneda == 0 && contraprestacion_incdocumento == false){
					   alert("Seleccione tipo de moneda de contraprestación");   
					   return false;
				   }
				   
				   var contraprestacion_file = $("#contraprestacion_file").val();
				   if(contraprestacion_file == "" && contraprestacion_incdocumento && noFilesUploadedToMontoContraprestacion){
						alert("Validacion: Debe seleccionar archivo con montos de cotraprestación");
						return false;
				    }
				   
				   if( termiesp_e_formapago == 1 && formapago_medida == 0){
					   alert("Seleccione forma de pago");   
					   return false;
				   }
				   
				   if(termiesp_e_formapago == 1 && termiesp_e_formapago == 0){
					   alert("Seleccione tipo de moneda en forma de pago en partes");   
					   return false;
				   }				   
				   
				   if(termiesp_e_formapago == 1 && forma_pago_en_partes_detalle == "[]" ){
					   alert("Debe ingresar al menos un item de detalle en forma de pago");   
					   return false;
				   }
				   
				   if(termiesp_g_garantia == 3 && modalidadpago_adelanto_medida == 0){
					   alert("Seleccione tipo de moneda en garantia adelantos");   
					   return false;
				   }
				   
				   if(termiesp_g_garantia == 4 && modalidadpago_fcumplimiento_medida == 0){
					   alert("Seleccione tipo de moneda en garantia fiel cumplimiento");   
					   return false;
				   }
				   
				   if(termiesp_g_garantia == 6 && modalidadpago_fgarantia_medida == 0){
					   alert("Seleccione tipo de moneda en garantia fondo garatia");   
					   return false;
				   }
				   
				   if(termiesp_g_garantia == 2 && modalidadpago_cartafianza_medida == 0){
					   alert("Seleccione tipo de moneda en carta fianza");   
					   return false;
				   }
				   
				   if(termiesp_f_modalidadpago == 0){
					   alert("Seleccione modalidad de pago");   
					   return false;
				   }
				   
				   if(termiesp_e_avancez_medida == 0 && termiesp_e_formapago == 3){
					   alert("FORMA DE PAGO, Por avances: No permitido");
					   return false;
				   }
				   if(termiesp_e_credito_dias == 0 && termiesp_e_formapago == 4){
					   alert("FORMA DE PAGO, Por credito: No permitido");
					   return false;
				   }
				   
				   if(termiesp_f_modalidadpago == 12 && modalidadpago_otro == ""){
					   alert("Modalidad de pago otro, no debe estar vacio");
					   return false;
				   }
                   if(termiesp_f_modalidadpago == 12 && modalidadpago_otro.length > 250){
                        alert("Modalidad de pago otro: Maximo numero de caracteres es 250");
                        return false;
                   }

                   if(termiesp_f_modalidadpago == 1 && modalidadpago_transcuenta_desc == ""){
                        alert("Modalidad de pago detalle, no debe estar vacio");
                        return false;
                    }
                   if(termiesp_f_modalidadpago == 1 && modalidadpago_transcuenta_desc.length > 500){
                        alert("Modalidad de pago transferencia cuenta detalle: Maximo numero de caracteres es 500");
                        return false;
                   }
				   
				   if(termiesp_g_garantia == 0){
					   alert("GARANTIA: No puede estar vacio");
					   return false;
				   }
				   
				   if(termiesp_g_garantia == 3){
					   
					   if(termiesp_g_adelanto_importe == ""){
						   alert("GARANTIA: Debe ingresar importe");
						   return false;
					   }   
					   if(modalidadpago_adelanto_exception == ""){
						   alert("GARANTIA: Debe ingresar excepción");
						   return false;
					   }
                       if(modalidadpago_adelanto_exception.length > 250){
                           alert("Excepción: Maximo numero de caracteres es 250");
                           return false;
                       }
                       /*if(modalidadpago_adelanto_adelantofile == ""){
                           alert("GARANTIA: Debe seleccionar archivo");
                            return false;
                       } */
					   
				   }
				   
				    if(termiesp_g_garantia == 6 && termiesp_g_fondogarantia_importe == ""){
						alert("GARANTIA: Debe ingresar importe");
						return false;						
					}
					
					if(termiesp_g_garantia == 2 && termiesp_g_cartafianza_importe == ""){
						alert("GARANTIA: Debe ingresar importe");
						return false;						
					}
					
					if(termiesp_g_garantia == 4 && termiesp_g_fcumplimiento_importe == ""){
						alert("GARANTIA: Debe ingresar importe");
						return false;						
					}

                    if(termiesp_h_lugarentrega.length > 250){
                        alert("LUGAR DE SERVICIO: Maximo numero de caracteres es 250");
                        return false;
                    }
                    if(lugar_entrega_personal_tercero_equipo.length > 250){
                        alert("LUGAR DE SERVICIO - Equipo: Maximo numero de caracteres es 250");
                        return false;
                    }
                    if(termiesp_i_observacionesamplicaciones.length > 1000){
                        alert("OBSERVACIONES O AMPLIACIONES: Maximo numero de caracteres es 1000");
                        return false;
                    }
                    if(metas_cumplir_comentario.length > 1000){
                        alert("METAS A CUMPLIR (ENTREGABLES): Maximo numero de caracteres es 1000");
                        return false;
                    }
                    if(reqesp_ruta.length > 250){
                        alert("RUTA: Maximo numero de caracteres es 250");
                        return false;
                    }
				   
				   
				   
				   /////////// END VALIDACIONES

				var formData = new FormData();

				   var proveedor_tipo = $('input:radio[name=proveedor_tipo]:checked').val();
				   if(typeof proveedor_tipo === "undefined"){
					   alert("DATOS PROVEEDOR, Tipo: Debe seleccionar un tipo");
					   return false;
				   }else if(proveedor_tipo == 1){//Persona Juridica
						
						<?php if(!isset($_GET['id'])){ ?>
						var archivo1 = $("#proveedor_jur_file_ficharuc").val();
						var archivo2 = $("#proveedor_jur_file_represetante").val();
						var archivo3 = $("#proveedor_jur_file_vigenciapoder").val();
						if(archivo1 == "" || archivo2 == "" || archivo3 == ""){
							alert("Validacion: Debe seleccionar archivo");
							return false;
						}
						<?php } ?>
				   
				   		formData.append("proveedor_jur_file_ficharuc", $("#proveedor_jur_file_ficharuc")[0].files[0]);
						formData.append("proveedor_jur_file_represetante", $("#proveedor_jur_file_represetante")[0].files[0]);
						formData.append("proveedor_jur_file_vigenciapoder", $("#proveedor_jur_file_vigenciapoder")[0].files[0]);
				   }else if(proveedor_tipo == 2){//Persona Natural
						
						<?php if(!isset($_GET['id'])){ ?>
						var archivo1 = $("#proveedor_nat_file_ficharuc").val();
						var archivo2 = $("#proveedor_nat_file_represetante").val();
						if(archivo1 == "" || archivo2 == ""){
							alert("Validacion: Debe seleccionar archivo");
							return false;
						}
						<?php } ?>
						
				   		formData.append("proveedor_nat_file_ficharuc", $("#proveedor_nat_file_ficharuc")[0].files[0]);
						formData.append("proveedor_nat_file_represetante", $("#proveedor_nat_file_represetante")[0].files[0]);
				   }
				   
				if( contraprestacion_incdocumento ){
					formData.append("contraprestacion_file", $("#contraprestacion_file")[0].files[0]);
				}
				
				var contrato_propuesto_proveedor = $("#contrato_propuesto_proveedor").val();
				if(contrato_propuesto_proveedor != ""){
					formData.append("contrato_propuesto_proveedor", $("#contrato_propuesto_proveedor")[0].files[0]);
				}
				
				if(modalidadpago_adelanto_adelantofile != ""){
					formData.append("modalidadpago_adelanto_adelantofile", $("#modalidadpago_adelanto_adelantofile")[0].files[0]);
				}
				
				var metas_cumplir_entregables 	=	$("#metas_cumplir_entregables").val();
				if(metas_cumplir_entregables != ""){
					formData.append("metas_cumplir_entregables", $("#metas_cumplir_entregables")[0].files[0]);
				}
				
				var cantidad_files_rubroinmobiliario_tp = document.getElementsByName('archivos_inmuebles_tp[]').length;
				for(var i = 0; i < cantidad_files_rubroinmobiliario_tp; i++){
					if(document.getElementById('archivos_inmuebles_tp_'+i).value == ""){
						alert("Debe seleccionar archivo - Rubro inmobiliario");
						return false;
					}else{
						formData.append("archivos_inmuebles_tp[]", $("#archivos_inmuebles_tp_"+i)[0].files[0]);
					}
				}
				
				var cantidad_files_rubroinmobiliario_cg = document.getElementsByName('archivos_inmuebles_cg[]').length;
				for(var i = 0; i < cantidad_files_rubroinmobiliario_cg; i++){
					if(document.getElementById('archivos_inmuebles_cg_'+i).value == ""){
						alert("Debe seleccionar archivo - Rubro inmobiliario");
						return false;
					}else{
						formData.append("archivos_inmuebles_cg[]", $("#archivos_inmuebles_cg_"+i)[0].files[0]);
					}
				}
				
				var cantidad_files_sct2 = document.getElementsByName('archivos_sct2[]').length;				
				for(var i = 1; i < cantidad_files_sct2 + 1; i++){
					if(document.getElementById('archivos_sct2_'+i).value == ""){
						alert("Debe seleccionar archivo - Rubro inmobiliario");
						return false;
					}else{
						formData.append("archivos_sct2[]", $("#archivos_sct2_"+i)[0].files[0]);
					}
				}
				
				var cantidad_files_OA1 = document.getElementsByName('archivos_OA1[]').length;				
				for(var i = 1; i < cantidad_files_OA1 + 1; i++){
					if(document.getElementById('archivos_OA1_'+i).value == ""){
						alert("Debe seleccionar archivo - Observaciones o Ampliaciones");
						return false;
					}else{
						formData.append("archivos_OA1[]", $("#archivos_OA1_"+i)[0].files[0]);
					}
				}
				
				if(contraprestacion_incdocumento)contraprestacion_incdocumento=1; else contraprestacion_incdocumento=0;
				
				
				formData.append("mode", <?=$showData==true?2:1?>/*1:guardar 2:editar*/);
				formData.append("id", <?=isset($_GET['id'])?$_GET['id']:0?>);
				formData.append("cod", 3);
				formData.append("tipo_flujo_contrato", tipo_flujo_contrato);
				formData.append("contrato_vinculado", 0);
				formData.append("reqgen_a_empresa", reqgen_a_empresa);
				formData.append("reqgen_a_areasolicitante", reqgen_a_areasolicitante);
				formData.append("reqgen_a_areasolicitante_jefatura", reqgen_a_areasolicitante_jefatura);
				formData.append("reqgen_a_compradorresponsable", reqgen_a_compradorresponsable);
				formData.append("reqgen_a_areausuaria", reqgen_a_areausuaria);
				formData.append("reqgen_a_areausuaria_jefatura", reqgen_a_areausuaria_jefatura);
				formData.append("reqgen_proveedor", reqgen_proveedor);
				formData.append("reqgen_proveedor_ruc", reqgen_proveedor_ruc);
				formData.append("termiesp_a_tipocontrato", termiesp_a_tipocontrato);
				formData.append("tipocontrato_otrosdesc", tipocontrato_otrosdesc);				
				formData.append("termiesp_a_nrocotizacion", termiesp_a_nrocotizacion);
				formData.append("termiesp_a_fecha", termiesp_a_fecha);
				formData.append("termiesp_b_alcance", termiesp_b_alcance);
				formData.append("termiesp_c_dias", termiesp_c_dias);
				formData.append("termiesp_c_formato", termiesp_c_formato);
				formData.append("termiesp_c_medida", termiesp_c_medida);
				formData.append("termiesp_c_fechainicio", termiesp_c_fechainicio);
				formData.append("termiesp_c_fechafin", termiesp_c_fechafin);
				formData.append("termiesp_c_incluyeacta", termiesp_c_incluyeacta);
				formData.append("termiesp_d_monto", termiesp_d_monto);
				formData.append("termiesp_d_moneda", termiesp_d_moneda);
				formData.append("termiesp_e_formapago", termiesp_e_formapago);
				formData.append("termiesp_e_avancez_medida", termiesp_e_avancez_medida);
				formData.append("termiesp_e_credito_dias", termiesp_e_credito_dias);
				formData.append("termiesp_f_modalidadpago", termiesp_f_modalidadpago);
				formData.append("modalidadpago_otro", modalidadpago_otro);
                formData.append("modalidadpago_transcuenta_desc", modalidadpago_transcuenta_desc);
                formData.append("termiesp_g_garantia", termiesp_g_garantia);
				formData.append("modalidadpago_cartafianza_importe", termiesp_g_cartafianza_importe);
				formData.append("modalidadpago_adelanto_exception", modalidadpago_adelanto_exception);				
				formData.append("termiesp_g_adelanto_importe", termiesp_g_adelanto_importe);
				formData.append("termiesp_g_fcumplimiento_importe", termiesp_g_fcumplimiento_importe);
				formData.append("termiesp_g_fondogarantia_importe", termiesp_g_fondogarantia_importe);
				formData.append("termiesp_h_lugarentrega", termiesp_h_lugarentrega);
				formData.append("lugar_entrega_personal_tercero", lugar_entrega_personal_tercero);
				formData.append("lugar_entrega_personal_tercero_numero", lugar_entrega_personal_tercero_numero);
				formData.append("lugar_entrega_personal_tercero_dias", lugar_entrega_personal_tercero_dias);
				formData.append("lugar_entrega_personal_tercero_equipo", lugar_entrega_personal_tercero_equipo);
				formData.append("termiesp_i_observacionesamplicaciones", termiesp_i_observacionesamplicaciones);
				formData.append("reqesp_ruta", reqesp_ruta);
				formData.append("autorizac_a_nombres", autorizac_a_nombres);
				formData.append("autorizac_a_cargo", autorizac_a_cargo);
				formData.append("autorizac_a_fecha", autorizac_a_fecha);
				formData.append("datosgenerales_usuarioregistra", datosgenerales_usuarioregistra);
				formData.append("datosgenerales_estado", datosgenerales_estado);
				formData.append("datosgenerales_codigo", datosgenerales_codigo);
				formData.append("forma_pago_en_partes_detalle", forma_pago_en_partes_detalle);
				formData.append("proveedor_tipo", proveedor_tipo);
				formData.append("tipo_renovacion", tipo_renovacion);
				formData.append("metas_cumplir_comentario", metas_cumplir_comentario);
				formData.append("penalidades", penalidades);
				formData.append("monto_mobiliario", monto_mobiliario);	
				formData.append("formapago_medida", formapago_medida);
				formData.append("modalidadpago_cartafianza_medida", modalidadpago_cartafianza_medida);
				formData.append("modalidadpago_adelanto_medida", modalidadpago_adelanto_medida);
				formData.append("modalidadpago_fcumplimiento_medida", modalidadpago_fcumplimiento_medida);
				formData.append("modalidadpago_fgarantia_medida", modalidadpago_fgarantia_medida);
				formData.append("monto_mobiliario_medida", monto_mobiliario_medida);
				formData.append("penalidades_medida", penalidades_medida);
				formData.append("contraprestacion_incdocumento", contraprestacion_incdocumento);
				formData.append("cotizacion_bynrocontrato", cotizacion_bynrocontrato);
				
				
				

                $.ajax({
                    data:  formData,
                    
					cache: false,
					contentType: false,
					processData: false,
					mimeType: 'multipart/form-data',

                    url:   '../phps/dcontract_ajax.php',
                    type:  'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#nueva_actividad').modal('hide');
                        openModal();
                    },
                    success: function(respuesta){
						
						var respuesta_temp = respuesta;
						
						try {
						  respuesta = $.parseJSON( respuesta );
						  if(respuesta.estado == "1"){

								closeModal();
								
								if(respuesta.data != ""){
									$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');                            
									setTimeout(function(){window.location = "create.php?id="+respuesta.data+"&mode=edit"; }, <?php echo $SLEEP_TIME ?>);
								}else{
									$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');                            
									setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);
								}								

							}else{

								closeModal();
								$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
								alert(respuesta.error);
								//location.reload();
							}
						} catch (error) {
						  alert("ERROR, los datos podrian no haberse guardado. Por favor revise la lista de contratos antes de continuar.\n\Server Error 0x1:\n"+respuesta_temp);
						  closeModal();
						}
                    },
                    error: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    },
                    failure: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    }
                });


            },
            invalidHandler: function (form) {
            }
        });
        
	function get_forma_pago_en_partes_to_array() {

			var rows = $("#table_report").dataTable().fnGetNodes();
    
			var detalle = new Array();
			var porcentaje = 0;
			var importe = 0;			

			for (var i = 0; i < rows.length; i++) {
				
				try {
					porcentaje = parseFloat($(rows[i]).find("input.per").val());
					importe = parseFloat($(rows[i]).find("input.imp").val());					
					var lista = {
						porcentaje: porcentaje, importe: importe
					};

					detalle.push(lista);
				}
				catch (err) {
					alert("Error: " + err);
					return false;
				}							
			}

			return detalle;
	}
	
	function get_penalidades_to_array() {

			var rows = $("#table_report2").dataTable().fnGetNodes();
    
			var detalle = new Array();
			var supuesto = "";
			var sancion = "";			

			for (var i = 0; i < rows.length; i++) {
				
				
				supuesto = $(rows[i]).find("input.supuesto").val();
				sancion = $(rows[i]).find("input.sancion").val();					
				var lista = {
						supuesto: supuesto, sancion: sancion
				};

				detalle.push(lista);
											
			}

			return detalle;
	}
		
	function send_1(id){

		if(!validateAdjuntosEliminables(true)){
			return false;
		}

		var confirmacion = confirm("Esta acción enviará la solicitud para validación.\n**Cambios en el formulario no serán guardados.**\n¿Confirma la acción?");
		if(confirmacion){
		}else{
			return false;
		}
		
		var parametros = {
				"id"  : 	 <?=isset($_GET['id'])?$_GET['id']:0?>,
				"idusuario": <?=$_SESSION['id']?>,
				"cod" : 	 4/*3:enviar para validacion*/
		}
		$.ajax({
                    data:  parametros,
                    url:   '../phps/dcontract_ajax.php',
                    type:  'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#nueva_actividad').modal('hide');
                        openModal();
                    },
                    success: function(respuesta){
						
						var respuesta_temp = respuesta;
						
						try {

							respuesta = $.parseJSON( respuesta );

							if(respuesta.estado == "1"){

								closeModal();
								$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');                            
								setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);

							}else{

								closeModal();
								$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
								alert(respuesta.error);
								//location.reload();
							}
						} catch (error) {						  
						  alert("ERROR, algo falló al procesar la data.\nServer Error 0x2:\n"+respuesta_temp);
						  closeModal();
						}
                    },
                    error: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    },
                    failure: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    }
                });
	}
		
	function send_data(id,new_estado,waitFirmaModo=0,nuevoEstadoDerivacion=-1){

		var confirmacion = confirm("Esta acción cambiara el estado de la solicitud.\n¿Confirma la acción?");
		if(confirmacion){
		}else{
			return false;
		}
		
		var data_observacion = $("#contrato_file1_obs2").val();
		if(data_observacion == ""){
			alert("Validacion: Debe ingresar comentario");
			return false;
		}
		
		var formData = new FormData(document.getElementById("validation-form_nuevo_3"));
        formData.append("cod", 5);
        formData.append("waitFirmaModo", waitFirmaModo);
		formData.append("nuevoEstadoDerivacion", nuevoEstadoDerivacion);
		formData.append("current_estado", <?=$estado?>);
		formData.append("new_estado", new_estado);
		formData.append("idusuario", <?=$_SESSION['id']?>);
		formData.append("id", <?=isset($_GET['id'])?$_GET['id']:0?>);
		formData.append("role", <?=isset($_GET['role'])?("'".$_GET['role']."'"):"''"?>);
		formData.append("TIPO_FLUJO_CONTRATO", <?=$TIPO_FLUJO_CONTRATO?>);		
		
		<?php if( $displayFile_new_movimiento ){ ?>
		var archivo = $("#contrato_file1").val();
		if(archivo == ""){
			formData.append("inc_file", 0);
		}else{
			formData.append("inc_file", 1);
		}
		<?php }else{ ?>
			formData.append("inc_file", 0);
		<?php } ?>
		
		if(<?=$estado?> == 1){			
			formData.append("flow", 				'<?=$TIPO_FLOW_LEGAL?>');
			
			if(new_estado == 2 && nuevoEstadoDerivacion != 2){
				var archivo = $("#contrato_file1").val();
				if(archivo == ""){
					alert("Validacion: Debe seleccionar archivo");
					return false;
				}
			}
		}else if(<?=$estado?> == 2){
			formData.append("flow", '<?=$TIPO_FLOW_USUARIO?>');
			formData.append("validateOthers", '1');
			formData.append("flag_has_last_approved_usuario", "<?=(  (isset($_GET['role']) && $_GET['role'] == 'jefe')?1:0 )?>");
			formData.append("flag_has_last_approved_logistica", "<?=(  (isset($_GET['role']) && $_GET['role'] == 'logistica')?1:0 )?>");
		}else if(<?=$estado?> == 3){
			formData.append("flow", 				'<?=$TIPO_FLOW_LEGAL?>');
			
			if(new_estado == 4){
				var archivo = $("#contrato_file1").val();
				if(archivo == ""){
					alert("Validacion: Debe seleccionar archivo");
					return false;
				}
			}
		}else if(<?=$legal_advance_options?'true':'false'?>){
            formData.append("flow", 				'<?=$TIPO_FLOW_LEGAL?>');
        }else if(<?=$estado?> == 0.3){
			formData.append("autorizac_b_nombres", 	'<?=$currentUserNames_fromActiveDirectory?>');
			formData.append("autorizac_b_cargo", 	'<?=$currentUserCargo_fromActiveDirectory?>');
			formData.append("autorizac_b_fecha", 	'<?=$currentFecha?>');
			formData.append("flow", 				'<?=$TIPO_RESPONSABLE_AREA?>');
		}else if(<?=$estado?> == 0.6){
			formData.append("autorizac_c_nombres", 	'<?=$currentUserNames_fromActiveDirectory?>');
			formData.append("autorizac_c_cargo", 	'<?=$currentUserCargo_fromActiveDirectory?>');
			formData.append("autorizac_c_fecha", 	'<?=$currentFecha?>');
			formData.append("flow", 				'<?=$TIPO_RESPONSABLE_LOGISTICA?>');
		}else if(<?=$estado?> == 0.8){
			formData.append("flow", 				'<?=$TIPO_FLOW_LEGAL?>');
		}
		
		$.ajax({
                    data:  formData,
                    url: "../phps/dcontract_ajax.php",
                    type:  'post',
                    dataType: "html",
					cache: false,
					contentType: false,
					processData: false,
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#nueva_actividad').modal('hide');
                        openModal();
                    },
                    success: function(respuesta){
						
						var respuesta_temp = respuesta;
						
						try {
							respuesta = $.parseJSON( respuesta );

							if(respuesta.estado == "1"){

								closeModal();
								$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>'); 
								if(<?=($noUserFlow==true?"true":"false")?>){
									setTimeout(function(){window.location = "../contract_miscontratos/index.php"; }, <?php echo $SLEEP_TIME ?>);
								}else{
									setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);
								}                            

							}else{

								closeModal();
								$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
								alert(respuesta.error);
								//location.reload();
							}
						  
						} catch (error) {						  
						  alert("ERROR, algo falló al procesar la data.\nServer Error 0x3:\n"+respuesta_temp);
						  closeModal();
						}
                    },
                    error: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    },
                    failure: function(respuesta){
                        closeModal();alert("Error al conectar con el servidor");
                    }
                });
	}
	
	function anular_solicitud(id){
		
		var parametros = {
                "id" : id,
				"cod" : 6,
				"usuario" : <?=$_SESSION['id']?>
            };

            $.ajax({
                data:  parametros,
                url:   '../phps/dcontract_ajax.php',
                type:  'post',
                dataType: "html",
                beforeSend: function (repuesta) {
                    // lo que se hace mientras llega
                    openModal();
                },
                success: function(respuesta){
                    respuesta = $.parseJSON( respuesta );

                    if(respuesta.estado == "1"){

                        closeModal();
                        $().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
                        setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

                    }else{

                        closeModal();
                        $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                        alert(respuesta.error);
                        location.reload();
                    }
                },
                error: function(respuesta){
                    closeModal();alert("Error al conectar con el servidor");
                },
                failure: function(respuesta){
                    closeModal();alert("Error al conectar con el servidor");
                }
            });
	}
	
	function ver_contrato(url){

		var pagina="../files/contratos/<?=$data_contract['datosgenerales_codigo']?>/"+url;
		var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=YES, resizable=yes, width=800, height=600, top=85, left=100";	
		window.open(pagina,"",opciones);
	}
	function ver_archivo(url){

		var pagina="../files/contratos/<?=$data_contract['datosgenerales_codigo']?>/"+url;
		var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=YES, resizable=yes, width=800, height=600, top=85, left=100";	
		window.open(pagina,"",opciones);
	}
	
	rObj = function (evt) { 
	   return evt.srcElement ?  evt.srcElement : evt.target;
	}
	
	evento = function (evt) { 
	   return (!evt) ? event : evt;
	}
	
	elimCamp = function (evt){
	   evt = evento(evt);
	   nCampo = rObj(evt);
	   div = document.getElementById(nCampo.name);
	   div.parentNode.removeChild(div);
	}
	
	var numero = 0;
	addCampo = function () { 
	
	  //Creo un div dentro del cul iran los nuevos elmentos
	   nDiv 			= document.createElement('div');
	   nDiv.className 	= 'control-group';
	   nDiv.id 			= 'file' + (++numero);
	  
	  //Creo un label
	   newlabel = document.createElement("Label");	
	   newlabel.className = 'control-label';
	   newlabel.innerHTML = "Archivo:";
	  
	   //Creo el input file
	   nCampo 			= document.createElement('input');
	   nCampo.name 		= 'archivos[]';
	   nCampo.type 		= 'file';
	   nCampo.size 		= '30';
	   nCampo.accept	= 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	   //nCampo.setAttribute("onchange", function(){ValidateSize(this);});	   
	   nCampo.onchange  = function(){ValidateSize(this);};//'ValidateSize(this)';
	   
	   //Creo el link para Eliminar
	   a 				= document.createElement('a');
	   a.name 			= nDiv.id;
	   a.href 			= '#';
	   a.onclick 		= elimCamp;
	   a.innerHTML 		= 'Eliminar';  
	   
	   //Agrego el input file y el link al nuevo div
	   nDiv.appendChild(newlabel);
	   nDiv.appendChild(nCampo);
	   nDiv.appendChild(a);
	   
	   //Agrego el nuevo div al container div adjuntos
	   container		 = document.getElementById('adjuntos');
	   container.appendChild(nDiv);

	}
	
	var numero1 = 0;
	addFilesPRsct2 = function () { 
	
	  //Creo un div dentro del cul iran los nuevos elmentos
	   nDiv 			= document.createElement('div');
	   nDiv.className 	= 'control-group';
	   nDiv.id 			= 'file' + (++numero1);
	  
	  //Creo un label
	   newlabel = document.createElement("Label");	
	   newlabel.className = 'control-label';
	   newlabel.innerHTML = "Archivo:";
	  
	   //Creo el input file
	   nCampo 			= document.createElement('input');
	   nCampo.name 		= 'archivos_sct2[]';
	   nCampo.id 		= 'archivos_sct2_'+numero1;
	   nCampo.type 		= 'file';
	   nCampo.size 		= '30';
	   nCampo.accept	= 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	   //nCampo.setAttribute("onchange", function(){ValidateSize(this);});	   
	   nCampo.onchange  = function(){ValidateSize(this);};//'ValidateSize(this)';
	   
	   //Creo el link para Eliminar
	   a 				= document.createElement('a');
	   a.name 			= nDiv.id;
	   a.href 			= '#/';
	   a.onclick 		= elimCamp;
	   a.innerHTML 		= 'Eliminar';  
	   
	   //Agrego el input file y el link al nuevo div
	   nDiv.appendChild(newlabel);
	   nDiv.appendChild(nCampo);
	   nDiv.appendChild(a);
	   
	   //Agrego el nuevo div al container div adjuntos
	   container		 = document.getElementById('adjuntos_sct2');
	   container.appendChild(nDiv);
	}
	
	var numero2 = 0;
	addFilesOA1 = function () { 
	
	  //Creo un div dentro del cul iran los nuevos elmentos
	   nDiv 			= document.createElement('div');
	   nDiv.className 	= 'control-group';
	   nDiv.id 			= 'file' + (++numero2);
	  
	  //Creo un label
	   newlabel = document.createElement("Label");	
	   newlabel.className = 'control-label';
	   newlabel.innerHTML = "Archivo:";
	  
	   //Creo el input file
	   nCampo 			= document.createElement('input');
	   nCampo.name 		= 'archivos_OA1[]';
	   nCampo.id 		= 'archivos_OA1_'+numero2;
	   nCampo.type 		= 'file';
	   nCampo.size 		= '30';
	   nCampo.accept	= 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	   //nCampo.setAttribute("onchange", function(){ValidateSize(this);});	   
	   nCampo.onchange  = function(){ValidateSize(this);};//'ValidateSize(this)';
	   
	   //Creo el link para Eliminar
	   a 				= document.createElement('a');
	   a.name 			= nDiv.id;
	   a.href 			= '#/';
	   a.onclick 		= elimCamp;
	   a.innerHTML 		= 'Eliminar';  
	   
	   //Agrego el input file y el link al nuevo div
	   nDiv.appendChild(newlabel);
	   nDiv.appendChild(nCampo);
	   nDiv.appendChild(a);
	   
	   //Agrego el nuevo div al container div adjuntos
	   container		 = document.getElementById('adjuntos_OA1');
	   container.appendChild(nDiv);
	}

	
	var numero_inmuebles = 0;
	var numero_inmuebles_cg = 0;
	var numero_inmuebles_tp = 0;
	addCampo_inmuebles = function () { 
	
	  //Creo un div dentro del cul iran los nuevos elmentos
	   nDiv 			= document.createElement('div');
	   nDiv.className 	= 'control-group';
	   nDiv.id 			= 'file_inmuebles' + (++numero_inmuebles);
	   
	   //Obtengo tipo de archivo a subir
	   var tipo = $( "#inmueble_tipo option:selected" ).val();
	   var nombre = "";
	   var id_files = "";
	   if(tipo==1){
		   nombre = "Tarjeta de propiedad";
		   id_files = "tp";
		   numero_inmuebles = numero_inmuebles_tp;
		   numero_inmuebles_tp++;
	   }else if(tipo==2){
		   nombre = "Certificado de gravámenes";
		   id_files = "cg";
		   numero_inmuebles = numero_inmuebles_cg;
		   numero_inmuebles_cg++;
	   }
	  
	  //Creo un label
	   newlabel = document.createElement("Label");	
	   newlabel.className = 'control-label';
	   newlabel.innerHTML = nombre;
	  
	   //Creo el input file
	   nCampo 			= document.createElement('input');
	   nCampo.name 		= 'archivos_inmuebles_'+id_files+'[]';
	   nCampo.id 		= 'archivos_inmuebles_'+id_files+"_"+numero_inmuebles;
	   nCampo.type 		= 'file';
	   nCampo.size 		= '30';
	   nCampo.accept	= 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	   //nCampo.setAttribute("onchange", function(){ValidateSize(this);});	   
	   nCampo.onchange  = function(){ValidateSize(this);};//'ValidateSize(this)';
	   
	   //Creo el link para Eliminar
	   a 				= document.createElement('a');
	   a.name 			= nDiv.id;
	   a.href 			= '#/';
	   a.onclick 		= elimCamp;
	   a.innerHTML 		= 'Eliminar';  
	   
	   //Agrego el input file y el link al nuevo div
	   nDiv.appendChild(newlabel);
	   nDiv.appendChild(nCampo);
	   nDiv.appendChild(a);
	   
	   //Agrego el nuevo div al container div adjuntos
	   container		 = document.getElementById('adjuntos_inmuebles');
	   container.appendChild(nDiv);

	}
	
	function ValidateSize(file) {
        /*var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 20) {
            alert('Validacion: El tamaño del archivo excede los 20MB');
           $(file).val(''); //for clearing with Jquery
        } */
    }
	
	$( "#tipo_contrato" ).change(function() {
		var tipoContratoSelected = $( "#tipo_contrato option:selected" ).val();
		if(tipoContratoSelected == "14"){
			$("#tr_tipocontrato_otrodesc").css("display", "table-row");
		}else{
			$("#tr_tipocontrato_otrodesc").css("display", "none");
			$("#tipocontrato_otrosdesc").val("");
		}
		  
	});
	
	function handle_personal_tercero(){
		var ischeked =  $('#lugar_entrega_personal_tercero').is(':checked');
		
		$("#lugar_entrega_personal_tercero_numero").val("");
		$("#lugar_entrega_personal_tercero_dias").val("");
		$("#lugar_entrega_personal_tercero_equipo").val("");
		
		if(ischeked){
			$("#lugar_entrega_personal_tercero_tr").css("display", "table-row");
		}else{
			$("#lugar_entrega_personal_tercero_tr").css("display", "none");
		}
	}
	
	function habilitar_subir_documento_montos(){
		var ischeked =  $('#contraprestacion_incdocumento').is(':checked');
		
		if(ischeked){
			$("#contraprestacion_monto").val("");
			$("#contraprestacion_medida").val(0);
			$("#contraprestacion_monto").prop( "disabled", true );
			$("#contraprestacion_medida").prop( "disabled", true );
			$("#contraprestacion_medida").css("background-color", "#eee");
			$("#contraprestacion_file_tr").css("display", "table-row");
		}else{
			$("#contraprestacion_monto").prop( "disabled", false );
			$("#contraprestacion_medida").prop( "disabled", false );
			$("#contraprestacion_medida").css("background-color", "white");
			$("#contraprestacion_file_tr").css("display", "none");
		}
	}
	
	function habilitar_cotizacion_contratovinculado(){
		
		var ischeked =  $('#cotizacion_bynrocontrato').is(':checked');
		
		if(ischeked){				
			$("#label_numero_cotizacion").html("*Nro Contrato:");
		}else{				
			$("#label_numero_cotizacion").html("*Nro Cotización:");
		}
		
		$("#tipo_contrato_cotizacion").val("");
		$("#tipo_contrato_cotizacion").focus();
	}
	
	function soloNumerosEnterosPositivos(e){
		var key = window.Event ? e.which : e.keyCode
		return ((key >= 48 && key <= 57) || (key==8))
	}
	
	function soloNumeros(e){
		var value = $("#"+event.target.id).val();		
		var key = window.Event ? e.which : e.keyCode
		
		if(key==46 && value.indexOf(".") != -1)return false;
		
		return ((key >= 48 && key <= 57) || (key==8)  || (key==46))
	}

	function isValidDate(dateString) {
		var regEx = /^(\d{4})(\/)(\d{2})(\/)(\d{2})$/;
		return dateString.match(regEx) != null;
	}
		
	$(document).ready(function() {
                   $('.date-picker').datepicker();	
				   $(".chosen-select").chosen();
    });

	function delete_document(campoName, fileName){

		if(!confirm("¿Seguro que desea eliminar el item?")){
			return false;
		}

		var parametros = {
			"idContrato" : <?=isset($_GET['id'])?$_GET['id']:0?>,
			"cod" : 12,
			"campo" : campoName,
			"codigo_contrato" : '<?=$data_contract['datosgenerales_codigo']?>',
			"name_file": fileName
		};

		$.ajax({
			data:  parametros,
			url:   '../phps/dcontract_ajax.php',
			type:  'post',
			dataType: "html",
			beforeSend: function (repuesta) {
				// lo que se hace mientras llega
				openModal();
			},
			success: function(respuesta){
				respuesta = $.parseJSON( respuesta );

				if(respuesta.estado == "1"){

					closeModal();
					$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
					setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

				}else{

					closeModal();
					$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
					alert(respuesta.error);
					location.reload();
				}
			},
			error: function(respuesta){
				closeModal();alert("Error al conectar con el servidor");
			},
			failure: function(respuesta){
				closeModal();alert("Error al conectar con el servidor");
			}
		});
	}

	function delete_document_detalle(idDetalle, fileName, option){

		if(!confirm("¿Seguro que desea eliminar el item?")){
			return false;
		}

		var parametros = {
			"idContrato" : <?=isset($_GET['id'])?$_GET['id']:0?>,
			"cod" : 13,
			"idDetalle" : idDetalle,
			"codigo_contrato" : '<?=$data_contract['datosgenerales_codigo']?>',
			"name_file" : fileName,
            "option" : option
		};

		$.ajax({
			data:  parametros,
			url:   '../phps/dcontract_ajax.php',
			type:  'post',
			dataType: "html",
			beforeSend: function (repuesta) {
				// lo que se hace mientras llega
				openModal();
			},
			success: function(respuesta){
				respuesta = $.parseJSON( respuesta );

				if(respuesta.estado == "1"){

					closeModal();
					$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
					setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

				}else{

					closeModal();
					$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
					alert(respuesta.error);
					location.reload();
				}
			},
			error: function(respuesta){
				closeModal();alert("Error al conectar con el servidor");
			},
			failure: function(respuesta){
				closeModal();alert("Error al conectar con el servidor");
			}
		});
	}

	function validateAdjuntosEliminables(onlyValidateFilesAlreadyUploaded=false){
        var proveedor_tipo = $('input:radio[name=proveedor_tipo]:checked').val();
        if(typeof proveedor_tipo === "undefined"){
            console.log("error on proveedor_tipo == undefined");
            alert("Validación: Documentos del proveedor no encontrados.");
            return false;
        }else if(proveedor_tipo == 1){//Persona Juridica
            var archivo1 = getValorArchivoProveedor("proveedor_jur_file_ficharuc", onlyValidateFilesAlreadyUploaded);
            var archivo2 = getValorArchivoProveedor("proveedor_jur_file_represetante", onlyValidateFilesAlreadyUploaded);
            var archivo3 = getValorArchivoProveedor("proveedor_jur_file_vigenciapoder", onlyValidateFilesAlreadyUploaded);
            if(archivo1 == "" || archivo2 == "" || archivo3 == ""){
                console.log("error on proveedor_tipo == 1. ["+archivo1+","+archivo2+","+archivo3+"]");
                alert("Validación: Documentos del proveedor no encontrados.");
                return false;
            }
        }else if(proveedor_tipo == 2){//Persona Natural
            var archivo1 = getValorArchivoProveedor("proveedor_nat_file_ficharuc", onlyValidateFilesAlreadyUploaded);
            var archivo2 = getValorArchivoProveedor("proveedor_nat_file_represetante", onlyValidateFilesAlreadyUploaded);
            if(archivo1 == "" || archivo2 == ""){
                console.log("error on proveedor_tipo == 2. ["+archivo1+","+archivo2+"]");
                alert("Validación: Documentos del proveedor no encontrados.");
                return false;
            }
        }


        var contraprestacion_incdocumento = $('#contraprestacion_incdocumento').is(':checked');
        var contraprestacion_adjuntado = getValorArchivoProveedor("contraprestacion_file", onlyValidateFilesAlreadyUploaded);
        if(contraprestacion_incdocumento && contraprestacion_adjuntado == ""){
            alert("Validación: Debe seleccionar archivo con montos de contraprestación");
            return false;
        }

        return true;
	}

    function getValorArchivoProveedor(id_element, onlyValidateFilesAlreadyUploaded = false){

        var archivo_from_input = "";
        if(!onlyValidateFilesAlreadyUploaded){
            archivo_from_input = $("#"+id_element).val();
        }

        if(archivo_from_input == ""){
            try {
                return document.getElementById("p_"+id_element).innerHTML;
            }catch (e){
                console.log("Error getValorArchivoProveedor for " + id_element + ". Not found.");
                return "";
            }

        }else{
            return archivo_from_input;
        }
    }
	
</script>

</body>
</html>
