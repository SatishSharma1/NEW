<?php 
/**
* 
*/
class Holidaymodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


 function SaveHoliday(){
	$called= $this->input->post('Hcalled_number');
	$date = $this->input->post('Hdate');
	$holiday = $this->input->post('holiday');

	$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	
	if($called!="" && $date!="" && $holiday!=""){
	$holidayData = array('OrganizationID'=>$organizationId,'CalledNumber'=>$called,'HolidayDate'=>$date,'Holiday_Description'=>$holiday);
	$this->db->insert('holiday',$holidayData);
	}
}

function ShowHoliday($limit,$start){
             $data['loggedUser']=$this->session->userdata('loggedIn');
	     	 $organizationId=$data['loggedUser']['organizationId'];     
         
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit,$start);
		$this->db->where('OrganizationID',$organizationId);
	    $result = $this->db->get('holiday');
	    return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('holiday');
     return $result->row()->total;
}

function UpdateHoliday(){
	
	$Hcalled_number1 =$this->input->post('Hcalled_number');
	$date1 =$this->input->post('Hdate');
	$holiday1 =$this->input->post('holiday');
	$id =$this->input->post('updateid');


	
	if($Hcalled_number1!="" && $date1!="" && $holiday1!=""){
	//	echo "satish";
	$arraydata = array('CalledNumber'=>$Hcalled_number1,'HolidayDate'=>$date1,'Holiday_Description'=>$holiday1);
    $this->db->where('id',$id);
	$this->db->update('holiday',$arraydata);
	
}
}


function deleteHoliday(){
		$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('holiday');
	}


	function checkholiday(){

	   $called = $this->input->get('callednumber');

	  $OrgIDKey = $this->input->get('key');	
	
 

	 $OrgID= $this->get_orgId_by_Key($OrgIDKey);
	
	
   $date = date('y-m-d');



  
    $this->db->where('HolidayDate',$date);

	$this->db->where('CalledNumber',$called);
	$this->db->where('OrganizationID',$OrgID);
	$result =$this->db->get('holiday');
 	$result=$result->row();
 //$result->id;

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