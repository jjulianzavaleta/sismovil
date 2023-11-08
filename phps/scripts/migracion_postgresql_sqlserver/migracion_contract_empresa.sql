USE db_sismovil;

declare @counterOrigin_contract_empresa as INT
declare @counterTemp_contract_empresa as INT
declare @counterTarget_contract_empresa as INT

set @counterOrigin_contract_empresa = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_empresa'));

if(OBJECT_ID('tmp_contract_empresa') IS NOT NULL)
    DROP TABLE tmp_contract_empresa;

DELETE FROM contract_empresa;

SELECT *
INTO tmp_contract_empresa
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_empresa');


set @counterTemp_contract_empresa = (SELECT COUNT(*) FROM tmp_contract_empresa);

if(  @counterOrigin_contract_empresa = @counterTemp_contract_empresa )
    print('contract_empresa table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_empresa table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_empresa ON;

INSERT INTO contract_empresa(id, descripcion)
SELECT id, descripcion FROM tmp_contract_empresa;

SET IDENTITY_INSERT dbo.contract_empresa OFF;

set @counterTarget_contract_empresa = (SELECT COUNT(*) FROM contract_empresa);

if(  @counterOrigin_contract_empresa = @counterTarget_contract_empresa )
    print('contract_empresa table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_empresa table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_empresa;