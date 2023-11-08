USE db_sismovil;

declare @counterOrigin_contract_garantia as INT
declare @counterTemp_contract_garantia as INT
declare @counterTarget_contract_garantia as INT

set @counterOrigin_contract_garantia = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_garantia'));

if(OBJECT_ID('tmp_contract_garantia') IS NOT NULL)
    DROP TABLE tmp_contract_garantia;

DELETE FROM contract_garantia;

SELECT *
INTO tmp_contract_garantia
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_garantia');


set @counterTemp_contract_garantia = (SELECT COUNT(*) FROM tmp_contract_garantia);

if(  @counterOrigin_contract_garantia = @counterTemp_contract_garantia )
    print('contract_garantia table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_garantia table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_garantia ON;

INSERT INTO contract_garantia(id, descripcion)
SELECT id, descripcion FROM tmp_contract_garantia;

SET IDENTITY_INSERT dbo.contract_garantia OFF;

set @counterTarget_contract_garantia = (SELECT COUNT(*) FROM contract_garantia);

if(  @counterOrigin_contract_garantia = @counterTarget_contract_garantia )
    print('contract_garantia table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_garantia table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_garantia;