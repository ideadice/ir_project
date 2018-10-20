<?php

   
    
             /*GET the login key*/
    
            $jsonLogin=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
            $json_data_Login=json_decode($jsonLogin,true);
           
            echo "Testing json_data_login <br><br>";
            print_r($json_data_Login);
            

            /*index call*/          
            
            
            $curl2 = curl_init();
            
            curl_setopt_array($curl2, array(
             CURLOPT_PORT => "9005",
             CURLOPT_URL => "https://api.gto.co.il:9005/v2/xml/market/table/simple?securities=475020&fields=Rate,BaseRateChange,BaseRateChangePercentage,DailyHighestRate,DailyLowestRate,BaseRate,DailyTurnover,AllYearMaximumRate,AllYearMinimumRate",
             CURLOPT_RETURNTRANSFER => TRUE,
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => "GET",
             CURLOPT_POSTFIELDS => "{\n\t\"Login\": {\n\t\t\n\t\t\"User\":\"apizvi01\",\n\t\t\"Password\":\"12345\"\n\t\n\t}\n}",
             CURLOPT_HTTPHEADER => array(
             "Cache-Control: no-cache",
             "Content-Type: application/json",
                 "session: ".$json_data_Login["Login"]["SessionKey"]
             ),
             ));
            
            echo "<br><br> realtime - Testing curl2"."<br>".$curl2."<br><br>";
            print_r($curl2);
            
            $response = curl_exec($curl2); //show the data on the screen.
            //$err = curl_error($curl2);
            
            
            echo "<br><br> Realtime - Testing response"."<br>"."<br><br>";
            print_r($response);
            
            
            curl_close($curl2);
            
            
            
            
            
            
            
            $json_response=json_decode($response);
            
            echo "<br><br> realtime - Testing json_response"."<br>"."<br><br>";
            print_r($json_response);
            
            
            //echo "<br><br> realtime - var_dump"."<br>"."<br><br>";
             
            //if ($err) {
            //    echo "cURL Error #:" . $err;
            //} else {
                
            //    echo $response;
            //}
            
            
            //$data = mb_substr($data, 0, -1);
            //$result = json_decode($data, true);

 
?>