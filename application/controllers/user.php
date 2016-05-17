<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
		parent::__construct();
		// $this->load->model('campaignmodel','',TRUE);
		$this->load->model('usermodel','',TRUE);
		$this->load->model('agentmodel','',TRUE);
		$this->load->model('calender/Member_model');
		//$this->load->library('layout');
		//$this->load->library('Auth');
		//$this->config->load('email');
		$this->load->config('email', TRUE);
		$this->load->helper('form');

	}
/* start of  my editting..mukesh   */
       
         


/* end of my edditaable code...*/
	function index() {

		if($this->session->userdata('loggedIn'))
		{
		//	die('aa');
			redirect('home','refresh');
		}
		else
		{
			
			redirect('login','refresh');
		}
	}
	function login()
	{
		
		$data['viewPage'] = 'Login';
		if($this->session->userdata('loggedIn'))
		{
			redirect('home','refresh');
		}		
		else
		{
			$data=array();
			
			if($this->input->cookie('userEmail'))
			{
				$data=array('userEmail'=>$this->input->cookie('userEmail'),'password'=>$this->input->cookie('password'));
				$_COOKIE = array();
			}
			
			$this->form_validation->set_rules('userEmail', 'User Email', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_checkLoginDetails');
			
			if($this->form_validation->run() == FALSE)
			{

			}
			else
			{
				redirect('home' , 'refresh');
			}
			
			 $key = $this->input->get('key');
			$orgID = $this->agentmodel->get_orgId_by_Key($key);
			  $data['logoname']= $this->usermodel->get_image_name_orgid($orgID);

			$this->load->view('user/login',$data);
		}
	}
	function checkLoginDetails($password)
	{

		//die('lala');
		
	 	$userEmail = $this->input->post('userEmail');

	
		//Checking user Exists or Not//
		$userExists = $this->usermodel->checkExists($userEmail);
		if(!$userExists)
		{
			$this->form_validation->set_message('checkLoginDetails', 'Invalid UserName or Password');
			return FALSE;
		}
		
		$userActivated = $this->usermodel->isActivated($userEmail);
		if(!$userActivated)
		{
			$this->form_validation->set_message('checkLoginDetails', 'Sorry, Your Account Not Activated Yet!');
			return FALSE;
		}


		$result = $this->usermodel->login($userEmail, $password);
		//die($result);
		if($result>=1)
		{
			$sessionArray = array();
			foreach($result as $row)
			{
				$sessionArray = array(
				'id' => $row->id,
				'userName' => $row->userName,
				'userLevel' => $row->userLevel,
				'userEmail' => $row->userEmail,
				'organizationId' => $row->organizationId,
				'user_status' => $row->user_status,
				'img_path' => $row->userImagePath,
				'userPhone' => $row->userPhone
				);
				
				if($this->input->post('rememberMe'))
				{
					$userEmailCookie=array(
								'name'	=>	'userEmail',
								'value'	=>	$userEmail,
								'expire'=>	3600*24*7
								);
					delete_cookie("userEmail");
					$this->input->set_cookie($userEmailCookie);
					$passwordCookie=array(
								'name'	=>	'password',
								'value'	=>	$password,
								'expire'=>	3600*24*7
								);
					delete_cookie("password");
					$this->input->set_cookie($passwordCookie);
				}
				$this->session->set_userdata('loggedIn', $sessionArray);
				$this->usermodel->record_user_activity_login($sessionArray);	//Enter Data to user_activity table for recording user's activity.
				//$this->auth->login($row->userEmail, $password);   //Login to Calender Plugin
					
			}
			return TRUE;
		}
		else if($result=='accessDenied')
		{
			$this->form_validation->set_message('checkLoginDetails', 'Permission Denied! Please contact to Administrator');
			return false;
		}
 		else
		{
			$this->form_validation->set_message('checkLoginDetails', 'Incorrect Email/Password Combination');
			return false;
		}
	
	}
	
	function logout() {
		$sessionArray=$this->session->userdata('loggedIn');
		$this->usermodel->record_user_activity_logout($sessionArray);	//Enter Data to user_activity table for recording user's activity.
		$this->session->sess_destroy();
		redirect('user/login', 'refresh');
	}


	function get_user_busy() {
		$sessionArray=$this->session->userdata('loggedIn');
		$this->usermodel->record_user_activity_busy($sessionArray);	//Enter Data to user_activity table for recording user's activity.
		redirect('home', 'refresh');
	}
	
	function get_user_break() {
		$sessionArray=$this->session->userdata('loggedIn');
		$this->usermodel->record_user_activity_break($sessionArray);	//Enter Data to user_activity table for recording user's activity.
		redirect('home', 'refresh');
	}
	
	function get_user_available() {
		$sessionArray=$this->session->userdata('loggedIn');
		$this->usermodel->record_user_activity_login($sessionArray);	//Enter Data to user_activity table for recording user's activity.
		redirect('home', 'refresh');
	}



		//Manage Users//
	function manageUsers($filter = '') {

		$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.validate.js'));
		$data['js']=put_headers_js();
		
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		$userId = $data['loggedUser']['id'];
		if(isset($_GET['query'])) {
			$filter = $_GET['query'];
		}
		if(!($this->session->userdata('loggedIn')))
		{
			redirect('login','refresh');
		}
		else if($data['loggedUser']['userLevel']!=$this->config->item('MasterAdmin') && $data['loggedUser']['userLevel']!=$this->config->item('Admin'))
		{
			redirect('home','refresh');
		}
		
		if($this->input->post('submitCreateUser'))
		{
			$this->form_validation->set_rules('name', 'User Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.userEmail]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
             
             if($this->input->post('code')){
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');	
			}
			$this->form_validation->set_message('is_unique', 'Email Already Registered!');
			//$this->form_validation->set_rules('userAgreement', 'User Agreement', 'callback_userAgreement');
			if($this->form_validation->run())
			{
				$data['createdUserId'] = $this->usermodel->saveUser($orgId,$userId);
				$data['uploadError'] = $this->usermodel->upload_profile_pic($data['createdUserId']);
				$loggedIn=$this->session->userdata('loggedIn');
				$data['bcc']=$loggedIn['userEmail'];
				$data['userEmail'] = $this->input->post('email');
				$data['userName'] = $this->input->post('name');
				$data['userPassword'] = $this->input->post('password');
				$data['siteName'] = $this->config->item('webSiteName','email');
				$this->user_email_send('createUser',$data);
				$level=1;
			//	$this->Member_model->registerUser($data['userEmail'], $data['userPassword'], $data['userEmail'], $image, $level, $signupdate);  //Register for Calender
			
			}
		}
		if($this->input->post('submitUpdateUser'))
		{
			$this->form_validation->set_rules('name', 'User Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[10]|xss_clean');	
			$this->form_validation->set_message('is_unique', 'Email Already Registered!');
			//$this->form_validation->set_rules('userAgreement', 'User Agreement', 'callback_userAgreement');
			if($this->form_validation->run())
			{
				$data['updateUser'] = $this->usermodel->updateUser($orgId,$this->input->post('updateUserId'));
				$data['uploadError'] = $this->usermodel->upload_profile_pic($this->input->post('updateUserId'));
			}
		}
		if (isset($_FILES['upload_csv'])) {

		
			$userCsvFileName=$_FILES['upload_csv']['name'];
			$this->session->set_userdata('userCsvFileName', $userCsvFileName);
			/**************  Script for importing csv file starts  ********************/
			if(is_uploaded_file($_FILES['upload_csv']['tmp_name'])) 
			{	
				$handle = fopen($_FILES['upload_csv']['tmp_name'], "r");
				$flag = true;
				while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{ 
					$role = mysql_real_escape_string($data[4]);
					if($role=='telecaller'){
						$level = '3';
					}elseif ($role=='counselor'){
						$level = '4';
					}
					$add = array(
						'userName' => mysql_real_escape_string($data[0]),
						'email' => mysql_real_escape_string($data[1]),
						'password' => md5(mysql_real_escape_string($data[2])),							
						'phone' => mysql_real_escape_string($data[3]),			
						'role' => $level,																		
					);
					$data['createdUserId'] = $this->usermodel->saveUserCsv($add, $orgId,$userId);
					//$data['uploadError'] = $this->usermodel->upload_profile_pic($data['createdUserId']);
					$loggedIn=$this->session->userdata('loggedIn');
					$data['bcc']=$loggedIn['userEmail'];
					$data['userEmail'] = $this->input->post('email');
					$data['userName'] = $this->input->post('name');
					$data['userPassword'] = $this->input->post('password');
					$data['siteName'] = $this->config->item('webSiteName','email');
					$this->user_email_send('createUser',$data);
				}
			}
			fclose($handle); 
			redirect('manageUsers','refresh');
		}
		
		if($data['loggedUser']['userLevel']==$this->config->item('MasterAdmin'))
		{
			$data['users'] = $this->usermodel->getUsers($filter);
		}
		else if($data['loggedUser']['userLevel']==$this->config->item('Admin'))
		{
			$data['users'] = $this->usermodel->getUsersFilterByOrg($orgId, $filter);
		}
		
		//$data['filterusers'] = $this->usermodel->getfilterUsers($orgId, $filter);
		$data['userPercentage'] = intval($this->usermodel->getUserPercentage($orgId));
		$data['createPermission'] = $this->usermodel->checkUserCreatePermission($orgId);  //gives package Details of Organization
		$data['countryCode'] = $this->usermodel->getCountryCode();
		$data['active'] = 'manageUsers';
		$this->load->view('layout/header',$data);
		$this->load->view('user/manageusers',$data);
	}


	function deleteUser()
	{
		echo $data['deleteUser'] = $this->usermodel->deleteUser();
		exit;
	}
	function banUser()
	 {
	  echo $data['banUser'] = $this->usermodel->banUser();
	  exit;
	 }


	 function checkPassword()
	{
		$loggedUser=$this->session->userdata('loggedIn');
		$orgId=$loggedUser['organizationId'];
		$id=$loggedUser['id'];
		$oldPassword=md5($_POST['oldPassword']);
		$dbPassword=$this->usermodel->checkIsPasswordSame($oldPassword,$orgId,$id);
		if(isset($dbPassword))
		{
			if($dbPassword==$oldPassword)
			{
				echo "correct";exit;
			}
			else
			{
				echo "error";exit;
			}
		}
	}
	
	function changePassword()
	{
		if(!$this->session->userdata('loggedIn'))
		{
			redirect('login','refresh');
		}
		$loggedUser=$this->session->userdata('loggedIn');
		$orgId=$loggedUser['organizationId'];
		$id=$loggedUser['id'];
		$newPassword=md5($_POST['newPassword']);
		$this->usermodel->upadateNewPassword($newPassword,$orgId,$id);
		// email
		$data['newPassword']=$_POST['newPassword'];
		$data['userEmail']=$loggedUser['userEmail'];
		$data['siteName'] = $this->config->item('webSiteName','email');
		$this->Changed_password_send_email('changePassword',$data['userEmail'],$data);
		// email
	}
	
	function Changed_password_send_email($type, $email, &$data)
	{
		//print_r($data);
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['mailtype'] = 'html';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		
		$this->email->initialize($config);
		$from = $this->config->item('webMasterEmail','email');
		$this->email->from($from);
		$this->email->reply_to('');
		$this->email->to($email);
		$this->email->subject(sprintf($this->config->item('changedPassword','email'),$this->config->item('webSiteName','email')));
		$this->email->message($this->load->view('email/'.$type.'_html',$data,true));
		//$this->email->set_alt_message($this->load->view('email/'.$type.'-txt',$data,true));
		$this->email->send();
	}
	
	function user_email_send($type,$data)//manage user email
	{
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['mailtype'] = 'html';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);
		$from = $this->config->item('webMasterEmail','email');
		$this->email->from($from);
		$this->email->reply_to('');
		$this->email->to($data['userEmail']);
		$this->email->bcc($data['bcc']);
		$this->email->subject(sprintf($this->config->item('userCreate','email'),$this->config->item('webSiteName','email')));
		$this->email->message($this->load->view('email/'.$type.'_html',$data,true));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt',$data,true));
		$this->email->send();

	}

	
}

?>
