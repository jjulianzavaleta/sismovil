<?php

/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 01:30 AM
 */

include("../plantilla1.php");
include_once("../phps/conexion.php");
include_once("../phps/dcontract_usuarios.php");

$NOMBRE_SHOW = "Permiso";
$NOMBRE_SHOW_PLURAL = "Permisos";

$areas = getListaAreas();

?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->
        <a data-toggle="modal" id="nueva_empresa_btn" href="#nueva_actividad" class="btn btn-app btn-primary btn-mini">
            <i class="icon-plus-sign"></i>
            Nuevo
        </a>
        <a data-toggle="modal" href="#" onclick="window.location.href ='permisosAdicionales.php'" class="btn btn-app btn-primary btn-mini">
            <i class="icon-key"></i>
            Extras
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
                            style="width: 80px;">Usuario
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Correo
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 50px;">Tipo
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 100px;">Area Principal
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 143px;">Permisos
                        </th>
						<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                            colspan="1"
                            style="width: 50px;">Estado
                        </th>


                        <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;">
                            Acciones
                        </th>

                    </tr>
                    </thead>


                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php

                    $data = getAllContractUsuarios();

                    if(!empty($data) && $data != false){

                        foreach($data as $d){
							
							$permisos   = "";
                            $prefijo    = "";

                            if(!isset($d['manageusers']))$d['manageusers'] = 0;

                            if($d['permission_crear']===1){$permisos.=$prefijo."- Solicitar contratos"."<br>";}
                            if($d['permission_aprobar']===1){$permisos.=$prefijo."- Aprobar contratos"."<br>";}
                            if($d['permission_reportes']===1){$permisos.=$prefijo."- Ver reportes"."<br>";}
							if($d['permission_admin']===1){$permisos.=$prefijo."- Administrar datos bases"."<br>";}
							if($d['permission_responsablearea']===1){$permisos.=$prefijo."- Responsable de área"."<br>";}
							if($d['tipo_usuario']===2){$tipo_comprador = "No comprador";}else{$tipo_comprador="Comprador estándar";}

                            echo "<tr class=\"odd\">";
                            echo "<td>".$d['id']."#".$d['permission_crear']."#".$d['permission_aprobar']."#".$d['permission_reportes']."#".$d['activo']."#".$d['permission_admin']."#".$d['permission_responsablearea']."#".$d['idarea']."#".$d['tipo_usuario']."</td>";
                            echo "<td class=\"id\">".$d['id']."</td>";
                            echo "<td class=\"desc\">".$d['usuario']."</td>";
							echo "<td class=\"correo\"><small>".$d['correo']."</small></td>";
							echo "<td class=\"area\"><small>".$tipo_comprador."</small></td>";
							echo "<td class=\"area\"><small>".$d['area']."</small></td>";
							echo "<td class=\"permisos\">".$permisos."</td>";
							echo "<td class=\"activo\">".($d['activo']==1?"Activo":"Inactivo")."</td>";
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
                            <label class="control-label" for="desc_nuevo">Usuario :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="desc_nuevo" id="desc_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="correo_nuevo">Correo :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="correo_nuevo" id="correo_nuevo" class="span6 uppercase" autocomplete="off">
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="area_nuevo">Área Principal:</label>

                            <div class="controls">
                                <div class="span12">
                                   <select name="area_nuevo" id="area_nuevo">
									  <?php
										foreach($areas as $area){
											echo "<option value='".$area['id']."'>".$area['descripcion']."</option>";
										}
									  ?>
									</select>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="tipousuario_nuevo">Tipo comprador :</label>

                            <div class="controls">
                                <div class="span12">
                                   <select name="tipousuario_nuevo" id="tipousuario_nuevo">
									 <option value='1'>Comprador estándar</option>
									 <option value='2'>No Comprador</option>	
									</select>
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="c_permission_crear"> Permisos Área principal:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="c_permission_crear" name="permission_crear">Solicitar contratos<br>
                                    <input type="checkbox" value="1" id="c_permission_aprobar" name="permission_aprobar">Aprobar contratos<br>
                                    <input type="checkbox" value="1" id="c_permission_reportes" name="permission_reportes">Ver reportes<br>
									<input type="checkbox" value="1" id="c_permission_responsablearea" name="permission_responsablearea">Responsable área<br>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="c_permission_admin"> Superusuario:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="c_permission_admin" name="permission_admin">Administrar data<br>
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
                            <label class="control-label" for="desc_editar">Usuario :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="desc_editar" id="desc_editar" class="span6 uppercase" autocomplete="off" readonly="readonly">
                                </div>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="correo_editar">Correo :</label>

                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="correo_editar" id="correo_editar" class="span6 uppercase" autocomplete="off" readonly="readonly">
                                </div>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="tipousuario_editar">Tipo comprador :</label>

                            <div class="controls">
                                <div class="span12">
                                   <select name="tipousuario_editar" id="tipousuario_editar">
									 <option value='1'>Comprador estándar</option>
									 <option value='2'>No Comprador</option>	
									</select>
                                </div>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="area_editar">Área Principal:</label>

                            <div class="controls">
                                <div class="span12">
                                   <select name="area_editar" id="area_editar">
									  <?php
										foreach($areas as $area){
											echo "<option value='".$area['id']."'>".$area['descripcion']."</option>";
										}
									  ?>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="e_permission_crear"> Permisos Área Principal:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="e_permission_crear" name="permission_crear">Solicitar contratos<br>
                                    <input type="checkbox" value="1" id="e_permission_aprobar" name="permission_aprobar">Aprobar contratos<br>
                                    <input type="checkbox" value="1" id="e_permission_reportes" name="permission_reportes">Ver reportes<br>
									<input type="checkbox" value="1" id="e_permission_responsablearea" name="permission_responsablearea">Responsable área<br>
								</div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="e_permission_admin"> Superusuario:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="e_permission_admin" name="permission_admin">Administrar data
                                </div>
                            </div>
                        </div>
						
						<div class="control-group">
                            <label class="control-label" for="e_activo"> Estado:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="checkbox" value="1" id="e_activo" name="activo">Activo<br>
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
    $( '#dbasesContratos' ).addClass( "active" );
    $( '.activePlantilla1' ).html( "<a href='../menu/bases_contract.php'>Bases Contratos > <a href='index.php'><?=$NOMBRE_SHOW_PLURAL?></a></a>");

    $('#nueva_empresa_btn').on('click',function(){
        $("#desc_nuevo").val("");

        $("#desc_nuevo").focus();
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
                desc_editar: {
                    required: true
                },
				correo_nuevo: {
                    required: true,
                    email: true
                }
            },

            messages: {
                desc_editar: {
                    required: "El nombre es obligatorio."
                },
				correo_nuevo: {
                    required: "Correo es requerido",
                    email:"Correo no cumple el formato"
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
                
                var id =   $("#id_editar").val();
				var correo =   $("#correo_editar").val();
				var area = $("#area_editar").val();
				var tipo_usuario = $("#tipousuario_editar").val();
				
				var activo = 0;
				var permission_crear     = 0;
                var permission_aprobar   = 0;
                var permission_reportes  = 0;
				var permission_admin     = 0;
				var permission_responsablearea = 0;

				if($("#e_activo").is(':checked')) {
                    activo      = 1;
                }
                if($("#e_permission_crear").is(':checked')) {
                    permission_crear      = 1;
                }
				if($("#e_permission_aprobar").is(':checked')) {
                    permission_aprobar      = 1;
                }
				if($("#e_permission_reportes").is(':checked')) {
                    permission_reportes      = 1;
                }
				if($("#e_permission_admin").is(':checked')) {
                    permission_admin      = 1;
                }
				if($("#e_permission_responsablearea").is(':checked')) {
                    permission_responsablearea      = 1;
                }

                var parametros = {
                    "a" : activo,
                    "b" :id,
					"c" : permission_crear,
					"d" : permission_aprobar,
					"e" : permission_reportes,
					"f" : correo,
					"g" : permission_admin,
					"h" : permission_responsablearea,
					"i" : area,
					"j" : tipo_usuario
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
                desc_nuevo: {
                    required: true
                },
				correo_nuevo: {
                    required: true,
                    email: true
                }
            },

            messages: {
                desc_nuevo: {
                    required: "Usuario es obligatorio."
                },
				correo_nuevo: {
                    required: "Correo es requerido",
                    email:"Correo no cumple el formato"
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

                var nameNuevo = $("#desc_nuevo").val();
				var correo = $("#correo_nuevo").val();
				var area = $("#area_nuevo").val();
				var tipo_usuario = $("#tipousuario_nuevo").val();
				
				
				var permission_crear     = 0;
                var permission_aprobar   = 0;
                var permission_reportes  = 0;
				var permission_admin     = 0;
				var permission_responsablearea = 0;

                if($("#c_permission_crear").is(':checked')) {
                    permission_crear      = 1;
                }
				if($("#c_permission_aprobar").is(':checked')) {
                    permission_aprobar      = 1;
                }
				if($("#c_permission_reportes").is(':checked')) {
                    permission_reportes      = 1;
                }
				if($("#c_permission_admin").is(':checked')) {
                    permission_admin      = 1;
                }
				if($("#c_permission_responsablearea").is(':checked')) {
                    permission_responsablearea      = 1;
                }

                var parametros = {
                    "a" : nameNuevo,
					"c" : permission_crear,
					"d" : permission_aprobar,
					"e" : permission_reportes,
					"f" : correo,
					"g" : permission_admin,
					"h" : permission_responsablearea,
					"i" : area,
					"j" : tipo_usuario
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

        var id   = $(this).parent().parent().parent().children("td.hide_column").text();
        var desc = $(this).parent().parent().parent().children("td.desc").text();
		var correo = $(this).parent().parent().parent().children("td.correo").text();
		
		var ids = id.split("#");
		//alert(ids[0]+"*"+ids[1]+"*"+ids[2]+"*"+ids[3]+"*"+ids[4]+"*");
        $("#id_editar").val(ids[0]);
        $("#desc_editar").val(desc);
		$("#correo_editar").val(correo);
		if(ids[1] == "1"){document.getElementById("e_permission_crear").checked = true;}else{document.getElementById("e_permission_crear").checked = false;}
		if(ids[2] == "1"){document.getElementById("e_permission_aprobar").checked = true;}else{document.getElementById("e_permission_aprobar").checked = false;}
		if(ids[3] == "1"){document.getElementById("e_permission_reportes").checked = true;}else{document.getElementById("e_permission_reportes").checked = false;}
		if(ids[4] == "1"){document.getElementById("e_activo").checked = true;}else{document.getElementById("e_activo").checked = false;}
		if(ids[5] == "1"){document.getElementById("e_permission_admin").checked = true;}else{document.getElementById("e_permission_admin").checked = false;}
		if(ids[6] == "1"){document.getElementById("e_permission_responsablearea").checked = true;}else{document.getElementById("e_permission_responsablearea").checked = false;}

		$("#area_editar").val(ids[7]);
		$("#tipousuario_editar").val(ids[8]);
		
        setTimeout(function (){
            $("#desc_editar").focus();
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
