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
        dias_habiles('10', '2023') dh 
        cross join empleados e 
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
