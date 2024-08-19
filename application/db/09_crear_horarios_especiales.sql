DROP TABLE IF EXISTS horarios_especiales;
CREATE TABLE horarios_especiales (
    id_horario_especial serial,
    cve_empleado int,
    nom_horario_especial text,
    fech_ini date,
    fech_fin date
);

DROP TABLE IF EXISTS horarios_especiales_dias;
CREATE TABLE horarios_especiales_dias (
    id_horario_especial int,
    cve_dia int,
    cve_horario int
);
