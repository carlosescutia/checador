<?php
class Importar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_model');
		$this->load->model('accesos_sistema_model');
		$this->load->model('opciones_sistema_model');
		$this->load->model('bitacora_model');
        $this->load->model('parametros_sistema_model');

		$this->load->model('asistencias_model');
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

			$this->load->view('templates/admheader', $data);
			$this->load->view('importar/index', $data);
			$this->load->view('templates/footer', $data);
		} else {
			redirect('admin/login');
		}
	}

    public function guardar()
    {
        $nombre_archivo = 'checador.csv';
        $nombre_archivo_fs = './doc/' . $nombre_archivo;
        if ( file_exists($nombre_archivo_fs) ) {
            // Cargar datos en bd
            $nombre_archivo = 'checador.csv';
            $nombre_archivo_fs = './doc/' . $nombre_archivo;
            $contador = 0;
            $file = fopen($nombre_archivo_fs, "r");
            $linea = fgetcsv($file);
            while(!feof($file)) {
                $contador += 1;
                $linea = fgetcsv($file);
                if ($linea) {
                    $data = array(
                        'cve_empleado' => $linea['1'],
                        'fecha' => $linea['3'],
                        'hora' => $linea['4'],
                    );
                    $this->asistencias_model->guardar($data);
                }
            }
            fclose($file);

            // Eliminar archivo de importación
            $status = unlink($nombre_archivo_fs) ? 'Se eliminó el archivo '.$nombre_archivo_fs : 'Error al eliminar el archivo '.$nombre_archivo_fs;
            echo $status;
        }
        redirect('importar');
    }


}
