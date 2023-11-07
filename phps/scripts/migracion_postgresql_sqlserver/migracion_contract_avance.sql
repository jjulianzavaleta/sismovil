USE db_sismovil;

declare @counterOrigin_contract_avance as INT
declare @counterTemp_contract_avance as INT
declare @counterTarget_contract_avance as INT

set @counterOrigin_contract_avance = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_avance'));

if(OBJECT_ID('temp_contract_avance') IS NOT NULL)
    DROP TABLE temp_contract_avance;

DELETE FROM contract_avance;

SELECT *
INTO temp_contract_avance
FROM OPENQUERY(PGRESS, 'SELECT id, descripcion from sismovil.public.contract_avance');


set @counterTemp_contract_avance = (SELECT COUNT(*) FROM temp_contract_avance);

if(  @counterOrigin_contract_avance = @counterTemp_contract_avance )
    print('contract_avance table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_avance table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_avance ON;

INSERT INTO contract_avance(id, descripcion)
SELECT id, descripcion FROM temp_contract_avance;

SET IDENTITY_INSERT dbo.contract_avance OFF;

set @counterTarget_contract_avance = (SELECT COUNT(*) FROM contract_avance);

if(  @counterOrigin_contract_avance = @counterTarget_contract_avance )
    print('contract_avance table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_avance table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE temp_contract_avance;