ALTER TABLE justificantes
ADD fech_fin date;

ALTER TABLE justificantes_masivos
ADD fech_fin date;

DROP TABLE IF EXISTS justificante_masivo_empleados;
CREATE TABLE justificante_masivo_empleados (
    id_justificante_masivo_empleado serial,
    cve_justificante_masivo integer,
    cve_empleado integer
);

