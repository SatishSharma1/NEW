<?php

 class Leadmodel extends CI_Model
 { 
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->gallery_path_doc = realpath(APPPATH . '../uploads/doc/');
		$this->gallery_path_pic=realpath(APPPATH . '../uploads/leads_pic/');
		
		$loggedUser=$this->session->userdata('loggedIn');
		$this->organizationId=$loggedUser['organizationId'];
		$this->userId=$loggedUser['id'];
		//$this->userEmail=$loggedUser['email'];
		$this->userLevel=$loggedUser['userLevel'];
		$this->db->cache_on();
		//$this->userName=$loggedUser['name'];
	}
	
	public function getRecordingTimeline($leadId) {
		$resQuery = $this->db->select("date, notes, time,  callRecordingurl url", FALSE)
				->join('notes', 'callLogs.leadId=notes.leadsId')
				->limit(100)
				->order_by('`date`', 'DESC')
				->get_where('callLogs', array(
					'leadId' => $leadId,
					'callRecordingurl != ' => 'none',
				));

		// return $resQuery->result();
		$result = array();
		$today = date('Y-m-d');
		$yest = date("F j, Y", strtotime( '-1 days' ) );
		foreach ($resQuery->result() as $row) {
			$val = array(
					'time' => $row->time,
					'url' => $row->url,
					'notes' => $row->notes
				);
			if($row->date == $today) {
				$result['Today'][] = $val;
			} elseif($row->date == $yest) {
				$result['Yesterday'][] = $val;
			} else {
				$result['Older'][] = $val;
			}
		}
		return $result;
		
	}

	function updateLeadData($userId) {
		//$sessionData=$this->session->userdata('loggedIn');
		$id = $this->input->post('leadId');
		$leadEmail = $this->input->post('leadEmail');
		$leadPhone = $this->input->post('leadPhone');
		$leadName = $this->input->post('leadName');
		$leadCity = $this->input->post('leadCity');
		$leadStatus = $this->input->post('leadStatus');
		
		$data = array(
			'phone' => $leadPhone,
			'name' => $leadName,
			'email' => $leadEmail,
			'city' => $leadCity,
			'status' => $leadStatus
		);
		$this->db->update('leads', $data, array('id' => $id));

		$this->insertLeadNotes_update($id ,$userId);
		return 1;
	}


	function addSourceName() {
	
	$leadSourceName = $this->input->post('SourceName');
	 $isSourceNameValid = $this->isSourceNameValid($leadSourceName);
		
		if($isSourceNameValid)
		 { 
			 $existLeadSourceId=$this->checkLeadSourceNameExist($isSourceNameValid); 
			 if(!$existLeadSourceId)
			 {
				//adding source to leadsource table starts
						$addSourceName = array(
							'source' => $isSourceNameValid,									
							'createdByID' => $this->userId,									
							//'createdByID' => $leadCreatedByID,									
							//'organizationId' => $organizationId			
							'organizationId' => $this->organizationId			
						);
						$this->db->insert('leadSource', $addSourceName);
						$SourceId=$this->db->insert_id();	
						$this->session->set_userdata('SourceId', $SourceId);
				//adding source to leadsource table ends
			 
			 }
			 //if source already exist get source id for import history table
			 if($existLeadSourceId)
			 {
				$existSource=$this->session->set_userdata('SourceId', $existLeadSourceId);
			 }
			
			//return true;
		}
		return $isSourceNameValid;	
	}
	function getLeadDetails($id) {
		$query = $this->db->get_where('leads', 
			array('id' => $id));
		return $query->row();
	}
/******************  Script for tags ********************/
	function addTags()
	{
	$alltagId='';
	//$sessionData=$this->session->userdata('loggedIn');
	//$organizationId=$sessionData['organizationId'];
	//$leadCreatedByID = $sessionData['id'];
	$leadsTags = $this->input->post('tags');
	$taglist=explode(',',$leadsTags);
		foreach($taglist as $newtags)
		{
			//check if tag already exits
			$checkTagExist = $this->checkTagExist($newtags);
			$tagId='';
			if(!$checkTagExist)
				{
						//adding tag to tags table starts
						$addtag = array(
							'tag' => $newtags,									
							//'organizationId' => $organizationId,					
							//'userId' => $leadCreatedByID
							'organizationId' => $this->organizationId,					
							'userId' => $this->userId		
						);
						$query=$this->db->insert('tags', $addtag);
						$checkTagExist=$this->db->insert_id().',';
					//adding tag to tags table ends
				}
			$alltagId=$alltagId.$checkTagExist;			
		}
		return rtrim($alltagId, ",");
	}
	/******************  Script for tags ********************/
	
	/**********************  leadImportHistory  ********************/
		function leadImportHistory($alltags)
		{
			$sessionData=$this->session->userdata('loggedIn');
			$fileName=$this->session->userdata('csvFileName');
			$sourceId=$this->session->userdata('SourceId');
			//$organizationId=$sessionData['organizationId'];
			//$leadCreatedByID = $sessionData['id'];
			$notes = $this->input->post('notes');
			$name = $this->input->post('name');
			$addImportHistory = array(
							'name' => $name,
							'fileName' => $fileName,							
							'tagsId' => $alltags,
							'notes' => $notes,									
							'sourceId' => $sourceId,									
							'userId' => $this->userId,
							'organizationId' => $this->organizationId,
							// 'userId' => $leadCreatedByID,
							// 'organizationId' => $organizationId,	
						);
			$query=$this->db->insert('importHistory', $addImportHistory);
			$this->session->unset_userdata('csvFileName');
			$this->session->unset_userdata('SourceId');
		}
/**********************  leadImportHistory  ********************/


/**********************  Script for tags ends  ********************/
	function uploadDoc($leadId)
	{
		$originalfilename = 'document_'.$leadId.'_'.rand(1000,999999);
		$filename = rtrim(base64_encode($originalfilename),'=');
		$config	=	array(
						'allowed_types' => 'jpg|jpeg|gif|png|xlsx|zip|rar|doc|docx',
						'upload_path'	=>	$this->gallery_path_doc,
						'file_name'		=>	$filename
						);
		//$fileArr = explode('.',$_FILES['filename']['name']);
		$ext = end(explode('.', $_FILES['filename']['name']));
		//$originalfilename .= '.'.$fileArr[1];
		$originalfilename .= '.'.$ext;
		if($_FILES['filename']['name']!='')
		{
			$this->load->library('upload',$config);
			$this->upload->overwrite = true;
			if(!$this->upload->do_upload('filename'))
			{
				return $this->upload->display_errors();
			}
			else
			{
				$data = $this->upload->data();
				$record = $this->updateLeadDoc($leadId,$originalfilename);
				return "uploaded Successfully";
			}
		}
	}
	function updateLeadDoc($leadId,$filename)
	{
		$this->db->select("doc");
		$this->db->from('leads');
		$this->db->where('id',$leadId);
		$data = $this->db->get();
		$lead = $data->row();
		if($lead->doc != '')
		{
			$filename = $lead->doc.",".$filename;
		}
		$this->db->set('doc', $filename);
		$this->db->where('id', $leadId);
		$this->db->update('leads');
	}
	function insert_file()
	{
		//return true;
	}
	function upload_leads_csv($validsource, $orgId)	{
	
		$ci = &get_instance();
		$ci->load->model('pluginmodel','',true);
		$phonelookup = array();
		
		 //$sessionData=$this->session->userdata('loggedIn');
		 //$organizationId=$sessionData['organizationId'];
	     //$leadCreatedByID = $sessionData['id'];
		 $leadsTags = $this->input->post('tags');
		 $skip = 0;
		 $error=0;
		 $success=0;
		 $count=0;
		 $csvFileName=$_FILES['filename']['name'];
		 $this->session->set_userdata('csvFileName', $csvFileName);
		/**************  Script for importing csv file starts  ********************/
			if(is_uploaded_file($_FILES['filename']['tmp_name'])) 
			{	
				$handle = fopen($_FILES['filename']['tmp_name'], "r");
				//Script to add leads  here do not exceed than allowed package
				$allowedLeadToImport = $this->CheckLeadCOuntFromUsedPackage();// get maximum allowed leads from package
				$allowedleadcount=$allowedLeadToImport[0]['leads'];
				$packagename=$allowedLeadToImport[0]['name'];
				//$existLeadCountInUsage = $this->isleadCountExitsInUsage($organizationId);//check already exist in usage
				$existLeadCountInUsage = $this->isleadCountExitsInUsage($this->organizationId);//check already exist in usage
				$LeadToImport=$allowedleadcount-$existLeadCountInUsage; //total remaining leads to upload
				
				while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{ 
					$leads_email = $this->isValidEmail(mysql_real_escape_string($data[1]));			
					$leads_phone = $this->isValidMobile(mysql_real_escape_string($data[2]));					
					$leads_city = $this->isValidCity(mysql_real_escape_string($data[3]));
					
					if(!$leads_email)
					{
							
						$error++;
					}
					else if($existLeadCountInUsage==$allowedleadcount)
					{
					$count++;
					}
					else if(!$leads_phone)
					{
						//return "Not a valid mobile number: Line no. ".$row;	
						$error++;
					}					
					else if(!$leads_city)					
					{												
					$error++;					
					}
					else
					{
						$phone_exists = $this->isPhoneAlreadyExists(mysql_real_escape_string($data[2]), $orgId);
						if($phone_exists)
						{
							$skip++;
							continue;
						} 
					if($success < $LeadToImport)
						{	
							//adding content to leads table starts
							$checkIfLeadExceed = $this->isleadCountExitsInUsage($this->organizationId);//check latest count
							if($checkIfLeadExceed<=$allowedleadcount)
							{ 
								$add = array('leadCreatedByID' => $this->userId,
									'organizationId' => $this->organizationId,
									'source' => $validsource,
									'tags' => $leadsTags,							
									'name' => mysql_real_escape_string($data[0]),			
									'email' => mysql_real_escape_string($data[1]),																		
									'city' => mysql_real_escape_string($data[3]),									
									'phone' => mysql_real_escape_string($data[2])
								);
								$this->db->insert('leads', $add);
								$id = $this->db->insert_id();
								$addLoadToImport = $this->addLeadCountInUsage(1);
								$this->markImportLeadSmsVerified($id,$data[2]);
								
								$isLookUpEnabled = $this->pluginmodel->isLookupEnable($this->organizationId);
								if($isLookUpEnabled)
								{
									$this->importLeadrunLookUp($data[2],$id,$this->organizationId);
								}
								$success++;
								//adding content to leads table ends
							}
							else
							{
								$count++;
							} 
								 
						}
						
					}
					
				}
				fclose($handle);
				//$ci->pluginmodel->lookup($phonelookup);
			}
				/**************  Script for importing csv file ends  ********************/
								
				if($skip > 0)
				{
					return $skip." lead(s) phone exists ".$success." leads uploaded successfully";
				}
				
				/* if($error>0)
				{
					return $error." leads skipped (Remaining ".$success." leads uploaded successfully)";
				}  */
				 if($count>0)
				{
				return 'Allowed Leads <strong>'.$allowedleadcount.'</strong> for <strong>'.$packagename.' </strong> Limit Exceed. <strong>'.$success.'</strong>  Leads successfully Imported';
				} 
				if($success==0)
				{
					return " No Leads uploaded ";
				}
				if ($success>0)
				{
				return $success."Lead(s) Uploaded Successfully";
				}
				if($success==0)
				{
					return "No Change";
				}
				
		
	}
	/*********************** import lead auto sms verified**************************/
	
	function markImportLeadSmsVerified($leadId,$phone)
	{
			$add = array(
									'leadId' => $leadId,
									'organizationId' => $this->organizationId,
									'isVerified' => '1',
									'phone' => $phone
								);
								$this->db->insert('smsVerification', $add);
	}
	/*********************** import lead auto sms verified**************************/
	
/*********** Script to update and add lead count in usage after lead successfully imported starts **************/
	function addLeadCountInUsage($success)
	{
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		//$leadCount=$this->isleadCountExitsInUsage($organizationId);
		$leadCount=$this->isleadCountExitsInUsage($this->organizationId);
		if($success > 0)
		{
				
						$leadCount=$leadCount+$success;
						$data = array('leads' => $leadCount);
						$this->db->where('organizationId', $this->organizationId);
						$query=$this->db->update('usage',$data);
						
		}
		
	}
