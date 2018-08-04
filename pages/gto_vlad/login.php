<?php

 /*Login to API*/
 
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
 
 echo "Login - Testing curl :"."<br>".$curl."<br><br>";
 
 $response = curl_exec($curl);
 $err = curl_error($curl);
 
 curl_close($curl);
 
// if ($err) {
//     echo "cURL Error #:" . $err;
 //} else {
 //    echo $response;
 //}
 
 echo "Login - Testing response"."<br>".$response."<br><br>";
 
 
 

 
 

 


?>