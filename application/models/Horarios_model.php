<?php
class Horarios_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_horarios() {
        $sql = 'select h.* from horarios h order by h.cve_horario';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_horario($cve_horario) {
        $sql = 'select h.* from horarios h where cve_horario = ?;';
        $query = $this->db->query($sql, array($cve_horario));
        return $query->row_array();
    }

    public function guardar($data, $cve_horario)
    {
        if ($cve_horario) {
            $this->db->where('cve_horario', $cve_horario);
            $this->db->update('horarios', $data);
            $id = $cve_horario;
        } else {
            $this->db->insert('horarios', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($cve_horario)
    {
        $this->db->where('cve_horario', $cve_horario);
        $result = $this->db->delete('horarios');
    }

}
