<?php 

class Sms extends CI_Controller {
	private $userData;
	function __construct() {
		parent::__construct();
		$this->load->model('smsmodel');
		//$this->load->library('layout');
		$this->load->library('pagination');
		$this->userData = $this->session->userdata('loggedIn');
		if(!$this->userData) {
			redirect('home');
		}

		/*if($this->config->item('Admin') != $this->userData['userLevel']) {
			redirect('home');
		}*/
		
	}

	public function checkSMSPlugin(){
		if($this->session->userData('smsPluginActivated')){
			$res = $this->session->userData('smsPluginActivated');
		}else{
			$orgId = $this->userData['organizationId'];
			$res = $this->smsmodel->checkSMSPlugin($orgId);
			$this->session->userData('smsPluginActivated', $res);
		}
		echo $res;
	}
	
	public function sendSMS($dest = '', $msg = '', $leadId=0){
         //echo $msg;
        $smsdetails=$this->smsmodel->getTemplateAndName($msg);
        //var_dump($smsdetails);
		$orgId = $this->userData['organizationId'];
		$res = $this->smsmodel->sendSMS($orgId, $dest, urlencode($smsdetails[0]->template),$smsdetails[0]->name);
		$this->smsmodel->saveSMSNote($leadId, urldecode($msg));
		if(!$res) {
			echo "SMS sent successfully";
		}else {
			echo $res;
		}
	}

	function template($limit='',$page='') {


			$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js','jquery.validate.js'));
		$data['js']=put_headers_js();

		$orgId = $this->userData['organizationId'];
		$this->form_validation->set_rules('name', 'Name','trim|required|is_unique[sms.name]|xss_clean');
		$this->form_validation->set_rules('template', 'Sms Template', 'trim|required|xss_clean');			
		if (isset($_POST['submit'])) {
		
			if($this->form_validation->run() == FALSE) {
			
			} else {
				//$limit = $this->smsmodel->checkOrgSmsLimit($data['loggedUser']['organizationId']);
				$this->smsmodel->saveSmsTemplate($orgId);
				$data['success'] = 'Saved Successfully';
			}
		}
		$data['loggedUser'] = $this->userData;

       // pagination code

         $limit = ($this->uri->segment(3))?$this->uri->segment(3):10;			//check if data limit is set or set it to 10
		 $data['total']=$this->smsmodel->_get_all($orgId);
		 $data['per_page'] = $limit;
		/**configration of pagination***/
		$config["base_url"] = base_url() . "sms/template/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);


		
		$data["links"] = $this->pagination->create_links();

       // end of pagination code

		$data['smsTemplates'] = $this->smsmodel->getAllSmsTemplates($orgId,$config['per_page'],$page);
		$data['usedSms'] = $this->smsmodel->getOrgUsedSms($orgId);
		$data['active'] = 'smsTemplate';
		$this->load->view('layout/header',$data);
		$this->load->view('sms/sms_template',$data);
	}

	function removeSms() {
		$smsId = $this->input->post('smsId');
		$this->db->set('removed','1');
		$this->db->where('id', $smsId);
		$this->db->update("sms");
	}

	function alltemplate() {
		$data['loggedUser'] = $this->userData;
		$data['smsTemplates'] = $this->smsmodel->getAllSmsTemplatesMasterAdmin();
		$data['orgSmsDetails'] =  $this->smsmodel->getOrgSmsDetails();
		$this->layout->view('sms/all_template', $data);
	}
	
	function discardTemplate() {
		$smsId = $this->input->post('smsId');
	//	$reason = $this->input->post('reason');
	//	$this->db->set('approved','2');
	//	$this->db->set('discardReason',$reason);
		$this->db->where('id',$smsId);
		$this->db->delete("sms");
	}

	function approveTemplate() {
		$smsId = $this->input->post('smsId');echo $smsId;
		$this->db->set('approved','1');
		$this->db->where('id',$smsId);
		$this->db->update("sms");
	}

	function createSMSTEmplate() {
		$data['loggedUser'] = $this->userData;
		$userId = $this->userData['id'];
		$orgId = $this->userData['organizationId'];
		$template = $this->smsmodel->saveApprovedSmsTemplate($orgId,$userId);
		if($template == 'sucess') {
			
		} else {
			echo "exist";exit;
		}
	}
}