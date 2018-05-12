<?php
#Weekly Mail server side function

#Connect to DB
include "/code/mysql/database.php";

echo "*PHP* -Weekly Mail send - start";

$todayDate=date("d-m-Y");
echo "<br> Today date is: ". $todayDate . "<br>";

#Get POST data from serverjs -> sendWeeklyEmail()

$ThursdayOpeningPrice = $_POST['ThursdayOpeningPrice'];
$ThursdayLastTrade = $_POST['ThursdayLastTrade'];
$ThursdayPreviousClose = $_POST['ThursdayPreviousClose'];
$ThursdayDayHigh = $_POST['ThursdayDayHigh'];
$ThursdayDayLow = $_POST['ThursdayDayLow'];
$ThursdayVolume = $_POST['ThursdayVolume'];
$WednesdayOpeningPrice = $_POST['WednesdayOpeningPrice'];
$WednesdayLastTrade = $_POST['WednesdayLastTrade'];
$WednesdayPreviousClose = $_POST['WednesdayPreviousClose'];
$WednesdayDayHigh = $_POST['WednesdayDayHigh'];
$WednesdayDayLow = $_POST['WednesdayDayLow'];
$WednesdayVolume = $_POST['WednesdayVolume'];
$TuesdayOpeningPrice = $_POST['TuesdayOpeningPrice'];
$TuesdayLastTrade = $_POST['TuesdayLastTrade'];
$TuesdayPreviousClose = $_POST['TuesdayPreviousClose'];
$TuesdayDayHigh = $_POST['TuesdayDayHigh'];
$TuesdayDayLow = $_POST['TuesdayDayLow'];
$TuesdayVolume = $_POST['TuesdayVolume'];
$MondayOpeningPrice = $_POST['MondayOpeningPrice'];
$MondayLastTrade = $_POST['MondayLastTrade'];
$MondayPreviousClose = $_POST['MondayPreviousClose'];
$MondayDayHigh = $_POST['MondayDayHigh'];
$MondayDayLow = $_POST['MondayDayLow'];
$MondayVolume = $_POST['MondayVolume'];
$SundayOpeningPrice = $_POST['SundayOpeningPrice'];
$SundayLastTrade = $_POST['SundayLastTrade'];
$SundayPreviousClose = $_POST['SundayPreviousClose'];
$SundayDayHigh = $_POST['SundayDayHigh'];
$SundayDayLow = $_POST['SundayDayLow'];
$SundayVolume = $_POST['SundayVolume'];


#number format - digits after point, big numbers seperation
if($SundayPreviousClose!="Holiday"){
    $SundayVolume=number_format($SundayVolume);
    $SundayPreviousClose=number_format($SundayPreviousClose,2);
}
if($MondayPreviousClose!="Holiday"){
    $MondayVolume=number_format($MondayVolume);
    $MondayPreviousClose=number_format($MondayPreviousClose,2);
}
if($TuesdayPreviousClose!="Holiday"){
    $TuesdayVolume=number_format($TuesdayVolume);
    $TuesdayPreviousClose=number_format($TuesdayPreviousClose,2);
}
if($WednesdayPreviousClose!="Holiday"){
    $WednesdayVolume=number_format($WednesdayVolume);
    $WednesdayPreviousClose=number_format($WednesdayPreviousClose,2);
}
if($ThursdayPreviousClose!="Holiday"){
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
                
                
                <div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling: weekly share summary</div>
                <br><br>
                
                <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                  Hi $userName
                  <br><br>
                
                  Check out the weekly summary for the Delek Drilling share
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
                            <p><b><span style="color:black;">Sunday</span></b><span style="color:black"><u></u><u></u></span></p>
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
                            <p><b><span style="color:black">Monday</span></b><span style="color:black"><u></u><u></u></span></p>
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
                            <p><b><span style="color:black">Tuesday</span></b><span style="color:black"><u></u><u></u></span></p>
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
                            <p><b><span style="color:black">Wednesday</span></b><span style="color:black"><u></u><u></u></span></p>
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
                            <p><b><span style="color:black">Thursday</span></b><span style="color:black"><u></u><u></u></span></p>
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
                We will be happy to provide you with more information, please <a href="http://ir.delekdrilling.co.il/contact-us/" style="text-decoration: underline; color: #999999; ">contact us</a>.  <br>
                <br>Thank you, <br>
                Delek Drilling IR Team.    
                </div>
                
                <br><br>
                            <br><br><div align="center" style="margin:0 auto; width:100%; text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;">
                            Delek Drilling, Abba Eban 19, Herzelia Pituah
                            <br>
                
                          <br> Don't like these emails? <a href="http://ir.delekdrilling.co.il/unsubscribe/" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">Unsubscribe</a>.
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
        
        
        #New Mailgun object
        $objArr[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');
        
        #Send Mail
        $res[$i] = $objArr[$i]->sendMessage($domain, array(
            'from'    => 'postmaster@irwebsites.co.il',
            'to'      => $row["emailID"],
            'subject' => 'Delek Drilling: Weekly summary - '.$todayDate,
            'html'    => $htmlBodyWeekly
        ));
        
        echo "Mail sent Successfully !";
        
        #increase index
        $i++;
    }
} else {
    echo "No results from database";
}




echo "*PHP* -Weekly Mail send - END";
?>