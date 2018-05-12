<?php

include 'reports.php';

#Connect to DB
include "/code/mysql/database.php";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user="timor";

// Create database
$sql = "SELECT * FROM postsUpdates";

$result = $conn->query($sql);

$x=0;

$a=array();
//array_push($a,"blue","yellow");

$data=array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($a,$row["datemax"],$row["idpost"]);
        $data[] = $row;
        
        $dateMax=$row["datemax"];
        $idPost=$row["idpost"];
        $idMax=$row["idmax"];
        $x++;
        
        echo "<br> id: ". $row["idpost"]. " - idmax: ". $row["idmax"]. " " . $row["datemax"] . "<br>";
        print_r($a);
        $arrlength = count($a);
        echo $arrlength;
        echo $a[0]["idpost"];
        
    }
} else {
    echo "0 results";
}
//foreach ($data as $var){
 // echo $var['idpost']."<br/>";
  //echo $var['idmax']."<br/>";
//}




$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="windows-1255">
<meta http-equiv="content-type" content="text/html" charset="UTF-8">
<title>Insert title here</title>

<script type="text/javascript" src="../src/jsonjs.js"></script>
<script type="text/javascript" src="../src/serverjs.js"></script>

<style>
table, td {
    border: 1px solid black;
}
</style>


</head>
<body >

<!-- <div id="id01"></div>
<div id="id02"></div>
<div id="id03"></div>
<div id="id04"></div>
<div id="id05"></div>
<div id="content"></div> -->

<div id="debug1"></div>
<div id="debug2"></div>
<div id="debug5"></div>
<div id="debug3"></div>
<div id="debug4"></div>
<div id="debug10"></div>
<div id="debug11"></div>

<table id="myTable">
  <tr>
    <th>id</th>
    <th>date</th>
    <th>title</th>
    <th>link</th>
  </tr>
</table>





<script>



var dateMax="<?php echo $dateMax?>";
var idPost="<?php echo $idPost?>";
var maxID="<?php echo $idMax?>";


alert(idPost+"  " +maxID+"  "+ dateMax);





var xmlhttp = new XMLHttpRequest();
var url = "http://ir.delekdrilling.co.il/wp-json/wp/v2/reports/";
var myArr;
xmlhttp.onreadystatechange = function() {
	//document.getElementById("id01").innerHTML = 8;
	myArr = JSON.parse(this.responseText);
	//document.getElementById("id02").innerHTML = myArr.length;

	myFunction(myArr);
    if (this.readyState == 4 && this.status == 200) {
         myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();




function myFunction(arr) {
   var out = "";
   var out1="";
    var i;
    var cnt=0;
   //var maxID=2885;//***insert to db for start checks!!!
   //var dateMax="2017-09-28T06:02:21";//***insert to db for start checks!!!
    var postsArray=[];
    var objRegExp  =/^[a-zA-Z ]+$/;//only english letters.
    //var objRegExpNumbers=/^[0-9]*$/;//only numbers.
    //var objRegExp2=/^[0-9a-zA-Z]+$/;
    var str;
    
    for(i = 0; i < arr.length; i++) {
        out +=  arr[i].id +"			"+arr[i].date_gmt+"				"+arr[i].title.rendered+"			"+arr[i].link+"<br/>";
   }
    
  for(i = 0; i < arr.length; i++) {  //take the new posts,consider in english title,id and dates!! 
	     str=arr[i].title.rendered;
         str=str.slice(0,8);
    	if(arr[i].id>maxID && arr[i].date_gmt>dateMax && objRegExp.test(str)){
   		postsArray.push(arr[i]);	   	
  	}
            
  }
  
     
   for(i = 0; i <postsArray.length; i++) {//checks for max id and date to the next time.
      
   	if( maxID<postsArray[i].id && postsArray[i].date_gmt>dateMax){
    	 maxID=postsArray[i].id;
         dateMax=postsArray[i].date_gmt;
    	}
    	out1+=  postsArray[i].id +"			"+postsArray[i].date_gmt+"				"+postsArray[i].title.rendered+"			"+postsArray[i].link+"<br/>";
   }



   
   var table = document.getElementById("myTable");
   var row;
   var j=1;
   var cell1;
   var cell2;
   var cell3;
   var cell4;
   for(i=0;i<postsArray.length;i++){
   row = table.insertRow(j);
   cell1 = row.insertCell(0);
   cell2 = row.insertCell(1);
   cell3 = row.insertCell(2);
   cell4 = row.insertCell(3);
   cell1.innerHTML = postsArray[i].id ;
   cell2.innerHTML = postsArray[i].date_gmt;
   cell3.innerHTML = postsArray[i].title.rendered;
   cell4.innerHTML = postsArray[i].link;
   j++;
   }
   
   document.getElementById("debug1").innerHTML = "Before moveToDb";

   moveToDbPosts();
   
   document.getElementById("debug4").innerHTML = "After moveToDb";

}

</script>

</body>
</html>


