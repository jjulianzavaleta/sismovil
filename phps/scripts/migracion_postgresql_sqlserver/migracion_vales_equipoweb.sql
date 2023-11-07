USE db_sismovil;

declare @counterOrigin_equipo as INT
declare @counterTemp_equipo as INT
declare @counterTarget_equipo as INT

set @counterOrigin_equipo = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_equipoweb'));

if(OBJECT_ID('tmp_equipoweb') IS NOT NULL)
    DROP TABLE tmp_equipoweb;

DELETE FROM vales_equipoweb;

SELECT *
INTO tmp_equipoweb
FROM OPENQUERY(PGRESS, 'SELECT id, equnr, txt_hequi, kostl, license_num, sttxt, termoking, kilometraje, gps, ruta, rendimiento_estandar, re_fecha_updated, re_userid_updated, operacion, medida_contador from sismovil.public.vales_equipoweb');


set @counterTemp_equipo = (SELECT COUNT(*) FROM tmp_equipoweb);

if(  @counterOrigin_equipo = @counterTemp_equipo )
    print('vales_equipoweb table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_equipoweb table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_equipoweb ON;

INSERT INTO vales_equipoweb(id, equnr, txt_hequi, kostl, license_num, sttxt, termoking, kilometraje, gps, ruta, rendimiento_estandar, re_fecha_updated, re_userid_updated, operacion, medida_contador)
SELECT id, equnr, txt_hequi, kostl, license_num, sttxt, termoking, kilometraje, gps, ruta, rendimiento_estandar, re_fecha_updated, re_userid_updated, operacion, medida_contador FROM tmp_equipoweb;

SET IDENTITY_INSERT dbo.vales_equipoweb OFF;

set @counterTarget_equipo = (SELECT COUNT(*) FROM vales_equipoweb);

if(  @counterOrigin_equipo = @counterTarget_equipo )
    print('vales_equipoweb table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_equipoweb table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_equipoweb;