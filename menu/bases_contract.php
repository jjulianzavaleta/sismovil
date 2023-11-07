<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 19/08/2015
 * Time: 09:54 PM
 */
include("../plantilla1.php");
?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
		 <a href="../contract_usuario/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-key"></i>
            Permisos
        </a>
		
		<a href="../contract_area/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-briefcase"></i>
            Area
        </a>
		
        <a href="../contract_proveedor/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-truck"></i>
           Proveedor
        </a>        
		
		<a href="../contract_empresa/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-building"></i>
            Empresas
        </a>
		
		<a href="../contract_tipocontrato/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-book"></i>
            Tipo Contrato
        </a>
<!--
        <a href="../contract_reglas/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-key"></i>
            Reglas
        </a>
-->
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
    $( '#dbasesContratos' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='bases_contract.php'>Bases Contratos</a>");

</script>

<script>closeModal();</script>
</body>
</html>
