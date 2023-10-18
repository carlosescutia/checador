<?php
class Incidentes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_incidentes_empleado($cve_empleado, $mes, $anio, $tiempo_tolerancia)
    {
        $sql = 'select ie.*, e.nom_empleado from incidentes(?, ?, ?) ie left join empleados e on ie.cve_empleado = e.cve_empleado where ie.cve_empleado = ?  order by ie.fecha';
        $query = $this->db->query($sql, array($mes, $anio, $tiempo_tolerancia, $cve_empleado));
        return $query->result_array();
    }

}
