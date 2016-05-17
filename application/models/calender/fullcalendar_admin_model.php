<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Model class
 *
 * Communicate with the event table in the database; the home and profile controllers (The middle guy)
 *
 * @package		ci_fullcalendar
 * @category    Models
 * @author		sirdre
 * @link		/home
 */ 
 
class Fullcalendar_admin_model extends CI_Model
{
   function __construct() {
		parent::__construct();
		$this->load->model('gmaps_model');	
		
		$this->load->helper('security');
		$this->load->database();
    }
 
	
    /**
    * jsonEvents - Reads the events database
    * Delivery and format json
    ****
    * @access private
    * @param none
    * @return json events
    */
   
	public function jsonEvents($username)    {       
	
	   $xusername = $this->security->xss_clean($username);
       $events = $this->db->select('*')->from('ic_events')->order_by('start', 'desc')->get();
	   
        $jsonevents = array();
        foreach ($events->result() as $entry)
        {
            $jsonevents[] = array(
				'id'     			=> $entry->id,
                'title'     		=> $entry->title,
                'username'     		=> $entry->username,
                'category'     		=> $entry->category,
                'backgroundColor'	=> $entry->backgroundColor,
                'borderColor'		=> $entry->borderColor,
                'textColor'			=> $entry->textColor,
                'description'   	=> $entry->description,
                'start'     		=> $entry->start,
                'allDay'    		=> ($entry->allDay=='true') ? true : false,
                'end'       		=> $entry->end,
                'url'       		=> $entry->url,
				'auth'       		=> $entry->auth,
				'rendering'       	=> $entry->rendering,
				'location'       	=> $entry->location, 
				'latitude' 			=> $entry->latitude, 
				'longitude'			=> $entry->longitude, 
				'filename'			=> $entry->filename, 
				
            );
        }
       echo json_encode($jsonevents);
    }
 
 
     /**
    * jsonEventsCategory - the event makers in the database
    *
    ****
    * @access public
    * @ Param events
    * @ Return string with the last query 
    */		
	function jsonEventsCategory($category, $auth)
	{
		$events = $this->db->select('*')->from('ic_events')->where('category', $category)->order_by('start', 'desc')->get();
  
        $jsonevents = array();
        foreach ($events->result() as $entry)
        {
            $jsonevents[] = array(
				'id'     			=> $entry->id,
                'title'     		=> $entry->title,
                'category'     		=> $entry->category,
                'backgroundColor'	=> $entry->backgroundColor,
                'borderColor'		=> $entry->borderColor,
                'textColor'			=> $entry->textColor,
                'description'   	=> $entry->description,
                'start'     		=> $entry->start,
                'allDay'    		=> ($entry->allDay=='true') ? true : false,
                'end'       		=> $entry->end,
                'url'       		=> $entry->url,
				'auth'       		=> $entry->auth, 
				'location'       	=> $entry->location, 
				'latitude' 			=> $entry->latitude, 
				'longitude'			=> $entry->longitude, 
				
            );
        }
       echo json_encode($jsonevents);
	}

    /**
    * profile_del - Delete members events from the database
    *
    ****
    * @access public
    * @ Param $username (event)
    * @ Return string with the last query
    */
    public function profile_del($username)  {
		
		$xusername = $this->security->xss_clean($username); 
		$this->db->where('username', $xusername);
        $this->db->delete('ic_events');
		return $this->db->last_query();
    }		
    /**
    * delete_event - Delete the event from the database
    *
    ****
    * @access public
    * @ Param $ id (event)
    * @ Return string with the last query
    */
    public function delete_event($id)  {
		
		$xid = $this->security->xss_clean($id);
        $this->db->delete('ic_events',array('id'=>$xid));
        $this->db->delete('ic_markers',array('event_id'=>$xid));
		return $this->db->last_query();
    }	
  
