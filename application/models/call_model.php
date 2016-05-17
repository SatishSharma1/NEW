<?php 
/**
* 
*/
class Call_model extends CI_Model
{
	
	function __construct() {
		parent::__construct();
		$this->load->model('api_setting_model', 'setting');
		$this->db->cache_on();
	}
	
	public function checkCallPlugin($orgId){
		$data = $this->setting->callSetting($orgId);
		if(!$data) {
			$plugin = 'noplugin';
		}else if($data['status'] != '1') {
			$plugin  = 'notactivated';
		}else{
			$plugin = 'activated';
		}
		return $plugin;
	}
	
	public function makeCall($orgId, $customer, $agent,$extra) {
		$data = $this->setting->callSetting($orgId);
		/*if(!$data) {
			return "You dont have valid plugin";
		}
		if($data['status'] != '1') {
			return "You dont have active status";
		}*/
		$data['agent'] = $agent;
		$data['caller'] = $customer;
		$data['extra'] = $extra;
		$url = $this->setting->parseSetting($data);
		if($url == false) {
			return "Invalid Plugin settings";
		}
		// return $url;
		return file_get_contents($url);

	}

	
}
