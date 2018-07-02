



function loadFunction(){
	// var json=$.getJSON("./pages/json/countryJSON.json");
	//var json = require('./pages/json/countryJSON.json');
	//var data= JSON.parse(json);
	//alert(data.length);
	
    var x = document.getElementById("sel1"); 
    var arr=['Israel',
    	'Afghanistan',
    	'Albania',
    	'Algeria',
    	'American Samoa',
    	'Andorra',
    	'Angola',
    	'Anguilla',
    	'Antarctica',
    	'Antigua And Barbuda',
    	'Argentina',
    	'Armenia',
    	'Aruba',
    	'Australia',
    	'Austria',
    	'Azerbaijan',
    	'Bahamas',
    	'Bahrain',
    	'Bangladesh',
    	'Barbados',
    	'Belarus',
    	'Belgium',
    	'Belize',
    	'Benin',
    	'Bermuda',
    	'Bhutan',
    	'Bolivia',
    	'Bosnia And Herzegovina',
    	'Botswana',
    	'Bouvet Island',
    	'Brazil',
    	'British Indian Ocean Territory',
    	'Brunei Darussalam',
    	'Bulgaria',
    	'Burkina Faso',
    	'Burundi',
    	'Cambodia',
    	'Cameroon',
    	'Canada',
    	'Cape Verde',
    	'Cayman Islands',
    	'Central African Republic',
    	'Chad',
    	'Chile',
    	'China',
    	'Christmas Island',
    	'Cocos (Keeling) Islands',
    	'Colombia',
    	'Comoros',
    	'Congo',
    	'Congo',
    	'Cook Islands',
    	'Costa Rica',
    	'CÃ´te dIvoire',
    	'Croatia',
    	'Cuba',
    	'Cyprus',
    	'Czech Republic',
    	'Denmark',
    	'Djibouti',
    	'Dominica',
    	'Dominican Republic',
    	'Ecuador',
    	'Egypt',
    	'El Salvador',
    	'Equatorial Guinea',
    	'Eritrea',
    	'Estonia',
    	'Ethiopia',
    	'Falkland Islands (Malvinas)',
    	'Faroe Islands',
    	'Fiji',
    	'Finland',
    	'France',
    	'French Guiana',
    	'French Polynesia',
    	'French Southern Territories',
    	'Gabon',
    	'Gambia',
    	'Georgia',
    	'Germany',
    	'Ghana',
    	'Gibraltar',
    	'Greece',
    	'Greenland',
    	'Grenada',
    	'Guadeloupe',
    	'Guam',
    	'Guatemala',
    	'guernsey',
    	'Guinea',
    	'Guinea-Bissau',
    	'Guyana',
    	'Haiti',
    	'Heard Island And McDonald Islands',
    	'Holy See (Vatican City State)',
    	'Honduras',
    	'Hong Kong',
    	'Hungary',
    	'Iceland',
    	'India',
    	'Indonesia',
    	'Iran',
    	'Iraq',
    	'Ireland',
    	'Isle Of Man',
    	'Israel',
    	'Italy',
    	'Jamaica',
    	'Japan',
    	'Jersey',
    	'Jordan',
    	'Kazakhstan',
    	'Kenya',
    	'Kiribati',
    	'Korea',
    	'Korea',
    	'Kuwait',
    	'Kyrgyzstan',
    	'Lao PeopleS Democratic Republic',
    	'Latvia',
    	'Lebanon',
    	'Lesotho',
    	'Liberia',
    	'Libyan Arab Jayasuriya',
    	'Liechtenstein',
    	'Lithuania',
    	'Luxembourg',
    	'Macao',
    	'Macedonia',
    	'Madagascar',
    	'Malawi',
    	'Malaysia',
    	'Maldives',
    	'Mali',
    	'Malta',
    	'Marshall Islands',
    	'Martinique',
    	'Mauritania',
    	'Mauritius',
    	'Mayotte',
    	'Mexico',
    	'Micronesia',
    	'Moldova',
    	'Monaco',
    	'Mongolia',
    	'Montenegro',
    	'Montserrat',
    	'Morocco',
    	'Mozambique',
    	'Myanmar',
    	'Namibia',
    	'Nauru',
    	'Nepal',
    	'Netherlands',
    	'Netherlands Antilles',
    	'New Caledonia',
    	'New Zealand',
    	'Nicaragua',
    	'Niger',
    	'Nigeria',
    	'Niue',
    	'Norfolk Island',
    	'Northern Mariana Islands',
    	'Norway',
    	'Oman',
    	'Pakistan',
    	'Palau',
    	'Palestinian Territory',
    	'Panama',
    	'Papua New Guinea',
    	'Paraguay',
    	'Peru',
    	'Philippines',
    	'Pitcairn',
    	'Poland',
    	'Portugal',
    	'Puerto Rico',
    	'Qatar',
    	'RÃ©union',
    	'Romania',
    	'Russian Federation',
    	'Rwanda',
    	'Saint BarthÃ©lemy',
    	'Saint Helena',
    	'Saint Kitts And Nevis',
    	'Saint Lucia',
    	'Saint Martin',
    	'Saint Pierre And Miquelon',
    	'Saint Vincent And The Grenadines',
    	'Samoa',
    	'San Marino',
    	'Sao Tome And Principe',
    	'Saudi Arabia',
    	'Senegal',
    	'Serbia',
    	'Seychelles',
    	'Sierra Leone',
    	'Singapore',
    	'Slovakia',
    	'Slovenia',
    	'Solomon Islands',
    	'Somalia',
    	'South Africa',
    	'South Georgia And The South Sandwich Islands',
    	'Spain',
    	'Sri Lanka',
    	'Sudan',
    	'Suriname',
    	'Svalbard And Jan Mayen',
    	'Swaziland',
    	'Sweden',
    	'Switzerland',
    	'Syrian Arab Republic',
    	'Taiwan',
    	'Tajikistan',
    	'Tanzania',
    	'Thailand',
    	'Timor-Leste',
    	'Togo',
    	'Tokelau',
    	'Tonga',
    	'Trinidad And Tobago',
    	'Tunisia',
    	'Turkey',
    	'Turkmenistan',
    	'Turks And Caicos Islands',
    	'Tuvalu',
    	'Uganda',
    	'Ukraine',
    	'United Arab Emirates',
    	'United Kingdom',
    	'United States Of America',
    	'United States Minor Outlying Islands',
    	'Uruguay',
    	'Uzbekistan',
    	'Vanuatu',
    	'Venezuela',
    	'Viet nam',
    	'Virgin Islands',
    	'Virgin Islands',
    	'Wallis And Futuna',
    	'Western Sahara',
    	'Yemen',
    	'Zambia',
    	'Zimbabwe',];
    var option;
    for(var i=0;i<arr.length;i++){
    option = document.createElement("option");
      option.text = arr[i];
    x.add(option);
    } 
	
}

