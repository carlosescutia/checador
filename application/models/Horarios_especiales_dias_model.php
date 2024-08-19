<?php
class Horarios_especiales_dias_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_horario_especial_dias($id_horario_especial) {
        $sql = 'select hed.*, h.desc_horario from horarios_especiales_dias hed left join horarios h on h.cve_horario = hed.cve_horario where hed.id_horario_especial = ? order by cve_dia';
        $query = $this->db->query($sql, array($id_horario_especial));
        return $query->result_array();
    }

    public function guardar($data)
    {
        $this->db->insert('horarios_especiales_dias', $data);
    }

    public function eliminar($id_horario_especial, $cve_dia)
    {
        $this->db->where('id_horario_especial', $id_horario_especial);
        $this->db->where('cve_dia', $cve_dia);
        $result = $this->db->delete('horarios_especiales_dias');
    }

}
