<?php
class Dias_inhabiles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_dias_inhabiles() {
        $sql = 'select dh.* from dias_inhabiles dh order by dh.fecha';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_dia_inhabil($cve_dia_inhabil) {
        $sql = 'select dh.* from dias_inhabiles dh where cve_dia_inhabil = ?;';
        $query = $this->db->query($sql, array($cve_dia_inhabil));
        return $query->row_array();
    }

    public function guardar($data, $cve_dia_inhabil)
    {
        if ($cve_dia_inhabil) {
            $this->db->where('cve_dia_inhabil', $cve_dia_inhabil);
            $this->db->update('dias_inhabiles', $data);
            $id = $cve_dia_inhabil;
        } else {
            $this->db->insert('dias_inhabiles', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($cve_dia_inhabil)
    {
        $this->db->where('cve_dia_inhabil', $cve_dia_inhabil);
        $result = $this->db->delete('dias_inhabiles');
    }

}
