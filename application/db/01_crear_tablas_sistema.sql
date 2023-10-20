DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    cve_usuario serial, 
    cve_organizacion integer,
    cve_rol text,
    nom_usuario text,
    usuario text,
    password text,
    activo integer
);

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    cve_rol text,
    nom_rol text
);

DROP TABLE IF EXISTS opciones_sistema;
CREATE TABLE opciones_sistema (
    cve_opcion serial,
    cod_opcion text,
    nom_opcion text,
    url text,
    es_menu integer
);

DROP TABLE IF EXISTS accesos_sistema;
CREATE TABLE accesos_sistema (
    cve_acceso serial,
    cve_rol text,
    cod_opcion text
);

DROP TABLE IF EXISTS opciones_publicas;
CREATE TABLE opciones_publicas (
    cve_opcion serial,
    orden integer,
    nom_opcion text,
    url text
);

DROP TABLE IF EXISTS parametros_sistema;
CREATE TABLE parametros_sistema (
    cve_parametro_sistema serial,
    nom_parametro_sistema text,
    valor_parametro_sistema text
);

DROP TABLE IF EXISTS organizaciones;
CREATE TABLE organizaciones (
    cve_organizacion serial,
    nom_organizacion text
);

DROP TABLE IF EXISTS bitacora;
CREATE TABLE bitacora (
    cve_evento serial,
    fecha date,
    hora time,
    origen text,
    usuario text,
    nom_usuario text,
    nom_organizacion text,
    accion text,
    entidad text,
    valor text
);
