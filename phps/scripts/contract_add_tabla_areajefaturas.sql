CREATE TABLE contract_areajefaturas (
                                        id INT NOT NULL IDENTITY,
                                        idusuariohabilitado INT NOT NULL,
                                        idarea INT NOT NULL,
                                        permission_crear INT NOT NULL DEFAULT 0,
                                        permission_aprobar INT NOT NULL DEFAULT 0,
                                        permission_reportes INT NOT NULL DEFAULT 0,
                                        permission_responsablearea INT NOT NULL DEFAULT 0,
                                        PRIMARY KEY (id),
                                        CONSTRAINT aj_usuarioarea UNIQUE (idusuariohabilitado,idarea),
                                        CONSTRAINT FK_area_aj FOREIGN KEY (idarea)
                                            REFERENCES contract_area (id)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION,
                                        CONSTRAINT FK_usuario_aj FOREIGN KEY (idusuariohabilitado)
                                            REFERENCES contract_usuarioshabilitados (id)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION,
);