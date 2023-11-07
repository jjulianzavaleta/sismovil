<?php

$loadNewestDataTablerVersion=true;
include("../plantilla1.php");
include_once("../phps/conexion.php");
include("../phps/dcontract_reportes.php");
include("../phps/dcontract_contratos.php");

if(!isset($_GET['id'])){
    die("Error: contrato no especificado");
}

$idContrato = $_GET['id'];

$legal_usuarios = getUsuariosLegal();
$historial = getDerivaciones($idContrato);

$NOMBRE_SHOW = "Historial";
$NOMBRE_SHOW_PLURAL = "Historial";
?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->

        <div class="row-fluid">
            <h3 class="header smaller lighter blue">Nueva Derivación</h3>
            <form class="form-horizontal">
            <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td  width="50%">
                        <label class="control-label" for="usuario_asignado">Usuario:</label>
                        <div class="control-group">
                        <select  class="span2 chosen-select" name="usuario_asignado" id="usuario_asignado">
                            <?php
                            foreach($legal_usuarios as $user_legal){
                                echo "<option value=".$user_legal['id'].">".$user_legal['usuario']." - ID: ".$user_legal['id']."</option>";
                            }
                            ?>
                        </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        <label class="control-label" for="detalle">Detalle:</label>
                        <div class="control-group">
                            <textarea class="span6" id="detalle" name="detalle" rows="4" cols="100" ></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                       <button type="button" class="btn btn-success" style= "float: left;position: relative;left: 50%;"  onclick="submit_derivar()">
                            <i class="icon-random icon-white"></i> Derivar
                        </button>
                    </td>
                </tr>
            </table>
            </form>

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
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Usuario Derivó
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha Derivó
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Detalle
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Usuario asignado
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha completado
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
                    foreach ($historial as $item){

                        $fecha_completa = "";
                        if(!empty($item['fechacompleta'])){
                            $fecha_completa = $item['fechacompleta']->format('Y-m-d H:i:s');
                        }else{
                            $fecha_completa = "-";
                        }

                        $estado = "";
                        if($item['anulado'] == 1){
                            $estado = "ANULADO<br>".$item['anulado_razon'];
                        }else if($item['estado'] == 0){
                            $estado = "EN PROCESO";
                        }else if($item['estado'] == 1){
                            $estado = "ESPERA VISTO BUENO";
                        }else if($item['estado'] == 2){
                            $estado = "FINALIZADO";
                        }

                        echo "<tr>";
                        echo "<td>".$item['id']."</td>";
                        echo "<td>".$item['datosgenerales_codigo']."</td>";
                        echo "<td>".getUsernameFromAdmin($item['idusuarioderiva'])."</td>";
                        echo "<td>".$item['fechaderiva']->format('Y-m-d H:i:s')."</td>";
                        echo "<td>".$item['detalle']."</td>";
                        echo "<td>".getUsernameFromAdmin($item['idusuarioasignado'])."</td>";
                        echo "<td>".$fecha_completa."</td>";
                        echo "<td>".$estado."</td>";
                        echo '<td class="td-actions">'.
                            '<div class="btn-group">'.
                            '<button alt="Ir al contrato" title="Ir al contrato" class="btn btn-mini btn-info" onclick="editar('.$idContrato.')">'.
                            '<i  class="icon-edit bigger-120"></i>'.
                            '</button>'.
                            '<button alt="Anular" title="Anular" class="btn btn-mini btn-warning" onclick="anular('.$item['id'].')">'.
                            '<i  class="icon-flag bigger-120"></i>'.
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
    $( '.activePlantilla1' ).html( "<a href='index.php'>Contratos<a/> > <a href='derivar.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

</script>

<!--inline scripts related to this page-->
<script type="text/javascript">

    $('document').ready(function(){
        $('#table_report').dataTable({
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column"},
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ]
        });

        $('[data-rel=tooltip]').tooltip();

        $('.date-picker').datepicker();
    });

    function submit_derivar(){

        var confirmacion = confirm("¿Esta seguro que desea derivar este contrato?");
        if(confirmacion){
        }else{
            return false;
        }

        var usuarioAsignado =  $("#usuario_asignado :selected").val();
        var detalle =  $("#detalle").val();
        var idContrato = <?=$idContrato?>;

        var parametros = {
            "cod" : 10,
            "idContrato" : idContrato,
            "detalle" : detalle,
            "idUsuarioAsignado" : usuarioAsignado,
            "idUsuarioAsigna" : <?=$_SESSION['id']?>
        };

        $.ajax({
            data:  parametros,
            url:   '../phps/dcontract_ajax.php',
            type:  'post',
            dataType: "html",
            beforeSend: function (repuesta) {
                // lo que se hace mientras llega
                openModal();
            },
            success: function(respuesta){
                respuesta = $.parseJSON( respuesta );

                if(respuesta.estado == "1"){

                    closeModal();
                    $().toastmessage('showSuccessToast', 'Exito');
                    setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

                }else{

                    closeModal();
                    $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                    alert(respuesta.error);
                    location.reload();
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

    function editar(idContrato){
        location.href = "../contract_miscontratos/create.php?id="+idContrato+"&mode=approve&role=legal";
    }

    function anular(id){
        var result = confirm("¿Esta seguro de ANULAR la derivación seleccionada?");

        if(result == true){

            var reason = prompt("Ingrese razón");

            if(reason == "" || reason == null || reason == undefined){
                alert("Validacion: Debe ingresar razón");
                return false;
            }

            var parametros = {
                "cod" : 11,
                "idContrato" : id,
                "usuario" : <?=$_SESSION['id']?>,
                "reason" : reason
            };

            $.ajax({
                data:  parametros,
                url:   '../phps/dcontract_ajax.php',
                type:  'post',
                dataType: "html",
                beforeSend: function (repuesta) {
                    // lo que se hace mientras llega
                    openModal();
                },
                success: function(respuesta){
                    respuesta = $.parseJSON( respuesta );

                    if(respuesta.estado == "1"){

                        closeModal();
                        $().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
                        setTimeout(function(){location.reload(); }, <?php echo $SLEEP_TIME ?>);

                    }else{

                        closeModal();
                        $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                        alert(respuesta.error);
                        location.reload();
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
    }


</script>

</body>
</html>
