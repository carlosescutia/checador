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
Obtiene asistencias, vacaciones, justificantes individuales y justificantes masivos por empleado en un mes y año
Utilizada en la función asistencias()
 */
create or replace function asistencias_justificantes()
returns table(cve_empleado int, fecha date, hora time) as 
$$
begin
    return query
		select 
			a.cve_empleado, a.fecha, a.hora
		from 
			asistencias a 
	union
		( select 
				j.cve_empleado, j.fecha, h.hora_entrada as hora
			from 
				justificantes j 
				left join empleados e on j.cve_empleado = e.cve_empleado 
				left join horarios h on e.cve_horario = h.cve_horario 
			where 
				j.tipo in ('E','D','V')
		union 
			select 
				j.cve_empleado, j.fecha, h.hora_salida as hora
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
                e.cve_empleado, jm.fecha, h.hora_entrada as hora
            from 
                justificantes_masivos jm 
                cross join empleados e 
                left join horarios h on e.cve_horario = h.cve_horario 
            where 
                jm.tipo in ('E','D')
                and e.activo = 1 
		union
		    select 
                e.cve_empleado, jm.fecha, h.hora_salida as hora
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
returns table(fecha date, cve_empleado int, hora_entrada time, hora_salida time, hora_entrada_real time, hora_salida_real time) as 
$$
declare
    fech_ini date;
    fech_fin date;
begin
    fech_ini := anio || '-' || mes || '-01';
    fech_fin := end_of_month(fech_ini);
    return query

    select 
        distinct dh.fecha, e.cve_empleado, min(aj.hora) as hora_entrada, max(aj.hora) as hora_salida, 
        (case 
            when min(a.hora) = max(a.hora) then
                case 
                    when min(a.hora) < '12:00' then min(a.hora)
                    when min(a.hora) > '12:00' then null
                end
            else min(a.hora)
        end) as hora_entrada_real,
        (case 
            when min(a.hora) = max(a.hora) then
                case 
                    when max(a.hora) > '12:00' then max(a.hora)
                    when max(a.hora) < '12:00' then null
                end
            else max(a.hora)
        end) as hora_salida_real
    from 
        dias_habiles(mes, anio) dh 
        cross join empleados e 
        left join asistencias_justificantes() aj on dh.fecha = aj.fecha and e.cve_empleado = aj.cve_empleado
        left join asistencias a on dh.fecha = a.fecha and e.cve_empleado = a.cve_empleado
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
create or replace function incidentes(mes varchar, anio varchar, tolerancia_retardo varchar, tolerancia_asistencia varchar)
returns table(fecha date, cve_empleado int, hora_entrada time, hora_salida time, hora_entrada_real time, hora_salida_real time, incidente text) as 
$$
declare
    fech_ini date;
    fech_fin date;
begin
    fech_ini := anio || '-' || mes || '-01';
    fech_fin := end_of_month(fech_ini);
    return query
    select
        a.fecha, a.cve_empleado, a.hora_entrada, a.hora_salida, a.hora_entrada_real, a.hora_salida_real,
        (select
            case when a.hora_entrada is not null then
                case when a.hora_entrada <> a.hora_salida then
                    case 
                        when date_trunc('minute', a.hora_entrada) > h.hora_entrada + tolerancia_retardo::interval then 
                            case when date_trunc('minute', a.hora_entrada) <= h.hora_entrada + tolerancia_asistencia::interval then 
                                case when a.hora_salida < h.hora_salida then
                                    'retardo, salida temprano'
                                else
                                    'retardo'
                                end
                            else
                                case when a.hora_salida < h.hora_salida then
                                    'entrada tardía, salida temprano'
                                else
                                    'entrada tardía'
                                end
                            end
                        when a.hora_salida < h.hora_salida then 'salida temprano'
                    end
                else
                    case when a.hora_entrada > '12:00' then
                        case when a.hora_salida < h.hora_salida then 
                            'sin entrada, salida temprano'
                        else 
                            'sin entrada' 
                        end
                    else
                        case when date_trunc('minute', a.hora_entrada) > h.hora_entrada + tolerancia_retardo::interval then 
                            case when date_trunc('minute', a.hora_entrada) <= h.hora_entrada + tolerancia_asistencia::interval then 
                                'retardo, sin salida'
                            else
                                'entrada tardía, sin salida'
                            end
                        else
                            'sin salida'
                        end
                    end
                end
            else
                case when a.fecha < now() then 'inasistencia' end
            end
        ) as incidente
    from 
        asistencias(mes, anio) a 
        left join empleados e on a.cve_empleado = e.cve_empleado
        left join horarios h on e.cve_horario = h.cve_horario
    ;
end;
$$ language plpgsql strict immutable;

/*
Función incidentes_justificados
-----------------------
Obtiene incidentes con informacion de justificantes por empleado en un mes y año
 */
create or replace function incidentes_justificados(mes varchar, anio varchar, tolerancia_retardo varchar, tolerancia_asistencia varchar)
returns table(fecha date, cve_empleado int, hora_entrada time, hora_salida time, hora_entrada_real time, hora_salida_real time, incidente text, cve_justificante int, tipo_justificante text, cve_justificante_masivo int, tipo_justificante_masivo text) as
$$
declare
    fech_ini date;
    fech_fin date;
begin
    fech_ini := anio || '-' || mes || '-01';
    fech_fin := end_of_month(fech_ini);
    return query
    select 
        i.fecha, i.cve_empleado, i.hora_entrada, i.hora_salida, i.hora_entrada_real, i.hora_salida_real, i.incidente, j.cve_justificante, j.tipo as tipo_justificante, jm.cve_justificante_masivo, jm.tipo as tipo_justificante_masivo 
    from 
        incidentes(mes, anio, tolerancia_retardo, tolerancia_asistencia) i 
        left join justificantes j on i.fecha = j.fecha and i.cve_empleado = j.cve_empleado 
        left join justificantes_masivos jm on i.fecha = jm.fecha 
    order by 
        fecha
    ;
end;
$$ language plpgsql strict immutable;
