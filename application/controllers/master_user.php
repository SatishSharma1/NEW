<?php
/**
* This will be handled by master user only
*/
class Master_user extends CI_Controller {
	private $data;
	
	function __construct() {
		parent::__construct();
		$this->load->model('usermodel');
		//$this->load->model('calender/Member_model');
		//$this->load->library('layout');
		$this->load->config('email', TRUE);
		$this->load->helper('form');
		$this->data['loggedUser'] = $loggedUser = $this->session->userdata('loggedIn');
		if(!$loggedUser || $loggedUser['userLevel'] != $this->config->item('MasterAdmin') ) {
			redirect('login','refresh');

		
		}
	}
	
	function updateActiveStatus($id) {
		$this->usermodel->updateActivateStatus($id);
	}

	public function manage_organization($filter = '') {
		if(isset($_GET['query'])) {
			$filter = $_GET['query'];
		}
		if($_POST) {
			$this->form_validation->set_rules('userName', 'User Name', 'trim|required|xss_clean');
			if($this->input->post('code')){
			$this->form_validation->set_rules('mobile', 'mobile', 'trim|required|min_length[10]|max_length[10]|xss_clean');
			}
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.userEmail]|xss_clean');
			$this->form_validation->set_rules('registerPassword', 'Password', 'trim|required|xss_clean|matches[repeatPassword]');
			$this->form_validation->set_rules('repeatPassword', 'Confirm Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('orgName', 'Organization Name', 'trim|required|xss_clean');		
			
			if($this->form_validation->run()) {
				$userData = $this->usermodel->insertOrganizationDetails();
				$userData['activationPeriod'] = $this->config->item('emailActivationExpire', 'email') / 3600;
				$userData['siteName'] = $this->config->item('webSiteName');
				$this->usermodel->send_email('activate', $userData['userEmail'], $userData);
				
				//var_dump($userData);
				$level=2;
				$this->Member_model->registerUser($userData['userEmail'], $_POST['registerPassword'], $userData['userEmail'], $image, $level, $signupdate);  //Register for Calender

				
				$this->data['accountRegistered'] = 'Registered successfully check your mail to activate account!';
				//return TRUE;
			} else {
			
			}
		}
		
		$this->data['countryCode'] = $this->usermodel->getCountryCode();
		$this->data['orgList'] = $this->usermodel->organizationList($filter);
		$this->data['active'] = 'manageUsers';
		$this->layout->view('user/manageorganisation',$this->data);
	}


	
	public function Country($filter = '') {
			$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.validate.js'));
		$data['js']=put_headers_js();
	  
		if($_POST) {
			$this->form_validation->set_rules('countryname', 'country Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('countrycode', 'country Code', 'trim|required|xss_clean');
			if($this->form_validation->run()) {
				$countryData = $this->usermodel->addCountry();
			}
		}
		if(isset($_GET['query'])) {
			$filter = $_GET['query'];
		}
		$this->data['countryList']= $this->usermodel->getCountry($filter);
			//dp($this->data['countryList']);
		$this->load->view('layout/header',$data);
		//var_dump($data);
		$this->load->view('user/country',$this->data);
	}
	
	function add_city() {
		if($_POST) {
			$this->form_validation->set_rules('countryname', 'country Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('cityname', 'country Code', 'trim|required|xss_clean');
			if($this->form_validation->run()) {
				$cityData = $this->usermodel->addCity();
			}
		}
		redirect('master_user/city');
	}
	function city($filter=''){
			$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.validate.js'));
		$data['js']=put_headers_js();

		if(isset($_GET['query'])) {
			$filter = $_GET['query'];
		}
		$this->data['countryList']= $this->usermodel->getCountry();
		$this->data['cityList']= $this->usermodel->getCity($filter);
		$this->load->view('layout/header',$data);
		$this->load->view('user/city',$this->data);
	}
	

	public function manage_users($filter = '') {
		if(isset($_GET['query'])) {
			$filter = $_GET['query'];
		}
		$this->data['allUsers'] = $this->usermodel->getUsers($filter);
		$this->layout->view('master/manage_users',$this->data);
	}

}