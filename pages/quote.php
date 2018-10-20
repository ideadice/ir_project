<?php


#Connect to DB
include "/code/mysql/database.php";

      /*START LogIn part*/
$curl_logIn = curl_init();

curl_setopt_array($curl_logIn, array(
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

$response_logIn = curl_exec($curl_logIn);
$err = curl_error($curl_logIn);


$results_json_Login = json_decode($response_logIn, true);

        /*END LogIn part*/


/*$jsonLogin=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
$json_data_Login=json_decode($jsonLogin,true);*/
// $lengthPresentationJSON = sizeof($json_data_presentation);

                /*   START values call   */
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_PORT => "9005",
    CURLOPT_URL => "https://api.gto.co.il:9005/v2/json/market/table/simple?securities=475020&fields=Rate,BaseRateChange,BaseRateChangePercentage,DailyHighestRate,DailyLowestRate,BaseRate,HebName,EngName,HebSymbol,EngSymbol,Exchange,Symbol,DailyTurnover,AllYearMaximumRate,AllYearMinimumRate,LastDealDate",
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
        "session: ".$results_json_Login["Login"]["SessionKey"]
    ),
));

$response_json = curl_exec($curl);
curl_close($curl);

$results_json = json_decode($response_json, true);


     /*    Values of quote     */
$BaseRateChangePercentage = $results_json[Table][Security][0][BaseRateChangePercentage];
$BaseRate = $results_json[Table][Security][0][BaseRate];
$Rate=$results_json[Table][Security][0][Rate];
$BaseRateChange=$results_json[Table][Security][0][BaseRateChange];
$DailyHighestRate=$results_json[Table][Security][0][DailyHighestRate];
$DailyLowestRate=$results_json[Table][Security][0][DailyLowestRate];
$HebName=$results_json[Table][Security][0][HebName];
$EngName=$results_json[Table][Security][0][EngName];
$HebSymbol=$results_json[Table][Security][0][HebSymbol];
$EngSymbol=$results_json[Table][Security][0][EngSymbol];
$Exchange=$results_json[Table][Security][0][Exchange];
$Symbol=$results_json[Table][Security][0][Symbol];
$AllYearMaximumRate=$results_json[Table][Security][0][AllYearMaximumRate];
$AllYearMinimumRate=$results_json[Table][Security][0][AllYearMinimumRate];


/*$LastDealDate=$results_json[Table][Security][0][LastDealDate];
$DailyTurnover=$results_json[Table][Security][0][DailyTurnover];*/

               

?>



<!DOCTYPE html>
<html>
<head>
        <link href="quote/css/quote-en.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>
    
<div>
    <dl>
        <dt>BaseRateChangePercentage:</dt>
        <dd><?php  print_r($BaseRateChangePercentage); ?></dd>
        <dt>BaseRate:</dt>
        <dd><?php  print_r($BaseRate); ?></dd>
        <dt>Rate:</dt>
        <dd><?php  print_r($Rate); ?></dd>
        <dt>BaseRateChange:</dt>
        <dd><?php  print_r($BaseRateChange); ?></dd>    
		<dt>DailyHighestRate:</dt>
        <dd><?php  print_r($DailyHighestRate); ?></dd>
        <dt>DailyLowestRate:</dt>
        <dd><?php  print_r($DailyLowestRate); ?></dd>
        <dt>HebName:</dt>
        <dd><?php  print_r($HebName); ?></dd>
        <dt>EngName:</dt>
        <dd><?php  print_r($EngName); ?></dd>
        <dt>HebSymbol:</dt>
        <dd><?php  print_r($HebSymbol); ?></dd>
        <dt>EngSymbol:</dt>
        <dd><?php  print_r($EngSymbol); ?></dd>
        <dt>DailyExchange:</dt>
        <dd><?php  print_r($Exchange); ?></dd>
        <dt>Symbol:</dt>
        <dd><?php  print_r($Symbol); ?></dd>
        <dt>AllYearMaximumRate:</dt>
        <dd><?php  print_r($AllYearMaximumRate); ?></dd>  
        <dt>AllYearMinimumRate:</dt>
        <dd><?php  print_r($AllYearMinimumRate); ?></dd>
            
    </dl>
</div>  
      
</body>

</html>