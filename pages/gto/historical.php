<?php

//echo "hello";


/*$.ajax
({
    type: "GET",
    url: "URL",
    dataType: 'json',
    async: false,
    username: 'apizvi01',
    password: '12345',
    data: '{ "comment" }',
    success: function (){
        alert('Thanks for your comment!');
    }
});*/


/* ****First try**** */
// create a new cURL resource
/*$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "https://api.gto.co.il:9005/V2/json/login");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);*/

/* ****First try**** */



/* ******Second try**********/

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

//function CallAPI($method, $url, $data = false){
   /* $url="https://api.gto.co.il:9005/V2/json/login";
    $data=false;
    $method="POST";
    
    $curl = curl_init();
    
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    
    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "apizvi01:12345");
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: 0'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array()));
    
    $result = curl_exec($curl);
   // echo $curl+"   size :  "+sizeof($result);
    echo $result;
    $i=0;
    
    foreach ($result as $value) {
        echo i."."+$value."\n";
        $i++;
    }
    
    curl_close($curl);*/
    
  //  return $result;
//}

    /* ******Second try**********/

    
    /* ****Third Try****   */
    /*$url ="https://api.gto.co.il:9005/V2/json/login";
    $resource = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept:application/json, Content-Type:application/json"]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    $result = curl_exec($ch);
    
    echo sizeof($ch);*/
    
    /* ****Third Try****   */
    
        /*  $host ="https://api.gto.co.il:9005/V2/json/login";
            $username="apizvi01";
            $password="12345";
            $process = curl_init($host);
            curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: Application/json', $additionalHeaders));
            curl_setopt($process, CURLOPT_HEADER, 1);
            curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            curl_setopt($process, CURLOPT_POST, 1);
            curl_setopt($process, CURLOPT_POSTFIELDS, $payloadName);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
            $return = curl_exec($process);
            echo sizeof($return);
            echo $return;
            foreach($return as $str){
                echo $str;
                
            }
            
            $jsonPresentation=file_get_contents($host);                    
            $json_data_presentation=json_decode($jsonPresentation,true);
            $lengthPresentationJSON = sizeof($json_data_presentation);
            echo  $lengthPresentationJSON."\n";
           echo  $json_data_presentation["User"];
            
            
            curl_close($process);*/
   
    
    
    
    
            $jsonPresentation=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
            
            
            $json_data_presentation=json_decode($jsonPresentation,true);
            $lengthPresentationJSON = sizeof($json_data_presentation);
            
            /*echo "<br/>the presentation length is: ".$lengthPresentationJSON;
            echo "<br/>Session Key : ".$json_data_presentation["Login"]["SessionKey"];*/
            
            
          
            
            $curl = curl_init();
            
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
                    "session: ".$json_data_presentation["Login"]["SessionKey"]
                ),
            ));*/
            
           // "https://api.gto.co.il:9005/v2/xml/market/table/simple?securities=475020&fields=BaseRate,BaseRateChange,TradingStage,HebName,LastDealRate,WeekYield,YesterdayYield,AllMonthYield,BeginOfYearYield,MinusOneYearYield,DailyHighestRate,DailyLowestRate,DailyTurnover",
           
            /*index call*/
            
            curl_setopt_array($curl, array(
             CURLOPT_PORT => "9005",
             CURLOPT_URL => "https://api.gto.co.il:9005/v2/xml/market/history?key=475020&fields=baserate&fromDate=24062018&toDate=30062018",
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
            
            $jsonPresentationtr=file_get_contents($curl);                                 
            $json_data_presentationetTT=json_decode($jsonPresentationtr,true);
            $lengthPresentationJSONR = sizeof($json_data_presentationtr);
            
            echo $json_data_presentationetTT["Securities"]["Security"]["Symbol"];
    
           /* echo "<br/>the presentation length is: ".$lengthPresentationJSON;
            echo "<br/> Key : ".$json_data_presentationt["Tns"]["-Key"];
*/
//https://api.gto.co.il:9005/v2/json/market/history?key=445015&fromDate=24072016&toDate=3 1072016 


?>