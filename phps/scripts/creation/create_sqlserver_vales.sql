/*
ALTER TABLE vales_usuarioshabilitados
ADD CONSTRAINT AK_usuarioshabilitados_usuario UNIQUE (usuario)
*/

CREATE TABLE admin (
                       id INT NOT NULL IDENTITY,
                       usuario VARCHAR(250) NOT NULL,
                       password VARCHAR(250) NOT NULL,
                       nombres VARCHAR(250) NOT NULL,
                       apellidos VARCHAR(250) NOT NULL,
                       activo INT NOT NULL,
                       permission_data INT,
                       permission_pedidos INT,
                       permission_paviferia INT,
                       manageusers INT,
                       departamento VARCHAR(250),
                       puesto VARCHAR(250),
                       puesto2 VARCHAR(250),
                       from_activedirectory INT,
                       PRIMARY KEY (id),
                       CONSTRAINT AK_admin_usuario UNIQUE(usuario)
);

CREATE TABLE vales_centroweb (
                                 id INT NOT NULL IDENTITY,
                                 kostl VARCHAR(250),
                                 ktext VARCHAR(250),
                                 kzona VARCHAR(500),
                                 PRIMARY KEY (id),
                                 CONSTRAINT AK_centroweb_kostl UNIQUE(kostl)
);

CREATE TABLE vales_grifo (
                             id INT NOT NULL IDENTITY,
                             nombre VARCHAR(250),
                             descripcion VARCHAR(250),
                             longitud VARCHAR(250),
                             latitud VARCHAR(250),
                             direccion VARCHAR(1500),
                             nroestacion VARCHAR(250),
                             flujo VARCHAR(10),
                             descripcion2 VARCHAR(1500),
                             PRIMARY KEY (id),
                             CONSTRAINT AK_grifo_nombre UNIQUE(nombre),
                             CONSTRAINT AK_grifo_nroestacion UNIQUE(nroestacion)
);

CREATE TABLE vales_material (
                                id INT NOT NULL IDENTITY,
                                cod_sap VARCHAR(250),
                                nombre VARCHAR(250),
                                rfcname VARCHAR(250),
                                PRIMARY KEY (id),
                                CONSTRAINT AK_material_cod_sap UNIQUE(cod_sap),
                                CONSTRAINT AK_material_nombre UNIQUE(nombre)
);

CREATE TABLE vales_usuarioweb (
                                  id INT NOT NULL IDENTITY,
                                  cod_conductor VARCHAR(250),
                                  name1 VARCHAR(500),
                                  num_doc_identidad VARCHAR(250),
                                  estado INT,
                                  password VARCHAR(500),
                                  isflujoconsumidor INT DEFAULT 0,
                                  PRIMARY KEY (id),
                                  CONSTRAINT AK_usuarioweb_cod_conductor UNIQUE(cod_conductor),
    --CONSTRAINT AK_usuarioweb_cod_num_doc_identidad UNIQUE(num_doc_identidad)
);

CREATE TABLE vales_usuarioshabilitados (
                                           id INT NOT NULL IDENTITY,
                                           usuario VARCHAR(250) NOT NULL,
                                           permission_planner INT DEFAULT 0,
                                           permission_driver INT DEFAULT 0,
                                           activo INT,
                                           correo VARCHAR(250),
                                           permission_reportes INT DEFAULT 0,
                                           permission_admin INT DEFAULT 0,
                                           PRIMARY KEY (id),
                                           CONSTRAINT AK_usuarioshabilitados_usuario UNIQUE(usuario),
                                           CONSTRAINT AK_usuarioshabilitados_correo UNIQUE(correo)
);

CREATE TABLE vales_setup (
                             id INT NOT NULL IDENTITY,
                             version_code VARCHAR(250),
                             stop_app VARCHAR(250),
                             max_images_per_product INT,
                             PRIMARY KEY (id)
);

