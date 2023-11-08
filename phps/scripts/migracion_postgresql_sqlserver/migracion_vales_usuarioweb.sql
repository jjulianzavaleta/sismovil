USE db_sismovil;

declare @counterOrigin_usuarioweb as INT
declare @counterTemp_usuarioweb as INT
declare @counterTarget_usuarioweb as INT

set @counterOrigin_usuarioweb = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_usuarioweb'));

if(OBJECT_ID('tmp_vales_usuarioweb') IS NOT NULL)
    DROP TABLE tmp_vales_usuarioweb;

DELETE FROM vales_usuarioweb;

SELECT *
INTO tmp_vales_usuarioweb
FROM OPENQUERY(PGRESS, 'SELECT id, cod_conductor, name1, num_doc_identidad, estado, password, isflujoconsumidor from sismovil.public.vales_usuarioweb');


set @counterTemp_usuarioweb = (SELECT COUNT(*) FROM tmp_vales_usuarioweb);

if(  @counterOrigin_usuarioweb = @counterTemp_usuarioweb )
    print('vales_usuarioweb table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_usuarioweb table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_usuarioweb ON;

INSERT INTO vales_usuarioweb(id, cod_conductor, name1, num_doc_identidad, estado, password, isflujoconsumidor)
SELECT id, cod_conductor, name1, num_doc_identidad, estado, password, isflujoconsumidor FROM tmp_vales_usuarioweb;

SET IDENTITY_INSERT dbo.vales_usuarioweb OFF;

set @counterTarget_usuarioweb = (SELECT COUNT(*) FROM vales_usuarioweb);

if(  @counterOrigin_usuarioweb = @counterTarget_usuarioweb )
    print('vales_usuarioweb table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_usuarioweb table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_vales_usuarioweb;