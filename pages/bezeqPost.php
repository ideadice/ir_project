<?php

#Connect to DB
//include "/code/mysql/database.php";


$servername = "212.111.42.168";
$username = "tvlad";
$password = "Vldt!896";
$dbname = "bezeq";

#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";

// Read JSON file
$jsonPosts = file_get_contents('http://ir.delekdrilling.co.il/wp-json/wp/v2/posts/');
//END Read JSON file

//Decode JSON
$json_data_posts = json_decode($jsonPosts,true);
//END decode JSON

$lengthPostsJSON=sizeof($json_data_posts);//get the length of json posts array.


echo "<br/>the posts length is:".$lengthPostsJSON;


//****Print on the screen the results of the json file for tests***
echo '<table style="border: 1px solid black;">';
echo '<tr>';
echo '<th>id</th>';
echo '<th>date</th>';
echo '<th>title</th>';
echo '<th>link</th>';
echo '</tr>';

for($j=0;$j<$lengthPostsJSON;$j++)
{
    echo '<tr>';
    for($i=0;$i<1;$i++)
    {
        echo '<td style="border: 1px solid black;">'.$json_data_posts[$j]["id"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data_posts[$j]["date_gmt"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data_posts[$j]["title"]["rendered"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data_posts[$j]["link"].'</td>';
    }
    echo '</tr>';
}
echo '</table>';

//****END print on the screen the results of the json file for tests***


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT * FROM newPosts";

$result = $conn->query($sql);

$a=array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $idMaxDB=$row["idmax"];
        $dateMaxDB=$row["datemax"];
        
        echo "<br> id: ". $row["idpost"]. " - idmax: ". $row["idmax"]. " " . $row["datemax"] . "<br>";
    }
} else {
    echo "0 results";
}


//Posts variable
$postMaxID=$idMaxDB;
$postDateMax=$dateMaxDB;
$cntpost=0;
$postMaxTODB=$postMaxID;
//END Posts variable

$postIndexArr=array();//for the index of new posts.

//Choosen the new POSTS,consider id and dates!!
for($i=0;$i<$lengthPostsJSON;$i++){
    if($json_data_posts[$i]["id"]>$postMaxID && $postDateMax<$json_data_posts[$i]["date_gmt"])
    {
        array_push($postIndexArr,$i);//get the index of the new posts.
        if($json_data_posts[$i]["id"]>$postMaxTODB){//find the max id and date for the next time.
            $postMaxTODB=$json_data_posts[$i]["id"];
            $postDateTODB=$json_data_posts[$i]["date_gmt"];
        }
        
        $cntpost=$cntpost+1;
    }
}
//END choosen the new POSTS,consider in english title,id and dates!!


echo "<br/>the length of cntpost:".$cntpost;
echo "<br/>the length of post index array is:".count($postIndexArr);

//print the array of the new reports for send email.
$y=0;

for($t=0;$t<count($postIndexArr);$t++){
    echo "<br/>index post:".$postIndexArr[$y];
    echo "<br/>".$json_data_posts[$postIndexArr[$y]]["id"];
    // $postStr=$postStr."Title: ".$json_data_posts[$postIndexArr[$y]]["title"]["rendered"]."<br/>Date: ".$json_data_posts[$postIndexArr[$y]]["date_gmt"]."<br/>Link:<a href='".$json_data_posts[$postIndexArr[$y]]["link"]."'>".$json_data_posts[$postIndexArr[$y]]["link"]."</a><br/><br/>";
    
    $datepost=$json_data_posts[$postIndexArr[$y]]["date_gmt"];
    $datePost1=substr($datepost,0,10);
    $datePost1=substr($datePost1,5,2)."/".substr($datePost1,8,2)."/".substr($datePost1,0,4);
    //str_replace("-","/",$datePost1);
    $timePost=substr($datepost,11,5);
    
    $postStr=$postStr."<br/><a href='".$json_data_posts[$postIndexArr[$y]]["link"]."'>".$json_data_posts[$postIndexArr[$y]]["title"]["rendered"]."</a><br/>".$datePost1." - ".$timePost."<br/>";
    echo "<a href='".$json_data_posts[$postIndexArr[$y]]["link"]."'>Link</a>";
    $y++;
}
//END print the array of the new reports.



echo "<br/>".$postStr;



//***START checks if have new posts and select the desire users

if(count($postIndexArr)>0)
{
    
    
    //********change!!
    $sqlDetailsPost = "SELECT emailID FROM user";
    
    $resultPost = $conn->query($sqlDetailsPost);
    
    
    #Run on all the emails & create new mailgun objects
    $objArr = [];
    $res = [];
    $i=0;
    
    if ($resultPost->num_rows > 0) {
        while($row = $resultPost->fetch_assoc()){
            
            
            #Receiver full name for email content
            //$userNamePost=" " . $row["lastName"] . " " . $row["privateName"] ;
            
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
                  <title>Posts Update</title>
                  
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
                               
                               
                <div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling: Investor Relations Updates</div>
                <br><br>
                
                <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                  Hi name!!
                  <br><br>
                  
                  Check out Delek Drilling investor relations website updates
                  <br><br>
                  
                        $postStr
                        <!--Posts Content here-->
                        <!--Posts Content here-->
                        <!--Posts Content here-->
                        <!--Posts Content here-->
                        
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
                Powered by <a href="Unsubscribe.html" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Dice</a>
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
                'subject' => 'Delek Drilling: Investor Relations Update',
                'html'    => $htmlBodyPosts1
            ));
            
            echo "Mail sent Successfully !";
            
            $i++;
            
        }
    }
    
    
    
    
    // UPDATE to DB the maxID and the dateMax for the next time if have new posts.
    $sql2 = "UPDATE newPosts SET idmax=".$postMaxTODB.",datemax='".$postDateTODB;
    
    if ($conn->query($sql2) === TRUE) {
        echo "update posts successfully";
    } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }
    //END UPDATE to DB the maxID and the dateMax for the next time.
    
}

//***END checks if have new posts and select the desire users

























?>