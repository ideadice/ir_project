/**Javascript - api json call functions **/

//API Call - 15 minutes interval datasets 

//START - Daily mail API pull

var price;
var change;
var percentChange;
var volume;
var WeekHigh52;
var WeekLow52;
var dayhigh;
var daylow;
var todaysopen;
var previousclose;


function getNewData() {

var settings = {
		  "async": true,
		  "crossDomain": true,
		  "url": "https://api.gto.co.il:9005/V2/json/login",
		  "method": "POST",
		  "headers": {
		    "Content-Type": "application/json",
		    "Cache-Control": "no-cache"
		  },
		  "processData": false,
		  "data": "{\n\t\"Login\": {\n\t\t\n\t\t\"User\":\"apizvi01\",\n\t\t\"Password\":\"12345\"\n\t\n\t}\n}"
		}


	document.getElementById("test0011").innerHTML = "before ajax";
	
	
	$.ajax(settings).done(function (response) {
		  console.log(response);
		});

	document.getElementById("test0012").innerHTML = "Finished ajax";
	
	
}



function getDailyDatasets() {
    var callUrl = "https://api.stockdio.com/data/financial/prices/v1/getlatestpricesex";
    var dataParam = "app-key=F2A5C7076BE141C79350BFA00AE3EC4B&stockExchange=TASE&symbols=DEDR.L;";
    
    $.ajax({
        type: "GET",
        url: callUrl,
        data: dataParam,
        dataType: "json",
        success: function (data) {

			//document.getElementById("price").innerHTML = data.data.prices.values[0].slice(2, 3); 
			document.getElementById("change").innerHTML = data.data.prices.values[0].slice(3, 4); 
			document.getElementById("percentChange").innerHTML = data.data.prices.values[0].slice(4, 5); 
			document.getElementById("volume").innerHTML = data.data.prices.values[0].slice(7, 8);
			
			price=data.data.prices.values[0].slice(2, 3).toString();
			change=data.data.prices.values[0].slice(3, 4).toString(); 
			percentChange=data.data.prices.values[0].slice(4, 5).toString();
			volume=data.data.prices.values[0].slice(7, 8).toString();
			
			document.getElementById("price").innerHTML = price;
			
			GetStocksSnapshot();
			
			
        },

        error: function (xhr, ajaxOptions, thrownError) {

        }

    });

}
    
function GetStocksSnapshot() {
    var callUrl = "https://api.stockdio.com/data/financial/prices/v1/GetStocksSnapshot";
    var dataParam = "app-key=F2A5C7076BE141C79350BFA00AE3EC4B&stockExchange=TASE&symbols=DEDR.L";
    
    $.ajax({
        type: "GET",
        url: callUrl,
        data: dataParam,
        dataType: "json",
        success: function (data) {

			document.getElementById("52WeekHigh").innerHTML = data.data.values[0].slice(16, 17);
			document.getElementById("52WeekLow").innerHTML = data.data.values[0].slice(18, 19); 
			document.getElementById("dayhigh").innerHTML = data.data.values[0].slice(9, 10);
			document.getElementById("daylow").innerHTML = data.data.values[0].slice(10, 11);
			document.getElementById("todaysopen").innerHTML = data.data.values[0].slice(8, 9);
			document.getElementById("previousclose").innerHTML = data.data.values[0].slice(12, 13);
		
			WeekHigh52=data.data.values[0].slice(16, 17).toString();
			WeekLow52=data.data.values[0].slice(18, 19).toString();
			dayhigh=data.data.values[0].slice(9, 10).toString();
			daylow=data.data.values[0].slice(10, 11).toString();
			todaysopen=data.data.values[0].slice(8, 9).toString();
			previousclose=data.data.values[0].slice(12, 13).toString();
			
			insertdbDailyFunc();

        },

        error: function (xhr, ajaxOptions, thrownError) {

        }

    });

}

//END - Daily mail API pull


//START - Weekly

