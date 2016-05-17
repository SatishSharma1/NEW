<?php
/**
 * Model class
 *
 * Communicate with the member table in the database and profile controller (The middle guy)
 *
 * @package		ci_fullcalendar
 * @category    Models
 * @author		sirdre
 * @link		/profile
 */ 
 
class Member_model extends CI_Model {

    /*
     * Member model class constructor
     */

    function Member_model() {
	parent::__construct();
	$this->load->helper('security');
	$this->load->database();
    }
	
    /**
    * captcha new user into captcha table of the database
    * registerUser
    ****
    * @access public
    * @param array (cinfo)  
    * @return none
    */

    function captchaUser($cinfo) {
 
		$cap_data = array(
			'captcha_time' => $cinfo['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cinfo['word']
		);
		// insert temporary captcha data
		$query = $this->db->insert_string('ic_captcha', $cap_data);
		$this->db->query($query);
		
		return $cinfo['image'];
	
    }
	
	/**
    * captcha verify new user into captcha table of the database
    * registerUser
    ****
    * @access public
    * @param array (cinfo)  
    * @return none
    */

    function captchaVerify($captcha, $expiration) {
 
		$this->db->query('DELETE FROM ic_captcha WHERE captcha_time < ' . $expiration);
		$sql = 'SELECT COUNT(*) AS count FROM ic_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
		
		$binds = array($captcha, $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();
		
		return $row->count > 0;
	
    }
	
    /**
    * Insert new user into member table of the database
    * registerUser
    ****
    * @access public
    * @param varchar (uname) 
    * @param md5 (password) 
    * @param varchar (email) 
    * @param varchar (image) 
    * @param int (level) 
    * @return none
    */

    function registerUser($uname, $password, $email, $image, $level, $signupdate) {
		// add the user
		$password = do_hash($password, 'md5');
		
		$data = array(
			'uname' => $uname,
			'password' => $password,
			'email' => $email, 
			'image' => $image,
			'level' => $level,
			'signupdate' => $signupdate
		);
		$this->db->insert('ic_members', $data);
    }
	
 
    /**
    * Update the user information 
    * updateUser
    ****
    * @access public
    * @param $id, $fname, $lname, $address, $phone, $email  
    * @return none
    */
    function updateUser($id, $fname, $lname, $address, $phone, $email) {
		// update the user
		
		$data['fname'] = $fname;
		$data['lname'] = $lname;
		$data['address'] = $address;
		$data['phone'] = $phone;
		$data['email'] = $email;
		
		$this->db->where('id', $id);
		$this->db->update('ic_members', $data);
    }
	
     /**
    * Update the user password
    * updateUser
    ****
    * @access public
    * @param $id, $password2  
    * @return none
    */
    function updatePassword($id, $password2) {
		// update the user password 
		$data['password'] = md5($password2);
		
		$this->db->where('id', $id);
		$this->db->update('ic_members', $data);
    } 
	
   /**
    * Update the user profile image from the member table of the database
    * updateImage
    ****
    * @access public
    * @param $userid, $image 
    * @return none
    */
    function updateImage($userid, $image) {
		// update the user image
		
		$data['image'] = $image;
		
		$this->db->where('id', $userid);
		$this->db->update('ic_members', $data);
	
    }
	
   /**
    * delete the user profile from the member table of the database
    * deleteUser
    ****
    * @access public
    * @param int (id)  
    * @return none
    */
    function profile_del($id) {  
		$xid = $this->security->xss_clean($id);
		$this->db->where('id', $xid);
		$this->db->delete('ic_members');
    }

    /**
    * Get all result and return count amount of all members from the member table of the database
    * countUsers
    ****
    * @access public
    * @param none 
    * @return count results
    */
    function countUsers() { 
		return $this->db->count_all_results('ic_members');
    }

	/**
    * Get result of the user profile by id from the member table of the database
    * getUserById
    ****
    * @access public
    * @param int (id)  
    * @return true/false
    */
    function getUserById($id) {
		// return the user
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('ic_members');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// no result
		return FALSE;
    }
	
	/**
    * Get result of the user profile by username from the member table of the database
    * getUserByUsername
    ****
    * @access public
    * @param varchar (uname)  
    * @return true/false
    */
    function getUserByUsername($uname, $id = 0) {
		// return the user
		$this->db->where('uname', $uname);
		// optionally ignore a particular user
		if ($id > 0) {
			$this->db->where('id !=', $id);
		}
		$this->db->limit(1);
		$query = $this->db->get('ic_members');
		// return the user
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0];
		}
		// no result
		return FALSE;
    }
 
 
	/**
    * Check against the database if image name exist in directory
    * userImageExists
    ****
    * @access public
    * @param varchar (image)  
    * @param int (id)  
    * @return true/false
    */
    function userImageExists($image, $id = 0) {
		// check if manager email address exists in users table... to prevent duplicates
		$this->db->where('image', $image);
		// ignore a user id... this is optional and is used when you want to ignore the current user when editing
		if ($id > 0) {
			$this->db->where('id !=', $id);
		}
		$this->db->limit(1);
		$query = $this->db->get('ic_members');
		// return number of users with this image
		if ($query->num_rows() > 0) {
			return $query->row()->id;
		}
		// no other users
		return FALSE;
    }
	 
