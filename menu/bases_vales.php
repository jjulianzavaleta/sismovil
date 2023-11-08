<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 19/08/2015
 * Time: 09:54 PM
 */
include("../plantilla1.php");
?>


<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
	<tr>
		<td>

			<button type="button" class="btn btn-mini btn-success" onclick="LoadData('centro_costo')"
					 title="Load centro costo">RFC centro costo
					 <i title="Load centro costo" class="icon-refresh icon-only bigger-150"></i>
			</button>

            <button type="button" class="btn btn-mini btn-success" onclick="LoadData('choferes')"
                    title="Load choferes">RFC choferes
                <i title="Load choferes" class="icon-refresh icon-only bigger-150"></i>
            </button>

            <button type="button" class="btn btn-mini btn-success" onclick="LoadData('equipos')"
                    title="Loadequipos">RFC equipos
                <i title="Load equipos" class="icon-refresh icon-only bigger-150"></i>
            </button>

            <button type="button" class="btn btn-mini btn-success" onclick="LoadData('estaciones')"
                    title="Load estaciones">RFC estaciones
                <i title="Load estaciones" class="icon-refresh icon-only bigger-150"></i>
            </button>

			<button type="button" class="btn btn-mini btn-success" onclick="LoadRendimientoEstandar()"
					 title="Cargar rendimiento estandar equipos">Rendimiento estandar
					 <i title="Cargar rendimiento estpandar equipos" class="icon-book icon-only bigger-150"></i>
			</button>
		</td>
	</tr>
</table>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
		 <a href="../vales_usuario/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-key"></i>
            Permisos
        </a>
		<a href="../vales_grifos/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-beaker"></i>
            Grifos
        </a>
		<a href="../vales_materiales/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-barcode"></i>
            Materiales
        </a>
		<a href="../vales_centroweb/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-home"></i>
            Centro Web
        </a>
		<a href="../vales_equipoweb/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-truck"></i>
            Equipo Web
        </a>
		<a href="../vales_usuarioweb/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-tablet"></i>
            Usuario Web
        </a>
        <!--/#page-content-->

    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->

<!--
<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>
-->
<!--MODAL- NUEVO PRODUCTO-->
<!-- Modal -->



<script>
    $( '#dbasesVales' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='bases_vales.php'>Bases Vales</a>");
	
	function LoadData(param){
        var url = getJobName(param);
		window.open('../jobs/'+url,'popUpWindow','height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}
    function getJobName(param){
        if(param == "centro_costo")
            return "main_valesFetchDataFromSAP_centrocosto.php";
        else if(param == "choferes")
            return "main_valesFetchDataFromSAP_choferes.php";
        else if(param == "equipos")
            return "main_valesFetchDataFromSAP_equipos.php";
        else if(param == "estaciones")
            return "main_valesFetchDataFromSAP_estaciones.php";
        else return "";
    }
	function LoadRendimientoEstandar(){
		window.location.replace("vales_rendimientoestandar_importar.php");		
	}

</script>

<script>closeModal();</script>
</body>
</html>
