<?php
class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios_model');
        $this->load->model('accesos_sistema_model');
        $this->load->model('opciones_sistema_model');
        $this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');
        $this->load->model('empleados_model');
        $this->load->model('incidentes_model');
        $this->load->model('justificantes_model');
        $this->load->model('dias_inhabiles_model');
        $this->load->model('justificantes_masivos_model');
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

            $activo = "1";
            $data['tot_empleados_activos'] = $this->empleados_model->get_num_empleados($activo);


            $filtros = $this->input->post();
            if ($filtros) {

                $mes = $filtros['mes'];
                $anio = $filtros['anio'];
                $filtros_proyectos = array(
                    'mes' => $mes,
                    'anio' => $anio,
                );
                $this->session->set_userdata($filtros_proyectos);

            } else {
                if ($this->session->userdata('mes')) {
                    $mes = $this->session->userdata('mes');
                } else {
                    $mes = date('m');
                }
                if ($this->session->userdata('anio')) {
                    $anio = $this->session->userdata('anio');
                } else {
                    $anio = date('Y');
                }
			}
            $data['mes'] = $mes;
            $data['anio'] = $anio;

            $tolerancia_retardo = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_retardo');
            $tolerancia_asistencia = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_asistencia');
            $data['tot_incidentes'] = $this->incidentes_model->get_tot_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['tot_empleados_incidentes'] = $this->incidentes_model->get_tot_empleados_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['tot_dias_habiles'] = $this->incidentes_model->get_tot_dias_habiles($mes, $anio);
            $data['tot_dias_incidentes'] = $this->incidentes_model->get_tot_dias_incidentes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['tot_dias_info'] = $this->incidentes_model->get_tot_dias_info($mes, $anio);


            $this->load->view('templates/admheader', $data);
            $this->load->view('admin/inicio', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $this->login();
        }
    }

    public function empleados_activos()
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $filtros = $this->input->post();
            if ($filtros) {

                $mes = $filtros['mes'];
                $anio = $filtros['anio'];
                $filtros_proyectos = array(
                    'mes' => $mes,
                    'anio' => $anio,
                );
                $this->session->set_userdata($filtros_proyectos);

            } else {
                if ($this->session->userdata('mes')) {
                    $mes = $this->session->userdata('mes');
                } else {
                    $mes = date('m');
                }
                if ($this->session->userdata('anio')) {
                    $anio = $this->session->userdata('anio');
                } else {
                    $anio = date('Y');
                }
			}
            $data['mes'] = $mes;
            $data['anio'] = $anio;

            $tolerancia_retardo = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_retardo');
            $tolerancia_asistencia = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_asistencia');
            $data['incidentes_empleados'] = $this->incidentes_model->get_lista_incidentes_empleados_todos($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['dias_inhabiles'] = $this->dias_inhabiles_model->get_dias_inhabiles($anio);
            $data['justificantes_masivos'] = $this->justificantes_masivos_model->get_justificantes_masivos($mes, $anio);
            $data['titulo'] = 'Incidentes de empleados activos';

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('admin/empleados_lista', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function empleados_incidentes()
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $filtros = $this->input->post();
            if ($filtros) {

                $mes = $filtros['mes'];
                $anio = $filtros['anio'];
                $filtros_proyectos = array(
                    'mes' => $mes,
                    'anio' => $anio,
                );
                $this->session->set_userdata($filtros_proyectos);

            } else {
                if ($this->session->userdata('mes')) {
                    $mes = $this->session->userdata('mes');
                } else {
                    $mes = date('m');
                }
                if ($this->session->userdata('anio')) {
                    $anio = $this->session->userdata('anio');
                } else {
                    $anio = date('Y');
                }
			}
            $data['mes'] = $mes;
            $data['anio'] = $anio;

            $tolerancia_retardo = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_retardo');
            $tolerancia_asistencia = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_asistencia');
            $data['incidentes_empleados'] = $this->incidentes_model->get_lista_incidentes_empleados_pendientes($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['dias_inhabiles'] = $this->dias_inhabiles_model->get_dias_inhabiles($anio);
            $data['justificantes_masivos'] = $this->justificantes_masivos_model->get_justificantes_masivos($mes, $anio);
            $data['titulo'] = 'Incidentes por empleado';

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('admin/empleados_lista', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function empleados_detalle($cve_empleado)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['empleado'] = $this->empleados_model->get_empleado($cve_empleado);

            $filtros = $this->input->post();
            if ($filtros) {

                $mes = $filtros['mes'];
                $anio = $filtros['anio'];
                $filtros_proyectos = array(
                    'mes' => $mes,
                    'anio' => $anio,
                );
                $this->session->set_userdata($filtros_proyectos);

            } else {
                if ($this->session->userdata('mes')) {
                    $mes = $this->session->userdata('mes');
                } else {
                    $mes = date('m');
                }
                if ($this->session->userdata('anio')) {
                    $anio = $this->session->userdata('anio');
                } else {
                    $anio = date('Y');
                }
			}
            $data['mes'] = $mes;
            $data['anio'] = $anio;

            $tolerancia_retardo = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_retardo');
            $tolerancia_asistencia = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_asistencia');
            $data['incidentes_empleado'] = $this->incidentes_model->get_incidentes_empleado($cve_empleado, $mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['vacaciones_empleado'] = $this->justificantes_model->get_vacaciones_empleado($anio, $cve_empleado);
            $data['justificantes_empleado'] = $this->justificantes_model->get_justificantes_empleado($mes, $anio, $cve_empleado);

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('admin/empleados_detalle', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function fechas_incidentes()
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $filtros = $this->input->post();
            if ($filtros) {

                $mes = $filtros['mes'];
                $anio = $filtros['anio'];
                $filtros_proyectos = array(
                    'mes' => $mes,
                    'anio' => $anio,
                );
                $this->session->set_userdata($filtros_proyectos);

            } else {
                if ($this->session->userdata('mes')) {
                    $mes = $this->session->userdata('mes');
                } else {
                    $mes = date('m');
                }
                if ($this->session->userdata('anio')) {
                    $anio = $this->session->userdata('anio');
                } else {
                    $anio = date('Y');
                }
			}
            $data['mes'] = $mes;
            $data['anio'] = $anio;

            $tolerancia_retardo = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_retardo');
            $tolerancia_asistencia = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_asistencia');
            $data['incidentes_fechas'] = $this->incidentes_model->get_lista_incidentes_fechas($mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['dias_inhabiles'] = $this->dias_inhabiles_model->get_dias_inhabiles($anio);
            $data['justificantes_masivos'] = $this->justificantes_masivos_model->get_justificantes_masivos($mes, $anio);

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('admin/fechas_lista', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function fechas_detalle($fecha)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $filtros = $this->input->post();
            if ($filtros) {

                $mes = $filtros['mes'];
                $anio = $filtros['anio'];
                $filtros_proyectos = array(
                    'mes' => $mes,
                    'anio' => $anio,
                );
                $this->session->set_userdata($filtros_proyectos);

            } else {
                if ($this->session->userdata('mes')) {
                    $mes = $this->session->userdata('mes');
                } else {
                    $mes = date('m');
                }
                if ($this->session->userdata('anio')) {
                    $anio = $this->session->userdata('anio');
                } else {
                    $anio = date('Y');
                }
			}
            $data['mes'] = $mes;
            $data['anio'] = $anio;

            $tolerancia_retardo = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_retardo');
            $tolerancia_asistencia = $this->parametros_sistema_model->get_parametro_sistema_nom('tolerancia_asistencia');
            $data['incidentes_empleados'] = $this->incidentes_model->get_incidentes_fecha($fecha, $mes, $anio, $tolerancia_retardo, $tolerancia_asistencia);
            $data['dias_inhabiles'] = $this->dias_inhabiles_model->get_dias_inhabiles($anio);
            $data['justificantes_masivos'] = $this->justificantes_masivos_model->get_justificantes_masivos($mes, $anio);
            $data['titulo'] = 'Incidentes del día ' . date('d/m/Y', strtotime($fecha));

            $this->load->view('templates/admheader', $data);
            $this->load->view('templates/dlg_borrar');
            $this->load->view('admin/fechas_detalle', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }


    public function login() {
        $this->session->sess_destroy();
        $data = array();
        $data['error'] = $this->session->flashdata('error');
        $data += $this->get_system_params();

        $this->load->view('admin/login', $data);
    }

    public function cerrar_sesion() {
        $usuario_data = array(
            'logueado' => FALSE
        );
        $usuario = $this->session->userdata('usuario');
        $nom_usuario = $this->session->userdata('nom_usuario');
        $cve_organizacion = $this->session->userdata('cve_organizacion');
        $nom_organizacion = $this->session->userdata('nom_organizacion');
        $data = array(
            'fecha' => date("Y-m-d"),
            'hora' => date("H:i"),
            'origen' => $_SERVER['REMOTE_ADDR'],
            'usuario' => $usuario,
            'nom_usuario' => $nom_usuario,
            'nom_organizacion' => $nom_organizacion,
            'accion' => 'logout',
            'entidad' => '',
            'valor' => ''
        );
        $this->bitacora_model->guardar($data);
        $this->session->set_userdata($usuario_data);
        redirect('admin/login');
    }

    public function post_login() {
        if ($this->input->post()) {
            $usuario = $this->input->post('usuario');
            $password = $this->input->post('password');
            $usuario_db = $this->usuarios_model->usuario_por_nombre($usuario, $password);
            if ($usuario_db) {
                $usuario_data = array(
                    'cve_usuario' => $usuario_db->cve_usuario,
                    'cve_organizacion' => $usuario_db->cve_organizacion,
                    'nom_organizacion' => $usuario_db->nom_organizacion,
                    'cve_rol' => $usuario_db->cve_rol,
                    'nom_usuario' => $usuario_db->nom_usuario,
                    'usuario' => $usuario_db->usuario,
                    'logueado' => TRUE
                );
                $this->session->set_userdata($usuario_data);
                $data = array(
                    'fecha' => date("Y-m-d"),
                    'hora' => date("H:i"),
                    'origen' => $_SERVER['REMOTE_ADDR'],
                    'usuario' => $usuario_db->usuario,
                    'nom_usuario' => $usuario_db->nom_usuario,
                    'nom_organizacion' => $usuario_db->nom_organizacion,
                    'accion' => 'login',
                    'entidad' => '',
                    'valor' => ''
                );
                $this->bitacora_model->guardar($data);
                redirect('admin');
            } else {
                $this->session->set_flashdata('error', 'Usuario o contraseña incorrectos');
                redirect('admin/login');
            }
        } else {
            $this->login();
        }
    }
}

