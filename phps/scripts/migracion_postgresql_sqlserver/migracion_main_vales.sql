-- to execute use:
-- sqlcmd -S localhost -U sa -i C:\\scripts\migracion_main_vales.sql -o C:\\scripts\output_vales.txt
-- PLEASE REPLACE THE PATH WITH YOUR LOCAL PATH

--PLEASE RUN FIRST (ONLY ONCE): migracion_main_prescript.sql

:On Error exit

:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_admin.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_usuarioweb.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_centroweb.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_equipoweb.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_grifo.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_material.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_setup.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_usuarioshabilitados.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_horas.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_horasviaje.sql

:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_vale.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_detalle_productos.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_detalle_asignacion.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_img_extras.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_historial_kilometraje.sql
:r C:\Users\Zod\Documents\projects\PHP-DOCKER-SISMOVIL\phps\scripts\migracion_vales_rfc_logs.sql

PRINT 'DATABASE MIGRATION IS COMPLETE'
GO