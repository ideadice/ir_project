<?php


#Connect to DB
include "/code/mysql/database.php";

#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";

// Read JSON file
$jsonPosts = file_get_contents('http://ir.delekdrilling.co.il/wp-json/wp/v2/posts/');
$json = file_get_contents('http://ir.delekdrilling.co.il/wp-json/wp/v2/reports/');
$jsonPresentation = file_get_contents('http://ir.delekdrilling.co.il/wp-json/wp/v2/presentations/');
$jsonFinancial_reports = file_get_contents('http://ir.delekdrilling.co.il/wp-json/wp/v2/financial_reports/');


//Decode JSON
$json_data = json_decode($json,true);
$json_data_posts = json_decode($jsonPosts,true);
$json_data_presentation = json_decode($jsonPresentation,true);
$json_data_financial_reports = json_decode($jsonFinancial_reports,true);
//END decode JSON

$lengthPostsJSON=sizeof($json_data_posts);//get the length of json posts array.
$length = sizeof($json_data);//get the length of json report array.
$lengthPresentationJSON = sizeof($json_data_presentation);//get the length of json report array.
$lengthFinancial_reports=sizeof($json_data_financial_reports);

echo "the length is:".$length;
echo "<br/>the posts length is:".$lengthPostsJSON;
echo "<br/>the presentation length is:".$lengthPresentationJSON;
echo "<br/>the financial_reports length is:".$lengthFinancial_reports;


//****Print on the screen the results of the json file for tests***
echo '<table style="border: 1px solid black;">';
echo '<tr>';
echo '<th>id</th>';
echo '<th>date</th>';
echo '<th>title</th>';
echo '<th>link</th>';
echo '</tr>';

