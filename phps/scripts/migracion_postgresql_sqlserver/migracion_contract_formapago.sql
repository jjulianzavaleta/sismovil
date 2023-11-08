USE db_sismovil;

declare @counterOrigin_contract_formapago as INT
declare @counterTemp_contract_formapago as INT
declare @counterTarget_contract_formapago as INT

set @counterOrigin_contract_formapago = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_formapago'));

if(OBJECT_ID('tmp_contract_formapago') IS NOT NULL)
    DROP TABLE tmp_contract_formapago;

DELETE FROM contract_formapago;

SELECT *
INTO tmp_contract_formapago
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_formapago');


set @counterTemp_contract_formapago = (SELECT COUNT(*) FROM tmp_contract_formapago);

if(  @counterOrigin_contract_formapago = @counterTemp_contract_formapago )
    print('contract_formapago table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_formapago table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_formapago ON;

INSERT INTO contract_formapago(id, descripcion)
SELECT id, descripcion FROM tmp_contract_formapago;

SET IDENTITY_INSERT dbo.contract_formapago OFF;

set @counterTarget_contract_formapago = (SELECT COUNT(*) FROM contract_formapago);

if(  @counterOrigin_contract_formapago = @counterTarget_contract_formapago )
    print('contract_formapago table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_formapago table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_formapago;