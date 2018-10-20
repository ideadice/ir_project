<?php


/*update zero file created for update the flag of sending mails to users*/



#Connect to DB
include "/code/mysql/database.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


     /*Update if sendToEmail=1 flag to sendToEmail=0  */ 
$sqlUpdateSendToMail="update users set sendToEmail=0 where sendToEmail=1";
        if ($conn->query($sqlUpdateSendToMail) === TRUE) {
            echo "update send to email zero successfully";
        } else {
            echo "Error:update the zero" . $sqlUpdateSendToMail . "<br>" . $conn->error;
        }
      
        /*END Update if sendToEmail=1 flag to sendToEmail=0  */ 

?>