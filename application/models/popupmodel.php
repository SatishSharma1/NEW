<?php 
/**
* 
*/
class Popupmodel extends CI_Model
{
	
	function __construct() {
		parent::__construct();
	//	$this->db->cache_on();

		
		
	}


 function SavePopup(){
	 $knowlarityNumber= $this->input->post('PKnowlarity_number');
	 $ApiKey = $this->input->post('PAPI_key');
	 $knowlarityAPI  = $this->input->post('Pknowlarity_api');
	 $cilentNumber ="leadmentor";
	 if($_POST){
	 $popupArray = array('knowlarity_number'=>$knowlarityNumber,'api_key'=>$ApiKey,'knowlarity_api'=>$knowlarityAPI,'cilent_name'=>$cilentNumber);
	 $this->db->insert('popup_configuration',$popupArray);
	}
	
}



function ShowPopup($limit,$start){
		
$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit,$start);
		//$this->db->where('OrganizationID',$organizationId);
	$result = $this->db->get('popup_configuration');
	return $result->result();
}

function _get_all(){
      
    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
	$this->db->select('count(id) total');
	//$this->db->where('OrganizationID',$organizationId);
	$result =$this->db->get('popup_configuration');
     return $result->row()->total;
}

function UpdatePopup(){
	
	 $knowlarityNumber= $this->input->post('PKnowlarity_number');
	 $ApiKey = $this->input->post('PAPI_key');
	 $knowlarityAPI  = $this->input->post('Pknowlarity_api');
	 $cilentNumber ="leadmentor";
	$id =$this->input->post('updateid');


	
	if($knowlarityNumber!="" && $ApiKey!="" && $knowlarityAPI!=""){
	//	echo "satish";
	//$arraydata = array('CalledNumber'=>$Hcalled_number1,'HolidayDate'=>$date1,'Holiday_Description'=>$holiday1);
   
	//$this->db->update('holiday',$arraydata);

	 $popupArray = array('knowlarity_number'=>$knowlarityNumber,'api_key'=>$ApiKey,'knowlarity_api'=>$knowlarityAPI,'cilent_name'=>$cilentNumber);
	  $this->db->where('id',$id); 
	 $this->db->update('popup_configuration',$popupArray);
	
}
}


function deletePopup(){
		$id	=$this->input->post('id');
     $this->db->where('id',$id);
     $this->db->delete('popup_configuration');
	}


  function get_lead_name_by_number(){
  	    $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
    	$number = $this->input->post('number');
    	$number = trim($number," ");
    	$this->db->select('id,name,email');
    	$this->db->like('phone',$number);
		$this->db->where('organizationId',$organizationId);
    	$result = $this->db->get('leads');
    	$result = $result->row();
    	//$result = $result->name;
    	if(empty($result))
    		return 0;
    	else
    		return $result;
    }
	
   function insertUpdateLead(){
   	$name = $this->input->post('name');
   	$email =  $this->input->post('email');
   	$phone =   $this->input->post('phone');
	$uuid =   $this->input->post('uuid');
    $update =  $this->input->post('updateidpop');
     $data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$userID=$data['loggedUser']['id'];

   	  $insertUpdate = array('name'=>$name,'email'=>$email,'phone'=>$phone,'organizationId'=>$organizationId,'leadAssignedID'=>$userID);

   	  if($update =='1'){
   	  	$this->db->where('phone',$phone);
   	  	$this->db->update('leads',$insertUpdate);
   	  //	return 'Lead Updated Successfully';
   	  }else{
   	  	$this->db->insert('leads',$insertUpdate);
   	 // 	return 'Lead Saved Successfully';
   	  }
	  
	  $this->savePopupUuid($uuid);

   }
   
   function savePopupUuid($uuid){
   $date = date('Y-m-d H:m:s');
   $dataArray =array('uuid'=>$uuid,'datetime'=>$date);
   $this->db->insert('popup_uuid',$dataArray);
   
   }


	
}
?>