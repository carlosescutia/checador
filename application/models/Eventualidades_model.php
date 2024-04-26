<?php
class Eventualidades_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_eventualidades() {
        $sql = 'select e.* from eventualidades e order by e.nom_eventualidad';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_eventualidad($cve_eventualidad) {
        $sql = 'select e.* from eventualidades e where cve_eventualidad = ?;';
        $query = $this->db->query($sql, array($cve_eventualidad));
        return $query->row_array();
    }

    public function guardar($data, $cve_eventualidad)
    {
        if ($cve_eventualidad) {
            $this->db->where('cve_eventualidad', $cve_eventualidad);
            $this->db->update('eventualidades', $data);
            $id = $cve_eventualidad;
        } else {
            $this->db->insert('eventualidades', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($cve_eventualidad)
    {
        $this->db->where('cve_eventualidad', $cve_eventualidad);
        $result = $this->db->delete('eventualidades');
    }

}
