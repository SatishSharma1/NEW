<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Model class
 *
 * Communicate with the category table in the database; the category, home and gmaps controllers (The middle guy)
 *
 * @package		ci_fullcalendar
 * @category    Models
 * @author		sirdre
 * @link		/category
 */ 
 
class Category_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('security');
		$this->load->database();		
	}
	 
 
    /**
    * add - the event category in the database
    *
    ****
    * @access public
    * @ Param $username, $category_name, $category_desc
    * @ Return string with the last query 
    */		
    function add($username, $category_name, $category_desc) {
	
		$xusername = $this->security->xss_clean($username);
		$data = array( 
			'username' => $xusername,
		    'category_name' => $category_name,
		    'category_desc' => $category_desc 
		);
		$this->db->insert('ic_category', $data);
    }
    /**
    * update - the event category in the database
    *
    ****
    * @access public
    * @ Param $id, $username, $category_name, $category_desc
    * @ Return string with the last query 
    */	
    function update($id, $username, $category_name, $category_desc ) {
	
		$xusername = $this->security->xss_clean($username);
		$data['username'] = $xusername;
		$data['category_name'] = $category_name;
		$data['category_desc'] = $category_desc; 
 
		$this->db->where('category_id', $id);
		$this->db->where('username', $xusername);
		$this->db->update('ic_category', $data);
    }	

    /**
    * profile_del - the event category in the database
    *
    ****
    * @access public
    * @ Param $id, $username
    * @ Return string with the last query 
    */		
	function profile_del($username) { 
	
		$xusername = $this->security->xss_clean($username); 
		$this->db->where('username', $xusername);
		$this->db->delete('ic_category');
    }	
    /**
    * delete - the event category in the database
    *
    ****
    * @access public
    * @ Param $id, $username
    * @ Return string with the last query 
    */		
	function delete($id, $username) { 
	
		$xusername = $this->security->xss_clean($username);
		$this->db->where('category_id', $id);
		$this->db->where('username', $xusername);
		$this->db->delete('ic_category');
    }
    /**
    * getCategoryList - the event category in the database
    *
    ****
    * @access public
    * @ Param $limit, $offset = 0, $username
    * @ Return string with the last query 
    */		
    function getCategoryList($limit, $offset = 0, $username) {
 
		$xusername = $this->security->xss_clean($username);
		if (!$offset) {
		    $offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
		    $this->db->limit($limit, $offset);
		}
		$this->db->order_by('category_name', 'ASC');
		$query = $this->db->where('username', $xusername);
		$query = $this->db->get('ic_category');
		 
		if ($query->num_rows() > 0) {
		    return $query->result();
		}
		// no result
		return FALSE;
    }
	
    /**
    * countCategories - the event category in the database
    *
    ****
    * @access public
    * @ Param $username
    * @ Return string with the last query 
    */	
    function countCategories($username) {  
		$xusername = $this->security->xss_clean($username);
		$query = $this->db->where('username', $xusername);
		$query = $this->db->count_all_results('ic_category');
		
		return $query; 
    }
    /**
    * getCategoriesById - the event category in the database
    *
    ****
    * @access public
    * @ Param $id
    * @ Return string with the last query / False
    */
    function getCategoriesById($id) { 
	
		$this->db->where('category_id', $id);
		$this->db->limit(1);
		$query = $this->db->get('ic_category');
		
		if ($query->num_rows() > 0) {
		    $result = $query->result();
		    return $result[0];
		} 
		return FALSE;
    }
	
	/**
    * Get all results of the events category
    * getAllCategories
    ****
    * @access public
    * @param int (limit), int (offset)  
    * @return true/false
    */
    public function getAllCategories($limit, $offset = 0) {
		// return all events
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		} 
		$query = $this->db->get('ic_category');
		// return the events
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no result
		return FALSE;
    }	
}