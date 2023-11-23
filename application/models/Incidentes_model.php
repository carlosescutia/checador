<?php
class Incidentes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_lista_incidentes_empleados_todos($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(i.incidente) as num_incidentes from incidentes(?,?,?,?) i left join empleados e on i.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario group by i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_lista_incidentes_empleados_pendientes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(i.incidente) as num_incidentes from incidentes(?,?,?,?) i left join empleados e on i.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario where incidente is not null group by i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_incidentes_empleado($cve_empleado, $mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select i.*, j.cve_justificante, j.tipo as tipo_justificante, jm.cve_justificante_masivo, jm.tipo as tipo_justificante_masivo from incidentes(?,?,?,?) i left join justificantes j on i.fecha = j.fecha and i.cve_empleado = j.cve_empleado left join justificantes_masivos jm on i.fecha = jm.fecha where i.cve_empleado = ? order by fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia, $cve_empleado));
        return $query->result_array();
    }

    public function get_incidentes_empleados($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select i.*, j.cve_justificante, j.tipo as tipo_justificante, jm.cve_justificante_masivo, jm.tipo as tipo_justificante_masivo from incidentes(?,?,?,?) i left join justificantes j on i.fecha = j.fecha and i.cve_empleado = j.cve_empleado left join justificantes_masivos jm on i.fecha = jm.fecha order by fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_lista_incidentes_fechas($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select fecha, count(incidente) as num_incidentes from incidentes(?,?,?,?) where incidente is not null group by fecha order by fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->result_array();
    }

    public function get_incidentes_fecha($fecha, $mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(i.incidente) as num_incidentes from incidentes(?,?,?,?) i left join empleados e on i.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario where fecha = ? and incidente is not null group by i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia, $fecha));
        return $query->result_array();
    }


    public function get_tot_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select count(incidente) as tot_incidentes from incidentes(?,?,?,?)';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->row_array()['tot_incidentes'] ?? null ;
    }

    public function get_tot_empleados_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select count(distinct(cve_empleado)) as tot_empleados_incidentes from incidentes(?,?,?,?) where incidente is not null';
        $query = $this->db->query($sql, array($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia));
        return $query->row_array()['tot_empleados_incidentes'] ?? null ;
    }

    public function get_tot_dias_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia)
    {
        $sql = 'select count(distinct(fecha)) as tot_dias_incidentes from incidentes(?,?,?,?) where incidente is not null';
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
