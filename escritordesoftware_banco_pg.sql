CREATE TABLE software (
id serial NOT NULL,
nome character varying(150),
 CONSTRAINT pk_software_id PRIMARY KEY (id)
);
CREATE TABLE objeto (
id serial NOT NULL,
nome character varying(150),
idsoftware character varying(150),
 CONSTRAINT pk_objeto_id PRIMARY KEY (id)
);
CREATE TABLE atributo (
id serial NOT NULL,
nome character varying(150),
tipo character varying(150),
indice character varying(150),
idobjeto character varying(150),
 CONSTRAINT pk_atributo_id PRIMARY KEY (id)
);
CREATE TABLE usuario (
id serial NOT NULL,
nome character varying(150),
email character varying(150),
login character varying(150),
senha character varying(150),
nivel character varying(150),
 CONSTRAINT pk_usuario_id PRIMARY KEY (id)
);
