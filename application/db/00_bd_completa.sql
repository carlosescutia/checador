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

DROP TABLE IF EXISTS empleados;
CREATE TABLE empleados (
    cve_empleado serial,
    cod_empleado text,
    nom_empleado text,
    activo int,
    cve_horario int
);

DROP TABLE IF EXISTS asistencias;
CREATE TABLE asistencias (
    cve_asistencia serial,
    cve_empleado int,
    fecha date,
    hora time
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
    documento text,
    detalle text
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

/*
Función end_of_month(date)
-----------------------
Devuelve el último día del mes
 */
create or replace function end_of_month(date)
returns date as
$$
select (date_trunc('month', $1) + interval '1 month' - interval '1 day')::date;
$$ language 'sql' immutable strict;


/*
Función dias_habiles(mes, anio)
-----------------------
Genera tabla con los dias habiles del mes 
excluyendo fines de semana y dias en tabla dias_inhabiles
 */
create or replace function dias_habiles(mes varchar, anio varchar)
returns table (fecha date) as 
$$
declare
    fech_ini date;
    fech_fin date;
begin
    fech_ini := anio || '-' || mes || '-01';
    fech_fin := end_of_month(fech_ini);
    return query
	select
		dt::date
	from
		generate_series(fech_ini, fech_fin, interval '1' day) as t(dt)
	where
		extract(dow from dt) between 1 and 5 
		and dt not in (select di.fecha from dias_inhabiles di where extract(month from di.fecha)::varchar = mes and extract(year from di.fecha)::varchar = anio)
	;

end; 
$$ language plpgsql strict immutable;

/*
Función asistencias_justificantes
-----------------------
Obtiene asistencias y justificantes por empleado en un mes y año
Utilizada en la función asistencias()
 */
create or replace function asistencias_justificantes()
returns table(cve_empleado int, fecha date, hora time, fuente text) as 
$$
begin
    return query
		select 
			a.cve_empleado, a.fecha, a.hora, 'A' as fuente
		from 
			asistencias a 
	union
		( select 
				j.cve_empleado, j.fecha, h.hora_entrada as hora, j.tipo as fuente
			from 
				justificantes j 
				left join empleados e on j.cve_empleado = e.cve_empleado 
				left join horarios h on e.cve_horario = h.cve_horario 
			where 
				j.tipo in ('E','D','V')
		union 
			select 
				j.cve_empleado, j.fecha, h.hora_salida as hora, j.tipo as fuente
			from 
				justificantes j 
				left join empleados e on j.cve_empleado = e.cve_empleado 
				left join horarios h on e.cve_horario = h.cve_horario 
			where 
				j.tipo in ('S','D','V')
		)
		union
		(
		    select 
                e.cve_empleado, jm.fecha, h.hora_entrada as hora, jm.tipo as fuente 
            from 
                justificantes_masivos jm 
                cross join empleados e 
                left join horarios h on e.cve_horario = h.cve_horario 
            where 
                jm.tipo in ('E','D')
                and e.activo = 1 
		union
		    select 
                e.cve_empleado, jm.fecha, h.hora_salida as hora, jm.tipo as fuente 
            from 
                justificantes_masivos jm 
                cross join empleados e 
                left join horarios h on e.cve_horario = h.cve_horario 
            where 
                jm.tipo in ('S','D')
                and e.activo = 1 
		) 
    ;
end;
$$ language plpgsql strict immutable;

/*
Función asistencias
-----------------------
Obtiene asistencias (entrada y salida) por empleado en un mes y año
 */
create or replace function asistencias(mes varchar, anio varchar)
returns table(fecha date, cve_empleado int, hora_entrada time, hora_salida time) as 
$$
declare
    fech_ini date;
    fech_fin date;
begin
    fech_ini := anio || '-' || mes || '-01';
    fech_fin := end_of_month(fech_ini);
    return query
    select 
        distinct dh.fecha, e.cve_empleado, min(a.hora) as hora_entrada, max(a.hora) as hora_salida
    from 
        dias_habiles(mes, anio) dh 
        cross join empleados e 
        left join asistencias_justificantes() a on dh.fecha = a.fecha and e.cve_empleado = a.cve_empleado
    where
        e.activo = 1
    group by
        dh.fecha, e.cve_empleado
    ;
end;
$$ language plpgsql strict immutable;

/*
Función incidentes
-----------------------
Obtiene incidentes por empleado en un mes y año
 */
create or replace function incidentes(mes varchar, anio varchar, tolerancia varchar)
returns table(fecha date, cve_empleado int, hora_entrada time, hora_salida time, incidente text) as 
$$
declare
    fech_ini date;
    fech_fin date;
begin
    fech_ini := anio || '-' || mes || '-01';
    fech_fin := end_of_month(fech_ini);
    return query
    select
        a.fecha, a.cve_empleado, a.hora_entrada, a.hora_salida, 
        (select
            (case when a.hora_entrada is not null then
                (case when a.hora_entrada <> a.hora_salida then
                    (case 
                        when a.hora_entrada > h.hora_entrada + tolerancia::interval then 
                            (case when a.hora_salida < h.hora_salida then
                                'entrada tarde, salida temprano'
                            else
                                'entrada tarde'
                            end)
                        when a.hora_salida < h.hora_salida then 'salida temprano'
                    end)
                else
                    (case when a.hora_entrada > '12:00' then
                        (case when a.hora_salida < h.hora_salida then 
                            'sin entrada, salida temprano'
                        else 
                            'sin entrada' 
                        end)
                    else
                        (case when a.hora_entrada > h.hora_entrada + tolerancia::interval then 
                            'entrada tarde, sin salida'
                        else
                            'sin salida'
                        end)
                    end)
                end)
            else
                case when a.fecha < now() then 'sin asistencia' end
            end)
        )as incidente
    from 
        asistencias(mes, anio) a 
        left join empleados e on a.cve_empleado = e.cve_empleado
        left join horarios h on e.cve_horario = h.cve_horario
    ;
end;
$$ language plpgsql strict immutable;
