<?php
class Justificantes_masivos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_justificantes_masivos() {
        $sql = 'select jm.* from justificantes_masivos jm order by fecha';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_justificante_masivo($cve_justificante_masivo) {
        $sql = 'select jm.* from justificantes_masivos jm where cve_justificante_masivo = ?;';
        $query = $this->db->query($sql, array($cve_justificante_masivo));
        return $query->row_array();
    }

    public function guardar($data, $cve_justificante_masivo)
    {
        if ($cve_justificante_masivo) {
            $this->db->where('cve_justificante_masivo', $cve_justificante_masivo);
            $this->db->update('justificantes_masivos', $data);
            $id = $cve_justificante_masivo;
        } else {
            $this->db->insert('justificantes_masivos', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($cve_justificante_masivo)
    {
        $this->db->where('cve_justificante_masivo', $cve_justificante_masivo);
        $result = $this->db->delete('justificantes_masivos');
    }

}