//Init variables
//5
var ThursdayOpeningPrice;
var ThursdayLastTrade;
var ThursdayPreviousClose;
var ThursdayDayHigh;
var ThursdayDayLow;
var ThursdayVolume;
//4
var WednesdayOpeningPrice;
var WednesdayLastTrade;
var WednesdayPreviousClose;
var WednesdayDayHigh;
var WednesdayDayLow;
var WednesdayVolume;
//3
var TuesdayOpeningPrice;
var TuesdayLastTrade;
var TuesdayPreviousClose;
var TuesdayDayHigh;
var TuesdayDayLow;
var TuesdayVolume;
//2
var MondayOpeningPrice;
var MondayLastTrade;
var MondayPreviousClose;
var MondayDayHigh;
var MondayDayLow;
var MondayVolume;
//1
var SundayOpeningPrice;
var SundayLastTrade;
var SundayPreviousClose;
var SundayDayHigh;
var SundayDayLow;
var SundayVolume;

//init date objects
var datesarr = [];
var initdate = [];
var api = [];
var callUrlWeek = "https://api.stockdio.com/data/financial/prices/v1/GetIntradayPricesEx";

function getWeeklyDatasets() {

//START DATES operations
    var d = new Date();
    document.getElementById("datetoday").innerHTML = 'Today is: ' + d.toISOString();

    var j=0;
    
    for(j=0;j<5;j++){
    	initdate[j]=new Date();
    	//construct the date for each day
    	initdate[j].setDate(initdate[j].getDate() - j);
    	
    	if((initdate[j].getMonth()+1)<10)
    	{
    		if((initdate[j].getDate())<10){
    			datesarr[j]=initdate[j].getFullYear().toString() + '-0' + (initdate[j].getMonth()+1).toLocaleString() + '-0' + initdate[j].getDate().toString();
    		}
    		else{
    			datesarr[j]=initdate[j].getFullYear().toString() + '-0' + (initdate[j].getMonth()+1).toLocaleString() + '-' + initdate[j].getDate().toString();
    		}
    		
    	}
    	else
    	{
    		if((initdate[j].getDate())<10){
    			datesarr[j]=initdate[j].getFullYear().toString() + '-' + (initdate[j].getMonth()+1).toLocaleString() + '-0' + initdate[j].getDate().toString();
    		}
    		else{
    			datesarr[j]=initdate[j].getFullYear().toString() + '-' + (initdate[j].getMonth()+1).toLocaleString() + '-' + initdate[j].getDate().toString();
    		}
    		
    	}
    	
    	//construct the api code for each day
    	api[j]='app-key=F2A5C7076BE141C79350BFA00AE3EC4B&stockExchange=TASE&symbol=DEDR.L&includeColumnNames=false&from='+datesarr[j]+'&to='+datesarr[j];
    }
    document.getElementById("datenew").innerHTML=datesarr.toString();
    document.getElementById("dateapi0").innerHTML=api[0];
    document.getElementById("dateapi1").innerHTML=api[1];
    document.getElementById("dateapi2").innerHTML=api[2];
    document.getElementById("dateapi3").innerHTML=api[3];
    document.getElementById("dateapi4").innerHTML=api[4];
    
//END DATES operations
    
    dayA();
}

function dayA() {
	document.getElementById("vlad1").innerHTML="into day A";
    $.ajax({
        type: "GET",
        url: callUrlWeek,
        data: api[4],
        dataType: "json",
        success: function (data) {
        	
        	if(data.data.open=='-1')
        		{
        			SundayOpeningPrice="Holiday";
        			SundayLastTrade="Holiday";
        			SundayPreviousClose="Holiday";
        			SundayDayHigh="Holiday";
        			SundayDayLow="Holiday";
        			SundayVolume="Holiday";
        		}
        	else{
	            	SundayOpeningPrice=data.data.open;
	            	SundayLastTrade=data.data.close;
	            	SundayPreviousClose=data.data.previousClose;
	            	SundayDayHigh=data.data.high;
	            	SundayDayLow=data.data.low;
	            	SundayVolume=data.data.volume;
        		}

        	//debug
			document.getElementById("open1").innerHTML = SundayOpeningPrice;
			
			//Next day in chain
			dayB();
        },
        error: function (xhr, ajaxOptions, thrownError) {
        }
    });
}

