<?php 
/**
* 
*/
class Agentmodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


 function SaveAgentMapping(){
 	
  	 $called_number =$this->input->post('CalledNumber');
	$agent_list =$this->input->post('Agentlist');
	$regioncode =$this->input->post('Extension');
	$region_name =$this->input->post('Menu');

			
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	

if($called_number!="" && $agent_list!="" && $regioncode!="" && $region_name!=""){
	$arraydata = array('OrganizationID'=>$organizationId,'CalledNumber'=>$called_number,'Agentlist'=>$agent_list,'Extension'=>$regioncode,'Menu'=>
		$region_name);

	$this->db->insert('agentlist',$arraydata);
}
   }
 
 
 function UpdateAgentMapping(){
    $called_number =$this->input->post('CalledNumber');

	$agent_list =$this->input->post('Agentlist');

	$regioncode =$this->input->post('Extension');

	$region_name =$this->input->post('Menu');

	$id =$this->input->post('updateid');


	
	$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];

if($called_number!="" && $agent_list!="" && $regioncode!="" && $region_name!=""){
	$arraydata = array('OrganizationID'=>$organizationId,'CalledNumber'=>$called_number,'Agentlist'=>$agent_list,'Extension'=>$regioncode,'Menu'=>
		$region_name);

    $this->db->where('id',$id);
	$this->db->update('agentlist',$arraydata);
}
   }



	function deleteagentmap(){
		//	(called_number,agent_list,regioncode,region_name)
		$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('agentlist');

	}
	


function getagentlist($organizationID,$callednumber,$extension){
    $this->db->where('OrganizationID',$organizationID);
    $this->db->where('CalledNumber',$callednumber);
    
	if(!empty($extension)){
        $this->db->where('Extension',$extension);
	}
$result=$this->db->get('agentlist');

if(!empty($extension)){
//	echo "satish";
	return $result->row(); 
}else{
//	echo "ravi";
	return $result->result(); 
}

}



function getagentlistCount($organizationID,$callednumber){
    $this->db->where('OrganizationID',$organizationID);
    $this->db->where('CalledNumber',$callednumber);
    
$result=$this->db->get('agentlist');
return $num = $result->num_rows();


}



  function getagentmapping($called,$regioncode){

  	$this->db->where('CalledNumber',$called);
	//$this->db->where('regioncode',$regioncode);
  	$result = $this->db->get('agentlist');
   	return $result->row(); 
  }
 
function ShowAgentMapping($limit,$start){
	$data['loggedUser']=$this->session->userdata('loggedIn');
	 	$organizationId=$data['loggedUser']['organizationId'];

		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit,$start);
		$this->db->where('OrganizationID',$organizationId);
	$result = $this->db->get('agentlist');
	return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('agentlist');
     return $result->row()->total;
}

function get_orgId_by_Key($key){
	$this->db->where('key',$key);
	$result= $this->db->get('organization');
     $result= $result->row();
     return $result->id;
}
	
}
?>