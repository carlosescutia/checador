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
Función dias_habiles_rango(fech_ini, fech_fin)
-----------------------
Devuelve el número de días hábiles entre dos fechas
 */
create or replace function dias_habiles_rango(fech_ini date, fech_fin date)
returns integer as 
$$
begin
    return (
        select
            count(*)
        from
            generate_series(fech_ini, fech_fin, interval '1' day) as t(dt)
        where
            extract(dow from dt) between 1 and 5 
            and dt not in (select fecha from dias_inhabiles)
    ) ;
end; 
$$ language plpgsql strict immutable;


/*
Función horarios(mes, anio)
-----------------------
Obtiene horarios (entrada y salida) por empleado en un mes y año
toma en cuenta horarios especiales
 */
create or replace function horarios(mes varchar, anio varchar)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time) as 
$$
begin
    return query
    select 
        distinct e.cve_empleado, dh.fecha,
        coalesce(hsp.hora_entrada, hb.hora_entrada) as hora_entrada,
        coalesce(hsp.hora_salida, hb.hora_salida) as hora_salida
    from 
        dias_habiles(mes, anio) dh  
        cross join empleados e 
        left join horarios_especiales he on he.cve_empleado = e.cve_empleado and dh.fecha::timestamp between he.fech_ini and he.fech_fin
        left join horarios_especiales_dias hed on hed.id_horario_especial = he.id_horario_especial and hed.cve_dia = extract(dow from dh.fecha::timestamp)
        left join horarios hsp on hsp.cve_horario = hed.cve_horario
        left join horarios hb on hb.cve_horario = e.cve_horario
    where
        e.activo = 1
    order by
        dh.fecha
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
        left join horarios(mes, anio) h on a.cve_empleado = h.cve_empleado and a.fecha = h.fecha
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
        , (select valor from
            (
                select 1 as orden, 'di' as valor where di.cve_dia_inhabil is not null
                union
                select 2 as orden, 'jm' as valor where jm.cve_justificante_masivo is not null and jm.tipo in ('D','V')
                union
                select 3 as orden, 'jm' as valor where jm2.cve_justificante_masivo is not null and jm2.tipo in ('D','V')
                union
                select 4 as orden, 'ji' as valor where j.cve_justificante is not null and j.tipo in ('D','V')
                union
                select 5 as orden, 'jm' as valor where jm.cve_justificante_masivo is not null and jm.tipo not in ('D','V')
                union
                select 6 as orden, 'jm' as valor where jm2.cve_justificante_masivo is not null and jm2.tipo not in ('D','V')
                union
                select 7 as orden, 'ji' as valor where j.cve_justificante is not null and j.tipo not in ('D','V')
                union
                select 8 as orden, 'hc' as valor where i.cve_incidente is not null and di.cve_dia_inhabil is null and jm.cve_justificante_masivo is null and j.cve_justificante is null and i.hora_salida - i.hora_entrada >= '8:00'
                order by orden
            ) as tj
        limit 1 ) as tipo_justificante
        , (select valor from
            (
                select 1 as orden, di.cve_dia_inhabil as valor where di.cve_dia_inhabil is not null
                union
                select 2 as orden, jm.cve_justificante_masivo as valor where jm.cve_justificante_masivo is not null and jm.tipo in ('D','V')
                union
                select 3 as orden, jm2.cve_justificante_masivo as valor where jm2.cve_justificante_masivo is not null and jm2.tipo in ('D','V')
                union
                select 4 as orden, j.cve_justificante as valor where j.cve_justificante is not null and j.tipo in ('D','V')
                union
                select 5 as orden, jm.cve_justificante_masivo as valor where jm.cve_justificante_masivo is not null and jm.tipo not in ('D','V')
                union
                select 6 as orden, jm2.cve_justificante_masivo as valor where jm2.cve_justificante_masivo is not null and jm2.tipo not in ('D','V')
                union
                select 7 as orden, j.cve_justificante as valor where j.cve_justificante is not null and j.tipo not in ('D','V')
                union
                select 8 as orden, 99 as valor where i.cve_incidente is not null and di.cve_dia_inhabil is null and jm.cve_justificante_masivo is null and j.cve_justificante is null and i.hora_salida - i.hora_entrada >= '8:00'
                order by orden
            ) as cj
        limit 1 ) as cve_justificante
    from
        incidentes(mes, anio, tolerancia_retardo, tolerancia_asistencia) i
        left join dias_inhabiles di on di.fecha = i.fecha
        left join justificantes_masivos jm on i.fecha between jm.fecha and coalesce(jm.fech_fin, jm.fecha) and jm.cve_justificante_masivo not in (select distinct cve_justificante_masivo from justificante_masivo_empleados)
        left join justificantes_masivos jm2 on i.fecha between jm2.fecha and coalesce(jm2.fech_fin, jm2.fecha) and i.cve_empleado in (select jme.cve_empleado from justificante_masivo_empleados jme where jme.cve_justificante_masivo = jm2.cve_justificante_masivo)
        left join justificantes j on i.fecha between j.fecha and coalesce(j.fech_fin, j.fecha) and j.cve_empleado = i.cve_empleado
    order by
        i.fecha
    ;

end;
$$ language plpgsql strict immutable;
