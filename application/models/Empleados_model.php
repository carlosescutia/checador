<?php
class Empleados_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_empleados_status($activo) {
        $sql = 'select e.*, h.desc_horario from empleados e left join horarios h on e.cve_horario = h.cve_horario where activo = ? order by e.nom_empleado;';
        $query = $this->db->query($sql, array($activo));
        return $query->result_array();
    }

    public function get_empleado($cve_empleado) {
        $sql = 'select * from empleados where cve_empleado = ?;';
        $query = $this->db->query($sql, array($cve_empleado));
        return $query->row_array();
    }

    public function get_num_empleados($activo) {
        $sql = 'select count(*) as num_empleados from empleados';

        $parametros = array();
        if ($activo <> "") {
            $sql .= ' where activo = ?';
            array_push($parametros, "$activo");
        } 
        $query = $this->db->query($sql, $parametros);
        return $query->row_array()['num_empleados'] ?? null ;
    }

    public function guardar($data, $cve_empleado)
    {
        if ($cve_empleado) {
            $this->db->where('cve_empleado', $cve_empleado);
            $this->db->update('empleados', $data);
            $id = $cve_empleado;
        } else {
            $this->db->insert('empleados', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($cve_empleado)
    {
        $this->db->where('cve_empleado', $cve_empleado);
        $result = $this->db->delete('empleados');
    }

}
