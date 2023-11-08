USE db_sismovil;

declare @counterOrigin_contract_inmuebles_partregistral as INT
declare @counterTemp_contract_inmuebles_partregistral as INT
declare @counterTarget_contract_inmuebles_partregistral as INT

set @counterOrigin_contract_inmuebles_partregistral = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_inmuebles_partregistral'));

if(OBJECT_ID('tmp_contract_inmuebles_partregistral') IS NOT NULL)
    DROP TABLE tmp_contract_inmuebles_partregistral;

DELETE FROM contract_inmuebles_partregistral;

SELECT *
INTO tmp_contract_inmuebles_partregistral
FROM OPENQUERY(PGRESS, 'SELECT id, idcontrato, url from sismovil.public.contract_inmuebles_partregistral');


set @counterTemp_contract_inmuebles_partregistral = (SELECT COUNT(*) FROM tmp_contract_inmuebles_partregistral);

if(  @counterOrigin_contract_inmuebles_partregistral = @counterTemp_contract_inmuebles_partregistral )
    print('contract_inmuebles_partregistral table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_inmuebles_partregistral table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_inmuebles_partregistral ON;

INSERT INTO contract_inmuebles_partregistral(id, idcontrato, url)
SELECT id, idcontrato, url FROM tmp_contract_inmuebles_partregistral;

SET IDENTITY_INSERT dbo.contract_inmuebles_partregistral OFF;

set @counterTarget_contract_inmuebles_partregistral = (SELECT COUNT(*) FROM contract_inmuebles_partregistral);

if(  @counterOrigin_contract_inmuebles_partregistral = @counterTarget_contract_inmuebles_partregistral )
    print('contract_inmuebles_partregistral table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_inmuebles_partregistral table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_inmuebles_partregistral;