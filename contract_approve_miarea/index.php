<?php

$loadNewestDataTablerVersion=true;
include("../plantilla1.php");
include_once("../phps/conexion.php");
include("../phps/dcontract_reportes.php");
include("../phps/dcontract_contratos.php");

$NOMBRE_SHOW = "Contrato";
$NOMBRE_SHOW_PLURAL = "Contratos";

$permissions_contracts = getPermissionsUsuarioContract( $_SESSION['username'] );

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
		
		<div class="row-fluid">
		<div class="modal fade" id="nueva_actividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Fecha de entrega de terreno</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate">

							<div class="control-group">
								<label class="control-label" for="id_actaterreno">ID :</label>

								<div class="controls">
									<div class="span12">
										<input readOnly="readOnly" type="text" name="id_actaterreno" id="id_actaterreno" class="span6" autocomplete="off">
									</div>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="codigo_actaterreno">Código :</label>

								<div class="controls">
									<div class="span12">
										<input readOnly="readOnly" type="text" name="codigo_actaterreno" id="codigo_actaterreno" class="span6" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="fechaentrega_actaterreno">Fecha entrega :</label>

								<div class="controls">
									<div class="span12">
										<input class="span6 date-picker" name="fechaentrega_actaterreno" id="fechaentrega_actaterreno" type="text"
											   data-date-format="yyyy-mm-dd" />
									</div>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="fechafin_actaterreno">Fecha finaliza :</label>
								<div class="controls">
									<div class="span12">
										<input class="span6 date-picker" name="fechafin_actaterreno" id="fechafin_actaterreno" type="text"
											   data-date-format="yyyy-mm-dd" />
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
								<input type="button" class="btn btn-primary" data-dismiss="modal" value="Guardar" onclick="save_entregaterreno()">
							</div>
						</form>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
		</div>
		</div>
		
    </div>
    <!--/#main-content-->
</div>
<!--/.fluid-container#main-container-->


<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>


<script>
    $( '#listar_contract_approve_miarea' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a>");

</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {
        $('#table_report').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "ajax": {
                "url": "/phps/dContractLoadMiArea.php",
                "data": function ( d ) {
                    d.sp_filter_idarea			=	<?=$permissions_contracts[0]['idarea']?>;
                }
            },
            "aoColumns": [
                {"bSortable": false,"sClass": "hide_column", "searchable": false},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase1"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase"},
                {"sClass": "tdClase", "bSortable": false, "searchable": false},
                {"sClass": "tdClase", "bSortable": false, "searchable": false}
            ],
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
        location.href = "../contract_miscontratos/create.php?id="+id+"&mode=approve&role=jefe";
    }

    function anular_go(id){
        var result = confirm("¿Esta seguro de ANULAR el <?=$NOMBRE_SHOW?> seleccionada?");

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


    function acta_terreno(id, codigo){
        $("#id_actaterreno").val(id);
        $("#codigo_actaterreno").val(codigo);
    }
	
	function save_entregaterreno(){
		
		var id = $("#id_actaterreno").val();
		var fechainicio = $("#fechaentrega_actaterreno").val();
		var fechafin = $("#fechafin_actaterreno").val();
		
		if( fechainicio == "" || fechafin == "" || fechainicio == null || fechafin == null){
			alert("Validación: Debe ingresar ambas fechas");
			return false;
		}
		
		    var parametros = {
                "id" : id,
				"cod" : 7,
				"fecha_inicio" : fechainicio,
				"fecha_fin" : fechafin,
				"usuario" : <?=$_SESSION['id']?>
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

</script>

</body>
</html>
