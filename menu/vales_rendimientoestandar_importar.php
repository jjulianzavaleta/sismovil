<?php

include("../plantilla1.php");

$NOMBRE_SHOW = "Vale";
$NOMBRE_SHOW_PLURAL = "Vales";

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <div class="row-fluid">
		<form class="form-horizontal" target="_blank" id="validation-form_nuevo" method="post" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" action="vales_rendimientoestandar_procesar.php">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">							
			<tr align="center">															
				<td width="50%"> 
					<label class="control-label" for="archivo">Archivo rendimiento estandar:</label>
					<div class="control-group">
						<input type="file" id="archivo" name="archivo" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />		
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<button type="button" class="btn btn-success" style= "float: left;position: relative;left: 50%;" onclick="send_data()">
									 <i class="icon-plane icon-white"></i> Procesar
					</button>
				</td>
				<td width="50%"></td>
			</tr>
		</table>
		</form>
		</div>
    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->


<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>

<script>
    $( '#dbasesVales' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='bases_vales.php'>Bases Vales</a>");
</script>


<!--inline scripts related to this page-->
<script type="text/javascript">

 function send_data(){
	 var file_value = document.getElementById('archivo').value;
	 
	 if(file_value == "" || file_value == null){
		alert("Validación: Debe seleccionar archivo");
		document.getElementById('archivo').focus();
		return false;
	 }
	 
	 document.getElementById("validation-form_nuevo").submit();
 }

</script>

</body>
</html>