CREATE TABLE vales_equipoweb (
                                 id INT NOT NULL IDENTITY,
                                 equnr VARCHAR(250),
                                 txt_hequi VARCHAR(250),
                                 kostl VARCHAR(250),
                                 license_num VARCHAR(250),
                                 sttxt VARCHAR(250),
                                 termoking INT DEFAULT 0,
                                 kilometraje NUMERIC(14,2) DEFAULT 0,
                                 gps INT,
                                 ruta VARCHAR(250),
                                 rendimiento_estandar NUMERIC(6,2),
                                 re_fecha_updated DATETIME,
                                 re_userid_updated INT,
                                 operacion VARCHAR(250),
                                 medida_contador INT,
                                 PRIMARY KEY (id),
                                 CONSTRAINT AK_equipoweb_equnr UNIQUE(equnr)
);

CREATE TABLE vales_vale (
                            id INT NOT NULL IDENTITY,
                            equnr INT,
                            grifo INT,
                            chofer INT,
                            chofer_aux INT,
                            placa VARCHAR(250),
                            fecha_max_consumo DATE,
                            kilom NUMERIC(25,3),
                            fecha_registro DATE,
                            detalle2_modo INT,
                            isflujoconsumidor INT DEFAULT 0,
                            istermoking INT DEFAULT 0,
                            hascarreta INT,
                            estado_pm INT,
                            estado INT,
                            anulado INT,
                            usuario_registra INT,
                            usuario_modifica INT,
                            usuario_emite INT,
                            usuario_anula INT,
                            fecha_registra DATETIME,
                            fecha_modifica DATETIME,
                            fecha_emite DATETIME,
                            fecha_anula DATETIME,
                            consumo_idusuario INT,
                            consumo_fechaconsumo DATETIME,
                            consumo_gps_longitude VARCHAR(250),
                            consumo_gps_latitude VARCHAR(250),
                            consumo_observacion VARCHAR(250),
                            consumo_unidadmedida INT,
                            tsomobile_kilometraje VARCHAR(250),
                            tsomobile_somethingwentwrong INT,
                            tsomobile_byjob INT,
                            tsomobile_fechaconsulta DATETIME,
                            tsomobile_response  VARCHAR(2000),
                            tsomobile_endpoint VARCHAR(500),
                            rfcconsumo_somethingwentwrong INT,
                            PRIMARY KEY (id),
                            CONSTRAINT FK_equipoweb_id FOREIGN KEY (equnr)
                                REFERENCES vales_equipoweb (id)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                            CONSTRAINT FK_grifo_id FOREIGN KEY (grifo)
                                REFERENCES vales_grifo (id)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                            CONSTRAINT FK_chofer_id FOREIGN KEY (chofer)
                                REFERENCES vales_usuarioweb (id)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                            CONSTRAINT FK_chofer_aux_id FOREIGN KEY (chofer_aux)
                                REFERENCES vales_usuarioweb (id)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                            CONSTRAINT FK_consumo_idusuario FOREIGN KEY (consumo_idusuario)
                                REFERENCES vales_usuarioweb (id)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
);

CREATE TABLE vales_detalle_asignacion (
                                          id INT NOT NULL IDENTITY,
                                          asignacion NUMERIC(25,2),
                                          kostl INT,
                                          idvale INT,
                                          matnr INT,
                                          PRIMARY KEY (id),
                                          CONSTRAINT FK_vale_id FOREIGN KEY (idvale)
                                              REFERENCES vales_vale (id)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION,
                                          CONSTRAINT FK_asignacion_material FOREIGN KEY (matnr)
                                              REFERENCES vales_material (id)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION,
                                          CONSTRAINT FK_asignacion_centroweb FOREIGN KEY (kostl)
                                              REFERENCES vales_centroweb (id)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION
);

