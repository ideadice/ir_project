<?php

#Daily Mail server side function

##  New Model:      Only this page is needed.
##                  Get needed values by json, save in the variables below            

#Connect to DB
include "/code/mysql/database.php";

echo "PHP -Daily Mail send - start";

##inits

#Flag for preventing emails on holidays. 0 - email will be sent, 1 - Holiday
$flag_red=0;


##Get data from API

#Get key
$jsonLogin=file_get_contents('http://irwebsites.co.il/Investor_Relations/pages/gto/login.php');
$json_data_Login=json_decode($jsonLogin,true);

#Work with Curl
$curl3 = curl_init();

curl_setopt_array($curl3, array(
    CURLOPT_PORT => "9005",
    CURLOPT_URL => "https://api.gto.co.il:9005/v2/json/market/table/simple?securities=475020&fields=Rate,BaseRateChange,BaseRateChangePercentage,DailyHighestRate,DailyLowestRate,BaseRate,DailyTurnover,AllYearMaximumRate,AllYearMinimumRate,OpenRate",
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

$responseJsonDaily = curl_exec($curl3);
curl_close($curl3);


##END - Get data from API

#Data to json
$resultsJsonDaily = json_decode($responseJsonDaily, true);

#Print the json as a test
echo "<br>"."Print r results array with pre:";
echo '<pre>'; print_r($resultsJsonDaily); echo '</pre>';
echo "<br>";


#Work with the json Data
if(($resultsJsonDaily['Table']['Security']['0']==NULL) or ($resultsJsonDaily['Table']['Security']['0']['OpenRate']==NULL and $resultsJsonDaily['Table']['Security']['0']['BaseRateChange']==NULL))
{
    #Detect holidays
    #Dont send email !!
    $flag_red=1;
}
else
{
    #Set the values from json data
    $priceE = $resultsJsonDaily['Table']['Security']['0']['Rate'];
    $changeE = $resultsJsonDaily['Table']['Security']['0']['BaseRateChange'];
    $pChangeE = $resultsJsonDaily['Table']['Security']['0']['BaseRateChangePercentage'];
    $volumeE = $resultsJsonDaily['Table']['Security']['0']['DailyTurnover'];
    $WeekHigh52E = $resultsJsonDaily['Table']['Security']['0']['AllYearMaximumRate'];
    $WeekLow52E = $resultsJsonDaily['Table']['Security']['0']['AllYearMinimumRate'];
    $dayhighE = $resultsJsonDaily['Table']['Security']['0']['DailyHighestRate'];
    $daylowE = $resultsJsonDaily['Table']['Security']['0']['DailyLowestRate'];
    $todaysopenE = $resultsJsonDaily['Table']['Security']['0']['OpenRate'];
    $previouscloseE = $resultsJsonDaily['Table']['Security']['0']['BaseRate'];
}

    #Value tests
    #echo "<br>"."Print new results val with pre:";
    #echo '<pre>'; print_r($resultsJsonDaily['Table']['Security']['0']); echo '</pre>';
    #echo "<br>";

    #init html body variable
    $htmlBody="init";
    
    #Select users from database
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT emailID, privateName, lastName FROM users WHERE dailyflag=1";
    $result = $conn->query($sql);

    #Use mailgun library installed on server
    require '/var/www/html/mailgun-php/vendor/autoload.php';
    use Mailgun\Mailgun;
    $domain = "irwebsites.co.il";
 

    // volume Separator
    $volumeES=number_format($volumeE);
    $pChangeES=$pChangeE."%";

    $todayDate=date("d-m-Y");
    echo "<br> Today date is: ". $todayDate . "<br>";
    
    #Run on all the emails & create new mailgun objects
    $objArr = [];
    $res = [];
    $i=0;

    if ($result->num_rows > 0) {
        #Go over the users
        while($row = $result->fetch_assoc()) {
            
                     
            #Debug info - destination email print
            echo "<br> email: ". $row["emailID"] . "<br>";
            
            #Receiver full name for email content
            $userName="" . $row["lastName"] . " " . $row["privateName"];

#START HTML Email contant - Using PHP POST variables
            
$htmlBody=<<<EOT
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
                
                <div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling: daily share summary</div>
                <br><br>
                
                <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                  Hi $userName
                  <br><br>
                
                  Check out the daily summary for the Delek Drilling share
                  <br><br>
                 <table class="DICE-WEEK" border="1" cellspacing="0" cellpadding="0" style="width: 100%; border:outset #fdfdfd 1.0pt">
                      <tbody>
                        <tr>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Last Price</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Volume</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" class="DICE-WEEK-td" style="text-align:center"><b><span style="color:black">Change</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">% Change</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Day High</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Last Price: </span><span  style="color:black">$priceE<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Volume: </span><span style="color:black">$volumeES<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Change: </span><span  style="color:black">$changeE<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">% Change: </span><span style="color:black">$pChangeES<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day High: </span><span style="color:black">$dayhighE<u></u><u></u></span></p>
                          </td>
                        </tr>
                       
                        <tr>
                        </tr>
                      </tbody>
                         <tbody>
                        <tr>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Day Low</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">52 Week High</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" class="DICE-WEEK-td" style="text-align:center"><b><span style="color:black">52 Week Low</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Today's Open</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                          <td valign="top" class="DICE-WEEK-td-none" style="border:inset #fdfdfd 1.0pt;background:#f4f4f4;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><b><span style="color:black">Previous Close</span></b><span style="color:black"><u></u><u></u></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td class="DICE-WEEK-td2" valign="top" style="border:inset #fdfdfd 1.0pt;background:#fff;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Day Low: </span><span  style="color:black">$daylowE<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td2" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">52 Week High: </span><span style="color:black">$WeekHigh52E<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td2" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">52 Week Low: </span><span  style="color:black">$WeekLow52E<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td2" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Today's Open: </span><span style="color:black">$todaysopenE<u></u><u></u></span></p>
                          </td>
                          <td class="DICE-WEEK-td2" valign="top" style="border:inset #fdfdfd 1.0pt;background:white;padding:1.5pt 1.5pt 1.5pt 1.5pt">
                            <p align="right" style="text-align:center"><span class="DICE-title-mobile">Previous Close: </span><span style="color:black">$previouscloseE<u></u><u></u></span></p>
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
            
            #Test html
            echo "<br><br><br>";
            echo $htmlBody;


            #New Mailgun object
            $objArr[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');
            
            #Send Mail
            #/*
            
            if($flag_red==0)
            {
            $res[$i] = $objArr[$i]->sendMessage($domain, array(
                'from'    => 'postmaster@irwebsites.co.il',
                'to'      => $row["emailID"],
                'subject' => 'Delek Drilling: Daily summary - '.$todayDate,
                'html'    => $htmlBody
            ));
            
            echo "Mail sent Successfully !";
            }
            else
            {
                echo "Mail not sent - Holiday !";
            }
            #increase index
            $i++;
            
        }
    } else {
        echo "No results from database";
    }


?>



