<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 27/08/2015
 * Time: 12:53 AM
 */

include_once("conexion.php");
include_once("dpaviferia_productos.php");

function getAllCotizaciones(){

    $sql = "select
            paviferia_pedido.id,
            paviferia_pedido.serie,
            paviferia_pedido.numero,
            paviferia_pedido.estado as estadoCot,
            convert(varchar, paviferia_pedido.fecharegistra, 103) as fechaEmision,
            paviferia_vendedor.usuario as username,
            paviferia_zona.descripcion as paviferia_zona,
            paviferia_formapago.descripcion as formapagodesc,
            (SELECT CASE paviferia_cliente.tipodocumento WHEN '1' THEN 'DNI' ELSE
            (SELECT CASE paviferia_cliente.tipodocumento WHEN '2' THEN 'RUC' ELSE '' END) END) as tipodocumento,
            paviferia_cliente.nrodocumento,
            paviferia_cliente.nombre_rzsocial,
            paviferia_cliente.direccion,
            paviferia_cliente.nombrecontacto,
            paviferia_cliente.correocontacto,
            paviferia_cliente.telefonofijo,
            paviferia_cliente.celularcontacto,
            paviferia_pedidodetalle.nroitem,
            paviferia_producto.id as idproducto,
            paviferia_producto.descripcion as productoname,
            paviferia_pedidodetalle.unidades,
            paviferia_pedidodetalle.kilogramos,
            paviferia_pedidodetalle.precio,
            paviferia_pedidodetalle.descuento,
            paviferia_pedidodetalle.subtotal,
            paviferia_pedidodetalle.igv,
            paviferia_pedidodetalle.total,
            paviferia_pedido.subtotal as subtotalpedido,
            paviferia_pedido.igv as igvpedido,
            paviferia_pedido.total as totalpedido
            from paviferia_pedidodetalle
            inner join paviferia_pedido on paviferia_pedido.id = paviferia_pedidodetalle.idpedido
            inner join paviferia_producto on paviferia_producto.id = paviferia_pedidodetalle.idproducto
            inner join paviferia_cliente on paviferia_cliente.id = paviferia_pedido.idcliente
            inner join paviferia_vendedor on paviferia_vendedor.id = paviferia_pedido.usuarioregistra
            inner join paviferia_zona on paviferia_zona.id = paviferia_pedido.serie
            inner join paviferia_formapago on paviferia_formapago.id = paviferia_pedido.formadepago";


    $link = conectarBD();

    $data = array();
    $stmt = sqlsrv_query( $sql, $link );

    if( $stmt === false) {
        return false;
    }else{
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $cerosserie   = "";
            $cerosnumero  = "";
            foreach (range(strlen(strval($row['serie'])), 2) as $i) {
                $cerosserie = $cerosserie."0";
            }
            foreach (range(strlen(strval($row['numero'])), 3) as $i) {
                $cerosnumero = $cerosnumero."0";
            }
            $serie_numero = $cerosserie.$row['serie']."-".$cerosnumero.$row['numero'];

            $row["codigo"] = $serie_numero;
            $row["kilogramosdesc"] = $row["kilogramos"]." ".getUnidadMedicaById($row['idproducto']);

            if($row['estadoCot'] == 1){
                $row['estadoCot1'] = "Emitida";
            }
            if($row['estadoCot'] == 2){
                $row['estadoCot1'] = "Cerrada";
            }
            if($row['estadoCot'] == 3){
                $row['estadoCot1'] = "Aceptada";
            }

            $data[] = $row;
        }
    }

    return $data;
}

function getAllCotizaciones2(){

    $sql = "select DISTINCT
            paviferia_pedido.id,
            paviferia_pedido.serie,
            paviferia_pedido.numero,
             paviferia_pedido.estado,
            paviferia_pedido.fecharegistra,
            convert(varchar, paviferia_pedido.fecharegistra, 103) as fechaEmision,
            paviferia_vendedor.usuario as username,
            paviferia_zona.descripcion as paviferia_zona,
            (SELECT CASE paviferia_cliente.tipodocumento WHEN '1' THEN 'DNI' ELSE
            (SELECT CASE paviferia_cliente.tipodocumento WHEN '2' THEN 'RUC' ELSE '' END) END) as tipodocumento,
            paviferia_cliente.nombre_rzsocial,
            paviferia_pedido.total
            from paviferia_pedido
            inner join paviferia_cliente on paviferia_cliente.id = paviferia_pedido.idcliente
            inner join paviferia_vendedor on paviferia_vendedor.id = paviferia_pedido.usuarioregistra
            inner join paviferia_zona on paviferia_zona.id = paviferia_pedido.serie
			where paviferia_pedido.fecharegistra between '".date("Y")."-01-01' and '".date("Y")."-12-31'
            order by fecharegistra desc ";


    $link = conectarBD();

    $data = array();
    $stmt = sqlsrv_query( $link, $sql );

    if( $stmt === false) {
        return false;
    }else{
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $cerosserie   = "";
            $cerosnumero  = "";
            foreach (range(strlen(strval($row['serie'])), 2) as $i) {
                $cerosserie = $cerosserie."0";
            }
            foreach (range(strlen(strval($row['numero'])), 3) as $i) {
                $cerosnumero = $cerosnumero."0";
            }
            $serie_numero = $cerosserie.$row['serie']."-".$cerosnumero.$row['numero'];

            $row["codigo"] = $serie_numero;

            $data[] = $row;
        }
    }

    return $data;
}


function getAllCotizaciones3($opcion=1,$fechIni,$fechaFin,$rangoIni,$rangoFin){

    $sql_filter = "";
    if($opcion == 1){
        $sql_filter = " where paviferia_pedido.fecharegistra between '$fechIni' and '$fechaFin'";
    }
    if($opcion == 2){
        $sql_filter = " where paviferia_pedido.numero between $rangoIni and $rangoFin";
    }

    $sql = "select DISTINCT
            paviferia_pedido.id,
            paviferia_pedido.serie,
            paviferia_pedido.numero,
            paviferia_pedido.estado,
            paviferia_pedido.fecharegistra,
            convert(varchar, paviferia_pedido.fecharegistra, 103) as fechaEmision,
            paviferia_vendedor.usuario as username,
            paviferia_zona.descripcion as paviferia_zona,
            (SELECT CASE paviferia_cliente.tipodocumento WHEN '1' THEN 'DNI' ELSE
            (SELECT CASE paviferia_cliente.tipodocumento WHEN '2' THEN 'RUC' ELSE '' END) END) as tipodocumento,
            paviferia_cliente.nombre_rzsocial,
            paviferia_pedido.total
            from paviferia_pedido
            inner join paviferia_cliente on paviferia_cliente.id = paviferia_pedido.idcliente
            inner join paviferia_vendedor on paviferia_vendedor.id = paviferia_pedido.usuarioregistra
            inner join paviferia_zona on paviferia_zona.id = paviferia_pedido.serie
            $sql_filter
            order by fecharegistra desc ";


    $link = conectarBD();

    $data = array();
    $stmt = sqlsrv_query( $link, $sql );

    if( $stmt === false) {
        return false;
    }else{
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $cerosserie   = "";
            $cerosnumero  = "";
            foreach (range(strlen(strval($row['serie'])), 2) as $i) {
                $cerosserie = $cerosserie."0";
            }
            foreach (range(strlen(strval($row['numero'])), 3) as $i) {
                $cerosnumero = $cerosnumero."0";
            }
            $serie_numero = $cerosserie.$row['serie']."-".$cerosnumero.$row['numero'];

            $row["codigo"] = $serie_numero;

            $data[] = $row;
        }
    }

    return $data;

}


