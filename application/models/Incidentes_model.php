<?php
class Incidentes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_lista_incidentes_empleados_todos($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select e.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(j.cve_incidente) as num_incidentes from empleados e left join justificacion(?,?,?,?) j on j.cve_empleado = e.cve_empleado and j.cve_incidente is not null and j.tipo_justificante is null left join horarios h on e.cve_horario = h.cve_horario where e.activo = 1 group by e.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_lista_incidentes_empleados_pendientes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select j.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(j.*) as num_incidentes from justificacion(?,?,?,?) j left join empleados e on j.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario where j.cve_incidente is not null and j.tipo_justificante is null group by j.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_incidentes_empleado($cve_empleado, $mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = ""
            ."select  "
            ."j.cve_empleado, j.fecha, j.hora_entrada, j.hora_salida, j.cve_incidente, ti.desc_incidente, j.tipo_justificante, j.cve_justificante "
            .",( "
            ."select 'dia inhabil' from dias_inhabiles di where j.tipo_justificante = 'di' and j.cve_justificante = di.cve_dia_inhabil "
            ."union "
            ."select case when tipo = 'D' then 'dia masivo justificado' when tipo = 'E' then 'entrada masiva justificada' when tipo = 'S' then 'salida masiva justificada' end from justificantes_masivos jm where j.tipo_justificante = 'jm' and j.cve_justificante = jm.cve_justificante_masivo "
            ."union "
            ."select case when tipo = 'V' then 'vacaciones' when tipo = 'S' then 'salida justificada' when tipo = 'E' then 'entrada justificada' when tipo = 'D' then 'dia justificado' end from justificantes ji where j.tipo_justificante = 'ji' and j.cve_justificante = ji.cve_justificante "
            ."union "
            ."select 'fuera de horario' where j.tipo_justificante = 'hc' "
            .") as desc_corta_justificante "
            .",( "
            ."select di.desc_dia_inhabil from dias_inhabiles di where j.tipo_justificante = 'di' and j.cve_justificante = di.cve_dia_inhabil "
            ."union "
            ."select jm.desc_justificante_masivo from justificantes_masivos jm where j.tipo_justificante = 'jm' and j.cve_justificante = jm.cve_justificante_masivo "
            ."union "
            ."select ji.detalle from justificantes ji where j.tipo_justificante = 'ji' and j.cve_justificante = ji.cve_justificante "
            ."union "
            ."select 'Cumple horas de trabajo' where j.tipo_justificante = 'hc' "
            .") as desc_justificante "
            ."from  "
            ."justificacion(?,?,?,?) j  "
            ."left join tipo_incidentes ti on ti.cve_incidente = j.cve_incidente "
            ."where  "
            ."j.cve_empleado = ? "
            ."order by "
            ."j.fecha "
            ;
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia, $cve_empleado));
        return $query->result_array();
    }

    public function get_incidentes_empleados($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = ""
            ."select  "
            ."j.cve_empleado, j.fecha, j.hora_entrada, j.hora_salida, j.cve_incidente, ti.desc_incidente, j.tipo_justificante, j.cve_justificante "
            .",( "
            ."select 'dia inhabil' from dias_inhabiles di where j.tipo_justificante = 'di' and j.cve_justificante = di.cve_dia_inhabil "
            ."union "
            ."select case when tipo = 'D' then 'dia masivo justificado' when tipo = 'E' then 'entrada masiva justificada' when tipo = 'S' then 'salida masiva justificada' end from justificantes_masivos jm where j.tipo_justificante = 'jm' and j.cve_justificante = jm.cve_justificante_masivo "
            ."union "
            ."select case when tipo = 'V' then 'vacaciones' when tipo = 'S' then 'salida justificada' when tipo = 'E' then 'entrada justificada' when tipo = 'D' then 'dia justificado' end from justificantes ji where j.tipo_justificante = 'ji' and j.cve_justificante = ji.cve_justificante "
            ."union "
            ."select 'fuera de horario' where j.tipo_justificante = 'hc' "
            .") as desc_corta_justificante "
            .",( "
            ."select di.desc_dia_inhabil from dias_inhabiles di where j.tipo_justificante = 'di' and j.cve_justificante = di.cve_dia_inhabil "
            ."union "
            ."select jm.desc_justificante_masivo from justificantes_masivos jm where j.tipo_justificante = 'jm' and j.cve_justificante = jm.cve_justificante_masivo "
            ."union "
            ."select ji.detalle from justificantes ji where j.tipo_justificante = 'ji' and j.cve_justificante = ji.cve_justificante "
            ."union "
            ."select 'Cumple horas de trabajo' where j.tipo_justificante = 'hc' "
            .") as desc_justificante "
            ."from  "
            ."justificacion(?,?,?,?) j  "
            ."left join tipo_incidentes ti on ti.cve_incidente = j.cve_incidente "
            ."order by "
            ."j.fecha "
            ;
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_lista_incidentes_fechas($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select j.fecha, count(j.cve_incidente) as num_incidentes from justificacion(?,?,?,?) j where j.cve_incidente is not null and j.tipo_justificante is null group by j.fecha order by j.fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_incidentes_fecha($fecha, $mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select j.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(j.*) as num_incidentes from justificacion(?,?,?,?) j left join empleados e on j.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario where fecha = ? and j.cve_incidente is not null and j.tipo_justificante is null group by j.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia, $fecha));
        return $query->result_array();
    }


    public function get_tot_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select count(j.cve_incidente) as tot_incidentes from justificacion(?,?,?,?) j where j.cve_incidente is not null and j.tipo_justificante is null' ;
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->row_array()['tot_incidentes'] ?? null ;
    }

    public function get_tot_empleados_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select count(distinct j.cve_empleado) as tot_empleados_incidentes from justificacion(?,?,?,?) j where j.cve_incidente is not null and j.tipo_justificante is null ' ;
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->row_array()['tot_empleados_incidentes'] ?? null ;
    }

    public function get_tot_dias_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select count(distinct j.fecha) as tot_dias_incidentes from justificacion(?,?,?,?) j where j.cve_incidente is not null and j.tipo_justificante is null' ;
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->row_array()['tot_dias_incidentes'] ?? null ;
    }

    public function get_tot_dias_habiles($mes, $anio)
    {
        $sql = 'select count(*) as tot_dias_habiles from dias_habiles(?,?)';
        $query = $this->db->query($sql, array($mes, $anio));
        return $query->row_array()['tot_dias_habiles'] ?? null ;
    }

    public function get_tot_dias_info($mes, $anio)
    {
        $sql = 'select count(distinct fecha) as tot_dias_info from asistencias where extract(month from fecha) = ? and extract(year from fecha) = ? ';
        $query = $this->db->query($sql, array($mes, $anio));
        return $query->row_array()['tot_dias_info'] ?? null ;
    }

}
