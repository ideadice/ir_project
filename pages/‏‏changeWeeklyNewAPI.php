<?php
#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";


#Connect to DB
include "/code/mysql/database.php";


/*$percentChangeLIVE = $_POST['percentChangeLIVE'];
$openLIVE = $_POST['openLIVE'];
$priceLIVE=$_POST['priceLIVE'];
$volumeLIVE=$_POST['volumeLIVE'];
$changeLIVE=$_POST['changeLIVE'];*/



/*     NEW CODE   03.08.2018      */

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


/*print the values of the array*/
echo "<br>"."BaseRate value: ".$results_json[Table][Security][0][BaseRate];
echo "<br>"."AllYearMaximumRate value: ".$results_json[Table][Security][0][AllYearMaximumRate];

$percentChangeLIVE = $results_json[Table][Security][0][BaseRateChangePercentage];
$openLIVE = $results_json[Table][Security][0][BaseRate];
$priceLIVE=$results_json[Table][Security][0][Rate];
$volumeLIVE=$results_json[Table][Security][0][DailyTurnover];
$changeLIVE=$results_json[Table][Security][0][BaseRateChange];


echo "<br>BaseRateChangePercentage: ".$percentChangeLIVE;
echo "<br>BaseRate: ".$openLIVE;
echo "<br>Rate: ".$priceLIVE;
echo "<br>DailyTurnover: ".$volumeLIVE;
echo "<br>BaseRateChange: ".$changeLIVE;

/*    END NEW CODE  03.08.2018  */



#Run on all the emails & create new mailgun objects
$objArr = [];
$res = [];
$i=0;


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$volumeLIVES=number_format($volumeLIVE);

$percentChangeLIVES=$percentChangeLIVE."%";


/*echo "<br/>price: ".$priceLIVE;
echo "<br/>change: ".$changeLIVE;
echo "<br/>Change%: ".$percentChangeLIVE;
echo "<br/>volume%: ".$volumeLIVES;
echo "<br/>open: ".$openLIVE;*/


if($percentChangeLIVE<0){
    
    $percentChangeLIVEPositive=$percentChangeLIVE*(-1);//if the change value negative change to positive.
    $percentChangeLIVENegative=$percentChangeLIVE;
    //$percentChangeLIVEM=$percentChangeLIVE*(-1);//for the position of the minus
   // $percentLIVEStr="-".$percentChangeLIVEM."%";
}
else {
    $percentChangeLIVEPositive=$percentChangeLIVE;
    $percentChangeLIVENegative=($percentChangeLIVE*(-1));//the change value positive.
}

$date=date("d/m/Y");
$time=date("h:i:sa");

//chack if have a different between the last time of change percent.
$sqlChange="select data15col from data15 where id=1 and data15col<>".$percentChangeLIVE;
$resultHaveChange=$conn->query($sqlChange);
echo "<br/>number of rows: ".$resultHaveChange->num_rows."<br/>";

//***START if have change
if($resultHaveChange->num_rows > 0  && $percentChangeLIVE<>0 &&  $percentChangeLIVE<50 &&  $percentChangeLIVE>-50){
    
       
    $sqlSelect="SELECT emailID,privateName,lastName,changingPercent,sendToEmail FROM users where PercentChangingOfStockPrice=1 and (changingPercent<=".$percentChangeLIVEPositive.") and sendToEmail=0";
        
        //**START check if have users that want to know about the change.
        $resultChange = $conn->query($sqlSelect);
        //if ($conn->query($sqlSelect) === TRUE) {
        
        if ($resultChange->num_rows > 0) {
        
            
            while($row = $resultChange->fetch_assoc()){
          
          
            
         
            #Receiver full name for email content
            echo" <br/>!" . $row["lastName"] . " " . $row["privateName"] ;
            $userName=" <br/>" . $row["lastName"] . " " . $row["privateName"] ;
            $changingPercent=$row["changingPercent"]."%";
            $htmlBodyPosts1=<<<EOT
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
                  <title>Daily share Update</title>
                  
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
                              .DICE-WEEK-td-none{
                                  padding: 0 !important;
                                   display: none;
                              }
                              .DICE-WEEK-td{
                                  padding: 0 !important;
                                   display: block;
                              }
                             .DICE-WEEK-td:nth-child(odd) {
                                   background: #f4f4f4!important;
                              }
                             .DICE-WEEK-td:nth-child(even) {
                                   background: #fff!important;
                              }
                              .DICE-WEEK-td2{
                                  padding: 0 !important;
                                   display: block;
                              }
                             .DICE-WEEK-td2:nth-child(odd) {
                                   background: #fff!important;
                              }
                             .DICE-WEEK-td2:nth-child(even) {
                                   background: #f4f4f4!important;
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
                
                
                <div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling: change in share price</div>
                <br><br>
                
                <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                  Hi $userName
                  <br><br>
                  You chose to get updates when the price change go above and below $changingPercent <br>
                  Check out the changes for the Delek Drilling share
                  <br><br>
                 <table class="DICE-WEEK" border="1" cellspacing="0" cellpadding="0" style="width: 100%; border:outset #fdfdfd 1.0pt">
                      <tbody>
                        <tr>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">% Change</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" class="DICE-WEEK-td" style="text-align:center"><b><span style="color:black">Change</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Last Price</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">% Change: </span><span  style="color:black">$percentChangeLIVES<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Change: </span><span  style="color:black">$changeLIVE<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Price: </span><span style="color:black">$priceLIVE<u></u><u></u></span></p>
                          </td>
                        </tr>
                       
                        <tr>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Volume</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Today's Open</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Symbol</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><span style="color:black">$volumeLIVES<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Today's Open: </span><span style="color:black">$openLIVE<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Symbol: </span><span style="color:black">DEDR-L<u></u><u></u></span></p>
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
                            Delek Drilling, Abba Eban 19, Herzelia Pituh
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
            
            
            
            #New Mailgun object
            $objArr[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');
            
            #Send Mail
            $res[$i] = $objArr[$i]->sendMessage($domain, array(
                'from'    => 'postmaster@irwebsites.co.il',
                'to'      => $row["emailID"],
                'subject' => 'Delek Drilling: change in share price-'.$date.'-'.$time,
                'html'    => $htmlBodyPosts1
            ));
            
            
            echo "<br/>Mail sent Successfully !";
            
            $i++;
            
            $sqlUpdateSendToMail="update users set sendToEmail=1 where emailID='".$row["emailID"]."'";
            //START check of update query
            
            
            if ($conn->query($sqlUpdateSendToMail) === TRUE) {
                echo "<br>update email successfully";
            } else {
                echo "<br>Error:update the send" . $sqlUpdateSendToMail . "<br>" . $conn->error;
            }
            //END check of update query
            
            
            }
        }
                    
         else {
            echo "<br>select users details failed: " . $sqlSelect . "<br>" . $conn->error;
        }
        
        //**END check if have users that want to know about the change.
        
        //START check of update query
        $sql="UPDATE data15 SET data15col=".$percentChangeLIVE."WHERE id=1";
        
        if ($conn->query($sql) === TRUE) {
            echo "<br>update reports successfully";
        } else {
            echo "Error:Update query failed " . $sql . "<br>" . $conn->error;
        }
        //END check of update query

       
          
}
else {
    echo "<br/>No have change of percent " . $sqlChange . "<br>" . $conn->error;
}
//****END if have change


?>