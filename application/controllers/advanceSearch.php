<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdvanceSearch extends CI_Controller 
{

	function __construct() {
		parent::__construct();
		$this->load->model('advanceSearchmodel','',TRUE);
		//$this->load->model('campaignmodel','',TRUE);
		$this->load->model('leadmodel','',TRUE);
		$this->load->model('smsmodel','',TRUE);
		$this->load->library("pagination");
		//$this->load->library('layout');
		$this->load->helper(array('form', 'url'));
	}
	
	function index() {
		if($this->session->userdata('loggedIn')) {
			redirect('home','refresh');
		}
	}
	
	function allLeads($search='',$limit='',$page='') {

		$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();

		if(!$this->session->userdata('loggedIn')) {
			redirect('login','refresh');
		}
		$loggedUser=$this->session->userdata('loggedIn');
		$userId=$loggedUser['id'];
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$limit = ($this->uri->segment(3))?$this->uri->segment(3):10;
		$search = ($this->uri->segment(2))?$this->uri->segment(2):'key';
		$search=rawurldecode($search);
		if($search!='key') {
			$phone=$status=$source=$city='';
			$data['additionalParameter']=$search;
			$searchdata=explode(",",$search);
			for($i=0;$i<sizeof($searchdata);$i++) {
				$searchfield=explode(":",$searchdata[$i]);
				if($searchfield[0]=='ph') {
					$phone=$searchfield[1];
				}
				else if($searchfield[0]=='so') {
					$source=$searchfield[1];
				}
				else if($searchfield[0]=='st') {
					$status=$searchfield[1];
				}
				else if($searchfield[0]=='ci') {
					$city=$searchfield[1];
				}
			}
			$data['total']=$this->advanceSearchmodel->getAdvanceLeadsData($phone,$source,$status,$city);
		}
		else {
			$data['total'] = $this->advanceSearchmodel->getAllLeadsCount($search);
		}
		$data['per_page'] = $limit;

		/**configration of pagination***/
		$config["base_url"] = base_url() . "allLead/".$search."/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);
		/***end**/
				
		if(empty($data['loggedUser'])) {
			redirect('home' , 'login');
		}
		$activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		if($search!='key') {
			$data['getAllLeads'] = $this->advanceSearchmodel->getAdvanceLeads($config['per_page'],$page,$phone,$source,$status,$city);
		}
		else {
			$data['getAllLeads'] = $this->advanceSearchmodel->getAllLeads($config['per_page'],$page);
		}
		$data['tot_records'] = count($data['getAllLeads']);
		$data["links"] = $this->pagination->create_links();
		$data['pagename'] = 'AdvanceSearch';
		$data['active'] = 'AdvanceSearch';
		$data['countries']=$this->leadmodel->getCountry();
      
        $activeUserLevel= $data['loggedUser']['userLevel'];
		$orgId = $data['loggedUser']['organizationId'];
		$data['leadStatus'] = $this->leadmodel->currentStatusByUser($activeUserLevel,$orgId);

        $data['agentNumber'] = $data['loggedUser']['userPhone'];
		$this->load->view('layout/header',$data);
		$this->load->view('leads/advanceSearch/allLead',$data);
		//$this->load->view('agents/home',$data);
		
	}
	
	
	
}
?>
