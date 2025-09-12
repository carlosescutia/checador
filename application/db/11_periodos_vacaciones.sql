DROP TABLE IF EXISTS justificante_periodo;
CREATE TABLE justificante_periodo (
    id_justificante_periodo serial,
    cve_justificante integer,
    id_periodo integer,
    anio integer
);

DROP TABLE IF EXISTS justificante_masivo_periodo;
CREATE TABLE justificante_masivo_periodo (
    id_justificante_masivo_periodo serial,
    cve_justificante_masivo integer,
    id_periodo integer,
    anio integer
);

DROP TABLE IF EXISTS periodos;
CREATE TABLE periodos (
    id_periodo serial,
    nom_periodo text,
    orden int
);

ALTER TABLE justificantes DROP COLUMN documento ;