/************** Script to update and add lead count after lead sucessfully imported ends *****************/
	function isValidEmail($email)
	{
		if($email == "")
		{
			return true;
		}
		else
		{
			return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
		}
	}
	function isValidMobile($number)
	{	
		if (preg_match("/^[7-9][0-9]*$/", $number)) 
		{
			if(is_numeric($number) && strlen((string) $number) == 10)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}	function isValidCity($city)	
	{						
	if(is_numeric($city) && strlen((string) $city) <= 2)			
	{				
	return false;			
	}			
	else			
	{				
	return true;			
	}	
	}
	function CheckLeadCOuntFromUsedPackage()//checking cout of orgnization
	{ 
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		$this->db->select('*');
		$this->db->from('organization');
		$this->db->join('package', 'package.id=organization.package');
		//$this->db->where('organization.id',$organizationId);
		$this->db->where('organization.id',$this->organizationId);
		$query = $this->db->get();
		$data=$query->result_array();
		return $data;
	}	
	function isPhoneAlreadyExists($leads_phone, $orgId)
	{
		$this->db->select('*');
		$this->db->from('leads');
		$this->db->where("phone",$leads_phone);
		$this->db->where("organizationId",$orgId);
		$query = $this->db->get();
		return ($query->num_rows() > 0)?true:false;		
	}
	function checkTagExist($newtags)
	{
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		$this->db->select('id');
		$this->db->from('tags');
		$this->db->where("tag",$newtags);
		//$this->db->where("organizationId",$organizationId);
		$this->db->where("organizationId",$this->organizationId);
		$query = $this->db->get();
		$data=$query->result_array();
		if(isset($data[0]['id']))
		{
			return $data[0]['id'].',';
		}	
		else return	false;			
	}
	 
	  function isleadCountExitsInUsage($organizationId)
	{
		$this->db->select('leads');
		$this->db->from('usage');
		$this->db->where('organizationId', $organizationId);
		$query = $this->db->get();
		$result=$query->result_array();
		if(isset($result[0]['leads']))
		{
		return $result[0]['leads'];}
		else return	0;	
	} 
	
	  function checkLeadSourceNameExist($isSourceNameValid)
	{
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		//$leadCreatedByID = $sessionData['id'];
		$this->db->select('id');
		$this->db->from('leadSource');
		$this->db->where('source',$isSourceNameValid);
		//$this->db->where('organizationId', $organizationId);
		$this->db->where('organizationId', $this->organizationId);
		$query = $this->db->get();
		$result=$query->result_array();
		if(isset($result[0]['id']))
		{
		return $result[0]['id'];}
		else return	false;	
	} 
	function isSourceNamevalid($leadSourceName)
	{
		if (preg_match("/^[A-Za-z][_a-zA-Z0-9- ]+$/", $leadSourceName)) {
		
			$checkValidSourceName=str_replace(" ","_",trim($leadSourceName));
			return $checkValidSourceName;
			}
		else{
			return false;
		}
	} 
	
	function getSmsVeryfiedLeadsCountMonth()
	{
		
		$month=date('Y-m-01');
		
		if($this->config->item('Admin')==$this->userLevel)
		{
			$query=$this->db->query("SELECT leads.id FROM `leads` LEFT JOIN smsVerification on smsVerification.leadId=leads.id  WHERE smsVerification.isVerified='1' AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month'");
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT leads.id FROM `leads` LEFT JOIN smsVerification on smsVerification.leadId=leads.id  WHERE smsVerification.isVerified='1' AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month' AND `leads`.`leadAssignedID`='$this->userId'");
		}
		
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT leads.id FROM `leads` LEFT JOIN smsVerification on smsVerification.leadId=leads.id  WHERE smsVerification.isVerified='1' AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month' AND `leads`.`leadAssignedID`='$this->userId'");
		}
		else{
			$query=$this->db->query("SELECT leads.id FROM `leads` LEFT JOIN smsVerification on smsVerification.leadId=leads.id  WHERE smsVerification.isVerified='1' AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month'");
		}
		
		
		return $query->num_rows();
	}
	
	function getInvalidLeadsCountMonth($activeUserLevel)
	{ 
		
			$month=date('Y-m-01');
			
		if($this->config->item('Admin')==$this->userLevel)
		{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='3') AND leadCreatedTime >='$month'");
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='3') AND leadCreatedTime >='$month' AND `leads`.`leadAssignedID`='$this->userId' ");
		}
		
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='3') AND leadCreatedTime >='$month'  AND `leads`.`leadAssignedID`='$this->userId'");
		}
		else{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='3') AND leadCreatedTime >='$month'");
		}	
			return $query->num_rows();
	}
	
	function getNewLeadsCountMonth($userLevel, $userId)
	{ 
		
		$month=date('Y-m-01');
		
		if($userLevel==$this->config->item('Admin'))
		{
			
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND (`leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='1') or `leads`.status='0') AND leadCreatedTime >='$month' ");
			return $query->num_rows();
		}
		else
		{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND (`leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='1') or `leads`.status='0') AND leadCreatedTime >='$month' AND `leads`.`leadAssignedID`='$userId'");
			return $query->num_rows();
		}
	}
	
	function getAllLeadsCountCurrentMonth()
	{
		
		$month=date('Y-m-01');
		
		if($this->config->item('Admin')==$this->userLevel)
		{
		$query=$this->db->query("SELECT id FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month'");
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
		$query=$this->db->query("SELECT id FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month' and `leads`.`leadAssignedID`='$this->userId'");
		}
		
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
		$query=$this->db->query("SELECT id FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month' and `leads`.`leadAssignedID`='$this->userId'");
		}
		else{
		$query=$this->db->query("SELECT id FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadCreatedTime >='$month'");
		}
		
		
		
		
		return $query->num_rows();
	}
	function getQualifiedLeadsCountCurrentMonth()
	{ 
		
		$month=date('Y-m-01');
		
		if($this->config->item('Admin')==$this->userLevel)
		{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='4') AND leadCreatedTime >='$month'");
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='4') AND leadCreatedTime >='$month' and `leads`.`leadAssignedID`='$this->userId'");
		}
		
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
				$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='4') AND leadCreatedTime >='$month' and `leads`.`leadAssignedID`='$this->userId'");
		}
		else{
			$query=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`removed` = '0' AND `organizationId`='$this->organizationId' AND `leads`.`status` IN(select`id` from `leadStatusData` where `parentStatusId` ='4') AND leadCreatedTime >='$month'");
		}
		
		
		
	
		return $query->num_rows();
	}
	function getAttemptedLeadsCountCurrentMonth($orgId, $mnth) { 
		$res = $this->db->select('COUNT(DISTINCT(leads.id)) count', false)
				->join('callLogs', 'leads.id = callLogs.leadId')
				->get_where('leads', array(
					'leads.organizationId' => $orgId,
					"DATE_FORMAT(leadCreatedTime,'%Y-%m')" => $mnth
				));
		return $res->row()->count;
	}

	function getAllLeadsCount($search='key')
	{
		//$sessionData = $this->session->userdata('loggedIn');
		//$organizationId = $sessionData['organizationId'];
		$search=urldecode($search);
		
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);

		if($this->config->item('Admin')==$this->userLevel)
		{
			if($search != 'key')
			{
			/*$this->db->select('*');
			$this->db->where();*/
			$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id`");

			}
			else
			{

			//$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id`");
				/*$this->db->select('count(`l`.`id`) as rows');
				$this->db->from('`leads` `l`');
				$this->db->where('`l`.`removed`','0');
				$this->db->where('`l`.`organizationId`',$this->organizationId);
				$this->db->where('NOT EXISTS (SELECT `lsd`.`id` from `leadStatusData` AS `lsd` WHERE `l`.`status`=`lsd`.`id` AND `lsd`.`parentStatusId`=\'3\') OR `l`.`status`=\'13\'');
				$this->db->group_by('`l`.`id`');
				$query=$this->db->get();
				$res=$query->result_array();
				return $res['rows'];*/
				$query=$this->db->query("SELECT * FROM `leads` AS `l` WHERE `l`.`removed` = '0' AND `l`.`organizationId`='$this->organizationId' AND (NOT EXISTS (SELECT `lsd`.`id` from `leadStatusData` AS `lsd` WHERE `l`.`status`=`lsd`.`id` AND `lsd`.`parentStatusId`='3') OR `l`.`status`='13') GROUP BY `l`.`id`");
			}
		}
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
			if($search != 'key')
			{
			$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadAssignedID='$this->userId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
			else
			{
			
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
			if($search != 'key')
			{
			$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadAssignedID='$this->userId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
			else
			{
			
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
		}
		
		else{
		
		if($search != 'key')
			{
			$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
			else
			{
			
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
		}
			return $query->num_rows();
		
		
	}
	function getAllLeadsSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		/*********** added on 22 jan 2014  by deepak sharma ***************/
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);


		if($this->config->item('Admin')==$this->userLevel)
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadAssignedID='$this->userId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND leadAssignedID='$this->userId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		else
		{
		$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		
		
		
		 
		return $query->result_array();
		
		
		
	}
	
	function getCountry()
	{
	$this->db->select('*');
	$query=$this->db->get('country');
	
	return $query->result();
	}
	
	function getCity($cid) {

		$this->db->where('countryId',$cid);
		$query=$this->db->get('city');
		return $query->result();
	}

	public function getCountryCode() {
		$resQuery = $this->db->select('code')
						->get('country');
		return $resQuery->result();
	}
	
	public function getCountryByCityName($cityName) { 
		$res = $this->db->select('countryId')->get_where('city', array('cityName'=>$cityName))->row();
		$country['id'] = $res->countryId;
		$nres=$this->db->select('countryName')->get_where('country', array('id'=>$country['id']))->row();
		if($nres) {
		$country['name'] = $nres->countryName;
		}
		return $country;
	}
	
	function getInterestTrackerCount($search='key')
	{ 
	
		//$sessionData=$this->session->userdata('loggedIn');
		//$userId = $sessionData['id'];
		//$organizationId=$sessionData['organizationId'];
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		
		//$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		 $city=$this->getLeadCityBySearchKeyword($search);
		
		$search=urldecode($search);
		$leadids=$this->getallInterestTracker($this->organizationId);
			
			if($search != 'key')
			{
				if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city  $counselor $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
				
				}
				else{
				
					if(empty($phone) && empty($city) && empty($counselor))
					{
						return 0;
					}
					else
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city  $counselor  `leads`.`id` IN($leadids) and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
				}
			}
			else{
			
				if(empty($leadids))
					{
						return 0;
					}
					else
					{
						
						$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN ($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
			
			}
			return $query->num_rows(); 
	}
	
	function getCounselorInterestTrackerCount($search='key')
	{ 
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		
		$search=urldecode($search);
		$leadids=$this->getallInterestTracker($this->organizationId);
			
			if($search != 'key')
			{
			
				//$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
				
				}
				else{
				
				if(empty($phone) && empty($city) && empty($email))
					{
						return 0;
					}
					else
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email  `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
				}
			}
			else{
					if(empty($leadids))
					{
						return 0;
					}
					else
					{
						$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN($leadids) AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
			
			
			}
			return $query->num_rows(); 
	}
	function getInterestTrackerSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		//$sessionData=$this->session->userdata('loggedIn');
		//$userId = $sessionData['id'];
		//$organizationId=$sessionData['organizationId'];
		
			$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$leadids=$this->getallInterestTracker($this->organizationId);
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$intesretedCountry=$this->getLeadIntesretedCountryBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
			if($campaign)
				{
				
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr ");
				}
				else{
				
					if(empty($phone) && empty($city) && empty($counselor)&& empty($email))
					{
					
						return array();
					}
					else
					{
					
						if(empty($leadids))
						{
						
						return array();
						}
						else
						{
							if(!empty($email))
							{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor `leads`.`id` IN($leadids) AND $email  `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
							}else
							{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
							}
						}
					
					}
				}
				
		return $query->result_array();
		 
		
	}
	
	function getAllLeadscsv($orgId)
	{
		$query = $this->db->query("SELECT leads.`id`,leads.`leadCreatedTime`,leads.`name`,leads.`phone`,leads.`city`,leads.`status`,leads.`source`,notes.`notes` FROM leads LEFT JOIN notes ON leads.id =notes.leadsId WHERE leads.`organizationId`='$orgId'");
		return $query->result_array();
	}
	
	function getAllLeads($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		if($this->config->item('Admin')==$this->userLevel)
		{
			
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		else if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		else if($this->config->item('CounslorLevel')==$this->userLevel)
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}
		else
		{
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and (leads.status not in (select id from leadStatusData where parentStatusId='3') or leads.status='13' ) GROUP BY `leads`.`id` limit $limitstr");
		}

		return $query->result_array();
	
	}
	
	function interestTracker($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		$leadids=$this->getallInterestTracker($this->organizationId);
		
				if(empty($leadids))
					{
						return array();
					}
					else
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN ($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
					return $query->result_array();
					}
		
	}
	function getallInterestTracker($organizationId)
	{
		
		$trackerquery=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`organizationId`='$organizationId' AND `leads`.`isTrackerOn`='1'");
		$allLeads=$trackerquery->result_array();
		$interestTrackerLeads="";
		if(!empty($allLeads))
		{
			
			foreach ($allLeads as $leads)
			{
				$interestTrackerLeads=$interestTrackerLeads.$leads['id'].",";
			}
		 return $leadids= rtrim($interestTrackerLeads,",");
		}
		else
		{
		return false;
		}
	}
	
	
	/********Counselor All Lead Functions*********/
	
	function getAllLeadsCountCounselor($search='key')
	{ 
		
		//$sessionData = $this->session->userdata('loggedIn');
		//$userId = $sessionData['id'];
		//$organizationId = $sessionData['organizationId'];
		
		$search=urldecode($search);
		
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		
		// if(($this->userId)=='117') // allow all for neha
		// {
		
		// if($search != 'key')
			// {
			// $query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND  leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			// }
			// else
			// {
			
			// $query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			// }
		// } 
		
		//else{
		if($search != 'key')
			{
			$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' AND  leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			}
			else
			{
			
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId'  and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
			
			}
		
		//}
			return $query->num_rows();
		
	}
	
	function getAllLeadsSearchCounselor($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		//$sessionData=$this->session->userdata('loggedIn');
		//$userId = $sessionData['id'];
		//$organizationId=$sessionData['organizationId'];
		
			$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		
		// if(($this->userId)=='117') // allow all for neha
		// {
		// $query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND  leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id` limit $limitstr");
		// }
		// else
		// {
			$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id` limit $limitstr");
		//}
		return $query->result_array();
			
		 
	}
	function getAllInterestTrackerLeadsSearchCounselor($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$leadids=$this->getallInterestTracker($this->organizationId);
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		//$intesretedCountry=$this->getLeadIntesretedCountryBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
		
		if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr ");
				}
				else{
				
					if(empty($phone) && empty($city) && empty($email))
					{
						return array();
					}
					else
					{
						if(empty($leadids))
						{
							return array();
						}
						else
						{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email  `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id`  ORDER BY `leads`.`id` desc limit $limitstr ");
						}
					
					}
				}
		
		
		
		return $query->result_array();
	}
	
	function getAllLeadsCounselor($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		// if(($this->userId)=='117'){
		// $query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id`  limit $limitstr");
		// }
		// else{
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id`  limit $limitstr");
		//}
		return $query->result_array();
	}
	function getInterestTrackerAllLeadsCounselor($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		//$sessionData=$this->session->userdata('loggedIn');
		//$userId = $sessionData['id'];
		//$organizationId=$sessionData['organizationId'];
		$leadids=$this->getallInterestTracker($this->organizationId);
		if(empty($leadids))
			{
				return array();
			}
		else
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
				return $query->result_array();
			}
		
	}
	
	/*********End***********/
	
	

	function getAllTags()
	{
		//$sessionData=$this->session->userdata('loggedIn');
		//$organizationId=$sessionData['organizationId'];
		$this->db->select('tag');
		$this->db->from('tags');
		$this->db->where("organizationId",$this->organizationId);
		$query = $this->db->get();
		return $query->result_array();
	}
	function getLeadsTags($leadId)
	{
		$this->db->select('tags');
		$this->db->from('leads');
		$this->db->where('id',$leadId);
		$query = $this->db->get();
		$leadsTag = $query->row();
		$rs = array();
		if($leadsTag->tags!='')
		{
			$tagsArray = explode(',',$leadsTag->tags);
			foreach($tagsArray as $index=>$key)
			{
				$this->db->select("tag");
				$this->db->from('tags');
				$this->db->where('id',$key);
				$data = $this->db->get()->row();
				if($data)
				{
				$rs[$index]['id']=$key;
				$rs[$index]['tag']=$data->tag;
				}
			}
		}
		return $rs;
	}
	function removeLeads()
	{ 
		$id = $this->input->post('id');
		$data = array('removed' => '1');
		$this->db->where('id', $id);
		$query=$this->db->update('leads',$data);
		return ($query)?true:false;	
	}
	function DeleteOneLeadUsages($orgId,$value=1)
	{
		$this->db->set('leads', 'leads - '.$value, FALSE);
		$this->db->where('organizationId', $orgId);
		$this->db->update('usage');
	}
	
	

	function checkLeadExistInExtendedProfile($leadsId)
	{
		return false;
		$this->db->select('leadsId');
		$this->db->from('extendedProfile');
		$this->db->where("leadsId",$leadsId);
		$query = $this->db->get();
		$data= $query->result_array();
		return($data)?true:false;
	}
	//************* get all city name****************
	function getAllCountryNames(){
	
		$this->db->select('*');
		$this->db->from('country');
		$query = $this->db->get();
		$Countries=$query->result_array();
		$data['Countries']="";
			foreach($Countries as $country) 
			{ 
				$data['Countries'].="<option value=".$country['countryName'].">".$country['countryName']."</option>";
			}
			return $data['Countries'];
	}
	function getAllCityNames(){
	
		$this->db->select('*');
		$this->db->from('city');
		$this->db->where('countryId','1');
		$query = $this->db->get();
		$Cities=$query->result_array();
		$data['Cities']="";
			foreach($Cities as $country) 
			{ 
				$data['Cities'].="<option value=".$country['cityName'].">".$country['cityName']."</option>";
			}
			return $data['Cities'];
	}
	//***************************
	
	//***************************manage status
	function manageStatus($organizationId)
	{
		$this->db->select('leadStatusData.id as childId,leadStatusData.detail,leadStatus.detail as parent,leadStatus.id,leadStatusData.removed,usersLevel.userLevel,leadStatus.userPrivledgeId as parent_id');
		$this->db->from('leadStatusData');
		$this->db->join('leadStatus ','leadStatusData.parentStatusId = leadStatus.id','left');
		$this->db->join('usersLevel','leadStatus.userPrivledgeId = usersLevel.userLevelId','left');
		$this->db->where('leadStatusData.organizationId','0');
		$this->db->or_where('leadStatusData.organizationId',$organizationId);
		$query = $this->db->get();
		return $query->result_array();
	}
	//***************************
	//***************************get user level script
	function getUserLevel()
	{
		$this->db->select('*');
		$this->db->from('usersLevel');
		$this->db->where("userLevelId !=",1);
		$query = $this->db->get();
		return $query->result_array();
	}
	//*************************** get user level script ends ************
	
	//***************************get users leads status by user level **************
	
	function getUsersLeadsStatusByUserLevel($userLevel)
	{
		
		$query=$this->db->query("select * from leadStatus where (userPrivledgeId='$userLevel' or userPrivledgeId='0') And (organizationId='0' or organizationId='95')");
		return $query->result_array();
	
	}
	//***************************get users leads status by user level ends *************
	
	//***************************edited ins leads level *************
	function InsertLeadsStatus($organizationId)
	{
		$addLeadStatus = array(
							'detail' => $_POST['StatusName'],
							'parentStatusId' => $_POST['parentId'],	
							'organizationId' => $organizationId	
						);
			$insertquery=$this->db->insert('leadStatusData', $addLeadStatus);
			return ($insertquery)?'Lead Status Saved Successfully... ':'Lead status not saved';	
	} 
	//***************************get users leads status by user level ends *************
	
	//***************************Insert Child Leads Status Same As Parent *************
	function InsertChildLeadsStatusSameAsParent($organizationId)
	{
		//****************** adding in parent and get parent id
		$addLeadStatusInParent = array(
							'detail' => $_POST['StatusName'],
							'userPrivledgeId' => $_POST['userLevel'],
							'organizationId' => $organizationId
						);
		$parentstatusInsertquery=$this->db->insert('leadStatus', $addLeadStatusInParent);
		$parentId=$this->db->insert_id();
		
		//************* adding in child ***********
		
		$addLeadStatusAsParent = array(
							'detail' => $_POST['StatusName'],
							'parentStatusId' => $parentId,
							'organizationId' => $organizationId
						);
			$ChildStatusInsertquery=$this->db->insert('leadStatusData', $addLeadStatusAsParent);
			return ($ChildStatusInsertquery)?'Lead Status Saved Successfully... ':'Lead status not saved';	
	} 
	//***************************get users leads status by user level ends *************
	
	function CheckIfStatusexist()
	{
		//$data['loggedUser']=$this->session->userdata('loggedIn');
		//$organizationId=$data['loggedUser']['organizationId'];
		$orgId = array(0,$this->organizationId);
		$this->db->select('*');
		$this->db->from('leadStatusData');
		$this->db->where_in("organizationId",$orgId);
		$this->db->where("detail",$_POST['StatusName']);
		$query = $this->db->get();
		$query->result_array();
		$data=$query->result_array();
		if(isset($data[0]['detail'])){
			return 'data exist';
		}
		else{
			return 'data not exist';
		}
	}
	function CheckIfStatusexistInParent()
	{
		//$data['loggedUser']=$this->session->userdata('loggedIn');
		//$organizationId=$data['loggedUser']['organizationId'];
		$orgId = array(0,$this->organizationId);
		
		$this->db->select('*');
		$this->db->from('leadStatus');
		$this->db->where_in("organizationId",$orgId);
		$this->db->where("detail",$_POST['StatusName']);
		$query = $this->db->get();
		$data=$query->result_array();
		if(isset($data[0]['detail'])){
			return 'data exist';
		}
		else{
			return 'data not exist';
		}
	}
	
	function MarkStatusInactive($id,$organizationId)
	{			
			if($this->isStatusInUse($id))
			{
			$data = array('removed' => '1');
			$this->db->where('id', $id);
			$this->db->where('organizationId', $organizationId);
			$query=$this->db->update('leadStatusData',$data);
			return ($query)?true:false;		
			}
			else
			{
			$data = array('removed' => '3');
			$this->db->where('id', $id);
			$this->db->where('organizationId', $organizationId);
			$query=$this->db->update('leadStatusData',$data);
			return ($query)?2:false;	
			}
			
	}
	function MarkStatusActive($id,$organizationId)
	{
			$data = array('removed' => '0');
			$this->db->where('id', $id);
			$this->db->where('organizationId', $organizationId);
			$query=$this->db->update('leadStatusData',$data);
			return ($query)?true:false;	
	}

	/**Fresh Lead Count**/
	
	function getfreshLeadsCount($search)
	{
		$search=urldecode($search);
		if($this->config->item('TelecallerLevel')==$this->userLevel)
		{

		if($search!='key')
		{
			$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				if($statusId)
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id`");
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id`");
				}
		}
		else
		{
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id`");
		}
	}
		else
		{
		
		if($search!='key')
		{
			$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				if($statusId)
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='0' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id`");
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='0' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id`");
				}
		}
		else
		{
		
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='0' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id`");
		}
	}
		return $query->num_rows();
	}
	
	function getfreshLeadsSearch($limit, $start,$search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		/******************* added on 22 jan 2014*********************/
		
		$search=urldecode($search);
		$statusId=$this->getStatusIdBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
		
		if($statusId)
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
			else
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
		}
		else{
		
		if($statusId)
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='0' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
			else
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='0' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
		}
	
	}
	
	
	/********** Fresh Leads Script ***********************/
	function getfreshLeads($limit, $start)
	{ // fresh lead  are those whose leadAssignedID`='0'  `leads`.`status`='0' or status=call back id
	
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		if($this->config->item('TelecallerLevel')==$this->userLevel)
		{
		
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id` limit $limitstr");
		}
		else
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='0' AND `organizationId`='$this->organizationId' AND `leads`.`status`IN(0,1) GROUP BY `leads`.`id` limit $limitstr");
		}
		return $query->result_array();
	}
	
	/********** Fresh Leads Script ends ***********************/
	
	/********** invalid Leads Script ***********************/
	function getInvalidLeadsCount($search='key', $activeUserLevel)
	{ 
		
		$search=urldecode($search);
		
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		
			$sessionData=$this->session->userdata('loggedIn');
			$organizationId=$sessionData['organizationId'];
			$userId=$sessionData['id'];
			
			$search=urldecode($search);
			if($activeUserLevel == $this->config->item('TelecallerLevel'))
			{
				
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
					
					
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' and leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");	
					
				}
				return $query->num_rows();
			}
			elseif($activeUserLevel == $this->config->item('CounslorLevel'))
			{
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leadAssignedID='$this->userId' and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' and leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
					
				}
				return $query->num_rows();
			}
			elseif($activeUserLevel == $this->config->item('Admin'))
			{
			
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
						
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
					
				}
				return $query->num_rows();
			}
			else
			{
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
						
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id`");
					
				}
				return $query->num_rows();
			}
				
	}
	
	function getInvalidLeadsSearch($limit, $start, $search, $activeUserLevel)
	{ 
		
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		$search=urldecode($search);
		
		
		
		// older
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
					if($activeUserLevel == $this->config->item('TelecallerLevel'))
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leadAssignedID='$this->userId' and leads.status  in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id` limit $limitstr");
					return $query->result_array();
					}
					elseif($activeUserLevel == $this->config->item('CounslorLevel'))
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leadAssignedID='$this->userId' and leads.status  in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id` limit $limitstr");
					return $query->result_array();
					}
					elseif($activeUserLevel == $this->config->item('Admin'))
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status  in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id` limit $limitstr");
					return $query->result_array();
					}
		
	}
	
	function getinvalidLeads($limit, $start, $activeUserLevel)// for telecaller
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		if($activeUserLevel == $this->config->item('TelecallerLevel'))
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`status` in (select id from leadStatusData where parentStatusId = '3') and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
			return $query->result_array();
		}elseif($activeUserLevel == $this->config->item('CounslorLevel')){
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND`leads`.`status` in(select id from leadStatusData where parentStatusId = 3) and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
			return $query->result_array();
		}elseif($activeUserLevel == $this->config->item('Admin')){
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`status` in(select id from leadStatusData where parentStatusId in(3)) AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
			return $query->result_array();
		}
	}
	/*
	/********** invalid Leads Script Councelor***********************/
	function getinvalidLeadsOfCouncelor($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND`leads`.`status` in(select id from leadStatusData where parentStatusId = 3) and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
		return $query->result_array();
	}
	
	function getinvalidLeadsOfCouncelorSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$statusId=$this->getStatusIdBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		if($statusId)
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND`leads`.`status` in(select id from leadStatusData where parentStatusId = 3) and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
		else{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND`leads`.`status` in(select id from leadStatusData where parentStatusId = 3) and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
		}
		
	}
	/**********  invalid Leads Script Councelor ends ***********************/
	/********** Admin invalid Leads Script ***********************/
	function getinvalidLeadsOfAdmin($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`status` in(select id from leadStatusData where parentStatusId in(3)) AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
		return $query->result_array();
	}
	function getinvalidLeadsOfAdminSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		// $search=urldecode($search);
		// $statusId=$this->getStatusIdBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		// if($statusId)
			// {
				// $query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`status` in(select id from leadStatusData where parentStatusId in(3)) AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
				// return $query->result_array();
			// }
			
			// else{
				// $query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`status` in(select id from leadStatusData where parentStatusId in(3)) AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
				// return $query->result_array();
			// }
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leads.status not in (select id from leadStatusData where parentStatusId='3') GROUP BY `leads`.`id` limit $limitstr");
		 
		return $query->result_array();
	
	}
	/**********  Admin invalid Leads Script ends ***********************/
	
	/*counselor lead count*/
	
	function getCounselorLeads($limit, $start)
	{ 
	// new leads for counclor
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		//$data['loggedUser']=$this->session->userdata('loggedIn');
		//$organizationId=$data['loggedUser']['organizationId'];
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND`leads`.`leadAssignedID`!='' AND `organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
		return $query->result_array();
	}
	
	function getCounselorLeadsCount($search)
	{ 
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		$search=urldecode($search);
		if($search != 'key')
		{
				
				$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				if($statusId)
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
				}
		
		
		}
		else
		{
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND`leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
		}
		return $query->num_rows();
	}
	
		
	function getCounselorLeadsSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		$search=urldecode($search);
		$statusId=$this->getStatusIdBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		if($statusId)
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
		else{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' AND `leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
				return $query->result_array();
			}
		
	}
	
	
	/********** New Leads Script ***********************/
	function getNewLeads($userId, $limit, $start)
	{ 
	// new leads for counclor
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` = `leads`.`leadAssignedTime` AND `leads`.`leadAssignedTime` != '0000-00-00' AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
		return $query->result_array();
	}
	
	/********** New Leads Script ***********************/
	function getNewAttemptedLeads($userId, $limit, $start)
	{ 
	// new leads for counclor
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$query=$this->db->query("SELECT *,leads.id FROM `leads` LEFT JOIN leadStatusData ON leadStatusData.id = leads.status WHERE `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` != `leads`.`leadAssignedTime` AND `leads`.`leadUpdatedTime` != '0000-00-00' AND leads.status in (select id from leadStatusData where parentStatusId='2') AND`leads`.`leadAssignedID`='$userId' AND leads.`organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
		return $query->result_array();
	}
	
	
	function getNewLeadsCount($search, $userLevel, $userId)
	{ 
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		$search=urldecode($search);
		if($userLevel==$this->config->item('Admin'))
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND`leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
			return $query->num_rows();
		}
		else
		{
		
			if($search != 'key')
			{
				$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				/* if($statusId)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId') and `leads`.`removed` = '0' and  `leads`.`leadAssignedTime` > DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
				}
				else
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' and  `leads`.`leadAssignedTime` > DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
				} */
				if($statusId)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId' ) and `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` = `leads`.`leadAssignedTime` AND `leads`.`leadAssignedTime` != '0000-00-00' AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` ");
				}
				else
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` = `leads`.`leadAssignedTime` AND `leads`.`leadAssignedTime` != '0000-00-00' AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` ");
				}
			}
			else
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' and  `leads`.`leadUpdatedTime` = `leads`.`leadAssignedTime` AND `leads`.`leadAssignedTime` != '0000-00-00' AND `leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
			}
			return $query->num_rows();
		}
	}
	
		
	function getNewLeadsSearch($userId, $limit, $start, $search)
	{ 
		$search=urldecode($search);
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		
		$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
		if($statusId)
		{	
		$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%' or leads.status ='$statusId' ) and `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` = `leads`.`leadAssignedTime` AND `leads`.`leadAssignedTime` != '0000-00-00' AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
		
		}
		else{
		$query=$this->db->query("SELECT * FROM `leads` WHERE (leads.name like '%$search%' or leads.source like '%$search%' or leads.phone like '%$search%' or leads.city like '%$search%') and `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` = `leads`.`leadAssignedTime` AND `leads`.`leadAssignedTime` != '0000-00-00' AND`leads`.`leadAssignedID`='$userId' AND `organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
		
		}
		
		
		//echo "<pre>";
		
		return $query->result_array();
	}
	
	/**********  new Leads Script ends ***********************/
	function getNewLeadsAttemptedCount($search, $userLevel, $userId)
	{ 
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
				
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		
		
		if($userLevel==$this->config->item('Admin'))
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND`leads`.`leadAssignedID`!='' AND `organizationId`='$organizationId' GROUP BY `leads`.`id`");
			return $query->num_rows();
		}
		else
		{
		
			if($search != 'key')
			{
				
				$query=$this->db->query("SELECT * FROM `leads` LEFT JOIN leadStatusData ON leadStatusData.id = leads.status WHERE $statusQuery $phone $city $source `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` != `leads`.`leadAssignedTime` AND `leads`.`leadUpdatedTime` != '0000-00-00' AND leads.status in (select id from leadStatusData where parentStatusId='2') AND`leads`.`leadAssignedID`='$userId' AND leads.`organizationId`='$organizationId' GROUP BY `leads`.`id`");
				
			}
			else{
				
				$query=$this->db->query("SELECT *,leads.id FROM `leads` LEFT JOIN leadStatusData ON leadStatusData.id = leads.status WHERE `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` != `leads`.`leadAssignedTime` AND `leads`.`leadUpdatedTime` != '0000-00-00' AND leads.status in (select id from leadStatusData where parentStatusId='2') AND`leads`.`leadAssignedID`='$userId' AND leads.`organizationId`='$organizationId' GROUP BY `leads`.`id`");
			}
			return $query->num_rows();
		}
			
	}
	
		
	function getNewLeadsAttemptedSearch($userId, $limit, $start, $search)
	{ 
		$search=urldecode($search);
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);
		$search =str_replace('-','|',$search);
		
		$query=$this->db->query("SELECT *,leads.id FROM `leads` LEFT JOIN leadStatusData ON leadStatusData.id = leads.status WHERE $statusQuery $phone $city $source  `leads`.`removed` = '0' and `leads`.`leadUpdatedTime` != `leads`.`leadAssignedTime` AND `leads`.`leadUpdatedTime` != '0000-00-00' AND leads.status in (select id from leadStatusData where parentStatusId='2') AND`leads`.`leadAssignedID`='$userId' AND leads.`organizationId`='$organizationId' GROUP BY `leads`.`id` limit $limitstr");
		
		
		
		return $query->result_array();
	}
	
	/**********  new Leads Script ends ***********************/
	
	
	/********** New Leads Script ***********************/
	function getAttemptedLeads($limit, $start,$activeUserLevel)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		if($activeUserLevel == $this->config->item('TelecallerLevel'))
		{
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`status` in (select id from leadStatusData where parentStatusId = '2') and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
			return $query->result_array();
		}elseif($activeUserLevel == $this->config->item('CounslorLevel')){
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND`leads`.`status` in(select id from leadStatusData where parentStatusId = 2) and leadAssignedID='$this->userId' and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
			return $query->result_array();
		}elseif($activeUserLevel == $this->config->item('Admin')){
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`status` in(select id from leadStatusData where parentStatusId in(2)) AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` limit $limitstr");
			return $query->result_array();
		}
		
	}
	
	
	function getAttemptedLeadsCount($search, $activeUserLevel)
	{ 
		$search=urldecode($search);
		
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		
			$sessionData=$this->session->userdata('loggedIn');
			$organizationId=$sessionData['organizationId'];
			$userId=$sessionData['id'];
			
			$search=urldecode($search);
			if($activeUserLevel == $this->config->item('TelecallerLevel'))
			{
				
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
					
					
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' and leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");	
					
				}
				return $query->num_rows();
			}
			elseif($activeUserLevel == $this->config->item('CounslorLevel'))
			{
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leadAssignedID='$this->userId' and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' and leadAssignedID='$this->userId' AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
					
				}
				return $query->num_rows();
			}
			elseif($activeUserLevel == $this->config->item('Admin'))
			{
			
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
						
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
					
				}
				return $query->num_rows();
			}
			else
			{
				if($search != 'key')
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
						
				}
				else
				{
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0'  AND `leads`.`organizationId`='$this->organizationId' and leads.status in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id`");
					
				}
				return $query->num_rows();
			}
	}
	
		
	function getAttemptedLeadsSearch($limit, $start, $search,$activeUserLevel)
	{ 
		

		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$statusQuery=$this->getStatusIdBySearchKeywords($searchKeyword);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$date=$this->getLeadByDateSearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$source=$this->getLeadSourceBySearchKeyword($search);// added by deepak sharma 22 jan 2014
		$search =str_replace('-','|',$search);
		$search=urldecode($search);
		
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
					if($activeUserLevel == $this->config->item('TelecallerLevel'))
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leadAssignedID='$this->userId' and leads.status  in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id` limit $limitstr");
					return $query->result_array();
					}
					elseif($activeUserLevel == $this->config->item('CounslorLevel'))
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' and leadAssignedID='$this->userId' and leads.status  in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id` limit $limitstr");
					return $query->result_array();
					}
					elseif($activeUserLevel == $this->config->item('Admin'))
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE  $counselor $statusQuery $phone $city $source `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  and leads.status  in (select id from leadStatusData where parentStatusId='2') GROUP BY `leads`.`id` limit $limitstr");
					return $query->result_array();
					}
		
	}

	/**********  new Leads Script ends ***********************/
	//current//
	function getLeadInfoById($leadId) //Get Lead Information By Id
	{

		$leadsInfo = $this->db->get_where('leads',array('id'=>$leadId));
		//print_r($leadsInfo->result());
		$rs=$leadsInfo->result();
		return $rs[0];

	}
	/*******External details*************/
	function getLeadExternalProfileInfo($leadId)
	{
		return false;
		$leadInfo = $this->db->get_where('extendedProfile',array('leadsId'=>$leadId));
		$rs = $leadInfo->result();
		return ($rs)?$rs[0]:False;
	}
	function updateLeadTags($leadsId,$tags)
	{
		$this->db->select('tags');
		$this->db->from('leads');
		$this->db->where('id',$leadsId);
		$query = $this->db->get();
		$leadtags = $query->row();
		$tagStr = $leadtags->tags;
		$leadtagsArray = explode(',',$leadtags->tags);
		//print_r($leadtagsArray);
		$tagsArray = explode(',',$tags);
		foreach($tagsArray as $index=>$key)
		{
			if(!in_array($key,$leadtagsArray))
			{
				if($tagStr=='')
					$tagStr = $key;
				else
					$tagStr = $tagStr.','.$key;
			}
		}
		//echo "final string  = ".$tagStr;
		$this->db->where('id',$leadsId);
		$this->db->update('leads',array('tags'=>$tagStr));
	}
	function updateLeadInfo($leadId)
	{
		$phone = preg_replace('/[^a-zA-Z0-9\']/', '', $this->input->post('editPhone'));
		//date_default_timezone_set("Asia/Kolkata"); 
		$currentTime=date('Y-m-d H:i:s'); 
		$data = array(
				'name' => $this->input->post('editFullName'),
				'email' => $this->input->post('editEmail'),
				'phone' => $phone,
				'city' => $this->input->post('editCity'),
				'status' => $this->input->post('editStatus'),
				'leadUpdatedTime' => $currentTime
		);
		$this->db->update('leads',$data,array('id'=>$leadId));
	}
	
	function updateInterestTrackerIR($leadId,$IR,$userId,$orgId)
	{
		if(isset($_POST['IR1'])){
		
			 $IR=$_POST['IR1'];
		}
		$query=$this->db->query("select * from interestTracker where userId='$userId' AND leadId='$leadId' AND organizationId='$orgId'");
		$trackerData=$query->result_array();
		
		$trackerOn=$this->getLeadInfoById($leadId);
		if($trackerOn->isTrackerOn==1)
		{
		
		if(empty($trackerData))
		{
			$data = array(
					'leadId' => $leadId,
					'userId'  => $userId,
					'IR'=>$IR,
					'organizationId' => $orgId,
					'updatedTime' => time()
					);
					$this->db->insert('interestTracker',$data);
		}
		else{
			$data = array(
				'IR' => $IR,
				'updatedTime' => time()
			);
		$this->db->update('interestTracker',$data,array('userId'=>$userId,'leadId'=>$leadId,'organizationId'=>$orgId));
		}
	}
	}
	
	function updateLeadExtentedInfo($leadId)
	{
		return false;
		if((isset($_POST['IR']))&&(isset($_POST['IR1'])))
		{
			$IR=($_POST['IR']>$_POST['IR1'])?$_POST['IR']:$_POST['IR1'];
		}
		
		else if(isset($_POST['IR1']))
		{
		$IR=$_POST['IR1'];
		}
		else if(isset($_POST['IR']))
		{
		$IR=$_POST['IR'];
		}
		$callBackDate=$this->input->post('editCallBackDate')." ".$this->input->post('editCallBackTime');
		$virtualConnectTime=$this->input->post('virtualconnectdatetime')." ".$this->input->post('timepickervirtaltime');

		$data = array(
				'interestedCourse' => $this->input->post('editInterestedCourse'),
				'interestedCountry' => $this->input->post('editInterestedCountry'),
				'dob' => $this->input->post('editDob'),
				'lastQualification' => $this->input->post('editLastQualification'),
				'lastPercentage' => $this->input->post('editLastPercentage'),
				'bestCallTime' => $this->input->post('editBestCallTime'),
				'twelfth' => $this->input->post('editTwelfth'),
				'bachelor' => $this->input->post('editBachelor'),
				'master' => $this->input->post('editMaster'),
				'IELTS' => $this->input->post('editIelts'),
				'PTE' => $this->input->post('editPte'),
				'TOEFL' => $this->input->post('editToefl'),
				'GMAT' => $this->input->post('editGmat'),
				'GRE' => $this->input->post('editGre'),
				'intake' => $this->input->post('editIntake'),
				'workExperience' => $this->input->post('editWorkExp'),
				'FA' => $this->input->post('editFa'),
				'CSR' => $this->input->post('CSR'),
				'IR' => $IR,
				'callBackDate' => $callBackDate,
				'university' => $this->input->post('editUniv'),
				'skypeId'=>$this->input->post('editSkypeId'),
				'virtualConnectTime'=>$virtualConnectTime,
				'appointmentTime'=>$this->input->post('appointTimeId')
				);
				
		$lead = $this->db->get_where('extendedProfile',array('leadsId'=>$leadId));
		if($lead->num_rows())
		{
			$this->db->update('extendedProfile',$data,array('leadsId'=>$leadId));
			
		}
		else 
		{
			$data['leadsId'] = $leadId;
			$this->db->insert('extendedProfile',$data);
		}
		// for telecaller if there is no
		
		$telecallerData=array(
			'leadAssignedID' => $this->userId,
			'leadAssignedByID' => $this->userId,
			'leadAssignedTime' => date('Y-m-d H:i:s'),
			);
			
		$lead = $this->db->get_where('leads',array('id'=>$leadId,'leadAssignedID'=>'0','organizationId'=>$this->organizationId));
		if($lead->num_rows())
		{
			$this->db->update('leads',$telecallerData,array('id'=>$leadId));
		}
	// for telecaller
	
		// notification table
/* 			$notificationData=array(
			'callBackDate' => $callBackDate,
			'leadId' => $leadId,
			'seen' => '0'
			);
			$lead = $this->db->get_where('notification',array('leadId'=>$leadId));
			if($lead->num_rows())
			{
				$this->db->update('notification',$notificationData,array('leadId'=>$leadId));
			}
			else 
			{
				$data['leadId'] = $leadId;
				$this->db->insert('notification',$notificationData);
			}
 */		// notification table ends
	}
	
	//Samarth
	function getAllNotes($leadId)
	{echo"";
		/*$this->db->select("*,DATE_FORMAT(notes.statusTime,'%I:%i %p') as time_notes");
		//$this->db->select("");
		$this->db->from('notes');
		$this->db->where('removed','0');
		$this->db->where('leadsId',$leadId);
		$result=$this->db->get();*/
		$result=$this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%d %b %Y %I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `removed`='0'");
		return $result->result();
	}
	//Samarth
	
	function getTodayNotes($leadId)
	{
		$currentDate = date('Y-m-d');
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$currentDate."%'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();t
		return ($rs)?$rs:false;
	}
	function getTodayCallNotes($leadId)
	{
		$currentDate = date('Y-m-d');
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$currentDate."%' AND type='1'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getTodaySmsNotes($leadId)
	{
		$currentDate = date('Y-m-d');
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$currentDate."%' AND type='2'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();t
		return ($rs)?$rs:false;
	}
	function getTodayEmailNotes($leadId)
	{
		$currentDate = date('Y-m-d');
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$currentDate."%' AND type='3'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();t
		return ($rs)?$rs:false;
	}
	function getPastDayNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$Date."%'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();t
		return ($rs)?$rs:false;
	}
	function getPastDayCallNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$Date."%' AND type='1'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getPastDaySmsNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$Date."%' AND type='2'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getPastDayEmailNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` LIKE '".$Date."%' AND type='3'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getOlderNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%d %b %Y %I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` < '".$Date."' ORDER by notes.id desc");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getOlderCallNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%d %b %Y %I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` < '".$Date."' AND type='1'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getOlderSmsNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%d %b %Y %I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` < '".$Date."' AND type='2'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function getOlderEmailNotes($leadId)
	{
		$Date = date('Y-m-d',time() - 60*60*24);
		$leadsInfo = $this->db->query("SELECT  *,DATE_FORMAT(notes.statusTime, '%d %b %Y %I:%i %p') as time_notes FROM (`notes`) WHERE `leadsId` = '".$leadId."' AND `statusTime` < '".$Date."' AND type='3'");
		//$leadsInfo = $this->db->get_where('notes',array('leadsId'=>$leadId));
		$rs=$leadsInfo->result();
		//echo $this->db->last_query();
		return ($rs)?$rs:false;
	}
	function currentStatusByUser($userLevel,$orgId)
	{
		if($userLevel == $this->config->item('Admin') || $userLevel == $this->config->item('MasterAdmin'))
		{
			$query = $this->db->query("SELECT * FROM (`leadStatus`) WHERE `organizationId` = '".$orgId."' OR `organizationId` = '0'");
			/*$this->db->select('*');
			$this->db->from('leadStatus');
			$this->db->where('organizationId',$orgId);
			$this->db->or_where('organizationId','0');
			$query=$this->db->get();*/
		}
		else
		{
			$query = $this->db->query("SELECT * FROM (`leadStatus`) WHERE (`userPrivledgeId` = '".$userLevel."' or `userPrivledgeId` = '0') AND (`organizationId` = '".$orgId."' OR `organizationId` = '0')");//userPrevilage id 0 for all means common
		}
		return $query->result();
	}
	
	function getAllCounselor($userLevel,$orgId)//get all councelor /
	{
		//$query = $this->db->query("SELECT * FROM (`users`) WHERE  `userLevel`='4' AND `organizationId` = '".$orgId."' OR `organizationId` = '0'");
		$query = $this->db->query("SELECT * FROM (`users`) WHERE  `userLevel`='4' AND `organizationId` = '".$orgId."' AND userStatus = '1'AND userStatus = '1'");
		
		return $query->result();
	}
	function getAllTelecaller($orgId)//get all telecaller /
	{
		$query = $this->db->query("SELECT * FROM (`users`) WHERE  `userLevel`='3' AND `organizationId` = '".$orgId."' AND userStatus = '1'");
		
		return $query->result();
	}
	function getChildStatus($parentId)
	{
				$data['loggedUser']=$this->session->userdata('loggedIn');
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		
		//$query = $this->db->get_where('leadStatusData',array('parentStatusId'=>$parentId,'organizationId'=>$orgId));
		$query = $this->db->query("SELECT * FROM `leadStatusData` WHERE `parentStatusId` = $parentId AND (`organizationId` = $orgId or `organizationId` = 0)");
		
		return $query->result();
	}
	function insertLeadNotes($leadId,$userId)
	{
		//date_default_timezone_set("Asia/Kolkata"); 
		$currentTime=date('Y-m-d H:i:s'); 
		$data = array(
					'leadsId' => $leadId,
					'userId'  => $userId,
					'notes'=>$this->input->post('editNotes'),
					'status' => $this->input->post('notes'),
					'statusTime' => $currentTime,
					'callBackDate' => $this->input->post('editCallBackDate')
		);
		if(!empty($data['notes']))
		{
		$this->db->insert('notes',$data);
		}
	}

	function insertLeadNotes_update($leadId,$userId)
	{
		//date_default_timezone_set("Asia/Kolkata"); 
		$currentTime=date('Y-m-d H:i:s'); 
		$data = array(
					'leadsId' => $leadId,
					'userId'  => $userId,
					'notes'=>$this->input->post('notes_body(server, mailbox, msg_number)'),
					'status' => $this->input->post('leadStatus'),
					'statusTime' => $currentTime
					//'callBackDate' => $this->input->post('editCallBackDate')
		);
		if(!empty($data['notes']))
		{
		$this->db->insert('notes',$data);
		}
	}
	//current//

	/************ Check if Status is used in leads,If yes Mark Inactive else Remove**************/
	function getLeadStatusdata()
	{
		$this->db->select('*');
		$this->db->from('leadStatusData');
		$query=$this->db->get();
		return $query->result_array();	
	}
	function isStatusInUse($statusId)
	{
		$this->db->select('*');
		$this->db->from('leads');
		$this->db->where('status',$statusId);
		$query=$this->db->get();
		return $query->result_array();	
	}	
	// lead profile update for individual entries starts
	function updateCityByAjax()
	{ 
			$city = $this->input->post('city');
			$id = $this->input->post('id');
			$data = array('city' => $city);
			$this->db->where('id', $id);
			$query=$this->db->update('leads',$data);
			return ($query)?true:false;	
	}
	function updateFullNameByAjax()
	{ 
			$name = $this->input->post('name');
			$id = $this->input->post('id');
			$data = array('name' => $name);
			$this->db->where('id', $id);
			$query=$this->db->update('leads',$data);
			return ($query)?true:false;	
	}
	function updatePhoneByAjax()
	{ 
			$phone = $this->input->post('phone');
			$id = $this->input->post('id');
			$data = array('phone' => $phone);
			$this->db->where('id', $id);
			$query=$this->db->update('leads',$data);
			return ($query)?true:false;	
	}
	function updateEmailByAjax()
	{ 
			$email = $this->input->post('email');
			$id = $this->input->post('id');
			$data = array('email' => $email);
			$this->db->where('id', $id);
			$query=$this->db->update('leads',$data);
			return ($query)?true:false;	
	}
	// lead profile update for individual entries ends
	function getPhoneByIds($leads) //get phone numbers from leads by Ids(More then one id)
	{
		$this->db->select('id,phone');
		$this->db->from('leads');
		$this->db->where_in('id',$leads);
		$data = $this->db->get();
		return $data->result_array();
	}
	function getNotesCount($leadsId)
	{
		$this->db->where('leadsId',$leadsId);
		$this->db->from('notes');
		return $this->db->count_all_results();
	}
	function getCallNotesCount($leadsId)
	{
		$this->db->where('leadsId',$leadsId);
		$this->db->where('type','1');
		$this->db->from('notes');
		return $this->db->count_all_results();
	}
	function getSmsNotesCount($leadsId)
	{
		$this->db->where('leadsId',$leadsId);
		$this->db->where('type','2');
		$this->db->from('notes');
		return $this->db->count_all_results();
	}
	function getEmailNotesCount($leadsId)
	{
		$this->db->where('leadsId',$leadsId);
		$this->db->where('type','3');
		$this->db->from('notes');
		return $this->db->count_all_results();
	}
	function loadSampleData($orgId)
	{
		//date_default_timezone_set("Asia/Kolkata"); 
		$today=date('Y-m-d H:i:s'); 
		$yesterday = date("Y-m-d H:i:s", time() - 60 * 60 * 24);
		$lastday = date("Y-m-d H:i:s", time() - 60 * 60 * 24 * 2);
		for($i=1;$i<=5;$i++)
		{
			$data = array(
					'name' => "sample$i",
					'email'=> "sample$i@gmail.com",
					'city' => 'Delhi',
					'source'=> 'sample',
					'organizationId'=> $orgId
			);
			$this->db->insert('leads',$data);
			echo $currentLead = $this->db->insert_id();//exit;
			$notes = array(
				array(
				'leadsId' => $currentLead,
				'notes' => 'Nice talking to him. Need to call back again. Seems to be interested',
				'statusTime'=> "$lastday"
				),
				array(
				'leadsId' => $currentLead,
				'notes' => 'Looking for universities in UK.',
				'statusTime'=> "$yesterday"),
				array(
				'leadsId' => $currentLead,
				'notes' => 'Will be sending us document to process application.',
				'statusTime'=> "$today")
			);
			
			$this->db->insert_batch('notes',$notes);
		}
	}
	
	function getIndividualNotesById($leadsId)
	{
		$this->db->select('leadsId,notes');
		$this->db->from('notes');
		$this->db->where('leadsId',$leadsId);
		$this->db->order_by('statusTime','desc');
		$this->db->limit(1);
		$data = $this->db->get();
		if(!empty($data->row()->notes))
		return $data->row()->notes;
		else
		return "";
	}
	function getLastNotesTimeById($leadsId)
	{
		$this->db->select('statusTime');
		$this->db->from('notes');
		$this->db->where('leadsId',$leadsId);
		$this->db->order_by('statusTime','desc');
		$this->db->limit(1);
		$data = $this->db->get();
		if(isset($data->row()->statusTime))
		return $data->row()->statusTime;
		else
		return "";
	}
	function getTotleIndividualNotesById($leadsId)
	{
		$query=$this->db->query("SELECT leadsId,notes from notes where leadsId='$leadsId'");
		return $query->num_rows();
	}
	function insertLead()
	{
		$add = array(						
				'name' => $this->input->get('name'),			
				'email' => $this->input->get('email'),					
				'phone' => $this->input->get('mobile'),
				'city' => $this->input->get('city'),
				'organizationId'=> $this->input->get('organization')
			);
		$this->db->insert('leads', $add);
		return $this->db->insert_id();
	}
// 	function checkodbverify($phone)
// 	{
// 		if($phone!=''&&$phone!=NULL)
// 		{
// 			$data = $this->db->get_where('obdlog',array('phone'=>$phone));
// 			
// 			//echo $phone;
// 			//print_r($data->row());
// 			//echo $this->db->last_query();
// 			if($data->row())
// 			{
// 				return $data->row();
// 			}
// 		}
// 		return false;
// 	}
	function checkodbverify($id)
	{
		if($id!=''&&$id!=NULL)
		{
			$data = $this->db->get_where('obdlog',array('leadId'=>$id));
			
			//echo $phone;
			//print_r($data->row());
			//echo $this->db->last_query();
			if($data->row())
			{
				return $data->row();
			}
		}
		return false;
	}
	function checkSmsverify($id)
	{
		if($id!=''&&$id!=NULL)
		{
			$data = $this->db->get_where('smsVerification',array('leadId'=>$id,'isVerified'=>'1'));
			
			if($data->row())
			{
				return $data->row();
			}
		}
		return false;
	}

	function checklookupcity($leadId)
	{
		return false;
		$data = $this->db->get_where('lookup',array('leadId'=>$leadId));
		if($data->row())
		{
			return $data->row()->lookupCity;
		}
		return false;
	}
	function getStatusById($id)
	{
		$this->db->select("detail");
		$this->db->where('id',$id);
		$this->db->from('leadStatusData');
		$data = $this->db->get();
		if($data->row())
			return $data->row()->detail;
		return 'N/A';
	}
	function getLookupCityById($id)
	{
		return 'N/A';
		$data = $this->db->get_where('lookup',array('leadId'=>$id));
		if($data->row())
		{
			return $data->row()->lookupCity;
		}
		return 'N/A';
	}
	/* added by deepak sharma 22 jan*/
	function getStatusIdBySearchKeyword($search)
	{
		$statusQuerys=$this->db->query("SELECT id FROM `leadStatusData` WHERE leadStatusData.detail = '$search' limit 1" );
		$statusQuery=$statusQuerys->result_array();
		if(!empty($statusQuery))
		{
		return $statusQuery[0]['id'];
		}
		else return false;
	}
	/* added by deepak sharma 22 jan ends*/
	function getUserNameById($userId)
	{
		$name=$this->db->query("SELECT userName FROM `users` WHERE users.id = '$userId' limit 1" );
		$username=$name->result_array();
		if(!empty($username))
		{
		return $username[0]['userName'];
		}
		else return false;
	}
	/* interest tracker on off script starts*/
	
	
	function checkInterestTrakerOnByAjax($leadId,$isTracker)
	{
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$userId=$data['loggedUser']['id'];
		$this->db->select('*');
		$this->db->from('interestTracker');
		$this->db->where('userId',$userId);
		$this->db->where('leadId',$leadId);
		$this->db->where('organizationId',$organizationId);
		$query = $this->db->get();
		$data=$query->result_array();
		if(!empty($data))
		{
			$this->updateInterestTrakerOnOffByAjax($leadId,$isTracker);
			echo "update";exit;
		}
		else{
		
			$data = array(
				'isTrackerOn' => $isTracker
			);
			$this->db->update('leads',$data,array('id'=>$leadId,'organizationId'=>$organizationId));
			// $add = array(						
				// 'userId' => $userId ,								
				// 'leadId' => $leadId,
				// 'updatedTime' =>time(),
				//'isTracker' => $isTracker,
				// 'organizationId'=> $organizationId
			// );
			
		// $this->db->insert('interestTracker', $add);
		echo "insert";exit;
		}
		
	}
	function updateInterestTrakerOnOffByAjax($leadId,$isTracker)
	{
		
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		 $userId=$data['loggedUser']['id'];
		
			$campaignListofuser1=$this->campaignmodel->getInterestTrackerCampaignUser1($organizationId,$leadId);
			if(!empty($campaignListofuser1))
			{
				
				$idList='';
				foreach($campaignListofuser1 as $id)
				{
					$idList.=$idList.$id.",";	
				}
				$idList=rtrim($idList,',');
				$this->db->query("DELETE FROM interestTracker WHERE campaignId not IN('$idList') and leadId='$leadId' and organizationId='$organizationId'");	
				
			}
			else{
					
					$data = array(
							'isTrackerOn' => $isTracker
						);
					$this->db->update('leads',$data,array('id'=>$leadId,'organizationId'=>$organizationId));
				
				$this->db->where('leadId', $leadId);
				//$this->db->where('userId', $userId);
				$this->db->where('organizationId', $organizationId);
				$this->db->delete('interestTracker');
				}	
	}
	/* interest tracker on off script ends */
	function updateInterestTrakerByAjax($leadId)
	{
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$organizationId=$data['loggedUser']['organizationId'];
		$userId=$data['loggedUser']['id'];
		
		$trackerOn=$this->getLeadInfoById($leadId);
		if(!isset($_POST['campaignList']))
		{
				$this->db->where('leadId', $leadId);
				$this->db->where('userId', $userId);
				$this->db->where('organizationId', $organizationId);
				$this->db->delete('interestTracker');	
		}
		else
		{
				$this->db->where('leadId', $leadId);
				$this->db->where('userId', $userId);
				$this->db->where('organizationId', $organizationId);
				$this->db->delete('interestTracker');
		}
		if($trackerOn->isTrackerOn==1)
		{
			$campaignId=$this->input->post('campaignList');
		
		if(isset($campaignId))
		{
			if(!empty($campaignId))
			{
				
				foreach($campaignId as $campaign)
				{
					$InterestTrakerExist = $this->pluginmodel->isInterestTrackerExist($organizationId,$userId,$leadId,$campaign);
					if(!$InterestTrakerExist)
						{
							$data = array(
								'leadId'=>$leadId,
								'userId'=>$userId,
								'campaignId'=>$campaign,
								'organizationId'=>$organizationId,
								'updatedTime'=>time()
								);
								$this->db->insert('interestTracker',$data);
						}
						else
						{
							
						}
				}
		}	
		
	}
	}
}
	
	
// 	function getObdInfoById($phone)
// 	{
// 		$data = $this->db->get_where("obdlog",array('phone'=>$phone));
// 		if($data->row())
// 		{
// 			return $data->row()->optedTime;
// 		}
// 		return 'N/A';
// 	}

	function getObdInfoById($leadId)
	{
		$data = $this->db->get_where("obdlog",array('leadId'=>$leadId));
		if($data->row())
		{
			//$data = $this->db->order_by("id", "desc"); /*added by debal*/
			return $data->row()->optedTime;
		}
		return 'N/A';
	}

	function getStatusIdBySearchKeywords($searchKeyword)
	{/*print_r($searchKeyword);exit;
		$this->db->select('*');
		$this->db->from('leadStatusData');
		$this->db-where_in('detail',$searchKeyword);
		$statusQuerys=$this->db->get();*/
		$statusQuerys=$this->db->query("SELECT id FROM `leadStatusData` WHERE leadStatusData.detail IN ($searchKeyword)" );
		$statusId=$statusQuerys->result_array();

		if($statusId)
		{
			if(count($statusId)>1)
			{
				$statusList='';
				foreach ($statusId as $status)
				{
					$statusList=$statusList.$status['id'].',';
				}
				$statusList=rtrim($statusList,",");
				return $statusQuery="leads.status IN('$statusList') and";
			}
			else
			{
				$statuslist=$statusId[0]['id'];
				return $statusQuery="leads.status ='$statuslist' and ";
			}
		}
		else
		{
			return $statusQuery='';
		}
	}

	function getLeadNameBySearchKeyword($searchKeyword)
	{
		$statusQuerys=$this->db->query("SELECT id,name FROM `leads` WHERE leads.name REGEXP '$searchKeyword'" );
		
		$statusQuery=$statusQuerys->result_array();
		if(!empty($statusQuery))
		{
			return "leads.name REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	function getLeadEmailBySearchKeyword($searchKeyword)
	{
		$statusQuerys=$this->db->query("SELECT id,email FROM `leads` WHERE leads.email REGEXP '$searchKeyword'" );
		
		$statusQuery=$statusQuerys->result_array();
		if(!empty($statusQuery))
		{
			return "leads.email REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	
	function getLeadIntesretedCountryBySearchKeyword($searchKeyword)
	{
		return false;
		$statusQuerys=$this->db->query("SELECT id FROM `extendedProfile` WHERE extendedProfile.interestedCountry REGEXP '$searchKeyword'" );
		
		$statusQuery=$statusQuerys->result_array();
		if(!empty($statusQuery))
		{
			return "leads.id REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	
	function getCampaignIdBySearchKeyword($searchKeyword)
	{
		
		$statusQuerys=$this->db->query("SELECT id FROM `campaign` WHERE campaign.campaign REGEXP '$searchKeyword'" );
		$statusQuery=$statusQuerys->result_array();
		 
		 if(!empty($statusQuery))
			{
				$queryId='';
				foreach($statusQuery as $queryid)
					{
						 $queryId=$queryId.$queryid['id'].",";
					}
				 $queryId=rtrim($queryId,",");
				$query=$this->db->query("SELECT leadId FROM `interestTracker` WHERE interestTracker.campaignId IN($queryId)" );
				$id=$query->result_array();
				if(!empty($id))
				{
					$findId='';
					foreach($id as $query1)
					{
						$findId=$findId.$query1['leadId'].",";
					}
					 $findId=rtrim($findId,",");
					return "leads.id in($findId) AND";
				}
				
			}
		else 
		{
			return false; 
		}
	}
	
	function getLeadCityBySearchKeyword($searchKeyword)
	{
		$statusQuerys=$this->db->query("SELECT id FROM `leads` WHERE leads.city REGEXP '$searchKeyword'" );
		$statusQuery=$statusQuerys->result_array();
		
		if(!empty($statusQuery))
		{
			return "leads.city REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	function getLeadSourceBySearchKeyword($searchKeyword)
	{
		$statusQuerys=$this->db->query("SELECT id FROM `leads` WHERE leads.source REGEXP '$searchKeyword'" );
		$statusQuery=$statusQuerys->result_array();
		
		if(!empty($statusQuery))
		{
			return "leads.source REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	function getLeadPhoneBySearchKeyword($searchKeyword)
	{
		$statusQuerys=$this->db->query("SELECT id FROM `leads` WHERE leads.phone REGEXP '$searchKeyword'" );
		$statusQuery=$statusQuerys->result_array();
		
		if(!empty($statusQuery))
		{
			return "leads.phone REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	/* function getLeadByDateSearchKeyword($searchKeyword)
	{
		$statusQuerys=$this->db->query("SELECT id FROM `leads` WHERE leads.leadCreatedTime REGEXP '$searchKeyword'" );
		$statusQuery=$statusQuerys->result_array();
		
		if(!empty($statusQuery))
		{
			return "leads.leadCreatedTime REGEXP '$searchKeyword' and";
		}
		else return false;
	} */
	function getLeadCounselorBySearchKeyword($searchKeyword)
	{//echo "$searchKeyword";exit;
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('userName REGEXP ', $searchKeyword, TRUE);
		$this->db->where_in('userLevel', array('3','4'));
		$statusQuerys=$this->db->get();
		//$statusQuerys=$this->db->query("SELECT id FROM `users` WHERE users.userName REGEXP '$searchKeyword' AND `users`.userLevel in(3,4)");
		$CounselorId=$statusQuerys->result_array();
		if($CounselorId)
		{
			if(count($CounselorId)>1)
			{
				$CounselorList='';
				foreach ($CounselorId as $Counselor)
				{
					 $CounselorList=$CounselorList.$Counselor['id'].',';
				}
				 $CounselorList=rtrim($CounselorList,",");
				 return $CounselorQuery="leads.leadAssignedID IN('$CounselorList') and";
			}
			else
			{
				$Counselorlist=$CounselorId[0]['id'];
				return $CounselorQuery="leads.leadAssignedID ='$Counselorlist' and ";
			}
		}
		else
		{
			return $CounselorQuery='';
		}
	
	}
	function getLastUpdatedLead($userId)
	{
		$today=date('Y-m-d');
		$Query=$this->db->query("SELECT * FROM  `leads` WHERE  `leads`.`removed` =  '0' AND  `leads`.`organizationId` =  '95' AND  `leads`.`leadAssignedID` =  '$userId' AND  `leadUpdatedTime` LIKE  '%$today%' GROUP BY  `leads`.`id` " );
		$TotalCount=$Query->result_array();
		return ($TotalCount)?count($TotalCount):0;
	}
	
	// added on 31 jan 2014
	
	function gettodayLeadsCount($search='key')
	{
		$sessionData = $this->session->userdata('loggedIn');
		$organizationId = $sessionData['organizationId'];
		$search=urldecode($search);
		$today=date('Y-m-d');
		if(!$this->uri->segment(2))
		{
		redirect('home');
		}
		 else
		 {
		 $userid=$this->uri->segment(2);
		 }
		if($search != 'key')
			{
			$query=$this->db->query("SELECT * FROM `leads` WHERE  `leads`.`removed` = '0' AND `leads`.`organizationId`='$organizationId' AND `leads`.`leadAssignedID`='$userid' AND `leadUpdatedTime` LIKE '%$today%' GROUP BY `leads`.`id`");
			}
			else
			{
			
			$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$organizationId' AND `leads`.`leadAssignedID`='$userid' AND `leadUpdatedTime` LIKE '%$today%' GROUP BY `leads`.`id`");
			
			}
			return $query->num_rows();
		
	}
	function getTodayLeadsSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		$today=date('Y-m-d');
		if(!$this->uri->segment(2))
		{
		redirect('home');
		}
		 else
		 {
		 $userid=$this->uri->segment(2);
		 }
		$search=urldecode($search);
		$query=$this->db->query("SELECT * FROM `leads` WHERE  `leads`.`removed` = '0' AND `leads`.`organizationId`='$organizationId' AND `leads`.`leadAssignedID`='$userid' AND `leadUpdatedTime` LIKE '%$today%' GROUP BY `leads`.`id` limit $limitstr");
		return $query->result_array();
		
	}
	function deleteTag()
	{
		$sessionData=$this->session->userdata('loggedIn');
		$organizationId=$sessionData['organizationId'];
		$tagId=$_POST['tagId'];
		$leadId=$_POST['leadId'];
		$query=$this->db->query("SELECT tags FROM  `leads` where `leads`.`organizationId`='$organizationId' AND `leads`.`id`='$leadId'");
		$tags=$query->result_array();
		if(!empty($tags))
		{
			$Id=explode(",",$tags[0]['tags']);
			foreach($Id as $index=>$id)
			{	
				if($id==$tagId)
				{
					unset($Id[$index]);
					$newId=implode(',',$Id);
				}
			}
			$updatedTags= rtrim($newId,",");
			$data = array(
               'tags' => $updatedTags
            );
			$this->db->where('id', $leadId);
			$this->db->update('leads', $data); 
			echo "success";
		}
		exit;
	}
 
 function getTransferedToName()
	{
		 $data['loggedUser']=$this->session->userdata('loggedIn');
		 $orgId = $data['loggedUser']['organizationId'];
		 $leadsId=$this->input->get('leadsId');
		 $this->db->distinct();
		 $this->db->select('transferToOrgId');
		 $this->db->from('transferedLeadHistory');
		 $this->db->where('type !=', 'C');
		 $this->db->where('removed','0');
		 $this->db->where('oldLeadId',$leadsId);
		 $this->db->where('transferFromOrgId',$orgId);
		 $data = $this->db->get();
		 $transferedHistory = $data->result_array(); 
		if(!empty($transferedHistory))
		{
			$transferedData=""; 
			foreach ($transferedHistory as $history)
			{
				$transferedData=$transferedData.'<span class="badge badge-success">'.$this->getOrgNameByOrgId($history['transferToOrgId']).'&nbsp;<i class="icon-small icon-retweet"></i></span>'.' ';
			
			}
			return rtrim($transferedData,' ');
			
		}
		else {
		return " ";
		}
	}
	function getTransferedToCampaignName()
	{
		 $data['loggedUser']=$this->session->userdata('loggedIn');
		 $orgId = $data['loggedUser']['organizationId'];
		 $leadsId=$this->input->get('leadsId');
		 $this->db->distinct();
		 $this->db->select('campaignId');
		 $this->db->from('transferedLeadHistory');
		 $this->db->where('removed','0');
		 $this->db->where('type', 'C');
		 $this->db->where('oldLeadId',$leadsId);
		 $this->db->where('transferFromOrgId',$orgId);
		 $data = $this->db->get();
		 $transferedHistory = $data->result_array(); 
		if(!empty($transferedHistory))
		{
			$transferedData=""; 
			foreach ($transferedHistory as $history)
			{
				$transferedData=$transferedData.'<span class="badge badge-success">'.$this->getCampaignNameId($history['campaignId']).'&nbsp;<i class="icon-small icon-retweet"></i></span>'.' ';
			
			}
			return rtrim($transferedData,' ');
			
		}
		else {
		return " ";
		}
	}
	function getCampaignNameId($campaignId)
	{
		 $this->db->select('campaign');
		 $this->db->from('campaign');
		 $this->db->where('id',$campaignId);
		 $data = $this->db->get();
		 $campaignName = $data->result_array(); 
		 if(!empty($campaignName))
		 {
			return $campaignName[0]['campaign'];
		 }
		 else{
		 return false;
		 }
	}
	
	function getOrgNameByOrgId($orgId)
	{
		 $this->db->select('name');
		 $this->db->from('organization');
		 $this->db->where('id',$orgId);
		 $data = $this->db->get();
		 $orgName = $data->result_array(); 
		 if(!empty($orgName))
		 {
			return $orgName[0]['name'];
		 }
		 else{
		 return false;
		 }
	}
	
	function getuserCampaignByLeadId($leadId,$orgId)
	{
		$data=$this->db->query("SELECT campaign.* FROM `interestTracker` LEFT JOIN campaign on campaign.id = interestTracker.campaignId WHERE interestTracker.removed = '0' AND interestTracker.organizationId = '$orgId' AND interestTracker.leadId = '$leadId'");
		$currentCampaign = $data->result_array();
		return $currentCampaign;
	}
	
	
	/********************** interest tracker admin fresh and shared*************************/
	
	function getFreshInterestTrackerCount($search='key')
	{ 
	
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		
		//$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		 $city=$this->getLeadCityBySearchKeyword($search);
		
		$search=urldecode($search);
		$leadids=$this->getallFreshInterestTracker($this->organizationId);
			
			if($search != 'key')
			{
				if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city  $counselor $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
				
				}
				else{
				
					if(empty($phone) && empty($city) && empty($counselor))
					{
						return 0;
					}
					else
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city  $counselor  `leads`.`id` IN($leadids) and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
				}
			}
			else{
			
				if(empty($leadids))
					{
						return 0;
					}
					else
					{
						
						$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN ($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
			
			}
			return $query->num_rows(); 
	}
	// search
	
	function getFreshInterestTrackerSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$leadids=$this->getallFreshInterestTracker($this->organizationId);
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$intesretedCountry=$this->getLeadIntesretedCountryBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
			if($campaign)
				{
				
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr ");
				}
				else{
				
					if(empty($phone) && empty($city) && empty($counselor)&& empty($email))
					{
					
						return array();
					}
					else
					{
					
						if(empty($leadids))
						{
						
						return array();
						}
						else
						{
							if(!empty($email))
							{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor `leads`.`id` IN($leadids) AND $email  `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
							}else
							{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
							}
						}
					
					}
				}
				
		return $query->result_array();
		 
		
	}
	// search
	function freshInterestTracker($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		$leadids=$this->getallFreshInterestTracker($this->organizationId);
		
				if(empty($leadids))
					{
						return array();
					}
					else
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN ($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
					return $query->result_array();
					}
		
	}
	//shared interest tracker
	
	function getSharedInterestTrackerCount($search='key')
	{ 
	
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		
		//$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		 $city=$this->getLeadCityBySearchKeyword($search);
		
		$search=urldecode($search);
		$leadids=$this->getallSharedInterestTracker($this->organizationId);
			
			if($search != 'key')
			{
				if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city  $counselor $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
				
				}
				else{
				
					if(empty($phone) && empty($city) && empty($counselor))
					{
						return 0;
					}
					else
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city  $counselor  `leads`.`id` IN($leadids) and `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
				}
			}
			else{
			
				if(empty($leadids))
					{
						return 0;
					}
					else
					{
						
						$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN ($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
			
			}
			return $query->num_rows(); 
	}
	// search
	
	function getSharedInterestTrackerSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$leadids=$this->getallSharedInterestTracker($this->organizationId);
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$intesretedCountry=$this->getLeadIntesretedCountryBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
			if($campaign)
				{
				
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr ");
				}
				else{
				
					if(empty($phone) && empty($city) && empty($counselor)&& empty($email))
					{
					
						return array();
					}
					else
					{
					
						if(empty($leadids))
						{
						
						return array();
						}
						else
						{
							if(!empty($email))
							{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor `leads`.`id` IN($leadids) AND $email  `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
							}else
							{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $counselor `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
							}
						}
					
					}
				}
				
		return $query->result_array();
		 
		
	}
	// search
	function sharedInterestTracker($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		$leadids=$this->getallSharedInterestTracker($this->organizationId);
		
				if(empty($leadids))
					{
						return array();
					}
					else
					{
					
					$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN ($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
					
					return $query->result_array();
					}
		
	}
	function getallSharedInterestTracker($organizationId)
	{
		$trackerquery=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`organizationId`='$organizationId' AND `leads`.`isTrackerOn`='1' and  `leads`.id  in(SELECT `transferedLeadHistory`.`oldLeadId` FROM `transferedLeadHistory`) ");
		
		$allLeads=$trackerquery->result_array();
		$interestTrackerLeads="";
		if(!empty($allLeads))
		{
			
			foreach ($allLeads as $leads)
			{
				$interestTrackerLeads=$interestTrackerLeads.$leads['id'].",";
			}
		 return $leadids= rtrim($interestTrackerLeads,",");
		
		}
		else
		{
		return false;
		}
	}
	function getallFreshInterestTracker($organizationId)
	{
		
		$trackerquery=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`organizationId`='$organizationId' AND `leads`.`isTrackerOn`='1' and  `leads`.id not in(SELECT `transferedLeadHistory`.`oldLeadId` FROM `transferedLeadHistory`) ");
		$allLeads=$trackerquery->result_array();
		
		$interestTrackerLeads="";
		if(!empty($allLeads))
		{
			
			foreach ($allLeads as $leads)
			{
				$interestTrackerLeads=$interestTrackerLeads.$leads['id'].",";
			}
		 return $leadids= rtrim($interestTrackerLeads,",");
		}
		else
		{
		return false;
		}
	}
	//shared interest tracker
	
	
	
	/********************** interest tracker admin fresh and shared*************************/
	
	/********************** interest tracker counselor fresh and shared*************************/
	function getCounselorFreshInterestTrackerCount($search='key')
	{ 
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		
		$search=urldecode($search);
		$leadids=$this->getallFreshInterestTrackerCounselor($this->organizationId);
			
			if($search != 'key')
			{
			
				//$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
				
				}
				else{
				
				if(empty($phone) && empty($city) && empty($email))
					{
						return 0;
					}
					else
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email  `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
				}
			}
			else{
					if(empty($leadids))
					{
						return 0;
					}
					else
					{
						$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN($leadids) AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
			
			
			}
			return $query->num_rows(); 
	}
	function getAllFreshInterestTrackerLeadsSearchCounselor($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$leadids=$this->getallFreshInterestTrackerCounselor($this->organizationId);
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		//$intesretedCountry=$this->getLeadIntesretedCountryBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
		
		if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr ");
				}
				else{
				
					if(empty($phone) && empty($city) && empty($email))
					{
						return array();
					}
					else
					{
						if(empty($leadids))
						{
							return array();
						}
						else
						{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email  `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id`  ORDER BY `leads`.`id` desc limit $limitstr ");
						}
					
					}
				}
		
		
		
		return $query->result_array();
	}
	function getFreshInterestTrackerCounselor($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$leadids=$this->getallFreshInterestTrackerCounselor($this->organizationId);
		if(empty($leadids))
			{
				return array();
			}
		else
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
				return $query->result_array();
			}
		
	}
	// shared
	function getCounselorSharedInterestTrackerCount($search='key')
	{ 
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		
		$search=urldecode($search);
		$leadids=$this->getallSharedInterestTrackerCounselor($this->organizationId);
			
			if($search != 'key')
			{
			
				//$statusId=$this->getStatusIdBySearchKeyword($search);//added by deepak sharma
				if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
				
				}
				else{
				
				if(empty($phone) && empty($city) && empty($email))
					{
						return 0;
					}
					else
					{
					$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email  `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
				}
			}
			else{
					if(empty($leadids))
					{
						return 0;
					}
					else
					{
						$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN($leadids) AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc");
					}
			
			
			}
			return $query->num_rows(); 
	}
	function getAllSharedInterestTrackerLeadsSearchCounselor($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$leadids=$this->getallSharedInterestTrackerCounselor($this->organizationId);
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		//$intesretedCountry=$this->getLeadIntesretedCountryBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
		
		if($campaign)
				{
				$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email $campaign `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr ");
				}
				else{
				
					if(empty($phone) && empty($city) && empty($email))
					{
						return array();
					}
					else
					{
						if(empty($leadids))
						{
							return array();
						}
						else
						{
							$query=$this->db->query("SELECT * FROM `leads` WHERE   $phone $city $email  `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId' AND `leads`.`leadAssignedID`='$this->userId' GROUP BY `leads`.`id`  ORDER BY `leads`.`id` desc limit $limitstr ");
						}
					
					}
				}
		
		
		
		return $query->result_array();
	}
	function getSharedInterestTrackerCounselor($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$leadids=$this->getallSharedInterestTrackerCounselor($this->organizationId);
		if(empty($leadids))
			{
				return array();
			}
		else
			{
				$query=$this->db->query("SELECT * FROM `leads` WHERE `leads`.`id` IN($leadids) AND `leads`.`removed` = '0' AND `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$this->organizationId' GROUP BY `leads`.`id` ORDER BY `leads`.`id` desc limit $limitstr");
				return $query->result_array();
			}
		
	}
	// shared
	function getallSharedInterestTrackerCounselor($organizationId)
	{
		$trackerquery=$this->db->query("SELECT id FROM `leads`  WHERE  `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$organizationId' AND `leads`.`isTrackerOn`='1' and  `leads`.id  in(SELECT `transferedLeadHistory`.`oldLeadId` FROM `transferedLeadHistory`) ");
		
		$allLeads=$trackerquery->result_array();
		$interestTrackerLeads="";
		if(!empty($allLeads))
		{
			
			foreach ($allLeads as $leads)
			{
				$interestTrackerLeads=$interestTrackerLeads.$leads['id'].",";
			}
		 return $leadids= rtrim($interestTrackerLeads,",");
		
		}
		else
		{
		return false;
		}
	}
	function getallFreshInterestTrackerCounselor($organizationId)
	{
		
		$trackerquery=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`leadAssignedID`='$this->userId' AND `leads`.`organizationId`='$organizationId' AND `leads`.`isTrackerOn`='1' and  `leads`.id not in(SELECT `transferedLeadHistory`.`oldLeadId` FROM `transferedLeadHistory`) ");
		$allLeads=$trackerquery->result_array();
		
		$interestTrackerLeads="";
		if(!empty($allLeads))
		{
			
			foreach ($allLeads as $leads)
			{
				$interestTrackerLeads=$interestTrackerLeads.$leads['id'].",";
			}
		 return $leadids= rtrim($interestTrackerLeads,",");
		}
		else
		{
		return false;
		}
	}
	/********************** interest tracker counselor fresh and shared*************************/
	function downloadInterestTrackerBucket($campaignId,$leadsId)
	{
		return false;
	$trackerquery=$this->db->query("select distinct leads.id, leads.name,leads.email,leads.phone,leads.city,notes.notes,extendedProfile.CSR,extendedProfile.`appointmentTime`,extendedProfile.interestedCountry, extendedProfile.lastQualification,extendedProfile.interestedCourse,extendedProfile.bachelor,extendedProfile.intake,extendedProfile.master,extendedProfile.skypeId,extendedProfile.virtualConnectTime,extendedProfile.appointmentTime,obdlog.optedTime from leads LEFT JOIN extendedProfile ON leads.id = extendedProfile.leadsId left join obdlog on obdlog.leadId=leads.id Left Join (select notes,leadsId from `notes` order by id desc) as `notes` ON `notes`.`leadsId`=`leads`.`id` LEFT JOIN `transferedLeadHistory` on transferedLeadHistory.oldLeadId = leads.id WHERE transferedLeadHistory.`campaignId` = '$campaignId' and leads.id in ($leadsId) group by leads.id");
	return $allLeads=$trackerquery->result_array();
		
	}
	// added 
	
	function getallFreshInterestTrackerNotTransfered($organizationId)
	{
		
		$trackerquery=$this->db->query("SELECT id FROM `leads`  WHERE `leads`.`organizationId`='$organizationId' AND `leads`.`isTrackerOn`='1' and  `leads`.id not in(SELECT `transferedLeadHistory`.`oldLeadId` FROM `transferedLeadHistory`) ");
		$allLeads=$trackerquery->result_array();
		
		$interestTrackerLeads="";
		if(!empty($allLeads))
		{
			
			foreach ($allLeads as $leads)
			{
				$interestTrackerLeads=$interestTrackerLeads.$leads['id'].",";
			}
		 return $leadids= rtrim($interestTrackerLeads,",");
		}
		else
		{
		return false;
		}
	}
	
	function getFreshInterestTrackerCountNotTransfered($search='key')
	{ 
	
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}
		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		
		//$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		//$name=$this->getLeadNameBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		 $city=$this->getLeadCityBySearchKeyword($search);

		$search=urldecode($search);

		$campaignId=$this->getCampaignIdByname($search);

			$query=$this->db->query("select leads.id from leads left join `interestTracker` on `interestTracker`.`leadId`=leads.id where `interestTracker`.campaignId='$campaignId' and interestTracker.leadId  not in (select oldLeadId from `transferedLeadHistory` where `transferedLeadHistory`.campaignId='$campaignId' ) and leads.organizationId='$this->organizationId' group by leads.id");
			return $query->num_rows();
	}

	function getFreshInterestTrackerSearchNotTransfered($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		$searcharray=explode("-",$search);
		$parameter="'";
		foreach($searcharray as $arr)
		{
			$parameter= $parameter."'".$arr."',";
		}

		$searchKey=rtrim($parameter,",");
		$searchKeyword=substr($searchKey, 1);
		$search =str_replace('-','|',$search);// for regx match
		$campaign=$this->getCampaignIdBySearchKeyword($search);
		$email=$this->getLeadEmailBySearchKeyword($search);
		$counselor=$this->getLeadCounselorBySearchKeyword($search);
		$phone=$this->getLeadPhoneBySearchKeyword($search);
		$city=$this->getLeadCityBySearchKeyword($search);
		$search =str_replace('-','|',$search);
			$campaignId=$this->getCampaignIdByname($search);

			$query=$this->db->query("select leads.*,interestTracker.campaignId from leads left join `interestTracker` on `interestTracker`.`leadId`=leads.id where `interestTracker`.campaignId='$campaignId' and interestTracker.leadId  not in (select oldLeadId from `transferedLeadHistory` where `transferedLeadHistory`.campaignId='$campaignId' ) and leads.organizationId='$this->organizationId' group by leads.id limit $limitstr");
			
		return $query->result_array();
		 
		
	}
	
	function getCampaignIdByname($name)
	{
		$query=$this->db->query("SELECT id FROM `campaign` WHERE `campaign`.`campaign` ='$name'");
		$data=$query->result_array();
		return ($data)?$data[0]['id']:false;
	}
	function getCampaignNameById($id)
	{
	
		$query=$this->db->query("SELECT campaign FROM `campaign` WHERE `campaign`.`id` ='$id'");
		$data=$query->result_array();
		return ($data)?$data[0]['campaign']:false;
	}
	// added 
	function getLeadFromTransferedHistory($leadId)
	{
		$query=$this->db->query("SELECT oldLeadId FROM `transferedLeadHistory` WHERE `transferedLeadHistory`.`oldLeadId` ='$leadId'");
		$data=$query->result_array();
		return ($data)?$data[0]['oldLeadId']:false;
	}
	function getOptedTimeAndSmsVerification($leadId)
	{
		
		$obdquery=$this->db->query("SELECT leads.id FROM `leads` left join obdlog on obdlog.leadId=leads.id where leads.id ='$leadId' and leads.organizationId='$this->organizationId' and `obdlog`.optedTime in('12pm to 6pm','9am to 12pm','After 6pm')");
		$obdData=$obdquery->result_array();
		
		$query=$this->db->query("SELECT leads.id FROM `leads`  left join smsVerification on smsVerification.leadId=leads.id WHERE  smsVerification.isVerified='1' and leads.id ='$leadId' and leads.organizationId='$this->organizationId'");
		$data=$query->result_array();
		if($obdData || $data)
		{
			if($obdData)
			{
			return $obdData[0]['id'];
			}
			else if($data)
			{
			return $data[0]['id'];
			}
		}
		else
		{
		return false;
		}
	}
	function importLeadrunLookUp($key,$leadID,$orgId)
	{
		return true;
			$content=file_get_contents("http://LOOKup.unicel.in/LOOKUP?uname=meetlook&pass=Z%28Z@m8j~&dest=91".$key);
			
			$detail_check=explode(":",$content);
			$request_check=explode(":",$detail_check[2]);
			$requestid_check=explode(" ",$request_check[0]);
			$lookupStatus_check=explode("Reason",$requestid_check[0]);
			
			$lookupStatus_check=trim($lookupStatus_check[0]);
			if($lookupStatus_check=='FAILURE')
			{
				$reqId_failed=explode("Status",$detail_check[1]);
				$reqId=$reqId_failed[0];
			
				$query=$this->db->query("INSERT INTO `leadment_lms`.`lookup` (`leadId`, `organizationId`, `phone`, `lookupCity`, `updatedTime`, `requestId`, `Status`, `HomeCountry`, `HomeOperator`, `Dest`, `Reason`) VALUES ('$leadID', '$orgId', '".$key."', '', CURRENT_TIMESTAMP, '$reqId_failed[0]', '$lookupStatus_check', '', '', '', '')");
			}
			else
			{
				$detail=explode(":",$content);
				$request=explode(":",$detail[1]);
				$requestid=explode(" ",$request[0]);
				$requestid=explode("Dest",$requestid[0]);
				$dest=explode(":",$detail[2]);
				$destination=explode("Status",$dest[0]);
				$Status=explode(":",$detail[3]);
				$lookupStatus=explode(" ",$Status[0]);
				$lookupStatus=explode("Reason",$lookupStatus[0]);
				$homeCountry=explode(" ",$detail[5]);
				$HomeOperator=explode(" ",$detail[6]);
				$HomeOperator=explode("HomeCircle",$HomeOperator[0]);
				$circle=explode(":",$detail[7]);
				$query=$this->db->query("INSERT INTO `leadment_lms`.`lookup` (`leadId`, `organizationId`, `phone`, `lookupCity`, `updatedTime`, `requestId`, `Status`, `HomeCountry`, `HomeOperator`, `Dest`, `Reason`) VALUES ('$leadID', '$orgId', '".$key."', '$circle[0]', CURRENT_TIMESTAMP, '$requestid[0]', '$lookupStatus[0]', '', '$HomeOperator[0]', '$destination[0]', '')");
		}
	}
	function uploadPic($leadId)
	{
		$originalfilename = 'document_'.$leadId.'_'.rand(1000,999999);
		$filename = rtrim(base64_encode($originalfilename),'=');
		$config	=	array(
						'allowed_types' => 'jpg|jpeg|gif|png',
						'upload_path'	=>	$this->gallery_path_pic,
						'file_name'		=>	$filename
						);
		//$fileArr = explode('.',$_FILES['avatar']['name']);
		$ext = end(explode('.', $_FILES['avatar']['name']));
		//$originalfilename .= '.'.$fileArr[1];
		$originalfilename .= '.'.$ext;
		if($_FILES['avatar']['name']!='')
		{
			$this->load->library('upload',$config);
			$this->upload->overwrite = true;
			if(!$this->upload->do_upload('avatar'))
			{
				return $this->upload->display_errors();
			}
			else
			{
				$data = $this->upload->data();
				$record = $this->updateLeadPic($leadId,$filename.".".$ext);
				return "uploaded Successfully";
			}
		}
	}
	function updateLeadPic($leadId,$filename)
	{
		return true;
		$this->db->set('profileImage', $filename);
		$this->db->where('leadsId', $leadId);
		$this->db->update('extendedprofile');
	}
	function getAdvanceLeads($limit, $start, $phone,$email,$source,$status,$counsellor,$city)
	{
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}

		$createquery='';

		if($phone!="")
		{
			$phn='';
			$pn=explode(',',$phone);
			for($i=0;$i<sizeof($pn);$i++)
			{
				$phn.="'".$pn[$i]."',";
			}
			$phone=rtrim($phn, ",");
			//echo $phone;
				$createquery.=" AND phone in ($phone)";
		}
		if($email!="")
		{//echo $email;
			$mail='';
			$em=explode(',',$email);
			for($i=0;$i<sizeof($em);$i++)
			{
				$mail.="'".$em[$i]."',";
			}
			$email=rtrim($mail, ",");
			//echo $email;
				$createquery.=" AND email in ($email)";
		}
		if($source!="")
		{
			$sor='';
			$so=explode(',',$source);
			for($i=0;$i<sizeof($so);$i++)
			{
				$sor.="'".$so[$i]."',";
			}
			$source=rtrim($sor, ",");
			$createquery.=" AND source in ($source)";
		}
		if($status!="")
		{
			$state=explode(',',$status);
			$sta=array();
			for($i=0;$i<sizeof($state);$i++)
			{
				array_push($sta,$state[$i]);
			}
			$this->db->select('id');
			$this->db->from('leadStatusData');
			$this->db->where_in('detail',$sta);
			$statquery=$this->db->get();
			$statres=$statquery->result_array();

			$statid='';
			foreach($statres as $res)
			{
				$statid.="'".$res['id']."',";
			}
			$statid=rtrim($statid, ",");
			if($statid!='')
			{
				$createquery.=" AND status in ($statid) ";
			}
		}
		if($counsellor!="")
		{//echo  $counsellor;
			//echo urldecode($counsellor);
			$createquery.=" AND leadAssignedID=$counsellor ";
		}
		if($city!="")
		{
			$cit='';
			$ci=explode(',',$city);
			for($i=0;$i<sizeof($ci);$i++)
			{
				$cit.="'".$ci[$i]."',";
			}
			$city=rtrim($cit, ",");
			$createquery.=" AND city in ($city)";
		}

		$query=$this->db->query("SELECT  l.id,l.name,l.city,l.email,l.phone,l.status,l.source,l.leadCreatedTime,l.organizationId,l.leadAssignedID FROM (
        SELECT  id FROM  leads where removed='0' AND organizationId='$this->organizationId' ".$createquery." ORDER BY id LIMIT $limitstr ) o JOIN  leads l ON   l.id = o.id ORDER BY l.id");


		return $query->result_array();
	}

	function getAdvanceLeadsData($phone,$email,$source,$status,$counsellor,$city)
	{
		$createquery='';

		if($phone!="")
		{
			$phn='';
			$pn=explode(',',$phone);
			for($i=0;$i<sizeof($pn);$i++)
			{
				$phn.="'".$pn[$i]."',";
			}
			$phone=rtrim($phn, ",");
			//echo $phone;
				$createquery.=" AND phone in ($phone)";
		}
		if($email!="")
		{//echo $email;
			$mail='';
			$em=explode(',',$email);
			for($i=0;$i<sizeof($em);$i++)
			{
				$mail.="'".$em[$i]."',";
			}
			$email=rtrim($mail, ",");
			//echo $email;
				$createquery.=" AND email in ($email)";
		}
		if($source!="")
		{
			$sor='';
			$so=explode(',',$source);
			for($i=0;$i<sizeof($so);$i++)
			{
				$sor.="'".$so[$i]."',";
			}
			$source=rtrim($sor, ",");
			$createquery.=" AND source in ($source)";
		}
		if($status!="")
		{
			$state=explode(',',$status);
			$sta=array();
			for($i=0;$i<sizeof($state);$i++)
			{
				array_push($sta,$state[$i]);
			}
			$this->db->select('id');
			$this->db->from('leadStatusData');
			$this->db->where_in('detail',$sta);
			$statquery=$this->db->get();
			$statres=$statquery->result_array();

			$statid='';
			foreach($statres as $res)
			{
				$statid.="'".$res['id']."',";
			}
			$statid=rtrim($statid, ",");
			if($statid!='')
			{
				$createquery.=" AND status in ($statid) ";
			}
		}
		if($counsellor!="")
		{
			//echo urldecode($counsellor);
			$createquery.=" AND leadAssignedID=$counsellor ";
		}
		if($city!="")
		{
			$cit='';
			$ci=explode(',',$city);
			for($i=0;$i<sizeof($ci);$i++)
			{
				$cit.="'".$ci[$i]."',";
			}
			$city=rtrim($cit, ",");
			$createquery.=" AND city in ($city)";
		}

		$query=$this->db->query("SELECT  l.id,l.name,l.city,l.email,l.phone,l.status,l.source,l.leadCreatedTime,l.organizationId,l.leadAssignedID FROM (
        SELECT  id FROM  leads where removed='0' AND organizationId='$this->organizationId' ".$createquery." ORDER BY id) o JOIN  leads l ON   l.id = o.id ORDER BY l.id");


		return $query->num_rows();
	}
	function getAllLeadsCountCoun($search='key',$councelor)
	{
		$search=urldecode($search);
				$query=$this->db->query("SELECT leads.id FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`leadAssignedID`=$councelor And `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id`");
		return $query->num_rows();
	}

	function getCampaignType($camId)
	{
		$this->db->select('type');
		$this->db->from('campaign');
		$this->db->where('id',$camId);
		$this->db->where('removed','0');
		$result=$this->db->get();
		$res=$result->result();
		foreach($res as $r)
		{
			$tpe=$r->type;
		}
		if(!empty($tpe))
			return $tpe;
		else
			return '';
	}

	function updateInterestTrackerCamType($leadId,$camId,$camType)
	{
		$this->db->set('campaignType',$camType);
		$this->db->where('campaignId',$camId);
		$this->db->where('leadId',$leadId);
		$this->db->update('interestTracker');
	}

	function insertThirdParty($leadId,$camId,$bor,$borlim,$adres)
	{
		$this->db->where('leadId',$leadId);
		$this->db->where('campaignId',$camId);
		$this->db->from('thirdParty');
		$cnt=$this->db->count_all_results();
		if($cnt==0)
		{
			$this->db->set('leadId',$leadId);
			$this->db->set('campaignId',$camId);
			$this->db->set('address',$adres);
			$this->db->set('borrower',$bor);
			$this->db->set('borrowerLimit',$borlim);
			$this->db->insert('thirdParty');
		}
		else
		{
			$this->db->set('address',$adres);
			$this->db->set('borrower',$bor);
			$this->db->set('borrowerLimit',$borlim);
			$this->db->where('campaignId',$camId);
			$this->db->where('leadId',$leadId);
			$this->db->update('thirdParty');
		}
	}
	function getThirdParty($leadId)
	{
		$this->db->select('*');
		$this->db->from('thirdParty');
		$this->db->where('leadId',$leadId);
		$result=$this->db->get();
		$res=$result->result();
		return $res;
	}
}
 ?>
