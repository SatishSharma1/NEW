<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "user";
$route['404_override'] = 'error/page_missing';
$route['(register|login|manageUsers)']='user/$1';
$route['importLead']="lead/importLead";

$route['allLeadsCounselor']="lead/allLeadsCounselor";
$route['allLeadsCounselor/(:any)']="lead/allLeadsCounselor/$1";
$route['allLeadsCounselor/(:any)/(:num)']="lead/allLeadsCounselor/$1/$2";
$route['allLeadsCounselor/(:any)/(:num)/(:num)']="lead/allLeadsCounselor/$1/$2/$3";

/****************************counselor interest tracker*******************************/
/*$route['counselorInterestTracker']="lead/counselorInterestTracker";
$route['counselorInterestTracker/(:any)']="lead/counselorInterestTracker/$1";
$route['counselorInterestTracker/(:any)/(:num)']="lead/counselorInterestTracker/$1/$2";
$route['counselorInterestTracker/(:any)/(:num)/(:num)']="lead/counselorInterestTracker/$1/$2/$3";


$route['freshInterestTrackerCounselor']="lead/freshInterestTrackerCounselor";
$route['freshInterestTrackerCounselor/(:any)']="lead/freshInterestTrackerCounselor/$1";
$route['freshInterestTrackerCounselor/(:any)/(:num)']="lead/freshInterestTrackerCounselor/$1/$2";
$route['freshInterestTrackerCounselor/(:any)/(:num)/(:num)']="lead/freshInterestTrackerCounselor/$1/$2/$3";

$route['sharedInterestTrackerCounselor']="lead/sharedInterestTrackerCounselor";
$route['sharedInterestTrackerCounselor/(:any)']="lead/sharedInterestTrackerCounselor/$1";
$route['sharedInterestTrackerCounselor/(:any)/(:num)']="lead/sharedInterestTrackerCounselor/$1/$2";
$route['sharedInterestTrackerCounselor/(:any)/(:num)/(:num)']="lead/sharedInterestTrackerCounselor/$1/$2/$3";
*/
/****************************counselor interest tracker*******************************/

$route['allLeadsTelecaller']="lead/allLeadsTelecaller";
$route['allLeadsTelecaller/(:any)']="lead/allLeadsTelecaller/$1";
$route['allLeadsTelecaller/(:any)/(:num)']="lead/allLeadsTelecaller/$1/$2";
$route['allLeadsTelecaller/(:any)/(:num)/(:num)']="lead/allLeadsTelecaller/$1/$2/$3";


$route['allLeads']="lead/allLeads";
$route['allLeads/(:any)']="lead/allLeads/$1";
$route['allLeads/(:any)/(:num)']="lead/allLeads/$1/$2";
$route['allLeads/(:any)/(:num)/(:any)']="lead/allLeads/$1/$2/$3";


$route['status']="lead/Status";
$route['connectedLogs']="log/connectedLogs";
$route['connectedLogs/(:any)']="log/connectedLogs/$1";
$route['connectedLogs/(:any)/(:num)']="log/connectedLogs/$1/$2";
$route['connectedLogs/(:any)/(:num)/(:num)']="log/connectedLogs/$1/$2/$3";

$route['missedLogs']="log/missedLogs";
$route['missedLogs/(:any)']="log/missedLogs/$1";
$route['missedLogs/(:any)/(:num)']="log/missedLogs/$1/$2";
$route['missedLogs/(:any)/(:num)/(:num)']="log/missedLogs/$1/$2/$3";

$route['allLogs']="log/allLogs";
$route['allLogs/(:any)']="log/allLogs/$1";
$route['allLogs/(:any)/(:num)']="log/allLogs/$1/$2";
$route['allLogs/(:any)/(:num)/(:num)']="log/allLogs/$1/$2/$3";

$route['freshLead']="lead/freshLead";
$route['freshLead/(:any)']="lead/freshLead/$1";
$route['freshLead/(:any)/(:num)']="lead/freshLead/$1/$2";
$route['freshLead/(:any)/(:num)/(:num)']="lead/freshLead/$1/$2/$3";

$route['invalidLead']="lead/invalidLead";
$route['invalidLead/(:any)']="lead/invalidLead/$1";
$route['invalidLead/(:any)/(:num)']="lead/invalidLead/$1/$2";
$route['invalidLead/(:any)/(:num)/(:num)']="lead/invalidLead/$1/$2/$3";

