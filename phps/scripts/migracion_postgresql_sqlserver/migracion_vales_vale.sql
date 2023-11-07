USE db_sismovil;

declare @counterOrigin_vale as INT
declare @counterTemp_vale as INT
declare @counterTarget_vale as INT

set @counterOrigin_vale = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_vale'));

if(OBJECT_ID('tmp_vale') IS NOT NULL)
    DROP TABLE tmp_vale;

DELETE FROM vales_vale;

SELECT *
INTO tmp_vale
FROM OPENQUERY(PGRESS, 'SELECT id,  equnr::INTEGER, placa, grifo, fecha_registro, fecha_max_consumo, kilom,    estado_pm,  estado, anulado, usuario_registra, usuario_modifica, fecha_modifica, fecha_registra, usuario_emite, fecha_emite, usuario_anula, fecha_anula, detalle2_modo, chofer, chofer_aux, consumo_idusuario, consumo_fechaconsumo, consumo_gps_longitude, consumo_gps_latitude, consumo_observacion, consumo_unidadmedida, istermoking, tsomobile_kilometraje, tsomobile_somethingwentwrong,  tsomobile_byjob, tsomobile_fechaconsulta, tsomobile_response, tsomobile_endpoint, isflujoconsumidor, rfcconsumo_somethingwentwrong, hascarreta from sismovil.public.vales_vale');


set @counterTemp_vale = (SELECT COUNT(*) FROM tmp_vale);

if(  @counterOrigin_vale = @counterTemp_vale )
    print('vales_vale table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_vale table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_vale ON;

INSERT INTO vales_vale(id,  equnr, placa, grifo, fecha_registro, fecha_max_consumo, kilom,    estado_pm,  estado, anulado, usuario_registra, usuario_modifica, fecha_modifica, fecha_registra, usuario_emite, fecha_emite, usuario_anula, fecha_anula, detalle2_modo, chofer, chofer_aux, consumo_idusuario, consumo_fechaconsumo, consumo_gps_longitude, consumo_gps_latitude, consumo_observacion, consumo_unidadmedida, istermoking, tsomobile_kilometraje, tsomobile_somethingwentwrong,  tsomobile_byjob, tsomobile_fechaconsulta, tsomobile_response, tsomobile_endpoint, isflujoconsumidor, rfcconsumo_somethingwentwrong, hascarreta)
SELECT id,  equnr, placa, grifo, fecha_registro, fecha_max_consumo, kilom,    estado_pm,  estado, anulado, usuario_registra, usuario_modifica, fecha_modifica, fecha_registra, usuario_emite, fecha_emite, usuario_anula, fecha_anula, detalle2_modo, chofer, chofer_aux, consumo_idusuario, consumo_fechaconsumo, consumo_gps_longitude, consumo_gps_latitude, consumo_observacion, consumo_unidadmedida, istermoking, tsomobile_kilometraje, tsomobile_somethingwentwrong,  tsomobile_byjob, tsomobile_fechaconsulta, tsomobile_response, tsomobile_endpoint, isflujoconsumidor, rfcconsumo_somethingwentwrong, hascarreta FROM tmp_vale;

SET IDENTITY_INSERT dbo.vales_vale OFF;

set @counterTarget_vale = (SELECT COUNT(*) FROM vales_vale);

if(  @counterOrigin_vale = @counterTarget_vale )
    print('vales_vale table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_vale table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_vale;