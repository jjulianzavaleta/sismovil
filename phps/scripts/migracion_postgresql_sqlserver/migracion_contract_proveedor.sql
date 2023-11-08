USE db_sismovil;

declare @counterOrigin_contract_proveedor as INT
declare @counterTemp_contract_proveedor as INT
declare @counterTarget_contract_proveedor as INT

set @counterOrigin_contract_proveedor = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_proveedor'));

if(OBJECT_ID('tmp_contract_proveedor') IS NOT NULL)
    DROP TABLE tmp_contract_proveedor;

DELETE FROM contract_proveedor;

SELECT *
INTO tmp_contract_proveedor
FROM OPENQUERY(PGRESS, 'SELECT idproveedor, ruc, razon_social from sismovil.public.contract_proveedor');


set @counterTemp_contract_proveedor = (SELECT COUNT(*) FROM tmp_contract_proveedor);

if(  @counterOrigin_contract_proveedor = @counterTemp_contract_proveedor )
    print('contract_proveedor table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_proveedor table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_proveedor ON;

INSERT INTO contract_proveedor(idproveedor, ruc, razon_social)
SELECT idproveedor, ruc, razon_social FROM tmp_contract_proveedor;

SET IDENTITY_INSERT dbo.contract_proveedor OFF;

set @counterTarget_contract_proveedor = (SELECT COUNT(*) FROM contract_proveedor);

if(  @counterOrigin_contract_proveedor = @counterTarget_contract_proveedor )
    print('contract_proveedor table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_proveedor table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_proveedor;