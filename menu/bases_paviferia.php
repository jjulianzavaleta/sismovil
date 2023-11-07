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
        <a href="../paviferia_productos/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-barcode"></i>
           Productos
        </a>

        <a href="../paviferia_clientesdescuentos/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-coffee"></i>
            Clientes
        </a>

        <a href="../paviferia_grupoclientes/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-group"></i>
            Grupos Cli
        </a>

        <a href="../paviferia_descuentos/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-ticket"></i>
            Descuentos
        </a>

        <a href="../paviferia_formadepago/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-credit-card"></i>
            Forma Pago
        </a>

        <a href="../paviferia_grupo/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-github"></i>
           Grupos Prod
        </a>
		
		 <a href="../paviferia_zona/index.php" class="btn btn-app btn-primary btn-minier" style="font-size: 15px">
            <i class="icon-map-marker"></i>
           Zonas
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
    $( '#bpaviferiaPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='bases_paviferia.php'>Datos Bases Paviferia</a>");

</script>

<script>closeModal();</script>
</body>
</html>
