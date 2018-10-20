<?php

function getVal($val) {
    
    global $json_data_Login;
    $path="https://api.gto.co.il:9005/v2/xml/market/table/simple?securities=6120216&fields=".$val;
    
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
    
    return $response;
    
}
    
//Get Login key
    
$jsonLogin=file_get_contents('https://irwebsites.co.il/Investor_Relations/pages/quote/src/login.php');
$json_data_Login=json_decode($jsonLogin,true);
           
      
// Get the values          
                   
$EngSymbol = getVal("EngSymbol");
$EngName = getVal("EngName");
$YesterdayYield = getVal("YesterdayYield");
$YesterdayLastDayLockRate = getVal("YesterdayLastDayLockRate");
// $DailyTurnover = getVal("DailyTurnover");
// $BaseRate = getVal("BaseRate");
// $DailyLowestRate = getVal("DailyLowestRate");
// $DailyHighestRate = getVal("DailyHighestRate");
// $BaseRateChangePercentage = getVal("BaseRateChangePercentage");
// $BaseRateChange = getVal("BaseRateChange");
// $Rate = getVal("Rate");

?>
<!DOCTYPE html>
<html>
<head>
        <link href="css/quote-en.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>
    
<div>
    <dl>
        <dt>Bond Symbol:</dt>
        <dd><?php echo "&nbsp;"; print_r($EngSymbol); ?></dd>
        <dt>Name:</dt>
        <dd><?php echo "&nbsp;"; print_r($EngName); ?></dd>
        <dt>Price:</dt>
        <dd><?php echo "&nbsp;"; print_r($YesterdayLastDayLockRate); ?></dd>
        <dt>Change:</dt>
        <dd><?php echo "&nbsp;"; print_r($YesterdayYield); ?></dd>
    </dl>
</div>  
      
</body>