<?php
class Asistencias_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_asistencias() {
        $sql = 'select e.nom_empleado, a.* from asistencias a left join empleados e on a.cve_empleado = e.cve_empleado ';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function existe($data)
    {
        $sql = 'select cve_asistencia from asistencias where cve_empleado = ? and fecha = ? and hora = ? ';
        $query = $this->db->query($sql, array($data['cve_empleado'], $data['fecha'], $data['hora']));
        return $query->num_rows();
    }

    public function get_tot_asistencias()
    {
        $sql = 'select count(*) as tot_asistencias from asistencias ;';
        $query = $this->db->query($sql);
        return $query->row_array()['tot_asistencias'] ?? null ;
    }

    public function get_asistencia_antigua()
    {
        $sql = "select min(fecha::text || ' - ' || hora::text) as asistencia_antigua from asistencias";
        $query = $this->db->query($sql);
        return $query->row_array()['asistencia_antigua'] ?? null ;
    }

    public function get_asistencia_reciente()
    {
        $sql = "select max(fecha::text || ' - ' || hora::text) as asistencia_reciente from asistencias";
        $query = $this->db->query($sql);
        return $query->row_array()['asistencia_reciente'] ?? null ;
    }


    public function guardar($data)
    {
        if ( !$this->existe($data) ) {
            $this->db->insert('asistencias', $data);
        }
    }

}
