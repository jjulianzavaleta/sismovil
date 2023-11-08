USE db_sismovil;

declare @counterOrigin_horasviaje as INT
declare @counterTemp_horasviaje as INT
declare @counterTarget_horasviaje as INT

set @counterOrigin_horasviaje = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_horasviaje'));

if(OBJECT_ID('tmp_horasviaje') IS NOT NULL)
    DROP TABLE tmp_horasviaje;

DELETE FROM vales_horasviaje;

SELECT *
INTO tmp_horasviaje
FROM OPENQUERY(PGRESS, 'SELECT placa, fecha_viaje_inicio, hora, minutos, segundos, fecha_viaje_fin, vale_id, dia from sismovil.public.vales_horasviaje');


set @counterTemp_horasviaje = (SELECT COUNT(*) FROM tmp_horasviaje);

if(  @counterOrigin_horasviaje = @counterTemp_horasviaje )
    print('vales_horasviaje table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_horasviaje table. Step 1/2 FAILED. Number of records does not matched');

INSERT INTO vales_horasviaje(placa, fecha_viaje_inicio, hora, minutos, segundos, fecha_viaje_fin, vale_id, dia)
SELECT placa, fecha_viaje_inicio, hora, minutos, segundos, fecha_viaje_fin, vale_id, dia FROM tmp_horasviaje;

set @counterTarget_horasviaje = (SELECT COUNT(*) FROM vales_horasviaje);

if(  @counterOrigin_horasviaje = @counterTarget_horasviaje )
    print('vales_horasviaje table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_horasviaje table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_horasviaje;