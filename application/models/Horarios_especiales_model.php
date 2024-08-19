<?php
class Horarios_especiales_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_horarios_especiales() {
        $sql = 'select he.*, e.nom_empleado, h.desc_horario from horarios_especiales he left join empleados e on e.cve_empleado = he.cve_empleado left join horarios h on h.cve_horario = e.cve_horario order by he.id_horario_especial';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_horario_especial($id_horario_especial) {
        $sql = 'select he.*, h.desc_horario from horarios_especiales he left join empleados e on e.cve_empleado = he.cve_empleado left join horarios h on h.cve_horario = e.cve_horario where he.id_horario_especial = ?;';
        $query = $this->db->query($sql, array($id_horario_especial));
        return $query->row_array();
    }

    public function guardar($data, $id_horario_especial)
    {
        if ($id_horario_especial) {
            $this->db->where('id_horario_especial', $id_horario_especial);
            $this->db->update('horarios_especiales', $data);
            $id = $id_horario_especial;
        } else {
            $this->db->insert('horarios_especiales', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($id_horario_especial)
    {
        $this->db->where('id_horario_especial', $id_horario_especial);
        $result = $this->db->delete('horarios_especiales');
    }

}
