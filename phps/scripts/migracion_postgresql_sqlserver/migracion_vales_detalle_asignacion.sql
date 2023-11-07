USE db_sismovil;

declare @counterOrigin_detalle_asignacion as INT
declare @counterTemp_detalle_asignacion as INT
declare @counterTarget_detalle_asignacion as INT

set @counterOrigin_detalle_asignacion = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_detalle_asignacion'));

if(OBJECT_ID('tmp_detalle_asignacion') IS NOT NULL)
    DROP TABLE tmp_detalle_asignacion;

DELETE FROM vales_detalle_asignacion;

SELECT *
INTO tmp_detalle_asignacion
FROM OPENQUERY(PGRESS, 'SELECT id, asignacion, kostl, idvale, matnr from sismovil.public.vales_detalle_asignacion');


set @counterTemp_detalle_asignacion = (SELECT COUNT(*) FROM tmp_detalle_asignacion);

if(  @counterOrigin_detalle_asignacion = @counterTemp_detalle_asignacion )
    print('vales_detalle_asignacion table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_detalle_asignacion table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_detalle_asignacion ON;

INSERT INTO vales_detalle_asignacion(id, asignacion, kostl, idvale, matnr)
SELECT id, asignacion, kostl, idvale, matnr FROM tmp_detalle_asignacion;

SET IDENTITY_INSERT dbo.vales_detalle_asignacion OFF;

set @counterTarget_detalle_asignacion = (SELECT COUNT(*) FROM vales_detalle_asignacion);

if(  @counterOrigin_detalle_asignacion = @counterTarget_detalle_asignacion )
    print('vales_detalle_asignacion table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_detalle_asignacion table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_detalle_asignacion;