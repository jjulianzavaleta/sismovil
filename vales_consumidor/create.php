<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../plantilla1.php");
include_once("../phps/conexion.php");
include_once("../phps/dvales_create.php");
include_once("../phps/dvales_consumidor.php");

$display_data       = false;
$id_vale            = 0;

/*----  GET DATA FOR COMBOBOXES ----*/
$equiposWeb         = getAllEquiposWebForCombobox();
$centroCostoWeb     = getAllCentroCostoWebForCombobox();
$grifos             = getAllGrifosForCombobox_flujo1();
$materiales         = getAllMaterialForCombobox();
$tractor_implementos = getTractorImplementosCombobox();
$tractor_promedios   = getTractorPromedioCombobox();
$medidas			= array( array("id" => 0, "name" => "Kilometraje"), array("id" => 1, "name" => "Odometro") );
$data_vale          = array();
$data_vale['estado']  = 1;
$data_vale['anulado'] = 0;
$data_vale['tractor_implemento']             = "";
$data_vale['tractor_promedio']               = "";
$read_only = false;
$max_img_extras = getMaxNumImagenesExtras();

/*----  LOGIC FOR EDIT MODE ----*/
if( isset($_GET['id']) && is_numeric($_GET['id']) ){
	$display_data  = true;
	$id_vale       = $_GET['id'];
	$data_vale     = getDataVale($id_vale);
	$data_detalle1 = getDataDetalle1($id_vale);
	$data_detalle2 = getDataDetalle2($id_vale);
	
	$data_vale['kostl'] 		= $data_detalle2[0]['kostl'];
	$data_vale['material'] 		= $data_detalle1[0]['matnr'];
	$data_vale['voucher_img'] 	= $data_detalle1[0]['voucher_img'];
	$data_vale['consumo']		= $data_detalle1[0]['menge_chofer'];
	
	if($data_vale['anulado'] == 1 || $data_vale['estado'] == 2 || $data_vale['estado'] == 3){
		$read_only = true;		
	}
}

array_unshift($equiposWeb,array("id" => 0, "equnr" => "--Seleccione--"));
array_unshift($centroCostoWeb,array("id" => 0, "ktext" => "--Seleccione--"));
array_unshift($grifos,array("id" => 0, "nombre" => "--Seleccione--"));
array_unshift($materiales,array("id" => 0, "nombre" => "--Seleccione--"));
array_unshift($medidas,array("id" => -1, "name" => "--Seleccione--"));

?>
<script src="../media/js/chosen.jquery.min.js"></script>
<link href="../media/css/chosen.min.css" rel="stylesheet"/>

