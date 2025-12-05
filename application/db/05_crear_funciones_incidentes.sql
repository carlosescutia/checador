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
Función dias_habiles_periodo(fech_ini, fech_fin)
-----------------------
Genera tabla con los dias habiles entre dos fechas
excluyendo fines de semana
 */
create or replace function dias_habiles_periodo(fech_ini date, fech_fin date)
returns table (fecha date) as
$$
begin
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
Función num_dias_habiles_periodo(fech_ini, fech_fin)
-----------------------
Devuelve el número de días hábiles entre dos fechas
excluyendo fines de semana y dias registrados en tabla dias_inhabiles
 */
create or replace function num_dias_habiles_periodo(fech_ini date, fech_fin date)
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
Función horarios_periodo(fech_ini, fech_fin)
-----------------------
Obtiene horarios (entrada y salida) por empleado entre dos fechas
toma en cuenta horarios especiales
 */
create or replace function horarios_periodo(fech_ini date, fech_fin date)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time) as
$$
begin
    return query
    select
        distinct e.cve_empleado, dh.fecha,
        coalesce(hsp.hora_entrada, hb.hora_entrada) as hora_entrada,
        coalesce(hsp.hora_salida, hb.hora_salida) as hora_salida
    from
        dias_habiles_periodo(fech_ini, fech_fin) dh
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
Función asistencias_periodo(fech_ini, fech_fin)
-----------------------
Obtiene asistencias (entrada y salida) por empleado entre dos fechas
 */
create or replace function asistencias_periodo(fech_ini date, fech_fin date)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time) as
$$
begin
    return query
    select
        distinct e.cve_empleado, dh.fecha, min(a.hora) as hora_entrada, max(a.hora) as hora_salida
    from
        dias_habiles_periodo(fech_ini, fech_fin) dh
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
Función incidentes_periodo(fech_ini, fech_fin, tolerancia_retardo, tolerancia_asistencia)
-----------------------
Obtiene incidentes por empleado entre dos fechas
 */
