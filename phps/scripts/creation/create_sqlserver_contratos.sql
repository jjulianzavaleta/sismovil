CREATE TABLE contract_area (
                               id INT NOT NULL IDENTITY,
                               descripcion VARCHAR(250),
                               codigo VARCHAR(250),
                               PRIMARY KEY (id),
                               CONSTRAINT AK_contract_area_desc UNIQUE(descripcion)
);

CREATE TABLE contract_proveedor (
                                    idproveedor INT NOT NULL IDENTITY,
                                    ruc VARCHAR(250),
                                    razon_social VARCHAR(250),
                                    PRIMARY KEY (idproveedor),
                                    CONSTRAINT AK_contract_proveedor_ruc UNIQUE(ruc),
                                    CONSTRAINT AK_contract_proveedor_razon_social UNIQUE(razon_social)
);

CREATE TABLE contract_avance (
                                 id INT NOT NULL IDENTITY,
                                 descripcion VARCHAR(900),
                                 PRIMARY KEY (id),
                                 CONSTRAINT AK_contract_avance_desc UNIQUE(descripcion)
);

CREATE TABLE contract_config_correlativos (
                                              id INT NOT NULL IDENTITY,
                                              year VARCHAR(250),
                                              idarea VARCHAR(250),
                                              correlativo NUMERIC(18,0),
                                              PRIMARY KEY (id)
);

CREATE TABLE contract_credito (
                                  id INT NOT NULL IDENTITY,
                                  descripcion VARCHAR(250),
                                  PRIMARY KEY (id),
                                  CONSTRAINT AK_contract_credito_desc UNIQUE(descripcion)
);

CREATE TABLE contract_empresa (
                                  id INT NOT NULL IDENTITY,
                                  descripcion VARCHAR(250),
                                  PRIMARY KEY (id),
                                  CONSTRAINT AK_contract_empresa_desc UNIQUE(descripcion)
);

CREATE TABLE contract_formapago (
                                    id INT NOT NULL IDENTITY,
                                    descripcion VARCHAR(250),
                                    PRIMARY KEY (id),
                                    CONSTRAINT AK_contract_formapago_desc UNIQUE(descripcion)
);

CREATE TABLE contract_garantia (
                                   id INT NOT NULL IDENTITY,
                                   descripcion VARCHAR(250),
                                   CONSTRAINT AK_contract_garantia_desc UNIQUE(descripcion)
);

CREATE TABLE contract_modalidadpago (
                                        id INT NOT NULL IDENTITY,
                                        descripcion VARCHAR(250),
                                        PRIMARY KEY (id),
                                        CONSTRAINT AK_contract_modalidadpago_desc UNIQUE(descripcion)
);

CREATE TABLE contract_tipocontrato (
                                       id INT NOT NULL IDENTITY,
                                       descripcion VARCHAR(250),
                                       PRIMARY KEY (id),
                                       CONSTRAINT AK_contract_tipocontrato_desc UNIQUE(descripcion)
);

CREATE TABLE contract_tipomoneda (
                                     id INT NOT NULL IDENTITY,
                                     descripcion VARCHAR(250),
                                     PRIMARY KEY (id),
                                     CONSTRAINT AK_contract_tipomoneda_desc UNIQUE(descripcion)
);

CREATE TABLE contract_usuarioshabilitados (
                                              id INT NOT NULL IDENTITY,
                                              usuario VARCHAR(250),
                                              correo VARCHAR(250),
                                              permission_crear INT,
                                              permission_aprobar INT,
                                              permission_reportes INT,
                                              permission_responsablearea INT,
                                              permission_admin INT,
                                              comprador_logistica INT,
                                              activo INT,
                                              idarea INT,
                                              tipo_usuario INT,
                                              PRIMARY KEY (id),
                                              CONSTRAINT AK_contract_userhabilitado_desc UNIQUE(usuario)
);

CREATE TABLE contract_vigenciaformato (
                                          id INT NOT NULL IDENTITY,
                                          descripcion VARCHAR(250),
                                          PRIMARY KEY (id),
                                          CONSTRAINT AK_contract_vigenciaformato_desc UNIQUE(descripcion)
);

