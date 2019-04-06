<?php
#Weekly Mail server side function

##  New Model:      Only this page is needed.
##                  Get needed values by json, save in the variables below    

#Connect to DB
include "/code/mysql/database.php";

echo "*PHP* -Weekly Mail send - start";

#START - Get data from API

$jsonPresentation=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
$json_data_presentation=json_decode($jsonPresentation,true);
$lengthPresentationJSON = sizeof($json_data_presentation);

function historicalFunction($shiftDate,$todayDate) {
    $curl = curl_init();
    
    #Dynamic API Path
    $path="https://api.gto.co.il:9005/v2/json/market/history?key=475020&fromDate=".$shiftDate."&toDate=".$todayDate;
    
    echo "Path:"."<br>";
    echo $path;
    echo "<br>";
    
    global $json_data_presentation;
    
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "9005",
        CURLOPT_URL => $path,
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
    
    return $response;
}


#Work with dates
#Format: 24062018
$todayDate=date("dmY");

#test dates
echo "Today date:"."<br>";
echo $todayDate;
echo "<br>";
#END test dates

$shiftValue="-4";
$shiftDate=date("dmY",strtotime($shiftValue.' days'));

#test shifted dates
echo "Shift date:"."<br>";
echo $shiftDate;
echo "<br>";
#END test dates


#Get data for each day of the week
#Send to function: shiftDate - Sunday, and todayDate - Thursday

$func_output=historicalFunction($shiftDate,$todayDate);

#Order the data of the last 5 days in json
#Json from 0 to 4 (5 days), first day is 0.
$resultsjson = json_decode($func_output, true);

#TEST Json - Print json data
echo "<br>"."Print r results array with pre:";
echo '<pre>'; print_r($resultsjson); echo '</pre>';
echo "<br>";
echo '<pre>'; print_r($resultsjson['History']['Entry']['0']['BaseRate']); echo '</pre>';
echo "<br>";

#END - Get data from API



#Get specific values by day
$exampleField=$resultsjson['History']['Entry']['0']['BaseRate'];


#Dates for holidays
#Cut date

$sundayDate=date("d-m-Y",strtotime("-4".' days'));
$mondayDate=date("d-m-Y",strtotime("-3".' days'));
$tuesdayDate=date("d-m-Y",strtotime("-2".' days'));
$wednesdayDate=date("d-m-Y",strtotime("-1".' days'));
$thursdayDate=date("d-m-Y");

echo "<br>";
echo "Week dates: ";
echo "<br>";
echo $sundayDate;
echo "<br>";
echo $mondayDate;
echo "<br>";
echo $tuesdayDate;
echo "<br>";
echo $wednesdayDate;
echo "<br>";
echo $thursdayDate;
echo "<br>";
echo "<br>";

$apiDate=substr($resultsjson['History']['Entry']['0']['Date'],0,10);
echo "<br>";
echo "Parted date: ";
print_r($apiDate);
echo "<br>";


#Dates from API - Used in the HTML Code
$apiDateSunday=substr($resultsjson['History']['Entry']['0']['Date'],0,10);
$apiDateMonday=substr($resultsjson['History']['Entry']['1']['Date'],0,10);
$apiDateTuesday=substr($resultsjson['History']['Entry']['2']['Date'],0,10);
$apiDateWednesday=substr($resultsjson['History']['Entry']['3']['Date'],0,10);
$apiDateThursday=substr($resultsjson['History']['Entry']['4']['Date'],0,10);

#Change date structure
if($apiDateSunday != null){
    $apiDateSunday = date("d-m-Y", strtotime($apiDateSunday));
}
else
{
    $apiDateSunday="-";
}
if($apiDateMonday != null){
    $apiDateMonday = date("d-m-Y", strtotime($apiDateMonday));
}
else
{
    $apiDateMonday="-";
}
if($apiDateTuesday != null){
    $apiDateTuesday = date("d-m-Y", strtotime($apiDateTuesday));
}
else
{
    $apiDateTuesday="-";
}
if($apiDateWednesday != null){
    $apiDateWednesday = date("d-m-Y", strtotime($apiDateWednesday));
}
else
{
    $apiDateWednesday="-";
}
if($apiDateThursday != null){
    $apiDateThursday = date("d-m-Y", strtotime($apiDateThursday));
}
else
{
    $apiDateThursday="-";
}

