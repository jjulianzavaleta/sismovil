CREATE TABLE vales_tractor_promedio (
                                        id INT NOT NULL IDENTITY,
                                        implemento_id INT NOT NULL,
                                        valor FLOAT NOT NULL,
                                        PRIMARY KEY (id),
                                        CONSTRAINT unique_valor_implemento UNIQUE (implemento_id, valor),
                                        CONSTRAINT FK_implemento FOREIGN KEY (implemento_id)
                                            REFERENCES vales_tractor_implemento (id)
                                            ON DELETE NO ACTION
                                            ON UPDATE NO ACTION,
);

INSERT INTO vales_tractor_promedio(implemento_id, valor) VALUES (1, 1.4);
INSERT INTO vales_tractor_promedio(implemento_id, valor) VALUES (2, 0.8);
INSERT INTO vales_tractor_promedio(implemento_id, valor) VALUES (3, 1.1);
INSERT INTO vales_tractor_promedio(implemento_id, valor) VALUES (4, 0.97);
INSERT INTO vales_tractor_promedio(implemento_id, valor) VALUES (5, 0.93);