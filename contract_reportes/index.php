<?php

include("../plantilla1.php");
include("../phps/dcontract_contratos.php");
include("../phps/dcontract_reportes.php");

$opt1          				= "checked='checked'";
$opt2          				= "";
$opt3						= "";
$chk_registrado 			= "";
$chk_val_jefarea 			= "";
$chk_val_legal_acepta		= "";
$chk_val_jef_log 			= "";
$chk_pendelaboracion  		= "";
$chk_pendaprobacionusuario  = "";
$chk_colectarfirmas 		= "";
$chk_vigente   				= "";
$chk_concluido 				= "";
$chk_proceso   				= "";
$chk_anulado   				= "";
$chk_vence15   				= "";
$chk_vence30   				= "";
$chk_vence60   				= "";
$chk_vence90   				= "";
$chk_vence365  				= "";
$fechaIni      				= "";
$fechaFin      				= "";
$codigo        				= "";
$alcance                    = "";
$chk_porempresa				= "";
$chk_porproveedor 			= "";
$chk_portipocontrato 		= "";
$chk_porcodigo				= "";
$chk_poralcance				= "";
$select_1					= "";
$select_2					= "";
$select_3					= "";

$empresas					= getAllEmpresasToCombobox();
$proveedores				= getAllProveedoresToCombobox();
$tipocontratos				= getAllTipoContratoToCombobox();


$permisos = getPermissionsUsuarioContract($_SESSION["username"]);
if($permisos[0]['permission_aprobar']=="1"){
	$userId = 0;
}else{
	$userId = $_SESSION['id'];
}

if(isset($_REQUEST['optSelec'])){

    if($_REQUEST['optSelec'] == 1){
		
		 $opt1 = "checked='checked'";
		 
        $fechaIni = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaIni']." 00:00:00"):"";
        $fechaFin = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaFin']." 23:59:59"):"";    
		
		$chk_vigente   				= isset($_REQUEST['chk_vigente'])?"checked='checked'":"";
		$chk_concluido 				= isset($_REQUEST['chk_concluido'])?"checked='checked'":"";
		$chk_registrado 			= isset($_REQUEST['chk_registrado'])?"checked='checked'":"";
		$chk_val_jefarea 			= isset($_REQUEST['chk_val_jefarea'])?"checked='checked'":"";
		$chk_val_legal_acepta		= isset($_REQUEST['chk_val_legal_acepta'])?"checked='checked'":"";
		$chk_val_jef_log 			= isset($_REQUEST['chk_val_jef_log'])?"checked='checked'":"";
		$chk_pendelaboracion 		= isset($_REQUEST['chk_pendelaboracion'])?"checked='checked'":"";
		$chk_pendaprobacionusuario 	= isset($_REQUEST['chk_pendaprobacionusuario'])?"checked='checked'":"";
		$chk_colectarfirmas			= isset($_REQUEST['chk_colectarfirmas'])?"checked='checked'":"";
		$chk_anulado   				= isset($_REQUEST['chk_anulado'])?"checked='checked'":"";
		
		$hide_option1= "";
		$hide_option2= " style='display:none' ";
		$hide_option3= " style='display:none' ";
		$chk_porempresa="checked='checked'";
		
    }else if($_REQUEST['optSelec'] == 2){		
       
        $opt2 	= "checked='checked'";
		
		$chk_porempresa			= isset($_REQUEST['rad_parametro_1'])?"checked='checked'":"";
		$chk_porproveedor		= isset($_REQUEST['rad_parametro_2'])?"checked='checked'":"";
		$chk_portipocontrato	= isset($_REQUEST['rad_parametro_3'])?"checked='checked'":"";
		$chk_porcodigo			= isset($_REQUEST['rad_parametro_4'])?"checked='checked'":"";
		$chk_poralcance			= isset($_REQUEST['rad_parametro_5'])?"checked='checked'":"";
		$select_1 				= isset($_REQUEST['select_1'])?$_REQUEST['select_1']:"";
		$select_2 				= isset($_REQUEST['select_2'])?$_REQUEST['select_2']:"";
		$select_3 				= isset($_REQUEST['select_3'])?$_REQUEST['select_3']:"";
		$codigo 				= isset($_REQUEST['codigo'])?$_REQUEST['codigo']:"";
		$alcance 				= isset($_REQUEST['alcance'])?$_REQUEST['alcance']:"";
		
		$hide_option1= " style='display:none' ";
		$hide_option2= "";
		$hide_option3= " style='display:none' ";
		
    }else if($_REQUEST['optSelec'] == 3){
		
		$opt3 	= "checked='checked'";
		
		$chk_vence15   = isset($_REQUEST['chk_vence15'])?"checked='checked'":"";
		$chk_vence30   = isset($_REQUEST['chk_vence30'])?"checked='checked'":"";
		$chk_vence60   = isset($_REQUEST['chk_vence60'])?"checked='checked'":"";
		$chk_vence90   = isset($_REQUEST['chk_vence90'])?"checked='checked'":"";
		$chk_vence365  = isset($_REQUEST['chk_vence365'])?"checked='checked'":"";
		
		$hide_option1= " style='display:none' ";
		$hide_option2= " style='display:none' ";
		$hide_option3= "";
		$chk_porempresa="checked='checked'";
	}

    $data = getAllMiContratosWithFilters($_REQUEST['optSelec'],$fechaIni,$fechaFin,$chk_vigente,$chk_concluido,$chk_registrado,$chk_val_jefarea,$chk_val_legal_acepta,$chk_val_jef_log,$chk_pendelaboracion,$chk_pendaprobacionusuario,$chk_colectarfirmas,$chk_anulado,$chk_porempresa,$chk_porproveedor,$chk_portipocontrato,$chk_porcodigo,$chk_poralcance,$select_1,$select_2,$select_3,$codigo,$chk_vence15,$chk_vence30,$chk_vence60,$chk_vence90,$chk_vence365,$alcance,$userId);
}else{
	$data = array();
	$hide_option1= "";
	$hide_option2= " style='display:none' ";
	$hide_option3= " style='display:none' ";
	$chk_porempresa="checked='checked'";
    
}


