USE db_sismovil;

--Fix para ejecutar en PostgreSQL
--update vales_vale set equnr = 5442 where id in (17436,17492,17573)

--Validar
--Que se puede ver el nombre del material en las imagenes extas para el vale: 10536

ALTER TABLE dbo.vales_usuarioweb
    DROP CONSTRAINT AK_material_cod_conductor;
GO

ALTER TABLE dbo.vales_equipoweb
    DROP CONSTRAINT AK_equipoweb_equnr;
GO

ALTER TABLE dbo.vales_grifo
    DROP CONSTRAINT AK_centroweb_nroestacion;
GO

ALTER TABLE dbo.vales_vale
    DROP CONSTRAINT FK_chofer_aux_id;
GO

ALTER TABLE dbo.vales_detalle_img_extras
    DROP CONSTRAINT FK_imagenes_material;
GO

ALTER TABLE dbo.contract_proveedor
    DROP CONSTRAINT AK_contract_proveedor_razon_social;
GO

ALTER TABLE dbo.contract_proveedor
    DROP CONSTRAINT AK_contract_proveedor_ruc;
GO

ALTER TABLE dbo.contract_usuarioshabilitados
    DROP CONSTRAINT AK_contract_userhabilitado_desc;
GO

ALTER TABLE dbo.contract_solcontrato
    DROP CONSTRAINT FK_vale_comprador_responsable;
GO

ALTER TABLE contract_movimiento ALTER COLUMN observacion VARCHAR (2000);
GO