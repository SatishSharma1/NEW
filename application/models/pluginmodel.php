<?php

 class Pluginmodel extends CI_Model
 { 
	function __construct() {
		parent::__construct();
		$this->load->database();
	}


	public function getOrganizations($filter) {
		$plgs = array(
				1 => 'sms',
				2 => 'call',
				3 => 'calender'
			);

		$this->db->select("organization.id as orgId, DATE_FORMAT(users.userCreatedOn, '%b %d, %Y') created_at, organization.name name", false)
			->join('users', 'organization.id = users.organizationId AND users.userLevel = 2');
		if($filter) {
			$this->db->like('organization.name', $filter);
		}
		$resQuery = $this->db->get_where('organization', array());
			
	
		$orgsList = $resQuery->result_array();
		$statusQuery = $this->db->get_where('api_settings', array(
						'type' => 'status'
					));
		$plugRows = $statusQuery->result();
		$myplugins = array();
		// settings plugins, organization as key
		if($plugRows) {
			foreach ($plugRows as $row) {
				$myplugins[$row->organizationId][$plgs[$row->pluginId]] = $row->value;
			}
		}
		// dp($myplugins);
		$myresult = array();
		foreach ($orgsList as $key => $orgs) {
			// setting default plugin  to zero;
			foreach ($plgs as $plg) {
				$myresult[$key][$plg] = 0;
			}
			// converting data to an array like normal
			foreach ($orgs as $item => $value) {
				$myresult[$key][$item] = $value;
			}
			if(isset($myplugins[$orgs['orgId']])) {
				$orgPlg = $myplugins[$orgs['orgId']];
				foreach ($plgs as $plg) {
					if(isset($orgPlg[$plg])) {
						$myresult[$key][$plg] = $orgPlg[$plg];
					}
				}
			}
		}
		// dp($myresult);
		return $myresult;
	}

	public function getOrgName($id) {
		$res = $this->db->select('name')
						->get_where('organization', array(
							'id' => $id
						));
		return $res->row()->name;
	}
	public function getOrganizationById($id) {
		$plgs = array(
				1 => 'sms',
				2 => 'call',
				3 => 'calender'
			);
		$res = array();
		$org = $this->db->get_where('organization', array('id' => $id));
		$plugin = $this->db->select('pluginId, type, value')
			->get_where('api_settings', array('organizationId' => $id));
		if($rows = $plugin->result()) {
			foreach ($rows as $row) {
				$res[$plgs[$row->pluginId]][$row->type] = $row->value;
			}
		}
		$returns = array_merge($res, $org->row_array());
		return $returns;
	}

	public function getPluginByOrg($id) {
		$plgs = array(
				1 => 'sms',
				2 => 'call',
				3 => 'calender'
			);
		$res = array();
		$plugin = $this->db->select('pluginId, type, value')
			->get_where('api_settings', array('organizationId' => $id));
		if($rows = $plugin->result()) {
			foreach ($rows as $row) {
				$res[$plgs[$row->pluginId]][$row->type] = $row->value;
			}
		}
		return $res;
	}

	public function enable($orgId, $pluginId) {
		$inp = $this->input;

		$this->db->insert_batch('api_settings', array(
				array(	
						'organizationId' => $orgId,
						'pluginId' => $pluginId,
						'type' => 'key',
						'value' => $inp->post('key'),
					),
				array(
						'organizationId' => $orgId,
						'pluginId' => $pluginId,
						'type' => 'url',
						'value' => $inp->post('url'),
					),
				array(
						'organizationId' => $orgId,
						'pluginId' => $pluginId,
						'type' => 'status',
						'value' => 1,
					)
			));
	}

	public function disable($orgId, $pluginId) {
		$this->db->delete('api_settings', array(
				'organizationId' => $orgId,
				'pluginId' => $pluginId
			));
	}


	function getOrgPlugins($orgId)
	{
		$data = $this->db->get_where('plugins',array('organizationId'=>$orgId));
		return $data->row();
	}
	function savelookup($data)
	{
		$detail = $this->lookupVerify($data['phone']);
		$lookup = $this->getlookupvalue($detail);
		print_r($lookup);//exit;
		//exit;
		$status = '';
		$data = array(
			'leadId' => $data['id'],
			'organizationId' => $data['orgId'],
			'phone' => $data['phone'],
			'requestId' => $lookup['Request ID'],
			'Status' => $lookup['Status']
			);
		if($lookup['Status'] === 'SUCCESS')
		{
			$lookupCity= '';
			//echo $lookupCity;
			$data ['lookupCity'] = $lookup['HomeCircle'];
			$data ['HomeCountry'] = $lookup['HomeCountry'];
			$data ['HomeOperator'] = $lookup['HomeOperator'];
		}
		
		//echo $no['phone'];
		
		$this->db->insert('lookup',$data);
	}
	function getlookupvalue($array)
	{
		//
		//$lookupCity = $circle[1];
		$lookup = array();
		for($i=0;$i<count($array);$i++)
		{
			//echo "<br>".$array[$i];
			$temp=explode(":",$array[$i]);
			$lookup[$temp[0]] =$temp[1];
		}
		//print_r($lookup);
		return $lookup;
	}
	
	public function obdverify($mobile)
	{
		$obdcall=file_get_contents("http://vapi.unicel.in/voiceapi?request=voiceobd&uname=meetuni&pass=letsrock&obdid=237&type=S&dest=91".$mobile."&msgtype=P&msg=Congratulations.wav&schtm=");
		
		return $obdcall;
	}
	
	function isLookupEnable($orgId)
	{
		$this->db->select('lookup');
		$this->db->from('plugins');
		$this->db->where('organizationId',$orgId);
		return $this->db->get()->row()->lookup;
	}
	function isOBDEnable($orgId)
	{
		$this->db->select('obdVerification');
		$this->db->from('plugins');
		$this->db->where('organizationId',$orgId);
		return $this->db->get()->row()->obdVerification;
	}
	function saveOBDrecord($values)
	{
		$data = array(
			'leadId'=>$values['leadId'],
			'phone'=>$values['phone'],
			'requestId'=>$values['response'],
			'organizationId'=>$values['orgId']
		);
		$this->db->insert('obdlog',$data);
	}
	function getAllOrgPlugins()
	{
		$this->db->select("*");
		$this->db->from('plugins');
		$this->db->join('organization', 'organization.id = plugins.organizationId');
		return $this->db->get()->result();
	}
	function isInterestTrackerEnable($orgId)	
	{		
		$this->db->select('interestTracker');		
		$this->db->from('plugins');		
		$this->db->where('organizationId',$orgId);		
		return $this->db->get()->row()->interestTracker;	
	}
	function isCampaignEnable($orgId)	
	{		
		$this->db->select('campaign');		
		$this->db->from('plugins');		
		$this->db->where('organizationId',$orgId);		
		return $this->db->get()->row()->campaign;	
	}	
	function isInterestTrackerExist($orgId,$userId,$leadsId,$campaign)	
	{	
		$this->db->select('id');		
		$this->db->from('interestTracker');		
		$this->db->where('userId',$userId);		
		$this->db->where('leadId',$leadsId);		
		$this->db->where('campaignId',$campaign);		
		$this->db->where('removed','0');		
		$this->db->where('organizationId',$orgId);		
		$data = $this->db->get();		
		$getData= $data->result_array();				
		if(isset($getData[0]['id']))		
		{			
		return $getData[0]['id'].',';		
		}			
		else return	false;			
	}
	
	function checkInterestTrackerExist($orgId,$leadsId)	
	{		
		$this->db->select('id');		
		$this->db->from('interestTracker');		
		$this->db->where('leadId',$leadsId);		
		$this->db->where('removed','0');		
		//$this->db->where('isTracker','1');		
		$this->db->where('organizationId',$orgId);		
		$data = $this->db->get();		
		$getData= $data->result_array();				
		if(isset($getData[0]['id']))		
		{			
		return $getData[0]['id'].',';		
		}			
		else return	false;			
	}
	function getInterestTrackerData($orgId,$leadsId)	
	{		
		$this->db->select('*');		
		$this->db->from('interestTracker');		
		$this->db->where('leadId',$leadsId);		
		$this->db->where('removed','0');		
		$this->db->where('organizationId',$orgId);		
		$this->db->order_by('id', 'DESC');
		$this->db->limit('1');
		$data = $this->db->get();		
		$getData= $data->result_array();				
		if(isset($getData))		
		{			
			return $getData;		
		}			
		else return	false;			
	}
	function getNotificationAlert($orgId)
	{
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$userLevel=$data['loggedUser']['userLevel'];
		$userId=$data['loggedUser']['id'];
		$currentDate =date('Y-m-d H:i:s');
		$Date =date('Y-m-d');
		$currentDates=strtotime($currentDate);
		if($userLevel==$this->config->item('Admin'))
		{
			$data=$this->db->query("select leads.id,leads.email,leads.phone,leads.name,extendedProfile.callBackDate from extendedProfile left join leads on leads.id=extendedProfile.leadsId where (UNIX_TIMESTAMP(callBackDate)>$currentDates) and callBackDate like '%$Date%' and leads.organizationId='$orgId' ");
			$result=$data->result_array();
			return $result;
		}
		else if($userLevel==$this->config->item('CounslorLevel'))
		{
			$data=$this->db->query("select leads.id,leads.email,leads.phone,leads.name,extendedProfile.callBackDate from extendedProfile left join leads on leads.id=extendedProfile.leadsId where (UNIX_TIMESTAMP(callBackDate)>$currentDates) and callBackDate like '%$Date%' and leads.organizationId='$orgId' and leadAssignedID='$userId'");
			$result=$data->result_array();
			return $result;
		}
		else
		{
		return false;
		}
		
		
	}
 }
?>