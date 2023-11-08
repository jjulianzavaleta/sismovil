<?php

/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 23/08/2015
 * Time: 05:05 PM
 */

include("../plantilla1.php");
include("../phps/dpaviferia_pedido.php");

$NOMBRE_SHOW = "Pedido Paviferia";
$opt1 = "checked='checked'";
$opt2 = "";

if(isset($_POST['optSelec'])){

    if($_POST['optSelec'] == 1){
        $fechaIni = $_POST['fechaIni']." 00:00:00";
        $fechaFin = $_POST['fechaFin']." 23:59:59";
        $opt1 = "checked='checked'";
        $opt2 = "";
    }else{
        $fechaIni = "";
        $fechaFin = "";
    }
    if($_POST['optSelec'] == 2){
        $rangoIni = $_POST['rangoIni'];
        $rangoFin = $_POST['rangoFin'];
        $opt1 = "";
        $opt2 = "checked='checked'";
    }else{
        $rangoIni = "";
        $rangoFin = "";
    }

    $cad_parameters = "?optSelec=".$_POST['optSelec']."&fechaIni=".$fechaIni."&fechaFin=".$fechaFin."&rangoIni=".$rangoIni."&rangoFin=".$rangoFin;

    $pedidos = getAllCotizaciones3($_POST['optSelec'],$fechaIni,$fechaFin,$rangoIni,$rangoFin);
}else{
    $cad_parameters = "";
    $pedidos = getAllCotizaciones2();
}


?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->

        <form action="listarCotizacion2.php" method="post" class="form-horizontal" onsubmit="validar()">
            <table>
                <thead></thead>
                <tbody>

                <tr>
                    <td>
                        <input <?=$opt1?> type="radio" name="optSelec" id="optSelec1" value="1" />
                    </td>
                    <td>
                        <div class="control-group">
                            <label class="control-label" for="fechaIni">Fecha Inicio :</label>

                            <div class="controls">
                                <div class="row-fluid input-append date">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <input class="span6 date-picker" AUTOCOMPLETE="off"
                                           value="<?php if(isset($_POST['fechaIni']))echo $_POST['fechaIni'];?>"
                                           name="fechaIni" id="fechaIni" type="text"
                                           data-date-format="yyyy/mm/dd"/>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="control-group">
                            <label class="control-label" for="fechaFin">Fecha Fin :</label>

                            <div class="controls">
                                <div class="row-fluid input-append date">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <input class="span6 date-picker" AUTOCOMPLETE="off"
                                           value="<?php if(isset($_POST['fechaFin']))echo $_POST['fechaFin'];?>"
                                           name="fechaFin" id="fechaFin" type="text"
                                           data-date-format="yyyy/mm/dd"/>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="radio" <?=$opt2?> name="optSelec" id="optSelec2" value="2" />
                    </td>
                    <td>
                        <div class="control-group">
                            <label class="control-label" for="rangoIni">Numero Inicio :</label>

                            <div class="controls">
                                <div class="row-fluid input-append date">
                                    <input class="span7" value="<?php if(isset($_POST['rangoIni']))echo $_POST['rangoIni'];?>"
                                           name="rangoIni" id="rangoIni" type="text" AUTOCOMPLETE="off"/>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="control-group">
                            <label class="control-label" for="rangoFin">Numero Fin :</label>

                            <div class="controls">
                                <div class="row-fluid input-append date">
                                        <input class="span7" value="<?php if(isset($_POST['rangoFin']))echo $_POST['rangoFin'];?>"
                                               name="rangoFin" id="rangoFin" type="text" AUTOCOMPLETE="off"/>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <button>Filtrar</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>

            <div class="row-fluid">
                <h5 class="header smaller lighter blue">Datos Cotizacion</h5>


                <div id="table_report_wrapper" class="dataTables_wrapper" role="grid">
                    <table id="table_report" class="table table-striped table-bordered table-hover dataTable"
                           aria-describedby="table_report_info">
                        <thead>
                        <tr role="row">
                            <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                 colspan="1">ID
                            </th>
                            <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                      colspan="1"
                                      style="width: 60px;;font-size: 11px">Codigo
                            </th>
                            <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                 colspan="1"
                                 style="width: 60px;;font-size: 11px">Estado
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 60px;;font-size: 11px">Fecha Emision
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 60px;;font-size: 11px">Usuario
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 60px;;font-size: 11px">Zona
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 200px;;font-size: 11px">Razon Social
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 80px;;font-size: 11px">Total
                            </th>
                            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;;font-size: 11px">
                                Acciones
                            </th>

                        </tr>
                        </thead>


                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                        <?php

                        if(!empty($pedidos) && $pedidos != false){

                            foreach($pedidos as $p){

                                $icon  = "";
                                $title = "";
                                $label = "";
                                $estadoCot = "";
                                if($p['estado'] == 2 || $p['estado'] == 3){
                                    $icon  = "icon-eye-open";
                                    $title = "Ver";

                                    if($p['estado'] == 2){$label="label-important";$estadoCot = "Cerrada";}
                                    if($p['estado'] == 3){$label="label-info";$estadoCot = "Aceptada";}
                                }else{
                                    $icon  = "icon-edit";
                                    $title = "Editar";
                                    if($p['estado'] == 1){$label="label-purple";$estadoCot = "Emitida";}
                                }

                                echo "<tr class=\"odd\" style='font-size: 11px'>";
                                echo "<td>".$p['id']."</td>";
                                echo "<td><span class=\"label ".$label."\">".$p['codigo']."</span></td>";
                                echo "<td>".$estadoCot."</td>";
                                echo "<td>".$p['fechaEmision']."</td>";
                                echo "<td>".$p['username']."</td>";
                                echo "<td>".$p['paviferia_zona']."</td>";
                                echo "<td>".$p['nombre_rzsocial']."</td>";
                                echo "<td>".$p['total']."</td>";
                                echo "<td class=\"td-actions\">
                                       <div class=\"btn-group\">
                                         <a  alt=\"".$title."\" title=\"".$title."\"
                                          class=\"btn btn-mini btn-info\" href=\"index.php?id=".$p['id']."\">
                                          <i  class=\"".$icon." bigger-120\"></i>
                                         </a>
                                         <a id=\"imprimir\" href=\"#\" class=\"btn btn-mini\" onclick=\"imprimir(".$p['id'].")\" alt=\"Imprimir\" title=\"Imprimir\">
                                                <i class=\"icon-print\"></i>
                                        </a>
                                        </div>
                                       </td>";
                                echo "</tr>";

                            }
                        }

                        ?>

                        </tbody>
                    </table>
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
    $( '#pedidospaviferiaPlantilla1' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'>Crear Cotizacion Paviferia > </a> <a href='listarCotizacion.php'>Litar Cotizaciones</a>");


    function imprimir(id){
        var pagina;
        var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no,scrollbars=YES, resizable=yes, width=880, height=500, top=85, left=100";
        pagina="frmPedidoPDF.php?id="+id;
        window.open(pagina,"",opciones);

    }
</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {
        var oTable1 = $('#table_report').dataTable({
            "aoColumns": [
                {"sClass": "hide_column"},
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                {"bSortable": false}
            ],
            "aaSorting": []
        });

        $('.date-picker').datepicker();

        $( "#fechaIni" ).click(function() {
          $("#optSelec1").prop('checked',true);
          $("#optSelec2").prop('checked',false);
          $("#rangoFin").val("");
          $("#rangoIni").val("");
        });

        $( "#fechaFin" ).click(function() {
            $("#optSelec1").prop('checked',true);
            $("#optSelec2").prop('checked',false);
            $("#rangoFin").val("");
            $("#rangoIni").val("");
        });

        $( "#rangoIni" ).click(function() {
            $("#optSelec1").prop('checked',false);
            $("#optSelec2").prop('checked',true);
            $("#fechaFin").val("");
            $("#fechaIni").val("");
        });

        $( "#rangoFin" ).click(function() {
            $("#optSelec1").prop('checked',false);
            $("#optSelec2").prop('checked',true);
            $("#fechaFin").val("");
            $("#fechaIni").val("");
        });

    });


</script>

</body>
</html>
