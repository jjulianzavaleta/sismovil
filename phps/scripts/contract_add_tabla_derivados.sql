CREATE TABLE contract_derivacioneslegal (
                                        id INT NOT NULL IDENTITY,
                                        idcontrato INT NOT NULL,
                                        idusuarioderiva INT NOT NULL,
                                        fechaderiva DATETIME,
                                        detalle VARCHAR(500),
                                        estado INT NOT NULL DEFAULT 0,
                                        idusuarioasignado INT NOT NULL,
                                        idusuariocompleta INT NULL,
                                        fechacompleta DATETIME,
                                        anulado INT NOT NULL DEFAULT 0,
                                        anulado_razon VARCHAR(500),
                                        anulado_usuario INT,
                                        anulado_fecha DATETIME,
                                        PRIMARY KEY (id),
                                        CONSTRAINT FK_user_deriva FOREIGN KEY (idusuarioderiva)
                                            REFERENCES admin (id)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION,
                                        CONSTRAINT FK_user_asignado FOREIGN KEY (idusuarioasignado)
                                            REFERENCES admin (id)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION
);