function getPedidoBySerieNumero($serie_buscar,$numero_buscar){

    $sql = "select id
            from paviferia_pedido
            where paviferia_pedido.serie = $serie_buscar and paviferia_pedido.numero = $numero_buscar";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if(empty($data)){
        return false;
    }else{
        return $data['id'];
    }
}

function getPedidoPaviferiaById($id){

    $sql = "select * , convert(varchar, paviferia_pedido.fecharegistra, 103) as fechaEmision, paviferia_vendedor.usuario as username,
                   paviferia_pedido.estado as estadoPedido, paviferia_formapago.descripcion as formapagodesc,
                   paviferia_pedido.id as idpedido
            from paviferia_pedido
            inner join paviferia_cliente on paviferia_cliente.id = paviferia_pedido.idcliente
            inner join paviferia_vendedor on paviferia_vendedor.id = paviferia_pedido.usuarioregistra
            inner join paviferia_formapago on paviferia_formapago.id = paviferia_pedido.formadepago
            where paviferia_pedido.id = $id";

    $link = conectarBD();

    $row = array();
    $stmt = sqlsrv_query( $link, $sql );

    if( $stmt === false) {
        return false;
    }else{
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $cerosserie   = "";
        $cerosnumero  = "";
        foreach (range(strlen(strval($row['serie'])), 2) as $i) {
             $cerosserie = $cerosserie."0";
        }
        foreach (range(strlen(strval($row['numero'])), 3) as $i) {
             $cerosnumero = $cerosnumero."0";
        }
        $serie_numero = $cerosserie.$row['serie']."-".$cerosnumero.$row['numero'];

        $row["codigo"] = $serie_numero;
    }

    return $row;
}

