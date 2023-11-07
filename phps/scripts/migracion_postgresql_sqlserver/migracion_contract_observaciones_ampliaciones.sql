USE db_sismovil;

declare @counterOrigin_contract_observaciones_ampliaciones as INT
declare @counterTemp_contract_observaciones_ampliaciones as INT
declare @counterTarget_contract_observaciones_ampliaciones as INT

set @counterOrigin_contract_observaciones_ampliaciones = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_observaciones_ampliaciones'));

if(OBJECT_ID('tmp_contract_observaciones_ampliaciones') IS NOT NULL)
    DROP TABLE tmp_contract_observaciones_ampliaciones;

DELETE FROM contract_observaciones_ampliaciones;

SELECT *
INTO tmp_contract_observaciones_ampliaciones
FROM OPENQUERY(PGRESS, 'SELECT id, idcontrato, url from sismovil.public.contract_observaciones_ampliaciones');


set @counterTemp_contract_observaciones_ampliaciones = (SELECT COUNT(*) FROM tmp_contract_observaciones_ampliaciones);

if(  @counterOrigin_contract_observaciones_ampliaciones = @counterTemp_contract_observaciones_ampliaciones )
    print('contract_observaciones_ampliaciones table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_observaciones_ampliaciones table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_observaciones_ampliaciones ON;

INSERT INTO contract_observaciones_ampliaciones(id, idcontrato, url)
SELECT id, idcontrato, url FROM tmp_contract_observaciones_ampliaciones;

SET IDENTITY_INSERT dbo.contract_observaciones_ampliaciones OFF;

set @counterTarget_contract_observaciones_ampliaciones = (SELECT COUNT(*) FROM contract_observaciones_ampliaciones);

if(  @counterOrigin_contract_observaciones_ampliaciones = @counterTarget_contract_observaciones_ampliaciones )
    print('contract_observaciones_ampliaciones table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_observaciones_ampliaciones table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_observaciones_ampliaciones;