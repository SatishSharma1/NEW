<?php 
error_reporting(0);
// helper functions
function h1($str) {
	return "<h1>" . $str . "</h1>";
}
function h3($str) {
	return "<h2>" . $str . "</h2>";
}
function error($msg, $code = 0) {
	return "<error><message>$msg</message><code>$code</code></error>";
}
function message($msg) {
	return "<response>$msg</response>";
}
function gget($var) {
	if(isset($_REQUEST[$var])) {
		return trim($_REQUEST[$var]);
	}elseif(isset($_REQUEST[strtolower($var)])) {
		return trim($_REQUEST[strtolower($var)]);
	}else {
		return '';
	}
}
function insert($fields, $table, $conn) {
	$field = ''; $val = '';
	foreach ($fields as $key => $value) {
		$field .= $key . ", ";
		$val .= ":" . $key . ", ";
	}
	$q = "INSERT INTO " . $table . "(" . trim($field, ', ') . ") VALUES(" . trim($val, ', ') . ")";
	
	$stmt = $conn->prepare( $q );
	$stmt->execute($fields);
}
function query($q, $param = array(), $conn) {
	$stmt = $conn->prepare($q);
	$stmt->execute($param);
	return $stmt->fetchAll();
}
// insert(['adil' => 'data1', 'adil2' => 'data2'], 'table1', 'app');

// end function
header("Content-type: application/xml");
try {
	$db = new PDO("mysql:host=localhost;dbname=leadmentor", 'root', '');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo error("Unable to connect to database: " . $e->getMessage(), $e->getCode());
	die();
}