CREATE TABLE vales_detalle_img_extras (
                                          id INT NOT NULL IDENTITY,
                                          idvale INT NOT NULL,
                                          matnr INT NOT NULL,
                                          voucher_img VARCHAR(250) NOT NULL,
                                          PRIMARY KEY (id),
                                          CONSTRAINT FK_vale_id_img_extras FOREIGN KEY (idvale)
                                              REFERENCES vales_vale (id)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION,
                                          CONSTRAINT FK_imagenes_material FOREIGN KEY (matnr)
                                              REFERENCES vales_material (id)
                                              ON DELETE NO ACTION
                                              ON UPDATE NO ACTION
);

CREATE TABLE vales_detalle_productos (
                                         id INT NOT NULL IDENTITY,
                                         matnr INT,
                                         idvale INT,
                                         menge numeric(25,3),
                                         menge_chofer numeric(25,3),
                                         voucher_img VARCHAR(250),
                                         voucher_nro VARCHAR(250),
                                         fromexcel_total numeric(25,3),
                                         fromexcel_cantidad numeric(25,3),
                                         fromexcel_precio numeric(25,3),
                                         PRIMARY KEY (id),
                                         CONSTRAINT FK_vale_id_detalleproductos FOREIGN KEY (idvale)
                                             REFERENCES vales_vale (id)
                                             ON DELETE NO ACTION
                                             ON UPDATE NO ACTION,
                                         CONSTRAINT FK_vale_id_detalleproductos_matnr FOREIGN KEY (matnr)
                                             REFERENCES vales_material (id)
                                             ON DELETE NO ACTION
                                             ON UPDATE NO ACTION
);

CREATE TABLE vales_historial_kilometraje (
                                             id INT NOT NULL IDENTITY,
                                             idvale INT NOT NULL,
                                             usuario INT NOT NULL,
                                             fecha DATETIME NOT NULL,
                                             vale_valor_old numeric(25,3) NOT NULL,
                                             vale_valor_new numeric(25,3) NOT NULL,
                                             was_equipo_valor_updated INT NOT NULL,
                                             vale_obs_old VARCHAR(250),
                                             vale_obs_new VARCHAR(250),
                                             PRIMARY KEY (id),
                                             CONSTRAINT FK_vale_id_historialkilometraje FOREIGN KEY (idvale)
                                                 REFERENCES vales_vale (id)
                                                 ON DELETE NO ACTION
                                                 ON UPDATE NO ACTION
);

CREATE TABLE vales_rfc_logs (
                                id INT NOT NULL IDENTITY,
                                idvale INT NOT NULL,
                                rfc VARCHAR(250) NOT NULL,
                                fecha DATETIME NOT NULL,
                                request VARCHAR(2000) NOT NULL,
                                response VARCHAR(2000) NOT NULL,
                                success INT NOT NULL,
                                byjob INT,
                                PRIMARY KEY (id),
                                CONSTRAINT FK_vale_id_rfclogs FOREIGN KEY (idvale)
                                    REFERENCES vales_vale (id)
                                    ON DELETE NO ACTION
                                    ON UPDATE NO ACTION
);

CREATE TABLE vales_horas (
        placa VARCHAR(250),
        vale_id INT,
        fecha_viaje_inicio DATETIME,
        fecha_viaje_fin DATETIME,
        dias_transcurrido INT,
        horas_transcurrido INT,
        minutos_transcurrido INT,
        segundos_transcurrido INT,
        dias_encendido INT,
        horas_encendido INT,
        minutos_encendido INT,
        segundos_encendido INT,
        dias_inactivo INT,
        horas_inactivo INT,
        minutos_inactivo INT,
        segundos_inactivo INT,
        dias_fuera_geo INT,
        horas_fuera_geo INT,
        minutos_fuera_geo INT,
        segundos_fuera_geo INT,
        dias_conduccion INT,
        horas_conduccion INT,
        minutos_conduccion INT,
        segundos_conduccion INT
    );

CREATE TABLE vales_horasviaje (
                                  placa VARCHAR(250),
                                  fecha_viaje_inicio DATETIME,
                                  hora INT,
                                  minutos INT,
                                  segundos INT,
                                  fecha_viaje_fin DATETIME,
                                  vale_id INT,
                                  dia INT
);