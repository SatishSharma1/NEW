<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shift extends CI_Controller {

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
		$this->load->model('shiftmodel','',TRUE);
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
        	if(!empty($this->input->post('updateid'))){
               $this->shiftmodel->UpdateShift();
        	}else{
        	 $this->shiftmodel->SaveShift();	
        }
}
       
         $limit = ($this->uri->segment(3))?$this->uri->segment(3):10;			//check if data limit is set or set it to 10
		 $data['total']=$this->shiftmodel->_get_all();
		$data['per_page'] = $limit;
		/**configration of pagination***/
		$config["base_url"] = base_url() . "shift/index/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);


		$blacklist = $this->shiftmodel->ShowShift($config['per_page'],$page);
		$data["links"] = $this->pagination->create_links();
 		$data['shift']=$blacklist;
 		//$this->layout->view('blacklisting',$data);

		$this->load->view('layout/header',$data);
		$this->load->view('shift/home',$data);
	}

	function deleteShift(){
	$this->shiftmodel->deleteshift();
}

function getshifts(){
	    $OrgIDKey = $this->input->get('key');
	   

	     $organizationID= $this->shiftmodel->get_orgId_by_Key($OrgIDKey);

         $result = $this->shiftmodel->getshifts($organizationID);
        
      
         header('Content-Type: text/xml');
	    if(empty($result)){
           echo $string= "<response>
    key is not valid</response>";

	    }else{
            $string= "<response>";
		   
		   foreach($result as $result){
     $string.="
           <shift_id>$result->shift_id</shift_id>
           <shift_name>$result->shift_name</shift_name>
           <start_time>$result->start_time</start_time>
            <end_time>$result->end_time</end_time>";
		   }
            $string .="</response>";
			echo $string;
	    }
		

}


 


}
