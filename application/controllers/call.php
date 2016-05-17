<?php 
/**
* 
*/
class Call extends CI_Controller
{
	
	function __construct( ) {
		parent::__construct();
		$this->load->model('call_model', 'call');
		$this->userData = $this->session->userdata('loggedIn');
	}
	
	public function checkCallPlugin(){
		if($this->session->userData('callPluginActivated')){
			$res = $this->session->userData('callPluginActivated');
		}else{
			$orgId = $this->userData['organizationId'];
			$res = $this->call->checkCallPlugin($orgId);
			$this->session->userData('callPluginActivated', $res);
		}
		echo $res;
	}

	public function checkDND($customer){
		$xml =file_get_contents("http://int.kapps.in/webapi/enterprise/generalized_api/ndnc_check?caller=".$customer);
            $xml = strip_tags($xml);
         echo $xml=trim($xml);

	}
	
	public function makeCall($agent = '', $customer = '',$extra ='') {
		if(!$agent || !$customer) {
			echo "Agent Or Caller is Not Available";
			return "Agent Or Caller is Not Available";
		}
		$orgId = $this->userData['organizationId'];
		$res = $this->call->makeCall($orgId, $agent, $customer,$extra);
		echo $res;
	}
}