#Values
#Sunday
if($resultsjson['History']['Entry']['0']==NULL)
{
    #Holidays
    $SundayOpeningPrice = "-";
    $SundayLastTrade = "-";
    $SundayPreviousClose = "-";
    $SundayDayHigh = "-";
    $SundayDayLow = "-";
    $SundayVolume = "-";
}
else
{
    #Set the values from json data
    $SundayOpeningPrice = $resultsjson['History']['Entry']['0']['OpeningRate'];
    $SundayLastTrade = $resultsjson['History']['Entry']['0']['LockRate'];
    $SundayPreviousClose = $resultsjson['History']['Entry']['0']['BaseRate'];
    $SundayDayHigh = $resultsjson['History']['Entry']['0']['DailyHigh'];
    $SundayDayLow = $resultsjson['History']['Entry']['0']['DailyLow'];
    $SundayVolume = $resultsjson['History']['Entry']['0']['Turnover'];
}
#Monday
if($resultsjson['History']['Entry']['1']==NULL)
{
    #Holidays
    $MondayOpeningPrice = "-";
    $MondayLastTrade = "-";
    $MondayPreviousClose = "-";
    $MondayDayHigh = "-";
    $MondayDayLow = "-";
    $MondayVolume = "-";
}
else
{
    #Set the values from json data
    $MondayOpeningPrice = $resultsjson['History']['Entry']['1']['OpeningRate'];
    $MondayLastTrade = $resultsjson['History']['Entry']['1']['LockRate'];
    $MondayPreviousClose = $resultsjson['History']['Entry']['1']['BaseRate'];
    $MondayDayHigh = $resultsjson['History']['Entry']['1']['DailyHigh'];
    $MondayDayLow = $resultsjson['History']['Entry']['1']['DailyLow'];
    $MondayVolume = $resultsjson['History']['Entry']['1']['Turnover'];
}
#Tuesday
if($resultsjson['History']['Entry']['2']==NULL)
{
    #Holidays
    $TuesdayOpeningPrice = "-";
    $TuesdayLastTrade = "-";
    $TuesdayPreviousClose = "-";
    $TuesdayDayHigh = "-";
    $TuesdayDayLow = "-";
    $TuesdayVolume = "-";
}
else
{
    #Set the values from json data
    $TuesdayOpeningPrice = $resultsjson['History']['Entry']['2']['OpeningRate'];
    $TuesdayLastTrade = $resultsjson['History']['Entry']['2']['LockRate'];
    $TuesdayPreviousClose = $resultsjson['History']['Entry']['2']['BaseRate'];
    $TuesdayDayHigh = $resultsjson['History']['Entry']['2']['DailyHigh'];
    $TuesdayDayLow = $resultsjson['History']['Entry']['2']['DailyLow'];
    $TuesdayVolume = $resultsjson['History']['Entry']['2']['Turnover'];
}
#Wednesday
if($resultsjson['History']['Entry']['3']==NULL)
{
    #Holidays
    $WednesdayOpeningPrice = "-";
    $WednesdayLastTrade = "-";
    $WednesdayPreviousClose = "-";
    $WednesdayDayHigh = "-";
    $WednesdayDayLow = "-";
    $WednesdayVolume = "-";
}
else
{
    #Set the values from json data
    $WednesdayOpeningPrice = $resultsjson['History']['Entry']['3']['OpeningRate'];
    $WednesdayLastTrade = $resultsjson['History']['Entry']['3']['LockRate'];
    $WednesdayPreviousClose = $resultsjson['History']['Entry']['3']['BaseRate'];
    $WednesdayDayHigh = $resultsjson['History']['Entry']['3']['DailyHigh'];
    $WednesdayDayLow = $resultsjson['History']['Entry']['3']['DailyLow'];
    $WednesdayVolume = $resultsjson['History']['Entry']['3']['Turnover'];
}
#Thursday
if($resultsjson['History']['Entry']['4']==NULL)
{
    #Holidays
    $ThursdayOpeningPrice = "-";
    $ThursdayLastTrade = "-";
    $ThursdayPreviousClose = "-";
    $ThursdayDayHigh = "-";
    $ThursdayDayLow = "-";
    $ThursdayVolume = "-";
}
else
{
    #Set the values from json data
    $ThursdayOpeningPrice = $resultsjson['History']['Entry']['4']['OpeningRate'];
    $ThursdayLastTrade = $resultsjson['History']['Entry']['4']['LockRate'];
    $ThursdayPreviousClose = $resultsjson['History']['Entry']['4']['BaseRate'];
    $ThursdayDayHigh = $resultsjson['History']['Entry']['4']['DailyHigh'];
    $ThursdayDayLow = $resultsjson['History']['Entry']['4']['DailyLow'];
    $ThursdayVolume = $resultsjson['History']['Entry']['4']['Turnover'];
}


#$todayDate=date("d-m-Y");
#echo "<br> Today date is: ". $todayDate . "<br>";


