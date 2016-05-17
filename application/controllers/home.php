<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{
	private $data = array();
	function __construct() {
		parent::__construct();
		$this->load->model('usermodel','',TRUE);
		$this->load->model('logmodel','',TRUE);
		$this->load->model('leadmodel','',TRUE);
		//$this->load->model('reportmodel','',TRUE);
		//$this->load->library('layout');

		
	}
	
	function index() {
      
       
       if($loggedUser = $this->session->userdata('loggedIn')) {
			$this->data['loggedUser'] = $loggedUser;
		} else {
			redirect('user', 'refresh');
		}
		$data = $this->data;
		$data['css']=add_css(array('jquery.dataTables.min.css'));
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.dataTables.min.js','jquery.table2excel.js'));
		$data['js']=put_headers_js();
		
		if(!$this->logmodel->logViewTableExist()) {
			redirect('admin/select_field');
		}

		$loggedUser = $this->data['loggedUser'];
		$orgId = $loggedUser['organizationId'];
		
		//$this->data['latestCallsCount'] = $this->logmodel->getLatestCallsCount(3);
		//$this->data['usage'] = $this->usermodel->getUsesDetailsByOrg($orgId);
		//$this->data['package'] = $this->usermodel->getPackageDetailsByOrg($orgId);
		//$this->data['allLeadsCount'] = $this->leadmodel->getAllLeadsCountCurrentMonth();
		//$this->data['invalidLeadsCount'] = $this->leadmodel->getInvalidLeadsCountMonth($loggedUser['userLevel']);
		//$this->data['AttemptedLeadsCount'] = $this->leadmodel->getAttemptedLeadsCountCurrentMonth($orgId, date('Y-m'));
		//$this->data['SmsVeryfiedLeadsCount'] = $this->leadmodel->getSmsVeryfiedLeadsCountMonth();
		//$this->data['newLeadsCount'] = $this->leadmodel->getNewLeadsCountMonth($loggedUser['userLevel'], $loggedUser['id']);
		//$this->data['qualifiedLeadsCount'] = $this->leadmodel->getQualifiedLeadsCountCurrentMonth();
		if($loggedUser['userLevel'] == $this->config->item('MasterAdmin')) {
			$this->data['users'] = $this->usermodel->getAllUsers();
		} else {
			$this->data['users'] = $this->usermodel->getAllUsersByOrg($orgId);
		//	var_dump($this->data['users']);
		}
		$lastRecord = array();
		$count = 0;
		if($this->data['users']) {
			foreach($this->data['users'] as $cs) {
				array_push( $lastRecord, $this->usermodel->getLastRecord($cs->id) );
			}
		}
		$this->data['lastRecord'] = $lastRecord;
		$this->data['active'] = 'dashboard';
		$this->data['selectedDate'] = date("Y-m");
		
		$this->data['InConnectedcallCount'] = $this->logmodel->getConnectedCallsInCountByDate($this->data['selectedDate']);
		$this->data['InMissedcallCount'] = $this->logmodel->getMissedCallsInCountByDate($this->data['selectedDate']);
		$this->data['InAllcallsCount'] = $this->logmodel->getAllCallsInCountByDate($this->data['selectedDate']);

		$this->data['OutConnectedcallCount'] = $this->logmodel->getConnectedCallsOutCountByDate($this->data['selectedDate']);
		$this->data['OutMissedcallCount'] = $this->logmodel->getMissedCallsOutCountByDate($this->data['selectedDate']);
		$this->data['OutAllcallsCount'] = $this->logmodel->getAllCallsOutCountByDate($this->data['selectedDate']);
		$this->data['stats'] = $this->logmodel->get_dashboard_Stats($orgId);
		$this->data['stats_graph'] = $this->logmodel->get_dashboard_Stats_graph($orgId);
		$this->data['user_stats'] = $this->logmodel->get_user_Stats_today();
		$this->data['rescent_activities'] = $this->logmodel->rescent_activities();
		
		//var_dump($this->data['stats']);

		$this->load->view('layout/header',$data);
		$this->load->view('dashboard', $this->data);
	}


  
  function mail_reports(){
  	
  	   $msg ="";
		 
		$msg.='<br>Inbound Connected: '.$InConnectedcallCount = $this->logmodel->getConnectedCallsInCountByDate_report();
		//echo "<br>";
		$msg.='<br>Inbound Missed: '.$InMissedcallCount = $this->logmodel->getMissedCallsInCountByDate_report();
		//echo "<br>";
		$msg.='<br>Inbound Not Connected: '.$InAllcallsCount = $this->logmodel->getAllCallsInCountByDate_report();
		//echo "<br>";
		$msg.='<br>Outbound Connected: '.$OutConnectedcallCount = $this->logmodel->getConnectedCallsOutCountByDate_report();
		//echo "<br>";
		$msg.='<br>Outbound Missed: '.$OutMissedcallCount = $this->logmodel->getMissedCallsOutCountByDate_report();
		//echo "<br>";
		$msg.='<br>	Outbound Not Connected: '.$OutAllcallsCount = $this->logmodel->getAllCallsOutCountByDate_report();
		  $stats = $this->logmodel->get_dashboard_Stats(27);
		  
		  $msg.='
		 
		  <table style="border: 1px solid black;">
                                            <thead>
                                            <tr>
                                                <th style="width: 1%" class="text-center">Date</th>
                                                <th style="width: 1%" class="text-center">Name</th>
                                                <th>First Logged In Time</th>
                                                <th class="text-center">Logout Time</th>
                                                <th class="text-center">Total Connected Calls</th>
                                                <th class="text-center">Total Missed Calls</th>
                                                <th class="text-center">Total Not Connected Calls</th>
                                                <th class="text-center">Total logged in time</th>
                                                <th class="text-center">Productive Hours</th>
                                                 <th class="text-center">Not Available</th>
                                                  <th class="text-center">Aux Time</th>
                                           
                                                
                                            </tr>
                                            </thead>
                                            <tbody> ';
                                            
                                   foreach ($stats as $stats) {
                                       
                                            
                              $msg .='             
                                            <tr>
                                                <td style="border: thin solid black">'.$stats->date.'</td>
                                                <td style="border: thin solid black">'.$stats->AgentName.'</td>
                                                <td style="border: thin solid black">'.$stats->FirstLoggedInTime.'</td>
                                                <td style="border: thin solid black">'.$stats->LogoutTime.'</td>
                                                <td style="border: thin solid black">'.$stats->TotalConnectedCalls.'</td>
                                                <td style="border: thin solid black">'.$stats->TotalMissedCalls.'</td>
                                                <td style="border: thin solid black">'.$stats->TotalNotConnectedCalls.'</td>
                                                 <td style="border: thin solid black">'.$stats->TotalLoggedInTime.'</td>
                                                  <td style="border: thin solid black">'.gmdate("H:i:s",$stats->productive_hours).'</td>
                                                <td style="border: thin solid black">'.gmdate("H:i:s",$stats->notAvailable).'</td>
                                                 <td style="border: thin solid black"> '.gmdate("H:i:s",$stats->auxTime).'</td></tr>';
                                             };
                                     $msg .='</tbody>
                                        </table>';
                  
		 
		 //echo $msg;
		  //$msg =
		 
		 
	           
					             $this->load->library('email');
								 $this->email->from('report@knowlarity.com', 'Report'); 
								//Aditi.Mahendra@concentrix.com,Kishore.Mohapatra@concentrix.com,S.Nithya@concentrix.com
							//	 $this->email->to('satish@meetuniversity.com');
								 $this->email->to(array('nameesh.sharma@knowlarity.com','Aditi.Mahendra@concentrix.com','Kishore.Mohapatra@concentrix.com','S.Nithya@concentrix.com'));
								 $this->email->subject('Report');
								 $this->email->message($msg);
								 $this->email->send();
			   
		 
		   	
  }


   function wrap_time(){
    
    $this->logmodel->wrap_time_agents();  // pass these in the cron function 
   }
   
    function wrap_time_agent_sum(){
   //	$UserID=""; 
   $WrapTime = $this->logmodel->wrap_time_agent_sum($AgentNumber,$organizationId);  //pass therse in cron function 
   }


	function get_dashboard_Stats_today(){
		
		$users = $this->usermodel->getAllUsers();
		
		 $date = date('y-m-d');
		//$date = date('2016-04-21');
		
		// check database for this date entry 
		 $check=  $this->logmodel->check_dashboard_stats($date);
		
		if($check=='1'){			
			// delete all entries on that date 
			$this->logmodel->delete_dashboard_stats($date);
		}		
		
		 foreach ($users as $users) {
		 	 $Name = $users->userName;
		 	 $UserID = $users->id;
		 	$AgentNumber = $users->userPhone;
		 	$organizationId = $users->organizationId;
		  	$logged_in_time =$this->logmodel->get_first_logged_in_time($UserID);
		  	$logout_time =$this->logmodel->get_log_out_time($UserID);
		 	 $connected_calls =$this->logmodel->get_total_connected_Calls($AgentNumber,$organizationId);
		 	 $missed_calls =$this->logmodel->get_total_missed_Calls($AgentNumber,$organizationId);
		 	 $notconnected_calls =$this->logmodel->get_total_notconnected_Calls($AgentNumber,$organizationId);
		 	 $total_logged_in_time =$this->logmodel->total_logged_in_time($UserID);
			 $productiveHour=$this->logmodel->productive_hours_agents($UserID);
		     $notAvailable=$this->logmodel->notAvailable_agents($UserID);
		     $auxTime = $this->logmodel->aux_time_agents($UserID);
			 $this->logmodel->wrap_time_agents();
			 $WrapTime = $this->logmodel->wrap_time_agent_sum($AgentNumber,$organizationId);
			// $WrapTime = $this->logmodel->wrap_time();
		 //	  $inbound_talktime =$this->logmodel->get_inbound_total_talktime($AgentNumber,$organizationId);
		 //	  $outbound_talktime =$this->logmodel->get_outbound_total_talktime($AgentNumber,$organizationId);

		 $ArrayData = array('date'=>$date,'AgentNumber'=>$AgentNumber,'user_id'=>$UserID,'AgentName'=>$Name,'OrgnizationId'=>$organizationId,'FirstLoggedInTime'=>$logged_in_time,'LogoutTime'=>$logout_time,'TotalConnectedCalls'=>$connected_calls
		 	,'TotalMissedCalls'=>$missed_calls,'TotalNotConnectedCalls'=>$notconnected_calls,'TotalLoggedInTime'=>$total_logged_in_time,'InboundTotalTalktime'=>$inbound_talktime,'OutboundTotalTalktime'=>$outbound_talktime,'productive_hours'=>$productiveHour,'notAvailable'=>$notAvailable,'auxTime'=>$auxTime,'wrap_time'=>$WrapTime);
		
           $this->logmodel->insert_Stats($ArrayData);
		 }

	}
}