for($j=0;$j<$length;$j++)
{
    echo '<tr>';
    for($i=0;$i<1;$i++)
    {
        echo '<td style="border: 1px solid black;">'.$json_data[$j]["id"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data[$j]["date_gmt"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data[$j]["title"]["rendered"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data[$j]["link"].'</td>';
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


// Create database
$sql = "SELECT * FROM postsUpdates";

$result = $conn->query($sql);

$a=array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($a,$row);
  
        echo "<br> id: ". $row["idpost"]. " - idmax: ". $row["idmax"]. " " . $row["datemax"] . "<br>";       
    }
} else {
    echo "0 results";
}


//echo "<br/>the count:".count($a)."<br/>";
//print_r($a);

//sort between the kind of posts details 
for($k=0;$k<count($a);$k++){
    $check=$a[$k]["idpost"];
    switch ($check) {
        case "posts":
            $p=$k;
            break;
        case "reports":
            $r=$k;
            break;
        case "presentation":
            $pres=$k;
            break;
        case "financialreports":
            $f=$k;
            break;
           
        default:
            echo "no have id";
    }
    
}
//END sort betwenn the kind of posts details 
      

     //echo "<br/>".$r;
     //echo "<br/>".$p."<br/>";

     //Reports variable
     $repMaxID=$a[$r]["idmax"];
     $repDateMax=$a[$r]["datemax"];
     $cnt=0;       
    $maxTODB=$repMaxID;
    //END Reports variable
    

    
    //!!!*****print test******
   $str=$json_data[0]["title"]["rendered"];
   // $str="hfghfgh543345..";
    //echo "the string is:".$str."<br/>";
    
    for($s=0;$s<$length;$s++) 
    {
      
        if((preg_match("/\p{Hebrew}/u",$json_data[$s]["title"]["rendered"]))==0){
            echo "<br/>id.".$json_data[$s]["id"];
        }  
    }
     
    $numberREs=preg_match("/\p{Hebrew}/u", $str);
     echo "<br/>the number is:".$numberREs;
    
    if((preg_match("/\p{Hebrew}/u", $str))==1){
    echo "<br/>Have hebrew letters :<br/>";
    }
    else  if((preg_match("/\p{Hebrew}/u", $str))==0) echo "NO have hebrew letters!!!!<br/>";
   
    //echo $str;
    //settype($str,"string");
   if(!(preg_match('/[à-ú]/',$str))){
   //if (ctype_alnum($str)) {
    //if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $str)){
   
        echo 'Good,no heb letters <br/>'.$str;
        echo $json_data[2]["id"]."<br/";
    }
    else {
        
        echo "****have heb letters***<br/";
        echo $json_data[2]["id"]."<br/";
    }
    
    echo "<br/>the date max is:".$repDateMax;
    echo "<br/>the id max is:".$repMaxID;
    
    //!!!***** END print test******
    
    
    
    
    $indexArr=array();//for the index of new reports.
    
    //Choosen the new REPORTS,consider in english title,id and dates!!
    for($i=0;$i<$length;$i++){
        $str=$json_data[$i]["title"]["rendered"];
        $checkNumber=preg_match("/\p{Hebrew}/u", $str);
        if($json_data[$i]["id"]>$repMaxID && $repDateMax<$json_data[$i]["date_gmt"] && $checkNumber==0 )
        {            
            array_push($indexArr,$i);//get the index of the new posts.
            if($json_data[$i]["id"]>$maxTODB){//find the max id and date for the next time.
                $maxTODB=$json_data[$i]["id"]; 
                $dateToDB=$json_data[$i]["date_gmt"];
            }
      
            $cnt=$cnt+1;     
        }
    }
    //END choosen the new REPORTS,consider in english title,id and dates!!
    
    echo "<br/>the cnt is:".$cnt;

    echo "<br/>the length of array is:".count($indexArr);
    
    //echo "<br/> the date to db :".$dateToDB."<br/>";
    
    
    //print the array of the new reports for send email.
    $y=0;
    
    for($t=0;$t<count($indexArr);$t++){
        echo "<br/>index:".$indexArr[$y];
        echo "<br/>".$json_data[$indexArr[$y]]["id"];
        $date=$json_data[$indexArr[$y]]["date_gmt"];
        $date1=substr($date,0,10);
        $date1=substr($date1,5,2)."/".substr($date1,8,2)."/".substr($date1,0,4); 
        //str_replace("-","/",$date1);
        $time=substr($date,11,5);
        //$reportStr=$reportStr."Title: ".$json_data[$indexArr[$y]]["title"]["rendered"]."<br/>Date: ".$json_data[$indexArr[$y]]["date_gmt"]."<br/>Link:<a href='".$json_data[$indexArr[$y]]["link"]."'>".$json_data[$indexArr[$y]]["link"]."</a><br/><br/>";
        $reportStr=$reportStr."<br/><a href='".$json_data[$indexArr[$y]]["link"]."'>".$json_data[$indexArr[$y]]["title"]["rendered"]."</a><br/>".$date1." - ".$time."<br/>";
        echo "<a href='".$json_data[$indexArr[$y]]["link"]."'>Link</a>";
        
        $y++;
    }
    //END print the array of the new reports.
    
    echo "<br/>".$reportStr;
    

    
    //***start checks if have new posts and select the desire users
    if(count($indexArr)>0)
    {
       
        $sqlDetailsReports = "SELECT emailID,privateName,lastName,immediateReports FROM users WHERE immediateReports=1";
        
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
                
                  Check out Delek Drilling investor relations website updates
                  <br><br>

                        $reportStr
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
        $sql1 = "UPDATE postsUpdates SET idmax=".$maxTODB.",datemax='".$dateToDB."' WHERE idpost='reports'";
        
        if ($conn->query($sql1) === TRUE) {
            echo "update reports successfully";
        } else {
            echo "Error: " . $sql1 . "<br>" . $conn->error;
        }
        //END UPDATE to DB the maxID and the dateMax for the next time.
        
    }
    
                                                                    //END Reports
    
    
    
                                                                  //****start posts**** 
    
    
    
    //Posts variable
    $postMaxID=$a[$p]["idmax"];
    $postDateMax=$a[$p]["datemax"];
    $cntpost=0;
    $postMaxTODB=$postMaxID;
    //END Posts variable
     
    $postIndexArr=array();//for the index of new posts.
    
    //Choosen the new POSTS,consider in english title,id and dates!!
    for($i=0;$i<$lengthPostsJSON;$i++){
        $strPOST=$json_data_posts[$i]["title"]["rendered"];
        $checkNumberPost=preg_match("/\p{Hebrew}/u", $strPOST);
        if($json_data_posts[$i]["id"]>$postMaxID && $postDateMax<$json_data_posts[$i]["date_gmt"] && $checkNumberPost==0 )
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
        
        
        
        $sqlDetailsPost = "SELECT emailID,privateName,lastName,news FROM users WHERE news=1";
        
        $resultPost = $conn->query($sqlDetailsPost);
        
        
        #Run on all the emails & create new mailgun objects
        $objArr = [];
        $res = [];
        $i=0;
        
        if ($resultPost->num_rows > 0) {
            while($row = $resultPost->fetch_assoc()){
                
                
                #Receiver full name for email content
                $userNamePost=" " . $row["lastName"] . " " . $row["privateName"] ;
                
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
                  Hi $userNamePost
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
        $sql2 = "UPDATE postsUpdates SET idmax=".$postMaxTODB.",datemax='".$postDateTODB."' WHERE idpost='posts'";
        
        if ($conn->query($sql2) === TRUE) {
            echo "update posts successfully";
        } else {
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }
        //END UPDATE to DB the maxID and the dateMax for the next time.
        
    }
    
    //***END checks if have new posts and select the desire users
    
   
                                                           //****end posts**** 
    
    
    
                                                         //****start presentation**** 
    
    //Presentation variable
    $presMaxID=$a[$pres]["idmax"];
    $presDateMax=$a[$pres]["datemax"];
    $cntpres=0;
    $presMaxTODB=$presMaxID;
    //END Presentation variable
    
    $str1=$json_data_presentation[2]["title"]["rendered"];
   
    if(preg_match("/\p{Hebrew}/u",$str1)==1){
        echo "<br/>id.hebrew".$json_data_presentation[2]["id"]."  the title is:".$json_data_presentation[2]["title"]["rendered"];
    }
    else echo "<br/>id.no hebrew".$json_data_presentation[2]["id"]."  the title is:".$json_data_presentation[2]["title"]["rendered"];
    
    
    
    $presIndexArr=array();//for the index of new posts.
    
    //Choosen the new PRESENTATION,consider in english title,id and dates!!
    for($h=0;$h<$lengthPresentationJSON;$h++){
        
        $strPres=$json_data_presentation[$h]["title"]["rendered"];
        $checkNumberPres=preg_match("/\p{Hebrew}/u", $strPres);
        
        if($presDateMax<$json_data_presentation[$h]["date_gmt"] && $checkNumberPres==0 && $json_data_presentation[$h]["id"]>$presMaxID )
        {
            array_push($presIndexArr,$h);//get the index of the new posts.
            if( $json_data_presentation[$h]["id"]>$presMaxTODB)
            {//find the max id and date for the next time.
                $presMaxTODB=$json_data_presentation[$h]["id"];
                $presDateTODB= $json_data_presentation[$h]["date_gmt"];
            }
            
            $cntpres=$cntres+1;
        }
    }
    //END choosen the new PRESENTATION,consider in english title,id and dates!!
    
 
    
    
    echo "<br/>the length of presentation index array is:".count($presIndexArr);
    
    
    //print the array of the new reports for send email.
    $y=0;
    $cnt2=1;
    for($t=0;$t<count($presIndexArr);$t++){
        echo "<br/>index pres:".$presIndexArr[$y];
        echo "<br/>".$json_data_presentation[$presIndexArr[$y]]["id"];
       // $presentationStr=$presentationStr."Title: ".$json_data_presentation[$presIndexArr[$y]]["title"]["rendered"]."<br/>Date: ".$json_data_presentation[$presIndexArr[$y]]["date_gmt"]."<br/>Link:<a href='".$json_data_presentation[$presIndexArr[$y]]["link"]."'>".$json_data_presentation[$presIndexArr[$y]]["link"]."</a><br/><br/>";
        
        $datepres=$json_data_presentation[$presIndexArr[$y]]["date_gmt"];
        $datePres1=substr($datepres,0,10);        
        $datePres1=substr($datePres1,5,2)."/".substr($datePres1,8,2)."/".substr($datePres1,0,4);  
        //str_replace("-","/",$datePres1);
        $timePres=substr($datepres,11,5);
        
        $presentationStr=$presentationStr."<br/><a href='".$json_data_presentation[$presIndexArr[$y]]["link"]."'>".$json_data_presentation[$presIndexArr[$y]]["title"]["rendered"]."</a><br/>".$datePres1." - ".$timePres."<br/>";
       
        echo "<a href='".$json_data_presentation[$presIndexArr[$y]]["link"]."'>Link</a>";
        $y++;
    }
    //END print the array of the new reports.
    
    echo "<br/>".$presentationStr;
 
    
    //***START checks if have new presentation and select the desire users
    
    if(count($presIndexArr)>0)
    {
        
        $sqlDetailsPres = "SELECT emailID,privateName,lastName,presentation FROM users WHERE presentation=1";
        
        $resultPres = $conn->query($sqlDetailsPres);
        
        
        #Run on all the emails & create new mailgun objects
        $objArrPres = [];
        $resPres = [];
        $pr=0;
        
        if ($resultPres->num_rows > 0) {
            while($row = $resultPres->fetch_assoc()){
                
                
                #Receiver full name for email content
                $userNamePres=" " . $row["lastName"] . " " . $row["privateName"] ;
                
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
                  Hi $userNamePres
                  <br><br>
                
                  Check out Delek Drilling investor relations website updates
                  <br><br>

                        $presentationStr
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
                $objArrPres[$i] = new Mailgun('key-1abc61ad099241246e85983d15c4ea02');
                
                #Send Mail
                $resPres[$i] = $objArrPres[$i]->sendMessage($domain, array(
                    'from'    => 'postmaster@irwebsites.co.il',
                    'to'      => $row["emailID"],
                    'subject' => 'Delek Drilling: Investor Relations Update',
                    'html'    => $htmlBodyPosts1
                ));
                
                echo "Mail sent Successfully !";
                
                $pr++;
                
            }
        }
        
        // UPDATE to DB the maxID and the dateMax for the next time if have new posts.
        $sql3 = "UPDATE postsUpdates SET idmax=".$presMaxTODB.",datemax='".$presDateTODB."' WHERE idpost='presentation'";
        
        if ($conn->query($sql3) === TRUE) {
            echo "<br/>update presentation successfully";
        } else {
            echo "Error: " . $sql3 . "<br>" . $conn->error;
        }
        //END UPDATE to DB the maxID and the dateMax for the next time.
        
    }
    //***END checks if have new presentation and select the desire users
    
                                                            //****END presentation**** 
    
    
    
    
                                                          //****start financial_reports****
    
    //financial_reports variable
    $finMaxID=$a[$f]["idmax"];
    $finDateMax=$a[$f]["datemax"];
    $cntfin=0;
    $finMaxTODB=$finMaxID;
    //END financial_reports variable
    
    

    $finIndexArr=array();//for the index of new posts.
    
    //Choosen the new financial_reports,consider in english title,id and dates!!
    for($i=0;$i<$lengthFinancial_reports;$i++){
        $strfin=$json_data_financial_reports[$i]["title"]["rendered"];
        $checkNumberfin=preg_match("/\p{Hebrew}/u", $strfin);
        if($json_data_financial_reports[$i]["id"]>$finMaxID && $finDateMax<$json_data_financial_reports[$i]["date_gmt"] && $checkNumberfin==0)
        {
            array_push($finIndexArr,$i);//get the index of the new posts.
            if( $json_data_financial_reports[$i]["id"]>$finMaxTODB){//find the max id and date for the next time.
                $finMaxTODB=$json_data_financial_reports[$i]["id"];
                $finDateTODB= $json_data_financial_reports[$i]["date_gmt"];
            }
            
            $cntfin=$cntfin+1;
        }
    }
    //END choosen the new financial_reports,consider in english title,id and dates!!
    
    
    
    
    echo "<br/>the length of financail reports index array is:".count($finIndexArr);
    
   
    //print the array of the new financial_reports for send email.
    $y=0;
    $cnt3=1;
    for($t=0;$t<count($finIndexArr);$t++){
        //echo "<br/>index fin:".$finIndexArr[$t];
        echo "<br/>timorrr".$json_data_financial_reports[$finIndexArr[$y]]["id"];
        //$financialStr=$financialStr."Title: ".$json_data_financial_reports[$finIndexArr[$y]]["title"]["rendered"]."<br/>Date: ".$json_data_financial_reports[$finIndexArr[$y]]["date_gmt"]."<br/>Link: ".$json_data_financial_reports[$finIndexArr[$y]]["link"]."<br/>";
       // $financialStr=$financialStr."Title: ".$json_data_financial_reports[$finIndexArr[$y]]["title"]["rendered"]."<br/>Date: ".$json_data_financial_reports[$finIndexArr[$y]]["date_gmt"]."<br/>Link:<a href='".$json_data_financial_reports[$finIndexArr[$y]]["link"]."'>".$json_data_financial_reports[$finIndexArr[$y]]["link"]."</a><br/><br/>"; 
       
        $dateFin=$json_data_financial_reports[$finIndexArr[$y]]["date_gmt"];
        $dateFin1=substr($dateFin,0,10);
        $dateFin1=substr($dateFin1,5,2)."/".substr($dateFin1,8,2)."/".substr($dateFin1,0,4);  //month,day,year     
       // str_replace("-","/",$dateFin1);
        $timeFin=substr($dateFin,11,5);
        
        $financialStr=$financialStr."<br/><a href='".$json_data_financial_reports[$finIndexArr[$y]]["link"]."'>".$json_data_financial_reports[$finIndexArr[$y]]["title"]["rendered"]."</a><br/>".$dateFin1." - ".$timeFin."<br/>";
        
        echo "<a href='".$json_data_financial_reports[$finIndexArr[$y]]["link"]."'>Link</a>";
        $y++;
    }
    //END print the array of the new reports.
    
    
    echo "<br/>".$financialStr;
    
    //***START checks if have new financial_reports and select the desire users
    
    if(count($finIndexArr)>0)
    {
        
        $sqlDetails = "SELECT emailID,privateName,lastName,financialReports FROM users WHERE financialReports=1";
        
        $result1 = $conn->query($sqlDetails);
        
        
        #Run on all the emails & create new mailgun objects
        $objArr = [];
        $res = [];
        $i=0;
        
        if ($result1->num_rows > 0) {
            while($row = $result1->fetch_assoc()){
                
                
                #Receiver full name for email content
                $userName="" . $row["lastName"] . " " . $row["privateName"];
                
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
                  Hi $userName
                  <br><br>
                
                  Check out Delek Drilling investor relations website updates
                  <br><br>

                        $financialStr
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
        
        //echo "<br/>The string financial: ".$financialStr;
        
        // UPDATE to DB the maxID and the dateMax for the next time if have new posts.
        $sql4 = "UPDATE postsUpdates SET idmax=".$finMaxTODB.",datemax='".$finDateTODB."' WHERE idpost='financialreports'";
        
        if ($conn->query($sql4) === TRUE) {
            echo "<br/>update financial_reports successfully";
        } else {
            echo "Error: " . $sql4 . "<br>" . $conn->error;
        }
        //END UPDATE to DB the maxID and the dateMax for the next time.
        
    }
    
    //***END checks if have new financial_reports and select the desire users
    
    //****END financial_reports**** 
    
    
    $conn->close();
    
?>





