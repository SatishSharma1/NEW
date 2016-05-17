<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('usermodel','',TRUE);
		$this->load->model('logmodel','',TRUE);
		$this->load->model('leadmodel','',TRUE);
		$this->load->model('admin_model','',TRUE);
		//$this->load->library('layout');
		$this->load->library('form_validation');
		$this->load->library("pagination");
		$this->load->helper(array('form', 'url'));
		$this->load->helper('csv');
		$this->adminSelectField = base_url('admin/select_field');
	}
	
	function index() {
		if($this->session->userdata('knowloggedIn')) {
			redirect('home','refresh');
		} else {
			$data=array();
			if($this->input->cookie('userEmail')) {
				$data=array('userEmail'=>$this->input->cookie('userEmail'),'password'=>$this->input->cookie('password'));
			}
			$this->load->view('user/login',$data);
		}
	}

	/********** Connected Leads Script ***********************/
	
	function getAgentByPhone($phno) {
		echo $this->logmodel->getAgentByPhone($phno);
	}

	function connectedLogs($search='',$limit='',$page='') {
		$data = $this->data;
	//	var_dump($data);exit();
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();

		if($listTitle = $this->logmodel->viewListTitle()) {
			$data['listTitle'] = $listTitle;
			//var_dump($data['listTitle']);exit();
		} else {
			redirect($this->adminSelectField);
		}
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		//var_dump($search);exit();
		$data['total'] = $this->logmodel->getConnectedCallsCount($search);//print_r($cc);exit;
			//var_dump($data['total']);exit();
		$data['per_page'] = $limit;

		$data['loggedUser'] = $this->session->userdata('loggedIn');
		if(empty($data['loggedUser']))
		{
			redirect('home' , 'login');
		}
		$data['pagename'] = 'connectedLogs';
		if($search!='key')
		{
			$customer=$agent=$datefrom=$dateto=$ivr='';
			$data['additionalParameter']=$search;
			$searchdata=explode(",",$search);
			//var_dump($searchdata);exit();  //----array(1) { [0]=> string(5) "ivr:I" }
			for($i=0;$i<sizeof($searchdata);$i++)
			{
				$searchfield=explode(":",$searchdata[$i]);
				if($searchfield[0]=='cu')
				{
					$customer=$searchfield[1];
				}
				if($searchfield[0]=='ag')
				{
					$agent=$searchfield[1];
				}
				 if($searchfield[0]=='datefrom')
				{
					$datefrom=$searchfield[1];
				}

				 if($searchfield[0]=='dateto')
				{
					$dateto=$searchfield[1];
				}
				 if($searchfield[0]=='ivr')
				{
					 $ivr=$searchfield[1];
				//var_dump($ivr); ----string(1) "I"
				//exit();
				}
				
			}
			 
			$data['total']=$this->logmodel->getAdvanceLogsData($customer,$agent,$datefrom,$dateto,'Connected',$ivr);
		 // var_dump($data); 
			//	exit();
		}
		
		$data['per_page'] = $limit;

		/**configration of pagination***/
		$config["base_url"] = base_url() . "connectedLogs/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		if($search!='key')
		{			
			$data['allCalls'] = $this->logmodel->getConnectedCallsSearch($config['per_page'],$page,$customer,$agent,$datefrom,$dateto,$ivr);
			//var_dump($ivr);exit();
		}
		else
		{
			$data['allCalls'] = $this->logmodel->getConnectedCalls($config['per_page'],$page);
		}

		//var_dump($data['allCalls']);
		$data['tot_records'] = count($data['allCalls']);
		$data["links"] = $this->pagination->create_links();
		$data['active'] = 'connectedLogs';
		$data['active_page'] = 'Connected Logs';
		//$this->layout->view('log/allLogs',$data);
		$this->load->view('layout/header',$data);
		$this->load->view('log/logs',$data);
	}
	/**************End*******/

	/********** Missed Logs Script ***********************/
	function missedLogs($search='',$limit='',$page='') 
	{
		    $data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();

		if($listTitle = $this->logmodel->viewListTitle()) {
				$data['listTitle'] = $listTitle;
		} else {
				redirect($this->adminSelectField);
		}

		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		
		$cc=$this->logmodel->getMissedCallsCount($search);//print_r($cc);exit;
		$data['total'] = $cc;
		$data['per_page'] = $limit;

		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(empty($data['loggedUser']))
		{
			redirect('home' , 'login');
		}
		$data['pagename'] = 'missedLogs';
		if($search!='key')
		{
			$customer=$agent=$datefrom=$dateto=$ivr='';
			$search;
			
			$data['additionalParameter']=$search;

			$searchdata=explode(",",$search);

			for($i=0;$i<sizeof($searchdata);$i++)
			{
				$searchfield=explode(":",$searchdata[$i]);
				if($searchfield[0]=='cu')
				{
					$customer=$searchfield[1];
					
				}
				if($searchfield[0]=='ag')
				{
					$agent=$searchfield[1];
				}
				else if($searchfield[0]=='datefrom')
				{
					$datefrom=$searchfield[1];
				}

				else if($searchfield[0]=='dateto')
				{
					$dateto=$searchfield[1];
				}

				else if($searchfield[0]=='ivr')
				{
				   $ivr=$searchfield[1];
				//exit();
				}
			}
			$data['total']=$this->logmodel->getAdvanceLogsData($customer,$agent,$datefrom,$dateto,'Missed',$ivr);
		}
		
		$data['per_page'] = $limit;

		/**configration of pagination***/
		$config["base_url"] = base_url() . "missedLogs/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		if($search!='key')
		{			
			$data['allCalls'] = $this->logmodel->getMissedCallsSearch($config['per_page'],$page,$customer,$agent,$datefrom,$dateto,$ivr);	
		}
		else
		{
			$data['allCalls'] = $this->logmodel->getMissedCalls($config['per_page'],$page);
		}
		$data['tot_records'] = count($data['allCalls']);
		$data["links"] = $this->pagination->create_links();
		$data['active'] = 'missedLogs';
		$data['active_page'] = 'Missed Logs';
		//$this->layout->view('log/allLogs',$data);
		$this->load->view('layout/header',$data);
		$this->load->view('log/logs',$data);
		//var_dump($data);
		//exit();
		

	}
	/**************End*******/
	
	function getcalldetails($id)
	{
		$result=$this->logmodel->getCalldetailsbyId($id);
		$json = json_encode($result);
		echo $json;
	}

	function allLogs($search='',$limit='',$page='')
	{

        $data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();


		if($listTitle = $this->logmodel->viewListTitle()) {
			$data['listTitle'] = $listTitle;
		} else {
			redirect($this->adminSelectField);
		}
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$cc=$this->logmodel->getAllCallsCount($search);//print_r($cc);exit;
		$data['total'] = $cc;
		$data['per_page'] = $limit;

		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(empty($data['loggedUser']))
		{
			redirect('home' , 'login');
		}
		$data['pagename'] = 'allLogs';
		if($search!='key')
		{
			$customer=$agent=$datefrom=$dateto=$ivr='';
			$data['additionalParameter']=$search;
			$searchdata=explode(",",$search);
			for($i=0;$i<sizeof($searchdata);$i++)
			{
				$searchfield=explode(":",$searchdata[$i]);
				if($searchfield[0]=='cu')
				{
					$customer=$searchfield[1];
				}
				if($searchfield[0]=='ag')
				{
					$agent=$searchfield[1];
				}
				else if($searchfield[0]=='datefrom')
				{
					$datefrom=$searchfield[1];
				}

				else if($searchfield[0]=='dateto')
				{
					$dateto=$searchfield[1];
				}
				else if($searchfield[0]=='ivr')
				{
					$ivr = $searchfield[1];

				}
			}
			$data['total']=$this->logmodel->getAdvanceLogsData($customer,$agent,$datefrom,$dateto,$ivr);
		}
		
		$data['per_page'] = $limit;

		/**configration of pagination***/
		$config["base_url"] = base_url() . "allLogs/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		if($search!='key')
		{			
			$data['allCalls'] = $this->logmodel->getAllCallsSearch($config['per_page'],$page,$customer,$agent,$datefrom,$dateto,$ivr);
		}
		else
		{
			$data['allCalls'] = $this->logmodel->getAllCalls($config['per_page'],$page);
		} 
		$data['enum_fields'] = array();$out = 0;
			foreach($data['allCalls'] as $call){
				$temp = array();
				foreach($call as $key=>$value){
					if(in_array($key, array('field_enum_1','field_enum_2','field_enum_3','field_enum_4','field_enum_5'))){
						$temp[$key] = $value;
					}
				}
				array_push($data['enum_fields'], $temp);
			} //print_r($data['allCalls']);exit;
		$data['tot_records'] = count($data['allCalls']);
		$data["links"] = $this->pagination->create_links();
		$data['active'] = 'allLogs';
		$data['active_page'] = 'All Logs';
		$this->load->view('layout/header',$data);
		$this->load->view('log/logs',$data);
		
		//var_dump($data['allCalls']);
	}
	
	/*
	function downloadAllLogcsv()
		{
			$data['loggedUser']=$this->session->userdata('loggedIn');
			$orgId = $data['loggedUser']['organizationId'];
			$now = date("Y-m-d-H:i:s");
			header("Content-type: application/vnd.ms-excel");
			if($_GET['pagename'] == 'missedLogs')
			{
			header("Content-Disposition: attachment; filename=MissedLog-".$now.".csv");		
			$getMissedLogData = $this->logmodel->getMissedLogcsv();
			echo array_to_csv($getMissedLogData);	
			}
			else if($_GET['pagename'] == 'connectedLogs')
			{
			header("Content-Disposition: attachment; filename=ConnectedLog-".$now.".csv");		
			$getConnectedLogData = $this->logmodel->getConnectedLogcsv();
			echo array_to_csv($getConnectedLogData);
			}
			else
			{
			header("Content-Disposition: attachment; filename=AllLog-".$now.".csv");		
			$getLogData = $this->logmodel->getAllLogcsv();
			echo array_to_csv($getLogData);	
			}
			
		}*/
	
	
		function downloadAllLogcsv()
	{
	
	    $data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		$now = date("Y-m-d-H:i:s");
      // $lowerlimit=$_GET['lowerLimit'];
      // $upperlimit=$_GET['upperLimit'];

		if($_GET['pagename'] == 'missedLogs')
		{
			
		$getMissedLogData = $this->logmodel->getMissedLogcsv();
		}
		else if($_GET['pagename'] == 'connectedLogs')
		{
		$getConnectedLogData = $this->logmodel->getConnectedLogcsv();
		}
		else if($_GET['pagename'] == 'allLogs')
		{
			//echo "Satish";
		$getLogData = $this->logmodel->getAllLogcsv();
		}
		
	}

	/*function importLogs()
	{
		$data['active'] = 'importLogs';
		$this->layout->view('log/importLogs',$data);
	}
	function findApiResponse()
	{
		$agent=trim($_POST['agentno']);
		$caller=trim($_POST['callerno']);
		$api=file_get_contents('http://int.kapps.in/webapi/zostel/api/zostel_click2call?callernumber='.$caller.'&agentnumber='.$agent);
		$xmlData = simplexml_load_string($api);
		echo $xmlData->status;
	}
	
	function updatePaymentByTicket()
	{
		$this->logmodel->updatePaymentByTicket();
	}
	function insertArrayValue()
	{	
		$this->logmodel->insertArrayValue();
	}*/
}

?>
