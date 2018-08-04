<?php

function getVal($val) {
    
    global $json_data_Login;
    $path="https://api.gto.co.il:9005/v2/xml/market/table/simple?securities=475020&fields=".$val;
    
    $curl2 = curl_init();
    
    curl_setopt_array($curl2, array(
        CURLOPT_PORT => "9005",
        CURLOPT_URL => $path,
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
    
    $response = curl_exec($curl2);
    curl_close($curl2);
    
    //echo "<br><br> realtime - into function - before return"."<br>"."<br><br>";
    
    return $response;
    
}
    
//Get Login key
    
$jsonLogin=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
$json_data_Login=json_decode($jsonLogin,true);
           
      
// Get the values          
           
echo "<br><br> realtime - function"."<br>"."<br><br>";
            
$AllYearMinimumRate = getVal("AllYearMinimumRate");
$AllYearMaximumRate = getVal("AllYearMaximumRate");
$DailyTurnover = getVal("DailyTurnover");
$BaseRate = getVal("BaseRate");
$DailyLowestRate = getVal("DailyLowestRate");
$DailyHighestRate = getVal("DailyHighestRate");
$BaseRateChangePercentage = getVal("BaseRateChangePercentage");
$BaseRateChange = getVal("BaseRateChange");
$Rate = getVal("Rate");

echo "<br><br> realtime - print function return AllYearMinimumRate:"."<br>";
print_r($AllYearMinimumRate);
echo "<br>realtime - print function return AllYearMaximumRate:"."<br>";
print_r($AllYearMaximumRate);
echo "<br>realtime - print function return DailyTurnover:"."<br>";
print_r($DailyTurnover);
echo "<br>realtime - print function return BaseRate:"."<br>";
print_r($BaseRate);
echo "<br>realtime - print function return DailyLowestRate:"."<br>";
print_r($DailyLowestRate);
echo "<br>realtime - print function return DailyHighestRate:"."<br>";
print_r($DailyHighestRate);
echo "<br>realtime - print function return BaseRateChangePercentage:"."<br>";
print_r($BaseRateChangePercentage);
echo "<br>realtime - print function return BaseRateChange:"."<br>";
print_r($BaseRateChange);
echo "<br>realtime - print function return Rate:"."<br>";
print_r($Rate);



//Without function - using json

$curl3 = curl_init();

curl_setopt_array($curl3, array(
    CURLOPT_PORT => "9005",
    CURLOPT_URL => "https://api.gto.co.il:9005/v2/json/market/table/simple?securities=475020&fields=Rate,BaseRateChange,BaseRateChangePercentage,DailyHighestRate,DailyLowestRate,BaseRate,DailyTurnover,AllYearMaximumRate,AllYearMinimumRate",
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

$response_json = curl_exec($curl3);
curl_close($curl3);

$results_json = json_decode($response_json, true);

echo "<br>"."Print r results array with pre:";
echo '<pre>'; print_r($results_json); echo '</pre>';
echo "<br>";





 
?>