CREATE TABLE contract_solcontrato (
                                      id INT NOT NULL IDENTITY,
                                      reqgen_a_empresa INT,
                                      reqgen_a_areasolicitante VARCHAR(250),
                                      reqgen_a_areasolicitante_jefatura INT,
                                      reqgen_a_compradorresponsable INT,
                                      reqgen_a_areausuaria INT,
                                      reqgen_a_areausuaria_jefatura INT,
                                      reqgen_proveedor INT,
                                      reqgen_proveedor_ruc VARCHAR(250),
                                      termiesp_a_tipocontrato INT,
                                      termiesp_a_nrocotizacion VARCHAR(250),
                                      termiesp_a_fecha VARCHAR(250),
                                      termiesp_b_alcance VARCHAR(1000),
                                      termiesp_c_dias VARCHAR(250),
                                      termiesp_c_medida INT,
                                      termiesp_c_fechainicio VARCHAR(250),
                                      termiesp_c_fechafin VARCHAR(250),
                                      termiesp_c_incluyeacta INT,
                                      termiesp_d_monto NUMERIC(25,2),
                                      termiesp_d_moneda INT,
                                      termiesp_e_formapago INT,
                                      termiesp_e_avancez_medida INT,
                                      termiesp_e_credito_dias INT,
                                      termiesp_f_modalidadpago INT,
                                      termiesp_g_garantia INT,
                                      termiesp_g_adelanto_importe NUMERIC(25,2),
                                      termiesp_g_fcumplimiento_importe NUMERIC(25,2),
                                      termiesp_g_fondogarantia_importe NUMERIC(25,2),
                                      termiesp_h_lugarentrega VARCHAR(1000),
                                      termiesp_i_observacionesamplicaciones VARCHAR(1000),
                                      reqesp_ruta VARCHAR(1000),
                                      autorizac_a_nombres VARCHAR(250),
                                      autorizac_a_cargo VARCHAR(250),
                                      autorizac_a_fecha VARCHAR(250),
                                      autorizac_b_nombres VARCHAR(250),
                                      autorizac_b_cargo VARCHAR(250),
                                      autorizac_b_fecha VARCHAR(250),
                                      datosgenerales_fecharegistra VARCHAR(250),
                                      datosgenerales_usuarioregistra INT,
                                      datosgenerales_fechaactualiza VARCHAR(250),
                                      datosgenerales_usuarioactualiza INT,
                                      datosgenerales_estado double precision,
                                      datosgenerales_codigo VARCHAR(250),
                                      datosgenerales_urlcontrato VARCHAR(250),
                                      jur_file_ficharuc VARCHAR(250),
                                      jur_file_represetante VARCHAR(250),
                                      jur_file_vigenciapoder VARCHAR(250),
                                      nat_file_ficharuc VARCHAR(250),
                                      nat_file_represetante VARCHAR(250),
                                      proveedor_tipo INT,
                                      anulado INT,
                                      anulado_usuario INT,
                                      anulado_fecha DATETIME,
                                      waitfirmamodo INT,
                                      tipo_renovacion INT,
                                      procesado INT DEFAULT 0,
                                      contrato_vinculado INT DEFAULT 0,
                                      procesado_time DATETIME,
                                      termiesp_c_formato INT,
                                      modalidadpago_otro VARCHAR(250),
                                      contrato_propuesto_proveedor VARCHAR(250),
                                      tipocontrato_otrosdesc VARCHAR(250),
                                      modalidadpago_cartafianza_importe VARCHAR(250),
                                      modalidadpago_adelanto_adelantofile VARCHAR(250),
                                      modalidadpago_adelanto_exception VARCHAR(250),
                                      lugar_entrega_personal_tercero_numero VARCHAR(250),
                                      lugar_entrega_personal_tercero_dias VARCHAR(250),
                                      lugar_entrega_personal_tercero_equipo VARCHAR(250),
                                      metas_cumplir_comentario VARCHAR(250),
                                      metas_cumplir_entregables VARCHAR(250),
                                      rubro_inmuebles_partidaregistral VARCHAR(250),
                                      lugar_entrega_personal_tercero INT,
                                      monto_mobiliario VARCHAR(250),
                                      autorizac_c_nombres VARCHAR(250),
                                      autorizac_c_cargo VARCHAR(250),
                                      autorizac_c_fecha VARCHAR(250),
                                      tipo_flujo INT,
                                      flag_has_last_approved_usuario INT,
                                      flag_has_last_approved_logistica INT,
                                      formapago_medida INT,
                                      modalidadpago_cartafianza_medida INT,
                                      modalidadpago_adelanto_medida INT,
                                      modalidadpago_fcumplimiento_medida INT,
                                      modalidadpago_fgarantia_medida INT,
                                      monto_mobiliario_medida INT,
                                      penalidades_medida INT,
                                      contraprestacion_incdocumento VARCHAR(250),
                                      contraprestacion_file VARCHAR(250),
                                      cotizacion_bynrocontrato INT,
                                      PRIMARY KEY (id),
                                      CONSTRAINT FK_vale_empresa FOREIGN KEY (reqgen_a_empresa)
                                          REFERENCES contract_empresa (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_area_solicitnte_jefatura FOREIGN KEY (reqgen_a_areasolicitante_jefatura)
                                          REFERENCES contract_usuarioshabilitados (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_comprador_responsable FOREIGN KEY (reqgen_a_compradorresponsable)
                                          REFERENCES contract_usuarioshabilitados (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_area_usuaria FOREIGN KEY (reqgen_a_areausuaria)
                                          REFERENCES contract_area (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_area_usuaria_jefatura FOREIGN KEY (reqgen_a_areausuaria_jefatura)
                                          REFERENCES contract_usuarioshabilitados (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_proveedor FOREIGN KEY (reqgen_proveedor)
                                          REFERENCES contract_proveedor (idproveedor)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_tipocontrato FOREIGN KEY (termiesp_a_tipocontrato)
                                          REFERENCES contract_tipocontrato (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_vigencia_formato FOREIGN KEY (termiesp_c_medida)
                                          REFERENCES contract_vigenciaformato (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_tipo_moneda FOREIGN KEY (termiesp_d_moneda)
                                          REFERENCES contract_tipomoneda (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_tipo_formapago FOREIGN KEY (termiesp_e_formapago)
                                          REFERENCES contract_formapago (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_tipo_modalidadpago FOREIGN KEY (termiesp_f_modalidadpago)
                                          REFERENCES contract_modalidadpago (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_registra_admin FOREIGN KEY (datosgenerales_usuarioregistra)
                                          REFERENCES admin (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_edita_admin FOREIGN KEY (datosgenerales_usuarioactualiza)
                                          REFERENCES admin (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_anula_admin FOREIGN KEY (anulado_usuario)
                                          REFERENCES admin (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION,
                                      CONSTRAINT FK_vale_formapago_medida FOREIGN KEY (formapago_medida)
                                          REFERENCES contract_tipomoneda (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION
);

------------------------------------------------------

CREATE TABLE contract_inmuebles_archivos (
                                             id INT NOT NULL IDENTITY,
                                             idcontrato INT,
                                             tipo INT,
                                             url VARCHAR(250),
                                             PRIMARY KEY (id),
                                             CONSTRAINT FK_inmuebles_archivos_contrato FOREIGN KEY (idcontrato)
                                                 REFERENCES contract_solcontrato (id)
                                                 ON DELETE NO ACTION
                                                 ON UPDATE NO ACTION
);

CREATE TABLE contract_inmuebles_partregistral (
                                                  id INT NOT NULL IDENTITY,
                                                  idcontrato INT,
                                                  url VARCHAR(250),
                                                  PRIMARY KEY (id),
                                                  CONSTRAINT FK_inmuebles_partregistral_contrato FOREIGN KEY (idcontrato)
                                                      REFERENCES contract_solcontrato (id)
                                                      ON DELETE NO ACTION
                                                      ON UPDATE NO ACTION
);

CREATE TABLE contract_movimiento (
                                     idmovimiento INT NOT NULL IDENTITY,
                                     idcontrato INT,
                                     idusuario INT,
                                     fecha_registra DATETIME,
                                     observacion VARCHAR(1000),
                                     tipo_flow INT,
                                     estado INT,
                                     title VARCHAR(250),
                                     cerrado INT,
                                     PRIMARY KEY (idmovimiento),
                                     CONSTRAINT FK_movimiento_contrato FOREIGN KEY (idcontrato)
                                         REFERENCES contract_solcontrato (id)
                                         ON DELETE NO ACTION
                                         ON UPDATE NO ACTION,
                                     CONSTRAINT FK_movimiento_admin FOREIGN KEY (idusuario)
                                         REFERENCES admin (id)
                                         ON DELETE NO ACTION
                                         ON UPDATE NO ACTION
);

CREATE TABLE contract_mov_archivo (
                                      idarchivo INT NOT NULL IDENTITY,
                                      idmovimiento INT,
                                      url VARCHAR(900),
                                      PRIMARY KEY (idarchivo),
                                      CONSTRAINT FK_mov_archivo_contrato FOREIGN KEY (idmovimiento)
                                          REFERENCES contract_movimiento (idmovimiento)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION
);

CREATE TABLE contract_observaciones_ampliaciones (
                                                     id INT NOT NULL IDENTITY,
                                                     idcontrato INT,
                                                     url VARCHAR(250),
                                                     PRIMARY KEY (id),
                                                     CONSTRAINT FK_observaciones_apliaciones_contrato FOREIGN KEY (idcontrato)
                                                         REFERENCES contract_solcontrato (id)
                                                         ON DELETE NO ACTION
                                                         ON UPDATE NO ACTION
);

CREATE TABLE contract_penalidades (
                                      id INT NOT NULL IDENTITY,
                                      idcontrato INT,
                                      supuesto VARCHAR(900),
                                      sancion_economica VARCHAR(900),
                                      PRIMARY KEY (id),
                                      CONSTRAINT FK_penalidades_contrato FOREIGN KEY (idcontrato)
                                          REFERENCES contract_solcontrato (id)
                                          ON DELETE NO ACTION
                                          ON UPDATE NO ACTION
);

CREATE TABLE contract_formapao_parntes_detalle (
                                                   id INT NOT NULL IDENTITY,
                                                   idsolcontracto INT NOT NULL,
                                                   porcentaje INT,
                                                   importte INT,
                                                   PRIMARY KEY (id),
                                                   CONSTRAINT FK_formapagodetalle_contrato FOREIGN KEY (idsolcontracto)
                                                       REFERENCES contract_solcontrato (id)
                                                       ON DELETE NO ACTION
                                                       ON UPDATE NO ACTION
);