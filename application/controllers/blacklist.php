<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blacklist extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->ci =& get_instance();
		date_default_timezone_set('Asia/Calcutta');
		$this->load->model('blacklistmodel');
		$this->load->library(array('pagination'));
		$this->data = array(
			);
	}

	public function index($limit='',$page='')
	{
		$data = $this->data;
		$data['css']=add_css();
		$data['css']=put_headers_css();
		$data['js']=add_js(array('plugins/metisMenu/jquery.metisMenu.js','plugins/slimscroll/jquery.slimscroll.min.js','plugins/iCheck/icheck.min.js'));
		$data['js']=put_headers_js();

        if($_POST){
        	if(!empty($this->input->post('updateid'))){
               $this->blacklistmodel->UpdateBlacklist();
        	}else{
        	 $this->blacklistmodel->SaveBlacklist();	
        }

}
         $limit = ($this->uri->segment(3))?$this->uri->segment(3):10;			//check if data limit is set or set it to 10
		 $data['total']=$this->blacklistmodel->_get_all();
		$data['per_page'] = $limit;
		/**configration of pagination***/
		$config["base_url"] = base_url() . "blacklist/index/".$limit."/";
		$config['total_rows'] = $data['total'];
		$config["per_page"] = $limit;
		$this->pagination->initialize($config);

		$blacklist = $this->blacklistmodel->ShowBlacklist($config['per_page'],$page);
		$data["links"] = $this->pagination->create_links();

 		$data['agentMap']=$blacklist;

		$this->load->view('layout/header',$data);
		$this->load->view('blacklist/home',$data);
	}

	function deleteBlacklist(){
	$this->blacklistmodel->deleteBlacklist();
}

function blacklistCaller(){
   $message = $this->blacklistmodel->blacklistCaller();
   echo $message;
}

  function checkblacklist()
{
        $caller =  $this->input->get('callernumber');
          $called =  $this->input->get('callednumber');
            $OrgIDKey =  $this->input->get('key');

             $orgId= $this->blacklistmodel->get_orgId_by_Key($OrgIDKey);
      // die();
           // $blacklist = array('called_number'=>'ljasdl','caller_number'=>'sssss');
      //   $this->db->insert('blacklist',$blacklist);
      $callerCheck= $this->blacklistmodel->check_caller($caller,$orgId);
        $calledCheck= $this->blacklistmodel->check_called($called,$orgId);

        if($callerCheck==1 && $calledCheck==1){
            $this->error("yes", 404);

       }elseif($callerCheck==1 && $called=="None"){
       	      $this->error("yes", 404);
       }elseif($callerCheck==1 && $called==""){
       	      $this->error("yes", 404);
       }elseif($callerCheck==1 && $calledCheck==0){
              $this->error("no", 404);
       }else{
       	    $this->error("no", 404);
       }
 
          
}


function error($msg, $code = 0) {
header('Content-Type: text/xml');
echo $string= "<response><is_blacklist>$msg</is_blacklist></response>";
}


function blacklistinsert(){

        $caller =  $this->input->get('callernumber');
       // echo $caller;
        //exit();
        $called =  $this->input->get('callednumber');
        $OrgIDKey =  $this->input->get('key');
       
       $orgId= $this->blacklistmodel->get_orgId_by_Key($OrgIDKey);


      $insertdata = $this ->blacklistmodel->insert_data_blacklist($caller,$called,$orgId);

      if(!empty($insertdata))
          {
          
           $this->error("successfully inserted", 404);

          }
        else{
         $this->error("Failed to insert data", 404);

           }
}

function blacklistdelete(){

        $caller =  $this->input->get('callernumber');
        $called =  $this->input->get('callednumber');
        $OrgIDKey =  $this->input->get('key');
        $orgId= $this->blacklistmodel->get_orgId_by_Key($OrgIDKey);
        $deleteblaclistdata = $this ->blacklistmodel-> delete_black_data($caller,$called,$orgId);  
     // $callerCheck = $this->blacklistmodel->check_caller($caller,$orgId);//check for responses only
      //$calledCheck = $this->blacklistmodel->check_called($called,$orgId);//check for responses only

     
      if(!empty($deleteblaclistdata))
               {
               
                $this->error("successfully deleted", 404);
     
               }
             else{
              $this->error("Failed to Delete data", 404);
     
                }    
         
     


}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */