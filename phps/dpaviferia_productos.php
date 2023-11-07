<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 19/08/2015
 * Time: 11:38 PM
 */

include_once("conexion.php");

global $IGV;
$IGV = 0.18;

function hasPrecioProductoInFecha($idproducto,$fechapedido){

    $sql = "select count(*) as cantidad from paviferia_precio
            where idproducto = $idproducto and
                  '$fechapedido' between fechainicio and fechafin";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if(empty($data['cantidad']) || $data['cantidad'] == 0 || $data === false){
        return false;
    }else{
        return true;
    }

}

function getPreciosProductosPaviferiaById($idProducto){

    $sql = "select * from paviferia_precio where idproducto = $idProducto";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}


/**
 * @param $name
 * @return array|bool
 */
function getAllClientesPaviferiaLikeNroDocumento($name){

    $sql = "select DISTINCT tipodocumento,nrodocumento,nombre_rzsocial,CONVERT( VARCHAR(MAX),direccion) as direccion,nombrecontacto,correocontacto,telefonofijo,celularcontacto, filial
            from paviferia_cliente
            where nrodocumento like '$name%'
            order by nrodocumento";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

/**
 * @return array|bool
 */
function getAllProductosPaviferia(){

    $sql = "select prod_pav.*,
             (SELECT CASE prod_pav.unidad_medida WHEN '1' THEN 'UNIDAD' ELSE
             (SELECT CASE prod_pav.unidad_medida WHEN '2' THEN 'KG' ELSE
             (SELECT CASE prod_pav.unidad_medida WHEN '3' THEN 'PAQUETE' ELSE '' END) END) END) as unidadmedida_desc,
             grupo.descripcion as grupodesc,
             (SELECT COUNT(*) FROM paviferia_precio where idproducto = prod_pav.id) as cantPrecios
            from paviferia_producto prod_pav
            inner join paviferia_grupo grupo on grupo.id = prod_pav.idgrupo";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

/**
 * @param $name
 * @return array|bool
 */
function getAllProductosPaviferiaLikeName($name,$fechaVenta){

    $sql = "select paviferia_producto.*
            from paviferia_producto
            inner join paviferia_precio pp on pp.idproducto = paviferia_producto.id
            where paviferia_producto.descripcion like '$name%' and '$fechaVenta' BETWEEN fechainicio and fechafin
            order by paviferia_producto.descripcion";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}


function getAllProductosPaviferiaLikeId($id,$fechaVenta){

    $sql = "select paviferia_producto.*
            from paviferia_producto
            inner join paviferia_precio pp on pp.idproducto = paviferia_producto.id
            where paviferia_producto.id like '$id%' and '$fechaVenta' BETWEEN fechainicio and fechafin
            order by paviferia_producto.descripcion";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getProductoPaviferiaById($id){

    $sql = "select *
            from paviferia_producto
            where id = $id
            order by descripcion";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    return $data;
}

function getProductosPaviferiaById($idproducto){

    $sql = "select *
            from paviferia_producto
            where id like $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    return $data;
}

function getProductosPaviferiaName($idproducto){

    $sql = "select descripcion
            from paviferia_producto
            where id = $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data['descripcion']))
            return '';
        else
            return $data['descripcion'];
    }
}

/**
 * @param $id
 * @param $descripcion
 * @param $unidad_medida
 * @param $precio_base
 * @param $peso_unidad
 * @param $idusuario
 * @return bool
 */
function registrarProductoPaviferia($id,$descripcion,$unidad_medida,$peso_unidad,$idgrupo,$idusuario){

    //$descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into paviferia_producto(id,descripcion,unidad_medida,peso_unidad,idgrupo,usuarioregistra,fecharegistra)
            values ($id,'$descripcion',$unidad_medida,$peso_unidad,$idgrupo,$idusuario,GETDATE())";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function registrarPrecioProductoPaviferia($idProducto,$descripcion,$precioBase,$fechaIni,$fechaFin,$idusuario){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into paviferia_precio(idproducto,descripcion,precio_base,fechainicio,fechafin,usuarioregistra,fecharegistra)
            values ($idProducto,'$descripcion',$precioBase,'$fechaIni', '$fechaFin',$idusuario,GETDATE())";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;

}

/**
 * @return int
 */
function getNewIdProductoPaviferia(){

    $id = 0;

    $sql = "select MAX(id) as id from paviferia_producto ";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false){
        $id = null;
    }else{
        $id = intval($data['id']) + 1;
    }

    return $id;
}

/**
 * @param $id
 * @return bool
 */
function eliminarProductoPaviferia($id){

    $sql = "delete from paviferia_producto where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function eliminarPrecioProductoPaviferia($id){

    $sql = "delete from paviferia_precio where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

/**
 * @param $id
 * @param $descripcion
 * @param $precio_base
 * @param $peso_unidad
 * @param $idusuario
 * @return bool
 */
function updateProductoPaviferia($id,$descripcion,$peso_unidad,$idusuario){

    //$descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update paviferia_producto
            set descripcion='$descripcion',
                peso_unidad = $peso_unidad,
                usuariomodifica = $idusuario,
                fechamodifica = GETDATE()
            where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function getIGV(){

    global $IGV;

    return $IGV;
}

function calculateCantidadGrupoByProducto($detallepedido,$idproducto){

    $res = 0;

    $grupobase = getGrupoProducto($idproducto);

    if($grupobase === false)
        return false;

    foreach ($detallepedido as $detail) {

        $aux = getGrupoProducto($detail['idproducto']);

        if($aux === false)
            return false;

        if($aux === $grupobase){
            $res = $res + $detail['cantidad'];
        }
    }


    return $res;
}

function getKgEquivalenteProductosPaviferia($idproducto,$cantidad=1){

    $sql = "select peso_unidad
            from paviferia_producto
            where id = $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else {
        if(empty($data))
            return 0;
        else
            return $cantidad * $data['peso_unidad'];
    }
}

function getDescuentoPorVolumenProductosPaviferia($cantidadGrupo,$idproducto){

    $sql = "select descuento
            from paviferia_descuento
            inner join paviferia_producto on paviferia_producto.idgrupo = paviferia_descuento.idgrupo
            where tipo=2 and xvolumen_min <= $cantidadGrupo and $cantidadGrupo <= xvolumen_max and paviferia_producto.id = $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data))
            return 0;
        else
            return $data['descuento'];
    }
}

