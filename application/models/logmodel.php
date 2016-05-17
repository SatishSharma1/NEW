<?php

 class Logmodel extends CI_Model {
 	private $organizationId;
	function __construct() {
		parent::__construct();
		$this->db=$this->load->database('default',TRUE);
		//$this->dbs=$this->load->database('dbserver',TRUE);
		$userData = $this->session->userdata('loggedIn');
		$this->usersData = $this->session->userdata('loggedIn');
		 $this->organizationId = $userData['organizationId'];
		$this->db->cache_on();
		$this->logViewTable = 'logs_list_view_' . $this->organizationId;
		
		
		// dd($this->viewField);
	}
	public function getCalldetailsbyId($id) {
		$query=$this->db->get_where('callLogs', array('id'=>$id));
 		return $query->row();
	}

	public function getAgentByPhone($phno){
		$query=$this->db->select('agentList')->get_where('callLogs', array('customerNumber'=>$phno));
		if($res = $query->row())
			return $res->agentList;
		return '';
	}

	public function getLatestCallsCount($limit) {
		$getQuery = $this->db->query("SELECT  `date` , count(if(customerStatus='Connected', 1, null)) connected,
					count(if(customerStatus='Missed', 1, null)) missed,
					count(logId) alllogs FROM $this->logViewTable GROUP BY `date` ORDER BY `date` DESC LIMIT $limit" );
		return $getQuery->result();
	} 
	public function logViewTableExist() {
		return $this->db->table_exists($this->logViewTable);
	}

	public function viewListTitle() {
		$titleQuery = $this->db->select('title')->get_where('list_view',
				array('organizationId' => $this->organizationId)
			);
		$res = $titleQuery->result();
		if(!$res) {
			redirect(base_url('admin/select_field'));
		}
		$ret = array();
		foreach ($res as $val) {
			$ret[] = $val->title;
		}
		return $ret;
	}

	function getConnectedCalls($limit,$start) {
		$this->db->from($this->logViewTable);
		$this->db->where('customerStatus','Connected');
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
		$this->db->order_by('logId','desc');
		$this->db->group_by('logId');
		if($start=='')
			$this->db->limit($limit);
		else
			$this->db->limit($limit,$start);
		$result=$this->db->get();
		$this->db->last_query();
		$res=$result->result_array();
		return $res;
	}





// code for stats in standalone
	function get_first_logged_in_time($userid){
		    $date = date('y-m-d');
		    $this->db->where('date',$date);
		    $this->db->limit(1);
		    $this->db->order_by('id','ASC');
		    $this->db->where('user_id',$userid);
		    $this->db->where('type','LI');
            $result = $this->db->get('user_activity');
            $result = $result->row();
            return $result->time;
	}

	function get_log_out_time($userid){
		    $date = date('y-m-d');
		    $this->db->where('date',$date);
		    $this->db->limit(1);
		    $this->db->order_by('id','DESC');
		    $this->db->where('user_id',$userid);
		    $this->db->where('type','LO');
            $result = $this->db->get('user_activity');
            $result = $result->row();
            return $result->time;
	}


	function get_total_connected_Calls($AgentNumber,$orgID){
		    $date = date('y-m-d');
		    $this->db->select('count(customerStatus) as callCount');
		    $table = 'logs_list_view_' .$orgID;
		    $this->db->where('agentNumber',$AgentNumber);	
		    $this->db->where('date',$date);		   
		    $this->db->where('customerStatus','Connected');
            $result = $this->db->get($table);
            $result = $result->row();
            return $result->callCount;
	}

	function get_total_missed_Calls($AgentNumber,$orgID){
		    $date = date('y-m-d');
		    $this->db->select('count(customerStatus) as callCount');
		    $table = 'logs_list_view_' .$orgID;
		    $this->db->where('agentNumber',$AgentNumber);	
		    $this->db->where('date',$date);		   
		    $this->db->where('customerStatus','Missed');
            $result = $this->db->get($table);
            $result = $result->row();
            return $result->callCount;
	}

	function get_total_notconnected_Calls($AgentNumber,$orgID){
		    $date = date('y-m-d');
		    $this->db->select('count(customerStatus) as callCount');
		    $table = 'logs_list_view_' .$orgID;
		    $this->db->where('agentNumber',$AgentNumber);	
		    $this->db->where('date',$date);		   
		    $this->db->where('customerStatus','Missed');
            $result = $this->db->get($table);
            $result = $result->row();
            return $result->callCount;
	}

   function total_logged_in_time($UserID){
		    $date = date('y-m-d');
		    $this->db->where('date',$date);
		    $this->db->where('user_id',$UserID);
            $result = $this->db->get('user_activity');
                   $loggTime ="";
            	$result = $result->result();
            	foreach ($result as $result) {
            		   if($result->type=='LI'){
                        $LI_time = $result->time;
                        $parsed = date_parse($LI_time);
                         $LI_time = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
                   }

                   if($result->type=='LO'){
                    $LO_time = $result->time;
                   	 $parsed1 = date_parse($LO_time);
                     $LO_time = $parsed1['hour'] * 3600 + $parsed1['minute'] * 60 + $parsed1['second'];
                   	$loggTime += $LO_time - $LI_time;
                   }
            	}

             return round($loggTime/60,2);   //returns data in mins 
            
      
	}

	function get_inbound_total_talktime($AgentNumber,$orgID){
            $date = date('y-m-d');
		    $this->db->select('inbound_talktime as talktime');
		    $table = 'logs_list_view_' .$orgID;
		    $this->db->where('agentNumber',$AgentNumber);	
		    $this->db->where('ivrType','I');
		    $this->db->where('date',$date);		   
		    $this->db->where('customerStatus','Connected');
            $result = $this->db->get($table);
            $result = $result->result();
            $talktime ="";
            foreach ($result as $result) {
            	$talktime += $result->talktime;
            }
            return $talktime;
	}

	function get_outbound_total_talktime($AgentNumber,$orgID){
		    $date = date('y-m-d');
		    $this->db->select('outbound_talktime as talktime');
		    $table = 'logs_list_view_' .$orgID;
		    $this->db->where('ivrType','O');
		    $this->db->where('agentNumber',$AgentNumber);	
		    $this->db->where('date',$date);		   
		    $this->db->where('customerStatus','Connected');
            $result = $this->db->get($table);
            $result = $result->result();
            $talktime ="";
            foreach ($result as $result) {
            	$talktime += $result->talktime;
            }
            return $talktime;           
	}
	
	function productive_hours_agents($userID){
		    $date = date('y-m-d');	   
		    $this->db->select('activity_time');
		    $this->db->where('date',$date);
			$this->db->where('user_id',$userID);
			$this->db->where('type','LI');
            $result = $this->db->get('user_activity');
            $result = $result->result();
            $activity_time ="";
            foreach ($result as $result) {
            	$activity_time += $result->activity_time;
            }
            return $activity_time;   
	}


     function aux_time_agents($userID){
		 $busy  =$this->busy_time_agents($userID);
		 $break =$this->break_time_agents($userID);
		   $auxTime = $busy + $break;
            return $auxTime;   
	}
	 
	 function wrap_time_agents(){
	 	$date = date('y-m-d');
		//echo error_reporting(E_ALL);
		 $uuidtoday = $this->get_all_uuid_time_today($date);
		  //var_dump($uuidtoday); 
		 
		  foreach($uuidtoday as $uuidtoday){
		  	$orgid = $uuidtoday->orgid;
			
			$uuid= $uuidtoday->uuid;
			
			$poptime = $uuidtoday->poptime;
			
			$logtime = $uuidtoday->logtime;
			
		 	$wraptime = strtotime($poptime) - strtotime($logtime);   // wrap time in seconds
			
			 // update wrap time in specific organization table 
			 
			 //$this->updateWrapTime();
			 
			 $tablename ='logs_list_view_' . $orgid;
			 
			 $updateArray = array('wrap_time'=>$wraptime);
			 $this->db->where('uuid',$uuid);
			 $this->db->update($tablename,$updateArray);
			 
			 
		  }
		  
		 /*
		  die();
														   $uuidArr = array();
				  foreach($uuidtoday as $uuidtoday){
					  $$uuidArr[$uuidtoday->uuid] = $uuidtoday->poptime;
				  }*/
		 		 	 	
	 }
	 
	 
	 function wrap_time_agent_sum($AgentNumber,$organizationId){
	 	    $date = date('y-m-d');
		    $this->db->select('sum(wrap_time) as sum');
		    $table = 'logs_list_view_' .$organizationId;
		    $this->db->where('agentNumber',$AgentNumber);	
		    $this->db->where('date',$date);		   
		    //$this->db->where('customerStatus','Connected');
            $result = $this->db->get($table);
            $result = $result->row();
            return $result->sum;
	 }
	 
	 function get_all_uuid_time_today($date){
	 	$this->db->select('TIME(pu.datetime) poptime,pu.uuid uuid,c.time logtime, c.organizationId orgid');
	 	$this->db->where('DATE(pu.datetime)',$date);
		$this->db->from('popup_uuid pu');
		$this->db->where('pu.uuid !=','NA');
		$this->db->where('pu.uuid !=','');
		$this->db->join('callLogs c','pu.uuid = c.uuid');
	 	$result = $this->db->get();
		$result = $result->result();
		//echo $this->db->last_query();
		return $result;
	 }
	 
	  function busy_time_agents($userID){
		    $date = date('y-m-d');	   
		    $this->db->select('activity_time');
		    $this->db->where('date',$date);
			$this->db->where('user_id',$userID);
			$this->db->where('type','BU');
            $result = $this->db->get('user_activity');
            $result = $result->result();
            $activity_time ="";
            foreach ($result as $result) {
            	$activity_time += $result->activity_time;
            }
            return $activity_time;   
	}
	  
	  function break_time_agents($userID){
		    $date = date('y-m-d');	   
		    $this->db->select('activity_time');
		    $this->db->where('date',$date);
			$this->db->where('user_id',$userID);
			$this->db->where('type','BR');
            $result = $this->db->get('user_activity');
            $result = $result->result();
            $activity_time ="";
            foreach ($result as $result) {
            	$activity_time += $result->activity_time;
            }
            return $activity_time;   
	}

     function notAvailable_agents($userID){
		    $date = date('y-m-d');	   
		    $this->db->select('activity_time');
		    $this->db->where('date',$date);
			$this->db->where('user_id',$userID);
			$this->db->where('type','LO');
            $result = $this->db->get('user_activity');
            $result = $result->result();
            $activity_time ="";
            foreach ($result as $result) {
            	$activity_time += $result->activity_time;
            }
            return $activity_time;   
	}
    function check_dashboard_stats($date){
      $this->db->where('date',$date);
	  $result = $this->db->get('dashboard_stats');
	  $result = $result->row();
	  if(empty($result)){
	  	return 0;
	  }else{
	     return 1;
	  }
	  	
    }
	
	function delete_dashboard_stats($date){
		$this->db->where('date',$date);
		$this->db->delete('dashboard_stats');
	}

	function insert_Stats($ArrayData){
		$this->db->insert('dashboard_stats',$ArrayData);
	}

	function get_dashboard_Stats($orgid){
		$date = date('y-m-d');
			//$date = date('2016-03-22');
	   //$date = date('Y-m-d',strtotime('yesterday'));

        $fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->like('date',$date);
     	}

		
		if($this->usersData['userLevel']=='3') { $this->db->where('AgentNumber',$this->usersData['userPhone']); } 
		 $this->db->where('OrgnizationId',$orgid);
		$result =$this->db->get('dashboard_stats');
		return $result->result();
	}


	function get_dashboard_Stats_graph($orgid){
		$date = date('y-m');
        $fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->like('date',$date);
     	}

		 $this->db->select('date,SUM(TotalConnectedCalls) TotalConnectedCalls,SUM(TotalMissedCalls) TotalMissedCalls,SUM(TotalNotConnectedCalls) TotalNotConnectedCalls');
		if($this->usersData['userLevel']=='3') { $this->db->where('AgentNumber',$this->usersData['userPhone']); } 

		  $this->db->group_by('date');
		   $this->db->where('OrgnizationId',$orgid);
		$result =$this->db->get('dashboard_stats');

      // echo $this->db->last_query();

		return $result->result();
	}




	function get_user_Stats_today(){
		     $date = date('y-m-d');
             $this->db->select('userName,agentNumber, count(customerStatus) count, MAX(time) time');
             $this->db->where('date',$date);
             $this->db->where('customerStatus','Connected');
             $this->db->join('users', 'users.userPhone ='.$this->logViewTable.'.agentNumber', 'left');
             $result=$this->db->get($this->logViewTable);
             $result=$result->result();
             return $result;
	}

	function rescent_activities()
	{
         $date = date('y-m-d');
        // $date = '2015-03-01';
		$organizationId =$this->organizationId;
		$where = array(
			'u.organizationId' => $organizationId,
			'n.removed' => '0'
			);
		$this->db->select('n.notes,n.statusTime,u.userName,n.leadsId,l.name,d.detail');
		$this->db->join('users u','u.id=n.userId','left');
		$this->db->join('leads l','l.id=n.leadsId','left');
		$this->db->join('leadStatusData d','n.status=d.id','left');
		$this->db->from('notes n');
		$this->db->where($where);
		$this->db->like('n.statusTime',$date);
		$this->db->order_by('n.id','desc');
		//$this->db->limit(7);
		$query = $this->db->get();
		$data = $query->result_array();
		//echo $this->db->last_query();
		return $data;
	}
