USE db_sismovil;

declare @counterOrigin_contract_penalidades as INT
declare @counterTemp_contract_penalidades as INT
declare @counterTarget_contract_penalidades as INT

set @counterOrigin_contract_penalidades = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_penalidades'));

if(OBJECT_ID('tmp_contract_penalidades') IS NOT NULL)
    DROP TABLE tmp_contract_penalidades;

DELETE FROM contract_penalidades;

SELECT *
INTO tmp_contract_penalidades
FROM OPENQUERY(PGRESS, 'SELECT id, idcontrato, supuesto, sancion_economica from sismovil.public.contract_penalidades');


set @counterTemp_contract_penalidades = (SELECT COUNT(*) FROM tmp_contract_penalidades);

if(  @counterOrigin_contract_penalidades = @counterTemp_contract_penalidades )
    print('contract_penalidades table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_penalidades table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_penalidades ON;

INSERT INTO contract_penalidades(id, idcontrato, supuesto, sancion_economica)
SELECT id, idcontrato, supuesto, sancion_economica FROM tmp_contract_penalidades;

SET IDENTITY_INSERT dbo.contract_penalidades OFF;

set @counterTarget_contract_penalidades = (SELECT COUNT(*) FROM contract_penalidades);

if(  @counterOrigin_contract_penalidades = @counterTarget_contract_penalidades )
    print('contract_penalidades table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_penalidades table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_penalidades;