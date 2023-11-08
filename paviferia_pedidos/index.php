<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 23/08/2015
 * Time: 05:05 PM
 */

include("../plantilla1.php");
include("../phps/dpaviferia_formapago.php");
include("../phps/dpaviferia_pedido.php");

$NOMBRE_SHOW = "Pedido Paviferia";

$lstformadepago     = getAllFormaPago();
$editar             = false;
$idcliente          = 0;
$estadoPedido       = 0;

if(isset($_GET['serie_buscar']) && isset($_GET['numero_buscar'])){

    $existsped = getPedidoBySerieNumero($_GET['serie_buscar'],$_GET['numero_buscar']);

    if($existsped === false){
        echo "<script> $().toastmessage('showErrorToast', 'No se encontro Pedido'); </script>";
    }else{
        echo "<script> $().toastmessage('showSuccessToast', 'Exito Pedido fue encontrado'); </script>";
        $_GET['id'] = $existsped;
    }
}

if(!isset($_GET['id'])){//CREAR PEDIDO

    $modoEnvio = 0;//crear

    $fecha_respuesta    = date('Y/m/d');
    $fecha_venta        = date('Y/m/d');
    $formapagoelegida   = 0;

    $tipo_documento_dni = "selected='selected'";
    $tipo_documento_ruc = "";
    $nro_documento      = "";
    $nombre_rzsocial    = "";
    $direccion          = "";
    $filial             = "";

    $nombre_contacto    = "";
    $email_contacto     = "";
    $telefono_contacto  = "";
    $celular_contacto   = "";

    $detallepedido      = array();

    $subtotal_pav       = 0;
    $igv_pav            = 0;
    $total_pav          = 0;
	$fecha_valida		= "";

    $btn_text = "Guardar";

}else{//MOSTRAR/EDITAR PEDIDO

    if(is_numeric($_GET['id'])){

        $modoEnvio = $_GET['id'];//editar

        $editar = true;

        $pedido = getPedidoPaviferiaById($_GET['id']);

        $estadoPedido = $pedido['estadoPedido'];
        $idcliente    = $pedido['idcliente'];

        $serie_numero = "";
        $cerosserie   = "";
        $cerosnumero  = "";
        foreach (range(strlen(strval($pedido['serie'])), 2) as $i) {
            $cerosserie = $cerosserie."0";
        }
        foreach (range(strlen(strval($pedido['numero'])), 3) as $i) {
            $cerosnumero = $cerosnumero."0";
        }
        $serie_numero = $cerosserie.$pedido['serie']."-".$cerosnumero.$pedido['numero'];

        $fecha_respuesta    = date_format($pedido['fecharespuesta'], 'Y/m/d');
        $fecha_venta        = date_format($pedido['fechaventa'], 'Y/m/d');
        $fecha_valida       = date_format($pedido['fechavalida'], 'Y/m/d');
        $formapagoelegida   = $pedido['formadepago'];

        $tipo_documento_dni = "";
        $tipo_documento_ruc = "";
        if($pedido['tipodocumento'] == 1){
            $tipo_documento_dni = "selected='selected'";
        }
        if($pedido['tipodocumento'] == 2){
            $tipo_documento_ruc = "selected='selected'";
        }

        $nro_documento      = $pedido['nrodocumento'];
        $nombre_rzsocial    = $pedido['nombre_rzsocial'];
        $direccion          = $pedido['direccion'];
        $filial             = $pedido['filial'];

        $nombre_contacto    = $pedido['nombrecontacto'];
        $email_contacto     = $pedido['correocontacto'];
        $telefono_contacto  = $pedido['telefonofijo'];
        $celular_contacto   = $pedido['celularcontacto'];

        $detallepedido      = getDetallePedidoPaviferiaById($_GET['id']);

        $subtotal_pav       = $pedido['subtotal'];
        $igv_pav            = $pedido['igv'];
        $total_pav          = $pedido['total'];

        $btn_text = "Actualizar";

    }
}


?>

