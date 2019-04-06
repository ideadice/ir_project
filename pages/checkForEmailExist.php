<?php


#Connect to DB
include "/code/mysql/database.php";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT emailID FROM users";
$result = $conn->query($sql);

// get the q parameter from URL
$q = $_REQUEST["q"];

$hint = "";


if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
          //  echo "<br/>".$row["emailID"];
            $email=$row["emailID"];
    //foreach($a as $name) {
            //if (stristr($q, substr($email, 0, $len))) {
            if($q==$row["emailID"]){
            if ($hint === "") {
                $hint = $email;
            } else {
                $hint .= ", $email";
            }
        }
     }
   // }
  }
}
// Output "no suggestion" if no hint was found or output correct values
//echo $hint="timorrrr";
echo $hint === "" ? "" :"This email already exists! ".$hint;

$conn->close();

?>