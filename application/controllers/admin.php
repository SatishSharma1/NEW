<?php

class Admin extends CI_Controller {

	private $data;

	public function __construct() {
		parent::__construct();
		//$this->load->library('layout');
		$this->load->model('admin_model', 'admin');
		$this->data['loggedUser']=$this->session->userdata('loggedIn');
		$this->orgId = $this->data['loggedUser']['organizationId'];
		$this->usrLevel=$this->data['loggedUser']['userLevel'];
		
	//	if($this->admin->existListView($this->orgId) || $this->usrLevel == '1') {
	//		redirect('allLogs');
	//	}

		if(empty($this->data['loggedUser'])) {
			redirect('home' , 'login');
		}
	}
	
	public function select_field() {	

    $data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();


		$orgId = $this->orgId;
		$fieldList =  $this->admin->getListFields();


		if($_POST) {
			//var_dump($_POST);
		//die();
			$addStatus = $this->admin->addListView($fieldList, $orgId);
			if($addStatus) {
				$lists = $this->admin->getPostedFields($fieldList);
				$this->admin->createViewListTable($lists, $orgId);
				redirect();
			} /*else {
				$this->data['status'] = "Minimum and Maximum must be 7 and 8";
			}*/
		}
		
		$this->data['fieldList'] = $fieldList;
		//$this->layout->view('user/admin', $this->data);
		$this->load->view('layout/header',$data);
		$this->load->view('user/admin',$this->data);
	}

	




   public function select_update_field($orgId) {			
	//	$orgId = $this->orgId;

	//	$orgId = $this->orgId;
	
		$fieldList =  $this->admin->getListFields();
		if($_POST) {

			$this->admin->deleteFieldsPresentListView($orgId);
			$addStatus = $this->admin->addListView($fieldList, $orgId);
			if($addStatus) {
				$lists = $this->admin->getPostedFields($fieldList);
				$this->admin->createViewListTable($lists, $orgId);
				redirect();
			} /*else {
				$this->data['status'] = "Minimum and Maximum must be 7 and 8";
			}*/
		}
		
		$this->data['fieldList'] = $fieldList;
		$this->layout->view('user/admin', $this->data);
	}


/*
public function select_update_field1($orgId) {			
	//	$orgId = $this->orgId;

		$fieldList =  $this->admin->getListFields();
		if($_POST) {

		$updateStatus = $this->admin->updateListView($fieldList, $orgId);
		                $this->admin->AddNewListView($fieldList, $orgId);
			if($updateStatus) {
				$lists = $this->admin->getPostedFields($fieldList);
			$this->admin->createViewListTable($lists, $orgId);
				redirect();
			} /*else {
				$this->data['status'] = "Minimum and Maximum must be 7 and 8";
			}*/
//		}
		//die("bbb");
//		$this->data['fieldList'] = $fieldList;
//		$this->layout->view('user/admins', $this->data);
//	}


  public function delete_organization() {			
		$orgId = $this->input->post("orgId");

       //die($this->input->post("orgId"));
		$this->admin->deleteOrganization($orgId);
	}


}
