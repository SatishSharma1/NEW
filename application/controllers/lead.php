<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lead extends CI_Controller  {
	function __construct() {
		parent::__construct();

		$this->load->model('usermodel','',TRUE);
		$this->load->model('leadmodel','',TRUE);
		$this->load->model('smsmodel','',TRUE);
		$this->load->model('pluginmodel','',TRUE);
		//die('aa');
		$this->load->model('emailmodel','',TRUE);
		//$this->load->library('layout');
		$this->load->library('form_validation');
		$this->load->library("pagination");
		$this->load->helper(array('form', 'url'));
	}

	function index() {
		if($this->session->userdata('loggedIn')) {
			redirect('home','refresh');
		} else {
			$data=array();
			if($this->input->cookie('userEmail')) {
				$data=array('userEmail'=>$this->input->cookie('userEmail'),'password'=>$this->input->cookie('password'));
			}
			$this->load->view('user/login',$data);
		}
	}
// By Adil
	function leadUpdateDetail($id) {
		$rest = $this->leadmodel->getLeadDetails($id);
		echo json_encode($rest);
	}

	function getCityByCountryId() {
		$cid=$_POST['cid'];
		$city['name']=$this->leadmodel->getCity($cid);
		$this->load->view('leads/getcity',$city);
	}

	public function getCountryIdByCityName($cityName = '') {
		$res = $this->db->select('countryId')->get_where('city', array('cityName'=>$cityName))->row();
		if($res) {
			echo $res->countryId;
		}else {
			echo "";
		}
	}

	function updateLeadDetail() {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$userid= $data['loggedUser']['id'];
		echo $this->leadmodel->updateLeadData($userid);
	}
// Finished Editing
	function importLead() {	
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$data['tags']=$this->leadmodel->getAllTags();
		$this->form_validation->set_rules('name', 'name','trim|required|xss_clean');
		$this->form_validation->set_rules('SourceName', 'source Name', 'trim|required|xss_clean');			
		if (isset($_POST['submit'])) { 	
			 if($this->form_validation->run() == FALSE) {	
			} else {
				$validsource = $this->leadmodel->addSourceName();
				if($validsource) {
					$checkLeadSourceExist = $this->leadmodel->checkLeadSourceNameExist($validsource);					
					$data['uploadCsv'] = $this->leadmodel->upload_leads_csv($validsource,$data['loggedUser']['organizationId']);
					$importHistoryTagId = $this->leadmodel->addTags();
					$data['leadImportHistory'] = $this->leadmodel->leadImportHistory($importHistoryTagId); 
					//$leadSourceExist = $this->leadmodel->addLeadCountInUsage();
				} 		
				if(!$validsource) {
					$data['sourceNameError']='invalid source name';
				} 
			} 
		}
		$data['active'] = 'importLead';
		$this->layout->view('leads/importLead',$data);	
	}
	
	function allLeadsCounselor($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if($this->config->item('CounslorLevel')!=$data['loggedUser']['userLevel']) {
			redirect('home');
		}
		$counsellor=$data['loggedUser']['id'];
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getAllLeadsCountCounselor($search);//New Model function
		//$data['per_page'] = $limit;
		/**configration of pagination***/
		/*$config["base_url"] = base_url() . "allLeadsCounselor/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);*/
		/***end**/
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		if($search!='key') {
			//$data['additionalParameter']=urldecode($search);
			//$data['getAllLeads'] = $this->leadmodel->getAllLeadsSearchCounselor($config['per_page'],$page,$search);//New Function
			$phone=$email=$status=$source=$city='';
			$data['additionalParameter']=$search;
			$searchdata=explode("+",$search);
			for($i=0;$i<sizeof($searchdata);$i++) {
				$searchfield=explode(":",$searchdata[$i]);
				if($searchfield[0]=='ph') {	
					$phone=$searchfield[1];
				} else if($searchfield[0]=='em') {
					$email=$searchfield[1];
				} else if($searchfield[0]=='so') {
					$source=$searchfield[1];
				} else if($searchfield[0]=='st') {
					$status=$searchfield[1];
				}
				/*else if($searchfield[0]=='co')
				{
					$counsellor=$searchfield[1];
				}*/
				else if($searchfield[0]=='ci') {
					$city=$searchfield[1];
				}
			}
			$data['total']=$this->leadmodel->getAdvanceLeadsData($phone,$email,$source,$status,$counsellor,$city);
		}
		else {
			//$data['getAllLeads'] = $this->leadmodel->getAllLeadsCounselor($config['per_page'],$page);//New Function
			$data['total'] = $this->leadmodel->getAllLeadsCountCoun($search,$counsellor);
		}
		$data['per_page'] = $limit;

		/**configration of pagination***/
		$config["base_url"] = base_url() . "allLeadsCounselor/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/

		if($search!='key') {
			$data['getAllLeads'] = $this->leadmodel->getAdvanceLeads($config['per_page'],$page,$phone,$email,$source,$status,$counsellor,$city);
		} else {
			$data['getAllLeads'] = $this->leadmodel->getAllLeadsCounselor($config['per_page'],$page);
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'alllead';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'allLeads';
		$this->layout->view('leads/counselor/allLead',$data);
	}
	
	function downloadAllLeadcsv()
	{
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		$now = date("Y-m-d-H:i:s");
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=AllLeads-".$now.".csv");		
		$getLeadsData = $this->leadmodel->getAllLeadscsv($orgId);
		echo "Lead ID,Lead Created Time,Lead Name,Lead Phone,Lead City,Lead Status,Lead Source,Lead Notes\n";
		
		foreach($getLeadsData as $getLeads)
		{
			echo $getLeads['id'].",".$getLeads['leadCreatedTime'].",".$getLeads['name'].",".$getLeads['phone'].",".$getLeads['city'].",".$getLeads['status'].",".$getLeads['source'].",".$getLeads['notes']."\n";
		}
		
	}
	
	
	
	function todayLeads($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->gettodayLeadsCount($search);
		$data['per_page'] = $limit;
		
		/**configration of pagination***/
		$config["base_url"] = base_url() . "todayLeads/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
				
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAllLeads'] = $this->leadmodel->gettodayLeadsSearch($config['per_page'],$page,$search);
		} else {
			$data['getAllLeads'] = $this->leadmodel->gettodayLeads($config['per_page'],$page);
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'todayLeads';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'todayLeads';
		$this->layout->view('leads/admin/todayLeads',$data);
	}
	
	function removeLead() {
		$data['loggedUser']=$this->session->userdata('loggedIn'); 
		//$this->leadmodel->DeleteOneLeadUsages($data['loggedUser']['organizationId']);//decrease lead usages value by one
		echo $data['removeLead'] = $this->leadmodel->removeLeads();
		exit;
	}
	  
	function Status() {
			$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.validate.js','jquery.dataTables.bootstrap.js'));
		$data['js']=put_headers_js();

		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$data['userLevelTypes'] = $this->leadmodel->getUserLevel();
		if(isset($_POST['userLevelByAjax'])) {
			$data['usersLeadsStatus'] = $this->leadmodel->getUsersLeadsStatusByUserLevel($_POST['userLevelByAjax']);
			$regs="<option value='0'>Select</option>";
			foreach($data['usersLeadsStatus'] as $reg) { 
				$regs.="<option value=".$reg['id'].">".$reg['detail']."</option>";
			}
			echo $regs; 				
			exit;	
		}
		$this->form_validation->set_rules('userLevel', 'User Level', 'trim|required|xss_clean');		
		$this->form_validation->set_rules('StatusName', 'status name', 'trim|required|xss_clean|callback_CheckDuplicateStatus');
		if(isset($_POST['userStatusDetails'])) {
				if($this->form_validation->run()) {
					if($_POST['userLevel']==$this->config->item('TelecallerLevel')) {
						if($_POST['parentId']==0) {
							$data['leadsStatusError']='Please Select Parent Status';
						} else {
						
							$data['successLeadsStatus'] = $this->leadmodel->InsertLeadsStatus($organizationId);
						}
					}
					if($_POST['userLevel']!=$this->config->item('TelecallerLevel')) {
						if(isset($_POST['parentId'])) {
								if($_POST['parentId']==0) {
									  $data['leadsStatusError']='Please Select Parent Status';
								} else {
									$data['successLeadsStatus'] = $this->leadmodel->InsertLeadsStatus($organizationId);
								}
						}
						if(!isset($_POST['parentId'])) {
							//if parent status not selected create child and parent as same name";
				            $data['successLeadsStatus'] = $this->leadmodel->InsertChildLeadsStatusSameAsParent($organizationId);
						}
					}
				} else {
						//$data['FormError']='Some Thing Went Wrong or you Forgot Fillup all entries.! ';
				}
			}
			$data['manage_status'] = $this->leadmodel->manageStatus($organizationId);
			$data['active'] = 'status';
			//die('aaa');
			 $this->load->view('layout/header',$data);
		     $this->load->view('leads/Status',$this->data);
	}
	
	function CheckDuplicateStatus()	{
		if(isset($_POST['parentId'])) {
			$data=$this->leadmodel->CheckIfStatusexist();
			if($data=='data exist') {
			$this->form_validation->set_message('CheckDuplicateStatus', 'Sorry, This Status Name Already Exist, try again with new .!');
			return FALSE;
			}
			if($data!='data exist') {
			return True;
			}
		}
		if(!isset($_POST['parentId'])) {
			$childData=$this->leadmodel->CheckIfStatusexist();
			$data=$this->leadmodel->CheckIfStatusexistInParent();
			if(($data=='data exist')||($childData=='data exist')) {
				$this->form_validation->set_message('CheckDuplicateStatus', 'Sorry, This Status Name Already Exist, try again with new .!');
			return FALSE;
			}
			if($data!='data exist') {
			return True;
			}
		}
		//return FALSE;
		//return ($data)?true:false;
	}
	
	function MarkStatusInactiveByAjax() {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$id=$_POST['id'];
		echo $flag=$this->leadmodel->MarkStatusInactive($id,$organizationId);
		exit;
	}
	
	function MarkStatusActiveByAjax() {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$id=$_POST['id'];
		echo $flag=$this->leadmodel->MarkStatusActive($id,$organizationId);
		exit;
	}
	
	/**********Profile Section ***********************/
	function profile($leadId=0)	{

        $data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();

		//die($leadId);
		// dp($this->leadmodel->getRecordingTimeline($leadId));
		//$leadId=2;
		// Fake Variables to  ingnore error in veiw
		$data['recordingTimeline'] = $this->leadmodel->getRecordingTimeline($leadId);
		//var_dump($data['recordingTimeline']);
		$data['sendSmsPermission'] = false;
		$data['sendEmailPermission'] = false;
		// end faking
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		//$data['userCampaign']=$this->leadmodel->getuserCampaignByLeadId($leadId,$orgId);
		//$data['userCampaignTransfered']=$this->leadmodel->getuserCampaignTransferedByCampaignId($leadId,$orgId);
		//$data['userCampaignTransferedByOrg']=$this->leadmodel->getuserCampaignTransferedByOrgId($leadId,$orgId);
		$data['leadsInfo']=$this->leadmodel->getLeadInfoById($leadId);
		//var_dump($data['leadsInfo']);
		if(!empty($data['leadsInfo']->city)){
			$data['selectedCountry']=$this->leadmodel->getCountryByCityName($data['leadsInfo']->city);
		}		
		$data['country']=$this->leadmodel->getCountry();
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		if($leadId==0 OR $orgId != $data['leadsInfo']->organizationId) {
			//redirect('allLeads','refresh');
		}
		$data['leadId'] = $leadId;
		if(isset($_POST['submitUpdateLead'])) {
			//var_dump($_POST);
			//die();
			if($this->input->post('tags')!='') {
				$importHistoryTagId = $this->leadmodel->addTags();
				$this->leadmodel->updateLeadTags($leadId,$importHistoryTagId);
			}
			$this->leadmodel->updateLeadInfo($leadId);
			$this->leadmodel->updateLeadExtentedInfo($leadId);
			//$this->leadmodel->getAllCampaignInterestTrackerDetails($leadId,$orgId);
			$this->leadmodel->insertLeadNotes($leadId,$data['loggedUser']['id']);
			if($_POST['editStatus']=='3' || $_POST['editStatus']=='12' || $_POST['editStatus']=='19') {
				$limit = $this->smsmodel->checkOrgSmsLimit($data['loggedUser']['organizationId']);//returns true or false
				$smsApi = $this->smsmodel->checkSmsApiDetails($data['loggedUser']['organizationId']);
				if($smsApi) {
					if($limit) {
						$username = $smsApi->username;
						$pwd= $smsApi->password;
						$msg='MeetUniv.Com-Best Place to Meet Universities ! Thanks for registering , we tried to reach out to you but were unable to get through. You can get in touch Email: connect@meetuniv.com Phone: 08375034794 MeetUniv.Com';
						$msg = urlencode($msg);
						$mobile=$_POST['editPhone'];
						$mobile=urlencode($mobile);
						$content=file_get_contents("http://api.unicel.in/SendSMS/sendmsg.php?uname=meetuni&pass=".$pwd."&send=meetus&dest=91".$mobile."&msg=".$msg);
					} else {
						echo "You Have Crossed Limit";
					}
				} else {
					echo "You have not SMS Plugin";
				}
			}
			//$this->leadmodel->updateInterestTrakerByAjax($leadId);
			redirect('lead/profile/'.$leadId , 'refresh');
		}
		if($this->input->post('submitDoc')) {
			$data['uploadDocMsg'] = $this->leadmodel->uploadDoc($leadId);
			redirect('lead/profile/'.$leadId , 'refresh');
		}
		/*if($this->input->post('submitAvatar'))
		{
			$this->leadmodel->uploadPic($leadId);
		}*/
		//fetching optedTime info of particulate lead
		//$data['optedTime'] = $this->leadmodel->getObdInfoById($data['leadsInfo']->phone);
		//fetching optedTime info of particulate lead
		$data['optedTime'] = $this->leadmodel->getObdInfoById($leadId);
		//echo "LEADID = ".$leadId;
		$data['lxE']=$this->leadmodel->getLeadExternalProfileInfo($leadId);
		//$data['tpt']=$this->leadmodel->getThirdPartyInfo($leadId);
		$data['newNotes']=$this->leadmodel->getAllNotes($leadId);
		//print_r($data['newNotes']);
		$tn=$pn=$on=$tcn=$pcn=$ocn=$tsn=$psn=$osn=$ten=$pen=$oen=array();

		/****Notes*********/
		foreach($data['newNotes'] as $nts) {
			$currentDate = date('Y-m-d');
			$date = date('Y-m-d',time() - 60*60*24);
			if(strpos($nts->statusTime,$currentDate)!==false)
				array_push($tn,$nts);
			else if(strpos($nts->statusTime,$date)!==false)
				array_push($pn,$nts);
			else// if($nts->statusTime<$date)
				array_push($on,$nts);
			//$data['todayNotes'] = $this->leadmodel->getTodayNotes($leadId);
			//$data['pastDayNotes'] = $this->leadmodel->getPastDayNotes($leadId);
			//$data['olderNotes'] = $this->leadmodel->getOlderNotes($leadId);
			//print_r($data['olderNotes']);exit;
			/****CallNotes**********/
			if($nts->type==1) {
				if(strpos($nts->statusTime,$currentDate)!==false)
					array_push($tcn,$nts);
				else if(strpos($nts->statusTime,$date)!==false)
					array_push($pcn,$nts);
				else// if($nts['statusTime']<$date)
					array_push($ocn,$nts);
			}
			// $data['todayCallNotes'] = $this->leadmodel->getTodayCallNotes($leadId);
			// $data['pastDayCallNotes'] = $this->leadmodel->getPastDayCallNotes($leadId);
			// $data['olderCallNotes'] = $this->leadmodel->getOlderCallNotes($leadId);
			/****SMSNotes**********/
			else if($nts->type==2) {
				if(strpos($nts->statusTime,$currentDate)!==false)
					array_push($tsn,$nts);
				else if(strpos($nts->statusTime,$date)!==false)
					array_push($psn,$nts);
				else// if($nts->statusTime<$date)
					array_push($osn,$nts);
			}
			// $data['todaySmsNotes'] = $this->leadmodel->getTodaySmsNotes($leadId);
			// $data['pastDaySmsNotes'] = $this->leadmodel->getPastDaySmsNotes($leadId);
			// $data['olderSmsNotes'] = $this->leadmodel->getOlderSmsNotes($leadId);
			/****EmailNotes******/
			else// if($nts->type==3) {
				if(strpos($nts->statusTime,$currentDate)!==false)
					array_push($ten,$nts);
				else if(strpos($nts->statusTime,$date)!==false)
					array_push($pen,$nts);
				else// if($nts->statusTime<$date)
					array_push($oen,$nts);
			//}
			// $data['todayEmailNotes'] = $this->leadmodel->getTodayEmailNotes($leadId);
			// $data['pastDayEmailNotes'] = $this->leadmodel->getPastDayEmailNotes($leadId);
			// $data['olderEmailNotes'] = $this->leadmodel->getOlderEmailNotes($leadId);
		}
		$data['todayNotes']=$tn;
		$data['pastDayNotes']=$pn;
		$data['olderNotes']=$on;
		$data['todayCallNotes']=$tcn;
		$data['pastDayCallNotes']=$pcn;
		$data['olderCallNotes']=$ocn;
		$data['todaySmsNotes']=$tsn;
		$data['pastDaySmsNotes']=$psn;
		$data['olderSmsNotes']=$osn;
		$data['todayEmailNotes']=$ten;
		$data['pastDayEmailNotes']=$pen;
		$data['olderEmailNotes']=$oen;
		/*echo "<pre>";
		print_r($data['olderNotes']);
		print_r($data['olderCallNotes']);
		print_r($data['olderSmsNotes']);
		print_r($data['olderEmailNotes']);
		exit;*/
		$data['notesCount'] = $this->leadmodel->getNotesCount($leadId);
		$data['notesCallCount'] = $this->leadmodel->getCallNotesCount($leadId);
		$data['notesSmsCount'] = $this->leadmodel->getSmsNotesCount($leadId);
		$data['notesEmailCount'] = $this->leadmodel->getEmailNotesCount($leadId);
		$data['sendSmsPermission'] = $this->smsmodel->checkOrgSmsLimit($orgId);
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		//$data['sendEmailPermission'] = $this->emailmodel->checkOrgEmailLimit($orgId);
		//$data['emailTemplates'] = $this->emailmodel->getApprovedEmailTemplates($orgId);//getting all Email template of organization
		$data['tags']=$this->leadmodel->getAllTags();
		$data['leadTags'] = $this->leadmodel->getLeadsTags($leadId);
		$data['lookupCity'] = $this->leadmodel->getLookupCityById($leadId);
		$data['tpd'] = Null;//echo "<pre>";print_r($data);exit;
		$this->load->view('layout/header',$data);
		$this->load->view('leads/profile',$data);
	}
	/**************End*******/
	/*****CounslerLeads for admin******/
	function counselorleads($search='',$limit='',$page='') {
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getCounselorLeadsCount($search);
		$data['per_page'] = $limit;
		$data['loggedUser']=$this->session->userdata('loggedIn');
		
		/**configration of pagination***/
		$config["base_url"] = base_url() . "counselor/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		if($this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home' , 'refresh');	
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		$data['pagename'] = 'freshlead';
		if($search!='key') {
			//$data['getfreshLeads'] = $this->leadmodel->getCounsleorLeadsSearch($config['per_page'],$page,$search);
			$data['getfreshLeads'] = $this->leadmodel->getCounselorLeadsSearch($config['per_page'],$page,$search);
			//print_r($data['getfreshLeads']);
			//echo "exit";exit;
		} else {
			$data['getfreshLeads'] = $this->leadmodel->getCounselorLeads($config['per_page'],$page);
		}
		$data["links"] = $this->pagination->create_links();
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		$data['active'] = 'counselorLead';
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/counselorLead',$data);
		}
	}
	
	
	/************ Telecaller Leads Script********************/
	function telecallerleads($search='',$limit='',$page='') {
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getfreshLeadsCount($search);
		$data['per_page'] = $limit;
		$data['loggedUser']=$this->session->userdata('loggedIn');
		
		/**configration of pagination***/
		$config["base_url"] = base_url() . "telecaller/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		if($this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home' , 'refresh');	
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		$data['pagename'] = 'freshlead';
		if($search!='key') {
			$data['getfreshLeads'] = $this->leadmodel->getfreshLeadsSearch($config['per_page'],$page,$search);
		} else {
			$data['getfreshLeads'] = $this->leadmodel->getfreshLeads($config['per_page'],$page);
		}
		$data["links"] = $this->pagination->create_links();
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		$data['active'] = 'telecallerLead';
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/telecallerLead',$data);
		}
	}
	/***********End***************/
	/********** Fresh Leads Script ***********************/
	function freshLead($search='',$limit='',$page='') {
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getfreshLeadsCount($search);
		$data['per_page'] = $limit;

		/**configration of pagination***/
		$config["base_url"] = base_url() . "freshLead/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		$this->load->model('smsmodel');
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		if($this->config->item('TelecallerLevel')!=$data['loggedUser']['userLevel'] &&  $this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home' , 'refresh');	
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		$data['pagename'] = 'freshlead';
		if($search!='key'){
			$data['getfreshLeads'] = $this->leadmodel->getfreshLeadsSearch($config['per_page'],$page,$search);
		} else {
			$data['getfreshLeads'] = $this->leadmodel->getfreshLeads($config['per_page'],$page);
		}
		$data["links"] = $this->pagination->create_links();
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'freshLead';
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		if($this->config->item('TelecallerLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/telecaller/freshLead',$data);
		}
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/freshLead',$data);
		}
	}
	/**************End*******/
	
	/********** Invalid Leads Script ***********************/
	function invalidLead($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		//echo $this->config->item('TelecallerLevel');exit;
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getInvalidLeadsCount($search, $activeUserLevel);
		$data['per_page'] = $limit;
		$data['loggedUser']=$this->session->userdata('loggedIn');
		
		/**configration of pagination***/
		$config["base_url"] = base_url()."invalidLead/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);// get status
		//$data['getinvalidLeads'] = $this->leadmodel->getinvalidLeads($config['per_page'],$page);//for telecaller
		//$data['getinvalidLeadsOfCouncelor'] = $this->leadmodel->getinvalidLeadsOfCouncelor();//for councelor
		//$data['getinvalidLeadsOfAdmin'] = $this->leadmodel->getinvalidLeadsOfAdmin();//for admin
		//$data['pagename'] = 'invalidlead';
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getinvalidLeads'] = $this->leadmodel->getInvalidLeadsSearch($config['per_page'],$page,$search, $activeUserLevel);//for invalidLead
			//$data['getinvalidLeadsOfCouncelor'] = $this->leadmodel->getinvalidLeadsOfCouncelorSearch($config['per_page'],$page,$search);//for councelor
			//$data['getinvalidLeadsOfAdmin'] = $this->leadmodel->getinvalidLeadsOfAdminSearch($config['per_page'],$page, $search);//for admin
		} else {
			$data['getinvalidLeads'] = $this->leadmodel->getinvalidLeads($config['per_page'],$page,$activeUserLevel);//for invalidLead
			//$data['getinvalidLeadsOfCouncelor'] = $this->leadmodel->getinvalidLeadsOfCouncelor($config['per_page'],$page);//for councelor
			//$data['getinvalidLeadsOfAdmin'] = $this->leadmodel->getinvalidLeadsOfAdmin($config['per_page'],$page);//for admin
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'invalidLead';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'invalidLead';
		if($this->config->item('TelecallerLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/telecaller/invalidLead',$data);
		}
		if($this->config->item('CounslorLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/counselor/invalidLead',$data);
		}
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/invalidLead',$data);
		}
	}
	/**************End*******/
	
	/********** new Leads Script ***********************/
	function newLead($search='',$limit='',$page='') { // where asigned id = logged in id
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getNewLeadsCount($search, $data['loggedUser']['userLevel'], $data['loggedUser']['id']);
		$data['per_page'] = $limit;
		
		/**configration of pagination***/
		$config["base_url"] = base_url() . "newLead/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		if($this->config->item('CounslorLevel')!=$data['loggedUser']['userLevel']) {
			redirect('home' , 'refresh');	
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		//$data['getNewLeads'] = $this->leadmodel->getnewLeads($data['loggedUser']['id']);
		//$data['pagename'] = 'newlead';
		if($search!='key') {
			$data['getNewLeads'] = $this->leadmodel->getNewLeadsSearch($data['loggedUser']['id'],$config['per_page'],$page,$search);
		} else {
			$data['getNewLeads'] = $this->leadmodel->getNewLeads($data['loggedUser']['id'],$config['per_page'],$page);
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'newlead';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'newLead';
		if($this->config->item('CounslorLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/counselor/newLead',$data);	
		}
	}
	/**************End*******/
	
	/********** new Leads Script ***********************/
	function attemptedCouselorLeads($search='',$limit='',$page='') { // where asigned id = logged in id
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getNewLeadsAttemptedCount($search, $data['loggedUser']['userLevel'], $data['loggedUser']['id']);
		$data['per_page'] = $limit;
		
		/**configration of pagination***/
		$config["base_url"] = base_url() . "attemptedCounselor/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		if($this->config->item('CounslorLevel')!=$data['loggedUser']['userLevel']) {
			redirect('home' , 'refresh');	
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getNewLeads'] = $this->leadmodel->getNewLeadsAttemptedSearch($data['loggedUser']['id'],$config['per_page'],$page,$search);
		} else {
			$data['getNewLeads'] = $this->leadmodel->getNewAttemptedLeads($data['loggedUser']['id'],$config['per_page'],$page);
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'counselorAttemptedNewlead';
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;
		$data['active'] = 'attemptedCounselor';
		if($this->config->item('CounslorLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/counselor/newLead',$data);	
		}
	}
	/**************End*******/

	
	/********** attempted Leads Script ***********************/
	function attemptedLeads($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$data['total'] = $this->leadmodel->getAttemptedLeadsCount($search, $activeUserLevel);//exit;
		$data['per_page'] = $limit;
		
		/**configration of pagination***/
		$config["base_url"] = base_url()."attemptedLeads/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		/* if($this->config->item('TelecallerLevel')!=$data['loggedUser']['userLevel'] && $this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home' , 'refresh');	
		} */
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		//$data['getAttemptedLeads'] = $this->leadmodel->getAttemptedLeads($data['loggedUser']['id']);
		//$data['pagename'] = 'attemptedlead';
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAttemptedLeads'] = $this->leadmodel->getAttemptedLeadsSearch($config['per_page'],$page,$search, $activeUserLevel);
		} else {
			$data['getAttemptedLeads'] = $this->leadmodel->getAttemptedLeads($config['per_page'],$page,$activeUserLevel);
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'attemptedlead';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'attemptedLead';
		if($this->config->item('TelecallerLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/telecaller/attemptedLeads',$data);	
		}
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/attemptedLeads',$data);
		}
	}
	/**************End*******/
	//lead profile update individual entries starts
	function updateCityByAjax() {
		$city=$this->leadmodel->updateCityByAjax();
		exit;
	}
	
	function updateFullNameByAjax() {
		$FullName=$this->leadmodel->updateFullNameByAjax();
		exit;
	}
	
	function updatePhoneByAjax() {
		$phone=$this->leadmodel->updatePhoneByAjax();
		exit;
	}
	
	function updateEmailByAjax() {
		$Email=$this->leadmodel->updateEmailByAjax();
		exit;
	}
	
	function smsMultiLead() {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$leads = explode(',',$this->input->post('leads'));
		$leads = array_unique($leads);
		$phoneNo = $this->leadmodel->getPhoneByIds($leads);
		$successCount = 0;
		$failureCount = 0;
		foreach($phoneNo as $ph) {
			$mobile = $ph['phone'];
			$orgId = $data['loggedUser']['organizationId'];
			$msg = $this->input->post('smsId');
			$res = $this->smsmodel->sendSMS($orgId, $mobile, $msg);
			$this->smsmodel->saveSMSNote($ph['id'], urldecode($msg));
			if(!$res) {
				$successCount++;
			} else {
				$failureCount++;
			}
		}
		$msg ='';
		if($successCount) {
		$msg.=" ".$successCount." SMS sent successfully";
		}
		if($failureCount) {
			$msg.=" ".$failureCount.' Failed Because of exceed of package.';
		}
		echo $msg;
	}
	
	function deleteMultiLead() {
		$leads = explode(',',$this->input->post('leads'));
		$leads = array_unique($leads);
		$data['loggedUser']=$this->session->userdata('loggedIn'); 
		$this->leadmodel->DeleteOneLeadUsages($data['loggedUser']['organizationId'],count($leads));//decrease lead usages value
		$this->db->where_in('id',$leads);
		$this->db->update('leads',array('removed'=>'1'));
	}
	
	function transferMultiLead() {
		date_default_timezone_set("Asia/Kolkata"); 
		$currentTime=date('Y-m-d H:i:s');
		$data = array(
				'leadAssignedID'=>$this->input->post('counselorId'),
				'leadAssignedTime'=>$currentTime,
				'leadUpdatedTime'=>$currentTime
				);
		$leads = explode(',',$this->input->post('leads'));
		$this->db->where_in('id',$leads);
		$this->db->update('leads',$data);
	}
	
	function getExtendedInfo() {
		$leadsId = $this->input->get('leadId');
		$this->db->select('interestedCountry,lastQualification,interestedCourse,lastPercentage,bestCallTime');
		$this->db->from('extendedProfile_bak');
		$this->db->where('leadsId',$leadsId);
		$data = $this->db->get();
		$profile = $data->row();
		header("Content-Type: application/json");
		echo json_encode($profile);
	}
	
	function uploadDocIndividual() {
		//print_r($_POST);exit;
		$leadId = $_POST['leadId'];
		$data['uploadDocMsg'] = $this->leadmodel->uploadDoc($leadId);
		echo "success";
	}
	
	function sourcedetails() {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		$currDate = date("Y-m")."-01";
		$data = $this->db->query("SELECT distinct source,count(source) as count FROM `leads` where organizationId = $orgId AND `leadCreatedTime` >= '$currDate' group by source order by count desc");//updated according organization
		//$data = $this->db->query("SELECT *from leads");//updated according organization
		$rs = $data->result_array();
		foreach($rs as $index=>$key) {
			if($key['source']=='') {
				$rs[$index]['source'] = 'Others';
			}
			$rs[$index] = array_values($rs[$index]);
		}
		header('Content-Type: application/json');
		echo json_encode($rs);
	}
	
	function smsVerified() { //  from curl
		//$orgId = '95';
		$data = array('isVerified'=>'1');
	    $this->db->where('phone', $this->input->post('phone'));
	    $this->db->order_by('id','desc');
	    $this->db->limit(1);
	    //$this->db->where('organizationId'=>'95');
	    $this->db->update('smsVerification', $data);
	} 
	
	function pixelFired() { //  from curl
		//$orgId = '95';
		$data = array('pixel'=>$this->input->post('pixel'));
	    $this->db->where('phone', $this->input->post('phone'));
	    //$this->db->where('organizationId'=>'95');
	    $this->db->update('smsVerification', $data);
	} 
	
	function enter() { //data coming from other server
		//print_r($_POST);		//exit;		//$orgId = '95';		//static value of webinfomart organization
		$data = array(
				'name' => $this->input->post('name'),
				'email'=> $this->input->post('email'),
				'phone'=> $this->input->post('phone'),
				'city'=> $this->input->post('city'),
				'source'=> $this->input->post('source'),
				'organizationId'=>'95'
		);
		//print_r($this->input->post());
		$this->db->insert('leads',$data);		//echo "sdfsdf";exit;
		$leadId = $this->db->insert_id();
		$lookupData = array(
					'leadId'=>$leadId,
					'organizationId'=>'95',
					'phone'=>$this->input->post('phone'),
					'lookupCity'=>$this->input->post('lookupCity')
		);
		$this->db->insert('lookup',$lookupData);
		//check lookup valid or invalid
		// if($this->input->post('city') == 'Delhi' && ($this->input->post('lookupCity') == 'UP (West)' || $this->input->post('lookupCity') == 'Delhi'))
		// {
			// $leads_status = '';
		// }
		// else if($this->input->post('city') == 'Mumbai' && ($this->input->post('lookupCity') == 'Mumbai' || $this->input->post('lookupCity') == 'Maharashtra'))
		// {
			// $leads_status = '';
		// }
		// else if($this->input->post('city') == 'Bangalore' && $this->input->post('lookupCity') == 'Karnataka')
		// {
			// $leads_status = '';
		// }
		// else if($this->input->post('city') == 'Pune' && $this->input->post('lookupCity') == 'Maharashtra')
		// {
			// $leads_status = '';
		// }
		// else if($this->input->post('city') == 'Kolkata' && ($this->input->post('lookupCity') == 'Kolkata' || $this->input->post('lookupCity') == 'West Bengal'))
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Ahmedabad' && $this->input->post('lookupCity') == 'Gujarat')
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Cochin' && $this->input->post('lookupCity') == 'Kerala')
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Baroda' && $this->input->post('lookupCity') == 'Gujarat')
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Nagpur' && $this->input->post('lookupCity') == 'Maharashtra')
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Comibatore' && ($this->input->post('lookupCity') == 'Tamilnadu' || $this->input->post('lookupCity') == 'Chennai'))
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Hyderabad' && $this->input->post('lookupCity') == 'AP')
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Chennai' && ($this->input->post('lookupCity') == 'Chennai' || $this->input->post('lookupCity') == 'Tamilnadu'))
		// {
			// $leads_stauts = '';
		// }
		// else if($this->input->post('city') == 'Chandigarh' && ($this->input->post('lookupCity') == 'Panjab' || $this->input->post('lookupCity') == 'Haryana'))
		// {
			// $leads_stauts = '';
		// }
		// else
		// {
			// $leads_status='22';
		// }
		
		//$data = array('status'=>$leads_status);
		$data = array('status'=>$this->input->post('status'));
	    $this->db->where('id', $leadId);
	    $this->db->where('organizationId', '95');
	    $this->db->update('leads', $data);
		//check lookup valid or invalid
		$obdData = array(
					'leadId'=>$leadId,
					'phone'=> $this->input->post('phone'),
					'requestId'=>$this->input->post('obdId'),
					'timeOfCall'=>$this->input->post('obdTime'),
					'organizationId'=>'95'
		);
		
		$this->db->insert('obdlog',$obdData);
		$campaignData = array(
					'leadId'=>$leadId,
					'campaignId'=>$this->input->post('campaign')
		);
		$this->db->insert('campaignlog',$campaignData);
		//extendedProfile
		$profData = array(
					'leadsId'=>$leadId,
		);
		$this->db->insert('extendedProfile',$profData);
		// sms veryfication
		$smsdata = array(
				'leadId'=> $leadId,
				'phone'=> $this->input->post('phone'),
				'subId'=> $this->input->post('leads_sub'),
				'code'=> $this->input->post('randomCode'),
				'organizationId'=>'95'
		);
		$this->db->insert('smsVerification',$smsdata);
		// sms veryfication
	}
	/* function enter()//data coming from other server
	{
		$orgId = '95';
		$data = array(
				'name' => $this->input->post('name'),
				'email'=> $this->input->post('email'),
				'phone'=> $this->input->post('phone'),
				'city'=> $this->input->post('city'),
				'source'=> $this->input->post('source'),
				'organizationId'=>$orgId//static value of webinfomart organization
		);
		//print_r($this->input->post());
		$this->db->insert('leads',$data);
		$leadId = $this->db->insert_id();
		
		
		$lookupData = array(
					'leadId'=>$leadId,
					'organizationId'=>$orgId,
					'phone'=>$this->input->post('phone'),
					'lookupCity'=>$this->input->post('lookupCity')
		);
		$this->db->insert('lookup',$lookupData);
		
		$obdData = array(
					'leadId'=>$leadId,
					'requestId'=>$this->input->post('obdId'),
					'timeOfCall'=>$this->input->post('obdTime'),
					'organizationId'=>$orgId
		);
		
		$this->db->insert('obdlog',$obdData);
		
		$campaignData = array(
					'leadId'=>$leadId,
					'campaignId'=>$this->input->post('campaign')
		);
		$this->db->insert('campaignlog',$campaignData);
		
	} */
	
	
	function loadSampleData() {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		$this->leadmodel->loadSampleData($orgId);
	}
	
	function getIndividualNotes() {
		print($this->leadmodel->getIndividualNotesById($this->input->get('leadsId')));
	}
	
	function getTotleIndividualNotes() {
		print($this->leadmodel->getTotleIndividualNotesById($this->input->get('leadsId')));
	}
	function getLastNotesTime() {
		print($this->leadmodel->getLastNotesTimeById($this->input->get('leadsId')));
	}
	
	function updateInterestTrakerByAjax() {
		$this->leadmodel->updateInterestTrakerByAjax($this->input->post('leadId'));
	}
	
	function checkInterestTrakerOnByAjax() {
		$this->leadmodel->checkInterestTrakerOnByAjax($this->input->post('leadId'),$this->input->post('onOffValue'));
		exit;
	}
	
	function checkCorrectLookupCity() {
		$this->leadmodel->checkCorrectLookupCity();
		exit;
	}
	
	function deleteTag() {
		$this->leadmodel->deleteTag();
		exit;
	}
	
	function getTransferedToName() {
		printf($this->leadmodel->getTransferedToName($this->input->get('leadsId')));
	}
	
	 function getTransferedToCampaignName() {
		printf($this->leadmodel->getTransferedToCampaignName($this->input->get('leadsId')));
	}
	
	/******************************* Admin interest tracker fresh and shared****************************************/
	
	function freshInterestTracker($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(!$this->session->userdata('loggedIn')) {
			redirect('login','refresh');
		}
		if($this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home');
		}
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getFreshInterestTrackerCount($search);
		$data['per_page'] = $limit;
				
		/**configration of pagination***/
		$config["base_url"] = base_url() . "freshInterestTracker/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAllLeads'] = $this->leadmodel->getFreshInterestTrackerSearch($config['per_page'],$page,$search);
		} else {
			$data['getAllLeads'] = $this->leadmodel->freshInterestTracker($config['per_page'],$page);
		} 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'freshInterestTracker';
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;
		$data['active'] = 'freshInterestTracker';
		if($this->config->item('CounslorLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/counselor/freshInterestTracker',$data);
		}
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/freshInterestTracker',$data);
		}
	}
	// shared interest tracker
	function sharedInterestTracker($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(!$this->session->userdata('loggedIn')) {
			redirect('login','refresh');
		}
		if($this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home');
		}
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getSharedInterestTrackerCount($search);
		$data['per_page'] = $limit;
				
		/**configration of pagination***/
		$config["base_url"] = base_url() . "sharedInterestTracker/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAllLeads'] = $this->leadmodel->getSharedInterestTrackerSearch($config['per_page'],$page,$search);
		} else {
			$data['getAllLeads'] = $this->leadmodel->sharedInterestTracker($config['per_page'],$page);
		} 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'sharedInterestTracker';
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;
		$data['active'] = 'sharedInterestTracker';
		if($this->config->item('CounslorLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/counselor/sharedInterestTracker',$data);
		}
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/sharedInterestTracker',$data);
		}
	}
	// shared interest tracker
	
	/******************************* Admin interest tracker fresh and shared****************************************/
	/******************************* counselor interest tracker fresh and shared****************************************/
	function freshInterestTrackerCounselor($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if($this->config->item('CounslorLevel')!=$data['loggedUser']['userLevel']) {
			redirect('home');
		}
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getCounselorFreshInterestTrackerCount($search);//New Model function
		$data['per_page'] = $limit;
				
		/**configration of pagination***/
		$config["base_url"] = base_url() . "freshInterestTrackerCounselor/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
				
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAllLeads'] = $this->leadmodel->getAllFreshInterestTrackerLeadsSearchCounselor($config['per_page'],$page,$search);//New Function
		} else {
			$data['getAllLeads'] = $this->leadmodel->getFreshInterestTrackerCounselor($config['per_page'],$page);//New Function
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'alllead';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'freshInterestTrackerCounselor';
		$this->layout->view('leads/counselor/freshInterestTracker',$data);
	}
	
	function sharedInterestTrackerCounselor($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if($this->config->item('CounslorLevel')!=$data['loggedUser']['userLevel']) {
			redirect('home');
		}
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getCounselorSharedInterestTrackerCount($search);//New Model function
		$data['per_page'] = $limit;
			
		/**configration of pagination***/
		$config["base_url"] = base_url() . "sharedInterestTrackerCounselor/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
				
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAllLeads'] = $this->leadmodel->getAllSharedInterestTrackerLeadsSearchCounselor($config['per_page'],$page,$search);//New Function
		} else {
			$data['getAllLeads'] = $this->leadmodel->getSharedInterestTrackerCounselor($config['per_page'],$page);//New Function
		}
		//creating links 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'alllead';
		//get country list
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		//get country list ends
		//get city list
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;	
		//get city list ends
		$data['active'] = 'sharedInterestTrackerCounselor';
		$this->layout->view('leads/counselor/sharedInterestTracker',$data);
	}
	/******************************* counselor interest tracker fresh and shared****************************************/
	
	function downloadInterestTrackerBucket() {
		$result=$this->leadmodel->downloadInterestTrackerBucket($_GET['CampaignId'],$_GET['leadsid']);
		date_default_timezone_set("Asia/Kolkata"); 
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=TransferedBucket".date('Y-m-d H:i:s').".csv");		
		// echo "Id,Name,Email,Phone,City,Interested Country,Last Qualification,Interested Course,Bachelor,master,CSR,bestCallTime,Notes\n";
		echo "Id,Name,Email,Phone,City,Interested Country,Last Qualification,Interested Course,Intake,Best Call Time,Notes,CSR,Bachelor,Master,Skype Id,Virtual Connect Time,Appointment Time\n";
		if($result) {
			foreach($result as $bucketData) {
				$notes=str_replace(array(",","\r", "\r\n", "\n"), array(" "), $bucketData['notes']);
				$name=str_replace(array(","), array("."), $bucketData['name']);
				$bestCallTime=str_replace(array(","), array("."), $bucketData['appointmentTime']);
				$email=str_replace(array(","), array("."), $bucketData['email']);
				$phone=str_replace(array(","), array("."), $bucketData['phone']);
				$city=str_replace(array(","), array("."), $bucketData['city']);
				$interestedCountry=str_replace(array(","), array("."), $bucketData['interestedCountry']);
				$lastQualification=str_replace(array(","), array("."), $bucketData['lastQualification']);
				$interestedCourse=str_replace(array(","), array("."), $bucketData['interestedCourse']);
				$bachelor=str_replace(array(","), array("."), $bucketData['bachelor']);
				$master=str_replace(array(","), array("."), $bucketData['master']);
				$intake=str_replace(array(","), array("."), $bucketData['intake']);
				$optedTime=str_replace(array(","), array("."), $bucketData['optedTime']);
				$csr=str_replace(array(","), array("."), $bucketData['CSR']);
				$skypeId=str_replace(array(","), array("."), $bucketData['skypeId']);
				$virtualConnectTime=str_replace(array(","), array("."), $bucketData['virtualConnectTime']);
				$appointmentTime=str_replace(array(","), array("."), $bucketData['appointmentTime']);
				// echo $bucketData['id'].','.$name.','.$email.','.$phone.','.$city.','.$interestedCountry.','.$lastQualification.','.$interestedCourse.','.$bachelor.','.$master.','.$csr.','.$bestCallTime.','.$notes."\n";
				echo $bucketData['id'].','.$name.','.$email.','.$phone.','.$city.','.$interestedCountry.','.$lastQualification.','.$interestedCourse.','.$intake.','.$bestCallTime.','.$notes.','.$csr.','.$bachelor.','.$master.','.$skypeId.','.$virtualConnectTime.','.$appointmentTime."\n";
				}
			}
	}
	// get un transfered interest tracker leads
	
	function freshInterestTrackerNotTransfered($search='',$limit='',$page='') {
		$data['loggedUser']=$this->session->userdata('loggedIn');
		if(!$this->session->userdata('loggedIn')) {
			redirect('login','refresh');
		}
		if($this->config->item('Admin')!=$data['loggedUser']['userLevel']) {
			redirect('home');
		}
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$data['total'] = $this->leadmodel->getFreshInterestTrackerCountNotTransfered($search);
		$data['per_page'] = $limit;
				
		/**configration of pagination***/
		$config["base_url"] = base_url() . "freshInterestTrackerNotTransfered/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
		
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);
		$data['smsTemplates'] = $this->smsmodel->getApprovedSmsTemplates($orgId);//getting all Sms template of organization
		if($search!='key') {
			$data['additionalParameter']=urldecode($search);
			$data['getAllLeads'] = $this->leadmodel->getFreshInterestTrackerSearchNotTransfered($config['per_page'],$page,$search);
		} else {
			$data['getAllLeads'] = $this->leadmodel->freshInterestTrackerNotTransfered($config['per_page'],$page);
		} 
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'freshInterestTracker';
		$Countries=$this->leadmodel->getAllCountryNames();
		$data['countryList']=$Countries;	
		$Cities=$this->leadmodel->getAllCityNames();
		$data['cityList']=$Cities;
		$data['active'] = 'freshInterestTracker';
		if($this->config->item('CounslorLevel')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/counselor/freshInterestTrackerNotTransfered',$data);
		}
		if($this->config->item('Admin')==$data['loggedUser']['userLevel']) {
			$this->layout->view('leads/admin/freshInterestTrackerNotTransfered',$data);
		}
	}
	// get un transfered interest tracker leads

	function campaignType() {
		$leadId=$this->input->post('leadId');
		$camId=$this->input->post('CampaignId');
		$result=$this->leadmodel->getCampaignType($camId);
		echo $result;
		return $result;
	}

	function thirdPartyInfo() {
		$leadId=$this->input->post('leadId');
		$camId=$this->input->post('CampaignId');
		$result=$this->leadmodel->getThirdPartyInfo($leadId,$camId);
	}
}
?>
