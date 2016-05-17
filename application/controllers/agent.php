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
		$this->load->model('agentmodel','',TRUE);
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
               $this->agentmodel->UpdateAgentMapping();
        	}else{
        	 $this->agentmodel->SaveAgentMapping();	
        }
}
       
         $limit = ($this->uri->segment(3))?$this->uri->segment(3):10;			//check if data limit is set or set it to 10
		 $data['total']=$this->agentmodel->_get_all();
		$data['per_page'] = $limit;
		/**configration of pagination***/
		$config["base_url"] = base_url() . "agent/index/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);


		$blacklist = $this->agentmodel->ShowAgentMapping($config['per_page'],$page);
		$data["links"] = $this->pagination->create_links();
 		$data['agentMap']=$blacklist;
 		//$this->layout->view('blacklisting',$data);

		$this->load->view('layout/header',$data);
		$this->load->view('agents/home',$data);
	}

	function deleteAgentMap(){
	$this->agentmodel->deleteagentmap();
}


function getagentlist(){

	    $OrgIDKey = $this->input->get('key');
	    $callednumber = $this->input->get('callednumber');
	    $extension = $this->input->get('extension');

	     $organizationID= $this->agentmodel->get_orgId_by_Key($OrgIDKey);

         $result = $this->agentmodel->getagentlist($organizationID,$callednumber,$extension);
        
       //  var_dump($result);
     //   die(); 
         header('Content-Type: text/xml');
	    if(empty($result)){
           echo $string= "<response>
    Parameters are not valid</response>";

	    }elseif(empty($extension)){
	   
          $count =$this->agentmodel->getagentlistCount($organizationID,$callednumber);
         
               echo $string= "<response>
    <rowcount>$count</rowcount>";

	    	foreach ($result as $result) {
 
	    		  echo $string= "<extensions>
	<menu>$result->Menu</menu>
        <agent_list>$result->Agentlist</agent_list>
    </extensions>";
	    	}

	    	  echo $string= "</response>";
               
	    }else{
           echo $string= "<response>
    <rowcount>1</rowcount>
           <menu>$result->Menu</menu>
            <agent_list>$result->Agentlist</agent_list>
</response>";
	    }

}


function getagentmapping(){
	
	  $called = $this->input->get('called_number');
	   $regioncode = $this->input->get('regioncode');
	  
	$data = $this->agentmodel->getagentmapping($called,$regioncode);
//	var_dump($data);
//	die("dddd");
	
	$this->db->where('CalledNumber', $called);
	//$this->db->where('regioncode', $regioncode);
    $this->db->from('agentlist');
      $count = $this->db->count_all_results();
	
	
	if(empty($data)){
		header('Content-Type: text/xml');
echo $string= "<response>

<rowcount>0</rowcount>

</response>";
	}else{
		header('Content-Type: text/xml');
echo $string= "<response>

<rowcount>$count</rowcount>

<agent_list>$data->Agentlist</agent_list>
<menu>$data->Menu</menu>
</response>";

	}
	


}

/*  function insertdata(){
  	for($i=0;$i<=1000000;$i++){
 $this->db->query("INSERT INTO `callLogs` (`leadId`, `organizationId`, `date`, `time`, `customerNumber`, `agentNumber`, `callRecordingurl`, `ivrType`, `field_date_1`, `field_date_2`, `field_time_1`, `field_time_2`, `field_datetime_1`, `field_datetime_2`, `field_varchar_1`, `field_varchar_2`, `field_varchar_3`, `field_varchar_4`, `field_varchar_5`, `field_varchar_6`, `field_varchar_7`, `field_varchar_8`, `field_varchar_9`, `field_varchar_10`, `field_enum_1`, `field_enum_2`, `field_enum_3`, `field_enum_4`, `field_enum_5`, `field_number_1`, `field_number_2`, `field_number_3`, `field_number_4`, `field_number_5`, `customerStatus`) VALUES
(565,	5,	'2015-02-13',	'00:00:00',	'+919990498605',	'+919990498605',	'',	'',	'0000-00-00',	'0000-00-00',	'00:00:00',	'00:00:00',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	'Delhi',	'Keshav',	'',	'',	'',	'',	'',	'',	'',	'',	'0',	'0',	'0',	'0',	'0',	0,	0,	0,	0,	0,	'Missed'),
(567,	5,	'2015-02-13',	'00:00:00',	'+919899859774',	'+919990498605',	'',	'I',	'0000-00-00',	'0000-00-00',	'00:00:00',	'00:00:00',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	'Delhi',	'Keshav',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	0,	0,	0,	0,	0,	'Connected');
");
  } }   */


 


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */