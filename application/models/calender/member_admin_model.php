<?php
/**
 * Member Admin Model class
 *
 * Communicate with the member table in the database and profile controller (The middle guy)
 *
 * @package		ci_fullcalendar
 * @category    Models
 * @author		sirdre
 * @link		/profile
 */ 
 
class Member_admin_model extends CI_Model {

    /*
     * Member model class constructor
     */

    function Member_admin_model() {
	parent::__construct();
	$this->load->helper('security');
	$this->load->database();
    }
 
	
 
    /**
    * Administrative update of the user information
    * updateUser
    ****
    * @access public
    * @param $id, $fname, $lname, $address, $phone, $email, $level, $status, $password 
    * @return none
    */
    function updateUser($id, $fname, $lname, $address, $phone, $email, $level, $status, $password) {
		
		// update the user 
		$data['fname'] = $fname;
		$data['lname'] = $lname;
		$data['address'] = $address;
		$data['phone'] = $phone;
		$data['email'] = $email;
		$data['level'] = $level;
		$data['status'] = $status;
		
		if(!empty($password)){
			$data['password'] = do_hash($password, 'md5');
		}
		
		$this->db->where('id', $id);
		$this->db->update('ic_members', $data);
    }
	
     /**
    * Insert new user into member table of the database
    * registerUser
    ****
    * @access public
    * @param $uname, $fname, $lname, $address, $phone, $email, $level, $status, $signupdate, $password, $image
    * @return none
    */

    function addUser($uname, $fname, $lname, $address, $phone, $email, $level, $status, $signupdate, $password, $image ){
		// add the user
		$xpassword = do_hash($password, 'md5');
		
		$data = array(
			'uname' => $uname,
			'fname' => $fname,
			'lname' => $lname,
			'address' => $address, 
			'phone' => $phone,
			'email' => $email,
			'level' => $level,
			'status' => $status,
			'signupdate' => $signupdate,
			'password' => $xpassword,
			'image' => $image
		);
		$this->db->insert('ic_members', $data);
    }
   /**
    * delete the user profile from the member table of the database
    * deleteUser
    ****
    * @access public
    * @param int (id)  
    * @return none
    */
    function delUser($id, $username) {  
		$xid = $this->security->xss_clean($id);
		$this->db->where('id', $xid);
		$this->db->where('uname', $username);
		$this->db->delete('ic_members');
    }
  
  	/**
    * Get result of the user profile by amount from the member table of the database
    * getAllUsers
    ****
    * @access public
    * @param int (limit)  
    * @param int (offset)  
    * @return true/false
    */
    function getAllUsers($limit, $offset = 0) {
		// return all users
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('ic_members');
		// return the users
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no result
		return FALSE;
    }

}

/* End of file user_model.php */
/* Location: ./application/models/member_model.php */