USE db_sismovil;

declare @counterOrigin_contract_movimiento as INT
declare @counterTemp_contract_movimiento as INT
declare @counterTarget_contract_movimiento as INT

set @counterOrigin_contract_movimiento = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_movimiento'));

if(OBJECT_ID('tmp_contract_movimiento') IS NOT NULL)
    DROP TABLE tmp_contract_movimiento;

DELETE FROM contract_movimiento;

SELECT *
INTO tmp_contract_movimiento
FROM OPENQUERY(PGRESS, 'SELECT idmovimiento, idcontrato, idusuario, fecha_registra, observacion, tipo_flow, estado, title, cerrado from sismovil.public.contract_movimiento');


set @counterTemp_contract_movimiento = (SELECT COUNT(*) FROM tmp_contract_movimiento);

if(  @counterOrigin_contract_movimiento = @counterTemp_contract_movimiento )
    print('contract_movimiento table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_movimiento table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_movimiento ON;

INSERT INTO contract_movimiento(idmovimiento, idcontrato, idusuario, fecha_registra, observacion, tipo_flow, estado, title, cerrado)
SELECT idmovimiento, idcontrato, idusuario, fecha_registra, observacion, tipo_flow, estado, title, cerrado FROM tmp_contract_movimiento;

SET IDENTITY_INSERT dbo.contract_movimiento OFF;

set @counterTarget_contract_movimiento = (SELECT COUNT(*) FROM contract_movimiento);

if(  @counterOrigin_contract_movimiento = @counterTarget_contract_movimiento )
    print('contract_movimiento table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_movimiento table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_movimiento;