if(gget('key') !== '') {
	$keyRes = query("SELECT id FROM organization WHERE `key` = :key", array(
			"key" => gget('key')
		), $db);
	
	if($keyRes) {
		$orgId = $keyRes[0][0];
		if(gget('customerNumber') !== '') {
			$idRes = query("SELECT id, name FROM leads WHERE phone=:phone AND organizationId=:orgId", array("phone" => gget('customerNumber'),"orgId" => $orgId), $db);
			if($idRes) {
			 	$id = $idRes[0][0];
			 	$name = $idRes[0][1];
			
			}else {
				insert(array(
					'phone' => gget('customerNumber'),
					'organizationId' => $orgId
					), 'leads', $db);
				$id = $db->lastInsertId();
			}
			$error = array();$i=0;
			if(gget('date')!=''){
				if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",gget('date'))){
					$error[$i]['field'] = 'date';
					$error[$i]['message'] = 'Date should be in format YYYY-MM-DD';
					$i++;
				}
			}
			if(gget('field_date_1')!=''){
				if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",gget('field_date_1'))){
					$error[$i]['field'] = 'field_date_1';
					$error[$i]['message'] = 'field_date_1 should be in format YYYY-MM-DD';
					$i++;
				}
			}
			if(gget('field_date_2')!=''){
				if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",gget('field_date_2'))){
					$error[$i]['field'] = 'field_date_2';
					$error[$i]['message'] = 'field_date_2 should be in format YYYY-MM-DD';
					$i++;
				}
			}
			if(gget('time')!=''){
				if ((!preg_match("/([01]?[0-9]|2[0-3]):[0-5][0-9]/",gget('time')))||(!preg_match("/(\d{2}):(\d{2})/",gget('time')))){
					$error[$i]['field'] = 'time';
					$error[$i]['message'] = 'time should be in format hh:ii:ss(24 hour format)';
					$i++;
				}
			}
			if(gget('field_time_1')!=''){
				if ((!preg_match("/([01]?[0-9]|2[0-3]):[0-5][0-9]/",gget('field_time_1')))||(!preg_match("/(\d{2}):(\d{2})/",gget('field_time_1')))){
					$error[$i]['field'] = 'field_time_1';
					$error[$i]['message'] = 'field_time_1 should be in format hh:ii:ss(24 hour format)';
					$i++;
				}
			}
			if(gget('field_time_2')!=''){
				if ((!preg_match("/([01]?[0-9]|2[0-3]):[0-5][0-9]/",gget('field_time_2')))||(!preg_match("/(\d{2}):(\d{2})/",gget('field_time_2')))){
					$error[$i]['field'] = 'field_time_2';
					$error[$i]['message'] = 'field_time_2 should be in format hh:ii:ss(24 hour format)';
					$i++;
				}
			}
			if(gget('field_datetime_1')!=''){
				if ((!preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/",gget('field_datetime_1')))||(!preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/",gget('field_datetime_1')))){
					$error[$i]['field'] = 'field_datetime_1';
					$error[$i]['message'] = 'field_datetime_1 should be in format YYYY-MM-DD hh:ii:ss(24 hour format)';
					$i++;
				}
			}
			if(gget('field_datetime_2')!=''){
				if ((!preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/",gget('field_datetime_2')))||(!preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/",gget('field_datetime_2')))){
					$error[$i]['field'] = 'field_datetime_2';
					$error[$i]['message'] = 'field_datetime_2 should be in format YYYY-MM-DD hh:ii:ss(24 hour format)';
					$i++;
				}
			}
			/*insert(array(
				'leadId' => $id,
				'organizationId' => $orgId,
				'date' => gget('date'),
				'time' => gget('time'),
				'dateTime' => gget('dateTime'),
				//'ticketId' => gget('ticketId'),
				'customerNumber' =>  '+' . gget('customerNumber'),
				'customerStatus' => gget('customerStatus'),
				'customerCallDuration' => gget('customerCallDuration'),
				'agentList' => gget('agentList'),
				'agentConnectedTo' => gget('agentConnectedTo'),
				'agentStatus' => gget('agentStatus'),
				'agentCallDuration' => gget('agentCallDuration'),
				'uniqueId' => gget('uniqueId'),
				'callRecordingurl' => gget('callRecordingurl'),
				'ivrType' => gget('ivrType'),
				'circle' => gget('circle'),
				'calledNumber' => gget('calledNumber')
			), 'callLogs', $db);*/
			if(empty($error)){
				insert(array(    
					'leadId' => $id,
					'organizationId' => $orgId,
					'date' => gget('date'),
					'time' => gget('time'),
					'customerNumber' =>  gget('customerNumber'),
					'customerStatus' => gget('customerStatus'),  
					'agentNumber' => gget('agentNumber'),
					'callRecordingurl' => gget('callRecordingurl'),
					'ivrType' => gget('ivrType'),
					'field_date_1' => gget('field_date_1'),
					'field_date_2' => gget('field_date_2'),
					'field_time_1' => gget('field_time_1'),
					'field_time_2' => gget('field_time_2'),
					'field_datetime_1' => gget('field_datetime_1'),
					'field_datetime_2' => gget('field_datetime_2'),
					'field_varchar_1' => gget('field_varchar_1'),
					'field_varchar_2' => gget('field_varchar_2'),
					'field_varchar_3' => gget('field_varchar_3'),
					'field_varchar_4' => gget('field_varchar_4'),
					'field_varchar_5' => gget('field_varchar_5'),
					'field_varchar_6' => gget('field_varchar_6'),
					'field_varchar_7' => gget('field_varchar_7'),
					'field_varchar_8' => gget('field_varchar_8'),
					'field_varchar_9' => gget('field_varchar_9'),
					'field_varchar_10' => gget('field_varchar_10'),
					'field_enum_1' => gget('field_enum_1'),
					'field_enum_2' => gget('field_enum_2'),
					'field_enum_3' => gget('field_enum_3'),
					'field_enum_4' => gget('field_enum_4'),
					'field_enum_5' => gget('field_enum_5'),
					'field_number_1' => gget('field_number_1'),
					'field_number_2' => gget('field_number_2'),
					'field_number_3' => gget('field_number_3'),
					'field_number_4' => gget('field_number_4'),
					'field_number_5' => gget('field_number_5')
				), 'callLogs', $db);

           $logid = $db->lastInsertId();

              $this_table = 'logs_list_view_' . $orgId;
               $q = "SHOW TABLES LIKE '$this_table'";
             $resu=query($q, $param = array(), $db);

                 
             

             if(empty($resu)){
                  // if table not present
             }else{
                // if table present insert logic 
                   $qs = "SHOW COLUMNS FROM $this_table";
                  $columns=query($qs, $param = array(), $db);
                    $columns=array_splice($columns,3);
                    $length= sizeof($columns);
                    $ArrayA = array('logId' =>$logid,'leadId'=>$id);
                    $ArrayB = array(); 
                    for($c=0;$c<$length;$c++){
                    	$fieldName = $columns[$c]['Field'];
                    	
                    	$ArrayB[$fieldName] = gget($fieldName);
                    }
                   $arrayC= array_merge($ArrayA,$ArrayB);
                  // var_dump($arrayC);

                insert($arrayC, $this_table, $db);

             }
          
         //  echo $table_exists = $db->mysql_num_rows($checktable) > 0;



			}
			
			if($error){
				foreach($error as $e){
					$msg.="<node><code>300</code>";
					$msg.="<message>".$e['message']."</message></node>";
				}
				echo message($msg);
			}else{
					echo message("<code>200</code><message>Data inserted</message>");
			}
		}else {
			echo error("CustomerNumber field is required", 404);
		}

	}else {
		echo error("Key is not matched (Invalid Key)", 403);
	}
} else {
	echo error("Key is required!  It could not be empty", 404);
}
/*if(isset($name)) {
	echo message("<name>$name</name>");
}*/


// http://meetuniversity.in/call_log_api.php?key=&date=&time=&dateTime=&ticketId=&customerNumber=+91750399979&customerStatus=&customerCallDuration=&agentList=&agentConnectedTo=&agentStatus=&agentCallDuration=&uniqueId=&callRecordingurl=&ivrType=

// http://meetuniversity.in/call_log_api.php?key=072e41178c79b3f4783e90e0cc841cf4&date=2015-10-03&time=02:30:23&customerNumber=+917827701616&customerStatus=&field_date_1=&field_date_2=&field_time_1=&field_time_2=&field_datetime_1=&field_datetime_2=&field_varchar_1=&field_varchar_2=&field_varchar_3=&field_varchar_4=&field_varchar_5=&field_varchar_6=&field_varchar_7=&field_varchar_8=&field_varchar_9=&field_varchar_10=&field_enum_1=&field_enum_2=&field_enum_3=&field_enum_4=&field_enum_5=&field_number_1=&field_number_2=&field_number_3=&field_number_4=&field_number_5=
