<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
/* $config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mail_protocol']='smtp';
$config['smtp_server']='relay.mailserv.in';
$config['smtp_user_name']='relay@meetuniversities.com';
$config['smtp_pass']='M^et4025'; */

//MasterAdmin Email Address 
/*
$config['masterAdminEmail'] = 'debal@webinfomart.com';

$config['webMasterEmail'] = 'leadmentor@leadmentor.in';
$config['emailSubject'] = 'Account Activation';
$config['webSiteName'] = 'leadmentor';
$config['userCreate'] = 'User Create';
$config['templateCreate'] = 'Sms Template Created';
$config['resetPassword'] = 'Password Reset';
$config['changedPassword'] = 'Change Password';
$config['CampaignPerformance'] = 'Campaign Performance';
$config['campaignLogs'] = 'Campaign Logs';
$config['EventAlert'] = 'Event Reminder';
$config['MeetUnivEmail'] = 'Meetuniv@meetuniv.com';
$config['emailActivationExpire'] = 172800;

*/


/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config['protocol'] = 'smtp';
$config['smtp_port'] = '587';
$config['smtp_host'] = 'smtp.sendgrid.net';
$config['smtp_user'] = 'musendgrid';
$config['smtp_pass'] = '1qazxsw2';
$config['mailtype'] = 'html';

$config['siteadmin'] = 'debal@meetuniv.com';
$config['keshav'] = 'keshav@meetuniv.com';
$config['nitin'] = 'nitin@meetuniv.com';
$config['system'] = 'mu@meetuniv.com';
$config['reply'] = 'connect@meetuniv.com';
$config['systemname'] = 'Meet University';
$config['tanmoy'] = 'tanmoy@meetuniv.com';
$config['neha'] = 'neha@webinfomart.com';
$config['kimmi'] = 'kimmi@meetuniv.com';

$config['admins'] = array('debal@meetuniv.com','nitin@meetuniv.com','keshav@meetuniv.com');

$config['doc_urlname'] = 'Doc URL Opened';
$config['doc_uploadname'] = 'Doc Upload';
$config['doc_errorname'] = 'Profile Error';
$config['sys_idlename'] = 'System Idle';

$config['helpline_number'] = '08375034794';

/*$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mail_protocol']='smtp';
$config['smtp_server']='smtp.falconide.com';
$config['smtp_user_name']='meetuniv';
$config['smtp_pass']='Meetun!1v';*/

/* End of file email.php */
/* Location: ./application/config/email.php */



/* End of file email.php */
/* Location: ./application/config/email.php */