function dayB() {
    $.ajax({
        type: "GET",
        url: callUrlWeek,
        data: api[3],
        dataType: "json",
        success: function (data) {
        	
        	if(data.data.open=='-1')
    		{
	            	MondayOpeningPrice="Holiday";
	            	MondayLastTrade="Holiday";
	            	MondayPreviousClose="Holiday";
	            	MondayDayHigh="Holiday";
	            	MondayDayLow="Holiday";
	            	MondayVolume="Holiday";
    		}
        	else{
	            	MondayOpeningPrice=data.data.open;
	            	MondayLastTrade=data.data.close;
	            	MondayPreviousClose=data.data.previousClose;
	            	MondayDayHigh=data.data.high;
	            	MondayDayLow=data.data.low;
	            	MondayVolume=data.data.volume;
        	}
		

        	//debug
        	document.getElementById("open2").innerHTML = MondayOpeningPrice;
			
			//Next day in chain
        	dayC();
        },
        error: function (xhr, ajaxOptions, thrownError) {
        }
    });
}

function dayC() {
    $.ajax({
        type: "GET",
        url: callUrlWeek,
        data: api[2],
        dataType: "json",
        success: function (data) {
        	
        	if(data.data.open=='-1')
    		{
	            	TuesdayOpeningPrice="Holiday";
	            	TuesdayLastTrade="Holiday";
	            	TuesdayPreviousClose="Holiday";
	            	TuesdayDayHigh="Holiday";
	            	TuesdayDayLow="Holiday";
	            	TuesdayVolume="Holiday";
    		}
        	else{
	            	TuesdayOpeningPrice=data.data.open;
	            	TuesdayLastTrade=data.data.close;
	            	TuesdayPreviousClose=data.data.previousClose;
	            	TuesdayDayHigh=data.data.high;
	            	TuesdayDayLow=data.data.low;
	            	TuesdayVolume=data.data.volume;
        	}
        	

        	//debug
        	document.getElementById("open3").innerHTML = TuesdayOpeningPrice;
			
			//Next day in chain
        	dayD();
        },
        error: function (xhr, ajaxOptions, thrownError) {
        }
    });
}

function dayD() {
    $.ajax({
        type: "GET",
        url: callUrlWeek,
        data: api[1],
        dataType: "json",
        success: function (data) {
        	
        	if(data.data.open=='-1')
    		{
	            	WednesdayOpeningPrice="Holiday";
	            	WednesdayLastTrade="Holiday";
	            	WednesdayPreviousClose="Holiday";
	            	WednesdayDayHigh="Holiday";
	            	WednesdayDayLow="Holiday";
	            	WednesdayVolume="Holiday";
    		}
        	else{
	            	WednesdayOpeningPrice=data.data.open;
	            	WednesdayLastTrade=data.data.close;
	            	WednesdayPreviousClose=data.data.previousClose;
	            	WednesdayDayHigh=data.data.high;
	            	WednesdayDayLow=data.data.low;
	            	WednesdayVolume=data.data.volume;
        	}
        	

        	//debug
        	document.getElementById("open4").innerHTML = WednesdayOpeningPrice;
        	
			//Next day in chain
        	document.getElementById("test100").innerHTML = "Going to dayE";
        	dayE();
        },
        error: function (xhr, ajaxOptions, thrownError) {
        }
    });
}

function dayE() {
	document.getElementById("test101").innerHTML = "Into dayE";
    $.ajax({
        type: "GET",
        url: callUrlWeek,
        data: api[0],
        dataType: "json",
        success: function (data) {
        	
        	if(data.data.open=='-1')
    		{
        		document.getElementById("test102").innerHTML = "into if";
	    			ThursdayOpeningPrice="Holiday";
	    			ThursdayLastTrade="Holiday";
	    			ThursdayPreviousClose="Holiday";
	    			ThursdayDayHigh="Holiday";
	    			ThursdayDayLow="Holiday";
	    			ThursdayVolume="Holiday";
    		}
        	else{
        		document.getElementById("test103").innerHTML = "into else";
	    			ThursdayOpeningPrice=data.data.open;
	    			ThursdayLastTrade=data.data.close;
	    			ThursdayPreviousClose=data.data.previousClose;
	    			ThursdayDayHigh=data.data.high;
	    			ThursdayDayLow=data.data.low;
	    			ThursdayVolume=data.data.volume;
        	}


        	//debug
			document.getElementById("open5").innerHTML = ThursdayOpeningPrice;
			
			//Send Email
			sendWeeklyEmail();
        },
        error: function (xhr, ajaxOptions, thrownError) {
        }
    });

}

//END - Weekly