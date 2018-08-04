<?php

            $jsonPresentation=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');

            $json_data_presentation=json_decode($jsonPresentation,true);
            $lengthPresentationJSON = sizeof($json_data_presentation);

            $curl = curl_init();

            curl_setopt_array($curl, array(
             CURLOPT_PORT => "9005",
             CURLOPT_URL => "https://api.gto.co.il:9005/v2/json/market/history?key=475020&fromDate=24062018&toDate=30062018&fields=dailylow",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_CUSTOMREQUEST => "GET",
             //CURLOPT_POSTFIELDS => "{\n\t\"Login\": {\n\t\t\n\t\t\"User\":\"apizvi01\",\n\t\t\"Password\":\"12345\"\n\t\n\t}\n}",
             CURLOPT_HTTPHEADER => array(
             "Cache-Control: no-cache",
             "Content-Type: application/json",
             "session: ".$json_data_presentation["Login"]["SessionKey"]
             ),
             ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
            

            echo "<br>";
            echo gettype($response);
            echo "<br>";
            
            $results = json_decode($response, true);
            
            echo "<br>"."Type of results (array?):";
            echo gettype($results);
            echo "<br>";
            
            echo "<br>"."Print r results"."<br>";
            print_r($results);
            echo "<br>";
            
            echo "<br>"."Print r results array with pre:";
            echo '<pre>'; print_r($results); echo '</pre>';
            echo "<br>";
            
            echo "<br>"."Var dump:"."<br>";
            var_dump($results);
            echo "<br>";
            
            $jsonPresentationtr=file_get_contents($curl);                                 
            $json_data_presentationetTT=json_decode($jsonPresentationtr,true);
            $lengthPresentationJSONR = sizeof($json_data_presentationtr);
            
            echo $json_data_presentationetTT["Securities"]["Security"]["Symbol"];


?>