<?php 

/**
* 
*/
class Api_setting_model extends CI_Model {


	// Constructor function
	function __construct() {
		parent::__construct();

	}

	public function callSetting($orgId) {
		$query = $this->db->select('type, value')
					->get_where('api_settings', array(
						'organizationId' => $orgId,
						'pluginId' => 2
					));
		$result = array();
		foreach ($query->result() as $row) {
			$result[$row->type] = $row->value;
		}
		return $result;
	}

	public function smsSetting($orgId) {
		$query = $this->db->select('type, value')
					->get_where('api_settings', array(
						'organizationId' => $orgId,
						'pluginId' => 1
					));
		$result = array();
		foreach ($query->result() as $row) {
			$result[$row->type] = $row->value;
		}
		return $result;
	}
	
	public function calenderSetting($orgId) {
		$query = $this->db->select('type, value')
					->get_where('api_settings', array(
						'organizationId' => $orgId,
						'pluginId' => 3
					));
		$result = array();
		foreach ($query->result() as $row) {
			$result[$row->type] = $row->value;
		}
		return $result;
	}

	public function parseSetting($data) {
	//print_r($data);exit;
		if(!isset($data['url'])) {
			return false;
		}
		$url = $data['url'];
		preg_match_all("/\<(\w+)\>/", $url, $matches);
		$fieldArr = $matches[1];
		//var_dump($fieldArr);
		if(!$fieldArr) {
			return false;
		}
		foreach ($fieldArr as $field) {
			if(!isset($data[$field])) {
				return "Miss Configured! :(";
			}
			$url = str_replace("<$field>", $data[$field], $url);
		}
		return $url;
	}
}