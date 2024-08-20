<?php
class Horarios_especiales extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accesos_sistema_model');
        $this->load->model('opciones_sistema_model');
        $this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');

        $this->load->model('horarios_especiales_model');
        $this->load->model('horarios_especiales_dias_model');
        $this->load->model('empleados_model');
        $this->load->model('horarios_model');
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

            $data['horarios_especiales'] = $this->horarios_especiales_model->get_horarios_especiales();

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('catalogos/horarios_especiales/lista', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function detalle($id_horario_especial)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['horario_especial'] = $this->horarios_especiales_model->get_horario_especial($id_horario_especial);
            $data['empleados'] = $this->empleados_model->get_empleados_activos();
            $data['horario_especial_dias'] = $this->horarios_especiales_dias_model->get_horario_especial_dias($id_horario_especial);
            $data['horarios'] = $this->horarios_model->get_horarios();

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('catalogos/horarios_especiales/detalle', $data);
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

            $this->load->view('templates/admheader', $data);
            $this->load->view('catalogos/horarios_especiales/nuevo', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function guardar($id_horario_especial=null)
    {
        if ($this->session->userdata('logueado')) {

            $horario_especial = $this->input->post();
            if ($horario_especial) {

                if ($horario_especial['id_horario_especial']) {
                    $accion = 'modificó';
                } else {
                    $accion = 'agregó';
                }

                // guardado
                $data = array(
                    'cve_empleado' => $horario_especial['cve_empleado'],
                    'nom_horario_especial' => $horario_especial['nom_horario_especial'],
                    'fech_ini' => $horario_especial['fech_ini'],
                    'fech_fin' => $horario_especial['fech_fin'],
                );
                $id_horario_especial = $this->horarios_especiales_model->guardar($data, $horario_especial['id_horario_especial']);
                
                // registro en bitacora
				$separador = ' -> ';
				$usuario = $this->session->userdata('usuario');
				$nom_usuario = $this->session->userdata('nom_usuario');
				$nom_organizacion = $this->session->userdata('nom_organizacion');
				$entidad = 'horarios_especiales';
                $valor = $id_horario_especial . " " . $horario_especial['nom_horario_especial'];
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

            redirect('horarios_especiales');

        } else {
            redirect('admin/login');
        }
    }

    public function eliminar($id_horario_especial)
    {
        if ($this->session->userdata('logueado')) {

            // registro en bitacora
            $horario_especial = $this->horarios_especiales_model->get_horario_especial($id_horario_especial);
            $separador = ' -> ';
            $usuario = $this->session->userdata('usuario');
            $nom_usuario = $this->session->userdata('nom_usuario');
            $nom_organizacion = $this->session->userdata('nom_organizacion');
            $accion = 'eliminó';
            $entidad = 'horarios_especiales';
            $valor = $id_horario_especial . " " . $horario_especial['nom_horario_especial'];
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

            // eliminar horarios_especiales_dias relacionados
            $this->horarios_especiales_dias_model->eliminar($id_horario_especial);

            // eliminado
            $this->horarios_especiales_model->eliminar($id_horario_especial);

            redirect('horarios_especiales');

        } else {
            redirect('admin/login');
        }
    }

}