<div id="page-content" class="clearfix">
    <div class="row-fluid">
        <!--PAGE CONTENT BEGINS HERE-->

        <input id="auxidgrupo" type="hidden" value="0"  />

        <div  style="float: right">
            <a href="index.php" class="btn  btn-primary"
               alt="Nuevo" title="Nuevo">
                <i class="icon-plus-sign"></i>
            </a>
            <a data-toggle="modal" id="nueva_empresa_btn" href="#nueva_actividad" class="btn btn-primary"
               alt="Buscar por codigo" title="Buscar por codigo">
                <i class=" icon-screenshot"></i>
            </a>
            <a id="imprimir" href="#" class="btn btn-primary" onclick="imprimir(<?=isset($_GET['id'])?$_GET['id']:0?>)"
               alt="Imprimir" title="Imprimir">
                <i class="icon-print"></i>
            </a>
            <a href="listarCotizacion.php" class="btn btn-success"
               alt="Listar Cotizaciones" title="Listar Cotizaciones">
                <i class="icon-list-ol"></i>
            </a>

            <?php if($estadoPedido == 1){ ?>
                <a data-toggle="modal" id="cambiar_estado_btn" href="#cambiar_estado" class="btn btn-warning"
                   alt="Cambiar Estado" title="Cambiar Estado">
                    <i class="icon-cogs"></i>
                </a>
            <?php } ?>

        </div>

        <form class="form-horizontal" id="validation-form_nuevo" method="post" novalidate="novalidate" autocomplete="off">

            <div class="row-fluid">
                <h5 class="header smaller lighter blue">Datos Cotizacion</h5>

                <?php
                if($editar === true){
                    ?>
                    <span class="label label-success">Cotizacion: <?=$serie_numero?></span>
                    <span class="label label-success">Fecha de Emision: <?=$pedido['fechaEmision']?></span>
                    <span class="label label-success">Usuario Registra: <?=$pedido['username']?></span>

                    <?php if($estadoPedido == 1){?>
                        <span class="label label-purple">Cotizacion EMITIDA</span>
                    <?php }?>

                    <?php if($estadoPedido == 2){?>
                        <span class="label label-important">Cotizacion CERRADA</span>
                    <?php }?>

                    <?php if($estadoPedido == 3){?>
                        <span class="label label-info">Cotizacion ACEPTADA</span>
                    <?php }?>

                    <?php

                }

                ?>


                <table border="0" cellpadding="0" cellspacing="0" width="100%">


                    <tr>
                        <td>
                            <label class="control-label" for="fecha_venta">Fecha Venta:</label>

                            <div class="control-group">
                                <input class="span6 date-picker" name="fecha_venta" id="fecha_venta" type="text"
                                       data-date-format="yyyy/mm/dd"
                                       value="<?=$fecha_venta?>"/>
                            </div>
                        </td>
                        <td>
                            <label class="control-label" for="fecha_valida">Valida Hasta:</label>

                            <div class="control-group">
                                <input class="span6 date-picker" name="fecha_valida" id="fecha_valida" type="text"
                                       data-date-format="yyyy/mm/dd"
                                       value="<?=$fecha_valida?>"/>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="control-label" for="fecha_emision">Fecha Respuesta:</label>

                            <div class="control-group">
                                <input class="span6 date-picker" name="fecha_emision" id="fecha_emision" type="text"
                                       data-date-format="yyyy/mm/dd"
                                       value="<?=$fecha_respuesta?>"/>
                            </div>
                        </td>


                        <td>

                            <label class="control-label" for="formadepago">Forma de Pago:</label>

                            <div class="control-group">

                                <select class="span6" id="formadepago" name="formadepago">
                                    <?php
                                    foreach($lstformadepago as $formadepago){

                                        $formapago_seleccionada = "";
                                        if($formapagoelegida === $formadepago['id'])
                                            $formapago_seleccionada = "selected='selected'";

                                        echo "<option value='".$formadepago['id']."' ".$formapago_seleccionada." >".$formadepago['descripcion']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            <label class="control-label" for="tipo_documento"> Tipo Documento:</label>

                            <div class="control-group">

                                <select class="span6" id="tipo_documento" name="tipo_documento">
                                    <option value="1" <?=$tipo_documento_dni?>>DNI</option>
                                    <option value="2" <?=$tipo_documento_ruc?>>RUC</option>
                                </select>
                            </div>
                        </td>

                        <td>
                            <label class="control-label" for="nro_documento">Numero Documento:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="nro_documento" id="nro_documento" class="span6" value="<?=$nro_documento?>">
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <label class="control-label" for="nombre_rzsocial"> Nombre/Razón Social:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="nombre_rzsocial" id="nombre_rzsocial" class="span6 uppercase" value="<?=$nombre_rzsocial?>">
                            </div>
                        </td>

                        <td>
                            <label class="control-label" for="direccion">  Direccion:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="direccion" id="direccion" class="span6" value="<?=$direccion?>">
                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td>
                            <label class="control-label" for="filial">  Filial:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="filial" id="filial" class="span6" value="<?=$filial?>">
                            </div>

                        </td>

                    </tr>



                    <tr>

                        <td>
                            <label class="control-label" for="nombre_contacto">Nombre Contacto:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="nombre_contacto" id="nombre_contacto" class="span6 uppercase" value="<?=$nombre_contacto?>">
                            </div>
                        </td>

                        <td>
                            <label class="control-label" for="email_contacto">E-mail Contacto:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="email_contacto" id="email_contacto" class="span6" value="<?=$email_contacto?>">
                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <label class="control-label" for="telefono_contacto">Telefono Contacto:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="telefono_contacto" id="telefono_contacto" class="span6" value="<?=$telefono_contacto?>">
                            </div>
                        </td>

                        <td>
                            <label class="control-label" for="celular_contacto">Celular Contacto:</label>

                            <div class="control-group">
                                <input autofocus type="text" autocomplete="off"
                                       name="celular_contacto" id="celular_contacto" class="span6" value="<?=$celular_contacto?>">
                            </div>
                        </td>

                    </tr>

                </table>

                <h5 class="header smaller lighter blue">Detalle Cotizacion

                    <?php
                    if($estadoPedido != 2 && $estadoPedido !=3) {
                        ?>

                        <button type="button" class="btn btn-mini btn-success" onclick="calcularPrecios()"
                                title="Actualizar Precios">
                            <i title="Actualizar Precios" class="icon-refresh icon-only bigger-150"></i>
                        </button>

                        <?php
                    }
                    ?>

                </h5>

                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            Agregar por ID Producto:
                            <input autofocus id="autocompletebyid" type="text" name="autocompletebyid" maxlength="50"  value="" class="span2">
                        </td>
                        <td>
                            Agregar por Nombre:
                            <input autofocus id="autocompletebyname" type="text" name="autocompletebyname" maxlength="50"  value="" class="span6">
                        </td>
                    </tr>
                </table>


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
                                 style="width: 60px;;font-size: 11px">Item
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 200px;;font-size: 11px">Producto
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 60px;;font-size: 11px">UN
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 60px;;font-size: 11px">Cantidad
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 90px;;font-size: 11px">P. BSE. (S/)
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 80px;;font-size: 11px">Dsto (%)
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 90px;;font-size: 11px">Subtotal(S/)
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 80px;;font-size: 11px">IGV (S/)
                            </th>
                            <th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1"
                                colspan="1"
                                style="width: 110px;;font-size: 11px">Importe (S/)
                            </th>
                            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 50px;;font-size: 11px">
                                Eliminar
                            </th>

                        </tr>
                        </thead>


                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                        <?php

                        if(!empty($detallepedido) && $detallepedido != false){

                            foreach($detallepedido as $d){

                                echo "<tr class=\"odd\" style='font-size: 11px'>";
                                echo "<td>".$d['id']."</td>";
                                echo "<td class=\"id\">".$d['nroitem']."</td>";
                                echo "<td class=\"desc\">".$d['productodesc']."</td>";
                                echo "<td class=\"desc\"><input type=\"text\" style=\"width: 60px;\" class=\"cantProd\" value=\"".$d['unidades']."\"></td>";
                                echo "<td class=\"desc\">".$d['kilogramos'].' '.getUnidadMedicaById($d['idproducto'])."</td>";
                                echo "<td class=\"desc\">".$d['precio']."</td>";
                                echo "<td class=\"desc\">".$d['descuento']."</td>";
                                echo "<td class=\"desc\">".$d['subtotal']."</td>";
                                echo "<td class=\"desc\">".$d['igv']."</td>";
                                echo "<td class=\"desc\">".$d['total']."</td>";

                                if($estadoPedido != 2 && $estadoPedido !=3){
                                    echo $ELIMINAR_HTML_CODE;
                                }else{
                                    echo "<td></td>";
                                }

                                echo "</tr>";

                            }
                        }

                        ?>

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">

                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                           style=" display: inline-block;height: 75px;margin: 10px;float: right">
                        <tr>
                            <td>Subtotal:</td><td>
                                <input autofocus type="text" id="pav_subtotal"
                                       readonly="readonly" maxlength="50"  value="<?=$subtotal_pav?>" style="text-align: center">
                            </td>
                            <td>
                                IGV:</td><td>
                                <input autofocus type="text" id="pav_igv"
                                       readonly="readonly" maxlength="50"  value="<?=$igv_pav?>" style="text-align: center">
                            </td>
                            <td>
                                Total:</td><td>
                                <input autofocus type="text" id="pav_total"
                                       readonly="readonly" maxlength="50"  value="<?=$total_pav?>" style="text-align: center">
                            </td>
                        </tr>
                    </table>

                    <?php
                    if($estadoPedido != 2 && $estadoPedido !=3){
                        ?>

                        <input type="submit" class="btn btn-primary" value="<?=$btn_text?>">

                        <?php
                    }
                    ?>
                </div>

        </form>

        <div class="row-fluid">
            <div class="modal fade" id="nueva_actividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Ingrese Codigo</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="validation-form_nuevo" method="get" novalidate="novalidate" action="index.php">

                                <div class="control-group">
                                    <label class="control-label" for="desc_nuevo">Codigo :</label>

                                    <div class="controls">
                                        <div class="span12">
                                            <input type="number" name="serie_buscar" class="span3 uppercase" autocomplete="off">-
                                            <input type="number" name="numero_buscar" class="span4 uppercase" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <input type="submit" class="btn btn-primary" value="Buscar">
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>

        </div>


        <?php
        if($estadoPedido != 2 && $estadoPedido !=3) {
            ?>

            <div class="row-fluid">
                <div class="modal fade" id="cambiar_estado" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Seleccione nuevo estado de la Cotizacion:</h4>
                            </div>
                            <div class="modal-body">

                                <div class="control-group">
                                    <label class="control-label" for="estado_nuevo">Estado :</label>

                                    <div class="controls">
                                        <select class="span6" id="estado_nuevo" name="estado_nuevo">
                                            <option value="3">Aceptada</option>
                                            <option value="2">Cerrada</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                    </button>
                                    <input type="button" class="btn btn-primary"
                                           onclick="cambiarEstado(<?= isset($_GET['id']) ? $_GET['id'] : 0 ?>)"
                                           value="Aceptar">
                                </div>

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>

            </div>

            <?php
        }
        ?>
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
    $( '.activePlantilla1' ).html( "<a href='index.php'>Crear Cotizacion Paviferia</a>");

    function isOkGrupo(idgrupo_nuevoproducto){

        var idgrupoactual = $("#auxidgrupo").val();
        var rows          = $("#table_report").dataTable().fnGetNodes();

        if(idgrupoactual == 0 || rows.length == 0){//No se ha agregado ningun producto
            $("#auxidgrupo").val(idgrupo_nuevoproducto);
        }else{
            if(idgrupoactual != idgrupo_nuevoproducto){
                return false;
            }
        }

        return true;

    }

    function imprimir(id){
        var pagina;
        var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no,scrollbars=YES, resizable=yes, width=880, height=500, top=85, left=100";
        pagina="frmPedidoPDF.php?id="+id;
        window.open(pagina,"",opciones);

    }

    <?php
               if($estadoPedido != 2 && $estadoPedido !=3){
    ?>


    function cambiarEstado(id) {

        var estadoNuevo = $("#estado_nuevo option:selected").val();

        var val = confirm("¿Confirma la el cambio del estado de la Cotizacion?");
        if (val == true) {

            $.post("anular.php", {a: id, b: estadoNuevo}, function (respuesta) {

                respuesta = $.parseJSON(respuesta);

                if (respuesta.estado == 1) {
                    $().toastmessage('showSuccessToast', 'Exito');

                    setTimeout(function () {
                        location.reload();
                    }, <?php echo $SLEEP_TIME ?>);

                } else {
                    $().toastmessage('showErrorToast', respuesta.error);
                }


            });
        }

    }

    function detallePedidoToArray() {

        var rows = $("#table_report").dataTable().fnGetNodes();

        var detallepedidoaux = new Array();
        var dp_idproducto = 0;
        var dp_cantidadpedido = 0;
        var dp_kgs = 0;
        var dp_precio = 0;
        var dp_dscto = 0;
        var dp_igv = 0;
        var dp_importe = 0;
        var nro_item = 0;

        for (var i = 0; i < rows.length; i++) {

            try {
                dp_idproducto = parseInt($(rows[i]).find("td:eq(0)").html());
                dp_cantidadpedido = parseInt($(rows[i]).find("input").val());
                dp_kgs = parseFloat($(rows[i]).find("td:eq(4)").html());
                dp_precio = parseFloat($(rows[i]).find("td:eq(5)").html());
                dp_dscto = parseFloat($(rows[i]).find("td:eq(6)").html());
                dp_igv = parseFloat($(rows[i]).find("td:eq(7)").html());
                dp_importe = parseFloat($(rows[i]).find("td:eq(8)").html());
                nro_item = parseFloat($(rows[i]).find("td:eq(1)").html());

                var lista = {
                    idproducto: dp_idproducto, cantidad: dp_cantidadpedido,
                    kgs: dp_kgs, precio: dp_precio,
                    dscto: dp_dscto, igv: dp_igv,
                    importe: dp_importe, nro_item: nro_item
                };

                detallepedidoaux.push(lista);
            }
            catch (err) {
                alert("Error: " + err);
                return false;
            }
        }

        return detallepedidoaux;
    }

    function calcularPrecios() {

        //mediante ajax calculo los precios, descuento, igv y importes de los productos agregados y del pedido

        var formadepago   = $("#formadepago option:selected").val();
        var fechaventa    = $("#fecha_venta").val();
        var nro_documento = $("#nro_documento").val();
        var datapedido    = JSON.stringify(detallePedidoToArray());

        $.post("calcularPreciosAjax.php", {a: datapedido, b: formadepago, c: fechaventa, d: nro_documento}, function (data) {

            var oTable1 = $("#table_report").dataTable();
            var rows = oTable1.fnGetNodes();

            oTable1.fnClearTable();

            var respuesta = $.parseJSON(data);
            var detallepedido = $.parseJSON(respuesta.detallepedido);
            var pedido = $.parseJSON(respuesta.pedido);

            $("#pav_subtotal").val(pedido.subtotal);
            $("#pav_igv").val(pedido.igv);
            $("#pav_total").val(pedido.total);

            for (var i = 0; i < detallepedido.length; i++) {

                oTable1.fnAddData(
                    [detallepedido[i].idproducto,
                        detallepedido[i].nro_item,
                        detallepedido[i].productodesc,
                        '<input type="text" style="width: 60px;" class="cantProd" value="' + detallepedido[i].unidades + '">',
                        detallepedido[i].kgs+" "+ detallepedido[i].unidadmedida,
                        detallepedido[i].precio,
                        detallepedido[i].descuento,
                        detallepedido[i].subtotal,
                        detallepedido[i].igv,
                        detallepedido[i].importe,
                        '<button alt="Eliminar" type="button" title="Eliminar" class="btn btn-mini btn-danger"><i  class="icon-trash bigger-120"></i></button>'
                    ]);

            }

        });
    }

    function actualizarSubtotalIgvTotal() {

        var rows = $("#table_report").dataTable().fnGetNodes();

        var total = parseFloat(0);
        var igv = parseFloat(0);
        var subtotal = parseFloat(0);

        for (var i = 0; i < rows.length; i++) {

            igv = igv + parseFloat($(rows[i]).find("td:eq(7)").html());
            total = total + parseFloat($(rows[i]).find("td:eq(8)").html());
        }

        subtotal = total - igv;

        $("#pav_subtotal").val(subtotal.toFixed(2));
        $("#pav_igv").val(igv.toFixed(2));
        $("#pav_total").val(total.toFixed(2));
    }

    <?php
          }
    ?>


</script>

<!--inline scripts related to this page-->
<script type="text/javascript">
    $(function () {
        var oTable1 = $('#table_report').dataTable({
            "bPaginate": false,
            "bFilter"  : false,
            "bInfo"    : false,
            "bSort": false,
            "aoColumns": [
                {"sClass": "hide_column"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                {"sClass": "row_css"},
                { "bSortable": false }
            ] ,
            "aaSorting": [
                [ 1, "asc" ]
            ]
        });

        <?php
               if($estadoPedido != 2 && $estadoPedido !=3){
        ?>

        $("#autocompletebyname").autocomplete("getProductInformation.php", {
            width: 265,
            matchContains: true,
            minChars: 1,
            selectFirst: true,
            cacheLength: 0,
            extraParams: {mode_filter : 1,cantidad : 0, idproducto: 0}
        });

        $("#autocompletebyname").result(function(event, data, formatted) {

            var rows = oTable1.fnGetNodes();
            var aux  = 0;
            var exist = false;

            //Verifico que el producto agregado seha del mismo grupo que los productos que existen
            if(!isOkGrupo(data[5])){
                $("#autocompletebyname").val("");
                alert("Validacion: Solo se pueden agregar productos del mismo GRUPO");
                return false;
            }

            //verifico nuevo producto no exista
            for(var i=0;i<rows.length;i++){

                aux = $(rows[i]).find("td:eq(0)").html();

                if(parseInt(aux) == parseInt(data[1])){
                    exist = true;
                }
            }

            if(exist) {

                if(exist)
                    alert("Error: El producto " + data[0] + " ya esta agregado");

            }else{

                oTable1.fnAddData(
                    [data[1],
                        oTable1.fnGetData().length+1,
                        data[4],
                        '<input type="text" style="width: 60px;" class="cantProd" value="'+data[2]+'">',
                        data[3],
                        0,
                        0,
                        0,
                        0,
                        0,
                        '<button alt="Eliminar" type="button" title="Eliminar" class="btn btn-mini btn-danger"><i  class="icon-trash bigger-120"></i></button>']);

                calcularPrecios();

            }

            $("#autocompletebyname").val("");
        });



        $("#autocompletebyid").autocomplete("getProductInformation.php", {
            width: 265,
            matchContains: true,
            minChars: 1,
            selectFirst: true,
            cacheLength: 0,
            extraParams: {mode_filter : 2,cantidad : 0, idproducto: 0}
        });

        $("#autocompletebyid").result(function(event, data, formatted) {

            var rows = oTable1.fnGetNodes();
            var aux  = 0;
            var exist = false;

            //Verifico que el producto agregado seha del mismo grupo que los productos que existen
            if(!isOkGrupo(data[5])){
                $("#autocompletebyid").val("");
                alert("Validacion: Solo se pueden agregar productos del mismo GRUPO");
                return false;
            }

            //verifico nuevo producto no exista
            for(var i=0;i<rows.length;i++){

                aux = $(rows[i]).find("td:eq(0)").html();

                if(parseInt(aux) == parseInt(data[1])){
                    exist = true;
                }
            }


            if(exist) {

                if(exist)
                    alert("Error: El producto " + data[0] + " ya esta agregado");

            }else{

                oTable1.fnAddData(
                    [data[1],
                        oTable1.fnGetData().length+1,
                        data[4],
                        '<input type="text" style="width: 60px;" class="cantProd" value="'+data[2]+'">',
                        data[3],
                        0,
                        0,
                        0,
                        0,
                        0,
                        '<button alt="Eliminar" title="Eliminar" class="btn btn-mini btn-danger"><i  class="icon-trash bigger-120"></i></button>']);

                calcularPrecios();
            }

            $("#autocompletebyid").val("");
        });

        $("#nro_documento").autocomplete("getClientInformation.php", {
            width: 400,
            matchContains: true,
            minChars: 1,
            selectFirst: true
        });

        $("#nro_documento").result(function(event, data, formatted) {

            $("#nro_documento").val(data[1]);
            $("#tipo_documento").val(data[2]);
            $("#nombre_rzsocial").val(data[3]);
            $("#direccion").val(data[4]);
            $("#nombre_contacto").val(data[5]);
            $("#email_contacto").val(data[6]);
            $("#telefono_contacto").val(data[7]);
            $("#celular_contacto").val(data[8]);
            $("#filial").val(data[9]);
        });

        $('#table_report tbody').on( 'keyup', 'input', function () {

            //calcularPrecios();
        } );

        $('#validation-form_nuevo').validate({
            errorElement: 'span',
            errorClass: 'help-inline',
            focusInvalid: false,
            onkeyup: false,
            onclick: false,
            // onfocusout: true,
            rules: {
                fecha_venta: {
                    required: true,
                    date: true
                },
                fecha_emision: {
                    required: true,
                    date: true
                },
                formadepago: {
                    required: true
                },
                tipo_documento: {
                    required: true
                },
                nro_documento: {
                    required: true,
                    digits: true
                },
                nombre_rzsocial: {
                    required: true
                },
                direccion: {
                    required: true
                },
                nombre_contacto: {
                    required: true
                },
                fecha_valida:{
                    required: true
                }/*,
                 email_contacto: {
                 required: true,
                 email: true
                 },
                 telefono_contacto: {
                 required: true
                 },
                 celular_contacto: {
                 required: true
                 }*/
            },

            messages: {
                fecha_venta: {
                    required: "Fecha de Venta es requerida",
                    date: "Fecha de Venta no cumple formato de fecha"
                },
                fecha_valida: {
                    required: "Fecha Valida Hasta es requerida",
                    date: "Fecha de Valida no cumple formato de fecha"
                },
                fecha_emision: {
                    required: "Fecha de Emision es requerida",
                    date: "Fecha de Emision no cumple formato de fecha"
                },
                formadepago: {
                    required: "Forma de pago es requerido"
                },
                tipo_documento: {
                    required: "Tipo de Documento es requerido"
                },
                nro_documento: {
                    required: "Nro de Documento es requerido",
                    digits: "Nro de Documento debe contener solo numeros"
                },
                nombre_rzsocial: {
                    required: "Nombre/Razón Social es requerido"
                },
                direccion: {
                    required: "Direccion es requerida"
                },
                nombre_contacto: {
                    required: "Nombre Contacto es requerido"
                },
                email_contacto: {
                    required: "E-mail Contacto requerido",
                    email: "E-mail no cumple el formato de email"
                },
                telefono_contacto: {
                    required: "Telefono Contacto requerido",
                },
                celular_contacto: {
                    required: "Celular Contacto requerido"
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
                /*
                 if (element.is(':checkbox') || element.is(':radio')) {
                 var controls = element.closest('.controls');
                 if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                 else error.insertAfter(element.nextAll('.lbl').eq(0));
                 }
                 else if (element.is('.chzn-select')) {
                 error.insertAfter(element.nextAll('[class*="chzn-container"]').eq(0));
                 }
                 else error.insertAfter(element);
                 */

                if (error.text() != "")
                    $().toastmessage('showErrorToast', error.text());
                else
                    error.insertAfter(element);
            },

            submitHandler: function (form) {

                var fecha_venta     = $("#fecha_venta").val();
                var fecha_valida    = $("#fecha_valida").val();
                var fecha_emision   = $("#fecha_emision").val();
                var formadepago     = $("#formadepago option:selected").val();
                var tipo_documento  = $("#tipo_documento").val();
                var nro_documento   = $("#nro_documento").val();
                var nombre_rzsocial = $("#nombre_rzsocial").val();
                var direccion       = $("#direccion").val();
                var filial          = $("#filial").val();

                var nombre_contacto     = $("#nombre_contacto").val();
                var email_contacto      = $("#email_contacto").val();
                var telefono_contacto   = $("#telefono_contacto").val();
                var celular_contacto    = $("#celular_contacto").val();
                var subtotal            = $("#pav_subtotal").val();
                var igv                 = $("#pav_igv").val();
                var total               = $("#pav_total").val();

                var detallepedidoaux = detallePedidoToArray();

                var detallepedido = JSON.stringify(detallepedidoaux);


                var parametros = {
                    "a": detallepedido,
                    "b": fecha_emision,
                    "c": formadepago,
                    "d": tipo_documento,
                    "e": nro_documento,
                    "f": nombre_rzsocial,
                    "g": direccion,
                    "h": nombre_contacto,
                    "i": email_contacto,
                    "j": telefono_contacto,
                    "k": celular_contacto,
                    "l": subtotal,
                    "m": igv,
                    "n": total,
                    "o": <?=$modoEnvio?>,
                    "p": <?=$idcliente?>,
                    "q": fecha_venta,
                    "r": filial,
                    "s": fecha_valida
                };

                $.ajax({
                    data: parametros,
                    url: 'nuevo.php',
                    type: 'post',
                    dataType: "html",
                    beforeSend: function (repuesta) {
                        // lo que se hace mientras llega
                        $('#nueva_actividad').modal('hide');
                        openModal();
                    },
                    success: function (respuesta) {

                        respuesta = $.parseJSON(respuesta);

                        if (respuesta.estado == "1") {

                            closeModal();
                            $().toastmessage('showSuccessToast', '<?=$SUCCES_MESSAGE?>');
                            //oTable1.fnAddData([respuesta.id,respuesta.id,respuesta.descripcion,'']);
                            setTimeout(function () {
                                location.assign("index.php?id=" + respuesta.id);
                            }, <?php echo $SLEEP_TIME ?>);

                        } else {

                            closeModal();
                            $().toastmessage('showErrorToast', '<?=$ERROR_MESSAGE?>');
                            alert(respuesta.error);
                            //location.reload();
                        }
                    },
                    error: function (respuesta) {
                        closeModal();
                        alert("Error al conectar con el servidor");
                    },
                    failure: function (respuesta) {
                        closeModal();
                        alert("Error al conectar con el servidor");
                    }
                });


            },
            invalidHandler: function (form) {
            }
        });


        $('#table_report tbody').on( 'click', 'button', function () {

            if(confirm("¿Seguro que desea eliminar el item?")){

                var target_row = $(this).closest("tr").get(0);
                var aPos = oTable1.fnGetPosition(target_row);
                oTable1.fnDeleteRow( aPos );

                var rows = oTable1.fnGetNodes();
                var aux = 0;

                //vuelvo a setear la fila ITEM
                for(var i=0;i<rows.length;i++){

                    aux = i + 1;
                    $(rows[i]).find("td:eq(1)").html(aux);
                }
            }
        } );

        <?php
                 }
       ?>

        $('[data-rel=tooltip]').tooltip();

        $('.date-picker').datepicker();

        $('table th input:checkbox').on('click', function () {
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });

        });

    })


</script>

</body>
</html>