function getDescuentoPorModoPagoProductosPaviferia($modopago){

    $sql = "select descuento
            from paviferia_descuento
            where tipo=1 and idformapago = $modopago";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return $sql;
    }else{
        if(empty($data))
            return 0;
        else
            return $data['descuento'];
    }
}

function getUnidadMedicaById($idproducto){

    $sql = "select
             (SELECT CASE unidad_medida WHEN '1' THEN 'UN' ELSE
             (SELECT CASE unidad_medida WHEN '2' THEN 'KG' ELSE
             (SELECT CASE unidad_medida WHEN '3' THEN 'PAQUETE' ELSE '' END) END) END) as unidadmedida_desc
            from paviferia_producto
            where id = $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data))
            return false;
        else
            return $data['unidadmedida_desc'];
    }

}

function getGrupoProducto($idproducto){

    $sql = "select idgrupo
            from paviferia_producto
            where id = $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data))
            return false;
        else
            return $data['idgrupo'];
    }
}

function getIdPrecioProductosPaviferia($idproducto,$fechaceventa){

    $sql = "select id
            from paviferia_precio
            where idproducto = $idproducto
                  and '$fechaceventa' between fechainicio and fechafin";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data))
            return false;
        else
            return $data['id'];
    }
}

function getPrecioBaseProductosPaviferia($idproducto,$fechaceventa){

    $sql = "select precio_base
            from paviferia_precio
            where idproducto = $idproducto
                  and '$fechaceventa' between fechainicio and fechafin";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data))
            return false;
        else
            return $data['precio_base'];
    }
}

function getPesoBaseProductosPaviferia($idproducto){

    $sql = "select peso_unidad
            from paviferia_producto
            where id = $idproducto";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data))
            return false;
        else
            return $data['peso_unidad'];
    }
}

function getDescuentoByNumeroDocumento($nro_documento){

    $sql = "select descuento
            from paviferia_grupocliente
            inner join paviferia_clientesdescuentos on paviferia_clientesdescuentos.idgrupo = paviferia_grupocliente.id
            where paviferia_clientesdescuentos.nrodocumento = '$nro_documento'";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false) {
        return false;
    }else{
        if(empty($data)){
            return false;
        }else{
			$descuento = $data['descuento'];
			if(empty($descuento) || $descuento==0)
				return false;
			else
				return $descuento;
		}
    }

}

function calcularPrecioDsctoProductoPaviferia($precioBase,$totalDesct){
    return round($precioBase * ( 1 - $totalDesct/100),2,PHP_ROUND_HALF_UP);
}

function getDescuentoPaviferia($cantidadGrupo,$idproducto,$modopago,$nro_documento){

    $dsctoByNroDocumento = getDescuentoByNumeroDocumento($nro_documento);

    if($dsctoByNroDocumento === false){

        $dsctVolumen  = getDescuentoPorVolumenProductosPaviferia($cantidadGrupo,$idproducto);
        $dsctModoPago = getDescuentoPorModoPagoProductosPaviferia($modopago);

        if(is_numeric($dsctModoPago) && is_numeric($dsctVolumen)){
            return $dsctVolumen + $dsctModoPago;
        }else{
            return false;
        }
    }else{
        return $dsctoByNroDocumento;
    }



}

function getPrecioConDescuento($idproducto,$cantidadGrupo,$modopago,$fechaventa,$nro_documento){

    $dscto        = getDescuentoPaviferia($cantidadGrupo,$idproducto,$modopago,$nro_documento);
    $precioBase   = getPrecioBaseProductosPaviferia($idproducto,$fechaventa);

    if(is_numeric($dscto)  && is_numeric($precioBase)){

        $totalDesct   = $dscto;

        $precioConDescuento = calcularPrecioDsctoProductoPaviferia($precioBase,$totalDesct);

        return $precioConDescuento;

    }else{
        return false;
    }



}

function calcularPrecio($idproducto, $modoPago, $cantidad, $cantidadGrupo,$fechaceventa,$nro_documento){

    global $IGV;

    $precioConDescuentos = getPrecioConDescuento($idproducto,$cantidadGrupo,$modoPago,$fechaceventa,$nro_documento);
    $kgs = getKgEquivalenteProductosPaviferia($idproducto,$cantidad);

    if($precioConDescuentos === false || $kgs === false || !is_numeric($precioConDescuentos) || !is_numeric($kgs)){
        return false;
    }else{

        $subtotal = round($precioConDescuentos * $kgs,2,PHP_ROUND_HALF_UP);
        $igv      = round($subtotal * $IGV,2,PHP_ROUND_HALF_UP);

        $parcial  = $subtotal + $igv;

        return $parcial;
    }
}