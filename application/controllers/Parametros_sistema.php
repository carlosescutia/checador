<?php
class Parametros_sistema extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('organizaciones_model');
        $this->load->model('accesos_sistema_model');
        $this->load->model('opciones_sistema_model');
        $this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');
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

            $data['parametros_sistema'] = $this->parametros_sistema_model->get_parametros_sistema();

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('catalogos/parametros_sistema/lista', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function detalle($cve_parametro_sistema)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['parametros_sistema'] = $this->parametros_sistema_model->get_parametro_sistema_cve($cve_parametro_sistema);

            $this->load->view('templates/admheader', $data);
            $this->load->view('catalogos/parametros_sistema/detalle', $data);
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
            $this->load->view('catalogos/parametros_sistema/nuevo', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function guardar($cve_parametro_sistema=null)
    {
        if ($this->session->userdata('logueado')) {

            $nueva_parametro_sistema = is_null($cve_parametro_sistema);

            $parametros_sistema = $this->input->post();
            if ($parametros_sistema) {

                if ($cve_parametro_sistema) {
                    $accion = 'modificó';
                } else {
                    $accion = 'agregó';
                }

                // guardado
                $data = array(
                    'nom_parametro_sistema' => $parametros_sistema['nom_parametro_sistema'],
                    'valor_parametro_sistema' => $parametros_sistema['valor_parametro_sistema'],
                );
                $cve_parametro_sistema = $this->parametros_sistema_model->guardar($data, $cve_parametro_sistema);
                
                // registro en bitacora
				$separador = ' -> ';
				$usuario = $this->session->userdata('usuario');
				$nom_usuario = $this->session->userdata('nom_usuario');
				$nom_organizacion = $this->session->userdata('nom_organizacion');
				$entidad = 'parametros_sistema';
                $valor = $cve_parametro_sistema . " " . $parametros_sistema['nom_parametro_sistema'];
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

            redirect('parametros_sistema');

        } else {
            redirect('admin/login');
        }
    }

    public function eliminar($cve_parametro_sistema)
    {
        if ($this->session->userdata('logueado')) {

            // registro en bitacora
            $parametro_sistema = $this->parametros_sistema_model->get_parametro_sistema_cve($cve_parametro_sistema);
            $separador = ' -> ';
            $usuario = $this->session->userdata('usuario');
            $nom_usuario = $this->session->userdata('nom_usuario');
            $nom_organizacion = $this->session->userdata('nom_organizacion');
            $accion = 'eliminó';
            $entidad = 'parametros_sistema';
            $valor = $cve_parametro_sistema . " " . $parametro_sistema['nom_parametro_sistema'];
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
            $this->parametros_sistema_model->eliminar($cve_parametro_sistema);

            redirect('parametros_sistema');

        } else {
            redirect('admin/login');
        }
    }

}
