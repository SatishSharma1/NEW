<?php

class Emailmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getAllEmailTemplates()
	{
		$this->db->select('*');
		$this->db->from('email');
		$this->db->where('removed','0');
		$data=$this->db->get();
		$emails=$data->result();
		if($emails)
			return $emails;
		else
			return false;
	}

	function saveEmailTemplate($orgId)
	{
		$email = array(
				'name'=>$this->input->post('name'),
				'template'=>$this->input->post('template'),
				'organizationId'=>$orgId
				);
		$this->db->insert('email',$email);
		
		return true;
	}

	function getOrgUsedEmail($orgId)
	{
		$rs = $this->db->get_where('usage',array('organizationId'=>$orgId));
		$orgUsages = $rs->row();
		$emailUsages = $orgUsages->email;
		return $emailUsages;
	}

	function getAllEmailTemplatesMasterAdmin()
	{
		$this->db->select('*,email.id as id,email.name as name,organization.name as orgName');
		$this->db->from('email');
		$this->db->where('removed', '0');
		$this->db->join('organization', 'email.organizationId = organization.id');
		$email = $this->db->get();                //getting all email templates form DB
		$this->db->order_by("organization.name"); 
		return $email->result();
	}

	function getOrgEmailDetails()
	{
		$this->db->select('*');
		$this->db->from('organization');
		$this->db->join('usage','organization.id = usage.organizationId');
		$org = $this->db->get();
		return $org->result();
	}

	function getPackageEmail($packageId)
	{
		$this->db->select('email');
		$this->db->from('package');
		$this->db->where('id',$packageId);
		$rs = $this->db->get();
		$org = $rs->row();
		return $org->email;
	}

	function checkOrgEmailApiDetails()
	{
		$this->db->select('*');
		$this->db->from('smsApi');
		$data = $this->db->get();
		return $data->result();
	}

	function checkOrgEmailLimit($orgId)
	{
		$rs = $this->db->get_where('organization',array('id'=>$orgId));
		$org = $rs->row();
		$packageId = $org->package;
		$rs = $this->db->get_where('package',array('id'=>$packageId));
		$orgPackage = $rs->row();
		$limitEmail = $orgPackage->email;
		
		
		$rs = $this->db->get_where('usage',array('organizationId'=>$orgId));
		$orgUsages = $rs->row();
		$emailUsages = $orgUsages->email;
		
		if($emailUsages >= $limitEmail)
		{
			return false;
		}
		else{
			return true;
		}
	}

	function  getApprovedEmailTemplates($orgId)
	{
		$data = array('organizationId'=>$orgId, 'approved'=>'1', 'removed'=>'0');
		$rs = $this->db->get_where('email',$data);
		$email = $rs->result();
		return $email;
	}
	function getTemplateById($emailId)
	{
	  $query = $this->db->get_where('email',array('id'=>$emailId));
	  $email = $query->row();
	  return $email->template;
	}
	function checkEmailExist()
	{
		$this->db->select('name');
		$this->db->from('email');
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