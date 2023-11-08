<?php

include("../plantilla1.php");
include("../phps/dvales_reportes.php");

$opt1          				= "checked='checked'";
$opt2          				= "";
$chk_registrado 			= "";
$chk_emitido  				= "";
$chk_consumido  			= "";
$chk_anulado   				= "";
$fechaIni      				= "";
$fechaFin      				= "";
$observacion                = "";
$chk_emitido				= "";
$chk_placa 					= "";
$chk_chofer					= "";
$chk_centrocosto 			= "";
$chk_grifo		 			= "";
$chk_observacion			= "";
$select_1					= "";
$select_2					= "";
$select_3					= "";
$select_4                   = "";

$placas						= getAllPlacasToCombobox();
$choferes					= getAllChoferesToCombobox();
$centrocosto				= getAllCentroCostoToCombobox();
$grifos						= getAllGrifosToCombobox();


if(isset($_REQUEST['optSelec'])){

    if($_REQUEST['optSelec'] == 1){
		
		 $opt1 = "checked='checked'";
		 
        $fechaIni = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaIni']." 00:00:00"):"";
        $fechaFin = isset( $_REQUEST['fechaIni'])?($_REQUEST['fechaFin']." 23:59:59"):"";    
		
		$chk_registrado   		= isset($_REQUEST['chk_registrado'])?"checked='checked'":"";
		$chk_emitido 			= isset($_REQUEST['chk_emitido'])?"checked='checked'":"";
		$chk_consumido 			= isset($_REQUEST['chk_consumido'])?"checked='checked'":"";
		$chk_anulado   			= isset($_REQUEST['chk_anulado'])?"checked='checked'":"";
		
		$hide_option1= "";
		$hide_option2= " style='display:none' ";				
		
    }else if($_REQUEST['optSelec'] == 2){		
       
        $opt2 	= "checked='checked'";		
		$select_1 				= isset($_REQUEST['select_1'])?$_REQUEST['select_1']:"";
		$select_2 				= isset($_REQUEST['select_2'])?$_REQUEST['select_2']:"";
		$select_3 				= isset($_REQUEST['select_3'])?$_REQUEST['select_3']:"";
		$select_4 				= isset($_REQUEST['select_4'])?$_REQUEST['select_4']:"";
		$chk_placa 				= $_REQUEST['rad_parametro']==1?"checked='checked'":"";
		$chk_chofer				= $_REQUEST['rad_parametro']==2?"checked='checked'":"";
		$chk_centrocosto 		= $_REQUEST['rad_parametro']==3?"checked='checked'":"";
		$chk_grifo		 		= $_REQUEST['rad_parametro']==4?"checked='checked'":"";
		$chk_observacion		= $_REQUEST['rad_parametro']==5?"checked='checked'":"";
		$observacion			= isset($_REQUEST['observacion'])?$_REQUEST['observacion']:"";
		
		$hide_option1= " style='display:none' ";
		$hide_option2= "";		
    }

    $data = getAllMiValesWithFilters($_REQUEST['optSelec'],$fechaIni,$fechaFin,$chk_registrado,$chk_emitido,$chk_consumido,$chk_anulado,$chk_placa,$chk_chofer,$chk_centrocosto,$chk_grifo,$chk_observacion,$select_1,$select_2,$select_3,$select_4,$observacion,false);
}else{
	$data = array();
	$hide_option1= "";
	$hide_option2= " style='display:none' ";
	$hide_option3= " style='display:none' ";
	$chk_placa 	 = "checked='checked'";
    
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
								<input type="checkbox" name="chk_registrado" id="chk_registrado" value="1" onclick="disabled_estado(1)"<?=$chk_registrado?>>Registrado
								<input type="checkbox" name="chk_emitido" id="chk_emitido" value="1" onclick="disabled_estado(1)"<?=$chk_emitido?>>Emitido
								<input type="checkbox" name="chk_consumido" id="chk_consumido" value="1" onclick="disabled_estado(1)"<?=$chk_consumido?>>Consumido
								<input type="checkbox" name="chk_anulado" id="chk_anulado" value="1" onclick="disabled_estado(2)"<?=$chk_anulado?>>Anulado
								
								
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
								<input type="radio" name="rad_parametro" id="chk_placa" value="1" onclick=""<?=$chk_placa?>>Placa&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<select name="select_1">
								 <?php
									foreach($placas as $item){
										
										$select = "";
										if($select_1 == $item['id'])
											$select = "selected='selected'";
											
										$desc_equipo = !empty($item['descripcion'])?$item['descripcion']:$item['equnr'];
										
										echo "<option  ".$select." value='".$item['id']."'>".$desc_equipo."</option>";
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
								<input type="radio" name="rad_parametro" id="chk_chofer" value="2" onclick=""<?=$chk_chofer?>>Chofer&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<select name="select_2">
								 <?php
									foreach($choferes as $item){
										
										$select = "";
										if($select_2 == $item['id'])
											$select = "selected='selected'";
										
										echo "<option  ".$select." value='".$item['id']."'>".$item['descripcion']."</option>";
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
								<input type="radio" name="rad_parametro" id="chk_centrocosto" value="3" onclick=""<?=$chk_centrocosto?>>Centro Costo&nbsp
								<select name="select_3">
								  <?php
									foreach($centrocosto as $item){
										
										$select = "";
										if($select_3 == $item['descripcion2'])
											$select = "selected='selected'";
										
										echo "<option ".$select." value='".$item['descripcion2']."'>".$item['descripcion']."</option>";
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
								<input type="radio" name="rad_parametro" id="chk_grifo" value="4" onclick=""<?=$chk_grifo?>>Grifo&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<select name="select_4">
								  <?php
									foreach($grifos as $item){
										
										$select = "";
										if($select_4 == $item['id'])
											$select = "selected='selected'";
										
										echo "<option ".$select." value='".$item['id']."'>".$item['descripcion']."</option>";
									}
								 ?>
								</select>
								
								
							</div>
					</div>
					</td>
				</tr>		

				<tr <?=$hide_option2?>  id="tr_opt2_5">
                   <td colspan="3">                   
                        <div class="control-group">                          
                            <div class="controls">
								<input type="radio" name="rad_parametro" id="chk_observacion" value="5" onclick=""<?=$chk_observacion?>>Observación&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    <input class="span5" value="<?php if(isset($_REQUEST['observacion']))echo $_REQUEST['observacion'];?>"
                                           name="observacion" id="observacion" type="text" AUTOCOMPLETE="off"/>
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
                            style="width: 30px;">ID
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha Registro
                        </th>
						 <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha Consumo Maximo
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Placa
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Material
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Usuario Registra
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
                            echo "<td><span class='label label-success'>".$d['id']."</span></td>";
                            echo "<td class=\"id\">".date_format($d['registra_fecha'],"Y/m/d H:i:s")."</td>";
                            echo "<td class=\"fecha\">".date_format($d['fecha_max_consumo'],"Y/m/d H:i:s")."</td>";
							echo "<td class=\"proveedor\">".$d['placa']."</td>";
							echo "<td class=\"proveedor\">".$d['material']."</td>";
							echo "<td class=\"tipocontrato\">".$d['registra_usuario']."</td>";
							echo "<td class=\"estado\">".$d['estado']."</td>";	
							
							echo $EDITAR_HTML_CODE;
							
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
    $( '#listar_vales_reportes' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'>Reportes </a> ");

    function descargar(){
        window.open("descargar.php?<?=$_SERVER['QUERY_STRING']?>");
    }
</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {
       var oTable1 = $('#table_report').dataTable({
            "aoColumns": [
			   {"sClass": "hide_columns"},
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
		var id = $(this).parent().parent().parent().children("td.hide_columns").text();
		location.href = "../vales_planner/create.php?id="+id+"&mode=edit";
	});
	
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
			
			var modo      = document.querySelector('input[name="rad_parametro"]:checked').value;

						
			var observacion = document.getElementById("observacion").value;
			if(modo == 5 && observacion == ""){
				alert("Validación: Debe ingresar observacion");
				return false;
			}			
			
		}
		
		return true;
	}
	
	function reset_filters(){
		
		document.getElementById("chk_registrado").checked   			= false;
		document.getElementById("chk_emitido").checked   				= false;
		document.getElementById("chk_consumido").checked   				= false;		
		document.getElementById("chk_anulado").checked   				= false;	
		document.getElementById("fechaIni").value 						= "";
		document.getElementById("fechaFin").value 						= "";
		
		
		$("#optSelec1").prop('checked',true);
		option_selected(1);
		$("#table_report").dataTable().fnClearTable();
		
		return false;
	}
	
	function disabled_estado(option){
		
		if(option == 1){		
			document.getElementById("chk_anulado").checked   				= false;
		}else if(option == 2){
			document.getElementById("chk_registrado").checked   			= false;
			document.getElementById("chk_emitido").checked   				= false;
			document.getElementById("chk_consumido").checked   				= false;		
		
		}
	}

</script>

</body>
</html>
