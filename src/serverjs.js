/** Functions on datasets for php - mysql **/


// 15 Minutes datasets

var id15 = Date.now();

//Daily dataset

var idDaily = Date.now();

//flag values: 1= insert db, 2= send email

function insertdbDailyFunc() {
    $.post("cronDaily.php",
    {
    	id: idDaily,
        price: price,
        change: change,
        percentChange: percentChange,
        volume: volume,
        WeekHigh52: WeekHigh52,
        WeekLow52: WeekLow52,
        dayhigh: dayhigh,
        daylow: daylow,
        todaysopen: todaysopen,
        previousclose: previousclose,
    },
    function(data,status){
        document.getElementById("saveWarningTextDaily").innerHTML = data;
        $( "#saveWarningTextDaily" ).fadeIn(100);
        setTimeout(function(){ $( "#saveWarningTextDaily" ).fadeOut(100); }, 3000);
    });

    document.getElementById("dbfunc").innerHTML = "server func db - to php post - finished.";

    sendDailyEmail();
}
function sendDailyEmail() {
	
	document.getElementById("mailSend").innerHTML = "JS Mail send function is activated!" ;
	document.getElementById("varChecks").innerHTML = "Price=" + price;
	
	
    $.post("dailyMail.php",
    {
        priceE: price,
        changeE: change,
        percentChangeE: percentChange,
        volumeE: volume,
        WeekHigh52E: WeekHigh52,
        WeekLow52E: WeekLow52,
        dayhighE: dayhigh,
        daylowE: daylow,
        todaysopenE: todaysopen,
        previouscloseE: previousclose,
    },
    function(data,status){
        document.getElementById("mailSender").innerHTML = data;
    });
    
}
// END Daily dataset


// Start Weekly Dataset

function sendWeeklyEmail() {
	
	document.getElementById("weekalert2").innerHTML = "JS sendWeeklyEmail() Mail send function is activated!" ;
	
    $.post("weeklyMail.php",
    {
    	ThursdayOpeningPrice: ThursdayOpeningPrice,
    	ThursdayLastTrade: ThursdayLastTrade,
    	ThursdayPreviousClose: ThursdayPreviousClose,
    	ThursdayDayHigh: ThursdayDayHigh,
    	ThursdayDayLow: ThursdayDayLow,
    	ThursdayVolume: ThursdayVolume,
    	WednesdayOpeningPrice: WednesdayOpeningPrice,
    	WednesdayLastTrade: WednesdayLastTrade,
    	WednesdayPreviousClose: WednesdayPreviousClose,
    	WednesdayDayHigh: WednesdayDayHigh,
    	WednesdayDayLow: WednesdayDayLow,
    	WednesdayVolume: WednesdayVolume,
    	TuesdayOpeningPrice: TuesdayOpeningPrice,
    	TuesdayLastTrade: TuesdayLastTrade,
    	TuesdayPreviousClose: TuesdayPreviousClose,
    	TuesdayDayHigh: TuesdayDayHigh,
    	TuesdayDayLow: TuesdayDayLow,
    	TuesdayVolume: TuesdayVolume,
    	MondayOpeningPrice: MondayOpeningPrice,
    	MondayLastTrade: MondayLastTrade,
    	MondayPreviousClose: MondayPreviousClose,
    	MondayDayHigh: MondayDayHigh,
    	MondayDayLow: MondayDayLow,
    	MondayVolume: MondayVolume,
    	SundayOpeningPrice: SundayOpeningPrice,
    	SundayLastTrade: SundayLastTrade,
    	SundayPreviousClose: SundayPreviousClose,
    	SundayDayHigh: SundayDayHigh,
    	SundayDayLow: SundayDayLow,
    	SundayVolume: SundayVolume,
    },
    function(data,status){
        document.getElementById("mailSenderWeek").innerHTML = data;
    });
    
    document.getElementById("weekalert3").innerHTML = "JS sendWeeklyEmail() Mail send function is finished!" ;
    
}
//END Weekly Dataset


function GetLatestPricesEx() {

    var callUrl = "https://api.stockdio.com/data/financial/prices/v1/getlatestpricesex";
    var dataParam = "app-key=F2A5C7076BE141C79350BFA00AE3EC4B&stockExchange=TASE&symbols=DEDR.L;";
    
    var callUrl2 = "https://api.stockdio.com/data/financial/prices/v1/getlatestpricesex2";
    var dataParam2 = "app-key=F2A5C7076BE141C79350BFA00AE3EC4B&stockExchange=TASE&symbols=DEDR.L;";
    
    
    var percentChangeLIVE;
    var openLIVE;
    var priceLIVE;
    var changeLIVE;
    var volumeLIVE;
   
    
    //get data from 2  api
    $.ajax({
        type: "GET",
        url: callUrl,
        data: dataParam,
        dataType: "json",
        success: function (data) {
        	    priceLIVE=parseFloat(data.data.prices.values[0].slice(2, 3));  
        	    percentChangeLIVE=parseFloat(data.data.prices.values[0].slice(4, 5));
        	    changeLIVE= parseFloat(data.data.prices.values[0].slice(3, 4));
        	    volumeLIVE= parseFloat(data.data.prices.values[0].slice(7, 8));
        	    
        	    
				 //print for tests.       	
				document.getElementById("priceLIVE").innerHTML = data.data.prices.values[0].slice(2, 3);  
				document.getElementById("changeLIVE").innerHTML = data.data.prices.values[0].slice(3, 4);
				document.getElementById("percentChangeLIVE").innerHTML = data.data.prices.values[0].slice(4, 5); 	
				document.getElementById("volumeLIVE").innerHTML = data.data.prices.values[0].slice(7, 8); 
                   ////End print for tests. 
				
				//****START call to second ajax for open live variable.
				$.ajax({
				    type: "GET",
				    url: callUrl2,
				    data: dataParam2,
				    dataType: "json",
				    success: function (data) {
				
				//document.getElementById("openLIVE").innerHTML = data.data.prices.values[0].slice(3, 4); 
				openLIVE=parseFloat(data.data.prices.values[0].slice(3, 4)); 
				document.getElementById("openLIVE").innerHTML =openLIVE;			

			    
			    
			    
			    //START call to php file for sending mail on change.
			    $.post("changeWeekly.php",
			    	    {
			    	percentChangeLIVE: percentChangeLIVE, 
			    	changeLIVE:changeLIVE,
			    	volumeLIVE:volumeLIVE,
			    	 openLIVE: openLIVE,
			    	 priceLIVE:priceLIVE,
			    	    },
			    	    function(data,status){
			    	        document.getElementById("mailSender").innerHTML = data;
			    	    });
			    
			    //END call to php file for sending mail on change.
				
				
				    },
				
				    error: function (xhr, ajaxOptions, thrownError) {
				
				    }
				
				});
				//****END call to second ajax for open live variable.
 
        },

        error: function (xhr, ajaxOptions, thrownError) {
        }
    });

}  


