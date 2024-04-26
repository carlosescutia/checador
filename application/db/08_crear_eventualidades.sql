DROP TABLE IF EXISTS eventualidades;
CREATE TABLE eventualidades (
    cve_eventualidad serial,
    nom_eventualidad text
);

ALTER TABLE justificantes
ADD cve_eventualidad int;

DROP TABLE IF EXISTS vacaciones;