create or replace function incidentes_periodo(fech_ini date, fech_fin date, tolerancia_retardo varchar, tolerancia_asistencia varchar)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time, cve_incidente int) as
$$
begin
    return query

    /* incidentes de salida */
    with incidentes_salida as (
        select
            a.cve_empleado, a.fecha, a.hora_entrada, a.hora_salida,
            (select
                case when a.hora_entrada is not null then
                    /* día con asistencia */
                    case when a.hora_entrada <> a.hora_salida then
                        /* dos checadas */
                        case when a.hora_salida < h.hora_salida then
                            /* 'salida temprano' */
                            5
                        end
                    else
                        /* solamente una checada */
                        case when a.hora_entrada > '12:00' then
                            /* checada después de las 12 del día, se considera checada de salida */
                            case when a.hora_salida < h.hora_salida then
                                /* 'salida temprano' */
                                5
                            end
                        else
                            /* checada antes de las 12 del día, se considera checada de entrada */
                            /* 'sin salida' */
                            10
                        end
                    end
                end
            ) as cve_incidente
        from
            asistencias_periodo(fech_ini, fech_fin) a
            left join horarios_periodo(fech_ini, fech_fin) h on a.cve_empleado = h.cve_empleado and a.fecha = h.fecha
    )
        select * from incidentes_salida ins where ins.cve_incidente is not null

    union

    /* incidentes de entrada, inasistencias y días sin incidentes */
    select
        a.cve_empleado, a.fecha, a.hora_entrada, a.hora_salida,
        (select
            case when a.hora_entrada is not null then
                /* día con asistencia */
                case when a.hora_entrada <> a.hora_salida then
                    /* dos checadas */
                    case when date_trunc('minute', a.hora_entrada) > h.hora_entrada + '0:15'::interval then
                        /* entrada después de hora de entrada + tolerancia */
                        case when date_trunc('minute', a.hora_entrada) <= h.hora_entrada + '0:30'::interval then
                            /* entrada después de tolerancia pero dentro de retardo */
                            /* 'retardo' */
                            2
                        else
                            /* entrada después de tolerancia y después de retardo */
                            /* 'entrada tardía' */
                            4
                        end
                    end
                else
                    /* solamente una checada */
                    case when a.hora_entrada > '12:00' then
                        /* checada después de las 12 del día, se considera checada de salida */
                        /* 'sin entrada' */
                        7
                    else
                        /* checada antes de las 12 del día, se considera checada de entrada */
                        case when date_trunc('minute', a.hora_entrada) > h.hora_entrada + '0:15'::interval then
                            /* entrada después de hora de entrada + tolerancia */
                            case when date_trunc('minute', a.hora_entrada) <= h.hora_entrada + '0:30'::interval then
                                /* entrada después de tolerancia pero dentro de retardo */
                                /* 'retardo' */
                                2
                            else
                                /* entrada después de tolerancia y después de retardo */
                                /* 'entrada tardía' */
                                4
                            end
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
        asistencias_periodo(fech_ini, fech_fin) a
        left join empleados e on a.cve_empleado = e.cve_empleado
        left join horarios_periodo(fech_ini, fech_fin) h on a.cve_empleado = h.cve_empleado and a.fecha = h.fecha
    order by
        fecha
    ;
end;
$$ language plpgsql strict immutable;


/*
Función justificacion_periodo(fech_ini, fech_fin, tolerancia_retardo, tolerancia_asistencia)
-----------------------
Obtiene justificacion de incidentes por empleado entre dos fechas
 */
create or replace function justificacion_periodo(fech_ini date, fech_fin date, tolerancia_retardo varchar, tolerancia_asistencia varchar)
returns table(cve_empleado int, fecha date, hora_entrada time, hora_salida time, cve_incidente int, tipo_justificante text, cve_justificante int) as
$$
begin
    return query
    /*
    tipo_justificante
    -----------------
    1   día inhábil
    2   justificante masivo tipo Día o Vacación sin especificar empleados a aplicar (aplica a todos)
    3   justificante masivo tipo Día o Vacación especificando empleados a aplicar
    4   justificante individual tipo Día o Vacación
    5   justificante masivo tipo Entrada o Salida sin especificar empleados a aplicar (aplica a todos)
    6   justificante masivo tipo Entrada o salida especificando empleados a aplicar
    7   justificante individual tipo Entrada o Salida
    8   cumple horas de trabajo

    incidentes de entrada
    ---------------------
    2   retardo
    4   entrada tardía
    7   sin entrada

    incidentes de salida
    --------------------
    5   salida temprano
    10  sin salida
    */
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
                select 7 as orden, 'ji' as valor where j.cve_justificante is not null
                union
                select 8 as orden, 'hc' as valor where i.cve_incidente is not null and di.cve_dia_inhabil is null and jm.cve_justificante_masivo is null and j.cve_justificante is null and date_trunc('minute', i.hora_salida) - date_trunc('minute', i.hora_entrada) >= '8:00'
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
                select 7 as orden, j.cve_justificante as valor where j.cve_justificante is not null
                union
                select 8 as orden, 99 as valor where i.cve_incidente is not null and di.cve_dia_inhabil is null and jm.cve_justificante_masivo is null and j.cve_justificante is null and date_trunc('minute', i.hora_salida) - date_trunc('minute', i.hora_entrada) >= '8:00'
                order by orden
            ) as cj
        limit 1 ) as cve_justificante

    from
        incidentes_periodo(fech_ini, fech_fin, tolerancia_retardo, tolerancia_asistencia) i

        left join dias_inhabiles di on di.fecha = i.fecha

        left join justificantes_masivos jm on 
            i.fecha between jm.fecha and coalesce(jm.fech_fin, jm.fecha) 
            and jm.cve_justificante_masivo not in (select distinct cve_justificante_masivo from justificante_masivo_empleados)

        left join justificantes_masivos jm2 on 
            i.fecha between jm2.fecha and coalesce(jm2.fech_fin, jm2.fecha) 
            and i.cve_empleado in (select jme.cve_empleado from justificante_masivo_empleados jme where jme.cve_justificante_masivo = jm2.cve_justificante_masivo)

        left join justificantes j on 
            i.fecha between j.fecha and coalesce(j.fech_fin, j.fecha) 
            and (
                    (j.tipo = 'E' and i.cve_incidente in (2,4,7) )
                    or
                    (j.tipo = 'S' and i.cve_incidente in (5,10) )
                    or
                    (j.tipo in ('D','V') and i.cve_incidente = 11 )
                )
            and j.cve_empleado = i.cve_empleado

    order by
        i.fecha
    ;
end;
$$ language plpgsql strict immutable;
