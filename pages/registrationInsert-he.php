
<?php


//new 07/02/18

#Use mailgun library installed on server

require '/var/www/html/mailgun-php/vendor/autoload.php';

use Mailgun\Mailgun;

$domain = "irwebsites.co.il";


#Connect to DB
include "/code/mysql/database.php";

echo "begin";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else echo "connection created successfully";


if( isset($_POST['submit']) )//Check if submit button clicked.
{
    //be sure to validate and clean your variables
    $email = htmlentities($_POST['email']);
    $inp1 = htmlentities($_POST['inp1']);
    $pName2 = htmlentities($_POST['pName2']);
    $cName = htmlentities($_POST['cName']);
    $country=htmlentities($_POST['sel1']);
    //$country= isset($_POST['sel1']);
    
    
    $option = isset($_POST['sel1']) ? $_POST['sel1'] : false;
    if ($option) {
        echo htmlentities($_POST['sel1'], ENT_QUOTES, "UTF-8");
    } else {
        echo "task option is required";
        exit;
    }
    
    
    
    //values of update stock.
    if(isset($_POST['dailySummaryStock'])==true){$dailySummaryStock=1;} else  {$dailySummaryStock=0;}
    if(isset($_POST['weeklySummaryStock'])==true){$weeklySummaryStock=1;} else {$weeklySummaryStock=0;}
    if(isset($_POST['stockThresholdAlert'])==true){$stockThresholdAlert=1;} else {$stockThresholdAlert=0;}
    
    $percent=0;
    //checks if the user want update of changee stock and get the desire number.
    if(isset($_POST['stockThresholdAlert']))
    { 
      $percent1 = htmlentities($_POST['percent']);
      
      $percent= substr($percent1,0,1);//slice the first character.
      if($percent=='%'){
          $percent=1;
      }
    }
    
    
    //values of update posts.
    // $allCheck = htmlentities($_POST['allCheck']);
    
   // if(isset($_POST['allCheck'])==true){//check if checkbox all choosen.
       // $immediateCheck = 1;
       // $newsCheck= 1;
       // $financialCheck =1;
       // $presentationCheck= 1;  
   // }
   // else if(isset($_POST['allCheck'])==false){
        if(isset($_POST['immediateCheck'])==true){$immediateCheck=1;} else  {$immediateCheck=0;}
        if(isset($_POST['newsCheck'])==true){$newsCheck=1;} else  {$newsCheck=0;}
        if(isset($_POST['financialCheck'])==true){$financialCheck=1;} else  {$financialCheck=0;}
        if(isset($_POST['presentationCheck'])==true){$presentationCheck=1;} else  {$presentationCheck=0;}
      // }
}

    
    //$sql = "INSERT INTO users (emailID,privateName,lastName,companyName) VALUES ('$val1', '$val2', '$val3', '$val4')";
    

$sql = "SELECT emailID FROM users";
$result = $conn->query($sql);

$flag=0;//checks if the emailID already exists.


//if the emailID exist flag=1,else flag=0.
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        if($email==$row['emailID']){           
            $flag=1;
        }   
   }
}






if($flag==0){
    
    
    
    //$sql = "INSERT INTO users (emailID,privateName,lastName,companyName,country,weeklyflag,dailyflag,financialReports,presentation,news,immediateReports,changingPercent,PercentChangingOfStockPrice) VALUES ('$email', '$inp1', '$pName2', '$cName','$country',$weeklySummaryStock, $dailySummaryStock, $financialCheck, $presentationCheck, $newsCheck, $immediateCheck, $percent, $stockThresholdAlert)";
    
    //new 07/02/2018
    $sql = "INSERT INTO users (emailID,privateName,lastName,companyName,country,weeklyflag,dailyflag,financialReports,presentation,news,immediateReports,changingPercent,PercentChangingOfStockPrice,sendToEmail) VALUES ('$email', '$inp1', '$pName2', '$cName','$country',$weeklySummaryStock, $dailySummaryStock, $financialCheck, $presentationCheck, $newsCheck, $immediateCheck, $percent, $stockThresholdAlert,0)";
    //END new 07/02/2018
    
    $i=0;
  
    
    if ($conn->query($sql)=== TRUE) {
        
        #Receiver full name for email content
        $nameString=$pName2." ".$inp1;
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
					  <title>Unsubscribed Successfully</title>
					  
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
								   
								   
					<div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling Mail Alert : Registered Successfully</div>
					<br><br>
					
					<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
					  Hi $nameString
					  <br><br>
					  You have been successfully registered to Delek Drilling investor relations mail alert.
					  <br>
					  
					  <br><br>
							<!--Posts Content here-->
							<!--Posts Content here-->
							<!--Posts Content here-->
							<!--Posts Content here-->
							
					<br>
					<div align="left" style="margin:0 auto; width:100%; text-align:left; font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;">
					<br>Thank you, <br>
					Delek Drilling IR Team.
					</div>
					
					<br>
								<br><br><div align="center" style="margin:0 auto; width:100%; text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;color:#333;">
								Delek Drilling, Abba Eban 19, Herzelia Pituh
								<br>
								
							  <br>Don't like these emails? <a href="https://www.delekdrilling.co.il/en/investor-relations/unsubscribe" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">Unsubscribe</a>.
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
        'from'    => 'delekdrilling@irwebsites.co.il',
        'to'      =>  $email,
        'subject' => 'Delek Drilling: Registration Complete',
        'html'    => $htmlBodyPosts1
        ));
        
        $i++;
        

        @header("location:reg-success-he.html");
        
    } else {
        @header("location:failed.html");
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
      

}
else { 
    @header("location:EmailExist.html");
    echo '<div class="alert alert-success" style="color:red;margin-left:40%;"> <strong>׳›׳×׳•׳‘׳× ׳”׳“׳•׳�"׳� ׳›׳‘׳¨ ׳§׳™׳™׳�׳× ׳‘׳�׳¢׳¨׳›׳×</strong>׳�׳ ׳� ׳ ׳¡׳” ׳©׳•׳‘ ׳¢׳� ׳›׳×׳•׳‘׳× ׳©׳•׳ ׳”...</div>';
       }
    //then you can use them in a PHP function.
    //$result = myFunction($val1, $val2);
//}

//echo "End";



$conn->close();



?>





