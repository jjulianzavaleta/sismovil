USE db_sismovil;

declare @counterOrigin_usuarioshabilitados as INT
declare @counterTemp_usuarioshabilitados as INT
declare @counterTarget_usuarioshabilitados as INT

set @counterOrigin_usuarioshabilitados = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_usuarioshabilitados'));

if(OBJECT_ID('tmp_usuarioshabilitados') IS NOT NULL)
    DROP TABLE tmp_usuarioshabilitados;

DELETE FROM vales_usuarioshabilitados;

SELECT *
INTO tmp_usuarioshabilitados
FROM OPENQUERY(PGRESS, 'SELECT id, usuario, permission_planner, permission_driver, activo, correo, permission_reportes, permission_admin from sismovil.public.vales_usuarioshabilitados');


set @counterTemp_usuarioshabilitados = (SELECT COUNT(*) FROM tmp_usuarioshabilitados);

if(  @counterOrigin_usuarioshabilitados = @counterTemp_usuarioshabilitados )
    print('vales_usuarioshabilitados table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_usuarioshabilitados table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_usuarioshabilitados ON;

INSERT INTO vales_usuarioshabilitados(id, usuario, permission_planner, permission_driver, activo, correo, permission_reportes, permission_admin)
SELECT id, usuario, permission_planner, permission_driver, activo, correo, permission_reportes, permission_admin FROM tmp_usuarioshabilitados;

SET IDENTITY_INSERT dbo.vales_usuarioshabilitados OFF;

set @counterTarget_usuarioshabilitados = (SELECT COUNT(*) FROM vales_usuarioshabilitados);

if(  @counterOrigin_usuarioshabilitados = @counterTarget_usuarioshabilitados )
    print('vales_usuarioshabilitados table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_usuarioshabilitados table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_usuarioshabilitados;