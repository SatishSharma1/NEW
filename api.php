<?php

class Api extends CI_Controller {

	function __construct() {
		parent::__construct();
		 $this->load->model('blacklistmodel','',TRUE);
		$this->load->model('usermodel','',TRUE);
		$this->load->model('leadmodel','',TRUE);
		$this->load->model('logmodel','',TRUE);
		$this->load->library('layout');
		//$this->config->load('email');
		$this->load->config('email', TRUE);
		$this->load->helper('form');
		$this->load->helper('xml');
		$this->load->library('form_validation');
		$this->userData = $this->session->userdata('loggedIn');

	}


function blacklistCaller(){
   $message = $this->blacklistmodel->blacklistCaller();
   echo $message;
}

function saveBlacklist(){
	$this->blacklistmodel->saveBlacklist();
     $this->blacklisted_Numbers("Blacklisting Saved successfully");
} 

function blacklisted_Numbers($message=""){
 		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
               $data['activeUserLevel']=2;
					$data['active'] = 'Blacklist';
					$data['message'] = $message;
 		$blacklist = $this->blacklistmodel->blacklisted_Numbers();
 		$data['blacklist']=$blacklist;
 		$this->layout->view('blacklisting',$data);
 	}

function downloadAllAgentMappingcsv(){
	$this->blacklistmodel->downloadAllAgentMappingcsv();
}

function blacklistapi(){
	$this->blacklistmodel->blacklistapi();
}

function performaction(){
	$this->blacklistmodel->performaction();
	
}

function SaveAgentMapping(){
	
	
 $save=$this->blacklistmodel->SaveAgentMapping();
 if($save==1){	
 $this->ShowAgentMapping("Agent Mapping Saved successfully");
}else{
	$this->ShowAgentMapping("didnumber already exists try another didnumber");
}}

function SaveLocation(){
	$this->blacklistmodel->SaveLocation();
	$this->ShowLocation("Location Saved Successfully");
}

function UpdateLocation(){
	$this->blacklistmodel->UpdateLocation();
	$this->ShowLocation("Location updated Successfully");
}

function ShowLocation($message=""){
	
	$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
               $data['activeUserLevel']=2;
			$data['active'] = 'location';
		$data['message'] = $message;
 		$blacklist = $this->blacklistmodel->ShowLocation();
		//vardump($blacklist);
		//die();
 		$data['location']=$blacklist;
 		$this->layout->view('blacklisting',$data);

	}

function Checkdidnumber1(){
$didnumber	=$this->input->post('didnumber');

$this->db->where('didnumber',$didnumber);
$result =$this->db->get('askme_agent_mapping');
	$result=$result->row();

	if(empty($result->id)){
       echo json_encode(FALSE);
	}else{
         echo json_encode(FALSE);
	}

}

//checkdid

function ShowAgentMapping($message=""){

	$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
               $data['activeUserLevel']=2;
			$data['active'] = 'AgentMapping';
		$data['message'] = $message;

 		$blacklist = $this->blacklistmodel->ShowAgentMapping();
 		//var_dump($blacklist);
 		//die('aaa');
 		$data['agentMap']=$blacklist;
 		$this->layout->view('blacklisting',$data);

	}





function getagentlistapi(){
	  $called = $this->input->get('callednumber');
	//die();
	$data = $this->blacklistmodel->getagentlistapi($called);
	
	if(empty($data)){
		header('Content-Type: text/xml');
echo $string= "<root><rowcount>0</rowcount></root>";
	}else{
		header('Content-Type: text/xml');
echo $string= "<root>
					<status>Success</status>
					<extensions>
						<locationid>".$data->locationid."</locationid>
						<mobile1>".$data->mobile1."</mobile1>
						<mobile2>".$data->mobile2."</mobile2>
						<mobile3>".$data->mobile3."</mobile3>
						<phone1>".$data->phone1."</phone1>
						<phone2>".$data->phone2."</phone2>
						<phone3>".$data->phone3."</phone3>
						<phone4>".$data->phone4."</phone4>
						<companyName>".$data->company_name."</companyName>
					</extensions>
              </root>";

	}
	


}




function getcallcount(){

	$data = $this->logmodel->getcallcount();
	
	if(empty($data)){
		header('Content-Type: text/xml');
echo $string= "<callcount>0</callcount>";
	}else{
		header('Content-Type: text/xml');
echo $string= "<callcount>$data</callcount>";

	}
	


}

    function checkblacklist()
{
         $caller =  $this->input->get('callernumber');
          $called =  $this->input->get('callednumber');
       $caller = str_replace(" ","+", $caller);
        $called = str_replace(" ","+", $called);
			

      $callerCheck= $this->blacklistmodel->check_caller($caller);
        $calledCheck= $this->blacklistmodel->check_called($called);

       if($callerCheck==1 && $calledCheck==1){
            $this->error("yes", 404);

       }elseif($callerCheck==1 && $called=="None"){
       	      $this->error("yes", 404);
       }elseif($callerCheck==1 && $called==""){
       	      $this->error("yes", 404);
       }elseif($callerCheck==1 && $calledCheck==0){
              $this->error("no", 404);
       }else{
       	    $this->error("no", 404);
       }
        
          
}

function deleteAgentMap(){
	$this->blacklistmodel->deleteagentmap();
}

function deleteLocation(){
	$this->blacklistmodel->deleteLocation();
}

function deleteblacklist(){
	$this->blacklistmodel->deleteblacklist();
}

 	

 


function error($msg, $code = 0) {
header('Content-Type: text/xml');
echo $string= "<root><is_blacklist>$msg</is_blacklist></root>";
}



}

?>
