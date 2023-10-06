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

    public function guardar($data)
    {
        $this->db->insert('asistencias', $data);
    }

}
