<?php
class Justificante_masivo_empleados_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_empleados_justificante_masivo($cve_justificante_masivo) {
        $sql = "select string_agg(cve_empleado::text, ',') as cve_empleado from justificante_masivo_empleados jme where jme.cve_justificante_masivo = ?" ;
        $query = $this->db->query($sql, array($cve_justificante_masivo));
        return $query->row_array();
    }

    public function guardar($data)
    {
        $this->db->insert('justificante_masivo_empleados', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function eliminar($cve_justificante_masivo)
    {
        $this->db->where('cve_justificante_masivo', $cve_justificante_masivo);
        $result = $this->db->delete('justificante_masivo_empleados');
    }

}

