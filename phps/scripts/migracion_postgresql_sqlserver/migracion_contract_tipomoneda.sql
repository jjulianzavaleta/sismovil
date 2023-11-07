USE db_sismovil;

declare @counterOrigin_contract_tipomoneda as INT
declare @counterTemp_contract_tipomoneda as INT
declare @counterTarget_contract_tipomoneda as INT

set @counterOrigin_contract_tipomoneda = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_tipomoneda'));

if(OBJECT_ID('tmp_contract_tipomoneda') IS NOT NULL)
    DROP TABLE tmp_contract_tipomoneda;

DELETE FROM contract_tipomoneda;

SELECT *
INTO tmp_contract_tipomoneda
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_tipomoneda');


set @counterTemp_contract_tipomoneda = (SELECT COUNT(*) FROM tmp_contract_tipomoneda);

if(  @counterOrigin_contract_tipomoneda = @counterTemp_contract_tipomoneda )
    print('contract_tipomoneda table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_tipomoneda table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_tipomoneda ON;

INSERT INTO contract_tipomoneda(id, descripcion)
SELECT id, descripcion FROM tmp_contract_tipomoneda;

SET IDENTITY_INSERT dbo.contract_tipomoneda OFF;

set @counterTarget_contract_tipomoneda = (SELECT COUNT(*) FROM contract_tipomoneda);

if(  @counterOrigin_contract_tipomoneda = @counterTarget_contract_tipomoneda )
    print('contract_tipomoneda table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_tipomoneda table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_tipomoneda;