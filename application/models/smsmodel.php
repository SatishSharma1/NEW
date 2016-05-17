<?php

 class Smsmodel extends CI_Model
 { 
	function __construct() {
		parent::__construct();
		$this->load->model('api_setting_model', 'setting');
	}

	public function getTemplate($orgId) {
		$tmpQuery = $this->db->select('template')->get_where('sms', array(
				'organizationId' => $orgId,
				'approved' => '1'
			));
	}
	
	public function getTemplateAndName($tempId) {
		$tmpQuery = $this->db->select('template,name')->get_where('sms', array(
				'id' => $tempId,
				'approved' => '1'
			));
			$sms_details = $tmpQuery->result();
			return $sms_details;
	}
	
	public function checkSMSPlugin($orgId){
		$data = $this->setting->smsSetting($orgId);
		if(!$data) {
			$plugin = 'noplugin';
		}else if($data['status'] != '1') {
			$plugin  = 'notactivated';
		}else{
			$plugin = 'activated';
		}
		return $plugin;
	}
	
	public function sendSMS($orgId, $dest, $msg,$name) {
		$data = $this->setting->smsSetting($orgId);
		$data['caller'] = $dest;
		$data['sms'] = $msg;
		$data['name'] = $name;
		$url = $this->setting->parseSetting($data);
		//var_dump($url);
		if(!$url) {
			return "Invalid Setting";
		}
		// return $url;
		file_get_contents($url);
		return false;
	}
	
	public function saveSMSNote($leadId = 0, $mesg) {
		$this->db->insert('notes', array(
				'leadsId' => $leadId,
				'notes' => $mesg
			));
	}
	function saveSmsTemplate( $orgId ) {
		$sms = array(
			'name'=> $this->input->post('name'),
			'template' => $this->input->post('template'),
			'organizationId' => $orgId
		);
		$this->db->insert('sms', $sms);
		return true;
	}

	function getAllSmsTemplates($orgId) {
		$data = array('organizationId'=>$orgId,'removed'=>'0');
		//$this->db->limit($limit,$start);
		$rs = $this->db->get_where('sms',$data);
		$sms = $rs->result();
		return $sms;
	}

	function _get_all($orgId){
      
    	$this->db->select('count(id) total');
	$this->db->where('organizationId',$orgId);
	$this->db->where('removed','0');
	$result =$this->db->get('sms');
     return $result->row()->total;
}

	function getOrgUsedSms($orgId) {
		$rs = $this->db->get_where('usage',array('organizationId'=>$orgId));
		$orgUsages = $rs->row();
		$smsUsages = $orgUsages->sms;
		return $smsUsages;
	}

	function checkOrgSmsLimit($orgId) {
		$rs = $this->db->get_where('organization',array('id'=>$orgId));
		$org = $rs->row();
		$packageId = $org->package;
		$rs = $this->db->get_where('package',array('id'=>$packageId));
		$orgPackage = $rs->row();
		$limitSms = $orgPackage->sms;
		$rs = $this->db->get_where('usage',array('organizationId'=>$orgId));
		$orgUsages = $rs->row();
		$smsUsages = $orgUsages->sms;
		if($smsUsages >= $limitSms) {
			return false;
		} else {
			return true;
		}
	}

	function getApprovedSmsTemplates($orgId) {
		$data = array('organizationId'=>$orgId, 'approved'=>'1', 'removed'=>'0');
		$rs = $this->db->get_where('sms',$data);
		$sms = $rs->result();
		return $sms;
	}

	function getAllSmsTemplatesMasterAdmin() {
		$this->db->select('*,sms.id as id,sms.name as name,organization.name as orgName');
		$this->db->from('sms');
		$this->db->join('organization', 'sms.organizationId = organization.id');
		$sms = $this->db->get();                //getting all sms templates form DB
		$this->db->order_by("organization.name"); 
		return $sms->result();
	}

	function getOrgSmsDetails() {
		$this->db->select('*');
		$this->db->from('organization');
		$this->db->join('usage','organization.id = usage.organizationId');
		$org = $this->db->get();
		return $org->result();
	}

	function getPackageSms($packageId) {
		$this->db->select('sms');
		$this->db->from('package');
		$this->db->where('id',$packageId);
		$rs = $this->db->get();
		$org = $rs->row();
		return $org->sms;
		
	}
	function getTemplateById($smsId)
	{
		$query = $this->db->get_where('sms',array('id'=>$smsId));
		$sms = $query->row();
		return $sms->template;
	}
	
	/***for sms username passwording get ****/
	function checkSmsApiDetails($orgId)
	{
		$query = $this->db->get_where('smsApi',array('organizationId'=>$orgId));
		$rs = $query->row();
		if($rs)
		{
			return $rs;
		}
		return false;
		
	}
	
	/**for orgnization details for sms api */
	
	function checkOrgSmsApiDetails()
	{
		$this->db->select('*');
		$this->db->from('smsApi');
		$data = $this->db->get();
		return $data->result();
	}
	function saveApprovedSmsTemplate($orgId,$userId)
	{
		$query = $this->db->get_where('sms',array('name'=>$this->input->post('name'),'removed'=>'0'));
		$rs = $query->row();
		if($rs)
		{
			return 'exist';
		}
		else{
		$sms = array(
				'name'=>$this->input->post('name'),
				'template'=>$this->input->post('template'),
				'approved'=>'0',
				'approvedById'=>$userId,
				'organizationId'=>$orgId
				);
		$this->db->insert('sms',$sms);
		return 'sucess';
		}
		
	}
	function saveApprovedOBDTemplate($orgId,$userId)
	{
		
		$this->db->select('name');
		$this->db->from('obd');
		$this->db->where('name',$this->input->get('url'));
		$this->db->where('removed','0');
		$rs = $this->db->get();
		$org = $rs->row();
		if(!empty($org->name))
		echo "false";
		else
		echo "true";
		exit;
		
	}
	function insertOBDTemplate($orgId,$userId)
	{
		$obd = array(
				'name'=>$this->input->post('url'),
				'templateBeforeEvent'=>$this->input->post('obdtemplate'),
				'templateOnEventDay'=>$this->input->post('obdtemplate1'),
				'approved'=>'1',
				'approvedById'=>$userId,
				'organizationId'=>$orgId
				);
		$this->db->insert('obd',$obd);
		return 'sucess';
		
	}
	function checkSmsExist()
	{
		$this->db->select('name');
		$this->db->from('sms');
		$this->db->where('name',$this->input->get('name'));
		$this->db->where('removed','0');
		$rs = $this->db->get();
		$org = $rs->row();
		if(!empty($org->name))
		echo "false";
		else
		echo "true";
		exit;
		
	}
 }
?>
