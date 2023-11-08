CREATE TABLE vales_tractor_implemento (
                                        id INT NOT NULL IDENTITY,
                                        valor VARCHAR(50) NULL,
                                        PRIMARY KEY (id),
                                        CONSTRAINT unique_valor UNIQUE (valor)
);

INSERT INTO vales_tractor_implemento(valor) VALUES ('Pulverizadora Jacto');
INSERT INTO vales_tractor_implemento(valor) VALUES ('Red Dragon');
INSERT INTO vales_tractor_implemento(valor) VALUES ('Rufa');
INSERT INTO vales_tractor_implemento(valor) VALUES ('Trituradora');
INSERT INTO vales_tractor_implemento(valor) VALUES ('Mixto');