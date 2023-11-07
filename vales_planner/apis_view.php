<div class="row-fluid">
					<h5 class="header smaller lighter blue">Localización</h5>						
						
						<?php  if( isset($data_vale['consumo_idusuario']) && !empty($data_vale['consumo_idusuario']) ){ ?>
						<a  alt="Mapa" title="Mapa" href='http://maps.google.com/?q=<?=$data_vale['consumo_gps_latitude'].",".$data_vale['consumo_gps_longitude']?>'  target=\"_blank\" class="btn btn-mini btn-info">
                                <i class="icon-map-marker bigger-120"></i></a>
						<?php } ?>
						
				</div>
				
				<div class="row-fluid">
					<h5 class="header smaller lighter blue">Foto Comprobante</h5>						
						
						<?php if( !empty($data_vale['consumo_idusuario']) ){ 
						
						     $contador=1;
						     foreach($data_detalle1 as $item){
								 
								 if( !empty($item['voucher_img']) ){
									echo getMaterialNameFromMaterial($item['matnr'])." (".$item['voucher_nro'].") ";
									echo '<a href="#" onclick="show_imagen_popup(&quot;'.$item['voucher_img'].'&quot;)">Ver imagen</a><br>';								  
									$contador++; 
								 }								  
							 }
							 
						 }else{ 
						   echo '<img src="smiley.gif" alt="Comprobante" height="42" width="42">';
						} ?>
				</div>
				
				<div class="row-fluid">
					<h5 class="header smaller lighter blue">Foto Comprobante extras</h5>						
						
						<?php if( !empty($data_vale['consumo_idusuario']) ){ 						
						     
							 $img_consumo_extras = get_img_consumo_extras($id_vale);
							 if( !empty($img_consumo_extras) ){
								 foreach($img_consumo_extras as $item){								 
									 if( !empty($item['voucher_img']) ){
										echo getMaterialNameFromValeDetalleProducto($item['matnr']);
										echo '<a href="#" onclick="show_imagen_popup(&quot;'.$item['voucher_img'].'&quot;)">Ver imagen</a><br>';						  
									 }								  
								 }
							 }else{ 
							   echo 'Ninguna';
							} 
							 
						 }else{ 
						   echo 'Ninguna';
						} ?>
				</div>
				
				<div class="row-fluid">
					<h5 class="header smaller lighter blue">TSOMobile API</h5>
						
						<?php if( $data_vale['tsomobile_somethingwentwrong'] == 1 ){ 						
						     echo '<span class="label label-warning">Ocurrio un error al obtener el kilometraje de TSOMobile API</span>';							 
						 }else if( !empty($data_vale['tsomobile_kilometraje']) || $data_vale['tsomobile_kilometraje'] == "0" ){ 
						   echo '<span class="label label-info">Kilometraje: '.$data_vale['tsomobile_kilometraje'].' Km</span>';						 
						}else{
							echo '<span class="label">Sin data para mostrar</span>';
						} ?>
					<br><small><a href="#" onclick="tsomobile_verlog(<?=$id_vale?>)">Ver logs</a></small>
				</div>
				
				<div class="row-fluid">
					<h5 class="header smaller lighter blue">RFC Consumo vales</h5>
						
						<?php if( $data_vale['rfcconsumo_somethingwentwrong'] == 1 ){ 						
						     echo '<span class="label label-warning">Ocurrio un error al enviar el consumo por ZMMRFC_IFCU_CONSUMO_APP</span>';							 
						 }else if( $data_vale['rfcconsumo_somethingwentwrong'] == "0" ){ 
						   echo '<span class="label label-info">Exito al enviar el vale por ZMMRFC_IFCU_CONSUMO_APP</span>';						 
						}else{
							echo '<span class="label">Sin data para mostrar</span>';
						} ?>
					<br><small><a href="#" onclick="rfcconsumo_verlog(<?=$id_vale?>)">Ver logs</a></small>
				</div>