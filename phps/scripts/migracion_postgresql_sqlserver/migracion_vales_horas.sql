USE db_sismovil;

declare @counterOrigin_horas as INT
declare @counterTemp_horas as INT
declare @counterTarget_horas as INT

set @counterOrigin_horas = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_horas'));

if(OBJECT_ID('tmp_horas') IS NOT NULL)
    DROP TABLE tmp_horas;

DELETE FROM vales_horas;

SELECT *
INTO tmp_horas
FROM OPENQUERY(PGRESS, 'SELECT placa, vale_id, fecha_viaje_inicio, fecha_viaje_fin, dias_transcurrido, horas_transcurrido, minutos_transcurrido, segundos_transcurrido, dias_encendido, horas_encendido, minutos_encendido, segundos_encendido, dias_inactivo, horas_inactivo, minutos_inactivo, segundos_inactivo, dias_fuera_geo, horas_fuera_geo, minutos_fuera_geo, segundos_fuera_geo, dias_conduccion, horas_conduccion, minutos_conduccion, segundos_conduccion from sismovil.public.vales_horas');


set @counterTemp_horas = (SELECT COUNT(*) FROM tmp_horas);

if(  @counterOrigin_horas = @counterTemp_horas )
    print('vales_horas table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_horas table. Step 1/2 FAILED. Number of records does not matched');

INSERT INTO vales_horas(placa, vale_id, fecha_viaje_inicio, fecha_viaje_fin, dias_transcurrido, horas_transcurrido, minutos_transcurrido, segundos_transcurrido, dias_encendido, horas_encendido, minutos_encendido, segundos_encendido, dias_inactivo, horas_inactivo, minutos_inactivo, segundos_inactivo, dias_fuera_geo, horas_fuera_geo, minutos_fuera_geo, segundos_fuera_geo, dias_conduccion, horas_conduccion, minutos_conduccion, segundos_conduccion)
SELECT placa, vale_id, fecha_viaje_inicio, fecha_viaje_fin, dias_transcurrido, horas_transcurrido, minutos_transcurrido, segundos_transcurrido, dias_encendido, horas_encendido, minutos_encendido, segundos_encendido, dias_inactivo, horas_inactivo, minutos_inactivo, segundos_inactivo, dias_fuera_geo, horas_fuera_geo, minutos_fuera_geo, segundos_fuera_geo, dias_conduccion, horas_conduccion, minutos_conduccion, segundos_conduccion FROM tmp_horas;


set @counterTarget_horas = (SELECT COUNT(*) FROM vales_horas);

if(  @counterOrigin_horas = @counterTarget_horas )
    print('vales_horas table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_horas table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_horas;