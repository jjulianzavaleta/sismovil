# SISMOVIL

Consta de 3 sistemas funcionando en PHP, actualmente con soporte para PHP Version 7.3.13

  - Paviferia - desarrollado en 2015
  - Sistema de Contratos - desarrollado desde 2019
  - Sistema de Vales - desarrollado desde

Al ser el sistema tan antiguo no se implementó usando framework, al no contar con presupuesto para una migración a framework como Laravel no se tienen planes para una migración, aunque es altamente sugerida.

# Instalación
- Es importante ir al archivo ```php.ini``` y actualizar los valores:
 ```sh
post_max_size = 100M
upload_max_filesize = 40M
 ```
 - Para desarrollo desde Windows descargar los dll de Miscrosoft SQL Server y agregar al archivo ```php.ini```:
 ```sh
extension=php_pdo_sqlsrv_73_ts_x64
extension=php_sqlsrv_73_ts_x64
  ```
  - Se debe crear un cron en linux para ejecutar los 3 archivos main que están en:
 ```sh
 \sismovil\jobs\
  ```
  - Dar permisos a apache para escribir en la carpeta donde se guardan archivos, y al mismo tiempo poner a apache como propietario. Esto para las carpetas:
  ```sh
sismovil\files\
sismovil\pedidospaviferia\
```

# Paviferia

  - Al momento de generar reportes en PDF, estos se guardan temporalmente en: 
  ```sh
sismovil\pedidospaviferia\reportes_pdf\temp
```
 # Sistema de Contratos
 - Los archivos en PDF se guardan en 
 ```sh
sismovil\files\contratos
```
 - Tiene 2 jobs, estos son:
 ```sh
\sismovil\jobs\contract_process\actions.php
 ```
  ```sh
 \sismovil\jobs\contract_process\alerts.php
  ```
que se ejecutan desde el archivo:
```sh
 \sismovil\jobs\main.php
  ```
  - Para limpiar la base de datos de contratos y empezar desde cero, ejecutar en el gestor de base de datos el script:
  ```sh
sismovil\phps\scripts\clean_tablas_contratos.sql
  ``` 
  y luego eliminar el contenido de la carpeta
  ```sh
  files/contratos/
  ```
  - Para forzar generar el PDF ```Solicitud PDF``` hacer un POST request al endpoint:
   ```sh
http://www.chimuagropecuaria.com.pe/sismovil/phps/dcontract_ajax.php
```
con los parametros:
  ```sh
  cod: 8
  id: id del contrato
```
   - Las credenciales del email para notificaciones se guarda en:
  ```sh
\sismovil\phps\Emailer.php
```

# Sistema de Vales
- Los archivos adjuntos (imágenes) se guardan en 
 ```sh
sismovil\files\vales
```
- En la tabla: 
```sh
vales_setup
```
se guarda la versión de la app movil permitida y si se forzará su uso como mínima versión
- Tiene 3 jobs estos son:
 ```sh
 \sismovil\jobs\main_sendValesConsumidosToSAP.php
  ```
  ```sh
 \sismovil\jobs\main_valesFetchDataFromSAP.php
  ```
  ```sh
 \sismovil\jobs\main_valesFetchDataFromTSOMobile.php
  ```
  - Para la ejecución de los ```RFC``` y ```TSOMobile``` se tiene que estar dentro de la ```VPN Chimu```
  
  
  
  
  
  
  
