USE db_sismovil;

declare @counterOrigin_admin as INT
declare @counterTemp_admin as INT
declare @counterTarget_admin as INT

set @counterOrigin_admin = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.admin'));

if(OBJECT_ID('tmp_admin') IS NOT NULL)
    DROP TABLE tmp_admin;

DELETE FROM admin;

SELECT *
INTO tmp_admin
FROM OPENQUERY(PGRESS, 'SELECT id, usuario, password, nombres, apellidos, activo, permission_data, permission_pedidos, permission_paviferia, manageusers, departamento, puesto, puesto2, from_activedirectory from sismovil.public.admin');


set @counterTemp_admin = (SELECT COUNT(*) FROM tmp_admin);

if(  @counterOrigin_admin = @counterTemp_admin )
    print('admin table. Step 1/2 SUCCESS. Number of records match');
else
    print('admin table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.admin ON;

INSERT INTO admin(id, usuario, password, nombres, apellidos, activo, permission_data, permission_pedidos, permission_paviferia, manageusers, departamento, puesto, puesto2, from_activedirectory)
SELECT id, usuario, password, nombres, apellidos, activo, permission_data, permission_pedidos, permission_paviferia, manageusers, departamento, puesto, puesto2, from_activedirectory FROM tmp_admin;

SET IDENTITY_INSERT dbo.admin OFF;

set @counterTarget_admin = (SELECT COUNT(*) FROM admin);

if(  @counterOrigin_admin = @counterTarget_admin )
    print('admin table. Step 2/2 SUCCESS. Number of records match');
else
    print('admin table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_admin;