USE db_sismovil;

declare @counterOrigin_contract_config_correlativos as INT
declare @counterTemp_contract_config_correlativos as INT
declare @counterTarget_contract_config_correlativos as INT

set @counterOrigin_contract_config_correlativos = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_config_correlativos'));

if(OBJECT_ID('tmp_contract_config_correlativos') IS NOT NULL)
    DROP TABLE tmp_contract_config_correlativos;

DELETE FROM contract_config_correlativos;

SELECT *
INTO tmp_contract_config_correlativos
FROM OPENQUERY(PGRESS, 'SELECT id, year, idarea, correlativo from sismovil.public.contract_config_correlativos');


set @counterTemp_contract_config_correlativos = (SELECT COUNT(*) FROM tmp_contract_config_correlativos);

if(  @counterOrigin_contract_config_correlativos = @counterTemp_contract_config_correlativos )
    print('contract_config_correlativos table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_config_correlativos table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_config_correlativos ON;

INSERT INTO contract_config_correlativos(id, year, idarea, correlativo)
SELECT id, year, idarea, correlativo FROM tmp_contract_config_correlativos;

SET IDENTITY_INSERT dbo.contract_config_correlativos OFF;

set @counterTarget_contract_config_correlativos = (SELECT COUNT(*) FROM contract_config_correlativos);

if(  @counterOrigin_contract_config_correlativos = @counterTarget_contract_config_correlativos )
    print('contract_config_correlativos table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_config_correlativos table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_config_correlativos;