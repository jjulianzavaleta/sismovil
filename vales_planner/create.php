<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../plantilla1.php");
include_once("../phps/dvales_create.php");


$NOMBRE_SHOW 	    = "Vale";
$NOMBRE_SHOW_PLURAL = "Vales";
$btn_submit_name    = "Guardar";
$display_data       = false;
$id_vale            = 0;
$isLastValeConsumidoByEquipo = false;
$ultimo_vale_by_equipo = array();
$ultimo_vale_by_equipo['vale_eqnro'] = 0;
$historial_kiloemtraje = array();

/*----  GET DATA FOR COMBOBOXES ----*/
$equiposWeb         = getAllEquiposWebForCombobox();
$centroCostoWeb     = getAllCentroCostoWebForCombobox();
$grifos             = getAllGrifosForCombobox();
$materiales         = getAllMaterialForCombobox();
$choferes           = getAllChoferesForCombobox();
$tractor_implementos = getTractorImplementosCombobox();
$tractor_promedios = getTractorPromedioCombobox();
$data_vale          = array();
$data_vale['estado']  = 1;
$data_vale['anulado'] = 0;
$data_vale['detalle2_modo'] = 1;//modo automatico
$data_vale['tsomobile_somethingwentwrong'] 		= "";
$data_vale['tsomobile_kilometraje'] 			= "";
$data_vale['rfcconsumo_somethingwentwrong']		= "";
$data_vale['tractor_implemento']                = "";
$data_vale['tractor_promedio']               = "";
$read_only = false;

/*----  LOGIC FOR EDIT MODE ----*/
if( isset($_GET['id']) && is_numeric($_GET['id']) ){
	$display_data  = true;
	$id_vale       = $_GET['id'];
	$data_vale     = getDataVale($id_vale);
	$data_detalle1 = getDataDetalle1($id_vale);
	$data_detalle2 = getDataDetalle2($id_vale);
	$historial_kiloemtraje = getHistorialKilometraje($id_vale);
	$ultimo_vale_by_equipo_temp = getUltimoValeConsumidoByVehiculo($data_vale['equnr']);
	
	if( !empty($ultimo_vale_by_equipo_temp)){
		$ultimo_vale_by_equipo		 = $ultimo_vale_by_equipo_temp;
		$isLastValeConsumidoByEquipo = $ultimo_vale_by_equipo['idvale'] == $id_vale?true:false;		
	}
	
	$data_vale['kostl'] = $data_detalle2[0]['kostl'];
	
	if($data_vale['anulado'] == 1 || $data_vale['estado'] == 2 || $data_vale['estado'] == 3){
		$read_only = true;		
	}
}

array_unshift($equiposWeb,array("id" => 0, "equnr" => "--Seleccione--"));
array_unshift($centroCostoWeb,array("id" => 0, "ktext" => "--Seleccione--"));
array_unshift($grifos,array("id" => 0, "nombre" => "--Seleccione--"));
array_unshift($choferes,array("id" => 0, "name1" => "--Seleccione--"));
//array_unshift($materiales,array("id" => 0, "nombre" => "--Seleccione--"));

?>
<script src="../media/js/chosen.jquery.min.js"></script>
<link href="../media/css/chosen.min.css" rel="stylesheet"/>