	/**
    * Check against the database if the email address exist
    * userEmailExists
    ****
    * @access public
    * @param int (email)  
    * @param int (id)  
    * @return true/false
    */
    function userEmailExists($email, $id = 0) {
		// check if manager email address exists in users table... to prevent duplicates
		$this->db->where('email', $email);
		// ignore a user id... this is optional and is used when you want to ignore the current user when editing
		if ($id > 0) {
			$this->db->where('id !=', $id);
		}
		$this->db->limit(1);
		$query = $this->db->get('ic_members');
		// return number of users with this email address
		if ($query->num_rows() > 0) {
			return $query->row()->id;
		}
		// no other users
		return FALSE;
    }
	 
	 
	/**
    * Check against the database if the provided key exist and used to reset the user's password
    * resetPassword
    ****
    * @access public
    * @param alphanumeric (key)  
    * @return true/false
    */
    function resetPassword($key) {
		// check the provided key and reset the user's password
		$this->db->where('key', $key);
		$this->db->limit(1);
		$query = $this->db->get('ic_members');
		
		if ($query->num_rows() > 0) {
			// found the user
			$user_id = $query->row()->id;
			// create a new password
			$password = random_string('alnum', 12);
			$data['password'] = do_hash($password, 'md5');
			$data['key'] = '';
			$this->db->where('id', $user_id);
			// update the member
			$this->db->update('ic_members', $data);
			// return the new password
			return $password;
		}
		// key not found
		return FALSE;
    }

	/**
	* Update the temporary 64 character alphanumeric key in the user record
    * storeTemporaryKey
    ****
    * @access public
    * @param string (email)  
    * @return true/false
    */
    function storeTemporaryKey($email) {
		// put a temporary 64 character alphanumeric key in the user record
		$data['key'] = random_string('alnum', 64);
		$this->db->where('email', $email);
		// update the user
		$this->db->update('ic_members', $data);
		// return the key
		return $data['key'];
    }

	/**
    * Get result of the provided key to find the user's email address
    * getEmailFromKey
    ****
    * @access public
    * @param alphanumeric (key)     
    * @return true/false
    */
    function getEmailFromKey($key) {
		// use the provided key to find the user's email address
		$this->db->where('key', $key);
		$this->db->limit(1);
		$query = $this->db->get('ic_members');
		// return the email address
		if ($query->num_rows() > 0) {
			return $query->row()->email;
		}
		// no result
		return FALSE;
    } 

	/**
    * Count the number of users for a particular level, default is 1
    * login
    ****
    * @access public
    * @param int (level)    
    * @return true/false
    */
    function countUsersForLevel($level = 1) {
		// count the number of users for a particular level, default is 1
		$this->db->where('level >=', $level);
		// return number of users
		return $this->db->count_all_results('ic_members');
    }

}

/* End of file member_model.php */
/* Location: ./application/models/member_model.php */