// end of stats 	
	
	/*
	function getAllLogcsv()
		{
			$result=$this->db->get($this->logViewTable);
			if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
			$res=$result->result_array();
			return $res;
		}
		
		function getMissedLogcsv()
		{
			$this->db->from($this->logViewTable);
			$this->db->where('customerStatus','Missed');
			if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
			$result=$this->db->get();
			$res=$result->result_array();
			return $res;
		}
		
		function getConnectedLogcsv()
		{
			$this->db->from($this->logViewTable);
			$this->db->where('customerStatus','Connected');
			if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }    // Condition for Agent Mapping
			$result=$this->db->get();
			$res=$result->result_array();
			return $res;
		}*/
	
	function getAllLogcsv()
	{

	  
	$type ='All';
	//echo "hhh";
          $this->csvCallDownload($type);
         
    }
 
   function getMissedLogcsv(){
  	       $type ='Missed';
          $this->csvCallDownload($type);  		
  } 


	function getConnectedLogcsv()
	{

 $type ='Connected';
          $this->csvCallDownload($type);  

	}
	
	
		function csvCallDownload($type){
			
			//die('aa');
       
                 $now = date("Y-m-d-H:i:s");
           
                 $this->load->library('excel');

		     //----------------------
	
          ini_set('memory_limit', '1024M');

          if($type=='All' || $type=='Missed' || $type=='Connected'){
       //  $this->db->select('date,time,logId,leadId,customerNumber,customerStatus,ivrType,field_time_1 as callTransferDuration,field_varchar_1 as calledNumber,agentNumber,field_varchar_8 as CompanyName');
         $result= $this->get_titles_by_orgid();
		$select = "";
		$count =1;
		 foreach($result as $result){
		$field= $result['field'];
			
		 $title= $result['title'];
		
		 
		 if($count==1){
		 	$select =$field;
		 }else{
		 	$select .=', '.$field.' as '.$title;
		 }
		 
		 $count ++;
		 }
		 
		   $select .=',users.userName as AgentName';
       
		$this->db->select($select);
          
          	if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }
          
        
              $this->db->from($this->logViewTable);   
              $this->db->join('users',$this->logViewTable.'.agentNumber=users.userPhone');   
                    if($type!='All')
	           $this->db->where('customerStatus',$type);
		
		$query=$this->db->get();

		//echo $this->db->last_query();
		//die();
		//var_dump($query->result());
		//die();

	}
    
        $this->excel->getProperties()->setTitle("export")->setDescription("none");
 
        $this->excel->setActiveSheetIndex(0);
 
        // Field names in the first row
         $fields = $query->list_fields();
		// var_dump($fields);
		//die();
        $col = 0;
        foreach ($fields as $field)
        {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
 
        // Fetching the table data
        $row = 2;
        foreach($query->result() as $data)
        {
            $col = 0;
            foreach ($fields as $field)
            {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                $col++;
            }
 
            $row++;
        }
 
        $this->excel->setActiveSheetIndex(0);
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        if($type=='All' || $type=='Missed' || $type=='Connected'){
        header('Content-Disposition: attachment;filename="'.$type.'Log_Inbound_'.date('dMy').'.xls"');
    }
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');
}

