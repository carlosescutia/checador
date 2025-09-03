<?php
class Justificantes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_vacaciones_empleado($anio, $cve_empleado)
    {
        $sql = "select * from justificantes where extract(year from fecha) = ? and cve_empleado = ? and tipo = 'V' order by fecha ";
        $query = $this->db->query($sql, array($anio, $cve_empleado));
        return $query->result_array();
    }

    public function get_justificantes_empleado($mes, $anio, $cve_empleado)
    {
        $sql = ""
            ."select  "
            ."j.*, "
            ."e.nom_eventualidad "
            ."from  "
            ."justificantes j "
            ."left join eventualidades e on e.cve_eventualidad = j.cve_eventualidad "
            ."where  "
            ."extract(month from j.fecha) = ?  "
            ."and extract(year from j.fecha) = ?  "
            ."and j.cve_empleado = ?  "
            ."and j.tipo in ('E','S','D')  "
            ."order  "
            ."by j.fecha  "
            ."";
        $query = $this->db->query($sql, array($mes, $anio, $cve_empleado));
        return $query->result_array();
    }

    public function get_justificante($cve_justificante)
    {
        $sql = 'select * from justificantes where cve_justificante = ?';
        $query = $this->db->query($sql, array($cve_justificante));
        return $query->row_array();
    }

    public function guardar($data, $cve_justificante)
    {
        if ($cve_justificante) {
            $this->db->where('cve_justificante', $cve_justificante);
            $this->db->update('justificantes', $data);
            $id = $cve_justificante;
        } else {
            $this->db->insert('justificantes', $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function eliminar($cve_justificante)
    {
        $this->db->where('cve_justificante', $cve_justificante);
        $result = $this->db->delete('justificantes');
    }

}
