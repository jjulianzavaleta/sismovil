<?php

header('Location: dashboard.php');

include("../plantilla1.php");
include("../phps/dAdmin.php");

$NOMBRE_SHOW = "Usuario";
$NOMBRE_SHOW_PLURAL = "Usuarios";

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
                             style="width: 50px;">ID
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Nombres
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Apellidos
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 183px;">Permisos
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Nombre de Usuario
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Estado
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getAdminById(intval($_SESSION['id']));

                    if(!empty($data) && $data != false){

                        foreach($data as $d){

                            $permisos   = "";
                            $prefijo    = "";

                            if(!isset($d['manageusers']))$d['manageusers'] = 0;

                            if($d['permission_data']==="1"){$permisos.=$prefijo."- Toma de Data"."<br>";}
                            if($d['permission_pedidos']==="1"){$permisos.=$prefijo."- Toma de Pedidos"."<br>";}
                            if($d['permission_paviferia']==="1"){$permisos.=$prefijo."- Paviferia"."<br>";}
                            if($d['manageusers']==="1"){$permisos.=$prefijo."- Administrar Usuarios"."<br>";}							

                            $estado = "";
                            if($d['activo']==="1"){$estado = "Activo";}else{$estado="Inactivo";}


                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id'].",".$d['activo'].",".
                                        $d['permission_data'].",".$d['permission_pedidos'].",".$d['permission_paviferia'].",".
                                        $d['manageusers']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"nombres\">".$d['nombres']."</td>";
                            echo "<td class=\"apellidos\">".$d['apellidos']."</td>";
                            echo "<td class=\"tipo\">".$permisos."</td>";
                            echo "<td class=\"usuario\">".$d['usuario']."</td>";
                            echo "<td class=\"estado\">".$estado."</td>";

                            echo "</tr>";

                        }
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


<!--MODAL- NUEVO-->
<!-- Modal -->

<script>
    $( '#adminPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( " <a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

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
                null                
            ] ,
            "aaSorting": [
                [ 1, "desc" ]
            ]
        });

        $('[data-rel=tooltip]').tooltip();

        $('.date-picker').datepicker();

    })


</script>

</body>
</html>
