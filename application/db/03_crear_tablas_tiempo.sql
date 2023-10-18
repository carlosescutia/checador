DROP TABLE IF EXISTS vacaciones;
CREATE TABLE vacaciones (
    cve_vacacion serial,
    cve_empleado int,
    fecha date
);

DROP TABLE IF EXISTS dias_inhabiles;
CREATE TABLE dias_inhabiles (
    cve_dia_inhabil serial,
    fecha date,
    desc_dia_inhabil text
);

DROP TABLE IF EXISTS justificantes;
CREATE TABLE justificantes (
    cve_justificante serial,
    cve_empleado int,
    fecha date,
    tipo text,
    documento text
);

DROP TABLE IF EXISTS justificantes_masivos;
CREATE TABLE justificantes_masivos (
    cve_justificante_masivo serial,
    fecha date,
    desc_justificante_masivo text,
    tipo text
);

DROP TABLE IF EXISTS horarios;
CREATE TABLE horarios (
    cve_horario serial,
    desc_horario text,
    hora_entrada time,
    hora_salida time
);
