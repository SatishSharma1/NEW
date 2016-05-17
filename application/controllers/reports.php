<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

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
		$this->load->model('reportsmodel','',TRUE);
		$this->load->library(array('pagination'));
		$this->data = array(
			);
	}

	public function index($limit='',$page='')
	{
		$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();

        if($_POST){
    //  var_dump($_POST);
			//$reptype = $this->input->post('reporttype');
			
			$this->reportsmodel->generate_report();
			die();  	
}
       
        
		$data["links"] = $this->pagination->create_links();
 		$data['agentMap']=$blacklist;
 		//$this->layout->view('blacklisting',$data);

		$this->load->view('layout/header',$data);
		$this->load->view('reports/home',$data);
	}
	
	function get_columns(){
		    $type = $this->input->post('reporttype');
			
			if($type =='logs'){
			echo $this->reportsmodel->get_log_fields();
				
			}elseif($type =='leads'){
				
			}elseif($type =='agents'){
				
			}
	}

	







 


}

