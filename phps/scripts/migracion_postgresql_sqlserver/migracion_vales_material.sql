USE db_sismovil;

declare @counterOrigin_material as INT
declare @counterTemp_material as INT
declare @counterTarget_material as INT

set @counterOrigin_material = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_material'));

if(OBJECT_ID('tmp_material') IS NOT NULL)
    DROP TABLE tmp_material;

DELETE FROM vales_material;

SELECT *
INTO tmp_material
FROM OPENQUERY(PGRESS, 'SELECT id, cod_sap, nombre, rfcname from sismovil.public.vales_material');


set @counterTemp_material = (SELECT COUNT(*) FROM tmp_material);

if(  @counterOrigin_material = @counterTemp_material )
    print('vales_material table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_material table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_material ON;

INSERT INTO vales_material(id, cod_sap, nombre, rfcname)
SELECT id, cod_sap, nombre, rfcname FROM tmp_material;

SET IDENTITY_INSERT dbo.vales_material OFF;

set @counterTarget_material = (SELECT COUNT(*) FROM vales_material);

if(  @counterOrigin_material = @counterTarget_material )
    print('vales_material table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_material table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_material;