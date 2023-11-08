ALTER TABLE dbo.[contract_config_correlativos]
    ADD correlativo_draf INT NULL;
INSERT INTO contract_config_correlativos(correlativo_draf) VALUES (1);