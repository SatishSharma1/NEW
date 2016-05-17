<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->ci =& get_instance();
		date_default_timezone_set('Asia/Calcutta');
		$this->data = array(
			);
	}

	public function index($thumb='',$today_stats='',$app_stats='',$org_app='',$source='')
	{
		$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js'));
		$data['js']=put_headers_js();

		$this->load->view('layout/header',$data);
		$this->load->view('home/dashboard',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */