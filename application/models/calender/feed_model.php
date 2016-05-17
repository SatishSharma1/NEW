<?php
/**
 * Feed_model class
 *
 * Communicate with the member table in the database and profile controller (The middle guy)
 *
 * @package		ci_fullcalendar
 * @category    Models
 * @author		sirdre
 * @link		/feed
 */ 
 
class Feed_model extends CI_Model {

    /*
     * Member model class constructor
     */

    function Feed_model() {
	parent::__construct();
	$this->load->helper('security');
	$this->load->database();
    }
 
 
	function get_allfeeds ($limit)	{
		$this->db->order_by('eid', 'desc');
		$this->db->where('auth', 0);
		$this->db->limit($limit);
		return $this->db->get('ic_events');
	} 

}

/* End of file feed_model.php */
/* Location: ./application/models/feed_model.php */