<?php
    
#Daily API pull - Update database

    #Credentials
    #TODO: Hide credentials
    
    #Connect to DB
    include "/code/mysql/database.php";
    
    $conn = new mysqli($servername, $username, $password, $dbname); // Create connection
    if ($conn->connect_error) {     // Check connection
        die("Connection failed: " . $conn->connect_error);
    }
    
    #Get POST data from serverjs -> insertdbDailyFunc()
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $change = mysqli_real_escape_string($conn, $_POST['change']);
    $pChange = mysqli_real_escape_string($conn, $_POST['percentChange']);
    $volume = mysqli_real_escape_string($conn, $_POST['volume']);
    $WeekHigh52 = mysqli_real_escape_string($conn, $_POST['WeekHigh52']);
    $WeekLow52 = mysqli_real_escape_string($conn, $_POST['WeekLow52']);
    $dayhigh = mysqli_real_escape_string($conn, $_POST['dayhigh']);
    $daylow = mysqli_real_escape_string($conn, $_POST['daylow']);
    $todaysopen = mysqli_real_escape_string($conn, $_POST['todaysopen']);
    $previousclose = mysqli_real_escape_string($conn, $_POST['previousclose']);
    
    #SQL Query
    
    $sql = "INSERT INTO dailyDatasets (id,price,pChange,volume,weekhigh,weeklow,dayhigh,daylow,todaysopen,previousclose,changing)
    VALUES ('$id' , '$price' , '$pChange' , '$volume' , '$WeekHigh52' , '$WeekLow52', '$dayhigh' , '$daylow', '$todaysopen' , '$previousclose', '$change')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Page saved to database!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();

?>