<div id="exTab2" class="container">	
	<ul class="nav nav-tabs">
		<li class="active"><a  href="#1" data-toggle="tab">Vale</a></li>
		<li><a href="#2" data-toggle="tab">Consumo</a></li>
	</ul>

	<div class="tab-content " style="width:95%;">
		<div class="tab-pane active" id="1">
			<div class="clearfix">
				<div class="row-fluid">
					<form class="form-horizontal" id="validation-form_nuevo" method="post" novalidate="novalidate" autocomplete="off">
					  
					  <?php if( $display_data ){ ?>
						<div class="row-fluid">
								<table border="0"  width="100%" align="center">
									<tr align="center">	
										<td>
										  <span class="label label-info">Fecha Registra: <?=date_format($data_vale['fecha_registra'], 'Y-m-d H:i:s')?></span>
										  <span class="label label-info">Usuario Registra: <?=getUsernameFromUsuarioActiveDirectory($data_vale['usuario_registra'])?></span>
										  
										  <?php if( !empty($data_vale['fecha_modifica']) ){ ?>
										  <span class="label label-warning">Fecha Última Modificación: <?=date_format($data_vale['fecha_modifica'], 'Y-m-d H:i:s')?></span>
										  <?php } ?>
										  
										  <?php if( !empty($data_vale['usuario_modifica']) ){ ?>
										  <span class="label label-warning">Usuario Última Modificación: <?=getUsernameFromUsuarioActiveDirectory($data_vale['usuario_modifica'])?></span>
										  <?php } ?>
										  
										  <?php if( !empty($data_vale['fecha_emite']) ){ ?>
										  <span class="label label-success">Fecha Emite: <?=date_format($data_vale['fecha_emite'], 'Y-m-d H:i:s')?></span>
										  <?php } ?>
										  
										  <?php if( !empty($data_vale['usuario_emite']) ){ ?>
										  <span class="label label-success">Usuario Emite: <?=getUsernameFromUsuarioActiveDirectory($data_vale['usuario_emite'])?></span>
										  <?php } ?>
										  
										</td>
									</tr>
								</table>
						</div>	
					  <?php } ?>	

					  <?php
						if( $display_data ){
							if( $data_vale['anulado'] == 1 ){
								include("anulado_view.php");
							}
						}
					  ?>
					  
						<div class="row-fluid">
							<h5 class="header smaller lighter blue">Datos Cabecera</h5>
						
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							
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
								<td  width="50%">
									<label class="control-label" for="chofer">Chofer:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="chofer" id="chofer" <?=$read_only?"disabled='disabled'":""?> >
											<?php
												foreach($choferes as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['chofer'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}	
													$extra_data = !empty($item['num_doc_identidad'])?(" (".$item['num_doc_identidad'].")"):"";		
													
													echo "<option value='".$item['id']."' ".$selected.">".($item['name1'].$extra_data)."</option>";
													
												}
											?>											
										</select>
									</div>
								</td>
															
								<td width="50%"> 
									<label class="control-label" for="copiloto">Copiloto:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="copiloto" id="copiloto" <?=$read_only?"disabled='disabled'":""?> >
											<?php
												foreach($choferes as $item){
													$selected = "";
													if( $display_data ){
														if( $data_vale['chofer_aux'] == $item['id'] ){
															$selected = "selected='selected'";
														}
													}
													$extra_data = !empty($item['num_doc_identidad'])?(" (".$item['num_doc_identidad'].")"):"";	
													
													echo "<option value='".$item['id']."' ".$selected.">".($item['name1'].$extra_data)."</option>";
												}
											?>												
										</select>
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
                                                    echo '<option value="'.$implemento['valor'].'" '.$tractor_implemento_selected.'>'.$implemento['valor'].'</option>';
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
								
								<td  width="50%">								   
									<label class="control-label" for="fecha_consumo">Fecha Máxima para Consumo:</label>
									<div class="control-group">																
									<input class="span6 date-picker" name="fecha_consumo" id="fecha_consumo" type="text"
											   data-date-format="yyyy-mm-dd" <?=$read_only?"disabled='disabled'":""?>
											   value="<?=$display_data===true?date_format($data_vale['fecha_max_consumo'], 'Y-m-d'):""?>"/>
									</div>
								</td>	
								<td width="50%">									
									<div class="control-group">																
									<input type="checkbox" id="isTermoking" value="isTermoking" <?=$read_only?"disabled='disabled'":""?>
									       <?=($display_data===true?($data_vale['istermoking']=="1"?"checked='checked'":""):"")?>>
								    <label class="control-label" for="isTermoking">Termoking:&nbsp&nbsp </label>
									</div>
									<div class="control-group">																
									<input type="checkbox" id="hasCarreta" value="hasCarreta" <?=$read_only?"disabled='disabled'":""?>
									       <?=($display_data===true?($data_vale['hascarreta']=="1"?"checked='checked'":""):"")?>>
								    <label class="control-label" for="hasCarreta">Carreta:&nbsp&nbsp </label>
									</div>
								</td>
							</tr>
							</table>
						</div>
						
						
						<div class="row-fluid">
							<h5 class="header smaller lighter blue">Detalle 1 - Lista de Materiales</h5>
						
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							
							<?php if(!$read_only){ ?>
							<tr>
								<td  width="50%">
									<label class="control-label" for="material_d1">Material:</label>
									<div class="control-group">
										<select  class="span6 chosen-select" name="material_d1" id="material_d1" >
											<?php
												foreach($materiales as $item){
													$extra_data = !empty($item['cod_sap'])?(" (".$item['cod_sap'].")"):"";	
													echo "<option value='".$item['id']."'>".($item['nombre'].$extra_data)."</option>";
												}
											?>												
										</select>
									</div>
								</td>
								<td width="50%"> 
									<label class="control-label" for="cantidad_d1">Cantidad:</label>
									<div class="control-group">									
									<input class="span6"  type="text" id="cantidad_d1" name="cantidad_d1" value="" onkeypress='validate_number(event)'/>
									</div>
								</td>								
							</tr>
							
							<tr>
								<td colspan="2" align="center">
									<input type="button" class="btn btn-primary" value="Agregar" onclick="add_row_detalle1()">
								</td>
							</tr>
							<?php } ?>
							
							</table>
							
						<div id="table_report_wrapper1" class="dataTables_wrapper" role="grid">
							<table id="table_report_1" class="table table-striped table-bordered table-hover dataTable"
								   aria-describedby="table_report_info">
								<thead>
								<tr role="row">
									<th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										 colspan="1">ID
									</th>
									<th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										 colspan="1"
										 style="width: 60px;;font-size: 11px">Item
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 200px;;font-size: 11px">Material
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 60px;;font-size: 11px">Cantidad
									</th>
									<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;;font-size: 11px">
										Eliminar
									</th>

								</tr>
								</thead>

								<tbody role="alert" aria-live="polite" aria-relevant="all">
									<?php
										$i = 1;
										if( $display_data && !empty($data_detalle1) ){
											foreach($data_detalle1 as $item){
												echo "<tr>";
												echo "<td>".$item['matnr']."</td>";
												echo "<td>".$i."</td>";
												echo "<td>".getMaterialNameFromMaterial($item['matnr'])."</td>";
												echo "<td><input type='text' style='width: 60px;' class='cantProd' ".($read_only?"readonly='readonly'":"")." value='".$item['menge']."'></td>";
												
												if(!$read_only){
													echo $ELIMINAR_HTML_CODE;
												}else{
													echo "<td></td>";
												}
												
												echo "</tr>";
												$i++;
											}
										}
									?>
								</tbody>
							</table>
						</div>						
					</div>
					
					<div class="row-fluid"  >
							<h5 class="header smaller lighter blue">Detalle 2 - Asignación de Centro de Costo</h5>
						
							<table cellpadding="0" cellspacing="0" width="100%">
							
							<tr>
								<td>
									<input type="radio" name="detalle2_modo" <?=$read_only?"disabled='disabled'":""?> value="1" <?=$data_vale['detalle2_modo']==1?"checked='checked'":""?> onchange="modo_section2_change()"> Automatico<br>
								</td>
							</tr>
							
							<tr id="centrocosto_row"  <?=$data_vale['detalle2_modo']==2?"style='display:none;'":""?>>
								<td>
									<table width="100%" align="left">
									<tr>
									<td> 	
										<label class="control-label" for="centrocosto">Centro de Costo:</label>
										<div class="control-group">
											<select  class="span6" name="centrocosto" id="centrocosto" disabled='disabled'>
												<?php
													foreach($centroCostoWeb as $item){
														$selected = "";
														if( $display_data && $data_vale['detalle2_modo']==1){
															if( $data_vale['kostl'] == $item['id'] ){
																$selected = "selected='selected'";
															}
														}	
														echo "<option value='".$item['id']."' ".$selected.">".$item['ktext']."</option>";
													}
												?>													
											</select>
										</div>	
									</td>
									<td width="50%"></td>									
									</tr>
									</table>
								</td>
							</tr>
							
							<tr>
								<td>
									<input type="radio" name="detalle2_modo" <?=$read_only?"disabled='disabled'":""?> value="2" <?=$data_vale['detalle2_modo']==2?"checked='checked'":""?> onchange="modo_section2_change()" > Manual<br>
								</td>
							</tr>
							
							<tr <?=$data_vale['detalle2_modo']==1?"style='display:none;'":""?> id="tr_section2_detail" >
								<td>
								<table  width="100%" align="left">
									<?php if(!$read_only){ ?>
									<tr>								
										<td  width="50%">
											<label class="control-label" for="centrocosto_d2">Centro de Costo:</label>
											<div class="control-group">
												<select  class="span6" name="centrocosto_d2" id="centrocosto_d2"  style="width:350px;">
													<?php
														foreach($centroCostoWeb as $item){
                                                           $extra_data = !empty($item['kostl'])?(" (".$item['kostl'].")"):"";
															echo "<option value='".$item['id']."'>".($item['ktext'].$extra_data)."</option>";
														}
													?>													
												</select>
											</div>
										</td>
										<td width="50%"> 
											<label class="control-label" for="material_d2">Material:</label>
											<div class="control-group">
												<select  class="span6" name="material_d2" id="material_d2"   >																							
												<?php
													if( $display_data ){
														foreach($data_detalle1 as $item){
															echo "<option value='".$item['matnr']."'>".getMaterialNameFromMaterial($item['matnr'])."</option>";
														}
													}
												?>
												</select>
											</div>
										</td>								
									</tr>
									<tr>								
										<td width="50%"> 
											<label class="control-label" for="asignacion_d2">Asignación (%):</label>
											<div class="control-group">									
											<input class="span6"  type="text" id="asignacion_d2" name="asignacion_d2" value=""  />
											</div>
										</td>	
									   <td width="50%">
									   </td>
									</tr>
									<tr>
									<td colspan="2" align="center" >
										<input type="button" class="btn btn-primary" value="Agregar" id="btn_addsection2" onclick="add_row_detalle2()" >
									</td>
									</tr>
									
									<?php } ?>
								
								<tr style="width:100%;">
									<td colspan="2" align="center" width="100%">
									<div id="table_report_wrapper2" class="dataTables_wrapper" role="grid" width="100%" style="display:block;">
										<table id="table_report_2" class="table table-striped table-bordered table-hover dataTable"
											   aria-describedby="table_report_info" style="width:100%;"">
											<thead>
											<tr role="row">
												<th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
													 colspan="1">ID
												</th>
												<th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
													 colspan="1"
													 style="width: 60px;;font-size: 11px">Item
												</th>	
												<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
													colspan="1"
													style="width: 60px;;font-size: 11px">Producto
												</th>									
												<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
													colspan="1"
													style="width: 60px;;font-size: 11px">Centro de Costo
												</th>	
												<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
													colspan="1"
													style="width: 60px;;font-size: 11px">Asignación
												</th>
												<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;;font-size: 11px">
													Eliminar
												</th>

											</tr>
											</thead>


											<tbody role="alert" aria-live="polite" aria-relevant="all">                       
												<?php
													$i = 1;
													if( $display_data && !empty($data_detalle2) ){
														foreach($data_detalle2 as $item){
															echo "<tr>";
															echo "<td>".$item['matnr']."$".$item['kostl']."</td>";
															echo "<td>".$i."</td>";
															echo "<td>".getMaterialNameFromMaterial($item['matnr'])."</td>";
															echo "<td>".getCentroCostoName($item['kostl'])." (".getCentroCostoCodigo($item['kostl']).")</td>";
															echo "<td><input type='text' style='width: 60px;' class='cantProd' ".($read_only?"readonly='readonly'":"")." value='".$item['asignacion']."'></td>";
															
															if(!$read_only){
																echo $ELIMINAR_HTML_CODE;
															}else{
																echo "<td></td>";
															}
															
															echo "</tr>";
															$i++;
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

							<div class="modal-footer">
										
								<?php if( $data_vale['estado'] == 1 && $data_vale['anulado'] != 1){ ?>
								<button type="button" class="btn btn-success" style= "float: left;position: relative;left: 50%;" onclick="send_data(1)">
									 <i class="icon-plane icon-white"></i> Emitir
								</button>
								<button type="button" class="btn btn-info" style= "float: left;position: relative;left: 50%;" onclick="send_data(0)">
									 <i class="icon-plane icon-save"></i> Guardar
								</button>
								<?php } ?>

								<?php if( $data_vale['estado'] == 2 && $data_vale['anulado'] != 1){ ?>
								<button type="button" class="btn btn-warning" style= "float: left;position: relative;left: 50%;" onclick="deshacer_emision(<?=$_GET['id']?>)">
									 <i class="icon-plane icon-white"></i> Deshacer Emisión
								</button>
								<?php } ?>
								
								<button type="button" class="btn btn-default" style= "float: left;position: relative;left: 50%;" data-dismiss="modal" onclick="go_back()">
									<i class="icon-remove"></i>Cancelar
								</button>
							</div>							
					</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="tab-pane" id="2">
		  <div class="clearfix" >
				<div class="row-fluid">
					<table border="0"  width="100%" align="center">
						<tr align="center">	
							<td>
								<?php  if( isset($data_vale['consumo_idusuario']) && !empty($data_vale['consumo_idusuario']) ){ ?>
								<span class="label label-info">Fecha Consumido: <?=date_format($data_vale['consumo_fechaconsumo'], 'Y-m-d H:i:s')?></span>
								<span class="label label-info">Usuario Consumió: <?=getChoferNameFromID($data_vale['consumo_idusuario'])?></span>									  
								<span class="label label-info">GPS: <?=($data_vale['consumo_gps_latitude'].",".$data_vale['consumo_gps_longitude'])?></span>	
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="row-fluid">
					<?php
						$historial_km_h1 = "";
						$historial_km_h2 = "";
						if( !empty($historial_kiloemtraje) ){
							$historial_km_h1 = 'style="display: none"';
							$historial_km_h2 = 'style="display: inline-block"';
						}else{
							$historial_km_h1 = 'style="display: inline-block"';
							$historial_km_h2 = 'style="display: none"';
						}
						
						$historial_link1 = '<span id="span_historial_1" class="label label-info" '.$historial_km_h1.'>No existe historial de modificaciones</span>';
						$historial_link2 = '<span id="span_historial_2" class="label label-warning" '.$historial_km_h2.'><a href="#" style="color:white;" onclick="kilometraje_verhistorial('.$id_vale.')">Ver historial</a></span>';
						$historial_link  = $historial_link1.$historial_link2;
						
						$tr_alerta_ultimo_vale_by_equipo = "";
						if( $isLastValeConsumidoByEquipo  ){
							$equipo_nro = $ultimo_vale_by_equipo['equnr'];
							$equipo_lastkilometraje = $ultimo_vale_by_equipo['equipo_kilometraje'];
							$tr_alerta_ultimo_vale_by_equipo = "<tr><td colspan='2'><span class='label label-warning'>Este vale es el último vale consumido para el equipo ".$equipo_nro.". Último kilometraje/hodómetro del equipo: <span id='equipo_lastkilometraje_html'>".$equipo_lastkilometraje."</span></span></td></tr>";
						}
					?>
					<h5 class="header smaller lighter blue">Data Consumo  <?=$historial_link?></h5>
						
					<table border="0" cellpadding="0" cellspacing="0" width="100%">	
						<?=$tr_alerta_ultimo_vale_by_equipo?>
						<tr>
							<td width="50%">
								<?php
									$label_kilometraje = isset($data_vale['consumo_unidadmedida'])?($data_vale['consumo_unidadmedida']==0?"Kilometraje":($data_vale['consumo_unidadmedida']==1?"Hódometro":"")):"Kilometraje/Hodómetro";
								?>
								<label class="control-label" for="kilometraje_user"><?=$label_kilometraje?>:</label>
								<div class="control-group">									
									<input class="span6"  type="text" id="kilometraje_user" name="kilometraje_user" value="<?=(isset($data_vale['kilom'])?$data_vale['kilom']:"")?>" readonly="readonly"/>
								</div>
							</td>		
							<td width="50%"> 
								<label class="control-label" for="obervacion_user">Observación:</label>
								<div class="control-group">	
									<textarea class="span6" id="obervacion_user" name="obervacion_user" rows="4" cols="100" readonly="readonly" style="background:#f5f5f5 "><?=(isset($data_vale['consumo_observacion'])?$data_vale['consumo_observacion']:"")?></textarea>
								</div>
							</td>								
						</tr>
						<?php if($data_vale['estado'] == 3){ ?>
						<tr>
							<td colspan="2">
								<div class="modal-footer">
										<a id="element_ae" alt="Activar edición" style="float: left;position: relative;left: 50%;" title="Activar edición" class="btn btn-mini btn-info" href="#" onclick="enable_edit_kilometraje()">Editar <i class="icon-edit bigger-120"></i></a>
										<a id="element_se" alt="Guardar"  style="display:none;float: left;position: relative;left: 50%;" title="Guardar" class="btn btn-mini btn-info" href="#" onclick="save_new_kilometraje(<?=$_GET['id']?>)">Guardar <i class="icon-save bigger-120"></i></a>
										<a id="element_ce" alt="Cancelar" style="display:none;float: left;position: relative;left: 50%;" title="Cancelar" class="btn btn-mini btn-danger" href="#" onclick="cancel_new_kilometraje()">Cancelar <i class="icon-remove bigger-120"></i></a>
								</div>
							</td>
						<tr>
						<?php } ?>
					</table>					  
				</div>
				
				<div class="row-fluid"  >
						<h5 class="header smaller lighter blue"> Cantidades cargadas</h5>
						
						<div id="table_report_wrapper3" class="dataTables_wrapper" role="grid">
							<table id="table_report_3" class="table table-striped table-bordered table-hover dataTable"
								   aria-describedby="table_report_info">
								<thead>
								<tr role="row">									
									<th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										 colspan="1"
										 style="width: 10px;;font-size: 11px">Item
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 100px;;font-size: 11px">Material
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 60px;;font-size: 11px">Chofer - Cantidad
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 60px;;font-size: 11px">Chofer - Voucher nro
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 60px;;font-size: 11px">Excel - Cantidad
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 60px;;font-size: 11px">Excel - Precio
									</th>
									<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
										colspan="1"
										style="width: 60px;;font-size: 11px">Excel - Total
									</th>

								</tr>
								</thead>

								<tbody role="alert" aria-live="polite" aria-relevant="all">
									<?php
										$i = 1;
										if( $display_data && !empty($data_detalle1) ){
											foreach($data_detalle1 as $item){
												echo "<tr>";												
												echo "<td>".$i."</td>";
												echo "<td>".getMaterialNameFromMaterial($item['matnr'])."</td>";
												echo "<td>".$item['menge_chofer']."</td>";												
												echo "<td>".$item['voucher_nro']."</td>";
												echo "<td>".$item['fromexcel_cantidad']."</td>";
												echo "<td>".$item['fromexcel_precio']."</td>";
												echo "<td>".$item['fromexcel_total']."</td>";
												echo "</tr>";
												$i++;
											}
										}
									?>
								</tbody>
							</table>
						</div>	
				</div>
				
				<?php include('apis_view.php'); ?>
				
				
				<br><br><br><br>
			</div>
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
		$( '.activePlantilla1' ).html( "<a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a> > <a href='create.php'>Nuevo</a>");
		
		var oTable1 = $('#table_report_1').dataTable({
				"bPaginate": false,
				"bFilter"  : false,
				"bInfo"    : false,
				"bSort": false,
				"aoColumns": [
					{"bSortable": false,"sClass": "hide_column"},
					null,
					null,
					null,
					{ "bSortable": false }
				] ,
			});
			
		var oTable2 = $('#table_report_2').dataTable({
				"bPaginate": false,
				"bFilter"  : false,
				"bInfo"    : false,
				"bSort": false,
				"aoColumns": [
					{"bSortable": false,"sClass": "hide_column"},
					null,
					null,
					null,
					null,
					{ "bSortable": false }
				] ,
			});
			
		var oTable3 = $('#table_report_3').dataTable({
				"bPaginate": false,
				"bFilter"  : false,
				"bInfo"    : false,
				"bSort": false,
				"aoColumns": [					
					null,
					null,
					null,
					null,
					null,
					null,
					null
				] ,
			});
		
		function add_row_detalle1(){			
			
			var idmaterial = $("#material_d1 option:selected").val();
			var material   = $("#material_d1 option:selected").text();
			var cantidad   = $("#cantidad_d1").val();						
			
			if( material == "" ){
				alert("Validacion: Seleccione material");
				$("#material_d1").focus();				
				return false;
			}else if( cantidad == "" ){
				alert("Validacion: Ingrese cantidad");
				$("#cantidad_d1").val("");
				$("#cantidad_d1").focus();
				return false;
			}else if( isNaN(cantidad) ){
				alert("Validacion: Cantidad Invalida");
				$("#cantidad_d1").val("");
				$("#cantidad_d1").focus();
				return false;
			}else if( is_material_repetido(idmaterial) ){
				alert("Validacion: Material ya fue agregado");				
				return false;
			}
			
			var oTable1    = $("#table_report_1").dataTable();
			var item       = oTable1.fnGetData().length+1;
			var editoption = '<button style="width: 30px;;font-size: 11px" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"><i class="icon-trash bigger-120"></i></button>';
			
			oTable1.fnAddData(
                [
					idmaterial,
                    item,
                    material,
					'<input type="text" style="width: 60px;" class="cantProd" value="' + cantidad + '">',
                    editoption
				]);
			
			$("#cantidad_d1").val("");
			$("#material_d1").focus();	

			//Add new option to combobox in second details frame
			$('#material_d2').append( new Option(material,idmaterial) );
		}
		
		$('#table_report_1 tbody').on( 'click', 'button', function () {

            if(confirm("¿Seguro que desea eliminar el item? Esto eliminará tambien la asignaciones relacionadas que existan")){
				
				var oTable1    = $("#table_report_1").dataTable();
                var target_row = $(this).closest("tr").get(0);
                var aPos       = oTable1.fnGetPosition(target_row);
				
				//get idmaterial to delete
				var rows       = oTable1.fnGetNodes();
				var ideliminar = $(rows[aPos]).find("td:eq(0)").html();				
				
                oTable1.fnDeleteRow( aPos );

                var rows = oTable1.fnGetNodes();
                var aux = 0;

                //set item col
                for(var i=0;i<rows.length;i++){
                    aux = i + 1;
                    $(rows[i]).find("td:eq(1)").html(aux);
                }
				
				//re-draw combobox options in second details frame
				$('#material_d2').empty()
				for(var i=0;i<rows.length;i++){                    
                    var id    = $(rows[i]).find("td:eq(0)").html();
					var name  = $(rows[i]).find("td:eq(2)").html();
					$('#material_d2').append( new Option(name,id) );
                }
				
				//remove asignaciones related to removed material in second details frame
				var oTable2    = $("#table_report_2").dataTable();
				var rows       = oTable2.fnGetNodes();				
				for(var i=0;i<rows.length;i++){                    
                    var ids      = $(rows[i]).find("td:eq(0)").html();					
					var idsarray = ids.split("$");
					if( idsarray[0] == ideliminar ){
						oTable2.fnDeleteRow( rows[i] , null, false);
					}
                }				
				oTable2.fnDraw();	

				 //set item col
				oTable2    = $("#table_report_2").dataTable();
				rows       = oTable2.fnGetNodes();				
				aux=0;
                for(var i=0;i<rows.length;i++){
                    aux = i + 1;
                    $(rows[i]).find("td:eq(1)").html(aux);
                }

            }else{
				return false;
			}
        } );
		
		function add_row_detalle2(mode){		
			
			var idmaterial    = $("#material_d2 option:selected").val();
			var material      = $("#material_d2 option:selected").text();
			var centrocosto   = $("#centrocosto_d2 option:selected").text();
			var idcentrocosto = $("#centrocosto_d2 option:selected").val();
			var asignacion    = $("#asignacion_d2").val();		
			
			if( idcentrocosto == 0){
				alert("Validacion: Seleccione Centro de Costo");
				$("#centrocosto_d2").focus();				
				return false;
			}else if( material == "" ){
				alert("Validacion: Seleccione material");
				$("#material_d2").focus();				
				return false;
			}else if( asignacion == "" ){
				alert("Validacion: Ingrese asignacion");
				$("#asignacion_d2").val("");
				$("#asignacion_d2").focus();
				return false;
			}else if( isNaN(asignacion) ){
				alert("Validacion: Asignación Invalida");
				$("#asignacion_d2").val("");
				$("#asignacion_d2").focus();
				return false;
			}else if( is_material_centrocosto_repetido(idmaterial,idcentrocosto) ){
				alert("Validacion: Material y Centro de Costo ya fueron agregados");				
				return false;
			}
			
			var oTable2       = $("#table_report_2").dataTable();
			var item       = oTable2.fnGetData().length+1;
			var editoption = '<button style="width: 30px;;font-size: 11px" alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"><i class="icon-trash bigger-120"></i></button>';
			
			oTable2.fnAddData(
                [
					idmaterial+"$"+idcentrocosto,
                    item,
                    material,
					centrocosto,
					'<input type="text" style="width: 60px;" class="cantProd" value="' + asignacion + '">',
                    editoption
				]);
			
			$("#asignacion_d2").val("");
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
		
		$('#table_report_2 tbody').on( 'click', 'button', function () {

            if(confirm("¿Seguro que desea eliminar el item?")){
				
				var oTable2    = $("#table_report_2").dataTable();
                var target_row = $(this).closest("tr").get(0);
                var aPos       = oTable1.fnGetPosition(target_row);
				
                oTable2.fnDeleteRow( aPos );
				
				var rows = oTable2.fnGetNodes();
                var aux = 0;

                //set item col
                for(var i=0;i<rows.length;i++){
                    aux = i + 1;
                    $(rows[i]).find("td:eq(1)").html(aux);
                }

            }else{
				return false;
			}
        } );
		
		function is_material_repetido(idmaterial){			
			var oTable1    = $("#table_report_1").dataTable();
			var rows       = oTable1.fnGetNodes();
			
			for(var i=0;i<rows.length;i++){              
                var id = $(rows[i]).find("td:eq(0)").html();				
				if( idmaterial == id ){
					return true;
				}
            }			
			return false;
		}
		
		function is_material_centrocosto_repetido(idmaterial,idcentrocosto){
			var oTable2    = $("#table_report_2").dataTable();
			var rows       = oTable2.fnGetNodes();
			
			for(var i=0;i<rows.length;i++){              
                var ids      = $(rows[i]).find("td:eq(0)").html();					
			    var idsarray = ids.split("$");							
				if( idsarray[0] == idmaterial && idsarray[1] == idcentrocosto ){
					return true;
				}
            }			
			return false;
		}
		
		function send_data(emitir){

			var confirmacion = confirm("¿Confirma la accion?");
			
			if(confirmacion && validaciones_formulario()){
			}else{
				return false;
			}
			
			var idvale        = <?=$id_vale?>;
			var idequipo      = $("#equipo option:selected").val();
			var idcentrocosto = $("#centrocosto option:selected").val();
			var idgrifo       = $("#grifo option:selected").val();
			var fecha_consumo = $("#fecha_consumo").val();
			var chofer        = $("#chofer option:selected").val();
			var chofer_aux    = $("#copiloto option:selected").val();
			
			var data_table1   = extract_data_table("table_report_1");
			var data_table2   = extract_data_table("table_report_2");
			var detalle2_mode = $("input[name='detalle2_modo']:checked").val();
			
			var isTermoking   = $('#isTermoking').is(':checked');
			isTermoking       = isTermoking === true?"1":"0";
			
			var hasCarreta    = $('#hasCarreta').is(':checked');
			hasCarreta        = hasCarreta === true?"1":"0";

            var tractor_implemento = "";
            var tractor_promedio = "";
            if(isTractorAndRequiresImplemento(idequipo)){
                tractor_implemento = $("#tractor_implemento option:selected").val();
                tractor_promedio = $("#tractor_promedio option:selected").val();
            }
			

			var parametros = {
                    "cod"    : 1,
                    "a"      : idequipo,
					"c"      : idgrifo,
					"b"      : idcentrocosto,
					"d"      : fecha_consumo,
					"e"      : <?=$_SESSION['id']?>,
					"f"      : data_table1,
					"g"      : data_table2,
					"id"     : idvale,
					"emitir" : emitir,
					"h"      : detalle2_mode,
					"i"      : chofer,
					"j"      : chofer_aux,
					"k"      : isTermoking,
					"hasCarreta" : hasCarreta,
                    "l"      : tractor_implemento,
                    "m"      : tractor_promedio
                };
			
			$.ajax({
						data:  parametros,
						url: "../phps/dvales_ajax.php",
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
	
		function modo_section2_change(){
			
			var band = $("input[name='detalle2_modo']:checked"). val();
			
			if(band == 1){//modo manual				
									
				$('#tr_section2_detail').css("display","none");				
				$('#centrocosto_row').css("display","table-row");	
				load_centrocsoto_by_equipo();				
				
			}else{//mofo automatico				
				
				$('#tr_section2_detail').css("display","table-row");		
				$('#centrocosto_row').css("display","none");							
					
				var oTable2    = $("#table_report_2").dataTable();
				oTable2.fnClearTable();
				oTable2.fnDraw();
				$("#centrocosto_d2").chosen();
					
			}
		}
		
		function detalleTableToArray(table_id){

			var rows = $("#"+table_id).dataTable().fnGetNodes();

			var detalle        = new Array();
			var dp_idmaterial  = 0;
			var dp_cantidad    = 0;
			var dp_centrocosto = 0;

			for (var i = 0; i < rows.length; i++) {

				try {
					dp_ids      = $(rows[i]).find("td:eq(0)").html();
					dp_cantidad = parseFloat($(rows[i]).find("input").val());
					
					if( table_id == "table_report_1" ){
						val1 = parseInt(dp_ids);
						
						var lista = {
							idmaterial  : val1,
							cantidad    : dp_cantidad
						};
						
					}else if( table_id  == "table_report_2"){
						 var idsarray = dp_ids.split("$");
						 val1 = parseInt(idsarray[0]);
						 val3 = parseInt(idsarray[1]);
						 
						 var lista = {
							idmaterial  : val1,
							cantidad    : dp_cantidad,
							centrocosto : val3
						};
					}

					detalle.push(lista);
				}
				catch (err) {
					alert("Error: " + err);
					return false;
				}
			}

			return detalle;
		}
		
		function extract_data_table(table_id){
			return JSON.stringify(detalleTableToArray(table_id));
		}
		
		function validaciones_formulario(){
			
			var idequipo      = $("#equipo option:selected").val();			
			var idgrifo       = $("#grifo option:selected").val();
			var fecha_consumo = $("#fecha_consumo").val();
			var idcentrocosto = $("#centrocosto option:selected").val();	
			var idchofer      = $("#chofer option:selected").val();	
			
			var rows_table1   = $("#table_report_1").dataTable().fnGetNodes().length;			
			var rows_table2   = $("#table_report_2").dataTable().fnGetNodes().length;
			var band          = $("input[name='detalle2_modo']:checked"). val();
			
			if(idequipo == 0 || idequipo == null){
				alert("Validación: Debe seleccionar Equipo.");
				$("#equipo").focus();
				return false;
			}else if( (idcentrocosto == 0 || idcentrocosto == null) && band == 1 ){
				alert("Validación: Debe seleccionar Centro de Costo.");
				$("#centrocosto").focus();
				return false;
			}else if(idcentrocosto == 3 && band == 1){
				alert("Validación: Debe ingresar asignacion manual, ya que el centro de costo NO EXISTE");
				$("#centrocosto").focus();
				return false;
			}else if(idchofer == 0 || idchofer == null){
				alert("Validación: Debe seleccionar Chofer.");
				$("#chofer").focus();
				return false;
			}else if(idgrifo == 0 || idgrifo == null){
				alert("Validación: Debe seleccionar Grifo.");
				$("#grifo").focus();
				return false;
			}else if(fecha_consumo == "" || idequipo == null){
				alert("Validación: Debe ingresar Fecha de Consumo.");
				$("#fecha_consumo").focus();
				return false;
			}else if(rows_table1 == 0){
				alert("Validación: Debe ingresar por lo menos un fila en Detalle 1.");
				return false;
			}else if(rows_table2 == 0 && band == 2 ){
				alert("Validación: Debe ingresar por lo menos un fila en Detalle 2.");
				return false;
			}else if( !validar_suma_100_asignaciones_por_productos() && band == 2){
				alert("Validación: Asignación por productos debe sumar 100%.");
				return false;
			}else if(isTractorAndRequiresImplemento(idequipo)){
                var idImplemento      = $("#tractor_implemento option:selected").val();
                var tractor_promedioo      = $("#tractor_promedio option:selected").val();
                if(idImplemento == "0" || idImplemento == "" || tractor_promedioo == "0" || tractor_promedioo == ""){
                    alert("Validación: Tractor require seleccion de Implemento y Promedio STD");
                    return false;
                }
            }
			
			return true;
		}
		
		function validar_suma_100_asignaciones_por_productos(){
			
			var listidsproductos = get_lista_productos_agregados_detalle1();
			var rows             = $("#table_report_2").dataTable().fnGetNodes();
			
			for(var i=0;i<listidsproductos.length;i++){
				var suma = 0;
				for(var j=0;j<rows.length;j++){              
					var ids        = $(rows[j]).find("td:eq(0)").html();					
					var idsarray   = ids.split("$");				
					var idmaterial = idsarray[0];
					if( idmaterial == listidsproductos[i]){
						var cantidad = parseFloat($(rows[j]).find("input").val());
						suma = suma + cantidad;						
					}
				}
			
				if(suma != 100){
					return false;
				}
			}

			return true;
		}
		
		function get_lista_productos_agregados_detalle1(){
			
			var rows      = $("#table_report_1").dataTable().fnGetNodes();
			var ids       = [];
			
			for(var i=0;i<rows.length;i++){              
                ids[i]      = $(rows[i]).find("td:eq(0)").html();
            }	
			
			return ids;
		}
		
		function go_back(){
			window.location.href = "index.php";
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
		 $(document).ready(function() {
                   $('.date-picker').datepicker();	
				   $(".chosen-select").chosen();
         });
		 
		 function show_imagen_popup(url){
			 window.open(
						"../files/vales/"+url, "window name",
						"height=400,width=400,modal=yes,alwaysRaised=yes");
		 }
		 
		 function deshacer_emision(idvale){
			 
			var parametros = {
				"cod" : 4,
				"idvale" : idvale
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
						location.reload();
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
		 
		 function enable_edit_kilometraje(){
			$('#element_ae').css("display","none");
			$('#element_se').css("display","inline-block");
			$('#element_ce').css("display","inline-block");
			$("#kilometraje_user").prop('readonly', false);
			$("#obervacion_user").prop('readonly', false);
			$("#obervacion_user").css("background-color","white");
			$("#kilometraje_user").focus();
		 }
		 function cancel_new_kilometraje(){
			location.reload();			
		 }
		 function disabled_edit_kilometraje(){
			$('#element_ae').css("display","inline-block");
			$('#element_se').css("display","none");
			$('#element_ce').css("display","none");
			$("#kilometraje_user").prop('readonly', true);
			$("#obervacion_user").prop('readonly', true);
			$("#obervacion_user").css("background-color","#f5f5f5");
		 }
		 function save_new_kilometraje(idvale){
			 
			 <?php
				if($isLastValeConsumidoByEquipo){
					echo 'if(confirm("¿Seguro que desea actualizar el valor? Actualizar el valor de kilometraje/hodómetro implicará actualizar el valor actual de kilometraje/hodómetro del equipo.")){';
					echo '}else{';
					echo 'return false;';
					echo '}';
				}
			 ?>
			
			var new_value = $("#kilometraje_user").val();
			var new_observacion = $("#obervacion_user").val();
				
			if(isNaN(new_value) || parseFloat(new_value) < 0.0){
				alert("Validación: Solo números positivos son permitodos");
				$("#kilometraje_user").focus();
				return false;
			}
			
			var parametros = {
				"cod" : 5,
				"idvale" : idvale,
				"new_value" : new_value,
				"new_observacion" : new_observacion,
				"idusuario" : <?=$_SESSION['id']?>,
				"isLastValeConsumidoByEquipo" : <?=($isLastValeConsumidoByEquipo?1:0)?>,
				"idequipo" : <?=$ultimo_vale_by_equipo['vale_eqnro']?>
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
						$("#kilometraje_user").val(respuesta.data);
						alert("Valor actualizado correctamente");
						disabled_edit_kilometraje();
						$("#span_historial_1").css("display","none");
						$("#span_historial_2").css("display","inline-block");
						$("#equipo_lastkilometraje_html").html(new_value);
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
		 
		 function kilometraje_verhistorial(idvale){
			 window.open("ver_historial_kilometraje.php?idvale="+idvale,"Historial",'width=900,height=400,toolbar=0,menubar=0,location=0');
		 }
		 
		 function tsomobile_verlog(idvale){
			 window.open("ver_logs_tsomobile.php?idvale="+idvale,"Historial",'width=900,height=400,toolbar=0,menubar=0,location=0');
		 }
		 
		 function rfcconsumo_verlog(idvale){
			 window.open("ver_logs_rfcconsumo.php?idvale="+idvale,"Historial",'width=900,height=400,toolbar=0,menubar=0,location=0');
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
