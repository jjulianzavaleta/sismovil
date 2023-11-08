USE db_sismovil;

declare @counterOrigin_contract_vigenciaformato as INT
declare @counterTemp_contract_vigenciaformato as INT
declare @counterTarget_contract_vigenciaformato as INT

set @counterOrigin_contract_vigenciaformato = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_vigenciaformato'));

if(OBJECT_ID('tmp_contract_vigenciaformato') IS NOT NULL)
    DROP TABLE tmp_contract_vigenciaformato;

DELETE FROM contract_vigenciaformato;

SELECT *
INTO tmp_contract_vigenciaformato
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_vigenciaformato');


set @counterTemp_contract_vigenciaformato = (SELECT COUNT(*) FROM tmp_contract_vigenciaformato);

if(  @counterOrigin_contract_vigenciaformato = @counterTemp_contract_vigenciaformato )
    print('contract_vigenciaformato table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_vigenciaformato table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_vigenciaformato ON;

INSERT INTO contract_vigenciaformato(id, descripcion)
SELECT id, descripcion FROM tmp_contract_vigenciaformato;

SET IDENTITY_INSERT dbo.contract_vigenciaformato OFF;

set @counterTarget_contract_vigenciaformato = (SELECT COUNT(*) FROM contract_vigenciaformato);

if(  @counterOrigin_contract_vigenciaformato = @counterTarget_contract_vigenciaformato )
    print('contract_vigenciaformato table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_vigenciaformato table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_vigenciaformato;