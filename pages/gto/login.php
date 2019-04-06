<?php

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

//function CallAPI($method, $url, $data = false){
 /*$url="https://api.gto.co.il:9005/V2/json/login";
 $data=false;
 $method="POST";
 
 $curl = curl_init();
 
 switch ($method)
 {
 case "POST":
 curl_setopt($curl, CURLOPT_POST, 1);
 curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 curl_setopt($curl, CURLOPT_USERPWD, "apizvi01:12345");
 
 curl_setopt($curl, CURLOPT_URL, $url);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
 curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));
 if ($data)
 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
 break;
 case "PUT":
 curl_setopt($curl, CURLOPT_PUT, 1);
 break;
 default:
 if ($data)
 $url = sprintf("%s?%s", $url, http_build_query($data));
 }*/
 
 // Optional Authentication:
 /*curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 curl_setopt($curl, CURLOPT_USERPWD, "apizvi01:12345");
 
 curl_setopt($curl, CURLOPT_URL, $url);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
 curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));*/
// curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array()));
 
 /*$result = curl_exec($curl);
 // echo $curl+"   size :  "+sizeof($result);
 echo $data;
 echo $result;
 $i=0;
 
 foreach ($result as $value) {
 echo i."."+$value."\n";
 $i++;
 }
 
 curl_close($curl);
 
 //  return $result;
 //}*/
 
 
 /*call to api*/
 
 $curl = curl_init();
 
 curl_setopt_array($curl, array(
     CURLOPT_PORT => "9005",
     CURLOPT_URL => "https://api.gto.co.il:9005/V2/json/login",
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => "",
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 30,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS => "{\n\t\"Login\": {\n\t\t\n\t\t\"User\":\"apizvi01\",\n\t\t\"Password\":\"12345\"\n\t\n\t}\n}",
     CURLOPT_HTTPHEADER => array(
         "Cache-Control: no-cache",
         "Content-Type: application/json",
         "Postman-Token: 04cfc7c3-c4ea-4732-a6a7-301ae8b37b0a"
     ),
 ));
 
 $response = curl_exec($curl);
 $err = curl_error($curl);
 
 
 
 /*$jsonLogin=file_get_contents($response);
 $json_data_Login=json_decode($jsonLogin,true);
 echo "1. ".$json_data_Login["Login"]["SessionKey"];*/
 
 
 
 
 
 
 curl_close($curl);
 
 if ($err) {
     echo "cURL Error #:" . $err;
 } else {
     echo $response;
 }
 
 

 
 

 


?>