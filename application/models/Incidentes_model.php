<?php
class Incidentes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_incidentes_empleados_todos($mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(i.incidente) as num_incidentes from incidentes(?,?,?) i left join empleados e on i.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario group by i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia));
        return $query->result_array();
    }

    public function get_incidentes_empleados_pendientes($mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(i.incidente) as num_incidentes from incidentes(?,?,?) i left join empleados e on i.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario where incidente is not null group by i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia));
        return $query->result_array();
    }

    public function get_incidentes_empleado($cve_empleado, $mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select ie.*, e.nom_empleado from incidentes(?, ?, ?) ie left join empleados e on ie.cve_empleado = e.cve_empleado where ie.cve_empleado = ?  order by ie.fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia, $cve_empleado));
        return $query->result_array();
    }

    public function get_incidentes_fechas($mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select fecha, count(incidente) as num_incidentes from incidentes(?,?,?) where incidente is not null group by fecha order by fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia));
        return $query->result_array();
    }

    public function get_incidentes_fecha($fecha, $mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario, count(i.incidente) as num_incidentes from incidentes(?,?,?) i left join empleados e on i.cve_empleado = e.cve_empleado left join horarios h on e.cve_horario = h.cve_horario where fecha = ? and incidente is not null group by i.cve_empleado, e.cod_empleado, e.nom_empleado, h.desc_horario order by e.nom_empleado';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia, $fecha));
        return $query->result_array();
    }


    public function get_tot_incidentes($mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select count(incidente) as tot_incidentes from incidentes(?,?,?)';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia));
        return $query->row_array()['tot_incidentes'] ?? null ;
    }

    public function get_tot_empleados_incidentes($mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select count(distinct(cve_empleado)) as tot_empleados_incidentes from incidentes(?,?,?) where incidente is not null';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia));
        return $query->row_array()['tot_empleados_incidentes'] ?? null ;
    }

    public function get_tot_dias_incidentes($mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select count(distinct(fecha)) as tot_dias_incidentes from incidentes(?,?,?) where incidente is not null';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia));
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