function getDetallePedidoPaviferiaById($id){

    $sql = "select * , paviferia_producto.descripcion as productodesc,paviferia_pedidodetalle.idproducto as idproducto
            from paviferia_pedidodetalle
            inner join paviferia_producto on paviferia_producto.id = paviferia_pedidodetalle.idproducto
            where idpedido = $id";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getDetallePedidoPaviferiaById2($id){

    $sql = "select * , paviferia_producto.descripcion as productodesc,paviferia_pedidodetalle.idproducto as idproducto,
                    paviferia_precio.fechainicio, paviferia_precio.fechafin
            from paviferia_pedidodetalle
            inner join paviferia_producto on paviferia_producto.id = paviferia_pedidodetalle.idproducto
            left join paviferia_precio on paviferia_precio.id = paviferia_pedidodetalle.idprecio
            where idpedido = $id";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function cambiarEstadoPedidoPaviferia($idpedido,$estado,$idusuario){

    $sql = "update paviferia_pedido set estado = $estado, idusuarioCambiaEstado = $idusuario where id = $idpedido";

    $link = conectarBD();
    $res = queryBD($sql,$link);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function registrarPedidoPaviferia($detallepedido,$fecha_respuesta,$formadepago,$tipo_documento,
                                  $nro_documento,$nombre_rzsocial,$direccion,$nombre_contacto,
                                  $email_contacto,$telefono_contacto,$celular_contacto,$idusuario,
                                  $modoEnvio,$fechaventa,$idcliente,$filial,$fechavalida){

    $nombre_rzsocial = mb_strtoupper($nombre_rzsocial, 'UTF-8');
    $nombre_contacto = mb_strtoupper($nombre_contacto, 'UTF-8');

    $cabecera        = getSerieNumeroPedidoPavieria($idusuario);

    $serie           = $cabecera['serie'];
    $numero          = $cabecera['numero'];

    if(!is_numeric($serie) || !is_numeric($numero)){
        $_SESSION['merror']= "Error al generar serie y numero\n";
        return false;
    }

    $link = conectarBD();

    //calculo descuento, total, subtotal e igv del pedido
    $pedido = calcularPedido($detallepedido,$formadepago,$fechaventa,$nro_documento);

    if($pedido === false){
        $_SESSION['merror']= "Error al Calcular Pedido\n".$_SESSION['merrorPrecio'];
        return false;
    }

    if ( sqlsrv_begin_transaction( $link ) === false ) {
		return false;
	}

    //registro o actualizo datos de cliente paviferia
    $idcliente = registrarClientePaviferia($tipo_documento,$nro_documento,$nombre_rzsocial,$direccion,
                                          $nombre_contacto,$email_contacto,$telefono_contacto,
                                          $celular_contacto,$idusuario,$idcliente,$filial,$link);
    if($idcliente === false){
        $_SESSION['merror']= "Error al registrar Cliente\n".json_encode( sqlsrv_errors() );
        sqlsrv_rollback( $link );
        return false;
    }

    //registro o actualizo la cabecera del pedido
    if($modoEnvio == 0){//registra cabecera

        $idpedido = registrarCabeceraPedidoPaviferia($idcliente,$serie,$numero,$pedido['descuento'],$pedido['subtotal'],
                             $pedido['igv'],$pedido['total'],$fecha_respuesta,$formadepago,$idusuario,$fechaventa,$fechavalida,$link);
    }else{
        //actualizo cabecera
        $idpedido = updateCabeceraPedidoPaviferia($modoEnvio,$pedido['descuento'],$pedido['subtotal'],
                              $pedido['igv'],$pedido['total'],$fecha_respuesta,$formadepago,$idusuario,$fechaventa,$fechavalida,$link);

    }


    if($idpedido === false){
        $_SESSION['merror']= "Error al registrar Cabecera de Pedido\n";
        sqlsrv_rollback( $link );
        return false;
    }

    //registro el detalle del pedido
    $detallepedido = calcularDetallePedido($detallepedido,$formadepago,$fechaventa,$nro_documento);

    if($modoEnvio != 0){//eliminar detalle antiguo
        $exito = deleteDetallePedido($idpedido,$link);

        if($exito === false){
            $_SESSION['merror']= "Error al actualizar Detalle Pedido\n";
            sqlsrv_rollback( $link );
            return false;
        }
    }

    $exito = registrarDetallePedido($idpedido,$detallepedido,$idusuario,$link);

    if($exito === false){
        $_SESSION['merror']= "Error al registrar Detalle Pedido\n";
        sqlsrv_rollback( $link );
        return false;
    }else{
        sqlsrv_commit( $link );
        return $exito;
    }

}

function getSerieNumeroPedidoPavieria($idusuario){

    $sql = "select idzona as serie, numero
            from paviferia_vendedor vend
            left join paviferia_pedido ped on vend.idzona = ped.serie
            where vend.id = $idusuario
            order by numero desc";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if(!empty($data['serie'])){

        $serie   = $data['serie'];

        if((!empty($data['numero']) && $data['numero'] != null) || $data['numero'] === 0){
            $numero   = intval($data['numero']) +1;
        }else{
            $numero = 1;
        }

        return array("serie"=>$serie,"numero"=>$numero);

    }else{
        return false;
    }

}

function calcularPedido($detallepedido,$modopago,$fechaceventa,$nro_documento){

    $ped = calcularDetallePedido($detallepedido,$modopago,$fechaceventa,$nro_documento);

    if($ped === false)
        return false;

    $descuento = 0;
    $igv       = 0;
    $total     = 0;
    $subtotal  = 0;

    foreach($ped as $p){

        //$descuento = $p['descuento'];
        $total     = $total + $p['importe'];
        $igv       = $igv   + $p['igv'];
        $subtotal  = $subtotal + $p['subtotal'];
    }

    return array("descuento"=>$descuento,"subtotal"=>$subtotal,"igv"=>$igv,"total"=>$total);

}