?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
		
        <div  style="float: right">
            <a id="buscar" href="#" class="btn btn-success" onclick="descargar()"
               alt="Cotizaciones" title="Descargar Cotizaciones">
                <i class="icon-download"></i>
            </a>
        </div>
		
        <form action="index.php" method="get" class="form-horizontal" onsubmit="return validar()">
            <table>
                <thead></thead>
                <tbody>
				
				<tr>
					<td colspan="4"><input <?=$opt1?> type="radio" name="optSelec" id="optSelec1" value="1" onclick="option_selected(1)" />Por estado</td>
				</tr>
				
				<tr <?=$hide_option1?> id="tr_opt1_1">
                    <td>
                        <div class="control-group">
                            <label class="control-label" for="fechaIni">Fecha inicio :</label>

                            <div class="controls">
                                <div class="row-fluid input-append date">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <input class="span6 date-picker" AUTOCOMPLETE="off"
                                           value="<?php if(isset($_REQUEST['fechaIni']))echo $_REQUEST['fechaIni'];?>"
                                           name="fechaIni" id="fechaIni" type="text"
                                           data-date-format="yyyy-mm-dd"/>
                                </div>
                            </div>							
                        </div>
                    </td>
                    <td>
                        <div class="control-group">
                            <label class="control-label" for="fechaFin">Fecha fin :</label>

                            <div class="controls">
                                <div class="row-fluid input-append date">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <input class="span6 date-picker" AUTOCOMPLETE="off"
                                           value="<?php if(isset($_REQUEST['fechaFin']))echo $_REQUEST['fechaFin'];?>"
                                           name="fechaFin" id="fechaFin" type="text"
                                           data-date-format="yyyy-mm-dd"/>
                                </div>
                            </div>							
                        </div>
                    </td>
                </tr>
				
				<tr <?=$hide_option1?>  id="tr_opt1_2">
					<td colspan="3">
					<div class="control-group">
							<div class="controls">								
								<input type="checkbox" name="chk_registrado" id="chk_registrado" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_registrado?>>Registrados
								<input type="checkbox" name="chk_val_jefarea" id="chk_val_jefarea" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_val_jefarea?>>Pend. validación preliminar jefe área
								<input type="checkbox" name="chk_val_jef_log" id="chk_val_jef_log" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_val_jef_log?>>Pend. validación preliminar jefe logística
								<input type="checkbox" name="chk_val_legal_acepta" id="chk_val_legal_acepta" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_val_legal_acepta?>>En espera legal acepta elaboración
								<input type="checkbox" name="chk_pendelaboracion" id="chk_pendelaboracion" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_pendelaboracion?>>Pend. de elaboración
								<input type="checkbox" name="chk_pendaprobacionusuario" id="chk_pendaprobacionusuario" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_pendaprobacionusuario?>>Pend. validación final
								<input type="checkbox" name="chk_colectarfirmas" id="chk_colectarfirmas" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_colectarfirmas?>>Recolectar firmas
								<input type="checkbox" name="chk_vigente" id="chk_vigente" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_vigente?>>Vigentes
								<input type="checkbox" name="chk_concluido" id="chk_concluido" value="1" onclick="desabilitar_filter_chk(1)"<?=$chk_concluido?>>Concluidos								
								<input type="checkbox" name="chk_anulado" id="chk_anulado" value="1" onclick="desabilitar_filter_chk(2)"<?=$chk_anulado?>>Anulados
								
								
							</div>
					</div>
					</td>
				</tr>
				
				<tr>
					<td colspan="4"><input <?=$opt2?> type="radio" name="optSelec" id="optSelec2" value="2"  onclick="option_selected(2)"/>Por parámetro</td>
				</tr>
				
				<tr <?=$hide_option2?>  id="tr_opt2_1">
					<td colspan="3">
					<div class="control-group">
							<div class="controls">								
								<input type="checkbox" name="rad_parametro_1" id="chk_porempresa" value="1" onclick=""<?=$chk_porempresa?>>Empresa&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<select name="select_1">
								 <?php
									foreach($empresas as $item){
										
										$select = "";
										if($select_1 == $item['id'])
											$select = "selected='selected'";
										
										echo "<option  ".$select." value='".$item['id']."'>".$item['descripcion']."</option>";
									}
								 ?>
								</select>
								
								
							</div>
					</div>
					</td>
				</tr>
				
				<tr <?=$hide_option2?>  id="tr_opt2_2">
					<td colspan="3">
					<div class="control-group">
							<div class="controls">								
								<input type="checkbox" name="rad_parametro_2" id="chk_porproveedor" value="2" onclick=""<?=$chk_porproveedor?>>Proveedor&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<select name="select_2">
								 <?php
									foreach($proveedores as $item){
										
										$select = "";
										if($select_2 == $item['idproveedor'])
											$select = "selected='selected'";
										
										echo "<option  ".$select." value='".$item['idproveedor']."'>".$item['razon_social']."</option>";
									}
								 ?>
								</select>
								
								
							</div>
					</div>
					</td>
				</tr>
				
				<tr <?=$hide_option2?>  id="tr_opt2_3">
					<td colspan="3">
					<div class="control-group">
							<div class="controls">								
								<input type="checkbox" name="rad_parametro_3" id="chk_portipocontrato" value="3" onclick=""<?=$chk_portipocontrato?>>Tipo de Contrato&nbsp
								<select name="select_3">
								  <?php
									foreach($tipocontratos as $item){
										
										$select = "";
										if($select_3 == $item['id'])
											$select = "selected='selected'";
										
										echo "<option ".$select." value='".$item['id']."'>".$item['descripcion']."</option>";
									}
								 ?>
								</select>
								
								
							</div>
					</div>
					</td>
				</tr>
				
				<tr <?=$hide_option2?>  id="tr_opt2_4">
                   <td colspan="3">                   
                        <div class="control-group">                          
                            <div class="controls">
								<input type="checkbox" name="rad_parametro_4" id="chk_porcodigo" value="4" onclick=""<?=$chk_porcodigo?>>Código&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    <input class="span5" value="<?php if(isset($_REQUEST['codigo']))echo $_REQUEST['codigo'];?>"
                                           name="codigo" id="codigo" type="text" AUTOCOMPLETE="off"/>
                                </div>                          
                        </div>
                    </td>                    
                </tr>
				
				<tr <?=$hide_option2?>  id="tr_opt2_5">
                   <td colspan="3">                   
                        <div class="control-group">                          
                            <div class="controls">
								<input type="checkbox" name="rad_parametro_5" id="chk_poralcance" value="5" onclick=""<?=$chk_poralcance?>>Alcance&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    <input class="span5" value="<?php if(isset($_REQUEST['alcance']))echo $_REQUEST['alcance'];?>"
                                           name="alcance" id="alcance" type="text" AUTOCOMPLETE="off"/>
                                </div>                          
                        </div>
                    </td>                    
                </tr>
				
				<tr>
					<td colspan="4"><input <?=$opt3?> type="radio" name="optSelec" id="optSelec3" value="3"  onclick="option_selected(3)" />Por fecha de vencimiento</td>
				</tr>
				
				<tr <?=$hide_option3?>  id="tr_opt3_1">
					<td colspan="3">
					<div class="control-group">
							<div class="controls">								
								<input type="checkbox" name="chk_vence15" id="chk_vence15" value="1" onclick="desabilitar_filter_chk(0)"<?=$chk_vence15?>>Vence en 15 días
								<input type="checkbox" name="chk_vence30" id="chk_vence30" value="1" onclick="desabilitar_filter_chk(0)"<?=$chk_vence30?>>Vence en 30 días
								<input type="checkbox" name="chk_vence60" id="chk_vence60" value="1" onclick="desabilitar_filter_chk(0)"<?=$chk_vence60?>>Vence en 60 días
								<input type="checkbox" name="chk_vence90" id="chk_vence90" value="1" onclick="desabilitar_filter_chk(0)"<?=$chk_vence90?>>Vence en 90 días
								<input type="checkbox" name="chk_vence365" id="chk_vence365" value="1" onclick="desabilitar_filter_chk(0)"<?=$chk_vence365?>>Vence en 1 año
							</div>
					</div>
					</td>
				</tr>

                <tr>
                    <td>
                        <button>Filtrar</button>
						<button onclick="return reset_filters()">Reset</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>

        <div class="row-fluid">
            <h5 class="header smaller lighter blue">Datos</h5>


            <div id="table_report_wrapper" class="dataTables_wrapper" role="grid">
                <table id="table_report" class="table table-striped table-bordered table-hover dataTable"
                       aria-describedby="table_report_info">
                    <thead>
                    <tr role="row">
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">ID
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Código
                        </th>
						 <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Empresa
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Proveedor
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Monto
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Tipo Contrato
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Estado
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php
					
                    if(!empty($data) && $data != false){

                        foreach($data as $d){							
                            echo "<tr class=\"odd\">";	
                            echo "<td>".$d['id']."</td>";
                            echo "<td class=\"id\"><span class='label label-success'>".$d['datosgenerales_codigo']."</span></td>";
                            echo "<td class=\"fecha\">".$d['fecha_formateada']."</td>";
							echo "<td class=\"empresa\">".$d['nombre_empresa']."</td>";
							echo "<td class=\"proveedor\">".$d['nombre_proveedor']."</td>";
							echo "<td class=\"monto\">".$d['monto']."</td>";
							echo "<td class=\"tipocontrato\">".$d['nombre_tipocontrato']."</td>";
							$warning_icon = display_warning_icon_when_action_needed($d['estado_html']);
							echo "<td class=\"estado\">".$warning_icon.$d['estado_html']."</td>";	
							
							if($d['anulado'] == 1 || $d['datosgenerales_estado'] == 4){
								echo $EDITAR_HTML_CODE;
							}else{
								echo $EDITAR_HTML_CODE;
								
							}
							
							echo "</tr>";
                        }
                    }

                    ?>

                    </tbody>
                </table>
            </div>
                
            </div>
        </div>
        <!--/#page-content-->



    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->


