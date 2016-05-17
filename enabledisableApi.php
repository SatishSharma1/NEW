<?php
                                                                                                                     
function enablepopupAPI() {  
$data = array("client_name"=>"leadmentor");
$data = json_encode($data);
  $url = "https://konnect.knowlarity.com/api/v1/registrations/%2B918213932970/enable";
  $ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
                                                         
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json', 
    'Authorization: b19afce5-7e5c-4180-a893-4044d0e58a8a',                                                                               
    'Content-Length: ' . strlen($data))                                                                       
); 
//var_dump($data);
//die();
echo $result = curl_exec($ch);                                                                                                                

}


   post_to_url();

 ?>