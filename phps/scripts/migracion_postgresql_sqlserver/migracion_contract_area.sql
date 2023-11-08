USE db_sismovil;

declare @counterOrigin_contract_area as INT
declare @counterTemp_contract_area as INT
declare @counterTarget_contract_area as INT

set @counterOrigin_contract_area = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_area'));

if(OBJECT_ID('tmp_contract_area') IS NOT NULL)
    DROP TABLE tmp_contract_area;

DELETE FROM contract_area;

SELECT *
INTO tmp_contract_area
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion, codigo from sismovil.public.contract_area');


set @counterTemp_contract_area = (SELECT COUNT(*) FROM tmp_contract_area);

if(  @counterOrigin_contract_area = @counterTemp_contract_area )
    print('contract_area table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_area table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_area ON;

INSERT INTO contract_area(id, descripcion, codigo)
SELECT id, descripcion, codigo FROM tmp_contract_area;

SET IDENTITY_INSERT dbo.contract_area OFF;

set @counterTarget_contract_area = (SELECT COUNT(*) FROM contract_area);

if(  @counterOrigin_contract_area = @counterTarget_contract_area )
    print('contract_area table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_area table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_area;