USE db_sismovil;

declare @counterOrigin_historial_kilometraje as INT
declare @counterTemp_historial_kilometraje as INT
declare @counterTarget_historial_kilometraje as INT

set @counterOrigin_historial_kilometraje = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_historial_kilometraje'));

if(OBJECT_ID('tmp_historial_kilometraje') IS NOT NULL)
    DROP TABLE tmp_historial_kilometraje;

DELETE FROM vales_historial_kilometraje;

SELECT *
INTO tmp_historial_kilometraje
FROM OPENQUERY(PGRESS, 'SELECT id, idvale, usuario, fecha, vale_valor_old, vale_valor_new, was_equipo_valor_updated, vale_obs_old, vale_obs_new from sismovil.public.vales_historial_kilometraje');


set @counterTemp_historial_kilometraje = (SELECT COUNT(*) FROM tmp_historial_kilometraje);

if(  @counterOrigin_historial_kilometraje = @counterTemp_historial_kilometraje )
    print('vales_historial_kilometraje table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_historial_kilometraje table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_historial_kilometraje ON;

INSERT INTO vales_historial_kilometraje(id, idvale, usuario, fecha, vale_valor_old, vale_valor_new, was_equipo_valor_updated, vale_obs_old, vale_obs_new)
SELECT id, idvale, usuario, fecha, vale_valor_old, vale_valor_new, was_equipo_valor_updated, vale_obs_old, vale_obs_new FROM tmp_historial_kilometraje;

SET IDENTITY_INSERT dbo.vales_historial_kilometraje OFF;

set @counterTarget_historial_kilometraje = (SELECT COUNT(*) FROM vales_historial_kilometraje);

if(  @counterOrigin_historial_kilometraje = @counterTarget_historial_kilometraje )
    print('vales_historial_kilometraje table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_historial_kilometraje table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_historial_kilometraje;