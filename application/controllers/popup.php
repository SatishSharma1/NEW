<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Popup extends CI_Controller {

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
		$this->load->model('popupmodel','',TRUE);
		$this->load->library('pagination');
		$this->data = array(
			);
	}

	public function index($limit='',$page='')
	{
		$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.validate.min.js'));
		$data['js']=put_headers_js();
         $message ="";
        if($_POST){
        	if(!empty($this->input->post('updateid'))){
               $this->popupmodel->UpdatePopup();
        	}else{

        	   $registration = $this->register_Enable_API();
        	   if($registration =='1'){	
        	 $this->popupmodel->SavePopup();
        	 $message ="successfully registered and saved";
        	 }else{
               $message ="something went wrong while registation";
        	 }	
        }

}

        $limit = ($this->uri->segment(3))?$this->uri->segment(3):10;			//check if data limit is set or set it to 10
		 $data['total']=$this->popupmodel->_get_all();
		$data['per_page'] = $limit;
		/**configration of pagination***/
		$config["base_url"] = base_url() . "popup/index/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);


		
		$data["links"] = $this->pagination->create_links();


		$blacklist = $this->popupmodel->ShowPopup($config['per_page'],$page);
		//var_dump($blacklist);
 		$data['agentMap']=$blacklist;
 		$data['message']=$message;
 		//$this->layout->view('blacklisting',$data);

		$this->load->view('layout/header',$data);
		$this->load->view('popup/home',$data);
	}

	function register_Enable_API(){
	    $kNo= $this->input->post('PKnowlarity_number');
	 //$ApiKey = $this->input->post('PAPI_key');
	 $kAPI  = $this->input->post('Pknowlarity_api');
	 $cilentNumber ="leadmentor";	
	 $register = $this->registerpopupAPI($kNo,$kAPI,$cilentNumber);
	 $register =json_decode($register);
	  $register->message;
	 $enable =$this->enablepopupAPI($kNo,$kAPI,$cilentNumber);
	 $enable =json_decode($enable);
	  $enable->message;

	  if($register->message=='Successfully registered.' && $enable->message=='Successfully enabled.'){
	  	return 1;
	  }else{
	  	return 0;
	  }
	}



	function registerpopupAPI($kNo,$kAPI,$cilentNumber) {

 $kno['knowlarity_number'][]= $kNo;
$data = array("client_name"=>$cilentNumber,"product"=>"SR");

$data = array_merge($kno,$data);
$data = json_encode($data);
$url ="https://konnect.knowlarity.com/api/v1/registrations";



  $ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
                                                         
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json', 
    'Authorization:'.$kAPI,                                                                               
    'Content-Length: ' . strlen($data))                                                                       
); 
//var_dump($data);
//die();
return $result = curl_exec($ch);                                                                                                                

}


function enablepopupAPI($kNo,$kAPI,$cilentNumber) {  
$data = array("client_name"=>$cilentNumber);
$data = json_encode($data);
$kNo = str_replace("+", "%2b",$kNo);
  $url = "https://konnect.knowlarity.com/api/v1/registrations/$kNo/enable";
  $ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
                                                         
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json', 
    'Authorization:'.$kAPI,                                                                               
    'Content-Length: ' . strlen($data))                                                                       
); 
//var_dump($data);
//die();
return $result = curl_exec($ch);                                                                                                                

}

	function deletePopup(){
	$this->popupmodel->deletePopup();
}

  function get_lead_name_by_number(){
	$result = $this->popupmodel->get_lead_name_by_number();
	echo json_encode($result);
}


function insertUpdateLead(){
	 $this->popupmodel->insertUpdateLead();
	 redirect();
}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */