<?php

$loadNewestDataTablerVersion=true;
include("../plantilla1.php");
include_once("../phps/conexion.php");
include("../phps/dcontract_reportes.php");
include("../phps/dcontract_contratos.php");

if( !isset($_GET['method']) || ( $_GET['method'] != "from" && $_GET['method'] != "to") ){
    die("Error, parametros incorrectos");
}



if($_GET['method'] == "to"){
    $contratos = getContratosDerivadoByUser_to($_SESSION['id']);
    $NOMBRE_SHOW = "Contrato derivado a mi usuario";
    $NOMBRE_SHOW_PLURAL = "Contratos derivados a mi usuario";
}else if($_GET['method'] == "from"){
    $contratos = getContratosDerivadoByUser_from($_SESSION['id']);
    $NOMBRE_SHOW = "Contrato derivado por mi usuario";
    $NOMBRE_SHOW_PLURAL = "Contratos derivados por mi usuario";
}

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->

        <div class="row-fluid">
            <h3 class="header smaller lighter blue"><?=$NOMBRE_SHOW_PLURAL?></h3>

            <div class="table-header">
                Resultados de "<?=$NOMBRE_SHOW_PLURAL?> registrados"
            </div>

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
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Estado
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Ver
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">

                    <?php
                    foreach ($contratos as $contrato){

                        $ROLE_RESPONSABLE_LEGAL 	= 3;
                        $role = $ROLE_RESPONSABLE_LEGAL;
                        $isTipoFlujoComprador = $contrato['tipo_flujo'] == 1 ? true : false;
                        $hasLastUserApproved  = $contrato['flag_has_last_approved_usuario'] == 1 ? true : false;
                        $hasLastLogisticaApproved = $contrato['flag_has_last_approved_logistica'] == 1 ? true : false;
                        $estado = $row['estado']      = convertToEstado($contrato['datosgenerales_estado'],$contrato['anulado'],$contrato['suspendido'],$role,true,$isTipoFlujoComprador,$hasLastLogisticaApproved,$hasLastUserApproved);

                        echo "<tr>";
                        echo "<td>".$contrato['id']."</td>";
                        echo "<td>".$contrato['datosgenerales_codigo']."</td>";
                        echo "<td>".$estado."</td>";
                        echo '<td class="td-actions">'.
                            '<div class="btn-group">'.
                            '<button alt="Ir al contrato" title="Ir al contrato" class="btn btn-mini btn-info" onclick="editar('.$contrato['id'].')">'.
                            '<i  class="icon-edit bigger-120"></i>'.
                            '</button>'.
                            '</div>'.
                            '</td>';
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!--PAGE CONTENT ENDS HERE-->

            <!--/row-->
        </div>
        <!--/#page-content-->
		
    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->


<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>


<script>
    $( '#listar_contract_approve' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'>Contratos<a/> > <a href='derivados_per_user.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

</script>

<!--inline scripts related to this page-->
<script type="text/javascript">

    $('document').ready(function(){
        $('#table_report').dataTable({
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column"},
                null,
                null,
                null
            ]
        });

        $('[data-rel=tooltip]').tooltip();

        $('.date-picker').datepicker();
    });

    function editar(idContrato){
        location.href = "derivar.php?id="+idContrato;
    }



</script>

</body>
</html>
