<?php 
/**
* 
*/
class Reportsmodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();
	
	  $loggedIn = $this->session->userdata('loggedIn');
	  $this->orgid = $loggedIn['organizationId'];

		
		
	}

function get_log_fields(){
	
	$this->db->select('title,field');
	$this->db->where('organizationId',$this->orgid);
	$result = $this->db->get('list_view');
	$result = $result->result();
	$string ="";
	foreach($result as $result){
		$string .="<input type='checkbox' name='fields[]' value='$result->field'>".$result->title;
		$string .="<br>";
	}
	return $string;
}

function generate_report(){
	 $type = $this->input->post('reporttype');
	 $fromdate = $this->input->post('fromdate');
	echo  $todate = $this->input->post('todate');
	 $fields = $this->input->post('fields');
	// var_dump($fields);
	if($type =='logs'){
		//$this->db->where('',)
	}
	
}

 	
}
?>