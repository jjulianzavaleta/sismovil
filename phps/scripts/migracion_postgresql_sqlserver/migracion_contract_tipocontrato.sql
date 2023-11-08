USE db_sismovil;

declare @counterOrigin_contract_tipocontrato as INT
declare @counterTemp_contract_tipocontrato as INT
declare @counterTarget_contract_tipocontrato as INT

set @counterOrigin_contract_tipocontrato = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_tipocontrato'));

if(OBJECT_ID('tmp_contract_tipocontrato') IS NOT NULL)
    DROP TABLE tmp_contract_tipocontrato;

DELETE FROM contract_tipocontrato;

SELECT *
INTO tmp_contract_tipocontrato
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_tipocontrato');


set @counterTemp_contract_tipocontrato = (SELECT COUNT(*) FROM tmp_contract_tipocontrato);

if(  @counterOrigin_contract_tipocontrato = @counterTemp_contract_tipocontrato )
    print('contract_tipocontrato table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_tipocontrato table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_tipocontrato ON;

INSERT INTO contract_tipocontrato(id, descripcion)
SELECT id, descripcion FROM tmp_contract_tipocontrato;

SET IDENTITY_INSERT dbo.contract_tipocontrato OFF;

set @counterTarget_contract_tipocontrato = (SELECT COUNT(*) FROM contract_tipocontrato);

if(  @counterOrigin_contract_tipocontrato = @counterTarget_contract_tipocontrato )
    print('contract_tipocontrato table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_tipocontrato table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_tipocontrato;