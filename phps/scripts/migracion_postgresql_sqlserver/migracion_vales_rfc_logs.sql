USE db_sismovil;

declare @counterOrigin_rfc_logs as INT
declare @counterTemp_rfc_logs as INT
declare @counterTarget_rfc_logs as INT

set @counterOrigin_rfc_logs = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_rfc_logs'));

if(OBJECT_ID('tmp_rfc_logs') IS NOT NULL)
    DROP TABLE tmp_rfc_logs;

DELETE FROM vales_rfc_logs;

SELECT *
INTO tmp_rfc_logs
FROM OPENQUERY(PGRESS, 'SELECT id, idvale, rfc, fecha, request, response, success, byjob from sismovil.public.vales_rfc_logs');


set @counterTemp_rfc_logs = (SELECT COUNT(*) FROM tmp_rfc_logs);

if(  @counterOrigin_rfc_logs = @counterTemp_rfc_logs )
    print('vales_rfc_logs table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_rfc_logs table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_rfc_logs ON;

INSERT INTO vales_rfc_logs(id, idvale, rfc, fecha, request, response, success, byjob)
SELECT id, idvale, rfc, fecha, request, response, success, byjob FROM tmp_rfc_logs;

SET IDENTITY_INSERT dbo.vales_rfc_logs OFF;

set @counterTarget_rfc_logs = (SELECT COUNT(*) FROM vales_rfc_logs);

if(  @counterOrigin_rfc_logs = @counterTarget_rfc_logs )
    print('vales_rfc_logs table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_rfc_logs table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_rfc_logs;