USE db_sismovil;

declare @counterOrigin_grifo as INT
declare @counterTemp_grifo as INT
declare @counterTarget_grifo as INT

set @counterOrigin_grifo = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_grifo'));

if(OBJECT_ID('tmp_grifo') IS NOT NULL)
    DROP TABLE tmp_grifo;

DELETE FROM vales_grifo;

SELECT *
INTO tmp_grifo
FROM OPENQUERY(PGRESS, 'SELECT id, nombre, descripcion, longitud, latitud, direccion, nroestacion, flujo, descripcion2 from sismovil.public.vales_grifo');


set @counterTemp_grifo = (SELECT COUNT(*) FROM tmp_grifo);

if(  @counterOrigin_grifo = @counterTemp_grifo )
    print('vales_grifo table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_grifo table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_grifo ON;

INSERT INTO vales_grifo(id, nombre, descripcion, longitud, latitud, direccion, nroestacion, flujo, descripcion2)
SELECT id, nombre, descripcion, longitud, latitud, direccion, nroestacion, flujo, descripcion2 FROM tmp_grifo;

SET IDENTITY_INSERT dbo.vales_grifo OFF;

set @counterTarget_grifo = (SELECT COUNT(*) FROM vales_grifo);

if(  @counterOrigin_grifo = @counterTarget_grifo )
    print('vales_grifo table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_grifo table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_grifo;