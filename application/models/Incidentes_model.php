<?php
class Incidentes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_semana_inglesa($mes, $year)
    {
        // obtiene los dias de lunes a viernes de un mes y año
        $resultado = [];
        for ($dia = 1 ; $dia <= 31; $dia++) {
            $fechastr = $year .'-'. $mes .'-'. $dia;
            $fecha = strtotime($fechastr);
            if ( (date('m', $fecha) == $mes) and (date("w",$fecha) !== "0") and date("w",$fecha) !== "6" ) {
                $resultado[] = $fechastr;
            }
        }
        return $resultado;
    }

    public function get_dias_habiles($dias, $mes, $year)
    {
    // le resta dias_inhabiles a los dias del mes

        $sql = 'select fecha from dias_inhabiles';
        $query = $this->db->query($sql);
        $datos = $query->result_array();
        $dias_inhabiles = [];
        foreach ($datos as $dato) {
            $dias_inhabiles[] = $dato['fecha'];
        }

        $resultado = array();
        foreach ($dias as $dia) {
            if ( !in_array($dia, $dias_inhabiles) ) {
                $resultado[] = $dia;
            }
        }
        return $resultado;

    }

}
