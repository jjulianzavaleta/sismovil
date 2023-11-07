USE db_sismovil;

declare @counterOrigin_contract_modalidadpago as INT
declare @counterTemp_contract_modalidadpago as INT
declare @counterTarget_contract_modalidadpago as INT

set @counterOrigin_contract_modalidadpago = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_modalidadpago'));

if(OBJECT_ID('tmp_contract_modalidadpago') IS NOT NULL)
    DROP TABLE tmp_contract_modalidadpago;

DELETE FROM contract_modalidadpago;

SELECT *
INTO tmp_contract_modalidadpago
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_modalidadpago');


set @counterTemp_contract_modalidadpago = (SELECT COUNT(*) FROM tmp_contract_modalidadpago);

if(  @counterOrigin_contract_modalidadpago = @counterTemp_contract_modalidadpago )
    print('contract_modalidadpago table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_modalidadpago table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_modalidadpago ON;

INSERT INTO contract_modalidadpago(id, descripcion)
SELECT id, descripcion FROM tmp_contract_modalidadpago;

SET IDENTITY_INSERT dbo.contract_modalidadpago OFF;

set @counterTarget_contract_modalidadpago = (SELECT COUNT(*) FROM contract_modalidadpago);

if(  @counterOrigin_contract_modalidadpago = @counterTarget_contract_modalidadpago )
    print('contract_modalidadpago table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_modalidadpago table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_modalidadpago;