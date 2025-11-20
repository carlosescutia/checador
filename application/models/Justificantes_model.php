<?php
class Justificantes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_vacaciones_empleado($anio, $cve_empleado)
    {
        $sql = ""
            ."select  "
            ."j.cve_justificante, j.cve_empleado, j.fecha, j.tipo, 'ji' as tipo_justificante, j.detalle, j.fech_fin, num_dias_habiles_periodo(j.fecha, coalesce(j.fech_fin, j.fecha)) as dias, jp.anio, p.nom_periodo "
            ."from  "
            ."justificantes j "
            ."left join justificante_periodo jp on jp.cve_justificante = j.cve_justificante "
            ."left join periodos p on p.id_periodo = jp.id_periodo "
            ."where  "
            ."extract(year from j.fecha) = ? "
            ."and j.cve_empleado = ?  "
            ."and j.tipo = 'V'  "
            ."union "
            ."select  "
            ."jm.cve_justificante_masivo, ? as cve_empleado, jm.fecha, jm.tipo, 'jm' as tipo_justificante, jm.desc_justificante_masivo as detalle, jm.fech_fin, num_dias_habiles_periodo(jm.fecha, coalesce(jm.fech_fin, jm.fecha)) as dias, jmp.anio, p.nom_periodo "
            ."from  "
            ."justificantes_masivos jm "
            ."left join justificante_masivo_periodo jmp on jmp.cve_justificante_masivo = jm.cve_justificante_masivo "
            ."left join periodos p on p.id_periodo = jmp.id_periodo "
            ."where  "
            ."? between extract(year from jm.fecha) and extract(year from jm.fech_fin) and jm.cve_justificante_masivo not in (select distinct cve_justificante_masivo from justificante_masivo_empleados) "
            ."and jm.tipo = 'V'  "
            ."union "
            ."select  "
            ."jm2.cve_justificante_masivo, ? as cve_empleado, jm2.fecha, jm2.tipo, 'jm' as tipo_justificante, jm2.desc_justificante_masivo as detalle, jm2.fech_fin, num_dias_habiles_periodo(jm2.fecha, coalesce(jm2.fech_fin, jm2.fecha)) as dias, jmp.anio, p.nom_periodo "
            ."from  "
            ."justificantes_masivos jm2 "
            ."left join justificante_masivo_periodo jmp on jmp.cve_justificante_masivo = jm2.cve_justificante_masivo "
            ."left join periodos p on p.id_periodo = jmp.id_periodo "
            ."where  "
            ."? between extract(year from jm2.fecha) and extract(year from jm2.fech_fin) and ? in (select jme.cve_empleado from justificante_masivo_empleados jme where jme.cve_justificante_masivo = jm2.cve_justificante_masivo) "
            ."and jm2.tipo = 'V'  "
            ."order by  "
            ."fecha  "
            ."";
        $query = $this->db->query($sql, array($anio, $cve_empleado, $cve_empleado, $anio, $cve_empleado, $anio, $cve_empleado));
        return $query->result_array();
    }

    public function get_justificantes_empleado($mes, $anio, $cve_empleado)
    {
        $sql = ""
            ."select  "
            ."j.*, "
            ."num_dias_habiles_periodo(j.fecha, coalesce(j.fech_fin, j.fecha)) as dias, "
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
        $sql = ""
            ."select "
            ."j.*, jp.id_justificante_periodo, jp.id_periodo, jp.anio "
            ."from "
            ."justificantes j "
            ."left join justificante_periodo jp on jp.cve_justificante = j.cve_justificante "
            ."where "
            ."j.cve_justificante = ? "
            ."";
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
