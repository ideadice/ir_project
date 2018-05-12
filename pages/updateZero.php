<?php

#Connect to DB
include "/code/mysql/database.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sqlSelect="SELECT emailID FROM users where sendToEmail=1";


//START check of update query

$resultChange = $conn->query($sqlSelect);
//if ($conn->query($sqlSelect) === TRUE) {

if ($resultChange->num_rows > 0) {
    
    
    while($row = $resultChange->fetch_assoc()){
        
        
        $sqlUpdateSendToMail="update users set sendToEmail=0 where emailID='".$row["emailID"]."'";
        //START check of update query
        
        
        if ($conn->query($sqlUpdateSendToMail) === TRUE) {
            echo "update send to email zero successfully";
        } else {
            echo "Error:update the zero" . $sqlUpdateSendToMail . "<br>" . $conn->error;
        }
        //END check of update query
    
        
    }
}
else {
    echo "Error:select the zero" . $sqlSelect . "<br>" . $conn->error;
}

//END check of update query





?>