<?php

#Connect to DB
include "/code/mysql/database.php";

#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";

// company number and name 475020 - DELEK Company
$companyName="DEDR.L";
$companyNumberOfStock=475020;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*SELECT The last date post*/
$sql = "SELECT * FROM postsUpdates WHERE idpost='".$companyNumberOfStock."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       $postLastDate=$row["datemax"];//set the last date
        echo "<br> id: ". $row["idpost"]. " - idmax: ". $row["idmax"]. " " . $row["datemax"] . "<br>";       
    }
} else {
    echo "0 results";
}

/*START Read the xml file*/
$url = "../data/updatePosts/delekdrilling_rss.xml"; // xmld.xml contains above data
$feeds = file_get_contents($url);
$rss = simplexml_load_string($feeds);

$items = [];

foreach($rss->channel->item as $entry) {
    $image = '';
    $image = 'N/A';
    $description = 'N/A';
    /*foreach ($entry->children('media', true) as $k => $v) {
        $attributes = $v->attributes();

        if ($k == 'content') {
            if (property_exists($attributes, 'url')) {
                $image = $attributes->url;
            }
        }
        if ($k == 'description') {
            $description = $v;
        }
    }*/
 
    $items[] = [
        'link' => $entry->link,
        'title' => $entry->title,
        'image' => $image,
        'description' => $description,
        'pubDate' => $entry->pubDate,
    ];
}
/*END Read the xml file*/


/*echo $items[0]['link']."<br>";
echo $items[0]['title']."<br>";
echo $items[0]['pubDate']."<br>";*/
//print_r($items);


$length=sizeof($items);//get the length of items array
$indexArr=array();//for the index of new reports.
echo "<br>size of items is : ".$length."<br>";

//Choosen the new REPORTS considor in dates!!
$dateToDB=$postLastDate;//initial the last date post
for($i=0;$i<$length;$i++){
    
    $date=date_create($items[$i]['pubDate'],timezone_open("Asia/Tel_Aviv"));//change format of date and time
    $dateString=date_format($date,"Y/m/d H:i:sa");
    if($postLastDate<$dateString)//get the max date
    {            
        array_push($indexArr,$i);//set the index of the new posts.
        if($dateString>$dateToDB){//find the max date for the next time.
           $dateToDB=$dateString;
        }                   
    }
}
echo "<br>the max date : ".$dateToDB."<br>";
$lengthIndex=sizeof($indexArr);
$reportStr="";
//print the index of items
for($j=0;$j<$lengthIndex;$j++){

    echo "<br>".$j." : ".$indexArr[$j];
    echo "<br>".$items[$indexArr[$j]]['link']."<br>";
    echo "<br>".$items[$indexArr[$j]]['title']."<br>";
    echo "<br>".$items[$indexArr[$j]]['pubDate']."<br>";

    $reportStr=$reportStr."<br/><a href='".$items[$indexArr[$j]]['link']."'>".$items[$indexArr[$j]]['title']."</a><br/>".$items[$indexArr[$j]]['pubDate']."<br/>";
    echo "<a href='".$items[$indexArr[$j]]['link']."'>Link</a>";
}


echo "<br> repStr is : ".$reportStr;
echo "<br> count indexArr is : ".count($indexArr);

 
   //***start checks if have new posts and select the desire users
   if(count($indexArr)>0)
   {
      
       $sqlDetailsReports = "SELECT emailID,privateName,lastName,immediateReports FROM users WHERE NewsUpdate=1";
       
       $resultReports = $conn->query($sqlDetailsReports);
       
       
       #Run on all the emails & create new mailgun objects
       $objArr = [];
       $res = [];
       $i=0;
       
       if ($resultReports->num_rows > 0) {
           while($row = $resultReports->fetch_assoc()){
               
               
               #Receiver full name for email content
               $userNameReports=" " . $row["lastName"] . " " . $row["privateName"] ;
               
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
                 Hi $userNameReports
                 <br><br>
               
                 Delek Drilling investor relations website updates
                 <br><br>

                       $reportStr
                       <!--Posts Content here-->
                       <!--Posts Content here-->
                       <!--Posts Content here-->
                       <!--Posts Content here-->
                   
               <br><br>
               <div align="left" style="margin:0 auto; width:100%; text-align:left; font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;">              
               Thank you, <br>
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
                   'from'    => 'delekdrilling@irwebsites.co.il',
                   'to'      => $row["emailID"],
                   'subject' => 'Delek Drilling: Investor Relations Update',
                   'html'    => $htmlBodyPosts1
               ));
               
               echo "Mail sent Successfully !";
               
               $i++;
               
           }
       }
          

        /*START UPDATE QUERY*/
        $sqlInsert="UPDATE postsUpdates set datemax='".$dateToDB."' WHERE idpost='".$companyNumberOfStock."'";

        if ($conn->query($sqlInsert) === TRUE) {
            echo "<br><br> Update Query Successfully <br>";
        } else {
            echo "<br>Error:update the send  " . $sqlInsert . "<br>" . $conn->error."<br>";
        }
        /*END UPDATE QUERY*/


      }


  $conn->close();
?>