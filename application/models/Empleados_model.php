<?php
class Empleados_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_empleados() {
        $sql = 'select e.*, h.desc_horario from empleados e left join horarios h on e.cve_horario = h.cve_horario order by e.nom_empleado;';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_empleados_activos() {
        $sql = 'select e.*, h.desc_horario from empleados e left join horarios h on e.cve_horario = h.cve_horario where e.activo = 1 order by e.nom_empleado;';
        $query = $this->db->query($sql);
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

    public function get_horas_a_cubrir_empleado($mes, $anio, $cve_empleado) {
        $sql = ""
            ."select count(*) * 8 as horas_a_cubrir_empleado from dias_habiles(?,?)  "
            ."where  "
            ."fecha not in (select fecha from dias_inhabiles) "
            ."and fecha not in "
            ."( "
            ."select (generate_series(fecha, coalesce(fech_fin, fecha), interval '1' day)) as fecha_jus from justificantes_masivos where tipo in ('D','V') and cve_justificante_masivo not in (select distinct cve_justificante_masivo from justificante_masivo_empleados)  "
            ."union "
            ."select (generate_series(jm2.fecha, coalesce(jm2.fech_fin, jm2.fecha), interval '1' day)) as fecha_jus from justificantes_masivos jm2 where tipo in ('D','V') and ? in (select tmp_jm.cve_empleado from justificante_masivo_empleados tmp_jm where tmp_jm.cve_justificante_masivo = jm2.cve_justificante_masivo) "
            ."union "
            ."select (generate_series(fecha, coalesce(fech_fin, fecha), interval '1' day)) as fecha_jus from justificantes where tipo in ('D','V') and cve_empleado = ? "
            .") "
            ."";
        $query = $this->db->query($sql, array($mes, $anio, $cve_empleado, $cve_empleado));
        return $query->row_array()['horas_a_cubrir_empleado'] ?? null ;
    }

    public function get_horas_trabajadas_empleado($mes, $anio, $cve_empleado) {
        $sql = ""
            ."select extract(hour from sum(hora_salida - hora_entrada)) as horas_trabajadas_empleado from asistencias(?,?) where cve_empleado = ? "
            ."";
        $query = $this->db->query($sql, array($mes, $anio, $cve_empleado));
        return $query->row_array()['horas_trabajadas_empleado'] ?? null ;
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