<div class="container">			
			<div class="clearfix">
				<div class="row-fluid">
					<form class="form-horizontal" id="validation-form_nuevo" method="post" novalidate="novalidate" autocomplete="off" enctype='multipart/form-data'>
					  
					  <?php if( $display_data ){ ?>
					  
					  <?php
						if( $display_data ){
							if( $data_vale['anulado'] == 1 ){
								include("../vales_planner/anulado_view.php");
							}
						}
					  ?>
						<div class="row-fluid">
								<table border="0"  width="100%" align="center">
									<tr align="center">	
										<td>
										  <span class="label label-info">Fecha Registra: <?=date_format($data_vale['fecha_registra'], 'Y-m-d  H:i:s')?></span>
										  <span class="label label-info">Usuario Registra: <?=getNameConsumidorFromChoferes($data_vale['usuario_registra'])?></span>
										  
										  <?php if( !empty($data_vale['fecha_modifica']) ){ ?>
										  <span class="label label-warning">Fecha Última Modificación: <?=date_format($data_vale['fecha_modifica'], 'Y-m-d  H:i:s')?></span>
										  <?php } ?>
										  
										  <?php if( !empty($data_vale['usuario_modifica']) ){ ?>
										  <span class="label label-warning">Usuario Última Modificación: <?=getNameConsumidorFromChoferes($data_vale['usuario_modifica'])?></span>
										  <?php } ?>
										  
										  <?php if( !empty($data_vale['fecha_emite']) ){ ?>
										  <span class="label label-success">Fecha Emite: <?=date_format($data_vale['fecha_emite'], 'Y-m-d  H:i:s')?></span>
										  <?php } ?>
										  
										  <?php if( !empty($data_vale['usuario_emite']) ){ ?>
										  <span class="label label-success">Usuario Emite: <?=getNameConsumidorFromChoferes($data_vale['usuario_emite'])?></span>
										  <?php } ?>
										  
										  <?php  if( $data_vale['estado'] == 3 && isset($data_vale['consumo_idusuario']) && !empty($data_vale['consumo_idusuario']) ){ ?>
										  <span class="label label-info">Fecha Consumido: <?=date_format($data_vale['consumo_fechaconsumo'], 'Y-m-d  H:i:s')?></span>
										  <span class="label label-info">Usuario Consumió: <?=getChoferNameFromID($data_vale['consumo_idusuario'])?></span>									  
										  <span class="label label-info">GPS: <?=($data_vale['consumo_gps_latitude'].",".$data_vale['consumo_gps_longitude'])?></span>	
										  <?php } ?>
										  
										</td>
									</tr>
								</table>
						</div>	
					  <?php } ?>
					  
					  <input name="gps_lat" id="gps_lat" type="hidden" value="0">
					  <input name="gps_lon" id="gps_lon" type="hidden" value="0">
					  
						<div class="row-fluid">
							<h5 class="header smaller lighter blue">Datos Cabecera</h5>
						
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							
							<tr>
								<td width="50">
									<label class="control-label" for="consumidor">Consumidor:</label>
									<div class="control-group">
										<input type="text" class="span6" name="consumidor" id="consumidor" 
											   value="<?=$display_data===true?$data_vale['consumo_idusuario']:$_SESSION['username']?>" readOnly/>									 
									</div>
								</td>
								
								<td  width="50%">								   
									<label class="control-label" for="fecha_consumo">Fecha Consumo:</label>
									<div class="control-group">																
										<input class="span6 date-picker" name="fecha_consumo" id="fecha_consumo" type="text"
											   data-date-format="yyyy-mm-dd" readOnly <?=$read_only?"disabled='disabled'":""?>
											   value="<?=$display_data===true?date_format($data_vale['consumo_fechaconsumo'],"Y-m-d"):date("Y-m-d")?>"/>
									</div>
								</td>
							</tr>
							
							<tr>
								<td  width="50%">
									<label class="control-label" for="equipo">Equipo:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="equipo" id="equipo" onchange="load_centrocsoto_by_equipo();evalute_show_implemento();" <?=$read_only?"disabled='disabled'":""?>>
											<?php
												foreach($equiposWeb as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['equnr'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}	
													$extra_data = !empty($item['license_num'])?(" (".$item['license_num'].")"):"";
													
													echo "<option value='".$item['id']."' ".$selected.">".($item['equnr'].$extra_data)."</option>";
													
												}
											?>											
										</select>
									</div>
								</td>
								
								<td  width="50%">
									<label class="control-label" for="centrocosto">Centro Costo:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="centrocosto" id="centrocosto" <?=$read_only?"disabled='disabled'":""?>>
											<?php
												foreach($centroCostoWeb as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['kostl'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}

													$extra_data = !empty($item['kostl'])?(" (".$item['kostl'].")"):"";
													
													echo "<option value='".$item['id']."' ".$selected.">".($item['ktext'].$extra_data)."</option>";
													
												}
											?>											
										</select>
									</div>
								</td>
															
								
							</tr>
							
							<tr>
								<td width="50%"> 
									<label class="control-label" for="grifo">Grifo:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="grifo" id="grifo" <?=$read_only?"disabled='disabled'":""?> >
											<?php
												foreach($grifos as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['grifo'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}
													$extra_data = !empty($item['descripcion'])?(" (".$item['descripcion'].")"):"";
													
													echo "<option value='".$item['id']."' ".$selected.">".($item['nombre'].$extra_data)."</option>";
												}
											?>												
										</select>
									</div>
								</td>								
							</tr>
							
							<tr>
								<td width="50%"> 
									<label class="control-label" for="material">Material:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="material" id="material" <?=$read_only?"disabled='disabled'":""?> >
											<?php
												foreach($materiales as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['material'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}												
													
													echo "<option value='".$item['id']."' ".$selected.">".$item['nombre']."</option>";
												}
											?>												
										</select>
									</div>
								</td>
								<td width="50">
									<label class="control-label" for="consumo">Cantidad Consumida:</label>
									<div class="control-group">
										<input type="text" class="span6" name="consumo" id="consumo" onkeypress='validate_number(event)'
											   value="<?=$display_data===true?$data_vale['consumo']:""?>" <?=$read_only?"readOnly":""?>/>									 
									</div>
								</td>
							</tr>
							
							<tr>
								<td width="50%"> 
									<label class="control-label" for="medidacontador">Medida contador:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="medidacontador" id="medidacontador" <?=$read_only?"disabled='disabled'":""?> >
											<?php
												foreach($medidas as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['consumo_unidadmedida'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}													
													
													echo "<option value='".$item['id']."' ".$selected.">".$item['name']."</option>";
												}
											?>												
										</select>
									</div>
								</td>
								<td width="50">
									<label class="control-label" for="contadorcantidad">Contador:</label>
									<div class="control-group">
										<input type="text" class="span6" name="contadorcantidad" id="contadorcantidad" onkeypress='validate_number(event)'
											   value="<?=$display_data===true?$data_vale['kilom']:""?>" <?=$read_only?"readOnly":""?>/>									 
									</div>
								</td>
							</tr>
                                <tr id="tr_tractores_extras" <?=empty($data_vale['tractor_implemento'])?"style='display: none'":''?>>
                                    <td  width="50%">
                                        <label class="control-label" for="tractor_implemento">Implemento:</label>
                                        <div class="control-group">
                                            <select  class="span6 chosen-select" name="tractor_implemento" id="tractor_implemento" onchange="select_promedioSTD()" <?=$read_only?"disabled='disabled'":""?> >
                                                <option value="0">--Seleccione--</option>
                                                <?php
                                                $found_implemento = false;
                                                foreach ($tractor_implementos as $implemento){
                                                    $tractor_implemento_selected = "";
                                                    if($data_vale['tractor_implemento'] == $implemento['valor']){
                                                        $tractor_implemento_selected = "selected";
                                                        $found_implemento = true;
                                                    }
                                                    echo '<option value="'.$implemento['valor'].'"'.$tractor_implemento_selected.'>'.$implemento['valor'].'</option>';
                                                }
                                                if(!$found_implemento){
                                                    echo '<option value="'.$data_vale['tractor_implemento'].'" selected>'.$data_vale['tractor_implemento'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </td>

                                    <td width="50%">
                                        <label class="control-label" for="tractor_promedio">Promedio STD:</label>
                                        <div class="control-group">
                                            <select  class="span6 chosen-select" name="tractor_promedio" id="tractor_promedio" disabled='disabled' >
                                                <option value="0">--Seleccione--</option>
                                                <?php
                                                $found_promedio = false;
                                                foreach ($tractor_promedios as $promedio){
                                                    $tractor_promedio_selected = "";
                                                    if($data_vale['tractor_promedio'] == $promedio['valor']){
                                                        $tractor_promedio_selected = "selected";
                                                        $found_promedio = true;
                                                    }
                                                    echo '<option value="'.$promedio['valor'].'" '.$tractor_promedio_selected.'>'.$promedio['valor'].'</option>';
                                                }
                                                if(!$found_promedio){
                                                    echo '<option value="'.$data_vale['tractor_promedio'].'" selected>'.$data_vale['tractor_promedio'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
							
							<tr>
								<td width="50%">
									<label class="control-label" for="imagen">Imagen:</label>
									<div class="control-group">
										<?php if(!$display_data || $data_vale['estado'] == 2){ ?>
											<input type="file" class="span6"  id="imagen" name="imagen" accept="image/x-png,image/gif,image/jpeg" value="">
										<?php }else if(!empty($data_vale['voucher_img'])){
											echo '<a href="#" onclick="show_imagen_popup(&quot;'.$data_vale['voucher_img'].'&quot;)">Ver imagen</a><br>';		
										}else{
											echo "No cargada";
										}?>
									</div>
								</td>
								<td>
									<label class="control-label" for="comentario">Comentario:</label>
									<div class="control-group">
										<textarea class="span6" name="comentario" id="comentario" rows="2" cols="50" <?=$read_only?"readOnly":""?>><?=$display_data===true?$data_vale['consumo_observacion']:""?></textarea>
									</div>
								</td>
							</tr>
                                <?php if(!$read_only){ ?>
							<tr>
								<td  colspan="2"><label class="control-label" for="add_extra_imagen"></label>								
									<div class="control-group">
									<label class="control-label" for="">Agregar imagen:</label>
										<a href="#/" onClick="add_extra_imagen_file()" id="add_extra_imagen">
											<img src="../assets/images/plusAudio.png" border="0"/>
										</a>										
									</div>
									
								</td>
							</tr>
                                <?php } ?>
							
							</table>
						</div>	
						
						<?php  
							if( $data_vale['estado'] == 3 && isset($data_vale['consumo_idusuario']) && !empty($data_vale['consumo_idusuario']) ){
								 include('../vales_planner/apis_view.php');						
							} 
						?>
				</div>
					
				<div class="row-fluid"  >
							<div class="modal-footer">
										
								<?php if( ( $data_vale['estado'] == 1 || $data_vale['estado'] == 2 ) && $data_vale['anulado'] != 1){ ?>								
								<button type="button" class="btn btn-success" style= "float: left;position: relative;left: 50%;" onclick="send_data(1)">
									 <i class="icon-plane icon-white"></i> Consumir
								</button>
								<!--<button type="button" class="btn btn-info" style= "float: left;position: relative;left: 50%;" onclick="send_data(0)">
									 <i class="icon-plane icon-save"></i> Guardar
								</button>-->
								<?php } ?>
								
								<button type="button" class="btn btn-default" style= "float: left;position: relative;left: 50%;" data-dismiss="modal" onclick="go_back()">
									<i class="icon-remove"></i>Cancelar
								</button>
							</div>							
					</form>
				</div>
			</div>
</div>

<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
	<i class="icon-double-angle-up icon-only bigger-110"></i>
</a>

<script src="../assets/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="../assets/css/jquery.autocomplete.css" />

<script>    		
		
		$( '#dValesMain' ).addClass( "active" );
		$( '.activePlantilla1' ).html( "<a href='index.php'>Vales</a> > <a href='create.php'>Nuevo</a>");
		
		function send_data(emitir){

			var confirmacion = confirm("¿Confirma la accion?");
			
			if(confirmacion && validaciones_formulario()){
			}else{
				return false;
			}
			
			var idequipo      = $("#equipo option:selected").val();
			
			var formData = new FormData();
			formData.append("cod"	, 8);
			formData.append("idequipo"	, idequipo);
			
			$.ajax({
						data:  formData,
						url: "../phps/dvales_ajax.php",
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
									
									var validate_contador    = validate_contadores(respuesta.data.kilometraje);
									var validate_rendimiento = validate_rendimientos(respuesta.data.rendimiento_estandar, respuesta.data.kilometraje);
									
									if( validate_contador && validate_rendimiento ){
										create_vale(emitir);										
									}else{
										closeModal();									
									}
									
								}else{
									alert("Validación: No se pudo obtener contador para el equipo #"+idequipo );							  
									closeModal();
								}
							} catch (error) {
							  console.error(error);
							  alert("Error 08: Error al procesar validacion de contador" );							  
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
		
		function create_vale(emitir){
			
			var idvale        = <?=$id_vale?>;
			var fechaMaxconsumo = $("#fecha_consumo").val();
			var idequipo      = $("#equipo option:selected").val();
			var idcentrocosto = $("#centrocosto option:selected").val();
			var idgrifo       = $("#grifo option:selected").val();			
			var chofer        = <?=$_SESSION['id']?>;
			var chofer_aux    = 0;	
			var material      = $("#material option:selected").val();				
			var data_table1   = '[{"idmaterial":'+material+',"cantidad":0}]';
			var data_table2   = '[{"centrocosto":'+idcentrocosto+',"idmaterial":'+material+',"cantidad":0}]';
			var isTermoking   = "0";
			var modo_centrocosto = 2;

            var tractor_implemento = "";
            var tractor_promedio = "";
            if(isTractorAndRequiresImplemento(idequipo)){
                tractor_implemento = $("#tractor_implemento option:selected").val();
                tractor_promedio = $("#tractor_promedio option:selected").val();
            }
				
			var formData = new FormData();
			formData.append("cod"	, 1);
			formData.append("id"	, idvale);
			formData.append("emitir", emitir);
			formData.append("flujo" , 1);
			formData.append("a"		, idequipo);
			formData.append("b"		, idcentrocosto);
			formData.append("c"		, idgrifo);
			formData.append("d"		, fechaMaxconsumo);
			formData.append("e"		, <?=$_SESSION['id']?>);
			formData.append("f"		, data_table1);
			formData.append("g"		, data_table2);
			formData.append("h"		, modo_centrocosto);
			formData.append("i"		, chofer);
			formData.append("j"		, chofer_aux);
			formData.append("k"		, isTermoking);
            formData.append("l"		, tractor_implemento);
            formData.append("m"		, tractor_promedio);
			
			$.ajax({
						data:  formData,
						url: "../phps/dvales_ajax.php",
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

								if(respuesta.estado == "1" && emitir == 1){									
									var formDataConsumir = createFormData_consumir(respuesta);
									consumir_vale(formDataConsumir, respuesta.data.idvale);						
								}else if(respuesta.estado == "1"){
									closeModal(); 
									$().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
									setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);	
								}else{
									closeModal();
									$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
									alert(respuesta.error);
								}
							} catch (error) {
							  console.error(error);
							  alert("ERROR, los datos podrian no haberse guardado. Por favor revise la lista de vales antes de continuar:\n\n"+respuesta_temp);
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
		
		function consumir_vale(formDataConsumir, idvale){
			
			$.ajax({
						data:  formDataConsumir,
						url: "../webserviceREST/vales_consumirvale.php",
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

								if(respuesta.respuesta == "exito"){
									var iteration = 1;
									var max_iteration = 60;
									console.log("validate_rfc_response init time: "+new Date().toLocaleString());
									validate_rfc_response(idvale, iteration , max_iteration);							

								}else{

									closeModal();
									$().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
									alert(respuesta.error);
									//location.reload();
								}
							} catch (error) {
							  console.error(error);
							  alert("ERROR, los datos podrian no haberse guardado. Por favor revise la lista de vales antes de continuar:\n\n"+respuesta_temp);
							  closeModal();
							  setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);
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
		
		function validate_rfc_response(idvale, iteration, max_iteration){
			
			var formData = new FormData();
			formData.append("cod"	, 6);
			formData.append("idvale"	, idvale);
			formData.append("iteration"	, iteration);
			formData.append("max_iteration"	, max_iteration);
			
			$.ajax({
						data:  formData,
						url: "../phps/dvales_ajax.php",
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

								if(respuesta.estado == "1"){//encontro respuesta del RFC
								
									if(respuesta.data.rfcresponse.includes("exitoso")){
										closeModal(); 
										$().toastmessage('showSuccessToast', 'Exito');
										alert('Vale #'+idvale+' consumido correctamente.\n\nRFC Response: '+respuesta.data.rfcresponse);
                                        console.log("validate_rfc_response finish time: "+new Date().toLocaleString());
										setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);	
									}else{
										alert("Error 01: No se puedo consumir el vale #"+idvale+" .\n\nRFC Response: "+respuesta.data.rfcresponse);
										closeModal();
                                        console.log("validate_rfc_response finish time: "+new Date().toLocaleString());
										setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);	
									}				

								}else{//Aun no encuentra respuesta del RFC
									if( iteration <= max_iteration ){
                                        console.log("validate_rfc_response try number : "+iteration+" at "+new Date().toLocaleString());
										setTimeout(function(){ validate_rfc_response(idvale, iteration+1, max_iteration); }, 10000);
									}else{
                                        force_undo_consumovale(idvale);
										alert("Error 02 ("+iteration+"): Vale fue consumido en sismovil, pero no se puedo obtener respuesta del RFC. Por favor valide manualmente en SAP. Vale #"+idvale);
										closeModal();
                                        console.log("validate_rfc_response finish time: "+new Date().toLocaleString());
										//setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);
									}
									
								}
							} catch (error) {
							  console.error("validate_rfc_response: "+error);
							  alert("Error 03: No se puedo obtener respuesta del RFC. Vale #"+idvale+"\n\nResponse:"+respuesta_temp+"\n\n"+error);
							  console.log("validate_rfc_response finish time: "+new Date().toLocaleString());
							  force_undo_consumovale(idvale);
							  closeModal();
							  setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);
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
		
		function force_undo_consumovale(idvale){
			
			var formData = new FormData();
			formData.append("cod"	, 7);
			formData.append("idvale"	, idvale);
			
			$.ajax({
						data:  formData,
						url: "../phps/dvales_ajax.php",
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
									setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);											
								}else{
									alert("Error 04: No se puedo quitar el check de consumido al vale. Vale #"+idvale );							  
									closeModal();
								}
							} catch (error) {
							  console.error(error);
							  alert("Error 05: No se puedo quitar el check de consumido al vale. Vale #"+idvale );							  
							  closeModal();
							  setTimeout(function(){window.location = "index.php"; }, <?php echo $SLEEP_TIME ?>);
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
		
		function validaciones_formulario(){			
			
			var fecha_consumo = $("#fecha_consumo").val();			
			var idequipo      = $("#equipo option:selected").val();	
			var idcentrocosto = $("#centrocosto option:selected").val();
			var idgrifo       = $("#grifo option:selected").val();
			var material      = $("#material option:selected").val();	
			var consumo		  = $("#consumo").val();	
			var medidacontador= $("#medidacontador option:selected").val();
			var contador	  = $("#contadorcantidad").val();
			var adjunto 	  =	$("#imagen").val();
			var comentario	  = $("#comentario").val();	
			var hasImagenPrimaria = false;
			var hasImagenExtras = false;
			
			if(fecha_consumo == "" || fecha_consumo == null){
				alert("Validación: Debe ingresar Fecha de Consumo.");
				$("#fecha_consumo").focus();
				return false;
			}else if(idequipo == 0 || idequipo == null){
				alert("Validación: Debe seleccionar Equipo.");
				$("#equipo").focus();
				return false;
			}else if( idcentrocosto == 0 || idcentrocosto == null ){
				alert("Validación: Debe seleccionar Centro de Costo.");
				$("#centrocosto").focus();
				return false;
			}else if(idgrifo == 0 || idgrifo == null){
				alert("Validación: Debe seleccionar Grifo.");
				$("#grifo").focus();
				return false;
			}else if(material == 0 || material == null){
				alert("Validación: Debe seleccionar Material.");
				$("#material").focus();
				return false;
			}else if(consumo == "" || consumo == null){
				alert("Validación: Debe ingresar consumo.");
				$("#consumo").focus();
				return false;
			}else if(isNaN(consumo)){
                alert("Validación: Consumo debe ser un número.");
                $("#consumo").focus();
                return false;
            }else if(medidacontador == -1 || medidacontador == null){
				alert("Validación: Debe seleccionar Medida Contador.");
				$("#medidacontador").focus();
				return false;
			}else if(contador == 0 || contador == null){
				alert("Validación: Debe ingresar Contador.");
				$("#contadorcantidad").focus();
				return false;
			}/*else if(adjunto == "" || adjunto == null){
				alert("Validación: Debe adjuntar imagen.");
				$("#imagen").focus();
				return false;
			}*/else if(comentario == "" || comentario == null){
				alert("Validación: Debe ingresar Comentario.");
				$("#comentario").focus();
				return false;
			}else if(isTractorAndRequiresImplemento(idequipo)){
                var idImplemento            = $("#tractor_implemento option:selected").val();
                var tractor_promedioo       = $("#tractor_promedio option:selected").val();
                if(idImplemento == "0" || idImplemento == "" || tractor_promedioo == "0" || tractor_promedioo == ""){
                    alert("Validación: Tractor require seleccion de Implemento y Promedio STD");
                    return false;
                }
            }
			
			var files_extras = document.getElementsByName('extra_imagen_file[]');
			for(var i = 0; i < files_extras.length; i++){
				var element_id =  files_extras[i].id;
				var element_value = files_extras[i].value;
				if(element_value == "" || element_value == null){
					alert("Validación: Debe subir imagen extra, de lo contrario elimine el elemento.");
					$("#"+element_id).focus();
					return false;
				}
			}
			
			hasImagenExtras = files_extras.length > 0?true:false;
			hasImagenPrimaria = (adjunto == "" || adjunto == null)?false:true;
			
			if(hasImagenExtras && hasImagenPrimaria == false){
				alert("Validación: Debe adjuntar imagen.");
				$("#imagen").focus();
				return false;
			}
			
			hasImagenExtras
			
			return true;
		}
		
		function go_back(){
			window.location.href = "index.php";
		}

		function show_imagen_popup(url){
			 window.open("../files/vales/"+url, "window name",
						"height=400,width=400,modal=yes,alwaysRaised=yes");
		}		
			
			
		$(document).ready(function() {
            $('.date-picker').datepicker();	
			$(".chosen-select").chosen();
			getUbicacion();
        });
		
		function onError(error){
		   switch(error.code) {
				case error.PERMISSION_DENIED:
					alert("Location Permission denied by user.");
					break;
				case error.POSITION_UNAVAILABLE:
					alert("Location position unavailable.");
					break;
				case error.TIMEOUT:
					alert("Location Request timeout.");
					break;
				case error.UNKNOWN_ERROR:
					alert("Location. Unknown error.");
					break;
			}
		}
		
		function onSucccess(position) {
		  $("#gps_lat").val(position.coords.latitude);
		  $("#gps_lon").val(position.coords.longitude);
		}	
		
		function getUbicacion(){			
			var config = {
			  enableHighAccuracy: true, 
			  maximumAge        : 30000, 
			  timeout           : 27000
			};
			navigator.geolocation.getCurrentPosition(onSucccess, onError, config );
		}
		
		function validate_number(evt){
		  var theEvent = evt || window.event;

		  // Handle paste
		  if (theEvent.type === 'paste') {
			  key = event.clipboardData.getData('text/plain');
		  } else {
		  // Handle key press
			  var key = theEvent.keyCode || theEvent.which;
			  key = String.fromCharCode(key);
		  }
		  var regex = /[0-9]|\./;
		  if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		  }
		}
		
		function load_centrocsoto_by_equipo(){
			
			var idequipo = $("#equipo option:selected").val();
			
			if(idequipo == 0 || idequipo == null){
				$("#centrocosto").val(0);
				return false;
			}
			
			var parametros = {
						"cod" : 3,
						"idequipo" : idequipo

			};

			$.ajax({
						data:  parametros,
						url:   '../phps/dvales_ajax.php',
						type:  'post',
						dataType: "html",
						beforeSend: function (repuesta) {                       
							openModal();
						},
						success: function(respuesta){

							respuesta = $.parseJSON( respuesta );

							if(respuesta.estado == "1"){							
								;
								$("#centrocosto").val(respuesta.data.id);
								$('#centrocosto').chosen().val(respuesta.data.id);
								$('#centrocosto').trigger("chosen:updated");
								
								if( respuesta.data.medida_contador != null ){
									$("#medidacontador").val(respuesta.data.medida_contador);
									$('#medidacontador').chosen().val(respuesta.data.medida_contador);
									$('#medidacontador').trigger("chosen:updated");
								}else{
									$('#medidacontador').chosen().val(-1);
									$("#medidacontador").val(-1);
									$('#medidacontador').trigger("chosen:updated");
								}
								closeModal();                          

							}else{

								closeModal();
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
		
		function format_data(idvale, lastDetalle1ItemId, equipo_id, consumidor, fechaConsumo, medidacontador, contador, comentario, consumo, gps_lon, gps_lat, fileattachname, mapping_extra_images_json){
			
			var data =
				'{'+
					'"idusuario"    :   "'+consumidor+'",'+
					'"idvale"       :   "'+idvale+'",'+
					'"equipo_id"    :   "'+equipo_id+'",'+
					'"unidad_medida":   '+medidacontador+','+
					'"longitud"     :   '+gps_lon+','+
					'"latitud"      :   '+gps_lat+','+
					'"observacion"  :   "'+comentario+'",'+
					'"kilometraje"  :   "'+contador+'",'+
					'"detalle":['+
							'{   "iditem"       :   '+lastDetalle1ItemId+','+
								'"cantidad"     :   '+consumo+','+
								'"voucher_img"  :   "'+fileattachname+'",'+
								'"voucher_nro"  :   ""'+
							'}'+
						'],'+
					'"mapping_extra_images" :' + mapping_extra_images_json+
				'}';
				
			return JSON.stringify(JSON.parse(data));
		}
		
		function tsomobile_verlog(idvale){
			 window.open("../vales_planner/ver_logs_tsomobile.php?idvale="+idvale,"Historial",'width=900,height=400,toolbar=0,menubar=0,location=0');
		 }
		 
		 function rfcconsumo_verlog(idvale){
			 window.open("../vales_planner/ver_logs_rfcconsumo.php?idvale="+idvale,"Historial",'width=900,height=400,toolbar=0,menubar=0,location=0');
		 }
		 
		 function validate_contadores(old_contador){
			 
			var contador = $("#contadorcantidad").val();
			var contador_new = parseFloat(contador);
			
			var contador_old = 0;
			if(old_contador != ""){
				contador_old = parseFloat(old_contador);
			}									
									
			if( contador_old > 0 &&  contador_old > contador_new ){
				alert("Validación: Contador debe ser siempre mayor al registrado anterior: "+contador_old);
				return false;
			}else{
				return true;										
			}
		 }
		 function validate_rendimientos(rendimiento_estandar, old_contador){
			
			var contador = $("#contadorcantidad").val();
			var contador_new = parseFloat(contador);
			
			var contador_old = 0;
			if(old_contador != ""){
				contador_old = parseFloat(old_contador);
			}
			
			var recorrido = 0;
			if( contador_old > 0 ){
				recorrido = contador_new - contador_old;
			}else{
				recorrido = contador_new;
			}
			
			var consumo  = $("#consumo").val();
			
			var rendimiento_real = recorrido / consumo;
			var rendimiento_estandar_limite = rendimiento_estandar * 5 / 100;
			
			if( rendimiento_real > rendimiento_estandar_limite ){
				var confirmacion = confirm("Alerta!\nSu consumo es superior al esperado en base al rendimiento estandar.\n ¿Seguro que desea continuar?");
			
				if(confirmacion){
					return true;
				}else{
					return false;
				}
			}else{
				return true;	
			}
					
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
	
	var numero_extras_images = 0;
	add_extra_imagen_file = function () { 
	
	  var max_img_extras = <?=$max_img_extras?>;
	  var files_extras_cantidad = document.getElementsByName('extra_imagen_file[]').length;
	  if( files_extras_cantidad >=  max_img_extras){
		  alert("Validación: Máximo número de imágenes extras alcanzado");
		  return false;
	  }
	
	  //Creo un div dentro del cul iran los nuevos elmentos
	   nDiv 			= document.createElement('div');
	   nDiv.className 	= 'control-group';
	   nDiv.id 			= 'file_extra_imagen_file' + (++numero_extras_images);
	   
	   //Obtengo tipo de archivo a subir	  
	   var nombre = "Imagen extra:";	  
	  
	  //Creo un label
	   newlabel = document.createElement("Label");	
	   newlabel.className = 'control-label';
	   newlabel.innerHTML = nombre;
	  
	   //Creo el input file
	   nCampo 			= document.createElement('input');
	   nCampo.name 		= 'extra_imagen_file[]';
	   nCampo.id 		= 'extra_imagen_file_'+numero_extras_images;
	   nCampo.type 		= 'file';
	   nCampo.size 		= '30';
	   nCampo.accept	= 'image/x-png,image/gif,image/jpeg';	
	   
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
	
	function createFormData_consumir(respuesta){
		
		var consumidor    = <?=$_SESSION['id']?>;
		var fechaConsumo  = $("#fecha_consumo").val();
		var medidacontador= $("#medidacontador option:selected").val();
		var contador	  = $("#contadorcantidad").val();
		var comentario	  = $("#comentario").val();
		var consumo_	  = $("#consumo").val();
		var gps_lon		  = $("#gps_lon").val();
		var gps_lat		  = $("#gps_lat").val();
		var adjunto 	  =	$("#imagen").val();
		var materialid    = $("#material option:selected").val();

        var consumo = Number(consumo_);

		var formDataConsumir = new FormData();																		
		formDataConsumir.append("id_conection"		, "kkkRwF^MQa!vv6ssH5%S=canessa19");
		var fileattachname = "";
		
		if(adjunto != "" && typeof adjunto != "undefined"){										
			var parts  = adjunto.split(".");
		    fileattachname = 'img.'+parts[1];
										
			var myFile = $("#imagen")[0].files[0];
			var myRenamedFile = new File([myFile], fileattachname);
			formDataConsumir.append("uploaded_file_0", myRenamedFile);
		}else{
			formDataConsumir.append("not_include_images", "1");
		}
		
		var mapping_extra_images = [];
		var files_extras = document.getElementsByName('extra_imagen_file[]');
		for(var i = 0; i < files_extras.length; i++){
			var element_id =  files_extras[i].id;
			var element_value = files_extras[i].value;
			formDataConsumir.append("uploaded_file_extras_"+i, $("#"+element_id)[0].files[0]);
			mapping_extra_images.push({iditem: i, material : materialid, idvale : respuesta.data.idvale, voucher_img: element_value});
		}
		
		var mapping_extra_images_json = JSON.stringify(mapping_extra_images);
		var data_form = format_data(respuesta.data.idvale, respuesta.data.lastDetalle1ItemId, respuesta.data.equipo_id, consumidor, fechaConsumo, medidacontador, contador, comentario, consumo, gps_lon, gps_lat, fileattachname, mapping_extra_images_json);
		formDataConsumir.append("data", data_form);
		
		return formDataConsumir;
	}

        function isTractorAndRequiresImplemento(idequipo){
            //Tractores 06-TRAAGR-00000001 al 06-TRAAGR-00000012
            if(idequipo >= 5383 && idequipo <= 5394){
                return true;
            }
            return false;
        }

        function evalute_show_implemento(){
            var idequipo = $("#equipo option:selected").val();

            if(isTractorAndRequiresImplemento(idequipo)){
                $("#tr_tractores_extras").css("display", "table-row");
                $('#tractor_implemento').chosen('destroy');
                $('#tractor_implemento').val("0");
                $("#tractor_implemento").chosen();
                $('#tractor_promedio').chosen('destroy');
                $('#tractor_promedio').val("0");
                $("#tractor_promedio").chosen();
            }else{
                $("#tr_tractores_extras").css("display", "none");
            }
        }

        function select_promedioSTD(){
            var idImplemento      = $("#tractor_implemento option:selected").val();

            <?php
            echo generate_javascript_mapping(
                $found_implemento,
                $found_promedio,
                $data_vale['tractor_implemento'],
                $data_vale['tractor_promedio']
            );
            ?>
        }
	
</script>

</body>
</html>
