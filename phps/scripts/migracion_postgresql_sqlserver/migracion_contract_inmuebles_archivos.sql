USE db_sismovil;

declare @counterOrigin_contract_inmuebles_archivos as INT
declare @counterTemp_contract_inmuebles_archivos as INT
declare @counterTarget_contract_inmuebles_archivos as INT

set @counterOrigin_contract_inmuebles_archivos = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_inmuebles_archivos'));

if(OBJECT_ID('tmp_contract_inmuebles_archivos') IS NOT NULL)
    DROP TABLE tmp_contract_inmuebles_archivos;

DELETE FROM contract_inmuebles_archivos;

SELECT *
INTO tmp_contract_inmuebles_archivos
FROM OPENQUERY(PGRESS, 'SELECT id, idcontrato, tipo, url from sismovil.public.contract_inmuebles_archivos');


set @counterTemp_contract_inmuebles_archivos = (SELECT COUNT(*) FROM tmp_contract_inmuebles_archivos);

if(  @counterOrigin_contract_inmuebles_archivos = @counterTemp_contract_inmuebles_archivos )
    print('contract_inmuebles_archivos table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_inmuebles_archivos table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_inmuebles_archivos ON;

INSERT INTO contract_inmuebles_archivos(id, idcontrato, tipo, url)
SELECT id, idcontrato, tipo, url FROM tmp_contract_inmuebles_archivos;

SET IDENTITY_INSERT dbo.contract_inmuebles_archivos OFF;

set @counterTarget_contract_inmuebles_archivos = (SELECT COUNT(*) FROM contract_inmuebles_archivos);

if(  @counterOrigin_contract_inmuebles_archivos = @counterTarget_contract_inmuebles_archivos )
    print('contract_inmuebles_archivos table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_inmuebles_archivos table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_inmuebles_archivos;