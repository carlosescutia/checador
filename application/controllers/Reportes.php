<?php
class Reportes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_model');
		$this->load->model('accesos_sistema_model');
		$this->load->model('opciones_sistema_model');
		$this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');
	}

	public function index()
	{
		if ($this->session->userdata('logueado')) {
			$cve_rol = $this->session->userdata('cve_rol');
			$data['cve_usuario'] = $this->session->userdata('cve_usuario');
			$data['cve_organizacion'] = $this->session->userdata('cve_organizacion');
			$data['nom_organizacion'] = $this->session->userdata('nom_organizacion');
			$data['cve_rol'] = $cve_rol;
			$data['nom_usuario'] = $this->session->userdata('nom_usuario');
			$data['error'] = $this->session->flashdata('error');
			$data['accesos_sistema_rol'] = explode(',', $this->accesos_sistema_model->get_accesos_sistema_rol($cve_rol)['accesos']);
			$data['opciones_sistema'] = $this->opciones_sistema_model->get_opciones_sistema();

			$this->load->view('templates/admheader', $data);
			$this->load->view('reportes/index', $data);
			$this->load->view('templates/footer');
		} else {
			redirect('admin/login');
		}
	}

	public function listado_bitacora_01()
	{
		if ($this->session->userdata('logueado')) {
			$cve_rol = $this->session->userdata('cve_rol');
			$data['cve_usuario'] = $this->session->userdata('cve_usuario');
			$data['cve_organizacion'] = $this->session->userdata('cve_organizacion');
			$data['nom_organizacion'] = $this->session->userdata('nom_organizacion');
			$data['cve_rol'] = $cve_rol;
			$data['nom_usuario'] = $this->session->userdata('nom_usuario');
			$data['error'] = $this->session->flashdata('error');
			$data['accesos_sistema_rol'] = explode(',', $this->accesos_sistema_model->get_accesos_sistema_rol($cve_rol)['accesos']);
			$data['opciones_sistema'] = $this->opciones_sistema_model->get_opciones_sistema();

			$filtros = $this->input->post();
			if ($filtros) {
				$accion = $filtros['accion'];
				$entidad = $filtros['entidad'];
			} else {
				$accion = '';
				$entidad = '';
			}

			$data['accion'] = $accion;
			$data['entidad'] = $entidad;

            $nom_organizacion = $this->session->userdata['nom_organizacion'];
            $usuario = $this->session->userdata['usuario'];
			$data['bitacora'] = $this->bitacora_model->get_bitacora($usuario, $nom_organizacion, $cve_rol, $accion, $entidad);

			$this->load->view('templates/admheader', $data);
			$this->load->view('reportes/listado_bitacora_01', $data);
			$this->load->view('templates/footer');
		} else {
			redirect('admin/login');
		}
	}

    public function listado_bitacora_01_csv()
    {
        if ($this->session->userdata('logueado')) {
            $cve_rol = $this->session->userdata('cve_rol');
            $data['cve_usuario'] = $this->session->userdata('cve_usuario');
            $data['cve_organizacion'] = $this->session->userdata('cve_organizacion');
            $data['nom_organizacion'] = $this->session->userdata('nom_organizacion');
            $data['cve_rol'] = $cve_rol;
            $data['nom_usuario'] = $this->session->userdata('nom_usuario');
            $data['error'] = $this->session->flashdata('error');
            $data['accesos_sistema_rol'] = explode(',', $this->accesos_sistema_model->get_accesos_sistema_rol($cve_rol)['accesos']);
            $data['opciones_sistema'] = $this->opciones_sistema_model->get_opciones_sistema();

            $this->load->dbutil();
            $this->load->helper('file');
            $this->load->helper('download');

            $nom_organizacion = $this->session->userdata('nom_organizacion');
            $usuario = $this->session->userdata('usuario');
            $filtros = $this->input->post();
            if ($filtros) {
                $accion = $filtros['accion'];
                $entidad = $filtros['entidad'];
            } else {
                $accion = '';
                $entidad = '';
            }

            if ($cve_rol == 'sup') {
                $usuario = '%';
            }
            if ($cve_rol == 'adm') {
                $usuario = '%';
                $nom_organizacion = '%';
            }

            $sql = "select b.* from bitacora b where b.usuario LIKE ? and b.nom_organizacion LIKE ? ";
            if ($cve_rol !== 'adm') {
                $sql .= " and b.usuario not in (select usuario from usuarios where cve_rol = 'adm')";
            }
            $parametros = array();
            array_push($parametros, "$usuario");
            array_push($parametros, "$nom_organizacion");
            if ($accion <> "") {
                $sql .= ' and b.accion = ?';
                array_push($parametros, "$accion");
            } 
            if ($entidad <> "") {
                $sql .= ' and b.entidad = ?';
                array_push($parametros, "$entidad");
            } 
            $sql .= ' order by b.cve_evento desc;';
            $query = $this->db->query($sql, $parametros);

            $delimiter = ",";
            $newline = "\r\n";
            $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
            force_download("listado_bitacora_01.csv", $data);
        }
    }

}
