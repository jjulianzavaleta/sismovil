USE db_sismovil;

declare @counterOrigin_contract_formapao_parntes_detalle as INT
declare @counterTemp_contract_formapao_parntes_detalle as INT
declare @counterTarget_contract_formapao_parntes_detalle as INT

set @counterOrigin_contract_formapao_parntes_detalle = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_formapao_parntes_detalle'));

if(OBJECT_ID('tmp_contract_formapao_parntes_detalle') IS NOT NULL)
    DROP TABLE tmp_contract_formapao_parntes_detalle;

DELETE FROM contract_formapao_parntes_detalle;

SELECT *
INTO tmp_contract_formapao_parntes_detalle
FROM OPENQUERY(PGRESS, 'SELECT id, idsolcontracto, porcentaje, importte from sismovil.public.contract_formapao_parntes_detalle');


set @counterTemp_contract_formapao_parntes_detalle = (SELECT COUNT(*) FROM tmp_contract_formapao_parntes_detalle);

if(  @counterOrigin_contract_formapao_parntes_detalle = @counterTemp_contract_formapao_parntes_detalle )
    print('contract_formapao_parntes_detalle table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_formapao_parntes_detalle table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_formapao_parntes_detalle ON;

INSERT INTO contract_formapao_parntes_detalle(id, idsolcontracto, porcentaje, importte)
SELECT id, idsolcontracto, porcentaje, importte FROM tmp_contract_formapao_parntes_detalle;

SET IDENTITY_INSERT dbo.contract_formapao_parntes_detalle OFF;

set @counterTarget_contract_formapao_parntes_detalle = (SELECT COUNT(*) FROM contract_formapao_parntes_detalle);

if(  @counterOrigin_contract_formapao_parntes_detalle = @counterTarget_contract_formapao_parntes_detalle )
    print('contract_formapao_parntes_detalle table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_formapao_parntes_detalle table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_formapao_parntes_detalle;