#number format - digits after point, big numbers seperation
if($SundayPreviousClose!="-"){
    $SundayVolume=number_format($SundayVolume);
    $SundayPreviousClose=number_format($SundayPreviousClose,2);
}
if($MondayPreviousClose!="-"){
    $MondayVolume=number_format($MondayVolume);
    $MondayPreviousClose=number_format($MondayPreviousClose,2);
}
if($TuesdayPreviousClose!="-"){
    $TuesdayVolume=number_format($TuesdayVolume);
    $TuesdayPreviousClose=number_format($TuesdayPreviousClose,2);
}
if($WednesdayPreviousClose!="-"){
    $WednesdayVolume=number_format($WednesdayVolume);
    $WednesdayPreviousClose=number_format($WednesdayPreviousClose,2);
}
if($ThursdayPreviousClose!="-"){
    $ThursdayVolume=number_format($ThursdayVolume);
    $ThursdayPreviousClose=number_format($ThursdayPreviousClose,2);
}

echo "<br> ThursdayOpeningPrice: ". $ThursdayOpeningPrice . "<br>";

#init weekly html body variable
$htmlBodyWeekly="init";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT emailID, privateName, lastName FROM users WHERE weeklyflag=1";
$result = $conn->query($sql);

#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";

#Run on all the emails & create new mailgun objects
echo "<br> **DEBUG ** Before variables set ";
$objArr = [];
$res = [];
$i=0;


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        
        #Debug info - destination email print
        echo "<br> email: ". $row["emailID"] . "<br>";
        
        #Receiver full name for email content
        $userName="" . $row["lastName"] . " " . $row["privateName"];
        
        #START HTML Email contant - Using PHP POST variables
        
        $htmlBodyWeekly=<<<EOT
                <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" 
                 xmlns:v="urn:schemas-microsoft-com:vml"
                 xmlns:o="urn:schemas-microsoft-com:office:office">
                <head>
                  <!--[if gte mso 9]><xml>
                   <o:OfficeDocumentSettings>
                    <o:AllowPNG/>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                   </o:OfficeDocumentSettings>
                  </xml><![endif]-->
                  <!-- fix outlook zooming on 120 DPI windows devices -->
                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
                  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
                  <meta name="format-detection" content="date=no"> <!-- disable auto date linking in iOS 7-9 -->
                  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS 7-9 -->
                  <title>Weekly share Update</title>
                  
                  <style type="text/css">
                body {
                  margin: 0;
                  padding: 0;
                  -ms-text-size-adjust: 100%;
                  -webkit-text-size-adjust: 100%;
                }
                table {
                  border-spacing: 0;
                }
                table td {
                  border-collapse: collapse;
                }
                .ExternalClass {
                  width: 100%;
                }
                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                  line-height: 100%;
                }
                .ReadMsgBody {
                  width: 100%;
                  background-color: #ebebeb;
                }
                table {
                  mso-table-lspace: 0pt;
                  mso-table-rspace: 0pt;
                }
                img {
                  -ms-interpolation-mode: bicubic;
                }
                .yshortcuts a {
                  border-bottom: none !important;
                }
                @media screen and (max-width: 599px) {
                  .force-row,
                  .container {
                    width: 100% !important;
                    max-width: 100% !important;
                  }
                }
                @media screen and (max-width: 400px) {
                  .container-padding {
                    padding-left: 12px !important;
                    padding-right: 12px !important;
                  }
                }
                .ios-footer a {
                  color: #aaaaaa !important;
                  text-decoration: underline;
                }
                a[href^="x-apple-data-detectors:"],
                a[x-apple-data-detectors] {
                  color: inherit !important;
                  text-decoration: none !important;
                  font-size: inherit !important;
                  font-family: inherit !important;
                  font-weight: inherit !important;
                  line-height: inherit !important;
                }
                                .DICE-title-mobile {
                                   display:none;
                              }
                      @media only screen and (max-width: 700px) {
                           .DICE-title-mobile {
                                   font-weight: bold;
                                   display:inline;
                              }
                            }
                            @media only screen and (max-width: 700px) {
                              .DICE-WEEK{
                               width: 100% !important;
                                padding: 0 !important;
                              }
                              .DICE-WEEK-td{
                                  padding: 0 !important;
                                   display: block;
                              }
                              .DICE-WEEK-td-none{
                                  padding: 0 !important;
                                   display: none;
                              }
                            }
                </style>
                </head>
                
                <body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                
                <!-- 100% background wrapper (grey background) -->
                <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
                  <tr>
                    <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">
                
                      <br>
                
                      <!-- 600px container (white background) -->
                      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
                        <tr>
                <center><img src="http://ir.delekdrilling.co.il/wp-content/uploads/2017/05/logo.png"  align="middle"></center><br>
                          <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:0px;padding-right:0px">
                          
                          </td>
                        </tr>
                        <tr>
                          <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
                               <br>
                
                
                <div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling: Weekly Share Summary</div>
                <br><br>
                
                <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                  Hi, $userName
                  <br><br>
                
                  Check out the weekly summary for the Delek Drilling share:
                  <br><br>
                 <table class="DICE-WEEK" border="1" cellspacing="0" cellpadding="0" style="width: 100%; border:outset #fdfdfd 1.0pt">
                      <tbody>
                        <tr>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">&nbsp;</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Opening Price</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Last Trade</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" class="DICE-WEEK-td" style="text-align:center"><b><span style="color:black">Previous Close</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Day's High</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Day's Low</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Volume</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" style="border:inset #fdfdfd 1.0pt;text-align:center;background:#f9f9f9;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p><b><span style="color:black;">$apiDateSunday</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Opening Price: </span><span  style="color:black">$SundayOpeningPrice<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Trade: </span><span style="color:black">$SundayLastTrade<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Previous Close: </span><span  style="color:black">$SundayPreviousClose<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's High: </span><span style="color:black">$SundayDayHigh<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's Low: </span><span style="color:black">$SundayDayLow<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><span  style="color:black">$SundayVolume<u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" style="text-align:center;border:inset #fdfdfd 1.0pt;background:#f9f9f9;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p><b><span style="color:black">$apiDateMonday</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Opening Price: </span><span  style="color:black">$MondayOpeningPrice<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Trade: </span><span  style="color:black">$MondayLastTrade<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Previous Close: </span><span style="color:black">$MondayPreviousClose<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's High: </span><span  style="color:black">$MondayDayHigh<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's Low: </span><span style="color:black">$MondayDayLow<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><span style="color:black">$MondayVolume<u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" style="text-align:center;border:inset #fdfdfd 1.0pt;background:#f9f9f9;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p><b><span style="color:black">$apiDateTuesday</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Opening Price: </span><span style="color:black">$TuesdayOpeningPrice<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Trade: </span><span style="color:black">$TuesdayLastTrade<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Previous Close: </span><span style="color:black">$TuesdayPreviousClose<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's High: </span><span style="color:black">$TuesdayDayHigh<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's Low: </span><spanstyle="color:black">$TuesdayDayLow<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><span style="color:black">$TuesdayVolume<u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" style="text-align:center;border:inset #fdfdfd 1.0pt;background:#f9f9f9;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p><b><span style="color:black">$apiDateWednesday</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Opening Price: </span><spanstyle="color:black">$WednesdayOpeningPrice<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Trade: </span><spanstyle="color:black">$WednesdayLastTrade<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Previous Close: </span><span style="color:black">$WednesdayPreviousClose<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's High: </span><spanstyle="color:black">$WednesdayDayHigh<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's Low: </span><span style="color:black">$WednesdayDayLow<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><span style="color:black">$WednesdayVolume<u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" style="text-align:center;border:inset #fdfdfd 1.0pt;background:#f9f9f9;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p><b><span style="color:black">$apiDateThursday</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Opening Price: </span><spanstyle="color:black">$ThursdayOpeningPrice<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Trade: </span><spanstyle="color:black">$ThursdayLastTrade<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Previous Close: </span><span style="color:black">$ThursdayPreviousClose<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's High: </span><span style="color:black">$ThursdayDayHigh<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day's Low: </span><spanstyle="color:black">$ThursdayDayLow<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><spanstyle="color:black">$ThursdayVolume<u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <br><br>
                <div align="left" style="margin:0 auto; width:100%; text-align:left; font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;">
                Thank you, <br>
                Delek Drilling IR Team.    
                </div>
                
                <br><br>
                            <br><br><div align="center" style="margin:0 auto; width:100%; text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;">
                            Delek Drilling, Abba Eban 19, Herzelia Pituah
                            <br>
                
                          <br> Don't like these emails? <a href="https://www.delekdrilling.co.il/en/investor-relations/unsubscribe" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">Unsubscribe</a>.
                </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="container-padding footer-text" align="center" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;padding-left:24px;padding-right:24px">
                
                            </span><br><br>
                Powered by <a href="http://ideadice.co.il" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Dice</a>
                            <br>
                <br><br>
                          </td>
                        </tr>
                      </table>
                <!--/600px container -->
                
                
                    </td>
                  </tr>
                </table>
                <!--/100% background wrapper-->
                
                </body>
                </html>
EOT;
        
        #END HTML Email content
        
        
        #Test html
        echo "<br><br><br>";
        echo $htmlBodyWeekly;
        
        #New Mailgun object
        
        $objArr[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');
        
        #Send Mail

        $res[$i] = $objArr[$i]->sendMessage($domain, array(
            'from'    => 'delekdrilling@irwebsites.co.il',
            'to'      => $row["emailID"],
            'subject' => 'Delek Drilling: Weekly summary - '.$todayDate,
            'html'    => $htmlBodyWeekly
        ));
        
        echo " *** AFTER mail send";
        echo "Mail sent Successfully !";
        
        #increase index
        $i++;
        
    }
} else {
    echo "No results from database";
}

echo "*PHP* -Weekly Mail send - END";
?>