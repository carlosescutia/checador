DROP TABLE IF EXISTS empleados;
CREATE TABLE empleados (
    cve_empleado serial,
    cod_empleado text,
    nom_empleado text
);

DROP TABLE IF EXISTS asistencias;
CREATE TABLE asistencias (
    cve_asistencia serial,
    cve_empleado int,
    fecha date,
    hora time
);
