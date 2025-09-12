<?php
class Periodos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_periodos() {
        $sql = 'select e.* from periodos e order by e.orden';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_periodo($id_periodo) {
        $sql = 'select e.* from periodos e where id_periodo = ?;';
        $query = $this->db->query($sql, array($id_periodo));
        return $query->row_array();
    }

    public function guardar($data, $id_periodo)
    {
        if ($id_periodo) {
            $this->db->where('id_periodo', $id_periodo);
            $this->db->update('periodos', $data);
            $id = $id_periodo;
        } else {
            $this->db->insert('periodos', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($id_periodo)
    {
        $this->db->where('id_periodo', $id_periodo);
        $result = $this->db->delete('periodos');
    }

}
