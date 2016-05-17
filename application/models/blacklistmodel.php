<?php 
/**
* 
*/
class Blacklistmodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


 function SaveBlacklist(){
 	$called = $this->input->post('called');
 	$caller = $this->input->post('caller');
      
       	
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];


       if($caller!="" && $called!="" ){
 	$data = array('OrganizationID'=>$organizationId,'called_number' =>$called,'caller_number'=>$caller);

 	$r=$this->db->insert('blacklist',$data);
 	return $r;
 }else{
 	return 0;
 }
 }

 function blacklistCaller(){
 	
 	$caller = $this->input->post('caller');
      
 	$data = array('called_number' =>"none",'caller_number'=>$caller);

 	$r=$this->db->insert('blacklist',$data);
 	return $r;
 
 }


	function check_caller($caller,$orgID){
	$this->db->where('caller_number',$caller);
	$this->db->where('OrganizationID',$orgID);
	$result =$this->db->get('blacklist');
	$caller=$result->row();
	if(empty($caller)){
       return 0;
	}else{
         return 1;
	}
}

function check_called($called,$orgID){
		$this->db->where('called_number',$called);
		$this->db->where('OrganizationID',$orgID);
	$result =$this->db->get('blacklist');
	$caller=$result->row();
	if(empty($caller)){
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


function ShowBlacklist($limit,$start){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];


	$this->db->order_by('id','DESC');
	$this->db->limit($limit,$start);
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('blacklist');
     return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('blacklist');
     return $result->row()->total;
}

function UpdateBlacklist(){
	
	$called =$this->input->post('called');
	$caller =$this->input->post('caller');
	
	$id =$this->input->post('updateid');


	
	if($called!="" && $caller!=""){
	//	echo "satish";
	$arraydata = array('called_number'=>$called,'caller_number'=>$caller);
    $this->db->where('id',$id);
	$this->db->update('blacklist',$arraydata);
	
}
}


function deleteBlacklist(){
	$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('blacklist');
	}

	
	function insert_data_blacklist($caller,$called,$orgId){

if($caller!="" && $called!="" && $orgId!="" ){

     $data = array(
         'OrganizationID'=>$orgId,
          'called_number' =>$called,
          'caller_number'=>$caller
              );
     $this->db->insert('blacklist',$data);           
    $q = $this->db->insert_id();

     return $q; 
      }
      else{
          return 0;
      }


}

function delete_black_data($caller,$called,$orgId){

//$data = array('OrganizationID'=>$orgId,'called_number' =>$called,'caller_number'=>$caller);
//  $query = $this->db->delete('blacklist',$data);          
   if($caller!="" && $called!="" && $orgId!="" ){ 
   $this->db->where('OrganizationID',$orgId);
   $this->db->where('called_number',$called);
   $this->db->where('caller_number',$caller);
   $query = $this->db->delete('blacklist');
    
     $qd = $this->db->affected_rows(); 
      return $qd; 
  
      }else{
          return 0;
      }

      
}



	
}
?>