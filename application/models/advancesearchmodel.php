<?php

 class AdvanceSearchmodel extends CI_Model
 { 
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$loggedUser=$this->session->userdata('loggedIn');
		$this->organizationId=$loggedUser['organizationId'];
		$this->userId=$loggedUser['id'];
	}
	function getLeadEmailBySearchKeyword($searchKeyword)
	{
		
		$statusQuerys=$this->db->query("SELECT id,email FROM `leads` WHERE leads.email REGEXP '$searchKeyword' " );
		
		$statusQuery=$statusQuerys->result_array();
		if(!empty($statusQuery))
		{
			return "leads.email REGEXP '$searchKeyword' and";
		}
		else return false;
	}
	
	function getAllLeadsCount($search='key')
	{
	
		$search=urldecode($search);
		// $searcharray=explode("-",$search);
		// $parameter="'";
		// foreach($searcharray as $arr)
		// {
			// $parameter= $parameter."'".$arr."',";
		// }
		 // $searchKey=rtrim($parameter,",");
		  // $searchKeyword=substr($searchKey, 1);
		 // $search =str_replace('-','|',$search);		
		//$statusQuery=$this->leadmodel->getStatusIdBySearchKeywords($searchKeyword);
		
		// $counselor=$this->leadmodel->getLeadCounselorBySearchKeyword($search);
		 // $phone=$this->leadmodel->getLeadPhoneBySearchKeyword($search);
		// $city=$this->leadmodel->getLeadCityBySearchKeyword($search);
		// $source=$this->leadmodel->getLeadSourceBySearchKeyword($search);
		// $email=$this->getLeadEmailBySearchKeyword($search);
		
			// if($search != 'key')
			// {
			// $query=$this->db->query("SELECT leads.id,leads.name,leads.leadCreatedTime,leads.phone,leads.email,leads.leadAssignedID,leads.city,leads.status,leads.source FROM `leads` WHERE $statusQuery $phone $city $source $counselor $email `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id`");
			// }
			// else
			// {
				// $query=$this->db->query("SELECT leads.id,leads.name,leads.leadCreatedTime,leads.phone,leads.email,leads.leadAssignedID,leads.city,leads.status,leads.source FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id`");
			
			// }
			
			// if($search != 'key')
			// {
			// $query=$this->db->query("SELECT leads.id,leads.name,leads.leadCreatedTime,leads.phone,leads.email,leads.leadAssignedID,leads.city,leads.status,leads.source FROM `leads` WHERE  `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id`");
			// }
			// else
			// {
				$query=$this->db->query("SELECT leads.id FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id`");


			//}
			return $query->num_rows();
	}
	function getAllLeadsSearch($limit, $start, $search)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		
		$search=urldecode($search);
		// $searcharray=explode("-",$search);
		// $parameter="'";
		// foreach($searcharray as $arr)
		// {
			// $parameter= $parameter."'".$arr."',";
		// }
		 // $searchKey=rtrim($parameter,",");
		  // $searchKeyword=substr($searchKey, 1);
		 // $search =str_replace('-','|',$search);	
		// $statusQuery=$this->leadmodel->getStatusIdBySearchKeywords($searchKeyword);
		// $counselor=$this->leadmodel->getLeadCounselorBySearchKeyword($search);
		
		// $phone=$this->leadmodel->getLeadPhoneBySearchKeyword($search);
		// $city=$this->leadmodel->getLeadCityBySearchKeyword($search);
		// $source=$this->leadmodel->getLeadSourceBySearchKeyword($search);
		
		 // $email=$this->getLeadEmailBySearchKeyword($search);
		
		//$query=$this->db->query("SELECT leads.id,leads.name,leads.leadCreatedTime,leads.phone,leads.email,leads.leadAssignedID,leads.city,leads.status,leads.source FROM `leads` WHERE  $statusQuery $phone $city $source $counselor $email `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` limit $limitstr");
		
		//$query=$this->db->query("SELECT leads.id,leads.name,leads.leadCreatedTime,leads.phone,leads.email,leads.leadAssignedID,leads.city,leads.status,leads.source FROM `leads` WHERE   `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` limit $limitstr");
		$query=$this->db->query("SELECT  l.id,l.name,l.city,l.email,l.phone,l.status,l.source,l.leadCreatedTime,l.organizationId,l.leadAssignedID FROM (
        SELECT  id FROM  leads where removed='0' AND organizationId='$this->organizationId' ORDER BY id LIMIT $limitstr ) o JOIN  leads l ON   l.id = o.id ORDER BY l.id");
		
		return $query->result_array();
		
	}
	function getAllLeads($limit, $start)
	{ 
		if($start==0)
		{$limitstr=$limit;}
		else
		{$limitstr=$start.','.$limit;}
		//$query=$this->db->query("SELECT leads.id,leads.name,leads.leadCreatedTime,leads.phone,leads.email,leads.leadAssignedID,leads.city,leads.status,leads.source FROM `leads` WHERE `leads`.`removed` = '0' AND `leads`.`organizationId`='$this->organizationId'  GROUP BY `leads`.`id` limit $limitstr");
		$query=$this->db->query("SELECT  l.id,l.name,l.city,l.email,l.phone,l.status,l.source,l.leadCreatedTime,l.organizationId,l.leadAssignedID FROM (
        SELECT  id FROM  leads where removed='0' AND organizationId='$this->organizationId' ORDER BY id DESC LIMIT $limitstr ) o JOIN  leads l ON   l.id = o.id ORDER BY l.id");
		return $query->result_array();
	
	}
	 
	function getAdvanceLeads($limit, $start, $phone,$source,$status,$city)
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
        SELECT  id FROM  leads where removed='0' AND organizationId='$this->organizationId' ".$createquery." ORDER BY id DESC LIMIT $limitstr ) o JOIN  leads l ON   l.id = o.id ORDER BY l.id");


		return $query->result_array();
	}

	function getAdvanceLeadsData($phone,$source,$status,$city) {
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
 }
 ?>
