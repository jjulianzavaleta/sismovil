USE db_sismovil;

declare @counterOrigin_contract_credito as INT
declare @counterTemp_contract_credito as INT
declare @counterTarget_contract_credito as INT

set @counterOrigin_contract_credito = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_credito'));

if(OBJECT_ID('tmp_contract_credito') IS NOT NULL)
    DROP TABLE tmp_contract_credito;

DELETE FROM contract_credito;

SELECT *
INTO tmp_contract_credito
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_credito');


set @counterTemp_contract_credito = (SELECT COUNT(*) FROM tmp_contract_credito);

if(  @counterOrigin_contract_credito = @counterTemp_contract_credito )
    print('contract_credito table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_credito table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_credito ON;

INSERT INTO contract_credito(id, descripcion)
SELECT id, descripcion FROM tmp_contract_credito;

SET IDENTITY_INSERT dbo.contract_credito OFF;

set @counterTarget_contract_credito = (SELECT COUNT(*) FROM contract_credito);

if(  @counterOrigin_contract_credito = @counterTarget_contract_credito )
    print('contract_credito table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_credito table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_credito;