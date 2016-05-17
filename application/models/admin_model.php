<?php
ini_set('memory_limit','16M');
/**
* 
*/
class Admin_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}


	public function getListFields() {
		return array(
				'date',
				'time',
				'customerNumber',
				'customerStatus',
				'ivrType',
				'field_date_1',
				'field_date_2',
				'field_time_1',
				'field_time_2',
				'field_datetime_1',
				'field_datetime_2',
				'field_varchar_1',
				'field_varchar_2',
				'field_varchar_3',
				'field_varchar_4',
				'field_varchar_5',
				'field_varchar_6',
				'field_varchar_7',
				'field_varchar_8',
				'field_varchar_9',
				'field_varchar_10',
				'field_enum_1',
				'field_enum_2',
				'field_enum_3',
				'field_enum_4',
				'field_enum_5',
				'field_number_1',
				'field_number_2',
				'field_number_3',
				'field_number_4',
				'field_number_5'
			);
	}
	public function getDefaults() {
		return array(
			'date',
			'time',
			'customerNumber',
			'customerStatus',
			'ivrType'
		);
	}
	public function getEnum() {
		return array(
			'field_enum_1',
			'field_enum_2',
			'field_enum_3',
			'field_enum_4',
			'field_enum_5'
		);
	}
	
	public function getEnumTitles(){
		//$enum = $this->db->select('title')->get_where('list_view', array('field' => 'field_enum_%'));
		$temp = $this->db->query("SELECT `title` FROM `list_view` WHERE `field` like 'field_enum_%'");
		$enum = array();
		foreach($temp->result_array() as $t){
				array_push($enum, $t['title']);
		}
		return $enum;
	}

	public function existListView($orgId) {
		$checkOrgQuery = $this->db->select('id')->get_where('list_view',
				array('organizationId' => $orgId)
			);
		$res = $checkOrgQuery->row();
		return $res ? True : False;
	}



public function deleteFieldsPresentListView($orgId){
		$this->db->query("delete from list_view where organizationId=$orgId");
	}

	public function addListView($fieldList, $orgId) {
		$lists = array();
		foreach ($fieldList as $fielName) {
			$inp = $this->input;
			if($inp->post($fielName) || in_array($fielName, $this->getDefaults())) {
				if(!$title = $inp->post($fielName . '_title')) {
					$title = $fielName;
				}
				$lists[] = array(
					'organizationId' => $orgId,
					'title' => $title,
					'field' => $fielName
				);
			}
		}
		$add = $this->db->insert_batch('list_view', $lists);
		if($add){
			return true;
		}else{
			return false;
		}

	}


public function updateListView($fieldList, $orgId) {

	//	$this->input->post('field_varchar_10_title');
			//die("xx");
		$lists = array();
		foreach ($fieldList as $fielName) {
			$inp = $this->input;
	

	//die($d);
			if($inp->post($fielName) || in_array($fielName, $this->getDefaults())) {
				if(!$title = $inp->post($fielName . '_title')) {
					$title = $fielName;
				}
				$lists= array(
					'organizationId' => $orgId,
					'title' => $title
					
				);
			}
			       $this->db->where('organizationId',$orgId); 
                   $this->db->where('field',$fielName);
			       $add = $this->db->update('list_view', $lists);

         



		}
		    
		if($add){
			return true;
		}else{
			return false;
		}

	}

public function get_fields_list_view($org_id){
	$this->db->where('organizationId',$org_id);
return	$this->db->get('list_view');
} 


public function AddNewListView($fieldList, $orgId){
	$lists = array();
	// code adding functionality
$presentFields =$this->get_fields_list_view($orgId)->result();

foreach ($fieldList as $fielName) {
  //  $value = $this->input->post($fielName.'_title');
    if($this->input->post($fielName.'_title')){
    //	echo '<br>'.$fielName;   // fields jo nayi maine bhari h 

    	foreach ($presentFields as $presentFields) {
          if($fielName!= $presentFields->field){
          	// add fielName to database 
            
          echo $this->input->post($fielName.'_title');


         //     die();
$lists[]= array(
					'organizationId' => $orgId,
					'title' => $this->input->post($fielName.'_title'),
					'field' => $fielName 
					
				);
         	
         	
			}
			     //  $this->db->where('organizationId',$orgId); 
                 //  $this->db->where('field',$fielName);

			       
          }


 }

    }
$add = $this->db->insert_batch('list_view', $lists);
//die();	
}


   public function deleteOrganization($orgId){

   	$this->db->query("delete from organization where id=$orgId");
   	$this->db->query("delete from list_view where organizationId=$orgId");
   	$this->db->query("drop view logs_list_view_$orgId");
    $this->db->query("delete from users where organizationId=$orgId");
    $this->db->query("delete from callLogs where organizationId=$orgId");

   }


	public function getPostedFields($fieldList) {
		$lists = array();
		foreach ($fieldList as $fielName) {
			if($this->input->post($fielName) || in_array($fielName, $this->getDefaults())) {
				$lists[] = $fielName;
			}
		}
		return $lists;
	}

	public function createViewListTable($lists, $orgId) {
		$selects = "callLogs.id logId, callLogs.leadId leadId, callLogs.agentNumber agentNumber,callLogs.inbound_talktime inbound_talktime,callLogs.outbound_talktime outbound_talktime,callLogs.call_duration_time call_duration_time,callLogs.conversation_duration_time conversation_duration_time,callLogs.call_end_time call_end_time,callLogs.wrap_time wrap_time,callLogs.uuid uuid, callLogs.callRecordingurl callRecordingurl";
		//$selects = "leadId,agentNumber,callRecordingurl";
		foreach ($lists as $mylist) {
			$selects .= " ," . $mylist;
		}
         // $selects .= " ,insertdatetime"; 
		/*$this->db->query("CREATE TABLE logs_list_view_$orgId 
			SELECT $selects FROM callLogs INNER JOIN leads
			ON leads.id = callLogs.leadId
			WHERE callLogs.organizationId=?
			ORDER BY logId", array($orgId)); */	
  // var_dump($selects);
  // die();
			/*	$this->db->query("CREATE TABLE logs_list_view_$orgId 
			AS (SELECT $selects FROM callLogs)");  */

            $this->db->query("CREATE TABLE logs_list_view_$orgId SELECT $selects FROM callLogs WHERE 1=0");
            $this->db->query("ALTER TABLE logs_list_view_$orgId ADD id INT PRIMARY KEY AUTO_INCREMENT FIRST");
            
			  		
	}

}
