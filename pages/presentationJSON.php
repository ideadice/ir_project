<?php




$jsonPresentation=file_get_contents('http://ir.delekdrilling.co.il/wp-json/wp/v2/reports/');


$json_data_presentation=json_decode($jsonPresentation,true);
$lengthPresentationJSON = sizeof($json_data_presentation);

echo "<br/>the presentation length is:".$lengthPresentationJSON;




//****Print on the screen the results of the json file for tests***
echo '<table style="border: 1px solid black;">';
echo '<tr>';
echo '<th>id</th>';
echo '<th>date</th>';
echo '<th>title</th>';
echo '<th>link</th>';
echo '</tr>';

for($j=0;$j<$lengthPresentationJSON;$j++)
{
    echo '<tr>';
    for($i=0;$i<1;$i++)
    {
        echo '<td style="border: 1px solid black;">'.$json_data_presentation[$j]["id"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data_presentation[$j]["date_gmt"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data_presentation[$j]["title"]["rendered"].'</td>';
        echo '<td style="border: 1px solid black;">'.$json_data_presentation[$j]["link"].'</td>';
    }
    echo '</tr>';
}
echo '</table>';

//****END print on the screen the results of the json file for tests***



//****start presentation****

//Presentation variable
$presMaxID=2933;
$presDateMax="2017-12-26T17:46:38";
$cntpres=0;
$presMaxTODB=$presMaxID;
//END Presentation variable




$str1=$json_data_presentation[2]["title"]["rendered"];

if(preg_match("/\p{Hebrew}/u",$str1)==1){
    echo "<br/>id.hebrew".$json_data_presentation[2]["id"]."  the title is:".$json_data_presentation[2]["title"]["rendered"];
}
else echo "<br/>id.no hebrew".$json_data_presentation[2]["id"]."  the title is:".$json_data_presentation[2]["title"]["rendered"];

//$date="2017-10-27T08:58:30";

$presIndexArrt=array();//for the index of new posts.

for($y=0;$y<$lengthPresentationJSON;$y++){
    $strPres=$json_data_presentation[$y]["title"]["rendered"];
    $checkNumberPres=preg_match("/\p{Hebrew}/u", $strPres);
    if($presDateMax<$json_data_presentation[$y]["date_gmt"] && $checkNumberPres==0 && $json_data_presentation[$y]["id"]>$presMaxID ){
        
    array_push($presIndexArrt,$y);
    if($json_data_presentation[$y]["id"]>$presMaxTODB){echo "<br/>instert";
    $presMaxTODB=$json_data_presentation[$y]["id"];
    //$presDateMax=$json_data_presentation[$i]["date_gmt"];
    }
    echo "<br/>succesful id.".$json_data_presentation[$y]["id"];  
    
}
else "blattttt";
}

$y=0;
for($t=$presIndexArrt[$y];$t<count($presIndexArrt);$t++){
    echo "<br/>index pres:".$presIndexArrt[$y];
    echo "<br/>".$json_data_presentation[$presIndexArrt[$y]]["id"];
    $y++;
}

echo "<br/>the length of presentation index ttt array is:".count($presIndexArrt);
echo "<br/>the max id:".$presMaxTODB;
echo "<br/>the date is:".$presDateMax;

$presIndexArr=array();//for the index of new posts.
$cnt=0;
//Choosen the new PRESENTATION,consider in english title,id and dates!!
for($i=0;$i<$lengthPresentationJSON;$i++){
    $strPres=$json_data_presentation[$i]["title"]["rendered"];
    $checkNumberPres=preg_match("/\p{Hebrew}/u", $strPres);
    if($presDateMax<$json_data_presentation[$i]["date_gmt"] && $checkNumberPres==0 && $json_data_presentation[$i]["id"]>$presMaxID )
    {
        $cnt=$cnt+1;
        array_push($presIndexArr,$i);//get the index of the new posts.
        if( $json_data_presentation[$i]["id"]>$presMaxTODB){//find the max id and date for the next time.
            $presMaxTODB=$json_data_presentation[$i]["id"];
            $presDateMax= $json_data_presentation[$i]["date_gmt"];
        }
        
        $cntpres=$cntres+1;
    }
}
//END choosen the new PRESENTATION,consider in english title,id and dates!!


echo "<br/>the cnt is:".$cnt;

echo "<br/>the length of presentation index array is:".count($presIndexArr)."<br/><br/>";


//print the array of the new reports for send email.
$y=0;
for($t=$presIndexArr[$y];$t<count($presIndexArr);$t++){
    echo "<br/>index pres:".$presIndexArr[$y];
    echo "<br/>".$json_data_presentation[$presIndexArr[$y]]["id"];
    $y++;
}
//END print the array of the new reports.

//***START checks if have new presentation and select the desire users


?>