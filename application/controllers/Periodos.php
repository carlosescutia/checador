<?php
class Periodos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accesos_sistema_model');
        $this->load->model('opciones_sistema_model');
        $this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');
        $this->load->model('periodos_model');
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

    public function index()
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['periodos'] = $this->periodos_model->get_periodos();

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('catalogos/periodos/lista', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function detalle($id_periodo)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['periodo'] = $this->periodos_model->get_periodo($id_periodo);

            $this->load->view('templates/admheader', $data);
            $this->load->view('catalogos/periodos/detalle', $data);
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

            $this->load->view('templates/admheader', $data);
            $this->load->view('catalogos/periodos/nuevo', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function guardar($id_periodo=null)
    {
        if ($this->session->userdata('logueado')) {

            $nuevo_periodo = is_null($id_periodo);

            $periodo = $this->input->post();
            if ($periodo) {

                if ($id_periodo) {
                    $accion = 'modificó';
                } else {
                    $accion = 'agregó';
                }

                // guardado
                $data = array(
                    'nom_periodo' => $periodo['nom_periodo'],
                    'orden' => $periodo['orden'],
                );
                $id_periodo = $this->periodos_model->guardar($data, $id_periodo);

                // registro en bitacora
                $separador = ' -> ';
                $usuario = $this->session->userdata('usuario');
                $nom_usuario = $this->session->userdata('nom_usuario');
                $nom_organizacion = $this->session->userdata('nom_organizacion');
                $entidad = 'periodos';
                $valor = $id_periodo . " " . $periodo['nom_periodo'];
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

            redirect('periodos');

        } else {
            redirect('admin/login');
        }
    }

    public function eliminar($id_periodo)
    {
        if ($this->session->userdata('logueado')) {

            // registro en bitacora
            $periodo = $this->periodos_model->get_periodo($id_periodo);
            $separador = ' -> ';
            $usuario = $this->session->userdata('usuario');
            $nom_usuario = $this->session->userdata('nom_usuario');
            $nom_organizacion = $this->session->userdata('nom_organizacion');
            $accion = 'eliminó';
            $entidad = 'periodos';
            $valor = $id_periodo . " " . $periodo['nom_periodo'];
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
            $this->periodos_model->eliminar($id_periodo);

            redirect('periodos');

        } else {
            redirect('admin/login');
        }
    }

}
