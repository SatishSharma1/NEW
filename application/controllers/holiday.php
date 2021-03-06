<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday extends CI_Controller {

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
		$this->load->model('holidaymodel','',TRUE);
		$this->load->library('pagination');
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
        	if(!empty($this->input->post('updateid'))){
               $this->holidaymodel->UpdateHoliday();
        	}else{
        	 $this->holidaymodel->SaveHoliday();	
        }

}
       $limit = ($this->uri->segment(3))?$this->uri->segment(3):10;			//check if data limit is set or set it to 10
		 $data['total']=$this->holidaymodel->_get_all();
		$data['per_page'] = $limit;
		/**configration of pagination***/
		$config["base_url"] = base_url() . "holiday/index/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);


		$blacklist = $this->holidaymodel->ShowHoliday($config['per_page'],$page);
		$data["links"] = $this->pagination->create_links();


		
		//var_dump($blacklist);
 		$data['agentMap']=$blacklist;
 		//$this->layout->view('blacklisting',$data);

		$this->load->view('layout/header',$data);
		$this->load->view('holiday/home',$data);
	}

	function deleteHoliday(){
	$this->holidaymodel->deleteHoliday();
}

function checkholiday(){	
	$result =$this->holidaymodel->checkholiday();
      
      header('Content-Type: text/xml');
      
	  if($result==0){
		
echo $string= "<response>

<is_holiday>no</is_holiday>

</response>";
	}else{
		echo $string= "<response>

<is_holiday>yes</is_holiday>

</response>";
		
}
}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */