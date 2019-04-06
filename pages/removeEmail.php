<?php

#Connect to DB
include "/code/mysql/database.php";


#Use mailgun library installed on server
require '/var/www/html/mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;
$domain = "irwebsites.co.il";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$email = htmlentities($_POST['email']);
$email=htmlspecialchars(strip_tags($email));
// sanitize email
$email=filter_var($email,FILTER_SANITIZE_EMAIL);

$queryDetails="select emailID,privateName,lastName from users where emailID=?";

$stat=$conn->prepare($queryDetails);
$stat->bind_param("s",$email);
$stat->execute();
$stat->bind_result($emailID,$privateName,$lastName);
$stat->fetch();
$userName=$lastName." ".$privateName;
if($emailID<>''){
	
$query = "delete from users where emailID=?";
$stat->close();
// prepare query 
$stmt = $conn->prepare($query);
// bind values
$stmt->bind_param("s",$email);

	if(!filter_var($email,FILTER_VALIDATE_EMAIL)===false){
    
    
    if ($stmt->execute()===TRUE) {
        $i=0; 
        
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
								   
								   
					<div class="title" style="text-align:center; font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Delek Drilling Mail Alert : Unsubscribed Successfully</div>
					<br><br>
					
					<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
					  Hi $userName
					  <br><br>
					  You have been successfully removed from this subscriber list.
					  <br>
					   You will no longer hear from us.
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
								
							  <br> Did you unsubscribe by accident? <a href="http://ir.delekdrilling.co.il/alert/" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">Re-subscribe</a>.
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
            'to'      =>  $email,
            'subject' => 'Delek Drilling: Unsubscribe Successfully',
                'html'    => $htmlBodyPosts1
            ));
        
        
        $i++; 
        
        //success deletee
				@header("location:Unsubscribe-success.html");         
        //echo '<div class="alert alert-success" style="color:green;margin-left:40%;"> <strong>Delete Email Success!</strong></div>';
        
    }
    else {
        //don't success delelte
        @header("location:failed.html");
       // echo '<div class="alert alert-success" style="color:red;margin-left:40%;"> <strong>Delete Mail Failed!</strong></div>';
    }
    
    
}
}
else { //email don't exist}
		@header("location:email-doesnt-exist.html");

    //echo '<div class="alert alert-success" style="color:red;margin-left:40%;"> <strong>Email Doesnt Exist!</strong></div>';
    
    
}




















//if ($result->num_rows > 0) {
//    while($row = $result->fetch_assoc()){
//        echo "<br/>".$row["emailID"];
//    }
//}




?>