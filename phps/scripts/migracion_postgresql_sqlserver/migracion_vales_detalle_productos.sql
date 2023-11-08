USE db_sismovil;

declare @counterOrigin_detalleProductos as INT
declare @counterTemp_detalleProductos as INT
declare @counterTarget_detalleProductos as INT

set @counterOrigin_detalleProductos = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_detalle_productos'));

if(OBJECT_ID('tmp_detalle_productos') IS NOT NULL)
    DROP TABLE tmp_detalle_productos;

DELETE FROM vales_detalle_productos;

SELECT *
INTO tmp_detalle_productos
FROM OPENQUERY(PGRESS, 'SELECT id, matnr, menge, idvale, menge_chofer, voucher_img, voucher_nro, fromexcel_total, fromexcel_cantidad, fromexcel_precio from sismovil.public.vales_detalle_productos');


set @counterTemp_detalleProductos = (SELECT COUNT(*) FROM tmp_detalle_productos);

if(  @counterOrigin_detalleProductos = @counterTemp_detalleProductos )
    print('vales_detalle_productos table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_detalle_productos table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_detalle_productos ON;

INSERT INTO vales_detalle_productos(id, matnr, menge, idvale, menge_chofer, voucher_img, voucher_nro, fromexcel_total, fromexcel_cantidad, fromexcel_precio)
SELECT id, matnr, menge, idvale, menge_chofer, voucher_img, voucher_nro, fromexcel_total, fromexcel_cantidad, fromexcel_precio FROM tmp_detalle_productos;

SET IDENTITY_INSERT dbo.vales_detalle_productos OFF;

set @counterTarget_detalleProductos = (SELECT COUNT(*) FROM vales_detalle_productos);

if(  @counterOrigin_detalleProductos = @counterTarget_detalleProductos )
    print('vales_detalle_productos table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_detalle_productos table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_detalle_productos;