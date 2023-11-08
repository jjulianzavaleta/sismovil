<?php

include("../plantilla1.php");
include_once("../phps/conexion.php");
include("../phps/dvales_vale.php");
include("../phps/dvales_consumidor.php");

$NOMBRE_SHOW = "Vale";
$NOMBRE_SHOW_PLURAL = "Vales";

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
        <a data-toggle="modal" id="nueva_empresa_btn" href="#nueva_actividad" class="btn btn-app btn-primary btn-mini">
            <i class="icon-plus-sign"></i>
            Nuevo
        </a>

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
                            colspan="1">ID
                        </th>
						 <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 50px;">ID
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha Registro
                        </th>
						 <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha Máx Consumo
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Centro de Costo
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">EQUI NRO
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

                    $data = getValesFlujo1Consumidor($_SESSION['id']);
                    $link = conectarBD();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){							
                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."</td>";
							
							$estado_desc = "";
							
							if( $d['anulado'] == 0 ){
								switch($d['estado']){
									case 1:
										$estado_desc = "<span class='label label-success'>Registrado</span>";
										break;
									case 2:
										$estado_desc = "<span class='label label-info'>Emitido</span>";
										break;
									case 3:
										$estado_desc = "<span class='label label-warning'>Consumido</span>";
										break;
								}
							}else{
								$estado_desc = "<span class='label'>Anulado</span>";
							}
							
							$centro_costo = getCentroCostoOfValve($d['id'], $link);
							
							echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"fecha_registro\">".date_format($d['fecha_registro'], 'Y-m-d')."</td>";
                            echo "<td class=\"fecha_consumo\">".date_format($d['fecha_max_consumo'], 'Y-m-d')."</td>";
							echo "<td class=\"kostl\" style='font-size:11px'><small>".$centro_costo."</small></td>";
							echo "<td class=\"placa\">".$d['equnr']."</td>";
							echo "<td class=\"usuario_registra\">".$d['usuario']."</td>";
							echo "<td class=\"estado\">".$estado_desc."</td>";

                            $valeConsumidoMesVigente = isValeConsumidoMesVigente($d['consumo_fechaconsumo']);
							
							if($d['anulado'] == 1 || ( $d['estado'] == 3 && $valeConsumidoMesVigente == false)){
								echo $EDITAR_HTML_CODE;
							}else{
								echo $EDITAR_ELIMINAR_HTML_CODE;
								
							}
							
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


<script>
    $( '#dValesMain' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

    $('#nueva_empresa_btn').on('click',function(){
		location.href = "create.php";
    });
	$('#link_sap').on('click',function(){
		location.href = "link_sap.php";
    });

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

       $('[data-rel=tooltip]').tooltip();

       $('.date-picker').datepicker();

    })
	
	$("a","#table_report").on("click",function  () {
		var id = $(this).parent().parent().parent().children("td.hide_column").text();
		location.href = "create.php?id="+id+"&mode=edit";
	});
	
	$("button","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece
        //
        var result = confirm("¿Esta seguro de ANULAR el <?=$NOMBRE_SHOW?> seleccionado?");

        if(result == true){
            var id = $(this).parent().parent().parent().children("td.hide_column").text();
          

            var parametros = {
                "id" : id,
				"cod" : 2,
				"usuario" : <?=$_SESSION['id']?>
            };

            $.ajax({
                data:  parametros,
                url:   '../phps/dvales_ajax.php',
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
                        alert(respuesta.data);
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

    });

</script>

</body>
</html>