function calcularDetallePedido($detallepedido,$modopago,$fechaceventa,$nro_documento){

    if(empty($detallepedido))
        return false;

    $res = array();

    foreach ($detallepedido as $dp) {

            $cantidadGrupo = calculateCantidadGrupoByProducto($detallepedido,$dp['idproducto']);

            $precio_base = getPrecioBaseProductosPaviferia($dp['idproducto'],$fechaceventa);
            $idprecio    = getIdPrecioProductosPaviferia($dp['idproducto'],$fechaceventa);
            $kgs         = getKgEquivalenteProductosPaviferia($dp['idproducto'],$dp['cantidad']);
            $dscto       = getDescuentoPaviferia($cantidadGrupo,$dp['idproducto'],$modopago,$nro_documento);
            $peso_base   = getPesoBaseProductosPaviferia($dp['idproducto']);

            $importe     = calcularPrecio($dp['idproducto'],$modopago,$dp['cantidad'],$cantidadGrupo,$fechaceventa,$nro_documento);
            $precioConDescuentos = getPrecioConDescuento($dp['idproducto'],$cantidadGrupo,$modopago,$fechaceventa,$nro_documento);
            $subtotal    = round($precioConDescuentos * $kgs,2,PHP_ROUND_HALF_UP);
            $igv         = round($subtotal * getIGV(),2,PHP_ROUND_HALF_UP);

            if($precio_base === false || $kgs === false || $dscto === false || $importe === false ||
               $precioConDescuentos === false || $igv===false || $cantidadGrupo === false || $idprecio === false){

                if($precio_base === false){
                    $_SESSION['merrorPrecio'] = "El producto ".getProductosPaviferiaName($dp['idproducto'])." no mantiene precios para la fecha de venta ".$fechaceventa;
                }

                return false;
            }


            $res[] = array( "nro_item"     => $dp['nro_item'],
                            "idproducto"   => $dp['idproducto'],
                            "productodesc" => getProductosPaviferiaName($dp['idproducto']),
                            "idprecio"     => $idprecio,
                            "idgrupo"      => getGrupoProducto($dp['idproducto']),
                            "unidades"     => $dp['cantidad'],
                            "peso_base"    => $peso_base,
                            "unidadmedida" => getUnidadMedicaById($dp['idproducto']),
                            "kgs"          => $kgs,
                            "descuento"    => $dscto,
                            "precio"       => $precio_base,
                            "precio_dscto" => $precioConDescuentos,
                            "subtotal"     => $subtotal,
                            "igv"          => $igv,
                            "importe"      => $importe);
    }

    return $res;

}

function registrarClientePaviferia($tipo_documento,$nro_documento,$nombre_rzsocial,$direccion,
                                   $nombre_contacto,$email_contacto,$telefono_contacto,
                                   $celular_contacto,$idusuario,$idcliente,$filial,&$link){


    if($idcliente == 0){

        $sql = "insert into paviferia_cliente
                   (tipodocumento,nrodocumento,nombre_rzsocial,direccion,nombrecontacto,correocontacto,filial,
                    telefonofijo,celularcontacto,usuarioregistra,fecharegistra)
                values
                  ($tipo_documento,'$nro_documento','$nombre_rzsocial','$direccion','$nombre_contacto','$email_contacto','$filial',
                   '$telefono_contacto','$celular_contacto',$idusuario,GETDATE())";
    }else{
        $sql = "update paviferia_cliente set
                       tipodocumento  = $tipo_documento,
                       nrodocumento   = '$nro_documento',
                       nombre_rzsocial= '$nombre_rzsocial',
                       direccion      = '$direccion',
                       filial         = '$filial',
                       nombrecontacto = '$nombre_contacto',
                       correocontacto = '$email_contacto',
                       telefonofijo   = '$telefono_contacto',
                       celularcontacto= '$celular_contacto',
                       usuariomodifica= $idusuario,
                       fechamodifica  = GETDATE()
                       where id = $idcliente";
    }

    $res = queryBD($sql,$link,true);

    if($res === false){
        return false;
    }else{
            if($idcliente == 0)
                return getLastInsertId($link);
            else
                return $idcliente;
    }

}

