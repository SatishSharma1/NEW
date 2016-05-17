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
 
class Fullcalendar_model extends CI_Model
{
   function __construct() {
		parent::__construct();
		$this->load->model('calender/gmaps_model');	
		
		$this->load->helper('string');
		$this->load->helper('security');
		$this->load->database();
		
    }
    /**
    * Reads the events database
    * Delivery and format json
    ****
    * @access public
    * @param none
    * @return json events
    */
   
    public function jsonPublicEvents()    {       
       
       $events = $this->db->select('*')->from('ic_events')->where('auth', 0)->order_by('start', 'desc')->get();
	   
        $jsonpublicevents = array();
        foreach ($events->result() as $entry)
        {
            $jsonpublicevents[] = array(
                'title'     		=> $entry->title,
				'backgroundColor'	=> $entry->backgroundColor,
                'borderColor'		=> $entry->borderColor,
                'textColor'			=> $entry->textColor,
                'description'   	=> $entry->description,
                'start'     		=> $entry->start,
                'allDay'    		=> ($entry->allDay=='true') ? true : false,
                'end'       		=> $entry->end,
                'url'       		=> $entry->url, 
				'rendering'       	=> $entry->rendering,
				'location'       	=> $entry->location, 
				'latitude' 			=> $entry->latitude, 
				'longitude'			=> $entry->longitude, 
				'filename'			=> $entry->filename, 
            );
        }
       echo json_encode($jsonpublicevents);
    }
    /**
    * Reads the events database
    * Delivery and format json
    ****
    * @access public
    * @param none
    * @return json events
    */
   
    public function jsonUserPublicEvents($username)    {       
       
		$xusername = $this->security->xss_clean($username);
       $events = $this->db->select('*')->from('ic_events')->where('organizationId', $orgId)->where('auth', 0)->order_by('start', 'desc')->get();
	   
        $jsonpublicevents = array();
        foreach ($events->result() as $entry)
        {
            $jsonpublicevents[] = array(
                'title'     		=> $entry->title,
				'backgroundColor'	=> $entry->backgroundColor,
                'borderColor'		=> $entry->borderColor,
                'textColor'			=> $entry->textColor,
                'description'   	=> $entry->description,
                'start'     		=> $entry->start,
                'allDay'    		=> ($entry->allDay=='true') ? true : false,
                'end'       		=> $entry->end,
                'url'       		=> $entry->url, 
				'rendering'       	=> $entry->rendering,
				'location'       	=> $entry->location, 
				'latitude' 			=> $entry->latitude, 
				'longitude'			=> $entry->longitude, 
				'filename'			=> $entry->filename, 
            );
        }
       echo json_encode($jsonpublicevents);
    }	
    /**
    * Reads the events database
    * Delivery and format json
    ****
    * @access private
    * @param none
    * @return json events
    */
   
