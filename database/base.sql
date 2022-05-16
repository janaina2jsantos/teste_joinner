CREATE DATABASE crud
	
CREATE TABLE pais
(
    id integer NOT NULL,
    nome character varying(30) NOT NULL,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

CREATE TABLE pessoa
(
    id integer NOT NULL,
    nome character varying(50) NOT NULL,
    nascimento date,
    genero character(1),
    pais_id integer NOT NULL,
    deleted_at date,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

-- criar sequence
CREATE SEQUENCE seq_pais_object
AS INT 
START WITH 1 
INCREMENT BY 1;

CREATE SEQUENCE seq_pessoa_object
AS INT 
START WITH 1 
INCREMENT BY 1;

-- inserir seeds
INSERT INTO pais(
	id, nome)
	VALUES (NEXT VALUE for seq_pais_object, 'Brasil');
	
INSERT INTO pais(
    id, nome)
    VALUES (NEXT VALUE for seq_pais_object, 'United States of America');

