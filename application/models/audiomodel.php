<?php 
/**
* 
*/
class Audiomodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


 function SaveAudio($audiolink){
 	
  	// $audiolink =$this->input->post('audiolink');
	$description =$this->input->post('description');
	$datatime  = date('Y-m-d H:i:s');
			
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$userId=$data['loggedUser']['id'];
	

if($audiolink!="" && $description!=""){
	$arraydata = array('OrganizationID'=>$organizationId,'user_id'=>$userId,'description'=>$description,'audio_link'=>$audiolink,'created_on'=>$datatime);

	$this->db->insert('uploaded_audio',$arraydata);
}
   }
 
 
 


	function deleteaudio(){
		//	(called_number,agent_list,regioncode,region_name)
		$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('uploaded_audio');

	}
	
 
function ShowAudio($limit,$start){
	$data['loggedUser']=$this->session->userdata('loggedIn');
	 	$organizationId=$data['loggedUser']['organizationId'];

		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit,$start);
		$this->db->where('OrganizationID',$organizationId);
	$result = $this->db->get('uploaded_audio');
	return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('uploaded_audio');
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

function getaudios($organizationID){
	if($organizationID==0)
	return 0;
    $this->db->where('OrganizationID',$organizationID);
    $result=$this->db->get('uploaded_audio');
	return $result->result(); 

}


	
}
?>