<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>


</div>

<script src="../assets/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="../assets/css/jquery.autocomplete.css" />

<script>
    $( '#listar_contract_reportes' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'>Reportes </a> ");

    function descargar(){
        window.open("descargar.php?userId=<?=$userId?>&<?=$_SERVER['QUERY_STRING']?>");
    }
</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {
       var oTable1 = $('#table_report').dataTable({
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column"},
                null,
                null,
				null,
				null,
				null,
				null,
				null,
                { "bSortable": false }
            ] ,
            "aaSorting": [
               
            ]
        });

        $('.date-picker').datepicker();


    });
	
	$("a","#table_report").on("click",function  () {
		var id = $(this).parent().parent().parent().children("td.hide_column").text();
		location.href = "../contract_miscontratos/create.php?id="+id+"&mode=edit";
	});
	
	
	function desabilitar_filter_chk(modo){
		
		var chk_registrado   			= document.getElementById("chk_registrado");
		var chk_pendelaboracion 		= document.getElementById("chk_pendelaboracion");
		var chk_pendaprobacionusuario   = document.getElementById("chk_pendaprobacionusuario");
		var chk_val_legal_acepta   		= document.getElementById("chk_val_legal_acepta");
		var chk_colectarfirmas   		= document.getElementById("chk_colectarfirmas");
		var chk_vigente   				= document.getElementById("chk_vigente");
		var chk_concluido   			= document.getElementById("chk_concluido");
		var chk_anulado   				= document.getElementById("chk_anulado");
		var chk_val_jefarea				= document.getElementById("chk_val_jefarea");
		var chk_val_jef_log				= document.getElementById("chk_val_jef_log");
		
		if(modo == 1){//chk_anulado
		
			chk_anulado.checked 				= false;			
			
		}else if(modo == 2){//chk chk_vence15
			
			chk_registrado.checked   			= false;
			chk_pendelaboracion.checked 		= false;
			chk_pendaprobacionusuario.checked   = false;
			chk_val_legal_acepta.checked		= false;
			chk_colectarfirmas.checked   		= false;
			chk_vigente.checked   				= false;
			chk_concluido.checked 				= false;
			chk_val_jefarea.checked				= false;
			chk_val_jef_log.checked				= false;
			
		}
		
	}
	
	function option_selected(mode_show){
		
		if(mode_show == 1){
			$("#tr_opt1_1").css("display", "table-row");
			$("#tr_opt1_2").css("display", "table-row");
			$("#tr_opt2_1").css("display", "none");
			$("#tr_opt2_2").css("display", "none");
			$("#tr_opt2_3").css("display", "none");
			$("#tr_opt2_4").css("display", "none");
			$("#tr_opt2_5").css("display", "none");
			$("#tr_opt3_1").css("display", "none");
		}else if(mode_show == 2){
			$("#tr_opt1_1").css("display", "none");
			$("#tr_opt1_2").css("display", "none");
			$("#tr_opt2_1").css("display", "table-row");
			$("#tr_opt2_2").css("display", "table-row");
			$("#tr_opt2_3").css("display", "table-row");
			$("#tr_opt2_4").css("display", "table-row");
			$("#tr_opt2_5").css("display", "table-row");
			$("#tr_opt3_1").css("display", "none");
		}else if(mode_show == 3){
			$("#tr_opt1_1").css("display", "none");
			$("#tr_opt1_2").css("display", "none");
			$("#tr_opt2_1").css("display", "none");
			$("#tr_opt2_2").css("display", "none");
			$("#tr_opt2_3").css("display", "none");
			$("#tr_opt2_4").css("display", "none");
			$("#tr_opt2_5").css("display", "none");
			$("#tr_opt3_1").css("display", "table-row");
		}
	}
	
	function validar(){
		
		var opt      = document.querySelector('input[name="optSelec"]:checked').value;		
		
		if(opt == 1){
			
			var fechaIni = document.getElementById("fechaIni").value;
			var fechaFin = document.getElementById("fechaFin").value;
			
			if( ( fechaIni != "" & fechaFin == "" ) || ( fechaIni == "" & fechaFin != "" ) ){
				alert("Validación: Fecha Inicio y Fecha Fin, ambas, son requeridas");
				return false;
			}
			
		}else if(opt == 2){
			
			var isChecked4      = document.getElementById('chk_porcodigo').checked;
			var codigo = document.getElementById("codigo").value;
			if(isChecked4 && codigo == ""){
				alert("Validación: Debe ingresar un codigo");
				return false;
			}

            var isChecked5      = document.getElementById('chk_poralcance').checked;
			var alcance = document.getElementById("alcance").value;
			if(isChecked5 && alcance == ""){
				alert("Validación: Debe ingresar alcance");
				return false;
			}
			
			
		}else if(opt == 3){
			
			var chk_15_dias = document.getElementById("chk_vence15");
			var chk_30_dias = document.getElementById("chk_vence30");
			var chk_60_dias = document.getElementById("chk_vence60");
			var chk_90_dias = document.getElementById("chk_vence90");
			var chk_365_dias = document.getElementById("chk_vence365");
			
			if( chk_15_dias.checked == false && chk_30_dias.checked == false 
   			   && chk_60_dias.checked == false && chk_90_dias.checked == false
			   && chk_365_dias.checked == false){
				alert("Validación: Debe seleccionar un filtro para días vencidos");
				return false;
			}
		}
		
		return true;
	}
	
	function reset_filters(){
		
		document.getElementById("chk_registrado").checked   			= false;
		document.getElementById("chk_val_jefarea").checked   			= false;
		document.getElementById("chk_val_jef_log").checked   			= false;
		document.getElementById("chk_pendelaboracion").checked   		= false;
		document.getElementById("chk_pendaprobacionusuario").checked   	= false;
		document.getElementById("chk_colectarfirmas").checked   		= false;
		document.getElementById("chk_val_legal_acepta").checked   		= false;
		document.getElementById("chk_vigente").checked   				= false;
		document.getElementById("chk_concluido").checked   				= false;
		document.getElementById("chk_anulado").checked   				= false;
		document.getElementById("chk_vence15").checked   				= false;
		document.getElementById("chk_vence30").checked   				= false;
		document.getElementById("chk_vence60").checked   				= false;
		document.getElementById("chk_vence90").checked   				= false;
		document.getElementById("chk_vence365").checked   				= false;
		document.getElementById("fechaIni").value 						= "";
		document.getElementById("fechaFin").value 						= "";
		document.getElementById("codigo").value 						= "";
        document.getElementById("alcance").value                        = "";
		
		$("#optSelec1").prop('checked',true);
		$("#chk_porempresa").prop('checked',true);
		option_selected(1);
		$("#table_report").dataTable().fnClearTable();
		
		return false;
	}

</script>

</body>
</html>
