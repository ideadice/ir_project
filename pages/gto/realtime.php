<?php

   
    
             /*GET the login key*/
    
            $jsonLogin=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
            $json_data_Login=json_decode($jsonLogin,true);
           // $lengthPresentationJSON = sizeof($json_data_presentation);

            

            /*index call*/          
            
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
             CURLOPT_PORT => "9005",
             CURLOPT_URL => "https://api.gto.co.il:9005/v2/json/market/table/simple?securities=475020&fields=Rate,BaseRateChange,BaseRateChangePercentage,DailyHighestRate,DailyLowestRate,BaseRate,DailyTurnover,AllYearMaximumRate,AllYearMinimumRate",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => "GET",
             //CURLOPT_POSTFIELDS => "{\n\t\"Login\": {\n\t\t\n\t\t\"User\":\"apizvi01\",\n\t\t\"Password\":\"12345\"\n\t\n\t}\n}",
             CURLOPT_HTTPHEADER => array(
             "Cache-Control: no-cache",
             "Content-Type: application/json",
                 "session: ".$json_data_Login["Login"]["SessionKey"]
             ),
             ));
            
            $response_json = curl_exec($curl);
            curl_close($curl);
            
            $results_json = json_decode($response_json, true);
            
            
            foreach ($results_json->items as $item) {
                var_dump($item[Table][-AsOfDate]);
            }
            
            
            echo "<br>"."BaseRate value: ".$results_json[Table][Security][0][BaseRate];
            echo "<br>"."AllYearMaximumRate value: ".$results_json[Table][Security][0][AllYearMaximumRate];
            //echo "try 2 : ".$results_json->Table->Security->0->BaseRate;
           
            
            echo "<br>"."Print r results array with pre:";
            echo '<pre>'; print_r($results_json); echo '</pre>';
            echo "<br>";

            //echo substr($response,81,5);
           
            //echo substr($response,52,5);
            

            
            /*$dom = new DOMDocument;
            $dom->loadXML($response);
            $books = $dom->getElementsByTagName('rate');
            foreach ($books as $book) {
              //  $text=$book->nodeValue;
                echo $book->nodeValue;
                                     
            }*/

            //echo $text;
          
           
            
            //echo "1  \n\n".substr($response,1,4);           
            //echo "\nthe size : ".sizeof($response);  
           /* $jsonRealTime=file_get_contents($curl);                                 
           $json_data_realTime=json_decode($jsonRealTime,true);
           
           //echo "json size ".sizeof($json_data_realTime);
           
           
           curl_close($curl);*/
           
           
            //echo "The size is : ".sizeof($json_data_realTime);
            //$lengthPresentationJSONR = sizeof($json_data_presentationtr);
            
          
          //echo "test  : ".$json_data_presentationetTT["Securities"]["Security"]["Symbol"];
           
          /*curl_setopt_array($curl, array(
            CURLOPT_PORT => "9005",
            CURLOPT_URL => "https://api.gto.co.il:9005/v2/json/market/tns/chart?key=142",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            //CURLOPT_POSTFIELDS => "{\n\t\"Login\": {\n\t\t\n\t\t\"User\":\"apizvi01\",\n\t\t\"Password\":\"12345\"\n\t\n\t}\n}",
            CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "session: ".$json_data_login["Login"]["SessionKey"]
            ),
            ));*/
           
           // "https://api.gto.co.il:9005/v2/xml/market/table/simple?securities=475020&fields=BaseRate,BaseRateChange,TradingStage,HebName,LastDealRate,WeekYield,YesterdayYield,AllMonthYield,BeginOfYearYield,MinusOneYearYield,DailyHighestRate,DailyLowestRate,DailyTurnover",
           
           
           
           
           
           
?>