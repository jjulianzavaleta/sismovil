USE db_sismovil;

declare @counterOrigin_contract_mov_archivo as INT
declare @counterTemp_contract_mov_archivo as INT
declare @counterTarget_contract_mov_archivo as INT

set @counterOrigin_contract_mov_archivo = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_mov_archivo'));

if(OBJECT_ID('tmp_contract_mov_archivo') IS NOT NULL)
    DROP TABLE tmp_contract_mov_archivo;

DELETE FROM contract_mov_archivo;

SELECT *
INTO tmp_contract_mov_archivo
FROM OPENQUERY(PGRESS, 'SELECT idarchivo, idmovimiento, url from sismovil.public.contract_mov_archivo');


set @counterTemp_contract_mov_archivo = (SELECT COUNT(*) FROM tmp_contract_mov_archivo);

if(  @counterOrigin_contract_mov_archivo = @counterTemp_contract_mov_archivo )
    print('contract_mov_archivo table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_mov_archivo table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_mov_archivo ON;

INSERT INTO contract_mov_archivo(idarchivo, idmovimiento, url)
SELECT idarchivo, idmovimiento, url FROM tmp_contract_mov_archivo;

SET IDENTITY_INSERT dbo.contract_mov_archivo OFF;

set @counterTarget_contract_mov_archivo = (SELECT COUNT(*) FROM contract_mov_archivo);

if(  @counterOrigin_contract_mov_archivo = @counterTarget_contract_mov_archivo )
    print('contract_mov_archivo table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_mov_archivo table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_mov_archivo;