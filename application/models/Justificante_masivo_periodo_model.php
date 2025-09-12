<?php
class Justificante_masivo_periodo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function guardar($data, $id_justificante_masivo_periodo)
    {
        if ($id_justificante_masivo_periodo) {
            $this->db->where('id_justificante_masivo_periodo', $id_justificante_masivo_periodo);
            $this->db->update('justificante_masivo_periodo', $data);
            $id = $id_justificante_masivo_periodo;
        } else {
            $this->db->insert('justificante_masivo_periodo', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($id_justificante_masivo_periodo)
    {
        $this->db->where('id_justificante_masivo_periodo', $id_justificante_masivo_periodo);
        $result = $this->db->delete('justificante_masivo_periodo');
    }

}

