<?php

$loadNewestDataTablerVersion=true;
include_once("../plantilla1.php");
include_once("../phps/conexion.php");
include_once("../phps/dvales_vale.php");
include_once("../phps/dvales_create.php");

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
		<a data-toggle="modal" id="link_sap" href="#link_sap" class="btn btn-app btn-success btn-mini">
            <i class="icon-bolt"></i>
            SAP
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
                            style="width: 143px;">Placa
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
        $('#table_report').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "ajax": {
                "url": "/phps/dContractLoadValesPlanner.php",
                "data": function ( d ) {
                }
            },
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column", "searchable": false},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase1"},
                {"sClass": "tdClase", "bSortable": false, "searchable": false},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase", "bSortable": false},
                {"sClass": "tdClase", "bSortable": false, "searchable": false}
            ],
            "aaSorting": [[ 1, "desc" ]],
            "dom": '<"top"flp<"clear">>rt<"bottom"ifp<"clear">>',
            "oLanguage": {
                "oPaginate": {
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ contratos",
                "sEmptyTable": "No se encontraron contratos",
                "sLengthMenu": "Mostrar _MENU_ contratos",
                "sInfoEmpty": "No se muestra ningun contrato",
                "sProcessing": "<img src='/assets/images/712.GIF'>"
            },
            "aoColumnDefs": [
                {
                    "aTargets": [1],
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<span class='label label-success'>"+sData+"</span></a>");
                    }
                }
            ]
        });

       $('[data-rel=tooltip]').tooltip();

       $('.date-picker').datepicker();

    })

    function edit_go(id){
        location.href = "create.php?id="+id+"&mode=edit";
    }

    function anular_go(idvale){

        var result = confirm("¿Esta seguro de ANULAR el <?=$NOMBRE_SHOW?> seleccionado?");

        if(result == true){

            var parametros = {
                "id" : idvale,
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
                        alert(respuesta.data);
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
