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
excluyendo fines de semana
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
	;
end; 
$$ language plpgsql strict immutable;


/*
Función asistencias(mes, anio)
-----------------------
Obtiene asistencias (entrada y salida) por empleado en un mes y año
 */
create or replace function asistencias(mes varchar, anio varchar)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time) as 
$$
begin
    return query
    select 
        distinct e.cve_empleado, dh.fecha, min(a.hora) as hora_entrada, max(a.hora) as hora_salida
    from 
        dias_habiles(mes, anio) dh 
        cross join empleados e 
        left join asistencias a on dh.fecha = a.fecha and e.cve_empleado = a.cve_empleado
    where
        e.activo = 1
    group by
        e.cve_empleado, dh.fecha
    order by
        dh.fecha
    ;
end;
$$ language plpgsql strict immutable;


/*
Función incidentes(mes, anio, tolerancia_retardo, tolerancia_asistencia)
-----------------------
Obtiene incidentes por empleado en un mes y año
 */
create or replace function incidentes(mes varchar, anio varchar, tolerancia_retardo varchar, tolerancia_asistencia varchar)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time, cve_incidente int) as 
$$
begin
    return query
    select
        a.cve_empleado, a.fecha, a.hora_entrada, a.hora_salida,
        (select
            case when a.hora_entrada is not null then
                case when a.hora_entrada <> a.hora_salida then
                    case 
                        when date_trunc('minute', a.hora_entrada) > h.hora_entrada + tolerancia_retardo::interval then 
                            case when date_trunc('minute', a.hora_entrada) <= h.hora_entrada + tolerancia_asistencia::interval then 
                                case when a.hora_salida < h.hora_salida then 
                                    /* 'retardo, salida temprano' */
                                    1
                                else
                                    /* 'retardo' */
                                    2
                                end
                            else
                                case when a.hora_salida < h.hora_salida then
                                    /* 'entrada tardía, salida temprano' */
                                    3
                                else
                                    /* 'entrada tardía' */
                                    4
                                end
                            end
                        when a.hora_salida < h.hora_salida then 
                            /* 'salida temprano' */ 
                            5
                    end
                else
                    case when a.hora_entrada > '12:00' then
                        case when a.hora_salida < h.hora_salida then 
                            /* 'sin entrada, salida temprano' */ 
                            6
                        else 
                            /* 'sin entrada' */
                            7
                        end
                    else
                        case when date_trunc('minute', a.hora_entrada) > h.hora_entrada + tolerancia_retardo::interval then 
                            case when date_trunc('minute', a.hora_entrada) <= h.hora_entrada + tolerancia_asistencia::interval then 
                                /* 'retardo, sin salida' */
                                8
                            else
                                /* 'entrada tardía, sin salida' */
                                9
                            end
                        else
                            /* 'sin salida' */
                            10
                        end
                    end
                end
            else
                case when a.fecha < now() then 
                    /* 'inasistencia' */
                    11
                end
            end
        ) as cve_incidente
    from 
        asistencias(mes, anio) a 
        left join empleados e on a.cve_empleado = e.cve_empleado
        left join horarios h on e.cve_horario = h.cve_horario
    order by
        fecha
    ;
end;
$$ language plpgsql strict immutable;


/*
Función justificacion(mes, anio, tolerancia_retardo, tolerancia_asistencia)
-----------------------
Obtiene justificacion de incidentes por empleado en un mes y año
 */
create or replace function justificacion(mes varchar, anio varchar, tolerancia_retardo varchar, tolerancia_asistencia varchar)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time, cve_incidente int, tipo_justificante text, cve_justificante int) as 
$$
begin
    return query
	select
		i.*
		, (select * from
			(
				select 'di' from dias_inhabiles di where di.fecha = i.fecha
				union
				select 'jm' from justificantes_masivos jm where jm.fecha = i.fecha
				union
				select 'ji' from justificantes j where j.fecha = i.fecha and j.cve_empleado = i.cve_empleado
				union
				select 'hc' where i.cve_incidente is not null and i.hora_salida - i.hora_entrada >= '8:00'
			) as tj
		limit 1 ) as tipo_justificante
		, (select * from
			(
				select di.cve_dia_inhabil from dias_inhabiles di where di.fecha = i.fecha
				union
				select jm.cve_justificante_masivo from justificantes_masivos jm where jm.fecha = i.fecha
				union
				select j.cve_justificante from justificantes j where j.fecha = i.fecha and j.cve_empleado = i.cve_empleado
				union
				select 99 where i.cve_incidente is not null and i.hora_salida - i.hora_entrada >= '8:00'
			) as cj
		limit 1 ) as cve_justificante
	from
		incidentes(mes, anio, tolerancia_retardo, tolerancia_asistencia) i
	order by
		i.fecha
	;
end;
$$ language plpgsql strict immutable;
