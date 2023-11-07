USE db_sismovil;

declare @counterOrigin_contract_usuarioshabilitados as INT
declare @counterTemp_contract_usuarioshabilitados as INT
declare @counterTarget_contract_usuarioshabilitados as INT

set @counterOrigin_contract_usuarioshabilitados = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.contract_usuarioshabilitados'));

if(OBJECT_ID('tmp_contract_usuarioshabilitados') IS NOT NULL)
    DROP TABLE tmp_contract_usuarioshabilitados;

DELETE FROM contract_usuarioshabilitados;

SELECT *
INTO tmp_contract_usuarioshabilitados
FROM OPENQUERY(PGRESS, 'SELECT id, usuario, permission_crear, permission_aprobar, permission_reportes, activo, comprador_logistica, correo, permission_admin, idarea, permission_responsablearea, tipo_usuario from sismovil.public.contract_usuarioshabilitados');


set @counterTemp_contract_usuarioshabilitados = (SELECT COUNT(*) FROM tmp_contract_usuarioshabilitados);

if(  @counterOrigin_contract_usuarioshabilitados = @counterTemp_contract_usuarioshabilitados )
    print('contract_usuarioshabilitados table. Step 1/2 SUCCESS. Number of records match');
else
    print('contract_usuarioshabilitados table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.contract_usuarioshabilitados ON;

INSERT INTO contract_usuarioshabilitados(id, usuario, permission_crear, permission_aprobar, permission_reportes, activo, comprador_logistica, correo, permission_admin, idarea, permission_responsablearea, tipo_usuario)
SELECT id, usuario, permission_crear, permission_aprobar, permission_reportes, activo, comprador_logistica, correo, permission_admin, idarea, permission_responsablearea, tipo_usuario FROM tmp_contract_usuarioshabilitados;

SET IDENTITY_INSERT dbo.contract_usuarioshabilitados OFF;

set @counterTarget_contract_usuarioshabilitados = (SELECT COUNT(*) FROM contract_usuarioshabilitados);

if(  @counterOrigin_contract_usuarioshabilitados = @counterTarget_contract_usuarioshabilitados )
    print('contract_usuarioshabilitados table. Step 2/2 SUCCESS. Number of records match');
else
    print('contract_usuarioshabilitados table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_contract_usuarioshabilitados;