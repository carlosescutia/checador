<?php
class Justificantes_masivos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_justificantes_masivos($mes, $anio) {
        $sql = ""
            ."select "
            ."jm.*, jmp.anio, p.nom_periodo "
            ."from "
            ."justificantes_masivos jm "
            ."left join justificante_masivo_periodo jmp on jmp.cve_justificante_masivo = jm.cve_justificante_masivo "
            ."left join periodos p on p.id_periodo = jmp.id_periodo "
            ."where "
            ."extract(month from jm.fecha)::varchar = ? "
            ."and extract(year from jm.fecha)::varchar = ? "
            ."order by "
            ."fecha "
            ."";
        $query = $this->db->query($sql, array($mes, $anio));
        return $query->result_array();
    }

    public function get_justificante_masivo($cve_justificante_masivo) {
        //$sql = 'select jm.* from justificantes_masivos jm where cve_justificante_masivo = ?;';
        $sql = ""
            ."select "
            ."jm.*, jmp.id_justificante_masivo_periodo, jmp.id_periodo, jmp.anio "
            ."from "
            ."justificantes_masivos jm "
            ."left join justificante_masivo_periodo jmp on jmp.cve_justificante_masivo = jm.cve_justificante_masivo "
            ."where "
            ."jm.cve_justificante_masivo = ? "
            ."";
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
