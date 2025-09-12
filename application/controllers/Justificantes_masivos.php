<?php
class Justificantes_masivos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accesos_sistema_model');
        $this->load->model('opciones_sistema_model');
        $this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');
        $this->load->model('justificantes_masivos_model');
        $this->load->model('empleados_model');
        $this->load->model('justificante_masivo_empleados_model');
        $this->load->model('periodos_model');
        $this->load->model('justificante_masivo_periodo_model');
    }

    public function get_userdata()
    {
        $cve_rol = $this->session->userdata('cve_rol');
        $data['cve_usuario'] = $this->session->userdata('cve_usuario');
        $data['cve_organizacion'] = $this->session->userdata('cve_organizacion');
        $data['nom_organizacion'] = $this->session->userdata('nom_organizacion');
        $data['cve_rol'] = $cve_rol;
        $data['nom_usuario'] = $this->session->userdata('nom_usuario');
        $data['error'] = $this->session->flashdata('error');
        $data['accesos_sistema_rol'] = explode(',', $this->accesos_sistema_model->get_accesos_sistema_rol($cve_rol)['accesos']);
        $data['opciones_sistema'] = $this->opciones_sistema_model->get_opciones_sistema();
        return $data;
    }

    public function get_system_params()
    {
        $data['nom_sitio_corto'] = $this->parametros_sistema_model->get_parametro_sistema_nom('nom_sitio_corto');
        $data['nom_sitio_largo'] = $this->parametros_sistema_model->get_parametro_sistema_nom('nom_sitio_largo');
        $data['nom_org_sitio'] = $this->parametros_sistema_model->get_parametro_sistema_nom('nom_org_sitio');
        $data['anio_org_sitio'] = $this->parametros_sistema_model->get_parametro_sistema_nom('anio_org_sitio');
        $data['tel_org_sitio'] = $this->parametros_sistema_model->get_parametro_sistema_nom('tel_org_sitio');
        $data['correo_org_sitio'] = $this->parametros_sistema_model->get_parametro_sistema_nom('correo_org_sitio');
        $data['logo_org_sitio'] = $this->parametros_sistema_model->get_parametro_sistema_nom('logo_org_sitio');
        return $data;
    }

    public function detalle($cve_justificante_masivo)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['justificante_masivo'] = $this->justificantes_masivos_model->get_justificante_masivo($cve_justificante_masivo);
            $data['empleados'] = $this->empleados_model->get_empleados_activos();
            $data['empleados_justificante_masivo'] = explode(',', $this->justificante_masivo_empleados_model->get_empleados_justificante_masivo($cve_justificante_masivo)['cve_empleado']);
            $data['periodos'] = $this->periodos_model->get_periodos();

            $this->load->view('templates/admheader', $data);
            $this->load->view('justificantes_masivos/detalle', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function nuevo()
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['empleados'] = $this->empleados_model->get_empleados_activos();
            $data['periodos'] = $this->periodos_model->get_periodos();

            $this->load->view('templates/admheader', $data);
            $this->load->view('justificantes_masivos/nuevo', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function guardar($cve_justificante_masivo=null)
    {
        if ($this->session->userdata('logueado')) {

            $nuevo_justificante_masivo = is_null($cve_justificante_masivo);

            $justificante_masivo = $this->input->post();
            if ($justificante_masivo) {

                // lista de empleados
                $emps = [];
                foreach ($justificante_masivo as $key => $value) {
                    if (substr($key, 0, 3) == 'chk') {
                        $emps[] = substr($key, 3, 99);
                    }
                }
                $num_emps = sizeof($emps);

                if ($cve_justificante_masivo) {
                    $accion = 'modificó';
                } else {
                    $accion = 'agregó';
                }

                // guardado
                $data = array(
                    'fecha' => $justificante_masivo['fecha'],
                    'fech_fin' => empty($justificante_masivo['fech_fin']) ? null : $justificante_masivo['fech_fin'],
                    'desc_justificante_masivo' => $justificante_masivo['desc_justificante_masivo'],
                    'tipo' => $justificante_masivo['tipo'],
                );
                $cve_justificante_masivo = $this->justificantes_masivos_model->guardar($data, $cve_justificante_masivo);

                // borrado de empleados del justificante masivo
                $this->justificante_masivo_empleados_model->eliminar($cve_justificante_masivo);

                // guardado de empleados del justificante masivo
                foreach ($emps as $emps_item) {
                    $data = [
                        "cve_justificante_masivo" => $cve_justificante_masivo,
                        "cve_empleado" => $emps_item
                    ];
                    $this->justificante_masivo_empleados_model->guardar($data);
                }

                // guardado de periodo
                $id_justificante_masivo_periodo = $justificante_masivo['id_justificante_masivo_periodo'];
                $tipo = $justificante_masivo['tipo'];
                $id_periodo = $justificante_masivo['id_periodo'];
                $anio = $justificante_masivo['anio'];
                if ($tipo == 'V' and $id_periodo and $anio) {
                    $data = [
                        "cve_justificante_masivo" => $cve_justificante_masivo,
                        "id_periodo" => $justificante_masivo['id_periodo'],
                        "anio" => $anio,
                    ];
                    $this->justificante_masivo_periodo_model->guardar($data, $id_justificante_masivo_periodo);
                }

                // registro en bitacora
                $separador = ' -> ';
                $usuario = $this->session->userdata('usuario');
                $nom_usuario = $this->session->userdata('nom_usuario');
                $nom_organizacion = $this->session->userdata('nom_organizacion');
                $entidad = 'justificantes_masivos';
                $valor = $cve_justificante_masivo . " " . $justificante_masivo['fecha'];
                $data = array(
                    'fecha' => date("Y-m-d"),
                    'hora' => date("H:i"),
                    'origen' => $_SERVER['REMOTE_ADDR'],
                    'usuario' => $usuario,
                    'nom_usuario' => $nom_usuario,
                    'nom_organizacion' => $nom_organizacion,
                    'accion' => $accion,
                    'entidad' => $entidad,
                    'valor' => $valor
                );
                $this->bitacora_model->guardar($data);

            }

            $url_padre = $this->session->userdata('url_padre');
            redirect($url_padre);

        } else {
            redirect('admin/login');
        }
    }

    public function eliminar($cve_justificante_masivo)
    {
        if ($this->session->userdata('logueado')) {

            // registro en bitacora
            $justificante_masivo = $this->justificantes_masivos_model->get_justificante_masivo($cve_justificante_masivo);
            $separador = ' -> ';
            $usuario = $this->session->userdata('usuario');
            $nom_usuario = $this->session->userdata('nom_usuario');
            $nom_organizacion = $this->session->userdata('nom_organizacion');
            $accion = 'eliminó';
            $entidad = 'justificantes_masivos';
            $valor = $cve_justificante_masivo . " " . $justificante_masivo['fecha'];
            $data = array(
                'fecha' => date("Y-m-d"),
                'hora' => date("H:i"),
                'origen' => $_SERVER['REMOTE_ADDR'],
                'usuario' => $usuario,
                'nom_usuario' => $nom_usuario,
                'nom_organizacion' => $nom_organizacion,
                'accion' => $accion,
                'entidad' => $entidad,
                'valor' => $valor
            );
            $this->bitacora_model->guardar($data);

            // eliminado
            $this->justificantes_masivos_model->eliminar($cve_justificante_masivo);

            $url_padre = $this->session->userdata('url_padre');
            redirect($url_padre);

        } else {
            redirect('admin/login');
        }
    }

}
