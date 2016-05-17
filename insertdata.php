<?php


  
                                                                                                                     


function post_to_url($url, $data) {
  /* $fields = '';
   foreach($data as $key => $value) { 
      $fields .= $key . '=' . $value . '&'; 
   }
   rtrim($fields, '&');

   $post = curl_init();

   curl_setopt($post, CURLOPT_URL, $url);
   curl_setopt($post, CURLOPT_POST, count($data));
   curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
   curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

  echo  $result = curl_exec($post);


   curl_close($post);  */

$ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data))                                                                       
); 
$result = curl_exec($ch);                                                                                                                

}

$data = array(
   "uname" => "meetuni",
   "upwd" => "meetuni123",
   "ukey" => "4C0327BC-91FC-4ACB-9243-FF883B89F5AA",
   "lead" => "ACV",
   "fname" => "ACV",
   "lname" => "ACV",
   "email" => "ACV",
   "phone" => "ACV",
   "mob" => "ACV",
   "country" => "ACV",
   "state" => "ACV",
   "city" => "ACV",
   "cat" => "ACV",
    "countryInt" => "ACV",  
   "eduLevel" => "ACV",
   "calltime" => "ACV",
   "branch" => "ACV",
   "intake" => "ACV",
   "uniclg" => "ACV"
  
); 

 /*  $data = array(
   "called" => "meetuni",
   "caller" => "meetuni123"
   //"TextBox3" => "4C0327BC-91FC-4ACB-9243-FF883B89F5AA"
   
);  */
$data = json_encode($data);

 //post_to_url("http://gopapi.global-opportunities.co.in/PushToJson.aspx", $data);
 //post_to_url("contact/process", $data);
   post_to_url("http://gopapi.global-opportunities.co.in/DataProcessor.aspx?Save=1", $data);
  
 //var_dump($response);




//$data = array("name" => "Hagrid", "age" => "36");                                                                    
//$data_string = json_encode($data);                                                                                   
                                                                                                                     


 ?>