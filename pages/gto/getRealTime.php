<?php
$htmlContent = file_get_contents("http://irwebsites.co.il/Investor_Relations/pages/gto/realtime.php");

$json_data_GetrealTime=json_decode($htmlContent,true);

print_r($json_data_GetrealTime);
















/*$DOM = new DOMDocument();
$DOM->loadHTML($htmlContent);


$Header = $DOM->getElementsByTagName('rate');
$val2 = $DOM->getElementsByTagName('BaseRateChange');


#$Header2 = $DOM->getElementsByTagName('table');
#$Detail = $DOM->getElementsByTagName('BaseRateChangePercentage');


//#Get header name of the table
foreach($Header as $NodeHeader)
{
    $aDataTableHeaderHTML[] = $NodeHeader->textContent;
    
}



foreach($val2 as $NodeHeader2)
{
    $arr2[] = $NodeHeader2->textContent;
    
}

#echo sizeof($aDataTableHeaderHTML);
#$val1 = $Header->textContent;

//print_r($val1);
print_r($aDataTableHeaderHTML[0]);
echo "<br>";

print_r($arr2[0]);

//print_r($aDataTableHeaderHTML2[0]);


die();*/



?>
