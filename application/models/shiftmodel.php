<?php 
/**
* 
*/
class Shiftmodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


 function SaveShift(){
 	
  	 $shift_id =$this->input->post('shift_id');
	$shift_name =$this->input->post('shift_name');
	$start_time =$this->input->post('start_time');
	$end_time =$this->input->post('end_time');

			
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	

if($shift_id!="" && $shift_name!="" && $start_time!="" && $end_time!=""){
	$arraydata = array('OrganizationID'=>$organizationId,'shift_id'=>$shift_id,'shift_name'=>$shift_name,'start_time'=>$start_time,'end_time'=>
		$end_time);

	$this->db->insert('shift_management',$arraydata);
}
   }
 
 
 function UpdateShift(){
   	 $shift_id =$this->input->post('shift_id');
	$shift_name =$this->input->post('shift_name');
	$start_time =$this->input->post('start_time');
	$end_time =$this->input->post('end_time');

	$id =$this->input->post('updateid');


	
	$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];

if($shift_id!="" && $shift_name!="" && $start_time!="" && $end_time!=""){
	$arraydata = array('OrganizationID'=>$organizationId,'shift_id'=>$shift_id,'shift_name'=>$shift_name,'start_time'=>$start_time,'end_time'=>
		$end_time);
    $this->db->where('id',$id);
	$this->db->update('shift_management',$arraydata);
}
   }



	function deleteshift(){
		//	(called_number,agent_list,regioncode,region_name)
		$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('shift_management');

	}
	










 
function ShowShift($limit,$start){
	$data['loggedUser']=$this->session->userdata('loggedIn');
	 	$organizationId=$data['loggedUser']['organizationId'];

		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit,$start);
		$this->db->where('OrganizationID',$organizationId);
	$result = $this->db->get('shift_management');
	return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('shift_management');
     return $result->row()->total;
}

function get_orgId_by_Key($key){
	$this->db->where('key',$key);
	$result= $this->db->get('organization');
     $result= $result->row();
	 if(empty($result)){
	 	return 0;
	 }
     return $result->id;
}

function getshifts($organizationID){
	if($organizationID==0)
	return 0;
    $this->db->where('OrganizationID',$organizationID);
    $result=$this->db->get('shift_management');
	return $result->result(); 

}


	
}
?>