$route['newLead']="lead/newLead";
$route['newLead/(:any)']="lead/newLead/$1";
$route['newLead/(:any)/(:num)']="lead/newLead/$1/$2";
$route['newLead/(:any)/(:num)/(:num)']="lead/newLead/$1/$2/$3";

$route['attemptedCounselor']="lead/attemptedCouselorLeads";
$route['attemptedCounselor/(:any)']="lead/attemptedCouselorLeads/$1";
$route['attemptedCounselor/(:any)/(:num)']="lead/attemptedCouselorLeads/$1/$2";
$route['attemptedCounselor/(:any)/(:num)/(:num)']="lead/attemptedCouselorLeads/$1/$2/$3";


$route['attemptedLeads']="lead/attemptedLeads";
$route['attemptedLeads/(:any)']="lead/attemptedLeads/$1";
$route['attemptedLeads/(:any)/(:num)']="lead/attemptedLeads/$1/$2";
$route['attemptedLeads/(:any)/(:num)/(:num)']="lead/attemptedLeads/$1/$2/$3";

$route['telecaller']="lead/telecallerleads";
$route['telecaller/(:any)']="lead/telecallerleads/$1";
$route['telecaller/(:any)/(:num)']="lead/telecallerleads/$1/$2";
$route['telecaller/(:any)/(:num)/(:num)']="lead/telecallerleads/$1/$2/$3";


$route['counselor']="lead/counselorleads";
$route['counselor/(:any)']="lead/counselorleads/$1";
$route['counselor/(:any)/(:num)']="lead/counselorleads/$1/$2";
$route['counselor/(:any)/(:num)/(:num)']="lead/counselorleads/$1/$2/$3"; 

/********************************* admin interest tracker ********************************/
/*
$route['interestTracker']="lead/interestTracker";
$route['interestTracker/(:any)']="lead/interestTracker/$1";
$route['interestTracker/(:any)/(:num)']="lead/interestTracker/$1/$2";
$route['interestTracker/(:any)/(:num)/(:num)']="lead/interestTracker/$1/$2/$3";

$route['freshInterestTracker']="lead/freshInterestTracker";
$route['freshInterestTracker/(:any)']="lead/freshInterestTracker/$1";
$route['freshInterestTracker/(:any)/(:num)']="lead/freshInterestTracker/$1/$2";
$route['freshInterestTracker/(:any)/(:num)/(:num)']="lead/freshInterestTracker/$1/$2/$3";

$route['sharedInterestTracker']="lead/sharedInterestTracker";
$route['sharedInterestTracker/(:any)']="lead/sharedInterestTracker/$1";
$route['sharedInterestTracker/(:any)/(:num)']="lead/sharedInterestTracker/$1/$2";
$route['sharedInterestTracker/(:any)/(:num)/(:num)']="lead/sharedInterestTracker/$1/$2/$3";
*/
/********************************* Advance search  ********************************/

$route['allLead']="advanceSearch/allLeads";
$route['allLead/(:any)']="advanceSearch/allLeads/$1";
$route['allLead/(:any)/(:num)']="advanceSearch/allLeads/$1/$2";
$route['allLead/(:any)/(:num)/(:any)']="advanceSearch/allLeads/$1/$2/$3";

/********************************* admin interest tracker ********************************/
$route['todayLeads']="lead/todayLeads";
$route['todayLeads/(:any)']="lead/todayLeads/$1";
$route['todayLeads/(:any)/(:num)']="lead/todayLeads/$1/$2";
$route['todayLeads/(:any)/(:num)/(:num)']="lead/todayLeads/$1/$2/$3";


//users public profile
//$route['([A-Za-z0-9_]+)'] = 'home/view/$1';

//cifullcalendar default urls
$route['register'] = 'register';
$route['feeds'] = 'feeds';
$route['calender-profile'] = 'profile/home';
$route['admin'] = 'admin/home';

//language
$route['^en/(.+)$'] = "$1";
$route['^fr/(.+)$'] = "$1";
$route['^es/(.+)$'] = "$1";

/*
$route['freshInterestTrackerNotTransfered']="lead/freshInterestTrackerNotTransfered";
$route['freshInterestTrackerNotTransfered/(:any)']="lead/freshInterestTrackerNotTransfered/$1";
$route['freshInterestTrackerNotTransfered/(:any)/(:num)']="lead/freshInterestTrackerNotTransfered/$1/$2";
$route['freshInterestTrackerNotTransfered/(:any)/(:num)/(:num)']="lead/freshInterestTrackerNotTransfered/$1/$2/$3";
*/
/* End of file routes.php */
/* Location: ./application/config/routes.php */