    /**
    * update_event - Update the event in from the database
    *
    ****
    * @access public
    * @ Param $event, $title, $backgroundColor, $borderColor, $textColor, $description, $start, $end, $url, $allDay, $auth, $location, $markers_lat, $markers_lng
    * @ Return string with the last query (this should be overridden in production)
    */		
	public function update_event($event, $title, $backgroundColor, $borderColor, $textColor, $description, $start, $end, $url, $allDay, $auth, $location, $markers_lat, $markers_lng) {
		 
		 $xtitle = $this->security->xss_clean($title);
           $new_event = array(
			    'title' 			=> $xtitle, 
			    'backgroundColor' 	=> $backgroundColor,
			    'borderColor' 		=> $borderColor,
			    'textColor' 		=> $textColor,
				'description'		=> $description,
			    'start' 			=> $start,  
		        'end' 				=> $end,
		        'url' 				=> $url,				
			    'allDay' 			=> $allDay,
				'auth' 				=> $auth,				
				'location' 			=> $location,
				'latitude' 			=> $markers_lat, 
				'longitude' 		=> $markers_lng 
				
			); 
					
			$this->db->where('id',$event);
			$this->db->update('ic_events',$new_event);
			return $this->db->last_query();

	}    

    /**
    * update_eventForMarkers - Update the event in from the database
    *
    ****
    * @access public
    * @ Param $event, $location, $markers_lat, $markers_lng
    * @ Return string with the last query (this should be overridden in production)
    */		
	public function update_eventForMarkers($event, $location, $markers_lat, $markers_lng) {
		  
           $new_event = array( 			
				'location' 			=> $location,
				'latitude' 			=> $markers_lat, 
				'longitude' 		=> $markers_lng  
			); 
			 
			$this->db->where('id',$event);
			$this->db->update('ic_events',$new_event);
			return $this->db->last_query();
	}    	
	
     /**
    * get_eventById - get the event by id from the database
    *
    ****
    * @access public
    * @ Param id
    * @ Return results/false
    */		
	public function get_eventById($id) {
		// return the user
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get('ic_events');
		if ($query->num_rows() > 0) {
		    $result = $query->result();
		    return $result[0];
		}
		// no result
		return FALSE;
    }
	
	/**
    * getAllEvents - Get all results of the events
    * 
    ****
    * @access public
    * @param int (limit), int (offset)  
    * @return true/false
    */
    public function getAllEvents($limit, $offset = 0) {
		// return all events
		// offset is used in pagination
		if (!$offset) {
			$offset = 0;
		}
		// if a limit more than zero is provided, limit the results
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('ic_events');
		// return the events
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		// no result
		return FALSE;
    }
  
     /**
    * get_eventname - get the event by name
    *
    ****
    * @access public
    * @ Param $eventname
    * @ Return id/false
    */		
	public function get_eventname($eventname) { 
	
		$xeventname = $this->security->xss_clean($eventname);
		
		$this->db->where('title', $xeventname);
		$query = $this->db->get('ic_events');
		// return the category
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$eventid = $result->id;
			return $eventid;
		}
		// no result
		return FALSE;
    }
	
 
     /**
    * search_admin - Search private event by name
    *
    ****
    * @access public
    * @ Param $title, $username
    * @ Return json
    */		
	public function search_admin($title, $username) {
	
		 $xtitle = $this->security->xss_clean($title);
		
		 $events = $this->db->select('*')->from('ic_events')->like('title', $xtitle)->or_like('category', $xtitle)->or_like('location', $xtitle)->order_by('start', 'asc')->get();
  
        $jsonevents = array();
        foreach ($events->result() as $entry)
        {
            $jsonevents[] = array(
				'id'     			=> $entry->id,
                'title'     		=> $entry->title,
                'category'     		=> $entry->category,
                'backgroundColor'	=> $entry->backgroundColor,
                'borderColor'		=> $entry->borderColor,
                'textColor'			=> $entry->textColor,
                'description'   	=> $entry->description,
                'start'     		=> $entry->start,
                'allDay'    		=> ($entry->allDay=='true') ? true : false,
                'end'       		=> $entry->end,
                'url'       		=> $entry->url,
				'auth'       		=> $entry->auth, 
				'location'       	=> $entry->location,  
				
            );
        }
       echo json_encode($jsonevents);	 

    }	
	
     /**
    * countCalendarEvents - Count all events
	*
    ****
    * @access public
    * @ Param none
    * @ Return query
    */
    public function countCalendarEvents() {  
		return $this->db->count_all_results('ic_events');
    }	
	
}
 
/* End of file fulcalendar.php */
/* Location: ./application/models/fulcalendar.php */