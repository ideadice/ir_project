<?php

#Connect to DB
include "/code/mysql/database.php";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// get the q parameter from URL
//$q = $_REQUEST["q"];
$email = htmlentities($_POST['email']);
//echo "email is:".$q;
echo "email is:".$email;




//$email=$q;

$sqlCheck="select emailID from users where emailID='".$email."'";

$result = $conn->query($sqlCheck);
$flag=0;//checks if the emailID already exists.

//if the emailID exist flag=1,else flag=0.
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        if($email==$row['emailID']){
            $flag=1;
        }
    }
}




if($flag==1){
    
    $sql="delete from users where emailID='".$email."'";
    
    if ($conn->query($sql)=== TRUE) {
        
        //success deletee
        @header("location:Unsubscribe-success-he.html");

        
        
        //echo '<div class="alert alert-success" style="color:green;margin-left:40%;"> <strong>Delete Email Success!</strong></div>';
        
    }
    else {
        //don't success delelte
        @header("location:failed-he.html");
       // echo '<div class="alert alert-success" style="color:red;margin-left:40%;"> <strong>Delete Mail Failed!</strong></div>';
    }
    
    
}
else { //email don't exist}
    @header("location:email-doesnt-exist-he.html");
    //echo '<div class="alert alert-success" style="color:red;margin-left:40%;"> <strong>Email Doesnt Exist!</strong></div>';
    
    
}




















//if ($result->num_rows > 0) {
//    while($row = $result->fetch_assoc()){
//        echo "<br/>".$row["emailID"];
//    }
//}




?>