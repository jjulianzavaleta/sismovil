	<?php
	
	$tiplo_flow_des = getFlowDescMovimiento($movimiento['title']);
	
	?>
	
	
	<form class="form-horizontal" id="validation-form_nuevo_2" method="post" novalidate="novalidate" autocomplete="off">				
				<div class="row-fluid">				
				<h5 class="header smaller lighter blue">					
					<span class="label label-success"><?=$tiplo_flow_des?></span>
					<span class="label label-success"><?=$movimiento['usuarioname']?></span> -
					<span class="label label-success"><?=$movimiento['title']?></span>										
					
					<span class="label label-success" style="float: right"><?=date_format($movimiento['fecha_registra'],"d/m/Y H:i:s")?></span>
					<?php if($movimiento['cerrado'] == 1){ ?>
						<span class="label label-success arrowed" style="float: right">Hecho</span> 						
					<?php }else{ ?>
						<span class="label label-warning arrowed" style="float: right">Obervado</span> 
					<?php } ?>									
					
				</h5>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
							<label class="control-label">Comentario:</label>
							<div class="control-group">
								<textarea class="span6" rows="2" cols="50" readOnly='readOnly'><?=$movimiento['observacion']?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td>
						<?php
							$archivos = getArchivosContractByMovimiento($movimiento['idmovimiento']);
							
							foreach($archivos as $archivo){
								?>
								<label class="control-label" for="archivo_<?=$archivo['idarchivo']?>">Archivo adjunto:</label>
								<div class="control-group">
									<a href="#" id="archivo_<?=$archivo['idarchivo']?>" onclick="ver_contrato('<?=$archivo['url']?>')">
										<img src="../assets/images/<?=getIconToDiplay($archivo['url'])?>" width="40" height="40" title="Ver PDF">
									</a>
								</div>
								<?php								
							}
							?>
						</td>
					</tr>
				</table>
				</div>
				</form>