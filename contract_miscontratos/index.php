<?php

$loadNewestDataTablerVersion=true;
include("../plantilla1.php");
include_once("../phps/conexion.php");
include("../phps/dcontract_reportes.php");
include("../phps/dcontract_contratos.php");

$NOMBRE_SHOW = "Solicitud de Contrato";
$NOMBRE_SHOW_PLURAL = "Mis solicitudes de contratos";

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
                            colspan="1"
                            style="width: 143px;">ID
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Código
                        </th>
						 <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 100px;">Fecha
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Empresa
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Proveedor
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Tipo Contrato
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
    $( '#listar_contract' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

    $('#nueva_empresa_btn').on('click',function(){
		location.href = "create.php";
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
                "url": "/phps/dContractLoadMisContratos.php",
                "data": function ( d ) {
                    d.sp_filter_userid			=	<?=$_SESSION['id']?>;
                }
            },
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column", "searchable": false},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase", "bSortable": false, "searchable": false},
                {"sClass": "tdClase", "bSortable": false, "searchable": false}
            ] ,
            "aaSorting": [[ 2, "desc" ]],
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
        location.href = "../contract_miscontratos/create.php?id="+id+"&mode=edit";
    }

    function anular_go(id){
        var result = confirm("¿Esta seguro de ANULAR la <?=$NOMBRE_SHOW?> seleccionada?");

        if(result == true){

            var reason = prompt("Ingrese razón");

            if(reason == "" || reason == null || reason == undefined){
                alert("Validacion: Debe ingresar razón");
                return false;
            }

            var parametros = {
                "id" : id,
                "cod" : 6,
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


    function suspend_go(id){
        var result = confirm("¿Esta seguro de SUSPENDER/ACTIVAR la <?=$NOMBRE_SHOW?> seleccionada?");

        if(result == true){

            var reason = prompt("Ingrese razón");

            if(reason == "" || reason == null || reason == undefined){
                alert("Validacion: Debe ingresar razón");
                return false;
            }

            var parametros = {
                "id" : id,
                "cod" : 9,
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
