	<?php
	
	$role_from = "";
	if( isset($_GET['role']) && $_GET['role'] == "jefe" ){
		$role_from = "Responsable área";
	}else if( isset($_GET['role']) && $_GET['role'] == "logistica" ){
		$role_from = "Responsable logística";
	}else if($UsuarioFlow){
		$role_from = "Usuario";
	}else if( isset($_GET['role']) && $_GET['role'] == "legal" ){
		$role_from = "Legal";
	}else if( !isset($_GET['role'])){
		$_GET['role'] = "";
	}	
	
	$file_new_movimiento_label  = "Archivo:";
    $isContratoDerivadoOpen = false;
    $isContratoDerivadoAMiUsuario = false;
    $isContratoDerivadoPorMiUsuario = false;
    $estadoDerivacion = 0;

	if( ($estado == 1 || $estado == 3 )&& $noUserFlow && $_GET['role'] == "legal"){
		$displayFile_new_movimiento = true;		
		$file_new_movimiento_label  = "*Contrato:";
        $isContratoDerivadoOpen = isContratoDerivadoOpen($_GET['id']);
        $isContratoDerivadoAMiUsuario = isContratoDerivadoAMiUsuario($_GET['id'], $_SESSION['id']);
        $isContratoDerivadoPorMiUsuario =  isContratoDerivadoPorMiUsuario($_GET['id'], $_SESSION['id']);
        $estadoDerivacion = getEstadoDerivacion($_GET['id']);//Solo puede haber una derivación activa por contrato a la vez
	}else if( ($estado == 0.3 || $estado == 2 )&& $_GET['role'] == "jefe" ){
		$displayFile_new_movimiento = true;		
	}else if( ($estado == 0.6 || $estado == 2 )&& $_GET['role'] == "logistica" ){
		$displayFile_new_movimiento = true;		
	}
    if($legal_advance_options){
        $role_from = "Legal";
    }
	?>
	<form class="form-horizontal" id="validation-form_nuevo_3" method="post" <?=$displayFile_new_movimiento?"enctype='multipart/form-data'":""?> novalidate="novalidate" autocomplete="off">				
				<div class="row-fluid">
				<h5 class="header smaller lighter blue">					
					<span class="label label-success"><?=$role_from?></span>	

					<span class="label label-warning arrowed" style="float: right">Proceso</span> 
					
				</h5>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
							<label class="control-label" for="contrato_file1_obs2">Comentario:</label>
							<div class="control-group">
								<textarea class="span6" name="contrato_file1_obs2" id="contrato_file1_obs2" rows="2" cols="50"></textarea>
							</div>
						</td>
					</tr>
					
					<?php 
						if( $displayFile_new_movimiento ){	
					?>
					<tr>
						<td>
							<label class="control-label" for="alcance_contrato"><?=$file_new_movimiento_label?></label>
							<div class="control-group">								
							<input  type="file" id="contrato_file1" name="archivos[]" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" onchange="ValidateSize(this)" value=""/>							
							</div>																								
						</td>
					</tr>
					<?php }?>
					
					<?php if( $estado == 0.3 && $_GET['role'] == "jefe" ){ ?>
					<?php
						$mov_nuevo_estado = $TIPO_FLUJO_CONTRATO==$TIPO_USUARIO_NO_COMPRADOR ? 0.8 : 0.6;
					?>
					<tr>
						<td align="center">
						<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,<?=$mov_nuevo_estado?>)">
						  <i class="icon-thumbs-up icon-white"></i> Aprobar
						</button>
						<button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,0)">
						  <i class="icon-thumbs-down icon-white"></i> Observar
						</button>
						
						</td>
					</tr>
					<?php }?>
					
					<?php if( $estado == 0.6 && $_GET['role'] == "logistica" ){ ?>
					<tr>
						<td align="center">
						<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,0.8)">
						  <i class="icon-thumbs-up icon-white"></i> Aprobar
						</button>
						<button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,0)">
						  <i class="icon-thumbs-down icon-white"></i> Observar
						</button>
						
						</td>
					</tr>
					<?php }?>

					<?php if( $estado == 0.8 && $_GET['role'] == "legal" ){ ?>
						<tr>
							<td align="center">
								<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,1)">
									<i class="icon-thumbs-up icon-white"></i> Aprobar
								</button>
								<button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,0)">
									<i class="icon-thumbs-down icon-white"></i> Observar
								</button>

							</td>
						</tr>
					<?php }?>
					
					<?php if( $estado == 1 && $noUserFlow && $_GET['role'] == "legal" && !$isContratoDerivadoOpen){ ?>
                    <tr>
                       <td>
                         <label class="control-label" for="alcance_contrato">Agregar archivo:</label>
                         <div class="control-group">
                             <a href="#" onClick="addCampo()">
                                 <img src="../assets/images/plusAudio.png" border="0"/>
                             </a>
                         </div>
                       </td>
                    </tr>
                    <tr>
                       <td>
                           <div id="adjuntos"></div>
                       </td>
                    </tr>
					<tr>
						<td align="center">

                            <button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,2)">
                                <i class="icon-thumbs-up icon-white"></i> Subir contrato
                            </button>
                            <button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,3)">
                                <i class="icon-thumbs-up icon-white"></i> Subir contrato y mover a recolectar firmas
                            </button>

						<?php if($movimientos[0]['estado'] == 2){ ?>
							<button type="button" class="btn btn-danger"  onclick="anular_solicitud(<?=$_GET['id']?>)">
							  <i class="icon-thumbs-down icon-white"></i> Anular solicitud
							</button>
							<button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,3)">
							  <i class="icon-thumbs-down icon-white"></i> Rechazar observación
							</button>
						<?php }else{ ?>
							<button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,0)">
							  <i class="icon-thumbs-down icon-white"></i> Observar
							</button>
						<?php }?>
						</td>
					</tr>
					<?php }?>

                    <?php if( $estado == 1 && $noUserFlow && $_GET['role'] == "legal" && $isContratoDerivadoOpen){ ?>
                        <tr>
                            <td>
                                <label class="control-label" for="alcance_contrato">Agregar archivo:</label>
                                <div class="control-group">
                                    <a href="#" onClick="addCampo()">
                                        <img src="../assets/images/plusAudio.png" border="0"/>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="adjuntos"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">

                                <?php if($isContratoDerivadoAMiUsuario && $estadoDerivacion == 0){ ?>
                                    <button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,1,0,1)">
                                        <i class="icon-thumbs-up icon-white"></i> Subir contrato y solicitar validación
                                    </button>
                                <?php }?>

                                <?php if($isContratoDerivadoPorMiUsuario && $estadoDerivacion == 1){ ?>
                                    <button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,2,0,2)">
                                        <i class="icon-thumbs-up icon-white"></i> Aprobar y finalizar derivación
                                    </button>
                                    <button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,1,0,0)">
                                        <i class="icon-thumbs-down icon-white"></i> Observar derivación
                                    </button>
                                <?php }?>

                            </td>
                        </tr>
                    <?php }?>

					<?php if( $estado == 2 && ( $_GET['role'] == "jefe" || $_GET['role'] == "logistica" ) ){ ?>
					<?php
						$mov_next_move = 2;
						if( $TIPO_FLUJO_CONTRATO == $TIPO_USUARIO_NO_COMPRADOR)
							$mov_next_move = 3;
						else if( $TIPO_FLUJO_CONTRATO == $TIPO_USUARIO_COMPRADOR && $_GET['role'] == "jefe" ){//es responsable area
								if( $data_contract['flag_has_last_approved_logistica'] == 1 ){
									$mov_next_move = 3;
								}						
						}else if( $TIPO_FLUJO_CONTRATO == $TIPO_USUARIO_COMPRADOR && $_GET['role'] == "logistica" ){
								if( $data_contract['flag_has_last_approved_usuario'] == 1 ){
									$mov_next_move = 3;
								}
						}
					?>
					<tr>
						<td align="center">
						<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,<?=$mov_next_move?>)">
						  <i class="icon-thumbs-up icon-white"></i> Aprobar
						</button>
						<button type="button" class="btn btn-danger"  onclick="send_data(<?=$_GET['id']?>,1)">
						  <i class="icon-thumbs-down icon-white"></i> Observar
						</button>
						</td>
					</tr>
					<?php }?>
					
					<?php if( $estado == 3 && $noUserFlow && $_GET['role'] == "legal"){ ?>
					<tr>
						<td>
						<label class="control-label" for="alcance_contrato">Agregar archivo:</label>
							<div class="control-group">								
							<a href="#" onClick="addCampo()">
								<img src="../assets/images/plusAudio.png" border="0"/>
							</a>
							</div>							
						</td>
					</tr>
					<tr>
						<td>
							<div id="adjuntos"></div>
						</td>
					</tr>
					<tr>
						<td align="center">
						<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,4)">
						  <i class="icon-thumbs-up icon-white"></i> Subir contrato final
						</button>
						
						<?php if(empty($data_contract['waitfirmamodo'])){ ?>
						<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,3,1)">
						  <i class="icon-thumbs-up icon-white"></i> Subir firma proveedor
						</button>
						<button type="button" class="btn btn-success" onclick="send_data(<?=$_GET['id']?>,3,2)">
						  <i class="icon-thumbs-up icon-white"></i> Subir firma representante legal
						</button>
						<?php } ?>
						
						<button type="button" class="btn btn-danger" onclick="send_data(<?=$_GET['id']?>,1)">
						  <i class="icon-thumbs-down icon-white"></i> Mover a elaboración Legal
						</button>

                         <button type="button" class="btn btn-danger" onclick="send_data(<?=$_GET['id']?>,0)">
                          <i class="icon-thumbs-down icon-white"></i> Devolver a Usuario creador
                         </button>
						
						</td>
					</tr>
					<?php }?>

                    <?php if( $legal_advance_options ){ ?>
                    <tr>
                        <td align="center">
                        <button type="button" class="btn btn-danger" onclick="send_data(<?=$_GET['id']?>,3)">
                            <i class="icon-thumbs-down icon-white"></i> Mover a ESPERA DE FIRMAS
                        </button>
                        </td>
                    </tr>

                    <?php } ?>
					
				</table>
				</div>
	</form>