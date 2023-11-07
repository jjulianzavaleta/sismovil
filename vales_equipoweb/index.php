<?php

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
ini_set('max_execution_time', '0'); // for infinite time of execution


include("../plantilla1.php");
include_once("../phps/dvales_equipoWeb.php");

$NOMBRE_SHOW = "Equipo Web";
$NOMBRE_SHOW_PLURAL = "Equipo Web";

$centroCostoWeb = getAllCentroCostoWebForCombobox();

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
                            style="width: 143px;">Número de Equipo
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Denominación de objeto técnico
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
                            style="width: 100px;">Tipo Contador
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

                    $data = getAllValesEquipoWeb();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){
							
							$medida_contador = "-";
							if( (!empty($d['medida_contador']) && $d['medida_contador'] != "") || $d['medida_contador'] == "0" ){
								switch($d['medida_contador']){
									case "0": $medida_contador = "Kilometraje";break;
									case "1": $medida_contador = "Odometro";break;
								}
							}
							
                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."$".$d['kostl']."$".$d['medida_contador']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"equnr\">".$d['equnr']."</td>";
							echo "<td class=\"txt_hequi\"><small>".$d['txt_hequi']."</small></td>";
							echo "<td class=\"kostl\"><small>".$d['ktext']."</small></td>";
							echo "<td class=\"license_num\"><small>".$d['license_num']."</small></td>";							
							echo "<td class=\"medida_contador\"><small>".$medida_contador."</small></td>";		
							echo "<td class=\"sttxt\"><small>".$d['sttxt']."</small></td>";							
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
                            <label class="control-label" for="equnr_nuevo">Número de Equipo :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="equnr_nuevo" id="equnr_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="txt_hequi_nuevo">Denominación de objeto técnico :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="txt_hequi_nuevo" id="txt_hequi_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="kostl_nuevo">Centro de Costo :</label>

                            <div class="controls">
                                <div class="span12">                                    
									<select  class="span6" name="kostl_nuevo" id="kostl_nuevo">
										<?php
											foreach($centroCostoWeb as $item){													
												echo "<option value='".$item['id']."' ".$selected.">".$item['ktext']."</option>";
											}
										?>													
									</select>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="license_num_nuevo">Placa :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="license_num_nuevo" id="license_num_nuevo" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="medida_contador_nuevo">Tipo contador :</label>

                            <div class="controls">
                                <div class="span12">                                    
									<select  class="span6" name="medida_contador_nuevo" id="medida_contador_nuevo">
										<option value="0">Kilometraje</option>
										<option value="1">Odometro</option>
									</select>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="sttxt_nuevo">Estado :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="sttxt_nuevo" id="sttxt_nuevo" class="span6" autocomplete="off">
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
                            <label class="control-label" for="equnr_editar">Número de Equipo :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="equnr_editar" id="equnr_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="txt_hequi_editar">Denominación de objeto técnico :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="txt_hequi_editar" id="txt_hequi_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="kostl_editar">Centro de Costo :</label>

                            <div class="controls">
                                <div class="span12">                                    
									<select  class="span6" name="kostl_editar" id="kostl_editar">
										<?php
											foreach($centroCostoWeb as $item){													
												echo "<option value='".$item['id']."' ".$selected.">".$item['ktext']."</option>";
											}
										?>													
									</select>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="license_num_editar">Placa :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="license_num_editar" id="license_num_editar" class="span6" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="medida_contador_editar">Tipo contador :</label>

                            <div class="controls">
                                <div class="span12">                                    
									<select  class="span6" name="medida_contador_editar" id="medida_contador_editar">
										<option value="0">Kilometraje</option>
										<option value="1">Odometro</option>
									</select>
                                </div>
                            </div>
                        </div>
												
						<div class="control-group">
                            <label class="control-label" for="sttxt_editar">Estado :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="sttxt_editar" id="sttxt_editar" class="span6" autocomplete="off">
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
                equnr_editar: {
                    required: true
                },
                id_editar: {
                    required: true,
                    digits: true
                },
				txt_hequi_editar: {
                    required: true
                },
				kostl_editar: {
                    required: true
                },
				license_num_editar: {
                    required: true
                },
				sttxt_editar: {
                    required: true
                }
            },

            messages: {
                equnr_editar: {
                    required: "Número es obligatorio"
                },
                id_editar: {
                    required: "Id es requerido",
                    digits:"Id es un numero entero"
                },
				txt_hequi_editar: {
                    required: "Denominación es obligatorio"
                },
				kostl_editar: {
                    required: "Centro de costo"
                },
				license_num_editar: {
                    required: "Placa es obligatorio"
                },
				sttxt_editar: {
                    required: "Estado es obligatorio"
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
                
                var equnr_nuevo         = $("#equnr_editar").val();
                var id                  = $("#id_editar").val();
				var txt_hequi_nuevo     = $("#txt_hequi_editar").val();				
				var kostl_nuevo         = $("#kostl_editar option:selected" ).val();
				var license_num_nuevo   = $("#license_num_editar").val();
				var sttxt_nuevo         = $("#sttxt_editar").val();		
				var medida_contador_editar = $("#medida_contador_editar").val();

                var parametros = {
                    "a" : id,
                    "b" : equnr_nuevo,
					"c" : txt_hequi_nuevo,
					"d" : kostl_nuevo,
					"e" : license_num_nuevo,
					"f" : sttxt_nuevo,
					"h" : medida_contador_editar
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
                equnr_nuevo: {
                    required: true
                },
				txt_hequi_nuevo: {
                    required: true
                },
				kostl_nuevo: {
                    required: true
                },
				license_num_nuevo: {
                    required: true
                },
				sttxt_nuevo: {
                    required: true
                }
            },

            messages: {
				equnr_nuevo: {
                    required: "Número es obligatorio"
                },
				txt_hequi_nuevo: {
                    required: "Denominación es obligatorio"
                },
				kostl_nuevo: {
                    required: "Centro de costo"
                },
				license_num_nuevo: {
                    required: "Placa es obligatorio"
                },
				sttxt_nuevo: {
                    required: "Estado es obligatorio"
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

                var equnr_nuevo         = $("#equnr_nuevo").val();
				var txt_hequi_nuevo     = $("#txt_hequi_nuevo").val();				
				var kostl_nuevo         = $("#kostl_nuevo option:selected" ).val();
				var license_num_nuevo   = $("#license_num_nuevo").val();
				var sttxt_nuevo         = $("#sttxt_nuevo").val();
				var medida_contador_nuevo = $("#medida_contador_nuevo").val();
				

                var parametros = {
                    "b" : equnr_nuevo,
					"c" : txt_hequi_nuevo,
					"d" : kostl_nuevo,
					"e" : license_num_nuevo,
					"f" : sttxt_nuevo,
					"h" : medida_contador_nuevo
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

        var id           = $(this).parent().parent().parent().children("td.hide_column").text();
        var equnr        = $(this).parent().parent().parent().children("td.equnr").text();
		var txt_hequi    = $(this).parent().parent().parent().children("td.txt_hequi").text();		
		var license_num  = $(this).parent().parent().parent().children("td.license_num").text();
		var sttxt        = $(this).parent().parent().parent().children("td.sttxt").text();
		
		var ids = id.split("$");
		
        $("#id_editar").val(ids[0]);
        $("#equnr_editar").val(equnr);               
		$("#txt_hequi_editar").val(txt_hequi);				
		$("#kostl_editar").val(ids[1]);
		$("#license_num_editar").val(license_num);
		$("#sttxt_editar").val(sttxt);		
		$("#medida_contador_editar").val(ids[2]);		

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

            var ids = id.split("$");

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
