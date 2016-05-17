<?php 
/**
* 
*/
class Workingmodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


function Saveworking(){
	$calledNumber = $this->input->post('Wcalled_number');
	$day = $this->input->post('dayid');
	$dayname = $this->input->post('day_name');
	$start_time = $this->input->post('start_time');
	$end_time = $this->input->post('end_time');

   		
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];

	
	if($calledNumber!="" && $day!="" && $dayname!="" && $start_time!="" && $end_time!="" ){
	$WorkingData = array('OrganizationID'=>$organizationId,'day_id'=>$day,'day_name'=>$dayname,'start_time'=>$start_time,'end_time'=>$end_time,'called_number'=>$calledNumber);
	$this->db->insert('working_hours',$WorkingData);
	}
}


function ShowWorking($limit,$start){
		
$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit,$start);
		$this->db->where('OrganizationID',$organizationId);
	$result = $this->db->get('working_hours');
	return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('working_hours');
     return $result->row()->total;
}

function UpdateWorking(){
	
	$called_number =$this->input->post('Wcalled_number');
 	$day =$this->input->post('dayid');
	
	$dayName =$this->input->post('day_name');
	$StartTime =$this->input->post('start_time');
	$EndTime =$this->input->post('end_time');
	$id =$this->input->post('updateid');
//die("sss");	
//	if($called_number!="" && $day!="" && $dayName!="" && $StartTime!="" && $EndTime!=""){
	//	echo "satish";
	$arraydata = array('called_number'=>$called_number,'day_id'=>$day,'day_name'=>$dayName,'start_time'=>$StartTime,'end_time'=>$EndTime);
    $this->db->where('id',$id);
	$this->db->update('working_hours',$arraydata);
	
//}
}

	function deleteWorking(){
		$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('working_hours');
	}

	function checkworkinghours(){
	  $called = $this->input->get('callednumber');

	  $OrgIDKey = $this->input->get('key');
	  $OrgID= $this->get_orgId_by_Key($OrgIDKey);
	 
       $time = date('H:i:s');

    $t=date('d-m-Y');
   $day  = date("l",strtotime($t));
   // echo $today = date("1"); 
  //die();
   
    $this->db->where('start_time <=',$time);
    $this->db->where('end_time >=',$time);
    $this->db->where('day_name',$day);
    $this->db->where('OrganizationID',$OrgID);
	$this->db->where('called_number',$called);
	$result =$this->db->get('working_hours');
 	$result=$result->row();
//echo $result->id;
//die();
	if(empty($result->id)){
       return 0;
	}else{
         return 1;
	}
}

function get_orgId_by_Key($key){
	$this->db->where('key',$key);
	$result= $this->db->get('organization');
     $result= $result->row();
     return $result->id;
}


	
}
?>