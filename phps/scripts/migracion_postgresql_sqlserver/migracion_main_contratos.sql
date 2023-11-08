-- to execute use:
-- sqlcmd -S localhost -U sa -i C:\scripts\migracion_main_contratos.sql -o C:\scripts\output_contratos.txt
-- PLEASE REPLACE THE PATH WITH YOUR LOCAL PATH

--PLEASE RUN FIRST (ONLY ONCE): migracion_main_prescript.sql

:On Error exit

:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_config_correlativos.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_area.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_avance.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_credito.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_empresa.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_formapago.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_garantia.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_proveedor.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_tipocontrato.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_tipomoneda.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_usuarioshabilitados.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_vigenciaformato.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_modalidadpago.sql

:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_solcontrato.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_formapao_parntes_detalle.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_inmuebles_archivos.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_inmuebles_partregistral.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_observaciones_ampliaciones.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_penalidades.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_movimiento.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_contract_mov_archivo.sql

PRINT 'DATABASE MIGRATION IS COMPLETE'
GO