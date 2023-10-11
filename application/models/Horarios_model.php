<?php
class Horarios_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_horarios() {
        $sql = 'select h.* from horarios h order by h.cve_horario;';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