function exitsClientePaviferia($tipo_documento,$nro_documento,&$link){

    $sql = "select id from paviferia_cliente
            where tipodocumento = $tipo_documento and nrodocumento = '$nro_documento'";

    $res = queryBD($sql,$link,true);

    if($res === false) {
        return false;
    }else{
        if(empty($res['id']))
            return -1;
        else
            return $res['id'];
    }
}

function getLastInsertId($link){

    $sql = "SELECT SCOPE_IDENTITY() AS ins_id";

    $res = queryBD($sql,$link,true);

    if($res === false) {
        return false;
    }else{
        if(empty($res['ins_id']))
            return false;
        else
            return $res['ins_id'];
    }

}

function registrarCabeceraPedidoPaviferia($idcliente,$serie,$numero,$descuento,$subtotal,
                                          $igv,$total,$fecha_respuesta,$formadepago,$idusuario,$fechaventa,$fechavalida,&$link){

    $sql = "insert into paviferia_pedido(idcliente,serie,numero,descuento,subtotal,igv,total,
                   fecharespuesta,estado,formadepago,usuarioregistra,fecharegistra,fechaventa,fechavalida)
            values($idcliente,$serie,$numero,0,$subtotal,$igv,$total,
                   '$fecha_respuesta',1,$formadepago,$idusuario,GETDATE(),'$fechaventa','$fechavalida')";

    $res = queryBD($sql,$link,true);

    if($res === false){
        return false;
    }else{
        return getLastInsertId($link);
    }

}

function updateCabeceraPedidoPaviferia($idpedido,$descuento,$subtotal,
                                       $igv,$total,$fecha_respuesta,$formadepago,$idusuario,$fechaventa,$fechavalida,$link){

    $sql = "update paviferia_pedido set
            descuento       = $descuento,
            subtotal        = $subtotal,
            igv             = $igv,
            total           = $total,
            fecharespuesta  = '$fecha_respuesta',
            formadepago     = $formadepago,
            usuariomodifica = $idusuario,
            fechamodifica   = GETDATE(),
            fechaventa      = '$fechaventa',
            fechavalida     = '$fechavalida'
            where id = $idpedido";

    $res = queryBD($sql,$link,true);

    if($res === false) {
        return false;
    }else{
        return $idpedido;
    }

}

function deleteDetallePedido($idpedido,$link){

    $sql = "delete from paviferia_pedidodetalle where idpedido = $idpedido";

    $res = queryBD($sql,$link,true);

    if($res === false) {
        return false;
    }else{
        return true;
    }
}

function registrarDetallePedido($idpedido,$detallepedido,$idusuario,&$link){

    if(empty($detallepedido))
        return false;

    $sql = "insert into paviferia_pedidodetalle(
                nroitem,idpedido,idproducto,
                unidades,kilogramos,descuento,
                precio,igv,total,peso_base,subtotal,precio_dscto,idprecio,
                usuarioregistra,fecharegistra) values";

    foreach($detallepedido as $dp){

        $nro_item       = $dp['nro_item'];
        $idproducto     = $dp['idproducto'];
        $unidades       = $dp['unidades'];
        $kgs            = $dp['kgs'];
        $descuento      = $dp['descuento'];
        $precio         = $dp['precio'];
        $precio_dscto   = $dp['precio_dscto'];
        $igv            = $dp['igv'];
        $peso_base      = $dp['peso_base'];
        $subtotal       = $dp['subtotal'];
        $total          = $dp['importe'];
        $idprecio       = $dp['idprecio'];

        $sql = $sql."($nro_item,$idpedido,$idproducto,$unidades,$kgs,$descuento,$precio,$igv,$total,$peso_base,$subtotal,$precio_dscto,$idprecio,$idusuario,GETDATE()),";
    }

    $res = queryBD(substr($sql, 0, -1),$link);

    if($res === false)
        return false;
    else
        return $idpedido;

}
