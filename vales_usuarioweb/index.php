<?php

include("../plantilla1.php");
include_once("../phps/dvales_usuarioWeb.php");

$NOMBRE_SHOW = "Usuario Web";
$NOMBRE_SHOW_PLURAL = "Usuario Web";

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
                            style="width: 40px;">ID
                        </th>
                        <th  role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                             colspan="1"
                             style="width: 40px;">ID
                        </th>
                        <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Código del conductor
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Nombres
                        </th>							
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">DNI
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Flujo
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

                    $data = getAllValesUsuarioWeb();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){
							
                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."#".$d['estado']."#".$d['isflujoconsumidor']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"cod_conductor\">".$d['cod_conductor']."</td>";
							echo "<td class=\"name1\">".$d['name1']."</td>";							
							echo "<td class=\"num_doc_identidad\">".$d['num_doc_identidad']."</td>";
							echo "<td class=\"isflujoconsumidor\">".($d['isflujoconsumidor']==1?"Flujo 1 (Consumidor)":"Flujo 2")."</td>";
							echo "<td class=\"estado\">".($d['estado']==1?"Activo":"Inactivo")."</td>";
                            echo $EDITAR_ELIMINAR_HTML_CODE;
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
<div class="row-fluid">
    <div class="modal fade" id="nueva_actividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Nuevo <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate">

                        <div class="control-group">
                            <label class="control-label" for="cod_conductor_nuevo">Código del conductor :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="cod_conductor_nuevo" id="cod_conductor_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="nombres_nuevo">Nombres :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="nombres_nuevo" id="nombres_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="num_doc_identidad_nuevo">DNI :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="num_doc_identidad_nuevo" id="num_doc_identidad_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="isflujoconsumidor_nuevo">Consumidor :</label>

                            <div class="controls">
                                <div class="span12">
                                   <input type="checkbox" value="1" id="isflujoconsumidor_nuevo" name="isflujoconsumidor_nuevo">Si<br>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <input type="submit" class="btn btn-primary" value="Guardar">
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
<!--END MODAL- NUEVO-->

<!--MODAL EDITAR-->
<!-- Modal -->
    <div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Editar <?=$NOMBRE_SHOW?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="validation-form_editar" method="get" novalidate="novalidate">

                        <div class="hidden">
                            <label class="control-label" for="id_editar">ID :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="id_editar" id="id_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>                        
						
                        <div class="control-group">
                            <label class="control-label" for="cod_conductor_editar">Código del conductor :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="cod_conductor_editar" id="cod_conductor_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="nombres_editar">Nombres :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="nombres_editar" id="nombres_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="num_doc_identidad_editar">DNI :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="num_doc_identidad_editar" id="num_doc_identidad_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="estado_activo_editar">Estado :</label>

                            <div class="controls">
                                <div class="span12">
                                   <input type="checkbox" value="1" id="estado_activo_editar" name="estado_activo_editar">Activo<br>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="isflujoconsumidor_editar">Consumidor :</label>

                            <div class="controls">
                                <div class="span12">
                                   <input type="checkbox" value="1" id="isflujoconsumidor_editar" name="isflujoconsumidor_editar">Si<br>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <input type="submit" class="btn btn-primary" value="Guardar">
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
<!-- /.modal -->
<!--END MODA LEDITAR-->


</div>
<script>
    $( '#dbasesVales' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/bases_vales.php'>Bases Vales > <a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a></a>");

    $('#nueva_empresa_btn').on('click',function(){
        $("#desc_nuevo").val("");

        setTimeout(function (){
            $("#nombre_nuevo").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);
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
                { "bSortable": false }
            ] ,
            "aaSorting": [
                [ 1, "desc" ]
            ]
        });

        $('table th input:checkbox').on('click', function () {
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });

        });

        $('[data-rel=tooltip]').tooltip();

        $('#validation-form_editar').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
				id_editar: {
                    required: true,
                    digits: true
                },
                nombres_editar: {
                    required: true
                },
                num_doc_identidad_editar: {
                    required: true
                },
				cod_conductor_editar: {
                    required: true
                }
            },

            messages: {				
                id_editar: {
                    required: "Id es requerido",
                    digits:"Id es un numero entero"
                },
				nombres_editar: {
                    required: "Nombres es requerido",
                },
				num_doc_identidad_editar: {
                    required: "DNI es requerido",
                },
				cod_conductor_editar: {
                    required: "Código es requerido",
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-error', $('.login-form')).show();
            },

            highlight: function (e) {
                $(e).closest('.control-group').removeClass('info').addClass('error');
            },

            success: function (e) {
                $(e).closest('.control-group').removeClass('error').addClass('info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if (element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('.controls');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl').eq(0));
                }
                else if (element.is('.chzn-select')) {
                    error.insertAfter(element.nextAll('[class*="chzn-container"]').eq(0));
                }
                else error.insertAfter(element);
            },

            submitHandler: function (form) {
                
                var id                       	= $("#id_editar").val();
				var nombres_editar       		= $("#nombres_editar").val();
				var num_doc_identidad_editar    = $("#num_doc_identidad_editar").val();
				var cod_conductor_editar        = $("#cod_conductor_editar").val();
				var editar                      = 0;
				
				var isflujoconsumidor_editar  = 0;
				
				if($("#isflujoconsumidor_editar").is(':checked')) {
                    isflujoconsumidor_editar      = 1;
                }
				
				if($("#estado_activo_editar").is(':checked')) {
                    editar      = 1;
                }

                var parametros = {
                    "a" : id,
                    "b" : nombres_editar,
					"c" : num_doc_identidad_editar,
					"d" : cod_conductor_editar,
					"e" : editar,
					"f" : isflujoconsumidor_editar
                };


                $.ajax({
                    data:  parametros,
                    url:   'editar.php',
                    type:  'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#editar').modal('hide');
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
            },
            invalidHandler: function (form) {
            }
        });
        $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            rules: {
                cod_conductor_nuevo: {
                    required: true
                },
                nombres_nuevo: {
                    required: true
                },
				num_doc_identidad_nuevo: {
                    required: true
                }
            },

            messages: {
				cod_conductor_nuevo: {
                    required: "Código es requerido",
                },
                nombres_nuevo: {
                    required: "Nombres es requerido",
                },
				num_doc_identidad_nuevo: {
                    required: "DNI es requerido"
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-error', $('.login-form')).show();
            },

            highlight: function (e) {
                $(e).closest('.control-group').removeClass('info').addClass('error');
            },

            success: function (e) {
                $(e).closest('.control-group').removeClass('error').addClass('info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if (element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('.controls');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl').eq(0));
                }
                else if (element.is('.chzn-select')) {
                    error.insertAfter(element.nextAll('[class*="chzn-container"]').eq(0));
                }
                else error.insertAfter(element);
            },

            submitHandler: function (form) {

				var cod_conductor_nuevo      = $("#cod_conductor_nuevo").val();
				var nombres_nuevo            = $("#nombres_nuevo").val();				
				var num_doc_identidad_nuevo  = $("#num_doc_identidad_nuevo").val();
				
				var isflujoconsumidor_nuevo  = 0;
				
				if($("#isflujoconsumidor_nuevo").is(':checked')) {
                    isflujoconsumidor_nuevo      = 1;
                }

                var parametros = {
                    "b" : cod_conductor_nuevo,
					"c" : nombres_nuevo,
					"d" : num_doc_identidad_nuevo,
					"e" : isflujoconsumidor_nuevo
                };

                $.ajax({
                    data:  parametros,
                    url:   'nuevo.php',
                    type:  'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#nueva_actividad').modal('hide');
                        openModal();
                    },
                    success: function(respuesta){

                        respuesta = $.parseJSON( respuesta );

                        if(respuesta.estado == "1"){

                            closeModal();
                            $().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
                            //oTable1.fnAddData([respuesta.id,respuesta.id,respuesta.descripcion,'']);
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


            },
            invalidHandler: function (form) {
            }
        });
        $('.date-picker').datepicker();

    })

    $("a","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece

        var id                 = $(this).parent().parent().parent().children("td.hide_column").text();
		var cod_conductor      = $(this).parent().parent().parent().children("td.cod_conductor").text();	
		var nombres            = $(this).parent().parent().parent().children("td.name1").text();
		var num_doc_identidad  = $(this).parent().parent().parent().children("td.num_doc_identidad").text();
        
		
		var ids = id.split("#");
		//alert(ids[0]+"*"+ids[1]+"*"+ids[2]+"*"+ids[3]+"*"+ids[4]+"*");
        $("#id_editar").val(ids[0]);
		$("#cod_conductor_editar").val(cod_conductor);	
		$("#nombres_editar").val(nombres);		
		$("#num_doc_identidad_editar").val(num_doc_identidad);		
		if(ids[2] == "1"){document.getElementById("isflujoconsumidor_editar").checked = true;}else{document.getElementById("isflujoconsumidor_editar").checked = false;}		
		if(ids[1] == "1"){document.getElementById("estado_activo_editar").checked = true;}else{document.getElementById("estado_activo_editar").checked = false;}		
        

        setTimeout(function (){
            $("#nombre_editar").focus();
        }, <?=$SLEEP_TIME_FOCUS?>);

    });

    $("button","#table_report").on("click",function  () {
        //parent() buscamos  el padre inmediatamente superior
        //children() hijo inmediatamente  inferior "td.so.." hace referncia a la calse a la que pertenece
        //
        var result = confirm("¿Esta seguro de eliminar la <?=$NOMBRE_SHOW?> seleccionada?");

        if(result == true){
            var id = $(this).parent().parent().parent().children("td.hide_column").text();

            var ids = id.split("#");

            var parametros = {
                "a" : ids[0]
            };

            $.ajax({
                data:  parametros,
                url:   'eliminar.php',
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

    });
</script>

</body>
</html>