	public function jsonEvents($username)    {       
		
		$data['loggedUser']=$this->session->userdata('loggedIn');
		$orgId = $data['loggedUser']['organizationId'];
		
		$xusername = $this->security->xss_clean($username);
       $events = $this->db->select('*')->from('ic_events')->where('organizationId', $orgId)->order_by('start', 'desc')->get();
	   
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
				'recurdays'       	=> $entry->recurdays,  
				'rendering'       	=> $entry->rendering,  
				'recurend'       	=> $entry->recurend,  
				'location'       	=> $entry->location, 
				'latitude' 			=> $entry->latitude, 
				'longitude'			=> $entry->longitude, 
				'filename'			=> $entry->filename, 
				
            );
        }
       echo json_encode($jsonevents);
    }
 
 
     /**
    * category - the event makers in the database
    *
    ****
    * @access public
    * @ Param events
    * @ Return string with the last query 
    */		
	function jsonEventsCategory($category, $username)
	{
		$events = $this->db->select('*')->from('ic_events')->where('category', $category)->where('username', $username)->order_by('start', 'desc')->get();
  
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
				'filename'			=> $entry->filename, 
				
            );
        }
       echo json_encode($jsonevents);
	}
	
    /**
    * Change the date of an event in the database
    *
    ****
    * @access public
    * @ Param $ data
    * @ Return string with the last query (this should be overridden in production)
    */
    public function drop_event ($data)    {
	
        extract($data);
        $new_event = array(
            'start' =>  $daystart,
            'end'   =>  $dayend,
            'allDay' =>  $allDay,
        );
       
        $this->db->where('id',$event);
        $this->db->update('ic_events',$new_event);
        return $this->db->last_query();
    }
 
    /**
    * Changes the dates of an event in the database
    *
    ****
    * @access public
    * @ Param $ data
    * @ Return string with the last query (this should be overridden in production)
    */
    public function resize($data)    {
	
        extract($data);
        $new_event = array(
            'start' =>  $daystart,
            'end'   =>  $dayend,
			'allDay' =>  $allDay,
        );
       
        $this->db->where('id',$event);
        $this->db->update('ic_events',$new_event);
        return $this->db->last_query();
    }

    /**
    * profile_del the event in the database
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
    * Clears the event in the database
    *
    ****
    * @access public
    * @ Param $ id (event)
    * @ Return string with the last query
    */
    public function delete_event($id)  {
		
		$xid = $this->security->xss_clean($id);
        $this->db->delete('ic_events',array('id'=>$xid));
		return $this->db->last_query();
    }	
    /**
    * add the event in the database
    *
    ****
    * @access public
    * @ Param $title, $marker_category, $backgroundColor, $borderColor, $textColor, $description, $start, $end, $url, $allDay, $auth, $location, $markers_lat, $markers_lng, $username
    * @ Return string with the last query (this should be overridden in production)
    */		
	public function add_event($id, $title, $marker_category, $backgroundColor, $borderColor, $textColor, $description, $start, $end, $url, $allDay, $rendering, $recurring, $endrecurring, $auth, $location, $markers_lat, $markers_lng, $username) {
		
			$xusername = $this->security->xss_clean($username);
			$xtitle = $this->security->xss_clean($title); 
		  	$data['loggedUser']=$this->session->userdata('loggedIn');
			$orgId = $data['loggedUser']['organizationId'];
			   $new_event = array(
					'id'	=> $id,
					"title" => $xtitle,
					'category' => $marker_category,
					'backgroundColor' => $backgroundColor,
					'borderColor' => $borderColor,
					'textColor' => $textColor,
					'description' => $description,
					'start' => $start,  
					'end' => $end,
					'url' => $url,				
					'allDay' => $allDay,			    
					'auth' => $auth,
					'rendering' => $rendering,
					'recurdays' => $recurring, 
					'recurend' => $endrecurring,
					'location' => $location, 
					'latitude' => $markers_lat, 
					'longitude' => $markers_lng, 
					'username' => $xusername,
					'organizationId' => $orgId
					
				); 				
			
				$this->db->insert('ic_events', $new_event);
				return $this->db->last_query();		
		
	}
 
     /**
    * update the event in the database
    *
    ****
    * @access public
    * @ Param $event, $title, $backgroundColor, $borderColor, $textColor, $description, $start, $end, $url, $allDay, $auth, $location, $markers_lat, $markers_lng, $username, $del
    * @ Return string with the last query (this should be overridden in production)
    */		
	public function update_event($event, $title, $marker_category, $backgroundColor, $borderColor, $textColor, $description, $start, $end, $url, $allDay, $auth, $rendering, $location, $markers_lat, $markers_lng, $username, $del) {
		 
		 $xusername = $this->security->xss_clean($username);
		 $xtitle = $this->security->xss_clean($title);
           $new_event = array(
			    'title' 			=> $xtitle,
				'category'			=> $marker_category,
			    'backgroundColor' 	=> $backgroundColor,
			    'borderColor' 		=> $borderColor,
			    'textColor' 		=> $textColor,
				'description'		=> $description,
			    'start' 			=> $start,  
		        'end' 				=> $end,
		        'url' 				=> $url,				
			    'allDay' 			=> $allDay,
				'auth' 				=> $auth,				
				'rendering' 		=> $rendering,				
				'location' 			=> $location,
				'latitude' 			=> $markers_lat, 
				'longitude' 		=> $markers_lng, 
				'username' 			=> $xusername
			); 
			
		if ($del == 1){
		
			$file = $this->get_eventById($event);
			if($file->filename != ''){
				unlink('./assets/attachments/' . $file->filename);  
			}
			
			$this->delete_event($event);
			$this->gmaps_model->delete_marker($event);
			
		   
		}else if ($del == 0){	
		
			$this->db->where('id',$event);
			$this->db->update('ic_events',$new_event);
			return $this->db->last_query();
			
		}

	}
 
	/**
    * Update the user attachments
    * updateImage
    ****
    * @access public
    * @param $eid, $image 
    * @return none
    */
    function attachment($id, $filename) { 
		 
	    $attach = array(
			'filename' 	=> $filename
		); 
			
		$this->db->where('id', $id);
		$this->db->update('ic_events', $attach);
		return $this->db->last_query();
    }
	
     /**
    * get the event by id the database
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
    * import event by user
    *
    ****
    * @access public
    * @ Param $eventname
    * @ Return id
    */		
	public function import($username, $ical_data) {
	
		$xusername = $this->security->xss_clean($username); 
		$location = ""; 
		
		$sqlstr = "INSERT into ic_events(id,username,start,end,title,location,description,recurdays,auth,category,backgroundColor,borderColor,textColor,latitude,longitude) VALUES  ";
		if (!empty($ical_data['VEVENT']) || !empty($ical_data['UID'])) {
			foreach ($ical_data['VEVENT'] as $key => $data) {
			
				if (!empty($data['UID'])) {
					$id = explode('@', $data['UID']);
						$insert_id = (int)$this->security->xss_clean($id[0]);
					 
				}else{$insert_id = random_string('numeric', 4);}
				
				//get StartDate And StartTime
				$start_dttimearr = $this->security->xss_clean($data['DTSTART']); 
				$insert_start = date("Y-m-d h:m:s", strtotime($start_dttimearr)) .""; 
				
				//get EndDate And EndTime
				$end_dttimearr = $this->security->xss_clean($data['DTEND']); 
				$insert_end = date("Y-m-d h:m:s", strtotime($end_dttimearr)) .""; 
				
				if (!empty($data['RRULE'])) {
					 $rrule = explode('=', $data['RRULE']);
					 $recur = $this->security->xss_clean($rrule[1]); 
					 
					 if($recur == "DAILY") {
						 $recurdays = 1;
					 }else if($recur == "WEEKLY") {
						 $recurdays = 7;
					 }else if($recur == "MONTHLY") {
						 $recurdays = 30;
					 }else if($recur == "YEARLY") {
						 $recurdays = 30;
					 }
					 
				}else{$recurdays = 0;}				
				
				if (!empty($data['DESCRIPTION'])) {
					$description = $this->security->xss_clean($data['DESCRIPTION']);
				}else{$description = "";}	
				
				if (!empty($data['SUMMARY'])) {
					$summary = $this->security->xss_clean($data['SUMMARY']);
				}else{$summary = "";}	
								
				if (!empty($data['LOCATION'])) {
					$location = $this->security->xss_clean($data['LOCATION']);
				}else{$location = "";}		
					
				if (!empty($data['GEO'])) {
					$geo = explode(';', $data['GEO']);
					$latitude = $this->security->xss_clean($geo[0]);
					$longitude = $this->security->xss_clean($geo[1]);
				}else{$latitude = 0;$longitude = 0;}	

				if (!empty($data['CATEGORIES'])) {
					$category = $this->security->xss_clean($data['CATEGORIES']);
				}else{$category = "";}	
				
				if (!empty($data['BACKGROUNDCOLOR'])) {
					$bgcolor = $this->security->xss_clean($data['BACKGROUNDCOLOR']);
				}else{$bgcolor = "";}		
				
				if (!empty($data['BORDERCOLOR'])) {
					$bordercolor = $this->security->xss_clean($data['BORDERCOLOR']);
				}else{$bordercolor = "";}	
				
				if (!empty($data['TEXTCOLOR'])) {
					$textcolor = $this->security->xss_clean($data['TEXTCOLOR']);
				}else{$textcolor = "";}	
				
				if (!empty($data['CLASS'])) {
					if($data['CLASS'] == "PUBLIC") {$auth = 0;}else{$auth = 1;}
				}	
				
				$sqlstr.="('" . $insert_id . "','" . $xusername . "','" . $insert_start . "','" . $insert_end . "','" . $summary . "','" . $location . "','" . $description . "','" . $recurdays . "','" . $auth . "','" . $category . "','" . $bgcolor . "','" . $bordercolor . "','" . $textcolor . "','" . $latitude . "','" . $longitude . "')";
				$sqlstr.=",";
			}
			
			$sqlstr = rtrim($sqlstr, ','); 
			$this->db->query($sqlstr); 
			return $this->db->last_query();  
		}
			
    }	
     /**
    * export public event by name
    *
    ****
    * @access public
    * @ Param $eventname
    * @ Return id
    */		
	public function export($username, $timezone) { 
		 
		$xusername = $this->security->xss_clean($username); 
		$transp = "";
		$ics_data = "";
		
		$ics_data .= "BEGIN:VCALENDAR\n";
		$ics_data .= "PPRODID:-//SIRDRE//CIFULLCALENDAR 1.5//EN\n";
		$ics_data .= "VERSION:2.0\n";		
		$ics_data .= "CALSCALE:GREGORIAN\n";		
		$ics_data .= "METHOD:PUBLISH\n";
		$ics_data .= "X-WR-CALNAME:cifullcalendar_". $xusername ."\n";

		# Change the timezone if needed
		$ics_data .= "X-WR-TIMEZONE:". $timezone ."\n";
		
		// $events = $this->db->select('*')->from('ic_events')->where('username', $xusername)->like('start', '2014-11-02')->order_by('start', 'ASC')->get();
		 $events = $this->db->select('*')->from('ic_events')->where('username', $xusername)->order_by('start', 'ASC')->get();
   
			foreach ($events->result() as $entry) {
				$id 				= $entry->id;
				$start_date 		= $entry->start;
				$start_time 		= $entry->start;
				$end_date 			= $entry->end;
				$end_time 			= $entry->end;
				$name 				= $entry->title;
				$location 			= $entry->location;
				$description 		= $entry->description;
				$backgroundColor 	= $entry->backgroundColor;
				$borderColor 		= $entry->borderColor;
				$textColor 			= $entry->textColor;
				$rendering 			= $entry->rendering;
				$recurdays 			= $entry->recurdays;
				$recurend 			= $entry->recurend;
				$auth 				= $entry->auth;
				$category 			= $entry->category;
				$latitude 			= $entry->latitude;
				$longitude 			= $entry->longitude;
				 
				# Replace HTML tags
				
				$search = array("/<br>/","/&amp;/","/&rarr;/","/&larr;/","/,/","/;/");
				$replace = array("\\n","&","-->","<--","\\,","\\;");    
				
				$title = preg_replace($search, $replace, $name);
				$location = preg_replace($search, $replace, $location);
				$description = preg_replace($search, $replace, $description);
				
				if($recurdays==0){
					$rec = "";
				}else if($recurdays==1){
					$rec = "RRULE:FREQ=DAILY\n";
				}else if($recurdays==7){
					$rec = "RRULE:FREQ=WEEKLY\n";
				}else if($recurdays==30){
					$rec = "RRULE:FREQ=MONTHLY\n";
				}else if($recurdays==365){
					$rec = "RRULE:FREQ=YEARLY\n";
				} 
				
				if($rendering == "background") {$transp = "TRANSPARENT"; }else {$transp = "OPAQUE";} 
				if($auth == 0) {$class = "PUBLIC"; }else {$class = "PRIVATE"; } 

				# Change TimeZone if needed
				$ics_data .= "BEGIN:VEVENT\n";
				$ics_data .= "DTSTART:" . date("Ymd h:m:s", strtotime($start_date)) . "\n";
				$ics_data .= "DTEND:" . date("Ymd h:m:s", strtotime($end_date)) . "\n";
				$ics_data .= "DTSTAMP:" . date('Ymd') . "T" . date('His') . "Z\n";
				$ics_data .= "UID:" . $id . "@". $xusername ."\n";
				$ics_data .= "CREATED:" . date('Ymd', strtotime($start_date)) . "T" . date('His', strtotime($start_time)) . "Z\n";
				$ics_data .= "DESCRIPTION:" . $description . "\n";
				$ics_data .= "LAST-MODIFIED:" . date("Ymd", strtotime($start_date)) . "T" . date('His', strtotime($start_time)) . "Z\n";
				$ics_data .= "SEQUENCE:0\n";				
				$ics_data .= "STATUS:CONFIRM\n";
				$ics_data .= "SUMMARY:". $title ."\n";	
				$ics_data .= "TRANSP:" . $transp . "\n";				
				$ics_data .= "LOCATION:" . $location . "\n";
				$ics_data .= "GEO:" . $latitude . ";" . $longitude . "\n";
				$ics_data .= "CATEGORIES:" . $category . "\n";
				$ics_data .= "BACKGROUNDCOLOR:" . $backgroundColor . "\n";
				$ics_data .= "BORDERCOLOR:" . $borderColor . "\n";
				$ics_data .= "TEXTCOLOR:" . $textColor . "\n";
				$ics_data .= "" . $rec . "";
				$ics_data .= "CLASS:" . $class . "\n";
				$ics_data .= "END:VEVENT\n";
			}
			$ics_data .= "END:VCALENDAR\n";
			echo $ics_data;
		
    }	
     /**
    * search public event by name
    *
    ****
    * @access public
    * @ Param $title
    * @ Return id
    */		
	public function search($title) {
	
		 $xtitle = $this->security->xss_clean($title);
		
		 $events = $this->db->select('*')->from('ic_events')->where('auth', 0)->like('title', $xtitle)->or_like('description', $xtitle)->or_like('location', $xtitle)->order_by('start', 'asc')->get();
  
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
    * search private event by name
    *
    ****
    * @access public
    * @ Param $title, $username
    * @ Return id
    */		
	public function search_private($title, $username) {
	
		 $xtitle = $this->security->xss_clean($title);
		
		 $events = $this->db->select('*')->from('ic_events')->where('username', $username)->like('title', $xtitle)->or_like('description', $xtitle)->or_like('category', $xtitle)->or_like('location', $xtitle)->order_by('start', 'asc')->get();
  
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
                'end'       		=> $entry->end,
				'allDay'    		=> ($entry->allDay=='true') ? true : false,
                'url'       		=> $entry->url,
				'auth'       		=> $entry->auth, 
				'location'       	=> $entry->location,  
				
            );
        }
       echo json_encode($jsonevents);	 

    }	
	
     /**
    * countCalendarEvents - Admin reviews
	*
    ****
    * @access public
    * @ Param $title, $username
    * @ Return id
    */
    public function countEventsByUsername($username) {  
	
		$xusername = $this->security->xss_clean($username);
		
		$this->db->where('username', $xusername); 
		$this->db->where('auth', 0); 
		
		return $this->db->count_all_results('ic_events');
    }	
	
}
 
/* End of file fulcalendar.php */
/* Location: ./application/models/fulcalendar.php */