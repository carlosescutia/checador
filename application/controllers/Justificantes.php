<?php
class Justificantes extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accesos_sistema_model');
        $this->load->model('opciones_sistema_model');
        $this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');
        $this->load->model('justificantes_model');
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

    public function detalle_justificante($cve_justificante)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['justificante'] = $this->justificantes_model->get_justificante($cve_justificante);

            $this->load->view('templates/admheader', $data);
            $this->load->view('justificantes/detalle_justificante', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }
    
    public function detalle_vacacion($cve_justificante)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['justificante'] = $this->justificantes_model->get_justificante($cve_justificante);

            $this->load->view('templates/admheader', $data);
            $this->load->view('justificantes/detalle_vacacion', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }
    
    public function nueva_vacacion($cve_empleado)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['cve_empleado'] = $cve_empleado ;

            $this->load->view('templates/admheader', $data);
            $this->load->view('justificantes/nueva_vacacion', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function nuevo_justificante($cve_empleado, $fecha=null)
    {
        if ($this->session->userdata('logueado')) {
            $data = [];
            $data += $this->get_userdata();
            $data += $this->get_system_params();

            $data['cve_empleado'] = $cve_empleado ;
            $data['fecha'] = $fecha ;

            $this->load->view('templates/admheader', $data);
            $this->load->view('justificantes/nuevo_justificante', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function guardar($cve_justificante=null)
    {
        if ($this->session->userdata('logueado')) {

            $nuevo_justificante = is_null($cve_justificante);

            $justificante = $this->input->post();
            if ($justificante) {

                if ($cve_justificante) {
                    $accion = 'modificó';
                } else {
                    $accion = 'agregó';
                }

                // guardado
                $data = array(
                    'cve_empleado' => $justificante['cve_empleado'],
                    'fecha' => $justificante['fecha'],
                    'tipo' => $justificante['tipo'],
                    'detalle' => $justificante['detalle'],
                    'documento' => $justificante['documento'],
                );
                $cve_justificante = $this->justificantes_model->guardar($data, $cve_justificante);
                
                // registro en bitacora
				$separador = ' -> ';
				$usuario = $this->session->userdata('usuario');
				$nom_usuario = $this->session->userdata('nom_usuario');
				$nom_organizacion = $this->session->userdata('nom_organizacion');
				$entidad = 'justificantes';
                $valor = $cve_justificante . " " . $justificante['fecha'] . " " . $justificante['tipo'] ;
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

            redirect('admin/empleados_detalle/' . $justificante['cve_empleado']);

        } else {
            redirect('admin/login');
        }
    }

    public function eliminar($cve_justificante)
    {
        if ($this->session->userdata('logueado')) {

            // registro en bitacora
            $justificante = $this->justificantes_model->get_justificante($cve_justificante);
            $separador = ' -> ';
            $usuario = $this->session->userdata('usuario');
            $nom_usuario = $this->session->userdata('nom_usuario');
            $nom_organizacion = $this->session->userdata('nom_organizacion');
            $accion = 'eliminó';
            $entidad = 'justificantes';
            $valor = $justificante['cve_empleado'] . " " . $justificante['fecha'] . " " . $justificante['tipo'] ;

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
            $this->justificantes_model->eliminar($cve_justificante);

            redirect('admin/empleados_detalle/' . $justificante['cve_empleado']);

        } else {
            redirect('admin/login');
        }
    }

}

