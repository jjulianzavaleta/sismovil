USE db_sismovil;

declare @counterOrigin_img_extras as INT
declare @counterTemp_img_extras as INT
declare @counterTarget_img_extras as INT

set @counterOrigin_img_extras = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_detalle_img_extras'));

if(OBJECT_ID('tmp_img_extras') IS NOT NULL)
    DROP TABLE tmp_img_extras;

DELETE FROM vales_detalle_img_extras;

SELECT *
INTO tmp_img_extras
FROM OPENQUERY(PGRESS, 'SELECT id, idvale, matnr, voucher_img from sismovil.public.vales_detalle_img_extras');


set @counterTemp_img_extras = (SELECT COUNT(*) FROM tmp_img_extras);

if(  @counterOrigin_img_extras = @counterTemp_img_extras )
    print('vales_detalle_img_extras table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_detalle_img_extras table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_detalle_img_extras ON;

INSERT INTO vales_detalle_img_extras(id, idvale, matnr, voucher_img)
SELECT id, idvale, matnr, voucher_img FROM tmp_img_extras;

SET IDENTITY_INSERT dbo.vales_detalle_img_extras OFF;

set @counterTarget_img_extras = (SELECT COUNT(*) FROM vales_detalle_img_extras);

if(  @counterOrigin_img_extras = @counterTarget_img_extras )
    print('vales_detalle_img_extras table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_detalle_img_extras table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_img_extras;