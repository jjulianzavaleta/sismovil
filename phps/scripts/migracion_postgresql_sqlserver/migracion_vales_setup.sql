USE db_sismovil;

declare @counterOrigin_setup as INT
declare @counterTemp_setup as INT
declare @counterTarget_setup as INT

set @counterOrigin_setup = (SELECT * FROM OPENQUERY(PGRESS, 'SELECT Count(*) from sismovil.public.vales_setup'));

if(OBJECT_ID('tmp_setup') IS NOT NULL)
    DROP TABLE tmp_setup;

DELETE FROM vales_setup;

SELECT *
INTO tmp_setup
FROM OPENQUERY(PGRESS, 'SELECT id, version_code, stop_app, max_images_per_product from sismovil.public.vales_setup');


set @counterTemp_setup = (SELECT COUNT(*) FROM tmp_setup);

if(  @counterOrigin_setup = @counterTemp_setup )
    print('vales_setup table. Step 1/2 SUCCESS. Number of records match');
else
    print('vales_setup table. Step 1/2 FAILED. Number of records does not matched');

SET IDENTITY_INSERT dbo.vales_setup ON;

INSERT INTO vales_setup(id, version_code, stop_app, max_images_per_product)
SELECT id, version_code, stop_app, max_images_per_product FROM tmp_setup;

SET IDENTITY_INSERT dbo.vales_setup OFF;

set @counterTarget_setup = (SELECT COUNT(*) FROM vales_setup);

if(  @counterOrigin_setup = @counterTarget_setup )
    print('vales_setup table. Step 2/2 SUCCESS. Number of records match');
else
    print('vales_setup table. Step 2/2 FAILED. Number of records does not matched');

DROP TABLE tmp_setup;