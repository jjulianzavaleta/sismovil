USE db_sismovil;

declare @counterOrigin_centroweb as INT
declare @counterTemp_centroweb as INT
declare @counterTarget_centroweb as INT

set @counterOrigin_centroweb = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_centroweb'));

if(OBJECT_ID('tmp_centroweb') IS NOT NULL)
    DROP TABLE tmp_centroweb;

DELETE FROM vales_centroweb;

SELECT *
INTO tmp_centroweb
FROM OPENQUERY(PGRESS, 'SELECT id, kostl, ktext, kzona from sismovil.public.vales_centroweb');


set @counterTemp_centroweb = (SELECT COUNT(*) FROM tmp_centroweb);

if(  @counterOrigin_centroweb = @counterTemp_centroweb )
    print('vales_centroweb table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_centroweb table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_centroweb ON;

INSERT INTO vales_centroweb(id, kostl, ktext, kzona)
SELECT id, kostl, ktext, kzona FROM tmp_centroweb;

SET IDENTITY_INSERT dbo.vales_centroweb OFF;

set @counterTarget_centroweb = (SELECT COUNT(*) FROM vales_centroweb);

if(  @counterOrigin_centroweb = @counterTarget_centroweb )
    print('vales_centroweb table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_centroweb table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_centroweb;