<?php

function getVal($val) {
    
    global $json_data_Login;
    $path="https://api.gto.co.il:9005/v2/xml/market/table/simple?securities=6120158&fields=".$val;
    
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
    
$jsonLogin=file_get_contents('https://irwebsites.co.il/Investor_Relations/pages/quote/login.php');
$json_data_Login=json_decode($jsonLogin,true);
           
      
// Get the values          
           
echo "<br><br> realtime - function"."<br>"."<br><br>";
            
$EngSymbol = getVal("EngSymbol");
$HebName = getVal("HebName");
$DailyTurnover = getVal("DailyTurnover");
$BaseRate = getVal("BaseRate");
$DailyLowestRate = getVal("DailyLowestRate");
$DailyHighestRate = getVal("DailyHighestRate");
$BaseRateChangePercentage = getVal("BaseRateChangePercentage");
$BaseRateChange = getVal("BaseRateChange");
$Rate = getVal("Rate");

echo "<br><br> realtime - print function return EngSymbol:"."<br>";
print_r($EngSymbol);
echo "<br>realtime - print function return HebName:"."<br>";
print_r($HebName);
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



 
?>