function get_titles_by_orgid(){
		
		$result=$this->db->query("SELECT field,title FROM list_view WHERE organizationId = ".$this->usersData['organizationId']);
       return $result = $result->result_array();
	}
		
	
	
	function getMissedCalls($limit,$start) {
		$this->db->from($this->logViewTable);
		$this->db->where('customerStatus','Missed');
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
	    $this->db->order_by('logId','desc');
		$this->db->group_by('logId');
		if($start=='')
			$this->db->limit($limit);
		else
			$this->db->limit($limit,$start);
		$result=$this->db->get();
		$res=$result->result_array();
		return $res;
	}
	function getMissedCallsSearch($limit,$start,$customer,$agent,$datefrom,$dateto,$ivr) {
        $this->db->from($this->logViewTable);
        $this->db->where('customerStatus','Missed');
        $this->db->order_by('logId','desc');
        if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
        if($customer!="") {
            $this->db->where('customerNumber',$customer);
        }
        if($datefrom!="" && $dateto!="")
        {
            
            $this->db->where('date >=', $datefrom);
           $this->db->where('date <=', $dateto);
        }    
        
        if($agent!="")
        {
            $this->db->where('agentNumber',$agent);
        }    
        
        
        if($ivr!="")
        {
            $this->db->where('ivrType',$ivr);
        }    
        
        
        if($start=='')
            $this->db->limit($limit);
        else
            $this->db->limit($limit,$start);
        $result=$this->db->get();
        $res=$result->result_array();
        return $res;
    }

	function getAllCallsSearch($limit,$start,$customer,$agent,$datefrom,$dateto,$ivr) {
         $this->db->from($this->logViewTable);
         $this->db->order_by('logId','desc');
        if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
        if($customer!="") {
            $this->db->where('customerNumber',$customer);
        }
        if($agent!="")
        {
            $this->db->where('agentNumber',$agent);
        }    

        if($datefrom!="" && $dateto!="")
        {
            
            $this->db->where('date >=', $datefrom);
           $this->db->where('date <=', $dateto);
        }    
        if($ivr!="") {
            $this->db->where('ivrType',$ivr);
        }
        //$this->dbs->group_by('id');
        if($start=='')
            $this->db->limit($limit);
        else
            $this->db->limit($limit,$start);
        $result=$this->db->get();
        $res=$result->result_array();
        return $res;
    }

	function getAllCalls($limit,$start) {
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
		$this->db->order_by('logId','desc');
		$this->db->group_by('logId');
		if($start=='')
			$this->db->limit($limit);
		else
			$this->db->limit($limit,$start);
		$result=$this->db->get();
		$res=$result->result_array();	
		//$this->db->last_query();	
		return $res;
	}

	function getConnectedCallsCount($search) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		$this->db->where('customerStatus','Connected');
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }  // Condition for Agent Mapping
		$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
	}

	function getMissedCallsCount($search) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		$this->db->where('customerStatus','Missed');
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
		$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
	}
	
	function getAllCallsCount($search)
	{
		$this->db->select('count(*) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
		$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
	}
	
	function getAllCallsInCountByDate($date) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','I');
		$this->db->where('customerStatus','NotConnected');
		$fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->where("date_format(date,'%Y-%m')",$date);
     	}
		$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
		
	}
	
	
	function getAllCallsInCountByDate_report() {
		$date =date('y-m-d');
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from('logs_list_view_27');
		//if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','I');
		$this->db->where('customerStatus','NotConnected');		
		$this->db->where("date",$date);
    	$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
		
	}
	
	function getConnectedCallsInCountByDate($date) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','I');
		$this->db->where('customerStatus','Connected');

		$fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->where("date_format(date,'%Y-%m')",$date);
     	}
		$result = $this->db->get();
		$res = $result->row();
		//echo $this->db->last_query();
		return $res->callCount;
	}
	
	
	 function getConnectedCallsInCountByDate_report() {
	 	$date =date('y-m-d');
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from('logs_list_view_27');
		//if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','I');
		$this->db->where('customerStatus','Connected');		
		$this->db->where("date",$date);     	
		$result = $this->db->get();
		$res = $result->row();
		//echo $this->db->last_query();
		return $res->callCount;
	}
	
	
	function getMissedCallsInCountByDate($date) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','I');
		$this->db->where('customerStatus','Missed');
		$fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->where("date_format(date,'%Y-%m')",$date);
     	}
		$result=$this->db->get();
		$res=$result->row();
		return $res->callCount;
	}
	
	function getMissedCallsInCountByDate_report() {
		$date =date('y-m-d');
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from('logs_list_view_27');
		//if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','I');
		$this->db->where('customerStatus','Missed');
		
		$this->db->where("date",$date);
     	
		$result=$this->db->get();
		$res=$result->row();
		return $res->callCount;
	}

	function getAllCallsOutCountByDate($date) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','O');
		$this->db->where('customerStatus','NotConnected');
		$fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->where("date_format(date,'%Y-%m')",$date);
     	}
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
		$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
		
	}
	
	
	function getAllCallsOutCountByDate_report() {
		$date =date('y-m-d');
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from('logs_list_view_27');
		//if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','O');
		$this->db->where('customerStatus','NotConnected');
		
		$this->db->where("date",$date);
     	
	//	if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
		$result = $this->db->get();
		$res = $result->row();
		return $res->callCount;
		
	}
	
	function getConnectedCallsOutCountByDate($date) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','O');
		$this->db->where('customerStatus','Connected');
		$fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->where("date_format(date,'%Y-%m')",$date);
     	}
		$result = $this->db->get();
		//echo $this->db->last_query();
		$res = $result->row();
		return $res->callCount;
	}
	
	function getConnectedCallsOutCountByDate_report() {
		$date =date('y-m-d');
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from('logs_list_view_27');
	//	if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','O');
		$this->db->where('customerStatus','Connected');		
		$this->db->where("date",$date);    	
		$result = $this->db->get();
		//echo $this->db->last_query();
		$res = $result->row();
		return $res->callCount;
	}
	
	function getMissedCallsOutCountByDate($date) {
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from($this->logViewTable);
		if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','O');
		$this->db->where('customerStatus','Missed');
		$fromDate = $this->input->post('fromDate');
		$toDate =$this->input->post('toDate');
		if(!empty($fromDate) && !empty($toDate)){
           $this->db->where("date >=",$fromDate);
            $this->db->where("date <=",$toDate);
		}else{
		$this->db->where("date_format(date,'%Y-%m')",$date);
     	}
		$result=$this->db->get();
		$res=$result->row();
		return $res->callCount;
	}


    function getMissedCallsOutCountByDate_report() {
    	$date =date('y-m-d');
		$this->db->select('count(customerStatus) as callCount');
		$this->db->from('logs_list_view_27');
		//if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); } 
		$this->db->where('ivrType','O');
		$this->db->where('customerStatus','Missed');
		$this->db->where("date",$date);    	
		$result=$this->db->get();
		$res=$result->row();
		return $res->callCount;
	}



	function getHighestDurationConnectedCallsByDate($date)
	{
		$this->db->select_max('callTransferDuration');
		$this->db->from('callLogs');
		$this->db->where('customerStatus','Connected');
		$this->db->where('date',$date);
		$query=$this->db->get();
		$data=$query->result_array();
		return ($data)?$data:false;
	}
	function getLowestestDurationConnectedCallsByDate($date)
	{
		$this->db->select_min('callTransferDuration');
		$this->db->from('callLogs');
		$this->db->where('customerStatus','Connected');
		$this->db->where('date',$date);
		$this->db->where('callTransferDuration !=','0:00:00');
		$query=$this->db->get();
		$data=$query->result_array();
		return ($data)?$data:false;
	}
	function getAverageDurationConnectedCallsByDate($date)
	{
		$this->db->select('callTransferDuration');
		$this->db->from('callLogs');
		$this->db->where('customerStatus','Connected');
		$this->db->where('date',$date);
		$this->db->where('callTransferDuration !=','0:00:00');
		$query=$this->db->get();
		$data=$query->result_array();
		$count=0;
		$time=0;
		foreach($data as $value){
			$times=explode(':',$value['callTransferDuration']);
			if($times[0]!='0'){
				$time=$time+($times[1]*3600);
			}
			if($times[1]!='0'){
				$time=$time+($times[1]*60);
			}
			if($times[2]!='0'){
				$time=$time+$times[2];
			}
			$count++;
		}
		$seconds=ceil($time/$count);
		return ($seconds)?$seconds:false;
	}

	

	function getConnectedCallsSearch($limit,$start,$customer,$agent,$datefrom,$dateto,$ivr)
    {
        $this->db->from($this->logViewTable);
        $this->db->where('customerStatus','Connected');
        $this->db->order_by('logId','desc');
        if($this->usersData['userLevel']=='3') { $this->db->where('agentNumber',$this->usersData['userPhone']); }   // Condition for Agent Mapping
        if($customer!="")
        {
            $this->db->where('customerNumber',$customer);
        }
        if($datefrom!="" && $dateto!="")
        {
            //$this->db->where('date',$date);
            //$this->db->where("date BETWEEN $datefrom AND $dateto");
            $this->db->where('date >=', $datefrom);
           $this->db->where('date <=', $dateto);
        }    
        
        if($agent!="")
        {
            $this->db->where('agentNumber',$agent);
        }
        if($ivr!="")
        {
            $this->db->where('ivrType',$ivr);
        }    

        
        $this->db->group_by('logId');
        
        if($start=='')
            $this->db->limit($limit);
        else
            $this->db->limit($limit,$start);
        $result=$this->db->get();
        $res=$result->result_array();
        return $res;
    }
	
	 function getAdvanceLogsData($customer,$agent,$datefrom,$dateto,$page='',$ivr)
    {
        if($customer!=''){
            $this->db->where('customerNumber',$customer);
        }
        if($page!=''){
                $this->db->where('customerStatus',$page);
            }
        if($datefrom!="" && $dateto!="")
        {
            //$this->db->where('date',$date);
        //$this->db->where("date BETWEEN $datefrom AND $dateto");
            $this->db->where('date >=', $datefrom);
           $this->db->where('date <=', $dateto);
        }    
        if($agent!="")
        {
            $this->db->where('agentNumber',$agent);
        }        
        if($ivr!="")
        {
            $this->db->where('ivrType',$ivr);
        }        
        
        $query=$this->db->get($this->logViewTable);//print_r($this->db->last_query());exit;
         //echo $this->db->last_query();
        //exit();

        return $query->num_rows();
    }
	public function getAgentNumberById($id) {
		$resQuery = $this->db->select('userPhone')->get_where('users',
					array('id' => $id)
				);
		$res = $resQuery->row();
		if($res) {
			return $res->userPhone;
		}
		return 'no_phone_defined';
	}
	function getAgentList($userdata) {
		if($userdata['userLevel'] == '3') {
			$resQuery = $this->db->select('userPhone')->get_where('users',
					array('id' => $userdata['id'])
				);
			return $resQuery->row()->userPhone;
		}
		$resQuery = $this->db->select('DISTINCT `userPhone`,`userName`', FALSE)->get_where('users',
				array('organizationId' => $userdata['organizationId'],'userStatus' =>'1','userLevel' => '3')
			);
		/*
		$result = array();
				foreach ($resQuery->result() as $phone) {
					$result[] = $phone->userPhone;
				}*/
		
		return $result=$resQuery->result();
	}
	function updatePaymentByTicket()
	{
		$this->dbs->set('remarks',$_POST['remark']);
		$this->dbs->set('booking_status',$_POST['booking']);
		$this->dbs->set('payment_status',$_POST['payment']);
		$this->dbs->set('special_requests',$_POST['specialTicket']);
		$this->dbs->where('ticketId',$_POST['ticketId']);
		$query=$this->dbs->update('zostel_inbound_c2c_ticket_details_view');
	}
	function insertArrayValue(){		
		
		$array=array( 
			'customer_call_duration'=>'0:02:22',
			'customerNumber' => '+917967772300',
			'customerStatus' =>'Connected',
			'agentConnectedTo' =>'+919711801134',
			'agent_status' => 'Connected',
			'callRecordingurl' =>'http://www.smartivr.in/sounds/voicemail/download/31cad5e4-66b8-4c1a-8333-b2140b4c3664_1',
			'ivrType' =>'Inbound');
		$this->db->insert('callLogs',$array);
	}
}
 ?>