function showHint(str) {
    if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "checkForEmailExist.php?q="+str, true);
        xmlhttp.send();
    }
    
    //document.getElementById("txtHint").innerHTML = "noooooo";
    
}







//Registration form functions

//var text=document.getElementById("errorEmail"); 


//function myFunction(){
	
	//document.getElementById("icon").style.display="none"; 	
	
	
//}


//function checkEmail() {

  //  var email = document.getElementById('email');//Point on input type=email 
 //   var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
     
  //   var icon=document.getElementById("icon");
   // if (!filter.test(email.value)) {
  //   text.innerHTML="Invalid Input, For Example:xxxx@yyy.com";
   //  text.style.color="red";
     
  //text.innerHTML=document.getElementById("MyElement").className;
   // alert('Please provide a valid email address');
  //  email.focus;
   
  //  checkIfEmpty(email);
    
//  }
	  
//}


//function checkIfEmpty(email){
	
	//if(email.value==" ") {
	//	text.innerHTML=" ";
	//}	
//}

//calculate the percent 
var inputPoint=document.getElementById('percent');
var count= 0;


function minusFunction(){
	
    if(count>0){
    	count=count-1;
    	inputPoint.value=count+"%";
        }
	
}


function plusFunction(){
	
    count=count+1;
    inputPoint.value=count+"%";

}
//END calculate the percent


var all = document.getElementById("allCheck");
var immCheck = document.getElementById("immediateCheck");
var newsCheck = document.getElementById("newsCheck");
var financialCheck = document.getElementById("financialCheck");
var presentationCheck= document.getElementById("presentationCheck");



function myFunction() {
    
   if(all.checked==true){
     immCheck.checked=true;
     newsCheck.checked=true;
    financialCheck.checked=true;
    presentationCheck.checked=true;
   } 
     //  else if(all.checked==false){
    	     //immCheck.checked=false;
    	     //newsCheck.checked=false;
    	    // financialCheck.checked=false;
    	    // presentationCheck.checked=false;  
   // }
   
}












